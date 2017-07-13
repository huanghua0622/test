<?php
/**
 * Created by PhpStorm.
 * User: 22750
 * Date: 2017/7/10
 * Time: 19:26
 */
$dsn = 'mysql:host = localhost;dbname = test; charset = utf-8';
$pdo = new PDO($dsn,'root','123456');
//$db = mysqli_connect('localhost','root','123456','test','3306');
//mysqli_query('set names utf8');
//在执行修改和删除的时候一定要添加where条件
$sql = "update student set name='张三丰',age='99'where id = 7";
$result = $pdo->exec($sql);
//pdo和pdostatement之间的关系 这个只有在执行select时用到query先处理再查询结果
//执行查询语句的时候
$sql =  "select * from student where id = 19";
$stmt  = $pdo->query($sql);
$row = $stmt->fetch(PDO::FETCH_ASSOC);

$sql = "insert into student (name,sex,age) values(?,?,?)";//使用预处理  这里买你要使用到占位符
$stmt = $pdo->prepare($sql);
$stmt->bindValue(1,'天上');//1 表示的是第一个占位符   后面的是给第一个占位符赋值
$stmt->bindValue(2,'女');
$stmt->bindValue(3,'88');//这是第二个占位符
$stmt->execute();//用占位符 并给她赋值后 执行文件 执行的时候不传值


//pdo中的事务处理
//开启事务
$pdo->beginTransaction();
$sql1 = 'update money set qian = 100 where id = 1';
$sql2 = 'update money set qian =100 where id = 2';
$res1 = $pdo->exec($sql1);
$res2 = $pdo->exec($sql2);
if($res1 && $res2){
    $pdo->commit();
}else{
    $pdo->rollBack();
}

$dsn = 'mysql:host=localhost;dbname=test;charset=utf8';
$pdo = new PDO($dan,'root','123456',array(PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION));

//上面设置的pdo使用错误模式为异常模式
//使用错误模式的时候使用try  catch来处理sql语句
try{
    $sql = "insert into student(name,sex,age) values('马大帅','男',30)";
    $result = $pdo->exec($sql);
}catch(PDOException $e){
    echo '<pre>';
    print_r($e->errorInfo);
}

