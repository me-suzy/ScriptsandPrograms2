<?php

// $Id: modify.php 116 2005-09-16 21:20:22Z stefan $

/*

 Website Baker Project <http://www.websitebaker.org/>
 Copyright (C) 2004-2005, Ryan Djurovich

 Website Baker is free software; you can redistribute it and/or modify
 it under the terms of the GNU General Public License as published by
 the Free Software Foundation; either version 2 of the License, or
 (at your option) any later version.

 Website Baker is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.

 You should have received a copy of the GNU General Public License
 along with Website Baker; if not, write to the Free Software
 Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA

*/

// Setup template object
$template = new Template(WB_PATH.'/modules/menu_link');
$template->set_file('page', 'modify.html');
$template->set_block('page', 'main_block', 'main');

// Get page link and target
$query_info = "SELECT link,target FROM ".TABLE_PREFIX."pages WHERE page_id = '$page_id'";
$get_info = $database->query($query_info);
$fetch_info = $get_info->fetchRow();
$link = ($fetch_info['link']);
$target = $fetch_info['target'];

// Insert vars
$template->set_var(array(
								'PAGE_ID' => $page_id,
								'WB_URL' => WB_URL,
								'LINK' => $link,
								'TEXT_LINK' => $TEXT['LINK'],
								'TEXT_TARGET' => $TEXT['TARGET'],
								'TEXT_NEW_WINDOW' => $TEXT['NEW_WINDOW'],
								'TEXT_SAME_WINDOW' => $TEXT['SAME_WINDOW'],
								'TEXT_SAVE' => $TEXT['SAVE'],
								'TEXT_CANCEL' => $TEXT['CANCEL'],
								'TEXT_PLEASE_SELECT' => $TEXT['PLEASE_SELECT']
								)
						);

// Select target
if($target == '_blank') {
	$template->set_var('BLANK_SELECTED', ' selected');
} elseif($target == '_top') {
	$template->set_var('TOP_SELECTED', ' selected');
}

// Parse template object
$template->parse('main', 'main_block', false);
$template->pparse('output', 'page');

?>