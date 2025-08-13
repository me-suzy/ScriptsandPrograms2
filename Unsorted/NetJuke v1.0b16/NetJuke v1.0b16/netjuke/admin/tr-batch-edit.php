<?php

// defines if this script requires to be logged in
define( "PRIVATE", true );

# Call common libraries
require_once('../lib/inc-admin.php');
require_once(FS_PATH."/etc/locale/".LANG_PACK."/inc-admin_tr-batch-edit.php");

if ($_REQUEST['do'] == "del_tr") {
########################################
# BATCH DELETE TRACKS
########################################
#  Usage:
#  - Delete: ?do=del_tr&val=1009,1001,1975
########################################
    
  if ($_REQUEST['val'] != '') {

    $pl_ids = array();
      
    $track_cache = array();
    
    // get the ids
    $id = split(",",$_REQUEST['val']);
  
    foreach ($id as $this_id) {
      
      // get the pl_id for each playlist containing this track
      $dbrs = $dbconn->Execute("select pl_id from netjuke_plists_tracks where tr_id = '$this_id'");
      
      while (!$dbrs->EOF) {
      
        // add the id as a unique key
        $pl_ids[$dbrs->fields[0]] = '';
      
        $dbrs->MoveNext();
      
      }
      
      $dbrs->Close();
      
      // delete the track from all playlists
      $dbconn->Execute("delete from netjuke_plists_tracks where tr_id = '$this_id'");
      
      $dbrs = $dbconn->Execute("select ar_id, al_id, ge_id from netjuke_tracks where id = '$this_id'");
      
      if ($dbrs->RecordCount() != 0) {
      
        $track_cache['netjuke_artists'][$dbrs->fields[0]]++;
        $track_cache['netjuke_albums'][$dbrs->fields[1]]++;
        $track_cache['netjuke_genres'][$dbrs->fields[2]]++;
        
        // delete the track
        $dbconn->Execute("delete from netjuke_tracks where id = '$this_id'");
      
      }
      
      $dbrs->Close();

    }
    
    // if we had some playlists
    if (count($pl_ids) > 0) {
    
      foreach ($pl_ids as $key => $val) {
      
        $dbrs = $dbconn->Execute("select count(id) from netjuke_plists_tracks where pl_id = '$key'");
        
        // if the playlist doesn't have anymore tracks
        if ($dbrs->RecordCount() == 1) {
          
          if ($dbrs->fields[0] == 0) {
        
            // delete the potential saved shared playlists
            $dbconn->Execute("delete from netjuke_plists_fav where pl_id = '$key'");
            
            // delete the playlist
            $dbconn->Execute("delete from netjuke_plists where id = '$key'");
          
          }
        
        }
      
        $dbrs->Close();
      
      }
    
    }
    
    // update the track cnt cache
    track_cache_batch('decrement', $track_cache);

  } else {

    // no track submitted
    alert (TRBEDIT_NOTRID);
    exit;

  }
  
  // done, just go to the index
  javascript("alert('".TRBEDIT_EDITED_MSG."');self.location.href = \"".WEB_PATH."/index.php\";");

  exit;

} elseif ($_REQUEST['do'] == "save") {
########################################
# BATCH SAVE TRACKS
########################################

  // START SINGLE RECORD MAINTENANCE

    // START ARTIST MAINTENANCE
    
      if (abs($_REQUEST['ar_do']) == 1) {
  
        if ($_REQUEST['ar_name'] != '') { 
          list($ar_id,$errors) = Find_Id_TrBatchEdit('netjuke_artists',$_REQUEST['ar_name']);
        } else {
          $ar_id = $_REQUEST['ar_id'];
        }
   
      }
  
      $ar_id = (int) $ar_id;
    
    // END ARTIST MAINTENANCE

    // START ALBUM MAINTENANCE
    
      if (abs($_REQUEST['al_do']) == 1) {
  
        if ($_REQUEST['al_name'] != '') {
          list($al_id,$errors) = Find_Id_TrBatchEdit('netjuke_albums',$_REQUEST['al_name']);
        } else {
          $al_id = $_REQUEST['al_id'];
        }
   
      }
  
      $al_id = (int) $al_id;
    
    // END ALBUM MAINTENANCE

    // START GENRE MAINTENANCE
    
      if (abs($_REQUEST['ge_do']) == 1) {
  
        if ($_REQUEST['ge_name'] != '') {
          list($ge_id,$errors) = Find_Id_TrBatchEdit('netjuke_genres',$_REQUEST['ge_name']);
        } else {
          $ge_id = $_REQUEST['ge_id'];
        }
   
      }
  
      $ge_id = (int) $ge_id;
    
    // END GENRE MAINTENANCE

  // END SINGLE RECORD MAINTENANCE

  // START MULTIPLE RECORD MAINTENANCE
  
    if ( is_array($_REQUEST['tr_ids']) ) {
    
      $track_cache_decr = array();
      $track_cache_incr = array();
      
      foreach ($_REQUEST['tr_ids'] as $tr_id) {
          
        $tr_id = (int) $tr_id;
        
        if (abs($tr_id) != 0) {
      
          $dbrs = $dbconn->Execute("select ar_id, al_id, ge_id from netjuke_tracks where id = '$tr_id'");
        
          if ($dbrs->RecordCount() != 0) {
        
            $ar_id_prev = $dbrs->fields[0];
            $al_id_prev = $dbrs->fields[1];
            $ge_id_prev = $dbrs->fields[2];
        
          }
        
          $dbrs->Close();
          
          $cols = array();
          
          // track name
          if ( (abs($_REQUEST['tr_do']) == 1) && (strlen($_REQUEST['tr_name']) != 0) ) {
            $cols[] = " name = '".raw_to_db($_REQUEST['tr_name'])."' ";
          }
          
          // track ar_id
          if ( (abs($_REQUEST['ar_do']) == 1) && (abs($ar_id) != 0) ) {
            $cols[] = " ar_id = '$ar_id' ";
            $track_cache_decr['netjuke_artists'][abs($ar_id_prev)]++;
            $track_cache_incr['netjuke_artists'][abs($ar_id)]++;
          }
          
          // track al_id
          if ( (abs($_REQUEST['al_do']) == 1) && (abs($al_id) != 0) ) {
            $cols[] = " al_id = '$al_id' ";
            $track_cache_decr['netjuke_albums'][abs($al_id_prev)]++;
            $track_cache_incr['netjuke_albums'][abs($al_id)]++;
          }
          
          // track ge_id
          if ( (abs($_REQUEST['ge_do']) == 1) && (abs($ge_id) != 0) ) {
            $cols[] = " ge_id = '$ge_id' ";
            $track_cache_decr['netjuke_genres'][abs($ge_id_prev)]++;
            $track_cache_incr['netjuke_genres'][abs($ge_id)]++;
          }
          
          // track location
          if ( (abs($_REQUEST['tl_do']) == 1) && (strlen($_REQUEST['tr_location']) != 0) ) {
            $cols[] = " location = '".specialUrlEncode(separatorCleanup($_REQUEST['tr_location']))."' ";
          }
          
          // track dl_cnt
          if ( abs($_REQUEST['dl_cnt_reset']) == 1 ) {
            $cols[] = " dl_cnt = 0 ";
          }
          
          // track comments
          if ( (abs($_REQUEST['tr_com_do']) == 1) ) {
            $cols[] = " comments = '".raw_to_db($_REQUEST['tr_com'])."' ";
          }
          
          // track lyrics
          if ( (abs($_REQUEST['tr_lyr_do']) == 1) ) {
            $cols[] = " lyrics = '".raw_to_db($_REQUEST['tr_lyr'])."' ";
          }
      
          if (count($cols) > 0) {
          
            $sql  = " update netjuke_tracks set "
                  . " ".implode(", ",$cols)." "
                  . " where id = $tr_id ";
            
            $dbconn->Execute($sql);
          
          }
          
          // track img_src
          if ( abs($_REQUEST['tr_img_do']) == 1 ) {
            Update_Image('tr', $tr_id, $_REQUEST['tr_img']);
          }

        }
      
      }
      
      // update track cnt cache if necessary
      
      if (    (count($track_cache_decr) > 0)
           && (count($track_cache_incr) > 0) ) {
      
        track_cache_batch('decrement', $track_cache_decr);
        track_cache_batch('increment', $track_cache_incr);
      
      }
    
    }

  // END MULTIPLE RECORD MAINTENANCE

  CacheCleanUp();
  
  javascript("alert('".TRBEDIT_EDITED_MSG."');self.close();");

} elseif ($_REQUEST['do'] == "edit") {
########################################
# BATCH EDIT TRACKS
########################################
#  Usage:
#  - Edit: pl-edit.php?do=edit&val=1001,1263,139
########################################

  if ($_REQUEST['val'] != '') {

    $pl_ids = array();
    
    // get the ids
    $id = split(",",$_REQUEST['val']);
    
    $scnt = count($id);
  
    $tr_field_list = "";
    
    foreach ($id as $this_id) {
    
      $this_id = (int) $this_id;
      
      if ($this_id > 0) $tr_field_list .= "<input type='hidden' name='tr_ids[]' value='$this_id'>\r\n";
    
    }

  } else {

    // no track submitted
    javascript ("alert('".TRBEDIT_NOTRID."');self.close();");
    exit;

  }

########################################

?>


<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN"
			"http://www.w3.org/TR/REC-html40/loose.dtd">

<HTML>
<HEAD>
	<meta HTTP-EQUIV="Pragma" CONTENT="no-cache">
	<meta http-equiv="Content-Type" content="text/html; charset=<?php echo  LANG_CHARSET ?>">
	<TITLE><?php echo  TRBEDIT_HEADER ?></TITLE>
    <style type="text/css">
      <?php require_once(FS_PATH."/lib/inc-css.php"); ?>
    </style>
</HEAD>
<BODY BGCOLOR='#<?php echo $NETJUKE_SESSION_VARS["bgcolor"]?>' TEXT='#<?php echo $NETJUKE_SESSION_VARS["text"]?>' LINK='#<?php echo $NETJUKE_SESSION_VARS["link"]?>' ALINK='#<?php echo $NETJUKE_SESSION_VARS["alink"]?>' VLINK='#<?php echo $NETJUKE_SESSION_VARS["vlink"]?>' ONLOAD='self.focus();'>
	<a name="PageTop"></a>

	<div align=center>
		<table width='100%' border=0 cellspacing=1 cellpadding=3 class='border'>
			<tr>
				<td align=left class="header" colspan=2><b><?php echo  TRBEDIT_HEADER." (".$scnt." ".TRBEDIT_SELECTED.")" ?></b></td>
			</tr>
		<form action='<?php echo $_SERVER['PHP_SELF']?>' method=post name='trackForm'>
        <input type=hidden name='do' value='save'>
        <?php echo  $tr_field_list ?>
			<tr>
				<td width="30%" align=right valign=top class="content" nowrap>
				  <?php echo  TRBEDIT_FORM_TR ?>
				  <input type="checkbox" name="tr_do" value="1">
				</td>
				<td width="70%" align=left valign=top class="content">
				  <input type="text" name="tr_name" value="" size="35" maxlength="100" class=input_content onChange="document.trackForm.tr_do.checked = true;">
				</td>
			</tr>
			<tr>
				<td width="30%" align=right valign=top class="content" nowrap>
				  <?php echo  TRBEDIT_FORM_AR ?>
				  <input type="checkbox" name="ar_do" value="1">
				</td>
				<td width="70%" align=left valign=top class="content">
				  <?php echo pairSelect('netjuke_artists','ar_id',1,"document.trackForm.ar_do.checked = true;")?>
				  <br>
				  <input type="text" name="ar_name" value="" size="35" maxlength="100" class=input_content onChange="document.trackForm.ar_do.checked = true;">
				</td>
			</tr>
			<tr>
				<td width="30%" align=right valign=top class="content" nowrap>
				  <?php echo  TRBEDIT_FORM_AL ?>
				  <input type="checkbox" name="al_do" value="1">
				</td>
				<td width="70%" align=left valign=top class="content">
				  <?php echo pairSelect('netjuke_albums','al_id',1,"document.trackForm.al_do.checked = true;")?>
				  <br>
				  <input type="text" name="al_name" value="" size="35" maxlength="100" class=input_content onChange="document.trackForm.al_do.checked = true;">
				</td>
			</tr>
			<tr>
				<td width="30%" align=right valign=top class="content" nowrap>
				  <?php echo  TRBEDIT_FORM_GE ?>
				  <input type="checkbox" name="ge_do" value="1">
				</td>
				<td width="70%" align=left valign=top class="content">
				  <?php echo pairSelect('netjuke_genres','ge_id',1,"document.trackForm.ge_do.checked = true;")?>
				  <br>
				  <input type="text" name="ge_name" value="" size="35" maxlength="100" class=input_content onChange="document.trackForm.ge_do.checked = true;">
				</td>
			</tr>
			<tr>
				<td width="30%" align=right valign=top class="content" nowrap>
				  <?php echo  TRBEDIT_FORM_FN ?>
				  <input type="checkbox" name="tl_do" value="1">
				</td>
				<td width="70%" align=left valign=top class="content">
				  <input type="text" name="tr_location" value="" size="35" maxlength="256" class=input_content onChange="document.trackForm.tl_do.checked = true;">
				</td>
			</tr>
			<tr>
				<td width="30%" align=right valign=top class="content" nowrap>
				  <?php echo  TRBEDIT_FORM_TR_IMG ?>
				  <input type="checkbox" name="tr_img_do" value="1">
				</td>
				<td width="70%" align=left valign=top class="content">
				  <input type="text" name="tr_img" value="" size="35" maxlength="256" class=input_content onChange="document.trackForm.tr_img_do.checked = true;">
				</td>
			</tr>
			<tr>
				<td width="30%" align=right valign=top class="content" nowrap><?php echo  TRBEDIT_FORM_LC ?></td>
				<td width="70%" align=left valign=top class="content">
				  <input type="checkbox" name="dl_cnt_reset" value="1"><?php echo  TRBEDIT_FORM_RESET ?></td>
				</td>
			</tr>
			<tr>
				<td width="30%" align=right valign=top class="content" nowrap>
				  <?php echo  TRBEDIT_FORM_TR_COM ?>
				  <input type="checkbox" name="tr_com_do" value="1">
				</td>
				<td width="70%" align=left valign=top class="content">
				  <textarea name="tr_com" rows="10" cols="35" class=input_content onChange="document.trackForm.tr_com_do.checked = true;"></textarea>
				</td>
			</tr>
			<tr>
				<td width="30%" align=right valign=top class="content" nowrap>
				  <?php echo  TRBEDIT_FORM_TR_LYR ?>
				  <input type="checkbox" name="tr_lyr_do" value="1">
				</td>
				<td width="70%" align=left valign=top class="content">
				  <textarea name="tr_lyr" rows="10" cols="35" class=input_content onChange="document.trackForm.tr_lyr_do.checked = true;"></textarea>
				</td>
			</tr>
			<tr>
				<td colspan=2 align=center valign=middle class="content">
				  <input type=submit name="btn_update" value='<?php echo  TRBEDIT_FORM_BTN_SAVE ?>' class='btn_content'>
				  <input type=reset value='<?php echo  TRBEDIT_FORM_BTN_RESET ?>' class='btn_content'>
				</td>
			</tr>
		</form>
		</table>

		<br>
		<table width='100%' border=0 cellspacing=1 cellpadding=3 class="border">
			<tr>
				<td width="" align="center" class="content"><a href="<?php echo WEB_PATH.'/play.php?do=play&val='.$_REQUEST['val']?>" title="<?php echo  TRBEDIT_PLAY_HELP ?>"><b><?php echo  TRBEDIT_PLAY ?></b></a></td>
				<td width="" align="center" class="content"><a href="javascript:window.close();" title="<?php echo  TRBEDIT_CLOSEWIN_HELP ?>"><b><?php echo  TRBEDIT_CLOSEWIN ?></b></a></td>
			</tr>
		</table>
	<div>

</BODY>
</HTML>
   
<?php

   exit;

} else {

   javascript ("self.close();");

   exit;

}

##################################################

function Find_Id_TrBatchEdit($table,$value) {

   GLOBAL $dbconn;
   
   $value = raw_to_db($value);

   if (strlen($value) > 0) {

     $select_sql = "SELECT id FROM $table WHERE UPPER(name) = '".strtoupper($value)."'";
     $insert_sql = "INSERT INTO $table (name) VALUES ('$value')";

     $dbrs = $dbconn->Execute($select_sql);

     if ($dbrs->RecordCount() < 1) {
       if ($dbconn->Execute($insert_sql) === false) {
	     $errors .= "- ".TRBEDIT_ERROR_INSERT." $table ($value): ".$dbconn->ErrorMsg()."\\n";
       } else {
         $dbrs = $dbconn->Execute($select_sql);
       }
     }

     return array($dbrs->fields[0],$errors);

   } else {

     return array(0,$errors);

   }

}

##################################################

?>