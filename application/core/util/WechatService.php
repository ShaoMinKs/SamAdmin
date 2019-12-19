<?php

namespace app\core\util;
use service\HookService;
use behavior\wechat\PaymentBehavior;

class WechatService {

    private static $instance = null;
    /**
     * 微信配置
     */
    public static function options()
    {
        $wechat = SystemConfigService::more(['wechat_appid','wechat_appsecret','wechat_token','wechat_encodingaeskey','wechat_encode']);
        $payment = SystemConfigService::more(['pay_weixin_mchid','pay_weixin_client_cert','pay_weixin_client_key','pay_weixin_key','pay_weixin_open']);
        $config = [
            'app_id'=>isset($wechat['wechat_appid']) ? $wechat['wechat_appid']:'',
            'secret'=>isset($wechat['wechat_appsecret']) ? $wechat['wechat_appsecret']:'',
            'token'=>isset($wechat['wechat_token']) ? $wechat['wechat_token']:'',
            'guzzle' => [
                'timeout' => 10.0, // 超时时间（秒）
            ],
        ];
        if(isset($wechat['wechat_encode']) && (int)$wechat['wechat_encode']>0 && isset($wechat['wechat_encodingaeskey']) && !empty($wechat['wechat_encodingaeskey']))
            $config['aes_key'] =  $wechat['wechat_encodingaeskey'];
        if(isset($payment['pay_weixin_open']) && $payment['pay_weixin_open'] == 1){
            $config['payment'] = [
                'merchant_id'=>$payment['pay_weixin_mchid'],
                'key'=>$payment['pay_weixin_key'],
                'cert_path'=>realpath('.'.$payment['pay_weixin_client_cert']),
                'key_path'=>realpath('.'.$payment['pay_weixin_client_key']),
                //'notify_url'=>SystemConfigService::get('site_url').Url::build('wap/Wechat/notify')
                'notify_url'=>Request::instance()->domain().Url::build('wap/Wechat/notify')
            ];
        }
        return $config;
    }

    /**
     * 支付
     */
    public static function payService(){
        return new \Wechat\Pay(self::options());
    }


        /**
     * 支付
     */
    public static function templateService(){
        return new \Wechat\Template(self::options());
    }

        /**
     * 获得jsSdk支付参数
     * @param $openid
     * @param $out_trade_no
     * @param $total_fee
     * @param $attach
     * @param $body
     * @param string $detail
     * @param string $trade_type
     * @param array $options
     * @return array|string
     */
    public static function jsPay($openid, $out_trade_no, $total_fee, $attach, $body, $detail='', $trade_type='JSAPI', $options = [])
    {
        return self::payService()->createParamsForJsApi(self::paymentPrepare($openid,$out_trade_no,$total_fee,$attach,$body,$detail,$trade_type,$options));
    }

        /**
     * 获得下单ID
     * @param $openid
     * @param $out_trade_no
     * @param $total_fee
     * @param $attach
     * @param $body
     * @param string $detail
     * @param string $trade_type
     * @param array $options
     * @return mixed
     */
    public static function paymentPrepare($openid, $out_trade_no, $total_fee, $attach, $body, $detail='', $trade_type='JSAPI', $options = [])
    {

        $order = self::paymentOrder($openid,$out_trade_no,$total_fee,$attach,$body,$detail,$trade_type,$options);
        $result = self::payService()->createOrder($order);
        if ($result->return_code == 'SUCCESS' && $result->result_code == 'SUCCESS'){
            // try{
            //     HookService::listen('wechat_payment_prepare',$order,$result->prepay_id,false,PaymentBehavior::class);
            // }catch (\Exception $e){}
            return $result->prepay_id;
        }else{
            if($result->return_code == 'FAIL'){
                exception('微信支付错误返回：'.$result->return_msg);
            }else if(isset($result->err_code)){
                exception('微信支付错误返回：'.$result->err_code_des);
            }else{
                exception('没有获取微信支付的预支付ID，请重新发起支付!');
            }
            exit;
        }

    }

        /**
     * 生成支付订单对象
     * @param $openid
     * @param $out_trade_no
     * @param $total_fee
     * @param $attach
     * @param $body
     * @param string $detail
     * @param string $trade_type
     * @param array $options
     * @return Order
     */
    protected static function paymentOrder($openid,$out_trade_no,$total_fee,$attach,$body,$detail='',$trade_type='JSAPI',$options = [])
    {
        $total_fee = bcmul($total_fee,100,0);
        $order = array_merge(compact('openid','out_trade_no','total_fee','attach','body','detail','trade_type'),$options);
        if($order['detail'] == '') unset($order['detail']);
        return $order;
    }

    /**
     * 发送模板消息
     */
    public static function sendTemplate($openid,$templateId,array $data,$url = null,$defaultColor = null)
    {
        return self::templateService()->send($data);
    }
    

        /**
     * 微信支付成功回调接口
     */
    public static function handleNotify()
    {
        return HookService::listen('wechat_pay_success',$notify,null,true,PaymentBehavior::class);
    }
}
