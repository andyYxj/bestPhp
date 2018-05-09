<?php
/**
 * Created by PhpStorm.
 * User: wuchen
 * Date: 2017/6/21 0021
 * Time: 10:42
 */

/**
 * 创建目录
 */
   function makeDir($path){
    try {
        if (!is_dir($path)) {
            $res = mkdir(iconv("UTF-8", "GBK", $path), 0777, true);
            if (!$res) {
                die('目录创建失败！');
            }
        }else{
            @chmod($path,0777);
        }
    }catch(Exception $e){
        print_r($e->getMessage());
    }

}