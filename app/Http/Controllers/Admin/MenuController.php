<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\AdminController;
use App\Model\AdminMenu;
use Illuminate\Http\Request;

class MenuController extends AdminController
{

    public function __construct()
    {
        $this->middleware('auth.admin');
    }

    public function index(Request $request)
    {
        $adminMenu = new AdminMenu();
        $id = $adminMenu->elementId();
        $tree = $adminMenu->toTree();
        $view = view('admin.menu', compact('tree', 'id'));

        return $this->render($view);
    }

    public function save(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'required|max:15',
            'icon' => 'required',
            'uri' => 'required',
        ]);
    }

    /**
     * 获取渲染内容
     * @param $view
     * @return mixed
     */
    public function render($view)
    {
        if(request()->pjax()) {
            $sections = $view->renderSections();
            return $sections['content'];
        }

        return $view;
    }

}
