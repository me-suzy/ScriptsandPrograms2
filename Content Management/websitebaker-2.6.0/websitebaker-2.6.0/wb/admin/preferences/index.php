<?php

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

require('../../config.php');
require_once(WB_PATH.'/framework/class.admin.php');
$admin = new admin('Preferences');

// Create new template object for the preferences form
$template = new Template(ADMIN_PATH.'/preferences');
$template->set_file('page', 'template.html');
$template->set_block('page', 'main_block', 'main');

// Get existing value from database
$database = new database();
$query = "SELECT display_name,email FROM ".TABLE_PREFIX."users WHERE user_id = '".$admin->get_user_id()."'";
$results = $database->query($query);
if($database->is_error()) {
	$admin->print_error($database->get_error(), 'index.php');
}
$details = $results->fetchRow();

// Insert values into form
$template->set_var('DISPLAY_NAME', $details['display_name']);
$template->set_var('EMAIL', $details['email']);

// Insert language values
$template->set_block('main_block', 'language_list_block', 'language_list');
$result = $database->query("SELECT * FROM ".TABLE_PREFIX."addons WHERE type = 'language'");
if($result->numRows() > 0) {
	while($addon = $result->fetchRow()) {
		// Insert code and name
		$template->set_var(array(
								'CODE' => $addon['directory'],
								'NAME' => $addon['name']
								));
		// Check if it is selected
		if(LANGUAGE == $addon['directory']) {
			$template->set_var('SELECTED', ' selected');
		} else {
			$template->set_var('SELECTED', '');
		}
		$template->parse('language_list', 'language_list_block', true);
	}
}

// Insert default timezone values
require(ADMIN_PATH.'/interface/timezones.php');
$template->set_block('main_block', 'timezone_list_block', 'timezone_list');
foreach($TIMEZONES AS $hour_offset => $title) {
	$template->set_var('VALUE', $hour_offset);
	$template->set_var('NAME', $title);
	if($admin->get_timezone() == $hour_offset*60*60) {
		$template->set_var('SELECTED', 'selected');
	} else {
		$template->set_var('SELECTED', '');
	}
	$template->parse('timezone_list', 'timezone_list_block', true);
}

// Insert date format list
$user_time = true;
require(ADMIN_PATH.'/interface/date_formats.php');
$template->set_block('main_block', 'date_format_list_block', 'date_format_list');
foreach($DATE_FORMATS AS $format => $title) {
	$format = str_replace('|', ' ', $format); // Add's white-spaces (not able to be stored in array key)
	if($format != 'system_default') {
		$template->set_var('VALUE', $format);
	} else {
		$template->set_var('VALUE', '');
	}
	$template->set_var('NAME', $title);
	if(DATE_FORMAT == $format AND !isset($_SESSION['USE_DEFAULT_DATE_FORMAT'])) {
		$template->set_var('SELECTED', 'selected');
	} elseif($format == 'system_default' AND isset($_SESSION['USE_DEFAULT_DATE_FORMAT'])) {
		$template->set_var('SELECTED', 'selected');
	} else {
		$template->set_var('SELECTED', '');
	}
	$template->parse('date_format_list', 'date_format_list_block', true);
}

// Insert time format list
require(ADMIN_PATH.'/interface/time_formats.php');
$template->set_block('main_block', 'time_format_list_block', 'time_format_list');
foreach($TIME_FORMATS AS $format => $title) {
	$format = str_replace('|', ' ', $format); // Add's white-spaces (not able to be stored in array key)
	if($format != 'system_default') {
		$template->set_var('VALUE', $format);
	} else {
		$template->set_var('VALUE', '');
	}
	$template->set_var('NAME', $title);
	if(TIME_FORMAT == $format AND !isset($_SESSION['USE_DEFAULT_TIME_FORMAT'])) {
		$template->set_var('SELECTED', 'selected');
	} elseif($format == 'system_default' AND isset($_SESSION['USE_DEFAULT_TIME_FORMAT'])) {
		$template->set_var('SELECTED', 'selected');
	} else {
		$template->set_var('SELECTED', '');
	}
	$template->parse('time_format_list', 'time_format_list_block', true);
}

// Insert language headings
$template->set_var(array(
								'HEADING_MY_SETTINGS' => $HEADING['MY_SETTINGS'],
								'HEADING_MY_EMAIL' => $HEADING['MY_EMAIL'],
								'HEADING_MY_PASSWORD' => $HEADING['MY_PASSWORD']
								)
						);
// Insert language text and messages
$template->set_var(array(
								'TEXT_SAVE' => $TEXT['SAVE'],
								'TEXT_RESET' => $TEXT['RESET'],
								'TEXT_DISPLAY_NAME' => $TEXT['DISPLAY_NAME'],
								'TEXT_EMAIL' => $TEXT['EMAIL'],
								'TEXT_LANGUAGE' => $TEXT['LANGUAGE'],
								'TEXT_TIMEZONE' => $TEXT['TIMEZONE'],
								'TEXT_DATE_FORMAT' => $TEXT['DATE_FORMAT'],
								'TEXT_TIME_FORMAT' => $TEXT['TIME_FORMAT'],
								'TEXT_CURRENT_PASSWORD' => $TEXT['CURRENT_PASSWORD'],
								'TEXT_NEW_PASSWORD' => $TEXT['NEW_PASSWORD'],
								'TEXT_RETYPE_NEW_PASSWORD' => $TEXT['RETYPE_NEW_PASSWORD']
								)
						);

// Parse template for preferences form
$template->parse('main', 'main_block', false);
$template->pparse('output', 'page');

$admin->print_footer();

?>