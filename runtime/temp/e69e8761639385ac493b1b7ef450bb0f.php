<?php /*a:2:{s:77:"/www/wwwroot/sam_zhuzhouyike_com/application/admin/view/admin/modify_pwd.html";i:1553154616;s:74:"/www/wwwroot/sam_zhuzhouyike_com/application/admin/view/public/layout.html";i:1551805875;}*/ ?>
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
    <div class="page">
        <div class="fixed-bar">
            <div class="item-title" style="padding-bottom: 5px">
               
                <div class="subject" style="height: auto;margin-left: 15px">
                    <h3><?php echo !empty($info['admin_id']) ? '编辑'  :  '新增'; ?>管理员</h3>
                </div>
               
            </div>
            <div class="layui-row" style="margin-top: 15px">
                    <form class="form-horizontal" id="adminHandle" method="post" name="admin_form">
                            <input type="hidden" name="admin_id" value="<?php echo htmlentities((isset($info['admin_id']) && ($info['admin_id'] !== '')?$info['admin_id']:'')); ?>">
                            <div class="layui-form-item">
                                <label class="layui-form-label" for="user_name">用户名</label>
                                <div class="layui-input-inline">
                                    <input type="text" name="user_name" maxlength="20"  id="user_name" value="<?php echo htmlentities((isset($info['user_name']) && ($info['user_name'] !== '')?$info['user_name']:'')); ?>" required  lay-verify="required"  autocomplete="off" class="layui-input"> 
                                    <span class="err" id="err_user_name"></span>    
                                </div>
                            </div>
          
                             <div class="layui-form-item">
                                <label class="layui-form-label" for="password">原密码</label>
                                <div class="layui-input-inline">
                                    <input type="password" id="old_pw" name="old_pw" value="" required  lay-verify="required"  autocomplete="off" class="layui-input"> 
                                    <span class="err" id="err_password"></span>      
                                </div>
                             </div>
                             <div class="layui-form-item">
                                <label class="layui-form-label" for="password">新密码</label>
                                <div class="layui-input-inline">
                                    <input type="password" id="new_pw" name="new_pw" value="" required  lay-verify="required"  autocomplete="off" class="layui-input"> 
                                    <span class="err" id="err_password"></span>      
                                </div>
                             </div>
                             <div class="layui-form-item">
                                <label class="layui-form-label" for="password">确认密码</label>
                                <div class="layui-input-inline">
                                    <input type="password" id="new_pw2" name="new_pw2" value="" required  lay-verify="required"  autocomplete="off" class="layui-input"> 
                                    <span class="err" id="err_password"></span>      
                                </div>
                             </div>
                             <div class="layui-form-item">
                                <div class="layui-input-block">
                                    <div class="layui-form-item">
                                        <div class="layui-input-block">
                                            <div class="layui-btn" onclick="adsubmit()">立即提交</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
            </div>
        </div>
    </div>
</body>

<script type="text/javascript">
    // 判断输入框是否为空
    function adsubmit(){
        var password =$('#new_pw').val();
        var password2 =$('#new_pw2').val();
        if($('#old_pw').val() == ''){
            layer.msg('密码不得为空！', {icon: 2,time: 1000});//alert('少年，密码不能为空！');
            $('#old_pw').focus();
            return false;
        }
        if((password.length < 6 || password.length>18)){
            layer.msg('密码长度应该在6-18位！', {icon: 2,time: 1000});//alert('少年，密码不能为空！');
            $('#new_pw').focus();
            return false;
        }
        if(password != password2){
            layer.msg('两次密码不一致！', {icon: 2,time: 1000});//alert('少年，密码不能为空！');
            return false;
        }
        $.ajax({
            url:'/index.php/Admin/Admin/modify_pwd?t='+Math.random(),
            data:$('#adminHandle').serialize(),
            type:'post',
            dataType:'json',
            success:function(data){
                if(!data.code){
                    layer.msg(data.msg,{icon: 2,time: 2000})
                    $.each(data.result,function (index,item) {
                        $('#err_'+index).text(item)
                    })
                }else{
                    layer.msg(data.msg,{icon: 1,time: 3000},function () {
                        window.location.href = data.url;
                    })
                }
            },
            error : function(XMLHttpRequest, textStatus, errorThrown) {
                $('#error').html('<span class="error">网络失败，请刷新页面后重试!</span>');
            }
        });
    }
</script>