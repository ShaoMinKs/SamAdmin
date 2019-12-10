<?php
use think\Db;
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件

/**
 * 密码加密
 */
function encrypt($str){
	return md5(config("AUTH_CODE").$str);
}

/**
 * 模拟tab产生空格
 * @param int $step
 * @param string $string
 * @param int $size
 * @return string
 */
function tab($step = 1, $string = ' ', $size = 4)
{
    return str_repeat($string, $size * $step);
}

// 应用公共文件
if(!function_exists('unThumb')){
    function unThumb($src){
        return str_replace('/s_','/',$src);
    }
}

function setView($uid,$product_id=0,$cate=0,$type='',$product_type = 'product',$content='',$min=20){
    $Db=think\Db::name('store_visit');
    $view=$Db->where(['uid'=>$uid,'product_id'=>$product_id,'product_type'=>$product_type])->field('count,add_time,id')->find();
    if($view && $type!='search'){
        $time=time();
        if(($view['add_time']+$min)<$time){
            $Db->where(['id'=>$view['id']])->update(['count'=>$view['count']+1,'add_time'=>time()]);
        }
    }else{
        $cate = explode(',',$cate)[0];
        $Db->insert([
            'add_time'=>time(),
            'count'=>1,
            'product_id'=>$product_id,
            'cate_id'=>$cate,
            'type'=>$type,
            'uid'=>$uid,
            'product_type'=>$product_type,
            'content'=>$content
        ]);
    }
}
/**
 * 缓存处理
 */
function freshCache($config_key,$data = array()){
	$param = explode('.', $config_key);
    if(empty($data)){
        //如$config_key=shop_info则获取网站信息数组
        //如$config_key=shop_info.logo则获取网站logo字符串
		$config = cache($param[0]);//直接获取缓存文件
        if(empty($config)){
            //缓存文件不存在就读取数据库
			$res = Db::name('config')->where("inc_type",$param[0])->select();
            if($res){
                foreach($res as $k=>$val){
                    $config[$val['name']] = $val['value'];
                }
                cache($param[0],$config);
            }
        }
        if(count($param)>1){
            return $config[$param[1]];
        }else{
            return $config;
        }
    }else{
        //更新缓存
        $result =  Db::name('config')->where("inc_type", $param[0])->select();
        if($result){
            foreach($result as $val){
                $temp[$val['name']] = $val['value'];
            }
            foreach ($data as $k=>$v){
                $newArr = array('name'=>$k,'value'=>trim($v),'inc_type'=>$param[0]);
                if(!isset($temp[$k])){
                    Db::name('config')->insert($newArr);//新key数据插入数据库
                }else{
                    if($v!=$temp[$k])
                        Db::name('config')->where("name", $k)->update($newArr);//缓存key存在且值有变更新此项
                }
            }
            //更新后的数据库记录
            $newRes = Db::name('config')->where("inc_type", $param[0])->select();
            foreach ($newRes as $rs){
                $newData[$rs['name']] = $rs['value'];
            }
        }else{
            foreach($data as $k=>$v){
                $newArr[] = array('name'=>$k,'value'=>trim($v),'inc_type'=>$param[0]);
            }
            Db::name('config')->insertAll($newArr);
            $newData = $data;
        }
        return cache($param[0],$newData);
    }
}