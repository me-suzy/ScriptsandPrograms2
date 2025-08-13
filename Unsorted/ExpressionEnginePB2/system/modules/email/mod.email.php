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
 File: mod.email.php
-----------------------------------------------------
 Purpose: Email class
-----------------------------------------------------
 Last Updated:  2004-03-16 14:29:00 
=====================================================
*/

if ( ! defined('EXT'))
{
    exit('Invalid file request');
}


class Email {

	var $email_time_interval = '45'; // In seconds
	var $email_max_emails = '20'; // Total recipients, not emails

    // ----------------------------------------
    //  Contact Form
    // ----------------------------------------

	function contact_form()
	{
		global $IN, $TMPL, $REGX, $FNS, $PREFS, $SESS, $LOC, $DB;
		
        $tagdata =& $TMPL->tagdata;     
        	
        // ----------------------------------------
        //   Recipient Email Checking
        // ----------------------------------------
        	
        $recipients = ( ! $TMPL->fetch_param('recipients'))  ? '' : $TMPL->fetch_param('recipients');
		$user_recipients = ( ! $TMPL->fetch_param('user_recipients'))  ? 'false' : $TMPL->fetch_param('user_recipients');
		$charset = ( ! $TMPL->fetch_param('charset'))  ? '' : $TMPL->fetch_param('charset');
		
		// No email left behind act
		if ($user_recipients == 'false' && $recipients == '')
		{
			$recipients = $PREFS->ini('webmaster_email');
		}
        
        // Clean and check recipient emails, if any
        if ($recipients != '')
        {
        	// Remove white space and replace with comma
        	$recipients = preg_replace("/\s*(\S+)\s*/", "\\1,", $recipients);
        	
        	// Remove any existing doubles
        	$recipients = str_replace(",,", ",", $recipients);
        	
        	// Remove any comma at the end
        	if (substr($recipients, -1) == ",")
			{
				$recipients = substr($recipients, 0, -1);
			}
		
			// Break into an array via commas and remove duplicates
			$emails = preg_split('/[,]/', $recipients);
			$emails = array_unique($emails);
			
			$approved_emails = array();
			foreach ($emails as $email)
			{
			     if ($REGX->valid_email($email))
			     {
			          $approved_emails[] = $email;
			     }
			}
		
			// Put together into string again
			$recipients = implode(',',$approved_emails);
		}		
		
		// ----------------------------------------
        //   Parse conditional pairs
        // ----------------------------------------

		foreach ($TMPL->var_cond as $val)
		{  
			// ----------------------------------------
			//   {if LOGGED_IN}
			// ----------------------------------------
		
			if ($val['0'] == 'if LOGGED_IN')
			{
				$rep = ($SESS->userdata['member_id'] == 0) ? '' : $val['2'];
				
				$tagdata =& str_replace($val['1'], $rep, $tagdata); 
			}
			
			// ----------------------------------------
			//   {if NOT_LOGGED_IN}
			// ----------------------------------------

			if ($val['0'] == 'if NOT_LOGGED_IN')
			{
				$rep = ($SESS->userdata['member_id'] != 0) ? '' : $val['2'];
				
				$tagdata =& str_replace($val['1'], $rep, $tagdata);                 
			}
		}
		// END CONDITIONALS
            
            
		// ----------------------------------------
		//   Parse "single" variables
		// ----------------------------------------

		foreach ($TMPL->var_single as $key => $val)
		{
			// ----------------------------------------
			//  parse {member_name}
			// ----------------------------------------
            
   			if ($key == 'member_name')
   			{
   				$name = ($SESS->userdata['screen_name'] != '') ? $SESS->userdata['screen_name'] : $SESS->userdata['username'];
   				$tagdata =& $TMPL->swap_var_single($key, $REGX->form_prep($name), $tagdata);
   			}
   			
   			// ----------------------------------------
   			//  parse {member_email}
   			// ----------------------------------------
   			
   			if ($key == 'member_email')
   			{
   				$email = ($SESS->userdata['email'] == '') ? '' : $SESS->userdata['email'];
   				$tagdata =& $TMPL->swap_var_single($key, $REGX->form_prep($email), $tagdata);
   			}
   			
   			// ----------------------------------------
   			//  parse {current_time}
   			// ----------------------------------------
   			
   			if (ereg("^current_time", $key))
   			{
   				$now = $LOC->set_localized_time();
   				$tagdata =& $TMPL->swap_var_single($key, $LOC->decode_date($val,$now), $tagdata);
   			}
   			
   			if (($key == 'author_email' || $key == 'author_name') && !isset($$key))
   			{
   				if ($IN->QSTR != '')
   				{
			        $entry_id = &$IN->QSTR;
			        
   					$sql = "SELECT exp_members.username, exp_members.email, exp_members.screen_name
                      		FROM exp_weblog_titles, exp_members
                      		WHERE exp_members.member_id = exp_weblog_titles.author_id  ";
                      
						if ( ! is_numeric($entry_id))
						{
							$sql .= " AND exp_weblog_titles.url_title = '".$entry_id."' ";
						}
						else
						{
							$sql .= " AND exp_weblog_titles.entry_id = '$entry_id'";
						}
						
						$query = $DB->query($sql);
						
						if ($query->num_rows == 0)
						{ 
							$author_name = '';
						}
						else
						{
							$author_name = ($query->row['screen_name'] != '') ? $query->row['screen_name'] : $query->row['username'];
						}
						
						$author_email = ($query->num_rows == 0) ? '' : $query->row['email'];
				}
				else
				{
					$author_email = '';
					$author_name = '';
				}
				
				// Do them both now and save ourselves a query
				$tagdata =& $TMPL->swap_var_single('author_email', $author_email, $tagdata);
   				$tagdata =& $TMPL->swap_var_single('author_name', $author_name, $tagdata);				
   			}		
   			
   			// Clear out any unused variables.
   			$tagdata =& $TMPL->swap_var_single($key, '', $tagdata);
   		}
   		
   		// ----------------------------------------
   		//  Create form
   		// ----------------------------------------
 
   		$hidden_fields = array(
   								'ACT'      			=> $FNS->fetch_action_id('Email', 'send_email'),
   								'RET'      			=> ( ! $TMPL->fetch_param('return'))  ? $FNS->fetch_current_uri() : $TMPL->fetch_param('return'),
   								'URI'      			=> ($IN->URI == '') ? 'index' : $IN->URI,
   								'recipients' 		=> $recipients,
   								'user_recipients' 	=> ($user_recipients == 'true') ? 'y' : 'n',
   								'charset'			=> $charset
								);            
                             
		$res  = $FNS->form_declaration($hidden_fields, '', 'contact_form');
		$res .= stripslashes($tagdata);
		$res .= "</form>";
		return $res;
	}
    // END
    
    
    // ----------------------------------------
    //  Tell A Friend Form
    // ----------------------------------------
    // {exp:email:tell_a_friend charset="utf-8" allow_html='n'}
    // {exp:email:tell_a_friend charset="utf-8" allow_html='<p>,<a>' recipients='sales@mydomain.com'}
	// {email}, {name}, {current_time format="%Y %d %m"}
	
	function tell_a_friend()
	{
		global $IN, $TMPL, $REGX, $FNS, $PREFS, $SESS, $LOC, $DB;
		
		if ($IN->QSTR == '')
        {
            return false;
        }
                	
        // ----------------------------------------
        //   Recipient Email Checking
        // ----------------------------------------
		
		$user_recipients = true;  // By default
        	
        $recipients	= ( ! $TMPL->fetch_param('recipients'))	? ''  : $TMPL->fetch_param('recipients');
		$charset	= ( ! $TMPL->fetch_param('charset'))	? ''  : $TMPL->fetch_param('charset');
		$allow_html	= ( ! $TMPL->fetch_param('allow_html'))	? 'n' : $TMPL->fetch_param('allow_html');
		
        
        // Clean and check recipient emails, if any
        if ($recipients != '')
        {
        	// Remove white space and replace with comma
        	$recipients = preg_replace("/\s*(\S+)\s*/", "\\1,", $recipients);
        	
        	// Remove any existing doubles
        	$recipients = str_replace(",,", ",", $recipients);
        	
        	// Remove any comma at the end
        	if (substr($recipients, -1) == ",")
			{
				$recipients = substr($recipients, 0, -1);
			}
		
			// Break into an array via commas and remove duplicates
			$emails = preg_split('/[,]/', $recipients);
			$emails = array_unique($emails);
			
			$approved_emails = array();
			foreach ($emails as $email)
			{
			     if ($REGX->valid_email($email))
			     {
			          $approved_emails[] = $email;
			     }
			}
		
			// Put together into string again
			$recipients = implode(',',$approved_emails);
		}	

        // ----------------------------------------
        //  Fetch the weblog entry
        // ----------------------------------------
		
		if ( ! class_exists('Weblog'))
        {
        	require PATH_MOD.'/weblog/mod.weblog'.EXT;
        }

        $weblog = new Weblog;        
        
        $weblog->fetch_custom_weblog_fields();
        $weblog->fetch_custom_member_fields();
        $weblog->build_sql_query();
        $weblog->query = $DB->query($weblog->sql);
        
        if ($weblog->query->num_rows == 0)
        {
            return false;
        }     
        
        if ( ! class_exists('Typography'))
        {
            require PATH_CORE.'core.typography'.EXT;
        }
        
        $weblog->TYPE = new Typography;
        
        $TMPL->tagparams['rdf'] = 'off'; // Turn off RDF code
        
        $weblog->fetch_categories();
        $weblog->parse_weblog_entries();
        $tagdata =& $weblog->return_data;
        
   		// ----------------------------------------
   		// Parse tell-a-friend variables
   		// ----------------------------------------
   		
   		// {member_name}
                
		$tagdata =& $TMPL->swap_var_single('member_name', $SESS->userdata['screen_name'], $tagdata);
		
   		// {member_email}
           
		$tagdata =& $TMPL->swap_var_single('member_email', $SESS->userdata['email'], $tagdata);
   		           		
   		// ----------------------------------------
   		// A little work on the form field's values
   		// ----------------------------------------
   		
   		// Match values in input fields
   		preg_match_all("/<input(.*?)value=\"(.*?)\"/", $tagdata, $matches);
   		if(sizeof($matches) > 0 && $allow_html != 'y')
   		{
   		     foreach($matches['2'] as $value)
   		     {
   		     	if ($allow_html == 'n')
   		     	{
   		     		$new = strip_tags($value);
   		     	}
   		     	else
   		     	{
   		     	    $new = strip_tags($value,$allow_html);
   		     	}
   		     	
   		     	$tagdata = str_replace($value,$new, $tagdata);
   		     }
   		}
   		
   		// Remove line breaks
   		$LB = 'snookums9loves4wookie';
   		$tagdata = preg_replace("/(\r\n)|(\r)|(\n)/", $LB, $tagdata);
   		
   		// Temporary switch back to slashes
   		$tagdata = str_replace($TMPL->slash,'/',$tagdata);
   		
   		// Match textarea content
   		preg_match_all("/<textarea(.*?)>(.*?)<\/textarea>/", $tagdata, $matches);
   		if (sizeof($matches) > 0 && $allow_html != 'y')
   		{
   			foreach($matches['2'] as $value)
   			{
   			    if ($allow_html == 'n')
   		     	{
   		     		$new = strip_tags($value);
   		     	}
   		     	else
   		     	{
   		     	    $new = strip_tags($value, $allow_html);
   		     	}
   		     	
   		     	$tagdata = str_replace($value, $new, $tagdata);   			     
   			}
   		}
   		
   		// Change it all back, yo.
   		$tagdata = str_replace('/',$TMPL->slash, $tagdata);
   		$tagdata = str_replace($LB, "\n", $tagdata);
   		
   		
   		// ----------------------------------------
   		//  Create form
   		// ----------------------------------------
   		   		
   		$hidden_fields = array(
   								'ACT'      			=> $FNS->fetch_action_id('Email', 'send_email'),
   								'RET'      			=> ( ! $TMPL->fetch_param('return'))  ? $FNS->fetch_current_uri() : $TMPL->fetch_param('return'),
   								'URI'      			=> ($IN->URI == '') ? 'index' : $IN->URI,
   								'recipients' 		=> $recipients,
   								'user_recipients' 	=> ($user_recipients == 'true') ? 'y' : 'n',
   								'charset'			=> $charset
								);            
                             
		$res  = $FNS->form_declaration($hidden_fields, '', 'contact_form');
		$res .= stripslashes($tagdata);
		$res .= "</form>";
		return $res;
	}
    // END



    // ----------------------------------------
    //  Send Email
    // ----------------------------------------

    function send_email()
    {
        global $IN, $SESS, $PREFS, $DB, $FNS, $OUT, $LANG, $REGX, $LOC;
    
    	  	
    	// ----------------------------------------
        // Check and Set
        // ----------------------------------------
    
        $default = array('subject', 'message', 'from', 'user_recipients', 'to', 'recipients', 'name', 'required');
        
        foreach ($default as $val)
        {
			if ( ! isset($_POST[$val]))
			{
				$_POST[$val] = '';
			}
			else
			{
			     $_POST[$val] = trim(stripslashes($_POST[$val]));
			}
        }
        
        // ----------------------------------------
        // Clean incoming
        // ----------------------------------------
        
        $clean = array('subject', 'from', 'user_recipients', 'to', 'recipients', 'name');
        
        foreach ($default as $val)
        {
			$_POST[$val] = strip_tags($_POST[$val]);
        }
        
        // ----------------------------------------
        // Fetch the email module language pack
        // ----------------------------------------
        
        $LANG->fetch_language_file('email');
        
        
        // ----------------------------------------
        // Basic Security Check
        // ----------------------------------------
    	
    	if ($SESS->sdata['ip_address'] == '' || $SESS->sdata['user_agent'] == '')
    	{        	
            return $OUT->show_user_error('general', array($LANG->line('em_unauthorized_request')));    		
    	}
        
        
        // ----------------------------------------
        // ERROR Checking
        // ----------------------------------------
                
        // If the message is empty, bounce them back
        
        if ($_POST['message'] == '')
        {
            $FNS->redirect($_POST['RET']);
        }
        
        // If the from field is empty, error
        if ($_POST['from'] == '' || !$REGX->valid_email($_POST['from']))
        {        	
            return $OUT->show_user_error('general', array($LANG->line('em_sender_required')));
        }
        
        // If no recipients, bounce them back
        
        if ($_POST['recipients'] == '' && $_POST['to'] == '')
        {            
            return $OUT->show_user_error('general', array($LANG->line('em_no_valid_recipients')));
        }
        
                
        // ----------------------------------------
        // Is the user banned?
        // ----------------------------------------
                
        if ($SESS->userdata['is_banned'] == TRUE)
        {            
            return $OUT->show_user_error('general', array($LANG->line('not_authorized')));
        }
        
        
        // ----------------------------------------
        //  Check Form Hash
        // ----------------------------------------
        
        if ($PREFS->ini('secure_forms') == 'y')
        {
            $query = $DB->query("SELECT COUNT(*) AS count FROM exp_security_hashes WHERE hash='".$DB->escape_str($_POST['XID'])."' AND date > UNIX_TIMESTAMP()-7200");
        
            if ($query->row['count'] == 0)
            {
                $FNS->redirect($_POST['RET']);
            }
        }        
        
        // ----------------------------
        //  Check Tracking Class
        // ----------------------------
		
		$day_ago = $LOC->now - 60*60*24;
		$query = $DB->query("DELETE FROM exp_email_tracker WHERE email_date < '{$day_ago}'");
		$query = $DB->query("SELECT * 
							FROM exp_email_tracker 
							WHERE sender_username = '".$DB->escape_str($SESS->userdata['username'])."'
							OR sender_ip = '".$IN->IP."'
							ORDER BY email_date DESC");
		
		if ($query->num_rows > 0)
		{
			// Max Emails - Quick check
			if ($query->num_rows >= $this->email_max_emails)
			{
				return $OUT->show_user_error('general', array($LANG->line('em_limit_exceeded')));  
			}
			
			// Max Emails - Indepth check
			$total_sent = 0;
			foreach($query->result as $row)
			{
				$total_sent = $total_sent + $row['number_recipients'];
			}
			
			if ($total_sent >= $this->email_max_emails)
			{
				return $OUT->show_user_error('general', array($LANG->line('em_limit_exceeded')));
			}
			
			// Interval check
			if ($query->row['email_date'] > ($LOC->now - $this->email_time_interval))
			{
				$error[] = str_replace("%s", $this->email_time_interval, $LANG->line('em_interval_warning'));
				return $OUT->show_user_error('general', $error);
			}
		}
        
        
        // ----------------------------------------
        //  Review Recipients
        // ----------------------------------------
        
        $recipients = ($_POST['user_recipients'] == 'y') ? $_POST['recipients'].','.$_POST['to'] : $_POST['recipients'];
        
		// Remove white space and replace with comma
		$recipients = preg_replace("/\s*(\S+)\s*/", "\\1,", $recipients);
        	
        // Remove any existing doubles
        $recipients = str_replace(",,", ",", $recipients);
        	
        // Remove any comma at the end
        if (substr($recipients, -1) == ",")
		{
			$recipients = substr($recipients, 0, -1);
		}
		
		// Break into an array via commas and remove duplicates
		$emails = preg_split('/[,]/', $recipients);
		$emails = array_unique($emails);
			
		// Emails to send email to...
		
		$error = array();
		$approved_emails = array();
		
		foreach ($emails as $email)
		{
			 if (trim($email) == '') continue;
			 			
		     if ($REGX->valid_email($email))
		     {
		          if (!$SESS->ban_check('email', $email))
		          {
		               $approved_emails[] = $email;
		          }
		          else
		          {
		               $error['ban_recp'] = $LANG->line('em_banned_recipient');
		          }
		     }
		     else
		     {
		     	$error['bad_recp'] = $LANG->line('em_invalid_recipient');
		     }
		}

		unset($email);
		
		// If we have no valid emails to send, back they go.
		if (sizeof($approved_emails) == 0)
        {
            $error[] = $LANG->line('em_no_valid_recipients');
        }
        
		// -------------------------------------
		//  Is from email banned?
		// -------------------------------------
		
		if ($SESS->ban_check('email', $_POST['from']))
		{
			$error[] = $LANG->line('em_banned_from_email');
		}	
		
        // ----------------------------------------
        //  Do we have errors to display?
        // ----------------------------------------
                
        if (count($error) > 0)
        {
           return $OUT->show_user_error('submission', $error);
        }
        
        // ----------------------------------------
        //  Censored Word Checking
        // ----------------------------------------
        
        if ( ! class_exists('Typography'))
        {
            require PATH_CORE.'core.typography'.EXT;
        }
        
        $TYPE = new Typography;
        
        $subject = $REGX->entities_to_ascii($_POST['subject']);
        $subject = $TYPE->filter_censored_words($subject);
        
        $message = ($_POST['required'] != '') ? $_POST['required']."\n".$_POST['message'] : $_POST['message'];
        
        $message = $REGX->entities_to_ascii($message);
        $message = $TYPE->filter_censored_words($message);
        
        // ----------------------------
        //  Send email
        // ----------------------------
        
        if ( ! class_exists('EEmail'))
        {
        	require PATH_CORE.'core.email'.EXT;
        }
        
        $email = new EEmail;
        $email->wordwrap = true;
        $email->mailtype = 'plain';
		$email->priority = '3';
		
		if (isset($_POST['charset']) && $_POST['charset'] != '')
		{
			$email->charset = $_POST['charset'];
		}
		
        foreach ($approved_emails as $val)
        {
        	$email->to($val);
        	$email->from($_POST['from'],$_POST['name']);
        	$email->subject($subject);
       		$email->message($message);
        	$email->Send();
        	$email->initialize();
        }
        
        
        // ----------------------------
        //  Store in tracking class
        // ----------------------------
        
        $data = array(	'email_id'			=> '', 
        				'email_date'		=> $LOC->now, 
        				'sender_ip'			=> $IN->IP,
        				'sender_email'		=> $_POST['from'],
        				'sender_username'	=> $SESS->userdata['username'],
        				'number_recipients'	=> sizeof($approved_emails)
					);
         
        $DB->query($DB->insert_string('exp_email_tracker', $data));
        
       
        // -------------------------------------------
        //  Thank you message
        // -------------------------------------------
        
		$site_name = ($PREFS->ini('site_name') == '') ? $LANG->line('back') : $PREFS->ini('site_name');
                
        $data = array(	'title' 	=> $LANG->line('email_module_name'),
        				'heading'	=> $LANG->line('thank_you'),
        				'content'	=> $LANG->line('em_email_sent'),
        				'link'		=> array($_POST['RET'], $site_name)
        			 );
				
		$OUT->show_message($data);
    }
    // END
    
    
    /*
    // -------------------------------
    // EXAMPLES:  Contact Form
    // -------------------------------
    {exp:email:contact_form user_recipients='false' recipients="mymail@mydomain.com" charset="utf-8"}
<p>
<label for="from">From: </label><br /><input type="text" id="from" name="from" size="40" maxlength="35" value="{member_email}" />
</p>

<p>
<label for="to">To: </label><br /><input type="text" id="to" name="to" size="40" maxlength="35" />
</p>

<p>
<label for="subject">Subject: </label><br /><input type="text" id="subject" name="subject" size="40" value="Contact Form" />
</p>

<p>
<label for="message">Required: </label><br /><textarea id="required" name="required" rows="2" cols="40" readonly="readonly">
Email Sent at {current_time format="%Y %m %d"}
Priority Level 4
</textarea>
</p>

<p>
<label for="message">Message: </label><br /><textarea id="message" name="message" rows="18" cols="40">
{current_time format="%Y %m %d"}
Love,
{name}
</textarea>
</p>

<p>
<input name="submit" type='submit' value='Submit Form' />
</p>
{/exp:email:contact_form}


	
	// -------------------------------
    // EXAMPLES:  Tell-a-Friend Form
    // -------------------------------

// Link to template holding form like this:  <a href="{title_permalink=weblog/tell_a_friend}">Tell-a-Friend</a>

{exp:email:tell_a_friend charset="utf-8" allow_html='n'}

<p>
<label for="from">Your Email: </label><br /><input type="text" id="from" name="from" size="40" maxlength="35" value="{member_email}" />
</p>

<p>
<label for="name">Your Name: </label><br /><input type="text" id="name" name="name" size="40" maxlength="35" value="{member_name}" />
</p>

<p>
<label for="to">To: </label><br /><input type="text" id="to" name="to" size="40" maxlength="35" />
</p>

<p>
<label for="subject">Subject: </label><br /><input type="text" id="subject" name="subject" size="40" value="Entry by: {author}" />
</p>

<p>
<label for="message">Message: </label><br /><textarea id="message" name="message" rows="18" cols="40">

{summary}
{body}
{permalink}
</textarea>
</p>

<p>
<input name="submit" type='submit' value='Submit Form' />
</p>
{/exp:email:tell_a_friend}

*/



}
// END CLASS
?>