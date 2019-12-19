<?php
namespace app\wap\controller;
use think\Controller;
use service\JsonService;
use think\facade\Url;
use service\UtilService;
use app\core\util\WechatService;
use think\facade\Session;
use service\HookService;

class BaseWap extends Controller {


    /**
     * 微信登录
     */
    public function wechatOauth(){
        $openid = Session::get('login_openid','wap');
        if($openid) return $this->redirect(Url::build('Index/index'));
        // if(!UtilService::isWechatBrowser()) exit($this->failed('请在微信客户端打开链接'));
        try{
            $wechatInfo = WechatService::getOriginal();
        }catch (\Exception $e){
            exit(Header("Location:".WechatService::getOauthRedirect($this->request->url(true),'base')));
        }
        if(!isset($wechatInfo['nickname'])){
            $wechatInfo = WechatService::getUserInfo($wechatInfo['openid']);
            if(!$wechatInfo['subscribe'] && !isset($wechatInfo['nickname']))
                exit(WechatService::oauthService()->scopes(['snsapi_userinfo'])
                    ->redirect($this->request->url(true))->send());
            if(isset($wechatInfo['tagid_list']))
                $wechatInfo['tagid_list'] = implode(',',$wechatInfo['tagid_list']);
        }else{
            if(isset($wechatInfo['privilege'])) unset($wechatInfo['privilege']);
            $wechatInfo['subscribe'] = 0;
        }
        $openid = $wechatInfo['openid'];
        HookService::afterListen('wechat_oauth',$wechatInfo,false,UserBehavior::class);
        Session::set('login_openid',$openid,'wap');
        return $this->redirect(Url::build('Index/index'));
    }
    
        /**
     * 操作失败 弹窗提示
     * @param string $msg
     * @param int $url
     * @param string $title
     */
    protected function failed($msg = '操作失败', $url = 0, $title='信息提示')
    {
        if($this->request->isAjax()){
            exit(JsonService::fail($msg,$url)->getContent());
        }else {
            $this->assign(compact('title', 'msg', 'url'));
            exit($this->fetch('public/error'));
        }
    }

    /**
     * 操作成功 弹窗提示
     * @param $msg
     * @param int $url
     */
    protected function successful($msg = '操作成功', $url = 0, $title='成功提醒')
    {
        if($this->request->isAjax()){
            exit(JsonService::successful($msg,$url)->getContent());
        }else {
            $this->assign(compact('title', 'msg', 'url'));
            exit($this->fetch('public/success'));
        }
    }

    public function _empty($name)
    {
        $url = strtolower($name) == 'index' ? Url::build('Index/index','',true,true) : 0;
        return $this->failed('请求页面不存在!',$url);
    }
}