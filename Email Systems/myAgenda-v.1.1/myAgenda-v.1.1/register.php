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
<SCRIPT language="JavaScript">
function CheckEmail(email){
	re=new RegExp("(^[a-z][a-z_0-9-\.]+@[a-z_0-9-\.]+\.[a-z]{2,3}$)");
	OK=re.exec(email);
	if(OK)
	return true;
	else
	return false;
}

function CheckUserName(isim){
        
        
        var validChars = "0123456789abcdefghijklmnopqrstuvwxyz-_";

        var dirtyStr = String(isim)
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

		if (the.name.value == "") {
			alert("<?=$GLOBALS['strJSEnterName'];?>")
		    the.name.focus();
			return false
		}
		if (the.surname.value == "") {
			alert("<?=$GLOBALS['strJSEntersurname'];?>")
		    the.surname.focus();
			return false
		}
		if (!CheckEmail(the.email.value) ) {
			alert("<?=$GLOBALS['strJSEnterEmail'];?>");
			the.email.focus();
			return false;
		}		
		if ( (the.username.value == "") || (the.username.value.length < 4) || (!CheckUserName(the.username.value)) ) {
			alert("<?=$GLOBALS['strJSUsername'];?>")
		    the.username.focus();
			return false
		}
		if ( (the.password.value == "") || (the.password.value.length < 4) ) {
			alert("<?=$GLOBALS['strJSPassword'];?>")
		    the.password.focus();
			return false
		}
		if (the.password.value != the.password2.value) {
			alert("<?=$GLOBALS['strJSPasswordsNoMatch'];?>")
		    the.password.focus();
			return false
		}
		return true
	}
</SCRIPT>
</head>
<title><?=$myAgenda_name;?></title>
<body bgcolor="<?=$bg_color;?>">
<br>
<br>
<form action="<?=$PHP_SELF;?><?=get_location($location);?>" method="post" name="myform" onsubmit="return(Check())">
<table border=0 cellpadding=1 cellspacing=0 width="300" align="center">
 <tr>
	<td><font class="text"><?=$GLOBALS['strSignup'];?></font></td>
 </tr>
</table>
<table border=0 cellspacing=0 cellpadding=1 width="300" bgcolor="#333333" align="center">
 <tr>
	<td>
	<table border=0 cellspacing=0 cellpadding=0 width="100%" bgcolor="#FFFFFF">
 	 <tr>
		<td bgcolor="#f3f3f3">

<table width="100%" border="0" cellspacing="2" cellpadding="2">
<?
if($post)
{
	if ( email_check($HTTP_POST_VARS[email]) )
	{
		$existmail = mysql_query("
								Select 
								email 
								From 
								".$myAgenda_tbl_users." 
								Where 
								email = '".$HTTP_POST_VARS[email]."'
								");
		if (mysql_num_rows($existmail) == 0)
		{
			$existuser = mysql_query("
								Select 
								username 
								From 
								".$myAgenda_tbl_users." 
								Where 
								username = '".trim($HTTP_POST_VARS[username])."'
								");
			if (mysql_num_rows($existuser) == 0)
			{
				$s = mysql_query("Insert Into ".$myAgenda_tbl_users." values(
									'',
									'".trim($HTTP_POST_VARS[name])."',
									'".trim($HTTP_POST_VARS[surname])."',
									'".$HTTP_POST_VARS[email]."',
									'".trim($HTTP_POST_VARS[username])."',
									'".trim($HTTP_POST_VARS[password])."',
									'',
									'',
									'".$TimeOffSet."'
									)") or die (mtt_sql_error());
				if ($s)
				{
					echo "<tr><td>".$GLOBALS['strRegisterOk']."</td></tr>";
					if (get_location($location))
					{
						echo "<tr><td>".$GLOBALS['strGoLocation']."</td></tr>";
					}
				}else{
					echo "<tr><td><B>".$GLOBALS['strError']." :</B><br>".$GLOBALS['strErrorSqlInsert']."</td></tr>";
				}
			}else{
				echo "<tr><td><B>".$GLOBALS['strError']." :</B><br>".$GLOBALS['strExistUser']."</td></tr>";
			}
		}else{
			echo "<tr><td><B>".$GLOBALS['strError']."</B><br>".$GLOBALS['strExistMail']."</td></tr>";
		}
	}else{
		echo "<tr><td><B>".$GLOBALS['strError']."</B><br>".$GLOBALS['strWrongMail']."</td></tr>";
	}
}else{
?>
<tr>
	<td width="50%" align="right"><b><?=$GLOBALS['strName'];?></b> </td>
	<td width="50%"><input type="text" name="name" size="25" maxlength="100"></td>
</tr><tr>
	<td align="right"><b><?=$GLOBALS['strSurname'];?></b> </td>
	<td><input type="text" name="surname" size="25" maxlength="10"></td>
</tr><tr>
	<td align="right"><b><?=$GLOBALS['strEmail'];?></b> </td>
	<td><input type="text" name="email" size="25" maxlength="100"></td>
</tr><tr>
	<td align="right"><b><?=$GLOBALS['strUsername'];?></b> </td>
	<td><input type="text" name="username" size="25" maxlength="10"></td>
</tr><tr>
	<td align="right"><b><?=$GLOBALS['strPassword'];?></b> </td>
	<td><input type="password" name="password" size="25" maxlength="10"></td>
</tr><tr>
	<td align="right"><b><?=$GLOBALS['strPassword'];?> (<?=$GLOBALS['strRepeate'];?>)</b> </td>
	<td><input type="password" name="password2" size="25" maxlength="10"></td>
</tr><tr>
	<td align="right">&nbsp;</td>
	<td><input type="Submit" name="post" value="<?=$GLOBALS['strSubmit'];?>"></td>
</tr>
<?}?>
</table>
		
	</td>
 </tr>
</table>
	</td>
 </tr>
</table>
</form>
<?include("files/bottom.php");?>