<?php

namespace App\Http\Grid;

use Illuminate\Database\Eloquent\Model as EloquentModel;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Str;

class Model
{

    /**
     * Eloquent model instance of the grid model.
     *
     * @var EloquentModel
     */
    protected $model;

    /**
     * @var EloquentModel
     */
    protected $originalModel;

    /**
     * Array of queries of the eloquent model.
     *
     * @var \Illuminate\Support\Collection
     */
    protected $queries;

    /**
     * Sort parameters of the model.
     *
     * @var array
     */
    protected $sort;

    /**
     * @var array
     */
    protected $data = [];

    /**
     * 20 items per page as default.
     *
     * @var int
     */
    protected $perPage = 20;

    /**
     * If the model use pagination.
     *
     * @var bool
     */
    protected $usePaginate = true;

    /**
     * The query string variable used to store the sort.
     *
     * @var string
     */
    protected $sortName = '_sort';

    /**
     * @var Relation
     */
    protected $relation;

    /**
     * @var array
     */
    protected $eagerLoads = [];


    /**
     * Create a new grid model instance.
     *
     * @param EloquentModel $model
     */
    public function __construct(EloquentModel $model)
    {
        $this->model = $model;

        $this->originalModel = $model;

        $this->queries = collect();
    }

    /**
     * @return EloquentModel
     */
    public function getOriginalModel()
    {
        return $this->originalModel;
    }

    /**
     * Get the eloquent model of the grid model.
     *
     * @return EloquentModel
     */
    public function eloquent()
    {
        return $this->model;
    }

    /**
     * Enable or disable pagination.
     *
     * @param bool $use
     */
    public function usePaginate($use = true)
    {
        $this->usePaginate = $use;
    }

    /**
     * Get per-page number.
     *
     * @return int
     */
    public function getPerPage()
    {
        return $this->perPage;
    }

    /**
     * Set per-page number.
     *
     * @param int $perPage
     *
     * @return $this
     */
    public function setPerPage($perPage)
    {
        $this->perPage = $perPage;

        $this->__call('paginate', [$perPage]);

        return $this;
    }

    /**
     * Get the query string variable used to store the sort.
     *
     * @return string
     */
    public function getSortName()
    {
        return $this->sortName;
    }

    /**
     * Set the query string variable used to store the sort.
     *
     * @param string $name
     *
     * @return $this
     */
    public function setSortName($name)
    {
        $this->sortName = $name;

        return $this;
    }

    /**
     * @param Relation $relation
     *
     * @return $this
     */
    public function setRelation(Relation $relation)
    {
        $this->relation = $relation;

        return $this;
    }

    /**
     * @return Relation
     */
    public function getRelation()
    {
        return $this->relation;
    }

    /**
     * Build.
     *
     * @param bool $toArray
     *
     * @return array|Collection|mixed
     */
    public function buildData($toArray = true)
    {
        if (empty($this->data)) {
            $collection = $this->get();

            if ($toArray) {
                $this->data = $collection->toArray();
            } else {
                $this->data = $collection;
            }
        }

        return $this->data;
    }

    /**
     * @param callable $callback
     * @param int      $count
     *
     * @return bool
     */
    public function chunk($callback, $count = 100)
    {
        if ($this->usePaginate) {
            return $this->buildData(false)->chunk($count)->each($callback);
        }

        $this->setSort();

        $this->queries->reject(function ($query) {
            return $query['method'] == 'paginate';
        })->each(function ($query) {
            $this->model = $this->model->{$query['method']}(...$query['arguments']);
        });

        return $this->model->chunk($count, $callback);
    }

    /**
     * @throws \Exception
     *
     * @return Collection
     */
    protected function get()
    {
        if ($this->model instanceof LengthAwarePaginator) {
            return $this->model;
        }

        if ($this->relation) {
            $this->model = $this->relation->getQuery();
        }

        $this->setSort();
        $this->setPaginate();

        $this->queries->unique()->each(function ($query) {
            $this->model = call_user_func_array([$this->model, $query['method']], $query['arguments']);
        });

        if ($this->model instanceof Collection) {
            return $this->model;
        }

        if ($this->model instanceof LengthAwarePaginator) {
            $this->handleInvalidPage($this->model);

            return $this->model->getCollection();
        }

        throw new \Exception('Grid query error');
    }

    /**
     * If current page is greater than last page, then redirect to last page.
     *
     * @param LengthAwarePaginator $paginator
     *
     * @return void
     */
    protected function handleInvalidPage(LengthAwarePaginator $paginator)
    {
        if ($paginator->lastPage() && $paginator->currentPage() > $paginator->lastPage()) {
            $lastPageUrl = Request::fullUrlWithQuery([
                $paginator->getPageName() => $paginator->lastPage(),
            ]);

            redirect($lastPageUrl)->send();

            exit;
        }
    }

    /**
     * Set the grid paginate.
     *
     * @return void
     */
    protected function setPaginate()
    {
        $paginate = $this->findQueryByMethod('paginate');

        $this->queries = $this->queries->reject(function ($query) {
            return $query['method'] == 'paginate';
        });

        if (!$this->usePaginate) {
            $query = [
                'method'    => 'get',
                'arguments' => [],
            ];
        } else {
            $query = [
                'method'    => 'paginate',
                'arguments' => $this->resolvePerPage($paginate),
            ];
        }

        $this->queries->push($query);
    }

    /**
     * Resolve perPage for pagination.
     *
     * @param array|null $paginate
     *
     * @return array
     */
    protected function resolvePerPage($paginate)
    {
        if (isset($paginate['arguments'][0])) {
            return $paginate['arguments'];
        }

        return [$this->perPage];
    }

    /**
     * Find query by method name.
     *
     * @param $method
     *
     * @return static
     */
    protected function findQueryByMethod($method)
    {
        return $this->queries->first(function ($query) use ($method) {
            return $query['method'] == $method;
        });
    }

    /**
     * Set the grid sort.
     *
     * @return void
     */
    protected function setSort()
    {
        $this->sort = Input::get($this->sortName, []);
        if (!is_array($this->sort)) {
            return;
        }

        if (empty($this->sort['column']) || empty($this->sort['type'])) {
            return;
        }

        if (Str::contains($this->sort['column'], '.')) {
            $this->setRelationSort($this->sort['column']);
        } else {
            $this->resetOrderBy();

            // get column. if contains "cast", set set column as cast
            if (!empty($this->sort['cast'])) {
                $column = "CAST({$this->sort['column']} AS {$this->sort['cast']}) {$this->sort['type']}";
                $method = 'orderByRaw';
                $arguments = [$column];
            } else {
                $column = $this->sort['column'];
                $method = 'orderBy';
                $arguments = [$column, $this->sort['type']];
            }

            $this->queries->push([
                'method'    => $method,
                'arguments' => $arguments,
            ]);
        }
    }

    /**
     * Set relation sort.
     *
     * @param string $column
     *
     * @return void
     */
    protected function setRelationSort($column)
    {
        list($relationName, $relationColumn) = explode('.', $column);

        if ($this->queries->contains(function ($query) use ($relationName) {
            return $query['method'] == 'with' && in_array($relationName, $query['arguments']);
        })) {
            $relation = $this->model->$relationName();

            $this->queries->push([
                'method'    => 'select',
                'arguments' => [$this->model->getTable().'.*'],
            ]);

            $this->queries->push([
                'method'    => 'join',
                'arguments' => $this->joinParameters($relation),
            ]);

            $this->resetOrderBy();

            $this->queries->push([
                'method'    => 'orderBy',
                'arguments' => [
                    $relation->getRelated()->getTable().'.'.$relationColumn,
                    $this->sort['type'],
                ],
            ]);
        }
    }

    /**
     * Reset orderBy query.
     *
     * @return void
     */
    public function resetOrderBy()
    {
        $this->queries = $this->queries->reject(function ($query) {
            return $query['method'] == 'orderBy' || $query['method'] == 'orderByDesc';
        });
    }

    /**
     * Build join parameters for related model.
     *
     * `HasOne` and `BelongsTo` relation has different join parameters.
     *
     * @param Relation $relation
     *
     * @throws \Exception
     *
     * @return array
     */
    protected function joinParameters(Relation $relation)
    {
        $relatedTable = $relation->getRelated()->getTable();

        if ($relation instanceof BelongsTo) {
            $foreignKeyMethod = (app()->version() < '5.8.0') ? 'getForeignKey' : 'getForeignKeyName';

            return [
                $relatedTable,
                $relation->{$foreignKeyMethod}(),
                '=',
                $relatedTable.'.'.$relation->getRelated()->getKeyName(),
            ];
        }

        if ($relation instanceof HasOne) {
            return [
                $relatedTable,
                $relation->getQualifiedParentKeyName(),
                '=',
                $relation->getQualifiedForeignKeyName(),
            ];
        }

        throw new \Exception('Related sortable only support `HasOne` and `BelongsTo` relation.');
    }

    /**
     * Set the relationships that should be eager loaded.
     *
     * @param mixed $relations
     *
     * @return $this|Model
     */
    public function with($relations)
    {
        if (is_array($relations)) {
            if (Arr::isAssoc($relations)) {
                $relations = array_keys($relations);
            }

            $this->eagerLoads = array_merge($this->eagerLoads, $relations);
        }

        if (is_string($relations)) {
            if (Str::contains($relations, '.')) {
                $relations = explode('.', $relations)[0];
            }

            if (Str::contains($relations, ':')) {
                $relations = explode(':', $relations)[0];
            }

            if (in_array($relations, $this->eagerLoads)) {
                return $this;
            }

            $this->eagerLoads[] = $relations;
        }

        return $this->__call('with', (array) $relations);
    }

    /**
     * @param string $method
     * @param array  $arguments
     *
     * @return $this
     */
    public function __call($method, $arguments)
    {
        $this->queries->push([
            'method'    => $method,
            'arguments' => $arguments,
        ]);

        return $this;
    }
}
