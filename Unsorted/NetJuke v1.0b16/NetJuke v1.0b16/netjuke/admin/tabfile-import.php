<?php

// defines if this script requires to be logged in
define( "PRIVATE", true );

##################################################

# Call common libraries
require_once('../lib/inc-admin.php');
require_once(FS_PATH."/etc/locale/".LANG_PACK."/inc-admin_tabfile-import.php");

##################################################

$section = "sysadmin";
include (INTERFACE_HEADER);

?>

<div align=center>
<table width='500' border=0 cellspacing=1 cellpadding=3 class='border'>
<tr>
  <td class='header' nowrap><B><?php echo  TFIMPT_HEADER ?></B></td>
</tr>
<tr>
  <td class='content'>

<?php

if ($_REQUEST['do'] == 'delete') {

  // make sure the data dir is writable or exit;
  CheckDataDirPerm();

  foreach ($_REQUEST['delfile'] as $val) {
  
    $val = str_replace("//","/",FS_PATH."/".DATA_DIR_IMPORT."/".rawurldecode($val));
    
    if (is_writable($val)) {
    
      unlink($val);
      
      echo "<b>&raquo;</b> ".TFIMPT_DELETE_SUCCESS.": ".$val."<br>";
    
    } else {
      
      echo "<b>&raquo;</b> ".TFIMPT_DELETE_ERROR.": ".$val."<br>";
    
    }
  
  }
  
  echo "<br>";

}

# Now that we have deleted the requested files, if any,
# let's get the remaining list of playlists to be processed

if ($dir = opendir(FS_PATH."/".DATA_DIR_IMPORT)) {

  $playlists = array();
  
  while($file = readdir($dir)) {

    if ((substr($file,0,1) != '.') && (strtolower(substr($file,-4)) == '.txt')) {

      array_push($playlists,$file);

    }

  }  

  closedir($dir);

  echo TFIMPT_FOUND_1.' '.count($playlists).' '.TFIMPT_FOUND_2.'<br>'.str_replace("//","/",FS_PATH."/".DATA_DIR_IMPORT).'<br>';
  
  if ($_REQUEST['do'] == 'import') {

    # make sure the data dir is writable or exit;
    
    CheckDataDirPerm();
  
    # process the named playlists in import directory
     
    ProcessPlaylists($playlists);

    CacheCleanUp();
  
  } else {
  
    # just list the files available for import
  
    if (count($playlists) > 0) ListFiles($playlists);
  
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

function ListFiles($playlists) {

  echo "<form action='".$_SERVER['PHP_SELF']."' method='post' name='ImportForm'>";
  echo "<input type=hidden name='do' value='delete'>";
  
  foreach ($playlists as $playlist) {
  
    echo "<input type=checkbox name='delfile[]' value='".rawurlencode($playlist)."'> ".str_replace("//","/",FS_PATH."/".DATA_DIR_IMPORT."/".$playlist)." [<a href='".WEB_PATH.str_replace("//","/","/".DATA_DIR_IMPORT."/".$playlist)."' target='_blank'>".TFIMPT_VIEW."</a>]<br>\n";
  
  }

  echo '<br><b>&raquo;</b> <a href="tabfile-import.php?do=import">'.TFIMPT_PROCEED.'</a>.';
  echo '<br><b>&raquo;</b> <a href="javascript:document.ImportForm.submit();">'.TFIMPT_DELETE.'</a>.';
  echo "</form>";

}

##################################################

function ProcessPlaylists($playlists) {

  GLOBAL $dbconn;

  $errors = "";

  # Process each playlist
  
  foreach ($playlists as $playlist) {
   
   if ((substr($playlist,0,1) != '.') && (strtolower(substr($playlist,-4)) == '.txt')) {
   
     echo TFIMPT_PROCESS.' '.$playlist.'.<br>';
     
     $fp = fopen (FS_PATH."/".DATA_DIR_IMPORT."/".$playlist,"r");
     
     $row_cnt = 0;

     $ar_cnt = 0;
     $al_cnt = 0;
     $ge_cnt = 0;
     
     # Process each row
     
     $track_cache = array();
     
     $new_raw_values = "";
     
     // using fgets() scheme below to cope with comments that might make
     // a line longer than the 5000 chars read per line. fgetcvs() just
     // ignored whatever was past 5000.

     while ($raw_values_str = fgets($fp, 5000)) {
       
       $new_raw_values .= $raw_values_str;
       
       // the following should not affect the comments because they
       // must be rawurlencoded to be stored in the data file anyway.
       if  ( (substr($raw_values_str, -1) == "\n") || (substr($raw_values_str, -1) == "\r") ) {
         
         $raw_values_str = $new_raw_values;
         
         $raw_values_str = str_replace("\n",'',$raw_values_str);
         $raw_values_str = str_replace("\r",'',$raw_values_str);
         
         $new_raw_values = "";
         
         //ignore columns
         if ($row_cnt != 0) {

           $raw_values = explode("\t", $raw_values_str);
          
           list($duplicate,$track_cache,$status,$ar_cnt,$al_cnt,$ge_cnt) = ImportTrackData($raw_values,$track_cache,$ar_cnt,$al_cnt,$ge_cnt);
           
           if ($duplicate == false) $row_cnt++;
           
           echo $status;
           
           if ($row_cnt % 50 == 0) {
             echo " &nbsp; &raquo; &nbsp; $row_cnt<br>\n";
             flush;
           }
          
         } else {
         
           echo TFIMPT_IGNORE_COLNAMES.'<br>';
           $row_cnt++;
         
         }
       
       }
     
     }
  
     fclose($fp);
     
     flush();
     
     track_cache_batch('increment', $track_cache);
     
     echo '<br>';
     echo TFIMPT_INSERT_1." ".($row_cnt - 1)." ".TFIMPT_INSERT_2.", $ar_cnt ".TFIMPT_INSERT_3.", $al_cnt ".TFIMPT_INSERT_4.", $ge_cnt ".TFIMPT_INSERT_5.".<br>";
  
     if (strlen($errors) > 0) {
        echo $errors.'<br>';
     }   
  
     rename(FS_PATH."/".DATA_DIR_IMPORT."/".$playlist, FS_PATH."/".DATA_DIR_BACKUP."/".$playlist);
     echo TFIMPT_ARCHIVE_1.' '.$playlist.' '.TFIMPT_ARCHIVE_2.' '.FS_PATH."/".DATA_DIR_IMPORT."/".$playlist.'<br>';
     
     echo TFIMPT_DONE.' '.FS_PATH."/".DATA_DIR_BACKUP."/".$playlist.'!<br><br>';
     
     flush();
   
   }
  
  }
  
}

##################################################

?>
