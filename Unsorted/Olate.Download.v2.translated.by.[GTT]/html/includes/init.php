<?php
/***************************************************************************
 *                      Olate Download v2 - Download Manager
 *
 *                           http://www.olate.com
 *                            -------------------
 *   author                : David Mytton
 *   copyright             : (C) Olate 2003 
 *
 *   Support for Olate scripts is provided at the Olate website. Licensing
 *   information is available in the license.htm file included in this
 *   distribution and on the Olate website.                  
 ***************************************************************************/

/***************************************************************************/
// 1. Check db.php, if size 0, display message
/***************************************************************************/
if ($admin == 1)
{
	$path = '../includes/db.php';
	if (filesize($path) == 0)
	{
		echo '<table width="500"  border="1" cellpadding="0" cellspacing="0" bordercolor="#000000">
		<tr>
		<td bordercolor="#FFFFFF"><table width="100%"  border="0" cellspacing="0" cellpadding="2">
		<tr>
		<td height="20" bgcolor="#E3E8EF"><font face="Arial, Helvetica, sans-serif" size="2"><b>db.php Empty:</b></font></td>
		</tr>
	 	<tr>
		<td><font face="Arial, Helvetica, sans-serif" size="2">db.php is empty. This usually indicates that the script has not been installed. Please proceed to http://www.yourdomain.com/pathtoscript/setup</font></td>
		</tr>
		</table></td>
		</tr>
		</table>';
		exit;
	}
} elseif ($admin == 2) {
	$path = '../../includes/db.php';
	if (filesize($path) == 0)
	{
		echo '<table width="500"  border="1" cellpadding="0" cellspacing="0" bordercolor="#000000">
		<tr>
		<td bordercolor="#FFFFFF"><table width="100%"  border="0" cellspacing="0" cellpadding="2">
		<tr>
		<td height="20" bgcolor="#E3E8EF"><font face="Arial, Helvetica, sans-serif" size="2"><b>db.php Empty:</b></font></td>
		</tr>
	 	<tr>
		<td><font face="Arial, Helvetica, sans-serif" size="2">db.php is empty. This usually indicates that the script has not been installed. Please proceed to http://www.yourdomain.com/pathtoscript/setup</font></td>
		</tr>
		</table></td>
		</tr>
		</table>';
		exit;
	}
} else {
	$path = 'includes/db.php';
	if (filesize($path) == 0)
	{
		echo '<table width="500"  border="1" cellpadding="0" cellspacing="0" bordercolor="#000000">
		<tr>
		<td bordercolor="#FFFFFF"><table width="100%"  border="0" cellspacing="0" cellpadding="2">
		<tr>
		<td height="20" bgcolor="#E3E8EF"><font face="Arial, Helvetica, sans-serif" size="2"><b>db.php Empty:</b></font></td>
		</tr>
	 	<tr>
		<td><font face="Arial, Helvetica, sans-serif" size="2">db.php is empty. This usually indicates that the script has not been installed. Please proceed to http://www.yourdomain.com/pathtoscript/setup</font></td>
		</tr>
		</table></td>
		</tr>
		</table>';
		exit;
	}
}

/***************************************************************************/
// 2. Check setup/ has been deleted. If not, return error
/***************************************************************************/
if ($admin == 1)
{
	$path = '../setup';
	if (file_exists($path))
	{
		echo '<table width="500"  border="1" cellpadding="0" cellspacing="0" bordercolor="#000000">
		<tr>
		<td bordercolor="#FFFFFF"><table width="100%"  border="0" cellspacing="0" cellpadding="2">
		<tr>
		<td height="20" bgcolor="#E3E8EF"><font face="Arial, Helvetica, sans-serif" size="2"><b>setup/ Directory</b></font></td>
		</tr>
	 	<tr>
		<td><font face="Arial, Helvetica, sans-serif" size="2">You must remove the setup/ directory and all its contents before using Olate Download. This is for security reasons and will prevent malicious users overwriting your installation.</font></td>
		</tr>
		</table></td>
		</tr>
		</table>';
		exit;
	}
} elseif ($admin == 2) {
	$path = '../../setup';
	if (file_exists($path))
	{
		echo '<table width="500"  border="1" cellpadding="0" cellspacing="0" bordercolor="#000000">
		<tr>
		<td bordercolor="#FFFFFF"><table width="100%"  border="0" cellspacing="0" cellpadding="2">
		<tr>
		<td height="20" bgcolor="#E3E8EF"><font face="Arial, Helvetica, sans-serif" size="2"><b>setup/ Directory</b></font></td>
		</tr>
	 	<tr>
		<td><font face="Arial, Helvetica, sans-serif" size="2">You must remove the setup/ directory and all its contents before using Olate Download. This is for security reasons and will prevent malicious users overwriting your installation.</font></td>
		</tr>
		</table></td>
		</tr>
		</table>';
		exit;
	}
} else {
	$path = 'setup';
	if (file_exists($path))
	{
		echo '<table width="500"  border="1" cellpadding="0" cellspacing="0" bordercolor="#000000">
		<tr>
		<td bordercolor="#FFFFFF"><table width="100%"  border="0" cellspacing="0" cellpadding="2">
		<tr>
		<td height="20" bgcolor="#E3E8EF"><font face="Arial, Helvetica, sans-serif" size="2"><b>setup/ Directory</b></font></td>
		</tr>
	 	<tr>
		<td><font face="Arial, Helvetica, sans-serif" size="2">You must remove the setup/ directory and all its contents before using Olate Download. This is for security reasons and will prevent malicious users overwriting your installation.</font></td>
		</tr>
		</table></td>
		</tr>
		</table>';
		exit;
	}
}
   
/***************************************************************************/
// 3. Include db.php
/***************************************************************************/
if ($admin == 1)
{
	require_once('../includes/db.php');
} elseif ($admin == 2) {
	require_once('../../includes/db.php');
} else {
	require_once('includes/db.php');
}
 
/***************************************************************************/
// 4. Include correct functions file
/***************************************************************************/
if ($admin == 1)
{
	require_once('../includes/functions_admin.php');
} elseif ($admin == 2) {
	require_once('../../includes/functions_admin.php');
} else {
	require_once('includes/functions.php');
}

/***************************************************************************/
// 5. Connect to database
/***************************************************************************/
// Connect to the MySQL server
@ $connect = mysql_pconnect($db_servername, $db_username, $db_password);

// Connect to the MySQL server: Error handling
if (!$connect)
{
		$error_message = 'Database Error - '.mysql_errno().': '.mysql_error().' when connecting to database';
		error_handling('includes/init.php', $error_message);
}

// Select database
@ $select = mysql_select_db($db_name);

// Select database: Error handling
if (!$select) 
{
		$error_message = 'Database Error - '.mysql_errno().': '.mysql_error().' when connecting to database';
		error_handling('includes/init.php', $error_message);
}

/***************************************************************************/
// 6. Include config.php
/***************************************************************************/
if ($admin == 1)
{
	require_once('../includes/config.php');
} elseif ($admin == 2) {
	require_once('../../includes/config.php');
} else {
	require_once('includes/config.php');
}

/***************************************************************************/
// 7. Check correct language files are present
/***************************************************************************/
if ($admin == 1)
{
	$path = '../languages/';
} elseif ($admin == 2) {
	$path = '../../languages/';
} else {
	$path = 'languages/';
}
	
// Using the opendir function
$dir_handle = opendir($path); 
	
// Running the while loop
while ($file = readdir($dir_handle)) 
{
	// . and .. are displayed so remove them
	if (($file == '.') ||($file == '..'))
	{
	} else {
		// Check to see if all 3 language files are present
		if ((!file_exists("$path$file/config.php")) || (!file_exists("$path$file/main.php")) || (!file_exists("$path$file/admin.php")))
		{
			// Display error
			$error_message = 'File Check Error: 1 or more of the 3 required language files are missing. Please ensure they are present.';
			error_handling('includes/init.php', $error_message);
		}
	}
} 
	
/***************************************************************************/
// 8. Include language files
/***************************************************************************/
if ($admin == 1)
{
	require_once('../languages/'.$config['language'].'/admin.php');
} elseif ($admin == 2) {
	require_once('../../languages/'.$config['language'].'/admin.php');
} else {
	require_once('languages/'.$config['language'].'/main.php');
}
?>