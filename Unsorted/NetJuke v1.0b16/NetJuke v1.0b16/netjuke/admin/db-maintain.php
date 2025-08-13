<?php

// defines if this script requires to be logged in
define( "PRIVATE", true );

##################################################

# Call common libraries
require_once('../lib/inc-admin.php');
require_once(FS_PATH."/etc/locale/".LANG_PACK."/inc-admin_db-maintain.php");

##################################################

$section = "sysadmin";
include (INTERFACE_HEADER);

?>

<script language="javascript">
  function confirmDelete(mode) {
    if (confirm("<?php echo  TFBCKP_CONFIRM ?>")) {
      self.location.href = '<?php echo  $_SERVER['PHP_SELF'] ?>?do=' + mode;
    }
  }
</script>

<div align=center>
<table width='400' border=0 cellspacing=1 cellpadding=3 class='border'>
<tr>
  <td class='header' nowrap><B><?php echo  TFBCKP_HEADER ?></B></td>
</tr>
<tr>
  <td class='content' align=left>

<?php

if ($_REQUEST['do'] == 'backup') {

  // make sure the data dir is writable or exit;
  CheckDataDirPerm();

  $rows = GetBackupData();
  
  $new_file = SaveNewImportFile(FS_PATH."/".DATA_DIR_BACKUP,$rows);
  
  $new_file = str_replace(FS_PATH, WEB_PATH, $new_file);

  echo "<b>&raquo;</b> <a href='$new_file' target='_blank'>".TFBCKP_BACKUP_DONE."</a><br>";

} elseif ($_REQUEST['do'] == 'maintain') {

  list($del_cnt, $del_locations) = MaintainDB();

  echo "<b>&raquo;</b> ".$del_cnt." ".TFBCKP_MAINTAIN_DONE."<br>";
  echo $del_locations."<br>";

} elseif ($_REQUEST['do'] == 'delete') {

  DeleteMusicData();

  echo "<b>&raquo;</b> ".TFBCKP_DELETE_DONE."<br>";

} else {

  echo "<b>&raquo;</b> <a href=\"javascript: confirmDelete('backup');\">".TFBCKP_BACKUP_START."</a><br>".TFBCKP_BACKUP_HELP."<br><br>";

  echo "<b>&raquo;</b> <a href=\"javascript: confirmDelete('maintain');\">".TFBCKP_MAINTAIN_START."</a><br>".TFBCKP_MAINTAIN_HELP."<br><br>";

  echo "<b>&raquo;</b> <a href=\"javascript: confirmDelete('delete');\">".TFBCKP_DELETE_START."</a><br>".TFBCKP_DELETE_HELP."<br><br>";

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

function GetBackupData() {

  GLOBAL $dbconn;

  $col_names = array("Name", "Artist", "Album", "Genre", "Size", "Time", "Track Number",
   "Track Count", "Year", "Date", "Date Added", "Bit Rate", "Sample Rate", "Volume Adjustment",
    "Kind", "Comments", "Location", "Track Image", "Artist Image", "Album Image");
  
  $rows = join("\t",$col_names)."\n";
  
  $sql = <<<__EOS
    SELECT tr.name, ar.name, al.name, ge.name,
           tr.size, tr.time, tr.track_number, tr.date,
           tr.bit_rate, tr.sample_rate, tr.kind,
           tr.location, tr.comments, 
           tr.img_src, ar.img_src, al.img_src
    FROM netjuke_tracks tr, netjuke_artists ar,
         netjuke_albums al, netjuke_genres ge
    WHERE tr.ar_id = ar.id
      and tr.al_id = al.id
      and tr.ge_id = ge.id
    ORDER BY tr.id asc 
__EOS;

  $dbrs = $dbconn->Execute($sql);

  $cnt = 0;
  
  while (!$dbrs->EOF) {
    
    $fields = array( $dbrs->fields[0]
                    ,$dbrs->fields[1]
                    ,$dbrs->fields[2]
                    ,$dbrs->fields[3]
                    ,$dbrs->fields[4]
                    ,$dbrs->fields[5]
                    ,$dbrs->fields[6]
                    ,''
                    ,''
                    ,$dbrs->fields[7]
                    ,$dbrs->fields[7]
                    ,$dbrs->fields[8]
                    ,$dbrs->fields[9]
                    ,''
                    ,$dbrs->fields[10]
                    ,$dbrs->fields[12]
                    ,rawurldecode($dbrs->fields[11])
                    ,$dbrs->fields[13]
                    ,$dbrs->fields[14]
                    ,$dbrs->fields[15] );

    $rows .= join("\t",$fields)."\n";
    
    $cnt++;
             
    echo '|';
    
    if ($cnt % 50 == 0) {
      echo " &nbsp; &raquo; &nbsp; $cnt<br>\n";
      flush();
    }
    
    $dbrs->MoveNext();
  
  }
  
  echo "<br><br>";
  
  $dbrs->Close();

  CacheCleanUp();
  
  return $rows;

}

##################################################

function MaintainDB() {

  GLOBAL $dbconn;
  
  $dbrs = $dbconn->Execute("SELECT id, ar_id, al_id, ge_id, location FROM netjuke_tracks");

  $cnt = $total_cnt = 0;
  
  $locations = "";
  
  $track_cache = array();
  
  while (!$dbrs->EOF) {
  
    // if the track doesn't have a full url (http://, rtsp://, etc)
    if (!stristr(rawurldecode($dbrs->fields[4]),"://")) {
    
      // if the media file cannot be found, then delete db record
      if (!file_exists(MUSIC_DIR."/".rawurldecode($dbrs->fields[4]))) {
        
        // look for playlists with this track id (grouped in case of duplicates)
        $pltr_dbrs = $dbconn->Execute("select pl_id from netjuke_plists_tracks where tr_id = ".$dbrs->fields[0]." group by pl_id ");
        
        // for all the unique playlist ids we found
        while (!$pltr_dbrs->EOF) {
        
          // delete the track entries in playlist
          $dbconn->Execute("delete from netjuke_plists_tracks where tr_id = ".$dbrs->fields[0]." and pl_id = ".$pltr_dbrs->fields[0]);
          
          // find out how many tracks are left in the playlist
          $pl_dbrs = $dbconn->Execute("SELECT count(id) FROM netjuke_plists_tracks where pl_id = ".$pltr_dbrs->fields[0]);
          
          // if there are no tracks left, delete the fav playlists and the actual one
          if ($pl_dbrs->fields[0] == 0) {
            $dbconn->Execute("delete from netjuke_plists_fav where pl_id = ".$pltr_dbrs->fields[0]);
            $dbconn->Execute("delete from netjuke_plists where id = ".$pltr_dbrs->fields[0]);
          }
          
          $pltr_dbrs->MoveNext();
        
        }
    
        // delete the track record
        $dbconn->Execute("delete from netjuke_tracks where id = ".$dbrs->fields[0]);
        
        // delete the artist record if no remaining track's are found
        // and if we're not dealing w/ the "n/a" record
        if ($dbrs->fields[1] != 1) {
          $ar_dbrs = $dbconn->Execute("SELECT count(id) FROM netjuke_tracks where ar_id = ".$dbrs->fields[1]);
          if ($ar_dbrs->fields[0] == 0) $dbconn->Execute("delete from netjuke_artists where id = ".$dbrs->fields[1]);
        }
        
        // delete the album record if no remaining track's are found
        // and if we're not dealing w/ the "n/a" record
        if ($dbrs->fields[2] != 1) {
          $al_dbrs = $dbconn->Execute("SELECT count(id) FROM netjuke_tracks where al_id = ".$dbrs->fields[2]);
          if ($al_dbrs->fields[0] == 0) $dbconn->Execute("delete from netjuke_albums where id = ".$dbrs->fields[2]);
        }
        
        // delete the genre record if no remaining track's are found
        // and if we're not dealing w/ the "n/a" record
        if ($dbrs->fields[3] != 1) {
          $ge_dbrs = $dbconn->Execute("SELECT count(id) FROM netjuke_tracks where ge_id = ".$dbrs->fields[3]);
          if ($ge_dbrs->fields[0] == 0) $dbconn->Execute("delete from netjuke_genres where id = ".$dbrs->fields[3]);
        }
        
        $track_cache['netjuke_artists'][$dbrs->fields[1]]++;
        $track_cache['netjuke_albums'][$dbrs->fields[2]]++;
        $track_cache['netjuke_genres'][$dbrs->fields[3]]++;
      
        // increment counter
        $cnt++;
        
        // add location to list of deleted files
        $locations .= "- ".MUSIC_DIR."/".rawurldecode($dbrs->fields[4])."<br>";

      } else {

        $dbconn->Execute("update netjuke_tracks set size = '".filesize(MUSIC_DIR."/".rawurldecode($dbrs->fields[4]))."' where id = ".$dbrs->fields[0]);
      
      }
    
    }
    
    $total_cnt++;
             
    echo '|';
    
    if ($total_cnt % 50 == 0) {
      echo " &nbsp; &raquo; &nbsp; $cnt<br>\n";
      flush();
    }
        
    $dbrs->MoveNext();
  
  }
  
  $dbrs->Close();
  
  track_cache_batch('decrement', $track_cache);

  CacheCleanUp();
  
  echo "<br><br>";

  return array($cnt,$locations);

}

##################################################

function DeleteMusicData() {

  // deletes all records in the db, except for the users,
  // their preferences, and their active sessions.
  
  GLOBAL $dbconn;

  $dbconn->Execute(" delete from netjuke_plists_fav ");
  $dbconn->Execute(" delete from netjuke_plists_tracks ");
  $dbconn->Execute(" delete from netjuke_plists ");
  
  echo " \n";
  flush();
  
  $dbconn->Execute(" delete from netjuke_tracks ");
  
  $dbconn->Execute(" delete from netjuke_artists where id != 1 ");
  $dbconn->Execute(" update netjuke_artists set track_cnt = 0 where id = 1 ");
  
  $dbconn->Execute(" delete from netjuke_albums where id != 1 ");
  $dbconn->Execute(" update netjuke_albums set track_cnt = 0 where id = 1 ");
  
  $dbconn->Execute(" delete from netjuke_genres where id != 1 ");
  $dbconn->Execute(" update netjuke_genres set track_cnt = 0 where id = 1 ");
  
  echo " \n";
  flush();

}

##################################################

?>
