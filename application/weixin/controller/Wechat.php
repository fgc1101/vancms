<?php

namespace app\weixin\controller;

use think\Controller;
use think\Request;
use EasyWeChat\Factory;

class Wechat extends Common
{

    /**
     *
     */
    public function server_content(){
        $response = $this->app->server->serve();

        // 将响应输出
        $response->send();exit;
    }
}
