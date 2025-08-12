
# Destiney.com Scripts Install SQL v0.3

CREATE TABLE admin (
  id tinyint(4) NOT NULL auto_increment,
  username char(16) NOT NULL default '',
  password char(32) NOT NULL default '',
  PRIMARY KEY (id)
);

INSERT INTO admin VALUES (
	1,
	'admin',
	'4cb9c8a8048fd02294477fcb1a41191a'
);

CREATE TABLE comment_threads (
  comment_id int(11) unsigned NOT NULL default '0',
  updated timestamp(14) NOT NULL,
  timestamp timestamp(14) NOT NULL,
  PRIMARY KEY (comment_id)
);

CREATE TABLE comments (
  id int(7) unsigned NOT NULL auto_increment,
  pid int(11) unsigned NOT NULL default '0',
  user_id smallint(5) unsigned NOT NULL default '0',
  subject varchar(255) NOT NULL default '',
  comment text NOT NULL,
  author_id smallint(5) NOT NULL default '0',
  author_ip varchar(15) NOT NULL default '',
  status varchar(16) NOT NULL default 'waiting',
  PRIMARY KEY (id),
  KEY user_id (user_id)
);

CREATE TABLE comment_views (
  comment_id int(11) unsigned NOT NULL default '0',
  ip char(15) NOT NULL default '',
  PRIMARY KEY (comment_id,ip)
);

CREATE TABLE cookies (
  userid int(11) NOT NULL default '0',
  cookie char(32) NOT NULL default '',
  UNIQUE KEY userid (userid),
  KEY cookie (cookie)
);

CREATE TABLE forums (
  forum_id tinyint(4) unsigned NOT NULL auto_increment,
  forum_pid tinyint(4) NOT NULL default '0',
  order_by tinyint(4) NOT NULL default '0',
  forum varchar(128) NOT NULL default '',
  description text NOT NULL,
  PRIMARY KEY (forum_id)
);

CREATE TABLE image_types (
  id tinyint(3) unsigned NOT NULL auto_increment,
  ext char(4) NOT NULL default '',
  PRIMARY KEY (id)
);

INSERT INTO image_types VALUES ('', 'jpg');
INSERT INTO image_types VALUES ('', 'JPG');
INSERT INTO image_types VALUES ('', 'jpeg');
INSERT INTO image_types VALUES ('', 'JPEG');
INSERT INTO image_types VALUES ('', 'png');
INSERT INTO image_types VALUES ('', 'PNG');

CREATE TABLE pms (
  id int(7) unsigned NOT NULL auto_increment,
  user_id smallint(5) unsigned NOT NULL default '0',
  subject varchar(50) NOT NULL default '',
  message text NOT NULL,
  author_id smallint(5) NOT NULL default '0',
  author_ip varchar(15) NOT NULL default '',
  pm_status varchar(16) NOT NULL default 'unread',
  timestamp timestamp(14) NOT NULL,
  PRIMARY KEY (id),
  KEY timestamp (timestamp),
  KEY user_id (user_id),
  KEY pm_status (pm_status)
);

CREATE TABLE posts (
  post_id int(11) unsigned NOT NULL auto_increment,
  thread_id int(11) unsigned NOT NULL default '0',
  subject varchar(255) NOT NULL default '',
  post text NOT NULL,
  userid int(11) unsigned NOT NULL default '0',
  updated timestamp(14) NOT NULL,
  timestamp timestamp(14) NOT NULL,
  PRIMARY KEY (post_id)
);

CREATE TABLE ratings (
  id int(7) unsigned NOT NULL auto_increment,
  user_id smallint(5) unsigned NOT NULL default '0',
  rating tinyint(1) unsigned NOT NULL default '0',
  rater_id smallint(5) NOT NULL default '0',
  rater_ip char(15) NOT NULL default '',
  timestamp timestamp(14) NOT NULL,
  PRIMARY KEY (id),
  KEY timestamp (timestamp),
  KEY user_id (user_id)
) PACK_KEYS=0;

CREATE TABLE sessions (
  id varchar(32) NOT NULL default '',
  data text NOT NULL,
  expire int(11) unsigned NOT NULL default '0',
  PRIMARY KEY (id)
);

CREATE TABLE thread_views (
  thread_id int(11) unsigned NOT NULL default '0',
  ip char(15) NOT NULL default '',
  PRIMARY KEY (thread_id,ip)
);

CREATE TABLE threads (
  thread_id int(11) unsigned NOT NULL auto_increment,
  forum_id tinyint(4) NOT NULL default '0',
  timestamp timestamp(14) NOT NULL,
  PRIMARY KEY (thread_id)
);

CREATE TABLE user_types (
  id tinyint(3) unsigned NOT NULL auto_increment,
  user_type varchar(255) NOT NULL default '',
  gender enum('m','f') NOT NULL default 'm',
  order_by tinyint(3) NOT NULL default '0',
  PRIMARY KEY (id)
);

CREATE TABLE users (
  id smallint(5) unsigned NOT NULL auto_increment,
  username varchar(16) NOT NULL default '',
  password varchar(16) NOT NULL default '',
  hint varchar(100) NOT NULL default '',
  realname varchar(48) NOT NULL default '',
  description text NOT NULL,
  age tinyint(2) unsigned NOT NULL default '0',
  user_type tinyint(3) unsigned NOT NULL default '0',
  state varchar(32) NOT NULL default '',
  country varchar(32) NOT NULL default 'United_States.gif',
  email varchar(48) NOT NULL default '',
  url varchar(255) NOT NULL default '',
  quote varchar(255) NOT NULL default '',
  image enum('here','there') NOT NULL default 'there',
  image_url varchar(144) NOT NULL default '',
  image_ext varchar(4) NOT NULL default '',
  image_status enum('disabled','queued','approved') NOT NULL default 'disabled',
  total_ratings smallint(5) unsigned NOT NULL default '0',
  total_points mediumint(9) unsigned NOT NULL default '0',
  average_rating decimal(6,4) NOT NULL default '0.0000',
  total_comments int(10) unsigned NOT NULL default '0',
  subscribed enum('yes','no') NOT NULL default 'yes',
  md5key varchar(32) NOT NULL default '',
  signup varchar(14) NOT NULL default '',
  timestamp timestamp(14) NOT NULL,
  PRIMARY KEY (id),
  UNIQUE KEY md5key (md5key),
  KEY sex (user_type),
  KEY timestamp (timestamp),
  KEY signup (signup),
  KEY username (username),
  KEY email (email),
  KEY subscribed (subscribed)
);
