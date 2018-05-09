<?php
/**
 * Created by  yuxianjun001@icloud.com.
 * User: wuchen
 * Date: 2017/12/11
 * Time: 13:40
 */

//载入.env 配置文件
$dotenv = new Dotenv\Dotenv(__DIR__.'/../');
$dotenv->load();

//引入 laravel  illuminate/database
use Illuminate\Database\Capsule\Manager as Capsule;
// Eloquent ORM
$capsule = new Capsule;
$capsule->addConnection(require  BASE_PATH.'/config/database.php');
$capsule->setAsGlobal();  //this is important
$capsule->bootEloquent();


//注册 filp/whoops  // whoops 错误提示
$whoops = new \Whoops\Run;
$whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler);
$whoops->register();








