<?php
namespace app\wap\model\user;
use basic\ModelBasic;
use traits\ModelTrait;
use think\facade\Request;
use think\facade\Session;
use think\facade\Cache;
use app\wap\model\user\Users;

class WechatFans extends ModelBasic {
    
        /**
     * 用uid获得openid
     * @param $uid
     * @return mixed
     */
    public static function uidToOpenid($uid,$update = false)
    {
        $cacheName = 'openid_'.$uid;
        $openid = Cache::get($cacheName);
        if($openid && !$update) return $openid;
        $wechat_id = Users::where('user_id',$uid)->value('wechat_id');
        $openid = self::where('id',$wechat_id)->value('openid');
        if(!$openid) exception('对应的openid不存在!');
        Cache::set($cacheName,$openid,0);
        return $openid;
    }
}