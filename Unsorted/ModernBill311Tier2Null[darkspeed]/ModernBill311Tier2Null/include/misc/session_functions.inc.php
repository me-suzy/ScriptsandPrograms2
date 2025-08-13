<?php
// +----------------------------------------------------------------------+
// | ModernBill [TM] .:. Client Billing System                            |
// +----------------------------------------------------------------------+
// | Copyright (c) 2001-2002 ModernGigabyte, LLC                          |
// +----------------------------------------------------------------------+
// | This source file is subject to the ModernBill End User License       |
// | Agreement (EULA), that is bundled with this package in the file      |
// | LICENSE, and is available at through the world-wide-web at           |
// | http://www.modernbill.com/extranet/LICENSE.txt                       |
// | If you did not receive a copy of the ModernBill license and are      |
// | unable to obtain it through the world-wide-web, please send a note   |
// | to license@modernbill.com so we can email you a copy immediately.    |
// +----------------------------------------------------------------------+
// | Authors: ModernGigabyte, LLC <info@moderngigabyte.com>               |
// | Support: http://www.modernsupport.com/modernbill/                    |
// +----------------------------------------------------------------------+
// | ModernGigabyte and ModernBill are trademarks of ModernGigabyte, LLC. |
// +----------------------------------------------------------------------+
##
## MySQL Session Management
##
function sess_mysql_open($save_path, $sess_name)
{
         GLOBAL $DIR,$dbh,$locale_db_host,$locale_db_login,$locale_db_pass,$locale_db_name;
         include($DIR."include/config/config.locale.php");
         $dbh=mysql_pconnect($locale_db_host,$locale_db_login,$locale_db_pass) or die("Problem with dB connection!");
         mysql_select_db($locale_db_name,$dbh) or die("Problem with dB connection!");
         return TRUE;
}

function sess_mysql_read($sess_id)
{
         $result = mysql_query("SELECT data FROM sessions WHERE id = '$sess_id'") or die(mysql_error());
         if(mysql_num_rows($result) == 0) { return(""); }
         $row = mysql_fetch_array($result);
         return $row["data"];
}

function sess_mysql_write($sess_id, $val)
{
         $result = mysql_query("REPLACE INTO sessions VALUES ('$sess_id', '$val', null)") or die(mysql_error());
         return TRUE;
}

function sess_mysql_destroy($sess_id)
{
         $result = mysql_query("DELETE FROM sessions WHERE id = '$sess_id'") or die(mysql_error());
         return TRUE;
}

function sess_mysql_gc($max_lifetime)
{
         $old = time() - $max_lifetime;
         $result = mysql_query("DELETE FROM sessions WHERE UNIX_TIMESTAMP(t_stamp) < $old") or die(mysql_error());
         return TRUE;
}
?>
