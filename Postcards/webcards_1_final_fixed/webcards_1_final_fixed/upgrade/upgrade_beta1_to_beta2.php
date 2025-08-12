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
// $Id: upgrade_beta1.php,v 1.00 2004/05/24 20:21:26 chrisc Exp $

?>

<html>
<head>
<link rel="stylesheet" type="text/css" href="../webcards.css">
<title>Install WebCards</title>
</head>
<body>
<table width="100%" border="0" cellpadding="0" cellspacing="0" class="noborder">
<tr><td>
<img src="../site_images/setup.gif"><br /><br />
</td></tr>
</table>

<?php

switch($HTTP_GET_VARS['stage'])
{
	case 'perms':
	check_file_perms();
	break;

	case 'do_upgrade':
	echo do_upgrade();
	break;

	default:
	echo "<span class=\"title\">Upgrade beta 1 to beta 2</span><br /><br />
			This script is <b>ONLY</b> designed for people who currently have beta 1 installed but wish to upgrade to beta 2.<br /><br />
			Some changes were made to the database structure in beta 2. This script will make those changes for you.
			<br /><br />If you do not have beta 1 installed you should <b>NOT</b> run this upgrade script. It will mess up your database.
			<br /><br />
			Click the button below to check you have the correct files uploaded.<br /><br />
			<form action=\"upgrade_beta1.php?stage=perms\" method=\"post\"><input type=\"submit\" value=\"Proceed with instalation\"></form>";
	break;
}

function check_file_perms()
{

	$files_to_write = array("../config.php", "../template.html", "../webcards.css", "../lang/English/email.php", "../lang/English/global.php", "../lang/English/index.php", "../lang/English/pickup.php", "../lang/English/admin/ad_ban.php", "../lang/English/admin/ad_category.php", "../lang/English/admin/ad_config.php", "../lang/English/admin/ad_images.php", "../lang/English/admin/ad_index.php", "../lang/English/admin/ad_lang.php", "../lang/English/admin/ad_misc.php", "../lang/English/admin/ad_template.php", "../lang/English/admin/ad_toolbox.php", "../lang/English/admin/ad_users.php", "../templates/main_form.html", "../templates/preview_card.html", "../templates/render_card.html", "../templates/select_img.html", "../templates/view_image_stats.html");
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
		echo "<form action=\"upgrade_beta1.php?stage=do_upgrade\" method=\"post\"><b>Test passed</b><br /><br />\n\nClick the button below to proceed.<br /><br /><input type=\"submit\" value=\"N  E  X  T    S  T  A  G  E\"></form>";
	}
	else
	{
		echo "<b>Test failed</b><br /><br />Please ensure the files marked <span style=\"color: red\";>fail</span> have sufficient permissions for the script to write to them. Try a chmod value of 0775. If that doesn't work, try 0777.";
	}

}

function do_upgrade()
{

	if(!@include "../config.php")
	{
		die("ERROR!<br />Unable to find your config.php file");
	}

	if(!@include "../drivers/" . $conf['db_driver'] . ".php")
	{
		die("Unable to find your database driver file");
	}

	// next step - attempt to connect to the DB
	$DB = new DB($conf['dbhost'], $conf['dbuser'], $conf['dbpass'], $conf['dbname']);
	if (!$DB->connect())
	{
		die("Unable to connect to the database.<br /><br />SQL error: " . $DB->error() . ".<br><br>Check your database settings in the configuration section.");
	}
	
	
	$sql = array();

	$sql[] = "CREATE TABLE " . $conf['db_prefix'] . "email_logs (
	  id tinyint(255) NOT NULL auto_increment,
	  email_type varchar(255) NOT NULL default '',
	  date int(10) NOT NULL default '0',
	  sender_email varchar(255) NOT NULL default '',
	  sender_ip varchar(16) NOT NULL default '127.0.0.1',
	  recip_email varchar(255) NOT NULL default '',
	  subject varchar(255) NOT NULL default '',
	  content text NOT NULL,
	  UNIQUE KEY id (id)
	)";
	
	foreach($sql as $sql)
	{
		if (!$DB->query($sql))
		{
			die("SQL Error returned: " . $DB->error() . ".<br><br>Check your database settings in the configuration section.");
		}
	}

	return "<span class=\"title\">Upgrade Complete</span><br /><br />Your Webcards installation has been sucessfully upgraded.<br /><br /><span class=\"warning\">You MUST remove this file immediately.</span> If you do not, your installation is at risk.";

}

?>