<html>
<head>
<title>upload form</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script language="JavaScript">
function pre() {
form2.preview.src = 'thumbnails/image'+form2.choiceimage.options[form2.choiceimage.selectedIndex].value+'.jpg';
}
</script>
</head>
<body bgcolor="#000000" text="#CCCCCC" link="#FF3300" vlink="#FF3300">
 <div align="center"><font color="#FF3300" face="Arial, Helvetica, sans-serif"><strong>GALLERY
  ADMIN PAGE</strong></font><br>
</div>
<?
include("config.inc");
if ($password==$adpass) {
if ($submit=="Edit") {
echo "<script language='JavaScript'>
window.location = 'uploadform.php?i=$choiceimage&password=$password';
</script>";
}elseif ($submit=="Delete") {
for ($i=1; file_exists("images/image".strval($i).".jpg"); $i++) {
}
$num=$i-1;
function unlinkk($f) {
if (!file_exists($f)) return true;
else return unlink($f);
}
function del($i) {
if (unlinkk("images/image$i.jpg") and unlinkk("thumbnails/image$i.jpg") and unlinkk("down/image$i.zip")
and unlinkk("des/image$i.txt")) return true;
}
if (!del($choiceimage)) echo "Error";
for ($i=intval($choiceimage)+1; $i<$num; $i--) {
$j=$i-1;
if (!rename("images/image$i.jpg", "images/image$j.jpg")) $error="r";
}
if ($error=="") echo "Image$choiceimage deleted successfully";
} else {
echo "<form name=\"form2\" action=\"$PHP_SELF\" method=\"post\">
  <p align=\"center\"><font color=\"#CCCCCC\" size=\"1\" face=\"verdana\"><strong><font size=\"2\">Upload
    New Picture</font></strong></font></p>
  <p align=\"center\"><strong><font color=\"#CCCCCC\" size=\"1\" face=\"verdana\"><a href=\"uploadform.php?password=$password\">click
    here</a> </font></strong></p>
  <p align=\"center\"><strong><font color=\"#CCCCCC\" size=\"1\" face=\"verdana\">OR</font></strong></p>
  <p align=\"center\"><strong><font color=\"#CCCCCC\" size=\"1\" face=\"verdana\"><strong><font size=\"2\">EDIT
    Existing Picture</font></strong></font></strong></p>
  <p align=\"center\"><font color=\"#CCCCCC\" size=\"1\" face=\"verdana\"><strong>Image
    Select :<br>
    <table border=0><tr><td><select name=\"choiceimage\" size=10 onChange=\"pre()\">";
       for ($i=1; file_exists("thumbnails/image".strval($i).".jpg"); $i++) {
    echo "<option value=$i>Image$i</option>";
	}
    echo "</select></td>
    </strong></font></p>
  <p align=center>
    <td><img src=\"noth.gif\" border=0 name=preview></td></p></tr></table>
  <p align=\"center\"><strong><font color=\"#CCCCCC\" size=\"1\" face=\"verdana\">
    <input type=\"submit\" name=\"submit\" value=\"Edit\">
    <input type=\"hidden\" name=\"password\" value=\"$password\">
    <input type=\"submit\" name=\"submit\" value=\"Delete\">
    </font></strong> </p>
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
