CREATE TABLE ###TABLE_PREFIX###mandator (
  mandator_id      int(11)      NOT NULL auto_increment,
  name             varchar(50)  NOT NULL,
  tree_root        int(11)      NOT NULL default 0,
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

INSERT INTO ###TABLE_PREFIX###user_mandator (user_id, mandator_id) VALUES (2,1);    

INSERT INTO ###TABLE_PREFIX###mandator (mandator_id, name, description) VALUES (1, 'Default', 'Default Mandator');

ALTER TABLE ###TABLE_PREFIX###group_details ADD COLUMN mandator_id  int(11) NOT NULL default 1;

ALTER TABLE ###TABLE_PREFIX###categories ADD COLUMN mandator int(11) NOT NULL default 1;

ALTER TABLE ###TABLE_PREFIX###components ADD COLUMN mandator int(11) NOT NULL default 1;

ALTER TABLE ###TABLE_PREFIX###page_stats ADD COLUMN mandator int(11) NOT NULL default 1;

ALTER TABLE ###TABLE_PREFIX###skins ADD COLUMN mandator int(11) NOT NULL default 1;

ALTER TABLE ###TABLE_PREFIX###states ADD COLUMN mandator int(11) NOT NULL default 1;

ALTER TABLE ###TABLE_PREFIX###access_options ADD COLUMN mandator int(11) NOT NULL default 1;

ALTER TABLE ###TABLE_PREFIX###collections ADD COLUMN mandator int(11) NOT NULL default 1;

ALTER TABLE ###TABLE_PREFIX###priorities ADD COLUMN mandator int(11) NOT NULL default 1;

ALTER TABLE ###TABLE_PREFIX###user_details ADD COLUMN jabber_pass varchar(100);

INSERT INTO ###TABLE_PREFIX###events VALUES (46, 'contact',      'assigned',   'contact was assigned',               0, NULL, 'system',   'assign_entry',  'contact assigned', 4);

ALTER TABLE ###TABLE_PREFIX###actions ADD COLUMN chooseable char(1) NOT NULL default '1';  

UPDATE ###TABLE_PREFIX###actions SET chooseable='0' WHERE action_id=4; 

UPDATE ###TABLE_PREFIX###components SET enabled='1' WHERE id=400; 

ALTER TABLE ###TABLE_PREFIX###mandator ADD column group_root int(11) NOT NULL default 1;

ALTER TABLE ###TABLE_PREFIX###accounts ADD column port int(11) NOT NULL default 110;

ALTER TABLE ###TABLE_PREFIX###accounts ADD column type varchar(10) NOT NULL default 'pop3';

ALTER TABLE ###TABLE_PREFIX###accounts ADD column use_ssl char(1) NOT NULL default '0';

ALTER TABLE ###TABLE_PREFIX###accounts ADD column active char(1) NOT NULL default '1';

INSERT INTO ###TABLE_PREFIX###components (id, module_name, module_type, version_main, version_sub, version_detail, index_url, new_window, enabled, dependencies) VALUES (1900,  'mandators',    'core',      0, 5, 1, '', '0', '1', '');

CREATE TABLE ###TABLE_PREFIX###datagrids (
  datagrid_id      int(11)      NOT NULL auto_increment,
  mandator_id      int(11)      NOT NULL,
  name             varchar(50)  NOT NULL,
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

INSERT INTO ###TABLE_PREFIX###tree (id, parent, name, link, frame, img, sign, order_nr, subtree_identifier, translate, enabled, authorize, protected) 
VALUES (32, 25, 'new folder',   '../../modules/tickets/index.php?command=add_folder_view', 'l4w_main', '', '', 3, '', '1', '1', '0', '0');

INSERT INTO ###TABLE_PREFIX###datagrids (datagrid_id, mandator_id, name, description) 
VALUES (1, 1, 'mandators', '');
        
INSERT INTO ###TABLE_PREFIX###datagrids (datagrid_id, mandator_id, name, description) 
VALUES (2, 1, 'notes', '');
 
INSERT INTO ###TABLE_PREFIX###datagrid_columns (
	datagrid_id, column_id, column_identifier, column_name, description,order_nr, width, visible, is_primary
	) VALUES (
		1, 1, 'mandator_id', 'id','', 1, '30','0','1' 
	); 

INSERT INTO ###TABLE_PREFIX###datagrid_columns (
	datagrid_id, column_id, column_identifier, column_name, description,order_nr, width, visible, is_primary
	) VALUES (
		1, 2, 'name',      'name','', 2, '230','1','0' 
	); 

INSERT INTO ###TABLE_PREFIX###datagrid_columns (
	datagrid_id, column_id, column_identifier, column_name, description,order_nr, width, visible, is_primary
	) VALUES (
		1, 3, 'tree_root', 'tree_root','', 3, '30','1','0' 
	); 

INSERT INTO ###TABLE_PREFIX###datagrid_columns (
	datagrid_id, column_id, column_identifier, column_name, description,order_nr, width, visible, is_primary
	) VALUES (
		1, 4, 'description', 'description','', 4, '130','1','0' 
	); 

INSERT INTO ###TABLE_PREFIX###datagrid_columns (
	datagrid_id, column_id, column_identifier, column_name, description,order_nr, width, visible, is_primary
	) VALUES (
		1, 5, 'acl_inc_php', 'acl_inc_php','', 5, '50','1','0' 
	); 

INSERT INTO ###TABLE_PREFIX###datagrid_columns (
	datagrid_id, column_id, column_identifier, column_name, description,order_nr, width, visible, is_primary
	) VALUES (
		1, 6, 'actions', '','', 6, '30','1','0' 
	);

DROP TABLE ###TABLE_PREFIX###application;

CREATE TABLE ###TABLE_PREFIX###alt_email_addresses (
  contact_id       int(11)      NOT NULL,
  email            varchar(50)  NOT NULL default '',
  INDEX (contact_id),
  FOREIGN KEY (contact_id) REFERENCES ###TABLE_PREFIX###contacts (contact_id)  
) TYPE=InnoDB;

# 10.6.2005
ALTER TABLE ###TABLE_PREFIX###group_details ADD COLUMN parent_id  int(11) NOT NULL default 0;

INSERT INTO ###TABLE_PREFIX###datagrids (datagrid_id, mandator_id, name, description) VALUES (5, 1, 'groupshierarchy', '');
INSERT INTO ###TABLE_PREFIX###datagrids (datagrid_id, mandator_id, name, description) VALUES (6, 1, 'docs', '');

INSERT INTO ###TABLE_PREFIX###datagrid_columns (datagrid_id, column_id, column_identifier, column_name, visible, is_primary, description, order_nr, width, searchable) VALUES("5", "1", "id",          "id",       "0", "1", "", "1", NULL, "0");
INSERT INTO ###TABLE_PREFIX###datagrid_columns (datagrid_id, column_id, column_identifier, column_name, visible, is_primary, description, order_nr, width, searchable) VALUES("5", "2", "name",        "name",     "1", "0", "", "2", NULL, "0");
INSERT INTO ###TABLE_PREFIX###datagrid_columns (datagrid_id, column_id, column_identifier, column_name, visible, is_primary, description, order_nr, width, searchable) VALUES("5", "3", "value",       "value",    "1", "0", "", "3", NULL, "0");
INSERT INTO ###TABLE_PREFIX###datagrid_columns (datagrid_id, column_id, column_identifier, column_name, visible, is_primary, description, order_nr, width, searchable) VALUES("5", "4", "cnt",         "count",    "1", "0", "", "4", NULL, "0");
INSERT INTO ###TABLE_PREFIX###datagrid_columns (datagrid_id, column_id, column_identifier, column_name, visible, is_primary, description, order_nr, width, searchable) VALUES("5", "5", "mandator_id", "mandator", "1", "0", "", "5", NULL, "0");
INSERT INTO ###TABLE_PREFIX###datagrid_columns (datagrid_id, column_id, column_identifier, column_name, visible, is_primary, description, order_nr, width, searchable) VALUES("5", "6", "parent_id",   "parent",   "1", "0", "", "6", NULL, "0");

update ###TABLE_PREFIX###events set object_type='doc' where object_type='document';

INSERT INTO ###TABLE_PREFIX###datagrid_columns (datagrid_id, column_id, column_identifier, column_name, visible, is_primary, description, order_nr, width, searchable) VALUES("6", "1",  "doc_id",       "doc_id",       "0", "1", "", "1",  NULL, "0");
INSERT INTO ###TABLE_PREFIX###datagrid_columns (datagrid_id, column_id, column_identifier, column_name, visible, is_primary, description, order_nr, width, searchable) VALUES("6", "2",  "checkboxes",   "checkboxes",   "1", "0", "", "2",  "30", "0");
INSERT INTO ###TABLE_PREFIX###datagrid_columns (datagrid_id, column_id, column_identifier, column_name, visible, is_primary, description, order_nr, width, searchable) VALUES("6", "3",  "is_dir",       "is_dir",       "0", "0", "", "3",  NULL, "0");
INSERT INTO ###TABLE_PREFIX###datagrid_columns (datagrid_id, column_id, column_identifier, column_name, visible, is_primary, description, order_nr, width, searchable) VALUES("6", "4",  "parent",       "parent",       "0", "0", "", "4",  NULL, "0");
INSERT INTO ###TABLE_PREFIX###datagrid_columns (datagrid_id, column_id, column_identifier, column_name, visible, is_primary, description, order_nr, width, searchable) VALUES("6", "5",  "name",         "name",         "1", "0", "", "5",  NULL, "0");
INSERT INTO ###TABLE_PREFIX###datagrid_columns (datagrid_id, column_id, column_identifier, column_name, visible, is_primary, description, order_nr, width, searchable) VALUES("6", "6",  "fullpath",     "fullpath",     "0", "0", "", "6",  NULL, "0");
INSERT INTO ###TABLE_PREFIX###datagrid_columns (datagrid_id, column_id, column_identifier, column_name, visible, is_primary, description, order_nr, width, searchable) VALUES("6", "7",  "category",     "category",     "0", "0", "", "7",  NULL, "0");
INSERT INTO ###TABLE_PREFIX###datagrid_columns (datagrid_id, column_id, column_identifier, column_name, visible, is_primary, description, order_nr, width, searchable) VALUES("6", "8",  "description",  "description",  "1", "0", "", "8",  NULL, "0");
INSERT INTO ###TABLE_PREFIX###datagrid_columns (datagrid_id, column_id, column_identifier, column_name, visible, is_primary, description, order_nr, width, searchable) VALUES("6", "9",  "group_name",   "group_name",   "1", "0", "", "9",  NULL, "0");
INSERT INTO ###TABLE_PREFIX###datagrid_columns (datagrid_id, column_id, column_identifier, column_name, visible, is_primary, description, order_nr, width, searchable) VALUES("6", "10", "owner",        "owner",        "1", "0", "", "10",  NULL, "0");
INSERT INTO ###TABLE_PREFIX###datagrid_columns (datagrid_id, column_id, column_identifier, column_name, visible, is_primary, description, order_nr, width, searchable) VALUES("6", "11", "created",      "created",      "1", "0", "", "11", NULL, "0");
INSERT INTO ###TABLE_PREFIX###datagrid_columns (datagrid_id, column_id, column_identifier, column_name, visible, is_primary, description, order_nr, width, searchable) VALUES("6", "12", "access_level", "access_level", "0", "0", "", "12", NULL, "0");
INSERT INTO ###TABLE_PREFIX###datagrid_columns (datagrid_id, column_id, column_identifier, column_name, visible, is_primary, description, order_nr, width, searchable) VALUES("6", "13", "owner_id",     "owner_id",     "0", "0", "", "13", NULL, "0");
INSERT INTO ###TABLE_PREFIX###datagrid_columns (datagrid_id, column_id, column_identifier, column_name, visible, is_primary, description, order_nr, width, searchable) VALUES("6", "14", "group_id",     "group_id",     "0", "0", "", "14", NULL, "0");
INSERT INTO ###TABLE_PREFIX###datagrid_columns (datagrid_id, column_id, column_identifier, column_name, visible, is_primary, description, order_nr, width, searchable) VALUES("6", "15", "color",        "color",        "0", "0", "", "15", NULL, "0");

INSERT INTO ###TABLE_PREFIX###datagrids (datagrid_id, mandator_id, name, description) VALUES (7, 1, 'states->references', '');

INSERT INTO ###TABLE_PREFIX###datagrid_columns (datagrid_id, column_id, column_identifier, column_name, visible, is_primary, description, order_nr, width, searchable) VALUES("7", "1", "reference",     "type",         "1", "1", "", "1",  NULL, "0");
INSERT INTO ###TABLE_PREFIX###datagrid_columns (datagrid_id, column_id, column_identifier, column_name, visible, is_primary, description, order_nr, width, searchable) VALUES("7", "2", "action",        "action",       "1", "0", "", "2",  NULL, "0");

INSERT INTO ###TABLE_PREFIX###datagrids (datagrid_id, mandator_id, name, description) VALUES (8, 1, 'states', '');

INSERT INTO ###TABLE_PREFIX###datagrid_columns (datagrid_id, column_id, column_identifier, column_name, visible, is_primary, description, order_nr, width, searchable) VALUES("8", "1", "reference",     "type",         "1", "1", "", "1",  NULL, "0");
INSERT INTO ###TABLE_PREFIX###datagrid_columns (datagrid_id, column_id, column_identifier, column_name, visible, is_primary, description, order_nr, width, searchable) VALUES("8", "2", "name",          "name",         "1", "1", "", "2",  NULL, "0");
INSERT INTO ###TABLE_PREFIX###datagrid_columns (datagrid_id, column_id, column_identifier, column_name, visible, is_primary, description, order_nr, width, searchable) VALUES("8", "3", "status",        "status",       "0", "0", "", "3",  NULL, "0");
INSERT INTO ###TABLE_PREFIX###datagrid_columns (datagrid_id, column_id, column_identifier, column_name, visible, is_primary, description, order_nr, width, searchable) VALUES("8", "4", "action",        "action",       "1", "0", "", "4",  NULL, "0");

INSERT INTO ###TABLE_PREFIX###datagrids (datagrid_id, mandator_id, name, description) VALUES (9, 1, 'transitions', '');

INSERT INTO ###TABLE_PREFIX###datagrid_columns (datagrid_id, column_id, column_identifier, column_name, visible, is_primary, description, order_nr, width, searchable) VALUES("9", "1", "reference",     "type",         "1", "1", "", "1",  NULL, "0");
INSERT INTO ###TABLE_PREFIX###datagrid_columns (datagrid_id, column_id, column_identifier, column_name, visible, is_primary, description, order_nr, width, searchable) VALUES("9", "2", "group_name",    "group",        "1", "0", "", "2",  NULL, "0");
INSERT INTO ###TABLE_PREFIX###datagrid_columns (datagrid_id, column_id, column_identifier, column_name, visible, is_primary, description, order_nr, width, searchable) VALUES("9", "3", "user_name",     "user",         "1", "0", "", "3",  NULL, "0");
INSERT INTO ###TABLE_PREFIX###datagrid_columns (datagrid_id, column_id, column_identifier, column_name, visible, is_primary, description, order_nr, width, searchable) VALUES("9", "4", "grp",           "",             "0", "0", "", "4",  NULL, "0");
INSERT INTO ###TABLE_PREFIX###datagrid_columns (datagrid_id, column_id, column_identifier, column_name, visible, is_primary, description, order_nr, width, searchable) VALUES("9", "5", "user",          "",             "0", "0", "", "5",  NULL, "0");
INSERT INTO ###TABLE_PREFIX###datagrid_columns (datagrid_id, column_id, column_identifier, column_name, visible, is_primary, description, order_nr, width, searchable) VALUES("9", "6", "newstate",      "new_state",    "1", "0", "", "6",  NULL, "0");
INSERT INTO ###TABLE_PREFIX###datagrid_columns (datagrid_id, column_id, column_identifier, column_name, visible, is_primary, description, order_nr, width, searchable) VALUES("9", "7", "name",          "name",         "1", "0", "", "7",  NULL, "0");
INSERT INTO ###TABLE_PREFIX###datagrid_columns (datagrid_id, column_id, column_identifier, column_name, visible, is_primary, description, order_nr, width, searchable) VALUES("9", "8", "isdefault",     "default",      "1", "0", "", "8",  NULL, "0");

#22.6.2005
ALTER TABLE ###TABLE_PREFIX###collections ADD column parent int(11) NOT NULL default 0 after mandator;

ALTER TABLE ###TABLE_PREFIX###collections ADD column is_dir char(1) NOT NULL default '0' after parent;

#CREATE TABLE ###TABLE_PREFIX###component_collection (
#  component_id     int(11)     NOT NULL,
#  collection_id    int(11)     NOT NULL,
#  PRIMARY KEY  (component, collection_id)
#) TYPE=MyISAM;


INSERT INTO ###TABLE_PREFIX###datagrids (datagrid_id, mandator_id, name, description) VALUES (10, 1, 'categories', '');

INSERT INTO ###TABLE_PREFIX###datagrid_columns (datagrid_id, column_id, column_identifier, column_name, visible, is_primary, description, order_nr, width, searchable) VALUES("10", "1", "collection_id", "id",           "0", "1", "", "1",  NULL, "0");
INSERT INTO ###TABLE_PREFIX###datagrid_columns (datagrid_id, column_id, column_identifier, column_name, visible, is_primary, description, order_nr, width, searchable) VALUES("10", "2", "name",          "name",         "1", "0", "", "2",  NULL, "0");
INSERT INTO ###TABLE_PREFIX###datagrid_columns (datagrid_id, column_id, column_identifier, column_name, visible, is_primary, description, order_nr, width, searchable) VALUES("10", "3", "description",   "description",  "1", "0", "", "3",  NULL, "0");
INSERT INTO ###TABLE_PREFIX###datagrid_columns (datagrid_id, column_id, column_identifier, column_name, visible, is_primary, description, order_nr, width, searchable) VALUES("10", "4", "is_dir",        "is_dir",       "0", "0", "", "4",  NULL, "0");
INSERT INTO ###TABLE_PREFIX###datagrid_columns (datagrid_id, column_id, column_identifier, column_name, visible, is_primary, description, order_nr, width, searchable) VALUES("10", "5", "actions",       "",             "1", "0", "", "5",  NULL, "0");

# 26.6.2005
ALTER TABLE ###TABLE_PREFIX###refering CHANGE COLUMN ref_path ref_path varchar(100) NOT NULL default '';