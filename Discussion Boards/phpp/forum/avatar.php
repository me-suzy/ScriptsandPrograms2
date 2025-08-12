<?
extract($HTTP_POST_FILES); 
extract($HTTP_POST_VARS); 
extract($HTTP_GET_VARS); 
extract($HTTP_COOKIE_VARS); 
include "header.php";
$pagetitle = "$sitename $split $txt_editprofile ($logincookie[user])";


echo "<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>"; ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"> 
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title><? echo $pagetitle; ?></title>
<link rel="StyleSheet" href="templates/styles.css" type="text/css" media="screen"/>
<link rel="StyleSheet" href="templates/<? echo $template; ?>.css" type="text/css" media="screen"/>
</head>
<body>
<div id="container">
<?
echo "<font class=\"header\">$txt_avatarupload</font><p/>
<div id=\"central\"><div class=\"boxes\"><div class=\"boxbot\">";

if(isset($s)) {
if($s == "y") {
echo "<script type=\"text/javascript\"><!--
opener.location.reload();
window.close();
// -->
</script>";
}
else {
if(isset($u)) {
if($admincheck == 1) unlink("gfx/avatars/$u.gif");
}
else unlink("gfx/avatars/$logincookie[user].gif");
echo "<script type=\"text/javascript\"><!--
opener.location.reload();
window.close();
// -->
</script>";
}
}
elseif(isset($userfile)) {
$imagefile = "gfx/avatars/$logincookie[user].gif";
$filename = basename($_FILES['userfile']['name']);

if($_FILES['userfile']['size'] <= 0 || ($gd == "0" && $_FILES['userfile']['size'] > 10240)) $err = 1;
elseif(!@move_uploaded_file($_FILES['userfile']['tmp_name'], $imagefile)) $err = 2;

if($err == 1) echo "<font class=\"subhead\">$txt_error</font><br/>$txt_choosefile<br/>$txt_goback<p/>";
elseif($err == 2) echo "<font class=\"subhead\">$txt_error</font><br/>$txt_filenotup<br/>$txt_goback<p/>";

if($gd > 0) {
$picsize=getimagesize($imagefile); 
$source_x = $picsize[0]; 
$source_y = $picsize[1]; 
$imgtype = $picsize[2];

if($source_x > 80 || $source_y > 80) {
$jpegqual = 75; 

if($source_x > $source_y) {
$dest_x = 80;
$ratio = $source_x/$dest_x;
$dest_y = $source_y/$ratio;
}
else {
$dest_y = 80; 
$ratio = $source_y/$dest_y;
$dest_x = $source_x/$ratio; 
}

if($imgtype == "2") $source_id = imageCreatefromjpeg($imagefile); 
elseif($imgtype == "1") $source_id = imageCreatefromgif($imagefile); 
else $err = 3;
if(!isset($err)) {
if($gd == 1) {
$target_id1 = imagecreate($dest_x, $dest_y); 
$target_pic1 = imagecopyresized($target_id1,$source_id,0,0,0,0,$dest_x,$dest_y,$source_x,$source_y); 
}
elseif($gd == 2) {
$target_id1 = imagecreatetruecolor($dest_x, $dest_y); 
$target_pic1 = imagecopyresampled($target_id1,$source_id,0,0,0,0,$dest_x,$dest_y,$source_x,$source_y); 
}
imagejpeg ($target_id1,$imagefile,$jpegqual); 
}
}
}

if($err == 3) echo "<font class=\"subhead\">$txt_error</font><br/>$txt_filenotrec<br/>$txt_goback<p/>";

if(!isset($err)) {
chmod("gfx/avatars/$logincookie[user].gif", 0666);
echo "<font class=\"subhead\">$txt_avatar</font><br/><img src=\"$imagefile\" alt=\"$txt_userposted\"/><br/>
<a href=\"avatar.php?s=y\">$txt_accept</a> $split <a href=\"avatar.php?s=n\">$txt_reject</a>";
}

}
else {
if($gd == "0") echo "$avatarrules<p/>";

echo "<form method=\"post\" enctype=\"multipart/form-data\" action=\"avatar.php\">
<input type=\"file\" name=\"userfile\"/><br/>
<input type=\"submit\" value=\"$txt_avatarupload\"/>
</form>";
}
echo "<p/>
<a href=\"javascript:window.close()\">$txt_closewindow</a>
</div>
</div>
</div>
</div>
</body>
</html>";

?>