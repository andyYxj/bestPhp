<?php
/**
 * Created by  yuxianjun001@icloud.com.
 * User: wuchen
 * Date: 2017/12/8
 * Time: 16:57
 */

namespace App\admin\controller;
use App\admin\model\Company  as Company;
use Common\controller\Controller;
//use Illuminate\Http\Request;

class Home extends Controller
{

    public function index(){
        echo generateRandomString(10);die;
        echo "hello myFrame!";
    }

    /**
     * 获取公司信息
     */
    public function companyInfo(){
      $all=Company::all();
      $this->response(200,'请求成功!',$all);die;
      foreach ($all as $v){
          echo $v.'<br/>';
      }
    }


}