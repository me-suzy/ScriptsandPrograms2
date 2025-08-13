<?php
#############################################################################
# myAgenda v2.0																#
# =============																#
# Copyright (C) 2003  Mesut Tunga - mesut@tunga.com							#
# http://php.tunga.com														#
#############################################################################
include("header.php");
?>

<table border=0 cellspacing=0 cellpadding=1 width="100%" bgcolor="#333333" align="center">
 <tr>
	<td>

	<table border=0 cellspacing=0 cellpadding=0 width="100%" bgcolor="#FFFFFF">
 	 <tr>
		<td>
		  <table border=0 cellpadding=1 cellspacing=1 width="100%">
			  <tr>
				 <td bgcolor="#f3f3f3" align="center">
<?php
if($HTTP_POST_VARS['act'] == "edit") {
	$sQL = mysql_query("SELECT * FROM ".$CFG->Tbl_Pfix."_REMINDERS WHERE ID = '".$HTTP_POST_VARS['ID']."'") or die(mysql_error());
	if(mysql_num_rows($sQL) !=0) {
		$row = mysql_fetch_array($sQL);
?>
<form action="<?=$ME;?>" name="myform" method="post">
<input type="hidden" name="ID" value="<?=$HTTP_POST_VARS['ID'];?>">
<input type="hidden" name="page" value="<?=$HTTP_POST_VARS['page'];?>">
<input type="hidden" name="act" value="update">
<table width="320" border="0" cellspacing="0" cellpadding="2">
 <tr>
 	<td><b><?=$LANGUAGE['strDate'];?></b></td>
 	<td><select name="day">
<?php
	for ($idx = "01"; $idx <= 31; $idx++)  {
		if (strlen($idx) == 1){$idx = "0".$idx;}
		echo "<option value=\"".$idx."\" ".( $idx == date("d", $row['DATE']) ? "Selected" : "" ) . ">" .  $idx . "\n";
	}
?></select> / 
<select name="month">
<?php
	for ($idx = "01"; $idx <= 12; $idx++) {
		if (strlen($idx) == 1){$idx = "0".$idx;}
		echo "<option value=\"".$idx."\" ".( $idx == date("m", $row['DATE']) ? "Selected" : "" ) . ">" .  $idx . "\n";
	}
?>
</select> / <select name="year">
<?php
	for ($idx = 2000; $idx <= 2005; $idx++ ) {
		echo "<option value=\"".$idx."\" ".( $idx == date("Y", $row['DATE']) ? "Selected" : "" ) . ">" .  $idx . "\n";
	}
?>
</select></td>
 </tr><tr>
	<td><b><?=$LANGUAGE['strType'];?></b></td>
	<td><?=get_remindtype($row['TYPE'],0);?></td>
 </tr><tr>
	<td><b><?=$LANGUAGE['strDuration'];?></b></td>
	<td><?=get_remindrepeat($row['REPEAT'],0);?></td>
 </tr><tr>
	<td><b><?=$LANGUAGE['strAdvance'];?></b></td>
	<td><?=get_remindday($row['ADVANCE'],0);?></td>
 </tr><tr>
	<td><b><?=$LANGUAGE['str_At'];?></b></td>
	<td><?=hour_form(date("G",$row['DATE']));?> <?=$LANGUAGE['str_Oclock'];?></td>
 </tr><tr>
	<td>&nbsp;</td>
	<td><b><?=$LANGUAGE['str_ReminderNote'];?></b></td>
 </tr><tr>
	<td>&nbsp;</td>
 	<td>
	<textarea name="REMINDER" cols="35" rows="5"><?=StripSlashes($row['REMINDER']);?></textarea>
	<br><?=$LANGUAGE['strMaxNoteChars'];?></font></td>
 </tr><tr>
 	<td>&nbsp;</td>
 </tr><tr>
	<td valign="top">&nbsp;</td>
	<td><input type="submit" value="<?=$LANGUAGE['strUpdate'];?>"></td>
</tr></form>
</table>
<?PHP
	}

}elseif($HTTP_POST_VARS['act'] == "update") {
	$remindtime = mktime($HTTP_POST_VARS['HOUR'],0,0,$HTTP_POST_VARS['month'],$HTTP_POST_VARS['day'],$HTTP_POST_VARS['year']);
	mysql_query("UPDATE ".$CFG->Tbl_Pfix."_REMINDERS Set
				TYPE = '".$HTTP_POST_VARS['TYPE']."',
				ADVANCE = '".$HTTP_POST_VARS['ADVANCE']."',
				DATE = '".$remindtime."',
				REPEAT = '".$HTTP_POST_VARS['REPEAT']."',
				REMINDER = '".trim(AddSlashes($HTTP_POST_VARS['REMINDER']))."'
				WHERE
				ID = '".$HTTP_POST_VARS['ID']."'
				") or die(mysql_error());
		if(mysql_affected_rows() != 0) {
?>
<table width="400" border="0" cellspacing="0" cellpadding="2">
<tr>
	<td><b><?=$LANGUAGE['strRecordUpdated'];?><BR><a href="reminders.php?page=<?=$HTTP_POST_VARS['page'];?>"><?=$LANGUAGE['strBack'];?></a></b></td>
</tr>
</table>
<?php
		}else{
?>
<table width="400" border="0" cellspacing="0" cellpadding="2">
<tr>
	<td align="center"><b><?=$LANGUAGE['strNothingUpdated'];?><BR><a href="reminders.php?page=<?=$HTTP_POST_VARS['page'];?>"><?=$LANGUAGE['strBack'];?></a></b></td>
</tr>
</table>
<?php
		}
}elseif($HTTP_POST_VARS['act'] == "deleteall") {
	if(sizeof($HTTP_POST_VARS['IDS'])) {
		foreach($HTTP_POST_VARS['IDS'] as $ID) {
			mysql_query("DELETE FROM ".$CFG->Tbl_Pfix."_REMINDERS WHERE ID = '".$ID."'") or die(mysql_error());
			if(mysql_affected_rows() != 0) {
				$i++;
			}
		}
	}
		if($i != 0) {
?>
<table width="400" border="0" cellspacing="0" cellpadding="2">
<tr>
	<td><b><?=str_replace("{TOTAL}", $i, $LANGUAGE['strItemsDeleted']);?></b></td>
</tr>
</table>
<?php
		}else{
?>
<table width="400" border="0" cellspacing="0" cellpadding="2">
<tr>
	<td align="center"><b><?=$LANGUAGE['strSelectOne'];?></b></td>
</tr>
</table>
<?php
		}
}else{

	switch ($order) {
		case "TYPE" : $order = "TYPE"; break;
		case "ADVANCE" : $order = "ADVANCE"; break;
		case "DATE" : $order = "DATE"; break;
		case "REPEAT" : $order = "REPEAT"; break;
		default : $order = "UID"; break;
	}

	switch ($sort) {
		case "Asc" : $n_sort = "Desc"; break;
		case "Desc" : $n_sort = "Asc"; break;
		default : $n_sort = "Asc"; break;
	}

$sQL = "SELECT A.*, B.NAME, B.SURNAME FROM ".$CFG->Tbl_Pfix."_REMINDERS A, ".$CFG->Tbl_Pfix."_USERS B WHERE A.UID = B.UID  ORDER BY $order $sort ";

if (!$page) { $page = 1; }
$prev_page = $page - 1;
$next_page = $page + 1;
$sqlStr = mysql_query($sQL)  or die (mysql_error());
$page_starts = (50 * $page) - 50;
$row_numbers = mysql_num_rows($sqlStr);
	if ($row_numbers <= 50) {
   		$page_numbers = 1;
		} else if (($row_numbers % 50) == 0) {
				   $page_numbers = ($row_numbers / 50);
				   } else { 
						   $page_numbers = ($row_numbers / 50) + 1; }
						   $page_numbers = (int) $page_numbers;
			if (($page > $page_numbers) || ($page < 0)) { echo ("Wrong Page Number"); }

	$sQL = $sQL . " Limit $page_starts, 50";
	$sQL = mysql_query($sQL) or die (mysql_error());

	if(mysql_num_rows($sqlStr) != 0) {
?>
<form name="myform" action="<?=$ME;?>" method="post">
					<table width="100%" border="0" cellspacing="0" cellpadding="2">
					 <tr>
						<td colspan="9"><font color="#FF0000" class="text"><b><?=$LANGUAGE['str_ListReminders'];?></b></font></td>
					</tr><tr>
						<td colspan="9"><?=str_replace("{TOTAL}", $row_numbers, $LANGUAGE['str_RegReminders']);?></td>
					 </tr><tr>
					 	<td colspan="9"><hr width="100%" size="1" color="#000000" noshade></td>
					 </tr><tr>
						<td align="center"><input type="checkbox" name="checkall" value="checkbox" onClick="checkAll(this.form);"></td>
						<td><b><?=$LANGUAGE['strName'];?> <?=$LANGUAGE['strSurname'];?></b></td>
						<td><b><a href="?order=TYPE&page=<?=$page;?>&sort=<?=$n_sort;?>"><?=$LANGUAGE['strType'];?></a></b></td>
						<td align="center"><b><a href="?order=ADVANCE&page=<?=$page;?>&sort=<?=$n_sort;?>"><?=$LANGUAGE['strAdvance'];?></a></b></td>
						<td align="center"><b><a href="?order=DATE&page=<?=$page;?>&sort=<?=$n_sort;?>"><?=$LANGUAGE['strDate'];?></a></b></td>
						<td><b><?=$LANGUAGE['str_ReminderNote'];?></b></td>
						<td><b><a href="?order=REPEAT&page=<?=$page;?>&sort=<?=$n_sort;?>"><?=$LANGUAGE['strRepeat'];?></a></b></td>
						<td align="center"><b><?=$LANGUAGE['strAction'];?></b></td>
					 </tr><tr>
					 	<td colspan="8"><hr width="100%" size="1" color="#000000" noshade></td>
					 </tr>
<?php
	while ($row = mysql_fetch_array($sQL)) { 
		if ($bgcolor=="#FFFFFF") {$bgcolor="#EFEFEF";} else {$bgcolor="#FFFFFF";} 
		$REMINDER = (strlen($row['REMINDER']) > 25) ? StripSlashes(substr($row['REMINDER'],0,25)) . " ..." : StripSlashes($row['REMINDER']);

?>
 <TR bgcolor="<?=$bgcolor;?>">
	<td valign="top" align="center"><input type="checkbox" name="IDS[]" value="<?=$row['ID'];?>" onClick="javascript:checkCtrl(this.form)"></td>
	<TD valign="top"><font class="small"><b><?=$row['NAME'];?> <?=$row['SURNAME'];?></b></font></TD>
	<TD valign="top"><font class="small"><?=get_remindtype($row['TYPE'], 1, "");?></font></TD>
	<TD valign="top" align="center"><font class="small"><?=get_remindday($row['ADVANCE'], 1, "");?></font></TD>
	<TD valign="top" align="center"><font class="small"><?=date($LANGUAGE['date_format'] ." G.00", $row['DATE']);?></font></TD>
	<TD valign="top"><font class="small"><b><?=$REMINDER;?></b></font></TD>
	<TD valign="top"><font class="small"><?=get_remindrepeat($row['REPEAT'], 1, "");?></font></TD>
	<TD align="center"><font class="small"><a href="#" onClick="edit('<?=$row['ID'];?>','edit')"><img src="../images/edit_pencil.gif" width="16" height="16" border="0" alt="<?=$LANGUAGE['strEdit'];?>"></a>&nbsp;<a href="JavaScript:void(0)" onClick="edit('<?=$row['ID'];?>','delete')"><img src="../images/delete_can.gif" width="16" height="16" border="0" alt="<?=$LANGUAGE['strDelete'];?>"></a></font></TD>
 </TR>
<? 	}	?>
 <TR>
	<TD colspan="8"><hr width="100%" size="1" color="#000000" noshade></TD>
  </TR><TR>
	<TD colspan="8"><a href="#" onClick="edit(null,'deleteall')"><b><?=$LANGUAGE['strDelSelected'];?></a></b></td></TD>
  </TR><TR>
	<TD colspan="8"><hr width="100%" size="1" color="#000000" noshade></TD>
 </TR>
<?php
		if($row_numbers > 50) {
?>
 <TR>
 	<TD colspan="8" align="center"><b><?=$LANGUAGE['strOtherPages'];?> :</b> 
<?
if ($prev_page){	echo "<a href=\"$PHP_SELF?page=$prev_page&order=$order&sort=$n_sort\">&laquo;".$LANGUAGE['strPrevPage']."</a>";}

	for ($idx = 1; $idx <= $page_numbers; $idx++) {
    	if ($idx != $page) {echo " <a href=\"$PHP_SELF?page=$idx&order=$order&sort=$n_sort\">$idx</a> ";
	   	} else {echo " <b>$idx</b> ";}
	}

	if ($page != $page_numbers) {echo "<a href=\"$PHP_SELF?page=$next_page&order=$order&sort=$n_sort\">".$LANGUAGE['strNextPage']."&raquo;</a>";}
?>	
	</TD>
 </TR>
<?php
 		}
?>
 					</table>
<input type="hidden" name="page" value="<?=$page;?>">
<input type="hidden" name="act">
<input type="hidden" name="ID">
</form>					
<Script language="JavaScript">
function edit(id,what) {
	var f = document.myform;
	if(what=="deleteall") {
	var j = 0;
		for(var i=0 ; i<f.elements.length; i++) {
			var e = f.elements[i];
			if((e.type == 'checkbox') && (e.name != 'checkall') && (e.checked==true) )
			j++
		}
		if(j != 0) {
			if(confirm('<?=$LANGUAGE['strJSConfirm'];?>')) {
				f.act.value = "deleteall";
				f.submit();
			}
		}else{
			alert('<?=$LANGUAGE['strSelectOne'];?>')
		}
	}
	if( (what=="edit") && (id != null || id != "") ) {
		f.act.value = "edit"
		f.ID.value = id
		f.submit();
	}
	if(what=='delete'){
		if(confirm('<?=$LANGUAGE['strJSConfirm'];?>')) {
			popUP("delete_reminder.php?ID="+id, 300, 100, "");
		}
	}

}
</SCRIPT>
<?php
		}else{
?>
<table width="100%" border="0" cellspacing="0" cellpadding="2">
 <tr>
	<td><font color="#FF0000" class="text"><b><?=$LANGUAGE['str_ListReminders'];?></b></font></td>
 </tr><tr>
	<td align="center"><b><?=$LANGUAGE['str_NoReminders'];?></b></td>
 </tr>
</table>
<?php
		}
		}
?>
				 </td>
			  </tr>
			 </table>
		</td>
	 </tr>
	</table>

	</td>
 </tr>
</table>
<img src="../images/spacer.gif" width="1" height="2" border="0" alt=""><br>
<?php
	include("footer.php");
?>