<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Admin\Form;
use App\Http\Controllers\Controller;
use App\Model\AdminMenu;
use App\Model\AdminRoleMenu;
use App\Model\Phone;
use App\Model\User;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {

        // hasOne更新
        /*$user = User::find(2);
        $phone = $user->phone;
        $phone->setAttribute('mobile', '136' . Str::random(8));
        $phone->save();*/

        /*$phone = Phone::find(4);
        $user = $phone->user;
        $user->setAttribute('name', Str::random(8));
        $user->save();*/


        /*$form = new Form(new Phone);
        $form->model = $form->model()->findOrFail(4);
        $form->pushFields(['user.name', 'mobile']);
        $data = [
            'user' => [
                'name' => '123456' . Str::random(4),
            ],
            'mobile' => '123321000'
        ];
        $form->store($data);*/


        // 添加栏目、权限
        /*$form = new Form(new AdminMenu);
        $data = [
            'title' => 123,
            'icon' => 'fa-tasks',
            'roles' => [1, 3, 5],
            '_token' => csrf_token(),
        ];

        $form->store($data);*/

        // 一对一手机号
        /*$form = $this->form();
        $form->model = $form->model->findOrFail(2);
        $str = Str::random(6);
        $data = [
            'name' => $str,
            'email' => $str . '@gmail.com',
            'password' => bcrypt('password'),
            'profile' => [
                'avatar' => '123444444444.jpg',
                'homepage' => 'uncle.com'
            ]
        ];

        $form->store($data);*/
    }

    public function form()
    {
        $form = new Form(new User);
        $form->pushFields(['name', 'email', 'password', 'profile.avatar', 'profile.homepage']);

        return $form;
    }


}
