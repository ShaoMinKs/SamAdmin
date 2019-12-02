 <?php
use think\Exception;
use think\facade\Log;
use think\Config;
use think\Db;
use think\Loader;
use think\Request;
use think\facade\Env;
use think\Controller;

class Generate extends Controller
{
    private $module;
    private $name;
    private $dir;
    private $namespaceSuffix;
    private $nameLower;
    private $data;
    // 控制器黑名单
    private $blacklistName = [
        'Admin',
        'Api',
        'Article',
        'AdminRole',
        'Base',
        'Curd',
        'Index',
        'Push',
        'Sns',
        'System',
        'Tools',
        'Ueditor',
        'User',
        'Wechat',
    ];
    // 数据表黑名单
    private $blacklistTable = [
        'sm_admin',
        'sm_admin_log',
        'sm_admin_menu',
        'sm_admin_role',
        'sm_article',
        'sm_article_cat',
        'sm_config',
        'sm_link',
        'sm_log',
        'sm_region',
        'sm_system_menu',
        'sm_users',
        'sm_wx_user',
        'sm_wechat_fans',
        'sm_wechat_keys',
        'sm_wechat_menu',
        'sm_wechat_news',
        'sm_wechat_news_article',
        'sm_wechat_news_image',
        'sm_wechat_news_media',
    ];

    public function run($data, $option = 'all')
    {
        // 检查方法是否存在
      
        $action = 'build' . ucfirst($option);
        if (!method_exists($this, $action)) {
            throw new Exception('选项不存在：' . $option, 404);
        }
        // 载入默认配置
        $defaultConfigFile = Env::get('app_path') . 'admin' . DS . 'extra' . DS . 'generate.php';
        if (file_exists($defaultConfigFile)) {
            $data = array_merge(include $defaultConfigFile, $data);
        }

        // 检查目录是否可写
        if(!self::checkWritable(Env::get('app_path'))){
            $this->error(Env::get('app_path')."目录没有权限不可写，请执行一下命令修改权限：<br>chmod -R 755 ");
        }
 
    
         $dir_list[] = Env::get('app_path') . $data['module']. DS.'controller';
         $dir_list[] = Env::get('app_path') . $data['module']. DS.'view' .DS.$data['controller'];
         $dir_list[] = Env::get('app_path') . $data['module']. DS.'view' .DS. 'public';
         if(isset($data['module']) && $data['module']){
            $dir_list[] = Env::get('app_path') . $data['module']. DS.'model';
         }
         if(isset($data['validate']) && $data['validate']){
            $dir_list[] = Env::get('app_path') . $data['module']. DS.'validate';
         }
         // 创建目录    
         $this->buildDir($dir_list);

         //公共view创建
         $layout_tpl = file_get_contents(Env::get('app_path') . 'admin/view/public/layout.html');
         $left_tpl   = file_get_contents(Env::get('app_path') . 'admin/view/public/left.html');
         if(!file_exists(Env::get('app_path') . $data['module']. DS.'view' .DS. 'public/layout.html')){
            file_put_contents(Env::get('app_path') . $data['module']. DS.'view' .DS. 'public/layout.html',$layout_tpl);
         }
         if(!file_exists(Env::get('app_path') . $data['module']. DS.'view' .DS. 'public/left.html')){
            file_put_contents(Env::get('app_path') . $data['module']. DS.'view' .DS. 'public/left.html',$left_tpl);
         }

        // 将菜单全部转为小写
        $this->data = $data;
        $this->module = $data['module'];
        $controllers = explode(".", $data['controller']);
        $this->name = array_pop($controllers);
        $this->nameLower = Loader::parseName($this->name);

        // 分级控制器目录和命名空间后缀
        if ($controllers) {
            $this->dir = strtolower(implode(DS, $controllers) . DS);
            $this->namespaceSuffix = "\\" . strtolower(implode("\\", $controllers));
        } else {
            $this->dir = "";
            $this->namespaceSuffix = "";
        }

        // 删除刚刚生成的文件
        if (isset($data['delete_file']) && $data['delete_file']) {
            $pathView = Env::get('app_path') . $this->module . DS . "view" . DS . $this->dir . $this->nameLower . DS;
            $fileName = Env::get('app_path') . "%MODULE%" . DS . "%NAME%" . DS . $this->dir . $this->name . ".php";
            $this->$action($pathView, $fileName);

            return true;
        }

        // 数据表表名
        $tableName = $data['table_name']?$data['table_name']:$data['controller'];
  
        // 判断是否在黑名单中
        if (in_array($data['controller'], $this->blacklistName)) {
            throw new Exception('该控制器不允许创建');
        }

        // 判断是否在数据表黑名单中
        if (isset($data['table']) && $data['table'] && in_array($tableName, $this->blacklistTable)) {
            throw new Exception('该数据表不允许创建');
        }

       

        if ($action != 'buildDir') {
            // 文件路径
            $pathView = Env::get('app_path') . $this->module . DS . "view" . DS . $data['controller'] . DS;
            $pathTemplate = Env::get('app_path') . 'admin' . DS . "view" . DS . "curd" . DS . "template" . DS;
            $fileName = Env::get('app_path') . "%MODULE%" . DS . "%NAME%" . DS . ucfirst($data['controller']) . ".php";
            $code = $this->parseCode();
            // 执行方法
            $this->$action($pathView, $pathTemplate, $fileName, $tableName, $code, $data);
        }
    }

    /**
     * 检查当前模块目录是否可写
     * @return bool
     */
    public static function checkWritable($path = '')
    {
        try {
            $path = $path ? $path : APP_PATH . 'admin' . DS;
            $testFile = $path . "bulid.test";
            if (!file_put_contents($testFile, "test")) {
                return false;
            }
            // 解除锁定
            unlink($testFile);

            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * 生成所有文件
     */
    private function buildAll($pathView, $pathTemplate, $fileName, $tableName, $code, $data)
    {
        
        // 创建文件
        $this->buildIndex($pathView, $pathTemplate, $fileName, $tableName, $code, $data);
        $this->buildEdit($pathView, $pathTemplate, $fileName, $tableName, $code, $data);
        $this->buildController($pathView, $pathTemplate, $fileName, $tableName, $code, $data);
        if (isset($data['validate']) && $data['validate']) {
            $this->buildValidate($pathView, $pathTemplate, $fileName, $tableName, $code, $data);
        }
        if (isset($data['model']) && $data['model']) {
            $this->buildModel($pathView, $pathTemplate, $fileName, $tableName, $code, $data);
        }
        if (isset($data['create_table']) && $data['create_table']) {
            $this->buildTable($pathView, $pathTemplate, $fileName, $tableName, $code, $data);
        }
        // 建立配置文件
        if (isset($data['create_config']) && $data['create_config']) {
            $this->buildConfig($pathView, $pathTemplate, $fileName, $tableName, $code, $data);
        }
    }

    /**
     * 删除所有文件
     *
     * @param        $pathView
     * @param string $phpFile
     */
    private function delAll($pathView, $phpFile = '')
    {
        try {
            $this->delTable($pathView, $phpFile);
            $this->delView($pathView, $phpFile);
            $this->delController($pathView, $phpFile);
            $this->delModel($pathView, $phpFile);
            $this->delValidate($pathView, $phpFile);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    /**
     * 删除首页文件
     *
     * @param $pathView
     * @param $phpFile
     *
     * @return bool
     */
    private function delIndex($pathView, $phpFile = '')
    {
        return $this->deleteFile($pathView . 'index.html');
    }

    /**
     * 删除form文件
     *
     * @param $pathView
     * @param $phpFile
     *
     * @return bool
     */
    private function delForm($pathView, $phpFile = '')
    {
        return $this->deleteFile($pathView . 'form.html');
    }

    /**
     * 删除th文件
     *
     * @param $pathView
     * @param $phpFile
     *
     * @return bool
     */
    private function delTh($pathView, $phpFile = '')
    {
        return $this->deleteFile($pathView . 'th.html');
    }

    /**
     * 删除td文件
     *
     * @param $pathView
     * @param $phpFile
     *
     * @return bool
     */
    private function delTd($pathView, $phpFile = '')
    {
        return $this->deleteFile($pathView . 'td.html');
    }

    /**
     * 删除编辑文件
     *
     * @param $pathView
     * @param $phpFile
     *
     * @return bool
     */
    private function delEdit($pathView, $phpFile = '')
    {
        return $this->deleteFile($pathView . 'edit.html');
    }

    /**
     * 删除回收站文件
     *
     * @param $pathView
     * @param $phpFile
     *
     * @return bool
     */
    private function delRecycleBin($pathView, $phpFile = '')
    {
        return $this->deleteFile($pathView . 'recyclebin.html');
    }

    /**
     * 删除配置文件
     *
     * @param $pathView
     * @param $phpFile
     *
     * @return bool
     */
    private function delConfig($pathView, $phpFile = '')
    {
        return $this->deleteFile($pathView . 'config.php');
    }

    /**
     * 删除视图文件夹
     *
     * @param $pathView
     * @param $phpFile
     *
     * @return bool
     */
    private function delView($pathView, $phpFile = '')
    {
        return $this->deleteFile($pathView);
    }

    /**
     * 删除控制器文件
     *
     * @param $pathView
     * @param $phpFile
     *
     * @return bool
     */
    private function delController($pathView, $phpFile = '')
    {
        $file = str_replace(
            ['%MODULE%', '%NAME%'],
            [$this->module, 'controller'],
            $phpFile
        );

        return $this->deleteFile($file);
    }

    /**
     * 删除模型文件
     *
     * @param $pathView
     * @param $phpFile
     *
     * @return bool
     */
    private function delModel($pathView, $phpFile = '')
    {
        // 获取模型的路径，根据配置文件读取
        $module = $this->readConfig($this->module, 'app', 'model_path', Config::get('app.model_path'));
        $name = $this->parseCamelCase($this->dir) . $this->name;
        $file = APP_PATH . $module . DS . "model" . DS . $name . ".php";

        return $this->deleteFile($file);
    }

    /**
     * 删除验证器文件
     *
     * @param $pathView
     * @param $phpFile
     *
     * @return bool
     */
    private function delValidate($pathView, $phpFile = '')
    {
        // 获取验证器的路径，根据配置文件读取
        $module = $this->readConfig($this->module, 'app', 'validate_path', Config::get('app.validate_path'));
        $file = str_replace(
            ['%MODULE%', '%NAME%'],
            [$module, 'validate'],
            $phpFile
        );

        return $this->deleteFile($file);
    }

    /**
     * 删除表
     *
     * @param        $pathView
     * @param string $phpFile
     *
     * @return bool
     */
    private function delTable($pathView, $phpFile = '')
    {
        // 数据表表名
        $tableName = str_replace(DS, '_', $this->dir) . $this->nameLower;
        // 一定别忘记表名前缀
        $tableName = isset($this->data['table_name']) && $this->data['table_name'] ?
            $this->data['table_name'] :
            Config::get("database.prefix") . $tableName;
        // 判断表是否存在
        $ret = Db::query("SHOW TABLES LIKE '{$tableName}'");
        // 表存在
        if ($ret && isset($ret[0])) {
            // 不是强制建表但表存在时直接return
            if (!isset($this->data['create_table_force']) || !$this->data['create_table_force']) {
                return true;
            }

            // 删除表
            Db::execute("DROP TABLE IF EXISTS `{$tableName}`");
        }

        return true;
    }

    /**
     * 删除文件或目录
     *
     * @param $path
     */
    private function deleteFile($path)
    {
        if (is_dir($path)) {
            return $this->deleteDir($path);
        } else {
            return unlink($path);
        }
    }

    /**
     * 删除目录及下面所有的文件
     *
     * @param $dir
     *
     * @return bool
     */
    private function deleteDir($dir)
    {
        //先删除目录下的文件：
        $dh = opendir($dir);
        while ($file = readdir($dh)) {
            if ($file != "." && $file != "..") {
                $fullpath = $dir . "/" . $file;
                if (!is_dir($fullpath)) {
                    unlink($fullpath);
                } else {
                    $this->deleteDir($fullpath);
                }
            }
        }
        closedir($dh);
        //删除当前文件夹：
        if (rmdir($dir)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 创建目录
     */
    private function buildDir($dir_list)
    {
        foreach ($dir_list as $dir) {
            if (!is_dir($dir)) {
                if(!$this->checkWritable($dir)){
                    $this->error($dir.'目录不可写，无权限');
                }else{
                     // 创建目录
                mkdir($dir, 0755, true); 
                }
               
            }
        }
    }

    /**
     * 创建 edit.html 文件
     */
    private function buildEdit($path, $pathTemplate, $fileName, $tableName, $code, $data)
    {
        $template   = file_get_contents($pathTemplate . "add.tpl");
        $file       = $path . "add.html";
        $module     = $data['module'];
        $controller = $data['controller'];
        $table      = $data['table_name'];
        return file_put_contents($file, str_replace(
            ["[MODULE]", "[CONTROLLER]","[TABLE]","[ROWS]"],
            [$module,$controller,$table,$code['edit']],
            $template));
    }

    /**
     * 创建form.html文件
     */
    private function buildForm($path, $pathTemplate, $fileName, $tableName, $code, $data)
    {
        $content = implode("\n", $code['search']);
        $file = $path . "form.html";

        return file_put_contents($file, $content);
    }

    /**
     * 创建th.html文件
     */
    private function buildTh($path, $pathTemplate, $fileName, $tableName, $code, $data)
    {
        $content = implode("\n", $code['th']);
        $file = $path . "th.html";

        return file_put_contents($file, $content);
    }

    /**
     * 创建td.html文件
     */
    private function buildTd($path, $pathTemplate, $fileName, $tableName, $code, $data)
    {
        $content = implode("\n", $code['td']);
        $file = $path . "td.html";

        return file_put_contents($file, $content);
    }

    /**
     * 创建 recyclebin.html 文件
     */
    private function buildRecycleBin($path, $pathTemplate, $fileName, $tableName, $code, $data)
    {
        // 首页菜单选择了回收站才创建回收站
        $file = $path . "recyclebin.html";

        //TODO 自定义模板路径
        if ($this->module == Request::instance()->module()) {
            $module = '';
        } else {
            $module = Request::instance()->module() . '@';
        }

        $content = '{extend name="' . $module . 'template/recyclebin" /}';
        if ($code['search_selected']) {
            $content .= "\n" . '{block name="script"}' . implode("", $code['script_search']) . "\n"
                . '<script>' . "\n"
                . tab(1) . '$(function () {' . "\n"
                . $code['search_selected']
                . tab(1) . '})' . "\n"
                . '</script>' . "\n"
                . '{/block}' . "\n";
        }

        // 默认直接继承模板
        return file_put_contents($file, $content);
    }

    /**
     * 创建 index.html 文件
     */
    private function buildIndex($path, $pathTemplate, $fileName, $tableName, $code, $data)
    {
        $form       = implode("\n" . tab(1), $code['search']);
        $template   = file_get_contents($pathTemplate . "index.tpl");   
        $file       = $path . "index.html";
        $module     = $data['module'];
        $controller = $data['controller'];
        $table      = $data['table_name'];
         return file_put_contents($file, str_replace(
                ["[MODULE]","[CONTROLLER]","[TABLE]", "[FORM]", "[LAYUICOL]"],
                [$module,$controller,$table, $form, $code['layui_col']],
                $template
            )
        );
    }

    /**
     * 创建控制器文件
     */
    private function buildController($path, $pathTemplate, $fileName, $tableName, $code, $data)
    {
        $module        = $data['module'];
        $template      = file_get_contents($pathTemplate . "Controller.tpl");
        $template_base = file_get_contents($pathTemplate . "Base.tpl");
        $base_php      = Env::get('app_path'). DS . $data['module'] . DS .'controller/Base.php';
        if(!file_exists($base_php)){
            file_put_contents($base_php, str_replace(
                ["[MODULE]"],
                [$module],
                $template_base
            )
            );
        }
        $file = str_replace(
            ['%MODULE%', '%NAME%'],
            [$this->module, 'controller'],
            $fileName
        );
        $file_base = str_replace(
            ['%MODULE%', '%NAME%'],
            [$this->module, 'controller'],
            $fileName
        );
        $module      = $data['module'];
        $controller  = ucfirst($data['controller']);
        $table       = $data['table_name'];
        $utable      = ucfirst($data['table_name']);
        $items       = '';
        foreach($data['form'] as $key=>$value){
            $items .= "'".$value['name']."'".',';
        }
        $items = substr($items,0,strlen($items)-1);
        return file_put_contents($file, str_replace(
                ["[MODULE]", "[CONTROLLER]", "[TABLE]","[UTABLE]","[ITEM]"],
                [$module , $controller,$table,$utable,'['.$items.']'],
                $template
            )
        );
    }

    /**
     * 创建模型文件
     */
    private function buildModel($path, $pathTemplate, $fileName, $tableName, $code, $data)
    {
        // 直接生成空模板
        $template = file_get_contents($pathTemplate . "Model.tpl");
        // 获取模型的路径，根据配置文件读取
        $module = $data['module'];
        $controller = $data['controller'];
        $table  = ucfirst($data['table_name']);
        $file   = Env::get('app_path') . $module . DS . "model" . DS . $table . ".php";
        $module = ucfirst($data['module']);
        $autoTimestamp = '';
        if (isset($this->data['auto_timestamp']) && $this->data['auto_timestamp']) {
            $autoTimestamp = '// 开启自动写入时间戳字段' . "\n"
                . tab(1) . 'protected $autoWriteTimestamp = true;';
        }

        return file_put_contents($file, str_replace(
                ["[MODULE]","[CONTROLLER]", "[TABLE]", "[AUTO_TIMESTAMP]"],
                [$module,$controller, $table, $autoTimestamp],
                $template
            )
        );
    }

    /**
     * 创建验证器
     */
    private function buildValidate($path, $pathTemplate, $fileName, $tableName, $code, $data)
    {
        $template = file_get_contents($pathTemplate . "Validate.tpl");
        // 获取验证器的路径，根据配置文件读取
        $module     = $data['module'];
        $controller = ucfirst($data['controller']);
        $table      = ucfirst($data['table_name']);
        $file       = Env::get('app_path') . $module .DS. 'validate' .DS. $controller.'.php';
        $table      = $data['table_name'];
        return file_put_contents($file, str_replace(
                ["[MODULE]", "[CONTROLLER]", "[RULE]"],
                [$module, $controller, $code['validate']],
                $template
            )
        );
    }

    /**
     * 创建数据表
     */
    private function buildTable($path, $pathTemplate, $fileName, $tableName, $code, $data)
    {
      
        // 一定别忘记表名前缀
        $tableName = isset($data['table_name']) && $data['table_name'] ?
        config("database.prefix") . $data['table_name'] : config("database.prefix") . $data['controller'];
        // 在 MySQL 中，DROP TABLE 语句自动提交事务，因此在此事务内的任何更改都不会被回滚，不能使用事务
        // http://php.net/manual/zh/pdo.rollback.php
        $tableExist = false;
        // 判断表是否存在
        $ret = Db::query("SHOW TABLES LIKE '{$tableName}'");
        // 表存在
        if ($ret && isset($ret[0])) {
            //不是强制建表但表存在时直接return
            if (!isset($data['create_table_force']) || !$data['create_table_force']) {
                return true;
            }
            Db::execute("RENAME TABLE {$tableName} to {$tableName}_build_bak");
            $tableExist = true;
        }
        $auto_create_field = ['id', 'status', 'isdelete', 'create_time', 'update_time'];
        // 强制建表和不存在原表执行建表操作
        $fieldAttr = [];
        $key = [];
        $data['title']  = '';
        if (in_array('id', $auto_create_field)) {
            $fieldAttr[] = tab(1) . "`id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '{$data['title']}主键'";
        }
        foreach ($this->data['field'] as $field) {
            if (!in_array($field['name'], $auto_create_field)) {
                // 字段属性
                $fieldAttr[] = tab(1) . "`{$field['name']}` {$field['type']}"
                    . ($field['extra'] ? ' ' . $field['extra'] : '')
                    . (isset($field['not_null']) && $field['not_null'] ? ' NOT NULL' : '')
                    . (strtolower($field['default']) == 'null' ? '' : " DEFAULT '{$field['default']}'")
                    . ($field['comment'] === '' ? '' : " COMMENT '{$field['comment']}'");
            }
            // 索引
            if (isset($field['key']) && $field['key'] && $field['name'] != 'id') {
                $key[] = tab(1) . "KEY `{$field['name']}` (`{$field['name']}`)";
            }
        }



        // 如果创建模型则自动生成create_time，update_time字段
        if (isset($data['auto_timestamp']) && $data['auto_timestamp']) {
            // 自动生成 create_time 字段，相应自动生成的模型也开启自动写入create_time和update_time时间，并且将类型指定为int类型
            // 时间戳使用方法见：http://www.kancloud.cn/manual/thinkphp5/138668
            $fieldAttr[] = tab(1) . "`create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间'";
            $fieldAttr[] = tab(1) . "`update_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间'";
        }
        // 默认自动创建主键为id
        $fieldAttr[] = tab(1) . "PRIMARY KEY (`id`)";

        // 会删除之前的表，会清空数据，重新创建表，谨慎操作
        $sql_drop = "DROP TABLE IF EXISTS `{$tableName}`";
        // 默认字符编码为utf8，表引擎默认InnoDB，其他都是默认
        $sql_create = "CREATE TABLE `{$tableName}` (\n"
            . implode(",\n", array_merge($fieldAttr, $key))
            . "\n)ENGINE=" . (isset($this->data['table_engine']) ? $this->data['table_engine'] : 'InnoDB')
            . " DEFAULT CHARSET=utf8 COMMENT '{$data['title']}'";

 
        // 写入执行的SQL到日志中，如果不是想要的表结构，请到日志中搜索BUILD_SQL，找到执行的SQL到数据库GUI软件中修改执行，修改表结构
        Log::write("BUILD_SQL：\n{$sql_drop};\n{$sql_create};", 'sql');
        // execute和query方法都不支持传入分号 (;)，不支持一次执行多条 SQL
        try {
            Db::execute($sql_drop);
            Db::execute($sql_create);
            Db::execute("DROP TABLE IF EXISTS `{$tableName}_build_bak`");
        } catch (\Exception $e) {
            // 模拟事务操作，滚回原表
            if ($tableExist) {
                Db::execute("RENAME TABLE {$tableName}_build_bak to {$tableName}");
            }

            throw new Exception($e->getMessage());
        }
    }

    /**
     * 创建配置文件
     */
    private function buildConfig($path, $pathTemplate, $fileName, $tableName, $code, $data)
    {
        $content = '<?php' . "\n\n"
            . 'return ' . var_export($data, true) . ";\n";
        $file = $path . "config.php";

        return file_put_contents($file, $content);
    }


    /**
     * 创建文件的代码
     * @return array
     * return [
     * 'search'          => $search,
     * 'th'              => $th,
     * 'td'              => $td,
     * 'edit'            => $editField,
     * 'set_checked'     => $setChecked,
     * 'set_selected'    => $setSelected,
     * 'search_selected' => $searchSelected,
     * 'filter'          => $filter,
     * 'validate'        => $validate,
     * ];
     */
    private function parseCode()
    {
        // 是否开启排序
        $sortable = false;
        // 生成 form.html 文件的代码
        $search = ['<form  method="get"   onsubmit="return false" id="search_form" class="layui-form layui-form-pane" action="">'];
        // 生成 th.html 文件的代码
        $th = ['<th width="25"><input type="checkbox"></th>'];
        // 生成 td.html 文件的代码
        $td = ['<td><input type="checkbox" name="id[]" value="{$vo.id}"></td>'];
        // 生成 add.html 文件的代码
        $editField = '';
        // radio类型的表单控件编辑状态使用javascript赋值
        $setChecked = [];
        // select类型的表单控件编辑状态使用javascript赋值
        $setSelected = [];
        // 搜索时被选中的值
        $searchSelected = '';
        // 控制器过滤器
        $filter = '';
        // 生成验证器文件的代码
        $validate = '';
        // DatePicker脚本引入
        $scriptSearch = [];
        $scriptEdit = [];
        if (isset($this->data['form']) && $this->data['form']) {
            foreach ($this->data['form'] as $form) {
                // 状态选择的自动设置为单选框
                if ($form['name'] == 'status') {
                    $form['type'] = 'radio';
                    $form['option'] = '1:启用#0:禁用';
                }
                $options = $this->parseOption($form['option']);
                 // layui表单构建
                 $layui_col[] = [
                    'field' => $form['name'],
                    'title' => $form['title'],
                    'align' => 'center',
                    'sort'   => isset($form['sort'] )? true : false,
                    'edit'   => isset($form['edit'] )? 'text' : ''
                ];
                // 表单搜索
                if (isset($form['search']) && $form['search']) {
                    // 表单搜索
                    switch ($form['search_type']) {
                        case 'select':
                            // td
                            $td[] = '<td>{$vo.' . $form['name'] . ($form['name'] == "status" ? '|get_status' : '') . '}</td>';
                            // 默认选中
                            $searchSelected .= tab(2) . '$("[name=\'' . $form['name'] . '\']").find("[value=\'{$Request.param.' . $form['name'] . '}\']").attr("selected", true);' . "\n";
                            $search[] = tab(1) . '<div class="select-box" style="width:250px">';
                            $search[] = tab(2) . '<select name="' . $form['name'] . '" class="select">';
                            $search = array_merge($search, $this->getOption($options, $form, true, 3));
                            $search[] = tab(2) . '</select>';
                            $search[] = tab(1) . '</div>';
                            break;
                        case 'date':
                            // td
                            $td[] = '<td>{$vo.' . $form['name'] . ($form['name'] == "status" ? '|get_status' : '') . '}</td>';
                            $search[] = tab(1) . '<input type="text" class="input-text Wdate" style="width:250px" '
                                . 'placeholder="' . $form['title'] . '" name="' . $form['name'] . '" '
                                . 'value="{$Request.param.' . $form['name'] . '}" '
                                . '{literal} onfocus="WdatePicker({dateFmt:\'yyyy-MM-dd\'})" {/literal} '
                                . '>';
                            $scriptSearch['date'] = "\n" . '<script type="text/javascript" src="__LIB__/My97DatePicker/WdatePicker.js"></script>';
                            break;
                        default:
                            // td
                            if ($form['name'] == 'sort') {
                                // 排序字段特殊处理
                                $sortable = true;
                                $td[] = '<td style="padding: 0">' . "\n"
                                    . tab(1) . '<input type="number" name="sort[{$vo.id}]" value="{$vo.sort}" style="width: 60px;"' . "\n"
                                    . tab(2) . 'class="input-text text-c order-input" data-id="{$vo.id}">'
                                    . '</td>';
                            } else {
                                $td[] = '<td>{$vo.' . $form['name'] . '|high_light=$Request.param.' . $form['name'] . "}</td>";
                            }
                            $filter .= tab(2) . 'if ($this->request->param("' . $form['name'] . '")) {' . "\n"
                                . tab(3) . '$map[\'' . $form['name'] . '\'] = ["like", "%" . $this->request->param("' . $form['name'] . '") . "%"];' . "\n"
                                . tab(2) . '}' . "\n";
                            $search[] = tab(8) . '<div class="layui-inline">'. "\n"
                            . tab(8) . '<label class="layui-form-label">'.$form['title'].'</label>' . "\n"
                            . tab(9) . '<div class="layui-input-inline">'."\n"
                            . tab(10) . '<input type="text" class="layui-input" style="padding-left:8px" '
                             . 'placeholder="' . $form['title'] . '" name="' . $form['name'] . '" '
                             . 'value="{$Request.param.' . $form['name'] . '}" '
                             . '>' . "\n"
                            . tab(9) . '</div>'."\n".
                            tab(8) .'</div>';
                            break;
                    }
                } else {
                    // td
                    if ($form['name'] == 'sort') {
                        // 排序字段特殊处理
                        $sortable = true;
                        $td[] = '<td style="padding: 0">' . "\n"
                            . tab(1) . '<input type="number" name="sort[{$vo.id}]" value="{$vo.sort}" style="width: 60px;"' . "\n"
                            . tab(2) . 'class="input-text text-c order-input" data-id="{$vo.id}">'
                            . '</td>';
                    } else {
                        $td[] = '<td>{$vo.' . $form['name'] . ($form['name'] == "status" ? '|get_status' : '') . '}</td>';
                    }
                }
                // th
                if (isset($form['sort']) && $form['sort']) {
                    // 带有表单排序的需使用表单排序方法
                    $th[] = '<th width="">' . "{:sort_by('{$form['title']}','{$form['name']}')}</th>";
                } else {
                    $th[] = '<th width="">' . $form['title'] . "</th>";
                }
                // 像id这种白名单字段不需要自动生成到编辑页
                if (!in_array($form['name'], ['id', 'isdelete', 'create_time', 'update_time'])) {
                    // 使用 Validform 插件前端验证数据格式，生成在表单控件上的验证规则
                    $validateForm = '';
                    if (isset($form['validate'])) {
                        $v = $form['validate'];
                        $defaultDesc = in_array($form['type'], ['checkbox', 'radio', 'select', 'date']) ? '选择' : '填写';
                        $validate .= tab(2) . '"' . $form['name'] . '" => "'
                            . (isset($form['validate']) && $form['validate'] ? 'require' : '') . '",' . "\n";
                    }
                    $editField .= tab(6) . '<div class="layui-form-item">' . "\n"
                        . tab(7) . '<label class="layui-form-label" for="'.$form['name'].'">'
                        . (isset($form['validate']) && $form['validate'] ? '<span class="c-red">*</span>' : '')
                        . $form['title'] . '：</label>' . "\n"
                        . tab(8) . '<div class="layui-input-inline'
                        . (in_array($form['type'], ['radio', 'checkbox']) ? ' skin-minimal' : '')
                        . '">' . "\n";
                        
                    switch ($form['type']) {
                        case "radio":
                        case "checkbox":
                            if ($form['type'] == "radio") {
                                // radio类型的控件进行编辑状态赋值，checkbox类型控件请自行根据情况赋值
                                $setChecked[] = tab(2) . '$("[name=\'' . $form['name'] . '\'][value=\'{$vo.' . $form['name'] . ' ?? \'' . $form['default'] . '\'}\']").prop("checked", true);';
                            } else {
                                $setChecked[] = tab(2) . 'var checks = \'' . $form['default'] . '\'.split(",");' . "\n"
                                    . tab(2) . 'if (checks.length > 0){' . "\n"
                                    . tab(3) . 'for (var i in checks){' . "\n"
                                    . tab(4) . '$("[name=\'' . $form['name'] . '[]\'][value=\'"+checks[i]+"\']").prop("checked", true);' . "\n"
                                    . tab(3) . '}' . "\n"
                                    . tab(2) . '}';
                            }

                            // 默认只生成一个空的示例控件，请根据情况自行复制编辑
                            $name = $form['name'] . ($form['type'] == "checkbox" ? '[]' : '');

                            switch ($options[0]) {
                                case 'string':
                                    $editField .= $this->getCheckbox($form, $name, $validateForm, $options[1], '', 0);
                                    break;
                                case 'var':
                                    $editField .= tab(4) . '{foreach name="$Think.config.conf.' . $options[1] . '" item=\'v\' key=\'k\'}' . "\n"
                                        . $this->getCheckbox($form, $name, $validateForm, '{$v}', '{$k}', '{$k}')
                                        . tab(4) . '{/foreach}' . "\n";
                                    break;
                                case 'array':
                                    foreach ($options[1] as $option) {
                                        $editField .= $this->getCheckbox($form, $name, $validateForm, $option[1], $option[0], $option[0]);
                                    }
                                    break;
                            }
                            break;
                        case "select":
                            // select类型的控件进行编辑状态赋值
                            $setSelected[] = tab(2) . '$("[name=\'' . $form['name'] . '\']").find("[value=\'{$vo.' . $form['name'] . ' ?? \'' . $form['default'] . '\'}\']").attr("selected", true);';
                            $editField .= tab(4) . '<div class="select-box">' . "\n"
                                . tab(5) . '<select name="' . $form['name'] . '" class="select"' . $validateForm . '>' . "\n"
                                . implode("\n", $this->getOption($options, $form, false, 6)) . "\n"
                                . tab(5) . '</select>' . "\n"
                                . tab(4) . '</div>' . "\n";
                            break;
                        case "textarea":
                            // 默认生成的textarea加入了输入字符长度实时统计，H-ui.admin官方的textarealength方法有问题，请使用 tpadmin 框架修改后的源码，也可拷贝 H-ui.js 里相应的方法
                            // 如果不需要字符长度实时统计，请在生成代码中删除textarea上的onKeyUp事件和下面p标签那行
                            $editField .= tab(4) . '<textarea class="textarea" placeholder="" name="' . $form['name'] . '" '
                                . 'onKeyUp="textarealength(this, 100)"' . $validateForm . '>'
                                . '{$vo.' . $form['name'] . ' ?? \'' . $form['default'] . '\'}'
                                . '</textarea>' . "\n"
                                . tab(4) . '<p class="textarea-numberbar"><em class="textarea-length">0</em>/100</p>' . "\n";
                            break;
                        case "date":
                            $editField .= tab(4) . '<input type="text" class="input-text Wdate" '
                                . 'placeholder="' . $form['title'] . '" name="' . $form['name'] . '" '
                                . 'value="' . '{$vo.' . $form['name'] . ' ?? \'' . $form['default'] . '\'}' . '" '
                                . '{literal} onfocus="WdatePicker({dateFmt:\'yyyy-MM-dd\'})" {/literal} '
                                . $validateForm . '>' . "\n";
                            $scriptEdit['date'] = "\n" . '<script type="text/javascript" src="__LIB__/My97DatePicker/WdatePicker.js"></script>';
                            break;
                        case "text":
                        case "password":
                        case "number":
                        default:
                            $editField .= tab(9) . '<input type="' . $form['type'] . '" class="layui-input" '
                                . 'placeholder="' . $form['title'] . '" lay-verify="'.$form['validate'].'" name="' . $form['name'] . '" '
                                . 'value="' . '{$info.' . $form['name'] . ' ?? \'' . $form['default'] . '\'}' . '" '
                                . $validateForm . '>' . "\n";
                            break;
                    }
                    $editField .= tab(8) . '</div>' . "\n"
                        . tab(6) . '</div>' . "\n";
                }
            }
        }
        if (count($search) > 1) {
            // 有设置搜索则显示
            $search[] = tab(8) . '<button class="layui-btn" data-type="reload" id="search">搜索</button>';
            $search[] = tab(7) .'</form>';
        } else {
            // 不设置将form.html置空
            $search = [];
        }


        if ($filter) {
            $filter = 'protected function filter(&$map)' . "\n"
                . tab(1) . '{' . "\n"
                . $filter
                . tab(1) . '}';
        }
        // 自动屏蔽查询条件isdelete字段
        if (!isset($this->data['menu']) ||
            (isset($this->data['menu']) &&
                !in_array("delete", $this->data['menu']) &&
                !in_array("recyclebin", $this->data['menu'])
            )
        ) {
            $filter = 'protected static $isdelete = false;' . "\n\n" . tab(1) . $filter;
        }
        if ($validate) {
            $validate = 'protected $rule = [' . "\n" . $validate . '    ];';
        }
  
        array_push($layui_col,['fixed'=>'right','title'=>'操作','align'=>'center','toolbar'=>'#barDemo']);
        array_unshift($layui_col,['type'=>'checkbox','fixed'=>'left']);
        return [
            'search'          => $search,
            'th'              => $th,
            'td'              => $td,
            'edit'            => $editField,
            'set_checked'     => $setChecked,
            'set_selected'    => $setSelected,
            'search_selected' => $searchSelected,
            'filter'          => $filter,
            'validate'        => $validate,
            'script_edit'     => $scriptEdit,
            'script_search'   => $scriptSearch,
            'layui_col'       => json_encode($layui_col,JSON_UNESCAPED_UNICODE)
        ];
    }

    /**
     * 生成复选框、单选框
     */
    private function getCheckbox($form, $name, $validateForm, $title, $value = '', $key = 0, $tab = 4)
    {
        return tab($tab) . '<div class="radio-box">' . "\n"
            . tab($tab + 1) . '<input type="' . $form['type'] . '" name="' . $name . '" '
            . 'id="' . $form['name'] . '-' . $key . '" value="' . $value . '"' . $validateForm . '>' . "\n"
            . tab($tab + 1) . '<label for="' . $form['name'] . '-' . $key . '">' . $title . '</label>' . "\n"
            . tab($tab) . '</div>' . "\n";
    }

    /**
     * 获取下拉框的option
     */
    private function getOption($options, $form, $empty = true, $tab = 3)
    {
        switch ($options[0]) {
            case 'string':
                return [tab($tab) . '<option value="">' . $options[1] . '</option>'];
                break;
            case 'var':
                $ret = [];
                if ($empty) {
                    $ret[] = tab($tab) . '<option value="">所有' . $form['title'] . '</option>';
                }
                $ret[] = tab($tab) . '{foreach name="$Think.config.conf.' . $options[1] . '" item=\'v\' key=\'k\'}';
                $ret[] = tab($tab + 1) . '<option value="{$k}">{$v}</option>';
                $ret[] = tab($tab) . '{/foreach}';

                return $ret;
                break;
            case 'think_var':
                $ret = [];
                if ($empty) {
                    $ret[] = tab($tab) . '<option value="">所有' . $form['title'] . '</option>';
                }
                $ret[] = tab($tab) . '{foreach name="$' . $options[1] . '" item=\'v\'}';
                $ret[] = tab($tab + 1) . '<option value="{$v.id}">{$v.name}</option>';
                $ret[] = tab($tab) . '{/foreach}';

                return $ret;
                break;
            case 'array':
                $ret = [];
                foreach ($options[1] as $option) {
                    $ret[] = tab($tab) . '<option value="' . $option[0] . '">' . $option[1] . '</option>';
                }

                return $ret;
                break;
        }
    }

    /**
     * 格式化选项值
     */
    private function parseOption($option, $string = false)
    {
        if (!$option) return ['string', $option];
        if (preg_match('/^\{\$(.*?)\}$/', $option, $match)) {
            // {$vo.item} 这种格式传入的变量
            return ['think_var', $match[1]];
        } elseif (preg_match('/^\{(.*?)\}$/', $option, $match)) {
            // {vo.item} 这种格式传入的变量
            return ['var', $match[1]];
        } else {
            if ($string) {
                return ['string', $option];
            }
            // key:val#key2:val2#val3#... 这种格式
            $ret = [];
            $arrVal = explode('#', $option);
            foreach ($arrVal as $val) {
                $keyVal = explode(':', $val, 2);
                if (count($keyVal) == 1) {
                    $ret[] = ['', $keyVal[0]];
                } else {
                    $ret[] = [$keyVal[0], $keyVal[1]];
                }
            }

            return ['array', $ret];
        }
    }

    /**
     * 读取配置
     *
     * @param        $module
     * @param        $scope
     * @param null   $name
     * @param string $default
     *
     * @return array|mixed|string
     */
    private function readConfig($module, $scope, $name = null, $default = '')
    {
        // 可能的配置文件路径
        $fileConfig = APP_PATH . $module . '/config.php';
        $fileExtra = APP_PATH . $module . 'extra/' . $scope . '.php';
        $config = [];
        // 加载配置
        if (file_exists($fileExtra)) {
            $config = include $fileExtra;
        } elseif (file_exists($fileConfig)) {
            $allConfig = include $fileConfig;
            if (isset($allConfig[$scope])) {
                $config = $allConfig[$scope];
            }
        }
        // 返回值
        if ($name) {
            return isset($config[$name]) ? $config[$name] : $default;
        } else {
            return $config;
        }
    }

    /**
     * 将one/two/three转为OneTwoThree
     *
     * @param $name
     *
     * @return mixed
     */
    private function parseCamelCase($name)
    {
        $pattern = DS == '\\' ? '/((^|\\\\)([a-z]))/' : '/((^|\\/)([a-z]))/';
        return preg_replace_callback($pattern, function ($matches) {
            return strtoupper($matches[3]);
        }, trim($name, DS));
    }
}
