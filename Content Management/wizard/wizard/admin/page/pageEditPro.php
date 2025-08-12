<?php

/*  
   Edit Page Script Processor
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


$newPosition = $_POST['newPosition']; 
$position = $_POST['position']; 
$pageName = $_POST['pageName']; 
$pageid = $_POST['pageid']; 
$parentId = $_POST['parentId'];
$permission = $_POST['permission'];
$menu = $_POST['menu'];
$fileName = $_POST['fileName']; 
$pageparent = $_POST['pageparent']; 
$description = $_POST['metadescription'];
$keywords = $_POST['keywords'];
$robots = $_POST['robots'];


if (($pageid == "1") && ($pageparent !== "0")) {
			$message = "Error - Home page Parent Id cannot be changed.";
			$location = CMS_WWW . "/admin.php?id=2&item=1&sub=9&pageid=$pageid&sec=2&message=$message";
			header("Location: $location");
			exit;
}

if (!$menu ) {$menu = "off";}
		else {$menu = "on";}

		if ($pageName == "") {
		    if ($pageid == 0) { $pageid = 1;  }
		    $message = "Error - You did not give the page a name.";
			$location = CMS_WWW . "/admin.php?id=2&item=1&sub=9&pageid=$pageid&sec=2&message=$message";
			header("Location: $location");
			exit;
		}
		
$pageName = checkSlashes($pageName);
$robots = checkSlashes($robots);
$pageName = strip_tags($pageName, '<b><i><em><strong><u><br><p>');

$description = clean($description);
$keywords = clean($keywords);


//check to see if the page parent is valid, except where parent is home (0)
if ($pageparent) {
	$db = new DB();
	$db->query("SELECT id FROM ". DB_PREPEND . "pages where id='$pageparent' ");
		$test = $db->num_rows();	
		if (!$test) {
		$message = "Error - You chose an invalid Parent id: $pageparent.";
				$location = CMS_WWW . "/admin.php?id=2&item=1&sub=9&pageName=$pageName&pageid=$pageid&sec=2&newPosition=$newPosition&message=$message";
				header("Location: $location");
				exit;
		}
}
//end check page parent

//start of filename

//check to see if filename is different


	$db = new DB();
	$db->query("SELECT filename FROM ". DB_PREPEND . "pages where id='$pageid' ");
		$page = $db->next_record();	
		//$test = $db->num_rows();
		
		$oldfile = $page['filename']; 

if ($oldfile != $fileName) {


			if ($fileName == "") {
		    	$message = "Error - You did not give the new page a file name (e.g. newpage.php).";
				$location = CMS_WWW . "/admin.php?id=2&item=1&sub=9&pageName=$pageName&pageid=$pageid&sec=2&newPosition=$newPosition&message=$message";
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
			$message = "Error - Your filename extension should be: .php";
			$location = CMS_WWW . "/admin.php?id=2&item=1&sub=9&pageName=$pageName&pageid=$pageid&sec=2&newPosition=$newPosition&message=$message";
				header("Location: $location");
				exit;
			}
		
	
		   //check to see if file already exists
		   $filecheck = CMS_ROOT . '/pages/' . $fileName;
	   
	   
		if (file_exists($filecheck)) {
   			$message = "Error - A file with the name ".$fileName." already exists.";
			$location = CMS_WWW . "/admin.php?id=2&item=1&sub=9&pageName=$pageName&pageid=$pageid&sec=2&newPosition=$newPosition&message=$message";
			header("Location: $location");
			exit;
			} 
			
		//rename file	
	 $file_old = CMS_ROOT . '/pages/' . $oldfile;
	 $file_new = CMS_ROOT . '/pages/' . $fileName;
	 
	 if (!rename($file_old, $file_new)) {$message = "Could not find or rename the php file.";  
	 
	 $location = CMS_WWW . "/admin.php?id=2&item=1&sub=9&filename=$fileName&pageName=$pageName&pageid=$pageid&sec=2&newPosition=$newPosition&message=$message";
			header("Location: $location");
			exit;
	 
	 
	 }
			
} //end if changed filename			
			
// end of file name


//update pages table
    $db = new DB();
	$db->query("UPDATE ". DB_PREPEND . "pages SET title='$pageName', parentId='$pageparent',filename='$fileName',menu='$menu', permit='$permission', description='$description', keywords='$keywords', robots='$robots'  WHERE id = '$pageid' ");



//change the pagename and or title in the comments table
	$file_old = '/pages/' . $oldfile;
	$file_new = '/pages/' . $fileName;

	$db = new DB();
	$db->query("UPDATE ". DB_PREPEND . "comments SET title='$pageName', page='$file_new'  WHERE page = '$file_old' ");
	$db->close();


//this section reorders the menu level according to the user defined position
$table_name = "pages";
$where = "1=1";
$insertId = $pageid;
if (is_array($position)){
$test = insert_position($table_name, $newPosition, $position, $where, $insertId);
}

/*
*  Builds new menu arrays and stores them in _menuData database tables
*/

include '../../inc/functions/store_menu_array.php';




$message = "Update was successful.";
	
$location = CMS_WWW . "/admin.php?id=2&item=1&sub=9&sec=2&pageid=$pageid&title=$pageName&message=$message";
header("Location: $location");

?>