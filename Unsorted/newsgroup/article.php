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
// article.php
// Author: Carlos Sánchez
// Created: 23/06/01
//
// Description: Show only one article of the thread, and the thread again.
//
//
//
//
//------------------------------------------------------------------//
?>
<?
session_start();

include("config.php");

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

// Manage the login module
$left_bar = manage_login($current_time,$t,$db);

// Fetch the latest articles
if(!fetch_articles($cron = false)){
	// Redirect to the error page, there're no groups at the system
	header("Location:".$_SESSION['conf_system_prefix']."error.php?error_id=1");	
}

$system_info = "Welcome to MyNewsGroups :) v ".MYNG_VERSION;
$main = "article.htm";
$t->set_file("main",$main);

// Blocks
$t->set_block("main","tree_block","tree_block_handle");
$t->set_block("main","body_block","body_block_handle");
$t->set_block("main","mark_all_read_block","mark_all_read_block_handle");

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
$query = "SELECT id, id_article, name, subject, body, num_readings, number 
			FROM `myng_".$group_name_for_table."` WHERE id_article='".$id_article."'";

$db->query($query);
$db->next_record();


// We try to fetch the article's parent in order to
// build the thread again.
fetch_parent($db->Record['id'],$group_name,$parent);

$t->set_var("a_name",$db->Record['name']);
$t->set_var("a_subject",$db->Record['subject']);

$a_reply_url = "post.php?type=reply&id=".$db->Record['number']."&group=".rawurlencode($group_name_for_table)."&grp_id=".$grp_id;
$t->set_var("a_reply_url",modifyLink($a_reply_url));

// Article compression functions
if($_SESSION['conf_system_zlib_yn'] == 'Y'){
        $a_body = gzuncompress($db->Record['body']);
        $a_body = stripslashes($a_body);
}else{
        $a_body = $db->Record['body'];
}

$t->set_var("a_body",$a_body);
$t->set_var("grp_id",$grp_id);

$group_name_real = strtr($group_name,"_",".");

$t->set_var("group_name",$group_name_real);
$t->set_var("id_article",$db->Record['id_article']);
$t->set_var("art_id",rawurlencode($db->Record['id']));

$post_url = "post.php?grp_id=".$grp_id."&newsgroups=".$group_name."&type=new";
$t->set_var("post_url",modifyLink($post_url));

$print_url = "print.php?id_article=".$db->Record['id_article'];
$t->set_var("print_url",modifyLink($print_url));

$friend_url = "friend.php?id_article=".$db->Record['id_article'];
$t->set_var("friend_url",modifyLink($friend_url));

$challenge=md5(uniqid($myng['cadena']));
$t->set_var("secret_challenge",$challenge);

//Page Text
$t->set_var("_myngpost_article",_MYNGPOST_ARTICLE);
$t->set_var("_myngdate",_MYNGDATE);
$t->set_var("_myngsubject",_MYNGSUBJECT);
$t->set_var("_myngauthor",_MYNGAUTHOR);
$t->set_var("_myngreply",_MYNGREPLY);
$t->set_var("_myngsend_friend",_MYNGSEND_FRIEND);
$t->set_var("_myngprint",_MYNGPRINT);
$t->set_var("_myngsave_article",_MYNGSAVE_ARTICLE);
$t->set_var("_myngmark_thread_read",_MYNGMARK_THREAD_READ);

// Update the num_readings stats
$num_readings = $db->Record['num_readings'] + 1;
$consulta2 = "UPDATE `"."myng_".$group_name_for_table."` SET num_readings = '".$num_readings."' WHERE id_article='".$id_article."'";
$db2->query($consulta2);

//--------------- User Login System Value Added Features ---------------//
if(isset($_SESSION['usr_name'])){
        // We're in a session. Must update the reads of each article in the
        // myng_library table.
        $consulta3 = "INSERT IGNORE INTO `myng_library` VALUES('".$db->Record['id_article']."','".$grp_id."','".$_SESSION['usr_id']."','','0')";
        $db2->query($consulta3);
        
        // Mark all read button show
		$t->parse("mark_all_read_block_handle","mark_all_read_block",true);			
}
//----------------------------------------------------------------------//


$finish = finish_time($start);
$t->set_var("page_time",$finish);

// Show the parent's info
$consulta=sprintf("SELECT * FROM `%s` WHERE id='%s'","myng_".$group_name_for_table, $parent);
$db->query($consulta);
$db->next_record();

$url_subject = "article.php?id_article=".$db->Record['id_article']."&grp_id=".$grp_id."&next=".$next;
$url_subject_with_text = "<a class=\"text\" href=\"".modifyLink($url_subject)."\">".$db->Record['subject']."</a>";
$t->set_var("p_subject",$url_subject_with_text);
$c->username = $db->Record['username'];
$c->email = $db->Record['from_header'];
$c->sendername = $db->Record['name'];
$p_sendername = formatAuthor($c);
$t->set_var("p_name",$p_sendername);
$date = date(_MYNGDATEDISPLAY,$db->Record['date']);
$t->set_var("p_date",$date);

// Get the number fo children for this thread
$num_children = is_parent($db->Record['id'],$group_name);

// If zero, don't parse the iframe
if($num_children != 0){
	$t->parse("tree_block_handle","tree_block",true);				
}

// Show all the page
show_layout($t,$left_bar,$system_info,MYNG_VERSION);

?>

