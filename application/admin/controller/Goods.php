<?php
namespace app\admin\controller;
use app\admin\logic\GoodsLogic;
use think\Db;
use think\facade\Request;
use app\admin\model\GoodsCategory as GoodsCategoryModel;
use think\Controller;

class Goods extends Base {

    /**
     * categoryList function
     *
     * @return void
     * @商品分类列表
     * @example
     * @author Sam
     * @since 20190410
     */
    public function categoryList(){
        $GoodsLogic  = new GoodsLogic();  
        $types       = Db::name('goods_category')->where('is_show',1)->order('sort_order asc')->select();
        $cat_list    = $GoodsLogic->goods_cat_list($types);
        return $this->fetch('',[
            'cat_list'  => $cat_list
        ]);
    }

      /**
     * 编辑分类详情
     */
    public function doeditCategory(){
        $GoodsCat = new GoodsLogic(); 
        $act        = input('act', 'edit');
        $cat_id     = input('cat_id/d');
        if ($cat_id) {
            $cat_info  = Db::name('goods_category')->where('id',$cat_id)->find();
            $parent_id = $cat_info['parent_id'];
            $this->assign('info', $cat_info);
            $this->assign('cat_id', $cat_id);
        }

        
        $cats = $GoodsCat->good_cat_list(0, 0, false);;
        $this->assign('act', $act);
        $this->assign('cat_select', $cats);
        return $this->fetch();
    }
    

        /**
     * 新增分类详情
     */
    public function doaddCategory(){
        $GoodsCat    = new GoodsLogic(); 
        $act           = input('act', 'add');
        $parent_id     = input('parent_id/d');
        if ($parent_id) {
            $this->assign('parent_id', $parent_id);
        }

        $cats = $GoodsCat->good_cat_list(0, 0, false);
        $this->assign('act', $act);
        $this->assign('cat_select', $cats);
        return $this->fetch();
    }

        /**
     * 分类提交
     */
    public function categoryHandle(){
        $data = input('post.');
        $act  = $data['act'];
        switch ($act) {
            case 'edit':
                $cat           = new GoodsCategoryModel;
                $Goodslogic    = new GoodsLogic(); 
                $res = $cat->allowField(true)->save($data,['id'=>$data['cat_id']]);
                $Goodslogic->refresh_cat($data['cat_id']);
                break;
            case 'add':
                $cat = new GoodsCategoryModel;
                $Goodslogic    = new GoodsLogic(); 
                $cat->data($data);
                $res = $cat->allowField(true)->save();
                $insert_id = $cat->id;
                $Goodslogic->refresh_cat($insert_id);
                break;
            default:
                if(Db::name('goods_category')->where('parent_id',$data['id'])->count() > 0){
                    $this->error('该分类下面还有子分类！请先删除子分类');
                }
                
                $res  = GoodsCategoryModel::destroy($data['id']);
                break;
        }
        if($res){
            $this->success('操作成功！');
        }else{
            $this->error('操作失败！');
        }
    }
}