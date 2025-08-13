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

## [DO NOT MODIFY/REMOVE BELOW]
## ----------------------------
$tier2   = TRUE;
$version = "3.1.1:TS";

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

//set_error_handler("custom_error_handler");
##
##
function my_array_shift(&$array)
{
         array_push($array,$array[0]);
         reset($array);
         $i = 0;
         $newArray = array();
         while(list($key,$val) = each($array)) {
           if ($i > 0) {
              $newArray[$key] = $val;
           } else {
              $toReturn = $array[$key];
           }
           $i++;
         }
         $array = $newArray;
         asort ($array);
         reset ($array);
         return (!is_array($toReturn)) ? array(1=>$toReturn) : $toReturn ;
}
##
##
function get_dir_array($path)
{
        $handle=opendir($path);
        while ($file = readdir($handle)) {
            if ($file != "." && $file != "..") {
                $file = explode(".",$file);
                $array[$file[0]] = ucwords($file[0]);
            }
        }
        closedir($handle);
        return $array;
}
##
## DB, Authentication, & Config Variables
##
$DIR = ( $DIR && !$HTTP_POST_VARS[DIR] && !$HTTP_GET_VARS[DIR]) ? $DIR : NULL ;
$signup_form = ( $signup_form && !$HTTP_POST_VARS[signup_form] && !$HTTP_GET_VARS[signup_form]) ? $signup_form : NULL ;
include($DIR."include/config/config.locale.php");
if(!$signup_form&&($argv[2]!="cron"))include_once($DIR."include/misc/auth.inc.php");
include_once($DIR."include/config/config.main.php");
##
## Define the language to use
##
GLOBAL $language;
$language = (!isset($language)) ? $default_language : $language ;
$language = ($new_language)     ? $new_language     : $language ;
$_SESSION[language] = $language = preg_replace("/[^a-zA-Z]/", "", $language);
if(!$signup_form&&($argv[2]!="cron"))session_register('language');
$translation_file = (file_exists($DIR."include/translations/$language.trans.inc.php")) ? $language : $default_language ;
include_once($DIR."include/translations/$translation_file.trans.inc.php");
##
## Define the theme to use
##
GLOBAL $theme;
$theme = (!isset($theme)) ? $default_theme : $theme ;
$theme = ($new_theme)     ? $new_theme     : $theme ;
$_SESSION[theme] = $theme = preg_replace("/[^a-zA-Z]/", "", $theme);
if(!$signup_form&&($argv[2]!="cron"))session_register('theme');
$theme_dir = (file_exists($DIR."include/config/themes/$theme/theme.config.inc.php")) ? $theme : $default_theme ;
include_once($DIR."include/config/themes/$theme_dir/theme.config.inc.php");
##
## Main Functions
## RooooooAAAArrr
include_once($DIR."include/config/config.selects.php");
include_once($DIR."include/config/config.email.php");
include_once($DIR."include/config/config.payments.php");
include_once($DIR."include/misc/cc_validate.class.php");
include_once($DIR."include/misc/db_core_logic.inc.php");
include_once($DIR."include/misc/db_functions.inc.php");
include_once($DIR."include/misc/db_select_menus.inc.php");
include_once($DIR."include/misc/db_sql_select_menus.inc.php");
include_once($DIR."include/misc/vortech_functions.inc.php");
include_once($DIR."include/misc/email_functions.inc.php");
?>