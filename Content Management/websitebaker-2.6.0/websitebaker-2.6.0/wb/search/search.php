<?php

// $Id: search.php 240 2005-11-23 15:17:50Z stefan $

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

if(!defined('WB_URL')) { header('Location: index.php'); }

// Check if search is enabled
if(SHOW_SEARCH != true) {
	echo $TEXT['SEARCH'].' '.$TEXT['DISABLED'];
} else {
	
	// Make pages_listed and items_listed blank arrays
	$pages_listed = array();
	$items_listed = array();

	// Get search string
	if(isset($_REQUEST['string'])) {
		if ($_REQUEST['match']!='exact') {
			$string=str_replace(',', '', $_REQUEST['string']);
		} else {
			$string=$_REQUEST['string'];
		}
		// reverse potential magic_quotes action
		$original_string=$wb->strip_slashes($string);
		// Double backslashes (mySQL needs doubly escaped backslashes in LIKE comparisons)
		$string = addslashes($wb->escape_backslashes($original_string));
		// then escape for mySQL query
		$search_string = htmlspecialchars($original_string,ENT_QUOTES);
	} else {
		$string = '';
		$search_string = '';
	}
	
	// Work-out what to do (match all words, any words, or do exact match), and do relevant with query settings
	$all_checked = '';
	$any_checked = '';
	$exact_checked = '';
	if($_REQUEST['match'] != 'exact') {
		// Split string into array with explode() function
		$exploded_string = explode(' ', $string);
		// Make sure there is no blank values in the array
		$string = array();
		foreach($exploded_string AS $each_exploded_string) {
			if($each_exploded_string != '') {
				$string[] = $each_exploded_string;
			}
		}
		if ($_REQUEST['match'] == 'any') {
			$any_checked = ' checked';
			$logical_operator = ' OR';
		} else {
			$all_checked = ' checked';
			$logical_operator = ' AND';
		}
	} else {
		$exact_checked = ' checked';
		$exact_string=$string;
		$string=array();
		$string[]=$exact_string;
	}	
	// Get list of usernames and display names
	$query_users = $database->query("SELECT user_id,username,display_name FROM ".TABLE_PREFIX."users");
	$users = array('0' => array('display_name' => $TEXT['UNKNOWN'], 'username' => strtolower($TEXT['UNKNOWN'])));
	if($query_users->numRows() > 0) {
		while($user = $query_users->fetchRow()) {
			$users[$user['user_id']] = array('display_name' => $user['display_name'], 'username' => $user['username']);
		}
	}
	
	// Get search settings
	$query_header = $database->query("SELECT value FROM ".TABLE_PREFIX."search WHERE name = 'header' LIMIT 1");
	$fetch_header = $query_header->fetchRow();
	$query_footer = $database->query("SELECT value FROM ".TABLE_PREFIX."search WHERE name = 'footer' LIMIT 1");
	$fetch_footer = $query_footer->fetchRow();
	$query_results_header = $database->query("SELECT value FROM ".TABLE_PREFIX."search WHERE name = 'results_header' LIMIT 1");
	$fetch_results_header = $query_results_header->fetchRow();
	$query_results_footer = $database->query("SELECT value FROM ".TABLE_PREFIX."search WHERE name = 'results_footer' LIMIT 1");
	$fetch_results_footer = $query_results_footer->fetchRow();
	$query_results_loop = $database->query("SELECT value FROM ".TABLE_PREFIX."search WHERE name = 'results_loop' LIMIT 1");
	$fetch_results_loop = $query_results_loop->fetchRow();
	$query_no_results = $database->query("SELECT value FROM ".TABLE_PREFIX."search WHERE name = 'no_results' LIMIT 1");
	$fetch_no_results = $query_no_results->fetchRow();
	
	// Replace vars in search settings with values
	$vars = array('[SEARCH_STRING]', '[WB_URL]', '[PAGE_EXTENSION]', '[TEXT_RESULTS_FOR]');
	$values = array($search_string, WB_URL, PAGE_EXTENSION, $TEXT['RESULTS_FOR']);
	$search_footer = str_replace($vars, $values, ($fetch_footer['value']));
	$search_results_header = str_replace($vars, $values, ($fetch_results_header['value']));
	$search_results_footer = str_replace($vars, $values, ($fetch_results_footer['value']));
	// Do extra vars/values replacement
	$vars = array('[SEARCH_STRING]', '[WB_URL]', '[PAGE_EXTENSION]', '[TEXT_SEARCH]', '[TEXT_ALL_WORDS]', '[TEXT_ANY_WORDS]', '[TEXT_EXACT_MATCH]', '[TEXT_MATCH]', '[TEXT_MATCHING]', '[ALL_CHECKED]', '[ANY_CHECKED]', '[EXACT_CHECKED]');
	$values = array($search_string, WB_URL, PAGE_EXTENSION, $TEXT['SEARCH'], $TEXT['ALL_WORDS'], $TEXT['ANY_WORDS'], $TEXT['EXACT_MATCH'], $TEXT['MATCH'], $TEXT['MATCHING'], $all_checked, $any_checked, $exact_checked);
	$search_header = str_replace($vars, $values, ($fetch_header['value']));
	
	// Show search header
	echo $search_header;
	
	// Work-out if the user has already entered their details or not
	if($string != '' AND $string != ' ' AND $string != '  ' AND $string != array()) {
		
		// Show search results_header
		echo $search_results_header;
		// Search page details only, such as description, keywords, etc.
			$query_pages = "SELECT page_id, page_title, menu_title, link, description, modified_when, modified_by FROM ".TABLE_PREFIX."pages WHERE ";
			$count = 0;
			foreach($string AS $each_string) {
				if($count != 0) { $query_pages .= $logical_operator; }
				$query_pages .= " visibility != 'none' AND page_title LIKE '%$each_string%' AND searching = '1'".
				" OR visibility != 'none' AND visibility != 'deleted' AND menu_title LIKE '%$each_string%' AND searching = '1'".
				" OR visibility != 'none' AND visibility != 'deleted' AND description LIKE '%$each_string%' AND searching = '1'".
				" OR visibility != 'none' AND visibility != 'deleted' AND keywords LIKE '%$each_string%' AND searching = '1'";
				$count = $count+1;
			}
			$query_pages = $database->query($query_pages);
		// Loop through pages
		if($query_pages->numRows() > 0) {
			while($page = $query_pages->fetchRow()) {
				// Get page link
				$link = page_link($page['link']);
				// Set vars to be replaced by values
				$vars = array('[LINK]', '[TITLE]', '[DESCRIPTION]', '[USERNAME]','[DISPLAY_NAME]','[DATE]','[TIME]','[TEXT_LAST_UPDATED_BY]','[TEXT_ON]');
				if($page['modified_when'] > 0) {
					$date = gmdate(DATE_FORMAT, $page['modified_when']+TIMEZONE);
					$time = gmdate(TIME_FORMAT, $page['modified_when']+TIMEZONE);
				} else {
					$date = $TEXT['UNKNOWN'].' '.$TEXT['DATE'];
					$time = $TEXT['UNKNOWN'].' '.$TEXT['TIME'];
				}
				$values = array($link, ($page['page_title']),($page['description']), $users[$page['modified_by']]['username'], $users[$page['modified_by']]['display_name'], $date, $time, $TEXT['LAST_UPDATED_BY'], strtolower($TEXT['ON']));
				// Show loop code with vars replaced by values
				if($values != array()) {
					echo str_replace($vars, $values, ($fetch_results_loop['value']));
				}
				// Say that we have already listed this page id
				$pages_listed[$page['page_id']] = true;
				// Set values to blank
				$value = array();
			}
		}
		// Get modules that have registered for custom query's to be conducted
		$get_modules = $database->query("SELECT value,extra FROM ".TABLE_PREFIX."search WHERE name = 'module'");
		// Loop through each module
		if($get_modules->numRows() > 0) {
			while($module = $get_modules->fetchRow()) {
				// Get module name
				$module_name = $module['value'];
				// Get fields to use for title, link, etc.
				$fields = unserialize($module['extra']);
				// Get query start
				$get_query_start = $database->query("SELECT value FROM ".TABLE_PREFIX."search WHERE name = 'query_start' AND extra = '$module_name' LIMIT 1");
				if($get_query_start->numRows() > 0) {
					// Fetch query start
					$fetch_query_start = $get_query_start->fetchRow();
					// Prepare query start for execution by replacing {TP} with the TABLE_PREFIX
					$query_start = str_replace('[TP]', TABLE_PREFIX, ($fetch_query_start['value']));
					// Get query end
					$get_query_end = $database->query("SELECT value FROM ".TABLE_PREFIX."search WHERE name = 'query_end' AND extra = '$module_name' LIMIT 1");
					if($get_query_end->numRows() > 0) {
						// Fetch query start
						$fetch_query_end = $get_query_end->fetchRow();
						// Set query end
						$query_end = ($fetch_query_end['value']);
						// Get query body
						$get_query_body = $database->query("SELECT value FROM ".TABLE_PREFIX."search WHERE name = 'query_body' AND extra = '$module_name' LIMIT 1");
						if($get_query_body->numRows() > 0) {
							// Fetch query start
							$fetch_query_body = $get_query_body->fetchRow();
							// Prepare query body for execution by replacing {STRING} with the correct one
							$query_body = str_replace(array('[TP]','[O]','[W]'), array(TABLE_PREFIX,'LIKE','%'), ($fetch_query_body['value']));
							// Loop through query body for each string, then combine with start and end
							$prepared_query = $query_start;
							$count = 0;
							foreach($string AS $each_string) {
								if($count != 0) { $prepared_query .= $logical_operator; }
								$prepared_query .= str_replace('[STRING]', $each_string, $query_body);
								$count = $count+1;
							}
							$prepared_query .= $query_end;
							// Execute query
							$query = $database->query($prepared_query);
							// Loop though queried items
							if($query->numRows() > 0) {
								while($page = $query->fetchRow()) {
									// Only show this page if it hasn't already been list
									if(!isset($fields['page_id']) OR !isset($pages_listed[$page[$fields['page_id']]])) {
										// Get page link
										$link = page_link($page[$fields['link']]);
										// Set vars to be replaced by values
										$vars = array('[LINK]', '[TITLE]', '[DESCRIPTION]', '[USERNAME]','[DISPLAY_NAME]','[DATE]','[TIME]','[TEXT_LAST_UPDATED_BY]','[TEXT_ON]');
										if($page[$fields['modified_when']] > 0) {
											$date = gmdate(DATE_FORMAT, $page[$fields['modified_when']]+TIMEZONE);
											$time = gmdate(TIME_FORMAT, $page[$fields['modified_when']]+TIMEZONE);
										} else {
											$date = $TEXT['UNKNOWN'].' '.$TEXT['DATE'];
											$time = $TEXT['UNKNOWN'].' '.$TEXT['TIME'];
										}
										$values = array($link, ($page[$fields['title']]), ($page[$fields['description']]), $users[$page[$fields['modified_by']]]['username'], $users[$page[$fields['modified_by']]]['display_name'], $date, $time, $TEXT['LAST_UPDATED_BY'], strtolower($TEXT['ON']));
										// Show loop code with vars replaced by values
										echo str_replace($vars, $values, ($fetch_results_loop['value']));
										// Say that this page or item has been listed if we can
										if(isset($fields['page_id'])) {
											$pages_listed[$page[$fields['page_id']]] = true;
										} elseif(isset($fields['item_id'])) {
											$items_listed[$page[$fields['item_id']]] = true;
										}
									}
								}
							}
						
						}
					}
				}
			}
			
			// Show search results_footer
			echo $search_results_footer;
			
		}
	
	// Say no items found if we should
	if($pages_listed == array() AND $items_listed == array()) {
		echo $fetch_no_results['value'];
	}
		
	}
	
	// Show search footer
	echo $search_footer;
	
}

?>