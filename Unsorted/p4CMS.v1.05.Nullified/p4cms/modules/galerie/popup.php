<?
include("../../include/config.inc.php");
include("../../include/mysql-class.inc.php");
include("../../include/functions.inc.php");

$sql =& new MySQLq();
$sql->Query("SELECT * FROM " . $sql_prefix . "galerien_bilder WHERE id='$_REQUEST[id]'");
while ($row = $sql->FetchRow()) {
	$bild = base64_decode($row->bild);
	$tit = $row->titel;
	$fn = "../../temp/" . time() . mt_rand(1000,99999) . ".tmp";
	$handle = fopen($fn, "w+");
	fwrite($handle, $bild);
	fclose($handle);
	
	$bild = imagecreatefromjpeg($fn);
	
	$w = imagesx($bild);
	$h = imagesy($bild);
	
	@unlink($fn);
}
$sql->Close();
?>
<html>
<head>
<title>Galerie (<?=$tit;?>)</title>
</head>
<body onLoad="self.resizeTo(<?=$w+12;?>,<?=$h+31;?>);self.moveTo((screen.width/2) - <?=($w+12)/2;?>,(screen.height/2) - <?=($h+31)/2;?>);" topmargin="0" leftmargin="0">
<a href="javascript:window.close();"><img src="bild.php?id=<?=$_REQUEST[id];?>" border="0" id="<?=$_REQUEST[id];?>" name="<?=$_REQUEST[id];?>"></a>
</body>
</html>