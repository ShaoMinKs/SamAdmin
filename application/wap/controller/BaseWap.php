<?php
namespace app\wap\controller;
use think\Controller;
use service\JsonService;

class BaseWap extends Controller {
    
        /**
     * 操作失败 弹窗提示
     * @param string $msg
     * @param int $url
     * @param string $title
     */
    protected function failed($msg = '操作失败', $url = 0, $title='信息提示')
    {
        if($this->request->isAjax()){
            exit(JsonService::fail($msg,$url)->getContent());
        }else {
            $this->assign(compact('title', 'msg', 'url'));
            exit($this->fetch('public/error'));
        }
    }

    /**
     * 操作成功 弹窗提示
     * @param $msg
     * @param int $url
     */
    protected function successful($msg = '操作成功', $url = 0, $title='成功提醒')
    {
        if($this->request->isAjax()){
            exit(JsonService::successful($msg,$url)->getContent());
        }else {
            $this->assign(compact('title', 'msg', 'url'));
            exit($this->fetch('public/success'));
        }
    }

    public function _empty($name)
    {
        $url = strtolower($name) == 'index' ? Url::build('Index/index','',true,true) : 0;
        return $this->failed('请求页面不存在!',$url);
    }
}