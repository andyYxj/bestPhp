<?php
/**
 * Created by  yuxianjun001@icloud.com.
 * User: wuchen
 * Date: 2017/12/11
 * Time: 15:27
 */

define('APP_NAME','nbPhp');//应用程序名称
define('DS', DIRECTORY_SEPARATOR);//目录分隔符
define('EXT', '.php');
define('BASE_PATH', dirname(dirname(__FILE__)).DS);//项目基目录

define('VENDOR_PATH',BASE_PATH. 'vendor'.DS);
define('COMMON_LIBRARY',BASE_PATH.'common/library'.DS);//公共库第三方库目录
define('CACHE_PATH', BASE_PATH . 'cache'.DS);//公共缓存目录
define('RUNTIME_PATH', BASE_PATH . 'cache/runtime'.DS);//公共运行时目录
define('CORE_PATH', BASE_PATH . 'core'.DS);//系统启动核心目录
define('COMMON', BASE_PATH . 'common'.DS);//公共目录
define('STORAGE', BASE_PATH . 'storage'.DS);//公共目录

// 环境常量
define('IS_CLI', PHP_SAPI == 'cli' ? true : false);
define('IS_WIN', strpos(PHP_OS, 'WIN') !== false);
