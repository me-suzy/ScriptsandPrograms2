<?
/* THIS FILE IS NOT IS USE
** ModernBill [TM] (Copyright::2001)
** Questions? webmaster@modernbill.com
**
**
**          Always save a backup before your upgrade!
**          Proceed with caution. You have been warned.
*/
########################################
######### MAIN CONFIG ##################
########################################
      $args = array(array("column"         => "config_type",
                           "required"      => 0,
                           "title"         => ID,
                           "type"          => "HIDDEN"),

# Main Config #######################################################################################
                    array("type"           => "HEADERROW",
                           "title"         => "ModernBill Main Settings & Configuration"),

                    array("column"         => "config_1",
                           "required"      => 0,
                           "title"         => "Enable Debug Mode",
                           "type"          => "FUNCTION_CALL",
                           "function_call" => true_false_radio("config_1",$config_1),
                           "append"        => "<br>Set to <b>YES</b> to display debug output.
                                                   Some functionality may be disabled.
                                                   NOTE: If you see debug output, so will your clients!
                                               <br><br>"),

                    array("column"         => "config_34",
                           "required"      => 0,
                           "title"         => "Disable Client Login",
                           "type"          => "FUNCTION_CALL",
                           "function_call" => true_false_radio("config_34",$config_34),
                           "append"        => "<br>Set to <b>YES</b> only if you want to disable the client login.
                                                   You should always do this before you make any major changes or enable debug mode.
                                                   The system will display the message below if a client tries to login.
                                               <br><br>"),

                    array("column"         => "config_35",
                           "required"      => 0,
                           "title"         => "Set Login Message?",
                           "type"          => "TEXTAREA",
                           "rows"          => $textarea_rows,
                           "cols"          => $textarea_cols,
                           "wrap"          => $textarea_wrap,
                           "maxlength"     => 255,
                           "append"        => "Message to display when a client tries to login while \"Disable Client Login\" equals \"Yes\".
                                               The Maximum Characters is 255.
                                               <br><br>"),

                    array("column"         => "config_36",
                           "required"      => 0,
                           "title"         => "Set Date Format",
                           "type"          => "FUNCTION_CALL",
                           "function_call" => date_format_select_box($config_36,"config_36"),
                           "append"        => "<br>NOTE: This is the PHP value for the <b>date function</b>.
                                               <br>The DEFAULT is Y/m/d which will return YYYY/MM/DD.
                                               <br><font color=red>Please do not change without fully testing.
                                                   We are still debugging this feature.</font>
                                               <br><br>"),

                    array("column"         => "config_2",
                           "required"      => 0,
                           "title"         => "Enable Language Translations Menu",
                           "type"          => "FUNCTION_CALL",
                           "function_call" => true_false_radio("config_2",$config_2),
                           "append"        => "<br>Set to <b>YES</b> will turn ON the Language Translations drop down menu.
                                                   This will allow users to switch to the language of their choice when they login.
                                               <br><br>"),

                    array("column"         => "config_3",
                           "required"      => 0,
                           "title"         => "Set Default Language",
                           "type"          => "FUNCTION_CALL",
                           "function_call" => language_select_box($config_3,"config_3"),
                           "append"        => "<br>Set to your language of choice.
                                                   This will be the default language used by ModernBill and in the Vortech Signup form next time you login.
                                                   NOTE: You will need to logout if you switch this to see your new settings.
                                               <br><br>"),

                    array("column"         => "config_4",
                           "required"      => 0,
                           "title"         => "Set Default Currency",
                           "type"          => "TEXT",
                           "size"          => 10,
                           "maxlength"     => 255,
                           "append"        => "<br>Current Options: <b>US</b>, <b>EURO</b>, <b>YEN</b>, <b>POUND</b> as defined by the function \"display_currency\" in file: include/misc/db_functions.inc.php).
                                                   You can add your own currency symbols and make changes to the formatting there as well.
                                                   Please forward your changes to the admin for inclusion in the next release.
                                              <br><br>"),

                    array("column"         => "config_5",
                           "required"      => 0,
                           "title"         => "Enable Themes Menu",
                           "type"          => "FUNCTION_CALL",
                           "function_call" => true_false_radio("config_5",$config_5),
                           "append"        => "<br>Set to <b>YES</b> will turn ON the Themes drop down menu.
                                                   This will allow users to switch to the theme of their choice when they login.
                                               <br><br>"),

                    array("column"         => "config_6",
                           "required"      => 0,
                           "title"         => "Set Default Theme",
                           "type"          => "FUNCTION_CALL",
                           "function_call" => theme_select_box($config_6,"config_6"),
                           "append"        => "<br>Set to your theme of choice.
                                                   This will be the default theme used by ModernBill the next time you login.
                                                   NOTE: You will need to logout if you switch this to see your new settings.
                                               <br><br>"),

                    array("column"         => "config_7",
                           "required"      => 0,
                           "title"         => "Set SQL Select Limit",
                           "type"          => "TEXT",
                           "size"          => 2,
                           "maxlength"     => 2,
                           "append"        => "<br>Default is <b>20</b>.
                                                   This will determine how many results, per page query, should be displayed.
                                                   The higher the number, the longer it will take the page to display.
                                               <br><br>"),

                    array("column"         => "config_8",
                           "required"      => 0,
                           "title"         => "Set Minimum Password Length",
                           "type"          => "TEXT",
                           "size"          => 2,
                           "maxlength"     => 2,
                           "append"        => "<br>Default is <b>6</b>.
                                                   Maximum is 10.
                                               <br><br>"),

                    array("column"         => "config_17",
                           "required"      => 0,
                           "title"         => "Set Bad Words Filter",
                           "type"          => "TEXT",
                           "size"          => 40,
                           "maxlength"     => 255,
                           "append"        => "<br>Format [comma seperated]: <b>bad1,bad2,etc...</b>
                                                   The filter is parsed every time a form is posted and removes any bad words found in the data.
                                                   This can NOT be left blank.
                                               <br><br>"),

                    array("column"         => "config_18",
                           "required"      => 0,
                           "title"         => "Enable Cut-off Text",
                           "type"          => "FUNCTION_CALL",
                           "function_call" => true_false_radio("config_18",$config_18),
                           "append"        => "<br>Set to <b>YES</b> to allow long text strings to be truncated while displaying results from various queries.
                                                   This is usually good if the comments field is part of any result set as it prevents the table from expanding too wide for your screen.
                                              <br><br>"),

                    array("column"         => "config_19",
                           "required"      => 0,
                           "title"         => "Set Cut-off Text Limit",
                           "type"          => "TEXT",
                           "size"          => 2,
                           "maxlength"     => 2,
                           "append"        => "<br>Default <b>20</b>.
                                                   This will determine how many characters should be truncate from a long text string.
                                               <br><br>"),

                    array("column"         => "config_10",
                           "required"      => 0,
                           "title"         => "Enable nl2br Function",
                           "type"          => "FUNCTION_CALL",
                           "function_call" => true_false_radio("config_10",$config_10),
                           "append"        => "<br>Set to <b>YES</b> will send all email templates through the nl2br function.
                                                   \"New Lines converted to &lt;br&gt;'s\"
                                               <br><br>"),

                   array("column"         => "config_15",
                           "required"      => 0,
                           "title"         => "Set Admin Login Prefix",
                           "type"          => "TEXT",
                           "size"          => 5,
                           "maxlength"     => 5,
                           "append"        => "<br>Format: 1-5 unique characters.
                                                   Example: <b>mb</b>
                                                   This must be typed prior to logging in with the admin username.
                                                   Please make it unique and do not use parts of it in the actual admin username, because it will cause the login to fail.
                                               <br><br>"),

                    array("column"         => "config_16",
                           "required"      => 0,
                           "title"         => "Enable Auto-Logout Every Hour",
                           "type"          => "FUNCTION_CALL",
                           "function_call" => true_false_radio("config_16",$config_16),
                           "append"        => "<br>Set to <b>YES</b> will destroy the users' or admins' session every hour, on the hour.
                                                   This will cause them to login again to continue.
                                               <br><br>"),

                    array("column"         => "config_32",
                           "required"      => 0,
                           "title"         => "Set cURL Path",
                           "type"          => "TEXT",
                           "size"          => 25,
                           "maxlength"     => 255,
                           "append"        => "<br>Path to the cURL executable on your server.
                                                   Something like: \"/usr/local/bin/curl\" NOTE: This will require PHP's safe-mode to be turned OFF!
                                                   If you have PHP compiled with cURL enabled and you want to use that, please enter \"<b>PHP</b>\" as the path."),

# Invoice Config #######################################################################################
                    array("type"           => "HEADERROW",
                           "title"         => "Billing Mode & Invoice Configuration"),
                    array("column"         => "config_12",
                           "required"      => 0,
                           "title"         => "Enable Anniversary Billing Mode",
                           "type"          => "FUNCTION_CALL",
                           "function_call" => true_false_radio("config_12",$config_12),
                           "append"        => "<br>Set to <b>YES</b> to enable the daily (anniversary) billing mode.
                                                   NOTE: You must also <b>DISABLE</b> pro-rate charges in the Vortech Config.
                                                   Your packages can renew ANY day of the month and all pro-rate calculations will be turned off.
                                               <br><br>
                                                   Set to <b>NO</b> to enable the monthly billing mode.
                                                   NOTE: You must also <b>ENABLE</b> pro-rate charges in the Vortech Config.
                                                   Your packages MUST ALWAYS renew on the 1st of any given month.
                                               <br><br>
                                                   <font color=red>
                                                   WARNING: You can switch from monthly mode to daily mode anytime, but you CAN NEVER switch from daily mode to monthly mode!!
                                                   This is due to the fact that the renew dates will never sync up again as required by monthly mode.
                                                   </font>
                                               <br><br>"),

                    array("column"         => "config_27",
                           "required"      => 0,
                           "title"         => "Enable Static Invoice Due Date",
                           "type"          => "FUNCTION_CALL",
                           "function_call" => true_false_radio("config_27",$config_27),
                           "append"        => "<br>Set to <b>YES</b> to enable STATIC Invoice Due Dates.
                                                   This is where Invoices will ALWAYS be DUE on the same day each month.
                                                   Set the magic date below.
                                               <br><br>
                                                   Set to <b>NO</b> to enable VARIABLE Invoice Due Dates.
                                                   This is where Invoices will ALWAYS be DUE <b>X</b> days AFTER the invoice is generated.
                                                   Set the magic date below.
                                               <br><br>"),

                    array("column"         => "config_28",
                           "required"      => 0,
                           "title"         => "Set Invoice Due Date",
                           "type"          => "TEXT",
                           "size"          => 2,
                           "maxlength"     => 2,
                           "append"        => "<br>Enter a number between 1-28.
                                                   The due date logic depends upon what you entered for \"Enable Static Invoice Due Date\" above.
                                               <br><br>"),

                    array("column"         => "config_20",
                           "required"      => 0,
                           "title"         => "Set Full User Login URL",
                           "type"          => "TEXT",
                           "size"          => 40,
                           "maxlength"     => 255,
                           "append"        => "<br>This is the FULL URL where your clients will login to ModernBill.
                                                   It will be parsed in the invoice templates and Vortech Signup Forms.
                                               <br><br>"),

                    array("column"         => "config_23",
                           "required"      => 0,
                           "title"         => "Set Invoice Header/Address<br>(Client Invoice Only)",
                           "type"          => "TEXTAREA",
                           "rows"          => $textarea_rows,
                           "cols"          => $textarea_cols,
                           "wrap"          => $textarea_wrap,
                           "append"        => "<br>This is the header/address that will be displayed on every invoice.
                                                   Basic HTML is OK except the &lt;br&gt; tag. New lines will be parsed at run time.
                                               <br><br>"),

# Contact Config #######################################################################################
                    array("type"           => "HEADERROW",
                           "title"         => "Contact Configuration"),
                    array("column"         => "config_21",
                           "required"      => 0,
                           "title"         => "Set Business Contact Information<br>(Client Contact/Support Page)",
                           "type"          => "TEXTAREA",
                           "rows"          => $textarea_rows,
                           "cols"          => $textarea_cols,
                           "wrap"          => $textarea_wrap,
                           "append"        => "This is your company address and contact information that will display on the client \"contact us\" page.
                                               Basic HTML is OK except the &lt;br&gt; tag. New lines will be parsed at run time.
                                               <br><br>"),

                    array("column"         => "config_41",
                           "required"      => 0,
                           "title"         => "Set User Help Docs<br>(Client Contact/Support Page)",
                           "type"          => "TEXTAREA",
                           "rows"          => $textarea_rows,
                           "cols"          => $textarea_cols,
                           "wrap"          => $textarea_wrap,
                           "append"        => "Create URLs that will display on the client \"contact us/support\" page.
                                               You can link to any external or internal URL.
                                               Basic HTML is OK except the &lt;br&gt; tag. New lines will be parsed at run time.
                                               <br><br>
                                               To access the built in FAQ, please use this URL:
                                               <br>
                                               <font color=red><b>".htmlspecialchars("<a href=user.php?op=faq>Online FAQ</a>")."</b></font>
                                               <br><br>
                                               To access the built in SUPPORT DESK, please use this URL:
                                               <br>
                                               <font color=red><b>".htmlspecialchars("<a href=user.php?op=menu&tile=mysupport>Support Desk</a>")."</b></font>
                                               <br><br>"),

# Registrars/Server Types Config #######################################################################################
                    array("type"           => "HEADERROW",
                           "title"         => "Registrars/Server Types Configuration"),
                    array("column"         => "config_30",
                           "required"      => 0,
                           "title"         => "Set Domain Registrars (For Domain Names)",
                           "type"          => "TEXTAREA",
                           "rows"          => $textarea_rows,
                           "cols"          => $textarea_cols,
                           "wrap"          => $textarea_wrap,
                           "append"        => "Format [comma seperated]: <b>reg1,reg2,etc...</b>
                                                   WARNING: You should NEVER change the order once you have added domain names into your db.
                                                   If you do, the order will be out-of-sync!
                                               <br><br>"),

                    array("column"         => "config_31",
                           "required"      => 0,
                           "title"         => "Set Server Types (For Account Details)",
                           "type"          => "TEXTAREA",
                           "rows"          => $textarea_rows,
                           "cols"          => $textarea_cols,
                           "wrap"          => $textarea_wrap,
                           "append"        => "Format [comma seperated]: <b>serv1,serve2,etc...</b>
                                                   WARNING: You should NEVER change the order once you have added any account details into your db.
                                                   If you do, the order will be out-of-sync!
                                               <br><br>"),

                    array("column"         => "config_33",
                           "required"      => 0,
                           "title"         => "Set Server Names (For Account Details)",
                           "type"          => "TEXTAREA",
                           "rows"          => $textarea_rows,
                           "cols"          => $textarea_cols,
                           "wrap"          => $textarea_wrap,
                           "append"        => "Format [comma seperated]: <b>name1,name2,etc...</b>.
                                                 This will build the servers_array menu.
                                               <br><br>"),

                    array("column"         => "config_9",
                           "required"      => 0,
                           "title"         => "Enable Manual Server Name Override (For Account Details)",
                           "type"          => "FUNCTION_CALL",
                           "function_call" => true_false_radio("config_9",$config_9),
                           "append"        => "<br>Set to <b>YES</b> will allow you to override the server names you have configured with your own.
                                                A text field will display when adding or editing the account_details for any client_package.
                                                The Vortech Config will STILL read from the servers_array built above.
                                               <br><br>"),

# Batch Config #######################################################################################
                    array("type"           => "HEADERROW",
                           "title"         => "Batch Configuration <b>[Tier2 Only]</b>"),
                    array("column"         => "config_13",
                           "required"      => 0,
                           "title"         => "Set Exported Batch Delimiter",
                           "type"          => "TEXT",
                           "size"          => 2,
                           "maxlength"     => 2,
                           "append"        => "<br>Default is a <b>,</b> (comma).
                                                   This is the character that will seperate each column when you export a batch.
                                               <br><br>"),

                    array("column"         => "config_14",
                           "required"      => 0,
                           "title"         => "Set Exported Batch File Name",
                           "type"          => "TEXT",
                           "size"          => 40,
                           "maxlength"     => 255,
                           "append"        => "<br>Default <b>exported_batch_%%DATE%%.dat</b>
                                                   The variable \"%%DATE%%\" with be parsed and generated dynamically.
                                               <br><br>"));
?>