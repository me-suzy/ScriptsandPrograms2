<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<link rel="stylesheet" type="text/css" href="styles/grey.css">
<title>sCssBoard installer</title>
</head>

<body>

<? if (!$_GET[step]) { 	?>
<div class='catheader' style='width:520px; float:left;'>sCssBoard Installation - Step 1</div>
<div class='msg_content' style='width:500px; float:left;'><p>Welcome to sCssBoard! You probably want to begin using your new message board as soon as possible, so let's get started.</p>
<p>
<?
if(!is_writable("system")) { echo "<strong>The <em>/system</em> directory needs to be CHMOD'ed to 777 before you continue.<br />"; }
if (file_exists("system/config.inc.php")) {
	include("system/config.inc.php");
	$db_host = $_CON[host];
	$db_prefix = $_CON[prefix];
} else {
	$db_host = "localhost";
	$db_prefix = "scb_";
}

?>First, you need to enter your MySQL database information. </p>
<form method="post" action="install.php?step=writeconfig" name="sqlForm">
<table width='400' border='0' cellpadding='2' cellspacing='2' align='center'>
<tr>
	<td colspan='2' class='catheader' align='center' style='font-size:14px;'>
		<strong>MySQL Settings</strong>
	</td>
</tr>
<tr>
	<td class='forum_stat_hd' width='200'>MySQL Server (usually localhost):</td>
<td class='forum_name' width='200'><input type='text' class='input' size='30' name='sql_host' value='<?=$db_host?>'></td>
</tr>
<tr>
	<td class='forum_stat_hd' width='200'>MySQL Username:</td>
<td class='forum_name' width='200'><input type='text' class='input' size='30' name='sql_user' value='<?=$_CON[user]?>'></td>
</tr>
<tr>
	<td class='forum_stat_hd' width='200'>MySQL Password:</td>
<td class='forum_name' width='200'><input type='password' class='input' size='30' name='sql_pass' value='<?=$_CON[pass]?>'></td>
</tr>
<tr>
	<td class='forum_stat_hd' width='200'>MySQL Database:</td>
<td class='forum_name' width='200'><input type='text' class='input' size='30' name='sql_db' value='<?=$_CON[name]?>'></td>
</tr>
<tr>
	<td class='forum_stat_hd' width='200'>MySQL Table Name Prefix:</td>
<td class='forum_name' width='200'><input type='text' class='input' size='30' name='sql_prefix' value='<?=$db_prefix?>'></td>
</tr>
<tr>
	<td class='forum_stat_hd' width='200'><strong>Upgrade from 1.0:<strong></td>
<td class='forum_name' width='200'><input type="checkbox" name="upgrade_10"></td>
</tr>
</table>
<p align='center'>
<em>Please double-check that you have entered all information correctly before proceeding.</em><br /><br />
<span class='main_button'><a href='javascript:document.sqlForm.submit();'>Continue >></a></span>
</p>
</form>
</div>
<img src='http://scssboard.if-hosting.com/installer_11.gif' alt='' />
<? } elseif ($_GET[step] == "writeconfig") {

	if ($_POST[sql_db] == "") {
		die("You did not enter a database for sCssBoard. Please go back and do so.");
	}

//If config.inc.php exists and we're not upgrading from 1.0, show the error
	if (!$_POST[upgrade_10]) {
	if (is_file("system/config.inc.php")) {
		die("The configuration file already exists. If you wish to reinstall or upgrade sCssBoard, you need to delete <em>/system/config.inc.php</em> first.");
	}
//Or else destroy config.inc.php so we can create a new one
	if (file_exists("system/config.inc.php")) { unlink("system/config.inc.php"); }
	}

	if (!is_writable("system")) {
		die("The <em>/system</em> directory needs to be CHMOD'ed to 777 in order to continue. Please CHMOD it then go back and try again.");
	}

	$config_file = fopen("system/config.inc.php", "w");
	$date = date("F j, Y, g:i a");

	fputs($config_file, "<?php\n //sCssBoard Configuration File 1.1 \n //Generated on $date \n\n");
	fputs($config_file, "\$_CON[host] = \"$_POST[sql_host]\";\n");
	fputs($config_file, "\$_CON[user] = \"$_POST[sql_user]\";\n");
	fputs($config_file, "\$_CON[pass] = \"$_POST[sql_pass]\";\n");
	fputs($config_file, "\$_CON[name] = \"$_POST[sql_db]\";\n");
	fputs($config_file, "\$_CON[prefix] = \"$_POST[sql_prefix]\";\n\n?>");

	fclose($config_file);

if (!$_POST[upgrade_10]) {
?>

	<div class='catheader' style='width:520px; float:left;'>sCssBoard Installation - Step 1b</div>
	<div class='msg_content' style='width:500px; float:left;'><p>The configuration file was successfully written.</p>
		<form action='install2.php' method='post' name='inst_verify' />
		<input type='hidden' name='i1complete' value='yes' />
		<p><span class='main_button'><a href='javascript:document.inst_verify.submit();'>Continue >></a></span></p></form></div>
<?
} elseif ($_POST[upgrade_10]) {

	echo "<strong>Upgrading from 1.0 to 1.1...</strong><br /> <br />";

	include("system/connect.inc.php");

	echo "Running 3 database queries...";

	mysql_query("ALTER TABLE `$_CON[prefix]posts` TYPE = MYISAM");
	mysql_query("ALTER TABLE $_CON[prefix]posts ADD FULLTEXT(posts_body);");
	mysql_query("ALTER TABLE `$_CON[prefix]forums` ADD `forums_description` TEXT NOT NULL AFTER `forums_name` ;");
?>
<br /><br />
<div class='catheader' style='width:520px; float:left;'>sCssBoard Upgrade Completed</div>
	<div class='msg_content' style='width:500px; float:left;'><p>Your database has been updated to 1.1. Please delete the install.php and install2.php files.<br /><br />You may need to clear your web browser cache (Ctrl-F5) to load the new style.</p>
		<p><span class='main_button'><a href='index.php'>Continue >></a></span></p></div>
<?
}
}
?>
</body>
</html>
