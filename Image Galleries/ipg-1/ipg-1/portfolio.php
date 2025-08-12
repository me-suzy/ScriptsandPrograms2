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
include("./includes/functions/portfolios.php");

db_connect();

if(private_cat($_GET['cat_id'])){ redirect('index.php'); }
	
$catName = catId2CatName($_GET['cat_id']);

if(!strlen($catName)){
    redirect('index.php');
}

$DOC_TITLE = SITE_NAME . ' - ' . $catName;

$sql = "SELECT i.image, i.public_view FROM " . PDB_PREFIX . "images i,
		" . PDB_PREFIX . "images_to_categories i2c  WHERE i.id = i2c.image_id AND i2c.cat_id = " . $_GET['cat_id'] . "
		ORDER BY i2c.display_order ASC LIMIT 0,1";

$resultCatImage = db_query($sql);
$rowCatImage = db_fetch_array($resultCatImage);
$catImage = $rowCatImage['image'];

$sql = "SELECT * FROM " . PDB_PREFIX . "user_text ut WHERE content_cat = " . $_GET['cat_id'];
$resultUserText = db_query($sql);
$rowUserText = db_fetch_array($resultUserText);

include('templates/header.php');
?> 
<div class="portfolio_main_area"> 
  <div class="portfolio_category_title"> 
    <?php print $catName; ?>
  </div>
  <?php if(strlen($catImage) > 0) { print '<div class="portfolio_showcase_image_block"><img class="portfolio_showcase_image"  src="' . PORTFOLIO_IMAGE_URL . '/' . $catImage . '"></div>'; }?>
</div>
<?php if(strlen($rowUserText['title'])) { print '<div class="portfolio_text_title">' . $rowUserText['title'] . '</div>'; } ?>
<?php if(strlen($rowUserText['text_content'])) { print '<div class="portfolio_text">' . $rowUserText['text_content'] . '</div>'; }?>
<?php if(strlen($catName) > 0) { print '<div style="clear: both;"></div><div class="portfolio_category_name">' . $catName . '</div>'; }?>
<?php print '<div class="portfolio_table">' . display_portfolio($_GET['cat_id'], $icl, $show_restricted, MAX_PORTFOLIO_THUMBNAIL_COLUMNS,THUMBNAIL_INFO_POS) . '</div>'; ?>
<?php
include('templates/footer.php'); ?>
