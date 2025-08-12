<?php

/*  
   Move Page Script Processor
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



$pageid = $_GET['pageid']; 
$moveToPage = $_GET['moveToPage'];


// before moving the page get parentId for the sort function below
$db = new DB();
$db->query("SELECT parentId FROM ". DB_PREPEND . "pages WHERE id='$pageid' ");
$i = $db->next_record();
$parentId = $i['parentId'];
$db->close();

//make Home Page "0" so that no pages can be attached below it
if ($moveToPage == 1) {$moveToPage = "0";}
		
//update page parent making moved page the last page in the page order
    $db = new DB();
	$db->query("UPDATE ". DB_PREPEND . "pages SET parentId='$moveToPage', position='9999'  WHERE id = '$pageid' ");
	
	
//this section re-orders the pages at the old menu level 
$table_name = "pages";
$where = "1=1";

$test = sort_position($table_name, $where, $parentId);

//this section re-orders the pages at the old menu level 
$parentId = $moveToPage;
$test = sort_position($table_name, $where, $parentId);
	
	
/*
*  Builds new menu arrays and stores them in _menuData database tables
*/

include '../../inc/functions/store_menu_array.php';




$message = "Move was successful.";
	
$location = CMS_WWW . "/admin.php?id=2&item=1&sub=10&sec=1&pageid=$pageid&message=$message";
header("Location: $location");

?>