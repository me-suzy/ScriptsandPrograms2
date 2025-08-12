<html>
<head>
<title>upload form</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script language="JavaScript" type="text/JavaScript">
function validate(){
if (form1.name.value=="" | form1.author.value=="" | form1.des.value=="" | form1.image.value=="" | form1.thumb.value=="")  {
alert("Name, author, File Source, Description and Thumbnail fields are required");
return false;
} else if((form1.web.value!="") & (form1.web.value.indexOf("http://")!=0)) {
alert("the web address must begin with 'http://'");
return false;
} else {
return true;
form1.submit();
}
}
</script>
</head>
<body bgcolor="#000000" text="#CCCCCC" link="#FF3300" vlink="#FF3300">
 <div align="center"><font color="#FF3300" face="Arial, Helvetica, sans-serif"><strong>GALLERY
  ADMIN PAGE</strong></font><br>
</div>
<? include("config.inc");
if ($password==$adpass) {
if ($action=="save") {
if ($i=="") for ($i=1; file_exists("images/image".strval($i).".jpg"); $i++) {
}
$file ="des/image$i.txt";
$fp =fopen($file, "w+");
$name=stripslashes($name);
$des=stripslashes($des);
$code=stripslashes($code);
$author=stripslashes($author);
$w ="name=".$name."&price=".$price."&code=".$code."&des=".$des."&author=".$author."&mail=".$mail."&date=".$date."&web=".$web;
for ($ii=1; $ii<100; $ii++) {
$w=stripcslashes($w);
}
if ($down<>"") $w = $w."&counter=0";
fwrite($fp, $w);
fclose($fp);
if (copy($thumb, "thumbnails/image".strval($i).".jpg"))  echo "Thumbnail copied<br>";
if (copy($image, "images/image".strval($i).".jpg"))  echo "Image copied<br>";
if (copy($down, "down/image".strval($i).".zip"))  echo "Download File copied";
} else {
$file="des/image$i.txt";
///
if (file_exists("des/image$i.txt")) {
$fp=fopen($file, "r");
$w=fread($fp, filesize($file));
for ($j=1; $j<10; $j++) {
$w=stripcslashes($w);
}
parse_str($w);
$name=stripslashes($name);
$des=stripslashes($des);
$code=stripslashes($code);
$author=stripslashes($author);
fclose($fp);
}
echo "<form method=\"post\" onsubmit=\"return validate();\" enctype=\"multipart/form-data\" name=\"form1\" action=\"$PHP_SELF\">
  <p> <font size=\"1\" face=\"verdana\">File Source:
    <input name=\"image\" type=\"file\" id=\"image\" value='images/image$i.jpg'>
    <input name=\"i\" type=\"hidden\" id=\"i\" value=$i>
    </font></p>
  <p><font size=\"1\" face=\"verdana\"> Name:
    <input name=\"name\" type=\"text\" id=\"name\" value='$name'>
    </font></p>
  <p><font size=\"1\" face=\"verdana\">Thumbnail:
    <input name=\"thumb\" type=\"file\" id=\"thumb\" value='thumbnails/image$i.jpg'>
    </font></p>
  <p><font size=\"1\" face=\"verdana\">Download File: (Not required)
    <input name=\"down\" type=\"file\" id=\"down\" value='down/image$i.jpg'>
    </font></p>
  <p><font size=\"1\" face=\"verdana\">Date:
    <input name=\"date\" type=\"text\" id=\"date\" value='$date'>
    <input name=\"action\" type=\"hidden\" id=\"action\" value=\"save\">
    <input name=\"password\" type=\"hidden\" id=\"action\" value=\"$password\">
    </font></p>
  <p><font size=\"1\" face=\"verdana\">Author:
    <input name=\"author\" type=\"text\" id=\"author\" value='$author'><br><br>
	Author Web: <input name=\"web\" type=\"text\" id=\"web\" value='$web'>
    </font></p>
  <p><font size=\"1\" face=\"verdana\">E-mail:
    <input name=\"mail\" type=\"text\" id=\"mail\" value='$mail'><br><br>Price :
	<input name=\"price\" type=\"text\" id=\"price\" value=\"$price\">
    </font></p>
  <p><font size=\"1\" face=\"verdana\">Description:</font></p>
  <p> <font size=\"1\" face=\"verdana\">
    <textarea name=\"des\" id=\"des\">$des</textarea>
    </font></p>
	  <p><font size=\"1\" face=\"verdana\">Paypal HTML Code:</font></p>
  <p> <font size=\"1\" face=\"verdana\">
    <textarea name=\"code\" id=\"code\">$code</textarea>
    </font></p>
  <p align=\"center\">&nbsp; </p>
  <p align=\"center\"> <font size=\"1\" face=\"verdana\">
    <input type=\"submit\" name=\"Submit\" value=\"Submit\">
    </font></p>
</form>";

}
} else echo "<form name=form1 action=$PHP_SELF method=post>Password : <input type=\"password\" name=\"password\" size=20>
<input type=submit name=submit value=OK>
</form>";
?>
<div align="center"><br><font color="#CCCCCC" size="1" face="verdana"><a href="index.php">Go To Gallery</a><br>
  <br>&copy;Kyscorp.tk -2000-2003- Kys
  Gallery 1.0
  </font></div>
<style>
BODY { scrollbar-face-color: "#000000"; scrollbar-arrow-color: "#000000"; scrollbar-track-color: "#000000"; scrollbar-3dlight-color:"#333333"; scrollbar-darkshadow-color: "#333333"; }
</style></body>
</html>
