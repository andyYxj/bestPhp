<?php
/**
 * Created by  yuxianjun001@icloud.com.
 * User: wuchen
 * Date: 2018/1/17
 * Time: 10:17
 */

namespace App\admin\controller;


class SessionTest
{
    public function session(){
        //session的永久保存（在不过期范围内）
        Session::put('name', 'session1');
//get操作
        $value = Session::get('name', 'default');
        echo $value;die;

//去除操作并删除，类似pop概念
        $value = Session::pull('key', 'default');

//检测是否存在key
        Session::has('users');

//删除key
        Session::forget('key');

    }

}