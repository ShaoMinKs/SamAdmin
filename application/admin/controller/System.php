<?php
namespace app\admin\controller;
use think\Db;
use think\facade\Cache;
use think\facade\Request;
use app\admin\model\AdminMenu as MenuModel;

class System extends Base {

    public function index(){
        /*配置列表*/
		$group_list = [
            'site_info' => '站点信息',
          
        ];	
        $inc_type =  input('param.inc_type','site_info');
        $config   =  freshCache($inc_type);
        if($inc_type == 'site_info'){
			$province = Db::name('region')->where(array('parent_id'=>0))->select();
			$city     =  Db::name('region')->where(array('parent_id'=>$config['province']))->select();
            $area     =  Db::name('region')->where(array('parent_id'=>$config['city']))->select();
			$this->assign('province',$province);
			$this->assign('city',$city);
            $this->assign('area',$area);
        }
        $this->assign('group_list',$group_list);
        $this->assign('inc_type',$inc_type);
		$this->assign('config',$config);//当前配置项
		return $this->fetch($inc_type);
    }

    /**
     * 提交操作
     */
    public function handle(){
        $data     = input('post.');
        $inc_type = $data['inc_type'];
        unset($data['inc_type']);
        freshCache($inc_type,$data);
        $this->success("操作成功",url('System/index',array('inc_type'=>$inc_type)));
    }


    /**
     * 权限资源列表
     */
    public function right_list(){
        return $this->fetch('right_list',[
            'count'  => Db::name('system_menu')->where('is_del',0)->count(),
        ]);
    }

    /**
     * 后台菜单列表
     */
    public function admin_menu(){
        $menu  = Db::name('admin_menu')->where('status',1)->select();
        if($menu){
            $menu  = array2level($menu);
        }
        return $this->fetch('admin_menu',[
            'list'  => $menu
        ]);
    }

    /**
     * 编辑后台菜单
     */
    public function editMenu(){
        $id = input('id/d');
        if(!$id) $this->error('缺少参数！');
        $menu  = Db::name('admin_menu')->where('status',1)->select();
        if($menu){
            $menu  = array2level($menu);
        }
        $list    = Db::name('admin_menu')->where('id',$id)->field('id,pid,name,icon,status,act,op,is_default')->find();
        return $this->fetch('editMenu',[
            'info'  => $list,
            'menu_select' => $menu
        ]);
    }

    /**
     * 菜单操作提交
     */
    public function menuHandle(){
        if(Request::isAjax()){
            $data = Request::post();
            $menu               = new MenuModel;
            $data['status']     = isset($data['status']) ? '1' : 0;
            $data['is_default'] = isset($data['is_default']) == 'on' ? '1' : 0;
            switch ($data['act']) {
                case 'edit':
                    $id          = $data['id'];
                    $data['act'] = $data['acts'];
                    if(!$id) {
                        $this->error('缺少参数！');
                    }
                   
                    $res = $menu->allowField(true)->save($data,['id'=>$id]);
                    break;
                case 'add':
                    $data['act'] = $data['acts'];
                    if($data['pid'] == 0 && !empty($data['op'])){
                        $uni = Db::name('admin_menu')->where(['pid'=>0,'op'=>$data['op']])->find();
                        if($uni){
                            $this->error('该分类下已经有此控制器！');
                        }
                    }
                    $uni_name =  Db::name('admin_menu')->where('name',$data['name'])->find();
                    if($uni_name){
                        $this->error('该菜单已经存在！');
                    }
                    $res = $menu->allowField(true)->save($data);
                    break;
                default:
                case 'del':
                    $uni  = Db::name('admin_menu')->where('pid',$data['cat_id'])->find();
                    if($uni){
                        $this->error('改菜单下面还有子菜单，不能删除！');
                    }
                    
                    $is_default = Db::name('admin_menu')->where('id',$data['cat_id'])->value('is_default');
                    if($is_default === 1){
                        $this->error('系统菜单不得删除！');
                    }
                    $res = $menu::destroy($data['cat_id']);
                    break;
            }
            if($res){
                $this->success('操作成功！');
            }else{
                $this->error('操作失败');
            }
        }else{
            $this->error('非法提交！');
        }
    }   

    /**
     * 添加菜单
     */
    public function addMenu(){
        $menu  = Db::name('admin_menu')->where('status',1)->select();
        if($menu){
            $menu  = array2level($menu);
        }
        return $this->fetch('addMenu',[
            'menu_select'  => $menu,
            'pid'          => input('parent_id/d',0)
        ]);
    }
    /**
     * ajax获取权限资源
     */
    public function ajaxGetRight(){
        $page    = input('param.page/d');
        $limit   = input('param.limit/d');
        $type    = input('type',0);
        $keyword = input('param.keyword');
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
        $condition['type'] = $type;
        if($keyword){
            $map[] = ['name|right','like',"%{$keyword}%"];
        }else{
            $map = " 1= 1";
        }
        $right_list = Db::name('system_menu')->where($map)->where('is_del',0)->page($page,$limit)->order('id desc')->select();
        foreach ($right_list as $key => &$value) {
            $value['group']  = $group[$value['group']];
        }
        unset($value);
        $count = Db::name('system_menu')->where($condition)->order('id desc')->count();
        return json([
            'code'  => 0,
            'msg'   => '',
            'count' => $count,
            'data'  => $right_list
        ]);
    }

    /**
     * 编辑权限
     */
    public function edit_right(){
        $group = [
            'system'  =>  '系统设置',
            'content' => '内容管理',
            'member'  => '会员中心',
            'weixin'  => '微信管理',
            'goods'   => '商品中心',
            'finance' => '财务管理',
            'tools'   => '插件工具',
            'order'   =>   '订单中心',
            'count'   => '统计报表',
            'distribut'=>'分销中心',
            'marketing' => '营销推广'
        ];
        $planPath = 'application/admin/controller';
        $planList = array();
        $dirRes   = opendir($planPath);
        while($dir = readdir($dirRes))
        {      
            if(!in_array($dir,array('.','..','.svn')))
            {
                $planList[] = basename($dir,'.php');
            }
        }
        if(Request::isPost()){
            $data = input('post.');
            if(!$data['right']){
                $this->error('请添加权限');
            }
            if($data['auth_code'] != config('AUTH_CODE')){
                $this->error('网络异常');
            }
            switch ($data['act']) {
                case 'edit':
                    $data['right']  = implode(',',$data['right']);
                    $r = Db::name('system_menu')->where('id',$data['id'])->update([
                        'name'  => $data['name'],
                        'right' => $data['right'],
                        'is_del'=> 0
                    ]);
                    break;
                case 'add':
                    $data['right']  = implode(',',$data['right']);
                    unset($data['id']);
                    unset($data['act']);
                    unset($data['auth_code']);
                    if(Db::name('system_menu')->where('name',$data['name'])->count()>0){
                        $this->error('该权限名称已添加，请检查',url('System/right_list'));
                    }
                    $r = Db::name('system_menu')->insert($data);
                break;
                case 'del':
                    $r = Db::name('system_menu')->where('id',$data['id'])->update(['is_del'=>1]);
                break;
                default:
                    # code...
                    break;
            }
           
            if($r){
                $this->success('操作成功！',url('admin/system/right_list'));
            }else{
                $this->error('操作失败！',url('admin/system/right_list'));
            }
            exit;
        }
        $id = input('param.id');
        if($id){
            $info          = Db::name('system_menu')->where(array('id'=>$id))->find();
            $info['right'] = explode(',', $info['right']);
            $this->assign('info',$info);
            $this->assign('act','edit');
        }else{
            $this->assign('act','add');
        }
        return $this->fetch('edit_right',[
            'planList' => $planList,
            'group'    => $group
        ]);
    }

    public function ajax_get_action(){
        $controller  = input('param.controller');
        $class_name  = "app\\admin\\controller\\".$controller;
        $class       = new \ReflectionClass($class_name);
        $method      = $class->getMethods(\ReflectionMethod::IS_PUBLIC);
        foreach ($method as $value) {
            if($value->class == $class_name){
                if($value->name != "__construct" && $value->name != '_initialize'){
                    $select_method[] = $value->name;
                }
            }
        }
        $html = '';
        
        foreach ($select_method as  $v) {
            $html .= "<input  title=".$v." value=".$v." type='checkbox' lay-skin='primary'>";
            if($v && strlen($v)> 18){
                $html .= "<li></li>";
            }
        }
        exit($html);
    }
    /**
     * 清除缓存
     */
    public function cleanCache(){
        delFile('runtime/');
        Cache::clear();
        $quick = input('quick',0);
			if($quick == 1){
				$script = "<script>parent.layer.msg('缓存清除成功', {time:3000,icon: 1});window.parent.location.reload();</script>";
			}else{
				$script = "<script>parent.layer.msg('缓存清除成功', {time:3000,icon: 1});window.location='/index.php/Admin/Index/welcome';</script>";
			}
           	exit($script);
    }
}