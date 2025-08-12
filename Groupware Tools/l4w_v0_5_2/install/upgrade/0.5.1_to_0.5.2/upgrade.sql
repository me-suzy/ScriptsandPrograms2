CREATE TABLE IF NOT EXISTS ###TABLE_PREFIX###update_app_stmts (
  tstamp datetime NOT NULL default '',
  version_main   int(11)      NOT NULL default 0,
  version_sub    int(11)      NOT NULL default 0,
  version_detail int(11)      NOT NULL default 1,
  stmt           text         NOT NULL default ''
) TYPE=MyISAM;

ALTER TABLE ###TABLE_PREFIX###translations CHANGE COLUMN mykey mykey varchar(50) NOT NULL default '';

INSERT INTO ###TABLE_PREFIX###tree (parent, name, link, frame, img, sign, order_nr, subtree_identifier, translate, enabled, authorize, protected) 
VALUES (25, 'templates',   '../../modules/templates/index.php?command=show_entries&type=ticket', 'l4w_main', '', '', 4, '', '1', '1', '0', '0');

INSERT INTO ###TABLE_PREFIX###collections (mandator, parent, is_dir, name, description) 
	VALUES (1, 0, '1', 'Leads', 'leads as category');  
	
var inserted_id=mysql_inserted_id;
	       
INSERT INTO ###TABLE_PREFIX###collections (mandator, parent, is_dir, name, description) 
	VALUES (1, ###inserted_id###, '0', 'A', 'Important customer');         
INSERT INTO ###TABLE_PREFIX###collections (mandator, parent, is_dir, name, description) 
	VALUES (1, ###inserted_id###, '0', 'B', 'Might become an important customer');         
INSERT INTO ###TABLE_PREFIX###collections (mandator, parent, is_dir, name, description) 
	VALUES (1, ###inserted_id###, '0', 'C', 'Might become a customer');
	
ALTER TABLE ###TABLE_PREFIX###datagrids ADD COLUMN aco_section varchar(50) NOT NULL default '' AFTER name;

UPDATE ###TABLE_PREFIX###datagrids SET aco_section='CategoryManager' WHERE name='categories';

CREATE TABLE IF NOT EXISTS ###TABLE_PREFIX###category_component (
  component_id   int(11)     NOT NULL,
  category_id    int(11)     NOT NULL,
  PRIMARY KEY  (component_id, category_id)
) TYPE=MyISAM;

INSERT INTO ###TABLE_PREFIX###category_component (component_id, category_id) VALUES (200,1);

ALTER TABLE ###TABLE_PREFIX###transitions ADD COLUMN mandator int(11) NOT NULL default 1 FIRST;

ALTER TABLE ###TABLE_PREFIX###transitions DROP INDEX reference, ADD UNIQUE reference (mandator, reference,grp,user,state_old,state_new);

INSERT INTO ###TABLE_PREFIX###datagrid_columns (datagrid_id, column_id, column_identifier, column_name, visible, is_primary, description, order_nr, width, searchable) VALUES("9", "9", "action",        "",             "1", "0", "", "9",  NULL, "0");

ALTER TABLE ###TABLE_PREFIX###contacts ADD COLUMN freetext1 varchar(50) AFTER remark;

ALTER TABLE ###TABLE_PREFIX###contacts ADD COLUMN freetext2 varchar(50) AFTER freetext1;

ALTER TABLE ###TABLE_PREFIX###contacts ADD COLUMN freetext3 varchar(50) AFTER freetext2;

ALTER TABLE ###TABLE_PREFIX###user_details ADD COLUMN navigation varchar(20) NOT NULL default 'tree' AFTER jabber_pass;

ALTER TABLE ###TABLE_PREFIX###states ADD COLUMN description varchar(50) AFTER color;

ALTER TABLE ###TABLE_PREFIX###states ADD COLUMN startpoint  char(1)      NOT NULL default '0' AFTER color;
ALTER TABLE ###TABLE_PREFIX###states ADD COLUMN endpoint    char(1)      NOT NULL default '0' AFTER startpoint;

INSERT INTO ###TABLE_PREFIX###datagrid_columns (datagrid_id, column_id, column_identifier, column_name, description, width, visible, is_primary, order_nr, searchable, sortable) VALUES (8, 5, 'startpoint', 'start', '', NULL, '1', '0', 4, '0', '0');
INSERT INTO ###TABLE_PREFIX###datagrid_columns (datagrid_id, column_id, column_identifier, column_name, description, width, visible, is_primary, order_nr, searchable, sortable) VALUES (8, 6, 'endpoint', 'end', '', NULL, '1', '0', 5, '0', '0');
UPDATE ###TABLE_PREFIX###datagrid_columns SET order_nr= 6 WHERE datagrid_id=8 AND column_id=4;

INSERT INTO ###TABLE_PREFIX###datagrids (
    mandator_id, name, aco_section, description
) VALUES (
    1, 'tickets', '',  ''
);
	
var inserted_id2=mysql_inserted_id;

INSERT INTO ###TABLE_PREFIX###datagrid_columns (datagrid_id, column_id, column_identifier, column_name, visible, is_primary, description, order_nr, width, searchable) VALUES(###inserted_id2###, "1",  "ticket_id",    "id",           "1", "1", "", "10",  "30",  "1");
INSERT INTO ###TABLE_PREFIX###datagrid_columns (datagrid_id, column_id, column_identifier, column_name, visible, is_primary, description, order_nr, width, searchable) VALUES(###inserted_id2###, "2",  "theme",        "theme",        "1", "0", "", "20",  "150", "0");
INSERT INTO ###TABLE_PREFIX###datagrid_columns (datagrid_id, column_id, column_identifier, column_name, visible, is_primary, description, order_nr, width, searchable) VALUES(###inserted_id2###, "3",  "content",      "content",      "1", "0", "", "30",  "",    "0");
INSERT INTO ###TABLE_PREFIX###datagrid_columns (datagrid_id, column_id, column_identifier, column_name, visible, is_primary, description, order_nr, width, searchable) VALUES(###inserted_id2###, "4",  "creator",      "creator",      "0", "0", "", "40",  "30",  "0");
INSERT INTO ###TABLE_PREFIX###datagrid_columns (datagrid_id, column_id, column_identifier, column_name, visible, is_primary, description, order_nr, width, searchable) VALUES(###inserted_id2###, "5",  "followup",     "followup",     "1", "0", "", "50",  "130", "0");
INSERT INTO ###TABLE_PREFIX###datagrid_columns (datagrid_id, column_id, column_identifier, column_name, visible, is_primary, description, order_nr, width, searchable) VALUES(###inserted_id2###, "6",  "owner",        "owner",        "1", "0", "", "60",  "130",  "0");
INSERT INTO ###TABLE_PREFIX###datagrid_columns (datagrid_id, column_id, column_identifier, column_name, visible, is_primary, description, order_nr, width, searchable) VALUES(###inserted_id2###, "7",  "grp",          "grp",          "0", "0", "", "70",  "30",  "0");
INSERT INTO ###TABLE_PREFIX###datagrid_columns (datagrid_id, column_id, column_identifier, column_name, visible, is_primary, description, order_nr, width, searchable) VALUES(###inserted_id2###, "8",  "created",      "created",      "0", "0", "", "80",  "30",  "0");
INSERT INTO ###TABLE_PREFIX###datagrid_columns (datagrid_id, column_id, column_identifier, column_name, visible, is_primary, description, order_nr, width, searchable) VALUES(###inserted_id2###, "9",  "last_changer", "last_changer", "0", "0", "", "90",  "30",  "0");
INSERT INTO ###TABLE_PREFIX###datagrid_columns (datagrid_id, column_id, column_identifier, column_name, visible, is_primary, description, order_nr, width, searchable) VALUES(###inserted_id2###, "10", "last_change",  "last_change",  "1", "0", "", "100", "130",  "0");
INSERT INTO ###TABLE_PREFIX###datagrid_columns (datagrid_id, column_id, column_identifier, column_name, visible, is_primary, description, order_nr, width, searchable) VALUES(###inserted_id2###, "11", "access_level", "access",       "0", "0", "", "110", "30",  "0");
INSERT INTO ###TABLE_PREFIX###datagrid_columns (datagrid_id, column_id, column_identifier, column_name, visible, is_primary, description, order_nr, width, searchable) VALUES(###inserted_id2###, "12", "is_dir",       "is_dir",       "0", "0", "", "120", "30",  "0");
INSERT INTO ###TABLE_PREFIX###datagrid_columns (datagrid_id, column_id, column_identifier, column_name, visible, is_primary, description, order_nr, width, searchable) VALUES(###inserted_id2###, "13", "owner_id",     "owner_id",     "0", "0", "", "130", "30",  "0");
INSERT INTO ###TABLE_PREFIX###datagrid_columns (datagrid_id, column_id, column_identifier, column_name, visible, is_primary, description, order_nr, width, searchable) VALUES(###inserted_id2###, "14", "group_id",     "group_id",     "0", "0", "", "140", "30",  "0");
INSERT INTO ###TABLE_PREFIX###datagrid_columns (datagrid_id, column_id, column_identifier, column_name, visible, is_primary, description, order_nr, width, searchable) VALUES(###inserted_id2###, "15", "done",         "done",         "0", "0", "", "150", "30",  "0");
INSERT INTO ###TABLE_PREFIX###datagrid_columns (datagrid_id, column_id, column_identifier, column_name, visible, is_primary, description, order_nr, width, searchable) VALUES(###inserted_id2###, "16", "color",        "color",        "0", "0", "", "160", "30",  "0");
INSERT INTO ###TABLE_PREFIX###datagrid_columns (datagrid_id, column_id, column_identifier, column_name, visible, is_primary, description, order_nr, width, searchable) VALUES(###inserted_id2###, "17", "state",        "state",        "0", "0", "", "170", "30",  "0");
INSERT INTO ###TABLE_PREFIX###datagrid_columns (datagrid_id, column_id, column_identifier, column_name, visible, is_primary, description, order_nr, width, searchable) VALUES(###inserted_id2###, "18", "checkboxes",   "",             "1", "0", "", "5",   "30",  "0");
INSERT INTO ###TABLE_PREFIX###datagrid_columns (datagrid_id, column_id, column_identifier, column_name, visible, is_primary, description, order_nr, width, searchable)           VALUES (###inserted_id2###, "19", "done_column",  "done",         "1", "0", "", "35",  "30",  "0");
INSERT INTO ###TABLE_PREFIX###datagrid_columns (datagrid_id, column_id, column_identifier, column_name, visible, is_primary, description, order_nr, width, searchable, sortable) VALUES (###inserted_id2###, "20", "show_actions", "action",       "1", "0", "", "180", "60",  "0", "0");  

ALTER TABLE ###TABLE_PREFIX###tickets ADD COLUMN reminded char(1) NOT NULL default '0' AFTER priority;

INSERT INTO ###TABLE_PREFIX###actions (name, user_function, description, chooseable) VALUES
    ('reminders', 'checkItemsToRemind', 'reminder Function', '0');
    
INSERT INTO ###TABLE_PREFIX###events (object_type, event, description, event_type, template, subject, default_action) 
VALUES
('ticket', 'ticket reminder', 'reached due date', 'cron', '','ticket reminder', 5);


UPDATE ###TABLE_PREFIX###tree SET link= '../../modules/docs/index.php?command=add_doc&parent=0' 
WHERE link='../../modules/docs/index.php?command=add_doc_view&parent=0';

INSERT INTO ###TABLE_PREFIX###datagrid_columns (datagrid_id, column_id, column_identifier, column_name, visible, is_primary, description, order_nr, width, searchable) VALUES("6", "16", "actions",    "", "1", "0", "", "16",  NULL, "0");

INSERT INTO ###TABLE_PREFIX###datagrid_columns (datagrid_id, column_id, column_identifier, column_name, visible, is_primary, description, order_nr, width, searchable) VALUES("6", "17", "getDocType", "", "1", "0", "", "17",  NULL, "0");
