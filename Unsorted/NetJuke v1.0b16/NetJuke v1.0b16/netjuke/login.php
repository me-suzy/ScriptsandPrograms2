<?php

// defines if this script requires to be logged in
define( "PRIVATE", false );

  require_once('./lib/inc-common.php');
  require_once(FS_PATH."/etc/locale/".LANG_PACK."/inc-login.php");
  
  $onload = "document.loginForm.netjuke_login.focus();";
  
  $section = "login";
  include (INTERFACE_HEADER);
  
  unset($onload);

?>

        <script language='Javascript'>
            function checkForm () {
              var msg = '';
              var emailPat=/^(.+)@(.+)\.(.+)$/;
              var matchArray=document.loginForm.netjuke_login.value.match(emailPat);
              if (document.loginForm.netjuke_login.value == '') {
                msg = msg + '- <?php echo  LOGIN_CHECKFORM_1 ?>\n';  
              } else if (matchArray == null) {
                msg = msg + '- <?php echo  LOGIN_CHECKFORM_2 ?>\n'; 
              }
              if (document.loginForm.netjuke_password.value == '') {
                msg = msg + '- <?php echo  LOGIN_CHECKFORM_3 ?>\n';  
              }
              if (msg == '') {
                // alert('true');
                return (true);
              } else {
                alert(msg);
                return (false);
              }
            }
          </script>
        
        <div align=center>
        <table width='350' border=0 cellspacing=1 cellpadding=3 class='border'>
        <form action='login.php' method=post name='loginForm' onSubmit="return checkForm();">
        <tr>
          <td class="header" nowrap><B><?php echo  LOGIN_HEADER ?></B></td>
        </tr>
        <tr>
          <td class="content" nowrap align=center>
            <table border=0 cellspacing=0 cellpadding=2>
            <tr>
              <td align=right nowrap><?php echo  LOGIN_EMAIL ?></td>
              <td align=left nowrap><input type=text name='netjuke_login' size='25' maxlength='75' value='' class=input_content tabindex=1></td>
            </tr>
            <tr>
              <td align=right nowrap><?php echo  LOGIN_PASSWD ?></td>
              <td align=left nowrap><input type=password name='netjuke_password' size='25' maxlength='20' value='' class=input_content tabindex=2></td>
            </tr>
            <tr>
              <td align=center nowrap colspan=2>
                <input type=submit value='<?php echo  LOGIN_BTN_1 ?>' class='btn_content' tabindex=3>
                <?php if (abs(substr(SECURITY_MODE,2,1)) < 1) { ?>
                <input type=button value='<?php echo  LOGIN_BTN_2 ?>' class='btn_content' onclick="top.location.href='account.php?do=new';" tabindex=4>
                <?php } ?>
              </td>
            </tr>
            </table>
          </td>
        </tr>
        </form>
        </table>

         <BR>
         </div>

<?php

  include (INTERFACE_FOOTER);

  exit;

?>
