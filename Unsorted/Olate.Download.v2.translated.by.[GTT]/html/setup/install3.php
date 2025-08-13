<?php 
function install($db_servername, $db_username, $db_password, $db_name, $db_prefix, $urlpath, $username, $password)
{
	/***************************************************************************/
	// 1. Check to make sure all fields were filled in
	/***************************************************************************/
	if(empty($db_servername) || empty($db_username) || empty($db_password) || empty($db_name) || empty($db_prefix) || empty($urlpath) || empty($username) || empty($password))
	{
		echo 'General Error: All fields must be filled out.';
		return false;
	}
	
	/***************************************************************************/
	// 2. Connect to database
	/***************************************************************************/

	// Connect to the MySQL server
	@ $connect = mysql_pconnect($db_servername, $db_username, $db_password);
	
	// Select database
	@ $select = mysql_select_db($db_name);
	
	// Connect to the MySQL server: Error handling
	if (!$connect || !$select)
	{
		echo 'Database Error: '.mysql_error();
		return false;
	}
	
	echo '<ul><li>Sucessfully Connected to Database</li>';
	
	/***************************************************************************/
	// 3. Add tables to database and populate
	/***************************************************************************/

	// Create table _auth
	$query = 'DROP TABLE IF EXISTS '.$db_prefix.'auth ';
	$result = mysql_query($query);
	if (!$result)
	{
		echo '<br>Database Error: '.mysql_error();
		return false;
	}	

	$query = 'CREATE TABLE '.$db_prefix.'auth (';
	$query.= '  id int(11) NOT NULL auto_increment,';
	$query.= '  username varchar(80) NOT NULL default "",';
	$query.= '  password varchar(80) NOT NULL default "",';
	$query.= '  master int(1) NOT NULL default "0",';
	$query.= '  PRIMARY KEY  (id)';
	$query.= ') TYPE=MyISAM AUTO_INCREMENT=6 ;';
	$result = mysql_query($query);
	if (!$result)
	{
		echo '<br>Database Error: '.mysql_error();
		return false;
	}
	
	// Populate table _auth
	$query = 'INSERT INTO '.$db_prefix.'auth VALUES (1, "'.$username.'", "'.md5($password).'", 1);';
	$result = mysql_query($query);
	if (!$result)
	{
		echo '<br>Database Error: '.mysql_error();
		return false;
	}
	
	echo '<li>Sucessfully Created Table '.$db_prefix.'auth</li>';	
	
	// Create table _categories
	$query = 'DROP TABLE IF EXISTS '.$db_prefix.'categories;';
	$result = mysql_query($query);
	if (!$result)
	{
		echo '<br>Database Error: '.mysql_error();
		return false;
	}	
	
	$query = 'CREATE TABLE '.$db_prefix.'categories (';
	$query.= '  id int(11) NOT NULL auto_increment,';
	$query.= '  name mediumtext NOT NULL,';
	$query.= '  PRIMARY KEY  (id)';
	$query.= ') TYPE=MyISAM AUTO_INCREMENT=2 ;';
	$result = mysql_query($query);
	if (!$result)
	{
		echo '<br>Database Error: '.mysql_error();
		return false;
	}	
	
	// Populate table _categories
	$query = 'INSERT INTO '.$db_prefix.'categories VALUES (1, "Test");';
	$result = mysql_query($query);
	if (!$result)
	{
		echo '<br>Database Error: '.mysql_error();
		return false;
	}	
	
	echo '<li>Sucessfully Created Table '.$db_prefix.'categories</li>';
	
	// Create table _config
	$query = 'DROP TABLE IF EXISTS '.$db_prefix.'config;';
	$result = mysql_query($query);
	if (!$result)
	{
		echo '<br>Database Error: '.mysql_error();
		return false;
	}	
	
	$query = 'CREATE TABLE '.$db_prefix.'config (';
	$query.= '  config_name varchar(20) NOT NULL default "",';
	$query.= '  config_value text NOT NULL,';
	$query.= '  PRIMARY KEY  (config_name)';
	$query.= ') TYPE=MyISAM;';
	$result = mysql_query($query);
	if (!$result)
	{
		echo '<br>Database Error: '.mysql_error();
		return false;
	}	
	
	// Populate table _config
	$query = 'INSERT INTO '.$db_prefix.'config VALUES ("notopdownloads", "5");';
	$result = mysql_query($query);
	if (!$result)
	{
		echo '<br>Database Error: '.mysql_error();
		return false;
	}	
	
	$query = 'INSERT INTO '.$db_prefix.'config VALUES ("topdownloadslink", "1");';
	$result = mysql_query($query);
	if (!$result)
	{
		echo '<br>Database Error: '.mysql_error();
		return false;
	}	
	
	$query = 'INSERT INTO '.$db_prefix.'config VALUES ("pages", "15");';
	$result = mysql_query($query);
	if (!$result)
	{
		echo '<br>Database Error: '.mysql_error();
		return false;
	}	
	
	$query = 'INSERT INTO '.$db_prefix.'config VALUES ("version", "2.2.0");';
	$result = mysql_query($query);
	if (!$result)
	{
		echo '<br>Database Error: '.mysql_error();
		return false;
	}	
	
	$query = 'INSERT INTO '.$db_prefix.'config VALUES ("language", "english");';
	$result = mysql_query($query);
	if (!$result)
	{
		echo '<br>Database Error: '.mysql_error();
		return false;
	}	
	
	$query = 'INSERT INTO '.$db_prefix.'config VALUES ("urlpath", "'.$urlpath.'");';
	$result = mysql_query($query);
	if (!$result)
	{
		echo '<br>Database Error: '.mysql_error();
		return false;
	}	
	
	$query = 'INSERT INTO '.$db_prefix.'config VALUES ("sorting", "1");';
	$result = mysql_query($query);
	if (!$result)
	{
		echo '<br>Database Error: '.mysql_error();
		return false;
	}	
	
	$query = 'INSERT INTO '.$db_prefix.'config VALUES ("ratings", "1");';
	$result = mysql_query($query);
	if (!$result)
	{
		echo '<br>Database Error: '.mysql_error();
		return false;
	}	
	
	$query = 'INSERT INTO '.$db_prefix.'config VALUES ("alldownloads", "1");';
	$result = mysql_query($query);
	if (!$result)
	{
		echo '<br>Database Error: '.mysql_error();
		return false;
	}	
	
	$query = 'INSERT INTO '.$db_prefix.'config VALUES ("searchlink", "1");';
	$result = mysql_query($query);
	if (!$result)
	{
		echo '<br>Database Error: '.mysql_error();
		return false;
	}
	
	echo '<li>Sucessfully Created Table '.$db_prefix.'config</li>';	
	
	// Create table _files
	$query = 'DROP TABLE IF EXISTS '.$db_prefix.'files;';
	$result = mysql_query($query);
	if (!$result)
	{
		echo '<br>Database Error: '.mysql_error();
		return false;
	}	
	
	$query = 'CREATE TABLE '.$db_prefix.'files (';
	$query.= '  id int(11) NOT NULL auto_increment,';
	$query.= '  date text NOT NULL,';
	$query.= '  name mediumtext NOT NULL,';
	$query.= '  count int(11) NOT NULL default "0",';
	$query.= '  votes int(11) NOT NULL default "0",';
	$query.= '  rating varchar(5) NOT NULL default "0",';
	$query.= '  location mediumtext NOT NULL,';
	$query.= '  size varchar(100) NOT NULL default "",';
	$query.= '  category int(11) NOT NULL default "0",';
	$query.= '  description_brief longtext NOT NULL,';
	$query.= '  description_full longtext NOT NULL,';
	$query.= '  custom_1_l varchar(20) NOT NULL default "",';
	$query.= '  custom_1_v varchar(40) NOT NULL default "",';
	$query.= '  custom_2_l varchar(20) NOT NULL default "",';
	$query.= '  custom_2_v varchar(40) NOT NULL default "",';
	$query.= '  image mediumtext NOT NULL,';
	$query.= '  PRIMARY KEY  (id)';
	$query.= ') TYPE=MyISAM AUTO_INCREMENT=2 ;';
	$result = mysql_query($query);
	if (!$result)
	{
		echo '<br>Database Error: '.mysql_error();
		return false;
	}	
	
	// Populate table _files
	$query = 'INSERT INTO '.$db_prefix.'files VALUES (1, "04/01/04", "Test Download", 0, 0, "0", "http://www.olate.com", "1", 1, "Brief description", "Full description", "Custom Label 1", "Custom Value 1", "Custom Label 2", "Custom Value 2", "");';
	$result = mysql_query($query);
	if (!$result)
	{
		echo '<br>Database Error: '.mysql_error();
		return false;
	}
	
	echo '<li>Sucessfully Created Table '.$db_prefix.'files</li>';
	
	/***************************************************************************/
	// 4. Write data to db.php
	/***************************************************************************/
	
	// Open file for writing. Delete any contents
	@ $file = fopen('../includes/db.php', 'w+');
	if (!$file)
	{
		echo '<br>File Error: Error whilst attempting to open db.php. Pleas ensure it is writable/it exists.';
		return false;
	}
	
	// Create data to go into db.php
	$data = '<?php '."\r\n";	
	$data.= '// Database server details '."\r\n";	
	$data.= '$db_servername = "'.$db_servername.'";'."\r\n";
	$data.= '$db_username = "'.$db_username.'";'."\r\n";
	$data.= '$db_password = "'.$db_password.'";'."\r\n";
	$data.= '$db_name = "'.$db_name.'";'."\r\n\r\n";
	$data.= 'define(db_prefix, "'.$db_prefix.'");'."\r\n";
	$data.= '?>';
	
	@ $write = fwrite($file, $data);
	if (!$write)
	{
		echo '<br>File Error: Error whilst attempting to write to db.php. Pleas ensure it is writable/it exists.';
		return false;
	}
	
	fclose($file);
	
	echo '<li>Sucessfully Wrote to db.php</li>';
		
	// If it reached here, everything was a success
	echo '</ul>Click the button below to complete the installation';
	return true;
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Setup - Installation - Step 3</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel="stylesheet" type="text/css" href="css/style.css" title="default" />
</head>

<body>
<table width="100%" height="100%" border="0" cellpadding="0" cellspacing="0">
<tr>
<td valign="top"><hr width="1" size="1" color="#FFFFFF">
<table class="admin_main" align="center" border="1">
<tr>
<td class="admin_title"><table class="admin_title_table">
<tr>
<td class="large admin_title">Olate Download - Download Management Script</td>
</tr>
</table></td>
</tr>
<tr>
<td class="admin_breadcrumb">
<table width="99%"  border="0" align="center" cellpadding="0" cellspacing="0">
<tr>
<td width="100%"><strong>Setup - Installation - Step 3 </strong></td>
</tr>
</table></td>
</tr>
<tr>
<td valign="top" bordercolor="#FFFFFF"><table width="100%" border="0" cellspacing="0" cellpadding="0">
<tr>
<td><p>This step installs the tables into your database and populates the dp.php file with the correct data needed to use Olate Download. This process may take a few moments.</p>
<strong>Progress</strong><br><br>
<?php
if (install($_POST['db_servername'], $_POST['db_username'], $_POST['db_password'], $_POST['db_name'], $_POST['db_prefix'], $_POST['urlpath'], $_POST['username'], $_POST['password']) == false)
{
	echo '<br><br>Before installation can continue, you need to rectify the problem(s) indicated above. <a href="JavaScript:history.go(-1);">Click here to go back</a>.';
} else {
?>
<p>
<form action="install4.php" method="post">
<input type="submit" value="Continue" name="Submit">
<input name="urlpath" type="hidden" id="urlpath" value="<?= $_POST['urlpath']; ?>">
</form>
<?php
} 
?></p>
</td>
</tr>
</table></td>
</tr>
<tr>
<td height="25" valign="middle" bordercolor="#FFFFFF" bgcolor="#E3E8EF">
<!--Begin Credit Line. Please leave-->
<div align="center"><span class="small"><a href="http://www.olate.com" target="_blank">Powered 
by Olate Download v2.2.0 </a></span></div></td>
</tr>
</table>
<hr width="1" size="1" color="#FFFFFF"></td>
</tr>
</table>
</body>
</html>
