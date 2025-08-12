delete from tree where 1=1;

drop table tree;

CREATE TABLE tree (
  id int(11) NOT NULL auto_increment,
  parent int(11) NOT NULL default '0',
  name varchar(50) NOT NULL default '',
  link varchar(200) NOT NULL default '',
  frame varchar(50) NOT NULL default '',
  img varchar(100) NOT NULL default '',
  sign varchar(10) NOT NULL default '',
  order_nr int(11) NOT NULL default '0',
  subtree_identifier varchar(20) default NULL,
  translate char(1) NOT NULL default '0',
  enabled char(1) NOT NULL default '1',
  authorize char(1) NOT NULL default '0',
  protected char(1) NOT NULL default '0',
  UNIQUE KEY id (id)
) TYPE=MyISAM;

INSERT INTO tree VALUES("1",  "0", "leads4web/4",     "", "", "", "", "-2", NULL, "0", "1", "0", "1");
INSERT INTO tree VALUES("2",  "1", "contacts",        "", "", "contacts.gif", "", "1", NULL, "1", "1", "0", "1");
INSERT INTO tree VALUES("7",  "2", "new contact",     "../../modules/contacts/index.php?command=add_contact_view", "l4w_main", "", "&rarr;", "-1", NULL, "1", "1", "0", "1");
INSERT INTO tree VALUES("8",  "2", "search",          "../../modules/contacts/index.php?command=show_entries", "l4w_main", "", "&rarr;", "0", NULL, "1", "1", "0", "1");
INSERT INTO tree VALUES("12", "2", "locked contacts", "../../modules/contacts/index.php?command=show_locked",  "l4w_main", "", "&rarr;", "0", NULL, "1", "1", "0", "1");
INSERT INTO tree VALUES("3",  "1", "options",         "", "", "admin.gif", "", "5", "", "1", "1", "0", "0");
INSERT INTO tree VALUES("9",  "3", "skins",           "", "", "", "", "1", "~~skins~~", "1", "1", "0", "0");
INSERT INTO tree VALUES("10", "3", "languages",       "", "", "", "", "1", "~~languages~~", "1", "1", "0", "0");
INSERT INTO tree VALUES("4",  "1", "administration",  "", "", "admin.gif", "", "6", "~~rights~~", "1", "1", "0", "1");
INSERT INTO tree VALUES("11", "1", "statistic", "",   "", "stats.gif", "", "7", "~~stats~~", "1", "1", "0", "0");
#INSERT INTO tree VALUES("5", "1", "quicklinks",      "../../quicklinks.php", "l4w_main", "quicklinks.gif", "", "8", NULL, "1", "1", "0", "0");
INSERT INTO tree VALUES("6",  "1", "logout",          "../../logout.php", "_top", "logout.gif", "", "9", NULL, "1", "1", "0", "1");

INSERT INTO tree (id, parent, name, link, frame, img, sign, order_nr, subtree_identifier, translate, enabled, authorize, protected) 
VALUES (13, 1, 'news', '', 'l4w_main', 'news.gif', '', 0, NULL, '1', '1', '0', '0');

INSERT INTO tree (id, parent, name, link, frame, img, sign, order_nr, subtree_identifier, translate, enabled, authorize, protected) 
VALUES (14, 13, 'current news', '../../modules/news/index.php?command=show_current_news', 'l4w_main', '', '&rarr;', 0, NULL, '1', '1', '0', '0');

INSERT INTO tree (id, parent, name, link, frame, img, sign, order_nr, subtree_identifier, translate, enabled, authorize, protected) 
VALUES (15, 13, 'all news', '../../modules/news/index.php?command=show_all_news', 'l4w_main', '', '&rarr;', 0, NULL, '1', '1', '0', '0');

INSERT INTO tree (id, parent, name, link, frame, img, sign, order_nr, subtree_identifier, translate, enabled, authorize, protected) 
VALUES (16, 1, 'documents', '', 'l4w_main', 'docs.gif', '', 2, NULL, '1', '1', '0', '0');

INSERT INTO tree (id, parent, name, link, frame, img, sign, order_nr, subtree_identifier, translate, enabled, authorize, protected) 
VALUES (17, 16, 'new document', '../../modules/docs/index.php?command=add_doc_view', 'l4w_main', '', '&rarr;', 1, NULL, '1', '1', '0', '0');

INSERT INTO tree (id, parent, name, link, frame, img, sign, order_nr, subtree_identifier, translate, enabled, authorize, protected) 
VALUES (18, 16, 'search', '../../modules/docs/index.php?command=show_entries', 'l4w_main', '', '&rarr;', 1, NULL, '1', '1', '0', '0');

INSERT INTO tree (id, parent, name, link, frame, img, sign, order_nr, subtree_identifier, translate, enabled, authorize, protected) 
VALUES (19, 1, 'notes', '', 'l4w_main', 'notes.gif', '', 3, NULL, '1', '1', '0', '0');

INSERT INTO tree (id, parent, name, link, frame, img, sign, order_nr, subtree_identifier, translate, enabled, authorize, protected) 
VALUES (20, 19, 'new note', '../../modules/notes/index.php?command=add_note_view', 'l4w_main', '', '&rarr;', 1, NULL, '1', '1', '0', '0');

INSERT INTO tree (id, parent, name, link, frame, img, sign, order_nr, subtree_identifier, translate, enabled, authorize, protected) 
VALUES (21, 19, 'search', '../../modules/notes/index.php?command=show_entries', 'l4w_main', '', '&rarr;', 1, NULL, '1', '1', '0', '0');

INSERT INTO tree (id, parent, name, link, frame, img, sign, order_nr, subtree_identifier, translate, enabled, authorize, protected) 
VALUES (22, 19, 'locked notes', '../../modules/notes/index.php?command=show_locked', 'l4w_main', '', '&rarr;', 1, NULL, '1', '1', '0', '0');

INSERT INTO tree (id, parent, name, link, frame, img, sign, order_nr, subtree_identifier, translate, enabled, authorize, protected) 
VALUES (23, 1, 'diverse', '', 'l4w_main', 'diverse.gif', '', 4, NULL, '1', '1', '0', '0');

INSERT INTO tree (id, parent, name, link, frame, img, sign, order_nr, subtree_identifier, translate, enabled, authorize, protected) 
VALUES (24, 23, 'Amazon DVD Service', 'http://www.amapsys.de/index.php', 'l4w_main', '', '&rarr;', 1, NULL, '1', '1', '0', '0');

delete from memos where 1=1;

#alter table memos change object_type ref_object_type varchar(20);
#alter table memos change object_id   ref_object_id int(11);

alter table memos drop column creator;
alter table memos drop column changer;
alter table memos drop column updated;
alter table memos drop column object_type;
alter table memos drop column object_id;

INSERT INTO events VALUES (17, 'note',         'new note',         'note was added',           0, NULL, 'system');
INSERT INTO events VALUES (18, 'note',         'changed note',     'note was changed',         0, NULL, 'system');
INSERT INTO events VALUES (19, 'note',         'deleted note',     'note was deleted',         0, NULL, 'system');

ALTER TABLE user_details ADD COLUMN default_group  INT(11) NOT NULL default 0;
ALTER TABLE user_details ADD COLUMN default_access VARCHAR(10) NOT NULL default '-rwx-------';

CREATE TABLE access_options (
  access_id             int(11)          NOT NULL auto_increment,
  identifier            varchar(10)      NOT NULL, 
  name                  varchar(30)      NOT NULL,
  icon                  varchar(30),
  PRIMARY KEY    (access_id)
) TYPE=MyISAM;

INSERT INTO access_options VALUES (1, '-rwx------', 'private',      'access_private.gif');
INSERT INTO access_options VALUES (2, '-rwxr-----', 'groupread',    'access_grpread.gif');
INSERT INTO access_options VALUES (3, '-rwxrw----', 'groupwrite',   'access_grpwrite.gif');
INSERT INTO access_options VALUES (4, '-rwxrwx---', 'groupdelete',  'access_grpdel.gif');
INSERT INTO access_options VALUES (5, '-rwxrwxr--', 'publicread',   'access_pubread.gif');
INSERT INTO access_options VALUES (6, '-rwxrwxrw-', 'publicwrite',  'access_pubwrite.gif');
INSERT INTO access_options VALUES (7, '-rwxrwxrwx', 'publicdelete', 'access_pubdel.gif');

CREATE TABLE refering (
  from_object_type  varchar(20) NOT NULL,
  from_object_id    int(11)     NOT NULL,
  to_object_type    varchar(20) NOT NULL,
  to_object_id      int(11)     NOT NULL,
  ref_type          int(11)     NOT NULL default 1,
  description       varchar(50),
  UNIQUE KEY (from_object_type, from_object_id, to_object_type, to_object_id)
) TYPE=MyISAM;

INSERT INTO components VALUES (250, 'jabber',           '0.4', '',  '0', '1', '');
INSERT INTO actions VALUES (2, 'jabber', 'send2jabber', 'send news to my jabber account');

alter table user_details add column   jabber_id      varchar(100);

CREATE TABLE collections (
  collection_id    int(11)     NOT NULL auto_increment,
  name             varchar(50),
  description      text,
  PRIMARY KEY  (collection_id)
) TYPE=MyISAM;

INSERT INTO events VALUES (20, 'collection',         'new collection',         'collection was added',           0, NULL, 'system');
INSERT INTO events VALUES (21, 'collection',         'changed collection',     'collection was changed',         0, NULL, 'system');
INSERT INTO events VALUES (22, 'collection',         'deleted collection',     'collection was deleted',         0, NULL, 'system');

CREATE TABLE sync (
  user_id           int(11)     NOT NULL,
  sync_with         varchar(5)  NOT NULL default '',
  remote_identifier varchar(50) NOT NULL,
  synced            date        NOT NULL,
  timeoffset        int(11)     NOT NULL default 0,
  PRIMARY KEY  (user_id, sync_with)
) TYPE=MyISAM;

CREATE TABLE refering_types (
  type_id int(11)             NOT NULL,
  name           varchar(30)  NOT NULL,
  description    varchar(200)
) TYPE=MyISAM;

INSERT INTO refering_types VALUES (1, 'weak',     'simple reference type, no dependencies');
INSERT INTO refering_types VALUES (2, 'heredity', 'from-object passes owner, group and access_level to to-object (change when to-object changes)');

