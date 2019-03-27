

{include file="public/layout" /}
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
            <li> <a href="{:url('[MODULE]/[CONTROLLER]/index')}">列表</a> </li>
            <li class="layui-this"><a  href="{:url('[MODULE]/[CONTROLLER]/add')}">{$info.id ? '编辑' : '新增'}</a></li>
            <li style="float:right;padding: 0;min-width: 35px;"><div title="刷新数据" class="pReload"><i class="fa fa-refresh"></i></div></li>
            <li style="float:right;padding: 0;min-width: 35px;"><a  href="javascript:history.back();" title="返回列表"> <i class="fa  fa-arrow-left"></i></a></li>
        </ul>
    </div>
    <div class="layui-tab-content">
                <div class="layui-row">
                    <form class="layui-form" action="{:url('[MODULE]/[CONTROLLER]/Handle')}" method="post">
                        <input type="hidden" name="act" id="act" value="{$info.id ? 'edit' : 'add'}">
                        <input type="hidden" name="id" value="{$info.id|default=''}">
                        <input type="hidden" name="auth_code" value="{$Think.config.AUTH_CODE}"/>
                        {:token()}
[ROWS]
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
        //监听select
      });
 
</script>


</body>
</html>

