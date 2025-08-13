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
|   > UserCP functions library
|   > Module written by Matt Mecham
|   > Date started: 20th February 2002
|
|	> Module Version Number: 1.0.0
+--------------------------------------------------------------------------
*/


class usercp_functions {

	var $class;
	
	function usercp_functions($class) {
		
		$this->class = $class;
	}
	
	function do_skin_langs() {
		global $ibforums, $DB, $std, $print, $HTTP_POST_VARS;
		
		// Check input for 1337 h/\x0r nonsense
		
		if ($HTTP_POST_VARS['act'] == "")
		{
			$std->Error( array( 'LEVEL' => 1, 'MSG' => 'complete_form' ) );
		}
		
		//+----------------------------------------
		
		if ( preg_match( "/\.\./", $ibforums->input['u_skin'] ) )
		{
			$std->Error( array( 'LEVEL' => 1, 'MSG' => 'poss_hack_attempt' ) );
		}
		//+----------------------------------------
		if ( preg_match( "/\.\./", $ibforums->input['u_language'] ) )
		{
			$std->Error( array( 'LEVEL' => 1, 'MSG' => 'poss_hack_attempt' ) );
		}
		
		//+----------------------------------------
		
		$DB->query("SELECT sid FROM ibf_skins WHERE hidden <> 1 AND sid='".$ibforums->input['u_skin']."'");
		
		if (! $DB->get_num_rows() )
		{
			$std->Error( array( 'LEVEL' => 1, 'MSG' => 'skin_not_found' ) );
		}
		
		//+----------------------------------------
		
		$db_string = $DB->compile_db_update_string(  array (
															  'language'    => $ibforums->input['u_language'],
															  'skin       ' => $ibforums->input['u_skin'],
												  )         );
		
		$DB->query("UPDATE ibf_members SET $db_string WHERE id='".$this->class->member['id']."'");
		
		$print->redirect_screen( $ibforums->lang['set_updated'], "act=UserCP&CODE=06" );
	
	}
	
	
	function do_board_prefs() {
		global $ibforums, $DB, $std, $print, $HTTP_POST_VARS;
		
		// Check the input for naughties :D
		
		if ($HTTP_POST_VARS['act'] == "")
		{
			$std->Error( array( 'LEVEL' => 1, 'MSG' => 'complete_form' ) );
		}
		//+----------------------------------------
		if ( ! preg_match( "/^[\-\d\.]+$/", $ibforums->input['u_timezone'] ) )
		{
			$std->Error( array( 'LEVEL' => 1, 'MSG' => 'poss_hack_attempt' ) );
		}
		//+----------------------------------------
		if ( ! preg_match( "/^\d+$/", $ibforums->input['VIEW_IMG'] ) )
		{
			$std->Error( array( 'LEVEL' => 1, 'MSG' => 'poss_hack_attempt' ) );
		}
		//+----------------------------------------
		if ( ! preg_match( "/^\d+$/", $ibforums->input['VIEW_SIGS'] ) )
		{
			$std->Error( array( 'LEVEL' => 1, 'MSG' => 'poss_hack_attempt' ) );
		}
		//+----------------------------------------
		if ( ! preg_match( "/^\d+$/", $ibforums->input['VIEW_AVS'] ) )
		{
			$std->Error( array( 'LEVEL' => 1, 'MSG' => 'poss_hack_attempt' ) );
		}
		//+----------------------------------------
		if ( ! preg_match( "/^\d+$/", $ibforums->input['DO_POPUP'] ) )
		{
			$std->Error( array( 'LEVEL' => 1, 'MSG' => 'poss_hack_attempt' ) );
		}
		
		if ( ! preg_match( "/^\d+$/", $ibforums->input['HIDE_SESS'] ) )
		{
			$std->Error( array( 'LEVEL' => 1, 'MSG' => 'poss_hack_attempt' ) );
		}
		
		//+----------------------------------------
		
		if ($ibforums->vars['postpage_contents'] == "")
		{
			$ibforums->vars['postpage_contents'] = '5,10,15,20,25,30,35,40';
		}
		
		if ($ibforums->vars['topicpage_contents'] == "")
		{
			$ibforums->vars['topicpage_contents'] = '5,10,15,20,25,30,35,40';
		}
		
		$ibforums->vars['postpage_contents']  .= ",-1,";
		$ibforums->vars['topicpage_contents'] .= ",-1,";
		
		if (! preg_match( "/(^|,)".$ibforums->input['postpage'].",/", $ibforums->vars['postpage_contents'] ) )
		{
			$ibforums->input['postpage'] = '-1';
		}
		
		//+----------------------------------------
		
		if (! preg_match( "/(^|,)".$ibforums->input['topicpage'].",/", $ibforums->vars['topicpage_contents'] ) )
		{
			$ibforums->input['topicpage'] = '-1';
		}
		
		//+----------------------------------------
		
		$db_string = $DB->compile_db_update_string(  array (
															  'time_offset'  => $ibforums->input['u_timezone'],
															  'view_avs'     => $ibforums->input['VIEW_AVS'],
															  'view_sigs'    => $ibforums->input['VIEW_SIGS'],
															  'view_img'     => $ibforums->input['VIEW_IMG'],
															  'view_pop'     => $ibforums->input['DO_POPUP'],
															  'dst_in_use'   => $ibforums->input['DST'],
															  'view_prefs'   => $ibforums->input['postpage']."&".$ibforums->input['topicpage'],
												  )         );
		
		$DB->query("UPDATE ibf_members SET $db_string WHERE id='".$this->class->member['id']."'");
		
		if ($ibforums->input['HIDE_SESS'] == 1)
		{
			$std->my_setcookie('hide_sess', '1');
		}
		else
		{
			$std->my_setcookie('hide_sess', '0');
		}
		
		$print->redirect_screen( $ibforums->lang['set_updated'], "act=UserCP&CODE=04" );
	
	}
	
	
	
	function do_email_settings() {
		global $ibforums, $DB, $std, $print, $HTTP_POST_VARS;
		
		if ($HTTP_POST_VARS['act'] == "")
		{
			$std->Error( array( 'LEVEL' => 1, 'MSG' => 'complete_form' ) );
		}
		//+----------------------------------------
		
		//check and set the rest of the info
		
		foreach ( array('hide_email', 'admin_send', 'send_full_msg', 'pm_reminder', 'auto_track') as $v )
		{
			$ibforums->input[ $v ] = $std->is_number( $ibforums->input[ $v ] );
			
			if ( $ibforums->input[ $v ] < 1 )
			{
				$ibforums->input[ $v ] = 0;
			}
		}
		
		$db_string = $DB->compile_db_update_string(  array (
															  'hide_email'         => $ibforums->input['hide_email'],
															  'email_full'         => $ibforums->input['send_full_msg'],
															  'email_pm'           => $ibforums->input['pm_reminder'],
															  'allow_admin_mails'  => $ibforums->input['admin_send'],
															  'auto_track'         => $ibforums->input['auto_track'],
												  )         );
		
		$DB->query("UPDATE ibf_members SET $db_string WHERE id='".$this->class->member['id']."'");
		
		$print->redirect_screen( "Email Settings updated", "act=UserCP&CODE=02" );
	
	}
	
	
	
	function do_avatar() {
		global $ibforums, $DB, $std, $print, $HTTP_POST_VARS, $HTTP_POST_FILES, $FILE_UPLOAD;
		
		if ($HTTP_POST_VARS['act'] == "")
		{
			$std->Error( array( 'LEVEL' => 1, 'MSG' => 'complete_form' ) );
		}
		//+----------------------------------------
		
		$real_choice = 'noavatar';
		$real_dims   = '';
		
		if ($ibforums->input['choice'] == 'gallery')
		{
			$avatar_gallery = array();
 		
			$dh = opendir( $ibforums->vars['html_dir'].'avatars' );
			while ( $file = readdir( $dh ) )
			{
				if ( !preg_match( "/^..?$|^index/i", $file ) )
				{
					$avatar_gallery[] = $file;
				}
			}
			closedir( $dh );
			
			if (!in_array( $HTTP_POST_VARS['gallery_list'], $avatar_gallery ) )
			{
				$std->Error( array( 'LEVEL' => 1, 'MSG' => 'no_avatar_selected' ) );
			}
			
			$real_choice = $ibforums->input['gallery_list'];
			
		}
		else if ($ibforums->input['choice'] == 'url')
		{
		
			//-----------------------------------
			// Check to make sure we don't just have
			// http:// in the URL box..
			//------------------------------------
			
			if ( preg_match( "/^http:\/\/$/i", $ibforums->input['url_avatar'] ) )
			{
				$ibforums->input['url_avatar'] = "";
			}
		
			if (empty($ibforums->input['url_avatar']) )
			{
			
			//------------------------------------
			// Lets check for an uploaded avatar..
			//------------------------------------
			
				if ($HTTP_POST_FILES['FILE_UPLOAD']['name'] != "" and ($HTTP_POST_FILES['FILE_UPLOAD']['name'] != "none") )
				{
					$FILE_NAME = $HTTP_POST_FILES['FILE_UPLOAD']['name'];
					$FILE_SIZE = $HTTP_POST_FILES['FILE_UPLOAD']['size'];
					$FILE_TYPE = $HTTP_POST_FILES['FILE_UPLOAD']['type'];
					
					if ($HTTP_POST_FILES['FILE_UPLOAD']['name'] == "")
					{
						$std->Error( array( 'LEVEL' => 1, 'MSG' => 'no_av_name' ) );
					}
		
					// Naughty Opera adds the filename on the end of the
					// mime type - we don't want this.
					
					$FILE_TYPE = preg_replace( "/^(.+?);.*$/", "\\1", $FILE_TYPE );
					
					// Are we allowed to upload or has the admin stopped us?
					
					if ( ($ibforums->member['g_avatar_upload'] != 1) or ($ibforums->vars['avup_size_max'] < 1) )
					{
						$std->Error( array( 'LEVEL' => 1, 'MSG' => 'no_av_upload' ) );
					}
					
					// Check to make sure it's the correct content type.
					// Naughty Nominell won't be able to use PNG :P
					
					require "./conf_mime_types.php";
					
					if ($mime_types[ $FILE_TYPE ][3] != 1)
					{
						$std->Error( array( 'LEVEL' => 1, 'MSG' => 'no_av_type' ) );
					}
					   
					//-------------------------------------------------
					// Check the file size
					//-------------------------------------------------
					
					if ($FILE_SIZE > ($ibforums->vars['avup_size_max']*1024))
					{
						$std->Error( array( 'LEVEL' => 1, 'MSG' => 'upload_to_big' ) );
					}
					
					$ext = '.gif';
		
					switch($FILE_TYPE)
					{
						case 'image/gif':
							$ext = '.gif';
							break;
						case 'image/jpeg':
							$ext = '.jpg';
							break;
						case 'image/pjpeg':
							$ext = '.jpg';
							break;
						case 'image/x-png':
							$ext = '.png';
							break;
						default:
							$ext = '.gif';
							break;
					}
					
					$real_name = 'av-'.$this->class->member['id'].$ext;
					
					//-------------------------------------------------
					// Copy the upload to the uploads directory
					//-------------------------------------------------
					
					if (! @move_uploaded_file( $HTTP_POST_FILES['FILE_UPLOAD']['tmp_name'], $ibforums->vars['upload_dir']."/".$real_name) )
					{
						$std->Error( array( 'LEVEL' => 1, 'MSG' => 'upload_failed' ) );
					}
					
					// Set the "real" avatar..
					
					$real_choice = 'upload:'.$real_name;
					
					$w = $ibforums->input['Avatar_width'];
					$h = $ibforums->input['Avatar_height'];
					
					list($aw, $ah) = explode( "x", $ibforums->vars['avatar_dims'] );
					
					$w = $w > $aw ? $aw : $w;
					$h = $h > $ah ? $ah : $h;
					
					$real_dims    = $w.'x'.$h;
					
				}
				else if ( preg_match( "/^upload:/", $this->class->member['avatar'] ) )
				{
					// Keep the current avatar
					
					$real_choice = $this->class->member['avatar'];
					
					$w = $ibforums->input['Avatar_width'];
					$h = $ibforums->input['Avatar_height'];
					
					list($aw, $ah) = explode( "x", $ibforums->vars['avatar_dims'] );
					
					$w = $w > $aw ? $aw : $w;
					$h = $h > $ah ? $ah : $h;
					
					$real_dims    = $w.'x'.$h;
				}
				else
				{
					// URL field and upload field left blank.
			
					$std->Error( array( 'LEVEL' => 1, 'MSG' => 'no_avatar_selected' ) );
				
			    }
			}
			else
			{
				// Non empty URL field, upload box is empty.
			
				if (!preg_match( "/^http:\/\//i", $ibforums->input['url_avatar'] ) )
				{
					$std->Error( array( 'LEVEL' => 1, 'MSG' => 'avatar_invalid_url' ) );
				}
				
				$ext = explode ( "|", $ibforums->vars['avatar_ext'] );
				$checked = 0;
				$av_ext = preg_replace( "/^.*\.(\S+)$/", "\\1", $ibforums->input['url_avatar'] );
				
				foreach ($ext as $v )
				{
					if (strtolower($v) == strtolower($av_ext))
					{
						$checked = 1;
					}
				}
				
				if ($checked != 1)
				{
					$std->Error( array( 'LEVEL' => 1, 'MSG' => 'avatar_invalid_ext' ) );
				}
				
				$w = $ibforums->input['Avatar_width'];
				$h = $ibforums->input['Avatar_height'];
				
				list($aw, $ah) = explode( "x", $ibforums->vars['avatar_dims'] );
				
				$w = $w > $aw ? $aw : $w;
				$h = $h > $ah ? $ah : $h;
				
				$real_dims    = $w.'x'.$h;
				$real_choice = $ibforums->input['url_avatar'];
			}
			
		}
		else
		{
			$real_choice = 'noavatar';
		}
		
		// Update the DB
		
		$DB->query("UPDATE ibf_members SET avatar='$real_choice', avatar_size='$real_dims' WHERE id='".$this->class->member['id']."'");
	
		$print->redirect_screen( "Avatar choice updated", "act=UserCP&CODE=24" );
	
	}
	
	
	function do_profile() {
		global $ibforums, $DB, $std, $print, $HTTP_POST_VARS;
		
		//----------------------------------
		// Check for bad entry
		//----------------------------------
		
		if ($HTTP_POST_VARS['act'] == "")
		{
			$std->Error( array( 'LEVEL' => 1, 'MSG' => 'complete_form' ) );
		}
		
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
		
		//+--------------------
		
		if ( (strlen($HTTP_POST_VARS['Interests']) > $ibforums->vars['max_interest_length']) and ($ibforums->vars['max_interest_length']) )
		{
			$std->Error( array( 'LEVEL' => 1, 'MSG' => 'int_too_long' ) );
		}
		//+--------------------
		if ( (strlen($HTTP_POST_VARS['Location']) > $ibforums->vars['max_location_length']) and ($ibforums->vars['max_location_length']) )
		{
			$std->Error( array( 'LEVEL' => 1, 'MSG' => 'loc_too_long' ) );
		}
		//+--------------------
		if (strlen($HTTP_POST_VARS['WebSite']) > 150)
		{
			$std->Error( array( 'LEVEL' => 1, 'MSG' => 'web_too_long' ) );
		}
		//+--------------------
		if (strlen($HTTP_POST_VARS['Photo']) > 150) 
		{
			$std->Error( array( 'LEVEL' => 1, 'MSG' => 'photo_too_long' ) );
		}
		//+--------------------
		if ( ($HTTP_POST_VARS['ICQNumber']) && (!preg_match( "/^(?:\d+)$/", $HTTP_POST_VARS['ICQNumber'] ) ) )
		{
			$std->Error( array( 'LEVEL' => 1, 'MSG' => 'not_icq_number' ) );
		}
		//+--------------------
		if ( empty($ibforums->vars['allow_dynamic_img']) )
		{
			if ( preg_match( "/[?&;]/", $HTTP_POST_VARS['Photo'] ) )
			{
				$std->Error( array( 'LEVEL' => 1, 'MSG' => 'not_url_photo' ) );
			}
		}
		
		//----------------------------------
		// make sure that either we entered
		// all calendar fields, or we left them
		// all blank
		//----------------------------------
		
		$c_cnt = 0;
		
		foreach ( array('day','month','year') as $v )
		{
			if (!empty($ibforums->input[$v]))
			{
				$c_cnt++;
			}
		}
		
		if ( ($c_cnt > 0) and ($c_cnt != 3) )
		{
			$std->Error( array( 'LEVEL' => 1, 'MSG' => 'calendar_not_all' ) );
		}
		
		if ( ! preg_match( "#^http://#", $ibforums->input['WebSite'] ) )
		{
			$ibforums->input['WebSite'] = 'http://'.$ibforums->input['WebSite'];
		}
		
		//----------------------------------
		// Start off our array
		//----------------------------------
		
		$set = array(  'website'     => $ibforums->input['WebSite'],
					   'icq_number'  => $ibforums->input['ICQNumber'],
					   'aim_name'    => $ibforums->input['AOLName'],
					   'yahoo'       => $ibforums->input['YahooName'],
					   'msnname'     => $ibforums->input['MSNName'],
					   'location'    => $ibforums->input['Location'],
					   'interests'   => $ibforums->input['Interests'],
					   'bday_day'    => $ibforums->input['day'],
					   'bday_month'  => $ibforums->input['month'],
					   'bday_year'   => $ibforums->input['year'],
					);
		
		//----------------------------------
		// check to see if we can enter a member title
		// and if one is entered, update it.
		//----------------------------------
		
		if ( ($ibforums->input['member_title']) and ($ibforums->vars['post_titlechange']) and ( $this->class->member['posts'] > $ibforums->vars['post_titlechange']) )
		{
			$set['title'] = $ibforums->input['member_title'];
		}
		
		//----------------------------------
		// Update the DB
		//----------------------------------
		
		$set_string = $DB->compile_db_update_string($set);
		
		$DB->query("UPDATE ibf_members SET $set_string WHERE id='".$this->class->member['id']."'");
		
		//----------------------------------
		// Save the profile stuffy wuffy
		//----------------------------------
		
		if ( count($custom_fields) > 0 )
		{
		
			// Do we already have an entry in the content table?
			
			$DB->query("SELECT member_id FROM ibf_pfields_content WHERE member_id='".$ibforums->member['id']."'");
			$test = $DB->fetch_row();
			
			if ( $test['member_id'] )
			{
				// We have it, so simply update
				
				$db_string = $DB->compile_db_update_string($custom_fields);
				
				$DB->query("UPDATE ibf_pfields_content SET $db_string WHERE member_id='".$ibforums->member['id']."'");
			}
			else
			{
				$custom_fields['member_id'] = $ibforums->member['id'];
				
				$db_string = $DB->compile_db_insert_string($custom_fields);
				
				$DB->query("INSERT INTO ibf_pfields_content (".$db_string['FIELD_NAMES'].") VALUES(".$db_string['FIELD_VALUES'].")");
			}
		
		}
		// Return us!
		
		$print->redirect_screen( $ibforums->lang['profile_edited'], "act=UserCP&CODE=01" );
		
	}
	
	function do_signature() {
		global $ibforums, $DB, $std, $print, $HTTP_POST_VARS;
		
		if ($HTTP_POST_VARS['act'] == "")
		{
			$std->Error( array( 'LEVEL' => 1, 'MSG' => 'complete_form' ) );
		}
		//+----------------------------------------
		
		//----------------------------------
		// Check for bad entry
		//----------------------------------
		
		if ( (strlen($HTTP_POST_VARS['Post']) > $ibforums->vars['max_sig_length']) and ($ibforums->vars['max_sig_length']) )
		{
			$std->Error( array( 'LEVEL' => 1, 'MSG' => 'sig_too_long' ) );
		}
		
		//----------------------------------
		// Check for valid IB CODE
		//----------------------------------
		//
		// For efficiency, we convert the IBF code into HTML and store it in the DB
		// Otherwise we'll have to parse the siggies each time we view a post - that
		// gets boring after a while.
		//
		// We will adjust raw HTML on the fly, as some admins may allow it until it's abused
		// then switch it off. If we pre-compile HTML in siggies, we'd have to edit everyones
		// siggies to remove it. We don't want that.
		//
		// I'm going to stick my neck out again and say that most admins will allow IBF Code
		// in siggies, so it's not much of a bother.
		
		$ibforums->input['Post'] = $this->class->parser->convert(  array( 'TEXT'      => $ibforums->input['Post'],
																   'SMILIES'   => 0,
																   'CODE'      => $ibforums->vars['sig_allow_ibc'],
																   'HTML'      => $ibforums->vars['sig_allow_html'],
																   'SIGNATURE' => 1
														 )       );
									   
		if ($this->class->parser->error != "")
		{
			$std->Error( array( 'LEVEL' => 1, 'MSG' => $this->class->parser->error) );
		}
		
		//Write it to the DB.
		
		$ibforums->input['Post'] = preg_replace( "/'/", "\\'", $ibforums->input['Post'] );
		
		$DB->query("UPDATE ibf_members SET signature='".$ibforums->input['Post']."' WHERE id ='".$this->class->member['id']."'");
		
		// Buh BYE:
		
		$std->boink_it($this->class->base_url."&act=UserCP&CODE=22");
		
		exit;
	}
	
	
	
	
	
}



?>