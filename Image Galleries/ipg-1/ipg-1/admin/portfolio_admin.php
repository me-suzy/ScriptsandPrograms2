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

$DOC_TITLE = PORTFOLIO_LABEL . " Administration";

if(!$_SESSION['admin']){  redirect('../login.php'); }

db_connect();

if($_GET['action'] == 'delete_cat'){  delete_category($_GET['cat_id']); }
if($_GET['action'] == 'delete_content'){  delete_content($_GET['cid']); }
if($_POST['action'] == 'toggle_private'){
    $sql = "UPDATE " . PDB_PREFIX . "categories SET private = IF(private = 1, 0, 1) WHERE id = " . $_POST['cat_id'];
	db_query($sql);
}

include('./templates/header.php');


?> 
<table width="100%" border="0" bordercolor="black" cellspacing="0" cellpadding="5">
  <tr> 
    <td valign="top"> 
      <div class="admin_section_title"> 
        <p><br>
          <?php print PORTFOLIO_LABEL; ?>
          Administration</p>
        <p><span class="admin_error_mark"> 
          <?php print $msg ?>
          </span> </p>
      </div>
      <p>Select one of the options below to update your 
        <?php print PORTFOLIO_LABEL; ?>
        .</p>
      <p><a href="portfolio_create_category.php?action=create_cat">Create New 
        Category</a>&nbsp;|&nbsp;<a href="create_content_page.php">Create New 
        Content Page</a>&nbsp;|&nbsp;<a href="portfolio_config.php">Configuration 
        Settings</a></p>
      <table width="95%" border="1" cellspacing="0" cellpadding="3">
        <tr class="admin_table_header"> 
          <td>Category</td>
          <td>Category Actions</td>
          <td>Images</td>
          <td nowrap>Text Area<img onmouseover="this.T_DELAY=0;this.T_WIDTH=200;this.T_FONTCOLOR='#003399';return escape('You can place additional text and information in each gallery page.')" align="textmiddle" src="../images/question_icon_reversed.gif" width="15" height="15"></td>
        </tr>
        <?php
$sql = "SELECT * FROM " . PDB_PREFIX . "categories c ORDER BY cat_name ASC";
$sql1 = "SELECT COUNT(i2c.image_id) AS image_count, i2c.cat_id FROM " . PDB_PREFIX . "images i 
		LEFT JOIN " . PDB_PREFIX . "images_to_categories i2c ON (i.id = i2c.image_id) 
		GROUP BY cat_id";
$result = db_query($sql);
$result1 = db_query($sql1);
while ($row1 = db_fetch_array($result1)){
	$ImageCount[$row1['cat_id']] = $row1['image_count'];
}
while($row = db_fetch_array($result)){		
  $str .= '<form name="form1" method="POST" action="' . $PHP_SELF . '"><tr>
          <td class="admin_cat_list_col"><a target="_blank" href="../'; if($row['private']){ $str .= 'private'; } else { $str .= 'portfolio'; } $str .= '.php?cat_id=' . $row['id'] . '">' . $row['cat_name'] . '</a></td><td class="admin_manage_links"><a  href="portfolio_edit_category.php?action=edit_cat&cat_id=' . $row['id'] . '">[edit category]</a>&nbsp;&nbsp;<a onClick="javascript:return confirmCategoryDelete();" href="' . $PHP_SELF . '?action=delete_cat&cat_id=' . $row['id'] . '">[delete category]</a>
		  &nbsp;&nbsp;<input type="hidden" name="action" value="toggle_private"> <input type="hidden" name="cat_id" value="' . $row['id'] . '">';
		  if($row['private']){ $str .= '<input type="image" border="0" src="../images/private_icon.gif">'; } else { $str .= '<input type="image" border="0" src="../images/public_icon.gif">'; }
   $str .= '</a></td>
          <td class="admin_manage_links"><a href="./portfolio_photos_post.php?cat_id=' . $row['id'] . '">';

  if($ImageCount[$row['id']]) { $str .= "Manage Images -> "; } else { $str .=  "Add Images"; } 
  
  $str .= $ImageCount[$row['id']] . '</a></td>
          <td class="admin_manage_links"><a href="./portfolio_text_post.php?cat_id=' . $row['id'] . '">Edit Text</a></td>
        </tr></form>';
} //end while   

print $strFrontPage . $str; ?>
      </table>
      <br>
      <div class="admin_section_title">EDIT CONTENT PAGES</div>
      <br>
      <table border="1" cellpadding="5" cellspacing="0" width="232">
        <tr class="admin_table_header">
          <td>Edit Page</td>
          <td>Action</td>
          <?php
			$sql = "SELECT id, title FROM " . PDB_PREFIX . "content ORDER BY id";
			$resultContent = db_query($sql);
			while($rowContent = db_fetch_array($resultContent)){
		  ?>
        <tr>
          <td class="admin_manage_links"> <a href="content_post.php?cid=<?php print $rowContent['id']; ?>">
            <?php print $rowContent['title']; ?>
            </a> </td>
          <td class="admin_manage_links">
		  <?php if($rowContent['id'] != '1') { ?>
		  <a onClick="javascript:return confirmContentDelete();" href="<?php print $PHP_SELF ?>?action=delete_content&cid=<?php print $rowContent['id'] ?>">[delete 
            page]</a>
		  <?php }//end if ?>&nbsp;</td>
        </tr>
        <?php }//end while ?>
      </table>
    </td>
  </tr>
</table>

<?php include('./templates/footer.php'); ?>
