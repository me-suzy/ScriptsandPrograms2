<?php
/***************************************\
|					|
|	    100janCMS v1.01		|
|    ------------------------------	|
|    Supplied & Nulled by GTT '2004	|
|					|
\***************************************/
// do not cache
header ("Expires: Mon, 26 Jul 1997 05:00:00 GMT");    	        // Date in the past
header ("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); // always modified
header ("Cache-Control: no-cache, must-revalidate");  	        // HTTP/1.1
header ("Pragma: no-cache");                          	        // HTTP/1.0
 
// Restrict acces to this page

//this page clearance
$arr = array (  
  '0' => 'ADMIN',
  '1' => 'ARTICLES_MASTER',
  '2' => 'COMMENTS_MASTER',
  );
define('CONSTANT_ARRAY',serialize($arr));

// check
include 'restrict_access.php';

//configuration file
include 'config_inc.php'; 



//Logout the current_user

//save menu state
	//if cookies had been flushed save default
	if (empty($_COOKIE["ac_menu_articles"]) or empty($_COOKIE["ac_menu_comments"]) or empty($_COOKIE["ac_menu_visitors"]) or empty($_COOKIE["ac_menu_users"]) or empty($_COOKIE["ac_menu_help"]) or empty($_COOKIE["ac_menu_admin"])) {
		$menu_state="articles=collapse, comments=collapse, visitors=collapse, users=collapse, help=collapse, admin=collapse,";
	} else {
	//else save cookies settings
		$menu_state="articles=".$_COOKIE["ac_menu_articles"].", comments=".$_COOKIE["ac_menu_comments"].", visitors=".$_COOKIE["ac_menu_visitors"].", users=".$_COOKIE["ac_menu_users"].", help=".$_COOKIE["ac_menu_help"].", admin=".$_COOKIE["ac_menu_admin"].",";
	}		
		//save
		$query = "UPDATE ".$db_table_prefix."users SET menu_state='$menu_state' WHERE username='".$_SESSION["current_user_username"]."'";
		mysql_query($query);
		
		
//unregister sessions
session_start();
unset($_SESSION["auth_ok_session"]);
unset($_SESSION["current_user_username"]);
unset($_SESSION["search_query_articles"]);
unset($_SESSION["search_query_comments"]);
unset($_SESSION["search_query_visitors"]);
unset($_SESSION["search_query_users"]);

//clear cookies
setcookie("move_source","cookie expired", time()-360000000);
setcookie("move_target","cookie expired", time()-360000000);

//go to login page
header("Location: index.php");
exit;

?>