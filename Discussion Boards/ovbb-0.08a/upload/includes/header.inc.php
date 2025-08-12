<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>

<meta http-equiv="content-type" content="text/html; charset=utf-8">
<?php
	if($strRedirect)
	{
		echo("<meta http-equiv=\"Refresh\" content=\"1; URL=$strRedirect\">");
	}
?>
<link rel="SHORTCUT ICON" href="favicon.ico">
<title><?php echo(htmlspecialchars($CFG['general']['name']).$strPageTitle); ?></title>

<style type="text/css">
<!--
	body
	{
		margin: 10px;
		padding: 0px;
		scrollbar-arrow-color: <?php echo($CFG['style']['table']['section']['txtcolor']); ?>;
		scrollbar-base-color: <?php echo($CFG['style']['table']['section']['bgcolor']); ?>;
		font-family: verdana, arial, helvetica, sans-serif;
		color: <?php echo($CFG['style']['forum']['txtcolor']); ?>;
	}
	a:link, a:visited, a:active, a:hover
	{
		color: <?php echo($CFG['style']['l_normal']['l']); ?>;
	}

	a.section:link, a.section:visited, a.section:active
	{
		color: <?php echo($CFG['style']['table']['section']['txtcolor']); ?>;
		text-decoration: none;
	}
	a.section:hover
	{
		color: <?php echo($CFG['style']['table']['section']['txtcolor']); ?>;
		text-decoration: underline;
	}

	a.underline:link, a.underline:visited, a.underline:active
	{
		text-decoration: none;
	}
	a.underline:hover
	{
		text-decoration: underline;
	}

	textarea, select
	{
		font-family: verdana, arial, helvetica, sans-serif;
		font-size: 11px;
		background-color: #CFCFCF;
	}

	.tinput
	{
		font-family: verdana, arial, helvetica, sans-serif;
		font-size: 12px;
		background-color: #CFCFCF;
	}

	.smaller
	{
		font-size: 10px;
	}
	.small
	{
		font-size: 11px;
	}
	.medium
	{
		font-size: 13px;
	}
-->
</style>

</head>

<body bgcolor="<?php echo($CFG['style']['page']['bgcolor']); ?>">

<table width="100%" cellpadding="0" cellspacing="0" border="0" align="center">
<tr>
	<td align="left" valign="bottom" nowrap="nowrap"><a href="index.php"><img src="images/logo.png" border="0" alt="<?php echo(htmlspecialchars($CFG['general']['name'])); ?>"></a></td>
	<td align="right" valign="bottom" width="100%" nowrap="nowrap">
<?php
	if($_SESSION['loggedin'])
	{
?>		<a href="usercp.php"><img src="images/menu_usercp.png" border="0" alt="User Control Panel"></a>
		<a href="logout.php"><img src="images/menu_logout.png" border="0" alt="Logout"></a>
<?php
	}
	else
	{
?>		<a href="register.php"><img src="images/menu_register.png" border="0" alt="Register"></a>
		<a href="login.php"><img src="images/menu_login.png" border="0" alt="Login"></a>
<?php
	}
?>		<a href="calendar.php"><img src="images/menu_calendar.png" border="0" alt="Calendar"></a>
		<a href="memberlist.php"><img src="images/menu_members.png" border="0" alt="Members"></a>
		<a href="#"><img src="images/menu_search.png" border="0" alt="Search"></a>
		<a href="index.php"><img src="images/menu_home.png" border="0" alt="Home"></a>&nbsp;
	</td>
</tr>
</table>


<table bgcolor="<?php echo($CFG['style']['forum']['bgcolor']); ?>" width="100%" cellpadding="10" cellspacing="0" border="0" align="center">
<tr><td width="98%" class="medium">