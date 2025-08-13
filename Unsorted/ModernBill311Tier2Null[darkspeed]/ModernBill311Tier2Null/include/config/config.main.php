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

## EXTRA CONFIG SECURITY SETTING
## -----------------------------
## You can disable the online config changes once you have everything setup.
## TRUE = NO ONLINE EDIT
## FALSE = YES ONLINE EDIT
$disable_config_changes = FALSE;
$disable_right_click    = TRUE;
##
##
## DEFINE CURRENT VERSION & OS
## ---------------------------
## \r\n = WINDOWS
## \n   = *NIX
define(CTRL,"\n");
##
##
## DEFINE FILE_NAMES FOR EACH ADMIN, USER, & INDEX FILES
## -----------------------------------------------------
## You can rename the files to whatever you wish.
## However. but MUST update the names here first.
$admin_page = "admin.php";
$user_page  = "user.php";
$login_page = "index.php";
##
##
## FOR MONTHLY BILLING ONLY -- DO NOT CHANGE
## -----------------------------------------
$renew_on_this_day = 1;
##
##
$display_faq_link = TRUE;
$display_support_desk_link = TRUE;
$display_news_link = TRUE;
$domain_whois_server_for_hotlink = "http://www.truewhois.com/print_version.php?domain={DOMAIN}";


$dbh=mysql_connect($locale_db_host,$locale_db_login,$locale_db_pass) or die("Problem with dB connection!");
mysql_select_db($locale_db_name,$dbh) or die("Problem with dB connection!");
$this_main_config=@mysql_fetch_array(mysql_query("SELECT * FROM config WHERE config_type='main'"));
##
##
$debug              = $this_main_config["config_1"];
$language_enabled   = $this_main_config["config_2"];
$default_language   = $this_main_config["config_3"];
$currency           = $this_main_config["config_4"];
$theme_enabled      = $this_main_config["config_5"];
$default_theme      = $this_main_config["config_6"];
$selectlimit        = $this_main_config["config_7"];
$password_length    = $this_main_config["config_8"];
$enable_manual_server_names = $this_main_config["config_9"]; // v3.0.7
$enable_nl2br       = $this_main_config["config_10"]; // v3.0.7
//$this_main_config["config_11"];
$anniversary_billing= $this_main_config["config_12"];
$batch_delim        = $this_main_config["config_13"];
$batch_export_file  = $this_main_config["config_14"];
$prefix             = $this_main_config["config_15"];
$logout_hourly      = $this_main_config["config_16"];
$bad_words          = ($this_main_config["config_17"]) ? explode(",",$this_main_config["config_17"]) : array("{BAD}") ;
$cuttext_off        = $this_main_config["config_18"];
$cuttextlimit       = $this_main_config["config_19"];
$user_login_url     = $this_main_config["config_20"];
$user_contact_info  = $this_main_config["config_21"];
//$this_main_config["config_22"];
$invoice_address    = $this_main_config["config_23"];
//$this_main_config["config_24"];
//$this_main_config["config_25"];
//$this_main_config["config_26"];
$dd_static          = $this_main_config["config_27"];
$due_on_this_day    = $this_main_config["config_28"];
//$this_main_config["config_29"];
$registrar_types    = explode(",",trim($this_main_config["config_30"]));
$server_types       = explode(",",trim($this_main_config["config_31"]));
$path_to_curl       = ($this_main_config["config_32"]) ? $this_main_config["config_32"] : "/usr/bin/curl" ;
$server_names       = explode(",",trim($this_main_config["config_33"]));
$we_are_closed      = $this_main_config["config_34"];
$why_are_we_closed  = $this_main_config["config_35"];
$date_format        = $this_main_config["config_36"];
$user_help_docs     = $this_main_config["config_41"];
##
##
my_array_shift($registrar_types);
my_array_shift($server_types);
my_array_shift($server_names);

## --> CURRENCY CONVERTER FUNCTION
##
## NOTE: The default currency is US. You can add a different currency by
##       adding another case statemant and setting the $currecy variable
##       to your currency of choice:
##       --> Send your new statement to ModernBIll to be included with
##           the next distrabution.
##
##              EX: $currency="US";
##
##       By default, the "$" symbol is displayed. Set $nosymbol=1 to suppress it.
##
##              EX: display_currecy($some_var,1);
##
function display_currency($money,$nosymbol=0)
{
         GLOBAL $currency;
         switch ($currency)
         {
            case US:    return ($nosymbol) ? number_format($money,2) : "\$".number_format($money,2) ; break;
            case EURO:  return ($nosymbol) ? number_format($money,2) : "&euro;".number_format($money,2) ; break;
            case YEN:   return ($nosymbol) ? number_format($money,2) : "&yen;".number_format($money,2) ; break;
            case POUND: return ($nosymbol) ? number_format($money,2) : "&pound;".number_format($money,2) ; break;
            // add your format here!
            default: return ($nosymbol) ? number_format($money,2) : "\$".number_format($money,2) ; break;
         }
}
?>