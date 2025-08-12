<?php # Access_Check.php

// Iron Muskie, Inc
// Michael J. Muff
// customer_service@iron-muskie-inc.com
// Version 1.0907
// September 2005

session_start(); // Start the session.

function AuthUser($level) {

// Open the database connection
require_once ('./Mysqlconnect.php'); // Connect to the db.

// If no session value is present, redirect the user.
if (!isset($_SESSION['user_id'])) {

	// Start defining the URL.
	$url = 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']);
	// Check for a trailing slash.
	if ((substr($url, -1) == '/') OR (substr($url, -1) == '\\') ) {
		$url = substr ($url, 0, -1); // Chop off the slash.
	}
	$url .= '/login.php'; // Add the page.
	header("Location: $url");
	exit(); // Quit the script.
}

//Get the user id!
$userid=$_SESSION['user_id'];

//Get the email address!
$email=$_SESSION['email'];

//Get the access level!
$acc=$_SESSION['access_level'];

		//Verify the user is in the database.
		$query = "SELECT email, access_level FROM Users WHERE user_id=$userid";
		$result = mysql_query($query);
		if (mysql_num_rows($result) != 0) {
			while ($row=mysql_fetch_array ($result, MYSQL_ASSOC)) {
				$CheckEmail=stripslashes($row['email']);
				$Access=stripslashes($row['access_level']);
			}
		}
//Verify the Session and User Exists
if ($email!=$CheckEmail ) {

	// Start defining the URL.
	$url = 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']);
	// Check for a trailing slash.
	if ((substr($url, -1) == '/') OR (substr($url, -1) == '\\') ) {
		$url = substr ($url, 0, -1); // Chop off the slash.
	}
	$url .= '/login.php'; // Add the page.
	header("Location: $url");
	exit(); // Quit the script.

}

//Restrict Access to a certain Access Level
if ($Access!='Admin') {
	if ($Access!=$level) {

		// Start defining the URL.
		$url = 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']);
		// Check for a trailing slash.
		if ((substr($url, -1) == '/') OR (substr($url, -1) == '\\') ) {
			$url = substr ($url, 0, -1); // Chop off the slash.
		}
		$url .= '/error.php'; // Add the page.
		$_SESSION['error'] = "Sorry, You do not have the correct permissions to access this page!";
		header("Location: $url");
		exit(); // Quit the script.

	}
}

}

function AuthIP($CheckIP) {

// Open the database connection
require_once ('./Mysqlconnect.php'); // Connect to the db.

		$BannedIPs[]='';
		$query = "SELECT * FROM Banned";
		$result = mysql_query($query);
			while ($row=mysql_fetch_array ($result, MYSQL_ASSOC)) {
				$BannedIPs[]=stripslashes($row['logged_ip']);
			}
			
		foreach ($BannedIPs as $id) {
			if ($id==$CheckIP){
			
			echo '<h2>Error:</h2>
			<p>You have been Banned from the Site!</p>';
			exit(); // Quit the script.
			
			}
		}

}

?>
