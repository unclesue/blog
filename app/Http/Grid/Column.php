<?php

namespace App\Http\Grid;

use Illuminate\Database\Eloquent\Model;

class Column
{
    /**
     * @var Grid
     */
    protected $grid;

    /**
     * Name of column.
     *
     * @var string
     */
    protected $name;

    /**
     * Label of column.
     *
     * @var string
     */
    protected $label;

    /**
     * Is column sortable.
     *
     * @var bool
     */
    protected $sortable = false;

    /**
     * Sort arguments.
     *
     * @var array
     */
    protected $sort;

    /**
     * @var Model
     */
    protected static $model;

    /**
     * @param string $name
     * @param string $label
     */
    public function __construct($name, $label)
    {
        $this->name = $name;

        $this->label = $this->formatLabel($label);
    }

    /**
     * Set grid instance for column.
     *
     * @param Grid $grid
     */
    public function setGrid(Grid $grid)
    {
        $this->grid = $grid;

        $this->setModel($grid->model()->eloquent());
    }

    /**
     * Set model for column.
     *
     * @param $model
     */
    public function setModel($model)
    {
        if (is_null(static::$model) && ($model instanceof Model)) {
            static::$model = $model->newInstance();
        }
    }

    /**
     * Get name of this column.
     *
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Format label.
     *
     * @param $label
     *
     * @return mixed
     */
    protected function formatLabel($label)
    {
        if ($label) {
            return $label;
        }

        $label = ucfirst($this->name);

        return __(str_replace(['.', '_'], ' ', $label));
    }

    /**
     * Get label of the column.
     *
     * @return mixed
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * Set sort value.
     *
     * @param bool $sort
     *
     * @return Column
     */
    public function sort($sort)
    {
        $this->sortable = $sort;

        return $this;
    }

    /**
     * Mark this column as sortable.
     *
     * @return Column
     */
    public function sortable()
    {
        return $this->sort(true);
    }

    /**
     * Create the column sorter.
     *
     * @return string
     */
    public function sorter()
    {
        if (!$this->sortable) {
            return '';
        }

        $icon = 'fa-sort';
        $type = 'desc';

        if ($this->isSorted()) {
            $type = $this->sort['type'] == 'desc' ? 'asc' : 'desc';
            $icon .= "-amount-{$this->sort['type']}";
        }

        // set sort value
        $sort = ['column' => $this->name, 'type' => $type];
        if (isset($this->cast)) {
            $sort['cast'] = $this->cast;
        }

        $query = app('request')->all();
        $query = array_merge($query, [$this->grid->model()->getSortName() => $sort]);

        $url = url()->current().'?'.http_build_query($query);

        return "<a class=\"fa fa-fw $icon\" href=\"$url\"></a>";
    }

    /**
     * Determine if this column is currently sorted.
     *
     * @return bool
     */
    protected function isSorted()
    {
        $this->sort = app('request')->get($this->grid->model()->getSortName());

        if (empty($this->sort)) {
            return false;
        }

        return isset($this->sort['column']) && $this->sort['column'] == $this->name;
    }

}
