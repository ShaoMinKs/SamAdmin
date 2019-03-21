<?php /*a:2:{s:75:"/www/wwwroot/sam_zhuzhouyike_com/application/admin/view/system/addMenu.html";i:1549878814;s:74:"/www/wwwroot/sam_zhuzhouyike_com/application/admin/view/public/layout.html";i:1551805875;}*/ ?>
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
<style>
    .system_img_location{text-align: center; width: 120px;position:absolute;top:15px; margin-left:265px;}
    .layui-input-inline {
        width: 280px!important;
    }
    .select {
        width: 150px!important;
    }
    .err {
        margin-top: 5px!important;
        display: inline-block!important;
    }
</style>
<body style="background-color: #FFF; overflow: auto;">
    <div class="layui-tab layui-tab-brief" lay-filter="docDemoTabBrief">
            <ul class="layui-tab-title">
                <li> <a href="<?php echo url('admin/system/admin_menu'); ?>">菜单列表</a> </li>
                <li class="layui-this"><a  href="<?php echo url('admin/system/addMenu'); ?>">新增菜单</a></li>
                <li style="float:right;padding: 0;min-width: 35px;"><div title="刷新数据" class="pReload"><i class="fa fa-refresh"></i></div></li>
                <li style="float:right;padding: 0;min-width: 35px;"><a  href="javascript:history.back();" title="返回列表"> <i class="fa  fa-arrow-left"></i></a></li>
            </ul>
        </div>
        <div class="layui-tab-content">
            <div class="layui-row">
                    <form class="form-horizontal layui-form" id="adminHandle" method="post" action="<?php echo url('admin/system/menuHandle'); ?>">
                        <input type="hidden" name="act" id="act" value="add">
                        <input type="hidden" name="auth_code" value="<?php echo htmlentities(app('config')->get('AUTH_CODE')); ?>"/>
                        <div class="layui-form-item">
                            <label class="layui-form-label" for="name">菜单名称</label>
                            <div class="layui-input-inline">
                                <input type="text" name="name" maxlength="20"  id="name" value="<?php echo htmlentities((isset($info['name']) && ($info['name'] !== '')?$info['name']:'')); ?>" required  lay-verify="required"  autocomplete="off" class="layui-input">    
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label">上级分类</label>
                            <div class="layui-input-inline">
                                <select name="pid" lay-filter="menuselect">
                                <option value="0">顶级分类</option>
                                    <?php if(is_array($menu_select) || $menu_select instanceof \think\Collection || $menu_select instanceof \think\Paginator): $i = 0; $__LIST__ = $menu_select;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?>
                                        <option value="<?php echo htmlentities($v['id']); ?>" title="<?php echo htmlentities($v['level']); ?>"   <?php if(!empty($pid) && $pid == $v['id']): ?>selected<?php endif; ?>><?php if($v['level'] > '0'): for($i=0;$i<$v['level'];$i++){echo ' &nbsp;&nbsp;&nbsp;&nbsp;';} ?><?php endif; ?><?php echo htmlentities($v['name']); ?></option>
                                    <?php endforeach; endif; else: echo "" ;endif; ?> 
                                </select>
                            </div>
                       </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label" for="icon">图标</label>
                            <div class="layui-input-inline">
                                <input type="text" name="icon" maxlength="20"  id="icon" value="<?php echo htmlentities((isset($info['icon']) && ($info['icon'] !== '')?$info['icon']:'')); ?>" autocomplete="off" class="layui-input">    
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label" for="op">控制器</label>
                            <div class="layui-input-inline">
                                <input type="text" name="op" maxlength="20"  id="op" value="<?php echo htmlentities((isset($info['op']) && ($info['op'] !== '')?$info['op']:'')); ?>"   autocomplete="off" class="layui-input">    
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label" for="acts">操作</label>
                            <div class="layui-input-inline">
                                <input type="text" name="acts" maxlength="20"  id="acts" value="<?php echo htmlentities((isset($info['act']) && ($info['act'] !== '')?$info['act']:'')); ?>"   autocomplete="off" class="layui-input">    
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label">是否显示</label>
                            <div class="layui-input-block">
                              <input type="checkbox" name="status" lay-skin="switch" lay-text="是|否" <?php echo !empty($info['status']) ? 'checked'  :  ''; ?>>
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label">是否是系统菜单</label>
                            <div class="layui-input-block">
                                <input type="checkbox" name="is_default" lay-skin="switch" lay-text="是|否" <?php echo !empty($info['is_default']) ? 'checked'  :  ''; ?>>
                            </div>
                        </div> 
                        <div class="layui-form-item">
                        <div class="layui-input-block">
                            <button class="layui-btn" lay-submit lay-filter="ajaxSubmit">立即提交</button>
                            <button type="reset" class="layui-btn layui-btn-primary">重置</button>
                        </div>
                        </div>
                </form>
            </div>
        </div>

<script type="text/javascript">
    layui.use('form', function(){
    var form = layui.form;
        form.on('select(menuselect)', function(data){
        level = data.elem[data.elem.selectedIndex].title;
        if(level == 3){
            layer.alert('菜单最多不得超过三级！',function(){
                location.href = location.href;
            })
            
        }
    });   
    });
</script>
</body>
</html>