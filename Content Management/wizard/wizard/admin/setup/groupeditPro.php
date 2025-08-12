<?php



/*  
   Group Edit Processor
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
// main configuration file
	include_once '../../inc/config_cms/configuration.php';
	include_once '../../inc/db/db.php';
include_once("../../inc/functions/validate.input.form.php");
include_once("../login/user.php");

$db = new DB();
$db->query("SELECT * FROM ". DB_PREPEND . "config");
$config = $db->next_record();



if ($config['admin_groups'] == "off" && !is_memberof(1)) { 
    $message = "Sorry, \"Settings\" allow only the Webmaster to edit Groups.";
	$location = CMS_WWW . "/admin.php?id=2&item=24&sub=30&message=$message";
	header("Location: $location");
	exit;
}

// Define post fields into simple variables
	
	$recordId = $_POST['recordId'];
	$gid = $_POST['gid'];
	$name = $_POST['name'];
	$description = $_POST['description'];
	
	$gid = strip_tags($gid, '<b><i><em><strong><u><br><p>');
	$name = strip_tags($name, '<b><i><em><strong><u><br><p>');
	$description = strip_tags($description, '<b><i><em><strong><u><br><p>');

	$description = checkSlashes($description);
	$name = checkSlashes($name);
	$gid = checkSlashes($gid);
	
	$db = new DB();
	$db = $db->query("UPDATE ". DB_PREPEND . "groups SET gid='$gid', name='$name', dsc='$description' WHERE gid='$recordId' ");
    
				
	$message = "Group update was successful.";
			$location = CMS_WWW . "/admin.php?id=2&item=24&sub=30&message=$message";
			header("Location: $location");
			exit(); 
		
?>