
## 
## Table structure for table `ss_announcements`
## 

DROP TABLE IF EXISTS `ss_announcements`;
CREATE TABLE `ss_announcements` (
  `announcement_id` int(11) NOT NULL auto_increment,
  `subject` varchar(255) NOT NULL default '',
  `text` text NOT NULL,
  `date` int(11) NOT NULL default '0',
  `username` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`announcement_id`)
) TYPE=MyISAM AUTO_INCREMENT=2 ;

## 
## Dumping data for table `ss_announcements`
## 

INSERT INTO `ss_announcements` VALUES (1, 'My First Announcement', 'You can use the announcement system to notify your users of important updates or changes at your StoryStream website.', 1082685104, 'admin');

## --------------------------------------------------------

## 
## Table structure for table `ss_bookmarks`
## 

DROP TABLE IF EXISTS `ss_bookmarks`;
CREATE TABLE `ss_bookmarks` (
  `bookmark_id` int(10) unsigned NOT NULL auto_increment,
  `subject_type` tinyint(4) NOT NULL default '0',
  `subject_id` int(11) NOT NULL default '0',
  `user_id` varchar(255) NOT NULL default '',
  `date` int(10) unsigned NOT NULL default '0',
  `name` varchar(255) NOT NULL default '',
  `notes` text NOT NULL,
  PRIMARY KEY  (`bookmark_id`)
) TYPE=MyISAM AUTO_INCREMENT=10 ;

## 
## Dumping data for table `ss_bookmarks`
## 

INSERT INTO `ss_bookmarks` VALUES (9, 2, 27, 'admin', 1079639824, 'My First Bookmark', 'Use the bookmarks to annotate scenes and stories as you read them.  The bookmark name and these notes will thereafter appear on the page near the story or scene.');

## --------------------------------------------------------

## 
## Table structure for table `ss_classification`
## 

DROP TABLE IF EXISTS `ss_classification`;
CREATE TABLE `ss_classification` (
  `classification_id` int(10) unsigned NOT NULL auto_increment,
  `subject_type` tinyint(10) unsigned NOT NULL default '0',
  `subject_id` int(10) unsigned NOT NULL default '0',
  `story_id` int(11) NOT NULL default '0',
  `user_id` varchar(255) NOT NULL default '0',
  `weight` tinyint(4) NOT NULL default '0',
  `date` int(14) default NULL,
  `ip` varchar(255) NOT NULL default '',
  `classification` varchar(255) NOT NULL default '0',
  `comment` text NOT NULL,
  `client_info` text NOT NULL,
  `rating_id` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`classification_id`)
) TYPE=MyISAM COMMENT='Contains a list of all the classifications ever made.' AUTO_INCREMENT=2 ;

## 
## Dumping data for table `ss_classification`
## 


## --------------------------------------------------------

## 
## Table structure for table `ss_fork`
## 

DROP TABLE IF EXISTS `ss_fork`;
CREATE TABLE `ss_fork` (
  `fork_id` int(10) unsigned NOT NULL auto_increment,
  `name` text NOT NULL,
  `user_id` varchar(255) NOT NULL default '',
  `description` text NOT NULL,
  `from_scene_id` int(10) NOT NULL default '0',
  `start_mod_id` int(10) unsigned NOT NULL default '0',
  `last_mod_id` int(10) unsigned NOT NULL default '0',
  `chosen_count` int(10) unsigned NOT NULL default '0',
  `status` int(10) unsigned NOT NULL default '0',
  `story_id` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`fork_id`)
) TYPE=MyISAM AUTO_INCREMENT=38 ;

## 
## Dumping data for table `ss_fork`
## 

INSERT INTO `ss_fork` VALUES (36, 'My Beginning Fork', 'admin', 'The beginning fork is one of many possible jumping off points from the story you or someone else has defined.  The description should probably describe what action would be taken in the setting described in the story description.', 0, 0, 0, 0, 1, 27);

INSERT INTO `ss_fork` VALUES (37, 'My Other Beginning Fork', 'admin', 'This is another way that a reader could start the story.  It might be a different event that occured in the same setting or it might follow the same event but from the perspective of a different character in the same setting.', 0, 0, 0, 0, 1, 27);

## --------------------------------------------------------

## 
## Table structure for table `ss_group`
## 

DROP TABLE IF EXISTS `ss_group`;
CREATE TABLE `ss_group` (
  `group_id` int(11) NOT NULL auto_increment,
  `name` varchar(255) NOT NULL default '',
  `username` varchar(255) NOT NULL default '',
  `description` text NOT NULL,
  `date` int(11) NOT NULL default '0',
  `status` tinyint(4) NOT NULL default '0',
  `allow_nonmember_viewing` tinyint(4) NOT NULL default '0',
  PRIMARY KEY  (`group_id`)
) TYPE=MyISAM AUTO_INCREMENT=18 ;

## 
## Dumping data for table `ss_group`
## 


## --------------------------------------------------------

## 
## Table structure for table `ss_modification`
## 

DROP TABLE IF EXISTS `ss_modification`;
CREATE TABLE `ss_modification` (
  `mod_id` int(10) unsigned NOT NULL auto_increment,
  `subject_type` int(10) unsigned NOT NULL default '0',
  `subject_id` int(10) unsigned NOT NULL default '0',
  `user_id` varchar(255) NOT NULL default '0',
  `story_id` int(10) unsigned NOT NULL default '0',
  `date` int(14) default NULL,
  `ip` varchar(255) NOT NULL default '',
  `action` tinyint(10) unsigned NOT NULL default '0',
  `data` text NOT NULL,
  `client_info` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`mod_id`)
) TYPE=MyISAM COMMENT='Contains a list of all the modifications done to all types o' AUTO_INCREMENT=1 ;

## 
## Dumping data for table `ss_modification`
## 


## --------------------------------------------------------

## 
## Table structure for table `ss_rating`
## 

DROP TABLE IF EXISTS `ss_rating`;
CREATE TABLE `ss_rating` (
  `rating_id` int(10) unsigned NOT NULL auto_increment,
  `subject_type` tinyint(10) unsigned NOT NULL default '0',
  `subject_id` int(10) unsigned NOT NULL default '0',
  `story_id` int(11) NOT NULL default '0',
  `user_id` varchar(255) NOT NULL default '0',
  `weight` tinyint(4) NOT NULL default '0',
  `date` int(14) default NULL,
  `ip` varchar(255) NOT NULL default '',
  `rating` tinyint(4) NOT NULL default '0',
  `comment` text NOT NULL,
  `client_info` text NOT NULL,
  `classification_id` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`rating_id`)
) TYPE=MyISAM COMMENT='Contains a list of all the ratings ever given.' AUTO_INCREMENT=1 ;

## 
## Dumping data for table `ss_rating`
## 


## --------------------------------------------------------

## 
## Table structure for table `ss_scene`
## 

DROP TABLE IF EXISTS `ss_scene`;
CREATE TABLE `ss_scene` (
  `scene_id` int(10) unsigned NOT NULL auto_increment,
  `name` text NOT NULL,
  `description` text NOT NULL,
  `user_id` varchar(255) NOT NULL default '',
  `rating` text NOT NULL,
  `story_id` int(10) unsigned NOT NULL default '0',
  `source_fork_id` int(11) NOT NULL default '0',
  `end_fork_id` int(11) NOT NULL default '0',
  `type` int(10) unsigned NOT NULL default '0',
  `start_mod_id` int(10) unsigned NOT NULL default '0',
  `last_mod_id` int(10) unsigned NOT NULL default '0',
  `status` int(10) unsigned NOT NULL default '0',
  `data_binary` longblob NOT NULL,
  `data_text` text NOT NULL,
  `data_type` varchar(255) NOT NULL default '0',
  `data_properties` text NOT NULL,
  `phpbb_topic_id` int(11) NOT NULL default '0',
  `license_url` text NOT NULL,
  `license_name` varchar(255) NOT NULL default '',
  `license_code` varchar(10) NOT NULL default '',
  PRIMARY KEY  (`scene_id`)
) TYPE=MyISAM AUTO_INCREMENT=32 ;

## 
## Dumping data for table `ss_scene`
## 

INSERT INTO `ss_scene` VALUES (30, 'My First Scene', 'This is the first scene in the story.  It branches off of a beginning fork so you might consider it Chapter 1 in just one of the many possible interpretations of the story.', 'admin', '0', 27, 36, 0, 0, 0, 0, 2, '', 'The text of the scene is the actual content of the story.  This is where your prose (or poetry) would go.  Only you can edit the scene you have created but anyone else can add another scene to the same fork.  That way, we can see lots of interpretations of the same event.', '0', '', 0, '', '', '');
INSERT INTO `ss_scene` VALUES (31, 'Your First Scene', 'I, being someone other than the person who wrote "My First Scene", decide that "My First Scene" kinda sucks.  Which is too bad because the story has a lot of potential.  Rather than complaining about it, though, I am going to write my own scene here.', 'admin', '0', 27, 36, 0, 0, 0, 0, 2, '', 'With alternate scenes, you generally want to stick with the premise of the fork that lead you to it.  This scene can be seen as in competition with "My First Scene".  The ratings it gets will tell you, the author, whether or not you have written a popular piece.  The genre or classification will tell others what kind of content to expect and to help them decide what to read when browsing.', '0', '', 0, '', '', '');

## --------------------------------------------------------

## 
## Table structure for table `ss_story`
## 

DROP TABLE IF EXISTS `ss_story`;
CREATE TABLE `ss_story` (
  `story_id` int(10) unsigned NOT NULL auto_increment,
  `name` text NOT NULL,
  `synopsis` text NOT NULL,
  `description` text NOT NULL,
  `user_id` varchar(255) NOT NULL default '',
  `type` int(10) unsigned NOT NULL default '0',
  `rating` text NOT NULL,
  `permission` int(11) unsigned NOT NULL default '0',
  `start_mod_id` int(11) unsigned NOT NULL default '0',
  `last_mod_id` int(11) unsigned NOT NULL default '0',
  `status` int(11) unsigned NOT NULL default '0',
  `degrees` int(11) NOT NULL default '0',
  `begin_scene_id` int(11) NOT NULL default '0',
  `end_scene_id` int(11) NOT NULL default '0',
  `phpbb_topic_id` int(11) NOT NULL default '0',
  `license_url` text NOT NULL,
  `license_name` varchar(255) NOT NULL default '',
  `license_code` varchar(10) NOT NULL default '',
  `data_type` varchar(255) NOT NULL default '',
  `data_binary` longblob NOT NULL,
  `data_properties` text NOT NULL,
  `group_id` int(11) NOT NULL default '0',
  PRIMARY KEY  (`story_id`),
  UNIQUE KEY `story_id` (`story_id`)
) TYPE=MyISAM COMMENT='Contains a list of submitted stories (finished and unfinishe' AUTO_INCREMENT=28 ;

## 
## Dumping data for table `ss_story`
## 

INSERT INTO `ss_story` VALUES (27, 'My First Story', 'This is the first story I''ve written.  The synopsis contains a brief overview of what the story is about and maybe some information about what type of forks/scenes should be added.', 'This is the first scene of the story.  All streams will fork from this scene.', 'admin', 3, '', 0, 0, 0, 1, 0, 0, 0, 0, '', '', '', '', '', '', 0);

## --------------------------------------------------------

## 
## Table structure for table `ss_user_group_map`
## 

DROP TABLE IF EXISTS `ss_user_group_map`;
CREATE TABLE `ss_user_group_map` (
  `group_id` int(11) NOT NULL default '0',
  `username` varchar(255) NOT NULL default '',
  `date` int(11) NOT NULL default '0',
  `type` tinyint(4) NOT NULL default '0'
) TYPE=MyISAM;

## 
## Dumping data for table `ss_user_group_map`
## 


## --------------------------------------------------------

## 
## Table structure for table `ss_users`
## 

DROP TABLE IF EXISTS `ss_users`;
CREATE TABLE `ss_users` (
  `username` varchar(255) NOT NULL default '',
  `first_name` text NOT NULL,
  `last_name` text NOT NULL,
  `email` text NOT NULL,
  `password` varchar(255) NOT NULL default '',
  `date_joined` bigint(14) default NULL,
  `date_lastlogin` bigint(14) default NULL,
  `last_session_id` text NOT NULL,
  `date_lastactivity` bigint(14) default NULL,
  `user_type` int(10) unsigned NOT NULL default '0',
  `status` tinyint(3) unsigned NOT NULL default '0',
  `hash` varchar(255) NOT NULL default '',
  `login_ip` text NOT NULL,
  `login_client_info` text NOT NULL,
  `phpbb_user_id` int(11) NOT NULL default '0',
  `phpbb_session_id` varchar(32) NOT NULL default '',
  `email_notify_new_story` tinyint(4) NOT NULL default '0',
  `email_notify_new_scene_fork` tinyint(4) NOT NULL default '0',
  `email_notify_updates` tinyint(4) NOT NULL default '0',
  `rank` int(11) NOT NULL default '0',
  PRIMARY KEY  (`username`)
) TYPE=MyISAM;

## 
## Dumping data for table `ss_users`
## 

INSERT INTO `ss_users` VALUES ('admin', 'StoryStream', 'Administrator', 'youremail@domain.com', '7c6a180b36896a0a8c02787eeafb0e4c', 0, 1090269783, 'd70d25e57fc49014a03acf240c115b71', 1090269783, 3, 1, '', '127.0.0.1', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.7) Gecko/20040711 Firefox/0.9.0+', 2, 'ba926443da0cc76675979ae1c9d2df26', 0, 0, 0, 0);

## --------------------------------------------------------

## 
## Table structure for table `ss_view`
## 

DROP TABLE IF EXISTS `ss_view`;
CREATE TABLE `ss_view` (
  `view_id` int(20) unsigned NOT NULL auto_increment,
  `target_type` int(10) unsigned NOT NULL default '0',
  `target_id` int(10) unsigned NOT NULL default '0',
  `user_id` varchar(255) default NULL,
  `story_id` int(10) unsigned NOT NULL default '0',
  `view_date` timestamp(14) NOT NULL,
  `view_ip` text NOT NULL,
  `client_info` text NOT NULL,
  PRIMARY KEY  (`view_id`)
) TYPE=MyISAM AUTO_INCREMENT=1 ;

## 
## Dumping data for table `ss_view`
## 

