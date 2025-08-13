<?
// ---------------------------------------------------------------------------- //
// MyNewsGroups :) 'Share your knowledge'
// Copyright (C) 2002 Carlos Sánchez Valle (yosoyde@bilbao.com)

// This program is free software; you can redistribute it and/or
// modify it under the terms of the GNU General Public License
// as published by the Free Software Foundation; either version 2
// of the License, or (at your option) any later version.

// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.

// You should have received a copy of the GNU General Public License
// along with this program; if not, write to the Free Software
// Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
// ---------------------------------------------------------------------------- //

//------------------------------------------------------------------//
// iframe.php
// Author: Carlos Sánchez
// Created: 23/06/01
//
// Description: Show the article tree in a iframe. 
//
//
//
//
//------------------------------------------------------------------//
?>
<?
session_start();

include("config.php");
// Tree Menu Required includes
include ("lib/tree/template.inc.php");	
include ("lib/tree/layersmenu.inc.php");


$db=new My_db;
$db->connect();

$db2=new My_db;
$db2->connect();

// MyNG setting up...
init();

// Set up the language
modules_get_language();

// Templates
$t = new Template($_SESSION['conf_system_root']."/themes/".$_SESSION['conf_vis_theme']."/templates/");

// Check the current time
$current_time=time();

$main = "iframe.html";
$t->set_file("main",$main);

// GET Variables
$grp_id = $_GET['grp_id'];
$id_article = $_GET['id_article'];


// Update the Session INFO
$query = "SELECT 
				grp_name,
				grp_last_article,
				grp_first_article,
				grp_num_messages,
				grp_serv_id,grp_allow_post_yn,
				serv_id,
				serv_host 
			FROM myng_newsgroup,myng_server 
			WHERE grp_id ='".$grp_id."' 
			AND grp_serv_id = serv_id";

$db->query($query);
$db->next_record();

$group_name = $db->Record['grp_name'];

$_SESSION['serv_host'] = $db->Record['serv_host'];
$_SESSION['grp_name'] = $group_name;
$_SESSION['grp_last_article'] = $db->Record['grp_last_article'];
$_SESSION['grp_first_article'] = $db->Record['grp_first_article'];
$_SESSION['grp_num_messages'] = $db->Record['grp_num_messages'];
$_SESSION['grp_allow_post_yn'] = $db->Record['grp_allow_post_yn'];


// Get the info from the article to show
$group_name_for_table = real2table($group_name);

// Get the info from the article to show
$group_name_for_table = real2table($group_name);
$query = "SELECT id, id_article, name, subject, body, num_readings, number 
			FROM `myng_".$group_name_for_table."` WHERE id_article='".$id_article."'";

$db->query($query);
$db->next_record();

// We try to fetch the article's parent in order to
// build the thread again.
fetch_parent($db->Record['id'],$group_name,$parent);

// Show the thread of an article
// We pass the '$t' to mix templates and recursive functions

//$depth = 0;
//$article_counter = 0;
//$i=0;

$linea2 = "";
$depth = 0;

show_thread_new($parent,$group_name);

// Count the number of articles of the tree
$num_lines = substr_count($linea2, "\n"); 

if($num_lines > 0){
	$mid = new LayersMenu();
	//$mid->setDirroot("d:/www/MyNewsGroups/");	
	$mid->setLibdir("lib/tree");
	$mid->setImgwww("./themes/".$_SESSION['conf_vis_theme']."/images/tree");
	//$mid->setTreeMenuImagesType("gif");
	$mid->setMenuStructureString($linea2);
	$mid->parseStructureForMenu("treemenu1");
	$mid->newTreeMenu("treemenu1");

	$menu = $mid->getTreeMenu("treemenu1");	
}

$t->set_var("menu",$menu);

// Show all the page
show_iface($main);


?>
