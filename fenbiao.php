<?php
/**
 * Created by PhpStorm.
 * User: 22750
 * Date: 2017/6/30
 * Time: 20:13
 */
$conn = mysqli_connect('192.168.89.114','root','123456','test',3306);
mysqli_query($conn,'set names utf8');

$table_num = 2;
$table_pre = 'goods_';
//获取新的数据
$data = array('name'=>'大宝','price'=>'10','pubdate'=>'2017-06-30 16:12:00');
//生成id的sql
$incr_sql = "insert into goods_id_incr values (null)";

mysqli_query($conn,$incr_sql);
//获取到最新的id值
$incr_id = mysqli_insert_id($conn);

$data['id'] = $incr_id;

$table_last = $incr_id % $table_num;
$table_name = $table_pre . $table_last;
$insert_sql = "insert into {$table_name} values('{$data['id']}','{$data['name']}','{$data['price']}','{$data['pubdate']}')";
mysqli_query($conn,$insert_sql);
echo 'ok';