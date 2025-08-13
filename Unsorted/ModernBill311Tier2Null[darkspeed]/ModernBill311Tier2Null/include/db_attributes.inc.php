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
// SET A FEW DEFAULT VARIABLES
$textarea_rows = 6;
$textarea_cols = 55;
$textarea_wrap = "VIRTUAL";
$tables_path   = "include/db_tables";
$default_date  = date($date_format);

switch ($db_table) {
        case account_dbs:           include($DIR."$tables_path/$db_table.table.php"); break;
        case account_pops:          include($DIR."$tables_path/$db_table.table.php"); break;
        case account_details:       include($DIR."$tables_path/$db_table.table.php"); break;
        case admin:                 include($DIR."$tables_path/$db_table.table.php"); break;
        case affiliate_config:      include($DIR."$tables_path/$db_table.table.php"); break;
        case banned_config:         include($DIR."$tables_path/$db_table.table.php"); break;
        case client_credit:         include($DIR."$tables_path/$db_table.table.php"); break;
        case client_info:           include($DIR."$tables_path/$db_table.table.php"); break;
        case client_invoice:        include($DIR."$tables_path/$db_table.table.php"); break;
        case client_news:           include($DIR."$tables_path/$db_table.table.php"); break;
        case client_package:        include($DIR."$tables_path/$db_table.table.php"); break;
        case client_register:       include($DIR."$tables_path/$db_table.table.php"); break;
        case config:                include($DIR."$tables_path/$db_table.table.php"); break;
        case coupon_codes:          include($DIR."$tables_path/$db_table.table.php"); break;
        case domain_names:          include($DIR."$tables_path/$db_table.table.php"); break;
        case email_config:          include($DIR."$tables_path/$db_table.table.php"); break;
        case event_log:             include($DIR."$tables_path/$db_table.table.php"); break;
        case faq_categories:        include($DIR."$tables_path/$db_table.table.php"); break;
        case faq_questions:         include($DIR."$tables_path/$db_table.table.php"); break;
        case package_feature:       include($DIR."$tables_path/$db_table.table.php"); break;
        case package_relationships: include($DIR."$tables_path/$db_table.table.php"); break;
        case package_type:          include($DIR."$tables_path/$db_table.table.php"); break;
        case support_desk:          include($DIR."$tables_path/$db_table.table.php"); break;
        case support_log:           include($DIR."$tables_path/$db_table.table.php"); break;
        case tld_config:            include($DIR."$tables_path/$db_table.table.php"); break;
        case todo_list:             include($DIR."$tables_path/$db_table.table.php"); break;
        case whois_stats:           include($DIR."$tables_path/$db_table.table.php"); break;

        /*-- TIER2 --*/
        case authnet_batch:         if ($tier2) include($DIR."$tables_path/$db_table.table.php"); break;
        case batch_details:         if ($tier2) include($DIR."$tables_path/$db_table.table.php"); break;
}

$select_sql = ($recursive&&$recursive_sql) ? $recursive_sql  : $select_sql;
$select_sql = ($use_user_select&&$user_select_sql) ? $user_select_sql  : $select_sql;
$update_sql = ($make_payments&&$pay_update_sql) ? $pay_update_sql : $update_sql;
?>