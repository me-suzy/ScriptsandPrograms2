<?php 
include "config.php";
include "incl/pss.inc";
include "incl/head.inc";
$temp_var=0;

$file="$forum_data[0]/banned";
$task_done="<title>...</title></head><body onload=\"pp=Math.round(99999*Math.random());window.location='ban.php?f=$f'+amp+'n='+pp\"></body></html>";

if(!is_file($file)){save_file($file,'',0);}
save_file("$forum_data[0]/flood",'',0);

$fs=open_file($file);
$fs=explode("\n",$fs);

if(isset($ban)&&strlen($ban)>6){
for($i=0;$i<count($fs);$i++){
if($ban==$fs[$i]){$temp_var=1;}}
if($temp_var==0){
$fs=implode("\n",$fs);
$fs="$ban\n$fs";
save_file($file,$fs,0);}
die($task_done);}

elseif(isset($unban)&&strlen($unban)>6){
for($i=0;$i<count($fs);$i++){
if($unban==$fs[$i]){
$fs[$i]='';$temp_var=1;}}
if($temp_var!=0){
$fs=implode("\n",$fs);
save_file($file,$fs,0);}
die($task_done);}
?>
<title>...</title></head><body>
<table align="center" width="200" border="0" cellpadding="0" cellspacing="0"><tr><td class="q">
<table width="100%" border="0" cellspacing="1" cellpadding="5"><tr class="c"><td colspan="2"><b class="w"><?php print $lang[47];?></b></td></tr>
<tr class="z"><td class="f" nowrap="nowrap">&nbsp;<?php print $lang[48];?></td><td class="f" nowrap="nowrap">&nbsp;<?php print $lang[33];?></td></tr>
<?php 

for($i=0;$i<count($fs);$i++){
if(isset($fs[$i])&&strlen($fs[$i])>6){
$range=explode(".",$fs[$i]);
if(isset($range[3])&&$range[3]!=''){
$range='';}else{$range='xxx';}

print "<tr class=\"$row_bg\"><td width=\"50%\" class=\"s\">$fs[$i]$range</td><td width=\"50%\" class=\"s\"><a href=\"ban.php?f=$f&amp;unban=$fs[$i]\">$lang[49]</a></td></tr>";
switch_row_bg();}}?>
</table></td></tr></table></body></html>