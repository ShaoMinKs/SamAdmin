<?php
namespace app\admin\controller;
use think\Db;
use think\facade\Cache;
use think\facade\Request;
use think\Backup;

class Tools extends Base {

    public function index(){
        $dbtables = DB::query('SHOW TABLE STATUS');
        $total    = '';
        foreach ($dbtables as $k => $v) {
            $total += $v['Data_length'] + $v['Index_length'];
        }
        return $this->fetch('index',[
            'count' => count($dbtables),
            'total' => format_bytes($total)
        ]);
    }

    public function ajaxGetTables(){
        $tables  = DB::query('SHOW TABLE STATUS');
        $total   = 0;
        foreach ($tables as $k => $v) {
            $tables[$k]['size'] = format_bytes($v['Data_length'] + $v['Index_length']);
            $total += $v['Data_length'] + $v['Index_length'];
        }
        return json([
            'code'  => 0,
            'msg'   => '',
            'count' => count($tables),
            'data'  => $tables
        ]);
    }


    public function restore(){
        return $this->fetch();
    }

    public function getRestore(){
        $path = config('DATA_BACKUP_PATH');
		if(!is_dir($path)){
			mkdir($path, 0755, true);
		}
		$path = realpath($path);
		$flag = \FilesystemIterator::KEY_AS_FILENAME;
		$glob = new \FilesystemIterator($path,  $flag);
		$list = array();$filenum = $total = 0;
		foreach ($glob as $name => $file) {
			if(preg_match('/^\d{8,8}-\d{6,6}-\d+\.sql(?:\.gz)?$/', $name)){
				$name = sscanf($name, '%4s%2s%2s-%2s%2s%2s-%d');
				$date = "{$name[0]}-{$name[1]}-{$name[2]}";
				$time = "{$name[3]}:{$name[4]}:{$name[5]}";
				$part = $name[6];
				$info = pathinfo($file);
				if(isset($list["{$date} {$time}"])){
					$info = $list["{$date} {$time}"];
					$info['part'] = max($info['part'], $part);
					$info['size'] = $info['size'] + $file->getSize();
				} else {
					$info['part'] = $part;
					$info['size'] = $file->getSize();
				}
				$info['compress'] = ($info['extension'] === 'sql') ? '-' : $info['extension'];
				$info['time']  = strtotime("{$date} {$time}");
				$filenum++;
				$total += $info['size'];
				$list["{$date} {$time}"] = $info;
			}
        }
        foreach ($list as $key => &$value) {
           $value['size'] = format_bytes($value['size'] );
           $value['time'] = date('Y-m-d H:i:s',$value['time']);
        }
        unset($value);
        return json([
            'code'  => 0,
            'msg'   => '',
            'count' => $filenum,
            'data'  => $list
        ]);
    }
        /**
     * 优化
     */
    public function optimize() {
        $table = input('tablename','');
    	if (empty($table)) {
    		$this->error('请选择要优化的表');
    	}

    	if (!DB::query("OPTIMIZE TABLE {$table} ")) {
    		$strTable = '';
    	}
    	$this->success("优化表成功" . $table, url('Tools/index'));
    
    }


    	/**
	 * 执行还原数据库操作
	 * @param int $time
	 * @param null $part
	 * @param null $start
	 */
	public function import($time = 0, $part = null, $start = null){
        function_exists('set_time_limit') && set_time_limit(0);
        $time = strtotime(input('time'));
		if(is_numeric($time) && is_null($part) && is_null($start)){ //初始化
			//获取备份文件信息
			$name  = date('Ymd-His', $time) . '-*.sql*';
			$path  = realpath(config('DATA_BACKUP_PATH')) . DIRECTORY_SEPARATOR . $name;
			$files = glob($path);
			$list  = array();
			foreach($files as $name){
				$basename = basename($name);
				$match    = sscanf($basename, '%4s%2s%2s-%2s%2s%2s-%d');
				$gz       = preg_match('/^\d{8,8}-\d{6,6}-\d+\.sql.gz$/', $basename);
				$list[$match[6]] = array($match[6], $name, $gz);
			}
			ksort($list);

			//检测文件正确性
			$last = end($list);
			if(count($list) === $last[0]){
				session('backup_list', $list); //缓存备份列表
				$this->success('初始化完成！', null, array('part' => 1, 'start' => 0));
			} else {
				$this->error('备份文件可能已经损坏，请检查！');
			}
		} elseif(is_numeric($part) && is_numeric($start)) {
			$list  = session('backup_list');
			$db = new Backup($list[$part], array(
					'path'     => realpath(config('DATA_BACKUP_PATH')) . DIRECTORY_SEPARATOR,
					'compress' => $list[$part][2]));
			$start = $db->import($start);
			if(false === $start){
				$this->error('还原数据出错！');
			} elseif(0 === $start) { //下一卷
				if(isset($list[++$part])){
					$data = array('part' => $part, 'start' => 0);
					$this->success("正在还原...#{$part}", null, $data);
				} else {
					session('backup_list', null);
					$this->success('还原完成！');
				}
			} else {
				$data = array('part' => $part, 'start' => $start[0]);
				if($start[1]){
					$rate = floor(100 * ($start[0] / $start[1]));
					$this->success("正在还原...#{$part} ({$rate}%)", null, $data);
				} else {
					$data['gz'] = 1;
					$this->success("正在还原...#{$part}", null, $data);
				}
			}
		} else {
			$this->error('参数错误！');
		}
    }
    

    	/**
	 * 下载
	 * @param int $time
	 */
	public function downFile($time = 0) {
        $time  = \strtotime(input('time'));
		$name  = date('Ymd-His', $time) . '-*.sql*';
		$path  = realpath(config('DATA_BACKUP_PATH')) . '/' . $name;
		$files = glob($path);
		if(is_array($files)){
			foreach ($files as $filePath){
				if (!file_exists($filePath)) {
					$this->error("该文件不存在，可能是被删除");
				}else{
					$filename = basename($filePath);
					header("Content-type: application/octet-stream");
					header('Content-Disposition: attachment; filename="' . $filename . '"');
					header("Content-Length: " . filesize($filePath));
					readfile($filePath);
				}
			}
		}
    }
    

    	/**
	 * 删除备份文件
	 * @param  Integer $time 备份时间
	 */
	public function del($time = 0){
		if($time){
            $time  = \strtotime(input('time'));
			$name  = date('Ymd-His', $time) . '-*.sql*';
			$path  = realpath(config('DATA_BACKUP_PATH')) . '/' . $name;
			array_map("unlink", glob($path));
			if(count(glob($path))){
				$this->error('备份文件删除失败，请检查权限！');
			} else {
				$this->success('备份文件删除成功！');
			}
		} else {
			$this->error('参数错误！');
		}
    }
    
/**
 * 备份
 */
    public function createBackup(){
        $table = input('tables','');
        $id = input('id',0);
        $start = input('start',0);
        if($table){
            foreach ($table as $key => $value) {
               $tables[] = $value['Name'];
            }
        }
        if(Request::isPost() && empty(!$tables) && is_array($tables)){
            $path = config('DATA_BACKUP_PATH');
            if(!is_dir($path)){
                \mkdir($path,0755,true);
            }
           
            //读取备份配置
                $config = array(
                    'path'     => realpath($path) . '/',
                    'part'     => config('DATA_BACKUP_PART_SIZE'),
                    'compress' => config('DATA_BACKUP_COMPRESS'),
                    'level'    => config('DATA_BACKUP_COMPRESS_LEVEL'),
            );
            if(!is_writable($config['path'])){
                    $this->error('备份目录不存在或不可写，请检查后重试');
            }
            $lock = "{$config['path']}backup.lock";
            if(is_file($lock)){
                $this->error('检测到有一个备份任务正在执行，请稍后再试！');
            }else{
                //创建锁文件
				file_put_contents($lock, $_SERVER['REQUEST_TIME']);
            }
            //生成备份文件信息
			$file = array(
                'name' => date('Ymd-His', $_SERVER['REQUEST_TIME']),
                'part' => 1,
            );
            session('backup_file', $file);
            session('backup_config', $config);
            //缓存要备份的表
            session('backup_tables', $tables);
            $Database = new Backup($file, $config);
            if($Database->create() !== false){
                $tab = array('id' => 0, 'start' => 0);
                $this->success('初始化成功！','',['tables' => $tables, 'tab' => $tab]);
            }else{
                $this->error('初始化失败！');
            }
        }elseif (is_numeric($id) && is_numeric($start) && Request::isGet()) {
            $tables   = session('backup_tables');
            $Database = new Backup(session('backup_file'), session('backup_config'));
            $start    = $Database->backup($tables[$id], $start);
            if($start === false){
                $this->error('备份出错！');
            }elseif (0 === $start) {
                if(isset($tables[++$id])){
					$tab = array('id' => $id, 'start' => 0);
                    $this->success('备份完成！','',['tab' => $tab]);
				} else { //备份完成，清空缓存
					unlink(session('backup_config.path') . 'backup.lock');
					session('backup_tables', null);
					session('backup_file', null);
					session('backup_config', null);
                   $this->success('备份完成！');
				}
            }else{
                $tab  = array('id' => $id, 'start' => $start[0]);
				$rate = floor(100 * ($start[0] / $start[1]));
               $this->success("正在备份...({$rate}%)", '', array('tab' => $tab));
            }
        }else{
            $this->error('参数错误！');
        }
    }
    /**
     * 修复
     */
    public function repair() {
        $table = input('tablename','');
    	if (empty($table)) {
    		$this->error('请选择要修复的表');
    	}

    	if (!DB::query("OPTIMIZE TABLE {$table} ")) {
    		$strTable = '';
    	}
    	$this->success("修复表成功" . $table, url('Tools/index'));
    
    }
}