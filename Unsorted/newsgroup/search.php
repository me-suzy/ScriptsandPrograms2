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
// search.php
// Author: Carlos Sánchez
// Created: 05/11/01
//
// Description: Search Page. We can search in the article archive.
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

// Set up the language
modules_get_language();

// MyNG setting up...
init();

// Templates
$t = new Template($_SESSION['conf_system_root']."/themes/".$_SESSION['conf_vis_theme']."/templates/");
//$t2 = new Template($_SESSION['conf_system_root']."/themes/".$_SESSION['conf_vis_theme']."/templates/");

// Fetch the latest articles
if(!fetch_articles($cron = false)){
	// Redirect to the error page, there're no groups at the system
	header("Location:".$_SESSION['conf_system_prefix']."error.php?error_id=1");
}

//$t->debug = true;

//Registramos el momento actual
$current_time=time();

// Manage the login module
$left_bar = manage_login($current_time,$t,$db);

// Check if there are any newsgroups registered
$consulta = sprintf("SELECT * FROM myng_newsgroup");
$db->query($consulta);

if($db->num_rows() == 0){
	
	// Redirect to the error page, there're no groups at the system
	header("Location:".$_SESSION['conf_system_prefix']."error.php?error_id=1");
}

//Check if the query has been submitted

if($_POST['action'] == "search_group"){
	
	// The user threw a query
	// Search Engine Methods
	$group = real2table($_POST['group']);
	$words = $_POST['words'];
	
	// With MySQL indexing, just faster to do a single query that gets both the
	// relevance and the actual articles.  Also no need to ask for grp_id in a join,
	// since it's a constant within each group.
	$query = "SELECT grp_id FROM myng_newsgroup WHERE grp_name='" . $_POST['group'] . "'";
	$db->query($query);
	$db->next_record();
	$grp_id = $db->Record['grp_id'];
	
	$query = sprintf("SELECT id_article,subject,newsgroup,name,date,num_readings, MATCH (body,subject) AGAINST('%s') AS relevance FROM `%s` WHERE MATCH(body,subject) AGAINST('%s') ORDER BY relevance DESC LIMIT 250",addslashes($words), "myng_".$group, addslashes($words));
	$db->query($query);
	
	if ($db->num_rows()) {
		
		// Show the results
		$system_info = _MYNGSEARCH_RESULTS;
		//$main = "result.htm";
		$main = "search.htm";
		$t->set_file("main",$main);
		$t->set_block("main","result_block","result_block_handle");
		$t->set_block("main","result_table_block","result_table_block_handle");
		
		// Page Text
		parse_text($t);
		
		$t->set_var('result_block_handle', '');
		
		unset($result_array);
		
		while($db->next_record()){
			
			$date = date("M d Y",$db->Record['date']);
			$t->set_var("date",$date);
			$article_url = "article.php?id_article=".$db->Record['id_article']."&grp_id=".$grp_id;
			$t->set_var("article_url",modifyLink($article_url));
			$t->set_var("subject",cut_phrase($db->Record['subject'],30));
			$t->set_var("sender",$db->Record['name']);
			$t->set_var("group",$db->Record['newsgroup']);
			$t->set_var("grp_id",$grp_id);
			$t->set_var("readings",$db->Record['num_readings']);
			
			$t->parse("result_block_handle","result_block",true);
			
		}
		
		$t->parse("result_table_block_handle","result_table_block",true);
		
		// Show all the page
		//show_layout($t,$left_bar,$system_info,MYNG_VERSION);
		show_search_interface($left_bar,$error,$db);
		
	}else{
		
		// There's no result.
		// We show the search page again showing the error message
		$error = _MYNGNOARTICLES_FOUND."\"".$words."\"";
		show_search_interface($left_bar,$error,$db);
		
	}
	
}


if($_POST['action']=="search_all"){
	
	$words = $_POST['words'];
	
	$something_found = 0;
	$system_info = _MYNGSEARCH_RESULTS;
	$main = "search.htm";
	$t->set_file("main",$main);
	$t->set_block("main","result_block","result_block_handle");
	$t->set_block("main","result_table_block","result_table_block_handle");
	
	// Page Text
	parse_text($t);
	
	//Fetch all the group's names
	$consulta = sprintf("SELECT * FROM myng_newsgroup");
	$db2->query($consulta);
	
	while($db2->next_record()){
		
		//With each group, we do a normal query.
		$group = $db2->Record['grp_name'];
		$group = real2table($group);
		
		//$query = sprintf("SELECT id_article,subject,newsgroup,name,date,num_readings FROM `%s` WHERE id_article IN (%s)","myng_".$group,join(',',$doc_ids));
		$query = sprintf("SELECT id_article,subject,newsgroup,name,date,num_readings, MATCH (body,subject) AGAINST('%s') AS relevance FROM `%s` WHERE MATCH(body,subject) AGAINST('%s') ORDER BY relevance DESC LIMIT 250",addslashes($words), "myng_".$group, addslashes($words));
		$db->query($query);
		
		if ($db->num_rows()) {
			
			while($db->next_record()){
				
				$date = date(_MYNGDATEDISPLAY,$db->Record['date']);
				$t->set_var("date",$date);
				$article_url = "article.php?id_article=".$db->Record['id_article']."&grp_id=".$db2->Record['grp_id'];
				$t->set_var("article_url",modifyLink($article_url));
				$t->set_var("subject",cut_phrase($db->Record['subject'],30));
				$t->set_var("sender",$db->Record['name']);
				$t->set_var("group",$db->Record['newsgroup']);
				$t->set_var("grp_id",$db2->Record['grp_id']);
				$t->set_var("readings",$db->Record['num_readings']);
				$t->parse("result_block_handle","result_block",true);
				
			}
			
			$something_found = 1;			
			
		}
				
	}
	if($something_found == 0){
			
		//No articles found
		$error = _MYNGNOARTICLES_FOUND." \"".$words."\"";
		show_search_interface($left_bar,$error,$db);
			
	}else{
			
		// Show the results
		//show_layout($t,$left_bar,$system_info,MYNG_VERSION);
		$t->parse("result_table_block_handle","result_table_block",true);
		show_search_interface($left_bar,$error,$db);
	}
	
}
	
if(!isset($_POST['action'])){
	
	//The user didn't throw any query
	//Show the search interface
	$error="";
	show_search_interface($left_bar,$error,$db);
		
}
	
?>

<?
// Script own functions

function show_search_interface($left_bar,$error,&$db){
	
	global $t, $start;
	
	$system_info = _MYNGSEARCH_ENGINE;
	$main = "search.htm";
	$t->set_file("main",$main);
	$t->set_block("main","group_block","group_block_handle");
	$t->set_block("main","result_table_block","result_table_block_handle");
	
	// Build the groups list
	$consulta = sprintf("SELECT * FROM myng_newsgroup");
	$db->query($consulta);
	
	while($db->next_record()){
		$t->set_var("group",$db->Record['grp_name']);
		$t->parse("group_block_handle","group_block",true);
	}
	
	$t->set_var("error",$error);
	
	// Page Text
	$t->set_var("_myngsearch_engine",_MYNGSEARCH_ENGINE);
	$t->set_var("_myngsearch_one_group",_MYNGSEARCH_ONE_GROUP);
	$t->set_var("_myngsearch_all_groups",_MYNGSEARCH_ALL_GROUPS);
	$t->set_var("_myngand_operation",_MYNGAND_OPERATION);
	$t->set_var("_myngpowered_by",_MYNGPOWERED_BY);
	$t->set_var("_mynggo",_MYNGGO);
	
	$finish = finish_time($start);
	$t->set_var("page_time",$finish);
	
	// Show all the page
	show_layout(&$t,$left_bar,$system_info,MYNG_VERSION);
	
}


function parse_text(&$t){
	
	$t->set_var("_myngsearch_results",_MYNGSEARCH_RESULTS);
	$t->set_var("_myngdate",_MYNGDATE);
	$t->set_var("_myngsubject",_MYNGSUBJECT);
	$t->set_var("_myngauthor",_MYNGAUTHOR);
	$t->set_var("_mynggroup",_MYNGGROUP);
	$t->set_var("_myngreadings",_MYNGREADINGS);
	
}

?>