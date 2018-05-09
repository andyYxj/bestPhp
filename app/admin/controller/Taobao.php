<?php
/**
 * Created by  yuxianjun001@icloud.com.
 * User: wuchen
 * Date: 2017/12/21
 * Time: 19:31
 */

namespace App\admin\controller;
//laravel 独立版本blade模板引擎加载
use Jenssegers\Blade\Blade;


class Taobao
{
    //获取淘宝店铺首页数据
    public function getTaobaoHome()
    {
        $url='http://www.baidu.com/';
        $ch= curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);//不自动输出内容
        curl_setopt($ch, CURLOPT_HEADER, 0);//不返回头部信息
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT_MS, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            "User-Agent: {Mozilla/5.0 (Windows NT 6.1; WOW64; rv:26.0) Gecko/20100101 Firefox/26.0}",
            "Accept: {text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8}",
            "Accept-Language: {zh-cn,zh;q=0.8,en-us;q=0.5,en;q=0.3}",
            'X-FORWARDED-FOR:111.222.333.4',
            'CLIENT-IP:121.222.67.100',
            "Cookie:{cq=ccp%3D1; cna=a7suCzOmSTECAXgg9iCf4AtX; t=671b2069c7e8ac444da66d664a397a5f; tracknick=%5Cu4F0D%5Cu6653%5Cu8F8901; _tb_token_=nDiU1vCuzFd0; cookie2=c54709ffbe04a5ccb80283c34d6b00fa; pnm_cku822=128WsMPac%2FFS4KgNn%2BYfhzduo4U2NC0zh9cAS4%3D%7CWUCLjKhqr873bOIFQcMecSw%3D%7CWMEKRlV%2B3D9a6XWaidNWNQOSWXwaXugvQHzhxALh%7CX0YLbX78NUR2b2DHoxnIqZENQqR35TBZbfQ5vooI0b6GHZA3U1kr%7CXkdILogCr878ZK9I%2B%2FE3QjAD3lFJJaAZRA%3D%3D%7CXUeMwMR2s%2BTUQk8IPP5TNgWfUjQwonccMCxihTa0fRYgtjgfa4j6%7CXMYK7F8liOvH3hMUpzXkiaU%2FJw%3D%3D}",
        ));
        curl_setopt($ch, CURLOPT_REFERER, "http://www.test.com");

        curl_setopt($ch, CURLOPT_NOBODY, 0);

        //执行curl
        $output = curl_exec($ch);
        if($output===false){
            die(curl_error($ch));
        }

        // 检查是否有错误发生
        if(curl_errno($ch)){
            echo 'Curl error: ' . curl_error($ch);
        }

        curl_close($ch);
        file_put_contents(BASE_PATH.'/cache/baidu.html',$output);
        echo  'ok';
    }


    public function showTaobaoPage(){
        return  view('greeting', ['name' => 'James']);
    }

}