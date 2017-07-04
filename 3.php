<?php
/**
 * Created by PhpStorm.
 * User: 22750
 * Date: 2017/7/4
 * Time: 20:25
 */
$db = mysqli_connect('192.168.38.99','mysql','mysql','bill','3306');
mysqli_query('set names utf8');
$fp = fopen('./lock.txt','w');
$persion = 1;
if(flock($fp,LOCK_EX)){
        $sql = "select total,type from memory";
        $result = mysqli_query($db,$sql);
        $num = mysqli_fetch_assoc($result);
        $total = $consumption+$num['total'];
        $type = $type+$num['type'];
        $insert_sql = "update memory set total={$total},type={$type}";
        mysqli_query($db,$insert_sql);
        flock($fp,LOCK_UN);
}
fclose($fp);