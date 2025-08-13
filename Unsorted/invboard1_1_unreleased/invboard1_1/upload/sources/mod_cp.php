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
|   > Moderation Control Panel module
|   > Module written by Matt Mecham
|   > Date started: 19th February 2002 / Revised Start: 23rd September
|
|   > Module Version 2.0.0
+--------------------------------------------------------------------------
*/


$idx = new Moderate;

class Moderate {

    var $output    = "";
    var $base_url  = "";
    var $html      = "";

    var $moderator = array();
    var $forum     = array();
    var $topic     = array();
    var $tids      = array();
    
    var $upload_dir = "";
    
    var $topic_id   = "";
    var $forum_id   = "";
    var $post_id    = "";
    var $start_val  = 0;

    
    /***********************************************************************************/
	//
	// Our constructor, load words, load skin, print the topic listing
	//
	/***********************************************************************************/
    
    function Moderate() {
    
        global $ibforums, $DB, $std, $print, $skin_universal;
        
        //-------------------------------------
		// Compile the language file
		//-------------------------------------
		
        $ibforums->lang  = $std->load_words($ibforums->lang, 'lang_modcp', $ibforums->lang_id);
        $ibforums->lang  = $std->load_words($ibforums->lang, 'lang_topic', $ibforums->lang_id);

        $this->html      = $std->load_template('skin_modcp');
        
        //-------------------------------------
        // Check the input
        //-------------------------------------
        
        $this->forum_id  = intval($ibforums->input['f']);
        $this->start_val = $ibforums->input['st'] ? intval($ibforums->input['st']) : 0;
        $this->topic_id  = intval($ibforums->input['t']);
        $this->post_id   = intval($ibforums->input['p']);
        
        $this->base_url  = $ibforums->base_url;
        
        //-------------------------------------
        // Make sure we're a moderator...
        //-------------------------------------
        
        $pass = 0;
        
        if ($ibforums->member['id'])
        {
        	if ($ibforums->member['g_is_supmod'] == 1)
        	{
        		$pass = 1;
        	}
        	else if ($ibforums->member['is_mod'])
        	{
        		// Load mod..
        
        		$DB->query("SELECT * FROM ibf_moderators WHERE  (member_id='".$ibforums->member['id']."' OR (is_group=1 AND group_id='".$ibforums->member['mgroup']."'))");
				if ( $this->moderator = $DB->fetch_row() )
				{
					$pass = 1;
				}
        	}
        	else
        	{
        		$pass = 0;
        	}
        }
        
        if ($pass == 0)
        {
        	$std->Error( array( LEVEL => 1, MSG => 'no_permission') );
        }
        
        
        $this->upload_dir = $ibforums->vars['upload_dir'];
        
        $this->upload_dir = preg_replace( "!/$!", "", $this->upload_dir );
        
        // start the output
        
        $this->output = $this->html->mod_cp_start();
        
        //-------------------------------------
        // Convert the code ID's into something
        // use mere mortals can understand....
        //-------------------------------------
        
        switch ($ibforums->input['CODE']) {
        
        	case 'members':
        		$this->find_user_one();
        		break;
        	case 'edituser':
        		$this->find_user_one(); // Left for backwards compatibility
        		break;
        	case 'dofinduser':
        		$this->find_user_two();
        		break;
        	case 'doedituser':
        		$this->edit_user();
        		break;
        	case 'compedit':
        		$this->complete_user_edit();
        		break;
        	
        	//-------------------------
        	
        	case 'prune':
        		$this->prune_juice();
        		break;
        	case 'doprune':
        		$this->drink_prune_juice();  // eew!
        		break;
        	case 'domove':
        		$this->do_move();
        		break;
        		
        	//-------------------------
        	
        	case 'modtopics':
        		$this->mod_topics();
        		break;
        	case 'domodtopics':
        		$this->domod_topics();
        		break;
        		
        	case 'modposts':
        		$this->mod_posts();
        		break;
        	case 'modtopicview':
        		$this->mod_topicview();
        		break;
        		
        	case 'domodposts':
        		$this->mod_domodposts();
        		break;
        		
        	case 'modtopicapprove':
        		$this->approve_all();
        		break;
        		
        	//-------------------------
        		
			case 'fchoice':
				switch ( $ibforums->input['fact'] )
				{
					case 'mod_topic':
						$this->mod_topics();
						break;
					case 'mod_post':
						$this->mod_posts();
						break;
					case 'prune_move':
						$this->prune_juice();
						break;
					default:
						exit();
						break; // Yeah, like it'll get here
				}
				break;
				
			case 'topicchoice':
			
				$this->tids  = $this->get_tids();
				$this->load_forum();
				
				switch ( $ibforums->input['tact'] )
				{
					case 'close':
						$this->alter_topics('close_topic', "state='closed'");
						break;
					case 'open':
						$this->alter_topics('open_topic', "state='open'");
						break;
					case 'pin':
						$this->alter_topics('pin_topic', "pinned=1");
						break;
					case 'unpin':
						$this->alter_topics('unpin_topic', "pinned=0");
						break;
					case 'delete':
						$this->delete_topics();
						break;
					case 'move':
						$this->start_checked_move();
						break;
					case 'domove':
						$this->complete_checked_move();
						break;
					default:
						exit();
						break; // Yeah, like it'll get here
				}
				break;
        		
        	//-------------------------
        	
        	case 'showforums':
        		$this->show_forums();
        		break;
        		
        	case 'showtopics':
        		$this->show_topics();
        		break;
        	case 'ip':
        		$this->ip_start();
        		break;
        	case 'doip':
        		$this->do_ip();
        		break;
        	
        	default:
        		$this->show_forums();
        		break;
        }
		
		if ( count($this->nav) < 1 )
		{
			$this->nav[] = "<a href='{$this->base_url}&act=modcp'>{$ibforums->lang['cp_modcp_home']}</a>";
		}
		
		if (! $this->page_title )
		{
			$this->page_title = $ibforums->lang['cp_modcp_ptitle'];
		}
    	
    	$print->add_output("$this->output");
        $print->do_output( array( 'TITLE' => $this->page_title, 'JS' => 1, 'NAV' => $this->nav ) );
      
	}
	
	//-------------------------------------------------
	// IP STUFF!
	//-------------------------------------------------
	
	function ip_start()
	{
		global $std, $ibforums, $DB, $print;
		
		$pass = 0;
		
		if ($ibforums->member['g_is_supmod'] == 1)
		{
			$pass = 1;
		}
		else if ($this->moderator['view_ip'] == 1)
		{
			$pass = 1;
		}
		else
		{
			$pass = 0;
		}
		
		if ($pass == 0)
		{
			$this->mod_error('cp_no_perms');
			return;
		}
		
		$ip_arr = array();
		
		if ($ibforums->input['incoming'] != "")
		{
			$ip_arr = explode( ".", $ibforums->input['incoming'] );
		}
		
		$this->output .= $this->html->ip_start_form($ip_arr);
	}
	
	//-------------------------------------------------------------------------------
	
	function do_ip()
	{
		global $std, $ibforums, $DB, $print;
		
		$pass = 0;
		
		if ($ibforums->member['g_is_supmod'] == 1)
		{
			$pass = 1;
		}
		else if ($this->moderator['view_ip'] == 1)
		{
			$pass = 1;
		}
		else
		{
			$pass = 0;
		}
		
		if ($pass == 0)
		{
			$this->mod_error('cp_no_perms');
			return;
		}
		
		// check to make sure we have enough input.
		
		$ip_array = array();
		$ip_bit_count = "";  // init var to count how many "real" IP bits we have
		
		foreach( array( 'ip1', 'ip2', 'ip3', 'ip4') as $ip_bit )
		{
			if ($ibforums->input[$ip_bit] != '*')
			{
				$ibforums->input[$ip_bit] = intval($ibforums->input[$ip_bit]);
				
				if (!isset($ibforums->input[$ip_bit]))
				{
					continue;
				}
				
				if ($ibforums->input[$ip_bit] < 1)
				{
					$ibforums->input[$ip_bit] = 0;
				}
				
				$ip_array[$ip_bit] = $ibforums->input[$ip_bit];
				$ip_bit_count++;
			}
			else
			{
				$ip_array[$ip_bit] = '*';
			}
		}
		
		// ensure we have at least 127.*
		
		if ( count($ip_array) < 2 )
		{
			$this->mod_error('cp_error_ip');
			return;
		}
		
		// ensure we don't have *.*
		
		if ($ip_bit_count < 1)
		{
			$this->mod_error('cp_error_ip');
			return;
		}
		
		$test_ip_string = $ip_array['ip1'].'%'.$ip_array['ip2'].'%'.$ip_array['ip3'].'%'.$ip_array['ip4'];
		
		// Check to make sure we don't have 123%*%123%0%0 or similar (of course)
		// Test for *%({numeric})  (*.127 for example...)
		
		if ( preg_match( "/\*%\d+(%|$)/", $test_ip_string) )
		{
			$this->mod_error('cp_error_ip');
			return;
		}
		
		// Ok, lets finalize the IP string, using the * as the stop character
		
		$final_ip_string = "";
		$exact_match = 1;
		
		foreach( $ip_array as $final_bits)
		{
			if ($final_bits == '0')
			{
				$final_ip_string .= '0.';
			}
			else if ($final_bits == '*')
			{
				$final_ip_string .= "%"; //SQL find any
				$exact_match = 0;
				break; // break out of foreach as we're done
			}
			else
			{
				$final_ip_string .= $final_bits.'.';
			}
		}
		
		// Remove trailing periods
		
		$final_ip_string = preg_replace( "/\.$/", "", $final_ip_string );
		
		//print $final_ip_string."<br>".$test_ip_string."<br>".implode('.', $ip_array); exit();
		
		// See, a gazillion lines of code just to ensure that the user read the frikken manual.
		
		// H'okay, what have we been asked to do? (that's a metaphorical "we" in a rhetorical question)
		
		if ($ibforums->input['iptool'] == 'resolve')
		{
			// Attempt a trival gethostbyaddr
			
			if ( $ip_bit_count != 4 )
			{
				$this->mod_error('cp_error_resolveip');
				return;
			}
			
			$resolved = @gethostbyaddr($final_ip_string);
			
			if ($resolved == "")
			{
				$this->mod_error('cp_safe_fail');
				return;
			}
			else
			{
				$ibforums->lang['ip_resolve_result'] = sprintf($ibforums->lang['ip_resolve_result'], $final_ip_string, $resolved);
				
				$this->output .= $this->html->mod_simple_page( $ibforums->lang['cp_results'], $ibforums->lang['ip_resolve_result'] );
				
				return TRUE;
			
			}
		}
		else if ($ibforums->input['iptool'] == 'members')
		{
			if ($exact_match == 0)
			{
				$sql = "ip_address LIKE '$final_ip_string'";
			}
			else
			{
				$sql = "ip_address='$final_ip_string'";
			}
			
			$DB->query("SELECT count(id) as max FROM ibf_members WHERE $sql");
			
			$total_possible = $DB->fetch_row();
			
			if ($total_possible['max'] < 1)
			{
				$this->mod_error('cp_no_matches');
				return;
			}
			
			$pages = $std->build_pagelinks( array( 'TOTAL_POSS'  => $total_possible['max'],
												   'PER_PAGE'    => 50,
												   'CUR_ST_VAL'  => $this->start_val,
												   'L_SINGLE'    => $ibforums->lang['single_page_forum'],
												   'L_MULTI'     => $ibforums->lang['multi_page_forum'],
												   'BASE_URL'    => $this->base_url."&act=modcp&CODE=doip&iptool=members&ip1={$ibforums->input['ip1']}&ip2={$ibforums->input['ip2']}&ip3={$ibforums->input['ip3']}&ip4={$ibforums->input['ip4']}",
												 )
										  );
										  
			$this->output .= $this->html->ip_member_start($pages);
			
			$DB->query("SELECT name, id, ip_address, posts, joined FROM ibf_members WHERE $sql ORDER BY joined DESC LIMIT {$this->start_val},50");
			
			while( $row = $DB->fetch_row() )
			{
				$row['joined'] = $std->get_date( $row['joined'], 'JOINED' );
				$this->output .= $this->html->ip_member_row($row);
			}
			
			$this->output .= $this->html->ip_member_end($pages);
		}
		else
		{
			// Find posts then!
			
			if ($exact_match == 0)
			{
				$sql = "ip_address LIKE '$final_ip_string'";
			}
			else
			{
				$sql = "ip_address='$final_ip_string'";
			}
			
			// Get forums we're allowed to view
			
			$aforum = array();
			
			$DB->query("SELECT id, read_perms FROM ibf_forums");
			
			while ( $f = $DB->fetch_row() )
			{
				if ($f['read_perms'] == '*')
				{
					$aforum[] = $f['id'];
				}
				else if ( preg_match( "/(^|,)".$ibforums->member['mgroup']."(,|$)/", $f['read_perms']) )
				{
					$aforum[] = $f['id'];
				}
			}
			
			if ( count($aforum) < 1)
			{
				$this->mod_error('cp_no_matches');
				return;
			}
			
			$forums = implode( ",", $aforum);
			
			$DB->query("SELECT pid FROM ibf_posts WHERE queued <> 1 AND forum_id IN($forums) AND $sql");
			
			$max_hits = $DB->get_num_rows();
		
			$posts  = "";
			
			while ($row = $DB->fetch_row() )
			{
				$posts .= $row['pid'].",";
			}
		
			$DB->free_result();
			
			$posts  = preg_replace( "/,$/", "", $posts );
			
			//------------------------------------------------
			// Do we have any results?
			//------------------------------------------------
			
			if ($posts == "")
			{
				$this->mod_error('cp_no_matches');
				return;
			}
			
			//------------------------------------------------
			// If we are still here, store the data into the database...
			//------------------------------------------------
			
			$unique_id = md5(uniqid(microtime(),1));
			
			$str = $DB->compile_db_insert_string( array (
															'id'         => $unique_id,
															'search_date'=> time(),
															'post_id'    => $posts,
															'post_max'   => $max_hits,
															'sort_key'   => 'p.post_date',
															'sort_order' => 'desc',
															'member_id'  => $ibforums->member['id'],
															'ip_address' => $ibforums->input['IP_ADDRESS'],
												   )        );
			
			$DB->query("INSERT INTO ibf_search_results ({$str['FIELD_NAMES']}) VALUES ({$str['FIELD_VALUES']})");
			
			$this->output .= $this->html->mod_simple_page( $ibforums->lang['cp_results'], $this->html->ip_post_results($unique_id, $max_hits) );
				
			return TRUE;
			
		}
		
		
		
	}
	
	//-------------------------------------------------
	// Complete move dUdE
	//-------------------------------------------------
	
	function complete_checked_move()
	{
		global $std, $ibforums, $DB, $print;

		$pass = 0;
		
		if ($ibforums->member['g_is_supmod'] == 1)
		{
			$pass = 1;
		}
		else if ($this->moderator['move_topic'] == 1)
		{
			$pass = 1;
		}
		else
		{
			$pass = 0;
		}
		
		if ($pass == 0)
		{
			$this->mod_error('cp_no_perms');
			return;
		}
		
		$dest_id   = intval($ibforums->input['df']);
		$source_id = $this->forum['id'];
		
		//----------------------------------
		// Check for input..
		//----------------------------------
		
		if ($source_id == "")
		{
			$this->mod_error('cp_error_move');
			return;
		}
		
		//----------------------------------
		
		if ($dest_id == "" or $dest_id == -1)
		{
			$this->mod_error('cp_error_move');
			return;
		}
		
		//----------------------------------
		
		if ($source_id == $dest_id)
		{
			$this->mod_error('cp_error_move');
			return;
		}
		
		//----------------------------------
		
		$DB->query("SELECT id, subwrap, sub_can_post, name FROM ibf_forums WHERE id IN(".$source_id.",".$dest_id.")");
		
		if ($DB->get_num_rows() != 2)
		{
			$this->mod_error('cp_error_move');
			return;
		}
		
		$source_name = "";
		$dest_name   = "";
		
		//-----------------------------------
		// Check for an attempt to move into a subwrap forum
		//-----------------------------------
		
		while ( $f = $DB->fetch_row() )
		{
			if ($f['id'] == $source_id)
			{
				$source_name = $f['name'];
			}
			else
			{
				$dest_name = $f['name'];
			}
			
			if ($f['subwrap'] == 1 and $f['sub_can_post'] != 1)
			{
				$this->mod_error('cp_error_move');
				return;
			}
		}
		
		//---------------------------------
		// God, I'm lazy....
		//----------------------------------
		
		$source = $source_id;
		$moveto = $dest_id;
		$tid    = implode(",",$this->tids);
		
		//----------------------------------
		// Update the topics
		//----------------------------------
		
		$DB->query("UPDATE ibf_topics SET forum_id='$moveto' WHERE forum_id='$source' AND tid IN($tid)");
		
		//----------------------------------
		// Update the posts
		//----------------------------------
		
		$DB->query("UPDATE ibf_posts SET forum_id='$moveto' WHERE forum_id='$source' AND topic_id IN($tid)");
		
		//----------------------------------
		// Update the polls
		//----------------------------------
		
		$DB->query("UPDATE ibf_polls SET forum_id='$moveto' WHERE forum_id='$source' AND tid IN($tid)");
		
		$this->moderate_log("Moved topics from $source_name to $dest_name");
		
		// Resync the forums..
		
		$this->recount($source);
		
		$this->recount($moveto);
	
		$print->redirect_screen( $ibforums->lang['cp_redirect_topics'], "act=modcp&CODE=showtopics&f=".$source_id );
		
	}
	
	
	//-------------------------------------------------
	// Start move form
	//-------------------------------------------------
	
	function start_checked_move()
	{
		global $std, $ibforums, $DB, $print;

		$pass = 0;
		
		if ($ibforums->member['g_is_supmod'] == 1)
		{
			$pass = 1;
		}
		else if ($this->moderator['move_topic'] == 1)
		{
			$pass = 1;
		}
		else
		{
			$pass = 0;
		}
		
		if ($pass == 0)
		{
			$this->mod_error('cp_no_perms');
			return;
		}
		
		$jump_html = $std->build_forum_jump('no_html');
		
		$this->output .= $this->html->move_checked_form_start($this->forum['name'], $this->forum['id']);
		
		$DB->query("SELECT title, tid FROM ibf_topics WHERE forum_id=".$this->forum['id']." AND tid IN(".implode(",", $this->tids).")");
		
		while( $row = $DB->fetch_row() )
		{
			$this->output .=  $this->html->move_checked_form_entry($row['tid'],$row['title']);
		}
		
		$this->output .= $this->html->move_checked_form_end($jump_html);
		
	}
	
	
	//-------------------------------------------------
	// Delete topics, groovy.
	//-------------------------------------------------
	
	function delete_topics()
	{
		global $std, $ibforums, $DB, $print;

		$pass = 0;
		
		if ($ibforums->member['g_is_supmod'] == 1)
		{
			$pass = 1;
		}
		else if ($this->moderator['delete_topic'] == 1)
		{
			$pass = 1;
		}
		else
		{
			$pass = 0;
		}
		
		if ($pass == 0)
		{
			$this->mod_error('cp_no_perms');
			return;
		}
		
		$DB->query("DELETE FROM ibf_topics WHERE forum_id=".$this->forum['id']." AND tid IN(".implode(",",$this->tids).")");
		
		$this->recount();
		
		$print->redirect_screen( $ibforums->lang['cp_redirect_topics'], "act=modcp&CODE=showtopics&f=".$this->forum['id'] );
	
	}
	
	//-------------------------------------------------
	// Alter the topics, yay!
	//-------------------------------------------------
	
	function alter_topics($mod_action="", $sql="")
	{
		global $std, $ibforums, $DB, $print;

		if ($mod_action == "" or $sql == "")
		{
			$this->mod_error('cp_no_perms');
			return;
		}
	
		$pass = 0;
		
		if ($ibforums->member['g_is_supmod'] == 1)
		{
			$pass = 1;
		}
		else if ($this->moderator[$mod_action] == 1)
		{
			$pass = 1;
		}
		else
		{
			$pass = 0;
		}
		
		if ($pass == 0)
		{
			$this->mod_error('cp_no_perms');
			return;
		}
		
		$DB->query("UPDATE ibf_topics SET $sql WHERE forum_id=".$this->forum['id']." AND tid IN(".implode(",",$this->tids).")");
		
		$print->redirect_screen( $ibforums->lang['cp_redirect_topics'], "act=modcp&CODE=showtopics&f=".$this->forum['id'] );
	
	}
	
	//-------------------------------------------------
	// Display the topics, yay!
	//-------------------------------------------------
	
	function show_topics()
	{
		global $std, $ibforums, $DB, $print;
		
		$ibforums->lang  = $std->load_words($ibforums->lang, 'lang_forum', $ibforums->lang_id);
		
		$this->load_forum();
		
		//-------------------------------------------------
		// Check we have permission to read this forum
		//-------------------------------------------------
		
		$pass = 0;
		
		if ($this->forum['read_perms'] == '*')
		{
			$pass = 1;
		}
		else if (preg_match( "/(^|,)".$ibforums->member['mgroup']."(,|$)/", $this->forum['read_perms'] ) )
		{
			$pass = 1;
		}
		
		if ($pass == 0)
		{
			$this->mod_error('cp_err_no_p');
			return;
		}
		
		//+----------------------------------------------------------------
		// Query the database to see how many topics there are in the forum
		//+----------------------------------------------------------------
		
		$DB->query("SELECT COUNT(tid) as max FROM ibf_topics WHERE forum_id='".$this->forum['id']."' and approved='1'");
		$total_possible = $DB->fetch_row();
		
		//+----------------------------------------------------------------
		// Generate the forum page span links
		//+----------------------------------------------------------------
		
		$pages = $std->build_pagelinks( array( 'TOTAL_POSS'  => $total_possible['max'],
											   'PER_PAGE'    => $ibforums->vars['display_max_topics'],
											   'CUR_ST_VAL'  => $ibforums->input['st'],
											   'L_SINGLE'    => $ibforums->lang['single_page_forum'],
											   'L_MULTI'     => $ibforums->lang['multi_page_forum'],
											   'BASE_URL'    => $this->base_url."&act=modcp&CODE=showtopics&f=".$this->forum['id'],
											 )
									  );
		//+----------------------------------------------------------------
		// Start printing the page
		//+----------------------------------------------------------------
		
		$this->output .= $this->html->start_topics($pages, $this->forum);
		
		//+----------------------------------------------------------------
		// Do we have any topics to show?
		//+----------------------------------------------------------------
		
		if ($total_possible['max'] < 1)
		{
			$this->output .= $this->html->show_no_topics();
		}
		
		$first = intval($ibforums->input['st']);
		
		if ($first < 1) $first = 0;
		
		$query = "SELECT * from ibf_topics WHERE forum_id='".$this->forum['id']."' and approved=1 ORDER BY pinned DESC, last_post DESC LIMIT $first,".$ibforums->vars['display_max_topics'];
		
		$DB->query($query);
		
		//+----------------------------------------------------------------
		// Grab the rest of the topics and print them
		//+----------------------------------------------------------------
		
		while ( $topic = $DB->fetch_row() )
		{
			$topic['last_text']   = $ibforums->lang['last_post_by'];
		
			$topic['last_poster'] = ($topic['last_poster_id'] != 0)
									? "<b><a href='{$this->base_url}&act=Profile&CODE=03&MID={$topic['last_poster_id']}'>{$topic['last_poster_name']}</a></b>"
									: "-".$topic['last_poster_name']."-";
									
			$topic['starter']     = ($topic['starter_id']     != 0)
									? "<a href='{$this->base_url}&act=Profile&CODE=03&MID={$topic['starter_id']}'>{$topic['starter_name']}</a>"
									: "-".$topic['starter_name']."-";
		 
			if ($topic['poll_state'])
			{
				$topic['prefix']     = $ibforums->vars['pre_polls'].' ';
			}
			
			$topic['folder_img']     = $std->folder_icon($topic);
			
			$topic['topic_icon']     = $topic['icon_id']  ? '<img src="'.$ibforums->vars['img_url'] . '/icon' . $topic['icon_id'] . '.gif" border="0" alt="">'
														  : '&nbsp;';
			
			$topic['start_date'] = $std->get_date( $topic['start_date'], 'LONG' );
		
			if ($topic['posts'] < 0) $topic['posts'] = 0;
			
			$topic['last_post']  = $std->get_date( $topic['last_post'], 'SHORT' );
			
			//+----------------------------------------------------------------
			
			// As "linked" move topics change the TID, we need to get a "real" value
			// for it, or you won't be able to moderate link topics.
			
			$topic['real_tid'] = $topic['tid'];
				
			if ($topic['state'] == 'link')
			{
				$t_array = explode("&", $topic['moved_to']);
				$topic['tid']       = $t_array[0];
				$topic['forum_id']  = $t_array[1];
				$topic['title']     = $topic['title'];
				$topic['views']     = '--';
				$topic['posts']     = '--';
				$topic['prefix']    = $ibforums->vars['pre_moved']." ";
				$topic['go_new_post'] = "";
			}
			
			if ($topic['pinned'] == 1)
			{
				$topic['prefix']     = $ibforums->vars['pre_pinned'];
				$topic['topic_icon'] = "<{B_PIN}>";
			}
			
			$this->output .= $this->html->topic_row( $topic );
		}
		
		$this->output .= $this->html->topics_end($this->forum);
		
		
	}
	
	
	//-------------------------------------------------
	// Display the forums we're allowed to manage, yay!
	//--------------------------------------------------
	
	function show_forums()
	{
		global $std, $ibforums, $DB, $print;
		
		$ibforums->lang  = $std->load_words($ibforums->lang, 'lang_boards', $ibforums->lang_id);
	
		//-------------------------------------------------
		// Get the id's of the forums we manage.
		//--------------------------------------------------
		
		$forum_ids = array();
		
		// If we're not a super mod, get the forums we mod.
		
		if ( ! $ibforums->member['g_is_supmod'] )
		{
			$DB->query("SELECT forum_id FROM ibf_moderators WHERE member_id={$ibforums->member['id']} OR group_id={$ibforums->member['mgroup']}");
			
			while ( $r = $DB->fetch_row() )
			{
				$forum_ids[] = $r['forum_id'];
			}
		}
		else
		{
			// We're a super mod, get all the forums we've got read access too
			
			$DB->query("SELECT id, read_perms FROM ibf_forums");
			
			while ( $r = $DB->fetch_row() )
			{
				if ( ($r['read_perms'] == '*') or ( preg_match( "/(^|,)".$ibforums->member['mgroup']."(,|$)/", $r['read_perms']) ) )
				{
					$forum_ids[] = $r['id'];
				}
			}
		}
		
		//--------------------------------------------------
		// Ensure that we have some forums...
		//--------------------------------------------------
		
		if ( count($forum_ids) < 1 )
		{
			$std->Error( array( 'LEVEL' => 1, 'MSG' => 'me_no_forum') );
		}
		
		$fids = implode( ",", $forum_ids );
		
		//--------------------------------------------------
		// Get number of queued posties :D
		//--------------------------------------------------
		
		$queued_posts = array();
		
		$DB->query("SELECT COUNT(pid) as qued, forum_id FROM ibf_posts
					WHERE queued=1 AND new_topic <> 1 AND forum_id IN ($fids)
					GROUP BY forum_id ORDER BY forum_id");
					
		while ( $q = $DB->fetch_row() )
		{
			$queued_posts[ $q['forum_id'] ] = $q['qued'];
		}
		
		//--------------------------------------------------
		// Get number of queued Topics :D
		//--------------------------------------------------
		
		$queued_topics = array();
		
		$DB->query("SELECT COUNT(tid) as qued, forum_id FROM ibf_topics
					WHERE approved <> 1 AND forum_id IN ($fids)
					GROUP BY forum_id ORDER BY forum_id");
					
		while ( $q = $DB->fetch_row() )
		{
			$queued_topics[ $q['forum_id'] ] = $q['qued'];
		}
		
		//--------------------------------------------------
		// Get the forum data
		//--------------------------------------------------
		
		$DB->query("SELECT f.*, c.id as cat_id, c.name as cat_name
		            FROM ibf_forums f
 		             LEFT JOIN ibf_categories c ON (c.id=f.category)
 		            WHERE f.id IN ($fids)
 		            ORDER BY c.position, f.position");
 		            
 		$last_cat_id = -1;
 		
 		$this->output .= $this->html->forum_page_start();
 		
		while( $forum = $DB->fetch_row() )
		{
		
			if ($last_cat_id != $forum['cat_id'])
			{
				$last_cat_id = $forum['cat_id'];
				
				$this->output .= $this->html->cat_row($forum['cat_name']);
			}
			
			$forum['q_posts'] = 0;
			
			if ( $queued_posts[ $forum['id'] ] )
			{
				$forum['q_posts'] = "<span class='highlight'>".$queued_posts[ $forum['id'] ]."</span>";
			}
			
			$forum['q_topics'] = 0;
			
			if ( $queued_topics[ $forum['id'] ] )
			{
				$forum['q_topics'] = "<span class='highlight'>".$queued_topics[ $forum['id'] ]."</span>";
			}
			
			$forum['n_posts'] = $forum['posts'] + $forum['topics'];
			
			$forum['last_post'] = $std->get_date($forum['last_post'], 'LONG');
					
			$forum['last_topic'] = $ibforums->lang['f_none'];
			
			$forum['last_title'] = str_replace( "&#33;" , "!", $forum['last_title'] );
			$forum['last_title'] = str_replace( "&quot;", "\"", $forum['last_title'] );
				
			if (strlen($forum['last_title']) > 30)
			{
				$forum['last_title'] = substr($forum['last_title'],0,27) . "...";
				$forum['last_title'] = preg_replace( '/&(#(\d+;?)?)?\.\.\.$/', '...', $forum['last_title'] );
			}
			
			if ($forum['password'] != "")
			{
				$forum['last_topic'] = $ibforums->lang['f_none'];
			}
			else
			{
				$forum['last_topic'] = "<a href='{$ibforums->base_url}&act=ST&f={$forum['id']}&t={$forum['last_id']}&view=getlastpost'>{$forum['last_title']}</a>";
			}
		 
						
			if ( isset($forum['last_poster_name']))
			{
				$forum['last_poster'] = $forum['last_poster_id'] ? "<a href='{$ibforums->base_url}&act=Profile&CODE=03&MID={$forum['last_poster_id']}'>{$forum['last_poster_name']}</a>"
																 : $forum['last_poster_name'];
			}
			else
			{
				$forum['last_poster'] = $ibforums->lang['f_none'];
			}
			
			$forum['folder_icon'] = $std->forum_new_posts($forum);
		
			$this->output .= $this->html->forum_row($forum);
		}
	
		$this->output .= $this->html->forum_page_end();
		
		$this->nav[] = "<a href='{$this->base_url}&act=modcp'>{$ibforums->lang['cp_modcp_home']}</a>";
		$this->nav[] = $ibforums->lang['menu_forums'];
	
	}
	
	
	
	//-------------------------------------------------
	// MODERATE NEW POSTS AND STUFF
	//--------------------------------------------------
	
	function approve_all()
	{
		global $std, $ibforums, $DB, $print;
		
		$this->load_forum();
		
		// Sort out the approved bit
		
		$DB->query("UPDATE ibf_posts SET queued=0 WHERE topic_id='".$ibforums->input['tid']."'");
		
		$DB->query("SELECT COUNT(pid) as posts FROM ibf_posts WHERE new_topic <> 1 and topic_id='".$ibforums->input['tid']."'");
		$count = $DB->fetch_row();
		
		$DB->query("UPDATE ibf_topics SET posts=".$count['posts']." WHERE tid='".$ibforums->input['tid']."'");
		
		// Update the posters ..er.. post count.
			
		$DB->query("SELECT starter_id FROM ibf_topics WHERE tid='".$ibforums->input['tid']."'");
		
		$mems = array();
		
		while ( $r = $DB->fetch_row() )
		{
			if ($r['starter_id'] > 0)
			{
				$mems[] = $r['starter_id'];
			}
		}
		
		if ( count($mems) > 0 )
		{
			$mstring = implode( ",", $mems );
			
			$DB->query("UPDATE ibf_members SET posts=posts+1 WHERE id IN ($mstring)");
		}
		
		// Update the last topic poster, time and number of posts.
			
		$DB->query("SELECT author_id, author_name, post_date FROM ibf_posts WHERE topic_id='".$ibforums->input['tid']."' AND queued <> 1 ORDER BY pid DESC LIMIT 0,1");
		
		if ($last = $DB->fetch_row())
		{
			$db_string = $DB->compile_db_update_string( array (
																 'last_post'        => $last['post_date'],
																 'last_poster_id'   => $last['author_id'],
																 'last_poster_name' => $last['author_name'],
													  )       );
													  
			$DB->query("UPDATE ibf_topics SET $db_string WHERE tid='".$ibforums->input['tid']."'");
		}
		
		// recount...
		
		$this->recount();
		
		
		// Boink
		
		$print->redirect_screen( $ibforums->lang['cp_redirect_mod_topics'], "act=modcp&CODE=modposts&f=".$this->forum['id'] );
		
	}
	
	//--------------------------------------------------
	
	function mod_domodposts()
	{
		global $std, $ibforums, $DB, $print;
		
		$this->load_forum();
		
		//--------------------------------------------------
		// Which TID's are we playing with?
		//--------------------------------------------------
		
		$delete_ids  = array();
		$approve_ids = array();
		
		foreach ($ibforums->input as $key => $value)
 		{
 			if ( preg_match( "/^PID_(\d+)$/", $key, $match ) )
 			{
 				if ($ibforums->input[$match[0]] == 'approve')
 				{
 					$approve_ids[] = $match[1];
 				}
 				else if ($ibforums->input[$match[0]] == 'remove')
 				{
 					$delete_ids[] = $match[1];
 				}
 			}
 		}
 		
 		//--------------------------------------------------
		// Did we actually select anyfink?
		//--------------------------------------------------
		
		$total = count($delete_ids) + count($approve_ids);
		
		if ( $total < 1 )
		{
			$this->mod_error('cp_error_no_topics');
			return;
		}
		
		//--------------------------------------------------
		// What did we do?
		//--------------------------------------------------
		
		if ( count($approve_ids) > 0 )
		{
			// Sort out the approved bit
			
			$pids = implode( ",", $approve_ids );
			
			$pid_count = count($approve_ids);
			
			$DB->query("UPDATE ibf_topics SET posts=posts+$pid_count WHERE tid='".$ibforums->input['tid']."'");
			
			$DB->query("UPDATE ibf_posts SET queued=0 WHERE pid IN ($pids)");
			
			// Update the posters ..er.. post count.
			
			$DB->query("SELECT author_id FROM ibf_posts WHERE queued <> 1 and pid IN ($pids)");
			
			$mems = array();
			
			while ( $r = $DB->fetch_row() )
			{
				if ($r['author_id'] > 0)
				{
					$mems[] = $r['author_id'];
				}
			}
			
			if ( count($mems) > 0 )
			{
				$mstring = implode( ",", $mems );
				
				$DB->query("UPDATE ibf_members SET posts=posts+1 WHERE id IN ($mstring)");
			}
			
			// Update the last topic poster, time and number of posts.
			
			$DB->query("SELECT author_id, author_name, post_date FROM ibf_posts WHERE topic_id='".$ibforums->input['tid']."' AND queued <> 1 ORDER BY pid DESC LIMIT 0,1");
			
			if ($last = $DB->fetch_row())
			{
				$db_string = $DB->compile_db_update_string( array (
																	 'last_post'        => $last['post_date'],
																	 'last_poster_id'   => $last['author_id'],
																     'last_poster_name' => $last['author_name'],
														  )       );
														  
				$DB->query("UPDATE ibf_topics SET $db_string WHERE tid='".$ibforums->input['tid']."'");
			}
			
		}
		
		if ( count($delete_ids) > 0 )
		{
			// Sort out the approved bit
			
			$pids = implode( ",", $delete_ids );
			
			// Delete 'dem postings
			
			$DB->query("DELETE FROM ibf_posts WHERE pid IN ($pids)");
			
		}
		
		// Recount..
		
		$this->recount();
		
		// Boink
		
		$print->redirect_screen( $ibforums->lang['cp_redirect_mod_topics'], "act=modcp&CODE=modposts&f=".$this->forum['id'] );

	}
	
	//**-------------------------------------------------------
	
	function mod_topicview()
	{
		global $std, $ibforums, $DB, $print;
		
		$this->load_forum();
		
		$DB->query("SELECT tid, title FROM ibf_topics WHERE tid='".$ibforums->input['tid']."'");
		
		if ( ! $DB->get_num_rows() )
		{
			$this->mod_error('cp_error_no_topics');
			return;
		}
		
		$topic = $DB->fetch_row();
		
		$this->output .= $this->html->modtopicview_start($ibforums->input['tid'], $this->forum['name'], $this->forum['id'], $topic['title']);
		
		
		//+----------------------------------------------------------------
		// Get the topics to work on
		//+----------------------------------------------------------------
		
		$DB->query( "SELECT p.*, ".
				    "m.id,m.name,m.mgroup,m.email,m.joined,m.avatar,m.avatar_size,m.posts as member_posts,m.aim_name,m.icq_number,m.signature, m.website,m.yahoo,m.title,m.hide_email,m.msnname, ".
				    "g.g_id, g.g_title, g.g_icon, t.* ".
				    "FROM ibf_posts p, ibf_members m, ibf_groups g, ibf_topics t ".
				    "WHERE t.tid='".$ibforums->input['tid']."' AND t.approved=1 AND p.topic_id=t.tid AND p.queued=1 AND p.author_id=m.id AND g.g_id=m.mgroup ".
				    "ORDER BY p.pid ASC");
		
		while( $r = $DB->fetch_row() )
		{
			$member = $this->parse_member($r);
			
			$r['post_date'] = $std->get_date( $r['post_date'], 'LONG' );
			
			$this->output .= $this->html->mod_postentry_checkbox($r['pid']);
			$this->output .= $this->html->mod_postentry( array( 'msg' => $r, 'member' => $member ) );
			
		}
		$this->output .= $this->html->mod_topic_spacer();
		$this->output .= $this->html->modtopics_end();
		
	}
	
	//+----------------------------------------------------------------
	//+----------------------------------------------------------------
	
	function mod_posts()
	{
		global $std, $ibforums, $DB, $print;
		
		$this->load_forum();
		
		$DB->query("SELECT t.*, COUNT(p.pid) as reply_count FROM ibf_topics t, ibf_posts p WHERE p.queued=1 AND p.new_topic <> 1 AND t.tid=p.topic_id AND t.forum_id='".$this->forum['id']."' GROUP BY t.tid ORDER BY p.post_date ASC");
		
		if ( ! $DB->get_num_rows() )
		{
			$this->mod_error('cp_error_no_topics');
			return;
		}
		
		$this->output .= $this->html->modpost_topicstart($this->forum['name'], $this->forum['id']);
		
		//+----------------------------------------------------------------
		// Get the topics to work on
		//+----------------------------------------------------------------
		
		while( $r = $DB->fetch_row() )
		{
			$this->output .= $this->html->modpost_topicentry($r['title'], $r['tid'], $r['reply_count'], $this->forum['id']);
		}
		
		$this->output .= $this->html->modpost_topicend();
		
	}
	
	//--------------------------------------------------
	// MODERATE NEW TOPICS AND STUFF
	//--------------------------------------------------
	
	function domod_topics()
	{
		global $std, $ibforums, $DB, $print;
		
		//--------------------------------------------------
		// Which TID's are we playing with?
		//--------------------------------------------------
		
		$this->load_forum();
		
		$delete_ids  = array();
		$approve_ids = array();
		
		foreach ($ibforums->input as $key => $value)
 		{
 			if ( preg_match( "/^TID_(\d+)$/", $key, $match ) )
 			{
 				if ($ibforums->input[$match[0]] == 'approve')
 				{
 					$approve_ids[] = $match[1];
 				}
 				else if ($ibforums->input[$match[0]] == 'remove')
 				{
 					$delete_ids[] = $match[1];
 				}
 			}
 		}
 		
 		//--------------------------------------------------
		// Did we actually select anyfink?
		//--------------------------------------------------
		
		$total = count($delete_ids) + count($approve_ids);
		
		if ( $total < 1 )
		{
			$this->mod_error('cp_error_no_topics');
			return;
		}
		
		//--------------------------------------------------
		// What did we do?
		//--------------------------------------------------
		
		if ( count($approve_ids) > 0 )
		{
			// Sort out the approved bit
			
			$tids = implode( ",", $approve_ids );
			
			// Sort out the approved bit
			
			$DB->query("UPDATE ibf_topics SET approved=1 WHERE tid IN ($tids)");
			
			$DB->query("UPDATE ibf_posts SET queued=0 WHERE topic_id IN ($tids)");
			
			// Update the posters ..er.. post count.
			
			$DB->query("SELECT starter_id FROM ibf_topics WHERE tid IN ($tids)");
			
			$mems = array();
			
			while ( $r = $DB->fetch_row() )
			{
				if ($r['starter_id'] > 0)
				{
					$mems[] = $r['starter_id'];
				}
			}
			
			if ( count($mems) > 0 )
			{
				$mstring = implode( ",", $mems );
				
				$DB->query("UPDATE ibf_members SET posts=posts+1 WHERE id IN ($mstring)");
			}
			
		}
		
		if ( count($delete_ids) > 0 )
		{
			// Sort out the approved bit
			
			$tids = implode( ",", $delete_ids );
			// Delete 'dem postings
			
			$DB->query("DELETE FROM ibf_topics WHERE tid IN ($tids)");
			
			$DB->query("DELETE FROM ibf_posts WHERE topic_id IN ($tids)");
			
			
		}
		
		// Recount..
			
		$this->recount();
		
		// Boink
		
		$print->redirect_screen( $ibforums->lang['cp_redirect_mod_topics'], "act=modcp&CODE=modtopics&f=".$this->forum['id'] );
		
	}
	
	//**-------------------------------------------------------
	
	function mod_topics()
	{
		global $std, $ibforums, $DB, $print;
		
		$perpage = 10;
		
		$start   = $ibforums->input['st'] ? $ibforums->input['st'] : 0;
		
		$this->load_forum();
		
		//--------------------------------------------------
		// How many topics must a man write down, before he is considered a man?
		//--------------------------------------------------
		
		$DB->query("SELECT COUNT(tid) as tcount FROM ibf_topics WHERE approved=0 and forum_id='".$this->forum['id']."'");
		$count = $DB->fetch_row();
		
		if ($count['tcount'] < 1)
		{
			$this->mod_error('cp_error_no_topics');
			return;
		}
		
		//+----------------------------------------------------------------
		// Generate the forum page span links
		//+----------------------------------------------------------------
		
		$pages = $std->build_pagelinks( array( 'TOTAL_POSS'  => $count['tcount'],
											   'PER_PAGE'    => $perpage,
											   'CUR_ST_VAL'  => $start,
											   'L_SINGLE'    => "",
											   'L_MULTI'     => $ibforums->lang['cp_pages'],
											   'BASE_URL'    => $this->base_url."&act=modcp&CODE=modtopics&f=".$this->forum['id'],
									  )      );
									  
		$this->output .= $this->html->modtopics_start($pages, $this->forum['name'], $this->forum['id']);
		
		//+----------------------------------------------------------------
		// Get the topics to work on
		//+----------------------------------------------------------------
		
		$DB->query( "SELECT p.*, ".
				    "m.id,m.name,m.mgroup,m.email,m.joined,m.avatar,m.avatar_size,m.posts as member_posts,m.aim_name,m.icq_number,m.signature, m.website,m.yahoo,m.title,m.hide_email,m.msnname, ".
				    "g.g_id, g.g_title, g.g_icon, t.* ".
				    "FROM ibf_posts p, ibf_members m, ibf_groups g, ibf_topics t ".
				    "WHERE t.forum_id='".$this->forum['id']."' and t.approved=0 AND p.topic_id=t.tid AND p.new_topic=1 AND p.author_id=m.id AND g.g_id=m.mgroup ".
				    "ORDER BY t.tid ASC LIMIT $start, $perpage");
		
		while( $r = $DB->fetch_row() )
		{
			$member = $this->parse_member($r);
			
			$r['post_date'] = $std->get_date( $r['post_date'], 'LONG' );
			
			$this->output .= $this->html->mod_topic_title($r['title'], $r['tid']);
			$this->output .= $this->html->mod_postentry( array( 'msg' => $r, 'member' => $member ) );
			$this->output .= $this->html->mod_topic_spacer();
		}
		
		$this->output .= $this->html->modtopics_end();
		
	}
	
	//--------------------------------------------------
	// Do Pruney wooney
	//--------------------------------------------------
	
	function drink_prune_juice()
	{
		global $std, $ibforums, $DB, $print;
		
		$this->load_forum();
		
		$pass = 0;
		
		if ($ibforums->member['g_is_supmod'] == 1)
		{
			$pass = 1;
		}
		else if ($this->moderator['mass_prune'] == 1)
		{
			$pass = 1;
		}
		else
		{
			$pass = 0;
		}
		
		if ($pass == 0)
		{
			$this->mod_error('cp_no_perms');
			return;
		}
		
		//-----------------------------------------------
		
		$db_query = "SELECT tid FROM ibf_topics WHERE approved=1 and forum_id='".$this->forum['id']."'";
		
		if ($ibforums->input['dateline'])
		{
			$db_query .= " AND last_post < {$ibforums->input['dateline']}";
		}
		
		if ($ibforums->input['starter'])
		{
			$db_query .= " AND starter_id='".$ibforums->input['starter']."'";
			
		}
		
		if ($ibforums->input['posts'])
		{
			$db_query .= " AND posts < '".$ibforums->input['posts']."'";
		}
		
		if ($ibforums->input['state'] != 'all')
		{
			if ($ibforums->input['state'])
			{
				$db_query .= " AND state='".$ibforums->input['state']."'";
			}
		}
		
		$batch = $DB->query($db_query);
		
		if ( ! $num_rows = $DB->get_num_rows() )
		{
			$this->mod_error('cp_error_no_topics');
			return;
		}
		
		//-----------------------------------------------
		// Right... to stop a potentially huge mySQL query
		// from forming with IN ( ), lets do this in batches
		// of 50...
		//-----------------------------------------------
		
		$tid_array = array();
		/*
		if ($num_rows > 50)
		{
			while ( $tid = $DB->fetch_row($batch) )
			{
				if ( count($tid_array) >= 50 )
				{
					$this->prune_batch( implode(",", $tid_array ) );
					$tid_array = array();
				}
				else
				{
					$tid_array[] = $tid['tid'];
				}
			}
		}
		else
		{
		*/
			while ( $tid = $DB->fetch_row() )
			{
				$tid_array[] = $tid['tid'];
			}
			
			$this->prune_batch( implode(",", $tid_array ) );
		//}
		
		// Recount the forum...
		
		$this->recount();
		
		$this->moderate_log("Pruned Forum");
		
		// Show results..
		
		$this->output .= $this->html->mod_simple_page( $ibforums->lang['cp_results'], $ibforums->lang['cp_result_del'].$num_rows );
		
	}
	
	//--------------------------------------------------------------------------
	
	function prune_batch($tid_string)
	{
		global $std, $ibforums, $DB;
		
		$this->load_forum();
		
		// Check for attachments
		
		$DB->query("SELECT attach_id FROM ibf_posts WHERE topic_id IN ($tid_string)");
		
		while ( $attach = $DB->fetch_row() )
		{
			if ( is_file( $this->upload_dir."/".$attach['attach_id'] ) )
			{
				@unlink( $this->upload_dir."/".$attach['attach_id'] );
			}
		}
		
		// Remove polls
		
		$DB->query("DELETE FROM ibf_polls WHERE tid IN ($tid_string)");
		
		// Remove poll voters
		
		$DB->query("DELETE FROM ibf_voters WHERE tid IN ($tid_string)");
		
		// Remove the topics
		
		$DB->query("DELETE FROM ibf_topics WHERE tid IN ($tid_string)");
		
		// Remove the posts
		
		$DB->query("DELETE FROM ibf_posts WHERE topic_id IN ($tid_string)");
		
	}
	
	
	//--------------------------------------------------
	// Prune Forum start
	//--------------------------------------------------
	
	function prune_juice()
	{
		global $std, $ibforums, $DB, $print;
		
		$this->load_forum();
		
		$pass = 0;
		
		if ($ibforums->member['g_is_supmod'] == 1)
		{
			$pass = 1;
		}
		else if ($this->moderator['mass_prune'] == 1)
		{
			$pass = 1;
		}
		else
		{
			$pass = 0;
		}
		
		if ($pass == 0)
		{
			$this->mod_error('cp_no_perms');
			return;
		}
		
		//-----------------------------------------------
		
		if ($ibforums->input['check'] == 1)
		{
		
			$link = "";
			$link_text = $ibforums->lang['cp_prune_dorem'];
			
			$DB->query("SELECT COUNT(tid) as tcount FROM ibf_topics WHERE approved=1 and forum_id='".$this->forum['id']."'");
			$tcount = $DB->fetch_row();
			
			$db_query = "SELECT COUNT(*) as count FROM ibf_topics WHERE approved=1 and forum_id='".$this->forum['id']."'";
			
			if ($ibforums->input['dateline'])
			{
				$date     = time() - $ibforums->input['dateline']*60*60*24;
				$db_query .= " AND last_post < $date";
				
				$link .= "&dateline=$date";
			}
			
			if ($ibforums->input['member'])
			{
				$DB->query("SELECT id FROM ibf_members WHERE name='".$ibforums->input['member']."'");
				
				if (! $mem = $DB->fetch_row() )
				{
					$this->mod_error('cp_error_no_mem');
					return;
				}
				else
				{
					$db_query .= " AND starter_id='".$mem['id']."'";
					$link     .= "&starter={$mem['id']}";
				}
			}
			
			if ($ibforums->input['posts'])
			{
				$db_query .= " AND posts < '".$ibforums->input['posts']."'";
				$link     .= "&posts={$ibforums->input['posts']}";
			}
			
			if ($ibforums->input['topic_type'] != 'all')
			{
				$db_query .= " AND state='".$ibforums->input['topic_type']."'";
				$link     .= "&state={$ibforums->input['topic_type']}";
			}
			
			$DB->query($db_query);
			$count = $DB->fetch_row();
			
			if ($ibforums->input['df'] == 'prune')
			{
				$link = "&act=modcp&f={$this->forum['id']}&CODE=doprune&".$link;
			}
			else
			{
				if ($ibforums->input['df'] == $this->forum['id'])
				{
					$this->mod_error('cp_same_forum');
					return;
				}
				else if ($ibforums->input['df'] == -1)
				{
					$this->mod_error('cp_no_forum');
					return;
				}
				
				$link = "&act=modcp&f={$this->forum['id']}&CODE=domove&df=".$ibforums->input['df'].$link;
				$link_text = $ibforums->lang['cp_prune_domove'];
			}
			
			$confirm_html = $this->html->prune_confirm( $tcount['tcount'], $count['count'], $link, $link_text );
			
		}
		
		
		$select = "<select name='topic_type' class='forminput'>";
		
		foreach( array( 'open', 'closed', 'link', 'all' ) as $type )
		{
			if ($ibforums->input['topic_type'] == $type)
			{
				$selected = ' selected';
			}
			else
			{
				$selected = '';
			}
			
			$select .= "<option value='$type'".$selected.">".$ibforums->lang['cp_pday_'.$type]."</option>";
		}
		
		$select .= "</select>\n";
		
		$forums = "<option value='prune'>{$ibforums->lang['cp_ac_prune']}</option>";
		
		$forums .= $std->build_forum_jump(0);
		
		
		if ($ibforums->input['df'])
		{
			$forums = preg_replace( "/<option value=\"".$ibforums->input['df']."\"/", "<option value=\"".$ibforums->input['df']."\" selected", $forums );
		}
		
		$this->output .= $this->html->prune_splash($this->forum, $forums, $select, $button, $confirm);
		
		if ($confirm_html)
		{
			$this->output = preg_replace( "/<!-- IBF\.CONFIRM -->/", "$confirm_html", $this->output );
		}
		
	}
	
	
	
	//--------------------------------------------------
	// Find a user to edit, dude.
	//--------------------------------------------------
	
	function find_user_one()
	{
		global $std, $ibforums, $DB, $print;
		
		$pass = 0;
		
		if ($ibforums->member['g_is_supmod'] == 1)
		{
			$pass = 1;
		}
		else if ($this->moderator['edit_user'] == 1)
		{
			$pass = 1;
		}
		else
		{
			$pass = 0;
		}
		
		if ($pass == 0)
		{
			$this->mod_error('cp_no_perms');
			return;
		}
		
		$this->output .= $this->html->find_user();
	}
	
	function find_user_two()
	{
		global $std, $ibforums, $DB, $print;
		
		if ($ibforums->input['name'] == "")
		{
			$this->mod_error('cp_no_matches');
			return;
		}
		
		//---------------------------------
		// Query the DB for possible matches
		//---------------------------------
		
		$DB->query("SELECT id, name FROM ibf_members WHERE name LIKE '".$ibforums->input['name']."%' LIMIT 0,100");
		
		if ( $DB->get_num_rows() )
		{
			$select = "<select name='memberid' class='forminput'>";
			
			while ( $member = $DB->fetch_row() )
			{
				$select .= "\n<option value='".$member['id']."'>".$member['name']."</option>";
			}
			
			$select .= "</select>";
			
			$this->output .= $this->html->find_two($select);
		}
		else
		{
			$this->mod_error('cp_no_matches');
			return;
		}
	}
	
	function edit_user()
	{
		global $std, $ibforums, $DB, $print, $root_path;
		
		$pass = 0;
		
		if ($ibforums->member['g_is_supmod'] == 1)
		{
			$pass = 1;
		}
		else if ($this->moderator['edit_user'] == 1)
		{
			$pass = 1;
		}
		else
		{
			$pass = 0;
		}
		
		if ($pass == 0)
		{
			$this->mod_error('cp_no_perms');
			return;
		}
		
		if ($ibforums->input['memberid'] == "")
		{
			$this->mod_error('cp_no_matches');
			return;
		}
		
		//--------------------------------------------------
		
		$DB->query("SELECT m.*, g.* FROM ibf_members m, ibf_groups g WHERE m.id='".$ibforums->input['memberid']."' AND m.mgroup=g.g_id");
		
		if (! $member = $DB->fetch_row() )
		{
			$this->mod_error('cp_no_matches');
			return;
		}
		
		//--------------------------------------------------
		// No editing of admins!
		//--------------------------------------------------
		
		if ($ibforums->member['g_access_cp'] != 1)
		{
			if ($member['g_access_cp'] == 1)
			{
				$this->mod_error('cp_admin_user');
				return;
			}
		}
		
		require $root_path."sources/lib/post_parser.php";
		
		$parser = new post_parser();
		
		$editable['signature'] = $parser->unconvert($member['signature']);
		$editable['location']  = $member['location'];
		$editable['interests'] = $member['interests'];
		$editable['website']   = $member['website'];
		$editable['id']        = $member['id'];
		$editable['name']      = $member['name'];
		
		$this->output .= $this->html->edit_user_form($editable);
	}
	
	//--------------------------------------------------
	
	function complete_user_edit()
	{
		global $std, $ibforums, $DB, $print, $root_path;
		
		$pass = 0;
		
		if ($ibforums->member['g_is_supmod'] == 1)
		{
			$pass = 1;
		}
		else if ($this->moderator['edit_user'] == 1)
		{
			$pass = 1;
		}
		else
		{
			$pass = 0;
		}
		
		if ($pass == 0)
		{
			$this->mod_error('cp_no_perms');
			return;
		}
		
		if ($ibforums->input['memberid'] == "")
		{
			$this->mod_error('cp_no_matches');
			return;
		}
		
		//--------------------------------------------------
		
		$DB->query("SELECT m.*, g.* FROM ibf_members m, ibf_groups g WHERE m.id='".$ibforums->input['memberid']."' AND m.mgroup=g.g_id");
		
		if (! $member = $DB->fetch_row() )
		{
			$this->mod_error('cp_no_matches');
			return;
		}
		
		//--------------------------------------------------
		// No editing of admins!
		//--------------------------------------------------
		
		if ($ibforums->member['g_access_cp'] != 1)
		{
			if ($member['g_access_cp'] == 1)
			{
				$this->mod_error('cp_admin_user');
				return;
			}
		}
		
		require $root_path."sources/lib/post_parser.php";
		
		$parser = new post_parser();
		
		$ibforums->input['signature'] = $parser->convert(  array( 'TEXT'      => $ibforums->input['signature'],
																  'SMILIES'   => 0,
																  'CODE'      => $ibforums->vars['sig_allow_ibc'],
																  'HTML'      => 0,
																  'SIGNATURE' => 1
														)       );
									   
		if ($parser->error != "")
		{
			$std->Error( array( 'LEVEL' => 1, 'MSG' => $parser->error) );
		}
		
		
		$profile = array (
						   'signature'   => $ibforums->input['signature'],
						   'location'    => $ibforums->input['location'],
						   'interests'   => $ibforums->input['interests'],
						   'website'     => $ibforums->input['website']
						 );
		
		
		if ($ibforums->input['avatar'] == 1)
		{
			$profile['avatar']      = "";
			$profile['avatar_size'] = "";
		}
		
		$db_string = $DB->compile_db_update_string($profile);
		
		$DB->query("UPDATE ibf_members SET $db_string WHERE id='".$ibforums->input['memberid']."'");
		
		$this->moderate_log("Edited Profile for: {$member['name']}");
		
		$std->boink_it($ibforums->base_url."&act=modcp&f={$ibforums->input['f']}&CODE=doedituser&memberid={$ibforums->input['memberid']}");
		exit();
	}

	
	//--------------------------------------------------
	// Show default ModCP screen
	//--------------------------------------------------
	
	function splash()
	{
		global $std, $ibforums, $DB, $print;
		
		// Get the counts for pending topics and posts and other assorted stuff etc and ok.
		
		$DB->query("SELECT COUNT(tid) as count FROM ibf_topics WHERE approved <> 1 and forum_id='".$this->forum['id']."'");
		$row = $DB->fetch_row();
		
		$tcount = $row['count'] ? $row['count'] : 0;
		
		//-------------------------------
		
		$DB->query("SELECT COUNT(pid) as pcount FROM ibf_posts WHERE queued=1 and new_topic <> 1 and forum_id='".$this->forum['id']."'");
		$row = $DB->fetch_row();
		
		$pcount = $row['pcount'] ? $row['pcount'] : 0;
		
		//-------------------------------
	
		$this->output .= $this->html->splash($tcount, $pcount, $this->forum['name']);
	}
	
	
	

	
	/*************************************************/
	
	function do_move() {
		global $std, $ibforums, $DB, $print;
		
		$this->load_forum();
		
		$pass = 0;
		
		if ($ibforums->member['g_is_supmod'] == 1)
		{
			$pass = 1;
		}
		else if ($this->moderator['mass_prune'] == 1)
		{
			$pass = 1;
		}
		else
		{
			$pass = 0;
		}
		
		if ($pass == 0)
		{
			$this->mod_error('cp_no_perms');
			return;
		}
		
		//-----------------------------------------------
		
		$db_query = "SELECT tid FROM ibf_topics WHERE approved=1 and forum_id='".$this->forum['id']."'";
		
		if ($ibforums->input['dateline'])
		{
			$db_query .= " AND last_post < {$ibforums->input['dateline']}";
		}
		
		if ($ibforums->input['starter'])
		{
			$db_query .= " AND starter_id='".$ibforums->input['starter']."'";
			
		}
		
		if ($ibforums->input['posts'])
		{
			$db_query .= " AND posts < '".$ibforums->input['posts']."'";
		}
		
		if ($ibforums->input['state'] != 'all')
		{
			if ($ibforums->input['state'])
			{
				$db_query .= " AND state='".$ibforums->input['state']."'";
			}
		}
		
		$DB->query($db_query);
		
		if ( ! $num_rows = $DB->get_num_rows() )
		{
			$this->mod_error('cp_error_no_topics'); 
			return;
		}
		
		$tid_array = array();
		
		while ($row = $DB->fetch_row())
		{
			$tid_array[] = $row['tid'];
		}
		
		//----------------------------------
		
		$tid    = implode( ",", $tid_array );
		$source = $this->forum['id'];
		$moveto = $ibforums->input['df'];
		
		//-----------------------------------
		// Check for an attempt to move into a subwrap forum
		//-----------------------------------
		
		$DB->query("SELECT subwrap, id, sub_can_post FROM ibf_forums WHERE id='$moveto'");
		
		$f = $DB->fetch_row();
		
		if ($f['subwrap'] == 1 and $f['sub_can_post'] != 1)
		{
			$this->mod_error('cp_error_no_subforum');
			return;
		}
		
		
		//----------------------------------
		// Update the topic
		//----------------------------------
		
		$DB->query("UPDATE ibf_topics SET forum_id='$moveto' WHERE forum_id='$source' AND tid IN($tid)");
		
		//----------------------------------
		// Update the posts
		//----------------------------------
		
		$DB->query("UPDATE ibf_posts SET forum_id='$moveto' WHERE forum_id='$source' AND topic_id IN($tid)");
		
		//----------------------------------
		// Update the polls
		//----------------------------------
		
		$DB->query("UPDATE ibf_polls SET forum_id='$moveto' WHERE forum_id='$source' AND tid IN($tid)");
		
		$this->moderate_log("Mass moved topics");
		
		// Resync the forums..
		
		$this->recount($source);
		
		$this->recount($moveto);
	
		// Show results..
		
		$this->output .= $this->html->mod_simple_page( $ibforums->lang['cp_results'], $ibforums->lang['cp_result_move'].$num_rows );
		
	}
	
	
	

	
//+---------------------------------------------------------------------------------------------
	
	
	/*************************************************/
	// MODERATE LOG:
	// ---------------
	//
	// Function for adding the mod action to the DB
	//
	/*************************************************/
	
	function moderate_log($title = 'unknown') {
		global $std, $ibforums, $DB, $HTTP_REFERER, $QUERY_STRING;
		
		$db_string = $std->compile_db_string( array (
														'forum_id'    => $ibforums->input['f'],
														'topic_id'    => $ibforums->input['t'],
														'post_id'     => $ibforums->input['p'],
														'member_id'   => $ibforums->member['id'],
														'member_name' => $ibforums->member['name'],
														'ip_address'  => $ibforums->input['IP_ADDRESS'],
														'http_referer'=> $HTTP_REFERER,
														'ctime'       => time(),
														'topic_title' => "<i>Via Moderators CP</i>",
														'action'      => $title,
														'query_string'=> $QUERY_STRING,
													)
										    );
		
		$DB->query("INSERT INTO ibf_moderator_logs (" .$db_string['FIELD_NAMES']. ") VALUES (". $db_string['FIELD_VALUES'] .")");
		
	}
	
	
	/*************************************************/
	// Re Count topics for the forums:
	// ---------------
	//
	// Handles simple moderation functions, saves on
	// writing the same code over and over.
	// ASS_U_ME's that the requesting user has been
	// authenticated by this stage.
	//
	/*************************************************/
	
	function recount($fid="") {
		global $ibforums, $root_path, $DB, $std;
		
		if ($fid != "")
		{
			$this->forum['id'] = $fid;
		}
		
		$DB->query("SELECT COUNT(tid) as tcount from ibf_topics WHERE approved=1");
		$topics = $DB->fetch_row();
		
		$DB->query("SELECT COUNT(pid) as pcount from ibf_posts WHERE queued <> 1");
		$posts  = $DB->fetch_row();
		
		$DB->query("SELECT COUNT(tid) as tcount from ibf_topics WHERE approved=1 and forum_id='".$this->forum['id']."'");
		$f_topics = $DB->fetch_row();
		$f_topics['tcount'] = $f_topics['tcount'] < 1 ? 0 : $f_topics['tcount'];
		
		$DB->query("SELECT COUNT(pid) as pcount from ibf_posts WHERE queued=0 and forum_id='".$this->forum['id']."'");
		$f_posts  = $DB->fetch_row();
		$f_posts['pcount'] = $f_posts['pcount'] < 1 ? 0 : $f_posts['pcount'];
		
		$this->forum['topics'] = $f_topics['tcount'];
		$this->forum['posts']  = $f_posts['pcount'] - $f_topics['tcount'];
		
		$DB->query("SELECT tid, title, last_poster_id, last_poster_name, last_post FROM ibf_topics WHERE approved=1 and forum_id='".$this->forum['id']."' ORDER BY last_post DESC LIMIT 0,1");
		$last_post = $DB->fetch_row();
		
		// Reset this forums stats
		
		$db_string = $DB->compile_db_update_string( array (
															 'last_poster_id'   => $last_post['last_poster_id'],
															 'last_poster_name' => $last_post['last_poster_name'],
															 'last_post'        => $last_post['last_post'],
															 'last_title'       => $last_post['title'],
															 'last_id'          => $last_post['tid'],
															 'topics'           => $this->forum['topics'],
															 'posts'            => $this->forum['posts']
												 )        );
		
		$DB->query("UPDATE ibf_forums SET $db_string WHERE id='".$this->forum['id']."'");
		
		// Update the main board stats.
		
		$DB->query("UPDATE ibf_stats SET TOTAL_TOPICS='".$topics['tcount']."', TOTAL_REPLIES='".$posts['pcount']."'");
		
	}
	
	/*************************************************/
	
	function load_forum($fid="")
	{
		global $std, $ibforums, $DB;
		
		if ($fid == "")
		{
			$fid = intval($ibforums->input['f']);
		}
		
		$DB->query("SELECT * FROM ibf_forums WHERE id=$fid");
		
		if ( ! $this->forum = $DB->fetch_row() )
		{
			$this->mod_error('cp_err_no_f');
			return;
		}
		
		return TRUE;
		
	}
	
	/*************************************************/
	
	function get_tids()
	{
		global $std, $ibforums, $DB;
		
		$ids = array();
 		
 		foreach ($ibforums->input as $key => $value)
 		{
 			if ( preg_match( "/^TID_(\d+)$/", $key, $match ) )
 			{
 				if ($ibforums->input[$match[0]])
 				{
 					$ids[] = $match[1];
 				}
 			}
 		}
 		
 		if ( count($ids) < 1 )
 		{
 			$this->mod_error('cp_err_no_topics');
 			return;
 		}
 		
 		return $ids;
	}
	
		
	/*************************************************/
	
	function mod_error($error)
	{
		global $std, $ibforums, $DB, $print;
		
		$error = $ibforums->lang[$error];
	
		$this->output .= $this->html->mod_simple_page($ibforums->lang['cp_error'],$error);
		
		if ( count($this->nav) < 1 )
		{
			$this->nav[] = "<a href='{$this->base_url}&act=modcp'>{$ibforums->lang['cp_modcp_home']}</a>";
		}
		
		if (! $this->page_title )
		{
			$this->page_title = $ibforums->lang['cp_modcp_ptitle'];
		}
    	
    	$print->add_output("$this->output");
        $print->do_output( array( 'TITLE' => $this->page_title, 'JS' => 1, NAV => $this->nav ) );
        
        exit();
	}
	
	/*************************************************/
	
	function parse_member($member=array()) {
		global $ibforums, $std, $DB;
		
		$member['name'] = "<a href='{$this->base_url}&act=Profile&CODE=03&MID={$member['id']}'>{$member['name']}</a>";
	
		$member['avatar'] = $std->get_avatar( $member['avatar'], $ibforums->member['view_avs'], $member['avatar_size'] );
		
		$member['MEMBER_JOINED'] = $ibforums->lang['m_joined'].' '.$std->get_date( $member['joined'], 'JOINED' );
		
		$member['MEMBER_GROUP'] = $ibforums->lang['m_group'].' '.$member['g_title'];
		
		$member['MEMBER_POSTS'] = $ibforums->lang['m_posts'].' '.$member['member_posts'];
		
		$member['PROFILE_ICON'] = "<a href='{$this->base_url}&act=Profile&CODE=03&MID={$member['id']}'><{P_PROFILE}></a>&nbsp;";
		
		$member['MESSAGE_ICON'] = "<a href='{$this->base_url}&act=Msg&CODE=04&MID={$member['id']}'><{P_MSG}></a>&nbsp;";
		
		if (!$member['hide_email']) {
			$member['EMAIL_ICON'] = "<a href='{$this->base_url}&act=Mail&CODE=00&MID={$member['id']}'><{P_EMAIL}></a>&nbsp;";
		}
		
		if ( $member['website'] and $member['website'] = preg_match( "/^http:\/\/\S+$/", $member['WEBSITE'] ) ) {
			$member['WEBSITE_ICON'] = "<a href='{$member['website']}' target='_blank'><{P_WEBSITE}></a>&nbsp;";
		}
		
		if ($member['icq_number']) {
			$member['ICQ_ICON'] = "<a href=\"javascript:PopUp('{$this->base_url}&act=ICQ&MID={$member['id']}','Pager','450','330','0','1','1','1')\"><{P_ICQ}></a>&nbsp;";
		}
		
		if ($member['aim_name']) {
			$member['AOL_ICON'] = "<a href=\"javascript:PopUp('{$this->base_url}&act=AOL&MID={$member['id']}','Pager','450','330','0','1','1','1')\"><{P_AOL}></a>&nbsp;";
		}
		
		//-----------------------------------------------------
		
		return $member;
	
	}
	
	//-----------------------------------------------------
	// Prints the index
	//-----------------------------------------------------
	

	
	function print_index() {
		global $ibforums, $std, $DB, $print;
		
		$this->output .= $this->html->cp_index();
		
	}

    
}

?>
