<!doctype html>
<html>
<head>
<meta charset="UTF-8" />
<title><?php echo $Title; ?></title>
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
      <li class="active"><a href="javascript:void(0)">1.检测环境</a></li>
      <li><a href="javascript:void(0)">2.创建数据</a></li>
      <li><a href="javascript:void(0)">3.完成安装</a></li>
    </ul>
      
    </div>
    
    <div class="server" style="padding:10px 5px">
      <table width="100%" class="table table-striped table-hover">
        <tr>
          <td class="td1">环境检测</td>
          <td class="td1" width="25%">推荐配置</td>
          <td class="td1" width="25%">当前状态</td>
          <td class="td1" width="25%">最低要求</td>
        </tr>
        <tr>
          <td>操作系统</td>
          <td>类UNIX</td>
          <td><span class="correct_span">&radic;</span> <?php echo $os; ?></td>
          <td>不限制</td>
        </tr>
        <tr>
          <td>服务器环境</td>
          <td>apache/nginx</td>
          <td><span class="correct_span">&radic;</span> <?php echo $_SERVER['SERVER_SOFTWARE']; ?></td>
          <td>apache2.0以上/nginx1.6以上</td>
        </tr>
        <tr>
          <td>PHP版本</td>
          <td>>5.4.x</td>
          <td><span class="correct_span">&radic;</span> <?php echo $phpv; ?></td>
          <td>5.4.0以上</td>
        </tr>
        <tr>
          <td>附件上传</td>
          <td>>2M</td>
          <td><?php echo $uploadSize; ?></td>
          <td>不限制</td>
        </tr>
        <tr>
          <td>session</td>
          <td>开启</td>
          <td><?php echo $session; ?></td>
          <td>开启</td>
        </tr>
        <tr>
          <td>safe_mode</td>
          <td>基础配置</td>
          <td><?php echo $safe_mode; ?></td>
          <td>启用</td>
        </tr>
        <tr>
          <td>GD库</td>
          <td>必须开启</td>
          <td><?php echo $gd; ?></td>
          <td>1.0</td>
        </tr>
        <tr>
          <td>mysqli</td>
          <td>必须开启</td>
          <td><?php echo $mysql; ?></td>
          <td>启用</td>
        </tr>        
      </table>
      <table width="100%" class="table table-striped table-hover">
        <tr>
          <td class="td1">目录、文件权限检查</td>
          <td class="td1" width="25%">推荐配置</td>
          <td class="td1" width="25%">写入</td>
          <td class="td1" width="25%">读取</td>
        </tr>
		<?php
		foreach($folder as $dir){
		     $Testdir = SITEDIR.$dir;
			 //echo "<br/>";
		         dir_create($Testdir);
			 if(TestWrite($Testdir)){
			     $w = '<span class="correct_span">&radic;</span>可写 ';
			 }else{
			     $w = '<span class="correct_span error_span">&radic;</span>不可写 ';
				 $err++;
			 }
			 if(is_readable($Testdir)){
			     $r = '<span class="correct_span">&radic;</span>可读' ;
			 }else{
			     $r = '<span class="correct_span error_span">&radic;</span>不可读';
				 $err++;
			 }
		?>
		        <tr>
		          <td><?php echo $dir; ?></td>
		          <td>读写</td>
		          <td><?php echo $w; ?></td>
		          <td><?php echo $r; ?></td>
		        </tr>
		<?php
		}                
		?>   
                <tr>
                  <td>config/database.php</td>
                  <td>读写</td>
                  <?php
                     if (is_writable(SITEDIR.'config/database.php')){
                        echo "<td><span class='correct_span'>√</span>可写 </td>
                              <td><span class='correct_span'>√</span>可读</td>";                 
                     }else{
                         $err++;
                        echo "<td><span class='correct_span error_span'>&radic;</span>不可写 </td>
                              <td><span class='correct_span error_span'>&radic;</span>不可读</td>";                        
                     }
                  ?>
                </tr>            
                <tr>
                  <td>config/app.php</td>
                  <td>读写</td>
                  <?php
                     if (is_writable(SITEDIR.'config/app.php')){
                        echo "<td><span class='correct_span'>√</span>可写 </td>
                              <td><span class='correct_span'>√</span>可读</td>";                 
                     }else{
                         $err++;
                        echo "<td><span class='correct_span error_span'>&radic;</span>不可写 </td>
                              <td><span class='correct_span error_span'>&radic;</span>不可读</td>";                        
                     }
                  ?>
                </tr>
                              
      </table>
      <table width="100%" class="table table-striped table-hover">
        <tr>
          <td class="td1">函数检测</td>
          <td class="td1" width="25%">推荐配置</td>
          <td class="td1" width="25%">当前状态</td>
          <td class="td1" width="25%">最低要求</td>
        </tr>
        <tr>
          <td>curl_init</td>
          <td>必须扩展</td>
          <td><?php echo $curl; ?></td>
          <td>--</td>
        </tr>
        <tr>
          <td>file_put_contents</td>
          <td>建议开启</td>
          <td><?php echo $file_put_contents; ?></td>
          <td>--</td>
        </tr>
      </table>
    </div>
    <div class="bottom tac"> 
	    <a href="<?php echo $_SERVER['PHP_SELF']; ?>?step=1" class="btn btn-primary">重新检测</a>
	    <?php if($err>0){?>
	    <a href="javascript:void(0)" onClick="javascript:alert('安装环境检测未通过，请检查')" class="btn btn-success" >下一步</a> 
	    <?php }else{?>
	    <a href="<?php echo $_SERVER['PHP_SELF']; ?>?step=2" class="btn btn-warning">下一步</a> 
	    <?php }?>
    </div>
  </section>
</div>
</body>
</html>