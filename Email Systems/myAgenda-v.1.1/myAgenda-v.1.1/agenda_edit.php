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
include("check.php");
?>
<html>
<head>
<META http-equiv="content-type" content="text/html; charset=windows-1254">
<META http-equiv="content-type" content="text/html; charset=<?=$CharSet;?>">
<?include("files/style.php");?>
<script LANGUAGE="javascript">
function Check()
{
	var the = document.myform

	if (the.RemindNote.value == ""){
	   	alert("<?=$GLOBALS['strJSNoNote'];?>")
		the.RemindNote.focus()
   		return false
   	}
	if (the.RemindNote.value.length > "125"){
	   	alert("<?=$GLOBALS['strJSToomuchChars'];?>")
		the.RemindNote.focus()
   		return false
   	}
	return true
}

var supportsKeys = false
var maxLength

function textKey(f) 
{
		supportsKeys = true
		calcCharLeft(f)
}

function calcCharLeft(f) 
{
		maxLength = 125;		
        if (f.RemindNote.value.length > maxLength){
	        f.RemindNote.value = f.RemindNote.value.substring(0,maxLength)
		    charleft = 0
        } else {
			charleft = maxLength - f.RemindNote.value.length
		}
        f.chars.value = charleft
}
</script>
</head>
<title><?=$myAgenda_name;?></title>
<body bgcolor="<?=$bg_color;?>">
<table border=0 cellpadding=1 cellspacing=0 width="320" align="center">
 <tr>
	<td><font class="text"><?=$GLOBALS['strEditReminder'];?></font></td>
 </tr>
</table>
<table border=0 cellspacing=0 cellpadding=1 width="300" bgcolor="#333333" align="center">
 <tr>
	<td>
	<table border=0 cellspacing=0 cellpadding=0 width="100%" bgcolor="#FFFFFF">
 	 <tr>
		<td bgcolor="#f3f3f3">
<table width="100%" border="0" cellspacing="2" cellpadding="5">
<?
	if($update)
	{
				$s = mysql_query("Update ".$myAgenda_tbl_reminders." Set
								remindtype = '".$HTTP_POST_VARS[RemindType]."',
								remindday = '".$HTTP_POST_VARS[RemindDay]."',
								remindrepeat = '".$HTTP_POST_VARS[RemindRepeat]."',
								remindnote = '".AddSlashes(HtmlSpecialChars($HTTP_POST_VARS[RemindNote]))."'
								Where
								uid = '".$HTTP_COOKIE_VARS[auID]."'
								And id = '".$HTTP_POST_VARS[id]."'
								") or die (mysql_error());
				if(mysql_affected_rows())
				{
?>
  <tr>
	<td align="center"><b><?=$GLOBALS['strReminderUpdated'];?><p><a href="index.php"><?=$GLOBALS['strHome'];?></a><p><a href="javascript:history.go(-1);"><?=$GLOBALS['strBack'];?></a></b></td>
  </tr>
<?
				}else{
					echo "<tr><td><b>".$GLOBALS['strError']."</b><p>".$GLOBALS['strErrorSqlInsert']."</td></tr>";
				}

	}elseif($del)
	{
				mysql_query("Delete From ".$myAgenda_tbl_reminders."
							Where
							uid = '".$HTTP_COOKIE_VARS[auID]."'
							And id = '".$HTTP_POST_VARS[id]."'
							") or die (mysql_error());
				if(mysql_affected_rows())
				{
?>
  <tr>
	<td align="center"><b><?=$GLOBALS['strReminderDeleted'];?><p><a href="index.php"><?=$GLOBALS['strHome'];?></a><p><a href="javascript:history.go(-1);"><?=$GLOBALS['strBack'];?></a></b></td>
  </tr>
<?
				}else{
					echo "<tr><td><b>".$GLOBALS['strError']."</b><p>".$GLOBALS['strErrorSqlInsert']."</td></tr>";
				}


	}else{

			$s = mysql_query("Select
								* 
								From 
								".$myAgenda_tbl_reminders." 
								Where 
								uid = '".$HTTP_COOKIE_VARS[auID]."' 
								And id = '".$id."'
								") or die(mysql_error());
			if(mysql_num_rows($s) != 0)
			{
			$r = mysql_fetch_array($s);
?>
 <form action="<?=$PHP_SELF;?>" method="post" name="myform" OnSubmit="return(Check())">
 <input type="hidden" name="id" value="<?=$id;?>">
<tr>
	<td><?=get_remindtype($r[remindtype],0);?></td>
 </tr><tr>
	<td><font class="small"><?=$GLOBALS['strMyThisReminder'];?></font><br><?=get_remindrepeat($r[remindrepeat],0);?></td>
 </tr><tr>
	<td><font class="small"><?=$GLOBALS['strFromMyDate'];?><br><?=get_remindday($r[remindday],0);?> <?=$GLOBALS['strRemindBefore'];?></font></td>
 </tr><tr>
	<td><p><font class="small"><?=$GLOBALS['strWriteNote'];?><br>
	<textarea name="RemindNote" cols="35" rows="5" onKeyUp="textKey(this.form)"><?=StripSlashes($r[remindnote]);?></textarea>
	<br><input value="125" size="3" name="chars" disabled>		
	<br><?=$GLOBALS['strMaxNoteChars'];?></font></td>
 </tr><tr>
 	<td>&nbsp;</td>
 </tr><tr>
	<td><input type="submit" value="<?=$GLOBALS['strUpdate'];?>" name="update">&nbsp;<input type="submit" value="<?=$GLOBALS['strDelete'];?>" name="del" onClick="return confirm('<?=$GLOBALS['strConfirm'];?>')"></td>
 </tr>
<?
		}else{
			echo "<tr><td><b>".$GLOBALS['strError']."</b></td></tr>";
		}
	}
?>

</table>

</td>
 </tr>
</table>
</td>
 </tr>
</table>
</form>
<?include("files/bottom.php");?>