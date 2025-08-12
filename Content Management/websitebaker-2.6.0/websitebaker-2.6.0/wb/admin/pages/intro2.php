<?php

// $Id: intro2.php 239 2005-11-22 11:50:41Z stefan $

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

// Get posted content
if(!isset($_POST['content'])) {
	header("Location: intro.php");
} else {
	$content = $_POST['content'];
}

// Create new admin object
require('../../config.php');
require_once(WB_PATH.'/framework/class.admin.php');
$admin = new admin('Pages', 'pages_intro');

$content=htmlspecialchars($admin->strip_slashes($content));

// Include the WB functions file
require_once(WB_PATH.'/framework/functions.php');

// Write new content
$filename = WB_PATH.PAGES_DIRECTORY.'/intro.php';
$handle = fopen($filename, 'w');
if(is_writable($filename)) {
	if(fwrite($handle, $content)) {
		fclose($handle);
		change_mode($filename, 'file');
		$admin->print_success($MESSAGE['PAGES']['INTRO_SAVED']);
	} else {
		fclose($handle);
		$admin->print_error($MESSAGE['PAGES']['INTRO_NOT_WRITABLE']);
	}
} else {
	$admin->print_error($MESSAGE['PAGES']['INTRO_NOT_WRITABLE']);
}

// Print admin footer
$admin->print_footer();

?>