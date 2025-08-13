<?php

/*
+--------------------------------------------------------------------------
|   > $$REG.MOD.PHP
|   > Version: 1.0.5
+--------------------------------------------------------------------------
*/

$obj = new Reg();

class Reg {

 var $reg_data = array();

  function Reg() {
     global $needsecure,$std,$DB,$INFO,$tpl;

     $needsecure->words = $std->load_words( $needsecure->words , 'lng_reg' , $needsecure->lang_id );
     
     if ( $INFO['NEW_REGS_ALLOW'] == '0' ) {
        $std->Error($needsecure->words['regs_not_allow']);
     }     
     
     $tpl->load_file("reg.tpl","main");
     $tpl->set_var("page_title",$needsecure->words['page_title']);
     $tpl->set_var("session_id",$needsecure->session_id);
     $tpl->set_var("base_url",$needsecure->base_url);
     $tpl->set_var("words_go_back",$needsecure->words['go_back']);
     $tpl->set_var("words_contact_webmaster",$needsecure->words['contact_webmaster']);
     $tpl->set_var("words_register_header",$needsecure->words['register_header']);
     $tpl->set_var("words_desired_username",$needsecure->words['desired_username']);
     $tpl->set_var("words_realname",$needsecure->words['realname']);
     $tpl->set_var("words_email",$needsecure->words['email']);
     $tpl->set_var("words_register_me",$needsecure->words['register_me']);
     $tpl->set_var("words_proceed_to_login_link_text",$needsecure->words['proceed_to_login_link_text']);

     if ( !$needsecure->input['code'] || empty($needsecure->input['code']) ) {
      // Registration
     
        if ( $needsecure->input['step'] == 'proceed' ) {
        
           if ( $needsecure->input['name'] == '' or $needsecure->input['realname'] == '' or $needsecure->input['email'] == '' ) {
	      $std->Error($needsecure->words['needed_fields_missed']);
	   }
	
	   $new_member['id'] = time();

	   // Check if such username already exists
	   $DB->query("SELECT id FROM ns_members WHERE name='{$needsecure->input['name']}'");
	   if ( $DB->get_num_rows() ) {
              $std->Error($needsecure->words['username_exists']);
	   } else {
              // Read denied for registeration UserNames
	      $fh = @fopen("{$INFO['BASE_PATH']}denied_logins.dat","r");
	      $allLogins = explode("|",@fgets($fh));
	      $errors=0;
	      for ($i=0;$i<count($allLogins);$i++) {
	         if ( $needsecure->input['name'] == $allLogins[$i] ) {
		    $errors++;
		 }
	      }
	      if ( $errors > 0 ) {
	         $std->Error($needsecure->words['login_usage_denied']);
	      } else {
	         $new_member['name'] = $needsecure->input['name'];
	      }
           }

           // Check if email format correct
	   $new_member['email'] = $std->clean_email($needsecure->input['email']);
           if ( !$new_member['email'] ) {
              $std->Error($needsecure->words['email_invalid']);
	   }
	
	   //$new_member['email'] = $needsecure->input['email'];

	   // Check if such email already exists
	   $DB->query("SELECT id FROM ns_members WHERE email='{$new_member['email']}'");
	   if ( $DB->get_num_rows() ) {
              $std->Error($needsecure->words['email_exists']);
	   }

	   $new_member['realname'] = $needsecure->input['realname'];
	
	   // Check if we need to create AUTH CODE
	   if ( $INFO['REG_AUTH_CODES'] == 1 ) {
              $new_member['authcode'] = $std->my_uniqid();
	   } else {
              $new_member['authcode'] = "";
	      // Create password
	      $new_member['plain_password'] = $std->make_password();
	      $new_member['password'] = md5($new_member['plain_password']);
	   }

	   // Check if we need ADMIN APPROVE
	   if ( $INFO['REG_ADMIN_APPROVE'] == 1 ) {
              $new_member['approved'] = '0';
	   } else {
              $new_member['approved'] = '1';
	   }

           

	   $new_member['regdate'] = date('Y-m-d');

           $new_member['member_ip'] = $needsecure->input['IP_ADDRESS'];
	   $new_member['lang'] = $INFO['DEFAULT_LANG'];
	
	   $SQL = "INSERT INTO ns_members 
	   (id,name,password,regdate,email,realname,lang,member_ip,count_visits,suspended,approved,authcode) 
	   VALUES 
	   ('{$new_member['id']}','{$new_member['name']}','{$new_member['password']}','{$new_member['regdate']}','{$new_member['email']}','{$new_member['realname']}','{$new_member['lang']}','{$new_member['member_ip']}','1','0','{$new_member['approved']}','{$new_member['authcode']}')";

	   // Parse it to database
	   $DB->query($SQL);	
	
	   if ( $INFO['REG_AUTH_CODES'] == 1 ) {
	      $new_member['auth_code_link'] = "{$needsecure->base_url}&act=reg&code=activate&email={$new_member['email']}&auth={$new_member['authcode']}";
	   
	      require ("{$needsecure->dirs['INC']}mail.class{$INFO['PHP_EXT']}");
              $mailer = new mailer($INFO['EMAIL_SENDER']);
              $mailer->setFrom($INFO['EMAIL_OUT']);
              $mailer->setTo($new_member['email']);
              $mailer->setMessage("auth_code",$new_member );
              $mailer->setSubj("Your account activation");
              $check = $mailer->send_mail();
    
              if ( !$check ) {
                 $std->Error("Error sending mail");
              }
	   
	      $tpl->set_var("words_reg_done",$needsecure->words['reg_done_with_authcode']);
	      $tpl->set_var("LoginLink",false);
	    
	   } elseif ( $INFO['REG_ADMIN_APPROVE'] == 1 ) {
	   
	      require ("{$needsecure->dirs['INC']}mail.class{$INFO['PHP_EXT']}");
              $mailer = new mailer($INFO['EMAIL_SENDER']);
              $mailer->setFrom($INFO['EMAIL_OUT']);
              $mailer->setTo($new_member['email']);
              $mailer->setMessage("reg_login_details_admin_approve",$new_member );
              $mailer->setSubj("Registration successfull");
              $check = $mailer->send_mail();
    
              if ( !$check ) {
                 $std->Error("Error sending mail");
              }
	   
	      $tpl->set_var("words_reg_done",$needsecure->words['reg_done_without_authcode_with_approve']);
	      $tpl->set_var("LoginLink",false);
	     
	   } else {
	
	      require ("{$needsecure->dirs['INC']}mail.class{$INFO['PHP_EXT']}");
              $mailer = new mailer($INFO['EMAIL_SENDER']);
              $mailer->setFrom($INFO['EMAIL_OUT']);
              $mailer->setTo($new_member['email']);
              $mailer->setMessage("reg_login_details",$new_member );
              $mailer->setSubj("Registration successfull");
              $check = $mailer->send_mail();
    
              if ( !$check ) {
                 $std->Error("Error sending mail");
              }
	   
	      $tpl->set_var("words_reg_done",$needsecure->words['reg_done_without_authcode_without_approve']);
	
	   }

	
	   $tpl->set_var("RegForm",false);
	   $tpl->set_var("AuthCode",false);
	
       } else {
           $tpl->set_var("RegDone",false);
	   $tpl->set_var("AuthCode",false);
       }
     
     } elseif ( $needsecure->input['code'] == 'activate' ) {
      // Activation
      
       if ( $needsecure->input['step'] == 'proceed' ) {
	  
	  // Activate member's account
	  $DB->query("SELECT id,name,realname,email FROM ns_members WHERE email='{$needsecure->input['email']}' and authcode='{$needsecure->input['auth']}'");
          if ( $DB->get_num_rows() < 1 ) {
	     $std->Error($needsecure->words['incorrect_autcode']);
	  } else {
	     $res = $DB->fetch_row();  
	  }
	  
	  // Create password to send
	  $res['plain_password'] = $std->make_password();
	  // Crypt for database
	  $res['password'] = md5($res['plain_password']);
	  
	  $DB->query("UPDATE ns_members SET password='{$res['password']}', authcode='' WHERE id='{$res['id']}' and authcode='{$needsecure->input['auth']}'");
	
	  if ( $INFO['REG_ADMIN_APPROVE'] == 1 ) {
	   
	        require ("{$needsecure->dirs['INC']}mail.class{$INFO['PHP_EXT']}");
                $mailer = new mailer($INFO['EMAIL_SENDER']);
                $mailer->setFrom($INFO['EMAIL_OUT']);
                $mailer->setTo($res['email']);
                $mailer->setMessage("reg_login_details_admin_approve",$res );
                $mailer->setSubj("Registration successfull");
                $check = $mailer->send_mail();
    
                if ( !$check ) {
                        $std->Error("Error sending mail");
                }
	   
	        $tpl->set_var("words_reg_done",$needsecure->words['reg_done_without_authcode_with_approve']);
	        $tpl->set_var("LoginLink",false);
	     
	   } else {
	
	        require ("{$needsecure->dirs['INC']}mail.class{$INFO['PHP_EXT']}");
                $mailer = new mailer($INFO['EMAIL_SENDER']);
                $mailer->setFrom($INFO['EMAIL_OUT']);
                $mailer->setTo($res['email']);
                $mailer->setMessage("reg_login_details",$res );
                $mailer->setSubj("Registration successfull");
                $check = $mailer->send_mail();
    
                if ( !$check ) {
                        $std->Error("Error sending mail");
                }
	   
	        $tpl->set_var("words_reg_done",$needsecure->words['reg_done_without_authcode_without_approve']);
	   
	
	   }
	   
	   $tpl->set_var("RegForm",false);
	   $tpl->set_var("AuthCode",false);
	  
	} else {
	  
	  // Show activation form  
	  $tpl->set_var("RegForm",false);
	  $tpl->set_var("RegDone",false);
          $tpl->set_var("member_email",$needsecure->input['email']);
	  $tpl->set_var("member_auth",$needsecure->input['auth']);
	  $tpl->set_var("words_authcode",$needsecure->words['authcode']);
	  $tpl->set_var("words_activate_account",$needsecure->words['activate_account']);	  
	  
	}
     
     }

     $tpl->pparse("main",true);

  }

} // End reg class

?>
