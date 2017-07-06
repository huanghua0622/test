<?php
/**
 * Created by PhpStorm.
 * User: 22750
 * Date: 2017/7/6
 * Time: 13:19
 */
require './Wechat.classs.php';
$wechat = new Wechat();
//echo $wechat->getAccessToken();
//echo $wechat->getAccessTokenByFile();
//echo $wechat->getAccessTokenByMem();
//echo $wechat->getAccessTokenByRedis();
// $wechat->getTicket(666);
//echo $wechat->getQRcode();
//echo $wechat->getQRcondeByFile(666);
echo $wechat->createMenu();