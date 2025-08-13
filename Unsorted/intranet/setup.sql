# MySQL dump 7.1
#
# Host: localhost    Database: sgreathouse
#--------------------------------------------------------
# Server version	3.22.32

#
# Table structure for table 'colors'
#
CREATE TABLE colors (
  id int(11) DEFAULT '0' NOT NULL auto_increment,
  hex varchar(6) DEFAULT 'dddddd' NOT NULL,
  label varchar(20) DEFAULT 'Medium Gray' NOT NULL,
  PRIMARY KEY (id)
);

#
# Dumping data for table 'colors'
#

INSERT INTO colors VALUES (1,'000066','Dark Blue');
INSERT INTO colors VALUES (2,'dddddd','Medium Gray');
INSERT INTO colors VALUES (3,'0000FF','Bright Blue');
INSERT INTO colors VALUES (4,'000099','Medium Blue');
INSERT INTO colors VALUES (5,'000000','Black');
INSERT INTO colors VALUES (6,'ddeeFF','Very Light Blue');
INSERT INTO colors VALUES (7,'ff0000','Bright Red');
INSERT INTO colors VALUES (8,'ff00ff','Bright Purple');
INSERT INTO colors VALUES (9,'ffff00','BrightYellow');
INSERT INTO colors VALUES (10,'ffff66','Light Yellow');
INSERT INTO colors VALUES (11,'99dddd','Robin\'s Egg Blue');
INSERT INTO colors VALUES (12,'111144','Navy Blue');
INSERT INTO colors VALUES (13,'ffffff','White');
INSERT INTO colors VALUES (14,'efefef','Off-white');
INSERT INTO colors VALUES (15,'00ddff','Light Sue Blue');
INSERT INTO colors VALUES (16,'330000','Very Dark Red');
INSERT INTO colors VALUES (17,'660000','Dark Red');
INSERT INTO colors VALUES (18,'006600','Dark Green');
INSERT INTO colors VALUES (19,'ff8888','Salmon');
INSERT INTO colors VALUES (20,'0055FF','Sue Blue');
INSERT INTO colors VALUES (21,'88ff88','Light Green');
INSERT INTO colors VALUES (22,'bbccff','Light Blue');
INSERT INTO colors VALUES (23,'ffcccc','Pink');
INSERT INTO colors VALUES (24,'662200','Brown');
INSERT INTO colors VALUES (25,'dd8800','Pumpkin');
INSERT INTO colors VALUES (26,'008888','Teal');
INSERT INTO colors VALUES (27,'442299','Prince Purple');
INSERT INTO colors VALUES (28,'992244','Mauve');
INSERT INTO colors VALUES (29,'efdddd','Naked');
INSERT INTO colors VALUES (30,'444444','Dark Gray');
INSERT INTO colors VALUES (31,'eeeeee','Light Gray');
INSERT INTO colors VALUES (32,'005555','Dark Teal');
INSERT INTO colors VALUES (33,'666666','Very Dark Gray');
INSERT INTO colors VALUES (34,'xxxxxx','Transparent');
INSERT INTO colors VALUES (35,'90A1D8','Odd Blue');
INSERT INTO colors VALUES (36,'E44000','Orange');
INSERT INTO colors VALUES (37,'650000','Mocha');
INSERT INTO colors VALUES (38,'500000','Dark Brown');
INSERT INTO colors VALUES (39,'AAAACC','Dr. Dan Blue');
INSERT INTO colors VALUES (40,'221149','Dark Purple');

#
# Table structure for table 'contactlog'
#
CREATE TABLE contactlog (
  id int(11) DEFAULT '0' NOT NULL auto_increment,
  date_time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
  contact_id int(11) DEFAULT '0' NOT NULL,
  product int(11) DEFAULT '0' NOT NULL,
  activity text NOT NULL,
  user varchar(20) DEFAULT '' NOT NULL,
  sale char(1) DEFAULT 'n' NOT NULL,
  type varchar(40),
  PRIMARY KEY (id)
);

#
# Dumping data for table 'contactlog'
#


#
# Table structure for table 'linkbar'
#
CREATE TABLE linkbar (
  id int(11) DEFAULT '0' NOT NULL auto_increment,
  icon varchar(30) DEFAULT 'ball_.gif' NOT NULL,
  title varchar(20) DEFAULT 'New Link' NOT NULL,
  url varchar(100),
  allusers char(1) DEFAULT 'y' NOT NULL,
  target varchar(10) DEFAULT '_top' NOT NULL,
  PRIMARY KEY (id)
);

#
# Dumping data for table 'linkbar'
#

INSERT INTO linkbar VALUES (6,'corel.gif','OPS','http://bhcinfo.com/ops/','y','_top');
INSERT INTO linkbar VALUES (5,'ball1d.gif','OPS Demo','http://bhcinfo.com/ops/demo/','y','_top');
INSERT INTO linkbar VALUES (7,'graph.gif','OPS WAT','http://bhcinfo.com/ops/wat.php3?T=demo','y','_top');
INSERT INTO linkbar VALUES (8,'talkbubble3_info.gif','BHCInfo.com','http://bhcinfo.com/','y','_top');
INSERT INTO linkbar VALUES (9,'afraid.gif','StressTest','http://www.stresstest.net/','y','_top');
INSERT INTO linkbar VALUES (12,'database3.gif','phpMyAdmin','phpMyAdmin/','a','_new');
INSERT INTO linkbar VALUES (14,'penguin2.gif','MLUG','http://mlug.missouri.edu/','a','_top');
INSERT INTO linkbar VALUES (15,'pcomm0a.gif','Slashdot','http://www.slashdot.org/','a','_top');
INSERT INTO linkbar VALUES (16,'moon4b.gif','Graphics','http://www.geocities.com/SiliconValley/6603/','a','_top');
INSERT INTO linkbar VALUES (17,'screw.gif','Dev Shed','http://www.developershed.com/','a','_top');
INSERT INTO linkbar VALUES (18,'gift0c.gif','FreshMeat','http://www.freshmeat.net/','a','_top');
INSERT INTO linkbar VALUES (23,'type001.gif','Terminal','/terminal/','n','content');
INSERT INTO linkbar VALUES (24,'i293.gif','File Upload','upload.php','n','content');
INSERT INTO linkbar VALUES (25,'dino2a.gif','File Download','/uploads/','n','content');

#
# Table structure for table 'news'
#
CREATE TABLE news (
  id int(11) DEFAULT '0' NOT NULL auto_increment,
  author varchar(15) DEFAULT '' NOT NULL,
  title varchar(50) DEFAULT '' NOT NULL,
  articlebody text NOT NULL,
  createdate datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
  PRIMARY KEY (id)
);

#
# Dumping data for table 'news'
#


#
# Table structure for table 'products'
#
CREATE TABLE products (
  id int(11) DEFAULT '0' NOT NULL auto_increment,
  name varchar(30) DEFAULT '' NOT NULL,
  PRIMARY KEY (id)
);

#
# Dumping data for table 'products'
#

INSERT INTO products VALUES (1,'t-shirt');
INSERT INTO products VALUES (2,'toy');
INSERT INTO products VALUES (3,'trading cards');

#
# Table structure for table 'projects'
#
CREATE TABLE projects (
  id int(11) DEFAULT '0' NOT NULL auto_increment,
  name varchar(50) DEFAULT '' NOT NULL,
  description varchar(100),
  PRIMARY KEY (id)
);

#
# Dumping data for table 'projects'
#

INSERT INTO projects VALUES (39,'RFPs','Copy of RFPs to be reviewed/considered for submission, reading/review of RFPs and meetings to discus');
INSERT INTO projects VALUES (40,'Internet/Network','Any work related to these items specifically -- if related to a specific project, list under that pr');

#
# Table structure for table 'rolodex'
#
CREATE TABLE rolodex (
  id int(11) DEFAULT '0' NOT NULL auto_increment,
  lastname varchar(25) DEFAULT '' NOT NULL,
  firstname varchar(25) DEFAULT '' NOT NULL,
  title varchar(40),
  company varchar(40),
  address1 varchar(35),
  address2 varchar(35),
  address3 varchar(35),
  city varchar(35),
  state char(2),
  zipcode varchar(5),
  zipplus4 varchar(4),
  phone varchar(20),
  fax varchar(20),
  altphone varchar(20),
  altphonetype varchar(20),
  email varchar(50),
  website varchar(100),
  comment text,
  editby varchar(20),
  createdate datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
  contacttype enum('Normal','Prospect','Both','Unknown') DEFAULT 'Unknown' NOT NULL,
  mediacontact char(1) DEFAULT 'n' NOT NULL,
  PRIMARY KEY (id)
);

#
# Dumping data for table 'rolodex'
#

INSERT INTO rolodex VALUES (5,'Greathouse','Scott','Research Analyst','Behavioral Health Concepts, Inc. (BHC)','2716 Forum Blvd.','','','Columbia','MO','65203','6520','573-446-0405','573-446-1816','573-442-1347','home','scott@bhcinfo.com','http://www.bhcinfo.com/','HOME ADDRESS:\r\n1412 Wilson Avenue\r\nColumbia, MO  65201\r\nWife\'s name is Jessica LONGAKER','sgreathouse','2000-07-07 09:32:00','Normal','n');

#
# Table structure for table 'surveyanswers'
#
CREATE TABLE surveyanswers (
  id int(11) DEFAULT '0' NOT NULL auto_increment,
  questionid int(11) DEFAULT '0' NOT NULL,
  user varchar(20) DEFAULT '' NOT NULL,
  answer text NOT NULL,
  PRIMARY KEY (id)
);

#
# Dumping data for table 'surveyanswers'
#


#
# Table structure for table 'surveyquestions'
#
CREATE TABLE surveyquestions (
  id int(11) DEFAULT '0' NOT NULL auto_increment,
  querent varchar(20),
  access varchar(8) DEFAULT 'restrict',
  question text,
  answertype varchar(4) DEFAULT 'mult' NOT NULL,
  option1 varchar(50) DEFAULT '' NOT NULL,
  option2 varchar(50),
  option3 varchar(50),
  option4 varchar(50),
  option5 varchar(50),
  option6 varchar(50),
  option7 varchar(50),
  option8 varchar(50),
  option9 varchar(50),
  option10 varchar(50),
  date_time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
  rand_val int(11) DEFAULT '0' NOT NULL,
  PRIMARY KEY (id)
);

#
# Dumping data for table 'surveyquestions'
#

#
# Table structure for table 'sysadminmsg'
#
CREATE TABLE sysadminmsg (
  id int(11) DEFAULT '0' NOT NULL auto_increment,
  login varchar(20) DEFAULT 'unknown' NOT NULL,
  message text,
  date_time datetime,
  PRIMARY KEY (id)
);

#
# Dumping data for table 'sysadminmsg'
#


#
# Table structure for table 'tasklist'
#
CREATE TABLE tasklist (
  id int(11) DEFAULT '0' NOT NULL auto_increment,
  task text NOT NULL,
  priority int(11) DEFAULT '1' NOT NULL,
  who_owns varchar(20) DEFAULT '' NOT NULL,
  tstamp timestamp(14),
  who_wrote varchar(20) DEFAULT '' NOT NULL,
  PRIMARY KEY (id)
);

#
# Dumping data for table 'tasklist'
#


#
# Table structure for table 'tasks'
#
CREATE TABLE tasks (
  id int(11) DEFAULT '0' NOT NULL auto_increment,
  name varchar(20) DEFAULT '' NOT NULL,
  description varchar(100) DEFAULT '' NOT NULL,
  PRIMARY KEY (id)
);

#
# Dumping data for table 'tasks'
#

INSERT INTO tasks VALUES (1,'Clerical/Secretarial','Any clerical or secretarial work.');
INSERT INTO tasks VALUES (2,'Computing/Network','Any computer or network support work.');
INSERT INTO tasks VALUES (3,'Accounting','');
INSERT INTO tasks VALUES (4,'Consulting','');
INSERT INTO tasks VALUES (5,'Contracts','');
INSERT INTO tasks VALUES (6,'Data Entry','');
INSERT INTO tasks VALUES (7,'Graphics','');
INSERT INTO tasks VALUES (8,'Internet Search','');
INSERT INTO tasks VALUES (9,'Interviews','');
INSERT INTO tasks VALUES (10,'Invoicing','');
INSERT INTO tasks VALUES (11,'Mail','');
INSERT INTO tasks VALUES (12,'Marketing','');
INSERT INTO tasks VALUES (13,'Meeting','');
INSERT INTO tasks VALUES (14,'Patient Registration','');
INSERT INTO tasks VALUES (15,'Programming','');
INSERT INTO tasks VALUES (16,'Proposal Write/Edit','');
INSERT INTO tasks VALUES (17,'Proposal Planning','');
INSERT INTO tasks VALUES (18,'Report Writing','');
INSERT INTO tasks VALUES (19,'RFP Reviews','');
INSERT INTO tasks VALUES (20,'Technical Assistance','');
INSERT INTO tasks VALUES (21,'Therapy Patients','');
INSERT INTO tasks VALUES (22,'Travel','');
INSERT INTO tasks VALUES (25,'Data Analysis','');
INSERT INTO tasks VALUES (26,'Timesheets','Data Entry Time Sheets');
INSERT INTO tasks VALUES (27,'Financials','Prepare monthly Financials');
INSERT INTO tasks VALUES (28,'Payroll','Preparation of Payroll records and payroll');
INSERT INTO tasks VALUES (29,'Vacation','Vacation Leave');
INSERT INTO tasks VALUES (30,'Sick','Sick Leave');
INSERT INTO tasks VALUES (31,'Holiday','Holiday Leave');
INSERT INTO tasks VALUES (32,'Research','Research  for information');
INSERT INTO tasks VALUES (33,'Management/Supervisi','Management and Supervision');
INSERT INTO tasks VALUES (34,'Testing','Time spent Testing Patients');
INSERT INTO tasks VALUES (35,'Scoring Tests','');
INSERT INTO tasks VALUES (36,'Test Interpretation','');

#
# Table structure for table 'timesheetrow'
#
CREATE TABLE timesheetrow (
  id int(11) DEFAULT '0' NOT NULL auto_increment,
  sheetid int(11) DEFAULT '0' NOT NULL,
  projectid int(11) DEFAULT '0' NOT NULL,
  taskid int(11) DEFAULT '0' NOT NULL,
  monday float(10,2) DEFAULT '0.00' NOT NULL,
  tuesday float(10,2) DEFAULT '0.00' NOT NULL,
  wednesday float(10,2) DEFAULT '0.00' NOT NULL,
  thursday float(10,2) DEFAULT '0.00' NOT NULL,
  friday float(10,2) DEFAULT '0.00' NOT NULL,
  weekend float(10,2) DEFAULT '0.00' NOT NULL,
  PRIMARY KEY (id)
);

#
# Dumping data for table 'timesheetrow'
#


#
# Table structure for table 'timesheets'
#
CREATE TABLE timesheets (
  id int(11) DEFAULT '0' NOT NULL auto_increment,
  login varchar(20) DEFAULT 'unknown' NOT NULL,
  endyear varchar(4),
  endmonth char(2),
  endday char(2),
  approveddate datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
  approvedby varchar(20),
  mondaydate varchar(10),
  mondayin varchar(15),
  tuesdaydate varchar(10),
  tuesdayin varchar(15),
  wednesdaydate varchar(10),
  wednesdayin varchar(15),
  thursdaydate varchar(10),
  thursdayin varchar(15),
  fridaydate varchar(10),
  fridayin varchar(15),
  weekenddate varchar(10),
  weekendin varchar(15),
  sheetfinished char(1) DEFAULT 'n' NOT NULL,
  mondayout varchar(15),
  tuesdayout varchar(15),
  wednesdayout varchar(15),
  thursdayout varchar(15),
  fridayout varchar(15),
  weekendout varchar(15),
  lunchmoin varchar(5),
  lunchmoout varchar(5),
  lunchtuin varchar(5),
  lunchtuout varchar(5),
  lunchwein varchar(5),
  lunchweout varchar(5),
  lunchthin varchar(5),
  lunchthout varchar(5),
  lunchfrin varchar(5),
  lunchfrout varchar(5),
  lunchwkendin varchar(5),
  lunchwkendout varchar(5),
  PRIMARY KEY (id)
);

#
# Dumping data for table 'timesheets'
#


#
# Table structure for table 'userinfo'
#
CREATE TABLE userinfo (
  login varchar(15) DEFAULT '' NOT NULL,
  firstname varchar(15) DEFAULT '' NOT NULL,
  lastname varchar(20) DEFAULT '' NOT NULL,
  ipaddress varchar(16) DEFAULT '999.999.999.999' NOT NULL,
  opsys varchar(10) DEFAULT '0' NOT NULL,
  datajack varchar(6) DEFAULT '0' NOT NULL,
  machinename varchar(10) DEFAULT '' NOT NULL,
  menu_bgcolor varchar(6) DEFAULT '000066' NOT NULL,
  menu_fontcolor varchar(6) DEFAULT 'ffffff' NOT NULL,
  menu_fontface varchar(40) DEFAULT 'Arial Narrow,Arial' NOT NULL,
  menu_fontsize char(1) DEFAULT '2' NOT NULL,
  menu_columns char(1) DEFAULT '1' NOT NULL,
  default_bgcolor varchar(6) DEFAULT 'dddddd' NOT NULL,
  menu_scroll char(3) DEFAULT 'yes' NOT NULL,
  default_fontcolor varchar(6) DEFAULT '000000' NOT NULL,
  default_fontface varchar(20) DEFAULT 'Arial' NOT NULL,
  default_fontsize char(2) DEFAULT '3' NOT NULL,
  heading_fontcolor varchar(6) DEFAULT 'ffff66' NOT NULL,
  heading_fontface varchar(20) DEFAULT 'Verdana,Arial' NOT NULL,
  heading_fontsize char(2) DEFAULT '5' NOT NULL,
  heading_bgcolor varchar(6) DEFAULT '111144' NOT NULL,
  showquote char(1) DEFAULT 'n' NOT NULL,
  password varchar(15) DEFAULT 'password' NOT NULL,
  utilitybar varchar(4) DEFAULT 'fals' NOT NULL,
  perm_news char(1) DEFAULT 'y' NOT NULL,
  perm_calendar char(1) DEFAULT 'y' NOT NULL,
  perm_rolodex char(1) DEFAULT 'y' NOT NULL,
  perm_network char(1) DEFAULT 'y' NOT NULL,
  perm_tasklist char(1) DEFAULT 'y' NOT NULL,
  perm_admin char(1) DEFAULT 'n' NOT NULL,
  perm_timesheet char(1) DEFAULT 'y' NOT NULL,
  perm_timesheetcp char(1) DEFAULT 'n' NOT NULL,
  perm_setup char(1) DEFAULT 'y' NOT NULL,
  want_news char(1) DEFAULT 'y' NOT NULL,
  want_calendar char(1) DEFAULT 'y' NOT NULL,
  want_rolodex char(1) DEFAULT 'y' NOT NULL,
  want_tasklist char(1) DEFAULT 'y' NOT NULL,
  want_timesheet char(1) DEFAULT 'y' NOT NULL,
  want_timesheetcp char(1) DEFAULT 'y' NOT NULL,
  want_admin char(1) DEFAULT 'y' NOT NULL,
  want_setup char(1) DEFAULT 'y' NOT NULL,
  want_network char(1) DEFAULT 'y' NOT NULL,
  quotes_dark char(1) DEFAULT 'n' NOT NULL,
  quotes_happy char(1) DEFAULT 'y' NOT NULL,
  quotes_odd char(1) DEFAULT 'n' NOT NULL,
  quotes_fortune char(1) DEFAULT 'y' NOT NULL,
  quotes_crude char(1) DEFAULT 'n' NOT NULL,
  quotes_joke char(1) DEFAULT 'n' NOT NULL,
  perm_sharepw char(1) DEFAULT 'n' NOT NULL,
  useheader char(1) DEFAULT 'y' NOT NULL,
  menumode varchar(4) DEFAULT 'norm' NOT NULL,
  want_admin_news char(1) DEFAULT 'y' NOT NULL,
  want_admin_bar char(1) DEFAULT 'n' NOT NULL,
  want_admin_msg char(1) DEFAULT 'n',
  emailaddress varchar(100),
  employeetype varchar(35) DEFAULT 'Unknown' NOT NULL,
  perm_survey char(1) DEFAULT 'n' NOT NULL,
  want_survey char(1) DEFAULT 'n' NOT NULL,
  want_contact char(1) DEFAULT 'n' NOT NULL,
  perm_contact char(1) DEFAULT 'n' NOT NULL,
  headinghighlight varchar(6) DEFAULT 'dddddd' NOT NULL,
  linkbarbg varchar(6) DEFAULT 'ffffff' NOT NULL,
  bgwallpaper varchar(35) DEFAULT 'none' NOT NULL,
  menuwallpaper varchar(35) DEFAULT 'none' NOT NULL,
  headingwallpaper varchar(35) DEFAULT 'none' NOT NULL,
  cal_login varchar(20),
  adminlbbg varchar(6) DEFAULT '666666' NOT NULL,
  lb_fontcolor varchar(6) DEFAULT '000000' NOT NULL,
  ab_fontcolor varchar(6) DEFAULT 'ffffff' NOT NULL,
  UNIQUE login (login),
  UNIQUE machinename (machinename),
  UNIQUE ipaddress (ipaddress)
);

#
# Dumping data for table 'userinfo'
#

INSERT INTO userinfo VALUES ('sgreathouse','Scott','Greathouse','192.168.1.101','win98','3A-1-1','scott','330000','ffff00','Arial Narrow,san-serif,sanserif','2','1','660000','no','ffffff','Arial','3','ffff00','Verdana, san-serif','4','xxxxxx','','password','true','y','y','y','y','y','y','y','y','y','y','y','y','y','y','n','y','y','n','y','n','y','y','n','n','y','y','norm','y','y','y','sgreathouse@bhcinfo.com','Normal','y','n','','y','330000','xxxxxx','Rose-Caramel-1.JPG','','Rose-Caramel-1.JPG','scott','xxxxxx','dddddd','dddddd');

#
# Table structure for table 'visitor'
#
CREATE TABLE visitor (
  id int(11) DEFAULT '0' NOT NULL auto_increment,
  name varchar(100) DEFAULT 'Anonymous' NOT NULL,
  first_login_dt datetime,
  last_login_dt datetime,
  first_login_ip varchar(15),
  last_login_ip varchar(15),
  qt_dark char(1) DEFAULT 'n' NOT NULL,
  qt_happy char(1) DEFAULT 'n' NOT NULL,
  qt_joke char(1) DEFAULT 'n' NOT NULL,
  qt_odd char(1) DEFAULT 'n' NOT NULL,
  qt_crude char(1) DEFAULT 'n' NOT NULL,
  qt_fortune char(1) DEFAULT 'n' NOT NULL,
  bgcolor varchar(6) DEFAULT 'FFFFFF' NOT NULL,
  add_quotes char(1) DEFAULT 'n' NOT NULL,
  bgwallpaper varchar(35),
  textcolor varchar(6) DEFAULT '000000' NOT NULL,
  linkcolor varchar(6) DEFAULT '0000FF' NOT NULL,
  heading varchar(100),
  heading_bgcolor varchar(6) DEFAULT '000066' NOT NULL,
  heading_textcolor varchar(6) DEFAULT 'FFFF66' NOT NULL,
  heading_wallpaper varchar(35),
  trusted char(1) DEFAULT 'n' NOT NULL,
  last_user_msg varchar(35),
  use_hidden_upload char(1) DEFAULT 'n' NOT NULL,
  PRIMARY KEY (id)
);

#
# Dumping data for table 'visitor'
#


#
# Table structure for table 'visitorlinks'
#
CREATE TABLE visitorlinks (
  id int(11) DEFAULT '0' NOT NULL auto_increment,
  BHCid int(11),
  label varchar(50),
  url varchar(200),
  PRIMARY KEY (id)
);

#
# Dumping data for table 'visitorlinks'
#


#
# Table structure for table 'visitors'
#
CREATE TABLE visitors (
  realipaddy varchar(15),
  fauxipaddy varchar(15),
  starttime datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
  id int(11) DEFAULT '0' NOT NULL auto_increment,
  PRIMARY KEY (id)
);

#
# Dumping data for table 'visitors'
#


#
# Table structure for table 'webcal_entry'
#
CREATE TABLE webcal_entry (
  cal_id int(11) DEFAULT '0' NOT NULL,
  cal_group_id int(11),
  cal_create_by varchar(25) DEFAULT '' NOT NULL,
  cal_date int(11) DEFAULT '0' NOT NULL,
  cal_time int(11),
  cal_mod_date int(11),
  cal_mod_time int(11),
  cal_duration int(11) DEFAULT '0' NOT NULL,
  cal_priority int(11) DEFAULT '2',
  cal_type char(1) DEFAULT 'E',
  cal_access char(1) DEFAULT 'P',
  cal_name varchar(80) DEFAULT '' NOT NULL,
  cal_description text,
  PRIMARY KEY (cal_id)
);

#
# Dumping data for table 'webcal_entry'
#


#
# Table structure for table 'webcal_entry_repeats'
#
CREATE TABLE webcal_entry_repeats (
  cal_id int(11) DEFAULT '0' NOT NULL,
  cal_type varchar(20),
  cal_end int(11),
  cal_frequency int(11) DEFAULT '1',
  cal_days varchar(7),
  PRIMARY KEY (cal_id)
);

#
# Dumping data for table 'webcal_entry_repeats'
#


#
# Table structure for table 'webcal_entry_user'
#
CREATE TABLE webcal_entry_user (
  cal_id int(11) DEFAULT '0' NOT NULL,
  cal_login varchar(25) DEFAULT '' NOT NULL,
  cal_status char(1) DEFAULT 'A',
  PRIMARY KEY (cal_id,cal_login)
);

#
# Dumping data for table 'webcal_entry_user'
#


#
# Table structure for table 'webcal_user'
#
CREATE TABLE webcal_user (
  cal_login varchar(25) DEFAULT '' NOT NULL,
  cal_passwd varchar(25),
  cal_lastname varchar(25),
  cal_firstname varchar(25),
  cal_is_admin char(1) DEFAULT 'N',
  cal_email varchar(75),
  PRIMARY KEY (cal_login)
);

#
# Dumping data for table 'webcal_user'
#

INSERT INTO webcal_user VALUES ('sgreathouse','password','Greathouse','Scott','Y',NULL);
INSERT INTO webcal_user VALUES ('admin','admin','Administrator','The','Y',NULL);


#
# Table structure for table 'webcal_user_pref'
#
CREATE TABLE webcal_user_pref (
  cal_login varchar(25) DEFAULT '' NOT NULL,
  cal_setting varchar(25) DEFAULT '' NOT NULL,
  cal_value varchar(50),
  PRIMARY KEY (cal_login,cal_setting)
);

#
# Dumping data for table 'webcal_user_pref'
#

#
# Table structure for table 'webcal_user_layers'
#
CREATE TABLE webcal_user_layers (
  cal_layerid INT DEFAULT '0' NOT NULL,
  cal_login varchar(25) NOT NULL,
  cal_layeruser varchar(25) NOT NULL,
  cal_color varchar(25) NULL,
  cal_dups CHAR(1) DEFAULT 'N',
  PRIMARY KEY ( cal_login, cal_layeruser )
);

#
# Table structure for table 'network'
#
CREATE TABLE network (
  id int(11) NOT NULL auto_increment,
  ipaddress varchar(15) DEFAULT '209.184.149.77' NOT NULL,
  domainname varchar(35) DEFAULT 'bhcinfo.com' NOT NULL,
  type enum('IntranetServer','Gateway','ISP','OurWebSite','MiscWebSite','DialUp') DEFAULT 'MiscWebSite' NOT NULL,
  pingwhich enum('ip','name') DEFAULT 'ip' NOT NULL,
  message varchar(100) DEFAULT 'uptime.php' NOT NULL,
  PRIMARY KEY (id)
);

#
# Dumping data for table 'network'
#

INSERT INTO network VALUES (1,'192.168.1.254','Gabrielle','Gateway','ip','uptime.php');
INSERT INTO network VALUES (2,'192.168.1.253','Xena','IntranetServer','ip','');
INSERT INTO network VALUES (3,'209.184.149.77','BHCInfo.com','OurWebSite','ip','');
INSERT INTO network VALUES (4,'206.156.230.1','Tranquility.net','ISP','ip','');
INSERT INTO network VALUES (5,'128.206.2.83','showme.missouri.edu','MiscWebSite','name','');
INSERT INTO network VALUES (6,'192.168.1.69','BHCDialUp','DialUp','ip','');

#
# Table structure for table 'quotes'
#
CREATE TABLE quotes (
  id int(11) NOT NULL auto_increment,
  quotetext text DEFAULT '' NOT NULL,
  quoteauthor varchar(100) DEFAULT 'Anonymous' NOT NULL,
  quotetype char(1) DEFAULT 'd' NOT NULL,
  PRIMARY KEY (id)
);

#
# Dumping data for table 'quotes'
#

INSERT INTO quotes VALUES (1,'Life is like a box of chocolates. A cheap,\r\nthoughtless, perfunctory gift that no one\r\never asks for. Unreturnable because all you\r\nget back is another box of chocolates. So,\r\nyou\'re stuck with mostly undefinable whipped\r\nmint crap, mindlessly wolfed down when there\'s\r\nnothing else to eat while you\'re watching the\r\ngame. Sure, once in a while you get a peanut\r\nbutter cup or an English toffee but it\'s gone\r\ntoo fast and the taste is fleeting. In the end,\r\nyou\'re left with nothing but broken bits filled\r\nwith hardened jelly and teeth shattering nuts,\r\nwhich if you are desperate enought to eat leaves\r\nnothing but an empty box of useless brown paper\r\nwrappers.','Cancer Man, from <a href=\"http://www.x-files.com/\">the X-files</a>','d');
INSERT INTO quotes VALUES (2,'Never try to teach a pig to sing. You\'ll only\r\nwaste your time and annoy the pig.','Mark Twain','d');
INSERT INTO quotes VALUES (3,'...Evil will always win because Good is dumb.','Dark Helmet, from Spaceballs','d');
INSERT INTO quotes VALUES (4,'Everything dies.','the X-Files','d');
INSERT INTO quotes VALUES (5,'Hell is other people.','Neitzsche','d');
INSERT INTO quotes VALUES (6,'Doing a thing well is often a waste of time.','Robert Byrne','d');
INSERT INTO quotes VALUES (7,'Some lies are so well disguised to resemble truth, that we should be poor\r\njudges of the truth not to believe them.','Anonymous','d');
INSERT INTO quotes VALUES (8,'All you need in this life is ignorance and confidence, and then success is\r\nsure.','Mark Twain','d');
INSERT INTO quotes VALUES (9,'It is dangerous to be right when the government is wrong.','Voltaire','d');
INSERT INTO quotes VALUES (10,'Under any conditions, anywhere, whatever you are doing, there is some\r\nordinance under which you can be booked.','Robert D. Sprecht','d');
INSERT INTO quotes VALUES (11,'To know all is not to forgive all. It is to despise everybody.','Quentin Crisp','d');
INSERT INTO quotes VALUES (12,'Love is an ideal thing, marriage a real thing; a confusion of the real with\r\nthe ideal never goes unpunished.','Goethe','d');
INSERT INTO quotes VALUES (13,'Wars, conflict, it\'s all business. One murder makes a villain. Millions a\r\nhero. Numbers sanctify.','Charlie Chaplin','d');
INSERT INTO quotes VALUES (14,'The world is a vampire<br>\r\nSent to drain','Billy Corgan','d');
INSERT INTO quotes VALUES (15,'I\'d like to share a revelation that I\'ve had during my time here. It came\r\nto me when I tried to classify your species. I\'ve realized that you are not\r\nactually mammals. Every mammal on this planet instinctively develops a\r\nnatural equilibrium with the surrounding environment. But you humans do not.\r\nYou move to an area and you multiply and multiply until every natural\r\nresource is consumed and the only way you can survive is to spread to another\r\narea. There is another organism on this planet that follows the same pattern.\r\nDo you know what it is? A virus.','Agent Smith, <b>The Matrix</b>','d');
INSERT INTO quotes VALUES (16,'Definition:<br>\r\nDemocracy: Political system where the proletariat periodically chooses which members of the bourgeoisie will exploit them.','Anonymous','d');
INSERT INTO quotes VALUES (17,'You will soon become romantically involved with an attractive blonde or brunette... or possibly a red head.','Anonymous','f');
INSERT INTO quotes VALUES (18,'Persecution is a bad and indirect way to plant religion.','Sir Thomas Browne (1605-1682)','d');
INSERT INTO quotes VALUES (19,'All of your fondest wishes will come true.','Anonymous','f');
INSERT INTO quotes VALUES (20,'Glory is like a circle in the water,<br>Which never ceaseth to enlarge itself,<br>Till by broad spreading it disperse to nought.','William Shakespeare','h');
INSERT INTO quotes VALUES (21,'Blessed are the peacemakers on earth.','William Shakespeare','h');
INSERT INTO quotes VALUES (22,'Love comforteth like sunshine after rain.','William Shakespeare','h');
INSERT INTO quotes VALUES (23,'If you are out of cream for your coffee, mayonnaise makes a dandy substitute!','Martha Stewart','o');
INSERT INTO quotes VALUES (24,'I don\\\'t remember it, but I have it written down.','Anonymous','o');
INSERT INTO quotes VALUES (25,'To err is human. To really foul things up requires a computer.','Anonymous','o');
INSERT INTO quotes VALUES (26,'Public use of any portable music system is a virtually guaranteed indicator of sociopathic tendencies.','Zoso','o');
INSERT INTO quotes VALUES (27,'Stealing a rhinocerous should not be attempted lightly.','Julius Caesar','o');
INSERT INTO quotes VALUES (28,'If imprinted foil seal under cap is broken or missing when purchased, do not use.','Anonymous','o');
INSERT INTO quotes VALUES (29,'Whoever would lie usefully should lie seldom.','Anonymous','f');
INSERT INTO quotes VALUES (30,'I love you more than anything in this world. I don\\\'t expect that will last.','Elvis Costello','o');
INSERT INTO quotes VALUES (31,'If once a man indulges himself in murder, very soon he comes to think little of robbing; and from robbing he next comes to drinking and Sabbath-breaking, and from that to incivility and procrastination.','Thomas De Quincey (1785 - 1859)','o');
INSERT INTO quotes VALUES (77,'There is no way to Peace. Peace is the way.','A. J. Muste','h');
INSERT INTO quotes VALUES (78,'Corporate Lessen #1<br>\r\nA crow was sitting on a tree, doing nothing all day. A small rabbit saw the\r\ncrow, and asked him, \\\"Can I also sit like you and do nothing all day  long?\\\"<br>\r\nThe crow answered: \\\"Sure, why not.\\\" So, the rabbit sat on the ground\r\nbelow the crow, and rested. All of a sudden, a fox appeared, jumped on the\r\nrabbit and ate it.<br>\r\n\r\nMoral of the story:\r\nTo be sitting and doing nothing, you must be sitting very, very high up.','Anonymous','j');
INSERT INTO quotes VALUES (33,'Q: How do you catch a unique rabbit?<br>\r\nA: Unique up on it.<br>\r\nQ: How do you catch a tame rabbit?<br>\r\nA: The tame way.','Anonymous','j');
INSERT INTO quotes VALUES (34,'I just ate a whole package of Sweet Tarts and a can of Coke. I think I saw God.','B. Hathrume Duk','o');
INSERT INTO quotes VALUES (35,'You have a deep appreciation of the arts and music.','Anonymous','f');
INSERT INTO quotes VALUES (36,'Life is like a box of chocolates. You never know what you\\\'re going to get.','Forest Gump','h');
INSERT INTO quotes VALUES (106,'Kaa\\\'s Law: In any sufficiently large group of people most are idiots. ','Kaa','d');
INSERT INTO quotes VALUES (107,'For every problem, there is a simple answer that is wrong.','H.L. Mencken','d');
INSERT INTO quotes VALUES (108,'You just plug it in, and you\\\'re good to go!','Satisfied AOL Customer','o');
INSERT INTO quotes VALUES (109,'Good... Bad... I\\\'m the guy with the gun.','Ash, <b>Army of Darkness</b>','o');
INSERT INTO quotes VALUES (38,'Knowledge is good.','Emil Fabre, <b>Animal House</b>','h');
INSERT INTO quotes VALUES (39,'Rosebud.','Charles Foster Kane, <b>Citizen Kane</b>','o');
INSERT INTO quotes VALUES (40,'My friend here is trying to convince me that any independent contractors who were working on the\r\n uncompleted Death Star, were innocent victims when they were destroyed by the Rebels.','Dante Hicks, <b>Clerks</b>','o');
INSERT INTO quotes VALUES (41,'It\\\'s important to have a job that makes a difference, boys. That\\\'s why I manually masturbate caged animals for artificial insemination.','Caged Animal Masturbator, <b>Clerks</b>','c');
INSERT INTO quotes VALUES (42,'A gift of a flower will soon be made to you.','Anonymous','f');
INSERT INTO quotes VALUES (43,'A visit to a strange place will bring you fresh work.','Anonymous','f');
INSERT INTO quotes VALUES (44,'Avert misunderstanding by calm, poise and understanding.','Anonymous','f');
INSERT INTO quotes VALUES (45,'Beware a dark-haired man with a loud tie.','Anonymous','f');
INSERT INTO quotes VALUES (46,'Expect correspondence from a friend who will ask a favor of you.','Anonymous','f');
INSERT INTO quotes VALUES (47,'Long life is in store for you.','Anonymous','f');
INSERT INTO quotes VALUES (48,'Make a wish. It might come true.','Anonymous','f');
INSERT INTO quotes VALUES (49,'This life is yours. Some of it was given to you; the rest, you made yourself.','Anonymous','f');
INSERT INTO quotes VALUES (50,'Yesterday, today was tomorrow.','Anonymous','f');
INSERT INTO quotes VALUES (51,'Today\\\'s weirdness is tomorrow\\\'s reason why.','Anonymous','f');
INSERT INTO quotes VALUES (52,'You are fairminded, just and loving.','Anonymous','f');
INSERT INTO quotes VALUES (53,'You are deeply attached to your friends and acquaintances.','Anonymous','f');
INSERT INTO quotes VALUES (54,'You enjoy the company of other people.','Anonymous','f');
INSERT INTO quotes VALUES (55,'You have many friends and few living enemies.','Anonymous','f');
INSERT INTO quotes VALUES (56,'You have been selected for a secret mission.','Anonymous','f');
INSERT INTO quotes VALUES (57,'You have the power to influence all with whom you come in contact.','Anonymous','f');
INSERT INTO quotes VALUES (58,'An opportunity for advancement in your career comes from an unexpected place.','Anonymous','f');
INSERT INTO quotes VALUES (59,'You recoil from the crude; you tend naturally toward the exquisite.','Anonymous','f');
INSERT INTO quotes VALUES (60,'You will be aided greatly by a person you thought to be unimportant.','Anonymous','f');
INSERT INTO quotes VALUES (61,'You will be called upon to help a friend in trouble.','Anonymous','f');
INSERT INTO quotes VALUES (62,'You will engage in a profitable business activity.','Anonymous','f');
INSERT INTO quotes VALUES (63,'You will experience a strong urge to do the right thing. But don\\\'t worrry, it will pass.','Anonymous','f');
INSERT INTO quotes VALUES (64,'You will have domestic happiness and faithful friends.','Anonymous','f');
INSERT INTO quotes VALUES (65,'You will have good luck and overcome many hardships.','Anonymous','f');
INSERT INTO quotes VALUES (66,'You will pay for your sins. If you have already paid, please disregard this message.','Anonymous','f');
INSERT INTO quotes VALUES (67,'Soon, you will forget this.','Anonymous','f');
INSERT INTO quotes VALUES (68,'Your present plans will be successful.','Anonymous','f');
INSERT INTO quotes VALUES (69,'You will make a journey by water.','Anonymous','f');
INSERT INTO quotes VALUES (70,'If change were death, it would be bad; but it is life.','Anonymous','f');
INSERT INTO quotes VALUES (71,'A rolling stone gathers no moss.','Anonymous','f');
INSERT INTO quotes VALUES (72,'You tip well, and are appreciated for it.','Anonymous','f');
INSERT INTO quotes VALUES (73,'If you want to touch the sky, you must first learn how to kneel.','Anonymous','f');
INSERT INTO quotes VALUES (74,'A strange person with spectacles will question your authority.','Anonymous','f');
INSERT INTO quotes VALUES (75,'Sexuality and homosexuality should not be a problem for anybody. I respect\r\npeople the way they are, just as I want people to respect me.','Ricky Martin, dodging a question from Barbara Walters','c');
INSERT INTO quotes VALUES (76,'Marvin the Nature Lover spied a grasshopper hopping along in the grass, and in a mood for communing with nature, rare even among full-fledged Nature Lovers, he spoke to the grasshopper, saying: \\\"Hello, friend grasshopper. Did you know they\\\'ve named a drink after you?\\\"\r\n\\\"Really?\\\" replied the grasshopper, obviously pleased. \\\"They\\\'ve named a drink Fred?\\\"','Anonymous','j');
INSERT INTO quotes VALUES (79,'Corporate Lesson #2:<br>\r\nA turkey was chatting with a bull. \\\"I would love to be able to get to\r\nthe top of that tree,\\\" sighed the turkey,\\\"but I haven\\\'t got the energy.\\\"<br>\r\n\\\"Well, why don\\\'t you nibble on some of my droppings?\\\" replied the bull.\r\n\\\"They\\\'re packed with nutrients.\\\" The turkey pecked at a lump of dung and\r\nfound that it actually gave him enough strength to reach the first branch of\r\nthe tree.<br>\r\nThe next day, after eating some more dung, he reached the second branch.<br>\r\nFinally after a fortnight, there he was proudly perched at the top of the\r\ntree. Soon he was promptly spotted by a farmer, who shot the turkey\r\nout of the tree.<br>\r\n\r\nMoral of the story:\r\nBullshit might get you to the top, but it won\\\'t keep you there.','Anonymous','c');
INSERT INTO quotes VALUES (80,'Corporate Lesson #3:<br>\r\nA little bird was flying south for the winter. It was so cold, the bird\r\nfroze and fell to the ground in a large field. While it was lying there, a\r\ncow came by and dropped some dung on it. As the frozen bird lay there\r\nin the pile of cow dung, it began to realize how warm it was.The dung was\r\nactually thawing him out! He lay there all warm and happy, and soon began to\r\nsing for joy.<br>\r\nA passing cat heard the bird singing and came to investigate.  Following the\r\nsound, the cat discovered the bird under the pile of cow dung, and promptly\r\ndug him out and ate him!<br>\r\n\r\nThe morals of this story are:<br>\r\n1) Not everyone who drops shit on you is your enemy.<br>\r\n2) Not everyone who gets you out of shit is your friend.<br>\r\n3) And when you\\\'re in deep shit, keep your mouth shut','Anonymous','c');
INSERT INTO quotes VALUES (81,'Corporate Lesson #4:<br>\r\nAn organization is like a tree full of monkeys, all on different limbs at\r\ndifferent levels, some climbing up, some fooling around and some simply just\r\nidling... The monkeys on top look down and see a tree full of smiling\r\nfaces... The monkeys on the bottom look up and see nothing but assholes...','Anonymous','c');
INSERT INTO quotes VALUES (82,' A local preacher was dissatisfied with the small amount in the collection plates each Sunday.  Someone suggested to him that perhaps he might be able\r\nto hypnotize the congregation into giving more. \\\"And just how would I go\r\nabout doing that?\\\" he asked.<br>\r\n\\\"It is very simple.  First you turn off the air conditioner so that the\r\nauditorium is warmer than usual.  Then you preach in a monotone. Meanwhile,\r\nyou dangle a watch on a chain and swing it in a slow arc above the lectern\r\nand suggest they put 20 dollars in the collection plate.\\\"\r\n<BR>So the very next Sunday, the reverend did as suggested, and lo and behold the plates were full of 20 dollar bills.  Now, the preacher did not want to take advantage of this technique each and every Sunday.  So therefore, he waited for a couple of weeks and then tried his mass hypnosis again.\r\n<br>Just as the last of the congregation was becoming mesmerized, the chain on\r\nthe watch broke and the watch hit the lectern with a loud thud and springs and parts flew everywhere.\r\n<br>\\\"Crap!\\\" exclaimed the pastor.\r\n<br>It took them a week to clean up the church.','Anonymous','c');
INSERT INTO quotes VALUES (83,'The National Transportation Safety Board recently divulged they had\r\ncovertly funded a project with the US auto makers for the past five years\r\nwhereby the auto makers were installing black boxes in four-wheel drive\r\npick-up trucks in an effort to determine, in fatal accidents, the\r\ncircumstances in the last 10 seconds before the crash.\r\n<br>They were surprised to find in 49 of the 50 states the last words of\r\ndrivers in 81.2 percent of fatal crashes were, \\\"Oh, Shit!\\\" Only the\r\nstate of Missouri was different, where 99.3 percent of the final words\r\nwere: \\\"Here, hold my beer and watch this!\\\"','Anonymous','c');
INSERT INTO quotes VALUES (84,'Dear Friends:<br>\r\n It is with the saddest heart that I have to pass on the following.  Please\r\njoin me in remembering a great icon. Veteran Pillsbury spokesperson, The\r\nPillsbury Doughboy, died  yesterday of a severe yeast infection and\r\ncomplications from  repeated pokes to\r\n the belly. He was 71.    Doughboy was buried in a  slightly greased coffin.\r\n<br>\r\nDozens of celebrities turned out, including Mrs. Butterworth, the\r\nCalifornia Raisins,  Hungry Jack, Betty Crocker, the Hostess Twinkies,\r\nCaptain Crunch, and many others.\r\n<br>\r\nThe graveside was piled high with flours as longtime  friend, Aunt Jemima,\r\ndelivered the  eulogy, describing Doughboy as a man who \\\"never knew  how\r\nmuch he was kneaded.\\\"\r\n<br>\r\nDoughboy rose quickly in show business but his later  life was filled with\r\nmany turnovers.   He was not considered a very smart cookie, wasting  much\r\nof his dough on half-baked  schemes. Despite being a little flaky at times,\r\nhe even still, as a crusty old man, was considered a roll model for\r\nmillions.  Toward the end it was thought he\\\'d rise once again,  but he was\r\nno tart.\r\n<br>\r\nDoughboy is survived by his second wife, Play Dough.   They have two\r\nchildren and one in  the oven. The funeral was held at 3:50 for about 20\r\nminutes.','Anonymous','j');
INSERT INTO quotes VALUES (85,'Alternate State Mottos:<br>\r\nAlabama: Yes, we have electricity\r\n<br>Arizona: But It\\\'s a Dry Heat\r\n<br>Arkansas: Litterasy Ain\\\'t Everthing\r\n<br>California: As Seen on TV \\\"Sin, Sex, Sun and Fun\\\"\r\n<br>Colorado: If You Don\\\'t Ski, Don\\\'t Bother\r\n<br>Connecticut: Like Massachusetts, Only Dirtier and With Less Character\r\n<br>Delaware: We Really Do Like the Chemicals in Our Water\r\n<br>Florida: Ask Us About Our Grandkids','Anonymous','j');
INSERT INTO quotes VALUES (86,'Alternate State Mottos:<br>\r\nGeorgia: We Put the \\\"Fun\\\" in Fundamentalist Extremism\r\n<br>Hawaii: Haka Tiki Mou Sha\\\'ami Leeki Toru  - Mahalo\r\n (Death to Mainland Scum, But Leave Your Money, Thank You.)\r\n<br>Idaho: Potatoes and Neo Nazi\\\'s ... What More Could You Ask For?\r\n<br>Illinois: Please Don\\\'t Pronounce the \\\"S\\\"\r\n<br>Indiana: 2 Billion Years Tidal Wave Free\r\n<br>Iowa: We Do Amazing Things With Corn\r\n<br>Kansas: Where Science Don\\\'t Mean Nothing\r\n<br>Kentucky: Five Million People; Fifteen Last Names','Anonymous','j');
INSERT INTO quotes VALUES (87,'Alternate State Mottos:<br>\r\nLouisiana: We\\\'re Not All Drunk Cajun Wackos, But That\\\'s Our Tourism\r\nCampaign\r\n<br>Maine: We\\\'re Really Cold, But We Have Cheap Lobster\r\n<br>Maryland: Thinking Man\\\'s Delaware\r\n<br>Massachusetts: Our Taxes Are Lower Than Sweden\\\'s (For Most Tax Brackets)\r\n<br>Michigan: First Line of Defense From the Canadians\r\n<br>Minnesota: 10,000 Lakes and 10,000,000,000,000,000,000 Mosquitoes\r\n<br>Mississippi: Come Feel Better About Your Own State\r\n<br>Missouri: Your Federal Flood Relief Tax Dollars at Work, Let Me Show\r\nYou!!!','Anonymous','j');
INSERT INTO quotes VALUES (88,'Alternate State Mottos:<br>\r\nMontana: Land of the Big Sky, the Unabomber, Right-Wing Crazies,\r\n Left-Wing Kooks and Very Little Else\r\n<br>Nebraska: Ask About Our State Motto Contest - Where\\\'s the beef?\r\n<br>Nevada: Hookers and Poker!\r\n<br>New Hampshire: Go Away and Leave Us Alone\r\n<br>New Jersey: You Want a ##$%##! Motto? I Got Yer ##$%##! Motto Right\r\nHere!\r\n<br>New Mexico: Lizards Make Excellent Pets\r\n<br>New York: You Have the Right to Remain Silent, You Have the Right to an\r\nAttorney...\r\n<br>North Carolina: Tobacco is a Vegetable','Anonymous','j');
INSERT INTO quotes VALUES (89,'Alternative State Mottos:<br>\r\nNorth Dakota: We Really are One of the 50 States!\r\n<br>Ohio: At Least We\\\'re Not Michigan - We\\\'re Buckeyes!\r\n<br>Oklahoma: Like the Play, only No Singing\r\n<br>Oregon: Spotted Owl... It\\\'s What\\\'s For Dinner\r\n<br>Pennsylvania: Cook With Coal\r\n<br>Rhode Island: We\\\'re Not REALLY An Island, really!\r\n<br>South Carolina: Remember the Civil War? We Didn\\\'t Actually Surrender\r\n<br>South Dakota: Closer Than North Dakota\r\n<br>Tennessee: The Educashun State','Anonymous','j');
INSERT INTO quotes VALUES (90,'Alternate State Mottos:<br>\r\nTexas: Si\\\' Hablo Ing\\\'les\r\n<br>Utah: Our Jesus Is Better Than Your Jesus\r\n<br>Vermont: Yep\r\n<br>Virginia: Who Says Government Stiffs and Slackjaw Yokels Don\\\'t Mix?\r\n<br>Washington: Help! We\\\'re Overrun By Nerds, Liberals and other Slackers!\r\n<br>Washington, D.C.: Wanna Be Mayor?\r\n<br>West Virginia: One Big Happy Family-Really!\r\n<br>Wisconsin: Come Cut The Cheese\r\n<br>Wyoming: Where men are men and sheep are scared','Anonymous','j');
INSERT INTO quotes VALUES (91,'Do not meddle in the affairs of sysadmins for they are subtle and quick to anger.','Anonymous','f');
INSERT INTO quotes VALUES (92,'Free your mind and your ass will follow.','George Clinton','o');
INSERT INTO quotes VALUES (93,'If you don\\\'t like something, change it. If you can\\\'t change it, change the way you think about it!','Anonymous','f');
INSERT INTO quotes VALUES (94,'When you reach the end of your rope, tie a knot in it and hang on.','Thomas Jefferson','h');
INSERT INTO quotes VALUES (95,'640 K ought to be enough for anybody.','Bill Gates','o');
INSERT INTO quotes VALUES (96,'We\\\'ve got to pause and ask ourselves: How much clean air do we need?','-Lee Iacocca','o');
INSERT INTO quotes VALUES (97,'I was under medication when I made the decision to burn the tapes.','Richard Nixon','o');
INSERT INTO quotes VALUES (98,'A day without sunshine is like, you know, night.','Steve Martin','o');
INSERT INTO quotes VALUES (99,'It is wonderful to be here in the great state of Chicago...\r\n','Dan Quayle','o');
INSERT INTO quotes VALUES (100,'Caution: Cape does not enable user to fly.','Batman Costume Warning Label','o');
INSERT INTO quotes VALUES (101,'When you think about it, C-sections are an awful lot like Satanic rituals','Adam Carolla','o');
INSERT INTO quotes VALUES (102,'This generation may be the one that will face Armageddon.','Ronald Reagan, <b>People</b>, 1985','o');
INSERT INTO quotes VALUES (103,'I have made this letter longer than usual only because I have not had time to make it shorter.','Pascal','o');
INSERT INTO quotes VALUES (104,'When the rabbit of chaos is pursued by the ferret of disorder through the fields of anarchy, it is time to hang your pants\r\non the hook of darkness. Whether they are clean or not.','The Chief, <b>Spice World</b>','o');
INSERT INTO quotes VALUES (105,'The headless chicken can only know where he\\\'s been. He can\\\'t see where he\\\'s going.','The Chief, <b>Spice World</b>','o');
INSERT INTO quotes VALUES (110,'I think computer viruses should count as life. I think it says something\r\nabout human nature that the only form of life we have created so far is\r\npurely destructive. We\\\'ve created life in our own image.','Stephen Hawking','d');
INSERT INTO quotes VALUES (111,'Whenever you find yourself on the side of the majority, it\\\'s time to pause\r\nand reflect.','Mark Twain','d');
INSERT INTO quotes VALUES (112,'The tendency of democracies is, in all things, to mediocrity.','James Fenimore Cooper','d');
INSERT INTO quotes VALUES (113,'The basis of optimism is sheer terror.','Oscar Wilde','d');
INSERT INTO quotes VALUES (114,'The best minds are not in government. If any were, business would steal\r\nthem away.','Ronald Reagan','d');
INSERT INTO quotes VALUES (115,'I believe everyody in the world should have guns. Citizens should have\r\nbazookas and rocket launchers too. I believe that all citizens should have\r\ntheir weapons of choice. However, I also believe that only I should have the\r\nammunition. Because frankly, I wouldn\\\'t trust the rest of the goobers with\r\nanything more dangerous than string.','Scott Adams','d');
INSERT INTO quotes VALUES (116,'Only two things are infinite, the universe and human stupidity, and I\\\'m not\r\nsure about the former.','Albert Einstein','d');
INSERT INTO quotes VALUES (117,'One of the penalties for refusing to participate in politics is that you\r\nend up being governed by your inferiors.','Plato','d');
INSERT INTO quotes VALUES (118,'Bush Sr. was a jerk, Quayle an idiot, Clinton was atrocious and disgusting,\r\nmost of those who persecuted him were hypocritical, Gore is shallow and\r\nweak, Bradley is an idealist, Bush Jr. a fool, and all of the independent\r\ncandidates act like they\\\'re on drugs.','David Borenstein, on politics in the 1990\\\'s','d');
INSERT INTO quotes VALUES (119,'The whole aim of practical politics is to keep the populace alarmed (and\r\nhence clamorous to be led to safety) by menacing it with an endless series\r\nof hobgoblins, all of them imaginary.','H.L. Mencken','d');
INSERT INTO quotes VALUES (120,'Anyone that wants the presidency so much that he\\\'ll spend two years\r\norganizing and campaigning for it is not to be trusted with the office.','David Broder','d');
INSERT INTO quotes VALUES (121,'Was it a dream where you see yourself standing in sort of sun god robes on a pyramid with a thousand naked women screaming and throwing little pickles at you?','Someone in <b>Real Genius</b>','o');
INSERT INTO quotes VALUES (122,'It\\\'s no exaggeration to say that the undecideds could go one way or\r\nanother.','George Bush, U.S. President','o');
INSERT INTO quotes VALUES (123,'I have opinions of my own -- strong opinions --but I don\\\'t always agree\r\nwith them.','George Bush, U.S. President','o');
INSERT INTO quotes VALUES (124,'If we don\\\'t succeed, we run the risk of failure.','Dan Quayle','o');
INSERT INTO quotes VALUES (125,'It isn\\\'t pollution that\\\'s harming the environment. It\\\'s the impurities in\r\nour air and water that are doing it.','Dan Quayle','o');
INSERT INTO quotes VALUES (126,'This is like deja vu all over again.','Yogi Berra','o');
INSERT INTO quotes VALUES (127,'Where am I going?  And why am I in this handbasket?','Anonymous','o');
INSERT INTO quotes VALUES (128,'I don\\\'t suffer from insanity; I enjoy every minute of it.','Anonymous','d');
INSERT INTO quotes VALUES (129,'\\\"Only ideas that we actually live by are of any value\\\".\r\n\r\n\r\n\r\n','- Hermann Hesse','h');
INSERT INTO quotes VALUES (130,'The large print giveth, and the small print taketh away.','Tom Waits','o');
INSERT INTO quotes VALUES (131,'Less than 30% of Americans vote, and most of them are drunk.','Steve Corell','o');
INSERT INTO quotes VALUES (132,'Every day, once a day, give yourself a present. Don\\\'t plan it, don\\\'t wait for it, just let it happen. It could be a new shirt at the men\\\'s store, a cat nap in your office chair or two cups of good, hot, black coffee.','Special Agent Dale Cooper','o');
INSERT INTO quotes VALUES (133,'Shut your eyes and you\\\'ll burst into flames.','Margaret, the Log Lady','o');
INSERT INTO quotes VALUES (134,'When did you start smoking?<br>\r\n<i>- James</i><br>\r\nI smoke every once in a while. It relieves tension.<br>\r\n<i>- Donna</i><br>\r\nWhen did you get so tense?<br>\r\n<i>- James</i><br>\r\nWhen I started smoking.','Donna','o');
INSERT INTO quotes VALUES (135,'I would like, in general, to treat people with much more care and respect. I would like to climb a tall hill -- but not too tall, sit in the cool grass -- but not too cool, and feel the sun on my face. I wish I could have cracked the Lindberg kidnapping case. I would very much like to make love to a beautiful woman who I had a genuine affection for. And, of course, it goes without saying that I would like to visit Tibet.','Special Agent Dale Cooper','o');
INSERT INTO quotes VALUES (136,'The owls are not what they seem.','Anonymous','f');
INSERT INTO quotes VALUES (137,'Without chemicals, he points.','Anonymous','f');
INSERT INTO quotes VALUES (138,'...the naked, spotless intellect is like a transparent vacuum without circumference or center.','Special Agent Dale Cooper','o');

