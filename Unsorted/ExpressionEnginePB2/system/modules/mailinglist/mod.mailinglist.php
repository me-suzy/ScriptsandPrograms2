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
 File: mcp.mailinglist.php
-----------------------------------------------------
 Purpose: Basic Mailint List class
=====================================================
*/


if ( ! defined('EXT'))
{
    exit('Invalid file request');
}


class Mailinglist {

	var $email_confirm	= TRUE;  // TRUE/FALSE - whether to send an email confirmation when users sign up
    var $return_data	= '';

    //-------------------------------------
    //  Constructor
    //-------------------------------------

    function Mailinglist()
    {
    }
    // END
    

    // ----------------------------------------
    //  Mailing List Submission Form
    // ----------------------------------------

    function form()
    {
        global $FNS, $TMPL;
        
        $tagdata = $TMPL->tagdata; 
                
        // ----------------------------------------
        //  Create form
        // ----------------------------------------
                                               
        $hidden_fields = array(
                                'ACT'      => $FNS->fetch_action_id('Mailinglist', 'insert_new_email'),
                                'RET'      => $FNS->fetch_current_uri()
                              );            
                             
        $res  = $FNS->form_declaration($hidden_fields, '', 'mailinglist_form');
        
        $res .= $tagdata;
        
        $res .= "</form>"; 
            
        return $res;
    }
    // END



    // ----------------------------------------
    //  Insert new email
    // ----------------------------------------

    function insert_new_email()
    {
        global $IN, $FNS, $OUT, $DB, $PREFS, $SESS, $REGX, $LANG;
        
        
        // ----------------------------------------
        // Fetch the mailinglist language pack
        // ----------------------------------------
        
        $LANG->fetch_language_file('mailinglist');
        
        // ----------------------------------------
        // Error trapping
        // ----------------------------------------
                
        $errors = array();
        
        $email = $IN->GBL('email', 'POST');
        
        $email = trim(strip_tags($email));

		if ($email == '')
		{
			$errors[] = $LANG->line('ml_missing_email');
		}		
        
        if ( ! $REGX->valid_email($email))
        {
			$errors[] = $LANG->line('ml_invalid_email');
        }
        
        if (count($errors) == 0)
        {        
			// ----------------------------------------
			// Is the security hash valid?
			// ----------------------------------------
        
			if ($PREFS->ini('secure_forms') == 'y')
			{
				$query = $DB->query("SELECT COUNT(*) AS count FROM exp_security_hashes WHERE hash='".$DB->escape_str($_POST['XID'])."' AND date > UNIX_TIMESTAMP()-7200");
			
				if ($query->row['count'] == 0)
				{
					$FNS->redirect(stripslashes($_POST['RET']));
					exit;			
				}
			}
        
			// ----------------------------------------
			// Is the email already in the list?
			// ----------------------------------------
        
        	$query = $DB->query("SELECT count(*) AS count FROM exp_mailing_list WHERE email = '".$DB->escape_str($email)."'");
        	
        	if ($query->row['count'] > 0)
        	{
				$errors[] = $LANG->line('ml_email_already_in_list');
        	}
        }
             
		 
		// ----------------------------------------
		//  Are there errors to display?
		// ----------------------------------------
        
        if (count($errors) > 0)
        {
			return $OUT->show_user_error('submission', $errors);
        }
        
        
		// ----------------------------------------
		//  Insert email
		// ----------------------------------------
				
		$code = $FNS->random('alpha', 10);
        
        $return = '';
        
		if ($this->email_confirm == FALSE)
		{
			$DB->query("INSERT INTO exp_mailing_list (user_id, authcode, email) VALUES ('', '".$code."', '".$DB->escape_str($email)."')");			
			
			$content  = $LANG->line('ml_email_accepted');
			
			$return = $_POST['RET'];
		}        
        else
        {        	
			$DB->query("INSERT INTO exp_mailing_list_queue (email, authcode, date) VALUES ('".$DB->escape_str($email)."', '".$code."', '".time()."')");			
			
			$this->send_email_confirmation($email, $code);

			$content  = $LANG->line('ml_email_confirmation_sent')."\n\n";
			$content .= $LANG->line('ml_click_confirmation_link');
        }
        
		// ----------------------------------------
		//  Clear security hash
		// ----------------------------------------
		
		if ($PREFS->ini('secure_forms') == 'y')
		{
			$DB->query("DELETE FROM exp_security_hashes WHERE (hash='".$DB->escape_str($_POST['XID'])."' OR date < UNIX_TIMESTAMP()-7200)");
		}
		
		
		$site_name = ($PREFS->ini('site_name') == '') ? $LANG->line('back') : $PREFS->ini('site_name');
                
        $data = array(	'title' 	=> $LANG->line('ml_mailinglist'),
        				'heading'	=> $LANG->line('thank_you'),
        				'content'	=> $content,
        				'link'		=> array($_POST['RET'], $site_name)
        			 );
				
		$OUT->show_message($data);
    }
    // END



	
	// ----------------------------------------
	//  Send confirmation email
	// ----------------------------------------

	function send_email_confirmation($email, $code)
	{
		global $LANG, $PREFS, $FNS;
        
        $qs = ($PREFS->ini('force_query_string') == 'y') ? '' : '?';        
		$action_id  = $FNS->fetch_action_id('Mailinglist', 'authorize_email');

		$swap = array(
						'activation_url'	=> $FNS->fetch_site_index(0, 0).$qs.'ACT='.$action_id.'&id='.$code,
						'site_name'			=> $PREFS->ini('site_name'),
						'site_url'			=> $PREFS->ini('site_url')
					 );
		
		$template = $FNS->fetch_email_template('mailinglist_activation_instructions');
		
		$email_msg = $FNS->var_replace($swap, $template['data']);
		
		// ----------------------------
		//  Send email
		// ----------------------------

		require PATH_CORE.'core.email'.EXT;
					
		$E = new EEmail;        
		$E->wordwrap = true;
		$E->mailtype = 'plain';
		$E->priority = '3';
		
		$E->from($PREFS->ini('webmaster_email'));	
		$E->to($email); 
		$E->subject($template['title']);	
		$E->message($email_msg);	
		$E->Send();
	}
	// END
	



	// ------------------------------
	//  Authorize email submission
	// ------------------------------

	function authorize_email()
	{
        global $IN, $FNS, $OUT, $DB, $PREFS, $SESS, $REGX, $LANG;
        
        // ----------------------------------------
        // Fetch the mailinglist language pack
        // ----------------------------------------
        
        $LANG->fetch_language_file('mailinglist');
        
        // ----------------------------------------
        // Fetch the name of the site
        // ----------------------------------------
        
		$site_name = ($PREFS->ini('site_name') == '') ? $LANG->line('back') : $PREFS->ini('site_name');
        
                
        // ----------------------------------------
        // No ID?  Tisk tisk...
        // ----------------------------------------
                
        $id  = $IN->GBL('id');        
                
        if ($id == FALSE)
        {
                        
			$data = array(	'title' 	=> $LANG->line('ml_mailinglist'),
							'heading'	=> $LANG->line('error'),
							'content'	=> $LANG->line('invalid_url'),
							'link'		=> array($FNS->fetch_site_index(), $site_name)
						 );
        
			$OUT->show_message($data);
        }
        
        // ----------------------------------------
        //  Fetch email associated with auth-code
        // ----------------------------------------
                        
        $expire = time() - (60*60*48);
        
		$DB->query("DELETE FROM exp_mailing_list_queue WHERE date < '$expire' ");
        
        $query = $DB->query("SELECT email FROM exp_mailing_list_queue WHERE authcode = '".$DB->escape_str($id)."'");
        
		if ($query->num_rows == 0)
		{
			$data = array(	'title' 	=> $LANG->line('ml_mailinglist'),
							'heading'	=> $LANG->line('error'),
							'content'	=> $LANG->line('ml_expired_date'),
							'link'		=> array($FNS->fetch_site_index(), $site_name)
						 );
		        
			echo  $OUT->show_message($data);
			exit;
		}       
        
        // ----------------------------------------
        //  Transfer email to the mailing list
        // ----------------------------------------
        
        $email = $query->row['email'];
        
		$DB->query("INSERT INTO exp_mailing_list (user_id, authcode, email) VALUES ('', '$id', '".$DB->escape_str($email)."')");			
                
		$data = array(	'title' 	=> $LANG->line('ml_mailinglist'),
						'heading'	=> $LANG->line('thank_you'),
						'content'	=> $LANG->line('ml_account_confirmed'),
						'link'		=> array($FNS->fetch_site_index(), $site_name)
					 );
										
		$OUT->show_message($data);
	}
	// END
	
	

	// ------------------------------
	//  Unsubscribe a user
	// ------------------------------

	function unsubscribe()
	{
        global $IN, $FNS, $OUT, $DB, $PREFS, $SESS, $REGX, $LANG;
        
        
        // ----------------------------------------
        // Fetch the mailinglist language pack
        // ----------------------------------------
        
        $LANG->fetch_language_file('mailinglist');
        
        
		$site_name = ($PREFS->ini('site_name') == '') ? $LANG->line('back') : $PREFS->ini('site_name');
                
        // ----------------------------------------
        // No ID?  Tisk tisk...
        // ----------------------------------------
                
        $id  = $IN->GBL('id');        
                
        if ($id == FALSE)
        {			
			$data = array(	'title' 	=> $LANG->line('ml_mailinglist'),
							'heading'	=> $LANG->line('error'),
							'content'	=> $LANG->line('invalid_url'),
							'link'		=> array($FNS->fetch_site_index(), $site_name)
						 );
		        
			$OUT->show_message($data);
        }
        
        // ----------------------------------------
        //  Fetch email associated with auth-code
        // ----------------------------------------
                        
        $expire = time() - (60*60*48);
        
		$DB->query("DELETE FROM exp_mailing_list WHERE authcode = '$id' ");
		
		if ($DB->affected_rows == 0)
		{
			$data = array(	'title' 	=> $LANG->line('ml_mailinglist'),
							'heading'	=> $LANG->line('error'),
							'content'	=> $LANG->line('ml_unsubscribe_failed'),
							'link'		=> array($FNS->fetch_site_index(), $site_name)
						 );
		        
			$OUT->show_message($data);
		}

                
		$data = array(	'title' 	=> $LANG->line('ml_mailinglist'),
						'heading'	=> $LANG->line('thank_you'),
						'content'	=> $LANG->line('ml_unsubscribe'),
						'link'		=> array($FNS->fetch_site_index(), $site_name)
					 );
										
		$OUT->show_message($data);
	}
	// END
	
}
// END CLASS
?>