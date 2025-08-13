<?php

/*
+--------------------------------------------------------------------------
|   > $$LOGIN.MOD.PHP
|   > Version: 1.0.5
+--------------------------------------------------------------------------
*/

$obj = new Login();

class Login {

 var $login_data = array();
 var $member = array();

  function Login() {
     global $needsecure,$std,$DB,$sess;

     $needsecure->words = $std->load_words( $needsecure->words , 'lng_login' , $needsecure->lang_id );

     switch($needsecure->input['code']) {
       case '01':
        $this->loginUser();
       break;
       case '02':
        $this->logoutUser();
       break;
       case '03':
        if ( $needsecure->input['step'] == '01' ) {
	   $this->lostPasswdSendPasswd();
	   $this->loginForm("lpwd",1);
        } else {
	   $this->loginForm("lpwd",0);
	}
       break;
       default:
        $this->loginForm("login",0);
       break;
     }

  }

  /*
    @ Try user to log in
  */
  function loginUser() {
    global $needsecure,$std,$DB,$tpl,$sess;

        $this->login_data['sess_id']  = $needsecure->input['s'];
	$this->login_data['login']    = $needsecure->input['login'];
	$this->login_data['password'] = md5($needsecure->input['password']);
	$this->login_data['remember'] = $needsecure->input['remember'];


        $DB->query("SELECT
              id,name,password,regdate,expire,email,realname,
	      lang,member_ip,member_browser,member_referrer,
	      last_login,count_visits,access_dirs,
	      suspended,approved,authcode
	      FROM ns_members
	      WHERE name='{$this->login_data['login']}' and password='{$this->login_data['password']}'");

	if ( $DB->get_num_rows() ) {
           $this->member = $DB->fetch_row();
        } else {
           $std->Error("Sorry, incorrect login or password. Try again.");
	}

        // Doest it banned by ip,id or email?
	$std->checkBann($this->member['id'],$this->member['email'],'');

	// Does it ativated it's account?
	if ( ( $this->member['authcode'] != '' ) || (!empty($this->member['authcode'])) ) {
           $std->Error("Sorry, Your account doesn't activated yet. Please activate Your account first, using ACTIVATION CODE in Your confirmation email.");
	}

	// Does it's account approved by administrator?
	if ( $this->member['approved'] == 0 ) {
           $std->Error("Sorry, Your account doesn't approved yet. Please contact system administrator.");
	}

	// Does it's account suspended?
        if ( $this->member['suspended'] == 1 ) {
           $std->Error("Sorry, Your account suspended. Please contact system administrator.");
	}

    $last_activity = time();
	$user_agent = substr($_SERVER["HTTP_USER_AGENT"],0,50);


	if ( $needsecure->input['s'] != '' and $needsecure->input['s'] != 0 ) {
	   $session_id = $needsecure->input['s'];
       $DB->query("DELETE FROM ns_sessions WHERE id <> '{$session_id}' and member_ip='{$needsecure->input['IP_ADDRESS']}'");
	   $SQL = "UPDATE ns_sessions SET member_id='{$this->member['id']}',member_name='{$this->member['name']}',member_ip='{$needsecure->input['IP_ADDRESS']}',member_browser='{$user_agent}' ,last_activity='{$last_activity}' WHERE id='{$session_id}'";
	   $DB->query($SQL);
	} else {
	   $session_id = md5( uniqid(microtime()) );
       $DB->query("DELETE FROM ns_sessions WHERE member_ip='{$needsecure->input['IP_ADDRESS']}'");
       $SQL = "INSERT INTO ns_sessions (id,member_id,member_name,member_ip,member_browser,last_activity) VALUES ('{$session_id}','{$this->member['id']}','{$this->member['name']}','{$needsecure->input['IP_ADDRESS']}','{$user_agent}','{$last_activity}')";
	   $DB->query($SQL);
	}

        $needsecure->member = $this->member;
	$needsecure->session_id  = $session_id;


        // Set cookies
        $std->my_setcookie( "member_id"         , $this->member['id']       , -1  );
        $std->my_setcookie( "member_pass_hash"  , $this->member['password'] , -1  );
	    $std->my_setcookie( "member_lang_id"    , $this->member['lang']     , -1  );

	// Update member in database
	$count_visits = $this->member['count_visits'] + 1;
        $last_login = date('Y-m-d H:i:s');

	$DB->query("UPDATE ns_members SET
                    last_login='{$last_login}',
		    count_visits='{$count_visits}'
		    WHERE id='{$this->member['id']}'");

        $url = $needsecure->base_url . "&act=idx";
    	@flush();
		$std->redirectPage($url,$needsecure->words['success_text']);

  }

  /*
    @ Log Out it!
  */
  function logoutUser() {
    global $needsecure,$std,$sess,$DB,$INFO;

     $std->my_setcookie( "member_session_id" ,  -1  );
     $std->my_setcookie( "member_id"         ,  -1  );
     $std->my_setcookie( "member_pass_hash"  ,  -1  );
     $std->my_setcookie( "member_lang_id"    ,  -1  );

     $DB->query("DELETE FROM ns_sessions WHERE id='{$needsecure->session_id}' or member_id='{$this->admin['id']}' or member_ip='{$needsecure->input['IP_ADDRESS']}'");

     $this->member['id']       = 0;
     $this->member['name']     = "";
     $this->member['password'] = "";


     $url = $needsecure->base_url;
     @flush();
     $std->redirectPage($url,$needsecure->words['logout_text']);

  }

  /*
    @ Retrieving member password
  */
  function lostPasswdSendPasswd() {
    global $needsecure,$std,$sess,$DB,$INFO;

    $DB->query("SELECT id,name,email,realname,access_dirs FROM ns_members WHERE email='{$needsecure->input['email']}'");
    if ( $DB->get_num_rows() ) {
           $member = $DB->fetch_row();
        } else {
           $std->Error("Sorry, email {$needsecure->input['email']} can't be found in our database. Try again more attentively.");
	}

    $member['email'] = $needsecure->input['email'];
    $member['plain_password'] = $std->make_password();
    $member['password'] = md5( $member['plain_password'] );

    $DB->query("UPDATE ns_members SET password='{$member['password']}', plain_password='{$member['plain_password']}' where email='{$member['email']}'");

       // Chage user password in all .htpasswd in his accessible dirs
	require ("{$needsecure->dirs['INC']}htaccess.class{$INFO['PHP_EXT']}");
	$ht = new htaccess;
 	$dirs = explode("|",$member['access_dirs']);
        for ($i=0;$i<count($dirs);$i++) {
	  $ht->setHtpasswd($INFO['HTA_TOP_DIR'] . "/" . $dirs[$i] . "/.htpasswd");
	  $ht->setUserPass($member['name'],$member['plain_password']);
        }
        unset($dirs);

    require ("{$needsecure->dirs['INC']}mail.class{$INFO['PHP_EXT']}");
    $mailer = new mailer($INFO['EMAIL_SENDER']);
    $mailer->setFrom($INFO['EMAIL_OUT']);
    $mailer->setTo($member['email']);
    $mailer->setMessage("lost_password",$member );
    $mailer->setSubj("{$INFO['SITE_NAME']}: Password recovery system");
    $check = $mailer->send_mail();

    if ( $check ) {
       $this->loginForm("lpwd",1);
    } else {
       $std->Error("Error sending mail");
    }

  }

  /*
    @ Output login form to visitor
  */
  function loginForm($part,$pwdstep) {
    global $needsecure,$std,$tpl;

     $tpl->load_file("loginform.tpl","main");
     $tpl->set_var("page_title",$INFO['SITE_NAME']);
     $tpl->set_var("session_id",$needsecure->session_id);
     $tpl->set_var("base_url",$needsecure->base_url);

     if ( $part == 'login' ) {
        $tpl->set_var("page_sub_title",$needsecure->words['login_page_subtitle']);
	$tpl->set_var("words_submit_login",$needsecure->words['submit_login']);
        $tpl->set_var("words_username",$needsecure->words['username']);
        $tpl->set_var("words_password",$needsecure->words['password']);
        $tpl->set_var("words_remember_login",$needsecure->words['remember_login']);
	$tpl->set_var("words_lost_passwd_link_text",$needsecure->words['lost_passwd_link_text']);
	$tpl->set_var("words_register_link_text",$needsecure->words['register_link_text']);
	$tpl->set_var("LostPasswdForm",false);
	$tpl->set_var("LostPasswdSent",false);
     } elseif ( $part == 'lpwd' ) {
        $tpl->set_var("page_sub_title",$needsecure->words['lostpasswd_page_subtitle']);

	$tpl->set_var("words_submit_email",$needsecure->words['submit_email']);
        $tpl->set_var("words_lpwd_email",$needsecure->words['lpwd_email']);
	$tpl->set_var("words_pwd_email_sent",$needsecure->words['pwd_email_sent']);
	$tpl->set_var("words_procced_to_login_link_text",$needsecure->words['procced_to_login_link_text']);
        $tpl->set_var("LoginForm",false);
        if ( $pwdstep == 0 ) {
           $tpl->set_var("LostPasswdSent",false);
        } elseif ( $pwdstep == 1 ) {
          $tpl->set_var("LostPasswdForm",false);
        }
     }

     $tpl->set_var("words_go_back",$needsecure->words['go_back']);
     $tpl->set_var("words_contact_webmaster",$needsecure->words['contact_webmaster']);

     $tpl->pparse("main",true);

     exit;

  }


}


?>