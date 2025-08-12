<?php
$fs=array();$i=0;

$handle=opendir($data);
while($entry=readdir($handle)){
if(is_file("$data/$entry")&&strstr("$data/$entry","20")){
$file="$data/$entry";
$topic=open_file($file);
$topic=explode("\n",$topic);
$posts=count($topic)-1;

$first_post=explode(":|:",$topic[0]);
$last_post=explode(":|:",$topic[$posts]);

$description=strip_tags($first_post[3]);
$description=substr($description,0,90).'...';
$posts=$posts+1;

$fs[$i]="$entry:|:$last_post[0]:|:$first_post[1]:|:$description:|:$first_post[2]:|:$posts:|:";
$i++;}}
closedir($handle);

rsort($fs);
$fs=implode("\n",$fs);
save_file($log,$fs,0);
?>