<?php
/**
 * Created by PhpStorm.
 * User: bhcs
 * Date: 2018/4/28
 * Time: 14:54
 */

namespace app\common\model;


use think\Db;
use think\Model;

class TimezoneModel extends Model
{


    /**
     * 根据 国家/地区 和 时间戳，显示各个国家的实际时间
     * @param $unixTime  unix 时间戳，为time()函数的值
     * @param $zone 区域，类似格式 Asia/Shanghai （亚洲，上海时区）
     * @$isRealUnixTime  true -是，false-否,是否是真的time()时间戳，不含时区
     * @return mixed
     */
    public function showTimeByCountryZone($unixTime,$zone,$isRealUnixTime){

        if($isRealUnixTime){
            $unixTime = $unixTime - date('Z');
            $sql1="SELECT FROM_UNIXTIME($unixTime + tz.gmt_offset, '%a, %d %b %Y, %H:%i:%s') AS local_time ";
        }else{
            $sql1="SELECT FROM_UNIXTIME($unixTime, '%a, %d %b %Y, %H:%i:%s') AS local_time ";
        }
        $sql=$sql1
            ." FROM `dyhl_timezone` as tz JOIN `dyhl_zone` as z "
            ." ON tz.zone_id=z.zone_id "
            ." WHERE tz.time_start <= UNIX_TIMESTAMP(UTC_TIMESTAMP()) AND z.zone_name="."'".$zone."'"
            ." ORDER BY tz.time_start DESC LIMIT 1 ;" ;
        $time=DB::query($sql);
        return $time;
    }

    public function getZone(){
        return $data = Db::name('zone')->select();
    }
}