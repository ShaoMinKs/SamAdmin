<?php
namespace app\wap\model\store;
use traits\ModelTrait;
use basic\ModelBasic;
use think\facade\Cache;

class StoreOrder  extends ModelBasic {

    protected $insert = ['add_time'];

    protected static $payType = ['weixin'=>'微信支付','yue'=>'余额支付','offline'=>'线下支付'];

    protected static $deliveryType = ['send'=>'商家配送','express'=>'快递配送'];

    protected function setAddTimeAttr()
    {
        return time();
    }

    protected function setCartIdAttr($value)
    {
        return is_array($value) ? json_encode($value) : $value;
    }

    protected function getCartIdAttr($value)
    {
        return json_decode($value,true);
    }

    /**
     * 获取购物车价格信息
     */
    public static function getOrderPriceGroup($cartInfo)
    {
        // $storePostage = floatval(SystemConfigService::get('store_postage'))?:0;
        // $storeFreePostage =  floatval(SystemConfigService::get('store_free_postage'))?:0;
        $storePostage     = 0;
        $storeFreePostage = 0;
        $totalPrice       = self::getOrderTotalPrice($cartInfo);
        $costPrice        = self::getOrderCostPrice($cartInfo);
        if(!$storeFreePostage) {
            $storePostage = 0;
        }else{
            foreach ($cartInfo as $cart){
                if(!$cart['productInfo']['is_postage'])
                    $storePostage = bcadd($storePostage,$cart['productInfo']['postage'],2);

            }
            if($storeFreePostage <= $totalPrice) $storePostage = 0;
        }
        return compact('storePostage','storeFreePostage','totalPrice','costPrice');
    }

    public static function getOrderTotalPrice($cartInfo)
    {
        $totalPrice = 0;
        foreach ($cartInfo as $cart){
            $totalPrice = bcadd($totalPrice,bcmul($cart['cart_num'],$cart['truePrice'],2),2);
        }
        return $totalPrice;
    }
    public static function getOrderCostPrice($cartInfo)
    {
        $costPrice=0;
        foreach ($cartInfo as $cart){
            $costPrice = bcadd($costPrice,bcmul($cart['cart_num'],$cart['costPrice'],2),2);
        }
        return $costPrice;
    }

    /**
     * 订单信息缓存
     */
    public static function cacheOrderInfo($uid,$cartInfo,$priceGroup,$other = [],$cacheTime = 600)
    {
        $key = md5(time());
        Cache::set('user_order_'.$uid.$key,compact('cartInfo','priceGroup','other'),$cacheTime);
        return $key;
    }
}