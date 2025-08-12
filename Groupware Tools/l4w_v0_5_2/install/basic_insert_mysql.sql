# MySQL-Front Dump 2.5
#
# Host: localhost   Database: gacl
# --------------------------------------------------------
# Server version 4.0.21-debug

INSERT INTO ###TABLE_PREFIX###mandator (mandator_id, name, tree_root, description) 
VALUES (1, 'Default', 0, 'Default Mandator');

# dependencies : id,v1.v2.v3|id,v1.v2.v3|id,v1.v2.v3 (v: Version main, sub, detail)
INSERT INTO ###TABLE_PREFIX###components (id, module_name, module_type, version_main, version_sub, version_detail, index_url, new_window, enabled, dependencies) VALUES (100,   'collections',  'core',      0, 5, 2, '', '0', '1', '');
INSERT INTO ###TABLE_PREFIX###components (id, module_name, module_type, version_main, version_sub, version_detail, index_url, new_window, enabled, dependencies) VALUES (200,   'contacts',     'system',    0, 5, 2, '', '0', '1', '');
INSERT INTO ###TABLE_PREFIX###components (id, module_name, module_type, version_main, version_sub, version_detail, index_url, new_window, enabled, dependencies) VALUES (300,   'docs',         'system',    0, 5, 2, '', '0', '1', '');
INSERT INTO ###TABLE_PREFIX###components (id, module_name, module_type, version_main, version_sub, version_detail, index_url, new_window, enabled, dependencies) VALUES (400,   'emails',       'system',    0, 5, 2, '', '0', '1', '');
INSERT INTO ###TABLE_PREFIX###components (id, module_name, module_type, version_main, version_sub, version_detail, index_url, new_window, enabled, dependencies) VALUES (500,   'events',       'core',      0, 5, 2, '', '0', '1', '');
INSERT INTO ###TABLE_PREFIX###components (id, module_name, module_type, version_main, version_sub, version_detail, index_url, new_window, enabled, dependencies) VALUES (600,   'faqs',         'extension', 0, 5, 2, '', '0', '0', '');
INSERT INTO ###TABLE_PREFIX###components (id, module_name, module_type, version_main, version_sub, version_detail, index_url, new_window, enabled, dependencies) VALUES (700,   'groups',       'core',      0, 5, 2, '', '0', '1', '');
INSERT INTO ###TABLE_PREFIX###components (id, module_name, module_type, version_main, version_sub, version_detail, index_url, new_window, enabled, dependencies) VALUES (800,   'moduledev',    'extension', 0, 5, 2, '', '0', '0', '');
INSERT INTO ###TABLE_PREFIX###components (id, module_name, module_type, version_main, version_sub, version_detail, index_url, new_window, enabled, dependencies) VALUES (900,   'news',         'system',    0, 5, 2, '', '0', '1', '');
INSERT INTO ###TABLE_PREFIX###components (id, module_name, module_type, version_main, version_sub, version_detail, index_url, new_window, enabled, dependencies) VALUES (1000,  'notes',        'core',      0, 5, 2, '', '0', '1', '');
INSERT INTO ###TABLE_PREFIX###components (id, module_name, module_type, version_main, version_sub, version_detail, index_url, new_window, enabled, dependencies) VALUES (1100,  'stats',        'core',      0, 5, 2, '', '0', '1', '');
INSERT INTO ###TABLE_PREFIX###components (id, module_name, module_type, version_main, version_sub, version_detail, index_url, new_window, enabled, dependencies) VALUES (1200,  'sync',         'system',    0, 5, 2, '', '0', '0', '');
INSERT INTO ###TABLE_PREFIX###components (id, module_name, module_type, version_main, version_sub, version_detail, index_url, new_window, enabled, dependencies) VALUES (1300,  'tickets',      'system',    0, 5, 2, '', '0', '1', '');
INSERT INTO ###TABLE_PREFIX###components (id, module_name, module_type, version_main, version_sub, version_detail, index_url, new_window, enabled, dependencies) VALUES (1400,  'todos',        'system',    0, 5, 2, '', '0', '1', '');
INSERT INTO ###TABLE_PREFIX###components (id, module_name, module_type, version_main, version_sub, version_detail, index_url, new_window, enabled, dependencies) VALUES (1500,  'translations', 'extension', 0, 5, 2, '', '0', '0', '');
INSERT INTO ###TABLE_PREFIX###components (id, module_name, module_type, version_main, version_sub, version_detail, index_url, new_window, enabled, dependencies) VALUES (1600,  'tree',         'core',      0, 5, 2, '', '0', '1', '');
INSERT INTO ###TABLE_PREFIX###components (id, module_name, module_type, version_main, version_sub, version_detail, index_url, new_window, enabled, dependencies) VALUES (1700,  'users',        'core',      0, 5, 2, '', '0', '1', '');
INSERT INTO ###TABLE_PREFIX###components (id, module_name, module_type, version_main, version_sub, version_detail, index_url, new_window, enabled, dependencies) VALUES (1800,  'workflow',     'core',      0, 5, 2, '', '0', '1', '');
INSERT INTO ###TABLE_PREFIX###components (id, module_name, module_type, version_main, version_sub, version_detail, index_url, new_window, enabled, dependencies) VALUES (1900,  'mandators',    'core',      0, 5, 2, '', '0', '1', '');

INSERT INTO ###TABLE_PREFIX###components (id, module_name, module_type, version_main, version_sub, version_detail, index_url, new_window, enabled, dependencies) VALUES (10000, 'jabber',       'addon',     0, 5, 2, '', '0', '1', '');

#=====================================================================================
# GACL 
#=====================================================================================
# MySQL-Front Dump 2.5
#
# Host: localhost   Database: gacl
# --------------------------------------------------------
# Server version 4.0.23-debug


#
# Dumping data for table 'gacl_acl'
#

INSERT INTO ###TABLE_PREFIX###gacl_acl VALUES("12", "user", "1", "1", "", "", "1099380961");
INSERT INTO ###TABLE_PREFIX###gacl_acl VALUES("14", "user", "1", "1", "", "", "1099712228");
INSERT INTO ###TABLE_PREFIX###gacl_acl VALUES("15", "user", "1", "1", "", "", "1100674140");
INSERT INTO ###TABLE_PREFIX###gacl_acl VALUES("16", "user", "1", "1", "", "", "1102765320");
INSERT INTO ###TABLE_PREFIX###gacl_acl VALUES("17", "user", "1", "1", "", "", "1113514033");
INSERT INTO ###TABLE_PREFIX###gacl_acl VALUES("25", "user", "1", "1", "", "", "1118479804");
INSERT INTO ###TABLE_PREFIX###gacl_acl VALUES("23", "user", "1", "1", "", "", "1118415024");
INSERT INTO ###TABLE_PREFIX###gacl_acl VALUES("26", "user", "1", "1", "", "", "1123183094");
INSERT INTO ###TABLE_PREFIX###gacl_acl VALUES("27", "user", "1", "1", "", "", "1123183112");


#
# Dumping data for table 'gacl_acl_sections'
#

INSERT INTO ###TABLE_PREFIX###gacl_acl_sections VALUES("1", "system", "1", "System", "0");
INSERT INTO ###TABLE_PREFIX###gacl_acl_sections VALUES("2", "user", "2", "User", "0");


#
# Dumping data for table 'gacl_acl_seq'
#

INSERT INTO ###TABLE_PREFIX###gacl_acl_seq VALUES("27");


#
# Dumping data for table 'gacl_aco'
#

INSERT INTO ###TABLE_PREFIX###gacl_aco VALUES("10", "Groupmanager", "Show Groupmanager", "1", "Show Groupmanager", "0");
INSERT INTO ###TABLE_PREFIX###gacl_aco VALUES("11", "Groupmanager", "Add Group", "2", "Add Group", "0");
INSERT INTO ###TABLE_PREFIX###gacl_aco VALUES("12", "Groupmanager", "Edit Group", "3", "Edit Group", "0");
INSERT INTO ###TABLE_PREFIX###gacl_aco VALUES("13", "Groupmanager", "Delete Group", "4", "Delete Group", "0");
INSERT INTO ###TABLE_PREFIX###gacl_aco VALUES("14", "Groupmanager", "Edit Permissions", "5", "Edit Permissions", "0");
INSERT INTO ###TABLE_PREFIX###gacl_aco VALUES("15", "Use Leads4web", "Show Logfile", "1", "Show Logfile", "0");
INSERT INTO ###TABLE_PREFIX###gacl_aco VALUES("16", "Use Leads4web", "Delete Logfile", "2", "Delete Logfile", "0");
INSERT INTO ###TABLE_PREFIX###gacl_aco VALUES("17", "Use Leads4web", "Edit Permissions", "3", "Edit Permissions", "0");
INSERT INTO ###TABLE_PREFIX###gacl_aco VALUES("18", "Usermanager", "Show Usermanager", "10", "Show Usermanager", "0");
INSERT INTO ###TABLE_PREFIX###gacl_aco VALUES("19", "Usermanager", "Add User", "20", "Add User", "0");
INSERT INTO ###TABLE_PREFIX###gacl_aco VALUES("20", "Usermanager", "Edit User", "30", "Edit User", "0");
INSERT INTO ###TABLE_PREFIX###gacl_aco VALUES("21", "Usermanager", "Delete User", "40", "Delete User", "0");
INSERT INTO ###TABLE_PREFIX###gacl_aco VALUES("22", "Usermanager", "Edit Permissions", "50", "Edit Permissions", "0");
INSERT INTO ###TABLE_PREFIX###gacl_aco VALUES("23", "Treemanager", "Show Treemanager", "1", "Show Treemanager", "0");
INSERT INTO ###TABLE_PREFIX###gacl_aco VALUES("24", "Treemanager", "Add Element", "2", "Add Element", "0");
INSERT INTO ###TABLE_PREFIX###gacl_aco VALUES("25", "Treemanager", "Edit Element", "3", "Edit Element", "0");
INSERT INTO ###TABLE_PREFIX###gacl_aco VALUES("26", "Treemanager", "Delete Element", "4", "Delete Element", "0");
INSERT INTO ###TABLE_PREFIX###gacl_aco VALUES("27", "Treemanager", "Edit Permissions", "5", "Edit Permissions", "0");
INSERT INTO ###TABLE_PREFIX###gacl_aco VALUES("28", "Eventmanager", "Show Eventmanager", "1", "Show Eventmanager", "0");
INSERT INTO ###TABLE_PREFIX###gacl_aco VALUES("29", "Eventmanager", "Add any Event", "2", "Add any Event", "0");
INSERT INTO ###TABLE_PREFIX###gacl_aco VALUES("30", "Eventmanager", "Edit any Event", "3", "Edit any Event", "0");
INSERT INTO ###TABLE_PREFIX###gacl_aco VALUES("31", "Eventmanager", "Delete any Event", "4", "Delete any Event", "0");
INSERT INTO ###TABLE_PREFIX###gacl_aco VALUES("32", "Eventmanager", "Add own Event", "5", "Add own Event", "0");
INSERT INTO ###TABLE_PREFIX###gacl_aco VALUES("33", "Eventmanager", "Edit own Event", "6", "Edit own Event", "0");
INSERT INTO ###TABLE_PREFIX###gacl_aco VALUES("34", "Eventmanager", "Delete own Event", "7", "Delete own Event", "0");
INSERT INTO ###TABLE_PREFIX###gacl_aco VALUES("35", "Eventmanager", "Edit Permissions", "8", "Edit Permissions", "0");
INSERT INTO ###TABLE_PREFIX###gacl_aco VALUES("36", "Languages", "Manage", "1", "Manage", "0");
INSERT INTO ###TABLE_PREFIX###gacl_aco VALUES("37", "Languages", "Edit Permissions", "2", "Edit Permissions", "0");
INSERT INTO ###TABLE_PREFIX###gacl_aco VALUES("39", "Mandatormanager", "Show Mandatormanager", "1", "Show Mandatormanager", "0");
INSERT INTO ###TABLE_PREFIX###gacl_aco VALUES("40", "Mandatormanager", "Add Mandator", "2", "Add Mandator", "0");
INSERT INTO ###TABLE_PREFIX###gacl_aco VALUES("41", "Mandatormanager", "Edit Mandator", "3", "Edit Mandator", "0");
INSERT INTO ###TABLE_PREFIX###gacl_aco VALUES("42", "Mandatormanager", "Delete Mandator", "4", "Delete Mandator", "0");
INSERT INTO ###TABLE_PREFIX###gacl_aco VALUES("43", "Mandatormanager", "Edit Permissions", "5", "Edit Permissions", "0");
INSERT INTO ###TABLE_PREFIX###gacl_aco VALUES("44", "Mandatormanager", "Edit Datagrid", "6", "Edit Datagrid", "0");
INSERT INTO ###TABLE_PREFIX###gacl_aco VALUES("45", "Mandatormanager", "Switch Mandator", "7", "Switch Mandator", "0");
INSERT INTO ###TABLE_PREFIX###gacl_aco VALUES("46", "Usermanager", "Switch User", "60", "Switch User", "0");
INSERT INTO ###TABLE_PREFIX###gacl_aco VALUES("47", "Treemanager", "Edit Datagrid", "60", "Edit Datagrid", "0");
INSERT INTO ###TABLE_PREFIX###gacl_aco VALUES("48", "StatsManager", "Show BasicPageStats", "10", "Show BasicPageStats", "0");
INSERT INTO ###TABLE_PREFIX###gacl_aco VALUES("49", "StatsManager", "Show GroupPageStats", "20", "Show GroupPageStats", "0");
INSERT INTO ###TABLE_PREFIX###gacl_aco VALUES("50", "StatsManager", "Show UserPageStats", "30", "Show UserPageStats", "0");
INSERT INTO ###TABLE_PREFIX###gacl_aco VALUES("51", "StatsManager", "Edit Permissions", "40", "Edit Permissions", "0");
INSERT INTO ###TABLE_PREFIX###gacl_aco VALUES("52", "CategoryManager", "Add Category", "10", "Add Category", "0");
INSERT INTO ###TABLE_PREFIX###gacl_aco VALUES("53", "CategoryManager", "Edit Category", "20", "Edit Category", "0");
INSERT INTO ###TABLE_PREFIX###gacl_aco VALUES("54", "CategoryManager", "Delete Category", "30", "Delete Category", "0");
INSERT INTO ###TABLE_PREFIX###gacl_aco VALUES("55", "CategoryManager", "Edit Permissions", "40", "Edit Permissions", "0");
INSERT INTO ###TABLE_PREFIX###gacl_aco VALUES("56", "CategoryManager", "Show CategoryManager", "50", "Show CategoryManager", "0");
INSERT INTO ###TABLE_PREFIX###gacl_aco VALUES("57", "CategoryManager", "Edit Datagrid", "60", "Edit Datagrid", "0");


#
# Dumping data for table 'gacl_aco_map'
#

INSERT INTO ###TABLE_PREFIX###gacl_aco_map VALUES("12", "Use Leads4web", "Delete Logfile");
INSERT INTO ###TABLE_PREFIX###gacl_aco_map VALUES("12", "Use Leads4web", "Edit Permissions");
INSERT INTO ###TABLE_PREFIX###gacl_aco_map VALUES("12", "Use Leads4web", "Show Logfile");
INSERT INTO ###TABLE_PREFIX###gacl_aco_map VALUES("14", "Groupmanager", "Add Group");
INSERT INTO ###TABLE_PREFIX###gacl_aco_map VALUES("14", "Groupmanager", "Delete Group");
INSERT INTO ###TABLE_PREFIX###gacl_aco_map VALUES("14", "Groupmanager", "Edit Group");
INSERT INTO ###TABLE_PREFIX###gacl_aco_map VALUES("14", "Groupmanager", "Edit Permissions");
INSERT INTO ###TABLE_PREFIX###gacl_aco_map VALUES("14", "Groupmanager", "Show Groupmanager");
INSERT INTO ###TABLE_PREFIX###gacl_aco_map VALUES("15", "Treemanager", "Add Element");
INSERT INTO ###TABLE_PREFIX###gacl_aco_map VALUES("15", "Treemanager", "Delete Element");
INSERT INTO ###TABLE_PREFIX###gacl_aco_map VALUES("15", "Treemanager", "Edit Element");
INSERT INTO ###TABLE_PREFIX###gacl_aco_map VALUES("15", "Treemanager", "Edit Permissions");
INSERT INTO ###TABLE_PREFIX###gacl_aco_map VALUES("15", "Treemanager", "Show Treemanager");
INSERT INTO ###TABLE_PREFIX###gacl_aco_map VALUES("16", "Eventmanager", "Add any Event");
INSERT INTO ###TABLE_PREFIX###gacl_aco_map VALUES("16", "Eventmanager", "Add own Event");
INSERT INTO ###TABLE_PREFIX###gacl_aco_map VALUES("16", "Eventmanager", "Delete any Event");
INSERT INTO ###TABLE_PREFIX###gacl_aco_map VALUES("16", "Eventmanager", "Delete own Event");
INSERT INTO ###TABLE_PREFIX###gacl_aco_map VALUES("16", "Eventmanager", "Edit any Event");
INSERT INTO ###TABLE_PREFIX###gacl_aco_map VALUES("16", "Eventmanager", "Edit own Event");
INSERT INTO ###TABLE_PREFIX###gacl_aco_map VALUES("16", "Eventmanager", "Edit Permissions");
INSERT INTO ###TABLE_PREFIX###gacl_aco_map VALUES("16", "Eventmanager", "Show Eventmanager");
INSERT INTO ###TABLE_PREFIX###gacl_aco_map VALUES("17", "Languages", "Edit Permissions");
INSERT INTO ###TABLE_PREFIX###gacl_aco_map VALUES("17", "Languages", "Manage");
INSERT INTO ###TABLE_PREFIX###gacl_aco_map VALUES("23", "Mandatormanager", "Add Mandator");
INSERT INTO ###TABLE_PREFIX###gacl_aco_map VALUES("23", "Mandatormanager", "Delete Mandator");
INSERT INTO ###TABLE_PREFIX###gacl_aco_map VALUES("23", "Mandatormanager", "Edit Datagrid");
INSERT INTO ###TABLE_PREFIX###gacl_aco_map VALUES("23", "Mandatormanager", "Edit Mandator");
INSERT INTO ###TABLE_PREFIX###gacl_aco_map VALUES("23", "Mandatormanager", "Edit Permissions");
INSERT INTO ###TABLE_PREFIX###gacl_aco_map VALUES("23", "Mandatormanager", "Show Mandatormanager");
INSERT INTO ###TABLE_PREFIX###gacl_aco_map VALUES("23", "Mandatormanager", "Switch Mandator");
INSERT INTO ###TABLE_PREFIX###gacl_aco_map VALUES("25", "Usermanager", "Add User");
INSERT INTO ###TABLE_PREFIX###gacl_aco_map VALUES("25", "Usermanager", "Delete User");
INSERT INTO ###TABLE_PREFIX###gacl_aco_map VALUES("25", "Usermanager", "Edit Permissions");
INSERT INTO ###TABLE_PREFIX###gacl_aco_map VALUES("25", "Usermanager", "Edit User");
INSERT INTO ###TABLE_PREFIX###gacl_aco_map VALUES("25", "Usermanager", "Show Usermanager");
INSERT INTO ###TABLE_PREFIX###gacl_aco_map VALUES("26", "StatsManager", "Edit Permissions");
INSERT INTO ###TABLE_PREFIX###gacl_aco_map VALUES("26", "StatsManager", "Show BasicPageStats");
INSERT INTO ###TABLE_PREFIX###gacl_aco_map VALUES("26", "StatsManager", "Show GroupPageStats");
INSERT INTO ###TABLE_PREFIX###gacl_aco_map VALUES("26", "StatsManager", "Show UserPageStats");
INSERT INTO ###TABLE_PREFIX###gacl_aco_map VALUES("27", "CategoryManager", "Add Category");
INSERT INTO ###TABLE_PREFIX###gacl_aco_map VALUES("27", "CategoryManager", "Delete Category");
INSERT INTO ###TABLE_PREFIX###gacl_aco_map VALUES("27", "CategoryManager", "Edit Category");
INSERT INTO ###TABLE_PREFIX###gacl_aco_map VALUES("27", "CategoryManager", "Edit Datagrid");
INSERT INTO ###TABLE_PREFIX###gacl_aco_map VALUES("27", "CategoryManager", "Edit Permissions");
INSERT INTO ###TABLE_PREFIX###gacl_aco_map VALUES("27", "CategoryManager", "Show CategoryManager");


#
# Dumping data for table 'gacl_aco_sections'
#

INSERT INTO ###TABLE_PREFIX###gacl_aco_sections VALUES("10", "Use Leads4web", "1", "Use Leads4web", "0");
INSERT INTO ###TABLE_PREFIX###gacl_aco_sections VALUES("11", "Groupmanager", "2", "Groupmanager", "0");
INSERT INTO ###TABLE_PREFIX###gacl_aco_sections VALUES("12", "Usermanager", "3", "Usermanager", "0");
INSERT INTO ###TABLE_PREFIX###gacl_aco_sections VALUES("13", "Treemanager", "4", "Treemanager", "0");
INSERT INTO ###TABLE_PREFIX###gacl_aco_sections VALUES("14", "Eventmanager", "5", "Eventmanager", "0");
INSERT INTO ###TABLE_PREFIX###gacl_aco_sections VALUES("15", "Languages", "6", "Languages", "0");
INSERT INTO ###TABLE_PREFIX###gacl_aco_sections VALUES("16", "Mandatormanager", "7", "Mandatormanager", "0");
INSERT INTO ###TABLE_PREFIX###gacl_aco_sections VALUES("17", "StatsManager", "8", "StatsManager", "0");
INSERT INTO ###TABLE_PREFIX###gacl_aco_sections VALUES("18", "CategoryManager", "9", "CategoryManager", "0");


#
# Dumping data for table 'gacl_aco_sections_seq'
#

INSERT INTO ###TABLE_PREFIX###gacl_aco_sections_seq VALUES("18");


#
# Dumping data for table 'gacl_aco_seq'
#

INSERT INTO ###TABLE_PREFIX###gacl_aco_seq VALUES("57");


#
# Dumping data for table 'gacl_aro'
#

INSERT INTO ###TABLE_PREFIX###gacl_aro VALUES("10", "Person", "2", "1", "superadmin", "0");
INSERT INTO ###TABLE_PREFIX###gacl_aro VALUES("12", "Person", "3", "2", "tester", "0");
INSERT INTO ###TABLE_PREFIX###gacl_aro VALUES("13", "Person", "4", "3", "guest", "0");


#
# Dumping data for table 'gacl_aro_groups'
#

INSERT INTO ###TABLE_PREFIX###gacl_aro_groups VALUES("10", "0", "1", "16", "root", "1");
INSERT INTO ###TABLE_PREFIX###gacl_aro_groups VALUES("11", "10", "2", "3", "imported", "4");
INSERT INTO ###TABLE_PREFIX###gacl_aro_groups VALUES("12", "10", "4", "7", "admin", "2");
INSERT INTO ###TABLE_PREFIX###gacl_aro_groups VALUES("13", "10", "8", "9", "mycompany", "5");
INSERT INTO ###TABLE_PREFIX###gacl_aro_groups VALUES("14", "12", "5", "6", "superadmingrp", "3");
INSERT INTO ###TABLE_PREFIX###gacl_aro_groups VALUES("15", "10", "10", "11", "test", "6");
INSERT INTO ###TABLE_PREFIX###gacl_aro_groups VALUES("16", "10", "12", "13", "guest", "7");
INSERT INTO ###TABLE_PREFIX###gacl_aro_groups VALUES("17", "10", "14", "15", "DemoMandatorRootGroup", "1000");


#
# Dumping data for table 'gacl_aro_groups_id_seq'
#

INSERT INTO ###TABLE_PREFIX###gacl_aro_groups_id_seq VALUES("17");


#
# Dumping data for table 'gacl_aro_groups_map'
#



#
# Dumping data for table 'gacl_aro_map'
#

INSERT INTO ###TABLE_PREFIX###gacl_aro_map VALUES("12", "Person", "2");
INSERT INTO ###TABLE_PREFIX###gacl_aro_map VALUES("14", "Person", "2");
INSERT INTO ###TABLE_PREFIX###gacl_aro_map VALUES("15", "Person", "2");
INSERT INTO ###TABLE_PREFIX###gacl_aro_map VALUES("16", "Person", "2");
INSERT INTO ###TABLE_PREFIX###gacl_aro_map VALUES("17", "Person", "2");
INSERT INTO ###TABLE_PREFIX###gacl_aro_map VALUES("23", "Person", "2");
INSERT INTO ###TABLE_PREFIX###gacl_aro_map VALUES("25", "Person", "2");
INSERT INTO ###TABLE_PREFIX###gacl_aro_map VALUES("26", "Person", "2");
INSERT INTO ###TABLE_PREFIX###gacl_aro_map VALUES("27", "Person", "2");


#
# Dumping data for table 'gacl_aro_sections'
#

INSERT INTO ###TABLE_PREFIX###gacl_aro_sections VALUES("10", "Person", "1", "Person", "0");


#
# Dumping data for table 'gacl_aro_sections_seq'
#

INSERT INTO ###TABLE_PREFIX###gacl_aro_sections_seq VALUES("10");


#
# Dumping data for table 'gacl_aro_seq'
#

INSERT INTO ###TABLE_PREFIX###gacl_aro_seq VALUES("13");


#
# Dumping data for table 'gacl_axo'
#



#
# Dumping data for table 'gacl_axo_groups'
#



#
# Dumping data for table 'gacl_axo_groups_map'
#



#
# Dumping data for table 'gacl_axo_map'
#



#
# Dumping data for table 'gacl_axo_sections'
#



#
# Dumping data for table 'gacl_groups_aro_map'
#

INSERT INTO ###TABLE_PREFIX###gacl_groups_aro_map VALUES("11", "10");
INSERT INTO ###TABLE_PREFIX###gacl_groups_aro_map VALUES("13", "10");
INSERT INTO ###TABLE_PREFIX###gacl_groups_aro_map VALUES("14", "10");
INSERT INTO ###TABLE_PREFIX###gacl_groups_aro_map VALUES("15", "12");
INSERT INTO ###TABLE_PREFIX###gacl_groups_aro_map VALUES("16", "13");


#
# Dumping data for table 'gacl_groups_axo_map'
#



#
# Dumping data for table 'gacl_phpgacl'
#

INSERT INTO ###TABLE_PREFIX###gacl_phpgacl VALUES("version", "3.3.3");
INSERT INTO ###TABLE_PREFIX###gacl_phpgacl VALUES("schema_version", "2.1");

#=======================================================================================
# GACL End
#=======================================================================================

INSERT INTO ###TABLE_PREFIX###categories (id, object_type, name, grp) VALUES (1, 'contact', 'n/a',1);
INSERT INTO ###TABLE_PREFIX###categories (id, object_type, name, grp) VALUES (2, 'contact', 'Fehlermeldung undef',1);
INSERT INTO ###TABLE_PREFIX###categories (id, object_type, name, grp) VALUES (3, 'contact', 'Fehlermeldung undef',1);
INSERT INTO ###TABLE_PREFIX###categories (id, object_type, name, grp) VALUES (4, 'contact', 'Fehlermeldung undef',1);
INSERT INTO ###TABLE_PREFIX###categories (id, object_type, name, grp) VALUES (5, 'contact', 'Fehlermeldung undef',1);
INSERT INTO ###TABLE_PREFIX###categories (id, object_type, name, grp) VALUES (6, 'contact', 'Fehlermeldung undef',1);
INSERT INTO ###TABLE_PREFIX###categories (id, object_type, name, grp) VALUES (7, 'contact', 'Fehlermeldung undef',1);
INSERT INTO ###TABLE_PREFIX###categories (id, object_type, name, grp) VALUES (8, 'contact', 'Fehlermeldung undef',1);
INSERT INTO ###TABLE_PREFIX###categories (id, object_type, name, grp) VALUES (9, 'contact', 'Fehlermeldung undef',1);
INSERT INTO ###TABLE_PREFIX###categories (id, object_type, name, grp) VALUES (10, 'contact', 'Category 1'        ,2);
INSERT INTO ###TABLE_PREFIX###categories (id, object_type, name, grp) VALUES (11, 'contact', 'Category 2'        ,2);
INSERT INTO ###TABLE_PREFIX###categories (id, object_type, name, grp) VALUES (12, 'contact', 'Category 3'        ,2);

INSERT INTO ###TABLE_PREFIX###group_details (id, description) VALUES (1,  'root group');
INSERT INTO ###TABLE_PREFIX###group_details (id, description) VALUES (2,  'admin group');
INSERT INTO ###TABLE_PREFIX###group_details (id, description) VALUES (3,  'group for superadmins');
INSERT INTO ###TABLE_PREFIX###group_details (id, description) VALUES (4,  'group for imported entries');
INSERT INTO ###TABLE_PREFIX###group_details (id, description) VALUES (5,  'my companys group');
INSERT INTO ###TABLE_PREFIX###group_details (id, description) VALUES (6,  'test group');
INSERT INTO ###TABLE_PREFIX###group_details (id, description) VALUES (7,  'guest groups');

INSERT INTO ###TABLE_PREFIX###countries (id, country, code, short) VALUES (1,  'n/a',            		    '',      'NN');
INSERT INTO ###TABLE_PREFIX###countries (id, country, code, short) VALUES (2,  'Afghanistan',    		    '',      'AF');
INSERT INTO ###TABLE_PREFIX###countries (id, country, code, short) VALUES (3,  'Albania',        		    '++355', 'AL');
INSERT INTO ###TABLE_PREFIX###countries (id, country, code, short) VALUES (4,  'Algeria',        		    '++213', 'DZ');
INSERT INTO ###TABLE_PREFIX###countries (id, country, code, short) VALUES (5,  'American Samoa', 		    '++684', 'AS');
INSERT INTO ###TABLE_PREFIX###countries (id, country, code, short) VALUES (6,  'Andorra',        		    '++376', 'AD');
INSERT INTO ###TABLE_PREFIX###countries (id, country, code, short) VALUES (7,  'Angola',        		    '++244', 'AO');
INSERT INTO ###TABLE_PREFIX###countries (id, country, code, short) VALUES (8,  'Anguilla',       		    '++1809','AI');
INSERT INTO ###TABLE_PREFIX###countries (id, country, code, short) VALUES (9,  'Antarctica',           	'',      'AQ');
INSERT INTO ###TABLE_PREFIX###countries (id, country, code, short) VALUES (10, 'Antigua and Barbuda', 	'',      'AG');
INSERT INTO ###TABLE_PREFIX###countries (id, country, code, short) VALUES (11, 'Argentina',           	'++54',  'AR');
INSERT INTO ###TABLE_PREFIX###countries (id, country, code, short) VALUES (12, 'Armenia',				    '++7',   'AM');
INSERT INTO ###TABLE_PREFIX###countries (id, country, code, short) VALUES (13, 'Aruba',			     	'++297', 'AW');
INSERT INTO ###TABLE_PREFIX###countries (id, country, code, short) VALUES (14, 'Australia',			    '++61',  'AU');
INSERT INTO ###TABLE_PREFIX###countries (id, country, code, short) VALUES (15, 'Austria',                 '++43',  'AT');
INSERT INTO ###TABLE_PREFIX###countries (id, country, code, short) VALUES (16, 'Azerbaijan',              '++994', 'AZ');
INSERT INTO ###TABLE_PREFIX###countries (id, country, code, short) VALUES (17, 'Bahamas',				    '',      'BS');
INSERT INTO ###TABLE_PREFIX###countries (id, country, code, short) VALUES (18, 'Bahrain',				    '',      'BH');
INSERT INTO ###TABLE_PREFIX###countries (id, country, code, short) VALUES (19, 'Bangladesh',		        '++880', 'BD');
INSERT INTO ###TABLE_PREFIX###countries (id, country, code, short) VALUES (20, 'Barbados',				'++1809','BB');
INSERT INTO ###TABLE_PREFIX###countries (id, country, code, short) VALUES (21, 'Belarus',                 '',      'BY');
INSERT INTO ###TABLE_PREFIX###countries (id, country, code, short) VALUES (22, 'Belgium',				    '++32',  'BE');
INSERT INTO ###TABLE_PREFIX###countries (id, country, code, short) VALUES (23, 'Belize',				    '++501', 'BZ');
INSERT INTO ###TABLE_PREFIX###countries (id, country, code, short) VALUES (24, 'Benin',				    '',      'BJ');
INSERT INTO ###TABLE_PREFIX###countries (id, country, code, short) VALUES (25, 'Bermuda',				    '',      'BM');
INSERT INTO ###TABLE_PREFIX###countries (id, country, code, short) VALUES (26, 'Bhutan',			    	'',      'BT');
INSERT INTO ###TABLE_PREFIX###countries (id, country, code, short) VALUES (27, 'Bolivia',				    '',      'BO');
INSERT INTO ###TABLE_PREFIX###countries (id, country, code, short) VALUES (28, 'Bosnia and Herzegovina',  '++387', 'BA');
INSERT INTO ###TABLE_PREFIX###countries (id, country, code, short) VALUES (29, 'Botswana',				'',      'BW');
INSERT INTO ###TABLE_PREFIX###countries (id, country, code, short) VALUES (30, 'Bouvet Island',		    '',      'BV');
INSERT INTO ###TABLE_PREFIX###countries (id, country, code, short) VALUES (31, 'Brazil',				    '++55',  'BR');
INSERT INTO ###TABLE_PREFIX###countries (id, country, code, short) VALUES (32, 'British Indian Ocean Territory','','IO');
INSERT INTO ###TABLE_PREFIX###countries (id, country, code, short) VALUES (33, 'Brunei Darussalam',       '',      'BN');
INSERT INTO ###TABLE_PREFIX###countries (id, country, code, short) VALUES (34, 'Bulgaria',				'++359', 'BG');
INSERT INTO ###TABLE_PREFIX###countries (id, country, code, short) VALUES (35, 'Burkina Faso',			'',      'BF');
INSERT INTO ###TABLE_PREFIX###countries (id, country, code, short) VALUES (36, 'Burundi',				'',  'BI');
INSERT INTO ###TABLE_PREFIX###countries (id, country, code, short) VALUES (37,'Cambodia',				'',  'KH');
INSERT INTO ###TABLE_PREFIX###countries (id, country, code, short) VALUES (38,'Cameroon',				'',  'CM');
INSERT INTO ###TABLE_PREFIX###countries (id, country, code, short) VALUES (39,'Canada',				'++1',    'CA');
INSERT INTO ###TABLE_PREFIX###countries (id, country, code, short) VALUES (40,'Cape	Verde',			'',   'CV');
INSERT INTO ###TABLE_PREFIX###countries (id, country, code, short) VALUES (41,'Cayman Islands',		'',  'KY');
INSERT INTO ###TABLE_PREFIX###countries (id, country, code, short) VALUES (42,'Central African Republic','',  'CF');
INSERT INTO ###TABLE_PREFIX###countries (id, country, code, short) VALUES (43,'Chad',					'',  'TD');
INSERT INTO ###TABLE_PREFIX###countries (id, country, code, short) VALUES (44,'Chile',				'',  'CL');
INSERT INTO ###TABLE_PREFIX###countries (id, country, code, short) VALUES (45,'China',				'',  'CN');
INSERT INTO ###TABLE_PREFIX###countries (id, country, code, short) VALUES (46,'Christmas Island',		'',  'CX');
INSERT INTO ###TABLE_PREFIX###countries (id, country, code, short) VALUES (47,'Cocos (Keeling) Islands','','CC');
INSERT INTO ###TABLE_PREFIX###countries (id, country, code, short) VALUES (48,'Colombia',				'',  'CO');
INSERT INTO ###TABLE_PREFIX###countries (id, country, code, short) VALUES (49,'Comoros',				'',  'KM');
INSERT INTO ###TABLE_PREFIX###countries (id, country, code, short) VALUES (50,'Congo',				'',  'CG');
INSERT INTO ###TABLE_PREFIX###countries (id, country, code, short) VALUES (51,'Cook	Islands',		'',  'CK');
INSERT INTO ###TABLE_PREFIX###countries (id, country, code, short) VALUES (52,'Costa Rica',			'',  'CR');
INSERT INTO ###TABLE_PREFIX###countries (id, country, code, short) VALUES (53,'Croatia (Hrvatska)',	'',  'HR');
INSERT INTO ###TABLE_PREFIX###countries (id, country, code, short) VALUES (54,'Cuba',					'',  'CU');
INSERT INTO ###TABLE_PREFIX###countries (id, country, code, short) VALUES (55,'Cyprus',				'',  'CY');
INSERT INTO ###TABLE_PREFIX###countries (id, country, code, short) VALUES (56,'Czech Republic',		'',  'CZ');
INSERT INTO ###TABLE_PREFIX###countries (id, country, code, short) VALUES (57,'Czechoslovakia (former)','',  'CS');
INSERT INTO ###TABLE_PREFIX###countries (id, country, code, short) VALUES (58,'Denmark',				'++45',  'DK');
INSERT INTO ###TABLE_PREFIX###countries (id, country, code, short) VALUES (59,'Djibouti',             '',  'DJ');
INSERT INTO ###TABLE_PREFIX###countries (id, country, code, short) VALUES (60,'Dominica',				'',  'DM');
INSERT INTO ###TABLE_PREFIX###countries (id, country, code, short) VALUES (61,'Dominican Republic',	'',  'DO');
INSERT INTO ###TABLE_PREFIX###countries (id, country, code, short) VALUES (62,'East	Timor',			'',  'TP');
INSERT INTO ###TABLE_PREFIX###countries (id, country, code, short) VALUES (63,'Ecuador',				'',  'EC');
INSERT INTO ###TABLE_PREFIX###countries (id, country, code, short) VALUES (64,'Egypt',				'',  'EG');
INSERT INTO ###TABLE_PREFIX###countries (id, country, code, short) VALUES (65,'El Salvador',			'',  'SV');
INSERT INTO ###TABLE_PREFIX###countries (id, country, code, short) VALUES (66,'Equatorial Guinea',	'',  'GQ');
INSERT INTO ###TABLE_PREFIX###countries (id, country, code, short) VALUES (67,'Eritrea',				'',  'ER');
INSERT INTO ###TABLE_PREFIX###countries (id, country, code, short) VALUES (68,'Estonia',				'',  'EE');
INSERT INTO ###TABLE_PREFIX###countries (id, country, code, short) VALUES (69,'Ethiopia',				'',  'ET');
INSERT INTO ###TABLE_PREFIX###countries (id, country, code, short) VALUES (70,'Falkland	Islands	(Malvinas)','',  'FK');
INSERT INTO ###TABLE_PREFIX###countries (id, country, code, short) VALUES (71,'Faroe Islands',		'',  'FO');
INSERT INTO ###TABLE_PREFIX###countries (id, country, code, short) VALUES (72,'Fiji',					'',  'FJ');
INSERT INTO ###TABLE_PREFIX###countries (id, country, code, short) VALUES (73,'Finland',				'',  'FI');
INSERT INTO ###TABLE_PREFIX###countries (id, country, code, short) VALUES (74,'France',				'++33',  'FR');
INSERT INTO ###TABLE_PREFIX###countries (id, country, code, short) VALUES (75,'France, Metropolitan',	'',  'FX');
INSERT INTO ###TABLE_PREFIX###countries (id, country, code, short) VALUES (76,'French Guiana',		'',  'GF');
INSERT INTO ###TABLE_PREFIX###countries (id, country, code, short) VALUES (77,'French Polynesia',		'',  'PF');
INSERT INTO ###TABLE_PREFIX###countries (id, country, code, short) VALUES (78,'French Southern Territories','',  'TF');
INSERT INTO ###TABLE_PREFIX###countries (id, country, code, short) VALUES (79,'Gabon',				'',  'GA');
INSERT INTO ###TABLE_PREFIX###countries (id, country, code, short) VALUES (80,'Gambia',				'',  'GM');
INSERT INTO ###TABLE_PREFIX###countries (id, country, code, short) VALUES (81,'Georgia',				'',  'GE');
INSERT INTO ###TABLE_PREFIX###countries (id, country, code, short) VALUES (82,'Germany',				'++49',  'DE');
INSERT INTO ###TABLE_PREFIX###countries (id, country, code, short) VALUES (83,'Ghana',				'',  'GH');
INSERT INTO ###TABLE_PREFIX###countries (id, country, code, short) VALUES (84,'Gibraltar',			'',  'GI');
INSERT INTO ###TABLE_PREFIX###countries (id, country, code, short) VALUES (85,'Great Britain (UK)',	'++44',  'GB');
INSERT INTO ###TABLE_PREFIX###countries (id, country, code, short) VALUES (86,'Greece',				'++30',  'GR');
INSERT INTO ###TABLE_PREFIX###countries (id, country, code, short) VALUES (87,'Greenland',			'',  'GL');
INSERT INTO ###TABLE_PREFIX###countries (id, country, code, short) VALUES (88,'Grenada',				'+1809',  'GD');
INSERT INTO ###TABLE_PREFIX###countries (id, country, code, short) VALUES (89,'Guadeloupe',			'',  'GP');
INSERT INTO ###TABLE_PREFIX###countries (id, country, code, short) VALUES (90,'Guam',					'',  'GU');
INSERT INTO ###TABLE_PREFIX###countries (id, country, code, short) VALUES (91,'Guatemala',			'',  'GT');
INSERT INTO ###TABLE_PREFIX###countries (id, country, code, short) VALUES (92,'Guinea',				'',  'GN');
INSERT INTO ###TABLE_PREFIX###countries (id, country, code, short) VALUES (93,'Guinea-Bissau',		'',  'GW');
INSERT INTO ###TABLE_PREFIX###countries (id, country, code, short) VALUES (94,'Guyana',				'',  'GY');
INSERT INTO ###TABLE_PREFIX###countries (id, country, code, short) VALUES (95,'Haiti',				'',  'HT');
INSERT INTO ###TABLE_PREFIX###countries (id, country, code, short) VALUES (96,'Heard and McDonald Islands','',  'HM');
INSERT INTO ###TABLE_PREFIX###countries (id, country, code, short) VALUES (97,'Honduras',				'',  'HN');
INSERT INTO ###TABLE_PREFIX###countries (id, country, code, short) VALUES (98,'Hong	Kong',			'',  'HK');
INSERT INTO ###TABLE_PREFIX###countries (id, country, code, short) VALUES (99,'Hungary',				'',  'HU');
INSERT INTO ###TABLE_PREFIX###countries (id, country, code, short) VALUES (100,'Iceland',				'',  'IS');
INSERT INTO ###TABLE_PREFIX###countries (id, country, code, short) VALUES (101,'India',				'',  'IN');
INSERT INTO ###TABLE_PREFIX###countries (id, country, code, short) VALUES (102,'Indonesia',			'',  'ID');
INSERT INTO ###TABLE_PREFIX###countries (id, country, code, short) VALUES (103,'Iran',				'',  'IR');
INSERT INTO ###TABLE_PREFIX###countries (id, country, code, short) VALUES (104,'Iraq',				'',  'IQ');
INSERT INTO ###TABLE_PREFIX###countries (id, country, code, short) VALUES (105,'Ireland',				'++353',  'IE');
INSERT INTO ###TABLE_PREFIX###countries (id, country, code, short) VALUES (106,'Israel',				'',  'IL');
INSERT INTO ###TABLE_PREFIX###countries (id, country, code, short) VALUES (107,'Italy',				'',  'IT');
INSERT INTO ###TABLE_PREFIX###countries (id, country, code, short) VALUES (108,'Jamaica',				'',  'JM');
INSERT INTO ###TABLE_PREFIX###countries (id, country, code, short) VALUES (109,'Japan',				'',  'JP');
INSERT INTO ###TABLE_PREFIX###countries (id, country, code, short) VALUES (110,'Jordan',				'',  'JO');
INSERT INTO ###TABLE_PREFIX###countries (id, country, code, short) VALUES (111,'Kazakhstan',			'',  'KZ');
INSERT INTO ###TABLE_PREFIX###countries (id, country, code, short) VALUES (112,'Kenya',				'',  'KE');
INSERT INTO ###TABLE_PREFIX###countries (id, country, code, short) VALUES (113,'Kiribati',			'',  'KI');
INSERT INTO ###TABLE_PREFIX###countries (id, country, code, short) VALUES (114,'Korea (North)',		'',  'KP');
INSERT INTO ###TABLE_PREFIX###countries (id, country, code, short) VALUES (115,'Korea (South)',		'',  'KR');
INSERT INTO ###TABLE_PREFIX###countries (id, country, code, short) VALUES (116,'Kuwait',				'',  'KW');
INSERT INTO ###TABLE_PREFIX###countries (id, country, code, short) VALUES (117,'Kyrgyzstan',			'',  'KG');
INSERT INTO ###TABLE_PREFIX###countries (id, country, code, short) VALUES (118,'Laos',				'',  'LA');
INSERT INTO ###TABLE_PREFIX###countries (id, country, code, short) VALUES (119,'Latvia',				'',  'LV');
INSERT INTO ###TABLE_PREFIX###countries (id, country, code, short) VALUES (120,'Lebanon',				'',  'LB');
INSERT INTO ###TABLE_PREFIX###countries (id, country, code, short) VALUES (121,'Lesotho',				'',  'LS');
INSERT INTO ###TABLE_PREFIX###countries (id, country, code, short) VALUES (122,'Liberia',				'',  'LR');
INSERT INTO ###TABLE_PREFIX###countries (id, country, code, short) VALUES (123,'Libya',				'',  'LY');
INSERT INTO ###TABLE_PREFIX###countries (id, country, code, short) VALUES (124,'Liechtenstein',		'',  'LI');
INSERT INTO ###TABLE_PREFIX###countries (id, country, code, short) VALUES (125,'Lithuania',			'',  'LT');
INSERT INTO ###TABLE_PREFIX###countries (id, country, code, short) VALUES (126,'Luxembourg',			'',  'LU');
INSERT INTO ###TABLE_PREFIX###countries (id, country, code, short) VALUES (127,'Macau',				'',  'MO');
INSERT INTO ###TABLE_PREFIX###countries (id, country, code, short) VALUES (128,'Macedonia',			'',  'MK');
INSERT INTO ###TABLE_PREFIX###countries (id, country, code, short) VALUES (129,'Madagascar',			'',  'MG');
INSERT INTO ###TABLE_PREFIX###countries (id, country, code, short) VALUES (130,'Malawi',				'',  'MW');
INSERT INTO ###TABLE_PREFIX###countries (id, country, code, short) VALUES (131,'Malaysia',			'',  'MY');
INSERT INTO ###TABLE_PREFIX###countries (id, country, code, short) VALUES (132,'Maldives',			'',  'MV');
INSERT INTO ###TABLE_PREFIX###countries (id, country, code, short) VALUES (133,'Mali',				'',  'ML');
INSERT INTO ###TABLE_PREFIX###countries (id, country, code, short) VALUES (134,'Malta',				'',  'MT');
INSERT INTO ###TABLE_PREFIX###countries (id, country, code, short) VALUES (135,'Marshall Islands',	'',  'MH');
INSERT INTO ###TABLE_PREFIX###countries (id, country, code, short) VALUES (136,'Martinique',			'',  'MQ');
INSERT INTO ###TABLE_PREFIX###countries (id, country, code, short) VALUES (137,'Mauritania',			'',  'MR');
INSERT INTO ###TABLE_PREFIX###countries (id, country, code, short) VALUES (138,'Mauritius',			'',  'MU');
INSERT INTO ###TABLE_PREFIX###countries (id, country, code, short) VALUES (139,'Mayotte',				'',  'YT');
INSERT INTO ###TABLE_PREFIX###countries (id, country, code, short) VALUES (140,'Mexico',				'',  'MX');
INSERT INTO ###TABLE_PREFIX###countries (id, country, code, short) VALUES (141,'Micronesia',			'',  'FM');
INSERT INTO ###TABLE_PREFIX###countries (id, country, code, short) VALUES (142,'Moldova',				'',  'MD');
INSERT INTO ###TABLE_PREFIX###countries (id, country, code, short) VALUES (143,'Monaco',				'',  'MC');
INSERT INTO ###TABLE_PREFIX###countries (id, country, code, short) VALUES (144,'Mongolia',			'',  'MN');
INSERT INTO ###TABLE_PREFIX###countries (id, country, code, short) VALUES (145,'Montserrat',			'',  'MS');
INSERT INTO ###TABLE_PREFIX###countries (id, country, code, short) VALUES (146,'Morocco',				'',  'MA');
INSERT INTO ###TABLE_PREFIX###countries (id, country, code, short) VALUES (147,'Mozambique',			'',  'MZ');
INSERT INTO ###TABLE_PREFIX###countries (id, country, code, short) VALUES (148,'Myanmar',				'',  'MM');
INSERT INTO ###TABLE_PREFIX###countries (id, country, code, short) VALUES (149,'Namibia',				'',  'NA');
INSERT INTO ###TABLE_PREFIX###countries (id, country, code, short) VALUES (150,'Nauru',				'',  'NR');
INSERT INTO ###TABLE_PREFIX###countries (id, country, code, short) VALUES (151,'Nepal',				'',  'NP');
INSERT INTO ###TABLE_PREFIX###countries (id, country, code, short) VALUES (152,'Netherlands',			'',  'NL');
INSERT INTO ###TABLE_PREFIX###countries (id, country, code, short) VALUES (153,'Netherlands Antilles','',  'AN');
INSERT INTO ###TABLE_PREFIX###countries (id, country, code, short) VALUES (154,'Neutral Zone',		'',  'NT');
INSERT INTO ###TABLE_PREFIX###countries (id, country, code, short) VALUES (155,'New Caledonia',		'',  'NC');
INSERT INTO ###TABLE_PREFIX###countries (id, country, code, short) VALUES (156,'New Zealand (Aotearoa)','','NZ');
INSERT INTO ###TABLE_PREFIX###countries (id, country, code, short) VALUES (157,'Nicaragua',			'',  'NI');
INSERT INTO ###TABLE_PREFIX###countries (id, country, code, short) VALUES (158,'Niger',				'',  'NE');
INSERT INTO ###TABLE_PREFIX###countries (id, country, code, short) VALUES (159,'Nigeria',				'',  'NG');
INSERT INTO ###TABLE_PREFIX###countries (id, country, code, short) VALUES (160,'Niue',				'',  'NU');
INSERT INTO ###TABLE_PREFIX###countries (id, country, code, short) VALUES (161,'Norfolk Island',		'',  'NF');
INSERT INTO ###TABLE_PREFIX###countries (id, country, code, short) VALUES (162,'Northern	Mariana	Islands','',  'MP');
INSERT INTO ###TABLE_PREFIX###countries (id, country, code, short) VALUES (163,'Norway',				'',  'NO');
INSERT INTO ###TABLE_PREFIX###countries (id, country, code, short) VALUES (164,'Oman',				'',  'OM');
INSERT INTO ###TABLE_PREFIX###countries (id, country, code, short) VALUES (165,'Pakistan',			'',  'PK');
INSERT INTO ###TABLE_PREFIX###countries (id, country, code, short) VALUES (166,'Palau',				'',  'PW');
INSERT INTO ###TABLE_PREFIX###countries (id, country, code, short) VALUES (167,'Panama',				'',  'PA');
INSERT INTO ###TABLE_PREFIX###countries (id, country, code, short) VALUES (168,'Papua New Guinea',	'',  'PG');
INSERT INTO ###TABLE_PREFIX###countries (id, country, code, short) VALUES (169,'Paraguay',			'',  'PY');
INSERT INTO ###TABLE_PREFIX###countries (id, country, code, short) VALUES (170,'Peru',				'',  'PE');
INSERT INTO ###TABLE_PREFIX###countries (id, country, code, short) VALUES (171,'Philippines',			'',  'PH');
INSERT INTO ###TABLE_PREFIX###countries (id, country, code, short) VALUES (172,'Pitcairn',			'',  'PN');
INSERT INTO ###TABLE_PREFIX###countries (id, country, code, short) VALUES (173,'Poland',				'',  'PL');
INSERT INTO ###TABLE_PREFIX###countries (id, country, code, short) VALUES (174,'Portugal',			'',  'PT');
INSERT INTO ###TABLE_PREFIX###countries (id, country, code, short) VALUES (175,'Puerto Rico',			'',  'PR');
INSERT INTO ###TABLE_PREFIX###countries (id, country, code, short) VALUES (176,'Qatar',				'',  'QA');
INSERT INTO ###TABLE_PREFIX###countries (id, country, code, short) VALUES (177,'Reunion',				'',  'RE');
INSERT INTO ###TABLE_PREFIX###countries (id, country, code, short) VALUES (178,'Romania',				'',  'RO');
INSERT INTO ###TABLE_PREFIX###countries (id, country, code, short) VALUES (179,'Russian Federation',	'',  'RU');
INSERT INTO ###TABLE_PREFIX###countries (id, country, code, short) VALUES (180,'Rwanda',				'',  'RW');
INSERT INTO ###TABLE_PREFIX###countries (id, country, code, short) VALUES (181,'S. Georgia and S. Sandwich Isls.','',  'GS');
INSERT INTO ###TABLE_PREFIX###countries (id, country, code, short) VALUES (182,'Saint Kitts and Nevis','',  'KN');
INSERT INTO ###TABLE_PREFIX###countries (id, country, code, short) VALUES (183,'Saint Lucia','',  'LC');
INSERT INTO ###TABLE_PREFIX###countries (id, country, code, short) VALUES (184,'Saint Vincent and the Grenadines','',  'VC');
INSERT INTO ###TABLE_PREFIX###countries (id, country, code, short) VALUES (185,'Samoa',				'',  'WS');
INSERT INTO ###TABLE_PREFIX###countries (id, country, code, short) VALUES (186,'San Marino',			'',  'SM');
INSERT INTO ###TABLE_PREFIX###countries (id, country, code, short) VALUES (187,'Sao Tome	and	Principe','',  'ST');
INSERT INTO ###TABLE_PREFIX###countries (id, country, code, short) VALUES (188,'Saudi Arabia',        '',  'SA');
INSERT INTO ###TABLE_PREFIX###countries (id, country, code, short) VALUES (189,'Senegal',             '',  'SN');
INSERT INTO ###TABLE_PREFIX###countries (id, country, code, short) VALUES (190,'Seychelles',          '',  'SC');
INSERT INTO ###TABLE_PREFIX###countries (id, country, code, short) VALUES (191,'Sierra Leone',        '',  'SL');
INSERT INTO ###TABLE_PREFIX###countries (id, country, code, short) VALUES (192,'Singapore',           '',  'SG');
INSERT INTO ###TABLE_PREFIX###countries (id, country, code, short) VALUES (193,'Slovak Republic',     '',  'SK');
INSERT INTO ###TABLE_PREFIX###countries (id, country, code, short) VALUES (194,'Slovenia',            '',  'SI');
INSERT INTO ###TABLE_PREFIX###countries (id, country, code, short) VALUES (195,'Solomon Islands',     '',  'Sb');
INSERT INTO ###TABLE_PREFIX###countries (id, country, code, short) VALUES (196,'Somalia',             '',  'SO');
INSERT INTO ###TABLE_PREFIX###countries (id, country, code, short) VALUES (197,'South Africa',        '',  'ZA');
INSERT INTO ###TABLE_PREFIX###countries (id, country, code, short) VALUES (198,'Spain',               '',  'ES');
INSERT INTO ###TABLE_PREFIX###countries (id, country, code, short) VALUES (199,'Sri Lanka',           '',  'LK');
INSERT INTO ###TABLE_PREFIX###countries (id, country, code, short) VALUES (200,'St. Helena',          ''  ,'SH');
INSERT INTO ###TABLE_PREFIX###countries (id, country, code, short) VALUES (201,'St. Pierre and Miquelon','',  'PM');
INSERT INTO ###TABLE_PREFIX###countries (id, country, code, short) VALUES (202,'Sudan','',  'SD');
INSERT INTO ###TABLE_PREFIX###countries (id, country, code, short) VALUES (203,'Suriname','',  'SR');
INSERT INTO ###TABLE_PREFIX###countries (id, country, code, short) VALUES (204,'Svalbard	and	Jan	Mayen Islands','',  'SJ');
INSERT INTO ###TABLE_PREFIX###countries (id, country, code, short) VALUES (205,'Swaziland','',  'SZ');
INSERT INTO ###TABLE_PREFIX###countries (id, country, code, short) VALUES (206,'Sweden','',  'SE');
INSERT INTO ###TABLE_PREFIX###countries (id, country, code, short) VALUES (207,'Switzerland','',  'CH');
INSERT INTO ###TABLE_PREFIX###countries (id, country, code, short) VALUES (208,'Syria','',  'SY');
INSERT INTO ###TABLE_PREFIX###countries (id, country, code, short) VALUES (209,'Taiwan','',  'TW');
INSERT INTO ###TABLE_PREFIX###countries (id, country, code, short) VALUES (210,'Tajikistan','',  'TJ');
INSERT INTO ###TABLE_PREFIX###countries (id, country, code, short) VALUES (211,'Tanzania','',  'TZ');
INSERT INTO ###TABLE_PREFIX###countries (id, country, code, short) VALUES (212,'Thailand','',  'TH');
INSERT INTO ###TABLE_PREFIX###countries (id, country, code, short) VALUES (213,'Togo','',  'TG');
INSERT INTO ###TABLE_PREFIX###countries (id, country, code, short) VALUES (214,'Tokelau','',  'TK');
INSERT INTO ###TABLE_PREFIX###countries (id, country, code, short) VALUES (215,'Tonga','',  'TO');
INSERT INTO ###TABLE_PREFIX###countries (id, country, code, short) VALUES (216,'Trinidad	and	Tobago','',  'TT');
INSERT INTO ###TABLE_PREFIX###countries (id, country, code, short) VALUES (217,'Tunisia','',  'TN');
INSERT INTO ###TABLE_PREFIX###countries (id, country, code, short) VALUES (218,'Turkey','',  'TR');
INSERT INTO ###TABLE_PREFIX###countries (id, country, code, short) VALUES (219,'Turkmenistan','',  'TM');
INSERT INTO ###TABLE_PREFIX###countries (id, country, code, short) VALUES (220,'Turks and Caicos	Islands','',  'TC');
INSERT INTO ###TABLE_PREFIX###countries (id, country, code, short) VALUES (221,'Tuvalu','',  'TV');
INSERT INTO ###TABLE_PREFIX###countries (id, country, code, short) VALUES (222,'US Minor	Outlying Islands','',  'UM');
INSERT INTO ###TABLE_PREFIX###countries (id, country, code, short) VALUES (223,'USSR	(former)','',  'SU');
INSERT INTO ###TABLE_PREFIX###countries (id, country, code, short) VALUES (224,'Uganda','',  'UG');
INSERT INTO ###TABLE_PREFIX###countries (id, country, code, short) VALUES (225,'Ukraine','',  'UA');
INSERT INTO ###TABLE_PREFIX###countries (id, country, code, short) VALUES (226,'United Arab Emirates','',  'AE');
INSERT INTO ###TABLE_PREFIX###countries (id, country, code, short) VALUES (227,'United Kingdom','',  'UK');
INSERT INTO ###TABLE_PREFIX###countries (id, country, code, short) VALUES (228,'United States','',  'US');
INSERT INTO ###TABLE_PREFIX###countries (id, country, code, short) VALUES (229,'Uruguay','',  'UY');
INSERT INTO ###TABLE_PREFIX###countries (id, country, code, short) VALUES (230,'Uzbekistan','',  'UZ');
INSERT INTO ###TABLE_PREFIX###countries (id, country, code, short) VALUES (231,'Vanuatu','',  'VU');
INSERT INTO ###TABLE_PREFIX###countries (id, country, code, short) VALUES (232,'Vatican City	State (Holy	See)','',  'VA');
INSERT INTO ###TABLE_PREFIX###countries (id, country, code, short) VALUES (233,'Venezuela','',  'VE');
INSERT INTO ###TABLE_PREFIX###countries (id, country, code, short) VALUES (234,'Viet	Nam','',  'VN');
INSERT INTO ###TABLE_PREFIX###countries (id, country, code, short) VALUES (235,'Virgin Islands (British)','',  'VG');
INSERT INTO ###TABLE_PREFIX###countries (id, country, code, short) VALUES (236,'Virgin Islands (U.S.)','',  'VI');
INSERT INTO ###TABLE_PREFIX###countries (id, country, code, short) VALUES (237,'Wallis and Futuna Islands','',  'WF');
INSERT INTO ###TABLE_PREFIX###countries (id, country, code, short) VALUES (238,'Western Sahara','',  'EH');
INSERT INTO ###TABLE_PREFIX###countries (id, country, code, short) VALUES (239,'Yemen','',  'YE');
INSERT INTO ###TABLE_PREFIX###countries (id, country, code, short) VALUES (240,'Yugoslavia','',  'YU');
INSERT INTO ###TABLE_PREFIX###countries (id, country, code, short) VALUES (241,'Zaire','',  'ZR');
INSERT INTO ###TABLE_PREFIX###countries (id, country, code, short) VALUES (242,'Zambia','',  'ZM');
INSERT INTO ###TABLE_PREFIX###countries (id, country, code, short) VALUES (243,'Zimbabwe','',  'ZW');

INSERT INTO ###TABLE_PREFIX###skins (id, name, img_path, css_path) VALUES (1,'default', 'img/default/', 'css/default/');
INSERT INTO ###TABLE_PREFIX###skins (id, name, img_path, css_path) VALUES (2,'metal',   'img/metal/',   'css/metal/');
INSERT INTO ###TABLE_PREFIX###skins (id, name, img_path, css_path) VALUES (3,'Allianz', 'img/Allianz/', 'css/Allianz/');
INSERT INTO ###TABLE_PREFIX###skins (id, name, img_path, css_path) VALUES (4,'eclipse', 'img/eclipse/','css/eclipse/');

INSERT INTO ###TABLE_PREFIX###languages (lang_id, language, set_local_str, aktiv, order_nr, filename) VALUES ('1','german', 'ger',1,1,'german');
INSERT INTO ###TABLE_PREFIX###languages (lang_id, language, set_local_str, aktiv, order_nr, filename) VALUES ('2','english','eng',1,2,'english');




INSERT INTO ###TABLE_PREFIX###tree VALUES("1",  "0", "leads4web/4",     "", "", "", "", "-2", NULL, "0", "1", "0", "1", "1");
INSERT INTO ###TABLE_PREFIX###tree VALUES("2",  "1", "contacts",        "", "", "contacts.gif", "", "1", NULL, "1", "1", "0", "1", "1");
INSERT INTO ###TABLE_PREFIX###tree VALUES("7",  "2", "new contact",     "../../modules/contacts/index.php?command=add_contact_view", "l4w_main", "", '', "-1", NULL, "1", "1", "0", "1", "1");
INSERT INTO ###TABLE_PREFIX###tree VALUES("8",  "2", "browse",          "../../modules/contacts/index.php?command=show_entries", "l4w_main",     "", '', "0", NULL, "1", "1", "0", "1", "1");
#INSERT INTO ###TABLE_PREFIX###tree VALUES("12","2", "locked contacts", "../../modules/contacts/index.php?command=show_locked",  "l4w_main",    "", '', "0", NULL, "1", "1", "0", "1", "1");
INSERT INTO ###TABLE_PREFIX###tree VALUES("3",  "1", "options",         "", "", "admin.gif", "", "6", "", "1", "1", "0", "0", "1");
INSERT INTO ###TABLE_PREFIX###tree VALUES("9",  "3", "skins",           "", "", "", "", "1", "~~skins~~", "1", "1", "0", "0", "1");
INSERT INTO ###TABLE_PREFIX###tree VALUES("10", "3", "languages",       "", "", "", "", "1", "~~languages~~", "1", "1", "0", "0", "1");
INSERT INTO ###TABLE_PREFIX###tree VALUES("4",  "1", "administration",  "", "", "admin.gif", "", "7", "~~rights~~", "1", "1", "0", "1", "1");
INSERT INTO ###TABLE_PREFIX###tree VALUES("11", "1", "statistic", "",   "", "stats.gif", "", "7", "~~stats~~", "1", "1", "0", "0", "1");
#INSERT INTO ###TABLE_PREFIX###tree VALUES("5", "1", "quicklinks",      "../../quicklinks.php", "l4w_main", "quicklinks.gif", "", "9", NULL, "1", "1", "0", "0", "1");
INSERT INTO ###TABLE_PREFIX###tree VALUES("6",  "1", "logout",          "../../logout.php", "_top", "logout.gif", "", "10", NULL, "1", "1", "0", "1", "1");

INSERT INTO ###TABLE_PREFIX###tree (id, parent, name, link, frame, img, sign, order_nr, subtree_identifier, translate, enabled, authorize, protected) 
VALUES (13, 1, 'news', '', 'l4w_main', 'news.gif', '', 0, NULL, '1', '1', '0', '0');

INSERT INTO ###TABLE_PREFIX###tree (id, parent, name, link, frame, img, sign, order_nr, subtree_identifier, translate, enabled, authorize, protected) 
VALUES (14, 13, 'current news', '../../modules/news/index.php?command=show_current_news', 'l4w_main', '', '', 0, NULL, '1', '1', '0', '0');

INSERT INTO ###TABLE_PREFIX###tree (id, parent, name, link, frame, img, sign, order_nr, subtree_identifier, translate, enabled, authorize, protected) 
VALUES (15, 13, 'all news', '../../modules/news/index.php?command=show_all_news', 'l4w_main', '', '', 0, NULL, '1', '1', '0', '0');

INSERT INTO ###TABLE_PREFIX###tree (id, parent, name, link, frame, img, sign, order_nr, subtree_identifier, translate, enabled, authorize, protected) 
VALUES (16, 1, 'documents', '', 'l4w_main', 'docs.gif', '', 3, NULL, '1', '1', '0', '0');

INSERT INTO ###TABLE_PREFIX###tree (id, parent, name, link, frame, img, sign, order_nr, subtree_identifier, translate, enabled, authorize, protected) 
VALUES (17, 16, 'new document', '../../modules/docs/index.php?command=add_doc&parent=0', 'l4w_main', '', '', 1, NULL, '1', '1', '0', '0');

INSERT INTO ###TABLE_PREFIX###tree (id, parent, name, link, frame, img, sign, order_nr, subtree_identifier, translate, enabled, authorize, protected) 
VALUES (18, 16, 'browse', '../../modules/docs/index.php?command=show_entries', 'l4w_main', '', '', 1, NULL, '1', '1', '0', '0');

### notes ###

INSERT INTO ###TABLE_PREFIX###tree (id, parent, name, link, frame, img, sign, order_nr, subtree_identifier, translate, enabled, authorize, protected) 
VALUES (19, 1, 'notes', '', 'l4w_main', 'notes.gif', '', 4, NULL, '1', '1', '0', '0');

INSERT INTO ###TABLE_PREFIX###tree (id, parent, name, link, frame, img, sign, order_nr, subtree_identifier, translate, enabled, authorize, protected) 
VALUES (20, 19, 'new note',   '../../modules/notes/index.php?command=add_entry_view', 'l4w_main',  '', '', 1, NULL, '1', '1', '0', '0');

INSERT INTO ###TABLE_PREFIX###tree (id, parent, name, link, frame, img, sign, order_nr, subtree_identifier, translate, enabled, authorize, protected) 
VALUES (21, 19, 'new folder', '../../modules/notes/index.php?command=add_folder_view', 'l4w_main', '', '', 2, NULL, '1', '1', '0', '0');

INSERT INTO ###TABLE_PREFIX###tree (id, parent, name, link, frame, img, sign, order_nr, subtree_identifier, translate, enabled, authorize, protected) 
VALUES (22, 19, 'browse',     '../../modules/notes/index.php?command=show_entries',    'l4w_main', '', '', 3, NULL, '1', '1', '0', '0');



#INSERT INTO ###TABLE_PREFIX###tree (id, parent, name, link, frame, img, sign, order_nr, subtree_identifier, translate, enabled, authorize, protected) 
#VALUES (23, 1, 'diverse', '', 'l4w_main', 'diverse.gif', '', 6, NULL, '1', '1', '0', '0');

#INSERT INTO ###TABLE_PREFIX###tree (id, parent, name, link, frame, img, sign, order_nr, subtree_identifier, translate, enabled, authorize, protected) 
#VALUES (24, 23, 'Amazon DVD Service', 'http://www.amapsys.de/index.php', 'l4w_main', '', '', 1, NULL, '1', '1', '0', '0');

INSERT INTO ###TABLE_PREFIX###tree (id, parent, name, link, frame, img, sign, order_nr, subtree_identifier, translate, enabled, authorize, protected) 
VALUES (25, 1, 'tickets', '', 'l4w_main', 'tickets.gif', '', 5, NULL, '1', '1', '0', '0');

INSERT INTO ###TABLE_PREFIX###tree (id, parent, name, link, frame, img, sign, order_nr, subtree_identifier, translate, enabled, authorize, protected) 
VALUES (26, 25, 'new ticket', '../../modules/tickets/index.php?command=add_ticket_view', 'l4w_main', '', '', 1, NULL, '1', '1', '0', '0');

INSERT INTO ###TABLE_PREFIX###tree (id, parent, name, link, frame, img, sign, order_nr, subtree_identifier, translate, enabled, authorize, protected) 
VALUES (27, 25, 'browse', '../../modules/tickets/index.php?command=show_entries', 'l4w_main', '', '', 2, NULL, '1', '1', '0', '0');

INSERT INTO ###TABLE_PREFIX###tree (id, parent, name, link, frame, img, sign, order_nr, subtree_identifier, translate, enabled, authorize, protected) 
VALUES (28, 1, 'todos', '', 'l4w_main', 'todos.gif', '', 5, NULL, '1', '1', '0', '0');

INSERT INTO ###TABLE_PREFIX###tree (id, parent, name, link, frame, img, sign, order_nr, subtree_identifier, translate, enabled, authorize, protected) 
VALUES (29, 28, 'new todo', '../../modules/todos/index.php?command=add_entry_view', 'l4w_main', '', '', 1, NULL, '1', '1', '0', '0');

INSERT INTO ###TABLE_PREFIX###tree (id, parent, name, link, frame, img, sign, order_nr, subtree_identifier, translate, enabled, authorize, protected) 
VALUES (30, 28, 'browse',   '../../modules/todos/index.php?command=show_entries', 'l4w_main', '', '', 2, NULL, '1', '1', '0', '0');

#INSERT INTO ###TABLE_PREFIX###tree VALUES("31",  "1", "email",           "", "", "mail.gif", "", "2", "~~emails~~", "1", "1", "0", "0");
INSERT INTO ###TABLE_PREFIX###tree (id, parent, name, link, frame, img, sign, order_nr, subtree_identifier, translate, enabled, authorize, protected) 
VALUES (31, 1, 'email',   '', '', 'mail.gif', '', 2, '~~emails~~', '1', '1', '0', '0');

INSERT INTO ###TABLE_PREFIX###tree (id, parent, name, link, frame, img, sign, order_nr, subtree_identifier, translate, enabled, authorize, protected) 
VALUES (32, 25, 'new folder',   '../../modules/tickets/index.php?command=add_folder_view', 'l4w_main', '', '', 3, '', '1', '1', '0', '0');

INSERT INTO ###TABLE_PREFIX###tree (parent, name, link, frame, img, sign, order_nr, subtree_identifier, translate, enabled, authorize, protected) 
VALUES (25, 'templates',   '../../modules/templates/index.php?command=show_entries&type=ticket', 'l4w_main', '', '', 4, '', '1', '1', '0', '0');

# states for contacts

INSERT INTO ###TABLE_PREFIX###states (mandator, reference, status, name, color, startpoint) VALUES (1, 'contact',-1, 'undefined', '#000066','0');
INSERT INTO ###TABLE_PREFIX###states (mandator, reference, status, name, color, startpoint) VALUES (1, 'contact', 0, 'new', '#000066','1');
INSERT INTO ###TABLE_PREFIX###states (mandator, reference, status, name, color, startpoint) VALUES (1, 'contact', 1, 'changed', '#000066','0');
INSERT INTO ###TABLE_PREFIX###states (mandator, reference, status, name, color, startpoint) VALUES (1, 'contact', 2, 'accepted', '#000066','0');
INSERT INTO ###TABLE_PREFIX###states (mandator, reference, status, name, color, startpoint) VALUES (1, 'contact', 3, 'not accepted', '#000066','0');
INSERT INTO ###TABLE_PREFIX###states (mandator, reference, status, name, color, startpoint) VALUES (1, 'contact', 4, 'to delete', '#000066','0');
INSERT INTO ###TABLE_PREFIX###states (mandator, reference, status, name, color, startpoint) VALUES (1, 'contact', 5, 'imported from l4w3', '#000066','0');

# reference, grp, user, old, new, name(internal note), isdefault
INSERT INTO ###TABLE_PREFIX###transitions VALUES (1, 'contact', 0,  0, 0, 1, 'everyone can transit from new to changed',         '1');
INSERT INTO ###TABLE_PREFIX###transitions VALUES (1, 'contact', 14, 0, 0, 2, 'grp 14 users can transit from new to accepted',     '1');
INSERT INTO ###TABLE_PREFIX###transitions VALUES (1, 'contact', 14, 0, 0, 3, 'grp 14 users can transit from new to not accepted', '0');
INSERT INTO ###TABLE_PREFIX###transitions VALUES (1, 'contact', 14, 0, 0, 4, 'grp 14 users can transit from new to to delete',    '0');

INSERT INTO ###TABLE_PREFIX###transitions VALUES (1, 'contact', 0,  0, 1, 1, 'changed and changed again (for all)',              '1');
INSERT INTO ###TABLE_PREFIX###transitions VALUES (1, 'contact', 14, 0, 1, 2, 'grp 14 users can transit from new to accepted',     '1');
INSERT INTO ###TABLE_PREFIX###transitions VALUES (1, 'contact', 14, 0, 1, 3, 'grp 14 users can transit from new to not accepted', '0');
INSERT INTO ###TABLE_PREFIX###transitions VALUES (1, 'contact', 14, 0, 1, 4, 'grp 14 users can transit from new to to delete',    '0');

INSERT INTO ###TABLE_PREFIX###transitions VALUES (1, 'contact', 0,  0, 2, 2, 'leave as is for all',                              '1');
INSERT INTO ###TABLE_PREFIX###transitions VALUES (1, 'contact', 0,  0, 3, 3, 'leave as is for all',                              '1');
INSERT INTO ###TABLE_PREFIX###transitions VALUES (1, 'contact', 0,  0, 4, 4, 'leave as is for all',                              '1');
INSERT INTO ###TABLE_PREFIX###transitions VALUES (1, 'contact', 0,  0, 5, 5, 'leave as is for all',                              '1');

# states for tickets

INSERT INTO ###TABLE_PREFIX###states (mandator, reference, status, name, color, startpoint) VALUES (1, 'ticket',-1, 'undefined', '#000066','0');
INSERT INTO ###TABLE_PREFIX###states (mandator, reference, status, name, color, startpoint) VALUES (1, 'ticket', 0, 'new', '#000066','1');
INSERT INTO ###TABLE_PREFIX###states (mandator, reference, status, name, color, startpoint) VALUES (1, 'ticket', 1, 'assigned', '#000066','0');
INSERT INTO ###TABLE_PREFIX###states (mandator, reference, status, name, color, startpoint) VALUES (1, 'ticket', 2, 'worked on', '#000066','0');
INSERT INTO ###TABLE_PREFIX###states (mandator, reference, status, name, color, startpoint) VALUES (1, 'ticket', 3, 'resolved', '#000066','0');
INSERT INTO ###TABLE_PREFIX###states (mandator, reference, status, name, color, startpoint) VALUES (1, 'ticket', 4, 'resolution confirmed', '#000066','0');
INSERT INTO ###TABLE_PREFIX###states (mandator, reference, status, name, color, startpoint) VALUES (1, 'ticket', 5, 'deferred', '#000066','0');
INSERT INTO ###TABLE_PREFIX###states (mandator, reference, status, name, color, startpoint) VALUES (1, 'ticket', 6, 'to delete', '#000066','0');
INSERT INTO ###TABLE_PREFIX###states (mandator, reference, status, name, color, startpoint) VALUES (1, 'ticket', 7, 'reopened', '#000066','0');


# reference, grp, user, old, new, name(internal note), isdefault
INSERT INTO ###TABLE_PREFIX###transitions VALUES (1, 'ticket', 0,  0, 0, 1, 'everyone can transit from new to assigned',         '1');
INSERT INTO ###TABLE_PREFIX###transitions VALUES (1, 'ticket', 14, 0, 0, 2, 'grp 14 users can transit from new to worked on',    '1');
INSERT INTO ###TABLE_PREFIX###transitions VALUES (1, 'ticket', 14, 0, 0, 5, 'grp 14 users can transit from new to to delete',    '0');

INSERT INTO ###TABLE_PREFIX###transitions VALUES (1, 'ticket', 0,  0, 1, 1, 'still assigned',                                    '1');
INSERT INTO ###TABLE_PREFIX###transitions VALUES (1, 'ticket', 0,  0, 1, 2, 'everyone can transit from assigned to worked on',   '1');
INSERT INTO ###TABLE_PREFIX###transitions VALUES (1, 'ticket', 0,  0, 1, 3, '',                                                  '0');
INSERT INTO ###TABLE_PREFIX###transitions VALUES (1, 'ticket', 14, 0, 1, 4, '',                                                  '0');
INSERT INTO ###TABLE_PREFIX###transitions VALUES (1, 'ticket', 14, 0, 1, 5, '',                                                  '0');

INSERT INTO ###TABLE_PREFIX###transitions VALUES (1, 'ticket', 0,  0, 2, 2, 'leave as is for all',                               '1');
INSERT INTO ###TABLE_PREFIX###transitions VALUES (1, 'ticket', 0,  0, 2, 3, '',                                                  '1');
INSERT INTO ###TABLE_PREFIX###transitions VALUES (1, 'ticket', 14, 0, 2, 4, '',                                                  '1');
INSERT INTO ###TABLE_PREFIX###transitions VALUES (1, 'ticket', 14, 0, 2, 3, '',                                                  '1');

INSERT INTO ###TABLE_PREFIX###transitions VALUES (1, 'ticket', 0,  0, 3, 3, 'leave as is for all',                              '1');
INSERT INTO ###TABLE_PREFIX###transitions VALUES (1, 'ticket', 0,  0, 3, 4, '',                                                 '1');

INSERT INTO ###TABLE_PREFIX###transitions VALUES (1, 'ticket', 0,  0, 4, 4, 'leave as is for all',                              '1');

INSERT INTO ###TABLE_PREFIX###transitions VALUES (1, 'ticket', 0,  0, 5, 5, 'leave as is for all',                              '1');

INSERT INTO ###TABLE_PREFIX###transitions VALUES (1, 'ticket', 0,  0, 6, 6, 'leave as is for all',                              '1');

INSERT INTO ###TABLE_PREFIX###transitions VALUES (1, 'ticket', 0,  0, 7, 7, 'leave as is for all',                              '1');


INSERT INTO ###TABLE_PREFIX###states (mandator, reference, status, name, color, startpoint) VALUES (1, 'todo',-1, 'undefined',             '#c0c0c0', '0');
INSERT INTO ###TABLE_PREFIX###states (mandator, reference, status, name, color, startpoint) VALUES (1, 'todo', 0, 'new',                    '#FF8000', '1');
INSERT INTO ###TABLE_PREFIX###states (mandator, reference, status, name, color, startpoint) VALUES (1, 'todo', 1, 'assigned',               '#000066', '0');
INSERT INTO ###TABLE_PREFIX###states (mandator, reference, status, name, color, startpoint) VALUES (1, 'todo', 2, 'worked on',              '#004000', '0');
INSERT INTO ###TABLE_PREFIX###states (mandator, reference, status, name, color, startpoint) VALUES (1, 'todo', 3, 'resolved',               '#00FF00', '0');
INSERT INTO ###TABLE_PREFIX###states (mandator, reference, status, name, color, startpoint) VALUES (1, 'todo', 4, 'resolution confirmed',   '#00FFFF', '0');
INSERT INTO ###TABLE_PREFIX###states (mandator, reference, status, name, color, startpoint) VALUES (1, 'todo', 5, 'deferred',               '#8000FF', '0');
INSERT INTO ###TABLE_PREFIX###states (mandator, reference, status, name, color, startpoint) VALUES (1, 'todo', 6, 'to delete',              '#FFFF80', '0');
INSERT INTO ###TABLE_PREFIX###states (mandator, reference, status, name, color, startpoint) VALUES (1, 'todo', 7, 'reopened',               '#FF0000', '0');


# reference, grp, user, old, new, name(internal note), isdefault
INSERT INTO ###TABLE_PREFIX###transitions VALUES (1, 'todo', 0,  0, 0, 1, 'everyone can transit from new to assigned',         '1');
INSERT INTO ###TABLE_PREFIX###transitions VALUES (1, 'todo', 14, 0, 0, 2, 'grp 14 users can transit from new to worked on',    '1');
INSERT INTO ###TABLE_PREFIX###transitions VALUES (1, 'todo', 14, 0, 0, 5, 'grp 14 users can transit from new to to delete',    '0');

INSERT INTO ###TABLE_PREFIX###transitions VALUES (1, 'todo', 0,  0, 1, 1, 'still assigned',                                    '1');
INSERT INTO ###TABLE_PREFIX###transitions VALUES (1, 'todo', 0,  0, 1, 2, 'everyone can transit from assigned to worked on',   '1');
INSERT INTO ###TABLE_PREFIX###transitions VALUES (1, 'todo', 0,  0, 1, 3, '',                                                  '0');
INSERT INTO ###TABLE_PREFIX###transitions VALUES (1, 'todo', 14, 0, 1, 4, '',                                                  '0');
INSERT INTO ###TABLE_PREFIX###transitions VALUES (1, 'todo', 14, 0, 1, 5, '',                                                  '0');

INSERT INTO ###TABLE_PREFIX###transitions VALUES (1, 'todo', 0,  0, 2, 2, 'leave as is for all',                               '1');
INSERT INTO ###TABLE_PREFIX###transitions VALUES (1, 'todo', 0,  0, 2, 3, '',                                                  '1');
INSERT INTO ###TABLE_PREFIX###transitions VALUES (1, 'todo', 14, 0, 2, 4, '',                                                  '1');
INSERT INTO ###TABLE_PREFIX###transitions VALUES (1, 'todo', 14, 0, 2, 3, '',                                                  '1');

INSERT INTO ###TABLE_PREFIX###transitions VALUES (1, 'todo', 0,  0, 3, 3, 'leave as is for all',                              '1');
INSERT INTO ###TABLE_PREFIX###transitions VALUES (1, 'todo', 0,  0, 3, 4, '',                                                 '1');

INSERT INTO ###TABLE_PREFIX###transitions VALUES (1, 'todo', 0,  0, 4, 4, 'leave as is for all',                              '1');

INSERT INTO ###TABLE_PREFIX###transitions VALUES (1, 'todo', 0,  0, 5, 5, 'leave as is for all',                              '1');

INSERT INTO ###TABLE_PREFIX###transitions VALUES (1, 'todo', 0,  0, 6, 6, 'leave as is for all',                              '1');

INSERT INTO ###TABLE_PREFIX###transitions VALUES (1, 'todo', 0,  0, 7, 7, 'leave as is for all',                              '1');


INSERT INTO ###TABLE_PREFIX###user_details (
	user_id) 
VALUES (2);								

INSERT INTO ###TABLE_PREFIX###user_details (
	user_id) 
VALUES (3);								

INSERT INTO ###TABLE_PREFIX###user_details (
	user_id) 
VALUES (4);								

INSERT INTO ###TABLE_PREFIX###users (
	id, login,  password, 
	grp, salutation, firstname, 
	lastname, email)
VALUES    
   (2,  'superadmin','fc6144c4e0e7ea625cc2c826ff883275',
    14,'','Max',
    'Admin','mymail@myserver.de');
    
INSERT INTO ###TABLE_PREFIX###users (
	id, login,  password, 
	grp, salutation, firstname, 
	lastname, email)
VALUES    
   (3,  'test','fc6144c4e0e7ea625cc2c826ff883275',
    15,'','Tim',
    'tester','mymail@myserver.de');
    
INSERT INTO ###TABLE_PREFIX###users (
	id, login,  password, 
	grp, salutation, firstname, 
	lastname, email)
VALUES    
   (4,  'guest','',
    16,'','guest',
    'guest','guest');
    
INSERT INTO ###TABLE_PREFIX###user_mandator (user_id, mandator_id) VALUES (2,1);    
INSERT INTO ###TABLE_PREFIX###user_mandator (user_id, mandator_id) VALUES (3,1);    
    
INSERT INTO ###TABLE_PREFIX###events VALUES (1,  'contact',      'new',        'entry was added',                    0, NULL, 'system', 'new_entry', 'dummy subject', 0);
INSERT INTO ###TABLE_PREFIX###events VALUES (2,  'contact',      'changed',    'entry was changed',                  0, NULL, 'system', '', 'dummy subject', 0);
INSERT INTO ###TABLE_PREFIX###events VALUES (3,  'contact',      '-1',         'state changed to undefined',         0, NULL, 'workflow', '', 'dummy subject', 0);
INSERT INTO ###TABLE_PREFIX###events VALUES (4,  'contact',      '0',          'state changed to new',               0, NULL, 'workflow', '', 'dummy subject', 0);
INSERT INTO ###TABLE_PREFIX###events VALUES (5,  'contact',      '1',          'state changed to changed',           0, NULL, 'workflow', '', 'dummy subject', 0);
INSERT INTO ###TABLE_PREFIX###events VALUES (6,  'contact',      '2',          'state changed to accepted',          0, NULL, 'workflow', '', 'dummy subject', 0);
INSERT INTO ###TABLE_PREFIX###events VALUES (7,  'contact',      '3',          'state changed to not accepted',      0, NULL, 'workflow', '', 'dummy subject', 0);
INSERT INTO ###TABLE_PREFIX###events VALUES (8,  'contact',      '4',          'state changed to to delete',         0, NULL, 'workflow', '', 'dummy subject', 0);
INSERT INTO ###TABLE_PREFIX###events VALUES (9,  'contact',      '5',          'state changed to imported',          0, NULL, 'workflow', '', 'dummy subject', 0);
INSERT INTO ###TABLE_PREFIX###events VALUES (10, 'contact',      'deleted',    'entry was deleted',                  0, NULL, 'system',   '', 'dummy subject', 0);
INSERT INTO ###TABLE_PREFIX###events VALUES (46, 'contact',      'assigned',   'contact was assigned',               0, NULL, 'system',   'assign_entry',  'contact assigned', 4);

INSERT INTO ###TABLE_PREFIX###events VALUES (11, 'doc',          'new folder',     'folder was added',               0, NULL, 'system', '', 'dummy subject', 0);
INSERT INTO ###TABLE_PREFIX###events VALUES (12, 'doc',          'changed folder', 'folder was changed',             0, NULL, 'system', '', 'dummy subject', 0);
INSERT INTO ###TABLE_PREFIX###events VALUES (13, 'doc',          'deleted folder', 'folder was deleted',             0, NULL, 'system', '', 'dummy subject', 0);

INSERT INTO ###TABLE_PREFIX###events VALUES (14, 'doc',          'new document',     'document was added',           0, NULL, 'system', '', 'dummy subject', 0);
INSERT INTO ###TABLE_PREFIX###events VALUES (15, 'doc',          'changed document', 'document was changed',         0, NULL, 'system', '', 'dummy subject', 0);
INSERT INTO ###TABLE_PREFIX###events VALUES (16, 'doc',          'deleted document', 'document was deleted',         0, NULL, 'system', '', 'dummy subject', 0);

INSERT INTO ###TABLE_PREFIX###events VALUES (17, 'note',         'new note',         'note was added',               0, NULL, 'system', 'new_entry', 'dummy subject', 0);
INSERT INTO ###TABLE_PREFIX###events VALUES (18, 'note',         'changed note',     'note was changed',             0, NULL, 'system', '', 'dummy subject', 0);
INSERT INTO ###TABLE_PREFIX###events VALUES (19, 'note',         'deleted note',     'note was deleted',             0, NULL, 'system', '', 'dummy subject', 0);
INSERT INTO ###TABLE_PREFIX###events VALUES (26, 'note',         'new folder',       'folder for notes was added',   0, NULL, 'system', '', 'dummy subject', 0);
INSERT INTO ###TABLE_PREFIX###events VALUES (27, 'note',         'changed folder',   'folder for notes was changed', 0, NULL, 'system', '', 'dummy subject', 0);
INSERT INTO ###TABLE_PREFIX###events VALUES (28, 'note',         'deleted folder',   'folder for notes was deleted', 0, NULL, 'system', '', 'dummy subject', 0);

INSERT INTO ###TABLE_PREFIX###events VALUES (20, 'collection',         'new collection',         'collection was added',           0, NULL, 'system', 'new_entry', 'dummy subject', 0);
INSERT INTO ###TABLE_PREFIX###events VALUES (21, 'collection',         'changed collection',     'collection was changed',         0, NULL, 'system', '', 'dummy subject', 0);
INSERT INTO ###TABLE_PREFIX###events VALUES (22, 'collection',         'deleted collection',     'collection was deleted',         0, NULL, 'system', '', 'dummy subject', 0);

INSERT INTO ###TABLE_PREFIX###events VALUES (23, 'ticket',       'new ticket',         'ticket was added',               0, NULL, 'system', 'new_entry',     'dummy subject', 0);
INSERT INTO ###TABLE_PREFIX###events VALUES (24, 'ticket',       'changed ticket',     'ticket was changed',             0, NULL, 'system', '',              'dummy subject', 0);
INSERT INTO ###TABLE_PREFIX###events VALUES (25, 'ticket',       'deleted ticket',     'ticket was deleted',             0, NULL, 'system', '',              'dummy subject', 0);
INSERT INTO ###TABLE_PREFIX###events VALUES (35, 'ticket',       'new folder',         'folder for tickets was added',   0, NULL, 'system', '',              'dummy subject', 0);
INSERT INTO ###TABLE_PREFIX###events VALUES (36, 'ticket',       'changed folder',     'folder for tickets was changed', 0, NULL, 'system', '',              'dummy subject', 0);
INSERT INTO ###TABLE_PREFIX###events VALUES (37, 'ticket',       'deleted folder',     'folder for tickets was deleted', 0, NULL, 'system', '',              'dummy subject', 0);
INSERT INTO ###TABLE_PREFIX###events VALUES (39, 'ticket',       'assigned',           'ticket was assigned',            0, NULL, 'system', 'assign_entry',  'ticket assigned', 4);
INSERT INTO ###TABLE_PREFIX###events VALUES (47, 'ticket',       'ticket reminder',    'reached due date',               0, NULL, 'cron',   '',              'ricket reminder', 5);

INSERT INTO ###TABLE_PREFIX###events VALUES (29, 'todo',         'new todo',         'todo was added',               0, NULL, 'system', 'new_entry', 'dummy subject', 0);
INSERT INTO ###TABLE_PREFIX###events VALUES (30, 'todo',         'changed todo',     'todo was changed',             0, NULL, 'system', '', 'dummy subject', 0);
INSERT INTO ###TABLE_PREFIX###events VALUES (31, 'todo',         'deleted todo',     'todo was deleted',             0, NULL, 'system', '', 'dummy subject', 0);
INSERT INTO ###TABLE_PREFIX###events VALUES (32, 'todo',         'new folder',       'folder for todos was added',   0, NULL, 'system', '', 'dummy subject', 0);
INSERT INTO ###TABLE_PREFIX###events VALUES (33, 'todo',         'changed folder',   'folder for todos was changed', 0, NULL, 'system', '', 'dummy subject', 0);
INSERT INTO ###TABLE_PREFIX###events VALUES (34, 'todo',         'deleted folder',   'folder for todos was deleted', 0, NULL, 'system', '', 'dummy subject', 0);

INSERT INTO ###TABLE_PREFIX###events VALUES (38, 'email',        'sending failed',   'sending mail from leads4web failed', 0, NULL, 'system', '', '', 0);

INSERT INTO ###TABLE_PREFIX###events VALUES (40, 'faq',          'new faq',          'faq was added',               0, NULL, 'system', 'new_entry', 'dummy subject', 0);
INSERT INTO ###TABLE_PREFIX###events VALUES (41, 'faq',          'changed faq',      'faq was changed',             0, NULL, 'system', '', 'dummy subject', 0);
INSERT INTO ###TABLE_PREFIX###events VALUES (42, 'faq',          'deleted faq',      'faq was deleted',             0, NULL, 'system', '', 'dummy subject', 0);
INSERT INTO ###TABLE_PREFIX###events VALUES (43, 'faq',          'new folder',       'folder for faqs was added',   0, NULL, 'system', '', 'dummy subject', 0);
INSERT INTO ###TABLE_PREFIX###events VALUES (44, 'faq',          'changed folder',   'folder for faqs was changed', 0, NULL, 'system', '', 'dummy subject', 0);
INSERT INTO ###TABLE_PREFIX###events VALUES (45, 'faq',          'deleted folder',   'folder for faqs was deleted', 0, NULL, 'system', '', 'dummy subject', 0);


INSERT INTO ###TABLE_PREFIX###actions VALUES (1, 'news',      'add_news',           'add news to news database', '1');
INSERT INTO ###TABLE_PREFIX###actions VALUES (2, 'jabber',    'send2jabber',        'send news to my jabber account', '1');
INSERT INTO ###TABLE_PREFIX###actions VALUES (3, 'email',     'sendmail',           'send mail', '1');
INSERT INTO ###TABLE_PREFIX###actions VALUES (4, 'assigned',  'entryAssignedEvent', 'send mail as of assignment','0');
INSERT INTO ###TABLE_PREFIX###actions VALUES (5, 'reminders', 'checkItemsToRemind', 'reminder Function',         '0');

INSERT INTO ###TABLE_PREFIX###eventwatcher VALUES (1, 2, 1, 0, 0, 1);    

INSERT INTO ###TABLE_PREFIX###access_options VALUES (1, 1, '-rwx------', 'private',      'access_private.gif');
INSERT INTO ###TABLE_PREFIX###access_options VALUES (2, 1, '-rwxr-----', 'groupread',    'access_grpread.gif');
INSERT INTO ###TABLE_PREFIX###access_options VALUES (3, 1, '-rwxrw----', 'groupwrite',   'access_grpwrite.gif');
INSERT INTO ###TABLE_PREFIX###access_options VALUES (4, 1, '-rwxrwx---', 'groupdelete',  'access_grpdel.gif');
INSERT INTO ###TABLE_PREFIX###access_options VALUES (5, 1, '-rwxrwxr--', 'publicread',   'access_pubread.gif');
INSERT INTO ###TABLE_PREFIX###access_options VALUES (6, 1, '-rwxrwxrw-', 'publicwrite',  'access_pubwrite.gif');
INSERT INTO ###TABLE_PREFIX###access_options VALUES (7, 1, '-rwxrwxrwx', 'publicdelete', 'access_pubdel.gif');

INSERT INTO ###TABLE_PREFIX###refering_types VALUES (1, 'weak',     'simple reference type, no dependencies');
INSERT INTO ###TABLE_PREFIX###refering_types VALUES (2, 'heredity', 'from-object passes owner, group and access_level to to-object (change when to-object changes)');
INSERT INTO ###TABLE_PREFIX###refering_types VALUES (3, 'extern',   'reference to external link (url)');

INSERT INTO ###TABLE_PREFIX###priorities VALUES (1, 1, 'high',     'high',   '1', 1, '#ff0000');
INSERT INTO ###TABLE_PREFIX###priorities VALUES (2, 1, 'medium',   'medium', '1', 2, '#000066');
INSERT INTO ###TABLE_PREFIX###priorities VALUES (3, 1, 'low',      'low',    '1', 3, '#000000');

#rfc 1738
INSERT INTO ###TABLE_PREFIX###url_schemes VALUES (1, 'http',     'http reference',    1);
INSERT INTO ###TABLE_PREFIX###url_schemes VALUES (2, 'file',     'file reference',    2);
INSERT INTO ###TABLE_PREFIX###url_schemes VALUES (3, 'mailto',   'mailto reference',  3);

# 
INSERT INTO ###TABLE_PREFIX###category_component (component_id, category_id) VALUES (200,1);
          
# add leads
INSERT INTO ###TABLE_PREFIX###collections (collection_id, mandator, parent, is_dir, name, description) 
	VALUES (1, 1, 0, '1', 'Leads', 'leads as category');         
INSERT INTO ###TABLE_PREFIX###collections (collection_id, mandator, parent, is_dir, name, description) 
	VALUES (2, 1, 1, '0', 'A', 'Important customer');         
INSERT INTO ###TABLE_PREFIX###collections (collection_id, mandator, parent, is_dir, name, description) 
	VALUES (3, 1, 1, '0', 'B', 'Might become an important customer');        
INSERT INTO ###TABLE_PREFIX###collections (collection_id, mandator, parent, is_dir, name, description) 
	VALUES (4, 1, 1, '0', 'C', 'Might become a customer');        
         
INSERT INTO ###TABLE_PREFIX###datagrids (datagrid_id, mandator_id, name, aco_section, description)                  VALUES (1,  1, 'mandators',          '', '');
INSERT INTO ###TABLE_PREFIX###datagrids (datagrid_id, mandator_id, name, aco_section, description)                  VALUES (2,  1, 'notes',              '', '');
INSERT INTO ###TABLE_PREFIX###datagrids (datagrid_id, mandator_id, name, aco_section, description)                  VALUES (3,  1, 'users',              '', '');
INSERT INTO ###TABLE_PREFIX###datagrids (datagrid_id, mandator_id, name, aco_section, description)                  VALUES (4,  1, 'mandators->users',   '', '');
INSERT INTO ###TABLE_PREFIX###datagrids (datagrid_id, mandator_id, name, aco_section, description)                  VALUES (5,  1, 'groupshierarchy',    '', '');
INSERT INTO ###TABLE_PREFIX###datagrids (datagrid_id, mandator_id, name, aco_section, description)                  VALUES (6,  1, 'docs',               '', '');
INSERT INTO ###TABLE_PREFIX###datagrids (datagrid_id, mandator_id, name, aco_section, description)                  VALUES (7,  1, 'states->references', '',  '');
INSERT INTO ###TABLE_PREFIX###datagrids (datagrid_id, mandator_id, name, aco_section, description)                  VALUES (8,  1, 'states',             '', '');
INSERT INTO ###TABLE_PREFIX###datagrids (datagrid_id, mandator_id, name, aco_section, description)                  VALUES (9,  1, 'transitions',        '',  '');
INSERT INTO ###TABLE_PREFIX###datagrids (datagrid_id, mandator_id, name, aco_section, description)                  VALUES (10, 1, 'categories',         'CategoryManager', '');
INSERT INTO ###TABLE_PREFIX###datagrids (datagrid_id, mandator_id, name, aco_section, description, searchButtonCol) VALUES (11, 1, 'tickets',            '',  '', 11);


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

INSERT INTO ###TABLE_PREFIX###datagrid_columns (datagrid_id, column_id, column_identifier, column_name, visible, is_primary, description, order_nr, width, searchable) VALUES("5", "1", "id",          "id",       "0", "1", "", "1", NULL, "0");
INSERT INTO ###TABLE_PREFIX###datagrid_columns (datagrid_id, column_id, column_identifier, column_name, visible, is_primary, description, order_nr, width, searchable) VALUES("5", "2", "name",        "name",     "1", "0", "", "2", NULL, "0");
INSERT INTO ###TABLE_PREFIX###datagrid_columns (datagrid_id, column_id, column_identifier, column_name, visible, is_primary, description, order_nr, width, searchable) VALUES("5", "3", "value",       "value",    "1", "0", "", "3", NULL, "0");
INSERT INTO ###TABLE_PREFIX###datagrid_columns (datagrid_id, column_id, column_identifier, column_name, visible, is_primary, description, order_nr, width, searchable) VALUES("5", "4", "cnt",         "count",    "1", "0", "", "4", NULL, "0");
INSERT INTO ###TABLE_PREFIX###datagrid_columns (datagrid_id, column_id, column_identifier, column_name, visible, is_primary, description, order_nr, width, searchable) VALUES("5", "5", "mandator_id", "mandator", "1", "0", "", "5", NULL, "0");
INSERT INTO ###TABLE_PREFIX###datagrid_columns (datagrid_id, column_id, column_identifier, column_name, visible, is_primary, description, order_nr, width, searchable) VALUES("5", "6", "parent_id",   "parent",   "1", "0", "", "6", NULL, "0");

INSERT INTO ###TABLE_PREFIX###datagrid_columns (datagrid_id, column_id, column_identifier, column_name, visible, is_primary, description, order_nr, width, searchable) VALUES("6", "1",  "doc_id",       "doc_id",       "0", "1", "", "1",  NULL, "0");
INSERT INTO ###TABLE_PREFIX###datagrid_columns (datagrid_id, column_id, column_identifier, column_name, visible, is_primary, description, order_nr, width, searchable) VALUES("6", "2",  "checkboxes",   "checkboxes",   "1", "0", "", "2",  "30", "0");
INSERT INTO ###TABLE_PREFIX###datagrid_columns (datagrid_id, column_id, column_identifier, column_name, visible, is_primary, description, order_nr, width, searchable) VALUES("6", "3",  "is_dir",       "is_dir",       "0", "0", "", "3",  NULL, "0");
INSERT INTO ###TABLE_PREFIX###datagrid_columns (datagrid_id, column_id, column_identifier, column_name, visible, is_primary, description, order_nr, width, searchable) VALUES("6", "4",  "parent",       "parent",       "0", "0", "", "4",  NULL, "0");
INSERT INTO ###TABLE_PREFIX###datagrid_columns (datagrid_id, column_id, column_identifier, column_name, visible, is_primary, description, order_nr, width, searchable) VALUES("6", "5",  "name",         "name",         "1", "0", "", "5",  NULL, "0");
INSERT INTO ###TABLE_PREFIX###datagrid_columns (datagrid_id, column_id, column_identifier, column_name, visible, is_primary, description, order_nr, width, searchable) VALUES("6", "6",  "fullpath",     "fullpath",     "0", "0", "", "7",  NULL, "0");
INSERT INTO ###TABLE_PREFIX###datagrid_columns (datagrid_id, column_id, column_identifier, column_name, visible, is_primary, description, order_nr, width, searchable) VALUES("6", "7",  "category",     "category",     "0", "0", "", "8",  NULL, "0");
INSERT INTO ###TABLE_PREFIX###datagrid_columns (datagrid_id, column_id, column_identifier, column_name, visible, is_primary, description, order_nr, width, searchable) VALUES("6", "8",  "description",  "description",  "1", "0", "", "9",  NULL, "0");
INSERT INTO ###TABLE_PREFIX###datagrid_columns (datagrid_id, column_id, column_identifier, column_name, visible, is_primary, description, order_nr, width, searchable) VALUES("6", "9",  "group_name",   "group_name",   "1", "0", "", "10", NULL, "0");
INSERT INTO ###TABLE_PREFIX###datagrid_columns (datagrid_id, column_id, column_identifier, column_name, visible, is_primary, description, order_nr, width, searchable) VALUES("6", "10", "owner",        "owner",        "1", "0", "", "11", NULL, "0");
INSERT INTO ###TABLE_PREFIX###datagrid_columns (datagrid_id, column_id, column_identifier, column_name, visible, is_primary, description, order_nr, width, searchable) VALUES("6", "11", "created",      "created",      "1", "0", "", "12", NULL, "0");
INSERT INTO ###TABLE_PREFIX###datagrid_columns (datagrid_id, column_id, column_identifier, column_name, visible, is_primary, description, order_nr, width, searchable) VALUES("6", "12", "access_level", "access_level", "0", "0", "", "13", NULL, "0");
INSERT INTO ###TABLE_PREFIX###datagrid_columns (datagrid_id, column_id, column_identifier, column_name, visible, is_primary, description, order_nr, width, searchable) VALUES("6", "13", "owner_id",     "owner_id",     "0", "0", "", "14", NULL, "0");
INSERT INTO ###TABLE_PREFIX###datagrid_columns (datagrid_id, column_id, column_identifier, column_name, visible, is_primary, description, order_nr, width, searchable) VALUES("6", "14", "group_id",     "group_id",     "0", "0", "", "15", NULL, "0");
INSERT INTO ###TABLE_PREFIX###datagrid_columns (datagrid_id, column_id, column_identifier, column_name, visible, is_primary, description, order_nr, width, searchable) VALUES("6", "15", "color",        "color",        "0", "0", "", "16", NULL, "0");
INSERT INTO ###TABLE_PREFIX###datagrid_columns (datagrid_id, column_id, column_identifier, column_name, visible, is_primary, description, order_nr, width, searchable) VALUES("6", "16", "actions",      "",             "1", "0", "", "17", NULL, "0");
INSERT INTO ###TABLE_PREFIX###datagrid_columns (datagrid_id, column_id, column_identifier, column_name, visible, is_primary, description, order_nr, width, searchable) VALUES("6", "17", "getDocType",   "",             "1", "0", "", "6",  NULL, "0");

INSERT INTO ###TABLE_PREFIX###datagrid_columns (datagrid_id, column_id, column_identifier, column_name, visible, is_primary, description, order_nr, width, searchable) VALUES("7", "1", "reference",     "type",         "1", "1", "", "1",  NULL, "0");
INSERT INTO ###TABLE_PREFIX###datagrid_columns (datagrid_id, column_id, column_identifier, column_name, visible, is_primary, description, order_nr, width, searchable) VALUES("7", "2", "action",        "action",       "1", "0", "", "2",  NULL, "0");

INSERT INTO ###TABLE_PREFIX###datagrid_columns (datagrid_id, column_id, column_identifier, column_name, visible, is_primary, description, order_nr, width, searchable) VALUES("8", "1", "reference",     "type",         "1", "1", "", "1",  NULL, "0");
INSERT INTO ###TABLE_PREFIX###datagrid_columns (datagrid_id, column_id, column_identifier, column_name, visible, is_primary, description, order_nr, width, searchable) VALUES("8", "2", "name",          "name",         "1", "1", "", "2",  NULL, "0");
INSERT INTO ###TABLE_PREFIX###datagrid_columns (datagrid_id, column_id, column_identifier, column_name, visible, is_primary, description, order_nr, width, searchable) VALUES("8", "3", "status",        "status",       "0", "0", "", "3",  NULL, "0");
INSERT INTO ###TABLE_PREFIX###datagrid_columns (datagrid_id, column_id, column_identifier, column_name, visible, is_primary, description, order_nr, width, searchable) VALUES("8", "4", "startpoint",    "start",        "1", "0", "", "4",  NULL, "0");
INSERT INTO ###TABLE_PREFIX###datagrid_columns (datagrid_id, column_id, column_identifier, column_name, visible, is_primary, description, order_nr, width, searchable) VALUES("8", "5", "endpoint",      "end",          "1", "0", "", "5",  NULL, "0");
INSERT INTO ###TABLE_PREFIX###datagrid_columns (datagrid_id, column_id, column_identifier, column_name, visible, is_primary, description, order_nr, width, searchable) VALUES("8", "6", "action",        "action",       "1", "0", "", "6",  NULL, "0");

INSERT INTO ###TABLE_PREFIX###datagrid_columns (datagrid_id, column_id, column_identifier, column_name, visible, is_primary, description, order_nr, width, searchable) VALUES("9", "1", "reference",     "type",         "1", "1", "", "1",  NULL, "0");
INSERT INTO ###TABLE_PREFIX###datagrid_columns (datagrid_id, column_id, column_identifier, column_name, visible, is_primary, description, order_nr, width, searchable) VALUES("9", "2", "group_name",    "group",        "1", "0", "", "2",  NULL, "0");
INSERT INTO ###TABLE_PREFIX###datagrid_columns (datagrid_id, column_id, column_identifier, column_name, visible, is_primary, description, order_nr, width, searchable) VALUES("9", "3", "user_name",     "user",         "1", "0", "", "3",  NULL, "0");
INSERT INTO ###TABLE_PREFIX###datagrid_columns (datagrid_id, column_id, column_identifier, column_name, visible, is_primary, description, order_nr, width, searchable) VALUES("9", "4", "grp",           "",             "0", "0", "", "4",  NULL, "0");
INSERT INTO ###TABLE_PREFIX###datagrid_columns (datagrid_id, column_id, column_identifier, column_name, visible, is_primary, description, order_nr, width, searchable) VALUES("9", "5", "user",          "",             "0", "0", "", "5",  NULL, "0");
INSERT INTO ###TABLE_PREFIX###datagrid_columns (datagrid_id, column_id, column_identifier, column_name, visible, is_primary, description, order_nr, width, searchable) VALUES("9", "6", "newstate",      "new_state",    "1", "0", "", "6",  NULL, "0");
INSERT INTO ###TABLE_PREFIX###datagrid_columns (datagrid_id, column_id, column_identifier, column_name, visible, is_primary, description, order_nr, width, searchable) VALUES("9", "7", "name",          "name",         "1", "0", "", "7",  NULL, "0");
INSERT INTO ###TABLE_PREFIX###datagrid_columns (datagrid_id, column_id, column_identifier, column_name, visible, is_primary, description, order_nr, width, searchable) VALUES("9", "8", "isdefault",     "default",      "1", "0", "", "8",  NULL, "0");
INSERT INTO ###TABLE_PREFIX###datagrid_columns (datagrid_id, column_id, column_identifier, column_name, visible, is_primary, description, order_nr, width, searchable) VALUES("9", "9", "action",        "",             "1", "0", "", "9",  NULL, "0");

INSERT INTO ###TABLE_PREFIX###datagrid_columns (datagrid_id, column_id, column_identifier, column_name, visible, is_primary, description, order_nr, width, searchable) VALUES("10", "1", "collection_id", "id",           "0", "1", "", "1",  NULL, "0");
INSERT INTO ###TABLE_PREFIX###datagrid_columns (datagrid_id, column_id, column_identifier, column_name, visible, is_primary, description, order_nr, width, searchable) VALUES("10", "2", "name",          "category",     "1", "0", "", "2",  NULL, "0");
INSERT INTO ###TABLE_PREFIX###datagrid_columns (datagrid_id, column_id, column_identifier, column_name, visible, is_primary, description, order_nr, width, searchable) VALUES("10", "3", "description",   "description",  "1", "0", "", "3",  NULL, "0");
INSERT INTO ###TABLE_PREFIX###datagrid_columns (datagrid_id, column_id, column_identifier, column_name, visible, is_primary, description, order_nr, width, searchable) VALUES("10", "4", "is_dir",        "is_dir",       "0", "0", "", "4",  NULL, "0");
INSERT INTO ###TABLE_PREFIX###datagrid_columns (datagrid_id, column_id, column_identifier, column_name, visible, is_primary, description, order_nr, width, searchable) VALUES("10", "5", "actions",       "",             "1", "0", "", "5",  NULL, "0");

INSERT INTO ###TABLE_PREFIX###datagrid_columns (datagrid_id, column_id, column_identifier, column_name, visible, is_primary, description, order_nr, width, searchable) VALUES("11", "1",  "ticket_id",    "id",           "1", "1", "", "10",  "30",  "1");
INSERT INTO ###TABLE_PREFIX###datagrid_columns (datagrid_id, column_id, column_identifier, column_name, visible, is_primary, description, order_nr, width, searchable) VALUES("11", "2",  "theme",        "theme",        "1", "0", "", "20",  "200", "0");
INSERT INTO ###TABLE_PREFIX###datagrid_columns (datagrid_id, column_id, column_identifier, column_name, visible, is_primary, description, order_nr, width, searchable) VALUES("11", "3",  "content",      "content",      "1", "0", "", "30",  "", "0");
INSERT INTO ###TABLE_PREFIX###datagrid_columns (datagrid_id, column_id, column_identifier, column_name, visible, is_primary, description, order_nr, width, searchable) VALUES("11", "4",  "creator",      "creator",      "0", "0", "", "40",  "130",  "0");
INSERT INTO ###TABLE_PREFIX###datagrid_columns (datagrid_id, column_id, column_identifier, column_name, visible, is_primary, description, order_nr, width, searchable) VALUES("11", "5",  "followup",    "followup",      "1", "0", "", "50",  "130",  "0");
INSERT INTO ###TABLE_PREFIX###datagrid_columns (datagrid_id, column_id, column_identifier, column_name, visible, is_primary, description, order_nr, width, searchable) VALUES("11", "6",  "owner",        "owner",        "1", "0", "", "60",  "120",  "0");
INSERT INTO ###TABLE_PREFIX###datagrid_columns (datagrid_id, column_id, column_identifier, column_name, visible, is_primary, description, order_nr, width, searchable) VALUES("11", "7",  "grp",          "grp",          "0", "0", "", "70",  "30",  "0");
INSERT INTO ###TABLE_PREFIX###datagrid_columns (datagrid_id, column_id, column_identifier, column_name, visible, is_primary, description, order_nr, width, searchable) VALUES("11", "8",  "created",      "created",      "0", "0", "", "80",  "30",  "0");
INSERT INTO ###TABLE_PREFIX###datagrid_columns (datagrid_id, column_id, column_identifier, column_name, visible, is_primary, description, order_nr, width, searchable) VALUES("11", "9",  "last_changer", "last_changer", "0", "0", "", "90",  "30",  "0");
INSERT INTO ###TABLE_PREFIX###datagrid_columns (datagrid_id, column_id, column_identifier, column_name, visible, is_primary, description, order_nr, width, searchable) VALUES("11", "10", "last_change",  "last_change",  "1", "0", "", "100", "130",  "0");
INSERT INTO ###TABLE_PREFIX###datagrid_columns (datagrid_id, column_id, column_identifier, column_name, visible, is_primary, description, order_nr, width, searchable) VALUES("11", "11", "access_level", "access",       "0", "0", "", "110", "30",  "0");
INSERT INTO ###TABLE_PREFIX###datagrid_columns (datagrid_id, column_id, column_identifier, column_name, visible, is_primary, description, order_nr, width, searchable) VALUES("11", "12", "is_dir",       "is_dir",       "0", "0", "", "120", "30",  "0");
INSERT INTO ###TABLE_PREFIX###datagrid_columns (datagrid_id, column_id, column_identifier, column_name, visible, is_primary, description, order_nr, width, searchable) VALUES("11", "13", "owner_id",     "owner_id",     "0", "0", "", "130", "30",  "0");
INSERT INTO ###TABLE_PREFIX###datagrid_columns (datagrid_id, column_id, column_identifier, column_name, visible, is_primary, description, order_nr, width, searchable) VALUES("11", "14", "group_id",     "group_id",     "0", "0", "", "140", "30",  "0");
INSERT INTO ###TABLE_PREFIX###datagrid_columns (datagrid_id, column_id, column_identifier, column_name, visible, is_primary, description, order_nr, width, searchable) VALUES("11", "15", "done",         "done",         "0", "0", "", "150", "30",  "0");
INSERT INTO ###TABLE_PREFIX###datagrid_columns (datagrid_id, column_id, column_identifier, column_name, visible, is_primary, description, order_nr, width, searchable) VALUES("11", "16", "color",        "color",        "0", "0", "", "160", "30",  "0");
INSERT INTO ###TABLE_PREFIX###datagrid_columns (datagrid_id, column_id, column_identifier, column_name, visible, is_primary, description, order_nr, width, searchable) VALUES("11", "17", "state",        "state",        "0", "0", "", "170", "30",  "0");
INSERT INTO ###TABLE_PREFIX###datagrid_columns (datagrid_id, column_id, column_identifier, column_name, visible, is_primary, description, order_nr, width, searchable) VALUES("11", "18", "checkboxes",   "",             "1", "0", "", "5",   "30",  "0");
INSERT INTO ###TABLE_PREFIX###datagrid_columns (datagrid_id, column_id, column_identifier, column_name, visible, is_primary, description, order_nr, width, searchable)           VALUES ("11", "19", "done_column",  "done",   "1", "0", "", "35",  "30",  "0");
INSERT INTO ###TABLE_PREFIX###datagrid_columns (datagrid_id, column_id, column_identifier, column_name, visible, is_primary, description, order_nr, width, searchable, sortable) VALUES ("11", "20", "show_actions", "action", "1", "0", "",  "180", "60",  "0", "0");  
