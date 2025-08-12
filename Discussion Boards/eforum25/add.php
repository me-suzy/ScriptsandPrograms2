<?php 
include "config.php";
include "incl/head.inc";
include "incl/ban.inc";

$pass_reason='';
$temp_title=$lang[19];
if(isset($memname)){$name=urldecode($memname);}
if(isset($mempass)){$key=urldecode($mempass);}

if(isset($name)&&isset($title)&&isset($text)&&isset($topic)){
if(!isset($image)||$image=='http://'){$image='';}

include "incl/flood.inc";
include "incl/format.inc";

$file="$data/$topic";
file_allowed($topic);

if($name==''||$title==''||$text==''){redirect("main.php?f=$f");}

$fs=open_file($log);
$fs=explode("\n",$fs);

for($i=0;$i<count($fs);$i++){
$row=explode(":|:",$fs[$i]);

if($i==0){$last=$row[1];
if(!strstr($last,' ')&&($current_time-$last)<$post_interval){

$temp_link='show.php?f='.$f.'&amp;topic='.$topic;
$text=str_replace('<br />',"\r\n",$text);
$text=strip_tags($text);
$name=strip_tags($name);
$pass_reason=$lang[75];
include "incl/edit.inc";
die();}}

if($row[0]==$topic){$row[5]=(int)$row[5];
$row[5]=$row[5]+1;
$row[1]=$current_time;
$row[6]=strip_tags($name);
$row=implode(":|:",$row);
$fs[$i]='';break;}}

$fs=implode("\n",$fs);
$fs="$row\n$fs";
save_file($log,$fs,0);

$fs=open_file($file);
$fs="$fs\n$current_time:|:$title:|:$name:|:$text:|:$image:|:$REMOTE_ADDR";
$temp_var=explode("\n",$fs);$temp_var=count($temp_var);
save_file($file,$fs,0);

if(isset($admin_mail)&&$admin_mail!='not_set_yet'){
$snm=strip_tags($name);
$txt=strip_tags($text);
$message="Subject: $title\nAuthor: $snm\n\n$txt";
mail($admin_mail,"Forum Notification",$message,"From: admin@$SERVER_NAME");}

redirect("show.php?f=$f&topic=$topic&u=$temp_var");}

elseif(isset($topic)){
file_allowed($topic);

$temp_link='show.php?f='.$f.'&amp;topic='.$topic;
include "incl/edit.inc";}

else{redirect("main.php?f=$f");}
?>

