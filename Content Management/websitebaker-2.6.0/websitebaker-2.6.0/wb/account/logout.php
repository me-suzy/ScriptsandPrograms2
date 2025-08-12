<?php

// $Id: logout.php 106 2005-09-15 19:15:43Z stefan $

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

require("../config.php");

$_SESSION['USER_ID'] = null;
$_SESSION['GROUP_ID'] = null;
$_SESSION['USERNAME'] = null;
$_SESSION['PAGE_PERMISSIONS'] = null;
$_SESSION['SYSTEM_PERMISSIONS'] = null;
$_SESSION = array();
session_unset();
unset($_COOKIE[session_name()]);
session_destroy();

if(INTRO_PAGE) {
	header('Location: '.WB_URL.PAGES_DIRECTORY.'/index'.PAGE_EXTENSION);
} else {
	header('Location: '.WB_URL.'/index'.PAGE_EXTENSION);
}

?>