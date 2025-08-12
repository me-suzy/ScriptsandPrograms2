<?php
/***************************************************************************
*	Very Simple News System
*	Version: 3.1.0
*	Filename: vsns_3.0.1_to_3.1.0.php
*	Description: Upgrades VSNS 3.0.1 to 3.1.0
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

<div id="top" style="text-align: center;">
	<img src="../logo.png" alt="VSNS v3.0" id="logo" />
</div>

<div id="main">

<h1 style="text-align: center;">VSNS v3.1.0 Upgrade</h1>

<?php
mysql_query("DELETE FROM vsns_config WHERE config_name = 'navdisplay'");
mysql_query("DELETE FROM vsns_config WHERE config_name = 'wysiwyg'");
mysql_query("INSERT INTO vsns_config VALUES('email', '')");
mysql_query("INSERT INTO vsns_config VALUES('notification', '1')");
mysql_query("INSERT INTO vsns_config VALUES('sitename', '')");
mysql_query("INSERT INTO vsns_config VALUES('desc', '')");
mysql_query("INSERT INTO vsns_config VALUES('copyright', '')");
mysql_query("INSERT INTO vsns_config VALUES('website', '')");
mysql_query("ALTER TABLE vsns_news ADD pubDate int(11) NOT NULL");
mysql_query("ALTER TABLE vsns_comments ADD pubDate int(11) NOT NULL");

mysql_query("UPDATE vsns_config SET config_value = '3.1.0' WHERE config_name = 'version'");

	$dbserver = $_POST["dbserver"];
	$dbuser = $_POST["dbuser"];
	$dbpass = $_POST["dbpass"];
	$db = $_POST["db"];

	//Get date
	$date = date("F j, Y");

	//Create the settings.php file
	$filecontent = <<<PIZZA
<?php
/***************************************************************************
*	Very Simple News System
*	Version: 3.1.0
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

//Configuration values
\$versionquery = mysql_query ("SELECT * FROM vsns_config WHERE config_name = 'version'");
\$version = mysql_fetch_array(\$versionquery);
\$version = \$version["config_value"];

\$limitquery = mysql_query("SELECT * FROM vsns_config WHERE config_name = 'limit'");
\$limit = mysql_fetch_array(\$limitquery);
\$limit = \$limit["config_value"];

\$headlinequery = mysql_query("SELECT * FROM vsns_config WHERE config_name = 'headline'");
\$headline = mysql_fetch_array(\$headlinequery);
\$headline = \$headline["config_value"];

\$disp_orderquery = mysql_query("SELECT * FROM vsns_config WHERE config_name = 'disp_order'");
\$disp_order = mysql_fetch_array(\$disp_orderquery);
\$disp_order = \$disp_order["config_value"];

\$on_expiryquery = mysql_query("SELECT * FROM vsns_config WHERE config_name = 'on_expiry'");
\$on_expiry = mysql_fetch_array(\$on_expiryquery);
\$on_expiry = \$on_expiry["config_value"];

\$prefixesquery = mysql_query("SELECT * FROM vsns_config WHERE config_name = 'prefixes'");
\$prefixes = mysql_fetch_array(\$prefixesquery);
\$prefixes = \$prefixes["config_value"];
\$prefixes = trim(\$prefixes);

\$disable_categories_query = mysql_query("SELECT * FROM vsns_config WHERE config_name = 'disable_categories'");
\$disable_categories = mysql_fetch_array(\$disable_categories_query);
\$disable_categories = \$disable_categories["config_value"];

\$categoriesquery = mysql_query("SELECT * FROM vsns_config WHERE config_name = 'categories'");
\$categories = mysql_fetch_array(\$categoriesquery);
\$categories = \$categories["config_value"];
\$categories = trim(\$categories);

\$show_date_query = mysql_query("SELECT * FROM vsns_config WHERE config_name = 'show_date'");
\$show_date = mysql_fetch_array(\$show_date_query);
\$show_date = \$show_date["config_value"];
\$show_author_query = mysql_query("SELECT * FROM vsns_config WHERE config_name = 'show_author'");
\$show_author = mysql_fetch_array(\$show_author_query);
\$show_author = \$show_author["config_value"];

\$disable_comments_query = mysql_query("SELECT * FROM vsns_config WHERE config_name = 'disable_comments'");
\$disable_comments = mysql_fetch_array(\$disable_comments_query);
\$disable_comments = \$disable_comments["config_value"];

\$path_query = mysql_query("SELECT * FROM vsns_config WHERE config_name = 'path'");
\$path = mysql_fetch_array(\$path_query);
\$path = \$path["config_value"];

\$hlevel_query = mysql_query("SELECT * FROM vsns_config WHERE config_name = 'hlevel'");
\$hlevel = mysql_fetch_array(\$hlevel_query);
\$hlevel = \$hlevel["config_value"];

\$email_query = mysql_query("SELECT * FROM vsns_config WHERE config_name = 'email'");
\$email = mysql_fetch_array(\$email_query);
\$email = \$email["config_value"];

\$notification_query = mysql_query("SELECT * FROM vsns_config WHERE config_name = 'notification'");
\$notification = mysql_fetch_array(\$notification_query);
\$notification = \$notification["config_value"];

\$sitename_query = mysql_query("SELECT * FROM vsns_config WHERE config_name = 'sitename'");
\$sitename = mysql_fetch_array(\$sitename_query);
\$sitename = \$sitename["config_value"];

\$desc_query = mysql_query("SELECT * FROM vsns_config WHERE config_name = 'desc'");
\$desc = mysql_fetch_array(\$desc_query);
\$desc = \$desc["config_value"];

\$cright_query = mysql_query("SELECT * FROM vsns_config WHERE config_name = 'copyright'");
\$cright = mysql_fetch_array(\$cright_query);
\$cright = \$cright["config_value"];

\$website_query = mysql_query("SELECT * FROM vsns_config WHERE config_name = 'website'");
\$website = mysql_fetch_array(\$website_query);
\$website = \$website["config_value"];

mysql_free_result(\$versionquery);
mysql_free_result(\$limitquery);
mysql_free_result(\$headlinequery);
mysql_free_result(\$disp_orderquery);
mysql_free_result(\$on_expiryquery);
mysql_free_result(\$prefixesquery);
mysql_free_result(\$disable_categories_query);
mysql_free_result(\$categoriesquery);
mysql_free_result(\$show_date_query);
mysql_free_result(\$show_author_query);
mysql_free_result(\$disable_comments_query);
mysql_free_result(\$path_query);
mysql_free_result(\$hlevel_query);
mysql_free_result(\$email_query);
mysql_free_result(\$notification_query);
mysql_free_result(\$sitename_query);
mysql_free_result(\$desc_query);
mysql_free_result(\$cright_query);
mysql_free_result(\$website_query);

//Include these files
include "functions/comments_functions.php";
include "functions/config_functions.php";
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
?>

<p class="response">Upgrade complete.  Delete this file.  Also, please visit the Blog Options to configure some new options not present prior to the upgrade.</p>

<div id="footer">
	<p>Powered by VSNS Lemon 3.1 &copy; 2005 by <a href="http://tachyondecay.net">Tachyon</a>.  All rights reserved.</p>
</div>

</body>
</html>