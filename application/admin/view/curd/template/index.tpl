

{include file="public/layout" /}
<body style="background-color: rgb(255, 255, 255); overflow: auto; cursor: default; -moz-user-select: inherit;">
        <div class="layui-tab layui-tab-brief" lay-filter="docDemoTabBrief">
                <ul class="layui-tab-title">
                  <li class="layui-this">列表 <span style="font-size:12px;color:#777">(共 <span class="count"></span> 条记录)</span></li>
                  <li><a  href="{:url('[MODULE]/[CONTROLLER]/add',['act'=>'add'])}">新增</a></li>
                  <li style="float:right"><div title="刷新数据" class="pReload"><i class="fa fa-refresh"></i></div></li>
                </ul>
                <div class="layui-tab-content">
                    <div class="page" style="padding-top:10px">
                        <div class="flexigrid">             
                              <div class="demoTable">
                                [FORM]
                              </div>
                              <hr>	
                              <div>                   
                                  <table class="layui-hide" id="[CONTROLLER]" lay-filter="[CONTROLLER]"></table>
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
                elem: '#[CONTROLLER]'
                ,url:"{:url('[MODULE]/[CONTROLLER]/index')}"
                ,toolbar: '#toolbarDemo'
                ,title: '用户数据表'
                ,cols: [[LAYUICOL]]
                ,page: true
                ,done:function(res,curr,count){
                    $('.count').text(res.count)
                }
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
             table.on('edit([CONTROLLER])', function(obj){
                var value = obj.value //得到修改后的值
                ,data     = obj.data //得到所在行所有键值
                ,field    = obj.field; //得到字段
                changeTableValue('[TABLE]','id',data.id,field,value);
              });
            
                 
              //监听行工具事件
              table.on('tool([CONTROLLER])', function(obj){
                var data = obj.data;
                //console.log(obj)
                if(obj.event === 'del'){
                  layer.confirm('确定删除么', function(index){
                    $.post("{:url('[MODULE]/[CONTROLLER]/Handle')}",{id:data.id,'act':'del','auth_code':"{$Think.config.AUTH_CODE}"},function(res){
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
                    var id  = data.id;
                    window.location.href = "/[MODULE]/[CONTROLLER]/add/act/edit/id/"+id;
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


