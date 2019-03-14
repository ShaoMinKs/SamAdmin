<?php
namespace app\admin\controller; 
use think\Db;

class Index extends Base {

    public function index(){
        $admin_info  = getAdminInfo(session('admin_id'));
		$res 		 = Db::name('admin_menu')->select();
		$menu        = array2tree($res);
		$head_menu   = Db::name('admin_menu')->where('pid',0)->field('name,op')->select();
		// dump($menu);die;
        return $this->fetch('index',[
            'admin_info' => $admin_info,
			'menu'       => $menu,
			'head_menu'  => $head_menu
        ]);
    }

    /**
     * 欢迎首页
     */
    public function welcome(){
        return $this->fetch('',[
            'sys_info' => $this->get_sys_info()
        ]);
    }

    /**
     * 获取环境配置
     */
    public function get_sys_info(){
		$sys_info['os']             = PHP_OS;
		$sys_info['zlib']           = function_exists('gzclose') ? 'YES' : 'NO';//zlib
		$sys_info['safe_mode']      = (boolean) ini_get('safe_mode') ? 'YES' : 'NO';//safe_mode = Off		
		$sys_info['timezone']       = function_exists("date_default_timezone_get") ? date_default_timezone_get() : "no_timezone";
		$sys_info['curl']			= function_exists('curl_init') ? 'YES' : 'NO';	
		$sys_info['web_server']     = $_SERVER['SERVER_SOFTWARE'];
		$sys_info['phpv']           = phpversion();
		$sys_info['ip'] 			= GetHostByName($_SERVER['SERVER_NAME']);
		$sys_info['fileupload']     = @ini_get('file_uploads') ? ini_get('upload_max_filesize') :'unknown';
		$sys_info['max_ex_time'] 	= @ini_get("max_execution_time").'s'; //脚本最大执行时间
		$sys_info['set_time_limit'] = function_exists("set_time_limit") ? true : false;
		$sys_info['domain'] 		= $_SERVER['HTTP_HOST'];
		$sys_info['memory_limit']   = ini_get('memory_limit');	                                
		$mysqlinfo = Db::query("SELECT VERSION() as version");
		$sys_info['mysql_version']  = $mysqlinfo[0]['version'];
		if(function_exists("gd_info")){
			$gd = gd_info();
			$sys_info['gdinfo'] 	= $gd['GD Version'];
		}else {
			$sys_info['gdinfo'] 	= "未知";
		}
		return $sys_info;
    }
}