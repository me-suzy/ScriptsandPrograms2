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

########################################
######### PAYMENTS CONFIG ##############
########################################
      $args = array(array("column"         => "config_type",
                           "required"      => 0,
                           "title"         => ID,
                           "type"          => "HIDDEN"),

# Tax Config #######################################################################################
                    array("type"           => "HEADERROW",
                           "title"         => "Tax Configuration"),

                    array("column"         => "config_34",
                           "required"      => 0,
                           "title"         => "Enable Tax Calculations",
                           "type"          => "FUNCTION_CALL",
                           "function_call" => true_false_radio("config_34",$config_34),
                           "append"        => "<br>Set to <b>YES</b> to enable tax calculations in the vortech signup form and all invoices.
                                                   Set the percentage below.
                                               <br><br>"),

                    array("column"         => "config_37",
                           "required"      => 0,
                           "title"         => "Set Tax Percentage",
                           "type"          => "TEXT",
                           "size"          => 10,
                           "maxlength"     => 10,
                           "append"        => "<br>FORMAT: 8.5% = \"<b>.085</b>\"
                                               <br><br>"),

                    array("column"         => "config_35",
                           "required"      => 0,
                           "title"         => "Set Tax Type",
                           "type"          => "FUNCTION_CALL",
                           "function_call" => tax_type_select_box("config_35",$config_35),
                           "append"        => "<br>Select the tax type to calculate.
                                               <br><br>"),

                    array("column"         => "config_36",
                           "required"      => 0,
                           "title"         => "Set Tax/VAT ID Number",
                           "type"          => "TEXT",
                           "size"          => 10,
                           "maxlength"     => 255,
                           "append"        => "<br>If you need to display your Tax/VAT ID Number, then enter it here.
                                                   Otherwise, leave it blank.
                                               <br><br>"),

# Credit Card Config #######################################################################################
                    array("type"           => "HEADERROW",
                           "title"         => "Credit Card Configuration"),

                    array("column"         => "config_1",
                           "required"      => 0,
                           "title"         => "Enable Storing of Encrypted Credit Card Numbers in DB",
                           "type"          => "TEXT",
                           "size"          => 10,
                           "maxlength"     => 10,
                           "append"        => "<br>We do not recommend storing any credit card information online!
                                                   This is disabled by default.
                                                   If you are going against our recommendation, do so <b>AT YOUR OWN RISK</b>.
                                                   You will need type \"<b>agree</b>\" to enable this feature.
                                               <br><br>"),

                    array("column"         => "config_2",
                           "required"      => 0,
                           "title"         => "Enable Credit Card [Visa]",
                           "type"          => "FUNCTION_CALL",
                           "function_call" => true_false_radio("config_2",$config_2),
                           "append"        => "<br>Set to <b>YES</b> to allow <b>Visa</b> orders.
                                               <br><br>"),

                    array("column"         => "config_3",
                           "required"      => 0,
                           "title"         => "Enable Credit Card [MasterCard]",
                           "type"          => "FUNCTION_CALL",
                           "function_call" => true_false_radio("config_3",$config_3),
                           "append"        => "<br>Set to <b>YES</b> to allow <b>MasterCard</b> orders.
                                               <br><br>"),

                    array("column"         => "config_4",
                           "required"      => 0,
                           "title"         => "Enable Credit Card [Discover]",
                           "type"          => "FUNCTION_CALL",
                           "function_call" => true_false_radio("config_4",$config_4),
                           "append"        => "<br>Set to <b>YES</b> to allow <b>Discover</b> orders.
                                               <br><br>"),

                    array("column"         => "config_5",
                           "required"      => 0,
                           "title"         => "Enable Credit Card [American Express]",
                           "type"          => "FUNCTION_CALL",
                           "function_call" => true_false_radio("config_5",$config_5),
                           "append"        => "<br>Set to <b>YES</b> to allow <b>American Express</b> orders.
                                               <br><br>"),

                    array("column"         => "config_6",
                           "required"      => 0,
                           "title"         => "Enable Credit Card [JBC]",
                           "type"          => "FUNCTION_CALL",
                           "function_call" => true_false_radio("config_6",$config_6),
                           "append"        => "<br>Set to <b>YES</b> to allow <b>JBC</b> orders.
                                               <br><br>"),

                    array("column"         => "config_7",
                           "required"      => 0,
                           "title"         => "Enable Credit Card [Enroute]",
                           "type"          => "FUNCTION_CALL",
                           "function_call" => true_false_radio("config_7",$config_7),
                           "append"        => "<br>Set to <b>YES</b> to allow <b>Enroute</b> orders.
                                               <br><br>"),

                    array("column"         => "config_8",
                           "required"      => 0,
                           "title"         => "Enable Credit Card [Diners Club]",
                           "type"          => "FUNCTION_CALL",
                           "function_call" => true_false_radio("config_8",$config_8),
                           "append"        => "<br>Set to <b>YES</b> to allow <b>Diners Club</b> orders.
                                               <br><br>"),

                    array("column"         => "config_9",
                           "required"      => 0,
                           "title"         => "Set Credit Card Display",
                           "type"          => "TEXT",
                           "size"          => 40,
                           "maxlength"     => 255,
                           "append"        => "<br>Type the names of the credit cards you will accept.
                                                   This will display in the Vortech Signup Form.
                                                   Example: \"<b>Visa, Mast, Disc, Amex</b>\"
                                               <br><br>"),

# Authnet Like Config #######################################################################################
                    array("type"           => "HEADERROW",
                           "title"         => "Authorize.net, PlanetPayment, QuickCommerce, eProcessingNetwork,<br>RTWare, & MerchantCommerce Configuration [Tier2 Only]"),

                    array("column"         => "config_10",
                           "required"      => 0,
                           "title"         => "Enable Authnet Style Gateways",
                           "type"          => "FUNCTION_CALL",
                           "function_call" => true_false_radio("config_10",$config_10),
                           "append"        => "<br>You must have your OWN gateway account to use this feature!
                                                   We currently support: <B>Authorize.net, PlanetPayment, QuickCommerce, eProcessingNetwork, RTWare, & MerchantCommerce</B>
                                               <br><br>"),

                    array("column"         => "config_11",
                           "required"      => 0,
                           "title"         => "Set the Authnet Gateway URL",
                           "type"          => "TEXT",
                           "size"          => 13,
                           "maxlength"     => 13,
                           "append"        => "ENTER ONLY ONE:<br>
                                               \"<b>authorize</b>\" (Authorize.net)<br>
                                               \"<b>planetpayment</b>\" (PlanetPayment.com)<br>
                                               \"<b>ecx</b>\" (QuickCommerce.net)<br>
                                               \"<b>epn</b>\" (eProcessingNetwork.com)<br>
                                               \"<b>rtware</b>\" (RTWare.net)<br>
                                               \"<b>mcps</b>\" (MerchantCommerce.net)<br>
                                               <br>"),

                    array("column"         => "config_12",
                           "required"      => 0,
                           "title"         => "Set Your Login ID, Password",
                           "type"          => "TEXT",
                           "size"          => 20,
                           "maxlength"     => 100,
                           "append"        => "<br>Enter your account id used to identify you as a merchant.
                                                   You may also use your password.
                                                   <br>
                                                   <b>FORMAT:</b> login_id|login_password
                                               <br><br>"),

                    array("column"         => "config_13",
                           "required"      => 0,
                           "title"         => "Set the Current Version",
                           "type"          => "TEXT",
                           "size"          => 4,
                           "maxlength"     => 5,
                           "append"        => "<br>Default <b>3.1</b>.
                                                   You may set to the current Authnet Version.
                                                   Please contact us when a newer version is available.
                                               <br><br>"),

                    array("column"         => "config_14",
                           "required"      => 0,
                           "title"         => "Enable Test Transaction",
                           "type"          => "FUNCTION_CALL",
                           "function_call" => true_false_radio("config_14",$config_14),
                           "append"        => "<br>Set to <b>YES</b> to test your Vortech Signup or Batch functions.
                                                   You may use 5400-0000-0000-0005 as a test credit card with any expiration date.
                                               <br>Set to <b>NO</b> for LIVE transactions.
                                               <br><br>"),

                    array("column"         => "config_15",
                           "required"      => 0,
                           "title"         => "Enable Generic Authnet Emails",
                           "type"          => "FUNCTION_CALL",
                           "function_call" => true_false_radio("config_15",$config_15),
                           "append"        => "<br>Set to <b>NO</b> to suppress the default Authnet Email.
                                               <br><br>"),

                    array("column"         => "config_16",
                           "required"      => 0,
                           "title"         => "Set the Authnet Invoice Description",
                           "type"          => "TEXT",
                           "size"          => 40,
                           "maxlength"     => 255,
                           "append"        => "<br>Define the service you offer in short, generic terms.
                                                   It will be used when sending transactions to Authnet Gateways.
                                               <br><br>"),

# Echo Config #######################################################################################
                    array("type"           => "HEADERROW",
                           "title"         => "Echo Configuration [Tier2 Only]"),

                    array("column"         => "config_30",
                           "required"      => 0,
                           "title"         => "Enable Echo Gateway",
                           "type"          => "FUNCTION_CALL",
                           "function_call" => true_false_radio("config_30",$config_30),
                           "append"        => "<br>You must have your OWN gateway account to use this feature!
                                                   NOTE: You can only enable one gateway, either Echo or Authent above.
                                                   If both are enabled, Authent will be used by default.
                                               <br><br>"),

                    array("column"         => "config_38",
                           "required"      => 0,
                           "title"         => "Enable Test Transaction",
                           "type"          => "FUNCTION_CALL",
                           "function_call" => true_false_radio("config_38",$config_38),
                           "append"        => "<br>Set to <b>YES</b> for TESTING.
                                               <br>Set to <b>NO</b> for LIVE transactions.
                                               <br><br>"),

                    array("column"         => "config_31",
                           "required"      => 0,
                           "title"         => "Set Your Echo Gateway URL",
                           "type"          => "TEXT",
                           "size"          => 50,
                           "maxlength"     => 255,
                           "append"        => "<br>Enter the current Echo Gateway URL you will use to process Credit Card Transactions.
                                               <br><br>"),

                    array("column"         => "config_32",
                           "required"      => 0,
                           "title"         => "Set Your Echo Merchant ID",
                           "type"          => "TEXT",
                           "size"          => 20,
                           "maxlength"     => 100,
                           "append"        => "<br>Enter your Echo Merchant ID.
                                               <br><br>"),

                    array("column"         => "config_33",
                           "required"      => 0,
                           "title"         => "Set Your Echo Merchant Pin",
                           "type"          => "TEXT",
                           "size"          => 20,
                           "maxlength"     => 100,
                           "append"        => "<br>Enter your Echo Merchant PIN.
                                               <br><br>"),

# 2checkout Config #######################################################################################
/*
                    array("type"           => "HEADERROW",
                           "title"         => "2checkout Configuration [Tier2 Only]"),

                    array("column"         => "config_25",
                           "required"      => 0,
                           "title"         => "Enable 2Checkout Gateway: [add]",
                           "type"          => "FUNCTION_CALL",
                           "function_call" => true_false_radio("config_25",$config_25),
                           "append"        => "<br>You must have your OWN gateway account to use this feature!
                                                   NOTE: You can only enable one gateway, either Echo, Authent, or 2Checkout.
                                                   If both are enabled, Authent will be used by default.
                                               <br><br>"),

                    array("column"         => "config_26",
                           "required"      => 0,
                           "title"         => "Set Your 2Checkout Merchant ID",
                           "type"          => "TEXT",
                           "size"          => 20,
                           "maxlength"     => 100,
                           "append"        => "<br>Enter your 2Checkout Merchant ID.
                                               <br><br>"),

                    array("column"         => "config_27",
                           "required"      => 0,
                           "title"         => "Enable Test Transaction",
                           "type"          => "FUNCTION_CALL",
                           "function_call" => true_false_radio("config_27",$config_27),
                           "append"        => "<br>Set to <b>YES</b> for TESTING.
                                               <br>Set to <b>NO</b> for LIVE transactions.
                                               <br><br>"),
*/

# PayPal Config #######################################################################################
                    array("type"           => "HEADERROW",
                           "title"         => "PayPal Configuration [Tier2 Only]"),

                    array("column"         => "config_17",
                           "required"      => 0,
                           "title"         => "Enable PayPal Payment Option",
                           "type"          => "FUNCTION_CALL",
                           "function_call" => true_false_radio("config_17",$config_17),
                           "append"        => "<br>You must have your OWN PayPal account to use this feature!
                                                   NOTE: It CAN be used in conjunction with the other payment gateways.
                                               <br><br>"),

                    array("column"         => "config_18",
                           "required"      => 0,
                           "title"         => "Set PayPal Payment URL",
                           "type"          => "TEXT",
                           "size"          => 40,
                           "maxlength"     => 255,
                           "append"        => "<br>Enter the PayPal Payment URL you will use to process Credit Card Transactions.
                                               <br><br>"),

                    array("column"         => "config_19",
                           "required"      => 0,
                           "title"         => "Set Your PayPal ID",
                           "type"          => "TEXT",
                           "size"          => 40,
                           "maxlength"     => 255,
                           "append"        => "<br>Enter your PayPal ID which is your email address.
                                               <br><br>"),

                    array("column"         => "config_20",
                           "required"      => 0,
                           "title"         => "Set the PayPal Return URL",
                           "type"          => "TEXT",
                           "size"          => 40,
                           "maxlength"     => 255,
                           "append"        => "<br>Enter the Return URL.
                                                   This is the URL that your clients will see after paying via PayPal.
                                               <br><br>"),

                    array("column"         => "config_21",
                           "required"      => 0,
                           "title"         => "Set the PayPal Cancel URL",
                           "type"          => "TEXT",
                           "size"          => 40,
                           "maxlength"     => 255,
                           "append"        => "<br>Enter the Cancel URL.
                                                   This is the URL that your clients will see after canceling payment via PayPal.
                                               <br><br>"),

                    array("column"         => "config_22",
                           "required"      => 0,
                           "title"         => "Set Your Logo Image to Display",
                           "type"          => "TEXT",
                           "size"          => 40,
                           "maxlength"     => 255,
                           "append"        => "<br>Enter the FULL SECURE URL to your logo that will appear on PayPal's web site when a client pays for your services.
                                               <br><br>"),

                    array("column"         => "config_23",
                           "required"      => 0,
                           "title"         => "Set Your Submit Image to Display",
                           "type"          => "TEXT",
                           "size"          => 40,
                           "maxlength"     => 255,
                           "append"        => "<br>Enter the FULL SECURE URL to the PayPal Submit Image that your clients will see on YOUR WEB SITE.
                                               <br><br>"),

                    array("column"         => "config_24",
                           "required"      => 0,
                           "title"         => "Set the PayPal Item Name",
                           "type"          => "TEXT",
                           "size"          => 40,
                           "maxlength"     => 255,
                           "append"        => "<br>Enter the short, generic item name to use if the REAL Package Name is not available.
                                               <br><br>"),
                    /*
                    array("column"         => "config_25",
                           "required"      => 0,
                           "title"         => "Enable PayPal Variable: [add]",
                           "type"          => "FUNCTION_CALL",
                           "function_call" => true_false_radio("config_25",$config_25),
                           "append"        => "<br>Set to <b>YES</b> to enable the extra PayPal variables.
                                               <br><br>"),

                    array("column"         => "config_26",
                           "required"      => 0,
                           "title"         => "Enable PayPal Variable: [no_note]",
                           "type"          => "FUNCTION_CALL",
                           "function_call" => true_false_radio("config_26",$config_26),
                           "append"        => "<br>Set to <b>YES</b> to enable the extra PayPal variables.
                                               <br><br>"),

                    array("column"         => "config_27",
                           "required"      => 0,
                           "title"         => "<nobr>Enable PayPal Variable: [undefined_quantity]</nobr>",
                           "type"          => "FUNCTION_CALL",
                           "function_call" => true_false_radio("config_27",$config_27),
                           "append"        => "<br>Set to <b>YES</b> to enable the extra PayPal variables.
                                               <br><br>"),

                    array("column"         => "config_28",
                           "required"      => 0,
                           "title"         => "Enable PayPal Variable: [no_shipping]",
                           "type"          => "FUNCTION_CALL",
                           "function_call" => true_false_radio("config_28",$config_28),
                           "append"        => "<br>Set to <b>YES</b> to enable the extra PayPal variables.
                                               <br><br>"),
                    */

                    array("column"         => "config_29",
                           "required"      => 0,
                           "title"         => "Set PayPal Billing Method",
                           "type"          => "FUNCTION_CALL",
                           "function_call" => payment_select_box($config_29,$name="config_29"),
                           "append"        => "<br>This MUST BE MAPPED to the PayPal Select Menu Item only if PayPal is Enabled.
                                                   NOTE: If you have not enabled PayPal yet, you will need enbale it, then save before the PayPal option will display in this menu here.
                                               <br><br>"),

# WorldPay Config #######################################################################################
                    array("type"           => "HEADERROW",
                           "title"         => "WorldPay Configuration [Tier2 Only]"),

                    array("column"         => "config_39",
                           "required"      => 0,
                           "title"         => "Enable WorldPay Payment Option",
                           "type"          => "FUNCTION_CALL",
                           "function_call" => true_false_radio("config_39",$config_39),
                           "append"        => "<br>You must have your OWN WorldPay account to use this feature!
                                                   NOTE: It CAN be used in conjunction with the other payment gateways.
                                               <br><br>"),

                    array("column"         => "config_40",
                           "required"      => 0,
                           "title"         => "Set WorldPay Payment URL",
                           "type"          => "TEXT",
                           "size"          => 40,
                           "maxlength"     => 255,
                           "append"        => "<br>Enter the WorldPay Payment URL you will use to process Credit Card Transactions.
                                               <br><br>"),

                    array("column"         => "config_41",
                           "required"      => 0,
                           "title"         => "Enable WorldPay Test Transactions",
                           "type"          => "TEXT",
                           "size"          => 3,
                           "maxlength"     => 3,
                           "append"        => "<br>Set TestMode Values: <b>0</b> = live, <b>101</b> = always fail, <b>100</b> = always accept.
                                               <br><br>"),

                    array("column"         => "config_42",
                           "required"      => 0,
                           "title"         => "Set Your WorldPay instID",
                           "type"          => "TEXT",
                           "size"          => 20,
                           "maxlength"     => 255,
                           "append"        => "<br>Enter your WorldPay instID.
                                               <br><br>"),

                    array("column"         => "config_43",
                           "required"      => 0,
                           "title"         => "Set the WorldPay Currency",
                           "type"          => "TEXT",
                           "size"          => 5,
                           "maxlength"     => 25,
                           "append"        => "<br>Example: <b>GBP</b> or <b>USD</b>
                                               <br><br>"),

                    array("column"         => "config_45",
                           "required"      => 0,
                           "title"         => "Set Your WorldPay CallBack Password",
                           "type"          => "TEXT",
                           "size"          => 5,
                           "maxlength"     => 25,
                           "append"        => "<br>In your WorldPay administration server, set the callback url to the full url of the include/misc/worldpay_return.inc.php file.
                                                   Change the callback password to be the same as your callback password at worldpay.
                                                   This will enable invoices to be updated in real-time after a client pays for your services via WorldPay.
                                               <br><br>"),

                    array("column"         => "config_44",
                           "required"      => 0,
                           "title"         => "Set WorldPay Billing Method",
                           "type"          => "FUNCTION_CALL",
                           "function_call" => payment_select_box($config_44,$name="config_44"),
                           "append"        => "<br>This MUST BE MAPPED to the WorldPay Select Menu Item only if WorldPay is Enabled.
                                                   NOTE: If you have not enabled WorldPay yet, you will need enbale it, then save before the WorldPay option will display in this menu here.
                                               <br><br>"));

?>