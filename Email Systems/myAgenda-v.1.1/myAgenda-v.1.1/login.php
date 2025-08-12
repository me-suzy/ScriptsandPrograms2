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
function CheckUserName(variable){
        
        
        var validChars = "0123456789abcdefghijklmnopqrstuvwxyz-_";

        var dirtyStr = String(variable)
        var len  = dirtyStr.length;
        for (var i = 0; i < len; i++){  
            var chr = dirtyStr.charAt(i);
            if (validChars.indexOf(chr) == -1){
                return false;
            }
        }
        return true;
}   

function Check() {

	var the = document.myform

	if ((the.username.value=="") || (the.username.value.length < 4) || (!CheckUserName(the.username.value)) ) {
		alert("<?=$GLOBALS['strJSUsername'];?>")
		the.username.focus()
		return false
	}
	if ((the.password.value=="") || (the.password.value.length < 4)) {
		alert("<?=$GLOBALS['strJSPassword'];?>")
		the.password.focus();    			
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
<form action="check.php<?=get_location($location);?>" method="post" name="myform" onsubmit="return(Check())">
<table border=0 cellpadding=1 cellspacing=0 width="320" align="center">
 <tr>
	<td><font class="text"><?=$GLOBALS['strLogin'];?></font></td>
 </tr>
</table>
<table border=0 cellspacing=0 cellpadding=1 width="320" bgcolor="#333333" align="center">
 <tr>
	<td>
	<table border=0 cellspacing=0 cellpadding=0 width="100%" bgcolor="#FFFFFF">
 	 <tr>
		<td bgcolor="#f3f3f3">

<table width="100%" border="0" cellspacing="2" cellpadding="2">
<?
if($EI)
{
echo "<tr><td colspan=\"2\" align=\"center\"><font color=\"#FF0000\"><b>";
	switch($EI)
	{
		case 1:		echo $GLOBALS['strErrorWronguser'];	break;
		case 2:		echo $GLOBALS['strErrorTimeout'];	break;
		default:	echo $GLOBALS['strErrorUnknown'];	break;
	}
echo "</b></font></td></tr>";
}
?>
<tr>
	<td width="50%" align="right"><b><?=$GLOBALS['strUsername'];?></b> </td>
	<td width="50%"><input type="text" name="username" size="25" maxlength="100"></td>
</tr><tr>
	<td align="right"><b><?=$GLOBALS['strPassword'];?></b> </td>
	<td><input type="password" name="password" size="25" maxlength="10"></td>
</tr><tr>
	<td align="right">&nbsp;</td>
	<td><input type="Submit" name="post" value="<?=$GLOBALS['strLogin'];?>"></td>
</tr></form>
</table>

		
	</td>
 </tr>
</table>
	</td>
 </tr>
</table>
<img src="images/bos" width="1" height="2" border="0" alt=""><br>
<table border=0 cellspacing=0 cellpadding=1 width="320" bgcolor="#333333" align="center">
 <tr>
	<td>
	<table border=0 cellspacing=0 cellpadding=0 width="100%" bgcolor="#FFFFFF">
 	 <tr>
		<td>
		 <table border=0 cellpadding=2 cellspacing=2 width="100%">
		 <tr>
			<td bgcolor="#f3f3f3"><?=$GLOBALS['strRegFree'];?></td>
	 </tr>
	</table>

	</td>
 </tr>
</table>

	</td>
 </tr>
</table>
<?include("files/bottom.php");?>