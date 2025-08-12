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
INSERT INTO tree VALUES("3",  "1", "options",         "", "", "admin.gif", "", "3", "", "1", "1", "0", "0");
INSERT INTO tree VALUES("9",  "3", "skins",           "", "", "", "", "1", "~~skins~~", "1", "1", "0", "0");
INSERT INTO tree VALUES("10", "3", "languages",       "", "", "", "", "1", "~~languages~~", "1", "1", "0", "0");
INSERT INTO tree VALUES("4",  "1", "administration",  "", "", "admin.gif", "", "4", "~~rights~~", "1", "1", "0", "1");
INSERT INTO tree VALUES("11", "1", "statistic", "",   "", "stats.gif", "", "5", "~~stats~~", "1", "1", "0", "0");
#INSERT INTO tree VALUES("5", "1", "quicklinks",      "../../quicklinks.php", "l4w_main", "quicklinks.gif", "", "5", NULL, "1", "1", "0", "0");
INSERT INTO tree VALUES("6",  "1", "logout",          "../../logout.php", "_top", "logout.gif", "", "6", NULL, "1", "1", "0", "1");

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


delete from eventwatcher where 1=1;
drop table eventwatcher;

CREATE TABLE eventwatcher (
  watchlist_id     int(11)      NOT NULL auto_increment default '0',
  watcher          int(11)      NOT NULL default 0,
  event_id         int(11)      NOT NULL,
  restrict_to_user int(11)      NOT NULL default 0,
  restrict_to_grp  int(11)      NOT NULL default 0,
  perform_action   int(11),
  PRIMARY KEY (watchlist_id),
  INDEX       (event_id),
  INDEX       (perform_action),
  FOREIGN KEY (event_id)         REFERENCES events(event_id),
  FOREIGN KEY (perform_action)   REFERENCES actions(action_id)
) TYPE=InnoDB;  

INSERT INTO eventwatcher VALUES (1, 2, 1, 0, 0, 1);    

delete from news where 1=1;
drop table news;

CREATE TABLE news (
  news_id          int(11)      NOT NULL auto_increment default 0,
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
  FOREIGN KEY (owner)           REFERENCES users(id)
) TYPE=InnoDB;  

UPDATE events SET description='entry was added' WHERE event_id=1;
UPDATE events SET description='entry was changed' WHERE event_id=2;
UPDATE events SET description='state changed to undefined', event_type='workflow' WHERE event_id=3;
UPDATE events SET description='state changed to new' WHERE event_id=4;
UPDATE events SET description='state changed to changed' WHERE event_id=5;
UPDATE events SET description='state changed to accepted' WHERE event_id=6;
UPDATE events SET description='state changed to not accepted' WHERE event_id=7;
UPDATE events SET description='state changed to to delete' WHERE event_id=8;
UPDATE events SET description='state changed to imported from l4w' WHERE event_id=9;
UPDATE events SET description='entry was deleted' WHERE event_id=10;

delete from events where event_id=11;
delete from events where event_id=12;
delete from events where event_id=13;

INSERT INTO events VALUES (11, 'document',     'new folder',     'folder was added',               0, NULL, 'system');
INSERT INTO events VALUES (12, 'document',     'changed folder', 'folder was changed',             0, NULL, 'system');
INSERT INTO events VALUES (13, 'document',     'deleted folder', 'folder was deleted',             0, NULL, 'system');

INSERT INTO events VALUES (14, 'document',     'new document',     'document was added',           0, NULL, 'system');
INSERT INTO events VALUES (15, 'document',     'changed document', 'document was changed',         0, NULL, 'system');
INSERT INTO events VALUES (16, 'document',     'deleted document', 'document was deleted',         0, NULL, 'system');

CREATE TABLE docs (
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
