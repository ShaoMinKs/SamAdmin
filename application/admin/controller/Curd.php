<?php
namespace app\admin\controller; 
use think\Db;
use think\facade\Request;
use think\facade\Config;
use think\Loader;

class Curd extends Base {
    private $modu;
    private $name;
    private $dir;
    private $namespaceSuffix;
    private $nameLower;
    private $data;

    public function index(){
        $dbtables = Db::query('SHOW TABLE STATUS');
        $this->assign('tables', $dbtables);
        if(Request::param('table')){
            $table      = Request::param('table');
            $prefix     = Config::get('database.prefix');
            $tableInfo  = Db::table($table)->getFieldsType();
            foreach ($tableInfo as $key => $value) {
                $tableInfo[] = $key;
                unset($tableInfo[$key]);
            }
            $controller = Loader::parseName(preg_replace('/^(' . $prefix . ')/', '', $table), 1);
            // dump(json_encode($tableInfo),true);die;
            $this->assign('table_info', json_encode($tableInfo));
            $this->assign('controller', $controller);
        }
        return $this->fetch();
    }

    public function run(){
        $generate = new \Generate();
        $data     = Request::post();
        $generate->run($data, Request::post('file'));
        $this->success('创建成功！');
    }
}