<?php

// defines if this script requires to be logged in
define( "PRIVATE", true );

########################################

require_once('../lib/inc-admin.php');
require_once(FS_PATH."/etc/locale/".LANG_PACK."/inc-admin_user-list.php");

########################################

if ((!$_REQUEST['do']) || ($_REQUEST['do'] == "list")) {
########################################
# LIST ALL USERS
########################################
#  Usage:
#  - List: user-list.php?do=list
########################################

  $pub_html;
  
  $dbrs = $dbconn->Execute( " SELECT email, name, nickname, created, login_cnt ".
                            " from netjuke_users ".
                            " where gr_id = 3 ".
                            " order by upper(name) asc, upper(email) asc " );

  $cnt = 0;
  
  while (!$dbrs->EOF) {
    $cnt++;
    $field = $dbrs->fields;
    if ($field[2] != '') $field[2] = "(" . $field[2] . ")";
    $pub_html .= "<TR>"
               .  "<td width='1%' valign=top class='content' >$cnt</td>"
               .  "<td width='99%' valign=top class='content' >"
               .  "<A HREF='user-edit.php?do=edit&email=".obfuscate_apply($field[0])."' title=\"".USLIST_EDIT_HELP."\">$field[1] $field[2]</a>"
               .  " ($field[4])"
               .  "<br>$field[0]"
               .  "<br>".date("Y-m-d H:i:s", strtotime($field[3]))."</td>"
               .  "</TR>";
    $dbrs->MoveNext();
  }
  
  $dbrs->Close();

  $edi_html;
  
  $dbrs = $dbconn->Execute( " SELECT email, name, nickname, created, login_cnt ".
                            " from netjuke_users ".
                            " where gr_id = 2 ".
                            " order by upper(name) asc, upper(email) asc " );

  $cnt = 0;
  
  while (!$dbrs->EOF) {
    $cnt++;
    $field = $dbrs->fields;
    if ($field[2] != '') $field[2] = "(" . $field[2] . ")";
    $edi_html .= "<TR>"
               .  "<td width='1%' valign=top class='content' >$cnt</td>"
               .  "<td width='99%' valign=top class='content' >"
               .  "<A HREF='user-edit.php?do=edit&email=".obfuscate_apply($field[0])."' title=\"".USLIST_EDIT_HELP."\">$field[1] $field[2]</a>"
               .  " ($field[4])"
               .  "<br>$field[0]"
               .  "<br>".date("Y-m-d H:i:s", strtotime($field[3]))."</td>"
               .  "</TR>";
    $dbrs->MoveNext();
  }
  
  $dbrs->Close();

  $adm_html;
  
  $dbrs = $dbconn->Execute( " SELECT email, name, nickname, created, login_cnt ".
                            " from netjuke_users ".
                            " where gr_id = 1 ".
                            " order by upper(name) asc, upper(email) asc " );

  $cnt = 0;
  
  while (!$dbrs->EOF) {
    $cnt++;
    $field = $dbrs->fields;
    if ($field[2] != '') $field[2] = "(" . $field[2] . ")";
    $adm_html .= "<TR>"
               .  "<td width='1%' valign=top class='content' >$cnt</td>"
               .  "<td width='99%' valign=top class='content' >"
               .  "<A HREF='user-edit.php?do=edit&email=".obfuscate_apply($field[0])."' title=\"".USLIST_EDIT_HELP."\">$field[1] $field[2]</a>"
               .  " ($field[4])"
               .  "<br>$field[0]"
               .  "<br>".date("Y-m-d H:i:s", strtotime($field[3]))."</td>"
               .  "</TR>";
    $dbrs->MoveNext();
  }
  
  $dbrs->Close();

  $section = "sysadmin";
  include (INTERFACE_HEADER);

?>


    <table width='100%' border=0 cellspacing=0 cellpadding=0>
      <tr>
        <td width="33%" align=left valign='top' nowrap>

  <table width='95%' border=0 cellspacing=1 cellpadding=3 class="border">
  <tr>
    <td colspan=2 class="header" nowrap>
      <table width='100%' border=0 cellspacing=0 cellpadding=0>
      <form>
        <tr class="header">
          <td align=left valign=middle nowrap>
            <B><?php echo  USLIST_US_HEADER ?></B>
          </td>
          <td align=right valign=middle nowrap>
            <input type=button value="<?php echo  USLIST_BTN_NEW ?>" onClick="self.location.href='user-edit.php?do=new&selected=3';" class='btn_header'>
          </td>
        </tr>
      </form>
      </table>
    </td>
  </tr>
  <tr>
    <td colspan=2 class="content">
      <?php echo  USLIST_US_HELP ?>
    </td>
  </tr>

  <?php echo  $pub_html ?>

  </table>

      </td>
      <td width="34%" align=center valign='top'>


  <table width='95%' border=0 cellspacing=1 cellpadding=3 class="border">
  <tr>
    <td colspan=2 class="header" nowrap>
      <table width='100%' border=0 cellspacing=0 cellpadding=0>
      <form>
        <tr class="header">
          <td align=left valign=middle nowrap>
            <B><?php echo  USLIST_ED_HEADER ?></B>
          </td>
          <td align=right valign=middle nowrap>
            <input type=button value="<?php echo  USLIST_BTN_NEW ?>" onClick="self.location.href='user-edit.php?do=new&selected=2';" class='btn_header'>
          </td>
        </tr>
      </form>
      </table>
    </td>
  </tr>
  <tr>
    <td colspan=2 class="content">
      <?php echo  USLIST_ED_HELP ?>
    </td>
  </tr>

  <?php echo  $edi_html ?>

  </table>


      </td>
      <td width="33%" align=right valign='top'>


  <table width='95%' border=0 cellspacing=1 cellpadding=3 class="border">
  <tr>
    <td colspan=2 class="header" nowrap>
      <table width='100%' border=0 cellspacing=0 cellpadding=0>
      <form>
        <tr class="header">
          <td align=left valign=middle nowrap>
            <B><?php echo  USLIST_AD_HEADER ?></B>
          </td>
          <td align=right valign=middle nowrap>
            <input type=button value="<?php echo  USLIST_BTN_NEW ?>" onClick="self.location.href='user-edit.php?do=new&selected=1';" class='btn_header'>
          </td>
        </tr>
      </form>
      </table>
    </td>
  </tr>
  <tr>
    <td colspan=2 class="content">
       <?php echo  USLIST_AD_HELP ?>
    </td>
  </tr>

  <?php echo  $adm_html ?>

  </table>


      </td>
    </tr>
  </table>

<?php

  include (INTERFACE_FOOTER);

  exit;

}

?>

