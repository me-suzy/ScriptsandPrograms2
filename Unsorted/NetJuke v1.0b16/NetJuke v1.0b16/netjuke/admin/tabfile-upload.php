<?php

// defines if this script requires to be logged in
define( "PRIVATE", true );

##################################################

# Call common libraries
require_once('../lib/inc-admin.php');
require_once(FS_PATH."/etc/locale/".LANG_PACK."/inc-admin_tabfile-upload.php");

##################################################

$section = "sysadmin";
include (INTERFACE_HEADER);

?>

<div align=center>
<table width='500' border=0 cellspacing=1 cellpadding=3 class='border'>
<tr>
  <td class='header' nowrap><B><?php echo  TFUPL_HEADER ?></B></td>
</tr>
<tr>
  <td class='content'>

<?php



if ($_FILES['userfile']) {  # Print the upload result information

  // make sure the data dir is writable or exit;
  CheckDataDirPerm();
  
  $cnt = 0;
  
  foreach ($_FILES['userfile']['tmp_name'] as $this_file) {
  
    echo '<b>&raquo;</b> '.($cnt + 1);
    echo ' - '.$_FILES['userfile']['name'][$cnt].' ['.$_FILES['userfile']['size'][$cnt].'] ['.$_FILES['userfile']['type'][$cnt].']';
    echo "<br>";
    
    if ($_FILES['userfile']['size'][$cnt] > 0) {
      if (($_FILES['userfile']['type'][$cnt] == 'text/plain') && (strtolower(substr($_FILES['userfile']['name'][$cnt],-4)) == '.txt')) {
        if (move_uploaded_file($_FILES['userfile']['tmp_name'][$cnt],str_replace('//','/',FS_PATH."/".DATA_DIR_IMPORT."/".$_FILES['userfile']['name'][$cnt])) === FALSE) {
          echo ('&nbsp; - '.TFUPL_ERROR.'<br>');
        }
      } else {
        echo ('&nbsp; - '.TFUPL_ERROR_NOTXT.' ['.$_FILES['userfile']['type'][$cnt].'].<br>');
      }
    }
    
    $cnt++;
  
  }
  
  echo '<br>';
  echo '<b>&raquo;</b> <a href="tabfile-import.php">'.TFUPL_PROCEED.'</a>.<br>';
  echo '<b>&raquo;</b> <a href="'.$_SERVER['PHP_SELF'].'">'.TFUPL_RETURN.'</a>.<br>';

} else {  # Print the documentation and upload form

?>

  <?php echo  TFUPL_CAPTION_1 ?>:
  <br>
     <?php echo  TFUPL_COLS_TR ?> 
  \t <?php echo  TFUPL_COLS_AR ?> 
  \t <?php echo  TFUPL_COLS_AL ?> 
  \t <?php echo  TFUPL_COLS_GE ?> 
  \t <?php echo  TFUPL_COLS_FS ?> 
  \t <?php echo  TFUPL_COLS_TI ?> 
  \t <?php echo  TFUPL_COLS_TN ?> 
  \t <?php echo  TFUPL_COLS_TC ?> 
  \t <?php echo  TFUPL_COLS_YR ?> 
  \t <?php echo  TFUPL_COLS_DT ?> 
  \t <?php echo  TFUPL_COLS_DA ?> 
  \t <?php echo  TFUPL_COLS_BR ?> 
  \t <?php echo  TFUPL_COLS_SR ?> 
  \t <?php echo  TFUPL_COLS_VA ?> 
  \t <?php echo  TFUPL_COLS_FK ?> 
  \t <?php echo  TFUPL_COLS_CT ?> 
  \t <?php echo  TFUPL_COLS_LC ?>
  <br>
  <br>
  <?php echo  TFUPL_CAPTION_2 ?> <?php echo  TFUPL_CAPTION_3 ?>
  <br>
  <br>
  <?php echo  TFUPL_CAPTION_4 ?>
  <br>
  <div align=center>
  <form enctype="multipart/form-data" action="<?$_SERVER['PHP_SELF']?>" method="POST">
  01. <input name="userfile[]" type="file" size="15">
  &nbsp;
  02. <input name="userfile[]" type="file" size="15">
  <br>
  <br>
  03. <input name="userfile[]" type="file" size="15">
  &nbsp;
  04. <input name="userfile[]" type="file" size="15">
  <br>
  <br>
  05. <input name="userfile[]" type="file" size="15">
  &nbsp;
  06. <input name="userfile[]" type="file" size="15">
  <br>
  <br>
  07. <input name="userfile[]" type="file" size="15">
  &nbsp;
  08. <input name="userfile[]" type="file" size="15">
  <br>
  <br>
  09. <input name="userfile[]" type="file" size="15">
  &nbsp;
  10. <input name="userfile[]" type="file" size="15">
  <br>
  <br>
  <input type="submit" value="<?php echo  TFUPL_BTN ?>" class='btn_content'>
  </form>
  </div>

<?php

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

?>
