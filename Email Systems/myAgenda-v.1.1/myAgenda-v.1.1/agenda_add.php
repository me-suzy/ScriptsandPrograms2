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
	<td><font class="text"><?=$GLOBALS['strAddReminder'];?> - <?=$day." ".$GLOBALS['strMonthnames'][$month-1]." ".$year;?></font></td>
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
if( !empty($day) &&  !empty($month) &&  !empty($year) )
{
	$today = mktime("","","", date("m"), date("d"), date("Y"));
	$remind = mktime("","","", $month, $day, $year);

	if ($today <= $remind)
	{
		if( checkdate($month, $day, $year) )
		{
			if($yolla)
			{
				$date = mktime("","","",$month,$day,$year);
				$remindtime = mktime("","","",$month,$day-$HTTP_POST_VARS[RemindDay],$year);

				$s = mysql_query("Insert into ".$myAgenda_tbl_reminders." values(
								'', 
								'".$HTTP_COOKIE_VARS[auID]."',
								'".$HTTP_POST_VARS[RemindType]."',
								'".$HTTP_POST_VARS[RemindDay]."',
								'".$remindtime."',
								'".$HTTP_POST_VARS[RemindRepeat]."',
								'".AddSlashes(HtmlSpecialChars($HTTP_POST_VARS[RemindNote]))."',
								'".$date."'
								 )") or die (mysql_error());
				if($s)
				{
?>
  <tr>
	<td align="center"><b><?=$GLOBALS['strSaveRemindOk'];?><p><a href="index.php"><?=$GLOBALS['strHome'];?></a><p><a href="javascript:history.go(-1);"><?=$GLOBALS['strBack'];?></a></b></td>
  </tr>
<?
				}else{
					echo "<tr><td><b>".$GLOBALS['strError']."</b><p>".$GLOBALS['strErrorSqlInsert']."</td></tr>";
				}
			}else{
?>
 <tr>
	<td><?=get_notes($HTTP_COOKIE_VARS[auID], $month, $day, $year, 1);?></td>
 </tr>
 <form action="<?=$PHP_SELF;?>?day=<?=$day;?>&month=<?=$month;?>&year=<?=$year;?>" method="post" name="myform" OnSubmit="return(Check())">
<tr>
	<td><?=get_remindtype();?></td>
 </tr><tr>
	<td><font class="small"><?=$GLOBALS['strMyThisReminder'];?></font><br><?=get_remindrepeat();?></td>
 </tr><tr>
	<td><font class="small"><?=$GLOBALS['strFromMyDate'];?><br><?=get_remindday();?> <?=$GLOBALS['strRemindBefore'];?></font></td>
 </tr><tr>
	<td><p><font class="small"><?=$GLOBALS['strWriteNote'];?><br>
	<textarea name="RemindNote" cols="35" rows="5" onKeyUp="textKey(this.form)"></textarea>
	<br><input value="125" size="3" name="chars" disabled>	
	<br><?=$GLOBALS['strMaxNoteChars'];?></font></td>
 </tr><tr>
 	<td>&nbsp;</td>
 </tr><tr>
	<td><input type="submit" value="<?=$GLOBALS['strSave'];?>" name="yolla"></td>
 </tr>
<?
	 		}
		}else{
			echo "<tr><td><b>".$GLOBALS['strError']."!</b><p>".$GLOBALS['strErrorWrongDate']."</td></tr>";
		}
	}else{
		echo "<tr><td><b>".$GLOBALS['strError']."!</b><p>".$GLOBALS['strErrorOldgDate']."</td></tr>";
	}
}else{
	echo "<tr><td><b>".$GLOBALS['strError']."!</b><p>".$GLOBALS['strErrorLackDate']."</td></tr>";
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