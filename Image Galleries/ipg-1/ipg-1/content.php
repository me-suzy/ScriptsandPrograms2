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

include("./includes/config.php");
include("./includes/functions/fns_std.php");
include("./includes/functions/fns_db.php");
include('./includes/settings.php');

db_connect();
$sql = "SELECT * FROM " . PDB_PREFIX . "content WHERE id = " . $_GET['cid'];
$result = db_query($sql);
$row = db_fetch_array($result);

if(strlen($row['title_tag'])){
	$DOC_TITLE = $row['title_tag'];
} else {
	$DOC_TITLE = $row['title'];
}

include('templates/header.php');
?> 
<div class="standard_content_area"> 
  <div class="content_heading">
    <?php print $row['title']; ?>
  </div>
  <?php print '<div class="content_body">' . $row['body'] . '</div>'; ?>
</div>
<?php
include('templates/footer.php');
?>
