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
 File: cp.myaccount.php
-----------------------------------------------------
 Purpose: User account management functions
=====================================================
*/

if ( ! defined('EXT'))
{
    exit('Invalid file request');
}


class MyAccount {

    var $username = '';


    // -----------------------------------
    //  Constructor
    // -----------------------------------   

    function MyAccount()
    {
        global $LANG, $IN, $DB, $DSP;
                
        if (FALSE === ($id = $this->auth_id()))
        {
            return $DSP->no_access_message();
        }
        
		// -----------------------------------
		//  Fetch username/screen name
		// -----------------------------------   
                
        $query = $DB->query("SELECT username, screen_name FROM exp_members WHERE member_id = '$id'");
        
        if ($query->num_rows == 0)
        {
            return $DSP->no_access_message();
        }
        
        $this->username = ($query->row['screen_name'] == '') ? $query->row['username']: $query->row['screen_name'];
        
		// -----------------------------------
		//  Direct the request
		// -----------------------------------   

        switch($IN->GBL('M'))
        {
            case 'edit_profile'          : $this->member_profile_form();
                break;
            case 'update_profile'        : $this->update_member_profile();
                break;
            case 'unpw_form'             : $this->username_password_form();
                break;
            case 'update_unpw'           : $this->update_username_password();
                break;
            case 'email_settings'        : $this->email_settings_form();
                break;
            case 'update_email'          : $this->update_email_settings();
                break;
            case 'localization'          : $this->localization_form();
                break;
            case 'localization_update'   : $this->localization_update();
                break;
            case 'subscriptions'         : $this->subscriptions_form();
                break;
            case 'pingservers'           : $this->my_ping_servers();
                break;               
            case 'htmlbuttons'           : $this->htmlbuttons();
                break; 
            case 'homepage'				 : $this->homepage_builder();
                break;
            case 'set_homepage_prefs'	 : $this->set_homepage_prefs();
            	break;
            case 'set_homepage_order'	 : $this->set_homepage_order();
            	break;
            case 'theme'				 : $this->theme_builder();
                break;
            case 'save_theme'			 : $this->save_theme();
                break;
            case 'notepad'               : $this->notepad();
                break;
            case 'notepad_update'        : $this->notepad_update();
                break;
            case 'administration'        : $this->administrative_options();
                break;
            case 'administration_update' : $this->administration_update();
                break;
            case 'quicklinks'            : $this->quick_links_form();
                break;
            case 'quicklinks_update'     : $this->quick_links_update();
                break;
            case 'bookmarklet'           : $this->bookmarklet();
                break;
            case 'bookmarklet_fields'    : $this->bookmarklet_fields();
                break;
            case 'create_bookmarklet'    : $this->create_bookmarklet();
                break;
            default                      : $this->account_wrapper();
                break;
        }
    }
    // END
    
   


    //------------------------------------------------
    //  Validate user and get the member ID number
    //-------------------------------------------------

    function auth_id()
    {
        global $IN, $SESS, $DSP, $LANG;   
        
        // Who's profile are we editing?

        $id = ( ! $IN->GBL('id', 'GP')) ? $SESS->userdata['member_id'] : $IN->GBL('id', 'GP');

        // Is the user authorized to edit the profile?
        
        if ($id != $SESS->userdata['member_id'])
        {
            if ( ! $DSP->allowed_group('can_admin_members'))
            {
                return FALSE;
            }
            
            // Only Super Admins can view Super Admin profiles
            
            if ($id == 1 AND $SESS->userdata['group_id'] != 1)
            {
                return FALSE;
            }        
        }
        
        return $id;
    }
    // END


    //------------------------------------------------
    //  Left side menu
    //------------------------------------------------

    function nav($path = '', $text = '')
    {
        global $DSP, $LANG;

        if ($path == '')
            return false;
            
        if ($text == '')
            return false;
        
        return 
                $DSP->div('itemWrapper').
                $DSP->anchor(BASE.AMP.'C=myaccount'.AMP.'M='.$path, $LANG->line($text)).
                $DSP->div_c();                
    }
    // END



    //------------------------------------------------
    //  "My Account" main page wrapper
    //-------------------------------------------------

    function account_wrapper($title = '', $crumb = '', $content = '')
    {
        global $DSP, $DB, $IN, $SESS, $FNS, $LANG;
                          
        if (FALSE === ($id = $this->auth_id()))
        {
            return $DSP->no_access_message();
        }
        
        // Default page title if not supplied  
                        
        if ($title == '')
        {
            $title = $LANG->line('my_account');
        }
        
        // Default bread crumb if not supplied
        
        if ($crumb == '')
        {
            if ($id != $SESS->userdata['member_id'])
            {
                $crumb = $LANG->line('user_account');
            }
            else
            {
                $crumb = $LANG->line('my_account');
            }        
        }
        
        // Default content if not supplied

        if ($content == '')
        {
            $content .= $this->profile_homepage();
        }
        
        // Set breadcrumb and title
        
        $DSP->title = &$title;
        $DSP->crumb = &$crumb;

		// Build the output

        $DSP->body	 =	$DSP->table('', '0', '', '100%').
             			$DSP->tr().
             			$DSP->td('tableBorder', '240px', '', '', 'top');
             			             			
        $DSP->body	.=	$DSP->div('tablePad'); 
             			
		$DSP->body	.=	$DSP->qdiv('tableHeadingBoldNoBot', '<b>'.$LANG->line('current_member').NBS.NBS.$this->username.'</b>');
						                       
        $DSP->body	.=	$DSP->qdiv('borderTopBot', $DSP->qdiv('profileHead', $LANG->line('personal_settings')));  
         
        $DSP->body	.=	$DSP->div('profileMenuInner').
      					$this->nav('edit_profile'.AMP.'id='.$id, 'edit_profile').
        				$this->nav('email_settings'.AMP.'id='.$id, 'email_settings').
       					$this->nav('unpw_form'.AMP.'id='.$id, 'username_and_password').
        				$this->nav('localization'.AMP.'id='.$id, 'localization').
       					$DSP->div_c();
       					
        $DSP->body	.=	$DSP->qdiv('borderTopBot', $DSP->qdiv('profileHead', $LANG->line('customize_cp')));  
         
        $DSP->body	.=	$DSP->div('profileMenuInner').
       					$this->nav('homepage'.AMP.'id='.$id, 'cp_homepage').
       					$this->nav('theme'.AMP.'id='.$id, 'cp_theme').
       					$DSP->div_c();
       	
       	// This is in progress
       	if(0==1)
       	{				
        $DSP->body	.=	$DSP->qdiv('borderTopBot', $DSP->qdiv('profileHead', $LANG->line('subscriptions'))).
        				$DSP->div('profileMenuInner').
        				$this->nav('subscriptions'.AMP.'id='.$id, 'edit_subscriptions').
        				$DSP->div_c();
		}


        $DSP->body	.=	$DSP->qdiv('borderTopBot', $DSP->qdiv('profileHead', $LANG->line('extras'))).
        				$DSP->div('profileMenuInner').
        				$this->nav('quicklinks'.AMP.'id='.$id, 'quick_links').
        				$this->nav('notepad'.AMP.'id='.$id, 'notepad').
        				$DSP->div_c();

        if ($DSP->allowed_group('can_access_publish') AND count($FNS->fetch_assigned_weblogs()) > 0)
        {
            $DSP->body	.=	$DSP->qdiv('borderTopBot', $DSP->qdiv('profileHead', $LANG->line('weblog_settings'))).  
            				$DSP->div('profileMenuInner').
            				$this->nav('pingservers'.AMP.'id='.$id, 'your_ping_servers').
            				$this->nav('htmlbuttons'.AMP.'id='.$id, 'your_html_buttons').
            				$this->nav('bookmarklet'.AMP.'id='.$id, 'bookmarklet').
            				$DSP->div_c();      
        }

        $DSP->body	.=	$DSP->qdiv('', NBS);

        if ($DSP->allowed_group('can_admin_members'))
        {
            $DSP->body	.=	$DSP->div('profileMenuInner').               
            				'<b>'.$this->nav('administration'.AMP.'id='.$id,  'administrative_options').'</b>'.
            				$DSP->div_c();
        }    

		$DSP->body .=	$DSP->div_c();

        $DSP->body	.=	$DSP->td_c().
              			$DSP->td('', '16px', '', '', 'top').NBS.$DSP->td_c().
              			$DSP->td('tableBorder', '', '', '', 'top').
						$DSP->qdiv('tablePad', $content).
						$DSP->td_c().
						$DSP->tr_c().
						$DSP->table_c();
    }
    // END
    


    // -----------------------------------
    //  Profile Homepage
    // -----------------------------------   
    
    function profile_homepage()
    {
        global $DSP, $LANG, $DB, $LOC;
        
        if (FALSE === ($id = $this->auth_id()))
        {
            return $DSP->no_access_message();
        }
    
        $query = $DB->query("SELECT email, ip_address, join_date, last_visit, total_entries, total_comments, last_entry_date, last_comment_date FROM exp_members WHERE member_id = '$id'");
        
        if ($query->num_rows == 0)
            return false;
        
        foreach ($query->row as $key => $val)
        {
            $$key = $val;
        }
        
        $i = 0;

        $r  = $DSP->table('', '0', '10', '100%').
              $DSP->tr().
              $DSP->td('tableHeadingBold', '', '2').$LANG->line('member_stats').NBS.NBS.NBS.$this->username.$DSP->td_c().
              $DSP->tr_c();
              
        $fields = array(
        					'email'				=> $DSP->mailto($email), 
        					'join_date'			=> $LOC->set_human_time($join_date), 
        					'last_visit'		=> ($last_visit == 0) ? '--' : $LOC->set_human_time($last_visit), 
        					'total_entries'		=> $total_entries, 
        					'total_comments'	=> $total_comments, 
        					'last_entry_date'	=> ($last_entry_date == 0) ? '--' : $LOC->set_human_time($last_entry_date), 
        					'last_comment_date'	=> ($last_comment_date == 0) ? '--' : $LOC->set_human_time($last_comment_date),
        					'user_ip_address'	=> $ip_address
        				);

		foreach ($fields as $key => $val)
		{
			$style = ($i++ % 2) ? 'tableCellOne' : 'tableCellTwo';
	
			$r .= $DSP->tr();
			$r .= $DSP->table_qcell($style, $DSP->qspan('defaultBold', $LANG->line($key)), '50%');
			$r .= $DSP->table_qcell($style, $val, '50%');
			$r .= $DSP->tr_c();		
		}              

        $r .= $DSP->table_c(); 

        return $r;
    }
    // END
    


    // -----------------------------------
    //  Edit Profile Form
    // -----------------------------------   
    
    function member_profile_form()
    {  
        global $IN, $DSP, $DB, $SESS, $REGX, $LOC, $PREFS, $LANG;

        $screen_name    = '';
        $email          = '';
        $url            = '';
               
        if (FALSE === ($id = $this->auth_id()))
        {
            return $DSP->no_access_message();
        }

        $title = $LANG->line('edit_profile');  
        
		// -----------------------------------
		//  Fetch profile data
		// -----------------------------------   

        $query = $DB->query("SELECT url, location, occupation, interests, aol_im, yahoo_im, msn_im, icq, bio, bday_y, bday_m, bday_d FROM exp_members WHERE member_id = '$id'");    
        
        foreach ($query->row as $key => $val)
        {
            $$key = $val;
        }

		// -----------------------------------
		//  Declare form
		// -----------------------------------   
        
        $r  = $DSP->form('C=myaccount'.AMP.'M=update_profile').
              $DSP->input_hidden('id', $id);
		
		// -----------------------------------
		//  Birthday Year Menu
		// -----------------------------------   
		
		$bd  = $DSP->input_select_header('bday_y');
		$bd .= $DSP->input_select_option('', $LANG->line('year'), ($bday_y == '') ? 1 : '');
		
		for ($i = date('Y', $LOC->now); $i > 1904; $i--)
		{                    					
		  $bd .= $DSP->input_select_option($i, $i, ($bday_y == $i) ? 1 : '');
		}
		
		$bd .= $DSP->input_select_footer();
		
		// -----------------------------------
		//  Birthday Month Menu
		// -----------------------------------   
		
		$months = array(
							'01' => 'January',
							'02' => 'February',
							'03' => 'March',
							'04' => 'April',
							'05' => 'May',
							'06' => 'June',
							'07' => 'July',
							'08' => 'August',
							'09' => 'September',
							'10' => 'October',
							'11' => 'November',
							'12' => 'December'
						);
		
		$bd .= $DSP->input_select_header('bday_m');		
		$bd .= $DSP->input_select_option('', $LANG->line('month'), ($bday_m == '') ? 1 : '');
		
		for ($i = 1; $i < 13; $i++)
		{
		  if (strlen($i) == 1)
			 $i = '0'.$i;
							
		  $bd .= $DSP->input_select_option($i, $LANG->line($months[$i]), ($bday_m == $i) ? 1 : '');
		}
		
		$bd .= $DSP->input_select_footer();
		
		// -----------------------------------
		//  Birthday Day Menu
		// -----------------------------------   
		
		$bd .= $DSP->input_select_header('bday_d');		
		$bd .= $DSP->input_select_option('', $LANG->line('day'), ($bday_d == '') ? 1 : '');
		
		for ($i = 31; $i >= 1; $i--)
		{                    
		  $bd .= $DSP->input_select_option($i, $i, ($bday_d == $i) ? 1 : '');
		}
		
		$bd .= $DSP->input_select_footer();

		// -----------------------------------
		//  Build Page Output
		// -----------------------------------   

        $i = 0;
         
        $r .= $DSP->table('', '0', '10', '100%').
              $DSP->tr().
              $DSP->td('tableHeadingLargeBold', '', '2');
              
        if ($IN->GBL('U'))
        {
        	$r .= $DSP->qspan('success', $LANG->line('profile_updated'));
        }
        else
        {
        	$r .= $LANG->line('profile_form');
        }
        
        $r .= $DSP->td_c().
              $DSP->tr_c();

        $style = ($i++ % 2) ? 'tableCellOne' : 'tableCellTwo';

        $r .= $DSP->tr();
        $r .= $DSP->table_qcell($style, $DSP->qspan('defaultBold', $LANG->line('birthday')), '25%');
        $r .= $DSP->table_qcell($style, $bd, '75%');
        $r .= $DSP->tr_c();

	  if ($url == '')
		  $url = 'http://';
                             
        $fields = array(
        					'url'			=> array('i', '75'), 
        					'location'		=> array('i', '50'), 
        					'occupation'	=> array('i', '80'), 
        					'interests'		=> array('i', '75'), 
        					'aol_im'		=> array('i', '50'), 
        					'icq'			=> array('i', '50'), 
        					'yahoo_im'		=> array('i', '50'), 
        					'msn_im'		=> array('i', '50'),
        					'bio'			=> array('t', '12')
        				);
        
		foreach ($fields as $key => $val)
		{		
			$style = ($i++ % 2) ? 'tableCellOne' : 'tableCellTwo';
			
			$align = ($val['0'] == 'i') ? '' : 'top';
	
			$r .= $DSP->tr();
			$r .= $DSP->table_qcell($style, $DSP->qspan('defaultBold', $LANG->line($key)), '', $align);
			
			if ($val['0'] == 'i')
			{
				$r .= $DSP->table_qcell($style, $DSP->input_text($key, $$key, '40', $val['1'], 'input', '100%'));
			}
			elseif ($val['0'] == 't')
			{
				$r .= $DSP->table_qcell($style, $DSP->input_textarea($key, $$key, $val['1'], 'textarea', '100%'));
			}
			$r .= $DSP->tr_c();
		}
			
		// -----------------------------------
		//  Extended profile fields
		// -----------------------------------   

		$sql = "SELECT *  FROM exp_member_fields ";
		
		if ($SESS->userdata['group_id'] != 1)
		{
			$sql .= " WHERE m_field_public = 'y' ";
		}
		
		$sql .= " ORDER BY m_field_order";
		
		                
        $query = $DB->query($sql);
        
        if ($query->num_rows > 0)
        {
        
			$result = $DB->query("SELECT * FROM  exp_member_data WHERE  member_id = '$id'");        
			
			if ($result->num_rows > 0)
			{    
				foreach ($result->row as $key => $val)
				{
					$$key = $val;
				}
			}
                
			foreach ($query->result as $row)
			{
				$field_data = ( ! isset( $result->row['m_field_id_'.$row['m_field_id']] )) ? '' : 
										 $result->row['m_field_id_'.$row['m_field_id']];
										 
							
				$width = '100%';
																			  
				$required  = ($row['m_field_required'] == 'n') ? '' : $DSP->required().NBS;     
			
				// Textarea fieled types
			
				if ($row['m_field_type'] == 'textarea')
				{               
					$rows = ( ! isset($row['m_field_ta_rows'])) ? '10' : $row['m_field_ta_rows'];
	
					$style = ($i++ % 2) ? 'tableCellOne' : 'tableCellTwo';
			
					$r .= $DSP->tr();
					$r .= $DSP->table_qcell($style, $DSP->qspan('defaultBold', $required.$row['m_field_label']), '', 'top');
					$r .= $DSP->table_qcell($style, $DSP->input_textarea('m_field_id_'.$row['m_field_id'], $field_data, $rows, 'textarea', $width));
					$r .= $DSP->tr_c();
				}
				else
				{        
					// Text input fields
					
					if ($row['m_field_type'] == 'text')
					{   
						$style = ($i++ % 2) ? 'tableCellOne' : 'tableCellTwo';
				
						$r .= $DSP->tr();
						$r .= $DSP->table_qcell($style, $DSP->qspan('defaultBold', $required.$row['m_field_label']));
						$r .= $DSP->table_qcell($style, $DSP->input_text('m_field_id_'.$row['m_field_id'], $field_data, '20', '100', 'input', $width));
						$r .= $DSP->tr_c();
					}            
	
					// Drop-down lists
					
					elseif ($row['m_field_type'] == 'select')
					{                          
						$d = $DSP->input_select_header('m_field_id_'.$row['m_field_id']);
										
						foreach (explode("\n", trim($row['m_field_list_items'])) as $v)
						{   
							$v = trim($v);
						
							$selected = ($field_data == $v) ? 1 : '';
												
							$d .= $DSP->input_select_option($v, $v, $selected);
						}
						
						$d .= $DSP->input_select_footer();
						
						$style = ($i++ % 2) ? 'tableCellOne' : 'tableCellTwo';
				
						$r .= $DSP->tr();
						$r .= $DSP->table_qcell($style, $DSP->qspan('defaultBold', $required.$row['m_field_label']));
						$r .= $DSP->table_qcell($style, $d);
						$r .= $DSP->tr_c();
					}
				}
			}        
		}
		// END CUSTOM FIELDS			

		$r .= $DSP->table_c(); 
              
        // Submit button                    

        $r .= $DSP->div('itemWrapper').BR.     
              $DSP->input_submit($LANG->line('update')).
              $DSP->div_c();              

        $r.=  $DSP->form_c();
        
        return $this->account_wrapper($title, $title, $r);
    }
    // END



    // ----------------------------------
    //  Update member profile
    // ----------------------------------
    
    function update_member_profile()
    {  
        global $IN, $DSP, $DB, $SESS, $PREFS, $FNS, $REGX, $LOG, $LANG;
       
        if (FALSE === ($id = $this->auth_id()))
        {
            return $DSP->no_access_message();
        }
        
        unset($_POST['id']);
      
		if ($_POST['url'] == 'http://')
			$_POST['url'] = '';       
        
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
        $DB->query($DB->update_string('exp_members', $data, "member_id = '$id'"));   
                       
        if (count($_POST) > 0)             
        $DB->query($DB->update_string('exp_member_data', $_POST, "member_id = '$id'"));   
                        
        $FNS->redirect(BASE.AMP.'C=myaccount'.AMP.'M=edit_profile'.AMP.'id='.$id.AMP.'U=1');
        exit;    
    }
    // END




    // -----------------------------------
    //  Email preferences form
    // -----------------------------------   

    function email_settings_form()
    {  
        global $IN, $DSP, $DB, $SESS, $REGX, $PREFS, $LANG;

        $message   = '';
        
                
        if (FALSE === ($id = $this->auth_id()))
        {
            return $DSP->no_access_message();
        }
        
        $title = $LANG->line('email_settings');
        
        $query = $DB->query("SELECT email, accept_admin_email, accept_user_email, notify_by_default FROM exp_members WHERE member_id = '$id'");    
        
        foreach ($query->row as $key => $val)
        {
            $$key = $val;
        }
                
        // Build the output
        
        $r  = $DSP->form('C=myaccount'.AMP.'M=update_email').
              $DSP->input_hidden('id', $id).
              $DSP->input_hidden('current_email', $query->row['email']);
              
         
        $r .= $DSP->table('', '0', '10', '100%').
              $DSP->tr().
              $DSP->td('tableHeadingLargeBold', '', '2');
                            
              
        if ($IN->GBL('U'))
        {
        	$r .= $DSP->qspan('success', $LANG->line('settings_updated'));
        }
        else
        {
        	$r .= $title;
        }
        
        $r .= $DSP->td_c().
              $DSP->tr_c();

        $r .= $DSP->tr();
        $r .= $DSP->table_qcell('tableCellTwo', $DSP->qspan('defaultBold', $DSP->required().NBS.$LANG->line('email')), '28%');
        $r .= $DSP->table_qcell('tableCellTwo', $DSP->input_text('email', $email, '40', '80', 'input', '100%'), '72%');
        $r .= $DSP->tr_c();
        
        $checkboxes = array('accept_admin_email', 'accept_user_email', 'notify_by_default');
        
        foreach ($checkboxes as $val)
        {
			$r .= $DSP->tr();
			$r .= $DSP->td('tableCellOne', '100%', '2');
			$r .= $DSP->input_checkbox($val, 'y', ($$val == 'y') ? 1 : '').NBS.$LANG->line($val);
			$r .= $DSP->td_c();
			$r .= $DSP->tr_c();
        }
        
		$r .= $DSP->table_c();

        $r .= $DSP->div('paddedWrapper').BR.
              $DSP->qdiv('itemTitle', $LANG->line('existing_password')).
              $DSP->qdiv('itemWrapper', $DSP->qdiv('highlight', $LANG->line('password_required_for_email'))).
              $DSP->input_pass('password', '', '35', '32', 'input', '310px').
              $DSP->div_c();
        
        // Submit button                    

        $r .= $DSP->div('paddedWrapper').  
              $DSP->required(1).$DSP->br(2).      
              $DSP->input_submit($LANG->line('update')).
              $DSP->div_c();      
        
        $r.=  $DSP->form_c();
        
        return $this->account_wrapper($title, $title, $r);
    }
    // END




    // -----------------------------------
    //  Update Email Preferences
    // -----------------------------------   

    function update_email_settings()
    {
        global $IN, $DSP, $DB, $SESS, $PREFS, $FNS, $REGX, $LOG, $LANG;
      
        if (FALSE === ($id = $this->auth_id()))
        {
            return $DSP->no_access_message();
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
										'member_id'			=> $id,
										'val_type'			=> 'update', // new or update
										'fetch_lang' 		=> FALSE, 
										'require_cpw' 		=> ($_POST['current_email'] != $_POST['email']) ? TRUE :FALSE,
										'enable_log'		=> TRUE,
										'email'				=> $_POST['email'],
										'cur_email'			=> $_POST['current_email'],
									 	'cur_password'		=> $_POST['password']
									 )
							);

		$VAL->validate_email();
		
		if (count($VAL->errors) > 0)
		{
			return $VAL->show_errors();
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

        $DB->query($DB->update_string('exp_members', $data, "member_id = '$id'"));   
        
        // -------------------------------------
        // Update comments and log email change
        // -------------------------------------
                
        if ($_POST['current_email'] != $_POST['email'])
        {                           
            $DB->query($DB->update_string('exp_comments', array('email' => $_POST['email']), "author_id = '$id'"));   
        
            $LOG->log_action($VAL->log_msg);
        }
        
        $FNS->redirect(BASE.AMP.'C=myaccount'.AMP.'M=email_settings'.AMP.'id='.$id.AMP.'U=1'.AMP);
        exit;    
    }
    // END



    // -----------------------------------
    //  Username/Password form
    // -----------------------------------   

    function username_password_form()
    {  
        global $IN, $DSP, $DB, $SESS, $REGX, $PREFS, $LANG;
        
        if (FALSE === ($id = $this->auth_id()))
        {
            return $DSP->no_access_message();
        }

        $username  = '';
        $message   = '';
        
		// -----------------------------------
		//  Show "successful update" message
		// -----------------------------------   
        
        if ($IN->GBL('U'))
        {
            $message = $DSP->qdiv('success', $LANG->line('settings_updated'));
            
            if ($IN->GBL('pw_change') == 1)
            {
                $message .= $DSP->qdiv('alert', BR.$LANG->line('password_change_warning').BR.BR);
            }
        }
        
        $title = $LANG->line('username_and_password');
        
		// -----------------------------------
		//  Fetch username
		// -----------------------------------   
        
        $query = $DB->query("SELECT username, screen_name FROM exp_members WHERE member_id = '$id'");    
        
        $username 		= $query->row['username'];
        $screen_name	= $query->row['screen_name'];
        
		// -----------------------------------
        // Build the output
		// -----------------------------------   
        
        $r  = $DSP->form('C=myaccount'.AMP.'M=update_unpw').
              $DSP->input_hidden('id', $id).
              $DSP->input_hidden('current_username', $query->row['username']).
              $DSP->input_hidden('current_screen_name', $screen_name);
        
        $r .= $DSP->table('', '0', '10', '100%').
              $DSP->tr().
              $DSP->td('tableHeadingLargeBold', '', '2');
              
        if ($IN->GBL('U'))
        {
        	$r .= $message;
        }
        else
        {
        	$r .= $title;
        }
              
        $r .= $DSP->td_c().
              $DSP->tr_c();
        
        if ($SESS->userdata['group_id'] != '1' AND $PREFS->ini('allow_username_change') == 'n')
        {
			$r .= $DSP->tr();
			$r .= $DSP->td('tableCellOne', '100%', '2');
			$r .= $LANG->line('username_change_not_allowed');
			$r .= $DSP->td_c();
			$r .= $DSP->tr_c();
        }
        else
        {
			$r .= $DSP->tr();
			$r .= $DSP->table_qcell('tableCellTwo', $DSP->qspan('defaultBold', $LANG->line('username')), '28%');
			$r .= $DSP->table_qcell('tableCellTwo', $DSP->input_text('username', $username, '40', '50', 'input', '100%'), '72%');
			$r .= $DSP->tr_c();
        }
        
		$r .= $DSP->tr();
		$r .= $DSP->table_qcell('tableCellTwo', $DSP->qspan('defaultBold', $LANG->line('screen_name')), '28%');
		$r .= $DSP->table_qcell('tableCellTwo', $DSP->input_text('screen_name', $screen_name, '40', '50', 'input', '100%'), '72%');
		$r .= $DSP->tr_c();
	
	
		$r .= $DSP->tr();
		$r .= $DSP->td('tableCellOne', '100%', '2');
	
        $r .= $DSP->div('itemWrapper')
             .$DSP->qdiv('itemTitle', $LANG->line('password_change'))
             .$DSP->qdiv('itemWrapper', $DSP->qdiv('alert', $LANG->line('leave_blank')))
             .$DSP->div_c();
             
        $r .= $DSP->qdiv('itemTitle', $LANG->line('new_password'))
             .$DSP->input_pass('password', '', '35', '32', 'input', '300px');

        $r .= $DSP->div('itemWrapper').
              $DSP->qdiv('itemTitle', $LANG->line('new_password_confirm')).
              $DSP->input_pass('password_confirm', '', '35', '32', 'input', '300px').
              $DSP->div_c();
				  
		$r .= $DSP->td_c();
		$r .= $DSP->tr_c();
        

		$r .= $DSP->table_c();
        

        $r .= $DSP->div('paddedWrapper').BR.
              $DSP->qdiv('itemTitle', $LANG->line('existing_password')).
              $DSP->qdiv('itemWrapper', $DSP->qdiv('highlight', $LANG->line('password_required'))).
              $DSP->input_pass('current_password', '', '35', '32', 'input', '310px');
        
        // Submit button                    

        $r .= $DSP->div('itemWrapper').BR.
              $DSP->qdiv('highlight', $LANG->line('password_change_requires_login')).
              BR.
              $DSP->input_submit($LANG->line('update')).
              $DSP->div_c(). 
              $DSP->div_c();      
        
        $r.=  $DSP->form_c();
        
        return $this->account_wrapper($title, $title, $r);
    }
    // END


    // -----------------------------------
    //  Update username and password
    // -----------------------------------   

    function update_username_password()
    {  
        global $IN, $DSP, $DB, $SESS, $PREFS, $FNS, $REGX, $LOG, $LANG;
      
        if (FALSE === ($id = $this->auth_id()))
        {
            return $DSP->no_access_message();
        }
    
        if ($PREFS->ini('allow_username_change') != 'y')
        {
            if ($_POST['password'] == '')
            {
                $FNS->redirect(BASE.AMP.'C=myaccount'.AMP.'M=unpw_form'.AMP.'id='.$id);
                exit;    
            }
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
										'member_id'			=> $id,
										'val_type'			=> 'update', // new or update
										'fetch_lang' 		=> FALSE, 
										'require_cpw' 		=> TRUE,
									 	'enable_log'		=> TRUE,
										'username'			=> $_POST['username'],
										'cur_username'		=> $_POST['current_username'],
										'screen_name'		=> $_POST['screen_name'],
										'cur_screen_name'	=> $_POST['current_screen_name'],
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
			return $VAL->show_errors();
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

        $pw_change = 0;

        if ($_POST['password'] != '')
        {
            $data['password'] = $FNS->hash(stripslashes($_POST['password']));
            
            if ($id == $SESS->userdata['member_id'])
            {
                $pw_change = 1;
            }
        }

        $DB->query($DB->update_string('exp_members', $data, "member_id = '$id'"));   

		if ($_POST['current_username'] != $_POST['username'])
		{  
            $query = $DB->query("SELECT screen_name FROM exp_members WHERE member_id = '$id'");

			$screen_name = ($query->row['screen_name'] != '') ? $query->row['screen_name'] : '';

			// Update comments with current member data
		
			$data = array('name' => ($screen_name != '') ? $screen_name : $_POST['username']);
						  
			$DB->query($DB->update_string('exp_comments', $data, "author_id = '$id'"));   
        }
        
        // Write log file
        
		$LOG->log_action($VAL->log_msg);
		
		// Redirect...

        $FNS->redirect(BASE.AMP.'C=myaccount'.AMP.'M=unpw_form'.AMP.'id='.$id.AMP.'U=1'.AMP.'pw_change='.$pw_change);
        exit;    
    }
    // END




    // -----------------------------------
    //  Ping servers
    // -----------------------------------   

    function my_ping_servers()
    {        
        global $IN, $LANG, $FNS, $DSP;
        
        if (FALSE === ($id = $this->auth_id()))
        {
            return $DSP->no_access_message();
        }

        // Is the user authorized to access the publish page?
        // And does the user have at least one blog assigned?
        // If not, show the no access message

        if ( ! $DSP->allowed_group('can_access_publish') || ! count($FNS->fetch_assigned_weblogs()) > 0)
        {
            return $DSP->no_access_message();
        }
        
        $message = ($IN->GBL('U', 'GET')) ? $DSP->qdiv('success', NBS.$LANG->line('pingservers_updated')) : '';
    
        require PATH_CP.'cp.publish_ad'.EXT;
        
        $PA = new PublishAdmin;

        $title = $LANG->line('ping_servers');

        return $this->account_wrapper($title, $title, $PA->ping_servers($message, $id));
    }    
    // END
    
    

    // -----------------------------------
    //  HTML buttons
    // -----------------------------------   

    function htmlbuttons()
    {        
        global $IN, $LANG, $FNS, $DSP;
        
        if (FALSE === ($id = $this->auth_id()))
        {
            return $DSP->no_access_message();
        }

        // Is the user authorized to access the publish page?
        // And does the user have at least one blog assigned?
        // If not, show the no access message

        if ( ! $DSP->allowed_group('can_access_publish') || ! count($FNS->fetch_assigned_weblogs()) > 0)
        {
            return $DSP->no_access_message();
        }
        
        $message = ($IN->GBL('U', 'GET')) ? $DSP->qdiv('success', NBS.$LANG->line('html_buttons_updated')) : '';
    
        require PATH_CP.'cp.publish_ad'.EXT;
        
        $PA = new PublishAdmin;

        $title = $LANG->line('html_buttons');

        return $this->account_wrapper($title, $title, $PA->html_buttons($message, $id));
    }    
    // END





    
    // -----------------------------------
    //  Home Page builder
    // -----------------------------------   

    function homepage_builder()
    {
        global $IN, $LANG, $DB, $SESS, $DSP;
        
        if (FALSE === ($id = $this->auth_id()))
        {
            return $DSP->no_access_message();
        }
        
        $DSP->rcrumb = $DSP->qdiv('crumblinksR', $DSP->anchor(BASE.AMP.'C=myaccount'.AMP.'M=set_homepage_order'.AMP.'id='.$id, $LANG->line('set_display_order')));
        
        $DB->fetch_fields = TRUE;
        
        $prefs = array();        
                
        $sql = "SELECT	recent_entries,
						recent_comments,
						site_statistics,
						notepad";
						
		if ($SESS->userdata['group_id'] == 1)
		{    	
			  $sql .= ",
						member_search_form,
						recent_members";
		}						
						
		$sql .= " FROM exp_member_homepage 
        		  WHERE member_id = '$id'";
        		  
        $DB->fetch_fields = TRUE;

        $query = $DB->query($sql);
                
        if ($query->num_rows == 0)
        {        
            foreach ($query->fields as $f)
            {
				$prefs[$f] = 'n';
            }
        }
        else
        {  
        	unset($query->row['member_id']);
              
            foreach ($query->row as $key => $val)
            {
				$prefs[$key] = $val;
            }
        }


        $title = $LANG->line('customize_homepage');
                        
        $r  = $DSP->form('C=myaccount'.AMP.'M=set_homepage_prefs');
        $r .= $DSP->input_hidden('id', $id);
        
        $r .= $DSP->table('', '0', '0', '100%').
              $DSP->tr().
              $DSP->td('tablePad'); 

		if ($IN->GBL('U')) 
		{
			$r .= $DSP->div('tableHeading');
			$r .= $DSP->qdiv('success', NBS.$LANG->line('preferences_updated'));
			$r .= $DSP->div_c();
		}

        $r .= $DSP->table('', '0', '10', '100%').
              $DSP->tr().
			  $DSP->table_qcell('tableHeadingBold', $LANG->line('homepage_preferences')).
			  $DSP->table_qcell('tableHeadingBold', $LANG->line('left_column')).
			  $DSP->table_qcell('tableHeadingBold', $LANG->line('right_column')).
			  $DSP->table_qcell('tableHeadingBold', $LANG->line('do_not_show')).
              $DSP->tr_c();

		$i = 0;
		
		foreach ($prefs as $key => $val)
		{
			$style = ($i++ % 2) ? 'tableCellOne' : 'tableCellTwo';
			
			$r .= $DSP->tr();
			$r .= $DSP->table_qcell($style, $DSP->qspan('defaultBold', $LANG->line($key)));
			$r .= $DSP->table_qcell($style, $DSP->input_radio($key, 'l', ($val == 'l') ? 1 : ''));
			$r .= $DSP->table_qcell($style, $DSP->input_radio($key, 'r', ($val == 'r') ? 1 : ''));
			$r .= $DSP->table_qcell($style, $DSP->input_radio($key, 'n', ($val == 'n') ? 1 : ''));
			$r .= $DSP->tr_c();
        }
        
        $r .= $DSP->table_c(); 

        $r .= $DSP->td_c()   
             .$DSP->tr_c()      
             .$DSP->table_c();  

        $r .= $DSP->div('itemWrapper').BR.
              $DSP->input_submit($LANG->line('update')).
              $DSP->div_c();              
        
        $r .= $DSP->form_c();

        return $this->account_wrapper($title, $title, $r);
    }
    // END


    //------------------------------------------------
    //  Set Homepage Display Order
    //-------------------------------------------------

    function set_homepage_order()
    {
        global $IN, $LANG, $DB, $SESS, $DSP;

        if (FALSE === ($id = $this->auth_id()))
        {
            return $DSP->no_access_message();
        }
        
        
        $opts = array(	'recent_entries',
						'recent_comments',
						'site_statistics',
						'notepad'
        			);
        			
		if ($SESS->userdata['group_id'] == 1)
		{  
			$opts[] = 'recent_members';
			$opts[] = 'member_search_form';
		}						
						
        
        $prefs = array();
                
        $sql = "SELECT	*
        		FROM exp_member_homepage 
        		WHERE member_id = '$id'";
                
        $query = $DB->query($sql);
					  
		foreach ($query->row as $key => $val)
		{
			if (in_array($key, $opts))
			{
				if ($val != 'n')
				{
					$prefs[$key] = $val;
				}
			}
		}


        $title = $LANG->line('customize_homepage');
        
        $r  = '';
                

        $r .= $DSP->form('C=myaccount'.AMP.'M=set_homepage_prefs');
        $r .= $DSP->input_hidden('id', $id);
        $r .= $DSP->input_hidden('loc', 'set_homepage_order');
        
        $r .= $DSP->table('', '0', '0', '100%').
              $DSP->tr().
              $DSP->td('tablePad'); 
              
        if (isset($_GET['U']))
        {
        	if ($_GET['U'] == 2)
        	{
        		$r .= $DSP->div('tableHeading');
        		$r .= $DSP->qdiv('success', NBS.$LANG->line('preferences_updated'));
        		$r .= $DSP->div_c();
        	}
        	else
        	{
        		$r .= $DSP->div('tableHeading');
        		$r .= $DSP->qdiv('success', NBS.$LANG->line('preferences_updated'));
        		$r .= $DSP->heading(NBS.$LANG->line('please_update_order'), 5);
        		$r .= $DSP->div_c();
        	}
        }
              

        $r .= $DSP->table('', '0', '10', '100%').
              $DSP->tr().
			  $DSP->table_qcell('tableHeadingBold', $LANG->line('set_display_order')).
			  $DSP->table_qcell('tableHeadingBold', $LANG->line('left_column')).
			  $DSP->table_qcell('tableHeadingBold', $LANG->line('right_column')).
              $DSP->tr_c();

		$i = 0;
		
		foreach ($prefs as $key => $val)
		{
			if (in_array($key, $opts))
			{
				$style = ($i++ % 2) ? 'tableCellOne' : 'tableCellTwo';
				
				$r .= $DSP->tr();
				$r .= $DSP->table_qcell($style, $DSP->qspan('defaultBold', $LANG->line($key)));
				
              //$DSP->qdiv('', $DSP->input_text('recipient', '', '20', '150', 'input', '300px')).
				
				if ($val == 'l')
				{
					$r .= $DSP->table_qcell($style, $DSP->input_text($key.'_order', $query->row[$key.'_order'], '10', '3', 'input', '50px'));
					$r .= $DSP->table_qcell($style, NBS);
				}
				elseif ($val == 'r')
				{
					$r .= $DSP->table_qcell($style, NBS);
					$r .= $DSP->table_qcell($style, $DSP->input_text($key.'_order', $query->row[$key.'_order'], '10', '3', 'input', '50px'));
				}
				
				$r .= $DSP->tr_c();
			}
        }
        
        $r .= $DSP->table_c(); 

        $r .= $DSP->td_c()   
             .$DSP->tr_c()      
             .$DSP->table_c();  

        $r .= $DSP->div('itemWrapper').BR.
              $DSP->input_submit($LANG->line('update')).
              $DSP->div_c();              
        
        $r .= $DSP->form_c();

        return $this->account_wrapper($title, $title, $r);

	}
	// END    
    


    //------------------------------------------------
    //  Update Homepage Preferences
    //-------------------------------------------------

    function set_homepage_prefs()
    {
        global $DB, $SESS, $FNS;   

        if (FALSE === ($id = $this->auth_id()))
        {
            return $DSP->no_access_message();
        }

		$loc = ( ! isset($_POST['loc'])) ? '' : $_POST['loc'];
        		
		unset($_POST['loc']);
		unset($_POST['id']);
		
		if ($SESS->userdata['group_id'] != 1)
		{  
			unset($_POST['recent_members']);
			unset($_POST['member_search_form']);
		}						
		
		$ref = 1;
		
		$reset = array(	
							'recent_entries_order' 				=> 0,
							'recent_comments_order' 			=> 0,
							'recent_members_order' 				=> 0,
							'site_statistics_order' 			=> 0,
							'member_search_form_order' 			=> 0,
							'notepad_order' 					=> 0
						);
				
		if ($loc == 'set_homepage_order')
		{
			$ref = 2;
		
        	$DB->query($DB->update_string('exp_member_homepage', $reset, "member_id = '$id'"));
		}
		
        $DB->query($DB->update_string('exp_member_homepage', $_POST, "member_id = '$id'"));
        
        // Decide where to redirect based on the value of the submission
        
        foreach ($reset as $key => $val)
        {
        	$key = str_replace('_order', '', $key);
        
        	if (isset($_POST[$key]) AND ($_POST[$key] == 'l' || $_POST[$key] == 'r'))
        	{
				$FNS->redirect(BASE.AMP.'C=myaccount'.AMP.'M=set_homepage_order'.AMP.'id='.$id.AMP.'U='.$ref);
				exit;    
        	}
        }
                
        $FNS->redirect(BASE.AMP.'C=myaccount'.AMP.'M=homepage'.AMP.'id='.$id.AMP.'U='.$ref);
        exit;    
	}
	// END    



    //---------------------------------
    //  Theme builder
    //---------------------------------
    
    // OK, well, the title is misleading.  Eventually, this will be a full-on
    // theme builder.  Right now it just lets users choose from among pre-defined CSS files

    function theme_builder()
    {
        global $IN, $DB, $DSP, $FNS, $SESS, $PREFS, $LANG;
                
        if (FALSE === ($id = $this->auth_id()))
        {
            return $DSP->no_access_message();
        }
        
		if ( ! class_exists('Admin'))
		{
			require PATH_CP.'cp.admin'.EXT;
		}

        $title = $LANG->line('cp_theme');
		
        $r  = $DSP->form('C=myaccount'.AMP.'M=save_theme');
        $r .= $DSP->input_hidden('id', $id);
        
        $AD = new Admin;
                
        $r .= $DSP->table('', '0', '10', '100%').
              $DSP->tr().
              $DSP->td('tableHeadingLargeBold', '', '2');
              
              
        if ($IN->GBL('U'))
        {
        	$r .= $DSP->qspan('success', $LANG->line('preferences_updated'));
        }
        else
        {
        	$r .= $title;
        }
                      
        $r .= $DSP->td_c().
              $DSP->tr_c();
              		
        $theme = ($SESS->userdata['theme'] == '') ? $PREFS->ini('cp_theme') : $SESS->userdata['theme'];

		$r .= $DSP->tr();
		$r .= $DSP->table_qcell('tableCellOne', $DSP->qspan('defaultBold', $LANG->line('choose_theme')), '50%');
		$r .= $DSP->table_qcell('tableCellOne', $AD->fetch_themes($theme), '50%');
		$r .= $DSP->tr_c();		

        $r .= $DSP->table_c(); 
        
        $r .= $DSP->div('itemWrapper').BR.
              $DSP->input_submit($LANG->line('update')).
              $DSP->div_c();              

        return $this->account_wrapper($title, $title, $r);
    }
    // END



    //---------------------------------
    //  Save Theme
    //---------------------------------

    function save_theme()
    {
        global $DB, $FNS;   

        if (FALSE === ($id = $this->auth_id()))
        {
            return $DSP->no_access_message();
        }
        		
        $DB->query("UPDATE exp_members SET theme = '".$_POST['cp_theme']."' WHERE member_id = '$id'");
                
        $FNS->redirect(BASE.AMP.'C=myaccount'.AMP.'M=theme'.AMP.'id='.$id.AMP.'U=1');
        exit;    
	}
	// END    





    //------------------------------------------------
    // Subscriptions
    //------------------------------------------------

    function subscriptions_form()
    {
        global $DSP, $LANG;

        $title = $LANG->line('subscriptions');

        $r = 'This page will allow each user to manage their subscriptions';

        return $this->account_wrapper($title, $title, $r);
    }
    // END



    // -----------------------------------
    //  Localization settings
    // -----------------------------------   

    function localization_form()
    {
        global $IN, $DB, $DSP, $FNS, $LOC, $SESS, $LANG;
                        
        if (FALSE === ($id = $this->auth_id()))
        {
            return $DSP->no_access_message();
        }
        
        $title = $LANG->line('localization_settings');
    
        $query = $DB->query("SELECT timezone,daylight_savings,language FROM exp_members WHERE member_id = '$id'");
        	
        $r  = $DSP->form('C=myaccount'.AMP.'M=localization_update').
              $DSP->input_hidden('id', $id);              

        $r .= $DSP->table('', '0', '10', '100%').
              $DSP->tr().
              $DSP->td('tableHeadingBold', '', '2');
                            
        if ($IN->GBL('U'))
        {
        	$r .= $DSP->qspan('success', $LANG->line('localization_updated'));
        }
        else
        {
        	$r .= $title;
        }

		$r .= $DSP->td_c().
              $DSP->tr_c();
	
			$r .= $DSP->tr();
			$r .= $DSP->table_qcell('tableCellTwo', $DSP->qspan('defaultBold', $LANG->line('timezone')), '50%');
			$r .= $DSP->table_qcell('tableCellTwo', $LOC->timezone_menu(($query->row['timezone'] == '') ? 'UTC' : $query->row['timezone']), '50%');
			$r .= $DSP->tr_c();		

			$r .= $DSP->tr();
			$r .= $DSP->td('tableCellOne', '100%', '2');
			$r .= $DSP->input_checkbox('daylight_savings', 'y', ($query->row['daylight_savings'] == 'y') ? 1 : '').' '.$LANG->line('daylight_savings_time');
			$r .= $DSP->td_c();
			$r .= $DSP->tr_c();

	
			$r .= $DSP->tr();
			$r .= $DSP->table_qcell('tableCellTwo', $DSP->qspan('defaultBold', $LANG->line('time_format')), '50%');
			$r .= $DSP->td('tableCellTwo', '50%');
			$r .= $DSP->input_select_header('time_format');    
			$r .= $DSP->input_select_option('us', $LANG->line('united_states'), ($SESS->userdata['time_format'] == 'us') ? 1 : '');    
			$r .= $DSP->input_select_option('eu', $LANG->line('european'), ($SESS->userdata['time_format'] == 'eu') ? 1 : '');
			$r .= $DSP->input_select_footer();
			$r .= $DSP->td_c();
			$r .= $DSP->tr_c();		
	
			$r .= $DSP->tr();
			$r .= $DSP->table_qcell('tableCellOne', $DSP->qspan('defaultBold', $LANG->line('language_choice')), '50%');
			$r .= $DSP->table_qcell('tableCellOne', $FNS->language_pack_names(($query->row['language'] == '') ? 'english' : $query->row['language']), '50%');
			$r .= $DSP->tr_c();		


        $r .= $DSP->table_c(); 

                

        $r .= $DSP->div().BR.BR.
              $DSP->input_submit($LANG->line('update')).
              $DSP->div_c().
              $DSP->form_c();
    
    
        return $this->account_wrapper($title, $title, $r);
    }
    // END
    



    // -----------------------------------
    //  Localization update
    // -----------------------------------   

    function localization_update()
    {
        global $IN, $FNS, $DB;
       
        if (FALSE === ($id = $this->auth_id()))
        {
            return $DSP->no_access_message();
        }
        
        $data['language']    = $_POST['deft_lang'];
        $data['timezone']    = $_POST['server_timezone'];
        $data['time_format'] = $_POST['time_format'];

        $data['daylight_savings'] = ($IN->GBL('daylight_savings', 'POST') == 'y') ? 'y' : 'n';
        
        $DB->query($DB->update_string('exp_members', $data, "member_id = '$id'"));   
        
        $FNS->redirect(BASE.AMP.'C=myaccount'.AMP.'M=localization'.AMP.'id='.$id.AMP.'U=1');
        exit;    
    }
    // END



    // -----------------------------------
    //  Notepad form
    // -----------------------------------   

    function notepad()
    {
        global $IN, $DB, $DSP, $SESS, $LANG;
        
        if (FALSE === ($id = $this->auth_id()))
        {
            return $DSP->no_access_message();
        }

        $title = $LANG->line('notepad');

        if ($id != $SESS->userdata['member_id'])
        {
            return $this->account_wrapper($title, $title, $LANG->line('only_self_notpad_access'));
        }
        
        $query = $DB->query("SELECT notepad, notepad_size FROM exp_members WHERE member_id = '$id'");
    
        $r  = $DSP->form('C=myaccount'.AMP.'M=notepad_update').
              $DSP->input_hidden('id', $id);
              
        $r .= $DSP->table('', '0', '10', '100%').
              $DSP->tr().
              $DSP->td('tableHeadingLargeBold', '', '2');
		
		if ($IN->GBL('U'))
		{
			$r .= $DSP->qdiv('success', $LANG->line('notepad_updated'));
		}
		else
		{
			$r .= $title;
		}
              
        $r .= $DSP->td_c().
              $DSP->tr_c();
              			
			$r .= $DSP->tr();
			$r .= $DSP->td('tableCellOne', '100%', '2');
			$r .= $LANG->line('notepad_instructions');
			$r .= $DSP->td_c();
			$r .= $DSP->tr_c();
			
			$r .= $DSP->tr();
			$r .= $DSP->td('tableCellTwo', '100%', '5');
			$r .= $DSP->input_textarea('notepad', $query->row['notepad'], $query->row['notepad_size'], 'textarea', '100%');
			$r .= $DSP->td_c();
			$r .= $DSP->tr_c();
			
			$r .= $DSP->tr();
			$r .= $DSP->table_qcell('tableCellOne', $DSP->qspan('defaultBold', $LANG->line('notepad_size')), '20%');
			$r .= $DSP->table_qcell('tableCellOne', $DSP->input_text('notepad_size', $query->row['notepad_size'], '4', '2', 'input', '40px'), '80%');
			$r .= $DSP->tr_c();

        $r .= $DSP->table_c(); 

        $r .= $DSP->div().BR.
              $DSP->input_submit($LANG->line('update')).
              $DSP->div_c().
              $DSP->form_c();
    
        return $this->account_wrapper($title, $title, $r);
    }
    // END


    // ----------------------------------
    //  Update notepad
    // ----------------------------------
    
    function notepad_update()
    {  
        global $FNS, $DB, $SESS;

        if (FALSE === ($id = $this->auth_id()))
        {
            return $DSP->no_access_message();
        }

        if ($id != $SESS->userdata['member_id'])
        {
            return false;
        }
       
        $notepad_size = ( ! is_numeric($_POST['notepad_size'])) ? 18 : $_POST['notepad_size'];

        $DB->query("UPDATE exp_members SET notepad = '".$DB->escape_str($_POST['notepad'])."', notepad_size = '".$notepad_size."' WHERE member_id ='".$id."'");
        
        $FNS->redirect(BASE.AMP.'C=myaccount'.AMP.'M=notepad'.AMP.'id='.$id.AMP.'U=1');
        exit;    
    }
    // END



    // -----------------------------------
    //  Administrative options
    // -----------------------------------   

    function administrative_options()
    {
        global $IN, $DB, $DSP, $FNS, $SESS, $LANG;
        
        if ( ! $DSP->allowed_group('can_admin_members'))
        {
            return $DSP->no_access_message();
        }
        
        if (FALSE === ($id = $this->auth_id()))
        {
            return $DSP->no_access_message();
        }
        
        $title = $LANG->line('administrative_options');
    
        $query = $DB->query("SELECT ip_address, in_authorlist, group_id FROM exp_members WHERE member_id = '$id'");

        foreach ($query->row as $key => $val)
        {
            $$key = $val;
        }
        
        $r  = $DSP->form('C=myaccount'.AMP.'M=administration_update').    
              $DSP->input_hidden('id', $id);
              
        $r .= $DSP->table('', '0', '10', '100%').
              $DSP->tr().
              $DSP->td('tableHeadingLargeBold', '', '2');
		
		if ($IN->GBL('U'))
		{
			$r .= $DSP->qdiv('success', $LANG->line('administrative_options_updated'));
		}
		else
		{
			$r .= $title;
		}
              
        $r .= $DSP->td_c().
              $DSP->tr_c();
              		              
              
        // Member groups assignment
        
        if ($DSP->allowed_group('can_admin_mbr_groups'))
        {                   
            if ($SESS->userdata['group_id'] != 1)
            {
                $sql = "SELECT group_id, group_title FROM exp_member_groups WHERE is_locked = 'n' order by group_title";
            }
            else
            {
                $sql = "SELECT group_id, group_title FROM exp_member_groups order by group_title";
            }
                 
            $query = $DB->query($sql);
            
            if ($query->num_rows > 0)
            {
            
				$r .= $DSP->tr();
				$r .= $DSP->table_qcell('tableCellOne', $DSP->qdiv('defaultBold', $LANG->line('member_group_assignment')).$DSP->qdiv('itemWrapper', $DSP->qdiv('alert', $LANG->line('member_group_warning'))), '50%');
        
        		$menu = $DSP->input_select_header('group_id');
							
				foreach ($query->result as $row)
				{					
					// If the current user is not a Super Admin
					// we'll limit the member groups in the list
					
					if ($SESS->userdata['group_id'] != 1)
					{
						if ($row['group_id'] == 1)
						{
							continue;
						}
					}                 
	
					$menu .= $DSP->input_select_option($row['group_id'], $row['group_title'], ($row['group_id'] == $group_id) ? 1 : '');
				}
				
				$menu .= $DSP->input_select_footer();
	
				$r .= $DSP->table_qcell('tableCellOne', $menu, '80%');
				$r .= $DSP->tr_c();
            	
			}
        }
	
		$r .= $DSP->tr();
		$r .= $DSP->td('tableCellOne', '100%', '2');
		$r .= $DSP->input_checkbox('in_authorlist', 'y', ($in_authorlist == 'y') ? 1 : '').NBS.$DSP->qspan('defaultBold', $LANG->line('include_in_multiauthor_list'));
		$r .= $DSP->td_c();
		$r .= $DSP->tr_c();

        $r .= $DSP->table_c(); 
        
        $r .= $DSP->div().BR.
              $DSP->input_submit($LANG->line('update')).
              $DSP->div_c().
              $DSP->form_c();
    
    
        return $this->account_wrapper($title, $title, $r);
    }
    // END



    // -----------------------------------
    //  Update administrative options
    // -----------------------------------   

    function administration_update()
    {
        global $IN, $DB, $DSP, $FNS, $SESS, $LANG;
        
        if ( ! $DSP->allowed_group('can_admin_members'))
        {
            return $DSP->no_access_message();
        }
                
        if (FALSE === ($id = $this->auth_id()))
        {
            return $DSP->no_access_message();
        }
                        
        $data['in_authorlist'] = ($IN->GBL('in_authorlist', 'POST') == 'y') ? 'y' : 'n';
        
        if ($IN->GBL('group_id', 'POST'))
        {        
            if ( ! $DSP->allowed_group('can_admin_mbr_groups'))
            {
                return $DSP->no_access_message();
            } 
            
            $data['group_id'] = $_POST['group_id'];
            
            
            if ($_POST['group_id'] == '1')
            {
            	if ($SESS->userdata['group_id'] != '1')
            	{
                	return $DSP->no_access_message();
            	}
            }
			else
			{
				if ($SESS->userdata['member_id'] == $id)
				{
            		return $DSP->error_message($LANG->line('super_admin_demotion_alert'));
				}
			}
        }   
        
        $DB->query($DB->update_string('exp_members', $data, "member_id = '$id'"));  
        
        $FNS->redirect(BASE.AMP.'C=myaccount'.AMP.'M=administration'.AMP.'id='.$id.AMP.'U=1');
        exit;    
    }
    // END
    
    
    
    //-----------------------------------------------------------
    //  Quick links
    //-----------------------------------------------------------

    function quick_links_form()
    { 
        global $IN, $DSP, $REGX, $LANG, $SESS, $DB;
                                
        if (FALSE === ($id = $this->auth_id()))
        {
            return $DSP->no_access_message();
        }

        if ($id != $SESS->userdata['member_id'])
        {
            return $this->account_wrapper($LANG->line('quick_links'), $LANG->line('quick_links'), $LANG->line('only_self_qucklink_access'));
        }
                
        $r = $DSP->div('tableHeading');     
        
        if ($IN->GBL('U'))
        {
        	$r .= $DSP->qdiv('success', NBS.$LANG->line('quicklinks_updated'));
        }
        else
        {
			$r .= $DSP->qdiv('itemWrapper', $LANG->line('quick_link_description'))
				 .$DSP->qdiv('itemWrapper', $LANG->line('quick_link_description_more'));
        } 
                
        $r .= $DSP->div_c();
        
        $r .= $DSP->form('C=myaccount'.AMP.'M=quicklinks_update').        
              $DSP->input_hidden('id', $id);
        
        $r .= $DSP->table('', '0', '0', '100%').
              $DSP->tr().
              $DSP->td('tablePad'); 

        $r .= $DSP->table('', '0', '', '100%').
              $DSP->tr().
              $DSP->table_qcell('tableHeadingBold', $LANG->line('link_title')).
              $DSP->table_qcell('tableHeadingBold', $LANG->line('link_url')).
              $DSP->table_qcell('tableHeadingBold', $LANG->line('link_order')).
              $DSP->tr_c();      
        
        $query = $DB->query("SELECT quick_links FROM exp_members WHERE member_id = '$id'");
         
        $i = 0;

        if ($query->row['quick_links'] != '')
        {             
            foreach (explode("\n", $query->row['quick_links']) as $row)
            {      
                $style = ($i % 2) ? 'tableCellOne' : 'tableCellTwo'; $i++;                               
                
                $x = explode('|', $row);
                
                $title = (isset($x['0'])) ? $x['0'] : '';
                $link  = (isset($x['1'])) ? $x['1'] : '';
                $order = (isset($x['2'])) ? $x['2'] : $i;
                
                
                $r .= $DSP->tr().
                      $DSP->table_qcell($style, $DSP->input_text('title_'.$i, $title, '20', '40', 'input', '100%'), '40%').
                      $DSP->table_qcell($style, $DSP->input_text('link_'.$i,   $link, '20', '120', 'input', '100%'), '55%').
                      $DSP->table_qcell($style, $DSP->input_text('order_'.$i, $order, '2', '3', 'input', '30px'), '5%').
                      $DSP->tr_c();
            }
        }            
        
        $style = ($i % 2) ? 'tableCellOne' : 'tableCellTwo'; $i++;

        $r .= $DSP->tr().
              $DSP->table_qcell($style, $DSP->input_text('title_'.$i,  '', '20', '40', 'input', '100%'), '40%').
              $DSP->table_qcell($style, $DSP->input_text('link_'.$i,  'http://', '20', '120', 'input', '100%'), '60%').
              $DSP->table_qcell($style, $DSP->input_text('order_'.$i, $i, '2', '3', 'input', '30px'), '5%').
              $DSP->tr_c();
              
        $r .= $DSP->table_c();  
        
        $r .= $DSP->td_c()   
             .$DSP->tr_c()      
             .$DSP->table_c();      

        $r .= $DSP->qdiv('itemWrapper', $DSP->qdiv('highlight', NBS.$LANG->line('quicklinks_delete_instructions')).BR);     
              
        $r .= $DSP->qdiv('', $DSP->input_submit($LANG->line('submit'), 'submit')).
              $DSP->form_c();
        
        return $this->account_wrapper($LANG->line('quick_links'), $LANG->line('quick_links'), $r);
    }
    // END  
    
    
      
    // -----------------------------------------
    //  Save quick links
    // -----------------------------------------
        
    function quick_links_update()
    {
        global $IN, $FNS, $LANG, $DB, $DSP, $SESS;
        
        if (FALSE === ($id = $this->auth_id()))
        {
            return $DSP->no_access_message();
        }
        
        if ($id != $SESS->userdata['member_id'])
        {
            return false;
        }
        
        $safety = array();
        $dups	= FALSE;
        
        foreach ($_POST as $key => $val)
        {
            if (strstr($key, 'title_') AND $val != '')
            {                
                $i = $_POST['order_'.substr($key, 6)];
                
                if ( ! isset($safety[$i]))
                {
                	$safety[$i] = true;
                }
                else
                {
					$dups = TRUE;
                }            
			}
		}
		
		if ($dups)
		{
			$i = 1;
		
			foreach ($_POST as $key => $val)
			{
				if (strstr($key, 'title_') AND $val != '')
				{                
					$_POST['order_'.substr($key, 6)] = $i;

					$i++;
				}
			}		
		}

        $data = array();
        
        foreach ($_POST as $key => $val)
        {
            if (strstr($key, 'title_') AND $val != '')
            {
                $n = substr($key, 6);
                
                $i = $_POST['order_'.$n];
                
                $data[$i] = $i.'|'.$_POST['title_'.$n].'|'.$_POST['link_'.$n].'|'.$_POST['order_'.$n]."\n";
            }
        }
                           
        sort($data);
                        
        $str = '';
        
        foreach ($data as $key => $val)
        {
            $str .= substr(strstr($val, '|'), 1);
        }
        
        $DB->query("UPDATE exp_members SET quick_links = '".trim($str)."' WHERE member_id = '$id'");

        $FNS->redirect(BASE.AMP.'C=myaccount'.AMP.'M=quicklinks'.AMP.'id='.$id.AMP.'U=1');
        exit;    
    }
    // END 

  

    // -----------------------------------
    //  Bookmarklet Form
    // -----------------------------------   
    
    function bookmarklet()
    {  
        global $DSP, $DB, $SESS, $FNS, $LANG;

        if (FALSE === ($id = $this->auth_id()))
        {
            return $DSP->no_access_message();
        }

        // Is the user authorized to access the publish page?
        // And does the user have at least one blog assigned?
        // If not, show the no access message

        if ( ! $DSP->allowed_group('can_access_publish') || ! count($FNS->fetch_assigned_weblogs()) > 0)
        {
            return $DSP->no_access_message();
        }
        
        $title = $LANG->line('bookmarklet');
        
        //-------------------------------------------------
        // Fetch the blogs the user is allowed to post in
        //-------------------------------------------------
                
        if ($SESS->userdata['group_id'] != 1) 
        { 
            $allowed_blogs = $FNS->fetch_assigned_weblogs();
            
            // If there aren't any blogs assigned to the user, bail out
            
            if (count($allowed_blogs) == 0)
            {
                return $DSP->no_access_message($LANG->line('no_blogs_assigned_to_user'));
            }
        
            // Build query
            
            $sql = "SELECT weblog_id, blog_title FROM exp_weblogs WHERE ";
            
            $sql .= " (";
        
            foreach ($allowed_blogs as $val)
            {
                $sql .= " weblog_id = '".$val."' OR"; 
            }
            
            $sql = substr($sql, 0, -2).')';
                    
            $sql .= " ORDER BY blog_title";
       }
       else
       {
            $sql = "SELECT weblog_id, blog_title FROM exp_weblogs WHERE is_user_blog = 'n' ORDER BY blog_title";
       }

        
        $query = $DB->query($sql);
        
        if ($query->num_rows == 0)
        {
            return $DSP->no_access_message($LANG->line('no_blogs_assigned_to_user'));
        }
                
        // Build the output
        
        $r  = $DSP->form('C=myaccount'.AMP.'M=bookmarklet_fields').
              $DSP->input_hidden('id', $id);


		$r .= $DSP->div('bigPad');
        $r .= $DSP->heading($title);
        
        $r .= $DSP->qdiv('itemWrapper', $LANG->line('bookmarklet_info'));
        
        $r .= $DSP->div('itemWrapper').
              $DSP->qdiv('itemTitle', $LANG->line('bookmarklet_name')).
              $DSP->qdiv('itemWrapper', $LANG->line('single_word_no_spaces')).
              $DSP->input_text('bm_name', $LANG->line('bookmarklet'), '35', '50', 'input', '300px').
              $DSP->div_c();

        $r .= $DSP->div('itemWrapper').
              $DSP->qdiv('itemTitle', $LANG->line('weblog_name'));
                            
              $r .= $DSP->input_select_header('weblog_id');
            
            foreach ($query->result as $row)
            {
                $r .= $DSP->input_select_option($row['weblog_id'], $row['blog_title'], '');
            }
            
              $r .= $DSP->input_select_footer();

        $r .= $DSP->div_c();
        
        // Submit button                    

        $r .= $DSP->div('itemWrapper').BR.
              $DSP->input_submit($LANG->line('bookmarklet_next_step')).
              $DSP->div_c().     
              $DSP->div_c();  

        
        $r.=  $DSP->form_c();
        
        return $this->account_wrapper($title, $title, $r);
    }
    // END

   
    // -----------------------------------
    //  Bookmarklet Form - setp two
    // -----------------------------------   
    
    function bookmarklet_fields()
    {  
        global $DSP, $DB, $SESS, $FNS, $LANG;

        if (FALSE === ($id = $this->auth_id()))
        {
            return $DSP->no_access_message();
        }
   
        // Is the user authorized to access the publish page?
        // And does the user have at least one blog assigned?
        // If not, show the no access message

        if ( ! $DSP->allowed_group('can_access_publish') || ! count($FNS->fetch_assigned_weblogs()) > 0)
        {
            return $DSP->no_access_message();
        }

        $title = $LANG->line('bookmarklet');
        
        $bm_name = strip_tags($_POST['bm_name']);
        $bm_name = preg_replace("/[\'\"\?\/\.\,\|\$\#\+]/", "", $bm_name);
        $bm_name = preg_replace("/\s+/", "_", $bm_name);
        $bm_name = stripslashes($bm_name);
        
        $query = $DB->query("SELECT field_group FROM exp_weblogs WHERE weblog_id = '".$_POST['weblog_id']."'");

        if ($query->num_rows == 0)
        {
            return $DSP->no_access_message($LANG->line('no_fields_assigned_to_blog'));
        }

        $field_group = $query->row['field_group'];

        $query = $DB->query("SELECT field_id, field_label FROM  exp_weblog_fields WHERE group_id = '$field_group' ORDER BY field_order");
        
        if ($query->num_rows == 0)
        {
            return $DSP->no_access_message($LANG->line('no_blogs_assigned_to_user'));
        }
                
        // Build the output
        
        $r  = $DSP->form('C=myaccount'.AMP.'M=create_bookmarklet')
             .$DSP->input_hidden('id', $id)
             .$DSP->input_hidden('bm_name',   $bm_name)
             .$DSP->input_hidden('weblog_id', $_POST['weblog_id']);
              
              
		$r .= $DSP->div('bigPad');
        $r .= $DSP->heading($title);
                
        $r .= $DSP->table('', '0', '', '100%').
              $DSP->tr().
              $DSP->td('', '54%', '', '', 'top');
        

        $r .= $DSP->div('itemWrapper').
              $DSP->qdiv('itemTitle', $LANG->line('select_field'));
                            
              $r .= $DSP->input_select_header('field_id');
            
            foreach ($query->result as $row)
            {
                $r .= $DSP->input_select_option('field_id_'.$row['field_id'], $row['field_label'], '');
            }
            
        $r .= $DSP->input_select_footer();

        $r .= $DSP->div_c();
        $r .= $DSP->div_c();
        
        // Submit button                    

        $r .= $DSP->div('itemWrapper').BR.
              $DSP->input_checkbox('safari', 'y', '').' '.$LANG->line('safari_users').BR.BR.
              $DSP->input_submit($LANG->line('create_the_bookmarklet')).
              $DSP->div_c();      

        
        $r.=  $DSP->form_c();
        
        return $this->account_wrapper($title, $title, $r);
    }
    // END
   

    // -----------------------------------
    //  Create Bookmarklet
    // -----------------------------------   
    
    function create_bookmarklet()
    {
        global $LANG, $DSP, $FNS, $PREFS;
        
        if (FALSE === ($id = $this->auth_id()))
        {
            return $DSP->no_access_message();
        }

        // Is the user authorized to access the publish page?
        // And does the user have at least one blog assigned?
        // If not, show the no access message

        if ( ! $DSP->allowed_group('can_access_publish') || ! count($FNS->fetch_assigned_weblogs()) > 0)
        {
            return $DSP->no_access_message();
        }
        
        $title = $LANG->line('bookmarklet');
        
        $bm_name   = $_POST['bm_name'];
        $weblog_id = $_POST['weblog_id'];
        $field_id  = $_POST['field_id'];
        
        $safari = (isset($_POST['safari'])) ? TRUE : FALSE;
        
        $path = $PREFS->ini('cp_url').'?C=publish'.AMP.'Z=1'.AMP.'BK=1'.AMP.'weblog_id='.$weblog_id.AMP;
        
        $type = ($safari) ? "window.getSelection()" : "document.selection?document.selection.createRange().text:document.getSelection()";   
                
		$r  = $DSP->div('bigPad').
        	  $DSP->heading($title).
        	  $DSP->qdiv('success', $LANG->line('bookmarklet_created')).
              $DSP->div('itemWrapper').
              $DSP->qdiv('itemWrapper', $LANG->line('bookmarklet_instructions')).
              $DSP->heading("<br /><a href=\"javascript:bm=$type;void(bmentry=window.open('".$path."title='+escape(document.title)+'&tb_url='+escape(window.location.href)+'&".$field_id."='+escape(bm),'bmentry','')) \">$bm_name</a>",5).
              $DSP->div_c().
              $DSP->div_c();
                
        return $this->account_wrapper($title, $title, $r);
    }
    // END  
}
// END                              
?>