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
 File: cp.communicate.php
-----------------------------------------------------
 Purpose: Email sending/management functions
=====================================================
*/

if ( ! defined('EXT'))
{
    exit('Invalid file request');
}



class Communicate {

    
    // -----------------------------
    //  Constructor
    // -----------------------------   

    function Communicate()
    {
        global $DSP, $LANG, $IN;
        
        if ( ! $DSP->allowed_group('can_access_comm'))
        {
            return $DSP->no_access_message();
        }
                
        // Fetch the needed language files
        
        $LANG->fetch_language_file('communicate');
        

        switch($IN->GBL('M'))
        {
            case 'send_email' 	: $this->send_email();          
                break;
            case 'batch_send' 	: $this->batch_send();          
                break;
            case 'view_cache' 	: $this->view_email_cache();          
                break;
            case 'view_email' 	: $this->view_email();          
                break;
            case 'delete_conf' 	: $this->delete_confirm();          
                break;
            case 'delete' 		: $this->delete_emails();          
                break;
            default           	: $this->email_form();  
                break;
        }     
    }
    // END
    
        
    
    // -----------------------------
    //  Email form
    // -----------------------------   
    
    function email_form()
    {  
        global $IN, $DSP, $DB, $PREFS, $SESS, $LANG;
        
		// -----------------------------
		//  Default form values
		// -----------------------------
		
		$member_groups	= array();
        
        $default = array(
							'from_name'		=> '',
							'from_email' 	=> $SESS->userdata['email'],
							'recipient'  	=> '',
							'cc'			=> '',
							'bcc'			=> '',
							'subject' 		=> '',
							'message'		=> '',
							'priority'		=>  3,
							'mailinglist'	=> 'n',
							'mailtype'		=> $PREFS->ini('mail_format'),
							'word_wrap'		=> $PREFS->ini('word_wrap')
        				);
        
        
		// -----------------------------
		//  Fetch form data
		// -----------------------------   

		// If the user is viewing a cached email, we'll gather the data
		
        if ($id = $IN->GBL('id', 'GET'))
        {     
			if ( ! $DSP->allowed_group('can_send_cached_email'))
			{     
				return $DSP->no_access_message($LANG->line('not_allowed_to_email_mailinglist'));
			}
			
			// Fetch cached data        
           
        	$query = $DB->query("SELECT * FROM exp_email_cache WHERE cache_id = '$id'");
        
        	if ($query->num_rows > 0)
        	{
        		foreach ($query->row as $key => $val)
        		{
        			if (isset($default[$key]))
        			{
        				$default[$key] = $val;
        			}
        		}
        	}
        	
        	// Fetch member group IDs
        	
        	$query = $DB->query("SELECT group_id FROM exp_email_cache_mg WHERE cache_id = '$id'");
        	
        	if ($query->num_rows > 0)
        	{
        		foreach ($query->result as $row)
        		{
					$member_groups[] = $row['group_id'];
        		}
        	}
        }
        
       
		// -----------------------------------
		//  Turn default data into variables
		// -----------------------------------
        
        foreach ($default as $key => $val)
        {
        	$$key = $val;
        }

		// -----------------------------------
		//  Create the email form
		// -----------------------------------
		
        $DSP->title = $LANG->line('communicate');
        $DSP->crumb	= $LANG->line('communicate');
        
		if ($DSP->allowed_group('can_send_cached_email'))
		{     
        	$DSP->rcrumb 	= $DSP->qdiv('crumblinksR', $DSP->anchor(BASE.AMP.'C=communicate'.AMP.'M=view_cache', $LANG->line('view_email_cache')));
		}
		
        $r  = $DSP->heading($LANG->line('send_an_email'));
        
        $r .= $DSP->form('C=communicate'.AMP.'M=send_email');
              
        $r .= $DSP->table('', '0', '0', '100%').
              $DSP->tr().
              $DSP->td('', '65%', '', '', 'top');
                      
            // -----------------------------
            //  Subject and message feilds
            // -----------------------------   
                      
        $r .= $DSP->div('itemWrapper').
              $DSP->qdiv('itemTitle', $DSP->required().NBS.$LANG->line('subject', 'subject')).
              $DSP->qdiv('', $DSP->input_text('subject', $subject, '20', '75', 'input', '93%')).
              $DSP->div_c();
              
        $r .= $DSP->div('itemWrapper').
              $DSP->qdiv('itemTitle', $DSP->required().NBS.$LANG->line('message', 'message')).
              $DSP->qdiv('', $DSP->input_textarea('message', $message, 21, 'textarea', '93%')).
              $DSP->div_c();
                                
            // -----------------------------
            //  Mail formatting buttons
            // -----------------------------
            
		$r .= $DSP->table('tableBorder', '0', '0', '93%').
			  $DSP->tr().
			  $DSP->td('tablePad'); 

        $r .= $DSP->table('', '0', '', '100%').
              $DSP->tr().
              $DSP->td('tableCellTwoBold', '50%').$LANG->line('mail_format').$DSP->td_c().
              $DSP->td('tableCellTwoBold', '50%').
              $DSP->input_select_header('mailtype').
      		  $DSP->input_select_option('plain', $LANG->line('plain_text'), ($mailtype == 'plain') ? 1 : '').
        	  $DSP->input_select_option('html',  $LANG->line('html'), ($mailtype == 'html') ? 1 : '').
              $DSP->input_select_footer().
              $DSP->td_c().
              $DSP->tr_c();
              
        $r .= $DSP->tr().
              $DSP->td('tableCellOneBold').$LANG->line('word_wrap').$DSP->td_c().
              $DSP->td('tableCellOneBold').
              $DSP->input_select_header('wordwrap').
			  $DSP->input_select_option('y', $LANG->line('on'), ($word_wrap == 'y') ? 1 : '').
			  $DSP->input_select_option('n',  $LANG->line('off'), ($word_wrap == 'n') ? 1 : '').
              $DSP->input_select_footer().
              $DSP->td_c().
              $DSP->tr_c();
              
        $r .= $DSP->tr().
              $DSP->td('tableCellTwoBold').$LANG->line('priority').$DSP->td_c().
              $DSP->td('tableCellTwoBold').
              $DSP->input_select_header('priority').
              $DSP->input_select_option('1', '1 ('.$LANG->line('highest').')',	($priority == 1) ? 1 : '').
              $DSP->input_select_option('2', '2 ('.$LANG->line('high').')',		($priority == 2) ? 1 : '').
              $DSP->input_select_option('3', '3 ('.$LANG->line('normal').')', 	($priority == 3) ? 1 : '').
              $DSP->input_select_option('4', '4 ('.$LANG->line('low').')',		($priority == 4) ? 1 : '').
              $DSP->input_select_option('5', '5 ('.$LANG->line('lowest').')',	($priority == 5) ? 1 : '').
              $DSP->input_select_footer();
  
        $r .= $DSP->td_c().
              $DSP->tr_c().
			  $DSP->table_c().
              $DSP->td_c().
              $DSP->tr_c().
              $DSP->table_c();
              
        // -----------------------------
        //  Submit button
        // -----------------------------              
            
        if ($DSP->allowed_group('can_email_member_groups'))
        {         
        	$r .= $DSP->qdiv('itemWrapper', BR.$DSP->input_checkbox('accept_admin_email', 'y', 1).NBS.$LANG->line('honor_email_pref')); 
		}        
        
		$r .= $DSP->qdiv('itemWrapper', BR.$DSP->required(1));
		$r .= $DSP->qdiv('itemWrapper', BR.$DSP->input_submit($LANG->line('send_it')));


        // -----------------------------
        //  Right side of page
        // -----------------------------              
              
        $r .= $DSP->td_c().
              $DSP->td('', '35%', '', '', 'top');
                
        // -----------------------------
        //  Sender/recipient fields
        // ----------------------------- 

        $r .= $DSP->div('itemWrapper').
              $DSP->qdiv('itemTitle', $LANG->line('your_name', 'name')).
              $DSP->qdiv('', $DSP->input_text('name', $from_name, '20', '50', 'input', '300px')).
              $DSP->div_c();
        
        $r .= $DSP->div('itemWrapper').
              $DSP->qdiv('itemTitle', $DSP->required().NBS.$LANG->line('your_email', 'from')).
              $DSP->qdiv('', $DSP->input_text('from', $from_email, '20', '75', 'input', '300px')).
              $DSP->div_c();
        
        $r .= $DSP->div('itemWrapper').
              $DSP->qdiv('itemWrapper', '<b>'.$LANG->line('recipient', 'recipient').'</b>').
              $DSP->qdiv('', $LANG->line('separate_emails_with_comma')).
              $DSP->qdiv('', $DSP->input_text('recipient', $recipient, '20', '150', 'input', '300px')).
              $DSP->div_c();
              
        $r .= $DSP->div('itemWrapper').
              $DSP->qdiv('itemTitle', $LANG->line('cc', 'cc')).
              $DSP->qdiv('', $DSP->input_text('cc', $cc, '20', '150', 'input', '300px')).
              $DSP->div_c();

        $r .= $DSP->div('itemWrapper').
              $DSP->qdiv('itemTitle', $LANG->line('bcc', 'bcc')).
              $DSP->qdiv('', $DSP->input_text('bcc', $bcc, '20', '150', 'input', '300px').BR).
              $DSP->div_c();    
		  
		if ($DSP->allowed_group('can_email_mailinglist'))	  
		{
			$r .= $DSP->table('tableBorder', '0', '0', '300px').
				  $DSP->tr().
				  $DSP->td(''); 
			$r .= $DSP->qdiv('tableHeadingBoldNoBot', $DSP->input_checkbox('mailinglist', 'y', ($mailinglist == 'y') ? 1 : '').NBS.$LANG->line('send_to_mailinglist')); 
			$r .= $DSP->td_c().
				  $DSP->tr_c().
				  $DSP->table_c();
		}
		
        // -----------------------------
        //  Member group selection
        // -----------------------------              
              
        if ($DSP->allowed_group('can_email_member_groups'))
        {         
            $r .= $DSP->table('tableBorder', '0', '0', '300px').
                  $DSP->tr().
                  $DSP->td('tablePad'); 
              
            $r .= $DSP->table('', '0', '', '100%').
                  $DSP->tr().
                  $DSP->td('tableHeading', '', '2').
                  $DSP->qdiv('itemWrapper', '<b>'.$LANG->line('recipient_group').'</b>').
                  $DSP->td_c().
                  $DSP->tr_c();
        
            $i = 0;
            
            $query = $DB->query("SELECT group_id, group_title FROM exp_member_groups ORDER BY group_title");
            
            
            foreach ($query->result as $row)
            {
                $style = ($i++ % 2) ? 'tableCellOne' : 'tableCellTwo';
        
                $r .= $DSP->tr().
                      $DSP->td($style, '50%').'<b>'.$row['group_title'].'</b>'.$DSP->td_c().
                      $DSP->td($style, '50%');
                      
                                                              
                $r .= $DSP->input_checkbox('group_'.$row['group_id'], $row['group_id'], (in_array($row['group_id'], $member_groups)) ? 1 : '').$DSP->nbs(1).$LANG->line('yes');
    
                $r .= $DSP->td_c()
                     .$DSP->tr_c();
            }        
        
            $r .= $DSP->table_c(); 
            
            $r .= $DSP->td_c().
                  $DSP->tr_c().
                  $DSP->table_c();
        }
              
        // -----------------------------
        //  Table end
        // -----------------------------              
              
        $r .= $DSP->td_c()   
             .$DSP->tr_c()      
             .$DSP->table_c();      
                        
        $r.=  $DSP->form_c();        
        
        $DSP->body  = &$r;
    }
    // END



    
    // -----------------------------
    //  Send email
    // -----------------------------   
    
    function send_email()
    {  
        global $DSP, $DB, $IN, $FNS, $REGX, $LANG, $SESS, $LOC, $PREFS;
        
        // -----------------------------
        // Are we missing any fields?
        // -----------------------------
        
        if ( ! $IN->GBL('from',		'POST') OR
             ! $IN->GBL('subject',	'POST') OR
             ! $IN->GBL('message',	'POST')
           )
        {
            return $DSP->error_message($LANG->line('empty_form_fields'));
        }
        
        // -----------------------------
        // Fetch $_POST data
        // -----------------------------
        
        // We'll turn the $_POST data into variables for simplicity
        
        $groups = array();
        
        foreach ($_POST as $key => $val)
        {
            if (substr($key, 0, 6) == 'group_')
            {
                $groups[] = $val;
            }            
            else
            {            
                $$key = stripslashes($val);
            }
        }
        
        // Was the "mailinglist" button clicked?
        
		$mailinglist = (isset($mailinglist)) ? TRUE : FALSE;
        
        // -----------------------------
        // Verify privileges
        // -----------------------------

        if (count($groups) > 0  AND  ! $DSP->allowed_group('can_email_member_groups'))
        {     
            return $DSP->no_access_message($LANG->line('not_allowed_to_email_member_groups'));
        }        
          
        if ($mailinglist == TRUE AND  ! $DSP->allowed_group('can_email_mailinglist'))
        {     
            return $DSP->no_access_message($LANG->line('not_allowed_to_email_mailinglist'));
        }        
                
        if (count($groups) == 0  AND $mailinglist == FALSE AND ! $IN->GBL('recipient', 'POST'))
        {
            return $DSP->error_message($LANG->line('empty_form_fields'));
        }
         
         
        // -------------------------------
        //  Assign data for caching
        // -------------------------------

        $cache_data = array(
								'cache_id'      	=> '',
								'cache_date'		=> $LOC->now,
								'total_sent'    	=> 0,
								'from_name'     	=> $name,
								'from_email'    	=> $from,
								'recipient'    		=> $recipient,
								'cc'    			=> $cc,
								'bcc'    			=> $bcc,
								'recipient_array'   => '',
								'subject'       	=> $subject,
								'message'       	=> $message,
								'mailinglist'      	=> ($mailinglist == TRUE) ? 'y' : 'n',
								'mailtype'      	=> $mailtype,
								'wordwrap'      	=> $wordwrap,
								'priority'      	=> $priority
						   );
         
         
        // -----------------------------
        //  Send a single email
        // -----------------------------
        
        if (count($groups) == 0 AND $mailinglist == FALSE)
        { 
            require PATH_CORE.'core.email'.EXT;
            
			$to = ($recipient == '') ? $SESS->userdata['email'] : $recipient;
                        
            $email = new EEmail;        
            $email->wordwrap  = ($wordwrap == 'y') ? TRUE : FALSE;
            $email->mailtype  = $mailtype;
            $email->priority  = $priority;
            $email->from($from, $name);	
            $email->to($to); 
            $email->cc($cc); 
            $email->bcc($bcc); 
            $email->subject($subject);	
            $email->message($message);	
            
            if ( ! $email->Send())
            {
                return $DSP->error_message($LANG->line('error_sending_email'), 0);
            }
       
			// ---------------------------------
			//  Save cache data
			// ---------------------------------
   			
   			$cache_data['total_sent'] = $this->fetch_total($to, $cc, $bcc);
   			
   			$this->save_cache_data($cache_data);
   
			// ---------------------------------
			//  Show success message
			// ---------------------------------
   
            $DSP->set_return_data($LANG->line('email_sent'), $DSP->qdiv('defaultPad', $DSP->qdiv('success', $LANG->line('email_sent_message'))), $LANG->line('email_sent'));
   
   			// We're done
   
            return;
        }

        //  Send Multi-emails
        
        
        // ----------------------------------------
        //  Is Batch Mode set?
        // ----------------------------------------
        
        $batch_mode = $PREFS->ini('email_batchmode');
        $batch_size = $PREFS->ini('email_batch_size');
        
        if ( ! is_numeric($batch_size))
        {
            $batch_mode = 'n';
        }
                

        $emails = array();
       
        // ---------------------------------
        //  Fetch member group emails
        // ---------------------------------

		if (count($groups) > 0)
		{
			$sql = "SELECT exp_members.member_id, exp_members.email 
					FROM   exp_members, exp_member_groups
					WHERE  exp_members.group_id = exp_member_groups.group_id ";
	
			if (isset($_POST['accept_admin_email']))
			{
				$sql .= "AND exp_members.accept_admin_email = 'y' ";
			}
			
			$sql .= "AND (";
			
			foreach ($groups as $id)
			{
				$sql .= " exp_member_groups.group_id = '".$id."' OR";
			}
	
			$sql = substr($sql, 0, -2);
			
			$sql .= ")";
			
			// Run the query
	
			$query = $DB->query($sql);
						
			if ($query->num_rows > 0)
			{
				foreach ($query->result as $row)
				{
					$emails['m'.$row['member_id']] = $row['email'];					
				}
			}
		}
        
        
        // ---------------------------------
        //  Fetch mailing list emails
        // ---------------------------------

		if ($mailinglist == TRUE)
		{	
			$query = $DB->query("SELECT authcode, email FROM exp_mailing_list ORDER BY user_id");
			
			// No result?  Show error message
			
			if ($query->num_rows == 0)
			{
				return $DSP->set_return_data($LANG->line('send_an_email'), $DSP->qdiv('defaultPad', $DSP->qdiv('alert', $LANG->line('no_email_matching_criteria'))), $LANG->line('send_an_email'));
			}
	
			if ($query->num_rows > 0)
			{
				foreach ($query->result as $row)
				{
					$emails['l'.$row['authcode']] = $row['email'];
				}
			}
		}
		
        // ----------------------------------------
        //  Kill duplicates
        // ----------------------------------------
        
        $emails = array_unique($emails);
        

        // ----------------------------------------
        //  After all that, do we have any emails?
        // ----------------------------------------
			
		if (count($emails) == 0 AND $recipient == '')
		{
			return $DSP->set_return_data($LANG->line('send_an_email'), $DSP->qdiv('defaultPad', $DSP->qdiv('alert', $LANG->line('no_email_matching_criteria'))), $LANG->line('send_an_email'));
		}	
		
		
					
		// ----------------------------------------
		//  Do we have any CCs or BCCs?
		// ----------------------------------------
	
		//  If so, we'll send those separately first
		
		$total_sent = 0;
		
		$recips = array();
		
		if ($cc != '' || $bcc != '')
		{				
			if ( ! class_exists('EEmail'))
			{
				require PATH_CORE.'core.email'.EXT;
			}
			
			$to = ($recipient == '') ? $SESS->userdata['email'] : $recipient;
						
			$email = new EEmail;    
			$email->wordwrap  = ($wordwrap == 'y') ? TRUE : FALSE;
			$email->mailtype  = $mailtype;
			$email->priority  = $priority;
			$email->from($from, $name);	
			$email->to($to); 
			$email->cc($cc); 
			$email->bcc($bcc); 
			$email->subject($subject);	
			$email->message($message);	
			
			if ( ! $email->Send())
			{
				return $DSP->error_message($LANG->line('error_sending_email'), 0);
			}	
			
   			$total_sent = $this->fetch_total($to, $cc, $bcc);
		}
		else
		{
			// No CC/BCCs? Convert recipients to an array so we can include them in the email sending cycle
		
			if ($recipient != '')
				$recips = $this->convert_recipients($recipient);
		}
		
		if (count($recips) > 0)
		{
			$emails = array_merge($emails, $recips);
        	$emails = array_unique($emails);
		}
	
			
		
        // ----------------------------------------
        //  If batch-mode is not set, send emails
        // ----------------------------------------
 
        if (count($emails) <= $batch_size)
        {
            $batch_mode = 'n';
        }
                
        if ($batch_mode == 'n')
        {
 			$action_id  = $FNS->fetch_action_id('Mailinglist', 'unsubscribe');
         
			if ( ! class_exists('EEmail'))
			{
				require PATH_CORE.'core.email'.EXT;
			}
						
			$email = new EEmail;    
	
			$email->wordwrap  = ($wordwrap == 'y') ? TRUE : FALSE;
			$email->mailtype  = $mailtype;
			$email->priority  = $priority;
                        
			foreach ($emails as $key => $val)
			{
				$email->to($val); 
				$email->from($from, $name);	
				$email->subject($subject);
				
				// We need to add the unsubscribe link to emails - but only ones
				// from the mailing list.  When we gathered the email addresses
				// above, we added one of three prefixes to the array key:
				//
				// m = member id
				// l = mailing list
				// r = general recipient
				//
				// So, we'll do a little substr() check and add the link when needed.
				
				$msg = $message;
				
				if (substr($key, 0, 1) == 'l')
				{
					$msg .= $this->add_unsubscribe_link($val, $action_id, substr($key, 1));
				}
				
				$email->message($msg);	
				
				if ( ! $email->Send())
				{
					return $DSP->error_message($LANG->line('error_sending_email'), 0);
				}
				
				$email->initialize();
				
				$total_sent++;
			}
			
			
			// ----------------------------------------
			//  Store email cache
			// ----------------------------------------
			
			$cache_data['total_sent'] = $total_sent;
	
			$this->save_cache_data($cache_data, $groups);
				
        
			// ----------------------------------------
			//  Success Mesage
			// ----------------------------------------
        
			$DSP->set_return_data($LANG->line('email_sent'), $DSP->qdiv('defaultPad', $DSP->qdiv('success', $LANG->line('email_sent_message'))).$DSP->qdiv('defaultPad', $DSP->qdiv('', $LANG->line('total_emails_sent').NBS.NBS.$total_sent)),  $LANG->line('email_sent'));
        
        	// We're done
        
        	return;
        }
               
		
        // ----------------------------------------
        //  Start Batch-Mode
        // ----------------------------------------
        
		//  Store email cache
		
		$cache_data['recipient_array'] = serialize($emails);

		$id = $this->save_cache_data($cache_data, $groups);
			
        // Turn on "refresh"
        // By putting the URL in the $DSP->refresh variable we'll tell the
        // system to write a <meta> refresh header, starting the batch process
        
        $DSP->refresh = BASE.AMP.'C=communicate'.AMP.'M=batch_send'.AMP.'id='.$id;
        $DSP->ref_rate = 7;
        
        // Kill the bread-crumb links, just to keep them away from the user
        
        $DSP->show_crumb = FALSE;
    
        // Write the initial message, telling the user the batch processor is about to start
    
        $r  = $DSP->heading(BR.$LANG->line('sending_email'));
        $r .= $DSP->qdiv('itemWrapper', $DSP->heading($LANG->line('batchmode_ready_to_begin'), 5));
        $r .= $DSP->qdiv('alert', $LANG->line('batchmode_warning'));
    
    
        $DSP->body = $r;
    }
    // END    
    
    
    

	// ----------------------------------------
	//   Add unsubscribe link to emails
	// ----------------------------------------

	function add_unsubscribe_link($email, $action_id, $code)
	{
		global $PREFS, $LANG, $FNS;
		
		$msg  = "\n\n\n";
		$msg .= $LANG->line('mailinglist_unsubscribe');
		$msg .= "\n";

        $qs = ($PREFS->ini('force_query_string') == 'y') ? '' : '?';        
		
		$msg .= $FNS->fetch_site_index(0, 0).$qs.'ACT='.$action_id.'&id='.$code;
	
		return $msg;
	}
	// END
    
    
    
    
    // ------------------------------------
    //  Convert recipient string to array
    // ------------------------------------

    function convert_recipients($recipients = '')
    {
    	$emails = array();
    
    	$ct = 0;
    	
			if ($recipients != '')
			{        
				if (ereg(',$', $recipients))
					$recipients = substr($recipients, 0, -1);
				
				if (ereg('^,', $recipients))
					$recipients = substr($recipients, 1);	
		
				$recipients = str_replace(",,", ",", $recipients);
						
				if (ereg(',', $recipients))
				{					
					$x = explode(',', $recipients);
										
					for ($i = 0; $i < count($x); $i ++)
						$emails['r'.$ct] = trim($x[$i]);
				}
				else
				{
					$emails['r'.$ct] = $recipients;
				}
			}
    
    	return $emails;
    }
    // END
    


    // -----------------------------
    //  Count total recipients
    // -----------------------------   
    
	function fetch_total($to, $cc, $bcc)
    {
    	$total = 0;
    
    	if ($to != '')
    	{
    		$total += count(explode(",", $to));	
    	}	
    	if ($cc != '')
    	{
    		$total += count(explode(",", $cc));	
    	}	
    	if ($bcc != '')
    	{
    		$total += count(explode(",", $bcc));	
    	}	
    
   		return $total;
    }
    // END
    
    
    // -----------------------------
    //  Save cache data
    // -----------------------------   
    
    function save_cache_data($cache_data, $groups = '')
    {
    	global $DB;
    	
    	// We don't cache emails sent by "user blogs"
    	
    	if (USER_BLOG != FALSE)
    	{
    		return;
    	}

        $sql = $DB->insert_string('exp_email_cache', $cache_data);

        $DB->query($sql); 
        
        $cache_id = $DB->insert_id;
        
        if (is_array($groups))
        {
			if (count($groups) > 0)
			{			
				foreach ($groups as $id)
				{
					$DB->query("INSERT INTO exp_email_cache_mg (cache_id, group_id) VALUES ('$cache_id', '$id')");
				}
			}
   		}
   		
   		return $cache_id;
	}    
	// END 
    
    
    // -----------------------------
    //  Send email in batch mode
    // -----------------------------   
    
    function batch_send()
    {  
        global $IN, $DSP, $FNS, $LANG, $DB, $SESS, $PREFS;
                
        $DSP->title = $LANG->line('communicate');
        $DSP->show_crumb = FALSE;
        
                
        if ( ! $id = $IN->GBL('id'))
        {
            return $DSP->error_message($LANG->line('problem_with_id'), 0);
        }
        
        // -----------------------------
        //  Fetch cached email
        // -----------------------------   
        
        $query = $DB->query("SELECT * FROM exp_email_cache WHERE cache_id = '$id'");
        
        if ($query->num_rows == 0)
        {
            return $DSP->error_message($LANG->line('cache_data_missing'), 0);
        }
        
        // Turn the result fields into variables
        
        foreach ($query->row as $key => $val)
        {
            if ($key == 'recipient_array')
            {
                $$key = unserialize($val);
            }
            else
            {
                $$key = $val;
            }
        }
        
        // -------------------------------------------------
        //  Determine which emails correspond to this batch
        // -------------------------------------------------
        
        $finished = FALSE;
        
        $total = count($recipient_array);
        
        $batch = $PREFS->ini('email_batch_size');
               
        if ($batch > $total)
        {
            $batch = $total;
            
            $finished = TRUE;
        }
        

        // ---------------------
        //  Send emails
        // ---------------------
        
		$action_id  = $FNS->fetch_action_id('Mailinglist', 'unsubscribe');

        require PATH_CORE.'core.email'.EXT;
        
        $email = new EEmail;    

        $email->wordwrap  = ($wordwrap == 'y') ? TRUE : FALSE;
        $email->mailtype  = $mailtype;
        $email->priority  = $priority;
        
        $i = 0;
        
        foreach ($recipient_array as $key => $val)
        {
        	if ($i == $batch)
        	{
        		break;
        	}
        
            $email->from($from_email, $from_name);	
            $email->to($val); 
            $email->subject($subject);	
            
			// m = member id
			// l = mailing list
			// r = general recipient
			
			$msg = $message;
			
			if (substr($key, 0, 1) == 'l')
			{
				$msg .= $this->add_unsubscribe_link($val, $action_id, substr($key, 1));
			}
			
			$email->message($msg);	            	
            
            if ( ! $email->Send())
            {
                return $DSP->error_message($LANG->line('error_sending_email'), 0);
            }
            
            $email->initialize();
            
            $i++;
        }
        
		$n = $total_sent + $i;
           
        // ------------------------
        //  More batches to do...
        // ------------------------
                
        if ($finished == FALSE)
        {

        	reset($recipient_array);
        
        	$recipient_array = serialize(array_slice($recipient_array, $i));
        	
            $DB->query("UPDATE exp_email_cache SET total_sent = '$n', recipient_array = '$recipient_array' WHERE cache_id = '$id'");
                    
            $DSP->refresh = BASE.AMP.'C=communicate'.AMP.'M=batch_send'.AMP.'id='.$id;
            $DSP->ref_rate = 4;

            $r  = $DSP->heading(BR.$LANG->line('sending_email'));
            
            $stats = str_replace("%x", ($total_sent + 1), $LANG->line('currently_sending_batch'));
            $stats = str_replace("%y", $n, $stats);
            
            $r .= $DSP->qdiv('itemWrapper', $DSP->heading($stats, 5));
            
        	$remaining = $total - $batch;
            
            $r .= $DSP->qdiv('itemWrapper', $DSP->heading($LANG->line('emails_remaining').NBS.NBS.$remaining, 5));
                        
            $r .= $DSP->qdiv('alert', $LANG->line('batchmode_warning'));
            
        }
        
        // ------------------------
        //  Finished!
        // ------------------------
        
        else
        {
            $DB->query("UPDATE exp_email_cache SET total_sent = '$n', recipient_array = '' WHERE cache_id = '$id'");
        
            $r  = $DSP->heading(BR.$LANG->line('email_sent'));
            
            $r .= $DSP->qdiv('success', $LANG->line('all_email_sent_message'));
            
            $total = $total_sent + $batch;
            
            $r .= $DSP->qdiv('itemWrapper', $DSP->heading($LANG->line('total_emails_sent').NBS.NBS.$total, 5));
        }
            
        $DSP->body = $r;
    }
    // END
    
    
    
    
	// ------------------------
	//  View Email Cache
	// ------------------------
    
	function view_email_cache($message = '')
	{  
    	global $IN, $DB, $LANG, $DSP, $LOC;
    
		if ( ! $DSP->allowed_group('can_send_cached_email'))
		{     
			return $DSP->no_access_message($LANG->line('not_allowed_to_email_mailinglist'));
		}
    
    
		// -----------------------------
    	//  Define base variables
		// -----------------------------   		
    	
		$i = 0;

		$s1 = 'tableCellOne';
		$s2 = 'tableCellTwo';
		
		$row_limit 	= 50;
		$paginate	= '';
		$row_count	= 0;
		
        $DSP->title = $LANG->line('previous_email');
        $DSP->crumb = $LANG->line('previous_email');
		
		$DSP->body  = $DSP->heading($LANG->line('previous_email'));
		
        if ($message != '')
        {
			$DSP->body .= $DSP->qdiv('success', $message);
        }
		
		
		// -----------------------------
    	//  Run Query
		// -----------------------------   		
		
		$sql = "SELECT * FROM exp_email_cache ORDER BY cache_id desc";
		
		$query = $DB->query($sql);
		
		if ($query->num_rows == 0)
		{
			if ($message == '')
				$DSP->body	.=	$DSP->qdiv('itemWrapper', $DSP->qdiv('highlight', $LANG->line('no_cached_email')));             
			
			return;
		}		
		
		// -----------------------------
    	//  Do we need pagination?
		// -----------------------------   		
		
		if ($query->num_rows > $row_limit)
		{ 
			$row_count = ( ! $IN->GBL('row')) ? 0 : $IN->GBL('row');
						
			$url = 'C=communicate'.AMP.'M=view_cache';
						
			$paginate = $DSP->pager(  $url,
									  $query->num_rows, 
									  $row_limit,
									  $row_count,
									  'row'
									);
			 
			$sql .= " LIMIT ".$row_count.", ".$row_limit;
			
			$query = $DB->query($sql);    
		}
    			
		
		
        $DSP->body .= $DSP->toggle();

        $DSP->body .= $DSP->form('C=communicate'.AMP.'M=delete_conf', 'target');

        $DSP->body .= $DSP->table('tableBorder', '0', '0', '100%').
					  $DSP->tr().
					  $DSP->td('tablePad'); 

        $DSP->body .= $DSP->table('', '0', '0', '100%').
					  $DSP->tr().
					  $DSP->table_qcell('tableHeadingBold',
										array(
												NBS,
												$LANG->line('email_title'), 
												$LANG->line('email_date'),
												$LANG->line('total_recipients'),
												$LANG->line('resend'),
												$DSP->input_checkbox('toggleflag', '', '', "onclick=\"toggle(this);\"").NBS.$LANG->line('delete').NBS.NBS
											  
											  )
											).
              $DSP->tr_c();
              
		// -----------------------------
    	//  Table Rows
		// ----------------------------- 
		
		$row_count++;  		
              
		foreach ($query->result as $row)
		{			
			$DSP->body	.=	$DSP->table_qrow( ($i++ % 2) ? $s1 : $s2, 
									array(
											$row_count,
													
                  							$DSP->anchorpop(BASE.AMP.'C=communicate'.AMP.'M=view_email'.AMP.'id='.$row['cache_id'].AMP.'Z=1', '<b>'.$row['subject'].'</b>', '600', '580'),

											$LOC->set_human_time($row['cache_date']),
											
											$row['total_sent'],
																						
											$DSP->qspan('defaultBold', $DSP->anchor(BASE.AMP.'C=communicate'.AMP.'id='.$row['cache_id'], $LANG->line('resend'))),
																						
											$DSP->input_checkbox('toggle[]', $row['cache_id'])

										  )
									);
			$row_count++;  		
		}	
        
        $DSP->body .= $DSP->table_c(); 

        $DSP->body .= $DSP->td_c().  
					  $DSP->tr_c().
					  $DSP->table_c();  
					  

    	if ($paginate != '')
    	{
    		$DSP->body .= $DSP->qdiv('itemWrapper', $paginate);
    	}
    
		$DSP->body .= $DSP->qdiv('itemWrapper', BR.$DSP->input_submit($LANG->line('delete')));             
        
        $DSP->body .= $DSP->form_c();
    }
    // END
    

    
	// ------------------------
	//  View a specific email
	// ------------------------
    
	function view_email()
	{  
    	global $IN, $DB, $LANG, $DSP, $LOC;
    
		if ( ! $DSP->allowed_group('can_send_cached_email'))
		{     
			return $DSP->no_access_message($LANG->line('not_allowed_to_email_mailinglist'));
		}
		
		$id = $IN->GBL('id');
    
		
		// -----------------------------
    	//  Run Query
		// -----------------------------   		
				
		$query = $DB->query("SELECT mailtype, subject, message FROM exp_email_cache WHERE cache_id = '$id' ");
		
		if ($query->num_rows == 0)
		{
			$DSP->body .= $DSP->qdiv('itemWrapper', $DSP->qdiv('highlight', $LANG->line('no_cached_email')));             
			
			return;
		}
	
		// -----------------------------
    	//  Clean up message
		// -----------------------------   		
		
		// If the message was submitted in HTML format
		// we'll remove everything except the body
		
		$message = $query->row['message'];
		
		if ($query->row['mailtype'] == 'html')
		{
        	$message = (preg_match("/<body.*?".">(.*)<\/body>/is", $message, $match)) ? $match['1'] : $message;
		}			
    			
		// -----------------------------
    	//  Render output
		// -----------------------------   		
				
		$DSP->body .= $DSP->heading(BR.$query->row['subject']);
		
		// ----------------------------------------
		//  Instantiate Typography class
		// ----------------------------------------        
	  
		if ( ! class_exists('Typography'))
		{
			require PATH_CORE.'core.typography'.EXT;
		}
            
		$TYPE = new Typography;
		
		$DSP->body .= $TYPE->parse_type( $message, 
								 array(
											'text_format'   => 'xhtml',
											'html_format'   => 'all',
											'auto_links'    => 'y',
											'allow_img_url' => 'y'
									   )
								);
    }
    // END
    
    

    // -------------------------------------------
    //   Delete Confirm
    // -------------------------------------------    

    function delete_confirm()
    { 
        global $IN, $DSP, $LANG;
        
		if ( ! $DSP->allowed_group('can_send_cached_email'))
		{     
			return $DSP->no_access_message($LANG->line('not_allowed_to_email_mailinglist'));
		}
        
        if ( ! $IN->GBL('toggle', 'POST'))
        {
            return $this->view_email_cache();
        }
        
        $DSP->title = $LANG->line('delete_emails');
		$DSP->crumb  = $DSP->anchor(BASE.AMP.'C=communicate'.AMP.'M=view_cache', $LANG->line('view_email_cache'));
		$DSP->crumb .= $DSP->crumb_item($LANG->line('delete_emails'));

        $DSP->body	.=	$DSP->form('C=communicate'.AMP.'M=delete');
        
        $i = 0;
        
        foreach ($_POST as $key => $val)
        {        
            if (strstr($key, 'toggle') AND ! is_array($val))
            {
                $DSP->body	.=	$DSP->input_hidden('delete[]', $val);
                
                $i++;
            }        
        }
        
		$DSP->body .= $DSP->heading($LANG->line('delete_confirm'));
		$DSP->body .= $DSP->qdiv('defaultBold', $LANG->line('delete_question'));
		$DSP->body .= $DSP->qdiv('alert', BR.$LANG->line('action_can_not_be_undone'));
		$DSP->body .= $DSP->qdiv('', BR.$DSP->input_submit($LANG->line('delete')));
		$DSP->body .= $DSP->qdiv('alert',$DSP->div_c());
		$DSP->body .= $DSP->form_c();
    }
    // END   
    
    
    
    // -------------------------------------------
    //   Delete Emails
    // -------------------------------------------    

    function delete_emails()
    { 
        global $IN, $DSP, $LANG, $DB;
        
		if ( ! $DSP->allowed_group('can_send_cached_email'))
		{     
			return $DSP->no_access_message($LANG->line('not_allowed_to_email_mailinglist'));
		}
        
        if ( ! $IN->GBL('delete', 'POST'))
        {
            return $this->view_email_cache();
        }
        

        $ids = array();
                
        foreach ($_POST as $key => $val)
        {        
            if (strstr($key, 'delete') AND ! is_array($val))
            {
                $ids[] = "cache_id = '".$val."'";
            }        
        }
        
        $IDS = implode(" OR ", $ids);
        
        $DB->query("DELETE FROM exp_email_cache WHERE ".$IDS);
        $DB->query("DELETE FROM exp_email_cache_mg WHERE ".$IDS);
    
        return $this->view_email_cache($LANG->line('email_deleted'));
    }
    // END 
    
       
}
// END CLASS
?>