<?php

namespace app\wap\model\store;


use basic\ModelBasic;
use traits\ModelTrait;

class StoreOrderCartInfo extends ModelBasic
{
    use ModelTrait;

    public static function getCartInfoAttr($value)
    {
        return json_decode($value,true)?:[];
    }

    public static function setCartInfo($oid,array $cartInfo)
    {
        $group = [];
        foreach ($cartInfo as $cart){
            $group[] = [
                'oid'=>$oid,
                'cart_id'=>$cart['id'],
                'product_id'=>$cart['productInfo']['id'],
                'cart_info'=>json_encode($cart),
                'unique'=>md5($cart['id'].''.$oid)
            ];
        }
        return self::setAll($group);
    }

    public static function getProductNameList($oid)
    {
        $cartInfo = self::where('oid',$oid)->select();
        $goodsName = [];
        foreach ($cartInfo as $cart){
            $suk = isset($cart['cart_info']['productInfo']['attrInfo']) ? '('.$cart['cart_info']['productInfo']['attrInfo']['suk'].')' : '';
            $goodsName[] = $cart['cart_info']['productInfo']['store_name'].$suk;
        }
        return $goodsName;
    }

}