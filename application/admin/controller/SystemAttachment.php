<?php

namespace app\admin\controller;

use app\admin\model\SystemAttachment as SystemAttachmentModel;
use service\UploadService as Upload;
/**
 * 附件管理控制器
 * Class SystemAttachment
 * @package app\admin\controller\system
 *
 */
class SystemAttachment extends Base
{

    /**
     * 编辑器上传图片
     * @return \think\response\Json
     */
    public function upload()
    {
        $res = Upload::image('upfile','editor/'.date('Ymd'));
        //产品图片上传记录
        $fileInfo = $res->fileInfo->getinfo();
        $thumbPath = Upload::thumb($res->dir);
        SystemAttachmentModel::attachmentAdd($res->fileInfo->getSaveName(),$fileInfo['size'],$fileInfo['type'],$res->dir,$thumbPath,0);
        $info = array(
            "originalName" => $fileInfo['name'],
            "name" => $res->fileInfo->getSaveName(),
            "url" => '.'.$res->dir,
            "size" => $fileInfo['size'],
            "type" => $fileInfo['type'],
            "state" => "SUCCESS"
        );
        echo json_encode($info);
    }
}
