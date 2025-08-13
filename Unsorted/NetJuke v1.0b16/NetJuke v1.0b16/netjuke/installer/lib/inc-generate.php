<?php

########################################

srand((double)microtime()*1000000);

########################################

$msg = '';

# define site wide time limit based on safe mode status
if (ini_get("safe_mode") == 0) {
  define("TIME_LIMIT",43200);
  set_time_limit(TIME_LIMIT);
}

define("DEFAULT_SEC_KEY",'Th15 15 # 5#MpL3 53cUr1Ty K3y Th#T 5h0UlD b3 Ch#Ng3D 1mM3d1#T3lY');

switch ($_REQUEST['typeField']) {
  case UPGRADE_STR:
    $type = UPGRADE_STR;
    break;
  default:
    $type = INSTALL_STR;
}

// DEFINE WEB PATH NETJUKE TO NETJUKE ROOT

$proto = 'http';
if ($_SERVER['HTTPS'] == 'on') $proto .= 's';
$server_info = $proto."://".$_SERVER['HTTP_HOST'];

 // use two pre-defined variables in case the platform doesn't have one...
if (isset($_SERVER['SCRIPT_NAME'])) {
  $path_info = $_SERVER['SCRIPT_NAME'];
} else {
  $path_info = $_SERVER['PHP_SELF'];
}
$temp_vals = explode('/',$path_info);
$unused = array_pop($temp_vals);  // drop filename
$unused = array_pop($temp_vals);  // drop admin dir
$path_info = implode('/',$temp_vals);

// make sure the future fs path doesn't end with /
if (substr($path_info,-1) == '/') $path_info = rtrim($path_info,'/');

$proto = 'http';

if ($_SERVER['HTTPS'] == 'on') $proto .= 's';

define(  'WEB_PATH_4_INST_ROOT', separatorCleanup($path_info) );
define(  'WEB_PATH_4_INST_FULL', separatorCleanup($server_info.WEB_PATH_4_INST_ROOT) );

unset($proto, $server_info, $temp_vals, $path_info, $unused);

// DEFINE FS PATH NETJUKE TO NETJUKE ROOT

// use two pre-defined variables in case the platform doesn't have one...
if (isset($_SERVER['SCRIPT_FILENAME'])) {
  $path_info = $_SERVER['SCRIPT_FILENAME'];
} else {
  $path_info = $_SERVER['PATH_TRANSLATED'];
}

// translate windows \\ to unix /
$path_info = separatorCleanup($path_info);

$temp_vals = explode('/',$path_info);
$unused = array_pop($temp_vals);
$unused = array_pop($temp_vals);
$path_info = implode('/',$temp_vals);

define(  'FS_PATH_4_INST', separatorCleanup($path_info) );

unset($temp_vals, $path_info, $unused);

$const_ar = array (   "SECURITY_MODE" => ""
                    , "SECURITY_KEY" => ""
                    , "DB_TYPE" => ""
                    , "DB_HOST" => ""
                    , "DB_USER" => ""
                    , "DB_PASS" => ""
                    , "DB_NAME" => ""
                    , "STREAM_SRVR" => ""
                    , "MUSIC_DIR" => ""
                    , "DATA_DIR_IMPORT" => ""
                    , "DATA_DIR_BACKUP" => ""
                    , "ARTWORK_DIR" => ""
                    , "SUPPORTED_FORMATS" => ""
                    , "ENABLE_DOWNLOAD" => ""
                    , "PROTECT_MEDIA" => ""
                    , "REAL_ONLY" => ""
                    , "RADIO_TYPE" => ""
                    , "RADIO_PLIST" => ""
                    , "RADIO_URL" => ""
                    , "INTERFACE_HEADER" => ""
                    , "INTERFACE_FOOTER" => ""
                    , "CUSTOM_HEADER" => ""
                    , "CUSTOM_FOOTER" => ""
                    , "LANG_PACK" => ""
                    , "ENABLE_COMMUNITY" => ""
                    , "RES_PER_PAGE" => ""
                    , "USER_THEMES" => ""
                    , "INV_ICN" => ""
                    , "DEFAULT_BGCOLOR" => ""
                    , "DEFAULT_TEXT" => ""
                    , "DEFAULT_LINK" => ""
                    , "DEFAULT_ALINK" => ""
                    , "DEFAULT_VLINK" => ""
                    , "DEFAULT_TD_BORDER" => ""
                    , "DEFAULT_TD_HEADER" => ""
                    , "DEFAULT_TD_HEADER_FC" => ""
                    , "DEFAULT_TD_CONTENT" => ""
                    , "DEFAULT_FONT_FACE" => ""
                    , "DEFAULT_FONT_SIZE" => ""  );

if ($type != UPGRADE_STR) {

  if (!isset($_REQUEST['lang_packField'])) {

    $msg .= "- Please Select A Language.\\n";
  
  } elseif (!@file_exists(FS_PATH_4_INST."/etc/locale/".$_REQUEST['lang_packField']."/icon.play.gif")) {

    $const_ar["LANG_PACK"] = 'en';
  
  } else {
  
    $const_ar["LANG_PACK"] = $_REQUEST['lang_packField'];
  
  }

} else {

  $pref_file = FS_PATH_4_INST."/etc/inc-prefs.php";

  if (@file_exists($pref_file)) {
    require_once($pref_file);
  } else {
    $msg .= "- The existing preference file cannot be found.\\n- Please copy your current pref file to etc/inc-prefs.php\\n- Make it writable if you want the updater to save to it directly.";
  }
  
  $const_ar["LANG_PACK"] = LANG_PACK;

}

if ($msg != '') alert($msg);

require_once(FS_PATH_4_INST."/etc/locale/".$const_ar["LANG_PACK"]."/inc-installer_installer.php");
require_once(FS_PATH_4_INST."/etc/locale/".$const_ar["LANG_PACK"]."/inc-langprefs.php");

if ($msg != '') alert($msg);

if ($type != UPGRADE_STR) {

  if ($_REQUEST['db_hostField'] == '') {
    $msg .= "- ".ADMINST_ERR_DBHOST.".\\n";
  } else {
    $const_ar["DB_HOST"] = $_REQUEST['db_hostField'];
  }
  
  if ($_REQUEST['db_nameField'] == '') {
    $msg .= "- ".ADMINST_ERR_DBNAME.".\\n";
  } else {
    $const_ar["DB_NAME"] = $_REQUEST['db_nameField'];
  }

  if ($_REQUEST['sys_admin_pass2Field'] == '') {
    $msg .= "- ".ADMINST_ERR_PASS.".\\n";
  } // no associated constant in pref file

  if ($_REQUEST['db_passField'] != '') {
    $const_ar["DB_PASS"] = $_REQUEST['db_passField'];
  }

  $const_ar["DB_USER"] = $_REQUEST['db_userField'];

  $const_ar["USER_THEMES"] = $_REQUEST['user_themesField'];
  
  if ($const_ar["USER_THEMES"] != 't') $const_ar["USER_THEMES"] = 'f';

  $const_ar["SECURITY_MODE"] = "1.1";
  $const_ar["SECURITY_KEY"] = encode_security_key(DEFAULT_SEC_KEY);
  $const_ar["STREAM_SRVR"] = "var/music";
  $const_ar["MUSIC_DIR"] = FS_PATH_4_INST."/var/music";
  $const_ar["DATA_DIR_IMPORT"] = "var/data/import";
  $const_ar["DATA_DIR_BACKUP"] = "var/data/backup";
  $const_ar["ARTWORK_DIR"] = "var/artwork";
  $const_ar["SUPPORTED_FORMATS"] = "mp3,mp2,ogg,wma,ra";
  $const_ar["ENABLE_DOWNLOAD"] = "f";
  $const_ar["PROTECT_MEDIA"] = "f";
  $const_ar["REAL_ONLY"] = "f";
  $const_ar["RADIO_TYPE"] = "";
  $const_ar["RADIO_PLIST"] = "";
  $const_ar["RADIO_URL"] = FS_PATH_4_INST.'/var/data/radio/playlist.m3u';
  $const_ar["JUKEBOX_PLAYER"] = '';
  $const_ar["JUKEBOX_PLAYER_PATH"] = '';
  $const_ar["JUKEBOX_PLAYER_PID"] = FS_PATH_4_INST.'/var/data/jukebox/player.pid';
  $const_ar["JUKEBOX_PLIST"] = FS_PATH_4_INST.'/var/data/jukebox/playlist.m3u';
  $const_ar["INTERFACE_HEADER"] = FS_PATH_4_INST."/lib/inc-header.php";
  $const_ar["INTERFACE_FOOTER"] = FS_PATH_4_INST."/lib/inc-footer.php";
  $const_ar["CUSTOM_HEADER"] = FS_PATH_4_INST."/var/html/inc-header.html";
  $const_ar["CUSTOM_FOOTER"] = FS_PATH_4_INST."/var/html/inc-footer.html";
  $const_ar["ENABLE_COMMUNITY"] = "t";
  $const_ar["RES_PER_PAGE"] = "50";
  $const_ar["INV_ICN"] = "f";
  $const_ar["DEFAULT_BGCOLOR"] = "FFFFFF";
  $const_ar["DEFAULT_TEXT"] = "000000";
  $const_ar["DEFAULT_LINK"] = "0000FF";
  $const_ar["DEFAULT_ALINK"] = "333333";
  $const_ar["DEFAULT_VLINK"] = "9900CC";
  $const_ar["DEFAULT_TD_BORDER"] = "666666";
  $const_ar["DEFAULT_TD_HEADER"] = "9999CC";
  $const_ar["DEFAULT_TD_HEADER_FC"] = "EEEEEE";
  $const_ar["DEFAULT_TD_CONTENT"] = "EEEEEE";
  $const_ar["DEFAULT_FONT_FACE"] = "Verdana, Geneva, Arial, Helvetica, sans-serif";
  $const_ar["DEFAULT_FONT_SIZE"] = "11";

} else {
  
  $const_ar["SECURITY_MODE"] = SECURITY_MODE;
  $const_ar["SECURITY_KEY"] = encode_security_key(SECURITY_KEY);
  $const_ar["DB_TYPE"] = DB_TYPE;
  $const_ar["DB_HOST"] = DB_HOST;
  $const_ar["DB_USER"] = DB_USER;
  $const_ar["DB_PASS"] = obfuscate_undo(DB_PASS);
  $const_ar["DB_NAME"] = DB_NAME;
  $const_ar["STREAM_SRVR"] = STREAM_SRVR;
  $const_ar["SUPPORTED_FORMATS"] = SUPPORTED_FORMATS;
  $const_ar["MUSIC_DIR"] = MUSIC_DIR;
  $const_ar["DATA_DIR_IMPORT"] = DATA_DIR_IMPORT;
  $const_ar["DATA_DIR_BACKUP"] = DATA_DIR_BACKUP;
  $const_ar["ARTWORK_DIR"] = ARTWORK_DIR;
  $const_ar["ENABLE_DOWNLOAD"] = ENABLE_DOWNLOAD;
  $const_ar["PROTECT_MEDIA"] = PROTECT_MEDIA;
  $const_ar["REAL_ONLY"] = REAL_ONLY;
  $const_ar["RADIO_TYPE"] = RADIO_TYPE;
  $const_ar["RADIO_PLIST"] = RADIO_PLIST;
  $const_ar["RADIO_URL"] = RADIO_URL;
  $const_ar["JUKEBOX_PLAYER"] = JUKEBOX_PLAYER;
  $const_ar["JUKEBOX_PLAYER_PATH"] = JUKEBOX_PLAYER_PATH;
  $const_ar["JUKEBOX_PLAYER_PID"] = JUKEBOX_PLAYER_PID;
  $const_ar["JUKEBOX_PLIST"] = JUKEBOX_PLIST;
  $const_ar["INTERFACE_HEADER"] = INTERFACE_HEADER;
  $const_ar["INTERFACE_FOOTER"] = INTERFACE_FOOTER;
  $const_ar["CUSTOM_HEADER"] = CUSTOM_HEADER;
  $const_ar["CUSTOM_FOOTER"] = CUSTOM_FOOTER;
  $const_ar["LANG_PACK"] = LANG_PACK;
  $const_ar["ENABLE_COMMUNITY"] = ENABLE_COMMUNITY;
  $const_ar["RES_PER_PAGE"] = RES_PER_PAGE;
  $const_ar["USER_THEMES"] = USER_THEMES;
  $const_ar["INV_ICN"] = INV_ICN;
  $const_ar["DEFAULT_BGCOLOR"] = DEFAULT_BGCOLOR;
  $const_ar["DEFAULT_TEXT"] = DEFAULT_TEXT;
  $const_ar["DEFAULT_LINK"] = DEFAULT_LINK;
  $const_ar["DEFAULT_ALINK"] = DEFAULT_ALINK;
  $const_ar["DEFAULT_VLINK"] = DEFAULT_VLINK;
  $const_ar["DEFAULT_TD_BORDER"] = DEFAULT_TD_BORDER;
  $const_ar["DEFAULT_TD_HEADER"] = DEFAULT_TD_HEADER;
  $const_ar["DEFAULT_TD_HEADER_FC"] = DEFAULT_TD_HEADER_FC;
  $const_ar["DEFAULT_TD_CONTENT"] = DEFAULT_TD_CONTENT;
  $const_ar["DEFAULT_FONT_FACE"] = DEFAULT_FONT_FACE;
  $const_ar["DEFAULT_FONT_SIZE"] = DEFAULT_FONT_SIZE;

  // 1.0b15/B16 UPGRADE
  if (!defined('SUPPORTED_FORMATS')) {
    // FROM B14
    $const_ar["SUPPORTED_FORMATS"] = 'mp3,mp2,ogg,wma,ra';
  } elseif (substr(SUPPORTED_FORMATS,0,7) == 'mp3,ogg') {
    // FROM B15.X
    $const_ar["SUPPORTED_FORMATS"] = 'mp3,mp2,ogg,wma,ra';
  }

  // 1.0b15 UPGRADE
  if (defined('WEB_PATH')) {
    $const_ar["STREAM_SRVR"] = str_replace(WEB_PATH,'',$const_ar["STREAM_SRVR"]);
    if (substr($const_ar["STREAM_SRVR"],0,2) == '//') {
      $const_ar["STREAM_SRVR"] = substr($const_ar["STREAM_SRVR"],2);
    } elseif (substr($const_ar["STREAM_SRVR"],0,1) == '/') {
      $const_ar["STREAM_SRVR"] = substr($const_ar["STREAM_SRVR"],1);
    }
  }

  // 1.0b16 UPGRADE
  if (!defined('JUKEBOX_PLAYER')) {
    $const_ar["JUKEBOX_PLAYER"] = '';
    $const_ar["JUKEBOX_PLAYER_PATH"] = '';
    $const_ar["JUKEBOX_PLAYER_PID"] = FS_PATH_4_INST.'/var/data/jukebox/player.pid';
    $const_ar["JUKEBOX_PLIST"] = FS_PATH_4_INST.'/var/data/jukebox/playlist.m3u';
  }

}

if ($_REQUEST['db_typeField'] == '') {
  $msg .= "- ".ADMINST_ERR_DBTYPE.".\\n";
} else {
  $const_ar["DB_TYPE"] = $_REQUEST['db_typeField'];
}

if ($_REQUEST['sys_admin_userField'] == '') {
  $msg .= "- ".ADMINST_ERR_EMAIL.".\\n";
} // no associated constant in pref file

if ($_REQUEST['sys_admin_passField'] == '') {
  $msg .= "- ".ADMINST_ERR_PASS.".\\n";
} // no associated constant in pref file

if ($msg != '') alert($msg);

if ($type != UPGRADE_STR) { 

  // Define if the inc-prefs.php already exists on the targetted copy.
  if ( @file_exists(FS_PATH_4_INST."/etc/inc-prefs.php") ) alert( ADMINST_ERR_DENIED_1 . " (type 1)" );

}

// failsafe to make sure the defined filesystem path is correct
if ( !@file_exists(FS_PATH_4_INST."/lib/adodb/adodb.inc.php") ) alert( ADMINST_ERR_DENIED_3 );

require_once(FS_PATH_4_INST."/lib/adodb/adodb.inc.php");

$dbconn = &ADONewConnection($const_ar["DB_TYPE"]);

# define if we can use persistent connections
# for the selected database type
switch (strtolower($const_ar["DB_TYPE"])) {
  case "mysql": // MySQL
    if (ini_get("mysql.allow_persistent") == 1) $persistent = 1;
    break;
  default: // PostgreSQL 6 & 7
    if (ini_get("pgsql.allow_persistent") == 1) $persistent = 1;
}

# connect using PConnect or Connect and print error if any.
if ($persistent == 1) {
  if ( !$dbconn->PConnect($const_ar["DB_HOST"],$const_ar["DB_USER"],$const_ar["DB_PASS"],$const_ar["DB_NAME"]) ) {
    alert( ADMINST_ERR_DBCONN . " (\$dbconn->PConnect)" );
  }
} else {
  if ( !$dbconn->Connect($const_ar["DB_HOST"],$const_ar["DB_USER"],$const_ar["DB_PASS"],$const_ar["DB_NAME"]) ) {
    alert( ADMINST_ERR_DBCONN . " (\$dbconn->Connect)\\n" );
  }
}

$msg = $dbrs = ''; // cleanup, cleanup, tralala lala itou.

// Load the SQL statements used to generate the database later

$sql_statements = array();

if ($_REQUEST['db_typeField'] == '') $_REQUEST['db_typeField'] = 'mysql';

require_once(FS_PATH_4_INST."/installer/lib/sql/".$type."/".strtolower($_REQUEST['db_typeField'])."/music.sql.php");
require_once(FS_PATH_4_INST."/installer/lib/sql/".$type."/".strtolower($_REQUEST['db_typeField'])."/account.sql.php");
require_once(FS_PATH_4_INST."/installer/lib/sql/".$type."/".strtolower($_REQUEST['db_typeField'])."/data.sql.php");

if ($type != UPGRADE_STR) {

  // GENERATE THE DATABASE

  // Define if the database has already been generated before
  // Exit if affirmative.
  if ( $dbrs = $dbconn->Execute('select count(id) from netjuke_tracks') ) {
    alert( ADMINST_ERR_DENIED_1 . " (type 2)" );
  }
  
  // run the appropriate database generation SQL commands
  foreach ($sql_statements as $sql) {
    
    $sql = str_replace(";","",$sql);
    
    if ( stristr($sql,"insert into netjuke_users") ) { 
  
      $sql = str_replace ("'admin@temp.admin'", "'".$_REQUEST['sys_admin_userField']."'", $sql);
      $sql = str_replace ("'12dc7717c371ea3ddfa4eebc474c0abc'", "'".md5($_REQUEST['sys_admin_passField'])."'", $sql);
      $sql = str_replace ("'2002-01-01 00:00:00'", "'".date("Y-m-d H:i:s")."'", $sql);
  
    } elseif ( stristr($sql,"insert into netjuke_userprefs") ) { 
  
      $sql = str_replace ("'admin@temp.admin'", "'".$_REQUEST['sys_admin_userField']."'", $sql);
  
    }
    
    if ( !($dbconn->Execute($sql)) ) alert(ADMINST_ERR_EXEC."\\n".rawurlencode(substr($sql,0,30))."...");

    flush();
    
  }

} else {

  // UPGRADE THE DATABASE

  // Define if the database has already been generated before
  // Exit if negative.
  if ( !($dbrs = $dbconn->Execute('select count(id) from netjuke_tracks')) ) {
    alert( ADMINST_ERR_DENIED_2 );
  }
  
  $dbrs = $dbconn->Execute( " select email from netjuke_users "
                          . " where email = '".$_REQUEST['sys_admin_userField']."' "
                          . " and password = '".md5($_REQUEST['sys_admin_passField'])."' "
                          . " and gr_id = 1 " );

  if ($dbrs->RecordCount() != 1) {
  
    // TEMPORARY: b16 and compatible
    b16up_upgrade_to_md5_passwd();
    
    // alert( ADMINST_ERR_DENIED_4 );
  
  }
  
  // make sure to log everybody out while we upgrade the db.
  $dbconn->Execute(" delete from netjuke_sessions ");

  // test for b15 lyrics column in netjuke_tracks, or for version other
  // than 1.0b16 if we forgot to remove that temporary check.

  if (    (!(@$dbconn->SelectLimit('select lyrics from netjuke_tracks',1)))
       || (str_replace('b','',str_replace('.','',trim(NETJUKE_VERSION))) > '1016')  ) {
  
    // run the appropriate database generation SQL commands
    foreach ($sql_statements as $sql) {
      
      $sql = str_replace(";","",$sql);
    
      if ( !($dbconn->Execute($sql)) ) alert(ADMINST_ERR_EXEC);
      // DEBUG if ( !($dbconn->Execute($sql)) ) echo ADMINST_ERR_EXEC." ".$sql."<br>";
  
      flush();
    
    }
  
  }

}

$content_str = "<"."?php\r\n\r\n";

$content_str .= "// " . ADMINST_FILEINFO_1 . ": " . date("Y-m-d @ H:i:s") .  " " . ADMINST_FILEINFO_2 . " " . $_SERVER['REMOTE_ADDR']."\r\n";
$content_str .= "// " . ADMINST_FILEINFO_3 . ": " . WEB_PATH_4_INST_FULL . "/etc/inc-prefs.php \r\n";

$content_str .= "\r\n";

$const_ar["DB_PASS"] = obfuscate_apply($const_ar["DB_PASS"]);

foreach ($const_ar as $key => $val) {

  $val = str_replace("'","\\'",$val);
  
  $content_str .= "define(  '$key',          '$val'  );\r\n";

}

$content_str .= "\r\n?".">";

if ( ($type != UPGRADE_STR) || (!@is_writable($pref_file)) ) {

  // print the appropriate http headers and dump to screen
  // if we are installing, or the pref file is not writable
  
  //header( "Content-type: text/plain" );
  
  header("Content-type: application/octet-stream\r\nContent-Disposition: inline; filename=inc-prefs.php");
  
  echo $content_str;
  
  exit;

} else {
    
  // replace the content of the new pref file
  // then redirect to the new netjuke, or display
  // message if a temp directory was used
  
  $fp = fopen($pref_file,'w');
    
  fwrite($fp,$content_str);
    
  fclose($fp);

  echo  "<b style='color:#FF0000;'>".ADMINST_FILEINFO_4." "
      . "<a href=\"".WEB_PATH_4_INST_FULL."\">".WEB_PATH_4_INST_FULL.'/index.php'."</a>.";

}

########################################

function b16up_upgrade_to_md5_passwd() {

  GLOBAL $dbconn;
  
  $dbrs = $dbconn->Execute( " select email from netjuke_users "
                          . " where email = '".$_REQUEST['sys_admin_userField']."' "
                          . " and password = '".obfuscate_apply($_REQUEST['sys_admin_passField'])."' "
                          . " and gr_id = 1 " );
  
  if ($dbrs->RecordCount() != 1) {
    
    alert( ADMINST_ERR_DENIED_4 );
  
  } else {
  
    $dbrs_2 = $dbconn->Execute('select email, password from netjuke_users');

    while (!$dbrs_2->EOF) {
    
      $dbconn->Execute('update netjuke_users set password = \''.md5(obfuscate_undo($dbrs_2->fields[1])).'\' where email = \''.$dbrs_2->fields[0].'\'');
    
      $dbrs_2->MoveNext();
  
    }

    $dbrs_2->Close();
  
  }

  $dbrs->Close();

}

########################################

function obfuscate_apply($str) {
   
   # nothing much given we're in an open-source environment anyway...
   # Just enough to keep honest people honest. ;o)
   return rawurlencode(base64_encode($str));

}

########################################

function obfuscate_undo($str) {
   
   # nothing much given we're in an open-source environment anyway...
   # Just enough to keep honest people honest. ;o)
   return base64_decode(rawurldecode($str));

}

########################################

function cust_str_shuffle($str) {
  
  $cnt = strlen($str);
  
  $pos = 0;
  
  $arr = array();
  
  while ($pos < $cnt) {
  
    $arr[] = substr($str,$pos,1);
    
    $pos++;
  
  }
  
  shuffle($arr);
  
  $newstr = implode('',$arr);
  
  return $newstr;
  
}

########################################

function encode_security_key($str) {
  
  $str = cust_str_shuffle(obfuscate_apply($str));
  
  // make sure the key is at least 256 chars long
  if (strlen($str) < 256) $str = encode_security_key($str);
  
  // make sure the key is at max 512 chars long
  if (strlen($str) > 512)  {
    $pos = floor( ( (strlen($str) - 512) / 2 ) );
    $str = substr($str,$pos,512);
  }
  
  return $str;
  
}

##################################################

function separatorCleanup($path = '') {
   
  # translate windows \\ to unix /, kill potential //
  # and trash trailing /
  
  $proto_sep = '://';
  
  if (strstr($path,$proto_sep)) list($proto, $path) = split($proto_sep,$path);
  
  if (strstr($path,"\\")) $path = str_replace("\\","/",$path);
  
  $path = str_replace("//","/",$path);
  
  if (isset($proto)) $path = $proto.$proto_sep.$path;
  
  if (substr($path,-1) == '/') $path = rtrim($path,'/');
  
  return $path;

}

##################################################

?>