<?php
namespace app\wap\controller;
use think\Controller;
use service\UtilService;
use think\Url;
use think\Session;

class Login extends BaseWap {

    /**
     * 登录
     */
    public function index(){
        if(UtilService::isWechatBrowser()){
            $this->loginOut();
            $openid = $this->wechatOauth();
            exit($this->redirect(Url::build('Index/index')));
        }
        return $this->fetch();
    }

    /**
     * 退出登录
     */
    public function loginOut(){
        Session::clear('wap');
    }
}