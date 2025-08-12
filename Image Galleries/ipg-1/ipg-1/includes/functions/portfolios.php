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

function display_portfolio_categories($cols) {
	$sql = "SELECT * FROM " . PDB_PREFIX . "categories c ORDER BY cat_name ASC";
	db_connect();
	//find categories with images in them
	$sql1 = "SELECT i2c.cat_id, c.cat_name FROM " . PDB_PREFIX . "images i,
	" . PDB_PREFIX . "images_to_categories i2c, " . PDB_PREFIX . "categories c  WHERE i.id = i2c.image_id AND c.private = 0
	AND c.id = i2c.cat_id GROUP BY cat_id ORDER BY cat_name";
	$result1 = db_query($sql1);
	//if there is a result start table and first row
	if(db_num_rows($result1)){
		 $str = '<table align="center" cellpadding="10">' . "\r\n" . '<tr>' . "\r\n";
		//while there are still categories get image for each
		$col = 1;
		while ($row1 = db_fetch_array($result1)){ 
			$sql2 = "SELECT i.image FROM " . PDB_PREFIX . "images i,
					" . PDB_PREFIX . "images_to_categories i2c  WHERE i.id = i2c.image_id AND i2c.cat_id = " . $row1['cat_id'] . "
					ORDER BY i2c.display_order ASC LIMIT 0,1";
			$result2 = db_query($sql2);
			$row2 = db_fetch_array($result2);
			$str .= '<td valign="bottom" class="portfolio_thumbnail_cell"><a href="portfolio.php?cat_id=' . $row1['cat_id'] . '">
					<img class="portfolio_thumbnail" src="' . PORTFOLIO_IMAGE_URL . '/thumbs/' . $row2['image'] . '"><div class="portfolio_thumbnail_cat_label">' . $row1['cat_name'] . '</div></a></td>';			
			if($col >= $cols) {
				$str .='</tr><tr>';
				$col = 1;
			} else {
				$col++;
			}
		}//end while rows
		$str .= '</tr></table>';
		return $str;
	}//end if rows
}//end function

function delete_image($path, $file){
	if(unlink($path . $file)){
		return true;
	}else {
		return false;	
	}
}

function display_portfolio($cat, $image_credit_label, $show_restricted, $cols,$info_pos="0") {
	$sql = "SELECT i.id, i.comment_requested, i.public_view, i.title, i.caption, 
		i.image, i.id as image_id, ir.times_clicked, i.view_counter, COUNT(pic.id) AS num_comments 
		FROM ((" . PDB_PREFIX . "images i LEFT JOIN " . PDB_PREFIX . "image_ratings ir ON i.id = ir.id) LEFT JOIN " . PDB_PREFIX . "image_comments pic ON i.id = pic.image_id),
		" . PDB_PREFIX . "images_to_categories i2c  WHERE i.id = i2c.image_id AND i2c.cat_id = " . $cat . "
		GROUP BY i.id ORDER BY display_order ASC";
	$result = db_query($sql);

	if(db_num_rows($result) > 0){
		$str = '<table align="center" cellpadding="10">' . "\r\n" . '<tr>' . "\r\n";
		$col = 1;
		for($i=0; $i < db_num_rows($result); $i++, $col++){
			$rowData = db_fetch_array($result);
			if(intval($rowData['view_counter']) > intval($rowData['times_clicked'])) { $rowData['times_clicked'] = $rowData['view_counter']; }
			$url = 'uid=' . $userData['id']  . '&id=' . urlencode(urlencode($rowData['id'])) . '&title=' . urlencode(urlencode($rowData['title'])) . '&caption=' . urlencode(urlencode($rowData['caption'])) . '&date_taken=' . urlencode(urlencode($rowData['date_taken'])) . '&photographer=' . urlencode(urlencode($userData['professional_name'])) . '&image=' . PORTFOLIO_IMAGE_URL . $userData['portfolio_path'] . '/' . $rowData['image'];

			if($rowData['public_view'] == '0' && !$show_restricted) { 
				$strImageLink = '<img src="images/no_view_thumbnail.gif">';
			} else {
				$strImageLink = '<a href="javascript: styleWindow(\'' . SITE_URL . APP_DIR . 'portfolio_photo_popup.php?' . $url . '\')">
				<img class="portfolio_thumbnail" src="' . PORTFOLIO_IMAGE_URL . '/thumbs/' . $rowData['image'] . '"></a>';
			}

			$str .= '<td class="portfolio_thumbnail_cell">';
			if(SHOW_TIMES_VIEWED) { $strTimesViewed = '<br>Times Viewed: ' . stripSlashes($rowData['times_clicked']); }
			if(strlen(stripSlashes($rowData['caption']))) { $strCaption = '<br>Caption: ' . stripSlashes($rowData['caption']); }
			if(strlen(stripSlashes($rowData['title']))) { $strTitle = '<br>Title: ' . stripSlashes($rowData['title']); }	
			if($info_pos == 0){ //don't show
				$str .= $strImageLink;
			}
			
			if($info_pos == 1){ //right side
				$str .= $strImageLink . '</td>
				<td class="portfolio_thumbnail_details_cell"><div class="portfolio_image_info">' . $strTitle . $caption .  $strTimesViewed  . '</div>';
			}
			
			if($info_pos == 2){ //bottom
				$str .= $strImageLink . '<div class="portfolio_image_info">' . $strTitle . $strCaption .  $strTimesViewed  . '</div>';	
			}

			//empty the vars
			$strTimesViewed = '';
			$strCaption = '';
			$strTitle = '';
	
			if($col >= $cols) {
				$str .='</td></tr><tr>';
				$col = 0;
			} else {
				  if($i >= db_num_rows($result)-1) {
					$str .= '</td></tr>';
				  } else {
					$str .= '</td>';
				  }
			}
		}//end for 
	$str .= '</table>';
	return str_replace('<tr></table>', '</table>', $str);
	}//end if num_rows > 0
	return false;
}//end function

?>