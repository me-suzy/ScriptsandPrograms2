ALTER TABLE `configuration_users` CHANGE `stream_id` `stream_id` INT( 10 ) DEFAULT '0' NOT NULL ;

#
# Database version
#

INSERT INTO configuration_database VALUES (8) ;
