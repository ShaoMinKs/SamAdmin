<?php
namespace app\admin\controller;
use think\Controller;
use think\Db;
use service\DataService;
use think\facade\Request;
use think\facade\Session;

class Base extends Controller {


        /**
     * 页面标题
     * @var string
     */
    public $title;

    /**
     * 默认操作数据表
     * @var string
     */
    public $table;

        /**
     * 析构函数
     */
    function __construct() 
    {
        Session::start();
        header("Cache-control: private");
        parent::__construct();
   }

       /**
     * 初始化操作
     */
    public function initialize()
    { 
       $this->action      = Request::action();
       $this->controller  = Request::controller();
       $this->module      = Request::module();
       $res = $this->rightCheck();
       if(!in_array($this->action,['login','verify','review'])){
           if(session('admin_id') > 0){
                $this->check_right();
                $this->admin_id = session('admin_id');
           }else{
            ($this->action == 'index') && $this->redirect(url('Admin/Admin/login'));
            $this->error('请先登录', url('Admin/Admin/login'), null, 1);
           }  
       }
       $site_config= [];
       $config = Db::name('config')->cache('config',3600)->select();
       if($config){
           foreach ($config as $key => $value) {
               $site_config[$value['inc_type'].'_'.$value['name']]  = $value['value'];
           }
       }
       $this->assign('site_config',$site_config);
    }



        /**
     * 检查权限
     */
    public function check_right(){
        $act_list = session('act_list');
        $uneed_right = array('login','logout','vertifyHandle','vertify','imageUp','upload','videoUp','delupload','login_task');
        if($this->action == 'Index' || $act_list == 'all' ){
            return true;
        }elseif(Request::isAjax() || in_array($this->action,$uneed_right)){
            return true;
        }else{
            $res = $this->rightCheck();
            if($res['status'] == -1){
                $this->error($res['msg'],$res['url']);
            };
        }
    }
    
    /**
     * 权限检查
     */
    private function rightCheck(){
        $act_list = session('act_list');
        $right = Db::name('system_menu')->cache(true)->column('right');
        $role_right = '';
        foreach ($right as $value){
            $role_right .= $value.',';
        }
        $role_right  = array_filter(explode(',',$role_right));
        if(!in_array($this->controller.'@'.$this->action,$role_right)){
            return ['status'=>-1,'msg'=>'您没有操作权限['.($this->controller.'@'.$this->action).'],请联系超级管理员分配权限','url'=>url('Admin/Index/welcome')];
        }
    }

    public function ajaxReturn($data,$type = 'json'){                        
        exit(json_encode($data));
   }


   
    /**
     * 表单默认操作
     * @param Query $dbQuery 数据库查询对象
     * @param string $tplFile 显示模板名字
     * @param string $pkField 更新主键规则
     * @param array $where 查询规则
     * @param array $extendData 扩展数据
     * @return array|string
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * @throws \think\Exception
     */
    protected function _form($dbQuery = null, $tplFile = '', $pkField = '', $where = [], $extendData = [])
    {
        $db = is_null($dbQuery) ? Db::name($this->table) : (is_string($dbQuery) ? Db::name($dbQuery) : $dbQuery);
        $pk = empty($pkField) ? ($db->getPk() ? $db->getPk() : 'id') : $pkField;
        $pkValue = $this->request->request($pk, isset($where[$pk]) ? $where[$pk] : (isset($extendData[$pk]) ? $extendData[$pk] : null));
        // 非POST请求, 获取数据并显示表单页面
        if (!$this->request->isPost()) {
            $vo = ($pkValue !== null) ? array_merge((array)$db->where($pk, $pkValue)->where($where)->find(), $extendData) : $extendData;
            if (false !== $this->_callback('_form_filter', $vo, [])) {
                empty($this->title) || $this->assign('title', $this->title);
                return $this->fetch($tplFile, ['vo' => $vo]);
            }
            return $vo;
        }
        // POST请求, 数据自动存库
        $data = array_merge($this->request->post(), $extendData);
        if (false !== $this->_callback('_form_filter', $data, [])) {
            $result = DataService::save($db, $data, $pk, $where);
            if (false !== $this->_callback('_form_result', $result, $data)) {
                if ($result !== false) {
                    $this->success('恭喜, 数据保存成功!', '');
                }
                $this->error('数据保存失败, 请稍候再试!');
            }
        }
    }


/**
 * data_list function
 *列表数据处理Description
 * @param [type] $dbQuery 查询对象
 * @param boolean $isPage 开启分页
 * @param boolean $isDisplay 是否直接输出
 * * @param boolean $layui 是否layui分页（需要layui支持）
 * @param boolean $total 总记录数
 * @param array $result 结果集
 * @return array|string
 * @列表数据处理Description
 * @author Sam
 * @since 
 */
    protected function data_list($dbQuery = null, $isPage = true,$layui = true, $isDisplay = true, $total = false, $result = []){
        $db = is_null($dbQuery) ? Db::name($this->table) : (is_string($dbQuery) ? Db::name($dbQuery) : $dbQuery);
        // 列表数据查询与显示
        if (null === $db->getOptions('order')) {
            in_array('sort', $db->getTableFields($db->getTable())) && $db->order('sort asc');
        }
        // 开启分页
        if($isPage){
            if($layui){
                $list  = $db->select();
                $count = $db->count();
                if (false !== $this->_callback('_data_filter', $list, []) && $isDisplay) {
                    !empty($this->title) && $this->assign('title', $this->title);
                    return json([
                        'code'  => 0,
                        'msg'   => '',
                        'count' => $count >0 ? $count : 0 ,
                        'data'  => isset($list) ? $list : ''
                    ]);
                }
            }else{
                $rows = intval($this->request->get('rows', cookie('page-rows')));
                cookie('page-rows', $rows = $rows >= 10 ? $rows : 20);
                // 分页数据处理
                $query = $this->request->get();
                $page = $db->paginate($rows, $total, ['query' => $query]);
                if (($totalNum = $page->total()) > 0) {
                    list($rowHTML, $curPage, $maxNum) = [[], $page->currentPage(), $page->lastPage()];
                    foreach ([10, 20, 30, 40, 50, 60, 70, 80, 90, 100, 110, 120, 130, 140, 150, 160, 170, 180, 190, 200] as $num) {
                        list($query['rows'], $query['page']) = [$num, '1'];
                        $url = url('@admin') . '#' . $this->request->baseUrl() . '?' . urldecode(http_build_query($query));
                        $rowHTML[] = "<option data-url='{$url}' " . ($rows === $num ? 'selected' : '') . " value='{$num}'>{$num}</option>";
                    }
                    list($pattern, $replacement) = [['|href="(.*?)"|', '|pagination|'], ['data-open="$1"', 'pagination pull-right']];
                    $html = "<span class='pagination-trigger nowrap'>共 {$totalNum} 条记录，每页显示 <select data-auto-none>" . join('', $rowHTML) . "</select> 条，共 {$maxNum} 页当前显示第 {$curPage} 页。</span>";
                    list($result['total'], $result['list'], $result['page']) = [$totalNum, $page->all(), $html . preg_replace($pattern, $replacement, $page->render())];
                } else {
                    list($result['total'], $result['list'], $result['page']) = [$totalNum, $page->all(), $page->render()];
                }
            }
        }else{
            $result['list'] = $db->select();
        }
        if (false !== $this->_callback('_data_filter', $result['list'], []) && $isDisplay) {
            !empty($this->title) && $this->assign('title', $this->title);
            return $this->fetch('', $result);
        }
        return $result;
    }


       /**
     * 当前对象回调成员方法
     * @param string $method
     * @param array|bool $data1
     * @param array|bool $data2
     * @return bool
     */
    protected function _callback($method, &$data1, $data2)
    {

        foreach ([$method, "_" . $this->request->action() . "{$method}"] as $_method) {
            if (method_exists($this, $_method) && false === $this->$_method($data1, $data2)) {
                return false;
            }
        }
        return true;
    }
}