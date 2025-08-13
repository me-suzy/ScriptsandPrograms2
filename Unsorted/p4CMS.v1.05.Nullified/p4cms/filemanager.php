<? ob_start();
$cmsdemo=1; 
 include("include/include.inc.php");
 
 if (!isset($HTTP_SESSION_VARS[u_loggedin]) or !($HTTP_SESSION_VARS[u_loggedin]=='yes')) {
  SessionError();
  exit;
 }
 
 if($_REQUEST['act']=="reswith"){ 
 $_REQUEST['do']="dir";
 $dodir = $_REQUEST['dir'];
}
 
 if ($HTTP_SESSION_VARS[u_gid] == 1) {
 	if ($_REQUEST['action']=="download") {
 		$file = $_REQUEST['file'];
 		$file = str_replace("..","",$file);
 		
 		$teile = explode("/", $file);
 		$fname = $teile[count($teile)-1];
 		
 		header("Content-Type: application/octet-stream");
 		header("Content-Length: " . filesize(".." . $file));
 		header("Content-Disposition: attachment; filename=$fname");
 		
 		readfile(".." . $file);
 		
 		exit();
 	}
 	
 	if (!isset($_REQUEST['action']) || $_REQUEST['action']=="frames") {
 		?>
<html>
<head>
<title>p4cms :: FileManager</title>
</head>
<frameset cols="180,*" border="1">
 <frame src="/p4cms/filemanager.php?action=left&d4sess=<? echo($sessid); ?>" scrolling="yes" name="strukt">
 <frameset rows="*,80" border="1">
  <frame src="/p4cms/filemanager.php?action=dir&dir=/&d4sess=<? echo($sessid); ?>&do=<?=$_REQUEST['do'];?>" scrolling="yes" name="haupt">
  <frame src="/p4cms/filemanager.php?action=unten&dir=/&d4sess=<? echo($sessid); ?>" scrolling="no" name="unten">
 </frameset>
</frameset><noframes></noframes>
</html> 		
 		<?	
 	} else {
 		?>
<html>
<head>
 <title>p4cms :: Dateiupload</title>
 <? StyleSheet(); ?>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1251"></head>
		<?
		if ($_REQUEST['action']=="left") {
			?>
	<body>
	<script type="text/javascript">
		<!--

		d = new dTree('d');			
		d.add(0,-1,"Wurzelverzeichnis","javascript:SwitchPage('filemanager.php?d4sess=<? echo($sessid); ?>&action=dir&dir=/', 'haupt');");
		<?
		$dc = 1;
		
		function DirList($dir, $parent) {
			global $dc;
			$d = dir(".." . $dir);
			while ($entry = $d->read()) {
				if (is_dir(".." . $dir . $entry) && $entry != "." && $entry != "..") {
					?>
		d.add(<?=$dc;?>,<?=$parent;?>,"<?=$entry;?>","javascript:SwitchPage('filemanager.php?d4sess=<? echo($sessid); ?>&action=dir&dir=<?=$dir.$entry."/";?>', 'haupt');","","","gfx/tree/folder.gif");
					<?
					$dc++;
					DirList($dir . $entry . "/", $dc - 1);
				}
			}
			$d->close();
		}
		


		DirList("/", 0);	
		?>
		
		document.write(d);
		
		//-->
	</script>
			<?
		}
		
		function deldir($dir){
			$current_dir = opendir($dir);
			while($entryname = readdir($current_dir)){
				if(is_dir("$dir/$entryname") and ($entryname != "." and $entryname!="..")){
					deldir("${dir}/${entryname}");
				}elseif($entryname != "." and $entryname!=".."){
					unlink("${dir}/${entryname}");
				}
			}
			closedir($current_dir);
			rmdir(${dir});
		}
		
		if ($_REQUEST['action']=="deldir") {
			$file = $_REQUEST['file'];
			$file = str_replace("..","",$file);
			$full = ".." . $file;
			
			echo "<script>";
			
			if (file_exists($full) && is_dir($full)) {
				deldir($full);
				?>
				alert("Der Ordner wurde gelöscht.");
				<?
			} else {
				?>
				alert("Ordner wurde nicht gefunden.");
				<?				
			}			
						
			echo "</script>";
			
			$teile = explode("/", $file);
			$cnt = 0;
			while (list($key,$val) = each($teile)) {
				$cnt++;
				if ($cnt != count($teile)) {
					$pfad .= $val . "/";
				}
			}
			
			$pfad = "/" . $pfad;
			
			$_REQUEST['action'] = "dir";
			$_REQUEST['dir'] = $pfad;
		}
		
		if ($_REQUEST['action']=="upload") {
			?>
<body class="boxstandart">
<form name="upform" method="post" style="display:inline;" action="/p4cms/filemanager.php?d4sess=<?=$sessid;?>&action=upload2&dir=<?=$_REQUEST['dir'];?>" enctype="multipart/form-data">
<center>
<table height="100%"><tr><td valign="middle" align="center">
Datei #1: <input type="file" name="upfile1">
<br>
Datei #2: <input type="file" name="upfile2">
<br>
Datei #3: <input type="file" name="upfile3">
<br>
Datei #4: <input type="file" name="upfile4">
<br>
Datei #5: <input type="file" name="upfile5">
<br>
<input class="button"  type="button" value="Hochladen" onClick="this.disabled=true;this.value='Bitte warten...';document.forms['upform'].submit();">
</td></tr></table>
</center>
</form>
			<?
		}
		
		function UpFile($id, $dir) {
			if (isset($_FILES['upfile' . $id])) {
				move_uploaded_file($_FILES['upfile' . $id]['tmp_name'], ".." . $dir . $_FILES['upfile' . $id]['name']);
			}
		}
		
		if ($_REQUEST['action']=="upload2") {
			$dir = str_replace("..", "", $_REQUEST['dir']);
			
			UpFile(1, $dir);
			UpFile(2, $dir);
			UpFile(3, $dir);
			UpFile(4, $dir);
			UpFile(5, $dir);
			
			?>
			<script language="javascript">
			<!--
				window.opener.parent.frames['haupt'].location.href = 'filemanager.php?d4sess=<?=$sessid;?>&action=dir&dir=<?=$dir;?>';
				window.close();
			//-->
			</script>
			<?
		}
		
		if ($_REQUEST['action']=="unten") {
			?>
			<body bgcolor="#FAFAFB">
			<script language="javascript">
			<!--
				function CreateDir() {
					<?
					if (is_writeable(".." . $_REQUEST['dir'])) {
						?>
	var dname = window.prompt('Bitte geben Sie einen Namen für das Verzeichnis ein:', 'Neues Verzeichnis');
	if (dname=='' || dname==null) {
		alert('Bitte geben Sie einen Dateinamen ein!');
	} else {
		parent.frames['haupt'].location.href='filemanager.php?dir=<?=$_REQUEST['dir'];?>&d4sess=<?=$sessid;?>&action=mkdir&newdir=' + dname;
	}							
						<?
					} else {
						?>
						alert("Das aktuelle Verzeichnis ist nicht beschreibbar. Bitte die Rechte entsprechend ändern.");
						<?
					}
					?>
				}
				function Upload() {
					<?
					if (is_writeable(".." . $_REQUEST['dir'])) {
						?>
	var url = 'filemanager.php?d4sess=<?=$sessid;?>&action=upload&dir=<?=$_REQUEST['dir'];?>';
 	var winWidth = 400;
  	var winHeight = 250;
  	var w = (screen.width - winWidth)/2;
  	var h = (screen.height - winHeight)/2 - 60;
  	var name = 'upload2fm';
  	var features = 'scrollbars=no,width='+winWidth+',height='+winHeight+',top='+h+',left='+w;
  	window.open(url,name,features);						
						<?
					} else {
						?>
						alert("Das aktuelle Verzeichnis ist nicht beschreibbar. Bitte die Rechte entsprechend ändern.");
						<?
					}
					?>					
				}
			//-->
			</script>
			<center>
			<input class="button" type="button" value=" Verzeichnis erstellen " onClick="CreateDir();"> 
			<input class="button" type="button" value=" Datei(en) hochladen " onClick="Upload();"><br>
<img src="/p4cms/gfx/dialog/delete.gif" alt="" vspace="5" border="0" align="absmiddle">l&ouml;schen <img src="/p4cms/gfx/code.png" alt="Einbindungs-Pfad anzeigen" hspace="2" vspace="5" border="0" align="absmiddle">Pfad/Dateiname <img src="/p4cms/gfx/listshow.gif" alt="umbenennen" hspace="2" vspace="5" border="0" align="absmiddle">umbenennen <img src="/p4cms/gfx/main/download.gif" alt="Datei herunterladen" width="18" height="16" hspace="2" vspace="5" border="0" align="absmiddle">download
			</center>
			<?	
		}
		
		if ($_REQUEST['action']=="delfile") {
			$file = $_REQUEST['file'];
			$file = str_replace("..","",$file);
			$full = ".." . $file;
			
			echo "<script>";
			
			if (file_exists($full)) {
				if (@unlink($full)) {
					?>
					alert("Die Datei wurde gelöscht.");
					<?
				} else {
					?>
					alert("Konnte Datei nicht löschen (keine Rechte?).");
					<?
				}
			} else {
				?>
				alert("Datei wurde nicht gefunden.");
				<?
			}
			
			echo "</script>";
			
			$teile = explode("/", $file);
			$cnt = 0;
			while (list($key,$val) = each($teile)) {
				$cnt++;
				if ($cnt != count($teile)) {
					$pfad .= $val . "/";
				}
			}
			
			$pfad = "/" . $pfad;
			
			$_REQUEST['action'] = "dir";
			$_REQUEST['dir'] = $pfad;
		}
		
		if ($_REQUEST['action']=="mkdir") {
			$dir = $_REQUEST['dir'];
			$dir = str_replace("..", "", $dir);
			$_REQUEST['action'] = "dir";
			
			if (@mkdir(".." . $dir. $_REQUEST['newdir'])) {
				@chmod(".." . $dir. $_REQUEST['newdir'], 0777);
				?>
				<!-- <script>alert("Das Verzeichnis wurde erstellt.");</script> -->
				<?
			} else {
				?>
				<script>alert("Das Verzeichnis konnte nicht erstellt werden!");</script>
				<?
			}
		}
 
 if($_REQUEST['do']=="dir")
 {
 $dodir = $_REQUEST['dir'];
 $_REQUEST['action'] = "dir";
 $_REQUEST['dir'] = $dodir;
 }
 
 
 
 
		if ($_REQUEST['action']=="dir") {
			$dir = $_REQUEST['dir'];
			if ($dir=="") {
				$dir = "/";
			}
			
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
 	
			?>
			
	<body topmargin="0" leftmargin="0">
 	<script language="javascript">
 	<!--
 	parent.frames['unten'].location.href = 'filemanager.php?action=unten&d4sess=<?=$sessid;?>&dir=<?=$_REQUEST['dir'];?>';
 	function delfile(src)
	<? if($cmsdemo==1){ ?>{alert('In der Demo nicht verfügbar');} <?} else { ?>
	 {
 		if (window.confirm('Achtung!\n\nWollen Sie die Datei ' + src + ' wirklich löschen?   ')) {
 			parent.frames['haupt'].location.href = 'filemanager.php?action=delfile&file=' + src + '&d4sess=<?=$sessid;?>';
 		}
 	}
	<? } ?>
	
	function delfilecms(src)
	<? if($cmsdemo==1){ ?>{alert('In der Demo nicht verfügbar');} <?} else { ?>
	{
 		if (window.confirm('Achtung!\n\nWollen Sie die Datei ' + src + ' wirklich löschen?\n\nDie Löschung kann möglicherweise die Funktion des Systems einschränken.  ')) {
 			parent.frames['haupt'].location.href = 'filemanager.php?action=delfile&file=' + src + '&d4sess=<?=$sessid;?>';
 		}
 	}
	<? } ?>
	
 	function deldir(src)
	<? if($cmsdemo==1){ ?>{alert('In der Demo nicht verfügbar');} <?} else { ?>
	{
 		if (window.confirm('Achtung!\n\nWollen Sie das Verzeichnis ' + src + ' wirklich löschen?  ')) {
 			parent.frames['haupt'].location.href = 'filemanager.php?action=deldir&file=' + src + '&d4sess=<?=$sessid;?>';
 		}
	}
	<? } ?>
	

	function deldircms(src)
	<? if($cmsdemo==1){ ?>{alert('In der Demo nicht verfügbar');} <?} else { ?>
	{
 		if (window.confirm('Achtung!\n\nWollen Sie das Verzeichnis ' + src + ' wirklich löschen?\n\nDie Löschung kann möglicherweise die Funktion des Systems einschränken.  ')) {
 			parent.frames['haupt'].location.href = 'filemanager.php?action=deldir&file=' + src + '&d4sess=<?=$sessid;?>';
 		}
	}
	<? } ?>
 	//-->
 	</script>
 	<table width=100%  border="1" cellpadding="4" cellspacing="0" bordercolorlight="#DCDEE4" bordercolordark="#FFFFFF">
 		<tr bgcolor="#EAEBEE">
		<td class="boxstandart">&nbsp;</td>
 		<td class="boxstandart">
 		  <table width="100%" cellspacing="0" cellpadding="0"><tr><td>&nbsp;Name</td>
 		</tr>
 		</table></td>
 		<td class="boxstandart">&nbsp;Gr&ouml;&szlig;e</td>
 		<td class="boxstandart">&nbsp;</td>
 		</tr>
 		<?
 		if ($dir!="/") {
 			?>
 <tr><td width="19"><img src="/p4cms/gfx/tree/folder.gif" border="0"></td>
 		<td width="45%" bgcolor="#FAFAFB">&nbsp;<a href="/p4cms/filemanager.php?action=dir&d4sess=<?=$sessid;?>&dir=<?=$dir;?>../">..</a>&nbsp;&nbsp;</td>
 		<td bgcolor="#FAFAFB">&nbsp;</td>
 		<td bgcolor="#FAFAFB">&nbsp;</td>
	  </tr>				
 			<?	
 		}
 		
 		$d = @dir(".." . $dir);
 		while ($entry = $d->read()) {
 			$full = ".." . $dir . $entry;
 			$rel = $dir . $entry;
 			if (is_dir($full) && $entry != "." && $entry != "..") {
 				?>
  		<tr><td width="19"><img src="/p4cms/gfx/tree/folder.gif" border="0"></td>
 		<td width="45%" bgcolor="#FAFAFB">&nbsp;<a href="/p4cms/filemanager.php?action=dir&d4sess=<?=$sessid;?>&dir=<?=$rel;?>/"><?=$entry;?></a>&nbsp;&nbsp;</td>
 		<td bgcolor="#FAFAFB">&nbsp;</td>
 		<td bgcolor="#FAFAFB">
		

		<?
			if (substr($rel, 0, 6) == "/p4cms") {
				?>
				
				<a href="javascript:deldircms('<?=$rel;?>');"><img src="/p4cms/gfx/dialog/delete.gif" alt="löschen" border="0" align="absmiddle"></a>			
		  <?
			} else { ?>
			<a href="javascript:deldir('<?=$rel;?>');"><img src="/p4cms/gfx/dialog/delete.gif" alt="löschen" border="0" align="absmiddle"></a>			
		
			<? } ?>
			<a href="#" onClick="window.open('filecode.php?path=<?=$rel."/";?>', 'filecode', 'width=600,height=50,top=528,left=00');"><img src="/p4cms/gfx/code.png" alt="Einbindungs-Pfad anzeigen" border="0" align="absmiddle"></a>
			<a href="#" onClick="window.open('filerename.php?path_3=<? $laeng1=strlen($rel); $laeng2=strlen($entry); $sub = $laeng1-$laeng2; echo $sub2=substr("$rel",0,$sub) ?>&path_2=<?=$entry;?>&path_1=<?=$rel;?>&oldname=<?=$entry;?>&modus=dir', 'filerename', 'width=600,height=150,top=528,left=0');"><img src="/p4cms/gfx/listshow.gif" alt="umbenennen" border="0" align="absmiddle"></a></td>
  		</tr>	<?
 			}
 		}
 		$d->close();
 		$d = dir(".." . $dir);
 		while ($entry = $d->read()) {
 			$full = ".." . $dir . $entry;
 			$rel = $dir . $entry;
 			if (!is_dir($full) && $entry != "." && $entry != "..") {
 			
			$ftype = explode(".", $rel);
			if(file_exists("gfx/fileex/".$ftype[1].".gif")){$fimage="gfx/fileex/".$ftype[1].".gif";}else{$fimage="gfx/tree/page.gif";}
			?>
  		<tr><td width="19"><img src="<?=$fimage;?>" border="0"></td>
 		<td width="45%" bgcolor="#FAFAFB">&nbsp;
		<? if($cmsdemo==1){ ?>
		<a href="javascript:void(0);" onClick="alert('in der Demo nicht verfügbar');"><?=$entry;?></a>
		<? } else { ?>
		<a href="javascript:void(0);" onClick="window.open('/p4cms/file_viewer.php?path=<?=$rel;?>', 'fileviewer', 'width=600,height=500,top=100,left=100');"><?=$entry;?></a>
		&nbsp;&nbsp;
		<? } ?></td>
 		<td bgcolor="#FAFAFB">&nbsp;<?=@round(filesize($full)/1024,2);?> KB</td>
 		<td bgcolor="#FAFAFB">
		
		
		<?
		if (substr($rel, 0, 6) == "/p4cms") {
			?>
 			<a href="javascript:delfilecms('<?=$rel;?>');"><img src="/p4cms/gfx/dialog/delete.gif" alt="löschen" border="0" align="absmiddle"></a>
 			<?
		} else {
		?>
		<a href="javascript:delfile('<?=$rel;?>');"><img src="/p4cms/gfx/dialog/delete.gif" alt="löschen" border="0" align="absmiddle"></a>
 			
		<? } ?>
            <a href="javascript:void(0);" onClick="window.open('filecode.php?path=<?=$rel;?>', 'filecode', 'width=600,height=50,top=528,left=0');"><img src="/p4cms/gfx/code.png" alt="Einbindungs-Pfad anzeigen" border="0" align="absmiddle"></a>
            <a href="#" onClick="window.open('filerename.php?path=<?=$dir;?>&oldname=<?=$entry;?>', 'filerename', 'width=600,height=150,top=528,left=0');"><img src="/p4cms/gfx/listshow.gif" alt="umbenennen" border="0" align="absmiddle"></a>
			
			<? if($cmsdemo==1){ ?>
			<a href="javascript:alert('in der Demo nicht verfügbar');"><img src="/p4cms/gfx/main/download.gif" alt="Datei herunterladen" hspace="2" border="0" align="absmiddle"></a>
			<? } else { ?>
			<a href="/p4cms/filemanager.php?d4sess=<?=$sessid;?>&action=download&file=<?=$rel;?>"><img src="/p4cms/gfx/main/download.gif" alt="Datei herunterladen" hspace="2" border="0" align="absmiddle"></a>
			<? } ?>
			</td>
  		</tr>			
 				<?
 			}
 		}
 		$d->close();
 		?>
 	</table>
	<br>
	<?
			
		}	
		?>
    </body>
</html>
 		<? 		
 	}
 	
 } else {
	StyleSheet();
	$msg = "<center>Sie besitzen nicht die Rechte, diese Aktion auszuführen.</center>";
	MsgBox($msg);
}
?>