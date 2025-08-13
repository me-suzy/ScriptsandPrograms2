<?php

/*
+--------------------------------------------------------------------------
|   > $$A_LOGIN.MOD.PHP
|   > Version: 1.0.5
+--------------------------------------------------------------------------
*/

$obj = new Login();

class Login {

 var $login_data = array();
 var $member = array();

  function Login() {
     global $needsecure,$std,$DB,$sess;

     switch($needsecure->input['code']) {
       case '01':
        $this->loginUser();
       break;
       case '02':
        $this->logoutUser();
       break;
       default:
        $this->loginForm();
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


        $DB->query("SELECT
              id,name,password,email,level,lang
	      FROM ns_admins
	      WHERE name='{$this->login_data['login']}' and password='{$this->login_data['password']}'");

	if ( $DB->get_num_rows() ) {
           $this->admin = $DB->fetch_row();
        } else {
           $std->Error("Sorry, incorrect login or password. Try again.");
	}

        if ( $needsecure->input['s'] != '' ) {
           $session_id = $needsecure->input['s'];
           $DB->query("DELETE FROM ns_admin_sessions WHERE id <> '{$session_id}' and admin_ip='{$needsecure->input['IP_ADDRESS']}'");
           $DB->query("UPDATE ns_admin_sessions SET admin_id='{$this->admin['id']}',admin_name='{$this->admin['name']}',admin_level='{$this->admin['level']}',admin_ip='{$needsecure->input['IP_ADDRESS']}' WHERE id='{$session_id}'");
	} else {
           $session_id = md5( uniqid(microtime()) );
           $DB->query("DELETE FROM ns_admin_sessions WHERE admin_ip='{$needsecure->input['IP_ADDRESS']}'");
           $DB->query("INSERT INTO ns_admin_sessions (id,admin_id,admin_name,admin_level,admin_ip) VALUES ('{$session_id}','{$this->admin['id']}','{$this->admin['name']}','{$this->admin['level']}','{$needsecure->input['IP_ADDRESS']}')");
	}

        $this->admin['ip_address'] = $needsecure->input['IP_ADDRESS'];

        $needsecure->admin = $this->admin;
	$needsecure->session_id  = $session_id;


	$std->my_setcookie( "admin_id"    , $this->admin['id']        , -1  );
        $std->my_setcookie( "apass_hash"  , $this->admin['password']  , -1  );
	$std->my_setcookie( "alang_id"    , $this->admin['lang']      , -1  );

	$url = $needsecure->base_url . "&act=idx";

	@flush();
	$std->redirectPage($url,$needsecure->words['login_success_text']);

  }

  /*
    @ Log Out it!
  */
  function logoutUser() {
    global $needsecure,$std,$sess,$DB,$INFO;

     $std->my_setcookie( "asession_id" ,  -1  );
     $std->my_setcookie( "admin_id"    ,  -1  );
     $std->my_setcookie( "apass_hash"  ,  -1  );
     $std->my_setcookie( "alang_id"    ,  -1  );

     $DB->query("DELETE FROM ns_admin_sessions WHERE id='{$needsecure->session_id}' or admin_id='{$this->admin['id']}' or admin_ip='{$needsecure->input['IP_ADDRESS']}'");

     $this->admin['id']       = 0;
     $this->admin['name']     = "";
     $this->admin['password'] = "";


     $url = "admin{$INFO['PHP_EXT']}";
     @flush();
     $std->redirectPage($url,$needsecure->words['logout_success_text']);

  }


  /*
    @ Output login form
  */
  function loginForm() {
    global $needsecure,$std,$tpl,$INFO;

     $tpl->load_file("loginform.tpl","main");

     $tpl->set_var("page_title",$INFO['SITE_NAME']);
     $tpl->set_var("session_id",$needsecure->session_id);
     $tpl->set_var("base_url",$needsecure->base_url);

     $tpl->set_var("words_admin_login_header",$needsecure->words['words_admin_login_header']);
     $tpl->set_var("words_submit_login",$needsecure->words['submit_login']);
     $tpl->set_var("words_username",$needsecure->words['username']);
     $tpl->set_var("words_password",$needsecure->words['password']);
     $tpl->set_var("words_remember_login",$needsecure->words['remember_login']);
     $tpl->set_var("words_lost_passwd_link_text",$needsecure->words['lost_passwd_link_text']);
     $tpl->set_var("words_register_link_text",$needsecure->words['register_link_text']);
     $tpl->set_var("words_go_back",$needsecure->words['go_back']);
     $tpl->set_var("words_contact_webmaster",$needsecure->words['contact_webmaster']);

     $tpl->pparse("main",true);

     exit;

  }


}


?>