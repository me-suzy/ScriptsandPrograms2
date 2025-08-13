<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Setup - Installation - Step 1</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel="stylesheet" type="text/css" href="css/style.css" title="default" />
</head>

<body>
<table width="100%" height="100%" border="0" cellpadding="0" cellspacing="0">
<tr>
<td valign="top"><hr width="1" size="1" color="#FFFFFF">
<table class="admin_main" align="center" border="1">
<tr>
<td class="admin_title"><table class="admin_title_table">
<tr>
<td class="large admin_title">Olate Download - Download Management Script</td>
</tr>
</table></td>
</tr>
<tr>
<td class="admin_breadcrumb">
<table width="99%"  border="0" align="center" cellpadding="0" cellspacing="0">
<tr>
<td width="100%"><strong>Setup - Installation - Step 1</strong></td>
</tr>
</table></td>
</tr>
<tr>
<td valign="top" bordercolor="#FFFFFF"><table width="100%" border="0" cellspacing="0" cellpadding="0">
<tr>
<td><p>Welcome to the Olate Download Download Management Script Installation. </p>
<p>This installation will guide you through the steps required to install Olate Download. It will create the database tables and generate a db.php file which will contain essential details needed for the script to work.</p>
<p><strong>Requirements</strong></p>
<p>In order for you to be able to use Olate Download, you must have certain minimum requirements. The installer has checked to make sure these are available, if not, a red marker will appear next to them and you will be unable to proceed with the installation:</p>
<table width="144" border="0" cellpadding="0" cellspacing="0">
<tr>
<td>&#8226; PHP 4.1.0+ </td>
<td width="20">	
<?php
if(version_compare(phpversion(), '4.1.0') == -1) 
{
	echo '<img src="images/problem.gif" alt="Problem" />';
	$problem = 1;
} else {
	echo '<img src="images/ok.gif" alt="Ok" />';
}
?></td>
</tr>
<tr>
<td>&#8226; MySQL Available</td>
<td><?php
if (!extension_loaded('mysql')) 
{
	echo '<img src="images/problem.gif" alt="Problem" />';
	$problem = 1;
} else {
	echo '<img src="images/ok.gif" alt="Ok" />';
}
?></td>
</tr>
<tr>
<td>&#8226; db.php writable </td>
<td><?php
if (!is_writable('../includes/db.php')) 
{
	echo '<img src="images/problem.gif" alt="Problem" />';
	$problem = 1;
} else {
	echo '<img src="images/ok.gif" alt="Ok" />';
}?></td>
</tr>
</table>
<?php
if (!is_writable('../includes/db.php'))
{
	$problem = 1;
	echo '<p>In order for the installation to write the data to db.php, this file needs to be writable. According to checks performed by the installer, db.php <strong>is not writable</strong>. In order to continue you need to make db.php writable. The most common way to do this (for Linux) is to chmod db.php to 777.';
}
?>
</p>
<p><?php
if ($problem == 1)
{
	echo 'It appears that your server does not match the requirements to allow installation/use of Olate Download. <a href="install1.php">Click here to re-check your server.</a>';
} else {
?>Your server matches the requirements needed to install Olate Download. Before proceeding, please read the license argreement below:</p>
<p><iframe src="license.php" width="100%" height="300" align="middle"></iframe></p>
<p>By continuing with this installation you show that you agree to the license as detailed above.</p>
<p><form action="install2.php" method="post">
<input type="submit" value="Continue" name="Submit">
</form>
</p></td>
<?php } ?>
</tr>
</table></td>
</tr>
<tr>
<td height="25" valign="middle" bordercolor="#FFFFFF" bgcolor="#E3E8EF">
<!--Begin Credit Line. Please leave-->
<div align="center"><span class="small"><a href="http://www.olate.com" target="_blank">Powered 
by Olate Download v2.2.0 </a></span></div></td>
</tr>
</table>
<hr width="1" size="1" color="#FFFFFF"></td>
</tr>
</table>
</body>
</html>
