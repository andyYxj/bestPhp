<?php
/**
 * Created by  yuxianjun001@icloud.com.
 * User: wuchen
 * Date: 2017/12/8
 * Time: 16:07
 */

require __DIR__.'/../bootstrap/constants.php';
require  __DIR__."/../bootstrap/autoload.php";
$app = require_once __DIR__.'/../bootstrap/app.php';
/*//laravel 系统启动 begin
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

$response->send();
$kernel->terminate($request, $response);
// laravel 系统启动 end*/

require __DIR__.'/../router/routers.php';
