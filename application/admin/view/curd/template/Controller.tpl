<?php
namespace app\[MODULE]\controller;
use think\Db;
use service\DataService;
use think\facade\Cache;
use think\facade\Request;
use app\[MODULE]\validate\[CONTROLLER] as [CONTROLLER]validate;
use app\[MODULE]\model\[UTABLE] as [TABLE]Model;

class [CONTROLLER] extends Base{
    

    /**
     * 指定当前数据表
     * @var string
     */
    public $table = '[TABLE]';

    /**
     * 首页
     */
    public function index(){
        if(Request::isAjax()){
            list($get, $db) = [$this->request->get(), Db::name($this->table)];
            foreach ([ITEM] as $key) {
                (isset($get[$key]) && $get[$key] !== '') && $db->whereLike($key, "%{$get[$key]}%");
            }
            list($page,$limit)  = [$this->request->get('page/d'),$this->request->get('limit/d')];
            ($page && $limit)  && $db->page($page,$limit);;
            return parent::data_list($db,true,true,true);
        }else{
            return $this->fetch(); 
        }

    }
    



    /**
     * 新增编辑
     */
    public function add(){
        $act     = Request::param('act');
        $id      = Request::param('id');
        if($act == 'edit' && $id){
            $info = Db::name('[TABLE]')->where('id',$id)->find();
            if($info){
                $this->assign('info',$info);
            }
        }
        return $this->fetch();
    }

    /**
     * 会员提交
     */
    public function  Handle(){
        if(Request::isAjax()){
            $data               = Request::post();
            $validate           = new [CONTROLLER]validate();
            $[TABLE]            = new [TABLE]Model();
            switch ($data['act']) {
                case 'add':
                    $result             = $validate->check($data);
                    if(true != $result){
                        $this->error($validate->getError());
                    }
                    $res = $[TABLE]->allowField(true)->save($data);
                    break;
                case 'edit':
                    $result             = $validate->check($data);
                        if(true != $result){
                            $this->error($validate->getError());
                        }
                    $res = $[TABLE]->allowField(true)->save($data,['id'=>$data['id']]);
                break;
                default:
                case 'del':
                    $res = $[TABLE]::destroy($data['id']);
                break;
                    break;
            }
            if($res){
                $this->success('操作成功！');
            }else{
                $this->error('操作失败');
            }   
        }else{
            $this->error('非法提交!');
        }
    }
}