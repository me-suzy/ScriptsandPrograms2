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

if($_POST['action'] == 'edit_config'){
	foreach($_POST as $name => $value) {
		if(eregi("key_value_", $name)) {
			$name = str_replace("key_value_", "", $name);
				$sql = "UPDATE " . PDB_PREFIX . "configuration SET config_value = '" . $value . 
			"' WHERE config_key = '" . $name . "'";
			$result = db_query($sql);
			if(db_affected_rows($result)){
			    $msg = "Configuration Updated";
			}
		}//end if
	}//end foreach
}//end if

include('./templates/header.php');

?> 
<p><br>
  <span class="admin_section_title">Edit Configuration Settings</span><br>
  Only new uploaded images will be saved according to the dimensions listed below.<br>
  Previously uploaded images will remain their current size.</p>
<p><span class="admin_error_mark"> 
  <?php print $msg ?>
  </span></p>
<form name="form1" method="post" action="<?php print $PHP_SELF ?>" enctype="multipart/form-data">
  <?php
	$sql = "SELECT * FROM " . PDB_PREFIX . "configuration ORDER BY id";
	$result = db_query($sql);
	while($row = db_fetch_array($result)){
?>
  <table class="admin_form_box">
    <tr> 
      <td width="200" align="left"  nowrap><span class="admin_form_label">
        <?php print $row['config_name']; ?>
        </span><br>
        <span style="font-size: 11px;">
        <?php print $row['config_description']; ?>
        </span></td>
      <td align="right" width="46%"> 
        <?php if($row['config_key'] == 'MAX_FILESIZE') { print number_format((intval($row['config_value'])/1024/1024),2) . 'MB'; } ?>
        <input size="10" type="text" name="key_value_<?php print $row['config_key']; ?>" value="<?php print $row['config_value']; ?>">
      </td>
    </tr>
  </table>
  <?php   }//end while
?>
  <input type="hidden" name="action" value="edit_config">
  <input type="submit" name="submit" value="Update">
</form>

<?php include('./templates/footer.php'); ?>
