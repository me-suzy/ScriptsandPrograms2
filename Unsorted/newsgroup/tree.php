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
// tree.php
// Author: Carlos Sánchez
// Created: 23/06/01
//
// Description: Thread Page. We show all the 'parents' in a table.
//              We show also the date, the author, the number of
//              articles in the thread, etc.
//
// In:          We need the number of the group to show. We pass
//              it through the URL. Optionally, we get the page number
//				and the date to browse the articles.
//
// Notes: 		Put all the code needed to display the main articles
//				in a function.
//
//------------------------------------------------------------------//
?>
<?
session_start();

include("config.php");


$start = start_time();

$db=new My_db;
$db2 = new My_db;
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

// Manage the login module
$left_bar = manage_login($current_time,&$t,&$db);

// GET Variables
$grp_id = $_GET['grp_id'];
//$group_name = $_GET['group_name'];

// Check if the NewsGroup belongs to the system
$result = testgroup($grp_id);

if($result == 0){
		// Redirect to the error page, unknown group
		header("Location:".$_SESSION['conf_system_prefix']."error.php?error_id=2");					        
}

//--------------------------------------------------------------------------//

$main = "tree.htm";
$t->set_file("main",$main);
$t->set_block("main","nav_block","nav_block_handle");
$t->set_block("main","thread_block","thread_block_handle");
$t->set_block("main","hot_articles_block","hot_articles_block_handle");
$t->set_block("main","hot_articles_TOP_block","hot_articles_TOP_block_handle");
$t->set_block("main","active_user_block","active_user_block_handle");
$t->set_block("main","active_user_TOP_block","active_user_TOP_block_handle");
$t->set_block("main","mark_all_read_block","mark_all_read_block_handle");
$t->set_block("main","unread_block","unread_block_handle");

// Put some additional data into the session.
$consulta = "SELECT 				
				grp_name,
				grp_id,
				grp_last_article,
				grp_first_article,
				grp_num_messages,
				grp_num_available,
				grp_serv_id,
				grp_allow_post_yn,
				serv_id,
				serv_host 
			FROM myng_newsgroup,myng_server 
			WHERE grp_id ='".$grp_id."' 
			AND grp_serv_id = serv_id";

$db->query($consulta);
$db->next_record();

$group_name = $db->Record['grp_name'];
$group_name_for_table = strtr($group_name,".","_");

$num_downloaded = $db->Record['grp_num_messages'];
$num_available = $db->Record['grp_num_available'];


// System info
$system_info = $group_name;

$_SESSION['serv_host'] = $db->Record['serv_host'];
$_SESSION['grp_name'] = $group_name;
$_SESSION['grp_id'] = $db->Record['grp_id'];
$_SESSION['grp_last_article'] = $db->Record['grp_last_article'];
$_SESSION['grp_first_article'] = $db->Record['grp_first_article'];
$_SESSION['grp_num_messages'] = $db->Record['grp_num_messages'];
$_SESSION['grp_allow_post_yn'] = $db->Record['grp_allow_post_yn'];

// Check if browsing through dates
if(isset($_GET['date'])){
	$date = $_GET['date'];
}else{
	$date = "";	
}

// Update navigation bar links with the date
$t->set_var("calendar_day",$date);

// Get the page number to show
if(isset($_GET['begin']) && $_GET['begin'] == 0){
	// We assure that the first page is shown if we
	// came from the newsgroups list page
	$page = remember_page($_GET['begin']);
}else{
	$page = remember_page(1);
}

// -------------- New Navigation Bar Hack!! -----------------//
if($date ==""){
	$query = "SELECT count(*) FROM `"."myng_".real2table($group_name)."` WHERE isanswer='0'";
}else{
	$query = "SELECT count(*) FROM `"."myng_".real2table($group_name)."` WHERE isanswer='0' AND FROM_UNIXTIME(UNIX_TIMESTAMP('".$date."'),'%d') = FROM_UNIXTIME(date,'%d')";	
}
$db->query($query);
$db->next_record();
// Number of elements to show
$num_elements = $db->Record[0];
$t->set_var("group_name_table",$group_name_for_table);
//echo $num_elements.$page.$_SESSION['conf_vis_nav_bar_items'];

navigation_bar($num_elements,$page);

// Para limitar la consulta a la base de datos
$primer_elemento = ($page - 1) * $_SESSION['conf_vis_nav_bar_items'];

$begin = $primer_elemento;



//------------------------------------------------------------//
// Fetch the bastards
// $headers is an array with all the bastard's
// information.

$headers = new articleHeader();

$headers = fetch_bastards($group_name,$begin,$_SESSION['conf_vis_nav_bar_items'],$date);

if(sizeof($headers) == 0){
	header("Location:".$_SESSION['conf_system_prefix']."error.php?error_id=5");	
}

reset($headers);

for($i=0;$i<count($headers);$i++){

	    $c = current($headers);

        $num_children = is_parent($c->id,$group_name);

        $article_url = $c->id_article;
        //$article_url = rawurlencode($article_url);
        $article_url = "article.php?id_article=".$article_url;
        $article_url = $article_url."&grp_id=".$grp_id."&push=1";

        // -- Contributed by Kevin # Show the date in the local way -- //
        $date = date(_MYNGDATEDISPLAY, $c->date);
        //$date = date("M d Y",$c->date);

        $sendername = formatAuthor($c);
        $t->set_var("sender_name",$sendername);
        $t->set_var("date",$date);
        $group_name = strtr($group_name,"_",".");
        $t->set_var("grp_id",$grp_id);
        $t->set_var("article_url",modifyLink($article_url));
        $t->set_var("subject",cut_phrase(htmlspecialchars($c->subject),50));

        //---------------- User Login System Module ------------------ //
        
        // Check if we are in a session
        if(isset($_SESSION['usr_name'])){

                // Try to show the unread articles
                $query = "
                	SELECT lib_art_id 
                	FROM myng_library 
                	WHERE lib_usr_id='".$_SESSION['usr_id']."' 
                	AND lib_grp_id ='".$grp_id."' 
                	AND lib_art_id='".$c->id_article."'";
                $db->query($query);

                if($db->num_rows() == 0){
                        // Unread article, Highlight it!
                        $unread = "yes";
                        $class = "normal_bold";
                        $bg_color = "#FFFFFF";
                }else{
                        $unread="no";
                        $class = "normal";
                        $bg_color = "#eeeeff";
                }
                
                // ## Added by Peter Kronfuss <dreamfire@gmx.at> (15.02.2003)
				// Check if unread articles are in the child-articles
				if ($num_children > 0) {
		    		// Test if Children has an unread article
		    		if (is_childread($c->id,$group_name,$grp_id,$_SESSION['usr_id']) == false) {
						$unread = "yes";
						$class = "normal_bold";
						$bg_color= "#CCE6B5";
					}
				}

                // Try to show the new articles
                $query = "
                	SELECT subs_last_article 
                	FROM myng_subscription 
                	WHERE subs_usr_id='".$_SESSION['usr_id']."' 
                	AND subs_grp_id ='".$grp_id."'";
                $db->query($query);

                if($db->num_rows() != 0){

                        $db->next_record();
                        // Group registered in mygroups.                		                       
                        if( ($db->Record['subs_last_article'] < $c->number) && ($c->number <= $_SESSION['grp_last_article']) ){
                                // This is a new article!
                                //$class = "normal_bold";
                                if($unread == "yes"){
                                        $bg_color = "#FFFFCC";
                                }
                        }

                }else{
                        // Group NOT registered in mygroups.
                        // Do somethign here??
                }
                
        //---------------------------------------------------//

        }else{
                // We are not in a session.
                $class = "normal";
                $bg_color = "#eeeeff";
        }

        $t->set_var("class",$class);
        $t->set_var("bg_color",$bg_color);
        $t->set_var("number",$num_children);

        // --- Oops!! Get this HTML out of here!! ------ //
        if($num_children != 0 ){
                $image = "<img src=".$_SESSION['conf_system_prefix']."themes/".$_SESSION['conf_vis_theme']."/images"."/plus.gif width=9 alt='".$message."' height=9>";
        }else{
                $image="<font class=\"small\" color=\"#E2ECF5\">-</font>";

        }

        $t->set_var("image",$image);
        $t->parse("thread_block_handle","thread_block",true);

        next($headers);

}

//--------------- User Login System Value Added Features ---------------//

// Mark all articles as read 
if(isset($_SESSION['usr_name'])){
	// Show the Mark All Read button
	$t->parse("mark_all_read_block_handle","mark_all_read_block",true);	
		
	/* ADDING BY PETER -> FOR COUNTING UNREAD MESSAGES */	
	$consulta_peter = "SELECT COUNT(*) AS num_read from myng_library WHERE lib_grp_id='".$grp_id."' AND lib_usr_id='".$_SESSION['usr_id']."'";               		
	$db->query($consulta_peter);
	$db->next_record();
	$read_messages_ingroup = $db->Record['num_read'];
	/* END ADDING BY PETER -> FOR COUNTING UNREAD MESSAGES */
	
	/* ADDING BY PETER -> LIST UNREAD MESSAGES */
	$t->set_var("unread_messages_ingroup",$num_downloaded - $read_messages_ingroup);
	$t->set_var("unread_ratio",sprintf("%.2f",(100-($num_downloaded / ($num_downloaded - $read_messages_ingroup)))) );	
	/* END ADDING BY PETER -> LIST UNREAD MESSAGES */
	
	// Show the Unread messages block
	$t->parse("unread_block_handle","unread_block",true);		
	
}else{
	
	$t->set_var("unread_block_handle","");
}

//----------------------------------------------------------------------//

// Statistics code
show_group_hot_articles($group_name, $grp_id);
show_group_active_user($group_name_for_table);

// Calendar Class code
$cal = new MyNGCalendar;
$cal->setStartDay(1);
$grp_name = $group_name;
$cal->getDateLink($day,$month,$year,$group_name);

$d = getdate(time());

if(!isset($_GET['month'])){
    $month = $d["mon"];
}else{
	$month = $_GET['month'];
}

if(!isset($_GET['year'])){
    $year = $d["year"];
}else{
	$year = $_GET['year'];
}

//echo $cal->getMonthView($month, $year);

$t->set_var("calendar",$cal->getMonthView($month, $year));

$t->set_var("group_name",$group_name);
// Group articles stats
$t->set_var("num_downloaded",$num_downloaded);				
+// Round the ratio before display.
$t->set_var("ratio",round(($num_downloaded * 100) / ($num_downloaded + $num_available), 2));				
//$t->set_var("ratio",($num_downloaded * 100) / ($num_downloaded + $num_available));				

$post_url = "post.php?grp_id=".$grp_id."&newsgroups=".$group_name."&type=new";
$t->set_var("post_url",$post_url);				

// Page Text
$t->set_var("_myngsearch_one_group",_MYNGSEARCH_ONE_GROUP);
$t->set_var("_myngpost_article",_MYNGPOST_ARTICLE);
$t->set_var("_myngdate",_MYNGDATE);
$t->set_var("_myngsubject",_MYNGSUBJECT);
$t->set_var("_myngauthor",_MYNGAUTHOR);
$t->set_var("_myngarticles",_MYNGARTICLES);
$t->set_var("_myngprevious",_MYNGPREVIOUS);
$t->set_var("_myngnext",_MYNGNEXT);
$t->set_var("_mynggo",_MYNGGO);
$t->set_var("_myngsubscribe_group",_MYNGSUBSCRIBE_GROUP);
$t->set_var("_mynghot_articles",_MYNGHOT_ARTICLES);
$t->set_var("_myngactive_user",_MYNGACTIVE_USER);
$t->set_var("_myngmark_all_read",_MYNGMARK_ALL_READ);
$t->set_var("_myngstats_articles",_MYNGSTATS_ARTICLES);

$t->set_var("grp_id",$grp_id);

$challenge=md5(uniqid($_SESSION['conf_sec_secret_string']));
$t->set_var("secret_challenge",$challenge);

$finish = finish_time($start);
$t->set_var("page_time",$finish);

// Show all the page
show_layout($t,$left_bar,$system_info,MYNG_VERSION);

?>
