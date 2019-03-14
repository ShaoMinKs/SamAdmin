<?php
namespace app\admin\controller;
use think\Db;
use service\DataService;
use think\facade\Cache;
use think\facade\Request;
use app\admin\validate\User as uservalidate;
use app\admin\model\Users as userModel;

class User extends Base{
    

    /**
     * 指定当前数据表
     * @var string
     */
    public $table = 'users';

    /**
     * 会员列表首页
     */
    public function index(){
        //采集某页面所有的图片
        $this->title = '用户管理';
        if(Request::isAjax()){
            list($get, $db) = [$this->request->get(), Db::name($this->table)];
            foreach (['username', 'phone', 'mail'] as $key) {
                (isset($get[$key]) && $get[$key] !== '') && $db->whereLike($key, "%{$get[$key]}%");
            }
            (isset($get['keywords']) && $get['keywords'] !== '') && $db->whereLike('nickname|email|mobile', "%{$get['keywords']}%");
            list($page,$limit)  = [$this->request->get('page/d'),$this->request->get('limit/d')];
            ($page && $limit)  && $db->page($page,$limit);
            $db->field('user_id,nickname,email,sex,mobile,email,reg_time,last_login');
            return parent::data_list($db->where(['is_lock' => '0']),true,true,true);
        }else{
            return $this->fetch(); 
        }

    }
    



    /**
     * 新增编辑会员
     */
    public function add(){
        $act     = Request::param('act');
        $user_id = Request::param('user_id');
        if($act == 'edit' && $user_id){
            $info = Db::name('users')->where('user_id',$user_id)->find();
            if($info){
                $this->assign('info',$info);
            }
        }
        return $this->fetch();
    }

    /**
     * 会员提交
     */
    public function  userHandle(){
        if(Request::isAjax()){
            $data               = Request::post();
            $validate           = new uservalidate();
            $data['is_lock']    =  isset($data['is_lock']) ? 1 : 0;
            $user               = new userModel();
            $result             = $validate->scene($data['act'])->check($data);
            if(true != $result){
                $this->error($validate->getError());
            }
            switch ($data['act']) {
                case 'add':
                    $data['reg_time']  = time();
                    $res = $user->allowField(true)->save($data);
                    break;
                case 'edit':
                    $res = $user->allowField(true)->save($data,['user_id'=>$data['user_id']]);
                break;
                default:
                case 'del':
                    $res = $user::destroy($data['user_id']);
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