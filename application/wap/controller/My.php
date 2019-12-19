<?php
namespace app\wap\controller;
use app\wap\model\user\UserAddress;
use app\wap\model\store\StoreOrder;

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

    /**
     * 个人中心
     */
    public function index(){
        $this->assign([
            'orderStatusNum'=>StoreOrder::getOrderStatusNum($this->userInfo['user_id']),
            'menus'=> [
                ['id' => 1,'name'=>'我的积分','icon'=>'http://datong.crmeb.net/public/uploads/attach/2019/01/15/5c3dc7ee98a2e.png','url'=>'/wap/my/integral.html'],
                ['id' => 2,'name'=>'已收藏商品','icon'=>'http://datong.crmeb.net/public/uploads/attach/2019/01/15/5c3dc91cee6ed.png','url'=>'/wap/my/collect.html'],
                ['id' => 3,'name'=>'地址管理','icon'=>'http://datong.crmeb.net/public/uploads/attach/2019/01/15/5c3dc93937a48.png','url'=>'/wap/my/address.html'],
                ['id' => 4,'name'=>'我的余额','icon'=>'http://datong.crmeb.net/public/uploads/attach/2019/01/15/5c3dc865bb257.png','url'=>'/wap/my/balance.html'],
              
            ]
        ]);
        return $this->fetch();
    }

    /**
     * 收藏的商品
     */
    public function collect(){
        return $this->fetch();
    }


    /**
     * 余额明细
     */
    public function balance()
    {
        $this->assign([
            'userMinRecharge'=>100
        ]);
        return $this->fetch();
    }
    /**
     * 我的收货地址
     */
    public function address()
    {
        $this->assign([
            'address'=>UserAddress::getUserValidAddressList($this->userInfo['user_id'],'id,real_name,phone,province,city,district,detail,is_default')
        ]);
        return $this->fetch();
    }
}