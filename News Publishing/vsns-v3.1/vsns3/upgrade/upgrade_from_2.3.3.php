<?php
/***************************************************************************
*	Very Simple News System
*	Version: 3.0.0
*	Filename: upgrade_from_2.3.3.php
*	Description: Installation of VSNS
****************************************************************************
*	Build Date: March 4, 2005
*	Author: Tachyon
*	Website: http://tachyondecay.net
****************************************************************************
*	Copyright © 2005 by Tachyon
*
*	This program is free software; you can redistribute it and/or modify
*   it under the terms of the GNU General Public License as published by
*   the Free Software Foundation; either version 2 of the License, or
*   (at your option) any later version.  A copy of the GPL version 2 is
*	included with this package in the file "COPYING.TXT"
*
*   This program is distributed in the hope that it will be useful,
*   but WITHOUT ANY WARRANTY; without even the implied warranty of
*   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
*    GNU General Public License for more details.
*
*   You should have received a copy of the GNU General Public License
*   along with this program; if not, write to the Free Software
*   Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
****************************************************************************/

include "../settings.php";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html>
<head>
	<title>VSNS 3.0 Upgrade</title>

	<style type="text/css">
		@import "../templates/styles.css";
	</style>

</head>
<body>

<div id="top" style="text-align: center;">
	<img src="../logo.png" alt="VSNS v3.0" id="logo" />
</div>

<div id="main">

<h1 style="text-align: center;">VSNS v3.0 Upgrade</h1>

<?php

//Actually install VSNS
function run_install()
{
	//Grab the correct variables
	$dbserver = $_POST["dbserver"];
	$dbuser = $_POST["dbuser"];
	$dbpass = $_POST["dbpass"];
	$db = $_POST["db"];

	$password = $_POST["password"];
	$password = md5($password);

	$path = $_POST["path"];
	$path_len = strlen($path);
	$path_len1 = $path_len - 1;
	$is_found = strpos($path, "/", $path_len1);
	if ($is_found === false)
	{
		$path = $path."/";
	}

	//Get date
	$date = date("F j, Y");

	//Create the settings.php file
	$filecontent = <<<PIZZA
<?php
/***************************************************************************
*	Very Simple News System
*	Version: 3.0.0
*	Filename: settings.php
*	Description: Contains configuration settings
****************************************************************************
*	Build Date: $date
*	Author: Tachyon
*	Website: http://tachyondecay.net
****************************************************************************
*	Copyright © 2005 by Tachyon
*
*	This program is free software; you can redistribute it and/or modify
*   it under the terms of the GNU General Public License as published by
*   the Free Software Foundation; either version 2 of the License, or
*   (at your option) any later version.  A copy of the GPL version 2 is
*	included with this package in the file "COPYING.TXT"
*
*   This program is distributed in the hope that it will be useful,
*   but WITHOUT ANY WARRANTY; without even the implied warranty of
*   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
*    GNU General Public License for more details.
*
*   You should have received a copy of the GNU General Public License
*   along with this program; if not, write to the Free Software
*   Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
****************************************************************************/

//
// These are the VSNS config variables
// Do NOT change these variables
// unless you ABSOLUTELY know what you are doing
// Have a nice day, eh
// Monty Python rocks ;)
//

\$server = "$dbserver";
\$user = "$dbuser";
\$dbpass = "$dbpass";
\$db = "$db";

\$connected = mysql_connect(\$server, \$user, \$dbpass);
mysql_select_db(\$db);

\$version_query = mysql_query("SELECT * FROM vsns_config WHERE config_name = 'version'");
\$version_data = mysql_fetch_array(\$version_query);
\$version = \$version_data["version"];

mysql_free_result(\$version_query);
PIZZA;

	$handle = fopen('../settings.php', 'wb');
	fwrite($handle, $filecontent);
	fclose($handle);

	$connection = mysql_connect($dbserver, $dbuser, $dbpass);
	mysql_select_db($db);

	mysql_query("ALTER TABLE vsns_news
		ADD category varchar(255),
		ADD author varchar(255),
		ADD comments varchar(255),
		ADD password varchar(255);") or die(mysql_error());

	mysql_query("CREATE TABLE vsns_comments(
		ID int(5) NOT NULL AUTO_INCREMENT,
		article_id int(5) NOT NULL,
		ip varchar(255) NOT NULL,
		name varchar(255),
		website varchar(255),
		comment text,
		date datetime NOT NULL,
		PRIMARY KEY (ID)
	);") or die(mysql_error());

	mysql_query("CREATE TABLE vsns_config(
		config_name varchar(255),
		config_value text
	);") or die(mysql_error());

	mysql_query("INSERT INTO vsns_config VALUES('version', '3.0.1')");
	mysql_query("INSERT INTO vsns_config VALUES('password', '$password')");
	mysql_query("INSERT INTO vsns_config VALUES('limit', '5')");
	mysql_query("INSERT INTO vsns_config VALUES('headline', '0')");
	mysql_query("INSERT INTO vsns_config VALUES('disp_order', 'DESC')");
	mysql_query("INSERT INTO vsns_config VALUES('on_expiry', 'unpin')");
	mysql_query("INSERT INTO vsns_config VALUES('prefixes', 'Pinned:\nAnnouncment:\nAlert!\nImportant:')");
	mysql_query("INSERT INTO vsns_config VALUES('disable_categories', '0')");
	mysql_query("INSERT INTO vsns_config VALUES('categories', 'General\nRants\nEntertainment\nSports')");
	mysql_query("INSERT INTO vsns_config VALUES('show_date', '1')");
	mysql_query("INSERT INTO vsns_config VALUES('show_author', '0')");
	mysql_query("INSERT INTO vsns_config VALUES('disable_comments', '0')");
	mysql_query("INSERT INTO vsns_config VALUES('path', '$path')");
	mysql_query("INSERT INTO vsns_config VALUES('banned_ip', '')");
	mysql_query("INSERT INTO vsns_config VALUES('wysiwyg', '1')");
	mysql_query("INSERT INTO vsns_config VALUES ('navdisplay', '1')");
	mysql_query("INSERT INTO vsns_config VALUES ('hlevel', '2')");

	if ($connection)
	{
		mysql_close($connection);
	}
?>
<p>Congratulations, your upgrade to VSNS 3.0 is complete.  Please delete this installation file for security purposes before continuing.</p>
<?php
}

//Show the install form
function install_form()
{
	global $server, $user, $pass, $db, $admin;
?>
<p>Welcome to the installation for VSNS 3.0.  Please review the README.html file included with this package before going further.  Remember that you must have done the following things before installing:</p>
<ul>
	<li>CHMOD 'settings.php' to 777</li>
	<li>Create a MySQL database</li>
	<li>Give a MySQL user permission to alter that database</li>
</ul>
<p>If you've done those three things, then fill out the form to begin the installation.</p>

<form name="install_of_doom" action="<?php echo $_SERVER["PHP_SELF"];?>" method="post">
	<input type="hidden" id="act" name="act" value="install" />

<div class="form">
	<span class="label">
		<label for="dbserver">Hostname:</label>
	</span>
	<span class="field">
		<input type="text" id="dbserver" name="dbserver" value="<?php echo $server;?>" onfocus="this.select(); return true" />
	</span>
</div>
<div class="form">
	<span class="label">
		<label for="dbuser">Username:</label>
	</span>
	<span class="field">
		<input type="text" id="dbuser" name="dbuser" value="<?php echo $user;?>" />
	</span>
</div>
<div class="form">
	<span class="label">
		<label for="dbpass">Password:</label>
	</span>
	<span class="field">
		<input type="text" id="dbpass" name="dbpass" value="<?php echo $pass;?>" />
	</span>
</div>
<div class="form">
	<span class="label">
		<label for="db">Database:</label>
	</span>
	<span class="field">
		<input type="text" id="db" name="db" value="<?php echo $db;?>" />
	</span>
</div>
<div class="form" style="margin-top: 25px;">
	<span class="label">
		<label for="password">Access Password:</label>
		<br />
This is the password you'll use to access the VSNS control panel.
	</span>
	<span class="field">
		<input type="text" id="password" name="password" value="<?php echo $admin; ?>" />
</div>
<div class="form">
	<span class="label">
		<label for="password">Access Path:</label>
		<br />
Eg; <em>http://example.com/vsns3/</em>
	</span>
	<span class="field">
		<input type="text" id="path" name="path" />
	</span>
</div>
<div class="form" style="text-align: center;">
	<input type="submit" class="button" value="Upgrade to VSNS v3.0" />
</div>
<?php
}

$act = $_POST["act"];

if ($act == "install")
{
	run_install();
}

else
{
	install_form();
}
?>
</div>

<div id="footer">
	<p>Powered by VSNS Lemon 3.0 &copy; 2005 by <a href="http://tachyondecay.net">Tachyon</a>.  All rights reserved.</p>
</div>

</body>
</html>