<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/7/8
 * Time: 17:08
 */

namespace App\Http\Form;


class Nullable extends Field
{
    public function __construct()
    {
    }

    public function __call($method, $parameters)
    {
        return $this;
    }
}
