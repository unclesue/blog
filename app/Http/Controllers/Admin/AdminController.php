<?php

namespace App\Http\Controllers\Admin;


use App\Http\Layout\Content;

class AdminController
{
    use HasResourceActions;

    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Title';

    /**
     * Set description for following 4 action pages.
     *
     * @var array
     */
    protected $description = [
//        'index'  => 'Index',
//        'show'   => 'Show',
//        'edit'   => 'Edit',
//        'create' => 'Create',
    ];

    /**
     * Get content title.
     *
     * @return string
     */
    protected function title()
    {
        return $this->title;
    }

    /**
     * Index interface.
     *
     */
    public function index(Content $content)
    {
        return $content
            ->title($this->title())
            ->description('描述')
            ->body($this->grid());
    }

    /**
     * Show interface.
     * @param $id
     * @return mixed
     */
    public function show($id)
    {
        return $this->detail($id);
    }

    /**
     * Edit interface.
     *
     * @param mixed   $id
     * @param Content $content
     *
     * @return Content
     */
    public function edit($id, Content $content)
    {
        return $content
            ->title($this->title())
            ->description('edit')
            ->body($this->form());
    }

    /**
     * Create interface.
     */
    public function create()
    {
        $this->form();

        return $this->view();
    }

    /**
     * 获取路由名称渲染页面
     *
     * @param array $data
     * @param null|string $view
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function view($data = [], $view = null)
    {
        $routeName = $view === null ? 'admin.' . request()->route()->getName() : $view;

        return view($routeName, $data);
    }

}
