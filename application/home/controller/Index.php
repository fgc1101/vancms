<?php
namespace app\home\controller;
use think\Controller;
use think\Db;
use think\facade\Env;
class Index extends Controller {

    public function index(){
        echo '首页';
    }
}