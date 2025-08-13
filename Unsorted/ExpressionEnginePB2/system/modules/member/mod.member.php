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
 File: mod.member.php
-----------------------------------------------------
 Purpose: Member Management Class
 Note: Because member management is so tightly
 integrated into the core system, most of the 
 member functions are contained in the core and cp
 files.
=====================================================
*/

if ( ! defined('EXT'))
{
    exit('Invalid file request');
}


class Member {

	var $MS;
	var $query;
	var	$content			= '';
	var $javascript			= '';
	var $basepath			= '';
	var $datecodes			= '';
	var $time_fmt			= 'us';
	var	$theme_path			= '';
	var $enable_breadcrumb	= TRUE;
	var $path				= 'member/';
	
	var $us_datecodes 	= array(
									'long'	=>	'%F %d, %Y &nbsp;%H:%i %A'
								);
	
	var $eu_datecodes 	= array(
									'long'	=>	'%d %F, %Y &nbsp;%h:%i'
								);

		
	var $map = array(
						'public'		=>	'mbr_member_profile',
						'forgot'		=>	'mbr_forgotten_password',
						'login'			=>	'mbr_login',
						'register'		=> 	'mbr_member_registration',						
						'memberlist'	=>	'mbr_memberlist',
						'email'			=>	'mbr_email_member',
						'send_email'	=>	'mbr_send_email',
						'aim'			=>	'mbr_aim_console',
						'icq'			=>	'mbr_icq_console',
						'profile'		=>	'mbr_my_account',
						'P1'			=>	'mbr_edit_your_profile',	
						'P2'			=>	'email_settings',	
						'P3'			=>	'username_and_password',
						'P4'			=>	'localization_settings',
						'P5'			=>	'subscriptions',
						'P6'			=>	'notepad',
						'U1'			=>	'profile_updated',
						'U2'			=>	'mbr_email_updated',
						'U3'			=>	'username_and_password',
						'U4'			=>	'localization_settings',
						'U5'			=>	'subscriptions',		
						'U6'			=>	'notepad'				
					);


    // ----------------------------------
    //  Constructor
    // ----------------------------------

	function Member()
	{
		global $FNS, $LANG, $PREFS;
		
		// ---------------------------------
		//  Fetch language files
		// ---------------------------------      
		
        $LANG->fetch_language_file('myaccount');
        $LANG->fetch_language_file('member');
        
		// ---------------------------------
		//  Define basepath for links
		// ---------------------------------      
        
		$this->basepath = $FNS->remove_double_slashes($FNS->fetch_site_index(1).'/'.$this->path.'/');		
	}
	// END

	
	
    // ----------------------------------
    //  Member Manager
    // ----------------------------------

	function manager()
	{
		global $IN, $TMPL, $FNS, $OUT, $LANG, $PREFS;
		
		// ---------------------------------
		//  Fetch theme
		// ---------------------------------      
	  
		$this->theme_path = ($PREFS->ini('member_theme') == '') ? 'default' : $PREFS->ini('member_theme');
		
		$this->theme_path = PATH_MOD.'member/themes/'.$this->theme_path.'/';
		
		// ---------------------------------
		//  Instantiate Profile Skin class
		// ---------------------------------      
	  
		if ( ! class_exists('Member_skin'))
		{
            require $this->theme_path.'member_skin'.EXT;
		}
		
		$this->MS = new Member_skin;
		
		// ----------------------------------
		//  Fetch the URI request
		// ----------------------------------
				
		$this->request = $IN->QSTR;		
						
		if (ereg("/", $IN->QSTR))
		{
			$this->request = substr($IN->QSTR, 0, strpos($IN->QSTR, "/"));
		
			$IN->QSTR = substr($IN->QSTR, strpos($IN->QSTR, '/') + 1);
		}
				
		if (is_numeric($IN->QSTR) AND ! in_array($this->request, array('email', 'send_email', 'aim', 'icq')))
		{
			$this->request = 'public';
		}
		
		if (substr($this->request, 0, 1) == 'U' || substr($this->request, 0, 1) == 'P')
		{
			$this->request = 'profile';
		}
		
		
		// ----------------------------------
		//  Parset the request
		// ----------------------------------
			
		switch ($this->request)
		{
			case 'profile'		:	$this->profile_manager();
				break;
			case 'public'		:	$this->public_profile();
				break;
			case 'forgot'		:	$this->forgot_pw_form();
				break;
			case 'login'		:	$this->login_form();
				break;
			case 'register'		:	$this->registration_form();
				break;
			case 'memberlist'	:	$this->memberlist();
				break;
			case 'email'		:	$this->email_form();
				break;
			case 'send_email'	:	$this->send_email();
				break;
			case 'aim'			:	$this->aim_console();
				break;
			case 'icq'			:	$this->icq_console();
				break;
			case 'email'		:	$this->email_form();
				break;
			default				: return '';				
		}
				
		// ----------------------------------
		//  Parse the template
		// ----------------------------------
			
		return $FNS->var_replace(
									array(
											'page_title'	=>	$LANG->line($this->map[$this->request]),
											'stylesheet'	=>	$this->MS->stylesheet(),
											'javascript'	=>	$this->javascript,
											'heading'		=>	$LANG->line($this->map[$this->request]),
											'breadcrumb'	=>	$this->breadcrumb(),
											'content'		=>	$this->content,
											'copyright'		=>	$this->MS->copyright()
										 ),
									 
										$TMPL->tagdata
								 );
	}
	// END



    // ----------------------------------
    //  Member Profile Manager
    // ----------------------------------

	function profile_manager()
	{
		global $IN, $FNS, $SESS, $DB, $PREFS, $LANG;
		
		switch ($IN->QSTR)
		{
			case 'P0'			:	$method = 'profile_main';
				break;
			case 'P1'			:	$method = 'profile_edit';
				break;
			case 'P2'			:	$method = 'email_edit';
				break;
			case 'P3'			:	$method = 'username_password_edit';
				break;
			case 'P4'			:	$method = 'localization_edit';
				break;
			case 'P5'			:	$method = 'subscriptions_edit';
				break;
			case 'P6'			:	$method = 'notepad_edit';
				break;
			case 'U1'			:	$method = 'profile_update';
				break;
			case 'U2'			:	$method = 'email_update';
				break;
			case 'U3'			:	$method = 'username_password_update';
				break;
			case 'U4'			:	$method = 'localization_update';
				break;
			case 'U5'			:	$method = 'subscriptions_update';
				break;
			case 'U6'			:	$method = 'notepad_update';
				break;
			default				: 	$method = 'profile_main';				
		}		
		
		if ( ! method_exists($this, $method))
		{
            return '';
		}

		$this->enable_breadcrumb = TRUE;
									        						
		// ---------------------------------
		//  Set date/time format
		// ---------------------------------      

        $this->time_fmt = ($SESS->userdata['time_format'] != '') ? $SESS->userdata['time_format'] : $PREFS->ini('time_format');
		
		$this->datecodes = ($this->time_fmt == 'us') ? $this->us_datecodes : $this->eu_datecodes;
				
		
		// ---------------------------------
		//  Is the user logged in?
		// ---------------------------------
		
		if ($SESS->userdata['member_id'] == 0)
		{
			return $this->login_form($this->basepath.$IN->QSTR.'/');
		}
		
        $this->query = $DB->query("SELECT * FROM exp_members WHERE member_id = '".$SESS->userdata['member_id']."'");
        
        if ($this->query->num_rows == 0)
        {
			return $this->login_form($this->basepath.$IN->QSTR.'/');
        }		
            
		// ---------------------------------
		//  Build the output
		// ---------------------------------
				
		$this->content  = $this->profile_menu();
		$this->content .= $this->$method();	
	}
	// END
	
	
	
	
    // ----------------------------------------
    //  Member Profile - Menu
    // ----------------------------------------

	function profile_menu()
	{
		global $FNS, $LANG;
	
		$swap = array(
						'path:profile'					=>	$this->basepath.'profile/P1/',
						'path:email'					=>	$this->basepath.'profile/P2/',
						'path:username'					=>	$this->basepath.'profile/P3/',
						'path:localization'				=>	$this->basepath.'profile/P4/',
						'path:subscriptions'			=>	$this->basepath.'profile/P5/',
						'path:notepad'					=>	$this->basepath.'profile/P6/',
						'lang:menu'						=>	$LANG->line('mbr_menu'),
						'lang:personal_settings'		=>	$LANG->line('personal_settings'),
						'lang:edit_profile'				=>	$LANG->line('edit_profile'),
						'lang:email_settings'			=>	$LANG->line('email_settings'),
						'lang:username_and_password'	=>	$LANG->line('username_and_password'),
						'lang:localization'				=>	$LANG->line('localization'),
						'lang:subscriptions'			=>	$LANG->line('subscriptions'),
						'lang:edit_subscriptions'		=>	$LANG->line('edit_subscriptions'),
						'lang:extras'					=>	$LANG->line('extras'),
						'lang:back_to_main'				=>	$LANG->line('mbr_back_to_main'),
						'lang:notepad'					=>	$LANG->line('notepad')
					 );
	
	
		return $FNS->var_replace($swap, $this->MS->menu());
	}
	// END
	
	
	
	
    // ----------------------------------------
    //  Member Profile Main Page
    // ----------------------------------------

	function profile_main()
	{
		global $FNS, $LANG, $LOC;
			
		$swap = array(
						'email'							=>	$this->query->row['email'],
						'join_date'						=>	$LOC->decode_date($this->datecodes['long'], $this->query->row['join_date']),
						'last_visit_date'				=>	($this->query->row['last_visit'] == 0) ? '--' : $LOC->decode_date($this->datecodes['long'], $this->query->row['last_visit']),
						'recent_entry_date'				=>	($this->query->row['last_entry_date'] == 0) ? '--' : $LOC->decode_date($this->datecodes['long'], $this->query->row['last_entry_date']),
						'recent_comment_date'			=>	($this->query->row['last_comment_date'] == 0) ? '--' : $LOC->decode_date($this->datecodes['long'], $this->query->row['last_comment_date']),
						'total_entries'					=>	$this->query->row['total_entries'],
						'total_comments'				=>	$this->query->row['total_comments'],
						'lang:your_stats'				=>	$LANG->line('mbr_your_stats'),
						'lang:email'					=>	$LANG->line('email'),
						'lang:join_date'				=>	$LANG->line('join_date'),
						'lang:last_visit'				=>	$LANG->line('last_visit'),
						'lang:most_recent_entry'		=>	$LANG->line('mbr_most_recent_entry'),
						'lang:most_recent_comment'		=>	$LANG->line('mbr_most_recent_comment'),
						'lang:total_entries'			=>	$LANG->line('total_entries'),
						'lang:total_comments'			=>	$LANG->line('total_comments'),
						'lang:back_to_main'				=>	$LANG->line('mbr_back_to_main')
					 );
	
		return $FNS->var_replace($swap, $this->MS->home_page());
	}
	// END

	
	
	
    // ----------------------------------------
    //  Member Profile Edit Page
    // ----------------------------------------

	function profile_edit()
	{
		global $FNS, $LANG, $LOC, $DB, $SESS;
		
		
		// ----------------------------------------
		//  Build the custom profile fields
		// ----------------------------------------
		
		$tmpl = $this->MS->custom_profile_fields();
		
		// ----------------------------------------
		//  Fetch the data
		// ----------------------------------------
		
        $sql = "SELECT * FROM exp_member_data WHERE member_id = '".$SESS->userdata['member_id']."'";
                        
        $result = $DB->query($sql);        
        
        if ($result->num_rows > 0)
        {
			foreach ($result->row as $key => $val)
			{
				$$key = $val;
			}
        }
        
		// ----------------------------------------
		//  Fetch the field defenitions
		// ----------------------------------------
        
        $r = '';
                                                                                 
		$sql = "SELECT *  FROM exp_member_fields ";
		
		if ($SESS->userdata['group_id'] != 1)
		{
			$sql .= " WHERE m_field_public = 'y' ";
		}
		
		$sql .= " ORDER BY m_field_order";
        
        $query = $DB->query($sql);
        
        if ($query->num_rows > 0)
        {                
			foreach ($query->result as $row)
			{
				$temp = $tmpl;  
				
				// ----------------------------------------
				//  Assign the data to the field
				// ----------------------------------------
			
				$field_data = ( ! isset( $result->row['m_field_id_'.$row['m_field_id']] )) ? '' : $result->row['m_field_id_'.$row['m_field_id']];
																										  
				$required  = ($row['m_field_required'] == 'n') ? '' : "<span class='alert'>*</span>&nbsp;";     
			
                $width = ( ! ereg("px", $row['m_field_width'])  AND ! ereg("%", $row['m_field_width'])) ? $row['m_field_width'].'px' : $row['m_field_width'];
			
				// ----------------------------------------
				//  Render textarea fields
				// ----------------------------------------
			
				if ($row['m_field_type'] == 'textarea')
				{               
					$rows = ( ! isset($row['m_field_ta_rows'])) ? '10' : $row['m_field_ta_rows'];
				
					$tarea = "<textarea name='".'m_field_id_'.$row['m_field_id']."'style='width:".$width.";' class='textarea' cols='90' rows='{$rows}'>".$field_data."</textarea>";
				
					$temp = str_replace('<td ', "<td valign='top' ", $temp);
					$temp = str_replace('{lang:profile_field}', $required.$row['m_field_label'], $temp);
					$temp = str_replace('{form:custom_profile_field}', $tarea, $temp);
				}
				elseif ($row['m_field_type'] == 'text')
				{ 
					// ----------------------------------------
					//  Render text fields
					// ----------------------------------------
				  
					$input = "<input type='text' name='".'m_field_id_'.$row['m_field_id']."'style='width:".$width.";' value='".$field_data."' maxlength='".$row['m_field_maxl']."' class='input' />";
				
					$temp = str_replace('{lang:profile_field}', $required.$row['m_field_label'], $temp);
					$temp = str_replace('{form:custom_profile_field}', $input, $temp);
				}					
				elseif ($row['m_field_type'] == 'select')
				{
					// ----------------------------------------
					//  Render pull-down menues
					// ----------------------------------------
				  
					$menu = "<select name='m_field_id_".$row['m_field_id']."' class='select'>\n";
					
					foreach (explode("\n", trim($row['m_field_list_items'])) as $v)
					{   
						$v = trim($v);
					
						$selected = ($field_data == $v) ? " selected='selected'" : '';
						
						$menu .= "<option value='{$v}'{$selected}>".$v."</option>\n";                            
					}

					$menu .= "</select>\n";
					
					$temp = str_replace('{lang:profile_field}', $required.$row['m_field_label'], $temp);
					$temp = str_replace('{form:custom_profile_field}', $menu, $temp);						
				}
				
				$r .= $temp;
			}        
		}		
		
		// ----------------------------------------
		//  Build the output data
		// ----------------------------------------
					
		$swap = array(
						'path:update_profile'		=> $this->basepath.'U1/',
						'lang:edit_your_profile'	=> $LANG->line('mbr_edit_your_profile'),
						'lang:url'					=> $LANG->line('url'),
						'lang:location'				=> $LANG->line('location'),
						'lang:occupation'			=> $LANG->line('occupation'),
						'lang:interests'			=> $LANG->line('interests'),
						'lang:aol_im'				=> $LANG->line('aol_im'),
						'lang:icq'					=> $LANG->line('icq'),
						'lang:yahoo_im'				=> $LANG->line('yahoo_im'),
						'lang:msn_im'				=> $LANG->line('msn_im'),
						'lang:bio'					=> $LANG->line('bio'),
						'lang:birthday'				=> $LANG->line('birthday'),
						'lang:update'				=> $LANG->line('update'),
						'lang:required'				=> $LANG->line('mbr_required_fields'),
						'lang:back_to_main'			=>	$LANG->line('mbr_back_to_main'),
						'url'						=> ($this->query->row['url'] == '') ? 'http://' : $this->query->row['url'],
						'location'					=> $this->query->row['location'],
						'occupation'				=> $this->query->row['occupation'],
						'interests'					=> $this->query->row['interests'],
						'aol_im'					=> $this->query->row['aol_im'],
						'icq'						=> $this->query->row['icq'],
						'yahoo_im'					=> $this->query->row['yahoo_im'],
						'msn_im'					=> $this->query->row['msn_im'],
						'bio'						=> $this->query->row['bio'],
						'form:birthday_year'		=> $this->birthday_year($this->query->row['bday_y']),
						'form:birthday_month'		=> $this->birthday_month($this->query->row['bday_m']),
						'form:birthday_day'			=> $this->birthday_day($this->query->row['bday_d']),
						'custom_profile_fields'		=> $r
					);
	
		return $FNS->var_replace($swap, $this->MS->edit_profile_form());
	}
	// END
	
	
	
	
    // ----------------------------------------
    //  Profile Update
    // ----------------------------------------

	function profile_update()
	{
        global $IN, $DB, $SESS, $PREFS, $FNS, $REGX, $LANG, $OUT;
        
                 
        // -------------------------------------
		// Are any required custom fields empty?
        // -------------------------------------
                
         $query = $DB->query("SELECT m_field_id, m_field_label FROM exp_member_fields WHERE m_field_required = 'y'");
         
		 $errors = array();        
         
         if ($query->num_rows > 0)
         {         
            foreach ($query->result as $row)
            {
                if (isset($_POST['m_field_id_'.$row['m_field_id']]) AND $_POST['m_field_id_'.$row['m_field_id']] == '') 
                {
                    $errors[] = $LANG->line('mbr_custom_field_empty').'&nbsp;'.$row['m_field_label'];
                }           
            }
         }
                                 
        // -------------------------------------
		// Show errors
        // -------------------------------------

         if (count($errors) > 0)
         {
			return $OUT->show_user_error('submission', $errors);
         }

        // -------------------------------------
		// Build query
        // -------------------------------------
        
        if (isset($_POST['url']) AND $_POST['url'] == 'http://')
        {
			$_POST['url'] = '';
        }
        
        $fields = array(	'bday_y',
        					'bday_m',
        					'bday_d',
        					'url', 
        					'location', 
        					'occupation', 
        					'interests', 
        					'aol_im', 
        					'icq', 
        					'yahoo_im', 
        					'msn_im',
        					'bio'
        				);

        $data = array();
        
        foreach ($fields as $val)
        {
        	if (isset($_POST[$val]))
        	{
        		$data[$val] = $_POST[$val];	
        	}
        	
        	unset($_POST[$val]);
        }
        
        if (count($data) > 0)
        {
        	$DB->query($DB->update_string('exp_members', $data, "member_id = '".$SESS->userdata['member_id']."'"));   
		}
		        
        // -------------------------------------
        // Update the custom fields
        // -------------------------------------
   
   		if (count($_POST) > 0)
   		{
			$DB->query($DB->update_string('exp_member_data', $_POST, "member_id = '".$SESS->userdata['member_id']."'"));   
		}
        
        // -------------------------------------
        // Success message
        // -------------------------------------
                
		$swap = array(
						'lang:heading'	=>	$LANG->line('profile_updated'),
						'lang:message'	=>	$LANG->line('mbr_profile_has_been_updated')
					 );
	
	
		return $FNS->var_replace($swap, $this->MS->success());
	}
	// END	
	
	
	

    // ----------------------------------------
    //  Email Settings
    // ----------------------------------------

	function email_edit()
	{
     	global $FNS, $LANG;     	
     	           
		$swap = array(
						'path:update_email_settings'	=>	$this->basepath.'U2/',
						'email'							=>	$this->query->row['email'],
						'lang:update'					=> 	$LANG->line('update'),
						'lang:required'					=> 	$LANG->line('mbr_required_fields'),
						'lang:email_settings'			=>	$LANG->line('email_settings'),
						'lang:email'					=>	$LANG->line('email'),
						'lang:accept_admin_email'		=>	$LANG->line('accept_admin_email'),
						'lang:accept_user_email'		=>	$LANG->line('accept_user_email'),
						'lang:notify_by_default'		=>	$LANG->line('notify_by_default'),
						'lang:existing_password'		=>	$LANG->line('existing_password'),
						'lang:back_to_main'				=>	$LANG->line('mbr_back_to_main'),
						'lang:existing_password_exp'	=>	$LANG->line('password_required_for_email'),
						'state:accept_admin_email'		=>	($this->query->row['accept_admin_email'] == 'y') ? " checked='checked'" : '',
						'state:accept_user_email'		=>	($this->query->row['accept_user_email'] == 'y')  ? " checked='checked'" : '',
						'state:notify_by_default'		=>	($this->query->row['notify_by_default'] == 'y')  ? " checked='checked'" : ''
					 );
	
		return $FNS->var_replace($swap, $this->MS->email_prefs_form());
	}
	// END

	
	
	
    // ----------------------------------------
    //  Email Update
    // ----------------------------------------

	function email_update()
	{
        global $DB, $SESS, $LANG, $OUT, $FNS;
	
		// Safety.  Prevents improperly accessing this method
		
        if ( ! isset($_POST['email']))
		{
			return $FNS->redirect($this->basepath.'P1/');
		}
	
        // -------------------------------------
        //  Validate submitted data
        // -------------------------------------

		if ( ! class_exists('Validate'))
		{
			require PATH_CORE.'core.validate'.EXT;
		}
		
		$VAL = new Validate(
								array( 
										'member_id'			=> $SESS->userdata['member_id'],
										'val_type'			=> 'update', // new or update
										'fetch_lang' 		=> TRUE, 
										'require_cpw' 		=> FALSE,
										'enable_log'		=> FALSE,
										'email'				=> $_POST['email'],
										'cur_email'			=> $this->query->row['email']
									 )
							);

		$VAL->validate_email();
		
		if ($_POST['email'] != $this->query->row['email'])
		{
			if ($SESS->userdata['group_id'] != 1)
			{
				if ($_POST['password'] == '')
				{
					$VAL->errors[] = $LANG->line('missing_current_password');
				}
				elseif ($FNS->hash(stripslashes($_POST['password'])) != $this->query->row['password'])
				{
					$VAL->errors[] = $LANG->line('invalid_password');
				}
			}
		}
		
		if (count($VAL->errors) > 0)
		{
			return $OUT->show_user_error('submission', $VAL->errors);
		}		

        // -------------------------------------
        // Assign the query data
        // -------------------------------------
                
        $data = array(
                        'email'                 =>  $_POST['email'],
                        'accept_admin_email'    => (isset($_POST['accept_admin_email'])) ? 'y' : 'n',
                        'accept_user_email'     => (isset($_POST['accept_user_email']))  ? 'y' : 'n',
                        'notify_by_default'     => (isset($_POST['notify_by_default']))  ? 'y' : 'n'
                      );

        $DB->query($DB->update_string('exp_members', $data, "member_id = '".$SESS->userdata['member_id']."'"));   
        
        // -------------------------------------
        // Update comments and log email change
        // -------------------------------------
                
        if ($this->query->row['email'] != $_POST['email'])
        {                           
            $DB->query($DB->update_string('exp_comments', array('email' => $_POST['email']), "author_id = '".$SESS->userdata['member_id']."'"));   
        
        }
        
        // -------------------------------------
        // Success message
        // -------------------------------------
                
		$swap = array(
						'lang:heading'	=>	$LANG->line('mbr_email_updated'),
						'lang:message'	=>	$LANG->line('mbr_email_has_been_updated')
					 );
	
	
		return $FNS->var_replace($swap, $this->MS->success());
	}
	// END	
	
	
	
	
    // ----------------------------------------
    //  Username/Password Preferences
    // ----------------------------------------

	function username_password_edit()
	{
     	global $FNS, $LANG, $SESS, $PREFS;     
     	     	               	     	           
		$swap = array(
						'row:username_form'				=>	($SESS->userdata['group_id'] == 1 || $PREFS->ini('allow_username_change') == 'y') ? $this->MS->username_row() : $this->MS->username_change_disallowed(),
						'path:update_username_password'	=>	$this->basepath.'U3/',
						'username'						=>	$this->query->row['username'],
						'screen_name'					=>	$this->query->row['screen_name'],
						'lang:back_to_main'				=>	$LANG->line('mbr_back_to_main'),
						'lang:screen_name'				=>	$LANG->line('screen_name'),
						'lang:screen_name_explanation'	=>	$LANG->line('mbr_screen_name_explanation'),
						'lang:username_disallowed'		=> 	$LANG->line('username_disallowed'),
						'lang:update'					=> 	$LANG->line('update'),
						'lang:required'					=> 	$LANG->line('mbr_required_fields'),
						'lang:username_and_password'	=>	$LANG->line('username_and_password'),
						'lang:username'					=>	$LANG->line('username'),
						'lang:password_change'			=>	$LANG->line('password_change'),
						'lang:password_change_exp'		=>	$LANG->line('leave_blank'),
						'lang:new_password'				=>	$LANG->line('new_password'),
						'lang:new_password_confirm'		=>	$LANG->line('new_password_confirm'),
						'lang:existing_password'		=>	$LANG->line('existing_password'),
						'lang:existing_password_exp'	=>	$LANG->line('password_required')
					 );
	
		return $FNS->var_replace($swap, $this->MS->username_password_form());
	}
	// END

	
	
	
    // ----------------------------------------
    //  Username/Password Update
    // ----------------------------------------

	function username_password_update()
	{
        global $IN, $DB, $SESS, $PREFS, $FNS, $REGX, $OUT, $LANG;
      
      	// Safety.  Prevents accessing this function unless
      	// the requrest came from the form submission
      
        if ( ! isset($_POST['current_password']))
		{
			return $FNS->redirect($this->basepath.'P1/');
		}

		// If the screen name field is empty, we'll assign is
		// from the username field.              
               
		if ($_POST['screen_name'] == '')
			$_POST['screen_name'] = $_POST['username'];              

        // -------------------------------------
        //  Validate submitted data
        // -------------------------------------

		if ( ! class_exists('Validate'))
		{
			require PATH_CORE.'core.validate'.EXT;
		}
		
		$VAL = new Validate(
								array( 
										'member_id'			=> $SESS->userdata['member_id'],
										'val_type'			=> 'update', // new or update
										'fetch_lang' 		=> TRUE, 
										'require_cpw' 		=> TRUE,
									 	'enable_log'		=> FALSE,
										'username'			=> $_POST['username'],
										'cur_username'		=> $this->query->row['username'],
										'screen_name'		=> $_POST['screen_name'],
										'cur_screen_name'	=> $this->query->row['screen_name'],
										'password'			=> $_POST['password'],
									 	'password_confirm'	=> $_POST['password_confirm'],
									 	'cur_password'		=> $_POST['current_password']
									 )
							);
														
		$VAL->validate_screen_name();

        if ($PREFS->ini('allow_username_change') == 'y')
        {
			$VAL->validate_username();
        }
                       
        if ($_POST['password'] != '')
        {
			$VAL->validate_password();
        }
                        
        // -------------------------------------
        //  Display error is there are any
        // -------------------------------------
        
		if (count($VAL->errors) > 0)
		{
			return $OUT->show_user_error('submission', $VAL->errors);
		}		
         
        // -------------------------------------
        // Assign the query data
        // -------------------------------------

        $data['screen_name'] = $_POST['screen_name'];

        if ($PREFS->ini('allow_username_change') == 'y')
        {
            $data['username'] = $_POST['username'];
        }
        
        // Was a password submitted?

        $pw_change = '';

        if ($_POST['password'] != '')
        {
            $data['password'] = $FNS->hash(stripslashes($_POST['password']));
                        
            $pw_change = $FNS->var_replace(array('lang:password_change_warning' => $LANG->line('password_change_warning')), $this->MS->password_change_warning());
        }
        
        $DB->query($DB->update_string('exp_members', $data, "member_id = '".$SESS->userdata['member_id']."'"));   
        
        // -------------------------------------
        //  Update comments if screen name has changed
        // -------------------------------------        

		if ($this->query->row['screen_name'] != $_POST['screen_name'])
		{                          
            $DB->query($DB->update_string('exp_comments', array('name' => $_POST['screen_name']), "author_id = '".$SESS->userdata['member_id']."'"));   
        }

        // -------------------------------------
        // Success message
        // -------------------------------------        
                
		$swap = array(
						'lang:heading'	=>	$LANG->line('username_and_password'),
						'lang:message'	=>	$LANG->line('mbr_settings_updated').$pw_change
					 );
	
	
		return $FNS->var_replace($swap, $this->MS->success());
	}
	// END	
	
	
	
	
    // ----------------------------------------
    //  Localization Edit Form
    // ----------------------------------------
	
	function localization_edit()
	{
		global $LANG, $FNS, $LOC;
	
		$swap = array(
						'path:update_localization'		=>	$this->basepath.'U4/',
						'form:localization'				=>	$LOC->timezone_menu(($this->query->row['timezone'] == '') ? 'UTC' : $this->query->row['timezone']),   
						'state:daylight_savings'		=>	($this->query->row['daylight_savings'] == 'y') ? " checked='checked'" : '',
						'lang:timezone'					=>	$LANG->line('timezone'),
						'lang:localization_settings'	=>	$LANG->line('localization_settings'),
						'lang:daylight_savings_time'	=>	$LANG->line('daylight_savings_time'),
						'lang:time_format'				=>	$LANG->line('time_format'),
						'form:time_format'				=>	$this->time_format(),
						'lang:update'					=>	$LANG->line('update'),
						'lang:language'					=>	$LANG->line('language_choice'),
						'lang:back_to_main'				=>	$LANG->line('mbr_back_to_main'),
						'form:language'					=>	$FNS->language_pack_names(($this->query->row['language'] == '') ? 'english' : $this->query->row['language'])
					 );
					                 	
		return $FNS->var_replace($swap, $this->MS->localization_form());
	}
	// END
	
	
	
	
    // ----------------------------------------
    //  Update Localization Prefs
    // ----------------------------------------
	
	function localization_update()
	{
		global $FNS, $IN, $SESS, $DB, $LANG;
		
        if ( ! isset($_POST['server_timezone']))
		{
			return $FNS->redirect($this->basepath.'P1/');
		}
	
        $data['language']    = $_POST['deft_lang'];
        $data['timezone']    = $_POST['server_timezone'];
        $data['time_format'] = $_POST['time_format'];

        $data['daylight_savings'] = ($IN->GBL('daylight_savings', 'POST') == 'y') ? 'y' : 'n';
        
        $DB->query($DB->update_string('exp_members', $data, "member_id = '".$SESS->userdata['member_id']."'"));   
        
        // -------------------------------------
        // Success message
        // -------------------------------------        
                
		$swap = array(
						'lang:heading'	=>	$LANG->line('localization_settings'),
						'lang:message'	=>	$LANG->line('mbr_localization_settings_updated')
					 );
	
		return $FNS->var_replace($swap, $this->MS->success());
	}
	// END
	
	
	
	
    // ----------------------------------------
    //  Notepad Edit Form
    // ----------------------------------------
	
	function notepad_edit()
	{
		global $LANG, $FNS, $LOC;
	
		$swap = array(
						'path:update_notepad'	=>	$this->basepath.'U6/',
						'lang:notepad'			=>	$LANG->line('notepad'),
						'lang:notepad_blurb'	=>	$LANG->line('notepad_instructions'),
						'lang:update'			=>	$LANG->line('update'),
						'lang:back_to_main'		=>	$LANG->line('mbr_back_to_main'),
						'notepad_data'			=>	$this->query->row['notepad'],
						'lang:notepad_size'		=>	$LANG->line('notepad_size'),
						'notepad_size'			=>	$this->query->row['notepad_size'],
					 );
					                 	
		return $FNS->var_replace($swap, $this->MS->notepad_form());
	}
	// END
	
	
	
    // ----------------------------------------
    //  Update Notepad
    // ----------------------------------------
	
	function notepad_update()
	{
		global $FNS, $IN, $SESS, $DB, $LANG;
		
        if ( ! isset($_POST['notepad']))
		{
			return $FNS->redirect($this->basepath.'P1/');
		}
	
        $notepad_size = ( ! is_numeric($_POST['notepad_size'])) ? 18 : $_POST['notepad_size'];

        $DB->query("UPDATE exp_members SET notepad = '".$DB->escape_str($_POST['notepad'])."', notepad_size = '".$notepad_size."' WHERE member_id ='".$SESS->userdata['member_id']."'");
        
        // -------------------------------------
        // Success message
        // -------------------------------------        
                
		$swap = array(
						'lang:heading'	=>	$LANG->line('notepad'),
						'lang:message'	=>	$LANG->line('mbr_notepad_updated')
					 );
	
		return $FNS->var_replace($swap, $this->MS->success());
	}
	// END
	
	
	
    // ----------------------------------------
    //  Subscriptions Edit Form
    // ----------------------------------------
	
	function subscriptions_edit()
	{
		global $LANG, $FNS, $LOC;
	
		$swap = array(
						'path:update_subscriptions'	=>	$this->basepath.'U5/',
						'lang:subscriptions'		=>	$LANG->line('subscriptions'),
						'lang:back_to_main'			=>	$LANG->line('mbr_back_to_main'),
						'lang:update'				=>	$LANG->line('update')
					 );
					                 	
		return $FNS->var_replace($swap, $this->MS->subscriptions_form());
	}
	// END
	
	
	
    // ----------------------------------------
    //  Update Subscriptions
    // ----------------------------------------
	
	function notepad_subscriptions()
	{
		global $FNS, $IN, $SESS, $DB, $LANG;
	
        
        // -------------------------------------
        // Success message
        // -------------------------------------        
                
		$swap = array(
						'lang:heading'		=>	$LANG->line('notepad'),
						'lang:back_to_main'	=>	$LANG->line('mbr_back_to_main'),
						'lang:message'		=>	$LANG->line('mbr_notepad_updated')
					 );
	
		return $FNS->var_replace($swap, $this->MS->success());
	}
	// END
	
		
    // ----------------------------------------
    //  Member Breadcrumb
    // ----------------------------------------

	function breadcrumb()
	{
		global $FNS, $SESS, $LANG;
		
		if ($this->enable_breadcrumb == FALSE)
		{
			return '';
		}
		
		$template = $this->MS->breadcrumb();

		if (preg_match_all("#".LD."\s*(profile_path\s*=.*?)".RD."#", $template, $matches))
		{
			$i = 0;
			foreach ($matches['1'] as $val)
			{
				$path = $FNS->create_url($FNS->extract_path($val).'/'.$SESS->userdata['member_id']);			

				$template =& preg_replace("#".$matches['0'][$i++]."#", $path, $template, 1); 
			}
		}
		
		$swap = array(
						'breadcrumb' 			=>	$this->breadcrumb_links(),
						'name'					=>	$SESS->userdata['screen_name'],
						'lang:memberlist'		=>	$LANG->line('mbr_memberlist'),
						'lang:logged_in_as'		=>	$LANG->line('mbr_logged_in_as'),
						'lang:my_account'		=>	$LANG->line('mbr_my_account'),
						'lang:logout'			=>	$LANG->line('mbr_logout')						
					 );
	
	
		return $FNS->var_replace($swap, $template);
	}
	// END


    // ----------------------------------------
    //  Profile Breadcrumb
    // ----------------------------------------

	function breadcrumb_links()
	{
		global $IN, $LANG, $SESS;
								
		$return = '';
		
		if ( ! isset($this->map[$IN->QSTR]))
		{
			if (is_numeric($IN->QSTR))
			{
				$return = '&nbsp;&#8250;&nbsp;&nbsp;'.$LANG->line('mbr_member_profile');	
			}
		
			if ($IN->QSTR == 'profile')
			{
				$return = '&nbsp;&#8250;&nbsp;&nbsp;'.$LANG->line('mbr_your_stats');	
			}
		}
		else
		{
			if (substr($IN->QSTR, 0, 1) == 'P' || substr($IN->QSTR, 0, 1) == 'U')
			{
				$return .= '&nbsp;&#8250;&nbsp;&nbsp;<a href="'.$this->basepath.'profile/">'.$LANG->line('mbr_profile_homepage').'</a>';
			}
		
			$return .= '&nbsp;&nbsp;&#8250;&nbsp;&nbsp;'.$LANG->line($this->map[$IN->QSTR]);
		}
		
		return $return;
	}
	// END


	
	// ----------------------------------------
	//  Time Format Menu
	// ----------------------------------------

	function time_format()
	{
		global $LANG, $LOC, $SESS;
			
		$r = "<select name='time_format' class='select'>\n";
		
		$selected = ($SESS->userdata['time_format'] == 'us') ? " selected='selected'" : '';
		
		$r .= "<option value='us'{$selected}>".$LANG->line('united_states')."</option>\n";
		
		$selected = ($SESS->userdata['time_format'] == 'eu') ? " selected='selected'" : '';
		
		$r .= "<option value='eu'{$selected}>".$LANG->line('european')."</option>\n";

		$r .= "</select>\n";
	
		return $r;
	}
	// END

	
	
	// ----------------------------------------
	//  Create the "year" pull-down menu
	// ----------------------------------------

	function birthday_year($year = '')
	{
		global $LANG, $LOC;
			
		$r = "<select name='bday_y' class='select'>\n";
		
		$selected = ($year == '') ? " selected='selected'" : '';
		
		$r .= "<option value=''{$selected}>".$LANG->line('year')."</option>\n";
		
		for ($i = date('Y', $LOC->now); $i > 1904; $i--)
		{                                      
			$selected = ($year == $i) ? " selected='selected'" : '';
			
			$r .= "<option value='{$i}'{$selected}>".$i."</option>\n";                            
		}
		
		$r .= "</select>\n";
	
		return $r;
	}
	// END



	// ----------------------------------------
	//  Create the "month" pull-down menu
	// ----------------------------------------

	function birthday_month($month = '')
	{
		global $LANG;
			
		$months = array('01' => 'January','02' => 'February','03' => 'March', '04' => 'April', '05' => 'May', '06' => 'June', '07' => 'July', '08' => 'August', '09' => 'September', '10' => 'October', '11' => 'November', '12' => 'December');
		
		$r = "<select name='bday_m' class='select'>\n";
		
		$selected = ($month == '') ? " selected='selected'" : '';
		
		$r .= "<option value=''{$selected}>".$LANG->line('month')."</option>\n";
		
		for ($i = 1; $i < 13; $i++)
		{
			if (strlen($i) == 1)
				$i = '0'.$i;
			
			$selected = ($month == $i) ? " selected='selected'" : '';
			
			$r .= "<option value='{$i}'{$selected}>".$LANG->line($months[$i])."</option>\n";                            
		}
				
		$r .= "</select>\n";
	
		return $r;
	}
	// END


	// ----------------------------------------
	//  Create the "day" pull-down menu
	// ----------------------------------------

	function birthday_day($day = '')
	{
		global $LANG;
			
		$r = "<select name='bday_d' class='select'>\n";
		
		$selected = ($day == '') ? " selected='selected'" : '';
		
		$r .= "<option value=''{$selected}>".$LANG->line('day')."</option>\n";
		
		for ($i = 31; $i >= 1; $i--)
		{                                      
			$selected = ($day == $i) ? " selected='selected'" : '';
			
			$r .= "<option value='{$i}'{$selected}>".$i."</option>\n";                            
		}
		
		$r .= "</select>\n";
	
		return $r;
	}
	// END



    // ----------------------------------------
    //  Member List
    // ----------------------------------------

    function memberlist()
    {
		global $IN, $TMPL, $DB, $LANG, $OUT, $SESS, $LOC, $FNS, $PREFS;
		
					        		
        // ----------------------------------------
        //  Can the user view profiles?
        // ----------------------------------------
				
		if ($SESS->userdata['can_view_profiles'] == 'n')
		{
			return $OUT->show_user_error('general', array($LANG->line('mbr_not_allowed_to_view_profiles')));
		}
				
        // ----------------------------------------
        //  Grab the templates
        // ----------------------------------------
		
		$member_row = $this->MS->memberlist_rows();
		$this->content = $this->MS->memberlist();
		$TMPL->assign_variables($this->content, '/');				
		$TMPL->assign_variables($member_row, '/');
			
		$qm = ($PREFS->ini('force_query_string') == 'y') ? '' : '?';
		
		$link_jump = $FNS->fetch_site_index().$qm.'URL=';			
					
        // ----------------------------------------
        //  Define the translation array
        // ----------------------------------------

		$lines = array(
						'email'	=> 'mbr_email'
					  );
					  
					  
        // ----------------------------------------
        //  Fetch the custom member field defenitions
        // ----------------------------------------
        
        $fields = array();
        
        $query = $DB->query("SELECT m_field_id, m_field_name FROM exp_member_fields");
        
        if ($query->num_rows > 0)
        {
        	foreach ($query->result as $row)
        	{
            	$fields[$row['m_field_name']] = $row['m_field_id'];
        	}
        }
        
		
        // ----------------------------------------
        //  Assign default variables
        // ----------------------------------------
                                                                     			
		$vars = array(
						'group_id'		=>	0, 
						'order_by'		=>	'screen_name', 
						'sort_order'	=>	'asc',
						'row_limit'		=>	'10',
						'row_count'		=>	0
					);
					
		
		foreach ($vars as $key => $val)
		{
			$$key = ( ! isset($_POST[$key])) ? $val : $_POST[$key];
		}

        // ----------------------------------------
        //  Parse Query String
        // ----------------------------------------

		// This fixes a bug that is induced by the pagenate class.
		
		if (eregi("^[0-9]{1,}\-[0-9a-z_]{1,}\-[0-9a-z]{1,}\-[0-9]{1,}\-$", $IN->QSTR))
		{									
			$IN->URI = str_replace($IN->QSTR.'/', '', $IN->URI);
			$IN->QSTR .= '0';
		}

		// Parse out the various query string variables.
		// Since ExpressionEngine doesn't use true query strings
		// we'll define a pseudo-query string in one of the URI segments
		
		$path = $IN->URI;
                
		if (eregi("^[0-9]{1,}\-[0-9a-z_]{1,}\-[0-9a-z]{1,}\-[0-9]{1,}\-[0-9]{1,}$", $IN->QSTR))
		{
			$x = explode("-", $IN->QSTR);
		
			$group_id	= $x['0'];
			$order_by 	= $x['1'];
			$sort_order	= $x['2'];
			$row_limit	= $x['3'];
			$row_count	= $x['4'];
						
			$path = $FNS->remove_double_slashes(str_replace($IN->QSTR, '', $IN->URI));
			
			$path .= $x['0'].'-'.$x['1'].'-'.$x['2'].'-'.$x['3'].'-';
		}
		else
		{
			$path .= $group_id.'-'.$order_by.'-'.$sort_order.'-'.$row_limit.'-';
		}
        
        // ----------------------------------------
        //  Build the query
        // ----------------------------------------  
        
        $sql = '';

        $f_sql = " SELECT 
						exp_members.member_id, 
						exp_members.username, 
						exp_members.screen_name, 
						exp_members.email, 
						exp_members.url, 
						exp_members.location, 
						exp_members.icq, 
						exp_members.aol_im, 
						exp_members.yahoo_im, 
						exp_members.msn_im, 
						exp_members.location, 
						exp_members.join_date, 
						exp_members.last_visit, 
						exp_members.last_entry_date, 
						exp_members.last_comment_date, 
						exp_members.total_entries,
						exp_members.total_comments,
						exp_members.language, 
						exp_members.timezone, 
						exp_members.daylight_savings, 
						exp_members.bday_d,
						exp_members.bday_m,
						exp_members.bday_y,
						exp_members.accept_user_email,
						exp_member_groups.group_title ";
						
		$p_sql = "SELECT COUNT(member_id) AS count ";
						
		$sql .= "FROM exp_members, exp_member_groups
				WHERE exp_members.group_id = exp_member_groups.group_id
				AND exp_member_groups.group_id != '2' 
				AND exp_member_groups.group_id != '3' 
				AND exp_member_groups.group_id != '4'";
						
        // 2 = Banned
        // 3 = Guests
        // 4 = Pending
						
		 		
		if ($group_id != 0)
		{
			$sql .= " AND exp_member_groups.group_id = '$group_id'";
		}
 		
		$sql .= " ORDER BY exp_members.".$order_by." ".$sort_order;
 				
        // ----------------------------------------
        //  Run "count" query for pagination
        // ----------------------------------------
        
		$query = $DB->query($p_sql.$sql);
			
		$current_page =  ($row_count / $row_limit) + 1;
			
        $total_pages = intval($query->row['count'] / $row_limit);
        
        if ($query->row['count'] % $row_limit) 
        {
            $total_pages++;
        }		

		$page_count = $LANG->line('page').' '.$current_page.' '.$LANG->line('of').' '.$total_pages;
		
		// -----------------------------
    	//  Do we need pagination?
		// -----------------------------  
				
		$pager = ''; 		
		
		if ($query->row['count'] > $row_limit)
		{ 											
			if ( ! class_exists('Paginate'))
			{
				require PATH_CORE.'core.paginate'.EXT;
			}
			
			$PGR = new Paginate();
			
			$PGR->first_url 	= $FNS->create_url('member/memberlist');
			$PGR->path			= $FNS->create_url($path, 0, 0);
			$PGR->total_count 	= $query->row['count'];
			$PGR->per_page		= $row_limit;
			$PGR->cur_page		= $row_count;
			
			$pager = $PGR->show_links();			
			 
			$sql .= " LIMIT ".$row_count.", ".$row_limit;			
		}
					
        // ----------------------------------------
        //  Run the full query and process result
        // ----------------------------------------     
        
		$query = $DB->query($f_sql.$sql);    
	
		$str = '';
		$i = 0;

		if ($query->num_rows > 0)
		{	
			foreach ($query->result as $row)
			{
				$temp = $member_row;
				
            	$style = ($i++ % 2) ? 'memberlistRowOne' : 'memberlistRowTwo';
				
				$temp = str_replace("{member_css}", $style, $temp);
								
				
				if ($row['url'] != '' AND substr($row['url'], 0, 4) != "http") 
				{ 
					$row['url'] = "http://".$row['url']; 
				} 
		
				$swap = array(
								'aim_console'			=> "onclick=\"window.open('".$this->basepath.'aim/'.$row['member_id'].'/'."', '_blank', 'width=240,height=360,scrollbars=yes,resizable=yes,status=yes,screenx=5,screeny=5');\"",
								'icq_console'			=> "onclick=\"window.open('".$this->basepath.'icq/'.$row['member_id'].'/'."', '_blank', 'width=650,height=580,scrollbars=yes,resizable=yes,status=yes,screenx=5,screeny=5');\"",
								'yahoo_console'			=> "http://edit.yahoo.com/config/send_webmesg?.target=".$row['yahoo_im']."&amp;.src=pg",
								'email_console'			=> "onclick=\"window.open('".$this->basepath.'email/'.$row['member_id'].'/'."', '_blank', 'width=650,height=580,scrollbars=yes,resizable=yes,status=yes,screenx=5,screeny=5');\"",
							);
		
				$temp = $FNS->var_replace($swap, $temp);

				// ----------------------------------------
				//   Parse conditional pairs
				// ----------------------------------------
	
				foreach ($TMPL->var_cond as $val)
				{								
					// ----------------------------------------
					//   Conditional statements
					// ----------------------------------------
					
					// The $key variable contains the full contitional statement.
					// For example: if username != 'joe'
					
					// First, we'll remove "if" from the statement, otherwise,
					// eval() will return an error 
					
					$cond = preg_replace("/^if/", "", $val['0']);
					
					// Since we allow the following shorthand condition: {if username}
					// but it's not legal PHP, we'll correct it by adding:  != ''
					
					if ( ! ereg("\|", $cond))
					{                    
						if ( ! preg_match("/(\!=|==|<|>|<=|>=|<>)/s", $cond))
						{
							$cond .= " != ''";
						}
					}
													
					// ----------------------------------------
					//  Parse conditions in standard fields
					// ----------------------------------------
				
					if ( isset($row[$val['3']]))
					{       
						$cond =& str_replace($val['3'], "\$row['".$val['3']."']", $cond);
						  
						$cond =& str_replace("\|", "|", $cond);
								 
						eval("\$result = ".$cond.";");

						if ($result)
						{
							$temp =& preg_replace("/".LD.$val['0'].RD."(.*?)".LD.'\/if'.RD."/s", "\\1", $temp); 
						}
						else
						{
							$temp =& preg_replace("/".LD.$val['0'].RD."(.*?)".LD.'\/if'.RD."/s", "", $temp); 
						}   
					}
					// ------------------------------------------
					//  Parse conditions in custom member fields
					// ------------------------------------------

					elseif (isset($fields[$val['3']]))
					{
						if (isset($row['m_field_id_'.$fields[$val['3']]]))
						{
							$v = $row['m_field_id_'.$fields[$val['3']]];
										 
							$cond =& str_replace($val['3'], "\$v", $cond);
							
							$cond =& str_replace("\|", "|", $cond);
									 
							eval("\$result = ".$cond.";");
		
							if ($result)
							{
								$temp =& preg_replace("/".LD.$val['0'].RD."(.*?)".LD.'\/if'.RD."/s", "\\1", $temp); 
							}
							else
							{
								$temp =& preg_replace("/".LD.$val['0'].RD."(.*?)".LD.'\/if'.RD."/s", "", $temp); 
							}   
						}
					}                        
					

					// ----------------------------------------
					//   {if accept_email}
					// ----------------------------------------

					if (preg_match("/^if\s+accept_email.*/i", $val['0']))
					{ 
						if ($row['accept_user_email'] == 'n')
						{
							$temp =& preg_replace("/".LD.$val['0'].RD."(.+?)".LD.'\/if'.RD."/s", "", $temp); 
						}
						else
						{
							$temp =& preg_replace("/".LD.$val['0'].RD."(.+?)".LD.'\/if'.RD."/s", "\\1", $temp); 
						} 
					}
				
				}
				// END PAIRS
				
						
				// ----------------------------------------
				//  Manual replacements
				// ----------------------------------------
											
				$temp = str_replace(LD.'name'.RD, ($row['screen_name'] != '') ? $row['screen_name'] : $row['username'], $temp);
		
				// ----------------------------------------
				//  1:1 variables
				// ----------------------------------------
			
				foreach ($TMPL->var_single as $key => $val)
				{    
              
              
					// ----------------------------------------
					//  parse profile path
					// ----------------------------------------
					
					if (ereg("^profile_path", $key))
					{                       
						$temp =& $TMPL->swap_var_single(
															$key, 
															$FNS->create_url($FNS->extract_path($key).'/'.$row['member_id']), 
															$temp
														 );
					}
				
					// ----------------------------------------
					//  parse "last_visit" 
					// ----------------------------------------
					
					if (ereg("^last_visit", $key))
					{			
						$temp =& $TMPL->swap_var_single(
															$key, 
															($row['last_visit'] > 0) ? $LOC->decode_date($val, $row['last_visit']) : '--', 
															$temp
														 );
					}
				  
					// ----------------------------------------
					//  parse "join_date" 
					// ----------------------------------------
					
					if (ereg("^join_date", $key))
					{        
						$temp =& $TMPL->swap_var_single(
															$key, 
															($row['join_date'] > 0) ? $LOC->decode_date($val, $row['join_date']) : '--', 
															$temp
														 );
					}
				
					
					// ----------------------------------------
					//  parse "last_entry_date" 
					// ----------------------------------------
					
					if (ereg("^last_entry_date", $key))
					{                     
						$temp =& $TMPL->swap_var_single(
															$key, 
															($row['last_entry_date'] > 0) ? $LOC->decode_date($val, $row['last_entry_date']) : '--', 
															$temp
														 );
					}
					
					
					// ----------------------------------------
					//  parse "recent_comment" 
					// ----------------------------------------
					
					if (ereg("^last_comment_date", $key))
					{                     
						$temp =& $TMPL->swap_var_single(
															$key, 
															($row['last_comment_date'] > 0) ? $LOC->decode_date($val, $row['last_comment_date']) : '--', 
															$temp
														 );
					}
					
					
					// ----------------------------------------
					//  parse "recent_comment" 
					// ----------------------------------------
					
					if (ereg("^last_comment_date", $key))
					{                     
						$temp =& $TMPL->swap_var_single(
															$key, 
															($row['last_comment_date'] > 0) ? $LOC->decode_date($val, $row['last_comment_date']) : '--', 
															$temp
														 );
					}
					
					
					// ----------------------------------------
					//  parse literal variables
					// ----------------------------------------
				
					if (isset($row[$val]))
					{                    
						$temp = $TMPL->swap_var_single($val, $row[$val], $temp);
					}
					
					// ----------------------------------------
					//  parse custom member fields
					// ----------------------------------------
	
					if ( isset($fields[$val]) AND isset($row['m_field_id_'.$fields[$val]]))
					{
						$temp =& $TMPL->swap_var_single(
															$val, 
															$row['m_field_id_'.$fields[$val]], 
															$temp
														  );
					}
					
				}
			
				// ----------------------------------------
				//  Language lines
				// ----------------------------------------
	
				foreach ($lines as $key => $val)
				{
					$temp = str_replace(LD."lang:".$key.RD, $LANG->line($val), $temp);
				}
			
			
				$str .= $temp;
			}
		}
		
		
		
		// ----------------------------------------
		//  Render the member group list
		// ----------------------------------------
		
		$english = array('Guests', 'Banned', 'Members', 'Pending', 'Super Admins');
		
		$query = $DB->query("SELECT group_id, group_title FROM exp_member_groups WHERE group_id != '2' AND group_id != '3' AND group_id != '4' order by group_title");
		
		$selected = ($group_id == 0) ? " selected='selected' " : '';

		$menu = "<option value='0'".$selected.">".$LANG->line('mbr_all_member_groups')."</option>\n";
				
		foreach ($query->result as $row)
		{
			$group_title = $row['group_title'];
		
            if (in_array($group_title, $english))
            {
                $group_title = $LANG->line(strtolower(str_replace(" ", "_", $group_title)));
            }
			
			$selected = ($group_id == $row['group_id']) ? " selected='selected' " : '';
					
			$menu .= "<option value='".$row['group_id']."'".$selected.">".$group_title."</option>\n";
		}
		
		$this->content = str_replace(LD.'group_id_options'.RD, $menu, $this->content);
		
		
		// ----------------------------------------
		//  Create the "Order By" menu
		// ----------------------------------------
		
		$selected = ($order_by == 'screen_name') ? " selected='selected' " : '';
		$menu = "<option value='screen_name'".$selected.">".$LANG->line('mbr_member_name')."</option>\n";
		
		$selected = ($order_by == 'total_comments') ? " selected='selected' " : '';
		$menu .= "<option value='total_comments'".$selected.">".$LANG->line('mbr_total_comments')."</option>\n";
		
		$selected = ($order_by == 'total_entries') ? " selected='selected' " : '';
		$menu .= "<option value='total_entries'".$selected.">".$LANG->line('mbr_total_entries')."</option>\n";
		
		$selected = ($order_by == 'join_date') ? " selected='selected' " : '';
		$menu .= "<option value='join_date'".$selected.">".$LANG->line('join_date')."</option>\n";

		$this->content = str_replace(LD.'order_by_options'.RD, $menu, $this->content);
		
		
		// ----------------------------------------
		//  Create the "Sort By" menu
		// ----------------------------------------
		
		$selected = ($sort_order == 'asc') ? " selected='selected' " : '';
		$menu = "<option value='asc'".$selected.">".$LANG->line('mbr_ascending')."</option>\n";
		
		$selected = ($sort_order == 'desc') ? " selected='selected' " : '';
		$menu .= "<option value='desc'".$selected.">".$LANG->line('mbr_descending')."</option>\n";
		
		$this->content = str_replace(LD.'sort_order_options'.RD, $menu, $this->content);
		
		
		// ----------------------------------------
		//  Create the "Row Limit" menu
		// ----------------------------------------
		
		$selected = ($row_limit == '10') ? " selected='selected' " : '';
		$menu  = "<option value='10'".$selected.">10</option>\n";
		$selected = ($row_limit == '20') ? " selected='selected' " : '';
		$menu .= "<option value='20'".$selected.">20</option>\n";
		$selected = ($row_limit == '30') ? " selected='selected' " : '';
		$menu .= "<option value='30'".$selected.">30</option>\n";
		$selected = ($row_limit == '40') ? " selected='selected' " : '';
		$menu .= "<option value='40'".$selected.">40</option>\n";
		$selected = ($row_limit == '50') ? " selected='selected' " : '';
		$menu .= "<option value='50'".$selected.">50</option>\n";
		
		$this->content = str_replace(LD.'row_limit_options'.RD, $menu, $this->content);


		// ----------------------------------------
		//  Put rendered chunk into template
		// ----------------------------------------

		if ($pager == '')
		{
			$this->content = preg_replace("/".LD."if paginate".RD.".*?".LD."\/if".RD."/s", '', $this->content);
		}
		else
		{
			$this->content = preg_replace("/".LD."if paginate".RD."(.*?)".LD."\/if".RD."/s", "\\1", $this->content);
		}
		
		$this->content = str_replace(LD."member_rows".RD,  $str, $this->content);
		
		$this->content = str_replace(LD.'paginate'.RD, $pager, $this->content);
		$this->content = str_replace(LD.'page_count'.RD, $page_count, $this->content);
		
		
			
		// ----------------------------------------
		//   Parse variables
		// ----------------------------------------
		
		$swap = array(
						'lang:name'				=> $LANG->line('mbr_name'),
						'lang:show'				=> $LANG->line('mbr_show'),
						'lang:sort'				=> $LANG->line('mbr_sort'),
						'lang:order'			=> $LANG->line('mbr_order'),
						'lang:rows'				=> $LANG->line('mbr_rows'),
						'lang:comments'			=> $LANG->line('mbr_comments'),
						'lang:last_visit'		=> $LANG->line('mbr_last_visit'),
						'lang:join_date'		=> $LANG->line('mbr_join_date'),
						'lang:email'			=> $LANG->line('mbr_email_short'),
						'lang:aol'				=> $LANG->line('mbr_aol_short'),
						'lang:icq'				=> $LANG->line('mbr_icq_short'),
						'lang:msn'				=> $LANG->line('mbr_msn_short'),
						'lang:yahoo'			=> $LANG->line('mbr_yahoo_short'),
						'lang:url'				=> $LANG->line('mbr_www'),
						'image_path'			=> $PREFS->ini('member_images', 1)
					);
	
		
		$this->content = $FNS->var_replace($swap, $this->content);
		
	}
	// END




    // ----------------------------------------
    //  Member Profile
    // ----------------------------------------

    function public_profile()
    {
		global $IN, $TMPL, $SESS, $LANG, $OUT, $DB, $FNS, $PREFS, $LOC;
				        		
        // ----------------------------------------
        //  Can the user view profiles?
        // ----------------------------------------
				
		if ($SESS->userdata['can_view_profiles'] == 'n')
		{
			return $OUT->show_user_error('general', array($LANG->line('mbr_not_allowed_to_view_profiles')));
		}
		
		$member_id = $IN->QSTR;
	
		if ( ! class_exists('Typography'))
		{
			require PATH_CORE.'core.typography'.EXT;
		}
		
		$TYPE = new Typography;
	
		
        $sql = " SELECT exp_members.weblog_id, 
						exp_members.tmpl_group_id, 
						exp_members.username, 
						exp_members.screen_name, 
						exp_members.email, 
						exp_members.url, 
						exp_members.location, 
						exp_members.occupation, 
						exp_members.interests, 
						exp_members.icq, 
						exp_members.aol_im, 
						exp_members.yahoo_im, 
						exp_members.msn_im, 
						exp_members.bio, 
						exp_members.join_date, 
						exp_members.last_visit, 
						exp_members.last_entry_date, 
						exp_members.last_comment_date, 
						exp_members.total_entries,
						exp_members.total_comments,
						exp_members.language, 
						exp_members.timezone, 
						exp_members.daylight_savings, 
						exp_members.bday_d,
						exp_members.bday_m,
						exp_members.bday_y,
						exp_members.accept_user_email,
						exp_member_groups.*
                  		FROM exp_members, exp_member_groups 
						WHERE  member_id = '".$member_id."'
                     	AND exp_members.group_id = exp_member_groups.group_id
                     	AND exp_members.group_id != '2'
                     	AND exp_members.group_id != '3'
                     	AND exp_members.group_id != '4'";
        

		$query = $DB->query($sql);
		
		if ($query->num_rows == 0)
		{
			return $this->content = '';
		}
		
		
		$this->content = $this->MS->public_profile();
		
		// ----------------------------------------
		//   Parse variables
		// ----------------------------------------
				
		
		$swap = array(
							'email_console'				=> "onclick=\"window.open('".$this->basepath.'email/'.$member_id.'/'."', '_blank', 'width=650,height=580,scrollbars=yes,resizable=yes,status=yes,screenx=5,screeny=5');\"",
							'aim_console'				=> "onclick=\"window.open('".$this->basepath.'aim/'.$member_id.'/'."', '_blank', 'width=240,height=360,scrollbars=yes,resizable=yes,status=yes,screenx=5,screeny=5');\"",
							'icq_console'				=> "onclick=\"window.open('".$this->basepath.'icq/'.$member_id.'/'."', '_blank', 'width=650,height=580,scrollbars=yes,resizable=yes,status=yes,screenx=5,screeny=5');\"",
							'yahoo_console'				=> "http://edit.yahoo.com/config/send_webmesg?.target=".$query->row['yahoo_im']."&amp;.src=pg",
							'lang:member_profile'		=> $LANG->line('mbr_member_profile'),
							'lang:member_group'			=> $LANG->line('mbr_member_group'),
							'lang:last_visit'			=> $LANG->line('mbr_last_visit'),
							'lang:most_recent_entry'	=> $LANG->line('mbr_most_recent_entry'),
							'lang:most_recent_comment'	=> $LANG->line('mbr_most_recent_comment'),
							'lang:join_date'			=> $LANG->line('mbr_join_date'),
							'lang:total_entries'		=> $LANG->line('mbr_total_entries'),
							'lang:total_comments'		=> $LANG->line('mbr_total_comments'),
							'lang:member_timezone'		=> $LANG->line('mbr_member_timezone'),
							'lang:member_local_time'	=> $LANG->line('mbr_member_local_time'),
							'lang:email'				=> $LANG->line('mbr_email'),
							'lang:email_address'		=> $LANG->line('mbr_email_address'),
							'lang:aol_im'				=> $LANG->line('mbr_aol_im'),
							'lang:icq'					=> $LANG->line('mbr_icq'),
							'lang:bio'					=> $LANG->line('mbr_bio'),
							'lang:msn'					=> $LANG->line('mbr_msn'),
							'lang:yahoo'				=> $LANG->line('mbr_yahoo'),
							'lang:url'					=> $LANG->line('mbr_url'),
							'lang:location'				=> $LANG->line('mbr_location'),
							'lang:birthday'				=> $LANG->line('mbr_birthday'),
							'lang:interests'			=> $LANG->line('mbr_interests'),
							'lang:occupation'			=> $LANG->line('mbr_occupation'),
							'lang:username'				=> $LANG->line('mbr_username'),
							'lang:lang:screen_name'		=> $LANG->line('mbr_screen_name'),
							'image_path'				=> $PREFS->ini('member_images', 1)
						);
		
		
		$this->content = $FNS->var_replace($swap, $this->content);
		
		
		$TMPL->assign_variables($this->content, '/');				

		// ----------------------------------------
		//   Parse conditional pairs
		// ----------------------------------------

		foreach ($TMPL->var_cond as $val)
		{
			// ----------------------------------------
			//   Conditional statements
			// ----------------------------------------
			
			// The $key variable contains the full contitional statement.
			// For example: if username != 'joe'
			
			// First, we'll remove "if" from the statement, otherwise,
			// eval() will return an error 
			
			$cond = preg_replace("/^if/", "", $val['0']);
			
			// Since we allow the following shorthand condition: {if username}
			// but it's not legal PHP, we'll correct it by adding:  != ''
			
			if ( ! ereg("\|", $cond))
			{                    
				if ( ! preg_match("/(\!=|==|<|>|<=|>=|<>)/s", $cond))
				{
					$cond .= " != ''";
				}
			}

			if ( isset($query->row[$val['3']]))
			{       
				$cond =& str_replace($val['3'], "\$query->row['".$val['3']."']", $cond);
				  
				$cond =& str_replace("\|", "|", $cond);
						 
				eval("\$result = ".$cond.";");
				
									
				if ($result)
				{
					$this->content =& preg_replace("/".LD.$val['0'].RD."(.*?)".LD.'\/if'.RD."/s", "\\1", $this->content); 
				}
				else
				{
					$this->content =& preg_replace("/".LD.$val['0'].RD."(.*?)".LD.'\/if'.RD."/s", "", $this->content); 
				}										
			}
			
			// ----------------------------------------
			//   {if accept_email}
			// ----------------------------------------
		

			if (preg_match("/^if\s+accept_email.*/i", $val['0']))
			{ 
				if ($query->row['accept_user_email'] == 'n')
				{
					$this->content =& preg_replace("/".LD.$val['0'].RD."(.+?)".LD.'\/if'.RD."/s", "", $this->content); 
				}
				else
				{
					$this->content =& preg_replace("/".LD.$val['0'].RD."(.+?)".LD.'\/if'.RD."/s", "\\1", $this->content); 
				} 
			}		
				
		}
		// END CONDITIONAL PAIRS
		
					
 
		// ----------------------------------------
		//   Parse "single" variables
		// ----------------------------------------

		foreach ($TMPL->var_single as $key => $val)
		{
              		
			// ----------------------------------------
			//  parse "last_visit" 
			// ----------------------------------------
			
			if (ereg("^last_visit", $key))
			{			
				$this->content =& $TMPL->swap_var_single(
													$key, 
													($query->row['last_visit'] > 0) ? $LOC->decode_date($val, $query->row['last_visit']) : '', 
													$this->content
												 );
			}
		  
			// ----------------------------------------
			//  parse "join_date" 
			// ----------------------------------------
			
			if (ereg("^join_date", $key))
			{                     
				$this->content =& $TMPL->swap_var_single(
													$key, 
													($query->row['join_date'] > 0) ? $LOC->decode_date($val, $query->row['join_date']) : '', 
													$this->content
												 );
			}
		
			
			// ----------------------------------------
			//  parse "last_entry_date" 
			// ----------------------------------------
			
			if (ereg("^last_entry_date", $key))
			{                     
				$this->content =& $TMPL->swap_var_single(
													$key, 
													($query->row['last_entry_date'] > 0) ? $LOC->decode_date($val, $query->row['last_entry_date']) : '', 
													$this->content
												 );
			}
			
			
			// ----------------------------------------
			//  parse "recent_comment" 
			// ----------------------------------------
			
			if (ereg("^last_comment_date", $key))
			{                     
				$this->content =& $TMPL->swap_var_single(
													$key, 
													($query->row['last_comment_date'] > 0) ? $LOC->decode_date($val, $query->row['last_comment_date']) : '', 
													$this->content
												 );
			}
			
					
					
			// ----------------------
			//  {name}
			// ----------------------
			
			$name = ( ! $query->row['screen_name']) ? $query->row['username'] : $query->row['screen_name'];
			
			if ($key == "name")
			{
				$this->content =& $TMPL->swap_var_single($val, $name, $this->content);
			}
						

			// ----------------------
			//  {member_group}
			// ----------------------
			
			if ($key == "member_group")
			{
				$this->content =& $TMPL->swap_var_single($val, $query->row['group_title'], $this->content);
			}
			
			// ----------------------
			//  {email}
			// ----------------------
			
			if ($key == "email")
			{
				if ( ! class_exists('Typography'))
				{
					require PATH_CORE.'core.typography'.EXT;
				}
					
				$TYPE = new Typography;
				
				$email = $TYPE->encode_email($query->row['email']);
				
				$this->content =& $TMPL->swap_var_single($val, $email, $this->content);
			}

			
			// ----------------------
			//  {birthday}
			// ----------------------
			
			if ($key == "birthday")
			{
				$birthday = '';
				
				if ($query->row['bday_m'] != '' AND $query->row['bday_m'] != 0)
				{
					$month = (strlen($query->row['bday_m']) == 1) ? '0'.$query->row['bday_m'] : $query->row['bday_m'];
				
					$m = $LOC->localize_month($month);
				
					$birthday .= $m['1'];
					
					if ($query->row['bday_d'] != '' AND $query->row['bday_d'] != 0)
					{
						$birthday .= ' '.$query->row['bday_d'];
					}
				}
		
				if ($query->row['bday_y'] != '' AND $query->row['bday_y'] != 0)
				{
					if ($birthday != '')
					{
						$birthday .= ', ';
					}
				
					$birthday .= $query->row['bday_y'];
				}
				
				if ($birthday == '')
				{
					$birthday = '';
				}
			
			
				$this->content =& $TMPL->swap_var_single($val, $birthday, $this->content);
			}
			
			
			// ----------------------
			//  {timezone}
			// ----------------------
			
			if ($key == "timezone")
			{				
				$timezone = ($query->row['timezone'] != '') ? $LANG->line($query->row['timezone']) : ''; 
				
				$this->content =& $TMPL->swap_var_single($val, $timezone, $this->content);
			}
	
			
			// ----------------------
			//  {local_time}
			// ----------------------
			
			if (ereg("^local_time", $key))
			{           
				$zones = $LOC->zones();

				$time = (isset($zones[$query->row['timezone']])) ? $LOC->decode_date($val, $LOC->now += $zones[$query->row['timezone']]*86400) : '';		
			          
				$this->content =& $TMPL->swap_var_single($key, $time, $this->content);
			}
			
			
			// ----------------------
			//  {bio}
			// ----------------------
			
			if (ereg("^bio$", $key))
			{           
				$bio = $TYPE->parse_type($query->row[$val], 
															 array(
																		'text_format'   => 'xhtml',
																		'html_format'   => 'safe',
																		'auto_links'    => 'y',
																		'allow_img_url' => 'n'
																   )
															);
			          
				$this->content =& $TMPL->swap_var_single($key, $bio, $this->content);
			}
			
			// ----------------------------------------
			//  parse basic fields (username, screen_name, etc.)
			// ----------------------------------------
			 
			if (isset($query->row[$val]))
			{                    
				$this->content =& $TMPL->swap_var_single($val, $query->row[$val], $this->content);
			}
		}        


        // -------------------------------------
        //  Do we have custom fields to show?
        // ------------------------------------

		// Grab the data for the particular member
									
		$sql = "SELECT m_field_id, m_field_name, m_field_label, m_field_fmt FROM  exp_member_fields ";
		
		if ($SESS->userdata['group_id'] != 1)
		{
			$sql .= " WHERE m_field_public = 'y' ";
		}
		
		$sql .= " ORDER BY m_field_order";
		
		$query = $DB->query($sql);
		
		if ($query->num_rows > 0)
		{
			$fnames = array();
			
			foreach ($query->result as $row)
			{
				$fnames[$row['m_field_name']] = $row['m_field_id'];
			}
			
			$result = $DB->query("SELECT * FROM  exp_member_data WHERE  member_id = '$member_id'");
	
			// ----------------------------------------
			//   Parse conditionals for custom fields
			// ----------------------------------------
	
			foreach ($TMPL->var_cond as $val)
			{                							
				$cond = preg_replace("/^if/", "", $val['0']);
				
				if ( ! ereg("\|", $cond))
				{                    
					if ( ! preg_match("/(\!=|==|<|>|<=|>=|<>)/s", $cond))
					{
						$cond .= " != ''";
					}
				}
	
				if (isset($fnames[$val['3']]))
				{
					$cond =& str_replace($val['3'], "\$result->row['m_field_id_".$fnames[$val['3']]."']", $cond);
					  
					$cond =& str_replace("\|", "|", $cond);
							 
					eval("\$rez = ".$cond.";");
										
					if ($rez)
					{
						$this->content =& preg_replace("/".LD.$val['0'].RD."(.*?)".LD.'\/if'.RD."/s", "\\1", $this->content); 
					}
					else
					{
						$this->content =& preg_replace("/".LD.$val['0'].RD."(.*?)".LD.'\/if'.RD."/s", "", $this->content); 
					}										
				}
							
			}
			// END CONDITIONALS
	
			// ----------------------------------------
			//   Parse single variables
			// ----------------------------------------
	
			foreach ($TMPL->var_single as $key => $val)
			{
				foreach ($query->result as $row)
				{
					if ($row['m_field_name'] == $key)
					{
						$field_data = ( ! isset( $result->row['m_field_id_'.$row['m_field_id']] )) ? '' : $result->row['m_field_id_'.$row['m_field_id']];
				
						if ($field_data != '')
						{
							$field_data = $TYPE->parse_type($field_data, 
																		 array(
																					'text_format'   => $row['m_field_fmt'],
																					'html_format'   => 'none',
																					'auto_links'    => 'n',
																					'allow_img_url' => 'n'
																			   )
																		);
						}
							
						$this->content =& $TMPL->swap_var_single($val, $field_data, $this->content);
					}
				}		
			}
	
			// ----------------------------------------
			//   Parse auto-generated "custom_fields"
			// ----------------------------------------
			
			$field_chunk = $this->MS->public_custom_profile_fields();
		
			// Is there a chunk to parse?
		
			if ($query->num_rows == 0)
			{
				$this->content = preg_replace("/{custom_profile_fields}/s", '', $this->content);
			}
			else
			{
				if ( ! class_exists('Typography'))
				{
					require PATH_CORE.'core.typography'.EXT;
				}
					
				$TYPE = new Typography;
				
				$str = '';
				
				foreach ($query->result as $row)
				{
					$temp = $field_chunk;
				
					$field_data = ( ! isset( $result->row['m_field_id_'.$row['m_field_id']] )) ? '' : $result->row['m_field_id_'.$row['m_field_id']];
			
					if ($field_data != '')
					{
						$field_data = $TYPE->parse_type($field_data, 
																	 array(
																				'text_format'   => $row['m_field_fmt'],
																				'html_format'   => 'safe',
																				'auto_links'    => 'y',
																				'allow_img_url' => 'n'
																		   )
																	);
																	
																	
																	
					}
			
			
					$temp = str_replace('{field_name}', $row['m_field_label'], $temp);
					$temp = str_replace('{field_data}', $field_data, $temp);
					
					$str .= $temp;
						
				}
				
				$this->content = preg_replace("/{custom_profile_fields}/s", $str, $this->content);
			}
		
		}
		// END  if ($quey->num_rows > 0)
				
		// ----------------------------------------
		//  Clean up left over variables
		// ----------------------------------------
		
		$this->content = preg_replace("/{custom_profile_fields}/s", '', $this->content);

		$this->content =& preg_replace("/".LD."if\s+.*?".RD.".*?".LD.'\/if'.RD."/s", "", $this->content); 
	}
	// END




    // ----------------------------------------
    //  Member Registration Form
    // ----------------------------------------

    function registration_form()
    {
        global $FNS, $LANG, $PREFS, $DB, $OUT, $SESS;
                
        // -------------------------------------
        //  Do we allow new member registrations?
        // ------------------------------------        
        
		if ($PREFS->ini('allow_member_registration') == 'n')
		{ 
                
			$data = array(	'title' 	=> $LANG->line('mbr_registration'),
							'heading'	=> $LANG->line('notice'),
							'content'	=> $LANG->line('mbr_registration_not_allowed'),
							'link'		=> array($FNS->fetch_site_index(), $PREFS->ini('site_name'))
						 );
				
			$OUT->show_message($data);	
        }
        
        // -------------------------------------
        //  Is the current user logged in?
        // ------------------------------------        
        
		if ($SESS->userdata['member_id'] != 0)
		{ 
			return $OUT->show_user_error('general', array($LANG->line('mbr_you_are_registered')));
        }
        
        // -------------------------------------
        //  Fetch the registration form
        // ------------------------------------        
                   
		$reg_form = $this->MS->registration_form();

        // -------------------------------------
        //  Do we have custom fields to show?
        // ------------------------------------
        
        $query = $DB->query("SELECT * FROM  exp_member_fields WHERE m_field_reg = 'y' ORDER BY m_field_order");

        // If not, we'll kill the custom field variables from the template
        
        if ($query->num_rows == 0)
        {
            $reg_form = preg_replace("/{custom_fields}.*?{\/custom_fields}/s", "", $reg_form);
        }        
        else
        {
            // -------------------------------------
            //  Parse custom field data
            // ------------------------------------
            
            // First separate the chunk between the {custom_fields} variable pairs.
            
            $field_chunk = (preg_match("/{custom_fields}(.*?){\/custom_fields}/s", $reg_form, $match)) ? $match['1'] : '';
            
            // Next, separate the chunck between the {required} variable pairs
            
            $req_chunk   = (preg_match("/{required}(.*?){\/required}/s", $field_chunk, $match)) ? $match['1'] : '';
            
            
            // -------------------------------------
            //  Loop through the query result
            // ------------------------------------
            
            $str = '';
            
            foreach ($query->result as $row)
            {
                $field  = '';           
                $temp   = $field_chunk;
                
                
                // --------------------------------
                //  Replace {field_name}
                // --------------------------------
                
                $temp = str_replace("{field_name}", $row['m_field_label'], $temp);
                
                
                // --------------------------------
                //  Replace {required} pair
                // --------------------------------
                
                if ($row['m_field_required'] == 'y')
                {
                    $temp = preg_replace("/".LD."required".RD.".*?".LD."\/required".RD."/s", $req_chunk, $temp);
                }
                else
                {
                    $temp = preg_replace("/".LD."required".RD.".*?".LD."\/required".RD."/s", '', $temp);
                }                

                    
                // --------------------------------
                //  Parse input fields
                // --------------------------------
                
                // Set field width            

                $width = ( ! ereg("px", $row['m_field_width'])  AND ! ereg("%", $row['m_field_width'])) ? $row['m_field_width'].'px' : $row['m_field_width'];
                                                                                              

                //  Textarea fields
    
                if ($row['m_field_type'] == 'textarea')
                {   
                    $rows = ( ! isset($row['m_field_ta_rows'])) ? '10' : $row['m_field_ta_rows'];
    
                    $field = "<textarea style=\"width:{$width};\" name=\"m_field_id_".$row['m_field_id']."\"  cols='50' rows='{$rows}' class=\"textarea\" ></textarea>";
                }
                else
                {   
                    //  Text fields
                                 
                    if ($row['m_field_type'] == 'text')
                    {   
                        $maxlength = ($row['m_field_maxl'] == 0) ? '100' : $row['m_field_maxl'];   
                    
                        $field = "<input type=\"text\" name=\"m_field_id_".$row['m_field_id']."\" value=\"\" class=\"input\" maxlength=\"$maxlength\" size=\"40\" style=\"width:{$width};\" />";
                    }
                    elseif ($row['m_field_type'] == 'select')
                    {     
                    
                        //  Drop-down fields
                        
                        $select_list = trim($row['m_field_list_items']);
                    
                        if ($select_list != '')
                        {
                            $field = "<select name=\"m_field_id_".$row['m_field_id']."\" class=\"select\">";
                            
                            foreach (explode("\n", $select_list) as $v)
                            {   
                                $v = trim($v);
                                
                                 $field .= "<option value=\"$v\">$v</option>";
                            }
                            
                             $field .= "</select>";  
                        }                      
                    }
                }
                                
                $temp = str_replace("{field}", $field, $temp);

                $str .= $temp;
            }
                        
            $reg_form = preg_replace("/".LD."custom_fields".RD.".*?".LD."\/custom_fields".RD."/s", $str, $reg_form);
        }        

		
		$un_min_len = str_replace("%x", $PREFS->ini('un_min_len'), $LANG->line('mbr_username_length'));
		$pw_min_len = str_replace("%x", $PREFS->ini('pw_min_len'), $LANG->line('mbr_password_length'));
		
		
		// ----------------------------------------
		//   Parse languge lines
		// ----------------------------------------
		
		$swap = array(
							'lang:member_registration'		=> $LANG->line('mbr_member_registration'),
							'lang:terms_of_service'			=> $LANG->line('mbr_terms_of_service'),
							'lang:terms_accepted'			=> $LANG->line('mbr_i_accept'),
							'lang:username'					=> $LANG->line('mbr_username'),
							'lang:username_length'			=> $un_min_len,
							'lang:password'					=> $LANG->line('mbr_password'),	
							'lang:password_length'			=> $pw_min_len,
							'lang:url'						=> $LANG->line('mbr_your_url'),	
							'lang:email'					=> $LANG->line('mbr_email_address'),	
							'lang:screen_name'				=> $LANG->line('mbr_screen_name'),
							'lang:screen_name_explanation'	=> $LANG->line('mbr_screen_name_exp'),
							'lang:password_confirm'			=> $LANG->line('mbr_password_confirm'),	
							'lang:required_fields'			=> $LANG->line('mbr_required_fields'),
							'lang:submit'					=> $LANG->line('mbr_submit')
						);
		
		$reg_form = $FNS->var_replace($swap, $reg_form);
		
        // ----------------------------------------
        //  Generate Form declaration
        // ----------------------------------------
                
        $hidden_fields = array(
                                'ACT'	=> $FNS->fetch_action_id('Member', 'register_member'),
                                'RET'	=> $FNS->fetch_site_index(),
                              );            
     
     
        // ----------------------------------------
        //  Return the final rendered form
        // ----------------------------------------
        
        $this->content =  $FNS->form_declaration($hidden_fields)
               .$reg_form."\n"
               ."</form>";
    }
    // END




    // ----------------------------------------
    //  Register Member
    // ----------------------------------------

    function register_member()
    {
        global $IN, $DB, $SESS, $PREFS, $FNS, $LOC, $LANG, $OUT, $STAT, $REGX;
        
        
        // -------------------------------------
        //  Do we allow new member registrations?
        // ------------------------------------        
        
		if ($PREFS->ini('allow_member_registration') == 'n')
		{
			return false;
        }

        // ----------------------------------------
        // Is user banned?
        // ----------------------------------------
        
        if ($SESS->userdata['is_banned'] == TRUE)
		{            
            return $OUT->show_user_error('general', array($LANG->line('not_authorized')));
		}
		
		        
        // ----------------------------------------
        // Set the default globals
        // ----------------------------------------
        
        $default = array('username', 'password', 'email', 'screen_name', 'url', 'location');
        
        if ($_POST['screen_name'] == '')
        	$_POST['screen_name'] = $_POST['username'];
        
        foreach ($default as $val)
        {
        	if ( ! isset($_POST[$val])) $_POST[$val] = '';
        }
        
        // -------------------------------------
        //  Instantiate validation class
        // -------------------------------------

		if ( ! class_exists('Validate'))
		{
			require PATH_CORE.'core.validate'.EXT;
		}
		
		$VAL = new Validate(
								array( 
										'member_id'			=> '',
										'val_type'			=> 'new', // new or update
										'fetch_lang' 		=> TRUE, 
										'require_cpw' 		=> FALSE,
									 	'enable_log'		=> FALSE,
										'username'			=> $_POST['username'],
										'cur_username'		=> '',
										'screen_name'		=> $_POST['screen_name'],
										'cur_screen_name'	=> '',
										'password'			=> $_POST['password'],
									 	'password_confirm'	=> $_POST['password_confirm'],
									 	'cur_password'		=> '',
									 	'email'				=> $_POST['email'],
									 	'cur_email'			=> ''
									 )
							);
		
		$VAL->validate_username();
		$VAL->validate_screen_name();
		$VAL->validate_password();
		$VAL->validate_email();
				
        // -------------------------------------
        // Do we have any custom fields?
        // -------------------------------------
        
        $query = $DB->query("SELECT m_field_id, m_field_name, m_field_label, m_field_required FROM exp_member_fields WHERE m_field_reg = 'y'");
        
        $cust_errors = array();
        $cust_fields = array();
        
        if ($query->num_rows > 0)
        {
        	foreach ($query->result as $row)
        	{
                if (isset($_POST['m_field_id_'.$row['m_field_id']])) 
                {
                	if ($row['m_field_required'] == 'y' AND $_POST['m_field_id_'.$row['m_field_id']] == '')
                	{
						$cust_errors[] = $LANG->line('mbr_field_required').'&nbsp;'.$row['m_field_label'];
					}
					
					$cust_fields['m_field_id_'.$row['m_field_id']] = $_POST['m_field_id_'.$row['m_field_id']];
                }           
        	}
        }        
        
        
        if ($PREFS->ini('require_terms_of_service') == 'y')
        {
        	if ( ! isset($_POST['accept_terms']))
        	{
        	 	$cust_errors[] = $LANG->line('mbr_terms_of_service_required');
        	}
        }
                
		$errors = array_merge($VAL->errors, $cust_errors);

        // -------------------------------------
        //  Display error is there are any
        // -------------------------------------

         if (count($errors) > 0)
         {
			return $OUT->show_user_error('submission', $errors);
         }
         
         
        // -------------------------------------
        // Assign the base query data
        // -------------------------------------
        
        // Set member group
                        
        if ($PREFS->ini('req_mbr_activation') == 'manual' || $PREFS->ini('req_mbr_activation') == 'email')
        {
        	$data['group_id'] = 4;  // Pending
        }
        else
        {
        	if ($PREFS->ini('default_member_group') == '')
        	{
				$data['group_id'] = 4;  // Pending
        	}
        	else
        	{
				$data['group_id'] = $PREFS->ini('default_member_group');
        	}
        }       
                 
        $data['username']    = $_POST['username'];
        $data['password']    = $FNS->hash(stripslashes($_POST['password']));
        $data['ip_address']  = $IN->IP;
        $data['unique_id']   = $FNS->random('encrypt');
        $data['join_date']   = $LOC->now;
        $data['email']       = $_POST['email'];
        $data['screen_name'] = $_POST['screen_name'];
        $data['url']         = $REGX->prep_url($_POST['url']);
        
        // We generate an authorization code if the member needs to self-activate
        
		if ($PREFS->ini('req_mbr_activation') == 'email')
		{
			$data['authcode'] = $FNS->random('alpha', 10);
		}
		        
        // -------------------------------------
        // Insert basic member data
        // -------------------------------------

        $DB->query($DB->insert_string('exp_members', $data)); 
        
        $member_id = $DB->insert_id;
         
        // -------------------------------------
        // Insert custom fields
        // -------------------------------------

		$cust_fields['member_id'] = $member_id;
											   
		$DB->query($DB->insert_string('exp_member_data', $cust_fields));


        // -------------------------------------
        // Create a record in the member homepage table
        // -------------------------------------

		// This is only necessary if the user gains CP access, but we'll add the record anyway.            
                           
        $DB->query($DB->insert_string('exp_member_homepage', array('member_id' => $member_id)));
        
        // -------------------------------------
        // Update global member stats
        // -------------------------------------
        
		$STAT->update_member_stats();
		
		
        // -------------------------------------
        // Send admin notifications
        // -------------------------------------
	
		if ($PREFS->ini('new_member_notification') == 'y' AND $PREFS->ini('mbr_notification_emails') != '')
		{
			$name = ($data['screen_name'] != '') ? $data['screen_name'] : $data['username'];
            
			$swap = array(
							'name'					=> $name,
							'site_name'				=> $PREFS->ini('site_name'),
							'control_panel_url'		=> $PREFS->ini('cp_url')
						 );
			
			$template = $FNS->fetch_email_template('admin_notify_reg');
			
			$email_msg = $FNS->var_replace($swap, $template['data']);
                                    
			$notify_address = $REGX->remove_extra_commas($PREFS->ini('mbr_notification_emails'));
                        
            // ----------------------------
            //  Send email
            // ----------------------------
            
            if ( ! class_exists('EEmail'))
            {
				require PATH_CORE.'core.email'.EXT;
            }
                 
            $email = new EEmail;
            $email->wordwrap = true;
            $email->from($PREFS->ini('webmaster_email'));	
            $email->to($notify_address); 
            $email->subject($template['title']);	
            $email->message($REGX->entities_to_ascii($email_msg));		
            $email->Send();
		}
	
	
        // -------------------------------------
        // Send user notifications
        // -------------------------------------

		if ($PREFS->ini('req_mbr_activation') == 'email')
		{
			$qs = ($PREFS->ini('force_query_string') == 'y') ? '' : '?';        
			
			$action_id  = $FNS->fetch_action_id('Member', 'activate_member');
		
			$swap = array(
							'activation_url'	=> $FNS->fetch_site_index(0, 0).$qs.'ACT='.$action_id.'&id='.$data['authcode'],
							'site_name'			=> $PREFS->ini('site_name'),
							'site_url'			=> $PREFS->ini('site_url')
						 );
			
			$template = $FNS->fetch_email_template('mbr_activation_instructions');
			
			$email_msg = $FNS->var_replace($swap, $template['data']);
                        
			$notify_address = $data['email'];
                        
            // ----------------------------
            //  Send email
            // ----------------------------
            
            if ( ! class_exists('EEmail'))
            {
				require PATH_CORE.'core.email'.EXT;
            }
                 
            $email = new EEmail;
            $email->wordwrap = true;
            $email->from($PREFS->ini('webmaster_email'));	
            $email->to($notify_address); 
            $email->subject($template['title']);	
            $email->message($REGX->entities_to_ascii($email_msg));		
            $email->Send();
            
        	$message = $LANG->line('mbr_membership_instructions_email');		
		}
        elseif ($PREFS->ini('req_mbr_activation') == 'manual')
        {
        	$message = $LANG->line('mbr_admin_will_activate');
        }	
		else
		{
			// ----------------------------------------
			//  Log user in
			// ----------------------------------------
				
			$expire = 60*60*24*182;
					
			$FNS->set_cookie($SESS->c_uniqueid , $data['unique_id'], $expire);       
			$FNS->set_cookie($SESS->c_password , $data['password'],  $expire);   
        

			// ----------------------------------------
			// Create a new session
			// ----------------------------------------
			
			if ($PREFS->ini('user_session_type') == 'cs' || $PREFS->ini('user_session_type') == 's')
			{        
				$SESS->sdata['session_id'] = $FNS->random();  
				$SESS->sdata['member_id']  = $member_id;  
				$SESS->sdata['last_visit'] = $LOC->now;  
				
				$FNS->set_cookie($SESS->c_session , $SESS->sdata['session_id'], $SESS->session_length);   
				
				$sql = $DB->insert_string('exp_sessions', $SESS->sdata);   
				
				$DB->query($sql);          
			}
			
			// ----------------------------------------
			//  Update existing session variables
			// ----------------------------------------
			
			$SESS->userdata['username']  = $data['username'];
			$SESS->userdata['member_id'] = $member_id;
			
		
			// ----------------------------------------
			//  Update stats
			// ----------------------------------------
			
			$data = array(
							'member_id'		=> $SESS->userdata['member_id'],
							'date'			=> $LOC->now,
							'anon'			=> ''
						);
	
	
			$DB->query($DB->update_string('exp_online_users', $data, "ip_address='$IN->IP'"));
					
        	$message = $LANG->line('mbr_your_are_logged_in');
		}
    	
    	
        
        // ----------------------------------------
        //  Build the message
        // ----------------------------------------
		
		$site_name = ($PREFS->ini('site_name') == '') ? $LANG->line('back') : $PREFS->ini('site_name');
		
		$return = $PREFS->ini('site_url');
                
        $data = array(	'title' 	=> $LANG->line('mbr_registration_complete'),
        				'heading'	=> $LANG->line('thank_you'),
        				'content'	=> $LANG->line('mbr_registration_completed')."\n\n".$message,
        				'redirect'	=> '',
        				'link'		=> array($return, $site_name)
        			 );
			
		$OUT->show_message($data);
	}
	// END




    // ----------------------------------------
    //  Member Self-Activation
    // ----------------------------------------

	function activate_member()
	{
        global $IN, $FNS, $OUT, $DB, $PREFS, $SESS, $REGX, $LANG;

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
                        
			$data = array(	'title' 	=> $LANG->line('mbr_activation'),
							'heading'	=> $LANG->line('error'),
							'content'	=> $LANG->line('invalid_url'),
							'link'		=> array($FNS->fetch_site_index(), $site_name)
						 );
        
			$OUT->show_message($data);
        }
        
        
        // ----------------------------------------
        //   Set the member group
        // ----------------------------------------
        
        $group_id = $PREFS->ini('default_member_group');
        
        $DB->query("UPDATE exp_members SET group_id = '$group_id' WHERE authcode = '$id'");        
        
        if ($DB->affected_rows == 0)
        {
			$data = array(	'title' 	=> $LANG->line('mbr_activation'),
							'heading'	=> $LANG->line('error'),
							'content'	=> $LANG->line('mbr_problem_activating'),
							'link'		=> array($FNS->fetch_site_index(), $site_name)
						 );
        
			$OUT->show_message($data);        
        }
        
        $DB->query("UPDATE exp_members SET authcode = '' WHERE authcode = '$id'");     
        
        
        // ----------------------------------------
        //  Show success message
        // ----------------------------------------
                
		$data = array(	'title' 	=> $LANG->line('mbr_activation'),
						'heading'	=> $LANG->line('thank_you'),
						'content'	=> $LANG->line('mbr_activation_success')."\n\n".$LANG->line('mbr_may_now_log_in'),
						'link'		=> array($FNS->fetch_site_index(), $site_name)
					 );
										
		$OUT->show_message($data);
	}
	// END




    // ----------------------------------------
    //  Login Page
    // ----------------------------------------
    
	function login_form($ret = '-2')
	{
		global $IN, $FNS, $LANG, $PREFS;
		
		$login_form = $this->MS->login_form();
		
		if ($IN->QSTR == 'nbc')
		{
			$this->enable_breadcrumb = FALSE;
		}
				
		if ($PREFS->ini('user_session_type') != 'c')
		{
			$login_form = preg_replace("/{if\s+auto_login}.*?{\/if}/s", '', $login_form);
		}
		else
		{
			$login_form = preg_replace("/{if\s+auto_login}(.*?){\/if}/s", "\\1", $login_form);
		}
				
		$link_title = (isset($this->map[$this->request]) AND $this->map[$this->request] != 'mbr_login') ? $LANG->line($this->map[$this->request]) : '';
		
        $hidden_fields = array(
                                'ACT'	=> $FNS->fetch_action_id('Member', 'member_login'),
                                'RET'	=> $ret,
                                'LTIT'	=> $link_title
                              );
                              
        if ($this->enable_breadcrumb == FALSE)
        {
        	$login_form = preg_replace("#{path=member/forgot}#", '{path=member/forgot/nbc/}', $login_form);
        }           
                             			
		$swap = array(
						'form_declaration'		=>	$FNS->form_declaration($hidden_fields),
						'lang:username'			=>	$LANG->line('username'),
						'lang:password'			=>	$LANG->line('password'),
						'lang:submit'			=>	$LANG->line('submit'),
						'lang:auto_login'		=>	$LANG->line('mbr_auto_login'),
						'lang:forgot_password'	=>	$LANG->line('mbr_forgot_password'),
						'lang:back_to_main'		=>	$LANG->line('mbr_back_to_main'),
						'lang:show_name'		=>	$LANG->line('mbr_show_name'),
						
					 );
	
		$this->content = $FNS->var_replace($swap, $login_form);
	}
	// END
	


    // ----------------------------------------
    //  Member Login
    // ----------------------------------------

    function member_login()
    {
        global $IN, $LANG, $SESS, $PREFS, $OUT, $LOC, $FNS, $DB;
        
        
        // ----------------------------------------
        // Is user banned?
        // ----------------------------------------
        
        if ($SESS->userdata['is_banned'] == TRUE)
		{            
            return $OUT->show_user_error('general', array($LANG->line('not_authorized')));
		}
		
		
        $LANG->fetch_language_file('login');
        
        // ----------------------------------------
        // Error trapping
        // ----------------------------------------
                
        $errors = array();
                

        // ----------------------------------------
        // No username/password?  Bounce them...
        // ----------------------------------------
    
        if ( ! $IN->GBL('username', 'POST') || ! $IN->GBL('password', 'POST'))
        {
			$OUT->show_user_error('submission', array($LANG->line('mbr_form_empty')));        
        }
        
        
        // ----------------------------------------
        // Is IP and User Agent required for login?
        // ----------------------------------------
    
        if ($PREFS->ini('require_ip_for_login') == 'y')
        {
        	if ($SESS->userdata['ip_address'] == '' || $SESS->userdata['user_agent'] == '')
        	{
				$OUT->show_user_error('general', array($LANG->line('unauthorized_request')));        
           	}
        }
                
        // ----------------------------------------
        //  Check password lockout status
        // ----------------------------------------
		
		if ($SESS->check_password_lockout() === TRUE)
		{
			$line = $LANG->line('password_lockout_in_effect');
		
			$line = str_replace("%x", $PREFS->ini('password_lockout_interval'), $line);
		
			$OUT->show_user_error('general', array($line));        
		}
		
				        
        // ----------------------------------------
        // Fetch member data
        // ----------------------------------------

        $sql = "SELECT exp_members.password, exp_members.unique_id, exp_members.member_id, exp_members.group_id
                FROM   exp_members, exp_member_groups
                WHERE  username = '".$DB->escape_str($IN->GBL('username', 'POST'))."'
                AND    exp_members.group_id = exp_member_groups.group_id";
                
        $query = $DB->query($sql);
               
        
        // ----------------------------------------
        //  Invalid Username
        // ----------------------------------------

        if ($query->num_rows == 0)
        {
        	$SESS->save_password_lockout();
        	
			$OUT->show_user_error('submission', array($LANG->line('no_username')));        
        }
                
        // ----------------------------------------
        //  Is the member account pending?
        // ----------------------------------------

        if ($query->row['group_id'] == 4)
        { 
			$OUT->show_user_error('general', array($LANG->line('mbr_account_not_active')));        
        }
        
                
        // ----------------------------------------
        //  Check password
        // ----------------------------------------

        $password = $FNS->hash(stripslashes($IN->GBL('password', 'POST')));
        
        if ($query->row['password'] != $password)
        {
            // To enable backward compatibility with pMachine we'll test to see 
            // if the password was encrypted with MD5.  If so, we will encrypt the
            // password using SHA1 and update the member's info.
            
            if ($query->row['password'] == md5(stripslashes($IN->GBL('password', 'POST'))))
            {
                $sql = "UPDATE exp_members 
                        SET    password = '".$password."' 
                        WHERE  member_id = '".$query->row['member_id']."' ";
                        
                $DB->query($sql);
            }
            else
            {
				// ----------------------------------------
				//  Invalid password
				// ----------------------------------------
					
        		$SESS->save_password_lockout();
	
				$errors[] = $LANG->line('no_password');        
            }
        }
        
        // --------------------------------------------------
        // Do we allow multiple logins on the same account?
        // --------------------------------------------------
        
        if ($PREFS->ini('allow_multi_logins') == 'n')
        {
            // Kill old sessions first
        
            $SESS->gc_probability = 100;
            
            $SESS->delete_old_sessions();
        
            $expire = time() - $SESS->session_length;
            
            // See if there is a current session

            $result = $DB->query("SELECT ip_address, user_agent 
                                  FROM   exp_sessions 
                                  WHERE  member_id  = '".$query->row['member_id']."'
                                  AND    last_visit > $expire");
                                
            // If a session exists, trigger the error message
                               
            if ($result->num_rows == 1)
            {
                if ($SESS->sdata['ip_address'] != $result->row['ip_address'] || 
                    $SESS->sdata['user_agent'] != $result->row['user_agent'] )
                {
					$errors[] = $LANG->line('multi_login_warning');        
                }               
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
        //  Set cookies
        // ----------------------------------------
        
        // Set cookie expiration to 6 months if the "remember me" button is clicked

        $expire = ( ! $IN->GBL('auto_login', 'POST')) ? '0' : 60*60*24*182;

        $FNS->set_cookie($SESS->c_uniqueid , $query->row['unique_id'], $expire);       
        $FNS->set_cookie($SESS->c_password , $password,  $expire);   
        
        // Does the user want to remain anonymous?
        
        if ( ! $IN->GBL('anon', 'POST')) 
        {
            $FNS->set_cookie($SESS->c_anon , 1,  $expire);
            
            $anon = 'y';            
        }
        else
        { 
            $FNS->set_cookie($SESS->c_anon);
                   
            $anon = '';
        }

        // ----------------------------------------
        // Create a new session
        // ----------------------------------------
        
        if ($PREFS->ini('user_session_type') == 'cs' || $PREFS->ini('user_session_type') == 's')
        {        
            $SESS->sdata['session_id'] = $FNS->random();  
            $SESS->sdata['member_id']  = $query->row['member_id'];  
            $SESS->sdata['last_visit'] = $LOC->now;  
            
            $FNS->set_cookie($SESS->c_session , $SESS->sdata['session_id'], $SESS->session_length);   
            
            $sql = $DB->insert_string('exp_sessions', $SESS->sdata);   
            
            $DB->query($sql);          
        }
        
        // ----------------------------------------
        //  Update existing session variables
        // ----------------------------------------
        
        $SESS->userdata['username']  = $IN->GBL('username', 'POST');
        $SESS->userdata['member_id'] = $query->row['member_id'];
        
    
        // ----------------------------------------
        //  Update stats
        // ----------------------------------------
        
		$data = array(
						'member_id'		=> $SESS->userdata['member_id'],
						'date'			=> $LOC->now,
						'anon'			=> $anon
					);


        $DB->query($DB->update_string('exp_online_users', $data, "ip_address='$IN->IP'"));
        
               
        // ----------------------------------------
        //  Delete old password lockouts
        // ----------------------------------------
        
		$SESS->delete_password_lockout();
	
        // ----------------------------------------
        //  Build success message
        // ----------------------------------------
		
		$return = $FNS->form_backtrack();

		$site_name = ($PREFS->ini('site_name') == '') ? $LANG->line('back') : $PREFS->ini('site_name');
		
		if (isset($_POST['LTIT']) AND $_POST['LTIT'] != '')
		{
			$site_name = $_POST['LTIT'];
		}
                
        $data = array(	'title' 	=> $LANG->line('mbr_login'),
        				'heading'	=> $LANG->line('thank_you'),
        				'content'	=> $LANG->line('mbr_you_are_logged_in'),
        				'redirect'	=> $return,
        				'rate'		=> 5,
        				'link'		=> array($return, $site_name)
        			 );
			
		$OUT->show_message($data);
    }
    // END




    // ----------------------------------------
    //  Member Logout
    // ----------------------------------------

    function member_logout()
    {
        global $PREFS, $IN, $LANG, $SESS, $OUT, $FNS, $DB;
        
        // ----------------------------------------
        // Kill the session and cookies
        // ----------------------------------------        

        $DB->query("DELETE FROM exp_sessions WHERE session_id = '".$SESS->sdata['session_id']."'");
        
        $DB->query("DELETE FROM exp_online_users WHERE ip_address = '$IN->IP'");
        
        $FNS->set_cookie($SESS->c_uniqueid);       
        $FNS->set_cookie($SESS->c_password);   
        $FNS->set_cookie($SESS->c_session);   
        $FNS->set_cookie($SESS->c_anon);   

        // ----------------------------------------
        //  Build success message
        // ----------------------------------------

        $data = array(	'title' 	=> $LANG->line('mbr_login'),
        				'heading'	=> $LANG->line('thank_you'),
        				'content'	=> $LANG->line('mbr_you_are_logged_out'),
        				'redirect'	=> $PREFS->ini('site_url'),
        				'rate'		=> 5,
        				'link'		=> array($PREFS->ini('site_url'), $PREFS->ini('site_name'))
        			 );
					
		$OUT->show_message($data);
    }
    // END




    // ----------------------------------------
    //  Member Forgot Password Form
    // ----------------------------------------

    function forgot_pw_form($ret = '-3')
    {
		global $IN, $FNS, $LANG, $PREFS;
		
		
		$forgot_form = $this->MS->forgot_form();
		
		if ($IN->QSTR == 'nbc')
		{
			$this->enable_breadcrumb = FALSE;
			
        	$forgot_form = preg_replace("#{path=member/login}#", '{path=member/login/nbc/}', $forgot_form);
		}
								
        $hidden_fields = array(
                                'ACT'   => $FNS->fetch_action_id('Member', 'retrieve_password'),
                                'RET'	=> $ret
                              );            
                             			
		$swap = array(
						'form_declaration'		=>	$FNS->form_declaration($hidden_fields),
						'lang:your_email'		=>	$LANG->line('mbr_your_email'),
						'lang:back_to_login'	=>	$LANG->line('mbr_back_to_login'),
						'lang:back_to_main'		=>	$LANG->line('mbr_back_to_main'),
						'lang:submit'			=>	$LANG->line('submit')						
					 );
		
	
		$this->content = $FNS->var_replace($swap, $forgot_form);
    }
    // END



    // ----------------------------------------
    //  Retreive Forgotten Password
    // ----------------------------------------

    function retrieve_password()
    {
        global $LANG, $PREFS, $SESS, $REGX, $FNS, $DSP, $IN, $DB, $OUT;
                
        // ----------------------------------------
        // Is user banned?
        // ----------------------------------------
        
        if ($SESS->userdata['is_banned'] == TRUE)
		{            
            return $OUT->show_user_error('general', array($LANG->line('not_authorized')));
		}
		
        // ----------------------------------------
        //  Error trapping
        // ----------------------------------------
        
        if ( ! $address = $IN->GBL('email', 'POST'))
        {
			return $OUT->show_user_error('submission', array($LANG->line('no_email')));
        }
        
        if ( ! $REGX->valid_email($address))
        {
			return $OUT->show_user_error('submission', array($LANG->line('invalid_email_address')));
        }
        
		$address = strip_tags($address);
        
        // Fetch user data
        
        $sql = "SELECT member_id, username FROM exp_members WHERE email ='".$DB->escape_str($address)."'";
        
        $query = $DB->query($sql);
        
        if ($query->num_rows == 0)
        {
			return $OUT->show_user_error('submission', array($LANG->line('no_email_found')));
        }
        
        $member_id = $query->row['member_id'];
        $username  = $query->row['username'];
        
        // Kill old data from the reset_password field
        
        $time = time() - (60*60*24);
        
        $DB->query("DELETE FROM exp_reset_password WHERE date < $time || member_id = '$member_id'");
        
        // Create a new DB record with the temporary reset code
        
        $rand = $FNS->random('alpha', 8);
                
        $data = array('member_id' => $member_id, 'resetcode' => $rand, 'date' => time());
         
        $DB->query($DB->insert_string('exp_reset_password', $data));
        
        // Buid the email message       
        
        $qs = ($PREFS->ini('force_query_string') == 'y') ? '' : '?';    
        		
		$swap = array(
						'name'		=> $username,
						'reset_url'	=> $FNS->fetch_site_index(0, 0).$qs.'ACT='.$FNS->fetch_action_id('Member', 'reset_password').'&id='.$rand,
						'site_name'	=> $PREFS->ini('site_name'),
						'site_url'	=> $PREFS->ini('site_url')
					 );
		
		$template = $FNS->fetch_email_template('forgot_password_instructions');
		
		$email_msg = $FNS->var_replace($swap, $template['data']);
                 
        // Instantiate the email class
             
        require PATH_CORE.'core.email'.EXT;
        
        $email = new EEmail;
        $email->wordwrap = true;
        $email->from($address);	
        $email->to($address); 
        $email->subject($template['title']);	
        $email->message($email_msg);	
        
        if ( ! $email->Send())
        {
			return $OUT->show_user_error('submission', array($LANG->line('error_sending_email')));
        } 

        // ----------------------------------------
        //  Build success message
        // ----------------------------------------
        
		$site_name = ($PREFS->ini('site_name') == '') ? $LANG->line('back') : $PREFS->ini('site_name');
		                
        $data = array(	'title' 	=> $LANG->line('mbr_login'),
        				'heading'	=> $LANG->line('thank_you'),
        				'content'	=> $LANG->line('forgotten_email_sent'),
        				'link'		=> array($FNS->form_backtrack(), $site_name)
        			 );
			
		$OUT->show_message($data);
	}
	// END




	// ----------------------------------------
	//  Reset the user's password
	// ----------------------------------------

	function reset_password()
	{
        global $LANG, $PREFS, $SESS, $FNS, $DSP, $IN, $OUT, $DB;
        
        // ----------------------------------------
        // Is user banned?
        // ----------------------------------------
        
        if ($SESS->userdata['is_banned'] == TRUE)
		{            
            return $OUT->show_user_error('general', array($LANG->line('not_authorized')));
		}
               
        
        if ( ! $id = $IN->GBL('id'))
        {
			return $OUT->show_user_error('submission', array($LANG->line('mbr_no_reset_id')));
        }
                
        $time = time() - (60*60*24);
                   
        // Get the member ID from the reset_password field   
                
        $query = $DB->query("SELECT member_id FROM exp_reset_password WHERE resetcode ='".$DB->escape_str($id)."' and date > $time");
        
        if ($query->num_rows == 0)
        {
			return $OUT->show_user_error('submission', array($LANG->line('mbr_id_not_found')));
        }
        
        $member_id = $query->row['member_id'];
                
        // Fetch the user data
        
        $sql = "SELECT username, email FROM exp_members WHERE member_id ='$member_id'";
        
        $query = $DB->query($sql);
        
        if ($query->num_rows == 0)
        {
            return false;
        }
        
        $address   = $query->row['email'];
        $username  = $query->row['username'];
                
        $rand = $FNS->random('alpha', 8);
        
        // Update member's password
        
        $sql = "UPDATE exp_members SET password = '".$FNS->hash($rand)."' WHERE member_id = '$member_id'";
       
        $DB->query($sql);
        
        // Kill old data from the reset_password field
        
        $DB->query("DELETE FROM exp_reset_password WHERE date < $time || member_id = '$member_id'");
                
        // Buid the email message    
        
		$swap = array(
						'name'		=> $username,
						'username'	=> $username,
						'password'	=> $rand,
						'site_name'	=> $PREFS->ini('site_name'),
						'site_url'	=> $PREFS->ini('site_url')
					 );
		
		$template = $FNS->fetch_email_template('reset_password_notification');
		
		$email_msg = $FNS->var_replace($swap, $template['data']);

        // Instantiate the email class
             
        require PATH_CORE.'core.email'.EXT;
        
        $email = new EEmail;
        $email->wordwrap = true;
        $email->from($address);	
        $email->to($address); 
        $email->subject($template['title']);	
        $email->message($email_msg);	
        
        if ( ! $email->Send())
        {
			return $OUT->show_user_error('submission', array($LANG->line('error_sending_email')));
        } 

        // ----------------------------------------
        //  Build success message
        // ----------------------------------------
        
		$site_name = ($PREFS->ini('site_name') == '') ? $LANG->line('back') : $PREFS->ini('site_name');
		                
        $data = array(	'title' 	=> $LANG->line('mbr_login'),
        				'heading'	=> $LANG->line('thank_you'),
        				'content'	=> $LANG->line('password_has_been_reset'),
        				'link'		=> array($FNS->form_backtrack(), $site_name)
        			 );
			
		$OUT->show_message($data);
	}
	// END



	// ----------------------------------
	//  AIM Console
	// ----------------------------------

	function aim_console()
	{
		global $IN, $DB, $FNS, $LANG, $PREFS;
		
		$this->enable_breadcrumb = FALSE;
			
		$aol_im = '';
		
		$query = $DB->query("SELECT aol_im FROM exp_members WHERE member_id = '".$IN->QSTR."'");
						
		if ($query->num_rows == 0)
		{
			return;
		}
			
		$swap = array(
						'image_path'		=>	$PREFS->ini('member_images', 1),
						'aol_im'			=>	$query->row['aol_im'],
						'lang:close_window'	=>	$LANG->line('mbr_close_window')
					 );
	
		$this->content = $FNS->var_replace($swap, $this->MS->aim_console());
	}
	// END
	
	
	

	// ----------------------------------
	//  ICQ Console
	// ----------------------------------
	
	function icq_console()
	{
		global $DB, $IN, $FNS, $LANG, $PREFS, $SESS;
		
		$this->enable_breadcrumb = FALSE;
		
		// ---------------------------------
		//  Is the user logged in?
		// ---------------------------------
		
		if ($SESS->userdata['member_id'] == 0)
		{
			return $this->login_form($this->basepath.'email/'.$IN->QSTR.'/');
		}
					
		$query = $DB->query("SELECT screen_name, icq FROM exp_members WHERE member_id = '{$IN->QSTR}'");
		
		if ($query->num_rows == 0)
		{
			return false;
		}
		
		$hidden_fields =	array(
									'to'		=> $query->row['icq'],
									'from'		=> $SESS->userdata['screen_name'],
									'fromemail'	=> ''												
								);
									
		$form = $FNS->form_declaration($hidden_fields, 'http://wwp.icq.com/scripts/WWPMsg.dll', '', 0);
				
		$icq_console = $this->MS->icq_console();
						                             			
		$swap = array(
						'form_declaration'			=>	$form,
						'name'						=>	$query->row['screen_name'],
						'icq'						=>	$query->row['icq'],
						'lang:icq_number'			=>	$LANG->line('mbr_icq_number'),
						'lang:recipient'			=>	$LANG->line('mbr_icq_recipient'),
						'lang:subject'				=>	$LANG->line('mbr_icq_subject'),
						'lang:message'				=>	$LANG->line('mbr_icq_message'),
						'lang:submit'				=>	$LANG->line('submit'),
						'lang:close_window'			=>	$LANG->line('mbr_close_window')
					 );
	
		$this->content = $FNS->var_replace($swap, $icq_console);	
	}
	// END
	

    // ----------------------------------
    //  Member Email Form
    // ----------------------------------

	function email_form()
	{
		global $DB, $IN, $FNS, $LANG, $PREFS, $SESS, $OUT;
		
		$this->enable_breadcrumb = FALSE;

		// ---------------------------------
		//  Is the user logged in?
		// ---------------------------------
		
		if ($SESS->userdata['member_id'] == 0)
		{
			return $this->login_form($this->basepath.'email/'.$IN->QSTR.'/');
		}
		
		// ---------------------------------
		//  Is user allowed to send email?
		// ---------------------------------
				
		if ($SESS->userdata['can_email_from_profile'] == 'n')
		{
			return $OUT->show_user_error('general', array($LANG->line('mbr_not_allowed_to_use_email_console')));
		}
		
			
		$query = $DB->query("SELECT screen_name, accept_user_email FROM exp_members WHERE member_id = '{$IN->QSTR}'");
		
		if ($query->num_rows == 0)
		{
			return false;
		}
		
		if ($query->row['accept_user_email'] != 'y')
		{
			$swap = array(
							'lang:message'			=>	$LANG->line('mbr_email_not_accepted'),
							'css_class'				=>	'highlight',
							'lang:close_window'		=>	$LANG->line('mbr_close_window')
						);
							
			return $this->content = $FNS->var_replace($swap, $this->MS->email_user_message());	
		}
				
		
		$email_form = $this->MS->email_form();
						                             			
		$swap = array(
						'form_declaration'			=>	$FNS->form_declaration(array('MID' => $IN->QSTR), $this->basepath.'send_email/'),
						'name'						=>	$query->row['screen_name'],
						'lang:recipient'			=>	$LANG->line('mbr_recipient'),
						'lang:subject'				=>	$LANG->line('mbr_subject'),
						'lang:send_self_copy'		=>	$LANG->line('mbr_send_self_copy'),
						'lang:message'				=>	$LANG->line('mbr_message'),
						'lang:message_disclaimer'	=>	$LANG->line('mbr_email_disclaimer'),
						'lang:message_logged'		=>	$LANG->line('mbr_email_logged'),
						'lang:submit'				=>	$LANG->line('submit'),
						'lang:close_window'			=>	$LANG->line('mbr_close_window')
					 );
	
		$this->content = $FNS->var_replace($swap, $email_form);	
	}
	// END




    // ----------------------------------
    //  Send Member Email
    // ----------------------------------

	function send_email()
	{
		global $DB, $IN, $FNS, $OUT, $LANG, $PREFS, $LOC, $SESS;
		
		$this->enable_breadcrumb = FALSE;
			
		// ---------------------------------
		//  Are we missing data?
		// ---------------------------------
		
		if ( ! $member_id = $IN->GBL('MID', 'POST'))
		{
			return false;
		}
		
		if ( ! isset($_POST['subject']) || ! isset($_POST['message']))
		{
			return false;
		}
		
		if ($_POST['subject'] == '' OR $_POST['message'] == '')
		{
			return $OUT->show_user_error('submission', array($LANG->line('mbr_missing_fields')));
		}
    
		// ---------------------------------
		//  Is the user logged in?
		// ---------------------------------
		
		if ($SESS->userdata['member_id'] == 0)
		{
			return $this->login_form($this->basepath.'email/'.$member_id.'/');
		}
		
        // ----------------------------------------
        // Is the user banned?
        // ----------------------------------------
        
        if ($SESS->userdata['is_banned'] == TRUE)
        {
			return false;
        }
        
        // ----------------------------------------
        // Check Email Timelock
        // ----------------------------------------
        
        if ($SESS->userdata['group_id'] != 1)
        {
        	$lock = $PREFS->ini('email_console_timelock');
        
			if (is_numeric($lock) AND $lock != 0)
			{
				if (($SESS->userdata['last_email_date'] + ($lock*60)) > $LOC->now)
				{
					$message = $LANG->line('mbr_email_timelock_not_expired');
					
					$message = str_replace("%x", $lock, $message);
				
					$swap = array(
									'lang:message'			=>	$message,
									'css_class'				=>	'highlight',
									'lang:close_window'		=>	$LANG->line('mbr_close_window')
								);
									
					return $this->content = $FNS->var_replace($swap, $this->MS->email_user_message());	
				}
			}
        }
               
		// ---------------------------------
		//  Do we have a secure hash?
		// ---------------------------------
		
        if ($PREFS->ini('secure_forms') == 'y')
        {
			$query = $DB->query("SELECT COUNT(*) AS count FROM exp_security_hashes WHERE hash='".$DB->escape_str($_POST['XID'])."' AND date > UNIX_TIMESTAMP()-7200");
		
			if ($query->row['count'] == 0)
			{
				return false;
			}
			
			$DB->query("DELETE FROM exp_security_hashes WHERE (hash='".$DB->escape_str($_POST['XID'])."' OR date < UNIX_TIMESTAMP()-7200)");
		}		
				
		// ---------------------------------
		//  Does the recipient accept email?
		// ---------------------------------
		
		$query = $DB->query("SELECT email, screen_name, accept_user_email FROM exp_members WHERE member_id = '{$member_id}'");
		
		if ($query->num_rows == 0)
		{
			return false;
		}
		
		if ($query->row['accept_user_email'] != 'y')
		{
			$swap = array(
							'lang:message'			=>	$LANG->line('mbr_email_not_accepted'),
							'css_class'				=>	'highlight',
							'lang:close_window'		=>	$LANG->line('mbr_close_window')
						);
							
			return $this->content = $FNS->var_replace($swap, $this->MS->email_user_message());	
		}
		
		$message  = stripslashes($_POST['message'])."\n\n";
		$message .= $LANG->line('mbr_email_forwarding')."\n";
		$message .= $PREFS->ini('site_url')."\n"; 
		$message .= $LANG->line('mbr_email_forwarding_cont');

		// ----------------------------
		//  Send email
		// ----------------------------
		
		if ( ! class_exists('EEmail'))
		{
			require PATH_CORE.'core.email'.EXT;
		}
			 
		$email = new EEmail;
		$email->wordwrap = true;
		$email->from($SESS->userdata['email']);	
		$email->to($query->row['email']); 
		$email->subject(stripslashes($_POST['subject']));	
		$email->message($message);		
		
		if (isset($_POST['self_copy']))
		{
			$email->bcc($SESS->userdata['email']);	
		}
		
		$swap['lang:close_window'] = $LANG->line('mbr_close_window');
		
		if ( ! $email->Send())
		{		
			$swap['lang:message']	= $LANG->line('mbr_email_error');
			$swap['css_class'] 		= 'alert';
		}
		else
		{
			$this->log_email($query->row['email'], $query->row['screen_name'], $_POST['subject'], $_POST['message']);

			$swap['lang:message']	= $LANG->line('mbr_good_email');
			$swap['css_class'] 		= 'default';
			
			$DB->query("UPDATE exp_members SET last_email_date = '{$LOC->now}' WHERE member_id = '{$SESS->userdata['member_id']}'");
			
		}
		
		$this->content = $FNS->var_replace($swap, $this->MS->email_user_message());			
	}
	// END



	// ---------------------------------
	//  Log Email Message
	// ---------------------------------

	function log_email($recipient, $recipient_name, $subject, $message)
	{
		global $IN, $LOC, $DB, $SESS, $PREFS;
		
		if ($PREFS->ini('log_email_console_msgs') == 'y')
		{
			$data = array(
							'cache_date'		=> $LOC->now,
							'member_id'			=> $SESS->userdata['member_id'],
							'member_name'		=> $SESS->userdata['screen_name'],
							'ip_address'		=> $IN->IP,
							'recipient'			=> $recipient,
							'recipient_name'	=> $recipient_name,
							'subject'			=> $subject,
							'message'			=> $message
						);
									
			$DB->query($DB->insert_string('exp_email_console_cache', $data));
		}      
	}
	// END
	
}
// END CLASS
?>