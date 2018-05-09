<?php
/**
 * Created by  yuxianjun001@icloud.com.
 * User: wuchen
 * Date: 2017/12/8
 * Time: 16:57
 */

namespace App\home\controller;
use App\home\model\Company  as Company;
class Home
{

    public function index(){
        echo "hello myFrame!";
    }

    /**
     * 获取公司信息
     */
    public function companyInfo(){
      $all=Company::all();
      foreach ($all as $v){
          echo $v.'<br/>';
      }
    }


}