
# Destiney.com Scripts Upgrade SQL v0.1 -> 0.2

ALTER TABLE `users` ADD `subscribed` ENUM( 'yes', 'no' ) DEFAULT 'yes' NOT NULL AFTER `total_comments` ;
ALTER TABLE `users` ADD `md5key` CHAR(32) NOT NULL AFTER `subscribed`;
UPDATE `users` SET `md5key` = md5(`username`);
ALTER TABLE `users` ADD UNIQUE (`md5key`);
ALTER TABLE `user_types` ADD `gender` ENUM( 'm', 'f' ) NOT NULL AFTER `user_type`;

CREATE TABLE `comment_views` (
  `comment_id` int(11) unsigned NOT NULL default '0',
  `ip` char(15) NOT NULL default '',
  PRIMARY KEY  (`comment_id`,`ip`)
);

ALTER TABLE `comments` ADD `subject` VARCHAR( 255 ) NOT NULL AFTER `user_id`;
