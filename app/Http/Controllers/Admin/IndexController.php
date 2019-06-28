<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class IndexController extends Controller
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
