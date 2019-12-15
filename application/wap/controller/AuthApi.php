<?php
namespace app\wap\controller;
use service\JsonService;
use app\wap\model\store\StoreProduct;
use app\wap\model\store\StoreCategory;
use app\wap\model\store\StoreCart;
use app\wap\model\store\StoreProductRelation;
use app\wap\model\user\UserAddress;

class AuthApi extends AuthWap {

    /**
     * 获取商品列表
     */
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

    /**
     * 获取用户购物车商品数量
     */
    public function get_cart_num()
    {
        return JsonService::successful('ok',StoreCart::getUserCartNum($this->userInfo['user_id'],'product'));
    }

    /**
     * 添加购物车
     */
    public function set_cart($productId = '',$cartNum = 1,$uniqueId = '')
    {

        if(!$productId || !is_numeric($productId)) return $this->failed('参数错误!');
        $res = StoreCart::setCart($this->userInfo['user_id'],$productId,$cartNum,$uniqueId,'product');
        if(!$res)
            return $this->failed(StoreCart::getErrorInfo('加入购物车失败!'));
        else{
            return $this->successful('ok',['cartId'=>$res->id]);
        }
    }

    /**
     * 收藏商品
     */
    public function collect_product($productId,$category = 'product')
    {
        if(!$productId || !is_numeric($productId)) return $this->failed('参数错误!');
        $res = StoreProductRelation::productRelation($productId,$this->userInfo['user_id'],'collect',$category);
        if(!$res)
            return $this->failed(StoreProductRelation::getErrorInfo('收藏失败!'));
        else
            return $this->successful();
    }

    /**
     * 取消收藏
     */
    public function uncollect_product($productId,$category = 'product')
    {
        if(!$productId || !is_numeric($productId)) return $this->failed('参数错误!');
        $res = StoreProductRelation::unProductRelation($productId,$this->userInfo['user_id'],'collect',$category);
        if(!$res)
            return $this->failed(StoreProductRelation::getErrorInfo('取消收藏失败!'));
        else
            return $this->successful();
    }

    /**
     * 点赞
     */
    public function like_product($productId = '',$category = 'product')
    {
        if(!$productId || !is_numeric($productId)) return $this->failed('参数错误!');
        $res = StoreProductRelation::productRelation($productId,$this->userInfo['user_id'],'like',$category);
        if(!$res)
            return $this->failed(StoreProductRelation::getErrorInfo('点赞失败!'));
        else
            return $this->successful();
    }

    /**
     * 取消点赞
     * 
     */
    public function unlike_product($productId = '',$category = 'product')
    {

        if(!$productId || !is_numeric($productId)) return $this->failed('参数错误!');
        $res = StoreProductRelation::unProductRelation($productId,$this->userInfo['user_id'],'like',$category);
        if(!$res)
            return $this->failed(StoreProductRelation::getErrorInfo('取消点赞失败!'));
        else
            return $this->successful();
    }

    /**
     * 直接购买
     * 
     * 
     */
    public function now_buy($productId = '',$cartNum = 1,$uniqueId = '',$combinationId = 0,$secKillId=0,$bargainId = 0)
    {
        if($productId == '') return $this->failed('参数错误!');
        $res = StoreCart::setCart($this->userInfo['user_id'],$productId,$cartNum,$uniqueId,'product',1,$combinationId,$secKillId,$bargainId);
        if(!$res)
            return $this->failed(StoreCart::getErrorInfo('订单生成失败!'));
        else {
            return $this->successful('ok', ['cartId' => $res->id]);
        }
    }

    /**
     * 地址列表
     */
    public function user_address_list()
    {
        $list = UserAddress::getUserValidAddressList($this->userInfo['user_id'],'id,real_name,phone,province,city,district,detail,is_default');
        return JsonService::successful($list);
    }

    /**
     * 设置默认地址
     */
    public function set_user_default_address($addressId = '')
    {
        if(!$addressId || !is_numeric($addressId)) return JsonService::fail('参数错误!');
        if(!UserAddress::be(['is_del'=>0,'id'=>$addressId,'uid'=>$this->userInfo['user_id']]))
            return JsonService::fail('地址不存在!');
        $res = UserAddress::setDefaultAddress($addressId,$this->userInfo['uid']);
        if(!$res)
            return JsonService::fail('地址不存在!');
        else
            return JsonService::successful();
    }

    public function user_default_address()
    {
        $defaultAddress = UserAddress::getUserDefaultAddress($this->userInfo['user_id'],'id,real_name,phone,province,city,district,detail,is_default');
        if($defaultAddress)
            return JsonService::successful('ok',$defaultAddress);
        else
            return JsonService::successful('empty',[]);
    }
}