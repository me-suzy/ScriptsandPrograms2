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
## Security Check!
## ---------------------------------
if ($DIR && ($HTTP_COOKIE_VARS[DIR] || $HTTP_POST_VARS[DIR] || $HTTP_GET_VARS[DIR] || $_COOKIE[DIR] || $_POST[DIR] || $_GET[DIR])) {
    $ip   = $HTTP_SERVER_VARS[REMOTE_ADDR];
    $host = gethostbyaddr($ip);
    $url  = $HTTP_SERVER_VARS["HTTP_HOST"].$HTTP_SERVER_VARS["REQUEST_URI"];
    $admin= ($GLOBALS[SERVER_ADMIN]) ? $GLOBALS[SERVER_ADMIN] : "security@your.server.com";
    $body = "IP:\t$ip\nHOST:\t$host\nURL:\t$url\nVER:\t$version\nTIME:\t".date("Y/m/d: h:i:s")."\n";
    @mail($admin,"Possible breakin attempt.",$body,"From: $admin\r\n");
    print str_repeat(" ", 300)."\n";
    flush();
    ?>
    <html><head><body>
    <center><h3><tt><b><font color=RED>Security violation from: <?=$ip?> @ <?=$host?></font></b></tt></h3></center>
    <hr>
    <pre><? @system("traceroute ".escapeshellcmd($ip)." 2>&1"); ?></pre>
    <hr>
    <center><h2><tt><b><font color=RED>The admin has been alerted.</font></b></tt></h2></center>
    </body></html>
    <?
    exit;
}

include($DIR."include/misc/session_functions.inc.php");
session_set_save_handler("sess_mysql_open",
                         "",
                         "sess_mysql_read",
                         "sess_mysql_write",
                         "sess_mysql_destroy",
                         "sess_mysql_gc");

session_start();

session_register('isloggedin',
                 'this_admin',
                 'this_user',
                 'language',
                 'theme',
                 'uri');

function login($username,$password)
{
         GLOBAL $dbh,
                $isloggedin,
                $this_user,
                $this_admin,
                $username,
                $prefix,
                $logout_hourly,
                $we_are_closed,
                $why_are_we_closed;

         if(!$dbh)dbconnect();
         $hash = md5($password);

         if (eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*$",$username)) {
             if ($we_are_closed) {
                 echo "<html><head><title></title></head><body><pre>$why_are_we_closed</pre></body></html>"; exit;
             } else {
                 $sql = "SELECT * FROM client_info WHERE client_email='$username' AND client_password='$hash'";
                 $result = mysql_query($sql,$dbh) or die (mysql_error());
                 if (!$result || mysql_num_rows($result) < 1){
                     if(session_unset())session_destroy();
                     $this_user  = NULL;
                     $this_admin = NULL;
                     return FALSE;
                 } else {
                     $this_user  = mysql_fetch_array($result);
                     $this_admin = NULL;
                     $hashvar    = ($logout_hourly) ? date("F d, Y H") : date("F d, Y") ;
                     $isloggedin = md5($hashvar);
                     return TRUE;
                 }
             }
         } elseif (eregi($prefix,$username)) {
             $username = str_replace($prefix,"",$username);
             $sql = "SELECT * FROM admin WHERE admin_username='$username' AND admin_password='$hash'";
             $result = mysql_query($sql,$dbh) or die (mysql_error());
                 if (!$result || mysql_num_rows($result) < 1){
                     if(session_unset())session_destroy();
                     $this_admin = NULL;
                     $this_user  = NULL;
                     return FALSE;
                 } else {
                     $this_admin = mysql_fetch_array($result);
                     $this_admin["ap"] = ($this_admin["admin_level"]==9) ? $password : NULL ;
                     $this_user  = NULL;
                     $hashvar    = ($logout_hourly) ? date("F d, Y H") : date("F d, Y") ;
                     $isloggedin = md5($hashvar);
                     $result     = @mysql_query("OPTIMIZE TABLE sessions");
                     return TRUE;
                 }
         } else {
                     if(session_unset())session_destroy();
                     $this_admin = NULL;
                     $this_user  = NULL;
                     return FALSE;
         }
}

function testlogin()
{
         GLOBAL $isloggedin,
                $validations,
                $logout_hourly;

         $validations ++;
         $hashvar     = ($logout_hourly) ? date("F d, Y H") : date("F d, Y") ;
         return (md5($hashvar) == $isloggedin) ? TRUE : FALSE ;
}
?>