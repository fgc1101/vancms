<?php
/**
 * Created by Fanguochao
 * User: fgc
 * Date: 2020-11-06
 * Time: 22:49
 * Desc: 广告控制器
 */

namespace app\admin\controller;

use app\common\model\Ad as AdModel;
use think\Controller;
use think\facade\Config;


class Ad extends Controller
{
    /***
     * 广告首页
     * @param AdModel $adModel
     * @return mixed|\think\response\Json
     */
    public function index(AdModel $adModel){
        if ($this->request->isAjax()) {
            $cur_page = input('page', 1, 'intval');
            $keyword = input('keyword', '', 'urldecode');
            $page_size = input('limit', Config::get('page_size'), 'intval');
            $map = $keyword ? "name like '%{$keyword}%'" : '';
            $json = $adModel->getListByWhere($map, $cur_page, $page_size);
            return json($json);
        } else {
            $page_size = Config::get('page_size');
            return $this->assign('page_size', $page_size)->fetch();
        }
    }

    public function add(){
        return $this->fetch();
    }
}