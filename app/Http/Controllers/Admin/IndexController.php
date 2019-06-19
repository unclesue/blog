<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

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
        return view('admin.home');
    }

    public function menu()
    {
        return view('admin.menu');
    }
}
