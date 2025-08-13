<?php
#############################################################################
# myAgenda v2.0																#
# =============																#
# Copyright (C) 2003  Mesut Tunga - mesut@tunga.com							#
# http://php.tunga.com														#
#############################################################################
include("../includes/config.php");
include("../includes/functions.php");
if (match_referer() && IsSet($HTTP_POST_VARS)) {
	$frm = $HTTP_POST_VARS;
	$errormsg = validate_form($frm);
	if(empty($errormsg)) {
		$sQL = mysql_query("SELECT ADMIN_USERNAME FROM ".$CFG->Tbl_Pfix."_CONFIGS WHERE ADMIN_USERNAME = '".$frm[USERNAME]."' AND ADMIN_PASSWORD = '".$frm[PASSWORD]."'") or die (mysql_error());
		if( mysql_num_rows($sQL) != 0 ){
			setcookie("adID",1,0,"/");
			mysql_close();
			header("Location: ./");
			die;
		}else{
			$errormsg = $LANGUAGE['strErrorWronguser'];
		}
	}
}
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
</head>
<body bgcolor="#FFFFFF">
<img src="../images/spacer.gif" width="1" height="20" border="0" alt=""><br>
<table border=0 cellspacing=0 cellpadding=1 width="400" bgcolor="#333333" align="center">
 <tr>
	<td>
	<table border=0 cellspacing=0 cellpadding=0 width="100%" bgcolor="#FFFFFF">
 	 <tr>
		<td bgcolor="#f3f3f3">

<form action="<?=$ME;?>" method="post">
<table width="100%" border="0" cellspacing="2" cellpadding="2">
<tr>
	<td colspan="2"><font color="#FF0000"><b><?=$errormsg;?></b></font></td>
</tr>
<tr>
	<td width="50%" align="right"><b><?=$LANGUAGE['strUsername'];?></b> </td>
	<td width="50%"><input type="text" name="USERNAME" value="<?=$frm["USERNAME"];?>" size="25" maxlength="100"></td>
</tr><tr>
	<td align="right"><b><?=$LANGUAGE['strPassword'];?></b> </td>
	<td><input type="password" name="PASSWORD" size="25" maxlength="10"></td>
</tr><tr>
	<td align="right">&nbsp;</td>
	<td><input type="Submit" name="post" value="<?=$LANGUAGE['strLogin'];?>"></td>
</tr></form>
</table>

		
	</td>
 </tr>
</table>
	</td>
 </tr>
</table>

	</td>
 </tr>
</table>
<?php
function validate_form(&$frm) {
	global $LANGUAGE;
	$msg = "";
	if(	(strlen($frm[USERNAME]) < 4) || (strrpos($frm[USERNAME],' ') > 0) ) {
		$msg .= "<li>" . str_replace("\\n","<br>",$LANGUAGE["strJSUsername"]). "</li>";
	}
	if(	(strlen($frm[PASSWORD]) < 4) || (strrpos($frm[PASSWORD],' ') > 0) ) {
		$msg .= "<li>" . str_replace("\\n","<br>",$LANGUAGE["strJSPassword"]). "</li>";
	}
	return $msg;
}
?>
</BODY>
</HTML>