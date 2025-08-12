ALTER TABLE `onecms_settings` ADD `sname` TEXT NOT NULL AFTER `id`
-------
CREATE TABLE `onecms_blog` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `name` text NOT NULL,
  `blog` text NOT NULL,
  `username` text NOT NULL,
  `date` text NOT NULL,
  PRIMARY KEY `id` (`id`)
) TYPE=MyISAM AUTO_INCREMENT=1;
-------
UPDATE `onecms_settings` SET `sname` = 'global' WHERE `id` = '1'
-------
UPDATE `onecms_settings` SET `sname` = 'general' WHERE `id` = '2'
-------
UPDATE `onecms_settings` SET `sname` = 'forum' WHERE `id` = '3'
-------
UPDATE `onecms_settings` SET `dformat` = '' WHERE `id` = '2'
-------
INSERT INTO `onecms_settings` VALUES (4, 'albums', '', '', '', '', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL);
-------
INSERT INTO `onecms_settings` VALUES (5, 'chat', '10', 'Yes', 'Yes', 'Yes', 'Yes', 'No', 'No', NULL, NULL, NULL, NULL, NULL);
-------
INSERT INTO `onecms_settings` VALUES (6, 'templates', 'latestcontent', 'list2-games', 'top10-2', 'No', 'No', NULL, NULL, NULL, NULL, NULL, NULL, NULL);
-------
INSERT INTO `onecms_templates` VALUES (null, 'top10-1', '{name} ({stats}) - {genre} :: {boxart} (that is the boxart)<br>', 'list');
------
INSERT INTO `onecms_templates` VALUES (null, 'top10-2', '{name} ({stats}) - {genre} :: {collection} {wishlist}<br>', 'list');
------
INSERT INTO `onecms_templates` VALUES (null, 'list2-games', '<a href=\\''{link}\\''>{name}</a> :: {genre}<br>', 'list');
------
INSERT INTO `onecms_templates` VALUES (null, 'companies', '<a href=\\''{link}\\''>{name}</a><br>', 'list');