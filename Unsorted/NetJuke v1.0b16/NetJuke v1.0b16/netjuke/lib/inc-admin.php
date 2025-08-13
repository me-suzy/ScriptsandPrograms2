<?php

##################################################

# Call common libraries
require_once('../lib/inc-common.php');
require_once(FS_PATH."/etc/locale/".LANG_PACK."/inc-lib_inc-admin.php");

##################################################

# Security check. gr_id is intitialized as 1 in
# ../account.php upon login

if (    (substr($_SERVER['SCRIPT_NAME'],-11) == 'tr-edit.php')
     || (substr($_SERVER['SCRIPT_NAME'],-17) == 'tr-batch-edit.php')
     || (substr($_SERVER['SCRIPT_NAME'],-14) == 'radio-edit.php') ) {

  if (abs($NETJUKE_SESSION_VARS["gr_id"]) > 2) {
    alert(INCADM_DENIED);
    exit;
  }

} else {

  if (abs($NETJUKE_SESSION_VARS["gr_id"]) != 1) {
    header("Location: ".WEB_PATH);
    exit;
  }

}

##################################################

function SaveNewImportFile($dir,$rows) {

  $new_file = $dir.'/bckp-'.str_replace(".","",str_replace(" ","",microtime()));
  
  $fp = fopen($new_file,'w');
  
  fwrite($fp,$rows);
  
  fclose($fp);
  
  rename($new_file, $new_file.'.txt');
  
  return $new_file.'.txt';

}

##################################################

function groupSelect($selected) {

  GLOBAL $dbconn;
  
  $dbrs = $dbconn->Execute("SELECT id, name FROM netjuke_groups where id != 4 order by id desc");

  $html = "<SELECT NAME='gr_idField' class=input_content>";

  while (!$dbrs->EOF) {
    
    if ($dbrs->fields[0] == $selected) {
       $indeed = "SELECTED";
    } else {
       $indeed = "";
    }
    
    $html .= "<OPTION VALUE='".$dbrs->fields[0]."' $indeed>".$dbrs->fields[1]."</OPTION>";
    
    $dbrs->MoveNext();
  
  }
  
  $dbrs->Close();

  $html .= "</SELECT>";

  return $html;

}

##################################################

function Update_Image($type = "", $id = 0, $img = "") {

  GLOBAL $dbconn;
  
  switch ($type) {
  
    case 'tr':
      $table = 'netjuke_tracks';
      break;
    case 'ar':
      $table = 'netjuke_artists';
      break;
    case 'al':
      $table = 'netjuke_albums';
      break;
    default:
      $table = FALSE;
  
  }
  
  if ( ($table) && (abs($id) != 0) ) {
  
    $dbconn->Execute( "update ".$table." set img_src = '".$img."' where id = ".abs($id) );
  
  }
  
  return $img;

}

##################################################

function CheckDataDirPerm() {

  $path = str_replace('//','/',FS_PATH."/".DATA_DIR_IMPORT);
  
  if (  !file_exists($path) || !is_writable($path) ) {
    alert(INCADM_NOPERM);
    exit;
  }

}


##################################################

function ImportTrackData($raw_values,$track_cache,$ar_cnt=0,$al_cnt=0,$ge_cnt=0) {

   GLOBAL $dbconn;
   
   //print_r($raw_values);

  $data = array();
          
  # The following fields can already be initialize
  # as they will be inserted as is in netjuke_tracks
  $data['size']           = $raw_values[4];
  $data['time']           = $raw_values[5];
  $data['track_number']   = $raw_values[6];
  $data['year']           = $raw_values[8];
  $data['date']           = $raw_values[10];
  $data['bit_rate']       = $raw_values[11];
  $data['sample_rate']    = $raw_values[12];
  $data['kind']           = $raw_values[14];

  $data['comments']       = raw_to_db(rawurldecode($raw_values[15])); // might be url encoded

  $data['img_src']        = $raw_values[17];

  $data['lyrics']         = raw_to_db(rawurldecode($raw_values[20])); // might be url encoded
          
  $data['name']           = raw_to_db($raw_values[0]);
  
  $temp_loc = $raw_values[16];
  
  $data['location']       = specialUrlEncode($temp_loc);
  
  // DEBUG echo "- '".$data['Location']."'<br>";
     
  if (substr($data['location'],0,1) == '/') $data['location'] = substr($data['location'],1);
  
  $dbrs = $dbconn->Execute("select id from netjuke_tracks where location = '".$data['location']."'");
   
  if ($dbrs->RecordCount() < 1) {
  
    $duplicate = false;
       
    # Get Artist, Album & Genre ID based on their respective names.
    list($data['ar_id'],$ar_cnt) = ImportTrackData_FindId('netjuke_artists',$raw_values[1],$ar_cnt,$raw_values[18]);
    list($data['al_id'],$al_cnt) = ImportTrackData_FindId('netjuke_albums',$raw_values[2],$al_cnt,$raw_values[19]);
    list($data['ge_id'],$ge_cnt) = ImportTrackData_FindId('netjuke_genres',$raw_values[3],$ge_cnt);
          
    $columns = array();
    $values = array();
    
    foreach ($data as $key => $value) {
      if (strlen($value) > 0) {
        array_push($columns, $key);
        array_push($values, "'$value'");
      }
    }
    
    $sql = 'INSERT INTO netjuke_tracks ('.join(', ',$columns).') VALUES ('.join(', ',$values).')';
     
    if (!$dbconn->Execute($sql)) {
     	   
      // $status = 'did not work...';
      // print $status;
    
    } else {
    
      $track_cache['netjuke_artists'][$data['ar_id']]++;
      $track_cache['netjuke_albums'][$data['al_id']]++;
      $track_cache['netjuke_genres'][$data['ge_id']]++;
      
      $status = '|';
    
    }
  
  } else {
  
    $duplicate = true;
     	   
    $status = '';
  
  }
  
  return array($duplicate,$track_cache,$status,$ar_cnt,$al_cnt,$ge_cnt);

}

##################################################

function ImportTrackData_FindId($table,$value,$cnt = 0,$img_src = "") {

   GLOBAL $dbconn;
   
   $value = raw_to_db($value);

   if (strlen($value) > 0) {

     $select_sql = "SELECT id FROM $table WHERE UPPER(name) = '".strtoupper($value)."'";
     $insert_sql = "INSERT INTO $table (name, img_src) VALUES ('$value', '$img_src')";

     $dbrs = $dbconn->Execute($select_sql);

     if ($dbrs->RecordCount() < 1) {
       if ($dbconn->Execute($insert_sql) === false) {
	     // error
       } else {
         $dbrs = $dbconn->Execute($select_sql);
         $cnt++;
       }
     }

     return array($dbrs->fields[0],$cnt);

   } else {

     return array(1,$cnt);

   }

}

##################################################

function track_cache($do, $table, $id = 0, $val = 0) {

  // $do: defines the action
  // expects: increment, decrement, reset

  // $table: defines the sql table
  // expects: netjuke_artists, netjuke_artists, netjuke_genres

  // $id: the record id
  // note: not needed in reset

  // $val: the number to in/decrement the counter by
  // note: not needed in reset
  
  GLOBAL $dbconn;
  
  if (    ($table == 'netjuke_artists') 
       || ($table == 'netjuke_albums')
       || ($table == 'netjuke_genres') ) {

    switch ($do) {

      case "increment":
        $dbconn->Execute(" update $table set track_cnt = track_cnt + $val where id = $id ");
        break;

      case "decrement":
        $dbconn->Execute(" update $table set track_cnt = track_cnt - $val where id = $id ");
        break;

      default:
        $do = 'reset';
        $dbconn->Execute(" update $table set track_cnt = 0 where track_cnt < 1 or track_cnt is null ");

    }

  }

}

##################################################

function track_cache_batch($do, $tables) {
  
  GLOBAL $dbconn;

  // $do is as defined in track_cache()
  
  // Processes a multi-demensional array ($tables) described below.
  // [table name list][record id list] = a number to in/decrement by.
  
  // Used as proxy to track_cache() in most scripts.
  
  foreach ($tables as $table => $ids) {
  
    track_cache('reset', $table);
  
    foreach ($ids as $id => $val) {
    
      track_cache($do, $table, $id, $val);
    
    }
  
    track_cache('reset', $table);
  
  }

}

##################################################

function CacheCleanUp() {

  GLOBAL $dbconn;

  $dbconn->Execute('delete from netjuke_artists where track_cnt = 0 and id != 1');
  $dbconn->Execute('delete from netjuke_albums where track_cnt = 0 and id != 1');
  $dbconn->Execute('delete from netjuke_genres where track_cnt = 0 and id != 1');

}

##################################################

function FixMP3Info($path, $getid3array) {
  
  $filename = explode("/", $path);
  $filename = str_replace( "_", " ", substr(end($filename), 0, -4) );

  if (stristr($filename, ' ')) {
    $explode_str = ' - ';
  } else {
    $explode_str = '-';
  }
  
  // "artist - album - tracknum - title.*"
  list($artist, $album, $tracknumber, $trackname) = explode($explode_str, $filename, 4);

  if (!is_numeric(trim($tracknumber)) || empty($tracknumber)) {
    
    // "artist - album - title.*"
    list($artist, $album, $trackname) = explode($explode_str, $filename, 3);
    
    $tracknumber = 0;

    if (empty($trackname)) {
      
      // "tracknum - title.*" or "artist - title.*"
      list($tempval, $trackname) = explode($explode_str, $filename, 2);
      
      $album = "";
      
      if ( (is_numeric(trim($tempval))) && (!empty($trackname)) ) {

        // "tracknum - title.*" - would fail if the artist's name
        // happens to be numerical only though...
        $tracknumber = $tempval;
        $artist = "";

      } elseif ( !empty($trackname) ) {

        //"artist - title.*"
        $artist = $tempval;
        $tracknumber = "";

      } else {

        // title.*
        $trackname = $filename;

        $artist = "";
        $tracknumber = "";

      }
    
    }
  
  }

  $trackname = trim($trackname);
  $tracknumber = trim($tracknumber);
  $artist = trim($artist);
  $album = trim($album);

  if (empty($trackname)) $trackname = "N/A";
  if (empty($artist)) $artist = "N/A";
  if (empty($tracknumber)) $tracknumber = 0;
  if (empty($album)) $album = "N/A";

  if (empty($getid3array["name"])) $getid3array["name"] = $trackname;
  if (empty($getid3array["artist"])) $getid3array["artist"] = $artist;
  if (empty($getid3array["album"])) $getid3array["album"] = $album;
  if (empty($getid3array["tr_num"])) $getid3array["tr_num"] = $tracknumber;
  if (empty($getid3array["genre"])) $getid3array["genre"] = "N/A";

  return $getid3array;

}

##################################################

?>
