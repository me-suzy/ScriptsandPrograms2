# MySQL-Front Dump 2.5
#
# Host: localhost   Database: leads4web
# --------------------------------------------------------
# Server version 4.0.21-debug


CREATE TABLE ###TABLE_PREFIX###authorize (
  tree_id int(11) NOT NULL default 0,
  user_id int(11) NOT NULL default 0
) TYPE=MyISAM;

CREATE TABLE ###TABLE_PREFIX###categories (
  id               int(11) NOT NULL auto_increment,
  mandator         int(11) NOT NULL default 1,
  object_type      varchar(20) NOT NULL,
  grp              int(11)     NOT NULL default '0',
  name             varchar(100) default NULL,
  PRIMARY KEY  (id)
) TYPE=MyISAM;


#CREATE TABLE ###TABLE_PREFIX###companies (
#  company_id int(11) NOT NULL auto_increment,
#  owner int(11) NOT NULL default '0',
#  grp int(11) NOT NULL default '0',
#  group_read enum('true','false') NOT NULL default 'true',
#  group_write enum('true','false') NOT NULL default 'false',
#  status varchar(20) NOT NULL default 'valid',
#  name1 varchar(60) default NULL,
#  name2 varchar(60) default NULL,
#  name3 varchar(60) default NULL,
#  email varchar(50) default NULL,
#  lead_scoring int(11) NOT NULL default '1',
#  mitarbeiter varchar(40) default NULL,
#  umsatz varchar(40) default NULL,
#  strasse varchar(50) default NULL,
#  land int(11) NOT NULL default '1',
#  plz varchar(10) default NULL,
#  ort varchar(50) default NULL,
#  postfach varchar(50) default NULL,
#  plz_postfach varchar(50) default NULL,
#  ort_postfach varchar(50) default NULL,
#  zentrale varchar(40) default NULL,
#  tel_identifier varchar(40) default '',
#  fax_zentrale varchar(40) default NULL,
#  homepage varchar(60) default NULL,
#  branche int(11) NOT NULL default '1',
#  bemerkung text,
#  internal_use1 varchar(20) default '',
#  internal_use2 varchar(20) default '',
#  internal_use3 varchar(20) default '',
#  PRIMARY KEY  (company_id)
#) TYPE=MyISAM;

CREATE TABLE ###TABLE_PREFIX###components (
  id             int(11)      NOT NULL auto_increment,
  mandator       int(11) NOT NULL default 1,
  module_name    varchar(100) NOT NULL default '',
  module_type    varchar(10)  NOT NULL default 'core',
  version_main   int(11)      NOT NULL default 0,
  version_sub    int(11)      NOT NULL default 0,
  version_detail int(11)      NOT NULL default 1,
  index_url      varchar(100) NOT NULL default 'http://',
  new_window     varchar(1)   NOT NULL default '0',
  enabled        varchar(1)   NOT NULL default '1',
  dependencies   varchar(20)  NOT NULL default '',
  UNIQUE KEY id (id)
) TYPE=MyISAM;


#
# Table structure for table 'contacts'
#

CREATE TABLE ###TABLE_PREFIX###contacts (
  contact_id            int(11)          NOT NULL auto_increment,
  salutation            varchar(10)      NOT NULL default '',
  salutation_letter     varchar(100)     NOT NULL default '',
  title                 varchar(40)               default '',
  firstname             varchar(50)               default '',
  lastname              varchar(50)               default '',
  email                 varchar(60)               default '',
  company               int(11)          NOT NULL default 0,
  department            varchar(50)               default '',
  function              varchar(50)               default '',
  phone_private1        varchar(30)               default '',
  phone_private2        varchar(30)               default '',
  mobile_phone          varchar(30)               default '',
  phone_company1        varchar(30)               default '',
  phone_company2        varchar(30)               default '',
  fax                   varchar(30)               default '',
  street                varchar(100)              default '',
  zipcode               varchar(10)               default '',
  city                  varchar(100)              default '',
  country               int(8)                    default 1,
  homepage              varchar(100)              default '',
  birthday              date                      default NULL,
  further_emails        text,
  category              int(11)          NOT NULL default '',
  remark                text,
  freetext1             varchar(50),
  freetext2             varchar(50),
  freetext3             varchar(50),  
  system_msg            varchar(100),
  system_msg_date       datetime,
  identity_hash         int(11)          NOT NULL default '0',
  PRIMARY KEY  (contact_id),
  KEY lastname (lastname)
) TYPE=InnoDB;


CREATE TABLE ###TABLE_PREFIX###countries (
  id      int(8)      unsigned NOT NULL auto_increment,
  country varchar(40)          NOT NULL default '',
  code    varchar(10)          NOT NULL default '',
  short   varchar(5)           NOT NULL default '',
  PRIMARY KEY  (id)
) TYPE=MyISAM;


#
# Table structure for table 'filter'
#

#CREATE TABLE ###TABLE_PREFIX###filter (
#  id int(11) NOT NULL auto_increment,
#  text text NOT NULL,
#  was enum('betreff','von') NOT NULL default 'betreff',
#  wo enum('starts','contains') NOT NULL default 'starts',
#  todo enum('moved','deleted') NOT NULL default 'moved',
#  owner int(11) NOT NULL default '0',
#  priority int(11) NOT NULL default '1',
#  condition text NOT NULL,
#  action text NOT NULL,
#  KEY id (id)
#) TYPE=MyISAM;

#
# Table structure for table 'gacl_acl'
#

CREATE TABLE ###TABLE_PREFIX###gacl_acl (
  id int(11) NOT NULL default '0',
  section_value varchar(230) NOT NULL default 'system',
  allow int(11) NOT NULL default '0',
  enabled int(11) NOT NULL default '0',
  return_value longtext,
  note longtext,
  updated_date int(11) NOT NULL default '0',
  PRIMARY KEY  (id),
  KEY gacl_enabled_acl (enabled),
  KEY gacl_section_value_acl (section_value),
  KEY gacl_updated_date_acl (updated_date)
) TYPE=MyISAM;



#
# Table structure for table 'gacl_acl_sections'
#

CREATE TABLE ###TABLE_PREFIX###gacl_acl_sections (
  id int(11) NOT NULL default '0',
  value varchar(230) NOT NULL default '',
  order_value int(11) NOT NULL default '0',
  name varchar(230) NOT NULL default '',
  hidden int(11) NOT NULL default '0',
  PRIMARY KEY  (id),
  UNIQUE KEY gacl_value_acl_sections (value),
  KEY gacl_hidden_acl_sections (hidden)
) TYPE=MyISAM;



#
# Table structure for table 'gacl_acl_seq'
#

CREATE TABLE ###TABLE_PREFIX###gacl_acl_seq (
  id int(11) NOT NULL default '0'
) TYPE=MyISAM;



#
# Table structure for table 'gacl_aco'
#

CREATE TABLE ###TABLE_PREFIX###gacl_aco (
  id int(11) NOT NULL default '0',
  section_value varchar(240) NOT NULL default '0',
  value varchar(240) NOT NULL default '',
  order_value int(11) NOT NULL default '0',
  name varchar(255) NOT NULL default '',
  hidden int(11) NOT NULL default '0',
  PRIMARY KEY  (id),
  UNIQUE KEY gacl_section_value_value_aco (section_value,value),
  KEY gacl_hidden_aco (hidden)
) TYPE=MyISAM;



#
# Table structure for table 'gacl_aco_map'
#

CREATE TABLE ###TABLE_PREFIX###gacl_aco_map (
  acl_id int(11) NOT NULL default '0',
  section_value varchar(230) NOT NULL default '0',
  value varchar(230) NOT NULL default '',
  PRIMARY KEY  (acl_id,section_value,value)
) TYPE=MyISAM;



#
# Table structure for table 'gacl_aco_sections'
#

CREATE TABLE ###TABLE_PREFIX###gacl_aco_sections (
  id int(11) NOT NULL default '0',
  value varchar(230) NOT NULL default '',
  order_value int(11) NOT NULL default '0',
  name varchar(230) NOT NULL default '',
  hidden int(11) NOT NULL default '0',
  PRIMARY KEY  (id),
  UNIQUE KEY gacl_value_aco_sections (value),
  KEY gacl_hidden_aco_sections (hidden)
) TYPE=MyISAM;



#
# Table structure for table 'gacl_aco_sections_seq'
#

CREATE TABLE ###TABLE_PREFIX###gacl_aco_sections_seq (
  id int(11) NOT NULL default '0'
) TYPE=MyISAM;



#
# Table structure for table 'gacl_aco_seq'
#

CREATE TABLE ###TABLE_PREFIX###gacl_aco_seq (
  id int(11) NOT NULL default '0'
) TYPE=MyISAM;



#
# Table structure for table 'gacl_aro'
#

CREATE TABLE ###TABLE_PREFIX###gacl_aro (
  id int(11) NOT NULL default '0',
  section_value varchar(240) NOT NULL default '0',
  value varchar(240) NOT NULL default '',
  order_value int(11) NOT NULL default '0',
  name varchar(255) NOT NULL default '',
  hidden int(11) NOT NULL default '0',
  PRIMARY KEY  (id),
  UNIQUE KEY gacl_section_value_value_aro (section_value,value),
  KEY gacl_hidden_aro (hidden)
) TYPE=MyISAM;



#
# Table structure for table 'gacl_aro_groups'
#

CREATE TABLE ###TABLE_PREFIX###gacl_aro_groups (
  id int(11) NOT NULL default '0',
  parent_id int(11) NOT NULL default '0',
  lft int(11) NOT NULL default '0',
  rgt int(11) NOT NULL default '0',
  name varchar(255) NOT NULL default '',
  value varchar(255) NOT NULL default '',
  PRIMARY KEY  (id,value),
  UNIQUE KEY gacl_value_aro_groups (value),
  KEY gacl_parent_id_aro_groups (parent_id),
  KEY gacl_lft_rgt_aro_groups (lft,rgt)
) TYPE=MyISAM;



#
# Table structure for table 'gacl_aro_groups_id_seq'
#

CREATE TABLE ###TABLE_PREFIX###gacl_aro_groups_id_seq (
  id int(11) NOT NULL default '0'
) TYPE=MyISAM;



#
# Table structure for table 'gacl_aro_groups_map'
#

CREATE TABLE ###TABLE_PREFIX###gacl_aro_groups_map (
  acl_id int(11) NOT NULL default '0',
  group_id int(11) NOT NULL default '0',
  PRIMARY KEY  (acl_id,group_id)
) TYPE=MyISAM;



#
# Table structure for table 'gacl_aro_map'
#

CREATE TABLE ###TABLE_PREFIX###gacl_aro_map (
  acl_id int(11) NOT NULL default '0',
  section_value varchar(230) NOT NULL default '0',
  value varchar(230) NOT NULL default '',
  PRIMARY KEY  (acl_id,section_value,value)
) TYPE=MyISAM;



#
# Table structure for table 'gacl_aro_sections'
#

CREATE TABLE ###TABLE_PREFIX###gacl_aro_sections (
  id int(11) NOT NULL default '0',
  value varchar(230) NOT NULL default '',
  order_value int(11) NOT NULL default '0',
  name varchar(230) NOT NULL default '',
  hidden int(11) NOT NULL default '0',
  PRIMARY KEY  (id),
  UNIQUE KEY gacl_value_aro_sections (value),
  KEY gacl_hidden_aro_sections (hidden)
) TYPE=MyISAM;



#
# Table structure for table 'gacl_aro_sections_seq'
#

CREATE TABLE ###TABLE_PREFIX###gacl_aro_sections_seq (
  id int(11) NOT NULL default '0'
) TYPE=MyISAM;



#
# Table structure for table 'gacl_aro_seq'
#

CREATE TABLE ###TABLE_PREFIX###gacl_aro_seq (
  id int(11) NOT NULL default '0'
) TYPE=MyISAM;



#
# Table structure for table 'gacl_axo'
#

CREATE TABLE ###TABLE_PREFIX###gacl_axo (
  id int(11) NOT NULL default '0',
  section_value varchar(240) NOT NULL default '0',
  value varchar(240) NOT NULL default '',
  order_value int(11) NOT NULL default '0',
  name varchar(255) NOT NULL default '',
  hidden int(11) NOT NULL default '0',
  PRIMARY KEY  (id),
  UNIQUE KEY gacl_section_value_value_axo (section_value,value),
  KEY gacl_hidden_axo (hidden)
) TYPE=MyISAM;



#
# Table structure for table 'gacl_axo_groups'
#

CREATE TABLE ###TABLE_PREFIX###gacl_axo_groups (
  id int(11) NOT NULL default '0',
  parent_id int(11) NOT NULL default '0',
  lft int(11) NOT NULL default '0',
  rgt int(11) NOT NULL default '0',
  name varchar(255) NOT NULL default '',
  value varchar(255) NOT NULL default '',
  PRIMARY KEY  (id,value),
  UNIQUE KEY gacl_value_axo_groups (value),
  KEY gacl_parent_id_axo_groups (parent_id),
  KEY gacl_lft_rgt_axo_groups (lft,rgt)
) TYPE=MyISAM;



#
# Table structure for table 'gacl_axo_groups_map'
#

CREATE TABLE ###TABLE_PREFIX###gacl_axo_groups_map (
  acl_id int(11) NOT NULL default '0',
  group_id int(11) NOT NULL default '0',
  PRIMARY KEY  (acl_id,group_id)
) TYPE=MyISAM;



#
# Table structure for table 'gacl_axo_map'
#

CREATE TABLE ###TABLE_PREFIX###gacl_axo_map (
  acl_id int(11) NOT NULL default '0',
  section_value varchar(230) NOT NULL default '0',
  value varchar(230) NOT NULL default '',
  PRIMARY KEY  (acl_id,section_value,value)
) TYPE=MyISAM;



#
# Table structure for table 'gacl_axo_sections'
#

CREATE TABLE ###TABLE_PREFIX###gacl_axo_sections (
  id int(11) NOT NULL default '0',
  value varchar(230) NOT NULL default '',
  order_value int(11) NOT NULL default '0',
  name varchar(230) NOT NULL default '',
  hidden int(11) NOT NULL default '0',
  PRIMARY KEY  (id),
  UNIQUE KEY gacl_value_axo_sections (value),
  KEY gacl_hidden_axo_sections (hidden)
) TYPE=MyISAM;



#
# Table structure for table 'gacl_groups_aro_map'
#

CREATE TABLE ###TABLE_PREFIX###gacl_groups_aro_map (
  group_id int(11) NOT NULL default '0',
  aro_id int(11) NOT NULL default '0',
  PRIMARY KEY  (group_id,aro_id)
) TYPE=MyISAM;



#
# Table structure for table 'gacl_groups_axo_map'
#

CREATE TABLE ###TABLE_PREFIX###gacl_groups_axo_map (
  group_id int(11) NOT NULL default '0',
  axo_id int(11) NOT NULL default '0',
  PRIMARY KEY  (group_id,axo_id)
) TYPE=MyISAM;



#
# Table structure for table 'gacl_phpgacl'
#

CREATE TABLE ###TABLE_PREFIX###gacl_phpgacl (
  name varchar(230) NOT NULL default '',
  value varchar(230) NOT NULL default '',
  PRIMARY KEY  (name)
) TYPE=MyISAM;

CREATE TABLE ###TABLE_PREFIX###groups_deleted (
  id             int(11),
  description  varchar(100) NOT NULL default '',
  deleted        datetime                    default '0000-00-00 00:00:00'
) TYPE=MyISAM;

#
# Table structure for table 'history'
#

CREATE TABLE ###TABLE_PREFIX###history (
  object_type    varchar(20) NOT NULL,
  object_id      int(11)     NOT NULL,
  user_id        int(5)      NOT NULL default '0',
  tstamp         timestamp (14),
  col            varchar(50) NOT NULL,
  old_value      varchar(50),
  new_value      varchar(50),
  KEY  (object_type,object_id),
  KEY user_id (user_id)
) TYPE=MyISAM;



#
# Table structure for table 'intern_news'
#

#CREATE TABLE ###TABLE_PREFIX###intern_news (
#  news_id int(11) NOT NULL auto_increment,
#  anleger int(11) NOT NULL default '0',
#  owner int(11) NOT NULL default '0',
#  grp int(11) NOT NULL default '0',
#  group_read enum('true','false') NOT NULL default 'false',
#  group_write enum('true','false') NOT NULL default 'false',
#  headline varchar(80) default NULL,
#  news text,
#  datum datetime NOT NULL default '0000-00-00 00:00:00',
#  vorlage date NOT NULL default '0000-00-00',
#  gelesen enum('true','false') NOT NULL default 'false',
#  sentto tinytext,
#  PRIMARY KEY  (news_id)
#) TYPE=MyISAM;


#
# Table structure for table 'leads'
#

#CREATE TABLE ###TABLE_PREFIX###leads (
#  lead_id int(11) NOT NULL auto_increment,
#  lead_scoring int(11) default NULL,
#  campaign int(11) NOT NULL default '0',
#  firma int(11) default NULL,
#  bemerkung text,
#  main_contact int(11) default NULL,
#  more_contacts varchar(100) default NULL,
#  datum datetime NOT NULL default '0000-00-00 00:00:00',
#  PRIMARY KEY  (lead_id)
#) TYPE=MyISAM;



#
# Table structure for table 'leads_liste'
#

#CREATE TABLE ###TABLE_PREFIX###leads_liste (
#  id int(11) NOT NULL auto_increment,
#  name varchar(50) default NULL,
#  grp int(11) NOT NULL default '0',
#  PRIMARY KEY  (id)
#) TYPE=MyISAM;



#
# Table structure for table 'logtable'
#

#CREATE TABLE ###TABLE_PREFIX###logtable (
#  id int(11) NOT NULL auto_increment,
#  predecessor int(11) NOT NULL default '0',
#  ts timestamp(14) NOT NULL,
#  ident varchar(16) NOT NULL default 'undefined',
#  category varchar(16) NOT NULL default 'undefined',
#  message varchar(200) default NULL,
#  PRIMARY KEY  (id),
#  UNIQUE KEY id (id)
#) TYPE=MyISAM;


# done in percent
CREATE TABLE ###TABLE_PREFIX###memos (
  memo_id          int(11)     NOT NULL auto_increment,
  is_dir 		   char(1)     NOT NULL default '0',
  parent           int(11)     NOT NULL default '0',
  headline         varchar(50),
  content          text,
  followup         date,
  due              date,
  starts           date,
  done             int(3)      NOT NULL default  '0',
  state            int(11)     NOT NULL default '0',
  priority         int(3)      NOT NULL default '1',
  KEY parent_key (parent),
  PRIMARY KEY  (memo_id)
) TYPE=MyISAM;
  

#
# Table structure for table 'page_stats'
#

CREATE TABLE ###TABLE_PREFIX###page_stats (
  user     int(11)      NOT NULL default '0',
  mandator int(11)      NOT NULL default 1,
  page     varchar(40)  NOT NULL default '',
  day      varchar(8)   NOT NULL,
  month    varchar(6)   NOT NULL,
  year     varchar(4)   NOT NULL,
  counter  int(11)      NOT NULL default '0',
  PRIMARY KEY  (user,page,day,month,year)
) TYPE=MyISAM;

#
# Table structure for table 'quicklinks'
#

CREATE TABLE ###TABLE_PREFIX###quicklinks (
  object_type  varchar(20)  NOT NULL,
  object_id    int(11)      NOT NULL,
  owner        int(11)      NOT NULL default '0',
  name         varchar(50)  NOT NULL default '',
  link         varchar(100) NOT NULL default '',
  sticky       varchar(1)   NOT NULL default '0',
  followup     datetime,
  PRIMARY KEY  (object_type, object_id)
) TYPE=MyISAM;



#
# Table structure for table 'rel_user_modules'
#

#CREATE TABLE ###TABLE_PREFIX###rel_user_modules (
#  user int(11) default '0',
#  module varchar(11) default '0'
#) TYPE=MyISAM;

#
# Table structure for table 'skins'
#

CREATE TABLE ###TABLE_PREFIX###skins (
  id       int(11) NOT NULL default '0',
  mandator int(11) NOT NULL default 1,
  name varchar(30) default NULL,
  img_path varchar(30) NOT NULL default '',
  css_path varchar(30) NOT NULL default '',
  PRIMARY KEY  (id),
  UNIQUE KEY id (id),
  KEY id_2 (id)
) TYPE=MyISAM;

CREATE TABLE ###TABLE_PREFIX###languages (
  lang_id       int(5)      NOT NULL auto_increment,
  language      varchar(20) NOT NULL default '',
  set_local_str char(3)     NOT NULL default 'ger',
  aktiv         varchar(1)  NOT NULL default '0',
  order_nr      int(11)              default '1',
  filename      varchar(12) NOT NULL default '',
  loaded_in_db  char(1)     NOT NULL default '0',
  path          varchar(30) NOT NULL default '',
  PRIMARY KEY  (lang_id)
) TYPE=MyISAM;


CREATE TABLE ###TABLE_PREFIX###states (
  mandator	  int(11)     NOT NULL default 1,
  reference   varchar(50)  NOT NULL default '',
  status      int(11)      NOT NULL,
  name        varchar(100) NOT NULL default '',
  color       varchar(7)   NOT NULL default '#ffffff',
  startpoint  char(1)      NOT NULL default '0',
  endpoint    char(1)      NOT NULL default '0',
  description varchar(50),  
  UNIQUE KEY  (mandator, reference, status)
) TYPE=MyISAM;

#
# Table structure for table 'stats'
#

CREATE TABLE ###TABLE_PREFIX###stats (
  user int(11) unsigned NOT NULL default '0',
  grp int(11) unsigned NOT NULL default '0',
  timestamp date NOT NULL default '0000-00-00',
  entry_type varchar(30) NOT NULL default '',
  assigned_entry int(11) NOT NULL default '0'
) TYPE=MyISAM;


CREATE TABLE ###TABLE_PREFIX###transitions (
    mandator	int(11)      NOT NULL default 1,
    reference   varchar(50)  NOT NULL default '',
    grp         int(11)      NOT NULL default 0,
    user        int(11)      NOT NULL default 0,
    state_old   int(11)      NOT NULL,
    state_new   int(11)      NOT NULL,
    name        varchar(100) NOT NULL default '',
    isdefault   varchar(1)   NOT NULL default '0',
    UNIQUE KEY (mandator, reference, grp, user, state_old, state_new)
) TYPE=MyISAM;


CREATE TABLE ###TABLE_PREFIX###tree (
  id                 int(11)         NOT NULL auto_increment,
  parent             int(11) NOT NULL default '0',
  name               varchar(50) NOT NULL default '',
  link               varchar(200) NOT NULL default '',
  frame              varchar(50) NOT NULL default '',
  img                varchar(100) NOT NULL default '',
  sign               varchar(10) NOT NULL default '',
  order_nr           int(11) NOT NULL default '0',
  subtree_identifier varchar(20) default NULL,
  translate          char(1) NOT NULL default '0',
  enabled            char(1) NOT NULL default '1',
  authorize          char(1) NOT NULL default '0',
  protected          char(1) NOT NULL default '0',
  visible_for_guest  char(1) NOT NULL default '1',
  UNIQUE KEY id (id)
) TYPE=MyISAM;


#
# Table structure for table 'user_details'
#

CREATE TABLE ###TABLE_PREFIX###user_details (
  user_id             int(11)     NOT NULL auto_increment,
  login_count         int(11)     NOT NULL default '0',
  last_login          datetime             default '0000-00-00 00:00:00',
  created             date        NOT NULL default '0000-00-00',
  created_by          int(11)     NOT NULL default '0',
  compression         varchar(1)  NOT NULL default '0',
  mailcheck           varchar(1)  NOT NULL default '0',
  may_change_profile  char(1)     NOT NULL default '1',
  skin                int(11)     NOT NULL default '4',
  lang                int(11)     NOT NULL default '2',
  current_tree        text,
  db_query_serialized blob,
  default_group       int(11)     NOT NULL default 0,
  default_access      varchar(10) NOT NULL default '-rwx------',
  jabber_id           varchar(100),
  jabber_pass         varchar(100),
  navigation          varchar(20) NOT NULL default 'tree',
  PRIMARY KEY  (user_id),
  UNIQUE KEY user_id (user_id)
) TYPE=MyISAM;



#
# Table structure for table 'useronline'
#

CREATE TABLE ###TABLE_PREFIX###useronline (
  timestamp   int(15)      NOT NULL default '0',
  user_id     int(11)      NOT NULL default '0',
  object_type varchar(100) NOT NULL default '',
  object_id   int(11)      NOT NULL default '0',
  UNIQUE KEY (user_id, object_type, object_id)
) TYPE=MyISAM;



#
# Table structure for table 'users'
#

CREATE TABLE ###TABLE_PREFIX###users (
  id             int(11)            NOT NULL auto_increment,
  login          varchar(50) binary NOT NULL default '',
  password       varchar(50)        NOT NULL default '',
  grp            int(11)            NOT NULL default '0',
  salutation     varchar(10)                 default NULL,
  firstname      varchar(50)                 default NULL,
  lastname       varchar(50)                 default NULL,
  email          varchar(60)                 default NULL,
  PRIMARY KEY  (id),
  UNIQUE  KEY  user_id (id),
  UNIQUE  KEY  login_key (login),
  KEY login (login)
) TYPE=InnoDB;

CREATE TABLE ###TABLE_PREFIX###users_deleted (
  id             int(11),
  login          varchar(50) binary NOT NULL default '',
  grp            int(11)            NOT NULL default '0',
  salutation     varchar(10)                 default NULL,
  firstname      varchar(50)                 default NULL,
  lastname       varchar(50)                 default NULL,
  email          varchar(60)                 default NULL,
  login_count    int(11)            NOT NULL default '0',
  last_login     datetime                    default '0000-00-00 00:00:00',
  created        date NOT           NULL     default '0000-00-00',
  created_by     int(11)            NOT NULL default '0',
  deleted        datetime                    default '0000-00-00 00:00:00'
) TYPE=MyISAM;

CREATE TABLE ###TABLE_PREFIX###metainfo (
  object_type  varchar(20) NOT NULL,
  object_id    int(11)     NOT NULL,
  creator      int(11)     NOT NULL,
  owner        int(11)     NOT NULL,
  grp          int(11)     NOT NULL,
  state        int(11)     NOT NULL,
  ordernr      int(11)     NOT NULL default 0,
  created      datetime    NOT NULL,
  last_changer int(11),
  last_change  datetime,
  access_level varchar(10) NOT NULL default '-rwx------',
  INDEX        (owner),
  INDEX        (grp),
  PRIMARY KEY  (object_type, object_id),
  FOREIGN KEY (owner) REFERENCES ###TABLE_PREFIX###users(id)
) TYPE=InnoDB;


CREATE TABLE ###TABLE_PREFIX###events (
  event_id       int(11)      NOT NULL auto_increment,
  object_type    varchar(20)  NOT NULL,
  event          varchar(20)  NOT NULL,
  description    varchar(30),
  added_by       int(11)      NOT NULL default 0,
  added_date     datetime,
  event_type     varchar(20)  NOT NULL default 'system',
  template       varchar(20)  NOT NULL default '',
  subject        varchar(30)  NOT NULL default 'dummy subject',
  default_action int(11)      NOT NULL default 0,
  PRIMARY KEY  (event_id),
  UNIQUE KEY   (object_type, event)
) TYPE=InnoDB;

CREATE TABLE ###TABLE_PREFIX###actions (
  action_id     int(11)      NOT NULL auto_increment,
  name          varchar(20),
  user_function varchar(30),
  description   varchar(30),
  chooseable    char(1)       NOT NULL default '1',        
  PRIMARY KEY (action_id)
) TYPE=InnoDB;


CREATE TABLE ###TABLE_PREFIX###eventwatcher (
  watchlist_id     int(11)      NOT NULL auto_increment,
  watcher          int(11)      NOT NULL default 0,
  event_id         int(11)      NOT NULL,
  restrict_to_user int(11)      NOT NULL default 0,
  restrict_to_grp  int(11)      NOT NULL default 0,
  perform_action   int(11),
  PRIMARY KEY (watchlist_id),
  INDEX       (event_id),
  INDEX       (perform_action),
  FOREIGN KEY (event_id)         REFERENCES ###TABLE_PREFIX###events(event_id),
  FOREIGN KEY (perform_action)   REFERENCES ###TABLE_PREFIX###actions(action_id)
) TYPE=InnoDB;  

CREATE TABLE ###TABLE_PREFIX###news (
  news_id          int(11)      NOT NULL auto_increment,
  creator          int(11)      NOT NULL default 0,
  owner            int(11)      NOT NULL default 0,
  headline         varchar(100),
  created          datetime     NOT NULL,
  followup         date         NOT NULL default '0000-00-00',
  beenread         varchar(1)   NOT NULL DEFAULT '0',
  news             text,
  sentto           text,
  color            varchar(7)   NOT NULL DEFAULT '#ffffcc',
  perform_action   varchar(100),
  PRIMARY KEY (news_id),
  INDEX       (owner),
  FOREIGN KEY (owner)           REFERENCES ###TABLE_PREFIX###users(id)
) TYPE=InnoDB;  

CREATE TABLE ###TABLE_PREFIX###docs (
  doc_id                int(11)          NOT NULL auto_increment,
  is_dir                varchar(1)       NOT NULL default '0', 
  parent                int(11)          NOT NULL default '0',
  object_type           varchar(20),
  object_id             int(11),
  name                  varchar(50)      NOT NULL default '',
  fullpath              varchar(255)     NOT NULL default '',
  category              int(11)          NOT NULL default '',
  description           varchar(255)     NOT NULL default '',
  system_msg            varchar(100),
  system_msg_date       datetime,
  PRIMARY KEY    (doc_id),
  KEY parent_key (parent)
) TYPE=InnoDB;

CREATE TABLE ###TABLE_PREFIX###access_options (
  access_id             int(11)          NOT NULL auto_increment,
  mandator              int(11)          NOT NULL default 1,
  identifier            varchar(10)      NOT NULL, 
  name                  varchar(30)      NOT NULL,
  icon                  varchar(30),
  PRIMARY KEY    (access_id, mandator)
) TYPE=MyISAM;

CREATE TABLE ###TABLE_PREFIX###refering (
  from_object_type  varchar(20)  NOT NULL,
  from_object_id    int(11)      NOT NULL,
  to_object_type    varchar(20)  NOT NULL,
  to_object_id      int(11)      NOT NULL,
  ref_type          int(11)      NOT NULL default 1,
  ref_scheme        int(11)      NOT NULL default 0,
  ref_path          varchar(100) NOT NULL default '',
  description       varchar(50),
  UNIQUE KEY (from_object_type, from_object_id, to_object_type, to_object_id, ref_path)
) TYPE=MyISAM;

# actually "categories", not "collections"
CREATE TABLE ###TABLE_PREFIX###collections (
  collection_id    int(11)     NOT NULL auto_increment,
  mandator         int(11)     NOT NULL default 1,
  parent           int(11)     NOT NULL default 0,
  is_dir           char(1)     NOT NULL default '0',
  name             varchar(50),
  description      text,
  PRIMARY KEY  (mandator, collection_id)
) TYPE=MyISAM;

CREATE TABLE ###TABLE_PREFIX###category_component (
  component_id   int(11)     NOT NULL,
  category_id    int(11)     NOT NULL,
  PRIMARY KEY  (component_id, category_id)
) TYPE=MyISAM;

CREATE TABLE ###TABLE_PREFIX###sync (
  user_id           int(11)      NOT NULL,
  object_type       varchar(20)  NOT NULL,
  object_id         int(11)      NOT NULL,
  sync_with         varchar(5)   NOT NULL default '',
  remote_identifier varchar(150) NOT NULL,
  synced            datetime     NOT NULL,
  timeoffset        int(11)      NOT NULL default 0,
  status            varchar(15)  NOT NULL default '',
  PRIMARY KEY  (user_id, sync_with, object_type, object_id)
) TYPE=MyISAM;

CREATE TABLE ###TABLE_PREFIX###refering_types (
  type_id int(11)             NOT NULL,
  name           varchar(30)  NOT NULL,
  description    varchar(200)
) TYPE=MyISAM;

CREATE TABLE ###TABLE_PREFIX###tickets (
  ticket_id        int(11)     NOT NULL auto_increment,
  is_dir 		   char(1)     NOT NULL default '0',
  parent           int(11)     NOT NULL default '0',
  contact_id       int(11)     NOT NULL,
  theme            varchar(50),
  content          text,
  followup         date,
  due              date,
  starts           date,
  done             int(3)      NOT NULL default '0',
  state            int(11)     NOT NULL default '0',
  priority         int(3)      NOT NULL default '1',
  reminded         char(1)     NOT NULL default '0',
  PRIMARY KEY  (ticket_id),
  KEY contact_key (contact_id),
  KEY parent_key  (parent)
  #  FOREIGN KEY (contact_id)     REFERENCES ###TABLE_PREFIX###contacts(contact_id)
) TYPE=InnoDB;

CREATE TABLE ###TABLE_PREFIX###priorities (
  prio_id        int(11)       NOT NULL auto_increment,
  mandator       int(11)       NOT NULL default 1,
  name           varchar(30)   NOT NULL,
  description    varchar(200),
  translate      char(1)       NOT NULL default '0',
  order_nr       int(11)       NOT NULL default 1,
  color          char (7)      NOT NULL default '#000000',
  PRIMARY KEY (mandator, prio_id)
) TYPE=MyISAM;

CREATE TABLE ###TABLE_PREFIX###url_schemes (
  scheme_id      int(11)       NOT NULL auto_increment,
  scheme         varchar(15)   NOT NULL,
  description    varchar(200),
  order_nr       int(11)       NOT NULL default 1,
  PRIMARY KEY (scheme_id)
) TYPE=MyISAM;

CREATE TABLE ###TABLE_PREFIX###accounts (
  id                int(11)     NOT NULL    auto_increment,
  type              varchar(10) NOT NULL    default 'pop3',
  owner             int(11)     NOT NULL    default '0',
  host              varchar(50)             default NULL,
  port              int(11)     NOT NULL    default 110,
  use_ssl           char(1)     NOT NULL    default '0',
  active            char(1)     NOT NULL    default '1',
  login             varchar(50)             default NULL,
  pass              varchar(50)             default NULL,
  default_folder    int(11)     NOT NULL    default 1,
  PRIMARY KEY  (id),
  INDEX        (owner),
  FOREIGN KEY  (owner) REFERENCES ###TABLE_PREFIX###users(id)
) TYPE=InnoDB;

#
# Table structure for table 'folders'
#

CREATE TABLE ###TABLE_PREFIX###folders (
  id        int(11)     NOT NULL auto_increment,
  owner     int(11)     NOT NULL default '0',
  parent    int(11)     NOT NULL default 0,
  folder    varchar(50) NOT NULL default '',
  PRIMARY KEY id (id),
  INDEX       (owner),
  FOREIGN KEY (owner) REFERENCES ###TABLE_PREFIX###users(id)
) TYPE=InnoDB;

CREATE TABLE ###TABLE_PREFIX###emails (
  mail_id           int(11)     NOT NULL   auto_increment,
  master_id         int(11)     NOT NULL   default '0',
  owner             int(11)     NOT NULL   default '0',
  grp               int(11)     NOT NULL   default '0',
  access_level      varchar(10) NOT NULL   default '-rwx------',
  account           int(11)     NOT NULL   default '0',
  contact           int(11)                default NULL,
  unique_id         varchar(70) NOT NULL   default '',
  msg_nr            int(11)     NOT NULL   default '',
  folder            int(11)                default 0,
  sender            text,
  recipient         text,
  senddate          datetime    NOT NULL   default '0000-00-00 00:00:00',
  subject           varchar(60)            default NULL,
  size              int(11)     NOT NULL   default '0',
  header            blob        NOT NULL,
  attachment        char(1)     NOT NULL   default '0',
  deleted           char(1)     NOT NULL   default '0',
  beenread          char(1)     NOT NULL   default '0',
  new               char(1)     NOT NULL   default '0',
  prim_body_type    int(11)     NOT NULL   default '0',
  parse_result      text,
  filename          varchar(50)            default NULL,
  subtype           varchar(10) NOT NULL   default '',
  log               text,
  PRIMARY KEY  (mail_id),
  KEY owner    (owner)
) TYPE=MyISAM;

CREATE TABLE ###TABLE_PREFIX###translations (
  id            int(11)     NOT NULL auto_increment,
  lang_id       int(11)     NOT NULL,
  mykey         varchar(50) NOT NULL default '',
  translation   text,
  PRIMARY KEY  (id),
  UNIQUE KEY   (lang_id, mykey)
) TYPE=MyISAM;

CREATE TABLE ###TABLE_PREFIX###mandator (
  mandator_id      int(11)      NOT NULL auto_increment,
  name             varchar(50)  NOT NULL,
  tree_root        int(11)      NOT NULL default 0,
  group_root       int(11)      NOT NULL default 1,  
  description      varchar(200) NOT NULL default '',
  acl_inc_php      varchar(30)  NOT NULL default '',
  PRIMARY KEY  (mandator_id)
) TYPE=InnoDB;

CREATE TABLE ###TABLE_PREFIX###user_mandator (
  user_id          int(11)      NOT NULL,
  mandator_id      int(11)      NOT NULL,
  INDEX       (mandator_id),
  FOREIGN KEY (mandator_id) REFERENCES ###TABLE_PREFIX###mandator(mandator_id)
) TYPE=InnoDB;

CREATE TABLE ###TABLE_PREFIX###group_details (
  id           int(11)      NOT NULL auto_increment,
  mandator_id  int(11)      NOT NULL default 1,
  parent_id    int(11)      NOT NULL default 0,
  description  varchar(100) NOT NULL default '',
  PRIMARY KEY  (id),
  INDEX       (mandator_id),
  FOREIGN KEY (mandator_id) REFERENCES ###TABLE_PREFIX###mandator(mandator_id)
) TYPE=InnoDB;

CREATE TABLE ###TABLE_PREFIX###datagrids (
  datagrid_id      int(11)      NOT NULL auto_increment,
  mandator_id      int(11)      NOT NULL,
  name             varchar(50)  NOT NULL,
  aco_section      varchar(50)  NOT NULL default '',
  description      varchar(200) NOT NULL default '',
  searchButtonCol  int(11),
  PRIMARY KEY (datagrid_id),
  INDEX (mandator_id),
  FOREIGN KEY (mandator_id) REFERENCES ###TABLE_PREFIX###mandator (mandator_id)  
) TYPE=InnoDB;

CREATE TABLE ###TABLE_PREFIX###datagrid_columns (
  datagrid_id       int(11)      NOT NULL,
  column_id         int(11)      NOT NULL,
  column_identifier varchar(50)  NOT NULL,
  column_name       varchar(50)  NOT NULL,
  description       varchar(50)  NOT NULL default '',
  width             varchar(10),
  visible           char(1)      NOT NULL default '1',
  is_primary        char(1)      NOT NULL default '0',
  order_nr          int(11)      NOT NULL default 1,
  searchable        char(1)      NOT NULL default '0',  
  sortable          char(1)      NOT NULL default '1',  
  PRIMARY KEY (datagrid_id, column_id),
  INDEX(datagrid_id),
  FOREIGN KEY (datagrid_id) REFERENCES ###TABLE_PREFIX###datagrids (datagrid_id)
) TYPE=InnoDB;

CREATE TABLE ###TABLE_PREFIX###serialized_models (
  model_id       int(11)      NOT NULL auto_increment,
  mandator_id    int(11)      NOT NULL default 1,
  object_type    varchar(20)  NOT NULL,
  grp            int(11)      NOT NULL default 0,
  save_as        varchar(10)  NOT NULL default 'clipboard',
  name           varchar(30),
  user_id        int(11)      NOT NULL,
  ts             datetime,
  model          text,
  PRIMARY KEY  (model_id),
  INDEX (user_id),
  INDEX (mandator_id),
  FOREIGN KEY (mandator_id) REFERENCES ###TABLE_PREFIX###mandator (mandator_id),
  FOREIGN KEY (user_id) REFERENCES ###TABLE_PREFIX###users (id)
) TYPE=InnoDB;

CREATE TABLE ###TABLE_PREFIX###alt_email_addresses (
  contact_id       int(11)      NOT NULL,
  email            varchar(50)  NOT NULL default '',
  INDEX (contact_id),
  FOREIGN KEY (contact_id) REFERENCES ###TABLE_PREFIX###contacts (contact_id)  
) TYPE=InnoDB;

CREATE TABLE ###TABLE_PREFIX###update_app_stmts (
  tstamp datetime NOT NULL default '',
  version_main   int(11)      NOT NULL default 0,
  version_sub    int(11)      NOT NULL default 0,
  version_detail int(11)      NOT NULL default 1,
  stmt           text         NOT NULL default ''
) TYPE=MyISAM;