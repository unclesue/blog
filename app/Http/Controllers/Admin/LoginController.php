<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\AdminController;
use Illuminate\Http\Request;
use App\Exceptions\AuthenticatesLogout;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends AdminController
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers, AuthenticatesLogout {
        AuthenticatesLogout::logout insteadof AuthenticatesUsers;
    }

    /**
     * Where to redirect users after login / registration.
     *
     * @var string
     */
    protected $redirectTo = '/admin';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest.admin', ['except' => 'logout']);
    }

    /**
     * 显示后台登录模板
     */
    public function showLoginForm()
    {
        return view('admin.login');
    }

    /**
     * 使用 admin guard
     */
    protected function guard()
    {
        return auth()->guard('admin');
    }

    /**
     * 退出跳转
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    protected function loggedOut(Request $request)
    {
        return redirect('/admin');
    }

    /**
     * 重写验证时使用的用户名字段
     */
    public function username()
    {
        return 'username';
    }

}
