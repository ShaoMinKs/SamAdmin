<?php /*a:2:{s:82:"/www/wwwroot/sam_zhuzhouyike_com/application/admin/view/wechat/keys_subscribe.html";i:1551951530;s:74:"/www/wwwroot/sam_zhuzhouyike_com/application/admin/view/public/layout.html";i:1551805875;}*/ ?>
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
</style>
<body class="gray-bg  pace-done">
          <div class="wrapper wrapper-content animated fadeInRight">
              <div class="row">
                  <div class="ibox-title">
                          <h5 style="color:#333">编辑关注默认回复  <small></small></h5>
                  </div>
                    <div class="ibox-content ">
                        <!-- 效果预览区域 开始 -->
                        <div class="mobile-preview pull-left" style="margin-top:13px;">
                                <div class="mobile-header">公众号</div>
                                <div class="mobile-body">
                                    <iframe id="phone-preview" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="/index.php/admin/Wechat/review.html?type=text&amp;content=%E6%84%9F%E8%B0%A2%E6%82%A8%E7%9A%84%E5%85%B3%E6%B3%A8%EF%BC%81%E7%B3%BB%E5%88%97%E5%8A%9F%E8%83%BD%E5%B0%86%E9%99%86%E7%BB%AD%E4%B8%8A%E7%BA%BF%EF%BC%81%E5%A6%82%E6%9E%9C%E6%82%A8%E6%98%AF%E6%94%BF%E5%8D%8F%E5%A7%94%E5%91%98%EF%BC%8C%E8%AF%B7%E6%82%A8%E7%82%B9%E5%87%BB%E4%B8%8B%E6%96%B9%E7%9A%84%E2%80%9C%E8%BA%AB%E4%BB%BD%E9%AA%8C%E8%AF%81%E2%80%9D%E7%BB%91%E5%AE%9A%E6%89%8B%E6%9C%BA%E5%8F%B7%E7%A0%81%EF%BC%8C%E7%99%BB%E9%99%86%E2%80%9C%E5%BE%AE%E4%BF%A1%E7%89%88%E5%B1%A5%E8%81%8C%E5%B9%B3%E5%8F%B0%E2%80%9D%E5%92%8C%E2%80%9C%E6%94%BF%E5%8D%8F%E5%85%A8%E4%BC%9A%E4%B8%93%E9%A2%98%E7%BD%91%E2%80%9D%EF%BC%81"></iframe>
                                </div>
                        </div>
                          <!-- 效果预览区域 结束 -->
                        <div class="row keys-container">
                            <div class="col-xs-6 margin-left-15">
                                <form class="form-horizontal layui-form" role="form" data-auto="true" action="/index.php/admin/Wechat/keysSubscribe.html" method="post" data-listen="true" novalidate="novalidate">
                                <fieldset class="layui-elem-field layui-box" style="    width: 535px; height: 590px;position: absolute;">
                                    <legend style="margin-left: 20px; padding: 0 10px;font-size: 20px;font-weight: 300;"> 编辑关注默认回复</legend>
                                        <div class="form-group">
                                            <label class="col-xs-2 control-label layui-form-label label-required">规则状态</label>
                                            <div class="col-xs-8">
                                                <div class="mt-radio-inline padding-bottom-0">
                                                    <input type="radio" name="status" <?php if((isset($vo) && $vo['status'] == 1) || !isset($vo)): ?>checked<?php endif; ?> value="1" title="启动">
                                                    <input type="radio" name="status" <?php if(isset($vo) && $vo['status'] == 0): ?>checked<?php endif; ?> value="0" title="禁用">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-xs-2 control-label layui-form-label label-required">消息类型</label>
                                            <div class="col-xs-8">
                            
                                                
                                                <label class="think-radio">
                                                    <input type="radio" lay-ignore name="type" value="text"  <?php if((isset($vo) && $vo['type'] == 'text') || !isset($vo)): ?>checked<?php endif; ?>  >文字
                                                </label>
                                                <label class="think-radio">
                                                        <input name="type" lay-ignore  type="radio" <?php if(isset($vo) && $vo['type'] == 'news'): ?>checked<?php endif; ?> value="news"> 图文
                                                </label>
                                                <label class="think-radio">
                                                        <input name="type"  lay-ignore type="radio" <?php if(isset($vo) && $vo['type'] == 'image'): ?>checked<?php endif; ?> value="image"> 图片
                                                </label>
                                                <label class="think-radio">
                                                        <input name="type"  lay-ignore type="radio" <?php if(isset($vo) && $vo['type'] == 'music'): ?>checked<?php endif; ?>  value="music"> 音乐
                                                </label>
                                                <label class="think-radio">
                                                        <input name="type"  lay-ignore type="radio"  <?php if(isset($vo) && $vo['type'] == 'video'): ?>checked<?php endif; ?> value="video"> 视频
                                                </label>
                                                
                                            <!-- {/foreach} -->
                                            </div>
                                        </div>

                                        <div class="form-group" data-keys-type='text'>
                                            <label class="col-xs-2 control-label layui-form-label label-required">规则内容</label>
                                            <div class="col-xs-8">
                                                <textarea name="content" maxlength="10000" class="form-control" rows="3"><?php echo htmlentities((isset($vo['content']) && ($vo['content'] !== '')?$vo['content']:'说点什么吧')); ?></textarea>
                                            </div>
                                        </div>

                                        <div class="form-group" data-keys-type='news'>
                                            <label class="col-xs-2 control-label layui-form-label">选取图文</label>
                                            <div class="col-xs-8">
                                                <a class="btn btn-link" data-title="选择图文" data-iframe="<?php echo url('admin/wechat/newsSelect'); ?>?field=<?php echo encode('news_id'); ?>">选择图文</a>
                                                <input type="hidden" class='layui-input' value="<?php echo htmlentities((isset($vo['news_id']) && ($vo['news_id'] !== '')?$vo['news_id']:0)); ?>" name="news_id">
                                            </div>
                                        </div>

                                        <div class="form-group" data-keys-type='image'>
                                            <label class="col-xs-2 control-label layui-form-label label-required">图片地址</label>
                                            <div class="col-xs-8">
                                                <input type="text" class="layui-input" id="image_url" onchange="$(this).nextAll('img').attr('src', this.value);"
                                                    value="<?php echo htmlentities((isset($vo['image_url']) && ($vo['image_url'] !== '')?$vo['image_url']:'http://sam.zhuzhouyike.com/public/static/images/wechat/images.jpg')); ?>"
                                                    name="image_url" required title="请上传图片或输入图片URL地址">
                                                <p class="help-block">文件最大2Mb，支持bmp/png/jpeg/jpg/gif格式</p>
                                                <img data-tips-image  id="image_url_preimg" class="img-thumbnail" src='<?php echo htmlentities((isset($vo['image_url']) && ($vo['image_url'] !== '')?$vo['image_url']:"http://sam.zhuzhouyike.com/public/static/images/wechat/images.jpg")); ?>' style="width:120px;height: auto;border-radius: 5px;margin-top: 5px;">
                                                <a onClick="GetUploadify(1,'image_url','wechat','img_call_back')"  class='btn btn-link'>上传图片</a>
                                            </div>
                                        </div>

                                        <div class="form-group" data-keys-type='voice'>
                                                <label class="col-xs-2 control-label layui-form-label label-required">上传语音</label>
                                                <div class="col-xs-8">
                                                    <div class="input-group">
                                                        <input class='layui-input' type="text" value="<?php echo htmlentities((isset($vo['voice_url']) && ($vo['voice_url'] !== '')?$vo['voice_url']:'')); ?>" name="voice_url" required title="请上传语音文件或输入语音URL地址　　">
                                                        <a data-file="one" data-type="mp3,wma,wav,amr" data-field="voice_url" class="input-group-addon"><i class="fa fa-upload"></i></a>
                                                    </div>
                                                    <p class="help-block">文件最大2Mb，播放长度不超过60s，mp3/wma/wav/amr格式</p>
                                                </div>
                                        </div>

                                    <div class="form-group" data-keys-type='music'>
                                        <label class="col-xs-2 control-label layui-form-label">音乐标题</label>
                                        <div class="col-xs-8">
                                            <input class='form-control' style="height: auto;" value="<?php echo htmlentities((isset($vo['music_title']) && ($vo['music_title'] !== '')?$vo['music_title']:'音乐标题')); ?>" name="music_title" required title="请输入音乐标题">
                                        </div>
                                    </div>
                                    <div class="form-group" data-keys-type='music'>
                                        <label class="col-xs-2 control-label layui-form-label label-required">上传音乐</label>
                                        <div class="col-xs-8">
                                            <div class="input-group">
                                                <input class='form-control' type="text" id="music_url" value="<?php echo htmlentities((isset($vo['music_url']) && ($vo['music_url'] !== '')?$vo['music_url']:'')); ?>" name="music_url" required title="请上传音乐文件或输入音乐URL地址">
                                                <a onClick="GetUploadify(1,'music_url','wechat','music_url_call_back','File')" class="input-group-addon"><i class="fa fa-upload" style="width: 50px"></i></a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group" data-keys-type='music'>
                                        <label class="col-xs-2 control-label layui-form-label">音乐描述</label>
                                        <div class="col-xs-8">
                                            <input name="music_desc" style="height: auto;" class="form-control" value="<?php echo htmlentities((isset($vo['music_desc']) && ($vo['music_desc'] !== '')?$vo['music_desc']:'音乐描述')); ?>">
                                        </div>
                                    </div>
                                    <div class="form-group" data-keys-type='music'>
                                        <label class="col-xs-2 control-label layui-form-label">音乐图片</label>
                                        <div class="col-xs-8">
                                            <input onchange="$(this).nextAll('img').attr('src', this.value);" type="text" id="music_image" class="form-control"
                                                value="<?php echo htmlentities((isset($vo['music_image']) && ($vo['music_image'] !== '')?$vo['music_image']:'http://sam.zhuzhouyike.com/public/static/images/wechat/images.jpg')); ?>"
                                                name="music_image" required title="请上传音乐图片或输入音乐图片URL地址　　">
                                            <p class="help-block">文件最大64KB，只支持JPG格式</p>
                                            <img data-tips-image  id="music_image_preimg"" src='<?php echo htmlentities((isset($vo['music_image']) && ($vo['music_image'] !== '')?$vo['music_image']:"http://sam.zhuzhouyike.com/public/static/images/wechat/images.jpg")); ?>' style="width:120px;height: auto;border-radius: 5px;margin-top: 5px;">
                                            <a onClick="GetUploadify(1,'music_image','wechat','music_image_call_back')"  class='btn btn-link'>上传图片</a>
                                        </div>
                                    </div>

                                    <div class="form-group" data-keys-type='video'>
                                        <label class="col-xs-2 control-label layui-form-label">视频标题</label>
                                        <div class="col-xs-8">
                                            <input class='form-control' style="height: auto;"value="<?php echo htmlentities((isset($vo['video_title']) && ($vo['video_title'] !== '')?$vo['video_title']:'视频标题')); ?>" name="video_title" required title="请输入视频标题">
                                        </div>
                                    </div>
                                <div class="form-group" data-keys-type='video'>
                                    <label class="col-xs-2 control-label layui-form-label label-required">上传视频</label>
                                    <div class="col-xs-8">
                                        <div class="input-group">
                                            <input class='form-control' type="text" id="video_url" value="<?php echo htmlentities((isset($vo['video_url']) && ($vo['video_url'] !== '')?$vo['video_url']:'')); ?>" name="video_url" required title="请上传音乐视频或输入音乐视频URL地址">
                                            <a onClick="GetUploadify(1,'video_url','wechat','video_url_call_back','Flash')" class="input-group-addon"><i class="fa fa-upload"></i></a>
                                        </div>
                                        <p class="help-block">文件最大10MB，只支持MP4格式</p>
                                    </div>
                                </div>
                                <div class="form-group" data-keys-type='video'>
                                    <label class="col-xs-2 control-label layui-form-label">视频描述</label>
                                    <div class="col-xs-8">
                                        <textarea name="video_desc" maxlength="50" class="form-control"><?php echo htmlentities((isset($vo['video_desc']) && ($vo['video_desc'] !== '')?$vo['video_desc']:'视频描述')); ?></textarea>
                                    </div>
                                </div>

                                <div class="text-center padding-bottom-10" style="position:absolute;bottom:0;width:100%;">
                                    <div class="hr-line-dashed" style="margin:10px 0"></div>
                                    <button class="layui-btn menu-submit" lay-submit lay-filter="ajaxSubmit">保存数据</button>
                                    <!--<?php if(!isset($vo['keys']) || !in_array($vo['keys'],['default','subscribe'])): ?>-->
                                    <button data-cancel-edit class="layui-btn layui-btn-danger" type='button'>取消编辑</button>
                                    <!--<?php endif; ?>-->
                                </div>


                                </fieldset>
                                </form>
                            </div>
                        </div>
                    </div>
              </div>
          </div>
          <script>  
                     /*! 刷新预览显示 */
                     function showReview(params){
                          $('#phone-preview').attr('src', '/index.php/admin/wechat/review.html?' + $.param(params ||{}));
                      }
                     // 图片回调  
                     function img_call_back(fileurl_tmp , elementid)
                            {
                                $("#"+elementid).val(fileurl_tmp);
                                $("#"+elementid+'_preimg').attr('src', fileurl_tmp);
                                showReview({type:'image', content:fileurl_tmp});
                            }
                    function music_url_call_back(fileurl_tmp , elementid){
                        $("#"+elementid).val(fileurl_tmp);
                    }
                    function video_url_call_back(fileurl_tmp , elementid){
                        $("#"+elementid).val(fileurl_tmp);
                        showReview({type:'video', url:fileurl_tmp});
                    }
                    //音乐图片回调 
                    function music_image_call_back(fileurl_tmp , elementid)
                    {
                        $("#"+elementid).val(fileurl_tmp);
                        $("#"+elementid+'_preimg').attr('src', fileurl_tmp)
                    } 
                            
                  $(function (){
              
                      var $body = $('body');
                      /*! 取消编辑 */
                      $('[data-cancel-edit]').on('click', function (){
                          var dialogIndex = $.msg.confirm('确定取消编辑吗？', function (){
                              history.back();
                              $.msg.close(dialogIndex);
                          });
                      });

                 
                      /*! 刷新预览显示 */
                      function showReview(params){
                          $('#phone-preview').attr('src', '/index.php/admin/wechat/review.html?' + $.param(params ||{}));
                      }
              
                      // 图文显示预览
                      $body.off('change', '[name="news_id"]').on('change', '[name="news_id"]', function (){
                          showReview({type:'news', content:this.value});
                      });
              
                      // 文字显示预览
                      $body.off('change', '[name="content"]').on('change', '[name="content"]', function (){
                          showReview({type:'text', content:this.value});
                      });
              
                      // 图片显示预览
                      $body.off('change', '[name="image_url"]').on('change', '[name="image_url"]', function (){
                          showReview({type:'image', content:this.value});
                      });
              
                      // 音乐显示预览
                      var musicSelector = '[name="music_url"],[name="music_title"],[name="music_desc"],[name="music_image"]';
                      $body.off('change', musicSelector).on('change', musicSelector, function (){
                          var params ={type:'music'}, $parent = $(this).parents('form');
                          params.title = $parent.find('[name="music_title"]').val();
                          params.url = $parent.find('[name="music_url"]').val();
                          params.image = $parent.find('[name="music_image"]').val();
                          params.desc = $parent.find('[name="music_desc"]').val();
                          showReview(params);
                      });
              
                      // 视频显示预览
                      var videoSelector = '[name="video_title"],[name="video_url"],[name="video_desc"]';
                      $body.off('change', videoSelector).on('change', videoSelector, function (){
                          var params ={type:'video'}, $parent = $(this).parents('form');
                          params.title = $parent.find('[name="video_title"]').val();
                          params.url = $parent.find('[name="video_url"]').val();
                          params.desc = $parent.find('[name="video_desc"]').val();
                          showReview(params);
                      });
              
                      /*! 默认类型事件 */
                      $body.off('click', 'input[name=type]').on('click', 'input[name=type]', function (){
                          var value = $(this).val(), $form = $(this).parents('form');
                          var $current = $form.find('[data-keys-type="' + value + '"]').removeClass('hide');
                          $form.find('[data-keys-type]').not($current).addClass('hide');
                          switch (value){
                              case 'news':
                                  return $('[name="news_id"]').trigger('change');
                              case 'text':
                                  return $('[name="content"]').trigger('change');
                              case 'image':
                                  return $('[name="image_url"]').trigger('change');
                              case 'video':
                                  return $('[name="video_url"]').trigger('change');
                              case 'music':
                                  return $('[name="music_url"]').trigger('change');
                              case 'voice':
                                  return $('[name="voice_url"]').trigger('change');
                          }
                      });
              
                      // 默认事件触发
                      $('input[name=type]:checked').map(function (){
                          $(this).trigger('click');
                      });
                  });
          </script>
  </body>