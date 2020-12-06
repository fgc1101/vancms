<?php
/**
 * Created by Fanguochao
 * User: fgc
 * Date: 2020-12-06
 * Time: 21:59
 * Desc: 上传文件控制器
 */

namespace app\home\controller;


use Qiniu\Auth;
use Qiniu\Storage\UploadManager;
use think\App;
use think\Controller;

class Upload extends Controller
{

    // accessKey
    protected $Qiniu_accessKey = 'Edcij9HelUPqeqvooENb9AeQTnMMdJbin3E10Esg';
    // secretKey
    protected $Qiniu_secretKey = 'xdq0ahvIWZPKfbP2H2AIHbUs73jEEEFIiqFgZ8oq';
    // 空间
    protected $bucket = 'jiangyuan';

    protected $qiniu;


    public function __construct(App $app = null)
    {
        parent::__construct($app);
        $this->qiniu = new UploadManager();

    }

    /**
     * 七牛上传文件
     * @throws \Exception
     */
    public function QiniuUpload(){
        $file = request()->file('image');
        $ext = pathinfo($file->getInfo('name'), PATHINFO_EXTENSION);
        $path = $file->getRealPath();
        $uploadMgr = new UploadManager();
        $auth = new Auth($this->Qiniu_accessKey, $this->Qiniu_secretKey);
        $token = $auth->uploadToken($this->bucket);
        $name = substr(md5($file->getRealPath()),0,5).date('YmdHis').rand(0,9999).'.'.$ext;
        list($ret, $error) = $uploadMgr->putFile($token, $name, $path);
        var_dump($ret);die;
    }

}