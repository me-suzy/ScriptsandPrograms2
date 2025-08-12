INSERT INTO refering_types VALUES (3, 'extern',   'reference to external link (url)');


alter table memos ADD column is_dir char(1) NOT NULL default '0' after memo_id;
alter table memos ADD column parent int(11) NOT NULL default '0' after is_dir;
alter table memos ADD key parent_key (parent);

INSERT INTO events VALUES (26, 'note',         'new folder',       'folder for notes was added',   0, NULL, 'system');
INSERT INTO events VALUES (27, 'note',         'changed folder',   'folder for notes was changed', 0, NULL, 'system');
INSERT INTO events VALUES (28, 'note',         'deleted folder',   'folder for notes was deleted', 0, NULL, 'system');

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
INSERT INTO tree VALUES("7",  "2", "new contact",     "../../modules/contacts/index.php?command=add_contact_view", "l4w_main", "", '', "-1", NULL, "1", "1", "0", "1");
INSERT INTO tree VALUES("8",  "2", "browse",          "../../modules/contacts/index.php?command=show_entries", "l4w_main",     "", '', "0", NULL, "1", "1", "0", "1");
#INSERT INTO tree VALUES("12", "2", "locked contacts", "../../modules/contacts/index.php?command=show_locked",  "l4w_main",    "", '', "0", NULL, "1", "1", "0", "1");
INSERT INTO tree VALUES("3",  "1", "options",         "", "", "admin.gif", "", "6", "", "1", "1", "0", "0");
INSERT INTO tree VALUES("9",  "3", "skins",           "", "", "", "", "1", "~~skins~~", "1", "1", "0", "0");
INSERT INTO tree VALUES("10", "3", "languages",       "", "", "", "", "1", "~~languages~~", "1", "1", "0", "0");
INSERT INTO tree VALUES("4",  "1", "administration",  "", "", "admin.gif", "", "7", "~~rights~~", "1", "1", "0", "1");
INSERT INTO tree VALUES("11", "1", "statistic", "",   "", "stats.gif", "", "7", "~~stats~~", "1", "1", "0", "0");
#INSERT INTO tree VALUES("5", "1", "quicklinks",      "../../quicklinks.php", "l4w_main", "quicklinks.gif", "", "9", NULL, "1", "1", "0", "0");
INSERT INTO tree VALUES("6",  "1", "logout",          "../../logout.php", "_top", "logout.gif", "", "10", NULL, "1", "1", "0", "1");

INSERT INTO tree (id, parent, name, link, frame, img, sign, order_nr, subtree_identifier, translate, enabled, authorize, protected) 
VALUES (13, 1, 'news', '', 'l4w_main', 'news.gif', '', 0, NULL, '1', '1', '0', '0');

INSERT INTO tree (id, parent, name, link, frame, img, sign, order_nr, subtree_identifier, translate, enabled, authorize, protected) 
VALUES (14, 13, 'current news', '../../modules/news/index.php?command=show_current_news', 'l4w_main', '', '', 0, NULL, '1', '1', '0', '0');

INSERT INTO tree (id, parent, name, link, frame, img, sign, order_nr, subtree_identifier, translate, enabled, authorize, protected) 
VALUES (15, 13, 'all news', '../../modules/news/index.php?command=show_all_news', 'l4w_main', '', '', 0, NULL, '1', '1', '0', '0');

INSERT INTO tree (id, parent, name, link, frame, img, sign, order_nr, subtree_identifier, translate, enabled, authorize, protected) 
VALUES (16, 1, 'documents', '', 'l4w_main', 'docs.gif', '', 3, NULL, '1', '1', '0', '0');

INSERT INTO tree (id, parent, name, link, frame, img, sign, order_nr, subtree_identifier, translate, enabled, authorize, protected) 
VALUES (17, 16, 'new document', '../../modules/docs/index.php?command=add_doc_view&parent=0', 'l4w_main', '', '', 1, NULL, '1', '1', '0', '0');

INSERT INTO tree (id, parent, name, link, frame, img, sign, order_nr, subtree_identifier, translate, enabled, authorize, protected) 
VALUES (18, 16, 'browse', '../../modules/docs/index.php?command=show_entries', 'l4w_main', '', '', 1, NULL, '1', '1', '0', '0');

INSERT INTO tree (id, parent, name, link, frame, img, sign, order_nr, subtree_identifier, translate, enabled, authorize, protected) 
VALUES (19, 1, 'notes', '', 'l4w_main', 'notes.gif', '', 4, NULL, '1', '1', '0', '0');

INSERT INTO tree (id, parent, name, link, frame, img, sign, order_nr, subtree_identifier, translate, enabled, authorize, protected) 
VALUES (20, 19, 'new note', '../../modules/notes/index.php?command=add_entry_view', 'l4w_main', '', '', 1, NULL, '1', '1', '0', '0');

INSERT INTO tree (id, parent, name, link, frame, img, sign, order_nr, subtree_identifier, translate, enabled, authorize, protected) 
VALUES (21, 19, 'browse', '../../modules/notes/index.php?command=show_entries', 'l4w_main', '', '', 1, NULL, '1', '1', '0', '0');

#INSERT INTO tree (id, parent, name, link, frame, img, sign, order_nr, subtree_identifier, translate, enabled, authorize, protected) 
#VALUES (22, 19, 'locked notes', '../../modules/notes/index.php?command=show_locked', 'l4w_main', '', '', 1, NULL, '1', '1', '0', '0');

INSERT INTO tree (id, parent, name, link, frame, img, sign, order_nr, subtree_identifier, translate, enabled, authorize, protected) 
VALUES (23, 1, 'diverse', '', 'l4w_main', 'diverse.gif', '', 6, NULL, '1', '1', '0', '0');

#INSERT INTO tree (id, parent, name, link, frame, img, sign, order_nr, subtree_identifier, translate, enabled, authorize, protected) 
#VALUES (24, 23, 'Amazon DVD Service', 'http://www.amapsys.de/index.php', 'l4w_main', '', '', 1, NULL, '1', '1', '0', '0');

INSERT INTO tree (id, parent, name, link, frame, img, sign, order_nr, subtree_identifier, translate, enabled, authorize, protected) 
VALUES (25, 1, 'tickets', '', 'l4w_main', 'tickets.gif', '', 5, NULL, '1', '1', '0', '0');

INSERT INTO tree (id, parent, name, link, frame, img, sign, order_nr, subtree_identifier, translate, enabled, authorize, protected) 
VALUES (26, 25, 'new ticket', '../../modules/tickets/index.php?command=add_ticket_view', 'l4w_main', '', '', 1, NULL, '1', '1', '0', '0');

INSERT INTO tree (id, parent, name, link, frame, img, sign, order_nr, subtree_identifier, translate, enabled, authorize, protected) 
VALUES (27, 25, 'browse', '../../modules/tickets/index.php?command=show_entries', 'l4w_main', '', '', 2, NULL, '1', '1', '0', '0');

INSERT INTO tree (id, parent, name, link, frame, img, sign, order_nr, subtree_identifier, translate, enabled, authorize, protected) 
VALUES (28, 1, 'todos', '', 'l4w_main', 'todos.gif', '', 5, NULL, '1', '1', '0', '0');

INSERT INTO tree (id, parent, name, link, frame, img, sign, order_nr, subtree_identifier, translate, enabled, authorize, protected) 
VALUES (29, 28, 'new todo', '../../modules/todos/index.php?command=add_entry_view', 'l4w_main', '', '', 1, NULL, '1', '1', '0', '0');

INSERT INTO tree (id, parent, name, link, frame, img, sign, order_nr, subtree_identifier, translate, enabled, authorize, protected) 
VALUES (30, 28, 'browse',   '../../modules/todos/index.php?command=show_entries', 'l4w_main', '', '', 2, NULL, '1', '1', '0', '0');

#INSERT INTO tree VALUES("31",  "1", "email",           "", "", "mail.gif", "", "2", "~~emails~~", "1", "1", "0", "0");

INSERT INTO events VALUES (29, 'todo',         'new todo',         'todo was added',               0, NULL, 'system');
INSERT INTO events VALUES (30, 'todo',         'changed todo',     'todo was changed',             0, NULL, 'system');
INSERT INTO events VALUES (31, 'todo',         'deleted todo',     'todo was deleted',             0, NULL, 'system');
INSERT INTO events VALUES (32, 'todo',         'new folder',       'folder for notes was added',   0, NULL, 'system');
INSERT INTO events VALUES (33, 'todo',         'changed folder',   'folder for notes was changed', 0, NULL, 'system');
INSERT INTO events VALUES (34, 'todo',         'deleted folder',   'folder for notes was deleted', 0, NULL, 'system');

CREATE TABLE priorities (
  prio_id        int(11)       NOT NULL auto_increment,
  name           varchar(30)   NOT NULL,
  description    varchar(200),
  translate      char(1)       NOT NULL default '0',
  order_nr       int(11)       NOT NULL default 1,
  PRIMARY KEY (prio_id)
) TYPE=MyISAM;

INSERT INTO priorities VALUES (1, 'high',     'high',   '1', 1);
INSERT INTO priorities VALUES (2, 'medium',   'medium', '1', 2);
INSERT INTO priorities VALUES (3, 'low',      'low',    '1', 3);

ALTER TABLE tickets ADD parent int(11) default 0;
ALTER TABLE tickets ADD KEY parent_key (parent);
ALTER TABLE tickets ADD is_dir char(1) default '0';
ALTER TABLE tickets DROP FOREIGN KEY `tickets_ibfk_1`;

INSERT INTO events VALUES (35, 'ticket',       'new folder',         'folder for tickets was added',   0, NULL, 'system');
INSERT INTO events VALUES (36, 'ticket',       'changed folder',     'folder for tickets was changed', 0, NULL, 'system');
INSERT INTO events VALUES (37, 'ticket',       'deleted folder',     'folder for tickets was deleted', 0, NULL, 'system');

alter table priorities add column color char(7) NOT NULL default '#000000';

update priorities set color='#ff0000' where prio_id=1;
update priorities set color='#000066' where prio_id=2;

CREATE TABLE url_schemes (
  scheme_id      int(11)       NOT NULL auto_increment,
  scheme         varchar(15)   NOT NULL,
  description    varchar(200),
  order_nr       int(11)       NOT NULL default 1,
  PRIMARY KEY (scheme_id)
) TYPE=MyISAM;

INSERT INTO url_schemes VALUES (1, 'http',     'http reference',    1);
INSERT INTO url_schemes VALUES (2, 'file',     'file reference',    2);
INSERT INTO url_schemes VALUES (3, 'mailto',   'mailto reference',  3);

ALTER TABLE refering add column ref_scheme int(11) NOT NULL default 0;
ALTER TABLE refering add column ref_path   varchar(100);

alter table refering drop unique_key;
alter table refering add UNIQUE KEY (from_object_type, from_object_id, to_object_type, to_object_id, ref_path)

delete from tree where id=12;
delete from tree where id=22;

alter table states add column color varchar(7) NOT NULL default '#ffffff';

INSERT INTO states VALUES ('todo',-1, 'undefinied',             '#c0c0c0');
INSERT INTO states VALUES ('todo', 0, 'new',                    '#FF8000');
INSERT INTO states VALUES ('todo', 1, 'assigned',               '#000066');
INSERT INTO states VALUES ('todo', 2, 'worked on',              '#004000');
INSERT INTO states VALUES ('todo', 3, 'resolved',               '#00FF00');
INSERT INTO states VALUES ('todo', 4, 'resolution confirmed',   '#00FFFF');
INSERT INTO states VALUES ('todo', 5, 'deferred',               '#8000FF');
INSERT INTO states VALUES ('todo', 6, 'to delete',              '#FFFF80');
INSERT INTO states VALUES ('todo', 7, 'reopened',               '#FF0000');


# reference, grp, user, old, new, name(internal note), isdefault
INSERT INTO transitions VALUES ('todo', 0,  0, 0, 1, 'everyone can transit from new to assigned',         '1');
INSERT INTO transitions VALUES ('todo', 14, 0, 0, 2, 'grp 14 users can transit from new to worked on',    '1');
INSERT INTO transitions VALUES ('todo', 14, 0, 0, 5, 'grp 14 users can transit from new to to delete',    '0');

INSERT INTO transitions VALUES ('todo', 0,  0, 1, 1, 'still assigned',                                    '1');
INSERT INTO transitions VALUES ('todo', 0,  0, 1, 2, 'everyone can transit from assigned to worked on',   '1');
INSERT INTO transitions VALUES ('todo', 0,  0, 1, 3, '',                                                  '0');
INSERT INTO transitions VALUES ('todo', 14, 0, 1, 4, '',                                                  '0');
INSERT INTO transitions VALUES ('todo', 14, 0, 1, 5, '',                                                  '0');

INSERT INTO transitions VALUES ('todo', 0,  0, 2, 2, 'leave as is for all',                               '1');
INSERT INTO transitions VALUES ('todo', 0,  0, 2, 3, '',                                                  '1');
INSERT INTO transitions VALUES ('todo', 14, 0, 2, 4, '',                                                  '1');
INSERT INTO transitions VALUES ('todo', 14, 0, 2, 3, '',                                                  '1');

INSERT INTO transitions VALUES ('todo', 0,  0, 3, 3, 'leave as is for all',                              '1');
INSERT INTO transitions VALUES ('todo', 0,  0, 3, 4, '',                                                 '1');

INSERT INTO transitions VALUES ('todo', 0,  0, 4, 4, 'leave as is for all',                              '1');

INSERT INTO transitions VALUES ('todo', 0,  0, 5, 5, 'leave as is for all',                              '1');

INSERT INTO transitions VALUES ('todo', 0,  0, 6, 6, 'leave as is for all',                              '1');

INSERT INTO transitions VALUES ('todo', 0,  0, 7, 7, 'leave as is for all',                              '1');


INSERT INTO actions VALUES (3, 'email',  'sendmail',    'send mail');

INSERT INTO events VALUES (38, 'email',        'sending failed',   'sending mail from leads4web failed', 0, NULL, 'system');

ALTER TABLE events ADD COLUMN template       varchar(20) NOT NULL default '';
ALTER TABLE events ADD COLUMN subject        varchar(30) NOT NULL default 'dummy subject';
ALTER TABLE events ADD COLUMN default_action int(11)     NOT NULL default 0;

update events SET template='new_entry' WHERE event_id=1;
update events SET template='new_entry' WHERE event_id=17;
update events SET template='new_entry' WHERE event_id=20;
update events SET template='new_entry' WHERE event_id=23;
update events SET template='new_entry' WHERE event_id=29;

INSERT INTO events VALUES (39, 'ticket',       'assigned',           'ticket was assigned',            0, NULL, 'system', 'assign_entry', 'ticket assigned', 4);

INSERT INTO actions VALUES (4, 'assigned', 'entryAssignedEvent', 'Send mail as of assignment');

INSERT INTO components VALUES (270, 'collections',  '0.4', '',  '0', '1', '');
INSERT INTO components VALUES (280, 'sync',         '0.4', '',  '0', '1', '');

INSERT INTO events VALUES (40, 'faq',          'new faq',          'faq was added',               0, NULL, 'system', 'new_entry', 'dummy subject', 0);
INSERT INTO events VALUES (41, 'faq',          'changed faq',      'faq was changed',             0, NULL, 'system', '', 'dummy subject', 0);
INSERT INTO events VALUES (42, 'faq',          'deleted faq',      'faq was deleted',             0, NULL, 'system', '', 'dummy subject', 0);
INSERT INTO events VALUES (43, 'faq',          'new folder',       'folder for faqs was added',   0, NULL, 'system', '', 'dummy subject', 0);
INSERT INTO events VALUES (44, 'faq',          'changed folder',   'folder for faqs was changed', 0, NULL, 'system', '', 'dummy subject', 0);
INSERT INTO events VALUES (45, 'faq',          'deleted folder',   'folder for faqs was deleted', 0, NULL, 'system', '', 'dummy subject', 0);

INSERT INTO components VALUES (600, 'development',  '0.5', '',  '0', '0', '');

INSERT INTO components VALUES (700, 'emails',       '0.6', '',  '0', '0', '100');

CREATE TABLE accounts (
  id                int(11)     NOT NULL    auto_increment,
  owner             int(11)     NOT NULL    default '0',
  host              varchar(50)             default NULL,
  login             varchar(50)             default NULL,
  pass              varchar(50)             default NULL,
  default_folder    int(11)     NOT NULL    default 1,
  PRIMARY KEY  (id),
  INDEX        (owner),
  FOREIGN KEY  (owner) REFERENCES users(id)
) TYPE=InnoDB;