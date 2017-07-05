<?php
namespace Api\Controller;
use Think\Controller;
class ApiController extends Controller {
  //天气查询测试接口
  public function weather(){
    $city = I('get.city');
    //1.url地址
    $url = 'http://api.map.baidu.com/telematics/v2/weather?location='.$city.'&ak=B8aced94da0b345579f481a1294c9094';
    //2.请求方式
    //3.发送请求
    //function requeset($url,$https=true,$method='get',$data=null){
    $content = requeset($url,false);
    //4.处理返回值
    //xml转对象
    $content = simplexml_load_string($content);
    //遍历数据
    foreach ($content->results->result as $key => $value) {
      $todayData = $value;
      echo '实时温度:'.$todayData->date.'<br />';
      echo '天气情况:'.$todayData->weather.'<br />';
      echo '风力:'.$todayData->wind.'<br />';
      echo '温度区间:'.$todayData->temperature.'<br />';
      echo '白天天气:<img src="'.$todayData->dayPictureUrl.'"/><br />';
      echo '夜间天气:<img src="'.$todayData->nightPictureUrl.'"/><br />';
      echo '<hr />';
    }
  }
  //电话号码归属地查询测试接口
  public function getAreaByPhone(){
    $phone = I('get.phone');
    //1.url
    $url = 'http://cx.shouji.360.cn/phonearea.php?number='.$phone;
    //2.请求方式
    //3.发送请求
    $content = requeset($url,false);
    //4.处理返回值
    //json转obj
    $content = json_decode($content);
    // dump($content);
    echo '查询号码:'.$phone.'<br />';
    echo '省份:'.$content->data->province.'<br />';
    echo '城市:'.$content->data->city.'<br />';
    echo '运营商:'.$content->data->sp.'<br />';
  }
  //快递查询测试接口
  /*
   * 快递查询
   * Time:2017年5月7日10:25:37
   * author: heart
   */
  public function express(){
    $type = 'jd';
    $postid = 'VC34438354672';
    //1.url
    $url = 'https://www.kuaidi100.com/query?type='.$type.'&postid='.$postid;
    //2.请求方式
    //3.发送请求
    // function requeset($url,$https=true,$method='get',$data=null){
    $content = requeset($url);
    //4.处理返回值
    //json转对象
    $content = json_decode($content);
    // dump($content);
    //遍历输出每一条物流信息
    foreach ($content->data as $key => $value) {
      if($key == 0){
        echo '<span style="color:red">'.$value->time.'###'.$value->context.'</span><br />';
      }else{
        echo $value->time.'###'.$value->context.'<br />';
      }
    }
  }
  //测试邮件发送接口
  public function testSend(){
    // function sendMail($subject,$msghtml,$sendAddress){
    // $rs = sendMail('shanghaiphp8发送的php的邮件','我是php发送的邮件，查看一下你是否能够收到！','woai281@163.com');
    $rs = sendMail('shanghaiphp8发送的php的邮件','我是php发送的邮件，查看一下你是否能够收到！','732677288@qq.com');
    if($rs === true){
      echo '发送邮件成功!';
    }else{
      echo '发送失败！'.'<br />';
      echo '错误原因为:'.$rs;
    }
  }
  //获取电话号码归属地信息通过远程API
  public function getAreaByPhoneToApi(){
    G('begin');
    //1.接收参数
    $phone = I('get.phone');
    //2.校验参数
    //根据业务需求，进行校验
    if(empty($phone)){
      $data = array(
          'errcode' => 1,   //错误返回码
          'time' => time(),  //返回时间戳，代表数据的返回时间
        );
    }else{
      //3.校验通过，通过参数查询数据
      $areaNum = substr($phone, 0, 7);
      //通过远程API调用获取
      $url = 'http://cx.shouji.360.cn/phonearea.php?number='.$areaNum;
      $content = requeset($url,false);
      $content = json_decode($content);
      //组合返回数据
      $data = array(
          'errcode' => 0,
          'time' => time(),
          'province' => $content->data->province,
          'city' => $content->data->city,
          'sp' => $content->data->sp,
        );
    }
    //4.约定格式返回数据
    echo json_encode($data);
    echo '<br />';
    G('end');
    echo G('begin','end').'s';
    echo '<br />';
    echo G('begin','end','m').'kb';
  }
  //获取号码归属地信息通过Mysql
  public function getAreaByPhoneToMysql(){
    G('begin');
    //1.接收参数
    $phone = I('get.phone');
    //2.校验参数
    if(empty($phone)){
      $data = array(
          'errcode' => 1,
          'time' => time()
        );
    }else{
      //3.校验通过，通过参数查询数据
      $areaNum = substr($phone, 0 ,7);
      //通过mysql查询获取数据
      $content = M('mobile')->where("mobile = $areaNum")->find();
      //组合数据
      $data = array(
          'errcode' => 0,
          'time' => time(),
          'province' => $content['province'],
          'city' => $content['city'],
          'sp' => $content['sp'],
        );
    }
    //4.约定格式返回数据
    echo json_encode($data);
    echo '<br />';
    G('end');
    echo G('begin','end').'s';
    echo '<br />';
    echo G('begin','end','m').'kb';
  }
  //从mysql存储数据到redis
  public function doMysqlToRedis(){
    //设置脚本临时使用大小
    ini_set("memory_limit","500M");
    //取出mysql的数据
    $data = M('mobile')->select();
    // echo count($data);
    //连接redis
    $redis = linkRedis();
    //$redis->hMset('user:1', array('name' => 'Joe', 'salary' => 2000));
    foreach ($data as $key => $value) {
      $redis->hMset($value['mobile'], $value);
    }
  }
  //获取号码归属地信息通过Redis
  public function getAreaByPhoneToRedis(){
    G('begin');
    //1.接收参数
    $phone = I('get.phone');
    //2.校验参数
    if(empty($phone)){
      $data = array(
          'errcode' => 1,
          'time' => time(),
        );
    }else{
      //3.校验通过，根据参数查询数据
      $areaNum = substr($phone,0,7);
      //通过redis查询数据
      $redis = linkRedis();
      $content = $redis->hGetAll($areaNum);
      //组合数据
      $data = array(
          'errcode' => 0,
          'time' => time(),
          'province' => $content['province'],
          'city' => $content['city'],
          'sp' => $content['sp'],
        );
    }
    //4.约定格式返回数据
    echo json_encode($data);
    echo '<br />';
    G('end');
    echo G('begin','end').'s';
    echo '<br />';
    echo G('begin','end','m').'kb';
  }
  //获取IP归属地信息通过本地数据文件
  public function getAreaByIpToFile(){
    //1.接收参数
    $ip = I('get.ip');
    //2.校验参数
    if(empty($ip)){
      $data = array(
          'errcode' => 1,
          'time' => time(),
        );
    }else{
      //3.校验通过，根据参数查询数据
      $Ip = new \Org\Net\IpLocation('qqwry.dat'); // 实例化类 参数表示IP地址库文件
      $area = $Ip->getlocation($ip); // 获取某个IP地址所在的位置
      // dump($area);
      // foreach ($area as $key => &$value) {
        //字符串格式转码
        // $value = iconv('gbk', 'utf-8', $value);
      // }
      // 回调函数  匿名函数的用法  闭包函数 closer
      $area = array_map(function($value){ return iconv('gbk','utf-8',$value);},$area);
      $data = array(
          'errcode' => 0,
          'time' => time(),
          'country' => $area['country'],
          'area' => $area['area']
        );
    }
    //4.预定格式返回数据
    echo json_encode($data);
  }
  //获取IP归属地信息通过远程API
  public function getAreaByIpToApi(){
    //1.接收参数
    $ip = I('get.ip');
    //2.校验参数
    if(empty($ip)){
      $data = array(
          'errcode' => 1,
          'time' => time(),
        );
    }else{
      //3.校验通过，通过参数查询数据
      //查询数据通过远程API
      $url = 'http://int.dpool.sina.com.cn/iplookup/iplookup.php?format=json&ip='.$ip;
      $content = json_decode(requeset($url,false));
      //组合数据
      $data = array(
          'errcode' => 0,
          'time' => time(),
          'country' => $content->country,
          'province' => $content->province,
          'city' => $content->city,
        );
    }
    //4.约定格式返回数据
    //xml模板
    $xml = '<?xml version="1.0" encoding="utf-8" ?><data><errcode>%s</errcode><time>%s</time><country>%s</country><province>%s</province><city>%s</city></data>';
    header('Content-Type:text/xml');
    echo sprintf($xml,$data['errcode'],$data['time'],$data['country'],$data['province'],$data['city']);
    // echo json_encode($data);
  }
  //把mysql的数据存储mongodb
  public function doMysqlToMongo(){
    //临时设置脚本可用大小
    ini_set('memory_limit','500M');
    //临时设置脚本超时时间 0不超时
    ini_set('max_execution_time',0);
    // set_time_limit(0)
    $data = M('mobile')->select();
    $mongo = linkMongo();
    foreach ($data as $key => $value) {
      //循环写入每一条数据
      $mongo->sh8->mobile->insert($value);
    }
  }
  //获取电话号码归属地信息通过Mongo
  public function getAreaByPhoneToMongo(){
    G('begin');
    //1.接收参数
    $phone = I('get.phone');
    //2.校验参数
    if(empty($phone)){
      $data = array(
          'errcode' => 1,
          'time' => time()
        );
    }else{
      //3.校验通过，查询数据
      $areaNum = substr($phone,0,7);
      //通过mongo查询数据
      $mongo = linkMongo();
      $content = $mongo->sh8->mobile->findOne(array('mobile' => $areaNum));
      // dump($content);
      $data = array(
          'errcode' => 0,
          'time' => time(),
          'province' => $content['province'],
          'city' => $content['city'],
          'sp' => $content['sp'],
        );
    }
    //4.约定格式返回数据
    echo json_encode($data);
    echo '<br />';
    G('end');
    echo G('begin','end').'s';
    echo '<br />';
    echo G('begin','end','m').'kb';
  }
}