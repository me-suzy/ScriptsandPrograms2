<?php
/*  
   Add User Processor
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
include_once("../../inc/config_cms/configuration.php");	
include_once("../../inc/db/db.php");	
include_once("../../inc/functions/validate.input.form.php");
include_once("../../inc/functions/user.php");

//authentication check
    	if (!is_memberof(1)) {
    		$message = "Sorry, only webmasters can add groups.";
			$location = CMS_WWW . "/admin.php?id=2&item=24&sub=31&message=$message";
			header("Location: $location");
		exit;
		}


$name = $_POST['name'];
$description = $_POST['description'];



if((!$name) || (!$description) )  {

	if(!$name){
		$message="Error: Please enter a group name.<br />";
	}
	if(!$description){
		$message="Error: Please enter a group description.<br />";
	}
	
			
    $location = CMS_WWW . "/admin.php?id=2&item=24&sub=30&message=$message&name=$name&description=$description";
	header("Location: $location");
	exit(); 

}


	
/* Let's strip some slashes in case the user entered
any escaped characters. */

$name = strip_tags($name, '<b><i><em><strong><u><br><p>');
$description = strip_tags($description, '<b><i><em><strong><u><br><p>');


$name = checkSlashes($name);
$description = checkSlashes($description);

$db = new DB();
$db->query("INSERT INTO ". DB_PREPEND . "groups (name,dsc) VALUES ('$name', '$description' )");


		$message = "The $name group was added successfully.";
			$location = CMS_WWW . "/admin.php?id=2&item=24&sub=31&message=$message";
			header("Location: $location");
			exit(); 
		
	
?>