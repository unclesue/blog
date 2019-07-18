<?php

namespace App\Http\Controllers\Admin;

use App\Http\Form\Form;
use App\Http\Grid\Grid;
use App\Model\User;

class UserController extends AdminController
{

    public function grid()
    {
        $grid = new Grid(new User());
        $grid->id();
        $grid->name();

        return $grid;
    }

    public function detail($id)
    {
        $user = User::findOrFail($id);

        return $this->view(compact('user', 'content'));
    }

    public function form()
    {
        $form = new Form(new User);
        $form->text('name')->rules('required|max:10');
        $form->text('email')->rules('required|max:10');
        //$form->image('profile.avatar');

        return $form;
    }

}
