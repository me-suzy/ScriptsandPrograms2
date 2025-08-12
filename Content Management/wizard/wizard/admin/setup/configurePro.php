<?php
/*
	Configure Processor
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
		
        			    		
	    $name = $_POST['name'];
		
		$url = $_POST['url'];
		$copyright = $_POST['copyright'];
		$address = $_POST['address'];
		$city = $_POST['city'];
		$state = $_POST['state'];
		$country = $_POST['country'];
		$postal = $_POST['postal'];
		$phone = $_POST['phone'];
		$fax = $_POST['fax'];
		$email = $_POST['email'];
		$siteAdmin = $_POST['siteAdmin'];
		$company = $_POST['company'];
		
	    $name = checkSlashes($name);
		$url = checkSlashes($url);
		$copyright = checkSlashes($copyright);
		$city = checkSlashes($city);
		$state = checkSlashes($state);
		$country = checkSlashes($country); 
		$postal = checkSlashes($postal);
		$phone = checkSlashes($phone);
		$fax = checkSlashes($fax);
		$email = checkSlashes($email);
	    $siteAdmin = checkSlashes($siteAdmin);
	    $company = checkSlashes($company);
	    		
		$db = new DB();
		$db->query("UPDATE ". DB_PREPEND . config . " SET name='$name', copyright='$copyright',  siteAdmin='$siteAdmin', address='$address', city='$city', state='$state', country='$country', phone='$phone', fax='$fax', postal='$postal', company='$company', email='$email' WHERE id = '1' ");
		
	 	$message = "Configuration was updated.";

$location = CMS_WWW . "/admin.php?id=2&item=24&sub=25&message=$message";
header("Location: $location");
?>