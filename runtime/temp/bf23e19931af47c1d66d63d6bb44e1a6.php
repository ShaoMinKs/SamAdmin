<?php /*a:7:{s:75:"D:\phpstudy\PHPTutorial\WWW\SamAdmin\application\wap\view\store\detail.html";i:1576416657;s:79:"D:\phpstudy\PHPTutorial\WWW\SamAdmin\application\wap\view\public\container.html";i:1575641667;s:74:"D:\phpstudy\PHPTutorial\WWW\SamAdmin\application\wap\view\public\head.html";i:1575473732;s:75:"D:\phpstudy\PHPTutorial\WWW\SamAdmin\application\wap\view\public\style.html";i:1576851498;s:79:"D:\phpstudy\PHPTutorial\WWW\SamAdmin\application\wap\view\public\requirejs.html";i:1575644772;s:74:"D:\phpstudy\PHPTutorial\WWW\SamAdmin\application\wap\view\public\foot.html";i:1575638673;s:79:"D:\phpstudy\PHPTutorial\WWW\SamAdmin\application\wap\view\public\right_nav.html";i:1575641808;}*/ ?>
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
    <title><?php echo htmlentities($storeInfo['store_name']); ?></title>
    <link rel="stylesheet" type="text/css" href="/static/css/reset.css"/>
<link rel="stylesheet" type="text/css" href="/wap/font/iconfont.css"/>
<link rel="stylesheet" type="text/css" href="/wap/css/style.css"/>
<script type="text/javascript" src="/static/js/media.js"></script>
<script type="text/javascript" src="/plugins/jquery-1.10.2.min.js"></script>

    
<link rel="stylesheet" href="/plugins/swiper/swiper-3.4.1.min.css">
<script type="text/javascript" src="/plugins/swiper/swiper-3.4.1.jquery.min.js"></script>
<script type="text/javascript"  src="/wap/js/car-model.js"></script>

    <script type="text/javascript" src="/plugins/requirejs/require.js"></script>
<script>
        requirejs.config({
            urlArgs: "v=15615616515616556",
            map: {
                '*': {
                    'css': '/plugins/requirejs/require-css.js'
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
            baseUrl: '//' + location.hostname + '',
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
    
    <script type="text/javascript" src="/wap/js/common.js"></script>
</head>
<body>

<div id="store_detail" class="wrapper product-con">
    <section>
        <div class="banner" ref="banners" style="height: 200px;">
                <ul class="swiper-wrapper">
                    <?php if(is_array($storeInfo['slider_image']) || $storeInfo['slider_image'] instanceof \think\Collection || $storeInfo['slider_image'] instanceof \think\Paginator): $i = 0; $__LIST__ = $storeInfo['slider_image'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
                    <li class="swiper-slide">
                        <img src="<?php echo htmlentities($vo); ?>"/> 
                    </li>
                    <?php endforeach; endif; else: echo "" ;endif; ?>
                </ul>
                <div class="swiper-pagination"></div>
        </div>
        <div class="product-info">
                <div class="title"><?php echo htmlentities($storeInfo['store_name']); ?></div>
                <div class="price">￥<?php echo htmlentities(floatval($storeInfo['price'])); ?></div>
                <div class="oldprice">原价:￥<?php echo htmlentities(floatval($storeInfo['ot_price'])); ?></div>
                <div class="info-amount flex"><span class="current">商品编号：<?php echo htmlentities($storeInfo['id']); ?></span> <span class="">库存:<?php echo htmlentities($storeInfo['stock']); ?><?php echo htmlentities($storeInfo['unit_name']); ?></span>
                    <span class="fr">销量:<?php echo htmlentities($storeInfo['ficti']+$storeInfo['sales']); ?><?php echo htmlentities($storeInfo['unit_name']); ?></span></div>
                <?php if($storeInfo['give_integral'] > '0'): ?>
                <div class="integral">积分:<?php echo htmlentities(floatval($storeInfo['give_integral'])); ?> <span>赠送</span></div>
                <?php endif; ?>
        </div>
        <div class="like-it" v-cloak=""><i class="zan-btn iconfont icon-thumbsup" :class="{'icon-thumbsup110':product.userLike == true}" @click="like"></i> 点赞
            <span v-text="product.like_num"></span>次
        </div>
        <?php if(!(empty($reply) || (($reply instanceof \think\Collection || $reply instanceof \think\Paginator ) && $reply->isEmpty()))): ?>
        <div class="item-box">
            <div class="item-tit"><i class="line"></i><i class="iconfont icon-pinglun1"></i><span>评价</span><i
                    class="line"></i></div>
            <div class="assess-hot"><p class="title">宝贝评价(<?php echo htmlentities($replyCount); ?>)</p>
                <div class="assess-hot-con">
                    <div class="user-info flex">
                        <div class="avatar"><img src="<?php echo htmlentities($reply['avatar']); ?>"/></div>
                        <div class="name"><?php echo htmlentities($reply['nickname']); ?></div>
                        <div class="star<?php echo htmlentities($reply['star']); ?> all"><span class="num"></span></div>
                    </div>
                    <div class="txt-info"><?php echo htmlentities($reply['comment']); ?></div>
                    <div class="pro-parameter"><span><?php echo htmlentities($reply['add_time']); ?></span> <span><?php echo htmlentities($reply['suk']); ?></span></div>
                    <?php if($replyCount > '1'): ?>
                    <a class="more"  href="<?php echo url('reply_list',['productId'=>$storeInfo['id']]); ?>">查看全部评价</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <?php endif; ?>
        <div class="item-box">
                <div class="item-tit"><i class="line"></i><i class="iconfont icon-icon-tupian"></i><span>详情</span><i class="line"></i></div>
                <div class="con-box" ref="store_desc"></div>
        </div>
        <div class="footer-bar flex">
                <a href="<?php echo Url('Service/service_list',array('mer_id'=>0)); ?>"> <span class="iconfont icon-kefu"></span><p>客服</p></a>
                <a class="collect-btn iconfont icon-xing1" :class="{'icon-xing2':product.userCollect == true}" @click="collect" href="javascript:void(0)"><p>收藏</p></a>
                <a href="<?php echo Url('store/cart'); ?>"> <span class="iconfont icon-icon-shoppingcart-02"></span> <p>购物车</p> <span class="cart_num" v-show="cartNum > 0" v-cloak="" v-text="cartNum"></span> </a>
                <div class="big-btn buy-car" @click="cardUp">加入购物车</div>
                <div class="big-btn confirm" @click="cardUp">立即购买</div>
        </div>
    </section>
    <shop-card ref="shopCard" :show="cardShow" :product="productCardInfo" 
              :on-close="cardClose" :on-cart="goCart" :on-buy="goBuy"></shop-card>
    <script ref="store_desc_temp" type="text/template"><?php echo $storeInfo['description']; ?></script>
    <div style="height:1rem;"></div>
</div>
<script>
     window.$product = <?php unset($storeInfo['description']); echo json_encode($storeInfo);?>;
    requirejs(['vue', 'axios', 'helper', 'store', '/wap/module/store/shop-card.js'],function(Vue, axios, $h, storeApi, shopCard){
        new Vue({
            el: "#store_detail",
            components: {'shop-card': shopCard},
            data: {
                cardShow: false,
                product: $product,
                productCardInfo: {},
                status: {like: false, collect: false},
                cartNum: 0
            },
            methods : {
                init : function(){
                    var myBanner =  new Swiper('.banner', {
                                    pagination: '.swiper-pagination',
                                    paginationClickable: false,
                                    autoplay: 4500,
                                    loop: true,
                                    speed: 2500,
                                    autoplayDisableOnInteraction: false
                                });
                },
                cardClose: function () {
                    this.cardShow = false;
                }, cardUp: function () {
                    this.cardShow = true;
                }, goCart: function (values, cartNum) {
                    // var checkedAttr = this.productValue[values.sort().join(',')], that = this;
//                    console.log(values);
//                    console.log(checkedAttr);
                    var that = this;
                    storeApi.setCart({
                        cartNum: cartNum,
                        uniqueId: 0,
                        productId: this.product.id
                    }, function () {
                        that.getCartNum();
                        $h.pushMsg('加入购物车成功!');
                    });
                    that.cardClose();
                },
                goBuy: function (values, cartNum) {
                    // var checkedAttr = this.productValue[values.sort().join(',')];
                    storeApi.goBuy({
                        cartNum: cartNum,
                        // uniqueId: checkedAttr === undefined ? 0 : checkedAttr.unique,
                        uniqueId: 0,
                        productId: this.product.id
                    }, function (cartId) {
                        location.href = $h.U({c: 'store', a: 'confirm_order', p: {cartId: cartId}});
                    });
                    this.cardClose();
                },
                collect: function () {
                    var that = this;
                    if (that.status.collect) return false;
                    that.status.collect = true;
                    if (this.product.userCollect) {
                        storeApi.unCollectProduct(this.product.id, 'product', function () {
                            setTimeout(function () {
                                that.product.userCollect = false;
                                that.status.collect = false;
                            }, 300);
                        }, function (err) {
                            that.status.collect = false;
                        });
                    } else {
                        storeApi.collectProduct(this.product.id, 'product', function () {
                            setTimeout(function () {
                                that.product.userCollect = true;
                                that.status.collect = false;
                            }, 300);
                        }, function (err) {
                            that.status.collect = false;
                        });
                    }
                },
                like: function () {
                    var that = this;
                    if (that.status.like) return false;
                    that.status.like = true;
                    if (this.product.userLike) {
                        storeApi.unlikeProduct(this.product.id, 'product', function () {
                            setTimeout(function () {
                                that.product.like_num -= 1;
                                that.product.userLike = false;
                                that.status.like = false;
                            }, 300);
                        }, function (err) {
                            that.status.like = false;
                        });
                    } else {
                        storeApi.likeProduct(this.product.id, 'product', function () {
                            setTimeout(function () {
                                that.product.like_num += 1;
                                that.product.userLike = true;
                                that.status.like = false;
                            }, 300);
                        }, function (err) {
                            that.status.like = false;
                        });
                    }
                },
                getCartNum: function () {
                    var that = this;
                    storeApi.getCartNum(function (cartNum) {
                        that.cartNum = cartNum;
                    });
                },
                setProductCardInfo: function (info) {
                    info || (info = {});
                    this.$set(this, 'productCardInfo', {
                        image: info.image !== undefined ? info.image : this.product.image,
                        price: info.price !== undefined ? info.price : this.product.price,
                        stock: info.stock !== undefined ? info.stock : this.product.stock
                    });
                }
            },
            mounted:function(){
                this.$nextTick(function () {
                    this.$refs.store_desc.innerHTML = this.$refs.store_desc_temp.innerHTML;
                });
                this.init();
                this.getCartNum();
                this.setProductCardInfo();
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