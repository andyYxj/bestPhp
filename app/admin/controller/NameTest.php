<?php
/**
 * Created by  yuxianjun001@icloud.com.
 * User: wuchen
 * Date: 2018/1/15
 * Time: 15:21
 */

namespace App\admin\controller;
use App\admin\controller\Name;

class NameTest
{
    public function index(){
        $name=new Name();
        echo $name->getName();
    }

}