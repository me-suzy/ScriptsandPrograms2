<?php

// $Id: create.php 10 2005-09-04 08:59:31Z ryan $

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

// Get dir name and target location
if(!isset($_POST['name']) OR $_POST['name'] == '') {
	header("Location: index.php");
} else {
	$name = $_POST['name'];
}
if(!isset($_POST['target']) OR $_POST['target'] == '') {
	header("Location: index.php");
} else {
	$target = $_POST['target'];
}

// Print admin header
require('../../config.php');
require_once(WB_PATH.'/framework/class.admin.php');
$admin = new admin('Media', 'media_create');

// Include the WB functions file
require_once(WB_PATH.'/framework/functions.php');

// Check to see if name or target contains ../
if(strstr($name, '../')) {
	$admin->print_error($MESSAGE['MEDIA']['NAME_DOT_DOT_SLASH']);
}
if(strstr($target, '../')) {
	$admin->print_error($MESSAGE['MEDIA']['TARGET_DOT_DOT_SLASH']);
}

// Remove bad characters
$name = media_filename($name);
  
// Create relative path of the new dir name
$relative = WB_PATH.$target.'/'.$name;

// Check to see if the folder already exists
if(file_exists($relative)) {
	$admin->print_error($MESSAGE['MEDIA']['DIR_EXISTS']);
}

// Try and make the dir
if(make_dir($relative)) {
	// Create index.php file
	$content = ''.
"<?php

header('Location: ../');

?>";
	$handle = fopen($relative.'/index.php', 'w');
	fwrite($handle, $content);
	fclose($handle);
	change_mode($relative.'/index.php', 'file');
	$admin->print_success($MESSAGE['MEDIA']['DIR_MADE']);
} else {
	$admin->print_error($MESSAGE['MEDIA']['DIR_NOT_MADE']);
}

// Print admin 
$admin->print_footer();

?>
