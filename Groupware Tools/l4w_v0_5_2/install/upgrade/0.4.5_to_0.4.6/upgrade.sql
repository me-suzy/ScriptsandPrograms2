alter table sync add column    status        varchar(10) NOT NULL default '';
alter table sync change column synced synced datetime    NOT NULL;
alter table sync change column status status varchar(15) NOT NULL;
alter table sync add column    object_type   varchar(20) NOT NULL;
alter table sync add column    object_id     int(11)     NOT NULL;

alter table sync drop primary key;
alter table sync add primary key (user_id, sync_with, object_type, object_id);

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
VALUES (23, 1, 'diverse', '', 'l4w_main', 'diverse.gif', '', 5, NULL, '1', '1', '0', '0');

INSERT INTO tree (id, parent, name, link, frame, img, sign, order_nr, subtree_identifier, translate, enabled, authorize, protected) 
VALUES (24, 23, 'Amazon DVD Service', 'http://www.amapsys.de/index.php', 'l4w_main', '', '&rarr;', 1, NULL, '1', '1', '0', '0');

INSERT INTO tree (id, parent, name, link, frame, img, sign, order_nr, subtree_identifier, translate, enabled, authorize, protected) 
VALUES (25, 1, 'tickets', '', 'l4w_main', 'tickets.gif', '', 4, NULL, '1', '1', '0', '0');

INSERT INTO tree (id, parent, name, link, frame, img, sign, order_nr, subtree_identifier, translate, enabled, authorize, protected) 
VALUES (26, 25, 'new ticket', '../../modules/tickets/index.php?command=add_ticket_view', 'l4w_main', '', '&rarr;', 1, NULL, '1', '1', '0', '0');

INSERT INTO tree (id, parent, name, link, frame, img, sign, order_nr, subtree_identifier, translate, enabled, authorize, protected) 
VALUES (27, 25, 'search', '../../modules/tickets/index.php?command=show_entries', 'l4w_main', '', '&rarr;', 2, NULL, '1', '1', '0', '0');

CREATE TABLE tickets (
  ticket_id        int(11)     NOT NULL auto_increment,
  contact_id       int(11)     NOT NULL,
  theme            varchar(50),
  content          text,
  followup         date,
  due              date,
  starts           date,
  done             int(3)      NOT NULL default '0',
  state            int(11)     NOT NULL default '0',
  priority         int(3)      NOT NULL default '1',
  PRIMARY KEY  (ticket_id),
  KEY contact_key (contact_id),
  FOREIGN KEY (contact_id)     REFERENCES contacts(contact_id)
) TYPE=InnoDB;

INSERT INTO events VALUES (23, 'ticket',       'new ticket',         'ticket was added',           0, NULL, 'system');
INSERT INTO events VALUES (24, 'ticket',       'changed ticket',     'ticket was changed',         0, NULL, 'system');
INSERT INTO events VALUES (25, 'ticket',       'deleted ticket',     'ticket was deleted',         0, NULL, 'system');


INSERT INTO states VALUES ('ticket',-1, 'undefinied');
INSERT INTO states VALUES ('ticket', 0, 'new');
INSERT INTO states VALUES ('ticket', 1, 'assigned');
INSERT INTO states VALUES ('ticket', 2, 'worked on');
INSERT INTO states VALUES ('ticket', 3, 'resolved');
INSERT INTO states VALUES ('ticket', 4, 'resolution confirmed');
INSERT INTO states VALUES ('ticket', 5, 'deferred');
INSERT INTO states VALUES ('ticket', 6, 'to delete');
INSERT INTO states VALUES ('ticket', 7, 'reopened');


# reference, grp, user, old, new, name(internal note), isdefault
INSERT INTO transitions VALUES ('ticket', 0,  0, 0, 1, 'everyone can transit from new to assigned',         '1');
INSERT INTO transitions VALUES ('ticket', 14, 0, 0, 2, 'grp 14 users can transit from new to worked on',    '1');
INSERT INTO transitions VALUES ('ticket', 14, 0, 0, 5, 'grp 14 users can transit from new to to delete',    '0');

INSERT INTO transitions VALUES ('ticket', 0,  0, 1, 1, 'still assigned',                                    '1');
INSERT INTO transitions VALUES ('ticket', 0,  0, 1, 2, 'everyone can transit from assigned to worked on',   '1');
INSERT INTO transitions VALUES ('ticket', 0,  0, 1, 3, '',                                                  '0');
INSERT INTO transitions VALUES ('ticket', 14, 0, 1, 4, '',                                                  '0');
INSERT INTO transitions VALUES ('ticket', 14, 0, 1, 5, '',                                                  '0');

INSERT INTO transitions VALUES ('ticket', 0,  0, 2, 2, 'leave as is for all',                               '1');
INSERT INTO transitions VALUES ('ticket', 0,  0, 2, 3, '',                                                  '1');
INSERT INTO transitions VALUES ('ticket', 14, 0, 2, 4, '',                                                  '1');
INSERT INTO transitions VALUES ('ticket', 14, 0, 2, 3, '',                                                  '1');

INSERT INTO transitions VALUES ('ticket', 0,  0, 3, 3, 'leave as is for all',                              '1');
INSERT INTO transitions VALUES ('ticket', 0,  0, 3, 4, '',                                                 '1');

INSERT INTO transitions VALUES ('ticket', 0,  0, 4, 4, 'leave as is for all',                              '1');

INSERT INTO transitions VALUES ('ticket', 0,  0, 5, 5, 'leave as is for all',                              '1');

INSERT INTO transitions VALUES ('ticket', 0,  0, 6, 6, 'leave as is for all',                              '1');

INSERT INTO transitions VALUES ('ticket', 0,  0, 7, 7, 'leave as is for all',                              '1');
