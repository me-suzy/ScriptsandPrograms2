<?php
/******************************************************************************
* IPG: Instant Photo Gallery                                                  *
* =========================================================================== *
* Software Version:             IPG 1.0                                       *
* Copyright 2005 by:            Verosky Media - Edward Verosky                *
* Support, News, Updates at:    http://www.instantphotogallery.com            *
*******************************************************************************
* This program is free software; you may redistribute it and/or modify it     *
* under the terms of the GNU General Public License as published by the Free  * 
* Software Foundation; either version 2 of the License, or (at your option)   *
* any later version.                                                          *                                                                             *
* This program is distributed WITHOUT ANY WARRANTIES; without even any        *
* implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.    *
*                                                                             *
* See www.gnu.org  for details of the GPL license.                            *
******************************************************************************/
?>

<HTML>
<HEAD>
<title>
<?php print $DOC_TITLE ?>
</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel="stylesheet" href="../CSS/main.css" type="text/css">
	<script language="JavaScript" type="text/javascript" src="./rte/html2xhtml.js"></script>
	<!-- To decrease bandwidth, use richtext_compressed.js instead of richtext.js //-->
	<script language="JavaScript" type="text/javascript" src="./rte/richtext.js"></script>
<script type='text/javascript'>
function confirmCategoryDelete(){
  if(confirm("Do you really want to delete this entire category and the images that are in it permanently?")){
	  return true;
  } else {
	  return false;
  }
}

function confirmContentDelete(){
  if(confirm("Do you really want to delete this content page permanently?")){
	  return true;
  } else {
	  return false;
  }
}
</script>
</head>
<body bgcolor="#FFFFFF" text="#000000" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" link="#0033CC" vlink="#0033CC" alink="#0033CC">
<table width="100%" border="0" cellspacing="0" cellpadding="0" height="27">
  <tr> 
    <td bgcolor="#000000" width="35%"><b><font color="yellow" size="+2" face="Arial, Helvetica, sans-serif">&nbsp;&nbsp; 
      <?php print $row['portfolio_name'] ?>
      </font></b></td>
    <td bgcolor="#000000" align="right" width="65%"><b><font color="white" size="+2" face="Arial, Helvetica, sans-serif">&nbsp;&nbsp;
      <?php print PORTFOLIO_LABEL; ?>
      Administration</font><font size="+2">&nbsp;&nbsp;</font></b></td>
  </tr>
  <tr> 
    <td colspan="2" style="background-image: url(../images/header_1_extender.jpg); background-repeat:repeat-x;">&nbsp;&nbsp;<a href="./portfolio_admin.php" class="menu_pdb_admin">Administration 
      Home </a> | <a target="_blank" href="../portfolio.php" class="menu_pdb_admin">View 
      Your 
      <?php print PORTFOLIO_LABEL; ?>
      </a> | <a href="../logout.php" class="menu_pdb_admin">Logout</a></td>
  </tr>
  <tr bgcolor="#000000"> 
    <td colspan="2"><font color="#000000">...</font></td>
  </tr>
</table>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td width="100%" valign="top">
      <div class="main_content_box"> 
