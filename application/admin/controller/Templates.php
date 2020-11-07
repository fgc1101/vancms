<?php
/* @project : VanCms
 * @auther  : simplevan
 * @date    : 2020年11月06日22:20:29
 * @desc    : 模板控制器
 */
namespace app\admin\controller;
use app\common\model\Templates as TemplatesModel;


use think\facade\Config;

class Templates
{
    /***
     * desc 模板首页列表展示
     * @param TemplatesModel $templatesModel
     * @return \think\response\Json
     */
    public function index(TemplatesModel $templatesModel){
        if ($this->request->isAjax()) {
            $cur_page = input('page', 1, 'intval');
            $keyword = input('keyword', '', 'urldecode');
            $page_size = input('limit', Config::get('page_size'), 'intval');
            $map = $keyword ? "name like '%{$keyword}%'" : '';
            $json = $templatesModel->getListByWhere($map, $cur_page, $page_size);
            return json($json);
        } else {
            $page_size = Config::get('page_size');
            return $this->assign('page_size', $page_size)->fetch();
        }
    }
}