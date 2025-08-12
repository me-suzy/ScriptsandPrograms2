<?php

/*  
   	Sitemap Processor
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
    		$message = "Sorry, only webmasters can make menu properties changes.";
			$location = CMS_WWW . "/admin.php?id=2&item=19&sub=88&message=$message";
			header("Location: $location");
			exit;
		}
        
		
        $sitemapDepth = $_POST['sitemapDepth'];			    		
	   
		
		//make sure value entered is valid
		if (!is_numeric($sitemapDepth)) {
			$message = "Error: sitemap depth must be numeric.";
			$location = CMS_WWW . "/admin.php?id=2&item=19&sub=89&message=$message";
			header("Location: $location");
			exit;
		}
		
		//make sure value entered is valid
		if (!$sitemapDepth >= 1 ) {
			$message = "Error: sitemap depth must be at least one level deep.";
			$location = CMS_WWW . "/admin.php?id=2&item=19&sub=89&message=$message";
			header("Location: $location");
			exit;
		}
			
	    		
		$db = new DB();
		$db->query("UPDATE ". DB_PREPEND . config . " SET sitemapDepth='$sitemapDepth' WHERE id = '1' ");
		
		
				
/*
*  Builds new menu arrays and stores them in _menuData database tables
*/

include '../../inc/functions/store_menu_array.php';
		
		
		
		
		
	 	$message = "Sitemap depth settings were updated.";

$location = CMS_WWW . "/admin.php?id=2&item=19&sub=89&message=$message";
header("Location: $location");
?>