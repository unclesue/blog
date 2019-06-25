<?php

namespace App\Http\Controllers\Admin;

use App\Model\AdminMenu;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class MenuController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth.admin');
    }

    public function index()
    {
        $adminMenu = new AdminMenu();
        $id = $adminMenu->elementId();
        $tree = $adminMenu->toTree();

        return view('admin.menu', compact('tree', 'id'));
    }

    public function save(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'required|max:15',
            'icon' => 'required',
        ]);
        print_r($validatedData);die;
    }

}
