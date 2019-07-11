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
        print_r($this->form()->model());

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

    public function view()
    {
        $routeName = request()->route()->getName();

        return view('admin.' . $routeName);
    }

}
