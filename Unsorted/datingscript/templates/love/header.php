<?
############################################################
# \-\-\-\-\-\-\     AzDG  - S C R I P T S    /-/-/-/-/-/-/ #
############################################################
# AzDGDatingLite          Version 2.1.1                    #
# Writed by               AzDG (support@azdg.com)          #
# Created 03/01/03        Last Modified 04/05/03           #
# Scripts Home:           http://www.azdg.com              #
############################################################
# Template Name           Love                             #
# Author                  AzDG <support@azdg.com>          #
############################################################
# File name               header.php                       #
# File purpose            Header for love template         #
# File created by         AzDG <support@azdg.com>          #
############################################################
$m1 = explode(" ", microtime());$stime = $m1[1] + $m1[0];?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 TRANSITIONAL//EN">
<html dir=<?=C_HTML_DIR?>>
<head>
<title><?=C_SNAME?></title>
<link rel="stylesheet" type="text/css" href="<?=C_URL?>/templates/<?=C_TEMP?>/style.css">
<META name=robots content=all>
<META HTTP-EQUIV="Expires" Content="0">
<meta http-equiv=Content-Type content="text/html; charset=<?=C_CHARSET?>">
<meta name=Copyright content="AzDG.com">
<meta name=Author content="AzDG.com">
<META name=description content="">
<META name=keywords content="">
<script>
<!-- 
function open_win(win_file, win_title)
{
window.open(win_file, win_title, 'resizable=yes,width=<?=C_WIN_WIDTH?>,height=<?=C_WIN_HEIGHT?>,toolbar=no,scrollbars=auto,location=yes,menubar=no,status=no');
}
-->
</script>

</head>
<body bgcolor=#FEDEEA leftmargin=0 topmargin=0>
<center><Table border="1" cellspacing="0" cellpadding="0" bordercolor="<?=C_TBCOLOR?>" bgcolor="<?=COLOR2?>">
<tr><td width="740" height="94" align=left><img src="<?=C_URL.'/templates/'.C_TEMP?>/images/logo.jpg" border="0" height="94" width="130" alt="AzDGDating"></td></tr></table><Table CellSpacing="<?=C_BORDER?>" CellPadding="0" width="<?=C_BWIDTH?>" bgcolor="<?=C_TBCOLOR?>"><Tr><Td>
<Table Border=0 CellSpacing="<?=C_IBORDER?>" CellPadding="1" width="<?=C_BWIDTH?>" class=mes>
<?if(C_SHOW_LANG > '1') include_once C_PATH.'/include/languages.inc.php';?>
<Tr bgcolor="<?=COLOR2?>"><Td Width="20%" align="center">
<a href="<?=C_URL?>/index.php?l=<?=$l?>" class=menu><?=$w[88]?></a></Td><Td Width="20%" align="center"><a href="<?=C_URL?>/add.php?l=<?=$l?>" class=menu><?=$w[89]?></a></Td><Td Width="20%" align="center"><a href="<?=C_URL?>/login.php?l=<?=$l?>" class=menu><?=$w[90]?></a></Td><Td Width="20%" align="center"><a href="<?=C_URL?>/search.php?l=<?=$l?>" class=menu><?=$w[91]?></a></Td><Td Width="20%" align="center"><a href="<?=C_URL?>/feedback.php?l=<?=$l?>" class=menu><?=$w[92]?></a></Td></tr>
<?if((isset($_SESSION["s"]))&&(isset($_SESSION["m"]))&&(is_numeric($_SESSION["m"]))&&($_SESSION["s"]) == md5(ip())) {
?>
<Tr bgcolor="<?=COLOR1?>" class=desc><Td colspan=5><?=$w[95]?>[<?=$_SESSION['m'];?>]:<br><a href="<?=C_URL?>/view.php?l=<?=$l?>&id=<?=$_SESSION['m']?>" class="menu">[<?=$w[98]?>]</a> &nbsp; <a href="<?=C_URL?>/members/index.php?l=<?=$l?>&a=c" class="menu">[<?=$w[99]?>]</a> &nbsp; <a href="<?=C_URL?>/members/index.php?l=<?=$l?>&a=p" class="menu">[<?=$w[100]?>]</a> <?if(C_REMOVE_ALLOW != '0') {?>&nbsp; <a href="<?=C_URL?>/members/index.php?l=<?=$l?>&a=h" class="menu">[<?=$w[101]?>]</a><?}?> &nbsp; <a href="<?=C_URL?>/members/index.php?l=<?=$l?>&a=e" class="menu">[<?=$w[102]?>]</a>
</Td></Tr>
<?}?>
</table></Td></Tr></table>
<?php
if(file_exists("install.php")) printm("<i>Security Alert</i>: Please remove install.php",2); 
?>