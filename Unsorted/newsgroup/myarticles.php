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
// myarticles.php
// Author: Carlos Sánchez
// Created: 14/01/02
//
// Description: Articles saved by the user.
//
//
//------------------------------------------------------------------//
// $id$
//------------------------------------------------------------------//
?>
<?
session_start();

include("config.php");

$db=new My_db;
$db2 = new My_db;
$db->connect();
$db2->connect();

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
if($_SESSION['conf_system_login_yn']=="Y"){


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


$system_info = _MYNGMY_ARTICLES;

$main = "myarticles.htm";
$t->set_file("main",$main);

$t->set_block("main","articles_block","articles_block_handle");

// POST variables
$id_article = $_POST['id_article'];
$grp_id = $_POST['grp_id'];

// Check if we have to del some article from myarticle list
if($_POST['del_article'] && $_POST['sure']){

        $query = "
        	UPDATE myng_library 
        	SET lib_my_article = '0'
        	WHERE lib_art_id = '".$id_article."'
        	AND lib_usr_id='".$_SESSION['usr_id']."'
        	AND lib_grp_id = '".$grp_id."'";
        $db2->query($query);

}

// Check if we have to add some article
if($_POST['add_article'] && $_POST['sure']){
	
	    $query = "
	    	UPDATE myng_library 
	    	SET lib_my_article = '1' 
	    	WHERE lib_art_id = '".$id_article."' 
	    	AND lib_usr_id='".$_SESSION['usr_id']."' 
	    	AND lib_grp_id = '".$grp_id."'";

        $db2->query($query);
        
}

// Build the my_articles list
$query = "
	SELECT grp_serv_id, grp_name, lib_art_id, lib_grp_id 
	FROM myng_library,myng_newsgroup 
	WHERE lib_usr_id='".$_SESSION['usr_id']."' 
	AND lib_my_article='1' 
	AND grp_id = lib_grp_id ";
$db->query($query);

while($db->next_record()){

        // Fetch the info for each article
        $consulta = sprintf("SELECT date,subject,name,id_article FROM myng_%s WHERE id_article='%s'",real2table($db->Record['grp_name']),$db->Record['lib_art_id']);
        $db2->query($consulta);

        while($db2->next_record()){

                 $date = date(_MYNGDATEDISPLAY,$db2->Record['date']);
                $t->set_var("date",$date);
                $t->set_var("id_article",$db->Record['lib_art_id']);
                $t->set_var("sender",$db2->Record['name']);
                $t->set_var("subject",$db2->Record['subject']);

                $group_url = "tree.php?grp_id=".$db->Record['lib_grp_id'];
                $t->set_var("group_url",modifyLink($group_url));

                $article_url = "article.php?id_article=".$db2->Record['id_article']."&grp_id=".$db->Record['lib_grp_id'];
                $t->set_Var("article_url",modifyLink($article_url));

                $t->set_var("grp_id",$db->Record['lib_grp_id']);
                $t->set_var("grp_name",$db->Record['grp_name']);
                $t->parse("articles_block_handle","articles_block",true);

        }

}



// Page Text

$t->set_var("_myngarticles_saved",_MYNGARTICLES_SAVED);
$t->set_var("_myngdate",_MYNGDATE);
$t->set_var("_myngsubject",_MYNGSUBJECT);
$t->set_var("_myngauthor",_MYNGAUTHOR);
$t->set_var("_mynggroup",_MYNGGROUP);
$t->set_var("_myngdel",_MYNGDEL);


// Show all the page
show_layout($t,$left_bar,$system_info,MYNG_VERSION);
?>


