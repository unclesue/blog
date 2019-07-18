<?php

namespace App\Http\Grid\Concerns;

use App\Http\Grid\Filter;
use Illuminate\Support\Collection;

trait HasFilter
{
    /**
     * The grid Filter.
     *
     * @var Filter
     */
    protected $filter;

    /**
     * Setup grid filter.
     *
     * @return $this
     */
    protected function initFilter()
    {
        $this->filter = new Filter($this->model());

        return $this;
    }

    /**
     * Process the grid filter.
     *
     * @param bool $toArray
     *
     * @return array|Collection|mixed
     */
    public function applyFilter($toArray = true)
    {
        return $this->filter->execute($toArray);
    }
}
