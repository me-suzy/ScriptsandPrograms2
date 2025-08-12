<? include("protect.php"); ?>

<?php
$data_file='news.txt';
$changes='';

if(isset($contents)) {
      $archive=fopen($data_file,'r+');
      flock($archive,2);
      ftruncate($archive,0);
      fputs($archive,stripslashes($contents));
      flock($archive,3);
      fclose($archive);
      unset($contents);
     $changes='<b style="color:#ff6600">Changes made!</b>&nbsp;&nbsp;&raquo;&nbsp;&nbsp;<a href="../">View Changes</a><br />';
}

function getfile($filename) {
   $fd = fopen($filename, "rb");
   $content = fread($fd, filesize($filename));
  fclose($fd);
   return $content;
}

$data=getfile($data_file);
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

<html>
<head>
	<title>GateQuest php News/Content Publisher</title>
	<link rel="stylesheet" href="../docs/misc/styles.css" type="text/css">
</head>

<body>

<table style="height: 100%; text-align: center; width: 90%">
	<tr>
		<td style="height: 65px; padding: 10px 0px 0px 10px; text-align: left"><a href="http://www.gatequest.net/products/News_Content_Publisher/"><img alt="" border="0" width="100" height="50" src="../docs/misc/gfx/gq_logo.gif"></a></td>
	</tr>
	<script language="Javascript" type="text/javascript">
	/******************************************************************* 
	*  Copyright © GateQuest, Inc. - gatequest.net
	*  Author:	D Stewart  (dstewart@gatequest.net)
	*  Notes:	Please visit http://gatequest.net/property before use.  
	********************************************************************/
	<!--
	function view() {
	q=open("","l"," status=0,toolbar=0,width=560,height=420,resizable=1,menubar=0,location=0");
	q.document.open();
	q.document.write('<html><head><title>News/Content Publisher - Preview Window</title>');
	q.document.write('<link rel="stylesheet" href="../docs/misc/styles.css" type="text/css">');
	q.document.write('</head><body><table bgcolor="#ffffff" width="100%" height="100%"><tr valign="top"><td style="color: #666666; line-height: 16px; padding: 10px 20px 0px 20px;">');
	q.document.write(document.forms[0].contents.value);
	q.document.write('</td></tr>');
	q.document.write('<tr valign="bottom"><td align="center" style="line-height: 16px;"><a href="javascript:window.close();"  onmouseover="window.status = \'Close Window\'; return true;" onmouseout="window.status = \'\'; return true;">Close Window</a>');
	q.document.write('</td></tr></table>');
	q.document.write('</body></html>');
	q.document.close();
	}
	
	//-->
	</script>
	<script language="JavaScript" type="text/javascript">
	<!--
	
	var imageTag = false;
	var theSelection = false;
	
	var clientPC = navigator.userAgent.toLowerCase();
	var clientVer = parseInt(navigator.appVersion);
	
	var is_ie = ((clientPC.indexOf("msie") != -1) && (clientPC.indexOf("opera") == -1));
	var is_nav = ((clientPC.indexOf('mozilla')!=-1) && (clientPC.indexOf('spoofer')==-1)
	                && (clientPC.indexOf('compatible') == -1) && (clientPC.indexOf('opera')==-1)
	                && (clientPC.indexOf('webtv')==-1) && (clientPC.indexOf('hotjava')==-1));
	var is_moz = 0;
	
	var is_win = ((clientPC.indexOf("win")!=-1) || (clientPC.indexOf("16bit") != -1));
	var is_mac = (clientPC.indexOf("mac")!=-1);
	
	b_help = "Bold text: <b>text</b> or (alt+b)";
	i_help = "Italic text: <i>text</i> or (alt+i)";
	u_help = "Underline text: <u>text</u> or (alt+u)";
	s_help = "Insert Single Space: <br> or (alt+s)";
	d_help = "Insert Double Space: <p> or (alt+d)";
	w_help = "Insert URL: <a href=\"http://url\">URL text</a> or (alt+w)";
	
	function helpline(help) {
		document.editform.helpbox.value = eval(help + "_help");
	}
	
	//-->
	</script>
	<form name="editform" method="post" action="<?php echo $_SERVER['PHP_SELF'];?>" onsubmit="if(q&&!q.closed)q.close();return true">
	<tr>
		<td style="vertical-align: middle">
		<div align="center"><fieldset style="border: 1px solid #CCC; width: 50%"><legend style="margin-left: 8px">&nbsp;Edit News/Events Information Here:&nbsp;</legend>
		<div style="padding: 20px">
		<div align="right"><a href="<? echo $PHP_SELF ?>?action=logout"><img src="../docs/misc/gfx/logout.gif" alt="Log Out" width="17" height="16" border="0" align="absmiddle"> Log Out</a></div>
		<?php echo $changes; ?>
		<p><input accesskey="b" onMouseOver="helpline('b')" style="font-weight: bold; margin:0px; width: 30px" type="button" value=" B " onClick="editform.contents.value+='<b></b>'"> <input accesskey="i" onMouseOver="helpline('i')" style="font-style: italic; margin:0px; width: 30px" type="button" value=" i " onClick="editform.contents.value+='<i></i>'"> <input accesskey="u" onMouseOver="helpline('u')" style="margin:0px; text-decoration: underline; width: 30px" type="button" value=" u " onClick="editform.contents.value+='<u></u>'"> <input accesskey="s" onMouseOver="helpline('s')" style="margin:0px" type="button" value="Space" onClick="editform.contents.value+='<br>'"> <input accesskey="d" onMouseOver="helpline('d')" style="margin:0px" type="button" value="Dbl Space" onClick="editform.contents.value+='<p>'"> <input accesskey="w" onMouseOver="helpline('w')" style="margin:0px; text-decoration: underline" type="button" value="URL" onClick="editform.contents.value+='<a href=&quot;http://url&quot;>URL text</a>'"><br><input type="text" name="helpbox" size="80" maxlength="150" style="border: none; color: #cc0033; margin: 8px 0px 0px 0px" value="Tip: Styles can be applied quickly to selected text." /><br>
											<textarea name="contents" rows="20" cols="50" tabindex="2"><?php echo htmlspecialchars(stripslashes($data)); ?></textarea><br>
											<input class="send" style="margin:0px;" type="button" onClick="view()" value="Preview" />&nbsp; &nbsp;<input class="send" type="submit" name="submitdata" value="Submit" />&nbsp; &nbsp;<input class="reset" type="reset" name="resetdata" value="Erase Changed" /></p></div></fieldset></div></td>
	</tr>
	</form>
</table>

</body>
</html>
