<?php /*a:7:{s:74:"D:\phpstudy\PHPTutorial\WWW\SamAdmin\application\wap\view\login\index.html";i:1575644827;s:79:"D:\phpstudy\PHPTutorial\WWW\SamAdmin\application\wap\view\public\container.html";i:1575641667;s:74:"D:\phpstudy\PHPTutorial\WWW\SamAdmin\application\wap\view\public\head.html";i:1575473732;s:75:"D:\phpstudy\PHPTutorial\WWW\SamAdmin\application\wap\view\public\style.html";i:1575641146;s:79:"D:\phpstudy\PHPTutorial\WWW\SamAdmin\application\wap\view\public\requirejs.html";i:1575644772;s:74:"D:\phpstudy\PHPTutorial\WWW\SamAdmin\application\wap\view\public\foot.html";i:1575638673;s:79:"D:\phpstudy\PHPTutorial\WWW\SamAdmin\application\wap\view\public\right_nav.html";i:1575641808;}*/ ?>
<!DOCTYPE html>
<html lang="en">
<head>
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
    <title>立即登陆</title>
    <link rel="stylesheet" type="text/css" href="/public/static/css/reset.css"/>
<link rel="stylesheet" type="text/css" href="/public/wap/font/iconfont.css"/>
<link rel="stylesheet" type="text/css" href="/public/wap/css/style.css"/>
<script type="text/javascript" src="/public/static/js/media.js"></script>
<script type="text/javascript" src="/public/plugins/jquery-1.10.2.min.js"></script>

    
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
    
    <script type="text/javascript" src="/public/wap/js/common.js"></script>
</head>
<body>

<div class="authenticate">
    <section>
        <form action="<?php echo Url('check'); ?>" method="post">
            <div class="user-infos"><input type="text" required name="account" placeholder="请输入登录账号"/></div>
            <div class="user-infos"><input type="password" required name="pwd" placeholder="请输入登录密码"/></div>
            <div class="publish-btn"><button type="submit">立即登陆</button></div>
        </form>
    </section>
</div>
<script>
    requirejs(['vue','store'], function (Vue, storeApi) {
        console.log(Vue);
    })
</script>



<?php /*  <section id="right-nav" class="right-barnav" >
        <a class="rb-home" href="<?php echo Url('Index/index'); ?>"></a>
        <a class="rb-car" href="<?php echo Url('Store/cart'); ?>"></a>
        <a class="rb-server" href="<?php echo Url('Service/service_list'); ?>"></a>
    </section>  */ ?>
  <section id="right-nav" class="right-menu-wrapper">
      <a class="home" href="<?php echo Url('Index/index'); ?>"></a>
      <a class="buy-car" href="<?php echo Url('Store/cart'); ?>"></a>
  </section>
  
  
</body>
</html>