<?php /*a:1:{s:72:"/www/wwwroot/sam_zhuzhouyike_com/application/admin/view/admin/login.html";i:1546394173;}*/ ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="/public/static/css/login.css?v=2.9" rel="stylesheet" type="text/css" />
    <script type="text/javascript" src="/public/static/js/jquery.js"></script>
    <script type="text/javascript" src="/public/static/js/jquery.SuperSlide.2.1.2.js"></script>
    <script type="text/javascript" src="/public/static/js/jquery.validation.min.js"></script>
    <script type="text/javascript" src="/public/static/js/jquery.cookie.js"></script>
    <title>登录</title>
    <!--[if lte IE 8]>
	<script type="Text/Javascript" language="JavaScript">
	    function detectBrowser()
	    {
		    var browser = navigator.appName
		    if(navigator.userAgent.indexOf("MSIE")>0){ 
			    var b_version = navigator.appVersion
				var version = b_version.split(";");
				var trim_Version = version[1].replace(/[ ]/g,"");
			    if ((browser=="Netscape"||browser=="Microsoft Internet Explorer"))
			    {
			    	if(trim_Version == 'MSIE8.0' || trim_Version == 'MSIE7.0' || trim_Version == 'MSIE6.0'){
			    		alert('请使用IE9.0版本以上进行访问');
			    		return false;
			    	}
			    }
		    }
	   }
       detectBrowser();
    </script>
<![endif]-->
</head>
<body>
    <div class="login-layout">
        <form action="" name='theForm' id="theForm" method="post">
            <div class="login-form" style="position: relative">
                <div class="formContent">
                	<div class="title">SamAdmin管理中心</div>
                    <div class="formInfo">
                    	<div class="formText">
                        	<i class="icon icon-user"></i>
                            <input type="text" name="username" autocomplete="off"  required class="input-text" value="" placeholder="请输入用户名"  onkeyup="this.value=this.value.replace(/^ +| +$/g,'')" />
                        </div>
                        <div class="formText">
                        	<i class="icon icon-pwd"></i>
                            <input type="password" name="password" autocomplete="off" required class="input-text" value="" placeholder="请输入密码" />
                        </div>
                        <div class="formText">
                            <i class="icon icon-chick" style="top:14px"></i>
                            <input type="text" name="vertify" id="vertify" autocomplete="off" class="input-text chick_ue" value="" placeholder="验证码" />
                            <img src="<?php echo url('Admin/verify'); ?>" class="chicuele" id="imgVerify" alt="" onclick="fleshVerify()">
                        </div>
                        <div class="formText">
                        	<!--<div class="checkbox">
                            	<div class="cur">
                                    <input type="hidden" value="1" name="remember"/>
                                </div>
                            </div>
                           <span class="span">保存信息</span>-->
                            <!-- <a href="<?php echo url('Admin/forget_pwd'); ?>" class="forget_pwd">忘记密码？</a> -->
                        </div>
						<div class="formText submitDiv">
                          <span class="submit_span">
                          	<input type="button" name="submit" class="sub" value="登录">
                          </span>
                       </div>
                    </div>
                </div>
                <div id="error" style="position: absolute;left:0px;bottom: 60px;text-align: center;width:441px;">

                </div>
            </div>
        </form>
    </div>
    <div id="bannerBox">
        <ul id="slideBanner" class="slideBanner">
            <li><img src="/public/static/images/banner_4.jpg"></li>
            <li><img src="/public/static/images/banner_5.jpg"></li> 
            <li><img src="/public/static/images/banner_6.jpg"></li>
        </ul>
    </div>
<script>
    $(function(){
   
            if(self !== top){
                top.location.href = self.location.href;
            }
            $(".formText .input-text").focus(function(){
                $(this).parent().addClass("focus");
               
			});
			
			$(".formText .input-text").blur(function(){
                $(this).parent().removeClass("focus");
                $('#error').empty();
			});
            $(".formText .input-yzm").focus(function(){
				$(this).prev().show();
			});
			
			$(".formText").blur(function(){
				$(this).prev().hide();
            });	
            $('.submit_span .sub').on('click',function(){
                $('.code').show();
            });
            $('#theForm input[name=submit]').on('click',function(){
                formSubmit();
            })

        });

        //表单提交
        function formSubmit(){
            $("#theForm").validate();
            var username=true;
            var password=true;
            var vertify=true;
            if(($("#theForm input[name=username]").val()).trim()== ''){
                $('#error').html('<span class="error">用户名不能为空!</span>');
                $("#theForm input[name=username]").focus();
                username = false;
                return false;
            }
            if(($("#theForm input[name=password]").val()).trim()== ''){
                $('#error').html('<span class="error">密码不能为空!</span>');
                $("#theForm input[name=password]").focus();
                password = false;
                return false;
            }
            if(($("#theForm input[name=vertify]").val()).trim()== ''){
                $('#error').html('<span class="error">验证码不能为空!</span>');
                $("#theForm input[name=vertify]").focus();
                vertify = false;
                return false;
            }
            if(vertify && $('#theForm input[name=username]').val() != '' && $('#theForm input[name=password]').val() != ''){
                $.ajax({
                    url : "<?php echo url('admin/admin/login'); ?>",
                    type : 'post',
                    data : {
                        username:$('#theForm input[name=username]').val(),
                        password:$('#theForm input[name=password]').val(),
                        verify:$('#theForm input[name=vertify]').val()
                    },
                    success : function(res){
                        if(res.status != 1){
                            $('#error').html('<span class="error">'+res.msg+'!</span>');
                            fleshVerify();
                            username=false;
                            password=false;
                            return false;
                        }else{
                            top.location.href = res.url;
                        }
                    }
                })
            }else{
                return false;
            }
        }
               //回车提交
               $(document).keyup(function(event){
                if(event.keyCode ==13){
                    var isFocus=$("#vertify").is(":focus");
                    if(true==isFocus){
                        formSubmit();
                    }
                }
            });
    //背景切换
    $("#bannerBox").slide({mainCell:".slideBanner",effect:"fold",interTime:3500,delayTime:500,autoPlay:true,autoPage:true,endFun:function(i,c,s){
        $(window).resize(function(){
            var width = $(window).width();
            var height = $(window).height();
            s.find(".slideBanner,.slideBanner li").css({"width":width,"height":height});
        });
    }});
  
    //验证码刷新
    function  fleshVerify(){
        $('#imgVerify').attr('src',"/admin/admin/verify/r="+Math.floor(Math.random()*100));//重载验证码
    }
</script>
</body>
</html>