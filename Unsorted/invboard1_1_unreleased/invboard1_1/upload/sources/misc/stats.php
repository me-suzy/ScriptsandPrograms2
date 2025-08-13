<?php

/*
+--------------------------------------------------------------------------
|   Invision Board v1.1
|   ========================================
|   by Matthew Mecham
|   (c) 2001,2002 Invision Power Services
|   http://www.ibforums.com
|   ========================================
|   Web: http://www.ibforums.com
|   Email: phpboards@ibforums.com
|   Licence Info: phpib-licence@ibforums.com
+---------------------------------------------------------------------------
|
|   > Topic Tracker module
|   > Module written by Matt Mecham
|   > Date started: 6th March 2002
|
|	> Module Version Number: 1.0.0
+--------------------------------------------------------------------------
*/


$idx = new stats;

class stats {

    var $output    = "";
    var $base_url  = "";
    var $html      = "";
	var $forum     = "";
	
    function stats() {
    
    	//------------------------------------------------------
    	// $is_sub is a boolean operator.
    	// If set to 1, we don't show the "topic subscribed" page
    	// we simply end the subroutine and let the caller finish
    	// up for us.
    	//------------------------------------------------------
    
        global $ibforums, $DB, $std, $print, $skin_universal;
        
        $ibforums->lang    = $std->load_words($ibforums->lang, 'lang_stats', $ibforums->lang_id );

    	$this->html = $std->load_template('skin_stats');
    	
    	$this->base_url        = "{$ibforums->vars['board_url']}/index.{$ibforums->vars['php_ext']}?s={$ibforums->session_id}";
    	
    	
    	
    	//--------------------------------------------
    	// What to do?
    	//--------------------------------------------
    	
    	switch($ibforums->input['CODE'])
    	{
    		case 'leaders':
    			$this->show_leaders();
    			break;
    		case '02':
    			$this->do_search();
    			break;
    		case 'id':
    			$this->show_queries();
    			break;
    			
    		case 'who':
    			$this->who_posted();
    			break;
    			
    		default:
    			$this->show_today_posters();
    			break;
    	}
    	
    	// If we have any HTML to print, do so...
    	
    	$print->add_output("$this->output");
        $print->do_output( array( 'TITLE' => $this->page_title, 'JS' => 0, NAV => $this->nav ) );
    		
 	}
 	
 	function who_posted()
 	{
 		global $ibforums, $DB, $std, $print;
 		
 		$tid = intval(trim($ibforums->input['t']));
 		
 		$to_print = "";
 		
 		$this->check_access($tid);
 		
 		$DB->query("SELECT COUNT(p.pid) as pcount, p.author_id, p.author_name FROM ibf_posts p
 				    WHERE p.topic_id=$tid AND queued <> 1 GROUP BY p.author_name ORDER BY pcount DESC");
 		
 		if ( $DB->get_num_rows() )
 		{
 		
 			$to_print = $this->html->who_header($this->forum['id'], $tid, $this->forum['topic_title']);
 			
 			while( $r = $DB->fetch_row() )
 			{
 				if ($r['author_id'])
 				{
 					$r['author_name'] = $this->html->who_name_link($r['author_id'], $r['author_name']);
 				}
 				
 				$to_print .= $this->html->who_row($r);
 			}
 			
 			$to_print .= $this->html->who_end();
 		}
 		else
 		{
 			$std->Error( array( 'LEVEL' => 1, 'MSG' => 'missing_files') );
 		}
 		
 		$print->pop_up_window("",$to_print);
 		
 		exit();
 	}
 	
 	//--------------------------------
 	
 	function check_access($tid)
    {
		global $ibforums, $DB, $std, $HTTP_COOKIE_VARS;
		
		if ( ! $ibforums->member['id'] )
		{
			$std->Error( array( 'LEVEL' => 1, 'MSG' => 'no_permission') );
		}
		
		//--------------------------------
		
		$DB->query("SELECT t.title as topic_title, f.read_perms, f.password, f.id from ibf_forums f, ibf_topics t WHERE t.tid=$tid and f.id=t.forum_id");
        
        $this->forum = $DB->fetch_row();
		
		$return = 1;
		
		if ($this->forum['read_perms'] == '*')
		{
			$return = 0;
		}
		else if (preg_match( "/(^|,)".$ibforums->member['mgroup']."(,|$)/", $this->forum['read_perms'] ) )
		{
			$return = 0;
		}
		
		if ($this->forum['password'])
		{
			if ($HTTP_COOKIE_VARS[ $ibforums->vars['cookie_id'].'iBForum'.$this->forum['id'] ] == $this->forum['password'])
			{
				$return = 0;
			}
		}
		
		if ($return == 1)
		{
			$std->Error( array( 'LEVEL' => 1, 'MSG' => 'no_permission') );
		}
	
	}
 	
 	//--------------------------------
 	
 	function show_leaders()
 	{
 		global $ibforums, $DB, $std;
 		
 		$this->output .= $this->html->page_title( $ibforums->lang['forum_leaders'] );
 		
 		//--------------------------------------------
    	// Work out where our super mods are at
    	//--------------------------------------------
    	
    	$sup_ids = array();
    	
    	$DB->query("SELECT g_id from ibf_groups WHERE g_is_supmod = 1");
    	
    	if ( $DB->get_num_rows() )
    	{
    		while ( $i = $DB->fetch_row() )
    		{
    			$sup_ids[] = $i['g_id'];
    		}
    	}
    	
    	//--------------------------------------------
    	// Get our admins
    	//--------------------------------------------
    	
    	$admin_ids = array();
    	
    	$DB->query("SELECT m.id, m.name, m.email, m.hide_email, m.location, m.aim_name, m.icq_number, g.g_access_cp
    			    FROM ibf_members m, ibf_groups g
    			    WHERE g.g_access_cp=1 AND m.mgroup=g.g_id ORDER BY m.name");
    	
    	$this->output .= $this->html->group_strip( $ibforums->lang['leader_admins'] );
    	
    	while ( $member = $DB->fetch_row() )
    	{
    		$this->output .= $this->html->leader_row( $this->parse_member( $member ), $ibforums->lang['leader_all_forums'] );
    		
    		$admin_ids[] = $member['id'];
    	}
    	
    	$this->output .= $this->html->close_strip();
    	
    	//--------------------------------------------
    	// Do the bizz with the super men, er mods.
    	//--------------------------------------------
    	
    	$admin_ids[] = '0';
    	
    	if ( count($sup_ids) > 0 )
    	{
    		
    		$DB->query("SELECT id, name, email, hide_email, location, aim_name, icq_number from ibf_members WHERE mgroup IN (".implode( ',', $sup_ids ).") and id NOT IN(".implode(',', $admin_ids).")");
    	
    		if ( $DB->get_num_rows() )
    		{
    			$this->output .= $this->html->group_strip( $ibforums->lang['leader_global'] );
    			
    			while ( $member = $DB->fetch_row() )
				{
					$this->output .= $this->html->leader_row( $this->parse_member( $member ), $ibforums->lang['leader_all_forums'] );
				}
				
				$this->output .= $this->html->close_strip();
			}
			
		}
		
		//--------------------------------------------
    	// Do we have any moderators?.
    	//--------------------------------------------
    	
    	$DB->query("SELECT m.id, m.name, m.email, m.hide_email, m.location, m.aim_name, m.icq_number, f.id as forum_id, f.read_perms, f.name as forum_name FROM ibf_members m, ibf_forums f, ibf_moderators mod "
    	          ."WHERE m.id=mod.member_id and f.id=mod.forum_id");
    	          
    	$data = array();
    	
    	while ( $i = $DB->fetch_row() )
    	{
    		if ( preg_match( "/(^|,)".$ibforums->member['mgroup']."(,|$)/", $i['read_perms'] ) )
    		{
    			$data[] = $i;
    		}
    		else if ( $i['read_perms'] == '*' )
    		{
    			$data[] = $i;
    		}
    	}
    	
    	//------------------------
    	          
    	if ( count($data) > 0 )
    	{
    		$mod_array = array();
    		
    		$this->output .= $this->html->group_strip( $ibforums->lang['leader_mods'] );
    		
    		foreach ( $data as $idx => $i )
    		{
    			if ( !isset( $mod_array['member'][ $i['id'] ][ 'name' ] ) )
    			{
    				// Member is not already set, lets add the member...
    				
    				$mod_array['member'][ $i['id'] ] = array( 'name'       => $i['name'],
    														  'email'      => $i['email'],
    														  'hide_email' => $i['hide_email'],
    														  'location'   => $i['location'],
    														  'aim_name'   => $i['aim_name'],
    														  'icq_number' => $i['icq_number'],
    														  'id'         => $i['id']
    														);
    														
    			}
    			
    			// Add forum..	
    				
    			$mod_array['forums'][ $i['id'] ][] = "<a href='".$ibforums->base_url."&act=SF&f=".$i['forum_id']."'>".$i['forum_name']."</a>";
    		}
    		
    		foreach( $mod_array['member'] as $id => $data )
    		{
    			$this->output .= $this->html->leader_row( 
														   $this->parse_member( $mod_array['member'][ $id ] ),
														   implode( "<br>", $mod_array['forums'][ $id ] )
														);
    														  
    		}
    		
    		$this->output .= $this->html->close_strip();
    		
    	}
    	
    	$this->page_title = $ibforums->lang['forum_leaders'];
    	$this->nav        = array( $ibforums->lang['forum_leaders'] );
    	
 	}
 	
 	function show_queries()
 	{
 		global $ibforums, $DB, $std;
 		
 		// show DB queries in graphic format(depreciated)
 		// left here to stop other functions breaking
 		flush();
 		header("Content-type: image/gif");
		echo base64_decode("R0lGODlhhgAfAMQAAAAAAP///+/v79/f38/Pz7+/v6+vr5+fn4+Pj4CAgHBwcGBgYFBQUEBAQDAwMCAgIBAQEAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAACwAAAAAhgAfAAAF/2AgjmRpnmiqrmzrvnAsz3Rt33iu7/x8mL8AgcEgiBINg2i4EAiJxtEhmlKYrCpCY5uYYXurQ8GUGAQeB8NDYFgU1oFkwopWO4/jrImKKrgLDXkwDWAsCyICC0VCCARYCQUKRggGAgwiDY54I5ABBwpFBVEGAz+AWD+JiwUGDV0FXQGwAapGia4iBwwGWgeEhSq/CwgHDpgkCwO/sLNxyGabEAgJCwSHAcaEDn4/hMPFAUhvA80KP9/GCgoFDkYApA5tv8AnBLHGAUoBlyKAzrKQYs3zx2kMPm1CDhF6UECAE4QBqiVQAqkAgIsQnOCrpm8dtoQBlNHrI7DELwJwlv8FPCYETsF/hBQYmOjsAIAHRgj9CjimYqxP/yANAQBhDKFm80aSaIZPHyEB7UQk83RgwLWnUUl0+kXIgAIHD0MKQHAJ4rpOPkeYLbArnFFZVgQkVSpiAJYiKEVcWpRLgVwzDga02cdnE1dM/LQJGOKsCFS3bpsdOOQ4G4EBDP+BRTCX7r4lDyDoK3LxYsMGAFBBwEmgNABBW1kOO3Zgdc6WEH6gLWDxYgMzKHPnW72gWy4ICzrTbWLCQBDP0KP3eT7Cr/Tr2PuZEJS9u/fv4MOLH1/3DowBTgrLQK9CALQR7lewnxEfTKcRh8SoYAD4xpQs1AkRIBncwdBMDxXdQcj/fQIU6IxVO1yWQoPmLfFeg3g0NIKESwiyGC28xTKChiCS8OEA6pWQgAMNPBCHa4A04IAVnUA4WRde5YNLjq3gaMVMGcoIgTVRbJMAVDICFMBXosXBgAOXrJjkkg40qcADLIaTJYsOiIhNAxAMgJkD+GD5wFcPIKBCAl244cxWP4AlhZrVxHSJJHYuqcWSd0ZRESEHTPTjWbMgQM6RxjwWyBnhWCHTY4qKYEwCauaogIg5HvCJEjIFkJEAnyr3UmyxQSaCJI3t4xche636GQOuZvhKF8ZUA8sAyI3BTEmHdbIrSzNBAJmvIt73zyyH/YPCbm++ZepHcgWAAFuaXjIt6wPVSkutLgXNMksRxsxiTVG/OtOrriuZy8CKw6L7EkvIsiTqJqQaZQQDd0QLpBaGNqAmv8r862/A3c7qyQKH3FppQAPwk80xCfxATMOS/uNiJweoOc2cniBQjbTGwbRmT2PklgB/Wyhw1KGmDukpNC6HKULMYpLsbRegKjHLAyuGGAcoXfTKc0pAn6GysJ0IMHQXqfAcJjsJpCSvCgWYUXU+BwhQRiYIOFH11c8FEXYuUpBNjiw1h5RH1mqHVOMYY4lNtihlIEJMXZCQcrXbBIwRxAD33Uq2J4PPMC95iNdweOKMN+7445BfFwIAOw==");
		exit();
 	}
 	
 	
 	
 	function show_today_posters()
 	{
 		global $ibforums, $DB, $std;
 		
 		$this->output .= $this->html->page_title( $ibforums->lang['todays_posters'] );
 		
 		$this->output .= $this->html->top_poster_header();
 		
 		$time_high = time();
 		
 		$time_low = $time_high - (60*60*24);
 		
 		//--------------------------------------------
    	// Query the DB
    	//--------------------------------------------
    	
    	$DB->query("SELECT COUNT(pid) as count FROM ibf_posts WHERE post_date < $time_high and post_date > $time_low");
    	$todays_posts = $DB->fetch_row();
    	
    	if ($todays_posts['count'] > 0)
    	{
    	
			$DB->query("SELECT COUNT(p.pid) as tpost, m.id, m.name, m.joined, m.posts FROM ibf_posts p, ibf_members m "
					  ."WHERE m.id > 0 AND m.id=p.author_id and post_date < $time_high and post_date > $time_low GROUP BY p.author_id ORDER BY tpost DESC LIMIT 0,10");
					  
			if ( $DB->get_num_rows() )
			{
			
				while ($info = $DB->fetch_row())
				{
					
					$info['total_today_posts'] = $todays_posts['count'];
					
					if ($todays_posts['count'] > 0 and $info['tpost'] > 0)
					{
						$info['today_pct']     = sprintf( '%.2f',  ( $info['tpost'] / $todays_posts['count'] ) * 100  );
					}
					
					$info['joined']            = $std->get_date( $info['joined'], 'JOINED' );
					
					$this->output .= $this->html->top_poster_row( $info );
				}
			}
			else
			{
				$this->output .= $this->html->top_poster_no_info();
			}
		}
		else
		{
			$this->output .= $this->html->top_poster_no_info();
		}
		
		$this->output .= $this->html->top_poster_footer( $todays_posts['count'] );
		
		$this->page_title = $ibforums->lang['top_poster_title'];
		
		$this->nav = array( $ibforums->lang['top_poster_title'] );
		
	}
	
//------------------------------------------------------------------------------------------------

	function parse_member( $member )
	{
		global $ibforums, $std;
		
		$member['msg_icon'] = "<a href='{$this->base_url}&act=Msg&CODE=04&MID={$member['id']}'><{P_MSG}></a>";
			
		if (!$member['hide_email'])
		{
			$member['email_icon'] = "<a href='{$this->base_url}&act=Mail&CODE=00&MID={$member['id']}'><{P_EMAIL}></a>";
		}
		else
		{
			$member['email_icon'] = '&nbsp;';
		}
		
		if ($member['icq_number'])
		{
			$member['icq_icon'] = "<a href=\"javascript:PopUp('{$this->base_url}&act=ICQ&MID={$member['id']}','Pager','450','330','0','1','1','1')\"><{P_ICQ}></a>";
		}
		else
		{
			$member['icq_iconn'] = '&nbsp;';
		}
		
		if ($member['aim_name'])
		{
			$member['aol_icon'] = "<a href=\"javascript:PopUp('{$this->base_url}&act=AOL&MID={$member['id']}','Pager','450','330','0','1','1','1')\"><{P_AOL}></a>";
		}
		else
		{
			$member['aol_icon'] = '&nbsp;';
		}
				
			return $member;
		
	}
        
}

?>





