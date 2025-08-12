<?php # Mysqlconnect.php

// Iron Muskie, Inc
// Michael J. Muff
// customer_service@iron-muskie-inc.com
// Version 1.0918
// September 2005

// This file contains the database access information. 
// This file also establishes a connection to MySQL and selects the database.
// This file also defines the escape_data() function.

//config file
require_once('./config.php');

// Set the database access information as constants.
DEFINE ('DB_USER', $DBUser);
DEFINE ('DB_PASSWORD', $DBPass);
DEFINE ('DB_HOST', $DBHost);
DEFINE ('DB_NAME', $DBName);

// Make the connection.
$dbc = @mysql_connect (DB_HOST, DB_USER, DB_PASSWORD) OR die ('Could not connect to MySQL: ' . mysql_error() );

// Select the database.
@mysql_select_db (DB_NAME) OR die ('Could not select the database: ' . mysql_error() );

// Create a function for escaping the data.
function escape_data ($data) {
	
	// Address Magic Quotes.
	if (ini_get('magic_quotes_gpc')) {
		$data = stripslashes($data);
	}
	
	// Check for mysql_real_escape_string() support.
	if (function_exists('mysql_real_escape_string')) {
		global $dbc; // Need the connection.
		$data = mysql_real_escape_string (trim($data), $dbc);
	} else {
		$data = mysql_escape_string (trim($data));
	}

	// Return the escaped value.	
	return $data;

} // End of function.
?>