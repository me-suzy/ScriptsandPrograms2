CREATE TABLE al_cat (
  id tinyint(3) unsigned NOT NULL default '0',
  name varchar(32) NOT NULL default '',
  selectable tinyint(4) NOT NULL default '0',
  PRIMARY KEY  (id)
) TYPE=MyISAM;

INSERT INTO al_cat VALUES ('1', 'Default', '1');
INSERT INTO al_cat VALUES ('2', '', '0');
INSERT INTO al_cat VALUES ('3', '', '0');
INSERT INTO al_cat VALUES ('5', '', '0');
INSERT INTO al_cat VALUES ('7', '', '0');
INSERT INTO al_cat VALUES ('4', '', '0');
INSERT INTO al_cat VALUES ('6', '', '0');
INSERT INTO al_cat VALUES ('8', '', '0');
INSERT INTO al_cat VALUES ('9', '', '0');
INSERT INTO al_cat VALUES ('11', '', '0');
INSERT INTO al_cat VALUES ('10', '', '0');
INSERT INTO al_cat VALUES ('13', '', '0');
INSERT INTO al_cat VALUES ('14', '', '0');
INSERT INTO al_cat VALUES ('12', '', '0');
INSERT INTO al_cat VALUES ('15', '', '0');
INSERT INTO al_cat VALUES ('16', '', '0');
INSERT INTO al_cat VALUES ('17', '', '0');
INSERT INTO al_cat VALUES ('18', '', '0');
INSERT INTO al_cat VALUES ('19', '', '0');
INSERT INTO al_cat VALUES ('20', '', '0');
INSERT INTO al_cat VALUES ('21', '', '0');
INSERT INTO al_cat VALUES ('22', '', '0');
INSERT INTO al_cat VALUES ('23', '', '0');
INSERT INTO al_cat VALUES ('24', '', '0');
INSERT INTO al_cat VALUES ('25', '', '0');
INSERT INTO al_cat VALUES ('26', '', '0');
INSERT INTO al_cat VALUES ('27', '', '0');
INSERT INTO al_cat VALUES ('28', '', '0');
INSERT INTO al_cat VALUES ('29', '', '0');
INSERT INTO al_cat VALUES ('30', '', '0');

CREATE TABLE al_conf (
  name varchar(16) NOT NULL default '',
  value varchar(255) NOT NULL default '',
  PRIMARY KEY  (name)
) TYPE=MyISAM;

INSERT INTO al_conf VALUES ('admin_name', '');
INSERT INTO al_conf VALUES ('admin_email', '');
INSERT INTO al_conf VALUES ('admin_pass', '');
INSERT INTO al_conf VALUES ('link_banners', '1');
INSERT INTO al_conf VALUES ('link_buttons', '1');
INSERT INTO al_conf VALUES ('link_thumbs', '0');
INSERT INTO al_conf VALUES ('moderate_new', '0');
INSERT INTO al_conf VALUES ('notify_ban', '1');
INSERT INTO al_conf VALUES ('notify_new', '1');
INSERT INTO al_conf VALUES ('confirm_new', '1');
INSERT INTO al_conf VALUES ('hotlink', '1');
INSERT INTO al_conf VALUES ('verify_new', '0');
INSERT INTO al_conf VALUES ('desc_min', '0');
INSERT INTO al_conf VALUES ('name_min', '5');
INSERT INTO al_conf VALUES ('desc_max', '50');
INSERT INTO al_conf VALUES ('name_max', '20');
INSERT INTO al_conf VALUES ('count_clicks', '1');
INSERT INTO al_conf VALUES ('unique_cookie', '1');
INSERT INTO al_conf VALUES ('unique_ip', '1');
INSERT INTO al_conf VALUES ('find_host', '1');

CREATE TABLE al_email (
  login varchar(30) NOT NULL default '',
  title varchar(100) NOT NULL default '',
  content text NOT NULL,
  mailto varchar(10) NOT NULL default '',
  PRIMARY KEY  (login)
) TYPE=MyISAM;

INSERT INTO al_email VALUES ('invite', 'Link Exchange Request for [name]', 'Hi,\r\n\r\nWe checked your site, [name], and would like invite you to exchange links. We use AutoLinks, an automated link exchange system, which means you\'ll be sure to get a fair amount of visitors back. To prevent the hassle of signing up, we have already added an account for your site, so you just have to use the links below and your link will appear in our listing tomorrow.\r\n\r\n[links]\r\n\r\nWe have assigned a random password to access your account. You may change your password there, check your statistics and get more information about linking us:\r\n\r\n  Site Login: [login]\r\n  Password: [pass]\r\n  Link: [refarea]\r\n\r\nIn the advanced linking page (link at the top of the page), we also invite you to upload the images for your site. If you send us visitors, your images may have a chance to be featured!\r\n\r\n\r\nThanks!\r\n\r\n[admin_name]\r\n[admin_email]', 'referrer');
INSERT INTO al_email VALUES ('confirm', '[name] Successfully Added', 'Hi,\r\n\r\nThis is an automatic notification to confirm that [name] has been added in our database. The hits you send will now be counted and your link will show up the day after you start sending hits. Please use the following links only:\r\n\r\n[links]\r\n\r\nYou may check your statistics, update your site information and get more information about linking us by logging into your account using the login and password below. In the advanced linking, we also invite you to upload the images for your site if you haven\'t already done so. If you send us enough hits, your images may have a chance to be featured!\r\n\r\n  Site Login: [login]\r\n  Password: [pass]\r\n  Link: [refarea]\r\n\r\n\r\nThanks!\r\n\r\n[admin_name]\r\n[admin_email]', 'referrer');
INSERT INTO al_email VALUES ('mod_accept', '[name] Approved!', 'Hi,\r\n\r\nThis is an automatic notification to let you know that [name] has been approved in our database. The hits you send will now be counted and your link will show up (the day after you start sending hits). Please use the following links only:\r\n\r\n[links]\r\n\r\nYou may check your statistics, update your site information and get more information about linking us by logging into your account using the login and password below. In the advanced linking, we also invite you to upload the images for your site if you haven\'t already done so. If you send us enough hits, your images may have a chance to be featured!\r\n\r\n  Site Login: [login]\r\n  Password: [pass]\r\n  Link: [refarea]\r\n\r\n\r\nThanks,\r\n\r\n[admin_name]\r\n[admin_email]', 'referrer');
INSERT INTO al_email VALUES ('mod_refuse', '[name] Not Approved', 'Hi,\r\n\r\nThis is an automatic notification to let you know that [name] has been not been approved in our database. The hits you send will not be counted so if you were linking us already, please remove your links to the following sites.\r\n\r\n[sites]\r\n\r\n\r\n[admin_name]\r\n[admin_email]', 'referrer');
INSERT INTO al_email VALUES ('ban', 'Account for [name] Disabled', 'Hi,\r\n\r\nThis is an automatic notification to let you know that [name] has been removed from our database. The hits you send will no more be counted and your link won\'t show up. You may remove your links to the following site(s) now:\r\n\r\n[sites]\r\n\r\n\r\n[admin_name]\r\n[admin_email]', 'referrer');
INSERT INTO al_email VALUES ('pass_send', 'Password for [name]', 'Hi,\r\n\r\nYou or someone else requested the login information for [name]. If you didn\'t request these information, just ignore this email. Here they are:\r\n\r\n  Site Login: [login]\r\n  Password: [pass]\r\n  Link: [refarea]\r\n\r\n\r\n[admin_name]\r\n[admin_email]', 'referrer');
INSERT INTO al_email VALUES ('verify', 'Verification needed for [name]', 'Hi,\r\n\r\nYou recently signed up for a link exchange with our site(s). In order to verify that the email address you provided is correct, please click the link below:\r\n\r\n[refarea]verify.php?login=[login]&code=[code]\r\n\r\n\r\n[admin_name]\r\n[admin_email]', 'referrer');
INSERT INTO al_email VALUES ('new_ref', 'New Referrer ([name])', 'Hi,\r\n\r\nThis is an automatic notification to let you know a new referrer signed up at AutoLinks. Details are below, you can change them in the control panel and, if you chose moderation, approve or refuse this referrer.\r\n\r\n  Login: [login]\r\n  Name: [name]\r\n  URL: [url]\r\n  Email: [email]\r\n  Category: [category]\r\n', 'admin');

CREATE TABLE al_hitclk (
  id bigint(20) unsigned NOT NULL auto_increment,
  sent datetime NOT NULL default '0000-00-00 00:00:00',
  site varchar(16) NOT NULL default '',
  ref varchar(16) NOT NULL default '',
  toref varchar(16) NOT NULL default '',
  ip varchar(15) NOT NULL default '',
  host varchar(40) NOT NULL default '',
  PRIMARY KEY  (id)
) TYPE=MyISAM;

CREATE TABLE al_hitin (
  id bigint(20) unsigned NOT NULL auto_increment,
  sent datetime NOT NULL default '0000-00-00 00:00:00',
  site varchar(16) NOT NULL default '',
  ref varchar(16) NOT NULL default '',
  ip varchar(15) NOT NULL default '',
  host varchar(40) NOT NULL default '',
  PRIMARY KEY  (id)
) TYPE=MyISAM;

CREATE TABLE al_hitout (
  id bigint(20) unsigned NOT NULL auto_increment,
  sent datetime NOT NULL default '0000-00-00 00:00:00',
  site varchar(16) NOT NULL default '',
  ref varchar(16) NOT NULL default '',
  PRIMARY KEY  (id)
) TYPE=MyISAM;

CREATE TABLE al_img (
  id smallint(5) unsigned NOT NULL auto_increment,
  type varchar(10) NOT NULL default '',
  login varchar(16) NOT NULL default '',
  format varchar(10) NOT NULL default '',
  extension char(3) NOT NULL default '',
  updated datetime NOT NULL default '0000-00-00 00:00:00',
  rawdata mediumblob NOT NULL,
  PRIMARY KEY  (id)
) TYPE=MyISAM;

CREATE TABLE al_redir (
  id smallint(5) unsigned NOT NULL auto_increment,
  site char(16) NOT NULL default '',
  ref char(16) NOT NULL default '',
  url char(100) NOT NULL default '',
  PRIMARY KEY  (id)
) TYPE=MyISAM;

CREATE TABLE al_ref (
  id int(10) unsigned NOT NULL auto_increment,
  login varchar(16) NOT NULL default '',
  password varchar(16) NOT NULL default '',
  name varchar(32) NOT NULL default '',
  url varchar(150) NOT NULL default '',
  description varchar(255) NOT NULL default '',
  thumb varchar(32) NOT NULL default '/',
  email varchar(50) NOT NULL default '',
  status int(10) NOT NULL default '0',
  added date NOT NULL default '0000-00-00',
  category tinyint(3) unsigned NOT NULL default '1',
  fromsite varchar(16) NOT NULL default '',
  code varchar(8) NOT NULL default '',
  PRIMARY KEY  (id),
  UNIQUE KEY login (login)
) TYPE=MyISAM;

CREATE TABLE al_refarea (
  id tinyint(3) unsigned NOT NULL auto_increment,
  header text NOT NULL,
  footer text NOT NULL,
  reginfo text NOT NULL,
  mainfontface varchar(100) NOT NULL default '',
  mainfontsize varchar(7) NOT NULL default '',
  mainfontcol varchar(7) NOT NULL default '',
  headerfontcol varchar(7) NOT NULL default '',
  highlightfontcol varchar(7) NOT NULL default '',
  formfrontcol varchar(7) NOT NULL default '',
  formbackcol varchar(7) NOT NULL default '',
  bodybackcol varchar(7) NOT NULL default '',
  areabackcol varchar(7) NOT NULL default '',
  PRIMARY KEY  (id)
) TYPE=MyISAM;

INSERT INTO al_refarea VALUES ('10', '<table width="520" border="0" cellspacing="0" cellpadding="0" align="center">\r\n  <tr>\r\n    <td height="34" valign="top"><font size="5">REFERRERS AREA</font></td>\r\n  </tr>\r\n</table>\r\n<table width="520" cellpadding="10" cellspacing="0" class="area" align="center">\r\n  <tr>\r\n    <td>', '    </td>\r\n  </tr>\r\n</table>', 'All links exchange on this site are automated. That means that if you want to get a link from this site, you need to sign up for an account and link us using the given code. It\'s very easy, the signup takes 2 seconds. Another advantage is that you can link all our sites with a single account. Just fill the form below!', 'Verdana, Arial, Helvetica, sans-serif', '12px', 'black', 'white', 'red', '#9999CC', '#F5F5F5', '#9999CC', '#D7D7D7');

CREATE TABLE al_site (
  id tinyint(3) unsigned NOT NULL auto_increment,
  login varchar(16) NOT NULL default '',
  name varchar(32) NOT NULL default '',
  url varchar(75) NOT NULL default '',
  alurl varchar(100) NOT NULL default '',
  status int(10) NOT NULL default '1',
  categories set('1','2','3','4','5','6','7','8','9','10','11','12','13','14','15','16','17','18','19','20','21','22','23','24','25','26','27','28','29','30') NOT NULL default '1',
  added date NOT NULL default '0000-00-00',
  nextupdate datetime NOT NULL default '0000-00-00 00:00:00',
  updinterval mediumint(9) NOT NULL default '15',
  PRIMARY KEY  (id)
) TYPE=MyISAM;

CREATE TABLE al_stats (
  id int(10) unsigned NOT NULL auto_increment,
  day date NOT NULL default '0000-00-00',
  site char(16) NOT NULL default '',
  ref char(16) NOT NULL default '',
  hitsin smallint(6) NOT NULL default '0',
  hitsout smallint(6) NOT NULL default '0',
  clicks smallint(6) NOT NULL default '0',
  PRIMARY KEY  (id)
) TYPE=MyISAM;

CREATE TABLE al_tag (
  id smallint(5) unsigned NOT NULL auto_increment,
  name varchar(50) NOT NULL default '',
  type varchar(10) NOT NULL default '',
  orderby varchar(10) NOT NULL default '',
  category tinyint(3) unsigned NOT NULL default '0',
  numlinks smallint(5) unsigned NOT NULL default '0',
  position smallint(5) unsigned NOT NULL default '0',
  minhits tinyint(3) unsigned NOT NULL default '0',
  numcolumns tinyint(3) unsigned NOT NULL default '0',
  padding tinyint(3) unsigned NOT NULL default '0',
  align varchar(10) NOT NULL default '',
  cssclass varchar(20) NOT NULL default '',
  fontsize varchar(5) NOT NULL default '',
  fonttype varchar(50) NOT NULL default '',
  showdesc tinyint(4) NOT NULL default '0',
  mouseover tinyint(4) NOT NULL default '0',
  PRIMARY KEY  (id)
) TYPE=MyISAM;

INSERT INTO al_tag VALUES (3, 'Newest Links', 'text', 'added', '0', 15, 0, '0', '1', '0', 'center', '', '', '', '0', '1');
INSERT INTO al_tag VALUES (2, 'Top Referrers', 'text', 'hitsin', '0', 15, 0, '1', '1', '0', 'center', '', '', '', '0', '1');
INSERT INTO al_tag VALUES (4, 'Top Referrers with Desc.', 'text', 'hitsin', '0', 15, 0, '1', '1', '0', 'left', '', '', '', '1', '1');
INSERT INTO al_tag VALUES (5, 'Random Banner', 'banner', 'random', '0', 1, 0, '0', '1', '5', 'center', '', '1', '', '1', '1');
INSERT INTO al_tag VALUES (6, 'Top Quality', 'text', 'clicks', '0', 15, 0, '1', '1', '0', 'center', '', '', '', '0', '1');
INSERT INTO al_tag VALUES (7, 'All Links in 4 Columns', 'text', 'name', '0', 999, 0, '0', '4', '0', 'center', '', '1', '', '0', '1');
INSERT INTO al_tag VALUES (8, '8 Thumbs in 4 Columns', 'thumb', 'random', '0', 8, 0, '0', '4', '5', 'center', '', '1', '', '1', '1');
INSERT INTO al_tag VALUES (9, 'Link of the Moment', 'text', 'random', '0', 1, 0, '10', '1', '0', 'center', '', '', '', '0', '1');