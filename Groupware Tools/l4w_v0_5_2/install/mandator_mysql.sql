# MySQL-Front Dump 2.5
#
# Host: localhost   Database: gacl
# --------------------------------------------------------
# Server version 4.0.21-debug

INSERT INTO ###TABLE_PREFIX###mandator (
	mandator_id, name, tree_root, group_root, description, acl_inc_php) 
VALUES (2, 'Demo', 1000,1000, 'Demo Mandator','demoMandator');

INSERT INTO ###TABLE_PREFIX###group_details (id, mandator_id, description) 
VALUES (1000, 2, 'Root for Demo Mandator');


INSERT INTO ###TABLE_PREFIX###user_mandator (user_id, mandator_id) VALUES (2,2);    

INSERT INTO ###TABLE_PREFIX###skins (mandator, name, img_path, css_path) VALUES (2,'eclipse', 'img/eclipse/','css/eclipse/');

#INSERT INTO ###TABLE_PREFIX###languages (language, set_local_str, aktiv, order_nr, filename) VALUES ('german', 'ger',1,1,'german');
#INSERT INTO ###TABLE_PREFIX###languages (language, set_local_str, aktiv, order_nr, filename) VALUES ('english','eng',1,2,'english');

INSERT INTO ###TABLE_PREFIX###tree VALUES("1001",  "1000", "Demo Mandator",   "", "", "", "", "-2", NULL, "0", "1", "0", "1", "1");
INSERT INTO ###TABLE_PREFIX###tree VALUES("1002",  "1001", "contacts",        "", "", "contacts.gif", "", "1", NULL, "1", "1", "0", "1", "1");
INSERT INTO ###TABLE_PREFIX###tree VALUES("1007",  "1002", "new contact",     "../../modules/contacts/index.php?command=add_contact_view", "l4w_main", "", '', "-1", NULL, "1", "1", "0", "1", "1");
INSERT INTO ###TABLE_PREFIX###tree VALUES("1008",  "1002", "browse",          "../../modules/contacts/index.php?command=show_entries", "l4w_main",     "", '', "0", NULL, "1", "1", "0", "1", "1");
INSERT INTO ###TABLE_PREFIX###tree VALUES("1003",  "1001", "options",         "", "", "admin.gif", "", "6", "", "1", "1", "0", "0", "1");
INSERT INTO ###TABLE_PREFIX###tree VALUES("1009",  "1003", "skins",           "", "", "", "", "1", "~~skins~~", "1", "1", "0", "0", "1");
INSERT INTO ###TABLE_PREFIX###tree VALUES("1010",  "1003", "languages",       "", "", "", "", "1", "~~languages~~", "1", "1", "0", "0", "1");
INSERT INTO ###TABLE_PREFIX###tree VALUES("1004",  "1001", "administration",  "", "", "admin.gif", "", "7", "~~rights~~", "1", "1", "0", "1", "1");
#INSERT INTO ###TABLE_PREFIX###tree VALUES("1011",  "1001", "statistic", "",   "", "stats.gif", "", "7", "~~stats~~", "1", "1", "0", "0", "1");
INSERT INTO ###TABLE_PREFIX###tree VALUES("1006",  "1001", "logout",          "../../logout.php", "_top", "logout.gif", "", "10", NULL, "1", "1", "0", "1", "1");

#INSERT INTO ###TABLE_PREFIX###tree (id, parent, name, link, frame, img, sign, order_nr, subtree_identifier, translate, enabled, authorize, protected) 
#VALUES (1013, 1001, 'news', '', 'l4w_main', 'news.gif', '', 0, NULL, '1', '1', '0', '0');

#INSERT INTO ###TABLE_PREFIX###tree (id, parent, name, link, frame, img, sign, order_nr, subtree_identifier, translate, enabled, authorize, protected) 
#VALUES (1014, 1013, 'current news', '../../modules/news/index.php?command=show_current_news', 'l4w_main', '', '', 0, NULL, '1', '1', '0', '0');

#INSERT INTO ###TABLE_PREFIX###tree (id, parent, name, link, frame, img, sign, order_nr, subtree_identifier, translate, enabled, authorize, protected) 
#VALUES (1015, 1013, 'all news', '../../modules/news/index.php?command=show_all_news', 'l4w_main', '', '', 0, NULL, '1', '1', '0', '0');

INSERT INTO ###TABLE_PREFIX###tree (id, parent, name, link, frame, img, sign, order_nr, subtree_identifier, translate, enabled, authorize, protected) 
VALUES (1016, 1001, 'documents', '', 'l4w_main', 'docs.gif', '', 3, NULL, '1', '1', '0', '0');

INSERT INTO ###TABLE_PREFIX###tree (id, parent, name, link, frame, img, sign, order_nr, subtree_identifier, translate, enabled, authorize, protected) 
VALUES (1017, 1016, 'new document', '../../modules/docs/index.php?command=add_doc_view&parent=0', 'l4w_main', '', '', 1, NULL, '1', '1', '0', '0');

INSERT INTO ###TABLE_PREFIX###tree (id, parent, name, link, frame, img, sign, order_nr, subtree_identifier, translate, enabled, authorize, protected) 
VALUES (1018, 1016, 'browse', '../../modules/docs/index.php?command=show_entries', 'l4w_main', '', '', 1, NULL, '1', '1', '0', '0');

INSERT INTO ###TABLE_PREFIX###tree (id, parent, name, link, frame, img, sign, order_nr, subtree_identifier, translate, enabled, authorize, protected) 
VALUES (1019, 1001, 'notes', '', 'l4w_main', 'notes.gif', '', 4, NULL, '1', '1', '0', '0');

### notes ###

INSERT INTO ###TABLE_PREFIX###tree (id, parent, name, link, frame, img, sign, order_nr, subtree_identifier, translate, enabled, authorize, protected) 
VALUES (1020, 1019, 'new note',   '../../modules/notes/index.php?command=add_entry_view', 'l4w_main',  '', '', 1, NULL, '1', '1', '0', '0');

INSERT INTO ###TABLE_PREFIX###tree (id, parent, name, link, frame, img, sign, order_nr, subtree_identifier, translate, enabled, authorize, protected) 
VALUES (1021, 1019, 'new folder', '../../modules/notes/index.php?command=add_folder_view', 'l4w_main', '', '', 2, NULL, '1', '1', '0', '0');

INSERT INTO ###TABLE_PREFIX###tree (id, parent, name, link, frame, img, sign, order_nr, subtree_identifier, translate, enabled, authorize, protected) 
VALUES (1022, 1019, 'browse',     '../../modules/notes/index.php?command=show_entries',    'l4w_main', '', '', 3, NULL, '1', '1', '0', '0');

INSERT INTO ###TABLE_PREFIX###tree (id, parent, name, link, frame, img, sign, order_nr, subtree_identifier, translate, enabled, authorize, protected) 
VALUES (1025, 1001, 'tickets', '', 'l4w_main', 'tickets.gif', '', 5, NULL, '1', '1', '0', '0');

INSERT INTO ###TABLE_PREFIX###tree (id, parent, name, link, frame, img, sign, order_nr, subtree_identifier, translate, enabled, authorize, protected) 
VALUES (1026, 1025, 'new ticket', '../../modules/tickets/index.php?command=add_ticket_view', 'l4w_main', '', '', 1, NULL, '1', '1', '0', '0');

INSERT INTO ###TABLE_PREFIX###tree (id, parent, name, link, frame, img, sign, order_nr, subtree_identifier, translate, enabled, authorize, protected) 
VALUES (1027, 1025, 'browse', '../../modules/tickets/index.php?command=show_entries', 'l4w_main', '', '', 2, NULL, '1', '1', '0', '0');

INSERT INTO ###TABLE_PREFIX###tree (id, parent, name, link, frame, img, sign, order_nr, subtree_identifier, translate, enabled, authorize, protected) 
VALUES (1028, 1001, 'todos', '', 'l4w_main', 'todos.gif', '', 5, NULL, '1', '1', '0', '0');

INSERT INTO ###TABLE_PREFIX###tree (id, parent, name, link, frame, img, sign, order_nr, subtree_identifier, translate, enabled, authorize, protected) 
VALUES (1029, 1028, 'new todo', '../../modules/todos/index.php?command=add_entry_view', 'l4w_main', '', '', 1, NULL, '1', '1', '0', '0');

INSERT INTO ###TABLE_PREFIX###tree (id, parent, name, link, frame, img, sign, order_nr, subtree_identifier, translate, enabled, authorize, protected) 
VALUES (1030, 1028, 'browse',   '../../modules/todos/index.php?command=show_entries', 'l4w_main', '', '', 2, NULL, '1', '1', '0', '0');

INSERT INTO ###TABLE_PREFIX###tree (id, parent, name, link, frame, img, sign, order_nr, subtree_identifier, translate, enabled, authorize, protected) 
VALUES (1031, 1001, 'email',   '', '', 'mail.gif', '', 2, '~~emails~~', '1', '1', '0', '0');

# states for contacts

INSERT INTO ###TABLE_PREFIX###states (mandator, reference, status, name, color, startpoint) VALUES (2, 'contact',-1, 'undefined',          '#000066', '0');
INSERT INTO ###TABLE_PREFIX###states (mandator, reference, status, name, color, startpoint) VALUES (2, 'contact', 0, 'new',                '#000066', '1');
INSERT INTO ###TABLE_PREFIX###states (mandator, reference, status, name, color, startpoint) VALUES (2, 'contact', 1, 'changed',            '#000066', '0');
INSERT INTO ###TABLE_PREFIX###states (mandator, reference, status, name, color, startpoint) VALUES (2, 'contact', 2, 'accepted',           '#000066', '0');
INSERT INTO ###TABLE_PREFIX###states (mandator, reference, status, name, color, startpoint) VALUES (2, 'contact', 3, 'not accepted',       '#000066', '0');
INSERT INTO ###TABLE_PREFIX###states (mandator, reference, status, name, color, startpoint) VALUES (2, 'contact', 4, 'to delete',          '#000066', '0');
INSERT INTO ###TABLE_PREFIX###states (mandator, reference, status, name, color, startpoint) VALUES (2, 'contact', 5, 'imported from l4w3', '#000066', '0');

# reference, grp, user, old, new, name(internal note), isdefault
#INSERT INTO ###TABLE_PREFIX###transitions VALUES ('contact', 0,  0, 0, 1, 'everyone can transit from new to changed',         '1');
#INSERT INTO ###TABLE_PREFIX###transitions VALUES ('contact', 14, 0, 0, 2, 'grp 14 users can transit from new to accepted',     '1');
#INSERT INTO ###TABLE_PREFIX###transitions VALUES ('contact', 14, 0, 0, 3, 'grp 14 users can transit from new to not accepted', '0');
#INSERT INTO ###TABLE_PREFIX###transitions VALUES ('contact', 14, 0, 0, 4, 'grp 14 users can transit from new to to delete',    '0');

#INSERT INTO ###TABLE_PREFIX###transitions VALUES ('contact', 0,  0, 1, 1, 'changed and changed again (for all)',              '1');
#INSERT INTO ###TABLE_PREFIX###transitions VALUES ('contact', 14, 0, 1, 2, 'grp 14 users can transit from new to accepted',     '1');
#INSERT INTO ###TABLE_PREFIX###transitions VALUES ('contact', 14, 0, 1, 3, 'grp 14 users can transit from new to not accepted', '0');
#INSERT INTO ###TABLE_PREFIX###transitions VALUES ('contact', 14, 0, 1, 4, 'grp 14 users can transit from new to to delete',    '0');
#
#INSERT INTO ###TABLE_PREFIX###transitions VALUES ('contact', 0,  0, 2, 2, 'leave as is for all',                              '1');
#INSERT INTO ###TABLE_PREFIX###transitions VALUES ('contact', 0,  0, 3, 3, 'leave as is for all',                              '1');
#INSERT INTO ###TABLE_PREFIX###transitions VALUES ('contact', 0,  0, 4, 4, 'leave as is for all',                              '1');
#INSERT INTO ###TABLE_PREFIX###transitions VALUES ('contact', 0,  0, 5, 5, 'leave as is for all',                              '1');

# states for tickets

INSERT INTO ###TABLE_PREFIX###states (mandator, reference, status, name, color, startpoint) VALUES (2, 'ticket',-1, 'undefined', '#000066', '0');
INSERT INTO ###TABLE_PREFIX###states (mandator, reference, status, name, color, startpoint) VALUES (2, 'ticket', 0, 'new',       '#000066', '1');
INSERT INTO ###TABLE_PREFIX###states (mandator, reference, status, name, color, startpoint) VALUES (2, 'ticket', 1, 'assigned',  '#000066', '0');
INSERT INTO ###TABLE_PREFIX###states (mandator, reference, status, name, color, startpoint) VALUES (2, 'ticket', 2, 'worked on', '#000066', '0');
INSERT INTO ###TABLE_PREFIX###states (mandator, reference, status, name, color, startpoint) VALUES (2, 'ticket', 3, 'resolved',  '#000066', '0');
INSERT INTO ###TABLE_PREFIX###states (mandator, reference, status, name, color, startpoint) VALUES (2, 'ticket', 4, 'resolution confirmed', '#000066', '0');
INSERT INTO ###TABLE_PREFIX###states (mandator, reference, status, name, color, startpoint) VALUES (2, 'ticket', 5, 'deferred',  '#000066', '0');
INSERT INTO ###TABLE_PREFIX###states (mandator, reference, status, name, color, startpoint) VALUES (2, 'ticket', 6, 'to delete', '#000066', '0');
INSERT INTO ###TABLE_PREFIX###states (mandator, reference, status, name, color, startpoint) VALUES (2, 'ticket', 7, 'reopened',  '#000066', '0');


# reference, grp, user, old, new, name(internal note), isdefault
#INSERT INTO ###TABLE_PREFIX###transitions VALUES ('ticket', 0,  0, 0, 1, 'everyone can transit from new to assigned',         '1');
#INSERT INTO ###TABLE_PREFIX###transitions VALUES ('ticket', 14, 0, 0, 2, 'grp 14 users can transit from new to worked on',    '1');
#INSERT INTO ###TABLE_PREFIX###transitions VALUES ('ticket', 14, 0, 0, 5, 'grp 14 users can transit from new to to delete',    '0');

#INSERT INTO ###TABLE_PREFIX###transitions VALUES ('ticket', 0,  0, 1, 1, 'still assigned',                                    '1');
#INSERT INTO ###TABLE_PREFIX###transitions VALUES ('ticket', 0,  0, 1, 2, 'everyone can transit from assigned to worked on',   '1');
#INSERT INTO ###TABLE_PREFIX###transitions VALUES ('ticket', 0,  0, 1, 3, '',                                                  '0');
#INSERT INTO ###TABLE_PREFIX###transitions VALUES ('ticket', 14, 0, 1, 4, '',                                                  '0');
#INSERT INTO ###TABLE_PREFIX###transitions VALUES ('ticket', 14, 0, 1, 5, '',                                                  '0');

#INSERT INTO ###TABLE_PREFIX###transitions VALUES ('ticket', 0,  0, 2, 2, 'leave as is for all',                               '1');
#INSERT INTO ###TABLE_PREFIX###transitions VALUES ('ticket', 0,  0, 2, 3, '',                                                  '1');
#INSERT INTO ###TABLE_PREFIX###transitions VALUES ('ticket', 14, 0, 2, 4, '',                                                  '1');
#INSERT INTO ###TABLE_PREFIX###transitions VALUES ('ticket', 14, 0, 2, 3, '',                                                  '1');

#INSERT INTO ###TABLE_PREFIX###transitions VALUES ('ticket', 0,  0, 3, 3, 'leave as is for all',                              '1');
#INSERT INTO ###TABLE_PREFIX###transitions VALUES ('ticket', 0,  0, 3, 4, '',                                                 '1');

#INSERT INTO ###TABLE_PREFIX###transitions VALUES ('ticket', 0,  0, 4, 4, 'leave as is for all',                              '1');

#INSERT INTO ###TABLE_PREFIX###transitions VALUES ('ticket', 0,  0, 5, 5, 'leave as is for all',                              '1');

#INSERT INTO ###TABLE_PREFIX###transitions VALUES ('ticket', 0,  0, 6, 6, 'leave as is for all',                              '1');

#INSERT INTO ###TABLE_PREFIX###transitions VALUES ('ticket', 0,  0, 7, 7, 'leave as is for all',                              '1');


INSERT INTO ###TABLE_PREFIX###states (mandator, reference, status, name, color, startpoint) VALUES (2, 'todo',-1, 'undefined',              '#c0c0c0','0');
INSERT INTO ###TABLE_PREFIX###states (mandator, reference, status, name, color, startpoint) VALUES (2, 'todo', 0, 'new',                    '#FF8000','1');
INSERT INTO ###TABLE_PREFIX###states (mandator, reference, status, name, color, startpoint) VALUES (2, 'todo', 1, 'assigned',               '#000066','0');
INSERT INTO ###TABLE_PREFIX###states (mandator, reference, status, name, color, startpoint) VALUES (2, 'todo', 2, 'worked on',              '#004000','0');
INSERT INTO ###TABLE_PREFIX###states (mandator, reference, status, name, color, startpoint) VALUES (2, 'todo', 3, 'resolved',               '#00FF00','0');
INSERT INTO ###TABLE_PREFIX###states (mandator, reference, status, name, color, startpoint) VALUES (2, 'todo', 4, 'resolution confirmed',   '#00FFFF','0');
INSERT INTO ###TABLE_PREFIX###states (mandator, reference, status, name, color, startpoint) VALUES (2, 'todo', 5, 'deferred',               '#8000FF','0');
INSERT INTO ###TABLE_PREFIX###states (mandator, reference, status, name, color, startpoint) VALUES (2, 'todo', 6, 'to delete',              '#FFFF80','0');
INSERT INTO ###TABLE_PREFIX###states (mandator, reference, status, name, color, startpoint) VALUES (2, 'todo', 7, 'reopened',               '#FF0000','0');


# reference, grp, user, old, new, name(internal note), isdefault
#INSERT INTO ###TABLE_PREFIX###transitions VALUES ('todo', 0,  0, 0, 1, 'everyone can transit from new to assigned',         '1');
#INSERT INTO ###TABLE_PREFIX###transitions VALUES ('todo', 14, 0, 0, 2, 'grp 14 users can transit from new to worked on',    '1');
#INSERT INTO ###TABLE_PREFIX###transitions VALUES ('todo', 14, 0, 0, 5, 'grp 14 users can transit from new to to delete',    '0');

#INSERT INTO ###TABLE_PREFIX###transitions VALUES ('todo', 0,  0, 1, 1, 'still assigned',                                    '1');
#INSERT INTO ###TABLE_PREFIX###transitions VALUES ('todo', 0,  0, 1, 2, 'everyone can transit from assigned to worked on',   '1');
#INSERT INTO ###TABLE_PREFIX###transitions VALUES ('todo', 0,  0, 1, 3, '',                                                  '0');
#INSERT INTO ###TABLE_PREFIX###transitions VALUES ('todo', 14, 0, 1, 4, '',                                                  '0');
#INSERT INTO ###TABLE_PREFIX###transitions VALUES ('todo', 14, 0, 1, 5, '',                                                  '0');
#
#INSERT INTO ###TABLE_PREFIX###transitions VALUES ('todo', 0,  0, 2, 2, 'leave as is for all',                               '1');
#INSERT INTO ###TABLE_PREFIX###transitions VALUES ('todo', 0,  0, 2, 3, '',                                                  '1');
#INSERT INTO ###TABLE_PREFIX###transitions VALUES ('todo', 14, 0, 2, 4, '',                                                  '1');
#INSERT INTO ###TABLE_PREFIX###transitions VALUES ('todo', 14, 0, 2, 3, '',                                                  '1');

#INSERT INTO ###TABLE_PREFIX###transitions VALUES ('todo', 0,  0, 3, 3, 'leave as is for all',                              '1');
#INSERT INTO ###TABLE_PREFIX###transitions VALUES ('todo', 0,  0, 3, 4, '',                                                 '1');

#INSERT INTO ###TABLE_PREFIX###transitions VALUES ('todo', 0,  0, 4, 4, 'leave as is for all',                              '1');

#INSERT INTO ###TABLE_PREFIX###transitions VALUES ('todo', 0,  0, 5, 5, 'leave as is for all',                              '1');

#INSERT INTO ###TABLE_PREFIX###transitions VALUES ('todo', 0,  0, 6, 6, 'leave as is for all',                              '1');

#INSERT INTO ###TABLE_PREFIX###transitions VALUES ('todo', 0,  0, 7, 7, 'leave as is for all',                              '1');

INSERT INTO ###TABLE_PREFIX###access_options VALUES (1, 2, '-rwx------', 'private',      'access_private.gif');
INSERT INTO ###TABLE_PREFIX###access_options VALUES (2, 2, '-rwxr-----', 'groupread',    'access_grpread.gif');
INSERT INTO ###TABLE_PREFIX###access_options VALUES (3, 2, '-rwxrw----', 'groupwrite',   'access_grpwrite.gif');
INSERT INTO ###TABLE_PREFIX###access_options VALUES (4, 2, '-rwxrwx---', 'groupdelete',  'access_grpdel.gif');
INSERT INTO ###TABLE_PREFIX###access_options VALUES (5, 2, '-rwxrwxr--', 'publicread',   'access_pubread.gif');
INSERT INTO ###TABLE_PREFIX###access_options VALUES (6, 2, '-rwxrwxrw-', 'publicwrite',  'access_pubwrite.gif');
INSERT INTO ###TABLE_PREFIX###access_options VALUES (7, 2, '-rwxrwxrwx', 'publicdelete', 'access_pubdel.gif');


INSERT INTO ###TABLE_PREFIX###priorities VALUES (1, 2, 'high',     'high',   '1', 1, '#ff0000');
INSERT INTO ###TABLE_PREFIX###priorities VALUES (2, 2, 'medium',   'medium', '1', 2, '#000066');
INSERT INTO ###TABLE_PREFIX###priorities VALUES (3, 2, 'low',      'low',    '1', 3, '#000000');
         
               