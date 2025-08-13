<?php

// defines if this script requires to be logged in
define( "PRIVATE", true );

##################################################

# Call common libraries
require_once('../lib/inc-admin.php');
require_once(FS_PATH."/etc/locale/".LANG_PACK."/inc-admin_tabfile-recursive.php");

require_once(FS_PATH."/lib/getid3/getid3.php");

##################################################

$section = "sysadmin";
include (INTERFACE_HEADER);

?>

<div align=center>
<table width='530' border=0 cellspacing=1 cellpadding=3 class='border'>
<tr>
  <td class='header' nowrap>
    <table border=0 cellpadding=0 cellspacing=0 width=100%><tr>
      <td class='header'><B><?php echo  TFREC_HEADER.' ('.str_replace(',',', ',SUPPORTED_FORMATS).')' ?></B></td>
      <td align='right' class='header' nowrap>
        <input type="button" value="<?php echo  $_REQUEST['interactive'] ? TFREC_HEADER_AUTO : TFREC_HEADER_INTER; ?>" onclick="self.location.href='<?php echo $_REQUEST['interactive'] ? '?' : '?interactive=1'; ?>'" class="btn_header">
      </td>
    </tr></table>
</tr>
<tr>
  <td class='content'>

<?php

if ($_REQUEST['do'] == '') {

  if (empty($_REQUEST['interactive'])) {

?>

  <form action="<?php echo $_SERVER['PHP_SELF']?>" method="post">
  <input type="hidden" name="do" value="process">
  <?php echo  TFREC_CAPTION_2 ?><br><br>
  <input type="text" name="scan_dir" size="30" maxlength="256" value="<?php echo MUSIC_DIR?>" class=input_content>
  <input type="submit" name="submit" value="<?php echo  TFREC_BTN ?>" class='btn_content'>
  <br><input type="checkbox" name="recursive_scan" value="1" CHECKED> <?php echo TFREC_OPTION_1?>
  <br><input type="checkbox" name="auto_queue" value="1" CHECKED> <?php echo TFREC_OPTION_2?>
  <br><input type="checkbox" name="verbose_scan" value="1"> <?php echo TFREC_OPTION_3?>
  <br><input type="checkbox" name="direct_import" value="1" CHECKED> <?php echo TFREC_OPTION_4?>
  </form>

<?php

  } else {

     if (!empty($_REQUEST['import_dirs'])) {
     
        $_REQUEST['recursive_scan'] = "";
        $total_cnt = 0;
     
        foreach ($_REQUEST['import_dirs'] as $key => $import_dir) {

          flush();

          $_REQUEST['scan_dir'] = MUSIC_DIR.'/'.$import_dir;
          $keepers = RecursiveAudioFileFinderOther( str_replace('//','/',MUSIC_DIR.'/'.urldecode($import_dir)) );
   
          // print_r($keepers);
          $total_cnt = $total_cnt + ProcessFoundAudioFiles($keepers);
        
        }
     
        if ($total_cnt > 0) {
     
          echo "<br>$total_cnt ".TFREC_SUCCESS_3."<br>\n";

          if (!isset($_REQUEST['direct_import'])) {
            echo '<b>&raquo; <a href="tabfile-import.php">'.TFREC_PROCEED."</a></b>.<br><br>\n";
          }
     
        } else {
          
          echo TFREC_ERROR_NOFILE."<br><br>\n";
        
        }
    
    } else {

      $scan_dir = str_replace('//','/',MUSIC_DIR.'/');

      $_REQUEST['recursive_scan'] = 1;

      $keepers = RecursiveAudioFileFinder( str_replace('//','/',$scan_dir) );

      foreach ($keepers as $key => $val) {
        $dirs[] = substr($key, 0, (strrpos($key, "/")+1));
      }
      
      if (count($dirs) == 0) $dirs = array();
      
      $unique_dirs = array_unique($dirs);
      sort ($unique_dirs);
      reset ($unique_dirs);

      echo "<form action=\"".$_SERVER['PHP_SELF']."?interactive=1\" method=\"post\">\n";
      echo "<select size=".sizeof($unique_dirs)." name=\"import_dirs[]\" multiple>\n";

      foreach ($unique_dirs as $key => $dir) {
        
        $total_files = 0;
        
        foreach ($dirs as $dirkey => $dirdir) {
          if (substr($dirdir, 0, strlen($dir)) == $dir) $total_files++;
        }

        echo "<option value=\"".urlencode($dir)."\">$dir\n";
      }
      
      echo "</select>\n";
      echo "<br><input type=\"checkbox\" name=\"auto_queue\" value='1' CHECKED> ".TFREC_OPTION_2."\n";
      echo "<br><input type=\"checkbox\" name=\"verbose_scan\" value='1'> ".TFREC_OPTION_3."\n";
      echo "<br><input type=\"checkbox\" name=\"direct_import\" value='1' CHECKED> ".TFREC_OPTION_4."\n";

      echo "<br><center><input type='submit' name='submit' value='".TFREC_BTN."' class='btn_content'></center>\n";
      echo "</form>\n";

    }

  }

} elseif ($_REQUEST['do'] == 'process') {

  // make sure the data dir is writable or exit;
  if (!isset($_REQUEST['direct_import'])) CheckDataDirPerm();
  
  if (strlen($_REQUEST['scan_dir']) == 0) $_REQUEST['scan_dir'] = MUSIC_DIR;
  
  if ((strlen($_REQUEST['scan_dir']) > 0) && (is_dir($_REQUEST['scan_dir']))) {
  
    if (substr($_REQUEST['scan_dir'],-1) != '/') $_REQUEST['scan_dir'] .= '/';
    
    if (substr($_REQUEST['scan_dir'],0,strlen(MUSIC_DIR)) == MUSIC_DIR) {
    
      $keepers = RecursiveAudioFileFinder( str_replace('//','/',$_REQUEST['scan_dir']) );

      if (@count($keepers) > 0) {
        
        ProcessFoundAudioFiles($keepers);
  
        if (!isset($_REQUEST['direct_import'])) {
          echo '<b>&raquo; <a href="tabfile-import.php">'.TFREC_PROCEED."</a></b><br><br>\n";
        }
      
      } else {
  
        echo "<b>&raquo;</b> ".TFREC_ERROR_NOFILE."<br><br>";
      
      }
  
    } else {
  
      echo "<b>&raquo;</b> ".TFREC_ERROR_NOMUDIR_1."<br><br>";
    
    }
  
  } else {
  
    echo "<b>&raquo;</b> ".TFREC_ERROR_NODIR."<br><br>";
  
  }

} elseif ($_REQUEST['do'] == 'edit') {

  if (isset($_REQUEST['direct_import'])) {

    $row_cnt = 0;
    
    $ar_cnt = 0;
    $al_cnt = 0;
    $ge_cnt = 0;

    $track_cache = array();
  
  } else {
    
    // make sure the data dir is writable or exit;
    CheckDataDirPerm();
    
    $col_names = array( TFREC_COLS_TR
                      , TFREC_COLS_AR
                      , TFREC_COLS_AL
                      , TFREC_COLS_GE
                      , TFREC_COLS_FS
                      , TFREC_COLS_TI
                      , TFREC_COLS_TN
                      , TFREC_COLS_TC
                      , TFREC_COLS_YR
                      , TFREC_COLS_DT
                      , TFREC_COLS_DA
                      , TFREC_COLS_BR
                      , TFREC_COLS_SR
                      , TFREC_COLS_VA
                      , TFREC_COLS_FK
                      , TFREC_COLS_CT
                      , TFREC_COLS_LC );
    
    $rows = join("\t",$col_names)."\n";
  
  }

  foreach ($_REQUEST['location_new'] as $key => $value) {
  
    $temp = array($_REQUEST['name_new'][$key], $_REQUEST['artist_new'][$key],
                  $_REQUEST['album_new'][$key], $_REQUEST['genre_new'][$key], 
                  $_REQUEST['size'][$key], $_REQUEST['time'][$key],
                  abs($_REQUEST['tr_num_New'][$key]), '', '',
                  date("Y-m-d H:i:s"), date("Y-m-d H:i:s"), 
                  $_REQUEST['bit_rate'][$key], $_REQUEST['sample_rate'][$key],
                  '', $_REQUEST['kind'][$key], '', $_REQUEST['location'][$key]);
    
    if (isset($_REQUEST['direct_import'])) {
          
      list($duplicate,$track_cache,$status,$ar_cnt,$al_cnt,$ge_cnt) = ImportTrackData($temp,$track_cache,$ar_cnt,$al_cnt,$ge_cnt);

      if ($duplicate == false) $row_cnt++;
           
      echo $status;
           
      if ($row_cnt % 50 == 0) {
        echo " &nbsp; &raquo; &nbsp; $row_cnt<br>\n";
        flush;
      }
    
    } else {
    
      $rows .= join("\t",$temp)."\n";
    
    }
  
  
  }
  
  if (isset($_REQUEST['direct_import'])) {
     
     track_cache_batch('increment', $track_cache);
     
     echo '<br>';
     echo TFREC_INSERT_1." ".$row_cnt." ".TFREC_INSERT_2.", $ar_cnt ".TFREC_INSERT_3.", $al_cnt ".TFREC_INSERT_4.", $ge_cnt ".TFREC_INSERT_5.".<br>";
   
  } else {
  
    $new_file = SaveNewImportFile(FS_PATH."/".DATA_DIR_IMPORT,$rows);
    
    $new_file = str_replace(FS_PATH, WEB_PATH, $new_file);
  
    echo "<b>&raquo;</b> <a href='$new_file' target='_blank'>".TFREC_VIEW."</a><br>";
  
  }

}

?>

  </td>
</tr>
</form>
</table>
</div>

<?php

include (INTERFACE_FOOTER);

##################################################

function GetExistingTracks() {

  GLOBAL $dbconn;
          
  flush();
  
  $dbrs = $dbconn->Execute("SELECT location FROM netjuke_tracks WHERE location not like '%://%'");
  
  $tracks = array();
  
  while (!$dbrs->EOF) {

    $tracks[] = rawurldecode(str_replace('//', '/', MUSIC_DIR.'/'.$dbrs->fields[0]));
    
    $dbrs->MoveNext();
  
  }
  
  return $tracks;

}

##################################################

function RecursiveAudioFileFinder($path) {

  GLOBAL $dbconn;
  
  if (    (substr($_SERVER['PATH_TRANSLATED'],1,1) == ':')
       || (substr($_SERVER['SCRIPT_FILENAME'],1,1) == ':') ) {
  
    // we're on windows, don't even try whereis
    
    $keepers = RecursiveAudioFileFinderOther($path,0);
  
  } else {
  
    $dbrs = $dbconn->Execute('select count(id) from netjuke_tracks');
    $tr_cnt = $dbrs->fields(0);

    if ($tr_cnt == 0) {
    
      // first time import, true recursive is faster
      $keepers = RecursiveAudioFileFinderOther($path,0);
    
    } else {
    
      @exec('whereis find', $temp);

      if (!@is_array($temp)) {
      
        // @exec() returned error or not found
        $keepers = RecursiveAudioFileFinderOther($path,0);
      
      } else {
      
        $find = trim($temp[0]);
      
        $find_array = explode(' ',$find);
        
        if (count($find_array) == 1) {
          $find = $find_array[0];
        } else {
          $find = $find_array[1];
        }
        
        if ( !@file_exists($find) ) {
      
          // still can't find "find"!
          $keepers = RecursiveAudioFileFinderOther($path,0);
      
        } else {
      
          // we can and it is appropriate to use the "find" software
          $keepers = RecursiveAudioFileFinderUnix($path,$find);
      
        }
      
      }
    
    }
  
  }
  
  return $keepers;

}

##################################################

function RecursiveAudioFileFinderUnix($path,$find) {
  
  // returns all the tracks from the db
  $previous = GetExistingTracks();
          
  // helps avoid timeouts for long operations
  flush();
  
  // slash cleanup
  if (substr($path,-1) == '/') $path = rtrim($path, '/') ;
  
  // use find to get a list off files with dir tree
  exec($find.' '.escapeshellarg($path).' -follow -name \'*.*\'', $current);
          
  // helps avoid timeouts for long operations
  flush();
  
  // compare the two arrays to find the files not present in the db
  $diffs = array_diff($current, $previous);
  
  // get aray of supported formats
  $supported_formats = explode(',',SUPPORTED_FORMATS);
  
  $keepers = array();
          
  // helps avoid timeouts for long operations
  flush();
  
  // keep only the files we are interested in
  foreach ($diffs as $path) {
    $temp_vals = explode('.',$path);
    $file_ext = strtolower(rtrim(array_pop($temp_vals)));
    if (strlen($file_ext) == 0) $file_ext = '|||'; // makes sure file_ext str is never empty
    if (    (substr($file_name, 0, 1) != '.')
         && (!strstr($path,'/.'))
         && (in_array($file_ext, $supported_formats)) ) {
      $location = str_replace( '//', '/', str_replace(MUSIC_DIR,'',$path) );
      if (substr($location,0,1) == '/') $location = substr($location,1);
      $keepers[$location] = str_replace( '//', '/', $path );
    }
  }
  
  return $keepers;

}

##################################################

function RecursiveAudioFileFinderOther($sPath,$oPath=0) { 

  GLOBAL $dbconn;
  
  // translate windows \\ to unix /
  $sPath = separatorCleanup($sPath);
  $oPath = separatorCleanup($oPath);

  // this function is now only used whe we need
  // a truly recursive tool, or if the unix software
  // "find" cannot be found

  $supported_formats = explode(',',SUPPORTED_FORMATS);
  
  $keepers = array();
  
  // Save the original path value to be stripped later
  if ($oPath === 0) $oPath = $sPath;
  
  // Load Directory Into Array 
  
  if (!@$handle=opendir($sPath)) {
    
    echo "<b>&raquo;</b> Can't read content of $sPath <br>";
    
    return;
    
  }
  
  $retVal = array();
  
  while ($file = readdir($handle)) { 
    if (substr($file,0,1) != '.') $retVal[] = $file;
  }

  //Clean up and sort 
  closedir($handle); 

  // Process the directory and go to recursive mode
  // in the children directories
  if (count($retVal) > 0) {

    foreach ($retVal as $key => $val) { 

      if ($val != "." && $val != "..") { 
        
        $path = str_replace("//","/",$sPath.'/'.$val); 

        if (is_file($path)) {
          
          $temp_vals = explode('.',$val);
          $file_ext = strtolower(rtrim(array_pop($temp_vals)));
        
          if (    (substr($file_name, 0, 1) != '.')
               && (!strstr($path,'/.'))
               && (in_array($file_ext, $supported_formats)) ) {
          
            $location = '';
            $location = str_replace(MUSIC_DIR,'',$path);
            
            if ($location != $path) {
    
              if (substr($location,0,1) == '/') $location = substr($location,1);
    
              $dbrs = $dbconn->Execute("select id from netjuke_tracks where location = '".specialUrlEncode($location)."'");
              
              if ($dbrs->RecordCount() < 1) {
              
                $keepers[$location] = $path;
              
              }
              
              echo ' ';
          
            } else {
          
              echo "<b>&raquo;</b> $location ".TFREC_ERROR_NOMUDIR_2."<br>";
          
            }

          }

        } elseif (is_dir($path)) { 
          
          if (isset($_REQUEST['recursive_scan'])) {
            
            $keepers = array_merge( $keepers, RecursiveAudioFileFinderOther($path."/",$oPath) );

          }

        }

      } 

    }

    flush();

  }
    
  echo chr(10);
  
  return $keepers;

}

##################################################

function  ProcessFoundAudioFiles($keepers) {
  
  $queued_cnt = $errors_cnt = 0;
  
  $error_rows = array();
  
  $col_names = array( TFREC_COLS_TR
                    , TFREC_COLS_AR
                    , TFREC_COLS_AL
                    , TFREC_COLS_GE
                    , TFREC_COLS_FS
                    , TFREC_COLS_TI
                    , TFREC_COLS_TN
                    , TFREC_COLS_TC
                    , TFREC_COLS_YR
                    , TFREC_COLS_DT
                    , TFREC_COLS_DA
                    , TFREC_COLS_BR
                    , TFREC_COLS_SR
                    , TFREC_COLS_VA
                    , TFREC_COLS_FK
                    , TFREC_COLS_CT
                    , TFREC_COLS_LC );

  if (isset($_REQUEST['direct_import'])) {
    
    $ar_cnt = 0;
    $al_cnt = 0;
    $ge_cnt = 0;

    $track_cache = array();
  
  } else {
  
    $rows = join("\t",$col_names)."\n";
  
  }

  if ($_REQUEST['verbose_scan']) {
    echo "<table border=0 cellspacing=1 cellpadding=3 class='border'>\n";
    echo "<tr class='header'><td>Status</td><td>".join("</td><td>", $col_names)."</td></tr>\n";
  }
  
  foreach ($keepers as $location => $path) {
     
    if ( $AudioFileInfo = @GetAllMP3info($path) ) {
    
      // prepare default values
      $temp_id3 = array( 'name' => "",
                         'artist' => "",
                         'album' => "",
                         'genre' => "",
                         'size' => filesize($path),
                         'time' => 0,
                         'tr_num' => 0,
                         'tr_cnt' => 0,
                         'year' => 0,
                         'date' => date("Y-m-d H:i:s"),
                         'date_added' => date("Y-m-d H:i:s"),
                         'bit_rate' => 0,
                         'sample_rate' => 0,
                         'volume_adj' => 0,
                         'kind' => "",
                         'comments' => "",
                         'location' => $location,
                         'tr_img' => "",
                         'ar_img' => "",
                         'al_img' => "",
                         'lyrics' => "" );
      // get track name
      if (isset($AudioFileInfo["title"])) {
        $temp_id3["name"] = trim($AudioFileInfo["title"]);
      } else {
        $temp_id3["name"] = "";
      }
      
      // get artists name
      if (isset($AudioFileInfo["artist"])) {
        $temp_id3["artist"] = trim($AudioFileInfo["artist"]);
      } else {
        $temp_id3["artist"] = "";
      }
      
      // get album name
      if (isset($AudioFileInfo["album"])) {
        $temp_id3["album"] = trim($AudioFileInfo["album"]);
      } else {
        $temp_id3["album"] = "";
      }
      
      // get genre name
      if (isset($AudioFileInfo["genre"])) {
        $temp_id3["genre"] = trim($AudioFileInfo["genre"]);
      } else {
        $temp_id3["genre"] = "";
      }
      
      // get time in seconds
      if (isset($AudioFileInfo["playtime_seconds"])) {
        $temp_id3["time"] = floor($AudioFileInfo["playtime_seconds"]);
      } else {
        $temp_id3["time"] = 0;
      }
      
      // get track number
      if (isset($AudioFileInfo["track"])) {
        $temp_id3["tr_num"] = trim($AudioFileInfo["track"]);
      } else {
        $temp_id3["tr_num"] = 0;
      }
        
      // get year
      if (isset($AudioFileInfo["year"])) {
        $temp_id3["year"] = abs($AudioFileInfo["year"]);
      } else {
        $temp_id3["year"] = 1900;
      }
        
      // get bit rate
      if (isset($AudioFileInfo["bitrate"])) {
        $temp_id3["bit_rate"] = floor($AudioFileInfo["bitrate"] / 1000);
      } else {
        $temp_id3["bit_rate"] = 0;
      }
      
      // get sample rate
      if (isset($AudioFileInfo["frequency"])) {
        $temp_id3["sample_rate"] = abs($AudioFileInfo["frequency"]);
      } else {
        $temp_id3["sample_rate"] = 0;
      }
        
      // get comments
      if (isset($AudioFileInfo["comment"])) {
        $temp_id3["comments"] = $AudioFileInfo["comment"];
      } else {
        $temp_id3["comments"] = "";
      }
      
      // rawurlencode comments if we output to a text file
      // to cope w/ potential tabs and new lines
      if (!isset($_REQUEST['direct_import'])) rawurlencode($temp_id3["comments"]);
  
      
      $temp_vals = explode('.',$location);
      $file_ext = strtolower(array_pop($temp_vals));
  
      if (    ($file_ext == 'mp3')
           || ($file_ext == 'mp2') ) {
     
      // START MP3/MP2 SPECIFIC
        
        $temp_id3["kind"] = "MPEG AUDIO FILE";
        
        // get lyrics
        if (isset($AudioFileInfo["lyrics3"]["LYR"])) {
          $temp_id3["lyrics"] = $AudioFileInfo["lyrics3"]["LYR"];
        } elseif (isset($AudioFileInfo["id3"]["id3v2"]["ULT"][0]["data"])) {
          $temp_id3["lyrics"] = $AudioFileInfo["id3"]["id3v2"]["ULT"][0]["data"];
        } elseif (isset($AudioFileInfo["id3"]["id3v2"]["USLT"][0]["data"])) {
          $temp_id3["lyrics"] = $AudioFileInfo["id3"]["id3v2"]["USLT"][0]["data"];
        } else {
          $temp_id3["lyrics"] = "";
        }
           
        // rawurlencode lyrics if we output to a text file
        // to cope w/ potential tabs and new lines
        if (!isset($_REQUEST['direct_import'])) $temp_id3["lyrics"] = rawurlencode($temp_id3["lyrics"]);
        
      // END MP3/MP2 SPECIFIC
      
      } elseif ($file_ext == 'ogg') {
        
      // START OGG SPECIFIC
        
        $temp_id3["kind"] = "OGG VORBIS AUDIO FILE";
        
      // END OGG SPECIFIC
      
      } if ($file_ext == 'wma') {
     
      // START WMA SPECIFIC
        
        $temp_id3["kind"] = "WMA AUDIO FILE";
        
      // END WMA SPECIFIC
      
      } if ($file_ext == 'ra') {
     
      // START REALMEDIA AUDIO SPECIFIC
        
        $temp_id3["kind"] = "REAL AUDIO FILE";
        
      // END REALMEDIA AUDIO SPECIFIC
      
      } else {
      
        // not supported - ignore file
      
      }
  
      if (    ( strlen($temp_id3["name"]) == 0 )
           && ( strlen($temp_id3["artist"]) == 0 )
           && ( strlen($temp_id3["album"]) == 0 )
           && ( strlen($temp_id3["genre"]) == 0 )  ) {
        
        // if we do not have track, artist, album and
        // genre name(s), queue the file in the dynamic form.
            
        $error_rows[$path] = $temp_id3;
  
        if ($_REQUEST['verbose_scan']) {
          echo "<tr class='error-row'><td>Error: </td><td>".join("</td><td>", $temp_id3)."</td></tr>\n";
        }
  
        $error_cnt++;
    
      } else {
        
        // success, queue it for import
      
        if (isset($_REQUEST['direct_import'])) {
            
          list($duplicate,$track_cache,$status,$ar_cnt,$al_cnt,$ge_cnt) = ImportTrackData(array_values($temp_id3),$track_cache,$ar_cnt,$al_cnt,$ge_cnt);
  
          if ($duplicate == false) $queued_cnt++;
        
        } else {
  
          $rows .= join("\t",$temp_id3)."\n";
          
          $status = '|';
        
          $queued_cnt++;
        
        }
        
        if ($_REQUEST['verbose_scan']) {
        
          echo "<tr class='content'><td>Queued: </td><td>".join("</td><td>", $temp_id3)."</td></tr>\n";
  
          if ($queued_cnt % 25 == 0) flush();
        
        } else {
        
          echo $status;
          
          if ($queued_cnt % 50 == 0) {
          
            echo " &nbsp; &raquo; &nbsp; $queued_cnt<br>\n";
  
            flush();
          
          }
        
        }
            
      }
    
    }

  }
  
  if ($_REQUEST['verbose_scan']) {
    echo "</table><br>";
  } else {
    echo '<br>';
  }
  
  if ($queued_cnt > 0) {
  
    if (isset($_REQUEST['direct_import'])) {
     
      track_cache_batch('increment', $track_cache);
     
      echo '<br>';
      echo TFREC_INSERT_1." ".$queued_cnt." ".TFREC_INSERT_2.", $ar_cnt ".TFREC_INSERT_3.", $al_cnt ".TFREC_INSERT_4.", $ge_cnt ".TFREC_INSERT_5.".<br>";
   
    } else {
  
      $new_file = SaveNewImportFile(FS_PATH."/".DATA_DIR_IMPORT,$rows);
    
      $new_file = str_replace(FS_PATH, WEB_PATH, $new_file);

      echo "$queued_cnt ".TFREC_SUCCESS_1."<br>\n".urldecode($_REQUEST['scan_dir'])."<br>\n";
      echo "<b>&raquo;</b> <a href='$new_file' target='_blank'>".TFREC_VIEW."</a><br>\n";
    
    }

  }

  if ((isset($_REQUEST['auto_queue'])) && (count($error_rows) > 0)) {

    $auto_queue_cnt = 0;

    if (isset($_REQUEST['direct_import'])) {
    
      $ar_cnt = 0;
      $al_cnt = 0;
      $ge_cnt = 0;

      $track_cache = array();
    
    } else {

      $error_rows_str = join("\t",$col_names)."\n";
    
    }
    
    foreach ($error_rows as $path => $columns) {
      
      $error_row = FixMP3Info($path, $columns);
      
      if (isset($_REQUEST['direct_import'])) {
          
        list($duplicate,$track_cache,$status,$ar_cnt,$al_cnt,$ge_cnt) = ImportTrackData(array_values($error_row),$track_cache,$ar_cnt,$al_cnt,$ge_cnt);

        if ($duplicate == false) $auto_queue_cnt++;
      
      } else {

        $error_rows_str .= join("\t", $error_row)."\n";
        
        $status = '|';
       
        $auto_queue_cnt++;
      
      }
     
      if (!isset($_REQUEST['verbose_scan'])) {
      
        echo $status;
        
        if ($auto_queue_cnt % 50 == 0) {
        
          echo " &nbsp; &raquo; &nbsp; $auto_queue_cnt<br>\n";

          flush();
        
        }
      
      }
    
    }
  
    if (isset($_REQUEST['direct_import'])) {
     
      track_cache_batch('increment', $track_cache);
     
      echo '<br>';
      echo TFREC_INSERT_1." ".$auto_queue_cnt." ".TFREC_INSERT_2.", $ar_cnt ".TFREC_INSERT_3.", $al_cnt ".TFREC_INSERT_4.", $ge_cnt ".TFREC_INSERT_5.".<br>";
   
    } else {
    
      $new_errored_file = SaveNewImportFile(FS_PATH."/".DATA_DIR_IMPORT,$error_rows_str);

      $new_errored_file = str_replace(FS_PATH, WEB_PATH, $new_errored_file);

      if (!isset($_REQUEST['verbose_scan'])) echo "<br>";
      echo "$auto_queue_cnt ".TFREC_SUCCESS_1."<br>\n".urldecode($_REQUEST['scan_dir'])."<br>\n";
      echo "<b>&raquo;</b> <a href='$new_errored_file' target='_blank'>".TFREC_VIEW."</a><br><br>\n";

    }
    
    $error_cnt = 0;

  }

  if (($queued_cnt > 0) || (count($error_rows) > 0)) {
    echo "<br>\n";
  }

  if ($error_cnt > 0) {
 
    echo TFREC_FORM_HELP_1." $error_cnt ".TFREC_FORM_HELP_2."<br>";
    echo TFREC_FORM_HELP_3."<br>";
    if ($error_cnt > RES_PER_PAGE) echo TFREC_FORM_HELP_4."<br>";
    echo "<br>";
    echo "  </td>";
    echo "</tr>";
    echo "</form>";
    echo "</table>";

    echo '<br>';
    echo "<table width='530' border='0' cellspacing='1' cellpadding='3' class='border'>";
    echo "<tr class='header'><td><b>".TFREC_COLS_TR."</b></td><td><b>".TFREC_COLS_AR."</b></td><td><b>".TFREC_COLS_AL."</b></td><td><b>".TFREC_COLS_GE."</b></td><td><b>#</b></td></tr>";
    echo "<form name='EditForm' action='".$_SERVER['PHP_SELF']."' method='post'>\n";
    echo "<input type='hidden' name='do' value='edit'>\n";
    echo "<tr class='content'><td colspan='7' align='center'><input type='checkbox' name='direct_import' CHECKED>".TFREC_OPTION_4."</td></tr>\n";
    
    $cnt = 0;
    
    foreach ($error_rows as $path => $columns) {

      $error_row = FixMP3Info($path, $columns);

      echo "<tr class='content'><td colspan='7'><b>&raquo;</b> ".TFREC_COLS_LC.": $path <input type='hidden' name='location_new[]' value='$path'></td></tr>\n";
      echo "<tr class='content'><td><input type='text' name='name_new[]' value=\"".$error_row["name"]."\" size='15' maxlength='100' class=input_content></td>";
      echo "<td><input type='text' name='artist_new[]' value=\"".$error_row["artist"]."\" size='15' maxlength='100' class=input_content></td>\n";
      echo "<td><input type='text' name='album_new[]' value=\"".$error_row["album"]."\" size='15' maxlength='100' class=input_content></td>\n";
      echo "<td><input type='text' name='genre_new[]' value=\"".$error_row["genre"]."\" size='15' maxlength='100' class=input_content></td>\n";
      echo "<td><input type='text' name='tr_num_New[]' value=\"".$error_row["tr_num"]."\" size='2' maxlength='3' class=input_content></td>\n";

      foreach ($columns as $index => $value) {
        echo "<input type='hidden' name='".str_replace(" ", "_", $index)."[]' value=\"$value\">\n";
      }
      
      $cnt++;
      
      if ($cnt >= RES_PER_PAGE) break;

    }
    
    echo "<tr class='content'><td colspan='7' align='center'><input type='submit' name='submit' value='".TFREC_FORM_BTN."' class='btn_content'></td></tr>\n";
    echo "</form>\n";
    echo "</table>\n";
    echo '<br><br>';
  
  } else {
  
    // echo TFREC_SUCCESS_2;
    // echo '<br><br>';
  
  }

  return $queued_cnt;
  
}

##################################################

?>
