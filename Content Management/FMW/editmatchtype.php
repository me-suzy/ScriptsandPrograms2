<?php
include "header.php";
session_start();
if (($_SESSION['perm'] < "5"))  {
echo "<font color='#$col_text'>"; die ('You are not authorised to access this section');
}
$fileId = $_GET['fileId'];

?><font color="#<?php echo $col_text ?>"><?php

$query="SELECT * FROM matchtypes WHERE matchtype_id= '$fileId'";
$result=mysql_query($query);
while($row = mysql_fetch_array($result))
{
$match_type = $row["matchtype"];

}

$dbQuery = "SELECT match_type "; 
$dbQuery .= "FROM fixtures WHERE match_type = '$match_type' "; 
$result2 = mysql_query($dbQuery) or die("Couldn't get file list");
$num=mysql_numrows($result2);


if ($num > '0') {
	die ('You currently have matches assigned to this matchtype, you must create a new match type');
}

if ($_POST['Delete'] == 'Delete') {
	mysql_query("DELETE FROM matchtypes WHERE matchtype_id= $fileId ")
			or die(mysql_error());



?> <meta HTTP-EQUIV="Refresh" CONTENT="0; URL=matchtype.php"> <?php

	}



if ($_POST['submit'] == 'submit') {
$match_type = $_POST['match_type'];
if(!$_POST['match_type']) {
	?><font color="#<?php echo "$col_text" ?>"><?php die('You must enter a Match Type name');
	}



$query="UPDATE matchtypes SET matchtype = '$match_type', match_cat='$match_type' WHERE matchtype_id = $fileId ";
@mysql_select_db($db_name) or die( "Unable to select database");
mysql_query($query);
?> <meta HTTP-EQUIV="Refresh" CONTENT="0; URL=matchtype.php"> <?php

}
?>

<br>

<html>

<head>

<title>Member Role</title>
</head>

<body>
<form method="post" action="<?php echo $_SERVER['PHP_SELF'];echo "?fileId="; echo "$fileId";?>">



  <center>
<br><br>

Enter New Match Type
<br><br>

<input id="match_type" size="40" name="match_type" value="<?php echo "$match_type" ?>"><br>


 

<?php


?>
  </center>



<center><br><input type="Submit" name="submit" value="submit">
<BR><BR><BR><BR>
Click to Delete Match Type
<BR>
<center><br><input type="Submit" name="Delete" value="Delete">


</body>

</html>