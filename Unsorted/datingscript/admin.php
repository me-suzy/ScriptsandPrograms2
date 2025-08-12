<?
############################################################
# \-\-\-\-\-\-\     AzDG  - S C R I P T S    /-/-/-/-/-/-/ #
############################################################
# AzDGDatingLite          Version 2.1.1                    #
# Writed by               AzDG (support@azdg.com)          #
# Created 03/01/03        Last Modified 04/05/03           #
# Scripts Home:           http://www.azdg.com              #
############################################################
# File name               admin.php                        #
# File purpose            Form for enter in Admin Area     #
# File created by         AzDG <support@azdg.com>          #
############################################################
include_once 'include/config.inc.php';
include_once 'include/options.inc.php';
include_once 'languages/'.C_ADMINLANG.'/'.C_ADMINLANG.'.php';
include_once 'languages/'.C_ADMINLANG.'/'.C_ADMINLANG.'_.php';
include_once 'languages/'.C_ADMINLANG.'/'.C_ADMINLANG.'a.php';
include_once 'include/functions.inc.php';
include_once 'templates/'.C_TEMP.'/config.php';

if(!isset($p)) $p=''; 
if ($p == "login") {
if(($login != C_ADMINL)||($password != C_ADMINP)) {
include_once 'templates/'.C_TEMP.'/header.php';
printm($x[1]);
}
else {session_destroy();session_start();unset($adminlogin);unset($adminpass);unset($adminip);$_SESSION['adminlogin']=md5(C_ADMINL);$_SESSION['adminpass']=md5(C_ADMINP);$_SESSION['adminip']=md5(ip());unset($adminlogin);unset($adminpass);unset($adminip);  }
Header("Location:".C_URL."/admin/index.php?a=v&".s());
} 
else 
{
include_once 'templates/'.C_TEMP.'/header.php';
?>
<form action="admin.php" method="post">
<input type="hidden" name="l" value="<?=$l?>">
<input type="hidden" name="p" value="login">
<center><span class=head><?=$x[2]?></span></center>
<br>
<Table CellSpacing="<?=C_BORDER?>" CellPadding="0" width="<?=C_SWIDTH?>" bgcolor="<?=C_TBCOLOR?>"><Tr><Td>
<Table Border=0 CellSpacing="<?=C_IBORDER?>" CellPadding="<?=C_CELLP?>" width="<?=C_SWIDTH?>" class=mes>
<Tr align="<?=C_ALIGN?>" bgcolor="<?=COLORH?>"><Td>
<?=$x[3]?></td><td><input class=minput type=text name=login></td></tr>
<Tr align="<?=C_ALIGN?>" bgcolor="<?=COLORH?>"><Td><?=$x[4]?></td><td><input class=minput type=password name=password></td></tr>
<Tr align="<?=C_ALIGN?>" bgcolor="<?=COLORH?>"><Td colspan=2 align=right><input class=minput type=submit value="<?=$x[5]?>"></td></tr>
</table></td></tr></table></form>
<?
}
include_once 'templates/'.C_TEMP.'/footer.php';
?>