<?php

// defines if this script requires to be logged in
define( "PRIVATE", false );

require_once('./lib/inc-common.php');
require_once(FS_PATH."/etc/locale/".LANG_PACK."/inc-account.php");

if (!$_REQUEST['do']) {

  header ("Location: index.php\n\n");

  exit;

}  elseif ($_REQUEST['do'] == "new") {

  if (abs(substr(SECURITY_MODE,2,1)) != 0) header("Location: ".WEB_PATH."/login.php");
  
  $section = "register";
  include (INTERFACE_HEADER);

?>

          <script language='Javascript'>
            function checkForm () {
              var msg = '';
              if (document.registerForm.nameField.value == '') {
              msg = msg + '- <?php echo  ACCNT_CHECKFORM_1 ?>\n';  
              }
              var emailPat=/^(.+)@(.+)\.(.+)$/;
              var matchArray=document.registerForm.emailField.value.match(emailPat);
              if (document.registerForm.emailField.value == '') {
                msg = msg + '- <?php echo  ACCNT_CHECKFORM_2 ?>\n';  
              } else if (matchArray == null) {
                msg = msg + '- <?php echo  ACCNT_CHECKFORM_3 ?>\n'; 
              }
              if (document.registerForm.passwordField.value == '') {
                msg = msg + '- <?php echo  ACCNT_CHECKFORM_4 ?>\n';  
              }
              if (document.registerForm.passwordField2.value == '') {
                msg = msg + '- <?php echo  ACCNT_CHECKFORM_5 ?>\n';  
              } else if (document.registerForm.passwordField.value.length < 6) {
                msg = msg + '- <?php echo  ACCNT_CHECKFORM_6 ?>\n';  
              } else if (document.registerForm.passwordField.value.length > 20) {
                msg = msg + '- <?php echo  ACCNT_CHECKFORM_7 ?>\n';  
              } else if (document.registerForm.passwordField2.value != document.registerForm.passwordField.value) {
                msg = msg + '- <?php echo  ACCNT_CHECKFORM_8 ?>\n';  
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
          <form action='account.php' method=post name='registerForm' onSubmit='return checkForm ();'>
          <input type=hidden name='do' value='register'>
          <tr>
            <td class='header' nowrap><B><?php echo  ACCNT_NEW_HEADER ?></B></td>
          </tr>
          <tr>
            <td class='content' nowrap align=center>
              <table border=0 cellspacing=0 cellpadding=2>
              <tr>
                <td align=left nowrap colspan=2><?php echo  ACCNT_NEW_CAPTION ?><br><br></td>
              </tr>
              <tr>
                <td align=right nowrap><?php echo  ACCNT_FORMS_NAME ?></td>
                <td align=left nowrap><input type=text name='nameField' size='20' maxlength='50' value='' class=input_content></td>
              </tr>
              <tr>
                <td align=right nowrap><?php echo  ACCNT_FORMS_NICKNAME ?></td>
                <td align=left nowrap><input type=text name='nicknameField' size='20' maxlength='50' value='' class=input_content></td>
              </tr>
              <tr>
                <td align=right nowrap><?php echo  ACCNT_FORMS_EMAIL ?></td>
                <td align=left nowrap><input type=text name='emailField' size='20' maxlength='75' value='' class=input_content></td>
              </tr>
              <tr>
                <td align=right nowrap><?php echo  ACCNT_FORMS_PASSWD_1 ?></td>
                <td align=left nowrap><input type=password name='passwordField' size='20' maxlength='20' value='' class=input_content></td>
              </tr>
              <tr>
                <td align=right nowrap><?php echo  ACCNT_FORMS_PASSWD_2 ?></td>
                <td align=left nowrap><input type=password name='passwordField2' size='20' maxlength='20' value='' class=input_content></td>
              </tr>
              <tr>
                <td align=center nowrap colspan=2>
                  <input type=submit value='<?php echo  ACCNT_FORMS_BTN_LOGIN ?>' class='btn_content'>
                  &nbsp;
                  <input type=button value='<?php echo  ACCNT_FORMS_BTN_REGISTER ?>' class='btn_content' onclick="top.location.href='./login.php';">
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

  # check if the security modes allows public registration.
  if (abs(substr(SECURITY_MODE,2,1)) != 0) header("Location: ".WEB_PATH."/login.php");

  $dbrs = $dbconn->Execute(" SELECT email FROM netjuke_users WHERE email = '".$_REQUEST['emailField']."' or (nickname = '".$_REQUEST['nicknameField']."' and nickname != '') ");
  
  if ($dbrs->RecordCount() == 0) {
  
    $sql1 = " INSERT INTO netjuke_users (name,nickname,email,password,gr_id,created,updated,login_cnt) "
           ."  VALUES ( '".raw_to_db($_REQUEST['nameField'])."' "
           ."          ,'".raw_to_db($_REQUEST['nicknameField'])."' "
           ."          ,'".$_REQUEST['emailField']."' "
           ."          ,'".md5($_REQUEST['passwordField'])."' "
           ."          , 3 "
           ."          ,'".date("Y-m-d H:i:s")."' "
           ."          ,'".date("Y-m-d H:i:s")."' "
           ."          ,'1' ) ";
    
    $sql2 = " INSERT INTO netjuke_userprefs ( us_email,bgcolor,text,link,alink,vlink "
           ."                                ,td_border,td_header,td_header_fc,td_content "
           ."                                ,font_face,font_size, inv_icn ) "
           ."  VALUES ( '".$_REQUEST['emailField']."' "
           ."          ,'".$NETJUKE_SESSION_VARS["bgcolor"]."' "
           ."          ,'".$NETJUKE_SESSION_VARS["text"]."' "
           ."          ,'".$NETJUKE_SESSION_VARS["link"]."' "
           ."          ,'".$NETJUKE_SESSION_VARS["alink"]."' "
           ."          ,'".$NETJUKE_SESSION_VARS["vlink"]."' "
           ."          ,'".$NETJUKE_SESSION_VARS["td_border"]."' "
           ."          ,'".$NETJUKE_SESSION_VARS["td_header"]."' "
           ."          ,'".$NETJUKE_SESSION_VARS["td_header_fc"]."' "
           ."          ,'".$NETJUKE_SESSION_VARS["td_content"]."' "
           ."          ,'".$NETJUKE_SESSION_VARS["font_face"]."' "
           ."          ,'".$NETJUKE_SESSION_VARS["font_size"]."' "
           ."          ,'".$NETJUKE_SESSION_VARS["inv_icn"]."' )";
    
    if ( ($dbconn->Execute($sql1) != false) && ($dbconn->Execute($sql2) != false) ) {

      $NETJUKE_SESSION_VARS["email"]       = $_REQUEST['emailField'];
      $NETJUKE_SESSION_VARS["name"]        = db_to_raw($_REQUEST['nameField']);
      $NETJUKE_SESSION_VARS["gr_id"]       = 3;
      $NETJUKE_SESSION_VARS["nickname"]    = db_to_raw($_REQUEST['nicknameField']);
    
      $bgflag = 1;
      
      netjuke_session('update');
  
    }
  
  } else {

    alert (ACCNT_DENIED);
    
  }
  
  $dbrs->Close();

  $section = "register";
  include (INTERFACE_HEADER);
  
  if ($bgflag == 1) $NETJUKE_SESSION_VARS["bgcolor"] = "";

?>
  
          <div align=center>
          <table width='350' border=0 cellspacing=1 cellpadding=3 class='border'>
          <tr>
            <td class='header' nowrap><B><?php echo  ACCNT_NEW_HEADER ?></B></td>
          </tr>
          <tr>
            <td class='content' nowrap align=center>
              <table border=0 cellspacing=0 cellpadding=2>
              <tr>
                <td align=left nowrap colspan=2><?php echo  ACCNT_NEW_SUCCESS_1 ?><br><br></td>
              </tr>
              <tr>
                <td align=right nowrap><?php echo  ACCNT_FORMS_NAME ?></td>
                <td align=left nowrap><B><?php echo $NETJUKE_SESSION_VARS["name"]?></B></td>
              </tr>
              <tr>
                <td align=right nowrap><?php echo  ACCNT_FORMS_NICKNAME ?></td>
                <td align=left nowrap><B><?php echo $NETJUKE_SESSION_VARS["nickname"]?></B></td>
              </tr>
              <tr>
                <td align=right nowrap><?php echo  ACCNT_FORMS_EMAIL ?></td>
                <td align=left nowrap><B><?php echo $NETJUKE_SESSION_VARS["email"]?></B></td>
              </tr>
              <tr>
                <td align=left colspan=2><br><?php echo  ACCNT_NEW_SUCCESS_2 ?></td>
              </tr>
              </table>
            </td>
          </tr>
          </table>
          </div>

<?php

  include (INTERFACE_FOOTER);

  exit;

}  elseif ($_REQUEST['do'] == "edit") {
  
  if ($_REQUEST['prevEmailField'] != '') {
   
    $dbrs1a = $dbconn->Execute(" SELECT email FROM netjuke_users WHERE email = '".$_REQUEST['emailField']."' ");
    $dbrs1b = $dbconn->Execute(" SELECT email FROM netjuke_users WHERE nickname = '".$_REQUEST['nicknameField']."' and nickname != '' ");
  
    if (    (    (($_REQUEST['emailField'] == $_REQUEST['prevEmailField']) && ($dbrs1a->RecordCount() == 1))
              || (($_REQUEST['emailField'] != $_REQUEST['prevEmailField']) && ($dbrs1a->RecordCount() == 0)) )
         && (    (($_REQUEST['nicknameField'] == $_REQUEST['prevNicknameField']) && ($dbrs1b->RecordCount() == 1))
              || (($_REQUEST['nicknameField'] != $_REQUEST['prevNicknameField']) && ($dbrs1b->RecordCount() == 0))
              || (($_REQUEST['nicknameField'] == $_REQUEST['prevNicknameField']) && ($_REQUEST['nicknameField'] == '')) )  ) {
   
      // We have both a unique email and nickname
      
      $dbrs2 = $dbconn->Execute(" SELECT email FROM netjuke_users "
             . " WHERE email = '".$_REQUEST['prevEmailField']."' "
             . " AND password = '".md5($_REQUEST['passwordField'])."' ");
      
      if ($dbrs2->RecordCount() == 1) {
   
        $sql = " UPDATE netjuke_users SET"
             . "   name = '".raw_to_db($_REQUEST['nameField'])."' "
             . " , nickname = '".raw_to_db($_REQUEST['nicknameField'])."'"
             . " , email = '".$_REQUEST['emailField']."'  "
             . " , updated = '".date("Y-m-d H:i:s")."'  ";
    
        if ($_REQUEST['newPasswordField'] != '') {
          $sql .= " , password = '".md5($_REQUEST['newPasswordField'])."' ";
        }
      
        $sql .= " WHERE email = '".$_REQUEST['prevEmailField']."' "
             .  " AND password = '".md5($_REQUEST['passwordField'])."' ";
      
        if ($dbconn->Execute($sql) === false) {
        
          alert ("There was an unexpected error! Please try again.");
          
        } else {
        
          $NETJUKE_SESSION_VARS["email"]     = $_REQUEST['emailField'];
          $NETJUKE_SESSION_VARS["name"]      = db_to_raw($_REQUEST['nameField']);
          $NETJUKE_SESSION_VARS["nickname"]  = db_to_raw($_REQUEST['nicknameField']);
      
          $dbconn->Execute("UPDATE netjuke_userprefs SET us_email = '".$_REQUEST['emailField']."' WHERE us_email = '".$_REQUEST['prevEmailField']."'");
          $dbconn->Execute("UPDATE netjuke_plists SET us_email = '".$_REQUEST['emailField']."' WHERE us_email = '".$_REQUEST['prevEmailField']."'");
          $dbconn->Execute("UPDATE netjuke_plists_fav SET us_email = '".$_REQUEST['emailField']."' WHERE us_email = '".$_REQUEST['prevEmailField']."'");
          $dbconn->Execute("UPDATE netjuke_plists_tracks SET us_email = '".$_REQUEST['emailField']."' WHERE us_email = '".$_REQUEST['prevEmailField']."'");
          
          netjuke_session('update');
        
        }
          
      } else {
        
        javascript ("alert ('".COMMON_ACCESS_DENIED_2."');");
        
      }
      
      $dbrs2->Close();
      
    } else {
    
      javascript ("alert ('".ACCNT_DENIED."');");
    
    }
    
    $dbrs1a->Close();
    $dbrs1b->Close();

  }
  
  $section = "account";
  include (INTERFACE_HEADER);

?>

          <script language='Javascript'>
            function checkForm () {
              var msg = '';
              if (document.registerForm.nameField.value == '') {
              msg = msg + '- <?php echo  ACCNT_CHECKFORM_1 ?>\n';  
              }
              var emailPat=/^(.+)@(.+)\.(.+)$/;
              var matchArray=document.registerForm.emailField.value.match(emailPat);
              if (document.registerForm.emailField.value == '') {
                msg = msg + '- <?php echo  ACCNT_CHECKFORM_2 ?>\n';  
              } else if (matchArray == null) {
                msg = msg + '- <?php echo  ACCNT_CHECKFORM_3 ?>\n'; 
              }
              if (document.registerForm.passwordField.value == '') {
              msg = msg + '- <?php echo  ACCNT_CHECKFORM_9 ?>\n';  
              }
              if ( (document.registerForm.newPasswordField.value.length > 0) && (document.registerForm.newPasswordField.value.length < 6) ) {
                msg = msg + '- <?php echo  ACCNT_CHECKFORM_6 ?>\n';  
              } else if (document.registerForm.newPasswordField.value.length > 20) {
                msg = msg + '- <?php echo  ACCNT_CHECKFORM_7 ?>\n';  
              } else if (document.registerForm.newPasswordField2.value != document.registerForm.newPasswordField.value) {
                msg = msg + '- <?php echo  ACCNT_CHECKFORM_8 ?>\n';  
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
          <form action='account.php' method=post name='registerForm' onSubmit='return checkForm ();'>
          <input type=hidden name='do' value='edit'>
          <input type=hidden name='prevEmailField' value='<?php echo $NETJUKE_SESSION_VARS["email"]?>'>
          <input type=hidden name='prevNicknameField' value='<?php echo $NETJUKE_SESSION_VARS["nickname"]?>'>
          <tr>
            <td class='header' nowrap><B><?php echo  ACCNT_EDIT_HEADER ?></B></td>
          </tr>
          <tr>
            <td class='content' nowrap align=center>
              <table border=0 cellspacing=0 cellpadding=2>
              <tr>
                <td align=left nowrap colspan=2><?php echo  ACCNT_EDIT_CAPTION ?><br><br></td>
              </tr>
              <tr>
                <td align=right nowrap><?php echo  ACCNT_FORMS_NAME ?></td>
                <td align=left nowrap><input type=text name='nameField' size='20' maxlength='50' value="<?php echo $NETJUKE_SESSION_VARS["name"]?>" class=input_content></td>
              </tr>
              <tr>
                <td align=right nowrap><?php echo  ACCNT_FORMS_NICKNAME ?></td>
                <td align=left nowrap><input type=text name='nicknameField' size='20' maxlength='50' value="<?php echo $NETJUKE_SESSION_VARS["nickname"]?>" class=input_content></td>
              </tr>
              <tr>
                <td align=right nowrap><?php echo  ACCNT_FORMS_EMAIL ?></td>
                <td align=left nowrap><input type=text name='emailField' size='20' maxlength='75' value="<?php echo $NETJUKE_SESSION_VARS["email"]?>" class=input_content></td>
              </tr>
              <tr>
                <td align=right nowrap><?php echo  ACCNT_FORMS_PASSWD_3 ?></td>
                <td align=left nowrap><input type=password name='passwordField' size='20' maxlength='20' value='' class=input_content></td>
              </tr>
              <tr>
                <td align=right nowrap><?php echo  ACCNT_FORMS_PASSWD_4 ?></td>
                <td align=left nowrap><input type=password name='newPasswordField' size='20' maxlength='20' value='' class=input_content></td>
              </tr>
              <tr>
                <td align=right nowrap><?php echo  ACCNT_FORMS_PASSWD_5 ?></td>
                <td align=left nowrap><input type=password name='newPasswordField2' size='20' maxlength='20' value='' class=input_content></td>
              </tr>
              <tr>
                <td align=center nowrap colspan=2>
                  <input type=submit value='<?php echo  ACCNT_FORMS_BTN_SAVE ?>' class='btn_content'>
                  <input type=reset value='<?php echo  ACCNT_FORMS_BTN_RESET ?>' class='btn_content'>
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

?>
