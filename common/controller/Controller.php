<?php

/**
 * larvavel 基类
 * Created by  yuxianjun001@icloud.com.
 * User: wuchen
 * Date: 2018/1/5
 * Time: 10:49
 */
namespace Common\controller;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;

use Monolog\Logger;
use Monolog\Handler\StreamHandler;
class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * 统一接口返回格式
     * @param $status
     * @param $data
     * @param $msg
     * @return string
     */
    public function response($status=200,$msg='请求成功!',$data){
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

}