<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\AdminController;
use Illuminate\Http\Request;

class IndexController extends AdminController
{

    public function __construct()
    {
        $this->middleware('auth.admin');
    }

    /**
     * 显示后台管理模板首页
     */
    public function index()
    {
        return view('admin.main');
    }

}
