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
 File: core.validate.php
-----------------------------------------------------
 Purpose: User validation class
=====================================================
*/

if ( ! defined('EXT'))
{
    exit('Invalid file request');
}


class Validate {

	var $member_id			= '';
	var $val_type			= 'update';
	var $fetch_lang 		= TRUE;
	var $require_cpw 		= FALSE;
	var $username			= '';
	var	$cur_username		= '';
	var $screen_name		= '';
	var $cur_screen_name	= '';
	var $password			= '';
	var	$password_confirm	= '';
	var $email				= '';
	var $cur_email			= '';
	var $errors 			= array();
	var $enable_log			= FALSE;
	var $log_msg			= array();
	
	
	// ----------------------------------
	//  Constructor
	// ----------------------------------
	
	function Validate($data = '')
	{	
		global $LANG;
		
		$vars = array('member_id', 'username', 'cur_username', 'screen_name', 'cur_screen_name', 'password', 'password_confirm', 'cur_password', 'email', 'cur_email');
		
		if (is_array($data))
		{
			foreach ($vars as $val)
			{
				$this->$val	= (isset($data[$val])) ? $data[$val] : '';
			}
		}
		
		if (isset($data['fetch_lang']))		$this->fetch_lang 	= $data['fetch_lang'];
		if (isset($data['require_cpw']))	$this->require_cpw 	= $data['require_cpw'];
		if (isset($data['enable_log']))		$this->enable_log 	= $data['enable_log'];
		if (isset($data['val_type']))		$this->val_type 	= $data['val_type'];
		if ($this->fetch_lang == TRUE)		$LANG->fetch_language_file('myaccount');
		if ($this->require_cpw == TRUE)		$this->password_safety_check();
	}
	// END


    // ----------------------------------------------
    //  Password Safety Check
    // ----------------------------------------------    

	function password_safety_check()
	{
		global $DB, $LANG, $SESS, $FNS;
		
		if ($SESS->userdata['group_id'] == 1)
		{
			return;
		}
			
		if ($this->cur_password == '')
		{
			return $this->errors[] = $LANG->line('missing_current_password');
		}

		$query = $DB->query("SELECT COUNT(*) as count FROM exp_members WHERE member_id = '".$DB->escape_str($this->member_id)."' AND password = '".$FNS->hash(stripslashes($this->cur_password))."'");
				   
		if ($query->row['count'] != 1)
		{
			$this->errors[] = $LANG->line('invalid_password');
		}
	}
	// END
	

	// ----------------------------------
	//  Validate Username
	// ----------------------------------

	function validate_username()
	{
		global $PREFS, $LANG, $SESS, $DB;
		
		$type = $this->val_type;

        // ----------------------------------
        //  Is username missing?
        // ----------------------------------
                
        if ($this->username == '')
        {
            return $this->errors[] = $LANG->line('missing_username');
        }
        
		// ----------------------------------
		//  Is username formatting correct?
		// ----------------------------------
		
		// Reserved characters:  |  "  '  !
	
		if (preg_match("/[\||\'|\"|\!]/", $this->username))
		{
			$this->errors[] = $LANG->line('invalid_characters_in_username');
		}                    
		
		// ----------------------------------
		//  Is username min length correct?
		// ----------------------------------
		
		$len = $PREFS->ini('un_min_len');
	
		if (strlen($this->username) < $len)
		{
			$this->errors[] = str_replace('%x', $len, $LANG->line('username_too_short'));
		}                    

		// ----------------------------------
		//  Is username max length correct?
		// ----------------------------------

		if (strlen($this->username) > 32)
		{
			$this->errors[] = $LANG->line('username_password_too_long');
		}
				
		// ----------------------------------
		//  Set validation type
		// ----------------------------------
		
		if ($this->cur_username != '')
		{
			if ($this->cur_username != $this->username)	
			{
				$type = 'new';

				if ($this->enable_log == TRUE)
					$this->log_msg[] = $LANG->line('username_changed').NBS.NBS.$this->username;
			}			
		}
	
		if ($type == 'new')
		{
			// ----------------------------------
			//  Is username banned?
			// ----------------------------------
				
			if ($SESS->ban_check('username', $this->username))
			{
				$this->errors[] = $LANG->line('username_taken');
			}
		
			// ----------------------------------
			//  Is username taken?
			// ----------------------------------

			$query = $DB->query("SELECT COUNT(*) as count FROM exp_members WHERE username = '".$DB->escape_str($this->username)."'");
							  
			if ($query->row['count'] > 0)
			{
				$this->errors[] = $LANG->line('username_taken');
			}
		}
	}
	// END



	// ----------------------------------
	//  Validate Screen Name
	// ----------------------------------

	function validate_screen_name()
	{
		global $LANG, $SESS, $DB;

		$type = $this->val_type;
		                
        if ($this->screen_name == '')
        {
            return $this->screen_name = $this->username;
        }

		if ($this->cur_screen_name != '')
		{
			if ($this->cur_screen_name != $this->screen_name)
			{ 
				$type = 'new';
			 
				if ($this->enable_log == TRUE)
					$this->log_msg[] = $LANG->line('screen_name_changed').NBS.NBS.$this->screen_name;
			}        
		}
	
		if ($type == 'new')
		{
			// -------------------------------------
			//  Is screen name banned?
			// -------------------------------------
		
			if ($SESS->ban_check('screen_name', $this->screen_name))
			{
				return $this->errors[] = $LANG->line('screen_name_taken');
			}

			// -------------------------------------
			//  Is screen name taken?
			// -------------------------------------
			
			$query = $DB->query("SELECT COUNT(*) AS count FROM exp_members WHERE screen_name = '".$DB->escape_str($this->screen_name)."'");
		
			if ($query->row['count'] > 0)
			{                            
				$this->errors[] = $LANG->line('screen_name_taken');
			}
		}
	}
	// END



	// ----------------------------------
	//  Validate Password
	// ----------------------------------

	function validate_password()
	{
		global $IN, $PREFS, $FNS, $DB, $LANG;

        // ----------------------------------
        //  Is password missing?
        // ----------------------------------
        
        if ($this->password == '' AND $this->password_confirm == '')
        {
           return $this->errors[] = $LANG->line('missing_password');
        }
                
		// -------------------------------------
		//  Is password min length correct?
		// -------------------------------------
		
		$len = $PREFS->ini('pw_min_len');
	
		if (strlen($this->password) < $len)
		{
			return $this->errors[] = str_replace('%x', $len, $LANG->line('password_too_short'));
		}
		
		// -------------------------------------
		//  Is password max length correct?
		// -------------------------------------

		if (strlen($this->password) > 32)
		{
			return $this->errors[] = $LANG->line('username_password_too_long');
		}        

		// -------------------------------------
		//  Is password the same as username?
		// -------------------------------------

		// We check for a reversed password as well

		//  Make UN/PW lowercase for testing

		$lc_user = strtolower($this->username);
		$lc_pass = strtolower($this->password);
		$nm_pass = strtr($lc_pass, 'elos', '3105');


		if ($lc_user == $lc_pass || $lc_user == strrev($lc_pass) || $lc_user == $nm_pass || $lc_user == strrev($nm_pass))
		{
			return $this->errors[] = $LANG->line('password_based_on_username');
		}        
		
		// -------------------------------------
		//  Do Password and confirm match?
		// -------------------------------------
		
		if ($IN->GBL('password', 'POST') != $IN->GBL('password_confirm', 'POST'))
		{
			return $this->errors[] = $LANG->line('missmatched_passwords');
		} 
		
		// -------------------------------------
		//  Are secure passwords required?
		// -------------------------------------

		if ($PREFS->ini('require_secure_passwords') == 'y')
		{
			$count = array('uc' => 0, 'lc' => 0, 'num' => 0);
						
			$pass = preg_quote($this->password, "/");

			$len = strlen($pass);

			for ($i = 0; $i < $len; $i++)
			{
				$n = substr($pass, $i, 1);

				if (preg_match("/^[[:upper:]]$/", $n))
				{
					$count['uc']++;
				}
				elseif (preg_match("/^[[:lower:]]$/", $n))
				{
					$count['lc']++;
				}
				elseif (preg_match("/^[[:digit:]]$/", $n))
				{
					$count['num']++;
				}
			}
			
			foreach ($count as $val)
			{
				if ($val == 0)
				{
					return $this->errors[] = $LANG->line('not_secure_password');
				}
			}
		}
		
		
		// -------------------------------------
		//  Does password exist in dictionary?
		// -------------------------------------

		if ($this->lookup_dictionary_word($lc_pass))
		{
			$this->errors[] = $LANG->line('password_in_dictionary');
		}
	}
	// END



	// ----------------------------------
	//  Validate Email
	// ----------------------------------

	function validate_email()
	{
		global $DB, $PREFS, $LANG, $SESS, $REGX;        
                
		$type = $this->val_type;
                
        // -------------------------------------
        //  Is email missing?
        // -------------------------------------
        
        if ($this->email == '')
        {
            return $this->errors[] = $LANG->line('missing_email');
        }

		// -------------------------------------
		//  Is email valid?
		// -------------------------------------
		
		if ( ! $REGX->valid_email($this->email))
		{
			return $this->errors[] = $LANG->line('invalid_email_address');
		}
		
        // -------------------------------------
        //  Set validation type
        // -------------------------------------
                
		if ($this->cur_email != '')
		{
			if ($this->cur_email != $this->email)	
			{
				if ($this->enable_log == TRUE)
					$this->log_msg = $LANG->line('email_changed').NBS.NBS.$this->email;
			
				$type = 'new';
			}			
		}		
		
        if ($type == 'new')
        {
			// -------------------------------------
			//  Is email banned?
			// -------------------------------------
        
			if ($SESS->ban_check('email', $this->email))
			{
				return $this->errors[] = $LANG->line('email_taken');
			}

			// -------------------------------------
			// Do we allow multiple identical emails?
			// -------------------------------------
			
			if ($PREFS->ini('allow_multi_emails') == 'n')
			{
				$query = $DB->query("SELECT COUNT(*) as count FROM exp_members WHERE email = '".$DB->escape_str($this->email)."'");
			
				if ($query->row['count'] > 0)
				{
					$this->errors[] = $LANG->line('email_taken');
				}
			}
		}
	}
	// END


	// ----------------------------------
	//  Display errors
	// ----------------------------------

	function show_errors()
	{
		global $DSP;

         if (count($this->errors) > 0)
         {
            $msg = '';
            
            foreach($this->errors as $val)
            {
                $msg .= $val.'<br />';  
            }
            
            return $DSP->error_message($msg);
         }
	}
	// END

    
    // ----------------------------------------------
    //  Lookup word in dictionary file
    // ----------------------------------------------    
  
    function lookup_dictionary_word($target)
    {  
        global $PREFS;
        
		if ($PREFS->ini('allow_dictionary_pw') == 'y')
		{
			return FALSE;
		}
		
		if ($PREFS->ini('name_of_dictionary_file') == '')
		{
			return FALSE;
		}
		
		if ( ! is_readable(PATH_DICT.$PREFS->ini('path_to_dictionary')))
		{
			return FALSE;
		}
		
		$word_file = file(PATH_DICT.$PREFS->ini('path_to_dictionary'));
				
		foreach ($word_file as $word)
		{
		 	if (trim($word) == $target)
		 	{
				return TRUE;
			}
		}
		
		return FALSE;
    }
    // END     

}
// END CLASS
?>