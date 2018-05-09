<?php
/**
 * Created by yuxianjun.
 * User: yuxianjun
 * Date: 2018/4/28
 * Time: 15:35
 */

namespace app\helpers;


use app\common\model\TimezoneModel;

class TimeZoneHelper
{
    public static $model;
    public static function showTimeByCountryZone($unixTime,$zone){
        self::$model=new TimezoneModel();
        $time=self::$model->showTimeByCountryZone($unixTime,$zone,$isRealUnixTime=true);
        return $time[0]['local_time'];
    }
}