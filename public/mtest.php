<?php
//房间号
$a="2271476204/31996485";
$ch = curl_init();
//接口1-能用
#curl_setopt($ch,CURLOPT_URL,"http://interface.yy.com/hls/get/0/".$a."?appid=0&excid=1200&type=m3u8&isHttps=0&callback=jsonp2");
//接口2-能用
curl_setopt($ch,CURLOPT_URL,"http://interface.yy.com/hls/new/get/".$a."/1200?source=wapyy&callback=jsonp3");
$headx = array();
$headx[] = 'Referer:  http://192.168.1.98/';
curl_setopt($ch,CURLOPT_HTTPHEADER,$headx);
curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
curl_close($ch);
var_dump($x);
?>