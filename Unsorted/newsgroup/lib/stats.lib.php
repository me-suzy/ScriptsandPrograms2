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
// stats.lib.php
// Author: Carlos Sánchez
// Created: 24/11/02
//
// Description: Statistics functions
//
//
//------------------------------------------------------------------//
?>
<?

function show_hot_groups(){

	global $t,$db;
		
	$max_width = 200;

	$consulta = "SELECT grp_id,grp_name,grp_num_messages FROM myng_newsgroup ORDER BY grp_num_messages DESC LIMIT 0,5";
	$db->query($consulta);
	$i=0;
	while($db->next_record()){
		
			// Real fixed bar width new hack
			if($i==0){
				$max = $db->Record['grp_num_messages'];
				$width = $max_width;
			}else{
				$width = ($db->Record['grp_num_messages'] * $max_width) / $max; 	
			}			
				       
        	$t->set_var("group_name",$db->Record['grp_name']);
        	$t->set_var("grp_id",$db->Record['grp_id']);
        	$t->set_var("num_articles",$db->Record['grp_num_messages']);
        	$t->set_var("width",$width);
        	$t->parse("hot_groups_block_handle","hot_groups_block",true);
        	$i++;
	}
	
	$t->parse("hot_groups_TOP_block_handle","hot_groups_TOP_block",true);
	
}



function show_popular_groups(){

	global $t, $db, $db2;
	
	//$max_scale = 500;
	$max_width = 200;

	$consulta = sprintf("SELECT grp_name,grp_id FROM myng_newsgroup");
	$db->query($consulta);
	$users = array();

	$i = 0;
	$j = 0;

	// For each newsgroup
	while($db->next_record()){

        $group_name = real2table($db->Record['grp_name']);
        $consulta2 = sprintf("SELECT number,subject,num_readings,COUNT(*) FROM `%s` GROUP BY number","myng_".$group_name);
        //echo $consulta2;
        $db2->query($consulta2);
        while($db2->next_record()){
                $num_readings[$j][0] = $num_readings[$j][0] + $db2->Record['num_readings'];
                $num_readings[$j][1] = $db->Record['grp_name'];
                $num_readings[$j][2] = $db->Record['grp_id'];                
        }
        $j ++;

	}

	rsort($num_readings);
	for($i=0;$i<$j;$i++){

		// Real fixed bar width new hack
		if($i==0){
			$max = $num_readings[$i][0];
			$width = $max_width;
		}else{
			$width = ($num_readings[$i][0] * $max_width) / $max; 	
		}
		
        $t->set_var("group_name",$num_readings[$i][1]);
        $t->set_var("grp_id",$num_readings[$i][2]);
        $t->set_var("num_readings",$num_readings[$i][0]);
        $t->set_var("width",$width);
        $t->parse("popular_groups_block_handle","popular_groups_block",true);


	}

	$t->parse("popular_groups_TOP_block_handle","popular_groups_TOP_block",true);

}


function show_crowded_groups(){

	global $t,$db,$db2;
	
	//$max_scale = 500;
	$max_width = 200;

	$consulta = sprintf("SELECT grp_name,grp_id FROM myng_newsgroup");
	$db->query($consulta);
	$users = array();

	$i = 0;

	// For each newsgroup
	while($db->next_record()){

        $group_name = real2table($db->Record['grp_name']);
        $consulta2 = sprintf("SELECT DISTINCT name FROM `%s`","myng_".$group_name);
        $db2->query($consulta2);
        $num_users[$i][0] = $db2->num_rows();
        $num_users[$i][1] = $group_name;
        $num_users[$i][2] = $db->Record['grp_id'];

        $i++;
}	

	rsort($num_users);
	for($i=0;$i<count($num_users);$i++){

		// Real fixed bar width new hack
		if($i==0){
			$max = $num_users[$i][0];
			$width = $max_width;
		}else{
			$width = ($num_users[$i][0] * $max_width) / $max; 	
		}
		       
        $t->set_var("group_name",table2real($num_users[$i][1]));
        $t->set_var("num_users",$num_users[$i][0]);
        $t->set_var("grp_id",$num_users[$i][2]);
        $t->set_var("width",$width);
        $t->parse("crowded_groups_block_handle","crowded_groups_block",true);

	}

	$t->parse("crowded_groups_TOP_block_handle","crowded_groups_TOP_block",true);
	
}


// Show hot articles for all the registered groups
function show_hot_articles(){

	global $db, $db2, $t;
		
	//$max_scale = 50;
	$max_width = 200;

	$consulta = sprintf("SELECT grp_name,grp_id FROM myng_newsgroup");
	$db->query($consulta);

	// For each newsgroup
	$j = 0;
	while($db->next_record()){

        $group_name = $db->Record['grp_name'];
    
        // Second method to do it: faster, one query to the database
        // Change the method in thread.php to get the parents!!!
        // With this query, I think that fetch_bastards and is_parent are no more needed!!

        $group_name_for_table = real2table($group_name);
        $consulta2 = "SELECT 
        				`myng_".$group_name_for_table."`.subject ,
         				`myng_".$group_name_for_table."`.id_article,
        				 COUNT(*)
        			 FROM
        			 	`myng_".$group_name_for_table."`,
        				`myng_ref_".$group_name_for_table."` 
        			WHERE `myng_".$group_name_for_table."`.isanswer='0' 
        			AND `myng_ref_".$group_name_for_table."`.reference=`myng_".$group_name_for_table."`.id 
        			GROUP BY subject"; 
        //echo $consulta2;
        $db2->query($consulta2);
        while($db2->next_record()){

                $num_replies[$j][0] = $db2->Record[2];
                $num_replies[$j][1] = $db2->Record['subject'];
                $num_replies[$j][2] = $db2->Record['id_article'];
                $num_replies[$j][3] = $db->Record['grp_id'];
                $j ++;

        }

	}

	// Sort the result
	rsort($num_replies);
	// We only want 5 best results.
	for($i=0;$i<5;$i++){

		// Real fixed bar width new hack
		if($i==0){
			$max = $num_replies[$i][0];
			$width = $max_width;
		}else{
			$width = ($num_replies[$i][0] * $max_width) / $max; 	
		}
		        
        $t->set_var("subject",cut_phrase($num_replies[$i][1],30));
        $t->set_var("id_article",rawurlencode($num_replies[$i][2]));
        $t->set_var("grp_id",$num_replies[$i][3]);
        $t->set_var("num_articles",$num_replies[$i][0]);
        $t->set_var("width",$width);
        $t->parse("hot_articles_block_handle","hot_articles_block",true);
	}

	$t->parse("hot_articles_TOP_block_handle","hot_articles_TOP_block",true);
	
}

// Show hot articles for a given group
function show_group_hot_articles($group_name,$grp_id){

	global $db, $db2, $t;
		
	//$max_scale = 50;
	$max_width = 100;

	$j = 0;
	
    $group_name_for_table = real2table($group_name);
    $consulta2 = "SELECT 
     				`myng_".$group_name_for_table."`.subject ,
       				`myng_".$group_name_for_table."`.id_article,
      				 COUNT(*)
      			 FROM
      			 	`myng_".$group_name_for_table."`,
      				`myng_ref_".$group_name_for_table."` 
       			WHERE `myng_".$group_name_for_table."`.isanswer='0' 
       			AND `myng_ref_".$group_name_for_table."`.reference=`myng_".$group_name_for_table."`.id 
       			GROUP BY subject"; 
    //echo $consulta2;
    $db2->query($consulta2);
    while($db2->next_record()){

            $num_replies[$j][0] = $db2->Record[2];
            $num_replies[$j][1] = $db2->Record['subject'];
            $num_replies[$j][2] = $db2->Record['id_article'];
            $num_replies[$j][3] = $grp_id;           
            $j ++;
	
    }

	// Sort the result
	if($num_replies != null){
		rsort($num_replies);
		// We only want 5 best results.
		for($i=0;$i<3;$i++){
	
			// Real fixed bar width new hack
			if($i==0){
				$max = $num_replies[$i][0];
				$width = $max_width;
			}else{
				$width = ($num_replies[$i][0] * $max_width) / $max; 	
			}
		        		
        	$t->set_var("subject",cut_phrase($num_replies[$i][1],15));
        	$t->set_var("id_article",rawurlencode($num_replies[$i][2]));        
        	$t->set_var("grp_id",$num_replies[$i][3]);
        	$t->set_var("num_articles",$num_replies[$i][0]);
        	$t->set_var("width",$width);
        	$t->parse("hot_articles_block_handle","hot_articles_block",true);
		}

		$t->parse("hot_articles_TOP_block_handle","hot_articles_TOP_block",true);
	}
	
}



function show_popular_articles(){

	global $t, $db, $db2;
	
	//$max_scale = 500;
	$max_width = 200;

	$consulta = sprintf("SELECT grp_name,grp_id FROM myng_newsgroup");
	$db->query($consulta);
	$users = array();

	$i = 0;
	$j = 0;

	// For each newsgroup
	while($db->next_record()){

        $group_name = real2table($db->Record['grp_name']);
        $consulta2 = sprintf("SELECT subject,num_readings,id_article,COUNT(*) FROM `%s` group by num_readings","myng_".$group_name);
        //echo $consulta2;
        $db2->query($consulta2);
        while($db2->next_record()){
                $num_readings[$j][0] = $db2->Record['num_readings'];
                $num_readings[$j][1] = $db2->Record['subject'];
                $num_readings[$j][2] = $db2->Record['id_article'];
                $num_readings[$j][3] = $db->Record['grp_id'];
                $j ++;
        }


	}

	rsort($num_readings);
	for($i=0;$i<5;$i++){

		// Real fixed bar width new hack
		if($i==0){
			$max = $num_readings[$i][0];
			$width = $max_width;
		}else{
			$width = ($num_readings[$i][0] * $max_width) / $max; 	
		}
        $t->set_var("subject",cut_phrase($num_readings[$i][1],40));
        //$t->set_var("subject",$num_readings[$i][1]);
        $t->set_var("id_article",$num_readings[$i][2]);
        $t->set_var("grp_id",$num_readings[$i][3]);
        $t->set_var("num_readings",$num_readings[$i][0]);
        $t->set_var("width",$width);
        $t->parse("popular_article_block_handle","popular_article_block",true);

	}

    $t->parse("popular_article_TOP_block_handle","popular_article_TOP_block",true);
	
}


function show_active_user(){


	global $t, $db, $db2;
	
	//$max_scale = 100;
	$max_width = 200;

	$consulta = sprintf("SELECT grp_name FROM myng_newsgroup");
	$db->query($consulta);
	$users = array();

	$i = 0;
	$j = 0;


	// For each newsgroup
	while($db->next_record()){

        $group_name = real2table($db->Record['grp_name']);        
    	$consulta2 = sprintf("SELECT name,from_header,COUNT(*) FROM `%s` GROUP BY name ORDER BY 'COUNT(*)' DESC","myng_".$group_name);
        //echo $consulta2."<br>";
        $db2->query($consulta2);

        while($db2->next_record()){

                if($db2->Record['name'] != ""){
                		$num_replies[$j][0] = $db2->Record[2];
                        $num_replies[$j][1] = $db2->Record['name'];
                        $num_replies[$j][2] = $db2->Record['from_header'];                        
                }else{
                        $j--;
                }
                $j ++;
        }
	}

	rsort($num_replies);

	for($j=0;$j<5;$j++){
		
		// Real fixed bar width new hack
		if($j==0){
			$max = $num_replies[$j][0];
			$width = $max_width;
		}else{
			$width = ($num_replies[$j][0] * $max_width) / $max; 	
		}
		        
        $t->set_var("username",$num_replies[$j][1]);
        $t->set_var("from_header",$num_replies[$j][2]);
        $t->set_var("num_posts",$num_replies[$j][0]);
        $t->set_var("width",$width);
        $t->parse("active_user_block_handle","active_user_block",true);
	}

    $t->parse("active_user_TOP_block_handle","active_user_TOP_block",true);
	
}


// Shows the groups most active user
function show_group_active_user($group_name){


	global $t, $db, $db2;
	
	//$max_scale = 100;
	$max_width = 100;
	
	$users = array();

	$i = 0;
	$j = 0;
    
    $consulta2 = sprintf("SELECT name,from_header,COUNT(*) FROM `%s` GROUP BY name ORDER BY 'COUNT(*)' DESC","myng_".$group_name);
    $db2->query($consulta2);

    while($db2->next_record()){

            if($db2->Record['name'] != ""){
                    $num_replies[$j][1] = $db2->Record['name'];
                    $num_replies[$j][2] = $db2->Record['from_header'];
                    $num_replies[$j][0] = $db2->Record[2];
            }else{
                    $j--;
            }
            $j ++;
    }

	//rsort($num_replies);

	for($j=0;$j<3;$j++){        
		
		// Real fixed bar width new hack
		if($j==0){
			$max = $num_replies[$j][0];
			$width = $max_width;
		}else{
			$width = ($num_replies[$j][0] * $max_width) / $max; 	
		}
	       
        $t->set_var("username",$num_replies[$j][1]);
        $t->set_var("from_header",$num_replies[$j][2]);
        $t->set_var("num_posts",$num_replies[$j][0]);
        $t->set_var("width",$width);
        $t->parse("active_user_block_handle","active_user_block",true);
	}

    $t->parse("active_user_TOP_block_handle","active_user_TOP_block",true);
	
}


function show_reply_user(){

	global $t, $db, $db2;
	
	//$max_scale = 100;
	$max_width = 200;

	$consulta = sprintf("SELECT grp_name FROM myng_newsgroup");
	$db->query($consulta);
	$users = array();

	$i = 0;
	$j = 0;

	// For each newsgroup
	while($db->next_record()){

        $group_name = real2table($db->Record['grp_name']);
        $consulta2 = sprintf("SELECT name,from_header,COUNT(*) FROM `%s` WHERE isanswer='1' GROUP BY name","myng_".$group_name);
        $db2->query($consulta2);

        while($db2->next_record()){

                if($db2->Record['name'] != ""){
                		$num_replies[$j][0] = $db2->Record[2];
                        $num_replies[$j][1] = $db2->Record['name'];
                        $num_replies[$j][2] = $db2->Record['from_header'];
                        
                }else{
                        $j--;
                }
                $j ++;
        }
	}

	rsort($num_replies);

	for($j=0;$j<5;$j++){
		
		// Real fixed bar width new hack
		if($j==0){
			$max = $num_replies[$j][0];
			$width = $max_width;
		}else{
			$width = ($num_replies[$j][0] * $max_width) / $max; 	
		}
        
        $t->set_var("username",$num_replies[$j][1]);
        $t->set_var("from_header",$num_replies[$j][2]);
        $t->set_var("num_replies",$num_replies[$j][0]);
        $t->set_var("width",$width);
        $t->parse("argumentative_user_block_handle","argumentative_user_block",true);

	}

	$t->parse("argumentative_user_TOP_block_handle","argumentative_user_TOP_block",true);
	
}

?>