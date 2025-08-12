<?php
include "header.php";
session_start();
	if (($_SESSION['perm'] < "5"))  {
	echo "<font color='#$col_text'>"; die ('You are not authorised to access this section');
}

$fileId = $_GET['fileId'];

?><font color="#<?php echo $col_text ?>"><?php

$query="SELECT * FROM topic WHERE topic_id= '$fileId'";
$result=mysql_query($query);
while($row = mysql_fetch_array($result))
	{
$topic_image = $row["topic_image"];
$topic_image_name = $row["topic_image_name"];


}

	if ($_POST['submit'] == 'submit') {

		if(!$_POST['topic_image_name']) {
		?><font color="#<?php echo "$col_text" ?>"><?php die('You must enter a name');
			}


$topic_image_name = $_POST['topic_image_name'];
$query="UPDATE topic SET topic_image_name = '$topic_image_name' WHERE topic_id = $fileId ";
@mysql_select_db($db_name) or die( "Unable to select database");
mysql_query($query);
?> <meta HTTP-EQUIV="Refresh" CONTENT="0; URL=topicmanage.php"> <?php

	}
?>

<br>

<html>

<head>

<title>Edit Order</title>
</head>

<body>
<form method="post" action="<?php echo $_SERVER['PHP_SELF'];echo "?fileId="; echo "$fileId";?>">

Enter new name for image
<br><br>
<img src="images/topic/<?php echo "$topic_image"; ?>"><br>
<br>
<input id="topic_image_name" size="25" name="topic_image_name" value="<?php echo "$topic_image_name" ?>"><br>

  </center>

<center><br><input type="Submit" name="submit" value="submit">

</body>

</html>