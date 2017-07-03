<?php

/**
 * Created by PhpStorm.
 * User: 22750
 * Date: 2017/6/30
 * Time: 21:23
 */
$db = mysqli_connect('localhost','root','123456',3306);
mysqli_query($db,"set names utf8");
begin;
$sql = "select * from goods where id = 90 lock in share mode";
$result = mysqli_query($db,$sql);
$goods_store = mysqli_fetch_assoc($result);
$goods_store = $goods_store['goods_store'];
$goods_store = $goods_store-1;
$update_sql = "update goods set goods_store = $goods_store where id = 90";
$rec = mysqli_query($db,$update_sql);
if($rec){
    commit;
}else{
    rollback;
}