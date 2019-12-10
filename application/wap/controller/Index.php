<?php
namespace app\wap\controller;

class Index extends AuthWap {

    public function index(){
        $this->assign([
            'banner' => [
                ['id'=>84,'title'=>'banner1','url'=>'#','pic'=>'http://sc.hnzzsoft.com/public/uploads/attach/2019/06/28/5d15717758fd9.jpg'
            ],
                ['id'=>169,'title'=>'banner2','url'=>'#','pic'=>'http://sc.hnzzsoft.com/public/uploads/attach/2019/09/28/5d8f04423e78d.jpg'
                ]
        ],
            'menus' => [
                ['id'=>1,'name'=>'砍价','url'=>'wap/store/cutlist','icon'=>'http://datong.crmeb.net/public/uploads/attach/2019/01/15/5c3dc72335ee5.png'],
                ['id'=>1,'name'=>'拼团','url'=>'wap/store/cutlist','icon'=>'http://datong.crmeb.net/public/uploads/attach/2019/01/15/5c3dc7146add5.png'],
                ['id'=>1,'name'=>'秒杀','url'=>'wap/store/cutlist','icon'=>'http://datong.crmeb.net/public/uploads/attach/2019/01/15/5c3dc73feecaf.png'],
                ['id'=>1,'name'=>'优惠','url'=>'wap/store/cutlist','icon'=>'http://datong.crmeb.net/public/uploads/attach/2019/01/15/5c3dc730dead2.png']
            ],
            'roll_news' => [
                ['id'=>1,'info'=>'即将上线，敬请期待','url'=>'#'],
                ['id'=>2,'info'=>'双11特惠','url'=>'#'],
            ]
        ]);
        return $this->fetch();
    }
}