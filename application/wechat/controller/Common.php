<?php
namespace app\wechat\controller;
use think\Controller;

class Common extends Controller{

    public function initialize()
    {
        if(!session('openid')){
            //var_dump(config('weixin')['appid']);die;
            $this->redirect('wechat/auth');
        }

    }


    public function index(){
        echo "wechat index";
    }
}