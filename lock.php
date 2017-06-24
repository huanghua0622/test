<?php 

$mem = memcache_connect('127.0.0.1',12000);

$ip_arr = $mem->get('qu_ip_arr');

$ip = '23.23.23.23';
if(!in_array($ip,$ip_arr)){
	$info = 'ok';
	//说明：时间必须设置 因为你的程序枷锁之后有可能就挂了 没有删除锁 所以一定要设置过期时间
	//时间设置成后面的程序大概在什么时候内跑完就这周这个时间。
	while(!$mem->add('lock','lock',0,1)){
		//sleep 这个是以秒为单位的
		usleep(200);//以毫秒为单位
	}
	//执行代码
	$mem->delete('lock');
}


 ?>