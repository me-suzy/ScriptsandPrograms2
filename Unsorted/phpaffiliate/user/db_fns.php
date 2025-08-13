<?
 
	include "../config.php";
 
function db_connect()
{
   $result = @mysql_pconnect($server, $db_user, $db_pass) or die ("Database CONNECT Error (db_fns line 7)");  
   if (!$result)
      return false;
   if (!@mysql_select_db($database))
      return false;

   return $result;
}

?>
