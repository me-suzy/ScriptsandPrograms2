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
 File: cp.home.php
-----------------------------------------------------
 Purpose: The control panel home page
=====================================================
*/

if ( ! defined('EXT'))
{
    exit('Invalid file request');
}


class Home {

	var $methods 	= array();
	var $query	 	= array();
	var $messages	= array();
	var $stats_ct	= 0;
	var $style_one 	= 'tableCellOne';
	var $style_two 	= 'tableCellTwo';

    // -----------------------------
    //  Constructor
    // -----------------------------   

    function Home()
    {
        global $IN, $PREFS, $FNS, $LANG, $DSP, $LOC;
        
		// --------------------------------
		//  Does the install file exist?
		// -------------------------------- 
		
		// If so, we will issue a warning  

        $path = str_replace($PREFS->ini('system_folder'), '', PATH);
        
		if (file_exists($FNS->remove_double_slashes($path.'/install'.EXT)))
		{
			$this->messages[] = $DSP->qdiv('alert', $LANG->line('install_lock_warning'));
			$this->messages[] = $DSP->qdiv('itemWrapper', $LANG->line('install_lock_removal'));
		}
		
		// --------------------------------
		//  Demo account expiration
		// -------------------------------- 
		
		// We use this code for demos.
		// Since it's only two lines of code we'll leave it in the master files
		// even though it's not needed for normal use.

		if ($PREFS->ini('demo_date'))
		{
			$expiration = ( ! $PREFS->ini('demo_expiration')) ? (60*60*24*30) : $PREFS->ini('demo_expiration');
			$this->messages[] = $DSP->qdiv('itemWrapper', $DSP->qspan('defaultBold', $LANG->line('demo_expiration').NBS.NBS.$LOC->format_timespan(($PREFS->ini('demo_date') + $expiration) - time())));
		}
		// -- End Demo Code
		
		
        // Available methods
        
        $this->methods = array(	
        						'recent_entries',
								'recent_comments',
								'recent_members',
								'site_statistics',
								'member_search_form',
								'notepad'
								);
								
        switch($IN->GBL('M'))
        {
            case 'notepad_update'		: $this->notepad_update();
                break;
            default	 					: $this->home_page();
            	break;
		}						
    }
    // END
    
    
        
    // -----------------------------
    //  Control panel home page
    // -----------------------------   
    
    function home_page()
    {  
        global $SESS, $LANG, $DB, $DSP;
        
		// ----------------------------------
		//  Fetch stats
		// ----------------------------------
        
        $sql = "SELECT * FROM exp_stats WHERE ";
        
        if (USER_BLOG !== FALSE)
        {
			$sql .= " weblog_id = '".UB_BLOG_ID."'";         
        }
        else
        {
        	$sql .= " weblog_id = '0'";
        }
        
        $this->query = $DB->query($sql);        
        
        // Turn off bread crumb
        
		$DSP->show_crumb = FALSE;
            
		$DSP->title = $LANG->line('main_menu');
		                                     
		// ----------------------------------
		//  Fetch the user display prefs
		// ----------------------------------
		
		// We'll fill two arrays.  One containing the left side options, the other containing the right side

		$left 	= array();
		$right 	= array();

		$query = $DB->query("SELECT * FROM exp_member_homepage WHERE member_id = '".$SESS->userdata['member_id']."'");

		if ($query->num_rows > 0)
		{
			foreach ($query->row as $key => $val)
			{
				if ($val == 'l')
				{
					$left[$query->row[$key.'_order'].'_'.$key] = $key;
				}
				elseif ($val == 'r')
				{
					$right[$query->row[$key.'_order'].'_'.$key] = $key;
				}
			}
		}
		
		// ----------------------------------
		//  Sort the arrays
		// ----------------------------------
		
		ksort($left);
		ksort($right);
		
		reset($left);
		reset($right);
		
		
		// ----------------------------------
		//  Build the page heading
		// ----------------------------------

        $user = ($SESS->userdata['screen_name'] == '') ? $SESS->userdata['username'] : $SESS->userdata['screen_name'];
        		
		$DSP->body	.=	$DSP->qdiv('', NBS);
		$DSP->body	.=	$DSP->table('', '0', '0', '100%');
		$DSP->body	.=	$DSP->tr();
		$DSP->body	.=	$DSP->td('leftColumn', '50%', '', '', 'bottom');
		$DSP->body  .=	$DSP->heading($LANG->line('main_menu'));
		$DSP->body	.=	$DSP->td_c();        	
		$DSP->body	.=	$DSP->td('default', '50%');
		$DSP->body	.=	$DSP->qdiv('defaultRightBold', $LANG->line('current_user').NBS.NBS.$DSP->anchor(BASE.AMP.'C=myaccount', '<b>'.$user.'</b>').NBS);
		$DSP->body	.=	$DSP->td_c();        	
		$DSP->body	.=	$DSP->tr_c();	
		
		// ----------------------------------
		//  Show system messages if they exist
		// ----------------------------------

		if (count($this->messages) > 0)
		{
			$DSP->body	.=	$DSP->tr();
			$DSP->body	.=	$DSP->td('box', '', '2');
			
			foreach ($this->messages as $msg)
			{
				$DSP->body	.=	$msg;
			}
			
			$DSP->body	.= 	$DSP->td_c();
			$DSP->body	.=	$DSP->tr_c();	
		}	
		
		// ----------------------------------
		//  Build the left page display
		// ----------------------------------
        
        if (count($left) > 0)
        {
			$DSP->body	.=	$DSP->tr();
			$DSP->body	.=	$DSP->td('leftColumn', '50%', '', '', 'top');
        
        	foreach ($left as $meth)
        	{
        		if (in_array($meth, $this->methods))
        		{
        			$DSP->body .= $DSP->qdiv('itemPad', $this->$meth());
        		}
        	}
        	
			$DSP->body	.=	$DSP->td_c();        	
        }
        
		// ----------------------------------
		//  Build the right page display
		// ----------------------------------
                
        if (count($right) > 0)
        {
			$DSP->body	.=	$DSP->td('rightColumn', '50%', '', '', 'top');
        
        	foreach ($right as $meth)
        	{
        		if (in_array($meth, $this->methods))
        		{
        			$DSP->body .= $DSP->qdiv('itemPad', $this->$meth());
        		}
        	}

			$DSP->body	.=	$DSP->td_c();        	
        }
        		
		$DSP->body	.=	$DSP->tr_c();
		$DSP->body	.=	$DSP->table_c();
    }
    // END
    
    
    
  
    // -----------------------------
    //  Recent entries
    // -----------------------------   
    
    function recent_entries()
    {  
    	global $DB, $DSP, $LANG, $FNS, $SESS;
    	
    	
        $sql = "SELECT DISTINCT 
                       exp_weblog_titles.weblog_id, 
					   exp_weblog_titles.author_id,
                       exp_weblog_titles.entry_id,         
                       exp_weblog_titles.title, 
                       exp_weblog_titles.comment_total, 
                       exp_weblog_titles.trackback_total
                FROM   exp_weblog_titles, exp_weblogs";
                                
        if ($SESS->userdata['weblog_id'] != 0)
        {        
        	$sql .= " WHERE exp_weblog_titles.weblog_id = '".$SESS->userdata['weblog_id']."'"; 
        }
        else
        {
            $sql .= " WHERE is_user_blog = 'n' ";
            
            if ($SESS->userdata['group_id'] != 1) 
            { 
                $allowed_blogs = $FNS->fetch_assigned_weblogs();
                
                // If the user is not assigned a weblog we want the
                // query to return false, so we'll use a dummy ID number
                
                if (count($allowed_blogs) == 0)
                {
                    $sql .= " AND exp_weblog_titles.weblog_id = '0'";
                }
                else
                {
                    $sql .= " AND (";
                
                    foreach ($allowed_blogs as $val)
                    {
                        $sql .= " exp_weblog_titles.weblog_id = '".$val."' OR"; 
                    }
                    
                    $sql = substr($sql, 0, -2).')';
                }
           }            
        }
        
        $sql .= " AND exp_weblog_titles.author_id = '".$SESS->userdata['member_id']."'";
                        
        $sql .= " ORDER BY entry_date desc
        		  LIMIT 10"; 
        
		$query = $DB->query($sql);
    	
		// -----------------------------
    	//  Define alternating style
		// -----------------------------   		
    	
		$i = 0;

		$s1 = 'tableCellOne';
		$s2 = 'tableCellTwo';
		
		// -----------------------------
    	//  Table Header
		// -----------------------------   		

        $r  = $DSP->table('tableBorder', '0', '0', '100%').
              $DSP->tr().
              $DSP->td('tablePad'); 

        $r .= $DSP->table('', '0', '0', '100%').
              $DSP->tr().
              $DSP->table_qcell('tableHeadingBold',
                                ($query->num_rows == 0) ? 
                                	array($LANG->line('most_recent_entries')) : 
                                	array($LANG->line('most_recent_entries'), $LANG->line('comments'))
                                ).
              $DSP->tr_c();
              
		// -----------------------------
    	//  Table Rows
		// -----------------------------   		
              
        if ($query->num_rows == 0)
        {
			$r .= $DSP->table_qrow( ($i++ % 2) ? $s1 : $s2, 
									array(
											$LANG->line('no_entries')
										  )
									);
        }
        else
        {
			foreach ($query->result as $row)
			{
				$total = $row['comment_total'] + $row['trackback_total'];
			
				$r .= $DSP->table_qrow( ($i++ % 2) ? $s1 : $s2, 
										array(
										
											$DSP->qspan('defaultBold', $DSP->anchor(BASE.AMP.'C=edit'.AMP.'M=edit_entry'.AMP.'weblog_id='.$row['weblog_id'].AMP.'entry_id='.$row['entry_id'], $row['title'])),
											$DSP->qspan('', $DSP->anchor(BASE.AMP.'C=edit'.AMP.'M=view_comments'.AMP.'weblog_id='.$row['weblog_id'].AMP.'entry_id='.$row['entry_id'], '('.$total.')'))
											  )
										);
			}	
        }
        
        $r .= $DSP->table_c(); 

        $r .= $DSP->td_c()   
             .$DSP->tr_c()
             .$DSP->table_c();  
    	
    	return $r;
	}
	// END  
  
  
    // -----------------------------
    //  Recent comments
    // -----------------------------   
    
    function recent_comments()
    {  
    	global $DB, $DSP, $LANG, $SESS, $FNS, $LOC;
    	
        $sql = "SELECT DISTINCT 
                       exp_weblog_titles.weblog_id, 
                       exp_weblog_titles.author_id,
                       exp_weblog_titles.entry_id,         
                       exp_weblog_titles.title, 
                       exp_weblog_titles.recent_comment_date,
                       exp_weblog_titles.recent_trackback_date
                FROM   exp_weblog_titles, exp_weblogs";
                        
        if ($SESS->userdata['weblog_id'] != 0)
        {        
        	$sql .= " WHERE exp_weblog_titles.weblog_id = '".$SESS->userdata['weblog_id']."'"; 
        }
        else
        {
            $sql .= " WHERE is_user_blog = 'n'";
            
            if ($SESS->userdata['group_id'] != 1) 
            { 
                $allowed_blogs = $FNS->fetch_assigned_weblogs();
                
                // If the user is not assigned a weblog we want the
                // query to return false, so we'll use a dummy ID number
                
                if (count($allowed_blogs) == 0)
                {
                    $sql .= " AND exp_weblog_titles.weblog_id = '0'";
                }
                else
                {
                    $sql .= " AND (";
                
                    foreach ($allowed_blogs as $val)
                    {
                        $sql .= " exp_weblog_titles.weblog_id = '".$val."' OR"; 
                    }
                    
                    $sql = substr($sql, 0, -2).')';
                }
           }            
        }
        
        $sql .= " AND exp_weblog_titles.author_id = '".$SESS->userdata['member_id']."'";
                        
        $sql .= " AND (recent_comment_date != '' || recent_trackback_date != '')
				  ORDER BY COALESCE(recent_comment_date, recent_trackback_date) desc
        		  LIMIT 10"; 

		$query = $DB->query($sql);

    	
		// -----------------------------
    	//  Define alternating style
		// -----------------------------   		
    	
		$i = 0;
		
		$s1 = 'tableCellOne';
		$s2 = 'tableCellTwo';
		
		// -----------------------------
    	//  Table Header
		// -----------------------------   		

        $r  = $DSP->table('tableBorder', '0', '0', '100%').
              $DSP->tr().
              $DSP->td('tablePad'); 

        $r .= $DSP->table('', '0', '0', '100%').
              $DSP->tr().
              $DSP->table_qcell('tableHeadingBold',
                                ($query->num_rows == 0) ? 
                                	array($LANG->line('most_recent_comments')) : 
                                	array($LANG->line('most_recent_comments'), $LANG->line('date'))
                                ).
              $DSP->tr_c();
              
		// -----------------------------
    	//  Table Rows
		// -----------------------------   		

        if ($query->num_rows == 0)
        {
			$r .= $DSP->table_qrow( ($i++ % 2) ? $s1 : $s2, 
									array(
											$LANG->line('no_comments')
										  )
									);
        }
        else
        {
			foreach ($query->result as $row)
			{			
				$date = ($row['recent_comment_date'] > $row['recent_trackback_date']) ? $row['recent_comment_date'] : $row['recent_trackback_date'];
			
				$r .= $DSP->table_qrow( ($i++ % 2) ? $s1 : $s2, 
										array(
												$DSP->qdiv('defaultBold', $DSP->anchor(BASE.AMP.'C=edit'.AMP.'M=view_comments'.AMP.'weblog_id='.$row['weblog_id'].AMP.'entry_id='.$row['entry_id'], $row['title'])),
												$DSP->qdiv('nowrap', $LOC->set_human_time($date))
											  )
										);
			}
		}	
        
        $r .= $DSP->table_c(); 

        $r .= $DSP->td_c()   
             .$DSP->tr_c()      
             .$DSP->table_c();  
    	
    	return $r;
	}
	// END



    // -----------------------------
    //  Recent members
    // -----------------------------   
    
    function recent_members()
    {  
    	global $DB, $DSP, $LANG, $LOC, $SESS;
    	
        $sql = "SELECT member_id, username, screen_name, group_id, join_date
                FROM   exp_members
                ORDER BY join_date desc
                LIMIT 10";

		$query = $DB->query($sql);
    	
		// -----------------------------
    	//  Define alternating style
		// -----------------------------   		
    	
		$i = 0;

		$s1 = 'tableCellOne';
		$s2 = 'tableCellTwo';
		
		// -----------------------------
    	//  Table Header
		// -----------------------------   		

        $r  = $DSP->table('tableBorder', '0', '0', '100%').
              $DSP->tr().
              $DSP->td('tablePad'); 

        $r .= $DSP->table('', '0', '0', '100%').
              $DSP->tr().
              $DSP->table_qcell('tableHeadingBold',
                                array(
                                		$LANG->line('recent_members'), 
                                		$LANG->line('join_date')
                                	 )
                                ).
              $DSP->tr_c();
              
		// -----------------------------
    	//  Table Rows
		// -----------------------------   
				
		foreach ($query->result as $row)
		{
			$name = ($row['screen_name'] == '') ? $row['username'] : $row['screen_name'];
		
			$r .= $DSP->table_qrow( ($i++ % 2) ? $s1 : $s2, 
									array(
									
										$DSP->qspan('defaultBold', $DSP->anchor(BASE.AMP.'C=myaccount'.AMP.'id='.$row['member_id'], $name)),
										$LOC->set_human_time($row['join_date'])
										  )
									);
		}	
        
        $r .= $DSP->table_c(); 

        $r .= $DSP->td_c()   
             .$DSP->tr_c()
             .$DSP->table_c();  
    	
    	return $r;
	}
	// END  



    // -----------------------------
    //  Site statistics
    // -----------------------------   
    
    function site_statistics()
    {  
    	global $DSP, $LANG, $PREFS, $SESS, $DB;
    	
		// -----------------------------
    	//  Define alternating style
		// -----------------------------   		
    	
		$i = 0;
		
		$s1 = 'tableCellOne';
		$s2 = 'tableCellTwo';
		
		// -----------------------------
    	//  Table Header
		// -----------------------------   		

        $r  = $DSP->table('tableBorder', '0', '0', '100%').
              $DSP->tr().
              $DSP->td('tablePad'); 

        $r .= $DSP->table('', '0', '0', '100%').
              $DSP->tr().
              $DSP->table_qcell('tableHeadingBold', 
                                array(
                                        $LANG->line('site_statistics'),
                                        $LANG->line('value')
                                     )
                                ).
              $DSP->tr_c();
  
  
		if ($SESS->userdata['group_id'] == 1)
		{    	
			$r .= $this->system_status();
		}
		
		$r .= $this->total_weblog_entries();
		
		$r .= $this->total_comments();							

		$r .= $this->total_trackbacks();							
	
		$r .= $this->total_hits();
		
		if ($SESS->userdata['group_id'] == 1)
		{    	
			$r .= $this->total_members();
	
			$r .= $this->total_validating_members();			
		}		
		
        if ($DSP->allowed_group('can_moderate_comments'))
        {
			$r .= $this->total_validating_comments();
        }        
		
        $r .= $DSP->table_c(); 

        $r .= $DSP->td_c()   
             .$DSP->tr_c()      
             .$DSP->table_c();  
    	
    	return $r;
	}
	// END


	// -----------------------------
	//  Total Hits
	// ----------------------------- 
	
	function total_hits()
	{
  		global $DB, $LANG, $SESS, $DSP;	

		$sql = "SELECT SUM(exp_templates.hits) AS total
				FROM   exp_templates, exp_template_groups
				WHERE  exp_templates.group_id = exp_template_groups.group_id";
		
        if ($SESS->userdata['weblog_id'] != 0)
        {        
        	$sql .= " AND exp_templates.group_id = '".UB_TMP_GRP."'"; 
        }
        else
        {
            $sql .= " AND exp_template_groups.is_user_blog = 'n'";
        }
		
				
		$query = $DB->query($sql);

		return $DSP->table_qrow( ($this->stats_ct++ % 2) ? $this->style_one : $this->style_two, 
								array(
										$DSP->qspan('defaultBold', $LANG->line('total_hits')),
										$query->row['total']
									  )
								);	
	}
	// END  



	// -----------------------------
	//  Total Validating Members
	// ----------------------------- 
			
	function total_validating_members()
	{  
  		global $DB, $LANG, $DSP, $PREFS;
  		
  		$total = 0;
  		
		if ($PREFS->ini('req_mbr_activation') == 'manual')
		{  		
			$query = $DB->query("SELECT count(member_id) AS count FROM exp_members WHERE group_id = '4'");
	
			$total = $query->row['count'] ;
		}
		
		$link = ($total > 0) ? $DSP->required().NBS.$DSP->anchor(BASE.AMP.'C=admin&M=members&P=member_validation', $LANG->line('total_validating_members')) : $LANG->line('total_validating_members');

		return $DSP->table_qrow(($this->stats_ct++ % 2) ? $this->style_one : $this->style_two, 
								array(
										$DSP->qspan('defaultBold', $link),
										$total
									  )
								);
		
  	}
  	// END


	// -----------------------------
	//  Total Validating Comments
	// ----------------------------- 
			
	function total_validating_comments()
	{  
  		global $DB, $LANG, $DSP, $PREFS;
  		
  		$total = 0;
	
		$query = $DB->query("SELECT count(comment_id) AS count FROM exp_comments WHERE status = 'c'");

		$total = $query->row['count'] ;
		
		$link = ($total > 0) ? $DSP->required().NBS.$DSP->anchor(BASE.AMP.'C=edit&M=view_comments&validate=1', $LANG->line('total_validating_comments')) : $LANG->line('total_validating_comments');

		return $DSP->table_qrow(($this->stats_ct++ % 2) ? $this->style_one : $this->style_two, 
								array(
										$DSP->qspan('defaultBold', $link),
										$total
									  )
								);
		
  	}
  	// END


	// -----------------------------
	//  Total Members
	// ----------------------------- 			
  
	function total_members()
	{
  		global $DB, $LANG, $DSP;

		return $DSP->table_qrow( ($this->stats_ct++ % 2) ? $this->style_one : $this->style_two, 
								array(
										$DSP->qspan('defaultBold', $LANG->line('total_members')),
										$this->query->row['total_members']
									  )
								);
	}  
  	// END



	// -----------------------------
	//  Total Trackbacks
	// ----------------------------- 
			
	function total_trackbacks()
	{ 
  		global $DB, $LANG, $DSP;

		return $DSP->table_qrow( ($this->stats_ct++ % 2) ? $this->style_one : $this->style_two, 
								array(
										$DSP->qspan('defaultBold', $LANG->line('total_trackbacks')),
										$this->query->row['total_trackbacks']
									  )
								);
  	}
	// END
	
	
	  
	// -----------------------------
	//  Total Comments
	// ----------------------------- 
			
	function total_comments()
	{  
  		global $DB, $LANG, $DSP;

		return $DSP->table_qrow( ($this->stats_ct++ % 2) ? $this->style_one : $this->style_two, 
								array(
										$DSP->qspan('defaultBold', $LANG->line('total_comments')),
										$this->query->row['total_comments']
									  )
								);
	}
	// END
	
	
	  
	// -----------------------------
	//  Total Weblog Entries
	// ----------------------------- 

	function total_weblog_entries()
	{  
  		global $DB, $LANG, $DSP;
				
		return $DSP->table_qrow( ($this->stats_ct++ % 2) ? $this->style_one : $this->style_two, 
								array(
										$DSP->qspan('defaultBold', $LANG->line('total_entries')),
										$this->query->row['total_entries']
									  )
								);
	}
	// END  



	// -----------------------------
	//  System status
	// -----------------------------   		

	function system_status()
	{
  		global $DB, $LANG, $DSP, $PREFS;
		
		return $DSP->table_qrow( ($this->stats_ct++ % 2) ? $this->style_one : $this->style_two, 
								array(
										$DSP->qspan('defaultBold', $LANG->line('system_status')),
										($PREFS->ini('is_system_on') == 'y') ? $DSP->qdiv('highlight_alt_bold', $LANG->line('online')) : $DSP->qdiv('highlight_bold', $LANG->line('offline'))
									  )
								);
	}  
  	// END



    // -----------------------------
    //  Member search form
    // -----------------------------   
    
    function member_search_form()
    {  
        global $LANG, $DSP, $DB;

        
        $r = $DSP->form('C=admin'.AMP.'M=members'.AMP.'P=do_member_search');
        
        $r .= $DSP->div('box');
        
		$r .= $DSP->heading($LANG->line('member_search') ,5);

		$r .= $DSP->qdiv('itemWrapper', $LANG->line('search_instructions', 'keywords'));

		$r .= $DSP->qdiv('itemWrapper', $DSP->input_text('keywords', '', '35', '100', 'input', '100%'));

        $r .= $DSP->input_select_header('criteria');
        $r .= $DSP->input_select_option('username', 	$LANG->line('search_by'));
		$r .= $DSP->input_select_option('username', 	$LANG->line('username'));
		$r .= $DSP->input_select_option('screen_name', 	$LANG->line('screen_name'));
		$r .= $DSP->input_select_option('email',		$LANG->line('email_address'));
		$r .= $DSP->input_select_option('url', 			$LANG->line('url'));
        $r .= $DSP->input_select_footer();
                              
        // Member group select list

        $query = $DB->query("SELECT group_id, group_title FROM  exp_member_groups WHERE group_id != '1' order by group_title");
              
        $r.= $DSP->input_select_header('group_id');
        
        $r.= $DSP->input_select_option('any', $LANG->line('member_group'));
        $r.= $DSP->input_select_option('any', $LANG->line('any'));
                                
        foreach ($query->result as $row)
        {                                
            $r .= $DSP->input_select_option($row['group_id'], $row['group_title']);
        }
        
        $r.= $DSP->input_select_footer();
        
        $r.= $DSP->input_submit($LANG->line('submit'));
        
        // END select list
        
        $r.= $DSP->div_c();
                        
        $r.= $DSP->form_c();
        
        return $r;
	}
	// END  
	
    // -----------------------------
    //  Validating members
    // -----------------------------   
    
    function validating_members()
    {  
    	global $DSP;
    	
  		return  $DSP->heading('validating_members', 5);
	}
	// END  
	
  
    // -----------------------------
    //  Notepad
    // -----------------------------   
    
    function notepad()
    {  
        global $DB, $DSP, $SESS, $LANG;
                
        $query = $DB->query("SELECT notepad, notepad_size FROM exp_members WHERE member_id = '".$SESS->userdata['member_id']."'");
    
    	return
        		 $DSP->form('C=home'.AMP.'M=notepad_update')
        		.$DSP->heading($LANG->line('notepad'), 5)
        		.$DSP->qdiv('', $DSP->input_textarea('notepad', $query->row['notepad'], 10, 'textarea', '100%'))
        		.$DSP->qdiv('', $DSP->input_submit($LANG->line('update')))
        		.$DSP->form_c();    	
	}
	// END  
	

    // ----------------------------------
    //  Update notepad
    // ----------------------------------
    
    function notepad_update()
    {  
        global $DB, $FNS, $SESS;

        $DB->query("UPDATE exp_members SET notepad = '".$DB->escape_str($_POST['notepad'])."' WHERE member_id ='".$SESS->userdata['member_id']."'");
        
        $FNS->redirect(BASE);
        exit;    
    }
    // END
	
	
}
// END CLASS
?>