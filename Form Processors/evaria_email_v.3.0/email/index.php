<?php

// ***************************************************************************
// * Copyright © 2003 - Thomas Egtvedt - www.evaria.com - thomas@evaria.com  *
// *                                                                         *
// *         EMAIL - Evaria Mail Client - Support: forum.evaria.com          *
// *                                                                         *
// * This program is commercial software; you can not redistribute/reproduce *
// * it and/or sell it without the prior written consent of www.evaria.com   *
// *                                                                         *
// * This program is distributed in the hope that it will be useful,         *
// * but WITHOUT ANY WARRANTY; without even the implied warranty of          *
// * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.                    *
// ***************************************************************************
//
// This is an example page for the Evaria Mail Client, 
// please review readme4.txt before installing it on your server.
//

// Get time when this page is requested...
function getmicrotime()
{
    list($usec, $sec) = explode(" ",microtime());
    return ((float)$usec + (float)$sec);
}
$time_start = getmicrotime();

// Include your functions and variables...
include "./admin/config.inc.php";

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<HTML>
<HEAD>

<TITLE><?=$sitename?> | email version 3.0</TITLE>

  <META http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
  <META http-equiv="Pragma" content="no-cache">
  <META http-equiv="Refresh" Content="1500">
  <META http-equiv="Expires" Content="0">
  <META name="Keywords" content="evaria, ecms, email, yourwords">
  <META name="Description" content="evaria.com - ecms - evaria mail client">
  <META name="Author" content="thomas egtvedt">
  <META name="Distribution" content="global">
  <META name="Robots" content="all">
  <LINK rel="stylesheet" href="./style/ecms.css" type="text/css">
  <style type="text/css">
  <!--
  /* Import the fancy styles for IE only (NS4.x doesn't use the @import function) */
  @import url("./style/ie.css");
  -->
  </style>

<SCRIPT language=JavaScript src="./admin/seek.js" type=text/javascript></SCRIPT>

</HEAD>

<BODY class="bar" bgcolor="#F3F3F3" text="#000000" leftmargin="0" topmargin="0"><A name="top"></A>

<TABLE class="col_top" width="800" align="center" cellspacing="0" cellpadding="0" border="0">
  <TR>
    <TD valign="top">

<!-- Start White Separator -->
<TABLE width="100%" cellspacing="0" cellpadding="0" border="0"><TR><TD><img src="./graphic/bg_white.gif" width="800" height="2" alt="evaria" border="0"></TD></TR></TABLE>

<!-- Start ECMS Header -->
<TABLE bgcolor="#EBEBEB" width="100%" align="center" cellspacing="0" cellpadding="0" border="0">
  <TR>
	<TD><img src="./graphic/bg_pix.gif" width="170" height="1" alt="evaria" border="0"></TD>
	<TD colspan="2"><img src="./graphic/bg_pix.gif" width="610" height="1" alt="evaria" border="0"></TD>
	<TD><img src="./graphic/bg_pix.gif" width="20" height="1" alt="evaria" border="0"></TD>
  </TR>
  <TR>
    <TD>&nbsp;</TD>
	<TD><a href="<?=$baseurl?>"><img src="./img/ecms.png" width="410" height="40" alt="Home" border="0"></a></TD>
	<TD class="info"><img src="./graphic/bg_pix.gif" width="170" height="15" alt="evaria" border="0"><br>
	<b>&raquo; email client v.3.0 <?=$copyright?></b></TD>
	<TD>&nbsp;</TD>
  </TR>
</TABLE>	

<!-- Start White Separator -->
<TABLE width="100%" cellspacing="0" cellpadding="0" border="0"><TR><TD><img src="./graphic/bg_white.gif" width="800" height="2" alt="evaria" border="0"></TD></TR></TABLE>

<!-- Start Main Block -->
<TABLE bgcolor="#DEDEDE" width="100%" cellspacing="0" cellpadding="0" border="0">
  <TR>

<!-- Start Left Cell -->
	<TD align="center" valign="top"><img src="graphic/bg_pix.gif" 
	width="170" height="10" alt="evaria" border="0"></TD>

<!-- Start Main Cell (Content) -->	
	<TD valign="top" align="center" bgcolor="#FFFFFF"><img src="graphic/bg_pix.gif"
	width="610" height="20" alt="evaria" border="0"><br>
<?php
// This is where the Evaria Mail Client gets included...
include "./admin/contact.inc";
?>
    <br><br>
	</TD>
	
<!-- Start Right Cell -->	
	<TD valign="top" bgcolor=""><img src="graphic/bg_pix.gif" width="20" height="1"
	alt="evaria" border="0"></TD>
  </TR>
</TABLE>

    </TD>
  </TR>
</TABLE>

<!-- Start White Separator -->
<TABLE width="100%" cellspacing="0" cellpadding="0" border="0"><TR><TD><img src="./graphic/bg_white.gif" width="800" height="2" alt="evaria" border="0"></TD></TR></TABLE>

<?php
// Get time when this page is produced...
// Then calculate loading time...
$time_end = getmicrotime();
$time = $time_end - $time_start;
$time =number_format($time , 4);
?>

<!-- Start Page Stats -->
<TABLE bgcolor="#EFEFEF" align="center" width="800" cellspacing="0" cellpadding="0" border="0">
  <TR>
    <TD class="footer" align="center">Powered by: <a href="http://www.evaria.com/en/?view=php" target="_blank">email v.3.0</a></TD>
  </TR>
</TABLE>

<!-- Start White Separator -->
<TABLE width="100%" cellspacing="0" cellpadding="0" border="0"><TR><TD><img src="./graphic/bg_white.gif" width="800" height="2" alt="evaria" border="0"></TD></TR></TABLE>

</BODY>
</HTML>
