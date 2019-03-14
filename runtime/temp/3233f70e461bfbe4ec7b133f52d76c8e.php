<?php /*a:2:{s:76:"/www/wwwroot/sam_zhuzhouyike_com/application/admin/view/wechat/weConfig.html";i:1551351397;s:74:"/www/wwwroot/sam_zhuzhouyike_com/application/admin/view/public/layout.html";i:1551805875;}*/ ?>
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
    .form-container {
    max-width: 800px;
}
.label-required:after {
    content: '*';
    color: red;
    position: absolute;
    margin-left: 4px;
    font-weight: bold;
    line-height: 1.8em;
    top: 6px;
    right: 5px;
}
.layui-word-aux {
    padding-top:5px !important; 
}
</style>
<body style="background-color: #FFF; overflow: auto;">
    <div class="layui-tab layui-tab-brief" lay-filter="docDemoTabBrief">
        <ul class="layui-tab-title">
            <li class="layui-this"> <a href="<?php echo url('admin/wechat/weConfig'); ?>">公众号详情</a> </li>
            <li style="float:right;padding: 0;min-width: 35px;"><div title="刷新数据" class="pReload"><i class="fa fa-refresh"></i></div></li>
            <li style="float:right;padding: 0;min-width: 35px;"><a  href="javascript:history.back();" title="返回列表"> <i class="fa  fa-arrow-left"></i></a></li>
        </ul>
    </div>
    <div class="layui-tab-content">
                <div class="layui-tab-item layui-show">
                    <form class="layui-form layui-form form-container" action="<?php echo url('admin/wechat/updateConfig'); ?>" method="post">
                        <?php echo token(); ?>
                        <input type="hidden" name="id" value="<?php echo htmlentities($info['id']); ?>">
                        <div class="layui-form-item">
                            <label class="layui-form-label label-required" for="wxname">公众号名称</label>
                            <div class="layui-input-block">
                                <input type="text" name="wxname"   id="wxname" value="<?php echo htmlentities((isset($info['wxname']) && ($info['wxname'] !== '')?$info['wxname']:'')); ?>" required  lay-verify="required"  autocomplete="off" class="layui-input"> 
                                <p class="layui-word-aux">公众号名称，可在公众平台查看</p>  
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label label-required" for="appid">Appid</label>
                            <div class="layui-input-block">
                                <input type="text" name="appid"   id="appid" value="<?php echo htmlentities((isset($info['appid']) && ($info['appid'] !== '')?$info['appid']:'')); ?>"  required  lay-verify="required"  autocomplete="off" class="layui-input">
                                <p class="layui-word-aux">公众号应用ID是所有接口必要参数，可以在公众号平台 [ 开发 > 基本配置 ] 页面获取。</p>   
                            </div>      
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label label-required" for="appsecret">Appsecret</label>
                            <div class="layui-input-block">
                                <input type="text" name="appsecret"   id="appsecret" value="<?php echo htmlentities((isset($info['appsecret']) && ($info['appsecret'] !== '')?$info['appsecret']:'')); ?>"  required  lay-verify="required"  autocomplete="off" class="layui-input"> 
                                <p class="layui-word-aux">公众号应用密钥是所有接口必要参数，可以在公众号平台 [ 开发 > 基本配置 ] 页面授权后获取。。</p> 
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label label-required" for="token">Token</label>
                            <div class="layui-input-block">
                                <input type="text" name="token"   id="token" value="<?php echo htmlentities((isset($info['token']) && ($info['token'] !== '')?$info['token']:'')); ?>"  required  lay-verify="required"  autocomplete="off" class="layui-input">
                                <p class="layui-word-aux">公众号平台与系统对接认证Token，请优先填写此参数并保存，然后再在微信公众号平台操作对接。</p>  
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label" for="aeskey">EncodingAESKey</label>
                            <div class="layui-input-block">
                                <input type="text" name="aeskey"   id="aeskey" value="<?php echo htmlentities((isset($info['aeskey']) && ($info['aeskey'] !== '')?$info['aeskey']:'')); ?>"  required  lay-verify="required"  autocomplete="off" class="layui-input"> 
                                <p class="layui-word-aux">公众号平台接口设置为加密模式，消息加密密钥必需填写并保持与公众号平台一致</p> 
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label" for="wxid">公众号原始ID</label>
                            <div class="layui-input-block">
                                <input type="text" name="wxid"   id="wxid" value="<?php echo htmlentities((isset($info['wxid']) && ($info['wxid'] !== '')?$info['wxid']:'')); ?>"    autocomplete="off" class="layui-input"> 
                                <p class="layui-word-aux">原始ID，可在公众平台基本配置中查看</p>
                            </div>                   
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label" for="weixin">微信号</label>
                            <div class="layui-input-block">
                                <input type="text" name="weixin"   id="weixin" value="<?php echo htmlentities((isset($info['weixin']) && ($info['weixin'] !== '')?$info['weixin']:'')); ?>"    autocomplete="off" class="layui-input"> 
                                <p class="layui-word-aux">公众号的微信号，可在公众平台基本配置中查看</p>
                            </div>
                        </div> 
                        <div class="layui-form-item">
                            <label class="layui-form-label" for="weixin">消息推送接口</label>
                            <div class="layui-input-block">
                                <input type="text"  value="http://sam.zhuzhouyike.com/api/push/index"   readonly autocomplete="off" class="layui-input"> 
                                <p class="layui-word-aux">公众号服务平台接口通知URL, 公众号消息接收与回复等</p>
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