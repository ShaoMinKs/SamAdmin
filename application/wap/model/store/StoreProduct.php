<?php
namespace app\wap\model\store;
use basic\ModelBasic;
use traits\ModelTrait;


class StoreProduct extends ModelBasic {

    public static function validWhere()
    {
        return self::where('is_del',0)->where('is_show',1)->where('mer_id',0);
    }

    public function category()
    {
        return $this->belongsTo('store_category', 'cate_id');
    }

       /**
     * 新品产品
     * @param string $field
     * @param int $limit
     * @return false|\PDOStatement|string|\think\Collection
     */
    public static function getNewProduct($field = '*',$limit = 0)
    {
        $model = self::where('is_new',1)->where('is_del',0)->where('mer_id',0)
            ->where('stock','>',0)->where('is_show',1)->field($field)
            ->order('sort DESC, id DESC');
        if($limit) $model->limit($limit);
        return $model->select();
    }

    /**
     * 热卖产品
     * @param string $field
     * @param int $limit
     * @return false|\PDOStatement|string|\think\Collection
     */
    public static function getHotProduct($field = '*',$limit = 0)
    {
        $model = self::where('is_hot',1)->where('is_del',0)->where('mer_id',0)
            ->where('stock','>',0)->where('is_show',1)->field($field)
            ->order('sort DESC, id DESC');
        if($limit) $model->limit($limit);
        return $model->select();
    }

    /**
     * 精品产品
     * @param string $field
     * @param int $limit
     * @return false|\PDOStatement|string|\think\Collection
     */
    public static function getBestProduct($field = '*',$limit = 0)
    {
        $model = self::where('is_best',1)->where('is_del',0)->where('mer_id',0)
            ->where('stock','>',0)->where('is_show',1)->field($field)
            ->order('sort DESC, id DESC');
        if($limit) $model->limit($limit);
        return $model->select();
    }

}