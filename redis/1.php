<?php
//实列化
$redis = new Redis();
//连接
$redis->connect('127.0.0.1',6379);
//设置key
var_dump($redis->set('time',time()));
echo '<hr>';
//获取key
//
//
var_dump($redis->get('time'));