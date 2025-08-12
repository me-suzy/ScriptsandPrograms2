<?php 
/* 
    Page Header 
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
	include_once 'inc/config_cms/configuration.php';
// database class
	include_once 'inc/db/db.php';
// language translation
	include_once 'inc/languages/' . $language . '.public.php';
// authentication
	include_once 'inc/functions/user.php';


// configuration parameters
	$db = new DB();
	$db->query("SELECT * FROM ". DB_PREPEND . "config");
	$config = $db->next_record();
	$db->close();	
	
// menu data 
	$db = new DB();
	$db->query("SELECT * FROM ". DB_PREPEND . "menuData WHERE id='0' ");
	$i = $db->next_record();
	$menuData = unserialize($i[serialized]);
	
	$num_rows = count($menuData);	
	// check database connection and pages table
	if ($num_rows < 1) { 
	$location = "templates/alerts/nopages.html";
	header("Location: $location");
	exit;
	}
	
	
//page id used
	$id = $_GET['id'];
	// security feature, prevents people from loading pages that bypass the page management system by not entering page id number
	if ((!is_numeric($id)) || $id == "0" || (!isset($id))) {
	$id = 1;
	$location = CMS_WWW . "/pages/index.php?id=1";
	header("Location: $location");
	exit;
	}
	
//page data
	// configuration parameters
	$db = new DB();
	$db->query("SELECT * FROM ". DB_PREPEND . "pages where id='$id'");
	$page = $db->next_record();
	$db->close();
	
//security check to make sure page id is valid with the exception of forms
    if ($id !== $page['id']) {
	$id = 1;
	$location = CMS_WWW . "/pages/index.php?id=1";
	header("Location: $location");
	exit;
	}

//visitor statistics, webmaster's visits are not logged
if ( user_getname() != "admin") {
$db = new DB();
$q='insert into '. DB_PREPEND . 'hits (Host, PageId, Title, Date, Member, Referer) values
  ("'.gethostbyaddr($_SERVER['REMOTE_ADDR']).'",
  "' . addslashes($id) . '", "'.addslashes($page['title']).'", now(), "'. user_getname() . '",  "'. $HTTP_REFERER  . '"  )'; 
$db->query("$q");  
}
?>