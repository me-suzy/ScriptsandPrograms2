ALTER TABLE tree ADD COLUMN visible_for_guest CHAR(1) NOT NULL default '1';

CREATE TABLE application (
  id           int(11)     NOT NULL    auto_increment,
  name         varchar(50)             default '',
  version      varchar(10)             default '',
  ts           datetime    NOT NULL,
  PRIMARY KEY (id)
);

CREATE TABLE folders (
  id        int(11)     NOT NULL auto_increment,
  owner     int(11)     NOT NULL default '0',
  parent    int(11)     NOT NULL default 0,
  folder    varchar(50) NOT NULL default '',
  PRIMARY KEY id (id),
  INDEX          (owner),
  FOREIGN KEY (owner) REFERENCES users(id)
) TYPE=InnoDB;


CREATE TABLE emails (
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

ALTER TABLE metainfo ADD COLUMN ordernr int(11) NOT NULL default 0 AFTER state;

ALTER TABLE languages ADD COLUMN loaded_in_db  char(1)     NOT NULL default '0';
ALTER TABLE languages ADD COLUMN path          varchar(30) NOT NULL default '';

CREATE TABLE translations (
  id            int(11)     NOT NULL auto_increment,
  lang_id       int(11)     NOT NULL,
  mykey         varchar(30) NOT NULL default '',
  translation   text,
  PRIMARY KEY  (id),
  UNIQUE KEY   (lang_id, mykey)
) TYPE=MyISAM;

###INSERT INTO components VALUES (800, 'translations', '0.6', '',  '0', '0', '');

drop table components;

CREATE TABLE components (
  id             int(11)      NOT NULL auto_increment,
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

INSERT INTO components (id, module_name, module_type, version_main, version_sub, version_detail, index_url, new_window, enabled, dependencies) VALUES (100,  'collections',  'core',      0, 5, 0, '', '0', '1', '');
INSERT INTO components (id, module_name, module_type, version_main, version_sub, version_detail, index_url, new_window, enabled, dependencies) VALUES (200,  'contacts',     'system',    0, 5, 0, '', '0', '1', '');
INSERT INTO components (id, module_name, module_type, version_main, version_sub, version_detail, index_url, new_window, enabled, dependencies) VALUES (300,  'docs',         'system',    0, 5, 0, '', '0', '1', '');
INSERT INTO components (id, module_name, module_type, version_main, version_sub, version_detail, index_url, new_window, enabled, dependencies) VALUES (400,  'emails',       'system',    0, 5, 0, '', '0', '0', '');
INSERT INTO components (id, module_name, module_type, version_main, version_sub, version_detail, index_url, new_window, enabled, dependencies) VALUES (500,  'events',       'core',      0, 5, 0, '', '0', '1', '');
INSERT INTO components (id, module_name, module_type, version_main, version_sub, version_detail, index_url, new_window, enabled, dependencies) VALUES (600,  'faqs',         'extension', 0, 5, 0, '', '0', '0', '');
INSERT INTO components (id, module_name, module_type, version_main, version_sub, version_detail, index_url, new_window, enabled, dependencies) VALUES (700,  'groups',       'core',      0, 5, 0, '', '0', '1', '');
INSERT INTO components (id, module_name, module_type, version_main, version_sub, version_detail, index_url, new_window, enabled, dependencies) VALUES (800,  'moduledev',    'extension', 0, 5, 0, '', '0', '0', '');
INSERT INTO components (id, module_name, module_type, version_main, version_sub, version_detail, index_url, new_window, enabled, dependencies) VALUES (900,  'news',         'system',    0, 5, 0, '', '0', '1', '');
INSERT INTO components (id, module_name, module_type, version_main, version_sub, version_detail, index_url, new_window, enabled, dependencies) VALUES (1000, 'notes',        'core',      0, 5, 0, '', '0', '1', '');
INSERT INTO components (id, module_name, module_type, version_main, version_sub, version_detail, index_url, new_window, enabled, dependencies) VALUES (1100, 'stats',        'core',      0, 5, 0, '', '0', '1', '');
INSERT INTO components (id, module_name, module_type, version_main, version_sub, version_detail, index_url, new_window, enabled, dependencies) VALUES (1200, 'sync',         'core',      0, 5, 0, '', '0', '1', '');
INSERT INTO components (id, module_name, module_type, version_main, version_sub, version_detail, index_url, new_window, enabled, dependencies) VALUES (1300, 'tickets',      'system',    0, 5, 0, '', '0', '1', '');
INSERT INTO components (id, module_name, module_type, version_main, version_sub, version_detail, index_url, new_window, enabled, dependencies) VALUES (1400, 'todos',        'system',    0, 5, 0, '', '0', '1', '');
INSERT INTO components (id, module_name, module_type, version_main, version_sub, version_detail, index_url, new_window, enabled, dependencies) VALUES (1500, 'translations', 'extension', 0, 5, 0, '', '0', '0', '');
INSERT INTO components (id, module_name, module_type, version_main, version_sub, version_detail, index_url, new_window, enabled, dependencies) VALUES (1600, 'tree',         'core',      0, 5, 0, '', '0', '1', '');
INSERT INTO components (id, module_name, module_type, version_main, version_sub, version_detail, index_url, new_window, enabled, dependencies) VALUES (1700, 'users',        'core',      0, 5, 0, '', '0', '1', '');
INSERT INTO components (id, module_name, module_type, version_main, version_sub, version_detail, index_url, new_window, enabled, dependencies) VALUES (1800, 'workflow',     'core',      0, 5, 0, '', '0', '1', '');

INSERT INTO components (id, module_name, module_type, version_main, version_sub, version_detail, index_url, new_window, enabled, dependencies) VALUES (10000, 'jabber',       'addon',     0, 5, 0, '', '0', '1', '');

ALTER TABLE sync CHANGE remote_identifier remote_identifier VARCHAR(150) NOT NULL;

ALTER TABLE user_details ADD column may_change_profile CHAR(1) NOT NULL default '1';
