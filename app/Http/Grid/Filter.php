<?php

namespace App\Http\Grid;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Support\Collection;

class Filter implements Renderable
{

    /**
     * @var Model
     */
    protected $model;

    /**
     * Primary key of giving model.
     *
     * @var mixed
     */
    protected $primaryKey;

    /**
     * Create a new filter instance.
     *
     * @param Model $model
     */
    public function __construct(Model $model)
    {
        $this->model = $model;

        $this->primaryKey = $this->model->eloquent()->getKeyName();
    }

    /**
     * Execute the filter with conditions.
     *
     * @param bool $toArray
     *
     * @return array|Collection|mixed
     */
    public function execute($toArray = true)
    {
        if (method_exists($this->model->eloquent(), 'paginate')) {
            $this->model->usePaginate(true);

            return $this->model->buildData($toArray);
        }

        return $this->model->buildData($toArray);
    }

    /**
     * Get the evaluated contents of the object.
     *
     * @return string
     */
    public function render()
    {
        // TODO: Implement render() method.
    }
}
