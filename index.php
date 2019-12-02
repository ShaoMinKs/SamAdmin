<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

// [ 应用入口文件 ]
namespace think;
if(version_compare(PHP_VERSION,'5.5.0','<'))
{
    header("Content-type: text/html; charset=utf-8");  
    die('PHP 版本太低!');
}

if(file_exists("./install/") && !file_exists("./install/install.lock")){
	if($_SERVER['PHP_SELF'] != '/index.php'){
		header("Content-type: text/html; charset=utf-8");         
		exit("请在域名根目录下安装,如:<br/> www.xxx.com/index.php");
	}  
	header('Location:/install/index.php');
	exit(); 
}
// 加载基础文件
require __DIR__ . '/thinkphp/base.php';

// 支持事先使用静态方法设置Request对象和Config对象
define('PLUGIN_PATH', __DIR__ . '/plugins/');
defined('UPLOAD_PATH') or define('UPLOAD_PATH','public/upload/'); // 编辑器图片上传路径
define('DS', DIRECTORY_SEPARATOR);
defined('APP_PATH') or define('APP_PATH', dirname($_SERVER['SCRIPT_FILENAME']) . DS);
defined('ROOT_PATH') or define('ROOT_PATH', dirname(realpath(APP_PATH)) . DS);
defined('PUBILC_PATH') or define('PUBILC_PATH', APP_PATH.'public/');
// 执行应用并响应
Container::get('app')->run()->send();
