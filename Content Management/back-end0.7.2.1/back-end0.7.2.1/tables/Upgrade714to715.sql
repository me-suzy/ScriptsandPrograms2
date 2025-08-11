#  Update the version flag.
UPDATE psl_variable SET value="0.7.1.5" WHERE variable_id='100';

CREATE TABLE be_hits (
  hitTime int(11) unsigned NOT NULL default '0',
  articleID int(11) unsigned NOT NULL default '0',
  KEY hitTime (hitTime),
  KEY articleID (articleID)
) TYPE=MyISAM;

ALTER TABLE `psl_infolog` ADD INDEX data(data);
ALTER TABLE `psl_infolog` CHANGE `id` `id` SMALLINT( 10 ) UNSIGNED DEFAULT '0' NOT NULL;
ALTER TABLE `be_sections` CHANGE `hitCounter` `hitCounter` SMALLINT( 10 ) UNSIGNED DEFAULT '0' NOT NULL;
ALTER TABLE `be_articles` CHANGE `hitCounter` `hitCounter` SMALLINT( 10 ) UNSIGNED DEFAULT '0' NOT NULL;
ALTER TABLE `be_link` CHANGE `hitCounter` `hitCounter` SMALLINT( 10 ) UNSIGNED DEFAULT '0' NOT NULL;

# Seems required by BE_Action_base. maparent
#ALTER TABLE `be_action` ADD subsiteID smallint(5) NOT NULL default '0' AFTER author_id;
#ALTER TABLE `be_action` ADD INDEX subsiteID(subsiteID);
#ALTER TABLE be_action2contact DROP PRIMARY KEY;
#ALTER TABLE be_action2contact ADD PRIMARY KEY (contactID, actionID,targetID);

# Required for changes to petitions - if petitions aren't installed, don't
# apply this.
# ALTER TABLE `pet_petition` CHANGE `petitionCounter` `petitionCounter` smallint(10) unsigned default '0' not null;
# ALTER TABLE `pet_petition` ADD hitCounter smallint(10) unsigned default '0' not null;
# ALTER TABLE `pet_petition` ADD sectionID smallint(10) default '-1' not null;

# Required for changes to Actions. Apply if actions are installed.
# ALTER TABLE `be_action` CHANGE `actionCounter` `actionCounter` smallint(10) unsigned default '0' not null;
# ALTER TABLE `be_action` ADD hitCounter smallint(10) unsigned default '0' not null;
