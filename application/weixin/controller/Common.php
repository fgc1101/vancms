<?php


namespace app\weixin\controller;

use think\Controller;
use think\Request;
use EasyWeChat\Factory;

class Common extends Controller
{

    protected $config;

    protected $app;

    protected $aouth;

    public function initialize()
    {
        $this->config = config('wechat.official_account.default');

        $this->app = Factory::officialAccount($this->config);
        parent::initialize();
    }

}
