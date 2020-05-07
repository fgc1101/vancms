<?php
namespace app\weixin\controller;

use think\Controller;
use EasyWeChat\Factory;

class Wauth extends Controller{

    public function server_content(){
        $config = config('wechat.official_account.default');
        $app = Factory::officialAccount($config);

        $app->server->push(function ($message) {
            return "您好！你正在使用范国超的微信测试号";
        });

        $response = $app->server->serve();

        // 将响应输出
        $response->send();exit;
    }
}