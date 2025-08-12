<?php 
include "config.php";
$encoding=explode(":",$lang[1]);
$url=str_replace("klip.php","","$SERVER_NAME$SCRIPT_NAME");
$forum_name[$f]=strip_tags($forum_name[$f]);
$forum_desc[$f]=strip_tags($forum_desc[$f]);

header("Content-Disposition: attachment; filename=forum-$f.klip");
header("Content-Type: application/download");
?>
<klip>

<owner>
<author><?php print $SERVER_NAME;?></author>
<copyright><?php print $SERVER_NAME;?></copyright>
<email><?php print 'info@'.$SERVER_NAME;?></email>
<web><?php print $SERVER_NAME;?></web>
</owner>

<identity>
<title><?php print $forum_name[$f];?></title>
<version>1.0</version>		
<lastmodified><?php print date('Y.m.d:Hi');?></lastmodified>
<description><?php print $forum_desc[$f];?></description>
</identity>
	
<locations>
<defaultlink><?php print 'http://'.$url;?></defaultlink>
<contentsource><?php print 'http://'.$url.'rdf.php?f='.$f;?></contentsource>
<icon><?php print 'http://'.$url.'pics/b1.gif';?></icon>
<kliplocation><?php print 'http://'.$url.'klip.php?f='.$f;?></kliplocation>	
</locations>
	
<setup>
<refresh>15</refresh>
<language><?php print $encoding[1];?></language>
</setup>

<messages>
<loading>Getting data...</loading>
<nodata>No items to display.</nodata>
</messages>

</klip>