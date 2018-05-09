<?php
/**
 * Created by  yuxianjun001@icloud.com.
 * User: wuchen
 * Date: 2017/12/11
 * Time: 13:46
 */
return [
    'driver'    => env('DB_CONNECTION', 'mysql'),
    'host'      => env('DB_HOST','121.199.182.2'),
    'database'  => env('DB_DATABASE','xdianshang_crm'),
    'username'  => env('DB_USERNAME','root'),
    'password'  => env('DB_PASSWORD','root'),
    'charset'   => 'utf8',
    'collation' => 'utf8_general_ci',
    'prefix'    => '',
    'port'=>env('DB_PORT',30001),
];