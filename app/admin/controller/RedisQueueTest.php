<?php
/**
 * Created by andyYu.
 * User: yuxianjun001@icloud.com
 * Date: 2018/3/8 0008
 * Time: 10:18
 */

namespace App\admin\controller;


class RedisQueueTest
{
    public function push(){
        $redis=new \Redis();
        $redis->connect('127.0.0.1',6379);
        $pwd='';
       // $redis->auth($pwd);
        $arrary=[
            1,
            2,
            3,
            4,
            5
        ];
        foreach($arrary as $y=>$v){
            $redis->rPush('queue1',$v);
        }
    }

    public function pop(){
        $redis = new \Redis();
        $redis->connect('127.0.0.1',6379);
        $password = '';
        //$redis->auth($password);
        //list类型出队操作
        $value = $redis->lpop('queue1');
        if($value){
            echo "出队的值".$value;
        }else{
            echo "出队完成";
        }
    }

}