<?php

// defines if this script requires to be logged in
define( "PRIVATE", true );

##################################################

# Call common libraries
require_once('../lib/inc-admin.php');
require_once(FS_PATH."/etc/locale/".LANG_PACK."/inc-admin_hidden.php");

##################################################

if ($_REQUEST['do'] == 'scan') {

  $_REQUEST['scan_dir'] = separatorCleanup($_REQUEST['scan_dir']);
  
  if ((strlen($_REQUEST['scan_dir']) > 0) && (is_dir($_REQUEST['scan_dir']))) {
  
    if (substr($_REQUEST['scan_dir'],-1) != '/') $_REQUEST['scan_dir'] .= '/';
    
    if (strtolower(substr($_REQUEST['scan_dir'],0,strlen(MUSIC_DIR))) == strtolower(MUSIC_DIR)) {

      header( "Content-Disposition: attachment; filename=hidden_files.sh" ); 
      header ("Content-type: text/plain");
      RecursiveFinder($_REQUEST['scan_dir']);
  
    } else {
  
      echo "<b>&raquo;</b> ".HDDN_DENIED_BADDIR."<br>";
    
    }
  
  } else {
  
    echo "<b>&raquo;</b> ".HDDN_DENIED_NODIR_1."<br>";
  
  }

} else {

  $section = "sysadmin";
  include (INTERFACE_HEADER);
  
  if ( (strlen(MUSIC_DIR) > 0) && (is_dir(MUSIC_DIR)) ) {
  
    $disp_value = MUSIC_DIR;
  
  } else {
  
    $disp_value = HDDN_DENIED_NODIR_2;
  
  }

?>

<div align=center>
<table width='400' border=0 cellspacing=1 cellpadding=3 class='border'>
<tr>
  <td class='header' nowrap><B><?php echo  HDDN_HEADER ?></B></td>
</tr>
<tr>
  <td class='content'>
  <?php echo  HDDN_CAPTION_1 ?>
  <br>
  <br>
  <form action="<?php echo $_SERVER['PHP_SELF']?>" method="post" target="_blank">
  <input type="hidden" name="do" value="scan">
  <b>&raquo;</b> <?php echo  HDDN_CAPTION_2 ?>:<br>
  <input type="text" name="scan_dir" size="30" maxlength="256" value="<?php echo $disp_value?>" class=input_content>
  <input type="submit" name="submit" value="<?php echo  HDDN_BTN_SCAN ?>" class='btn_content'>
  </form>
  <br>

  </td>
</tr>
</form>
</table>
</div>

<?php

  include (INTERFACE_FOOTER);

}

##################################################

function RecursiveFinder($sPath) { 
  
  // Load Directory Into Array 
  $handle=opendir($sPath); 
  while ($file = readdir($handle)) { 
    $retVal[count($retVal)] = $file; 
  } 

  //Clean up and sort 
  closedir($handle); 
  sort($retVal);

  // Process the directory and go to recursive mode
  // in the children directories
  while (list($key, $val) = each($retVal)) { 
    
    if ($val != "." && $val != "..") { 

      $path = str_replace("//","/",$sPath.$val); 

      if (substr($val,0,1) == '.') {
      
        echo "rm -rf \"$path\"\r\n";
      
      } elseif (is_dir($sPath.$val)) {
      
        RecursiveFinder($sPath.$val."/");
      
      }

    } 

  }
  
  return $keepers;

}

##################################################

?>
