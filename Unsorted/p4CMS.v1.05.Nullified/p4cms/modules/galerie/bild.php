<?
 include("../../include/config.inc.php"); 
 include("../../include/mysql-class.inc.php");
 include("../../include/functions.inc.php");

 $sql =& new MySQLq();
 $sql->Query("SELECT * FROM " . $sql_prefix . "galerien_bilder WHERE id='$_REQUEST[id]'");
 
if ($_REQUEST['modus']=="thumb") {
 header("Content-Type: image/jpeg");
 }
 
 while ($row = $sql->FetchRow()) {
 	$bild = base64_decode($row->bild);
 	if ($_REQUEST['modus']=="thumb") {
 		$sql2 =& new MySQLq();
 		$sql2->Query("SELECT * FROM " . $sql_prefix . "galerien WHERE id='$row->gallerie'");
 		$row2 = $sql2->FetchRow();
 		$w = $row2->w;
 		$h = $row2->h;
 		$sql2->Close();
 		
 		$fn = "../../temp/" . time() . mt_rand(1000,99999) . ".tmp";
 		$handle = fopen($fn, "w+");
 		fwrite($handle, $bild);
 		fclose($handle);
 		
 		$quelle = imagecreatefromjpeg($fn);
 		
 		if (function_exists('imagecreatetruecolor')) {
 			$ziel = imagecreatetruecolor($w, $h);
 		} else {
 			$ziel = imagecreate($w, $h);
 		}
 		
 		imagecopyresized($ziel, $quelle, 0, 0, 0, 0, $w, $h, imagesx($quelle), imagesy($quelle));
 		
 		$bild = imagejpeg($ziel,"",100);
 		
 		@unlink($fn);
 	}
 	
 	
 	
 	header("Content-Length: " . strlen($bild));
 	header("Content-Type: image/jpeg");
 	header("Content-Disposition: attachment; filename=\"thumb.jpg\"");
 	echo $bild;
 	exit;
 }
 $sql->Close();
?>