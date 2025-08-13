<?
include("inc/config.php");
$connection = mysql_connect($hostname, $user, $pass) or die ("Unable to connect!");
$query = "SELECT * FROM clients WHERE name = '$name' AND password = PASSWORD('$password')";
$result = mysql_db_query($database, $query, $connection);
if (mysql_num_rows($result) == 1)
	{
	session_start();

	session_register("client_id");
	session_register("client_name");
	session_register("client_email");
	session_register("client_ref");
	session_register("client_title");
	list($clientid, $name, $pass, $email, $ref, $title) = mysql_fetch_row($result);
	$client_id = $clientid;
	$client_name = $name;
	$client_email = $email;
	$client_ref = $ref;
	$client_title = $title;
	
	header("Location: menu.php");
	mysql_free_result ($result);	

	mysql_close($connection);
	}
else

	{
	mysql_free_result ($result);	
	mysql_close($connection);

	header("Location: index.htm");
	exit;
	}
?>
