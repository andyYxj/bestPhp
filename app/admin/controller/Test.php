<?php
/**
 * Created by  yuxianjun001@icloud.com.
 * User: wuchen
 * Date: 2017/12/26
 * Time: 17:24
 */

namespace App\admin\controller;
use GuzzleHttp\Client as Client;

class Test
{
    /**
     * guzzle 测试
     */
    public function guzzleTest(){
        $client=new Client();
        $res=$client->request('GET','http://blog.csdn.net/donglynn/article/details/52883033');
        echo  $res->getStatusCode().'<br/>';
        echo  $res->getHeader().'<br/>';
        echo $res->getBody().'<br/>';
    }
    public function date(){
        header('P3P: CP="CURa ADMa DEVa PSAo PSDo OUR BUS UNI PUR INT DEM STA PRE COM NAV OTC NOI DSP COR"');
         setcookie("test", 123456, time()+3600, "/", ".nbphp.com");
       // setcookie("test2", 123456, time()+3600, "/", "appscrmdev.ecbao.cn");
        var_dump($_COOKIE);
        var_dump(env('DB_HOST'));

       /* $date=date_create("2013-03-15 23:40:00",timezone_open("Europe/Oslo"));
        echo date_format($date,"Y/m/d H:iP");*/
    }

}