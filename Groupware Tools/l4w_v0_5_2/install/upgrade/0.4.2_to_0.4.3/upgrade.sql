alter table contacts add column system_msg      varchar (100);
alter table contacts add column system_msg_date datetime;
alter table contacts add column identity_hash int(11) NOT NULL default '0';

update components SET enabled='1' WHERE id=200;

alter table useronline drop primary key;
alter table useronline drop file;
alter table useronline drop grp;
alter table useronline add object_type varchar(100) NOT NULL default '';
alter table useronline add object_id   int(11)      NOT NULL default '0';

alter table useronline add UNIQUE KEY (user_id, object_type, object_id);

INSERT INTO tree (parent, name, link, frame, img, sign, order_nr, subtree_identifier, translate, enabled, authorize, protected) 
VALUES (2, 'locked contacts', '../../modules/contacts/index.php?command=show_locked', 'l4w_main', '', '', 1, NULL, '1', '1', '0', '1');

UPDATE tree set name='statistic' where id=11;


drop table mailevents;

CREATE TABLE events (
  event_id     int(11)      NOT NULL auto_increment default '0',
  object_type  varchar(20)  NOT NULL,
  event        varchar(20)  NOT NULL,
  description  varchar(30),
  added_by     int(11)      NOT NULL default 0,
  added_date   datetime,
  event_type   varchar(20)  NOT NULL default 'system',
  PRIMARY KEY  (event_id),
  UNIQUE KEY   (object_type, event)
) TYPE=InnoDB;


INSERT INTO events VALUES (1,  'contact',      'new',     'entry was added (system)',    0, NULL, 'system');
INSERT INTO events VALUES (2,  'contact',      'changed', 'entry was changed (system)',  0, NULL, 'system');
INSERT INTO events VALUES (3,  'contact',      '-1',      'workflos: undefined',         0, NULL, 'system');
INSERT INTO events VALUES (4,  'contact',      '0',       'workflow: new',               0, NULL, 'workflow');
INSERT INTO events VALUES (5,  'contact',      '1',       'workflow: changed',           0, NULL, 'workflow');
INSERT INTO events VALUES (6,  'contact',      '2',       'workflow: accepted',          0, NULL, 'workflow');
INSERT INTO events VALUES (7,  'contact',      '3',       'workflow: not accepted',      0, NULL, 'workflow');
INSERT INTO events VALUES (8,  'contact',      '4',       'workflow: to delete',         0, NULL, 'workflow');
INSERT INTO events VALUES (9,  'contact',      '5',       'workflow: imported from l4w', 0, NULL, 'workflow');
INSERT INTO events VALUES (10, 'contact',      'deleted', 'entry was deleted (system)',  0, NULL, 'system');
INSERT INTO events VALUES (11, 'contact memo', 'added',   'entry was added',             0, NULL, 'system');
INSERT INTO events VALUES (12, 'contact memo', 'changed', 'entry was changed',           0, NULL, 'system');
INSERT INTO events VALUES (13, 'contact memo', 'deleted', 'entry was deleted',           0, NULL, 'system');

CREATE TABLE actions (
  action_id     int(11)      NOT NULL auto_increment default '0',
  name          varchar(20),
  user_function varchar(30),
  description   varchar(30),
  PRIMARY KEY (action_id)
) TYPE=InnoDB;

INSERT INTO actions VALUES (1, 'news', 'add_news', 'add news to news database');


CREATE TABLE eventwatcher (
  watchlist_id     int(11)      NOT NULL auto_increment default '0',
  watcher          int(11)      NOT NULL default 0,
  event_id         int(11)      NOT NULL,
  restrict_to_user int(11),
  restrict_to_grp  int(11),
  perform_action   int(11),
  PRIMARY KEY (watchlist_id),
  INDEX       (event_id),
  INDEX       (restrict_to_user),
  INDEX       (perform_action),
  FOREIGN KEY (event_id)         REFERENCES events(event_id),
  FOREIGN KEY (restrict_to_user) REFERENCES users(id),
  FOREIGN KEY (perform_action)   REFERENCES actions(action_id)
) TYPE=InnoDB;  

INSERT INTO eventwatcher VALUES (1, 2, 1, NULL, NULL, 1)

CREATE TABLE news (
  news_id          int(11)      NOT NULL auto_increment default 0,
  creator          int(11)      NOT NULL default 0,
  owner            int(11)      NOT NULL default 0,
  headline         varchar(100),
  created          datetime     NOT NULL,
  followup         datetime,
  beenread         varchar(1)   NOT NULL DEFAULT '0',
  news             text,
  sentto           text,
  perform_action   varchar(100),
  PRIMARY KEY (news_id),
  INDEX       (creator),
  INDEX       (owner),
  FOREIGN KEY (creator)         REFERENCES users(id),
  FOREIGN KEY (owner)           REFERENCES users(id)
) TYPE=InnoDB;  

#INSERT INTO tree (parent, name, link, frame, img, sign, order_nr, subtree_identifier, translate, enabled, authorize, protected) 
#VALUES (1, 'news', '../../modules/news/index.php?command=show_news', 'l4w_main', 'news.gif', '', 0, NULL, '1', '1', '0', '0');