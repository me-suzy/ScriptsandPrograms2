<?php

/*
+--------------------------------------------------------------------------
|   > $$IDX.MOD.PHP
|   > Version: 1.0.5
+--------------------------------------------------------------------------
*/

$obj = new Idx();

class Idx {

   function Idx() {
     global $needsecure,$std,$sess,$DB,$tpl,$INFO;

     $needsecure->words = $std->load_words( $needsecure->words , 'lng_idx' , $needsecure->lang_id );

     $tpl->load_file("idx.tpl","main");
     $tpl->set_var("page_title",$INFO['SITE_NAME']);
     $tpl->set_var("page_sub_title",$needsecure->words['page_sub_title']);
     $tpl->set_var("base_url",$needsecure->base_url);
     $tpl->set_var("img_url",$INFO['IMG_URL']);
     $tpl->set_var("session_id",$needsecure->session_id);
     $tpl->set_var("realname",$needsecure->member['realname']);
     $tpl->set_var("last_login",$needsecure->member['last_login']);
     $tpl->set_var("words_welcome",$needsecure->words['welcome']);
     $tpl->set_var("words_last_visit",$needsecure->words['last_visit']);
     $tpl->set_var("words_logout",$needsecure->words['logout']);
     $tpl->set_var("words_menu_edit_profile",$needsecure->words['menu_edit_profile']);
     $tpl->set_var("words_menu_edit_password",$needsecure->words['menu_edit_password']);
     $tpl->set_var("words_menu_accessing_dirs",$needsecure->words['menu_accessing_dirs']);
     $tpl->set_var("words_menu_anounces",$needsecure->words['menu_anounces']);
     $tpl->set_var("words_fields_not_filled",$needsecure->words['fields_not_filled']);

     switch ( $needsecure->input['code'] ) {
        case '00':
          $tpl->set_var("PassBody",false);
	  $tpl->set_var("MemberProfile",false);
	  $tpl->set_var("ChangePasswd",false);
          $tpl->set_var("PasswdJava",false);
          $tpl->set_var("ProfileJava",false);
	  $tpl->set_var("AccessingDirs",false);
	  $this->getAnounces();
	break;
	case '01':
          $tpl->set_var("PassBody",false);
	  $tpl->set_var("SystemAnounces",false);
          $tpl->set_var("ChangePasswd",false);
          $tpl->set_var("PasswdJava",false);
	  $tpl->set_var("AccessingDirs",false);
          $this->MemberProfile();
	break;
	case '02':
	  $tpl->set_var("OtherBody",false);
	  $tpl->set_var("SystemAnounces",false);
	  $tpl->set_var("MemberProfile",false);
          $tpl->set_var("ProfileJava",false);
          $tpl->set_var("AccessingDirs",false);
          $this->ChangePasswd();
	break;
	case '03':
          $tpl->set_var("PassBody",false);
	  $tpl->set_var("SystemAnounces",false);
	  $tpl->set_var("MemberProfile",false);
          $tpl->set_var("ProfileJava",false);
	  $tpl->set_var("ChangePasswd",false);
          $tpl->set_var("PasswdJava",false);
          $this->AccessingDirs();
	break;
      default:
          $tpl->set_var("PassBody",false);
          $tpl->set_var("MemberProfile",false);
          $tpl->set_var("ProfileJava",false);
	  $tpl->set_var("ChangePasswd",false);
	  $tpl->set_var("PasswdJava",false);
	  $tpl->set_var("AccessingDirs",false);
	  $this->getAnounces();
	break;
     }

     $tpl->pparse("main",false);

   }

   function getAnounces() {
     global $needsecure,$DB,$tpl,$INFO,$std;

     $tpl->set_var("words_anounces_header",$needsecure->words['anounces_header']);
     $tpl->set_var("words_more_anounces_link",$needsecure->words['more_anounces_link']);

     if ( ( $needsecure->input['code'] == 00 ) && ( empty($needsecure->input['id']) ) ) {
        $DB->query("SELECT id,date,header FROM ns_anounces ORDER BY date");
     } elseif ( ( $needsecure->input['code'] == 00 ) && ( !empty($needsecure->input['id']) ) ) {
        $DB->query("SELECT id,date,header,body FROM ns_anounces WHERE id='{$needsecure->input['id']}'");
        $anounce = $DB->fetch_row();
	$tpl->set_var("NoAnounces",false);
	$tpl->set_var("AnounceRow",false);
	$tpl->set_var("anounce_id",$anounce['id']);
	$tpl->set_var("anounce_date",$anounce['date']);
        $tpl->set_var("anounce_header",$anounce['header']);
	$tpl->set_var("anounce_body",$anounce['body']);
	return;
     } else {
        $DB->query("SELECT id,date,header FROM ns_anounces ORDER BY date LIMIT 0,5");
     }

     if ( $DB->get_num_rows() < 1 ) {
        $tpl->set_var("AnounceRow",false);
	$tpl->set_var("OneAnounce",false);
	$tpl->set_var("words_no_anounces",$needsecure->words['no_anounces']);
	return;
     } else {
	$tpl->set_var("NoAnounces",false);
        $tpl->set_var("OneAnounce",false);
	while ( $anounce = $DB->fetch_row() ) {
	   $tpl->set_var("anounce_id",$anounce['id']);
	   $tpl->set_var("anounce_date",$anounce['date']);
           $tpl->set_var("anounce_header",$anounce['header']);
	   $tpl->parse("AnounceRow",true);
	}
     }

   }

   function MemberProfile() {
     global $needsecure,$std,$DB,$INFO,$tpl;

     if ( $needsecure->input['step'] == 'proceed' ) {

        $DB->query("UPDATE ns_members SET realname='{$needsecure->input['realname']}',email='{$needsecure->input['email']}',lang='{$needsecure->input['lang']}' where id='{$needsecure->member['id']}'");

        $tpl->set_var("ProfileForm",false);
	//$tpl->set_var("words_profile_saved",$needsecure->words['profile_saved']);

	$url = "{$needsecure->base_url}&act=idx&code=01";
	@flush();
	$std->redirectPage($url,$needsecure->words['profile_saved']);

     } else {

	$DB->query("SELECT name,realname,regdate,expire,email,lang FROM ns_members where id='{$needsecure->member['id']}'");
	$member = $DB->fetch_row();

        $tpl->set_var("ProfiledSaved",false);

	$tpl->set_var("member_name",$member['name']);
	$tpl->set_var("member_realname",$member['realname']);
	$tpl->set_var("member_regdate",$member['regdate']);
	$tpl->set_var("member_expire",$member['expire']);
	$tpl->set_var("member_email",$member['email']);

	$langs = explode("|",$INFO['INSTALLED_LANGS']);
	 for ($i=0;$i<count($langs);$i++) {
            $lang_data = explode(":",$langs[$i]);
	    if ( $lang_data[0] == $needsecure->member['lang'] ) {
              $selected = "selected";
	    } else {
              $selected = "";
	    }
	    $lang_select_options .= "<option value='{$lang_data[0]}' {$selected}>{$lang_data[1]}</option>\n";
         }
        $tpl->set_var("lang_select_options",$lang_select_options);

        $tpl->set_var("words_profile_header",$needsecure->words['profile_header']);
        $tpl->set_var("words_username",$needsecure->words['username']);
	$tpl->set_var("words_realname",$needsecure->words['realname']);
	$tpl->set_var("words_regdate",$needsecure->words['regdate']);
        $tpl->set_var("words_expire",$needsecure->words['expire']);
	$tpl->set_var("words_email",$needsecure->words['email']);
	$tpl->set_var("words_interface_lang",$needsecure->words['interface_lang']);
        $tpl->set_var("words_save_profile",$needsecure->words['save_profile']);

        unset($member);

	return;

     }

   }

   function ChangePasswd() {
     global $needsecure,$std,$sess,$tpl,$DB,$INFO;

     if ( $needsecure->input['step'] == 'proceed' ) {

        if ( $needsecure->input['new_password'] != $needsecure->input['new_password_again'] ) {
             $std->Error($needsecure->words['pass_missmatch']);
	}

        $oldpass = md5($needsecure->input['old_password']);

        $DB->query("SELECT id FROM ns_members WHERE password='{$oldpass}'");

	$member = $DB->fetch_row();

	if ( $member['id'] != $needsecure->member['id'] ) {
             $std->Error($needsecure->words['pass_incorrect']);
	}

	if ( (!$needsecure->input['random_passwd']) or (empty($needsecure->input['random_passwd'])) ) {
	   $member['plain_password'] = $needsecure->input['new_password'];
	} else {
           $member['plain_password'] = $std->make_password();
	}
        $new_pass = md5($member['plain_password']);

	$DB->query("UPDATE ns_members SET password='{$new_pass}', plain_password='{$member['plain_password']}' where id='{$needsecure->member['id']}'");

	// Chage user password in all .htpasswd in his accessible dirs
	require ("{$needsecure->dirs['INC']}htaccess.class{$INFO['PHP_EXT']}");
	 $ht = new htaccess;
	$dirs = explode("|",$needsecure->member['access_dirs']);
       //AccessDirsRow
        for ($i=0;$i<count($dirs);$i++) {
	      $ht->setHtpasswd($INFO['HTA_TOP_DIR'] . "/" . $dirs[$i] . "/.htpasswd");
	      $ht->setUserPass($needsecure->member['name'],$member['plain_password']);
       }
        unset($dirs);

	// Re-Auth member:

    $user_agent = substr($_SERVER["HTTP_USER_AGENT"],0,50);
    $last_activity = time();

          $DB->query("SELECT
          id,name,password,plain_password,regdate,expire,email,realname,
	      lang,member_ip,member_browser,member_referrer,
	      last_login,count_visits,access_dirs,
	      suspended,approved,authcode
	      FROM ns_members
	      WHERE id='{$needsecure->member['id']}'");

        $res = $DB->fetch_row();

        if ( $needsecure->input['s'] and $needsecure->input['s'] != '' and $needsecure->input['s'] != 0 ) {
	       $session_id = $needsecure->input['s'];
           $DB->query("DELETE FROM ns_sessions WHERE id <> '{$session_id}' and member_ip='{$needsecure->input['IP_ADDRESS']}'");
	       $DB->query("UPDATE ns_sessions SET member_id='{$res['id']}',member_name='{$res['name']}',member_ip='{$needsecure->input['IP_ADDRESS']}',member_browser='{$user_agent}',last_activity='{$last_activity}' WHERE id='{$session_id}'");
	    } else {
	       $session_id = md5( uniqid(microtime()) );
           $DB->query("DELETE FROM ns_sessions WHERE member_ip='{$needsecure->input['IP_ADDRESS']}'");
           $DB->query("INSERT INTO ns_sessions (id,member_id,member_name,member_ip,member_browser,last_activity) VALUES ('{$session_id}','{$res['id']}','{$res['name']}','{$needsecure->input['IP_ADDRESS']}','{$user_agent}','{$last_activity}')");
	    }

        $needsecure->member      = $res;
	    $needsecure->session_id  = $session_id;

        // Set cookies
        $std->my_setcookie( "member_id"         , $needsecure->member['id']       , -1  );
        $std->my_setcookie( "member_pass_hash"  , $needsecure->member['password'] , -1  );
	    $std->my_setcookie( "member_lang_id"    , $needsecure->member['lang']     , -1  );

	// Disable output
	$tpl->set_var("ChangePasswdForm",false);

	// Send mail
    $member['realname'] = $needsecure->member['realname'];
	$member['name'] = $needsecure->member['name'];
	$member['email'] = $needsecure->member['email'];

        require ("{$needsecure->dirs['INC']}mail.class{$INFO['PHP_EXT']}");
        $mailer = new mailer($INFO['EMAIL_SENDER']);
        $mailer->setFrom($INFO['EMAIL_OUT']);
        $mailer->setTo($member['email']);
        $mailer->setMessage("lost_password",$member);
        $mailer->setSubj("{$INFO['SITE_NAME']}: Your new login details");
        $mailer->send_mail();

           $url = "{$needsecure->base_url}&act=idx&code=00";
	       @flush();
	       $std->redirectPage($url,$needsecure->words['passwd_changed']);

     } else {
        $tpl->set_var("words_change_passwd_header",$needsecure->words['change_passwd_header']);
        $tpl->set_var("words_old_password",$needsecure->words['old_password']);
     	$tpl->set_var("words_new_password",$needsecure->words['new_password']);
	    $tpl->set_var("words_new_password_again",$needsecure->words['new_password_again']);
        $tpl->set_var("words_change_my_passwd",$needsecure->words['change_my_passwd']);
        $tpl->set_var("words_passwd_creation_message",$needsecure->words['passwd_creation_message']);
        $tpl->set_var("words_create_random_passwd",$needsecure->words['create_random_passwd']);
	return;

     }

   }

   function AccessingDirs() {
     global $needsecure, $std, $DB, $INFO, $tpl;

     $tpl->set_var("words_accessing_dirs_header",$needsecure->words['accessing_dirs_header']);
     $tpl->set_var("words_username",$needsecure->words['username']);
     $tpl->set_var("member_name",$needsecure->member['name']);

     $dirs = explode("|",$needsecure->member['access_dirs']);
     //AccessDirsRow
     for ($i=0;$i<count($dirs);$i++) {

       $parts = pathinfo( preg_replace( "#/$#","",$INFO['HTA_TOP_DIR']) );
       $real_dirname = $parts['basename'];

       $url = "http://" . $_SERVER["HTTP_HOST"] . "/" . $real_dirname . "/" . $dirs[$i] ."/";
       $dirname = $dirs[$i];
       $tpl->set_var("url",$url);
       $tpl->set_var("dirname",$dirname);
       $tpl->parse("AccessDirsRow",true);
     }
     unset($dirs);
   }


} // End of class Idx;


?>