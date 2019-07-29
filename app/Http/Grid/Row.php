<?php

namespace App\Http\Grid;

use Closure;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Contracts\Support\Jsonable;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Support\Arr;

class Row
{
    /**
     * Row number.
     *
     * @var
     */
    public $number;

    /**
     * Row data.
     *
     * @var
     */
    protected $data;

    /**
     * Attributes of row.
     *
     * @var array
     */
    protected $attributes = [];

    /**
     * Constructor.
     *
     * @param $number
     * @param $data
     */
    public function __construct($number, $data)
    {
        $this->number = $number;

        $this->data = $data;
    }

    /**
     * Get or set value of column in this row.
     *
     * @param string $name
     * @param mixed  $value
     *
     * @return $this|mixed
     */
    public function column($name, $value = null)
    {
        if (is_null($value)) {
            $column = Arr::get($this->data, $name);

            return $this->output($column);
        }

        if ($value instanceof Closure) {
            $value = $value->call($this, $this->column($name));
        }

        Arr::set($this->data, $name, $value);

        return $this;
    }

    /**
     * Output column value.
     *
     * @param mixed $value
     *
     * @return mixed|string
     */
    protected function output($value)
    {
        if ($value instanceof Renderable) {
            $value = $value->render();
        }

        if ($value instanceof Htmlable) {
            $value = $value->toHtml();
        }

        if ($value instanceof Jsonable) {
            $value = $value->toJson();
        }

        if (!is_null($value) && !is_scalar($value)) {
            return sprintf('<pre>%s</pre>', var_export($value, true));
        }

        return $value;
    }

}
