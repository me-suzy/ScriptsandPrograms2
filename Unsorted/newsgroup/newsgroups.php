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
// newsgroups.php
// Author: Carlos Sánchez
// Created: 23/06/01
//
// Description: First page of the system, it shows to us
//              the group list, the description and the
//              number of messages available. It also tell us
//              if there are new messages in a particular group.
//
//
// TODO:        In future versions, we'll check if new articles are
//              available, focusing in each user's account and
//              information.
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


//Registramos el momento actual
$current_time=time();

// Manage the login module
$left_bar = manage_login($current_time,$t,$db);

// Fetch the latest articles
if(!fetch_articles($cron = false)){
	// Redirect to the error page, there're no groups at the system
	header("Location:".$_SESSION['conf_system_prefix']."error.php?error_id=1");	
}

// Get the page number to show
if(isset($_GET['page']) && $_GET['page'] != 0){
	// We assure that the first page is shown if we
	// came from the newsgroups list page
	$page = $_GET['page'];
}else{
	$page = 1;
}


// Check if there are any newsgroups registered
$consulta = sprintf("SELECT * FROM myng_newsgroup ORDER BY grp_name");
$db->query($consulta);
if($db->num_rows() == 0){

	// Redirect to the error page
	header("Location:".$_SESSION['conf_system_prefix']."error.php?error_id=1");		                

}


$news_groups = array();

$consulta = sprintf("SELECT * from myng_server");
$db->query($consulta);

while($db->next_record()){

    // Fetch the server's data
    $serv_id = $db->Record['serv_id'];
    $server = $db->Record['serv_host'];
    $port = $db->Record['serv_port'];
    $login = $db->Record['serv_login'];
    $passwd = $db->Record['serv_passwd'];

    // Get the groups' data
    read_groups($ns,$serv_id,$port,$news_groups,$_SESSION['conf_system_zlib_yn']);

}

$system_info = _MYNGBROWSE_NG;

$main = "newsgroups.htm";
$t->set_file("main",$main);
$t->set_block("main","groups_block","groups_block_handle");
$t->set_block("main","nav_block","nav_block_handle");

// Count the number of groups
$num_elements = sizeof($news_groups);

// Limit the groups to show
$primer_elemento = ($page - 1) * $_SESSION['conf_vis_nav_bar_items'];

// Slice the array of groups
$news_groups = array_slice($news_groups, $primer_elemento, $_SESSION['conf_vis_nav_bar_items']);

show_newsgroups($news_groups,$t);

// Show the navegation bar
navigation_bar($num_elements,$page);

$challenge=md5(uniqid($myng['cadena']));
$t->set_var("secret_challenge",$challenge);

$finish = finish_time($start);
$t->set_var("page_time",$finish);

// Show all the page
show_layout($t,$left_bar,$system_info,MYNG_VERSION);


?>