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

$dbh=mysql_pconnect($locale_db_host,$locale_db_login,$locale_db_pass) or die("Problem with dB connection!");
mysql_select_db($locale_db_name,$dbh) or die("Problem with dB connection!");
$this_payments_config=mysql_fetch_array(mysql_query("SELECT * FROM config WHERE config_type='payments'"));
##
## Additional PayPal Variables
## ---------------------------
$pp_item_number   = NULL; # <-- Populates with Invoice Number or ClientPackage ID
$pp_amount        = NULL; # <-- Populates with Invoice Amount or Signup Amount
$pp_add           = "1";
$pp_no_note       = "1";
$pp_undefined_quantity = "1";
$pp_no_shipping   = "1";
##
##
$store_cc_in_db   = $this_payments_config["config_1"];
$acc_visa         = $this_payments_config["config_2"];
$acc_mast         = $this_payments_config["config_3"];
$acc_discover     = $this_payments_config["config_4"];
$acc_amex         = $this_payments_config["config_5"];
$acc_jbc          = $this_payments_config["config_6"];
$acc_enroute      = $this_payments_config["config_7"];
$acc_diners       = $this_payments_config["config_8"];
$we_accept        = $this_payments_config["config_9"];
$authnet_enabled  = $this_payments_config["config_10"];
$x_Gateway        = $this_payments_config["config_11"];
$x_Login          = $this_payments_config["config_12"];
$x_Version        = $this_payments_config["config_13"];
$x_Test_Request   = ($this_payments_config["config_14"]) ? "TRUE" : "FALSE" ;
$x_Email_Customer = ($this_payments_config["config_15"]) ? "TRUE" : "FALSE" ;
$x_Description    = $this_payments_config["config_16"];
$paypal_enabled   = $this_payments_config["config_17"];
$pp_url           = $this_payments_config["config_18"];
$pp_business      = $this_payments_config["config_19"];
$pp_return        = $this_payments_config["config_20"];
$pp_cancel_return = $this_payments_config["config_21"];
$pp_image_url     = $this_payments_config["config_22"];
$pp_submit_button = $this_payments_config["config_23"];
$pp_item_name     = $this_payments_config["config_24"];
$checkout_enabled = FALSE; //$this_payments_config["config_25"];
$checkout_sid     = NULL;  //($this_payments_config["config_26"]);
$checkout_test    = NULL;  //($this_payments_config["config_27"]) ? "Y" : NULL ;
// = ($this_payments_config["config_28"]);
$paypal_id        = $this_payments_config["config_29"];
$echo_enabled     = $this_payments_config["config_30"];
$echo_server      = $this_payments_config["config_31"];
$merchant_echo_id = $this_payments_config["config_32"];
$merchant_pin     = $this_payments_config["config_33"];
$tax_enabled      = $this_payments_config["config_34"];
$tax_type         = $this_payments_config["config_35"];
$tax_number       = $this_payments_config["config_36"];
$tax_amount       = $this_payments_config["config_37"];
$echo_debug       = ($this_payments_config["config_38"]) ? "C" : "F" ;
$worldpay_enabled = $this_payments_config["config_39"];
$wp_url           = $this_payments_config["config_40"];
$wp_testmode      = $this_payments_config["config_41"];
$wp_istid         = $this_payments_config["config_42"];
$wp_currency      = $this_payments_config["config_43"];
$worldpay_id      = $this_payments_config["config_44"];
$wp_return_pw     = $this_payments_config["config_45"];
?>