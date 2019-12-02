<?php
namespace app\admin\model;
use think\Model;

class Users extends Model {
    
    protected $pk = 'user_id';

    public function getRegTimeAttr($value){
        return date('Y-m-d',$value);
    }
<<<<<<< HEAD

    public function setPasswordAttr($value){
        return md5($value);
    }
=======
>>>>>>> 08fd3df2e645c5201063c7dfeb9266561fb5755c
}