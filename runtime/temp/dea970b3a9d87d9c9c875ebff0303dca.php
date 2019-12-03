<?php /*a:2:{s:75:"D:\phpstudy\PHPTutorial\WWW\SamAdmin\application\admin\view\curd\index.html";i:1575379551;s:78:"D:\phpstudy\PHPTutorial\WWW\SamAdmin\application\admin\view\public\layout.html";i:1575380244;}*/ ?>
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
<link rel="stylesheet" href="/public/static/awesome/css/font-awesome.min.css">

<link rel="stylesheet" href="/public/static/css/bootstrap.css?v=1.0">
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
<script src="https://cdn.staticfile.org/twitter-bootstrap/3.3.7/js/bootstrap.min.js"></script>
<style>

    .modal-dialog {
        /* max-height: 800px; */
        min-width: 800px;
        /* overflow-y:scroll; */
    }
    /* * {
        -webkit-box-sizing:inherit!important;
        box-sizing: inherit!important
    } */
</style>
    <script type="text/javascript">
   
    $(function(){
          var form = layui.form;
            form.render();
      //监听提交
      form.on('submit(ajax_form_submit)', function(data){
        var url    = data.form.action;
        var method = data.form.method;
        var field  = data.field;
        $.ajax({
            url  : url,
            type : method,
            data : field,
            beforeSend:function(){
                load = layer.load(1); //0代表加载的风格，支持0-2
                $('.ajax_form_submit').addClass('layui-btn-disabled').removeAttr('lay-filter');
            },
            success:function(res){
                if(!res.code){
                    layer.msg(res.msg,{icon: 2,time: 1000})
                }else{
                    layer.msg(res.msg,{icon: 1,time: 1500},function () {
                           $('#myModal').modal('hide');
                            window.location.href = res.url;
                        })
                }
            },
            complete:function(){
                layer.close(load);
            },
            error:function(){
                layer.alert('服务器繁忙，请稍候');
            },
            
        });
        return false;
      });
    })
  
   
    $(function(){
        $('body').on('click', '[data-modal]', function () {
            return modal_iframe($(this).attr('data-modal'), 'open_type=modal', $(this).attr('data-title') || '编辑');
            });

        $('body').on('click', '[data-modal2]', function () {
            return modal($(this).attr('data-modal'), 'open_type=modal', $(this).attr('data-title') || '编辑');
            });
        $('body').on('click', '[data-tips-image]', function () {
            let content = $(this).attr('src');
            layer.open({
                type:1,
                title:false,
                closeBtn:1,
                skin:'layui-layer-nobg',
                shadeClose:true,
                content:'<img src='+content+'>',
                
            })
        });

        
    })
    function img_call_back(fileurl_tmp , elementid)
    {
     
        $("#"+elementid).val(fileurl_tmp);
        $("#"+elementid+'_pre').attr('href', fileurl_tmp);
        $("#"+elementid+'_preimg').attr('src', fileurl_tmp);
        // $("#"+elementid+'_preimg').attr('click', "layer.tips('<img src="+fileurl_tmp+">',this,{tips: [1, '#fff']});");
    } 
  function modal(url, data, title, callback, loading, tips){
    $.get(url,data,function(res){
      var layerIndex = layer.open({
        type:1,
        btn:false,
        area:'800px',
        title:title,
        content:res
      })
    })
  }


  

  function modal_iframe(url, data, title, callback, loading, tips){
    layer.open({
        type:2,
        btn:false,
        area:['750px','680px'],
        fixed:false,
        maxmin: true,
        moveOut:false,
        anim:5,
        resize:true,//是否允许拉伸
        title:title,
        content:url
      })
  }

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


<!-- 模态框（Modal） -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
					&times;
				</button>
				<h4 class="modal-title" id="myModalLabel">
					
				</h4>
			</div>
			<div class="modal-body">
				
			</div>
			
		</div><!-- /.modal-content -->
	</div><!-- /.modal -->
</div>

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
    .layui-form-label {
        width:120px!important;
    }
</style>
<body style="background-color: #FFF; overflow: auto;">
        <div class="layui-tab layui-tab-brief" lay-filter="docDemoTabBrief">
                <ul class="layui-tab-title">
                    <li class="layui-this"> 代码生成</li>
                    <li style="float:right;padding: 0;min-width: 35px;"><div title="刷新数据" class="pReload"><i class="fa fa-refresh"></i></div></li>
                    <li style="float:right;padding: 0;min-width: 35px;"><a  href="javascript:history.back();" title="返回列表"> <i class="fa  fa-arrow-left"></i></a></li>
                </ul>
        </div>
        <div class="layui-tab-content">
            <div class="layui-row">
                <form class="layui-form" action="<?php echo url('admin/curd/run'); ?>" method="post">
                    <div class="layui-form-item">
                            <label class="layui-form-label" for="link_name">从数据表生成：</label>
                            <div class="layui-input-inline">
                                <select class="select db-table">
                                        <option value="">不从数据表生成</option>
                                        <?php if(is_array($tables) || $tables instanceof \think\Collection || $tables instanceof \think\Paginator): if( count($tables)==0 ) : echo "" ;else: foreach($tables as $key=>$table): ?>
                                        <option value="<?php echo htmlentities($table['Name']); ?>" <?php echo app('request')->param('table')==$table['Name']?'selected' : ''; ?>><?php echo htmlentities($table['Name']); ?></option>
                                        <?php endforeach; endif; else: echo "" ;endif; ?>
                                </select> 
                            </div>
                            <button type="button" class="layui-btn  db-jump" title="点击此项选择从数据库生成字段">
                                    确认选择
                            </button>
                    </div>
                    <div class="layui-form-item">
                            <label class="layui-form-label" for="link_name">生成文件：</label>
                            <div class="layui-input-inline">
                                    <select name="file" class="select" required  lay-verify="required">
                                            <option value="all">默认生成文件（all）</option>
                                            <option value="controller">控制器（controller）</option>
                                            <option value="model">模型（model）</option>
                                            <option value="validate">验证器（validate）</option>
                                            <?php if(!app('request')->param('table')): ?>
                                                <option value="table">数据表（table）</option>
                                            <?php endif; ?>
                                            <option value="edit">编辑添加页（edit.html）</option>
                                            <option value="index">列表页（index.html）</option>
                                            <option value="recycleBin">回收站（recyclebin.html）</option>
                                            <option value="form">搜索框（form.html）</option>
                                            <option value="th">表格表头（th.html）</option>
                                            <option value="td">表格表体（td.html）</option>
                                            <option value="config">配置文件（config.php）</option>
                                            <option value="dir">目录（dir）</option>
                                    </select> 
                            </div>
                    </div>
                    <div class="layui-form-item">
                            <label class="layui-form-label" for="">模块：</label>
                            <div class="layui-input-inline">
                                <input type="text" class="layui-input" required  lay-verify="required" placeholder="默认为当前模块" name="module" datatype="/^[a-z]+$/" value="<?php echo htmlentities(app('request')->module()); ?>" title="默认为当前模块">
                            </div>
                    </div>
                    <div class="layui-form-item">
                            <label class="layui-form-label" for="">控制器：</label>
                            <div class="layui-input-inline">
                                <input type="text" class="layui-input" required  lay-verify="required" placeholder="字母，驼峰式"  name="controller" datatype="/^[a-z]+$/" value="" title="默认为当前模块">
                            </div>
                    </div>
                    <h2 style="font-size: 16px">表单信息 ：</h2>
                    <hr>
                    <table class="layui-table">
                            <thead>
                            <tr class="text-c">
                                <th width="110" rowspan="2" title="删除后不可恢复，谨慎操作" style="text-align: center;">
                                    操作<br>
                                    <a href="javascript:;" class="layui-btn layui-btn-xs op-add" data-type="form" data-header="1" style="margin-top: 5px"> <span class="fa fa-plus"></span> 增加一栏</a>
                                </th>
                                <th width="660" colspan="5" title="字段配置信息">字段</th>
                                <th width="90" rowspan="2" title="勾选后自动给字段添加排序功能">筛选排序</th>
                                <th width="90" rowspan="2" title="勾选后字段可直接编辑修改数据库">是否可编辑</th>
                                <th width="220" colspan="2" title="自动生成搜索项">搜索</th>
                                <th width="630" colspan="4" title="如何使用请看相应文档">layui 验证</th>
                            </tr>
                            <tr class="text-c">
                                <th width="120" title="中文描述，编辑页为对应label标签内容，首页对应表头内容"><span class="c-red">*</span> 标题</th>
                                <th width="120" title="一般为对应数据库字段的名称"><span class="c-red">*</span> 名称</th>
                                <th width="130" title="自动生成编辑页相应的表单控件"><span class="c-red">*</span> 类型</th>
                                <th width="170"
                                    title="只针对select,radio,checkbox控件,支持变量和配置值，例如 {foo}-对应conf.foo对应的配置项，生成foreach循环 | 1:值一#2:值二#3:值三 | 空值的默认标签名">
                                    选项值
                                </th>
                                <th width="120" title="字段编辑页默认值">默认值</th>
                                <th width="90" title="勾选后自动生成控制器筛选项和前端搜索框">表单搜索</th>
                                <th width="130" title="select的取值为字段中的选项值">搜索类型</th>
                          
                                <th width="180" title="layui的lay-verify配置项">lay-verify</th>
                            </tr>
                            </thead>
                            <tbody id="tbody-form">
                            <tr>
                                <td title="删除后不可恢复，谨慎操作">
                                    <a href="javascript:;" class="layui-btn layui-btn-xs  op-add" data-type="form">增加一栏</a>
                                    <a href="javascript:;" class="layui-btn  layui-btn-danger layui-btn-xs radius op-delete">删除</a>
                                </td>
                                <td title="中文描述，编辑页为对应label标签内容，首页对应表头内容">
                                    <input type="text" class="layui-input form-title" placeholder="中文描述" name="form[0][title]">
                                </td>
                                <td title="一般为对应数据库字段的名称">
                                    <input type="text" class="layui-input form-name" placeholder="字段，字母" name="form[0][name]">
                                </td>
                                <td title="自动生成编辑页相应的表单控件">
                                    <div class="select-box">
                                        <select class="select" name="form[0][type]">
                                            <option value="text">text</option>
                                            <option value="select">select</option>
                                            <option value="radio">radio</option>
                                            <option value="textarea">textarea</option>
                                            <option value="checkbox">checkbox</option>
                                            <option value="password">password</option>
                                            <option value="number">number</option>
                                            <option value="date">date</option>
                                        </select>
                                    </div>
                                </td>
                                <td title="只针对select,radio,checkbox控件,支持变量和配置值，例如 {foo}-对应conf.foo对应的配置项，生成foreach循环 | 1:值一#2:值二#3:值三 | 空值的默认标签名">
                                    <input type="text" class="layui-input" placeholder="变量或以#隔开" name="form[0][option]">
                                </td>
                                <td title="字段编辑页默认值">
                                    <input type="text" class="layui-input" placeholder="表单默认值" name="form[0][default]">
                                </td>
                                <td title="勾选后自动给字段添加排序功能">

                                        <input type="checkbox"  lay-skin="primary" name="form[0][sort]" value="1" title="排序">
                                    
                                </td>
                                <td title="勾选后字段可直接编辑">

                                    <input type="checkbox"  lay-skin="primary" name="form[0][edit]" value="1" title="可编辑">
                                
                                </td>
                                <td class="text-c" title="勾选后自动生成控制器筛选项和前端搜索框">
                                    <input type="checkbox"  lay-skin="primary" name="form[0][search]" value="1" title="搜索">
                                </td>
                                <td title="select的取值为字段中的选项值">
                                    <div class="select-box">
                                        <select class="select" name="form[0][search_type]">
                                            <option value="text">text</option>
                                            <option value="select">select</option>
                                            <option value="date">date</option>
                                        </select>
                                    </div>
                                </td>
                                
                                <td title="lay-verify配置项">
                                    <input type="text" class="layui-input form-validate-datatype" placeholder="required（必填项）phone（手机号）email（邮箱）url（网址）number（数字）date（日期）identity（身份证）" name="form[0][validate]">
                                </td>
                            </tr>
                            </tbody>
                    </table>
                  
                    <?php if(!app('request')->param('table')): ?>
                    <h2 style="font-size: 16px">数据表信息 ：</h2>
                    <hr>
                    <div class="layui-form-item">
                        <label class="layui-form-label">数据表：</label>
                        <div class="layui-input-inline">
                            <input type="checkbox"  name="create_table" value="1"  title="创建数据表" lay-skin="primary" >
                            <input type="checkbox" name="create_table_force" value="1"  title="强制建表" lay-skin="primary" >
                        </div>
                        <button class="layui-btn op-sync"  title="将表单元素里的字段自动填充到表字段里，会清空原表字段的数据，谨慎操作" >同步字段</button>
                    </div>

                    <div class="layui-form-item">
                        <label class="layui-form-label">表引擎：</label>
                        <div class="layui-input-inline">
                            <select name="table_engine">
                                    <option value="InnoDB">InnoDB</option>
                                    <option value="MyISAM">MyISAM</option>
                                    <option value="MRG_MYISAM">MRG_MYISAM</option>
                                    <option value="MEMORY">MEMORY</option>
                                    <option value="ARCHIVE">ARCHIVE</option>
                            </select>
                        </div>
                        <div class="layui-input-inline">
                            <input type="text" required lay-verify="require" class="layui-input" name="table_name" placeholder="表名，不填则默认为控制器名.勿带表前缀">    
                        </div>
                    </div>
                    <table class="layui-table">
                            <thead>
                            <tr class="text-c">
                                <th width="110" title="删除后不可恢复，谨慎操作" style="text-align: center">操作<br>
                                    <a href="javascript:;" style="margin-top: 5px" class="layui-btn layui-btn-xs  op-add" data-type="field" data-header="1"> <i class="fa fa-plus"></i> 增加一栏</a>
                                </th>
                                <th width="130" title="只能小写字符和下划线，例如 user_id"><span class="c-red">*</span> 名称</th>
                                <th width="130" title="字段类型+大小，例如 varchar(255) , int(10) , text"><span class="c-red">*</span> 类型</th>
                                <th width="130" title="为NULL表示不设默认值，不区分大小写">默认值</th>
                                <th width="90" title="勾选后生成 NOT NULL">不是 null</th>
                                <th width="90" title="勾选后生成索引">索引</th>
                                <th width="130" title="设置字段备注">备注</th>
                                <th width="130" title="扩展属性，例如 unsigned , auto_increment">扩展属性</th>
                            </tr>
                            </thead>
                            <tbody id="tbody-field">
                            <tr>
                                <td title="删除后不可恢复，谨慎操作">
                                    <a href="javascript:;" class="layui-btn layui-btn-xs op-add" data-type="field">增加一栏</a>
                                    <a href="javascript:;" class="layui-btn layui-btn-xs layui-btn-danger op-delete">删除</a>
                                </td>
                                <td title="只能小写字符和下划线，例如 user_id">
                                    <input type="text" class="layui-input field-name" placeholder="字段名称" name="field[0][name]">
                                </td>
                                <td title="字段类型+大小，例如 varchar(255) , int(10) , text">
                                    <input type="text" class="layui-input" placeholder="例如varchar(255)" value="varchar(255)" name="field[0][type]">
                                </td>
                                <td title="为NULL表示不设默认值，不区分大小写">
                                    <input type="text" class="layui-input" placeholder="为NULL表示不设默认值" name="field[0][default]"
                                           value="NULL">
                                </td>
                                <td title="勾选后生成 NOT NULL">
                                    <input type="checkbox"  lay-skin="primary"  name="field[0][not_null]" value="1" title="NOT NULL">
                                </td>
                                <td title="勾选后生成索引">
                                        <input type="checkbox"  lay-skin="primary" name="field[0][key]" value="1" title="生成索引">
                                </td>
                                <td title="设置字段备注">
                                    <input type="text" class="layui-input field-comment" placeholder="备注" name="field[0][comment]">
                                </td>
                                <td title="扩展属性，例如 unsigned , auto_increment">
                                    <input type="text" class="layui-input" placeholder="例如unsigned" name="field[0][extra]">
                                </td>
                            </tr>
                            </tbody>
                    </table>
                    <?php endif; ?>
                    <h2 style="font-size: 16px">其他选项 ：</h2>
                    <hr>
                    <div class="layui-form-item">
                        <label class="layui-form-label">创建模型：</label>
                        <div class="layui-input-inline">
                                <input type="checkbox" name="model" value="1"  title="创建模型" lay-skin="primary">
                                <input type="checkbox" name="auto_timestamp" value="1"  title="自动时间戳" lay-skin="primary"  title="会自动创建相应的模型，并且自动添加字段create_time，update_time，并且开启时间戳记录">
                        </div>
                    </div>
                    <div class="layui-form-item">
                            <label class="layui-form-label">验证器：</label>
                            <div class="layui-input-inline">
                                    <input type="checkbox" name="validate" value="1"  title="创建验证器" lay-skin="primary">
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
</body>
<script>
    var form = layui.form;
      // 获取模板
      var template = {}, index = {};
        template['form'] = $("#tbody-form").html();
        template['field'] = $("#tbody-field").html();
        index['form'] = 0;
        index['field'] = 0;

    $(document).on("click", ".db-jump", function () {
        location.href = "/admin/curd/index/table/" + $('.db-table').val();
        }).on("click",".op-add",function(){
            var type = $(this).attr("data-type");
            var html = template[type].replace(/(\[\d+\])/g, '[' + (++index[type]) + ']');
            // 表头菜单，追加到第一个
            if ($(this)[0].hasAttribute('data-header')) {
                $("#tbody-" + type).prepend(html);
            } else {
                $(this).closest('tr').after(html);
            }
            form.render()
        }).on("click",".op-sync",function(){
            var objField = $("#tbody-field");
            objField.find('tr').remove();
            $("#tbody-form").find('tr').each(function () {
                objField.append(template['field'].replace(/(\[\d+\])/g, '[' + (++index['field']) + ']'));
                var objCurrent = objField.find('tr:last');
                objCurrent.find('.field-comment').val($(this).find('.form-title').val());
                objCurrent.find('.field-name').val($(this).find('.form-name').val());
            });
            form.render()
        }).on("click", ".op-delete", function () {
            // 删除一栏
            $(this).closest("tr").fadeOut(undefined, undefined, function () {
                // 使用回调函数，强行移除该DOM
                $(this).remove();
            });
            form.render();
        })
        <?php if(isset($table_info)): ?>
            var tableInfo = <?php echo $table_info; ?>;
            var objForm = $("#tbody-form");
            objForm.find('tr').remove();
            for (var i = 0; i < tableInfo.length; i++) {
                objForm.append(template['form'].replace(/(\[\d+\])/g, '[' + (++index['form']) + ']'));
                var objCurrent = objForm.find('tr:last');
                objCurrent.find('.form-name').val(tableInfo[i]);
            }
        <?php endif; ?>
</script>