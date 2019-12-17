<?php
namespace app\wap\model\store;
use traits\ModelTrait;
use basic\ModelBasic;
use think\facade\Cache;
use app\wap\model\user\Users;
use app\wap\model\user\UserAddress;
use app\wap\model\store\StoreOrderStatus;
use app\wap\model\user\WechatFans;
use app\core\util\WechatService;
use app\wap\model\user\UserBill;
use behavior\wechat\PaymentBehavior;
use service\HookService;
use think\Loader;
use think\facade\Hook;

class StoreOrder  extends ModelBasic {

    use ModelTrait;
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
    /**
     * 获取缓存订单信息
     */
    public static function getCacheOrderInfo($uid,$key)
    {
        $cacheName = 'user_order_'.$uid.$key;
        if(!Cache::has($cacheName)) return null;
        return Cache::get($cacheName);
    }

    /**
     * 删除缓存订单信息
     */
    public static function clearCacheOrderInfo($uid,$key)
    {
        Cache::clear('user_order_'.$uid.$key);
    }

    /**
     * 生成订单
     */
    public static function cacheKeyCreateOrder($uid,$key,$addressId,$payType,$useIntegral = false,$couponId = 0,$mark = '',$combinationId = 0,$pinkId = 0,$seckill_id=0,$bargainId = 0)
    {
        if(!array_key_exists($payType,self::$payType)) return self::setErrorInfo('选择支付方式有误!');
        if(self::be(['unique'=>$key,'uid'=>$uid])) return self::setErrorInfo('请勿重复提交订单');
        $userInfo = Users::getUserInfo($uid);
        if(!$userInfo) return  self::setErrorInfo('用户不存在!');
        $cartGroup = self::getCacheOrderInfo($uid,$key);
        if(!$cartGroup) return self::setErrorInfo('订单已过期,请刷新当前页面!');
        $cartInfo = $cartGroup['cartInfo'];
        $priceGroup = $cartGroup['priceGroup'];
        $other = isset($cartGroup['other']) ? $cartGroup['other'] : '';
        $payPrice = $priceGroup['totalPrice'];
        $payPostage = $priceGroup['storePostage'];
        if(!$addressId) return self::setErrorInfo('请选择收货地址!');
        if(!UserAddress::be(['uid'=>$uid,'id'=>$addressId,'is_del'=>0]) || !($addressInfo = UserAddress::find($addressId)))
            return self::setErrorInfo('地址选择有误!'); 
        //使用优惠劵
        $res1 = true;
        if($couponId){
            $couponInfo = StoreCouponUser::validAddressWhere()->where('id',$couponId)->where('uid',$uid)->find();
            if(!$couponInfo) return self::setErrorInfo('选择的优惠劵无效!');
            if($couponInfo['use_min_price'] > $payPrice)
                return self::setErrorInfo('不满足优惠劵的使用条件!');
            $payPrice = bcsub($payPrice,$couponInfo['coupon_price'],2);
            $res1 = StoreCouponUser::useCoupon($couponId);
            $couponPrice = $couponInfo['coupon_price'];
        }else{
            $couponId = 0;
            $couponPrice = 0;
        }
        if(!$res1) return self::setErrorInfo('使用优惠劵失败!');
   
        //是否包邮
        if((isset($other['offlinePostage'])  && $other['offlinePostage'] && $payType == 'offline')) $payPostage = 0;
        $payPrice = bcadd($payPrice,$payPostage,2);
        //积分抵扣
        $res2 = true;
        if($useIntegral && $userInfo['integral'] > 0){
            $deductionPrice = bcmul($userInfo['integral'],$other['integralRatio'],2);
            if($deductionPrice < $payPrice){
                $payPrice = bcsub($payPrice,$deductionPrice,2);
                $usedIntegral = $userInfo['integral'];
                $res2 = false !== User::edit(['integral'=>0],$userInfo['uid'],'uid');
            }else{
                $deductionPrice = $payPrice;
                $usedIntegral = bcdiv($payPrice,$other['integralRatio'],2);
                $res2 = false !== User::bcDec($userInfo['uid'],'integral',$usedIntegral,'uid');
                $payPrice = 0;
            }
            $res2 = $res2 && false != UserBill::expend('积分抵扣',$uid,'integral','deduction',$usedIntegral,$key,bcsub($userInfo['integral'],$usedIntegral,2),'购买商品使用'.floatval($usedIntegral).'积分抵扣'.floatval($deductionPrice).'元');
        }else{
            $deductionPrice = 0;
            $usedIntegral = 0;
        }
        if(!$res2) return self::setErrorInfo('使用积分抵扣失败!');
        $cartIds = [];
        $totalNum = 0;
        $gainIntegral = 0;
        foreach ($cartInfo as $cart){
                $cartIds[] = $cart['id'];
                $totalNum += $cart['cart_num'];
                $gainIntegral = bcadd($gainIntegral,$cart['productInfo']['give_integral'],2);
        }
        $orderInfo = [
            'uid'=>$uid,
            'order_id'=>self::getNewOrderId(),
            'real_name'=>$addressInfo['real_name'],
            'user_phone'=>$addressInfo['phone'],
            'user_address'=>$addressInfo['province'].' '.$addressInfo['city'].' '.$addressInfo['district'].' '.$addressInfo['detail'],
            'cart_id'=>$cartIds,
            'total_num'=>$totalNum,
            'total_price'=>$priceGroup['totalPrice'],
            'total_postage'=>$priceGroup['storePostage'],
            'coupon_id'=>$couponId,
            'coupon_price'=>$couponPrice,
            'pay_price'=>$payPrice,
            'pay_postage'=>$payPostage,
            'deduction_price'=>$deductionPrice,
            'paid'=>0,
            'pay_type'=>$payType,
            'use_integral'=>$usedIntegral,
            'gain_integral'=>$gainIntegral,
            'mark'=>htmlspecialchars($mark),
            'combination_id'=>$combinationId,
            'pink_id'=>$pinkId,
            'seckill_id'=>$seckill_id,
            'bargain_id'=>$bargainId,
            'cost'=>$priceGroup['costPrice'],
            'unique'=>$key
        ];
        $order = self::set($orderInfo);
        if(!$order)return self::setErrorInfo('订单生成失败!');
        $res5 = true;
        foreach ($cartInfo as $cart){
            //减库存加销量
            if($combinationId) $res5 = $res5 && StoreCombination::decCombinationStock($cart['cart_num'],$combinationId);
            else if($seckill_id) $res5 = $res5 && StoreSeckill::decSeckillStock($cart['cart_num'],$seckill_id);
            else if($bargainId) $res5 = $res5 && StoreBargain::decBargainStock($cart['cart_num'],$bargainId);
            else $res5 = $res5 && StoreProduct::decProductStock($cart['cart_num'],$cart['productInfo']['id'],isset($cart['productInfo']['attrInfo']) ? $cart['productInfo']['attrInfo']['unique']:'');

         }
        //保存购物车商品信息
        $res4 = false !== StoreOrderCartInfo::setCartInfo($order['id'],$cartInfo);
        //购物车状态修改
        $res6 = false !== StoreCart::where('id','IN',$cartIds)->update(['is_pay'=>1]);
        if(!$res4 || !$res5 || !$res6) return self::setErrorInfo('订单生成失败!');
       
        self::clearCacheOrderInfo($uid,$key);
        self::commitTrans();
        StoreOrderStatus::status($order['id'],'cache_key_create_order','订单生成');
        return $order;
    }


    /**
     * 微信支付
     * @param string $orderId 订单ID
     */
    public static function jsPay($orderId,$field = 'order_id')
    {
        if(is_string($orderId))
            $orderInfo = self::where($field,$orderId)->find();
        else
            $orderInfo = $orderId;
        if(!$orderInfo || !isset($orderInfo['paid'])) exception('支付订单不存在!');
        if($orderInfo['paid']) exception('支付已支付!');
        if($orderInfo['pay_price'] <= 0) exception('该支付无需支付!');
        $openid = WechatFans::uidToOpenid($orderInfo['uid']);
        return WechatService::jsPay($openid,$orderInfo['order_id'],$orderInfo['pay_price'],'product',SystemConfigService::get('site_name'));
    }
    
    /**
     * 生成订单号
     */
    public static function getNewOrderId()
    {
        $count = (int) self::where('add_time',['>=',strtotime(date("Y-m-d"))],['<',strtotime(date("Y-m-d",strtotime('+1 day')))])->count();
        return 'wx'.date('YmdHis',time()).(10000+$count+1);
    }

    /**
     * 余额支付
     */
    public static function yuePay($order_id,$uid)
    {
        $orderInfo = self::where('uid',$uid)->where('order_id',$order_id)->where('is_del',0)->find();
        if(!$orderInfo) return self::setErrorInfo('订单不存在!');
        if($orderInfo['paid']) return self::setErrorInfo('该订单已支付!');
        if($orderInfo['pay_type'] != 'yue') return self::setErrorInfo('该订单不能使用余额支付!');
        $userInfo = Users::getUserInfo($uid);
        if($userInfo['user_money'] < $orderInfo['pay_price'])
            return self::setErrorInfo('余额不足'.floatval($orderInfo['pay_price']));
        try{
            self::beginTrans();
            $res1 = false !== Users::bcDec($uid,'user_money',$orderInfo['pay_price'],'user_id');
            $res2 = UserBill::expend('购买商品',$uid,'user_money','pay_product',$orderInfo['pay_price'],$orderInfo['id'],bcsub($userInfo['user_money'],$orderInfo['pay_price'],2),'余额支付'.floatval($orderInfo['pay_price']).'元购买商品');
            $res3 = self::paySuccess($order_id);
             HookService::listen('yue_pay_product',$orderInfo,false,PaymentBehavior::class);
            self::commitTrans();
        }catch (\Exception $e){
            self::rollbackTrans();
            return self::setErrorInfo($e->getMessage());
        }
        return true;
    }

        /**
     * //TODO 支付成功后
     * @param $orderId
     * @param $notify
     * @return bool
     */
    public static function paySuccess($orderId)
    {
        $order = self::where('order_id',$orderId)->find();
        $resPink = true;
        Users::bcInc($order['uid'],'pay_count',1,'user_id');
        $res1 = self::where('order_id',$orderId)->update(['paid'=>1,'pay_time'=>time()]);
        $oid = self::where('order_id',$orderId)->value('id');
        StoreOrderStatus::status($oid,'pay_success','用户付款成功');
        // WechatTemplateService::sendTemplate(WechatFans::uidToOpenid($order['uid']),WechatTemplateService::ORDER_PAY_SUCCESS, [
        //     'first'=>'亲，您购买的商品已支付成功',
        //     'keyword1'=>$orderId,
        //     'keyword2'=>$order['pay_price'],
        //     'remark'=>'点击查看订单详情'
        // ],Url::build('wap/My/order',['uni'=>$orderId],true,true));
        // WechatTemplateService::sendAdminNoticeTemplate([
        //     'first'=>"亲,您有一个新订单 \n订单号:{$order['order_id']}",
        //     'keyword1'=>'新订单',
        //     'keyword2'=>'已支付',
        //     'keyword3'=>date('Y/m/d H:i',time()),
        //     'remark'=>'请及时处理'
        // ]);
        $res = $res1;
        return false !== $res;
    }
}