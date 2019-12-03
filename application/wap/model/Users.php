<?php
namespace app\wap\model;
use basic\ModelBasic;
use traits\ModelTrait;
use think\facade\Request;
use think\facade\Session;

class Users  extends ModelBasic{

    use ModelTrait;
    protected $insert = ['reg_time','last_login','last_ip'];

    protected function setRegTimeAttr($value){
        return time();
    }
    protected function setLastLogin($value){
        return  time();
    }
    protected function setLastIp($value){
        return Request::ip();
    }

    /**
     * 根据UID获取用户信息
     * @param int $uid 用户id
     * @return array $userinfo;
     */
    public static function  getUserInfo($uid){
        $userinfo = self::where('uid',$uid)->find();
        if(!$userinfo) exception('无此用户信息');
        return $userinfo->toArray();
    }

    /**
     * 获取当前用户登录UID
     */
    public static function getSessionId(){
        $uid = null;
        if(Session::has('login_uid','wap')){
            $uid = Session::get('login_uid','wap');
        }
        if(!$uid && Session::has('login_openid','wap') && ($openid = Session::get('login_openid','wap'))){
          
        }
        if(!$uid) exit(exception('请登录'));
        return $uid;
    }
}