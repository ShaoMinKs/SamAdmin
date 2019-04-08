<?php /*a:2:{s:80:"/www/wwwroot/sam_zhuzhouyike_com/application/admin/view/system/storage_info.html";i:1552880084;s:74:"/www/wwwroot/sam_zhuzhouyike_com/application/admin/view/public/layout.html";i:1551805875;}*/ ?>
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
<div id="append_parent"></div>
<div id="ajaxwaitid"></div>
<div class="page">
    <div class="fixed-bar">
        <div class="item-title">
            <div class="subject">
                <h3>对象储存</h3>
                <h5>网站全局图片存储类型</h5>
            </div>
            <ul class="tab-base nc-row">
                <?php if(is_array($group_list) || $group_list instanceof \think\Collection || $group_list instanceof \think\Paginator): if( count($group_list)==0 ) : echo "" ;else: foreach($group_list as $k=>$v): ?>
                    <li><a href="<?php echo url('System/index',['inc_type'=> $k]); ?>" <?php if($k==$inc_type): ?>class="current"<?php endif; ?>><span><?php echo htmlentities($v); ?></span></a></li>
                <?php endforeach; endif; else: echo "" ;endif; ?>
            </ul>
        </div>
    </div>
        <div class="layui-row">
                <form action="<?php echo url('System/handle'); ?>" enctype="multipart/form-data" method="post" id="handlepost" class=" layui-anim-up layui-form form-container">
                    <div class="layui-form-item">
                     
                        <div class="layui-form-item">
                            <label class="layui-form-label label-required" for="record_no">储存引擎</label>
                            <div class="layui-input-block">
                               <?php foreach(['local'=>'本地服务器存储','qiniu'=>'七牛云存储','oss'=>'阿里云OSS存储'] as $k=>$v): ?>
                                <label class="think-radio">
                                    <input  <?php echo $config['storage_type']==$k ? 'checked'  :  ''; ?> type="radio" name="storage_type" value="<?php echo htmlentities($k); ?>" title="<?php echo htmlentities($v); ?>">
                                </label>
                                <?php endforeach; ?>
                                <p  data-storage-type="local" class="layui-word-aux">文件存储在本地服务器，请确保服务器的 ./static/upload/ 目录有写入权限</p>
                                <p  data-storage-type="qiniu" class="layui-word-aux"> 若还没有七牛云帐号，可<a target="_blank" href="https://portal.qiniu.com/signup?code=3lhz6nmnwbple">免费申请10G存储</a>，申请成功后添加公开bucket。</p>
                                <p  data-storage-type="oss" class="layui-word-aux"> 若还没有OSS存储账号, 可<a target="_blank" href="https://oss.console.aliyun.com">创建阿里云OSS存储</a>，需要配置OSS公开访问及跨域策略。</p>
                            </div>
                          </div>
                          <div class="layui-form-item" data-storage-type="qiniu">
                                <label class="layui-form-label label-required" for="record_no">存储区域</label>
                                <div class="layui-input-block" >
                                        <?php foreach(['华东','华北','华南','北美'] as $area): ?>
                                        <label class="think-radio">
                                            <input <?php echo $config['storage_qiniu_region']==$area ? 'checked'  :  ''; ?>   type="radio" name="storage_qiniu_region" value="<?php echo htmlentities($area); ?>" title="<?php echo htmlentities($area); ?>">                                   
                                        </label>
                                        <?php endforeach; ?>
                                        <p class="layui-word-aux">七牛云存储空间所在区域，需要严格对应储存所在区域才能上传文件。</p>
                                </div>
                            </div>
                        <div class="layui-form-item" data-storage-type="qiniu">
                                <label class="layui-form-label label-required" for="record_no">存储区域</label>
                                <div class="layui-input-block" >
                                        <?php foreach(['http','https','auto'] as $pro): ?>
                                        <label class="think-radio">
                                          <input  <?php echo $config['storage_qiniu_is_https']==$pro ? 'checked'  :  ''; ?>  type="radio" name="storage_qiniu_is_https" value="<?php echo htmlentities($pro); ?>"  title="<?php echo htmlentities($pro); ?>">                                    
                                        </label>
                                        <?php endforeach; ?>
                                    <p class="layui-word-aux">七牛云存储访问协议（http、https、auto），其中 https 需要配置证书才能使用，auto 为相对协议自动根据域名切换http与https。</p>
                                </div>
                        </div>

                        <div class="layui-form-item" data-storage-type="qiniu">
                                <label class="layui-form-label label-required" for="record_no">空间名称</label>
                                <div class="layui-input-block" >
                                    <input type="text" name="storage_qiniu_bucket" required="required" value="<?php echo htmlentities((isset($config['storage_qiniu_bucket']) && ($config['storage_qiniu_bucket'] !== '')?$config['storage_qiniu_bucket']:'')); ?>"
                                        title="请输入七牛云存储 Bucket (空间名称)" placeholder="请输入七牛云存储 Bucket (空间名称)" class="layui-input">
                                    <p class="layui-word-auxk">填写七牛云存储空间名称，如：static</p>
                                </div>
                        </div>

                        <div class="layui-form-item" data-storage-type="qiniu">
                                <label class="layui-form-label label-required" for="record_no">访问域名</label>
                                <div class="layui-input-block" >
                                     <input type="text" name="storage_qiniu_domain" required="required" value="<?php echo htmlentities((isset($config['storage_qiniu_domain']) && ($config['storage_qiniu_domain'] !== '')?$config['storage_qiniu_domain']:'')); ?>"
                                        title="请输入七牛云存储 Domain (访问域名)" placeholder="请输入七牛云存储 Domain (访问域名)" class="layui-input">
                                 <p class="layui-word-aux">填写七牛云存储访问域名，如：static.ctolog.cc</p>
                                </div>
                        </div>
                        <div class="layui-form-item" data-storage-type="qiniu">
                                <label class="layui-form-label label-required" for="record_no">访问密钥</label>
                                <div class="layui-input-block" >
                                 <input type="text" name="storage_qiniu_access_key" required="required" value="<?php echo htmlentities((isset($config['storage_qiniu_access_key']) && ($config['storage_qiniu_access_key'] !== '')?$config['storage_qiniu_access_key']:'')); ?>"
                                        title="请输入七牛云 AccessKey (访问密钥)" placeholder="请输入七牛云 AccessKey (访问密钥)" class="layui-input">
                                 <p class="layui-word-aux">可以在 [ 七牛云 > 个人中心 ] 设置并获取到访问密钥。</p>
                                </div>
                        </div>
                        <div class="layui-form-item" data-storage-type="qiniu">
                                <label class="layui-form-label label-required" for="record_no">安全密钥</label>
                                <div class="layui-input-block" >
                                <input type="text" name="storage_qiniu_secret_key" required="required" value="<?php echo htmlentities((isset($config['storage_qiniu_secret_key']) && ($config['storage_qiniu_secret_key'] !== '')?$config['storage_qiniu_secret_key']:'')); ?>" maxlength="43"
                                        title="请输入七牛云 SecretKey (安全密钥)" placeholder="请输入七牛云 SecretKey (安全密钥)" class="layui-input">
                                 <p class="layui-word-aux">可以在 [ 七牛云 > 个人中心 ] 设置并获取到安全密钥。</p>
                                </div>
                        </div>

                        <div class="layui-form-item" data-storage-type="oss">
                                <label class="layui-form-label label-required" for="record_no">空间名称</label>
                                <div class="layui-input-block" >
                                    <input type="text" name="storage_oss_bucket" required="required" value="<?php echo htmlentities((isset($config['storage_oss_bucket']) && ($config['storage_oss_bucket'] !== '')?$config['storage_oss_bucket']:'')); ?>"
                                        title="请输入OSS Bucket (空间名称)" placeholder="请输入OSS Bucket (空间名称)" class="layui-input">
                                 <p class="layui-word-aux">填写OSS存储空间名称，如：think-admin-oss</p>
                                </div>
                        </div>

                        <div class="layui-form-item" data-storage-type="oss">
                                <label class="layui-form-label label-required" for="record_no">数据中心</label>
                                <div class="layui-input-block" >
                                        <input type="text" name="storage_oss_endpoint" required="required" value="<?php echo htmlentities((isset($config['storage_oss_endpoint']) && ($config['storage_oss_endpoint'] !== '')?$config['storage_oss_endpoint']:'')); ?>"
                                        title="请输入OSS数据中心访问域名 (访问域名)" placeholder="请输入OSS数据中心访问域名 (访问域名)" class="layui-input">
                                         <p class="layui-word-aux">填写OSS数据中心访问域名，如：oss-cn-shenzhen.aliyuncs.com</p>
                                </div>
                        </div>

                        <div class="layui-form-item" data-storage-type="oss">
                                <label class="layui-form-label label-required" for="record_no">访问域名</label>
                                <div class="layui-input-block" >
                                        <input type="text" name="storage_oss_domain" required="required" value="<?php echo htmlentities((isset($config['storage_oss_domain']) && ($config['storage_oss_domain'] !== '')?$config['storage_oss_domain']:'')); ?>"
                                        title="请输入OSS存储 Domain (访问域名)" placeholder="请输入OSS存储 Domain (访问域名)" class="layui-input">
                                 <p class="layui-word-aux">填写OSS存储外部访问域名，如：think-admin-oss.oss-cn-shenzhen.aliyuncs.com</p>
                                </div>
                        </div>

                        <div class="layui-form-item" data-storage-type="oss">
                                <label class="layui-form-label label-required" for="record_no">访问密钥</label>
                                <div class="layui-input-block" >
                                    <input type="text" name="storage_oss_keyid" required="required" value="<?php echo htmlentities((isset($config['storage_oss_keyid']) && ($config['storage_oss_keyid'] !== '')?$config['storage_oss_keyid']:'')); ?>" maxlength="16"
                                        title="请输入16位OSS AccessKey (访问密钥)" placeholder="请输入OSS AccessKey (访问密钥)" class="layui-input">
                                 <p class="layui-word-aux">可以在 [ 阿里云 > 个人中心 ] 设置并获取到访问密钥。</p>
                                </div>
                        </div>
                       
                        <div class="layui-form-item" data-storage-type="oss">
                                <label class="layui-form-label label-required" for="record_no">安全密钥</label>
                                <div class="layui-input-block" >
                                        <input type="text" name="storage_oss_secret" required="required" value="<?php echo htmlentities((isset($config['storage_oss_secret']) && ($config['storage_oss_secret'] !== '')?$config['storage_oss_secret']:'')); ?>" maxlength="30"
                                        title="请输入30位OSS SecretKey (安全密钥)" placeholder="请输入OSS SecretKey (安全密钥)" class="layui-input">
                                 <p class="layui-word-aux">可以在 [ 阿里云 > 个人中心 ] 设置并获取到安全密钥。</p>
                                </div>
                        </div>
                       
                        <input type="hidden" name="inc_type" value="<?php echo htmlentities($inc_type); ?>">
                          <div class="layui-form-item">
                            <div class="layui-input-block">
                            <a class="layui-btn" lay-submit lay-filter="ajaxSubmit">立即提交</a>
                            </div>
                          </div>
                </form>
        </div>      
</div>
<div id="goTop"> <a href="JavaScript:void(0);" id="btntop"><i class="fa fa-angle-up"></i></a><a href="JavaScript:void(0);" id="btnbottom"><i class="fa fa-angle-down"></i></a></div>
</body>
<script type="text/javascript">
      (function () {
        buildForm('<?php echo htmlentities($config['storage_type']); ?>');
        $('[name=storage_type]').on('click', function () {
            buildForm($('[name=storage_type]:checked').val())
        });

        // 表单显示编译
        function buildForm(value) {
            var $tips = $("[data-storage-type='" + value + "']");
            $("[data-storage-type]").not($tips.show()).hide();
        }
    })();
   
</script>
</html>