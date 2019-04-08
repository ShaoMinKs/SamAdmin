<?php /*a:2:{s:74:"/www/wwwroot/sam_zhuzhouyike_com/application/admin/view/wechat/weTags.html";i:1551860206;s:74:"/www/wwwroot/sam_zhuzhouyike_com/application/admin/view/public/layout.html";i:1551805875;}*/ ?>
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
        <div class="layui-tab layui-tab-brief" lay-filter="docDemoTabBrief">
            <ul class="layui-tab-title">
                <li class="layui-this"> <a href="<?php echo url('admin/wechat/weTags'); ?>">微信标签管理</a> </li>
                <div class="pull-right margin-right-15 nowrap">
                    <button data-modal="<?php echo url('tagsAdd'); ?>" data-title="添加标签" class='layui-btn layui-btn-sm'> <i class="fa fa-plus"></i> 添加标签</button>
                    <button data-load="<?php echo url('sync'); ?>" class='layui-btn layui-btn-sm layui-btn-primary'>远程获取标签</button>            
                </div>    
            </ul>
        </div> 
        <!-- 表单搜索 开始 -->
        <form autocomplete="off" class="layui-form layui-form-pane form-search" action="<?php echo request()->url(); ?>"  method="get">
            <div class="layui-form-item layui-inline">
                <label class="layui-form-label">标 签</label>
                <div class="layui-input-inline">
                    <input type="text" name="name" value="<?php echo htmlentities((app('request')->get('name') ?: '')); ?>" placeholder="请输入标签" class="layui-input">
                </div>
            </div>
            <div class="layui-form-item layui-inline">
                <button class="layui-btn layui-btn-primary"><i class="layui-icon">&#xe615;</i> 搜 索</button>
            </div>
        </form>
        <!-- 表单搜索 结束 -->
        <form onsubmit="return false;" data-auto="true" method="post">
            <?php if(empty($list)): ?>
            <p class="help-block text-center well">没 有 记 录 哦！</p>
            <?php else: ?>
            <input type="hidden" value="resort" name="action"/>
            <table class="layui-table" lay-skin="line">
                <thead>
                <tr>
                    <th class='list-table-check-td think-checkbox'>
                        <input data-auto-none="" data-check-target='.list-check-box' type='checkbox'/>
                    </th>
                    <th class='text-center'>ID</th>
                    <th class='text-left'>标签名称</th>
                    <th class='text-left'>标签类型</th>
                    <th class='text-right'>粉丝数</th>
                    <th class='text-center'>操作</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach($list as $key=>$vo): ?>
                <tr>
                    <td class='list-table-check-td think-checkbox'>
                        <input class="list-check-box" value='<?php echo htmlentities($vo['id']); ?>' type='checkbox'>
                    </td>
                    <td class='text-center'><?php echo htmlentities((isset($vo['id']) && ($vo['id'] !== '')?$vo['id']:'0')); ?></td>
                    <td class='text-left'><?php echo htmlentities((isset($vo['name']) && ($vo['name'] !== '')?$vo['name']:'')); ?></td>
                    <td class='text-left'><?php echo $vo['id']<100 ? "系统标签"  :  "自定义标签"; ?></td>
                    <td class='text-right'><?php echo htmlentities((isset($vo['count']) && ($vo['count'] !== '')?$vo['count']:'')); ?></td>
                    <td class='text-center nowrap'>
                        <?php if($vo['id'] < 100): else: ?>
                            <span class="text-explode">|</span>
                            <a data-modal='<?php echo url("admin/wechat/tagEdit"); ?>?id=<?php echo htmlentities($vo['id']); ?>' data-title="编辑标签">编辑</a>        
                            <span class="text-explode">|</span>
                            <a data-update="<?php echo htmlentities($vo['id']); ?>" data-field='delete' data-action='<?php echo url("tagDel"); ?>'>删除</a>
                        <?php endif; ?>   
                    </td>
                </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
            <?php if(isset($page)): ?><p><?php echo $page; ?></p><?php endif; ?>
            <?php endif; ?>
        </form>
    </div>
</body>