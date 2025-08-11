# 15Feb03 - PAC
# ADAPTED FROM PSL UPGRADE SCRIPT ========================
# --------------------------------------------------------

# ========================================================
# PART ONE
# UPGRADE OF EXISTING BACK-END 0.5.2 DATABASE
# ========================================================


ALTER TABLE psl_story ADD story_options TEXT;
ALTER TABLE psl_section ADD section_options TEXT;
ALTER TABLE psl_author ADD author_options TEXT;
ALTER TABLE psl_block CHANGE `title` `title` VARCHAR(255) NOT NULL;
ALTER TABLE psl_story ADD order_no int(10) unsigned NOT NULL default '0';

INSERT INTO db_sequence VALUES ('psl_permission_seq',82);
INSERT INTO db_sequence VALUES ('psl_group_seq',27);
INSERT INTO db_sequence VALUES ('psl_group_section_lut_seq',66);
INSERT INTO db_sequence VALUES ('psl_group_group_lut_seq',64);
INSERT INTO db_sequence VALUES ('psl_author_group_lut_seq',26);

CREATE TABLE psl_author_group_lut (
  lut_id int(11) unsigned NOT NULL default '0',
  author_id int(11) unsigned default NULL,
  group_id int(11) unsigned default NULL,
  UNIQUE KEY lut_id_2 (lut_id),
  KEY lut_id (lut_id)
) TYPE=MyISAM;

#
# Dumping data for table 'psl_author_group_lut'
#

INSERT INTO psl_author_group_lut VALUES (26,1,24);


#
# Table structure for table 'psl_group'
#

CREATE TABLE psl_group (
  group_id int(10) unsigned NOT NULL default '0',
  group_name varchar(60) NOT NULL default '',
  group_description text,
  PRIMARY KEY  (group_id),
  UNIQUE KEY group_name (group_name)
) TYPE=MyISAM;

#
# Dumping data for table 'psl_group'
#

INSERT INTO psl_group VALUES (1,'author','administer site authors');
INSERT INTO psl_group VALUES (4,'block','Block Admin');
INSERT INTO psl_group VALUES (8,'comment','Comment Admin');
INSERT INTO psl_group VALUES (9,'glossary','Glossary Admin');
INSERT INTO psl_group VALUES (10,'groupAdmin','Group Admin');
INSERT INTO psl_group VALUES (11,'logging','Infolog Admin');
INSERT INTO psl_group VALUES (12,'mailinglist','Mailing List Admin');
INSERT INTO psl_group VALUES (13,'permissionAdmin','Permission Admin');
INSERT INTO psl_group VALUES (14,'poll','Poll Admin');
INSERT INTO psl_group VALUES (15,'section','Section Admin');
INSERT INTO psl_group VALUES (16,'story','Story Admin');
INSERT INTO psl_group VALUES (17,'submission','Submission Admin');
INSERT INTO psl_group VALUES (18,'topic','Topic Admin');
INSERT INTO psl_group VALUES (19,'variable','Variable Admin');
INSERT INTO psl_group VALUES (20,'nobody','Anon user (not effective)');
INSERT INTO psl_group VALUES (21,'user','logged in user privileges.  General users.');
INSERT INTO psl_group VALUES (22,'commentUser','User comment privileges');
INSERT INTO psl_group VALUES (23,'storyeditor','Special extend story editor privileges');
INSERT INTO psl_group VALUES (24,'root','All privileges');


#
# Table structure for table 'psl_group_group_lut'
#

CREATE TABLE psl_group_group_lut (
  lut_id int(11) unsigned NOT NULL default '0',
  group_id int(11) unsigned default NULL,
  childgroup_id int(11) unsigned default NULL,
  UNIQUE KEY lut_id_2 (lut_id),
  KEY lut_id (lut_id)
) TYPE=MyISAM;

#
# Dumping data for table 'psl_group_group_lut'
#

INSERT INTO psl_group_group_lut VALUES (64,24,19);
INSERT INTO psl_group_group_lut VALUES (63,24,21);
INSERT INTO psl_group_group_lut VALUES (62,24,18);
INSERT INTO psl_group_group_lut VALUES (61,24,17);
INSERT INTO psl_group_group_lut VALUES (60,24,23);
INSERT INTO psl_group_group_lut VALUES (59,24,16);
INSERT INTO psl_group_group_lut VALUES (58,24,25);
INSERT INTO psl_group_group_lut VALUES (57,24,15);
INSERT INTO psl_group_group_lut VALUES (56,24,14);
INSERT INTO psl_group_group_lut VALUES (55,24,13);
INSERT INTO psl_group_group_lut VALUES (54,24,20);
INSERT INTO psl_group_group_lut VALUES (53,24,12);
INSERT INTO psl_group_group_lut VALUES (52,24,11);
INSERT INTO psl_group_group_lut VALUES (51,24,10);
INSERT INTO psl_group_group_lut VALUES (50,24,9);
INSERT INTO psl_group_group_lut VALUES (49,24,22);
INSERT INTO psl_group_group_lut VALUES (48,24,8);
INSERT INTO psl_group_group_lut VALUES (47,24,4);
INSERT INTO psl_group_group_lut VALUES (46,24,1);

#
# Table structure for table 'psl_group_permission_lut'
#

CREATE TABLE psl_group_permission_lut (
  lut_id int(11) unsigned NOT NULL default '0',
  group_id int(11) unsigned default NULL,
  permission_id int(11) unsigned default NULL,
  UNIQUE KEY lut_id_2 (lut_id),
  KEY lut_id (lut_id)
) TYPE=MyISAM;

#
# Dumping data for table 'psl_group_permission_lut'
#

INSERT INTO psl_group_permission_lut VALUES (10,1,50);
INSERT INTO psl_group_permission_lut VALUES (9,1,47);
INSERT INTO psl_group_permission_lut VALUES (8,1,49);
INSERT INTO psl_group_permission_lut VALUES (7,1,48);
INSERT INTO psl_group_permission_lut VALUES (6,1,45);
INSERT INTO psl_group_permission_lut VALUES (11,1,46);
INSERT INTO psl_group_permission_lut VALUES (20,4,35);
INSERT INTO psl_group_permission_lut VALUES (21,4,38);
INSERT INTO psl_group_permission_lut VALUES (22,4,39);
INSERT INTO psl_group_permission_lut VALUES (23,4,37);
INSERT INTO psl_group_permission_lut VALUES (24,4,36);
INSERT INTO psl_group_permission_lut VALUES (25,8,7);
INSERT INTO psl_group_permission_lut VALUES (26,8,9);
INSERT INTO psl_group_permission_lut VALUES (27,8,4);
INSERT INTO psl_group_permission_lut VALUES (28,8,6);
INSERT INTO psl_group_permission_lut VALUES (29,8,3);
INSERT INTO psl_group_permission_lut VALUES (30,8,8);
INSERT INTO psl_group_permission_lut VALUES (31,8,5);
INSERT INTO psl_group_permission_lut VALUES (32,9,56);
INSERT INTO psl_group_permission_lut VALUES (33,9,59);
INSERT INTO psl_group_permission_lut VALUES (34,9,60);
INSERT INTO psl_group_permission_lut VALUES (35,9,58);
INSERT INTO psl_group_permission_lut VALUES (36,9,57);
INSERT INTO psl_group_permission_lut VALUES (37,10,20);
INSERT INTO psl_group_permission_lut VALUES (38,10,23);
INSERT INTO psl_group_permission_lut VALUES (39,10,24);
INSERT INTO psl_group_permission_lut VALUES (40,10,22);
INSERT INTO psl_group_permission_lut VALUES (41,10,21);
INSERT INTO psl_group_permission_lut VALUES (42,11,66);
INSERT INTO psl_group_permission_lut VALUES (43,11,69);
INSERT INTO psl_group_permission_lut VALUES (44,11,70);
INSERT INTO psl_group_permission_lut VALUES (45,11,68);
INSERT INTO psl_group_permission_lut VALUES (46,11,67);
INSERT INTO psl_group_permission_lut VALUES (47,12,61);
INSERT INTO psl_group_permission_lut VALUES (48,12,64);
INSERT INTO psl_group_permission_lut VALUES (49,12,65);
INSERT INTO psl_group_permission_lut VALUES (50,12,63);
INSERT INTO psl_group_permission_lut VALUES (51,12,62);
INSERT INTO psl_group_permission_lut VALUES (52,13,25);
INSERT INTO psl_group_permission_lut VALUES (53,13,28);
INSERT INTO psl_group_permission_lut VALUES (54,13,29);
INSERT INTO psl_group_permission_lut VALUES (55,13,27);
INSERT INTO psl_group_permission_lut VALUES (56,13,26);
INSERT INTO psl_group_permission_lut VALUES (57,14,40);
INSERT INTO psl_group_permission_lut VALUES (58,14,43);
INSERT INTO psl_group_permission_lut VALUES (59,14,44);
INSERT INTO psl_group_permission_lut VALUES (60,14,42);
INSERT INTO psl_group_permission_lut VALUES (61,14,41);
INSERT INTO psl_group_permission_lut VALUES (62,15,30);
INSERT INTO psl_group_permission_lut VALUES (63,15,33);
INSERT INTO psl_group_permission_lut VALUES (64,15,34);
INSERT INTO psl_group_permission_lut VALUES (65,15,32);
INSERT INTO psl_group_permission_lut VALUES (66,15,31);
INSERT INTO psl_group_permission_lut VALUES (67,16,78);
INSERT INTO psl_group_permission_lut VALUES (68,16,77);
INSERT INTO psl_group_permission_lut VALUES (69,16,71);
INSERT INTO psl_group_permission_lut VALUES (70,16,79);
INSERT INTO psl_group_permission_lut VALUES (71,16,74);
INSERT INTO psl_group_permission_lut VALUES (72,16,76);
INSERT INTO psl_group_permission_lut VALUES (73,16,75);
INSERT INTO psl_group_permission_lut VALUES (74,16,80);
INSERT INTO psl_group_permission_lut VALUES (75,16,73);
INSERT INTO psl_group_permission_lut VALUES (76,16,72);
INSERT INTO psl_group_permission_lut VALUES (77,17,10);
INSERT INTO psl_group_permission_lut VALUES (78,17,13);
INSERT INTO psl_group_permission_lut VALUES (79,17,14);
INSERT INTO psl_group_permission_lut VALUES (80,17,12);
INSERT INTO psl_group_permission_lut VALUES (81,17,11);
INSERT INTO psl_group_permission_lut VALUES (82,18,15);
INSERT INTO psl_group_permission_lut VALUES (83,18,18);
INSERT INTO psl_group_permission_lut VALUES (84,18,19);
INSERT INTO psl_group_permission_lut VALUES (85,18,17);
INSERT INTO psl_group_permission_lut VALUES (86,18,16);
INSERT INTO psl_group_permission_lut VALUES (87,19,51);
INSERT INTO psl_group_permission_lut VALUES (88,19,54);
INSERT INTO psl_group_permission_lut VALUES (89,19,55);
INSERT INTO psl_group_permission_lut VALUES (90,19,53);
INSERT INTO psl_group_permission_lut VALUES (91,19,52);
INSERT INTO psl_group_permission_lut VALUES (92,20,4);
INSERT INTO psl_group_permission_lut VALUES (93,20,6);
INSERT INTO psl_group_permission_lut VALUES (94,20,3);
INSERT INTO psl_group_permission_lut VALUES (95,20,5);
INSERT INTO psl_group_permission_lut VALUES (96,21,4);
INSERT INTO psl_group_permission_lut VALUES (97,21,6);
INSERT INTO psl_group_permission_lut VALUES (98,21,3);
INSERT INTO psl_group_permission_lut VALUES (99,21,5);
INSERT INTO psl_group_permission_lut VALUES (100,22,4);
INSERT INTO psl_group_permission_lut VALUES (101,22,6);
INSERT INTO psl_group_permission_lut VALUES (102,22,3);
INSERT INTO psl_group_permission_lut VALUES (103,22,5);
INSERT INTO psl_group_permission_lut VALUES (104,23,78);
INSERT INTO psl_group_permission_lut VALUES (105,23,77);
INSERT INTO psl_group_permission_lut VALUES (106,23,79);
INSERT INTO psl_group_permission_lut VALUES (107,23,76);
INSERT INTO psl_group_permission_lut VALUES (108,23,80);
INSERT INTO psl_group_permission_lut VALUES (110,20,81);
INSERT INTO psl_group_permission_lut VALUES (111,8,82);
INSERT INTO psl_group_permission_lut VALUES (112,24,50);
#
# Table structure for table 'psl_group_section_lut'
#

CREATE TABLE psl_group_section_lut (
  lut_id int(11) unsigned NOT NULL default '0',
  group_id int(11) unsigned default NULL,
  section_id int(11) unsigned default NULL,
  UNIQUE KEY lut_id_2 (lut_id),
  KEY lut_id (lut_id)
) TYPE=MyISAM;

#
# Dumping data for table 'psl_group_section_lut'
#

INSERT INTO psl_group_section_lut VALUES (17,1,0);
INSERT INTO psl_group_section_lut VALUES (19,3,0);
INSERT INTO psl_group_section_lut VALUES (40,4,0);
INSERT INTO psl_group_section_lut VALUES (41,5,0);
INSERT INTO psl_group_section_lut VALUES (42,6,0);
INSERT INTO psl_group_section_lut VALUES (43,7,0);
INSERT INTO psl_group_section_lut VALUES (44,8,0);
INSERT INTO psl_group_section_lut VALUES (45,9,0);
INSERT INTO psl_group_section_lut VALUES (46,10,0);
INSERT INTO psl_group_section_lut VALUES (47,11,0);
INSERT INTO psl_group_section_lut VALUES (48,12,0);
INSERT INTO psl_group_section_lut VALUES (49,13,0);
INSERT INTO psl_group_section_lut VALUES (50,14,0);
INSERT INTO psl_group_section_lut VALUES (51,15,0);
INSERT INTO psl_group_section_lut VALUES (52,16,0);
INSERT INTO psl_group_section_lut VALUES (53,17,0);
INSERT INTO psl_group_section_lut VALUES (54,18,0);
INSERT INTO psl_group_section_lut VALUES (55,19,0);
INSERT INTO psl_group_section_lut VALUES (56,20,0);
INSERT INTO psl_group_section_lut VALUES (57,21,0);
INSERT INTO psl_group_section_lut VALUES (58,22,0);
INSERT INTO psl_group_section_lut VALUES (59,23,0);
INSERT INTO psl_group_section_lut VALUES (66,24,0);
INSERT INTO psl_group_section_lut VALUES (61,25,0);
INSERT INTO psl_group_section_lut VALUES (62,26,0);

#
# Table structure for table 'psl_permission'
#

CREATE TABLE psl_permission (
  permission_id int(10) unsigned NOT NULL default '0',
  permission_name varchar(60) NOT NULL default '',
  permission_description text,
  PRIMARY KEY  (permission_id),
  UNIQUE KEY permission_name (permission_name)
) TYPE=MyISAM;

#
# Dumping data for table 'psl_permission'
#

INSERT INTO psl_permission VALUES (3,'commentShow','can see comments');
INSERT INTO psl_permission VALUES (4,'commentPost','can post comments');
INSERT INTO psl_permission VALUES (5,'commentView','can preview? comments?');
INSERT INTO psl_permission VALUES (6,'commentSave','can edit comments?');
INSERT INTO psl_permission VALUES (7,'commentDelete','can delete comments');
INSERT INTO psl_permission VALUES (8,'commentUpdate','can moderate comments');
INSERT INTO psl_permission VALUES (9,'commentEdit','can edit comments');
INSERT INTO psl_permission VALUES (10,'submissionDelete','can delete submissions');
INSERT INTO psl_permission VALUES (11,'submissionSave','can save submissions');
INSERT INTO psl_permission VALUES (12,'submissionNew','can submit a story');
INSERT INTO psl_permission VALUES (13,'submissionEdit','can edit submissions');
INSERT INTO psl_permission VALUES (14,'submissioneditasstory','can post submissions as stories');
INSERT INTO psl_permission VALUES (15,'topicDelete','can delete topics');
INSERT INTO psl_permission VALUES (16,'topicSave','can save topics');
INSERT INTO psl_permission VALUES (17,'topicNew','can create new topics');
INSERT INTO psl_permission VALUES (18,'topicEdit','can update topics');
INSERT INTO psl_permission VALUES (19,'topicList','get lists of topics');
INSERT INTO psl_permission VALUES (20,'groupDelete','can delete groups');
INSERT INTO psl_permission VALUES (21,'groupSave','can save groups');
INSERT INTO psl_permission VALUES (22,'groupNew','can create groups');
INSERT INTO psl_permission VALUES (23,'groupEdit','can edit groups');
INSERT INTO psl_permission VALUES (24,'groupList','can list groups');
INSERT INTO psl_permission VALUES (25,'permissionDelete','can delete permissions');
INSERT INTO psl_permission VALUES (26,'permissionSave','can save permissions');
INSERT INTO psl_permission VALUES (27,'permissionNew','can create permissions');
INSERT INTO psl_permission VALUES (28,'permissionEdit','can edit permissions');
INSERT INTO psl_permission VALUES (29,'permissionList','can list permissions');
INSERT INTO psl_permission VALUES (30,'sectionDelete','delete sections');
INSERT INTO psl_permission VALUES (31,'sectionSave','save sections');
INSERT INTO psl_permission VALUES (32,'sectionNew','create sections');
INSERT INTO psl_permission VALUES (33,'sectionEdit','update sections');
INSERT INTO psl_permission VALUES (34,'sectionList','list sections');
INSERT INTO psl_permission VALUES (35,'blockDelete','delete blocks');
INSERT INTO psl_permission VALUES (36,'blockPut','save blocks');
INSERT INTO psl_permission VALUES (37,'blockNew','create blocks');
INSERT INTO psl_permission VALUES (38,'blockEdit','update a block');
INSERT INTO psl_permission VALUES (39,'blockList','list all blocks');
INSERT INTO psl_permission VALUES (40,'pollDelete','delete polls');
INSERT INTO psl_permission VALUES (41,'pollPut','save a poll');
INSERT INTO psl_permission VALUES (42,'pollNew','create a poll');
INSERT INTO psl_permission VALUES (43,'pollEdit','edit polls');
INSERT INTO psl_permission VALUES (44,'pollList','list all polls');
INSERT INTO psl_permission VALUES (45,'authorDelete','delete a user');
INSERT INTO psl_permission VALUES (46,'authorSave','save user info');
INSERT INTO psl_permission VALUES (47,'authorNew','create a user');
INSERT INTO psl_permission VALUES (48,'authorEdit','update user info');
INSERT INTO psl_permission VALUES (49,'authorList','list all authors');
INSERT INTO psl_permission VALUES (50,'authorprofileSave','update your own info');
INSERT INTO psl_permission VALUES (51,'variableDelete','delete a Variable');
INSERT INTO psl_permission VALUES (52,'variableSave','save a variable');
INSERT INTO psl_permission VALUES (53,'variableNew','create a variable');
INSERT INTO psl_permission VALUES (54,'variableEdit','edit a variable');
INSERT INTO psl_permission VALUES (55,'variableList','list all variables');
INSERT INTO psl_permission VALUES (56,'glossaryDelete','delete a glossary entry');
INSERT INTO psl_permission VALUES (57,'glossarySave','save a glossary entry');
INSERT INTO psl_permission VALUES (58,'glossaryNew','create a glossary entry');
INSERT INTO psl_permission VALUES (59,'glossaryEdit','update a glossary entry');
INSERT INTO psl_permission VALUES (60,'glossaryList','list all glossary entries');
INSERT INTO psl_permission VALUES (61,'mailinglistDelete','delete a list member');
INSERT INTO psl_permission VALUES (62,'mailinglistSave','save list member address');
INSERT INTO psl_permission VALUES (63,'mailinglistNew','new member form');
INSERT INTO psl_permission VALUES (64,'mailinglistEdit','update form');
INSERT INTO psl_permission VALUES (65,'mailinglistList','list all members');
INSERT INTO psl_permission VALUES (66,'infologDelete','delete a log entry');
INSERT INTO psl_permission VALUES (67,'infologSave','save a log entry');
INSERT INTO psl_permission VALUES (68,'infologNew','create a log entry');
INSERT INTO psl_permission VALUES (69,'infologEdit','change a log entry?');
INSERT INTO psl_permission VALUES (70,'infologList','display the infolog');
INSERT INTO psl_permission VALUES (71,'storyDelete','delete a story');
INSERT INTO psl_permission VALUES (72,'storySave','save a story');
INSERT INTO psl_permission VALUES (73,'storyNew','new story form');
INSERT INTO psl_permission VALUES (74,'storyEdit','edit story form');
INSERT INTO psl_permission VALUES (75,'storyList','list stories');
INSERT INTO psl_permission VALUES (76,'storyeditothers','edit other authors stories');
INSERT INTO psl_permission VALUES (77,'storychangedate','can change date of stories');
INSERT INTO psl_permission VALUES (78,'storychangeauthor','can change the author of the story');
INSERT INTO psl_permission VALUES (79,'storydeleteothers','can delete other author&#039;s stories');
INSERT INTO psl_permission VALUES (80,'storylistothers','story list contains other author&#039;s stories');
INSERT INTO psl_permission VALUES (81,'commentChangeName','can Change comment na
me and url');
INSERT INTO psl_permission VALUES( '82', 'commentViewIP', 'Can view full IP/Host of person posting a comment');

# PAC: ALREADY IN BACK-END ITERATION 1

# These are the alterations to the database wherein dates are kept as
# unix timestamps (seconds since the epoch) with the goal of making
# PHPSlash more cross-RDBMS.  There is also a naming convention in
# place, using "date_x", where "x" is one of the Dublin Core refinements
# of the date element. (MPL - 11/18/2002)

##############################
# updates to table psl_block #
##############################
#CREATE TABLE psl_block_new (
#  id int(11) unsigned NOT NULL default '0',
#  type int(11) NOT NULL default '0',
#  title varchar(255) NOT NULL default '',
#  expire_length int(11) NOT NULL default '0',
#  last_update timestamp(14) NOT NULL,
#  location varchar(254) NOT NULL default '',
#  source_url varchar(254) NOT NULL default '',
#  cache_data text NOT NULL,
#  block_options text,
#  ordernum int(10) unsigned NOT NULL default '0',
#  PRIMARY KEY  (id)
#) TYPE=MyISAM;
#ALTER TABLE `psl_block_new` ADD `date_issued` INT DEFAULT NULL;
#INSERT INTO psl_block_new 
#SELECT *,UNIX_TIMESTAMP(last_update)
#FROM psl_block;
#
#DROP TABLE psl_block;
#ALTER TABLE `psl_block_new` RENAME `psl_block`;
#
#ALTER TABLE `psl_block` DROP `last_update`;

################################
# updates to table psl_comment #
################################
CREATE TABLE psl_comment_new (
  comment_id int(11) NOT NULL default '0',
  parent_id int(11) NOT NULL default '0',
  story_id int(11) NOT NULL default '0',
  user_id int(15) NOT NULL default '0',
  date datetime default NULL,
  name varchar(50) NOT NULL default '',
  email varchar(50) default NULL,
  ip varchar(50) default NULL,
  subject varchar(50) NOT NULL default '',
  comment_text text NOT NULL,
  pending tinyint(3) unsigned NOT NULL default '0',
  PRIMARY KEY  (story_id,comment_id)
) TYPE=MyISAM;
ALTER TABLE `psl_comment_new` ADD `date_created` INT DEFAULT NULL;
INSERT INTO psl_comment_new 
SELECT *,UNIX_TIMESTAMP(date)
FROM psl_comment;

DROP TABLE psl_comment;
ALTER TABLE `psl_comment_new` RENAME `psl_comment`;

ALTER TABLE `psl_comment` DROP `date`;

################################
# updates to table psl_infolog #
################################
CREATE TABLE psl_infolog_new (
  id smallint(6) NOT NULL default '0',
  time datetime default NULL,
  description varchar(50) default NULL,
  data varchar(255) default NULL,
  PRIMARY KEY  (id)
) TYPE=MyISAM;
ALTER TABLE `psl_infolog_new` ADD `date_created` INT DEFAULT NULL;
INSERT INTO psl_infolog_new 
SELECT *,UNIX_TIMESTAMP(time)
FROM psl_infolog;

DROP TABLE psl_infolog;
ALTER TABLE `psl_infolog_new` RENAME `psl_infolog`;

ALTER TABLE `psl_infolog` DROP `time`;

####################################
# updates to table psl_mailinglist #
####################################
CREATE TABLE psl_mailinglist_new (
  id int(10) unsigned NOT NULL default '0',
  email varchar(100) NOT NULL default '',
  name varchar(100) default NULL,
  timestamp timestamp(14) NOT NULL,
  PRIMARY KEY  (id),
  UNIQUE KEY id_2 (id),
  KEY id (id,email)
) TYPE=MyISAM;
ALTER TABLE `psl_mailinglist_new` ADD `date_created` INT DEFAULT NULL;
INSERT INTO psl_mailinglist_new 
SELECT *,UNIX_TIMESTAMP(timestamp)
FROM psl_mailinglist;

DROP TABLE psl_mailinglist;
ALTER TABLE `psl_mailinglist_new` RENAME `psl_mailinglist`;

ALTER TABLE `psl_mailinglist` DROP `timestamp`;

######################################
# updates to table psl_poll_question #
######################################
CREATE TABLE psl_poll_question_new (
  question_id int(10) unsigned NOT NULL default '0',
  question_text varchar(255) NOT NULL default '',
  question_total_votes int(11) default NULL,
  date datetime default NULL,
  current tinyint(4) NOT NULL default '0',
  PRIMARY KEY  (question_id)
) TYPE=MyISAM;
ALTER TABLE `psl_poll_question_new` ADD `date_created` INT DEFAULT NULL;
INSERT INTO psl_poll_question_new 
SELECT *,UNIX_TIMESTAMP(date)
FROM psl_poll_question;

DROP TABLE psl_poll_question;
ALTER TABLE `psl_poll_question_new` RENAME `psl_poll_question`;

ALTER TABLE `psl_poll_question` DROP `date`;

###################################
# updates to table psl_poll_voter #
###################################
CREATE TABLE psl_poll_voter_new (
  question_id int(10) unsigned NOT NULL default '0',
  voter_id varchar(30) default NULL,
  time datetime default NULL,
  user_id int(11) NOT NULL default '0'
) TYPE=MyISAM;
ALTER TABLE `psl_poll_voter_new` ADD `date_created` INT DEFAULT NULL;
INSERT INTO psl_poll_voter_new 
SELECT *,UNIX_TIMESTAMP(time)
FROM psl_poll_voter;

DROP TABLE psl_poll_voter;
ALTER TABLE `psl_poll_voter_new` RENAME `psl_poll_voter`;

ALTER TABLE `psl_poll_voter` DROP `time`;

##############################
# updates to table psl_story #
##############################
CREATE TABLE psl_story_new (
  story_id int(11) unsigned NOT NULL default '0',
  user_id int(11) unsigned NOT NULL default '0',
  title varchar(80) default NULL,
  dept varchar(80) default NULL,
  time datetime default NULL,
  intro_text text NOT NULL,
  body_text text,
  hits int(11) unsigned default NULL,
  topic_cache text,
  story_options text,
  order_no int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (story_id)
) TYPE=MyISAM;
ALTER TABLE `psl_story_new` ADD `date_available` INT DEFAULT NULL;
INSERT INTO psl_story_new 
SELECT *,UNIX_TIMESTAMP(time)
FROM psl_story;

DROP TABLE psl_story;
ALTER TABLE `psl_story_new` RENAME `psl_story`;

ALTER TABLE `psl_story` DROP `time`;

###################################
# updates to table psl_submission #
###################################
CREATE TABLE psl_submission_new (
  story_id int(11) unsigned NOT NULL default '0',
  user_id int(11) unsigned NOT NULL default '0',
  title varchar(80) default NULL,
  dept varchar(80) default NULL,
  time datetime default NULL,
  intro_text text NOT NULL,
  body_text text,
  hits int(11) unsigned default NULL,
  email varchar(50) default NULL,
  name varchar(50) NOT NULL default '',
  topic_cache text,
  PRIMARY KEY  (story_id)
) TYPE=MyISAM;
ALTER TABLE `psl_submission_new` ADD `date_created` INT DEFAULT NULL;
INSERT INTO psl_submission_new 
SELECT *,UNIX_TIMESTAMP(time)
FROM psl_submission;

DROP TABLE psl_submission;
ALTER TABLE `psl_submission_new` RENAME `psl_submission`;

ALTER TABLE `psl_submission` DROP `time`;


# PAC: ALREADY IN BACK-END ITERATION 1

# --------------------------------------------------------
#
# Table structure for table 'CACHEDATA'
#
#
#CREATE TABLE CACHEDATA (
#   CACHEKEY varchar(255) NOT NULL,
#   CACHEEXPIRATION int(11) NOT NULL,
#   GZDATA blob,
#   DATASIZE int(11),
#   DATACRC int(11),
#   PRIMARY KEY (CACHEKEY)
# ) TYPE=MyISAM; 



# 16Feb03 - PAC
# NEW PSL 0.7 BLOCK TYPES ================================
# --------------------------------------------------------

INSERT INTO psl_block_type (id, name) VALUES (11, 'login');
INSERT INTO psl_block_type (id, name) VALUES (12, 'navbar');
INSERT INTO psl_block_type (id, name) VALUES (13, 'submission');


# --------------------------------------------------------
# BACK-END SPECIFIC CHANGES
# --------------------------------------------------------


# 19Feb03 - PAC
# RENAME LOCALS TO SUBSITES ==============================
# --------------------------------------------------------

ALTER TABLE `be_locals` RENAME `be_subsites`;
ALTER TABLE `be_subsites` CHANGE `localID` `subsite_id` SMALLINT(5)  UNSIGNED DEFAULT "0" NOT NULL;
ALTER TABLE `be_subsites` CHANGE `localTypeID` `subsite_type_id` SMALLINT(5)  UNSIGNED DEFAULT "0" NOT NULL;

ALTER TABLE `be_localtypes` RENAME `be_subsite_types`;
ALTER TABLE `be_subsite_types` CHANGE `localTypeID` `subsite_type_id` SMALLINT(5)  UNSIGNED DEFAULT "0" NOT NULL;

ALTER TABLE `be_local2block` RENAME `be_subsite_block_lut`;
ALTER TABLE `be_subsite_block_lut` CHANGE `localID` `subsite_id` SMALLINT(5)  UNSIGNED DEFAULT "0" NOT NULL;

#DROP TABLE IF EXISTS `be_local2section`;

# 20Feb03 - PAC
# ADD SUBSITES RIGHTS ====================================
# --------------------------------------------------------


#DROP TABLE IF EXISTS `be_localrights`;
ALTER TABLE `psl_author_group_lut` ADD `subsite_id` INT( 11 ) ;


# 22Feb03 - PAC
# BACK-END PERMISSIONS AND GROUPS ========================
# --------------------------------------------------------

#
# Dumping data for table `psl_group`
#

INSERT INTO psl_group (group_id, group_name, group_description) VALUES 
(28, 'ContentProvider', 'Can submit and edit stories in specified subsite'),
(29, 'subsite', 'Back-End subsite administration'),
(30, 'upload', 'Back-End uploads'),
(31, 'gallery', 'Back-End gallery'),
(32, 'ContentManager', 'Subsite administration including sections, stories, uploads');

#
# Dumping data for table `psl_group_group_lut`
#

INSERT INTO psl_group_group_lut (lut_id, group_id, childgroup_id) VALUES 
(65, 28, 16),
(66, 28, 21),
(67, 32, 15),
(68, 32, 16),
(69, 32, 23),
(70, 32, 17),
(71, 32, 30),
(72, 32, 21),
(92, 24, 29),
(93, 24, 30),
(94, 24, 31),
(95, 24, 21),
(96, 24, 19);

#
# Dumping data for table `psl_group_permission_lut`
#

INSERT INTO psl_group_permission_lut (lut_id, group_id, permission_id) 
VALUES 
(113, 30, 83),
(114, 31, 85),
(115, 29, 84);

#
# Dumping data for table `psl_permission`
#

INSERT INTO psl_permission (permission_id, permission_name, permission_description) VALUES 
(83, 'upload', 'Can upload files for current subsite'),
(84, 'subsite', 'Can administer subsites'),
(85, 'gallery', 'Gallery administration');

UPDATE db_sequence SET nextid=32 WHERE seq_name='psl_group_seq';
UPDATE db_sequence SET nextid=96 WHERE seq_name='psl_group_group_lut_seq';
UPDATE db_sequence SET nextid=115 WHERE seq_name='psl_group_permission_lut_seq';
UPDATE db_sequence SET nextid=85 WHERE seq_name='psl_permission_seq';

# RUK: Already in iteration 2.

# RUK: Mar 6, 2003
# Table structure for table `be_linkTextValidation`
#

CREATE TABLE be_linkTextValidation (
  linkTextID smallint(5) NOT NULL default '0',
  validationState enum ("VALID", "MALFORMED_URL", "UNABLE_TO_CONNECT", "INVALID_PROTOCOL", "INVALID", "UNKNOWN") default "UNKNOWN",
  dateValid INT(10) UNSIGNED NOT NULL default '0',
  dateChecked INT(10) UNSIGNED NOT NULL default '0',
  PRIMARY KEY (linkTextID)
) TYPE=MyISAM;
# --------------------------------------------------------

# 9Mar03 - pac
# Renumber groups and permissions to prevent clashes with phpSlash developments

UPDATE psl_group SET group_id=200 WHERE group_id=28;
UPDATE psl_group SET group_id=201 WHERE group_id=29;
UPDATE psl_group SET group_id=202 WHERE group_id=30;
UPDATE psl_group SET group_id=203 WHERE group_id=31;
UPDATE psl_group SET group_id=204 WHERE group_id=32;
UPDATE psl_group SET group_id=205 WHERE group_id=33; # Template

INSERT INTO psl_group_group_lut (lut_id, group_id, childgroup_id) VALUES 
(200, 200, 16),
(201, 200, 21),
(202, 204, 15),
(203, 204, 16),
(204, 204, 23),
(205, 204, 17),
(206, 204, 202),
(207, 204, 21),
(208, 24, 200),
(209, 24, 202),
(210, 24, 203),
(211, 24, 21),
(212, 24, 19);

DELETE FROM psl_group_group_lut WHERE lut_id IN (65, 66, 67, 68, 69, 70, 71, 72, 92,93, 94, 95, 96);


UPDATE psl_group_permission_lut SET group_id=201, permission_id=201 WHERE lut_id=115;
UPDATE psl_group_permission_lut SET group_id=202, permission_id=200 WHERE lut_id=113;
UPDATE psl_group_permission_lut SET group_id=203, permission_id=201 WHERE lut_id=114;

UPDATE psl_permission SET permission_id=200 WHERE permission_id=83;
UPDATE psl_permission SET permission_id=201 WHERE permission_id=84;
UPDATE psl_permission SET permission_id=202 WHERE permission_id=85;

UPDATE db_sequence SET nextid=202 WHERE seq_name='psl_permission_seq';
UPDATE db_sequence SET nextid=204 WHERE seq_name='psl_group_seq';
UPDATE db_sequence SET nextid=212 WHERE seq_name='psl_group_group_lut_seq';
UPDATE db_sequence SET nextid=202 WHERE seq_name='psl_group_permission_lut_seq';


# Mar12 03 - ian @ CUPE - Add and initialize singleton LDAP UID counter
CREATE TABLE UidNumber (Uid INTEGER);
INSERT INTO UidNumber VALUES ("2000");

# ===========================================================================================================
# PART TWO
# DEVELOPMENT EXTENSIONS TO DATABASE
# THESE HAVE TO BE RUN EVEN ON A NEW DATABASE CREATED BY
# RUNNING slash_core.sql, BE_core.sql, extra_modules.sql, BE_exampleDatea.sql
# ===========================================================================================================


# 27Feb03
# Add editTemplate permission 
#

INSERT INTO psl_permission (permission_id, permission_name, permission_description) VALUES (203, 'template', 'Can edit templates'); #87-203
INSERT INTO psl_group (group_id, group_name, group_description) VALUES (205,  'Template', 'Group of permissions that allow users to amend templates online'); #33-205
INSERT INTO psl_group_permission_lut (lut_id, group_id, permission_id) VALUES (203, 205, 203); #118-203
INSERT INTO psl_group_group_lut (lut_id, group_id, childgroup_id) VALUES (213, 204, 205), (214, 24, 205); #158-213, 159-214

UPDATE db_sequence SET nextid=203 WHERE seq_name='psl_permission_seq';
UPDATE db_sequence SET nextid=205 WHERE seq_name='psl_group_seq';
UPDATE db_sequence SET nextid=203 WHERE seq_name='psl_group_permission_lut_seq';
UPDATE db_sequence SET nextid=214 WHERE seq_name='psl_group_group_lut_seq';


# 28Feb03
# Adding Wiki text editing by storing source 
#

ALTER TABLE `be_articleText` 
   ADD `content_source` TEXT NOT NULL AFTER content,
   ADD `blerb_source` TEXT NOT NULL AFTER content,
   ADD `title_source` VARCHAR(255) NOT NULL AFTER content;
   
ALTER TABLE `be_sectionText` 
   ADD `content_source` TEXT NOT NULL AFTER content,
   ADD `blerb_source` TEXT NOT NULL AFTER content,
   ADD `title_source` VARCHAR(255) NOT NULL AFTER content;

ALTER TABLE `be_linkText` 
   ADD `description_source` TEXT NOT NULL AFTER description,
   ADD `title_source` VARCHAR(255) NOT NULL AFTER description;

UPDATE be_articleText 
   SET title_source   = title, 
       blerb_source   = blerb, 
       content_source = content;
UPDATE be_sectionText 
   SET title_source   = title, 
       blerb_source   = blerb, 
       content_source = content;
UPDATE be_linkText 
   SET title_source   = title,
       description_source=description;

ALTER TABLE `be_articles` 
        ADD `content_type` VARCHAR(8)  NOT NULL AFTER dateRemoved;
ALTER TABLE `be_sections` 
        ADD `content_type` VARCHAR(8)  NOT NULL AFTER dateRemoved;
ALTER TABLE `be_link` 
        ADD `content_type` VARCHAR(8)  NOT NULL AFTER dateRemoved;

UPDATE be_articles SET content_type='html';
UPDATE be_sections SET content_type='html';
UPDATE be_link SET content_type='html';

# March 2,
# Adding indexing for What's Popular
# It's also probably overkill..
#

ALTER TABLE be_articles ADD INDEX(articleID,hide,restrict2members,dateAvailable,dateRemoved,hitCounter);
ALTER TABLE be_sections ADD INDEX(sectionID,hide,restrict2members,dateAvailable,dateRemoved,hitCounter);
ALTER TABLE be_articles ADD INDEX(hide,restrict2members,dateAvailable,dateRemoved,hitCounter);
ALTER TABLE be_sections ADD INDEX(hide,restrict2members,dateAvailable,dateRemoved,hitCounter);
ALTER TABLE be_articles ADD INDEX(hide,restrict2members,dateAvailable,dateRemoved);
ALTER TABLE be_sections ADD INDEX(hide,restrict2members,dateAvailable,dateRemoved);


# 14Mar03:ruk
# Add show link submission flag to sections table records.
#
ALTER TABLE `be_sections`
   ADD `showLinkSubmit` tinyint(2) NOT NULL DEFAULT '1' AFTER `showArticles`;
 
# 16Mar03:ruk
# Add link group and permissions
#
INSERT INTO psl_group (group_id, group_name, group_description) VALUES (25,'linkAdmin','Link Admin');
INSERT INTO psl_group_group_lut (lut_id, group_id, childgroup_id) VALUES (65,24,25);
INSERT INTO psl_permission (permission_id, permission_name, permission_description) VALUES (83,'linkNew','new link');
INSERT INTO psl_permission (permission_id, permission_name, permission_description) VALUES (84,'linkEdit','edit link');
INSERT INTO psl_permission (permission_id, permission_name, permission_description) VALUES (85,'linkSave','save link');
INSERT INTO psl_permission (permission_id, permission_name, permission_description) VALUES (86,'linkList','list link');
optimize table be_articles, be_articleText, be_sections, be_sectionText;


# 7Apr03:mg 
# Conflict with these
# INSERT INTO psl_group_permission_lut (lut_id, group_id, permission_id) VALUES (113,25,83);
# INSERT INTO psl_group_permission_lut (lut_id, group_id, permission_id) VALUES (114,25,84);
# INSERT INTO psl_group_permission_lut (lut_id, group_id, permission_id) VALUES (115,25,85);
# INSERT INTO psl_group_permission_lut (lut_id, group_id, permission_id) VALUES (116,25,86); 

# Changed to these
INSERT INTO psl_group_permission_lut (lut_id, group_id, permission_id) VALUES (116,25,83);
INSERT INTO psl_group_permission_lut (lut_id, group_id, permission_id) VALUES (117,25,84);
INSERT INTO psl_group_permission_lut (lut_id, group_id, permission_id) VALUES (118,25,85);
INSERT INTO psl_group_permission_lut (lut_id, group_id, permission_id) VALUES (119,25,86); 

#Updated lut
INSERT INTO db_sequence VALUES ('psl_group_permission_lut_seq',120);


# Filling in the missing piecs


#
# Table structure for table `auth_user`
#

CREATE TABLE auth_user (
  user_id varchar(32) NOT NULL default '',
  username varchar(32) NOT NULL default '',
  password varchar(32) NOT NULL default '',
  perms varchar(255) default NULL,
  PRIMARY KEY  (user_id),
  UNIQUE KEY k_username (username)
) TYPE=MyISAM;

#
# Dumping data for table `auth_user`
#


# --------------------------------------------------------

#
# Table structure for table `auth_user_md5`
#

CREATE TABLE auth_user_md5 (
  user_id varchar(32) NOT NULL default '',
  username varchar(32) NOT NULL default '',
  password varchar(32) NOT NULL default '',
  perms varchar(255) default NULL,
  PRIMARY KEY  (user_id),
  UNIQUE KEY k_username (username)
) TYPE=MyISAM;

#
# Dumping data for table `auth_user_md5`
#

DROP TABLE be_author2localrights;


INSERT INTO be_sectionText VALUES (12, 9, 'en', 'Admin', '', '', 'Admin', '', '', '', '', NULL, '', 0, 4);
INSERT INTO be_sections VALUES (9, 'Admin', 0, 1049346000, 1049402623, 1049346000, 0, 'exttrans', 1, 0, 1, 1, 1, 0, 2, 0, '', 3);
UPDATE db_sequence SET nextid=12 WHERE seq_name='be_sections';

# ALTER TABLE `be_subsite_block_lut` DROP INDEX `subsite`;
# ALTER TABLE `be_subsite_block_lut` ADD KEY subsite_id (subsite_id);

INSERT INTO psl_author_group_lut VALUES (27, 20, 20, NULL);
INSERT INTO psl_author_group_lut VALUES (28, 21, 1, NULL);
INSERT INTO psl_author_group_lut VALUES (29, 21, 4, NULL);
INSERT INTO psl_author_group_lut VALUES (30, 21, 8, NULL);
INSERT INTO psl_author_group_lut VALUES (31, 21, 22, NULL);
INSERT INTO psl_author_group_lut VALUES (32, 21, 204, NULL);
INSERT INTO psl_author_group_lut VALUES (33, 21, 200, NULL);
INSERT INTO psl_author_group_lut VALUES (34, 21, 203, NULL);
INSERT INTO psl_author_group_lut VALUES (35, 21, 9, NULL);
INSERT INTO psl_author_group_lut VALUES (36, 21, 10, NULL);
INSERT INTO psl_author_group_lut VALUES (37, 21, 11, NULL);
INSERT INTO psl_author_group_lut VALUES (38, 21, 12, NULL);
INSERT INTO psl_author_group_lut VALUES (39, 21, 20, NULL);
INSERT INTO psl_author_group_lut VALUES (40, 21, 14, NULL);
INSERT INTO psl_author_group_lut VALUES (41, 21, 27, NULL);
INSERT INTO psl_author_group_lut VALUES (42, 21, 24, NULL);
INSERT INTO psl_author_group_lut VALUES (43, 21, 15, NULL);
INSERT INTO psl_author_group_lut VALUES (44, 21, 25, NULL);
INSERT INTO psl_author_group_lut VALUES (45, 21, 16, NULL);
INSERT INTO psl_author_group_lut VALUES (46, 21, 23, NULL);
INSERT INTO psl_author_group_lut VALUES (47, 21, 17, NULL);
INSERT INTO psl_author_group_lut VALUES (48, 21, 201, NULL);
INSERT INTO psl_author_group_lut VALUES (49, 21, 205, NULL);
INSERT INTO psl_author_group_lut VALUES (50, 21, 18, NULL);
INSERT INTO psl_author_group_lut VALUES (51, 21, 202, NULL);
INSERT INTO psl_author_group_lut VALUES (52, 21, 21, NULL);
INSERT INTO psl_author_group_lut VALUES (53, 21, 19, NULL);

DELETE FROM psl_group WHERE group_id = '20';
DELETE FROM psl_group WHERE group_id = '25';
DELETE FROM psl_group WHERE group_id = '27';
 
INSERT INTO psl_group VALUES (27, 'PublicUserAccount', 'logged in Public User Account');
INSERT INTO psl_group VALUES (20, 'nobody', 'Anon user');
INSERT INTO psl_group VALUES (25, 'siteeditor', 'Site Editor');



DELETE FROM psl_group_permission_lut WHERE lut_id = '19';
INSERT INTO psl_group_permission_lut VALUES (19, 2, 49);
DELETE FROM psl_group_permission_lut WHERE lut_id = '109';
INSERT INTO psl_group_permission_lut VALUES (109, 20, 12);
DELETE FROM psl_group_permission_lut WHERE lut_id = '112';
INSERT INTO psl_group_permission_lut VALUES (112, 29, 84);

INSERT INTO psl_group_group_lut VALUES (5, 2, 1);
INSERT INTO psl_group_group_lut VALUES (6, 27, 20);
INSERT INTO psl_group_group_lut VALUES (7, 27, 21);

DELETE FROM psl_group_section_lut WHERE lut_id = '17';
INSERT INTO psl_group_section_lut VALUES (17, 1, 4);
INSERT INTO psl_group_section_lut VALUES (16, 1, 3);
INSERT INTO psl_group_section_lut VALUES (15, 1, 5);
INSERT INTO psl_group_section_lut VALUES (14, 1, 8);
INSERT INTO psl_group_section_lut VALUES (39, 2, 4);
INSERT INTO psl_group_section_lut VALUES (38, 2, 3);
INSERT INTO psl_group_section_lut VALUES (37, 2, 5);
INSERT INTO psl_group_section_lut VALUES (63, 27, 0);

DELETE FROM psl_permission where permission_name = 'linkList';
INSERT INTO psl_permission VALUES (204, 'linkList', 'Allows you to edit links');


# Table structure for table `psl_variable`
#
CREATE TABLE psl_variable (
  variable_id int(10) unsigned NOT NULL default '0',
  variable_name varchar(32) NOT NULL default '',
  value varchar(127) default NULL,
  description varchar(127) default NULL,
  variable_group varchar(20) default NULL,
  UNIQUE KEY variable_name (variable_name),
  KEY variable_id (variable_id)
) TYPE=MyISAM;

INSERT INTO psl_block VALUES (200, 12, 'Administration', 0, '', 'menu_ary=menuadmin&tpl=navbarBlockh', '<!-- START: navbarBlock.tpl -->\n       &nbsp<a href="/profile.php"><b><font size="-1" face="Verdana,Arial,Helvetica,San-Serif">User Profile</b></a>\n       &nbsp<a href="/admin/blockAdmin.php"><b><font size="-1" face="Verdana,Arial,Helvetica,San-Serif">Block</b></a>\n       &nbsp<a href="/admin/pollAdmin.php"><b><font size="-1" face="Verdana,Arial,Helvetica,San-Serif">Poll</b></a>\n       &nbsp<a href="/admin/authorAdmin.php"><b><font size="-1" face="Verdana,Arial,Helvetica,San-Serif">Author</b></a>\n       &nbsp<a href="/admin/infologAdmin.php"><b><font size="-1" face="Verdana,Arial,Helvetica,San-Serif">Logging</b></a>\n       &nbsp<a href="/admin/groupAdmin.php"><b><font size="-1" face="Verdana,Arial,Helvetica,San-Serif">Group</b></a>\n       &nbsp<a href="/admin/BE_sectionAdmin.php"><b><font size="-1" face="Verdana,Arial,Helvetica,San-Serif">Section</b></a>\n       &nbsp<a href="/admin/BE_articleAdmin.php"><b><font size="-1" face="Verdana,Arial,Helvetica,San-Serif">Article</b></a>\n       &nbsp<a href="/admin/BE_subsiteAdmin.php"><b><font size="-1" face="Verdana,Arial,Helvetica,San-Serif">Subsites</b></a>\n       &nbsp<a href="/admin/BE_linkAdmin.php"><b><font size="-1" face="Verdana,Arial,Helvetica,San-Serif">Link-Admin</b></a>\n       &nbsp<a href="/admin/BE_editTemplateAdmin.php"><b><font size="-1" face="Verdana,Arial,Helvetica,San-Serif">Edit Templates</b></a>\n       &nbsp<a href="/admin/BE_uploadAdmin.php"><b><font size="-1" face="Verdana,Arial,Helvetica,San-Serif">Upload</b></a>\n       &nbsp<a href="/admin/BE_galleryAdmin.php"><b><font size="-1" face="Verdana,Arial,Helvetica,San-Serif">Gallery</b></a>\n<!-- END: navbarBlock.tpl -->\n', 'a:5:{s:6:"column";s:6:"center";s:5:"width";s:0:"";s:8:"box_type";s:0:"";s:5:"perms";s:4:"user";s:10:"cache_data";a:1:{s:2:"fr";a:1:{s:10:"BE_default";a:2:{s:10:"cache_data";s:0:"";s:11:"last_update";i:1049522980;}}}}', 50, 1049749221);

INSERT INTO db_sequence VALUES ('psl_block',201);

DROP TABLE `be_localrights`;

INSERT INTO psl_author VALUES (20, 'nobody', 'Anonymous', '', '', '', '7dae6bd6d92a6c64367c27ea48169e4e', 0, '', 'N;')

# DROP TABLE `psl_block_old`;


INSERT INTO psl_section_block_lut VALUES (44, 10, 7);
INSERT INTO psl_section_block_lut VALUES (43, 10, 6);
INSERT INTO psl_section_block_lut VALUES (42, 10, 4);
INSERT INTO psl_section_block_lut VALUES (41, 10, 2);
INSERT INTO psl_section_block_lut VALUES (40, 10, 1);
INSERT INTO psl_section_block_lut VALUES (17, 200, 1);
INSERT INTO psl_section_block_lut VALUES (18, 200, 2);
INSERT INTO psl_section_block_lut VALUES (19, 200, 4);
INSERT INTO psl_section_block_lut VALUES (20, 200, 6);
INSERT INTO psl_section_block_lut VALUES (21, 200, 7);
INSERT INTO psl_section_block_lut VALUES (22, 200, 9);
INSERT INTO psl_section_block_lut VALUES (39, 8, 9);
INSERT INTO psl_section_block_lut VALUES (38, 8, 7);
INSERT INTO psl_section_block_lut VALUES (37, 8, 6);
INSERT INTO psl_section_block_lut VALUES (36, 8, 4);
INSERT INTO psl_section_block_lut VALUES (35, 8, 2);
INSERT INTO psl_section_block_lut VALUES (34, 8, 1);
INSERT INTO psl_section_block_lut VALUES (97, 13, 7);
INSERT INTO psl_section_block_lut VALUES (96, 13, 6);
INSERT INTO psl_section_block_lut VALUES (95, 13, 4);
INSERT INTO psl_section_block_lut VALUES (94, 13, 2);
INSERT INTO psl_section_block_lut VALUES (93, 13, 1);

# 23May03:mg
# sql for rssTool
#

CREATE TABLE be_rsstool (
  md5 varchar(50) NOT NULL default '0',
  url varchar(255) default '',
  dateCreated int(10) unsigned NOT NULL default '0',
  dateModified int(10) unsigned NOT NULL default '0',
  dateRemoved int(10) unsigned NOT NULL default '0',
  requests text NOT NULL,
  DATA text NOT NULL,
  PRIMARY KEY  (md5),
  KEY md5 (md5,dateRemoved)
) TYPE=MyISAM COMMENT='rss and html cache';  

ALTER TABLE `be_linkText` CHANGE `description` `description` TEXT DEFAULT NULL;
 ALTER TABLE `be_linkText` CHANGE `description_source` `description_source` TEXT DEFAULT NULL;