<?php

/*  
   	User profile edit Processor
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
	include_once("../../inc/functions/user.php");

$db = new DB();
$db->query("SELECT * FROM ". DB_PREPEND . "config");
$config = $db->next_record();



if ($config['user_edit'] == "off" && !is_memberof(1)) { 
    $message = "Sorry, Wizard is set to only allow the Webmaster to edit user profiles.";
	$location = CMS_WWW . "/admin.php?id=2&item=27&message=$message";
	header("Location: $location");
	exit;
}

// Define post fields into simple variables
	
	$first_name = $_POST['first_name'];
	$last_name = $_POST['last_name'];
	$country = $_POST['country'];
	$organization = $_POST['organization'];
	$phone = $_POST['phone'];
	$fax = $_POST['fax'];
	$address = $_POST['address'];
	$city = $_POST['city'];
	$state = $_POST['state'];
	$postal = $_POST['postal'];
	$passwd = $_POST['passwd'];
	$email = $_POST['email'];
	$gid = $_POST['gid'];
    $comment = $_POST['comment'];
	$uid = $_POST['uid'];
    $subscribe = $_POST['subscribe'];
	$is_confirmed = $_POST['is_confirmed'];

	
	$first_name = strip_tags($first_name, '<a><b><i><u>');
	$last_name = strip_tags($last_name, '<a><b><i><u>');
	$email = strip_tags($email, '<a><b><i><u>');
	$passwd = strip_tags($passwd, '<a><b><i><u>');
	$organization = strip_tags($organization, '<a><b><i><u>');
	$comment = strip_tags($comment, '<a><b><i><u>');
	$phone = strip_tags($phone, '<b><i><em><strong><u><br><p>');
	$address = strip_tags($address, '<b><i><em><strong><u><br><p>');
	$address2 = strip_tags($address2, '<b><i><em><strong><u><br><p>');
	$city = strip_tags($city, '<b><i><em><strong><u><br><p>');
	$comment = strip_tags($comment, '<b><i><em><strong><u><br><p>');
	$state = strip_tags($state, '<b><i><em><strong><u><br><p>');
	$fax = strip_tags($fax, '<b><i><em><strong><u><br><p>');
	$is_confirmed = strip_tags($is_confirmed, '<b><i><em><strong><u><br><p>');

	$first_name = checkSlashes($first_name);
	$last_name = checkSlashes($last_name);
	$email = checkSlashes($email);
	$passwd = checkSlashes($passwd);
	$organization = checkSlashes($organization);
	$phone = checkSlashes($phone);
	$address = checkSlashes($address);
	$city = checkSlashes($city); 
	$postal = checkSlashes($postal);  
    $comment = checkSlashes($comment);
	$state = checkSlashes($state);
	$fax = checkSlashes($fax);
	$is_confirmed = checkSlashes($is_confirmed);
	$address2 = checkSlashes($address2);
	
	

	$db = new DB();
	$db = $db->query("UPDATE ". DB_PREPEND . "users SET first_name='$first_name', last_name='$last_name', country='$country', organization='$organization', phone='$phone', fax='$fax', address='$address', address2='$address2', city='$city', is_confirmed='$is_confirmed', state='$state', postal='$postal', email='$email', comment='$comment' WHERE uid='$uid' ");
    
	//now add the group memberships
	
	if ($gid) {
	   
	    if ($_POST['gid']) {
	        foreach ($gid as $g)
			{
	        $db = new DB();
			$db = $db->query("INSERT INTO ". DB_PREPEND . "groupusers (uid, gid) VALUES ('$uid', '$g') ");
			
			}
	    }	
	}
		
	$message = "User record update was successful.";
			$location = CMS_WWW . "/admin.php?id=2&item=27&sub=28&message=$message";
			header("Location: $location");
			exit(); 
		
?>