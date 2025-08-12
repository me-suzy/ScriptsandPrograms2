<?php
/*  
   Permissions Processor 
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
		
        $register = $_POST['register'];	
		$approve = $_POST['approve'];				    		
	    $reg_webmaster = $_POST['reg_webmaster'];
		$user_view = $_POST['user_view'];
		$user_edit = $_POST['user_edit'];
		$user_add = $_POST['user_add'];
		$searchrestrict = $_POST['searchrestrict'];
		
		if (!$register ) {$register = "off";}
		else {$register = "on";}
		
		if (!$approve ) {$approve = "off";}
		else {$approve = "on";}
		
		if (!$reg_webmaster ) {$reg_webmaster = "off";}
		else {$reg_webmaster = "on";}
	    
		if (!$user_add ) {$user_add = "off";}
		else {$user_add = "on";}
		
		if (!$user_view ) {$user_view = "off";}
		else {$user_view = "on";}
		
		if (!$user_edit ) {$user_edit = "off";}
		else {$user_edit = "on";}
		
		if (!$searchrestrict ) {$searchrestrict = "off";}
		else {$searchrestrict = "on";}
		
	    		
		$db = new DB();
		$db->query("UPDATE ". DB_PREPEND . config . " SET register='$register', user_approve='$approve',reg_webmaster='$reg_webmaster', user_add='$user_add', user_view='$user_view', user_edit='$user_edit', searchRestrict='$searchrestrict'  WHERE id = '1' ");
		
	 	$message = "Permissions were updated.";

$location = CMS_WWW . "/admin.php?id=2&item=24&sub=75&message=$message";
header("Location: $location");
?>