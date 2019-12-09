<?php
/**
 * PHP表单生成器
 *
 * @package  FormBuilder
 * @author   xaboy <xaboy2005@qq.com>
 * @version  2.0
 * @license  MIT
 * @link     https://github.com/xaboy/form-builder
 */

namespace FormBuilder\Rule;


use FormBuilder\Contract\FormComponentInterface;

trait ChildrenRule
{
    /**
     * 组件的插槽名称,如果组件是其它组件的子组件，需为插槽指定名称
     *
     * @var
     */
    protected $slot;

    /**
     * 设置父级组件的插槽,默认为default.可配合 slot 配置项使用
     *
     * @var
     */
    protected $children = [];

    /**
     * @param $slot
     * @return $this
     */
    public function slot($slot)
    {
        $this->slot = (string)$slot;
        return $this;
    }

    /**
     * @param array $children
     * @return $this
     */
    public function children(array $children)
    {
        $this->children = $children;
        return $this;
    }

    /**
     * @param string|array|self $child
     * @return $this
     */
    public function appendChild($child)
    {
        $this->children[] = $child;
        return $this;
    }

    /**
     * @param array $children
     * @return $this
     */
    public function appendChildren($children)
    {
        $this->children[] = array_merge($this->children, $children);
        return $this;
    }

    public function getSlot()
    {
        return $this->slot;
    }

    public function getChildren()
    {
        return $this->children;
    }

    protected function parseChildren($child)
    {
        return $child instanceof FormComponentInterface ? $child->build() : $child;
    }

    /**
     * @return array
     */
    public function parseChildrenRule()
    {
        if (!count($this->children)) return [];
        $children = [];
        foreach ($this->children as $child) {
            $children[] = $this->parseChildren($child);
        }

        return compact('children');
    }
}