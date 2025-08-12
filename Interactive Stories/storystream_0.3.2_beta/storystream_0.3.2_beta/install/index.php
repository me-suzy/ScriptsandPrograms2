<html>
<head>
<title>StoryStream Installer</title>
<link href="install.css" rel="stylesheet" type="text/css" />
</head>
<body>
<h1>StoryStream Installer </h1>

<?

error_reporting (0);

function write_dbinfo ($file, $host,$dbname,$user,$pass)
{
	$absPath = realpath($file);
	$configFile = '
<?php
$db_type = "mysql";		// MYSQL IS THE ONLY DB TESTED RIGHT NOW
$db_name = "'.$dbname.'";
$db_host = "'.$host.'";
$db_user = "'.$user.'";
$db_pass = "'.$pass.'";
$db_table_prefix = "";	// NOT USED (LEAVE EMPTY)
?>
	';	
	
	$fh = fopen ($absPath, 'w+');
	if ($fh)
	{
		fwrite ($fh, $configFile);
		fclose ($fh);
		echo '<div class="success">Successfully created the dbconfig.php file with the given settings.</div>';
	}
	else {
		echo '<div class="error">Failed to write out the database config file (dbconfig.php).<br/>Before you can
				run StoryStream, you must edit the config/dbconfig.php file with 
				the correct database information. </div>';
	}
}

function make_writable ($filename, $isdir)
{
	if (!file_exists ($filename))
	{
		if (!$isdir)
		{
			$fh = fopen ($filename, 'w+');
			if (!$fh) {
				echo '<div class="error">The following file could not be created: '.$filename.'</div>';
				return false;
			}
			
			fclose ($fh);
			return true;
		}
		else
		{
			echo '<div class="error">Directory not found: '.$filename.'</div>';
			return false;
		}
	}
	
	if (!is_writable ($filename))
	{
		// Now try to make the templates_c directory readable.
		$absPath = realpath ($filename);
		if ($absPath) {
			// Found absolute path now trying chmod
			if (!chmod ($absPath,0777))
			{
				// not a failure case, but let them know.
				echo '<div class="error">Failed to modify write permissions on: '.$absPath.'</div>';
				return false;
			}
		}
	}
	
	return true;
}

function populate_db($host, $user, $pass, $dbname, $sqlfile='storystream.sql') 
{
	$errors = array ();

	$link = mysql_connect ($host,$user,$pass);
	if (!$link)
	{
		echo '<div class="error">Failed to connect to the specified host</div>';
		return false;
	}
	
	if (!mysql_select_db($dbname, $link))
	{
		echo '<div class="error">Failed to find specified database name</div>';
		return false;
	}
	
	$mqr = @get_magic_quotes_runtime();
	@set_magic_quotes_runtime(0);
	$query = fread(fopen("sql/".$sqlfile, "r"), filesize("sql/".$sqlfile));
	@set_magic_quotes_runtime($mqr);
	$pieces  = split_sql($query);

	for ($i=0; $i<count($pieces); $i++) {
		$pieces[$i] = trim($pieces[$i]);
		if(!empty($pieces[$i]) && $pieces[$i] != "#") 
		{
			// Only do this if we support table name prefixes
			// $pieces[$i] = str_replace( "#__", $DBPrefix, $pieces[$i]);
			
			if (!$result = mysql_query ($pieces[$i])) {
				$errors[] = array ( mysql_error(), $pieces[$i] );
			}
		}
	}
	
	if (count ($errors) == 0)
	{
		return true;
	}
	else
	{
		foreach ($errors as $error) {
			echo '<div class="error"> DB Error: '.$error[0].'<div>'.$error[1].'</div></div>';		
		}
		return false;
	}
}

function split_sql($sql) 
{
	$sql = trim($sql);
	$sql = ereg_replace("\n#[^\n]*\n", "\n", $sql);

	$buffer = array();
	$ret = array();
	$in_string = false;

	for($i=0; $i<strlen($sql)-1; $i++) {
		if($sql[$i] == ";" && !$in_string) {
			$ret[] = substr($sql, 0, $i);
			$sql = substr($sql, $i + 1);
			$i = 0;
		}

		if($in_string && ($sql[$i] == $in_string) && $buffer[1] != "\\") {
			$in_string = false;
		}
		elseif(!$in_string && ($sql[$i] == '"' || $sql[$i] == "'") && (!isset($buffer[0]) || $buffer[0] != "\\")) {
			$in_string = $sql[$i];
		}
		if(isset($buffer[1])) {
			$buffer[0] = $buffer[1];
		}
		$buffer[1] = $sql[$i];
	}

	if(!empty($sql)) {
		$ret[] = $sql;
	}
	return($ret);
}

// Get the db info

$submit = @$_POST['Submit'];
if ($submit)
{
	$hostname = @$_POST['host'];
	$dbase = @$_POST['dbase'];
	$user = @$_POST['user'];
	$pass = @$_POST['password'];
}
else
{
	$hostname = 'localhost';
	$dbase = 'db_storystream';
	$user = '';
	$pass = '';
}

$error = false;

if ($submit != '')
{
	$error = false;
	// an attempt was made - see if everything is okay
	if (!$hostname) {
		echo '<div class="error">You must specify a hostname (e.g. localhost)</div>';
		$error = true;
	}
	if (!$dbase) {
		echo '<div class="error">You must specify a database name(e.g. db_storystream)</div>';
		$error = true;
	}
	if (!$user) {
		echo '<div class="error">You must specify a database username (e.g. root)</div>';
		$error = true;
	}
	if (!$pass) {
		echo '<div class="error">You must specify a password</div>';
		$error = true;
	}
	
	if (!$error) {
		// Try to setup the database
		if (!populate_db ($hostname, $user, $pass,$dbase))
		{
			echo '<div class="error">Database install failed.</div>';
			$error = true;
		}
		else {

			echo '<div class="success">StoryStream database tables were setup successfully</div>';

			if (!make_writable ('../config/', true))
			{
				$error = true;
			}
			else 
			{			
				// only do this if we could make the config directory writeable.
				if (make_writable ('../config/dbconfig.php', false))
				{				
					// Write theconfiguration file
					write_dbinfo ('../config/dbconfig.php', $hostname, $dbase, $user, $pass);
				}
				else
				{
					$error = true;
					echo '<div class="error">Failed to write to the database config file: the config directory must have create permissions for the script user (0777, for example)</div>';
				}
			}

			if (!make_writable ('../include/smarty/templates_c/', true))
			{
				$error = true;
			}
			else 
			{
				echo '<div class="success">Permissions on "include/smarty/templates_c" are set correctly</div>';
			}
		}
	}
}

if (!$submit)
{
	if (!is_writable ('../config'))
	{
		echo '<div class="message">Note: Your database configuration folder is not writable.  The 
				installer will attempt to change that during installation, but not all servers 
				will allow scripts to change permissions on a folder.  To change the permissions yourself, 
				login to your server via FTP and change the file "[root]/config" to the correct 
				permissions (0777 is a safe bet).</div>';
	}
}
else {
	if ($error) {
		echo '<div class="message">Remember: You must address any <span class="error">red labeled</span> messages before proceeding</div>';
	}
	else {
		echo '<div> <a href="../index.php">Click here</a> to visit your new StoryStream site </div>';
	}
}

if (!$submit || $error){
?>
<link href="install.css" rel="stylesheet" type="text/css" />

<form action="index.php" method="post" name="frmDbInfo" target="_top">
	<table align="left" width="200" border="0">
		<caption>
			Database Information
		</caption>
		<tr>
			<td>Hostname</td>
			<td><input type="text" name="host" value="<?php echo $hostname?>"></td>
		</tr>
		<tr>
			<td>Database</td>
			<td><input type="text" name="dbase" value="<?php echo $dbase?>"></td>
		</tr>
		<tr>
			<td><label>User</label></td>
			<td><input type="text" name="user" value="<?php echo $user?>"></td>
		</tr>
		<tr>
			<td>Password</td>
			<td><input type="password" name="password" value="<?php echo $pass?>"></td>
		</tr>
		<tr>
			<td colspan="2"><label>
				<div align="center">
					<input type="submit" name="Submit" value="Install">
					</div>
			</label></td>
		</tr>
	</table>
</form>
<?
}
?>
</body>
</html>