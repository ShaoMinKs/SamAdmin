<?php


namespace app\wap\model\user;


use basic\ModelBasic;
use traits\ModelTrait;

class UserBill extends ModelBasic
{
    use ModelTrait;

    protected $insert = ['add_time'];

    protected function setAddTimeAttr()
    {
        return time();
    }

    /**
     * 收入
     */
    public static function income($title,$uid,$category,$type,$number,$link_id = 0,$balance = 0,$mark = '',$status = 1)
    {
        $pm = 1;
        return self::set(compact('title','uid','link_id','category','type','number','balance','mark','status','pm'));
    }

    /**
     * 支出
     */
    public static function expend($title,$uid,$category,$type,$number,$link_id = 0,$balance = 0,$mark = '',$status = 1)
    {
        $pm = 0;
        return self::set(compact('title','uid','link_id','category','type','number','balance','mark','status','pm'));
    }

}