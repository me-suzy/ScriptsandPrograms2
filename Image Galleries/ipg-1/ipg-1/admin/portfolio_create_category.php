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
 
include("../includes/config.php");
include("../includes/functions/fns_std.php");
include("../includes/functions/fns_db.php");
include('../includes/settings.php');

if(!$_SESSION['admin']){  redirect('../login.php'); }

$DOC_TITLE = PORTFOLIO_LABEL . " Administration";

db_connect();

if($_GET['action'] != 'create_cat' && $_POST['action'] != 'create_cat'){ 
    redirect('portfolio_admin.php');
}

if($_POST['submit']){
	if($_POST['name'] == '' || strlen($_POST['name']) > 64) { $msg = "Please enter a name of between 1 - 64 characters."; } else {
	if($theCatId = create_category($_POST)) { $msg = "Category <b>" . catId2catName($theCatId) . "</b> has been created."; } else { $msg = "There was a problem.  Category Not Created."; }
	}
}

include('./templates/header.php');

?> 
<table border="0" cellpadding="10" width="444">
  <tr> 
    <td> <br>
      <p><span class="admin_section_title">Create Category</span></p>
      <p><span class="admin_error_mark"> 
        <?php print $msg ?>
        </span></p>
      <form name="form1" method="post" action="<?php print $PHP_SELF ?>" enctype="multipart/form-data">
        <table width="55%" class="admin_form_box">
          <tr> 
            <td colspan="3" nowrap> 
              <p class="admin_form_header">Create Category</p>
            </td>
          </tr>
          <tr valign="middle"> 
            <td align="left" class="admin_form_label" nowrap>New Category: </td>
            <td align="left" width="46%"> 
              <input type="text" name="name">
            </td>
            <td align="left" valign="middle" width="46%"> 
              <input type="hidden" name="action" value="create_cat">
              <input type="submit" name="submit" value="Create Category">
            </td>
          </tr>
        </table>
        <br>
      </form>
	  <br>
	  <a href="index.php">Back To Main Panel</a>
    </td>
  </tr>
</table>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<?php include('./templates/footer.php'); ?>
