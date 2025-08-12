<?php
ob_start();
//include the db connection
require("config.php");
//show the upload file form
echo "<br><br>";
echo "<form enctype=multipart/form-data method=post action=index.php?action=upload><input name=userfile type=file>&nbsp;<input type=submit value=Upload></form>";
echo "<br>";
if($_GET['action'] == 'upload')
{
//get the number value from the DB
$result = mysql_query("SELECT * FROM picphp2") 
or die(mysql_error()); 
$row = mysql_fetch_array( $result );
$number = $row['number'];
$number2 = $number + 1;
$uploaddir = 'pictures/';
$trim = str_replace(" ", "",basename($_FILES['userfile']['name']));
$uploadfile3 = strtolower($trim);
$uploadfile2 = $number2 . $uploadfile3;
$uploadfile = $uploaddir . $uploadfile2;
$name = $uploadfile2;
if(substr($name, -5, 5) == '.jpeg' || substr($name, -4, 4) == '.gif' || substr($name, -4, 4) == '.jpg' || substr($name, -4, 4) == '.png' || substr($name, -4, 4) == '.bmp')
{
if (move_uploaded_file($_FILES['userfile']['tmp_name'], $uploadfile)) 
{
   //update the number value
   $result = mysql_query("UPDATE picphp2 SET number='{$number2}'") 
   or die(mysql_error()); 

   mysql_query("INSERT INTO picphp 
   (name) VALUES('{$name}') ") 
   or die(mysql_error());
   
   //write "uploaded successfully"
   echo func("uploaded");
} 
else 
{
   echo func("error");
}
}
else
{
echo "<b>Not a valid image file</b>";
}
}
function func($action)
{
if($action == 'uploaded')
{
echo "<b>Uploaded successfully</b>";
}
if($action == 'error')
{
echo "<b>The file can not be uploaded</b>";
}
}
//show all the uploaded pictures...
echo "<br>";
$result = mysql_query("SELECT * FROM picphp") or die(mysql_error());
// keeps getting the next row until there are no more to get
$number4 = 15;
$i = 0;
while($row = mysql_fetch_array( $result )) 
{
if($number4 == $i)
{
echo "<br>";
$i = 0;
}
echo "<a href=show.php?id=" . $row['name'] . " target=_blank><img src=pictures/" . $row['name'] . " border=0 width=50 height=50></a>";
$i++;
} 
?>