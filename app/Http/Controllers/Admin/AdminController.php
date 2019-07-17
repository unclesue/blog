<?php

namespace App\Http\Controllers\Admin;


use Collective\Html\FormFacade;

class AdminController
{
    use HasResourceActions;

    /**
     * Index interface.
     *
     */
    public function index()
    {
        return $this->grid();
    }

    /**
     * Show interface.
     * @param $id
     * @return mixed
     */
    public function show($id)
    {
        return $this->detail($id);
    }

    /**
     * Edit interface.
     * @param $id
     * @return mixed
     */
    public function edit($id)
    {
        $form = $this->form();

        return $this->view();
    }

    /**
     * Create interface.
     */
    public function create()
    {
        $this->form();

        return $this->view();
    }

    /**
     * 获取路由名称渲染页面
     *
     * @param array $data
     * @param null|string $view
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function view($data = [], $view = null)
    {
        $routeName = $view === null ? 'admin.' . request()->route()->getName() : $view;

        return view($routeName, $data);
    }

}
