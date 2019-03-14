<?php /*a:2:{s:73:"/www/wwwroot/sam_zhuzhouyike_com/application/admin/view/article/link.html";i:1551260009;s:74:"/www/wwwroot/sam_zhuzhouyike_com/application/admin/view/public/layout.html";i:1551805875;}*/ ?>
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
<script src="/public/static/js/layer/laydate/laydate.js"></script>
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
            <li> <a href="<?php echo url('admin/article/linklist'); ?>">链接列表</a> </li>
            <li class="layui-this"><a  href="<?php echo url('admin/article/link'); ?>"><?php echo !empty($info['link_id']) ? '编辑'  :  '新增'; ?>链接</a></li>
            <li style="float:right;padding: 0;min-width: 35px;"><div title="刷新数据" class="pReload"><i class="fa fa-refresh"></i></div></li>
            <li style="float:right;padding: 0;min-width: 35px;"><a  href="javascript:history.back();" title="返回列表"> <i class="fa  fa-arrow-left"></i></a></li>
        </ul>
    </div>
    <div class="layui-tab-content">
                <div class="layui-row">
                    <form class="layui-form" action="<?php echo url('admin/article/linkHandle'); ?>" method="post">
                        <input type="hidden" name="act" id="act" value="<?php echo !empty($info['link_id']) ? 'edit'  :  'add'; ?>">
                        <input type="hidden" name="link_id" value="<?php echo htmlentities((isset($info['link_id']) && ($info['link_id'] !== '')?$info['link_id']:'')); ?>">
                        <input type="hidden" name="auth_code" value="<?php echo htmlentities(app('config')->get('AUTH_CODE')); ?>"/>
                        <?php echo token(); ?>
                        <div class="layui-form-item">
                            <label class="layui-form-label" for="link_name">链接名称</label>
                            <div class="layui-input-inline">
                                <input type="text" name="link_name" maxlength="20"  id="link_name" value="<?php echo htmlentities((isset($info['link_name']) && ($info['link_name'] !== '')?$info['link_name']:'')); ?>" required  lay-verify="required"  autocomplete="off" class="layui-input">   
                            </div>
                        </div>
                        <div class="layui-form-item">
                                <label class="layui-form-label" for="link_url">链接地址</label>
                                <div class="layui-input-inline">
                                    <input type="text" name="link_url"   id="link_url" value="<?php echo htmlentities((isset($info['link_url']) && ($info['link_url'] !== '')?$info['link_url']:'')); ?>"  required  lay-verify="required"  autocomplete="off" class="layui-input"> 
                                </div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label">是否显示</label>
                            <div class="layui-input-block">
                                <input type="checkbox" name="is_show" lay-skin="switch" lay-text="是|否" <?php echo !empty($info['is_show']) ? 'checked'  :  ''; ?>>
                            </div>
                        </div> 
                        <div class="layui-form-item">
                                <label class="layui-form-label" for="link_logo">图片</label>
                                <div class="layui-input-inline">
                                    <input type="text" name="link_logo"  id="link_logo" value="<?php echo htmlentities((isset($info['link_logo']) && ($info['link_logo'] !== '')?$info['link_logo']:'')); ?>" onClick="GetUploadify(1,'link_logo','link','img_call_back')" readonly  placeholder="请上传链接图片" autocomplete="off" class="layui-input">
                                    <span class="show">
                                        <a id="link_logo_pre" target="_blank" class="nyroModal" rel="gal" href="<?php echo htmlentities((isset($config['link_logo']) && ($config['link_logo'] !== '')?$config['link_logo']:'')); ?>">
                                            <img  id="link_logo_preimg" style="width:150px;height:60px;border:2px solid #e5e5e5;border-radius:5px;margin-top:5px" src="<?php echo htmlentities((isset($info['link_logo']) && ($info['link_logo'] !== '')?$info['link_logo']:'')); ?>" alt="图片" onmouseover="layer.tips('<img src=<?php echo htmlentities((isset($info['link_logo']) && ($info['link_logo'] !== '')?$info['link_logo']:'')); ?>>',this,{tips: [1, '#fff']});" onmouseout="layer.closeAll();">
                                            
                                        </a>
                                    </span>
                                </div>
                                <div class="layui-input-inline">
                                    <input type="button" class="layui-btn layui-btn-normal"  onClick="GetUploadify(1,'link_logo','link','img_call_back')" value="上传">
                                </div>
                                <div class="layui-form-mid layui-word-aux" style="margin-left:-200px">链接缩略图</div>
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
<div id="goTop"> <a href="JavaScript:void(0);" id="btntop"><i class="fa fa-angle-up"></i></a><a href="JavaScript:void(0);" id="btnbottom"><i class="fa fa-angle-down"></i></a></div>     
<script>
      //Demo
layui.use('form', function(){
        var form = layui.form;
        var laydate = layui.laydate;
        laydate.render({
            elem: '#publish_time' //指定元素
            });
      });

       

      function img_call_back(fileurl_tmp , elementid)
    {
        $("#"+elementid).val(fileurl_tmp);
        $("#"+elementid+'_pre').attr('href', fileurl_tmp);
        $("#"+elementid+'_preimg').attr('src', fileurl_tmp);
        $("#"+elementid+'_preimg').attr('onmouseover', "layer.tips('<img src="+fileurl_tmp+">',this,{tips: [1, '#fff']});");
    }  
</script>
</body>
</html>