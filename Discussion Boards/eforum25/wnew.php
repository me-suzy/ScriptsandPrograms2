<?php 
include "config.php";
include "incl/wml.inc";
include "incl/ban.inc";

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

$topic_id=date('YmdHis');
$entry="$current_time:|:$title:|:<img src=\"pics/w8.gif\" $size_img[1] alt=\"\" border=\"0\" hspace=\"2\" />$name:|:$text:|::|:$REMOTE_ADDR";
$file="$data/$topic_id";
save_file($file,$entry,0);
$fs=open_file($log);

if(isset($admin_mail)&&$admin_mail!='not_set_yet'){
$name=strip_tags($name);
$text=strip_tags($text);
$message="Subject: $title\nAuthor: $name\n\n$text";
mail($admin_mail,"Forum Notification",$message,"From: admin@$SERVER_NAME");}

$entry="$topic_id:|:$current_time:|:$title:|:$desc:|:<img src=\"pics/w8.gif\" $size_img[1] alt=\"\" border=\"0\" hspace=\"2\" />$name:|:1:|:0\n$fs";
save_file($log,$entry,0);

die("<card id=\"ok\" title=\"ok\"><onevent type=\"onenterforward\"><go href=\"wnd.php?f=$f&amp;u=$random\" /></onevent><p><a href=\"wnd.php?f=$f&amp;u=$random\">Forward...</a></p></card></wml>");
}}

print '<card id="new" title="New Topic">';
include "incl/wedt.inc";
?></card></wml>