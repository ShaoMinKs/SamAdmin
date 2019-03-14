<?php
namespace app\admin\validate;
use think\Validate;

class Link extends Validate{

    protected $rule = [
        'link_name'     => 'require|unique:link',
        'link_url'     => 'require',
        '__token__' => 'token',
    ];

    protected $message = [
        'link_name.require'    => '链接名称不能为空',
        'link_url.require'  => '链接地址不能为空',
    ];

       //验证场景
       protected $scene = [
        'add'  => ['link_name', 'link_url'],
        'edit' => ['link_name.require', 'link_url'],
        'del'  => ['link_id']
    ];
}