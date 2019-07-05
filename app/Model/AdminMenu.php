<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class AdminMenu extends Model
{

    public function fields()
    {
        return ['title', 'icon'];
    }

    /**
     * 获取适用于请求的验证规则。
     *
     * @return array
     */
    public function validationRules()
    {
        return [
            'title' => 'required|max:15',
            'icon' => 'required',
        ];
    }

    /**
     * 获取已定义的验证规则的错误消息。
     *
     * @return array
     */
    public function validationMessages()
    {
        return [
            'title.required' => 'A title is required',
            'icon.required'  => 'A message is required',
        ];
    }

    /**
     * A Menu belongs to many roles.
     *
     * @return BelongsToMany
     */
    public function roles() : BelongsToMany
    {
        return $this->belongsToMany(AdminRoleMenu::class, 'admin_role_menus', 'menu_id', 'role_id');
    }

    /**
     * Format data to tree like array.
     *
     * @return array
     */
    public function toTree()
    {
        return $this->buildNestedArray();
    }

    /**
     * 获取所有节点
     *
     * @return mixed
     */
    public function allNodes()
    {
        return AdminMenu::all()->toArray();
    }

    /**
     * 获取节点名称
     *
     * @return string
     */
    public function elementId()
    {
        return 'tree-' . uniqid();
    }

    /**
     * Get options for Select field in form.
     *
     * @return array
     */
    public static function selectOptions()
    {
        return (new static())->buildSelectOptions();
    }

    /**
     * Build Nested array.
     *
     * @param array $nodes
     * @param int   $parentId
     *
     * @return array
     */
    protected function buildNestedArray(array $nodes = [], $parentId = 0)
    {
        $branch = [];

        if (empty($nodes)) {
            $nodes = $this->allNodes();
        }

        foreach ($nodes as $node) {
            if ($node['parent_id'] == $parentId) {
                $children = $this->buildNestedArray($nodes, $node[$this->getKeyName()]);

                if ($children) {
                    $node['children'] = $children;
                }

                $branch[] = $node;
            }
        }

        return $branch;
    }

    /**
     * Build options of select field in form.
     *
     * @param array  $nodes
     * @param int    $parentId
     * @param string $prefix
     * @param string $space
     *
     * @return array
     */
    protected function buildSelectOptions(array $nodes = [], $parentId = 0, $prefix = '', $space = '&nbsp;')
    {
        $prefix = $prefix ?: '┝'.$space;

        $options = [];

        if (empty($nodes)) {
            $nodes = $this->allNodes();
        }

        foreach ($nodes as $index => $node) {
            if ($node['parent_id'] == $parentId) {
                $node['title'] = $prefix.$space.$node['title'];

                $childrenPrefix = str_replace('┝', str_repeat($space, 6), $prefix).'┝'.str_replace(['┝', $space], '', $prefix);

                $children = $this->buildSelectOptions($nodes, $node[$this->getKeyName()], $childrenPrefix);

                $options[$node[$this->getKeyName()]] = $node['title'];

                if ($children) {
                    $options += $children;
                }
            }
        }

        return $options;
    }

}
