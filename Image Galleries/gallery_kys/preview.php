<html>

<head>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
<title>images</title>
</head>
<body text="#FFFFFF" bgcolor="#000000" link=#ffcc00 vlink=#ffcc00 alink=#ffcc00><font face=Verdana>
<?
if ($i=="") $i="1";
if (!file_exists("images/image$i.jpg")) die("No images uploaded !!");
$size=floor(filesize("images/image".$i.".jpg")/1024);
$file="des/image$i.txt";
///
$fp=fopen($file, "r");
$w=fread($fp, filesize($file));
for ($j=1; $j<100; $j++) {
$w=stripslashes($w);
}
parse_str($w);
$name=stripslashes($name);
$des=stripslashes($des);
$code=stripslashes($code);
$author=stripslashes($author);
fclose($fp);
///
$a=getimagesize("images/image".$i.".jpg");
echo "<center><h4>$name</h4><br>
<img border=0 src=images/image$i.jpg>
<br><b>Size : </b> $size KB
<br><b>Height : </b> $a[1] <b>Width :</b> $a[0]
<br><b>Description : </b> $des
<br><b>Date : </b> $date
<br><b>Author : </b> $author&nbsp;&nbsp;<a href=mailto:$mail><img src=mail.gif border=0></a> <a href=$web><img src=web.gif border=0></a>
<br><b>Price : </b> $price";
if ($code<>"") echo "<br><br>$code";
echo "</center>";
if (file_exists("down/image$i.zip")) {
$sized=floor(filesize("down/image$i.zip")/1024);
echo "<center>
<br><a href=download.php?i=$i>Download</a>
<br><b>Download size : </b> $sized KB
<br><b>Number Of Downloads : </b> $counter
</center>";
}
?></font><center><font size=1 face=verdana> <br><br><br><br><a href=http://www.kyscorp.tk>Kyscorp.tk</a> © 2000-2003 Kys Gallery 1.0
</font></center>
<style>
BODY { scrollbar-face-color: "#000000"; scrollbar-arrow-color: "#000000"; scrollbar-track-color: "#000000"; scrollbar-3dlight-color:"#333333"; scrollbar-darkshadow-color: "#333333"; }
</style></body>

</html>
