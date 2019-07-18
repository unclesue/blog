<?php

namespace App\Http\Grid;

use Closure;
use Illuminate\Database\Eloquent\Model as Eloquent;
use Illuminate\Support\Collection;

class Grid
{
    use Concerns\HasFilter;

    /**
     * The grid data model instance.
     *
     * @var Model
     */
    protected $model;

    /**
     * Collection of all grid columns.
     *
     * @var \Illuminate\Support\Collection
     */
    protected $columns;

    /**
     * Collection of all data rows.
     *
     * @var \Illuminate\Support\Collection
     */
    protected $rows;

    /**
     * View for grid to render.
     *
     * @var string
     */
    protected $view = 'admin.grid.table';

    /**
     * @var string
     */
    public $tableID;

    /**
     * Default items count per-page.
     *
     * @var int
     */
    public $perPage = 20;

    /**
     * Mark if the grid is builded.
     *
     * @var bool
     */
    protected $builded = false;

    /**
     * All variables in grid view.
     *
     * @var array
     */
    protected $variables = [];


    /**
     * Create a new grid instance.
     *
     * @param Eloquent $model
     */
    public function __construct(Eloquent $model)
    {
        $this->model = new Model($model);

        $this->initialize();
    }

    /**
     * Initialize.
     */
    protected function initialize()
    {
        $this->tableID = uniqid('grid-table');

        $this->columns = Collection::make();
        $this->rows = Collection::make();

        $this->initFilter();
    }

    /**
     * Get Grid model.
     *
     * @return Model
     */
    public function model()
    {
        return $this->model;
    }

    /**
     * Paginate the grid.
     *
     * @param int $perPage
     *
     * @return void
     */
    public function paginate($perPage = 20)
    {
        $this->perPage = $perPage;

        $this->model()->setPerPage($perPage);
    }

    /**
     * Build the grid.
     *
     * @return void
     */
    public function build()
    {
        $collection = $this->applyFilter(false);

        $data = $collection->toArray();

        $this->rows->push($data);
    }

    /**
     * Get all variables will used in grid view.
     *
     * @return array
     */
    protected function variables()
    {
        $this->variables['grid'] = $this;

        return $this->variables;
    }

    /**
     * Get the string contents of the grid view.
     *
     * @return array|string
     * @throws \Throwable
     */
    public function render()
    {
        $this->build();

        return view($this->view, $this->variables())->render();
    }

    /**
     * Add column to grid.
     *
     * @param string $column
     * @param string $label
     */
    protected function addColumn($column = '', $label = '')
    {
        $this->columns->put($column, $label);
    }

    /**
     * Dynamically add columns to the grid view.
     *
     * @param $method
     * @param $arguments
     */
    public function __call($method, $arguments)
    {
        $label = $arguments ?? null;

        $this->addColumn($method, $label);
    }

}
