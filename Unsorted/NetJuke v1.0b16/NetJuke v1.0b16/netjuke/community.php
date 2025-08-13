<?php

// defines if this script requires to be logged in
define( "PRIVATE", false );

# CALL COMMON LIBRARIES

require_once('./lib/inc-common.php');
require_once(FS_PATH."/etc/locale/".LANG_PACK."/inc-community.php");

# make sure the community feature is enabled
if (ENABLE_COMMUNITY != 't') header(WEB_PATH."/index.php");
  

  $row_cnt = 10;


# $row_cnt LATEST SHARED PLAYLISTS

  $shrd_html = '';
  
  $dbrs = $dbconn->SelectLimit(  " SELECT id, us_email, title, comment "
                               . " from netjuke_plists  "
                               . " where shared_list = 't' "
                               . " order by created desc "
                               , $row_cnt );

  while (!$dbrs->EOF) {
    
    $shrd_html .= "<TR><td align=left class='content'>"
               .  "<a href=\"".WEB_PATH."/play.php?do=plist&val=".$dbrs->fields[0]."\" title=\"".CMNT_SHRD_PLAY_HELP."\"><img src='".$ICONS['play']."' alt='".CMNT_SHRD_PLAY_HELP."' width='8' height='8' hspace='0' vspace='0' border='0' align='absmiddle'></a> "
               .  "<a href=\"".WEB_PATH."/pl-shrd-view.php?do=view&pl_id=".$dbrs->fields[0]."&section=community\" title=\"".CMNT_SHRD_VIEW_HELP."\"><img src='".$ICONS['info']."' alt='".CMNT_SHRD_VIEW_HELP."' width='8' height='8' hspace='0' vspace='0' border='0' align='middle'></a> "
               .  "<a href=\"".WEB_PATH."/pl-shrd-view.php?do=view&pl_id=".$dbrs->fields[0]."&section=community\" title=\"".CMNT_SHRD_VIEW_HELP."\">"
               .  $dbrs->fields[2]."</a>"
               .  "<br>".CMNT_SHRD_BY.": ".get_display_name($dbrs->fields[1]);
    
    if ($dbrs->fields[3] != '') $shrd_html .= "<br>" . $dbrs->fields[3];
    
    $shrd_html .= "</td></tr>";
    
    $dbrs->MoveNext();

  }

  $dbrs->Close();


# TOP $row_cnt DOWNLOADS

  $dl_html = '';
  
  $dbrs = $dbconn->SelectLimit(  " SELECT tr.id, tr.name, tr.dl_cnt "
                               . "   , tr.ar_id, ar.name, tr.al_id, al.name "
                               . "   , tr.ge_id, ge.name, tr.location "
                               . " from netjuke_tracks tr, netjuke_artists ar, "
                               . "   netjuke_albums al, netjuke_genres ge "
                               . " where tr.ar_id = ar.id "
                               . "   and tr.al_id = al.id "
                               . "   and tr.ge_id = ge.id "
                               . " order by tr.dl_cnt desc "
                               , $row_cnt );

  $cnt = 0;
  
  while (!$dbrs->EOF) {
    
    if ($cnt != 0) $top_10_plist .= ',';
    $top_10_plist .= $dbrs->fields[0];
    
    $cnt++;
      
    if (ENABLE_DOWNLOAD == 't') {
        
      $tr_location = $dbrs->fields[9];
      if (substr_count($tr_location,"://") < 1) $tr_location = STREAM_SRVR."/".$tr_location;
      if (substr_count($tr_location,"://") < 1) $tr_location  = WEB_PATH."/".$tr_location;
      $dload = "<a href='".$tr_location."' title=\"".CMNT_DL_DLOAD_HELP."\"><img alt='".CMNT_DL_DLOAD_HELP."' src='".$ICONS['dload']."' border=0 width=7 height=8 align=absmiddle hspace=0 vspace=0></a>";
      
    } else {
      
      $dload = "";
      
    }
    
    $dl_html .= "<TR>"
             .  "<td width='1%' align=right valign=top class='content'>"
             .  "$cnt "
             .  "</td>"
             .  "<td width='4%' align=center valign=top class='content' nowrap>"
             .  "<a href=\"".WEB_PATH."/play.php?do=play&val=".$dbrs->fields[0]."\" title=\"".CMNT_DL_PLAY_HELP."\"><img src='".$ICONS['play']."' alt=\"".CMNT_DL_PLAY_HELP."\" width='8' height='8' hspace='0' vspace='3' border='0' align='absmiddle'></a> "
             .  $dload." <a href=\"".WEB_PATH."/tr-info.php?id=".$dbrs->fields[0]."\" target=\"NetJukeGetInfo\" onClick=\"window.open('','NetJukeGetInfo','width=500,height=575,top=0,left=0,menubar=no,scrollbars=yes,resizable=yes');\" title=\"".CMNT_DL_INFO_HELP."\"><img src='".$ICONS['info']."' alt=\"".CMNT_DL_INFO_HELP."\" width='8' height='8' hspace='0' vspace='3' border='0' align='middle'></a> "
             .  "</td>"
             .  "<td width='95%' align=left valign=top class='content'>"
             .  format_for_display($dbrs->fields[1])
             .  " &nbsp;[ <a href='".WEB_PATH."/search.php?do=list.tracks&col=ge.id&val=".$dbrs->fields[7]."&sort=ge' title=\"".CMNT_DL_GENRE_HELP."\">".format_for_display($dbrs->fields[8])."</a> ]"
             .  "<br>".CMNT_DL_ARTIST.": <a href='".WEB_PATH."/search.php?do=list.tracks&col=ar.id&val=".$dbrs->fields[3]."&sort=ar' title=\"".CMNT_DL_ARTIST_HELP."\">".format_for_display($dbrs->fields[4])."</a>"
             .  " - ".CMNT_DL_ALBUM.": <a href='".WEB_PATH."/search.php?do=list.tracks&col=al.id&val=".$dbrs->fields[5]."&sort=al' title=\"".CMNT_DL_ALBUM_HELP."\">".format_for_display($dbrs->fields[6])."</a>"
             .  "</td></tr>";
    
    $dbrs->MoveNext();

  }

  $dbrs->Close();

# HTML HEADER

  $section = "community";
  include (INTERFACE_HEADER);
  
  // print the common summary header
  SummaryHeader();

?>
  
  <table width='100%' border=0 cellspacing=0 cellpadding=0>
      <tr>
       <td width="40%" align='left' valign='top' nowrap>

         <table width='100%' border=0 cellspacing=1 cellpadding=3 class="border">
         <tr>
           <td class="header" nowrap>
             <table width='100%' border=0 cellspacing=0 cellpadding=0>
             <form>
               <tr class="header">
                 <td align=left valign=middle nowrap>
                   <B><?php echo  CMNT_SHRD_HEADER ?></B>
                 </td>
                 <td align=right valign=middle nowrap>
                   <input type=button value="<?php echo  CMNT_SHRD_LIST_BTN ?>" onClick="self.location.href='<?php echo  WEB_PATH.'/pl-shrd-list.php' ?>';" class='btn_header'>
                 </td>
               </tr>
             </form>
             </table>
           </td>
         </tr>

         <?php echo  $shrd_html ?>

         </table>

       </td>
       <td width="60%" align='right' valign='top' nowrap>

         <table width='95%' border=0 cellspacing=1 cellpadding=3 class="border">
         <tr>
           <td colspan=3 class="header" nowrap>
             <table width='100%' border=0 cellspacing=0 cellpadding=0>
             <form>
               <tr class="header">
                 <td align=left valign=middle nowrap>
                   <B><?php echo  CMNT_DL_HEADER ?></B>
                 </td>
                 <td align=right valign=middle nowrap>
                   <input type=button value="<?php echo  CMNT_DL_TOP10_BTN ?>" onClick="self.location.href='<?php echo  WEB_PATH.'/play.php?do=play&val='.$top_10_plist ?>';" class='btn_header'>
                 </td>
               </tr>
             </form>
             </table>
           </td>
         </tr>

         <?php echo  $dl_html ?>

         </table>

       </td>
     </tr>
  </table>

<?php 

  # HTML footer 
  include (INTERFACE_FOOTER);

?>
