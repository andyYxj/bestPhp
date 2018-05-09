<?php
/**
 * Created by  yuxianjun001@icloud.com.
 * User: wuchen
 * Date: 2018/1/30
 * Time: 9:48
 */

/**
 * 判断是否是https 请求
 */
if(!function_exists('is_https')){
    function is_https(){
         return  isset($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) !== 'off' ? true : false;
    }
}


/**
 * 统一接口返回格式
 * @param $status
 * @param $data
 * @param $msg
 * @return string
 */
 function response($status=200,$msg='请求成功!',$data){
    $return= json_encode([
        'status'=>$status,
        'msg'=>$msg,
        'data'=>$data,
    ]);

    $jsonpCallback=isset($_REQUEST['jsonpCallback'])?trim($_REQUEST['jsonpCallback']):'';
    if(!empty($jsonpCallback)){
        //jsonp处理
        echo $jsonpCallback.'('.$return.')';//jsonp
    }else{
        //一般json处理
        header('Content-Type: application/json');
        echo $return;
    }
}