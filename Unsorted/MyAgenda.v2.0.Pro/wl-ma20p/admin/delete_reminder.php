<?php
#############################################################################
# myAgenda v2.0																#
# =============																#
# Copyright (C) 2003  Mesut Tunga - mesut@tunga.com							#
# http://php.tunga.com														#
#############################################################################
include("check.php");

if(!empty($ID)) {
	mysql_query("DELETE FROM ".$CFG->Tbl_Pfix."_REMINDERS WHERE ID = '".$ID."' ") or die (mysql_error());
	if(mysql_affected_rows() != 0) {
		$noticemsg = $LANGUAGE['strRecordDeleted'];
	}else{
		$noticemsg = $LANGUAGE['strErrorUnknown'];
	}
}else{
		$noticemsg = $LANGUAGE['strErrorUnknown'];
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
<body bgcolor="#BFBFBF">
<table border=0 cellpadding=1 cellspacing=0 width="100%" bgcolor="#BFBFBF" align="center">
 <tr>
	<td>
	
<table width="280" border="0" cellspacing="0" cellpadding="0">
 <tr>
	<td width="36"><img src="../images/alert_icon.gif" width="32" height="32" border="0" alt=""></td>
	<td><?=$noticemsg;?></td>
 </tr><tr>
 	<td align="center" colspan="2"><input type="button" onclick="self.close()" value="<?=$LANGUAGE['str_OK'];?>" style="width:105px"></td>
 </tr>
</table>
	
	</td>
 </tr>
</table>
<script language="javascript">
	window.opener.location.reload();
	setTimeout("self.close()",2500)
</script>
</BODY>
</HTML>