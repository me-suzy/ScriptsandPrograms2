<ul>
<? // 3.0 FULL INSTALL

$stop=NULL;

if ($drop_tables) mysql_query("DROP TABLE IF EXISTS account_dbs");
if (mysql_query("CREATE TABLE account_dbs (
  db_id int(11) NOT NULL auto_increment,
  client_id int(11) NOT NULL default '0',
  cp_id int(11) NOT NULL default '0',
  db_type int(11) NOT NULL default '0',
  db_name varchar(25) default NULL,
  db_user varchar(25) default NULL,
  db_pass varchar(25) default NULL,
  db_stamp int(11) NOT NULL default '0',
  PRIMARY KEY (db_id)
) TYPE=MyISAM")) { echo "<li>--> <font color=blue>account_dbs <b>OK</b></font>"; } else { $stop=1;  echo "<li> --> <font color=red>account_dbs <b>NOT OK</b></font>"; }


if ($drop_tables) mysql_query("DROP TABLE IF EXISTS account_details");
if (mysql_query("CREATE TABLE account_details (
  details_id int(11) NOT NULL auto_increment,
  client_id int(11) NOT NULL default '0',
  cp_id int(11) NOT NULL default '0',
  domain_id int(11) default NULL,
  ip varchar(255) default NULL,
  server varchar(255) NOT NULL default '',
  server_type int(11) NOT NULL default '0',
  username varchar(255) NOT NULL default '',
  password varchar(255) default NULL,
  PRIMARY KEY (details_id),
  KEY client_id(client_id),
  KEY cp_id(cp_id)
) TYPE=MyISAM")) { echo "<li>--> <font color=blue>account_details <b>OK</b></font>"; } else { $stop=1;  echo "<li> --> <font color=red>account_details <b>NOT OK</b></font>"; }


if ($drop_tables) mysql_query("DROP TABLE IF EXISTS account_pops");
if (mysql_query("CREATE TABLE account_pops (
  pop_id int(11) NOT NULL auto_increment,
  client_id int(11) NOT NULL default '0',
  cp_id int(11) NOT NULL default '0',
  pop_real_name varchar(255) default NULL,
  pop_username varchar(255) default NULL,
  pop_password varchar(255) default NULL,
  pop_space varchar(25) NOT NULL default '0',
  pop_ftp tinyint(3) NOT NULL default '0',
  pop_telnet tinyint(3) NOT NULL default '0',
  pop_stamp int(11) NOT NULL default '0',
  PRIMARY KEY (pop_id)
) TYPE=MyISAM")) { echo "<li>--> <font color=blue>account_pops <b>OK</b></font>"; } else { $stop=1;  echo "<li> --> <font color=red>account_pops <b>NOT OK</b></font>"; }


if ($drop_tables) mysql_query("DROP TABLE IF EXISTS admin");
if (mysql_query("CREATE TABLE admin (
  admin_id int(11) NOT NULL auto_increment,
  admin_realname varchar(50) NOT NULL default '',
  admin_email varchar(100) NOT NULL default '',
  admin_username varchar(25) NOT NULL default '',
  admin_password varchar(255) NOT NULL default '',
  admin_level tinyint(3) NOT NULL default '0',
  PRIMARY KEY (admin_id)
) TYPE=MyISAM")) { echo "<li>--> <font color=blue>admin <b>OK</b></font>"; } else { $stop=1;  echo "<li> --> <font color=red>admin <b>NOT OK</b></font>"; }


if ($drop_tables) mysql_query("DROP TABLE IF EXISTS affiliate_config");
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



if ($drop_tables) mysql_query("DROP TABLE IF EXISTS authnet_batch");
if (mysql_query("CREATE TABLE authnet_batch (
  an_id int(11) NOT NULL auto_increment,
  x_Invoice_Num int(11) NOT NULL default '0',
  x_Description varchar(100) NOT NULL default '',
  x_Amount decimal(10,2) NOT NULL default '0.00',
  x_Method varchar(25) NOT NULL default '',
  x_Type varchar(25) NOT NULL default '',
  x_Card_Num mediumtext NOT NULL,
  x_Exp_Date varchar(25) NOT NULL default '',
  x_CC_Code int(11) NOT NULL default '0',
  x_Cust_ID int(11) NOT NULL default '0',
  x_First_Name varchar(100) NOT NULL default '',
  x_Last_Name varchar(100) NOT NULL default '',
  x_Company varchar(100) NOT NULL default '',
  x_Address varchar(150) NOT NULL default '',
  x_City varchar(100) NOT NULL default '',
  x_State varchar(25) NOT NULL default '',
  x_Zip varchar(25) NOT NULL default '0',
  x_Phone varchar(15) NOT NULL default '0',
  x_Email varchar(100) NOT NULL default '',
  an_stamp int(11) NOT NULL default '0',
  PRIMARY KEY (an_id)
) TYPE=MyISAM")) { echo "<li>--> <font color=blue>authnet_batch <b>OK</b></font>"; } else { $stop=1;  echo "<li> --> <font color=red>authnet_batch <b>NOT OK</b></font>"; }


if ($drop_tables) mysql_query("DROP TABLE IF EXISTS banned_config");
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


if ($drop_tables) mysql_query("DROP TABLE IF EXISTS batch_details");
if (mysql_query("CREATE TABLE batch_details (
  batch_id int(11) NOT NULL auto_increment,
  batch_stamp int(11) NOT NULL default '0',
  batch_sum_approved decimal(10,2) NOT NULL default '0.00',
  batch_sum_declined decimal(10,2) NOT NULL default '0.00',
  batch_sum_error decimal(10,2) NOT NULL default '0.00',
  batch_num_approved int(11) NOT NULL default '0',
  batch_num_declined int(11) NOT NULL default '0',
  batch_num_error int(11) NOT NULL default '0',
  PRIMARY KEY (batch_id)
) TYPE=MyISAM")) { echo "<li>--> <font color=blue>batch_details <b>OK</b></font>"; } else { $stop=1;  echo "<li> --> <font color=red>batch_details <b>NOT OK</b></font>"; }


if ($drop_tables) mysql_query("DROP TABLE IF EXISTS client_credit");
if (mysql_query("CREATE TABLE client_credit (
  credit_id int(11) NOT NULL auto_increment,
  client_id int(11) NOT NULL default '0',
  credit_amount decimal(10,2) NOT NULL default '0.00',
  credit_comments mediumtext,
  credit_stamp int(11) NOT NULL default '0',
  PRIMARY KEY (credit_id),
  KEY client_id(client_id)
) TYPE=MyISAM")) { echo "<li>--> <font color=blue>client_credit <b>OK</b></font>"; } else { $stop=1;  echo "<li> --> <font color=red>client_credit <b>NOT OK</b></font>"; }


if ($drop_tables) mysql_query("DROP TABLE IF EXISTS client_info");
if (mysql_query("CREATE TABLE client_info (
  client_id int(11) NOT NULL auto_increment,
  client_fname varchar(100) NOT NULL default '',
  client_lname varchar(100) NOT NULL default '',
  client_email varchar(100) NOT NULL default '',
  client_company varchar(100) default NULL,
  client_address varchar(100) NOT NULL default '',
  client_city varchar(100) NOT NULL default '',
  client_state varchar(100) NOT NULL default '',
  client_zip varchar(100) NOT NULL default '',
  client_country varchar(100) NOT NULL default '',
  client_phone1 varchar(25) NOT NULL default '',
  client_phone2 varchar(25) NOT NULL default '',
  billing_method varchar(25) NOT NULL default '',
  billing_cc_type varchar(25) default NULL,
  billing_cc_num varchar(255) default NULL,
  billing_cc_exp varchar(25) default NULL,
  billing_cc_code int(11) default NULL,
  client_password varchar(255) NOT NULL default '',
  client_comments mediumtext NOT NULL,
  client_status varchar(50) NOT NULL default '',
  client_stamp int(11) NOT NULL default '0',
  client_secondary_email varchar(255) default NULL,
  client_username varchar(255) default NULL,
  client_real_pass varchar(10) default NULL,
  x_Bank_Name varchar(100) default NULL,
  x_Bank_ABA_Code varchar(100) default NULL,
  x_Bank_Acct_Num varchar(100) default NULL,
  x_Drivers_License_Num varchar(100) default NULL,
  x_Drivers_License_State varchar(100) default NULL,
  x_Drivers_License_DOB varchar(100) default NULL,
  apply_tax tinyint(3) default NULL,
  default_translation varchar(100) default NULL,
  default_currency varchar(100) default NULL,
  send_email_type varchar(100) default NULL,
  secondary_contact varchar(255) default NULL,
  client_field_1 varchar(255) default NULL,
  client_field_2 varchar(255) default NULL,
  client_field_3 varchar(255) default NULL,
  client_field_4 varchar(255) default NULL,
  client_field_5 varchar(255) default NULL,
  client_field_6 varchar(255) default NULL,
  client_field_7 varchar(255) default NULL,
  client_field_8 varchar(255) default NULL,
  client_field_9 varchar(255) default NULL,
  client_field_10 varchar(255) default NULL,
  PRIMARY KEY (client_id)
) TYPE=MyISAM")) { echo "<li>--> <font color=blue>client_info <b>OK</b></font>"; } else { $stop=1;  echo "<li> --> <font color=red>client_info <b>NOT OK</b></font>"; }


if ($drop_tables) mysql_query("DROP TABLE IF EXISTS client_invoice");
if (mysql_query("CREATE TABLE client_invoice (
  invoice_id int(11) NOT NULL auto_increment,
  client_id int(11) NOT NULL default '0',
  invoice_amount decimal(10,2) NOT NULL default '0.00',
  invoice_amount_paid decimal(10,2) NOT NULL default '0.00',
  invoice_date_entered int(11) NOT NULL default '0',
  invoice_date_due int(11) NOT NULL default '0',
  invoice_date_paid int(11) NOT NULL default '0',
  invoice_payment_method varchar(50) NOT NULL default '',
  invoice_snapshot mediumtext NOT NULL,
  invoice_comments mediumtext,
  auth_return tinyint(3) NOT NULL default '0',
  auth_code int(11) NOT NULL default '0',
  avs_code char(3) NOT NULL default '',
  trans_id int(11) NOT NULL default '0',
  batch_stamp int(11) NOT NULL default '0',
  invoice_stamp int(11) NOT NULL default '0',
  PRIMARY KEY (invoice_id),
  KEY client_id(client_id)
) TYPE=MyISAM")) { echo "<li>--> <font color=blue>client_invoice <b>OK</b></font>"; } else { $stop=1;  echo "<li> --> <font color=red>client_invoice <b>NOT OK</b></font>"; }


if ($drop_tables) mysql_query("DROP TABLE IF EXISTS client_package");
if (mysql_query("CREATE TABLE client_package (
  cp_id int(11) NOT NULL auto_increment,
  client_id int(11) NOT NULL default '0',
  pack_id int(11) NOT NULL default '0',
  pack_price varchar(11) NOT NULL default '0',
  parent_cp_id int(11) default NULL,
  cp_qty int(11) NOT NULL default '1',
  cp_discount decimal(5,2) NOT NULL default '0.00',
  cp_start_stamp int(11) NOT NULL default '0',
  cp_renew_stamp int(11) NOT NULL default '0',
  cp_billing_cycle varchar(25) NOT NULL default '',
  cp_status varchar(25) NOT NULL default '',
  cp_comments mediumtext,
  cp_renewed_on int(11) NOT NULL default '0',
  cp_stamp int(11) NOT NULL default '0',
  PRIMARY KEY (cp_id),
  KEY client_id(client_id)
) TYPE=MyISAM")) { echo "<li>--> <font color=blue>client_package <b>OK</b></font>"; } else { $stop=1;  echo "<li> --> <font color=red>client_package <b>NOT OK</b></font>"; }


if ($drop_tables) mysql_query("DROP TABLE IF EXISTS client_register");
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


if ($drop_tables) mysql_query("DROP TABLE IF EXISTS config");
if (mysql_query("CREATE TABLE config (
  config_type varchar(255) NOT NULL default '',
  config_1 varchar(255) default NULL,
  config_2 varchar(255) default NULL,
  config_3 varchar(255) default NULL,
  config_4 varchar(255) default NULL,
  config_5 varchar(255) default NULL,
  config_6 varchar(255) default NULL,
  config_7 varchar(255) default NULL,
  config_8 varchar(255) default NULL,
  config_9 varchar(255) default NULL,
  config_10 varchar(255) default NULL,
  config_11 varchar(255) default NULL,
  config_12 varchar(255) default NULL,
  config_13 varchar(255) default NULL,
  config_14 varchar(255) default NULL,
  config_15 varchar(255) default NULL,
  config_16 varchar(255) default NULL,
  config_17 varchar(255) default NULL,
  config_18 varchar(255) default NULL,
  config_19 varchar(255) default NULL,
  config_20 varchar(255) default NULL,
  config_21 varchar(255) default NULL,
  config_22 varchar(255) default NULL,
  config_23 varchar(255) default NULL,
  config_24 varchar(255) default NULL,
  config_25 varchar(255) default NULL,
  config_26 varchar(255) default NULL,
  config_27 varchar(255) default NULL,
  config_28 varchar(255) default NULL,
  config_29 varchar(255) default NULL,
  config_30 varchar(255) default NULL,
  config_31 varchar(255) default NULL,
  config_32 varchar(255) default NULL,
  config_33 varchar(255) default NULL,
  config_34 varchar(255) default NULL,
  config_35 varchar(255) default NULL,
  config_36 varchar(255) default NULL,
  config_37 varchar(255) default NULL,
  config_38 varchar(255) default NULL,
  config_39 varchar(255) default NULL,
  config_40 varchar(255) default NULL,
  config_41 mediumtext,
  config_42 mediumtext,
  config_43 mediumtext,
  config_44 mediumtext,
  config_45 mediumtext,
  config_46 mediumtext,
  config_47 mediumtext,
  config_48 mediumtext,
  config_49 mediumtext,
  config_50 mediumtext,
  PRIMARY KEY (config_type)
) TYPE=MyISAM")) { echo "<li>--> <font color=blue>config <b>OK</b></font>"; } else { $stop=1;  echo "<li> --> <font color=red>config <b>NOT OK</b></font>"; }


if ($drop_tables) mysql_query("DROP TABLE IF EXISTS coupon_codes");
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


if ($drop_tables) mysql_query("DROP TABLE IF EXISTS domain_names");
if (mysql_query("CREATE TABLE domain_names (
  domain_id int(11) NOT NULL auto_increment,
  domain_name varchar(255) NOT NULL default '',
  client_id int(11) NOT NULL default '0',
  domain_created int(11) NOT NULL default '0',
  domain_expires int(11) NOT NULL default '0',
  registrar_id int(11) NOT NULL default '0',
  monitor tinyint(3) NOT NULL default '0',
  PRIMARY KEY (domain_id),
  KEY client_id(client_id)
) TYPE=MyISAM")) { echo "<li>--> <font color=blue>domain_names <b>OK</b></font>"; } else { $stop=1;  echo "<li> --> <font color=red>domain_names <b>NOT OK</b></font>"; }


if ($drop_tables) mysql_query("DROP TABLE IF EXISTS email_config");
if (mysql_query("CREATE TABLE email_config (
  email_id int(11) NOT NULL auto_increment,
  email_title varchar(255) NOT NULL default '',
  email_heading mediumtext NOT NULL,
  email_body mediumtext NOT NULL,
  email_footer mediumtext NOT NULL,
  email_signature mediumtext NOT NULL,
  email_stamp int(11) NOT NULL default '0',
  PRIMARY KEY (email_id)
) TYPE=MyISAM")) { echo "<li>--> <font color=blue>email_config <b>OK</b></font>"; } else { $stop=1;  echo "<li> --> <font color=red>email_config <b>NOT OK</b></font>"; }


if ($drop_tables) mysql_query("DROP TABLE IF EXISTS event_log");
if (mysql_query("CREATE TABLE event_log (
  log_id int(11) NOT NULL auto_increment,
  client_id int(11) NOT NULL default '0',
  log_type varchar(20) NOT NULL default '',
  log_comments mediumtext,
  log_stamp int(11) NOT NULL default '0',
  PRIMARY KEY (log_id),
  KEY client_id(client_id)
) TYPE=MyISAM")) { echo "<li>--> <font color=blue>event_log <b>OK</b></font>"; } else { $stop=1;  echo "<li> --> <font color=red>event_log <b>NOT OK</b></font>"; }


if ($drop_tables) mysql_query("DROP TABLE IF EXISTS faq_categories");
if (mysql_query("CREATE TABLE faq_categories (
  cid tinyint(11) NOT NULL auto_increment,
  cname varchar(50) NOT NULL default '',
  ctype tinyint(3) NOT NULL default '0',
  PRIMARY KEY (cid)
) TYPE=MyISAM")) { echo "<li>--> <font color=blue>faq_categories <b>OK</b></font>"; } else { $stop=1;  echo "<li> --> <font color=red>faq_categories <b>NOT OK</b></font>"; }


if ($drop_tables) mysql_query("DROP TABLE IF EXISTS faq_questions");
if (mysql_query("CREATE TABLE faq_questions (
  fid tinyint(11) NOT NULL auto_increment,
  cid tinyint(11) NOT NULL default '0',
  question mediumtext NOT NULL,
  answer mediumtext NOT NULL,
  timestamp varchar(50) NOT NULL default '',
  PRIMARY KEY (fid)
) TYPE=MyISAM")) { echo "<li>--> <font color=blue>faq_questions <b>OK</b></font>"; } else { $stop=1;  echo "<li> --> <font color=red>faq_questions <b>NOT OK</b></font>"; }


if ($drop_tables) mysql_query("DROP TABLE IF EXISTS myquery");
if (mysql_query("CREATE TABLE myquery (
  myqueryID int(5) NOT NULL auto_increment,
  mydatabase varchar(250) NOT NULL default '',
  myquery text,
  title varchar(255) NOT NULL default '',
  PRIMARY KEY (myqueryID),
  KEY title(title)
) TYPE=MyISAM")) { echo "<li>--> <font color=blue>myquery <b>OK</b></font>"; } else { $stop=1;  echo "<li> --> <font color=red>myquery <b>NOT OK</b></font>"; }


if ($drop_tables) mysql_query("DROP TABLE IF EXISTS package_feature");
if (mysql_query("CREATE TABLE package_feature (
  feature_id int(11) NOT NULL auto_increment,
  pack_id int(11) NOT NULL default '0',
  feature_name varchar(100) NOT NULL default '',
  feature_comments mediumtext,
  feature_stamp int(11) NOT NULL default '0',
  PRIMARY KEY (feature_id)
) TYPE=MyISAM")) { echo "<li>--> <font color=blue>package_feature <b>OK</b></font>"; } else { $stop=1;  echo "<li> --> <font color=red>package_feature <b>NOT OK</b></font>"; }


if ($drop_tables) mysql_query("DROP TABLE IF EXISTS package_relationships");
if (mysql_query("CREATE TABLE package_relationships (
  pr_id int(11) NOT NULL auto_increment,
  parent_pack_id int(11) NOT NULL default '0',
  child_pack_id int(11) NOT NULL default '0',
  pr_status int(11) NOT NULL default '0',
  PRIMARY KEY (pr_id)
) TYPE=MyISAM")) { echo "<li>--> <font color=blue>package_relationships <b>OK</b></font>"; } else { $stop=1;  echo "<li> --> <font color=red>package_relationships <b>NOT OK</b></font>"; }


if ($drop_tables) mysql_query("DROP TABLE IF EXISTS package_type");
if (mysql_query("CREATE TABLE package_type (
  pack_id int(11) NOT NULL auto_increment,
  pack_name varchar(100) NOT NULL default '',
  pack_price varchar(255) NOT NULL default '',
  pack_setup varchar(255) NOT NULL default '',
  pack_cost varchar(11) NOT NULL default '0',
  pack_comments mediumtext,
  pack_status tinyint(1) NOT NULL default '0',
  pack_display tinyint(1) NOT NULL default '0',
  email_override tinyint(1) NOT NULL default '0',
  email_id tinyint(1) NOT NULL default '0',
  pack_stamp int(11) NOT NULL default '0',
  PRIMARY KEY (pack_id)
) TYPE=MyISAM")) { echo "<li>--> <font color=blue>package_type <b>OK</b></font>"; } else { $stop=1;  echo "<li> --> <font color=red>package_type <b>NOT OK</b></font>"; }


if ($drop_tables) mysql_query("DROP TABLE IF EXISTS support_desk");
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


if ($drop_tables) mysql_query("DROP TABLE IF EXISTS support_log");
if (mysql_query("CREATE TABLE support_log (
  log_id int(11) NOT NULL auto_increment,
  call_id int(11) NOT NULL default '0',
  log_event mediumtext NOT NULL,
  call_technician int(11) NOT NULL default '0',
  log_stamp int(11) NOT NULL default '0',
  PRIMARY KEY (log_id)
) TYPE=MyISAM")) { echo "<li>--> <font color=blue>support_log <b>OK</b></font>"; } else { $stop=1;  echo "<li> --> <font color=red>support_log <b>NOT OK</b></font>"; }


if ($drop_tables) mysql_query("DROP TABLE IF EXISTS tld_config");
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


if ($drop_tables) mysql_query("DROP TABLE IF EXISTS todo_list");
if (mysql_query("CREATE TABLE todo_list (
  todo_id int(11) NOT NULL auto_increment,
  todo_title varchar(100) NOT NULL default '',
  todo_desc mediumtext NOT NULL,
  admin_id int(11) NOT NULL default '0',
  todo_status varchar(25) NOT NULL default '',
  todo_due int(11) NOT NULL default '0',
  todo_stamp int(11) NOT NULL default '0',
  PRIMARY KEY (todo_id)
) TYPE=MyISAM")) { echo "<li>--> <font color=blue>todo_list <b>OK</b></font>"; } else { $stop=1;  echo "<li> --> <font color=red>todo_list <b>NOT OK</b></font>"; }


if ($drop_tables) mysql_query("DROP TABLE IF EXISTS whois_stats");
if (mysql_query("CREATE TABLE whois_stats (
  ws_id int(11) NOT NULL auto_increment,
  ws_domain varchar(255) default NULL,
  ws_qty int(11) default NULL,
  ws_from varchar(25) default NULL,
  ws_stamp int(11) default NULL,
  PRIMARY KEY (ws_id)
) TYPE=MyISAM")) { echo "<li>--> <font color=blue>whois_stats <b>OK</b></font>"; } else { $stop=1;  echo "<li> --> <font color=red>whois_stats <b>NOT OK</b></font>"; }

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
<font color=red>There was an error creating the ModernBill tables.
If you are reinstalling the tables over an existing database, please select the "drop tables" option and run this script again.
NOTE: All data will be lost!</font>
<? } else { ?>
<font color=blue>All tables created successfully!</font>
<? } ?>
</b></center>
<br>