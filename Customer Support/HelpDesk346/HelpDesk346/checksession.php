<?
	session_start();
	include_once("config.php");
	//MYSQL DataBase Connection Sectionrequire("config.php");
	mysql_connect(DB_HOST, DB_UNAME, DB_PASS);
	mysql_select_db(DB_DBNAME);
	
	if (!isset($_SESSION['enduser']) || !isset($_SESSION['loggedIn']) || $_SESSION['loggedIn'] != true)
		die("Access Denied - Please Use index.php to log in");
		
	include_once "./classes/user.php";
	include_once "./includes/constants.php";	
	$user = unserialize($_SESSION['enduser']);
	if ($user->get('securityLevel', 'intval') == ENDUSER_SECURITY_LEVEL)
		die("Access Cannot be Granted to this Content");
		
	unset($user);
	include_once "./includes/constants.php";
?>