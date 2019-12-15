<?php
namespace app\wap\model\store;
use basic\ModelBasic;
use traits\ModelTrait;

class StoreProductRelation extends ModelBasic {

    use ModelTrait;

    protected $insert = ['add_time'];

    protected function setAddTimeAttr($value)
    {
        return time();
    }

    /**
     * 收藏商品
     */
    public static function productRelation($productId,$uid,$relationType,$category = 'product'){
        if(!$productId) return self::setErrorInfo('产品不存在!');
        $category = strtolower($category);
        $data = ['uid'=>$uid,'product_id'=>$productId,'type'=>$relationType,'category'=>$category];
        if(self::be($data)) return true;
        self::set($data);
        return true;
    }

    /**
     * 取消收藏或点赞
     */
    public static function unProductRelation($productId,$uid,$relationType,$category = 'product')
    {
        if(!$productId) return self::setErrorInfo('产品不存在!');
        $relationType = strtolower($relationType);
        $category = strtolower($category);
        self::where(['uid'=>$uid,'product_id'=>$productId,'type'=>$relationType,'category'=>$category])->delete();
        return true;
    }

    /**
     * 是否收藏或点赞
     */
    public static function isProductRelation($product_id,$uid,$relationType,$category = 'product')
    {
        $type = strtolower($relationType);
        $category = strtolower($category);
        return self::be(compact('product_id','uid','type','category'));
    }

    /**
     * 收藏或点赞数量
     */
    public static function productRelationNum($productId,$relationType,$category = 'product')
    {
        $relationType = strtolower($relationType);
        $category = strtolower($category);
        return self::where('type',$relationType)->where('product_id',$productId)->where('category',$category)->count();
    }

}