<?php
/***************************************************************************
*	Vanilla Guestbook
*	Version: 0.1
*	Filename: config_functions.php
*	Description: All comment-managing functions
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

//Blog options, eh
function bk_form()
{
	global $bkemail, $path, $notification, $queue, $allow, $hlevel, $limit, $disp_order, $serverpath, $bkname;

	$mod = $_GET["mod"];
	if ($mod == "yes")
	{
		echo '<p class="response">Guestbook options updated.</p>';
	}
?>
<h1 class="configheader">Guestbook Options</h1>
<form id="blogopt" action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post">

<div class="bk_form">
	<input type="hidden" name="act" value="bk_update" />
	<span class="bk_label">
		<label for="bk_name">Guestbook Name:</label>
	</span>
	<span class="bk_field">
		<input type="text" id="bk_name" name="bk_name" value="<?php echo $bkname; ?>" />
	</span>
</div>

<div class="bk_form">
	<span class="bk_label">
		<label for="bkemail">Email:</label>
	</span>
	<span class="bk_field">
		<input type="text" id="bkemail" name="bkemail" value="<?php echo $bkemail; ?>" />
	</span>
</div>

<div class="bk_form">
	<span class="bk_label">
		<label for="notification">Email Notification of New Entries?</label>
	</span>
	<span class="bk_field">
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

<div class="bk_form">
	<span class="bk_label">
		<label for="allow">Allow New Entries?</label>
	</span>
	<span class="bk_field">
<?php
	if ($allow == 1)
	{
?>
		<input type="checkbox" id="allow" name="allow" value="1" checked="check" />
<?php
	}
	else
	{
?>
		<input type="checkbox" id="allow" name="allow" value="1" />
<?php
	}
?>
	</span>
</div>

<div class="bk_form">
	<span class="bk_label">
		<label for="queue">Queue New Entries?</label>
	</span>
	<span class="bk_field">
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

<div class="bk_form">
	<span class="bk_label">
		<label for="hlevel">Heading Level</label>:
	</span>
	<span class="bk_field">
		<select id="hlevel" name="hlevel">
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
			<option value="5" selected="selected">H5</option>
			<option value="6">H6</option>
<?php
		break;
	}

?>
		</select>
	</span>
</div>

<div class="bk_form">
	<span class="bk_label">
		<label for="disp_order">Display Order</label>:
	</span>
	<span class="bk_field">
<?php
	if ($disp_order == "ASC")
	{
?>
		<input type="radio" id="disp_order" name="disp_order" value="ASC" checked="checked" />Oldest to Newest
		<input type="radio" id="disp_order" name="disp_order" value="DESC" />Newest to Oldest
<?php
	}
	if ($disp_order == "DESC")
	{
?>
		<input type="radio" id="disp_order" name="disp_order" value="ASC" />Oldest to Newest
		<input type="radio" id="disp_order" name="disp_order" value="DESC" checked="checked" />Newest to Oldest
<?php
	}
?>
	</span>
</div>

<div class="bk_form">
	<span class="bk_label">
		<label for="limit"><abbr title="Number">No.</abbr> of comments per page</label>
	</span>
	<span class="bk_field">
		<input type="text" id="limit" name="limit" size="3" value="<?php echo $limit;?>" />
	</span>
</div>

<div class="bk_form">
	<span class="bk_label">
		<label for="path">Access Path to Guestbook</label>:
	</span>
	<span class="bk_field">
		<input type="text" id="path" name="path" value="<?php echo $path; ?>" />
	</span>
</div>

<div class="bk_form">
	<span class="bk_label">
		<label for="serverpath">Server Path to Guestbook</label>:
	</span>
	<span class="bk_field">
		<input type="text" id="serverpath" name="serverpath" value="<?php echo $serverpath; ?>" />
	</span>
</div>

<div class="bk_form bk_buttons">
	<input type="submit" class="button" value="Update Guestbook Options" />
</div>
</form>
<?php
}

//Update guestbook
function bk_update()
{
	$bkname = $_POST["bk_name"];
	$bkemail = $_POST["bkemail"];
	$path = $_POST["path"];
	$notification = $_POST["notification"];
	$limit = $_POST["limit"];
	$allow = $_POST["allow"];
	$disp_order = $_POST["disp_order"];
	$hlevel = $_POST["hlevel"];
	$queue = $_POST["queue"];
	$serverpath = $_POST["serverpath"];

	if ($allow != 1)
	{
		$allow = 0;
	}

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

	if (is_numeric($limit))
	{
		mysql_query("UPDATE vanilla_config SET config_value = '$bkemail' WHERE config_name = 'bkemail'");
		mysql_query("UPDATE vanilla_config SET config_value = '$path' WHERE config_name = 'path'");
		mysql_query("UPDATE vanilla_config SET config_value = '$notification' WHERE config_name = 'notification'");
		mysql_query("UPDATE vanilla_config SET config_value = '$limit' WHERE config_name = 'limit'");
		mysql_query("UPDATE vanilla_config SET config_value = '$allow' WHERE config_name = 'allow'");
		mysql_query("UPDATE vanilla_config SET config_value = '$disp_order' WHERE config_name = 'disp_order'");
		mysql_query("UPDATE vanilla_config SET config_value = '$hlevel' WHERE config_name = 'hlevel'");
		mysql_query("UPDATE vanilla_config SET config_value = '$queue' WHERE config_name = 'queue'");
		mysql_query("UPDATE vanilla_config SET config_value = '$serverpath' WHERE config_name = 'serverpath'");
		mysql_query("UPDATE vanilla_config SET config_value = '$bkname' WHERE config_name = 'bkname'");

		header ("Location: ".$path."admin.php?act=bk_config&mod=yes&allow=$allow");
	}
	else
	{
		echo "<p class=\"response\">The value of the Number of Articles per page <em>must</em> be numeric.</p>";
	}
}

//Change password form
function changepass_form()
{
?>
<form id="generalconfig" action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post">

<div class="bk_form">
	<span class="bk_label">
		<label for="newpassword">New Password:</label>
	</span>
	<span class="bk_field">
		<input type="password" id="newpassword" name="newpassword" />
		<input type="hidden" name="act" value="changepass_update" />
	</span>
</div>

<div class="bk_form">
	<span class="bk_label">
		<label for="confirmpass">Confirm Password:</label>
	</span>
	<span class="bk_field">
		<input type="password" id="confirmpass" name="confirmpass" />
	</span>
</div>

<div class="bk_form bk_buttons">
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
		mysql_query ("UPDATE vanilla_config SET config_value = '$newpass' WHERE config_name = 'password'");
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
	global $server, $user, $db, $path;
?>
<h1 class="configheader">MySQL Configuration</h1>

<form id="mysql" action="<?php echo $_SERVER["PHP_SELF"];?>" method="post">
	<input type="hidden" id="act" name="act" value="mysql_update" />

<div class="bk_form">
	<span class="bk_label">
		<label for="dbserver">Server/Host:</label>
	</span>
	<span class="bk_field">
		<input type="text" id="dbserver" name="dbserver" value="<?php echo $server;?>" />
	</span>
</div>
<div class="bk_form">
	<span class="bk_label">
		<label for="dbuser">User:</label>
	</span>
	<span class="bk_field">
		<input type="text" id="dbuser" name="dbuser" value="<?php echo $user;?>" />
	</span>
</div>
<div class="bk_form">
	<span class="bk_label">
		<label for="dbpass">Password:</label>
	</span>
	<span class="bk_field">
		<input type="password" id="dbpass" name="dbpass" />
	</span>
</div>
<div class="bk_form">
	<span class="bk_label">
		<label for="db">Database:</label>
	</span>
	<span class="bk_field">
		<input type="text" id="db" name="db" value="<?php echo $db;?>" />
	</span>
</div>
<div class="form" style="text-align: center;">
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
\$configdata = array("version", "limit", "disp_order", "path", "banned_ip", "hlevel", "allow", "email", "notification", "queue", "serverpath", "bkname");
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
PIZZA;

	$handle = fopen('settings.php', 'wb');
	fwrite($handle, $filecontent);
	fclose($handle);
	echo "<p class=\"response\">MySQL settings updated.</p>";
	mysql_config();
}
?>