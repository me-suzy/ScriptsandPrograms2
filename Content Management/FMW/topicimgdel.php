<?php
include "header.php";

session_start();
if (($_SESSION['perm'] < "5"))  {
echo "<font color='#$col_text'>"; die ('You are not authorised to access this section');
}

$fileId = $_GET['fileId'];



$query="SELECT * FROM topic WHERE topic_id= '$fileId'";
$result=mysql_query($query);
while($row = mysql_fetch_array($result))
	{
$topic_image = $row["topic_image"];
$topic_image_name = $row["topic_image_name"];


$oldname = $row["topic_image"];


$query="SELECT * FROM news WHERE topic_image = '$oldname'";
$result2=mysql_query($query);
$num=mysql_numrows($result2);


	
$news_image = $row["topic_image"];



	if ($_POST['submit'] == 'submit') {

			mysql_query("DELETE FROM topic WHERE topic_id= $fileId ")
			or die(mysql_error());

	$getFilePath = "images/topic/" . $oldname; //location of file on server
	unlink($getFilePath);  //deletes the file on the server 

?> <meta HTTP-EQUIV="Refresh" CONTENT="0; URL=topicmanage.php"> <?php


	?>

	<?php
	}
}
	?>




<html>

<head>
<title>Topic Image Delete</title>
</head>


<body>
<center>
<font color="#<?php echo $col_text ?>">
<form method="post" action="<?php echo $_SERVER['PHP_SELF'];echo "?fileId="; echo "$fileId";?>">

You currently have <?php echo "$num"; ?> news items using this topic<br>
Confirm delete topic <?php echo "$topic_image" ?> ?
<br><br><img src="images/topic/<?php echo "$topic_image"; ?>"><br>
<br>
<input type="Submit" name="submit" value="submit">
</form>
</font>
</BODY> 
</HTML>


