<?php
namespace app\admin\validate;
use think\Validate;

class Article extends Validate{

    protected $rule = [
        'title'     => 'require',
        'cat_id'    => 'require',
        'content'   => 'require',
        '__token__' => 'token',
    ];

    protected $message = [
        'title.require'    => '标题不能为空',
        'content.require'  => '内容不能为空',
        'cat_id.require'   => '所属分类缺少参数',
    ];

      //验证场景
      protected $scene = [
        'add'  => ['title', 'cat_id', 'content'],
        'edit' => ['title', 'cat_id', 'content'],
        'del'  => ['article_id']
    ];
}