<?php


/*  
   Delete User From Group Script
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
	include_once("../../inc/functions/user.php");

$gid=$_GET['gid'];
$uid=$_GET['uid'];
$colname=$_GET['colname'];
$colvalue=$_GET['colvalue'];

$db = new DB();
$db->query("SELECT * FROM ". DB_PREPEND . "config");
$config = $db->next_record();

if ($config['user_edit'] == "off" && !is_memberof(1)) { 
    $message = "Sorry, \"Settings\" allow only the Webmaster to edit this option.";
	$location = CMS_WWW . "/admin.php?id=2&item=27&sub=32&colname=$colname&colvalue=$colvalue&message=$message";
	header("Location: $location");
	exit;
}

$db = new DB();
$db->query("DELETE FROM ". DB_PREPEND . "groupusers WHERE uid='$uid' AND gid='$gid' ");

$message = "Sorry, \"Settings\" allow only the Webmaster to edit this option.";
$location = CMS_WWW . "/admin.php?id=2&item=27&sub=32&colname=$colname&colvalue=$colvalue&message=$message";
header("Location: $location");
exit;



?>