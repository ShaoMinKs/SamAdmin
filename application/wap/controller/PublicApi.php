<?php
namespace app\wap\controller;
use service\FileService;
use service\JsonService;
use service\UtilService;
use think\Cache;
use app\wap\model\store\StoreCategory;
use app\wap\model\store\StoreProduct;

class PublicApi {

    /**
     * 获取分类商品
     */
    public function get_category_product_list($limit = 4)
    {
        $cateInfo = StoreCategory::where('is_show',1)->where('pid',0)->field('id,cate_name,pic')
            ->order('sort DESC')->select()->toArray();
        $result = [];
        $StoreProductModel = new StoreProduct;
        foreach ($cateInfo as $k=>$cate){
            $cate['product'] = $StoreProductModel::alias('A')->where('A.is_del',0)->where('A.is_show',1)
                ->where('A.mer_id',0)->where('B.pid',$cate['id'])
                ->join('__STORE_CATEGORY__ B','B.id = A.cate_id')
                ->order('A.is_benefit DESC,A.sort DESC,A.add_time DESC')
                ->limit($limit)->field('A.id,A.image,A.store_name,A.sales,A.price,A.unit_name')->select()->toArray();
            if(count($cate['product']))
                $result[] = $cate;
        }
        return JsonService::successful($result);
    }

/**
 * 获取’is_best‘商品列表
 */
    public function get_best_product_list($first = 0,$limit = 8)
    {
        $list = StoreProduct::validWhere()->where('mer_id',0)->order('is_best DESC,sort DESC,add_time DESC')
            ->limit($first,$limit)->field('id,image,store_name,sales,price,unit_name')->select()->toArray();
        return JsonService::successful($list);
    }
}