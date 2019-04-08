<?php /*a:2:{s:80:"/www/wwwroot/sam_zhuzhouyike_com/application/admin/view/wechat/default_menu.html";i:1551768004;s:74:"/www/wwwroot/sam_zhuzhouyike_com/application/admin/view/public/layout.html";i:1551805875;}*/ ?>
<!doctype html>
<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=UTF-8">
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
<!-- Apple devices fullscreen -->
<meta name="apple-mobile-web-app-capable" content="yes">
<!-- Apple devices fullscreen -->
<meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
<link href="/public/static/css/main.css?v=1.5" rel="stylesheet" type="text/css">
<link href="/public/static/css/page.css" rel="stylesheet" type="text/css">
<link href="/public/static/font/css/font-awesome.min.css" rel="stylesheet" />
<link rel="stylesheet" href="/public/static/js/layui/css/layui.css">
<!--[if IE 7]>
  <link rel="stylesheet" href="/public/static/font/css/font-awesome-ie7.min.css">
<![endif]-->
<link href="/public/static/js/jquery-ui/jquery-ui.min.css" rel="stylesheet" type="text/css"/>
<link href="/public/static/js/perfect-scrollbar.min.css" rel="stylesheet" type="text/css"/>
<style type="text/css">html, body { overflow: visible;}</style>
<script type="text/javascript" src="/public/static/js/jquery.js"></script>
<script type="text/javascript" src="/public/static/js/jquery-ui/jquery-ui.min.js"></script>
<script type="text/javascript" src="/public/static/js/layer/layer.js"></script><!-- 弹窗js 参考文档 http://layer.layui.com/-->
<script type="text/javascript" src="/public/static/js/admin.js"></script>
<script type="text/javascript" src="/public/static/js/jquery.validation.min.js"></script>
<script type="text/javascript" src="/public/static/js/common.js"></script>
<script type="text/javascript" src="/public/static/js/perfect-scrollbar.min.js"></script>
<script type="text/javascript" src="/public/static/js/jquery.mousewheel.js"></script>
<script src="/public/static/js/layui/layui.all.js"></script>
<script src="/public/static/js/layer/layer.js"></script>
<script src="/public/js/myFormValidate.js"></script>
<script src="/public/js/myAjax2.js?v=1.0"></script>
<script src="/public/js/global.js?v=2.3"></script>
    <script type="text/javascript">
    $(function(){
          var form = layui.form;
            form.render();
      //监听提交
      form.on('submit(ajaxSubmit)', function(data){
        var url    = data.form.action;
        var method = data.form.method;
        var field  = data.field;
        $.ajax({
            url  : url,
            type : method,
            data : field,
            success:function(res){
            if(!res.code){
                layer.msg(res.msg,{icon: 2,time: 1000})
              }else{
                layer.msg(res.msg,{icon: 1,time: 1000},function () {
                        window.location.href = res.url;
                    })
              }
            },
            error:function(){
                layer.alert('服务器繁忙，请稍候');
            }
        });
        return false;
      });
    })
  
    function delfunc(obj){
    	layer.confirm('确认删除？', {
    		  btn: ['确定','取消'] //按钮
    		}, function(){
    		    // 确定
   				$.ajax({
   					type : 'post',
   					url : $(obj).attr('data-url'),
   					data : {act:'del',del_id:$(obj).attr('data-id')},
   					dataType : 'json',
   					success : function(data){
						layer.closeAll();
   						if(data.status==1){
                            layer.msg(data.msg, {icon: 1, time: 2000},function(){
                                location.href = '';
//                                $(obj).parent().parent().parent().remove();
                            });
   						}else{
   							layer.msg(data, {icon: 2,time: 2000});
   						}
   					}
   				})
    		}, function(index){
    			layer.close(index);
    			return false;// 取消
    		}
    	);
    }

    function selectAll(name,obj){
    	$('input[name*='+name+']').prop('checked', $(obj).checked);
    }

    function get_help(obj){

		window.open("http://www.tp-shop.cn/");
		return false;

        layer.open({
            type: 2,
            title: '帮助手册',
            shadeClose: true,
            shade: 0.3,
            area: ['70%', '80%'],
            content: $(obj).attr('data-url'),
        });
    }

    function delAll(obj,name){
    	var a = [];
    	$('input[name*='+name+']').each(function(i,o){
    		if($(o).is(':checked')){
    			a.push($(o).val());
    		}
    	})
    	if(a.length == 0){
    		layer.alert('请选择删除项', {icon: 2});
    		return;
    	}
    	layer.confirm('确认删除？', {btn: ['确定','取消'] }, function(){
    			$.ajax({
    				type : 'get',
    				url : $(obj).attr('data-url'),
    				data : {act:'del',del_id:a},
    				dataType : 'json',
    				success : function(data){
						layer.closeAll();
    					if(data == 1){
    						layer.msg('操作成功', {icon: 1});
    						$('input[name*='+name+']').each(function(i,o){
    							if($(o).is(':checked')){
    								$(o).parent().parent().remove();
    							}
    						})
    					}else{
    						layer.msg(data, {icon: 2,time: 2000});
    					}
    				}
    			})
    		}, function(index){
    			layer.close(index);
    			return false;// 取消
    		}
    	);
    }

    /**
     * 全选
     * @param obj
     */
    function checkAllSign(obj){
        $(obj).toggleClass('trSelected');
        if($(obj).hasClass('trSelected')){
            $('#flexigrid > table>tbody >tr').addClass('trSelected');
        }else{
            $('#flexigrid > table>tbody >tr').removeClass('trSelected');
        }
    }
    /**
     * 批量公共操作（删，改）
     * @returns {boolean}
     */
    function publicHandleAll(type){
        var ids = '';
        $('#flexigrid .trSelected').each(function(i,o){
//            ids.push($(o).data('id'));
            ids += $(o).data('id')+',';
        });
        if(ids == ''){
            layer.msg('至少选择一项', {icon: 2, time: 2000});
            return false;
        }
        publicHandle(ids,type); //调用删除函数
    }
    /**
     * 公共操作（删，改）
     * @param type
     * @returns {boolean}
     */
    function publicHandle(ids,handle_type){
        layer.confirm('确认当前操作？', {
                    btn: ['确定', '取消'] //按钮
                }, function () {
                    // 确定
                    $.ajax({
                        url: $('#flexigrid').data('url'),
                        type:'post',
                        data:{ids:ids,type:handle_type},
                        dataType:'JSON',
                        success: function (data) {
                            layer.closeAll();
                            if (data.status == 1){
                                layer.msg(data.msg, {icon: 1, time: 2000},function(){
                                    location.href = data.url;
                                });
                            }else{
                                layer.msg(data.msg, {icon: 2, time: 2000});
                            }
                        }
                    });
                }, function (index) {
                    layer.close(index);
                }
        );
    }


</script>  

</head>
<link rel="stylesheet" href="/public/static/css/consoles.css">
<link rel="stylesheet" href="/public/static/css/bootstrap.min.css">
<link rel="stylesheet" href="/public/static/css/style.css">
<script src="/public/static/js/wechat.js?v=1.2"></script>
<style>
    .wrapper-content {
    padding: 0 10px;
}
.layui-form-label {
    font-size: 14px
}
a {
        color:#333
    }

</style>
<body>
    <div class="wrapper wrapper-content layui-anim layui-anim-up" style="padding:0 10px">
        <div class="layui-row">
                <div class="layui-tab layui-tab-brief" lay-filter="docDemoTabBrief">
                        <ul class="layui-tab-title">
                            <li> <a href="<?php echo url('admin/wechat/defaultMenu'); ?>">编辑微信菜单</a> </li>
                            <li style="float:right;padding: 0;min-width: 35px;"><div title="刷新数据" class="pReload"><i class="fa fa-refresh"></i></div></li>fa-arrow-left"></i></a></li>
                        </ul>
                </div> 
                <div class='mobile-preview pull-left notselect'>
                        <div class='mobile-header'>公众号</div>
                        <div class='mobile-body'></div>
               
                        <ul class="mobile-footer">
                            <?php if(!empty($list)): foreach($list as $key=>$menu): ?>
                            <li class="parent-menu">
                                <a>
                                    <i class="icon-sub"></i>
                                     <span data-type="<?php echo htmlentities((isset($menu['type']) && ($menu['type'] !== '')?$menu['type']:'text')); ?>" data-content="<?php echo htmlentities($menu['content']); ?>"><?php echo htmlentities($menu['name']); ?></span>
                                </a>
                                <div class="sub-menu text-center hide">
                                    <ul>
                                        <?php if(empty($menu['sub']) == false): if(is_array($menu['sub']) || $menu['sub'] instanceof \think\Collection || $menu['sub'] instanceof \think\Paginator): if( count($menu['sub'])==0 ) : echo "" ;else: foreach($menu['sub'] as $key=>$submenu): ?>
                                        <li>
                                            <a class="bottom-border">
                                                    <span data-type="<?php echo htmlentities($submenu['type']); ?>" data-content="<?php echo htmlentities($submenu['content']); ?>"><?php echo htmlentities($submenu['name']); ?></span>
                                                </a>
                                            </li>
                                        <?php endforeach; endif; else: echo "" ;endif; ?>
                                        <?php endif; ?>
                                        <li class="menu-add">
                                            <a><i class="icon-add"></i></a>
                                        </li>
                                    </ul>
                                    <i class="arrow arrow_out"></i>
                                    <i class="arrow arrow_in"></i>
                                </div>
                            </li>
                            <?php endforeach; ?>
                            <?php endif; ?>
                            
                            <li class="parent-menu menu-add" style="width: 50%;">
                                <a><i class="icon-add"></i></a>
                            </li>
                        </ul>
                    </div>
                    <div class="pull-left" style="position:absolute">
                            <div class="popover fade right up in menu-editor">
                                <div class="arrow"></div>
                                <h3 class="popover-title">
                                    微信菜单编辑

                                    <button type="button" class="pull-right menu-item-deleted layui-btn layui-btn-sm layui-btn-danger">移除菜单项</button>
  
                                </h3>
                                <div class="popover-content menu-content"></div>
                            </div>
                    </div>

                    <div class="hide menu-editor-parent-tpl">
                            <form class="form-horizontal" action="<?php echo url('admin/wechat/menu_edit'); ?>" autocomplete="off">
                                <p class="help-block text-center">已添加子菜单，仅可设置菜单名称。</p>
                                <div class="form-group margin-top-20">
                                    <label class="col-xs-3 control-label label-required">菜单名称</label>
                                    <div class="col-xs-8">
                                        <input name="menu-name" class="layui-input">
                                        <span class="help-block m-b-none">字数不超过5个汉字或16个字母</span>
                                    </div>
                                </div>
                            </form>
                    </div>
                    <div class="hide menu-editor-content-tpl">
                            <form class="form-horizontal" autocomplete="off">
                                <div class="form-group margin-top-20">
                                    <label class="col-xs-3 control-label label-required">菜单名称</label>
                                    <div class="col-xs-8">
                                        <input name="menu-name" class="layui-input">
                                        <span class="help-block m-b-none">字数不超过13个汉字或40个字母</span>
                                    </div>
                                </div>
                                
                                <div class="form-group margin-top-20">
                                    <label class="col-xs-3 control-label label-required">菜单内容</label>
                                    <div class="col-xs-8">
                                        <div class="row padding-top-5">
                                            <label class="col-xs-5 margin-bottom-10 pointer think-radio">
                                                <input type="radio" name="menu-type" value="text"> 文字消息
                                            </label>
                                            <label class="col-xs-5 margin-bottom-10 pointer think-radio">
                                                <input type="radio" name="menu-type" value="keys"> 关键字
                                            </label>
                                            <label class="col-xs-5 margin-bottom-10 pointer think-radio">
                                                <input type="radio" name="menu-type" value="view"> 跳转网页
                                            </label>
                                            <label class="col-xs-5 margin-bottom-10 pointer think-radio">
                                                <input type="radio" name="menu-type" value="event"> 事件功能
                                            </label>
                                            <label class="col-xs-5 margin-bottom-10 pointer think-radio">
                                                <input type="radio" name="menu-type" value="miniprogram"> 小程序
                                            </label>
                                            <label class="col-xs-5 margin-bottom-10 pointer think-radio">
                                                <input type="radio" name="menu-type" value="customservice"> 多客服
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group margin-top-20">
                                    <div class="col-xs-10 col-xs-offset-1 editor-content-input"></div>
                                </div>

                            </form>
                    </div>
                    <div style="clear:both"></div>
                     <div class="text-center menu-submit-container">
            
                        <button class="layui-btn menu-submit" lay-submit lay-filter="ajaxSubmit">保存发布</button>
            
                   
                        <button data-load='<?php echo url("admin/wechat/menuCancel"); ?>' class="layui-btn layui-btn-danger">取消发布</button>
                     
                    </div>

        </div>
    </div>
<script>
        $(function () {
        new function () {
            var self = this;
            this.listen = function () {
                $('.mobile-footer').on('click', 'li a', function () {
                    self.$btn = $(this);
                    self.$btn.parent('li').hasClass('menu-add') ? self.add() : self.checkShow();
                }).find('li:first a:first').trigger('click');
                $('.menu-item-deleted').on('click', function () {
                    var dialogIndex = $.msg.confirm('删除后菜单下设置的内容将被删除！', function () {
                        self.del(), $.msg.close(dialogIndex);
                    });
                });
                $('.menu-submit').on('click', function () {
                    self.submit();
                });
            };
            this.add = function () { 
                var $add = this.$btn.parent('li'), $ul = $add.parent('ul');
                if ($ul.hasClass('mobile-footer')) { /* 添加一级菜单 */
                    var $li = $('<li class="parent-menu"><a class="active"><i class="icon-sub hide"></i> <span>一级菜单</span></a></li>').insertBefore($add);
                    this.$btn = $li.find('a');
                    $('<div class="sub-menu text-center hide"><ul><li class="menu-add"><a><i class="icon-add"></i></a></li></ul><i class="arrow arrow_out"></i><i class="arrow arrow_in"></i></div>').appendTo($li);
                } else { /* 添加二级菜单 */
                    this.$btn = $('<li><a class="bottom-border"><span>二级菜单</span></a></li>').prependTo($ul).find('a');
                }
                this.checkShow();
            };
            this.checkShow = function () {
                var $li = this.$btn.parent('li'), $ul = $li.parent('ul');
                /* 选中一级菜单时显示二级菜单 */
                if ($li.hasClass('parent-menu')) {
                    $('.parent-menu .sub-menu').not(this.$btn.parent('li').find('.sub-menu').removeClass('hide')).addClass('hide');
                }
                /* 一级菜单添加按钮 */
                var $add = $('li.parent-menu:last');
                $add.siblings('li').size() >= 3 ? $add.addClass('hide') : $add.removeClass('hide');
                /* 二级菜单添加按钮 */
                $add.siblings('li').map(function () {
                    var $add = $(this).find('ul li:last');
                    $add.siblings('li').size() >= 5 ? $add.addClass('hide') : $add.removeClass('hide');
                });
                /* 处理一级菜单 */
                var parentWidth = 100 / $('li.parent-menu:visible').size() + '%';
                $('li.parent-menu').map(function () {
                    var $icon = $(this).find('.icon-sub');
                    $(this).width(parentWidth).find('ul li').size() > 1 ? $icon.removeClass('hide') : $icon.addClass('hide');
                });
                /* 更新选择中状态 */
                $('.mobile-footer a.active').not(this.$btn.addClass('active')).removeClass('active');
                return this.renderEdit(), $ul;
            };
            this.del = function () {
                var $li = this.$btn.parent('li'), $ul = $li.parent('ul');
                var $default = function () {
                    if ($li.prev('li').size() > 0) {
                        return $li.prev('li');
                    }
                    if ($li.next('li').size() > 0 && !$li.next('li').hasClass('menu-add')) {
                        return $li.next('li');
                    }
                    if ($ul.parents('li.parent-menu').size() > 0) {
                        return $ul.parents('li.parent-menu');
                    }
                    return $('null');
                }.call(this);
                $li.remove();
                this.$btn = $default.find('a:first');
                this.checkShow();
            };
            this.renderEdit = function () {
                var $span = this.$btn.find('span'), $li = this.$btn.parent('li'), $html = '';
                if ($li.find('ul li').size() > 1) { /* 父菜单 */
                    $html = $($('.menu-editor-parent-tpl').html());
                    $html.find('input[name="menu-name"]').val($span.text()).on('change keyup', function () {
                        $span.text(this.value || ' ');
                    });
                    return $('.menu-editor .menu-content').html($html);
                }
                $html = $($('.menu-editor-content-tpl').html());
                $html.find('input[name="menu-name"]').val($span.text()).on('change keyup', function () {
                    $span.text(this.value || ' ');
                });
                $('.menu-editor .menu-content').html($html);
                var type = $span.attr('data-type') || 'text';
                $html.find('input[name="menu-type"]').on('click', function () {
                    var type = this.value, content = $span.data('content') || '请输入内容';
                    $span.attr('data-type', this.value || 'text').data('content', content);
                    var $edit = $((function () {
                        switch (type) {
                            case 'miniprogram':
                                var tpl = '<div><div>小程序的appid<input class="layui-input block margin-bottom-10" value="{appid}" name="appid"></div><div>小程序网页链接<input class="layui-input block margin-bottom-10" value="<?php echo url("","",true,false);?>" name="url"></div><div>小程序的页面路径<input name="pagepath" class="layui-input block" value={pagepath}></div></div>';
                                var _appid = '', _pagepath = '', _url = '';
                                if (content.indexOf(',') > 0) {
                                    _appid = content.split(',')[0] || '';
                                    _url = content.split(',')[1] || '';
                                    _pagepath = content.split(',')[2] || '';
                                }
                                $span.data('appid', _appid), $span.data('url', _url), $span.data('pagepath', _pagepath);
                                return tpl.replace('{appid}', _appid).replace('<?php echo url("","",true,false);?>', _url).replace('{pagepath}', _pagepath);
                            case 'customservice':
                            case 'text':
                                return '<div>回复内容<textarea style="resize:none;height:150px" name="content" class="form-control input-sm">{content}</textarea></div>'.replace('{content}', content);
                            case 'view':
                                var wxMenu = eval('<?php echo htmlentities(json_encode((isset($GLOBALS['WechatMenuLink']) && ($GLOBALS['WechatMenuLink'] !== '')?$GLOBALS['WechatMenuLink']:[]))); ?>');
                                var wxMenuHtml = '<div>常用链接<select id="wxMenuLinkSelecter" class="layui-select full-width"><option value="">自定义地址</option>';
                                for (var i in wxMenu) {
                                    wxMenuHtml += '<option value="' + wxMenu[i].link + '">' + wxMenu[i].title + '</option>';
                                }
                                return wxMenuHtml + '</select>跳转链接<textarea id="wxMenuLinkContent" style="resize:none;height:120px" name="content" class="form-control input-sm">{content}</textarea></div>'.replace('{content}', content);
                            case 'keys':
                                return '<div>匹配内容<textarea style="resize:none;height:150px" name="content" class="form-control input-sm">{content}</textarea></div>'.replace('{content}', content);
                            case 'event':
                                var options = {
                                    'scancode_push': '扫码推事件',
                                    'scancode_waitmsg': '扫码推事件且弹出“消息接收中”提示框',
                                    'pic_sysphoto': '弹出系统拍照发图',
                                    'pic_photo_or_album': '弹出拍照或者相册发图',
                                    'pic_weixin': '弹出微信相册发图器',
                                    'location_select': '弹出地理位置选择器'
                                };
                                var select = [];
                                var tpl = '<p class="margin-bottom-5"><label class="font-noraml pointer think-radio"><input name="content" type="radio" {checked} value="{value}"> {title}</label></p>';
                                if (!(options[content] || false)) {
                                    (content = 'scancode_push'), $span.data('content', content);
                                }
                                for (var i in options) {
                                    select.push(tpl.replace('{value}', i).replace('{title}', options[i]).replace('{checked}', (i === content) ? 'checked' : ''));
                                }
                                return select.join('');
                        }
                    })());
                    // 参数编辑器数据输入绑定
                    $edit.find('input,textarea').on('keyup', function () {
                        $span.data(this.name, $(this).val() || $(this).html());
                        if (type === 'miniprogram') {
                            // 打开小程序，拼接参数并绑定
                            $span.data('content', [$span.data('appid'), $span.data('url'), $span.data('pagepath')].join(','));
                        } else if (type === 'view') {
                            // 跳转网页，自定义链接自动切换选择
                            $('#wxMenuLinkSelecter option').map(function () {
                                this.selected = this.value === $span.data('content');
                            });
                        }
                    });
                    // 显示参数编辑器
                    $('.editor-content-input').html($edit);
                    // 跳转网页处理选择器切换，事件监听
                    if (type === 'view') {
                        $('#wxMenuLinkSelecter option').map(function () {
                            this.selected = this.value === content;
                        });
                        $('body').off('change', '#wxMenuLinkSelecter').on('change', '#wxMenuLinkSelecter', function () {
                            $('#wxMenuLinkContent').val(this.options[this.selectedIndex].value || '#').trigger('keyup');
                        });
                    }
                }).filter('input[value="' + type + '"]').trigger('click');
            };
            // 提交微信菜单数据
            this.submit = function () {
                var data = [];
                $('li.parent-menu').map(function (index, item) {
                    if (!$(item).hasClass('menu-add')) {
                        var menudata = getdata($(item).find('a:first span'));
                        menudata.index = index + 1;
                        menudata.pindex = 0;
                        menudata.sub = [];
                        menudata.sort = index;
                        data.push(menudata);
                        $(item).find('.sub-menu ul li:not(.menu-add) span').map(function (ii, span) {
                            var submenudata = getdata($(span));
                            submenudata.index = (index + 1) + '' + (ii + 1);
                            submenudata.pindex = menudata.index;
                            submenudata.sort = ii;
                            data.push(submenudata);
                        });
                    }
                });
                $.form.load('<?php echo url("admin/wechat/menu_edit"); ?>', {data: data}, 'post');

                function getdata($span) {
                    var menudata = {};
                    menudata.name = $span.text();
                    menudata.type = $span.attr('data-type');
                    menudata.content = $span.data('content') || '';
                    menudata.tag  = $('.tags').val();
                    return menudata;
                }
            };
            this.listen();
        };
    });
</script>
<style>
        .menu-editor {
            left: 317px;
            width: 500px;
            height: 580px;
            display: block;
            max-width: 500px;
            border-radius: 0;
            box-shadow: none;
            border-color: #e7e7eb;
        }
    
        .menu-editor textarea:active, .menu-editor textarea:focus {
            box-shadow: none
        }
    
        .menu-editor .arrow {
            top: auto !important;
            bottom: 15px
        }
    
        .menu-editor .popover-title {
            height: 58px;
            padding: 12px;
            margin-top: 0;
            font-size: 14px;
            line-height: 40px;
        }
    
        .menu-editor textarea, .menu-editor input[type=text] {
            border-radius: 0
        }
    
        .menu-editor .menu-item-deleted {
            font-weight: 400;
            font-size: 12px
        }
    
        .menu-submit-container {
            width: 780px;
            padding-top: 40px
        }
    </style>
</body>