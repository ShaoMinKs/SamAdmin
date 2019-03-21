<?php
namespace app\common\logic;
use think\Db;
use think\facade\Request;
use think\facade\Cache;

class AdminLogic {

    public function login($username, $password){
        if (empty($username) || empty($password)) {
            return ['status' => 0, 'msg' => '请填写账号密码'];
        }
        $map['user_name'] = $username;
        $map['password'] = encrypt($password);
        $admin           = Db::name('admin')
                          ->where($map)
                          ->find();
        if (!$admin) {
             return ['status' => 0, 'msg' => '账号密码不正确'];
        }
        $admin['act_list']  = Db::name('admin_role')->where('role_id',$admin['role_id'])->value('act_list');
        Db::name('admin')->where('admin_id', $admin['admin_id'])->update([
            'last_login' => time(),
            'last_ip' => Request::ip()
        ]);
        session('admin_id', $admin['admin_id']);
        session('last_login_time', $admin['last_login']);
        session('last_login_ip', $admin['last_ip']);
        session('act_list', $admin['act_list']);
        adminLog('后台登录');
        return ['status' => 1, 'url' => url('admin/index/index')];
    }


}