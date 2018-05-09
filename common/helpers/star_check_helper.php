<?php

/**
 * 判断是不是五星好评
 * @param type 图片URL或者图片本地路径
 */
function isFiveStarImage($platForm,$imageUrlOrPath)
{
    $newData = getValidateImageData($imageUrlOrPath);

    $fiveStarNum = 0;
    foreach ($newData as $data) {
        if (isFiveStartData($data)) {
            $fiveStarNum ++;
        }
    }
    switch($platForm){
        case 1:
        case 13:
            return ($fiveStarNum>=2)?1:0;//淘宝，天猫
        case 7:
            return ($fiveStarNum>=1)?1:0; //京东
        default:
            return ($fiveStarNum>=2)?1:0;
    }

}

/*
 * 图片主要（三通道）颜色判断
 * author cuitengwei
 * 2014/1/16
 */

function getValidateImageData($imgUrl)
{
    $imageObject = getPressedImageObject($imgUrl, 64);

    //循环色值
    $data = $newData = [];
    $xLength = imagesx($imageObject);
    $yLength = imagesy($imageObject);

    $color = [];
    for ($x = 0; $x < $xLength; $x++) {
        for ($y = 0; $y < $yLength; $y++) {
            $rgb = imagecolorat($imageObject, $x, $y);

            //三通道
            $r = ($rgb >> 16) & 0xFF;
            $g = ($rgb >> 8) & 0xFF;
            $b = $rgb & 0xFF;

            if ($r < 200 || $g > 200 || $b > 200) {
                $color[$y][$x] = 0;
                $data[$y][$x] = 0;
            } else {
                $data[$y][$x] = 1;
                $color[$y][$x] = "$r,$g,$b";
            }
        }
    }

    $xAll = count($data);
    $yAll = count(current($data));
    $lastX = -1;
    for ($x = 0; $x < $xAll; $x ++) {

        //处理掉连续1，或者没有1的情况
        for ($y = 0; $y < $yAll; $y ++) {
            if (!empty($data[$x][$y]) && $y < $yAll - 1) {

                for ($z = $y + 1; $z < $yAll; $z ++) {
                    if (!empty($data[$x][$z])) {
                        $data[$x][$z] = 0;
                        $y ++;
                        continue;
                    }
                    break;
                }
            }
        }

        //查看是否不少于 5个星
        $goodNum = 0;
        for ($y = 0; $y < $yAll; $y ++) {
            if (!empty($data[$x][$y])) {
                $goodNum ++;
            }
        }

        if ($goodNum < 5) {
            continue;
        }


        //把一个星星多个符合的行，压缩成一行
        if ($lastX + 1 == $x) {
            $lastX = $x;
            continue;
        }

        $lastX = $x;
        $newData[] = $data[$x];
    }

    unset($data);

    return $newData;
}

/**
 * 1、缩小尺寸。将图片缩小到8×8的尺寸，总共64个像素。这一步的作用是去除图片的细节，
 * 只保留结构、明暗等基本信息，摒弃不同尺寸、比例带来的图片差异。
 * @param type $src
 * @param type $newWidth
 * @return type
 */
function getPressedImageObject($src, $newWidth)
{
    $imageInfo = getimagesize($src);

    //图片类型
    $imgType = strtolower(substr(image_type_to_extension($imageInfo[2]), 1));

    //方便配置长度宽度、高度，设置框为变量wid,高度为hei
    //判断长度和宽度，以方便等比缩放,规格按照500, 320
    $wid = $newWidth;
    $hei = $imageInfo[1] * $newWidth / $imageInfo[0];

    //对应函数
    $imageFun = 'imagecreatefrom' . ($imgType == 'jpg' ? 'jpeg' : $imgType);
    $oldImageObject = $imageFun($src);

    //在内存中建立一张图片
    $pressImageObj = imagecreatetruecolor($wid, $hei);

    //将原图复制到新建图片中
    imagecopyresampled($pressImageObj, $oldImageObject, 0, 0, 0, 0, $wid, $hei, $imageInfo[0], $imageInfo[1]);

    //销毁原始图片
    imagedestroy($oldImageObject);

    return $pressImageObj;
}

/**
 * 是否符合五星好评的数据分布，就单行数据而言
 * @param type $data
 * @return boolean
 */
function isFiveStartData($data)
{
    $yAll = count($data);

    $getAry = [];

    for ($y = 0; $y < $yAll; $y ++) {
        if (!empty($data[$y])) {
            $getAry[] = $y;
        }
    }

    $splitAry = [];
    $ySplit = count($getAry);

    $allSplitNum = 0;
    for ($y = 1; $y < $ySplit; $y ++) {
        $splitIndex = $getAry[$y] - $getAry[$y - 1];
        $splitAry[] = $splitIndex;
        $allSplitNum += $splitIndex;
    }

    //投票选择使用哪个作为分段
    if (count($splitAry) > 4) {

        $avgSplitNum = (int) ($allSplitNum / count($splitAry));

        $tmpAry = [];
        foreach ($splitAry as $splitVal) {
            $tmpAry[abs($splitVal - $avgSplitNum)][] = $splitVal;
        }

        //找出和均值差别最小的4个
        ksort($tmpAry);
        foreach ($tmpAry as $ary) {
            foreach ($ary as $aryVal) {
                $needAry[] = $aryVal;
            }
        }

        //这四个作为识别目标
        $splitAry = [];
        for ($y = 0; $y < 4; $y ++) {
            $splitAry[] = $needAry[$y];
        }
    }

    //4个识别目标之间的间隔像素小于3个
    if (count($splitAry) == 4) {
        $firstSplit = $splitAry[0];
        for ($y = 1; $y < 4; $y ++) {
            if (abs($splitAry[$y] - $firstSplit) > 2) {
                return FALSE;
            }
        }
        return TRUE;
    }
    return FALSE;
}
