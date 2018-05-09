<?php

/**
 * Created by
 * yuxianjun001@icloud.com.
 * User: wuchen
 * Date: 2018/1/31
 * Time: 17:23
 */
class Container
{
    public $bindings;
    public function bind($abstract,$concrete){
        $this->bindings[$abstract]=$concrete;
    }


    public function make($abstract,$params=[]){
        return call_user_func_array($this->bindings[$abstract],$params);
    }


}