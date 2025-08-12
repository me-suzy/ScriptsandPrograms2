<?php 
/***************************************\
|					|
|	    100janCMS v1.01		|
|    ------------------------------	|
|    Supplied & Nulled by GTT '2004	|
|					|
\***************************************/
// ******************************************
//   User autorization / Restrict access
// ******************************************

//database connection
include 'config_connection.php';

//start session
session_start();

//session_register("auth_ok_session");
//session_register("current_user_username");

$array = unserialize(CONSTANT_ARRAY);


//load user_privileges
$query="SELECT * FROM ".$db_table_prefix."users WHERE username='".$_SESSION["current_user_username"]."'";
$result=mysql_query($query);
$row = mysql_fetch_array($result); //wich row

$user_privileges=$row["user_privileges"];


for ($i=0;$i<count($array);$i++) {
	if (substr_count($user_privileges, $array[$i])<>0) {$does_exists=1;}
}



if (($_SESSION["auth_ok_session"] <> "1") or ($does_exists<>1)) //denie access
{ 
	//load absolute root url
	$query="SELECT config_value FROM ".$db_table_prefix."config WHERE config_name='app_url'";
	$result=mysql_query($query);
	$app_url=mysql_result($result,0,"config_value");
		
		header("Location: ".$app_url."access_denied.php");
		die;
}

?>