<?php /*a:2:{s:72:"/www/wwwroot/sam_zhuzhouyike_com/application/admin/view/wechat/keys.html";i:1551891096;s:74:"/www/wwwroot/sam_zhuzhouyike_com/application/admin/view/public/layout.html";i:1551805875;}*/ ?>
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
    a {
        color:#333
    }
</style>
<body>
    <div class="wrapper wrapper-content  layui-anim layui-anim-up" style="padding:0 10px">
        <!-- <div class="layui-header notselect"  style="border-bottom:1px solid #e7eaec;height: 40px;">
            <div class="pull-left"><span style="color:#333">微信关键字管理</span></div>
            <div class="pull-right margin-right-15 nowrap">
                    <a href="<?php echo url('add'); ?>" class='layui-btn layui-btn-sm layui-btn-primary'>添加规则</a>
                    <button data-update data-field='delete' data-action='<?php echo url("home/wechats/del"); ?>' class='layui-btn layui-btn-sm layui-btn-primary'>删除规则</button>            
            </div>
        </div>       -->
        <div class="layui-tab layui-tab-brief" lay-filter="docDemoTabBrief">
            <ul class="layui-tab-title">
                <li class="layui-this"> <a href="<?php echo url('admin/wechat/keys'); ?>">微信关键字管理</a> </li>
                <li> <a href="<?php echo url('admin/wechat/keysEdit',['act'=>'add']); ?>">添加关键字</a> </li>
                <!-- <div class="pull-right margin-right-15 nowrap">
                    <a href="<?php echo url('admin/wechat/keysEdit',['act'=>'add']); ?>" class='layui-btn layui-btn-sm layui-btn-primary'>添加规则</a>
                    <button data-update data-field='delete' data-action="<?php echo url('admin/wechat/keysEdit',['act'=>'del']); ?>" class='layui-btn layui-btn-sm layui-btn-primary'>删除规则</button>            
                </div>     -->
            </ul>
        </div> 
    <form onsubmit="return false;" data-auto="true" action="<?php echo request()->url(); ?>" method="post">
            <?php if(count($list) == 0): ?>
            <p class="help-block text-center well">没 有 记 录 哦！</p>
            <?php else: ?>
            <input type="hidden" value="resort" name="action"/>
            <table class="layui-table" lay-skin="line">
                <thead>
                <tr>
                    <th class='list-table-check-td think-checkbox'>
                        <input data-auto-none="" data-check-target='.list-check-box' type='checkbox'/>
                    </th>
                    <th class='list-table-sort-td'>
                        <button type="submit" class="layui-btn layui-btn-normal layui-btn-xs">排 序</button>
                    </th>
                    <th class="text-left nowrap">关键字</th>
                    <th class="text-left nowrap">类型</th>
                    <th class="text-left nowrap">预览</th>
                    <th class="text-left nowrap">添加时间</th>
                    <th class="text-left nowrap">状态</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
             
                <?php foreach($list as $key=>$vo): ?>
                <tr>
                    <td class='list-table-check-td think-checkbox'>
                        <input class="list-check-box" value='<?php echo htmlentities($vo['id']); ?>' type='checkbox'/>
                    </td>
                    <td class='list-table-sort-td'>
                        <input name="_<?php echo htmlentities($vo['id']); ?>" value="<?php echo htmlentities($vo['sort']); ?>" class="list-sort-input"/>
                    </td>
                    <td class="text-left nowrap">

                        <?php echo htmlentities($vo['keys']); ?>
                    </td>
                    <td class="text-left nowrap"><?php echo htmlentities($vo['type']); ?></td>
                    <td class="text-left nowrap">
                        <?php if($vo['type'] == 'music'): ?>
                        <a data-phone-view='<?php echo url("admin/wechat/review"); ?>?type=music&title=<?php echo htmlentities(urlencode($vo['music_title'])); ?>&desc=<?php echo htmlentities(urlencode($vo['music_desc'])); ?>'>预览 <i class="fa fa-eye"></i></a>
                        <?php elseif($vo['type'] == 'text'): ?>
                        <a data-phone-view='<?php echo url("admin/wechat/review"); ?>?type=text&content=<?php echo htmlentities(urlencode($vo['content'])); ?>'>预览 <i class="fa fa-eye"></i></a>
                        <?php elseif($vo['type'] == 'image'): ?>
                        <a data-phone-view='<?php echo url("admin/wechat/review"); ?>?type=image&content=<?php echo htmlentities(urlencode($vo['image_url'])); ?>'>预览 <i class="fa fa-eye"></i></a>
                        <?php elseif($vo['type'] == 'news'): ?>
                        <a data-phone-view='<?php echo url("admin/wechat/review"); ?>?type=news&content=<?php echo htmlentities($vo['news_id']); ?>'>预览 <i class="fa fa-eye"></i></a>
                        <?php elseif($vo['type'] == 'video'): ?>
                        <a data-phone-view='<?php echo url("admin/wechat/review"); ?>?type=video&title=<?php echo htmlentities(urlencode($vo['video_title'])); ?>&desc=<?php echo htmlentities(urlencode($vo['video_desc'])); ?>&url=<?php echo htmlentities(urlencode($vo['video_url'])); ?>'>预览 <i class="fa fa-eye"></i></a>
                        <?php else: ?>
                            <?php echo htmlentities($vo['content']); ?>
                        <?php endif; ?>
                    </td>
                    <td class="text-left nowrap"><?php echo htmlentities($vo['create_at']); ?></td>
                    <td class='text-left nowrap'>
                        <?php if($vo['status'] == 0): ?><span class="color-desc">已禁用</span><?php elseif($vo['status'] == 1): ?><span class="color-green">使用中</span><?php endif; ?>
                    </td>
                    <td class='text-left nowrap'>
        
                        <span class="text-explode">|</span>
                        <a data-open='<?php echo url("edit"); ?>?id=<?php echo htmlentities($vo['id']); ?>' href="<?php echo url('keysEdit',['id'=>$vo['id'],'act'=>'edit']); ?>">编辑</a>

        
                        <?php if($vo['status'] == 1): ?>
                        <span class="text-explode">|</span>
                        <a data-update="<?php echo htmlentities($vo['id']); ?>" data-field='status' data-value='0' data-action='<?php echo url("admin/wechat/forbid"); ?>'>禁用</a>
                        <?php elseif(1==1): ?>
                        <span class="text-explode">|</span>
                        <a data-update="<?php echo htmlentities($vo['id']); ?>" data-field='status' data-value='1' data-action='<?php echo url("admin/wechat/forbid"); ?>'>启用</a>
                        <?php endif; ?>
        
                  
                        <span class="text-explode">|</span>
                        <a data-update="<?php echo htmlentities($vo['id']); ?>" data-field='delete'  data-action="<?php echo url('admin/wechat/keysHandle',['id'=>$vo['id'],'act'=>'del']); ?>">删除</a>
                  
        
                    </td>
                </tr>
            <?php endforeach; ?>
                </tbody>
            </table>
            <?php echo $list; ?>
        <?php endif; ?>
        </form>
        </div>
<script>
    
$(function () {
    /**
     * 默认类型事件
     * @type String
     */
    $('body').off('change', 'select[name=type]').on('change', 'select[name=type]', function () {
        var value = $(this).val(), $form = $(this).parents('form');
        var $current = $form.find('[data-keys-type="' + value + '"]').removeClass('hide');
        $form.find('[data-keys-type]').not($current).addClass('hide');
        switch (value) {
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

    function showReview(params) {
        params = params || {};
        $('#phone-preview').attr('src', '{"@wechat/review"|app_url}&' + $.param(params));
    }

    // 图文显示预览
    $('body').off('change', '[name="news_id"]').on('change', '[name="news_id"]', function () {
        showReview({type: 'news', content: this.value});
    });
    // 文字显示预览
    $('body').off('change', '[name="content"]').on('change', '[name="content"]', function () {
        showReview({type: 'text', content: this.value});
    });
    // 图片显示预览
    $('body').off('change', '[name="image_url"]').on('change', '[name="image_url"]', function () {
        showReview({type: 'image', content: this.value});
    });
    // 音乐显示预览
    var musicSelector = '[name="music_url"],[name="music_title"],[name="music_desc"],[name="music_image"]';
    $('body').off('change', musicSelector).on('change', musicSelector, function () {
        var params = {type: 'music'}, $parent = $(this).parents('form');
        params.title = $parent.find('[name="music_title"]').val();
        params.url = $parent.find('[name="music_url"]').val();
        params.image = $parent.find('[name="music_image"]').val();
        params.desc = $parent.find('[name="music_desc"]').val();
        showReview(params);
    });
    // 视频显示预览
    var videoSelector = '[name="video_title"],[name="video_url"],[name="video_desc"]';
    $('body').off('change', videoSelector).on('change', videoSelector, function () {
        var params = {type: 'video'}, $parent = $(this).parents('form');
        params.title = $parent.find('[name="video_title"]').val();
        params.url = $parent.find('[name="video_url"]').val();
        params.desc = $parent.find('[name="video_desc"]').val();
        showReview(params);
    });

    // 默认事件触发
    $('select[name=type]').map(function () {
        $(this).trigger('change');
    });

    /*! 删除关键字 */
    $('[data-delete]').on('click', function () {
        var id = this.getAttribute('data-delete');
        var url = this.getAttribute('data-action');
        var dialogIndex = $.msg.confirm('确定要删除这条记录吗？', function () {
            $.form.load(url, {id: id}, 'post', function (ret) {
                if (ret.code === "SUCCESS") {
                    window.location.reload();
                }
                $.msg.close(dialogIndex);
            });
        })
    });
});
</script>
</body>