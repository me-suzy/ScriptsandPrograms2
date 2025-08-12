<?php

// $Id: index.php 10 2005-09-04 08:59:31Z ryan $

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

require_once("../../config.php");
require_once(WB_PATH."/framework/class.login.php");

if(defined('SMART_LOGIN') AND SMART_LOGIN == 'enabled') {
	// Generate username field name
	$username_fieldname = 'username_';
	$password_fieldname = 'password_';
	$salt = "abchefghjkmnpqrstuvwxyz0123456789";
	srand((double)microtime()*1000000);
	$i = 0;
	while ($i <= 7) {
		$num = rand() % 33;
		$tmp = substr($salt, $num, 1);
		$username_fieldname = $username_fieldname . $tmp;
		$password_fieldname = $password_fieldname . $tmp;
		$i++;
	}
} else {
	$username_fieldname = 'username';
	$password_fieldname = 'password';
}

$thisApp = new Login(
							array(
									'MAX_ATTEMPS' => "50",
									'WARNING_URL' => ADMIN_URL."/login/warning.html",
									'USERNAME_FIELDNAME' => $username_fieldname,
									'PASSWORD_FIELDNAME' => $password_fieldname,
									'REMEMBER_ME_OPTION' => SMART_LOGIN,
									'MIN_USERNAME_LEN' => "2",
									'MIN_PASSWORD_LEN' => "2",
									'MAX_USERNAME_LEN' => "30",
									'MAX_PASSWORD_LEN' => "30",
									'LOGIN_URL' => ADMIN_URL."/login/index.php",
									'DEFAULT_URL' => ADMIN_URL."/start/index.php",
									'TEMPLATE_DIR' => ADMIN_PATH."/login",
									'TEMPLATE_FILE' => "template.html",
									'FRONTEND' => false,
									'FORGOTTEN_DETAILS_APP' => ADMIN_URL."/login/forgot/index.php",
									'USERS_TABLE' => TABLE_PREFIX."users",
									'GROUPS_TABLE' => TABLE_PREFIX."groups",
							)
					);

?>