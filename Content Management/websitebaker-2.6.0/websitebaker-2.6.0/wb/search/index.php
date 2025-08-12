<?php

// $Id: index.php 108 2005-09-15 19:28:35Z stefan $

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

// Include the config file
require('../config.php');

// Required page details
$page_id = 0;
$page_description = '';
$page_keywords = '';
define('PAGE_ID', 0);
define('ROOT_PARENT', 0);
define('PARENT', 0);
define('LEVEL', 0);
define('PAGE_TITLE', 'Search');
define('MENU_TITLE', 'Search');
define('MODULE', '');
define('VISIBILITY', 'public');
define('PAGE_CONTENT', 'search.php');

// Find out what the search template is
$database = new database();
$query_template = $database->query("SELECT value FROM ".TABLE_PREFIX."search WHERE name = 'template' LIMIT 1");
$fetch_template = $query_template->fetchRow();
$template = $fetch_template['value'];
if($template != '') {
	define('TEMPLATE', $template);
}
unset($template);

// Include index (wrapper) file
require(WB_PATH.'/index.php');

?>