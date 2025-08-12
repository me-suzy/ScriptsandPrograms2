<?php 
include "config.php";
include "incl/head.inc";?>
<title><?php print $lang[38];?>...</title>
</head><body onload="self.focus()"><table align="center" width="95%"><tr><td>
<table width="100%" border="0" cellpadding="0" cellspacing="0"><tr><td>
<table width="100%" border="0" cellpadding="0" cellspacing="0"><tr><td class="q">
<table width="100%" border="0" cellspacing="1" cellpadding="5"><tr class="c"><td colspan="3">
<table width="100%" cellpadding="2" cellspacing="0"><tr><td nowrap="nowrap"><span class="c"><a href="main.php" id="lnk" style="color:#ffffff;text-decoration:none" onmouseover="start_impress();return true" onmouseout="stop_impress();return true" onclick="return false"><?php print $forum_name[$f];?></a></span></td></tr></table></td></tr>
<tr class="z"><td class="f" width="20%" nowrap="nowrap">&nbsp;<?php print $lang[28];?>&nbsp;</td><td class="f" width="70%" nowrap="nowrap">&nbsp;<?php print $lang[17];?>&nbsp;</td><td class="f" width="10%" nowrap="nowrap">&nbsp;<?php print $lang[15];?>&nbsp;</td></tr>
<?php 

if(isset($name)&&isset($text)&&isset($title)){
if(!isset($image)||$image==''||$image=='http://'){$image='';}

include "incl/format.inc";
$entry="$current_time:|:$title:|:$name:|:$text:|:$image";

$row=explode(":|:",$entry);
$pic_number=explode(".gif",$row[2]);
$pic_number=substr($pic_number[0], -1);
if(!strstr($row[0],' ')){$row[0]=time_offset($row[0]);}

$row[3]=eregi_replace("([A-z0-9._-]+)@([A-z0-9._-]+)","<script type=\"text/javascript\">show_mail('\\1','\\2')</script>",$row[3]);

if(!isset($row[4])||$row[4]==''||$row[4]=='http://'){$img='';}
else{$img="<img src=\"$row[4]\" align=\"right\" border=\"1\" width=\"50\" height=\"50\" onclick=\"show_image(this,'$row[4]')\" vspace=\"4\" hspace=\"4\" onmouseover=\"this.style.cursor='hand'\" onmouseout=\"this.style.cursor='default'\" alt=\"$lang[42]\" title=\"$lang[42]\" />";}

print "\n<tr class=\"$row_bg\"><td><b>$row[2]</b><br clear=\"all\" /><img src=\"pics/b$pic_number.gif\" $size_img[3] alt=\"\" hspace=\"1\" align=\"left\" /><b>$row[1]</b></td>";
print "\n<td class=\"s\">$img $row[3]</td><td class=\"s\" nowrap=\"nowrap\">$row[0]</td></tr>";
switch_row_bg();}
else{print "\n<tr class=\"$row_bg\"><td colspan=\"3\">&nbsp;</td></tr>";}?>
</table></td></tr></table></td></tr></table>
</td></tr></table></body></html>