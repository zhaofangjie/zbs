<?php
$str='
[DEFAULT]
BASEURL=http://'.$_SERVER['HTTP_HOST'].'/index/room
[{000214A0-0000-0000-C000-000000000046}]
Prop3=19,2
[InternetShortcut]
IDList=
URL=http://'.$_SERVER['HTTP_HOST'].'/index/room
IconFile=http://'.$_SERVER['HTTP_HOST'].'/style/images/favio.ico
IconIndex=1
';
header("Content-type:application/octet-stream");
header("Content-Disposition:attachment;filename=财运金融直播室.url");
echo $str;
?>
