<?php
/**
 * Created by
 * yuxianjun001@icloud.com.
 * User: wuchen
 * Date: 2018/1/31
 * Time: 14:06
 */
if(!function_exists('getLastItemOfArray')){
    /**
     * 返回一个数组的最后一个元素
     * Created by
     * yuxianjun001@icloud.com.
     * User: wuchen
     * @param $array
     * @return array
     */
    function getLastItemOfArray($array){
        if(!empty($array)){
            return array_slice($array,-1,1);
        }
        return false;
    }

}