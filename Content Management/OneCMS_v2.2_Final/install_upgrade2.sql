ALTER TABLE `onecms_albums` DROP `type`
-------
ALTER TABLE `onecms_albums` DROP `album`
-------
ALTER TABLE `onecms_albums` ADD `views` INT( 11 ) DEFAULT '0' NOT NULL
-------
ALTER TABLE `onecms_albums` ADD `systems` TEXT NOT NULL
-------
ALTER TABLE `onecms_images` DROP `watermark`
-------
ALTER TABLE `onecms_images` ADD `type2` TEXT NOT NULL
-------
ALTER TABLE `onecms_mods` ADD `version` VARCHAR( 5 ) NOT NULL
-------
ALTER TABLE `onecms_mods` ADD `readme` TEXT NOT NULL
-------
ALTER TABLE `onecms_mods` ADD `sql` TEXT NOT NULL
-------
ALTER TABLE `onecms_mods` ADD `status` CHAR( 3 ) NOT NULL
-------
INSERT INTO `onecms_mods` VALUES (1, 'Random Top 10', '', 'Yes', '1.0', '', 'random10.php', 'On');
-------
INSERT INTO `onecms_mods` VALUES (2, 'Newsletter', 'a_newsletter.php', 'Yes', '1.0', '', '', 'On');
-------
INSERT INTO `onecms_mods` VALUES (3, 'PR', 'a_pr.php', 'Yes', '1.0', '', '', 'On');
-------
INSERT INTO `onecms_mods` VALUES (4, 'Affiliates', 'a_af.php', 'Yes', '1.0', '', '', 'On');
-------
INSERT INTO `onecms_mods` VALUES (5, 'File', 'a_upload.php', 'Yes', '2.0', '', 'files.php', 'On');
-------
INSERT INTO `onecms_mods` VALUES (6, 'Page', 'a_pages.php', 'Yes', '1.0', '', 'pages.php', 'On');
-------
INSERT INTO `onecms_mods` VALUES (7, 'Comments', 'a_comments2.php', 'Yes', '1.0', '', '', 'On');
-------
INSERT INTO `onecms_mods` VALUES (8, 'Smilies/Badwords', 'a_comments1.php', 'Yes', '1.0', '', '', 'On');
-------
INSERT INTO `onecms_mods` VALUES (9, 'Private Messaging', 'a_inbox.php?box=in', 'Yes', '1.0', '', 'pm.php?box=in', 'On');
-------
INSERT INTO `onecms_mods` VALUES (10, 'Ad', 'a_ad.php', 'Yes', '', '', '', 'On');
-------
INSERT INTO `onecms_mods` VALUES (11, 'Gallery', 'a_gallery.php', 'Yes', '1.0', '', 'gallery.php', 'On');
-------
INSERT INTO `onecms_mods` VALUES (12, 'Contest', 'a_contest.php', 'Yes', '1.0', '', 'contest.php', 'On');
-------
CREATE TABLE `onecms_userreviews` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `name` text NOT NULL,
  `games` text NOT NULL,
  `systems` text NOT NULL,
  `review` text NOT NULL,
  `overall` text NOT NULL,
  `rate` varchar(255) NOT NULL default '0|0',
  `user` text NOT NULL,
  `date` text NOT NULL,
  PRIMARY KEY  (`id`)
) TYPE=MyISAM AUTO_INCREMENT=1 ;
-------
UPDATE `onecms_settings` SET sname = 'gallery' WHERE sname = 'albums'
-------
ALTER TABLE `onecms_games` ADD `system` TEXT NOT NULL AFTER `skin`
-------
ALTER TABLE `onecms_games` ADD `album` TEXT NOT NULL AFTER `system`
-------
CREATE TABLE `onecms_fielddata` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `name` text NOT NULL,
  `data` text NOT NULL,
  `id2` varchar(11) NOT NULL default '',
  `cat` text NOT NULL,
  PRIMARY KEY  (`id`)
) TYPE=MyISAM PACK_KEYS=0 AUTO_INCREMENT=1 ;
-------
ALTER TABLE `onecms_ad` ADD `views` VARCHAR( 11 ) DEFAULT '0' NOT NULL ;