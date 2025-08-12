
#
# Table structure for table `additional_info`
#

CREATE TABLE additional_info (
  mem_id int(11) default NULL,
  age int(3) default NULL,
  gender enum('Male','Female') default NULL,
  marital varchar(10) default NULL,
  household char(2) default NULL,
  childrens char(2) default NULL,
  income varchar(7) default NULL,
  housestatus varchar(6) default NULL,
  learning varchar(15) default NULL,
  occupation varchar(6) default NULL,
  vehicles varchar(6) default NULL,
  creditcard enum('yes','no') default NULL,
  spentonline varchar(4) default NULL,
  interests text,
  html_email enum('yes','no') default NULL
) TYPE=MyISAM;
# --------------------------------------------------------

#
# Table structure for table `advt_banner`
#

CREATE TABLE advt_banner (
  ad_id varchar(16) NOT NULL default '0',
  email_id varchar(50) NOT NULL default '',
  name varchar(20) NOT NULL default '',
  title varchar(20) NOT NULL default '',
  imp_purchased int(11) default NULL,
  imp_used int(11) NOT NULL default '0',
  PRIMARY KEY  (ad_id)
) TYPE=MyISAM;
# --------------------------------------------------------

#
# Table structure for table `advt_email`
#

CREATE TABLE advt_email (
  ad_id varchar(16) NOT NULL default '0',
  email_id varchar(50) NOT NULL default '',
  name varchar(20) NOT NULL default '',
  title varchar(20) NOT NULL default '',
  url varchar(150) default NULL,
  amt_perclick float(5,2) NOT NULL default '0.00',
  clicks_recd int(6) NOT NULL default '0',
  type varchar(7) default NULL,
  info text,
  PRIMARY KEY  (ad_id)
) TYPE=MyISAM;
# --------------------------------------------------------

#
# Table structure for table `banner`
#

CREATE TABLE banner (
  banner_id int(5) unsigned NOT NULL auto_increment,
  title varchar(30) default NULL,
  html text,
  user varchar(50) default NULL,
  clicks int(11) default NULL,
  PRIMARY KEY  (banner_id)
) TYPE=MyISAM;
# --------------------------------------------------------

#
# Table structure for table `banner_clicks`
#

CREATE TABLE banner_clicks (
  member_id varchar(50) binary NOT NULL default '''''',
  banner_id int(4) unsigned NOT NULL default '0',
  click_date date NOT NULL default '0000-00-00',
  PRIMARY KEY  (member_id,banner_id,click_date)
) TYPE=MyISAM;
# --------------------------------------------------------

#
# Table structure for table `banner_imp`
#

CREATE TABLE banner_imp (
  banner_id int(4) unsigned NOT NULL auto_increment,
  count int(5) unsigned default '0',
  click_date date NOT NULL default '0000-00-00',
  PRIMARY KEY  (banner_id,click_date)
) TYPE=MyISAM;
# --------------------------------------------------------

#
# Table structure for table `contact_us`
#

CREATE TABLE contact_us (
  email_id varchar(50) NOT NULL default '',
  name varchar(25) NOT NULL default '',
  q_type varchar(100) NOT NULL default '',
  q_desc varchar(100) default NULL,
  question text
) TYPE=MyISAM;
# --------------------------------------------------------

#
# Table structure for table `email_clicks`
#

CREATE TABLE email_clicks (
  ad_id varchar(30) NOT NULL default '0',
  clicks int(11) NOT NULL default '0',
  date date default NULL
) TYPE=MyISAM;
# --------------------------------------------------------

#
# Table structure for table `member_credit`
#

CREATE TABLE member_credit (
  mem_id int(11) default NULL,
  r_credit text,
  credits float(5,2) default NULL,
  c_date date default NULL
) TYPE=MyISAM;
# --------------------------------------------------------

#
# Table structure for table `member_debit`
#

CREATE TABLE member_debit (
  mem_id int(11) default NULL,
  r_debit text,
  debits float(5,2) default NULL,
  d_date date default NULL
) TYPE=MyISAM;
# --------------------------------------------------------

#
# Table structure for table `member_details`
#

CREATE TABLE member_details (
  mem_id int(11) NOT NULL auto_increment,
  email_id varchar(50) NOT NULL default '',
  f_name varchar(25) NOT NULL default '',
  l_name varchar(25) NOT NULL default '',
  address varchar(150) default NULL,
  city varchar(25) default NULL,
  state varchar(25) default NULL,
  zip varchar(15) default NULL,
  country varchar(45) default NULL,
  password varchar(15) default NULL,
  joined_date date default NULL,
  ipadds varchar(25) default NULL,
  status varchar(8) default NULL,
  last_login timestamp(14) NOT NULL,
  PRIMARY KEY  (mem_id)
) TYPE=MyISAM;
# --------------------------------------------------------

#
# Table structure for table `member_earnings`
#

CREATE TABLE member_earnings (
  mem_id int(11) NOT NULL default '0',
  pd_clickthro float(5,2) default '0.00',
  referral_bonus float(5,2) default '0.00',
  credits float(5,2) default '0.00',
  total_debits float(5,2) default '0.00',
  total_referrals int(4) NOT NULL default '0',
  pd_clickban float(5,2) default '0.00',
  PRIMARY KEY  (mem_id)
) TYPE=MyISAM;
# --------------------------------------------------------

#
# Table structure for table `member_referrals`
#

CREATE TABLE member_referrals (
  mem_id int(11) NOT NULL default '0',
  parent_id int(11) NOT NULL default '0'
) TYPE=MyISAM;
# --------------------------------------------------------

#
# Table structure for table `paid_mail`
#

CREATE TABLE paid_mail (
  mem_id int(11) default NULL,
  aff_id varchar(16) default NULL
) TYPE=MyISAM;
# --------------------------------------------------------

#
# Table structure for table `pay_banners`
#

CREATE TABLE pay_banners (
  banner_id int(4) unsigned NOT NULL auto_increment,
  user_id varchar(50) NOT NULL default '',
  type enum('banner','text') NOT NULL default 'banner',
  banner_name varchar(50) NOT NULL default '',
  image_url varchar(255) default NULL,
  link_url varchar(255) NOT NULL default '',
  image_width int(3) unsigned NOT NULL default '0',
  image_height int(3) unsigned NOT NULL default '0',
  text_link longtext,
  click_amount decimal(9,3) NOT NULL default '0.000',
  clicks_remaining int(9) unsigned NOT NULL default '0',
  date_added date NOT NULL default '0000-00-00',
  total_clicks int(9) unsigned default '0',
  PRIMARY KEY  (banner_id)
) TYPE=MyISAM;
# --------------------------------------------------------

#
# Table structure for table `purchase_contact`
#

CREATE TABLE purchase_contact (
  email_id varchar(50) NOT NULL default '',
  ad_type varchar(30) NOT NULL default '',
  cost float(11,2) default NULL,
  info text,
  agree char(1) default NULL
) TYPE=MyISAM;
# --------------------------------------------------------

#
# Table structure for table `purchase_gold`
#

CREATE TABLE purchase_gold (
  email_id varchar(50) NOT NULL default '',
  payment varchar(30) NOT NULL default '',
  info text,
  agree char(1) default NULL
) TYPE=MyISAM;
# --------------------------------------------------------

#
# Table structure for table `redeem_contact`
#

CREATE TABLE redeem_contact (
  email varchar(50) NOT NULL default '',
  amt float(11,2) default NULL,
  PRIMARY KEY  (email)
) TYPE=MyISAM;
# --------------------------------------------------------

#
# Table structure for table `redempt`
#

CREATE TABLE redempt (
  track_id varchar(30) NOT NULL default '',
  item varchar(30) default NULL,
  amt int(11) default NULL,
  r_desc text
) TYPE=MyISAM;

    
