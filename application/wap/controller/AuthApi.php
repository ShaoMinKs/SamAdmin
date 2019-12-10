<?php
namespace app\wap\controller;
use service\JsonService;
use app\wap\model\store\StoreProduct;
use app\wap\model\store\StoreCategory;

class AuthApi extends AuthWap {

    public function get_product_list($keyword = '', $cId = 0,$sId = 0,$priceOrder = '', $salesOrder = '', $news = 0, $first = 0, $limit = 8)
    {
        if(!empty($keyword)){
            $encodedData = str_replace(' ','+',$keyword);
            $keyword = base64_decode(htmlspecialchars($encodedData));
        }
        $model = StoreProduct::validWhere();
        if($cId && $sId){
            $product_ids=\think\Db::name('store_product_cate')->where('cate_id',$sId)->column('product_id');
            if(count($product_ids))
                $model=$model->where('id',"in",$product_ids);
            else
                $model=$model->where('cate_id',-1);
        }elseif($cId){
            $sids = StoreCategory::pidBySidList($cId)?:[];
            $sids[] = $cId;
            $model->where('cate_id','IN',$sids);
        }
        if(!empty($keyword)) $model->where('keyword|store_name','LIKE',"%$keyword%");
        if($news) $model->where('is_new',1);
        $baseOrder = '';
        if($priceOrder) $baseOrder = $priceOrder == 'desc' ? 'price DESC' : 'price ASC';
//        if($salesOrder) $baseOrder = $salesOrder == 'desc' ? 'sales DESC' : 'sales ASC';
        if($salesOrder) $baseOrder = $salesOrder == 'desc' ? 'ficti DESC' : 'ficti ASC';
        if($baseOrder) $baseOrder .= ', ';
        $model->order($baseOrder.'sort DESC, add_time DESC');
        $list = $model->limit($first,$limit)->field('id,store_name,image,sales,ficti,price,stock')->select()->toArray();
        if($list) setView($this->uid,0,$sId,'search','product',$keyword);
        return JsonService::successful($list);
    }
}