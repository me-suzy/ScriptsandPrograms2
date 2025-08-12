<?php 
include "config.php";
include "incl/pss.inc";
include "incl/head.inc";

if(isset($do_it)&&is_file("$data/g_show")){
copy("$data/g_show","$data/gshow");unlink("$data/g_show");
die("<title>...</title></head><body><b style=\"color:#ffffff\">DONE!</b></body></html>");}

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
$file="$data/g_show";
save_file($file,$fs,0);
?>
<title>Repairing "gshow" ...</title></head><body>
<table width="100%" border="0" cellpadding="0" cellspacing="0"><tr><td class="q">
<table width="100%" border="0" cellspacing="1" cellpadding="<?php print $cellpadding;?>">
<tr class="c"><td colspan="5"><table width="100%" cellpadding="2" cellspacing="0"><tr><td nowrap="nowrap"><span class="c"><a href="main.php" id="lnk" style="color:#ffffff;text-decoration:none" onmouseover="start_impress();return true" onmouseout="stop_impress();return true" onclick="return false"><?php print $forum_name[$f];?></a></span></td><td align="right" nowrap="nowrap"><b>&nbsp;</b></td></tr></table></td></tr>
<tr class="z"><td class="f" width="70%" nowrap="nowrap">&nbsp;<?php print $lang[21];?>&nbsp;</td><td class="f" width="10%" nowrap="nowrap">&nbsp;<?php print $lang[23];?>&nbsp;</td><td class="f" width="10%" nowrap="nowrap">&nbsp;<?php print $lang[24];?>&nbsp;</td><td class="f" width="5%" nowrap="nowrap">&nbsp;<?php print $lang[25];?>&nbsp;</td><td class="f" width="5%" nowrap="nowrap">&nbsp;<?php print $lang[26];?>&nbsp;</td></tr>
<?php
$fs=explode("\n",$fs);
for($i=0;$i<count($fs);$i++){
if(isset($fs[$i])&&$fs[$i]!=""&&$fs[$i]!="\r"){
$row=explode(":|:",$fs[$i]);

$pic_number=explode(".gif",$row[4]);
$pic_number=substr($pic_number[0], -1);
if(!strstr($row[1],' ')){$row[1]=time_offset($row[1]);}
if($row[3]!="&nbsp;"){$row[3]="<div class=\"s\">$row[3]</div>";}

print "<tr class=\"$row_bg\"><td><a href=\"show.php?f=$f&amp;topic=$row[0]\"><img src=\"pics/t$pic_number.gif\" $size_img[2] alt=\"\" border=\"0\" align=\"left\" vspace=\"0\" /><b>$row[2]</b></a>$row[3]</td>";
print "\n<td nowrap=\"nowrap\"><b>$row[4]</b></td>";

print "<td class=\"s\" nowrap=\"nowrap\">$row[1]</td>";
print "<td class=\"s\">$row[5]</td>";
print "<td class=\"s\">$row[6]</td></tr>\n";
switch_row_bg();
}}?></table></td></tr></table>
<br /><br /><div class="w"><b>Is this acceptable?</b><br />
<br />1. The order of the topics is not absolutely correct.
<br />2. Take a note that you'll have 10 topics/page as usual.
<br /><br /><a href="repair.php?f=<?php print $f;?>&amp;do_it=1" style="color:#ffffff"><b>Click here</b></a> to continue...</div><br /><br />
</body></html>