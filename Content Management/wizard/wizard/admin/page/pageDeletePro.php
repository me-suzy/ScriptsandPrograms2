<?php

/*  
   Page Delete Processor
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


// before deleting the page get its server file name and parentId for the sort function below
$db = new DB();
$db->query("SELECT parentId,filename FROM ". DB_PREPEND . "pages WHERE id='$pageid' ");
$i = $db->next_record();
$parentId = $i['parentId'];
$filename = $i['filename'];


//now delete the file from the database	
	$db->query("DELETE FROM ". DB_PREPEND . "pages WHERE id = '$pageid' ");
//now delete the file from the server

	$filepath = CMS_ROOT . '/pages/' . $filename;
	if (!unlink($filepath)) {
	$message2 = "Warning: failed to delete ".$filename." from server.";
	}
	
//delete this page from the comments table as well
	$commentName = '/pages/' . $filename;
	$db->query("DELETE FROM ". DB_PREPEND . "comments WHERE page = '$commentName' ");
	
//this section re-orders the pages at this menu level according to user-defined position
$table_name = "pages";
$where = "1=1";

$test = sort_position($table_name, $where, $parentId);

/*
*  Builds new menu arrays and stores them in _menuData database tables
*/

include '../../inc/functions/store_menu_array.php';

//determine if the file delete was successful
    $edit = $_GET['edit'];
	
	if ($edit==1)
	{
     if ($message2){ $message = $message2; }
	 else {$message = "Page was deleted.";}
	 $pageid = $_GET['parentId'];
	 
	$location = CMS_WWW . "/admin.php?id=2&item=1&sub=9&sec=2&pageid=$pageid&message=$message";
	header("Location: $location");

	}
	else	{
	$message = "Page was deleted.";
	$location = CMS_WWW . "/admin.php?id=2&item=1&sub=85&sec=1&message=$message";
	header("Location: $location");
	
	}

?>