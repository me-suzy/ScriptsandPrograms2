<ul>
<? // 2.03 to 3.0 UPGRADE INSTALL

$stop=NULL;

if (mysql_query("ALTER TABLE account_details
                       ADD INDEX(client_id),
                       ADD INDEX(cp_id)"))
                       { echo "<li>--> <font color=blue>modified account_details<b>OK</b></font>"; } else
                       { $stop=1;  echo "<li> --> <font color=red>modified account_details <b>NOT OK</b></font>"; }


if (mysql_query("ALTER TABLE client_credit   ADD INDEX(client_id)"))
                       { echo "<li>--> <font color=blue>modified client_credit<b>OK</b></font>"; } else
                       { $stop=1;  echo "<li> --> <font color=red>modified client_credit <b>NOT OK</b></font>"; }


if (mysql_query("ALTER TABLE client_invoice  ADD INDEX(client_id)"))
                       { echo "<li>--> <font color=blue>modified client_invoice<b>OK</b></font>"; } else
                       { $stop=1;  echo "<li> --> <font color=red>modified client_invoice <b>NOT OK</b></font>"; }


if (mysql_query("ALTER TABLE client_package
                       ADD pack_price VARCHAR(11) DEFAULT '0' NOT NULL AFTER pack_id,
                       ADD parent_cp_id INT(11) AFTER pack_price,
                       ADD INDEX(client_id)"))
                       { echo "<li>--> <font color=blue>modified client_package<b>OK</b></font>"; } else
                       { $stop=1;  echo "<li> --> <font color=red>modified client_package <b>NOT OK</b></font>"; }


if (mysql_query("ALTER TABLE domain_names    ADD INDEX(client_id)"))
                       { echo "<li>--> <font color=blue>modified domain_names<b>OK</b></font>"; } else
                       { $stop=1;  echo "<li> --> <font color=red>modified domain_names <b>NOT OK</b></font>"; }


if (mysql_query("ALTER TABLE event_log       ADD INDEX(client_id)"))
                       { echo "<li>--> <font color=blue>modified event_log<b>OK</b></font>"; } else
                       { $stop=1;  echo "<li> --> <font color=red>modified event_log <b>NOT OK</b></font>"; }


if (mysql_query("ALTER TABLE package_type    ADD pack_cost VARCHAR(11) DEFAULT '0' NOT NULL AFTER pack_setup"))
                       { echo "<li>--> <font color=blue>modified package_type<b>OK</b></font>"; } else
                       { $stop=1;  echo "<li> --> <font color=red>modified package_type <b>NOT OK</b></font>"; }

if (mysql_query("ALTER TABLE client_info
                       ADD client_secondary_email varchar(255) default NULL,
                       ADD client_username varchar(255) default NULL,
                       ADD client_real_pass varchar(10) default NULL,
                       ADD x_Bank_Name varchar(100) default NULL,
                       ADD x_Bank_ABA_Code varchar(100) default NULL,
                       ADD x_Bank_Acct_Num varchar(100) default NULL,
                       ADD x_Drivers_License_Num varchar(100) default NULL,
                       ADD x_Drivers_License_State varchar(100) default NULL,
                       ADD x_Drivers_License_DOB varchar(100) default NULL,
                       ADD apply_tax tinyint(3) default NULL,
                       ADD default_translation varchar(100) default NULL,
                       ADD default_currency varchar(100) default NULL,
                       ADD send_email_type varchar(100) default NULL,
                       ADD secondary_contact varchar(255) default NULL,
                       ADD client_field_1 varchar(255) default NULL,
                       ADD client_field_2 varchar(255) default NULL,
                       ADD client_field_3 varchar(255) default NULL,
                       ADD client_field_4 varchar(255) default NULL,
                       ADD client_field_5 varchar(255) default NULL,
                       ADD client_field_6 varchar(255) default NULL,
                       ADD client_field_7 VARCHAR(255) default NULL,
                       ADD client_field_8 VARCHAR(255) default NULL,
                       ADD client_field_9 VARCHAR(255) default NULL,
                       ADD client_field_10 VARCHAR(255)"))
                       { echo "<li>--> <font color=blue>modified client_info<b>OK</b></font>"; } else
                       { $stop=1;  echo "<li> --> <font color=red>modified client_info <b>NOT OK</b></font>"; }


if (mysql_query("CREATE TABLE banned_config (
  ban_id int(11) NOT NULL auto_increment,
  ban_type tinyint(3) NOT NULL default '0',
  ban_string varchar(255) NOT NULL default '',
  ban_message mediumtext NOT NULL,
  ban_count int(11) NOT NULL default '0',
  ban_status tinyint(3) NOT NULL default '0',
  ban_last_stamp int(11) NOT NULL default '0',
  PRIMARY KEY (ban_id),
  KEY ban_string(ban_string)
) TYPE=MyISAM")) { echo "<li>--> <font color=blue>banned_config <b>OK</b></font>"; } else { $stop=1;  echo "<li> --> <font color=red>banned_config <b>NOT OK</b></font>"; }

if (mysql_query("CREATE TABLE support_desk (
  call_id int(11) NOT NULL auto_increment,
  client_id int(11) NOT NULL default '0',
  call_priority tinyint(3) NOT NULL default '0',
  call_type varchar(255) NOT NULL default '0',
  call_subject varchar(255) NOT NULL default '',
  call_question mediumtext NOT NULL,
  call_error mediumtext NOT NULL,
  call_stamp int(11) NOT NULL default '0',
  call_status tinyint(3) NOT NULL default '0',
  call_response mediumtext NOT NULL,
  call_technician varchar(255) NOT NULL default '',
  PRIMARY KEY (call_id)
) TYPE=MyISAM")) { echo "<li>--> <font color=blue>support_desk <b>OK</b></font>"; } else { $stop=1;  echo "<li> --> <font color=red>support_desk <b>NOT OK</b></font>"; }


if (mysql_query("CREATE TABLE support_log (
  log_id int(11) NOT NULL auto_increment,
  call_id int(11) NOT NULL default '0',
  log_event mediumtext NOT NULL,
  call_technician int(11) NOT NULL default '0',
  log_stamp int(11) NOT NULL default '0',
  PRIMARY KEY (log_id)
) TYPE=MyISAM")) { echo "<li>--> <font color=blue>support_log <b>OK</b></font>"; } else { $stop=1;  echo "<li> --> <font color=red>support_log <b>NOT OK</b></font>"; }

@mysql_query("DROP TABLE IF EXISTS faq_categories");
if (mysql_query("CREATE TABLE faq_categories (
  cid tinyint(11) NOT NULL auto_increment,
  cname varchar(50) NOT NULL default '',
  ctype tinyint(3) NOT NULL default '0',
  PRIMARY KEY (cid)
) TYPE=MyISAM")) { echo "<li>--> <font color=blue>faq_categories <b>OK</b></font>"; } else { $stop=1;  echo "<li> --> <font color=red>faq_categories <b>NOT OK</b></font>"; }


@mysql_query("DROP TABLE IF EXISTS faq_questions");
if (mysql_query("CREATE TABLE faq_questions (
  fid tinyint(11) NOT NULL auto_increment,
  cid tinyint(11) NOT NULL default '0',
  question mediumtext NOT NULL,
  answer mediumtext NOT NULL,
  timestamp varchar(50) NOT NULL default '',
  PRIMARY KEY (fid)
) TYPE=MyISAM")) { echo "<li>--> <font color=blue>faq_questions <b>OK</b></font>"; } else { $stop=1;  echo "<li> --> <font color=red>faq_questions <b>NOT OK</b></font>"; }


if ($drop_tables) mysql_query("DROP TABLE IF EXISTS package_relationships");
if (mysql_query("CREATE TABLE package_relationships (
  pr_id int(11) NOT NULL auto_increment,
  parent_pack_id int(11) NOT NULL default '0',
  child_pack_id int(11) NOT NULL default '0',
  pr_status int(11) NOT NULL default '0',
  PRIMARY KEY (pr_id)
) TYPE=MyISAM")) { echo "<li>--> <font color=blue>package_relationships <b>OK</b></font>"; } else { $stop=1;  echo "<li> --> <font color=red>package_relationships <b>NOT OK</b></font>"; }


if (mysql_query("CREATE TABLE client_register (
  reg_id int(11) NOT NULL auto_increment,
  client_id int(11) NOT NULL default '0',
  reg_date int(11) NOT NULL default '0',
  reg_desc varchar(255) NOT NULL default '',
  invoice_id int(11) NOT NULL default '0',
  reg_bill varchar(11) NOT NULL default '0',
  reg_payment varchar(11) NOT NULL default '0',
  reg_tracker varchar(11) NOT NULL default '',
  PRIMARY KEY (reg_id),
  KEY client_id(client_id)
) TYPE=MyISAM")) { echo "<li>--> <font color=blue>client_register <b>OK</b></font>"; } else { $stop=1;  echo "<li> --> <font color=red>client_register <b>NOT OK</b></font>"; }


if (mysql_query("CREATE TABLE coupon_codes (
  coupon_id int(11) NOT NULL auto_increment,
  coupon_code varchar(255) NOT NULL default '',
  coupon_percent_discount decimal(10,2) NOT NULL default '0.00',
  coupon_dollar_discount decimal(10,2) NOT NULL default '0.00',
  coupon_comments mediumtext,
  coupon_status tinyint(3) default NULL,
  coupon_start_stamp int(11) default NULL,
  coupon_end_stamp int(11) default NULL,
  coupon_expire_string varchar(255) default NULL,
  coupon_count int(11) default NULL,
  coupon_max_count int(11) default NULL,
  coupon_new_only tinyint(3) default NULL,
  coupon_misc1 varchar(255) default NULL,
  coupon_misc2 varchar(255) default NULL,
  PRIMARY KEY (coupon_id)
) TYPE=MyISAM")) { echo "<li>--> <font color=blue>coupon_codes <b>OK</b></font>"; } else { $stop=1;  echo "<li> --> <font color=red>coupon_codes <b>NOT OK</b></font>"; }

if (mysql_query("CREATE TABLE tld_config (
  tld_id int(11) NOT NULL auto_increment,
  tld_extension varchar(25) NOT NULL default '',
  tld_name varchar(100) NOT NULL default '',
  tld_whois_server varchar(100) NOT NULL default '',
  tld_whois_response varchar(100) NOT NULL default '',
  tld_accepted tinyint(3) NOT NULL default '0',
  tld_auto_search tinyint(3) NOT NULL default '0',
  tld_transfer decimal(10,2) NOT NULL default '0.00',
  tld_1y decimal(10,2) NOT NULL default '0.00',
  tld_2y decimal(10,2) NOT NULL default '0.00',
  tld_3y decimal(10,2) NOT NULL default '0.00',
  tld_4y decimal(10,2) NOT NULL default '0.00',
  tld_5y decimal(10,2) NOT NULL default '0.00',
  tld_6y decimal(10,2) NOT NULL default '0.00',
  tld_7y decimal(10,2) NOT NULL default '0.00',
  tld_8y decimal(10,2) NOT NULL default '0.00',
  tld_9y decimal(10,2) NOT NULL default '0.00',
  tld_10y decimal(10,2) NOT NULL default '0.00',
  registrar_id tinyint(3) NOT NULL default '0',
  pack_id int(11) NOT NULL default '0',
  PRIMARY KEY (tld_id)
) TYPE=MyISAM")) { echo "<li>--> <font color=blue>tld_config <b>OK</b></font>"; } else { $stop=1;  echo "<li> --> <font color=red>tld_config <b>NOT OK</b></font>"; }

if (mysql_query("CREATE TABLE affiliate_config (
  aff_id int(11) NOT NULL auto_increment,
  client_id int(11) NOT NULL default '0',
  aff_code varchar(255) NOT NULL default '',
  aff_hits int(11) NOT NULL default '0',
  aff_count int(11) NOT NULL default '0',
  aff_pay_type tinyint(3) NOT NULL default '0',
  aff_pay_sum decimal(10,2) NOT NULL default '0.00',
  aff_status tinyint(3) NOT NULL default '0',
  aff_stamp int(11) NOT NULL default '0',
  PRIMARY KEY (aff_id)
) TYPE=MyISAM")) { echo "<li>--> <font color=blue>affiliate_config <b>OK</b></font>"; } else { $stop=1;  echo "<li> --> <font color=red>affiliate_config <b>NOT OK</b></font>"; }

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

mysql_query("UPDATE config SET config_6  = 'newleft',
                               config_33 = 'modernserver,modernhost',
                               config_35 = 'We are temporarily closed. Please check back shortly.',
                               config_36 = 'Y/m/d',
                               config_41 = '<a href=user.php?op=faq>Online FAQ</a><br>\r\n' WHERE config_type = 'main'");

mysql_query("ALTER TABLE client_package ADD aff_code VARCHAR(255)");
mysql_query("ALTER TABLE client_package ADD aff_last_paid INT(11)");
mysql_query("ALTER TABLE affiliate_config ADD aff_pay_time INT(5) NOT NULL AFTER aff_count");
mysql_query("ALTER TABLE affiliate_config ADD aff_pay_amount DECIMAL(10,2) NOT NULL AFTER aff_pay_time");
mysql_query("ALTER TABLE affiliate_config ADD aff_pay_cycle INT(11) NOT NULL AFTER aff_pay_type");
@mysql_query("CREATE TABLE client_news (
                           ID bigint(255) NOT NULL default '0',
                           Subject text NOT NULL,
                           Post_user text NOT NULL,
                           Post_email text NOT NULL,
                           Date text NOT NULL,
                           Time text NOT NULL,
                           Headline_date text NOT NULL,
                           Date_time text NOT NULL,
                           Text text NOT NULL,
                           Modify_date text NOT NULL,
                           Modify_user text NOT NULL,
                           mainpage enum('N','Y') NOT NULL default 'N',
                           mainid int(255) NOT NULL default '0'
                           ) TYPE=MyISAM");
                           
if (mysql_query("CREATE TABLE sessions (id varchar(50) NOT NULL default '',data mediumtext NOT NULL,t_stamp timestamp(14) NOT NULL,PRIMARY KEY (id),KEY t_stamp (t_stamp))")) {
    echo "<li> <font color=blue>The \"sessions\" table was added successfully.</font>";
} else {
    echo "<li> <font color=red>The \"sessions\" table was NOT added successfully.</font>";
}

if (mysql_query("ALTER TABLE config ADD config_51 VARCHAR(255),ADD config_52 VARCHAR(255),ADD config_53 VARCHAR(255),ADD config_54 VARCHAR(255),ADD config_55 VARCHAR(255),ADD config_56 VARCHAR(255),ADD config_57 VARCHAR(255),ADD config_58 VARCHAR(255),ADD config_59 VARCHAR(255),ADD config_60 VARCHAR(255)")) {
    echo "<li> <font color=blue>The \"config\" table was updated successfully.</font>";
} else {
    echo "<li> <font color=red>The \"config\" table was NOT updated successfully.</font>";
}
?>
</ul>
<center><b>
<? if ($stop) { ?>
<font color=red>An error has occured.
Please print this screen and send it to the <a href=mailto:admin@modernbill.com>admin</a> for review.
(We recommend that you copy this page and send an HTML email.)</font>
<? } else { ?>
<font color=blue>All tables updated successfully!</font>
<? } ?>
</b></center>
<br>