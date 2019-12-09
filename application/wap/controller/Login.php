<?php
namespace app\wap\controller;
use think\Controller;
use service\UtilService;
use think\facade\Url;
use think\facade\Session;
use think\facade\Cookie;
use think\Request;
use app\wap\model\user\Users;

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

    /**
     * 登录验证
     */
    public function checkLogin(Request $request){
       list($account,$pwd) =  UtilService::postMore(['account','pwd'],$request,true);
       if(!$account || !$pwd) return $this->failed('请输入登录账号');
       if(!Users::be(['account'=>$account])) return $this->failed('账号不存在！');
       $userInfo  = Users::get(['account'=>$account]);
       $errorInfo = Session::get('login_err_info','wap')?:['nums'=>0];
       $now = time();
       if($errorInfo['nums'] > 3 && $errorInfo['time'] < ($now - 900)) return $this->failed('错误次数过多,请稍候再试!');
       if($userInfo['password'] != md5($pwd)) {
           Session::set('login_err_info',['nums'=>$errorInfo['nums'] + 1,'time'=>$now],'wap');
           return $this->failed('账号或密码错误');
       }
       if($userInfo['is_lock']) return $this->failed('账号被锁定');
       Session::set('login_uid',$userInfo['user_id'],'wap');
       $userInfo['last_login']  = time();
       $userInfo['last_ip']     = $request->ip();
       $userInfo->save();
       Session::delete('login_err_info');
       Cookie::set('is_login',1);
       return $this->successful('登录成功',Url::build('Index/index'));
    }
}