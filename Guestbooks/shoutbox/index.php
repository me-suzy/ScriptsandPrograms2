<?php

// Shoutbox - Unknown Domain (Tom Lynch)
//            klix@unknowndomain.co.uk
//            www.unknowndomain.co.uk

// SETTINGS
//  You may edit these

//----database settings
define ("mysql_host", "localhost");
define ("mysql_username", "root");
define ("mysql_password", "");
define ("mysql_database", "shoutbox");
define ("mysql_table", "shoutbox");

//----admin settings
define("admin_username", "admin");
define("admin_password", "admin");

//----misc settings
define("script_filename", "index.php");
define("shouts_per_page", "10");

// /////////////////////////////////////////////////////////////// //
// INSTALLATION                                                    //
// /////////////////////////////////////////////////////////////// //
// 1. Create a database and insert this sql                        //
//      CREATE TABLE shoutbox (                                    //
//       id int(11) NOT NULL auto_increment,                       //
//       handle varchar(255) NOT NULL default '',                  //
//       message text NOT NULL,                                    //
//       ip varchar(255) NOT NULL default '',                      //
//       datetime datetime default NULL,                           //
//       PRIMARY KEY  (id)                                         //
//      ) TYPE=MyISAM;                                             //
// 2. Edit the settings below                                      //
// 3. Edit the HTML which is right at the bottom to fit the style  //
//    of your site.                                                //
// 4. Finished! I hope you enjoy this script                       //
// /////////////////////////////////////////////////////////////// //



// /////////////////////////////////////////////////////////////// //
// CREATIVE COMMONS LICENCE                                        //
// /////////////////////////////////////////////////////////////// //
// Attribution-ShareAlike 2.0 England & Wales                      //
//                                                                 //
// You are free:                                                   //
//  * to copy, distribute, display, and perform the work           //
//  * to make derivative works                                     //
//  * to make commercial use of the work                           //
//                                                                 //
// Under the following conditions:                                 //
// Attribution. You must attribute the work in the manner          //
// specified by the author or licensor.                            //
//                                                                 //
// Share Alike. If you alter, transform, or build upon this        //
// work, you may distribute the resulting work only under a        //
// license identical to this one.                                  //
//                                                                 //
//  * For any reuse or distribution, you must make clear to        //
//	  others the license terms of this work.                       //
//                                                                 //
//  * Any of these conditions can be waived if you get             //
//    permission from the copyright holder.                        //
//                                                                 //
//  Your fair use and other rights are in no way affected by the   //
//  above.                                                         //
//                                                                 //
// This is a human-readable summary of the Legal Code              //
// (the full license).                                             //
//                                                                 //
// /////////////////////////////////////////////////////////////// //
//  WWW                                                            //
// /////////////////////////////////////////////////////////////// //
// creative commons:                                               //
//  http://creativecommons.org/                                    //
//                                                                 //
// human licence:                                                  //
//  http://creativecommons.org/licenses/by-sa/2.0/uk/              //
//                                                                 //
// full licence:                                                   //
//  http://creativecommons.org/licenses/by-sa/2.0/uk/legalcode     //
//                                                                 //
// /////////////////////////////////////////////////////////////// //



// /////////////////////////////////////////////////////////////// //
// CHANGE LIST                                                     //
// /////////////////////////////////////////////////////////////// //
// Date			Notes								Who			   //
// ----			-----								---			   //		
// 20/07/05		Inital Version 1.0					Tom Lynch	   //
// /////////////////////////////////////////////////////////////// //



// /////////////////////////////////////////////////////////////// //
// DO NOT EDIT BELOW THIS LINE, UNLESS YOU KNOW WHAT YOUR DOING!!! //
// /////////////////////////////////////////////////////////////// //

// Start Session
session_start();

// FUNCTIONS

//----connects to mysql
function mysql_conn() {

	mysql_connect(mysql_host, mysql_username, mysql_password) or die(mysql_error());
	mysql_select_db(mysql_database) or die(mysql_error());

}

//----adds a shout
function addShout($handle, $message) {

	mysql_conn();
	
	$ip = $_SERVER['REMOTE_ADDR'];
	$datetime = date("U");
	
	$sql = "INSERT INTO ".mysql_table." (handle, message, ip, datetime) VALUES ('$handle', '$message', '$ip', '$datetime');";	
	mysql_query($sql) or die(mysql_error()."<br />".$sql);
	
	mysql_close() or die(mysql_error());

}

//----updates a specific shout
function editShout($id, $handle, $message) {

	mysql_conn();
	
	$sql = "UPDATE ".mysql_table." SET handle = '$handle', message = '$message' WHERE id = '$id';";	
	mysql_query($sql) or die(mysql_error()."<br />".$sql);
	
	mysql_close() or die(mysql_error());
	
}

//----deletes a specific shout
function deleteShout($id) {

	mysql_conn();
	
	$sql = "DELETE FROM ".mysql_table." WHERE id = '$id';";	
	mysql_query($sql) or die(mysql_error()."<br />".$sql);
	
	mysql_close() or die(mysql_error());

}

//----gets a specific shout
function getShout($id) {

	mysql_conn();
	
	$sql = "SELECT * FROM ".mysql_table." WHERE id = '$id';";	
	$query = mysql_query($sql) or die(mysql_error()."<br />".$sql);
	
	$data = mysql_fetch_row($query);
	
	mysql_close() or die(mysql_error());
	
	return $data;

}

//----gets the shouts for that page
function getShouts($page, $type) {

	mysql_conn();

	//----multiply page number by number of shouts to work out which shout to start on
	$starting_shout = $page * shouts_per_page;

	//----sql
	$sql = "SELECT id, handle, message FROM ".mysql_table." ORDER BY id DESC LIMIT $starting_shout, ".shouts_per_page.";";
	$query = mysql_query($sql) or die(mysql_error()."<br />".$sql);
	
	//----loop through and format data into one 
	while ($data = mysql_fetch_row($query)) {
	$output .= "\n" . '<b>' . $data[1] . '</b> - ' . $data[2] ;
	if ($type == "admin") {
	$output .= ' - [<a href="'.script_filename.'?do=edit&id='. $data[0] .'">Edit</a>] [<a href="'.script_filename.'?do=delete&id='. $data[0] .'">Delete</a>]<br />';
	} else {
	$output .= '<br />';
	};
	}
	$output .= "\n";
	mysql_close() or die(mysql_error());
	
	return $output;
}

//----counts the number of shouts (used as a subfunction of other functions - afaik)
function countShouts() {
	
	mysql_conn();
	
	//----sql
	$sql = "SELECT id FROM ".mysql_table.";";
	$query = mysql_query($sql) or die(mysql_error()."<br />".$sql);
	
	$output = mysql_num_rows($query);
	
	mysql_close();
	
	return $output;
	
}

//----builds the page numbers
function buildPager($page) {

	//----calcualte number of pages
	$numpages = countShouts() / shouts_per_page;
	$previous_page = $page - 1;
	$next_page = $page + 1;
	
	//----page x of x output
	$output .= 'Page '.$page.' of '.floor($numpages).'<br />';
	$output .= "\n";
	
	//----previous page button
	if ($page > 1)	{$output .= '<a href="'.script_filename.'?page='.$previous_page.'"><</a>';}
	else	{$output .= '<';};
	$output .= "\n";
	
	//----page numbers
	$count = 1;
	while ($count < $numpages) {
	$output .= ' <a href="'.script_filename.'?page='.$count.'">'.$count.'</a> ';
	$output .= "\n";
	$count = $count + 1;
	}
	
	//----next page button
	if ($page < floor($numpages))	{$output .= '<a href="'.script_filename.'?page='.$next_page.'">></a>';}
	else	{$output .= '>';};
	$output .= "\n";
	
	return $output;

}

//----logs user in
function loginAdmin($username, $password) {

	$_SESSION['username'] = $username;
	$_SESSION['password'] = $password;
	
	$do = "admin";

}

//----logs out user
function logoutAdmin() {

	session_unset($_SESSION['username']);
	session_unset($_SESSION['password']);
	
	$do = "home";

}

//----checks user is logged in
function checkLogin() {

	if ($_SESSION['username'] == admin_username && $_SESSION['password'] == admin_password) {
	$result = 1;
	} else {
	$result = 0;
	};
		
	return $result;

}

// CODE

//----decide which operation user has requested
if ($_POST['do']) {
	$do = $_POST['do'];
} elseif($_GET['do']) {
	$do = $_GET['do'];
} else {
	$do = "home";
};

//----add
if ($do == "add") {
	
	//----get in the variables	
	$handle = stripslashes($_POST['handle']);
	$message = stripslashes($_POST['message']);
	$page = stripslashes($_POST['page']);
	
	//----send request to addShout() function
	addShout($handle, $message);
	
	//----set the handle session variable for further messages
	$_SESSION['handle'] = "$handle";
	
	//----display home	
	$do = "home";
	$msg = "Added!";

};

//----edit
if ($do == "edit") {

	//----logged in
	if (checkLogin()) {
	
		$id = $_GET['id'];
		$shout = getShout($id);
		$output .= '<h1>Shoutbox Administration</h1>
					<h2>Edit Shout</h2>						
					<fieldset>
					<form action="' . script_filename . '" method="POST">
					<label for="handle">Handle</label></br>
					<input type="text" id="handle" name="handle" value="' . $shout[1] . '" /><br />
					</br>
					<label for="message">Message</label></br>
					<input type="text" id="message" name="message" value="'. $shout[2] .'" /><br />
					</br>
					IP:<br/>
					'.$shout[3].'<br />
					<br />
					Posted:<br/>
					'.date("l jS F Y @ g:i a (O T)", strtotime($shout[4])).'<br />
					<br />
					<input type="hidden" id="do" name="do" value="edit-script" />
					<input type="hidden" id="id" name="id" value="'.$shout[0].'" />
					<input type="submit" value="Update" />
					</form>
					</fieldset>
					<a href="'.script_filename.'?do=home">Home</a> - <a href="'.script_filename.'?do=admin">Admin</a> - <a href="'.script_filename.'?do=logout">Logout</a>';

	}
	
	//----not logged in
	if (!checkLogin()) {
	$do = "login";
	$output = '';
	}

};

//----edit script
if ($do == "edit-script") {

	//----logged in
	if (checkLogin()) {
		
		$id = $_POST['id'];
		$handle = $_POST['handle'];
		$message = $_POST['message'];
		editShout($id, $handle, $message);		
		$do = "admin";
		$msg = "Updated!";

	}
	
	//----not logged in
	if (!checkLogin()) {
	$do = "login";
	$output = '';
	}

};

//----delete
if ($do == "delete") {
	
	//----logged in
	if (checkLogin()) {

		$id = $_GET['id'];
		deleteShout($id);
		
		$output = '';
		$do = "admin";
		$msg = "Deleted!";

	}
	
	//----not logged in
	if (!checkLogin()) {
	$do = "login";
	$output = '';
	}

};

//----login script
if ($do == "login-script") {

	$username = $_POST['username'];
	$password = $_POST['password'];
	loginAdmin($username, $password);
	$do = "admin";
	$msg = "Logged in!";

};

//----admin
if ($do == "admin") {
	
	//----logged in
	if (checkLogin()) {
		$page = 1;
		
		if ($_POST['page']) {
			$page = $_POST['page'];
		} elseif ($_GET['page']) {
			$page = $_GET['page'];
		};
		
		$output = 	'<h1>Shoutbox Administration</h1>
					<h2>'.$msg.'</h2>
					<p>'
					.getShouts($page, 'admin').
					'</p>
					<p>'
					.buildPager($page).
					'</p>
					<p><a href="'.script_filename.'?do=home">Home</a> - <a href="'.script_filename.'?do=logout">Logout</a></p>';
	}
	
	//----not logged in
	if (!checkLogin()) {
		$do = "login";
		$output = '';
	}

};

//----login
if ($do == "login") {
	
		$output .= '<h1>Shoutbox Login</h1>
		<fieldset>
		<form action="'.script_filename.'" method="POST">
		<label for="username">Username</label><br />
		<input type="text" id="username" name="username" /><br />
		<br />
		<label for="password">Password</label><br />
		<input type="password" id="password" name="password" /><br />
		<br />
		<input type="hidden" id="do" name="do" value="login-script"
		<input type="submit" value="Login" />
		</form>
		</fieldset>
		';

};

//----logout
if ($do == "logout") {

	$output = logoutAdmin();
	$do = "home";
	$msg = "Logged out!";	

};

//----home
if ($do == "home" or $do == "") {

	$page = 1;
	
	if ($_POST['page']) {
		$page = $_POST['page'];
	} elseif ($_GET['page']) {
		$page = $_GET['page'];
	};
	
	$output = 	'<h1>Shoutbox</h1>
				<h2>'.$msg.'</h2>
				<p>'
				.getShouts($page, 'user').
				'</p>
				<p>'
				.buildPager($page).
				'</p>
				<fieldset>
				<form action="' . $_SERVER['PHP_SELF'] . '" method="POST">
				<label for="handle">Handle</label></br>
				<input type="text" id="handle" name="handle" value="' . $_SESSION['handle'] . '" /><br />
				</br>
				<label for="message">Message</label></br>
				<input type="text" id="message" name="message" /><br />
				</br>
				<input type="hidden" id="do" name="do" value="add" />
				<input type="hidden" id="page" name="page" value="'.$page.'" />
				<input type="submit" value="Shout!" />
				</form>
				</fieldset>
				<a href="'.script_filename.'?do=admin">Admin</a>
				';

};

// /////////////////////////////////////////////////////////////// //
// HTML CODE (EDITABLE)                                            //
// /////////////////////////////////////////////////////////////// //
?>
<html>
<head>
<title>Shoutbox</title>
</head>
<body>

<?php /* MOVE THIS LINE TO THE PLACE WHERE YOU WANT THE SHOUTBOX TO PRINT!!! */ echo $output; ?>

</body>
</html>