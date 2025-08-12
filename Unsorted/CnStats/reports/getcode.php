<?php

if ($COUNTER["type"]==2) {print $TABLE; ?>

<tr class=tbl1><td align=center><b>PHP include</b></td></tr>
<tr class=tbl2><td>
&lt;?<br>
<?php
if ($HTTP_SERVER_VARS["DOCUMENT_ROOT"][strlen($HTTP_SERVER_VARS["DOCUMENT_ROOT"])-1]!="/") $HTTP_SERVER_VARS["DOCUMENT_ROOT"].="/";

print "include &quot;".$HTTP_SERVER_VARS["DOCUMENT_ROOT"]."cnstats/cnt.php&quot;;<br>\n";
?>
?&gt;
</td></tr>
</table>
<?php } ?>

<?php if ($COUNTER["type"]==1) {print $TABLE; ?>
<tr class=tbl1><td align=center><b>PNG</b></td></tr>
<tr class=tbl2><td>
&lt;SCRIPT Language="JavaScript"&gt;<br>
&lt;!--<br>
document.write("&lt;img src='/cnstats/cntg.php?r="+escape(document.referrer)+<br>
'&'+Math.random()+"' width=88 height=31 border=0&gt;");<br>
// --&gt;<br>
&lt;/SCRIPT&gt;<br>
&lt;NOSCRIPT&gt;&lt;img src="/cnstats/cntg.php?r=nojs" width="88" height="31"<br>
border="0"&gt;&lt;/NOSCRIPT&gt;<br>
</td></tr>
</table>
<?php } ?>

<?php if ($COUNTER["type"]==0) {
	print $TABLE;
	if (!empty($HTTP_SERVER_VARS["HTTP_HOST"])) $HTTP_SERVER_VARS["HTTP_HOST"]="http://".$HTTP_SERVER_VARS["HTTP_HOST"];
?>
<tr class=tbl1><td align=center><b>GIF 1x1</b></td></tr>
<tr class=tbl2><td>
&lt;SCRIPT language="JavaScript"&gt;<br>
cnsd=document;cnsd.cookie="b=b";cnsc=cnsd.cookie?1:0;<br>
document.write('&lt;img src="<?=$HTTP_SERVER_VARS["HTTP_HOST"];?>/cnstats/cntg.php?c='+cnsc+'&r='+escape(cnsd.referrer)+'&p='+escape(cnsd.location)+'" width="1" height="1" border="0"&gt;');<br>
&lt;/SCRIPT&gt;&lt;NOSCRIPT&gt;&lt;img src="<?=$HTTP_SERVER_VARS["HTTP_HOST"];?>/cnstats/cntg.php?468&c=0" width="1" height="1" border="0"&gt;&lt;/NOSCRIPT&gt;<br>
</td></tr>
</table>
<?php }

$NOFILTER=1;
?>
