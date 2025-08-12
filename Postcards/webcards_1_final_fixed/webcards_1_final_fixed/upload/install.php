<?php

/* vim: set expandtab tabstop=4 shiftwidth=4: */
// +----------------------------------------------------------------------------------+
// | WebCards Version 1.0 - A powerful, easy to configure e-card system               |
// | Copyright (C) 2003  Chris Charlton (corbyboy@hotmail.com)                        |
// |                                                                                  |
// |     This program is free software; you can redistribute it and/or modify         |
// |     it under the terms of the GNU General Public License as published by         |
// |     the Free Software Foundation; either version 2 of the License, or            |
// |     (at your option) any later version.                                          |
// |                                                                                  |
// |     This program is distributed in the hope that it will be useful,              |
// |     but WITHOUT ANY WARRANTY; without even the implied warranty of               |
// |     MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the                |
// |     GNU General Public License for more details.                                 |
// |                                                                                  |
// |     You should have received a copy of the GNU General Public License            |
// |     along with this program; if not, write to the Free Software                  |
// |     Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA    |
// |                                                                                  |
// | Authors: Chris Charlton <corbyboy@hotmail.com>                                   |
// | Official Homepage: http://webcards.sourceforge.net                               |
// | Project Homepage: http://www.sourceforge.net/projects/webcards                   |
// +----------------------------------------------------------------------------------+
//
// $Id: install.php,v 1.00 2003/08/10 22:21:23 chrisc Exp $

//This is the installation file for version 1 final release.
?>


<html>
<head>
<link rel="stylesheet" type="text/css" href="./templates/webcards.css">
<title>Install WebCards</title>
</head>
<body>
<table width="100%" border="0" cellpadding="4" cellspacing="0" class="noborder">
<tr><td>
<img src="./site_images/setup.gif">

<h1>Welcome to WebCards Installation</h1>You are currently installing WebCards version 1.0 final.<br /><br />
<?php

//error_reporting  (E_ERROR | E_WARNING | E_PARSE);
set_magic_quotes_runtime(0);

switch($HTTP_GET_VARS['stage'])
{
	case 'add_config':
		echo add_config();
		break;
	case 'form':
		echo show_form();
		break;
	case 'perms_check':
		echo check_file_perms();
		break;
	default:
	pre_check();
		if(count($error_array) > 0)
		{
			echo "<span class=\"title\">Errors were found</span><br /><br />The following errors were found and need to be corrected before installation can continue.\n<ul>";
			foreach($error_array as $error)
			{
			echo "<li>" . $error . "</li>";
			}
			echo "</ul>";
			echo "<a href=\"" . $HTTP_SERVER_VARS['PHP_SELF'] . "\">Click here to try again</a>";
		}
		else
		{
			echo "<span class=\"title\">Installation Ready</span><br /><br />\n\nAn integrity check was completed and all the necessary files are in place to start installation.<br /><br />The next step will ensure all the required files have sufficient permissions.<br /><br /> Please click on the button below to start.\n\n";
			echo "<form action=\"install.php?stage=perms_check\" method=\"post\">\n\n<br /><input type=\"submit\" value=\"S T A R T   I N S T A L L A T I O N\"></form>";
		}
		break;
}

$error_array = array();

function pre_check()
{
global $error_array;
	$required_dirs = array("./source/drivers", "./export", "./images", "./lang", "lang/English", "./site_images", "./source", "./source/modules", "./templates", "./templates/admin");
	foreach ($required_dirs as $dir)
	{
		if(!is_dir($dir))
		{
			$error_array[] = "Cannot find the \"" . $dir . "\" directory.";
		}
	}
	$required_files = array("./admin.php", "./config.php", "./source/functions.php", "./pickup.php");
	foreach ($required_files as $file)
	{
		if(!file_exists($file))
		{
			$error_array[] = "Cannot find the file \"" . $file . "\"";
		}
	}
	if(file_exists("./lock.cgi"))
	{
		$error_array[] = "The installer is locked. Please remove the file \"lock.cgi\" from the Webcards directory to continue.";
	}
}

function check_file_perms()
{

	$files_to_write = array("./config.php", "./templates/template.html", "./lang/English/email.php", "./lang/English/global.php", "./lang/English/index.php", "./lang/English/pickup.php", "./lang/English/admin/ad_ban.php", "./lang/English/admin/ad_category.php", "./lang/English/admin/ad_config.php", "./lang/English/admin/ad_images.php", "./lang/English/admin/ad_index.php", "./lang/English/admin/ad_lang.php", "./lang/English/admin/ad_misc.php", "./lang/English/admin/ad_template.php", "./lang/English/admin/ad_toolbox.php", "./lang/English/admin/ad_users.php", "./templates/main_form.html", "./templates/preview_card.html", "./templates/render_card.html", "./templates/select_img.html", "./templates/view_image_stats.html", "./templates/styles/Default.css");
	$fail_count = 0;

	echo "Testing for correct permissions on necessary files.<br /><br />";
	echo "<table class=\"noborder\" cellspacing=\"4\" cellpadding=\"3\" width=\"50%\">";
	foreach($files_to_write as $f)
	{
		echo "<tr><td>" . $f . "</td><td>";
		if(is_writeable($f))
		{
			echo "<span style=\"color: green\";>pass</span>";
		}
		else
		{
			echo "<span style=\"color: red\";>fail</span>";
			$fail_count ++;
		}
		echo "</td></tr>";
	}
	echo "</table>";

	if($fail_count == 0)
	{
		echo "<form action=\"install.php?stage=form\" method=\"post\"><b>Test passed</b><br /><br />\n\nClick the button below to proceed.<br /><br /><input type=\"submit\" value=\"N  E  X  T    S  T  A  G  E\"></form>";
	}
	else
	{
		echo "<br /><br /><b>Test failed</b><br /><br />Please ensure the files marked <span style=\"color: red\";>fail</span> have sufficient permissions for the script to write to them. Try a chmod value of 0775. If that doesn't work, try 0777.";
	}

}

function show_form()
{
global $HTTP_SERVER_VARS;
	$html = "";
	$html .= "<span style=\"color:red; font-weight:bold;\">Please note that if the tables you specify below already exist in the database all data in them will be deleted.</span>";
	$html .= "<form action=\"install.php?stage=add_config\" method=\"post\">\n";
	$html .= "<span style=\"font-size:25px;\">1</span>.&nbsp;&nbsp;This first stage of the installation process requires you to fill in the directory and URL that WebCards will use:<br><br>\n";
	$html .= "Base Directory (with a trailing slash):<br>\n";

	$slash = eregi("win", PHP_OS) ? "\\" : "/";	

	$html .= "<input type=\"text\" size=\"100\" name=\"dir\" value=\"" . getcwd() . $slash . "\"><br><br>\n";
	$url = preg_replace("/install.php/i", "", $HTTP_SERVER_VARS['SERVER_NAME'] . $HTTP_SERVER_VARS['SCRIPT_NAME']);
	$html .= "Base URL (with trailing slash):<br>\n";
	$html .= "<input type=\"text\" size=\"100\" name=\"url\" value=\"http://" . $url . "\"><br><br>\n";

	$html .= "<hr><span style=\"font-size:25px;\">2</span>.&nbsp;&nbsp;The next stage requires your database information.<br><br>";
	$html .= "The following database drivers are available to you. Only choose an option that your host will support:<br>\n<select name=\"db_driver\">\n";
	$dp = @opendir("./source/drivers");
	if(!$dp)
	{
		die("Unable to open the /source/drivers/ directory.<br><br>Try the following solutions: Ensure the directory exists.<br>Ensure the directory has proper permissions.<br>Ensure the directory contains at least 1 database driver file.");
	}
	while($file = readdir($dp))
	{
	if($file!="." && $file!=".." && $file!="index.html")
	{
		$file_parts = explode(".", $file);
		$html .= "<option value=\"" . $file_parts[0] . "\">$file_parts[0]</option>\n";
	}
	}
	$html .= "</select>\n<br><br>\n";
	$html .= "Database host:<br>\n<input size=\"100\" type=\"text\" name=\"dbhost\" value=\"localhost\"><br><br>";
	$html .= "Database table prefix (optional) - to prevent conflicts with other programs:<br>\n<input size=\"100\" type=\"text\" name=\"dbprefix\" value=\"wc_\"><br><br>";
	$html .= "Database username (account must already be set up):<br>\n<input size=\"100\" type=\"text\" name=\"dbuser\"><br><br>";
	$html .= "Database password (account must already be set up):<br>\n<input size=\"100\" type=\"password\" name=\"dbpass\"><br><br>";
	$html .= "Database where WebCards will be installed. Due to restrictions on some webhosts, this database must already exist:<br>\n<input size=\"100\" type=\"text\" name=\"dbname\" value=\"webcards\"><br><br><hr>";

	$html .= "<span style=\"font-size:25px;\">3</span>.&nbsp;&nbsp;This final stage will require you to set up a base administration account for using the WebCards administration centre.<br><br>Please be aware that whilst other administration accounts can be added and removed at will, this base administration account cannot be removed.<br><br>";
	$html .= "Administrator username:<br><input size=\"100\" type=\"text\" name=\"admin_name\"><br><br>";
	$html .= "Administrator password:<br><input size=\"100\" type=\"password\" name=\"admin_pass1\"><br><br>";
	$html .= "Administrator password (to confirm):<br><input size=\"100\" type=\"password\" name=\"admin_pass2\"><br><br>";
	$html .= "Administrator email address:<br><input size=\"100\" type=\"text\" name=\"email_from\" value=\"" . $HTTP_SERVER_VARS['SERVER_ADMIN'] . "\"><br><br>";
	$html .= "<br><input type=\"submit\" style=\"font-weight:bold;\" value=\"          C  O  N  T  I  N  U  E  .  .  .  .          \">\n";
	return $html;
}

function add_config()
{
global $HTTP_POST_VARS, $conf;

	//Three main things to do to complete installation

	// Add slashes to dir and url if they are not found
	$slash = eregi("win", PHP_OS) ? "\\" : "/";

	if(!file_exists($HTTP_POST_VARS['dir'] . "config.php"))
	{
		if(!file_exists($HTTP_POST_VARS['dir'] . $slash . "config.php"))
		{
			die("Unable to locate the file \"" . stripslashes($HTTP_POST_VARS['dir']) . "config.php\". Ensure the base directory value is correct");
		}
		$HTTP_POST_VARS['dir'] .= $slash;
	}

	// Define an array of stuff that must be filled in
	$non_empty = array("dir" => "Base Directory", "url" => "Base URL", "db_driver" => "Database driver", "dbhost" => "Database host", "dbuser" => "Database username", "dbname" => "Database name");
	foreach($non_empty as $k => $v)
	{
		if(!isset($HTTP_POST_VARS[$k]) || $HTTP_POST_VARS[$k] == "")
		{
			die("Some data was missing.<br /><br />A value for " . $v . " must be filled in.");
		}
	}

	if(!preg_match("/\/$/", $HTTP_POST_VARS['url']))
	{
		$HTTP_POST_VARS['url'] .= "/";
	}

	if ($HTTP_POST_VARS['admin_name'] == "" || $HTTP_POST_VARS['admin_pass1'] == "" || $HTTP_POST_VARS['admin_pass2'] == "")
	{
		die("Please go back an ensure all administrator account fields are filled in.");
	}

	if ($HTTP_POST_VARS['admin_pass1'] != $HTTP_POST_VARS['admin_pass2'])
	{
		die("Please go back an ensure that the two passwords match.");
	}
	// Now we are sure all data is OK, proceed with updating the config file

	require $HTTP_POST_VARS['dir'] . "config.php";

	foreach ($HTTP_POST_VARS as $k => $v)
	// Take our submitted data, clean it up and add it to the $conf array
	{
		$v = preg_replace("/\n/", "", $v);
		$v = preg_replace("/'/", "&#39;", stripslashes($v));
		$conf[$k] = $v;
	}

	$str = "<?php\n";

	foreach ($conf as $k => $v)
	{
		$v = addslashes($v);
		if ($k!="admin_name" && $k!="admin_pass1" && $k!="admin_pass2")
		{
			$str .= "\$conf['$k'] = \"$v\";\n";
		}
	}

	$str .= "?>";
	if ($fp = @fopen($conf['dir'] . "config.php", "w"))
	{
		@fwrite($fp, $str, strlen($str));
		@fclose($fp);
		$output = "Checking configuration file <br />............... <span style=\"color:green\">SUCCESS!</span><br><br>";
	}
	else
	{
		die("Could not create the config file.<br>Ensure the directory has the correct permissions.");
	}


	require "./source/drivers/" . $conf['db_driver'] . ".php";
	// we need the newly created configuration file and the DB driver to test DB settings are all OK.

	// next step - attempt to connect to the DB
	$DB = new DB($conf['dbhost'], $conf['dbuser'], $conf['dbpass'], $conf['dbname']);
	if (!$DB->connect())
	{
		die("SQL error: " . $DB->error() . ".<br><br>Check your database settings in the configuration section.");
	}
	$output .= "Checking database connection <br />............... <span style=\"color:green\">SUCCESS!</span><br><br>";

	$sql = get_sql();
	foreach ($sql as $sql)
	{
		$output .= "Running SQL query:<br />" . clean_field($sql) . "<br />............... <span style=\"color:green\">SUCCESS!</span><br /><br />";
		if (!$DB->query($sql))
		{
			die("SQL Error returned: " . $DB->error() . ".<br><br>Check your database settings in the configuration section.");
		}
	}
	$output .= "Adding database tables <br />............... <span style=\"color:green\">SUCCESS!</span><br><br>";
	
	$ad_pass = MD5($HTTP_POST_VARS['admin_pass1']);
       	if (!$DB->query("INSERT INTO " . $conf['dbprefix'] . "admin (id, user, password, base, login_time, ip, session) VALUES ('', '$HTTP_POST_VARS[admin_name]', '$ad_pass', 'y', NULL, NULL, NULL)") )
       	{
        	die("SQL Error returned: " . $DB->error() . ".<br><br>Check your database settings in the configuration section.");
	}
	$output .= "Adding administrator account <br />............... <span style=\"color:green\">SUCCESS!</span><br><br>";


	if(!($fp = @fopen("./lock.cgi", "w+")))
	{
		$output .= "Attempting to lock the installer <br />............... <span style=\"color:red\">FAILED!</span><br>The installer was unable to create the lock file. However your WebCards installation is complete. Ensure the current directory has the correct permissions to allow the script to write to it and attempt to lock the installer as soon as possible through the administration centre.<br><br>";
		
	}
	else
	{
		@fputs($fp, "OOOOOOTZ");
		$output .= "Attempting to lock the installer <br />............... <span style=\"color:green\">SUCCESS!</span><br><br>";
	}
	$output .= "<h2>You may now login at the <a href=\"" . $conf['admin_script'] . "\">admin centre</a>.</h2>";
	return $output;
}

function get_sql()
{
global $conf;
$sql = array();

$sql[] = "DROP TABLE IF EXISTS " . $conf['dbprefix'] . "admin";

$sql[] = "CREATE TABLE " . $conf['dbprefix'] . "admin (
  id int(10) NOT NULL auto_increment,
  user varchar(255) NOT NULL default '',
  password varchar(255) NOT NULL default '',
  base enum('y','n') NOT NULL default 'n',
  lang varchar(25) NOT NULL default 'en',
  login_time varchar(10) default NULL,
  ip varchar(30) default NULL,
  session varchar(32) default NULL,
  PRIMARY KEY  (id)
)";

$sql[] = "DROP TABLE IF EXISTS " . $conf['dbprefix'] . "categories";

$sql[] = "CREATE TABLE " . $conf['dbprefix'] . "categories (
  id int(10) NOT NULL auto_increment,
  title varchar(255) NOT NULL default '',
  description text NOT NULL,
  PRIMARY KEY  (id)
)";

$sql[] = "DROP TABLE IF EXISTS " . $conf['dbprefix'] . "images";

$sql[] = "CREATE TABLE " . $conf['dbprefix'] . "images (
  id int(10) NOT NULL auto_increment,
  cat int(10) NOT NULL default '0',
  img_type enum('upload','link') NOT NULL default 'upload',
  thumb_type enum('upload','link') NOT NULL default 'upload',
  url varchar(255) NOT NULL default '',
  thumb varchar(255) NOT NULL default '',
  name varchar(255) NOT NULL default '',
  width int(5) default NULL,
  height int(5) default NULL,
  width_thumb int(5) default NULL,
  height_thumb int(5) default NULL,
  PRIMARY KEY  (`id`)
)";


$sql[] = "DROP TABLE IF EXISTS " . $conf['dbprefix'] . "macro";

$sql[] = "CREATE TABLE " . $conf['dbprefix'] . "macro (
  id int(10) NOT NULL auto_increment,
  name varchar(255) NOT NULL default '',
  extensions varchar(255) NOT NULL default '',
  macro text NOT NULL,
  PRIMARY KEY  (id)
)";

$sql[] = "DROP TABLE IF EXISTS " . $conf['dbprefix'] . "sent_cards";

$sql[] = "CREATE TABLE " . $conf['dbprefix'] . "sent_cards (
  id varchar(32) NOT NULL default '',
  pic int(10) NOT NULL default '0',
  title varchar(225) NOT NULL default '',
  date varchar(12) default NULL,
  from_name varchar(255) NOT NULL default '',
  from_email varchar(255) NOT NULL default '',
  recip_email text NOT NULL,
  bg_color varchar(25) NOT NULL default 'white',
  font_color varchar(25) NOT NULL default 'black',
  font_face varchar(25) NOT NULL default 'verdana',
  font_size varchar(25) NOT NULL default 'medium',
  message text NOT NULL,
  notify enum('0','1') NOT NULL default '0',
  email_sent enum('0','1') NOT NULL default '0',
  num_resends tinyint(3) NOT NULL default '0',
  sender_ip varchar(16) NOT NULL default '',
  PRIMARY KEY  (id)
)";

$sql[] = "DROP TABLE IF EXISTS " . $conf['dbprefix'] . "email_logs";

$sql[] = "CREATE TABLE " . $conf['dbprefix'] . "email_logs (
  id int(10) NOT NULL auto_increment,
  email_type varchar(255) NOT NULL default '',
  date int(10) NOT NULL default '0',
  sender_email varchar(255) NOT NULL default '',
  sender_ip varchar(16) NOT NULL default '127.0.0.1',
  recip_email varchar(255) NOT NULL default '',
  subject varchar(255) NOT NULL default '',
  content text NOT NULL,
  UNIQUE KEY id (id)
)";

//Add default macro data

$sql[] = "INSERT into " . $conf['dbprefix'] . "macro (id, name, extensions, macro) VALUES ('', 'Picture', 'gif,jpg,jpeg,bmp,png,tiff', '<img src=\"{{img}}\" alt=\"{{name}}\" border=\"0\" {{width}} {{height}} />')";
$sql[] = "INSERT into " . $conf['dbprefix'] . "macro (id, name, extensions, macro) VALUES ('', 'Macromedia Flash', 'swf', '<OBJECT classid=\"clsid:D27CDB6E-AE6D-11cf-96B8-444553540000\" codebase=\"http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,40,0\" id=\"FlashContent\" {{width}} {{height}}>
<PARAM NAME=movie VALUE=\"{{img}}\">
<PARAM NAME=quality VALUE=high>
<EMBED src=\"{{img}}\" {{width}} {{height}} quality=\"high\" NAME=\"FlashContent\" AllowScriptAccess=\"never\" TYPE=\"application/x-shockwave-flash\" PLUGINSPAGE=\"http://www.macromedia.com/go/getflashplayer\">
</EMBED>
</OBJECT>')";

	return $sql;
}

function clean_field($field)
{

	$to_find = array (	"/</",
				"/>/",
				"/<script[^>]*?>.*?<\/script>/si",
               			"/\"/"
					);
	$to_replace = array (	"&lt;",
              			"&gt;",
				"",
           			"&quot;"
  					);

	return stripslashes(preg_replace($to_find, $to_replace, $field));
}

?>
</td></tr></table>
</body>
</html>