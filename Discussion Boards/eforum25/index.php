<?php 
include "config.php";
include "incl/head.inc";
if(count($forum_name)<2||count($forum_data)<2||count($forum_desc)<2){redirect('main.php');}
?><title><?php print $lang[2];?></title>
</head><body><?php include "incl/cust-top.inc";?>
<table width="100%" border="0" cellpadding="0" cellspacing="0"><tr><td class="q">
<table width="100%" border="0" cellspacing="1" cellpadding="<?php print $cellpadding;?>">
<tr class="c"><td colspan="5"><table width="100%" cellpadding="2" cellspacing="0"><tr><td nowrap="nowrap"><span class="c"><a href="#" id="lnk" style="color:#ffffff;text-decoration:none" onmouseover="start_impress();return true" onmouseout="stop_impress();return true" onclick="window.location='index.php';return false"><?php print $lang[2];?></a></span></td><td align="right" nowrap="nowrap">&nbsp;</td></tr></table></td></tr>
<tr class="z"><td class="f" width="5%"></td><td class="f" width="65%" nowrap="nowrap">&nbsp;</td><td class="f" width="20%" nowrap="nowrap">&nbsp;<?php print $lang[24];?>&nbsp;</td><td class="f" width="5%" nowrap="nowrap">&nbsp;<?php print $lang[21];?>&nbsp;</td><td class="f" width="5%" nowrap="nowrap">&nbsp;<?php print $lang[25];?>&nbsp;</td></tr>
<?php
$total_posts=0;$total_topics=0;

for($i=0;$i<count($forum_data);$i++){
$pic_number=mt_rand(1,7);
$log="$forum_data[$i]/gshow";

if(is_dir($forum_data[$i])){
$posts=0;$topics=0;$last=' ';

if(is_file($log)){

$fs=open_file($log);
$fs=explode("\n",$fs);

for($j=0;$j<count($fs);$j++){
if(isset($fs[$j])&&strlen($fs[$j])>9){
$topics+=1;
$row=explode(":|:",$fs[$j]);
if($j==0){$last=$row[1];}
settype($row[5],"integer");
settype($row[6],"integer");
$posts+=$row[5];
}}}

$total_posts=$total_posts+$posts;
$total_topics=$total_topics+$topics;

if(!strstr($last,' ')){$show_last=time_offset($last);}else{$show_last=$last;}
print "<tr class=\"$row_bg\">";
$forum_desc[$i]="<div class=\"s\">$forum_desc[$i]</div>";
print "<td><img src=\"pics/t$pic_number.gif\" $size_img[2] alt=\"\" hspace=\"2\" vspace=\"2\" /></td>";
print "<td><a href=\"main.php?f=$i\"><b>$forum_name[$i]</b></a>$forum_desc[$i]</td>";
print "<td class=\"s\" nowrap=\"nowrap\">$show_last</td><td class=\"s\">$topics</td><td class=\"s\">$posts</td>";
print "</tr>";switch_row_bg();
}}

if(isset($memname)&&isset($mempass)){
$login_logout=$lang[68];$is_member='a';}
else{$login_logout=$lang[67];$is_member='b';}

print "<tr class=\"$row_bg\"><td colspan=\"2\" class=\"r\">&nbsp;<a href=\"#\" style=\"text-decoration:none\" onclick=\"yy=window.open('offset.php?f=$f','offs','width=220,height=400,resizable=1');yy.focus();return false\" title=\"$lang[72]\">$show_time</a></td>";
print "<td class=\"r\" align=\"center\" nowrap=\"nowrap\"><a href=\"#\" onclick=\"zz=window.open('join.php','join','width=310,height=300,resizable=1');zz.focus();return false\" title=\"$lang[60]\"><img src=\"pics/log.gif\" width=\"24\" border=\"0\" height=\"14\" vspace=\"2\" hspace=\"3\" alt=\"$lang[60]\" /></a> <a href=\"#\" onclick=\"zz=window.open('log.php?f=$f','log','width=310,height=300,resizable=1');zz.focus();return false\" title=\"$login_logout\"><img src=\"pics/ot$is_member.gif\" border=\"0\" width=\"22\" height=\"13\" vspace=\"2\" hspace=\"3\" alt=\"$login_logout\" /></a></td>";
print "<td class=\"r\">$total_topics</td><td class=\"r\">$total_posts</td></tr>";
?></table></td></tr></table>
<?php include "incl/clrs.inc";?><br clear="all" />
<?php include "incl/cust-bot.inc";?>
</body></html>