<?php
namespace app\admin\controller;
use service\FileService;

class Uploadify extends Base {


    public function upload(){
        $func = input('func');
        $path = input('path','temp');
		$image_upload_limit_size = config('image_upload_limit_size');
		$uptype = freshCache('storage_info.storage_type');
        $fileType = input('fileType','Images');  //上传文件类型，视频，图片
        if($fileType == 'Flash'){
            $upload = url('Admin/Ueditor/videoUp',array('savepath'=>$path,'pictitle'=>'banner','dir'=>'video'));
            $type = 'mp4,3gp,flv,avi,wmv';
        }elseif($fileType == 'File'){
			$upload = url('Admin/Ueditor/fileUp',array('savepath'=>$path,'pictitle'=>'banner','dir'=>'file'));
			$type = 'mp3,txt,doc,docx,sql,xls';
		}else{
            $upload = url('Admin/Ueditor/imageUp',array('savepath'=>$path,'pictitle'=>'banner','dir'=>'images'));
            $type = 'jpg,png,gif,jpeg';
        }
        $info = array(
        	'num'=> input('num/d'),
        	'fileType'=> $fileType,
            'title' => '',
            'upload' =>$upload,
        	'fileList'=>url('Admin/Uploadify/fileList',array('path'=>$path)),
            'size' => $image_upload_limit_size/(1024 * 1024).'M',
			'type' =>$type,
			'uptype' => $uptype ? $uptype : 'local',
            'input' =>input('input'),
            'func' => empty($func) ? 'undefined' : $func,
        );
        $this->assign('info',$info);
        return $this->fetch();
    }


	    /**
     * 文件状态检查
     * @throws \OSS\Core\OssException
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     */
    public function upstate()
    {
        $post = $this->request->post();
        $ext = strtolower(pathinfo($post['filename'], 4));
        $filename = join('/', str_split($post['md5'], 16)) . '.' . ($ext ? $ext : 'tmp');
        // 检查文件是否已上传
        if (($site_url = FileService::getFileUrl($filename,$post['uptype']))) {
            return json(['data' => ['site_url' => $site_url], 'code' => "IS_FOUND"]);
        }
        // 需要上传文件，生成上传配置参数
        $data = ['uptype' => $post['uptype'], 'file_url' => $filename];
        switch (strtolower($post['uptype'])) {
            case 'local':
                $data['token'] = md5($filename . session_id());
                $data['server'] = FileService::getUploadLocalUrl();
                break;
            case 'qiniu':
                $data['token'] = $this->_getQiniuToken($filename);
                $data['server'] = FileService::getUploadQiniuUrl(true);
                break;
            case 'oss':
                $time = time() + 3600;
                $policyText = [
                    'expiration' => date('Y-m-d', $time) . 'T' . date('H:i:s', $time) . '.000Z',
                    'conditions' => [['content-length-range', 0, 1048576000]],
                ];
                $data['server'] = FileService::getUploadOssUrl();
                $data['policy'] = base64_encode(json_encode($policyText));
                $data['site_url'] = FileService::getBaseUriOss() . $filename;
                $data['signature'] = base64_encode(hash_hmac('sha1', $data['policy'], sysconf('storage_oss_secret'), true));
                $data['OSSAccessKeyId'] = sysconf('storage_oss_keyid');
                break;
        }
        return json(['data' => $data, 'code' => "NOT_FOUND"]);
	}

	    /**
     * 生成七牛文件上传Token
     * @param string $key
     * @return string
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     */
    protected function _getQiniuToken($key)
    {
		$baseUrl = FileService::getBaseUriQiniu();
        $bucket = freshCache('storage_info.storage_qiniu_bucket');
        $accessKey = freshCache('storage_info.storage_qiniu_access_key');
        $secretKey = freshCache('storage_info.storage_qiniu_secret_key');
        $params = [
            "scope"      => "{$bucket}:{$key}", "deadline" => 3600 + time(),
			// "returnBody" => "{\"data\":{\"site_url\":\"{$baseUrl}/$(key)\",\"file_url\":\"$(key)\"}, \"code\": \"SUCCESS\"}",
			"returnBody" => "{\"url\":\"{$baseUrl}/$(key)\",\"file_url\":\"$(key)\", \"state\": \"SUCCESS\"}",
        ];
        $data = str_replace(['+', '/'], ['-', '_'], base64_encode(json_encode($params)));
        return $accessKey . ':' . str_replace(['+', '/'], ['-', '_'], base64_encode(hash_hmac('sha1', $data, $secretKey, true))) . ':' . $data;
    }
	
        /**
     * 删除上传的图片,视频
     */
    public function delupload(){
        $action = input('action','del');
        $filename= input('filename');
        $filename= empty($filename) ? input('url') : $filename;
        $filename= str_replace('../','',$filename);
        $filename= trim($filename,'.');
        $filename= trim($filename,'/');
        if($action=='del' && !empty($filename) && file_exists($filename)){
            $filetype = strtolower(strstr($filename,'.'));
            $phpfile = strtolower(strstr($filename,'.php'));  //排除PHP文件
            $erasable_type = config('erasable_type');  //可删除文件
            if(!in_array($filetype,$erasable_type) || $phpfile){
                exit;
            }
            if(unlink($filename)){
                // $this->deleteWechatImage(input('url'));
                echo 1;
            }else{
                echo 0;
            }
            exit;
        }
    }

    public function fileList()
    {
    	/* 判断类型 */
    	$type = input('type','Images');
    	switch ($type){
    		/* 列出图片 */
    		case 'Images' : $allowFiles = 'png|jpg|jpeg|gif|bmp';break;
    	
    		case 'Flash' : $allowFiles = 'mp4|3gp|flv|avi|wmv|flash|swf';break;
    	
    		/* 列出文件 */
    		default : $allowFiles = '.+';
    	}

    	$path = UPLOAD_PATH.input('path','temp');
    	//echo file_exists($path);echo $path;echo '--';echo $allowFiles;echo '--';echo $key;exit;
    	$listSize = 100000;
    	
    	$key = empty($_GET['key']) ? '' : $_GET['key'];
    	
    	/* 获取参数 */
    	$size = isset($_GET['size']) ? htmlspecialchars($_GET['size']) : $listSize;
    	$start = isset($_GET['start']) ? htmlspecialchars($_GET['start']) : 0;
    	$end = $start + $size;
    	
    	/* 获取文件列表 */
    	$files = $this->getfiles($path, $allowFiles, $key,['public/upload/logo']);
    	if (!count($files)) {
    		echo json_encode(array(
    				"state" => "没有相关文件",
    				"list" => array(),
    				"start" => $start,
    				"total" => count($files)
    		));
    		exit;
    	}
    	
    	/* 获取指定范围的列表 */
    	$len = count($files);
    	for ($i = min($end, $len) - 1, $list = array(); $i < $len && $i >= 0 && $i >= $start; $i--){
    		$list[] = $files[$i];
    	}
    	
    	/* 返回数据 */
    	$result = json_encode(array(
    			"state" => "SUCCESS",
    			"list" => $list,
    			"start" => $start,
    			"total" => count($files)
    	));
    	
    	echo $result;
    }

        /**
     * 遍历获取目录下的指定类型的文件
     * @param $path
     * @param array $files
     * @return array
     */
    private function getfiles($path, $allowFiles, $key,$ignore = array(), &$files = array()){
    	if (!is_dir($path)) return null;
    	if(substr($path, strlen($path) - 1) != '/') $path .= '/';
    	$handle = opendir($path);
    	while (false !== ($file = readdir($handle))) {
    		if ($file != '.' && $file != '..') {
    			$path2 = $path . $file;
    			if (is_dir($path2) && !in_array($path2,$ignore)) {
                    $this->getfiles($path2, $allowFiles, $key,array(), $files);
    			} else {
    				if (preg_match("/\.(".$allowFiles.")$/i", $file) && preg_match("/.*". $key .".*/i", $file)) {
    					$files[] = array(
    						'url'=> '/'.$path2,
    						'name'=> $file,
    						'mtime'=> filemtime($path2)
    					);
    				}
    			}
    		}
    	}
    	return $files;
    }

    
	public function preview(){

		// 此页面用来协助 IE6/7 预览图片，因为 IE 6/7 不支持 base64
		$DIR = 'preview';
		// Create target dir
		if (!file_exists($DIR)) {
			@mkdir($DIR);
		}

		$cleanupTargetDir = true; // Remove old files
		$maxFileAge = 5 * 3600; // Temp file age in seconds

		if ($cleanupTargetDir) {
			if (!is_dir($DIR) || !$dir = opendir($DIR)) {
				die('{"jsonrpc" : "2.0", "error" : {"code": 100, "message": "Failed to open temp directory."}, "id" : "id"}');
			}

			while (($file = readdir($dir)) !== false) {
				$tmpfilePath = $DIR . DIRECTORY_SEPARATOR . $file;
				// Remove temp file if it is older than the max age and is not the current file
				if (@filemtime($tmpfilePath) < time() - $maxFileAge) {
					@unlink($tmpfilePath);
				}
			}
			closedir($dir);
		}

		$src = file_get_contents('php://input');
		if (preg_match("#^data:image/(\w+);base64,(.*)$#", $src, $matches)) {
			$previewUrl = sprintf(
					"%s://%s%s",
					isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off' ? 'https' : 'http',
					$_SERVER['HTTP_HOST'],$_SERVER['REQUEST_URI']
			);
			$previewUrl = str_replace("preview.php", "", $previewUrl);
			$base64 = $matches[2];
			$type = $matches[1];
			if ($type === 'jpeg') {
				$type = 'jpg';
			}

			$filename = md5($base64).".$type";
			$filePath = $DIR.DIRECTORY_SEPARATOR.$filename;

			if (file_exists($filePath)) {
				die('{"jsonrpc" : "2.0", "result" : "'.$previewUrl.'preview/'.$filename.'", "id" : "id"}');
			} else {
				$data = base64_decode($base64);
				$filePathLower = strtolower($filePath);
				if (strstr($filePathLower, '../') || strstr($filePathLower, '..\\') || strstr($filePathLower, '.php')) {
					die('{"jsonrpc" : "2.0", "error" : {"code": 100, "message": "文件上传格式错误 error ！"}}');
				}
				file_put_contents($filePath, $data);
				die('{"jsonrpc" : "2.0", "result" : "'.$previewUrl.'preview/'.$filename.'", "id" : "id"}');
			}
		} else {
			die('{"jsonrpc" : "2.0", "error" : {"code": 100, "message": "un recoginized source"}}');
		}
	}
}