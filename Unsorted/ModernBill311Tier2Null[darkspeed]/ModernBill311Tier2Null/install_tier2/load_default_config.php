<ul>
<?
$stop=NULL;
if (mysql_query("INSERT INTO config (config_type, config_1, config_2, config_3, config_4, config_5, config_6, config_7, config_8, config_9, config_10,
                                                  config_11, config_12, config_13, config_14, config_15, config_16, config_17, config_18, config_19, config_20,
                                                  config_21, config_22, config_23, config_24, config_25, config_26, config_27, config_28, config_29, config_30,
                                                  config_31, config_32, config_33, config_34, config_35, config_36, config_37, config_38, config_39, config_40,
                                                  config_41, config_42, config_43, config_44, config_45, config_46, config_47, config_48, config_49, config_50)
VALUES ('vortech_type1',
        '$company_name',
        '$company_url',
        '$company_url/policies.php?hosting',
        '$emailx',
        '$emailx',
        '$company_name\r\n123 Main St\r\nLouisville, KY 40207\r\n',
        '1',
        '1',
        '1',
        '1',
        'modernserver',
        '3',
        '',
        '1',
        'net=17.00|\r\ncom=18.00|\r\norg=14.00|\r\nuk=7.00',
        '2',
        '',
        '1',
        '1',
        '1',
        '1',
        '1',
        '1',
        '1',
        '1',
        '1',
        '1',
        '1-Month',
        '1',
        '3-Months',
        '1',
        '6-Months',
        '1',
        '12-Months',
        '400',
        'Your free email address is not allowed. Please use a real POP email account.',
        'CCCCCC',
        '888888',
        '666666',
        'FFFFFF',
        'DDDDDD',
        'EEEEEE',
        '1',
        '1',
        '1',
        '1',
        'Altavista=Search Engine: Altavista|\r\nAskjeeves=Search Engine: Ask Jeeves|\r\nExcite=Search Engine: Excite|\r\nGoogle=Search Engine: Google|\r\nInfoseek=Search Engine: Infoseek|\r\nLooksmart=Search Engine: LookSmart|\r\nFriend=Friend|\r\nOther=Other\r\n',
        '1',
        '1',
        '20')"))
                {
                  echo "<li>--> <font color=blue>vortech_type1 <b>OK</b></font>";
                }
                else
                { $stop=1;
                  echo "<li> --> <font color=red>vortech_type1 <b>NOT OK</b></font>";
                }

if (mysql_query("INSERT INTO config (config_type, config_1, config_2, config_3, config_4, config_5, config_6, config_7, config_8, config_9, config_10,
                                                  config_11, config_12, config_13, config_14, config_15, config_16, config_17, config_18, config_19, config_20,
                                                  config_21, config_22, config_23, config_24, config_25, config_26, config_27, config_28, config_29, config_30,
                                                  config_31, config_32, config_33, config_34, config_35, config_36, config_37, config_38, config_39, config_40,
                                                  config_41, config_42, config_43, config_44, config_45, config_46, config_47, config_48, config_49, config_50)
VALUES ('main',
        '',
        '1',
        'english',
        'US',
        '1',
        'newtop',
        '20',
        '6',
        'admin.php',
        'user.php',
        'index.php',
        '1',
        ',',
        'exported_batch_%%DATE%%.dat',
        'mb',
        '',
        'badword,anotherbadword',
        '1',
        '20',
        'http://your.server.com/tier2-3.0/',
        '<b>ModernHost.com</b>\r\n123 Main St\r\nSeattle, WA 98012\r\nPhone: (xxx) xxx-xxxx\r\nFax: (xxx) xxx-xxxx',
        '',
        '<b>ModernHost.com</b>\r\n<i>\"Web Hosting The Modern Way\"</i>\r\n123 Main St\r\nLouisville, KY 40207\r\n123-456-7890',
        '',
        '',
        '1',
        '',
        '15',
        '1',
        'NetworkSolutions,Joker,NameRegistrars,YahooDomains,Other',
        'UNIX,NT,Win2k,RedHat,FreeBSD,Cobalt,Sun,OTHER',
        '/usr/bin/curl',
        'modernserver,modernhost',
        '',
        'We are temporarily closed. Please check back shortly.',
        'Y/m/d',
        '',
        '',
        '',
        '',
        '<a href=user.php?op=faq>Online FAQ</a><br>\r\n',
        '',
        '',
        '',
        '',
        '',
        '',
        '',
        '',
        '')"))
                {
                  echo "<li>--> <font color=blue>main <b>OK</b></font>";
                }
                else
                { $stop=1;
                  echo "<li> --> <font color=red>main <b>NOT OK</b></font>";
                }

if (mysql_query("INSERT INTO config (config_type, config_1, config_2, config_3, config_4, config_5, config_6, config_7, config_8, config_9, config_10,
                                                  config_11, config_12, config_13, config_14, config_15, config_16, config_17, config_18, config_19, config_20,
                                                  config_21, config_22, config_23, config_24, config_25, config_26, config_27, config_28, config_29, config_30,
                                                  config_31, config_32, config_33, config_34, config_35, config_36, config_37, config_38, config_39, config_40,
                                                  config_41, config_42, config_43, config_44, config_45, config_46, config_47, config_48, config_49, config_50)
VALUES ('email',
        '1',
        '1',
        '2',
        '3',
        '4',
        '5',
        '6',
        '3',
        'Your Invoice# %%INVOICE_NUMBER%%:$company_url',
        '$emailx',
        '$emailx',
        '$emailx',
        'ModernHost.com <$emailx>',
        'ModernHost.com <$emailx>',
        'ModernHost.com <$emailx>',
        'ModernHost.com <$emailx>',
        '$emailx',
        '$emailx',
        'Generated by ModernBill .:. Client Billing System',
        '1',
        '1',
        '',
        '',
        '',
        '',
        '',
        '',
        '',
        '',
        '8',
        '1',
        '10',
        '13',
        '',
        '',
        '',
        '',
        '',
        '',
        '',
        '',
        '',
        '',
        '',
        '',
        '',
        '',
        '[SUPPORT] Password|Password,\r\n[SUPPORT] Frontpage|Frontpage,\r\n[SUPPORT] Email|Email,\r\n[SUPPORT] Web Control|Web Control,\r\n[SUPPORT] FTP|FTP,\r\n[SUPPORT] MySQL|MySQL,\r\n[SUPPORT] Cgi We Provided|Cgi We Provided,\r\n[SUPPORT] Domain Registration|Domain Registration,\r\n[SUPPORT] PHP|PHP,\r\n[SUPPORT] Sitebuilder|Sitebuilder,\r\n[SUPPORT] Miva Merchant/Order|Miva Merchant/Order,\r\n[SUPPORT] Server Appears Down|Server Appears Down,\r\n[SUPPORT] Other|Other',
        'support@yourserver.com|Tech Support,\r\nbilling@yourserver.com|Billing,\r\ninfo@yourserver.com|Information',
        '[MB User] Tech Support Question|Tech Support Question,\r\n[MB User] Billing Question|Billing Question,\r\n[MB User] Information Question|Information Question,\r\n[MB User] Other Question|Other')"))
                {
                  echo "<li>--> <font color=blue>email <b>OK</b></font>";
                }
                else
                { $stop=1;
                  echo "<li> --> <font color=red>email <b>NOT OK</b></font>";
                }

if (mysql_query("INSERT INTO config (config_type, config_1, config_2, config_3, config_4, config_5, config_6, config_7, config_8, config_9, config_10,
                                                  config_11, config_12, config_13, config_14, config_15, config_16, config_17, config_18, config_19, config_20,
                                                  config_21, config_22, config_23, config_24, config_25, config_26, config_27, config_28, config_29, config_30,
                                                  config_31, config_32, config_33, config_34, config_35, config_36, config_37, config_38, config_39, config_40,
                                                  config_41, config_42, config_43, config_44, config_45, config_46, config_47, config_48, config_49, config_50)
VALUES ('payments',
        'disagree',
        '1',
        '1',
        '1',
        '1',
        '',
        '',
        '',
        'Visa,Mast,Disc,Amex',
        '1',
        'authorize',
        'testdrive',
        '3.0',
        '1',
        '',
        '$company_url Web Hosting',
        '1',
        'https://www.paypal.com/xclick/',
        '$emailx',
        'http://your.server.com/tier2/',
        'http://your.server.com/tier2/',
        'http://your.server.com/tier2/images/logo.gif',
        'http://images.paypal.com/images/x-click-but02.gif',
        'ModernHost.com Hosting Invoice',
        '1',
        '1',
        '1',
        '1',
        '5',
        '',
        'https://wwws.echo-inc.com/scripts/INR300.EXE',
        '123456789',
        '123456789',
        '1',
        '1',
        '123456',
        '.10',
        '1',
        '1',
        'https://select.worldpay.com/wcc/purchase',
        '100',
        '123456',
        'USD',
        '6',
        '',
        '',
        '',
        '',
        '',
        '')"))
                {
                  echo "<li>--> <font color=blue>payments <b>OK</b></font>";
                }
                else
                { $stop=1;
                  echo "<li> --> <font color=red>payments <b>NOT OK</b></font>";
                }

if (mysql_query("INSERT INTO config (config_type, config_1, config_2, config_3, config_4, config_5, config_6, config_7, config_8, config_9, config_10,
                                                  config_11, config_12, config_13, config_14, config_15, config_16, config_17, config_18, config_19, config_20,
                                                  config_21, config_22, config_23, config_24, config_25, config_26, config_27, config_28, config_29, config_30,
                                                  config_31, config_32, config_33, config_34, config_35, config_36, config_37, config_38, config_39, config_40,
                                                  config_41, config_42, config_43, config_44, config_45, config_46, config_47, config_48, config_49, config_50)

VALUES ('client_extras_1_5','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','')"))
                {
                  echo "<li>--> <font color=blue>client_extras_1_5 <b>OK</b></font>";
                }
                else
                { $stop=1;
                  echo "<li> --> <font color=red>client_extras_1_5 <b>NOT OK</b></font>";
                }

if (mysql_query("INSERT INTO config (config_type, config_1, config_2, config_3, config_4, config_5, config_6, config_7, config_8, config_9, config_10,
                                                  config_11, config_12, config_13, config_14, config_15, config_16, config_17, config_18, config_19, config_20,
                                                  config_21, config_22, config_23, config_24, config_25, config_26, config_27, config_28, config_29, config_30,
                                                  config_31, config_32, config_33, config_34, config_35, config_36, config_37, config_38, config_39, config_40,
                                                  config_41, config_42, config_43, config_44, config_45, config_46, config_47, config_48, config_49, config_50)

VALUES ('client_extras_6_10','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','')"))
                {
                  echo "<li>--> <font color=blue>client_extras_6_10 <b>OK</b></font>";
                }
                else
                { $stop=1;
                  echo "<li> --> <font color=red>client_extras_6_10 <b>NOT OK</b></font>";
                }

if (mysql_query("INSERT INTO config (config_type, config_1, config_2, config_3, config_4, config_5, config_6, config_7, config_8, config_9, config_10,
                                                  config_11, config_12, config_13, config_14, config_15, config_16, config_17, config_18, config_19, config_20,
                                                  config_21, config_22, config_23, config_24, config_25, config_26, config_27, config_28, config_29, config_30,
                                                  config_31, config_32, config_33, config_34, config_35, config_36, config_37, config_38, config_39, config_40,
                                                  config_41, config_42, config_43, config_44, config_45, config_46, config_47, config_48, config_49, config_50)
VALUES ('theme_newleft',
        'FFFF99',
        'FFFFCC',
        'FFFFFF',
        '2',
        '2',
        '768',
        '768',
        '336699',
        'FFFFFF',
        'ModernBill .:. Client Billing System',
        '336699',
        'images/logo_blue.gif',
        '336699',
        'images/delete_can.gif',
        'images/edit_pencil.gif',
        'images/minus.gif',
        'images/plus.gif',
        'Verdana, Arial, Helvetica, sans-serif',
        '3',
        '1',
        '2',
        '',
        '336699',
        'FFFFFF',
        '',
        '',
        '',
        '',
        '',
        '',
        '',
        '',
        '',
        '',
        '',
        '',
        '',
        '',
        '',
        '',
        '',
        '',
        '',
        '',
        '',
        '',
        '',
        '',
        '',
        'a:link   { color: #000000; font: 7.5pt Verdana, Arial, Helvetica, sans-serif; font-weight: none; text-decoration: underlined }\r\n\r\na:visited { color: #000000; font: 7.5pt Verdana, Arial, Helvetica, sans-serif; font-weight: none; text-decoration: underlined }\r\n\r\na:active  { color: #000080; font: 7.5pt Verdana, Arial, Helvetica, sans-serif; font-weight: none; text-decoration: none }\r\n\r\na:hover   { color: #000080; font: 7.5pt Verdana, Arial, Helvetica, sans-serif; font-weight: none; text-decoration: none }\r\n\r\ntd        { color: #000000; font: 7.5pt Verdana, Arial, Helvetica, sans-serif; font-weight: none; text-decoration: none }\r\n\r\nbody      { color: #000000; font: 7.5pt Verdana, Arial, Helvetica, sans-serif; font-weight: none; text-decoration: none }\r\n\r\ninput     { color:#000000; font: 7.5pt Verdana, Arial, Helvetica, sans-serif; font-weight: none; text-decoration: none; background: #CCCCCC; border: 1 solid #555555; }\r\n\r\ntextarea  { color:#000000; font: 7.5pt Verdana, Arial, Helvetica, sans-serif; font-weight: none; text-decoration: none; background: #CCCCCC; border: 1 solid #555555; }\r\n\r\nselect    { color:#000000; font: 7.5pt Verdana, Arial, Helvetica, sans-serif; font-weight: none; text-decoration: none; background: #CCCCCC; border: 1 solid #555555; }\r\n')"))
                {
                  echo "<li>--> <font color=blue>theme_newleft <b>OK</b></font>";
                }
                else
                { $stop=1;
                  echo "<li> --> <font color=red>theme_newleft <b>NOT OK</b></font>";
                }


if (mysql_query("INSERT INTO config (config_type, config_1, config_2, config_3, config_4, config_5, config_6, config_7, config_8, config_9, config_10,
                                                  config_11, config_12, config_13, config_14, config_15, config_16, config_17, config_18, config_19, config_20,
                                                  config_21, config_22, config_23, config_24, config_25, config_26, config_27, config_28, config_29, config_30,
                                                  config_31, config_32, config_33, config_34, config_35, config_36, config_37, config_38, config_39, config_40,
                                                  config_41, config_42, config_43, config_44, config_45, config_46, config_47, config_48, config_49, config_50)
VALUES ('theme_newtop',
        'FFFF99',
        'FFFFCC',
        'FFFFFF',
        '2',
        '2',
        '768',
        '768',
        '336699',
        'FFFFFF',
        'ModernBill .:. Client Billing System',
        '336699',
        'images/logo_blue.gif',
        '336699',
        'images/delete_can.gif',
        'images/edit_pencil.gif',
        'images/minus.gif',
        'images/plus.gif',
        'Verdana, Arial, Helvetica, sans-serif',
        '3',
        '1',
        '2',
        '',
        '336699',
        'FFFFFF',
        '',
        '',
        '',
        '',
        '',
        '',
        '',
        '',
        '',
        '',
        '',
        '',
        '',
        '',
        '',
        '',
        '',
        '',
        '',
        '',
        '',
        '',
        '',
        '',
        '',
        'a:link   { color: #000000; font: 7.5pt Verdana, Arial, Helvetica, sans-serif; font-weight: none; text-decoration: underlined }\r\n\r\na:visited { color: #000000; font: 7.5pt Verdana, Arial, Helvetica, sans-serif; font-weight: none; text-decoration: underlined }\r\n\r\na:active  { color: #000080; font: 7.5pt Verdana, Arial, Helvetica, sans-serif; font-weight: none; text-decoration: none }\r\n\r\na:hover   { color: #000080; font: 7.5pt Verdana, Arial, Helvetica, sans-serif; font-weight: none; text-decoration: none }\r\n\r\ntd        { color: #000000; font: 7.5pt Verdana, Arial, Helvetica, sans-serif; font-weight: none; text-decoration: none }\r\n\r\nbody      { color: #000000; font: 7.5pt Verdana, Arial, Helvetica, sans-serif; font-weight: none; text-decoration: none }\r\n\r\ninput     { color:#000000; font: 7.5pt Verdana, Arial, Helvetica, sans-serif; font-weight: none; text-decoration: none; background: #CCCCCC; border: 1 solid #555555; }\r\n\r\ntextarea  { color:#000000; font: 7.5pt Verdana, Arial, Helvetica, sans-serif; font-weight: none; text-decoration: none; background: #CCCCCC; border: 1 solid #555555; }\r\n\r\nselect    { color:#000000; font: 7.5pt Verdana, Arial, Helvetica, sans-serif; font-weight: none; text-decoration: none; background: #CCCCCC; border: 1 solid #555555; }\r\n')"))
                {
                  echo "<li>--> <font color=blue>theme_newtop <b>OK</b></font>";
                }
                else
                { $stop=1;
                  echo "<li> --> <font color=red>theme_newtop <b>NOT OK</b></font>";
                }
?>
</ul>
<center><b>
<? if ($stop) { ?>
<font color=red>An error has occured.
Please print this screen and send it to the <a href=mailto:admin@yourserver.com>admin</a> for review.
(We recommend that you copy this page and send an HTML email.)</font>
<? } else { ?>
<font color=blue>All config values inserted successfully!</font>
<? } ?>
</b></center>
<br>