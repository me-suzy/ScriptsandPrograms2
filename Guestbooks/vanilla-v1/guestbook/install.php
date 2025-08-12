<?php
/***************************************************************************
*	Vanilla Guestbook
*	Version: 0.1
*	Filename: install.php
*	Description: Installation of Vanilla Guestbook
****************************************************************************
*	Build Date: August 20, 2005
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
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html>
<head>
	<title>Vanilla Guestbook 1.0 Beta</title>

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
	<img src="images/logo.png" alt="Vanilla Guestbook 1.0 Beta" id="logo" />
</div>

<div id="main">
<h1>Vanilla Guestbook Installation</h1>

<?php
//Actual install
function install()
{
	extract($_POST, EXTR_SKIP);

	$path_len = strlen($path);
	$path_len1 = $path_len - 1;
	$pos = strpos($path, "/", $path_len1);
	if ($pos === false)
	{
		$path = $path."/";
	}

	$serverpath_len = strlen($serverpath);
	$serverpath_len1 = $serverpath_len - 1;
	$spos = strpos($serverpath, "/", $serverpath_len1);
	if ($spos === false)
	{
		$serverpath = $serverpath."/";
	}

	//Get date
	$date = date("F j, Y");

	//Create settings.php file
	$filecontent = <<<TOVAN
<?php
/***************************************************************************
*	Vanilla Guestbook
*	Version: 0.1
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

///////////////////
// Below are the Vanilla Guestbook configuration
// variables.  Tampering with them can break
// the script.  It's best to leave them alone
// unless you need to change something.
//////////////////

\$server = "$dbserver";
\$user = "$dbuser";
\$dbpass = "$dbpass";
\$db = "$db";

\$connected = mysql_connect(\$server, \$user, \$dbpass);
mysql_select_db(\$db);

//Configuration variables
\$configdata = array("version", "limit", "disp_order", "path", "banned_ip", "hlevel", "allow", "bkemail", "notification", "queue", "serverpath", "bkname");
\$config_size = sizeof(\$configdata) - 1;
\$i = 0;

while (\$i <= \$config_size)
{
	\$query = mysql_query("SELECT config_value AS value FROM vanilla_config WHERE config_name = '{\$configdata[\$i]}'") or die(mysql_error());
	\$querydata = mysql_fetch_array(\$query);
	\$config["\$configdata[\$i]"] = stripslashes(\$querydata["value"]);
	mysql_free_result(\$query);

	\$i++;
}

extract(\$config, EXTR_SKIP);

//Include these files
include "functions/config_functions.php";
include "functions/emoticon_functions.php";
include "functions/entry_functions.php";
include "functions/functions.php";
include "functions/ip_functions.php";
include "functions/login_functions.php";
include "functions/support_functions.php";
include "functions/update_functions.php";
?>
TOVAN;

	$handle = fopen('settings.php', 'wb');
	fwrite($handle, $filecontent);
	fclose($handle);

	//////////////////
	// Now we're going to connect to the database
	// and run some lovely queries to install our
	// tables.
	//////////////////

	$connection = mysql_connect($dbserver, $dbuser, $dbpass) or die(mysql_error());
	mysql_select_db($db) or die(mysql_error());

	mysql_query ("CREATE TABLE vanilla_config (
  		config_name varchar(255) default NULL,
  		config_value text
	) TYPE=MyISAM") or die(mysql_error());

	mysql_query ("CREATE TABLE vanilla_entry (
  		ID int(11) NOT NULL auto_increment,
  		queue int(1) NOT NULL default '0',
		ip varchar(255) NOT NULL default '',
  		date datetime NOT NULL default '0000-00-00 00:00:00',
  		name varchar(255) default NULL,
  		email varchar(255) default NULL,
		website varchar(255) default NULL,
  		msn varchar(255) default NULL,
  		yahoo varchar(255) default NULL,
  		aim varchar(255) default NULL,
  		icq varchar(255) default NULL,
  		gtalk varchar(255) default NULL,
  		comment text NOT NULL,
  		score int(11) NOT NULL default '0',
  		avatar varchar(255) default NULL,
  		PRIMARY KEY  (ID)
	) TYPE=MyISAM") or die(mysql_error());

	mysql_query ("INSERT INTO vanilla_config VALUES ('version', '1.0 Beta')") or die(mysql_error());
	mysql_query ("INSERT INTO vanilla_config VALUES ('password', '$password')") or die(mysql_error());
	mysql_query ("INSERT INTO vanilla_config VALUES ('limit', '10')") or die(mysql_error());
	mysql_query ("INSERT INTO vanilla_config VALUES ('disp_order', 'DESC')") or die(mysql_error());
	mysql_query ("INSERT INTO vanilla_config VALUES ('path', '$path')") or die(mysql_error());
	mysql_query ("INSERT INTO vanilla_config VALUES ('banned_ip', '')") or die(mysql_error());
	mysql_query ("INSERT INTO vanilla_config VALUES ('hlevel', '2')") or die(mysql_error());
	mysql_query ("INSERT INTO vanilla_config VALUES ('allow', '1')") or die(mysql_error());
	mysql_query ("INSERT INTO vanilla_config VALUES ('bkemail', '$bkemail')") or die(mysql_error());
	mysql_query ("INSERT INTO vanilla_config VALUES ('notification', '1')") or die(mysql_error());
	mysql_query ("INSERT INTO vanilla_config VALUES ('queue', '0')") or die(mysql_error());
	mysql_query ("INSERT INTO vanilla_config VALUES ('serverpath', '$serverpath')") or die(mysql_error());
	mysql_query ("INSERT INTO vanilla_config VALUES ('bkname', '$bkname')") or die(mysql_error());

	echo '<p class="response">Congratulations, Vanilla Guestbook has been installed successfully.  You should delete this file now.  Go to your <a href="admin.php">Administration CP</a> to start using the Guestbook.</p>';
}

//Install form
function install_form($act,$passwd = 0)
{
	switch($act)
	{
		default:
		case "step0":
?>
<p class="instructions">Welcome to the installation for Vanilla Guestbook 0.1.  Please review the README.html file included with this package before going further.  Remember that you must have done the following things before installing:</p>
<ul>
	<li>CHMOD <code>settings.php</code> to 777</li>
	<li>Create a MySQL database</li>
	<li>Give a MySQL user permission to alter that database</li>
</ul>
<p class="instructions">If you've done those three things, then you are ready to begin!</p>

<p class="jumplink"><a href="install.php?act=step1">Begin Installation</a></p>
<?
		break;

		case "step1":
?>
<p class="instructions">Enter the information for your MySQL database below.</p>
<form id="install" action="<?php echo $_SERVER["PHP_SELF"];?>" method="post">

<div class="bk_form">
	<input type="hidden" name="act" value="step2" />
	<span class="bk_label">
		<label for="dbserver">Hostname</label>:
	</span>
	<span class="bk_field">
		<input type="text" id="dbserver" name="dbserver" value="localhost" onfocus="this.select(); return true" />
	</span>
</div>

<div class="bk_form">
	<span class="bk_label">
		<label for="dbuser">Username</label>:
	</span>
	<span class="bk_field">
		<input type="Text" id="dbuser" name="dbuser" />
	</span>
</div>

<div class="bk_form">
	<span class="bk_label">
		<label for="dbpass">Password:</label>
	</span>
	<span class="bk_field">
		<input type="text" id="dbpass" name="dbpass" />
	</span>
</div>

<div class="bk_form">
	<span class="bk_label">
		<label for="db">Database:</label>
	</span>
	<span class="bk_field">
		<input type="text" id="db" name="db" />
	</span>
</div>

<div class="bk_form bk_buttons">
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

<div class="bk_form">
	<input type="hidden" name="act" value="step3" />
	<input type="hidden" name="dbserver" value="<?php echo $dbserver;?>" />
	<input type="hidden" name="dbuser" value="<?php echo $dbuser;?>" />
	<input type="hidden" name="dbpass" value="<?php echo $dbpass;?>" />
	<input type="hidden" name="db" value="<?php echo $db;?>" />
	<span class="bk_label">
		<label for="bkname">Guestbook Name:</label>
	</span>
	<span class="bk_field">
		<input type="text" name="bkname" value="" />
	</span>
</div>

<div class="bk_form">
	<span class="bk_label">
		<label for="bkemail">Email Address:</label>
	</span>
	<span class="bk_field">
		<input type="text" id="bkemail" name="bkemail" value="" />
	</span>
</div>

<div class="bk_form">
	<span class="bk_label">
		<label for="password">Password:</label>
	</span>
	<span class="bk_field">
		<input type="password" id="password" name="password" />
	</span>
</div>

<div class="bk_form">
	<span class="bk_label">
		<label for="confpassword">Confirm Password:</label>
	</span>
	<span class="bk_field">
		<input type="password" id="confpassword" name="confpassword" />
	</span>
</div>

<div class="bk_form bk_buttons">
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

<div class="bk_form">
	<input type="hidden" name="act" value="install" />
	<input type="hidden" name="dbserver" value="<?php echo $dbserver;?>" />
	<input type="hidden" name="dbuser" value="<?php echo $dbuser;?>" />
	<input type="hidden" name="dbpass" value="<?php echo $dbpass;?>" />
	<input type="hidden" name="db" value="<?php echo $db;?>" />
	<input type="hidden" name="bkname" value="<?php echo $bkname;?>" />
	<input type="hidden" name="bkemail" value="<?php echo $bkemail;?>" />
	<input type="hidden" name="password" value="<?php echo $password;?>" />
	<span class="bk_label">
		<label for="path">Access Path:</label>
		<br />
Eg; <em>http://example.com/vanilla/</em>
	</span>
	<span class="bk_field">
<?php
	$accesspath = "http://".$_SERVER["HTTP_HOST"].$_SERVER["REQUEST_URI"];
	$accesslen = strlen($accesspath);
	$accesslen1 = $accesslen - 11;
	$accesspath = substr($accesspath, 0, $accesslen1);
?>
		<input type="text" id="path" name="path" value="<?php echo $accesspath; ?>" />
	</span>
</div>

<div class="bk_form">
	<span class="bk_label">
		<label for="serverpath">Server Path to Guestbook</label>:
	</span>
	<span class="bk_field">
<?php
	$serverpath = $_SERVER["DOCUMENT_ROOT"].$_SERVER["PHP_SELF"];
	$serverpathlen = strlen($serverpath);
	$serverpathlen1 = $serverpathlen - 11;
	$serverpath = substr($serverpath, 0, $serverpathlen1);
?>
		<input type="text" id="serverpath" name="serverpath" value="<?php echo $serverpath; ?>" />
	</span>
</div>

<div class="bk_form bk_buttons">
	<input type="submit" class="button" value="Install Vanilla Guestbook" />
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
	install();
}

else
{
	install_form($act);
}
?>
</div>

<div id="footer">
	<p>Powered by Vanilla Guestbook 1.0 Beta &copy; 2005 by <a href="http://tachyondecay.net/">Tachyon</a>.  All rights reserved.</p>
</div>

</div>

</body>
</html>