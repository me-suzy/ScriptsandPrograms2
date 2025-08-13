<?php

/*
=====================================================
 ExpressionEngine - by pMachine
-----------------------------------------------------
 Nullified by GTT
-----------------------------------------------------
 Copyright (c) 2003 - 2004 pMachine, Inc.
=====================================================
 THIS IS COPYRIGHTED SOFTWARE
 PLEASE READ THE LICENSE AGREEMENT
=====================================================
 File: mcp.stats.php
-----------------------------------------------------
 Purpose: Statistical tracking module - backend
=====================================================
*/

if ( ! defined('EXT'))
{
    exit('Invalid file request');
}


class Stats_CP {

    var $version	= '1.0';
    var $stats		= array();
    


    // --------------------------------
    //  Update statistics
    // --------------------------------
        
    function update_stats()
    {    
		global $IN, $FNS, $LOC, $DB, $SESS;
		
		$time_limit = 15; // Number of minutes to track users
				
		// --------------------------------
		//  Set weblog ID
		// --------------------------------

		$weblog_id = (USER_BLOG !== FALSE) ? UB_BLOG_ID : 0;


		// --------------------------------
		//  Fetch current user's name
		// --------------------------------

        if ($SESS->userdata['member_id'] != 0)
        {
            $name = ($SESS->userdata['screen_name'] == '') ? $SESS->userdata['username'] : $SESS->userdata['screen_name'];
        }
        else
        {
            $name = '';
        }
        
        // Is user browsing anonymously?
        
        $anon = ( ! $IN->GBL('anon', 'COOKIE')) ? '' : 'y';
		

		// --------------------------------
		//  Fetch online users
		// --------------------------------
			
		$cutoff = $LOC->now - ($time_limit * 60);
		
		$sql = "SELECT * FROM exp_online_users WHERE date > $cutoff AND weblog_id = '$weblog_id' ORDER BY name";		
	
		$query = $DB->query($sql);
		
		
		// -------------------------------------------
		//  Assign users to a multi-dimensional array
		// -------------------------------------------
		
		$users = array();
		
		if ($query->num_rows > 0)
		{
            foreach ($query->result as $row)
            {
                $users[$row['ip_address']] = array('member_id' => $row['member_id'], 'name' => $row['name'], 'anon' => $row['anon']);
            }
        }
        
		// -------------------------------------------
		//  Set the "update" pref, which we'll use later
		// -------------------------------------------
        
        if (isset($users[$IN->IP]))
        {
            $update = TRUE;
            
            $total_visitors = $query->num_rows;
            
            if ($anon == '')
            {
                $anon = $users[$IN->IP]['anon'];
            }
        }
        else
        {
            $update = FALSE;
            
            $total_visitors = $query->num_rows + 1;
        }
        
		// -------------------------------------------
		// Add current user in the result set
		// -------------------------------------------
				
        $users[$IN->IP] = array('member_id' => $SESS->userdata['member_id'], 'name' => $name, 'anon' => $anon);
		
		
		// --------------------------------
		//  Tally the result
		// --------------------------------
        
		$total_logged	= 0;
		$total_guests	= 0;
		$total_anon	    = 0;
		$current_names	= array();
        
        foreach ($users as $key => $val)
        {
            if ($val['member_id'] != 0)
            {
                $current_names[$val['member_id']] = array($val['name'], $val['anon']);

                if ($val['anon'] != '')
                {		
                    $total_anon++;
                }
                else
                {	
                    $total_logged++;
                }
            }
            else
            {
                $total_guests++;
            }
        }

		// --------------------------------
		//  Update online_users table
		// --------------------------------

		$data = array(
						'weblog_id'		=> $weblog_id,
						'member_id'		=> $SESS->userdata['member_id'],
						'name'			=> $name,
						'ip_address'	=> $IN->IP,
						'date'			=> $LOC->now,
						'anon'			=> $anon
					);

		if ($update == FALSE)
		{
        	$DB->query($DB->insert_string('exp_online_users', $data));
		}
		else
		{
        	$DB->query($DB->update_string('exp_online_users', $data, "ip_address='$IN->IP'"));
		}


		// --------------------------------
		//  Fetch global statistics
		// --------------------------------

		$sql = "SELECT * FROM exp_stats WHERE weblog_id = '$weblog_id'";
		
		$query = $DB->query($sql);
		
		// --------------------------------
		//  Assign the stats
		// --------------------------------
				
		$this->stats = array(
								'total_members'				=> $query->row['total_members'],
								'total_entries'				=> $query->row['total_entries'],
								'total_comments'			=> $query->row['total_comments'],
								'total_trackbacks'			=> $query->row['total_trackbacks'],
								'most_visitors'				=> ($total_visitors > $query->row['most_visitors']) ? $total_visitors : $query->row['most_visitors'],
								'last_entry_date'			=> $query->row['last_entry_date'],
								'last_comment_date'			=> $query->row['last_comment_date'],
								'last_visitor_date'			=> $LOC->now,
								'most_visitor_date'			=> ($total_visitors >= $query->row['most_visitors']) ? $LOC->now : $query->row['most_visitor_date'],
							);
						
		$DB->query($DB->update_string('exp_stats', $this->stats, "weblog_id = '$weblog_id'"));
		
		$this->stats['total_logged_in']	= $total_logged;
		$this->stats['total_guests']	= $total_guests;
		$this->stats['total_anon']		= $total_anon;
		$this->stats['current_names']	= $current_names;

        srand(time());
  
        if ((rand() % 100) < $SESS->gc_probability) 
        {                 
            $DB->query("DELETE FROM exp_online_users WHERE date < $cutoff AND weblog_id = '$weblog_id'");             
        }    
	}
	// END



    // -------------------------------------
    //  Fetch Weblog ID numbers for query
    // -------------------------------------

	function fetch_weblog_ids()
	{
		global $DB;
		
		$sql = '';
	
		if (USER_BLOG === FALSE)
		{
			$query = $DB->query("SELECT weblog_id FROM exp_weblogs WHERE is_user_blog = 'n'");
			
			$sql .= " (";
				
			foreach ($query->result as $row)
			{
				$sql .= " weblog_id = '".$row['weblog_id']."' OR";
			}
			
			$sql = substr($sql, 0, -2).")";
		}
		else
		{
			$sql .= " weblog_id = '".UB_BLOG_ID."'";
		}
	
		return $sql;
	}
	// END


    // -------------------------------
    //  Update Member Stats
    // ------------------------------- 
  
    function update_member_stats()
    {
    	global $DB;
    	  
		$weblog_id = (USER_BLOG === FALSE) ? 0 : UB_BLOG_ID;

        $query = $DB->query("SELECT count(*) AS count FROM exp_members WHERE group_id != '4' AND group_id != '2'");
        
        $sql = "UPDATE exp_stats SET total_members = '".$query->row['count']."' WHERE weblog_id = '$weblog_id'";
                
		$DB->query($sql);
	}
	// END

    
    // -------------------------------
    //  Update Weblog Stats
    // ------------------------------- 
  
    function update_weblog_stats($weblog_id = '')
    {
    	global $LOC, $DB;
    	  
        // Update global stats table  
    	  
		$user_blog_id = (USER_BLOG === FALSE) ? 0 : UB_BLOG_ID;
		
		$blog_ids = $this->fetch_weblog_ids();
		
        $query = $DB->query("SELECT count(*) AS count FROM exp_weblog_titles WHERE ".$blog_ids." AND entry_date < ".$LOC->now." AND (expiration_date = 0 || expiration_date > ".$LOC->now.") AND status != 'closed'");
        
        $total = $query->row['count'];
        
        $query = $DB->query("SELECT entry_date FROM exp_weblog_titles WHERE ".$blog_ids." AND entry_date < ".$LOC->now." AND (expiration_date = 0 || expiration_date > ".$LOC->now.") AND status != 'closed' ORDER BY entry_date desc LIMIT 1");
        
        $date = ($query->num_rows == 0) ? 0 : $query->row['entry_date'];
                                
        $DB->query("UPDATE exp_stats SET total_entries = '$total', last_entry_date = '$date' WHERE weblog_id = '$user_blog_id'");
        
        
        // Update exp_weblog table
		
		if ($weblog_id != '')
		{
            $query = $DB->query("SELECT count(*) AS count FROM exp_weblog_titles WHERE weblog_id = '$weblog_id' AND entry_date < ".$LOC->now." AND (expiration_date = 0 || expiration_date > ".$LOC->now.") AND status != 'closed'");
            
            $total = $query->row['count'];
            
            $query = $DB->query("SELECT entry_date FROM exp_weblog_titles WHERE weblog_id = '$weblog_id' AND entry_date < ".$LOC->now." AND (expiration_date = 0 || expiration_date > ".$LOC->now.") AND status != 'closed' ORDER BY entry_date desc LIMIT 1");
            
            $date = ($query->num_rows == 0) ? 0 : $query->row['entry_date'];
                                
            $DB->query("UPDATE exp_weblogs SET total_entries = '$total', last_entry_date = '$date' WHERE weblog_id = '$weblog_id'");
        }
	}
	// END
	
	
	
    // -------------------------------
    //  Update Comment Stats
    // ------------------------------- 
  
    function update_comment_stats($weblog_id = '')
    {  
    	global $LOC, $DB;
    	    	
        // Update global stats table  

		$user_blog_id = (USER_BLOG === FALSE) ? 0 : UB_BLOG_ID;
		
		$blog_ids = $this->fetch_weblog_ids();

        $query = $DB->query("SELECT count(*) AS count FROM exp_comments WHERE status = 'o' AND ".$blog_ids);
        
        $total = $query->row['count'];
        
        $query = $DB->query("SELECT comment_date FROM exp_comments WHERE  status = 'o' AND ".$blog_ids." ORDER BY comment_date desc LIMIT 1");
        
        $date = ($query->num_rows == 0) ? 0 : $query->row['comment_date'];
                                
		$DB->query("UPDATE exp_stats SET total_comments = '$total', last_comment_date = '$date' WHERE weblog_id = '$user_blog_id'");
		
		
        // Update exp_weblog table

		if ($weblog_id != '')
		{
            $query = $DB->query("SELECT count(*) AS count FROM exp_comments WHERE  status = 'o' AND weblog_id = '$weblog_id'");
            
            $total = $query->row['count'];
            
            $query = $DB->query("SELECT comment_date FROM exp_comments WHERE status = 'o' AND weblog_id = '$weblog_id' ORDER BY comment_date desc LIMIT 1");
            
            $date = ($query->num_rows == 0) ? 0 : $query->row['comment_date'];
                                
            $DB->query("UPDATE exp_weblogs SET total_comments = '$total', last_comment_date = '$date' WHERE weblog_id = '$weblog_id'");
		}
	}
	// END


    // -------------------------------
    //  Update Trackback Stats
    // ------------------------------- 
  
    function update_trackback_stats($weblog_id = '')
    {  
    	global $LOC, $DB;

        // Update global stats table

		$user_blog_id = (USER_BLOG === FALSE) ? 0 : UB_BLOG_ID;
		
		$blog_ids = $this->fetch_weblog_ids();

        $query = $DB->query("SELECT count(*) AS count FROM exp_trackbacks WHERE ".$blog_ids);
        
        $total = $query->row['count'];
        
        $query = $DB->query("SELECT trackback_date FROM exp_trackbacks WHERE ".$blog_ids." ORDER BY trackback_date desc LIMIT 1");
        
        $date = ($query->num_rows == 0) ? 0 : $query->row['trackback_date'];
                
		$DB->query("UPDATE exp_stats SET total_trackbacks = '$total', last_trackback_date = '$date' WHERE weblog_id = '$user_blog_id'");
		
        // Update exp_weblog table
		
		if ($weblog_id != '')
		{
            $query = $DB->query("SELECT count(*) AS count FROM exp_trackbacks WHERE weblog_id = '$weblog_id'");
            
            $total = $query->row['count'];
            
            $query = $DB->query("SELECT trackback_date FROM exp_trackbacks WHERE weblog_id = '$weblog_id' ORDER BY trackback_date desc LIMIT 1");
            
            $date = ($query->num_rows == 0) ? 0 : $query->row['trackback_date'];
                        
            $DB->query("UPDATE exp_weblogs SET total_trackbacks = '$total', last_trackback_date = '$date' WHERE weblog_id = '$weblog_id'");
        }		
	}
	// END



    // --------------------------------
    //  Module installer
    // --------------------------------

    function stats_module_install()
    {
        global $DB;        
        
        $sql[] = "INSERT INTO exp_modules (module_id, module_name, module_version, has_cp_backend) VALUES ('', 'Stats', '$this->version', 'n')";        
    
        foreach ($sql as $query)
        {
            $DB->query($query);
        }
        
        return true;
    }
    // END
    
    
    // -------------------------
    //  Module de-installer
    // -------------------------

    function stats_module_deinstall()
    {
        global $DB;    

        $query = $DB->query("SELECT module_id FROM exp_modules WHERE module_name = 'Stats'"); 
                
        $sql[] = "DELETE FROM exp_module_member_groups WHERE module_id = '".$query->row['module_id']."'";        
        $sql[] = "DELETE FROM exp_modules WHERE module_name = 'Stats'";
        $sql[] = "DELETE FROM exp_actions WHERE class = 'Stats'";
        $sql[] = "DELETE FROM exp_actions WHERE class = 'Stats_CP'";
    
        foreach ($sql as $query)
        {
            $DB->query($query);
        }

        return true;
    }
    // END


}
// END CLASS
?>