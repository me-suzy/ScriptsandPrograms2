<?php
#############################################################################
# myAgenda v2.0																#
# =============																#
# Copyright (C) 2003  Mesut Tunga - mesut@tunga.com							#
# http://php.tunga.com														#
#############################################################################
include("check.php");
?>
<html>
<head>
<META http-equiv="content-type" content="text/html; charset=windows-1254">
<META http-equiv="content-type" content="text/html; charset=">
	<STYLE>
	A				{color: #000099; font-weight : bold;}
	A:visited   	{color: #000099; font-weight : bold;}
	td				{font-family: Georgia, Arial, Verdana, sans-serif; font-size: 10pt;}
	.text			{font-size: 13pt;font-family: Georgia, Arial, Verdana, sans-serif; font-weight : bold;}
	.small			{font-family: verdana, arial, sans-serif;font-size: 8pt;}
	.small:hover	{text-decoration: underline}
	unknown			{font-size: 10pt; font-family: verdana, arial, sans-serif;}
	input			{font-size: 8pt; font-family: verdana, arial, sans-serif;}
	select			{font-size: 8pt; font-family: verdana, arial, sans-serif;}
	</STYLE>
<title>myAgenda Administrative Area</title>
<script LANGUAGE="javascript" src="../js/jslib.js"></script>
</head>
<body bgcolor="#FFFFFF">
<table border=0 cellspacing=0 cellpadding=1 width="100%" bgcolor="#333333" align="center">
 <tr>
	<td>

	<table border=0 cellspacing=0 cellpadding=0 width="100%" bgcolor="#FFFFFF">
 	 <tr>
		<td>
		  <table border=0 cellpadding=1 cellspacing=1 width="100%">
			  <tr>
				 <td bgcolor="#f3f3f3" align="right"><b>
				 <a href="users.php"><?=$LANGUAGE['str_ListUsers'];?></a>&nbsp;
				 <a href="reminders.php"><?=$LANGUAGE['str_ListReminders'];?></a>&nbsp;
				 <a href="config.php"><?=$LANGUAGE['str_Config'];?></a>&nbsp;
				 <a href="logout.php"><?=$LANGUAGE['strLogout'];?></a>
				 </b>&nbsp;&nbsp;</td>
			  </tr>
			 </table>
		</td>
	 </tr>
	</table>

	</td>
 </tr>
</table>
<img src="../images/spacer.gif" width="1" height="2" border="0" alt=""><br>