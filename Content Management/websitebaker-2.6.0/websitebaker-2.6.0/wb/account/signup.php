<?php

// $Id: signup.php 27 2005-09-05 23:34:13Z stefan $

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

require('../config.php');

if(!is_numeric(FRONTEND_SIGNUP)) {
	if(INTRO_PAGE) {
		header('Location: '.WB_URL.PAGES_DIRECTORY.'/index'.PAGE_EXTENSION);
	} else {
		header('Location: '.WB_URL.'/index'.PAGE_EXTENSION);
	}
}

// Load the language file
if(!file_exists(WB_PATH.'/languages/'.DEFAULT_LANGUAGE.'.php')) {
	exit('Error loading language file '.DEFAULT_LANGUAGE.', please check configuration');
} else {
	require_once(WB_PATH.'/languages/'.DEFAULT_LANGUAGE.'.php');
	$load_language = false;
}


// Required page details
$page_id = 0;
$page_description = '';
$page_keywords = '';
define('PAGE_ID', 0);
define('ROOT_PARENT', 0);
define('PARENT', 0);
define('LEVEL', 0);
define('PAGE_TITLE', $TEXT['SIGNUP']);
define('MENU_TITLE', $TEXT['SIGNUP']);
define('MODULE', '');
define('VISIBILITY', 'public');

// Set the page content include file
if(isset($_POST['username'])) {
	define('PAGE_CONTENT', WB_PATH.'/account/signup2.php');
} else {
	define('PAGE_CONTENT', WB_PATH.'/account/signup_form.php');
}

// Set auto authentication to false
$auto_auth = false;

// Include the index (wrapper) file
require(WB_PATH.'/index.php');

?>