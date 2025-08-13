<?php

// defines if this script requires to be logged in
define( "PRIVATE", true );

# Call common libraries
require_once('./lib/inc-common.php');
require_once(FS_PATH."/etc/locale/".LANG_PACK."/inc-prefs.php");

if (USER_THEMES == 'f') {
  header ("Location: ".WEB_PATH."/index.php\n\n");
  exit;
}

if (!$_REQUEST['do']) {

  header ("Location: ".WEB_PATH."/index.php\n\n");

  exit;

}  elseif ($_REQUEST['do'] == "edit") {
  
  if ($_REQUEST['save'] != '') {
  
    if ($_REQUEST['inv_icnField'] == '') $_REQUEST['inv_icnField'] = 'f';

    $sql = " UPDATE netjuke_userprefs SET"
          ."   bgcolor = '".$_REQUEST['bgcolorField']."' "
          ." , text = '".$_REQUEST['textField']."' "
          ." , link = '".$_REQUEST['linkField']."' "
          ." , alink = '".$_REQUEST['alinkField']."' "
          ." , vlink = '".$_REQUEST['vlinkField']."' "
          ." , td_border = '".$_REQUEST['td_borderField']."' "
          ." , td_header = '".$_REQUEST['td_headerField']."' "
          ." , td_header_fc = '".$_REQUEST['td_header_fcField']."' "
          ." , td_content = '".$_REQUEST['td_contentField']."' "
          ." , font_face = '".$_REQUEST['font_faceField']."' "
          ." , font_size = '".$_REQUEST['font_sizeField']."' "
          ." , inv_icn = '".$_REQUEST['inv_icnField']."' "
          ." WHERE us_email = '".$NETJUKE_SESSION_VARS["email"]."' ";
  
    if ($dbconn->Execute($sql) === false) {
    
      javascript ("alert('".PREFS_DENIED_1."');");
      
    } else {
    
      $NETJUKE_SESSION_VARS["inv_icn"] = $_REQUEST['inv_icnField'];
      
      $NETJUKE_SESSION_VARS["bgcolor"] = $_REQUEST['bgcolorField'];
      $NETJUKE_SESSION_VARS["text"] = $_REQUEST['textField'];
      $NETJUKE_SESSION_VARS["link"] = $_REQUEST['linkField'];
      $NETJUKE_SESSION_VARS["alink"] = $_REQUEST['alinkField'];
      $NETJUKE_SESSION_VARS["vlink"] = $_REQUEST['vlinkField'];
      $NETJUKE_SESSION_VARS["td_border"] = $_REQUEST['td_borderField'];
      $NETJUKE_SESSION_VARS["td_header"] = $_REQUEST['td_headerField'];
      $NETJUKE_SESSION_VARS["td_header_fc"] = $_REQUEST['td_header_fcField'];
      $NETJUKE_SESSION_VARS["td_content"] = $_REQUEST['td_contentField'];
      $NETJUKE_SESSION_VARS["font_face"] = $_REQUEST['font_faceField'];
      $NETJUKE_SESSION_VARS["font_size"] = $_REQUEST['font_sizeField'];
      
      netjuke_session('update');
    
    }

  }

  if (strtolower(substr($NETJUKE_SESSION_VARS["inv_icn"],0,1)) == 't') $inv_icnChecked = 'CHECKED';
  
  $section = "prefs";
  include (INTERFACE_HEADER);

?>

          <script language='Javascript'>
            function checkForm () {
              var msg = '';
              if (document.prefsForm.bgcolorField.value.length < 6) {
              msg = msg + '- <?php echo  PREFS_CHECKFORM_1 ?>\n';  
              }
              if (document.prefsForm.font_faceField.value.length == 0) {
              msg = msg + '- <?php echo  PREFS_CHECKFORM_2 ?>\n';  
              }
              if (document.prefsForm.font_sizeField.value.length < 1) {
              msg = msg + '- <?php echo  PREFS_CHECKFORM_3 ?>\n';  
              }
              if (document.prefsForm.textField.value.length < 6) {
              msg = msg + '- <?php echo  PREFS_CHECKFORM_4 ?>\n';  
              }
              if (document.prefsForm.linkField.value.length < 6) {
              msg = msg + '- <?php echo  PREFS_CHECKFORM_5 ?>\n';  
              }
              if (document.prefsForm.alinkField.value.length < 6) {
              msg = msg + '- <?php echo  PREFS_CHECKFORM_6 ?>\n';  
              }
              if (document.prefsForm.vlinkField.value.length < 6) {
              msg = msg + '- <?php echo  PREFS_CHECKFORM_7 ?>\n';  
              }
              if (document.prefsForm.td_borderField.value.length < 6) {
              msg = msg + '- <?php echo  PREFS_CHECKFORM_8 ?>\n';  
              }
              if (document.prefsForm.td_headerField.value.length < 6) {
              msg = msg + '- <?php echo  PREFS_CHECKFORM_9 ?>\n';  
              }
              if (document.prefsForm.td_header_fcField.value.length < 6) {
              msg = msg + '- <?php echo  PREFS_CHECKFORM_10 ?>\n';  
              }
              if (document.prefsForm.td_contentField.value.length < 6) {
              msg = msg + '- <?php echo  PREFS_CHECKFORM_11 ?>\n';  
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
          <table width='400' border=0 cellspacing=1 cellpadding=3 class='border'>
          <form action='prefs.php' method=post name='prefsForm' onSubmit='return checkForm ();'>
          <input type=hidden name='do' value='edit'>
          <input type=hidden name='save' value='1'>
          <tr>
            <td class='header' nowrap><B><?php echo  PREFS_HEADER ?></B></td>
          </tr>
          <tr>
            <td class='content' nowrap align=center>
              <table border=0 cellspacing=0 cellpadding=2>
              <tr>
                <td align=center wrap="virtual" colspan=3>
                  <?php echo  PREFS_CAPTION ?>
                  <BR>
                  <A HREF="<?php echo  WEB_PATH ?>/palette.php" target="NetJukePalette" onClick="window.open('','NetJukePalette','width=640,height=575,top=0,left=0,menubar=no,scrollbars=yes,resizable=yes');"><B><?php echo  PREFS_PALETTE ?></B></A>
                  <BR><BR>
                </td>
              </tr>
              <tr>
                <td width="40%" align=right valign=top nowrap><?php echo  PREFS_FORMS_INVICN ?></td>
                <td width="60%" align=left valign=top colspan=2>
                	<input type=checkbox name='inv_icnField' value='t' <?php echo $inv_icnChecked?> title="<?php echo  PREFS_FORMS_INVICN_HELP ?>"> <?php echo  PREFS_FORMS_INVICN_ENABLED ?>
                </td>
              </tr>
              <tr>
                <td align=right nowrap><?php echo  PREFS_FORMS_FONTFACE ?></td>
                <td align=left nowrap colspan=2><input type=text name='font_faceField' size='25' maxlength='80' value='<?php echo $NETJUKE_SESSION_VARS["font_face"]?>' class=input_content></td>
              </tr>
              <tr>
                <td align=right nowrap><?php echo  PREFS_FORMS_FONTSIZE ?></td>
                <td align=left nowrap colspan=2><input type=text name='font_sizeField' size='2' maxlength='2' value='<?php echo $NETJUKE_SESSION_VARS["font_size"]?>' class=input_content></td>
              </tr>
              <tr>
                <td align=right nowrap><?php echo  PREFS_FORMS_BGCOLOR ?></td>
                <td align=left nowrap><input type=text name='bgcolorField' size='6' maxlength='6' value='<?php echo $NETJUKE_SESSION_VARS["bgcolor"]?>' class=input_content></td>
                <td><TABLE BORDER="1" WIDTH="20" CELLSPACING="0" CELLPADDING="0"><TR><TD BGCOLOR='#<?php echo $NETJUKE_SESSION_VARS["bgcolor"]?>'>&nbsp;</TD></TR></TABLE></td>
              </tr>
              <tr>
                <td align=right nowrap><?php echo  PREFS_FORMS_TEXT ?></td>
                <td align=left nowrap><input type=text name='textField' size='6' maxlength='6' value='<?php echo $NETJUKE_SESSION_VARS["text"]?>' class=input_content></td>
                <td><TABLE BORDER="1" WIDTH="20" CELLSPACING="0" CELLPADDING="0"><TR><TD BGCOLOR='#<?php echo $NETJUKE_SESSION_VARS["text"]?>'>&nbsp;</TD></TR></TABLE></td>
              </tr>
              <tr>
                <td align=right nowrap><?php echo  PREFS_FORMS_LINK ?></td>
                <td align=left nowrap><input type=text name='linkField' size='6' maxlength='6' value='<?php echo $NETJUKE_SESSION_VARS["link"]?>' class=input_content></td>
                <td><TABLE BORDER="1" WIDTH="20" CELLSPACING="0" CELLPADDING="0"><TR><TD BGCOLOR='#<?php echo $NETJUKE_SESSION_VARS["link"]?>'>&nbsp;</TD></TR></TABLE></td>
              </tr>
              <tr>
                <td align=right nowrap><?php echo  PREFS_FORMS_ALINK ?></td>
                <td align=left nowrap><input type=text name='alinkField' size='6' maxlength='6' value='<?php echo $NETJUKE_SESSION_VARS["alink"]?>' class=input_content></td>
                <td><TABLE BORDER="1" WIDTH="20" CELLSPACING="0" CELLPADDING="0"><TR><TD BGCOLOR='#<?php echo $NETJUKE_SESSION_VARS["alink"]?>'>&nbsp;</TD></TR></TABLE></td>
              </tr>
              <tr>
                <td align=right nowrap><?php echo  PREFS_FORMS_VLINK ?></td>
                <td align=left nowrap><input type=text name='vlinkField' size='6' maxlength='6' value='<?php echo $NETJUKE_SESSION_VARS["vlink"]?>' class=input_content></td>
                <td><TABLE BORDER="1" WIDTH="20" CELLSPACING="0" CELLPADDING="0"><TR><TD BGCOLOR='#<?php echo $NETJUKE_SESSION_VARS["vlink"]?>'>&nbsp;</TD></TR></TABLE></td>
              </tr>
              <tr>
                <td align=right nowrap><?php echo  PREFS_FORMS_BORDER ?></td>
                <td align=left nowrap><input type=text name='td_borderField' size='6' maxlength='6' value='<?php echo $NETJUKE_SESSION_VARS["td_border"]?>' class=input_content></td>
                <td><TABLE BORDER="1" WIDTH="20" CELLSPACING="0" CELLPADDING="0"><TR><TD BGCOLOR='#<?php echo $NETJUKE_SESSION_VARS["td_border"]?>'>&nbsp;</TD></TR></TABLE></td>
              </tr>
              <tr>
                <td align=right nowrap><?php echo  PREFS_FORMS_HEADER ?></td>
                <td align=left nowrap><input type=text name='td_headerField' size='6' maxlength='6' value='<?php echo $NETJUKE_SESSION_VARS["td_header"]?>' class=input_content></td>
                <td><TABLE BORDER="1" WIDTH="20" CELLSPACING="0" CELLPADDING="0"><TR><TD BGCOLOR='#<?php echo $NETJUKE_SESSION_VARS["td_header"]?>'>&nbsp;</TD></TR></TABLE></td>
              </tr>
              <tr>
                <td align=right nowrap><?php echo  PREFS_FORMS_HEADERFC ?></td>
                <td align=left nowrap><input type=text name='td_header_fcField' size='6' maxlength='6' value='<?php echo $NETJUKE_SESSION_VARS["td_header_fc"]?>' class=input_content></td>
                <td><TABLE BORDER="1" WIDTH="20" CELLSPACING="0" CELLPADDING="0"><TR><TD BGCOLOR='#<?php echo $NETJUKE_SESSION_VARS["td_header_fc"]?>'>&nbsp;</TD></TR></TABLE></td>
              </tr>
              <tr>
                <td align=right nowrap><?php echo  PREFS_FORMS_CONTENT ?></td>
                <td align=left nowrap><input type=text name='td_contentField' size='6' maxlength='6' value='<?php echo $NETJUKE_SESSION_VARS["td_content"]?>' class=input_content></td>
                <td><TABLE BORDER="1" WIDTH="20" CELLSPACING="0" CELLPADDING="0"><TR><TD BGCOLOR='<?php echo $NETJUKE_SESSION_VARS["td_content"]?>'>&nbsp;</TD></TR></TABLE></td>
              </tr>
              <tr>
                <td align=center nowrap colspan=3>
                  <input type=submit value='<?php echo  PREFS_FORMS_BTN_SAVE ?>' class='btn_content'>
                  <input type=reset value='<?php echo  PREFS_FORMS_BTN_RESET ?>' class='btn_content'>
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

  header ("Location: ".WEB_PATH."/index.php\n\n");

  exit;

}

?>
