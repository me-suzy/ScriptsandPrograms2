ALTER TABLE `bitmap` ADD INDEX ( `filemtime` ) ;
ALTER TABLE `bitmap` ADD INDEX ( `cd_front` ) ;
ALTER TABLE `bitmap` ADD INDEX ( `cd_back` ) ;

#
# Database version
#

INSERT INTO configuration_database VALUES (5) ;


