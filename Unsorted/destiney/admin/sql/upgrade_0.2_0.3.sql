
# Destiney.com Scripts Upgrade SQL v0.2 -> 0.3

CREATE TABLE `comment_threads` (
	`comment_id` INT( 11 ) UNSIGNED NOT NULL,
	`updated` TIMESTAMP( 14 ) NOT NULL,
	`timestamp` TIMESTAMP( 14 ) NOT NULL,
	PRIMARY KEY ( `comment_id` ) 
);

INSERT  INTO `comment_threads` SELECT `id`, `timestamp`, `timestamp` FROM `comments` ORDER BY `id`;

ALTER TABLE `comments` DROP `timestamp`;

UPDATE `users` SET `state` = 'Alabama' WHERE `state` = 'AL';
UPDATE `users` SET `state` = 'Alaska' WHERE `state` = 'AK';
UPDATE `users` SET `state` = 'Arizona' WHERE `state` = 'AZ';
UPDATE `users` SET `state` = 'Arkansas' WHERE `state` = 'AR';
UPDATE `users` SET `state` = 'California' WHERE `state` = 'CA';
UPDATE `users` SET `state` = 'Colorado' WHERE `state` = 'CO';
UPDATE `users` SET `state` = 'Connecticut' WHERE `state` = 'CT';
UPDATE `users` SET `state` = 'Delaware' WHERE `state` = 'DE';
UPDATE `users` SET `state` = 'Florida' WHERE `state` = 'FL';
UPDATE `users` SET `state` = 'Georgia' WHERE `state` = 'GA';
UPDATE `users` SET `state` = 'Hawaii' WHERE `state` = 'HI';
UPDATE `users` SET `state` = 'Idaho' WHERE `state` = 'ID';
UPDATE `users` SET `state` = 'Illinois' WHERE `state` = 'IL';
UPDATE `users` SET `state` = 'Indiana' WHERE `state` = 'IN';
UPDATE `users` SET `state` = 'Iowa' WHERE `state` = 'IA';
UPDATE `users` SET `state` = 'Kansas' WHERE `state` = 'KS';
UPDATE `users` SET `state` = 'Kentucky' WHERE `state` = 'KY';
UPDATE `users` SET `state` = 'Louisiana' WHERE `state` = 'LA';
UPDATE `users` SET `state` = 'Maine' WHERE `state` = 'ME';
UPDATE `users` SET `state` = 'Maryland' WHERE `state` = 'MD';
UPDATE `users` SET `state` = 'Massachusetts' WHERE `state` = 'MA';
UPDATE `users` SET `state` = 'Michigan' WHERE `state` = 'MI';
UPDATE `users` SET `state` = 'Minnesota' WHERE `state` = 'MN';
UPDATE `users` SET `state` = 'Mississippi' WHERE `state` = 'MS';
UPDATE `users` SET `state` = 'Missouri' WHERE `state` = 'MO';
UPDATE `users` SET `state` = 'Montana' WHERE `state` = 'MT';
UPDATE `users` SET `state` = 'Nebraska' WHERE `state` = 'NE';
UPDATE `users` SET `state` = 'Nevada' WHERE `state` = 'NV';
UPDATE `users` SET `state` = 'New Hampshire' WHERE `state` = 'NH';
UPDATE `users` SET `state` = 'New Jersey' WHERE `state` = 'NJ';
UPDATE `users` SET `state` = 'New Mexico' WHERE `state` = 'NM';
UPDATE `users` SET `state` = 'New York' WHERE `state` = 'NY';
UPDATE `users` SET `state` = 'North Carolina' WHERE `state` = 'NC';
UPDATE `users` SET `state` = 'North Dakota' WHERE `state` = 'ND';
UPDATE `users` SET `state` = 'Ohio' WHERE `state` = 'OH';
UPDATE `users` SET `state` = 'Oklahoma' WHERE `state` = 'OK';
UPDATE `users` SET `state` = 'Oregon' WHERE `state` = 'OR';
UPDATE `users` SET `state` = 'Pennsylvania' WHERE `state` = 'PA';
UPDATE `users` SET `state` = 'Rhode Island' WHERE `state` = 'RI';
UPDATE `users` SET `state` = 'South Carolina' WHERE `state` = 'SC';
UPDATE `users` SET `state` = 'South Dakota' WHERE `state` = 'SD';
UPDATE `users` SET `state` = 'Tennessee' WHERE `state` = 'TN';
UPDATE `users` SET `state` = 'Texas' WHERE `state` = 'TX';
UPDATE `users` SET `state` = 'Utah' WHERE `state` = 'UT';
UPDATE `users` SET `state` = 'Vermont' WHERE `state` = 'VT';
UPDATE `users` SET `state` = 'Virginia' WHERE `state` = 'VA';
UPDATE `users` SET `state` = 'Washington' WHERE `state` = 'WA';
UPDATE `users` SET `state` = 'West Virginia' WHERE `state` = 'WV';
UPDATE `users` SET `state` = 'Wisconsin' WHERE `state` = 'WI';
UPDATE `users` SET `state` = 'Wyoming' WHERE `state` = 'WY';

