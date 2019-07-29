<?php

namespace App\Http\Grid\Concerns;

use App\Http\Grid\Column;
use Illuminate\Support\Collection;

trait CanHidesColumns
{

    /**
     * Default columns be hidden.
     *
     * @var array
     */
    public $hiddenColumns = [];

    /**
     * Setting default shown columns on grid.
     *
     * @param array|string $columns
     *
     * @return $this
     */
    public function hideColumns($columns)
    {
        if (func_num_args()) {
            $columns = (array) $columns;
        } else {
            $columns = func_get_args();
        }

        $this->hiddenColumns = array_merge($this->hiddenColumns, $columns);

        return $this;
    }

    /**
     * Get all visible column instances.
     *
     * @return Collection|static
     */
    public function visibleColumns()
    {
        $visible = array_values(array_diff($this->columnNames, $this->hiddenColumns));

        if (empty($visible)) {
            return $this->columns;
        }

        return $this->columns->filter(function (Column $column) use ($visible) {
            return in_array($column->getName(), $visible);
        });
    }

    /**
     * Get all visible column names.
     *
     * @return array
     */
    public function visibleColumnNames()
    {
        $visible = array_values(array_diff($this->columnNames, $this->hiddenColumns));

        if (empty($visible)) {
            return $this->columnNames;
        }

        return collect($this->columnNames)->filter(function ($column) use ($visible) {
            return in_array($column, $visible);
        })->toArray();
    }

    /**
     * Get default visible column names.
     *
     * @return array
     */
    public function getDefaultVisibleColumnNames()
    {
        return array_values(
            array_diff(
                $this->columnNames,
                $this->hiddenColumns
            )
        );
    }

}
