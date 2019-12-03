<?php
namespace app\admin\model;
use think\Model;

class Users extends Model {
    
    protected $pk = 'user_id';

    public function getRegTimeAttr($value){
        return date('Y-m-d',$value);
    }

}