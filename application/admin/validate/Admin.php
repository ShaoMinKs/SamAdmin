<?php
namespace app\admin\validate;
use think\validate;

class Admin extends validate {

    protected $rule = [
        'user_name'    =>'require|unique:admin',
        'email'   =>'require|email',
        'password'=>'require',
        'admin_id'=>'require|number',
    ];

    protected $message = [
        'user_name.require'    => '用户名必填',
        'user_name.unique'     => '已存在相同用户名',
        'email.require'        => '邮箱必填',
        'email.email'          => '邮箱格式错误',
        'password.require'     => '密码必填',
        'admin_id.require'     => 'ID必须',
        'admin_id.number'      => '参数错误！',
    ];

    protected $scene = [
        'add' =>['user_name','email','password'],
        'edit'=>['user_name','email','admin_id'],
        'del' =>['admin_id'],
    ];
}