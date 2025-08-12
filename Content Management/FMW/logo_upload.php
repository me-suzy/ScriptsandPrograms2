<?php
require('db_connect.php');

$dbQuery = "SELECT rights "; 

$dbQuery .= "FROM users WHERE username = ('$_SESSION[username]')"; 
$result = mysql_query($dbQuery) or die("Couldn't get file list");
while($row = mysql_fetch_array($result))


$rights = $row["rights"];

if ("$rights" < '5') {
echo "<font color='#$col_text'>"; die ('You are not authorised to access this section');
}
?>

<HEAD>
<TITLE>Upload Logo</TITLE>
</HEAD>
<BODY>
<center>
<p><font color="#000000"></font></p>
<body bgcolor="#b3b3cc">
<tr><th colspan="4">Current Logo <br> <p align="center"><img src="images/logo.gif"></th></tr>


<form name="form1" method="post" action="" enctype="multipart/form-data">
<input type="file" name="imagefile">
<input type="submit" name="Submit" value="Submit"> 
<br><br>
<p><center>Click 'Browse' to upload gif or jpg file (max size 100k)</p>
<br><br>
<a href="configuration.php"><font color="000000">Click to return to Configuration screen</font></a> 

<?php
if(isset($_POST['Submit']))
{
//If the Submitbutton was pressed do:


if (($_FILES['imagefile']['size'] <= 100000) && ($_FILES['imagefile']['type'] == 'image/gif' || $_FILES['imagefile']['type'] == 'image/pjpeg' || $_FILES['imagefile']['type'] == 'image/jpeg'))
{

copy ($_FILES['imagefile']['tmp_name'], "images/logo.gif")
    or die ("Could not copy"); 
?> <meta HTTP-EQUIV="Refresh" CONTENT="0; URL=logo_upload.php"> <?php


echo "";
        echo "Name: ".$_FILES['imagefile']['name']."";
        echo "Size: ".$_FILES['imagefile']['size']."";
        echo "Type: ".$_FILES['imagefile']['type']."";
        echo "Copy Done....";

        } 


else {
?> <br><br> 
<?php
            echo "";
            echo "Could Not Copy, Wrong Filetype or file too big (".$_FILES['imagefile']['name'].")";
        }
} 


?> 
</form>
</BODY> 