<?php
namespace app\admin\controller;
use think\Db;
use think\Controller;
use think\facade\Log;
use \WeChat\Contracts\Tools;
class Push extends Controller {
      // 微信配置
    protected $weconfig = [];

    public function initialize(){
        parent::initialize();
        if(empty($this->weconfig)){
            $config = Db::name('wx_user')->field('wxname,appid,appsecret,token,aeskey')->find();
            if($config){
                $this->weconfig = $config;
            }
        }
    }

        /**
     * 写入日志
     */
    public function logger($content){
    	$logSize=100000;
    	$log="log.txt";
    	if(file_exists($log) && filesize($log)  > $logSize){
    		unlink($log);
    	}
    	file_put_contents($log,date('Y-m-d H:i:s')." ".$content."\n",FILE_APPEND);
    }

    // 入口
    public function index(){
        $api        = new \WeChat\Receive($this->weconfig);
        $msgType    = $api->getMsgType();
        $post_data  = $api->getReceive();
        $openid     = $api->getOpenid();
        switch (trim($msgType)) {
            case 'text':
                $text       = $post_data['Content'];
                return $this->keys("wechat_keys#keys#" .trim($text),false);
                break;
            case 'event':
                if($post_data['Event'] == 'subscribe'){
                    $this->updateFansinfo(true,$openid);
                    return $this->keys('wechat_keys#keys#subscribe', true);
                }elseif ($post_data['Event'] == 'unsubscribe') {
                    $this->updateFansinfo(false,$openid);
                }elseif($post_data['Event'] == 'CLICK') {
                    return $this->keys($post_data['EventKey']);     
                }elseif($post_data['Event'] == 'SACN'){
                    return $this->keys("wechat_keys#keys#{$post_data['EventKey']}", true);
                }elseif($post_data['Event'] == 'scancode_waitmsg'){
                    if (isset($post_data['ScanCodeInfo'])) {
                        $post_datae['ScanCodeInfo'] = (array)$post_data['ScanCodeInfo'];
                        if (!empty($post_data['ScanCodeInfo']['ScanResult'])) {
                            return $this->keys("wechat_keys#keys#{$post_data['ScanCodeInfo']['ScanResult']}");
                        }
                    }
                    return false;
                }
                break;
            default:
                return 'success';
                break;
        }
        return 'success';
        
    }

    /**
     * 关键字处理
     */
    public function keys($rule, $isLastReply = false){
        $api                         = new \WeChat\Receive($this->weconfig);
        list($table, $field, $value) = explode('#', $rule . '##');
        $info                        = Db::name($table)->where($field,$value)->find();
        if (empty($info['type']) || (array_key_exists('status', $info) && empty($info['status']))) {
            // 切换默认回复
            return $isLastReply ? false : $this->keys('wechat_keys#keys#default', true);
        }
        if(is_array($info = Db::name($table)->where($field, $value)->find()) && isset($info['type'])){
            // 数据状态检查
            if (array_key_exists('status', $info) && empty($info['status'])) {
                return 'success';
            }
            switch ($info['type']) {
                case 'text':
                    return   $api->text($info['content'])->reply();
                    break;
                case 'image':
                if (empty($info['image_url']) || !($media_id = $this->uploadForeverMedia($info['image_url'], 'image',[]))) {
                    return false;
                }
                    return  $api->image($media_id)->reply();
                    break;
                case 'video':
                        if (empty($info['video_url']) || empty($info['video_desc']) || empty($info['video_title'])) {
                            return false;
                        }
                        $videoData = ['title' => $info['video_title'], 'introduction' => $info['video_desc']];
                        if (!($media_id = $this->uploadForeverMedia($info['video_url'], 'video', $videoData))) {
                            return false;
                        }
                        $data = ['media_id' => $media_id, 'title' => $info['video_title'], 'description' => $info['video_desc']];
                        return  $api->video($data)->reply();
                    break;
                case 'music':
                    if (empty($info['music_url']) || empty($info['music_title']) || empty($info['music_desc'])) {
                        return false;
                    }
                    $media_id = empty($info['music_image']) ? '' : $this->uploadForeverMedia($info['music_image'], 'image');
                    $data = ['title' => $info['music_title'], 'description' => $info['music_desc'], 'musicurl' => $info['music_url'], 'hqmusicurl' =>$info['music_url'], 'thumb_media_id' => $media_id];
                    return  $api->music($info['music_title'],$info['music_desc'],request()->domain().$info['music_url'],request()->domain().$info['music_url'],$media_id)->reply();
                case 'news':
                    list($news, $data) = [$this->getNewsById($info['news_id']), []];
                    if (empty($news['articles'])) {
                        return false;
                    }
                   
                    foreach ($news['articles'] as $vo) {
                        $url = url("admin/wechat/review", '', true, true) . "?content={$vo['id']}&type=article";
                        $data[] = ['Url' => $url, 'Title' => $vo['title'], 'PicUrl' => request()->domain().$vo['local_url'], 'Description' => $vo['digest']];
                    }
          
                    return  $api->news($data)->reply();
                    break;  
                default:
                    # code...
                    break;
            }
        }
    }

    


    /**
 * 获取文章
 */
private function getNewsById($id){
    $data        = Db::name('wechat_news')->where(['id' => $id])->find();
    $article_ids = explode(',', $data['article_id']);
    $articles    =  Db::name('wechat_news_article')->whereIn('id', $article_ids)->select();
    $data['articles'] = [];
    foreach ($article_ids as $article_id) {
        foreach ($articles as $article) {
            if (intval($article['id']) === intval($article_id)) {
                unset($article['create_by'], $article['create_at']);
                $data['articles'][] = $article;
            }
        }
    }
    return $data;
}

         /**
     * 上传图片永久素材，返回素材media_id
     * @param string $local_url 文件URL地址
     * @param string $type 文件类型
     * @param array $video_info 视频信息
     * @return string|null
     *  
     **/
    private function uploadForeverMedia($local_url, $type = 'image', $video_info = []){
        $map = ['md5' => md5($local_url), 'appid' => $this->weconfig['appid']];
        if ($media_id = Db::name('wechat_news_media')->where($map)->value('media_id')) {
            return $media_id;
        }
        try {
            // 实例接口
            $wechat = new \WeChat\Media($this->weconfig); 
            // 执行操作
            $result = $wechat->addMaterial($_SERVER['DOCUMENT_ROOT'].$local_url, $type, $video_info); 
            $data   = ['md5' => $map['md5'], 'type' => $type, 'appid' => $map['appid'], 'media_id' => $result['media_id'], 'local_url' => $local_url];
            isset($result['url']) && $data['media_url'] = $result['url'];
            if (Db::name('wechat_news_media')->where($map)->value('media_id')) {
                Db::name('wechat_news_media')->where($map)->update(['type'=>$data['type'],'media_id'=>$data['media_id'],'local_url'=>$local_url]);
            }else{
                Db::name('wechat_news_media')->insert($data);
            }
            return $data['media_id'];    
        } catch (Exception $e){
            // 异常处理
            log::record($e->getMessage(),'error');
        }
    }
    /*
     * 更新粉丝信息
     */
    public function updateFansinfo($subscribe = true,$openid)
    {
        $wechat_user  = new \app\admin\model\WechatFans;
        $api          = new \WeChat\Receive($this->weconfig);
        $userApi      = new \WeChat\User($this->weconfig);
        $userinfo     = $userApi->getUserInfo($openid);
        if ($subscribe) { 
            $user       = Db::name('wechat_fans')->where('openid',$openid)->find();
            if($user){
                $res = $wechat_user->allowfield(true)->save($userinfo,['openid'=>$openid]);
            }else{
                $res = $wechat_user->allowfield(true)->save($userinfo);
            }
        } else {
            $res = $wechat_user->allowfield(true)->save($userinfo,['openid'=>$openid]);
        }
    }
}