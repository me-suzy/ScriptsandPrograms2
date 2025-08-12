# Create Your GetPaid MySQL-Dump
# version 5.5.1
# http://www.createyourgetpaid.com
#
# Host: createyourgetpaid.com
# Generation Time: Oct 25, 2003 at 03:30 PM
# Server version: 4.0.15
# PHP Version: 4.3.3
#
# Database : `cygp`
#
# --------------------------------------------------------

#
# Table structure for table `actions`
#

DROP TABLE IF EXISTS `actions`;
CREATE TABLE `actions` (
  `id` int(11) NOT NULL auto_increment,
  `uid` int(11) NOT NULL default '0',
  `aid` int(11) NOT NULL default '0',
  `type` enum('emails','clicks','signup','lead','sale','credits','debits','points','dpoints','transfer_to','transfer_from','bubble_to','bubble_from','ht_won','ht_lost','payout','refund','deposit') NOT NULL default 'credits',
  `c_type` enum('unknown','cash','points') NOT NULL default 'unknown',
  `credits` float NOT NULL default '0',
  `dateStamp` int(22) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `uid` (`uid`),
  KEY `aid` (`aid`),
  KEY `dateStamp` (`dateStamp`)
) TYPE=MyISAM AUTO_INCREMENT=307 ;

# --------------------------------------------------------

#
# Table structure for table `ad_orders`
#

DROP TABLE IF EXISTS `ad_orders`;
CREATE TABLE `ad_orders` (
  `id` int(11) NOT NULL auto_increment,
  `package` int(11) NOT NULL default '0',
  `method` enum('unknown','paypal','egold','moneybookers','cc','account') NOT NULL default 'unknown',
  `endtotal` float NOT NULL default '0',
  `fullname` varchar(132) NOT NULL default '',
  `address` varchar(132) NOT NULL default '',
  `zipcode` varchar(132) NOT NULL default '',
  `city` varchar(132) NOT NULL default '',
  `country` varchar(132) NOT NULL default '',
  `email` varchar(132) NOT NULL default '',
  `ad_url` varchar(255) NOT NULL default '',
  `ad_title` varchar(132) NOT NULL default '',
  `ad_text` text NOT NULL,
  `comments` text NOT NULL,
  `referer` int(11) NOT NULL default '0',
  `billdate` int(22) NOT NULL default '0',
  `payment_acct` varchar(132) NOT NULL default '',
  `payment_date` int(22) NOT NULL default '0',
  `payment_id` varchar(66) NOT NULL default '',
  PRIMARY KEY  (`id`),
  KEY `package` (`package`),
  KEY `method` (`method`),
  KEY `billdate` (`billdate`)
) TYPE=MyISAM AUTO_INCREMENT=1 ;

# --------------------------------------------------------

#
# Table structure for table `ad_packages`
#

DROP TABLE IF EXISTS `ad_packages`;
CREATE TABLE `ad_packages` (
  `id` int(111) NOT NULL auto_increment,
  `title` varchar(132) NOT NULL default '',
  `price` float NOT NULL default '0',
  `type` varchar(22) NOT NULL default '',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM AUTO_INCREMENT=39 ;

# --------------------------------------------------------

#
# Table structure for table `ads`
#

DROP TABLE IF EXISTS `ads`;
CREATE TABLE `ads` (
  `id` int(11) NOT NULL auto_increment,
  `aid` int(11) NOT NULL default '1',
  `name` varchar(66) NOT NULL default '',
  `path` varchar(132) NOT NULL default '',
  `url` varchar(255) NOT NULL default '',
  `alt` varchar(132) NOT NULL default '',
  `clicks` int(11) NOT NULL default '0',
  `views` int(11) NOT NULL default '0',
  `type` enum('clicks','views') NOT NULL default 'clicks',
  `quantity` int(11) NOT NULL default '0',
  `jscode` text NOT NULL,
  `b_type` enum('img','js') NOT NULL default 'img',
  `active` enum('yes','no') NOT NULL default 'no',
  PRIMARY KEY  (`id`),
  KEY `aid` (`aid`)
) TYPE=MyISAM AUTO_INCREMENT=13 ;

# --------------------------------------------------------

#
# Table structure for table `blocklist`
#

DROP TABLE IF EXISTS `blocklist`;
CREATE TABLE `blocklist` (
  `email` text NOT NULL,
  `remote_addr` text NOT NULL,
  `payment_account` text NOT NULL
) TYPE=MyISAM;

# --------------------------------------------------------

#
# Table structure for table `bubble`
#

DROP TABLE IF EXISTS `bubble`;
CREATE TABLE `bubble` (
  `id` int(11) NOT NULL auto_increment,
  `uid` int(11) NOT NULL default '0',
  `spend` float NOT NULL default '0',
  `description` varchar(255) NOT NULL default '',
  `link` varchar(255) NOT NULL default '',
  `highlight` enum('0','1') NOT NULL default '0',
  `cycled` enum('0','1') NOT NULL default '0',
  `dateStamp` int(22) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `uid` (`uid`),
  KEY `cycled` (`cycled`),
  KEY `dateStamp` (`dateStamp`)
) TYPE=MyISAM AUTO_INCREMENT=144 ;

# --------------------------------------------------------

#
# Table structure for table `cheaters`
#

DROP TABLE IF EXISTS `cheaters`;
CREATE TABLE `cheaters` (
  `id` int(11) NOT NULL auto_increment,
  `uid` int(11) NOT NULL default '0',
  `email` varchar(255) NOT NULL default '',
  `payment_account` varchar(255) NOT NULL default '',
  `remote_addr` varchar(22) NOT NULL default '000.000.000.000',
  `count` int(11) NOT NULL default '1',
  `dateStamp` int(22) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `dateStamp` (`dateStamp`)
) TYPE=MyISAM AUTO_INCREMENT=1 ;

# --------------------------------------------------------

#
# Table structure for table `config`
#

DROP TABLE IF EXISTS `config`;
CREATE TABLE `config` (
  `site_title` varchar(132) NOT NULL default '',
  `site_email` varchar(132) NOT NULL default '',
  `site_language` varchar(132) NOT NULL default 'english',
  `site_datestamp` varchar(66) NOT NULL default 'm/d/Y',
  `site_cleanpm` int(11) NOT NULL default '604800',
  `site_cleanpc` int(11) NOT NULL default '604800',
  `site_cleansession` int(11) NOT NULL default '1440',
  `site_session` int(11) NOT NULL default '43200',
  `site_sessionlength` int(11) NOT NULL default '32',
  `site_statistics` enum('YES','NO') NOT NULL default 'YES',
  `site_usersonline` enum('YES','NO') NOT NULL default 'YES',
  `site_userstimeout` int(11) NOT NULL default '600',
  `site_crontest` enum('YES','NO') NOT NULL default 'NO',
  `site_logs` enum('YES','NO') NOT NULL default 'YES',
  `site_maintenance` enum('YES','NO') NOT NULL default 'NO',
  `cron_backup` enum('YES','NO') NOT NULL default 'NO',
  `cron_backuptime` int(11) NOT NULL default '24',
  `cron_lastbackup` int(22) NOT NULL default '0',
  `cron_apretry` enum('YES','NO') NOT NULL default 'NO',
  `cron_apretrylimit` int(11) NOT NULL default '50',
  `logs_apmin` float NOT NULL default '7.5',
  `member_signupbonus` float NOT NULL default '5',
  `member_logincookie` int(11) NOT NULL default '86400',
  `member_destroytime` int(11) NOT NULL default '604800',
  `member_emailrefresh` int(11) NOT NULL default '20',
  `member_clickrefresh` int(11) NOT NULL default '20',
  `member_interests` text NOT NULL,
  `member_additional` text NOT NULL,
  `member_vaclength` int(11) NOT NULL default '2678400',
  `member_latestact` int(11) NOT NULL default '10',
  `member_latestactpp` int(11) NOT NULL default '10',
  `member_signupspp` int(11) NOT NULL default '10',
  `member_points` enum('YES','NO') NOT NULL default 'NO',
  `member_trackwait` enum('YES','NO') NOT NULL default 'YES',
  `member_activation` enum('YES','NO') NOT NULL default 'YES',
  `member_ct` enum('YES','NO') NOT NULL default 'NO',
  `member_ap` enum('YES','NO') NOT NULL default 'NO',
  `member_payoutmemo` varchar(255) NOT NULL default '',
  `member_sendmail` int(11) NOT NULL default '30',
  `member_delete` int(11) NOT NULL default '60',
  `member_deactivate` int(11) NOT NULL default '0',
  `member_transfer` enum('YES','NO') NOT NULL default 'NO',
  `member_transferfee` int(11) NOT NULL default '5',
  `member_tcenable` enum('YES','NO') NOT NULL default 'YES',
  `member_tcratio` int(11) NOT NULL default '10',
  `member_tcdebit` float NOT NULL default '0',
  `member_tcdelete` enum('YES','NO') NOT NULL default 'NO',
  `member_tcinactivate` enum('YES','NO') NOT NULL default 'NO',
  `startpage_maxclicks` int(11) NOT NULL default '5',
  `startpage_maxemails` int(11) NOT NULL default '5',
  `email_paidmail` varchar(66) NOT NULL default '',
  `email_signup` varchar(66) NOT NULL default '',
  `email_advertise` varchar(132) NOT NULL default '',
  `email_contact` varchar(132) NOT NULL default '',
  `email_getgold` varchar(132) NOT NULL default '',
  `email_mailer` enum('php','smtp') NOT NULL default 'php',
  `email_smtphost` varchar(66) NOT NULL default '',
  `email_smtpuser` varchar(66) NOT NULL default '',
  `email_smtppass` varchar(66) NOT NULL default '',
  `email_background` enum('YES','NO') NOT NULL default 'NO',
  `lead_checkemail` enum('YES','NO') NOT NULL default 'YES',
  `lead_checkip` enum('YES','NO') NOT NULL default 'YES',
  `signup_checkemail` enum('YES','NO') NOT NULL default 'YES',
  `signup_checkbank` enum('YES','NO') NOT NULL default 'YES',
  `signup_checkip` enum('YES','NO') NOT NULL default 'YES',
  `signup_monitorip` enum('YES','NO') NOT NULL default 'YES',
  `signup_hashlength` int(11) NOT NULL default '12',
  `referral_type` varchar(132) NOT NULL default 'PERCENTAGE',
  `referral_levels` varchar(132) NOT NULL default '5|25|20|15|10|5',
  `referral_loggedin` int(11) NOT NULL default '0',
  `referral_earned` float NOT NULL default '0',
  `referral_within` int(11) NOT NULL default '0',
  `referral_movetier` enum('YES','NO') NOT NULL default 'YES',
  `error_handling` enum('SHOW','HIDE') NOT NULL default 'HIDE',
  `error_email` varchar(255) NOT NULL default '',
  `ct_top` int(11) NOT NULL default '10',
  `ct_exclude` text NOT NULL,
  `ap_accountid` int(11) NOT NULL default '0',
  `ap_passphrase` varchar(66) NOT NULL default '',
  `ap_retry` enum('YES','NO') NOT NULL default 'NO',
  `ap_wtdbalance` enum('1','2','3','4') NOT NULL default '1',
  `ap_wtdinvalid` enum('1','2') NOT NULL default '1',
  `ap_ads` enum('0','1') NOT NULL default '0',
  `ap_acctegold` int(11) NOT NULL default '0',
  `ap_acctmoneybookers` varchar(132) NOT NULL default '',
  `ap_acctpaypal` varchar(132) NOT NULL default '',
  `ap_hashegold` varchar(66) NOT NULL default '',
  `ap_hashmoneybookers` varchar(66) NOT NULL default '',
  `ap_deposit` enum('YES','NO') NOT NULL default 'NO',
  `ap_dacctegold` int(11) NOT NULL default '0',
  `ap_dacctmoneybookers` varchar(255) NOT NULL default '',
  `ap_dacctpaypal` varchar(255) NOT NULL default '',
  `ap_dhashegold` varchar(66) NOT NULL default '',
  `ap_dhashmoneybookers` varchar(66) NOT NULL default '',
  `ap_dmin` float NOT NULL default '0.01',
  `ap_dmax` float NOT NULL default '100',
  `ap_dfee` int(11) NOT NULL default '5',
  `bubble_percent` int(11) NOT NULL default '150',
  `bubble_min` float NOT NULL default '0.01',
  `bubble_max` float NOT NULL default '5',
  `bubble_reset` int(11) NOT NULL default '72',
  `bubble_cash` float NOT NULL default '0',
  `bubble_fee` int(11) NOT NULL default '5',
  `bubble_highlight` float NOT NULL default '0.5',
  `bubble_earned` float NOT NULL default '0',
  `ht_min` float NOT NULL default '0.01',
  `ht_max` float NOT NULL default '2',
  `ht_win` int(11) NOT NULL default '50',
  `ht_fee` int(11) NOT NULL default '5',
  `ht_percent` int(11) NOT NULL default '200',
  `ht_wins` int(11) NOT NULL default '0',
  `ht_losses` int(11) NOT NULL default '0',
  `ht_mwins` float NOT NULL default '0',
  `ht_mlosses` float NOT NULL default '0',
  `turing_enabled` enum('YES','NO') NOT NULL default 'NO',
  `turing_numbers` int(11) NOT NULL default '4',
  `turing_pixels` enum('YES','NO') NOT NULL default 'YES',
  `turing_blur` enum('YES','NO') NOT NULL default 'YES',
  `turing_font` text NOT NULL,
  `addon_ct` enum('0','1') NOT NULL default '0',
  `addon_tf` enum('0','1') NOT NULL default '0',
  `addon_ap` enum('0','1') NOT NULL default '0',
  `addon_bubble` enum('0','1') NOT NULL default '0',
  `addon_ht` enum('0','1') NOT NULL default '0',
  `addon_turing` enum('0','1') NOT NULL default '0',
  `addon_scratch` enum('0','1') NOT NULL default '0',
  `admin_currency` varchar(11) NOT NULL default '$',
  `admin_lastlogin` text NOT NULL,
  `admin_lockmail` int(22) NOT NULL default '0',
  `license` enum('false','true') NOT NULL default 'false',
  `cronjobs` enum('YES','NO') NOT NULL default 'NO'
) TYPE=MyISAM;

# --------------------------------------------------------

#
# Table structure for table `deposits`
#

DROP TABLE IF EXISTS `deposits`;
CREATE TABLE `deposits` (
  `id` int(11) NOT NULL auto_increment,
  `uid` int(11) NOT NULL default '0',
  `method` enum('paypal','egold','moneybookers','cc') NOT NULL default 'egold',
  `amount` float NOT NULL default '0',
  `payment_acct` varchar(255) NOT NULL default '',
  `payment_date` int(22) NOT NULL default '0',
  `payment_id` varchar(66) NOT NULL default '',
  `dateStamp` int(22) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `uid` (`uid`),
  KEY `method` (`method`),
  KEY `dateStamp` (`dateStamp`)
) TYPE=MyISAM AUTO_INCREMENT=1 ;

# --------------------------------------------------------

#
# Table structure for table `lead_data`
#

DROP TABLE IF EXISTS `lead_data`;
CREATE TABLE `lead_data` (
  `id` int(11) NOT NULL auto_increment,
  `lid` int(11) NOT NULL default '0',
  `uid` int(11) NOT NULL default '0',
  `formdata` text NOT NULL,
  `active` varchar(66) NOT NULL default '',
  `remote_addr` varchar(22) NOT NULL default '000.000.000.000',
  `dateStamp` int(22) NOT NULL default '0',
  `status` enum('checked','unchecked') NOT NULL default 'unchecked',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM AUTO_INCREMENT=1 ;

# --------------------------------------------------------

#
# Table structure for table `leads`
#

DROP TABLE IF EXISTS `leads`;
CREATE TABLE `leads` (
  `id` int(11) NOT NULL auto_increment,
  `aid` int(11) NOT NULL default '1',
  `name` varchar(132) NOT NULL default '',
  `description` text NOT NULL,
  `description2` text NOT NULL,
  `html` text NOT NULL,
  `url` varchar(255) NOT NULL default 'http://',
  `type` enum('form','url') NOT NULL default 'form',
  `conf_mail` enum('yes','no') NOT NULL default 'no',
  `max` int(11) NOT NULL default '0',
  `c_type` enum('cash','points') NOT NULL default 'cash',
  `credits` float NOT NULL default '0',
  `active` enum('yes','no') NOT NULL default 'no',
  PRIMARY KEY  (`id`),
  KEY `aid` (`aid`)
) TYPE=MyISAM AUTO_INCREMENT=2 ;

# --------------------------------------------------------

#
# Table structure for table `login_logs`
#

DROP TABLE IF EXISTS `login_logs`;
CREATE TABLE `login_logs` (
  `uid` int(11) NOT NULL default '0',
  `browser` varchar(132) NOT NULL default '',
  `remote_addr` varchar(22) NOT NULL default '000.000.000.000',
  `dateStamp` int(22) NOT NULL default '0',
  UNIQUE KEY `uid` (`uid`)
) TYPE=MyISAM;

# --------------------------------------------------------

#
# Table structure for table `massmailer`
#

DROP TABLE IF EXISTS `massmailer`;
CREATE TABLE `massmailer` (
  `id` int(11) NOT NULL auto_increment,
  `mid` int(11) NOT NULL default '0',
  `uid` int(11) NOT NULL default '0',
  `email` varchar(255) NOT NULL default '',
  `fname` varchar(66) NOT NULL default '',
  `sname` varchar(66) NOT NULL default '',
  `turingnr` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `mid` (`mid`),
  KEY `uid` (`uid`),
  KEY `turingnr` (`turingnr`)
) TYPE=MyISAM AUTO_INCREMENT=73 ;

# --------------------------------------------------------

#
# Table structure for table `memberships`
#

DROP TABLE IF EXISTS `memberships`;
CREATE TABLE `memberships` (
  `id` int(11) NOT NULL auto_increment,
  `title` varchar(132) NOT NULL default '',
  `weight` int(11) NOT NULL default '1',
  `ref_levels` varchar(132) NOT NULL default '',
  `advantages` text NOT NULL,
  `price` float NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `weight` (`weight`),
  KEY `ref_levels` (`ref_levels`)
) TYPE=MyISAM AUTO_INCREMENT=4 ;

# --------------------------------------------------------

#
# Table structure for table `news`
#

DROP TABLE IF EXISTS `news`;
CREATE TABLE `news` (
  `id` int(11) NOT NULL auto_increment,
  `dateStamp` int(22) NOT NULL default '0',
  `title` varchar(132) NOT NULL default '',
  `text` text NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `dateStamp` (`dateStamp`)
) TYPE=MyISAM AUTO_INCREMENT=2 ;

# --------------------------------------------------------

#
# Table structure for table `optimize_gain`
#

DROP TABLE IF EXISTS `optimize_gain`;
CREATE TABLE `optimize_gain` (
  `gain` decimal(10,3) default NULL
) TYPE=MyISAM;

# --------------------------------------------------------

#
# Table structure for table `paid_clicks`
#

DROP TABLE IF EXISTS `paid_clicks`;
CREATE TABLE `paid_clicks` (
  `id` int(11) NOT NULL auto_increment,
  `aid` int(11) NOT NULL default '1',
  `title` varchar(132) NOT NULL default '',
  `text` text NOT NULL,
  `banner` varchar(255) NOT NULL default '',
  `url` varchar(255) NOT NULL default '',
  `clicks` int(11) NOT NULL default '0',
  `sent` int(11) NOT NULL default '0',
  `timer` int(11) NOT NULL default '0',
  `type` enum('banner','text') NOT NULL default 'text',
  `c_type` enum('cash','points') NOT NULL default 'cash',
  `credits` float NOT NULL default '0',
  `ref_earnings` enum('yes','no') NOT NULL default 'yes',
  `active` enum('yes','no') NOT NULL default 'no',
  PRIMARY KEY  (`id`),
  KEY `aid` (`aid`)
) TYPE=MyISAM AUTO_INCREMENT=6 ;

# --------------------------------------------------------

#
# Table structure for table `paid_emails`
#

DROP TABLE IF EXISTS `paid_emails`;
CREATE TABLE `paid_emails` (
  `id` int(11) NOT NULL auto_increment,
  `aid` int(11) NOT NULL default '1',
  `subject` varchar(132) NOT NULL default '',
  `url` varchar(255) NOT NULL default '',
  `text` text NOT NULL,
  `texttype` enum('plain','html','auto') NOT NULL default 'auto',
  `description` text NOT NULL,
  `clicks` int(11) NOT NULL default '0',
  `sent` int(11) NOT NULL default '0',
  `type` enum('paid','unpaid') NOT NULL default 'paid',
  `timer` int(11) NOT NULL default '0',
  `priority` enum('1','3','5') NOT NULL default '3',
  `c_type` enum('cash','points') NOT NULL default 'cash',
  `credits` float NOT NULL default '0',
  `ref_earnings` enum('yes','no') NOT NULL default 'yes',
  `active` enum('yes','no') NOT NULL default 'no',
  PRIMARY KEY  (`id`),
  KEY `aid` (`aid`)
) TYPE=MyISAM AUTO_INCREMENT=12 ;

# --------------------------------------------------------

#
# Table structure for table `paid_signups`
#

DROP TABLE IF EXISTS `paid_signups`;
CREATE TABLE `paid_signups` (
  `id` int(11) NOT NULL auto_increment,
  `aid` int(11) NOT NULL default '1',
  `title` varchar(132) NOT NULL default '',
  `text` text NOT NULL,
  `url` varchar(255) NOT NULL default '',
  `c_type` enum('cash','points') NOT NULL default 'cash',
  `credits` float NOT NULL default '0',
  `max` int(11) NOT NULL default '0',
  `mail` enum('0','1') NOT NULL default '0',
  `active` enum('yes','no') NOT NULL default 'no',
  PRIMARY KEY  (`id`),
  KEY `aid` (`aid`)
) TYPE=MyISAM AUTO_INCREMENT=3 ;

# --------------------------------------------------------

#
# Table structure for table `payment_methods`
#

DROP TABLE IF EXISTS `payment_methods`;
CREATE TABLE `payment_methods` (
  `id` int(11) NOT NULL auto_increment,
  `method` varchar(132) NOT NULL default '',
  `fee` float NOT NULL default '0',
  `minimum` float NOT NULL default '0',
  `active` enum('yes','no') NOT NULL default 'no',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM AUTO_INCREMENT=4 ;

# --------------------------------------------------------

#
# Table structure for table `payments`
#

DROP TABLE IF EXISTS `payments`;
CREATE TABLE `payments` (
  `id` int(11) NOT NULL auto_increment,
  `uid` int(11) NOT NULL default '0',
  `credits` float NOT NULL default '0',
  `method` int(11) NOT NULL default '0',
  `account` varchar(132) NOT NULL default '',
  `dateStamp` int(22) NOT NULL default '0',
  `paid` enum('yes','no') NOT NULL default 'no',
  `batchnr` varchar(132) NOT NULL default '',
  `status` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`id`),
  KEY `uid` (`uid`),
  KEY `method` (`method`)
) TYPE=MyISAM AUTO_INCREMENT=2 ;

# --------------------------------------------------------

#
# Table structure for table `received_signups`
#

DROP TABLE IF EXISTS `received_signups`;
CREATE TABLE `received_signups` (
  `id` int(11) NOT NULL auto_increment,
  `uid` int(11) NOT NULL default '0',
  `sid` int(11) NOT NULL default '0',
  `confirmation` text NOT NULL,
  `credited` enum('yes','no') NOT NULL default 'no',
  `checked` enum('0','1') NOT NULL default '0',
  `dateStamp` int(22) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM AUTO_INCREMENT=1 ;

# --------------------------------------------------------

#
# Table structure for table `redempts`
#

DROP TABLE IF EXISTS `redempts`;
CREATE TABLE `redempts` (
  `id` int(11) NOT NULL auto_increment,
  `item` varchar(255) NOT NULL default '',
  `c_type` enum('cash','points') NOT NULL default 'cash',
  `credits` float NOT NULL default '0',
  `description` text NOT NULL,
  `weights` text NOT NULL,
  `type` varchar(22) NOT NULL default '',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM AUTO_INCREMENT=5 ;

# --------------------------------------------------------

#
# Table structure for table `refs`
#

DROP TABLE IF EXISTS `refs`;
CREATE TABLE `refs` (
  `id` int(11) NOT NULL auto_increment,
  `uid` int(11) NOT NULL default '0',
  `rid` int(11) NOT NULL default '0',
  `status` enum('0','1') NOT NULL default '0',
  `ct` enum('0','1') NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `uid` (`uid`),
  KEY `rid` (`rid`),
  KEY `status` (`status`),
  KEY `ct` (`ct`)
) TYPE=MyISAM AUTO_INCREMENT=1 ;

# --------------------------------------------------------

#
# Table structure for table `replies`
#

DROP TABLE IF EXISTS `replies`;
CREATE TABLE `replies` (
  `id` int(11) NOT NULL auto_increment,
  `nid` int(11) NOT NULL default '0',
  `dateStamp` int(22) NOT NULL default '0',
  `author` varchar(132) NOT NULL default '',
  `text` text NOT NULL,
  `remote_addr` varchar(22) NOT NULL default '000.000.000.000',
  PRIMARY KEY  (`id`),
  KEY `nid` (`nid`),
  KEY `dateStamp` (`dateStamp`)
) TYPE=MyISAM AUTO_INCREMENT=1 ;

# --------------------------------------------------------

#
# Table structure for table `s_cats`
#

DROP TABLE IF EXISTS `s_cats`;
CREATE TABLE `s_cats` (
  `id` int(11) NOT NULL auto_increment,
  `category` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM AUTO_INCREMENT=7 ;

# --------------------------------------------------------

#
# Table structure for table `s_posts`
#

DROP TABLE IF EXISTS `s_posts`;
CREATE TABLE `s_posts` (
  `id` int(11) NOT NULL auto_increment,
  `tid` int(11) NOT NULL default '0',
  `message` text NOT NULL,
  `type` enum('from','to') NOT NULL default 'from',
  `dateStamp` int(22) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `tid` (`tid`,`dateStamp`)
) TYPE=MyISAM AUTO_INCREMENT=3 ;

# --------------------------------------------------------

#
# Table structure for table `s_tickets`
#

DROP TABLE IF EXISTS `s_tickets`;
CREATE TABLE `s_tickets` (
  `id` int(11) NOT NULL auto_increment,
  `uid` int(11) NOT NULL default '0',
  `cid` int(11) NOT NULL default '0',
  `subject` varchar(255) NOT NULL default '',
  `text` text NOT NULL,
  `email` enum('YES','NO') NOT NULL default 'NO',
  `urgency` enum('1','2','3','4') NOT NULL default '2',
  `resolved` enum('0','1') NOT NULL default '0',
  `status` enum('open','pending','closed') NOT NULL default 'open',
  `dateStamp` int(22) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `status` (`status`),
  KEY `uid` (`uid`),
  KEY `cid` (`cid`),
  KEY `urgency` (`urgency`),
  KEY `resolved` (`resolved`),
  KEY `dateStamp` (`dateStamp`)
) TYPE=MyISAM AUTO_INCREMENT=9 ;

# --------------------------------------------------------

#
# Table structure for table `sale_data`
#

DROP TABLE IF EXISTS `sale_data`;
CREATE TABLE `sale_data` (
  `id` int(11) NOT NULL auto_increment,
  `sid` int(11) NOT NULL default '0',
  `uid` int(11) NOT NULL default '0',
  `formdata` text NOT NULL,
  `remote_addr` varchar(22) NOT NULL default '000.000.000.000',
  `dateStamp` int(22) NOT NULL default '0',
  `status` enum('checked','unchecked') NOT NULL default 'unchecked',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM AUTO_INCREMENT=1 ;

# --------------------------------------------------------

#
# Table structure for table `sales`
#

DROP TABLE IF EXISTS `sales`;
CREATE TABLE `sales` (
  `id` int(11) NOT NULL auto_increment,
  `aid` int(11) NOT NULL default '1',
  `name` varchar(132) NOT NULL default '',
  `description` text NOT NULL,
  `description2` text NOT NULL,
  `html` text NOT NULL,
  `url` varchar(255) NOT NULL default 'http://',
  `type` enum('form','url') NOT NULL default 'form',
  `c_type` enum('cash','points') NOT NULL default 'cash',
  `credits` float NOT NULL default '0',
  `max` int(11) NOT NULL default '0',
  `active` enum('yes','no') NOT NULL default 'no',
  PRIMARY KEY  (`id`),
  KEY `aid` (`aid`)
) TYPE=MyISAM AUTO_INCREMENT=3 ;

# --------------------------------------------------------

#
# Table structure for table `sent_clicks`
#

DROP TABLE IF EXISTS `sent_clicks`;
CREATE TABLE `sent_clicks` (
  `id` int(11) NOT NULL auto_increment,
  `cid` int(11) NOT NULL default '0',
  `uid` int(11) NOT NULL default '0',
  `onClickthru` int(11) NOT NULL default '0',
  `status` enum('normal','locked','unlocked') NOT NULL default 'normal',
  `clickStamp` int(22) NOT NULL default '0',
  `dateStamp` int(22) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `cid` (`cid`),
  KEY `uid` (`uid`),
  KEY `status` (`status`),
  KEY `clickStamp` (`clickStamp`),
  KEY `dateStamp` (`dateStamp`)
) TYPE=MyISAM AUTO_INCREMENT=3 ;

# --------------------------------------------------------

#
# Table structure for table `sent_emails`
#

DROP TABLE IF EXISTS `sent_emails`;
CREATE TABLE `sent_emails` (
  `id` int(11) NOT NULL auto_increment,
  `mid` int(11) NOT NULL default '0',
  `uid` int(11) NOT NULL default '0',
  `status` enum('read','unread') NOT NULL default 'unread',
  `dateStamp` int(22) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `mid` (`mid`),
  KEY `uid` (`uid`),
  KEY `dateStamp` (`dateStamp`)
) TYPE=MyISAM AUTO_INCREMENT=6 ;

# --------------------------------------------------------

#
# Table structure for table `sent_queue`
#

DROP TABLE IF EXISTS `sent_queue`;
CREATE TABLE `sent_queue` (
  `id` int(11) NOT NULL auto_increment,
  `cid` int(11) NOT NULL default '0',
  `queue` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `cid` (`cid`),
  KEY `queue` (`queue`)
) TYPE=MyISAM AUTO_INCREMENT=2 ;

# --------------------------------------------------------

#
# Table structure for table `sessions`
#

DROP TABLE IF EXISTS `sessions`;
CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL default '',
  `lastUpdate` int(11) NOT NULL default '0',
  `data` text,
  PRIMARY KEY  (`id`),
  KEY `lastUpdate` (`lastUpdate`)
) TYPE=MyISAM;

# --------------------------------------------------------

#
# Table structure for table `stats_date`
#

DROP TABLE IF EXISTS `stats_date`;
CREATE TABLE `stats_date` (
  `year` smallint(6) NOT NULL default '0',
  `month` tinyint(4) NOT NULL default '0',
  `date` tinyint(4) NOT NULL default '0',
  `hits` bigint(20) NOT NULL default '0',
  KEY `year` (`year`),
  KEY `month` (`month`),
  KEY `date` (`date`),
  KEY `hits` (`hits`)
) TYPE=MyISAM;

# --------------------------------------------------------

#
# Table structure for table `stats_month`
#

DROP TABLE IF EXISTS `stats_month`;
CREATE TABLE `stats_month` (
  `year` smallint(6) NOT NULL default '0',
  `month` tinyint(4) NOT NULL default '0',
  `hits` bigint(20) NOT NULL default '0',
  KEY `year` (`year`),
  KEY `month` (`month`),
  KEY `hits` (`hits`)
) TYPE=MyISAM;

# --------------------------------------------------------

#
# Table structure for table `stats_year`
#

DROP TABLE IF EXISTS `stats_year`;
CREATE TABLE `stats_year` (
  `year` smallint(6) NOT NULL default '0',
  `hits` bigint(20) NOT NULL default '0',
  KEY `year` (`year`),
  KEY `hits` (`hits`)
) TYPE=MyISAM;

# --------------------------------------------------------

#
# Table structure for table `ticket`
#

DROP TABLE IF EXISTS `ticket`;
CREATE TABLE `ticket` (
  `id` int(11) NOT NULL auto_increment,
  `uspick1` int(5) NOT NULL default '0',
  `uspick2` int(5) NOT NULL default '0',
  `pick1` int(5) NOT NULL default '0',
  `pick2` int(5) NOT NULL default '0',
  `pick3` int(5) NOT NULL default '0',
  `pick4` int(5) NOT NULL default '0',
  `show1` int(1) NOT NULL default '0',
  `show2` int(1) NOT NULL default '0',
  `show3` int(1) NOT NULL default '0',
  `show4` int(1) NOT NULL default '0',
  `played` int(1) NOT NULL default '0',
  `win` int(11) NOT NULL default '0',
  `email` text NOT NULL,
  `dayt` int(2) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM AUTO_INCREMENT=1 ;

# --------------------------------------------------------

#
# Table structure for table `useronline`
#

DROP TABLE IF EXISTS `useronline`;
CREATE TABLE `useronline` (
  `remote_addr` varchar(22) NOT NULL default '000.000.000.000',
  `dateStamp` int(22) NOT NULL default '0',
  UNIQUE KEY `remote_addr` (`remote_addr`),
  KEY `dateStamp` (`dateStamp`)
) TYPE=MyISAM;

# --------------------------------------------------------

#
# Table structure for table `users`
#

DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` int(11) NOT NULL auto_increment,
  `email` varchar(132) NOT NULL default '',
  `password` varchar(66) NOT NULL default '',
  `fname` varchar(66) NOT NULL default '',
  `sname` varchar(66) NOT NULL default '',
  `address` varchar(132) NOT NULL default '',
  `city` varchar(66) NOT NULL default '',
  `state` varchar(66) NOT NULL default '',
  `zipcode` varchar(66) NOT NULL default '',
  `country` varchar(66) NOT NULL default '',
  `gender` enum('male','female') NOT NULL default 'male',
  `birth_month` int(11) NOT NULL default '0',
  `birth_day` int(11) NOT NULL default '0',
  `birth_year` int(11) NOT NULL default '1900',
  `payment_method` int(11) NOT NULL default '0',
  `payment_account` varchar(132) NOT NULL default '',
  `interests` text NOT NULL,
  `additional` text NOT NULL,
  `vacation` int(22) NOT NULL default '0',
  `premium` int(11) NOT NULL default '0',
  `advertiser` enum('yes','no') NOT NULL default 'no',
  `operator` enum('yes','no') NOT NULL default 'no',
  `bonus` float NOT NULL default '0',
  `clickthrus` float NOT NULL default '0',
  `ptc` float NOT NULL default '0',
  `paidgames` float NOT NULL default '0',
  `paidsignups` float NOT NULL default '0',
  `leads_sales` float NOT NULL default '0',
  `games` float NOT NULL default '0',
  `credits` float NOT NULL default '0',
  `debits` float NOT NULL default '0',
  `points` float NOT NULL default '0',
  `dpoints` float NOT NULL default '0',
  `referral_data` text NOT NULL,
  `ref_hits` int(11) NOT NULL default '0',
  `notes` text NOT NULL,
  `sessions` int(11) NOT NULL default '0',
  `lastlogin` int(22) NOT NULL default '0',
  `lastactive` int(22) NOT NULL default '0',
  `sentmail` enum('yes','no') NOT NULL default 'no',
  `active` varchar(66) NOT NULL default 'no',
  `remote_addr` varchar(22) NOT NULL default '000.000.000.000',
  `regdate` int(22) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `email` (`email`),
  KEY `password` (`password`),
  KEY `premium` (`premium`),
  KEY `advertiser` (`advertiser`),
  KEY `active` (`active`),
  KEY `sentmail` (`sentmail`),
  KEY `lastactive` (`lastactive`),
  KEY `lastlogin` (`lastlogin`),
  KEY `country` (`country`),
  KEY `payment_method` (`payment_method`),
  KEY `regdate` (`regdate`)
) TYPE=MyISAM AUTO_INCREMENT=5 ;