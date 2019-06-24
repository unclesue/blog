<?php

namespace App\Http\Controllers\Admin;

use App\Model\AdminMenu;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class MenuController extends Controller
{
    public function index()
    {
        $adminMenu = new AdminMenu();
        $id = $adminMenu->elementId();
        $tree = $adminMenu->toTree();

        return view('admin.menu', compact('tree', 'id'));
    }
}
