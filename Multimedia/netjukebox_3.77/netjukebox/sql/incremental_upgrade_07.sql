ALTER TABLE `configuration_users` ADD `stream_id` INT( 10 ) UNSIGNED DEFAULT '0' NOT NULL AFTER `access_config` ;

ALTER TABLE `configuration_client` RENAME `configuration_httpq` ;
ALTER TABLE `configuration_httpq` CHANGE `id` `httpq_id` INT( 10 ) NOT NULL AUTO_INCREMENT ;


#
# Database version
#

INSERT INTO configuration_database VALUES (7) ;
