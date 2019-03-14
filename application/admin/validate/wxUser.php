<?php
namespace app\admin\validate;
use think\Validate;

class WxUser extends Validate{

    protected $rule = [
        'wxname'    => 'require|token',
        'appid'     => 'require',
        'appsecret' => 'require',
        'token'     => 'require',
        '__token__' => 'token',
    ];

    protected $message = [
        'wxname.require'    => '名称不得为空',
        'wxname.token'    => '名称不得为空',
        'appid.require'  => 'Appid不能为空',
        'appsecret.require'  => 'appsecret不得为空',
        'token.require'  => 'token不能为空',
    ];

}