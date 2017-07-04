<meta charset="utf-8">
<?php
//接收用户参数
$username = $_POST['username'];
$passwd = $_POST['passwd'];
//验证用户是否超过了密码错误次数
$redis = new Redis();
$redis->connect('127.0.0.1',6379);
//查询错误次数
$num = $redis->get($username);
// var_dump($num);
//如果没有错误次数或者没有操作次数
//验证数据库密码
if($num === false || $num < 3){
  //假设数据库密码已经查询到
  $truepassword = 123456;
  //判断密码是否正确
  if($passwd == $truepassword){
    //验证成功
    echo '登录成功！';
  }else{
    //密码错误
    echo '登录失败!';
    //计数器加1操作
    $redis->incr($username);
    //设置封冻有效期
    $redis->setTimeout($username, 3600);
  }

}else{
  echo '已经超过错误次数';
}