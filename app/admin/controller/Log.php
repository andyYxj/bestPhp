<?php
/**
 * Created by  yuxianjun001@icloud.com.
 * User: wuchen
 * Date: 2018/1/31
 * Time: 11:00
 */

namespace App\admin\controller;
use Common\controller\Controller;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
class Log extends Controller
{
    public function index(){
        //echo intiLogName(__NAMESPACE__,__CLASS__);die;

        $log=new Logger('nbPhp');
        $log->pushHandler(new StreamHandler(intiLogName(__NAMESPACE__,__CLASS__),Logger::WARNING));
        $log->warning('This  is  a warning!');
        $log->error('this is a error!');
    }

}