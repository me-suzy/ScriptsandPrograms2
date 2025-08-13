<?php

// defines if this script requires to be logged in
define( "PRIVATE", true );

# Call common libraries
require_once('./lib/inc-common.php');
require_once(FS_PATH."/etc/locale/".LANG_PACK."/inc-pl-list.php");

if ((!isset($_REQUEST['do'])) || ($_REQUEST['do'] == "list")) {
########################################
# LIST ALL PLAYLIST
########################################
#  Usage:
#  - List: pl-list.php?do=list
########################################

  $prvt_html;
  
  if (ENABLE_COMMUNITY != 't') {
  
    $dbrs = $dbconn->Execute( " SELECT id, title, comment, shared_list, sequence ".
                              " from netjuke_plists ".
                              " where us_email = '".$NETJUKE_SESSION_VARS["email"]."' ".
                              " order by sequence asc " );
  
  } else {
    
    $dbrs = $dbconn->Execute( " SELECT id, title, comment, shared_list, sequence ".
                              " from netjuke_plists ".
                              " where us_email = '".$NETJUKE_SESSION_VARS["email"]."' ".
                              " and shared_list = 'f' ".
                              " order by sequence asc " );
  
  }
  
  $cnt = 0;
  
  while (!$dbrs->EOF) {
    $cnt++;
    $field = $dbrs->fields;
    if ($field[2] != '') $field[2] = "(" . $field[2] . ")";
    $prvt_html .= "<TR>"
               .  "<td width='1%' valign=top class='content' nowrap>$cnt "
               .  "<a href=\"".WEB_PATH."/play.php?do=plist&val=".$dbrs->fields[0]."\" title=\"".PLIST_PLAY_HELP."\"><img src='".$ICONS['play']."' alt='".PLIST_PLAY_HELP."' width='8' height='8' hspace='0' vspace='0' border='0' align='absmiddle'></a> "
               .  "<a href=\"".WEB_PATH."/pl-edit.php?do=edit&pl_id=".$dbrs->fields[0]."\" title=\"".PLIST_EDIT_HELP."\"><img src='".$ICONS['info']."' alt='".PLIST_EDIT_HELP."' width='8' height='8' hspace='0' vspace='0' border='0' align='middle'></a> "
               .  "</td>"
               .  "<td width='99%' valign=top class='content' ><A HREF='pl-edit.php?do=edit&pl_id=$field[0]' title=\"".PLIST_EDIT_HELP."\">$field[1]</a> &nbsp; $field[2]</td>"
               .  "</TR>";
    $dbrs->MoveNext();
  }
  
  $dbrs->Close();

  # make sure the community feature is enabled
  if (ENABLE_COMMUNITY == 't') {

    $shrd_html;
    
    $dbrs = $dbconn->Execute( " SELECT id, title, comment, shared_list, sequence ".
                              " from netjuke_plists ".
                              " where us_email = '".$NETJUKE_SESSION_VARS["email"]."' ".
                              " and shared_list = 't' ".
                              " order by sequence asc " );
  
    $cnt = 0;
    
    while (!$dbrs->EOF) {
      $cnt++;
      $field = $dbrs->fields;
      if ($field[2] != '') $field[2] = "(" . $field[2] . ")";
      $shrd_html .= "<TR>"
                 .  "<td width='1%' valign=top class='content' nowrap>$cnt "
                 .  "<a href=\"".WEB_PATH."/play.php?do=plist&val=".$dbrs->fields[0]."\" title=\"".PLIST_PLAY_HELP."\"><img src='".$ICONS['play']."' alt='".PLIST_PLAY_HELP."' width='8' height='8' hspace='0' vspace='0' border='0' align='absmiddle'></a> "
                 .  "<a href=\"".WEB_PATH."/pl-edit.php?do=edit&pl_id=".$dbrs->fields[0]."\" title=\"".PLIST_EDIT_HELP."\"><img src='".$ICONS['info']."' alt='".PLIST_EDIT_HELP."' width='8' height='8' hspace='0' vspace='0' border='0' align='middle'></a> "
                 .  "</td>"
                 .  "<td width='99%' valign=top class='content' ><A HREF='pl-edit.php?do=edit&pl_id=$field[0]' title=\"".PLIST_EDIT_HELP."\">$field[1]</a> &nbsp; $field[2]</td>"
                 .  "</TR>";
      $dbrs->MoveNext();
    }
    
    $dbrs->Close();
  
    $fav_html;
    
    $dbrs = $dbconn->Execute( " SELECT pf.pl_id, pl.title, pl.comment, pf.sequence "
                            . " from netjuke_plists_fav pf, netjuke_plists pl "
                            . " where pf.us_email = '".$NETJUKE_SESSION_VARS["email"]."' "
                            . " and pl.id = pf.pl_id "
                            . " and pl.shared_list = 't' "
                            . " order by pf.sequence asc " );
  
    $cnt = 0;
    
    while (!$dbrs->EOF) {
      $cnt++;
      $field = $dbrs->fields;
      if ($field[2] != '') $field[2] = "(" . $field[2] . ")";
      $fav_html .= "<TR>"
                 .  "<td width='1%' valign=top class='content' nowrap>$cnt "
                 .  "<a href=\"".WEB_PATH."/play.php?do=plist&val=".$dbrs->fields[0]."\" title=\"".PLIST_PLAY_HELP."\"><img src='".$ICONS['play']."' alt='".PLIST_PLAY_HELP."' width='8' height='8' hspace='0' vspace='0' border='0' align='absmiddle'></a> "
                 .  "<a href=\"".WEB_PATH."/pl-shrd-view.php?do=view&pl_id=".$dbrs->fields[0]."\" title=\"".PLIST_VIEW_HELP."\"><img src='".$ICONS['info']."' alt='".PLIST_VIEW_HELP."' width='8' height='8' hspace='0' vspace='0' border='0' align='middle'></a> "
                 .  "</td>"
                .  "<td width='99%' valign=top class='content' ><A HREF='pl-shrd-view.php?do=view&pl_id=$field[0]' title=\"".PLIST_VIEW_HELP."\">$field[1]</a> &nbsp; $field[2]</td>"
                .  "</TR>";
      $dbrs->MoveNext();
    }
    
    $dbrs->Close();
  
  }
  
  $section = "playlists";
  include (INTERFACE_HEADER);
  
  // print the common summary header
  SummaryHeader();

  # make sure the community feature is enabled
  if (ENABLE_COMMUNITY != 't') {

?>

  <div align=center>
  <table width='400' border=0 cellspacing=1 cellpadding=3 class="border">
  <tr>
    <td colspan=2 class="header" nowrap>
      <B><?php echo  PLIST_PRVT_HEADER ?></B>
    </td>
  </tr>
  <tr>
    <td colspan=2 class="content">
      <?php echo  PLIST_PRVT_HELP ?>
    </td>
  </tr>

  <?php echo  $prvt_html ?>

  </table>
  </div>

<?php

  } else {

?>

    <table width='100%' border=0 cellspacing=0 cellpadding=0>
      <tr>
        <td width="33%" align=left valign='top' nowrap>

  <table width='95%' border=0 cellspacing=1 cellpadding=3 class="border">
  <tr>
    <td colspan=2 class="header" nowrap>
      <B><?php echo  PLIST_PRVT_HEADER ?></B>
    </td>
  </tr>
  <tr>
    <td colspan=2 class="content">
      <?php echo  PLIST_PRVT_HELP ?>
    </td>
  </tr>

  <?php echo  $prvt_html ?>

  </table>

      </td>
      <td width="34%" align=center valign='top'>


  <table width='95%' border=0 cellspacing=1 cellpadding=3 class="border">
  <tr>
    <td colspan=2 class="header" nowrap>
      <B><?php echo  PLIST_SHRD_HEADER ?></B>
    </td>
  </tr>
  <tr>
    <td colspan=2 class="content">
      <?php echo  PLIST_SHRD_HELP ?>
    </td>
  </tr>

  <?php echo  $shrd_html ?>

  </table>


      </td>
      <td width="33%" align=right valign='top'>


  <table width='95%' border=0 cellspacing=1 cellpadding=3 class="border">
  <tr>
    <td colspan=2 class="header" nowrap>
      <B><?php echo  PLIST_FAV_HEADER ?></B>
    </td>
  </tr>
  <tr>
    <td colspan=2 class="content">
      <?php echo  PLIST_FAV_HELP ?>
    </td>
  </tr>

  <?php echo  $fav_html ?>

  </table>


      </td>
    </tr>
  </table>

<?php
  
  }
  
  include (INTERFACE_FOOTER);

  exit;

}

?>

