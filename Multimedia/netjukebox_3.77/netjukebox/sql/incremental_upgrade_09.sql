ALTER TABLE `configuration_users` ADD `access_playlist` ENUM( 'N', 'Y' ) DEFAULT 'N' NOT NULL AFTER `access_favorites` ;
ALTER TABLE `configuration_users` ADD `access_add` ENUM( 'N', 'Y' ) DEFAULT 'N' NOT NULL AFTER `access_play` ;


#
# Database version
#

INSERT INTO configuration_database VALUES (9) ;
