<?php
/***************************************************************************
*	Very Simple News System
*	Version: 3.1.1
*	Filename: config_functions.php
*	Description: All comment-managing functions
****************************************************************************
*	Build Date: July 20, 2005
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

//Blog options, eh
function blog_form()
{
	global $vsnsemail, $sitename, $desc, $cright, $path, $notification, $website, $serverpath, $queue;

	$mod = $_GET["mod"];
	if ($mod == "yes")
	{
		echo '<p class="response">Blog options updated.</p>';
	}
?>
<form id="blogopt" action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post">

<div class="vsns_form">
	<span class="vsns_label">
		<label for="sitename">Blog Name:</label>
	</span>
	<span class="vsns_field">
		<input type="hidden" name="act" value="blog_update" />
		<input type="text" id="sitename" name="sitename" value="<?php echo $sitename; ?>" />
	</span>
</div>

<div class="vsns_form">
	<span class="vsns_label">
		<label for="website">Website:</label>
	</span>
	<span class="vsns_field">
		<input type="text" id="website" name="website" value="<?php echo $website; ?>" />
	</span>
</div>

<div class="vsns_form">
	<span class="vsns_label">
		<label for="vsnsemail">Email:</label>
	</span>
	<span class="vsns_field">
		<input type="text" id="vsnsemail" name="vsnsemail" value="<?php echo $vsnsemail; ?>" />
	</span>
</div>

<div class="vsns_form">
	<span class="vsns_label">
		<label for="notification">Email Notification of Comments:</label>
	</span>
	<span class="vsns_field">
<?php
	if ($notification == 1)
	{
?>
		<input type="checkbox" id="notification" name="notification" value="1" checked="check" />
<?php
	}
	else
	{
?>
		<input type="checkbox" id="notification" name="notification" value="1" />
<?php
	}
?>
	</span>
</div>

<div class="vsns_form">
	<span class="vsns_label">
		<label for="queue">Queue Comments?</label>
	</span>
	<span class="vsns_field">
<?php
	if ($queue == 1)
	{
?>
		<input type="checkbox" id="queue" name="queue" value="1" checked="check" />
<?php
	}
	else
	{
?>
		<input type="checkbox" id="queue" name="queue" value="1" />
<?php
	}
?>
	</span>
</div>

<div class="vsns_form">
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
		<label for="path">Path to Blog</label>:
	</span>
	<span class="vsns_field">
		<input type="text" id="path" name="path" value="<?php echo $path; ?>" />
	</span>
</div>

<div class="vsns_form">
	<span class="vsns_label">
		<label for="serverpath">Server Path to Blog</label>:
	</span>
	<span class="vsns_field">
		<input type="text" id="serverpath" name="serverpath" value="<?php echo $serverpath; ?>" />
	</span>
</div>

<div class="vsns_form vsns_buttons">
	<input type="submit" class="button" value="Update Blog Options" />
</div>
</form>
<?php
}

//Update blog
function blog_update()
{
	extract($_POST, EXTR_SKIP);

	if ($queue != 1)
	{
		$queue = 0;
	}

	if ($notification != 1)
	{
		$notification = 0;
	}

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
		$serverpath .= "/";
	}

	mysql_query("UPDATE vsns_config SET config_value = '$sitename' WHERE config_name = 'sitename'");
	mysql_query("UPDATE vsns_config SET config_value = '$email' WHERE config_name = 'email'");
	mysql_query("UPDATE vsns_config SET config_value = '$cright' WHERE config_name = 'copyright'");
	mysql_query("UPDATE vsns_config SET config_value = '$desc' WHERE config_name = 'desc'");
	mysql_query("UPDATE vsns_config SET config_value = '$path' WHERE config_name = 'path'");
	mysql_query("UPDATE vsns_config SET config_value = '$website' WHERE config_name = 'website'");
	mysql_query("UPDATE vsns_config SET config_value = '$notification' WHERE config_name = 'notification'");
	mysql_query("UPDATE vsns_config SET config_value = '$queue' WHERE config_name = 'queue'");
	mysql_query("UPDATE vsns_config SET config_value = '$serverpath' WHERE config_name = 'serverpath'");

	header ("Location: ".$path."admin.php?act=blog_config&mod=yes");
}

//Change password form
function changepass_form()
{
?>
<form id="generalconfig" action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post">

<div class="vsns_form">
	<span class="vsns_label">
		<label for="newpassword">New Password:</label>
	</span>
	<span class="vsns_field">
		<input type="password" id="newpassword" name="newpassword" />
		<input type="hidden" id="act" name="act" value="changepass_update" />
	</span>
</div>

<div class="vsns_form">
	<span class="vsns_label">
		<label for="confirmpass">Confirm Password:</label>
	</span>
	<span class="vsns_field">
		<input type="password" id="confirmpass" name="confirmpass" />
	</span>
</div>

<div class="vsns_form vsns_buttons">
	<input type="submit" class="button" value="Change Password" />
</div>
</form>
<?php
}

//Update password
function changepass_update()
{
	$newpass = $_POST["newpassword"];
	$confirmpass = $_POST["confirmpass"];

	if ($newpass === $confirmpass)
	{
		$newpass = md5($newpass);
		mysql_query ("UPDATE vsns_config SET config_value = '$newpass' WHERE config_name = 'password'");
		echo "<p class=\"response\">Password changed.</p>";
	}

	else
	{
		echo "<p class=\"response\">Passwords did not match, please try again.</p>";
	}

	changepass_form();
}

//Disply MySQL information as a form
function mysql_config()
{
	global $server, $user, $db, $version, $path;
?>
<h2 class="configheader">MySQL Configuration</h2>

<form id="mysql" action="<?php echo $_SERVER["PHP_SELF"];?>" method="post">
	<input type="hidden" id="act" name="act" value="mysql_update" />

<div class="vsns_form">
	<span class="vsns_label">
		<label for="dbserver">Server/Host:</label>
	</span>
	<span class="vsns_field">
		<input type="text" id="dbserver" name="dbserver" value="<?php echo $server;?>" />
	</span>
</div>
<div class="vsns_form">
	<span class="vsns_label">
		<label for="dbuser">User:</label>
	</span>
	<span class="vsns_field">
		<input type="text" id="dbuser" name="dbuser" value="<?php echo $user;?>" />
	</span>
</div>
<div class="vsns_form">
	<span class="vsns_label">
		<label for="dbpass">Password:</label>
	</span>
	<span class="vsns_field">
		<input type="password" id="dbpass" name="dbpass" />
	</span>
</div>
<div class="vsns_form">
	<span class="vsns_label">
		<label for="db">Database:</label>
	</span>
	<span class="vsns_field">
		<input type="text" id="db" name="db" value="<?php echo $db;?>" />
	</span>
</div>
<div class="vsns_form" style="text-align: center;">
	<input type="submit" class="button" value="Update MySQL Settings" />
</div>

</form>
<?php
}

//Update the MySQL config info
function mysql_update()
{
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
	echo "<p class=\"response\">MySQL settings updated.</p>";
	mysql_config();
}

/*******
***	News Options
*******/

function newsopt_form()
{
	global $limit, $headline, $disp_order, $on_expiry, $prefixes, $disable_categories, $categories, $show_date, $show_author, $disable_comments, $hlevel;

	$mod = $_GET["mod"];
	if ($mod == "yes")
	{
		echo '<p class="response">News options updated.</p>';
	}

?>
<form id="newsopts" action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post">

<div class="vsns_form">
	<span class="vsns_label">
		<label for="limit">Number of Articles to Display:</label>
	</span>
	<span class="vsns_field">
		<input type="hidden" name="act" value="newsopt_update" />
		<input type="text" id="limit" name="limit" size="2" value="<?php echo $limit; ?>" />
	</span>
</div>

<div class="vsns_form">
	<span class="vsns_label">
		<label for="headline">Display only the headline?</label>
	</span>
	<span class="vsns_field">
<?php
	if ($headline == 1)
	{
?>
		<input type="checkbox" id="headline" name="headline" value="1" checked="check" />
<?php
	}
	else
	{
?>
		<input type="checkbox" id="headline" name="headline" value="1" />
<?php
	}
?>
	</span>
</div>
<div class="vsns_form">
	<span class="vsns_label">
		<label for="disp_order">Display Order:</label>
	</span>
	<span class="vsns_field">
<?php
	if ($disp_order == "ASC")
	{
?>
		<input type="radio" name="disp_order" value="ASC" checked="checked" />Oldest First
			&nbsp;&nbsp;&nbsp;&nbsp;
		<input type="radio" name="disp_order" value="DESC" />Newest First
<?php
	}
	if ($disp_order == "DESC")
	{
?>
		<input type="radio" name="disp_order" value="ASC" />Oldest First
			&nbsp;&nbsp;&nbsp;&nbsp;
		<input type="radio" name="disp_order" value="DESC" checked="checked" />Newest First
<?php
	}
?>
	</span>
</div>
<div class="vsns_form">
	<span class="vsns_label">
		<label for="on_expiry">What to do with expired articles:</label>
	</span>
	<span class="vsns_field">
<?php
	if ($on_expiry == "delete")
	{
?>
		<input type="radio" name="on_expiry" value="delete" checked="checked" />Delete
			&nbsp;&nbsp;&nbsp;&nbsp;
		<input type="radio" name="on_expiry" value="unpin" />Unpin
<?php
	}
	if ($on_expiry == "unpin")
	{
?>
		<input type="radio" name="on_expiry" value="delete" />Delete
			&nbsp;&nbsp;&nbsp;&nbsp;
		<input type="radio" name="on_expiry" value="unpin" checked="checked" />Unpin
<?php
	}
?>
	</span>
</div>
<div class="vsns_form">
	<span class="vsns_label">
		<label for="prefixes">Pinned Article Prefixes:</label> (one per line!)
	</span>
	<span class="vsns_field">
		<textarea id="prefixes" name="prefixes" cols="15" rows="5">
<?php
	echo $prefixes;
?></textarea>
	</span>
</div>
<div class="vsns_form">
	<span class="vsns_label">
		<label for="disable_categories">Disable categories?</label>
	</span>
	<span class="vsns_field">
<?php
	if ($disable_categories == 1)
	{
?>
		<input type="checkbox" id="disable_categories" name="disable_categories" value="1" checked="check" />
<?php
	}
	else
	{
?>
		<input type="checkbox" id="disable_categories" name="disable_categories" value="1" />
<?php
	}
?>
	</span>
</div>
<div class="vsns_form">
	<span class="vsns_label">
		<label for="categories">Categories:</label> (one per line!)
	</span>
	<span class="vsns_field">
		<textarea id="categories" name="categories" cols="15" rows="5">
<?php
	echo $categories;
?></textarea>
	</span>
</div>
<div class="vsns_form">
	<span class="vsns_label">
		<label for="show_date">Automatically display date below each article?</label>
	</span>
	<span class="vsns_field">
<?php
	if ($show_date == 1)
	{
?>
		<input type="checkbox" id="show_date" name="show_date" value="1" checked="check" />
<?php
	}
	else
	{
?>
		<input type="checkbox" id="show_date" name="show_date" value="1" />
<?php
	}
?>
	</span>
</div>
<div class="vsns_form">
	<span class="vsns_label">
		<label for="show_author">Automatically display author below each article?</label>
	</span>
	<span class="vsns_field">
<?php
	if ($show_author == 1)
	{
?>
		<input type="checkbox" id="show_author" name="show_author" value="1" checked="check" />
<?php
	}
	else
	{
?>
		<input type="checkbox" id="show_author" name="show_author" value="1" />
<?php
	}
?>
	</span>
</div>
<div class="vsns_form">
	<span class="vsns_label">
		<label for="disable_comments">Disable comments?</label>
	</span>
	<span class="vsns_field">
<?php
	if ($disable_comments == 1)
	{
?>
		<input type="checkbox" id="disable_comments" name="disable_comments" value="1" checked="check" />
<?php
	}
	else
	{
?>
		<input type="checkbox" id="disable_comments" name="disable_comments" value="1" />
<?php
	}
?>
	</span>
</div>
<div class="vsns_form">
	<span class="vsns_label">
		<label for="hlevel">News Heading Level:</label>
	</span>
	<span class="vsns_field">
		<select name="hlevel" id="hlevel">
<?php
	switch ($hlevel)
	{
		case 1:
?>
			<option value="1" selected="selected">H1</option>
			<option value="2">H2</option>
			<option value="3">H3</option>
			<option value="4">H4</option>
			<option value="5">H5</option>
			<option value="6">H6</option>
<?php
		break;

		default:
		case 2:
?>
			<option value="1">H1</option>
			<option value="2" selected="selected">H2</option>
			<option value="3">H3</option>
			<option value="4">H4</option>
			<option value="5">H5</option>
			<option value="6">H6</option>
<?php
		break;

		case 3:
?>
			<option value="1">H1</option>
			<option value="2">H2</option>
			<option value="3" selected="selected">H3</option>
			<option value="4">H4</option>
			<option value="5">H5</option>
			<option value="6">H6</option>
<?php
		break;

		case 4:
?>
			<option value="1">H1</option>
			<option value="2">H2</option>
			<option value="3">H3</option>
			<option value="4" selected="selected">H4</option>
			<option value="5">H5</option>
			<option value="6">H6</option>
<?php
		break;

		case 5:
?>
			<option value="1">H1</option>
			<option value="2">H2</option>
			<option value="3">H3</option>
			<option value="4">H4</option>
			<option value="5" selected="selected">H5</option>
			<option value="6">H6</option>
<?php
		break;

		case 6:
?>
			<option value="1">H1</option>
			<option value="2">H2</option>
			<option value="3">H3</option>
			<option value="4">H4</option>
			<option value="5">H5</option>
			<option value="6">H6</option>
<?php
		break;
	}

?>
		</select>
	</span>
</div>
<div class="vsns_form vsns_buttons">
	<input type="submit" value="Update News Options" />
</div>
</form>
<?php
}

//Update the news options
function newsopt_update()
{
	$limit = $_POST["limit"];
	$headline = $_POST["headline"];
	$disp_order = $_POST["disp_order"];
	$on_expiry = $_POST["on_expiry"];
	$prefixes = $_POST["prefixes"];
	$prefixes = trim($prefixes);
	$disable_categories = $_POST["disable_categories"];
	$categories = $_POST["categories"];
	$categories = trim($categories);
	$show_date = $_POST["show_date"];
	$show_author = $_POST["show_author"];
	$disable_comments = $_POST["disable_comments"];
	$hlevel = $_POST["hlevel"];

	if ($headline != 1)
	{
		$headline = 0;
	}
	if ($disable_categories != 1)
	{
		$disable_categories = 0;
	}
	if ($show_date != 1)
	{
		$show_date = 0;
	}
	if ($show_author != 1)
	{
		$show_author = 0;
	}
	if ($disable_comments != 1)
	{
		$disable_comments = 0;
	}

	mysql_query("UPDATE vsns_config SET config_value = '$limit' WHERE config_name='limit'") or die(mysql_error());
	mysql_query("UPDATE vsns_config SET config_value = '$headline' WHERE config_name='headline'") or die(mysql_error());
	mysql_query("UPDATE vsns_config SET config_value = '$disp_order' WHERE config_name='disp_order'") or die(mysql_error());
	mysql_query("UPDATE vsns_config SET config_value = '$on_expiry' WHERE config_name='on_expiry'") or die(mysql_error());
	mysql_query("UPDATE vsns_config SET config_value = '$prefixes' WHERE config_name='prefixes'") or die(mysql_error());
	mysql_query("UPDATE vsns_config SET config_value = '$disable_categories' WHERE config_name='disable_categories'") or die(mysql_error());
	mysql_query("UPDATE vsns_config SET config_value = '$categories' WHERE config_name='categories'") or die(mysql_error());
	mysql_query("UPDATE vsns_config SET config_value = '$show_date' WHERE config_name='show_date'") or die(mysql_error());
	mysql_query("UPDATE vsns_config SET config_value = '$show_author' WHERE config_name='show_author'") or die(mysql_error());
	mysql_query("UPDATE vsns_config SET config_value = '$disable_comments' WHERE config_name='disable_comments'") or die(mysql_error());
	mysql_query("UPDATE vsns_config SET config_value = '$hlevel' WHERE config_name='hlevel'") or die(mysql_error());
	echo "<p class=\"response\">Configuration updated.</p>";

	header ("Location: ".$path."admin.php?act=news_config&mod=yes");
}
?>