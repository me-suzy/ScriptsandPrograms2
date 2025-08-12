<?php
function write_cnfgdata($host, $user, $pass, $database_name) {


/*
      THIS SECTION OF CODE WRITES THE CONFIG FILE.
      IF IT CAN NOT WRITE THE FILE IT WILL FTP IT INTO THE DIRECTORY TO GET AROUND THAT.
*/
   $config_data = '<?php' . " \n";
   //$config_data .= 'function connection_data($host, $user, $pass, $database_name){' . " \n";
   $config_data .= '$host = \'' . $host . '\';' . " \n";
   $config_data .= '$user = \'' . $user .  '\';' . " \n";
   $config_data .= '$pass = \'' . $pass . '\';' . " \n";
   $config_data .= '$database_name = \'' . $database_name . '\';' . " \n";
   //$config_data .= '}' . " \n";
   $config_data .= '?' . '>';
   $file_name = "CNFG.php";

   @umask(0111);
   $no_open = FALSE;

   if (!($handle = @fopen($file_name, 'w'))) {
      $s_hidden_fields = '<input type="hidden" name="config_data" value="' . htmlspecialchars($config_data) . '" />';
      if (@extension_loaded('ftp') && !defined('NO_FTP')) {
         echo $lang['ftp_choose'];
         echo $lang['Attempt_ftp'];
?>
         <input type="radio" name="send_file" value="2">
<?php 
         echo $lang['Send_file'];
?>
         <input type="radio" name="send_file" value="1">
<?php 
         }
      else {
         $s_hidden_fields .= '<input type="hidden" name="send_file" value="1" />';
         }
      exit;
      }
   $result = @fputs($handle, $config_data, strlen($config_data));
   @fclose($handle);
   if (file_exists($file_name)) {
      echo "Configuration file $file_name has been created.";
      }
   }
?>
