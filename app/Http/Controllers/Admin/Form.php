<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/7/2
 * Time: 16:15
 */

namespace App\Http\Controllers\Admin;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;

class Form
{
    /**
     * Eloquent model of the form.
     *
     * @var Model
     */
    protected $model;

    /**
     * Create a new form instance.
     *
     * @param $model
     */
    public function __construct($model)
    {
        $this->model = $model;
    }

    /**
     * Store a new record.
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|\Illuminate\Http\JsonResponse
     */
    public function store()
    {
        $data = \request()->all();

        // Handle validation errors.
        $validator = Validator::make($data, $this->model->rules(), $this->model->messages());
        if ($validator->fails()) {
            return back()->withInput()->withErrors($validator);
        }


    }

}
