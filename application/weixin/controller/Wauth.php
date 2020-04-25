<?php
namespace app\weixin\controller;

use think\Controller;
use EasyWeChat\Factory;
use think\facade\Session;
use app\common\model\User as UserModel;

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

    public function oauth_callback(){

        $app = Factory::officialAccount(config('wechat.official_account.default'));
        $oauth = $app->oauth;
        $user = $oauth->user();
        Session::set('wechat_user',$user->toArray());

        //判断这个用户是否在数据库中存在 不存在就插入
        $userinfo = Session::get('wechat_user');
        $res = UserModel::where('openid','=',$userinfo['original']['openid'])->find();
        if(!$res){
            //入库
            UserModel::create($userinfo['original']);
        }

        //跳转
        $targetUrl = empty(Session::get('target_url')) ? '/weixin/index/index' : Session::get('target_url');
        $this->redirect($targetUrl);

    }
}