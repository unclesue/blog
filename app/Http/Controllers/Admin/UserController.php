<?php

namespace App\Http\Controllers\Admin;

use App\Http\Form\Form;
use App\Http\Form\Model;
use App\Model\User;

class UserController extends AdminController
{

    public function grid()
    {
        $model = new Model(new User);
        $model->setPerPage(20);
        $model->with('profile')->orderBy('id', 'DESC');
        $users = $model->buildData(false);

        return $this->view(compact('users'));
    }

    public function detail($id)
    {
        $user = User::findOrFail($id);

        return $this->view(compact('user'));
    }

    public function form()
    {
        $form = new Form(new User);
        $form->text('name')->rules('required|max:10');
        $form->image('profile.avatar');

        return $form;
    }

}
