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
|   > Registration functions
|   > Module written by Matt Mecham
|   > Date started: 16th February 2002
|
|	> Module Version Number: 1.0.0
+--------------------------------------------------------------------------
*/


$idx = new Register;

class Register {

    var $output     = "";
    var $page_title = "";
    var $nav        = array();
    var $html       = "";
    var $email      = "";
    
    function Register() {
    	global $ibforums, $DB, $std, $print;
    	
    	//--------------------------------------------
    	// Require the HTML and language modules
    	//--------------------------------------------
    	
		$ibforums->lang = $std->load_words($ibforums->lang, 'lang_register', $ibforums->lang_id );
    	
    	$this->html = $std->load_template('skin_register');
    	
    	$this->base_url        = "{$ibforums->vars['board_url']}/index.{$ibforums->vars['php_ext']}?s={$ibforums->session_id}";
    	$this->base_url_nosess = "{$ibforums->vars['board_url']}/index.{$ibforums->vars['php_ext']}";
    	
    	//--------------------------------------------
    	// Get the emailer module
		//--------------------------------------------
		
		require "./sources/lib/emailer.php";
		
		$this->email = new emailer();
    	
    	//--------------------------------------------
    	// What to do?
    	//--------------------------------------------
    	
    	switch($ibforums->input['CODE']) {
    		
    			
    		case '02':
    			$this->create_account();
    			break;
    			
    		case '03':
    			$this->validate_user();
    			break;

    		case '05':
    			$this->show_manual_form();
    			break;
    			
    		case '06':
    			$this->show_manual_form('lostpass');
    			break;
    			
    		case '07':
    			$this->show_manual_form('newemail');
    			break;
    			
    		case '10':
    			$this->lost_password_start();
    			break;
    		case '11':
    			$this->lost_password_end();
    			break;
    			
    		case '12':
    			$this->coppa_perms_form();
    			break;
    			
    		case 'coppa_two':
    			$this->coppa_two();
    			break;
    			
    		case 'image':
    			$this->show_image();
    			break;
    		

    		default:
    			if ($ibforums->vars['use_coppa'] == 1 and $ibforums->input['coppa_pass'] != 1)
    			{
    				$this->coppa_start();
    			}
    			else
    			{
    				$this->show_reg_form();
    			}
    			break;
    	}
    	
    	// If we have any HTML to print, do so...
    	
    	$print->add_output("$this->output");
        $print->do_output( array( 'TITLE' => $this->page_title, 'JS' => 0, NAV => $this->nav ) );
    		
 	}
 	
 	
 	/*****************************************************/
	// Coppa Start
	// ------------------
	// Asks the registree if they are an old git or not
	/*****************************************************/
	
	function coppa_perms_form()
	{
		global $ibforums, $DB, $std;
		
		echo($this->html->coppa_form());
		exit();
	}
	
	
	
	function coppa_start()
	{
		global $ibforums, $DB, $std;
		
		$coppa_date = date( 'j-F y', mktime(0,0,0,date("m"),date("d"),date("Y")-13) );
		
		$ibforums->lang['coppa_form_text'] = str_replace( "<#FORM_LINK#>", "<a href='{$ibforums->base_url}&act=Reg&CODE=12'>{$ibforums->lang['coppa_link_form']}</a>", $ibforums->lang['coppa_form_text']);
		
		$this->output .= $this->html->coppa_start($coppa_date);
		
		$this->page_title = $ibforums->lang['coppa_title'];
		
    	$this->nav        = array( $ibforums->lang['coppa_title'] );
 	
 	}
 	
 	function coppa_two()
	{
		global $ibforums, $DB, $std;
		
		$ibforums->lang['coppa_form_text'] = str_replace( "<#FORM_LINK#>", "<a href='{$ibforums->base_url}&act=Reg&CODE=12'>{$ibforums->lang['coppa_link_form']}</a>", $ibforums->lang['coppa_form_text']);
		
		$this->output .= $this->html->coppa_two();
		
		$this->page_title = $ibforums->lang['coppa_title'];
		
    	$this->nav        = array( $ibforums->lang['coppa_title'] );
 	
 	}
 	
 	/*****************************************************/
	// lost_password_start
	// ------------------
	// Simply shows the lostpassword form
	// What do you want? Blood?
	/*****************************************************/
	
	function lost_password_start()
	{
		global $ibforums, $DB, $std;
		
		$this->page_title = $ibforums->lang['lost_pass_form'];
		
    	$this->nav        = array( $ibforums->lang['lost_pass_form'] );

    	$this->output    .= $this->html->lost_pass_form();
    }
    
    
    
    
    function lost_password_end()
    {
    	global $ibforums, $DB, $std, $HTTP_POST_VARS, $print;
    	
    	if ($HTTP_POST_VARS['member_name'] == "")
    	{
    		$std->Error( array( 'LEVEL' => 1, 'MSG' => 'no_username' ) );
    	}
    	
    	//------------------------------------------------------------
		// Check for input and it's in a valid format.
		//------------------------------------------------------------
		
		$member_name = trim(strtolower($ibforums->input['member_name']));
		
		if ($member_name == "")
		{
			$std->Error( array( 'LEVEL' => 1, 'MSG' => 'no_username' ) );
		}
    	
    	//------------------------------------------------------------
		// Attempt to get the user details from the DB
		//------------------------------------------------------------
		
		$DB->query("SELECT name, id, email, mgroup, validate_key FROM ibf_members WHERE LOWER(name)='$member_name'");
		
		if ( !$DB->get_num_rows() )
		{
			$std->Error( array( 'LEVEL' => 1, 'MSG' => 'no_such_user' ) );
		}
		else
		{
			$member = $DB->fetch_row();
			
			//------------------------------------------------------------
			// Is there a validation key? If so, we'd better not touch it
			//------------------------------------------------------------
			
			if ($member['mgroup'] == $ibforums->vars['auth_group'])
			{
				$std->Error( array( 'LEVEL' => 1, 'MSG' => 'val_key_present' ) );
			}
			
			if ($member['id'] == "")
			{
				$std->Error( array( 'LEVEL' => 1, 'MSG' => 'no_such_user' ) );
			}
			
			$new_pass     = $std->make_password();
			$validate_key = $std->make_password();
			$md5_pass     = md5($new_pass);
			
			//------------------------------------------------------------
			// Update the DB for this member.
			//------------------------------------------------------------
			
			$DB->query("UPDATE ibf_members SET new_pass='$md5_pass', validate_key='$validate_key' WHERE id='".$member['id']."'");
			
			//------------------------------------------------------------
			// Send out the email.
			//------------------------------------------------------------
			
    		$this->email->get_template("lost_pass");
				
			$this->email->build_message( array(
												'NAME'         => $member['name'],
												'PASSWORD'     => $new_pass,
												'MAN_LINK'     => $this->base_url_nosess."?act=Reg&CODE=06",
												'EMAIL'        => $member['email'],
												'ID'           => $member['id'],
												'CODE'         => $validate_key,
												'IP_ADDRESS'   => $ibforums->input['IP_ADDRESS'],
											  )
										);
										
			$this->email->subject = $ibforums->lang['lp_subject'].' '.$ibforums->vars['board_name'];
			$this->email->to      = $member['email'];
			
			$this->email->send_mail();
			
			$std->my_setcookie( 'pass_hash' , '-1', 0 );
			
			$print->redirect_screen( $ibforums->lang['lp_redirect'], 'act=Reg&CODE=06' );
		}
    	
    }
 	
 	/*****************************************************/
	// show_reg_form
	// ------------------
	// Simply shows the registration form, no - really! Thats
	// all it does. It doesn't make the tea or anything.
	// Just the registration form, no more - no less.
	// Unless your server went down, then it's just useless.
	/*****************************************************/   
    
    function show_reg_form($errors = "") {
    	global $ibforums, $DB, $std;
    	
    	if ($ibforums->vars['no_reg'] == 1)
    	{
    		$std->Error( array( 'LEVEL' => 1, 'MSG' => 'reg_off' ) );
    	}
    	
    	if ($ibforums->vars['reg_auth_type'])
    	{
    		$ibforums->lang['std_text'] .= "<br>" . $ibforums->lang['email_validate_text'];
    	}
    	
    	//-----------------------------------------------
		// Clean out anti-spam stuffy
		//-----------------------------------------------
		
		if ($ibforums->vars['reg_antispam'])
		{
		
			// Get a time roughly 6 hours ago...
			
			$r_date = time() - (60*60*6);
			
			// Remove old reg requests from the DB
			
			$DB->query("DELETE FROM ibf_reg_antispam WHERE ctime < '$r_date'");
			
			// Set a new ID for this reg request...
			
			$regid = md5( uniqid(microtime()) );
			
			// Set a new 6 character numerical string
			
			mt_srand ((double) microtime() * 1000000);
			
			$reg_code = mt_rand(100000,999999);
			
			// Insert into the DB
			
			$str = $DB->compile_db_insert_string( array (
															'regid'      => $regid,
															'regcode'    => $reg_code,
															'ip_address' => $ibforums->input['IP_ADDRESS'],
															'ctime'      => time(),
												)       );
												
			$DB->query("INSERT INTO ibf_reg_antispam ({$str['FIELD_NAMES']}) VALUES ({$str['FIELD_VALUES']})");
		
		}
    	
    	//-----------------------------------------------
		// Custom profile fields stuff
		//-----------------------------------------------
		
		$required_output = "";
		$optional_output = "";
		$field_data     = array();
		
		$DB->query("SELECT * from ibf_pfields_data WHERE fedit=1 AND fshowreg=1 ORDER BY forder");
		
		while( $row = $DB->fetch_row() )
		{
			$form_element = "";
			
			if ( $row['freq'] == 1 )
			{
				$ftype = 'required_output';
			}
			else
			{
				$ftype = 'optional_output';
			}
			
			if ( $row['ftype'] == 'drop' )
			{
				$carray = explode( '|', trim($row['fcontent']) );
				
				$d_content = "";
				
				foreach( $carray as $entry )
				{
					$value = explode( '=', $entry );
					
					$ov = trim($value[0]);
					$td = trim($value[1]);
					
					if ($ov !="" and $td !="")
					{
						$d_content .= "<option value='$ov'>$td</option>\n";
					}
				}
				
				if ($d_content != "")
				{
					$form_element = $this->html->field_dropdown( 'field_'.$row['fid'], $d_content );
				}
			}
			else if ( $row['ftype'] == 'area' )
			{
				$form_element = $this->html->field_textarea( 'field_'.$row['fid'], "" );
			}
			else
			{
				$form_element = $this->html->field_textinput( 'field_'.$row['fid'], "" );
			}
			
			${$ftype} .= $this->html->field_entry( $row['ftitle'], $row['fdesc'], $form_element );
		}
    	
    	$this->page_title = $ibforums->lang['registration_form'];
    	$this->nav        = array( $ibforums->lang['registration_form'] );
    	
    	$coppa = ($ibforums->input['coppa_user'] == 1) ? 1 : 0;
    	
    	if ($errors != "")
    	{
    		$this->output .= $this->html->errors( $ibforums->lang[$errors]);
    	}

    	$this->output    .= $this->html->ShowForm( array( 'TEXT'        => $ibforums->lang['std_text'],
    												      'RULES'       => $ibforums->lang['click_wrap'],
    												      'coppa_user'  => $coppa,
    											 )      );
    											 
    	if ($ibforums->vars['reg_antispam'])
    	{
    		$this->output = str_replace( "<!--{REG.ANTISPAM}-->", $this->html->reg_antispam( $regid ), $this->output );
    	}
    	
    	if ($required_output != "")
		{
			$this->output = str_replace( "<!--{REQUIRED.FIELDS}-->", "\n".$required_output, $this->output );
		}
		
		if ($optional_output != "")
		{
			$this->output = str_replace( "<!--{OPTIONAL.FIELDS}-->", $this->html->optional_title()."\n".$optional_output, $this->output );
		}
    }
    
   	/*****************************************************/
	// create_account
	// ------------------
	// Now this is a really good subroutine. It adds the member
	// to the members table in the database. Yes, really fancy
	// this one. It also finds the time to see if we need to
	// check any email verification type malarky before we
	// can use this brand new account. It's like buying a new
	// car and getting it towed home and being told the keys
	// will be posted later. Although you can't polish this
	// routine while you're waiting.
	/*****************************************************/ 
	
	function create_account() {
		global $ibforums, $std, $DB, $print, $HTTP_POST_VARS;
		
		if ($HTTP_POST_VARS['act'] == "")
		{
			$std->Error( array( 'LEVEL' => 1, 'MSG' => 'complete_form' ) );
		}
		
		if ($ibforums->vars['no_reg'] == 1)
    	{
    		$std->Error( array( 'LEVEL' => 1, 'MSG' => 'reg_off' ) );
    	}
    	
    	$coppa = ($ibforums->input['coppa_user'] == 1) ? 1 : 0;
    	
    	//----------------------------------
		// Custom profile field stuff
		//----------------------------------
		
		$custom_fields = array();
		
		$DB->query("SELECT * from ibf_pfields_data WHERE fedit=1");
		
		while ( $row = $DB->fetch_row() )
		{
			if ($row['freq'] == 1)
			{
				if ($HTTP_POST_VARS[ 'field_'.$row['fid'] ] == "")
				{
					$std->Error( array( 'LEVEL' => 1, 'MSG' => 'complete_form' ) );
				}
			}
			
			if ($row['fmaxinput'] > 0)
			{
				if (strlen($HTTP_POST_VARS[ 'field_'.$row['fid'] ]) > $row['fmaxinput'])
				{
					$std->Error( array( 'LEVEL' => 1, 'MSG' => 'cf_to_long', 'EXTRA' => $row['ftitle'] ) );
				}
			}
			
			$custom_fields[ 'field_'.$row['fid'] ] = $ibforums->input[ 'field_'.$row['fid'] ];
		}
		
		//---------------------------------------
		// Trim off the username and password
		
		$in_username = trim($ibforums->input['UserName']);
		$in_password = trim($ibforums->input['PassWord']);
		$in_email    = strtolower( trim($ibforums->input['EmailAddress']) );
		
		$ibforums->input['EmailAddress_two'] = strtolower( trim($ibforums->input['EmailAddress_two']) );
		
		if ($ibforums->input['EmailAddress_two'] != $in_email)
		{
			$this->show_reg_form('err_email_address_match');
			return;
		}
		
		//+--------------------------------------------
		//| Check for errors in the input.
		//+--------------------------------------------
		
		if (empty($in_username))
		{
			$this->show_reg_form('err_no_username');
			return;
		}
		if (strlen($in_username) < 3)
		{
			$this->show_reg_form('err_no_username');
			return;
		}
		if (strlen($in_username) > 32) 
		{
			$this->show_reg_form('err_no_username');
			return;
		}
		if (empty($in_password))
		{
			$this->show_reg_form('err_no_password');
			return;
		}
		if (strlen($in_password) < 3)
		{
			$this->show_reg_form('err_no_password');
			return;
		}
		if (strlen($in_password) > 32) 
		{
			$this->show_reg_form('err_no_password');
			return;
		}
		if ($ibforums->input['PassWord_Check'] != $in_password)
		{
			$this->show_reg_form('err_pass_match');
			return;
		}
		if (strlen($in_email) < 6)
		{
			$this->show_reg_form('err_invalid_email');
			return;
		}
		
		//+--------------------------------------------
		//| Check the email address
		//+--------------------------------------------
		
		$in_email = $std->clean_email($in_email);
		
		if (! $in_email )
		{
			$this->show_reg_form('err_invalid_email');
			return;
		}
		
		//+--------------------------------------------
		//| Is this name already taken?
		//+--------------------------------------------
		
		$DB->query("SELECT id FROM ibf_members WHERE LOWER(name)='".strtolower($in_username)."'");
		$name_check = $DB->fetch_row();
		
		if ($name_check['id'])
		{
			$this->show_reg_form('err_user_exists');
			return;
		}
		
		if (strtolower($in_username) == 'guest')
		{
			$this->show_reg_form('err_user_exists');
			return;
		}
		
		//+--------------------------------------------
		//| Is this email addy taken?
		//+--------------------------------------------
		
		if (! $ibforums->vars['allow_dup_email'] )
		{
			$DB->query("SELECT id FROM ibf_members WHERE email='".$in_email."'");
			$email_check = $DB->fetch_row();
			if ($email_check['id'])
			{
				$this->show_reg_form('err_email_exists');
				return;
			}
		}
		
		//+--------------------------------------------
		//| Are they in the reserved names list?
		//+--------------------------------------------
		
		if ($ibforums->vars['ban_names'])
		{
			$names = explode( "|" , $ibforums->vars['ban_names'] );
			foreach ($names as $n)
			{
				if (preg_match( "/$n/i", $in_username ))
				{
					$this->show_reg_form('err_user_exists');
					break;
					return;
				}
			}
		}	
		
		//+--------------------------------------------
		//| Are they banned?
		//+--------------------------------------------
		
		if ($ibforums->vars['ban_ip'])
		{
			$ips = explode( "|", $ibforums->vars['ban_ip'] );
			foreach ($ips as $ip)
			{
				$ip = preg_replace( "/\*/", '.*' , $ip );
				if (preg_match( "/$ip/", $ibforums->input['IP_ADDRESS'] ))
				{
					$std->Error( array( LEVEL => 1, MSG => 'you_are_banned' ) );
				}
			}
		}
		
		if ($ibforums->vars['ban_email'])
		{
			$ips = explode( "|", $ibforums->vars['ban_email'] );
			foreach ($ips as $ip)
			{
				$ip = preg_replace( "/\*/", '.*' , $ip );
				if (preg_match( "/$ip/", $in_email ))
				{
					$std->Error( array( LEVEL => 1, MSG => 'you_are_banned' ) );
				}
			}
		}
		
		//+--------------------------------------------
		//| Check the reg_code
		//+--------------------------------------------
		
		if ($ibforums->vars['reg_antispam'])
		{
			if ($ibforums->input['regid'] == "")
			{
				$this->show_reg_form('err_reg_code');
				return;
			}
			
			$DB->query("SELECT * FROM ibf_reg_antispam WHERE regid='".trim(addslashes($ibforums->input['regid']))."'");
			
			if ( ! $row = $DB->fetch_row() )
			{
				$this->show_reg_form('err_reg_code');
				return;
			}
			
			if ( trim( intval($ibforums->input['reg_code']) ) != $row['regcode'] )
			{
				$this->show_reg_form('err_reg_code');
				return;
			}
		}
		
		//+--------------------------------------------
		//| Build up the hashes
		//+--------------------------------------------
		
		$mem_group = $ibforums->vars['member_group'];
		
		//+--------------------------------------------
		//| Are we asking the member or admin to preview?
		//+--------------------------------------------
		
		if ($ibforums->vars['reg_auth_type'])
		{
			$mem_group = $ibforums->vars['auth_group'];
		}
		else if ($coppa == 1)
		{
			$mem_group = $ibforums->vars['auth_group'];
		}
		
		//+--------------------------------------------
		//| Find the highest member id, and increment it
		//| auto_increment not used for guest id 0 val.
		//+--------------------------------------------
		
		$DB->query("SELECT MAX(id) as new_id FROM ibf_members");
		$r = $DB->fetch_row();
		
		$member_id = $r['new_id'] + 1;
		
		$member = array(
						 'id'              => $member_id,
						 'name'            => $in_username,
						 'password'        => $in_password,
						 'email'           => $in_email,
						 'mgroup'          => $mem_group,
						 'posts'           => 0,
						 'avatar'          => 'noavatar',
						 'joined'          => time(),
						 'ip_address'      => $ibforums->input['IP_ADDRESS'],
						 'time_offset'     => $ibforums->vars['time_offset'],
						 'view_sigs'       => 1,
						 'email_pm'        => 1,
						 'view_img'        => 1,
						 'view_avs'        => 1,
						 'allow_post'      => 1,
						 'view_pop'        => 1,
						 'vdirs'           => "in:Inbox|sent:Sent Items",
						 'msg_total'       => 0,
						 'new_msg'         => 0,
						 'coppa_user'      => $coppa,
					   );
					   
		
					   
		//+--------------------------------------------
		//| Insert into the DB
		//+--------------------------------------------
		
		$member['password'] = md5( $member['password'] );
		
		$db_string = $std->compile_db_string( $member );
		
		$DB->query("INSERT INTO ibf_members (" .$db_string['FIELD_NAMES']. ") VALUES (". $db_string['FIELD_VALUES'] .")");
		
		unset($db_string);
		
		//+--------------------------------------------
		//| Insert into the custom profile fields DB
		//+--------------------------------------------
		
		$custom_fields['member_id'] = $member['id'];
			
		$db_string = $DB->compile_db_insert_string($custom_fields);
			
		$DB->query("INSERT INTO ibf_pfields_content (".$db_string['FIELD_NAMES'].") VALUES(".$db_string['FIELD_VALUES'].")");
		
		unset($db_string);
		
		//+--------------------------------------------
		
		$validate_key = $std->make_password();
		$time         = time();
		
		
		if ($coppa != 1)
		{
			if ( ($ibforums->vars['reg_auth_type'] == 'user') or ($ibforums->vars['reg_auth_type'] == 'admin') ) {
			
				// We want to validate all reg's via email, after email verificiation has taken place,
				// we restore their previous group and remove the validate_key
				
				$DB->query("UPDATE ibf_members SET validate_key='$validate_key', prev_group='".$ibforums->vars['member_group']."' "
						  ."WHERE id='$member_id'");
				
				
				if ( $ibforums->vars['reg_auth_type'] == 'user' )
				{
				
					$this->email->get_template("reg_validate");
					
					$this->email->build_message( array(
														'THE_LINK'     => $this->base_url_nosess."?act=Reg&CODE=03&uid=".urlencode($member_id)."&aid=".urlencode($validate_key),
														'NAME'         => $member['name'],
														'PASSWORD'     => $in_password,
														'MAN_LINK'     => $this->base_url_nosess."?act=Reg&CODE=05",
														'EMAIL'        => $member['email'],
														'ID'           => $member_id,
														'CODE'         => $validate_key,
													  )
												);
												
					$this->email->subject = "Registration at ".$ibforums->vars['board_name'];
					$this->email->to      = $member['email'];
					
					$this->email->send_mail();
					
					$this->output     = $this->html->show_authorise( $member );
					
				}
				else if ( $ibforums->vars['reg_auth_type'] == 'admin' )
				{
					$this->output     = $this->html->show_preview( $member );
				}
				
				$this->page_title = $ibforums->lang['reg_success'];
				
				$this->nav        = array( $ibforums->lang['nav_reg'] );
			}
	
			else
			{
				
				// We don't want to preview, or get them to validate via email.
				
				$DB->query("UPDATE ibf_stats SET ".
							 "MEM_COUNT=MEM_COUNT+1, ".
							 "LAST_MEM_NAME='" . $member['name'] . "', ".
							 "LAST_MEM_ID='"   . $member['id']   . "'");
							 
				if ($ibforums->vars['new_reg_notify']) {
					
					$date = $std->get_date( time(), 'LONG' );
					
					$this->email->get_template("admin_newuser");
				
					$this->email->build_message( array(
														'DATE'         => $date,
														'MEMBER_NAME'  => $member['name'],
													  )
												);
												
					$this->email->subject = "New Registration at ".$ibforums->vars['board_name'];
					$this->email->to      = $ibforums->vars['email_in'];
					$this->email->send_mail();
				}
				
				$std->my_setcookie("member_id"   , $member['id']      , 1);
				$std->my_setcookie("pass_hash"   , $member['password'], 1);
					
				$std->boink_it($ibforums->base_url.'&act=Login&CODE=autologin&fromreg=1');
			}
		}
		else
		{
			// This is a COPPA user, so lets tell them they registered OK and redirect to the form.
			
			$print->redirect_screen( $ibforums->lang['cp_success'], 'act=Reg&CODE=12' );
		}
				
	} 
    
    /*****************************************************/
	// validate_user
	// ------------------
	// Leave a message after the tone, and I'll amuse myself
	// by pulling faces when hearing the message later.
	/*****************************************************/
	
	function validate_user() {
		global $ibforums, $std, $DB, $print;
		
		//------------------------------------------------------------
		// Check for input and it's in a valid format.
		//------------------------------------------------------------
		
		$in_user_id      = trim(urldecode($ibforums->input['uid']));
		$in_validate_key = trim(urldecode($ibforums->input['aid']));
		$in_type         = trim($ibforums->input['type']);
		
		if ($in_type == "")
		{
			$in_type = 'reg';
		}
		
		//------------------------------------------------------------
		
		if (! preg_match( "/^(?:[\d\w]){6,14}$/", $in_validate_key ) )
		{
			$std->Error( array( 'LEVEL' => 1, 'MSG' => 'data_incorrect' ) );
		}
		
		//------------------------------------------------------------
		
		if (! preg_match( "/^(?:\d){1,}$/", $in_user_id ) )
		{
			$std->Error( array( 'LEVEL' => 1, 'MSG' => 'data_incorrect' ) );
		}
		
		//------------------------------------------------------------
		// Attempt to get the profile of the requesting user
		//------------------------------------------------------------
		
		$DB->query("SELECT id, name, validate_key, prev_group, mgroup, email, new_pass FROM ibf_members WHERE id='$in_user_id'");
		
		if ( !$DB->get_num_rows() )
		{
			$std->Error( array( 'LEVEL' => 1, 'MSG' => 'auth_no_mem' ) );
		}
		
		//------------------------------------------------------------
		
		$member = $DB->fetch_row();
		
		//------------------------------------------------------------
		
		if ( $member['id'] == "" )
		{
			$std->Error( array( 'LEVEL' => 1, 'MSG' => 'auth_no_mem' ) );
		}
		
		//------------------------------------------------------------
		if ( $member['validate_key'] == "")
		{
			$std->Error( array( 'LEVEL' => 1, 'MSG' => 'auth_no_key' ) );
		}
		else if ($member['validate_key'] != $in_validate_key)
		{
			$std->Error( array( 'LEVEL' => 1, 'MSG' => 'auth_key_wrong' ) );
		}
		else
		{
			//------------------------------------------------------------
			// Update the member...
			//------------------------------------------------------------
			
			if ($in_type == 'reg')
			{
			
				if (empty($member['prev_group']))
				{
					$member['prev_group'] = $ibforums->vars['member_group'];
				}
				
				$DB->query("UPDATE ibf_members SET mgroup='".$member['prev_group']."', prev_group='', validate_key='', new_pass='' WHERE id='".$member['id']."'");
			
				//------------------------------------------------------------
				// Update the stats...
				//------------------------------------------------------------
			
				$DB->query("UPDATE ibf_stats SET ".
							 "MEM_COUNT=MEM_COUNT+1, ".
							 "LAST_MEM_NAME='" . $member['name'] . "', ".
							 "LAST_MEM_ID='"   . $member['id']   . "'");
							 
				if ($ibforums->vars['new_reg_notify']) {
					
					$date = $std->get_date( time(), 'LONG' );
					
					$this->email->get_template("admin_newuser");
				
					$this->email->build_message( array(
														'DATE'         => $date,
														'MEMBER_NAME'  => $member['name'],
													  )
												);
												
					$this->email->subject = "New Registration at ".$ibforums->vars['board_name'];
					$this->email->to      = $ibforums->vars['email_in'];
					$this->email->send_mail();
				}
			
			}
			else if ($in_type == 'lostpass')
			{
			
				if ($member['new_pass'] == "")
				{
					$std->Error( array( 'LEVEL' => 1, 'MSG' => 'lp_no_pass' ) );
				}
				
				$DB->query("UPDATE ibf_members SET password='".$member['new_pass']."', prev_group='', validate_key='', new_pass='' WHERE id='".$member['id']."'");
			
			}
			else if ($in_type == 'newemail')
			{
				if (empty($member['prev_group']))
				{
					$member['prev_group'] = $ibforums->vars['member_group'];
				}
				
				$DB->query("UPDATE ibf_members SET mgroup='".$member['prev_group']."', prev_group='', validate_key='', new_pass='' WHERE id='".$member['id']."'");
			}
				
			$text = $ibforums->lang['done_reg_2'];
			$url  = 'act=Login&CODE=00';
			
			
			$print->redirect_screen( $text, $url );
		} 
		
	} 
    
    /*****************************************************/
	// show_board_rules
	// ------------------
	// o_O  ^^
	/*****************************************************/
	
	function show_board_rules() {
		global $ibforums, $DB;
		
		$DB->query("SELECT RULES_TEXT from ib_forum_rules WHERE ID='00'");
		$rules = $DB->fetch_row();
		
		$this->output     = $this->html->show_rules($rules);
		$this->page_title = $ibforums->lang['board_rules'];
		$this->nav        = array( $ibforums->lang['board_rules'] );
	
	}
	
	/*****************************************************/
	// show_manual_form
	// ------------------
	// This feature is not available in an auto option
	/*****************************************************/
	
	function show_manual_form($type='reg') {
		global $ibforums;
		
		$this->output     = $this->html->show_dumb_form($type);
		$this->page_title = $ibforums->lang['activation_form'];
		$this->nav        = array( $ibforums->lang['activation_form'] );
	
	}
	
	function show_image()
	{
		global $ibforums, $DB;
		
		// Init array
		
		$numbers = array( 0 => 'R0lGODlhCAANAJEAAAAAAP////4BAgAAACH5BAQUAP8ALAAAAAAIAA0AAAIUDH5hiKsOnmqSPjtT1ZdnnjCUqBQAOw==',
						  1 => 'R0lGODlhCAANAJEAAAAAAP////4BAgAAACH5BAQUAP8ALAAAAAAIAA0AAAIUjAEWyMqoXIprRkjxtZJWrz3iCBQAOw==',
						  2 => 'R0lGODlhCAANAJEAAAAAAP////4BAgAAACH5BAQUAP8ALAAAAAAIAA0AAAIUDH5hiKubnpPzRQvoVbvyrDHiWAAAOw==',
						  3 => 'R0lGODlhCAANAJEAAAAAAP////4BAgAAACH5BAQUAP8ALAAAAAAIAA0AAAIVDH5hiKbaHgRyUZtmlPtlfnnMiGUFADs=',
						  4 => 'R0lGODlhCAANAJEAAAAAAP////4BAgAAACH5BAQUAP8ALAAAAAAIAA0AAAIVjAN5mLDtjFJMRjpj1Rv6v1SHN0IFADs=',
						  5 => 'R0lGODlhCAANAJEAAAAAAP////4BAgAAACH5BAQUAP8ALAAAAAAIAA0AAAIUhA+Bpxn/DITL1SRjnps63l1M9RQAOw==',
						  6 => 'R0lGODlhCAANAJEAAAAAAP////4BAgAAACH5BAQUAP8ALAAAAAAIAA0AAAIVjIEYyWwH3lNyrQTbnVh2Tl3N5wQFADs=',
						  7 => 'R0lGODlhCAANAJEAAAAAAP////4BAgAAACH5BAQUAP8ALAAAAAAIAA0AAAIUhI9pwbztAAwP1napnFnzbYEYWAAAOw==',
						  8 => 'R0lGODlhCAANAJEAAAAAAP////4BAgAAACH5BAQUAP8ALAAAAAAIAA0AAAIVDH5hiKubHgSPWXoxVUxC33FZZCkFADs=',
						  9 => 'R0lGODlhCAANAJEAAAAAAP////4BAgAAACH5BAQUAP8ALAAAAAAIAA0AAAIVDA6hyJabnnISnsnybXdS73hcZlUFADs=',
						);
		
		if ( $ibforums->input['p'] == "" )
		{
			return false;
		}
		
		if ( $ibforums->input['rc'] == "" )
		{
			return false;
		}
		
		// Get the info from the db
		
		$DB->query("SELECT * FROM ibf_reg_antispam WHERE regid='".trim(addslashes($ibforums->input['rc']))."'");
		
		if ( ! $row = $DB->fetch_row() )
		{
			return false;
		}
		
		$p = intval($ibforums->input['p']) - 1; //substr starts from 0, not 1 :p
		
		$this_number = substr( $row['regcode'], $p, 1 );
		
		flush();
		header("Content-type: image/gif");
		echo base64_decode($numbers[ $this_number ]);
		exit();
		
	}
	
        
}

?>
