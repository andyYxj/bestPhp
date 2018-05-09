<?php
/**
 * Created by
 * yuxianjun001@icloud.com.
 * User: wuchen
 * Date: 2018/1/31
 * Time: 11:53
 */
if(!function_exists('getSimpleClassName')){
    /**
     * 返回类似  App\admin\controller\Log 的类名的真实类名  Log
     * 兼容 类似  Log 的类名 传入直接原样返回
     * Created by
     * yuxianjun001@icloud.com.
     * User: wuchen
     * @param $classname
     * @return mixed|string
     */

    function getSimpleClassName($classname){
        if(!empty($classname)){
            $arrayClass=explode("\\",$classname);
            //单一名字，不带路径的名字认为是类原名称
            if(count($arrayClass)==1){
                return $classname;
            }
           // $name= array_slice($arrayClass,-1,1);//也可
            $name=end($arrayClass);
            return $name;
        }
        return '类名不能未空';
    }
}

if(!function_exists('intiLogName')){
    /**
     * 自动初始化命名空间相关的(子应用相关的)日志存放目录和日志文件
     * Created by
     * yuxianjun001@icloud.com.
     * User: wuchen
     * @return string
     */
    function intiLogName($_name_space_,$_class_){
        $name= STORAGE.'log'.DS.getChildAppName($_name_space_).'Log'.DS.getSimpleClassName($_class_).'.log';
        return $name;
    }
}


if(!function_exists('getChildAppName')){

    /**
     * 获取子应用app 名字
     * （支持类似 namespace App\admin\controller 这样结构的子应用名字的获取） demo: 返回admin
     * Created by
     * yuxianjun001@icloud.com.
     * User: wuchen
     * @param $_name_space_
     * @return mixed
     */
    function getChildAppName($_name_space_){
        return explode("\\",$_name_space_)['1'];
    }
}
