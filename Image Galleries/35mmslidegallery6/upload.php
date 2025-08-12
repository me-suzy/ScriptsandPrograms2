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


if ($_REQUEST['submitted2'])
{
umask (0);
mkdir ("$abpath/$newdir", 0755);
chmod ("$abpath/$newdir",0777);
$file = fopen("$abpath/$newdir/album.txt","w");
chmod ("$abpath/$newdir/album.txt",0777);
fwrite($file,"$album");
fclose($file);
}

if ($_REQUEST['submitted']){ // Begin processing portion of script


$log = "";

for ($i=0; $i<$number_of_uploads; $i++) {
$j=$i + 1;


if($select =="Choose. . ."){
$log .= "Please Choose Upload Directory<br>";
} else {

	//checks if file exists
	if ($img_name[$i] == "") {
		$log .= "No file selected for upload $j<br>";
	}

	if ($img_name[$i] != "") {
		//checks if file exists
		if (file_exists("$abpath/$select/$img_name[$i]")) {
			$log .= "File $j already existed<br>";
		} else {

			//checks if files to big
			if (($sizelim == "yes") && ($img_size[$i] > $size)) {
				$log .= "File $j was too big<br>";
			} else {


				//Checks if file is an image
				if( (substr($img_name[$i],-3)=="gif") || (substr($img_name[$i],-3)=="jpg")  || (substr($img_name[$i],-3)=="JPG") || (substr($img_name[$i],-3)=="txt") || (substr($img_name[$i],-3)=="TXT") || (substr($img_name[$i],-3)=="GIF") ) {
					@copy($img[$i], "$abpath/$select/$img_name[$i]") or $log .= "Couldn't copy image 1 to server<br>";
$file = fopen("$abpath/$select/$img_name[$i].txt","w");
chmod ("$abpath/$select/$img_name[$i].txt",0777);
fwrite($file,"$comnt[$i]");
fclose($file);

					if (file_exists("$abpath/$select/$img_name[$i]")) {
						$log .= "File $j was uploaded<br>";
					}
					} else {
						$log .= "File $j is not an image or text file<br>";
					}
				}
			}
		}

}
	
}
?>

<html>
<head>
<title>Image Report</title>
</head>
<body>
<p>Log:<br>
<?

echo "$log";

?>
</p>
<a href="upload.php">back</a>
<body>
</html>
<? 
exit;
} // End processing portion of script
?>

<html>
<head>
       <title>35mm Slide Gallery - Upload Module</title>
<link rel="stylesheet" type="text/css" href="gallery.css">
</head>
<body>
<div align="right"><font size="1">powered by <a href="http://www.andymack.com/freescripts/">35mm 
  Slide Gallery</a></font></div>
<form method=POST action=upload.php enctype=multipart/form-data>
<input type="hidden" name="submitted2" value="true">
<b>Create New Album: </b><input type="text" name="newdir">
<br><b>Album Description: </b> <input type="text" name=album size=20>
<input type="submit" name="submit" value="Create"> 
</form>
<form method=POST action=upload.php enctype=multipart/form-data>
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
print "<hr align='left' width='400'><br>";
print "<b>Upload to:</b> <select name=\"select\">";
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
<p><b>Files to upload:</b><br>
<blockquote>
<?php

for ($j=0; $j<$number_of_uploads; $j++) {
?>
<input type=file name=img[] size=30> <br><b>Caption</b> <input type="text" name=comnt[] size=30><p>
<?
}
?>
<br> 
<input type="hidden" name="submitted" value="true">
<input type="submit" name="submit" value="Upload"> 
</form>
</blockquote>
go to <a href="index.php">gallery</a>
</body>
</html>



