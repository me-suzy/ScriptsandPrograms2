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
// my.php
// Author: Carlos Sánchez
// Created: 14/01/02
// Last Modified: 06/02/02
//
// Description: Home Page of the user.
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

//Registramos el momento actual
$current_time=time();

// Check if the login system is activated or not
if($_SESSION['conf_system_login_yn']="Y"){


	if(isset($_SESSION['usr_name'])){
	
        	//--------- We are in a session! -------------------------------//
	
        	// Create an user instance
        	$user = new User($_SESSION['usr_name']);
        	// Check the online status and update the timestamps
        	$user->is_online();
        	// Clean the people_online table
        	clean_people_online($db,$current_time);
        	// Show the new interface (online)		
			$left_bar = manage_login($current_time,$t,$db); 
        	//$left_bar = "my_bar.htm";
        	$t->set_var("name",$user->id_user);
        	$t->set_var("email",$user->email);


	}else{
	        //-------- User not logged ------------------------------------//
	        
	        // Redirect to the error page
			header("Location:".$_SESSION['conf_system_prefix']."error.php?error_id=3");		                
				        
	}

}else{

   // Redirect to the error page
   header("Location:".$_SESSION['conf_system_prefix']."error.php?error_id=3");		                

}


$system_info = "My Home Page";

$main = "my.htm";
$t->set_file("main",$main);

$t->set_var("id_user",$_SESSION['usr_name']);

if($_SESSION['conf_sec_protect_email_yn'] == 'Y'){
	$t->set_var("email",protect_email($_SESSION['usr_email']));
}else{
    $t->set_var("email",$_SESSION['usr_email']);
}


$t->set_var("country",$_SESSION['usr_country']);

// Page Text

$t->set_var("_myngwelcome_user",_MYNGWELCOME_USER);
$t->set_var("_myngpost",_MYNGPOST);
$t->set_var("_myngreply",_MYNGREPLY);
$t->set_var("_myngsave",_MYNGSAVE);
$t->set_var("_myngstats",_MYNGSTATS);
$t->set_var("_myngsubscribe",_MYNGSUBSCRIBE);
$t->set_var("_myngoptions",_MYNGOPTIONS);
$t->set_var("_myngprofile",_MYNGPROFILE);
$t->set_var("_myngnew_articles",_MYNGNEW_ARTICLES);
$t->set_var("_myngto_articles",_MYNGTO_ARTICLES);
$t->set_var("_myngedit_your",_MYNGEDIT_YOUR);
$t->set_var("_myngto_groups",_MYNGTO_GROUPS);
$t->set_var("_myngyour_articles",_MYNGYOUR_ARTICLES);
$t->set_var("_mynguser_name",_MYNGUSER_NAME);
$t->set_var("_myngemail",_MYNGEMAIL);
$t->set_var("_myngcountry",_MYNGCOUNTRY);
$t->set_var("_myngview_your",_MYNGVIEW_YOUR);
$t->set_var("_mynghome",_MYNGHOME);

$finish = finish_time($start);
$t->set_var("page_time",$finish);

// Show all the page
show_layout($t,$left_bar,$system_info,MYNG_VERSION);
?>


