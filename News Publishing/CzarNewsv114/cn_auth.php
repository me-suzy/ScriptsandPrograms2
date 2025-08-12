<?
session_start();
include("cn_config.php");

### If there are no users in the database, include user admin to create first user ###
$q[info] = mysql_query("SELECT * FROM $t_user ORDER BY user ASC", $link) or E("Couldn't select users:<br>" . mysql_error());
$num = mysql_num_rows($q[info]);
if($num == "0") {
	include("cn_users.php");
	exit;
}


// If login form is submitted
if(!empty($_POST['usern']) || !empty($_POST['passw'])) {
	$_SESSION['usern'] = $_POST['usern'];
	$_SESSION['passw'] = $_POST['passw'];
} elseif (isset($_COOKIE['recook'])) {
	list ($_SESSION[usern], $_SESSION[passw]) = split ('[,]', $_COOKIE[recook]);
}

// If session variables are set
if (!empty($_SESSION['usern']) && !empty($_SESSION['passw'])) {
	if($_REQUEST['op'] == "logout") {
		setcookie('recook','',time()-28800,'/');
		$msg = "User logged out";
		include("login.php");
		session_destroy();
		exit;
	} else {
		$q[useri] = mysql_query("SELECT * FROM $t_user WHERE user='$_SESSION[usern]' && pass='$_SESSION[passw]'", $link);
		if (mysql_num_rows($q[useri]) != 0) {
			$useri = mysql_fetch_array($q[useri]);
			// Asseble user cetegories into an array
			$ucats = explode(", ", $useri[categories]); 
			if($useri[cookie] == "0") { $logtime = 3600; } else { $logtime = $useri[cookie]*24*60*60; }
			setcookie('recook',"$useri[user],$useri[pass]",time()+$logtime,'/');
			$time = strtotime("now");
			mysql_query("UPDATE $t_user SET last_login='$time' WHERE id=$useri[id]", $link);
			
			// Register session variables
			$_SESSION['usern']++;
			$_SESSION['passw']++;
	
		} else {
			// Wrong user/pass message
			$msg = "Incorrect Username/Password";
			include("login.php");
			exit;
		}
	}
} else {
	// User is not logged in
	$msg = "You must login to proceed";
	include("login.php");
	exit;
}
?>