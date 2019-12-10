<?php
namespace app\wap\controller;
use app\wap\model\store\StoreCategory;

class Store extends AuthWap {

    public function index($keyword = '',$cid = '',$sid = '')
    {
        if($keyword != '') $keyword = base64_decode($keyword);
        $keyword = addslashes($keyword);
        $cid = intval($cid);
        $sid = intval($sid);
        $category = null;
        if($sid)
            $category = StoreCategory::get($sid);
        if($cid && !$category)
            $category = StoreCategory::get($cid);
        $this->assign(compact('keyword','cid','sid','category'));
        return $this->fetch();
    }

    public function detail($id){
        
    }
}