<?php
	include("checksession.php");
	include_once "./includes/settings.php";
	include_once "./classes/user.php";
	
	$user = unserialize($_SESSION['enduser']);
?>
<html>
<head>
<title>Configure your Help Desk Accounts</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta name="Designed by Chad Edwards" content="QuickIntranet.com">
<link href="style.css" rel="stylesheet" type="text/css">
</head>

<body bgcolor="#FFFFFF" text="#000000" link="#0000FF" alink="#FF0000" vlink="#0000FF">
<table cellpadding="0" cellspacing="0" border="0">
	<tr><td colspan="2" align="center">
	<?php
		if ($OBJ->get('navigation') == 'B') {
			include_once "./dataaccessheader.php";
		}
		else {
			include_once "./textnavsystem.php";
		}
	?>
		<br/><strong>The Helpdesk Control Panel</strong>
	</td></tr>
	<?php
		//decide which design schema to follow
		if ($user->get('securityLevel', 'intval') == ADMIN_SECURITY_LEVEL) {
	?>
	<tr>
		<td align="right"><a href="settings/uninstall.php"><img src="images/uninstall.jpg" border="0" /></a></td>
		<td><a href="settings/file_upload.php"><img src="images/file.upload.gif" border="0" /></a></td>
	</tr>
	<tr>
		<td align="right"><a href="settings/view_users.php"><img src="images/manageuser.jpg" border="0" /></a></td>
		<td><a href="settings/problem_category.php"><img src="images/catmanage.jpg" border="0" /></a></td>
	</tr>
	<tr>
		<td align="right"><a href="settings/priority.php"><img src="images/priority.gif" border="0" /></a></td>
		<td><a href="settings/status.php"><img src="images/status.jpg" border="0" /></a></td>
	</tr>
	<?php
		}
	?>
	<tr>
		<td align="right""><a href="settings/passwd.php"><img src="images/passwd.jpg" border="0" /></a></td>
		<td><a href="settings/settings.php"><img src="images/settings.jpg" border="0" /></a></td>
	</tr>
</table>
</body>
</html>
