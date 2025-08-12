# MySQL-Front Dump 2.5
#
# Host: localhost   Database: gacl
# --------------------------------------------------------
# Server version 4.0.21-debug

# mandators are not used by ticketmanager, but this entry has to be added as
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


INSERT INTO ###TABLE_PREFIX###tree VALUES("1",  "0", "ticketmanager",   "",                                                          "",         "",             "", "-2",  NULL, "0", "1", "0", "1", "1");
INSERT INTO ###TABLE_PREFIX###tree VALUES("2",  "1", "contacts",        "",                                                          "",         "contacts.gif", "", "5",  NULL, "1", "1", "0", "1", "1");
INSERT INTO ###TABLE_PREFIX###tree VALUES("9",  "2", "new contact",     "../../modules/contacts/index.php?command=add_contact_view", "l4w_main", "",             '', "-1",  NULL, "1", "1", "0", "1", "1");
INSERT INTO ###TABLE_PREFIX###tree VALUES("10", "2", "browse",          "../../modules/contacts/index.php?command=show_entries",     "l4w_main", "",             '', "0",   NULL, "1", "1", "0", "1", "1");

INSERT INTO ###TABLE_PREFIX###tree (id, parent, name, link, frame, img, sign, order_nr, subtree_identifier, translate, enabled, authorize, protected) 
VALUES (11, 1, 'notes', '', 'l4w_main', 'notes.gif', '', 4, NULL, '1', '1', '0', '0');

INSERT INTO ###TABLE_PREFIX###tree (id, parent, name, link, frame, img, sign, order_nr, subtree_identifier, translate, enabled, authorize, protected) 
VALUES (12, 11, 'new note',   '../../modules/notes/index.php?command=add_entry_view', 'l4w_main',  '', '', 1, NULL, '1', '1', '0', '0');

INSERT INTO ###TABLE_PREFIX###tree (id, parent, name, link, frame, img, sign, order_nr, subtree_identifier, translate, enabled, authorize, protected) 
VALUES (13, 11, 'new folder', '../../modules/notes/index.php?command=add_folder_view', 'l4w_main', '', '', 2, NULL, '1', '1', '0', '0');

INSERT INTO ###TABLE_PREFIX###tree (id, parent, name, link, frame, img, sign, order_nr, subtree_identifier, translate, enabled, authorize, protected) 
VALUES (14, 11, 'browse',     '../../modules/notes/index.php?command=show_entries',    'l4w_main', '', '', 3, NULL, '1', '1', '0', '0');

INSERT INTO ###TABLE_PREFIX###tree (id, parent, name, link, frame, img, sign, order_nr, subtree_identifier, translate, enabled, authorize, protected) 
VALUES (15, 1, 'tickets', '', 'l4w_main', 'tickets.gif', '', 1, NULL, '1', '1', '0', '0');

INSERT INTO ###TABLE_PREFIX###tree (id, parent, name, link, frame, img, sign, order_nr, subtree_identifier, translate, enabled, authorize, protected) 
VALUES (16, 15, 'new ticket', '../../modules/tickets/index.php?command=add_ticket_view', 'l4w_main', '', '', 1, NULL, '1', '1', '0', '0');

INSERT INTO ###TABLE_PREFIX###tree (id, parent, name, link, frame, img, sign, order_nr, subtree_identifier, translate, enabled, authorize, protected) 
VALUES (17, 15, 'browse', '../../modules/tickets/index.php?command=show_entries', 'l4w_main', '', '', 2, NULL, '1', '1', '0', '0');

INSERT INTO ###TABLE_PREFIX###tree VALUES("3",  "1", "options",         "",                      "",       "admin.gif",  "", "6",  "",              "1", "1", "0", "0", "1");
INSERT INTO ###TABLE_PREFIX###tree VALUES("4",  "3", "skins",           "",                      "",       "",           "", "1",  "~~skins~~",     "1", "1", "0", "0", "1");
INSERT INTO ###TABLE_PREFIX###tree VALUES("5",  "3", "languages",       "",                      "",       "",           "", "1",  "~~languages~~", "1", "1", "0", "0", "1");
INSERT INTO ###TABLE_PREFIX###tree VALUES("6",  "1", "administration",  "",                      "",       "admin.gif",  "", "7",  "~~rights~~",    "1", "1", "0", "1", "1");
INSERT INTO ###TABLE_PREFIX###tree VALUES("7",  "1", "logout",          "../../logout.php",      "_top",   "logout.gif", "", "10", NULL,            "1", "1", "0", "1", "0");

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

INSERT INTO ###TABLE_PREFIX###components (id, module_name, module_type, version_main, version_sub, version_detail, index_url, new_window, enabled, dependencies) VALUES (100,   'collections',  'core',      0, 0, 1, '', '0', '1', '');
INSERT INTO ###TABLE_PREFIX###components (id, module_name, module_type, version_main, version_sub, version_detail, index_url, new_window, enabled, dependencies) VALUES (200,   'contacts',     'system',    0, 0, 1, '', '0', '1', '');
INSERT INTO ###TABLE_PREFIX###components (id, module_name, module_type, version_main, version_sub, version_detail, index_url, new_window, enabled, dependencies) VALUES (300,   'docs',         'system',    0, 0, 1, '', '0', '0', '');
INSERT INTO ###TABLE_PREFIX###components (id, module_name, module_type, version_main, version_sub, version_detail, index_url, new_window, enabled, dependencies) VALUES (400,   'emails',       'system',    0, 0, 1, '', '0', '0', '');
INSERT INTO ###TABLE_PREFIX###components (id, module_name, module_type, version_main, version_sub, version_detail, index_url, new_window, enabled, dependencies) VALUES (500,   'events',       'core',      0, 0, 1, '', '0', '0', '');
INSERT INTO ###TABLE_PREFIX###components (id, module_name, module_type, version_main, version_sub, version_detail, index_url, new_window, enabled, dependencies) VALUES (600,   'faqs',         'extension', 0, 0, 1, '', '0', '0', '');
INSERT INTO ###TABLE_PREFIX###components (id, module_name, module_type, version_main, version_sub, version_detail, index_url, new_window, enabled, dependencies) VALUES (700,   'groups',       'core',      0, 0, 1, '', '0', '1', '');
INSERT INTO ###TABLE_PREFIX###components (id, module_name, module_type, version_main, version_sub, version_detail, index_url, new_window, enabled, dependencies) VALUES (800,   'moduledev',    'extension', 0, 0, 1, '', '0', '0', '');
INSERT INTO ###TABLE_PREFIX###components (id, module_name, module_type, version_main, version_sub, version_detail, index_url, new_window, enabled, dependencies) VALUES (900,   'news',         'system',    0, 0, 1, '', '0', '0', '');
INSERT INTO ###TABLE_PREFIX###components (id, module_name, module_type, version_main, version_sub, version_detail, index_url, new_window, enabled, dependencies) VALUES (1000,  'notes',        'core',      0, 0, 1, '', '0', '0', '');
INSERT INTO ###TABLE_PREFIX###components (id, module_name, module_type, version_main, version_sub, version_detail, index_url, new_window, enabled, dependencies) VALUES (1100,  'stats',        'core',      0, 0, 1, '', '0', '0', '');
INSERT INTO ###TABLE_PREFIX###components (id, module_name, module_type, version_main, version_sub, version_detail, index_url, new_window, enabled, dependencies) VALUES (1200,  'sync',         'system',    0, 0, 1, '', '0', '0', '');
INSERT INTO ###TABLE_PREFIX###components (id, module_name, module_type, version_main, version_sub, version_detail, index_url, new_window, enabled, dependencies) VALUES (1300,  'tickets',      'system',    0, 0, 1, '', '0', '1', '');
INSERT INTO ###TABLE_PREFIX###components (id, module_name, module_type, version_main, version_sub, version_detail, index_url, new_window, enabled, dependencies) VALUES (1400,  'todos',        'system',    0, 0, 1, '', '0', '0', '');
INSERT INTO ###TABLE_PREFIX###components (id, module_name, module_type, version_main, version_sub, version_detail, index_url, new_window, enabled, dependencies) VALUES (1500,  'translations', 'extension', 0, 0, 1, '', '0', '0', '');
INSERT INTO ###TABLE_PREFIX###components (id, module_name, module_type, version_main, version_sub, version_detail, index_url, new_window, enabled, dependencies) VALUES (1600,  'tree',         'core',      0, 0, 1, '', '0', '1', '');
INSERT INTO ###TABLE_PREFIX###components (id, module_name, module_type, version_main, version_sub, version_detail, index_url, new_window, enabled, dependencies) VALUES (1700,  'users',        'core',      0, 0, 1, '', '0', '1', '');
INSERT INTO ###TABLE_PREFIX###components (id, module_name, module_type, version_main, version_sub, version_detail, index_url, new_window, enabled, dependencies) VALUES (1800,  'workflow',     'core',      0, 0, 1, '', '0', '1', '');
INSERT INTO ###TABLE_PREFIX###components (id, module_name, module_type, version_main, version_sub, version_detail, index_url, new_window, enabled, dependencies) VALUES (1900,  'mandators',    'core',      0, 0, 1, '', '0', '0', '');

INSERT INTO ###TABLE_PREFIX###components (id, module_name, module_type, version_main, version_sub, version_detail, index_url, new_window, enabled, dependencies) VALUES (10000, 'jabber',       'addon',     0, 0, 1, '', '0', '0', '');

UPDATE ###TABLE_PREFIX###user_details SET navigation='verticaltabs';

ALTER TABLE ###TABLE_PREFIX###user_details CHANGE navigation navigation VARCHAR(20) DEFAULT "verticaltabs" NOT NULL;

DELETE FROM ###TABLE_PREFIX###states WHERE reference <> 'contact' AND reference <> 'ticket';
