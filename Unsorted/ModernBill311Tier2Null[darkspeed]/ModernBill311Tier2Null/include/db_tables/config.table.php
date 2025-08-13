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
## [DO NOT MODIFY/REMOVE BELOW]
##
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

$title          = SYSTEMCONFIG;
$disable_delete = TRUE;
$config_path    = "include/db_tables/cases";

if (eregi("theme_",$config_type))
{
    $old_config_type = $config_type;
    $config_type = "theme";
}
if (eregi("vortech_",$config_type))
{
    $old_config_type = $config_type;
    $config_type = "vortech";
}

switch ($config_type) {

        case email:    include($DIR."$config_path/$config_type.case.php"); break;
        case main:     include($DIR."$config_path/$config_type.case.php"); break;
        case payments: include($DIR."$config_path/$config_type.case.php"); break;
        case theme:    $config_type = $old_config_type; include($DIR."$config_path/theme.case.php");   break;
        case vortech:  $config_type = $old_config_type; include($DIR."$config_path/vortech.case.php"); break;
        case client_extras_1_5:  include($DIR."$config_path/$config_type.case.php"); break;
        case client_extras_6_10: include($DIR."$config_path/$config_type.case.php"); break;
}

$update_sql = "UPDATE config SET  config_1='$config_1',
                                  config_2='$config_2',
                                  config_3='$config_3',
                                  config_4='$config_4',
                                  config_5='$config_5',
                                  config_6='$config_6',
                                  config_7='$config_7',
                                  config_8='$config_8',
                                  config_9='$config_9',
                                  config_10='$config_10',
                                  config_11='$config_11',
                                  config_12='$config_12',
                                  config_13='$config_13',
                                  config_14='$config_14',
                                  config_15='$config_15',
                                  config_16='$config_16',
                                  config_17='$config_17',
                                  config_18='$config_18',
                                  config_19='$config_19',
                                  config_20='$config_20',
                                  config_21='$config_21',
                                  config_22='$config_22',
                                  config_23='$config_23',
                                  config_24='$config_24',
                                  config_25='$config_25',
                                  config_26='$config_26',
                                  config_27='$config_27',
                                  config_28='$config_28',
                                  config_29='$config_29',
                                  config_30='$config_30',
                                  config_31='$config_31',
                                  config_32='$config_32',
                                  config_33='$config_33',
                                  config_34='$config_34',
                                  config_35='$config_35',
                                  config_36='$config_36',
                                  config_37='$config_37',
                                  config_38='$config_38',
                                  config_39='$config_39',
                                  config_40='$config_40',
                                  config_41='$config_41',
                                  config_42='$config_42',
                                  config_43='$config_43',
                                  config_44='$config_44',
                                  config_45='$config_45',
                                  config_46='$config_46',
                                  config_47='$config_47',
                                  config_48='$config_48',
                                  config_49='$config_49',
                                  config_50='$config_50',
                                  config_51='$config_51',
                                  config_52='$config_52',
                                  config_53='$config_53',
                                  config_54='$config_54',
                                  config_55='$config_55',
                                  config_56='$config_56',
                                  config_57='$config_57',
                                  config_58='$config_58',
                                  config_59='$config_59',
                                  config_60='$config_60' WHERE config_type='$config_type'";

$update_sql = ($disable_config_changes) ? NULL : $update_sql;

if ($this_admin[admin_level]!=9) {
    $update_sql = $delete_sql = NULL;
}
?>