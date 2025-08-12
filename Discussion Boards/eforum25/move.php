<?php 
include "config.php";
include "incl/pss.inc";
include "incl/head.inc";

if(isset($topic)&&isset($f)&&isset($to)){
$f=(int)$f;$to=(int)$to;
if(is_file("$data/$topic")&&is_file($log)&&is_file("$forum_data[$to]/gshow")){

$fs=open_file($log);
$fs=explode("\n",$fs);

for($i=0;$i<count($fs);$i++){
$row=explode(":|:",$fs[$i]);
if(isset($row[0])&&$topic==$row[0]){

$entry=$fs[$i];$fs[$i]='';
$fs=implode("\n",$fs);
save_file($log,$fs,0);

$fs=open_file("$forum_data[$to]/gshow");
$fs=$entry."\n".$fs;
save_file("$forum_data[$to]/gshow",$fs,0);

copy("$data/$topic","$forum_data[$to]/$topic");
unlink("$data/$topic");
break;}}

die("<title>...</title></head><body onload=\"window.opener.location='admin.php?f=$f';setTimeout('self.close()',2000)\"><span class=\"w\">OK</span></body></html>");

}}?>
<title><?php print $lang[76];?></title></head><body>
<table align="center" width="220" border="0" cellpadding="0" cellspacing="0"><tr><td class="q">
<table width="100%" border="0" cellspacing="1" cellpadding="5"><tr class="c">
<td><b><?php print $lang[76];?> -&gt;</b></td></tr>
<?php
for($i=0;$i<count($forum_data);$i++){
if(isset($forum_data[$i])&&$i!=$f&&isset($topic)){
$forum_name[$i]=strip_tags($forum_name[$i]);
$forum_desc[$i]=strip_tags($forum_desc[$i]);$forum_desc[$i]=substr($forum_desc[$i],0,60).'...';
print "<tr class=\"$row_bg\"><td class=\"s\"><a href=\"move.php?f=$f&amp;topic=$topic&amp;to=$i\"><b>$forum_name[$i]</b></a><br />$forum_desc[$i]</td></tr>";
}switch_row_bg();}
?></table></td></tr></table></body></html>