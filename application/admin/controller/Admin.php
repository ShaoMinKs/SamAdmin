<?php
namespace app\admin\controller;
use think\captcha\Captcha;
use think\facade\Request;
use app\common\logic\AdminLogic;
use think\Db;
use think\facade\Session;
use think\facade\Cache;
use app\admin\model\Admin as AdminModel;
use app\admin\validate\Admin as AdminValidate;

class Admin extends Base{


    /**
     * 管理员列表
     */
    public function index(){
        $map[] = ['name','like','think'];
        $map[] = ['status','=',1];
        $res  = Db::name('admin')->field('admin_id')->select();
        return $this->fetch('index',[
            'list'  => $res
        ]);
    }

    /**
     * ajax 获取列表
     */
    public function getAdmin(){
        $page   = input('param.page/d');
        $limit  = input('param.limit/d');
        $keyword = input('param.keyword');
        if($keyword){
            $map = " user_name like '%{$keyword}%'";
        }else{
            $map = " 1= 1";
        }
        $res    = Db::name('admin')->page($page,$limit)->where($map)->select();
        $role   = Db::name('admin_role')->column('role_id,role_name');
        $count  = Db::name('admin')->count();
        $list   = [];
        foreach ($res as $key=>&$value){
            $value['role']        = $role[$value['role_id']];
            $value['add_time']    = date('Y-m-d H:i:s',$value['add_time']);  
            $value['last_login']  = date('Y-m-d H:i:s',$value['last_login']);
            $list[] = $value;
        }
        return json([
            'code'  => 0,
            'msg'   => '',
            'count' => $count,
            'data'  => $list
        ]);
    }

        /**
     * ajax 获取角色列表
     */
    public function getAdminRole(){
        $page   = input('param.page/d');
        $limit  = input('param.limit/d');
        $keyword = input('param.keyword');
        if($keyword){
            $map = " role_name like '%{$keyword}%'";
        }else{
            $map = " 1= 1";
        }
        $list    = Db::name('admin_role')->page($page,$limit)->where($map)->where('role_id','>',1)->select();
        $count   = Db::name('admin_role')->count();
        return json([
            'code'  => 0,
            'msg'   => '',
            'count' => $count,
            'data'  => $list
        ]);
    }


    public function changeTableValue(){
        if(Request::isAjax()){
            $data = input('post.');
            if($data['table'] && $data['id_name'] && $data['id_value'] && $data['field']){
                $res  = Db::name($data['table'])->where($data['id_name'],$data['id_value'])->update([$data['field']=>$data['field_value']]);
                if($res){
                    $this->success('操作成功！');
                }else{
                    $this->error('操作失败!');
                }
            }else{
                $this->error('缺少参数！');
            }
        }else{
            $this->error('非法请求！');
        }
      
       
    }

    /**
     * 
     * 角色列表
     */
    public function role(){
        $count  = Db::name('admin_role')->count();
        $this->assign('count',$count);
        return $this->fetch();
    }
    /**
     * 管理员详情
     */
    public function admin_info(){
        $admin_id = \input('admin_id/d');
        if($admin_id){
            $info = Db::name('admin')->where('admin_id',$admin_id)->find();
            $this->assign('info',$info);
        }else{
            $this->assign('info',[]);
        }
        $act = empty($admin_id) ? 'add' : 'edit';
        $this->assign('act',$act);
        $role = Db::name('admin_role')->select();
        $this->assign('role',$role);
        return $this->fetch();
    }

/**
 * 角色编辑管理
 */
    public function role_info(){
        $role_id = \input('role_id/d');
        $detail = array();
    	if($role_id){
    		$detail = Db::name('admin_role')->where("role_id",$role_id)->find();
    		$detail['act_list'] = explode(',', $detail['act_list']);
    		$this->assign('detail',$detail);
        }
        $right = Db::name('system_menu')->order('id')->where('is_del',0)->select();
		foreach ($right as $val){
			if(!empty($detail)){
				$val['enable'] = in_array($val['id'], $detail['act_list']);
			}
			$modules[$val['group']][] = $val;
        }
        $group = [
            'system' =>  '系统设置',
            'content' => '内容管理',
            'member'  => '会员中心',
            'weixin'  => '微信管理',
            'goods'   => '商品中心',
            'finance' => '财务管理',
            'tools'   => '插件工具',
            'order'  =>   '订单中心',
            'count'   => '统计报表',
            'distribut'=>'分销中心',
            'marketing' => '营销推广'
        ];
        $this->assign('group',$group);
        $this->assign('modules',$modules);
 
        $act = empty($role_id) ? 'add' : 'edit';
        $this->assign('act',$act);
        return $this->fetch();
    }
    /**
     * 管理员提交
     */
    public function adminHandle(){
        $data          = Request::post();
        if($data['auth_code'] != config('AUTH_CODE')){
            $this->ajaxReturn(['status'=>-1,'msg'=>'非法提交！']);
        }
        unset($data['auth_code']);
        $adminValidate = new  AdminValidate();
        if (!$adminValidate->scene($data['act'])->batch()->check($data)) {
            $this->ajaxReturn(['status'=>-1,'msg'=>'操作失败','result'=>$adminValidate->getError()]);
        }
        if(empty($data['password'])){
            unset($data['password']);
        }else{
            $data['password']  = encrypt($data['password']);
        }
        if($data['act'] == 'add'){
            unset($data['admin_id']);   		
            $admin = new AdminModel();
            $data['add_time'] = time();
			$r = $admin->save($data);
        }
        if($data['act'] == 'edit'){
            $admin = new AdminModel();
            $r = $admin->allowField(true)->save($data,['admin_id'=>$data['admin_id']]);
    		// $r = Db::name('admin')->where('admin_id', $data['admin_id'])->update($data);
        }
        if($data['act'] == 'del' && $data['admin_id']>1){
            $r = Db::name('admin')->where('admin_id', $data['admin_id'])->delete();
    	}
        if($r){
            $this->success('操作成功！','admin/admin/index');

		}else{
			$this->error(['status'=>-1,'msg'=>'操作失败']);
    	}
    }

        /**
     * 修改管理员密码
     * @return \think\mixed
     */
    public function modify_pwd(){
        $admin_id = input('admin_id/d',0);
        $oldPwd = input('old_pw/s');
        $newPwd = input('new_pw/s');
        $new2Pwd = input('new_pw2/s');
       
        if($admin_id){
            $info = Db::name('admin')->where("admin_id", $admin_id)->find();
            $info['password'] =  "";
            $this->assign('info',$info);
        }
        
         if(Request::isPost()){
            //修改密码
            $enOldPwd = encrypt($oldPwd);
            $enNewPwd = encrypt($newPwd);
            $admin = Db::name('admin')->where('admin_id' , $admin_id)->find();
            if(!$admin || $admin['password'] != $enOldPwd){
                exit(json_encode(array('status'=>-1,'msg'=>'旧密码不正确')));
            }else if($newPwd != $new2Pwd){
                exit(json_encode(array('status'=>-1,'msg'=>'两次密码不一致')));
            }else{
                $row = Db::name('admin')->where('admin_id' , $admin_id)->update(array('password' => $enNewPwd));
                if($row){
                    $this->success('修改成功');
                }else{
                    $this->success('修改失败');
                }
            }
        }
        return $this->fetch();
    }

    /**
     * 管理员登录
     */
    public function login(){
        if(Request::isPost()){
            $data = Request::post();
            if( !captcha_check($data['verify'], 'admin_login' ))
                {
                    return json(['status' => 0, 'msg' => '验证码错误']);
                }
            $adminLogic  = new AdminLogic;
            $return = $adminLogic->login($data['username'], $data['password']);
            return json($return);
        }
        return $this->fetch();
    }

    public function logout(){
        Session::clear();
        Cache::clear();
        return $this->redirect('admin/admin/login');
    }
    /**
     * 验证码获取
     */
    public function verify(){
        $config =    [
            // 验证码字体大小
            'fontSize'    =>    30,    
            // 验证码位数
            'length'      =>    4,   
            // 关闭验证码杂点
            'useNoise'    =>    false, 
            'useCurve' => false,
            'reset' => true
        ];
        $captcha = new Captcha($config);
        return $captcha->entry("admin_login"); 
        exit();
    }
} 