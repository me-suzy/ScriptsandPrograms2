<?php

// defines if this script requires to be logged in
define( "PRIVATE", true );

# Call common libraries
require_once('./lib/inc-common.php');
require_once(FS_PATH."/etc/locale/".LANG_PACK."/inc-pl-edit.php");

if ($_REQUEST['do'] == "addto") {
########################################
# ADD TO PLAYLIST
########################################
#  Usage:
#  - Create: pl-edit.php?do=addto&pl_id=&val=1009,1001,1975
#  - Add To: pl-edit.php?do=addto&pl_id=1000&val=1009,1001,1975
########################################

  if ($_REQUEST['val'] != '') {

    if ($_REQUEST['pl_id'] != '') {
    
      # do select to confirm id
      $dbrs = $dbconn->Execute("select id from netjuke_plists where id = ".$_REQUEST['pl_id']);
      $pl_exist = $dbrs->RecordCount();
      $dbrs->Close();
    
    }
      
    if ($pl_exist !== 1) {

      # do pl insert and get new $_REQUEST['pl_id']

      $timestamp = time;
      $date = date("Y-m-d H:i:s");
      $dbconn->Execute("insert into netjuke_plists (us_email,title,created) values ('".$NETJUKE_SESSION_VARS["email"]."','$timestamp','$date')");

      $dbrs = $dbconn->Execute("select id from netjuke_plists where us_email = '".$NETJUKE_SESSION_VARS["email"]."' and title = '$timestamp'");
      $_REQUEST['pl_id'] = $dbrs->fields[0];
      $dbrs->Close();

      if (abs($_REQUEST['random']) != 1) {
      
        $title = PLEDIT_ADDTO_TITLE_1;
      
      } else {
      
        $title = PLEDIT_ADDTO_TITLE_2." ".$date;
      
      }
      
      $dbconn->Execute("update netjuke_plists set title = '$title' where id = ".$_REQUEST['pl_id']);

    }
      
    # insert pl_tr list using $_REQUEST['pl_id'], $user_email and $tr_id

    $id = split(",",$_REQUEST['val']);

    $dbrs = $dbconn->Execute("select max(sequence) from netjuke_plists_tracks where pl_id = ".$_REQUEST['pl_id']);
    $max_sequence = $dbrs->fields[0];
    $dbrs->Close();
    
    if ($max_sequence != '')  {
      $sequence = $max_sequence;
    } else {
      $sequence = 0;
    }
  
    foreach ($id as $tr_id) {

      if ($tr_id >= 1) {
         
        $sequence++;
     
        $dbconn->Execute("insert into netjuke_plists_tracks (us_email,pl_id,tr_id,sequence) values ('".$NETJUKE_SESSION_VARS["email"]."',".$_REQUEST['pl_id'].",$tr_id,$sequence)");
      
      }
    
    }
      
    # redirect to playlist using $_REQUEST['pl_id']
    
    $NETJUKE_SESSION_VARS["default_pl"] = $_REQUEST['pl_id'];
    
    netjuke_session('update');

    header ('Location: '.WEB_PATH.'/pl-edit.php?do=edit&pl_id='.$_REQUEST['pl_id']);
    exit;

  } else {

    print alert (PLEDIT_ADDTO_NOID);

  }

   exit;

} elseif ($_REQUEST['do'] == "save") {
########################################
# SAVE PLAYLIST
########################################
#  Usage:
#  - Save: pl-edit.php?do=save&pl_id=1000&pl_title=value&pt_id[]=1001&pt_id[]=1002
########################################

   if (strlen($_REQUEST['pl_id']) < 1) {
     print javascript("alert('".PLEDIT_SAVE_NOID."');self.history.go(-1);");
     exit;
   }
    
   $NETJUKE_SESSION_VARS["default_pl"] = $_REQUEST['pl_id'];
   
   netjuke_session('update');
   
   if ($_REQUEST['pl_shared'] != 't') $_REQUEST['pl_shared'] = 'f';
   
   $dbconn->Execute("UPDATE netjuke_plists SET title = '".$_REQUEST['pl_title']."', shared_list = '".$_REQUEST['pl_shared']."' WHERE id = ".$_REQUEST['pl_id']);
   
   $cnt = 0;
   
   foreach ($_REQUEST['pt_id'] as $this_id) {
     
     if ($_REQUEST['seq'][$cnt] == '') $_REQUEST['seq'][$cnt] = 1;
   
     $dbconn->Execute("UPDATE netjuke_plists_tracks SET sequence = ".$_REQUEST['seq'][$cnt]." WHERE id = $this_id");

     $cnt++;
   
   }

   header ('Location: '.WEB_PATH.'/pl-edit.php?do=edit&pl_id='.$_REQUEST['pl_id']);
   exit;

} elseif ($_REQUEST['do'] == "del_pl") {
########################################
# DELETE PLAYLIST
########################################
#  Usage:
#  - Delete: pl-edit.php?do=del_pl&pl_id=1000
########################################

   if (strlen($_REQUEST['pl_id']) < 1) {
     print javascript("alert('".PLEDIT_DELPL_NOID."');self.history.go(-1);");
     exit;
   }
    
   $NETJUKE_SESSION_VARS["default_pl"] = "";
   
   netjuke_session('update');

   $dbconn->Execute("DELETE FROM netjuke_plists WHERE id = ".$_REQUEST['pl_id']." AND us_email = '".$NETJUKE_SESSION_VARS["email"]."'");

   $dbconn->Execute("DELETE FROM netjuke_plists_tracks WHERE pl_id = ".$_REQUEST['pl_id']." AND us_email = '".$NETJUKE_SESSION_VARS["email"]."'");

   $dbconn->Execute("DELETE FROM netjuke_plists_fav WHERE pl_id = ".$_REQUEST['pl_id']);

   header ("Location: pl-list.php?do=list\n\n");
   exit;

} elseif ($_REQUEST['do'] == "del_pt") {
########################################
# DELETE TRACKS
########################################
#  Usage:
#  - Delete: pl-edit.php?do=del_pt&pl_id=1000&val=1009,1001,1975
########################################

   if (strlen($_REQUEST['pl_id']) < 1) {
     print javascript("alert('".PLEDIT_NOPLID."');self.history.go(-1);");
     exit;
   }
    
  if ($_REQUEST['val'] != '') {

    $id = split(",",$_REQUEST['val']);
  
    foreach ($id as $this_id) {

      $dbconn->Execute("delete from netjuke_plists_tracks where id = $this_id and pl_id = ".$_REQUEST['pl_id']." and us_email = '".$NETJUKE_SESSION_VARS["email"]."'");

    }
    
    $dbrs = $dbconn->Execute("select id from netjuke_plists_tracks where pl_id = ".$_REQUEST['pl_id']." and us_email = '".$NETJUKE_SESSION_VARS["email"]."'");

    if ($dbrs->RecordCount() == 0) {

      $dbconn->Execute("delete from netjuke_plists where id = ".$_REQUEST['pl_id']." and us_email = '".$NETJUKE_SESSION_VARS["email"]."'");

      header ("Location: pl-list.php\n\n");
      exit;

    }

  } else {

    alert (PLEDIT_DELPT_NOTRID);
    exit;

  }

  header ('Location: '.WEB_PATH.'/pl-edit.php?do=edit&pl_id='.$_REQUEST['pl_id']);
  exit;

} elseif ($_REQUEST['do'] == "edit") {
########################################
# EDIT PLAYLIST
########################################
#  Usage:
#  - Edit: pl-edit.php?do=edit&pl_id=1000
########################################

   if (strlen($_REQUEST['pl_id']) < 1) {
     print javascript("alert('".PLEDIT_NOPLID."');self.history.go(-1);");
     exit;
   }
    
   $NETJUKE_SESSION_VARS["default_pl"] = $_REQUEST['pl_id'];
   
   netjuke_session('update');
   
   $dbrs = $dbconn->Execute("SELECT title, comment, shared_list from netjuke_plists where id = ".$_REQUEST['pl_id']);

   $pl_title = $dbrs->fields[0];
   $pl_comment = $dbrs->fields[1];
   
   if ($dbrs->fields[2] == 't') {
     $pl_shared = "CHECKED";
   } else {
     $pl_shared = "";
   }
   
   $dbrs->Close();

   $columns = array ( PLEDIT_COLS_SQ
                    , PLEDIT_COLS_TR
                    , PLEDIT_COLS_TI
                    , PLEDIT_COLS_AR
                    , PLEDIT_COLS_AL
                    , PLEDIT_COLS_TN
                    , PLEDIT_COLS_GE );

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
             tr.img_src, ar.img_src, al.img_src, tr.size
      from netjuke_tracks tr, netjuke_artists ar, netjuke_albums al, 
           netjuke_genres ge, netjuke_plists pl, netjuke_plists_tracks pt
      where pt.pl_id = ".$_REQUEST['pl_id']."
        and pt.us_email = '".$NETJUKE_SESSION_VARS["email"]."'
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

   if ($rows < 1) {
     print javascript("alert('".PLEDIT_DENIED."');self.location.href='index.php';");
     exit;
   }
   
   while (!$dbrs->EOF) {

      $fields = $dbrs->fields;

      $fields[0] = format_for_display($fields[0]);
      $fields[1] = format_for_display($fields[1]);
      $fields[2] = format_for_display($fields[2]);
      $fields[3] = format_for_display($fields[3]);
          
      $tr_img = image_icon($fields[13]);
      $ar_img = image_icon($fields[14]);
      $al_img = image_icon($fields[15]);
      
      $select = "<input type=checkbox name='val[]' value='".$fields[5]."' title='".PLEDIT_CHCK_HELP."'>";
      $play = "<a href='./play.php?do=play&val=".$fields[5]."' title='".PLEDIT_PLAY_HELP."'><img alt='".PLEDIT_PLAY_HELP."' src='".$ICONS['play']."' border=0 width=8 height=8 align=absmiddle hspace=0 vspace=0></a>";
      $play_ar = "<a href='./play.php?do=play_all&type=ar&id=".$fields[6]."' title=\"".PLEDIT_PLAY_AR_HELP."\"><img alt='".PLEDIT_PLAY_AR_HELP."' src='".$ICONS['play']."' border=0 width=8 height=8 align=absmiddle hspace=0 vspace=0></a>";
      $filter_ar = " <a href='./filter.php?do=list.albums&search_do=list.tracks&col=ar_id&val=".$fields[6]."' TARGET='NetjukeRemote' onClick=\"window.open('','NetjukeRemote','width=500,height=575,top=0,left=0,menubar=no,scrollbars=yes,resizable=yes');\" title=\"".PLEDIT_FILTER_AR_HELP."\"><img alt='".PLEDIT_FILTER_AR_HELP."' src='".$ICONS['info']."' border=0 width=8 height=8 align=absmiddle hspace=0 vspace=0></a>";
      $play_al = "<a href='./play.php?do=play_all&type=al&id=".$fields[7]."' title=\"".PLEDIT_PLAY_AL_HELP."\"><img alt='".PLEDIT_PLAY_AL_HELP."' src='".$ICONS['play']."' border=0 width=8 height=8 align=absmiddle hspace=0 vspace=0></a>";
      $filter_al = " <a href='./filter.php?do=list.artists&search_do=list.tracks&col=al_id&val=".$fields[7]."' TARGET='NetjukeRemote' onClick=\"window.open('','NetjukeRemote','width=500,height=575,top=0,left=0,menubar=no,scrollbars=yes,resizable=yes');\" title=\"".PLEDIT_FILTER_AL_HELP."\"><img alt='".PLEDIT_FILTER_AL_HELP."' src='".$ICONS['info']."' border=0 width=8 height=8 align=absmiddle hspace=0 vspace=0></a>";
      $play_ge = "<a href='./play.php?do=play_all&type=ge&id=".$fields[8]."' title=\"".PLEDIT_PLAY_GE_HELP."\"><img alt='".PLEDIT_PLAY_GE_HELP."' src='".$ICONS['play']."' border=0 width=8 height=8 align=absmiddle hspace=0 vspace=0></a>";
      $get_info = "<a href=\"".WEB_PATH."/tr-info.php?id=".$fields[5]."\" target=\"NetJukeGetInfo\" onClick=\"window.open('','NetJukeGetInfo','width=500,height=575,top=0,left=0,menubar=no,scrollbars=yes,resizable=yes');\" title=\"".PLEDIT_INFO_HELP."\"><img alt=\"".PLEDIT_INFO_HELP."\" src='".$ICONS['info']."' border=0 width=8 height=8 align=absmiddle hspace=0 vspace=0></a>";
      
      if (ENABLE_DOWNLOAD == 't') {
        
        $tr_location = $fields[4];
        if (substr_count($tr_location,"://") < 1) $tr_location = STREAM_SRVR."/".$tr_location;
        if (substr_count($tr_location,"://") < 1) $tr_location  = WEB_PATH."/".$tr_location;
        $dload = "<a href='".$tr_location."' title=\"".PLEDIT_DLOAD_HELP."\"><img alt='".PLEDIT_DLOAD_HELP."' src='".$ICONS['dload']."' border=0 width=7 height=8 align=absmiddle hspace=0 vspace=0></a>";
      
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
        <td class='content' align=center><INPUT TYPE='text' NAME='seq[]' VALUE='$fields[11]' MAXLENGTH='3' style='text-align: right;'SIZE='3' class=input_content></td>
        <td class='content'>$fields[0]&nbsp;$tr_img</td>
        <td class='content' align=right>$time&nbsp;</td>
        <td class='content'>$play_ar $filter_ar <A HREF='search.php?do=list.tracks&col=ar.id&val=$fields[6]&sort=ar' title=\"".PLEDIT_AR_HELP."\">$fields[1]</A>&nbsp;$ar_img</td>
        <td class='content'>$play_al $filter_al <A HREF='search.php?do=list.tracks&col=al.id&val=$fields[7]&sort=al' title=\"".PLEDIT_AL_HELP."\">$fields[2]</A>&nbsp;$al_img</td>
        <td class='content' align=center>$fields[10]</td>
        <td class='content'>$play_ge <A HREF='search.php?do=list.tracks&col=ge.id&val=$fields[8]&sort=ge' title=\"".PLEDIT_GE_HELP."\">$fields[3]</A>&nbsp;</td>
      </tr> 
      ";
      
      $dbrs->MoveNext();

   }

   $dbrs->Close();

   # HTML header
   $section = "playlists";
   include (INTERFACE_HEADER);

########################################

?>

        <script language="javascript">
        <!--
          function deletePlist() {
            if (confirm("<?php echo  PLEDIT_DELPL_CONFIRM ?>\n- <?php echo $pl_title?>")) {
              self.location.href = '<?php echo $_SERVER['PHP_SELF']?>?do=del_pl&pl_id=<?php echo $_REQUEST['pl_id']?>';
            }
          }
          function savePlist() {
            document.playForm.pl_title.value = document.saveForm.pl_title.value;
            <?php if (ENABLE_COMMUNITY == 't') { ?>
              if (document.saveForm.pl_shared.checked == true) {
                document.playForm.pl_shared.value = 't';
              } else {
                document.playForm.pl_shared.value = 'f';
              }
            <?php } else { ?>
              document.playForm.pl_shared.value = 'f';
            <?php } ?>
            document.playForm.submit();
          }
          function AdjustSeq() {
            var cnt = 1;
            for (var i=0;i< document.playForm.elements.length;i++) {
              if (document.playForm.elements[i].name == 'seq[]') {
                document.playForm.elements[i].value = cnt;
                cnt++;
              }
            }
          }
          function deleteTracks() {
            if (confirm('<?php echo  PLEDIT_DELPT_CONFIRM ?>')) {
              var del_pt = '';
              for (var i=0;i<document.playForm.elements.length;i++) {
                if (document.playForm.elements[i].name == 'val[]') {
                  if (document.playForm.elements[i].checked == true) {
                    if (del_pt != '') { del_pt = del_pt + ','; }
                    del_pt = del_pt + document.playForm.elements[i-1].value
                  }
                }
              }
              if (del_pt != '') {
                top.location.href = '<?php echo $_SERVER['PHP_SELF']?>?do=del_pt&pl_id=<?php echo $_REQUEST['pl_id']?>&val=' + del_pt;
              } else {
                alert("<?php echo  PLEDIT_DELPT_NOTRID ?>");
              }
            }
          }
        -->
        </script>
        <table width='100%' border=0 cellspacing=1 cellpadding=3 class="border">
        <form name='saveForm' onSubmit="alert('<?php echo  PLEDIT_MUSTCLICK ?>');return(false);">
         <tr>
           <td class="header" nowrap colspan=7>
             <TABLE BORDER="0" WIDTH="100%" CELLSPACING="0" CELLPADDING="0">
               <TR>
                 <TD ALIGN=LEFT VALIGN=MIDDLE WIDTH="75%" nowrap class="header">
                   <?php echo  PLEDIT_HEADER_1 ?>:
                   <INPUT TYPE="text" NAME="pl_title" VALUE="<?php echo $pl_title?>" SIZE="35" MAXLENGTH="100" class=input_content>
                   <?php if (ENABLE_COMMUNITY == 't') { ?>
                     <INPUT TYPE="checkbox" NAME="pl_shared" VALUE="t" <?php echo $pl_shared?>> <?php echo  PLEDIT_SHRD ?>
                   <?php } ?>
                 </TD>
                 <TD ALIGN=RIGHT VALIGN=MIDDLE WIDTH="25%" nowrap class="header">
                   <INPUT TYPE='button' NAME='mode' VALUE='<?php echo  PLEDIT_BTN_SAVE ?>' class='btn_header' onClick="savePlist();">
                   <INPUT TYPE='button' NAME='mode' VALUE='<?php echo  PLEDIT_BTN_DELETE ?>' class='btn_header' onClick="deletePlist();">
                   <INPUT TYPE='button' NAME='mode' VALUE='<?php echo  PLEDIT_BTN_CANCEL ?>' class='btn_header' onClick="self.location.href='pl-list.php?do=list';">
                 </TD>
               </TR>
             </TABLE>
             </B>
           </td>
         </tr>
        </form>
         <tr>
           <td class="content" align=center nowrap colspan=2>
             <?php echo $rows?> <?php echo  PLEDIT_ROWCNT_1 ?>
           </td>
           <td class="content" align=center nowrap>
               <?php echo  myTimeFormat($total_time)." ".PLEDIT_ROWCNT_2." ". myFilesizeFormat($total_size) ?>
           </td>
        <form action='search.php' method=get name='searchForm'>
        <input type=hidden name='do' value='search'>
           <td class="content" align=center nowrap colspan=2>
             <select name='col' class=input_content>
               <option value='ar.name' ><?php echo  PLEDIT_QS_AR ?></option>
               <option value='tr.name' ><?php echo  PLEDIT_QS_TR ?></option>
               <option value='al.name' ><?php echo  PLEDIT_QS_AL ?></option>
               <option value='ge.name' ><?php echo  PLEDIT_QS_GE ?></option>
               <option value='tr.time' ><?php echo  PLEDIT_QS_TI ?></option>
             </select>
             <input type=text name='val' size='12' maxlength=50 value=''  class=input_content>
             <input type=submit value='<?php echo  PLEDIT_QS_BTN ?>' class='btn_content'> 
           </td>
        </form>
        <form action='<?php echo $_SERVER['PHP_SELF']?>' method=get name='viewForm' onSubmit="return(Plist('view'));">
        <input type=hidden name='do' value='edit'>
            <td class="content" align=center nowrap colspan=2>
            	<?php echo  plistSelect($NETJUKE_SESSION_VARS["email"],$NETJUKE_SESSION_VARS["default_pl"]) ?>
            	<INPUT TYPE='submit' NAME='mode' VALUE='<?php echo  PLEDIT_TB_PLIST_BTN ?>' class='btn_content'>
            </td>
        </form>
        </tr>
        <tr>
           <td class="content" align=center nowrap><B><A HREF="play.php?do=plist&val=<?php echo $_REQUEST['pl_id']?>" title='<?php echo  PLEDIT_TB_PLAY_ALL_HELP ?>'><?php echo  PLEDIT_TB_PLAY_ALL ?></A></B></td>
           <td class="content" align=center nowrap><B><A HREF="Javascript:SelectAll();" title="<?php echo  PLEDIT_TB_SELECT_HELP ?>"><?php echo  PLEDIT_TB_SELECT ?></A></B></td>
           <td class="content" align=center nowrap><B><A HREF="Javascript:ResetAll();" title="<?php echo  PLEDIT_TB_RESET_HELP ?>"><?php echo  PLEDIT_TB_RESET ?></A></B></td>
           <td class="content" align=center nowrap><B><A HREF="Javascript:Plist('play');" title="<?php echo  PLEDIT_TB_PLAY_HELP ?>"><?php echo  PLEDIT_TB_PLAY ?></A></B></td>
           <td class="content" align=center nowrap><B><A HREF="Javascript:deleteTracks('');" title="<?php echo  PLEDIT_TB_DELETE_HELP ?>"><?php echo  PLEDIT_TB_DELETE ?></A></B></td>
           <td class="content" align=center nowrap><B><A HREF="Javascript:AdjustSeq();" title="<?php echo  PLEDIT_TB_ADJUST_HELP ?>"><?php echo  PLEDIT_TB_ADJUST ?></A></B></td>
           <td class="content" align=center nowrap><B><A HREF="Javascript:Plist('addto');" title="<?php echo  PLEDIT_TB_ADDTO_HELP ?>"><?php echo  PLEDIT_TB_ADDTO ?></A></B></td>
        </tr>
        </table>

        <?php echo  SpecialEditTB() ?>
        
        <BR>

        <table width='100%' border=0 cellspacing=1 cellpadding=3 class="border">   
        <form action='<?php echo $_SERVER['PHP_SELF']?>' method=post name='playForm'> 
        <input type=hidden name='do' value='save'>
        <input type=hidden name='pl_id' value='<?php echo $_REQUEST['pl_id']?>'>
        <input type=hidden name='pl_title' value="">
        <input type=hidden name='pl_shared' value="">
           <tr>
             <td class='header' width='4%' nowrap><B><?php echo  PLEDIT_COLS_OP ?></B></td>
             <td class='header' width='4%' nowrap><B><A CLASS="header" HREF="<?php echo $_SERVER['PHP_SELF']?>?do=<?php echo $_REQUEST['do']?>&pl_id=<?php echo $_REQUEST['pl_id']?>&sort=sq" title="<?php echo  PLEDIT_COLS_SQ_HELP ?>"><?php echo $columns[0]?></A></B></td>
             <td class='header' width='27%' nowrap><B><A CLASS="header" HREF="<?php echo $_SERVER['PHP_SELF']?>?do=<?php echo $_REQUEST['do']?>&pl_id=<?php echo $_REQUEST['pl_id']?>&sort=tr" title="<?php echo  PLEDIT_COLS_TR_HELP ?>"><?php echo $columns[1]?></A></B></td>
             <td class='header' width='5%' nowrap><B><A CLASS="header" HREF="<?php echo $_SERVER['PHP_SELF']?>?do=<?php echo $_REQUEST['do']?>&pl_id=<?php echo $_REQUEST['pl_id']?>&sort=ti" title="<?php echo  PLEDIT_COLS_TI_HELP ?>"><?php echo $columns[2]?></A></B></td>
             <td class='header' width='20%' nowrap><B><A CLASS="header" HREF="<?php echo $_SERVER['PHP_SELF']?>?do=<?php echo $_REQUEST['do']?>&pl_id=<?php echo $_REQUEST['pl_id']?>&sort=ar" title="<?php echo  PLEDIT_COLS_AR_HELP ?>"><?php echo $columns[3]?></A></B></td>
             <td class='header' width='28%' nowrap><B><A CLASS="header" HREF="<?php echo $_SERVER['PHP_SELF']?>?do=<?php echo $_REQUEST['do']?>&pl_id=<?php echo $_REQUEST['pl_id']?>&sort=al" title="<?php echo  PLEDIT_COLS_AL_HELP ?>"><?php echo $columns[4]?></A></B></td>
             <td class='header' width='2%' nowrap><B><A CLASS="header" HREF="<?php echo $_SERVER['PHP_SELF']?>?do=<?php echo $_REQUEST['do']?>&pl_id=<?php echo $_REQUEST['pl_id']?>&sort=tn" title="<?php echo  PLEDIT_COLS_TN_HELP ?>"><?php echo $columns[5]?></A></B></td>
             <td class='header' width='10%' nowrap><B><A CLASS="header" HREF="<?php echo $_SERVER['PHP_SELF']?>?do=<?php echo $_REQUEST['do']?>&pl_id=<?php echo $_REQUEST['pl_id']?>&sort=ge" title="<?php echo  PLEDIT_COLS_GE_HELP ?>"><?php echo $columns[6]?></A></B></td>
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
