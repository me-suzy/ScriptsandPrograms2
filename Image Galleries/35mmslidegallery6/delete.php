<?php

require('config.php');

function error ($error_message) {
	echo $error_message."<BR>";
	exit;
}

if ( (!isset($PHP_AUTH_USER)) || ! (($PHP_AUTH_USER == $LOGIN) && ( $PHP_AUTH_PW == "$PASSWORD" )) ) {
	header("WWW-Authenticate: Basic entrer=\"Form2txt admin\"");
	header("HTTP/1.0 401 Unauthorized");
	error("Unauthorized access...");
}



function removedir ($dirb)
{
$dh=opendir($dirb);
while ($file=readdir($dh))
{
if($file!="." && $file!="..")
{
$fullpath=$dirb."/".$file;
if(!is_dir($fullpath))
{
unlink($fullpath);
}else{
removedir($fullpath);
}
}
}
closedir($dh);
if(rmdir($dirb))
{
print "Directory:<font color='#FFCC00'><b>$dirb</b></font> deleted.<p>";
return true;
}else{
return false;
}
}

if ($_REQUEST['submitted'])
{
$dirc= "$abpath/$select";
removedir ($dirc);
}


?>
<html>
<head>
       <title>35mm Slide Gallery - Delete Module</title>
<link rel="stylesheet" type="text/css" href="gallery.css">
</head>
<body>
<div align="right"><font size="1">powered by <a href="http://www.andymack.com/freescripts/">35mm 
  Slide Gallery</a></font></div>

<form method=POST action=delete.php enctype=multipart/form-data>
<?php
$dh = opendir($dir);
 while($file = readdir($dh))
 {
if ($file != "." && $file != ".." && is_dir($file))   
{$dname[] = $file;
sort($dname);
reset ($dname);
 }
}
print "<font color='#FFCC00'>Warning! It will erase the whole directory without further notification!</font><br><br>";
print "<b>DELETE:</b> <select name=\"select\">";
print "<option value=\"#\">Choose. . .</option><br>\n";
$u=0;
 foreach($dname as $key=>$val)
  {  if($dname[$u])   
{ print "<option value=\"$dname[$u]\">$dname[$u]</option>\n";
$u++;
}
}
print "</select>";

?>

 
<input type="hidden" name="submitted" value="true">
<input type="submit" name="submit" value="Delete"> 
</form>
go to <a href="upload.php">upload</a> | go to <a href="index.php">gallery</a>
</body>
</html>
