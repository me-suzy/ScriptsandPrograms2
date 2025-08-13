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
|   > Log in / log out module
|   > Module written by Matt Mecham
|   > Date started: 14th February 2002
|
|	> Module Version Number: 1.0.0
+--------------------------------------------------------------------------
*/


$idx = new Login;

class Login {

    var $output     = "";
    var $page_title = "";
    var $nav        = array();
    var $login_html = "";
    
    function Login() {
    	global $ibforums, $DB, $std, $print;
    	
		$ibforums->lang = $std->load_words($ibforums->lang, 'lang_login', $ibforums->lang_id);
    	
    	$this->login_html = $std->load_template('skin_login');

    	
    	// Are we enforcing log ins?
    	
    	if ($ibforums->vars['force_login'] == 1)
    	{
    		$msg = 'admin_force_log_in';
    	}
    	else
    	{
    		$msg = "";
    	}
    	
    	// What to do?
    	
    	switch($ibforums->input['CODE']) {
    		case '01':
    			$this->do_log_in();
    			break;
    		case '02':
    			$this->log_in_form();
    			break;
    		case '03':
    			$this->do_log_out();
    			break;
    			
    		case '04':
    			$this->markforum();
    			break;
    			
    		case '05':
    			$this->markboard();
    			break;
    			
    		case '06':
    			$this->delete_cookies();
    			break;
    			
    		case 'autologin':
    			$this->auto_login();
    			break;
    			
    		default:
    			$this->log_in_form($msg);
    			break;
    	}
    	
    	// If we have any HTML to print, do so...
    	
    	$print->add_output("$this->output");
        $print->do_output( array( 'TITLE' => $this->page_title, 'JS' => 0, NAV => $this->nav ) );
    		
 	}
 	
 	function auto_login()
 	{
 		global $ibforums, $DB, $std, $print;
 		
 		// Universal routine.
 		// If we have cookies / session created, simply return to the index screen
 		// If not, return to the log in form
 		
 		$true_words  = $ibforums->lang['logged_in'];
 		$false_words = $ibforums->lang['not_logged_in'];
 		$method = 'no_show';
 		
 		if ($ibforums->input['fromreg'] == 1)
 		{
 			$true_words  = $ibforums->lang['reg_log_in'];
 			$false_words = $ibforums->lang['reg_not_log_in'];
 			$method = 'show';
 		}
 		
 		if ($ibforums->member['id'])
 		{
 			if ($method == 'show')
 			{
 				$print->redirect_screen( $true_words, "" );
 			}
 			else
 			{
 				$std->boink_it($ibforums->base_url);
 			}
 		}
 		else
 		{
 			if ($method == 'show')
 			{
 				$print->redirect_screen( $false_words, 'act=Login&CODE=00' );
 			}
 			else
 			{
 				$std->boink_it($ibforums->base_url.'&act=Login&CODE=00');
 			}
 		}
 		
 		
 	}
 	
 	
 	
 	function delete_cookies()
 	{
 		global $ibforums, $DB, $std, $HTTP_COOKIE_VARS;
 		
 		if (is_array($HTTP_COOKIE_VARS))
 		{
 			foreach( $HTTP_COOKIE_VARS as $cookie => $value)
 			{
 				if (preg_match( "/^(".$ibforums->vars['cookie_id']."fread.*$)/", $cookie, $match))
 				{
 					$std->my_setcookie( str_replace( $ibforums->vars['cookie_id'], "", $match[0] ) , '-1', -1 );
 				}
 				
 				if (preg_match( "/^(".$ibforums->vars['cookie_id']."ibforum.*$)/i", $cookie, $match))
 				{
 					$std->my_setcookie( str_replace( $ibforums->vars['cookie_id'], "", $match[0] ) , '-', -1 );
 				}
 			}
 		}
 		
 		$std->my_setcookie('pass_hash' , '-1');
 		$std->my_setcookie('member_id' , '-1');
 		$std->my_setcookie('session_id', '-1');
 		$std->my_setcookie('topicsread', '-1');
 		$std->my_setcookie('anonlogin' , '-1');
 		
		$std->boink_it($ibforums->base_url);
		exit();
	}  
	
 	
 	function markboard()
 	{
 		global $ibforums, $DB, $std;
 		
 		if(! $ibforums->member['id'])
		{
			$std->Error( array( LEVEL => 1, MSG => 'no_guests') );
		}
		
		$DB->query("UPDATE ibf_members SET last_visit='".time()."', last_activity='".time()."' WHERE id='".$ibforums->member['id']."'");
		
		$std->boink_it($ibforums->base_url);
		exit();
	}  
    
    
    function markforum() {
        global $ibforums, $DB, $std;
        
        $ibforums->input['f'] = preg_replace( "/^(\d+)$/", "\\1", $ibforums->input['f'] );
        
        if ($ibforums->input['f'] == "")
        {
        	$std->Error( array( LEVEL => 1, MSG => 'missing_files' ) );
        }
        
        $std->my_setcookie( "fread_".$ibforums->input['f'], time() );
        
        $std->boink_it($ibforums->base_url);
        exit();
        
    }
    
    
    
    
    function log_in_form($message="") {
        global $ibforums, $DB, $std, $print, $HTTP_REFERER;
        
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
        
        //+--------------------------------------------
        
        if ($message != "")
        {
        	$message = $ibforums->lang[ $message ];
        	$message = preg_replace( "/<#NAME#>/", "<b>{$ibforums->input[UserName]}</b>", $message );
        
			$this->output .= $this->login_html->errors($message);
		}
		
		$this->output .= $this->login_html->ShowForm( $ibforums->lang['please_log_in'], $HTTP_REFERER );
		
		$this->nav        = array( $ibforums->lang['log_in'] );
	 	$this->page_title = $ibforums->lang['log_in'];
		
		$print->add_output("$this->output");
        $print->do_output( array( 'TITLE' => $this->page_title, 'JS' => 0, NAV => $this->nav ) );
        
        exit();
        
    }
    
    //+--------------------------------------------
    
    function do_log_in() {
    	global $DB, $ibforums, $std, $print, $sess, $HTTP_USER_AGENT, $HTTP_POST_VARS;
    	
    	$url = "";
    	
    	//-------------------------------------------------
    	// Make sure the username and password were entered
    	//-------------------------------------------------
    	
    	if ($HTTP_POST_VARS['UserName'] == "")
    	{
    		$std->Error( array( 'LEVEL' => 1, 'MSG' => 'no_username' ) );
    	}
    
     	if ($HTTP_POST_VARS['PassWord'] == "")
     	{
    		$std->Error( array( 'LEVEL' => 1, 'MSG' => 'pass_blank' ) );
    	}   

		
		//-------------------------------------------------
		// Check for input length
		//-------------------------------------------------
		
		if (strlen($ibforums->input['UserName']) > 32)
		{
			$std->Error( array( LEVEL => 1, MSG => 'username_long' ) );
		}
		
		if (strlen($ibforums->input['PassWord']) > 32)
		{
			$std->Error( array( LEVEL => 1, MSG => 'pass_too_long' ) );
		}
		
		$username    = strtolower($ibforums->input['UserName']);
		$password    = md5( $ibforums->input['PassWord'] );
		
		//-------------------------------------------------
		// Attempt to get the user details
		//-------------------------------------------------
		
		$DB->query("SELECT id, name, mgroup, password, new_pass FROM ibf_members WHERE LOWER(name)='$username'");
		
		if ($DB->get_num_rows())
		{
			$member = $DB->fetch_row();
			
			if ( empty($member['id']) or ($member['id'] == "") )
			{
				$this->log_in_form( 'wrong_name' );
			}
			
			if ($member['password'] != $password)
			{
				$this->log_in_form( 'wrong_pass' );
			}
			
			//------------------------------
			
			if ($ibforums->input['CookieDate'])
			{
				$std->my_setcookie("member_id"   , $member['id'], 1);
				$std->my_setcookie("pass_hash"   , $password, 1);
			}
			
			//------------------------------
			
			if ($ibforums->input['s'])
			{
				$session_id = $ibforums->input['s'];
				
				// Delete any old sessions with this users IP addy that doesn't match our
				// session ID.
				
				$DB->query("DELETE FROM ibf_sessions WHERE ip_address='".$ibforums->input['IP_ADDRESS']."' AND id <> '$session_id'");
				
				$db_string = $DB->compile_db_update_string( array (
																	 'member_name'  => $member['name'],
																	 'member_id'    => $member['id'],
																	 'running_time' => time(),
																	 'member_group' => $member['mgroup'],
																	 'login_type'   => $ibforums->input['Privacy'] ? 1 : 0
														  )       );
														  
				$db_query = "UPDATE ibf_sessions SET $db_string WHERE id='".$ibforums->input['s']."'";
			}
			else
			{
				$session_id = md5( uniqid(microtime()) );
				
				// Delete any old sessions with this users IP addy.
				
				$DB->query("DELETE FROM ibf_sessions WHERE ip_address='".$ibforums->input['IP_ADDRESS']."'");
				
				$db_string = $DB->compile_db_insert_string( array (
																	 'id'           => $session_id,
																	 'member_name'  => $member['name'],
																	 'member_id'    => $member['id'],
																	 'running_time' => time(),
																	 'member_group' => $member['mgroup'],
																	 'ip_address'   => substr($ibforums->input['IP_ADDRESS'], 0, 50),
																	 'browser'      => substr($HTTP_USER_AGENT, 0, 50),
																	 'login_type'   => $ibforums->input['Privacy'] ? 1 : 0
														  )       );
														 
				$db_query = "INSERT INTO ibf_sessions (" .$db_string['FIELD_NAMES']. ") VALUES (". $db_string['FIELD_VALUES'] .")";
			}
			
			$DB->query( $db_query );
			
			//-----------------------------------
			// If a bogus reset passy action occured,
			// and we managed to log in, we'll assume
			// that the user did nothing, so we remove
			// this new pass setting.
			//-----------------------------------
			
			if ($member['new_pass'] != "")
			{
				$DB->query("UPDATE ibf_members SET new_pass='' WHERE id='".$member['id']."'");
			}
			
			$ibforums->member           = $member;
			$ibforums->session_id       = $session_id;
			
			if ($ibforums->input['referer'] && ($ibforums->input['act'] != 'Reg'))
			{
				$url = $ibforums->input['referer'];
				$url = str_replace( "{$ibforums->vars['board_url']}/index.{$ibforums->vars['php_ext']}", "", $url );
				$url = preg_replace( "!^\?!"       , ""   , $url );
				$url = preg_replace( "!s=(\w){32}!", ""   , $url );
				$url = preg_replace( "!act=(login|reg|lostpass)!i", "", $url );
			}
			
			//-----------------------------------
			// set our privacy cookie
			//-----------------------------------
			
			if ($ibforums->input['Privacy'] == 1)
			{
				$std->my_setcookie( "anonlogin", 1 );
			}
			
			//-----------------------------------
			// Redirect them to either the board
			// index, or where they came from
			//-----------------------------------
			
			$print->redirect_screen( "{$ibforums->lang[thanks_for_login]} {$ibforums->member['name']}", $url );
			
			
		}
		else
		{
			$this->log_in_form( 'wrong_name' );
		}
		
	}
	
	
	
	
	

	function do_log_out() {
		global $std, $ibforums, $DB, $print, $sess, $HTTP_COOKIE_VARS;
		
		/*if(! $ibforums->member['id'])
		{
			$std->Error( array( LEVEL => 1, MSG => 'no_guests') );
		}*/
		
		// Update the DB
		
		$DB->query("UPDATE ibf_sessions SET ".
				     "member_name='',".
				     "member_id='0',".
				     "login_type='0' ".
				     "WHERE id='". $sess->session_id ."'");
				     
		$DB->query("UPDATE ibf_members SET last_visit='".time()."', last_activity='".time()."' WHERE id='".$ibforums->member['id']."'");
				     
		// Set some cookies
		
		$std->my_setcookie( "member_id" , "0"  );
		$std->my_setcookie( "pass_hash" , "0"  );
		$std->my_setcookie( "anonlogin" , "-1" );
		
		if (is_array($HTTP_COOKIE_VARS))
 		{
 			foreach( $HTTP_COOKIE_VARS as $cookie => $value )
 			{
 				if (preg_match( "/^(".$ibforums->vars['cookie_id']."fread.*$)/", $cookie, $match))
 				{
 					$std->my_setcookie( str_replace( $ibforums->vars['cookie_id'], "", $match[0] ) , '-1', -1 );
 				}
 				
 				if (preg_match( "/^(".$ibforums->vars['cookie_id']."ibforum.*$)/i", $cookie, $match))
 				{
 					$std->my_setcookie( str_replace( $ibforums->vars['cookie_id'], "", $match[0] ) , '-', -1 );
 				}
 			}
 		}
		
		// Redirect...
		
		$print->redirect_screen( $ibforums->lang['thanks_for_logout'], "" );
		
	}




        
}

?>
