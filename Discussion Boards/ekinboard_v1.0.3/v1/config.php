<?PHP
ob_start();
session_start();

$_debug = 0;

if($_debug == 1){
		error_reporting (E_ALL);
	echo "<center>
			<table cellpadding=\"0\" cellspacing=\"0\" class=\"redtable\" width=\"80%\">
				<tr>
					<td class=\"redtable_header\">
						<b>Debug Mode</b>
					</td>
				</tr>
				<tr>
					<td class=\"redtable_content\">";
} else {
	error_reporting(E_ERROR);
}

// Include the database connection variables.
include ("db_info.php");

// Attempt to connect to mySQL.
$connect = mysql_connect($db_host,$db_user,$db_pass);

// If connection failed, then show the error page.
if (!$connect) {
   header("Location: noconnect.html");
}

// Now we want to attempt to connect to the database.
$db = mysql_select_db($db_name,$connect);


// Escape string for MySQL query
function escape_string($string) {
	if (!get_magic_quotes_gpc()){
		 $string = addslashes($string);
	}
	if (!get_magic_quotes_runtime()) {
		if (version_compare(phpversion(),"4.3.0")=="-1"){
			mysql_escape_string($string);
		} else {
			mysql_real_escape_string($string);
		}
	}
	return $string;
}

// Escape any dangerous strings in the GET variable in the beguinning.
foreach ( $_GET as $key => $val ){
	$val =  escape_string($val);
	$_GET[ $key ] = $val;
}

// And the same with the POST variables.
foreach ( $_POST as $key => $val ){
	$val =  escape_string($val);
	$_POST[ $key ] = $val;
}

// Start creating the setting variables.
$_SETTING = NULL;

// Get get all the settings from the database.
$options_sql = mysql_query("SELECT * FROM settings WHERE name!='rule'");

// Loop through them and create a new setting variable for each setting.
while($settings_row = mysql_fetch_array($options_sql)){

	// Get and set the new setting.
	$settings_name = $settings_row['name'];

	// If there is no name set.
	if($settings_name == NULL){
		// refer to it with the ID.
		$settings_name = $settings_row['id'];
	}
	
	// Add to the array.
	$_SETTING[$settings_name] = $settings_row['value'];

	$settings_name = NULL;
}

// If any of the required settings are missing. Give them the default values.
if($_SETTING['acitvate_accounts'] == NULL){
	$_SETTING['acitvate_accounts'] = 1;
}
if($_SETTING['upload_avatars'] == NULL){
    $_SETTING['upload_avatars'] = 0;
}
if($_SETTING['avatar_exts'] == NULL){
	$_SETTING['avatar_exts'] = "gif, jpg, png";
}
if($_SETTING['avatar_max_size'] == NULL){
	$_SETTING['avatar_max_size'] = "20480";
}
if($_SETTING['temp_setting'] == NULL){
	$_SETTING['temp_setting'] = 1;
}
if($_SETTING['allow_attch'] == NULL){
	$_SETTING['allow_attch'] = 1;
}
if($_SETTING['attch_exts'] == NULL){
	$_SETTING['attch_exts'] = "gif, jpg, png, txt";
}
if($_SETTING['attch_max_size'] == NULL){
	$_SETTING['attch_max_size'] = "20480";
}
if($_SETTING['allow_feeds'] == NULL){
	$_SETTING['allow_feeds'] = 1;
}
if($_SETTING['feed_display'] == NULL){
	$_SETTING['feed_display'] = 10;
}
if($_SETTING['template'] == NULL){
	$_SETTING['template'] = "default";
}
////////

// Set the default theme.
$user["theme"] = $_SETTING['template'];

// Check for any existing cookies.
if ((isset($_COOKIE['username'])) && (isset($_COOKIE['password']))){
	// Set some new variables for checking.
	$_u = $_COOKIE['username'];
	$_p = $_COOKIE['password'];

	// Varify with the database that the cookie is a valid cookie.
	$cookie_check = mysql_query("SELECT * FROM users WHERE username='". $_u ."' AND password='". $_p ."'");

	// If so, make sessions.
	if (mysql_num_rows($cookie_check) > 0){
		// Set the session values.
		$_SESSION['username'] = $_COOKIE['username'];
		$_SESSION['password'] = $_COOKIE['password'];
	}
}

// Check for any existing sessions.
if ((isset($_SESSION['username'])) && (isset($_SESSION['password']))){
	// Set some new variables for checking.
	$_u = $_SESSION['username'];
	$_p = $_SESSION['password'];

	// Verify with the database that the sessions are valid sessions.
	$yes = mysql_query("SELECT * FROM users WHERE username='". $_u ."' AND password='". $_p ."'");

	// If so, set some constants that will be refered to on most pages.
	if (mysql_num_rows($yes) > 0){	
		$row = mysql_fetch_array($yes);

		// If the user is banned than nullify all the member constants.
		if($row['banned'] == 1){
			$_userlevel = 1;
			$_username = NULL;
			$_userid = NULL;
			$_email = NULL;
			$_template = NULL;
	
			// Tell the script that the user is banned from the site, and give the reason.
			$_banned = TRUE;
			$_banned_reason = $row['banned_reason'];

		// If the user is not banned than set the member constants.
		} else {
			// Associate the constants with the database values.
			$_username = $row['username'];
			$_userid = $row['id'];
			$_userlevel = $row['level'];
			$_email = $row['email'];
			$_template = $row['skin'];

			// Check the users theme.  If it is default than assign the default theme, otherwise set the users desired theme.
			if($_template == "default"){
				$user["theme"] = $user["theme"];
			} else {
				$user["theme"] = $_template;
			}
		}
	}

// If no sessions exist than they are not logged in and are a guest.
} else {
	// Set the userlevel and nullify the rest of the constants.
	$_userlevel = 1;
	$_username = NULL;
	$_userid = NULL;
	$_email = NULL;
	$_template = NULL;
}

// Declair ekinboard version.  PLEASE DO NOT CHANGE THIS. THIS IS NEEDED THOUGHTOUT THE SCRIPT.
$_version = "1.0.3";

// Include my very own ekincode.
include 'ekincode.php';

?>