ALTER TABLE `bitmap` ADD `flag` INT( 1 ) UNSIGNED DEFAULT '0' NOT NULL AFTER `filemtime` ;
ALTER TABLE `bitmap` ADD INDEX ( `flag` ) ;

#
# Database version
#

INSERT INTO configuration_database VALUES (10) ;

