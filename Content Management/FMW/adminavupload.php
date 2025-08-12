<?php
include "header.php";
session_start();
if (($_SESSION['perm'] < "5"))  {
echo "<font color='#$col_text'>"; die ('You are not authorised to access this section');
}
$fileId = $_GET['fileId'];
$dbQuery = "SELECT * "; 

$dbQuery .= "FROM users WHERE id = '$fileId' "; 
$result = mysql_query($dbQuery) or die("Couldn't get file list");
while($row = mysql_fetch_array($result))

$oldname = $row["avatar"];



?>

<html>

<head>
<title>Avatar Upload</title>
</head>
<?php echo "<font color='#$col_text'>"; ?>

<body>
<center>
Current Avatar:
<BR>
<p align="center"><img src="images/avatar/<?php echo "$oldname" ?>">
<BR>
Avatar can be no larger than 300*160 and no bigger than 80K.
<br>
(File type must be jpeg or gif)
<BR><BR>
<form name="form1" method="post" action="" enctype="multipart/form-data">

<input type="file" name="imagefile">
<br><br>
<input type="submit" name="Submit" value="Submit"> 


<?
if(isset($_POST['Submit']))
{
//If the Submitbutton was pressed do:

 $imagedata = getimagesize($_FILES['imagefile']['tmp_name']);
$width = $imagedata[0];
$height = $imagedata[1]; 

?><BR><?php
$filename = "".$_FILES['imagefile']['name']."";
$dbQuery = "SELECT avatar "; 
$dbQuery .= "FROM users WHERE avatar = '$filename' "; 
$result = mysql_query($dbQuery) or die("Couldn't get file list");
$num=mysql_numrows($result);

if ("$num" >= '1') {
echo "filename already exists, please rename file before uploading";
}
else{

?><BR><BR><?php
echo "picture width "; echo "$width ";
echo "picture height "; echo "$height ";
?><BR><?php

if (($_FILES['imagefile']['size'] <= 80000) && ($width <= 300) && (height <= 160) && ($_FILES['imagefile']['type'] == 'image/jpeg' || $_FILES['imagefile']['type'] == 'image/pjpeg' || $_FILES['imagefile']['type'] == 'image/gif')) 
{

copy ($_FILES['imagefile']['tmp_name'], "images/avatar/".$_FILES['imagefile']['name'])
    or die ("Could not copy"); 



$query = "UPDATE users SET avatar = '$filename' WHERE id = $fileId ";
mysql_query($query); 

if ($oldname != 'no_pic.gif'){

$getFilePath = "images/avatar/" . $oldname; //location of file on server
unlink($getFilePath);  //deletes the file on the server 
}

?> <meta HTTP-EQUIV="Refresh" CONTENT="0; URL=memberedit.php"> <?php

        } 


else {
            echo "";
            echo "Could Not Copy, Wrong Filetype, filesize too big or picture dimensions too big (".$_FILES['imagefile']['name'].")";
        }
} 

}
?> </form> 


</body>