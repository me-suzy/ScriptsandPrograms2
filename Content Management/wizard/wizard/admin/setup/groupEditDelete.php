<?php
/*  
   Group Membership Delete Processor
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

if ($config['admin_groups'] == "off" && !is_memberof(1)) {
    $message = "Sorry, only the webmaster can delete groups.";
	$location = CMS_WWW . "/admin.php?id=2&item=24&message=$message";
	header("Location: $location");
}

$gid = $_GET['gid']; 

	
	$db = new DB();
	$db->query("DELETE FROM ". DB_PREPEND . "groups WHERE gid='$gid' ");
	
	//delete group users
	$db = new DB();
	$db->query("DELETE FROM ". DB_PREPEND . "groupusers WHERE gid='$gid' ");
	
	
	$message = "Group was deleted.";
	
$location = CMS_WWW . "/admin.php?id=2&item=24&sub=30&message=$message";
header("Location: $location");


