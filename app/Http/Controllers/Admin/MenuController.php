<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Model\AdminMenu;
use Illuminate\Http\Request;

class MenuController extends Controller
{

    use HasResourceActions;

    public function index(Request $request)
    {
        $adminMenu = new AdminMenu();
        $id = $adminMenu->elementId();
        $tree = $adminMenu->toTree();

        return view('admin.menu', compact('tree', 'id'));
    }

    /**
     * @param Request $request
     */
    protected function validateStore(Request $request)
    {
        $request->validate([
            'title' => 'required|max:15',
            'icon' => 'required',
        ]);
    }

    public function form()
    {
        return new AdminMenu;
    }

}
