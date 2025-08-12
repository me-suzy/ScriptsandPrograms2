<?php

/*  
   	New Page Script Processor
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
include '../../inc/functions/validate.input.form.php';
include '../../inc/functions/sort_functions.php';
//include '../../inc/functions/dump.class.php';
//$dumparray = new dump();

$uid = $_POST['uid'];
$newPosition = $_POST['newPosition']; 
$pageName = $_POST['pageName']; 
$parentId = $_POST['parentId']; 
$position = $_POST['position'];
$fileName = $_POST['fileName']; 
$template = $_POST['template'];



if (!$menu ) {$menu = "off";}
		else {$menu = "on";}	


if ($newPosition == "") { $newPosition = 1; }

if ($fileName == "") {
		    $message = "Error - You did not give the new page a file name (e.g. newpage.php).";
			$location = CMS_WWW . "/admin.php?id=2&item=1&sub=2&pageName=$pageName&pageid=$parentId&sec=2&newPosition=$newPosition&message=$message";
			header("Location: $location");
			exit;
		}
		
		//convert to lowercase
		$fileName=strtolower($fileName);
		
		//reform name to be legal
			$fileName=reform($fileName);
		
		
		//check for .php extension
		$findme  = '.php';
		$pos = strpos($fileName, $findme);
		if (!$pos)
		{
		$message = "Error - Your filename extension should be: php";
			$location = CMS_WWW . "/admin.php?id=2&item=1&sub=2&sec=2&pageid=$parentId&fileName=$fileName&pageName=$pageName&newPosition=$newPosition&message=$message";
			header("Location: $location");
			exit;
		}
			
	   //check to see if file already exists
	   $filecheck = CMS_ROOT . '/pages/' . $fileName;
	   
	   
	if (file_exists($filecheck)) {
   		$message = "Error - A file with the name ".$fileName." already exists.";
			$location = CMS_WWW . "/admin.php?id=2&item=1&sub=2&sec=2&pageid=$parentId&pageName=$pageName&newPosition=$newPosition&message=$message";
			header("Location: $location");
			exit;
		} 


    		
		if ($pageName == "") {
		    $message = "Error - You did not give the new page a name.";
			$location = CMS_WWW . "/admin.php?id=2&item=1&sub=2&fileName=$fileName&pageName=$pageName&pageid=$parentId&sec=2&newPosition=$newPosition&message=$message";
			header("Location: $location");
			exit;
		}
		//make sure user entered a number for the menu position of the new page
		if (!$test = is_numeric($newPosition)) {
		    $message = "Error - The new menu position must be a number.";
			$location = CMS_WWW . "/admin.php?id=2&item=1&sub=2&fileName=$fileName&pageName=$pageName&pageid=$parentId&sec=2&newPosition=$newPosition&message=$message";
			header("Location: $location");
			exit;
		}
		
// create the new file in the pages directory on the server
 $file = CMS_ROOT . '/templates/' . $template;
	 $file_new = CMS_ROOT . '/pages/' . $fileName;
	 
	 if (!copy($file, $file_new)) {echo "Could not save the new file to the pages directory.";
	 $location = CMS_WWW . "/admin.php?id=2&item=1&sub=2&fileName=$fileName&pageName=$pageName&pageid=$parentId&sec=2&newPosition=$newPosition&message=$message";
			header("Location: $location");
			exit;
		} 		
		
		
		
$pageName = checkSlashes($pageName);
$pageName = strip_tags($pageName, '<b><i><em><strong><u><br><p>');



//Create new page
$db = new DB();
$db->query("INSERT INTO ". DB_PREPEND . "pages (date,title,parentId,position,filename) VALUES (now(),'$pageName','$parentId','$newPosition','$fileName')");  
$insertId = $db->lastId();
$db->close();


//this section re-orders the pages at this menu level according to user-defined position
$table_name = "pages";
$where = "1=1";
if (is_array($position)){
insert_position($table_name, $newPosition, $position, $where, $insertId);
}

/*
*  Builds new menu arrays and stores them in _menuData database tables
*/

include '../../inc/functions/store_menu_array.php';


$message = "New page was created.";
$location = CMS_WWW . "/admin.php?id=2&item=1&sub=9&sec=4&pageid=$insertId&message=$message";
header("Location: $location");

?>