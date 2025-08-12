<?php
/***************************************************************************
*	Very Simple News System
*	Version: 3.1.1
*	Filename: vsns_3.1.0_to_3.1.1.php
*	Description: Upgrades VSNS 3.1.0 to 3.1.1
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
	<title>VSNS 3.1 Upgrade</title>

	<style type="text/css">
		@import "../templates/styles.css";
	</style>

</head>
<body>

<div id="wrapper">
<div id="top" style="text-align: center;">
	<img src="../logo.png" alt="VSNS v3.1" id="logo" />
</div>

<div id="main">

<h1 style="text-align: center;">VSNS v3.1.1 Upgrade</h1>

<?php
$serverpath = $_SERVER["DOCUMENT_ROOT"].$_SERVER["PHP_SELF"];
	$serverpathlen = strlen($serverpath);
	$serverpathlen1 = $serverpathlen - 11;
	$serverpath = substr($serverpath, 0, $serverpathlen1);

mysql_query("UPDATE vsns_config SET config_name = 'vsnsemail' WHERE config_name = 'email'");
mysql_query("UPDATE vsns_config SET config_name = 'cright' WHERE config_name = 'copyright'");
mysql_query("UPDATE vsns_config SET config_value = '3.1.1' WHERE config_name = 'version'");

mysql_query("INSERT INTO vsns_config values('serverpath','$serverpath'");
mysql_query("INSERT INTO vsns_config values('queue',0)");

mysql_query("ALTER TABLE `vsns_comments` ADD `commentemail` VARCHAR(255) NOT NULL AFTER `name`") or die(mysql_error());
mysql_query("ALTER TABLE `vsns_comments` ADD `queue` TINYINT(1) NOT NULL") or die(mysql_error());

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

\$server = "$server";
\$user = "$user";
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

	$handle = fopen('../settings.php', 'wb');
	fwrite($handle, $filecontent);
	fclose($handle);
?>

<p class="response">Upgrade complete.  Delete this file.  Also, please visit the Blog Options to configure some new options not present prior to the upgrade.</p>
</div>

<div id="footer">
	<p>Powered by VSNS Lemon 3.1 &copy; 2005 by <a href="http://tachyondecay.net">Tachyon</a>.  All rights reserved.</p>
</div>
</div>

</body>
</html>