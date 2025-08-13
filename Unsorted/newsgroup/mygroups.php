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
// mygroups.php
// Author: Carlos Sánchez
// Created: 14/01/02
// Last Modified: 06/02/02
//
// Description: NewsGroups Subscription System.
//
//
//------------------------------------------------------------------//
?>
<?
session_start();

include("config.php");

$db = new My_db;
$db2= new My_db;
$db->connect();
$db2->connect();

// MyNG setting up...
init();

// Templates
$t = new Template($_SESSION['conf_system_root']."/themes/".$_SESSION['conf_vis_theme']."/templates/");

// Fetch the latest articles
if(!fetch_articles($cron = false)){
	// Redirect to the error page, there're no groups at the system
	header("Location:".$_SESSION['conf_system_prefix']."error.php?error_id=1");	
}

// Set up the language
modules_get_language();

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


$system_info = _MYNGMY_GROUPS;

$main = "mygroups.htm";
$t->set_file("main",$main);
$t->set_block("main","group2add_block","group2add_block_handle");
$t->set_block("main","groups_block","groups_block_handle");


// Build the groups select form
$query = sprintf("SELECT * FROM myng_newsgroup");
$db->query($query);

while($db->next_record()){
        $t->set_var("grp_name",$db->Record['grp_name']);
        $t->set_var("grp_id",$db->Record['grp_id']);
        $t->parse("group2add_block_handle","group2add_block",true);
        // Catch the group name to add or delete's last_article data
        // (We avoid another query to the database)
        if($db->Record['grp_id'] == $_POST['grp_id']){
            $last_article = $db->Record['grp_last_article'];
        }
}


// Check if we have to add some group
if($_POST['add_group'] && $_POST['sure']){

        $query = sprintf(
        	"INSERT IGNORE INTO myng_subscription (
        		subs_grp_id,
        		subs_usr_id,
        		subs_last_article,
        		subs_last_article_timestamp,
        		subs_posted
        	)VALUES(
        		'".$_POST['grp_id']."',
        		'".$_SESSION['usr_id']."',
        		'".$last_article."',
        		'".time()."',
        		'0'
        	)");
        	
        $db2->query($query);

}

// Check if we have to del some group
if($_POST['del_group'] && $_POST['sure']){

        $query = 
        	"DELETE FROM myng_subscription 
        	WHERE subs_grp_id = '".$_POST['grp_id']."' 
        	AND subs_usr_id='".$_SESSION['usr_id']."'";
        $db2->query($query);

}

// Build 'mygroups' list
$query = sprintf("SELECT * FROM myng_subscription WHERE subs_usr_id='%s'",$_SESSION['usr_id']);
$db->query($query);

$group = new newsGroupType;
$newsGroups = array();

while($db->next_record()){

        $query = sprintf("SELECT * FROM myng_newsgroup WHERE grp_id='%s'",$db->Record['subs_grp_id']);
        $db2->query($query);

        while($db2->next_record()){

                // Number of new articles in this group
                $num_new = ($db2->Record['grp_last_article'] - $db->Record['subs_last_article']);

                $group->id = $db2->Record['grp_id'];
                $group->name = $db2->Record['grp_name'];
                $group->description = $db2->Record['grp_description'];
                $group->count = $db2->Record['grp_num_messages'];
                $group->newArticles = $num_new;
                $group->numPosted = $db->Record['subs_posted'];
                $group->server = $db2->Record['grp_serv_id'];

                // We must update the field 'last_article' in the "myng_subscription" table.
                // But we don't need to update it inmediatedly. User may want to browse
                // the newsgroups first.

                if($num_new > 0 && ($current_time - $db->Record['subs_last_article_timestamp']) > $_SESSION['conf_vis_time_highlight_new']){

                        // There are new articles in this group
                        $query = sprintf("UPDATE myng_subscription SET subs_last_article = '%s',subs_last_article_timestamp='%s' WHERE subs_grp_id = '%s' AND subs_usr_id='%s'", $db2->Record['grp_last_article'], time(),$db->Record['subs_grp_id'], $_SESSION['usr_id']);                       
                        $db2->query($query);

                }

                // Number of articles already read in this group
                $query = "SELECT COUNT(*) from myng_library WHERE lib_grp_id='".$db->Record['subs_grp_id']."' AND lib_usr_id='".$_SESSION['usr_id']."'";               
                $db2->query($query);
                $db2->next_record();
                $num_read = $db2->Record[0];
                $num_unread = ($group->count - $num_read);
                $group->numUnread = $num_unread;


                // We build the array of the newsgroups's data
                array_push($newsGroups,$group);

        }

}

show_newsgroups($newsGroups,$t);


// Page Text

$t->set_var("_myngsubscription_system",_MYNGSUBSCRIPTION_SYSTEM);
$t->set_var("_myngchoose_group",_MYNGCHOOSE_GROUP);
$t->set_var("_myngsure",_MYNGSURE);
$t->set_var("_mynggroups_subscribed",_MYNGGROUPS_SUBSCRIBED);
$t->set_var("_myngarticles",_MYNGARTICLES);
$t->set_var("_mynggroup_name",_MYNGGROUP_NAME);
$t->set_var("_myngdescription",_MYNGDESCRIPTION);
$t->set_var("_myngnew",_MYNGNEW);
$t->set_var("_myngunread",_MYNGUNREAD);
$t->set_var("_myngposted",_MYNGPOSTED);
$t->set_var("_myngadd",_MYNGADD);
$t->set_var("_myngdel",_MYNGDEL);

$finish = finish_time($start);
$t->set_var("page_time",$finish);

// Show all the page
show_layout($t,$left_bar,$system_info,MYNG_VERSION);
?>


