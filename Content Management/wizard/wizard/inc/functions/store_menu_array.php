<?php
/*  
   Function builds menu array stored in database
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

$db = new DB();
	$db->query("SELECT * FROM ". DB_PREPEND . "config");
	$config = $db->next_record();
	$db->close();	

include("menu_array.php"); //generates menu array

// get the raw data: ids, parentIds, and titles from the specified table
	$menutable = "pages";
	$result_array = result_array($menutable);  // get all the ids and parentIds in the table
	
//now process the data and build an array with the root,branch leaf structure
	$parent = $result_array[0][0];
	$arr_size = count($result_array);
	$depth = 1;
	$startSeed = 1;
	$menu_array[] = array($depth, $result_array[0][0], $result_array[0][1], $result_array[0][2], $result_array[0][3], $result_array[0][4],$result_array[0][5]);
	find_child($parent, $startSeed, $depth); 

//include 'dump.class.php';
//$dumparray = new dump();
//echo $dumparray->dump($menu_array); 

//store the new menu array in the database for use by other functions, particularly front menu and site map
$store = serialize($menu_array);
$db = new DB();
$db->query("UPDATE ". DB_PREPEND . "menuData  SET serialized='$store' WHERE id='0' ");
$db->close();

//top (horizontal) menu data

$frontlevels = $config['topmenu'];

if ($frontlevels)
{  
    $frontlevels--;
    $keycount = 0;
	foreach ($menu_array as $v1) {
	    if ($menu_array[$keycount][0] > $frontlevels)
		{}
		else {
		$front_menu[] = array($menu_array[$keycount][0], $menu_array[$keycount][1], $menu_array[$keycount][2], $menu_array[$keycount][3], $menu_array[$keycount][4],$menu_array[$keycount][5], $menu_array[$keycount][6] );

		} // store this key
		$keycount++;
	} //foreach

//echo $dumparray->dump($front_menu); exit;

//store front menu in database
$store = serialize($front_menu);
$db = new DB();
$db->query("UPDATE ". DB_PREPEND . "menuData  SET serialized='$store' WHERE id='1' ");
$db->close();

} // if more than one level for top level

//this is used in cases where the site has a vertical menu orientation
//left (vertical) menu data is only generated if it has one or more levels and the toplevel menu is set to 0
$frontlevels = $config['topmenu'];
if (!frontlevels) { }
else {
		$leftlevels = $config['leftmenu'];

		if ($leftlevels)
		{  
    	$leftlevels--;
    	$keycount = 0;
		foreach ($menu_array as $v1) {
	    	if ($menu_array[$keycount][0] > $leftlevels)
			{}
			else {
				$left_menu[] = array($menu_array[$keycount][0], $menu_array[$keycount][1], $menu_array[$keycount][2], $menu_array[$keycount][3], $menu_array[$keycount][4],$menu_array[$keycount][5], $menu_array[$keycount][6]  );

			} // store this key
			$keycount++;
		} //foreach

	//echo $dumparray->dump($left_menu); exit;

	//store front menu in database
	$store = serialize($left_menu);
	$db = new DB();
	$db->query("UPDATE ". DB_PREPEND . "menuData  SET serialized='$store' WHERE id='2' ");
	$db->close();

	} // if more than one level for left level

} // if top menu is not zero


//used for storing sitemap levels

$sitemaplevels = $config['sitemapDepth'];

if ($sitemaplevels)
{  
    $sitemaplevels--;
	
	
    $keycount = 0;
	foreach ($menu_array as $v1) {
	    if ($menu_array[$keycount][0] > $sitemaplevels)
		{}
		else {
		$sitemap_menu[] = array($menu_array[$keycount][0], $menu_array[$keycount][1], $menu_array[$keycount][2], $menu_array[$keycount][3], $menu_array[$keycount][4],$menu_array[$keycount][5], $menu_array[$keycount][6] );

		} // store this key
		$keycount++;
	} //foreach

//echo $dumparray->dump($sitemap_menu); exit;

//store sitemap menu in database
$store = serialize($sitemap_menu);
$db = new DB();
$db->query("UPDATE ". DB_PREPEND . "menuData  SET serialized='$store' WHERE id='3' ");
$db->close();

} // if more than one level for top level