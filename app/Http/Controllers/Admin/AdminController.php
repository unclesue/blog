<?php

namespace App\Http\Controllers\Admin;


class AdminController
{
    use HasResourceActions;

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
