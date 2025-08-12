<?php

// $Id: functions.php 249 2005-11-26 08:33:10Z stefan $

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

/*

Website Baker functions file
This file contains general functions used in Website Baker

*/

// Stop this file from being accessed directly
if(!defined('WB_URL')) {
	header('Location: ../index.php');
}

// Define that this file has been loaded
define('FUNCTIONS_FILE_LOADED', true);

// Function to remove a non-empty directory
function rm_full_dir($directory)
{
    // If suplied dirname is a file then unlink it
    if (is_file($directory)) {
        return unlink($directory);
    }

    // Empty the folder
    $dir = dir($directory);
    while (false !== $entry = $dir->read()) {
        // Skip pointers
        if ($entry == '.' || $entry == '..') {
            continue;
        }

        // Deep delete directories      
        if (is_dir("$directory/$entry")) {
            rm_full_dir("$directory/$entry");
        } else {
            unlink("$directory/$entry");
        }
    }

    // Now delete the folder
    $dir->close();
    return rmdir($directory);
}

// Function to open a directory and add to a dir list
function directory_list($directory) {
	
	$list = array();

	// Open the directory then loop through its contents
	$dir = dir($directory);
	while (false !== $entry = $dir->read()) {
		// Skip pointers
		if(substr($entry, 0, 1) == '.' || $entry == '.svn') {
			continue;
		}
		// Add dir and contents to list
		if (is_dir("$directory/$entry")) {
			$list = array_merge($list, directory_list("$directory/$entry"));
			$list[] = "$directory/$entry";
		}
	}

	// Now return the list
	return $list;
}

// Function to open a directory and add to a dir list
function chmod_directory_contents($directory, $file_mode) {
	
	// Set the umask to 0
	$umask = umask(0);
	
	// Open the directory then loop through its contents
	$dir = dir($directory);
	while (false !== $entry = $dir->read()) {
		// Skip pointers
		if(substr($entry, 0, 1) == '.' || $entry == '.svn') {
			continue;
		}
		// Chmod the sub-dirs contents
		if(is_dir("$directory/$entry")) {
			chmod_directory_contents("$directory/$entry", $file_mode);
		}
		change_mode($directory.'/'.$entry, 'file');
	}
	
	// Restore the umask
	umask($umask);

}

// Function to open a directory and add to a file list
function file_list($directory, $skip = array()) {
	
	$list = array();
	$skip_file = false;
	
	// Open the directory then loop through its contents
	$dir = dir($directory);
	while (false !== $entry = $dir->read()) {
		// Skip pointers
		if($entry == '.' || $entry == '..') {
			$skip_file = true;
		}
		// Check if we to skip anything else
		if($skip != array()) {
			foreach($skip AS $skip_name) {
				if($entry == $skip_name) {
					$skip_file = true;
				}
			}
		}
		// Add dir and contents to list
		if($skip_file != true AND is_file("$directory/$entry")) {
			$list[] = "$directory/$entry";
		}
		
		// Reset the skip file var
		$skip_file = false;
	}

	// Now delete the folder
	return $list;
}

// Function to get a list of home folders not to show
function get_home_folders() {
	global $database, $admin;
	$home_folders = array();
	// Only return home folders is this feature is enabled
	if(HOME_FOLDERS) {
		$query_home_folders = $database->query("SELECT home_folder FROM ".TABLE_PREFIX."users WHERE home_folder != '".$admin->get_home_folder()."'");
		if($query_home_folders->numRows() > 0) {
			while($folder = $query_home_folders->fetchRow()) {
				$home_folders[$folder['home_folder']] = $folder['home_folder'];
			}
		}
		function remove_home_subs($directory = '/', $home_folders) {
			if($handle = opendir(WB_PATH.MEDIA_DIRECTORY.$directory)) {
				// Loop through the dirs to check the home folders sub-dirs are not shown
			   while(false !== ($file = readdir($handle))) {
					if(substr($file, 0, 1) != '.' AND $file != '.svn' AND $file != 'index.php') {
						if(is_dir(WB_PATH.MEDIA_DIRECTORY.$directory.'/'.$file)) {
							if($directory != '/') { $file = $directory.'/'.$file; } else { $file = '/'.$file; }
							foreach($home_folders AS $hf) {
								$hf_length = strlen($hf);
								if($hf_length > 0) {
									if(substr($file, 0, $hf_length+1) == $hf) {
										$home_folders[$file] = $file;
									}
								}
							}
							$home_folders = remove_home_subs($file, $home_folders);
						}
					}
				}
			}
			return $home_folders;
		}
		$home_folders = remove_home_subs('/', $home_folders);
	}
	return $home_folders;
}

// Function to create directories
function make_dir($dir_name, $dir_mode = OCTAL_DIR_MODE) {
	if(!file_exists($dir_name)) {
		$umask = umask(0);
		mkdir($dir_name, $dir_mode);
		umask($umask);
		return true;
	} else {
		return false;	
	}
}

// Function to chmod files and directories
function change_mode($name) {
	if(OPERATING_SYSTEM != 'windows') {
		// Only chmod if os is not windows
		if(is_dir($name)) {
			$mode = OCTAL_DIR_MODE;
		} else {
			$mode = OCTAL_FILE_MODE;
		}
		if(file_exists($name)) {
			$umask = umask(0);
			chmod($name, $mode);
			umask($umask);
			return true;
		} else {
			return false;	
		}
	} else {
		return true;
	}
}

// Function to figure out if a parent exists
function is_parent($page_id) {
	global $database;
	// Get parent
	$query = $database->query("SELECT parent FROM ".TABLE_PREFIX."pages WHERE page_id = '$page_id'");
	$fetch = $query->fetchRow();
	// If parent isnt 0 return its ID
	if($fetch['parent'] == '0') {
		return false;
	} else {
		return $fetch['parent'];
	}
}

// Function to work out level
function level_count($page_id) {
	global $database;
	// Get page parent
	$query_page = $database->query("SELECT parent FROM ".TABLE_PREFIX."pages WHERE page_id = '$page_id' LIMIT 1");
	$fetch_page = $query_page->fetchRow();
	$parent = $fetch_page['parent'];
	if($parent > 0) {
		// Get the level of the parent
		$query_parent = $database->query("SELECT level FROM ".TABLE_PREFIX."pages WHERE page_id = '$parent' LIMIT 1");
		$fetch_parent = $query_parent->fetchRow();
		$level = $fetch_parent['level'];
		return $level+1;
	} else {
		return 0;
	}
}

// Function to work out root parent
function root_parent($page_id) {
	global $database;
	// Get page details
	$query_page = $database->query("SELECT parent,level FROM ".TABLE_PREFIX."pages WHERE page_id = '$page_id' LIMIT 1");
	$fetch_page = $query_page->fetchRow();
	$parent = $fetch_page['parent'];
	$level = $fetch_page['level'];	
	if($level == 1) {
		return $parent;
	} elseif($parent == 0) {
		return 0;
	} else {
		// Figure out what the root parents id is
		$parent_ids = array_reverse(get_parent_ids($page_id));
		return $parent_ids[0];
	}
}

// Function to get page title
function get_page_title($id) {
	global $database;
	// Get title
	$query = $database->query("SELECT page_title FROM ".TABLE_PREFIX."pages WHERE page_id = '$id'");
	$fetch = $query->fetchRow();
	// Return title
	return $fetch['page_title'];
}

// Function to get a pages menu title
function get_menu_title($id) {
	// Connect to the database
	$database = new database();
	// Get title
	$query = $database->query("SELECT menu_title FROM ".TABLE_PREFIX."pages WHERE page_id = '$id'");
	$fetch = $query->fetchRow();
	// Return title
	return $fetch['menu_title'];
}

// Function to get all parent page titles
function get_parent_titles($parent_id) {
	$titles[] = get_menu_title($parent_id);
	if(is_parent($parent_id) != false) {
		$parent_titles = get_parent_titles(is_parent($parent_id));
		$titles = array_merge($titles, $parent_titles);
	}
	return $titles;
}

// Function to get all parent page id's
function get_parent_ids($parent_id) {
	$ids[] = $parent_id;
	if(is_parent($parent_id) != false) {
		$parent_ids = get_parent_ids(is_parent($parent_id));
		$ids = array_merge($ids, $parent_ids);
	}
	return $ids;
}

// Function to genereate page trail
function get_page_trail($page_id) {
	return implode(',', array_reverse(get_parent_ids($page_id)));
}

// Function to get all sub pages id's
function get_subs($parent, $subs) {
	// Connect to the database
	$database = new database();
	// Get id's
	$query = $database->query("SELECT page_id FROM ".TABLE_PREFIX."pages WHERE parent = '$parent'");
	if($query->numRows() > 0) {
		while($fetch = $query->fetchRow()) {
			$subs[] = $fetch['page_id'];
			// Get subs of this sub
			$subs = get_subs($fetch['page_id'], $subs);
		}
	}
	// Return subs array
	return $subs;
}

// Function to convert a page title to a page filename
function page_filename($string) {
	// First, translate any non-english characters to their english equivalents
	require(WB_PATH.'/framework/convert.php');
   $string = strtr($string, $conversion_array);
	// Now replace spaces with page spcacer
	$string = str_replace(' ', PAGE_SPACER, $string);
	// Now remove all bad characters
	$bad = array(
	'\'', /* /  */ '"', /* " */	'<', /* < */	'>', /* > */
	'{', /* { */	'}', /* } */	'[', /* [ */	']', /* ] */	'`', /* ` */
	'!', /* ! */	'@', /* @ */	'#', /* # */	'$', /* $ */	'%', /* % */
	'^', /* ^ */	'&', /* & */	'*', /* * */	'(', /* ( */	')', /* ) */
	'=', /* = */	'+', /* + */	'|', /* | */	'/', /* / */	'\\', /* \ */
	';', /* ; */	':', /* : */	',', /* , */	'?' /* ? */
	);
	$string = str_replace($bad, '', $string);
	// Now convert to lower-case
	$string = strtolower($string);
	// Now remove multiple page spacers
	$string = str_replace(PAGE_SPACER.PAGE_SPACER, PAGE_SPACER, $string);
	// Clean any page spacers at the end of string
	$string = str_replace(PAGE_SPACER, ' ', $string);
	$string = trim($string);
	$string = str_replace(' ', PAGE_SPACER, $string);
	// If there are any weird language characters, this will protect us against possible problems they could cause
	$string = str_replace(array('%2F', '%'), array('/', ''), urlencode($string));
	// Finally, return the cleaned string
	return $string;
}

// Function to convert a desired media filename to a clean filename
function media_filename($string) {
	// First, translate any non-english characters to their english equivalents
	require(WB_PATH.'/framework/convert.php');
   $string = strtr($string, $conversion_array);
	// Now remove all bad characters
	$bad = array(
	'\'', // '
	'"', // "
	'`', // `
	'!', // !
	'@', // @
	'#', // #
	'$', // $
	'%', // %
	'^', // ^
	'&', // &
	'*', // *
	'=', // =
	'+', // +
	'|', // |
	'/', // /
	'\\', // \
	';', // ;
	':', // :
	',', // ,
	'?' // ?
	);
	$string = str_replace($bad, '', $string);
	// Clean any page spacers at the end of string
	$string = trim($string);
	// Finally, return the cleaned string
	return $string;
}

// Function to work out a page link
if(!function_exists('page_link')) {
	function page_link($link) {
		global $admin;
		return $admin->page_link($link);
	}
}

// Create a new file in the pages directory
function create_access_file($filename,$page_id,$level) {
	global $admin;
	if(!is_writable(WB_PATH.PAGES_DIRECTORY.'/')) {
		$admin->print_error($MESSAGE['PAGES']['CANNOT_CREATE_ACCESS_FILE']);
	} else {
		// First make sure parent folder exists
		$parent_folders = explode('/',str_replace(WB_PATH.PAGES_DIRECTORY, '', dirname($filename)));
		$parents = '';
		foreach($parent_folders AS $parent_folder) {
			if($parent_folder != '/' AND $parent_folder != '') {
				$parents .= '/'.$parent_folder;
				if(!file_exists(WB_PATH.PAGES_DIRECTORY.$parents)) {
					make_dir(WB_PATH.PAGES_DIRECTORY.$parents);
				}
			}	
		}
		// The depth of the page directory in the directory hierarchy
		// '/pages' is at depth 1
		$pages_dir_depth=count(explode('/',PAGES_DIRECTORY))-1;
		// Work-out how many ../'s we need to get to the index page
		$index_location = '';
		for($i = 0; $i < $level + $pages_dir_depth; $i++) {
			$index_location .= '../';
		}
		$content = ''.
'<?php
$page_id = '.$page_id.';
require("'.$index_location.'config.php");
require(WB_PATH."/index.php");
?>';
		$handle = fopen($filename, 'w');
		fwrite($handle, $content);
		fclose($handle);
		// Chmod the file
		change_mode($filename, 'file');
	}
}

// Function for working out a file mime type (if the in-built PHP one is not enabled)
if(!function_exists('mime_content_type')) {
   function mime_content_type($file) {
       $file = escapeshellarg($file);
       return trim(`file -bi $file`);
   }
}

// Generate a thumbnail from an image
function make_thumb($source, $destination, $size) {
	// Check if GD is installed
	if(extension_loaded('gd') AND function_exists('imageCreateFromJpeg')) {
		// First figure out the size of the thumbnail
		list($original_x, $original_y) = getimagesize($source);
		if ($original_x > $original_y) {
			$thumb_w = $size;
			$thumb_h = $original_y*($size/$original_x);
		}
		if ($original_x < $original_y) {
			$thumb_w = $original_x*($size/$original_y);
			$thumb_h = $size;
		}
		if ($original_x == $original_y) {
			$thumb_w = $size;
			$thumb_h = $size;	
		}
		// Now make the thumbnail
		$source = imageCreateFromJpeg($source);
		$dst_img = ImageCreateTrueColor($thumb_w, $thumb_h);
		imagecopyresampled($dst_img,$source,0,0,0,0,$thumb_w,$thumb_h,$original_x,$original_y);
		imagejpeg($dst_img, $destination);
		// Clear memory
		imagedestroy($dst_img);
	   imagedestroy($source);
	   // Return true
	   return true;
   } else {
   	return false;
   }
}

// Function to work-out a single part of an octal permission value
function extract_permission($octal_value, $who, $action) {
	// Make sure the octal value is 4 chars long
	if(strlen($octal_value) == 0) {
		$octal_value = '0000';
	} elseif(strlen($octal_value) == 1) {
		$octal_value = '000'.$octal_value;
	} elseif(strlen($octal_value) == 2) {
		$octal_value = '00'.$octal_value;
	} elseif(strlen($octal_value) == 3) {
		$octal_value = '0'.$octal_value;
	} elseif(strlen($octal_value) == 4) {
		$octal_value = ''.$octal_value;
	} else {
		$octal_value = '0000';
	}
	// Work-out what position of the octal value to look at
	switch($who) {
	case 'u':
		$position = '1';
		break;
	case 'user':
		$position = '1';
		break;
	case 'g':
		$position = '2';
		break;
	case 'group':
		$position = '2';
		break;
	case 'o':
		$position = '3';
		break;
	case 'others':
		$position = '3';
		break;
	}
	// Work-out how long the octal value is and ajust acording
	if(strlen($octal_value) == 4) {
		$position = $position+1;
	} elseif(strlen($octal_value) != 3) {
		exit('Error');
	}
	// Now work-out what action the script is trying to look-up
	switch($action) {
	case 'r':
		$action = 'r';
		break;
	case 'read':
		$action = 'r';
		break;
	case 'w':
		$action = 'w';
		break;
	case 'write':
		$action = 'w';
		break;
	case 'e':
		$action = 'e';
		break;
	case 'execute':
		$action = 'e';
		break;
	}
	// Get the value for "who"
	$value = substr($octal_value, $position-1, 1);
	// Now work-out the details of the value
	switch($value) {
	case '0':
		$r = false;
		$w = false;
		$e = false;
		break;
	case '1':
		$r = false;
		$w = false;
		$e = true;
		break;
	case '2':
		$r = false;
		$w = true;
		$e = false;
		break;
	case '3':
		$r = false;
		$w = true;
		$e = true;
		break;
	case '4':
		$r = true;
		$w = false;
		$e = false;
		break;
	case '5':
		$r = true;
		$w = false;
		$e = true;
		break;
	case '6':
		$r = true;
		$w = true;
		$e = false;
		break;
	case '7':
		$r = true;
		$w = true;
		$e = true;
		break;
	default:
		$r = false;
		$w = false;
		$e = false;
	}
	// And finally, return either true or false
	return $$action;
}

// Function to delete a page
function delete_page($page_id) {
	
	global $admin, $database;
	
	// Find out more about the page
	$database = new database();
	$query = "SELECT page_id,menu_title,page_title,level,link,parent,modified_by,modified_when FROM ".TABLE_PREFIX."pages WHERE page_id = '$page_id'";
	$results = $database->query($query);
	if($database->is_error()) {
		$admin->print_error($database->get_error());
	}
	if($results->numRows() == 0) {
		$admin->print_error($MESSAGE['PAGES']['NOT_FOUND']);
	}
	$results_array = $results->fetchRow();
	$parent = $results_array['parent'];
	$level = $results_array['level'];
	$link = $results_array['link'];
	$page_title = ($results_array['page_title']);
	$menu_title = ($results_array['menu_title']);
	
	// Get the sections that belong to the page
	$query_sections = $database->query("SELECT section_id,module FROM ".TABLE_PREFIX."sections WHERE page_id = '$page_id'");
	if($query_sections->numRows() > 0) {
		while($section = $query_sections->fetchRow()) {
			// Set section id
			$section_id = $section['section_id'];
			// Include the modules delete file if it exists
			if(file_exists(WB_PATH.'/modules/'.$section['module'].'/delete.php')) {
				require(WB_PATH.'/modules/'.$section['module'].'/delete.php');
			}
		}
	}
	
	// Update the pages table
	$query = "DELETE FROM ".TABLE_PREFIX."pages WHERE page_id = '$page_id'";
	$database->query($query);
	if($database->is_error()) {
		$admin->print_error($database->get_error());
	}
	
	// Update the sections table
	$query = "DELETE FROM ".TABLE_PREFIX."sections WHERE page_id = '$page_id'";
	$database->query($query);
	if($database->is_error()) {
		$admin->print_error($database->get_error());
	}
	
	// Include the ordering class or clean-up ordering
	require_once(WB_PATH.'/framework/class.order.php');
	$order = new order(TABLE_PREFIX.'pages', 'position', 'page_id', 'parent');
	$order->clean($parent);
	
	// Unlink the page access file and directory
	$directory = WB_PATH.PAGES_DIRECTORY.$link;
	$filename = $directory.'.php';
	$directory .= '/';
	if(file_exists($filename)) {
		if(!is_writable(WB_PATH.PAGES_DIRECTORY.'/')) {
			$admin->print_error($MESSAGE['PAGES']['CANNOT_DELETE_ACCESS_FILE']);
		} else {
			unlink($filename);
			if(file_exists($directory)) {
				rm_full_dir($directory);
			}
		}
	}
	
}

// Load module into DB
function load_module($directory, $install = false) {
	global $database,$admin,$MESSAGE;
	if(file_exists($directory.'/info.php')) {
		require($directory.'/info.php');
		if(isset($module_name)) {
			if(!isset($module_license)) { $module_license = 'GNU General Public License'; }
			if(!isset($module_platform) AND isset($module_designed_for)) { $module_platform = $module_designed_for; }
			if(!isset($module_function) AND isset($module_type)) { $module_function = $module_type; }
			$module_function = strtolower($module_function);
			// Check that it doesn't already exist
			$result = $database->query("SELECT addon_id FROM ".TABLE_PREFIX."addons WHERE directory = '".$module_directory."' LIMIT 0,1");
			if($result->numRows() == 0) {
				// Load into DB
				$query = "INSERT INTO ".TABLE_PREFIX."addons ".
				"(directory,name,description,type,function,version,platform,author,license) ".
				"VALUES ('$module_directory','$module_name','".addslashes($module_description)."','module',".
				"'$module_function','$module_version','$module_platform','$module_author','$module_license')";
				$database->query($query);
				// Run installation script
				if($install == true) {
					if(file_exists($directory.'/install.php')) {
						require($directory.'/install.php');
					}
				}
			}
		}
	}
}

// Load template into DB
function load_template($directory) {
	global $database;
	if(file_exists($directory.'/info.php')) {
		require($directory.'/info.php');
		if(isset($template_name)) {
			if(!isset($template_license)) { $template_license = 'GNU General Public License'; }
			if(!isset($template_platform) AND isset($template_designed_for)) { $template_platform = $template_designed_for; }
			// Check that it doesn't already exist
			$result = $database->query("SELECT addon_id FROM ".TABLE_PREFIX."addons WHERE directory = '".$template_directory."' LIMIT 0,1");
			if($result->numRows() == 0) {
				// Load into DB
				$query = "INSERT INTO ".TABLE_PREFIX."addons ".
				"(directory,name,description,type,version,platform,author,license) ".
				"VALUES ('$template_directory','$template_name','".addslashes($template_description)."','template',".
				"'$template_version','$template_platform','$template_author','$template_license')";
				$database->query($query);
			}
		}
	}
}

// Load language into DB
function load_language($file) {
	global $database;
	if(file_exists($file)) {
		require($file);
		if(isset($language_name)) {
			if(!isset($language_license)) { $language_license = 'GNU General Public License'; }
			if(!isset($language_platform) AND isset($language_designed_for)) { $language_platform = $language_designed_for; }
			// Check that it doesn't already exist
			$result = $database->query("SELECT addon_id FROM ".TABLE_PREFIX."addons WHERE directory = '".$language_code."' LIMIT 0,1");
			if($result->numRows() == 0) {
				// Load into DB
				$query = "INSERT INTO ".TABLE_PREFIX."addons ".
				"(directory,name,type,version,platform,author,license) ".
				"VALUES ('$language_code','$language_name','language',".
				"'$language_version','$language_platform','$language_author','$language_license')";
	 		$database->query($query);
			}
		}
	}
}

?>