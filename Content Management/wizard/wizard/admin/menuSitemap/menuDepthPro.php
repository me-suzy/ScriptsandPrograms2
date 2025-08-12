<?php

/*  
   Menu Properties Processor
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
		include '../../inc/functions/user.php';
		
		
		//authentication check
    	if (!is_memberof(1)) {
    		$message = "Sorry, only webmasters can make menu depth changes.";
			$location = CMS_WWW . "/admin.php?id=2&item=19&sub=88&message=$message";
			header("Location: $location");
		exit;
		}
        
		
        $topmenu = $_POST['topmenu'];			    		
	    $leftmenu = $_POST['leftmenu'];
		
		

		
		//make sure value entered is valid
		if (!is_numeric($topmenu)) {
			$message = "Sorry, your top menu entry was not a numeric value.";
			$location = CMS_WWW . "/admin.php?id=2&item=19&sub=88&message=$message";
			header("Location: $location");
			exit;
		}
		
		//make sure value entered is valid
		if (!is_numeric($leftmenu)) {
			$message = "Sorry, your left menu entry was not a numeric value.";
			$location = CMS_WWW . "/admin.php?id=2&item=19&sub=88&message=$message";
			header("Location: $location");
			exit;
		}
		
				
	    		
		$db = new DB();
		$db->query("UPDATE ". DB_PREPEND . config . " SET topmenu='$topmenu', leftmenu='$leftmenu' WHERE id = '1' ");
		
/*
*  Builds new menu arrays and stores them in _menuData database tables
*/

include '../../inc/functions/store_menu_array.php';
		
		
		
		
		
		
	 	$message = "Menu settings were updated.";

$location = CMS_WWW . "/admin.php?id=2&item=19&sub=88&message=$message";
header("Location: $location");
?>