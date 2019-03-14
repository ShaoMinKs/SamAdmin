<?php /*a:2:{s:71:"/www/wwwroot/sam_zhuzhouyike_com/application/admin/view/user/index.html";i:1552013360;s:74:"/www/wwwroot/sam_zhuzhouyike_com/application/admin/view/public/layout.html";i:1551805875;}*/ ?>
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
<body style="background-color: rgb(255, 255, 255); overflow: auto; cursor: default; -moz-user-select: inherit;">
        <div class="layui-tab layui-tab-brief" lay-filter="docDemoTabBrief">
                <ul class="layui-tab-title">
                  <li class="layui-this">会员列表 <span style="font-size:12px;color:#777">(共 <span class="count"></span> 条记录)</span></li>
                  <li><a  href="<?php echo url('admin/user/add',['act'=>'add']); ?>">新增会员</a></li>
                  <li style="float:right"><div title="刷新数据" class="pReload"><i class="fa fa-refresh"></i></div></li>
                </ul>
                <div class="layui-tab-content">
                    <div class="page" style="padding-top:10px">
                        <div class="flexigrid">             
                              <div class="demoTable">
                                <form class="layui-form layui-form-pane" action=""  id="search_form" onsubmit="return false">
                                  <div class="layui-inline">
                                    <label class="layui-form-label">关键词</label>
                                    <div class="layui-input-inline">
                                        <input type="text" name="keywords" value="" id="name" placeholder="请输入标题或者关键字" class="layui-input" style="padding-left:8px">
                                    </div>
                                  </div>
                                  <div class="layui-inline">
                                    <button class="layui-btn" data-type="reload" id="search">搜索</button>
                                  </div>
                                </form>
                              </div>
                              <hr>	
                              <div>                   
                                  <table class="layui-hide" id="user" lay-filter="user"></table>
                              </div>	
                              <script type="text/html" id="barDemo">
                                  <a class="layui-btn layui-btn-xs" lay-event="edit"> <i class="fa fa-pencil-square-o"></i> 编辑</a>
                                  <a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="del"> <i class="fa fa-trash-o"></i> 删除</a>
                              </script>                        
                        </div>
                      </div>
                </div>
            </div>
    <script>
        $(document).ready(function(){
                layui.use('table', function(){
                var table = layui.table;
                var  form = layui.form;
              
            var tableIns = table.render({
                elem: '#user'
                ,url:"<?php echo url('admin/user/index'); ?>"
                ,toolbar: '#toolbarDemo'
                ,title: '用户数据表'
                ,cols: [[
                  {type: 'checkbox', fixed: 'left'}
                  ,{field:'user_id', title:'ID',  fixed: 'left',width:80, unresize: true, sort: true}
                  ,{field:'nickname', title:'昵称'}
                  ,{field:'email', title:'邮箱',  edit: 'text',align:'center'}
                  ,{field:'reg_time', title:'注册时间',align:'center',width:120,sort: true}
                  ,{field:'last_login', title:'最后登陆时间',align:'center',width:120,sort: true}
                  ,{field:'mobile', title:'手机',align:'center'}
                  ,{fixed: 'right', title:'操作', toolbar: '#barDemo'}
                ]]
                ,page: true
                ,done:function(res,curr,count){
                    $('.count').text(res.count)
                }
              });
            
            form.on('checkbox(is_show)', function(obj){
                var checked = obj.elem.checked ? 1 : 0;
                changeTableValue('link','link_id',$(this).data('id'),this.name,checked);
              });
            
              $('#search').on('click',function(){
                  var data = $('#search_form').serializeObject();
                  tableIns.reload({
                    where:data
                    ,page: {
                        curr: 1 //重新从第 1 页开始
                    }
                    });
              })
            
              $.fn.serializeObject = function() {  
                    var o = {};  
                    var a = this.serializeArray();  
                    $.each(a, function() {  
                        if (o[this.name]) {  
                            if (!o[this.name].push) {  
                                o[this.name] = [ o[this.name] ];  
                            }  
                            o[this.name].push(this.value || '');  
                        } else {  
                            o[this.name] = this.value || '';  
                        }  
                    });  
                    return o;  
                }  
            

            
                //监听单元格编辑
             table.on('edit(user)', function(obj){
                var value = obj.value //得到修改后的值
                ,data     = obj.data //得到所在行所有键值
                ,field    = obj.field; //得到字段
                changeTableValue('users','user_id',data.user_id,field,value);
              });
            
                 
              //监听行工具事件
              table.on('tool(user)', function(obj){
                var data = obj.data;
                //console.log(obj)
                if(obj.event === 'del'){
                  layer.confirm('确定删除么', function(index){
                    $.post("<?php echo url('admin/user/userHandle'); ?>",{user_id:data.user_id,'act':'del','auth_code':"<?php echo htmlentities(app('config')->get('AUTH_CODE')); ?>"},function(res){
                      if(res.code == 1){
                        layer.msg(res.msg,{icon: 1,time: 1000})
                        obj.del();
                        layer.close(index);
                      }else{
                        layer.msg(res.msg,{icon: 2,time: 1000})
                      }
                    });
                  });
                } else if(obj.event === 'edit'){
                    var id  = data.user_id;
                    window.location.href = "/admin/user/add/act/edit/user_id/"+id;
                }
              });
            });
                    // 表格行点击选中切换
                    $('#flexigrid > table>tbody >tr').click(function(){
                        $(this).toggleClass('trSelected');
                    });
            
                    // 点击刷新数据
                    $('.fa-refresh').click(function(){
                        location.href = location.href;
                    });
            
                });
            
            
                function delfun(obj) {
                    // 删除按钮
                    layer.confirm('确认删除？', {
                        btn: ['确定', '取消'] //按钮
                    }, function () {
                        $.ajax({
                            type: 'post',
                            url: $(obj).attr('data-url'),
                            data : {act:'del',admin_id:$(obj).attr('data-id')},
                            dataType: 'json',
                            success: function (data) {
                                if (data.status == 1) {
                                    layer.msg(data.msg,{icon: 1,time: 1000},function () {
                                        $(obj).parent().parent().parent().remove();
                                    })
                                } else {
                                    layer.msg(data.msg,{icon: 2,time: 2000})
                                }
                            }
                        })
                    }, function () {
                    });
                }
            </script>
</body>
</html>