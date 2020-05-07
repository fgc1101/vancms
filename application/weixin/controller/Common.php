<?php
namespace app\weixin\controller;

use think\Controller;
use EasyWeChat\Factory;
use think\facade\Session;
use app\common\model\User as UserModel;

class Common extends Controller{

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