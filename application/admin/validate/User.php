<?php
namespace app\admin\validate;
use think\Validate;

class User extends Validate{

    protected $rule = [
        'nickname'     => 'require|unique:users',
        'email'        => 'email',
        'password'     => 'require|confirm',
        'mobile'       => 'require|mobile|unique:users',
        '__token__'    => 'token',
        'user_id'      => 'require'
    ];

    protected $message = [
        'nickname.require'     => '昵称不得为空',
        'nickname.unique'      => '该昵称已经存在',
        'mobile.unique'        => '该手机号码已存在',
        'email.email'          => '邮箱格式不正确',
        'mobile.mobile'        => '手机格式不正确',
        'mobile.mobile'        => '手机不得为空',
        'password.require'     => '密码不得为空',
        'password.confirm'     => '两次密码输入不一致',
    ];

       //验证场景
       protected $scene = [
        'add'  => ['nickname', 'email','password','mobile'],
        'edit' => ['nickname', 'email','mobile'],
        'del'  => ['user_id']
    ];
}