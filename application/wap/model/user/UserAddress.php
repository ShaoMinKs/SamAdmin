<?php

namespace app\wap\model\user;
use basic\ModelBasic;
use traits\ModelTrait;

class UserAddress extends ModelBasic {

    use ModelTrait;

    protected $insert = ['add_time'];

    protected function setAddTimeAttr()
    {
        return time();
    }

    /**
     * 设置默认地址
     */
    public static function setDefaultAddress($id,$uid)
    {
        self::beginTrans();
        $res1 = self::where('uid',$uid)->update(['is_default'=>0]);
        $res2 = self::where('id',$id)->where('uid',$uid)->update(['is_default'=>1]);
        $res =$res1 !== false && $res2 !== false;
        self::checkTrans($res);
        return $res;
    }

    /**
     * 获取用户收货地址
     */
    public static function getUserValidAddressList($uid,$field = '*')
    {
        return self::where('uid',$uid)->order('add_time DESC')->field($field)->select()->toArray()?:[];
    }

    /**
     * 获取默认地址
     */
    public static function getUserDefaultAddress($uid,$field = '*')
    {
        return self::where('uid',$uid)->where('is_default',1)->field($field)->find();
    }
}