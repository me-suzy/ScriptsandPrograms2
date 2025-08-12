#!/usr/local/bin/php -q
<?php
//***************************************************************
//     mysql_remote_backup v1.0 FINAL (Guess/Hope/Supposed to)
//     -------------------------------------------------------
//     Written by : Mathieu Landry <matt@utsn.net>
//        Date    : 2005-10-09
//        URL     : http://www.utsn.net/ (We are great !)
//***************************************************************

/*   <SHAMELESS_PLUG> 
       http://www.quebecblogue.com      -- Le Blogue du Québec
       http://www.coupestanley.com      -- Du hockey plein la gueule!
       http://bigmac.biz                -- Big Mac Index
       http://www.webmasterquebec.com   -- Webmaster Québec
       http://www.nofxfans.com          -- NOFX Fans // Great Band, Great Fans!
      </SHAMELESS_PLUG>
*/

//  If you need any further help,  I HIGHLY suggest that
//  you don't touch database administration with a TEN FOOT POLE!

set_time_limit(0); //  that shouldnt HANG!
//***********************************************************
//    Database configuration
//***********************************************************
$DB_HOST = "localhost";
$DB_USERNAME = "root";
$DB_PASSWORD = "xxxxxxxxxxxxx";
$DB_NAME = "all"; // or something else.

//***********************************************************
//    FTP Configuration
//***********************************************************
$FTP_REMOTE = "ftp.example.com";
$FTP_LOGIN  = "xxxxxx";
$FTP_PASSWORD = "xxxxxxx";
$FTP_PATH = "/home/xxxxx/backup/";

$LOCAL_DUMP_PATH = "/root/mysqldump/";   // local stocking path.
$FILENAME_PREFFIX = "sqldump";           // you shouldn't care...

// Generating filename
$filename = $FILENAME_PREFFIX.date("YmdHis").".sql";
 
//  Build mysqldump command 
$dump_cmd = "mysqldump -h$DB_HOST -u$DB_USERNAME -p$DB_PASSWORD";
if ($DB_NAME == "all" || $DB_NAME = "ALL") {
  $dump_cmd .= " --all-databases";
} else {
  $dump_cmd = " $DB_NAME";
}
$dump_cmd .= " > $LOCAL_DUMP_PATH$filename"; // complete la commande


exec($dump_cmd); // generating the dump file

// Try to connect to FTP Server
$conn_id = ftp_connect($FTP_REMOTE);

// Use that connection to send a login / pass !
// Yes! A password!
$login_result = ftp_login($conn_id, $FTP_LOGIN, $FTP_PASSWORD);

// Are-you connected?  If you're not I guess it's useless to this point.
if ((!$conn_id) || (!$login_result)) {
       echo "The connection to $FTP_REMOTE failed !\r\n";
       exit;
   } else {
       echo "Connected on $FTP_REMOTE with user $FTP_LOGIN\r\n";
       
       if (ftp_put($conn_id,$FTP_PATH.$filename ,$LOCAL_DUMP_PATH.$filename, FTP_BINARY)) {
       	 echo "$filename have been sent...\r\n";
       } else {
         echo "#\$! damn upload have failed\r\n";
       }
       ftp_close($conn_id);
       echo "Done.";
   }
?>