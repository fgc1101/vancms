<?php
namespace app\weixin\controller;

use EasyWeChat\Factory;
use think\Controller;
use think\facade\Session;
use think\Request;

class Index extends Controller {

    //进行微信授权登录的验证
    protected $middleware = ['auth'];

    public function index(){
        echo "weixin shouye";
        var_dump(Session::get('wechat_user'));
    }

    public function index1(){
        echo "微信首页";
    }
}