<?php

// defines if this script requires to be logged in
define( "PRIVATE", false );

# Call common libraries
require_once('./lib/inc-common.php');
require_once(FS_PATH."/etc/locale/".LANG_PACK."/inc-pl-shrd-view.php");

# make sure the community feature is enabled
if (ENABLE_COMMUNITY != 't') header(WEB_PATH."/index.php");

if ($_REQUEST['do'] == "save") {
########################################
# SAVE PLAYLIST
########################################
#  Usage:
#  - Save: pl-shrd-view.php?do=save&pl_id=1000&pl_title=value
########################################

   if ($NETJUKE_SESSION_VARS["email"] == '') {
     header('Location:'.WEB_PATH.'/login.php');
     exit;
   }
   
   if (strlen($_REQUEST['pl_id']) < 1) {
     print javascript("alert('".PLSHRD_SAVE_NOID."');self.history.go(-1);");
     exit;
   }
   
   $date_created = date("Y-m-d H:i:s");
   
   $dbconn->Execute(" INSERT INTO netjuke_plists_fav (us_email, pl_id,created) "
                   ." values ('".$NETJUKE_SESSION_VARS["email"]."', ".$_REQUEST['pl_id'].", '".$date_created."') ");

   header ("Location: ./pl-shrd-view.php?do=view&pl_id=".$_REQUEST['pl_id']."\n\n");
   exit;

} elseif ($_REQUEST['do'] == "del_pl") {
########################################
# DELETE PLAYLIST
########################################
#  Usage:
#  - Delete: pl-shrd-view.php?do=del_pl&pl_id=1000
########################################

   if ($NETJUKE_SESSION_VARS["email"] == '') {
     header('Location:'.WEB_PATH.'/login.php');
     exit;
   }

   if (strlen($pl_fav_id) < 1) {
     print javascript("alert('".PLSHRD_DELPL_NOID."');self.history.go(-1);");
     exit;
   }

   $dbconn->Execute("DELETE FROM netjuke_plists_fav WHERE id = $pl_fav_id AND us_email = '".$NETJUKE_SESSION_VARS["email"]."'");

   header ("Location: pl-list.php?do=list\n\n");
   exit;

} elseif ($_REQUEST['do'] == "view") {
########################################
# VIEW PLAYLIST
########################################
#  Usage:
#  - View: pl-shrd-view.php?do=view&pl_id=1000
########################################

   if (strlen($_REQUEST['pl_id']) < 1) {
     print javascript("alert('".PLSHRD_NOPLID."');self.history.go(-1);");
     exit;
   }
   
   $dbrs = $dbconn->Execute(" SELECT title, comment, shared_list, us_email from netjuke_plists "
         . " where id = ".$_REQUEST['pl_id']." and shared_list = 't' ");
   
   if ($dbrs->RecordCount() == 1) {

     $pl_title = $dbrs->fields[0];
     $pl_comment = $dbrs->fields[1];
     $pl_us_email = get_display_name($dbrs->fields[3]);
     
   } else {
   
     header("Location: ./index.php");
     exit;
   
   }
   
   $dbrs->Close();
   
   $dbrs = $dbconn->Execute("SELECT id, created from netjuke_plists_fav where pl_id = ".$_REQUEST['pl_id']." and us_email = '".$NETJUKE_SESSION_VARS["email"]."'");

   if ($dbrs->RecordCount() > 0) {
     $pl_fav_id = $dbrs->fields[0];
     $pl_fav_date = $dbrs->fields[1];
   }
   
   $dbrs->Close();

   $columns = array ( PLSHRD_COLS_SQ
                    , PLSHRD_COLS_TR
                    , PLSHRD_COLS_TI
                    , PLSHRD_COLS_AR
                    , PLSHRD_COLS_AL
                    , PLSHRD_COLS_TN
                    , PLSHRD_COLS_GE );

   if ( (!$_REQUEST['sort']) ||  ($_REQUEST['sort'] == "sq")) {

     # SORT BY SEQUENCE #
     $order_by = 'pt.sequence,tr.track_number,tr.location';
     $selected = 0;

   } elseif ($_REQUEST['sort'] == "ar") {

     # SORT BY ARTIST NAME
     $order_by = 'upper(ar.name),upper(al.name),tr.track_number,tr.location';
     $selected = 3;

   } elseif ($_REQUEST['sort'] == "al") {

     # SORT BY ALBUM NAME
     $order_by = 'upper(al.name),tr.track_number,tr.location';
     $selected = 4;

   } elseif ($_REQUEST['sort'] == "tr") {

     # SORT BY TRACK NAME
     $order_by = 'upper(tr.name),upper(ar.name),upper(al.name),tr.track_number,tr.location';
     $selected = 1;

   } elseif ($_REQUEST['sort'] == "ge") {

     # SORT BY GENRE
     $order_by = 'upper(ge.name),upper(al.name),tr.track_number,tr.location';
     $selected = 6;

   } elseif ($_REQUEST['sort'] == "ti") {

     # SORT BY TIME
     $order_by = 'tr.time';
     $selected = 2;

   } else {

     # SORT BY TRACK NUMBER
     $order_by = 'tr.track_number,tr.location';
     $selected = 5;

   }

   $cnt = 0;
   
   while ($cnt < 7) {
     if ($cnt == $selected) $columns[$cnt] = strtoupper ($columns[$cnt]);
     $cnt++;
   }

   $sql = "
      SELECT tr.name, ar.name, al.name, ge.name, 
             tr.location, tr.id, ar.id, al.id,
             ge.id, tr.time, tr.track_number, pt.sequence, pt.id,
             tr.img_src, ar.img_src, al.img_src,
             tr.size
      from netjuke_tracks tr, netjuke_artists ar, netjuke_albums al, 
           netjuke_genres ge, netjuke_plists pl, netjuke_plists_tracks pt
      where pt.pl_id = ".$_REQUEST['pl_id']."
        and pl.shared_list = 't'
        and pt.pl_id = pl.id
        and pt.us_email = pl.us_email
        and tr.id = pt.tr_id
        and ar.id = tr.ar_id
        and al.id = tr.al_id
        and ge.id = tr.ge_id
      order by $order_by asc
   ";
   
   $dbrs = $dbconn->Execute($sql);
   
   $rows = $dbrs->RecordCount();
   
   $total_time = 0;
   
   $total_size = 0;
   
   if ($rows == 0) header("Location: ./index.php");
   
   while (!$dbrs->EOF) {

      $fields = $dbrs->fields;

      $fields[0] = format_for_display($fields[0]);
      $fields[1] = format_for_display($fields[1]);
      $fields[2] = format_for_display($fields[2]);
      $fields[3] = format_for_display($fields[3]);
          
      $tr_img = image_icon($fields[13]);
      $ar_img = image_icon($fields[14]);
      $al_img = image_icon($fields[15]);
      
      $select = "<input type=checkbox name='val[]' value='".$fields[5]."' title='".PLSHRD_CHCK_HELP."'>";
      $play = "<a href='./play.php?do=play&val=".$fields[5]."' title='".PLSHRD_PLAY_HELP."'><img alt='".PLSHRD_PLAY_HELP."' src='".$ICONS['play']."' border=0 width=8 height=8 align=absmiddle hspace=0 vspace=0></a>";
      $play_ar = "<a href='./play.php?do=play_all&type=ar&id=".$fields[6]."' title=\"".PLSHRD_PLAY_AR_HELP."\"><img alt='".PLSHRD_PLAY_AR_HELP."' src='".$ICONS['play']."' border=0 width=8 height=8 align=absmiddle hspace=0 vspace=0></a>";
      $filter_ar = " <a href='./filter.php?do=list.albums&search_do=list.tracks&col=ar_id&val=".$fields[6]."' TARGET='NetjukeRemote' onClick=\"window.open('','NetjukeRemote','width=500,height=575,top=0,left=0,menubar=no,scrollbars=yes,resizable=yes');\" title=\"".PLSHRD_FILTER_AR_HELP."\"><img alt='".PLSHRD_FILTER_AR_HELP."' src='".$ICONS['info']."' border=0 width=8 height=8 align=absmiddle hspace=0 vspace=0></a>";
      $play_al = "<a href='./play.php?do=play_all&type=al&id=".$fields[7]."' title=\"".PLSHRD_PLAY_AL_HELP."\"><img alt='".PLSHRD_PLAY_AL_HELP."' src='".$ICONS['play']."' border=0 width=8 height=8 align=absmiddle hspace=0 vspace=0></a>";
      $filter_al = " <a href='./filter.php?do=list.artists&search_do=list.tracks&col=al_id&val=".$fields[7]."' TARGET='NetjukeRemote' onClick=\"window.open('','NetjukeRemote','width=500,height=575,top=0,left=0,menubar=no,scrollbars=yes,resizable=yes');\" title=\"".PLSHRD_FILTER_AL_HELP."\"><img alt='".PLSHRD_FILTER_AL_HELP."' src='".$ICONS['info']."' border=0 width=8 height=8 align=absmiddle hspace=0 vspace=0></a>";
      $play_ge = "<a href='./play.php?do=play_all&type=ge&id=".$fields[8]."' title=\"".PLSHRD_PLAY_GE_HELP."\"><img alt='".PLSHRD_PLAY_GE_HELP."' src='".$ICONS['play']."' border=0 width=8 height=8 align=absmiddle hspace=0 vspace=0></a>";
      $get_info = "<a href=\"".WEB_PATH."/tr-info.php?id=".$fields[5]."\" target=\"NetJukeGetInfo\" onClick=\"window.open('','NetJukeGetInfo','width=500,height=575,top=0,left=0,menubar=no,scrollbars=yes,resizable=yes');\" title=\"".PLSHRD_INFO_HELP."\"><img alt=\"".PLSHRD_INFO_HELP."\" src='".$ICONS['info']."' border=0 width=8 height=8 align=absmiddle hspace=0 vspace=0></a>";
      
      if (ENABLE_DOWNLOAD == 't') {
        
        $tr_location = $fields[4];
        if (substr_count($tr_location,"://") < 1) $tr_location = STREAM_SRVR."/".$tr_location;
        if (substr_count($tr_location,"://") < 1) $tr_location  = WEB_PATH."/".$tr_location;
        $dload = "<a href='".$tr_location."' title=\"".PLSHRD_DLOAD_HELP."\"><img alt='".PLSHRD_DLOAD_HELP."' src='".$ICONS['dload']."' border=0 width=7 height=8 align=absmiddle hspace=0 vspace=0></a>";
      
      } else {
      
        $dload = "";
      
      }
      
      $time = myTimeFormat($fields[9]);
          
      $total_time += $fields[9];
          
      $total_size += $fields[16];
      
      $html .= "
      <tr valign=top>
      <input type=hidden name='pt_id[]' value='$fields[12]'>
        <td class='content' nowrap>$select $play $dload $get_info</td>
        <td class='content' align=right>$fields[11]&nbsp;</td>
        <td class='content'>$fields[0]&nbsp;$tr_img</td>
        <td class='content' align=right>$time&nbsp;</td>
        <td class='content'>$play_ar $filter_ar <A HREF='search.php?do=list.tracks&col=ar.id&val=$fields[6]&sort=ar' title='".PLSHRD_AR_HELP."'>$fields[1]</A>&nbsp;$ar_img</td>
        <td class='content'>$play_al $filter_al <A HREF='search.php?do=list.tracks&col=al.id&val=$fields[7]&sort=al' title='".PLSHRD_AL_HELP."'>$fields[2]</A>&nbsp;$al_img</td>
        <td class='content' align=center>$fields[10]</td>
        <td class='content'>$play_ge <A HREF='search.php?do=list.tracks&col=ge.id&val=$fields[8]&sort=ge' title='".PLSHRD_GE_HELP."'>$fields[3]</A>&nbsp;</td>
      </tr> 
      ";
      
      $dbrs->MoveNext();

   }

   $dbrs->Close();

   # HTML header
   if (!$_REQUEST['section']) $_REQUEST['section'] = "playlists";
   include (INTERFACE_HEADER);

########################################

?>

        <script language="javascript">
        <!--
          function deletePlist() {
            if (confirm("<?php echo  PLSHRD_DELPL_CONFIRM ?>\nTitle: <?php echo $pl_title?>")) {
              self.location.href = '<?php echo $_SERVER['PHP_SELF']?>?do=del_pl&pl_fav_id=<?php echo abs($pl_fav_id)?>';
            }
          }
        -->
        </script>
        <table width='100%' border=0 cellspacing=1 cellpadding=3 class="border">
        <form>
         <tr>
           <td width="100%" class="header" nowrap colspan=6>
             <TABLE BORDER="0" WIDTH="100%" CELLSPACING="0" CELLPADDING="0">
               <TR>
                 <TD ALIGN=LEFT VALIGN=MIDDLE WIDTH="75%" nowrap class="header">
                   <b>
                     <?php 
                       if ( abs($pl_fav_id) > 0 ) { 
                         echo PLSHRD_HEADER_FAV; 
                       } else {
                         echo PLSHRD_HEADER_PUB;
                       }
                       echo ": ".$pl_title." ".PLSHRD_HEADER_BY." ".$pl_us_email;
                     ?>
                   </b>
                 </TD>
                 <TD ALIGN=RIGHT VALIGN=MIDDLE WIDTH="25%" nowrap class="header">
                   <?php if ( abs($pl_fav_id) > 0 ) { ?>
                     <INPUT TYPE='button' NAME='mode' VALUE='<?php echo  PLSHRD_BTN_DELETE ?>' class='btn_header' onClick="deletePlist();">
                   <?php } ?>
                   <INPUT TYPE='button' NAME='mode' VALUE='<?php echo  PLSHRD_BTN_CANCEL ?>' class='btn_header' onClick="self.location.href='pl-list.php?do=list';">
                 </TD>
               </TR>
             </TABLE>
             </B>
           </td>
         </tr>
        </form>
         <tr>
           <td width="30%" class="content" align=center nowrap>
             <?php echo $rows?> <?php echo  PLSHRD_ROWCNT_1 ?>
           </td>
           <td class="content" align=center nowrap>
               <?php echo  myTimeFormat($total_time)." ".PLSHRD_ROWCNT_2." ". myFilesizeFormat($total_size) ?>
           </td>
        <form action='search.php' method=get name='searchForm'>
        <input type=hidden name='do' value='search'>
           <td width="35%" class="content" align=center nowrap colspan=2>
             <select name='col' class=input_content>
               <option value='ar.name' ><?php echo  PLSHRD_QS_AR ?></option>
               <option value='tr.name' ><?php echo  PLSHRD_QS_TR ?></option>
               <option value='al.name' ><?php echo  PLSHRD_QS_AL ?></option>
               <option value='ge.name' ><?php echo  PLSHRD_QS_GE ?></option>
               <option value='tr.time' ><?php echo  PLSHRD_QS_TI ?></option>
             </select>
             <input type=text name='val' size='12' maxlength=50 value='' class=input_content>
             <input type=submit value='<?php echo  PLSHRD_QS_BTN ?>' class='btn_content'> 
           </td>
        </form>
        <form action='pl-edit.php' method=get name='viewForm' onSubmit="return(Plist('view'));">
        <input type=hidden name='do' value='edit'>
            <td width="35%" class="content" align=center nowrap colspan=2>
            	<?php echo  plistSelect($NETJUKE_SESSION_VARS["email"],$NETJUKE_SESSION_VARS["default_pl"]) ?>
            	<INPUT TYPE='submit' NAME='mode' VALUE='<?php echo  PLSHRD_TB_PLIST_BTN ?>' class='btn_content'>
            </td>
        </form>
        </tr>
        <tr>
             <?php if ($_REQUEST['section'] != "playlists") { ?>
               <td class="content" align=center nowrap><B><A HREF="<?php echo $_SERVER['PHP_SELF']?>?do=save&pl_id=<?php echo $_REQUEST['pl_id']?>" title='<?php echo  PLSHRD_TB_SAVE_HELP ?>'><?php echo  PLSHRD_TB_SAVE ?></A></B></td>
             <?php } else  { ?>
               <td class="content" align=center nowrap><?php echo  PLSHRD_TB_ADDED ?>: <?php echo  date("Y-m-d H:i", strtotime($pl_fav_date)) ?></td>
             <?php } ?>
           <td class="content" align=center nowrap><B><A HREF="play.php?do=plist&val=<?php echo $_REQUEST['pl_id']?>" title='<?php echo  PLSHRD_TB_PLAY_ALL_HELP ?>'><?php echo  PLSHRD_TB_PLAY_ALL ?></A></B></td>
           <td class="content" align=center nowrap><B><A HREF="Javascript:SelectAll();" title="<?php echo  PLSHRD_TB_SELECT_HELP ?>"><?php echo  PLSHRD_TB_SELECT ?></A></B></td>
           <td class="content" align=center nowrap><B><A HREF="Javascript:ResetAll();" title="<?php echo  PLSHRD_TB_RESET_HELP ?>"><?php echo  PLSHRD_TB_RESET ?></A></B></td>
           <td class="content" align=center nowrap><B><A HREF="Javascript:Plist('play');" title="<?php echo  PLSHRD_TB_PLAY_HELP ?>"><?php echo  PLSHRD_TB_PLAY ?></A></B></td>
           <td class="content" align=center nowrap><B><A HREF="Javascript:Plist('addto');" title="<?php echo  PLSHRD_TB_ADDTO_HELP ?>"><?php echo  PLSHRD_TB_ADDTO ?></A></B></td>
        </tr>
        </table>

        <?php echo  SpecialEditTB() ?>

        <BR>

        <table width='100%' border=0 cellspacing=1 cellpadding=3 class="border">   
        <form action='<?php echo $_SERVER['PHP_SELF']?>' method=post name='playForm'> 
           <tr>
             <td class='header' width='4%' nowrap><B><?php echo  PLSHRD_COLS_OP ?></B></td>
             <td class='header' width='4%' nowrap><B><A CLASS="header" HREF="<?php echo $_SERVER['PHP_SELF']?>?do=<?php echo $_REQUEST['do']?>&pl_id=<?php echo $_REQUEST['pl_id']?>&sort=sq&section=<?php echo $_REQUEST['section']?>" title="<?php echo  PLSHRD_COLS_SQ_HELP ?>"><?php echo $columns[0]?></A></B></td>
             <td class='header' width='27%' nowrap><B><A CLASS="header" HREF="<?php echo $_SERVER['PHP_SELF']?>?do=<?php echo $_REQUEST['do']?>&pl_id=<?php echo $_REQUEST['pl_id']?>&sort=tr&section=<?php echo $_REQUEST['section']?>" title="<?php echo  PLSHRD_COLS_TR_HELP ?>"><?php echo $columns[1]?></A></B></td>
             <td class='header' width='5%' nowrap><B><A CLASS="header" HREF="<?php echo $_SERVER['PHP_SELF']?>?do=<?php echo $_REQUEST['do']?>&pl_id=<?php echo $_REQUEST['pl_id']?>&sort=ti&section=<?php echo $_REQUEST['section']?>" title="<?php echo  PLSHRD_COLS_TI_HELP ?>"><?php echo $columns[2]?></A></B></td>
             <td class='header' width='20%' nowrap><B><A CLASS="header" HREF="<?php echo $_SERVER['PHP_SELF']?>?do=<?php echo $_REQUEST['do']?>&pl_id=<?php echo $_REQUEST['pl_id']?>&sort=ar&section=<?php echo $_REQUEST['section']?>" title="<?php echo  PLSHRD_COLS_AR_HELP ?>"><?php echo $columns[3]?></A></B></td>
             <td class='header' width='28%' nowrap><B><A CLASS="header" HREF="<?php echo $_SERVER['PHP_SELF']?>?do=<?php echo $_REQUEST['do']?>&pl_id=<?php echo $_REQUEST['pl_id']?>&sort=al&section=<?php echo $_REQUEST['section']?>" title="<?php echo  PLSHRD_COLS_AL_HELP ?>"><?php echo $columns[4]?></A></B></td>
             <td class='header' width='2%' nowrap><B><A CLASS="header" HREF="<?php echo $_SERVER['PHP_SELF']?>?do=<?php echo $_REQUEST['do']?>&pl_id=<?php echo $_REQUEST['pl_id']?>&sort=tn&section=<?php echo $_REQUEST['section']?>" title="<?php echo  PLSHRD_COLS_TN_HELP ?>"><?php echo $columns[5]?></A></B></td>
             <td class='header' width='10%' nowrap><B><A CLASS="header" HREF="<?php echo $_SERVER['PHP_SELF']?>?do=<?php echo $_REQUEST['do']?>&pl_id=<?php echo $_REQUEST['pl_id']?>&sort=ge&section=<?php echo $_REQUEST['section']?>" title="<?php echo  PLSHRD_COLS_GE_HELP ?>"><?php echo $columns[6]?></A></B></td>
           </tr>

           <?php echo  $html ?>

        </form> 
        </table>

        <BR>
   
<?php

   # HTML footer
   include (INTERFACE_FOOTER);

   exit;

} else {

   header ("Location: index.php\n\n");

   exit;

}

?>
