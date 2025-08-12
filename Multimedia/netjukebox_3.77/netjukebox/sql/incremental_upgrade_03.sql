ALTER TABLE `track` ADD `audio_bitrate` INT UNSIGNED NOT NULL AFTER `file_size` ;
ALTER TABLE `configuration_users` ADD `access_download` ENUM( 'N', 'Y' ) DEFAULT 'N' NOT NULL AFTER `access_record` ;

TRUNCATE TABLE `track` ;
# Table track has been emptied 


#
# Database version
#

INSERT INTO configuration_database VALUES (3) ;
