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
<meta name="keywords" content="">
<meta name="description" content=" ">
<SCRIPT LANGUAGE=JAVASCRIPT TYPE="TEXT/JAVASCRIPT">
function styleWindow(stylePage){
newWindow = window.open(stylePage, 'newWin', 
           'toolbar=no,location=no,resizable=yes,scrollbars=yes'); 
}
</SCRIPT>
<link rel="stylesheet" href="./CSS/main.css" type="text/css">
</head>
<body leftmargin="0" marginwidth="0" topmargin="0" marginheight="0">
<table border="0" width="100%" cellpadding="0" cellspacing="0">
  <tr> 
    <td class="header">
<?php if(strlen(SITE_NAME))  { ?>
	<div class="header_site_name"><?php print SITE_NAME; ?></div><br>
<?php } ?>
	<div class="header_text"><?php print HEADER_TEXT; ?></div></td>
  </tr>
</table>
<?php if(MENU_POS == '0') { ?>
<table border="0" width="100%" cellpadding="0" cellspacing="0">
  <tr> 
    <td class="header_menu_bar"> <a href="index.php" class="menu_pdb">Home</a>
      <div class="menu_divider"></div>
      <?php print build_menu(); ?>
      <a href="login.php" class="menu_pdb">Login</a> 
<?php if($_SESSION['user_id']){ ?>
	  <?php if(!$_SESSION['admin']){ ?>
	  <div class="menu_divider"></div>
      <a href="private.php" class="menu_pdb">Private Gallery</a> 
      <div class="menu_divider"></div>
      <a href="logout.php" class="menu_pdb">Logout</a> 
      <?php }//end if
	  }//end if ?>
    </td>
  </tr>
</table>
<?php }//end if ?>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <?php if(MENU_POS == '1') { ?>
    <td valign="top" class="left_sidebar"> <a href="index.php" class="menu_pdb">Home</a>
      <div class="menu_divider"></div>
      <?php print build_menu(); ?>
      <a href="login.php" class="menu_pdb">Login</a> 
      <?php if($_SESSION['user_id']){ ?>
	  <?php if(!$_SESSION['admin']){ ?>
	  <div class="menu_divider"></div>
      <a href="private.php" class="menu_pdb">Private Gallery</a> 
      <div class="menu_divider"></div>
      <a href="logout.php" class="menu_pdb">Logout</a> 
      <?php }//end if
	  }//end if ?>
    </td>
    <?php }//end if ?>
    <td width="100%" valign="top">
      <div class="main_content_box"> 

