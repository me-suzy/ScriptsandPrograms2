<?php 
include "config.php";
include "incl/head.inc";
include "incl/format2.inc";

if(!isset($image)||$image==''||$image=='http://'){$img='';}
else{$img="<img src=\"$image\" align=\"right\" border=\"1\" width=\"50\" height=\"50\" onclick=\"show_image(this,'$image')\" vspace=\"4\" hspace=\"4\" onmouseover=\"this.style.cursor='hand'\" onmouseout=\"this.style.cursor='default'\" alt=\"$lang[42]\" title=\"$lang[42]\" />";}

$text=eregi_replace("([A-z0-9._-]+)@([A-z0-9._-]+)","<script type=\"text/javascript\">show_mail('\\1','\\2')</script>",$text);

if(isset($sex)&&$sex!='m'){$sex='s';}else{$sex='w';}

$member="<img src=\"pics/".$sex."1.gif\" $size_img[1] alt=\"\" hspace=\"2\" border=\"0\" align=\"left\" />$name";
$text="<img src=\"pics/t1.gif\" $size_img[2] alt=\"\" border=\"0\" align=\"left\" vspace=\"0\" />$text";
?>
<title><?php print $lang[58];?></title></head><body>
<table width="280" border="0" cellpadding="0" cellspacing="0" align="center"><tr><td class="q">
<table width="100%" border="0" cellspacing="1" cellpadding="5">
<tr class="c"><td><table width="100%" cellpadding="1" cellspacing="0"><tr><td nowrap="nowrap"><span class="c"><?php print $member;?>&nbsp;</span></td></tr></table></td></tr>
<tr class="a"><td class="s"><?php print "$img $text";?></td></tr></table></td></tr></table></body></html>