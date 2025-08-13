<?php

// defines if this script requires to be logged in
define( "PRIVATE", true );

########################################

require_once('../lib/inc-admin.php');
require_once(FS_PATH."/etc/locale/".LANG_PACK."/inc-admin_user-edit.php");

########################################

if (!$_REQUEST['do']) {

  header ("Location: user-list.php\n\n");

  exit;

}  elseif ($_REQUEST['do'] == "new") {
  
  $section = "sysadmin";
  include (INTERFACE_HEADER);

?>

          <script language='Javascript'>
            function checkForm () {
              var msg = '';
              if (document.registerForm.nameField.value == '') {
              msg = msg + '- <?php echo  USEDIT_CHECKFORM_1 ?>\n';  
              }
              var emailPat=/^(.+)@(.+)\.(.+)$/;
              var matchArray=document.registerForm.emailField.value.match(emailPat);
              if (document.registerForm.emailField.value == '') {
                msg = msg + '- <?php echo  USEDIT_CHECKFORM_2 ?>\n';  
              } else if (matchArray == null) {
                msg = msg + '- <?php echo  USEDIT_CHECKFORM_3 ?>\n'; 
              }
              if (document.registerForm.passwordField.value == '') {
                msg = msg + '- <?php echo  USEDIT_CHECKFORM_4 ?>.\n';  
              }
              if (document.registerForm.passwordField2.value == '') {
                msg = msg + '- <?php echo  USEDIT_CHECKFORM_5 ?>\n';  
              } else if (document.registerForm.passwordField.value.length < 6) {
                msg = msg + '- <?php echo  USEDIT_CHECKFORM_6 ?>\n';  
              } else if (document.registerForm.passwordField.value.length > 20) {
                msg = msg + '- <?php echo  USEDIT_CHECKFORM_7 ?>\n';  
              } else if (document.registerForm.passwordField2.value != document.registerForm.passwordField.value) {
                msg = msg + '- <?php echo  USEDIT_CHECKFORM_8 ?>\n';  
              }
              if (msg == '') {
                return (true);
              } else {
                alert(msg);
                return (false);
              }
            }
          </script>
          <div align=center>
          <table width='350' border=0 cellspacing=1 cellpadding=3 class='border'>
          <form action='<?php echo $_SERVER['PHP_SELF']?>' method=post name='registerForm' onSubmit='return checkForm ();'>
          <input type=hidden name='do' value='register'>
          <tr>
            <td class='header' nowrap><B><?php echo  USEDIT_NEW_HEADER ?></B></td>
          </tr>
          <tr>
            <td class='content' nowrap align=center>
              <table border=0 cellspacing=0 cellpadding=2>
              <tr>
                <td align=left nowrap colspan=2><?php echo  USEDIT_NEW_CAPTION ?><br><br></td>
              </tr>
              <tr>
                <td align=right nowrap><?php echo  USEDIT_FORMS_GROUP ?></td>
                <td align=left nowrap><?php echo groupSelect($_REQUEST['selected'])?></td>
              </tr>
              <tr>
                <td align=right nowrap><?php echo  USEDIT_FORMS_NAME ?></td>
                <td align=left nowrap><input type=text name='nameField' size='20' maxlength='50' value='' class=input_content></td>
              </tr>
              <tr>
                <td align=right nowrap><?php echo  USEDIT_FORMS_NICKNAME ?></td>
                <td align=left nowrap><input type=text name='nicknameField' size='20' maxlength='50' value='' class=input_content></td>
              </tr>
              <tr>
                <td align=right nowrap><?php echo  USEDIT_FORMS_EMAIL ?></td>
                <td align=left nowrap><input type=text name='emailField' size='20' maxlength='75' value='' class=input_content></td>
              </tr>
              <tr>
                <td align=right nowrap><?php echo  USEDIT_FORMS_PASSWD_1 ?></td>
                <td align=left nowrap><input type=password name='passwordField' size='20' maxlength='20' value='' class=input_content></td>
              </tr>
              <tr>
                <td align=right nowrap><?php echo  USEDIT_FORMS_PASSWD_2 ?></td>
                <td align=left nowrap><input type=password name='passwordField2' size='20' maxlength='20' value='' class=input_content></td>
              </tr>
              <tr>
                <td align=center nowrap colspan=2>
                  <input type=submit value="<?php echo  USEDIT_FORMS_BTN_SAVE ?>" class='btn_content'>
                  &nbsp;
                  <input type=button value="<?php echo  USEDIT_FORMS_BTN_USLIST ?>" onclick="top.location.href='./user-list.php';" class='btn_content'>
                  </td>
              </tr>
              </table>
            </td>
          </tr>
          </form>
          </table>
          </div>

<?php

  include (INTERFACE_FOOTER);

  exit;

}  elseif ($_REQUEST['do'] == "register") {

  $dbrs = $dbconn->Execute(" SELECT email FROM netjuke_users WHERE email = '".$_REQUEST['emailField']."' or (nickname = '".$_REQUEST['nicknameField']."' and nickname != '') ");
  
  if ($dbrs->RecordCount() === 0) {
  
    $sql1 = " INSERT INTO netjuke_users (name,nickname,email,password,gr_id,created,updated,login_cnt) "
           ."  VALUES ( '".raw_to_db($_REQUEST['nameField'])."' "
           ."          ,'".raw_to_db($_REQUEST['nicknameField'])."' "
           ."          ,'".$_REQUEST['emailField']."' "
           ."          ,'".md5($_REQUEST['passwordField'])."' "
           ."          ,'".$_REQUEST['gr_idField']."' "
           ."          ,'".date("Y-m-d H:i:s")."' "
           ."          ,'".date("Y-m-d H:i:s")."' "
           ."          ,'0') ";
    
    $sql2 = " INSERT INTO netjuke_userprefs ( us_email,bgcolor,text,link,alink,vlink "
           ."                                ,td_border,td_header,td_header_fc,td_content "
           ."                                ,font_face,font_size, inv_icn ) "
           ."  VALUES ( '".$_REQUEST['emailField']."' "
           ."          ,'".DEFAULT_BGCOLOR."' "
           ."          ,'".DEFAULT_TEXT."' "
           ."          ,'".DEFAULT_LINK."' "
           ."          ,'".DEFAULT_ALINK."' "
           ."          ,'".DEFAULT_VLINK."' "
           ."          ,'".DEFAULT_TD_BORDER."' "
           ."          ,'".DEFAULT_TD_HEADER."' "
           ."          ,'".DEFAULT_TD_HEADER_FC."' "
           ."          ,'".DEFAULT_TD_CONTENT."' "
           ."          ,'".DEFAULT_FONT_FACE."' "
           ."          ,'".DEFAULT_FONT_SIZE."' "
           ."          ,'".INV_ICN."' )";
    
    if ( ($dbconn->Execute($sql1) === false) || ($dbconn->Execute($sql2) === false) ) {
  
      alert ("There was an unexpected error! Please try again.");
    
    }
  
  } else {

    alert (USEDIT_DENIED_4);
    
  }
  
  $dbrs->Close();

  $section = "sysadmin";
  include (INTERFACE_HEADER);

?>
  
          <div align=center>
          <table width='350' border=0 cellspacing=1 cellpadding=3 class='border'>
          <tr>
            <td class='header' nowrap><B><?php echo  USEDIT_NEW_HEADER ?></B></td>
          </tr>
          <tr>
            <td class='content' nowrap align=center>
              <table border=0 cellspacing=0 cellpadding=2>
              <tr>
                <td align=left nowrap colspan=2><?php echo  USEDIT_NEW_SUCCESS ?>:<br><br></td>
              </tr>
              <tr>
                <td align=right nowrap><?php echo  USEDIT_FORMS_NAME ?></td>
                <td align=left nowrap><B><A HREF='<?php echo $_SERVER['PHP_SELF']?>?do=edit&email=<?php echo  obfuscate_apply($_REQUEST['emailField']) ?>'><?php echo db_to_raw($_REQUEST['nameField'])?></B></td>
              </tr>
              <tr>
                <td align=right nowrap><?php echo  USEDIT_FORMS_NICKNAME ?></td>
                <td align=left nowrap><B><A HREF='<?php echo $_SERVER['PHP_SELF']?>?do=edit&email=<?php echo  obfuscate_apply($_REQUEST['emailField']) ?>'><?php echo db_to_raw($_REQUEST['nicknameField'])?></B></td>
              </tr>
              <tr>
                <td align=right nowrap><?php echo  USEDIT_FORMS_EMAIL ?></td>
                <td align=left nowrap><B><A HREF='<?php echo $_SERVER['PHP_SELF']?>?do=edit&email=<?php echo  obfuscate_apply($_REQUEST['emailField']) ?>'><?php echo $_REQUEST['emailField']?></a></B></td>
              </tr>
              </table>
            </td>
          </tr>
          </table>
          </div>

<?php

  include (INTERFACE_FOOTER);

  exit;

}  elseif ($_REQUEST['do'] == "delete") {

  $email = obfuscate_undo($_REQUEST['email']);
  
  if ($email == $NETJUKE_SESSION_VARS["email"]) {
  
    alert(USEDIT_DENIED_3);
  
  } else {
  
    $dbconn->Execute("DELETE FROM netjuke_plists_fav WHERE us_email = '".$email."'");
    $dbconn->Execute("DELETE FROM netjuke_plists_tracks WHERE us_email = '".$email."'");
    $dbconn->Execute("DELETE FROM netjuke_plists WHERE us_email = '".$email."'");
    $dbconn->Execute("DELETE FROM netjuke_userprefs WHERE us_email = '".$email."'");

    $dbconn->Execute("DELETE FROM netjuke_sessions WHERE email = '".$email."'");

    $dbconn->Execute("DELETE FROM netjuke_users WHERE email = '".$email."'");
  
    header("Location: ".$_SERVER['PHP_SELF']);
  
  }

}  elseif ($_REQUEST['do'] == "edit") {
  
  if ($_REQUEST['prevEmailField'] != '') {
   
    $now = date("Y-m-d H:i:s");
    
    $dbrs1a = $dbconn->Execute(" SELECT email FROM netjuke_users WHERE email = '".$_REQUEST['emailField']."' ");
    $dbrs1b = $dbconn->Execute(" SELECT email FROM netjuke_users WHERE nickname = '".$_REQUEST['nicknameField']."' and nickname != '' ");
  
    if (    (    (($_REQUEST['emailField'] == $_REQUEST['prevEmailField']) && ($dbrs1a->RecordCount() == 1))
              || (($_REQUEST['emailField'] != $_REQUEST['prevEmailField']) && ($dbrs1a->RecordCount() == 0)) )
         && (    (($_REQUEST['nicknameField'] == $_REQUEST['prevNicknameField']) && ($dbrs1b->RecordCount() == 1))
              || (($_REQUEST['nicknameField'] != $_REQUEST['prevNicknameField']) && ($dbrs1b->RecordCount() == 0))
              || (($_REQUEST['nicknameField'] == $_REQUEST['prevNicknameField']) && ($_REQUEST['nicknameField'] == '')) )  ) {
   
      // We have both a unique email and nickname
   
      $sql = " UPDATE netjuke_users SET"
           . "   name = '".raw_to_db($_REQUEST['nameField'])."' "
           . " , nickname = '".raw_to_db($_REQUEST['nicknameField'])."' "
           . " , email = '".$_REQUEST['emailField']."' "
           . " , gr_id = '".$_REQUEST['gr_idField']."' "
           . " , updated = '".$now."' ";
  
      if ($newPasswordField != '') {
        $sql .= " , password = '".md5($_REQUEST['newPasswordField'])."' ";
      }
    
      $sql .= " WHERE email = '".$_REQUEST['prevEmailField']."' ";
    
      if ($dbconn->Execute($sql) === false) {
      
        alert ("There was an unexpected error! Please try again.");
        
      } else {
    
        $dbconn->Execute("UPDATE netjuke_sessions SET email = '".$_REQUEST['emailField']."' WHERE email = '".$_REQUEST['prevEmailField']."'");
        $dbconn->Execute("UPDATE netjuke_userprefs SET us_email = '".$_REQUEST['emailField']."' WHERE us_email = '".$_REQUEST['prevEmailField']."'");
        $dbconn->Execute("UPDATE netjuke_plists SET us_email = '".$_REQUEST['emailField']."' WHERE us_email = '".$_REQUEST['prevEmailField']."'");
        $dbconn->Execute("UPDATE netjuke_plists_fav SET us_email = '".$_REQUEST['emailField']."' WHERE us_email = '".$_REQUEST['prevEmailField']."'");
        $dbconn->Execute("UPDATE netjuke_plists_tracks SET us_email = '".$_REQUEST['emailField']."' WHERE us_email = '".$_REQUEST['prevEmailField']."'");
      
      }
      
    } else {
      
      javascript ("alert ('".USEDIT_DENIED_4."');");
    
    }
    
    $dbrs1a->Close();
    $dbrs1b->Close();
    
    list ( $user_email
         , $user_name
         , $user_gr_id
         , $user_nickname
         , $user_created
         , $user_updated
         , $user_login_cnt ) = get_user_data($_REQUEST['emailField']);
    
    if ($_REQUEST['prevEmailField'] == $NETJUKE_SESSION_VARS["email"]) {

      $NETJUKE_SESSION_VARS["email"]     = $user_email;
      $NETJUKE_SESSION_VARS["name"]      = $user_name;
      $NETJUKE_SESSION_VARS["gr_id"]     = $user_gr_id;
      $NETJUKE_SESSION_VARS["nickname"]  = $user_nickname;
      
      netjuke_session('update');

    }

  } else {
  
    list ( $user_email
         , $user_name
         , $user_gr_id
         , $user_nickname
         , $user_created
         , $user_updated
         , $user_login_cnt ) = get_user_data(obfuscate_undo($_REQUEST['email']));
    
  }
  
  $section = "sysadmin";
  include (INTERFACE_HEADER);

?>

          <script language='Javascript'>
            function checkForm () {
              var msg = '';
              if (document.registerForm.nameField.value == '') {
              msg = msg + '- <?php echo  USEDIT_CHECKFORM_1 ?>\n';  
              }
              var emailPat=/^(.+)@(.+)\.(.+)$/;
              var matchArray=document.registerForm.emailField.value.match(emailPat);
              if (document.registerForm.emailField.value == '') {
                msg = msg + '- <?php echo  USEDIT_CHECKFORM_2 ?>\n';  
              } else if (matchArray == null) {
                msg = msg + '- <?php echo  USEDIT_CHECKFORM_3 ?>\n'; 
              }
              if ( (document.registerForm.newPasswordField.value.length > 0) && (document.registerForm.newPasswordField.value.length < 6) ) {
                msg = msg + '- <?php echo  USEDIT_CHECKFORM_6 ?>\n';  
              } else if (document.registerForm.newPasswordField.value.length > 20) {
                msg = msg + '- <?php echo  USEDIT_CHECKFORM_7 ?>\n';  
              } else if (document.registerForm.newPasswordField2.value != document.registerForm.newPasswordField.value) {
                msg = msg + '- <?php echo  USEDIT_CHECKFORM_8 ?>\n';  
              }
              if (msg == '') {
                return (true);
              } else {
                alert(msg);
                return (false);
              }
            }
          </script>
          <div align=center>
          <table width='350' border=0 cellspacing=1 cellpadding=3 class='border'>
          <form action='<?php echo $_SERVER['PHP_SELF']?>' method=post name='registerForm' onSubmit='return checkForm ();'>
          <input type=hidden name='do' value='edit'>
          <input type=hidden name='prevEmailField' value='<?php echo $user_email?>'>
          <input type=hidden name='prevNicknameField' value='<?php echo $user_nickname?>'>
          <tr>
            <td class='header' nowrap><B><?php echo  USEDIT_EDIT_HEADER ?></B></td>
          </tr>
          <tr>
            <td class='content' nowrap align=center>
              <table border=0 cellspacing=0 cellpadding=2>
              <tr>
                <td align=left nowrap colspan=2><?php echo  USEDIT_EDIT_CAPTION ?><br><br></td>
              </tr>
              <tr>
                <td align=right nowrap><?php echo  USEDIT_FORMS_GROUP ?></td>
                <td align=left nowrap><?php echo groupSelect($user_gr_id)?></td>
              </tr>
              <tr>
                <td align=right nowrap><?php echo  USEDIT_FORMS_NAME ?></td>
                <td align=left nowrap><input type=text name='nameField' size='20' maxlength='50' value="<?php echo db_to_raw($user_name)?>" class=input_content></td>
              </tr>
              <tr>
                <td align=right nowrap><?php echo  USEDIT_FORMS_NICKNAME ?></td>
                <td align=left nowrap><input type=text name='nicknameField' size='20' maxlength='50' value="<?php echo db_to_raw($user_nickname)?>" class=input_content></td>
              </tr>
              <tr>
                <td align=right nowrap><?php echo  USEDIT_FORMS_EMAIL ?></td>
                <td align=left nowrap><input type=text name='emailField' size='20' maxlength='75' value="<?php echo $user_email?>" class=input_content></td>
              </tr>
              <tr>
                <td align=right nowrap><?php echo  USEDIT_FORMS_PASSWD_3 ?></td>
                <td align=left nowrap><input type=password name='newPasswordField' size='20' maxlength='20' value='' class=input_content></td>
              </tr>
              <tr>
                <td align=right nowrap><?php echo  USEDIT_FORMS_PASSWD_4 ?></td>
                <td align=left nowrap><input type=password name='newPasswordField2' size='20' maxlength='20' value='' class=input_content></td>
              </tr>
              <tr>
                <td align=right nowrap><?php echo  USEDIT_FORMS_CREATED ?> = </td>
                <td align=left nowrap><?php echo  date("Y-m-d H:i:s", strtotime($user_created)) ?></td>
              </tr>
              <tr>
                <td align=right nowrap><?php echo  USEDIT_FORMS_UPDATED ?> = </td>
                <td align=left nowrap><?php echo  date("Y-m-d H:i:s", strtotime($user_updated)) ?></td>
              </tr>
              <tr>
                <td align=right nowrap><?php echo  USEDIT_FORMS_LOGIN_CNT ?> = </td>
                <td align=left nowrap><?php echo  $user_login_cnt ?></td>
              </tr>
              <tr>
                <td align=center nowrap colspan=2>
                  <input type=submit value="<?php echo  USEDIT_FORMS_BTN_SAVE ?>" class='btn_content'>
                  &nbsp;
                  <input type=reset value="<?php echo  USEDIT_FORMS_BTN_RESET ?>" class='btn_content'>
                  &nbsp;
                  <input type=button value="<?php echo  USEDIT_FORMS_BTN_DEL ?>" onclick="if (confirm('<?php echo  USEDIT_FORMS_BTN_DEL_CONF ?>'))  top.location.href = '<?php echo $_SERVER['PHP_SELF']?>?do=delete&email=<?php echo obfuscate_apply($user_email)?>'; " class='btn_content'>
                  &nbsp;
                  <input type=button value="<?php echo  USEDIT_FORMS_BTN_USLIST ?>" onclick="top.location.href='./user-list.php';" class='btn_content'>
                  </td>
              </tr>
              </table>
            </td>
          </tr>
          </form>
          </table>
          </div>

<?php

  include (INTERFACE_FOOTER);

  exit;

} else {

  header ("Location: index.php\n\n");

  exit;

}

function get_user_data($email) {
  
    GLOBAL $dbconn;
    
    $dbrs = $dbconn->Execute(" SELECT email, name, gr_id, nickname, created, updated, login_cnt FROM netjuke_users WHERE email = '".$email."' ");
  
    if ($dbrs->RecordCount() === 1) {
    
      $user_email      = $dbrs->fields[0];
      $user_name       = $dbrs->fields[1];
      $user_gr_id      = $dbrs->fields[2];
      $user_nickname   = $dbrs->fields[3];
      $user_created    = $dbrs->fields[4];
      $user_updated    = $dbrs->fields[5];
      $user_login_cnt  = $dbrs->fields[6];
    
    } else {
      
      alert (USEDIT_DENIED_1);
      
    }
      
    return array ( $user_email
                 , $user_name
                 , $user_gr_id
                 , $user_nickname
                 , $user_created
                 , $user_updated
                 , $user_login_cnt );

}

?>
