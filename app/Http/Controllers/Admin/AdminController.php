<?php

namespace App\Http\Controllers\Admin;


class AdminController
{
    use HasResourceActions;

    /**
     * Index interface.
     */
    public function index()
    {
        $this->grid();

        return $this->view();
    }

    /**
     * Show interface.
     */
    public function show($id)
    {
        return $this->view();
    }

    /**
     * Edit interface.
     */
    public function edit($id)
    {
        return $this->view();
    }

    /**
     * Create interface.
     */
    public function create()
    {
        return $this->view();
    }

    /**
     * 获取路由名称渲染页面
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function view()
    {
        $routeName = request()->route()->getName();

        return view('admin.' . $routeName);
    }

}
