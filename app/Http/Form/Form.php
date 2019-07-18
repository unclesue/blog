<?php
namespace App\Http\Form;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Relations;
use Illuminate\Support\MessageBag;
use Illuminate\Support\Str;

/**
 * Class Form.
 *
 * @method Text           text($column)
 * @method File           file($column)
 * @method Image          image($column)
 */
class Form implements Renderable
{
    /**
     * Eloquent model of the form.
     *
     * @var Model
     */
    protected $model;

    /**
     * Data for save to current model from input.
     *
     * @var array
     */
    protected $updates = [];

    /**
     * @var Builder
     */
    protected $builder;

    /**
     * Data for save to model's relations from input.
     *
     * @var array
     */
    protected $relations = [];

    /**
     * Input data.
     *
     * @var array
     */
    protected $inputs = [];

    /**
     * Available fields.
     *
     * @var array
     */
    public static $availableFields = [];

    /**
     * Ignored saving fields.
     *
     * @var array
     */
    protected $ignored = [];

    /**
     * @var bool
     */
    protected $isSoftDeletes = false;

    /**
     * Create a new form instance.
     *
     * @param $model
     */
    public function __construct($model)
    {
        $this->model = $model;

        $this->builder = new Builder($this);

        Form::registerBuiltinFields();

        $this->isSoftDeletes = in_array(SoftDeletes::class, class_uses_deep($this->model));
    }

    /**
     * @return Model
     */
    public function model()
    {
        return $this->model;
    }

    /**
     * Store a new record.
     */
    public function store()
    {
        $data = request()->all();

        // Handle validation errors.
        if ($validationMessages = $this->validationMessages($data)) {
            return back()->withInput()->withErrors($validationMessages);
        }

        $this->prepare($data);

        DB::transaction(function () {
            $inserts = $this->prepareInsert($this->updates);

            foreach ($inserts as $column => $value) {
                $this->model->setAttribute($column, $value);
            }

            $this->model->save();

            $this->updateRelation($this->relations);
        });

        return $this->redirectAfterStore();
    }

    /**
     * Handle update.
     *
     * @param int  $id
     * @param null $data
     *
     * @return bool|mixed
     */
    public function update($id, $data = null)
    {
        $data = ($data) ?: request()->all();

        $builder = $this->model;

        if ($this->isSoftDeletes) {
            $builder = $builder->withTrashed();
        }

        $this->model = $builder->with($this->getRelations())->findOrFail($id);

        $this->setFieldOriginalValue();

        // Handle validation errors.
        if ($validationMessages = $this->validationMessages($data)) {
            return back()->withInput()->withErrors($validationMessages);
        }

        $this->prepare($data);

        DB::transaction(function () {
            $updates = $this->prepareUpdate($this->updates);

            foreach ($updates as $column => $value) {
                $this->model->setAttribute($column, $value);
            }

            $this->model->save();

            $this->updateRelation($this->relations);
        });

        return $this->redirectAfterUpdate($id);
    }

    /**
     * Get validation messages.
     *
     * @param array $input
     *
     * @return MessageBag|bool
     */
    public function validationMessages($input)
    {
        $failedValidators = [];

        /** @var Field $field */
        foreach ($this->builder->fields() as $field) {
            if (!$validator = $field->getValidator($input)) {
                continue;
            }

            if (($validator instanceof Validator) && !$validator->passes()) {
                $failedValidators[] = $validator;
            }
        }

        $message = $this->mergeValidationMessages($failedValidators);

        return $message->any() ? $message : false;
    }

    /**
     * Get all relations of model from callable.
     *
     * @return array
     */
    public function getRelations()
    {
        $relations = $columns = [];

        /** @var Field $field */
        foreach ($this->builder->fields() as $field) {
            $columns[] = $field->column();
        }

        foreach (Arr::flatten($columns) as $column) {
            if (Str::contains($column, '.')) {
                list($relation) = explode('.', $column);

                if (method_exists($this->model, $relation) &&
                    $this->model->$relation() instanceof Relations\Relation
                ) {
                    $relations[] = $relation;
                }
            } elseif (method_exists($this->model, $column) &&
                !method_exists(Model::class, $column)
            ) {
                $relations[] = $column;
            }
        }

        return array_unique($relations);
    }

    /**
     * Get current resource route url.
     *
     * @param int $slice
     *
     * @return string
     */
    public function resource($slice = -2)
    {
        $segments = explode('/', trim(app('request')->getUri(), '/'));

        if ($slice != 0) {
            $segments = array_slice($segments, 0, $slice);
        }

        return implode('/', $segments);
    }

    /**
     * @param Field $field
     *
     * @return $this
     */
    public function pushField(Field $field)
    {
        $field->setForm($this);

        $this->builder->fields()->push($field);

        return $this;
    }

    /**
     * Generate a edit form.
     *
     * @param $id
     *
     * @return $this
     */
    public function edit($id)
    {
        $this->builder->setMode(Builder::MODE_EDIT);
        $this->builder->setResourceId($id);

        $this->setFieldValue($id);

        return $this;
    }

    /**
     * Register builtin fields.
     *
     * @return void
     */
    public static function registerBuiltinFields()
    {
        $map = [
            'text' => Text::class,
            'file' => File::class,
            'image' => Image::class,
        ];

        foreach ($map as $abstract => $class) {
            static::$availableFields[$abstract] = $class;
        }
    }

    /**
     * Set all fields value in form.
     *
     * @param $id
     *
     * @return void
     */
    protected function setFieldValue($id)
    {
        $relations = $this->getRelations();

        $builder = $this->model();

        if ($this->isSoftDeletes) {
            $builder = $builder->withTrashed();
        }

        $this->model = $builder->with($relations)->findOrFail($id);

        $data = $this->model->toArray();

        $this->builder->fields()->each(function (Field $field) use ($data) {
            if (!in_array($field->column(), $this->ignored)) {
                $field->fill($data);
            }
        });
    }

    /**
     * Merge validation messages from input validators.
     *
     * @param \Illuminate\Validation\Validator[] $validators
     *
     * @return MessageBag
     */
    protected function mergeValidationMessages($validators)
    {
        $messageBag = new MessageBag();

        foreach ($validators as $validator) {
            $messageBag = $messageBag->merge($validator->messages());
        }

        return $messageBag;
    }

    /**
     * Is input data is has-one relation.
     *
     * @param array $inserts
     *
     * @return bool
     */
    protected function isHasOneRelation($inserts)
    {
        $first = current($inserts);

        if (!is_array($first)) {
            return false;
        }

        if (is_array(current($first))) {
            return false;
        }

        return Arr::isAssoc($first);
    }

    /**
     * Prepare input data for insert or update.
     *
     * @param array $data
     *
     * @return mixed
     */
    protected function prepare($data = [])
    {
        $this->inputs = $data;

        $this->relations = $this->getRelationInputs($this->inputs);

        $this->updates = Arr::except($this->inputs, array_keys($this->relations));
    }

    /**
     * Set original data for each field.
     *
     * @return void
     */
    protected function setFieldOriginalValue()
    {
        $values = $this->model->toArray();

        $this->builder->fields()->each(function (Field $field) use ($values) {
            $field->setOriginal($values);
        });
    }

    /**
     * Update relation data.
     *
     * @param array $relationsData
     *
     * @return void
     *
     * @throws \Exception
     */
    protected function updateRelation($relationsData)
    {
        foreach ($relationsData as $name => $values) {
            if (!method_exists($this->model, $name)) {
                continue;
            }

            $relation = $this->model->$name();

            $oneToOneRelation = $relation instanceof Relations\HasOne
                || $relation instanceof Relations\MorphOne
                || $relation instanceof Relations\BelongsTo;

            $prepared = $this->prepareUpdate([$name => $values], $oneToOneRelation);

            if (empty($prepared)) {
                continue;
            }

            switch (true) {
                case $relation instanceof Relations\BelongsToMany:
                case $relation instanceof Relations\MorphToMany:
                    if (isset($prepared[$name])) {
                        $relation->sync($prepared[$name]);
                    }
                    break;
                case $relation instanceof Relations\HasOne:

                    $related = $this->model->$name;

                    // if related is empty
                    if (is_null($related)) {
                        $related = $relation->getRelated();
                        $qualifiedParentKeyName = $relation->getQualifiedParentKeyName();
                        $localKey = Arr::last(explode('.', $qualifiedParentKeyName));
                        $related->{$relation->getForeignKeyName()} = $this->model->{$localKey};
                    }

                    foreach ($prepared[$name] as $column => $value) {
                        $related->setAttribute($column, $value);
                    }

                    $related->save();
                    break;
                case $relation instanceof Relations\BelongsTo:
                case $relation instanceof Relations\MorphTo:

                    $parent = $this->model->$name;

                    // if related is empty
                    if (is_null($parent)) {
                        $parent = $relation->getRelated();
                    }

                    foreach ($prepared[$name] as $column => $value) {
                        $parent->setAttribute($column, $value);
                    }

                    $parent->save();

                    // When in creating, associate two models
                    $foreignKeyMethod = (app()->version() < '5.8.0') ? 'getForeignKey' : 'getForeignKeyName';
                    if (!$this->model->{$relation->{$foreignKeyMethod}()}) {
                        $this->model->{$relation->{$foreignKeyMethod}()} = $parent->getKey();

                        $this->model->save();
                    }

                    break;
                case $relation instanceof Relations\MorphOne:
                    $related = $this->model->$name;
                    if (is_null($related)) {
                        $related = $relation->make();
                    }
                    foreach ($prepared[$name] as $column => $value) {
                        $related->setAttribute($column, $value);
                    }
                    $related->save();
                    break;
                case $relation instanceof Relations\HasMany:
                case $relation instanceof Relations\MorphMany:

                    foreach ($prepared[$name] as $related) {
                        /** @var Relations\Relation $relation */
                        $relation = $this->model->$name();

                        $keyName = $relation->getRelated()->getKeyName();

                        $instance = $relation->findOrNew(Arr::get($related, $keyName));

                        if ($related['_remove_'] == 1) {
                            $instance->delete();

                            continue;
                        }

                        Arr::forget($related, '_remove_');

                        $instance->fill($related);

                        $instance->save();
                    }

                    break;
            }
        }
    }

    /**
     * Prepare input data for update.
     *
     * @param array $updates
     * @param bool  $oneToOneRelation If column is one-to-one relation.
     *
     * @return array
     */
    protected function prepareUpdate(array $updates, $oneToOneRelation = false)
    {
        $prepared = [];

        /** @var Field $field */
        foreach ($this->builder->fields() as $field) {
            $columns = $field->column();

            // If column not in input array data, then continue.
            if (!Arr::has($updates, $columns)) {
                continue;
            }

            if ($this->isInvalidColumn($columns, $oneToOneRelation)) {
                continue;
            }

            $value = $this->getDataByColumn($updates, $columns);

            $value = $field->prepare($value);

            Arr::set($prepared, $columns, $value);
        }

        return $prepared;
    }

    /**
     * @param string|array $columns
     * @param bool         $containsDot
     *
     * @return bool
     */
    protected function isInvalidColumn($columns, $containsDot = false)
    {
        foreach ((array) $columns as $column) {
            if ((!$containsDot && Str::contains($column, '.')) ||
                ($containsDot && !Str::contains($column, '.'))) {
                return true;
            }
        }

        return false;
    }

    /**
     * Prepare input data for insert.
     *
     * @param $inserts
     *
     * @return array
     */
    protected function prepareInsert($inserts)
    {
        if ($this->isHasOneRelation($inserts)) {
            $inserts = Arr::dot($inserts);
        }

        foreach ($inserts as $column => $value) {
            if (is_null($field = $this->getFieldByColumn($column))) {
                unset($inserts[$column]);
                continue;
            }

            $inserts[$column] = $field->prepare($value);
        }

        $prepared = [];

        foreach ($inserts as $key => $value) {
            Arr::set($prepared, $key, $value);
        }

        return $prepared;
    }

    /**
     * @param array        $data
     * @param string|array $columns
     *
     * @return array|mixed
     */
    protected function getDataByColumn($data, $columns)
    {
        return Arr::get($data, $columns);
    }

    /**
     * Find field object by column.
     *
     * @param $column
     *
     * @return mixed
     */
    protected function getFieldByColumn($column)
    {
        return $this->builder->fields()->first(
            function (Field $field) use ($column) {
                return $field->column() == $column;
            }
        );
    }

    /**
     * Get inputs for relations.
     *
     * @param array $inputs
     *
     * @return array
     */
    protected function getRelationInputs($inputs = [])
    {
        $relations = [];

        foreach ($inputs as $column => $value) {
            if (!method_exists($this->model, $column)) {
                continue;
            }

            $relation = call_user_func([$this->model, $column]);

            if ($relation instanceof Relations\Relation) {
                $relations[$column] = $value;
            }
        }

        return $relations;
    }

    /**
     * Get RedirectResponse after store.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function redirectAfterStore()
    {
        $resourcesPath = $this->resource(0);

        $key = $this->model->getKey();

        return $this->redirectAfterSaving($resourcesPath, $key);
    }

    /**
     * Get RedirectResponse after update.
     *
     * @param mixed $key
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function redirectAfterUpdate($key)
    {
        $resourcesPath = $this->resource(-1);

        return $this->redirectAfterSaving($resourcesPath, $key);
    }

    /**
     * Get RedirectResponse after data saving.
     *
     * @param string $resourcesPath
     * @param string $key
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    protected function redirectAfterSaving($resourcesPath, $key)
    {
        if (request('after-save') == 1) {
            // continue editing
            $url = rtrim($resourcesPath, '/')."/{$key}/edit";
        } elseif (request('after-save') == 2) {
            // continue creating
            $url = rtrim($resourcesPath, '/').'/create';
        } elseif (request('after-save') == 3) {
            // view resource
            $url = rtrim($resourcesPath, '/')."/{$key}";
        } else {
            $url = $resourcesPath;
        }

        return redirect($url);
    }

    /**
     * Generate a Field object and add to form builder if Field exists.
     *
     * @param string $method
     * @param array  $arguments
     *
     * @return Field
     */
    public function __call($method, $arguments)
    {
        $class = Arr::get(static::$availableFields, $method);
        if (class_exists($class)) {
            $column = Arr::get($arguments, 0, ''); //[0];
            $element = new $class($column, array_slice($arguments, 1));
            $this->pushField($element);

            return $element;
        }

        return new Nullable();
    }

    /**
     * Get the evaluated contents of the object.
     *
     * @return string
     */
    public function render()
    {
        return $this->builder->render();
    }
}
