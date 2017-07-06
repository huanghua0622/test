<?php
/**
 * Created by PhpStorm.
 * User: 22750
 * Date: 2017/7/6
 * Time: 11:56
 */
//定义一个wechat类
//存储接口的调用方法
require './config.php';
class Wechat{

    private $appid;
    private $appsecret;
    //构造方法
    public function __construct($appid=APPID,$appsecret=APPSECRET)
    {
         $this->appid = $appid;
         $this->appsecret = $appsecret;
    }
    public function request($url,$https=true,$method = 'get',$data=null)
    {
        $ch = curl_init($url);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
        if($https === true){
            curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,false);
            curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,false);
        }
        if($method === 'post'){
            curl_setopt($ch,CURLOPT_POST,true);
            curl_setopt($ch,CURLOPT_POSTFIELDS,$data);
        }
        $content = curl_exec($ch);
        curl_close($ch);
        return $content;
    }
    public function getAccessToken()
    {
        //url
        $url = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid='.$this->appid.'&secret='.$this->appsecret;
        //请求方式
        //发送请求
        $content = $this->request($url);
        //处理返回数据
        $content = json_decode($content);
        echo $content->access_token;
    }
    public function getAccessTokenByFile()
    {
        $filename = './accesstoken';
        if(!file_exists($filename) || (time() - filemtime($filename)) >7200 ){
            //url
            $url = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid='.$this->appid.'&secret='.$this->appsecret;
            //请求方式判断
            //发送请求
            $content = $this->request($url);
           $content = json_decode($content);
           $access_token = $content->access_token;
           file_put_contents($filename,$access_token);
        }else{
            $access_token = file_get_contents($filename);
        }
        return $access_token;
    }
    public function getAccessTokenByMem()
    {
        $mem = new Memcache();
        $mem->connect('127.0.0.1',11211);
        $access_token = $mem->get('accesstoken');
        if($access_token === false){
            $url = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid='.$this->appid.'&secret='.$this->appsecret;
            //发送请求
            $content = $this->request($url);
            $content = json_decode($content);
            $access_token = $content->access_token;
            //保存在缓存中 并且设置过期的时间
            $mem->set('accesstoken',$access_token,0,8);
        }
        echo $access_token;
    }
    public function getAccessTokenByRedis()
    {
        $redis = new Redis();
        $redis->connect('127.0.0.1',6379);
        $access_token = $redis->get('accesstoken');
        if($access_token === false){
            $url = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid='.$this->appid.'&secret='.$this->appsecret;
            //发送请求
            //返回的是json数据  先转化格式再进行取值
            $content = $this->request($url);
            $content = json_decode($content);
            $access_token = $content->access_token;
            $redis->set('accesstoken',$access_token);
            $redis->setTimeout('accesstoken',8);
        }
        return $access_token;
    }
    //tmp是来判断是永久的还是暂时的
    public function getTicket($scene_id,$expire_second=604800,$tmp=true)
    {
        $access_token = $this->getAccessTokenByFile();
        $url='https://api.weixin.qq.com/cgi-bin/qrcode/create?access_token='.$access_token;
        if($tmp === true){
            $data = '{"expire_seconds": '.$expire_second.', "action_name": "QR_SCENE", "action_info": {"scene": {"scene_id": '.$scene_id.'}}}';
        }else{
            $data = '{"action_name": "QR_LIMIT_SCENE", "action_info": {"scene": {"scene_id": '.$scene_id.'}}}';
        }
        //发送请求
        $content = $this->request($url,true,'post',$data);
        $content = json_decode($content);
//        echo $content;
        return $content->ticket;
        //这个只需要获取对应的ticket   下面再写方法  针对ticket码来进行操作 获取对于的二维码
    }
    public function getQRcode()
    {
        $ticket = 'gQEi8DwAAAAAAAAAAS5odHRwOi8vd2VpeGluLnFxLmNvbS9xLzAybDNKVFpyZl9kRG0xbHBJRDFwMTQAAgTZMV5ZAwSAOgkA';
//        echo $ticket;die;
        $url = 'https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket='.$ticket;
//        echo $url;die;
        //发送请求
        $content = $this->request($url);
//        header('Content-Type:image/jpg');
//        echo $content;
        return file_put_contents('qrcode.jpg',$content);
    }
    //以上是分两步来获取二维码的
    //一步来获取二维码
    public function getQRcondeByFile($scene_id)
    {
       $ticket =  $this->getTicket($scene_id);
       $rc = $this->getQRcode($ticket);
       if($rc){
           echo "生成二维码成功";
       }else{
            echo "生成二维码失败";
       }
    }
    public function createMenu()
    {
        $url = 'https://api.weixin.qq.com/cgi-bin/menu/create?access_token='.$this->getAccessTokenByFile();
        $data = ' {
                     "button":[
                     {	
                          "type":"click",
                          "name":"今日歌曲",
                          "key":"V1001_TODAY_MUSIC"
                      },
                      {
                           "name":"菜单",
                           "sub_button":[
                           {	
                               "type":"view",
                               "name":"搜索",
                               "url":"http://www.soso.com/"
                            },
                            {
                                 "type":"miniprogram",
                                 "name":"wxa",
                                 "url":"http://mp.weixin.qq.com",
                                 "appid":"wx286b93c14bbf93aa",
                                 "pagepath":"pages/lunar/index"
                             },
                            {
                               "type":"click",
                               "name":"赞一下我们",
                               "key":"V1001_GOOD"
                            }]
                       }]
                 }';
        $content = $this->request($url,true,'post',$data);
        echo $content;die;
        $content = json_decode($content);
        if($content->errmsg == 'ok'){
            echo "创建菜单成功";
        }else{
            echo "创建菜单失败";
            echo "错误码为".$content->errcode;
            echo "错误信息".$content->errmsg;
        }
    }


}