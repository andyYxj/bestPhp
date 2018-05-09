<?php
/**
 * Created by PhpStorm.
 * User: wuchen
 * Date: 2017/6/21 0021
 * Time: 10:50
 */

/**
 * 判断当前操作系统是win（1）还是linux,其他（-1）
 */
 function telOs(){
    if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
        return 1;
    } else {
        return -1;
    }
}