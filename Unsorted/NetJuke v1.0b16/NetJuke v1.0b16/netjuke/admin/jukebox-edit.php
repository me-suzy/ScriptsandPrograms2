<?php

// defines if this script requires to be logged in
define( "PRIVATE", true );

# Call common libraries
require_once('../lib/inc-admin.php');
require_once(FS_PATH."/etc/locale/".LANG_PACK."/inc-admin_jukebox-edit.php");

########################################

if ($_REQUEST['do'] == "edit") {

  editJukeboxPlist($_REQUEST['val'],$_REQUEST['replace']);

} elseif (    ($_REQUEST['do'] == "start")
           || ($_REQUEST['do'] == "stop") ) {

  choosePlayer($_REQUEST['do']);

} else {

   header ("Location: ".WEB_PATH."/index.php\n\n");

   exit;

}

########################################

function editJukeboxPlist($val,$replace) {
  
  GLOBAL $dbconn, $NETJUKE_SESSION_VARS;
  
  // limited to admin only (open to editors?)
  if ($NETJUKE_SESSION_VARS['gr_id'] != 1) header("Location:".WEB_PATH);

  if ($val != '') {
    
    $id = split(",",$val);
    
    $prev_id = 0;
  
    $playlist = array();
    
    foreach ($id as $this_id) {
      
      $sql = " select location, dl_cnt from netjuke_tracks where id = '$this_id' ";
      
      $dbrs = $dbconn->Execute($sql);
      
      if ($dbrs->RecordCount() > 0) {

        $tr_location  = rawurldecode($dbrs->fields[0]);
        $tr_dl_cnt    = $dbrs->fields[1];
        
        // update the track's download count.
        // Used to be done in dispatcher, but it is now optional...
        if ($prev_id != $this_id) $dbconn->Execute( "update netjuke_tracks set dl_cnt = ".($tr_dl_cnt + 1)." where id = ".$this_id." " );
        
        if (!strstr($tr_location,'://')) $tr_location = MUSIC_DIR.'/'.$tr_location;
        
        $playlist[] = trim(separatorCleanup($tr_location));
      
      }
      
      $dbrs->Close();
      
      $prev_id = $this_id;

    }

  } else {

    alert (PLAYER_NOID);
    exit;

  }

  // start saving to the streamer's playlist.
  // if writable, check for JUKEBOX_PLIST
  
  // make sure file is writable
  
  $writable = false;
  
  if (JUKEBOX_PLIST != '') {

    if (@touch(JUKEBOX_PLIST)) {

      $writable = true;

    } else {

      alert(JUKEBOX_LIST_NOPERM);
      exit;
    
    }

  } else {

    alert(JUKEBOX_LIST_NOPATH);
    exit;

  }
  
  if ($writable == true) {

    save_plist_text( $replace, $playlist );
  
  }
  
  alert(JUKEBOX_LIST_DONE);

  exit;

}

########################################

function save_plist_text($replace,$playlist) {
  
  // check for append or replace
  // make sure not to let the user set the
  // mode string directly
  if ($replace == 1) {
    $mode = 'w';
  } else {
    $mode = 'a';
  }
  
  if (count($playlist) > 0) {
    
    // open file pointer in appropriate mode
    $fp = fopen( JUKEBOX_PLIST, $mode );
    
    $formatted = implode("\n",$playlist);
    if (substr($formatted,-1) != "\n") $formatted .= "\n";
    
    // write playlist to file
    $fp = fwrite( $fp, $formatted );
    
    if ($replace == 1) {
      // choosePlayer('start'); // would restart
    } else {
      if (JUKEBOX_PLAYER == 'winamp') {
        playWithWINAMP('add',$playlist);
      }
    }
  
  }

}

########################################

function choosePlayer($do = 'stop') {

  if ($do != 'start') $do = 'stop';
  
  if (@file_exists(JUKEBOX_PLAYER_PATH)) {

    switch (JUKEBOX_PLAYER) {
      case ('mpg123'):
        playWithMPG123($do);
        break;
      case ('mpg321'):
        playWithMPG123($do);
        break;
      case ('ogg123'):
        playWithOGG123($do);
        break;
      case 'winamp':
        playWithWINAMP($do);
        break;
    }
    
    if ($do == 'start') {
      alert(JUKEBOX_START_DONE);
    } else {
      alert(JUKEBOX_STOP_DONE);
    }
  
  } else {
  
    alert(JUKEBOX_NOPLAYER);
  
  }
  
  if ($status != $do) {
    alert($status);
  } else {
    javascript('self.history.go(-1);');
  }

}

########################################

function playWithMPG123($do = 'stop') {

  if ($do == 'start') {

    if (!@file_exists(JUKEBOX_PLAYER_PID)) { 
      exec(JUKEBOX_PLAYER_PATH.' -q -@ '.escapeshellarg(JUKEBOX_PLIST).' >/dev/null &');
      saveJukeboxPid();
    } else {
      playWithMPG123('stop');
      playWithMPG123('start');
    }   
  
  } else {
    
    if (@file_exists(JUKEBOX_PLAYER_PID)) {
      killJukeboxPid();
    }
  
  }

}

########################################

function playWithOGG123($do = 'stop') {

  if ($do == 'start') {

    if (!@file_exists(JUKEBOX_PLAYER_PID)) { 
      $lines = @file(JUKEBOX_PLIST);
      $list = '';
      foreach ($lines as $line) {
        $list .= " '".escapeshellarg(trim($line))."'";
      }
      if ($list != '') {
        exec(JUKEBOX_PLAYER_PATH.' -q '.$list.' >/dev/null &');
        saveJukeboxPid();
      }
    } else {
      playWithOGG123('stop');
      playWithOGG123('start');
    }   
  
  } else {

    if (@file_exists(JUKEBOX_PLAYER_PID)) {
      killJukeboxPid();
    }
  
  }

}

########################################

function playWithWINAMP($do = 'stop', $playlist = array()) {

  if ($do == 'start') {
    exec('"'.JUKEBOX_PLAYER_PATH.'" '.JUKEBOX_PLIST);
  } elseif ($do == 'add') {
    if (count($playlist) > 0) {
      exec('"'.JUKEBOX_PLAYER_PATH.'" /ADD "'.implode('" "',$playlist).'"');
    }
  } else {
    exec('"'.JUKEBOX_PLAYER_PATH.'" /STOP');
  }

}

########################################

function saveJukeboxPid() {

  $temp_1 = explode('/',JUKEBOX_PLAYER_PATH);
  $player = array_pop($temp_1);
  unset($temp_1);
  
  exec('ps -axc',$temp_2);

  foreach($temp_2 as $key => $val) {
    if (stristr($val,$player)) {
      $pid_str = $val;
      break;
    }
  }
  
  $pat = "/^\s*(\d*).*/";
  preg_match($pat,$pid_str,$matches);
  $pid = array_pop($matches);
    
  $fp = fopen( JUKEBOX_PLAYER_PID, 'w' );
  
  fwrite( $fp, $pid );
    
  fclose($fp);

}

########################################

function getJukeboxPid() {

  $fp = fopen( JUKEBOX_PLAYER_PID, 'r' );
  
  $pid = fread($fp, filesize(JUKEBOX_PLAYER_PID));
  
  fclose($fp);
  
  return $pid;

}

########################################

function killJukeboxPid() {

  $temp_1 = explode('/',JUKEBOX_PLAYER_PATH);
  $player = array_pop($temp_1);
  unset($temp_1);

  exec('ps -axc',$temp);
  
  $pat = "/^\D*(\d*)\D*/";
  
  foreach($temp as $key => $val) {
  
    if (stristr($val,trim($player))) {
  
      $matches = array();
      preg_match($pat,$val,$matches);
  
      foreach ($matches as $pid) {
  
          @exec ("kill $pid");
          // alert ($pid.'\n');
  
      }
  
    }
  
  }
  
  @unlink(JUKEBOX_PLAYER_PID);

}

########################################

?>>