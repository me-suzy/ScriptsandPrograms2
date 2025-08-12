ALTER TABLE `pl_topics` ADD `keywords` VARCHAR( 255 ) NOT NULL ;
ALTER TABLE `pl_topics` ADD FULLTEXT(`title`);
ALTER TABLE `pl_topics` ADD FULLTEXT(`keywords`);

ALTER TABLE `pl_links` ADD `postdate` INT( 11 ) DEFAULT '1124620234' NOT NULL AFTER `priority` ;

-- INSERT INTO `pl_config` (`config_name`, `config_value`, `config_help`) VALUES ('dropdownfullpaths', 'true', 'If you are using the topic drop down menu, set this to true to display full paths such as topic a > topic b > topic c or set it to false to use #id number - name (uses less resources especially with big directories).');
INSERT INTO `pl_config` VALUES ('dropdownfullpaths', 'true', 'If you are using the topic drop down menu, set this to true to display full paths such as topic a > topic b > topic c or set it to false to use #id number - name (uses less resources especially with big directories).');
INSERT INTO `pl_config` VALUES ('topicresults', '10', 'Number of related topics (if any) to show on the search results page. Set to 99999 to essentially show them all (not recommended but setting it to  something like 50 won''t do any harm) or set to 0 to disable.');
INSERT INTO `pl_config` VALUES ('recentlyadded', '10', 'Number of sites listed on the recently added page. You can set this to 0 to disable the feature (users will be redirected back to the main topic page if the feature is disabled).');
INSERT INTO `pl_config` VALUES ('showrecentlink', 'true', 'Set to true to show the recently added page link at the top next to the admin link or set to false to disable it.');

-- --------------------------------------------------------

-- 
-- Table structure for table `pl_phrases`
-- 

DROP TABLE IF EXISTS `pl_phrases`;
CREATE TABLE `pl_phrases` (
  `phraseid` int(11) NOT NULL auto_increment,
  `phrase_name` varchar(255) NOT NULL default '',
  `phrase_value` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`phraseid`)
) TYPE=MyISAM AUTO_INCREMENT=30 ;

-- 
-- Dumping data for table `pl_phrases`
-- 

INSERT INTO `pl_phrases` VALUES (1, 'top', 'Top');
INSERT INTO `pl_phrases` VALUES (2, 'suggesturl', 'Suggest URL');
INSERT INTO `pl_phrases` VALUES (3, 'search', 'Search');
INSERT INTO `pl_phrases` VALUES (4, 'sitename', 'Site Name');
INSERT INTO `pl_phrases` VALUES (5, 'url', 'URL');
INSERT INTO `pl_phrases` VALUES (6, 'description', 'Description');
INSERT INTO `pl_phrases` VALUES (7, 'emailaddress', 'Email Address');
INSERT INTO `pl_phrases` VALUES (8, 'results', 'results');
INSERT INTO `pl_phrases` VALUES (9, 'topic', 'Topic');
INSERT INTO `pl_phrases` VALUES (10, 'noresults', 'No results were found for this search term, please try another');
INSERT INTO `pl_phrases` VALUES (11, 'resultspages', 'Results Pages');
INSERT INTO `pl_phrases` VALUES (12, 'submit_notitle', 'No title entered');
INSERT INTO `pl_phrases` VALUES (13, 'submit_nourl', 'No URL entered');
INSERT INTO `pl_phrases` VALUES (14, 'submit_nodescription', 'No description entered');
INSERT INTO `pl_phrases` VALUES (15, 'submit_missingtopic', 'Unable to locate the topic');
INSERT INTO `pl_phrases` VALUES (16, 'submit_urlinqueue', 'This URL is already in the queue');
INSERT INTO `pl_phrases` VALUES (17, 'submit_success', 'Website suggestion recorded successfully!');
INSERT INTO `pl_phrases` VALUES (18, 'submit_submission', 'Submission');
INSERT INTO `pl_phrases` VALUES (19, 'submit_emailbody', 'With regards to your submissions to {SITENAME}, your site, {WEBSITE}, has been {MSG}.');
INSERT INTO `pl_phrases` VALUES (20, 'submit_rejected', 'rejected');
INSERT INTO `pl_phrases` VALUES (21, 'submit_accepted', 'accepted');
INSERT INTO `pl_phrases` VALUES (22, 'allrightsreserved', 'All rights reserved');
INSERT INTO `pl_phrases` VALUES (23, 'poweredby', 'Powered by');
INSERT INTO `pl_phrases` VALUES (24, 'change', 'Change');
INSERT INTO `pl_phrases` VALUES (25, 'skin', 'Skin');
INSERT INTO `pl_phrases` VALUES (26, 'usedefaultskin', 'Use default skin');
INSERT INTO `pl_phrases` VALUES (27, 'recentlyadded', 'Recently Added');
INSERT INTO `pl_phrases` VALUES (28, 'admin', 'Admin');
INSERT INTO `pl_phrases` VALUES (29, 'newest', 'Newest');