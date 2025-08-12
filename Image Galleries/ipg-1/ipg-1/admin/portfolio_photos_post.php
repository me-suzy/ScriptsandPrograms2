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

include('../includes/config.php');
include('../includes/functions/fns_db.php');
include('../includes/settings.php');
include('../includes/functions/fns_std.php');
include('../includes/functions/portfolios.php');
include("../includes/classes/fileupload-class.php");

if(!$_SESSION['admin']){  redirect('../login.php'); }

$DOC_TITLE = "Post Your Photos";

db_connect();

//setup selection boxes
$sql = "SELECT id, cat_name FROM " . PDB_PREFIX . "categories ORDER BY cat_name";
$result = db_query($sql);
while($row = db_fetch_array($result)){
  $selPortfolioCats[$row['id']] = $row['cat_name'];
 }

$theCatId = strlen($_GET['cat_id'])?$_GET['cat_id']:$_POST['cat_id'];
$theCatName = catId2catName($theCatId);

// Reorder the display

$arrImageOrderList = reorder_images($theCatId, $_SESSION['user_id']);

  if($_POST['direction'] == 'UP'){
      $sql1 = "UPDATE " . PDB_PREFIX . "images_to_categories SET display_order = IF((display_order - 1)<0, 0, (display_order - 1)) WHERE image_id = " . $arrImageOrderList[$_POST['display_order']];
	  db_query($sql1);
	  $sql2 = "UPDATE " . PDB_PREFIX . "images_to_categories SET display_order = (display_order + 1) WHERE image_id = " . $arrImageOrderList[$_POST['display_order'] - 1];
	  db_query($sql2);
  }elseif($_POST['direction'] == 'DOWN'){
      $sql3 = "UPDATE " . PDB_PREFIX . "images_to_categories SET display_order = (display_order + 1) WHERE image_id = " . $arrImageOrderList[$_POST['display_order']];
	  db_query($sql3);
	  $sql4 = "UPDATE " . PDB_PREFIX . "images_to_categories SET display_order = IF((display_order - 1)<0, 0, (display_order - 1))  WHERE image_id = " . $arrImageOrderList[$_POST['display_order'] + 1];
	  db_query($sql4);
  }

include('./templates/header.php');

if($_POST['photo_info_update']){

	$sql = "UPDATE " . PDB_PREFIX . "images  
  				SET title = '" . $_POST['title'] . "', 
				comment_requested = '" . $_POST['comment_requested'] . "',
				copyright = '" . $_POST['copyright'] . "', 
				caption =  '" . $_POST['imageCaption'] . "',
				public_view = " . 1 . "  
				WHERE id = " . $_POST['image_id'];
	db_query($sql);
	$sql = "UPDATE " . PDB_PREFIX . "images_to_categories  
  				SET cat_id = " . $_POST['port_cat'] . " 
				WHERE image_id = " . $_POST['image_id'] . "";
	db_query($sql);
}


if($_POST['delete']){
	delete_image(DIR_PORTFOLIOS . "/thumbs/", $_POST['image_to_handle']);
	delete_image(DIR_PORTFOLIOS . "/", $_POST['image_to_handle']);  
	$sql = "DELETE FROM " . PDB_PREFIX . "images
  			WHERE id = " . $_POST['image_id'];
	db_query($sql);
	$sql = "DELETE FROM " . PDB_PREFIX . "images_to_categories 
  			WHERE image_id = " . $_POST['image_id'] . "";
	db_query($sql);
}

if($_POST['submit']) {

		$path = DIR_PORTFOLIOS; 
		$image_url = PORTFOLIO_IMAGE_URL;
		$upload_file_name = "image1";
		$acceptable_file_types = "image/jpeg|image/pjpeg";
		$default_extension = "";
		$mode = 2;	// this is 'create new with incremental extention'.

		$my_uploader = new uploader;
		
		// OPTIONAL: set the max filesize of uploadable files in bytes
		$my_uploader->max_filesize(MAX_FILESIZE);
	
		// UPLOAD the file
		if ($my_uploader->upload($upload_file_name, $acceptable_file_types, $default_extension)) {
			$success = $my_uploader->save_file($path, $mode);
		}
		
		if ($success) {
		
					$msg .= ($my_uploader->file['name'] . " Successfully Uploaded!");
					$imageFileName = $my_uploader->file['name'];
					
					// Place data in db
					
					if($_POST['hide'] == '1') {
						$publicView = 0;
					} else {
						$publicView = 1;
					}
								
					$sql = "INSERT INTO " . PDB_PREFIX . "images 
					VALUES (0, " . 
					"'" . $imageFileName .
					"', '" . $_POST['title'] .
					"', '" . $_POST['imageCaption'] .  
					"', '" . '0' .  
					"', '" . $_POST['copyright'] .
					"',0," . $my_uploader->total_filesize . ", " . $publicView . ")";

					db_query($sql);
							
					$sql = "INSERT INTO " . PDB_PREFIX . "images_to_categories 
					VALUES (" . mysql_insert_id() . ", " . $_POST['cat_id'] . ",0)";
		
					db_query($sql);
					
		} else {
			// ERROR uploading...
 			if($my_uploader->errors) {
				while(list($key, $var) = each($my_uploader->errors)) {
					$msg .= $var . "<br>";
				}//end while upload errors
 			}//end if upload errors
 		}//end if successful upload
		/* END IMAGE UPLOAD */
		
}//end if submit

?> 
<table border="0" cellpadding="10">
  <tr> 
    <td> <br>
      <p><span class="admin_section_title">
        <?php print $theCatName . "&nbsp;Category<br>"; ?>
        Image Manager</span></p>
      <p><span class="admin_error_mark">
        <?php print $msg ?>
        </span></p>
      <?php 
	if($_POST['update_info']){
	  $sql = "SELECT * FROM " . PDB_PREFIX . "images i, " . PDB_PREFIX . "images_to_categories i2c 
				WHERE i.id = " . $_POST['image_id'] . " AND i2c.image_id = i.id";
	  db_connect();
	  $result = db_query($sql);
	  $updateRow = db_fetch_array($result);
	  include('./portfolio_photos_info_update_form.php');
	} else {
?>
      <form name="form1" method="post" action="<?php print $PHP_SELF ?>" enctype="multipart/form-data">
        <table width="55%" class="admin_form_box">
          <tr> 
            <td colspan="2" nowrap> 
              <p class="admin_form_header">&nbsp;ADD AN IMAGE TO: 
                <?php print $theCatName; ?>
                Category&nbsp;</p>
            </td>
          </tr>
          <tr valign="middle"> 
            <td align="left" class="admin_form_label">Image: </td>
            <td align="left"> 
              <input type="file" name="image1">
            </td>
          <tr valign="middle" align="left"> 
            <td class="admin_form_label">Title: </td>
            <td> 
              <input type="text" maxlength="64" name="title">
            </td>
          </tr>
          <tr valign="middle" align="left"> 
            <td class="admin_form_label">Caption: </td>
            <td> 
              <input type="text" maxlength="64" name="imageCaption">
            </td>
          </tr>
          <tr valign="middle" align="left"> 
            <td class="admin_form_label">Credit: </td>
            <td> 
              <input type="text" maxlength="64" name="photographer">
			</td>
          </tr>
          <tr valign="middle" align="left"> 
            <td class="admin_form_label">Copyright: </td>
            <td> 
              <input type="text" maxlength="64" name="copyright">
            </td>
          </tr>
          <tr> 
            <td align="left" valign="top">&nbsp;</td>
            <td align="left" valign="middle"> 
              <input type="hidden" name="cat_id" value="<?php print $theCatId; ?>">
              <input type="submit" name="submit" value="Upload">
            </td>
          </tr>
        </table>
        <br>
      </form>
      <?php 
	}//end if update or not

?>
    </td>
  </tr>
</table>
<?php
	reorder_images($theCatId, $_SESSION['user_id']);
	$sql = "SELECT * FROM " . PDB_PREFIX . "images i, " . PDB_PREFIX . "images_to_categories i2c 
			WHERE i.id = i2c.image_id 
			AND i2c.cat_id = " . $theCatId . "  ORDER BY i2c.display_order";
	$result = db_query($sql);
?>
<table width="55%" border="1" cellspacing="0" cellpadding="0" bordercolor="#CCCCCC">
  <tr class="admin_form_header" align="center" valign="middle"> 
    <td colspan="4"><b>Current Images In 
      <?php print $theCatName; ?>
      &nbsp;Category<br>
      </b></td>
  </tr>
  <tr class="admin_form_label" bgcolor="#FFFFFF"> 
    <td align="center" valign="middle" width="19%">Edit</td>
    <td align="center" valign="middle" width="50%">Image Description</td>
    <td align="center" width="20%" valign="middle">Order</td>
    <td align="center" valign="middle">Image</td>
  </tr>
  <?php
  while($rowPics = db_fetch_array($result)){

?>
  <form name="form2" method="POST" action="<?php print $PHP_SELF ?>">
    <tr> 
      <td align="center" valign="middle" width="19%" bgcolor="#000000"> <font face="Arial, Helvetica, sans-serif"> 
        <input type="hidden" name="cat_id" value="<?php print $theCatId; ?>">
        <input type="hidden" name="image_id" value="<?php print $rowPics['image_id']; ?>">
        <input type="hidden" name="display_order" value="<?php print $rowPics['display_order']; ?>">
        <input type="submit" name="update_info" value="Update Info">
        <input type="submit" name="delete" value="Delete">
        </font></td>
      <td align="center" valign="middle" width="50%" bgcolor="#000000"> <font face="Arial, Helvetica, sans-serif"> 
        <?php if($rowPics['display_order'] == 1) print '<div style="font-weight:bold; color: yellow;">SHOWCASE IMAGE</div>'; ?>
        <font color="#FFFFFF"> 
        <input type="hidden" name="image_to_handle" value="<?php print $rowPics['image'] ?>">
        <?php print $rowPics['image'] . "<br>" . $rowPics['title'] . "<br>" . $rowPics['caption']; ?>
        </font></font></td>
      <td align="center" width="20%" valign="middle" bgcolor="#FFFFFF"> 
        <?php if($rowPics['display_order'] > 1) { ?>
        <input type="submit" name="direction" value="UP">
        <br/>
        <?php }//end if ?>
        <?php if($rowPics['display_order'] < db_num_rows($result)) { ?>
        <input type="submit" name="direction" value="DOWN">
        <?php }//end if ?>
      </td>
      <td align="center" valign="middle" bgcolor="#000000"><img src="<?php print PORTFOLIO_IMAGE_URL . "/thumbs/".$rowPics['image'] ?>"></td>
    </tr>
  </form>
  <?php
 }//end while image files

?>
</table>
<p>&nbsp;</p>
<?
include('./templates/footer.php');

/***********************************************************
 * FUNCTIONS
***********************************************************/

function reorder_images($categoryId, $userId) {
 db_connect();
  $sql = "SELECT image_id, display_order FROM " . PDB_PREFIX . "images i, " . PDB_PREFIX . "images_to_categories i2c 
			WHERE i.id = i2c.image_id 
			AND i2c.cat_id = " . $categoryId . "  ORDER BY i2c.display_order";
  $result = db_query($sql);
  $seqNum = 1;
	while($row = db_fetch_array($result)){
		$sql = "UPDATE " . PDB_PREFIX . "images_to_categories SET display_order = " . $seqNum . " 
		WHERE image_id = " . $row['image_id'];
		db_query($sql);
		$orderList[$seqNum] = $row['image_id'];
		$seqNum++;
	}
	return $orderList;
}


?>
