<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Model\AdminMenu;
use Illuminate\Http\Request;

class MenuController extends Controller
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

        return view('admin.menu', compact('tree', 'id'));
    }

    public function save(Request $request)
    {
        $this->validate($request, [
            'title' => 'required|max:15',
            'icon' => 'required',
        ]);

        AdminMenu::create($request->all());
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
