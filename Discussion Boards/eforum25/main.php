<?php
include "config.php";
include "incl/head.inc";

$fs=open_file($log);
$fs=explode("\n",$fs);

if(!isset($go)){$go=0;}else{$go=(int)$go;}

$go_forward=$go+$topics_per_page;
if($go>$topics_per_page){$go_back=$go-$topics_per_page;}
else{$go_back=0;}

?><title><?php $temp_var=$go+1;print "$lang[21] ($temp_var-$go_forward)";?></title>
</head><body><?php include "incl/cust-top.inc";?>
<table width="100%" border="0" cellpadding="0" cellspacing="0"><tr><td class="q">
<table width="100%" border="0" cellspacing="1" cellpadding="<?php print $cellpadding;?>">
<tr class="c"><td colspan="5"><table width="100%" cellpadding="2" cellspacing="0"><tr><td nowrap="nowrap"><span class="c"><a href="main.php" id="lnk" title="<?php print $lang[0];?>" style="color:#ffffff;text-decoration:none" onmouseover="start_impress();return true" onmouseout="stop_impress();return true" onclick="refresh(<?php print $f;?>);return false"><?php print $forum_name[$f];?></a></span></td><td align="right" nowrap="nowrap"><b><a href="new.php?f=<?php print $f;?>" style="color:#ffffff"><?php print $lang[18];?></a></b></td></tr></table></td></tr>
<tr class="z"><td class="f" width="5%"></td><td class="f" width="70%" nowrap="nowrap">&nbsp;<?php print $lang[21];?>&nbsp;</td><td class="f" width="10%" nowrap="nowrap">&nbsp;<?php print $lang[23];?>&nbsp;</td><td class="f" width="10%" nowrap="nowrap">&nbsp;<?php print $lang[24];?>&nbsp;</td><td class="f" width="5%" nowrap="nowrap">&nbsp;<?php print $lang[25];?>&nbsp;</td></tr>
<?php
for($i=$go;$i<$go_forward;$i++){
if(isset($fs[$i])&&strlen($fs[$i])>5){
$row=explode(":|:",$fs[$i]);
$pic_number=explode(".gif",$row[4]);
$pic_number=substr($pic_number[0], -1);
if(!strstr($row[1],' ')){$row[1]=time_offset($row[1]);}

if($row[3]!="&nbsp;"){$row[3]="<div class=\"s\">$row[3]</div>";}
print "<tr class=\"$row_bg\"><td><img src=\"pics/t$pic_number.gif\" $size_img[2] alt=\"\" hspace=\"2\" vspace=\"2\" /></td>";
print "<td><a href=\"show.php?f=$f&amp;topic=$row[0]&amp;u=$row[5]\" class=\"v\"><b>$row[2]</b></a>$row[3]</td>";
print "\n<td nowrap=\"nowrap\"><b>$row[4]</b></td>";
print "<td class=\"s\" nowrap=\"nowrap\">$row[1]</td>";
print "<td class=\"s\">$row[5]</td></tr>\n";
switch_row_bg();}}

if(isset($memname)&&isset($mempass)){
$login_logout=$lang[68];$is_member='a';}
else{$login_logout=$lang[67];$is_member='b';}

print "<tr class=\"$row_bg\"><td colspan=\"2\" class=\"r\">&nbsp;<a href=\"#\" style=\"text-decoration:none\" onclick=\"yy=window.open('offset.php?f=$f','offs','width=220,height=400,resizable=1');yy.focus();return false\" title=\"$lang[72]\">$show_time</a></td>";
print "<td class=\"r\" align=\"center\" nowrap=\"nowrap\"><a href=\"#\" onclick=\"zz=window.open('join.php','join','width=310,height=300,resizable=1');zz.focus();return false\" title=\"$lang[60]\"><img src=\"pics/log.gif\" width=\"24\" border=\"0\" height=\"14\" vspace=\"2\" hspace=\"3\" alt=\"$lang[60]\" /></a> <a href=\"#\" onclick=\"zz=window.open('log.php?f=$f','log','width=310,height=300,resizable=1');zz.focus();return false\" title=\"$login_logout\"><img src=\"pics/ot$is_member.gif\" border=\"0\" width=\"22\" height=\"13\" vspace=\"2\" hspace=\"3\" alt=\"$login_logout\" /></a></td><td class=\"r\" align=\"center\"><table cellpadding=\"0\" cellspacing=\"0\" width=\"100%\"><tr><td align=\"center\">";
print "<a href=\"main.php?f=$f&amp;go=$go_back\" title=\"&lt;&lt;\"><img src=\"pics/la.png\" width=\"10\" height=\"9\" hspace=\"0\" vspace=\"0\" border=\"0\" alt=\"&lt;&lt;\" onmouseover=\"this.src='pics/lb.png'\" onmouseout=\"this.src='pics/la.png'\" /></a>";

for($i=0;$i<5;$i++){
if(is_integer($i/2)){$hspace=1;}else{$hspace=0;}
if($go!=($i*$topics_per_page)){set_navbar($i+1,0);print "<a href=\"main.php?f=$f&amp;go=$nav2temp\" title=\"$lang[21]: $nav3temp-$nav1temp\"><img src=\"pics/of.png\" width=\"10\" height=\"9\" hspace=\"$hspace\" vspace=\"0\" border=\"0\" alt=\"$lang[21]: $nav3temp-$nav1temp\" onmouseover=\"this.src='pics/ou.png'\" onmouseout=\"this.src='pics/of.png'\" /></a>";}else{print "<img src=\"pics/on.png\" width=\"10\" height=\"9\" hspace=\"$hspace\" vspace=\"0\" alt=\"\" />";}}

print "<a href=\"main.php?f=$f&amp;go=$go_forward\" title=\"&gt;&gt;\"><img src=\"pics/ra.png\" width=\"10\" height=\"9\" hspace=\"0\" vspace=\"0\" border=\"0\" alt=\"&gt;&gt;\" onmouseover=\"this.src='pics/rb.png'\" onmouseout=\"this.src='pics/ra.png'\" /></a>";

print "</td><td>&nbsp;<a href=\"search.php?f=$f\"><img src=\"pics/find.gif\" width=\"15\" height=\"15\" border=\"0\" alt=\"$lang[32]\" title=\"$lang[32]\" /></a></td></tr></table></td><td class=\"r\" align=\"center\"><a href=\"admin.php?f=$f\" title=\"ADMIN\" target=\"_blank\"><img src=\"pics/adm.gif\" width=\"14\" height=\"12\" border=\"0\" alt=\"ADMIN\" /></a></td></tr>";
?></table></td></tr></table>
<?php include "incl/clrs.inc";?><br clear="all" />
<?php include "incl/cust-bot.inc";?>
</body></html>