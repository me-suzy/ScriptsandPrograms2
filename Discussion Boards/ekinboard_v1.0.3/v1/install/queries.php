<?PHP

// Create the array's

$query = array();
$update = array();


// Create the tables for the database

$query[0] = "CREATE TABLE ads (
  id int(255) NOT NULL auto_increment,
  ad_type enum('text','banner','script') NOT NULL default 'text',
  ad_location enum('top','bottom') NOT NULL default 'top',
  ad_text_name varchar(255) NOT NULL default '',
  ad_text_href varchar(255) NOT NULL default '',
  ad_text_description text NOT NULL,
  ad_script text NOT NULL,
  ad_banner_img varchar(255) NOT NULL default '',
  ad_banner_href varchar(255) NOT NULL default '',
  PRIMARY KEY  (id)
) TYPE=MyISAM";

$query[1] = "CREATE TABLE categories (
  id int(255) NOT NULL auto_increment,
  name varchar(255) NOT NULL default '',
  level_limit enum('1','2','3') NOT NULL default '1',
  PRIMARY KEY  (id)
) TYPE=MyISAM";

$query[2] = "CREATE TABLE forums (
  id int(255) NOT NULL auto_increment,
  cid int(255) NOT NULL default '0',
  subforum enum('0','1') NOT NULL default '0',
  name varchar(255) NOT NULL default '',
  description varchar(255) NOT NULL default '',
  hidden enum('0','1') NOT NULL default '0',
  protected enum('0','1') NOT NULL default '0',
  password varchar(255) NOT NULL default '',
  news enum('0','1') NOT NULL default '0',
  restricted_level enum('1','2','3') NOT NULL default '1',
  PRIMARY KEY  (id)
) TYPE=MyISAM";

$query[3] = "CREATE TABLE inbox (
  id int(255) NOT NULL auto_increment,
  sender varchar(255) NOT NULL default '',
  sender_id varchar(255) NOT NULL default '',
  reciever_id varchar(255) NOT NULL default '',
  subject varchar(255) NOT NULL default '',
  message text NOT NULL,
  date varchar(255) NOT NULL default '',
  datesort varchar(255) NOT NULL default '',
  message_read enum('0','1') NOT NULL default '0',
  sender_delete enum('0','1') NOT NULL default '0',
  reciever_delete enum('0','1') NOT NULL default '0',
  PRIMARY KEY  (id)
) TYPE=MyISAM";

$query[4] = "CREATE TABLE logs (
  id int(255) NOT NULL auto_increment,
  userid varchar(255) NOT NULL default '',
  timestamp datetime NOT NULL default '0000-00-00 00:00:00',
  content text NOT NULL,
  desc text NOT NULL,
  PRIMARY KEY  (id)
) TYPE=MyISAM";

$query[5] = "CREATE TABLE moderators (
  forum_id int(255) NOT NULL default '0',
  item enum('1','2') NOT NULL default '1',
  item_id int(255) NOT NULL default '0'
) TYPE=MyISAM";

$query[6] = "CREATE TABLE online (
  id varchar(255) NOT NULL default '',
  username varchar(255) NOT NULL default '',
  timestamp varchar(255) NOT NULL default '',
  guest enum('0','1') NOT NULL default '0',
  ip text NOT NULL,
  viewtopic int(255) NOT NULL default '0',
  viewforum int(255) NOT NULL default '0',
  posting enum('0','1') NOT NULL default '0',
  isonline enum('0','1') NOT NULL default '1',
  online_date varchar(255) NOT NULL default '',
  page varchar(255) NOT NULL default '',
  PRIMARY KEY  (id)
) TYPE=MyISAM";

$query[7] = "CREATE TABLE poll_choices (
  id int(255) NOT NULL auto_increment,
  pid int(255) NOT NULL default '0',
  choice varchar(255) NOT NULL default '',
  PRIMARY KEY  (id)
) TYPE=MyISAM";

$query[8] = "CREATE TABLE poll_votes (
  id int(255) NOT NULL auto_increment,
  pid int(255) NOT NULL default '0',
  choice_id int(255) NOT NULL default '0',
  voter int(255) NOT NULL default '0',
  PRIMARY KEY  (id)
) TYPE=MyISAM";

$query[9] = "CREATE TABLE read (
  topic_id int(255) NOT NULL default '0',
  user_id int(255) NOT NULL default '0',
  KEY topic_id (topic_id,user_id)
) TYPE=MyISAM";

$query[10] = "CREATE TABLE replies (
  id int(255) NOT NULL auto_increment,
  tid varchar(255) NOT NULL default '0',
  message text NOT NULL,
  poster varchar(255) NOT NULL default '',
  date varchar(255) default NULL,
  datesort varchar(255) NOT NULL default '',
  PRIMARY KEY  (id),
  FULLTEXT KEY message (message)
) TYPE=MyISAM";

$query[11] = "CREATE TABLE rules (
  id int(255) NOT NULL auto_increment,
  rule_number int(255) NOT NULL default '0',
  rule_content text NOT NULL,
  PRIMARY KEY  (id)
) TYPE=MyISAM";

$query[12] = "CREATE TABLE settings (
  id int(255) NOT NULL auto_increment,
  name varchar(255) NOT NULL default '',
  value text NOT NULL,
  PRIMARY KEY  (id)
) TYPE=MyISAM";

$query[13] = "CREATE TABLE topics (
  id int(255) NOT NULL auto_increment,
  fid int(255) NOT NULL default '0',
  poll enum('0','1') NOT NULL default '0',
  poll_question varchar(255) NOT NULL default '',
  title varchar(255) NOT NULL default '',
  description varchar(255) NOT NULL default '',
  message text NOT NULL,
  sticky enum('0','1') NOT NULL default '0',
  locked enum('0','1') NOT NULL default '0',
  protected_level enum('1','2','3') NOT NULL default '1',
  poster varchar(255) NOT NULL default '',
  date varchar(255) NOT NULL default '',
  views varchar(255) NOT NULL default '0',
  protected enum('0','1') NOT NULL default '0',
  last_post varchar(255) NOT NULL default '',
  datesort varchar(255) NOT NULL default '',
  allow_replies enum('0','1') NOT NULL default '1',
  attch_name varchar(255) NOT NULL default '',
  attch_size varchar(255) NOT NULL default '',
  attch_type varchar(255) NOT NULL default '',
  PRIMARY KEY  (id),
  FULLTEXT KEY message (title,message)
) TYPE=MyISAM";

$query[14] = "CREATE TABLE users (
  id int(255) NOT NULL auto_increment,
  first_name varchar(255) NOT NULL default '',
  display_name varchar(255) NOT NULL default '',
  email varchar(255) NOT NULL default '',
  new_email varchar(55) NOT NULL default '',
  email_code int(11) NOT NULL default '0',
  username varchar(255) NOT NULL default '',
  password varchar(255) NOT NULL default '',
  signup_date date NOT NULL default '0000-00-00',
  lastlogin date NOT NULL default '0000-00-00',
  activated enum('0','1') NOT NULL default '0',
  level enum('1','2','3') NOT NULL default '1',
  sig text NOT NULL,
  title varchar(255) NOT NULL default '',
  website_url varchar(255) NOT NULL default '',
  aim varchar(255) NOT NULL default '',
  msn varchar(255) NOT NULL default '',
  yahoo varchar(255) NOT NULL default '',
  icq varchar(255) NOT NULL default '',
  warning enum('0','1','2','3','4') NOT NULL default '0',
  activation_code varchar(255) NOT NULL default '',
  skin varchar(255) NOT NULL default 'default',
  avatar varchar(255) NOT NULL default '',
  avatar_alt varchar(255) NOT NULL default '',
  ava_uploaded enum('0','1') NOT NULL default '0',
  forum_mod varchar(255) NOT NULL default '',
  banned enum('0','1') NOT NULL default '0',
  banned_reason text NOT NULL,
  group_id int(255) NOT NULL default '1',
  PRIMARY KEY  (id)
) TYPE=MyISAM";

$query[15] = "CREATE TABLE votes (
  id int(255) NOT NULL auto_increment,
  type enum('member','topic') NOT NULL default 'member',
  value enum('null','bad','good') NOT NULL default 'null',
  id_from varchar(255) NOT NULL default '',
  id_to varchar(255) NOT NULL default '',
  PRIMARY KEY  (id)
) TYPE=MyISAM";

$query[16] = "CREATE TABLE wordfilters (
  id int(255) NOT NULL auto_increment,
  word varchar(255) NOT NULL default '',
  replacement varchar(255) NOT NULL default '',
  PRIMARY KEY  (id)
) TYPE=MyISAM";


// Inserts data into the existing database

$query[17] = "INSERT INTO `ads` VALUES (1, 'text', 'top', 'EKINboard', 'http://www.ekinboard.com', 'A great, free, open-source, forum software.', '', '', '')";

$query[18] = "INSERT INTO `categories` VALUES (1, 'Test Category', '1')";

$query[19] = "INSERT INTO `forums` VALUES (1, 1, '0', 'Test Forum', 'This is a test forum.', '0', '0', '', '0', '1')";

$query[20] = "INSERT INTO `settings` (`name`, `value`) VALUES ('organization', 'EKINboard')";

$query[21] = "INSERT INTO `settings` VALUES (3, 'terms', 'The posters themselves are responsible for any messages they post, and not the board that it is posted on. Their views and opinions are not necessarily the views and opions of the staff here, or the website on which this board is hosted. We do not give any warranty as to the accuracy, completeness, or usefulness of any posts on this board.\r\n\r\nYou agree to not post any objectional content (any content that is knowingly false, inaccurate, abusive, vulgar, hateful, harrassing, obscene, profane, sexually oriented, threatening, invasive of a persons privacy, or any other violation of national and international laws), and understand that there will be consequences for those who post objectional content, including, but not limited to: banning and/or legal action being taken against you.\r\n\r\nYou agree to any information you enter on this board being stored into a database. While this information is kept private, hacking does happen; the staff here cannot be held responsible for any compromised data due to hacking.\r\n\r\nBy checking the box and continuing on, you agree to be bound by these conditions. If you do not agree, please click your Back button.')";


// Updates older databases

$update[0] = "ALTER TABLE `users` CHANGE `level` `level` ENUM( '1', '2', '3' ) DEFAULT '1' NOT NULL";

$update[1] = "ALTER TABLE `topics` CHANGE `protected_level` `protected_level` ENUM( '1', '2', '3' ) DEFAULT '1' NOT NULL";

$update[2] = "ALTER TABLE `topics` ADD `attch_name` VARCHAR( 255 ) NOT NULL ,ADD `attch_size` VARCHAR( 255 ) NOT NULL ,ADD `attch_type` VARCHAR( 255 ) NOT NULL";

$update[3] = "ALTER TABLE `users` ADD `ava_uploaded` enum('0','1') default '0' NOT NULL";

?>