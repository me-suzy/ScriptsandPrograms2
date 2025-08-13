<?
/* ----------------------------------------------------------------------------
MyNewsGroups :) 'Share your knowledge'
Copyright (C) 2002 Carlos Sánchez Valle (yosoyde@bilbao.com)

This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
-----------------------------------------------------------------------------*/

//------------------------------------------------------------------//
// standard.lib.php
//
// Author: Carlos Sánchez
// Created: 23/06/01
//
// Description: Core functions of the MyNewsgroups System.
//
// Notes:
//
// Acknowledgements:
//
// - Bug From field without addslashes fixed by Jordan Russell
//
//------------------------------------------------------------------//

//----------- Functions in standard.lib.php ---------------//
//
// init
// del_group
// add_server
// del_server
// del_articles
// read_groups
// fetch_bastards
// is_parent
// expand_thread
// show_thread
// real2table
// table2real
// readHeader_from_DB
// readBody_from_DB
// show_layout
// clean_people_online
// protect_email
// manage_login
// show_newsgroups
// fetch_articles
// build_newsgroups_list
// update_activity_index
// navegation_bar

// Some other NewsPortal modified functions.
//-------------------------------------------------//



//--------------------------------------------------//
// Name:        init
// Task:        Initialize the Configuration Environment
// Description: We query the database put all the configuration
//				information into the session.
//
// In:          
// Out:         
//
//--------------------------------------------------//

function init(){

	global $db;
	
	// --------- v 0.5 Special Initialization ---------- //
	// This init script must be run only once per session.

	// Get the default Configuration Variables
	if (!isset($_SESSION['conf'])) {
		// Get the configuration from the DB and put them in the Session	
		$query = "SELECT * FROM myng_config WHERE conf_active_yn = 'Y'";
		$db->query($query);

		if($db->next_record()){	
								
			// System
			$_SESSION['conf_system_prefix'] = $db->Record['conf_system_prefix'];
			$_SESSION['conf_system_root'] = $db->Record['conf_system_root'];
			$_SESSION['conf_system_language'] = $db->Record['conf_system_language'];
			$_SESSION['conf_system_zlib_yn'] = $db->Record['conf_system_zlib_yn'];
			$_SESSION['conf_system_debug_yn'] = $db->Record['conf_system_debug_yn'];
			$_SESSION['conf_system_login_yn'] = $db->Record['conf_system_login_yn'];
			$_SESSION['conf_system_online_yn'] = $db->Record['conf_system_online_yn'];			
			// Download
			$_SESSION['conf_down_days'] = $db->Record['conf_down_days'];
			$_SESSION['conf_down_list_items'] = $db->Record['conf_down_list_items'];
			$_SESSION['conf_down_num_articles'] = $db->Record['conf_down_num_articles'];
			$_SESSION['conf_down_num_groups'] = $db->Record['conf_down_num_groups'];			
			// Clean Up
			$_SESSION['conf_clean_MAX_days'] = $db->Record['conf_clean_MAX_days'];
			$_SESSION['conf_clean_MAX_articles'] = $db->Record['conf_clean_MAX_articles'];			
			// Visualization
			$_SESSION['conf_vis_theme'] = $db->Record['conf_vis_theme'];
			$_SESSION['conf_vis_num_2_flames'] = $db->Record['conf_vis_num_2_flames'];
			$_SESSION['conf_vis_articles_x_page'] = $db->Record['conf_vis_articles_x_page'];
			$_SESSION['conf_vis_nav_bar_items'] = $db->Record['conf_vis_nav_bar_items'];	
			$_SESSION['conf_vis_nav_bar_pages'] = $db->Record['conf_vis_nav_bar_pages'];
			$_SESSION['conf_vis_time_highlight_new'] = $db->Record['conf_vis_time_highlight_new'];
			// Security
			$_SESSION['conf_sec_protect_email_yn'] = $db->Record['conf_sec_protect_email_yn'];
			$_SESSION['conf_sec_send_poster_host_yn'] = $db->Record['conf_sec_send_poster_host_yn'];
			$_SESSION['conf_sec_test_group_yn'] = $db->Record['conf_sec_test_group_yn'];
			$_SESSION['conf_sec_validate_email_yn'] = $db->Record['conf_sec_validate_email_yn'];
			$_SESSION['conf_sec_secret_string'] = $db->Record['conf_sec_secret_string'];
		}

    	$_SESSION['conf'] = $db->Record['conf_name'];;   
    	
	}else{	
		// Nothing yet.
		// Put default values??
	} 

	
}



//--------------------------------------------------//
// Name:        del_group
// Task:        Delete a NewsGroup from the System.
// Description: We query the database and Delete all the entries
//              related to the group, in all the tables.
//
// In:          $group_name - Name of the newsgroup
// Out:         $result
//
//--------------------------------------------------//

function del_group($grp_id,$grp_name){

        $db=new My_db;
        $db->connect();

        $group_name_for_table = real2table($grp_name);

        $ref_table = "myng_ref_".$group_name_for_table;
        $doclist_table = "myng_".$group_name_for_table."_doclist";
        $stoplist_table = "myng_".$group_name_for_table."_stoplist";
        $vectorlist_table = "myng_".$group_name_for_table."_vectorlist";
        $wordlist_table =  "myng_".$group_name_for_table."_wordlist";

        // Table myng_newsgroups
        $consulta = sprintf("DELETE FROM myng_newsgroup WHERE grp_name='%s'",$grp_name);
        //echo $consuta;
        $db->query($consulta);
        // Table myng_subscription
        $consulta = sprintf("DELETE FROM myng_subscription WHERE subs_grp_id='%s'",$grp_id);
        $db->query($consulta);
        // Table myng_library
        $consulta = sprintf("DELETE FROM myng_library WHERE lib_grp_id='%s'",$grp_id);
        $db->query($consulta);
        // Table myng_<group-name>
        $consulta = sprintf("DROP TABLE `%s` ","myng_".$group_name_for_table);
        $db->query($consulta);
        // Table myng_ref<group-name>
        $consulta = sprintf("DROP TABLE `%s`",$ref_table);
        $db->query($consulta);
        

}// End of function



//-------------------------------------------------------//
// Name:        add_server
// Task:        Add a new NewsGroup to the System.
// Description: We add a new row in the 'server' table
//
// In:          $host,$port,$login,$passwd
// Out:         $result
//
//-------------------------------------------------------//

function add_server($host,$port,$login,$passwd){

        $db=new My_db;
        $db->connect();
        $consulta = sprintf("INSERT IGNORE INTO myng_server VALUES('%s','%s','%s','%s')",$host,$port,$login,$passwd);     //Build the query
        //echo $consulta;
        $db->query($consulta);

}// End of function



//--------------------------------------------------//
// Name:        del_server
// Task:        Delete a server from the System.
// Description: We query the database and Delete all the entries
//              related to the server, in all the tables.
//
// In:          $host - Name of the server
//
//--------------------------------------------------//

function del_server($host){

        $db=new My_db;
        $db->connect();
        $consulta = sprintf("DELETE FROM myng_server WHERE host='%s'",$host);
        $db->query($consulta);

        //BEWARE!! We must delete all the groups of the server!!!
        $consulta = sprintf("SELECT group_name FROM myng_newsgroup WHERE server='%s'",$host);
        $db->query($consulta);
        while($db->next_record()){
                $group_to_delete = $db->Record['group_name'];
                del_group($group_to_delete);
        }

}// End of function



//--------------------------------------------------//
// Name:        del_articles
// Task:        Delete some articles from a group.
// Description: We query the database and Delete all the
//              oldest N articles.
//
// In:          $num_articles - Number of articles to delete
//              $group_name - Group to delete from
//
// Known Problems:      If we delete the parent article from a thread,
//                      the whole thread seem to dissapear completely from
//                      the tree.php page. The children of that article are
//                      still in the DB, but hidden for the user. Anyway,
//                      the next time somebody delete more articles, they will
//                      be deleted also.
//
//--------------------------------------------------//

function del_articles($num_articles,$group_name){

        $db=new My_db;
        $db->connect();

        // Get the group's data
        $consulta = "SELECT * FROM myng_newsgroup WHERE grp_name = '".$group_name."'";
        $db->query($consulta);
        $db->next_record();

        $last = $db->Record['grp_last_article'];
        $first = $db->Record['grp_first_article'];
        $num = $db->Record['grp_num_messages'];

        // Check if $num_articles is bigger than the number of available articles !!!

        if($num_articles > $num ){
                // We must delete all the articles of the newsgroup
                $num_articles = $num;
                if($num_articles == 0){
                        // We don't do nothing
                        return;
                }
        }        

        // Create the list of articles to retrieve his id_article from the DB
        $number_list = range($first,($first + $num_articles)-1);
        $list = $number_list[0];
        for($i=1;$i<sizeof($number_list);$i++){
                $list.=",".$number_list[$i];
        }

        // Query to get the id_article
        $consulta = sprintf("SELECT id,id_article,subject FROM `%s` WHERE number IN (%s)","myng_".real2table($group_name), $list);
        //echo $consulta;
        $db->query($consulta);
        

        // Create the array for deleting from the index
        $id=array();
        $l=0;
        $id_list = "'";
        $id_article_list = "";
        while($db->next_record()){
                $id[$l] = $db->Record['id_article'];
                $id_list = $id_list.$db->Record['id']."','";
                $id_article_list = $id_article_list.$db->Record['id_article'].",";
                $l++;
        }
        // Delete the last ',' of the id_list
        $id_list = substr($id_list, 0, strlen($id_list)-2);
        $id_article_list = substr($id_article_list, 0, strlen($id_article_list)-1);        
        
        
        // -------------- Delete the articles for that group -----------------------//
        $consulta = "DELETE FROM myng_".real2table($group_name)." WHERE number < ".($first + $num_articles);
        //echo $consulta;
        $db->query($consulta);

        // --------------- Update the myng_newsgroup table -------------------------//
        $consulta = "UPDATE myng_newsgroup SET grp_first_article = '".($first + $num_articles)."', grp_num_messages = '".($num - $num_articles)."' WHERE grp_name = '".$group_name."'";
        //echo $consulta;
        $db->query($consulta);

        // ---------------------- Delete the entries in the Ref Tables ! ----------------------------//

        $consulta = "DELETE FROM myng_ref_".real2table($group_name)." WHERE id_article IN (".$id_list.")";
        //echo $consulta;
        $db->query($consulta);

        // ----------------------- Delete the entries in the Library ! ------------------------------//
        $consulta = "DELETE FROM myng_library WHERE lib_art_id IN (".$id_article_list.")";
        $db->query($consulta);


}// End of function




//------------------------------------------------//
// Name:        read_groups
// Description: This function tries to read all the group
//              data from the DB, and then try to fill
//              the array newsgroups with instances
//              of newsgroupType objets. The function gets
//              the description of the group, the number
//              of messages available, and the number
//              of new messages.
//
// In:          $server,$port
// Out:         $newsgroups
//------------------------------------------------//

function read_groups(&$ns,$serv_id,$port,&$news_groups,$zip_articles) {

        //global $t,$main;


        $db=new My_db;
        $db->connect();

        $consulta = sprintf("SELECT * FROM myng_newsgroup WHERE grp_serv_id ='%s' ORDER BY grp_name",$serv_id);
        //echo $consulta;
        $db->query($consulta);

        while($db->next_record()){

                $groupname = $db->Record['grp_name'];

                // Insert an algorithm to prevent News Server 'bombing'
                /*
                srand((double)microtime()*1000000);
                $random_number = mt_rand(1,10);
                */
                /*
                if($ns != false){

                        //----------- Prepare the Search Engine to index -----------//
                        $kw->table_name = real2table($groupname);

                        // We query the News Server
                        $new_articles = check_new_articles($ns,$groupname,$db->Record['last_article'],$db->Record['num_messages'],$zip_articles);
                }else{
                        // We don't query the News Server
                        $new_articles = 0;
                }
                */
                //$new_articles = check_new_articles($ns,$groupname,$db->Record['last_article'],$db->Record['num_messages']);
                //echo $new_articles;

                // Setting off the old downloading method
                $new_articles = 0;
                $group = new newsGroupType;
                $group->id = $db->Record['grp_id'];
                $group->name = $db->Record['grp_name'];
                $group->description = $db->Record['grp_description'];
                $group->count = $db->Record['grp_num_messages'] + $new_articles;
                $group->newArticles = $new_articles;
                $group->server = $server;
                $group->allow_post = $db->Record['grp_allow_post_yn'];
                // We bouild the array of the newsgroups's data
                array_push($news_groups,$group);

        }//Fin del while

        //return $newsgroups;
}





//------------------------------------------------------------------//
// Name:        fetch_bastards XD
// Task:        Fetch from the DB the articles without parents
// Description: We get the articles where the isAnswer flag is '0'
//              from the DB.
//
// In:          $group_name - Name of the newsgroup
//				$date - If we want only articles from a date
// Out:         $headers - Array of 'articleHeader' objects
//
//
//-----------------------------------------------------------------//
function fetch_bastards($group_name,$begin,$articles_per_page,$date){
		
        $article_header = new articleHeader;
        //We just have to connect with the DB and do some queries.

        $db=new My_db;
        $db->connect();
        
        // Beware! The name of the table is the same as the group name.
        // This is an IMPORTANT fact. We do this to optimize the queries.
        // We CANNOT have a table storing all the articles of all the newsgroups.

        // The name of the table cannot have a dot,'.', so we need to substitute the
        // dots with '_' before building the query.
        // es.comp.linux -> es_comp_linux

        $group_name = real2table($group_name);
        
        //echo $date;
        
        if($date == ""){
        	$query = "
        		SELECT number, username, from_header, subject, id, id_article, date, name 
        		FROM `myng_".$group_name."` 
        		WHERE isanswer=0 
        		ORDER BY NUMBER DESC LIMIT ".$begin.",".$articles_per_page;        	        
        	
        }else{        	

	        $date_min = strtotime($date); 
            $date_max = strtotime("$date +1 day");

        	$query = "
	        	SELECT number, username, from_header, subject, id, id_article, date, name 
        		FROM `myng_".$group_name."` 
        		WHERE isanswer=0 AND date >='$date_min' AND date <'$date_max'
        		ORDER BY NUMBER DESC LIMIT ".$begin.",".$articles_per_page;
        }           
        
        $db->query($query);

        while($db->next_record()){
                // Build the $article_header object
                $article_header->date = $db->Record['date'];
                $article_header->sendername = $db->Record['name'];
                $article_header->username = $db->Record['username'];
                $article_header->email = $db->Record['from_header'];
                $article_header->subject = $db->Record['subject'];
                $article_header->number = $db->Record['number'];
                $article_header->id = $db->Record['id'];
                $article_header->id_article = $db->Record['id_article'];
                // Build the array
                $headers[] = $article_header;
        }

        return $headers;
}





//------------------------------------------------------------------//
// Name:        is_parent
// Task:        Check if the article has any children
// Description: Try to get the number of children the article has.
//              That is, the number of articles in the thread.
//              It'll tell us if an article is hot or not.
//
// In:          $id - id of the article
// Out:         $num_children - number of children (if 0, no children)
//
//
//-----------------------------------------------------------------//

function is_parent($id,$group_name){

        // With the 'parent' field of the table, we could count
        // how many 'direct' children it has, but not the number of
        // articles in that thread.
        // I think that we can calculate this counting the number
        // of articles that are referencing to this 'id'

        $group_name = real2table($group_name);

        $ref_table = "ref_".$group_name;

        $db=new My_db;
        $db->connect();
        // Beware!! Like in the previous function, we need a table for each group
        // We could call each table 'ref_name_of_the_group'
        $consulta = sprintf("SELECT count(*) FROM `%s` WHERE reference='%s'","myng_".$ref_table,$id);
        $db->query($consulta);
        $db->next_record();
        $num_children = $db->Record[0];

        return $num_children;

}


// ## Added by Peter Kronfuss <dreamfire@gmx.at> (15.02.2003)
//------------------------------------------------------------------//
// Name:        is_childread
// Task:        Check if the child articles have been read by the user
// Description: Check if a child article has been read by the user
//				so you can highlight it in the message tree.
//
// In:          $id - id of the article
//				$group_name - Name of the Group
//				$group_id - id of the Group
//				$user_id - User id to check for
// Out:         $subread - false if one or more messages have not
//				been read
//
//
//-----------------------------------------------------------------//

function is_childread($id,$group_name,$group_id,$user_id){
	    
		$group_name = real2table($group_name);
        $ref_table = "ref_".$group_name;

        $db=new My_db;
        $db->connect();
		$db2=new My_db;
		$db2->connect();
        // $consulta = sprintf("SELECT id_article FROM `%s` WHERE reference='%s'","myng_".$ref_table,$id);
		$query = sprintf("SELECT a.id_article, b.id_article FROM `%s` AS a, `%s` AS b WHERE a.reference='%s' AND a.id_article = b.id","myng_".$ref_table,"myng_".$group_name,$id);
        $db->query($query);
		$subread=true;
		while ($db->next_record()){
			$query = "
                	SELECT lib_art_id 
                	FROM myng_library 
                	WHERE lib_usr_id='".$user_id."' 
                	AND lib_grp_id ='".$group_id."' 
                	AND lib_art_id='".$db->Record['id_article']."'";
            $db2->query($query);
			if($db2->num_rows() == 0){
				// Unread article
				$subread=false;
			}
		}
		return $subread;
}



//------------------------------------------------------------------//
// Name:        fetch_parent
// Task:        Try to find the article's top parent.
//
// Description: Find the parent of the complete thread.
//
// In:          $id - id of the article
//              $group_name
//              $parent
//
// Out:         $parent - id of the parent
//
//-----------------------------------------------------------------//

function fetch_parent($id,$group_name,&$parent){

        $group_name = real2table($group_name);

        $db=new My_db;
        $db->connect();

        $consulta = sprintf("SELECT parent,id FROM `%s` WHERE id='%s'","myng_".$group_name,$id);   
        $db->query($consulta);

        $db->next_record();

        if($db->Record['parent'] != ""){

                fetch_parent($db->Record['parent'],$group_name,$parent);

        }else{
                $parent = $id;
        }

}





//------------------------------------------------------------------//
// Name:        expand_thread
// Task:        Builds the complete thread of an article with replies.
// Description: Fetch all the necessary data to build the thread of
//              an article with replies.
//
//
// In:
// Out:
//
//
//-----------------------------------------------------------------//

function expand_thread($parent,$group_name,&$t,&$depth,$zip_articles,$server){

        $group_name_for_table = real2table($group_name);

        $db=new My_db;
        $db2=new My_db;
        $db->connect();
        $consulta=sprintf("SELECT * FROM `%s` WHERE parent='%s' ORDER BY date ","myng_".$group_name_for_table, $parent);
        //echo $consulta;
        $db->query($consulta);

        // Ponemos el primer TAG
        //echo "<UL>";
        //echo $db->num_rows();

        //Si esa categoria tiene hijos
        if($db->num_rows()!= 0){

                $depth ++;
                $linea="";
                for($i=0;$i<$depth;$i++){
                        $linea = "&nbsp;&nbsp;&nbsp;&nbsp;".$linea;
                        //echo $linea;
                }
                //echo $depth;
                if($depth > 3){
                                $li_number = ($depth % 3);
                                if($li_number == 0){
                                        $li_number = 3;
                                }

                        }else{
                                $li_number = $depth;
                        }
                $flag = 0;

                while($db->next_record()){

                        // Use the id of the article for the anchors.
                        $i = $db->Record['id'];

                        // The 'li_image' depends on the depth of the article
                        // The color depends if it's the first article or the second
                        if($flag == "0"){
                                 $color = "r";
                        }else{
                              $color = "n";
                        }
                        $flag = 1;

                        $li_image = "li".$li_number.$color.".gif";

                        $linea2 = $linea."<img src=images/".$li_image." width=5 height=5>&nbsp;"."<a class=\"text\" href=\"#".$i."\">".$db->Record['subject']."</a>";

                        $reply_url = "post.php?type=reply&id=".$db->Record['number']."&group=".rawurlencode($group_name)."&server=".$server;


                        //echo "<br>".$linea;
                        $t->set_var("subject",$linea2);
                        $t->set_var("link_name",$i);
                        $c->username = $db->Record['username'];
                        $c->email = $db->Record['from_header'];
                        $c->sendername = $db->Record['name'];
                        $sendername = formatAuthor($c);
                        $t->set_var("name",$sendername);
                        // -- Contributed by Kevin # Show the date in the local way -- //
                        $date = date(_MYNGDATEDISPLAY,$db->Record['date']);
                        //$date = date("M d Y",$db->Record['date']);
                        $t->set_var("date",$date);

                        // Article compression functions
                     if($_SESSION['conf_system_zlib_yn'] == 'Y'){
                                $body = gzuncompress($db->Record['body']);
                                $body = stripslashes($body);
                        }else{
                                $body = $db->Record['body'];
                        }

                        $t->set_var("body",$body);
                        $t->set_var("reply_url",modifyLink($reply_url));
                        $t->parse("tree_block_handle","tree_block",true);
                        $t->parse("body_block_handle","body_block",true);





                        //$depth -- ;
                        expand_thread($db->Record['id'],$group_name,&$t,&$depth,$zip_articles,$server); //Recursive function!

                }

        }else{

                          // Update the value of 'num_readings'
                $num_readings = $db->Record['num_readings'] + 1;
                $consulta2 = sprintf("UPDATE `%s` SET num_readings = '%s' WHERE id='%s'","myng_".$group_name_for_table,$num_readings,$db->Record['id']);
                //echo $consulta2."<br>";
                $db2->query($consulta2);

                if(session_is_registered("Session")){

                        // We're in a session. Must update the reads of each article in the
                        // myng_library table.
                        $consulta3 = sprintf("INSERT INTO `myng_library` VALUES('','%s','%s','%s','','0')",$db->Record['id'],table2real($group_name),$Session['user']['id_user']);
                        //echo $consulta3;
                }




        }

       // Fetch Info for the parent

        $consulta=sprintf("SELECT * FROM `%s` WHERE id='%s'","myng_".$group_name_for_table, $parent);
        $db->query($consulta);
        $db->next_record();

        // Update the value of 'num_readings'
        $num_readings = $db->Record['num_readings'] + 1;
        $consulta2 = sprintf("UPDATE `%s` SET num_readings = '%s' WHERE id='%s'","myng_".$group_name_for_table,$num_readings,$db->Record['id']);
        $db2->query($consulta2);
        //echo $consulta2;

        $p_reply_url = "post.php?type=reply&id=".$db->Record['number']."&group=".rawurlencode($group_name_for_table)."&server=".$server;
        $t->set_var("p_reply_url",modifyLink($p_reply_url));
        $t->set_var("p_subject",$db->Record['subject']);
        $c->username = $db->Record['username'];
        $c->email = $db->Record['from_header'];
        $c->sendername = $db->Record['name'];
        $p_sendername = formatAuthor($c);
        $t->set_var("p_name",$p_sendername);
        // -- Contributed by Kevin # Show the date in the local way -- //
        $date = date(_MYNGDATEDISPLAY,$db->Record['date']);
        $t->set_var("p_date",$date);

        // Article compression functions
        if($_SESSION['conf_system_zlib_yn'] == 'Y'){
                $p_body = gzuncompress($db->Record['body']);
                $p_body = stripslashes($p_body);
        }else{
                $p_body = $db->Record['body'];
        }

        $t->set_var("p_body",$p_body);
        // Ponemos el último TAG
        //echo "</UL>";
}


//------------------------------------------------------------------//
// Name:        get_thread_ids
// Task:        Get the ids of the articles in a thread
// Description: Just get all the ids of the articles
//              in a thread
//
// In:			grp_name, art_id, thread_ids
// Out:
//
//
//-----------------------------------------------------------------//

function get_thread_ids($grp_name,$art_id,$thread_ids){
			
	$db=new My_db;
    $db->connect();
	
	$query = "SELECT id FROM `myng_".real2table($grp_name)."` 
				WHERE parent='".$art_id."' 
				ORDER BY date ";
    $db->query($query);
    
    $thread_ids[] = $art_id;
    
    while($db->next_record()){

     	// Use the id of the article for the anchors.
        //$i = $db->Record['id_article'];        		                          
		get_thread_ids($grp_name,$db->Record['id'],&$thread_ids); //Recursive function!
    }        
    
}




//------------------------------------------------------------------//
// Name:        show_thread_new
// Task:        Show the complete thread
// Description: Doesn't show the article's contents, just the thread
//              It works oK for the article.php page. 
//
// In:
// Out:
//
//
//-----------------------------------------------------------------//

function show_thread_new($parent,$group_name){

	global $t,$depth,$grp_id,$linea2;
		
    $group_name_for_table = real2table($group_name);
	$group_name_for_ref_table = "ref_$group_name_for_table";
				
	$db=new My_db;
    $db2=new My_db;

    $db->connect();
    $db2->connect();		               
        		       
    //$consulta = "SELECT * FROM `myng_".$group_name_for_table."` WHERE parent='".$parent."' ORDER BY date ";
    $consulta = "SELECT GRP.id_article AS id_article,subject,number,id,date, username,from_header,name  
    		FROM `myng_$group_name_for_table` AS GRP, `myng_$group_name_for_ref_table` AS REF 
    		WHERE GRP.id = REF.id_article 
    		AND reference='$parent' 
    		AND parent='$parent' ORDER BY date";
    
    $db->query($consulta);                       
       
    // If there are more branches...
    if($db->num_rows()!= 0){
				
        ++ $depth;
        $linea="";
        // Adding Dots for tree building system
        for($i=0; $i < $depth; $i++){        	        	
            $linea = ".".$linea;            
        }        
                          
        while($db->next_record()){

        	// Use the id of the article for the anchors.
            $i = $db->Record['id'];
                        
            // Check if this is the article being shown
            if($db->Record['id_article'] == $_GET['id_article']){
               	$shown = "yes";                        	
            }else{
                $shown = "no";                        	
            }                       
                        		                                               
            // Check if we are in a session
            
            if(isset($_SESSION['usr_name'])){

            	// Try to show the unread articles
                $consulta2 = sprintf("SELECT lib_art_id FROM myng_library WHERE lib_usr_id='%s' and lib_grp_id ='%s' AND lib_art_id='%s'",$_SESSION['usr_id'],$grp_id,$db->Record['id_article']);
                $db2->query($consulta2);

                if($db2->num_rows() == 0){
                	// Highlight the actual article
                    if($shown == "yes"){                                	
                    	$unread = "yes";
                        $class = "normal_bold";                                        
                        $bg_color = "#FFFFFF";                                        
                   	}else{
                    	$unread = "yes";
                        $class = "normal";
                        $bg_color = "#FFFFFF";                                                                        		                                		
                   	}
                                	
                }else{
                                	
              		if($shown == "yes"){                                               		
	                    $unread = "no";
                        $class = "normal_bold";
                        $bg_color = "#F3F3F3";
               		}else{
    	       			$unread = "no";
                        $class = "normal";
                        $bg_color = "#F3F3F3";                               			
               		}                                      
                }
  
                // Try to show the new articles
                $consulta = sprintf("SELECT subs_last_article FROM myng_subscription WHERE subs_usr_id='%s' AND subs_grp_id ='%s'",$_SESSION['usr_id'],$grp_id);
                $db2->query($consulta);

                if($db2->num_rows() != 0){

 	               $db2->next_record();
                   // Group registered in mygroups.
                   //global $Group;
                   if( ($db2->Record['subs_last_article'] < $db->Record['number']) && ($db->Record['number'] <= $_SESSION['grp_last_article']) ){
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


            }else{
            	// We are not in a session.
                // Highlight the current article
                if($shown == "yes"){
                	$class = "normal_bold";
                    $bg_color = "#F3F3F3";
                }else{
               		$class = "normal";
                   	$bg_color = "#F3F3F3";
                }
            }
            
            $c->username = $db->Record['username'];
            $c->email = $db->Record['from_header'];
            $c->sendername = cut_phrase($db->Record['name'],15);
            $sender_name = formatAuthor($c);
            $subject = htmlspecialchars($db->Record['subject']);           
			// Build the menu's data
            $linea2 = $linea2.$linea.
            			"|".cut_phrase($db->Record['subject'],40). 
            			"|".modifyLink("article.php?id_article=".$db->Record['id_article']."&grp_id=".$grp_id)."&next=".$next.
            			"|".$subject.
            			"|".
            			"|_parent".
            			"|".
            			"|".date(_MYNGDATEDISPLAY,$db->Record['date']).
            			"|".date("H:i:s",$db->Record['date']).
            			"|".$sender_name.
            			"|".$class.
            			"|".$bg_color."\n";
            
            show_thread_new($db->Record['id'],$group_name); //Recursive function
        }

    }else{
    	// Rewind the depth counter
        $depth = 1;        
    }
           
}





//------------------------------------------------------------------//
// Name:        real2table
// Task:        Converts the real group name into a suitable name for
//              a table or a URL

//
//
// In:
// Out:
//
//
//-----------------------------------------------------------------//
function real2table($real_name){

        // Beware with the following characters: '-','&'
        $table_name = strtr($real_name,".","_");
        return($table_name);
}



//------------------------------------------------------------------//
// Name:        table2real
// Task:        Converts the table group name into a real name
//
//
// In:
// Out:
//
//
//-----------------------------------------------------------------//
function table2real($table_name){

        $real_name = strtr($table_name,"_",".");
        return($real_name);
}



function readHeader_from_DB($group,$id){

  // The 'id' that we use here it's the number.
  // But we should use the message_id instead
  // of the number because the numbers are repeated
  // after two years more or less.

  // We need to look for the newest message at the query

   $group_name_for_table = real2table($group);
   $group_name_for_ref_table = "ref_".$group_name_for_table;
   //Firstly we have to build an articleType object
    $article = new articleType;
    $j=0;       //Index for the parents array

   //We try to connect to th DB
    $db=new My_db;
    $db->connect();

    $consulta = sprintf("SELECT * FROM `%s` WHERE (number ='%s' OR id='%s')","myng_".$group_name_for_table,$id,$id);
    //echo $consulta;
    $db->query($consulta);


    if($db->num_rows() == 0){
        //The article doesn't exist. We should display an apropiate display.
        //echo "El artículo no existe.";
    }

    $db2=new My_db;
    $db2->connect();
    $db->next_record();

    $article->id = $db->Record['id'];
    $article->number = $db->Record['number'];
    $article->isReply = $db->Record['isreply'];
    $article->subject = $db->Record['subject'];
    $article->date = $db->Record['date'];
    $article->from = $db->Record['from_header'];
    $article->username = $db->Record['username'];
    $article->name = $db->Record['name'];
    $article->isAnswer = $db->Record['isanswer'];
    $article->newsgroups = $db->Record['newsgroups'];
    $article->followup =  $db->Record['followup'];
    $article->organization =  $db->Record['organization'];
    $article->content_transfer_encoding =  $db->Record['content_transfer_encoding'];
    $article->user_agent =  $db->Record['user_agent'];

    //Now we have to build the references
    $consulta2 = sprintf("SELECT reference FROM `%s` WHERE id_article='%s'","myng_".$group_name_for_ref_table,$db->Record['id']);
    $db2->query($consulta2);
    $i=0;
    while($db2->next_record()){
           $article->references[$i++] = $db2->Record['reference'];
    }

    return($article);
}



function readBody_from_DB($group,$id){

    //We try to connect to th DB

    $group_name_for_table = real2table($group);
    $group_name_for_ref_table = "ref_".$group_name_for_table;

    $db=new My_db;
    $db->connect();
    $consulta = sprintf("SELECT body FROM `%s` WHERE (number ='%s' OR id ='%s')","myng_".$group_name_for_table,$id,$id);
    //echo $consulta;
    $db->query($consulta);
    $db->next_record();
    $body = $db->Record['body'];
    //echo $body;
    return($body);
}




//------------------------------------------------------------------//
// Name:        show_layout
// Task:        parses all the required templates.
//
//
// In:          &$t,$left_bar,$system_info,$version
// Out:
//
//
//-----------------------------------------------------------------//

function show_layout(&$t,$left_bar,$system_info,$version){

        global $myng;
        global $myng_dir;
        global $myng_file;      

        if($myng['system'] == "postnuke") {
                global $ModName,$file;
        }

        $t->set_file("layout","layout/layout.htm");
        $t->set_file("header","layout/header.htm");
        $t->set_file("footer","layout/footer.htm");
        $t->set_file("left_bar",$left_bar);
        //$t->set_file("right_bar","right_bar.htm");

        $t->set_var("system_info",$system_info);
        $t->set_var("version",$version);
        $t->set_var("char_set",_CHARSET);        

        // CSS styles directory and file
        $t->set_var("style_dir",$_SESSION['conf_system_prefix']."themes/".$_SESSION['conf_vis_theme']."/styles");
        $t->set_var("file_style",$myng_file['style']);
        //$t->set_var("images_dir",$myng_dir['images']);
        $t->set_var("images_dir",$_SESSION['conf_system_prefix']."themes/".$_SESSION['conf_vis_theme']."/images");
        
        $t->set_var("modules_url",$myng_file['url']);

        // Template's text
        $t->set_var("_myngsystem_info",_MYNGSYSTEM_INFO);
        $t->set_var("_myngmenu_home",_MYNGMENU_HOME);
        $t->set_var("_myngmenu_newsgroups",_MYNGMENU_NEWSGROUPS);
        $t->set_var("_myngmenu_stats",_MYNGMENU_STATS);
        $t->set_var("_myngmenu_search",_MYNGMENU_SEARCH);
        $t->set_var("_myngmenu_about",_MYNGMENU_ABOUT);
        $t->set_var("_mynghome_page",_MYNGHOME_PAGE);
        $t->set_var("_mynggpl",_MYNGGPL);
        $t->set_var("_myngpage_generated",_MYNGPAGE_GENERATED);
        $t->set_var("_myngseconds",_MYNGSECONDS);

        // Check if we have to show the debug window
        if($_SESSION['conf_system_debug_yn'] == "Y"){

            $t->set_var("_myngdebug",_MYNGDEBUG);
            // Debug Window's content
            $t->set_var("debug_text",$_SESSION['debug_text']);

        }

        // Error Page text (In case the error page is loaded)
        $t->set_var("_myngsometh_wrong",_MYNGSOMETH_WRONG);
        $t->set_var("_myngerror_message",_MYNGERROR_MESSAGE);
        $t->set_var("_mynghave_doubt",_MYNGHAVE_DOUBT);
        $t->set_var("_myngerror",_MYNGERROR);

        // Parse little templates
        $t->parse("header_block","header");
        $t->parse("footer_block","footer");
        $t->parse("left_bar_block","left_bar");
        //$t->parse("right_bar_block","right_bar");
        // Parse the main template (The application)
        $t->parse("main_block","main");
        // Parse all the page
        $print = $t->parse("out","layout");
        
        // Show the web
        $t->p("out");
        
}



//----------------------------------------------------------------------//
// Name:        clean_people_online
// Task:        Remove from the 'people_online' table the old sessions.
//
//
// In:          $current_time
// Out:
//
//
//----------------------------------------------------------------------//

function clean_people_online(&$db,$current_time){

        $critical_time = $current_time - ( 60*3 );
        $consulta=sprintf("DELETE FROM myng_user_online WHERE uonl_session_time <= '%s'",$critical_time);
        $db->query($consulta);
}



//------------------------------------------------------------------//
// Name:        protect_email
// Task:        Protect the email from SPAM BOTS
//
// In:          $email
// Out:         $spam_protected
//-----------------------------------------------------------------//

function protect_email($email){

        list ($user, $domain)  = split ("@", $email, 2);
        $spam_protected = $user."_at_";
        $arr = explode (".", $domain);

        for($i=0;$i<count($arr);$i++){
                if($i != count($arr)-1){
                        $spam_protected .= $arr[$i]."_dot_";
                }else{
                        $spam_protected .= $arr[$i];
                }
        }

        $spam_protected .= "@foo.com";
        return $spam_protected;
}




//------------------------------------------------------------------//
// Name:        manage_login
// Task:        Checks if the login module is activated,
//              and do the login if it is.
// In:          $current_time, &$t, &$db, $_SESSION['conf_system_login_yn']
// Out:         $left_bar
//-----------------------------------------------------------------//
function manage_login($current_time,&$t,&$db){
        
        // Check if the login system is activated or not
        if($_SESSION['conf_system_login_yn'] == "Y"){
        	
                // The login system is working!
                if(isset($_SESSION['usr_name'])){
                		
                        //--------- We are in a session! -------------------------------//
                        // Create an user instance
                        $user = new User($_SESSION['usr_name']);
                        // Check the online status and update the timestamps
                        $user->is_online();
                        // Clean the myng_people_online table
                        clean_people_online(&$db,$current_time);
                        // Show the new interface (online)
                        $left_bar = "my_bar.htm";
                        $t->set_file("left_bar",$left_bar);
						$t->set_block("left_bar","admin_enter_block","admin_enter_block_handle");

                        // my_bar Text
            			//check if we can show admin link in my_bar
            			if ($user->id_user == $myng['administrator']) {
		                     $t->set_var("_myngmy_admin",_MYNGMY_ADMIN);
			                 $t->parse("admin_enter_block_handle","admin_enter_block",true);
			            }
                        $t->set_var("_myngmy_box",_MYNGMY_BOX);
                        $t->set_var("_myngmy_home",_MYNGMY_HOME);
                        $t->set_var("_myngmy_groups",_MYNGMY_GROUPS);
                        $t->set_var("_myngmy_articles",_MYNGMY_ARTICLES);
                        $t->set_var("_myngmy_stats",_MYNGMY_STATS);
                        $t->set_var("_myngoptions",_MYNGOPTIONS);
                        $t->set_var("_myngprofile",_MYNGPROFILE);
                        $t->set_var("_mynglog_out",_MYNGLOG_OUT);

                }else{
                        //-------- User not logged ------------------------------------//
                        $left_bar = "login.htm";
                        $challenge=md5(uniqid($cadena));
                        $t->set_var("secret_challenge",$challenge);

                        // Login Text
                        $t->set_var("_mynglogin",_MYNGLOGIN);
                        $t->set_var("_myngpassword",_MYNGPASSWORD);
                        $t->set_var("_myngregister",_MYNGREGISTER);
                }

        }else{
                // There's no login system
                $left_bar = "poweredby.htm";
        }

        return $left_bar;

}





//------------------------------------------------------------------//
// Name:        show_newsgroups
// Task:        Show newsgroups interface user in newsgroups.php and
//              mygroups.php
// In:          $news_groups, &$t
// Out:
//-----------------------------------------------------------------//

function show_newsgroups($news_groups,&$t){

        reset($news_groups);

        for($i=0;$i<count($news_groups);$i++){

                $c = current($news_groups);
                $count = $c->count;
                $new_articles = $c->newArticles;

               // *******************************************
                // Try to check wich folder image we'll use
                // *******************************************
                // If new articles -> red folder
                // If no new articles-> normal folder
                // A lot of articles? flames
                // A lot of articles and new ones? red and flames

                if($new_articles != 0){
                        // New articles
                        if($count > $_SESSION['conf_vis_num_2_flames'] ){
                                $folder_image="hot_red_folder.gif";
                        }else{
                                $folder_image="red_folder.gif";
                        }

                }else{
                        //echo $count;
                        // No new articles
                        if($count < $_SESSION['conf_vis_num_2_flames']){
                                $folder_image="folder.gif";
                        }else{
                                $folder_image="hot_folder.gif";
                        }
                }

                //$post_url = "post.php?newsgroups=".rawurlencode(real2table($c->name))."&type=new&server=".rawurlencode($c->server);
                //$group_url = "tree.php?group_name=".rawurlencode(real2table($c->name))."&begin=0&server=".rawurlencode($c->server);
                $post_url = "post.php?grp_id=".$c->id."&newsgroups=".rawurlencode(real2table($c->name))."&type=new";
                $group_url = "tree.php?grp_id=".$c->id."&begin=0";

                // Newsgroups text
                $t->set_var("_myngarticle_number",_MYNGARTICLE_NUMBER);
                $t->set_var("_mynggroup_name",_MYNGGROUP_NAME);
                $t->set_var("_myngdescription",_MYNGDESCRIPTION);
                $t->set_var("_myngsubscribe_group",_MYNGSUBSCRIBE_GROUP);

                $t->set_var("message",$c->name);
                $t->set_var("group_url",modifyLink($group_url));
                $t->set_var("post_url",modifyLink($post_url));
                $t->set_var("folder_image",$folder_image);
                $t->set_var("num_articles",$c->count);
                $t->set_var("group_name",$c->name);
                $t->set_var("num_new",$c->newArticles);
                $t->set_var("num_unread",$c->numUnread);
                $t->set_var("num_posted",$c->numPosted);
                $t->set_var("description",$c->description);

                $t->parse("groups_block_handle","groups_block",true);

                next($news_groups);
        }

}




//--------------------------------------------------------------//
// Name:        fetch_articles
// Task:        Fetch the required articles from the server
// Description: Implements the new article downloading
//              system. New article retrieval algorithm.
//
//
// In:  last_days -> The number of days to calculate the Activity Index
//
//--------------------------------------------------------------//

function fetch_articles($cron){

    global $t, $myng; 	
    
    // Check if we work online or not
    if($_SESSION['conf_system_online_yn'] == "Y" || $cron){
        
    	$db=new My_db;
    	$db->connect();

    	// To fetch articles, we need at least one group in the system
    	$first_query = "SELECT * FROM myng_newsgroup";
    	$db->query($first_query);
    	$num_groups = $db->num_rows();
    	
    	if($num_groups != 0){
    
    
    		// Try to get the newsgroups list to check articles out.
    		// We use the new Activity index, in the myng_newsgroup table

    		// We use a 'windowed' based walk on a list of newsgroups.

    		// Create the list only ONCE. It will be created and stored
    		// in the session.

    		// Register the debugging information to show it at the
    		// debug window.
    		// First we clean it
    		$_SESSION['debug_text'] = "";    
        
    		// If the script calling this function is the 
    		// cron.php, it updates the activity index and 
    		// builds the list, and it also downloads the articles.
    		// If it's another script, it'll build the list
    		// and update the A.I. only once and then it'll 
    		// download the new articles.
    		if(!isset($_SESSION['Fetch_list']) || $cron){
    	    	
        		// Firstly, we recalculate the Activity Index for each group.
        		// Activity index is based on the 'articles per day' parameter,
        		// and tends to be up to day.
        		$newsgroup_activity = update_activity_index();
        		// The newsgroups list has NOT been created,
        		// we build it and sort it in the session
        		$newsgroups_list = build_newsgroups_list($newsgroup_activity);
        		// Put the newsgroups list into the Session,
        		// with the pointers to the first group to fetch and the
        		// last group to fetch
        		$First_flag = "0";
        		// The number of groups to fetch articles from
        		$Last_flag = $_SESSION['conf_down_num_groups'] - 1;
        		//$Fetch_list = array();
        		//$Fetch_list = $newsgroups_list;
        		//echo count($newsgroups_list);
        
		        $_SESSION['Fetch_list'] = $newsgroups_list;
				$_SESSION['Last_flag'] = $Last_flag;
				$_SESSION['First_flag'] = $First_flag;
		        //session_register('Last_flag','First_flag');

    		}    		    	
    		if(isset($_SESSION['Fetch_list']) || $cron){
    					    	
		        foreach($_SESSION['Fetch_list'] as $id_group) {
            		//echo $id_group." | ";
            		$_SESSION['debug_text'] = $_SESSION['debug_text'].$id_group." | ";
        		}

        		// The list has been created and stored
        		for($i = $_SESSION['First_flag']; $i < ($_SESSION['Last_flag'] + 1); $i++){
	        		//for($i = $_SESSION['First_flag']; $i < ($_SESSION['Last_flag']); $i++){

            		// Check if we have reached the end of the list
            		//echo "<br>".$i."-".$_SESSION['num_items'];
    		        $_SESSION['debug_text'] = $_SESSION['debug_text']."<br> Index: ".$i."/".$_SESSION['num_items'];

		            if($i >= ($_SESSION['num_items'])){
                		// Fetch the articles for the correct group
                		$j = ($i - $_SESSION['num_items']);
            		}else{
                		$j = $i;
            		}

            		// Get the required Info
            		$consulta = "SELECT * FROM myng_newsgroup,myng_server
                		WHERE grp_id =".$_SESSION['Fetch_list'][$j]."
                		AND grp_serv_id = serv_id";
            		//echo $consulta;
            		$db->query($consulta);
            		$db->next_record();

            		// Fetch the server's data
            		$server = $db->Record['serv_host'];
            		$port = $db->Record['serv_port'];
            		$login = $db->Record['serv_login'];
            		$passwd = $db->Record['serv_passwd'];

            		//echo $db->Record['group_name'];
            		$_SESSION['debug_text'] = $_SESSION['debug_text']." - ".$db->Record['grp_name']."<br>";

            		// Fetch Articles for the group           
            		$ns = OpenNNTPconnection($server,$port,$login,$passwd);

            		if($ns == false){

            			closeNNTPconnection($ns);
                		flush();
            			
            			// Redirect to the error page, connection Error
						header("Location:".$_SESSION['conf_system_prefix']."error.php?error_id=0");							                	                		                	

            		}else{
		
                		// Connection Completed!
	               		// Get new articles.
    		            $new_articles = check_new_articles($ns,$db->Record['grp_name'],$db->Record['grp_last_article'],$db->Record['grp_num_messages']);
                		closeNNTPconnection($ns);
                		flush();

            		}

        		}// End of for

        		// Check if we have reached the end of the list
        		// The two flags must have passed the limit to
        		// reset them.
        		if($_SESSION['Last_flag'] > ($_SESSION['num_items'] - 1) && $_SESSION['First_flag'] > ($_SESSION['num_items'] - 1)){
            		$_SESSION['Last_flag'] -= $_SESSION['num_items'];
            		$_SESSION['First_flag'] -= $_SESSION['num_items'];
        		}

        		// Move the flags
        		$_SESSION['First_flag'] += $_SESSION['conf_down_num_groups'];
        		$_SESSION['Last_flag'] += $_SESSION['conf_down_num_groups'];

        		//echo "<br>".$First_flag.$Last_flag;
        		$_SESSION['debug_text'] = $_SESSION['debug_text']."<br>".$_SESSION['First_flag'].$_SESSION['Last_flag'];
        		
 
    		}// Else if


    		// Get the Latest article indexed for that group
    		// $query = "select max(number) from myng_es_rec_bricolaje, myng_es_rec_bricolaje_doclist where myng_es_rec_bricolaje.id_article = myng_es_rec_bricolaje_doclist.id";
			// Get the oldest article indexed for that group
    		// $query = "select min(number) from myng_es_rec_bricolaje, myng_es_rec_bricolaje_doclist where myng_es_rec_bricolaje.id_article = myng_es_rec_bricolaje_doclist.id";

    		return true;
    
    	}else{
    		// num_groups = 0
    		return false;    		
    	}
    	
    }else{
    	return true;
    }// SESSION['conf_system_online_yn'] is set

}


//--------------------------------------------------//
// Name:        build_newsgroups_list
// Task:        Build the newsgroups to fetch articles from list
// Description: This list is created based on the activity index
//              of each group.
//
// In:          $newsgroup_activity,
//--------------------------------------------------//

function build_newsgroups_list($newsgroup_activity){

    //global $_SESSION['num_items'];
        
    // Firstly, we have to fill the list with the required newsgroups
    // based on their activity index and then we'll shuffle that list
    // many times. Finally, we store in the session the situation of our
    // 'window' and each time 'fetch_articles' function is called,
    // the newsgroups that fit on that window will download latest articles,
    // and the window will move forward.

    // Declare an array to store the number of times that a newsgroup should
    // appear in the list
    $times_to_appear = array();

    // Prepare the Session variable that will store
    // the real number of items in the list
    $_SESSION['num_items'] = $_SESSION['conf_down_list_items'] ;

    // Build the times_to_appear array
    foreach($newsgroup_activity as $k => $activity_index) {

        $times = ($_SESSION['conf_down_list_items'] * $activity_index) / 100;

        // If the times to appear is very near to 0, but
        // we DO have an activity index different from 0.
        if(round($times) == 0 && $activity_index != 0){

            // Unless the activity index tell us that this group
            // shouldn't appear, We should add one item to the list
            // in order to make this group to appear. Store the real
            // $_SESSION['conf_down_list_items'] variable in the session.
			//if($_SESSION['num_items'] < ){
            	$_SESSION['num_items'] ++;
			//}
            //session_register('Num_items');

        }
        $times_to_appear[$k] = ceil($times);
        /*
        echo $k."-".ceil($times)."<br>";
        echo "Num_items: ".$_SESSION['num_items']."<br>";
        */

    }

    $newsgroups_list = array();

    // Build the newsgroups' list
    foreach($times_to_appear as $j => $times) {
        //echo $times."<br>".$j;
        // Insert the group's id as many times as $times
        for($i = 0; $i < $times; $i++){
            array_unshift($newsgroups_list,$j);
        }
    }

    // If the newsgroups list is smaller than the num_items we 
    // merge the same array untill we get num_items.
    while(sizeof($newsgroups_list) < $_SESSION['num_items']){
    	$newsgroups_list = array_merge($newsgroups_list, $newsgroups_list);
    }
    
    // Shuffle the list
    shuffle($newsgroups_list);
    return $newsgroups_list;

}


//--------------------------------------------------//
// Name:        update_activity_index
// Task:        Update the activity index of all the newsgroups
// Description: The activity index is tells us if newsgorups'
//              popularity, based on the number of articles
//              downloaded each day.
//
// In:  last_days -> The number of days to calculate the Activity Index
//
//--------------------------------------------------//

function update_activity_index(){	
	
    $db=new My_db;
    $db->connect();

    $db2=new My_db;
    $db2->connect();

    // We check the articles downloaded the last N days
    //get_last_N_days();

    // Get the last N days's dates english formatted in an array
    $current_time = time();
    $last_N_dates = array();

    // Build the date's array
    for($i=0;$i<$_SESSION['conf_down_days'];$i++){
        // We don't count the current day
        // 24 hours a day
        // 3600 seconds an hour
        $current_time = $current_time - (24*3600);
        array_unshift($last_N_dates,date("Y-m-d",$current_time));

    }

    $num_articles = 0;
    // Array to store the 'articles per day' number
    $articles_per_N_days = array();
    // Total number of articles downloaded in N days
    $tot_articles = 0;

    // For each registered newsgroup
    $first_query = "SELECT * FROM myng_newsgroup";
    $db->query($first_query);
    $num_groups = $db->num_rows();
    
    if($num_groups != 0){
    
    while($db->next_record()){

        //echo $db->Record['grp_name'];

        // Walk the array to calculate articles per day
        foreach($last_N_dates as $date){
        	
            // Get the articles downloaded the day...$date YYYY-MM-DD                                   
		    // But convert to unix timestamp *first* -- don't make MySQL do it (slowly),
	    	// and don't use the buggy "%d" format (which is just day of month).
            $date_min = strtotime($date);             
            $date_max = strtotime("$date +1 day");
            
            $query = "SELECT COUNT(*) FROM `myng_".real2table($db->Record['grp_name'])."`            
	            WHERE date >= $date_min AND date < $date_max";
            
            //$query = "SELECT COUNT(*) FROM `myng_".real2table($db->Record['grp_name'])."`
            //WHERE FROM_UNIXTIME(UNIX_TIMESTAMP('".$date."'),'%d') = FROM_UNIXTIME(date,'%d')";
            //echo $query;
            $db2->query($query);
            $db2->next_record();
            // Articles per day
            // The day
            //echo "Value: $date<br>\n";
            //echo $db2->Record[0]."<br>";
            $num_articles = $num_articles + $db2->Record[0];

        }

        // articles_per_day for each group=  (Sum of articles downloaded last N days for each group / N)
        $articles_per_N_days[$db->Record['grp_id']] = ($num_articles / $_SESSION['conf_down_days']);
        // Update the Total of articles counter
        // Tot_articles = SUM(All the articles downloaded last N days for ALL groups)
        $tot_articles = $tot_articles + $num_articles;
        // Reset the counter of articles
        $num_articles = 0;

    }
    
    // At the beginning we don't have any article    
    // So we force to avoid divide by zero
	if($tot_articles == 0){
		$tot_articles = 3;
	}

    // Calculate the Activity Index for each group
    foreach($articles_per_N_days as $k => $articles_x_day) {
        // Activity index = (articles_per_day * 100) / Tot_articles;
        //echo $articles_x_day."<br>";
        $activity_index = ($articles_x_day * 100) / ($tot_articles / $_SESSION['conf_down_days']);
        // Maybe we have an activity index of 0. That's a special
        // case because we have to make it greater than 0 in order
        // to let that group download the new articles.
        // (An activity index of 0 would make that group idle forever).
        if($activity_index == 0){
            // We give the group a opportunity to download.
            // It receives only one place in the list.
            
            // Maybe there's only one group in the system,
            // if that's the case, we give it the 100%, to avoid
            // problems with only one group registered.
            if($num_groups == 1){
            	$activity_index = 100;
            }else{
            	// We'll give it at least one place            	
            	$activity_index = 100 / $num_groups;	
            }
        }
        // Build the $activity index array to return
        $newsgroup_activity[$k] = $activity_index;
        //Try to get rid of decimal numbers!!
        // Save the latest activity index for that group
        $query = "UPDATE myng_newsgroup SET grp_activity_index = ".$activity_index." WHERE grp_id = '".$k."'";
        //echo $query."<br>";
        $db->query($query);
        // Debug Window
        $_SESSION['debug_text'] = $_SESSION['debug_text'].$k." : ".$activity_index."<br>";

    }
    
    }// NewsGroups = 0

    return $newsgroup_activity;

}


//-------------- Navigation Bar -------------------//

function navigation_bar($num_elements,$page){

	global $t;	

	//echo $myng_article['pages_in_bar'];
	
	$nav_items = $_SESSION['conf_vis_nav_bar_items'];
	$nav_bar_pages = $_SESSION['conf_vis_nav_bar_pages'];
			
	//----------------- Control de la paginación ----------------------//
	// $num_elements = Resultado de la consulta
	// $num_paginas = Nº de páginas en las que organizamos los resultados
	// $elementos_por_página = Número de resultados que aparecen en cada página
	// $num_paginas_MAX = Nº máximo de páginas a mostrar en la barra de navegación
	// $primero = 	Primer elemento del grupo de páginas. Nos sirve para pasar al
	//				siguiente grupo o al anterior.
	// $num_grupos = Número de grupos que vamos a tener
	// $num_grupo_actual = Número del grupo en el que estamos navegando
	
	// Número de páginas o grupos de elementos que mostraríamos
	$num_pages = ceil($num_elements / $nav_items);
	//echo $num_pages;
	// Número de grupos que vamos a tener
	//echo $num_pages;
	$num_groups = ceil($num_pages / $nav_bar_pages);	
	//echo $num_groups;
	//echo $num_pages;
		
	// Comprobamos si tenemos poquitos elementos
	if($nav_bar_pages > $num_pages && $num_pages != 0){		
		$nav_bar_pages = $num_pages;		
	}
	
	//echo $nav_bar_pages;

	// Construimos los elementos << < > >>
		
	if ($num_elements != 0){	// Si por lo menos hay un producto
	
		$position = $page % $nav_bar_pages;
		if($position == 0){ 	// Se trata del último elemento
			// Intentamos averiguar el primer elemento del grupo
			$first = ($page - $nav_bar_pages) + 1;
		}
		else{
			$first = ($page - $position) + 1;
		}
	
		
		for($i=0; $i < $nav_bar_pages; $i++){
				
			if($page == ($first + $i)){
				$t->set_var("class","top_link_act");
			}else{
				$t->set_var("class","top_link");
			}
			// Comprobamos si no nos hemos pasado de páginas
			if($first + $i <= $num_pages){			
				$t->set_var("num_page",$first + $i);			
		    	$t->parse("nav_block_handle","nav_block",true);
			}
		}

		//echo $myng_article['pages_in_bar'];
	
		$num_grupo_actual = ceil($page / $nav_bar_pages);

		$num_page_sig = $page + 1;
		$num_page_ant = $page - 1;
		$num_page_grupo_ant = $first - $nav_bar_pages;
		$num_page_grupo_sig = $first + $nav_bar_pages;

		// <<
		// Comprobamos si tiene que aparecer
		// No aparece si se trata del primer grupo
		if($num_page_grupo_ant > 0){
			$t->set_var("flecha_doble_izq","<<");
			$t->set_var("num_page_grupo_ant",$num_page_grupo_ant);
		}

		// <
		// Comprobamos si tiene que aparecer
		// No aparece si se trata de la primera página
		if($page != 1){
			$t->set_var("flecha_izq","<");
			$t->set_var("num_page_ant",$num_page_ant);
		}

		// >
		// Comprobamos si tiene que aparecer
		// No aparece si se trata de la última página
		if($page != $num_pages){
			$t->set_var("flecha_der",">");
			$t->set_var("num_page_sig",$num_page_sig);
		}

		// >>
		// Comprobamos si tiene que aparecer
		// No aparecer si se trata del último grupo
		if($num_grupo_actual != $num_groups){
			$t->set_var("flecha_doble_der",">>");			
			$t->set_var("num_page_grupo_sig",$num_page_grupo_sig);
		}

	}else{
		
		$t->set_var("nav_block","");
		$t->parse("nav_block_handle","nav_block",true);
		
	}// num_elements = 0
	//------------------------------------------------------------------//

}


//--------------------------------------------------//
// Name:        show_iface
// Task:        Shows the new interface of MyNewsGroups :)
// Description: Build the new interface layout of MyNG, based
//              on the use of HTML layers.
//
// Notes:       It's only active in the Administration Interface!!
//
//--------------------------------------------------//

function show_iface($main){

    global $t,$myng_dir,$myng_file;

    // Links
    $t->set_var("home_dir",$_SESSION['conf_system_prefix']);
    // CSS
    $t->set_var("style_dir",$_SESSION['conf_system_prefix']."themes/".$_SESSION['conf_vis_theme']."/styles/");
    // Images
    $t->set_var("images_dir",$_SESSION['conf_system_prefix']."admin/images");

    $t->set_var("file_style",$myng_file['style']);

	$t->set_root($_SESSION['conf_system_root']."admin/templates/layout");
    //$t->set_root($myng_dir['themes']."/".$myng['theme']."/templates/admin/layout");
    $t->set_file("menu","header_menu.htm");
    $t->parse("header_menu_template","menu");
    // Parseamos toda la página
    $t->parse("out","main");
    // Mostramos el web
    $t->p("out");


}

function remember_page($begin){
	
	//------------ Get the page number -----------//
	// Code to remember the page number
	
	// Force the first page. User comes from newsgroups list.
	if($begin == 0){
		$page = 1;
		// Put the page info into session
		$_SESSION['page'] = $page;
	}
	
	// No GET
	if(!isset($_GET['page'])){
		// SESSION?
		if(isset($_SESSION['page'])){
			$page = $_SESSION['page'];
		}else{
			$page = 1;	
		}
	// GET    
	}else{ 
		$page = $_GET['page'];
		// Put the page info into session
		$_SESSION['page'] = $page;
	}
	
	return $page;
}

// Function to get start the timer
function start_time(){
	
	//Get current time
    $mtime = microtime();
	//Split seconds and microseconds
    $mtime = explode(" ",$mtime);
	//Create one value for start time
    $mtime = $mtime[1] + $mtime[0];
	//Write start time into a variable
    $tstart = $mtime;

    return $tstart;
 
}

// Function to retrieve the time elapsed
function finish_time($tstart){

	//Get current time as we did at start
    $mtime = microtime();
    $mtime = explode(" ",$mtime);
    $mtime = $mtime[1] + $mtime[0];
	//Store end time in a variable
    $tend = $mtime;
	//Calculate the difference
    $totaltime = ($tend - $tstart);
	
    return round($totaltime,3);
}



// Cuts a phrase into the given length
// It would be great if no word is cut
function cut_phrase($phrase, $proper_length){
	
	$real_length = strlen($phrase);
	if($real_length > $proper_length){
		// Cut down the size of the phrase
		$phrase = substr($phrase, 0, $proper_length);
		$phrase = $phrase." ..";
	}

	return $phrase;
}


//-------------------- Different Functions for standalone -------------------//
//
// modules_get_language - Get the language loaded into the system
//
//---------------------------------------------------------------------------//

function modules_get_language($script = 'global'){
	  //global $myng;
	  if (!empty($_SESSION['conf_system_language'])) {
		if (file_exists("lang/".$_SESSION['conf_system_language']."/$script.php")){
				@include "lang/".$_SESSION['conf_system_language']."/$script.php";
		}
	  }
	  else{
		@include "lang/en/".$script.".php";
	  }
	  return ;
}
	







//------------------- NewsPortal -----------------------------------//


function textwrap($text, $wrap=80, $break="\n"){
  $len = strlen($text);
  if ($len > $wrap) {
    $h = '';        // massaged text
    $lastWhite = 0; // position of last whitespace char
    $lastChar = 0;  // position of last char
    $lastBreak = 0; // position of last break
    // while there is text to process
    while ($lastChar < $len) {
      $char = substr($text, $lastChar, 1); // get the next character
      // if we are beyond the wrap boundry and there is a place to break
      if (($lastChar - $lastBreak > $wrap) && ($lastWhite > $lastBreak)) {
        $h .= substr($text, $lastBreak, ($lastWhite - $lastBreak)) . $break;
        $lastChar = $lastWhite + 1;
        $lastBreak = $lastChar;
      }
      // You may wish to include other characters as valid whitespace...
      if ($char == ' ' || $char == chr(13) || $char == chr(10)) {
        $lastWhite = $lastChar; // note the position of the last whitespace
      }
      $lastChar = $lastChar + 1; // advance the last character position by one
    }
    $h .= substr($text, $lastBreak); // build line
  } else {
    $h = $text; // in this case everything can fit on one line
  }
  return $h;
}

/*
 * makes URLs clickable
 */
function htmlParse($comment) {
  global $frame_externallink;
  if ((isset($frame_externallink)) && ($frame_externallink != "")) {
    $target=' TARGET="'.$frame_externallink.'" ';
  } else {
    $target=' ';
  }
  $ncomment = eregi_replace( 'http://([-a-z0-9_./~@?=%#&;\n]+)', '<a'.$target.'href="http://\1">http://\1</a>', $comment);
  if ($ncomment == $comment)
    $ncomment = eregi_replace( '(www\.[-a-z]+\.(de|int|es|ar|eu|org|net|at|ch|com))','<a'.$target.'href="http://\1">\1</a>',$comment);
  $comment=$ncomment;
  $comment = eregi_replace( 'https://([-a-z0-9_./~@?=%#&;\n]+)', '<a'.$target.' href="https://\1">https://\1</a>', $comment);
  $comment = eregi_replace( 'gopher://([-a-z0-9_./~@?=%\n]+)','<a'.$target.'href="gopher://\1">gopher://\1</a>', $comment);
  $comment = eregi_replace( 'news://([-a-z0-9_./~@?=%\n]+)','<a'.$target.'href="news://\1">news://\1</a>', $comment);
  $comment = eregi_replace( 'ftp://([-a-z0-9_./~@?=%\n]+)', '<a'.$target.'href="ftp://\1">ftp://\1</a>', $comment);
  $comment = eregi_replace( '([-a-z0-9_./n]+)@([-a-z0-9_.]+)','<a href="mailto:\1@\2">\1@\2</a>', $comment);
  return($comment);
}


/*
 * Validates an email adress
 */
function validate_email($adress)
{
  global $validate_email;
  $return=true;
  if (($validate_email >= 1) && ($return == true))
    $return = (ereg('^[-!#$%&\'*+\\./0-9=?A-Z^_A-z{|}~]+'.'@'.
               '[-!#$%&\'*+\\/0-9=?A-Z^_A-z{|}~]+\.'.
               '[-!#$%&\'*+\\./0-9=?A-Z^_A-z{|}~]+$',$adress));
  if (($validate_email >= 2) && ($return == true)) {
    $adressarray=adressDecode($adress,"garantiertungueltig");
    $return=checkdnsrr($adressarray[0]["host"],"MX");
    if (!$return) $return=checkdnsrr($adressarray[0]["host"],"A");
  }
  return($return);
}


// Format the sender name
function formatAuthor($c) {

        $return = '<a href="mailto:'.trim($c->email).'">';
        if (trim($c->sendername)!="") {
                $return .= htmlspecialchars(trim($c->sendername));
        } else {
                if (isset($c->username)) {
                        $s = strpos($c->username,"%");
                        if ($s != false) {
                                $return .= htmlspecialchars(substr($c->username,0,$s));
                        } else {
                                $return .= htmlspecialchars($c->username);
                        }
                }
        }
        $return .= "</a>";
        return($return);
}



//--------------------------------------------------------//
// This function checks whether the groupname given
// appears or not in the 'newsgroup' table of the DB
//
//--------------------------------------------------------//

function testgroup($grp_id) {
		
        if (isset($_SESSION['conf_sec_test_group_yn']) && $_SESSION['conf_sec_test_group_yn'] == "Y" ) {
        		
                //****************************************//
                // Here begins our modification
                //****************************************//
                //We try to connect to th DB
                $db=new My_db;
                $db->connect();             //Connect to the Database
                $consulta = sprintf("SELECT grp_name FROM myng_newsgroup WHERE grp_id='%s'",$grp_id);     //Build the query
                $db->query($consulta);      //Throw the query

                if($db->num_rows()!= 0){
                        //The group exists, ok
                        return 1;
                }else{
                        //There's not a group with that name
                        return 0;
                }

        } else {
                return true;        //If the testgroup variable is false, we don't care about the result
        }

}

function testgroups($newsgroups) {
  $groups=explode(",",$newsgroups);
  $count=count($groups);
  $return="";
  $o=0;
  for ($i=0; $i<$count; $i++) {
    if (testgroup($groups[$i])) {
      if ($o>0) $return.=",";
      $o++;
      $return.=$groups[$i];
    }
  }
  return($return);
}



function getTimestamp($value) {
  $months=array("Jan"=>1,"Feb"=>2,"Mar"=>3,"Apr"=>4,"May"=>5,"Jun"=>6,"Jul"=>7,"Aug"=>8,"Sep"=>9,"Oct"=>10,"Nov"=>11,"Dec"=>12);
  $value=str_replace("  "," ",$value);
  $d=split(" ",$value,5);
  if (strcmp(substr($d[0],strlen($d[0])-1,1),",") == 0) {
    $date[0]=$d[1];  // day
    $date[1]=$d[2];  // month
    $date[2]=$d[3];  // year
    $date[3]=$d[4];  // hours:minutes:seconds
  } else {
    $date[0]=$d[0];  // day
    $date[1]=$d[1];  // month
    $date[2]=$d[2];  // year
    $date[3]=$d[3];  // hours:minutes:seconds
  }
  $time=split(":",$date[3]);
  $timestamp=mktime($time[0],$time[1],$time[2],$months[$date[1]],$date[0],$date[2]);
  return $timestamp;
}


function headerDecode($value) {
  if (eregi('=\?.*\?Q\?.*\?=',$value)) {
    $result=eregi_replace('(.*)=\?.*\?Q\?(.*)\?=(.*)','\1\2\3',$value);
    if ($value != $result) $result=headerDecode($result);
    $result=str_replace("_"," ",quoted_printable_decode($result));
    return($result);
  }
  if (eregi('=\?.*\?B\?.*\?=',$value)) {
    $result=eregi_replace('(.*)=\?.*\?B\?(.*)\?=(.*)','\1\2\3',$value);
    if ($value != $result) $result=headerDecode($result);
    $result=str_replace("_"," ",base64_decode($result));
    return($result);
  }

  return($value);
}

function splitSubject(&$subject) {
  $s=eregi_replace('^(aw:|re:|re\[2\]:| )+','',$subject);
  if ($s != $subject) {
    $return=true;
  } else {
    $return=false;
  }
  $subject=$s;
  return $return;
}

function adressDecode($adrstring,$defaulthost) {
  $parsestring=trim($adrstring);
  $len=strlen($parsestring);
  $at_pos=strpos($parsestring,'@');     // find @
  $ka_pos=strpos($parsestring,"(");     // find (
  $kz_pos=strpos($parsestring,')');     // find )
  $ha_pos=strpos($parsestring,'<');     // find <
  $hz_pos=strpos($parsestring,'>');     // find >
  $space_pos=strpos($parsestring,')');  // find ' '
  $email="";
  $mailbox="";
  $host="";
  $personal="";
  if ($space_pos != false) {
    if (($ka_pos != false) && ($kz_pos != false)) {
      $personal=substr($parsestring,$ka_pos+1,$kz_pos-$ka_pos-1);
      $email=trim(substr($parsestring,0,$ka_pos-1));
    }
  } else {
    $email=$adrstring;
  }
  if (($ha_pos != false) && ($hz_pos != false)) {
    $email=trim(substr($parsestring,$ha_pos+1,$hz_pos-$ha_pos-1));
    $personal=substr($parsestring,0,$ha_pos-1);
  }
  if ($at_pos != false) {
    $mailbox=substr($email,0,strpos($email,'@'));
    $host=substr($email,strpos($email,'@')+1);
  } else {
    $mailbox=$email;
    $host=$defaulthost;
  }
  $personal=trim($personal);
  if (substr($personal,0,1) == '"') $personal=substr($personal,1);
  if (substr($personal,strlen($personal)-1,1) == '"')
    $personal=substr($personal,0,strlen($personal)-1);
  $result["mailbox"]=trim($mailbox);
  $result["host"]=trim($host);
  if ($personal!="") $result["personal"]=$personal;
  $complete[]=$result;
  return ($complete);
}

//----------- PHP-Nuke Integration --------------//

function modifyLink($url) {
 global $myng,$myng_file;
 if($url) {
  // Postnuke
  if($myng['system'] == "postnuke") {
   $output = $myng_file['url'];
   $output .= str_replace(".php?", "&", $url);
   return $output;
  } else {
  // Standalone
   return $url;
  }
 }
}

?>