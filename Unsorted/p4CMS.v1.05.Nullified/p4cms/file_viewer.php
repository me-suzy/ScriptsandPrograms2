<?
 include("include/include.inc.php");
 
 if (!isset($HTTP_SESSION_VARS[u_loggedin]) or !($HTTP_SESSION_VARS[u_loggedin]=='yes')) {
  SessionError();
  exit;
 }
 
 
 if ($_REQUEST[action]=="view") {
 	show_source("../" . $_REQUEST[path]);
 	exit;
 }
 
 if ($_REQUEST[action]=="download") {
    header("Cache-control: private");
	header("Content-type: application/octet-stream"); 
	header("Content-length: " . filesize("../" . $_REQUEST[path]));
	header("Content-disposition:attachment; filename=" . basename($_REQUEST[path]));
 	$inhalt = CNTFile("../" . $_REQUEST[path]);
 	echo $inhalt;
 	exit;	
 }
 
 if ($_REQUEST[action]=="save") {
 	$handle = @fopen("../" . $_REQUEST[path], "w");
 	@fwrite($handle, stripslashes($_REQUEST[inhalt]));
 	@fclose($handle);
 }
?>
<html>
<head>
 <title>p4cms :: FileManager</title>
 <? StyleSheet(); ?>  
</head>
<body class="boxstandart">
<table width="100%" height="100%" cellspacing="0" cellpadding="0">
<tr>
    <td valign="top"> <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
        <tr> 
          <td> <table width="100%" border="0" align="center" cellpadding="8" cellspacing="0">
              <tr> 
                <td valign="top"><p><font size="1" face="Verdana, Arial, Helvetica, sans-serif"> 
					<table width="100%">
					<tr>
					<td width="50" align="center"><img src="gfx/ext/default.gif" border="0"></td>
					<td><? echo($_REQUEST[path]); ?> (<?
$siz = filesize("../" . $_REQUEST[path]);
$kb = round($siz / 1024, 2);
echo $kb . " KB";				
?>)<br>
					  <i>(Diese Datei wurde nicht mit p4cms erstellt)</i></td>
					</tr>
					<tr>
					<td>&nbsp;</td>
					<form name="editor" action="file_viewer.php?d4sess=<? echo($sessid); ?>&action=save&path=<? echo($_REQUEST[path]); ?>" method="post">
					<td><?
						function Ext($ext, $string) {
							if (substr($string, strlen($string) - strlen($ext))==$ext) {
								return true;
							} else {
								return false;
							}
						}
						
						if (Ext(".php", $_REQUEST[path]) or Ext(".php4", $_REQUEST[path]) or Ext(".txt", $_REQUEST[path]) or Ext(".css", $_REQUEST[path]) or Ext(".vbs", $_REQUEST[path]) or Ext(".js", $_REQUEST[path])) {
							?>
							<textarea style="width:98%;height=300" name="inhalt"><? ob_start(); readfile("../" . $_REQUEST[path]); $cont = ob_get_contents(); ob_end_clean(); echo(htmlentities($cont)); ?></textarea>
							<br><br>
							<?
							$dsp = 1;
						}
						
						if (Ext(".jpg", $_REQUEST[path]) or Ext(".gif", $_REQUEST[path]) or Ext(".bmp", $_REQUEST[path]) or Ext(".jpeg", $_REQUEST[path]) or Ext(".png", $_REQUEST[path])) {
							?>
							<img src="../<? echo($_REQUEST[path]); ?>" border="0">
							<br><br>
							<?	
							//$dsp = 1;
						}
						
						if (Ext(".html", $_REQUEST[path]) or Ext(".htm", $_REQUEST[path])) {
							?>
							<input type="hidden" name="inhalta" value="">
							<?
							$ed =& new p4cmsEditor(CNTFile("../" . $_REQUEST[path]));
							$ed->CreateFCKeditor("inhalt", "98%", "300");
							$dsp = 0;
							
							if (is_writable("../" . $_REQUEST[path])) {
								?>
								<br><br>
								<a href="javascript:document.forms['editor'].submit();"><img src="gfx/ext/speichern.gif" border="0"></a>
								<?
							}
						}

					if (is_writable("../" . $_REQUEST[path]) && $dsp == 1) {
						?>
						<input type="image" action="submit" src="gfx/ext/speichern.gif" border="0">&nbsp;
						<?
					}
					?><a href="file_viewer.php?d4sess=<? echo($sessid); ?>&action=download&path=<? echo($_REQUEST[path]); ?>"><img src="gfx/ext/download.gif" border="0"></a></td>
					</tr>
					</form>
					</table>
					<br>
                    </font></td>
              </tr>
            </table></td>
        </tr>
      </table>
    </td>
</tr>

</table>
</body>
</html>