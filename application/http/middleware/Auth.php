<?php

namespace app\http\middleware;

use EasyWeChat\Factory;
use think\facade\Session;

class Auth
{
    public function handle($request, \Closure $next)
    {
        $app = Factory::officialAccount(config('wechat.official_account.default'));
        $oauth = $app->oauth;
        // 未登录
        if (empty(Session::get('wechat_user'))) {
            Session::set('target_url','weixin/index/index');

            //return $oauth->redirect();
            $oauth->redirect()->send();
            //return response($oauth->redirect()->send());
        }
        return $next($request);
    }
}
