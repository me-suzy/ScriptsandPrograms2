<?php 
include "config.php";
include "incl/head.inc";
include "incl/ban.inc";

$pass_reason='';
$temp_title=$lang[18];
$temp_link='main.php?f='.$f;
if(isset($memname)){$name=urldecode($memname);}
if(isset($mempass)){$key=urldecode($mempass);}

if(isset($name)&&isset($title)&&isset($text)){

if(!isset($image)||$image=='http://'){$image='';}

include "incl/flood.inc";
include "incl/format.inc";

if($name==''||$title==''||$text==''){redirect("new.php?f=$f");}
$fs=open_file($log);
$fs=explode("\n",$fs);

if(isset($fs[0])&&strlen($fs[0])>9){
$row=explode(":|:",$fs[0]);
$last=$row[1];
if(!strstr($last,' ')&&($current_time-$last)<$post_interval){

$text=str_replace('<br />',"\r\n",$text);
$text=strip_tags($text);
$name=strip_tags($name);
$pass_reason=$lang[75];

include "incl/edit.inc";
die();}}

$fs=implode("\n",$fs);

$topic_id=date('YmdHis');

$fs="$topic_id:|:$current_time:|:$title:|:$desc:|:$name:|:1:|:\n$fs";
save_file($log,$fs,0);

$entry="$current_time:|:$title:|:$name:|:$text:|:$image:|:$REMOTE_ADDR";
$file="$data/$topic_id";
save_file($file,$entry,0);

if(isset($admin_mail)&&$admin_mail!='not_set_yet'){
$name=strip_tags($name);
$text=strip_tags($text);
$message="Subject: $title\nAuthor: $name\n\n$text";
mail($admin_mail,"Forum Notification",$message,"From: admin@$SERVER_NAME");}

redirect("main.php?f=$f");}

else{
include "incl/edit.inc";
}
?>