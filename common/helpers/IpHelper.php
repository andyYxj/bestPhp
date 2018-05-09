<?php
/**
 * Created by yuxianjun.
 * User: yuxianjun
 * Date: 2018/4/20
 * Time: 16:15
 */

namespace app\helpers;


class IpHelper
{
    /*
 * 获取请求的IP地址
 * */
    public static function getUserIp()
    {
        if (getenv("HTTP_CLIENT_IP"))
            $ip = getenv("HTTP_CLIENT_IP");
        else if(getenv("HTTP_X_FORWARDED_FOR"))
            $ip = getenv("HTTP_X_FORWARDED_FOR");
        else if(getenv("REMOTE_ADDR"))
            $ip = getenv("REMOTE_ADDR");
        else $ip = "Unknow";
        //整理IP
        $ip = trim($ip);
        //过滤IP
        if ((bool)filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4 | FILTER_FLAG_IPV6 | FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)) {
            return $ip;
        }else{
            return null;
        }
    }

    /**
     * 根据ip地址反查询地区代码
     * @param $ip
     */
    public static function checkReginByIp($ip){
        include(ROOT_COMMON_PATH.DS.'file/ip/geoip.inc');
        //打开本地数据库, 数据保存在 GeoIP 文件中.
        $geoData = geoip_open(ROOT_COMMON_PATH.DS.'file/ip/GeoIP.dat', GEOIP_STANDARD);

        //获取国家 IP
        $countryCode = geoip_country_code_by_addr($geoData, $ip);
        //获取国家名称
        //$countryName = geoip_country_name_by_addr($geoData, $ip);//47.91.76.155

        //关闭本地数据库
        geoip_close($geoData);
        return $countryCode?:false;

    }

}