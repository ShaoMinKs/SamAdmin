<?php
namespace app\admin\controller;
use think\Db;
use think\facade\Cache;
use think\facade\Request;
use app\admin\logic\ArticleCatLogic;
use app\admin\model\ArticleCat;
use app\admin\model\Link;
use app\admin\model\Article as Articlemodel;
use app\admin\validate\Article as validateArtice;

class Article extends Base {

    public function categoryList(){
        $ArticleCat = new ArticleCatLogic(); 
        $cat_list = $ArticleCat->article_cat_list(0, 0, false);
        return $this->fetch('category_list',[
            'list' => $cat_list,
            'keyword' => input('param.keyword')
        ]);
    }

    /**
     * 新增分类详情
     */
    public function category(){
        $ArticleCat    = new ArticleCatLogic(); 
        $act           = input('act', 'add');
        $parent_id     = input('parent_id/d');
        if ($parent_id) {
            $this->assign('parent_id', $parent_id);
        }

        $cats = $ArticleCat->article_cat_list(0, 0, false);
        $this->assign('act', $act);
        $this->assign('cat_select', $cats);
        return $this->fetch();
    }

    /**
     * 文章提交
     */
    public function articleHandle(){
        if(Request::isAjax()){
            $data = Request::post();
            $article   = new Articlemodel();
            if(isset($data['publish_time']))  $data['publish_time'] = strtotime($data['publish_time']);
            if(isset($data['is_open']))       $data['is_open']      = $data['is_open'] == 'on' ? '1' : 0;
            $validate = new validateArtice();
            $result = $validate->scene($data['act'])->check($data);
            if ($result !== true) {
                $this->error($validate->getError());
            }
            switch ($data['act']) {
                case 'add':
                    $find  = Db::name('article')->where(['cat_id'=>$data['cat_id'],'title'=>$data['title']])->field('article_id')->find();
                    if($find){
                        $this->error('该分类下已有此标题');
                    }
                    $res = $article->allowField(true)->save($data);
                    break;
                case 'edit':
                    $article_id  = $data['id'];
                    if(!$article_id) {
                        $this->error('缺少参数！');
                    }
                    $res = $article->allowField(true)->save($data,['article_id'=>$article_id]);
                    break;
                default:
                case 'del':
                    $article_id  = $data['id'];
                    $res         = $article::destroy($article_id);
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
     * 提交链接
     */
    public function linkHandle(){
        if(Request::isAjax()){
            $data    = Request::post();
            $data['is_show'] =  isset($data['is_show']) ? 1 : 0;
            $link    = new Link();
            $validate = new \app\admin\validate\Link;
            if(!$validate->scene($data['act'])->check($data)){
                $this->error($validate->getError());
            }
            switch ($data['act']) {
                case 'add':
                $res = $link->allowField(true)->save($data);
                    break;
                case 'edit':
                $link_id  = $data['link_id'];
                if(!$link_id) {
                    $this->error('缺少参数！');
                }
                $res = $link->allowField(true)->save($data,['link_id'=>$link_id]);
                break;
                case 'del':
                $link_id  = $data['link_id'];
                $res      = $link::destroy($link_id);
                default:
                    # code...
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
     * 文章详情
     */
    public function article(){
        $ArticleCat = new ArticleCatLogic(); 
        $cats = $ArticleCat->article_cat_list(0, 0, false);
        $id  = input('article_id/d');
        $act  = input('act');
        if($id){
            $info  = Db::name('article')->where('article_id',$id)->field('article_id,cat_id,title,content,is_open,thumb,publish_time,keywords')->find();
            $this->assign('info',$info);
        }
        return $this->fetch('article',[
            'cats' => $cats,
            'act'  => $act
        ]);
    }
    
    /**
     * 编辑分类详情
     */
    public function editCategory(){
        $ArticleCat = new ArticleCatLogic(); 
        $act        = input('act', 'edit');
        $cat_id     = input('cat_id/d');
        if ($cat_id) {
            $cat_info  = Db::name('article_cat')->where('cat_id',$cat_id)->find();
            $parent_id = $cat_info['parent_id'];
            $this->assign('info', $cat_info);
            $this->assign('cat_id', $cat_id);
        }

        $cats = $ArticleCat->article_cat_list(0, 0, false);
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
                $cat = new ArticleCat();
                $res = $cat->allowField(true)->save($data,['cat_id'=>$data['cat_id']]);
                break;
            case 'add':
                $cat = new ArticleCat();
                $cat->data($data);
                $res = $cat->allowField(true)->save();
                break;
            default:
                if(Db::name('article_cat')->where('parent_id',$data['cat_id'])->count() > 0){
                    $this->error('该分类下面还有子分类！请先删除子分类');
                }
                if(Db::name('article')->where('cat_id',$data['cat_id'])->count() > 0){
                    $this->error('该分类下面还有文章！请先删除文章');
                }
                $res  = ArticleCat::destroy($data['cat_id']);
                break;
        }
        if($res){
            $this->success('操作成功！');
        }else{
            $this->error('操作失败！');
        }
    }

    /**
     * 文章列表
     */
    public function articleList(){
        $count = Db::name('article')->count();
        $ArticleCat = new ArticleCatLogic(); 
        $cats = $ArticleCat->article_cat_list(0, 0, false);
        $this->assign('count',$count);
        $this->assign('cats',$cats);
        return $this->fetch();
    }

    /**
     * 友情链接列表
     */
    public function linkList(){
        $count  = Db::name('link')->count();
        return $this->fetch('linkList',[
            'counts' => $count ? $count : 0
        ]);
    }

    /**
     * ajax获取链接列表
     */
    public function getLinks(){
        $page    = input('param.page/d');
        $limit   = input('param.limit/d');
        $keyword = input('param.keywords');
        $map     = [];
        if($keyword){
            $map[] = ['link_name','like',"%{$keyword}%"];
        }
        $list   = Db::name('link')->where($map)->page($page,$limit)->field('link_id,link_name,link_url,is_show,orderby')->order('orderby desc')->select();
        $count = Db::name('link')->count();
        return json([
            'code'  => 0,
            'msg'   => '',
            'count' => $count,
            'data'  => isset($list) ? $list : ''
        ]);
    }

    /**
     * 新增/编辑链接
     */
    public function link(){
        $act     = Request::param('act');
        $link_id = Request::param('link_id');
        if($act == 'edit' && $link_id){
            $info = Db::name('link')->where('link_id',$link_id)->find();
            if($info){
                $this->assign('info',$info);
            }
        }
        return $this->fetch();
    }
    /**
     * ajax获取文章列表
     */
    public function getArticles(){
        $page    = input('param.page/d');
        $limit   = input('param.limit/d');
        $keyword = input('param.name');
        $cat     = input('param.cat_id');
        $map     = [];
        if($keyword){
            $map[] = ['title|keywords','like',"%{$keyword}%"];
        }
        if($cat){
            $map[] = ['cat_id','=',$cat];
        }
        $res = Db::name('article')->where($map)->page($page,$limit)->field('article_id,title,cat_id,is_open,publish_time')->order('publish_time desc')->select();
        if($res) foreach ($res as $key => &$value) {
            $value['publish_time'] = date('Y-m-d H:i:s',$value['publish_time']);   
            $value['cat']          = Db::name('article_cat')->where('cat_id',$value['cat_id'])->value('cat_name'); 
            $list[] = $value;
        }
        $count = Db::name('article')->where('is_open',1)->count();
        return json([
            'code'  => 0,
            'msg'   => '',
            'count' => $count,
            'data'  => isset($list) ? $list : ''
        ]);
    }
}