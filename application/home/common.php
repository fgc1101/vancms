<?php
/**
 * PDF2PNG
 * @param $pdf  待处理的PDF文件
 * @param $path 待保存的图片路径
 * @param $page 待导出的页面 -1为全部 0为第一页 1为第二页
 * @return      保存好的图片路径和文件名
 * 注：此处为坑 对于Imagick中的$pdf路径 和$path路径来说，   php版本为5+ 可以使用相对路径。php7+版本必须使用绝对路径。所以，建议大伙使用绝对路径。
 */
function pdf2png($pdf,$path,$page=-1)
{
    if(!extension_loaded('imagick'))
    {
        return false;
    }
    if(!file_exists($pdf))
    {
        return false;
    }
    if(!is_readable($pdf))
    {
        return false;
    }
    $im = new Imagick();
    $im->setResolution(150,150);
    $im->setCompressionQuality(100);
    if($page==-1)
        $im->readImage($pdf);
    else
        $im->readImage($pdf."[".$page."]");
    foreach ($im as $Key => $Var)
    {
        $Var->setImageFormat('png');
        $filename = $path. md5($Key.time()).'.png';
        if($Var->writeImage($filename) == true)
        {
            $Return[] = $filename;
        }
    }
    //返回转化图片数组，由于pdf可能多页，此处返回二维数组。
    return $Return;
}