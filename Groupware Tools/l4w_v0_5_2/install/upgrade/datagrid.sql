# MySQL-Front Dump 2.5
#
# Host: localhost   Database: leads4web_4
# --------------------------------------------------------
# Server version 4.0.21-debug


#
# Dumping data for table '###TABLE_PREFIX###datagrid_columns'
#

INSERT INTO ###TABLE_PREFIX###datagrid_columns (datagrid_id, column_id, column_identifier, column_name, visible, is_primary, description, order_nr, width, searchable) VALUES("1", "1", "mandator_id", "id", "0", "1", "", "1", "30", "0");
INSERT INTO ###TABLE_PREFIX###datagrid_columns (datagrid_id, column_id, column_identifier, column_name, visible, is_primary, description, order_nr, width, searchable) VALUES("1", "2", "name", "name", "1", "0", "", "2", "230", "1");
INSERT INTO ###TABLE_PREFIX###datagrid_columns (datagrid_id, column_id, column_identifier, column_name, visible, is_primary, description, order_nr, width, searchable) VALUES("1", "3", "tree_root", "tree_root", "1", "0", "", "3", "30", "0");
INSERT INTO ###TABLE_PREFIX###datagrid_columns (datagrid_id, column_id, column_identifier, column_name, visible, is_primary, description, order_nr, width, searchable) VALUES("1", "4", "description", "description", "1", "0", "", "4", "130", "0");
INSERT INTO ###TABLE_PREFIX###datagrid_columns (datagrid_id, column_id, column_identifier, column_name, visible, is_primary, description, order_nr, width, searchable) VALUES("1", "5", "acl_inc_php", "acl_inc_php", "1", "0", "", "5", "50", "0");
INSERT INTO ###TABLE_PREFIX###datagrid_columns (datagrid_id, column_id, column_identifier, column_name, visible, is_primary, description, order_nr, width, searchable) VALUES("1", "6", "actions", "", "1", "0", "", "6", "30", "0");
INSERT INTO ###TABLE_PREFIX###datagrid_columns (datagrid_id, column_id, column_identifier, column_name, visible, is_primary, description, order_nr, width, searchable) VALUES("3", "1", "id", "id", "0", "1", "", "1", "30", "0");
INSERT INTO ###TABLE_PREFIX###datagrid_columns (datagrid_id, column_id, column_identifier, column_name, visible, is_primary, description, order_nr, width, searchable) VALUES("3", "2", "section_value", "section_value", "0", "0", "", "2", NULL, "0");
INSERT INTO ###TABLE_PREFIX###datagrid_columns (datagrid_id, column_id, column_identifier, column_name, visible, is_primary, description, order_nr, width, searchable) VALUES("3", "3", "value", "value", "0", "0", "", "3", NULL, "0");
INSERT INTO ###TABLE_PREFIX###datagrid_columns (datagrid_id, column_id, column_identifier, column_name, visible, is_primary, description, order_nr, width, searchable) VALUES("3", "4", "login", "login", "1", "0", "", "4", NULL, "1");
INSERT INTO ###TABLE_PREFIX###datagrid_columns (datagrid_id, column_id, column_identifier, column_name, visible, is_primary, description, order_nr, width, searchable) VALUES("3", "5", "firstname", "first name", "1", "0", "", "5", NULL, "1");
INSERT INTO ###TABLE_PREFIX###datagrid_columns (datagrid_id, column_id, column_identifier, column_name, visible, is_primary, description, order_nr, width, searchable) VALUES("3", "6", "lastname", "last name", "1", "0", "", "6", NULL, "1");
INSERT INTO ###TABLE_PREFIX###datagrid_columns (datagrid_id, column_id, column_identifier, column_name, visible, is_primary, description, order_nr, width, searchable) VALUES("3", "7", "email", "email", "1", "0", "", "7", NULL, "0");
INSERT INTO ###TABLE_PREFIX###datagrid_columns (datagrid_id, column_id, column_identifier, column_name, visible, is_primary, description, order_nr, width, searchable) VALUES("3", "8", "name", "name", "1", "0", "", "8", NULL, "0");
INSERT INTO ###TABLE_PREFIX###datagrid_columns (datagrid_id, column_id, column_identifier, column_name, visible, is_primary, description, order_nr, width, searchable) VALUES("3", "9", "membership", "membership", "1", "0", "", "9", NULL, "0");
INSERT INTO ###TABLE_PREFIX###datagrid_columns (datagrid_id, column_id, column_identifier, column_name, visible, is_primary, description, order_nr, width, searchable) VALUES("3", "10", "actions_block", "actions", "1", "0", "", "10", NULL, "0");
INSERT INTO ###TABLE_PREFIX###datagrid_columns (datagrid_id, column_id, column_identifier, column_name, visible, is_primary, description, order_nr, width, searchable) VALUES("4", "1", "id", "id", "0", "1", "", "1", NULL, "0");
INSERT INTO ###TABLE_PREFIX###datagrid_columns (datagrid_id, column_id, column_identifier, column_name, visible, is_primary, description, order_nr, width, searchable) VALUES("4", "2", "login", "login", "1", "0", "", "2", NULL, "1");
INSERT INTO ###TABLE_PREFIX###datagrid_columns (datagrid_id, column_id, column_identifier, column_name, visible, is_primary, description, order_nr, width, searchable) VALUES("4", "3", "firstname", "first name", "1", "0", "", "3", NULL, "1");
INSERT INTO ###TABLE_PREFIX###datagrid_columns (datagrid_id, column_id, column_identifier, column_name, visible, is_primary, description, order_nr, width, searchable) VALUES("4", "4", "lastname", "last name", "1", "0", "", "4", NULL, "1");
INSERT INTO ###TABLE_PREFIX###datagrid_columns (datagrid_id, column_id, column_identifier, column_name, visible, is_primary, description, order_nr, width, searchable) VALUES("4", "5", "membership", "", "1", "0", "", "5", NULL, "0");


#
# Dumping data for table '###TABLE_PREFIX###datagrids'
#

INSERT INTO ###TABLE_PREFIX###datagrids VALUES("1", "1", "mandators", "", "5");
INSERT INTO ###TABLE_PREFIX###datagrids VALUES("2", "1", "notes", "", NULL);
INSERT INTO ###TABLE_PREFIX###datagrids VALUES("3", "1", "users", "", NULL);
INSERT INTO ###TABLE_PREFIX###datagrids VALUES("4", "1", "mandators->users", "", "4");
