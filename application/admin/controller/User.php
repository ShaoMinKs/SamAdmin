<?php
namespace app\admin\controller;
use think\Db;
use service\DataService;
use think\facade\Cache;
use think\facade\Request;
use app\admin\validate\User as uservalidate;
use app\admin\model\Users as userModel;
use service\FormBuilder as Form;
use think\Url;


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
            return $this->data_list($db,true,true,true);
        }else{
            return $this->fetch(); 
        }

    }
    





    /**
     * 新增编辑会员
     */
    public function add(){
        return $this->_form($this->table,'public/form-builder');
        // $act     = Request::param('act');
        // $user_id = Request::param('user_id');
        // if($act == 'edit' && $user_id){
        //     $info = Db::name('users')->where('user_id',$user_id)->find();
        //     if($info){
        //         $this->assign('info',$info);
        //     }
        // }
        // return $this->fetch();
    }


    public function _add_form_filter(&$data){
        if(!Request::isPost()){
            $field = [
                Form::hidden('user_id',$data['user_id']??''),
                Form::input('nickname','会员昵称',$data['nickname'] ?? '')->required('昵称不得为空'),
                Form::frameImageOne('image','头像(305*305px)',url('admin/widget.images/index',array('fodder'=>'image')),$data['head_pic']??'')->icon('image')->width('100%')->height('500px'),
                Form::input('password','管理员密码')->type('password'),
                Form::input('password_confirm','确认密码')->type('password'),
                Form::input('mobile','手机号码',$data['mobile'] ?? '')->required('手机不得为空'),
                Form::radio('sex','性别',$data['sex'] ?? '')->options([['label'=>'男','value'=>1],['label'=>'女','value'=>2]])->col(24),
                Form::input('qq','QQ',$data['qq'] ?? '')->col(12),
                Form::input('email','邮箱',$data['email'] ?? '')->required()->col(12),
                
                Form::radio('is_lock','是否锁定',$data['is_lock'] ?? '')->options([['label'=>'是','value'=>1],['label'=>'否','value'=>0]])->col(24),
            ];
            $form = Form::make_post_form('添加',$field,'add',2);
            $this->assign(compact('form'));
        }else{

            $validate           = new uservalidate;
            $result             = $validate->scene($data['user_id']?'edit':'add')->check($data);
            $data['head_pic']   = $data['image'];
            if($data['user_id'] && !$data['password']){
                 unset($data['password']);
            }else{
                $data['password'] = md5($data['password']);
            }
            if(!$data['user_id']) $data['reg_time'] = time();
            if(true != $result){
                $this->error($validate->getError());
            }
            return true;
        }
        
    }

    public function form_build(){
        $field = [
            Form::input('username','用户名','汉字')->required('不得为空'),
            Form::hidden('uid',1),
            Form::input('store_name','产品名称')->col(Form::col(24)),
            Form::input('store_info','产品简介')->type('textarea'),
            Form::frameImageOne('image','产品主图片(305*305px)',url('admin/widget.images/index',array('fodder'=>'image')))->icon('image')->width('100%')->height('500px'),
            Form::number('price','产品售价')->min(0)->col(8),
            Form::radio('is_show','产品状态',0)->options([['label'=>'上架','value'=>1],['label'=>'下架','value'=>0]])->col(24),
        ];
        $form = Form::make_post_form('添加产品',$field,'save',2);
        $this->assign(compact('form'));
        return $this->fetch('public/form-builder');
    }

    public function save(){
        return $this->_form($this->table);
    }

    /**
     * 会员提交
     */
    public function  userHandle(){
        if(Request::isAjax()){
            $data               = Request::post();
            $validate           = new uservalidate();
            $data['is_lock']    = isset($data['is_lock']) ? 1 : 0;
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

    public function _save_form_filter(&$data){
        $validate           = new uservalidate;
        $data['is_lock']    =  isset($data['is_lock']) ? 1 : 0;
        $result             = $validate->scene($data['act'])->check($data);
        if(true != $result){
            $this->error($validate->getError());
        }
        
    }
}