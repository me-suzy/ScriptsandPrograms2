<?
############################################################
# \-\-\-\-\-\-\     AzDG  - S C R I P T S    /-/-/-/-/-/-/ #
############################################################
# AzDGDatingLite          Version 2.1.1                    #
# Writed by               AzDG (support@azdg.com)          #
# Created 03/01/03        Last Modified 04/05/03           #
# Scripts Home:           http://www.azdg.com              #
############################################################
# Template Name           Default                          #
# Author                  AzDG <support@azdg.com>          #
############################################################
# File name               header.php                       #
# File purpose            Header for default template      #
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
<META name=description content="guestbook script from AzDG">
<META name=keywords content="guestbook script from AzDG">
<script>
<!-- 
function open_win(win_file, win_title)
{
window.open(win_file, win_title, 'resizable=yes,width=<?=C_WIN_WIDTH?>,height=<?=C_WIN_HEIGHT?>,toolbar=no,scrollbars=auto,location=no,menubar=no,status=no');
}
-->
</script>
</head>
<body bgcolor=#BDD3F4 leftmargin=0 topmargin=0>
