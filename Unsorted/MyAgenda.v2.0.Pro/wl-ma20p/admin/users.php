<?php
#############################################################################
# myAgenda v2.0																#
# =============																#
# Copyright (C) 2003  Mesut Tunga - mesut@tunga.com							#
# http://php.tunga.com														#
#############################################################################
include("header.php");
?>
<img src="../images/spacer.gif" width="1" height="2" border="0" alt=""><br>

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
	$sQL = mysql_query("SELECT NAME, SURNAME, EMAIL, USERNAME, PASSWORD FROM ".$CFG->Tbl_Pfix."_USERS WHERE UID = '".$HTTP_POST_VARS['UID']."'") or die(mysql_error());
	if(mysql_num_rows($sQL) !=0) {
		$row = mysql_fetch_array($sQL);
?>
<SCRIPT language="JavaScript">
function validate() {

	var f = document.myform

		if ( (f.NAME.value=="") || (f.NAME.value.length < 2) ) {
			alert('<?=$LANGUAGE['strJSEnterName'];?>');
		    f.NAME.focus();
			return false
		}

		if ( (f.SURNAME.value=="") || (f.SURNAME.value.length < 2) ) {
			alert('<?=$LANGUAGE['strJSEnterSurname'];?>');
		    f.SURNAME.focus();
			return false
		}
		if (!validate_email(f.EMAIL) ) {
			alert('<?=$LANGUAGE['strJSEnterEmail'];?>');
			f.EMAIL.focus();
			return false;
		}		
		if ( (f.USERNAME.value=="") || (f.USERNAME.value.length < 4) ) {
			alert('<?=$LANGUAGE['strJSUsername'];?>');
		    f.USERNAME.focus();
			return false
		}
		if ( (f.PASSWORD.value == "") || (f.PASSWORD.value.length < 4) ) {
			alert('<?=$LANGUAGE['strJSPassword'];?>')
		    f.PASSWORD.focus();
			return false
		}
		return true
	}
</SCRIPT>
<form action="<?=$ME;?>" name="myform" method="post" onsubmit="return(validate())">
<input type="hidden" name="UID" value="<?=$HTTP_POST_VARS['UID'];?>">
<input type="hidden" name="page" value="<?=$HTTP_POST_VARS['page'];?>">
<input type="hidden" name="act" value="update">
<table width="350" border="0" cellspacing="0" cellpadding="2">
<tr>
	<td valign="top"><b><?=$LANGUAGE['strName'];?></b></td>
	<td><input type="text" name="NAME" value="<?=$row[NAME];?>" size="20"></td>
</tr><tr>
	<td valign="top"><b><?=$LANGUAGE['strSurname'];?></b></td>
	<td><input type="text" name="SURNAME" value="<?=$row[SURNAME];?>" size="20"></td>
</tr><tr>
	<td valign="top"><b><?=$LANGUAGE['strEmail'];?></b></td>
	<td><input type="text" name="EMAIL" value="<?=$row[EMAIL];?>" size="20"></td>
</tr><tr>
	<td valign="top"><b><?=$LANGUAGE['strUsername'];?></b></td>
	<td><input type="text" name="USERNAME" value="<?=$row[USERNAME];?>" size="20"></td>
</tr><tr>
	<td valign="top"><b><?=$LANGUAGE['strPassword'];?></b></td>
	<td><input type="text" name="PASSWORD" value="<?=$row[PASSWORD];?>" size="20"><br>
	<font class="small"><a href="#" onclick="sendPassword()"><?=$LANGUAGE['strsendPassword'];?></a></font></td>
</tr><tr>
	<td valign="top">&nbsp;</td>
	<td><input type="submit" value="<?=$LANGUAGE['strUpdate'];?>"></td>
</tr></form>
</table>
<Script language="JavaScript">
function sendPassword() {
	var f = document.myform;
		f.act.value = "sendPassword";
		f.submit();
}					
</SCRIPT>
<?php
	}

}elseif($HTTP_POST_VARS['act'] == "update") {
	mysql_query("UPDATE ".$CFG->Tbl_Pfix."_USERS SET
				NAME = '".trim(AddSlashes($HTTP_POST_VARS[NAME]))."',
				SURNAME = '".trim(AddSlashes($HTTP_POST_VARS[SURNAME]))."',
				EMAIL = '".trim(AddSlashes($HTTP_POST_VARS[EMAIL]))."',
				USERNAME = '".trim($HTTP_POST_VARS[USERNAME])."',
				PASSWORD = '".trim($HTTP_POST_VARS[PASSWORD])."'
				WHERE 
				UID = '".$HTTP_POST_VARS['UID']."'
				") or die(mysql_error());
		if(mysql_affected_rows() != 0) {
?>
<table width="320" border="0" cellspacing="0" cellpadding="2">
<tr>
	<td><b><?=$LANGUAGE['strRecordUpdated'];?><BR><a href="users.php?page=<?=$HTTP_POST_VARS['page'];?>"><?=$LANGUAGE['strBack'];?></a></b></td>
</tr>
</table>
<?php
		}else{
?>
<table width="320" border="0" cellspacing="0" cellpadding="2">
<tr>
	<td><b><?=$LANGUAGE['strNothingUpdated'];?><BR><a href="users.php?page=<?=$HTTP_POST_VARS['page'];?>"><?=$LANGUAGE['strBack'];?></a></b></td>
</tr>
</table>

<?php
		}
}elseif($HTTP_POST_VARS['act'] == "deleteall") {
	if(sizeof($HTTP_POST_VARS['UIDS'])) {
		foreach($HTTP_POST_VARS['UIDS'] as $ID){
			mysql_query("DELETE FROM ".$CFG->Tbl_Pfix."_USERS WHERE UID = '".$ID."'") or die(mysql_error());
			if(mysql_affected_rows() != 0) {
				$i++;
				mysql_query("DELETE FROM ".$CFG->Tbl_Pfix."_REMINDERS WHERE UID = '".$ID."'") or die(mysql_error());
			}
		}
	}
		if($i != 0) {
?>
<table width="320" border="0" cellspacing="0" cellpadding="2">
<tr>
	<td><b><?=str_replace("{TOTAL}", $i, $LANGUAGE['strItemsDeleted']);?></b></td>
</tr>
</table>
<?php
	}else{
?>
<table width="320" border="0" cellspacing="0" cellpadding="2">
<tr>
	<td><b><?=$LANGUAGE['strSelectOne'];?></b></td>
</tr>
</table>
<?php
	}
}elseif($HTTP_POST_VARS['act'] == "sendPassword") {
		$sQL = mysql_query("SELECT NAME, SURNAME, EMAIL, USERNAME, PASSWORD
						FROM
						".$CFG->Tbl_Pfix."_USERS
						WHERE 
						UID = '".$HTTP_POST_VARS['UID']."'
						") or die(mysql_error());
	if(mysql_num_rows($sQL) != 0) {
		$row = mysql_fetch_array($sQL);
			$con = get_file_content($CFG->PROG_PATH . "/templates/emails/fpw_admin.tpl");
			$trans = array(
							"{NAME}" => $row["NAME"], 
							"{SURNAME}" => $row["SURNAME"], 
							"{DATETIME}" => date($LANGUAGE['date_format']." ".$LANGUAGE['time_format'], $CFG->TIME_OFFSET),
							"{strUsername}" => $LANGUAGE["strUsername"], 
							"{strPassword}" => $LANGUAGE["strPassword"], 
							"{USERNAME}" => $row["USERNAME"], 
							"{PASSWORD}" => $row["PASSWORD"], 
							"{PROG_NAME}" => $CFG->PROG_NAME,
							"{PROG_URL}" => $CFG->PROG_URL
							);
			$email_msg = strtr($con, $trans);
			send_mail($row[EMAIL], $LANGUAGE['str_ForgotPwEmailSubject'], $email_msg, $CFG->PROG_NAME, $CFG->PROG_EMAIL);
?>
<table width="320" border="0" cellspacing="0" cellpadding="2">
<tr>
	<td><b><?=$LANGUAGE['strForgotPassEmailOk'];?></b></td>
</tr>
</table>
<?php
		}else{
?>
<table width="320" border="0" cellspacing="0" cellpadding="2">
<tr>
	<td><b><?=$LANGUAGE['strForgotPassEmailError'];?></b></td>
</tr>
</table>
<?php
		}

}else{

	switch ($order) {
		case "NAME" : $order = "NAME"; break;
		case "SURNAME" : $order = "SURNAME"; break;
		case "EMAIL" : $order = "EMAIL"; break;
		case "USERNAME" : $order = "USERNAME"; break;
		case "PASSWORD" : $order = "PASSWORD"; break;
		case "LASTACCESS" : $order = "LASTACCESS"; break;
		default : $order = "UID"; break;
	}

	switch ($sort) {
		case "Asc" : $n_sort = "Desc"; break;
		case "Desc" : $n_sort = "Asc"; break;
		default : $n_sort = "Asc"; break;
	}

$sQL = "SELECT UID, NAME, SURNAME, USERNAME, PASSWORD, EMAIL, LASTACCESS FROM ".$CFG->Tbl_Pfix."_USERS ORDER BY $order $sort";

if (!$page) { $page = 1; }
$prev_page = $page - 1;
$next_page = $page + 1;
$sqlStr = mysql_query($sQL) or die (mysql_error());
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
	<td colspan="8"><font color="#FF0000" class="text"><b><?=$LANGUAGE['str_ListUsers'];?></b></font></td>
 </tr><tr>
						<td colspan="8"><?=str_replace("{TOTAL}", $row_numbers, $LANGUAGE['str_RegUsers']);?></td>
					 </tr><tr>
					 	<td colspan="8"><hr width="100%" size="1" color="#000000" noshade></td>
					 </tr><tr bgcolor="#B1B1B1">
						<td>&nbsp;</td>
						<td align="center"><input type="checkbox" name="checkall" value="checkbox" onClick="checkAll(this.form);"></td>
						<td><b><a href="?order=NAME&page=<?=$page;?>&sort=<?=$n_sort;?>"><?=$LANGUAGE['strName'];?></a> <a href="?order=surname&page=<?=$page;?>&sort=<?=$n_sort;?>"><?=$LANGUAGE['strSurname'];?></a></b></td>
						<td><b><a href="?order=EMAIL&page=<?=$page;?>&sort=<?=$n_sort;?>"><?=$LANGUAGE['strEmail'];?></a></b></td>
						<td><b><a href="?order=USERNAME&page=<?=$page;?>&sort=<?=$n_sort;?>"><?=$LANGUAGE['strUsername'];?></a></b></td>
						<td><b><a href="?order=PASSWORD&page=<?=$page;?>&sort=<?=$n_sort;?>"><?=$LANGUAGE['strPassword'];?></a></b></td>
						<td><b><a href="?order=LASTACCESS&page=<?=$page;?>&sort=<?=$n_sort;?>"><?=$LANGUAGE['strLastAccess'];?></a></b></td>
						<td align="center"><b><?=$LANGUAGE['strAction'];?></b></td>
					 </tr><tr>
					 	<td colspan="8"><hr width="100%" size="1" color="#000000" noshade></td>
					 </tr>
<?php
	while ($row = mysql_fetch_array($sQL)) {
	$i++;
		if ($bgcolor=="#FFFFFF") {$bgcolor="#EFEFEF";} else {$bgcolor="#FFFFFF";} 
?>
 <TR bgcolor="<?=$bgcolor;?>">
	<TD align="center"><font color="#FF0000"><b><?=$page_starts+$i;?>.</b></font></TD>
	<td valign="top" align="center"><input type="checkbox" name="UIDS[]" value="<?=$row[UID];?>" onClick="javascript:checkCtrl(this.form)"></td>
	<TD><font class="small"><b><?=$row[NAME];?> <?=$row[SURNAME];?></b></font></TD>
	<TD><font class="small"><a href="mailto:<?=$row[EMAIL];?>"><?=substr($row[EMAIL],0,20);?></a></font></TD>
	<TD><font class="small"><b><?=$row[USERNAME];?></b></font></TD>
	<TD><font class="small"><b><?=$row[PASSWORD];?></b></font></TD>
	<TD><font class="small"><b><?=date($LANGUAGE['date_format'] . " " . $LANGUAGE['time_format'], $row[LASTACCESS]);?></b></font></TD>
	<TD align="center"><font class="small"><a href="#" onClick="edit('<?=$row[UID];?>','edit')"><img src="../images/edit_pencil.gif" width="16" height="16" border="0" alt="<?=$LANGUAGE['strEdit'];?>"></a>&nbsp;<a href="JavaScript:void(0)" onClick="edit('<?=$row[UID];?>','delete')"><img src="../images/delete_can.gif" width="16" height="16" border="0" alt="<?=$LANGUAGE['strDelete'];?>"></a></font></TD>
 </TR>
<?php
 	}
?>
 <TR>
	<TD colspan="8"><hr width="100%" size="1" color="#000000" noshade></TD>
  </TR><TR>
	<TD colspan="8"><a href="#" onClick="edit(null,'deleteall')"><b><?=$LANGUAGE['strDelSelected'];?></a></b></td></TD>
  </TR><TR>
	<TD colspan="8"><hr width="100%" size="1" color="#000000" noshade></TD>
 </TR>
<?php
		if($row_numbers > 50) {
?><tr>
	<td colspan="8"><hr width="100%" size="1" color="#000000" noshade></td>
 </tr><TR>
 	<TD colspan="8" align="center"><b><?=$LANGUAGE['strOtherPages'];?> :</b> 
<?php
if ($prev_page){	echo "<a href=\"$ME?page=$prev_page&order=$order&sort=$n_sort\">&laquo;".$LANGUAGE['strPrevPage']."</a>";}

	for ($idx = 1; $idx <= $page_numbers; $idx++) {
    	if ($idx != $page) {
			echo " <a href=\"$ME?page=$idx&order=$order&sort=$n_sort\">$idx</a> ";
	   	} else {
			echo " <b>$idx</b> ";
		}
	}
	if ($page != $page_numbers) {
		echo "<a href=\"$ME?page=$next_page&order=$order&sort=$n_sort\">".$LANGUAGE['strNextPage']."&raquo;</a>";
	}
?>	
	</TD>
 </TR>
<?php
 		}
?>
</table>
<input type="hidden" name="page" value="<?=$page;?>">
<input type="hidden" name="act">
<input type="hidden" name="UID">
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
	if(what=='delete'){
		if(confirm('<?=$LANGUAGE['strJSConfirm'];?>')) {
			popUP("delete_user.php?UID="+id, 300, 100, "");
		}
	}

	if( (what=="edit") && (id != null || id != "") ) {
		f.act.value = "edit"
		f.UID.value = id
		f.submit();
	}
}
</SCRIPT>
<?php
		}else{
?>
<table width="100%" border="0" cellspacing="0" cellpadding="2">
  <tr>
	<td><font color="#FF0000" class="text"><b><?=$LANGUAGE['str_ListUsers'];?></b></font></td>
 </tr><tr>
	<td align="center"><b><?=$LANGUAGE['str_NoUsers'];?></b></td>
 </tr>
</table>
<?php
 		}
?>
 
<?php
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