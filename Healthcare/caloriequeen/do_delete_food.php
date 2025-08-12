<?
//start a session
session_start();

//validate user to see if they are allowed to be here
if ($_SESSION[valid] != "yes") {
   header("Location: http://website.com/food_menu.php");
   exit;

}
//set up table and database
	$db_name = "";
	$table_name = "foodcomp";
	$connection = @mysql_connect("localhost", "", "")
		or die(mysql_error());
	$db = @mysql_select_db($db_name,$connection) or die(mysql_error());

//build and issue query
$sql = "DELETE FROM $table_name WHERE id = '$_POST[id]'";
$result = @mysql_query($sql,$connection) or die(mysql_error());
?>		
<html>

<head>
<title>Delete A Food</title>
</head>

<body>
<p>Food Deleted</p>
<br><p><a href="food_menu.php">Return to Main Menu</a></p>
</body>

</html>
