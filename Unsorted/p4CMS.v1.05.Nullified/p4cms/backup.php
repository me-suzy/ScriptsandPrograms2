<?
 include("include/include.inc.php");
 
 if (!isset($HTTP_SESSION_VARS[u_loggedin]) or !($HTTP_SESSION_VARS[u_loggedin]=='yes')) {
  SessionError();
  exit;
 }
 
 $i = 0;
 
 function AddZip($dir, $node, &$zip) {
	global $i;
	if (!isset($i)) {
		$i = 1;
	}
	$d = dir($dir);
	while (false !== ($entry = $d->read())) {
		if (!($entry==".") and !($entry=="..") and !($entry=="p4cms")) {
			if (is_dir($dir . $entry)) {
				$i++;
				AddZip($dir . $entry . "/", $i, $zip);
			} else {
				$i++;
				$zip->AddFile(CNTFile($dir . $entry), str_replace("../", "", $dir . $entry));
			}
		}
	}
	$d->close();
}
 
 if ($_REQUEST['action']=="" or !isset($_REQUEST['action'])) {
 	?>
 	<html>
 	<head>
 	 <title>Einen Moment...</title>
	 <meta http-equiv="refresh" content="5; URL=javascript:DLExit('<? echo($sessid); ?>','<? echo($_REQUEST[kopie]); ?>','<?=$_REQUEST[gal];?>');">
     <? StyleSheet(); ?>
	 <link rel="stylesheet" href="style/style.css">
 	</head>
 	<body  class="boxstandart" topmargin="0" leftmargin="0"> 
 	<center><img src="gfx/export/export.gif" border="0"><br>
 	Ein Seiten/Datenbank-Backup wird erstellt...</center>
 	</body>
 	</html>
 	<?
 } else {
 	$dat = date("dmYHis");
 	$fname = "p4cms_backup_$dat.zip";
 	$zname = "p4cms_backup_$dat.sql";
 	$mname = "p4cms_backup_${dat}_mediapool.zip";
 	
 	$htmlfiles =& new zipfile();
 	AddZip("../media/", 0, $htmlfiles);
 	
 	$mediapool =& new zipfile();
 	AddZip("media/", 0, $mediapool);
 	
 	$backup =& new zipfile();
 	$backup->AddFile($mediapool->File(), "mediapool.zip");
 	$backup->AddFile($htmlfiles->File(), "rootmedia.zip");
 	
 	$verbindung = mysql_connect($sql_server, $sql_user, $sql_passwort);
	mysql_select_db($sql_db);
	
	$backup->AddFile(MySQLDump(), "database.sql");
	mysql_close($verbindung);
	
	$handle = fopen("include/backup_readme.txt", "r");
	$liesmich = fread($handle, filesize("include/backup_readme.txt"));
	fclose($handle);
	
	$liesmich = str_replace("{datum}", date("d.m.Y H:i:s"), $liesmich);
	$liesmich = str_replace("{dateiname}", $fname, $liesmich);
	
	$backup->AddFile($liesmich, "backup.txt");

 	$zipfl = $backup->File();
 	
	if ($_REQUEST[kopie]=="ja") {
		$datei = fopen("archive/" . $fname, "w");
		fwrite($datei, $zipfl);
		fclose($datei);
	}
	
	eLog("user", "Backup erstellt von $_SESSION[u_user]");
	
	header("Cache-control: private");
	header("Content-type: application/zip"); 
	header("Content-length: " . strlen($zipfl));
	header("Content-disposition:attachment; filename=$fname");
 	echo ($zipfl);	
 }
?>