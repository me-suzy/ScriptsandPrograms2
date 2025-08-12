<?
require("../db.php");
require("include.php");

$SUID=f_ip2dec($REMOTE_ADDR);
if (!session_id($SUID))
session_unset();
session_start();
if (!session_is_registered('signed_in'))
   session_register('signed_in');
   $signed_in = "no";
   $log=$_SESSION["signed_in"];

Header( "Location: index.php");


?>
