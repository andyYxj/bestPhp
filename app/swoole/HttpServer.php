<?php
/**
 *
 * /Applications/MAMP/bin/php/php7.1.1/bin/php  HttpServer.php
 * Created by PhpStorm.
 * User: andyYu
 * Date: 2018/2/6
 * Time: 17:16
 */
$http = new swoole_http_server("127.0.0.1", 9501);

$http->on("start", function ($server) {
    echo "Swoole http server is started at http://127.0.0.1:9501\n";
});

$http->on("request", function ($request, $response) {
    $response->header("Content-Type", "text/plain");
    $response->end("Hello World\n");
});

$http->start();