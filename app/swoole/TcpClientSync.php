<?php
/**
 *
 * tcp 客户端  同步模式
 * Created by PhpStorm.
 * User: andyYu
 * Date: 2018/2/6
 * Time: 17:52
 */

$client = new swoole_client(SWOOLE_SOCK_TCP);

//连接到服务器
if (!$client->connect('127.0.0.1', 9502, 0.5))
{
    die("connect failed.");
}
//向服务器发送数据
if (!$client->send("hello 123"))
{
    die("send failed.");
}
//从服务器接收数据
$data = $client->recv();
if (!$data)
{
    die("recv failed.");
}
echo $data;
//关闭连接
$client->close();