<?php 
include "config.php";
include "incl/wml.inc";
include "incl/ban.inc";

if(!isset($t)){$t=0;}is_topic($t);

if(isset($name)||isset($title)||isset($text)){
$name=abc_only($name,0);
$title=abc_only($title,0);
$text=abc_only($text,0);

$name=remove_bad_words($name);
$title=remove_bad_words($title);
$text=remove_bad_words($text);

if(strlen($name)<$flood[1]&&strlen($title)<$flood[2]&&strlen($text)<500&&$name!=''&&$title!=''&&$text!=''){
$desc=substr($text,0,90).'...';
include("incl/flood.inc");

$file="$data/$t";
$fs=open_file($file);
$entry="$fs\n$current_time:|:$title:|:<img src=\"pics/w8.gif\" $size_img[1] alt=\"\" border=\"0\" hspace=\"2\" />$name:|:$text:|::|:$REMOTE_ADDR";
save_file($file,$entry,0);

if(isset($admin_mail)&&$admin_mail!='not_set_yet'){
$name=strip_tags($name);
$text=strip_tags($text);
$message="Subject: $title\nAuthor: $name\n\n$text";
mail($admin_mail,"Forum Notification",$message,"From: admin@$SERVER_NAME");}

$fs=open_file($log);
$fs=explode("\n",$fs);

for($i=0;$i<count($fs);$i++){
$row=explode(":|:",$fs[$i]);
if($row[0]==$t){
$row[5]=(int)$row[5];
$row[5]=$row[5]+1;
$row[1]=$current_time;
$row=implode(":|:",$row);
$fs[$i]='';break;}}

$fs=implode("\n",$fs);
$entry="$row\n$fs";
save_file($log,$entry,0);
die("<card id=\"ok\" title=\"ok\"><onevent type=\"onenterforward\"><go href=\"wshow.php?f=$f&amp;t=$t&amp;u=$random\" /></onevent><p><a href=\"wshow.php?f=$f&amp;t=$t&amp;u=$random\">Forward...</a></p></card></wml>");
}}
print '<card id="add" title="Add Post">';
include "incl/wedt.inc";
?></card></wml>