ALTER TABLE `track` ADD `audio_bits_per_sample` INT( 10 ) UNSIGNED DEFAULT '0' NOT NULL AFTER `audio_raw_decoded` ,
ADD `audio_sample_rate` INT( 10 ) UNSIGNED DEFAULT '0' NOT NULL AFTER `audio_bits_per_sample` ,
ADD `audio_channels` INT( 4 ) UNSIGNED DEFAULT '0' NOT NULL AFTER `audio_sample_rate` ;

TRUNCATE TABLE `track` ;

#
# Database version
#

INSERT INTO configuration_database VALUES (6) ;


