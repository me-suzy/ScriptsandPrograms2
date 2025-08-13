<?php

// defines if this script requires to be logged in
define( "PRIVATE", false );

# Call common libraries
require_once('./lib/inc-common.php');
require_once(FS_PATH."/etc/locale/".LANG_PACK."/inc-search.php");

if (!isset($_REQUEST['do'])) {

  header ("Location: index.php\n\n");
  exit;

}  elseif ( (substr($_REQUEST['do'],0,4) == 'list') || (substr($_REQUEST['do'],0,6) == 'search') ) {
########################################
# LIST OR SEARCH TRACKS
########################################

   if (strlen($_REQUEST['col']) < 1) $_REQUEST['col'] = 'tr.id';

   $valurl = rawurlencode($_REQUEST['val']);

   if (substr($_REQUEST['do'],0,5) == "list.") {

      // TRACK LISTING

      if (strlen($_REQUEST['val']) < 1) $_REQUEST['val'] = '1';

      $clause = $_REQUEST['col']." = '".$_REQUEST['val']."'";

   } else {

      if ($_REQUEST['do'] == 'search.adv') {

      // ADVANCED SEARCH
      
         if (!isset($_REQUEST['clause'])) {
         
         // QUERY FROM ADVANCED SEARCH FORM
         
            $clause_array = array();
          
            // LIKE STATEMENTS
            
            if (strlen($_REQUEST['tr_name']) > 0) {
               $clause_array[] = "upper(tr.name) like '%".strtoupper(raw_to_db($_REQUEST['tr_name']))."%'";
            }
          
            if (strlen($_REQUEST['ar_name']) > 0) {
               $clause_array[] = "upper(ar.name) like '%".strtoupper(raw_to_db($_REQUEST['ar_name']))."%'";
            }
            
            if (strlen($_REQUEST['al_name']) > 0) { 
               $clause_array[] = "upper(al.name) like '%".strtoupper(raw_to_db($_REQUEST['al_name']))."%'";
            }
          
            if (strlen($_REQUEST['ge_name']) > 0) { 
               $clause_array[] = "upper(ge.name) like '%".strtoupper(raw_to_db($_REQUEST['ge_name']))."%'";
            }
          
            if (strlen($_REQUEST['tr_location']) > 0) { 
               $clause_array[] = "upper(tr.location) like '%".strtoupper(specialUrlEncode($_REQUEST['tr_location']))."%'";
            }
          
            if (strlen($_REQUEST['tr_comments']) > 0) { 
               $clause_array[] = "upper(tr.comments) like '%".strtoupper($_REQUEST['tr_comments'])."%'";
            }
          
            if (strlen($_REQUEST['tr_lyrics']) > 0) { 
               $clause_array[] = "upper(tr.lyrics) like '%".strtoupper($_REQUEST['tr_lyrics'])."%'";
            }
          
            // EQUAL STATEMENTS
          
            if (strlen($_REQUEST['tr_kind']) > 0) { 
               $clause_array[] = "tr.kind = '".$_REQUEST['tr_kind']."'";
            }
            
            // DYNAMIC OPERATOR STATEMENTS
          
            if ( (strlen($_REQUEST['tr_time']) > 0) && (is_numeric($_REQUEST['tr_time'])) ) { 
               $clause_array[] = "tr.time ".$_REQUEST['tr_time_op']." ".$_REQUEST['tr_time'];
            }
          
            if ( (strlen($_REQUEST['tr_track_number']) > 0) && (is_numeric($_REQUEST['tr_track_number'])) ) { 
               $clause_array[] = "tr.track_number ".$_REQUEST['tr_track_number_op']." ".$_REQUEST['tr_track_number'];
            }
          
            if ( (strlen($_REQUEST['tr_bit_rate']) > 0) && (is_numeric($_REQUEST['tr_bit_rate'])) ) { 
               $clause_array[] = "tr.bit_rate ".$_REQUEST['tr_bit_rate_op']." ".$_REQUEST['tr_bit_rate'];
            }
          
            if ( (strlen($_REQUEST['tr_sample_rate']) > 0) && (is_numeric($_REQUEST['tr_sample_rate'])) ) { 
               $clause_array[] = "tr.sample_rate ".$_REQUEST['tr_sample_rate_op']." ".$_REQUEST['tr_sample_rate'];
            }
          
            if ( (strlen($_REQUEST['tr_size']) > 0) && (is_numeric($_REQUEST['tr_size'])) ) { 
               $clause_array[] = "tr.size ".$_REQUEST['tr_size_op']." ".$_REQUEST['tr_size'];
            }
          
            if ( (strlen($_REQUEST['tr_dl_cnt']) > 0) && (is_numeric($_REQUEST['tr_dl_cnt'])) ) { 
               $clause_array[] = "tr.dl_cnt ".$_REQUEST['tr_dl_cnt_op']." ".$_REQUEST['tr_dl_cnt'];
            }
            
            // BUILD SQL CLAUSE OR EXIT WITH ERROR
            
            if (count($clause_array) > 0) {
            
               $clause = '( '.implode(' '.$_REQUEST['condition'].' ',$clause_array).' )';
               // echo $clause;
  
               $clause_enc = obfuscate_apply($clause);
            
            } else {
            
               alert(SRCH_NOSTR);
               exit;
            
            }
         
         } else {
         
         // QUERY FROM LINK (SORT, FILTER, ETC.)
            
            // DECODE SQL CLAUSE OR EXIT WITH ERROR
            
            if (strlen($_REQUEST['clause']) > 0) {
            
               $clause = obfuscate_undo($_REQUEST['clause']);
               // echo $clause;
  
               $clause_enc = $_REQUEST['clause'];
            
            } else {
            
               alert(SRCH_NOSTR);
               exit;
            
            }
         
         }
      
      } else {

      // SIMPLE SEARCH
            
         $prev_val = db_to_raw($_REQUEST['val']);
         
         if (strlen($_REQUEST['val']) > 0) {
   
           if ($_REQUEST['col'] == 'tr.time') {
   
             if (substr($_REQUEST['val'],0,1) == '>') {
                 $clause = $_REQUEST['col']." > ".substr($_REQUEST['val'],1);
             } else if (substr($_REQUEST['val'],0,1) == '<') {
                 $clause = $_REQUEST['col']." < ".substr($_REQUEST['val'],1);
             } else {
                 $_REQUEST['val'] = abs($_REQUEST['val']);
                 $clause = $_REQUEST['col']." >= ".($_REQUEST['val'] - 5)." and ".$_REQUEST['col']." <= ".($_REQUEST['val'] + 5);
             } 
   
             if ($_REQUEST['sort'] == '') $_REQUEST['sort'] = "ti";
   
           } else {
   
             $upper_val = strtoupper($_REQUEST['val']);
   
             $clause = "UPPER(".$_REQUEST['col'].") like '%".raw_to_db($upper_val)."%'";
   
             if ($_REQUEST['sort'] == '') $_REQUEST['sort'] = substr($_REQUEST['col'],0,2);
   
           }
           
           $str = strtolower($_REQUEST['val']);
   
         } else {
   
           alert(SRCH_NOSTR);
           exit;
   
         }
      
      }

   }
   
   if (strlen($_REQUEST['filter']) > 0) $and = ' and '.rawurldecode($_REQUEST['filter']); 

   if (substr($_REQUEST['col'],0,2) == "ar") $ars = 'SELECTED';
   if (substr($_REQUEST['col'],0,2) == "tr") $trs = 'SELECTED';
   if (substr($_REQUEST['col'],0,2) == "al") $als = 'SELECTED';
   if (substr($_REQUEST['col'],0,2) == "ge") $ges = 'SELECTED';
   if (substr($_REQUEST['col'],3,2) == "ti") $tis = 'SELECTED';
   
   if ($NETJUKE_SESSION_VARS["email"] != "") {
     $plist_opt1 = "<A HREF=\"Javascript:Plist('addto');\" title=\"".SRCH_TB_ADDTO_HELP."\">".SRCH_TB_ADDTO."</A> &raquo;";
     $plist_opt2 = plistSelect($NETJUKE_SESSION_VARS["email"],$NETJUKE_SESSION_VARS["default_pl"]) . " <INPUT TYPE='submit' NAME='submit' VALUE='".SRCH_TB_PLIST_BTN."' class='btn_content'>";
   } else {
     if (abs(substr(SECURITY_MODE,2,1)) != 2) {
       $plist_opt1 = SRCH_TB_ADDTO . " &raquo;";
     }
     $plist_opt2 = "<A HREF='login.php'><b>".SRCH_TB_LOGIN."</b></A>";
     if (abs(substr(SECURITY_MODE,2,1)) == 0) {
       $plist_opt2 .= " / <A HREF='account.php?do=new'><b>".SRCH_TB_REGISTER."</b></A>";
     }
   }

   $columns = array ( SRCH_COLS_TR
                    , SRCH_COLS_TI
                    , SRCH_COLS_AR
                    , SRCH_COLS_AL
                    , SRCH_COLS_TN
                    , SRCH_COLS_GE );

   if ( ($_REQUEST['sort'] == '') ||  ($_REQUEST['sort'] == "al")) {

     # SORT BY ALBUM NAME
     $order_by = 'order by upper(al.name), tr.track_number, upper(ar.name), tr.location';
     $selected = 3;

   } elseif ($_REQUEST['sort'] == "ar") {

     # SORT BY ARTIST NAME
     $order_by = 'order by upper(ar.name), upper(al.name), tr.track_number, tr.location';
     $selected = 2;

   } elseif ($_REQUEST['sort'] == "tr") {

     # SORT BY TRACK NAME
     $order_by = 'order by upper(tr.name), upper(ar.name), upper(al.name), tr.track_number, tr.location';
     $selected = 0;

   } elseif ($_REQUEST['sort'] == "ge") {

     # SORT BY GENRE
     $order_by = 'order by upper(ge.name), upper(al.name), tr.track_number, tr.location';
     $selected = 5;

   } elseif ($_REQUEST['sort'] == "ti") {

     # SORT BY TIME
     $order_by = 'order by tr.time';
     $selected = 1;

   } else {

     # SORT BY TRACK NUMBER
     $order_by = 'order by tr.track_number,tr.location';
     $selected = 4;

   }

   $cnt = 0;
   
   while ($cnt < 6) {
     if ($cnt == $selected) $columns[$cnt] = strtoupper ($columns[$cnt]);
     $cnt++;
   }

   $from = <<<___END_SQL
      from netjuke_tracks tr, netjuke_artists ar, netjuke_albums al, 
           netjuke_genres ge
___END_SQL;

   $where = <<<___END_SQL
      where $clause $and
        and ar.id = tr.ar_id
        and al.id = tr.al_id
        and ge.id = tr.ge_id
___END_SQL;

   $dbrs = $dbconn->Execute("select count(tr.id), sum(tr.time), sum(tr.size) $from $where");
  
   $total_pages = $dbrs->fields[0];
   $total_time = $dbrs->fields[1];
   $total_size = $dbrs->fields[2];

   if ($_REQUEST['page_nav']) {
     list($_REQUEST['first'],$_REQUEST['page']) = split(",",$_REQUEST['page_nav']);
   }
   
   if ($_REQUEST['first'] < 1) $_REQUEST['first'] = 1;
   $plus = abs(RES_PER_PAGE);
   if ($plus < 1) $plus = 25;
   $last = $_REQUEST['first'] + $plus;
   $prev_first = $_REQUEST['first'] - $plus;
  
   if ($_REQUEST['page'] < 1) $_REQUEST['page'] = 1;

   $select = "
      SELECT tr.name, ar.name, al.name, ge.name, tr.location,
             tr.id, ar.id, al.id, ge.id,
             tr.time, tr.track_number,
             tr.img_src, ar.img_src, al.img_src,
             tr.size
   ";

   $sql = "$select $from $where $order_by";
   
   $dbrs = $dbconn->SelectLimit($sql,($last-1));

   $page_cnt = 1;
   
   $rows = $dbrs->RecordCount();
   
   while (!$dbrs->EOF) {
    
     if ($page_cnt == $last) {
      
        break;
      
     } else {

       if ($page_cnt >= $_REQUEST['first']) {

          $fields = $dbrs->fields;
          
          $select = "<input type=checkbox name='val[]' value='".$fields[5]."' title=\"".SRCH_CHCK_HELP."\">";
          $play = "<a href='./play.php?do=play&val=".$fields[5]."' title=\"".SRCH_PLAY_HELP."\"><img alt='".SRCH_PLAY_HELP."' src='".$ICONS['play']."' border=0 width=8 height=8 align=absmiddle hspace=0 vspace=0></a>";
          $play_ar = "<a href='./play.php?do=play_all&type=ar&id=".$fields[6]."' title=\"".SRCH_PLAY_AR_HELP."\"><img alt='".SRCH_PLAY_AR_HELP."' src='".$ICONS['play']."' border=0 width=8 height=8 align=absmiddle hspace=0 vspace=0></a>";
          $filter_ar = " <a href='./filter.php?do=list.albums&search_do=list.tracks&col=ar_id&val=".$fields[6]."' TARGET='NetjukeRemote' onClick=\"window.open('','NetjukeRemote','width=500,height=575,top=0,left=0,menubar=no,scrollbars=yes,resizable=yes');\" title=\"".SRCH_FILTER_AR_HELP."\"><img alt='".SRCH_FILTER_AR_HELP."' src='".$ICONS['info']."' border=0 width=8 height=8 align=absmiddle hspace=0 vspace=0></a>";
          $play_al = "<a href='./play.php?do=play_all&type=al&id=".$fields[7]."' title=\"".SRCH_PLAY_AL_HELP."\"><img alt='".SRCH_PLAY_AL_HELP."' src='".$ICONS['play']."' border=0 width=8 height=8 align=absmiddle hspace=0 vspace=0></a>";
          $filter_al = " <a href='./filter.php?do=list.artists&search_do=list.tracks&col=al_id&val=".$fields[7]."' TARGET='NetjukeRemote' onClick=\"window.open('','NetjukeRemote','width=500,height=575,top=0,left=0,menubar=no,scrollbars=yes,resizable=yes');\" title=\"".SRCH_FILTER_AL_HELP."\"><img alt='".SRCH_FILTER_AL_HELP."' src='".$ICONS['info']."' border=0 width=8 height=8 align=absmiddle hspace=0 vspace=0></a>";
          $play_ge = "<a href='./play.php?do=play_all&type=ge&id=".$fields[8]."' title=\"".SRCH_PLAY_GE_HELP."\"><img alt='".SRCH_PLAY_GE_HELP."' src='".$ICONS['play']."' border=0 width=8 height=8 align=absmiddle hspace=0 vspace=0></a>";
          $get_info = "<a href=\"".WEB_PATH."/tr-info.php?id=".$fields[5]."\" target=\"NetJukeGetInfo\" onClick=\"window.open('','NetJukeGetInfo','width=500,height=575,top=0,left=0,menubar=no,scrollbars=yes,resizable=yes');\" title=\"".SRCH_INFO_HELP."\"><img alt=\"".SRCH_INFO_HELP."\" src='".$ICONS['info']."' border=0 width=8 height=8 align=absmiddle hspace=0 vspace=0></a>";
          
          if (ENABLE_DOWNLOAD == 't') {
            
            $tr_location = $fields[4];
            if (substr_count($tr_location,"://") < 1) $tr_location = STREAM_SRVR."/".$tr_location;
            if (substr_count($tr_location,"://") < 1) $tr_location  = WEB_PATH."/".$tr_location;
            $dload = "<a href='".$tr_location."' title=\"".SRCH_DLOAD_HELP."\"><img alt='".SRCH_DLOAD_HELP."' src='".$ICONS['dload']."' border=0 width=7 height=8 align=absmiddle hspace=0 vspace=0></a>";
          
          } else {
          
            $dload = "";
          
          }

          $time = myTimeFormat($fields[9]);

          $fields[0] = format_for_display($fields[0]);
          $fields[1] = format_for_display($fields[1]);
          $fields[2] = format_for_display($fields[2]);
          $fields[3] = format_for_display($fields[3]);
          
          $tr_img = image_icon($fields[11]);
          $ar_img = image_icon($fields[12]);
          $al_img = image_icon($fields[13]);
          
          $html .= "
          <tr valign=top>
            <td class='content' nowrap>$select $play $dload $get_info</td>
            <td class='content'>$fields[0]&nbsp;".$tr_img."</td>
            <td class='content' align=right>$time&nbsp;</td>
            <td class='content'>$play_ar $filter_ar <A HREF=\"".$_SERVER['PHP_SELF']."?do=list.tracks&col=ar.id&val=$fields[6]&sort=ar\" title=\"".SRCH_AR_HELP."\">$fields[1]</A>&nbsp;".$ar_img."</td>
            <td class='content'>$play_al $filter_al <A HREF=\"".$_SERVER['PHP_SELF']."?do=list.tracks&col=al.id&val=$fields[7]&sort=al\" title=\"".SRCH_AL_HELP."\">$fields[2]</A>&nbsp;".$al_img."</td>
            <td class='content' align=center>$fields[10]</td>
            <td class='content'>$play_ge <A HREF=\"".$_SERVER['PHP_SELF']."?do=list.tracks&col=ge.id&val=$fields[8]&sort=ge\" title=\"".SRCH_GE_HELP."\">$fields[3]</A>&nbsp;</td>
          </tr> 
          ";
       
       }
       
       $page_cnt++;
          
       $dbrs->MoveNext();
    
     }

   }

   $dbrs->Close();
  
   if ($prev_first > 0) {
     if ($_REQUEST['do'] == 'search.adv') {
       $prev_btn = "<b>&laquo; <a href='".$_SERVER['PHP_SELF']."?do=".$_REQUEST['do']."&clause=".$clause_enc."&sort=".$_REQUEST['sort']."&filter=".$_REQUEST['filter']."&first=$prev_first&page=".($_REQUEST['page'] - 1)."' title=\"".SRCH_TB_PREV_HELP."\">".SRCH_TB_PREV."</a> &nbsp;";
     } else {
       $prev_btn = "<b>&laquo; <a href='".$_SERVER['PHP_SELF']."?do=".$_REQUEST['do']."&col=".$_REQUEST['col']."&val=".$_REQUEST['val']."&sort=".$_REQUEST['sort']."&filter=".$_REQUEST['filter']."&first=$prev_first&page=".($_REQUEST['page'] - 1)."' title=\"".SRCH_TB_PREV_HELP."\">".SRCH_TB_PREV."</a> &nbsp;";
     }
   } else {
     $prev_btn = "&nbsp";
   }
   
   if ($page_cnt <= $total_pages) {
     if ($_REQUEST['do'] == 'search.adv') {
       $next_btn = "<b><a href='".$_SERVER['PHP_SELF']."?do=".$_REQUEST['do']."&clause=".$clause_enc."&sort=".$_REQUEST['sort']."&filter=".$_REQUEST['filter']."&first=$last&page=".($_REQUEST['page'] + 1)."' title=\"".SRCH_TB_NEXT_HELP."\">".SRCH_TB_NEXT."</a> &raquo;</b> &nbsp;";
     } else {
       $next_btn = "<b><a href='".$_SERVER['PHP_SELF']."?do=".$_REQUEST['do']."&col=".$_REQUEST['col']."&val=".$_REQUEST['val']."&sort=".$_REQUEST['sort']."&filter=".$_REQUEST['filter']."&first=$last&page=".($_REQUEST['page'] + 1)."' title=\"".SRCH_TB_NEXT_HELP."\">".SRCH_TB_NEXT."</a> &raquo;</b> &nbsp;";
     }
   } else {
     $next_btn = "&nbsp";
   }
   
   $page_cnt = 0;
   
   $this_page  = 1;
 
   $this_prev_first = 1;
   
   while ($page_cnt < $total_pages) {
     
     if ($page_cnt % $plus == 0) {
 
       $this_first = $this_prev_first;
 
       if ($this_page == $_REQUEST['page']) {
         $page_selected = "selected";
       } else {
         $page_selected = "";
       }
       $page_nav_html .= "<option value='$this_first,$this_page' $page_selected>".SRCH_TB_PAGE." $this_page</option>";
   
       $this_prev_first = $this_first + $plus;
       
        $this_page++;
     
     }
      
     $page_cnt++;
  
   }

   # HTML header
   $section = "browse";
   include (INTERFACE_HEADER);
   
}

########################################

?>

        <table width='100%' border=0 cellspacing=1 cellpadding=3 class='border'>
         <tr>
           <td class="header" nowrap colspan=5>
             <B><?php echo  SRCH_HEADER ?></B>
           </td>
         </tr>
        <form action='<?php echo $_SERVER['PHP_SELF']?>' method=get name='searchForm'>
        <input type=hidden name='do' value='search'>
         <tr>
           <td class="content" align=center nowrap>
               <?php echo  $total_pages." ".SRCH_ROWCNT_1.". " ?>
           </td>
           <td class="content" align=center nowrap>
               <?php echo  " ".SRCH_ROWCNT_2." ".$_REQUEST['page']." ".SRCH_ROWCNT_3." ". ($this_page - 1) ?>
           </td>
           <td class="content" align=center nowrap>
               <?php echo  myTimeFormat($total_time)." ".SRCH_ROWCNT_4." ". myFilesizeFormat($total_size) ?>
           </td>
           <td class="content" align=center nowrap colspan=2>
             <select name='col' class=input_content>
               <option value='ar.name' <?php echo $ars?>><?php echo  SRCH_QS_AR ?></option>
               <option value='tr.name' <?php echo $trs?>><?php echo  SRCH_QS_TR ?></option>
               <option value='al.name' <?php echo $als?>><?php echo  SRCH_QS_AL ?></option>
               <option value='ge.name' <?php echo $ges?>><?php echo  SRCH_QS_GE ?></option>
               <option value='tr.time' <?php echo $tis?>><?php echo  SRCH_QS_TI ?></option>
             </select>
             <input type=text name='val' size='15' maxlength=50 value='<?php echo $prev_val?>' class=input_content>
             <input type=submit value='<?php echo  SRCH_QS_BTN ?>' class='btn_content'> 
           </td>
        </tr>
        </form>
        <form action='pl-edit.php' method=get name='viewForm' onSubmit="return(Plist('view'));">
        <input type=hidden name='do' value='edit'>
         <tr>
           <td class="content" align=center nowrap><B><A HREF="Javascript:SelectAll();" title="<?php echo  SRCH_TB_SELECT_HELP ?>"><?php echo  SRCH_TB_SELECT ?></A></B></td>
           <td class="content" align=center nowrap><B><A HREF="Javascript:ResetAll();" title="<?php echo  SRCH_TB_RESET_HELP ?>"><?php echo  SRCH_TB_RESET ?></A></B></td>
           <td class="content" align=center nowrap><B><A HREF="Javascript:Plist('play');" title="<?php echo  SRCH_TB_PLAYSEL_HELP ?>"><?php echo  SRCH_TB_PLAYSEL ?></A></B></td>
           <td class="content" align=center nowrap><B><?php echo $plist_opt1?></B></td>
           <td class="content" align=center nowrap><?php echo $plist_opt2?></td>
        </tr>
        </form>
        </table>

        <?php echo  SpecialEditTB() ?>
        
        <BR>

        <?php searchPageNav('top', $clause_enc, $prev_btn, $next_btn, $page_nav_html); ?>

        <BR>

        <table width='100%' border=0 cellspacing=1 cellpadding=3 class='border'>   
        <form name='playForm'> 
           
           <?php
             if ($_REQUEST['do'] == 'search.adv') {
               advancedHeader($columns, $clause_enc);
             } else {
               simpleHeader($columns, $valurl);
             }
           ?>

<!-- tbody style="overflow: auto; height: 330px;" -->

           <?php echo  $html ?>

<!-- /tbody -->

        </form> 
        </table>


        <BR>

        <?php searchPageNav('bottom', $clause_enc, $prev_btn, $next_btn, $page_nav_html); ?>
   
<?php
   
   # HTML footer
   include (INTERFACE_FOOTER);

########################################

function searchPageNav($type, $clause_enc, $prev_btn, $next_btn, $page_nav_html) {

  if ($type == 'top') {
    $scrolling = "<a href='#PageBot' title='". SRCH_TB_PAGEBOT_HELP ."'><b>". SRCH_TB_PAGEBOT ."</b></a>";
  } else {
    $scrolling = "<a href='#PageTop' title='". SRCH_TB_PAGETOP_HELP ."'><b>". SRCH_TB_PAGETOP ."</b></a>";
  }
  
  if ($_REQUEST['do'] == 'search.adv') {

    $hidden_fields = "
        <input type=hidden name='do' value='".$_REQUEST['do']."'>
        <input type=hidden name='clause' value='".$clause_enc."'>
        <input type=hidden name='sort' value='".$_REQUEST['sort']."'>
        <input type=hidden name='filter' value='".$_REQUEST['filter']."'>
    ";
  
  } else {

    $hidden_fields = "
        <input type=hidden name='do' value='".$_REQUEST['do']."'>
        <input type=hidden name='col' value='".$_REQUEST['col']."'>
        <input type=hidden name='val' value='".$_REQUEST['val']."'>
        <input type=hidden name='sort' value='".$_REQUEST['sort']."'>
        <input type=hidden name='filter' value='".$_REQUEST['filter']."'>
    ";
  
  }
  
  echo "
        <table width='100%' border=0 cellspacing=1 cellpadding=3 class='border'>
        <form action='".$_SERVER['PHP_SELF']."' method=get name='pageForm'>
        ".$hidden_fields."
         <tr>
           <td width='25%' class='content' align=center nowrap>
             ".$prev_btn."
           </td>
           <td width='25%' class='content' align=center nowrap>
             ".$scrolling."
           </td>
           <td width='25%' class='content' align=center>
             <select name='page_nav' class=input_content>
               ".$page_nav_html."
             </select>
             <input type=submit value='". SRCH_TB_PAGE_BTN ."' class='btn_content'>
           </td>
           <td width='25%' class='content' align=center nowrap>
             ".$next_btn."
           </td>
        </tr>
        </form>
        </table>
  ";

}

########################################

function simpleHeader($columns, $valurl) {

  GLOBAL $ICONS;
  
  if (strlen($_REQUEST['filter']) == 0) {
    $ar_filter = "&nbsp;<A HREF='". WEB_PATH ."/filter.php?do=list.artists&search_do=".$_REQUEST['do']."&col=".$_REQUEST['col']."&val=".$valurl."' TARGET='NetjukeRemote' onClick=\"window.open('','NetjukeRemote','width=500,height=575,top=0,left=0,menubar=no,scrollbars=yes,resizable=yes');\" title='Click To Filter These Results By Artists'><img src='".$ICONS['filter']."' alt='Click To Filter These Results By Artists' hspace='0' vspace='0' border='0' align='absmiddle'></a>";
  }
  
  if (strlen($_REQUEST['filter']) == 0) {
    $al_filter = "&nbsp;<A A HREF='". WEB_PATH ."/filter.php?do=list.albums&search_do=".$_REQUEST['do']."&col=".$_REQUEST['col']."&val=".$valurl."' TARGET='NetjukeRemote' onClick=\"window.open('','NetjukeRemote','width=500,height=575,top=0,left=0,menubar=no,scrollbars=yes,resizable=yes');\" title='Click To Filter These Results By Albums'><img src='".$ICONS['filter']."' alt='Click To Filter These Results By Albums' hspace='0' vspace='0' border='0' align='absmiddle'></a>";
  }
  
  if (strlen($_REQUEST['filter']) == 0) {
    $ge_filter = "&nbsp;<A HREF='". WEB_PATH ."/filter.php?do=list.genres&search_do=".$_REQUEST['do']."&col=".$_REQUEST['col']."&val=".$valurl."' TARGET='NetjukeRemote' onClick=\"window.open('','NetjukeRemote','width=500,height=575,top=0,left=0,menubar=no,scrollbars=yes,resizable=yes');\" title='Click To Filter These Results By Genres'><img src='".$ICONS['filter']."' alt='Click To Filter These Results By Genres' hspace='0' vspace='0' border='0' align='absmiddle'></a>";
  }
  
  echo "
    <tr>
      <td class='header' width='5%' nowrap>
        <B>". SRCH_COLS_OP ."</B>
      </td>
      <td class='header' width='30%' nowrap>
        <B><A CLASS='header' HREF='".$_SERVER['PHP_SELF']."?do=".$_REQUEST['do']."&col=".$_REQUEST['col']."&val=".$valurl."&sort=tr&filter=".$_REQUEST['filter']."' title='". SRCH_COLS_TR_HELP ."'>".$columns[0]."</A></B>
      </td>
      <td class='header' width='5%' nowrap>
        <B><A CLASS='header' HREF='".$_SERVER['PHP_SELF']."?do=".$_REQUEST['do']."&col=".$_REQUEST['col']."&val=".$valurl."&sort=ti&filter=".$_REQUEST['filter']."' title='". SRCH_COLS_TI_HELP ."'>".$columns[1]."</A></B>
      </td>
      <td class='header' width='20%' nowrap>
        <B><A CLASS='header' HREF='".$_SERVER['PHP_SELF']."?do=".$_REQUEST['do']."&col=".$_REQUEST['col']."&val=".$valurl."&sort=ar&filter=".$_REQUEST['filter']."' title='". SRCH_COLS_AR_HELP ."'>".$columns[2]."</A></B>
        ". $ar_filter ."
      </td>
      <td class='header' width='28%' nowrap>
        <B><A CLASS='header' HREF='".$_SERVER['PHP_SELF']."?do=".$_REQUEST['do']."&col=".$_REQUEST['col']."&val=".$valurl."&sort=al&filter=".$_REQUEST['filter']."' title='". SRCH_COLS_AL_HELP ."'>".$columns[3]."</A></B>
        ". $al_filter ."
      </td>
      <td class='header' width='2%' nowrap>
        <B><A CLASS='header' HREF='".$_SERVER['PHP_SELF']."?do=".$_REQUEST['do']."&col=".$_REQUEST['col']."&val=".$valurl."&sort=tn&filter=".$_REQUEST['filter']."' title='". SRCH_COLS_TN_HELP ."'>".$columns[4]."</A></B>
      </td>
      <td class='header' width='10%' nowrap>
        <B><A CLASS='header' HREF='".$_SERVER['PHP_SELF']."?do=".$_REQUEST['do']."&col=".$_REQUEST['col']."&val=".$valurl."&sort=ge&filter=".$_REQUEST['filter']."' title='". SRCH_COLS_GE_HELP ."'>".$columns[5]."</A></B>
        ". $ge_filter ."
      </td>
    </tr>
  ";

}

########################################

function advancedHeader($columns, $clause_enc) {

  GLOBAL $ICONS;
  
  if (strlen($_REQUEST['filter']) == 0) {
    $ar_filter = "&nbsp;<A HREF='". WEB_PATH ."/filter.php?do=list.artists&search_do=".$_REQUEST['do']."&clause=".$clause_enc."' TARGET='NetjukeRemote' onClick=\"window.open('','NetjukeRemote','width=500,height=575,top=0,left=0,menubar=no,scrollbars=yes,resizable=yes');\" title='Click To Filter These Results By Artists'><img src='".$ICONS['filter']."' alt='Click To Filter These Results By Artists' hspace='0' vspace='0' border='0' align='absmiddle'></a>";
  }
  
  if (strlen($_REQUEST['filter']) == 0) {
    $al_filter = "&nbsp;<A A HREF='". WEB_PATH ."/filter.php?do=list.albums&search_do=".$_REQUEST['do']."&clause=".$clause_enc."' TARGET='NetjukeRemote' onClick=\"window.open('','NetjukeRemote','width=500,height=575,top=0,left=0,menubar=no,scrollbars=yes,resizable=yes');\" title='Click To Filter These Results By Albums'><img src='".$ICONS['filter']."' alt='Click To Filter These Results By Albums' hspace='0' vspace='0' border='0' align='absmiddle'></a>";
  }
  
  if (strlen($_REQUEST['filter']) == 0) {
    $ge_filter = "&nbsp;<A HREF='". WEB_PATH ."/filter.php?do=list.genres&search_do=".$_REQUEST['do']."&clause=".$clause_enc."' TARGET='NetjukeRemote' onClick=\"window.open('','NetjukeRemote','width=500,height=575,top=0,left=0,menubar=no,scrollbars=yes,resizable=yes');\" title='Click To Filter These Results By Genres'><img src='".$ICONS['filter']."' alt='Click To Filter These Results By Genres' hspace='0' vspace='0' border='0' align='absmiddle'></a>";
  }
  
  echo "
    <tr>
      <td class='header' width='5%' nowrap>
        <B>". SRCH_COLS_OP ."</B>
      </td>
      <td class='header' width='30%' nowrap>
        <B><A CLASS='header' HREF='".$_SERVER['PHP_SELF']."?do=".$_REQUEST['do']."&clause=".$clause_enc."&sort=tr&filter=".$_REQUEST['filter']."' title='". SRCH_COLS_TR_HELP ."'>".$columns[0]."</A></B>
      </td>
      <td class='header' width='5%' nowrap>
        <B><A CLASS='header' HREF='".$_SERVER['PHP_SELF']."?do=".$_REQUEST['do']."&clause=".$clause_enc."&sort=ti&filter=".$_REQUEST['filter']."' title='". SRCH_COLS_TI_HELP ."'>".$columns[1]."</A></B>
      </td>
      <td class='header' width='20%' nowrap>
        <B><A CLASS='header' HREF='".$_SERVER['PHP_SELF']."?do=".$_REQUEST['do']."&clause=".$clause_enc."&sort=ar&filter=".$_REQUEST['filter']."' title='". SRCH_COLS_AR_HELP ."'>".$columns[2]."</A></B>
        ". $ar_filter ."
      </td>
      <td class='header' width='28%' nowrap>
        <B><A CLASS='header' HREF='".$_SERVER['PHP_SELF']."?do=".$_REQUEST['do']."&clause=".$clause_enc."&sort=al&filter=".$_REQUEST['filter']."' title='". SRCH_COLS_AL_HELP ."'>".$columns[3]."</A></B>
        ". $al_filter ."
      </td>
      <td class='header' width='2%' nowrap>
        <B><A CLASS='header' HREF='".$_SERVER['PHP_SELF']."?do=".$_REQUEST['do']."&clause=".$clause_enc."&sort=tn&filter=".$_REQUEST['filter']."' title='". SRCH_COLS_TN_HELP ."'>".$columns[4]."</A></B>
      </td>
      <td class='header' width='10%' nowrap>
        <B><A CLASS='header' HREF='".$_SERVER['PHP_SELF']."?do=".$_REQUEST['do']."&clause=".$clause_enc."&sort=ge&filter=".$_REQUEST['filter']."' title='". SRCH_COLS_GE_HELP ."'>".$columns[5]."</A></B>
        ". $ge_filter ."
      </td>
    </tr>
  ";

}

########################################

?>
