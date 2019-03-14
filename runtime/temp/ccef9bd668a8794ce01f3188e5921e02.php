<?php /*a:2:{s:76:"/www/wwwroot/sam_zhuzhouyike_com/application/admin/view/article/article.html";i:1548823380;s:74:"/www/wwwroot/sam_zhuzhouyike_com/application/admin/view/public/layout.html";i:1551805875;}*/ ?>
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
<script type="text/javascript" src="/public/plugins/Ueditor/ueditor.config.js"></script>
<script type="text/javascript" src="/public/plugins/Ueditor/ueditor.all.min.js"></script>
<script type="text/javascript" charset="utf-8" src="/public/plugins/Ueditor/lang/zh-cn/zh-cn.js"></script>
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
            <li> <a href="<?php echo url('admin/article/articlelist'); ?>">文章列表</a> </li>
            <li class="layui-this"><a  href="<?php echo url('admin/article/article'); ?>"><?php echo !empty($info['article_id']) ? '编辑'  :  '新增'; ?>文章</a></li>
            <li style="float:right;padding: 0;min-width: 35px;"><div title="刷新数据" class="pReload"><i class="fa fa-refresh"></i></div></li>
            <li style="float:right;padding: 0;min-width: 35px;"><a  href="javascript:history.back();" title="返回列表"> <i class="fa  fa-arrow-left"></i></a></li>
        </ul>
    </div>
    <div class="layui-tab-content">
                <div class="layui-row">
                    <form class="layui-form" action="<?php echo url('admin/article/articleHandle'); ?>" method="post">
                        <input type="hidden" name="act" id="act" value="<?php echo !empty($info['article_id']) ? 'edit'  :  'add'; ?>">
                        <input type="hidden" name="id" value="<?php echo htmlentities((isset($info['article_id']) && ($info['article_id'] !== '')?$info['article_id']:'')); ?>">
                        <input type="hidden" name="auth_code" value="<?php echo htmlentities(app('config')->get('AUTH_CODE')); ?>"/>
                        <?php echo token(); ?>
                        <div class="layui-form-item">
                            <label class="layui-form-label" for="title">标题</label>
                            <div class="layui-input-inline">
                                <input type="text" name="title" maxlength="20"  id="title" value="<?php echo htmlentities((isset($info['title']) && ($info['title'] !== '')?$info['title']:'')); ?>"   autocomplete="off" class="layui-input">   
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label" for="cat_id">所属分类</label>
                            <div class="layui-input-inline">
                                <select name="cat_id" id="cat_id"  class="layui-select" lay-verify="required">
                                    <?php if(is_array($cats) || $cats instanceof \think\Collection || $cats instanceof \think\Paginator): $i = 0; $__LIST__ = $cats;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$item): $mod = ($i % 2 );++$i;?>
                                        <option value="<?php echo htmlentities($item['cat_id']); ?>" <?php if(!empty($info['group']) && $key == $info['group']): ?>selected<?php endif; ?>> <?php if($item['level'] > '0'): for($i=0;$i<$item['level'];$i++){echo ' &nbsp;&nbsp;&nbsp;&nbsp;';} ?><?php endif; ?> <?php echo htmlentities($item['cat_name']); ?></option>
                                    <?php endforeach; endif; else: echo "" ;endif; ?>
                                </select>    
                            </div>
                        </div>
                        <div class="layui-form-item">
                                <label class="layui-form-label" for="keywords">seo关键字</label>
                                <div class="layui-input-inline">
                                    <input type="text" name="keywords" maxlength="20"  id="keywords" value="<?php echo htmlentities((isset($info['keywords']) && ($info['keywords'] !== '')?$info['keywords']:'')); ?>" required  lay-verify="required"  autocomplete="off" class="layui-input"> 
                                </div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label">是否显示</label>
                            <div class="layui-input-block">
                                <input type="checkbox" name="is_open" lay-skin="switch" lay-text="是|否" <?php echo !empty($info['is_open']) ? 'checked'  :  ''; ?>>
                            </div>
                        </div>
                        <div class="layui-form-item">
                                <label class="layui-form-label">内容</label>
                                <div class="layui-input-block">
                  
                                    <textarea class="span12 ckeditor" id="post_content" name="content" title="">
                                        <?php if(isset($info['content'])): ?> 
                                            <?php echo htmlentities(html_entity_decode($info['content'])); ?>  
                                        <?php endif; ?>
                                    </textarea>  
        
                                        
                                </div>
                        </div>
                        <div class="layui-form-item">
                                <label class="layui-form-label" for="publish_time">发布时间</label>
                                <div class="layui-input-inline">
                                    <?php if(isset($info['publish_time'])): ?> 
                                    <input type="text" name="publish_time"  id="publish_time" value="<?php echo htmlentities(date('Y-m-d',!is_numeric($info['publish_time'])? strtotime($info['publish_time']) : $info['publish_time'])); ?>" required  lay-verify="required"  autocomplete="off" class="layui-input"> 
                                    <?php else: ?>
                                    <input type="text" name="publish_time"  id="publish_time" value="<?php echo date('Y-m-d'); ?>" required  lay-verify="required"  autocomplete="off" class="layui-input"> 
                                    <?php endif; ?>
                                      
                                </div>
                        </div>
                        <div class="layui-form-item">
                                <label class="layui-form-label" for="store_logo">缩略图</label>
                                <div class="layui-input-inline">
                                    <input type="text" name="thumb"  id="thumb" value="<?php echo htmlentities((isset($config['thumb']) && ($config['thumb'] !== '')?$config['thumb']:'')); ?>" onClick="GetUploadify(1,'thumb','article','img_call_back')" readonly  placeholder="请上传文章缩略图" autocomplete="off" class="layui-input">
                                    <span class="show">
                                        <a id="thumb_pre" target="_blank" class="nyroModal" rel="gal" href="<?php echo htmlentities((isset($config['thumb']) && ($config['thumb'] !== '')?$config['thumb']:'')); ?>">
                                            <img  id="thumb_preimg" style="width:150px;height:60px;border:2px solid #e5e5e5;border-radius:5px;margin-top:5px" src="<?php echo htmlentities((isset($config['thumb']) && ($config['thumb'] !== '')?$config['thumb']:'')); ?>" alt="图片" onmouseover="layer.tips('<img src=<?php echo htmlentities((isset($config['thumb']) && ($config['thumb'] !== '')?$config['thumb']:'')); ?>>',this,{tips: [1, '#fff']});" onmouseout="layer.closeAll();">
                                            
                                        </a>
                                    </span>
                                </div>
                                <div class="layui-input-inline">
                                    <input type="button" class="layui-btn layui-btn-normal"  onClick="GetUploadify(1,'thumb','article','img_call_back')" value="上传">
                                </div>
                                <div class="layui-form-mid layui-word-aux" style="margin-left:-200px">文章缩略图</div>
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
        //监听提交
    form.on('submit(formDemo)', function(data){
          $.post("<?php echo url('admin/system/edit_right'); ?>",data.field,function(res){
              if(!res.code){
                layer.msg(res.msg,{icon: 2,time: 1000})
              }else{
                layer.msg(res.msg,{icon: 1,time: 1000},function () {
                        window.location.href = res.url;
                    })
              }
          })
          return false;
        });
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
      });

       
    var url="<?php echo url('admin/Ueditor/index',array('savePath'=>'article')); ?>";
    var ue = UE.getEditor('post_content',{
        serverUrl :url,
        zIndex: 999,
        initialFrameWidth: "80%", //初化宽度
        initialFrameHeight: 300, //初化高度            
        focus: false, //初始化时，是否让编辑器获得焦点true或false
        maximumWords: 99999, removeFormatAttributes: 'class,style,lang,width,height,align,hspace,valign',//允许的最大字符数 'fullscreen',
        pasteplain:false, //是否默认为纯文本粘贴。false为不使用纯文本粘贴，true为使用纯文本粘贴
        autoHeightEnabled: true
    });

      function img_call_back(fileurl_tmp , elementid)
    {
        $("#"+elementid).val(fileurl_tmp);
        $("#"+elementid+'_pre').attr('href', fileurl_tmp);
        $("#"+elementid+'_preimg').attr('src', fileurl_tmp);
        $("#"+elementid+'_preimg').attr('onmouseover', "layer.tips('<img src="+fileurl_tmp+">',this,{tips: [1, '#fff']});");
    }  
</script>

<script type="text/javascript">
   
    // 判断输入框是否为空
    function adsubmit(){
        $('.err').show();
        var password =$('#password').val();
        var act =$('#act').val();
        if((password.length < 6 || password.length>18) && act=='add'){
            layer.msg('密码长度应该在6-18位！', {icon: 2,time: 1000});//alert('少年，密码不能为空！');
            return false;
        }
        $.ajax({
            async:false,
            url:'/index.php/Admin/Admin/adminHandle?t='+Math.random(),
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
</body>
</html>