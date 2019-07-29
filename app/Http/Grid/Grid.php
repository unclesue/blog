<?php

namespace App\Http\Grid;

use Closure;
use Illuminate\Database\Eloquent\Model as Eloquent;
use Illuminate\Support\Collection;

class Grid
{
    use Concerns\HasFilter,
        Concerns\CanHidesColumns;

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
     * All column names of the grid.
     *
     * @var array
     */
    public $columnNames = [];


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
     * Get the grid paginator.
     *
     * @return mixed
     */
    public function paginator()
    {
        return new Tools\Paginator($this);
    }

    /**
     * Build the grid.
     *
     * @return void
     */
    public function build()
    {
        if ($this->builded) {
            return;
        }

        $collection = $this->applyFilter(false);

        $data = $collection->toArray();

        $this->columns->map(function (Column $column) use (&$data) {
            //$data = $column->fill($data);

            $this->columnNames[] = $column->getName();
        });

        $this->buildRows($data);

        $this->builded = true;
    }

    /**
     * Build the grid rows.
     *
     * @param array $data
     *
     * @return void
     */
    protected function buildRows(array $data)
    {
        $this->rows = collect($data)->map(function ($model, $number) {
            return new Row($number, $model);
        });
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
     *
     * @return Column
     */
    protected function addColumn($column = '', $label = '')
    {
        $column = new Column($column, $label);
        $column->setGrid($this);

        return tap($column, function ($value) {
            $this->columns->push($value);
        });
    }

    /**
     * Dynamically add columns to the grid view.
     *
     * @param $method
     * @param $arguments
     * @return Column
     */
    public function __call($method, $arguments)
    {
        $label = $arguments[0] ?? null;

        return $this->addColumn($method, $label);
    }

    /**
     * Set grid row callback function.
     *
     * @return Collection|null
     */
    public function rows()
    {
        return $this->rows;
    }

}
