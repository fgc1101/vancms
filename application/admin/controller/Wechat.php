<?php

namespace app\admin\controller;

use think\Controller;
use app\admin\model\WxUser as WxUserModel;
use think\facade\Config;

class Wechat extends Controller
{

    /**
     * 微信用户列表
     * @param WxUserModel $WxUserModel
     * @return mixed|\think\response\Json
     */
    public function wx_user_index(WxUserModel $WxUserModel){
        if ($this->request->isAjax()) {
            $cur_page = input('page', 1, 'intval');
            $keyword = input('keyword', '', 'urldecode');
            $page_size = input('limit', Config::get('page_size'), 'intval');
            $map = $keyword ? "username like '%{$keyword}%'" : '';
            $json = $WxUserModel->getDataByWhere($map, $cur_page, $page_size);
            return json($json);
        } else {
            $page_size = Config::get('page_size');
            return $this->assign('page_size', $page_size)->fetch();
        }
    }
}
