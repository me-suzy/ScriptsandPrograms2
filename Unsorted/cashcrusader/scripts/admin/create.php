<? 
include("../conf.inc.php");
$no_db_error=1;
require_once("../functions.inc.php");
@mysql_query("create database $mysql_database");
if(!@mysql_select_db($mysql_database)){
chdir($pages_dir);
if (file_exists($pages_dir."server_error.php")){
include($pages_dir."server_error.php");}
else {
include($pages_dir."header.php");
echo "<center><br><h3><font face=arial>Server error please try later!</font></h3></center>";
include($pages_dir."footer.php");
}
exit;
}
@mysql_query("CREATE TABLE ".$mysql_prefix."ips (
ip char(64) not null,
primary key ip(ip)
) TYPE=MyISAM");
@mysql_query("CREATE TABLE ".$mysql_prefix."emails (
address char(64) not null,
primary key address(address)
) TYPE=MyISAM");
@mysql_query("CREATE TABLE ".$mysql_prefix."browsers (
md5agent char(32) not null,
agent varchar(255) not null,
block char(1) not null,
primary key md5agent(md5agent),
key block(block)
) TYPE=MyISAM");
@mysql_query("CREATE TABLE ".$mysql_prefix."system_values (
name char(16) not null,
value blob not null,
primary key (name)) TYPE=MyISAM");
@mysql_query("CREATE TABLE ".$mysql_prefix."clicks_to_process (
username char(16) not null,
type char(6) not null,
amount bigint not null,
key usertype(username,type)
) TYPE=MyISAM");
@mysql_query("CREATE TABLE ".$mysql_prefix."latest_stats (
username char(16) not null,
id char(255) not null,
time char(255) not null,
type char(255) not null,
primary key (username)
) TYPE=MyISAM");
@mysql_query("CREATE TABLE ".$mysql_prefix."free_refs (
username char(16) not null,
key username(username)
) TYPE=MyISAM");
@mysql_query("CREATE TABLE ".$mysql_prefix."redemptions (
  id int NOT NULL AUTO_INCREMENT,
  description char(255) not null,
  special blob not null,
  amount bigint not null,
  type char(6) not null,
  auto char(3) not null,
  phpcode blob not null, 
  key amount(amount),
  primary key (id),
  key description(description)
) TYPE=MyISAM");
@mysql_query("CREATE TABLE ".$mysql_prefix."click_counter (
  username char(16) not null,
  time bigint not null,
  counter int not null,
  primary key (username),
  key counter(counter),
  key time(time)
) TYPE=MyISAM");
@mysql_query("CREATE TABLE ".$mysql_prefix."ref_counter (
 username char(16) not null,
 counter int not null,
 primary key (username),
 key counter(counter)
) TYPE=MyISAM");
@mysql_query("CREATE TABLE ".$mysql_prefix."last_login (
  username char(16) not null,
  time bigint not null,
  primary key (username),
  key time(time)
) TYPE=MyISAM");
@mysql_query("CREATE TABLE ".$mysql_prefix."users (
  username char(16) NOT NULL, 
  password char(16) NOT NULL,
  email char(64) NOT NULL, 
  upline char(16) NOT NULL, 
  referrer char(16) NOT NULL, 
  signup_ip_host char(64) NOT NULL, 
  first_name char(32) NOT NULL, 
  last_name char(32) NOT NULL, 
  address char(128) NOT NULL, 
  city char(64) NOT NULL, 
  state char(64) NOT NULL, 
  zipcode char(16) NOT NULL, 
  country char(32) NOT NULL, 
  signup_date datetime not null,
  id_change_date datetime not null,
  pay_type char(8) not null,
  pay_account char(64) not null,
  free_refs char(3) not null,
  account_type char(16) not null,
  vacation date not null,
  rebuild_stats_cache char(3) not null,
  PRIMARY KEY  (username),
  KEY password (password),
  unique email (email),
  KEY upline (upline),
  KEY referrer (referrer),
  KEY signup_ip_host (signup_ip_host),
  KEY first_name (first_name),
  KEY last_name (last_name),
  KEY address (address),
  KEY city (city),
  KEY state (state),
  KEY zipcode (zipcode),
  KEY country (country),
  KEY signup_date(signup_date),
  KEY pay_type(pay_type),
  KEY pay_account(pay_account),
  key free_refs(free_refs),
  key account_type(account_type),
  key rebuild_stats_cache(rebuild_stats_cache),
  key vacation(vacation)
) TYPE=MyISAM");
@mysql_query("CREATE TABLE ".$mysql_prefix."interest (
  username char(16) not null,
  keyword char(34) not null,
  KEY username(username),
  key keyword(keyword),
  unique uniqueness(username,keyword) 
) TYPE=MyISAM");
@mysql_query("CREATE TABLE ".$mysql_prefix."accounting (
  transid char(14) not null, 
  unixtime bigint not null,
  username char(16) not null,
  description char(32) not null,
  comm char(1) not null,
  amount bigint not null,
  type char(6) not null,
  time timestamp not null,
  primary key (transid),
  KEY username(username),
  unique uniqueness(unixtime,username,description,type),
  key description(description),
  key amount(amount),
  key comm(comm),
  key type(type),
  key time(time)
) TYPE=MyISAM");
@mysql_query("CREATE TABLE ".$mysql_prefix."rotating_ads (
  bannerid int NOT NULL AUTO_INCREMENT,
  id char(16) NOT NULL, 
  description char(32) NOT NULL,
  image_url char(255) NOT NULL,
  img_width int not null,
  img_height int not null,
  site_url char(255) NOT NULL,
  alt_text char(255) NOT NULL,
  html blob NOT NULL,
  category char(16) NOT NULL,
  run_quantity bigint NOT NULL,
  run_type char(10), 
  time timestamp NOT NULL,
  views bigint NOT NULL,
  clicks int NOT NULL,
  popupurl char(255) NOT NULL,
  popupwidth int not null,
  popupheight int not null,
  popuptype char(8) not null,
  PRIMARY KEY (bannerid),
  KEY id(id),
  KEY description(description),
  KEY category(category),
  KEY run_quantity(run_quantity),
  KEY run_type(run_type),
  KEY views(views),
  KEY clicks(clicks),
  KEY time(time) 
) TYPE=MyISAM");
@mysql_query("CREATE TABLE ".$mysql_prefix."ptc_ads (
  ptcid int NOT NULL AUTO_INCREMENT,
  id char(16) NOT NULL,
  description char(32) NOT NULL,
  image_url char(255) NOT NULL,
  img_width int not null,
  img_height int not null,
  site_url char(255) NOT NULL,
  alt_text char(255) NOT NULL,
  html blob NOT NULL,
  category char(16) NOT NULL,
  run_quantity bigint NOT NULL,
  run_type char(10),
  time timestamp NOT NULL,
  views bigint NOT NULL,
  clicks int NOT NULL,
  value int not null,
  vtype char(6) not null,
  timer int not null, 
  hrlock int not null,
  PRIMARY KEY (ptcid),
  KEY id(id),
  KEY description(description),
  KEY category(category),
  KEY run_quantity(run_quantity),
  KEY run_type(run_type),
  KEY views(views),
  KEY clicks(clicks),
  KEY value(value),
  KEY vtype(vtype),
  KEY time(time),
  KEY hrlock(hrlock)
) TYPE=MyISAM");
@mysql_query("CREATE TABLE ".$mysql_prefix."email_ads (
  emailid int NOT NULL AUTO_INCREMENT,
  id char(16) NOT NULL,
  description char(32) NOT NULL,
  site_url char(255) NOT NULL,
  ad_text blob NOT NULL,
  run_quantity bigint NOT NULL,
  run_type char(10),
  time timestamp NOT NULL,
  clicks int NOT NULL,
  value int not null,
  vtype char(6) not null,
  timer int not null,
  login char(6) not null,
  creation_date datetime not null,
  PRIMARY KEY (emailid),
  KEY id(id),
  KEY description(description),
  KEY run_quantity(run_quantity),
  KEY run_type(run_type),
  KEY clicks(clicks),
  KEY creation_date(creation_date),
  KEY time(time)
) TYPE=MyISAM");
@mysql_query("CREATE TABLE paid_clicks (
  id int NOT NULL,
  username char(16) NOT NULL,
  value int not null,
  vtype char(6) not null,
  ip_host char(64) not null,
  time timestamp not null,
  KEY id(id),
  KEY username(username),
  KEY value(value),
  KEY vtype(vtype),
  KEY ip_host(ip_host),
  KEY time(time)
) TYPE=MyISAM");
@mysql_query("CREATE TABLE ".$mysql_prefix."mass_mailer (
  time timestamp not null,
  massmailid int NOT NULL AUTO_INCREMENT, 
  subject char(64) NOT NULL,
  start int NOT NULL,
  stop int not null,
  current int not null,
  ad_text blob not null,
  ad_html blob not null,
  primary key (massmailid),
  KEY subject(subject),
  KEY start(start),
  KEY stop(stop),
  KEY current(current),
  KEY time(time)
) TYPE=MyISAM");
@mysql_query("CREATE TABLE ".$mysql_prefix."levels (
  username char(16) NOT NULL,
  upline char(16) NOT NULL,
  level int NOT NULL,
  unique uniqueness(username,upline),
  KEY username(username),
  KEY upline(upline),
  KEY level(level)
) TYPE=MyISAM");
