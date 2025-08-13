<?php

// defines if this script requires to be logged in
define( "PRIVATE", false );

# Call common libraries
require_once('./lib/inc-common.php');
require_once(FS_PATH."/etc/locale/".LANG_PACK."/inc-play.php");

if ( ($_REQUEST['do'] == "play") || ($_REQUEST['do'] == "plist") ) {

  m3uMe($_REQUEST['do'],$_REQUEST['val']);

} elseif ($_REQUEST['do'] == "play_all") {

  m3uAll($_REQUEST['type'],$_REQUEST['id']);

} elseif ($_REQUEST['do'] == "dispatch") {

  dispatchMe($_REQUEST['val'],$_REQUEST['orig_session_id']);

} elseif ($_REQUEST['do'] == "radio") {

  radioMe($_REQUEST['val']);

} else {

   header ("Location: index.php\n\n");

   exit;

}

########################################

function playMe($encoded_url) {
     
  GLOBAL $dbconn;
  
  $decoded = separatorCleanup(obfuscate_undo($encoded_url));
  
  $dbconn->Execute( "update netjuke_tracks set dl_cnt = dl_cnt + 1 where location = '".str_replace(STREAM_SRVR."/","",$decoded)."' " );

  header ( "Location: ".$decoded );

}

########################################

function m3uMe($do,$val) {
     
  GLOBAL $dbconn, $NETJUKE_SESSION_VARS;

  if ($val != '') {

    if ($do == "plist") $val = get_pl_tracks(abs($val));
    
    $id = split(",",$val);

    $trans = get_html_translation_table (HTML_ENTITIES);
    $trans = array_flip ($trans);
    $data = strtr($data,$trans);
  
    foreach ($id as $this_id) {
      
      $sql = " SELECT tr.location, tr.time, tr.name, ar.name, al.name  "
           . " from netjuke_tracks tr, netjuke_artists ar, netjuke_albums al "
           . " where tr.id = $this_id and ar.id = tr.ar_id and al.id = tr.al_id ";
      
      $dbrs = $dbconn->Execute($sql);
      
      $tr_location  = separatorCleanup($dbrs->fields[0]);
      $tr_time      = floor($dbrs->fields[1]);
      $tr_name      = strtr(format_for_display($dbrs->fields[2]),$trans);
      $ar_name      = strtr(format_for_display($dbrs->fields[3]),$trans);
      $al_name      = strtr(format_for_display($dbrs->fields[4]),$trans);
      
      # Append streaming server value if location doesn't contain ://
      # Scanning for :// only because full protocol could be http://, https://, rtsp://, etc.
      # This enables to add internet radio stations or files streamed from multiple servers by
      # simply having a full url (eg: http://other.host.dom/path/to/media/file.mp3) in the
      # track's location field.
      if (!strstr($tr_location,"://")) $tr_location = STREAM_SRVR."/".$tr_location;
      
      # Append web path value if location still doesn't contain ://
      # Copes with the fact that the streaming server could be a relative path (eg: music/)
      # instead of a protocol+hostname combination (http://streaming.host.dom)
      if (!strstr($tr_location,"://")) $tr_location  = WEB_PATH."/".$tr_location;
      
      $tr_location = separatorCleanup($tr_location);
      
      if ( stristr(strtolower($_SERVER['HTTP_USER_AGENT']),"windows") ) $playlist .= "#EXTINF:$tr_time,$tr_name - $ar_name ($al_name)\r\n";
      
      if (PROTECT_MEDIA == 't') {
        $playlist .= WEB_PATH."/play.php?do=dispatch&val=".obfuscate_apply($tr_location)."&orig_session_id=".$NETJUKE_SESSION_VARS['session_id']."\r\n";
      } else {
        $playlist .= $tr_location."\r\n";
        // update the track's download count.
        // Is normally done accurately in the dispatcher, but it is now optional...
        $dbconn->Execute( "update netjuke_tracks set dl_cnt = dl_cnt + 1 where id = ".$this_id." " );
      }
      
      $dbrs->Close();
      
      //echo $tr_dl_cnt;

    }

  } else {

    alert (PLAYER_NOID);
    exit;

  }
  
  header ("Content-type: audio/x-mpegurl\r\nContent-Disposition: inline; filename=netjuke-".substr(time(),-7).".m3u" ); 

  if ( stristr(strtolower($_SERVER['HTTP_USER_AGENT']),"windows") ) echo "#EXTM3U\r\n";
  echo $playlist;

  exit;

}

########################################

function m3uAll($type, $id) {
     
  GLOBAL $dbconn, $NETJUKE_SESSION_VARS;

    $trans = get_html_translation_table (HTML_ENTITIES);
    $trans = array_flip ($trans);
    $data = strtr($data,$trans);
      
    $sql = " SELECT tr.location, tr.time, tr.name, ar.name, al.name  "
         . " from netjuke_tracks tr, netjuke_artists ar, netjuke_albums al "
         . " where ar.id = tr.ar_id and al.id = tr.al_id ";
    
    if (    ( ($type =='ar') || ($type =='al') || ($type =='ge') )
         && ( is_numeric($id) )  ) {
      
      $sql .= ' and tr.'.$type.'_id = '.$id.' ';
    
    }
    
    $sql .= " order by al.name, tr.track_number, ar.name, tr.id ";
      
    $dbrs = $dbconn->Execute($sql);
  
    header ("Content-type: audio/x-mpegurl\r\nContent-Disposition: inline; filename=netjuke-".substr(time(),-7).".m3u" ); 
    if ( stristr(strtolower($_SERVER['HTTP_USER_AGENT']),"windows") ) echo "#EXTM3U\r\n";
  
    while (!$dbrs->EOF) {
      
      $tr_location  = separatorCleanup($dbrs->fields[0]);
      $tr_time      = floor($dbrs->fields[1]);
      $tr_name      = strtr(format_for_display($dbrs->fields[2]),$trans);
      $ar_name      = strtr(format_for_display($dbrs->fields[3]),$trans);
      $al_name      = strtr(format_for_display($dbrs->fields[4]),$trans);
      
      # Append streaming server value if location doesn't contain ://
      # Scanning for :// only because full protocol could be http://, https://, rtsp://, etc.
      # This enables to add internet radio stations or files streamed from multiple servers by
      # simply having a full url (eg: http://other.host.dom/path/to/media/file.mp3) in the
      # track's location field.
      if (!strstr($tr_location,"://")) $tr_location = STREAM_SRVR."/".$tr_location;
      
      # Append web path value if location still doesn't contain ://
      # Copes with the fact that the streaming server could be a relative path (eg: music/)
      # instead of a protocol+hostname combination (http://streaming.host.dom)
      if (!strstr($tr_location,"://")) $tr_location  = WEB_PATH."/".$tr_location;
      
      $tr_location = separatorCleanup($tr_location);
      
      if ( stristr(strtolower($_SERVER['HTTP_USER_AGENT']),"windows") ) echo "#EXTINF:$tr_time,$tr_name - $ar_name ($al_name)\r\n";
      
      if (PROTECT_MEDIA == 't') {
        echo WEB_PATH."/play.php?do=dispatch&val=".obfuscate_apply($tr_location)."&orig_session_id=".$NETJUKE_SESSION_VARS['session_id']."\r\n";
      } else {
        echo $tr_location."\r\n";
        // update the track's download count.
        // Is normally done accurately in the dispatcher, but it is now optional...
        $dbconn->Execute( "update netjuke_tracks set dl_cnt = dl_cnt + 1 where id = ".$this_id." " );
      }
      
      //echo $tr_dl_cnt;
      
      $dbrs->MoveNext();

    }
      
      $dbrs->Close();

  exit;

}

########################################

function dispatchMe($encoded_url,$orig_session_id) {

   GLOBAL $dbconn;
   
   $dbrs = $dbconn->Execute(" select session_id from netjuke_sessions where session_id = '$orig_session_id' ");
   
   if ($dbrs->RecordCount() != 1) exit;
   
   // The following browser checks are only as secure as the concept
   // of user agents itself... we should get a list of acceptable players'
   // user agents and check against this instead, but older versions of
   // itunes didn't have a user agent
   if (    ( $_SERVER['HTTP_USER_AGENT'] != "")
        && ( (substr_count(strtolower($_SERVER['HTTP_USER_AGENT']),"mozilla")) < 1)
        && ( (substr_count(strtolower($_SERVER['HTTP_USER_AGENT']),"netscape")) < 1)
        && ( (substr_count(strtolower($_SERVER['HTTP_USER_AGENT']),"msie")) < 1) 
        && ( (substr_count(strtolower($_SERVER['HTTP_USER_AGENT']),"wget")) < 1) 
        && ( (substr_count(strtolower($_SERVER['HTTP_USER_AGENT']),"curl")) < 1) 
        && ( (substr_count(strtolower($_SERVER['HTTP_USER_AGENT']),"galeon")) < 1)
        && ( (substr_count(strtolower($_SERVER['HTTP_USER_AGENT']),"konqueror")) < 1)
        && ( (substr_count(strtolower($_SERVER['HTTP_USER_AGENT']),"omniweb")) < 1) 
        && ( (substr_count(strtolower($_SERVER['HTTP_USER_AGENT']),"interarchy")) < 1)
        && ( (substr_count(strtolower($_SERVER['HTTP_USER_AGENT']),"anarchie")) < 1) ) {

     if (REAL_ONLY == 'f') {
     
       playMe($encoded_url);
     
     } elseif ( (REAL_ONLY == 't') && (substr_count(strtolower($_SERVER['HTTP_USER_AGENT']),"realmedia") == 1) ) {
     
       playMe($encoded_url);
     
     } else {

       header ( "Location: ".WEB_PATH."/etc/locale/".LANG_PACK."/real-error.mp3" );
     
     }

   } else {

     exit;

   }

}

########################################

function radioMe() {
  
  header ("Content-type: audio/x-mpegurl\r\nContent-Disposition: attachment; filename=netjuke-".substr(time(),-7).".m3u" ); 

  echo "#EXTM3U\r\n";
  echo RADIO_URL;

}

########################################

?>