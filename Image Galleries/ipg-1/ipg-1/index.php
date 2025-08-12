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

include('./includes/config.php');
include('./includes/functions/fns_db.php');
include('./includes/settings.php');
include('./includes/functions/fns_std.php');
include('./includes/functions/portfolios.php');

$DOC_TITLE = SITE_NAME;

db_connect();
$sql = "SELECT * FROM " . PDB_PREFIX . "content WHERE id = 1";
$result = db_query($sql);
$row = db_fetch_array($result);

if(strlen($row['title_tag'])){
	$DOC_TITLE = $row['title_tag'];
} else {
	$DOC_TITLE = $row['title'];
}

include('./templates/header.php'); ?>

<?php if(strlen($row['body'])) { print '<div class="portfolio_text">' . $row['body'] . '</div>'; }?>
<?php if(strlen($catName) > 0) { print '<div style="clear: both;"></div><div class="portfolio_category_name">' . $catName . '</div>'; }?>
<?php print '<div class="portfolio_table">' . display_portfolio_categories(MAX_CATEGORY_THUMBNAIL_COLUMNS) . '</div>'; ?>

<?php include('./templates/footer.php');  ?>