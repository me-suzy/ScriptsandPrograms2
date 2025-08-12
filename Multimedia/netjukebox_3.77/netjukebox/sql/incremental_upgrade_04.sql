ALTER TABLE `configuration_client` CHANGE `httpq_host` `httpq_host` VARCHAR( 255 ) NOT NULL ;

ALTER TABLE `bitmap` DROP `image75` ;
ALTER TABLE `bitmap` ADD `image100` MEDIUMBLOB NOT NULL AFTER `image50` ;
TRUNCATE TABLE `bitmap` ;


#
# Database version
#

INSERT INTO configuration_database VALUES (4) ;

