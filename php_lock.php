<?php
/**
 * Created by PhpStorm.
 * User: 22750
 * Date: 2017/6/30
 * Time: 20:57
 */
$db = mysqli_connect('localhost','root','123456','test',3306);
mysqli_query($db,'set names utf8');

$presion = 1;

$sql = "select * from lock_num where id = 1";
$result = mysqli_query($db,$sql);
$num = mysqli_fetch_assoc($result);

$num =$num['num']+$presion;
$insert_sql = "update lock_num set num={$num} where id = 1]";
mysqli_query($db,$insert_sql);