<?php
namespace app\weixin\controller;

use think\Controller;
use EasyWeChat\Factory;
use think\facade\Session;

class Wauth extends Controller{


    public function oauth_callback(){

        $app = Factory::officialAccount(config('wechat.official_account.default'));
        $oauth = $app->oauth;
        $user = $oauth->user();
        Session::set('wechat_user',$user->toArray());
        $targetUrl = empty(Session::get('target_url')) ? '/weixin/index/index' : Session::get('target_url');

        $this->redirect($targetUrl);

    }
}