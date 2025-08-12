<?php

// $Id: login.php 239 2005-11-22 11:50:41Z stefan $

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

require_once("../config.php");

// Make sure the login is enabled
if(!FRONTEND_LOGIN) {
	if(INTRO_PAGE) {
		header('Location: '.WB_URL.PAGES_DIRECTORY.'/index'.PAGE_EXTENSION);
	} else {
		header('Location: '.WB_URL.'/index'.PAGE_EXTENSION);
	}
}

// Required page details
$page_id = 0;
$page_description = '';
$page_keywords = '';
define('PAGE_ID', 0);
define('ROOT_PARENT', 0);
define('PARENT', 0);
define('LEVEL', 0);
define('PAGE_TITLE', 'Please login');
define('MENU_TITLE', 'Please login');
define('VISIBILITY', 'public');
// Set the page content include file
define('PAGE_CONTENT', WB_PATH.'/account/login_form.php');

require_once(WB_PATH.'/framework/class.login.php');

// Create new login app
$thisApp = new Login(
							array(
									"MAX_ATTEMPS" => "50",
									"WARNING_URL" => ADMIN_URL."/login/warning.html",
									"USERNAME_FIELDNAME" => 'username',
									"PASSWORD_FIELDNAME" => 'password',
									"REMEMBER_ME_OPTION" => SMART_LOGIN,
									"MIN_USERNAME_LEN" => "2",
									"MIN_PASSWORD_LEN" => "2",
									"MAX_USERNAME_LEN" => "30",
									"MAX_PASSWORD_LEN" => "30",
									"LOGIN_URL" => WB_URL."/account/login".PAGE_EXTENSION.'?redirect='.$_REQUEST['redirect'],
									"DEFAULT_URL" => WB_URL.PAGES_DIRECTORY."/index".PAGE_EXTENSION,
									"TEMPLATE_DIR" => ADMIN_PATH."/login",
									"TEMPLATE_FILE" => "template.html",
									"FRONTEND" => true,
									"FORGOTTEN_DETAILS_APP" => WB_URL."/account/forgot.php".PAGE_EXTENSION,
									"USERS_TABLE" => TABLE_PREFIX."users",
									"GROUPS_TABLE" => TABLE_PREFIX."groups",
									"REDIRECT_URL" => $_REQUEST['redirect']
							)
					);

// Set extra outsider var
$globals[] = 'thisApp';

// Include the index (wrapper) file
require(WB_PATH.'/index.php');


?>