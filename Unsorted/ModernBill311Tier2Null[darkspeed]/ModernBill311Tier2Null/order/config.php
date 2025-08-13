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


/*
# M O D I F Y  M E  F I R S T ! ! ! ! !
# $DIR is the directory you installed the Vortech Signup Files...
# It should be relative to the ModernBill "include" directory.
#
# modernbill/include/ <-- Key Directory
# modernbill/order/   <-- $DIR = "../"
*/

$DIR         = "../";
$this_DIR    = "order";
$this_TYPE   = "vortech_type1";

## DO NOT CHANGE START
include($DIR."include/misc/session_functions.inc.php");
session_set_save_handler("sess_mysql_open","","sess_mysql_read","sess_mysql_write","sess_mysql_destroy","sess_mysql_gc");
session_start();
session_register("set_language");
$new_language = ($set_language) ? $set_language : NULL ;
$signup_form = TRUE;
include($DIR."include/functions.inc.php");
## DO NOT CHANGE STOP

/*
# DEFINE YOUR ORDER FORM URLs
# (can be read from include/config/config.locale.php)
*/

# FULL SSL url of Vortech directory
$script_url            = "$https://$secure_url"."$this_DIR/";

# FULL NON-SSL url of Vortech directory
$script_url_non_secure = "http://$standard_url"."$this_DIR/";

/*
# EXTRA SIGNUP SECURITY SETTING
# -----------------------------
# The Vortech Signup form sends the CC Number in a Plain Text email to teh admin.
# TRUE = SENDS "XXXX-XXXX-XXXX-LAST4" IN SIGNUP EMAIL TO ADMIN
# FALSE = SENDS "FULL CC NUMBER" IN SIGNUP EMAIL TO ADMIN
*/
$send_secure_cc = TRUE;

## DO NOT CHANGE START
$dbh=mysql_pconnect($locale_db_host,$locale_db_login,$locale_db_pass) or die("Problem with dB connection!");
mysql_select_db($locale_db_name,$dbh) or die("Problem with dB connection!");
$this_vortech_config=mysql_fetch_array(mysql_query("SELECT * FROM config WHERE config_type='$this_TYPE'"));
$company_name          = $this_vortech_config["config_1"];
$company_url           = $this_vortech_config["config_2"];
$path_to_terms         = $this_vortech_config["config_3"];
$admin_email           = $this_vortech_config["config_4"];
$order_email           = $this_vortech_config["config_5"];
$company_address       = $this_vortech_config["config_6"];
$disable_whois         = $this_vortech_config["config_7"]; // v3.0.7
$allow_xyear           = $this_vortech_config["config_8"];
$xyear_name            = $this_vortech_config["config_9"]; //v3.0.9
$insert_new_clients    = $this_vortech_config["config_10"];
$default_server_name   = $this_vortech_config["config_11"];
$default_server_type   = $this_vortech_config["config_12"];
$allow_domain_transfer = $this_vortech_config["config_13"]; // v3.0.7
$allow_buy_domain_only = $this_vortech_config["config_14"];
$allow_whois_www_link  = $this_vortech_config["config_15"];
$allow_domain_skip     = $this_vortech_config["config_16"]; // v3.0.7
$allow_domain_username = $this_vortech_config["config_17"];
$allow_domain_password = $this_vortech_config["config_18"];
$allow_credit_card     = $this_vortech_config["config_19"];
$allow_invoice         = $this_vortech_config["config_20"];
$allow_paypal          = $this_vortech_config["config_21"];
$allow_pro_rate_billing= $this_vortech_config["config_22"];
$allow_signup_charge   = $this_vortech_config["config_23"];
$require_cvvc_code     = $this_vortech_config["config_24"];
$this_pack_display     = $this_vortech_config["config_25"];
$allow_domain_register = $this_vortech_config["config_26"]; // v3.0.9
$allow_monthly         = $this_vortech_config["config_27"];
$monthly_name          = $this_vortech_config["config_28"];
$allow_quarterly       = $this_vortech_config["config_29"];
$quarterly_name        = $this_vortech_config["config_30"];
$allow_semiannual      = $this_vortech_config["config_31"];
$semiannual_name       = $this_vortech_config["config_32"];
$allow_annual          = $this_vortech_config["config_33"];
$annual_name           = $this_vortech_config["config_34"];
$table_width           = $this_vortech_config["config_35"];
$default_ban_message   = $this_vortech_config["config_36"];
$outerborder           = $this_vortech_config["config_37"];
$innerborder           = $this_vortech_config["config_38"];
$headercolor           = $this_vortech_config["config_39"];
$headertextcolor       = $this_vortech_config["config_40"];
$tablebgcolor          = $this_vortech_config["config_41"];
$tablebgcolor2         = $this_vortech_config["config_42"];
$allow_email_to_client = $this_vortech_config["config_43"];
$display_package_types = $this_vortech_config["config_44"];
$display_package_comparisons = $this_vortech_config["config_45"];
$allow_referrer        = $this_vortech_config["config_46"];
$use_html_signup_email = $this_vortech_config["config_48"];
$allow_worldpay        = $this_vortech_config["config_49"];
$prorate_threshhold    = $this_vortech_config["config_50"];
$package_menu_display_order  = ($this_vortech_config["config_51"]) ? $this_vortech_config["config_51"] : "pack_name"; //v3.1.0
$package_menu_display_type   = ($this_vortech_config["config_52"]) ? $this_vortech_config["config_52"] : 2; //v3.1.0
$enable_banned_email_checks  = ($this_vortech_config["config_53"]); //v3.1.0
$referrer_array        = explode("|",trim($this_vortech_config["config_47"]));
$count = count($referrer_array);
if ($count == 1) {
   list($value,$name)=explode("=",$referrer_array[0]);
   $referrer_options = "<option value=\"".trim($value)."\">".trim($name)."</option>";
} elseif ($count > 1) {
   for ($i = 0; $i <= $count - 1; $i++) {
       list($value,$name)=explode("=",$referrer_array[$i]);
       $referrer_options .= "<option value=\"".trim($value)."\">".trim($name)."</option>";
   }
}
$pack_display     = ($this_pack_display) ? $this_pack_display : 1 ;
$password_length  = ($password_length) ? $password_length : 6 ;
$creditcard_list  = $we_accept;
$allow_echeck     = FALSE;
$include_header   = TRUE;
$include_footer   = TRUE;
$include_css      = TRUE;
$contract_pricing = TRUE;
$suppress_price_from_comparison = TRUE;
## DO NOT CHANGE STOP
?>