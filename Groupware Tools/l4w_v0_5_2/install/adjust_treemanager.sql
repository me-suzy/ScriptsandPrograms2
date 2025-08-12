# MySQL-Front Dump 2.5
#
# Host: localhost   Database: gacl
# --------------------------------------------------------
# Server version 4.0.21-debug

# mandators are not used by treemanager, but this entry has to be added as
# of common gacl installation
INSERT INTO ###TABLE_PREFIX###group_details (id, mandator_id, description) 
VALUES (1000, 1, 'Root for Demo Mandator');

delete from ###TABLE_PREFIX###tree where 1=1;

drop table ###TABLE_PREFIX###tree;

CREATE TABLE ###TABLE_PREFIX###tree (
  id int(11)        NOT NULL auto_increment,
  parent int(11)    NOT NULL default '0',
  name varchar(50)  NOT NULL default '',
  link varchar(200) NOT NULL default '',
  frame varchar(50) NOT NULL default '',
  img varchar(100)  NOT NULL default '',
  sign varchar(10)  NOT NULL default '',
  order_nr int(11)  NOT NULL default '0',
  subtree_identifier varchar(20) default NULL,
  translate char(1) NOT NULL default '0',
  enabled char(1)   NOT NULL default '1',
  authorize char(1) NOT NULL default '0',
  protected char(1) NOT NULL default '0',
  visible_for_guest  char(1) NOT NULL default '1',
  UNIQUE KEY id (id)
) TYPE=MyISAM;


INSERT INTO ###TABLE_PREFIX###tree VALUES("1",  "0", "treemanager",     "",                      "",       "",           "", "-2", NULL,            "0", "1", "0", "1", "1");
INSERT INTO ###TABLE_PREFIX###tree VALUES("2",  "1", "links",           "",                      "",       "",           "", "5",  "",              "0", "1", "0", "0", "1");
INSERT INTO ###TABLE_PREFIX###tree VALUES("3",  "1", "options",         "",                      "",       "admin.gif",  "", "6",  "",              "1", "1", "0", "0", "1");
INSERT INTO ###TABLE_PREFIX###tree VALUES("4",  "3", "skins",           "",                      "",       "",           "", "1",  "~~skins~~",     "1", "1", "0", "0", "1");
INSERT INTO ###TABLE_PREFIX###tree VALUES("5",  "3", "languages",       "",                      "",       "",           "", "1",  "~~languages~~", "1", "1", "0", "0", "1");
INSERT INTO ###TABLE_PREFIX###tree VALUES("6",  "1", "administration",  "",                      "",       "admin.gif",  "", "7",  "~~rights~~",    "1", "1", "0", "1", "1");
INSERT INTO ###TABLE_PREFIX###tree VALUES("7",  "1", "logout",          "../../logout.php",      "_top",   "logout.gif", "", "10", NULL,            "1", "1", "0", "1", "0");

INSERT INTO ###TABLE_PREFIX###tree VALUES("8",  "2", "evandor.de",      "http://www.evandor.de", "l4w_main",       "",           "", "10", NULL,            "0", "1", "0", "1", "0");

delete from ###TABLE_PREFIX###components where 1=1;

drop table ###TABLE_PREFIX###components;

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

INSERT INTO ###TABLE_PREFIX###components (id, module_name, module_type, version_main, version_sub, version_detail, index_url, new_window, enabled, dependencies) VALUES (100,   'collections',  'core',      0, 5, 2, '', '0', '0', '');
INSERT INTO ###TABLE_PREFIX###components (id, module_name, module_type, version_main, version_sub, version_detail, index_url, new_window, enabled, dependencies) VALUES (200,   'contacts',     'system',    0, 5, 2, '', '0', '0', '');
INSERT INTO ###TABLE_PREFIX###components (id, module_name, module_type, version_main, version_sub, version_detail, index_url, new_window, enabled, dependencies) VALUES (300,   'docs',         'system',    0, 5, 2, '', '0', '0', '');
INSERT INTO ###TABLE_PREFIX###components (id, module_name, module_type, version_main, version_sub, version_detail, index_url, new_window, enabled, dependencies) VALUES (400,   'emails',       'system',    0, 5, 2, '', '0', '0', '');
INSERT INTO ###TABLE_PREFIX###components (id, module_name, module_type, version_main, version_sub, version_detail, index_url, new_window, enabled, dependencies) VALUES (500,   'events',       'core',      0, 5, 2, '', '0', '0', '');
INSERT INTO ###TABLE_PREFIX###components (id, module_name, module_type, version_main, version_sub, version_detail, index_url, new_window, enabled, dependencies) VALUES (600,   'faqs',         'extension', 0, 5, 2, '', '0', '0', '');
INSERT INTO ###TABLE_PREFIX###components (id, module_name, module_type, version_main, version_sub, version_detail, index_url, new_window, enabled, dependencies) VALUES (700,   'groups',       'core',      0, 5, 2, '', '0', '1', '');
INSERT INTO ###TABLE_PREFIX###components (id, module_name, module_type, version_main, version_sub, version_detail, index_url, new_window, enabled, dependencies) VALUES (800,   'moduledev',    'extension', 0, 5, 2, '', '0', '0', '');
INSERT INTO ###TABLE_PREFIX###components (id, module_name, module_type, version_main, version_sub, version_detail, index_url, new_window, enabled, dependencies) VALUES (900,   'news',         'system',    0, 5, 2, '', '0', '0', '');
INSERT INTO ###TABLE_PREFIX###components (id, module_name, module_type, version_main, version_sub, version_detail, index_url, new_window, enabled, dependencies) VALUES (1000,  'notes',        'core',      0, 5, 2, '', '0', '0', '');
INSERT INTO ###TABLE_PREFIX###components (id, module_name, module_type, version_main, version_sub, version_detail, index_url, new_window, enabled, dependencies) VALUES (1100,  'stats',        'core',      0, 5, 2, '', '0', '0', '');
INSERT INTO ###TABLE_PREFIX###components (id, module_name, module_type, version_main, version_sub, version_detail, index_url, new_window, enabled, dependencies) VALUES (1200,  'sync',         'system',    0, 5, 2, '', '0', '0', '');
INSERT INTO ###TABLE_PREFIX###components (id, module_name, module_type, version_main, version_sub, version_detail, index_url, new_window, enabled, dependencies) VALUES (1300,  'tickets',      'system',    0, 5, 2, '', '0', '0', '');
INSERT INTO ###TABLE_PREFIX###components (id, module_name, module_type, version_main, version_sub, version_detail, index_url, new_window, enabled, dependencies) VALUES (1400,  'todos',        'system',    0, 5, 2, '', '0', '0', '');
INSERT INTO ###TABLE_PREFIX###components (id, module_name, module_type, version_main, version_sub, version_detail, index_url, new_window, enabled, dependencies) VALUES (1500,  'translations', 'extension', 0, 5, 2, '', '0', '0', '');
INSERT INTO ###TABLE_PREFIX###components (id, module_name, module_type, version_main, version_sub, version_detail, index_url, new_window, enabled, dependencies) VALUES (1600,  'tree',         'core',      0, 5, 2, '', '0', '1', '');
INSERT INTO ###TABLE_PREFIX###components (id, module_name, module_type, version_main, version_sub, version_detail, index_url, new_window, enabled, dependencies) VALUES (1700,  'users',        'core',      0, 5, 2, '', '0', '1', '');
INSERT INTO ###TABLE_PREFIX###components (id, module_name, module_type, version_main, version_sub, version_detail, index_url, new_window, enabled, dependencies) VALUES (1800,  'workflow',     'core',      0, 5, 2, '', '0', '0', '');
INSERT INTO ###TABLE_PREFIX###components (id, module_name, module_type, version_main, version_sub, version_detail, index_url, new_window, enabled, dependencies) VALUES (1900,  'mandators',    'core',      0, 5, 2, '', '0', '0', '');

INSERT INTO ###TABLE_PREFIX###components (id, module_name, module_type, version_main, version_sub, version_detail, index_url, new_window, enabled, dependencies) VALUES (10000, 'jabber',       'addon',     0, 5, 2, '', '0', '0', '');

