<?php /*a:3:{s:74:"/www/wwwroot/sam_zhuzhouyike_com/application/admin/view/wechat/weFans.html";i:1551966726;s:74:"/www/wwwroot/sam_zhuzhouyike_com/application/admin/view/public/layout.html";i:1551805875;s:76:"/www/wwwroot/sam_zhuzhouyike_com/application/admin/view/wechat/tags_inc.html";i:1551866468;}*/ ?>
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
<link rel="stylesheet" href="/static/plugs/bootstrap/css/bootstrap.min.css">
<link rel="stylesheet" href="/public/static/css/style.css">
<script src="/public/static/js/wechat.js?v=1.2"></script>
<script src="/static/plugs/require/require.js"></script>
<script src="/static/app.js"></script>
<style>
    a {
        color:#333
    }
</style>
<div class="wrapper wrapper-content  layui-anim layui-anim-up" style="padding:0 10px">
        <div class="layui-tab layui-tab-brief" lay-filter="docDemoTabBrief">
            <ul class="layui-tab-title">
                <li class="layui-this"> <a href="<?php echo url('admin/wechat/weFans'); ?>">微信粉丝管理</a> </li>
                <div class="pull-right margin-right-15 nowrap">
                    <button data-update="" data-action="<?php echo url('backdel'); ?>" data-title="添加标签" class='layui-btn layui-btn-sm'> <i class="fa fa-plus"></i> 拉黑粉丝</button>
                    <button data-load="<?php echo url('admin/wechat/fans_sync'); ?>" class='layui-btn layui-btn-sm layui-btn-primary'>远程获取粉丝</button>            
                </div>    
            </ul>
        </div>
        <form autocomplete="off" class="layui-form layui-form-pane form-search" action="<?php echo request()->url(); ?>"  method="get">

                <div class="layui-form-item layui-inline">
                    <label class="layui-form-label">昵 称</label>
                    <div class="layui-input-inline">
                        <input name="nickname" placeholder="请输入昵称" autocomplete="off" class="layui-input">
                    </div>
                </div>
            
                <div class="layui-form-item layui-inline">
                    <label class="layui-form-label">性 别</label>
                    <div class="layui-input-inline">
                        <select name="sex" class="layui-select">
                            <option value="">- 性别 -</option>
                            <!--<?php foreach([1=>'男',2=>'女'] as $key=>$sex): ?>-->
                            <!--<?php if(app('request')->get('sex') == $key.''): ?>-->
                            <option selected value="<?php echo htmlentities($key); ?>">- <?php echo htmlentities($sex); ?> -</option>
                            <!--<?php else: ?>-->
                            <option value="<?php echo htmlentities($key); ?>">- <?php echo htmlentities($sex); ?> -</option>
                            <!--<?php endif; ?>-->
                            <!--<?php endforeach; ?>-->
                        </select>
                    </div>
                </div>
            
                <div class="layui-form-item layui-inline">
                    <label class="layui-form-label">标 签</label>
                    <div class="layui-input-inline">
                        <select name="tag" class="layui-select" lay-search="true">
                            <option value="">- 粉丝标签 -</option>
                            
                            <!--<?php foreach($tags as $key=>$tag): ?>-->
                            <!--<?php if(app('request')->get('tag') == $key): ?>-->
                            <option selected value="<?php echo htmlentities($key); ?>"><?php echo htmlentities($tag); ?></option>
                            <!--<?php else: ?>-->
                            <option value="<?php echo htmlentities($key); ?>"><?php echo htmlentities($tag); ?></option>
                            <!--<?php endif; ?>-->
                            <!--<?php endforeach; ?>-->
                          
                        </select>
                    </div>
                </div>
            
                <div class="layui-form-item layui-inline">
                    <label class="layui-form-label">国 家</label>
                    <div class="layui-input-inline">
                        <input name="country" value="<?php echo htmlentities((app('request')->get('country') ?: '')); ?>" placeholder="请输入国家" class="layui-input">
                    </div>
                </div>
            
                <div class="layui-form-item layui-inline">
                    <label class="layui-form-label">省 份</label>
                    <div class="layui-input-inline">
                        <input name="province" value="<?php echo htmlentities((app('request')->get('province') ?: '')); ?>" placeholder="请输入省份" class="layui-input">
                    </div>
                </div>
            
                <div class="layui-form-item layui-inline">
                    <label class="layui-form-label">城 市</label>
                    <div class="layui-input-inline">
                        <input name="city" value="<?php echo htmlentities((app('request')->get('city') ?: '')); ?>" placeholder="请输入城市" class="layui-input">
                    </div>
                </div>
            
                <div class="layui-form-item layui-inline">
                    <label class="layui-form-label">时 间</label>
                    <div class="layui-input-inline">
                        <input name="create_at" id='create_at' value="<?php echo htmlentities((app('request')->get('create_at') ?: '')); ?>" placeholder="关注时间" class="layui-input">
                    </div>
                </div>
            
                <div class="layui-form-item layui-inline">
                    <button class="layui-btn layui-btn-primary"><i class="layui-icon">&#xe615;</i> 搜 索</button>
                </div>
            
        </form>
        <form onsubmit="return false;" data-auto="true" method="post">
                <?php if(count($list) == 0): ?>
                    <p class="help-block text-center well">没 有 记 录 哦！</p>
                <?php else: ?>
                <input type="hidden" value="resort" name="action">
                <table class="layui-table" lay-skin="line">
                    <thead>
                    <tr>
                        <th class='list-table-check-td think-checkbox'>
                            <input data-auto-none="none" data-check-target='.list-check-box' type='checkbox'/>
                        </th>
                        <th class='text-left'>用户昵称</th>
                        <th class='text-left'>性别</th>
                        <th class='text-left'>标签</th>
                        <th class='text-left'>区域</th>
                        <th class='text-left'>关注时间</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach($list as $key=>$vo): ?>
                    <tr>
                        <td class='list-table-check-td think-checkbox'>
                            <input class="list-check-box" value='<?php echo htmlentities($vo['id']); ?>' type='checkbox'/>
                        </td>
                        <td class='text-left nowrap'>
                            <img data-tips-image class="headimg" src="<?php echo htmlentities($vo['headimgurl']); ?>"/>
                            <?php echo htmlentities((isset($vo['nickname']) && ($vo['nickname'] !== '')?$vo['nickname']:'<span class="color-desc">未设置微信昵称</span>')); ?>
                        </td>
                        <td class='text-left'>
                            <?php echo $vo['sex']==1 ? '男' : ($vo['sex']==2?'女':'未知'); ?>
                        </td>
                        <td class="nowrap nowrap">
                            <span><a data-add-tag='<?php echo htmlentities($vo['id']); ?>' data-used-id='<?php echo join(",",array_keys($vo['tags_list'])); ?>' style="border-radius:0" id="tag-fans-<?php echo htmlentities($vo['id']); ?>" class='label label-default add-tag'>+</a></span>
                            <?php if(empty($vo['tags_list'])): ?>
                                <span class="color-desc">尚未设置标签</span>
                            <?php else: foreach($vo['tags_list'] as $k=>$tag): ?><span class="layui-badge layui-bg-gray margin-right-5"><?php echo htmlentities($tag); ?></span><?php endforeach; ?>
                            <?php endif; ?>
                        </td>
                        <td class='text-left nowrap'>
                            <?php echo (isset($vo['country']) && ($vo['country'] !== '')?$vo['country']:'<span class="color-desc">未设置区域信息</span>'); ?><?php echo htmlentities($vo['province']); ?><?php echo htmlentities($vo['city']); ?>
                        </td>
                        <td class='text-left nowrap'><?php echo htmlentities($vo['subscribe_at']); ?></td>
                        <td class="text-center nowrap">
                            <a data-update="<?php echo htmlentities($vo['id']); ?>" data-action="<?php echo url('backadd'); ?>">拉黑</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>

                <?php endif; ?>
        </form>
        <style>
        .headimg {
            width: 25px;
            height: 25px;
            border-radius: 50%;
            margin-right: 10px;
        }
    
        .add-tag {
            font-size: 12px;
            font-weight: 400;
            border-radius: 50%;
            color: #333;
            background: #eee;
            border: 1px solid #ddd;
        }
    
        .add-tag:hover {
            color: #000 !important;
            border: 1px solid #ccc;
        }
    
    </style>
    
    <div id="tags-box" class="hide">
        <form>
            <div class="row margin-right-0" style='max-height:230px;overflow:auto;'>
                <?php if(is_array($tags) || $tags instanceof \think\Collection || $tags instanceof \think\Paginator): $i = 0; $__LIST__ = $tags;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$tag): $mod = ($i % 2 );++$i;?>
                <div class="col-xs-4">
                    <label class="layui-elip block think-checkbox"><input value="<?php echo htmlentities($key); ?>" type="checkbox"/> <?php echo htmlentities($tag); ?></label>
                </div>
                <?php endforeach; endif; else: echo "" ;endif; ?>
            </div>
            <div class="text-center margin-top-10">
                <div class="hr-line-dashed margin-top-0"></div>
                <button type="button" data-event="confirm" class="layui-btn layui-btn-sm">保存数据</button>
                <button type="button" data-event="cancel" class="layui-btn layui-btn-sm layui-btn-danger">取消编辑</button>
            </div>
        </form>
    </div>
    
    <script>
        // 添加标签
        require(['bootstrap'], function () {
            $('body').find('[data-add-tag]').map(function () {
                var self = this;
                var fans_id = this.getAttribute('data-add-tag');
                var used_ids = (this.getAttribute('data-used-id') || '').split(',');
                var $content = $(document.getElementById('tags-box').innerHTML);
                for (var i in used_ids) {
                    $content.find('[value="' + used_ids[i] + '"]').attr('checked', 'checked');
                }
                $content.attr('fans_id', fans_id);
                // 标签面板关闭
                $content.on('click', '[data-event="cancel"]', function () {
                    $(self).popover('hide');
                });
                // 标签面板确定
                $content.on('click', '[data-event="confirm"]', function () {
                    var tags = [];
                    $content.find('input:checked').map(function () {
                        tags.push(this.value);
                    });
                    $.form.load('<?php echo url("@admin/wechat/tagset"); ?>', {fans_id: $content.attr('fans_id'), 'tags': tags.join(',')}, 'post');
                });
                // 限制每个表单最多只能选择三个
                $content.on('click', 'input', function () {
                    ($content.find('input:checked').size() > 3) && (this.checked = false);
                });
                // 标签选择面板
                $(this).data('content', $content).on('shown.bs.popover', function () {
                    $('[data-add-tag]').not(this).popover('hide');
                }).popover({
                    html: true,
                    trigger: 'click',
                    content: $content,
                    title: '标签编辑（最多选择三个标签）',
                    template: '<div class="popover" style="max-width:500px" role="tooltip"><div class="arrow"></div><h3 class="popover-title"></h3><div class="popover-content" style="width:500px"></div></div>'
                });
            })
        });
    </script>
</div>
<script>
        window.laydate.render({range: true, elem: '#create_at'});
        window.form.render();
</script>