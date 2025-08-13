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
|   > ICQ / AIM / EMAIL functions
|   > Module written by Matt Mecham
|   > Date started: 28th February 2002
|
|	> Module Version Number: 1.0.0
+--------------------------------------------------------------------------
*/


$idx = new Contact;

class Contact {

    var $output    = "";
    var $base_url  = "";
    var $html      = "";
    
    var $nav       = array();
    var $page_title= "";
    var $email     = "";
    var $forum     = "";
    var $email     = "";

    /***********************************************************************************/
	//
	// Our constructor, load words, load skin
	//
	/***********************************************************************************/
    
    function Contact() {
    
        global $ibforums, $DB, $std, $print, $skin_universal;
        
        
        // What to do?
        
        switch($ibforums->input['act']) {
        	case 'Mail':
        		$this->mail_member();
        		break;
        	case 'AOL':
        		$this->show_aim();
        		break;
        	case 'ICQ':
        		$this->show_icq();
        		break;
        	case 'MSN':
        		$this->show_msn();
        		break;
        	case 'YAHOO':
        		$this->show_yahoo();
        		break;
        	case 'Invite':
        		$this->invite_member();
        		break;
        	
        	case 'report':
        		if ($ibforums->input['send'] != 1)
        		{
        			$this->report_form();
        		}
        		else
        		{
        			$this->send_report();
        		}
        		break;
        	
        	default:
        		$std->Error( array( 'LEVEL' => 1, 'MSG' => 'invalid_use' ) );
        		break;
        }
        
        $print->add_output("$this->output");
        $print->do_output( array( 'TITLE' => $this->page_title, 'JS' => 0, NAV => $this->nav ) );
        
	}
	
	
	
	//****************************************************************/
	// REPORT POST FORM:
	//
	//****************************************************************/
        
        
	function report_form() {
		global $ibforums, $DB, $std, $print;
		
		$ibforums->lang = $std->load_words($ibforums->lang, 'lang_emails', $ibforums->lang_id );

		$this->html     = $std->load_template('skin_emails');
		
		$pid = intval($ibforums->input['p']);
		$tid = intval($ibforums->input['t']);
		$fid = intval($ibforums->input['f']);
		$st  = intval($ibforums->input['st']);
		
		if ( (!$pid) and (!$tid) and (!$fid) )
		{
			$std->Error( array( 'LEVEL' => 1, 'MSG' => 'missing_files') );
		}
		
		// Do we have permission to do stuff in this forum? Lets hope so eh?!
		
		$this->check_access($fid, $tid);
		
		$this->output .= $this->html->report_form($fid, $tid, $pid, $st, $this->forum['topic_title']);
		
		$this->nav[] = "<a href='".$ibforums->base_url."&act=SC&c={$this->forum['cat_id']}'>{$this->forum['cat_name']}</a>";
        $this->nav[] = "<a href='".$ibforums->base_url."&act=SF&f={$this->forum['id']}'>{$this->forum['name']}</a>";
        $this->nav[] = $ibforums->lang['report_title'];
        
        $this->page_title = $ibforums->lang['report_title'];
		
	}
	
	
	function send_report()
	{
		global $ibforums, $DB, $std, $print, $HTTP_POST_VARS;
		
		$ibforums->lang = $std->load_words($ibforums->lang, 'lang_emails', $ibforums->lang_id );

		$this->html     = $std->load_template('skin_emails');
		
		$pid = intval($ibforums->input['p']);
		$tid = intval($ibforums->input['t']);
		$fid = intval($ibforums->input['f']);
		$st  = intval($ibforums->input['st']);
		
		if ( (!$pid) and (!$tid) and (!$fid) )
		{
			$std->Error( array( 'LEVEL' => 1, 'MSG' => 'missing_files') );
		}
		
		//--------------------------------------------
		// Make sure we came in via a form.
		//--------------------------------------------
		
		if ($HTTP_POST_VARS['message'] == "")
		{
			$std->Error( array( 'LEVEL' => 1, 'MSG' => 'complete_form') );
		}
		
		//--------------------------------------------
		// Get the topic title
		//--------------------------------------------
		
		$DB->query("SELECT title FROM ibf_topics WHERE tid='$tid'");
		
		$topic = $DB->fetch_row();
		
		if ( ! $topic['title'] )
		{
			$std->Error( array( 'LEVEL' => 1, 'MSG' => 'missing_files') );
		}
		
		//--------------------------------------------
		// Do we have permission to do stuff in this forum? Lets hope so eh?!
		//--------------------------------------------
		
		$this->check_access($fid, $tid);
		
		$mods = array();
		
		// Check for mods in this forum
		
		$DB->query("SELECT m.name, m.email, mod.member_id FROM ibf_moderators mod, ibf_members m WHERE mod.forum_id='$fid' and mod.member_id=m.id");
		
		if ( $DB->get_num_rows() )
		{
			while( $r = $DB->fetch_row() )
			{
				$mods[] = array(
								 'name'  => $r['name'],
								 'email' => $r['email']
							   );
			}
		}
		else
		{
			//--------------------------------------------
			// No mods? Get those with control panel access
			//--------------------------------------------
			
			$DB->query("SELECT m.id, m.name, m.email FROM ibf_members m, ibf_groups g WHERE g.g_access_cp=1 AND m.mgroup=g.g_id");
			
			while( $r = $DB->fetch_row() )
			{
				$mods[] = array(
								 'name'  => $r['name'],
								 'email' => $r['email']
							   );
			}
		}
		
		//--------------------------------------------
    	// Get the emailer module
		//--------------------------------------------
		
		require "./sources/lib/emailer.php";
		
		$this->email = new emailer();
		
		//--------------------------------------------
		// Loop and send the mail
		//--------------------------------------------
		
		$report = trim(stripslashes($HTTP_POST_VARS['message']));
		
		$report = str_replace( "<!--"    , "" , $report );
		$report = str_replace( "-->"     , "" , $report );
		$report = str_replace( "<script" , "" , $report );
		
		foreach( $mods as $idx => $data )
		{
			$this->email->get_template("report_post");
				
			$this->email->build_message( array(
												'MOD_NAME'     => $data['name'],
												'USERNAME'     => $ibforums->member['name'],
												'TOPIC'        => $topic['title'],
												'LINK_TO_POST' => "{$ibforums->vars['board_url']}/index.{$ibforums->vars['php_ext']}"."?act=ST&f=$fid&t=$tid&st=$st&#entry$pid",
												'REPORT'       => $report,
											  )
										);
										
			$this->email->subject = $ibforums->lang['report_subject'].' '.$ibforums->vars['board_name'];
			$this->email->to      = $data['email'];
			
			$this->email->send_mail();
		
		}
			
		$print->redirect_screen( $ibforums->lang['report_redirect'], "act=ST&f=$fid&t=$tid&st=$st&#entry$pid");					   
		
	}
	
	//--------------------------------------------
	
     
    function check_access($fid, $tid)
    {
		global $ibforums, $DB, $std, $HTTP_COOKIE_VARS;
		
		if ( ! $ibforums->member['id'] )
		{
			$std->Error( array( 'LEVEL' => 1, 'MSG' => 'no_permission') );
		}
		
		//--------------------------------
		
		$DB->query("SELECT t.title as topic_title, f.*, c.id as cat_id, c.name as cat_name from ibf_forums f, ibf_categories c, ibf_topics t WHERE f.id=".$fid." and c.id=f.category and t.tid=$tid");
        
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
	
	//****************************************************************/
	// MSN CONSOLE:
	//
	//****************************************************************/
	
	function show_msn() {
		global $ibforums, $DB, $std, $print;
		
		$this->html    = $std->load_template('skin_emails');

		$ibforums->lang    = $std->load_words($ibforums->lang, 'lang_emails', $ibforums->lang_id );
		
		//----------------------------------
	
		if (empty($ibforums->member['id']))
		{
			$std->Error( array( 'LEVEL' => 1, 'MSG' => 'no_guests' ) );
		}
	
		if ( empty($ibforums->input['MID']) )
		{
			$std->Error( array( 'LEVEL' => 1, 'MSG' => 'invalid_use' ) );
		}
		
		//----------------------------------
		
		if (! preg_match( "/^(\d+)$/" , $ibforums->input['MID'] ) )
		{
			$std->Error( array( 'LEVEL' => 1, 'MSG' => 'invalid_use' ) );
		}
		
		//----------------------------------
		
		$DB->query("SELECT name, id, msnname from ibf_members WHERE id='".$ibforums->input['MID']."'");

		$member = $DB->fetch_row();
		
		//----------------------------------
		
		if (! $member['id'] )
		{
			$std->Error( array( 'LEVEL' => 1, 'MSG' => 'no_such_user' ) );
		}
		
		//----------------------------------
		
		if (! $member['msnname'] )
		{
			$std->Error( array( 'LEVEL' => 1, 'MSG' => 'no_msn' ) );
		}
		
		//----------------------------------
		
		$html  = $this->html->pager_header( array( 'TITLE' => 'MSN' ) );
		
		$html .= $this->html->msn_body( $member['msnname'] );
		
		$html .= $this->html->end_table();
		
		$print->pop_up_window( "MSN CONSOLE", $html );
	
	}
	
	//****************************************************************/
	// Yahoo! CONSOLE:
	//
	//****************************************************************/
	
	function show_yahoo() {
		global $ibforums, $DB, $std, $print;
		
		$this->html    = $std->load_template('skin_emails');

		$ibforums->lang    = $std->load_words($ibforums->lang, 'lang_emails', $ibforums->lang_id );
		
		//----------------------------------
	
		if (empty($ibforums->member['id']))
		{
			$std->Error( array( 'LEVEL' => 1, 'MSG' => 'no_guests' ) );
		}
	
		if ( empty($ibforums->input['MID']) )
		{
			$std->Error( array( 'LEVEL' => 1, 'MSG' => 'invalid_use' ) );
		}
		
		//----------------------------------
		
		if (! preg_match( "/^(\d+)$/" , $ibforums->input['MID'] ) )
		{
			$std->Error( array( 'LEVEL' => 1, 'MSG' => 'invalid_use' ) );
		}
		
		//----------------------------------
		
		$DB->query("SELECT name, id, yahoo from ibf_members WHERE id='".$ibforums->input['MID']."'");

		$member = $DB->fetch_row();
		
		//----------------------------------
		
		if (! $member['id'] )
		{
			$std->Error( array( 'LEVEL' => 1, 'MSG' => 'no_such_user' ) );
		}
		
		//----------------------------------
		
		if (! $member['yahoo'] )
		{
			$std->Error( array( 'LEVEL' => 1, 'MSG' => 'no_yahoo' ) );
		}
		
		//----------------------------------
		
		$html  = $this->html->pager_header( array( 'TITLE' => "Yahoo!" ) );
		
		$html .= $this->html->yahoo_body( $member['yahoo'] );
		
		$html .= $this->html->end_table();
		
		$print->pop_up_window( "YAHOO! CONSOLE", $html );
	
	}
     
    //****************************************************************/
	// AOL CONSOLE:
	//
	//****************************************************************/
        
        
	function show_aim() {
		global $ibforums, $DB, $std, $print;
		
		$ibforums->lang    = $std->load_words($ibforums->lang, 'lang_emails', $ibforums->lang_id );

		$this->html    = $std->load_template('skin_emails');
		
		//----------------------------------
	
		if (empty($ibforums->member['id']))
		{
			$std->Error( array( 'LEVEL' => 1, 'MSG' => 'no_guests' ) );
		}
	
		if ( empty($ibforums->input['MID']) )
		{
			$std->Error( array( 'LEVEL' => 1, 'MSG' => 'invalid_use' ) );
		}
		
		//----------------------------------
		
		if (! preg_match( "/^(\d+)$/" , $ibforums->input['MID'] ) )
		{
			$std->Error( array( 'LEVEL' => 1, 'MSG' => 'invalid_use' ) );
		}
		
		//----------------------------------
		
		$DB->query("SELECT name, id, aim_name from ibf_members WHERE id='".$ibforums->input['MID']."'");

		$member = $DB->fetch_row();
		
		//----------------------------------
		
		if (! $member['id'] )
		{
			$std->Error( array( 'LEVEL' => 1, 'MSG' => 'no_such_user' ) );
		}
		
		//----------------------------------
		
		if (! $member['aim_name'] )
		{
			$std->Error( array( 'LEVEL' => 1, 'MSG' => 'no_aol' ) );
		}
		
		$member['aim_name'] = str_replace(" ", "", $member['aim_name']);
		
		//----------------------------------
		
		$print->pop_up_window( "AOL CONSOLE", $this->html->aol_body( array( 'AOLNAME' => $member['aim_name'] ) ) );
	
	}
	
	//****************************************************************/
	// ICQ CONSOLE:
	//
	//****************************************************************/
	
	
	function show_icq() {
		global $ibforums, $DB, $std, $print;
		
		$ibforums->lang    = $std->load_words($ibforums->lang, 'lang_emails', $ibforums->lang_id);

		$this->html    = $std->load_template('skin_emails');
		
		//----------------------------------
	
		if (empty($ibforums->member['id'])) {
			$std->Error( array( 'LEVEL' => 1, 'MSG' => 'no_guests' ) );
		}
	
		if ( empty($ibforums->input['MID']) )
		{
			$std->Error( array( 'LEVEL' => 1, 'MSG' => 'invalid_use' ) );
		}
		
		//----------------------------------
		
		if (! preg_match( "/^(\d+)$/" , $ibforums->input['MID'] ) )
		{
			$std->Error( array( 'LEVEL' => 1, 'MSG' => 'invalid_use' ) );
		}
		
		//----------------------------------
		
		$DB->query("SELECT name, id, icq_number from ibf_members WHERE id='".$ibforums->input['MID']."'");

		$member = $DB->fetch_row();
		
		//----------------------------------
		
		if (! $member['id'] )
		{
			$std->Error( array( 'LEVEL' => 1, 'MSG' => 'no_such_user' ) );
		}
		
		//----------------------------------
		
		if (! $member['icq_number'] )
		{
			$std->Error( array( 'LEVEL' => 1, 'MSG' => 'no_icq' ) );
		}
		
		//----------------------------------
		
		$html  = $this->html->pager_header( array( $ibforums->lang['icq_title'] ) );
		
		$html .= $this->html->icq_body( array( 'UIN' => $member['icq_number'] ) );
		
		$html .= $this->html->end_table();
		
		$print->pop_up_window( "ICQ CONSOLE", $html );
	
	
	}
	
	//****************************************************************/
	// MAIL MEMBER:
	//
	// Handles the routines called by clicking on the "email" button when
	// reading topics
	//****************************************************************/
	
	
	function mail_member() {
		global $ibforums, $DB, $std, $print;
	
		require "./sources/lib/emailer.php";
		$this->email = new emailer();
		
		//----------------------------------
		
		$ibforums->lang    = $std->load_words($ibforums->lang, 'lang_emails', $ibforums->lang_id );

		$this->html    = $std->load_template('skin_emails');
		
		//----------------------------------
	
		if (empty($ibforums->member['id']))
		{
			$std->Error( array( 'LEVEL' => 1, 'MSG' => 'no_guests' ) );
		}
		
		//----------------------------------
		
		if ($ibforums->input['CODE'] == '01')
		{
		
			// Send the email, yippee
			
			if ( empty($ibforums->input['to']) )
			{
				$std->Error( array( 'LEVEL' => 1, 'MSG' => 'invalid_use' ) );
			}
			
			//----------------------------------
			
			if (! preg_match( "/^(\d+)$/" , $ibforums->input['to'] ) )
			{
				$std->Error( array( 'LEVEL' => 1, 'MSG' => 'invalid_use' ) );
			}
			
			//----------------------------------
			
			$DB->query("SELECT name, id, email, hide_email from ibf_members WHERE id='".$ibforums->input['to']."'");
	
			$member = $DB->fetch_row();
			
			//----------------------------------
			
			if (! $member['id'] )
			{
				$std->Error( array( 'LEVEL' => 1, 'MSG' => 'no_such_user' ) );
			}
			
			/*if ($member['id'] == 1)
			{
				$std->Error( array( 'LEVEL' => 1, 'MSG' => 'private_email' ) );
			}*/
			
			//----------------------------------
			
			$check_array = array ( 
								   'message'   =>  'no_message',
								   'subject'   =>  'no_subject'
								 );
							 
			foreach ($check_array as $input => $msg)
			{
				if (empty($ibforums->input[$input]))
				{
					$std->Error( array( LEVEL => 1, MSG => $msg) );
				}
			}
			
			$this->email->get_template("email_member");
				
			$this->email->build_message( array(
												'MESSAGE'     => $ibforums->input['message'],
												'MEMBER_NAME' => $member['name'],
												'FROM_NAME'   => $ibforums->member['name']
											  )
										);
										
			$this->email->subject = $ibforums->input['subject'];
			$this->email->to      = $member['email'];
			$this->email->from    = $ibforums->member['email'];
			$this->email->send_mail();
			
			$forum_jump = $std->build_forum_jump();
		    $forum_jump = preg_replace( "!#Forum Jump#!", $ibforums->lang['forum_jump'], $forum_jump);
			
			$this->output  = $this->html->sent_screen($member['name']);
			
			$this->output .= $this->html->forum_jump($forum_jump);
			
			$this->page_title = $ibforums->lang['email_sent'];
			$this->nav        = array( $ibforums->lang['email_sent'] );
			
		}
		else
		{
			// Show the form, booo...
			
			if ( empty($ibforums->input['MID']) )
			{
				$std->Error( array( 'LEVEL' => 1, 'MSG' => 'invalid_use' ) );
			}
			
			//----------------------------------
			
			if (! preg_match( "/^(\d+)$/" , $ibforums->input['MID'] ) )
			{
				$std->Error( array( 'LEVEL' => 1, 'MSG' => 'invalid_use' ) );
			}
			
			//----------------------------------
			
			$DB->query("SELECT name, id, email, hide_email from ibf_members WHERE id='".$ibforums->input['MID']."'");
	
			$member = $DB->fetch_row();
			
			//----------------------------------
			
			if (! $member['id'] )
			{
				$std->Error( array( 'LEVEL' => 1, 'MSG' => 'no_such_user' ) );
			}
			
			if ($member['hide_email'] == 1)
			{
				$std->Error( array( 'LEVEL' => 1, 'MSG' => 'private_email' ) );
			}
			
			//----------------------------------
			
			$this->output = $ibforums->vars['use_mail_form']
						  ? $this->html->send_form(
													  array(
															  'NAME'   => $member['name'],
															  'TO'     => $member['id'],
														   )
						  						   )
						  : $this->html->show_address(
						  							  array(
															  'NAME'    => $member['name'],
															  'ADDRESS' => $member['email'],
														   )
													 );
													 
			$this->page_title = $ibforums->lang['member_address_title'];
			$this->nav        = array( $ibforums->lang['member_address_title'] );

		}
		
	}
        		
        		
        		
        		
}

?>