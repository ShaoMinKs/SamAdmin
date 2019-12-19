<?php /*a:2:{s:74:"D:\phpstudy\PHPTutorial\WWW\SamAdmin\application\wap\view\login\index.html";i:1576763597;s:79:"D:\phpstudy\PHPTutorial\WWW\SamAdmin\application\wap\view\public\requirejs.html";i:1575644772;}*/ ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0,maximum-scale=1.0,minimum-scale=1.0,user-scalable=no">
    <meta name="browsermode" content="application"/>
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <!-- 禁止百度转码 -->
    <meta http-equiv="Cache-Control" content="no-siteapp">
    <!-- uc强制竖屏 -->
    <meta name="screen-orientation" content="portrait">
    <!-- QQ强制竖屏 -->
    <meta name="x5-orientation" content="portrait">
    <link rel="stylesheet" href="/public/wap/css/login.css">
    <script type="text/javascript" src="/public/plugins/requirejs/require.js"></script>
<script>
        requirejs.config({
            urlArgs: "v=15615616515616556",
            map: {
                '*': {
                    'css': '/public/plugins/requirejs/require-css.js'
                }
            },
            shim: {
                'iview': {
                    deps: ['css!iviewcss']
                },
                'layer': {
                    deps: ['css!layercss']
                },
                'ydui': {
                    deps: ['css!yduicss']
                },
                'vant': {
                    deps: ['css!vantcss']
                },
                'cityselect': {
                    deps: ['css!yduicss']
                }
            },
            baseUrl: '//' + location.hostname + '/public',
            paths: {
                'static': 'static',
                'vue': 'plugins/vue/dist/vue.min',
                'axios': 'plugins/axios.min',
                'iview': 'plugins/iview/dist/iview.min',
                'iviewcss': 'plugins/iview/dist/styles/iview',
                'lodash': 'plugins/lodash',
                'layer': 'plugins/layer/layer',
                'layercss': 'plugins/layer/theme/default/layer',
                'jquery': 'plugins/jquery-1.10.2.min',
                'moment': 'plugins/moment',
                'sweetalert': 'plugins/sweetalert2/sweetalert2.all.min',
                'helper':'plugins/helper',
                'store':'wap/module/store',
                'better-scroll':"plugins/better-scroll",
                'ydui':"plugins/ydui/ydui",
                'yduicss':"plugins/ydui/ydui-px",
                'vant':"plugins/vant/vant.min",
                'vantcss':"plugins/vant/vant",
                'cityselect':"plugins/ydui/cityselect",
                'reg-verify':"plugins/reg-verify"
            }
        });
    </script>
    <title>登录</title>
</head>
<style>
    .line_04 {
    overflow: hidden;
    _zoom: 1;
    color:#AFAFB0
}
.line_04 b {
    background: #AFAFB0;
    margin-top: 12px;
    float: left;
    width: 26%;
    height: 1px;
    _overflow: hidden;
}
.line_04 span {
    padding: 0 10px;
    width: 40%;
    float: left;
    text-align: center;
}
</style>
<body>
    <div class="app-block" id="login">
        <div class="cube"><img src="/public/wap/images/cube.png" class="img-responsive" alt="" /></div>
        <form onsubmit="return false">
            <div><input type="text"  v-model="account" required placeholder="请输入登录账号"/></div>
            <div><input type="password"  required v-model="password" placeholder="请输入登录密码"/></div>
            <div class="submit"  @click="wapLogin" style="display: block; height: 2.5rem;line-height:2.5rem;background-color: #56ca59;color: #fff;border-radius: 30px;cursor: pointer;"> {{login_text}}</div>
            <div class="clear"></div>
            <!-- <p><a href="#">忘记密码 ?</a></p> -->
            <div class="line_04"><b></b><span>其他登录方式</span><b></b></div>
        </form>
        <!-- <p class="sign">建立新账号? <a href="#"> 注册</a></p> -->
        <a href="<?php echo url('BaseWap/wechatOauth'); ?>">
                <img src="/public/wap/images/weixin.png" style="border-radius: 50%;margin: 0 auto;width: 45px;;" alt="">
        </a>
    </div>
</body>
</html>

<script>
    requirejs(['vue','store','helper','reg-verify'], function (Vue, storeApi,$h,$reg) {
        new Vue({
            el : '#login',
            data : {
                 account : '',
                 password : '',
                 login_text : '立即登录'
            },
            methods : {  
                wapLogin:function(){
                    var that =this;
                    if($reg.isEmpty(this.account))
                        return $h.returnErrorMsg('请输入账号');
                    if($reg.isEmpty(this.password))
                    return $h.returnErrorMsg('请输入密码');
                    storeApi.basePost('checkLogin',{account:this.account,pwd : this.password},function(res){
                            location.href = res.data.data;
                            that.login_text = '登录中，正在跳转。。。'
                    });
                }
            },
            mounted:function(){
              
            }
        });
    })
</script>
{/block}