# Oct 20 - bug fixes in 0.7 release - mg

# only install if the petition module has been loaded
# ALTER TABLE `pet_alert` CHANGE `fromName` `senderName` VARCHAR( 50 ) DEFAULT NULL;
# ALTER TABLE `pet_alert` CHANGE `fromEmail` `sender` VARCHAR( 50 ) DEFAULT NULL;

INSERT INTO `psl_commentcount` ( `count_id` , `count` ) VALUES ('4', '0');

UPDATE psl_variable SET value="0.7.0.1" WHERE variable_id='100';