<!doctype html>
<html>
<head>
<meta charset="UTF-8" />
<title><?php echo $Title; ?> - <?php echo $Powered; ?></title>
<link rel="stylesheet" href="./css/install.css?v=9.0" />
<link rel="stylesheet" href="https://cdn.staticfile.org/twitter-bootstrap/3.3.7/css/bootstrap.min.css">  
	<script src="https://cdn.staticfile.org/jquery/2.1.1/jquery.min.js"></script>
	<script src="https://cdn.staticfile.org/twitter-bootstrap/3.3.7/js/bootstrap.min.js"></script>
</head>
<body>
<div class="wrap">
  <?php require './templates/header.php';?>
  <section class="section">
    <div>
    <ul class="nav nav-tabs nav-justified">
      <li><a href="javascript:void(0)">1.检测环境</a></li>
      <li  class="active"><a href="javascript:void(0)">2.创建数据</a></li>
      <li><a href="javascript:void(0)">3.完成安装</a></li>
    </ul>
    </div>
    <form id="J_install_form" action="index.php?step=3" method="post">
      <input type="hidden" name="force" value="0" />
      <div class="server" style="padding:10px 0">
        <table width="100%" class="table table-striped table-hover">
          <tr>
            <td class="td1" width="150">数据库信息</td>
            <td class="td1" width="200">&nbsp;</td>
            <td class="td1">&nbsp;</td>
          </tr>
		  <tr>
            <td class="tar">数据库服务器：</td>
            <td><input type="text" name="dbhost" id="dbhost" value="localhost" class="input"></td>
            <td><div id="J_install_tip_dbhost"><span class="gray">数据库服务器地址，一般为localhost</span></div></td>
          </tr>
		  <tr>
            <td class="tar">数据库端口：</td>
            <td><input type="text" name="dbport" id="dbport" value="3306" class="input"></td>
            <td><div id="J_install_tip_dbport"><span class="gray">数据库服务器端口，一般为3306</span></div></td>
          </tr>
          <tr>
            <td class="tar">数据库用户名：</td>
            <td><input type="text" name="dbuser" id="dbuser" value="root" class="input"></td>
            <td><div id="J_install_tip_dbuser"></div></td>
          </tr>
          <tr>
            <td class="tar">数据库密码：</td>
            <td><input type="password" name="dbpw" id="dbpw" value="" class="input" autoComplete="off" onBlur="TestDbPwd(0)"></td>
            <td><div id="J_install_tip_dbpw"></div></td>
          </tr>
          <tr>
            <td class="tar">数据库名：</td>
            <td><input type="text" name="dbname" id="dbname" value="tpshop2.0" class="input" onBlur="TestDbPwd(0)"></td>
            <td><div id="J_install_tip_dbname"></div></td>
          </tr>
          <tr>
            <td class="tar">数据库表前缀：</td>
            <td><input type="text" name="dbprefix" id="dbprefix" value="tp_" class="input" ></td>
            <td><div id="J_install_tip_dbprefix"><span class="gray">建议使用默认，同一数据库安装多个TPshop时需修改</span></div></td>
          </tr>
          <tr>
          	<td class="tar">演示数据：</td>
          	<td colspan="2"><input style="width:18px;height:18px;" type="checkbox" id="demo" name="demo" value="demo" checked>是否安装测试数据</td>
          </tr>
        </table>

        <table width="100%" class="table table-striped table-hover">
          <tr>
            <td class="td1" width="150">管理员信息</td>
            <td class="td1" width="200">&nbsp;</td>
            <td class="td1">&nbsp;</td>
          </tr>
          <tr>
            <td class="tar">管理员帐号：</td>
            <td><input type="text" name="manager" id="manager" value="admin" class="input"></td>
            <td><div id="J_install_tip_manager"></div></td>
          </tr>
          <tr>
            <td class="tar">管理员密码：</td>
            <td><input type="password" name="manager_pwd" id="manager_pwd" class="input" autoComplete="off"></td>
            <td><div id="J_install_tip_manager_pwd"></div></td>
          </tr>
          <tr>
            <td class="tar">重复密码：</td>
            <td><input type="password" name="manager_ckpwd" id="manager_ckpwd" class="input" autoComplete="off"></td>
            <td><div id="J_install_tip_manager_ckpwd"></div></td>
          </tr>
          <tr>
            <td class="tar">Email：</td>
            <td><input type="text" name="manager_email" class="input" value=""></td>
            <td><div id="J_install_tip_manager_email"></div></td>
          </tr>
        </table>
        <div id="J_response_tips" style="display:none;"></div>
      </div>
      <div class="bottom tac"> <a href="./index.php?step=1" class="btn btn-primary">上一步</a>
        <button type="button" onClick="checkForm();" class="btn btn-warning btn_submit J_install_btn">创建数据</button>
      </div>
    </form>
  </section>
  <div  style="width:0;height:0;overflow:hidden;"> <img src="./images/install/pop_loading.gif"> </div>
  <script src="./js/jquery.js?v=9.0"></script> 
  <script src="./js/validate.js?v=9.0"></script> 
  <script src="./js/ajaxForm.js?v=9.0"></script> 
  <script>
   
  function TestDbPwd(connect_db)
    {
        var dbHost = $('#dbhost').val();
        var dbUser = $('#dbuser').val();
        var dbPwd = $('#dbpw').val();
        var dbName = $('#dbname').val();
        var dbport = $('#dbport').val();
		var demo  =  $('#demo').val();
        data={'dbHost':dbHost,'dbUser':dbUser,'dbPwd':dbPwd,'dbName':dbName,'dbport':dbport,'demo':demo};
        var url =  "<?php echo $_SERVER['PHP_SELF']; ?>?step=2&testdbpwd=1";
        $.ajax({
            type: "POST",
            url: url,
            data: data,
            dataType:'JSON',
            beforeSend:function(){				 
            },
            success: function(msg){			
                if(msg == 1){
                     
					if(connect_db == 1)
					{
						$("#J_install_form").submit(); // ajax 验证通过后再提交表单
					}		
					$('#J_install_tip_dbpw').html('');
					$('#J_install_tip_dbname').html('');							
                }
				else if(msg == -1)
				{				    
                    $('#J_install_tip_dbpw').html('<span for="dbname" generated="true" class="tips_error" style="">请在mysql配置文件修sql-mode或sql_mode为NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION 若无sql_mode请在[mysqld]后面一行加上</span>');
				}
				else if(msg == -2)
				{				    
                    $('#J_install_tip_dbname').html('<span for="dbname" generated="true" class="tips_error" style="">该数据库已经存在</span>');
				}
				else{
				    $('#dbpw').val("");
                    $('#J_install_tip_dbpw').html('<span for="dbname" generated="true" class="tips_error" style="">数据库链接配置失败</span>');
                }
            },
            complete:function(){
            },
            error:function(){
                $('#J_install_tip_dbpw').html('<span for="dbname" generated="true" class="tips_error" style="">数据库链接配置失败</span>');		
				$('#dbpw').val("");
            }
        });
    }
	
 

	function checkForm()
	{
			manager = $.trim($('#manager').val());				//用户名表单
			manager_pwd = $.trim($('#manager_pwd').val());				//密码表单
			manager_ckpwd = $.trim($('#manager_ckpwd').val());		//密码提示区
			 
			if(manager.length == 0 )
			{
				alert('管理员账号不能为空');
				return false;
			}
			if(manager_pwd.length < 6 )
			{
				alert('管理员密码必须6位数以上');
				return false;
			}	
			if(manager_ckpwd !=  manager_pwd)
			{
				alert('两次密码不一致');
				return false;
			}				
			TestDbPwd(1);		
	}
 


</script> 
</div>
</body>
</html>