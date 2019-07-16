<?php

namespace App\Http\Controllers\Admin;

use App\Http\Form\Form;
use App\Http\Form\Model;
use App\Model\User;

class UserController extends AdminController
{

    public function index()
    {
        $model = new Model(new User);
        $model->setPerPage(20);
        $model->with('profile')->orderBy('id', 'DESC');
        $users = $model->buildData(false);

        return $this->view(compact('users'));
    }

    public function form()
    {
        $form = new Form(new User);
        $form->text('name')->rules('required|max:10');
        $form->text('email')->rules('required|email');
        $form->text('password')->rules('required|max:12');
        $form->text('phone.mobile')->rules('required|unique:phones|min:11', ['mobile.min' => '{{id}}email must']);

        return $form;
    }

}
