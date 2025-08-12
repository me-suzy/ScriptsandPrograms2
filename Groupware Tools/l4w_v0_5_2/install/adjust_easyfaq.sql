# MySQL-Front Dump 2.5
#
# Host: localhost   Database: gacl
# --------------------------------------------------------
# Server version 4.0.21-debug

delete from ###TABLE_PREFIX###tree where 1=1~|~

drop table ###TABLE_PREFIX###tree~|~

CREATE TABLE ###TABLE_PREFIX###tree (
  id int(11)        NOT NULL auto_increment,
  parent int(11)    NOT NULL default '0',
  name varchar(50)  NOT NULL default '',
  link varchar(200) NOT NULL default '',
  frame varchar(50) NOT NULL default '',
  img varchar(100)  NOT NULL default '',
  sign varchar(10)  NOT NULL default '',
  order_nr int(11)  NOT NULL default '0',
  subtree_identifier varchar(20) default NULL,
  translate char(1) NOT NULL default '0',
  enabled char(1)   NOT NULL default '1',
  authorize char(1) NOT NULL default '0',
  protected char(1) NOT NULL default '0',
  visible_for_guest  char(1) NOT NULL default '1',
  UNIQUE KEY id (id)
) TYPE=MyISAM~|~


INSERT INTO ###TABLE_PREFIX###tree VALUES("1",  "0", "easyFAQ",     "", "", "", "", "-2", NULL, "0", "1", "0", "1", "1")~|~

#INSERT INTO ###TABLE_PREFIX###tree VALUES("2",  "1", "faqs",            "", "", "faqs.gif", "", "1", NULL, "1", "1", "0", "1", "1")~|~
INSERT INTO ###TABLE_PREFIX###tree VALUES("7",  "1", "new question",    "../../modules/faqs/index.php?command=add_entry_view",    "l4w_main", "", '', "-1", NULL, "1", "1", "0", "1", "1")~|~

INSERT INTO ###TABLE_PREFIX###tree VALUES("8",  "1", "browse",          "../../modules/faqs/index.php?command=show_entries", "l4w_main",     "", '', "1", NULL, "1", "1", "0", "1", "0")~|~
INSERT INTO ###TABLE_PREFIX###tree VALUES("11", "1", "search",          "../../modules/faqs/index.php?command=search_view",  "l4w_main",     "", '', "2", NULL, "1", "1", "0", "1", "1")~|~
INSERT INTO ###TABLE_PREFIX###tree VALUES("12", "1", "list",            "../../modules/faqs/index.php?command=list",         "l4w_main",     "", '', "3", NULL, "1", "1", "0", "1", "1")~|~

INSERT INTO ###TABLE_PREFIX###tree VALUES("3",  "1", "options",         "", "", "admin.gif", "", "6", "", "1", "1", "0", "0", "0")~|~
INSERT INTO ###TABLE_PREFIX###tree VALUES("9",  "3", "skins",           "", "", "", "", "1", "~~skins~~", "1", "1", "0", "0", "0")~|~
INSERT INTO ###TABLE_PREFIX###tree VALUES("10", "3", "languages",       "", "", "", "", "1", "~~languages~~", "1", "1", "0", "0", "0")~|~
INSERT INTO ###TABLE_PREFIX###tree VALUES("4",  "1", "administration",  "", "", "admin.gif", "", "7", "~~rights~~", "1", "1", "0", "1", "0")~|~
INSERT INTO ###TABLE_PREFIX###tree VALUES("6",  "1", "logout",          "../../logout.php", "_top", "logout.gif", "", "10", NULL, "1", "1", "0", "1", "0")~|~


delete from ###TABLE_PREFIX###components where 1=1~|~

drop table ###TABLE_PREFIX###components~|~

CREATE TABLE ###TABLE_PREFIX###components (
  id             int(11)      NOT NULL auto_increment,
  module_name    varchar(100) NOT NULL default '',
  version        varchar(10)  NOT NULL default '0.0.1',
  index_url      varchar(100) NOT NULL default 'http://',
  new_window     varchar(1)   NOT NULL default '0',
  enabled        varchar(1)   NOT NULL default '1',
  dependencies   varchar(20)  NOT NULL default '',
  UNIQUE KEY id (id)
) TYPE=MyISAM~|~

INSERT INTO ###TABLE_PREFIX###components VALUES (100, 'contacts',     '0.4', '',  '0', '0', '')~|~
INSERT INTO ###TABLE_PREFIX###components VALUES (200, 'access_level', '0.4', '',  '0', '1', '')~|~
INSERT INTO ###TABLE_PREFIX###components VALUES (250, 'jabber',       '0.4', '',  '0', '1', '')~|~
INSERT INTO ###TABLE_PREFIX###components VALUES (270, 'collections',  '0.4', '',  '0', '0', '')~|~
INSERT INTO ###TABLE_PREFIX###components VALUES (280, 'sync',         '0.4', '',  '0', '0', '')~|~
INSERT INTO ###TABLE_PREFIX###components VALUES (300, 'workflow',     '0.5', '',  '0', '1', '')~|~
INSERT INTO ###TABLE_PREFIX###components VALUES (400, 'companies',    '0.5', '',  '0', '0', '')~|~
INSERT INTO ###TABLE_PREFIX###components VALUES (500, 'email',        '0.5', '',  '0', '0', '')~|~

UPDATE ###TABLE_PREFIX###gacl_aro_groups SET name='unreleased' WHERE id=13~|~

DELETE FROM ###TABLE_PREFIX###gacl_groups_aro_map WHERE 1=1~|~

INSERT INTO ###TABLE_PREFIX###gacl_groups_aro_map VALUES("13", "10")~|~
INSERT INTO ###TABLE_PREFIX###gacl_groups_aro_map VALUES("15", "12")~|~
INSERT INTO ###TABLE_PREFIX###gacl_groups_aro_map VALUES("16", "10")~|~

UPDATE ###TABLE_PREFIX###group_details SET description='unreleased faqs' WHERE id=5~|~

DELETE FROM ###TABLE_PREFIX###access_options WHERE 1=1~|~

INSERT INTO ###TABLE_PREFIX###access_options VALUES (2, '-rwxr-----', 'groupread',    'access_grpread.gif')~|~

DELETE FROM ###TABLE_PREFIX###application WHERE 1=1~|~

INSERT INTO ###TABLE_PREFIX###application (id, name, version, ts) VALUES (1, 'easyfaq', '###VERSION###', now())~|~




DELETE FROM ###TABLE_PREFIX###events WHERE 1=1~|~

INSERT INTO ###TABLE_PREFIX###events VALUES (40, 'faq',          'new faq',          'faq was added',               0, NULL, 'system', 'new_entry', 'dummy subject', 0);
INSERT INTO ###TABLE_PREFIX###events VALUES (41, 'faq',          'changed faq',      'faq was changed',             0, NULL, 'system', '', 'dummy subject', 0);
INSERT INTO ###TABLE_PREFIX###events VALUES (42, 'faq',          'deleted faq',      'faq was deleted',             0, NULL, 'system', '', 'dummy subject', 0);
INSERT INTO ###TABLE_PREFIX###events VALUES (43, 'faq',          'new folder',       'folder for faqs was added',   0, NULL, 'system', '', 'dummy subject', 0);
INSERT INTO ###TABLE_PREFIX###events VALUES (44, 'faq',          'changed folder',   'folder for faqs was changed', 0, NULL, 'system', '', 'dummy subject', 0);
INSERT INTO ###TABLE_PREFIX###events VALUES (45, 'faq',          'deleted folder',   'folder for faqs was deleted', 0, NULL, 'system', '', 'dummy subject', 0);




















INSERT INTO ###TABLE_PREFIX###memos VALUES("1", "0", "0", "What is easyFAQ?", "EasyFAQ is an advanced application to manage Frequently Asked questions.<br />", "0000-00-00", "2005-04-18", "2005-04-05", "0", "0", "2")~|~
INSERT INTO ###TABLE_PREFIX###memos VALUES("2", "0", "0", "Why is it advanced?", "<p>EasyFAQ offers a lot of possibilities and functionalities:</p><ul>  <li>Multi-User management</li>  <li>Event management</li>  <li>Different Skins</li>  <li>Logfile and Debugging</li>  <li>The possibility to adopt and extend easyFAQ&nbsp;</li></ul>", "0000-00-00", "2005-04-18", "2005-04-05", "0", "0", "2")~|~
INSERT INTO ###TABLE_PREFIX###memos VALUES("3", "1", "0", "Developers", "Questions about the development of easyFAQ", NULL, NULL, NULL, "0", "0", "1")~|~
INSERT INTO ###TABLE_PREFIX###memos VALUES("4", "0", "0", "unreleased question", "&lt;p&gt;this &amp;quot;question&amp;quot; is not visible to the public, as it has been assigned\r\nto the group &amp;quot;unreleased&amp;quot;. If you want to publish it, simply change the\r\ngroup to &amp;quot;public&amp;quot;.&lt;/p&gt;&lt;p&gt;When\r\nlogged in as superadmin, however, you have access to this question, as\r\nthe superuser belongs to both groups &amp;quot;unreleased&amp;quot; and &amp;quot;public&amp;quot;.&amp;nbsp;&lt;/p&gt;", "0000-00-00", "2005-04-21", "2005-04-08", "0", "0", "2")~|~
INSERT INTO ###TABLE_PREFIX###memos VALUES("5", "0", "0", "Why do I need Multi-User Management anyway?", "As a guest (that is using the public view) you won&#039;t notice any\r\nmulti-user functionality, but when you log in as superuser, you have\r\naccess to a lot more items. Especially, there are two groups (&amp;quot;public&amp;quot;\r\nand &amp;quot;unreleased&amp;quot;) to organize your FAQs. In the backend, you can add\r\nnew users (and even groups) to help you editing and publishing new\r\nFAQs. On the other hand, &amp;quot;guest&amp;quot; users can ask questions, which will be\r\nadded to the &amp;quot;unreleased&amp;quot; group.&lt;br /&gt;", "0000-00-00", "2005-04-21", "2005-04-08", "0", "0", "2")~|~
INSERT INTO ###TABLE_PREFIX###memos VALUES("6", "0", "0", "And Event-Management? What does that mean,. then?", "easyFAQ offers you the possibility to register to events (provided you\r\nare logged in in the backend). Registering means that you can define an\r\naction (sending an email, for example)&amp;nbsp; which is triggered when\r\nsomething happens (someone added a new FAQ, for example).&lt;br /&gt;\r\n", "0000-00-00", "2005-04-21", "2005-04-08", "0", "0", "2")~|~


#
# Dumping data for table 'ef_metainfo'
#

INSERT INTO ###TABLE_PREFIX###metainfo VALUES("faq", "1", "2", "2", "16", "0", 1, "2005-04-04 18:54:04", NULL, NULL, "-rwxr-----")~|~
INSERT INTO ###TABLE_PREFIX###metainfo VALUES("faq", "2", "2", "2", "16", "0", 2, "2005-04-04 18:58:03", NULL, NULL, "-rwxr-----")~|~
INSERT INTO ###TABLE_PREFIX###metainfo VALUES("faq", "3", "2", "2", "16", "0", 1, "2005-04-04 19:10:47", NULL, NULL, "-rwxr-----")~|~
INSERT INTO ###TABLE_PREFIX###metainfo VALUES("faq", "4", "2", "2", "13", "-1",3, "2005-04-07 08:56:56", "2", "2005-04-07 08:58:42", "-rwxr-----")~|~
INSERT INTO ###TABLE_PREFIX###metainfo VALUES("faq", "5", "2", "2", "16", "-1",4, "2005-04-07 09:10:27", "2", "2005-04-07 09:11:15", "-rwxr-----")~|~
INSERT INTO ###TABLE_PREFIX###metainfo VALUES("faq", "6", "2", "2", "16", "0", 5, "2005-04-07 09:14:15", NULL, NULL, "-rwxr-----")~|~


