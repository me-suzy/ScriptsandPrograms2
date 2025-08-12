<?php

/*  
   Page Renumber Processor
   (c) 2005 Philip Shaddock, www.wizardinteractive.com
	This file is part of the Wizard Site Framework.

    This file is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    It is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with the Wizard Site Framework; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

include '../../inc/config_cms/configuration.php';	
include '../../inc/db/db.php';	
include '../../inc/functions/sort_functions.php';


$pageid = $_POST[pageid]; 
$positionOrder = $_POST[positionOrder];
$idOrder = $_POST[idOrder];
$parentId = $_POST[parentId];



//Check that menu positions are numbers if they were changed
if ($positionOrder) {
    
	
	$count = 0;
	foreach ($positionOrder as $position) {
	
	//check if the menu position is a number
	if (!$test = is_numeric($position)) {
		    if ($pageid == 0) { $pageid = 1;  }
		    $message = "Error - New page positions must be numbers.";
			$location = CMS_WWW . "/admin.php?id=2&item=1&sub=80&pageid=$pageid&sec=2&message=$message";
			header("Location: $location");
			exit;
		} //if tesf
} // if positionOrder
    
	$positionOrder = $_POST[positionOrder];

    $db = new DB();
	$count = 0;
	foreach ($positionOrder as $new) {
	$db->query("UPDATE ". DB_PREPEND . "pages SET position='$new'  WHERE id = '" . $idOrder[$count]. "' ");
	$count++;
	} //foreach $recordid
} //if $positionOrder

//this section re-orders the pages at this menu level according to user-defined position
$table_name = "pages";
$where = "1=1";

$test = sort_position($table_name, $where, $parentId);

/*
*  Builds new menu arrays and stores them in _menuData database tables
*/

include '../../inc/functions/store_menu_array.php';



$message = "Update was successful.";
	
$location = CMS_WWW . "/admin.php?id=2&item=1&sub=80&sec=2&pageid=$pageid&message=$message";
header("Location: $location");

?>