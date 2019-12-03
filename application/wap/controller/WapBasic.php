<?php
namespace app\wap\controller;
use think\Controller;
use app\wap\model\Users;
use think\facade\Request;
use think\facade\Cookie;
use think\facade\Url;

class WapBasic extends Controller{

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