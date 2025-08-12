<?php
############################################################
# \-\-\-\-\-\-\     AzDG  - S C R I P T S    /-/-/-/-/-/-/ #
############################################################
# AzDGDatingLite          Version 2.1.1                    #
# Writed by               AzDG (support@azdg.com)          #
# Created 03/01/03        Last Modified 04/05/03           #
# Scripts Home:           http://www.azdg.com              #
############################################################
# File name               remind.php                       #
# File purpose            Forget password script           #
# File created by         AzDG <support@azdg.com>          #
############################################################
include_once 'include/config.inc.php';
include_once 'include/options.inc.php';
include_once 'include/functions.inc.php';
include_once 'include/security.inc.php';
include_once 'templates/'.C_TEMP.'/config.php';
include_once 'templates/'.C_TEMP.'/header.php';

if(!isset($p)) $p=''; 
if ($p == "s") {
$inquiry='';
switch (C_ID) {
  case '2':
    if (c_email($id) == 0) {
      include_once 'templates/'.C_TEMP.'/header.php';
      printm($w[11]);
    }
    $inquiry=" email='".cbmail($id)."'";
  break;
  default: // default by ID
    if (!is_numeric($id) || empty($id)) {
      include_once 'templates/'.C_TEMP.'/header.php';
      printm($w[185]);
    }
    $inquiry=" id='".$id."'";
  break;
  }
$tmp=mysql_query("SELECT email, password FROM ".C_MYSQL_MEMBERS." WHERE".$inquiry." AND status >= '7' LIMIT 1");
if(mysql_num_rows($tmp) == '0') printm($w[196]);
$i=mysql_fetch_array($tmp);
$tm=array(C_SNAME);
$subject=template($w[195],$tm);
$tm=array($id,$i['password'],C_SNAME);
$message=template($w[197],$tm);
sendmail(C_FROMM,$i['email'],$subject,$message,'text');
printm($w[198],2);
} else {
?>
<script language="JavaScript">
<!--
function formCheck(form) {
if (form.id.value == "")
{alert("<?=$w[164]?>");return false;}

if (document.form.submit.action != "") {
document.form.submit.disabled=1;}
}
// -->
</script>

<form action="remind.php" method="post" name=form OnSubmit="return formCheck(this)">
<input class=input type=hidden name="l" value="<?=$l?>">
<input class=input type=hidden name="p" value="s">
<center><span class=head><?=$w[173]?></span>
<br>
<Table CellSpacing="<?=C_BORDER?>" CellPadding="0" width="<?=C_SWIDTH?>" bgcolor="<?=C_TBCOLOR?>"><Tr><Td>
<Table Border=0 CellSpacing="<?=C_IBORDER?>" CellPadding="<?=C_CELLP?>" width="<?=C_SWIDTH?>" class=mes>
<Tr align="<?=C_ALIGN?>" bgcolor="<?=COLOR1?>"><Td>
<?=login(C_ID);?><?=$w[0];?>
</td><td><input class=input type=text name=id maxlength="64"></td></tr>
<Tr align="<?=C_ALIGN?>" bgcolor="<?=COLOR1?>"><Td colspan="2">
<input class=input type=submit value="<?=$w[200]?>" name="submit">
</td></tr></table></td></tr></table>
</form>
<?php
}
include_once 'templates/'.C_TEMP.'/footer.php';
?>