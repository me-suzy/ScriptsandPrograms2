<?php 
include "config.php";
include "incl/wml.inc";

$fs=open_file($log);
$fs=explode("\n",$fs);

if(!isset($go)){$go=0;}
else{$go=(int)$go;}
$go_forward=$go+5;
$temp_var=$go+1;
$go_back=$go-5;

print "<card id=\"topics\" title=\"Topics $temp_var-$go_forward\">\n<p><a href=\"wnew.php?f=$f&amp;u=$random\">New...</a> <a href=\"wap.php\">Forums</a></p>";

if($go>=5){print "<p><a href=\"wnd.php?f=$f&amp;go=$go_back&amp;u=$random\">&lt;&lt;</a> &nbsp;";}else{print "<p>";}
print "<a href=\"wnd.php?f=$f&amp;go=$go_forward&amp;u=$random\">&gt;&gt;</a></p>";

for($i=$go;$i<$go_forward;$i++){
if(isset($fs[$i])&&strlen($fs[$i])>5){
$row=abc_only($fs[$i],1);
if($row==0){$row='0:|::|::|:The post contains non-latin letters and cannot be shown:|::|::|:';}

$row=explode(":|:",$row);
$row[3]=substr($row[3],0,40);
$row[2]=substr($row[2],0,10);
$row[4]=substr($row[4],0,10);
if(!strstr($row[1],' ')){
$row[1]=time_offset($row[1]);}
print "<p><a href=\"wshow.php?f=$f&amp;t=$row[0]&amp;u=$random\">$row[2]</a><br /><small>$row[3]</small><br />$row[4] ($row[5]) $row[1]</p>\n";
}}

if($go>=5){print "<p><a href=\"wnd.php?f=$f&amp;go=$go_back&amp;u=$random\">&lt;&lt;</a> &nbsp;";}else{print "<p>";}
print "<a href=\"wnd.php?f=$f&amp;go=$go_forward&amp;u=$random\">&gt;&gt;</a></p>";
?></card></wml>