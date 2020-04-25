<?php
namespace tool;

class Pdf2png{
    /**
     * 将pdf文件转化为多张png图片
     * @param string $pdf  pdf所在路径 （/www/pdf/abc.pdf pdf所在的绝对路径）
     * @param string $path 新生成图片所在路径 (/www/pngs/)
     *
     * @return array|bool
     */
    public function __construct()
    {

    }

    public function pdf2png($pdf, $path)
    {
        if (!extension_loaded('imagick')) {
            return false;
        }
        if (!file_exists($pdf)) {
            return false;
        }
        $im = new \Imagick();
        $im->setResolution(120, 120); //设置分辨率 值越大分辨率越高
        $im->setCompressionQuality(100);
        $im->readImage($pdf);
        foreach ($im as $k => $v) {
            $v->setImageFormat('png');
            $fileName = $path . md5($k . time()) . '.png';
            if ($v->writeImage($fileName) == true) {
                $return[] = $fileName;
            }
        }
        return $return;
    }

}