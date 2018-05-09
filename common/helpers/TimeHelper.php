<?php
/**
 * 时间辅助函数类
 * Created by yuxianjun.
 * User: yuxianjun
 * Date: 2018/4/27
 * Time: 17:27
 */

namespace app\helpers;


class TimeHelper
{

    /**
     * 根据unix时间戳和时区
     * 显示不同国家所在区域的当地时间
     * @param $timeStamp时间戳 为time() 函数的返回值
     * @param $timeZone  时区 取值范围 ：0到11（东），-1到-11（西）
     * @return false|string
     */
    public static function showTime($timeZone=8,$timeStamp=false){
        if(is_numeric($timeZone)){
            $areaEast=[
                0,1,2,3,4,5,6,7,8,9,10,11,
            ];
            $areaWest=[
                -1,-2,-3,-4,-5,-6,-7,-8,-9,-10,-11,
            ];
            if(in_array($timeZone,$areaEast) || in_array($timeZone,$areaWest)){
                return gmdate('Y-m-d H:i:s',is_numeric($timeStamp)?$timeStamp:time() + $timeZone*3600);
            }
        }
        return false;//时区格式不对
    }

    /**
     * 设置时间，unix 时间戳
     * 该系统 数据库保存的时间均采用此值
     * @return int
     */
    public static  function getTime(){
        date_default_timezone_set("Etc/GMT");
        return time();
    }

    /**
     * 把不同国家的时间 根据时间转为统一的unix时间戳
     * @param $date  时间 2018-5-03
     * @param $timeZone  时区  Asia/shanghai
     */
    public function getUnixTimeByDate($date,$timeZone){

    }

}