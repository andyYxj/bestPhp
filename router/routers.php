<?php
/**
 * 路由文件
 * Created by  yuxianjun001@icloud.com.
 * User: wuchen
 * Date: 2017/12/8
 * Time: 16:10
 */

use NoahBuscher\Macaw\Macaw;

Macaw::get('index','App\admin\controller\Home@index'); //测试参考路由

Macaw::get('info','App\admin\controller\Home@companyInfo'); //测试参考路由2
Macaw::get('date','App\admin\controller\Test@date');//日期
Macaw::get('validator','App\admin\controller\ValidationTest@test');//输入验证测试
Macaw::get('log','App\admin\controller\Log@index');

Macaw::get('name','App\admin\controller\NameTest@index');//名字测试

Macaw::get('taobao','App\admin\controller\Taobao@getTaobaoHome');
Macaw::get('guzzle','App\admin\controller\Test@guzzleTest');
Macaw::get('session','App\admin\controller\SessionTest@session');

Macaw::get('menu','App\admin\controller\Menu@index');


//reedis 队列测试
Macaw::get('queuePush','App\admin\controller\RedisQueueTest@push');
Macaw::get('queuePop','App\admin\controller\RedisQueueTest@pop');


Macaw::get('test', function() {
    echo "成功！";die;
});

Macaw::get('(:all)', function($fu) {
    echo '未匹配到路由<br>'.$fu;
});

Macaw::error(function() {
    echo '404 :: Not Found';
});

Macaw::$error_callback = function() {

    throw new Exception("路由无匹配项 404 Not Found");

};

Macaw::dispatch();