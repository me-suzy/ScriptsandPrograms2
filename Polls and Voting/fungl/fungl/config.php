<?php
$pear_path = dirname(__FILE__)."/PEAR/"; // Path to the pear folder
define('DB_PREFIX', '__PREFIX__'); // database tables prefix

$dsn = "__DSN__"; // database connection string

// define user levels
define('GUEST_LVL', 1);
define('USER_LVL', 500);
define('ADMIN_LVL', 1000);
define('SUPERADMIN_LVL', 2000);

error_reporting(E_WARNING);

/** Name of chart classes, key is the name shown to the user, value is the class name */
$graphs = array("Row" => "Row", "Pie" => "Pie", "Colum" => "Colum");

// Set the path so we can include the pear files 
if(eregi("^WIN",PHP_OS)){
    // for windows
    set_include_path(".;".$pear_path);
}else{
    // for unix
    set_include_path(".:".$pear_path);
}
session_start();

require_once 'DB.php';
require_once 'classes/project.php';
require_once 'Auth/PrefManager.php';
require_once 'Auth.php';

// connect to the database and save the connection i the global variable $db
$db = &DB::connect($dsn);
if (PEAR::isError($db)) {
    die($db->getMessage());
}

// auth system paramters, 
// see http://pear.php.net/manual/en/package.authentication.auth.auth.auth.php for info
$params = array(
            "dsn" => $dsn,
            "table" => DB_PREFIX."fungl_auth",
            "usernamecol" => "username",
            "passwordcol" => "password"
            );
$user = new Auth("DB", $params, "", false);
$user->start();
// if the user requested logout, we logout the user
if($_GET['action'] == "logout" && $user->checkAuth()) {
    $user->logout();
    $user->start(); // start the auth system with guest privileges
}

// user access system, 
// see http://pear.php.net/manual/en/package.authentication.auth-prefmanager.auth-prefmanager.auth-prefmanager.php
$options = array(
			'serialize' => true,
			'table' => DB_PREFIX.'fungl_userpreferences'
			); 
if($user->getAuth()){
	// get user prefs
	$userpref = new Auth_PrefManager($dsn, $options, $user->getUsername()); // Create the object.
}else{
	$userpref = new Auth_PrefManager($dsn, $options, 'guest'); // Create the object.
}

/** translates the numeric user levels to human readable text
 * @param $lvl int with a user lvl
 * @return string containing a human readable version of the lvl
 */
function getLvlName($lvl){
	if($lvl == SUPERADMIN_LVL)
		return "Superadmin";
	if($lvl == ADMIN_LVL)
		return "Admin";
	if($lvl == USER_LVL)
		return "User";
	if($lvl == GUEST_LVL)
		return "Guest";
	return "Unknown lvl: ".$lvl;
}

/** Get all the userlevels in the system
 * @return array with user lvl values
 */
function getUserLvls(){
	return array(SUPERADMIN_LVL, ADMIN_LVL, USER_LVL, GUEST_LVL);
}

/** checks if the username is allready used
 * @param $username string containing the username
 * @return true if username is in use, false if it is not in use
 */
function usernameInUse($username){
	$db = &$GLOBALS['db'];
	$res = &$db->getRow('SELECT username FROM '.DB_PREFIX.'fungl_auth WHERE username='.$db->quoteSmart($username));
	if(PEAR::isError($res)){
		// we default to the username is in use if the query fails
	   	return true;
	}
	if($res[0] != $username){
		return false;
	}
	return true;
}

/** gets all projects owned by the specified user
 * @param $username username 
 * @return array with all project owned by the user, if no projects is owned by the user an empty array will be returned
 */
function listProjects($username){
	$db = &$GLOBALS['db'];
	$res = &$db->query('SELECT id FROM '.DB_PREFIX.'fungl_projects WHERE userid='.$db->quoteSmart($username));
	if(!PEAR::isError($res)){
	   	while ($row = &$res->fetchRow()) {
	   		$project = new Project($GLOBALS['db'], $row[0]);
	   		if(!$project->isError()){
	   			$projects[] = $project;
	   		}
	   	}
	}else{
		return array();
	}
	if(empty($projects)){
		return array();
	}
	return $projects;
}

function getPollAmount($projects){
	$count = 0;
	while($i = each($projects)){
		$count += count($i['value']->getPolls());
	}
	return $count;
}

function deleteProjects($username){
	$projects = listProjects($username);
	for($i = 0; $i < count($projects); $i++){
		if(!$projects[$i]->delete())
			$error = true;
		else
			unset($projects[$i]);
	}
	if(!empty($error))
		return false;
	else
		return true;
}

function sendAccountWelcomeMail($username, $password){
	$to = $GLOBALS['userpref']->getPref($username, 'email');
	$subject = 'Welcome to FunGL the only polling software you need';
	$headers = 'From: webmaster@example.com'."\r\n";
	$message = "Welcome to FunGL\r\n" .
			"You are now allowed to create ".$GLOBALS['userpref']->getPref($username, 'projectamount').
			" project including ".$GLOBALS['userpref']->getPref($username, 'pollamount')." polls\r\n".
			"We hope that you will find this software helpfull.\r\n".
			"\r\n".
			"Your registered with the following information.\r\n".
			"Username: ".$username."\r\n".
			"Password: ".$password."\r\n";
			"E-mail: ".$GLOBALS['userpref']->getPref($username, 'email');
	
	mail($to, $subject, $message, $headers);
}

function sendAccountChangeMail($username, $password){
	$to = $GLOBALS['userpref']->getPref($username, 'email');
	$subject = 'Account change, FunGL';
	$headers = 'From: webmaster@example.com'."\r\n";
	$message = "Account change with FunGL\r\n" .
			"You are now allowed to create ".$GLOBALS['userpref']->getPref($username, 'projectamount').
			" project including ".$GLOBALS['userpref']->getPref($username, 'pollamount')." polls\r\n".
			"\r\n".
			"Your are now registered with the following information.\r\n".
			"Username: ".$username."\r\n".
			"Password: ".$password."\r\n".
			"E-mail: ".$GLOBALS['userpref']->getPref($username, 'email');
	mail($to, $subject, $message, $headers);
}

function sendAccountDeleteMail($username){
	$to = $GLOBALS['userpref']->getPref($username, 'email');
	$subject = 'Your account with FunGL has been deleted';
	$headers = 'From: webmaster@example.com'."\r\n";
	$message = 'You account with FunGL has been deleted';
	
	mail($to, $subject, $message, $headers);
}
?>