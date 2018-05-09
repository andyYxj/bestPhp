<?php
/**
 * Respect/Validation 验证类用法demo
 * 更多用法，参考项目根目录readme.md文件对应连接
 * Created by  yuxianjun001@icloud.com.
 * User: wuchen
 * Date: 2018/1/5
 * Time: 14:50
 */

namespace App\admin\controller;

use Respect\Validation\Validator as V;
class ValidationTest
{
    public function test(){

        //简易测试
        $number=123;
       // echo V::numeric()->validate($number).'</br>';


        //字母验证
      //  echo v::alpha()->validate('').'</br>'; // false input required
        //echo  v::alpha()->validate(null).'</br>'; // false input required
        echo  V::alpha()->validate('abc').'</br>';
        echo  v::alpha()->validate('a').'</br>';
        echo  v::alpha()->validate('1').'</br>';

        //not 运算
        echo  v::not(v::intVal())->validate(10); // false, input must not be integer

        //区间比较
        echo v::intVal()->between(10, 20)->validate(15).'betwen'; // true

        //时间区间比较
        v::date()->between('2009-01-01', '2013-01-01')->validate('2010-01-01'); // true
        v::date()->between('yesterday', 'tomorrow')->validate('now'); // true
        var_dump(func_get_args());


    }

}