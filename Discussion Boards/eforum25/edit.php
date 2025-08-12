<?php 
include "config.php";
include "incl/head.inc";
include "incl/ban.inc";

if(isset($members_edit)&&$members_edit==0){
die('<title>...</title><body onload="history.back(1)"></body></html>');}

$members_only=1;
$pass_reason='';

if(isset($topic)&&isset($line)){
$file="$data/$topic";
file_allowed($topic);

$line=(int)$line;

$fs=open_file($file);
$fs=explode("\n",$fs);

if($line>=count($fs)){$line=0;}
$row=$fs[$line];
$row=str_replace('<br />',"\r\n",$row);
$row=explode(":|:",$row);

if(isset($name)&&isset($title)&&isset($text)&&isset($line)){
if(!isset($image)||$image=='http://'){$image='';}
if(strstr($row[2],'onclick')){$name=strip_tags($row[2]);}else{$name='';}

include "incl/format.inc";

if($name==''||$title==''||$text==''){redirect("main.php?f=$f");}

$modified=time_offset($current_time-$user_time*3600);
$modified.=' GMT';
$text="$text<br /><br /><i class=\"s\">$lang[37]: $modified</i>";
if(!isset($row[5])){$row[5]='';}
$fs[$line]="$row[0]:|:$title:|:$name:|:$text:|:$image:|:$row[5]";
$fs=implode("\n", $fs);
save_file($file,$fs,0);

if($line==0){
$fs=open_file($log);
$fs=explode("\n",$fs);

for($i=0;$i<count($fs);$i++){
$log_row=explode(":|:",$fs[$i]);
if($topic==$log_row[0]){
$fs[$i]="$log_row[0]:|:$log_row[1]:|:$title:|:$desc:|:$name:|:$log_row[5]:|:$log_row[6]";}
}
$fs=implode("\n",$fs);
save_file($log,$fs,0);}

redirect("show.php?f=$f&topic=$topic");}

else{
$title=strip_tags($row[1]);
$name=strip_tags($row[2]);
$text=strip_tags($row[3]);
if(isset($row[4])){$image=strip_tags($row[4]);}

$temp_title=$lang[22];
$temp_link='show.php?f='.$f.'&amp;topic='.$topic;

if(isset($memname)){$name=urldecode($memname);}
if(isset($mempass)){$key=urldecode($mempass);}
include "incl/edit.inc";}}

else{redirect("main.php?f=$f");}
?>

