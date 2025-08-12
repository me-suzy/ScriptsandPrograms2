<?php
/***************************************************************************
*	Very Simple News System
*	Version: 3.1.1
*	Filename: install.php
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
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html>
<head>
	<title>VSNS 3.1 Installation</title>

	<meta http-equiv="Content-Style-Type" content="text/css" />
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />

	<style type="text/css">
		@import "templates/styles.css";
	</style>

	<script type="text/javascript" src="javascript.js"></script>

</head>
<body>

<div id="wrapper">
<div id="top">
	<img src="images/logo.png" alt="VSNS Lemon" id="logo" />
</div>

<div id="main">
<h1>VSNS Lemon Installation</h1>

<?php

//Actually install VSNS
function run_install()
{
	extract($_POST, EXTR_SKIP);

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
*	Version: 3.1.1
*	Filename: settings.php
*	Description: Contains configuration settings
****************************************************************************
*	Build Date: $date
*	Author: Tachyon
*	Website: http://tachyondecay.net/
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

//Configuration variables
\$configdata = array("version", "limit", "headline", "disp_order", "on_expiry", "prefixes", "disable_categories", "categories", "show_date", "show_author", "disable_comments", "path", "hlevel", "vsnsemail", "notification", "sitename", "desc", "cright", "website", "serverpath", "queue");
\$config_size = sizeof(\$configdata) - 1;
\$i = 0;

while (\$i <= \$config_size)
{
	\$query = mysql_query("SELECT config_value AS value FROM vsns_config WHERE config_name = '{\$configdata[\$i]}'") or die(mysql_error());
	\$querydata = mysql_fetch_array(\$query);
	\$config["\$configdata[\$i]"] = stripslashes(\$querydata["value"]);
	mysql_free_result(\$query);

	\$i++;
}

extract(\$config, EXTR_SKIP);

//Include these files
include "functions/comments_functions.php";
include "functions/config_functions.php";
include "functions/emoticon_functions.php";
include "functions/functions.php";
include "functions/ip_functions.php";
include "functions/login_functions.php";
include "functions/news_functions.php";
include "functions/update_functions.php";
?>
PIZZA;

	$handle = fopen('settings.php', 'wb');
	fwrite($handle, $filecontent);
	fclose($handle);

	$connection = mysql_connect($dbserver, $dbuser, $dbpass);
	mysql_select_db($db);

	mysql_query("CREATE TABLE vsns_news(
		ID int(5) NOT NULL AUTO_INCREMENT,
		heading varchar(255),
		content text,
		pinned tinyint(1),
		month tinyint(2),
		day tinyint(2),
		year int(4),
		prefix varchar(255),
		expires date,
		category varchar(255),
		author varchar(255),
		comments varchar(255),
		password varchar(255),
		pubDate int(11) NOT NULL,
		PRIMARY KEY (ID)
	);") or die(mysql_error());

	mysql_query("CREATE TABLE vsns_comments(
		ID int(5) NOT NULL AUTO_INCREMENT,
		article_id int(5) NOT NULL,
		ip varchar(255) NOT NULL,
		name varchar(255),
		commentemail varchar(255),
		website varchar(255),
		comment text,
		date datetime NOT NULL,
		pubDate int(11) NOT NULL,
		PRIMARY KEY (ID)
	);") or die(mysql_error());

	mysql_query("CREATE TABLE vsns_config(
		config_name varchar(255),
		config_value text
	);") or die(mysql_error());

	mysql_query("INSERT INTO vsns_config VALUES('version', '3.1.1')");
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
	mysql_query("INSERT INTO vsns_config VALUES('hlevel', 2)");
	mysql_query("INSERT INTO vsns_config VALUES('vsnsemail', '$vsnsemail')");
	mysql_query("INSERT INTO vsns_config VALUES('notification', '1')");
	mysql_query("INSERT INTO vsns_config VALUES('sitename', '$sitename')");
	mysql_query("INSERT INTO vsns_config VALUES('desc', '$desc')");
	mysql_query("INSERT INTO vsns_config VALUES('cright', '$cright')");
	mysql_query("INSERT INTO vsns_config VALUES('website', '$website')");
	mysql_query("INSERT INTO vsns_config VALUES('serverpath', '$serverpath')");
	mysql_query("INSERT INTO vsns_config VALUES('queue', '0')");

	if ($connection)
	{
		mysql_close($connection);
	}
	echo '<p class="response">Congratulations, VSNS Lemon has been installed successfully.  You should delete this file now.  Go to your <a href="admin.php">Administration CP</a> to start using it.</p>';
}

//Show the install form
function install_form($act,$passwd = 0)
{
	switch($act)
	{
		default:
		case "step0":
?>
<p class="instructions">Welcome to the installation for VSNS 3.1.  Please review the README.html file included with this package before going further.  Remember that you must have done the following things before installing:</p>
<ul>
	<li>CHMOD 'settings.php' to 777</li>
	<li>Create a MySQL database</li>
	<li>Give a MySQL user permission to alter that database</li>
</ul>
<p class="instructions">If you've done those three things, then you're ready to begin!</p>

<p class="jumplink"><a href="install.php?act=step1">Begin Installation</a></p>
<?
		break;

		case "step1":
?>
<p class="instructions">Enter the information for your MySQL database below.</p>
<form id="install" action="<?php echo $_SERVER["PHP_SELF"];?>" method="post">

<div class="vsns_form">
	<input type="hidden" name="act" value="step2" />
	<span class="vsns_label">
		<label for="dbserver">Hostname:</label>
	</span>
	<span class="vsns_field">
		<input type="text" id="dbserver" name="dbserver" value="localhost" onfocus="this.select(); return true" />
	</span>
</div>
<div class="vsns_form">
	<span class="vsns_label">
		<label for="dbuser">Username:</label>
	</span>
	<span class="vsns_field">
		<input type="text" id="dbuser" name="dbuser" />
	</span>
</div>
<div class="vsns_form">
	<span class="vsns_label">
		<label for="dbpass">Password:</label>
	</span>
	<span class="vsns_field">
		<input type="text" id="dbpass" name="dbpass" />
	</span>
</div>
<div class="vsns_form">
	<span class="vsns_label">
		<label for="db">Database:</label>
	</span>
	<span class="vsns_field">
		<input type="text" id="db" name="db" />
	</span>
</div>

<div class="vsns_form vsns_buttons">
	<input type="submit" class="button" value="Continue to Step 2" />
</div>
</form>
<?php
		break;

		case "step2":
			extract($_POST, EXTR_SKIP);

			if ($passwd == 1)
			{
				echo '<p class="instructions">Your passwords did not match.</p>';
			}
?>
<form id="install" action="<?php echo $_SERVER["PHP_SELF"];?>" method="post">

<div class="vsns_form">
	<input type="hidden" name="act" value="step3" />
	<input type="hidden" name="dbserver" value="<?php echo $dbserver;?>" />
	<input type="hidden" name="dbuser" value="<?php echo $dbuser;?>" />
	<input type="hidden" name="dbpass" value="<?php echo $dbpass;?>" />
	<input type="hidden" name="db" value="<?php echo $db;?>" />
	<span class="vsns_label">
		<label for="sitename">Blog Name:</label>
	</span>
	<span class="vsns_field">
		<input type="text" id="sitename" name="sitename" value="" />
	</span>
</div>

<div class="vsns_form">
	<span class="vsns_label">
		<label for="vsnsemail">Email Address:</label>
	</span>
	<span class="vsns_field">
		<input type="text" id="vsnsemail" name="vsnsemail" value="" />
	</span>
</div>

<div class="vsns_form">
	<span class="vsns_label">
		<label for="password">Password:</label>
	</span>
	<span class="vsns_field">
		<input type="password" id="password" name="password" />
	</span>
</div>

<div class="vsns_form">
	<span class="vsns_label">
		<label for="confpassword">Confirm Password:</label>
	</span>
	<span class="vsns_field">
		<input type="password" id="confpassword" name="confpassword" />
	</span>
</div>

<div class="vsns_form vsns_buttons">
	<input type="submit" class="button" value="Continue to Step 3" />
</div>

</form>
<?php
		break;

		case "step3":
			extract($_POST,EXTR_SKIP);
			if ($password !== $confpassword)
			{
				install_form("step2",1);
			}

			else
			{
				$password = md5($password);
?>
<form id="install" action="<?php echo $_SERVER["PHP_SELF"];?>" method="post">

<div class="vsns_form">
	<input type="hidden" name="act" value="install" />
	<input type="hidden" name="dbserver" value="<?php echo $dbserver;?>" />
	<input type="hidden" name="dbuser" value="<?php echo $dbuser;?>" />
	<input type="hidden" name="dbpass" value="<?php echo $dbpass;?>" />
	<input type="hidden" name="db" value="<?php echo $db;?>" />
	<input type="hidden" name="sitename" value="<?php echo $sitename;?>" />
	<input type="hidden" name="vsnsemail" value="<?php echo $vsnsemail;?>" />
	<input type="hidden" name="password" value="<?php echo $password;?>" />
	<span class="vsns_label">
		<label for="cright">Name:</label>
	</span>
	<span class="vsns_field">
		<input type="text" id="cright" name="cright" value="<?php echo $cright; ?>" />
	</span>
</div>

<div class="vsns_form">
	<span class="vsns_label">
		<label for="desc">Blog Description</label>:
	</span>
	<span class="vsns_field">
		<input type="text" id="desc" name="desc" value="<?php echo $desc; ?>" />
	</span>
</div>

<div class="vsns_form">
	<span class="vsns_label">
		<label for="password">Access Path:</label>
		<br />
Eg; <em>http://example.com/vsns3/</em>
	</span>
	<span class="vsns_field">
<?php
	$accesspath = "http://".$_SERVER["HTTP_HOST"].$_SERVER["REQUEST_URI"];
	$accesslen = strlen($accesspath);
	$accesslen1 = $accesslen - 11;
	$accesspath = substr($accesspath, 0, $accesslen1);
?>
		<input type="text" id="path" name="path" value="<?php echo $accesspath; ?>" />
	</span>
</div>

<div class="vsns_form">
	<span class="vsns_label">
		<label for="website">Website:</label>
	</span>
	<span class="vsns_field">
		<input type="text" id="website" name="website" value="" />
	</span>
</div>

<div class="vsns_form">
	<span class="vsns_label">
		<label for="website">Server Path:</label>
	</span>
	<span class="vsns_field">
<?php
	$serverpath = $_SERVER["DOCUMENT_ROOT"].$_SERVER["PHP_SELF"];
	$serverpathlen = strlen($serverpath);
	$serverpathlen1 = $serverpathlen - 11;
	$serverpath = substr($serverpath, 0, $serverpathlen1);
?>
		<input type="text" id="serverpath" name="serverpath" value="<?php echo $serverpath; ?>" />
	</span>
</div>

<div class="vsns_form vsns_buttons">
	<input type="submit" class="button" value="Install VSNS Lemon" />
</div>
</form>
<?php
		}
		break;
	}
}

$act = $_REQUEST["act"];

if ($act == "install")
{
	run_install();
}

else
{
	install_form($act);
}
?>
</div>

<div id="footer">
	<p>Powered by VSNS Lemon 3.1 &copy; 2005 by <a href="http://tachyondecay.net/">Tachyon</a>.  All rights reserved.</p>
</div>
</div>

</body>
</html>