UPDATE psl_variable SET value="0.7.1.3" WHERE variable_id='100';

# ALTER TABLE `be_actionText` CHANGE `blerb` `blurb` TEXT NOT NULL;
# ALTER TABLE `be_actionText` CHANGE `blerb_source` `blurb_source` TEXT NOT NULL;

ALTER TABLE `be_articleText` CHANGE `blerb` `blurb` TEXT NOT NULL;
ALTER TABLE `be_articleText` CHANGE `blerb_source` `blurb_source` TEXT NOT NULL;
ALTER TABLE `be_sectionText` CHANGE `blerb` `blurb` TEXT DEFAULT NULL;
ALTER TABLE `be_sectionText` CHANGE `blerb_source` `blurb_source` TEXT NOT NULL;
