<?php
namespace app\wap\controller;
use think\Controller;
use app\wap\model\user\Users;
use think\facade\Request;
use think\facade\Cookie;
use think\facade\Url;

class AuthWap extends BaseWap{

    protected $uid;
    protected $userInfo;

    public function initialize(){
        parent::initialize();
        try {
            $uid = Users::getSessionId();
        } catch (\Exception $e) {
           Cookie::set('is_login',0);
           $this->redirect(Url::build('Login/index'));
        }
        $this->userInfo = Users::getUserInfo($uid);
        if(!$this->userInfo || !isset($this->userInfo['user_id'])) return $this->failed('读取用户信息失败!');
        if($this->userInfo['is_lock']) return $this->failed('已被禁止登陆!');
        $this->uid = $this->userInfo['user_id'];
        $this->assign('userInfo',$this->userInfo);
    }

}