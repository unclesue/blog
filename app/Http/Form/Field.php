<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/7/8
 * Time: 16:31
 */

namespace App\Http\Form;

use Closure;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Support\Arr;
use Illuminate\Support\Traits\Macroable;

class Field implements Renderable
{
    use Macroable;

    const FILE_DELETE_FLAG = '_file_del_';

    /**
     * Element value.
     *
     * @var mixed
     */
    protected $value;

    /**
     * Column name.
     *
     * @var string
     */
    protected $column = '';

    /**
     * Validation rules.
     *
     * @var array
     */
    protected $rules = [];

    /**
     * The validation rules for creation.
     *
     * @var array|\Closure
     */
    public $creationRules = [];

    /**
     * The validation rules for updates.
     *
     * @var array|\Closure
     */
    public $updateRules = [];

    /**
     * Validation messages.
     *
     * @var array
     */
    protected $validationMessages = [];

    /**
     * Parent form.
     *
     * @var Form
     */
    protected $form = null;

    /**
     * Field original value.
     *
     * @var mixed
     */
    protected $original;

    /**
     * View for field to render.
     *
     * @var string
     */
    protected $view = '';


    /**
     * Field constructor.
     * @param string $column
     */
    public function __construct($column = '')
    {
        $this->column = $column;
    }

    /**
     * Get column of the field.
     *
     * @return string
     */
    public function column()
    {
        return $this->column;
    }

    /**
     * Prepare for a field value before update or insert.
     *
     * @param $value
     *
     * @return mixed
     */
    public function prepare($value)
    {
        return $value;
    }

    /**
     * Set original value to the field.
     *
     * @param array $data
     *
     * @return void
     */
    public function setOriginal($data)
    {
        $this->original = Arr::get($data, $this->column);
    }

    /**
     * @param Form $form
     *
     * @return $this
     */
    public function setForm(Form $form = null)
    {
        $this->form = $form;

        return $this;
    }

    /**
     * Set the validation rules for the field.
     *
     * @param array|string $rules
     * @param array                 $messages
     *
     * @return $this
     */
    public function rules($rules = null, $messages = [])
    {
        $this->rules = $this->mergeRules($rules, $this->rules);

        $this->setValidationMessages('default', $messages);

        return $this;
    }

    /**
     * @param string|array         $input
     * @param string|array         $original
     *
     * @return array|Closure
     */
    protected function mergeRules($input, $original)
    {
        if (!empty($original)) {
            $original = $this->formatRules($original);
        }

        $rules = array_merge($original, $this->formatRules($input));

        return $rules;
    }

    /**
     * Format validation rules.
     *
     * @param array|string $rules
     *
     * @return array
     */
    protected function formatRules($rules)
    {
        if (is_string($rules)) {
            $rules = array_filter(explode('|', $rules));
        }

        return array_filter((array) $rules);
    }

    /**
     * Set validation messages for column.
     *
     * @param string $key
     * @param array  $messages
     *
     * @return $this
     */
    public function setValidationMessages($key, array $messages)
    {
        $this->validationMessages[$key] = $messages;

        return $this;
    }

    /**
     * Get validator for this field.
     *
     * @param array $input
     *
     * @return bool|\Illuminate\Contracts\Validation\Validator|mixed
     */
    public function getValidator(array $input)
    {
        $rules = [];

        if (!$fieldRules = $this->getRules()) {
            return false;
        }

        if (!Arr::has($input, $this->column)) {
            return false;
        }

        $rules[$this->column] = $fieldRules;

        return validator($input, $rules, $this->getValidationMessages());
    }

    /**
     * Get validation messages for the field.
     *
     * @return array|mixed
     */
    public function getValidationMessages()
    {
        // Default validation message.
        $messages = $this->validationMessages['default'] ?? [];

        if (request()->isMethod('POST')) {
            $messages = $this->validationMessages['creation'] ?? $messages;
        } elseif (request()->isMethod('PUT')) {
            $messages = $this->validationMessages['update'] ?? $messages;
        }

        return $messages;
    }

    /**
     * Get field validation rules.
     *
     * @return string
     */
    protected function getRules()
    {
        if (request()->isMethod('POST')) {
            $rules = $this->creationRules ?: $this->rules;
        } elseif (request()->isMethod('PUT')) {
            $rules = $this->updateRules ?: $this->rules;
        } else {
            $rules = $this->rules;
        }

        if (is_string($rules)) {
            $rules = array_filter(explode('|', $rules));
        }

        return $rules;
    }

    /**
     * Set or get value of the field.
     *
     * @param null $value
     *
     * @return mixed
     */
    public function value($value = null)
    {
        if (is_null($value)) {
            return is_null($this->value) ? '' : $this->value;
        }

        $this->value = $value;

        return $this;
    }

    /**
     * Fill data to the field.
     *
     * @param array $data
     *
     * @return void
     */
    public function fill($data)
    {
        $this->data = $data;

        if (is_array($this->column)) {
            foreach ($this->column as $key => $column) {
                $this->value[$key] = Arr::get($data, $column);
            }

            return;
        }

        $this->value = Arr::get($data, $this->column);
        if (isset($this->customFormat) && $this->customFormat instanceof \Closure) {
            $this->value = call_user_func($this->customFormat, $this->value);
        }
    }

    /**
     * Get the view variables of this field.
     *
     * @return array
     */
    public function variables()
    {
        return [];
    }

    /**
     * Get view of this field.
     *
     * @return string
     */
    public function getView()
    {
        if (!empty($this->view)) {
            return $this->view;
        }

        $class = explode('\\', get_called_class());

        return 'admin.form.'.strtolower(end($class));
    }

    /**
     * Render this filed.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|string
     */
    public function render()
    {
        return view($this->getView(), $this->variables());
    }
}
