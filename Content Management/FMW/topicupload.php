<?php
include "header.php";

session_start();
if (($_SESSION['perm'] < "5"))  {
echo "<font color='#$col_text'>"; die ('You are not authorised to access this section');
}



$query="SELECT * FROM topic ";
$result=mysql_query($query);
while($row = mysql_fetch_array($result))

$oldname = $row["news_image"];




?>

<html>

<head>
<title>Topic Image Upload</title>
</head>
<?php echo "<font color='#$col_text'>"; ?>

<body>
<center>



News topic image can be no larger than 120*120 and no bigger than 20K.
<br>
(File type must be jpeg or gif)
<BR><BR>
<br>

<form name="form1" method="post" action="" enctype="multipart/form-data">

<input type="file" name="imagefile">
<br><br>

<input type="submit" name="Submit" value="Submit"> 
<BR><BR>




<?php




if(isset($_POST['Submit']))
{
//If the Submitbutton was pressed do:
$topic_name = $_POST['topic_name'];
 $imagedata = getimagesize($_FILES['imagefile']['tmp_name']);
$width = $imagedata[0];
$height = $imagedata[1]; 

?><BR><?php
$filename = "".$_FILES['imagefile']['name']."";
$dbQuery = "SELECT news_image "; 
$dbQuery .= "FROM news WHERE news_image = '$filename' "; 
$result = mysql_query($dbQuery) or die("Couldn't get file list");
$num=mysql_numrows($result);

if ("$num" >= '1') {
echo "filename already exists, please rename file before uploading";
}
else{

?><BR><BR><?php
echo "picture width "; echo "$width ";
echo "picture height "; echo "$height ";
echo "$filename";
?><BR><?php

if (($_FILES['imagefile']['size'] <= 20000) && ($width <= 120) && (height <= 120) && ($_FILES['imagefile']['type'] == 'image/jpeg' || $_FILES['imagefile']['type'] == 'image/pjpeg' || $_FILES['imagefile']['type'] == 'image/gif')) 
{

copy ($_FILES['imagefile']['tmp_name'], "images/topic/".$_FILES['imagefile']['name'])
    or die ("Could not copy"); 


$query="INSERT INTO topic (topic_image, topic_image_name)
VALUES ('$filename', '$filename')";
mysql_query($query); 

?> <meta HTTP-EQUIV="Refresh" CONTENT="0; URL=topicmanage.php"> <?php


        } 


else {
            echo "";
            echo "Could Not Copy, Wrong Filetype, filesize too big or picture dimensions too big (".$_FILES['imagefile']['name'].")";
        }
} 

}
?> </form> 


</body>



