<?php
namespace app\home\controller;
use think\Controller;
use think\Db;
use think\facade\Env;
use tool;

class Index extends Controller {

    public function index(){
        echo '首页';
    }

    public function scerweima($url='http://www.baidu.com'){
        include_once '../extend/tool/phpqrcode.php';

        $value = $url;					//二维码内容

        $errorCorrectionLevel = 'L';	//容错级别
        $matrixPointSize = 5;			//生成图片大小

        //生成二维码图片
        $filename = './uploads/qrcode/'.time().'.png';
        $time = time();
        $qrcode = new \QRcode();
        $qrcode::png($value,$filename , $errorCorrectionLevel, $matrixPointSize, 2);

        $QR = $filename;				//已经生成的原始二维码图片文件


        $QR = imagecreatefromstring(file_get_contents($QR));

        //输出图片
        imagepng($QR, 'qrcode.png');
        imagedestroy($QR);
        $url = '/uploads/qrcode/'.$time.'.png';
        return '<img src='.$url.' alt="使用微信扫描支付">';
    }
}