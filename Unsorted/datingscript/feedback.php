<?php
############################################################
# \-\-\-\-\-\-\     AzDG  - S C R I P T S    /-/-/-/-/-/-/ #
############################################################
# AzDGDatingLite          Version 2.1.1                    #
# Writed by               AzDG (support@azdg.com)          #
# Created 03/01/03        Last Modified 04/05/03           #
# Scripts Home:           http://www.azdg.com              #
############################################################
# File name               feedback.php                     #
# File purpose            Feedback with webmaster          #
# File created by         AzDG <support@azdg.com>          #
############################################################
include_once 'include/config.inc.php';
include_once 'include/options.inc.php';
include_once 'include/security.inc.php';
include_once 'include/functions.inc.php';
include_once 'templates/'.C_TEMP.'/config.php';
security(C_FEEDBACK,$w[152]);
include_once 'templates/'.C_TEMP.'/header.php';

if(!isset($a)) $a='';
if ($a == "s") {
if(empty($subject)||empty($message)||empty($remail)) printm($w[164]);
if (c_email($remail) == 0) printm($w[11]);
$subject=cb($subject);$message=cbmail($message);$remail=cb($remail);
unset($deactive);unset($m); // Special for hackers:)
if(isset($_SESSION['m']) && is_numeric($_SESSION['m'])) { 
$tmp=mysql_query("SELECT email FROM ".C_MYSQL_MEMBERS." WHERE id='".$_SESSION['m']."' AND status >= '7'");
$row=mysql_fetch_array($tmp);
$deactive = C_FEEDBACK_MAIL ? $remail : $row['email']; 
} else $deactive=$remail;
sendmail($deactive,C_ADMINM,$subject,$message,'text');
printm($w[165],2);
} else {
?>
<script language="JavaScript">
<!--
function formCheck(form) {
if (form.subject.value == "")
{alert("<?=$w[166]?>");return false;}
if (form.message.value == "")
{alert("<?=$w[167]?>");return false;}
if (form.remail.value == "")
{alert("<?=$w[11]?>");return false;}

if (document.form.submit.action != "") {
document.form.submit.disabled=1;}
}
// -->
</script>

<form action="feedback.php" method="post" name=form OnSubmit="return formCheck(this)">
<input class=input type=hidden name="l" value="<?=$l?>">
<input class=input type=hidden name="a" value="s">
<center><span class=head><?=$w[92]?></span>
<br>
<Table CellSpacing="<?=C_BORDER?>" CellPadding="0" width="<?=C_SWIDTH?>" bgcolor="<?=C_TBCOLOR?>"><Tr><Td>
<Table Border=0 CellSpacing="<?=C_IBORDER?>" CellPadding="<?=C_CELLP?>" width="<?=C_SWIDTH?>" class=mes>
<Tr align="<?=C_ALIGN?>" bgcolor="<?=COLOR1?>"><Td>
<?=$w[168]?><?=$w[0];?></td><td><input class=input type=text name=subject maxlength="40"></td></tr>
<Tr align="<?=C_ALIGN?>" bgcolor="<?=COLOR1?>"><Td>
<?=$w[169]?><?=$w[0];?></td><td><textarea class=textarea name=message cols=40 rows=15></textarea></td></tr>
<?
unset($deactive);unset($m); // Special for hackers:)
if(isset($_SESSION['m']) && is_numeric($_SESSION['m'])) { // If user registered and login
$tmp=mysql_query("SELECT email FROM ".C_MYSQL_MEMBERS." WHERE id='".$_SESSION['m']."' AND status >= '7'");
$row=mysql_fetch_array($tmp);
$deactive = C_FEEDBACK_MAIL ? 'value="'.$row['email'].'"' : 'value="'.$row['email'].'" disabled'; 
}
else $deactive='';
?>
<Tr align="<?=C_ALIGN?>" bgcolor="<?=COLOR1?>"><Td>
<?=$w[60]?><?=$w[0];?></td><td><input class=input type=text name=remail <?=$deactive?>></td></tr>
<Tr align="<?=C_ALIGN?>" bgcolor="<?=COLOR1?>"><Td colspan="2">
<input class=input type=submit value="<?=$w[170]?>" name="submit">
</td></tr></table></td></tr></table>
</form>
<?}include_once 'templates/'.C_TEMP.'/footer.php';?>