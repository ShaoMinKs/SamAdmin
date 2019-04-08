<?php /*a:2:{s:74:"/www/wwwroot/sam_zhuzhouyike_com/application/admin/view/wechat/review.html";i:1551806153;s:74:"/www/wwwroot/sam_zhuzhouyike_com/application/admin/view/public/layout.html";i:1551805875;}*/ ?>
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
<link rel="stylesheet" href="/public/static/css/aui.css">
<!-- <link rel="stylesheet" href="/public/static/css/bootstrap.min.css"> -->
<link rel="stylesheet" href="/public/static/css/font-awesome.min.css">
<script src="/public/static/js/wechat.js?v=1.2"></script>
<body class="gray-bg  mini-navbar pace-done" style="min-width:auto">
    <style>
        * {font-family: "Microsoft YaHei" !important;letter-spacing: .01rem}
        html,body{display:block;height:100%;overflow:auto!important}
        .aui-chat .aui-chat-media img {border-radius:0}
        .aui-chat .aui-chat-inner {max-width:80%!important;}
        .aui-chat .bg-white {background: #f5f5f5!important;border:1px solid #ccc;}
        .aui-chat .time {color: #f5f5f5;background:rgba(0,0,0,.3);padding:.1rem .3rem;border-radius:.2rem;font-size:.5rem;}
        .aui-chat .aui-chat-content .aui-chat-arrow.two {top:.7rem!important;background:#f5f5f5!important;left:-0.25rem!important;}
        .aui-chat .aui-chat-content .aui-chat-arrow.one {top:.7rem!important;background:#f5f5f5!important;border:1px solid #ccc!important;left:-0.28rem!important;}
        .aui-card-list-content-padded img {max-width: 100% !important;}
    </style>
<?php if(($type == 'text') or ($type == 'image') or ($type == 'music')): ?>
<section class="aui-chat">
    <div class="aui-chat-header"><span class="time"><?php echo date('H:i'); ?></span></div>
    <div class="aui-chat-item aui-chat-left">
        <div class="aui-chat-media">
            <img style="border-radius:50%;border:1px solid #ccc;min-width:42px;display:inline-block;" src="/public/static/images/headimg.png"/>
        </div>
        <div class="aui-chat-inner">
            <?php if($type == 'text'): ?>
            <div class="aui-chat-content bg-white">
                <div class="aui-chat-arrow one"></div>
                <div class="aui-chat-arrow two"></div>
                <?php echo htmlspecialchars_decode((isset($content) && ($content !== '')?$content:'')); ?>
            </div>
            <?php elseif($type == 'image'): ?>
            <div class="aui-chat-content bg-white">
                <div class="aui-chat-arrow one"></div>
                <div class="aui-chat-arrow two"></div>
                <img src='<?php echo htmlentities((isset($content) && ($content !== '')?$content:"/public/static/theme/img/image.png")); ?>'/>
            </div>
            <?php elseif($type == 'music'): ?>
            <div class="aui-chat-content" style='background:#080'>
                <div class="aui-chat-arrow one" style="background:#080!important;"></div>
                <div class="aui-chat-arrow two" style="background:#080!important;"></div>
                <table>
                    <tr>
                        <td style='overflow:hidden;white-space:nowrap;color:#f5f5f5;min-width:100%'>
                            <?php echo htmlentities((isset($title) && ($title !== '')?$title:'')); ?>
                        </td>
                        <td style='overflow:hidden;white-space:nowrap;color:#f5f5f5;max-width:100%;' rowspan="2">
                            <div style='position:absolute;right:0;top:0;bottom:0;width:2.5rem;background:#080;border-radius:5px'></div>
                            <div style='width:1.5rem;height:1.5rem;background:#0a0;padding:.1rem;text-align:center;position:absolute;right:.5rem;top:1rem'>
                                <i style='font-size:1rem' class='aui-iconfont aui-icon-video'></i>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td style='overflow:hidden;white-space:nowrap;color:#f5f5f5;font-size:.3rem;'>
                            <?php echo htmlentities((isset($desc) && ($desc !== '')?$desc:'')); ?>　　　　　　　　　　
                        </td>
                    </tr>
                </table>
            </div>
            <?php endif; ?>
        </div>
    </div>
</section>
<?php elseif($type == 'article'): ?>
<section class="aui-content">
    <div class="aui-card-list" style='margin-bottom:0'>
        <div class="aui-card-list-header" style='font-size:1rem'><?php echo htmlentities((isset($vo['title']) && ($vo['title'] !== '')?$vo['title']:'')); ?></div>
        <div class="aui-info" style='padding:0 15px'>
            <div class="aui-info-item" style='font-size:0.8rem;color:#666'>
                <span class="aui-margin-l-5"><?php echo date('Y-m-d',strtotime($vo['create_at'])); ?></span>
                <span class="aui-margin-l-5" style='color:#0099CC'><?php echo htmlentities((isset($vo['author']) && ($vo['author'] !== '')?$vo['author']:'')); ?></span>
            </div>
        </div>
        <?php if($vo['show_cover_pic'] == 1): ?>
        <div class="aui-card-list-content-padded"><img src="<?php echo htmlentities($vo['local_url']); ?>"/></div>
        <?php endif; ?>
        <div class="aui-card-list-content-padded" style="color:#333;font-size:0.8rem"><?php echo (isset($vo['content']) && ($vo['content'] !== '')?$vo['content']:''); ?></div>
        <?php if($vo['content_source_url']): ?>
        <div class="aui-card-list-footer" style="color:#999;">
            <div>
                <a style='color:#0099CC' target='_blank' href='<?php echo request()->url(); ?>'>阅读原文</a>
            </div>
        </div>
        <?php endif; ?>
    </div>
</section>
<?php elseif($type == 'video'): ?>
<section class="aui-chat">
    <div class="aui-chat-header"><span class="time"><?php echo date('H:i'); ?></span></div>
    <div class="aui-chat-item">
        <div class="aui-chat-content"
             style='background: #fff;border:1px solid #ccc;width:100%;max-width:100%;padding:0'>
            <section class="aui-content">
                <div class="aui-card-list" style='margin-bottom:0;background: none'>
                    <div class="aui-card-list-header" style='padding:0 .3rem 0 .3rem;min-height:1.5rem;white-space:nowrap;overflow: hidden;text-overflow:ellipsis'>
                        <?php echo htmlentities((isset($title) && ($title !== '')?$title:'')); ?>
                    </div>
                    <div style='font-size:.5rem;padding:0 .3rem .3rem .3rem;color:#999'><?php echo date('m月d日'); ?></div>
                    <div class="aui-card-list-content-padded aui-border-b" style='padding:0 .3rem'>
                        <video src="<?php echo htmlentities((isset($url) && ($url !== '')?$url:'')); ?>" width="100%" controls preload></video>
                    </div>
                    <div class="aui-card-list-footer" style='min-height:.8rem;padding:.2rem .3rem'>
                        <div style='font-size:.55rem;white-space:nowrap;overflow: hidden;text-overflow:ellipsis '>
                            <?php echo htmlentities((isset($desc) && ($desc !== '')?$desc:'')); ?>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
</section>

<?php elseif($type == 'news'): ?>
<section class="aui-chat">
    <div class="aui-chat-header"><span class="time"><?php echo date('H:i'); ?></span></div>
    <div class="aui-chat-item">
        <div class="aui-chat-content" style='background: #fff;border:1px solid #ccc;width:100%;max-width:100%;padding:0'>
            <section class="aui-content">
                <?php if(!empty($articles)): foreach($articles as $key=>$vo): if(count($articles) > 1): if($key < 1): ?>
                <div data-href="<?php echo url('admin/wechat/review'); ?>?content=<?php echo htmlentities($vo['id']); ?>&type=article" class="aui-card-list" style="cursor:pointer;margin:0;padding:.5rem .5rem .3rem .5rem;display:block;background:none">
                    <div class="aui-card-list-content" style='width:100%;height:10rem;background-repeat:no-repeat;background-image:url("<?php echo htmlentities($vo['local_url']); ?>");background-position:center;background-size:cover'></div>
                    <div class="aui-card-list-header" style='left:.5rem;right:.5rem;position:absolute;bottom:0.2rem;display:block;max-height:6em;overflow:hidden;text-overflow:ellipsis;background:rgba(0,0,0,.8);color:#fff'>
                        <?php echo htmlentities((isset($vo['title']) && ($vo['title'] !== '')?$vo['title']:'')); ?>
                    </div>
                </div>
                <?php else: ?>
                <table data-href="<?php echo url('admin/wechat/review'); ?>?content=<?php echo htmlentities($vo['id']); ?>&type=article" cellpadding="10"  class="aui-border-t" style='cursor:pointer;width:100%;margin:0;padding:.3rem .5rem .5rem .5rem;border-collapse: inherit'>
                    <tr style='width:100%;padding:0;margin:0;'>
                        <td style="text-overflow:ellipsis;overflow:hidden;display:inline-block;font-size: 15px"><?php echo htmlentities($vo['title']); ?></td>
                        <td style='width:3rem;height:3rem;background-repeat:no-repeat;background-image:url("<?php echo htmlentities($vo['local_url']); ?>");background-position:center;background-size:cover'></td>
                    </tr>
                </table>
                <?php endif; else: ?>
                <div class="aui-card-list" style="margin:0;padding:.5rem .5rem .3rem .5rem;display:block;background:none">
                    <div class="aui-card-list-header" style='padding:0;margin:0;min-height:1.2rem;display:block;overflow:hidden;text-overflow:ellipsis;'>
                        <?php echo htmlentities((isset($vo['title']) && ($vo['title'] !== '')?$vo['title']:'')); ?>
                    </div>
                    <div  style="padding:5px 0;color:#999;min-height:1rem;display:block;display:block;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;font-size:12px">
                        <?php echo date('m月d日'); ?>
                    </div>
                    <div class="aui-card-list-content" style='width:100%;height:10rem;background-repeat:no-repeat;background-image:url("<?php echo htmlentities($vo['local_url']); ?>");background-position:center;background-size:cover'></div>
                    <div class="aui-card-list-content-padded" style="color:#7b7b7b;padding:0;display:block;overflow:hidden;text-overflow:ellipsis">
                        <?php echo str_replace(['　',"n"],'',strip_tags($vo['digest'])); ?> ...
                    </div>
                </div>
                <div class="aui-card-list-content-padded aui-border-t" style="padding-top:.3rem">
                    <a class="aui-list-item-arrow" style="color:#333;font-size:.6rem;display:block" href="<?php echo url('admin/wechat/review'); ?>?content=<?php echo htmlentities($vo['id']); ?>&type=article">阅读全文</a>
                </div>
                <?php endif; ?>
                <?php endforeach; ?>
                <?php endif; ?>
            </section>
        </div>
    </div>
</section>
<script>
    $(function () {
        $('[data-href]').on('click', function () {
            window.location.href = this.getAttribute('data-href');
        });
    });
</script>
<?php endif; ?>
</body>