<?php
namespace app\wap\controller;
use app\wap\model\user\UserAddress;

class My extends AuthWap {

    /**
     * 编辑地址
     */
    public function edit_address($addressId = '')
    {
        if($addressId && is_numeric($addressId) && UserAddress::be(['is_del'=>0,'id'=>$addressId,'uid'=>$this->userInfo['user_id']])){
            $addressInfo = UserAddress::find($addressId)->toArray();
        }else{
            $addressInfo = [];
        };
        $this->assign(compact('addressInfo'));
        return $this->fetch();
    }
}