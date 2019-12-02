/**
* ajax 提交表单 到后台去验证然后回到前台提示错误
* 验证通过后,再通过 form 自动提交
*/
before_request = 1; // 标识上一次ajax 请求有没回来, 没有回来不再进行下一次
function ajax_submit_form(form_id,submit_url){

         if(before_request == 0)
            return false;
	$("[id^='err_']").hide();  // 隐藏提示
    $.ajax({
                type : "POST",
                url  : submit_url,
                data : $('#'+form_id).serialize(),// 你的formid
                error: function(request) {
                        alert("服务器繁忙, 请联系管理员!");
                },
                success: function(v) {
                    before_request = 1; // 标识ajax 请求已经返回
                    var v =  eval('('+v+')');
                        // 验证成功提交表单
                    if(v.hasOwnProperty('status'))
                    {
                        //layer.alert(v.msg);
                        if(v.status == 1)
                        {
                            layer.msg(v.msg, {
                                icon: 1,   // 成功图标
                                time: 2000 //2秒关闭（如果不配置，默认是3秒）
                            });
                            if(v.hasOwnProperty('data')){
                                if(v.data.hasOwnProperty('url')){
                                    location.href = v.data.url;
                                }else{
                                    location.href = location.href;
                                }
                            }else{
                                location.href = location.href;
                            }
                            return true;
                        }
                        if(v.status <= 0)
                        {
                            layer.msg(v.msg, {
                                icon: 2,   // 成功图标
                                time: 2000 //2秒关闭（如果不配置，默认是3秒）
                            });
                            // 验证失败提示错误
                            for(var i in v['data'])
                            {
                                $("#err_"+i).text(v['data'][i]).show(); // 显示对于的 错误提示
                            }
                            return false;
                        }
                        //return false;
                    }
                         // 验证失败提示错误
                     for(var i in v['data'])
                     {
                        $("#err_"+i).text(v['data'][i]).show(); // 显示对于的 错误提示
                     }
                }
            });
            before_request = 0; // 标识ajax 请求已经发出
}





