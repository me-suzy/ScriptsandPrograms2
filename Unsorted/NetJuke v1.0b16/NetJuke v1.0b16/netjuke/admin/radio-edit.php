<?php

// defines if this script requires to be logged in
define( "PRIVATE", true );

# Call common libraries
require_once('../lib/inc-admin.php');
require_once(FS_PATH."/etc/locale/".LANG_PACK."/inc-admin_radio-edit.php");

if ($_REQUEST['do'] == "edit") {

  editRadioPlist($_REQUEST['val'],$_REQUEST['replace']);

} else {

   header ("Location: ".WEB_PATH."/index.php\n\n");

   exit;

}

########################################

function editRadioPlist($val,$replace) {
  
  GLOBAL $dbconn, $NETJUKE_SESSION_VARS;
  
  // limited to admin only (open to editors?)
  if ($NETJUKE_SESSION_VARS['gr_id'] != 1) header("Location:".WEB_PATH);

  if ($val != '') {
    
    $id = split(",",$val);
    
    $prev_id = 0;
  
    $playlist = array();
    
    foreach ($id as $this_id) {
      
      // we can't add remote files t a radio, so scan for ://
      $sql = " select location, dl_cnt from netjuke_tracks where id = '$this_id' and location not like '%://%' ";
      
      $dbrs = $dbconn->Execute($sql);
      
      if ($dbrs->RecordCount() > 0) {
      
        $tr_location  = rawurldecode(separatorCleanup($dbrs->fields[0]));
        $tr_dl_cnt    = $dbrs->fields[1];
        
        // update the track's download count.
        // Used to be done in dispatcher, but it is now optional...
        if ($prev_id != $this_id) $dbconn->Execute( "update netjuke_tracks set dl_cnt = ".($tr_dl_cnt + 1)." where id = ".$this_id." " );
        
        $tr_location  = str_replace('//','/',MUSIC_DIR."/".$tr_location);
        
        $playlist[] = $tr_location;
      
      }
      
      $dbrs->Close();
      
      $prev_id = $this_id;

    }

  } else {

    alert (PLAYER_NOID);
    exit;

  }

  if (RADIO_TYPE == 'QTSS4') {
  
    $formatted_playlist = format_plist_QTSS4( $playlist, $replace );
  
  } else {
  
    $formatted_playlist = format_plist_text( $playlist, $replace );
  
  }

  // start saving to the streamer's playlist.
  // if writable, check for RADIO_PLIST
  
  // check for append or replace
  // make sure not to let the user set the
  // mode string directly
  if ($replace == 1) {
    $mode = 'w';
  } else {
    $mode = 'a';
  }
  
  // make sure file is writable
  
  $writable = false;
  
  if (RADIO_PLIST != '') {

    if (@touch(RADIO_PLIST)) {

      $writable = true;

    } else {

      alert(RADIO_LIST_NOPERM);
      exit;
    
    }

  } else {

    alert(RADIO_LIST_NOPATH);
    exit;

  }
  
  if ($writable == true) {

    if (RADIO_TYPE == 'QTSS4') {
    
      save_plist_QTSS4( $mode, $formatted_playlist );
    
    } else {
    
      save_plist_text( $mode, $formatted_playlist );
    
    }
  
  }
  
  alert(RADIO_LIST_DONE);

  exit;

}

########################################

function save_plist_QTSS4($mode,$formatted_playlist) {
  
  // open file pointer in appropriate mode
  $fp = fopen( RADIO_PLIST, $mode );
  
  // write playlist to file
  $fp = fwrite( $fp, $formatted_playlist );
  
  fclose($fp);
  
  $path_parts = explode('/',RADIO_PLIST);
  $basename = array_pop($path_parts);
  $dirname = implode('/',$path_parts);
  
  $temp_vals = explode('.',$basename);
  
  if (@is_writeable($dirname)) {
  
    $pl_name = $temp_vals[0];
    
    $replace_list = $dirname.'/'.$pl_name.'.replacelist';
    $insert_list = $dirname.'/'.$pl_name.'.insertlist';
    
    if ($mode == 'w') {
      if (@touch($insert_list)) unlink($insert_list);
      $extra_file = $replace_list;
    } else {
      if (@touch($replace_list)) unlink($replace_list);
      $extra_file = $insert_list;
      $formatted_playlist = "*PLAY-LIST*\n".$formatted_playlist;
    }
    
    // create the .replacelist or .insertlist file
    $fp = fopen( $extra_file, $mode );
    
    // write playlist to file
    $fp = fwrite( $fp, $formatted_playlist );
    
    fclose($fp);
  
  }
  
}

########################################

function format_plist_QTSS4($playlist,$replace) {

  $formatted_playlist = "";

  if ($replace == 1) {
  
    $formatted_playlist .= "*PLAY-LIST*\n";
    $formatted_playlist .= "#\n";
    $formatted_playlist .= "# Created with the Artekopia Netjuke\n";
    $formatted_playlist .= "#\n";
  
  }

  foreach ($playlist as $location) {
  
    $formatted_playlist .= "\"".$location."\" 5\n";
  
  }
  
  return $formatted_playlist;

}

########################################

function save_plist_text($mode,$formatted_playlist) {
  
  // open file pointer in appropriate mode
  $fp = fopen( RADIO_PLIST, $mode );
  
  // write playlist to file
  $fp = fwrite( $fp, $formatted_playlist );

}

########################################

function format_plist_text($playlist,$replace) {

  $formatted_playlist = "";

  foreach ($playlist as $location) {
  
    $formatted_playlist .= $location."\n";
  
  }
  
  return $formatted_playlist;

}

########################################

?>