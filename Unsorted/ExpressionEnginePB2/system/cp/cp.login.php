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
 File: core.login.php
-----------------------------------------------------
 Purpose: Admin authentication class.
=====================================================
*/

if ( ! defined('EXT'))
{
    exit('Invalid file request');
}


class Login {


    // --------------------------------------
    //  Constructor
    // --------------------------------------

    function Login()
    {
        global $IN, $DSP;        

        switch($IN->GBL('M'))
        {
            case 'auth'        : $this->authenticate();
                break;
            case 'logout'      : $this->logout();
                break;
            case 'forgot'      : $this->forgotten_password_form();
                break;
            case 'send_forgot' : $this->retrieve_forgotten_password();
                break;
            default            : $this->login_form();
                break;
        }    
    }
    // END



    //---------------------------------------
    // Log-in form
    //---------------------------------------

    function login_form($message = '')
    {
        global $LANG, $DSP, $PREFS, $IN;
        
        $DSP->body_props = " onLoad=\"document.forms[0].username.focus();\"";

        $qstr = '';

        if ( ! isset($_SERVER['QUERY_STRING']))
        {
            if (isset($_SERVER['REQUEST_URI']))
            {
                $qstr = $_SERVER['REQUEST_URI'];
            }
        }
        else
        {
            $qstr = $_SERVER['QUERY_STRING'];
        }      
        
            $username = ( ! $IN->GBL('username', 'POST')) ? '' : $IN->GBL('username', 'POST'); 
            $password = ( ! $IN->GBL('password', 'POST')) ? '' : $IN->GBL('password', 'POST'); 
            
            $r = $DSP->div('leftPad').BR;
             
            $r .= ($message != '') ? $DSP->qdiv('highlight', $message) : ''; 
       
            $r .=
                $DSP->div('leftPad').
                $DSP->form('C=login'.AMP.'M=auth').
                $DSP->input_hidden('return_path', SELF);
                
        if ($IN->GBL('BK', 'GET') AND $qstr != '')
        {
            $qstr = preg_replace("#.*?C=publish(.*?)#", "C=publish\\1", $qstr);
        
            $r .= $DSP->input_hidden('bm_qstr', $qstr);
        }
                        
            $r .=
                $DSP->qdiv('', BR.$LANG->line('username', 'username')).
                $DSP->qdiv('', $DSP->input_text('username', $username, '20', '32', 'input', '150px')).
                $DSP->qdiv('', BR.$LANG->line('password', 'password')).
                $DSP->qdiv('', $DSP->input_pass('password', $password, '20', '32', 'input', '150px'));
            
        if ($PREFS->ini('admin_session_type') == 'c')
        {
            $r .=    
                $DSP->div('itemWrapper').BR.
                $DSP->span().$DSP->input_checkbox('remember_me', '1').$LANG->line('remember_me').$DSP->span_c().
                $DSP->div_c();
        }

            $r .=
                $DSP->div().BR.
                $DSP->input_submit($LANG->line('submit')).
                $DSP->div_c();
                
            $r .= $DSP->form_c();
                
            $r .= $DSP->qdiv('', BR.$DSP->anchor(BASE.AMP.'C=login'.AMP.'M=forgot', $LANG->line('forgot_password')));
              
            $r .= $DSP->div_c();    
            $r .= $DSP->div_c();    
                
        $DSP->body =& $r;
        $DSP->title = $LANG->line('login');                
    }  
    // END



    // --------------------------------------
    //  Authenticate user
    // --------------------------------------

    function authenticate()
    {
        global $IN, $DSP, $LANG, $SESS, $PREFS, $OUT, $LOC, $FNS, $REGX, $LOG, $DB;


        // ----------------------------------------
        // Is the user banned?
        // ----------------------------------------
        
        if ($SESS->userdata['group_id'] != 1)
        {
            if ($SESS->ban_check())
            {
                return $OUT->fatal_error($LANG->line('not_authorized'));
            }
        }

        // ----------------------------------------
        // No username/password?  Bounce them...
        // ----------------------------------------
    
        if ( ! $IN->GBL('username', 'POST') || ! $IN->GBL('password', 'POST'))
        {
            return $this->login_form();
        }
        
        
        // ----------------------------------------
        // Is IP and User Agent required for login?
        // ----------------------------------------
    
        if ($PREFS->ini('require_ip_for_login') == 'y')
        {
        	if ($SESS->userdata['ip_address'] == '' || $SESS->userdata['user_agent'] == '')
        	{
            	return $this->login_form($LANG->line('unauthorized_request'));
           	}
        }
        
        
        // ----------------------------------------
        //  Check password lockout status
        // ----------------------------------------
		
		if ($SESS->check_password_lockout() === TRUE)
		{
			$line = $LANG->line('password_lockout_in_effect');
		
			$line = str_replace("%x", $PREFS->ini('password_lockout_interval'), $line);
		
            return $this->login_form($line);
		}
		        
        // ----------------------------------------
        // Fetch member data
        // ----------------------------------------

        $sql = "SELECT exp_members.password, exp_members.unique_id, exp_members.member_id, exp_member_groups.can_access_cp
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
        
            return $this->login_form($LANG->line('no_username'));
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
	
                return $this->login_form($LANG->line('no_password'));
            }
        }
        
        // ----------------------------------------
        //  Is user allowed to access the CP?
        // ----------------------------------------
        
        if ($query->row['can_access_cp'] != 'y')
        {
            return $this->login_form($LANG->line('not_authorized'));        
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
                    return $this->login_form($LANG->line('multi_login_warning'));                            
                }               
            } 
        }  
        
        // ----------------------------------------
        //  Set cookies
        // ----------------------------------------
        
        // Set cookie expiration to 6 months if the "remember me" button is clicked

        $expire = ( ! $IN->GBL('remember_me', 'POST')) ? '0' : 60*60*24*182;

        $FNS->set_cookie($SESS->c_uniqueid , $query->row['unique_id'], $expire);       
        $FNS->set_cookie($SESS->c_password , $password,  $expire);   
        
        // ----------------------------------------
        // Create a new session
        // ----------------------------------------
        
        if ($PREFS->ini('admin_session_type') == 'cs' || $PREFS->ini('admin_session_type') == 's')
        {        
            $SESS->sdata['session_id'] = $FNS->random();  
            $SESS->sdata['admin_sess'] = 1;  
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
        //  Log the login
        // ----------------------------------------
        
        $LOG->log_action($LANG->line('member_logged_in'));
        
        // ----------------------------------------
        //  Delete old password lockouts
        // ----------------------------------------
        
		$SESS->delete_password_lockout();

        // ----------------------------------------
        //  Redirect the user to the CP home page
        // ----------------------------------------

        $return_path = $REGX->decode_qstr($IN->GBL('return_path', 'POST').'?S='.$SESS->sdata['session_id']);
        
        if ($IN->GBL('bm_qstr', 'POST'))
        {
            $return_path .= AMP.$IN->GBL('bm_qstr', 'POST');
        }

        $FNS->redirect($return_path);
        exit;    
    }
    // END
    
    
    
    //-------------------------------------
    // Log-out
    //-------------------------------------

    function logout()
    {
        global $SESS, $FNS, $LOG, $LOG, $LANG, $DB;

        $DB->query("DELETE FROM exp_sessions WHERE session_id = '".$SESS->sdata['session_id']."'");
        
        $FNS->set_cookie($SESS->c_uniqueid);       
        $FNS->set_cookie($SESS->c_password);   
        $FNS->set_cookie($SESS->c_session);   
        $FNS->set_cookie($SESS->c_anon);   

        $LOG->log_action($LANG->line('member_logged_out'));
        
        $FNS->redirect(SELF);
        exit;
    }
    // END
    
    

    //---------------------------------------
    // Forgotten password form
    //---------------------------------------

    function forgotten_password_form($message = '')
    {
        global $LANG, $DB, $DSP, $IN;        
        
        $email = ( ! $IN->GBL('email', 'POST')) ? '' : $IN->GBL('email', 'POST'); 
                
        if ($message != '')
        {
            $message = $DSP->div('alert').$message.$DSP->div_c().BR; 
        }
       
        $r =
            $DSP->div('leftPad').
            $DSP->form('C=login'.AMP.'M=send_forgot').
            $message.
            $DSP->qdiv('', BR.BR.$LANG->line('submit_email_address')).
            $DSP->qdiv('', $DSP->input_text('email', $email, '20', '80', 'input', '250px')).
            $DSP->qdiv('', BR.$DSP->input_submit($LANG->line('submit'))).
            $DSP->qdiv('', BR.BR.$DSP->anchor(BASE, $LANG->line('return_to_login'))).
            $DSP->form_c().
            $DSP->div_c();
                
        $DSP->set_return_data($LANG->line('forgotten_password'), $r);
    }  
    // END


    //---------------------------------------
    // Retrieve forgotten password
    //---------------------------------------

    function retrieve_forgotten_password()
    {
        global $LANG, $PREFS, $FNS, $DSP, $IN, $DB;
        
        if ( ! $address = $IN->GBL('email', 'POST'))
        {
            return $this->forgotten_password_form();
        }
        
		$address = strip_tags($address);
        
        // Fetch user data
        
        $sql = "SELECT member_id, username FROM exp_members WHERE email ='".$DB->escape_str($address)."'";
                
        $query = $DB->query($sql);
        
        if ($query->num_rows == 0)
        {
            return $this->forgotten_password_form($LANG->line('no_email_found'));
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
        
        $message  = $username.",".
                    $DSP->nl(2).
                    $LANG->line('reset_link').
                    $DSP->nl(2).
                    $PREFS->ini('cp_url')."?C=reset&id=".$rand.
                    $DSP->nl(2).
                    $LANG->line('password_will_be_reset').
                    $DSP->nl(2).
                    $LANG->line('ignore_password_message');
         
         
        // Instantiate the email class
             
        require PATH_CORE.'core.email'.EXT;
        
        $email = new EEmail;
        $email->wordwrap = true;
        $email->from($address);	
        $email->to($address); 
        $email->subject($LANG->line('your_new_login_info'));	
        $email->message($message);	
        
        if ( ! $email->Send())
        {
            $res = $LANG->line('error_sending_email');
        } 
        else 
        {   
            $res = $LANG->line('forgotten_email_sent');
        }
        

        $DSP->set_return_data(
                                $LANG->line('forgotten_password'), 
                                
                                $DSP->div('leftPad').BR.
                                $res.
                                $DSP->br(2).
                                $DSP->anchor(BASE, $LANG->line('return_to_login')).
                                $DSP->div_c()
                                
                                );
    }  
    // END


    //---------------------------------------
    // Reset password
    //---------------------------------------

    function reset_password()
    {
        global $LANG, $PREFS, $FNS, $DSP, $IN, $DB;
        
        if ( ! $id = $IN->GBL('id', 'GET'))
        {
            return $this->login_form();
        }
        
        $time = time() - (60*60*24);
                   
        // Get the member ID from the reset_password field   
                
        $query = $DB->query("SELECT member_id FROM exp_reset_password WHERE resetcode ='$id' and date > $time");
        
        if ($query->num_rows == 0)
        {
            return $this->login_form();
        }
        
        $member_id = $query->row['member_id'];
                
        // Fetch the user data
        
        $sql = "SELECT username, email FROM exp_members WHERE member_id ='$member_id'";
        
        $query = $DB->query($sql);
        
        if ($query->num_rows == 0)
        {
            return $this->login_form();
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
        
        $message  = $username.",".
                    $DSP->nl(2).
                    $LANG->line('new_login_info').
                    $DSP->nl(2).
                    $LANG->line('username').': '.$username.
                    $DSP->nl(1).
                    $LANG->line('password').': '.$rand;
         
         
        // Instantiate the email class
             
        require PATH_CORE.'core.email'.EXT;
        
        $email = new EEmail;
        $email->wordwrap = true;
        $email->from($address);	
        $email->to($address); 
        $email->subject($LANG->line('your_new_login_info'));	
        $email->message($message);	
        
        if ( ! $email->Send())
        {
            $res = $LANG->line('error_sending_email');
        } 
        else 
        {   
            $res = $LANG->line('password_has_been_reset');
        }
        

        $DSP->set_return_data(
                                $LANG->line('forgotten_password'), 
                                
                                $DSP->div('leftPad').BR.
                                $res.
                                $DSP->br(2).
                                $DSP->anchor(BASE, $LANG->line('return_to_login')).
                                $DSP->div_c()
                              );
    }  
    // END
      
}
// END CLASS
?>