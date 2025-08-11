#  Update the version flag.
UPDATE psl_variable SET value="0.7.1.4" WHERE variable_id='100';

ALTER TABLE `be_sections` ADD subsiteID smallint(5) NOT NULL DEFAULT 0 AFTER author_id;
ALTER TABLE `be_sections` ADD INDEX subsiteID(subsiteID);

#  Make URLname language-specific -- ian@clysdale.ca, Sep30/2004
ALTER TABLE be_articleText ADD URLname varchar(255) NOT NULL DEFAULT '' AFTER languageID;
ALTER TABLE be_articleText ADD INDEX URLname(URLname);
ALTER TABLE be_sectionText ADD URLname varchar(255) NOT NULL DEFAULT '' AFTER languageID;
ALTER TABLE be_sectionText ADD INDEX URLname(URLname);
