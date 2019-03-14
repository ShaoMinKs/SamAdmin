<?php
namespace app\admin\controller;
use think\Db;
use think\facade\Cache;
use think\facade\Request;
use WeChat\Contracts\Tools;
use think\facade\Log;

class Wechat extends Base
{
    // 微信配置
    protected $weconfig = [];


    public function initialize()
    {
        parent::initialize();
        if (empty($this->weconfig)) {
            $config = Db::name('wx_user')->field('wxname,appid,appsecret,token,aeskey')->find();
            if ($config) {
                $this->weconfig = $config;
            }
        }
    }
    /**
     * 公众号授权
     */
    public function weConfig()
    {
        $info = Db::name('wx_user')->find();
        return $this->fetch('weConfig', [
            'info' => $info
        ]);
    }

    /**
     * 公众号配置提交
     */
    public function updateConfig()
    {
        $data = Request::post();
        $validate = new \app\admin\validate\WxUser;
        $WxUser = new \app\admin\model\WxUser;
        $result = $validate->check($data);
        if ($result != true) {
            $this->error($validate->getError());
        }
        if ($data['id']) {
            $res = $WxUser->allowField(true)->save($data, ['id' => $data['id']]);
        } else {
            $res = $WxUser->allowField(true)->save($data);
        }
        if ($res) {
            $this->success('操作成功！');
        } else {
            $this->error('操作失败');
        }

    }

    /**
     * 关注默认回复
     */
    public function keysSubscribe()
    {
        $vo = Db::name('wechat_keys')->where('keys', 'subscribe')->find();
        if ($vo) {
            $this->assign('vo', $vo);
        }
        if (Request::isAjax()) {
            $data = Request::post();
            $data['keys'] = 'subscribe';
            $uni = Db::name('wechat_keys')->where('keys', 'subscribe')->find();
            if ($uni) {
                $res = Db::name('wechat_keys')->where('keys', 'subscribe')->update($data);
            } else {
                $res = Db::name('wechat_keys')->where('keys', 'subscribe')->insert($data);
            }
            if ($res) {
                $this->success('操作成功！');
            } else {
                $this->error('操作失败！');
            }
        }
        return $this->fetch();
    }

    /**
     * 无反馈默认回复
     */
    public function keysDefault()
    {
        $vo = Db::name('wechat_keys')->where('keys', 'default')->find();
        if ($vo) {
            $this->assign('vo', $vo);
        }
        if (Request::isAjax()) {
            $data = Request::post();
            $data['keys'] = 'default';
            $uni = Db::name('wechat_keys')->where('keys', 'default')->find();
            if ($uni) {
                $res = Db::name('wechat_keys')->where('keys', 'default')->update($data);
            } else {
                $res = Db::name('wechat_keys')->where('keys', 'default')->insert($data);
            }
            if ($res) {
                $this->success('操作成功！');
            } else {
                $this->error('操作失败！');
            }
        }
        return $this->fetch();
    }

    /**
     * 关键字列表
     */
    public function keys()
    {
        $list = Db::name('wechat_keys')->where('keys', 'not in', ['subscribe', 'default'])->paginate(10);
        return $this->fetch('keys', [
            'list' => $list ? $list : []
        ]);
    }

    /**
     * 关键字添加和编辑
     */
    public function keysEdit()
    {
        $id = Request::param('id/d');
        if ($id) {
            $info = Db::name('wechat_keys')->where('id', $id)->find();
            $this->assign('vo', $info);
        }
        return $this->fetch();
    }

    /**
     * 关键字操作
     */
    public function keysHandle()
    {
        $data = Request::post();
        $keyModel = new \app\admin\model\WechatKeys;
        if (isset($data['field']) && $data['field'] == 'delete') {
            if ($keyModel->destroy($data['id'])) {
                return $this->success('操作成功！');
            }
        }
        switch ($data['act']) {
            case 'add':
                $uni = $keyModel::where('keys', $data['keys'])->find();
                if ($uni) $this->error('改关键字已经存在！');
                $res = $keyModel->allowField(true)->save($data);
                break;
            case 'edit':
                $res = $keyModel->allowField(true)->save($data, ['id' => $data['id']]);
                break;
            default:

                break;
        }
        if ($res) {
            $this->success('操作成功！');
        } else {
            $this->error('操作失败！');
        }
    }

    /**
     * 关键字启动/禁用
     */
    public function forbid()
    {
        $id = Request::param('id/d');
        $res = Db::name('wechat_keys')->where('id', $id)->update([Request::param('field') => Request::param('value')]);
        if ($res) {
            $this->success('操作成功！');
        } else {
            $this->error('操作失败！');
        }
    }

    /**
     * 菜单
     */
    public function defaultMenu()
    {
        $menu = Db::name('wechat_menu')->select();
        if (count($menu) > 0) {
            $menu = Tools::arr2tree($menu, 'index', 'pindex');
        }
        return $this->fetch('default_menu', [
            'list' => $menu
        ]);
    }


    /**
     * 菜单编辑
     */
    public function menu_edit()
    {

        $menu = new \WeChat\Menu($this->weconfig);
        if (Request::isAjax()) {
            $post = Request::post();
            !isset($post['data']) && $this->error('访问出错，请稍候再试！');
             // 删除菜单
            if (empty($post['data'])) {
                try {
                    Db::name('wecaht_menu')->where('1=1')->delete();
                    $menu->delete();
                } catch (\Exception $e) {
                    $this->error('删除取消微信菜单失败，请稍候再试！' . $e->getMessage());
                }
                $this->success('删除并取消微信菜单成功！', '');
            }
            // 数据过滤处理
            try {
                foreach ($post['data'] as &$vo) {
                    isset($vo['content']) && ($vo['content'] = str_replace('"', "'", $vo['content']));
                }
                Db::transaction(function () use ($post) {
                    Db::name('wechat_menu')->where('1=1')->delete();
                    Db::name('wechat_menu')->insertAll($post['data']);
                });
                $this->_push();
            } catch (\Exception $e) {
                $this->error('微信菜单发布失败，请稍候再试！' . $e->getMessage());
            }
            Log::record('微信管理发布微信菜单成功', 'notice');
            $this->success('保存发布菜单成功！', '');
        } else {
            $this->error('非法提交');
        }
    }


    /**
     * 菜单发布
     */
    private function _push()
    {
        $wechat = new \WeChat\Menu($this->weconfig);
        list($map, $field) = [['status' => '1'], 'id,index,pindex,name,type,content'];
        $result = (array)Db::name('wechat_menu')->field($field)->where($map)->order('sort ASC,id ASC')->select();
        foreach ($result as &$row) {
            empty($row['content']) && $row['content'] = uniqid();
            if ($row['type'] === 'miniprogram') {
                list($row['appid'], $row['url'], $row['pagepath']) = explode(',', "{$row['content']},,");
            } elseif ($row['type'] === 'view') {
                if (preg_match('#^(\w+:)?//#', $row['content'])) {
                    $row['url'] = $row['content'];
                } else {
                    $row['url'] = url($row['content'], '', true, true);
                }
            } elseif ($row['type'] === 'event') {
                if (isset($this->menuType[$row['content']])) {
                    list($row['type'], $row['key']) = [$row['content'], "wechat_menu#id#{$row['id']}"];
                }
            } elseif ($row['type'] === 'media_id') {
                $row['media_id'] = $row['content'];
            } else {
                $row['key'] = "wechat_menu#id#{$row['id']}";
                !in_array($row['type'], $this->menuType) && $row['type'] = 'click';
            }
            unset($row['content']);
        }
        $menus = Tools::arr2tree($result, 'index', 'pindex', 'sub_button');
        //去除无效的字段
        foreach ($menus as &$menu) {
            unset($menu['index'], $menu['pindex'], $menu['id']);
            if (empty($menu['sub_button'])) {
                continue;
            }
            foreach ($menu['sub_button'] as &$submenu) {
                unset($submenu['index'], $submenu['pindex'], $submenu['id']);
            }
            unset($menu['type']);
        }
        $wechat->create(['button' => $menus]);
    }

    /**
     * 取消菜单发布
     */
    public function menucancel()
    {
        $wechat = new \WeChat\Menu($this->weconfig);
        try {
            Db::name('wechat_menu')->where('1 = 1')->delete();
            $wechat->delete();
        } catch (\Exception $e) {
            $this->error('菜单取消失败');
        }
        log::record(date('Y-m-d H:i:s') . '----取消菜单成功！', 'notice');
        $this->success('菜单取消成功，重新关注可立即生效！', '');
    }

    /**
     * 图文列表
     */
    public function news()
    {
        $list = Db::name('wechat_news')->where('is_deleted', 0)->select();
        if (count($list) > 0) {
            foreach ($list as $key => &$value) {
                $value = $this->getNewsById($value['id']);
                $newList[] = $value;
            }
        }
        return $this->fetch('news', [
            'list' => $newList ? $newList : ''
        ]);
    }

    /**
     * 推送图文展示
     */
    public function newspush()
    {

        # 获取将要推送的粉丝列表
        switch (strtolower($this->request->get('action', ''))) {
            case 'getuser':
                if ('' === ($params = $this->request->post('group', ''))) {
                    return ['code' => 'SUCCESS', 'data' => []];
                }
                list($ids, $db) = [explode(',', $params), Db::name('wechat_fans')];
                !in_array('0', $ids) && $db->where("concat(',',tagid_list,',') REGEXP '," . join(',|,', $ids) . ",'");
                $list = $db->where(['subscribe' => '1'])->limit(200)->column('nickname');
                foreach ($list as &$vo) {
                    $vo = Tools::emojiDecode($vo);
                }
                return ['code' => "SUCCESS", 'data' => $list];
            default:
                $news_id = $this->request->get('id', '');
                // 显示及图文
                $newsinfo = $this->getNewsById($news_id);
                // Get 请求，显示选择器界面
                if ($this->request->isGet()) {
                    $fans_tags = (array)Db::name('WechatFansTags')->select();
                    $count = Db::name('WechatFans')->where(['subscribe' => '1'])->count();
                    array_unshift($fans_tags, ['id' => 0, 'name' => '全部', 'count' => $count]);
                    return $this->fetch('push', ['vo' => $newsinfo, 'fans_tags' => $fans_tags]);
                }
                // Post 请求，执行图文推送操作
                $post = $this->request->post();
                empty($post['fans_tags']) && $this->error('还没有选择要粉丝对象！');
                // 图文上传操作
                !$this->_uploadWechatNews($newsinfo) && $this->error('图文上传失败，请稍候再试！');
                // 数据拼装
                $data = [];
                if (in_array('0', $post['fans_tags'])) {
                    $data['msgtype'] = 'mpnews';
                    $data['filter'] = ['is_to_all' => true];
                    $data['mpnews'] = ['media_id' => $newsinfo['media_id']];
                } else {
                    $data['msgtype'] = 'mpnews';
                    $data['filter'] = ['is_to_all' => false, 'tag_id' => join(',', $post['fans_tags'])];
                    $data['mpnews'] = ['media_id' => $newsinfo['media_id']];
                }
                $custom = new \WeChat\Custom($this->weconfig);
                if ($custom->massSendAll($data)) {
                    log::record('微信管理图文[{$news_id}]推送成功', 'notice');
                    $this->success('微信图文推送成功！', '');
                }
                $this->error("微信图文推送失败");
        }
    }

    /**
     * 编辑图文消息
     */
    public function newsedit()
    {
        $id = Request::param('id', '');
        if (Request::isGet()) {
            empty($id) && $this->error('参数错误，请稍候再试！');
            $data = $this->getNewsById($id);
            if ($data['articles']) {
                foreach ($data['articles'] as $key => &$value) {
                    $value['content'] = htmlspecialchars_decode($value['content']);
                }
            }
            if ($this->request->get('output') === 'json') {
                return json(['code' => 1, 'data' => $data, 'msg' => '获取数据成功！']);
            }
            return $this->fetch('newsAdd', ['title' => '编辑图文']);
        }
        $data = Request::post();
        $ids = $this->_apply_news_article($data['data']);
        if (!empty($ids)) {
            if (Db::name('wechat_news')->where('id', $id)->update(['article_id' => $ids, 'update_time' => date('Y-m-d H:i:s'), 'create_by' => session('admin_id')])) {
                $this->success('图文更新成功!');
            }
        }
        $this->error('图文更新失败，请稍候再试！');
    }
    /**
     * 上传永久图文
     * 
     */
    private function _uploadWechatNews(&$news)
    {

        $wechat = new \WeChat\Media($this->weconfig);
        foreach ($news['articles'] as &$article) {
            $article['thumb_media_id'] = $this->uploadForeverMedia($article['local_url']);
            $article['content'] = preg_replace_callback(
                "/<img(.*?)src=['\"](.*?)['\"](.*?)\/?>/i",
                function ($matches) {
                    $src = $this->uploadImage($matches[2]);
                    return "<img {$matches[1]}src=\"{$src}\"{$matches[3]} />";
                },
                $article['content']
            );
        }
         // 如果已经上传过，先删除之前的历史记录
        !empty($news['media_id']) && $wechat->delMaterial($news['media_id']);
            // 上传图文到微信服务器
        $result = $wechat->addNews(['articles' => $news['articles']]);
        if (isset($result['media_id'])) {
            $news['media_id'] = $result['media_id'];
            return Db::name('WechatNews')->where(['id' => $news['id']])->update(['media_id' => $result['media_id']]);
        }
        Log::error("上传永久图文失败");
        return false;
    }

    /**
     * 上传图片到微信服务器
     * @param string $local_url 图文地址
     * @return string
     * @throws \WeChat\Exceptions\InvalidResponseException
     * @throws \WeChat\Exceptions\LocalCacheException
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     */
    public function uploadImage($local_url)
    {
        $wechat = new \WeChat\Media($this->weconfig);
        $map = ['md5' => md5($local_url)];
        if (($media_url = Db::name('WechatNewsImage')->where($map)->value('media_url'))) {
            return $media_url;
        }
        $info = $wechat->uploadImg($_SERVER['DOCUMENT_ROOT'] . $local_url);
        $data = ['local_url' => $local_url, 'media_url' => $info['url'], 'md5' => $map['md5']];
        if (Db::name('WechatNewsImage')->where($map)->value('media_id')) {
            Db::name('WechatNewsImage')->where($map)->update(['type' => $data['type'], 'media_id' => $data['media_id'], 'local_url' => $local_url]);
        } else {
            Db::name('WechatNewsImage')->insert($data);
        }
        return $info['url'];
    }


    /**
     * 上传图片永久素材，返回素材media_id
     * @param string $local_url 文件URL地址
     * @param string $type 文件类型
     * @param array $video_info 视频信息
     * @return string|null
     *
     **/
    private function uploadForeverMedia($local_url, $type = 'image', $video_info = [])
    {
        $map = ['md5' => md5($local_url), 'appid' => $this->weconfig['appid']];
        if ($media_id = Db::name('wechat_news_media')->where($map)->value('media_id')) {
            return $media_id;
        }
        try {
        // 实例接口
            $wechat = new \WeChat\Media($this->weconfig);
            // 执行操作
            $result = $wechat->addMaterial($_SERVER['DOCUMENT_ROOT'] . $local_url, $type, $video_info);
            $data = [
                'md5' => $map['md5'], 'type' => $type, 'appid' => $map['appid'], 'media_id' => $result['media_id'],
                'local_url' => $local_url
            ];
            isset($result['url']) && $data['media_url'] = $result['url'];
            if (Db::name('wechat_news_media')->where($map)->value('media_id')) {
                Db::name('wechat_news_media')->where($map)->update(['type' => $data['type'], 'media_id' => $data['media_id'], 'local_url' => $local_url]);
            } else {
                Db::name('wechat_news_media')->insert($data);
            }
            return $data['media_id'];
        } catch (Exception $e) {
            // 异常处理
            log::record($e->getMessage(), 'error');
        }
    }

    /**
     * 标签
     */
    public function weTags()
    {
        if (Request::isGet()) {
            $keyword = Request::param('name');
            $map = [];
            if ($keyword) {
                $map[] = ['name', 'like', "%{$keyword}%"];
            }
        }
        $list = Db::name('wechat_fans_tags')->where($map)->select();
        return $this->fetch('weTags', [
            'list' => $list
        ]);
    }


    /**
     * 添加标签
     */
    public function tagsAdd()
    {
        if (Request::isGet()) {
            return $this->fetch();
        }
        $name = Request::post('name', '');
        empty($name) && $this->error('粉丝标签名不能为空!');
        if (Db::name('wechat_fans_tags')->where('name', $name)->count() > 0) {
            $this->error('粉丝标签标签名已经存在, 请使用其它标签名!');
        }
        $wechat = new \WeChat\Tags($this->weconfig);
        if (false === ($result = $wechat->createTags($name)) && isset($result['tag'])) {
            $this->error("添加粉丝标签失败. ");
        }
        $result['tag']['count'] = 0;
        $result['tag']['appid'] = $this->weconfig['appid'];
        if (Db::name('wechat_fans_tags')->insert($result['tag'])) {
            $this->success('添加粉丝标签成功!', '');
        }
        $this->error('粉丝标签添加失败, 请稍候再试!');
    }

    /**
     * 远程获取标签
     */
    public function sync()
    {
        $appid = $this->weconfig['appid'];
        $wechat = new \WeChat\Tags($this->weconfig);
        Db::name('WechatFansTags')->where(['appid' => $appid])->delete();
        $result = $wechat->getTags();
        foreach (array_chunk($result['tags'], 100) as $list) {
            foreach ($list as &$vo) {
                $vo['appid'] = $appid;
            }
            Db::name('WechatFansTags')->insertAll($list);
        }
        $this->success('同步公众号标签成功！');

    }

    /**
     * 增加或更新粉丝信息
     * @param array $user
     * @return bool
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     */
    public static function set(array $user)
    {
        if (!empty($user['subscribe_time'])) {
            $user['subscribe_at'] = date('Y-m-d H:i:s', $user['subscribe_time']);
        }
        if (isset($user['tagid_list']) && is_array($user['tagid_list'])) {
            $user['tagid_list'] = join(',', $user['tagid_list']);
        }
        foreach (['country', 'province', 'city', 'nickname', 'remark'] as $field) {
            isset($user[$field]) && $user[$field] = Tools::emojiEncode($user[$field]);
        }
        unset($user['privilege'], $user['groupid']);
        if (Db::name('wechat_fans')->where('openid', $user['openid'])->find()) {
            unset($user['openid']);
            return Db::name('wechat_fans')->where('openid', $user['openid'])->update($user);
        } else {
            return Db::name('wechat_fans')->insert($user);
        }

    }

    /**
     * 设置黑名单
     */
    public function backadd()
    {
        try {
            $ids = Request::post('id', '');
            empty($ids) && $this->error('没有需要操作的数据!');
            $openids = Db::name('wechat_fans')->whereIn('id', explode(',', $ids))->column('openid');
            empty($openids) && $this->error('没有需要操作的数据!');
            $wechat = new \WeChat\User($this->weconfig);
            $wechat->batchBlackList($openids);
            Db::name('wechat_fans')->whereIn('openid', $openids)->setField('is_black', '1');
        } catch (\Exception $e) {
            $this->error("设置黑名单失败，请稍候再试！");
        }
        $this->success('设置黑名单成功！', '');
    }

    /**
     * 黑名单列表
     */
    public function weBlack()
    {
        $get = Request::get();
        $map = [];
        if (isset($get['sex']) && $get['sex'] !== '') {
            $map[] = ['sex', '=', $get['sex']];
        }
        foreach (['nickname', 'country', 'province', 'city'] as $key) {
            if (isset($get[$key]) && $get[$key] !== '') {
                $map[] = [$key, 'like', "%{$get[$key]}%"];
            }
        }

        if (isset($get['create_at']) && $get['create_at'] !== '') {
            list($start, $end) = explode(' - ', $get['create_at']);
            $map[] = ['subscribe_at', 'between', ["{$start} 00:00:00", "{$end} 23:59:59"]];
        }
        $list = Db::name('wechat_fans')->where('is_black', 1)->where($map)->select();
        if (isset($get['tag']) && $get['tag'] !== '') {
            $list = Db::name('wechat_fans')->where('is_black', 1)->where("concat(',',tagid_list,',') like :tag", ['tag' =>
                "%,{$get['tag']},%"])->select();
        }
        $tags = Db::name('WechatFansTags')->column('id,name');
        foreach ($list as &$vo) {
            list($vo['tags_list'], $vo['nickname']) = [[], Tools::emojiDecode($vo['nickname'])];
            foreach (explode(',', $vo['tagid_list']) as $tag) {
                if ($tag !== '' && isset($tags[$tag])) {
                    $vo['tags_list'][$tag] = $tags[$tag];
                } elseif ($tag !== '') {
                    $vo['tags_list'][$tag] = $tag;
                }
            }
        }
        return $this->fetch('weBlack', [
            'list' => $list,
            'tags' => $tags
        ]);
    }

    /**
     * 取消黑名
     */
    public function backdel()
    {
        $ids = Request::post('id', '');
        empty($ids) && $this->error('没有需要操作的数据!');
        $openids = Db::name('wechat_fans')->whereIn('id', explode(',', $ids))->column('openid');
        empty($openids) && $this->error('没有需要操作的数据!');
        $wechat = new \WeChat\User($this->weconfig);
        try {
            $wechat->batchUnblackList($openids);
            Db::name('wechat_fans')->whereIn('openid', $openids)->setField('is_black', '0');
        } catch (\Exception $e) {
            $this->error("设备黑名单失败，请稍候再试！" . $e->getMessage());
        }
        $this->success("已成功将 " . count($openids) . " 名粉丝从黑名单中移除!", '');
    }

    /**
     * 远程获取粉丝
     */
    public function fans_sync()
    {
        Db::startTrans();
        try {
            Db::name('wechat_fans')->where('1=1')->delete();
            $this->user_sync($next_openid = '');
            // 提交事务
            Db::commit();
        } catch (\Exception $e) {
            // 回滚事务
            Db::rollback();
            log::record($e->getMessage(), 'error');
            $this->error('同步粉丝记录失败，请稍候再试！' . $e->getMessage());
        }
        $this->success('同步获取所有粉丝成功！', '');

    }

    /**
     * 同步所有粉丝数据记录
     * @param string $next_openid
     * @return bool
     * @throws \WeChat\Exceptions\InvalidResponseException
     * @throws \WeChat\Exceptions\LocalCacheException
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     */
    public function user_sync($next_openid = '')
    {
        $wechat = new \WeChat\User($this->weconfig);;
        $result = $wechat->getUserList($next_openid);
        if (empty($result['data']['openid'])) {
            return false;
        }
        foreach (array_chunk($result['data']['openid'], 100) as $openids) {
            foreach ($wechat->getBatchUserInfo($openids)['user_info_list'] as $user) {
                if (false === self::set($user)) {
                    return false;
                }
                if ($result['next_openid'] === $user['openid']) {
                    unset($result['next_openid']);
                }
            }
        }
        return empty($result['next_openid']) ? true : self::user_sync($result['next_openid']);
    }

    /**
     *
     * 粉丝
     */
    public function weFans()
    {
        $get = Request::get();
        $map = [];
        if (isset($get['sex']) && $get['sex'] !== '') {
            $map[] = ['sex', '=', $get['sex']];
        }
        foreach (['nickname', 'country', 'province', 'city'] as $key) {
            if (isset($get[$key]) && $get[$key] !== '') {
                $map[] = [$key, 'like', "%{$get[$key]}%"];
            }
        }

        if (isset($get['create_at']) && $get['create_at'] !== '') {
            list($start, $end) = explode(' - ', $get['create_at']);
            $map[] = ['subscribe_at', 'between', ["{$start} 00:00:00", "{$end} 23:59:59"]];
        }
        $list = Db::name('wechat_fans')->where('is_black', 0)->where($map)->select();
        if (isset($get['tag']) && $get['tag'] !== '') {
            $list = Db::name('wechat_fans')->where('is_black', 0)->where("concat(',',tagid_list,',') like :tag", ['tag' =>
                "%,{$get['tag']},%"])->select();
        }
        $tags = Db::name('WechatFansTags')->column('id,name');
        foreach ($list as &$vo) {
            list($vo['tags_list'], $vo['nickname']) = [[], Tools::emojiDecode($vo['nickname'])];
            foreach (explode(',', $vo['tagid_list']) as &$tag) {
                if ($tag !== '' && isset($tags[$tag])) {
                    $vo['tags_list'][$tag] = $tags[$tag];
                } elseif ($tag !== '') {
                    $vo['tags_list'][$tag] = $tag;
                }
            }
        }
        return $this->fetch('weFans', [
            'list' => $list,
            'tags' => $tags,
        ]);
    }


    /**
     * 标签选择
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function tagset()
    {
        $tags = $this->request->post('tags', '');
        $fans_id = $this->request->post('fans_id', '');
        $fans = Db::name('WechatFans')->where(['id' => $fans_id])->find();
        empty($fans) && $this->error('需要操作的数据不存在!');
        try {
            $wechat = new \WeChat\Tags($this->weconfig);
            foreach (explode(',', $fans['tagid_list']) as $tagid) {
                is_numeric($tagid) && $wechat->batchUntagging([$fans['openid']], $tagid);
            }
            foreach (explode(',', $tags) as $tagid) {
                is_numeric($tagid) && $wechat->batchTagging([$fans['openid']], $tagid);
            }
            Db::name('WechatFans')->where(['id' => $fans_id])->setField('tagid_list', $tags);
        } catch (\Exception $e) {
            $this->error('粉丝标签设置失败, 请稍候再试!');
        }
        $this->success('粉丝标签成功!', '');
    }

    /**
     * 编辑标签
     */
    public function tagEdit()
    {
             // 显示编辑界面
        if (Request::isGet()) {
            $id = Request::param('id/d');
            $vo = Db::name('WechatFansTags')->where('id', $id)->find();
            return $this->fetch('tags_add', [
                'vo' => $vo ? $vo : ''
            ]);
        }
        $wechat = new \WeChat\Tags($this->weconfig);
         // 接收提交的数据
        $id = Request::post('id', '0');
        $name = Request::post('name', '');
        $info = Db::name('WechatFansTags')->where(['name' => $name])->find();
        if (!empty($info)) {
            if (intval($info['id']) === intval($id)) {
                $this->error('粉丝标签名没有改变, 无需修改!');
            }
            $this->error('标签已经存在, 使用其它名称再试!');
        }
        try {
            $wechat->updateTags($id, $name);
            Db::name('WechatFansTags')->where('id', $id)->update(['name' => $name]);
        } catch (\Exception $e) {
            $this->error('编辑标签失败, 请稍后再试!' . $e->getMessage());
        }
        $this->success('编辑标签成功!', '');
    }


    /**
     * 删除粉丝标签
     * @throws \WeChat\Exceptions\InvalidResponseException
     * @throws \WeChat\Exceptions\LocalCacheException
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     */
    public function tagDel()
    {
        $wechat = new \WeChat\Tags($this->weconfig);
        foreach (explode(',', Request::post('id', '')) as $id) {
            if ($wechat->deleteTags($id)) {
                Db::name('WechatFansTags')->where(['id' => $id])->delete();
            } else {
                $this->error('移除粉丝标签失败，请稍候再试！');
            }
        }
        $this->success('移除粉丝标签成功！', '');
    }

    /**
     * 获取文章
     */
    private function getNewsById($id)
    {
        $data = Db::name('wechat_news')->where(['id' => $id])->find();
        $article_ids = explode(',', $data['article_id']);
        $articles = Db::name('wechat_news_article')->whereIn('id', $article_ids)->select();
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
     * 新增图文
     */
    public function newsAdd()
    {
        if (Request::isGet()) {
            return $this->fetch('newsAdd', ['title' => '新建图文']);
        }
        if (Request::isPost()) {
            $data = Request::post();
            if (($ids = $this->_apply_news_article($data['data'])) && !empty($ids)) {
                $post = ['article_id' => $ids, 'create_by' => session('admin_id')];
                if (Db::name('wechat_news')->insert($post)) {
                    $this->success('图文添加成功!', 'admin/wechat/news');
                }
            }
            $this->error('图文添加失败，请稍候再试！');
        }
    }

    /**
     * 图文更新操作
     * @param array $data
     * @param array $ids
     * @return string
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     */
    protected function _apply_news_article($data, $ids = [])
    {
        foreach ($data as &$vo) {
            $vo['create_by'] = session('user.id');
            $vo['create_at'] = date('Y-m-d H:i:s');
            if (empty($vo['digest'])) {
                $vo['digest'] = mb_substr(strip_tags(str_replace(["\s", '　'], '', htmlspecialchars_decode($vo['content']))), 0, 120);
            }
            if (empty($vo['id'])) {
                $result = $id = Db::name('wechat_news_article')->insertGetId($vo);
            } else {
                $id = intval($vo['id']);
                $result = Db::name('wechat_news_article')->where('id', $id)->update($vo);
            }
            if ($result !== false) {
                $ids[] = $id;
            }
        }
        return join(',', $ids);
    }


    /**
     * 删除图文
     */
    public function newsdel()
    {
        $id = Request::param('id/d');
        if (Db::name('wechat_news')->where('id', $id)->update(['is_deleted' => 1, 'update_time' => date('Y-m-d H:i:s')])) {
            $this->success('操作成功');
        } else {
            $this->error('操作失败');
        }
    }

    /**
     * 图文选择
     */
    public function newsSelect()
    {
        $list = Db::name('wechat_news')->where('is_deleted', 0)->select();
        if (count($list) > 0) {
            foreach ($list as $key => &$value) {
                $value = $this->getNewsById($value['id']);
                $newList[] = $value;
            }
        }
        return $this->fetch('newsSelect', [
            'list' => $newList ? $newList : ''
        ]);
    }
    /**
     * 预览
     */
    public function review()
    {
        $content = str_replace("\n", "<br>", $this->request->get('content', '', 'urldecode')); // 内容
        $type = $this->request->get('type', 'text'); // 类型

        // 图文处理
        if ($type === 'news' && is_numeric($content) && !empty($content)) {
            $news = $this->getNewsById($content);
            $this->assign('articles', $news['articles']);
        }
            // 文章预览
        if ($type === 'article' && is_numeric($content) && !empty($content)) {
            $article = Db::name('wechat_news_article')->where('id', $content)->find();
            $article['content'] = htmlspecialchars_decode($article['content']);
            if (!empty($article['content_source_url'])) {
                $this->redirect($article['content_source_url']);
            }
            $this->assign('vo', $article);
        }
        $this->assign('type', $type);
        $this->assign('content', $content);
        $this->assign($this->request->get());
            // 渲染模板并显示
        return $this->fetch();
    }
}