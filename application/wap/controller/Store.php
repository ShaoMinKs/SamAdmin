<?php
namespace app\wap\controller;
use app\wap\model\store\StoreCategory;
use app\wap\model\store\StoreProduct;
use app\wap\model\store\StoreProductRelation;
use app\wap\model\store\StoreCart;
use app\wap\model\store\StoreOrder;
use app\wap\model\user\Users;

class Store extends AuthWap {

    public function index($keyword = '',$cid = '',$sid = '')
    {
        if($keyword != '') $keyword = base64_decode($keyword);
        $keyword = addslashes($keyword);
        $cid = intval($cid);
        $sid = intval($sid);
        $category = null;
        if($sid)
            $category = StoreCategory::get($sid);
        if($cid && !$category)
            $category = StoreCategory::get($cid);
        $this->assign(compact('keyword','cid','sid','category'));
        return $this->fetch();
    }

    /**
     * 商品详情
     */
    public function detail($id){
        if(!$id || !$storeInfo = StoreProduct::getValidProduct($id)) {
            return $this->failed('商品不存在或已下架！');
        }
        $storeInfo['userLike'] = StoreProductRelation::isProductRelation($id,$this->userInfo['user_id'],'like');
        $storeInfo['like_num'] = StoreProductRelation::productRelationNum($id,'like');
        $storeInfo['userCollect'] = StoreProductRelation::isProductRelation($id,$this->userInfo['user_id'],'collect');
        $this->assign([
            'storeInfo'  => $storeInfo
        ]);
        return $this->fetch();
    }

    /**
     * 确认订单
     */
    public function confirm_order($cartId = ''){
        if(!is_string($cartId) || !$cartId){
            return $this->failed('缺少参数');
        }
        $cartGroup = StoreCart::getUserProductCartList($this->userInfo['user_id'],$cartId,1);
        if(count($cartGroup['invalid']))
            return $this->failed($cartGroup['invalid'][0]['productInfo']['store_name'].'已失效!');
        if(!$cartGroup['valid']) return $this->failed('请提交购买的商品!');
        $cartInfo = $cartGroup['valid'];
        $priceGroup = StoreOrder::getOrderPriceGroup($cartInfo);
        $orderKey = StoreOrder::cacheOrderInfo($this->userInfo['user_id'],$cartInfo,$priceGroup,'');
        $recProduct = storeProduct::scope('valid')->where('cate_id', 'IN', function($query){
            $query->name('store_category')->where('type', 1)->field('id');
        })->order('id DESC')->field('id,image,store_name,compose_price,price')->limit(5)->select();
        foreach($recProduct as $v){
            $v['product_num'] = 0;
        }
        $this->assign([
            'cartInfo'=>$cartInfo,
            'priceGroup'=>$priceGroup,
            'orderKey'=>$orderKey,
            'userInfo'=>Users::getUserInfo($this->userInfo['user_id']),
            'recProduct'=>$recProduct,
        ]);
        return $this->fetch();
    }
}