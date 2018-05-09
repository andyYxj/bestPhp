<?php
/**
 * Created by PhpStorm.
 * User: wuchen
 * Date: 2017/6/21 0021
 * Time: 16:46
 */

/**
 * 获取文件后缀名
 * @param $file
 * @return mixed
 */
function getImgExt($file)
{
    $temp=explode('.', $file);
    $ext=strtolower(end($temp));
    $options=array(
        'png','jpeg','jpg'
    );
    if(in_array($ext,$options)){
        return $ext;
    }else{
       return 'png';
    }
}

//根据图片后缀名创建指定格式的图片
/**
 * @param $file
 */
function imagecreatefromExt($file){
    $ext=getImgExt($file);
    switch($ext){
        case 'png':
            return imagecreatefrompng($file);
        case 'jpg':
        case 'jpeg' :
            return imagecreatefromjpeg($file);
    }
}
/**根据图片后缀名，保存图片
 * @param $ext
 */
function saveImgByExt($im,$path,$fileName,$ext){
    switch($ext){
        case 'png':
            return   imagepng($im,$path.$fileName.'.'.$ext);
        case 'jpg';
        case 'jpeg' :
            return imagejpeg($im,$path.$fileName.'.'.$ext);
        case 'bmp':
            return imagebmp($im,$path.$fileName.'.'.$ext);
    }

}
/**
 * 图片自动压缩
 * @param $im
 * @param $maxwidth
 * @param $maxheight
 * @param $name
 * @param $filetype
 */
function resizeImage($im,$maxwidth,$maxheight,$name,$filetype,$path)
{
    $pic_width = imagesx($im);
    $pic_height = imagesy($im);
    $resizewidth_tag = $resizeheight_tag = false;

    if(($maxwidth && $pic_width > $maxwidth) || ($maxheight && $pic_height > $maxheight))
    {
        if($maxwidth && $pic_width>$maxwidth)
        {
            $widthratio = $maxwidth/$pic_width;
            $resizewidth_tag = true;
        }

        if($maxheight && $pic_height>$maxheight)
        {
            $heightratio = $maxheight/$pic_height;
            $resizeheight_tag = true;
        }

        if($resizewidth_tag && $resizeheight_tag)
        {
            if($widthratio<$heightratio)
                $ratio = $widthratio;
            else
                $ratio = $heightratio;
        }

        if($resizewidth_tag && !$resizeheight_tag)
            $ratio = $widthratio;
        if($resizeheight_tag && !$resizewidth_tag)
            $ratio = $heightratio;

        $newwidth = $pic_width * $ratio;
        $newheight = $pic_height * $ratio;

        if(function_exists("imagecopyresampled"))
        {
            $newim = imagecreatetruecolor($newwidth,$newheight);
            imagecopyresampled($newim,$im,0,0,0,0,$newwidth,$newheight,$pic_width,$pic_height);
        }
        else
        {
            $newim = imagecreate($newwidth,$newheight);
            imagecopyresized($newim,$im,0,0,0,0,$newwidth,$newheight,$pic_width,$pic_height);
        }

        //创建新图片
        saveImgByExt($newim,$path,$name,$filetype);
    }
    else
    {
        //创建图片
        saveImgByExt($im,$path,$name,$filetype);
    }
}

/**
 * return  图片路径，含名字
 * 功能描述:实现原格式本地保存
 * 只支持 png,jpg,jpeg  格式处理，实现原格式
 * @param $imgUrl  七牛云图片完整地址
 * @param $path     希望存储的本地路径
 * @param $name     希望保存的文件名字
 */
function saveImgFromQiniu($imgUrl,$path,$name){
    //保存远程（七牛云）图片到本地
    try {
        $im = @imagecreatefromExt($imgUrl);//辅助函数
        $fileType = getImgExt($imgUrl);
        saveImgByExt($im, $path, $name, $fileType);
        return  $path.$name.'.'.$fileType;
    }catch(Exception $e){
        print_r($e->getMessage());
    }
}

/**
 * @param $imageFile
 * @return false  如果不支持
 */
function getRealImgExt($imageFile){
    list($width, $height, $type) = getimagesize( $imageFile );
    switch($type){
        case 1: return 'gif';
        case 2: return 'jpg';
        case 3: return 'png';
        case 6: return 'bmp';
        default :return false;
    }
}


/**
 * 通过外部传入后缀名字来创建图片流，
 * 适用于不带后缀名字的图片，或者希望外部传入后缀名字的
 * @param $file
 * @param $ext  可以为空
 * @return resource
 * 后缀处理失败 返回false
 */
function imagecreateByExt($file,$ext=''){
    if(empty($ext)){
        $ext=getRealImgExt($file);
        if(empty($ext)){
            return false;
        }
    }
    switch($ext){
        case 'png':
            return imagecreatefrompng($file);
        case 'jpg':
        case 'jpeg' :
            return imagecreatefromjpeg($file);
    }
}
