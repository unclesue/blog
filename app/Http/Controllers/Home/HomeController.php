<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use App\Http\Form\Form;
use App\Http\Form\Image;
use App\Model\AdminMenu;
use App\Model\AdminRoleMenu;
use App\Model\Phone;
use App\Model\User;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
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
        /*$form = new Form(new Phone);
        $form->text('user.name')->rules('required|max:10');
        $form->text('user.email')->rules('required|email');
        $form->text('user.password')->rules('required|max:12');
        $form->text('mobile')->rules('required|unique:phones|min:11', ['mobile.min' => '{{id}}email must']);

        $str = Str::random(6);
        $data = [
            'user' => [
                'name' => '123',
                'email' => $str . 'gmail.com',
                'password' => bcrypt('password'),
            ],
            'mobile' => '1'
        ];
        $form->update(4, $data);*/

        /*$form = new Form(new User);
        $form->text('name')->rules('required|max:10');
        $form->text('email')->rules('required|email');
        $form->Image('profile.avatar')->rules('required|image');
        $str = Str::random(6);
        $data = [
            'user' => [
                'name' => $str,
                'email' => $str . 'gmail.com',
            ],
            'profile' => [
                'avatar' => ''
            ]
        ];*/

        if (request()->isMethod('POST')) {
            $form = new Form(new User);
            /*$form->text('name')->rules('required|max:10');
            $form->text('email')->rules('required|email');*/
            $form->image('profile.avatar')->rules('required');
            $form->text('phone.mobile')->rules('required');

            $res = $form->update(1);
        }

        return view('test');

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

        /*$form = new Form(new Phone);
        $form->pushFields(['mobile']);
        $str = Str::random(6);
        $data = [
            'user' => [
                'name' => $str,
            ],
            'mobile' => time()
        ];
        $form->update(14, $data);*/
    }


}
