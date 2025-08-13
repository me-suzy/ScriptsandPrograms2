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
######### VORTECH CONFIG ###############
########################################
      $args = array(array("column"         => "config_type",
                           "required"      => 0,
                           "title"         => ID,
                           "type"          => "HIDDEN"),
                    array("column"         => "config_25",
                           "required"      => 0,
                           "title"         => "Set Package Type",
                           "type"          => "FUNCTION_CALL",
                           "function_call" => pack_display_select_box($config_25,"config_25"),
                           "append"        => "<br><b>DO NOT MODIFY UNLESS YOU SETUP A NEW VORTECH_TYPE!</b>
                                                   This is the value that controls which Packages will be selected for THIS Vortech Configuration!
                                               <br><br>"),

# Company Config #######################################################################################
                    array("type"           => "HEADERROW",
                           "title"         => "Company Configuration"),

                    array("column"         => "config_1",
                           "required"      => 0,
                           "title"         => "Set Your Company Name",
                           "type"          => "TEXT",
                           "size"          => 40,
                           "maxlength"     => 255,
                           "append"        => "<br>Enter the name of your company that will display throughout the Vortech Signup Form.
                                               <br><br>"),

                    array("column"         => "config_2",
                           "required"      => 0,
                           "title"         => "Set Your Company's Web Site URL",
                           "type"          => "TEXT",
                           "size"          => 40,
                           "maxlength"     => 255,
                           "append"        => "<br>Enter the FULL URL for your web site that will display throughout the Vortech Signup Form.
                                                   This should be the FULL standard URL.
                                               <br><br>"),

                    array("column"         => "config_3",
                           "required"      => 0,
                           "title"         => "Set the URL to your Terms & Policies",
                           "type"          => "TEXT",
                           "size"          => 40,
                           "maxlength"     => 255,
                           "append"        => "<br>Enter the FULL URL or RELATIVE URL to your Terms & Conditions or Policies.
                                                   The Vortech Signup Form will link to these from the final order confirmation page.
                                               <br><br>"),

                    array("column"         => "config_6",
                           "required"      => 0,
                           "title"         => "Set Your Company Address",
                           "type"          => "TEXTAREA",
                           "rows"          => $textarea_rows,
                           "cols"          => $textarea_cols,
                           "wrap"          => $textarea_wrap,
                           "append"        => "This is your company address and contact information that will display in the order emails.
                                               Basic HTML is OK except the &lt;br&gt; tag. New lines will be parsed at run time if HTML emails is enabled.
                                               <br><br>"),

# Order Email Config #######################################################################################
                    array("type"           => "HEADERROW",
                           "title"         => "Order Email Configuration"),

                    array("column"         => "config_5",
                           "required"      => 0,
                           "title"         => "Set the Order To Email [email_to]",
                           "type"          => "TEXT",
                           "size"          => 40,
                           "maxlength"     => 255,
                           "append"        => "<br>Enter the email address where you want new orders sent to.
                                               <br><br>"),

                    array("column"         => "config_4",
                           "required"      => 0,
                           "title"         => "Set the Order From Email [email_from]",
                           "type"          => "TEXT",
                           "size"          => 40,
                           "maxlength"     => 255,
                           "append"        => "<br>Enter the email address where the order confirmation emails will be sent from.
                                               <br><br>"),

                    array("column"         => "config_43",
                           "required"      => 0,
                           "title"         => "Enable Client Order Email",
                           "type"          => "FUNCTION_CALL",
                           "function_call" => true_false_radio("config_43",$config_43),
                           "append"        => "<br>Set to <b>YES</b> to enable the new client a signup email.
                                                   You will need to map the email template for EACH package when you create them in the Package Admin section.
                                               <br><br>"),

                    array("column"         => "config_48",
                           "required"      => 0,
                           "title"         => "Enable HTML Email",
                           "type"          => "FUNCTION_CALL",
                           "function_call" => true_false_radio("config_48",$config_48),
                           "append"        => "<br>Set to <b>YES</b> to enable HTML parsing in the Vortech Order email templates.
                                               <br><br>"),

                    array("column"         => "config_53",
                           "required"      => 0,
                           "title"         => "Enable \"Free\" Email Banning",
                           "type"          => "FUNCTION_CALL",
                           "function_call" => true_false_radio("config_53",$config_53),
                           "append"        => "<br>Set to <b>YES</b> to ban orders using free email accounts.
                                               <br><br>"),

                    array("column"         => "config_36",
                           "required"      => 0,
                           "title"         => "Set the Default Banned Email Message",
                           "type"          => "TEXTAREA",
                           "rows"          => $textarea_rows,
                           "cols"          => $textarea_cols,
                           "wrap"          => $textarea_wrap,
                           "append"        => "<br>Maximum Characters <b>255</b>.
                                                   This is the default message that will display when a new client tries to resgister with a banned email address.
                                                   Each banned email address configured can override this message if desired.
                                               <br><br>"),

# Signup Form Display Config #######################################################################################
                    array("type"           => "HEADERROW",
                           "title"         => "Signup Form Display Configuration"),

                    array("column"         => "config_35",
                           "required"      => 0,
                           "title"         => "Set the Signup Form Table's Width",
                           "type"          => "TEXT",
                           "size"          => 5,
                           "maxlength"     => 5,
                           "append"        => "<br>Default <b>400</b>.
                                                   Enter the Pixel Width of the master Vortech Signup Form Table.
                                               <br><br>"),

                    array("column"         => "config_37",
                           "required"      => 0,
                           "title"         => "Set Outer Border Color",
                           "type"          => "TEXT",
                           "size"          => 7,
                           "swatch"        => 1,
                           "maxlength"     => 255),

                    array("column"         => "config_38",
                           "required"      => 0,
                           "title"         => "Set Inner Border Color",
                           "type"          => "TEXT",
                           "size"          => 7,
                           "swatch"        => 1,
                           "maxlength"     => 255),

                    array("column"         => "config_39",
                           "required"      => 0,
                           "title"         => "Set Header Row Color",
                           "type"          => "TEXT",
                           "size"          => 7,
                           "swatch"        => 1,
                           "maxlength"     => 255),

                    array("column"         => "config_40",
                           "required"      => 0,
                           "title"         => "Set Header Text Color",
                           "type"          => "TEXT",
                           "size"          => 7,
                           "swatch"        => 1,
                           "maxlength"     => 255),

                    array("column"         => "config_41",
                           "required"      => 0,
                           "title"         => "Set Alternating Background Color 1",
                           "type"          => "TEXT",
                           "size"          => 7,
                           "swatch"        => 1,
                           "maxlength"     => 255),

                    array("column"         => "config_42",
                           "required"      => 0,
                           "title"         => "Set Alternating Background Color 2",
                           "type"          => "TEXT",
                           "size"          => 7,
                           "swatch"        => 1,
                           "maxlength"     => 255),

# Enabling Options Config #######################################################################################
                    array("type"           => "HEADERROW",
                           "title"         => "Enabling Options Configuration"),

                    array("column"         => "config_15",
                           "required"      => 0,
                           "title"         => "Enable <i>WWW</i> and <i>WHOIS</i> links",
                           "type"          => "FUNCTION_CALL",
                           "function_call" => true_false_radio("config_15",$config_15),
                           "append"        => "<br>Set to <b>YES</b> to enable these links to dynamically display next to registered domains during the ordering process.
                                               <br><br>"),

                    array("column"         => "config_17",
                           "required"      => 0,
                           "title"         => "Enable Username Field",
                           "type"          => "FUNCTION_CALL",
                           "function_call" => true_false_radio("config_17",$config_17),
                           "append"        => "<br>Set to <b>YES</b> will allow the user to enter their own username.
                                                   NOTE This is the username associated with their package, NOT their ModernBill login.
                                               <br><br>"),

                    array("column"         => "config_18",
                           "required"      => 0,
                           "title"         => "Enable Password Field",
                           "type"          => "FUNCTION_CALL",
                           "function_call" => true_false_radio("config_18",$config_18),
                           "append"        => "<br>Set to <b>YES</b> will allow the user to enter their own password.
                                                   NOTE This is the password associated with their package AND ALSO set as their default ModernBill password.
                                               <br><br>"),

                    array("column"         => "config_46",
                           "required"      => 0,
                           "title"         => "Enable Referrers",
                           "type"          => "FUNCTION_CALL",
                           "function_call" => true_false_radio("config_46",$config_46),
                           "append"        => "<br>Set to <b>YES</b> to enable the referrers select menu.
                                                   Enter your referrer options below.
                                               <br><br>"),

                    array("column"         => "config_47",
                           "required"      => 0,
                           "title"         => "Set Referrers Values",
                           "type"          => "TEXTAREA",
                           "rows"          => $textarea_rows,
                           "cols"          => $textarea_cols,
                           "wrap"          => $textarea_wrap,
                           "append"        => "<br>FORMAT <b>value1=name1|value2=name2</b>...etc
                                                   NOTE The order does not matter. You can edit at any time.
                                               <br><br>"),

                    array("column"         => "config_44",
                           "required"      => 0,
                           "title"         => "Enable Package Display Grid",
                           "type"          => "FUNCTION_CALL",
                           "function_call" => true_false_radio("config_44",$config_44),
                           "append"        => "<br>Set to <b>YES</b> to display the packages on the plan selection page.
                                                   The clients will be able to view the details for each package.
                                               <br><br>"),

                    array("column"         => "config_45",
                           "required"      => 0,
                           "title"         => "Enable Package Comparison Grid",
                           "type"          => "FUNCTION_CALL",
                           "function_call" => true_false_radio("config_45",$config_45),
                           "append"        => "<br>Set to <b>YES</b> will display the feature comparison chart ON the order form.
                                               <br>Set to <b>NO</b> will display a popup URL instead.
                                               <br>NOTE Each package can have an unlimited amount of features.
                                                   You must setup these feature so the names are similar and can be used in the feature comparison chart.
                                               <br><br>"),

                    array("column"         => "config_26",
                           "required"      => 0,
                           "title"         => "Enable Domain Registration Option",
                           "type"          => "FUNCTION_CALL",
                           "function_call" => true_false_radio("config_26",$config_26),
                           "append"        => "<br>Set to <b>YES</b> to enable the option to \"Register\" a domain name.
                                               <br><br>"),

                    array("column"         => "config_7",
                           "required"      => 0,
                           "title"         => "Disable Whois Lookups",
                           "type"          => "FUNCTION_CALL",
                           "function_call" => true_false_radio("config_7",$config_7),
                           "append"        => "<br>Set to <b>YES</b> to bypass whois lookups on domain registrations & transfers.
                                                   All domains will be listed as available.
                                               <br><br>"),

                    array("column"         => "config_14",
                           "required"      => 0,
                           "title"         => "Enable Domain Only Ordering",
                           "type"          => "FUNCTION_CALL",
                           "function_call" => true_false_radio("config_14",$config_14),
                           "append"        => "<br>Set to <B>YES</b> will add a \"Domain Only\" option to the package drop down menu.
                                                   This is really only applicable if you allow domain registrations.
                                               <br><br>"),

                    array("column"         => "config_13",
                           "required"      => 0,
                           "title"         => "Enable Domain Transfer Option",
                           "type"          => "FUNCTION_CALL",
                           "function_call" => true_false_radio("config_13",$config_13),
                           "append"        => "<br>Set to <b>YES</b> to enable the option to \"Transfer\" a domain name.
                                               <br><br>"),

                    array("column"         => "config_16",
                           "required"      => 0,
                           "title"         => "Enable Domain Skip Option",
                           "type"          => "FUNCTION_CALL",
                           "function_call" => true_false_radio("config_16",$config_16),
                           "append"        => "<br>Set to <b>YES</b> to enable the option to \"Skip\" and signup without a domain name.
                                                   <br>
                                                   <i>FORMAT: index.php?submit_skip=1</i>
                                               <br><br>"),

                    array("column"         => "config_51",
                           "required"      => 0,
                           "title"         => "Package Sort Order",
                           "type"          => "FUNCTION_CALL",
                           "function_call" => vortech_pack_sort_select_box($config_51,"config_51"),
                           "append"        => "<br>Set the sort order for your packages in the order form.
                                               <br><br>"),

                    array("column"         => "config_52",
                           "required"      => 0,
                           "title"         => "Package Menu Display Type",
                           "type"          => "FUNCTION_CALL",
                           "function_call" => vortech_pack_menu_display_select_box($config_52,"config_52"),
                           "append"        => "<br>Set the package menu display type.
                                                   v2 Display or v3 Display.
                                               <br><br>"),

# Payment Options Config #######################################################################################
                    array("type"           => "HEADERROW",
                           "title"         => "Payment Options Configuration"),

                    array("column"         => "config_19",
                           "required"      => 0,
                           "title"         => "Enable Credit Card Payment",
                           "type"          => "FUNCTION_CALL",
                           "function_call" => true_false_radio("config_19",$config_19),
                           "append"        => "<br>Set to <b>YES</b> to enable the card payment option.
                                                   You will also need to setup your Payment Config Options in the admin section.
                                               <br><br>"),

                    array("column"         => "config_24",
                           "required"      => 0,
                           "title"         => "Enable CVVC Field",
                           "type"          => "FUNCTION_CALL",
                           "function_call" => true_false_radio("config_24",$config_24),
                           "append"        => "<br>Set to <b>YES</b> to enable the feild for the CVVC, special credit card security code.
                                                   Not all credit cards have this code available.
                                                   <font color=red><b>NOTE IT IS ILLEGAL TO STORE THIS NUMBER IN ANY DATABASE AND MODERNBILL WILL NOT STORE IT FOR YOU!</b></font>
                                               <br><br>"),

                    array("column"         => "config_20",
                           "required"      => 0,
                           "title"         => "Enable Check/Invoice",
                           "type"          => "FUNCTION_CALL",
                           "function_call" => true_false_radio("config_20",$config_20),
                           "append"        => "<br>Set to <b>YES</b> to enable the Invoice Payment Option.
                                               <br><br>"),

                    array("column"         => "config_21",
                           "required"      => 0,
                           "title"         => "Enable PayPal<br><font color=red><b>[Tier2 ONLY]</b></font>",
                           "type"          => "FUNCTION_CALL",
                           "function_call" => true_false_radio("config_21",$config_21),
                           "append"        => "<br>Set to <B>YES</b> to enable the PayPal Payment Option.
                                                   Clients will be given a dynamic link to process their initial order in via PayPal.
                                               <br><br>"),

                    array("column"         => "config_49",
                           "required"      => 0,
                           "title"         => "Enable WorldPay<br><font color=red><b>[Tier2 ONLY]</b></font>",
                           "type"          => "FUNCTION_CALL",
                           "function_call" => true_false_radio("config_49",$config_49),
                           "append"        => "<br>Set to <B>YES</b> to enable the WorldPay Payment Option.
                                                   Clients will be given a dynamic link to process their initial order in via WorldPay.
                                               <br><br>"),

                    array("column"         => "config_22",
                           "required"      => 0,
                           "title"         => "Enable Initial Month Pro-Rated",
                           "type"          => "FUNCTION_CALL",
                           "function_call" => true_false_radio("config_22",$config_22),
                           "append"        => "<br>Set to <b>YES</b> to pro-rate the FIRST month of service and add the total to the first billing cycle.
                                                   If you turned on Daily/Anniversary Billing, you MUST set this to <b>NO</b>.
                                               <br><br>"),


# Order Integration Config #######################################################################################
                    array("type"           => "HEADERROW",
                           "title"         => "Order Integration Configuration"),

                    array("column"         => "config_23",
                           "required"      => 0,
                           "title"         => "Enable Integrated Processor Charge<br><font color=red><b>[Tier2 ONLY]</b></font>",
                           "type"          => "FUNCTION_CALL",
                           "function_call" => true_false_radio("config_23",$config_23),
                           "append"        => "<br>Set to <b>YES</b> will attempt to charge the initial credit card payment before entering the new client into the database.
                                                   You will also have to setup your Payment Config Options in the admin area.
                                                   <font color=red><b>NOTE cURL is required for this feature.</b></font>
                                               <br><br>"),

                    array("column"         => "config_10",
                           "required"      => 0,
                           "title"         => "Enable New Clients Auto-Inserted",
                           "type"          => "FUNCTION_CALL",
                           "function_call" => true_false_radio("config_10",$config_10),
                           "append"        => "<br>Set to <b>YES</b> to auto-insert new clients into the ModernBill database.
                                               <br>Set to <b>NO<b/> to email the new order only.
                                               <br><br>"),

                    array("column"         => "config_11",
                           "required"      => 0,
                           "title"         => "Set the Default Server Name",
                           "type"          => "FUNCTION_CALL",
                           "function_call" => server_name_select_box($config_11,NULL,"config_11"),
                           "append"        => "<br>Map to the default server_name to be entered into the account_details for the new package.
                                               <br><br>"),

                    array("column"         => "config_12",
                           "required"      => 0,
                           "title"         => "Set the Default Server Type",
                           "type"          => "FUNCTION_CALL",
                           "function_call" => server_type_select_box($config_12,NULL,"config_12"),
                           "append"        => "<br>Map to the default server_type to be entered into the account_details for the new package.
                                               <br><br>"),

# Order Integration Config #######################################################################################
                    array("type"           => "HEADERROW",
                           "title"         => "Billing Cycle Configuration"),


                    array("column"         => "config_27",
                           "required"      => 0,
                           "title"         => "Enable Cycle [Monthly]",
                           "type"          => "FUNCTION_CALL",
                           "function_call" => true_false_radio("config_27",$config_27),
                           "append"        => "<br>Set to <b>YES</b> to allow the Monthly Billing Cycle as an option.
                                               <br><br>"),

                    array("column"         => "config_28",
                           "required"      => 0,
                           "title"         => "Set Monthly Billing Cycle Name",
                           "type"          => "TEXT",
                           "size"          => 25,
                           "maxlength"     => 255,
                           "append"        => "<br>Enter the Monthly Billing Cycle name that will appear next to the cycle total.
                                               <br><br>"),

                    array("column"         => "config_29",
                           "required"      => 0,
                           "title"         => "Enable Cycle [Quarterly]",
                           "type"          => "FUNCTION_CALL",
                           "function_call" => true_false_radio("config_29",$config_29),
                           "append"        => "<br>Set to <b>YES</b> to allow the Quarterly Billing Cycle as an option.
                                               <br><br>"),

                    array("column"         => "config_30",
                           "required"      => 0,
                           "title"         => "Set Quarterly Cycle Name",
                           "type"          => "TEXT",
                           "size"          => 25,
                           "maxlength"     => 255,
                           "append"        => "<br>Enter the Quarterly Billing Cycle name that will appear next to the cycle total.
                                               <br><br>"),

                    array("column"         => "config_31",
                           "required"      => 0,
                           "title"         => "Enable Cycle [Semi-Annual]",
                           "type"          => "FUNCTION_CALL",
                           "function_call" => true_false_radio("config_31",$config_31),
                           "append"        => "<br>Set to <b>YES</b> to allow the Semi-Annual Billing Cycle as an option.
                                               <br><br>"),

                    array("column"         => "config_32",
                           "required"      => 0,
                           "title"         => "Set Semi-Annual Cycle Name",
                           "type"          => "TEXT",
                           "size"          => 25,
                           "maxlength"     => 255,
                           "append"        => "<br>Enter the Semi-Annual Billing Cycle name that will appear next to the cycle total.
                                               <br><br>"),

                    array("column"         => "config_33",
                           "required"      => 0,
                           "title"         => "Enable Cycle [Yearly]",
                           "type"          => "FUNCTION_CALL",
                           "function_call" => true_false_radio("config_33",$config_33),
                           "append"        => "<br>Set to <b>YES</b> to allow the Yearly Billing Cycle as an option.
                                               <br><br>"),

                    array("column"         => "config_34",
                           "required"      => 0,
                           "title"         => "Set Yearly Cycle Name",
                           "type"          => "TEXT",
                           "size"          => 25,
                           "maxlength"     => 255,
                           "append"        => "<br>Enter the Yearly Billing Cycle name that will appear next to the cycle total.
                                               <br><br>"),

                    array("column"         => "config_8",
                           "required"      => 0,
                           "title"         => "Enable Cycle [2-Year]",
                           "type"          => "FUNCTION_CALL",
                           "function_call" => true_false_radio("config_8",$config_8),
                           "append"        => "<br>Set to <b>YES</b> to allow the 2-Year Billing Cycle as an option.
                                               <br><br>"),

                    array("column"         => "config_9",
                           "required"      => 0,
                           "title"         => "Set 2-Year Cycle Name",
                           "type"          => "TEXT",
                           "size"          => 25,
                           "maxlength"     => 255,
                           "append"        => "<br>Enter the 2-Year Billing Cycle name that will appear next to the cycle total.
                                               <br><br>"));

?>