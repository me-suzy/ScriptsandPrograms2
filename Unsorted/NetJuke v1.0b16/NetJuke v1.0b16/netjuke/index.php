<?php

// defines if this script requires to be logged in
define( "PRIVATE", false );

# CALL COMMON LIBRARIES

require_once('./lib/inc-common.php');
require_once(FS_PATH."/etc/locale/".LANG_PACK."/inc-index.php");

# HTML HEADER

  $section = "browse";
  include (INTERFACE_HEADER);

# GET TABLE STATS

  $ge_cnt = getCount('ge', ' where track_cnt > 0 ');


# GENRE TABLE

  $ge_html = '';

  $dbrs = $dbconn->Execute(  " SELECT id, name, img_src, track_cnt "
                           . " from netjuke_genres "
                           . " where track_cnt > 0 "
                           . " order by upper(name) asc " );

  $cnt = $row_cnt = 0;

  while (!$dbrs->EOF) {
    
    if ($cnt % 3 == 0) {
      $ge_html .= "<TR>\n";
      $row_cnt++;
    }
    
    $ge_html .= "<td class='content' width='33%'>";

    $ge_html .= "<a href='./play.php?do=play_all&type=ge&id=".$dbrs->fields[0]."' title=\"".BRWS_PLAY_GE_HELP."\"><img alt='".BRWS_PLAY_GE_HELP."' src='".$ICONS['play']."' border=0 width=8 height=8 align=absmiddle hspace=0 vspace=0></a>"
             .  " <A HREF='search.php?do=list.tracks&col=ge_id&val=".$dbrs->fields[0]."&sort=ge'"
             .  "   title=\"".BRWS_GE_HELP."\">"
             .  format_for_display($dbrs->fields[1])."</A>"
             .  " (".$dbrs->fields[3].")";
    
    $ge_html .= "</td>\n";
    
    if (($cnt + 1) % 3 == 0) $ge_html .= "</TR>\n";
    
    $dbrs->MoveNext();
    
    $cnt++;
  
  }

  $dbrs->Close();

  if ( ($cnt + 2) % 3 == 0 ) {
    
    $ge_html .= "<td class='content' nowrap>&nbsp;</td>\n";
    $ge_html .= "<td class='content' nowrap>&nbsp;</td>\n";
    $ge_html .= "</tr>";
  
  } elseif ( ($cnt + 1) % 3 == 0 ) {
    
    $ge_html .= "<td class='content' nowrap>&nbsp;</td>\n";
    $ge_html .= "</tr>\n";
  
  }
  
  $row_cnt = $row_cnt;
  if (abs($row_cnt) < 5) $row_cnt = 5;


# ARTISTS TABLE

  $ar_html = '';
  
  $dbrs = $dbconn->SelectLimit(  " SELECT id, name, img_src, track_cnt "
                               . " from netjuke_artists "
                               . " where track_cnt > 0 "
                               . " order by id desc "
                               , $row_cnt );

  while (!$dbrs->EOF) {
    
    $ar_html .= "<TR><td class='content'>";

    $ar_html .= "<a href='./play.php?do=play_all&type=ar&id=".$dbrs->fields[0]."' title=\"".BRWS_PLAY_AR_HELP."\"><img alt='".BRWS_PLAY_AR_HELP."' src='".$ICONS['play']."' border=0 width=8 height=8 align=absmiddle hspace=0 vspace=0></a>"
             .  " <a href='./filter.php?do=list.albums&search_do=list.tracks&col=ar_id&val=".$dbrs->fields[0]."' TARGET='NetjukeRemote' onClick=\"window.open('','NetjukeRemote','width=500,height=575,top=0,left=0,menubar=no,scrollbars=yes,resizable=yes');\" title=\"".BRWS_FILTER_AR_HELP."\"><img alt='".BRWS_FILTER_AR_HELP."' src='".$ICONS['info']."' border=0 width=8 height=8 align=absmiddle hspace=0 vspace=0></a>"
             .  " <A HREF='search.php?do=list.tracks&col=ar_id&val=".$dbrs->fields[0]."&sort=ar' title=\"".BRWS_AR_HELP."\">"
             .  format_for_display($dbrs->fields[1])."</A>"
             .  " (".$dbrs->fields[3].")"
             .  "&nbsp;".image_icon($dbrs->fields[2]);

    $ar_html .= "</td></tr>\n";
    
    $dbrs->MoveNext();

  }

  $dbrs->Close();


# ALBUM TABLE

  $al_html = '';
  
  $dbrs = $dbconn->SelectLimit(  " SELECT id, name, img_src, track_cnt "
                               . " from netjuke_albums "
                               . " where track_cnt > 0 "
                               . " order by id desc "
                               , $row_cnt );

  while (!$dbrs->EOF) {
    
    $al_html .= "<TR><td class='content'>";

    $al_html .= "<a href='./play.php?do=play_all&type=al&id=".$dbrs->fields[0]."' title=\"".BRWS_PLAY_AL_HELP."\"><img alt='".BRWS_PLAY_AL_HELP."' src='".$ICONS['play']."' border=0 width=8 height=8 align=absmiddle hspace=0 vspace=0></a>"
             .  " <a href='./filter.php?do=list.artists&search_do=list.tracks&col=al_id&val=".$dbrs->fields[0]."' TARGET='NetjukeRemote' onClick=\"window.open('','NetjukeRemote','width=500,height=575,top=0,left=0,menubar=no,scrollbars=yes,resizable=yes');\" title=\"".BRWS_FILTER_AL_HELP."\"><img alt='".BRWS_FILTER_AL_HELP."' src='".$ICONS['info']."' border=0 width=8 height=8 align=absmiddle hspace=0 vspace=0></a>"
             .  " <A HREF='search.php?do=list.tracks&col=al_id&val=".$dbrs->fields[0]."&sort=al' title=\"".BRWS_AL_HELP."\">"
             .  format_for_display($dbrs->fields[1])."</A>"
             .  " (".$dbrs->fields[3].")"
             .  "&nbsp;".image_icon($dbrs->fields[2]);

    $al_html .= "</td></tr>\n";
    
    $dbrs->MoveNext();

  }

  $dbrs->Close();
  
  // print the common summary header
  SummaryHeader();

?>


<table border="0" width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td width="48%" align="left" valign="top">

         <table width='100%' border=0 cellspacing=1 cellpadding=3 class="border">
         <tr>
           <td class="header" colspan=3 nowrap><B><?php echo  BRWS_GE_HEADER ?></B> (<?php echo  $ge_cnt ?>)</td>
         </tr>

         <?php echo  $ge_html ?>

         </table>

		</td>
		<td width="26%" align="right" valign="top">

         <table width='90%' border=0 cellspacing=1 cellpadding=3 class="border">
           <tr>
             <td class="header" nowrap>
               <B><?php echo  BRWS_AR_HEADER ?></B>
             </td>
           </tr>

           <?php echo  $ar_html ?>

         </table>
		
		</td>
		<td width="26%" align="right" valign="top">

         <table width='90%' border=0 cellspacing=1 cellpadding=3 class="border">
           <tr>
             <td class="header" nowrap>
               <B><?php echo  BRWS_AL_HEADER ?></B>
             </td>
           </tr>

           <?php echo  $al_html ?>

         </table>
		
		</td>
	</tr>
</table>

<?php 

  # HTML footer 
  include (INTERFACE_FOOTER);

?>
