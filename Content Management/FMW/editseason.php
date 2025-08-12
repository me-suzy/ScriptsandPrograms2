<?php
include "header.php";
session_start();
if (($_SESSION['perm'] < "5"))  {
echo "<font color='#$col_text'>"; die ('You are not authorised to access this section');
}
$fileId = $_GET['fileId'];

?><font color="#<?php echo $col_text ?>"><?php

$query="SELECT * FROM seasons WHERE season_id= '$fileId'";
$result=mysql_query($query);
while($row = mysql_fetch_array($result))
{
$season_name = $row["season_name"];
$season_start = $row["season_start"];
$season_end = $row["season_end"];
}


if ($_POST['Delete'] == 'Delete') {
	mysql_query("DELETE FROM seasons WHERE season_id= $fileId ")
			or die(mysql_error());



?> <meta HTTP-EQUIV="Refresh" CONTENT="0; URL=seasonmanage.php"> <?php

	}



if ($_POST['submit'] == 'submit') {
$season_name = $_POST['season_name'];
$season_start = $_POST['season_start'];
$season_end = $_POST['season_end'];


if(!$_POST['season_name']) {
	?><font color="#<?php echo "$col_text" ?>"><?php die('You must enter a Match Type name');
	}



$query="UPDATE seasons SET season_name = '$season_name', season_start='$season_start', season_end='$season_end' WHERE season_id = $fileId ";
@mysql_select_db($db_name) or die( "Unable to select database");
mysql_query($query);
?> <meta HTTP-EQUIV="Refresh" CONTENT="0; URL=seasonmanage.php"> <?php

}
?>

<br>

<html>

<head>

<title>Edit / Delete Season</title>
</head>

<body>
<form method="post" action="<?php echo $_SERVER['PHP_SELF'];echo "?fileId="; echo "$fileId";?>">



  <center>
<br><br>

Enter Season name
<br><br>

<input id="match_type" size="40" name="season_name" value="<?php echo "$season_name" ?>"><br>

Enter Season Start Date
<br><br>

<input id="match_type" size="40" name="season_start" value="<?php echo "$season_start" ?>"><br>
Enter Season End Date
<br><br>

<input id="match_type" size="40" name="season_end" value="<?php echo "$season_end" ?>"><br>
 

<?php


?>
  </center>



<center><br><input type="Submit" name="submit" value="submit">
<BR><BR><BR><BR>
Click to Delete Season
<BR>
<center><br><input type="Submit" name="Delete" value="Delete">


</body>

</html>