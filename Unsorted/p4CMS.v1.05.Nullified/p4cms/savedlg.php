<?
 include("include/include.inc.php");
 
 if (!isset($HTTP_SESSION_VARS[u_loggedin]) or !($HTTP_SESSION_VARS[u_loggedin]=='yes')) {
  SessionError();
  exit;
 }
 
 if ($_REQUEST['action']=="list") {
 	?>
<html>
<head>
<title>Speicherort wählen</title>
<link rel="stylesheet" href="style/style.css">
<? StyleSheet(); ?>
</head>
<body bgcolor="" background="/p4cms/gfx/main/bgbody.gif"  topmargin="0" leftmargin="0">

 	<table border="0" cellspacing="0" width="100%">
 		<tr><td class="boxheader">&nbsp;</td>
 		<td class="boxheader"><table width="100%" border="0" cellpadding="0" cellspacing="0">
 		  <tr><td width="90%">&nbsp;Name</td><td width="10%" align="right"><img src="gfx/dialog/sep.gif" border="0"></td></tr></table></td>
 		<td class="boxheader"><table width="100%" border="0" cellpadding="0" cellspacing="0">
 		  <tr><td>&nbsp;Gr&ouml;&szlig;e</td><td align="right"><img src="gfx/dialog/sep.gif" border="0"></td></tr></table></td>
 		<td class="boxheader"><table width="100%" border="0" cellpadding="0" cellspacing="0">
 		  <tr><td>&nbsp;Typ</td><td align="right"><img src="gfx/dialog/sep.gif" border="0"></td></tr></table></td>
		<td class="boxheader">&nbsp;Ge&auml;ndert</td>
 		</tr>
 	<? 	 	
 	$dir = $_REQUEST['dir'];
 	
 	if (substr($dir,strlen($dir)-4)=="/../") {
 		$zerlegen = explode("/", $dir);
 		$myf = count($zerlegen) - 3;
 		$myd = "";
 		for ($i=0; $i<$myf; $i++) {
 			if ($zerlegen[$i]!="") {
 				$myd .= "/" . $zerlegen[$i];
 			}
 		}
 		if (substr($myd, strlen($myd)-1)=="/") {
 			$dir = $myd;
 		} else {
 			$dir = $myd . "/";
 		}
 	}
 	
 	if (!($dir=="/")) {
 		?>
 		<tr><td bgcolor="#FAFAFB" width="19"><a href="savedlg.php?d4sess=<?=$sessid;?>&dir=<?=$dir . "../";?>&action=list"><img src="gfx/tree/folder.gif" alt="" border="0"></a></td>
 		<td bgcolor="#FAFAFB" width="45%">&nbsp;<a href="savedlg.php?d4sess=<?=$sessid;?>&dir=<?=$dir . "../";?>&action=list">..</a>&nbsp;&nbsp;</td>
 		<td>&nbsp;</td>
 		<td bgcolor="#FAFAFB">&nbsp;Verzeichnis</td>
 		<td>&nbsp;</td></tr>
 		<?
 	}
 	
 	$resuld = @mkdir("../" . $dir . $_REQUEST['newdir']);
  	
 	$d = dir("../" . $dir);
 	while (false !== ($entry = $d->read())) {
 		if ($entry != "." && $entry != "..") {
 			if (is_dir("../" . $dir . $entry)) {
 				$elem['dir'][] = $entry;
 			} else {
 				$elem['file'][] = $entry;
 			}
 		}
 	}
 	$d->close();
 	while (list($key,$val) = @each($elem['dir'])) {
 		?>
 		<tr><td bgcolor="#FAFAFB" width="19"><img src="gfx/tree/folder.gif" border="0"></td>
 		<td width="45%" bgcolor="#FAFAFB">&nbsp;<a href="savedlg.php?d4sess=<?=$sessid;?>&dir=<?=$dir . $val . "/";?>&action=list"><?=$val;?></a>&nbsp;&nbsp;</td>
 		<td>&nbsp;</td>
 		<td bgcolor="#FAFAFB">&nbsp;Verzeichnis</td>
 		<td>&nbsp;</td></tr>
 		<?
 	}
 	while (list($key,$val) = @each($elem['file'])) {
 		?>
  		<tr><td bgcolor="#FAFAFB" width="19"><img src="gfx/tree/page.gif" alt="" border="0"></td>
 		<td width="45%" bgcolor="#FAFAFB">&nbsp;<?=$val;?>&nbsp;&nbsp;</td>
 		<td>&nbsp;<?=round(filesize("../" . $dir . $val)/1024,2);?> KB</td>
 		<td bgcolor="#FAFAFB">&nbsp;Datei</td>	
 		<td>&nbsp;<?=date("d.m.Y H:i:s", filemtime("../" . $dir . $val));?></td>
 		<td>
 		<?
 	}
 	?>
</table>
 	<script language="javascript">
 	<!--
 	parent.document.all.dateiname.value='<?=$dir;?>';
 	var uplink = 'savedlg.php?d4sess=<?=$sessid;?>&dir=<?=$dir . "../";?>&action=list';
 	//-->
 	</script>
<?
 	if ($_REQUEST['newdir']!="" && isset($_REQUEST['newdir'])) {
 		if ($resuld) {
 			@chmod("../" . $dir . $_REQUEST['newdir'], 0777);
 			?>
 			<script language="javascript">
 			<!--
 			alert('Das Verzeichnis wurde erfolgreicht erstellt.');
 			//-->
 			</script>
 			<?
		} else {
 			?>
 			<script language="javascript">
 			<!--
 			alert('Fehler: Das Verzeichnis konnte nicht erstellt werden!');
 			//-->
 			</script>
 			<?			
		}
	}
	?>
</body>
</html>
<?
 } else {
?>
<html>
<head>
<title>Speicherort wählen</title>
<? StyleSheet(); ?>
</head>
<body bgcolor="#F6F6F6">
<form style="display:inline;" onSubmit="return false;">
<table cellspacing=0 cellpadding=0 width=100%><tr><td><nobr>Pfad: <input type="text" name="dateiname" size="20" style="width:100%;" readonly></nobr></td><td align=right width=100><a href="javascript:UpDir();"><img src="gfx/dialog/up.gif" border="0" alt="&Uuml;bergeordnetes Verzeichnis"></a> &nbsp; <a href="javascript:NewFolder();"><img src="gfx/dialog/create.gif" border="0" alt="Neues Verzeichnis"></a></td></tr></table>
<br>
<iframe name="zf" id="zf" width="100%" height="340" scrolling="yes" src="savedlg.php?d4sess=<?=$sessid;?>&dir=/&action=list"></iframe><br><br>
<table width=100% cellspacing=0 cellpadding=0><tr><td width=90><nobbr>Dateiname: </td><td><nobr><input type="text" name="fn" size="20" style="width:95%;">.<?
if (isset($_REQUEST[exta])) {
	echo $_REQUEST[exta];
} else {
	echo "<select name=\"typdat\" id=\"typdat\">
    <option value=\"htm\" selected>htm</option>
    <option value=\"html\">html</option>
    <option value=\"php\">php</option>";
} ?></td><td width=80 align=right><nobr><input type="button" value="Okay" onClick="submitTheForm();"></nobr></td></tr></table></form>
</body>
<script language="javascript">
<!--
function submitTheForm() {
	if (document.all.fn.value == '') {
		alert('Bitte einen Dateinamen eingeben!');
	} else {
		window.opener.document.all.filename.value = document.all.dateiname.value + document.all.fn.value + '.' + <?
		if (isset($_REQUEST[exta])) {
	echo "'".$_REQUEST[exta]."'";
} else { ?>document.all.typdat.value<? }
		?>;
		<?
		if (isset($_REQUEST[exta])) {
			?>
			window.opener.document.all.dosave.value='y';
			window.opener.ref();
			window.opener.document.all.dosave.value='n';
			<?
		}
		?>
		parent.close();
	}
}
function UpDir() {
	parent.frames['zf'].location.href='savedlg.php?dir=' + document.all.dateiname.value + '../&d4sess=<?=$sessid;?>&action=list';
}
function NewFolder() {
	var dname = window.prompt('Bitte geben Sie einen Namen für das Verzeichnis ein:', 'Neues Verzeichnis');
	if (dname=='' || dname==null) {
		alert('Bitte geben Sie einen Dateinamen ein!');
	} else {
		parent.frames['zf'].location.href='savedlg.php?dir=' + document.all.dateiname.value + '&d4sess=<?=$sessid;?>&action=list&newdir=' + dname;
	}	
}
//-->
</script>
</html>
<?
 }
?>
