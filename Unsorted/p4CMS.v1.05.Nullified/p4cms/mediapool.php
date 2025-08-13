<?
 include("include/include.inc.php");
 
 if (!isset($HTTP_SESSION_VARS[u_loggedin]) or !($HTTP_SESSION_VARS[u_loggedin]=='yes')) {
  SessionError();
  exit;
 }
 
 if ($_REQUEST['action']=="upload") {
 	$grp = Gruppe($HTTP_SESSION_VARS[u_gid]);
	if($grp['m_mediapool']=="yes") {
 	?>
<html>
<head>
<title>Hochladen</title>
<? StyleSheet(); ?>
<link rel="stylesheet" href="style/style.css">
<meta http-equiv="Content-Type" content="text/html; charset=windows-1251"></head>
<body bgcolor="" background="/p4cms/gfx/main/bgbody.gif" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<form name="upform" method="post" style="display:inline;" action="mediapool.php?d4sess=<?=$sessid;?>&action=upload2&pfad=<?=$_REQUEST['pfad'];?>&typ=<?=$_REQUEST['typ'];?>" enctype="multipart/form-data">
<center>
<table width="100%" height="100%" border="0" cellpadding="4" cellspacing="0">
  <tr><td align="center" valign="middle" class="boxstandart">
<table width="100%"  border="0" cellspacing="1" cellpadding="2">
  <tr>
    <td>Datei:</td>
    <td><input name="upfile" type="file" size="50"></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td><?
if ($_REQUEST['typ']=="bild") {
	?>
      <br>
      <strong>Optionale Bildverkleinerung<br>
      </strong>Achtung ! Die Verkleinerung funktioniert nur bei JPG &amp; PNG-Bildern <br>
      <br>
      <input type="checkbox" name="resize">
Bildgr&ouml;&szlig;e &auml;ndern auf: &nbsp;&nbsp;&nbsp;
<input type="text" name="w" size="2">
x
<input type="text" name="h" size="2">
Pixel
<?
}
?>
<br>
<br>
<input name="button" type="button" class="button" onClick="this.disabled=true;this.value='Bitte warten...';document.forms['upform'].submit();" value="Hochladen"></td>
  </tr>
</table>
</td>
  </tr></table>
</center>
</form>
</body>
</html>
 	<?
	} else {
		?>
		<script language="javascript">
		<!--
		alert('Ihre Gruppe darf nicht in den Media-Pool hochladen.');
		window.close();
		//-->
		</script>
		<?
	}
 	exit();
 }
 
 if ($_REQUEST['action']=="upload2") {
 	$grp = Gruppe($HTTP_SESSION_VARS[u_gid]);
	if($grp['m_mediapool']=="yes") {

 	$d_name = $_FILES['upfile']['name'];
 	$endg = strtolower(substr($d_name, strlen($d_name) - 4));
 	if (($endg==".jpg" || $endg==".gif" || $endg==".bmp" || $endg==".png")) {
 		$d_tmp = $_FILES['upfile']['tmp_name'];
 		move_uploaded_file($d_tmp, "media" . $_REQUEST['pfad'] . $d_name);
 		@chmod("media" . $_REQUEST['pfad'] . $d_name, 0777);
 		if (isset($_REQUEST['resize'])) {
 			$error = 0;
			if (function_exists("imagecreatetruecolor")){
				$sowhat = "imagecreatetruecolor";}
				else {
					$sowhat = "imagecreate";}
					
 			if (function_exists("imagecreate")) {
 				$neues_bild = $sowhat($_REQUEST['w'], $_REQUEST['h']);
 				if ($endg==".jpg") {
 					$altes_bild = imagecreatefromjpeg("media" . $_REQUEST['pfad'] . $d_name);
 				}
 				if ($endg==".png") {
 					$altes_bild = imagecreatefrompng("media" . $_REQUEST['pfad'] . $d_name);
 				}
 				if ($endg==".gif") {
 					$error = 1;
 				}
 				if (isset($altes_bild)) {
 					imagecopyresized($neues_bild, $altes_bild, 0, 0, 0, 0, imagesx($neues_bild), imagesy($neues_bild), imagesx($altes_bild), imagesy($altes_bild));
 					if ($endg==".jpg") {
 						@unlink("media" . $_REQUEST['pfad'] . $d_name);
 						imagejpeg($neues_bild, "media" . $_REQUEST['pfad'] . $d_name);
 					}
 					if ($endg==".png") {
 						@unlink("media" . $_REQUEST['pfad'] . $d_name);
 						imagepng($neues_bild, "media" . $_REQUEST['pfad'] . $d_name);
 					}
 				}
 			} else {
 				$error = 2;
 			}
 			if ($error!=0) {
 				?>
 				<script>alert('Fehler: <?=$error;?>. Das Bild wurde in voller Größe hochgeladen. Stellen Sie sicher dass das zu verkleinernde Bild als .jpg oder .png hochgeladen wurde und das GD-Libraries auf Ihrem Server aktiviert sind.');</script>
 				<?
 			}
 		}
 	} else {
 		if ($_REQUEST['typ']=="vide" && ($endg==".avi" || $endg==".wmv" || $endg==".mpg")) {
 			$d_tmp = $_FILES['upfile']['tmp_name'];
 			move_uploaded_file($d_tmp, "media" . $_REQUEST['pfad'] . $d_name);
 			@chmod("media" . $_REQUEST['pfad'] . $d_name, 0777);		
 		} else {
 			if ($_REQUEST['typ']=="vide") {
 		?>
 		<script language="javascript">
 		<!--
 		alert('Falscher Dateityp! Erlaubt sind für Videos nur .avi,.wmv und .mpg!');
 		//-->
 		</script>
 		<? 				
 			} else {
 		?>
 		<script language="javascript">
 		<!--
 		alert('Falscher Dateityp! Erlaubt sind für Bilder nur .gif, .jpg, .bmp und .png!');
 		//-->
 		</script>
 		<?
 			}
 		}
 	}
 	?>
 	<script language="javascript">
 	<!--
 	window.opener.parent.frames['zf'].location.href = window.opener.parent.frames['zf'].location.href;
 	window.close();
 	//-->
 	</script>
 	<?

	}

 	exit();
 }
 
 if ($_REQUEST['action']=="delfile") {
	if (@unlink("media" . $_REQUEST['file'])) {
		?>
		<script language="javascript">
		<!--
		alert('Die Datei wurde erfolgreich gelöscht.');
		//-->
		</script>
		<?
	} else {
		?>
		<script language="javascript">
		<!--
		alert('Unbekannter Fehler beim Löschen der Datei.');
		//-->
		</script>
		<?
	}
 	$_REQUEST['action']="list";
 }
 
 if ($_REQUEST['action']=="list") {
 	?>
<html>
<head>
<title>Media-Pool</title>
<? StyleSheet(); ?>
</head>
<body bgcolor="#ffffff" topmargin="0" leftmargin="0">
 	<table border="0" cellspacing="0" width="100%">
 		<tr><td class="boxstandart">&nbsp;</td>
 		<td class="boxstandart"><table width="100%" cellspacing="0" cellpadding="0"><tr><td>&nbsp;Name</td><td align="right"></td></tr></table></td>
 		<td class="boxstandart"><table width="100%" cellspacing="0" cellpadding="0"><tr><td>&nbsp;Gr&ouml;&szlig;e</td><td align="right"></td></tr></table></td>
		<td class="boxstandart">&nbsp;</td>
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
 		<tr><td bgcolor="#FAFAFB" width="19"><img src="gfx/dialog/folder.gif" border="0"></td>
 		<td bgcolor="#FAFAFB" width="45%">&nbsp;<a href="mediapool.php?typ=<?=$_REQUEST['typ'];?>&d4sess=<?=$sessid;?>&dir=<?=$dir . "../";?>&action=list">..</a>&nbsp;&nbsp;</td>
 		<td>&nbsp;</td>
 		<td>&nbsp;</td></tr>
 		<?
 	}
 	
 	$resuld = @mkdir("media/" . $dir . $_REQUEST['newdir']);
  	
 	$d = dir("media/" . $dir);
 	while (false !== ($entry = $d->read())) {
 		if ($entry != "." && $entry != ".." && !($entry == "p4cms" && $dir == "/")) {
 			if (is_dir("media/" . $dir . $entry)) {
 				$elem['dir'][] = $entry;
 			} else {
 				$elem['file'][] = $entry;
 			}
 		}
 	}
 	$d->close();
 	while (list($key,$val) = @each($elem['dir'])) {
 		?>
 		<tr><td bgcolor="#FAFAFB" width="19"><img src="gfx/dialog/folder.gif" border="0"></td>
 		<td width="45%" bgcolor="#FAFAFB">&nbsp;<a href="mediapool.php?typ=<?=$_REQUEST['typ'];?>&d4sess=<?=$sessid;?>&dir=<?=$dir . $val . "/";?>&action=list"><?=$val;?></a>&nbsp;&nbsp;</td>
 		<td>&nbsp;</td>
 		<td>&nbsp;</td></tr>
 		<?
 	}
 	while (list($key,$val) = @each($elem['file'])) {
 		$endg = strtolower(substr($val, strlen($val) - 4));
 		if (($_REQUEST['typ']=="bild" && ($endg==".gif" || $endg==".jpg" || $endg==".bmp" || $endg==".png")) || ($_REQUEST['typ']=="vide" && ($endg==".avi" || $endg==".wmv" || $endg==".mpg"))) {
 		?>
  		<tr><td bgcolor="#FAFAFB" width="19"><img src="gfx/dialog/file.gif" border="0"></td>
 		<td width="45%" bgcolor="#FAFAFB">&nbsp;<a href="<?
 		if ($_REQUEST['typ']=="vide") {
 			?>javascript:selbild('<?=$val;?>',0);<?
 		} else {
 			?>javascript:selbild('<?=$val;?>',1);<?
 		} ?>"><?=$val;?></a>&nbsp;&nbsp;</td>
 		<td>&nbsp;<?=round(filesize("media/" . $dir . $val)/1024,2);?> KB</td>
 		<td><a href="javascript:delfile('<?=$val;?>');"><img src="gfx/dialog/delete.gif" border="0"></a></td>
 		</tr>
 		<?
 		}
 	}
 	?>
 	</table>
 	<script language="javascript">
 	<!--
 	function selbild(src,prv) {
 		if (prv==1) {
 			parent.frames['vs'].location.href = 'media<?=$dir;?>' + src;
 		} else {
 			parent.frames['vs'].location.href = 'about:blank';
 			parent.frames['vs'].document.write('<img dynsrc="media<?=$dir;?>' + src + '" border="0">');
 		}
 		parent.document.all.fn.value = src;
 	}
 	function delfile(src) {
 		if (window.confirm('Wollen Sie die Datei ' + src + ' wirklich löschen?')) {
 			parent.frames['zf'].location.href = 'mediapool.php?typ=<?=$_REQUEST['typ'];?>&action=delfile&dir=<?=$dir;?>&file=<?=$dir;?>' + src + '&d4sess=<?=$sessid;?>';
 		}
 	}
 	parent.document.all.dateiname.value='<?=$dir;?>';
 	//-->
 	</script>
<?
 	if ($_REQUEST['newdir']!="" && isset($_REQUEST['newdir'])) {
 		if ($resuld) {
 			@chmod("media/" . $dir . $_REQUEST['newdir'], 0777);
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
<title>Media-Pool</title>
<? StyleSheet(); ?>
</head>
<body class="boxstandart">
<form style="display:inline;" onSubmit="return false;">
<table cellspacing=0 cellpadding=0 width=100%><tr><td><nobr>Pfad: <input type="text" name="dateiname" size="20" style="width:100%;" readonly></nobr></td><td align=right width=125><a href="javascript:UpDir();"><img src="gfx/dialog/up.gif" border="0" alt="&Uuml;bergeordnetes Verzeichnis"></a> &nbsp; <a href="javascript:NewFolder();"><img src="gfx/dialog/create.gif" border="0" alt="Neues Verzeichnis"></a> &nbsp; <a href="javascript:updlg();"><img src="gfx/dialog/upload.gif" border="0" alt="Datei hochladen"></a></td></tr></table>
<br>
<iframe name="zf" id="zf" width="70%" height="340" scrolling="yes" src="mediapool.php?typ=<?=$_REQUEST['typ'];?>&d4sess=<?=$sessid;?>&dir=/&action=list"></iframe><iframe width="30%" height="83%" name="vs" id="vs"></iframe><br><br>
<table width=100% cellspacing=0 cellpadding=0><tr><td width=90><nobbr>Dateiname: </td><td><nobr><input type="text" name="fn" size="20" style="width:100%;" readonly></td><td width=80 align=right><nobr><input type="button" value="Okay" onClick="submitTheForm();"></nobr></td></tr></table></form>
</body>
<script language="javascript">
<!--
function submitTheForm() {
	if (document.all.fn.value == '') {
		alert('Bitte einen Dateinamen angeben!');
	} else {
		<?
		if($_REQUEST['target'] == "filename") {
		?>
		window.opener.document.all['filename'].value = '<?=$p4cms_pfad;?>/media' + document.all.dateiname.value + document.all.fn.value;
		//window.opener.updatePreview();
		<?	
		} else {
		?>
		window.opener.document.all['img_<?=$_REQUEST['target'];?>'].<?
		if ($_REQUEST['typ']=="vide") {
			echo "dyn";
		} ?>src = 'media' + document.all.dateiname.value + document.all.fn.value;
		window.opener.document.all['<?=$_REQUEST['target'];?>'].value = document.all.dateiname.value + document.all.fn.value;
		<?
		}
		?>
		parent.close();
	}
}
function UpDir() {
	parent.frames['zf'].location.href='mediapool.php?typ=<?=$_REQUEST['typ'];?>&dir=' + document.all.dateiname.value + '../&d4sess=<?=$sessid;?>&action=list';
}
function NewFolder() {
	var dname = window.prompt('Bitte geben Sie einen Namen für das Verzeichnis ein:', 'Neues Verzeichnis');
	if (dname=='' || dname==null) {
		alert('Bitte geben Sie einen Dateinamen ein!');
	} else {
		parent.frames['zf'].location.href='mediapool.php?typ=<?=$_REQUEST['typ'];?>&dir=' + document.all.dateiname.value + '&d4sess=<?=$sessid;?>&action=list&newdir=' + dname;
	}	
}
function updlg() {
	var url = 'mediapool.php?d4sess=<?=$sessid;?>&action=upload&pfad=' + document.all.dateiname.value + '&typ=<?=$_REQUEST['typ'];?>';
 	var winWidth = 500;
  	var winHeight = 200;
  	var w = (screen.width - winWidth)/2;
  	var h = (screen.height - winHeight)/2 - 60;
  	var name = 'upload2mp';
  	var features = 'scrollbars=no,width='+winWidth+',height='+winHeight+',top='+h+',left='+w;
  	window.open(url,name,features);
}
//-->
</script>
</html>
<?
 }
?>
