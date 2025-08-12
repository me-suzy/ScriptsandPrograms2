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

$theCatId = strlen($_GET['cat_id'])?$_GET['cat_id']:$_POST['cat_id'];

$DOC_TITLE = PORTFOLIO_LABEL . " Administration";

db_connect();
	$sql = "SELECT * FROM " . PDB_PREFIX . "auth WHERE cat_id = " . $theCatId;
	$result = db_query($sql);
	if(db_num_rows($result)){
		$row = db_fetch_array($result);
		$catUserExists = true;
	} else {
		$catUserExists = false;
	}

if($_GET['action'] != 'edit_cat' && $_POST['action'] != 'edit_cat' && $_POST['action'] != 'create_user'){ 
    redirect('portfolio_admin.php');
}

if($_POST['action'] == 'edit_cat'){
	if($_POST['new_name'] == '' || strlen($_POST['new_name']) > 64) { $msg = "Please enter a new name of between 1 - 64 characters."; } else {
	if(edit_category($_POST)) { $msg = "Category name has been changed."; } else { $msg = "There was a problem.  Name not changed."; }
	}
}

if($_POST['action'] == 'create_user'){
	if($_POST['username'] == '' || strlen($_POST['username']) > 32) { $msg = "Please enter a new name of between 1 - 32 characters."; } else {
		if(!$catUserExists) {
			if(create_private_user($_POST)) { $msg = "Private user created."; } else { $msg = "There was a problem.  Private user not created."; }
		} else {
			if(update_private_user($_POST)) { $msg = "Private user updated."; } else { $msg = "There was a problem.  Private user not updated."; }
			$sql = "SELECT * FROM " . PDB_PREFIX . "auth WHERE cat_id = " . $theCatId;
			$result = db_query($sql);
			if(db_num_rows($result)){
				$row = db_fetch_array($result);
			}
		}
	}
}

$theCatName = catId2catName($theCatId);

include('./templates/header.php');

?> 
<table border="0" cellpadding="10" width="444">
  <tr> 
    <td> <br>
      <p><span class="admin_section_title">Edit Category</span></p>
      <p><span class="admin_error_mark"> 
        <?php print $msg ?>
        </span></p>
      <form name="form1" method="post" action="<?php print $PHP_SELF ?>" enctype="multipart/form-data">
        <table width="55%" class="admin_form_box">
          <tr> 
            <td colspan="3" nowrap> 
              <p class="admin_form_header">&nbsp;Current Name: 
                <?php print $theCatName; ?>
              </p>
            </td>
          </tr>
          <tr valign="middle"> 
            <td align="left" class="admin_form_label" nowrap>Change Name To: </td>
            <td align="left" width="46%"> 
              <input type="text" name="new_name">
            </td>
            <td align="left" valign="middle" width="46%"> 
              <input type="hidden" name="cat_id" value="<?php print $theCatId; ?>">
              <input type="hidden" name="action" value="edit_cat">
              <input type="submit" name="submit" value="Change Category Name">
            </td>
          </tr>
        </table>
        <br>
      </form>
    </td>
  </tr>
</table>
<form name="form1" method="post" action="<?php print $PHP_SELF ?>" enctype="multipart/form-data">
  <table width="55%" class="admin_form_box">
    <tr> 
      <td colspan="3" nowrap> 
        <p class="admin_form_header">Private User For: 
          <?php print $theCatName; ?>
        </p>
      </td>
    </tr>
    <tr valign="middle"> 
      <td align="left" class="admin_form_label" nowrap>Username: </td>
      <td align="left" width="46%"> 
        <input type="text" name="username" value="<?php print $row['username']; ?>">
      </td>
    </tr>
    <tr valign="middle"> 
      <td align="left" class="admin_form_label" nowrap>Assign Password: </td>
      <td align="left" width="46%"> 
        <input type="text" name="password" value="">
      </td>
    </tr>
    <tr valign="middle"> 
      <td align="left" class="admin_form_label" nowrap>Client Name: </td>
      <td align="left" width="46%"> 
        <input type="text" name="name" value="<?php print $row['name']; ?>">
      </td>
    </tr>
    <tr> 
      <td align="left" valign="middle" width="46%"> 
        <input type="hidden" name="cat_id" value="<?php print $theCatId; ?>">
        <input type="hidden" name="action" value="create_user">
        <input type="submit" name="submit" value="Assign User">
      </td>
    </tr>
  </table>
  <br>
</form>
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
