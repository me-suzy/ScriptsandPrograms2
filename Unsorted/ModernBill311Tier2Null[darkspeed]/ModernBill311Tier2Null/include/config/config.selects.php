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

$dbh=mysql_pconnect($locale_db_host,$locale_db_login,$locale_db_pass) or die("Problem with dB connection!");
mysql_select_db($locale_db_name,$dbh) or die("Problem with dB connection!");
$this_payments_config=mysql_fetch_array(mysql_query("SELECT * FROM config WHERE config_type='payments'"));
##
## <-- S T A R T   C O N F I G U R A T I O N --> #
## VARIABLES FOR: include/misc/db_select_menus.inc.php
## DO NOT CHANGE ONCE _ANY_ DATA IS INSERTED INTO THE DB!!!
$category_types    = array(1=>USER,2=>ADMIN);
$ban_types         = array(1=>IP,2=>EMAIL);
$db_types          = array(1=>MYSQL,2=>MSSQL,3=>POSTGRES);
$tax_types         = array(1=>SALESTAX,2=>VAT);
$priority_types    = array(4=>LOW,3=>NORMAL,2=>HIGH,1=>EMERGENCY);
$call_status_types = array(1=>NEW_t,2=>OPEN,3=>CLOSED);
$true_false        = array(1=>YES,0=>NO);
$monitor_types     = array(1=>YES,2=>NO);
$status_types      = array(1=>INACTIVE,2=>ACTIVE,3=>PENDING,4=>CANCELED,5=>FRAUD);
$log_types         = array(1=>ADMIN,2=>USER,3=>SYSTEM);
$todo_types        = array(1=>NEW_t,2=>WIP,3=>PENDING,4=>COMPLETED,5=>POSTPONED);
$vortech_sort_types         = array("pack_id"=>"pack_id","pack_name"=>"pack_name");
$vortech_pack_display_types = array("2"=>"v3 ".DISPLAY,"3"=>"v2 ".DISPLAY);

##
## LANGUAGE TRANSLATION TYPES ARRAY
## To add support for another language, you need to make a new
## translation file, ex. /include/translations/english.trans.inc.php
## and add your language name here.
$language_types = get_dir_array($DIR."include/translations/");
##
## PACKAGE DISPLAY TYPES
## The Vortech Signup Form will only display packages you designate!
## You can have more that one signup form and designate which plans go with which
## form.
$pack_display_result = mysql_query("SELECT * FROM config WHERE config_type LIKE '%vortech%' ORDER BY config_type");
$pack_display_types  = array("0" => SELECT);
while(list($this_type) = mysql_fetch_array($pack_display_result)) {
    $this_type = explode("_",$this_type);
    list($null,$count) = explode("e",$this_type[1]);
    $pack_display_types[trim($count)] = ucwords($this_type[1]);
}
##
## THEMES TYPES ARRAY
## To add support for another theme, you need to make a new theme dir
## and config file, EX: /include/config/themes/blue/theme.config.inc.php
## and add your theme name here.
$theme_types = get_dir_array($DIR."include/config/themes/");
##
## PAYMENT TYPES ARRAY (What are you willing to accept from clients?)
## Any changes to this function will required modification to the
## generate_invoices script in: include/scripts/generate_invoices.inc.php
## WARNING: CCBATCH MUST ALWAYS BE 1
## WARNING: CHECK MUST ALWAYS BE 2
## "OTHER" set at 50 to allow room to add your own
$payment_types    = array("1"  => CCBATCH,
                          "3"  => CCSINGLE,
                          "2"  => CHECK,
                          "4"  => ECHECK,
                          "50" => OTHER);
if ($tier2&&$this_payments_config["config_17"]) $payment_types[5] = PAYPAL;
if ($tier2&&$this_payments_config["config_44"]) $payment_types[6] = WORLDPAY;
##
## BILLING METHODS ARRAY (How do you bill clients?)
## Any changes to this function will required modification to the
## generate_invoices script in: include/scripts/generate_invoices.inc.php
## WARNING: CCBATCH MUST ALWAYS BE 1
## WARNING: CHECK MUST ALWAYS BE 2
## "OTHER" set at 50 to allow room to add your own
$billing_types    = array("1"  => CCBATCH,
                          "3"  => CCSINGLE,
                          "2"  => CHECK,
                          "4"  => ECHECK,
                          "50" => OTHER);
if ($tier2&&$this_payments_config["config_17"]) $billing_types[5] = PAYPAL;
if ($tier2&&$this_payments_config["config_44"]) $billing_types[6] = WORLDPAY;
##
## BILLING CYCLE TYPES ARRAY (What billing cycles do you offer clients?)
## Any changes to this function will required modification to the
## generate_invoices script in: include/scripts/generate_invoices.inc.php
## WARNING: ONETIME VALUE MUST ALWAYS BE 100!
$cycle_types      = array("1"   => MONTHLY,
                          "3"   => QUARTERLY,
                          "6"   => SEMIANNUALLY,
                          "12"  => ANNUALLY,
                          "24"  => TWOYEARS,
                          "103" => ONETIME.": ".QUARTERLY,
                          "106" => ONETIME.": ".SEMIANNUALLY,
                          "112" => ONETIME.": ".ANNUALLY,
                          "100" => ONETIME.": ".NORENEWAL,
                          "111" => DOMAIN.": ".ONEYEAR,
                          "124" => DOMAIN.": ".TWOYEARS,
                          "136" => DOMAIN.": ".THREEYEARS,
                          "148" => DOMAIN.": ".FOURYEARS,
                          "160" => DOMAIN.": ".FIVEYEARS,
                          "172" => DOMAIN.": ".SIXYEARS,
                          "184" => DOMAIN.": ".SEVENYEARS,
                          "196" => DOMAIN.": ".EIGHTYEARS,
                          "1108" => DOMAIN.": ".NINEYEARS,
                          "1120" => DOMAIN.": ".TENYEARS);
##
## Affiliate Types
$affiliate_cycles = array("1"   => MONTHLY,
                          "100" => ONETIME.": ".NORENEWAL);

$affiliate_pay_types = array("1" => PERCENTAGE,
                             "2" => FLATFEE);

##
## DATE FORMATS
$date_format_types = array("Y/m/d" => "YYYY/MM/DD",
                           "d/m/Y" => "DD/MM/YYYY",
                           "m/d/Y" => "MM/DD/YYYY");
$this_date_display  = $date_format_types[$date_format];
define(_DATE_FORMAT_, "$this_date_display");
## <-- E N D   C O N F I G U R A T I O N --> ##
?>