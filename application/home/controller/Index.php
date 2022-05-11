<?php
namespace app\home\controller;
use think\Controller;
use think\Db;
use think\facade\Env;
use think\facade\Session;

class Index extends Controller {

    public function index(){
        //Session::clear();
        echo "首页";
    }

    /**
     * 生成二维码功能
     * @param string $url
     * @return string
     */
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

    /**
     * pdf装成png图片
     */
    public function pdftopng(){

        $pdf = new \Spatie\PdfToImage\Pdf('../public/datas/jianli.pdf');
        foreach (range(1, $pdf->getNumberOfPages()) as $pageNumber) {
            $pdf->setPage($pageNumber)
                ->saveImage('../public/datas/png/page'.$pageNumber.'.jpg');
        }

    }

    public function clock(){
        // 清除缓存
        // Cache::clear();

        // var_dump("清除缓存成功");die;

        $usercard = $this->request->param('user_num','');
        // $uid = $this->request->param('uid','');

        $clockTime = $this->request->param('clockTime','1622123934');

        $data = [
            // 'uid'=>$uid,
            's_month'=>'2103',
            'sign_time'=>date('Y-m-d H:i:s',$clockTime),
            'classid'=>'',
            'clock_status'=>1,
            'user_num'=>$usercard,
        ];
        
        $res = Db::name('jy_attendance_record')->strict(false)->insert($data);
        // asleep(1);
        if($res){
            return json(['code'=>1,'msg'=>'打卡成功，打卡的时间是'.date("Y-m-d H:i:s",time()).'--打卡用户是'.$usercard]);
        }else{
            return json(['code'=>1,'msg'=>'打卡失败，打卡的时间是'.date("Y-m-d H:i:s",time())]);
        }


//        if(empty($usercard) || empty($clockTime)){
//            return json(['code'=>1,'msg'=>'必填参数为空']);
//        }
//
//        if(cache('daka_'.$usercard) === 1){
//            return json(['code'=>0,'msg'=>'打卡失败，已经打完卡了']);
//        }
//
//        // 设置缓存
//        cache('daka_'.$usercard,1);




    }
}