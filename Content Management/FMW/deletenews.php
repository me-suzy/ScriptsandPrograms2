<?php
include "header.php";

session_start();
if (($_SESSION['perm'] < "3"))  {
echo "<font color='#$col_text'>"; die ('You are not authorised to access this section');
}

$fileId = $_GET['fileId'];


$query="SELECT * FROM news WHERE news_id = '$fileId'";
$result=mysql_query($query);
while($row = mysql_fetch_array($result)) {
$news_title = $row["news_title"];

$oldname = $row["news_image"];

}

if ($_POST['Delete'] == 'Delete') {
$news_id = $_POST['news_id'];
$oldname = $_POST['image_name'];


$getFilePath = "images/news/" . $oldname; //location of file on server
unlink($getFilePath);  //deletes the file on the server


mysql_query("DELETE FROM news WHERE news_id='$news_id'")
or die(mysql_error());

?><meta HTTP-EQUIV="Refresh" CONTENT="0; URL=main.php"><?php 

}
?>




<HTML>
<HEAD>
<TITLE>Delete News Post</TITLE>
</HEAD>
<BODY>
<h3><?php echo "<font color='#$col_text'>"; ?>Delete Record</h3>
<p>Delete record with title:<br> <?php echo"$news_title"; ?>

<form method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">

<input type="hidden" name="news_id" value="<? echo $fileId; ?>">
<input type="hidden" name="image_name" value="<? echo $oldname; ?>">

Confirm Delete Record ?<br>
<input type="Submit" name="Delete" value="Delete">


</BODY> 
</HTML>