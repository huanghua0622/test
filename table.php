<?php
/**
 * Created by PhpStorm.
 * User: 22750
 * Date: 2017/6/2
 * Time: 22:15
 */
//接收参数
$name = urldecode($_POST['username']);
$password = urldecode($_POST['password']);
//链接数据库  查找用户账号和密码信息
try{
    //执行数据库的连接 如果发生错误 pdo会自动扔出一个pdoexception相当于 你手动的使用throw new PDOException('
    $pdo = new PDO('mysql:host=localhost;dbname=hm4;charset=utf8','root','123456',array(
        PDO::ATTR_DEFAULT_FETCH_MODE =>PDO::FETCH_ASSOC,
    ));
}catch(PDOException $ex){
    //捕获错误  并输出错误的信息
    echo $ex->getMessage();
}
    //执行并判断返回结果
    $sql = "select * from user where username={$name}";
    $userRec = $pdo->query($sql)->fetch();
    var_dump($userRec);die();
    if(empty($userRec)){
        //账号不存在 使用json来存储数据
        exit(json_encode(['status'=>false,'info'=>'账号不存在']));
    }else if($userRec['password'] !== md5($password)){
        //密码不正确
        exit(json_encode(['status'=>false,'info'=>'密码不正确']));
    }else{

        exit(json_encode(['status'=>true,'info'=>$userRec]));
    }
