<?php

// Installation file.

error_reporting(E_ERROR);
$TABLE_PREFIX = "plogger_";

if (file_exists("plog-config.php")) {
	require_once("plog-config.php");
	if (@mysql_connect(PLOGGER_DB_HOST,PLOGGER_DB_USER,PLOGGER_DB_PW)) {
		die("Plogger has already been installed!");
	};
};

foreach($_POST as $key => $val) $_POST[$key] = stripslashes($val);

$config_keys = array("db_user","db_pass","db_name","gallery_name","username","password");
$config_vars = array();
foreach($config_keys as $config_key) {
	$config_val = !empty($_POST[$config_key]) ? $_POST[$config_key] : "";
	$config_vars[$config_key] = $config_val;
};


if (!empty($_POST))
{
	$cfg_file = "<?php\n";
	$cfg_file .= '// this is the file used to connect to your database.'."\n";
	$cfg_file .= '// you must change these values in order to run the gallery.'."\n";

	$cfg_file .= 'define("PLOGGER_DB_HOST","'.$_POST["db_host"].'");'."\n";
	$cfg_file .= 'define("PLOGGER_DB_USER","'.$_POST["db_user"].'");'."\n";
	$cfg_file .= 'define("PLOGGER_DB_PW","'.$_POST["db_pass"].'");'."\n";
	$cfg_file .= 'define("PLOGGER_DB_NAME","'.$_POST["db_name"].'");'."\n";

	$cfg_file .= "?>\n";

};

$errors = $config_errors = array();
$output = '<form action="'.$_SERVER["PHP_SELF"].'" method="post">';

if (isset($_POST["action"]) && $_POST["action"] == "install"){
	// First, check for missing data.
	if (strlen(trim($_POST["db_host"])) == 0){
		$errors[] = 'Please enter the name of your MySQL host.';
	}
	if (strlen(trim($_POST["db_user"])) == 0){
		$errors[] = 'Please enter the MySQL username.';
	}
	if (strlen(trim($_POST["db_name"])) == 0){
		$errors[] = 'Please enter the MySQL database name.';
	}
	if (strlen(trim($_POST["db_pass"])) == 0){
		$errors[] = 'Please enter the MySQL password.';
	}
	if (strlen(trim($_POST["gallery_name"])) == 0){
		$errors[] = 'Please enter a gallery name.';
	}
	if (strlen(trim($_POST["username"])) == 0){
		$errors[] = 'Please enter a username.';
	}
	if (strlen(trim($_POST["password"])) == 0){
		$errors[] = 'Please enter a password.';
	}
	
	$files_to_read = array("./","./admin","./css","./images","./lib","./thumbs","./uploads");
	
	foreach($files_to_read as $file){
		if (!is_readable(realpath($file))){
			$errors[] = "The path ".realpath($file)." (".$file.") is not readable.";
		}
	}
	
	$files_to_write = array("./","./thumbs","./images", "./uploads");
	
	foreach($files_to_write as $file){
		if (!is_writable(realpath($file))){
			$errors[] = 'The path '.realpath($file).' is not writable by the Web server.';
		}
	}
	
	// Check for the correct database information.	
	if (count($errors) == 0){
		if(!@mysql_connect($_POST["db_host"],$_POST["db_user"],$_POST["db_pass"])){
			$errors[] = 'Plogger could not connect to the MySQL host with the information you provided.';
		}
		elseif(!mysql_select_db($_POST["db_name"])){
			$errors[] = 'Plogger connected to the MySQL host, but could not find the database '.$_POST["db_name"].'.';
		}
	}
	
	// Try to write the plog-config.php file.
	if (count($errors) == 0){
		// Open the file for writing.

		$handle = fopen(realpath("./plog-config.php"),"w");
		
		if ($handle){
			fwrite($handle, $cfg_file);
			fclose($handle);
		}
		else{
			// Try chmodding the file.
			@chmod(realpath("./plog-config.php"), 0666);
			
			$handle = fopen(realpath("./plog-config.php"),"w");
			
			if ($handle){
				fwrite($handle, $cfg_file);
				fclose($handle);
			}
			else{
				$config_errors[] = "Plogger could not write the plog-config.php file.  Either make this file writable by the Web server and click 'Try Again', or replace the contents of the current plog-config.php file with the following code:<br /><pre>" . htmlentities($cfg_file) . "</pre>If you choose to overwrite the file manually, do so, and don't forget to change the permissions back after you overwrite it.  Proceed to your <a href=\"admin\">Administrative Tools</a> when you have fixed the connection file.";
			}
		}

		// since 4.1 MySQL has support for specifying character encoding for tables
		// and I really want to  use it if avaiable. So we need figure out what version
		// we are running on and to the right hting
		$mysql_version = mysql_get_server_info();
		$mysql_charset_support = "4.1";
		$default_charset = "";

		if (1 == version_compare($mysql_version,$mysql_charset_support))
		{
			$default_charset = "DEFAULT CHARACTER SET UTF8";
		};

		// first lets check if the tables have already been installed
		
		$query = "CREATE TABLE `".$TABLE_PREFIX."collections` (
		  `name` varchar(128) NOT NULL default '',
		  `description` varchar(255) NOT NULL default '',
		  `path` varchar(255) NOT NULL default '',
		  `id` int(11) NOT NULL auto_increment,
		  `thumbnail_id` int(11) NOT NULL,
			PRIMARY KEY  (id)
		) Type=MyISAM $default_charset";
		mysql_query($query) or die(mysql_error().'<br /><br />'. $query);
		
		$query = "CREATE TABLE `".$TABLE_PREFIX."albums` (
		  `name` varchar(128) NOT NULL default '',
		  `id` int(11) NOT NULL auto_increment,
		  `description` varchar(255) NOT NULL default '',
		  `path` varchar(255) NOT NULL default '',
		  `parent_id` int(11) NOT NULL default '0',
		  `thumbnail_id` int(11) NOT NULL,
		  PRIMARY KEY  (id),
			INDEX pid_idx (parent_id)
			) Type=MyISAM $default_charset";
		mysql_query($query) or die(mysql_error().'<br /><br />'. $query);

		$query = "CREATE TABLE `".$TABLE_PREFIX."config` (
			`max_thumbnail_size` int(11) NOT NULL default '0',
			`max_display_size` int(11) NOT NULL default '0',
			`thumb_num` int(11) NOT NULL default '0',
			`admin_username` varchar(64) NOT NULL default '',
			`admin_password` varchar(64) NOT NULL default '',
			`admin_email` varchar(50) NOT NULL default '',
			`date_format` varchar(64) NOT NULL default '',
			`compression` int(11) NOT NULL default '',
			`default_sortby` varchar(20) NOT NULL default '',
			`default_sortdir` varchar(5) NOT NULL default '',
			`gallery_name` varchar(255) NOT NULL default '',
			`allow_dl` smallint(1) NOT NULL default '0',
			`allow_comments` smallint(1) NOT NULL default '1',
			`allow_print` smallint(1) NOT NULL default '1',
			`truncate` int(11) NOT NULL default '12',
			`square_thumbs` tinyint default 1,
			`feed_num_entries` int(15) NOT NULL default '15',
			`rss_thumbsize` int(11) NOT NULL default '400',
			`feed_title` text NOT NULL,
			`use_mod_rewrite` tinyint NOT NULL default '0',
			`comments_notify` tinyint NOT NULL default '1',
			`feed_language` varchar(255) NOT NULL default 'en-us'
			
		) Type=MyISAM $default_charset";
		mysql_query($query) or die(mysql_error().'<br /><br />'. $query);
		$query = "CREATE TABLE `".$TABLE_PREFIX."pictures` (
		  `path` varchar(255) NOT NULL default '',
		  `parent_album` int(11) NOT NULL default '0',
		  `parent_collection` int(11) NOT NULL default '0',
		  `caption` mediumtext NOT NULL,
		  `id` int(11) NOT NULL auto_increment,
		  `date_modified` timestamp(14) NOT NULL,
		  `date_submitted` timestamp(14) NOT NULL,
		  `EXIF_date_taken` varchar(64) NOT NULL default '',
		  `EXIF_camera` varchar(64) NOT NULL default '',
		  `EXIF_shutterspeed` varchar(64) NOT NULL default '',
		  `EXIF_focallength` varchar(64) NOT NULL default '',
		  `EXIF_flash` varchar(64) NOT NULL default '',
		  `EXIF_aperture` varchar(64) NOT NULL default '',
		  `allow_comments` int(11) NOT NULL default '1',
		  PRIMARY KEY  (id),
			INDEX pa_idx (parent_album),
			INDEX pc_idx (parent_collection)
		) Type=MyISAM $default_charset";
		mysql_query($query) or die(mysql_error().'<br /><br />'. $query);
		
				$query = "CREATE TABLE `".$TABLE_PREFIX."comments` (
		  `id` int(11) NOT NULL auto_increment,
			`parent_id` int(11) NOT NULL default '0',
		  `author` varchar(64) NOT NULL default '',
		  `email` varchar(64) NOT NULL default '',
		  `url` varchar(64) NOT NULL default '',
		  `date` datetime NOT NULL,
		  `comment` longtext NOT NULL,
		  `ip` char(64),
		  `approved` tinyint default '1',
			PRIMARY KEY  (id),
			INDEX pid_idx (parent_id),
			INDEX approved_idx (approved)
		) Type=MyISAM $default_charset";
		mysql_query($query) or die(mysql_error().'<br /><br />'. $query);
		
		$query = "INSERT INTO `".$TABLE_PREFIX."config` 
			( `compression`,
			  `max_thumbnail_size`,
		  	`max_display_size`,
		  	`thumb_num`,
		  	`admin_username`,
		  	`admin_password`,
		  	`date_format`,
			`feed_title`,
		   `gallery_name`)
		   VALUES
		   (75,
			  100,
		    500,
		    20,
		    '".mysql_escape_string($_POST["username"])."',
		    MD5('".mysql_escape_string($_POST["password"])."'),
		    'n.j.Y',
		    'Plogger Photo Feed',
		    '".mysql_escape_string($_POST["gallery_name"])."')";
		mysql_query($query) or die(mysql_error().'<br /><br />'. $query);
		
		if (count($config_errors) == 0){
			header("Location: ./admin/index.php");
			exit;
		}
		else{
			$set_config_error = true;
			
			// Display the config error information
			
			$output .= '
				<input type="hidden" name="action" value="try_again" />
				<input type="hidden" name="db_host" value="'.$_POST["db_host"].'" />
				<input type="hidden" name="db_user" value="'.$_POST["db_user"].'" />
				<input type="hidden" name="db_pass" value="'.$_POST["db_pass"].'" />
				<input type="hidden" name="db_name" value="'.$_POST["db_name"].'" />
				<input type="hidden" name="username" value="'.$_POST["username"].'" />
				<input type="hidden" name="password" value="'.$_POST["password"].'" />
				<input type="hidden" name="gallery_name" value="'.$_POST["gallery_name"].'" />
				<p>The following errors occurred:</p><ul class="error">';
			
			foreach($config_errors as $error){
				$output .= '<li>'.$error.'</li>';
			}
			
			$output .= '</ul>
						<table>
							<tr>
								<td class="submitButtonRow" colspan="2"><input type="submit" name="submit" value="Try Again" /></td>
							</tr>
						</table>';
		}
	}
}

if(isset($_POST["action"]) && $_POST["action"] == "try_again"){
	// The user has done the database setup, but the plog-config.php file has not been written.
	$handle = @fopen(realpath("./plog-config.php"),"w");
	if ($handle){
		fwrite($handle, $cfg_file);
		fclose($handle);
	}
	else{
		$config_errors[] = "Plogger could not write the plog-config.php file.  Either make this file writable by the Web server and click 'Try Again', or replace the contents of the current plog-config.php file with the following code:<br /><pre>" . htmlentities($cfg_file) . "</pre>If you choose to overwrite the file manually, do so, and don't forget to change the permissions back after you overwrite it.  Proceed to your <a href=\"admin\">Administrative Tools</a> when you have fixed the connection file.";
	}
	
	if (count($config_errors) == 0){
		header("Location: ./admin/index.php");
		exit;
	}
	else{
		$output .= '
				<input type="hidden" name="action" value="try_again" />
				<input type="hidden" name="db_host" value="'.stripslashes($_POST["db_host"]).'" />
				<input type="hidden" name="db_user" value="'.stripslashes($_POST["db_user"]).'" />
				<input type="hidden" name="db_pass" value="'.stripslashes($_POST["db_pass"]).'" />
				<input type="hidden" name="db_name" value="'.stripslashes($_POST["db_name"]).'" />
				<input type="hidden" name="username" value="'.stripslashes($_POST["username"]).'" />
				<input type="hidden" name="password" value="'.stripslashes($_POST["password"]).'" />
				<input type="hidden" name="gallery_name" value="'.stripslashes($_POST["gallery_name"]).'" />
				<p>The following errors occurred:</p><ul class="error">';
		
		foreach($config_errors as $error){
			$output .= '<li>'.$error.'</li>';
		}
		
		$output .= '</ul>
					<table>
						<tr>
							<td class="submitButtonRow" colspan="2"><input type="submit" name="submit" value="Try Again" /></td>
						</tr>
					</table>';
	}
}
elseif(empty($set_config_error)){
	$db_host = (isset($_POST["action"])) ? stripslashes($_POST["db_host"]) : 'localhost';
		
	if (count($errors) > 0){
		$output .= '<p>The following errors occurred:</p><ul class="error">';
		
		foreach($errors as $error){
			$output .= '<li>'.$error.'</li>';
		}
		
		$output .= '</ul>';
	}
	
	$output .= '	<input type="hidden" name="action" value="install" />
		<table>
			<tr>
				<td colspan="2">
					<div id="navcontainer">
						<h1>Database Setup</h1>
						
					</div>
				</td>
			</tr>
			<tr>
				<td class="form_label"><label for="db_host">MySQL host:</label></td>
				<td class="form_input"><input type="text" name="db_host" id="db_host" value="'.$db_host.'" /></td>
			</tr>
			<tr>
				<td class="form_label"><label for="db_user">MySQL Username:</label></td>
				<td class="form_input"><input type="text" name="db_user" id="db_user" value="'.$config_vars["db_user"].'" /></td>
			</tr>
			<tr>
				<td class="form_label"><label for="db_password">MySQL Password:</label></td>
				<td class="form_input"><input type="password" name="db_pass" id="db_pass" value="'.$config_vars["db_pass"].'" /></td>
			</tr>
			<tr>
				<td class="form_label"><label for="db_name">MySQL Database:</label></td>
				<td class="form_input"><input type="text" name="db_name" id="db_name" value="'.$config_vars["db_name"].'" /></td>
			</tr>
			<tr>
				<td colspan="2">
					<div id="navcontainer">
						<h1>Administrative Setup</h1>
					</div>
				</td>
			</tr>
			<tr>
				<td class="form_label"><label for="gallery">Gallery Name:</label></td>
				<td class="form_input"><input type="text" name="gallery_name" id="gallery" value="'.$config_vars["gallery_name"].'" /></td>
			</tr>
			<tr>
				<td class="form_label"><label for="username">Username:</label></td>
				<td class="form_input"><input type="text" name="username" id="username" value="'.$config_vars["username"].'" /></td>
			</tr>
			<tr>
				<td class="form_label"><label for="password">Password:</label></td>
				<td class="form_input"><input type="password" name="password" id="password" value="'.$config_vars["password"].'" /></td>
			</tr>
			<tr>
				<td class="submitButtonRow" colspan="2"><input type="submit" name="submit" id="submit" value="Install" /></td>
			</tr>
		</table>';
}

$output .= '</form>';

echo '
	<html>
		<head>
			<title>Install Plogger</title>
			<link rel="stylesheet" type="text/css" href="admin/../css/admin.css">
		</head>
		<body>
			<img src="graphics/plogger.gif" alt="Plogger">
			<h1>Install Plogger</h1>
			<p>Welcome to the Plogger installation page.  To install, simply fill out the following form.  If there are any errors, such as unexecutable files or incorrect data, you will be notified and ask to fix them before the installation will continue.  After the installation has finished, you will be redirected to your Plogger home page.</p>
			'.$output.'
		</body>
	</html>';

?>
