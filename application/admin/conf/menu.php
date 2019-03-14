<?php
return	array(	
	'index'=>array('name'=>'首页','child'=>array(
			array('name' => '概览','child' => array(
					array('name' => '模板设置', 'act'=>'index', 'op'=>'index'),
			)),
	)),

	'system'=>array('name'=>'设置','child'=>array(
				array('name' => '系统','child' => array(
						array('name'=>'站点设置','act'=>'index','op'=>'System'),
						array('name'=>'清除缓存','act'=>'cleanCache','op'=>'System')
				)),
			array('name' => '权限','child'=>array(
						array('name' => '管理员列表', 'act'=>'index', 'op'=>'Admin'),
						array('name' => '角色管理', 'act'=>'role', 'op'=>'Admin'),
						array('name'=>'权限资源列表','act'=>'right_list','op'=>'System'),
				)),
			
				array('name' => '数据','child'=>array(
						array('name' => '数据备份', 'act'=>'index', 'op'=>'Tools'),
						array('name' => '数据还原', 'act'=>'restore', 'op'=>'Tools'),
				)),

	)),
		



	'center'=>array('name'=>'内容管理','child'=>array(
			

			array('name' => '文章','child'=>array(
					array('name' => '文章列表', 'act'=>'articleList', 'op'=>'Article'),
					array('name' => '文章分类', 'act'=>'categoryList', 'op'=>'Article'),
					//array('name' => '帮助管理', 'act'=>'help_list', 'op'=>'Article'),
					array('name'=>'友情链接','act'=>'linkList','op'=>'Article'),
					array('name' => '会员协议', 'act'=>'agreement', 'op'=>'Article'),
			)),

			array('name' => '新闻','child'=>array(
					array('name' => '新闻列表', 'act'=>'newsList', 'op'=>'News'),
					array('name' => '新闻分类', 'act'=>'categoryList', 'op'=>'News'),
			)),
	)),
		



 	'member'=>array('name'=>'会员','child'=>array(
		array('name' => '会员管理','child'=>array(
			array('name'=>'会员列表','act'=>'index','op'=>'User'),
			array('name'=>'会员等级','act'=>'levelList','op'=>'User'),
		)),
	)),


);