<?php 
include "config.php";
include "incl/head.inc";

if(!isset($us)){die('<title>...</title></head><body> </body></html>');}

$info='...';$member='...';$img='';

$fs=open_file($members_file);
$fs=explode("\n",$fs);

for($i=1;$i<count($fs);$i++){
if(strlen($fs[$i])>9){
$row=explode(":|:",$fs[$i]);

if(strtolower($row[0])==strtolower($us)){

if(isset($row[4])&&$row[4]!=''&&$row[4]!='http://')
{$img="<img src=\"$row[4]\" align=\"right\" border=\"1\" width=\"50\" height=\"50\" onclick=\"show_image(this,'$row[4]')\" vspace=\"4\" hspace=\"4\" onmouseover=\"this.style.cursor='hand'\" onmouseout=\"this.style.cursor='default'\" alt=\"$lang[42]\" title=\"$lang[42]\" />";}

$info=$row[3];$info=eregi_replace("([A-z0-9._-]+)@([A-z0-9._-]+)","<script type=\"text/javascript\">show_mail('\\1','\\2')</script>",$info);
if(isset($row[5])&&$row[5]!='m'){$sex='s';}else{$sex='w';}
$member="<img src=\"pics/".$sex."1.gif\" $size_img[1] alt=\"\" hspace=\"2\" border=\"0\" align=\"left\" />$us";
$info="<img src=\"pics/t1.gif\" $size_img[2] alt=\"\" border=\"0\" align=\"left\" vspace=\"0\" />$info";
}}}
?><title><?php print $lang[58];?></title></head><body>
<table width="280" border="0" cellpadding="0" cellspacing="0" align="center"><tr><td class="q">
<table width="100%" border="0" cellspacing="1" cellpadding="5">
<tr class="c"><td><table width="100%" cellpadding="1" cellspacing="0"><tr><td nowrap="nowrap"><span class="c"><?php print $member;?></span></td><td align="right" nowrap="nowrap"><b><a style="color:#ffffff" href="memed.php?name=<?php print $us;?>"><?php print $lang[22];?></a></b></td></tr></table></td></tr>
<tr class="a"><td class="s"><?php print "$img $info";?></td></tr></table></td></tr></table></body></html>