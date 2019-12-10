<?php /*a:8:{s:74:"D:\phpstudy\PHPTutorial\WWW\SamAdmin\application\wap\view\index\index.html";i:1575953124;s:79:"D:\phpstudy\PHPTutorial\WWW\SamAdmin\application\wap\view\public\container.html";i:1575641667;s:74:"D:\phpstudy\PHPTutorial\WWW\SamAdmin\application\wap\view\public\head.html";i:1575473732;s:75:"D:\phpstudy\PHPTutorial\WWW\SamAdmin\application\wap\view\public\style.html";i:1575641146;s:79:"D:\phpstudy\PHPTutorial\WWW\SamAdmin\application\wap\view\public\requirejs.html";i:1575644772;s:80:"D:\phpstudy\PHPTutorial\WWW\SamAdmin\application\wap\view\public\store_menu.html";i:1575954032;s:74:"D:\phpstudy\PHPTutorial\WWW\SamAdmin\application\wap\view\public\foot.html";i:1575638673;s:79:"D:\phpstudy\PHPTutorial\WWW\SamAdmin\application\wap\view\public\right_nav.html";i:1575641808;}*/ ?>
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
    <title>
首页
</title>
    <link rel="stylesheet" type="text/css" href="/public/static/css/reset.css"/>
<link rel="stylesheet" type="text/css" href="/public/wap/font/iconfont.css"/>
<link rel="stylesheet" type="text/css" href="/public/wap/css/style.css"/>
<script type="text/javascript" src="/public/static/js/media.js"></script>
<script type="text/javascript" src="/public/plugins/jquery-1.10.2.min.js"></script>

    
<link rel="stylesheet" href="/public/plugins/swiper/swiper-3.4.1.min.css">
<script type="text/javascript" src="/public/plugins/swiper/swiper-3.4.1.jquery.min.js"></script>
<script type="text/javascript" src="/public/plugins/jquery-slide-up.js"></script>
<script type="text/javascript" src="/public/wap/js/jquery.downCount.js"></script>
<script type="text/javascript" src="/public/wap/js/car-model.js"></script>
<script type="text/javascript" src="/public/wap/js/base.js"></script>
<script type="text/javascript" src="/public/wap/js/lottie.min.js"></script>

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

<div class="page-index" id="app-index">
    <section ref="bsDom">
        <!-- 轮播 -->
        <?php if(!(empty($banner) || (($banner instanceof \think\Collection || $banner instanceof \think\Paginator ) && $banner->isEmpty()))): ?>
        <div class="banner" ref="banners" style="height: 200px;">
            <ul class="swiper-wrapper">
                <?php if(is_array($banner) || $banner instanceof \think\Collection || $banner instanceof \think\Paginator): $i = 0; $__LIST__ = $banner;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
                <li class="swiper-slide"><a href="<?= empty($vo['url']) ? 'javascript:void(0);' : $vo['url']; ?>">
                        <img src="<?php echo htmlentities(unThumb($vo['pic'])); ?>"/> </a></li>
                <?php endforeach; endif; else: echo "" ;endif; ?>
            </ul>
            <div class="swiper-pagination"></div>
        </div>
        <?php endif; ?>

        <!-- 菜单 -->
        <?php if(!(empty($menus) || (($menus instanceof \think\Collection || $menus instanceof \think\Paginator ) && $menus->isEmpty()))): ?>
            <div class="nav">
                <ul class="flex"> <?php if(is_array($menus) || $menus instanceof \think\Collection || $menus instanceof \think\Paginator): $i = 0; $__LIST__ = $menus;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
                    <li><a href="<?= empty($vo['url']) ? 'javascript:void(0);' : $vo['url']; ?>"> <img src="<?php echo htmlentities(unThumb($vo['icon'])); ?>">
                            <p><?php echo htmlentities($vo['name']); ?></p></a></li>
                    <?php endforeach; endif; else: echo "" ;endif; ?>
                </ul>
            </div>
        <?php endif; ?>

        <!-- 新闻 -->
        <?php if(!(empty($roll_news) || (($roll_news instanceof \think\Collection || $roll_news instanceof \think\Paginator ) && $roll_news->isEmpty()))): ?>
        <div class="hot-txt-roll border-1px flex">
            <div class="hot-icon"><img src="/public/wap/images/hot-icon.png"></div>
            <div class="txt-list">
                <ul class="line"> <?php if(is_array($roll_news) || $roll_news instanceof \think\Collection || $roll_news instanceof \think\Paginator): $i = 0; $__LIST__ = $roll_news;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
                    <li style="overflow: hidden;white-space: nowrap;text-overflow: ellipsis;"><a
                            style="display: block;"
                            href="<?= empty($vo['url']) ? 'javascript:void(0);' : $vo['url']; ?>"><?php echo htmlentities($vo['info']); ?></a>
                    </li>
                    <?php endforeach; endif; else: echo "" ;endif; ?>
                </ul>
            </div>
        </div>
        <?php endif; ?>

         <!-- 商品分类模板 -->
         <div class="template-prolist" v-cloak="" v-for="item in cateGroupList" v-show="cateGroupList.length > 0">
                <div class="index-common-title border-1px">
                    <a :href="'/wap/store/index/cid/'+item.id">
                        {{item.cate_name}} <i class="icon"></i> </a></div>
                <div class="product-banner"><img :src="(item.pic)" v-show="item.pic != ''"></div>
                <ul class="flex">
                    <li v-for="pro in item.product"><a :href="'/wap/store/detail/id/'+pro.id">
                            <div class="picture"><img :src="pro.image"></div>
                            <div class="product-info"><p class="title" v-text="pro.store_name"></p>
                                <p class="count-wrapper flex"><span class="price"
                                                                    v-html="getPriceStr(pro.price)"></span> <span
                                        class="count">已售{{pro.sales}}{{pro.unit_name || '件'}}</span></p></div>
                        </a></li>
                </ul>
            </div>


             <!-- 商品推荐模板 -->
             <div class="template-prolist">
                    <div class="title-like flex" v-show="page.list.length > 0" v-cloak=""><span class="title-line left"></span> <span class="icon"></span>
                        <span>新品推荐</span> <span class="title-line right"></span></div>
                    <ul class="flex">
                        <li v-for="item in page.list" v-cloak=""><a :href="'/wap/store/detail/id/'+item.id">
                                <div class="picture"><img :src="item.image"></div>
                                <div class="product-info"><p class="title" v-text="item.store_name"></p>
                                    <p class="count-wrapper flex"><span class="price"
                                                                        v-html="getPriceStr(item.price)"></span> <span
                                            class="count">已售{{item.sales}}{{item.unit_name || '件'}}</span></p></div>
                            </a></li>
                    </ul>
                </div>
                <p class="loading-line" v-show="loading == true"><i></i><span>正在加载中</span><i></i></p>
                <p class="loading-line" v-show="loading == false && page.loaded == false" v-cloak="" @click="getList">
                    <i></i><span>点击加载</span><i></i></p>
                <p class="loading-line" v-show="loading == false && page.loaded == true" v-cloak="">
                    <i></i><span>没有更多了</span><i></i></p></div>
    </section>
    <div style="height:.92rem;"></div>
<?php
use think\facade\Request;
$now_c = Request::controller();$now_a = Request::action();
$menu = [
    ['c'=>'Index','a'=>'index','name'=>'首页','class'=>'home'],
    ['c'=>'Store','a'=>'index','name'=>'商城','class'=>'sort'], 
    ['c'=>'Store','a'=>'cart','name'=>'购物车','class'=>'car'],
    ['c'=>'My','a'=>'index','name'=>'我的','class'=>'user'],
];
?>
<footer class="common-footer flex border-1px" ref="storeMenu" @touchmove.prevent>
    <?php if(is_array($menu) || $menu instanceof \think\Collection || $menu instanceof \think\Paginator): $i = 0; $__LIST__ = $menu;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;
        if(strtolower($now_c) == strtolower($vo['c']) && strtolower($now_a) == strtolower($vo['a'])){
            $href = 'javascript:void(0);';
            $checked = true;
        }else{
            $href = Url($vo['c'].'/'.$vo['a']);
            $checked = false;
        }
    ?>
    <a class="<?php echo htmlentities($vo['class']); if($checked){echo' on ';} ?>" href="<?php echo htmlentities($href); ?>">
        <span class="footer-icon icon"></span>
        <p><?php echo htmlentities($vo['name']); ?></p>
    </a>
    <?php endforeach; endif; else: echo "" ;endif; ?>
</footer>
</div>

<script>
    requirejs(['vue', 'store', 'helper', 'better-scroll'], function (Vue, storeApi, $h, BScroll) {
        new Vue({
            el: '#app-index',
            data:{
                cateGroupList : [],
                page: {first: 0, limit: 10, list: [], loaded: false},
                loading: false
            },
            methods:{
                init:function(){
                    var myBanner = new Swiper('.banner', {
                                    pagination: '.swiper-pagination',
                                    paginationClickable: false,
                                    autoplay: 4500,
                                    loop: true,
                                    speed: 2500,
                                    autoplayDisableOnInteraction: false
                                });
                $(".line").slideUp({"li_h": $('.txt-list').height()});
                },
                getCateData : function(){
                    var that = this;
                    storeApi.getCategoryProductList(4, function (res) {
                        console.log(res);
                            that.cateGroupList = res.data.data;
                        })
                },
                getPriceStr: function (price) {
                        var format = this.formatPrice(price);
                        return "<i>￥</i>" + format[0] + "<i>." + format[1] + "</i>";
                    },
                formatPrice: function (price) {
                    var format = price.toString().split('.');
                    if (format[1] == undefined) format[1] = '00';
                    if (format[1].length == 1) format[1] += '0';
                    return format;
                },
                getList: function () {
                        if (this.loading) return;
                        var that = this, group = that.page;
                        if (group.loaded) return;
                        this.loading = true;
                        storeApi.getBestProductList({first: group.first, limit: group.limit}, function (res) {
                            var list = res.data.data;
                            group.loaded = list.length < group.limit;
                            group.first += list.length;
                            group.list = group.list.concat(list);
                            that.$set(that, 'page', group);
                            that.loading = false;
                        }, function () {
                            that.loading = false
                        });
                    },
            },
            mounted:function(){
                var that = this;
                this.init();
                this.getCateData();
                setTimeout(function() {
                        that.getList();
                    },0);
            }
        })
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