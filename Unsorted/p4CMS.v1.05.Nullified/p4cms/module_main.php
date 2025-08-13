<? ob_start();
include("include/include.inc.php");

if (!isset($HTTP_SESSION_VARS[u_loggedin]) or !($HTTP_SESSION_VARS[u_loggedin]=='yes')) {
	SessionError();
	exit;
}

 function deldir($dir){
			$current_dir = opendir($dir);
			while($entryname = readdir($current_dir)){
				if(is_dir("$dir/$entryname") and ($entryname != "." and $entryname!="..")){
					@deldir("${dir}/${entryname}");
				}elseif($entryname != "." and $entryname!=".."){
					@unlink("${dir}/${entryname}");
				}
			}
			closedir($current_dir);
			@rmdir(${dir});
 }

if ($_REQUEST['action']=="install") {
	if (isset($_FILES['modfile'])) {
		$tmpname = "temp/MOD." . time() . mt_rand(1000,99999);
		$tmpdir = $tmpname . ".DIR/";
		@mkdir($tmodir, 0777);
		@chmod($tmpdir, 0777);
		
		move_uploaded_file($_FILES['modfile']['tmp_name'], $tmpname);
		
		$zip =& new zip();
		$liste = $zip->get_List($tmpname);
		$zip->Extract($tmpname, $tmpdir);
		
		$handle = fopen($tmpdir . "modul.ini","r");
		$modname = fread($handle, filesize($tmpdir . "modul.ini"));
		fclose($handle);
		
		
		
		$moddir = "modules/$modname/";
		if (file_exists($moddir)) {
			deldir($moddir);
		}
		$old_umask = umask(0);
		@mkdir($moddir, 0777);
		@chmod($moddir, 0777);
		umask($old_umask);
		
		$zip =& new zip();
		$zip->Extract($tmpdir . "modul.zip", $moddir);
		
		$handle = fopen($tmpdir . "database.sql", "r");
		$db = fread($handle, filesize($tmpdir . "database.sql"));
		fclose($handle);
		
		$m_ok = 0;
		$m_fail = 0;
		
		mysql_connect($sql_server, $sql_user, $sql_passwort);
		mysql_select_db($sql_db);
		
		//ALTE LÖSCHEN
		$sql =& new MySQLq();
        $sql->Query("DELETE FROM " . $sql_prefix . "module WHERE name='$modname'");
                	
		
		if (ereg("\r\n", $db)) {
			$delim = "\r\n";
		} else {
			if (ereg("\n", $db)) {
				$delim = "\n";
			}
		}
		
		$ar = explode(";$delim", $db);
		while (list($key,$val) = each($ar)) {
			if (rtrim(ltrim($val)) != "") {
				$q = str_replace("\n","",$val);
				$q = str_replace("\r","",$q);
				$q = $q . ";";
				if (mysql_query($q)) {
					$m_ok++;
				} else {
					$m_fail++;
				}
			}
		}
		
		mysql_close();
		
		@unlink ($tmpname);
		
		deldir($tmpdir);
		?>
	<script language="javascript">
	<!--
	parent.frames['struktur'].location.href = parent.frames['struktur'].location.href;
	//-->
	</script>
		<?
	}
}
?>
<html>
<head>
<link rel="stylesheet" href="style/style.css">
 <? StyleSheet(); ?>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1251"></head>
<link rel="stylesheet" href="include/dynCalendar.css" type="text/css" media="screen">
<script src="include/kalender.js" type="text/javascript" language="javascript"></script>
<body bgcolor="" background="/p4cms/gfx/main/bgbody.gif">
<table width=100%  border="1" cellpadding="4" cellspacing="0" bordercolorlight="#DCDEE4" bordercolordark="#FFFFFF">

<tr>
                <td height="17" class="boxheader">Titel</td>
  </tr>
                <?
                $sql2 =& new MySQLq();
                $sql2->Query("SELECT * FROM " . $sql_prefix . "module ORDER BY titel ASC");
                while ($row2 = $sql2->FetchRow()) {
                		?>
                		<tr>
                		<td height="17" bgcolor="#FAFAFB"><?=$row2->titel;?></td>
						</tr>
                		<?
                }
                $sql2->Close();
                ?>
</table>
                <br></td>
            </tr>
            </table>
            <br>
<table width=100%  border="1" cellpadding="4" cellspacing="0" bordercolorlight="#DCDEE4" bordercolordark="#FFFFFF">

         <tr> 
                <td align="left" valign="top" bgcolor="#FAFAFB">
				<script type="text/javascript" language="JavaScript">
<!--
function checks() {
  if (document.uploadform.elements[0].value != "") {
    var ext1 = document.uploadform.elements[0].value;
    ext1 = ext1.substring(ext1.length-3,ext1.length);
    ext1 = ext1.toLowerCase();
    if(ext1 != 'p4m') { 
      alert('Sie haben eine .'+ext1+' Datei gewählt, erlaubt sind nur *.p4m - Dateien!');
      return false; 
    }
	
  }
   if(document.uploadform.elements[0].value == "") { 
	 alert('Bitte geben Sie den Speicherort an.');
      return false; 
	 }
	 
	 document.all.subm.disabled = true;
	 document.all.subm.value = 'Bitte warten...';
}
//-->
</script>
                <form name="uploadform" style="display:inline;" action="" method="post" enctype="multipart/form-data" onSubmit="return checks();">
				<center>Modul installieren (*.p4m - Datei):
				<input name="modfile" type="file" size="25" value="none"> 
				<input type="hidden" name="action" value="install">
                <input type="submit" value=" Modul instalieren  " class="button" name="subm">
                </center></form>
           </td>
  </tr></table>