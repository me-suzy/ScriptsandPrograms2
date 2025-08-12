<?php
#############################################################################
# myAgenda v1.1																#
# =============																#
# Copyright (C) 2002  Mesut Tunga - mesut@tunga.com							#
# http://php.tunga.com														#
#																			#
# This program is free software. You can redistribute it and/or modify		#
# it under the terms of the GNU General Public License as published by 		#
# the Free Software Foundation; either version 2 of the License.       		#
#############################################################################
include("files/functions.php");
?>
<html>
<head>
<META http-equiv="content-type" content="text/html; charset=windows-1254">
<META http-equiv="content-type" content="text/html; charset=<?=$CharSet;?>">
<?include("files/style.php");?>
<script LANGUAGE="javascript">
function Check() {

var the = document.myform

if ( (the.email.value=="") || (the.email.value.length < 7) ) {
	alert("<?=$GLOBALS['strJSEmail'];?>")
	the.email.focus()
	return false
}
	return true
}
</script>
</head>
<title><?=$myAgenda_name;?></title>
<body bgcolor="<?=$bg_color;?>">
<br>
<br>
<table border=0 cellpadding=1 cellspacing=0 width="320" align="center">
 <tr>
	<td><font class="text"><?=$GLOBALS['strSendMyPassword'];?></font></td>
 </tr>
</table>
<?
if($post)
{
	$s = mysql_query("Select name, surname, email, password
					From 
					".$myAgenda_tbl_users."
					Where
					email = '".trim($HTTP_POST_VARS[email])."'
					") or die(mysql_error());
?>
<img src="images/bos" width="1" height="2" border="0" alt=""><br>
<table border=0 cellspacing=0 cellpadding=1 width="320" bgcolor="#333333" align="center">
 <tr>
	<td>
	<table border=0 cellspacing=0 cellpadding=0 width="100%" bgcolor="#FFFFFF">
 	 <tr>
		<td>
		 <table border=0 cellpadding=2 cellspacing=2 width="100%">
		 <tr>
			<td bgcolor="#f3f3f3">
<?
	if(mysql_num_rows($s) != 0)
	{
		$r = mysql_fetch_array($s);
		$msg = str_replace("{name}", $r[name]." ".$r[surname], $GLOBALS['strForgotPassEmailBody'])  . $r[password];
		$msg .= "\n\n" . $MyAgenda_name ."\n" . $myAgenda_url;

		mail($r[email], $GLOBALS['strForgotPassEmailSubj'], $msg, "From: $MyAgenda_name <$myAgenda_admin_email>");
		echo $GLOBALS['strForgotPassEmailOk'];

	}else{
		echo $GLOBALS['strForgotPassEmailError'];
	}
?></td>
	 </tr>
	</table>

	</td>
 </tr>
</table>
<?
}else{
?>

<table border=0 cellspacing=0 cellpadding=1 width="320" bgcolor="#333333" align="center">
 <tr><form action="<?=$PHP_SELF;?>" method="post" name="myform" onsubmit="return(Check())">
	<td>
	<table border=0 cellspacing=0 cellpadding=0 width="100%" bgcolor="#FFFFFF">
 	 <tr>
		<td bgcolor="#f3f3f3">

<table width="100%" border="0" cellspacing="2" cellpadding="2">
 <tr>
	<td width="50%" align="right"><b><?=$GLOBALS['strEmail'];?></b> </td>
	<td width="50%"><input type="text" name="email" size="25" maxlength="100"></td>
</tr><tr>
	<td align="right">&nbsp;</td>
	<td><input type="Submit" name="post" value="<?=$GLOBALS['strGo'];?>">
	</td>
</tr></form>
</table>

		
	</td>
 </tr>
</table>
<?}?>
	</td>
 </tr>
</table>

<?include("files/bottom.php");?>