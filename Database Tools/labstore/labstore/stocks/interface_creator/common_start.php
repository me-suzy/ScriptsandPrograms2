<?php
ob_start();
session_start();
if (!isset($_POST)){
	$_POST=$HTTP_POST_VARS;
}
if (!isset($_GET)){
	$_GET=$HTTP_GET_VARS;
}
if (!isset($_FILES)){
	$_FILES=$HTTP_POST_FILES;
}
if (!isset($_SESSION)){
	$_SESSION=$HTTP_SESSION_VARS;
}

if ($host != "" && $user != "" && $db_name != "" && $site_url != "") {
	
	// connect server
	$conn = connect_db($host, $user, $pass);

	// select the database
	select_db($db_name, $conn);

	$mysql_server_version = mysql_get_server_info($conn);

	$mysql_server_version_ar = explode('.', $mysql_server_version);

	// if something is not ok, I set the version to 3.21.0, I see this in PHPMyAdmin
	if (!isset($mysql_server_version_ar) || !isset($mysql_server_version_ar[0])) {
		$mysql_server_version_ar[0] = 3;
	}
	if (!isset($mysql_server_version_ar[1])) {
		$mysql_server_version_ar[1] = 21;
	}
	if (!isset($mysql_server_version_ar[2])) {
		$mysql_server_version_ar[2] = 0;
	}

	$mysql_server_version = (int)sprintf('%d%02d%02d', $mysql_server_version_ar[0], $mysql_server_version_ar[1], intval($mysql_server_version_ar[2])); // intval because it could be e.g. 3.23.41-nt

	if ($mysql_server_version >= 32306){
		$quote = "`";
	} // end if
	else{
		$quote = ""; // versions before 3.23.06 don't support back quote
	} // end else

	if ($mysql_server_version > 32300){
		$use_limit_in_update = 1;
	} // end if
	else{
		$use_limit_in_update = 0; // versions before 3.23.00 don't support limit in update statements
	} // end else 

} // end if
else{
	echo "<p><b>[01] Error:</b> either host, username or password settings for the MySQL database server connection, or the MySQL database name and site url are not specified or incorrect. Please check parameters and ensure correct syntax in the config.php file. Also, check that MySQL server is functioning and that you can connect to it with the host, username or password settings. Ensure that the database you want to use exists on the MySQL server.</p>";
	exit;
} // end else

// tables present in the databse
$table_names_ar = build_tables_names_array(0, 0);

if (count($table_names_ar) == 0){ // no table
	echo "<p><b>[02] Error:</b> your database - ".$db_name." - is empty. No tables were found. Please create some tables in the MySQL database before using this interface creator. They need not contain any data but the structure - how many columns (fields) and their types should be there. For help to create tables in MySQL, please check elsewhere. You may want to use the free phpmyadmin web application to do so - www.phpmyadmin.net.</p>";
	exit;
} // end if

// the var is set in check_login but check_login it's not included by e.g. admin, and it's useful for some functions (e.g. build_tables_names_array) to have it set
$current_user_is_administrator = 0;
?>