<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Model\AdminMenu;
use Illuminate\Http\Request;

class MenuController extends Controller
{

    public function index(Request $request)
    {
        $adminMenu = new AdminMenu();
        $id = $adminMenu->elementId();
        $tree = $adminMenu->toTree();

        return view('admin.menu', compact('tree', 'id'));
    }

    public function form()
    {
        $form = new Form(new AdminMenu);

        return $form;
    }

}
