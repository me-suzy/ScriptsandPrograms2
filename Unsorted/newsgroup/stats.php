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
// stats.php
// Author: Carlos Sánchez
// Created: 06/09/01
// Last Modified: 14/09/01
//
// Description: Shows different statistics of the system.
//
//
//------------------------------------------------------------------//
?>
<?
session_start();

include("config.php");

$db=new My_db;
$db->connect();

// MyNG setting up...
init();

// Set up the language
modules_get_language();

// Templates
$t = new Template($_SESSION['conf_system_root']."/themes/".$_SESSION['conf_vis_theme']."/templates/");

// Fetch the latest articles
if(!fetch_articles($cron = false)){
	// Redirect to the error page, there're no groups at the system
	header("Location:".$_SESSION['conf_system_prefix']."error.php?error_id=1");	
}

$current_time=time();

// Manage the login module
$left_bar = manage_login($current_time,$t,$db);

// Check if there are any newsgroups registered
$consulta = sprintf("SELECT * FROM myng_newsgroup");
$db->query($consulta);
if($db->num_rows() == 0){

    // Redirect to the error page
	header("Location:".$_SESSION['conf_system_prefix']."error.php?error_id=1");		                

}

$challenge=md5(uniqid($_SESSION['conf_sec_secret_string']));
$t->set_var("secret_challenge",$challenge);

$system_info = _MYNGMENU_STATS;
$main = "stats.htm";
$t->set_file("main",$main);


$t->set_block("main","hot_groups_block","hot_groups_block_handle");
$t->set_block("main","hot_groups_TOP_block","hot_groups_TOP_block_handle");
$t->set_block("main","active_user_block","active_user_block_handle");
$t->set_block("main","active_user_TOP_block","active_user_TOP_block_handle");
$t->set_block("main","crowded_groups_block","crowded_groups_block_handle");
$t->set_block("main","crowded_groups_TOP_block","crowded_groups_TOP_block_handle");
$t->set_block("main","argumentative_user_block","argumentative_user_block_handle");
$t->set_block("main","argumentative_user_TOP_block","argumentative_user_TOP_block_handle");
$t->set_block("main","hot_articles_block","hot_articles_block_handle");
$t->set_block("main","hot_articles_TOP_block","hot_articles_TOP_block_handle");
$t->set_block("main","popular_article_block","popular_article_block_handle");
$t->set_block("main","popular_article_TOP_block","popular_article_TOP_block_handle");
$t->set_block("main","popular_groups_block","popular_groups_block_handle");
$t->set_block("main","popular_groups_TOP_block","popular_groups_TOP_block_handle");

//----------- Stats Code ------------------//

$db = new My_db;
$db2 = new My_db;
$db->connect();

//---------------- Hot NewsGroups -------------//
if($_GET['stat_id'] == "hot_groups"){
		
	show_hot_groups();
	
}

//------------ Popular NewsGroups -------------//
if($_GET['stat_id'] == "popular_groups"){

	show_popular_groups();

}

//------------- Crowded NewsGroups ----------------//
if($_GET['stat_id'] == "crowded_groups"){

	show_crowded_groups();

}

//----------------- Hot articles --------------------//

if($_GET['stat_id'] == "hot_articles"){

	show_hot_articles();
  
}

//---------------- Popular Articles -------------------//

if($_GET['stat_id'] == "popular_articles"){

	show_popular_articles();

}


//------------------- Active User --------------------//

if($_GET['stat_id'] == "active_user"){

	show_active_user();
	
}

//--------------- Argumentative User -------------------//

if($_GET['stat_id'] == "reply_user"){
	
	show_reply_user();
	
}



// Page Text
$t->set_var("_myngstats",_MYNGMENU_STATS);
$t->set_var("_mynghot_ng",_MYNGHOT_NG);
$t->set_var("_myngpopular_ng",_MYNGPOPULAR_NG);
$t->set_var("_myngcrowded_ng",_MYNGCROWDED_NG);
$t->set_var("_mynghot_articles",_MYNGHOT_ARTICLES);
$t->set_var("_myngpopular_articles",_MYNGPOPULAR_ARTICLES);
$t->set_var("_myngactive_user",_MYNGACTIVE_USER);
$t->set_var("_myngarg_user",_MYNGARG_USER);
$t->set_var("_myngstats_articles",_MYNGSTATS_ARTICLES);
$t->set_var("_myngstats_readings",_MYNGSTATS_READINGS);
$t->set_var("_myngusers",_MYNGUSERS);
$t->set_var("_myngpostings",_MYNGPOSTINGS);
$t->set_var("_myngreplies",_MYNGREPLIES);

$finish = finish_time($start);
$t->set_var("page_time",$finish);

// Show all the page
show_layout($t,$left_bar,$system_info,MYNG_VERSION);



?>
