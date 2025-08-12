<?php 

include "config.php";
include "incl/pss.inc";
include "incl/head.inc";

if(!isset($topic)){redirect("admin.php?f=$f");}
else{$file="$data/$topic";file_allowed($topic);} 

$fs=open_file($file);
$fs=explode("\n",$fs);

if(isset($delete)){
$delete=(int)$delete;
if($delete<count($fs)&&$delete>0){

$fs[$delete]='';
$fs=implode("\n",$fs);
save_file($file,$fs,0);

$fs=open_file($log);
$fs=explode("\n",$fs);
for($i=0;$i<count($fs);$i++){
$row=explode(":|:",$fs[$i]);
if($row[0]==$topic){
$row[5]=(int)$row[5];
$row[5]=$row[5]-1;
$fs[$i]=implode(":|:",$row);break;
}}
$fs=implode("\n",$fs);
save_file($log,$fs,0);
}
redirect("adminsh.php?f=$f&topic=$topic");
}
?><title>ADMIN: <?php print $lang[29];?>...</title>
</head><body><?php include "incl/cust-top.inc";?>
<table width="100%" border="0" cellpadding="0" cellspacing="0"><tr><td class="q">
<table width="100%" border="0" cellspacing="1" cellpadding="<?php print $cellpadding;?>"><tr class="c"><td colspan="4">
<table width="100%" cellpadding="2" cellspacing="0"><tr><td nowrap="nowrap"><span class="c"><a href="admin.php" id="lnk" title="<?php print $lang[0];?>" style="color:#ffffff;text-decoration:none" onmouseover="start_impress();return true" onmouseout="stop_impress();return true" onclick="pp=Math.round(999999*Math.random());window.location='admin.php?f=<?php print $f;?>'+amp+'n='+pp;return false"><?php print $forum_name[$f];?></a></span></td><td align="right" nowrap="nowrap"><b style="color:white">ADMIN</b></td></tr></table></td></tr>
<tr class="z"><td class="f" width="20%" nowrap="nowrap">&nbsp;<?php print $lang[28];?>&nbsp;</td><td class="f" width="70%" nowrap="nowrap">&nbsp;<?php print $lang[17];?>&nbsp;</td><td class="f" width="5%" nowrap="nowrap">&nbsp;<?php print $lang[33];?>&nbsp;</td><td class="f" width="5%" nowrap="nowrap">&nbsp;<?php print $lang[44];?>&nbsp;</td></tr>
<?php 
for($i=0;$i<count($fs);$i++){
if(strlen($fs[$i])>9){
$row=explode(":|:",$fs[$i]);
$pic_number=explode(".gif",$row[2]);
$pic_number=substr($pic_number[0],-1);
if(!strstr($row[0],' ')){$row[0]=time_offset($row[0]);}

if(isset($row[5])&&strlen($row[5])>5){
$ban_ip="<a href=\"#\" title=\"$row[5]\" onclick=\"ban($f,'$row[5]');return false\">$lang[45]</a>";
$ban_range=explode(".",$row[5]);
$ban_range="<a href=\"#\" title=\"$ban_range[0].$ban_range[1].$ban_range[2].xxx\" onclick=\"ban($f,'$ban_range[0].$ban_range[1].$ban_range[2].');return false\">$lang[46]</a>";}
else{$ban_ip="_";$ban_range="_";}

if(!isset($row[4])||$row[4]==''||$row[4]=='http://'){$img='';}
else{$img="<img src=\"$row[4]\" align=\"right\" border=\"1\" width=\"50\" height=\"50\" onclick=\"show_image(this,'$row[4]')\" vspace=\"4\" hspace=\"4\" onmouseover=\"this.style.cursor='hand'\" onmouseout=\"this.style.cursor='default'\" alt=\"$lang[42]\" title=\"$lang[42]\" />";}

if($i==0){$delete="<span style=\"color:#ffffff;text-decoration:underline\">$lang[34]</span>";}
else{$delete="<a href=\"adminsh.php?f=$f&amp;topic=$topic&amp;delete=$i\" onclick=\"return no_undo('$lang[35]')\">$lang[34]</a>";}

print "\n<tr class=\"$row_bg\"><td><b>$row[2]</b><br clear=\"all\" /><img src=\"pics/b$pic_number.gif\" $size_img[3] alt=\"\" hspace=\"1\" align=\"left\" /><b>$row[1]</b></td>";
print "\n<td class=\"s\">$img $row[3] <br clear=\"all\" /><div align=\"right\"><i>$row[0]</i></div></td><td align=\"center\" class=\"s\" nowrap=\"nowrap\">$delete</td><td align=\"center\" class=\"s\" nowrap=\"nowrap\">$ban_ip $ban_range</td></tr>";
switch_row_bg();}}
print "\n<tr class=\"$row_bg\"><td class=\"r\" colspan=\"3\">&nbsp;</td><td align=\"center\" class=\"r\"><a href=\"#\" onclick=\"self.scrollTo(0,0);return false\">$lang[30]</a></td></tr>";

?></table></td></tr></table>
<div class="f"><?php $end_time=time_to_run();$total_time=substr(($end_time-$start_time),0,5);print "$total_time $lang[31]";?></div>
<?php include "incl/cust-bot.inc";?>
</body></html>