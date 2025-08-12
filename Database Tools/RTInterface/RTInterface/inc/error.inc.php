


<?php
/******************************************************************
This file is included in every php file and handle errors
******************************************************************/

set_error_handler('errorHandler');

function errorHandler ($errno, $errstr, $errfile, $errline, $errcontext)
{
	switch ($errno)
	{
		case E_USER_WARNING:
		case E_USER_NOTICE:
		case E_WARNING:
		case E_NOTICE:
		case E_CORE_WARNING:
		case E_COMPILE_WARNING:
		break;
		case E_USER_ERROR:
		case E_ERROR:
		case E_PARSE:
		case E_CORE_ERROR:
		case E_COMPILE_ERROR:

		global $query;

		session_start();

		if (eregi('^(sql)$', $errstr)) {
			$errstr = "MySQL error: $MYSQL_ERRNO : $MYSQL_ERROR";
			$MYSQL_ERRNO = mysql_errno();
			$MYSQL_ERROR = mysql_error();
		} else {
			$query = NULL;
		} // if

		echo "<h2>System not available at the moment </h2>\n";
		echo "<b><font color='red'>\n";
		echo "<p>FATAL ERROR: $errstr (# $errno).</p>\n";
		if ($query) echo "<p>SQL query: $query</p>\n";
		echo "<p>Error in line $errline of file'$errfile'.</p>\n";
		echo "<p>Script: '{$_SERVER['PHP_SELF']}'.</p>\n";
		echo "</b></font>";

		// Stop the system
		session_unset();
		session_destroy();
		die();
		default:
		break;
	} // switch
} // errorHandler

?>