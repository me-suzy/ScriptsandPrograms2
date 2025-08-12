<?php 
include "config.php";
include "incl/head.inc";
if(!isset($topic)){redirect("main.php?f=$f");}
else{$file="$data/$topic";
file_allowed($topic);}

$fs=open_file($file);
$fs=explode("\n",$fs);
if(count($fs)>=$posts_max){$add_post='';}
else{$add_post=$lang[19];}

?><title><?php print $lang[29];?>...</title>
</head><body><?php include "incl/cust-top.inc";?>
<table width="100%" border="0" cellpadding="0" cellspacing="0"><tr><td class="q">
<table width="100%" border="0" cellspacing="1" cellpadding="<?php print $cellpadding;?>"><tr class="c"><td colspan="3">
<table width="100%" cellpadding="2" cellspacing="0"><tr><td nowrap="nowrap"><span class="c"><a href="main.php" id="lnk" title="<?php print $lang[0];?>" style="color:#ffffff;text-decoration:none" onmouseover="start_impress();return true" onmouseout="stop_impress();return true" onclick="refresh(<?php print $f;?>);return false"><?php print $forum_name[$f];?></a></span></td><td align="right" nowrap="nowrap"><b><a style="color:#ffffff" href="add.php?f=<?php print $f;?>&amp;topic=<?php print $topic; ?>"><?php print $add_post;?></a></b></td></tr></table></td></tr>
<tr class="z"><td class="f" width="20%" nowrap="nowrap">&nbsp;<?php print $lang[28];?>&nbsp;</td><td class="f" width="70%" nowrap="nowrap">&nbsp;<?php print $lang[17];?>&nbsp;</td><td class="f" width="10%" nowrap="nowrap">&nbsp;<?php print $lang[15];?>&nbsp;</td></tr>
<?php

for($i=0;$i<count($fs);$i++){
if(isset($fs[$i])&&strlen($fs[$i])>9){
$row=explode(":|:",$fs[$i]);

$pic_number=explode(".gif",$row[2]);
$pic_number=substr($pic_number[0], -1);
if(isset($memname)&&strstr($row[2],'onclick')&&strtolower(strip_tags($row[2]))==strtolower($memname)&&$members_edit==1){
$edit="<div align=\"right\"><a href=\"edit.php?f=$f&amp;topic=$topic&amp;line=$i\">$lang[22]</a></div>";
}else{$edit='';}

if(!strstr($row[0],' ')){$row[0]=time_offset($row[0]);}
if(!isset($row[4])||$row[4]==''||$row[4]=='http://'){$img='';}else{$img="<img src=\"$row[4]\" align=\"right\" border=\"1\" width=\"50\" height=\"50\" onclick=\"show_image(this,'$row[4]')\" vspace=\"4\" hspace=\"4\" onmouseover=\"this.style.cursor='hand'\" onmouseout=\"this.style.cursor='default'\" alt=\"$lang[42]\" title=\"$lang[42]\" />";}
if(strstr($row[3],'@')){$row[3]=eregi_replace("([A-z0-9._-]+)@([A-z0-9._-]+)","<script type=\"text/javascript\">show_mail('\\1','\\2')</script>",$row[3]);}

print "\n<tr class=\"$row_bg\"><td><b>$row[2]</b><br clear=\"all\" /><img src=\"pics/b$pic_number.gif\" $size_img[3] alt=\"\" hspace=\"1\" align=\"left\" /><b>$row[1]</b></td>";
print "\n<td class=\"s\">$img $row[3] $edit</td><td class=\"s\" nowrap=\"nowrap\">$row[0]</td></tr>";
switch_row_bg();}}
print "\n<tr class=\"$row_bg\"><td class=\"r\" colspan=\"2\">&nbsp;<a href=\"add.php?f=$f&amp;topic=$topic\">$add_post</a></td><td align=\"center\" class=\"r\"><a href=\"#\" onclick=\"self.scrollTo(0,0);return false\">$lang[30]</a></td></tr>";
?>
</table></td></tr></table>
<?php $color_changing=0;include "incl/clrs.inc";?>
<br clear="all" /><?php include "incl/cust-bot.inc";?>
</body></html>
