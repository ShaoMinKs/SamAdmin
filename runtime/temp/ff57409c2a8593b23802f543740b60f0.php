<?php /*a:2:{s:78:"/www/wwwroot/sam_zhuzhouyike_com/application/admin/view/system/edit_right.html";i:1548732684;s:74:"/www/wwwroot/sam_zhuzhouyike_com/application/admin/view/public/layout.html";i:1551805875;}*/ ?>
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
<div id="toolTipLayer" style="position: absolute; z-index: 9999; display: none; visibility: visible; left: 95px; top: 573px;"></div>
<div id="append_parent"></div>
<div id="ajaxwaitid"></div>
<div class="page" style="height:100%">
    <div class="fixed-bar">
        <div class="item-title" style="padding-bottom: 5px">
           
            <div class="subject" style="height: auto;margin-left: 15px">
                <h3><?php echo !empty($info['id']) ? '编辑'  :  '新增'; ?>权限资源</h3>
            </div>
            <a class="layui-btn layui-btn-sm" style="float: right"  href="javascript:history.back();" title="返回列表"> <i class="fa  fa-arrow-left"></i> 返回上一页</a>
        </div>
    </div>
        <div class="layui-row" style="margin-top: 15px">
            <form class="layui-form" action="<?php echo url('admin/system/edit_right'); ?>" method="post" style="height:100%">
                <input type="hidden" name="act" id="act" value="<?php echo htmlentities($act); ?>">
                <input type="hidden" name="id" value="<?php echo htmlentities((isset($info['id']) && ($info['id'] !== '')?$info['id']:'')); ?>">
                <input type="hidden" name="auth_code" value="<?php echo htmlentities(app('config')->get('AUTH_CODE')); ?>"/>
                <div class="layui-form-item">
                    <label class="layui-form-label" for="name">权限资源名称</label>
                    <div class="layui-input-inline">
                        <input type="text" name="name" maxlength="20"  id="name" value="<?php echo htmlentities((isset($info['name']) && ($info['name'] !== '')?$info['name']:'')); ?>" required  lay-verify="required"  autocomplete="off" class="layui-input"> 
                        <span class="err" id="err_name"></span>    
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label" for="group">所属分组</label>
                    <div class="layui-input-inline">
                        <select name="group" id="group"  class="layui-select" lay-verify="required">
                            <?php if(is_array($group) || $group instanceof \think\Collection || $group instanceof \think\Paginator): $i = 0; $__LIST__ = $group;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$item): $mod = ($i % 2 );++$i;?>
                                <option value="<?php echo htmlentities($key); ?>" <?php if(!empty($info['group']) && $key == $info['group']): ?>selected<?php endif; ?>><?php echo htmlentities($item); ?></option>
                            <?php endforeach; endif; else: echo "" ;endif; ?>
                        </select>    
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label" for="group">控制器</label>
                    <div class="layui-input-inline">
                        <select  id="controller"  lay-verify="required"  lay-filter="right">
                            <?php if(is_array($planList) || $planList instanceof \think\Collection || $planList instanceof \think\Paginator): $i = 0; $__LIST__ = $planList;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
                                <option value="<?php echo htmlentities($vo); ?>"><?php echo htmlentities($vo); ?></option>
                            <?php endforeach; endif; else: echo "" ;endif; ?>
                        </select>    
                    </div>
                    <div class=" method-list">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label" for="group">权限码</label>
                    <div class="layui-input-inline">
                        <table class="layui-table" lay-skin="nob"  lay-size="sm">
                            <tr><th style="width:80%">权限码</th><th style="width: 100px;text-align: center;" >操作</th></tr>
                            <tbody id="rightList">
                                <?php if(!empty($info)): if(is_array($info['right']) || $info['right'] instanceof \think\Collection || $info['right'] instanceof \think\Paginator): if( count($info['right'])==0 ) : echo "" ;else: foreach($info['right'] as $key=>$vo): ?>
                                    <tr id="<?php echo str_replace('@','_',$vo); ?>">
                                        <td><input name="right[]" type="text" value="<?php echo htmlentities($vo); ?>" class="layui-input" style="width:300px;"></td>
                                        <td style="text-align: center;"><a class="layui-btn" href="javascript:;" onclick="$(this).parent().parent().remove();">删除</a></td>
                                    </tr>
                                <?php endforeach; endif; else: echo "" ;endif; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>    
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


<script>
    var form = layui.form;

        //监听select
   form.on('select(right)', function(data){
            $.ajax({
                url: "<?php echo url('system/ajax_get_action'); ?>",
                data:{'controller':data.value},
                type:'post',
                dataType:'html',
                success : function(res){
                    $('.method-list').empty().append(res);
                    form.render('checkbox');
                }
            });

        })
    form.on('checkbox()', function(data){
        var is_check = data.elem.checked;
        var ncode = $('#controller').val();
        var row_id = ncode+'_'+ data.value;
        if(is_check){
            var a = [];
            $('#rightList .form-control').each(function(i,o){
                    if($(o).val() != ''){
                        a.push($(o).val());
                    }
                });
    
            if(ncode !== ''){
                var temp = ncode+'@'+ data.value;
                if($.inArray(temp,a) != -1){
                    return ;
                }
            }else{
                layer.alert("请选择控制器" , {icon:2,time:1000});
                return;
            }
            var strtr = "<tr id="+row_id+">";
            if(ncode!= ''){
                strtr += '<td><input type="text" name="right[]" value="'+ncode+'@'+ data.value+'" readonly class="layui-input" style="width:300px;"></td>';
            }else{
                strtr += '<td><input type="text" name="right[]" value="" readonly class="layui-input" style="width:300px;"></td>';
            }
            strtr += '<td style="text-align: center;"><a href="javascript:;" class="layui-btn" onclick="$(this).parent().parent().remove();">删除</a></td>';
            $('#rightList').append(strtr);
        }else{
            $("#"+row_id).remove();
        }

    })

</script>

</body>
</html>