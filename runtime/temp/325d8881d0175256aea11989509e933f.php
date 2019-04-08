<?php /*a:2:{s:72:"/www/wwwroot/sam_zhuzhouyike_com/application/admin/view/index/index.html";i:1553155364;s:72:"/www/wwwroot/sam_zhuzhouyike_com/application/admin/view/public/left.html";i:1549046060;}*/ ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <link rel="shortcut icon" type="image/x-icon" href="<?php echo htmlentities((isset($site_config['shop_info_store_ico']) && ($site_config['shop_info_store_ico'] !== '')?$site_config['shop_info_store_ico']:'/public/static/images/logo/storeico_default.png')); ?>" media="screen"/>
    <title><?php echo htmlentities((isset($site_config['shop_info_store_name']) && ($site_config['shop_info_store_name'] !== '')?$site_config['shop_info_store_name']:'SamAdmin')); ?></title>
    <script type="text/javascript">
        var SITEURL = window.location.host +'/index.php/admin';
      </script>
    <link href="/public/static/css/main.css" rel="stylesheet" type="text/css">
    <link href="/public/static/js/jquery-ui/jquery-ui.min.css" rel="stylesheet" type="text/css">
    <link href="/public/static/font/css/font-awesome.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="/public/static/js/layui/css/layui.css">
    <script type="text/javascript" src="/public/static/js/jquery.js"></script>
    <script type="text/javascript" src="/public/static/js/jquery-ui/jquery-ui.min.js"></script>
    <script type="text/javascript" src="/public/static/js/dialog/dialog.js" id="dialog_js"></script>
    <script type="text/javascript" src="/public/static/js/jquery.cookie.js"></script>
    <script type="text/javascript" src="/public/static/js/admincp.js"></script>
    <script type="text/javascript" src="/public/static/js/jquery.validation.min.js"></script>
    <script src="/public/static/js/layui/layui.all.js"></script>
    <script src="/public/static/js/layer/layer.js"></script>
</head>

<body>
    <div class="admincp-header">
        <div class="bgSelector"></div>
        <div id="foldSidebar"><i class="fa fa-outdent " title="展开/收起侧边导航"></i></div>
        <div class="admincp-name" onClick="javascript:openItem('welcome|Index');">
            <!-- <h2 style="cursor:pointer;">TPshop3.0<br>平台系统管理中心</h2> -->
            <img  style="width: 148px;height: 28px" src="<?php echo htmlentities((isset($site_config['shop_info_admin_home_logo']) && ($site_config['shop_info_admin_home_logo'] !== '')?$site_config['shop_info_admin_home_logo']:'/public/static/images/logo/admin_home_logo_default.png')); ?>" alt="">
        </div>
        <div class="nc-module-menu">
            <ul class="nc-row">
                <?php if(is_array($head_menu) || $head_menu instanceof \think\Collection || $head_menu instanceof \think\Paginator): $i = 0; $__LIST__ = $head_menu;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vv): $mod = ($i % 2 );++$i;?>
                    <li data-param="<?php echo htmlentities($vv['op']); ?>"><a href="javascript:void(0);"><?php echo htmlentities($vv['name']); ?></a></li>
                <?php endforeach; endif; else: echo "" ;endif; ?>
                <!-- <li data-param="index"><a href="javascript:void(0);">首页</a></li>
                <li data-param="system"><a href="javascript:void(0);">设置</a></li>
                <li data-param="center"><a href="javascript:void(0);">内容管理</a></li>
                <li data-param="member"><a href="javascript:void(0);">会员</a></li>      -->
            </ul>
        </div>
        <div class="admincp-header-r">
            <!-- <div class="manager">    
                <dl>
                  <dt class="name"><?php echo htmlentities($admin_info['user_name']); ?></dt>
                  <dd class="group">管理员</dd>
                </dl>
                <span class="avatar">
                <img alt="" tptype="admin_avatar" src="/public/static/images/admint.png"> </span><i class="arrow" id="admin-manager-btn" title="显示快捷管理菜单"></i>
                <div class="manager-menu">
                    <div class="title">
                        <h4>最后登录</h4>
                        <a href="javascript:void(0);" onClick="CUR_DIALOG = ajax_form('modifypw', '修改密码', '<?php echo url('Admin/modify_pwd',array('admin_id'=>$admin_info['admin_id'])); ?>');" class="edit-password">修改密码</a> 
                    </div> 
                    <div class="login-date"> <?php echo date('Y-m-d H:i:s',session('last_login_time'));?> <span>(IP: <?php echo session('last_login_ip');?> )</span> </div> 
                     <div class="title">
                        <h4>常用操作</h4>
                        <a href="javascript:void(0)" class="add-menu">添加菜单</a> 
                    </div>
                    <ul class="nc-row" tptype="quick_link">
                        <li><a href="javascript:void(0);" onClick="openItem('index|System')">站点设置</a></li>
                    </ul> 
                </div>
            </div> -->
            <div class="operate bgd-opa">
                <span class="bgdopa-t"><?php echo htmlentities($admin_info['user_name']); ?><i class="opa-arow"></i></span>
                <ul class="bgdopa-list">
                    <li><a class="login-out show-option" href="<?php echo url('Admin/logout'); ?>" style="text-align: center">退出系统</a></li>
                    <li><a class="sitemap show-option" style="text-align: center" href="<?php echo url('System/cleanCache',array('quick'=>1)); ?>" target="workspace">更新缓存</a></li>
                    <li><a class="sitemap show-option" style="text-align: center" target="workspace" href="<?php echo url('admin/modify_pwd',array('admin_id'=>$admin_info['admin_id'])); ?>">修改密码</a></li>
                  
            </div>
          </div>
          <div class="clear"></div>
    </div>
    <div class="admincp-container unfold">
        <div class="admincp-container-left">
    <!--<div class="top-border"><span class="nav-side"></span><span class="sub-side"></span></div>-->
    <!-- <div id="admincpNavTabs_index" class="nav-tabs">
    	<dl>
		    <dt><a href="javascript:void(0);"><span class="fa fa-home fa-lg"></span><h3>首页</h3></a></dt>
		    <dd class="sub-menu">
			    <ul>
				    <li><a href="javascript:void(0);" data-param="welcome|Index">系统后台</a></li>
			    </ul>
		    </dd>
	    </dl>
    </div> -->
    <?php if(is_array($menu) || $menu instanceof \think\Collection || $menu instanceof \think\Paginator): if( count($menu)==0 ) : echo "" ;else: foreach($menu as $mk=>$vo): ?>
    <div id="admincpNavTabs_<?php echo htmlentities($vo['op']); ?>" class="nav-tabs">
		<?php if(isset($vo['children']) && count($vo['children']) > 0): if(is_array($vo['children']) || $vo['children'] instanceof \think\Collection || $vo['children'] instanceof \think\Paginator): if( count($vo['children'])==0 ) : echo "" ;else: foreach($vo['children'] as $key=>$v2): ?>
	    <dl>
		    <dt><a href="javascript:void(0);"><span class="<?php echo htmlentities($v2['icon']); ?> fa-lg"></span><h3><?php echo htmlentities($v2['name']); ?></h3></a></dt>
		    <dd class="sub-menu">
			    <ul>
					<?php if(isset($v2['children']) && count($v2['children']) > 0): if(is_array($v2['children']) || $v2['children'] instanceof \think\Collection || $v2['children'] instanceof \think\Paginator): if( count($v2['children'])==0 ) : echo "" ;else: foreach($v2['children'] as $key=>$v3): ?>
							<li><a href="javascript:void(0);" data-param="<?php echo htmlentities($v3['act']); ?>|<?php echo htmlentities($v3['op']); ?>"><?php echo htmlentities($v3['name']); ?></a></li>
						<?php endforeach; endif; else: echo "" ;endif; ?>
					<?php endif; ?>
			    </ul>
		    </dd>
	    </dl>
        <?php endforeach; endif; else: echo "" ;endif; ?>
		<?php endif; ?>
    	
    </div>
    <?php endforeach; endif; else: echo "" ;endif; ?>
    <div class="about" title="关于系统" onclick="javascript:layer.open({type: 2,title: '关于我们',shadeClose: true,shade: 0.3,area: ['50%', '60%'],content:'<?php echo url("Index/about"); ?>', });"><i class="fa fa-copyright"></i><span>SamAdmin</span></div>
</div>
          <div class="admincp-container-right">
            <!--<div class="top-border"></div>-->
            <iframe src="" id="workspace" name="workspace" style="overflow: visible;" frameborder="0" width="100%" height="94%" scrolling="yes" onload="window.parent"></iframe>
          </div>
    </div>

    <script type="text/javascript">

        function FullScreen(el){
            var isFullscreen=document.fullScreen||document.mozFullScreen||document.webkitIsFullScreen;
            if(!isFullscreen){
                (el.requestFullscreen&&el.requestFullscreen())||(el.mozRequestFullScreen&&el.mozRequestFullScreen())||
                 (el.webkitRequestFullscreen&&el.webkitRequestFullscreen())||(el.msRequestFullscreen&&el.msRequestFullscreen());
            }else{
                document.exitFullscreen?document.exitFullscreen():
                document.mozCancelFullScreen?document.mozCancelFullScreen():
                document.webkitExitFullscreen?document.webkitExitFullscreen():'';
            }
            }
        function toggleFullScreen(e){
            var el=e.srcElement||e.target;//target兼容Firefox
            el.innerHTML=='点我全屏'?el.innerHTML='退出全屏':el.innerHTML='点我全屏';
            FullScreen(el);
            }
            </script>
</body>
</html>