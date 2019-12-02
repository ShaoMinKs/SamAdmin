<?php
namespace app\admin\controller;
use think\Db;

class Api extends Base {

        /*
     * 获取地区
     */
    public function getRegion(){
        header("Content-type: text/html; charset=utf-8");
        $parent_id = input('param.parent_id/d');
        $selected = input('param.selected', 0);
        $data = Db::name('region')->where("parent_id", $parent_id)->select();
        $html = '';
        if ($data) {
            foreach ($data as $h) {
                if ($h['id'] == $selected) {
                    $html .= "<option value='{$h['id']}' selected>{$h['name']}</option>";
                }
                $html .= "<option value='{$h['id']}'>{$h['name']}</option>";
            }
        }
        echo  ($html);
    }
}