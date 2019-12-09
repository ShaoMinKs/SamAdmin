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
    }

}