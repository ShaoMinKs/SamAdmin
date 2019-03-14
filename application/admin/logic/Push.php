<?php
namespace app\admin\logic;
use think\Db;
use think\Controller;

class Push extends Controller {
      // 微信配置
    protected $weconfig = [];

    public function initialize(){
        parent::initialize();
        if(empty($this->weconfig)){
            $config = Db::name('wx_user')->field('wxname,appid,appsecret,token,aeskey')->find();
            if($config){
                $this->weconfig = $config;
            }
        }
    }

        /**
     * 写入日志
     */
    public function logger($content){
    	$logSize=100000;

    	$log="log.txt";

    	if(file_exists($log) && filesize($log)  > $logSize){
    		unlink($log);
    	}

    	file_put_contents($log,date('Y-m-d H:i:s')." ".$content."\n",FILE_APPEND);

    }
    public function index(){
        $api = new \WeChat\Receive($this->weconfig);
    }
}