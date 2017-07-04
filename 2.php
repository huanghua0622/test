<?php
/**
 * Created by PhpStorm.
 * User: 22750
 * Date: 2017/7/4
 * Time: 19:57
 */

$mem = memcache_connect('127.0.0.1',12000);
$mem_ip = $ip;
$mem_page = $page;
$mem_referrer = $referrer;
$mem_info = $info;
if(!in_array($ip,$ip_arr)){
    $mem->set('mem_ip',$mem_ip&'mem_page',$mem_page&'mem_referrer',$mem_referrer&'mem_info',$mem_info);
    while(!$mem->add('lock','lock',0,1)){
        usleep(100);
    }
    $mem->delete('lock');
}

