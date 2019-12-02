<?php

namespace app\admin\controller;

use anerg\OAuth2\OAuth;
use think\facade\Config;

class Sns
{
    private $config;

    /**
     * 第三方登录，执行跳转操作
     *
     * @param string $name 第三方渠道名称，目前可用的为：weixin,qq,weibo,alipay,facebook,twitter,line,google
     */
    public function login($name)
    {
        //获取配置
        $this->config = Config::get('sns.' . $name);

        //设置回跳地址
        $this->config['callback'] = $this->makeCallback($name);

        //可以设置代理服务器，一般用于调试国外平台
        $this->config['proxy'] = 'http://127.0.0.1:1080';

        /**
         * 对于微博，如果登录界面要适用于手机，则需要设定->setDisplay('mobile')
         *
         * 对于微信，如果是公众号登录，则需要设定->setDisplay('mobile')，否则是WEB网站扫码登录
         *
         * 其他登录渠道的这个设置没有任何影响，为了统一，可以都写上
         */
        return redirect(OAuth::$name($this->config)->setDisplay('mobile')->getRedirectUrl());

        /**
         * 如果需要微信代理登录，则需要：
         *
         * 1.将wx_proxy.php放置在微信公众号设定的回调域名某个地址，如 http://www.abc.com/proxy/wx_proxy.php
         * 2.config中加入配置参数proxy_url，地址为 http://www.abc.com/proxy/wx_proxy.php
         *
         * 然后获取跳转地址方法是getProxyURL，如下所示
         */
        $this->config['proxy_url'] = 'http://www.abc.com/proxy/wx_proxy.php';
        return redirect(OAuth::$name($this->config)->setDisplay('mobile')->getProxyURL());
    }

    public function callback($name)
    {
        //获取配置
        $this->config = Config::get('sns.' . $name);

        //设置回跳地址
        $this->config['callback'] = $this->makeCallback($name);

        //获取格式化后的第三方用户信息
        $snsInfo = OAuth::$name($this->config)->userinfo();

        //获取第三方返回的原始用户信息
        $snsInfoRaw = OAuth::$name($this->config)->userinfoRaw();

        //获取第三方openid
        $openid = OAuth::$name($this->config)->openid();
    }

    /**
     * 生成回跳地址
     *
     * @return string
     */
    private function makeCallback($name)
    {
        //注意需要生成完整的带http的地址
        return url('/sns/callback/' . $name, '', 'html', true);
    }
}