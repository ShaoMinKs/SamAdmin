<?php /*a:2:{s:77:"/www/wwwroot/sam_zhuzhouyike_com/application/admin/view/system/site_info.html";i:1553709771;s:74:"/www/wwwroot/sam_zhuzhouyike_com/application/admin/view/public/layout.html";i:1551805875;}*/ ?>
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
</style>
<body style="background-color: #FFF; overflow: auto;">
<div id="append_parent"></div>
<div id="ajaxwaitid"></div>
<div class="page">
    <div class="fixed-bar">
        <div class="item-title">
            <div class="subject">
                <h3>站点设置</h3>
                <h5>网站全局内容基本选项设置</h5>
            </div>
            <ul class="tab-base nc-row">
                <?php if(is_array($group_list) || $group_list instanceof \think\Collection || $group_list instanceof \think\Paginator): if( count($group_list)==0 ) : echo "" ;else: foreach($group_list as $k=>$v): ?>
                    <li><a href="<?php echo url('System/index',['inc_type'=> $k]); ?>" <?php if($k==$inc_type): ?>class="current"<?php endif; ?>><span><?php echo htmlentities($v); ?></span></a></li>
                <?php endforeach; endif; else: echo "" ;endif; ?>
            </ul>
        </div>
    </div>
        <div class="layui-row">
                <form action="<?php echo url('System/handle'); ?>" enctype="multipart/form-data" method="post" id="handlepost" class=" layui-anim-up layui-form">
                    <div class="layui-form-item">
                        <label class="layui-form-label" for="record_no">网站备案号</label>
                        <div class="layui-input-inline">
                            <input type="text" name="record_no"  id="record_no" value="<?php echo htmlentities($config['record_no']); ?>" required  lay-verify="required" placeholder="请输入网站备案号" autocomplete="off" class="layui-input">     
                        </div>
                        <div class="layui-form-mid layui-word-aux">网站备案号，将显示在首页底部等位置</div>
                    </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label" for="store_name">网站名称</label>
                            <div class="layui-input-inline">
                                <input type="text" name="store_name"  id="store_name" value="<?php echo htmlentities($config['store_name']); ?>" required  lay-verify="required" placeholder="请输入网站名称" autocomplete="off" class="layui-input">
                            </div>
                            <div class="layui-form-mid layui-word-aux">网站名称，将显示在首页底部等位置</div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label" for="store_logo">网站LOGO</label>
                            <div class="layui-input-inline">
                                <input type="text" name="store_logo"  id="store_logo" value="<?php echo htmlentities((isset($config['store_logo']) && ($config['store_logo'] !== '')?$config['store_logo']:'/public/static/images/logo/pc_home_logo_default.png')); ?>" onClick="GetUploadify(1,'store_logo','logo','img_call_back')" readonly required  lay-verify="required" placeholder="请上传网站logo" autocomplete="off" class="layui-input">
                                <span class="show">
                                    <a id="store_logo_a" target="_blank" class="nyroModal" rel="gal" href="<?php echo htmlentities((isset($config['store_logo']) && ($config['store_logo'] !== '')?$config['store_logo']:'/public/static/images/logo/pc_home_logo_default.png')); ?>">
                                        <img  id="store_logo_i" style="width:150px;height:60px;border:2px solid #e5e5e5;border-radius:5px;margin-top:5px" src="<?php echo htmlentities((isset($config['store_logo']) && ($config['store_logo'] !== '')?$config['store_logo']:'/public/static/images/logo/pc_home_logo_default.png')); ?>" alt="LOGO" onmouseover="layer.tips('<img  src=<?php echo htmlentities((isset($config['store_logo']) && ($config['store_logo'] !== '')?$config['store_logo']:'/public/static/images/logo/pc_home_logo_default.png')); ?>>',this,{tips: [1, '#fff']});" onmouseout="layer.closeAll();">
                                        
                                    </a>
                                </span>
                            </div>
                            <div class="layui-input-inline">
                                <input type="button" class="layui-btn layui-btn-normal"  onClick="GetUploadify(1,'store_logo','logo','img_call_back')" value="上传">
                            </div>
                            <div class="layui-form-mid layui-word-aux" style="margin-left:-200px">网站LOGO,最佳显示尺寸为230*58像素</div>
                        </div>

                        <div class="layui-form-item">
                            <label class="layui-form-label" for="admin_home_logo">后台管理中心LOGO</label>
                            <div class="layui-input-inline">
                                <input type="text" name="admin_home_logo"  id="admin_home_logo" value="<?php echo htmlentities((isset($config['store_logo']) && ($config['store_logo'] !== '')?$config['store_logo']:'/public/static/images/logo/pc_home_logo_default.png')); ?>" onClick="GetUploadify(1,'store_logo','logo','img_call_back')" readonly required  lay-verify="required" placeholder="请上传logo" autocomplete="off" class="layui-input">
                                <span class="show">
                                    <a id="admin_home_logo_a" target="_blank" class="nyroModal" rel="gal" href="<?php echo htmlentities((isset($config['admin_home_logo']) && ($config['admin_home_logo'] !== '')?$config['admin_home_logo']:'/public/static/images/logo/admin_home_logo_default.png')); ?>">
                                    <img  id="admin_home_logo_i" style="width:150px;height:60px;border:2px solid #e5e5e5;border-radius:5px;margin-top:5px" src="<?php echo htmlentities((isset($config['admin_home_logo']) && ($config['admin_home_logo'] !== '')?$config['admin_home_logo']:'/public/static/images/logo/admin_home_logo_default.png')); ?>" alt="LOGO" onmouseover="layer.tips('<img src=<?php echo htmlentities((isset($config['admin_home_logo']) && ($config['admin_home_logo'] !== '')?$config['admin_home_logo']:'/public/static/images/logo/admin_home_logo_default.png')); ?>>',this,{tips: [1, '#fff']});" onmouseout="layer.closeAll();">     
                                    </a>
                                </span>
                            </div>
                            <div class="layui-input-inline">
                                <input type="button" class="layui-btn layui-btn-normal"  onClick="GetUploadify(1,'admin_home_logo','logo','img_call_back')" value="上传">
                            </div>
                            <div class="layui-form-mid layui-word-aux" style="margin-left:-200px">后台管理中心LOGO,显示在后台左上角</div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label" for="contact">联系人</label>
                            <div class="layui-input-inline">
                                <input type="text" name="contact"  id="contact" value="<?php echo htmlentities($config['contact']); ?>" required  lay-verify="required" placeholder="请输入联系人" autocomplete="off" class="layui-input">     
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label" for="store_keyword">网站关键字</label>
                            <div class="layui-input-inline">
                                <input type="text" name="store_keyword"  id="store_keyword" value="<?php echo htmlentities($config['store_keyword']); ?>" required  lay-verify="required" placeholder="请输入网站关键字" autocomplete="off" class="layui-input">     
                            </div>
                            <div class="layui-form-mid layui-word-aux">网站关键字，便于SEO</div>
                        </div>
                        
                        <div class="layui-form-item">
                            <label class="layui-form-label" for="phone">联系电话</label>
                            <div class="layui-input-inline">
                                <input type="text" name="phone"  id="phone" value="<?php echo htmlentities($config['phone']); ?>" required  lay-verify="required" placeholder="请输入联系电话" autocomplete="off" class="layui-input">     
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label" for="mobile">联系手机</label>
                            <div class="layui-input-inline">
                                <input type="text" name="mobile"  id="mobile" value="<?php echo htmlentities($config['mobile']); ?>" required  lay-verify="required" placeholder="请输入联系手机" autocomplete="off" class="layui-input">     
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label" for="address">所在地区</label>
                            <div class="layui-input-inline select">
                                <select onchange="get_city(this);" id="province" name="province"  lay-ignore class="layui-select" style="width:150px"> 
                                    <option  value="0">选择省份</option>
                                    <?php if(is_array($province) || $province instanceof \think\Collection || $province instanceof \think\Paginator): $i = 0; $__LIST__ = $province;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
                                        <option value="<?php echo htmlentities($vo['id']); ?>" <?php if($vo['id'] == $config['province']): ?>selected<?php endif; ?> ><?php echo htmlentities($vo['name']); ?></option>
                                    <?php endforeach; endif; else: echo "" ;endif; ?>
                                </select>    
                            </div>
                                <div class="layui-input-inline select" style="width:150px">
                                <select onchange="get_area(this);" id="city" name="city" lay-ignore class="layui-select" style="width:150px">
                                    <option value="0">选择城市</option>
                                    <?php if(is_array($city) || $city instanceof \think\Collection || $city instanceof \think\Paginator): $i = 0; $__LIST__ = $city;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
                                        <option value="<?php echo htmlentities($vo['id']); ?>" <?php if($vo['id'] == $config['city']): ?>selected<?php endif; ?> ><?php echo htmlentities($vo['name']); ?></option>
                                    <?php endforeach; endif; else: echo "" ;endif; ?>
                                </select>  
                            </div>
                            <div class="layui-input-inline select" style="width:150px">
                                <select id="district" name="district" lay-ignore class="layui-select" style="width:150px">
                                    <option value="0">选择区域</option>
                                    <?php if(is_array($area) || $area instanceof \think\Collection || $area instanceof \think\Paginator): $i = 0; $__LIST__ = $area;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
                                        <option value="<?php echo htmlentities($vo['id']); ?>" <?php if($vo['id'] == $config['district']): ?>selected<?php endif; ?> ><?php echo htmlentities($vo['name']); ?></option>
                                    <?php endforeach; endif; else: echo "" ;endif; ?>
                                </select> 
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label" for="address">详细地址</label>
                            <div class="layui-input-inline">
                                <input type="text" name="address"  id="address" value="<?php echo htmlentities($config['address']); ?>" required  lay-verify="required" placeholder="请输入详细地址" autocomplete="off" class="layui-input" >     
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label" for="qq">客服QQ</label>
                            <div class="layui-input-inline">
                                <input type="text" name="qq"  id="qq" value="<?php echo htmlentities($config['qq']); ?>" required  lay-verify="required" placeholder="请输入客服qq" autocomplete="off" class="layui-input" >     
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
    //网站图标
    function img_call_back(fileurl_tmp , elementid)
    {
        
        $("#"+elementid).val(fileurl_tmp);
        $("#"+elementid+'_a').attr('href', fileurl_tmp);
        $("#"+elementid+'_i').attr('src', fileurl_tmp);
        $("#"+elementid+'_i').attr('onmouseover', "layer.tips('<img src="+fileurl_tmp+">',this,{tips: [1, '#fff']});");
    }
    //网站用户中心logo
    function user_img_call_back(fileurl_tmp)
    {
        $("#store_user_logo").val(fileurl_tmp);
        $("#userimg_a").attr('href', fileurl_tmp);
        $("#userimg_i").attr('onmouseover', "layer.tips('<img src="+fileurl_tmp+">',this,{tips: [1, '#fff']});");
    }
    //网站图标
    function store_ico_call_back(fileurl_tmp)
    {
        $("#store_ico").val(fileurl_tmp);
        $("#storeico_a").attr('href', fileurl_tmp);
        $("#storeico_i").attr('onmouseover', "layer.tips('<img src="+fileurl_tmp+">',this,{tips: [1, '#fff']});");
    }
   
</script>
</html>