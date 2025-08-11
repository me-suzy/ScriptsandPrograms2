# July01~:mg
# Adding sitemap section to control look/feel of the sitemap page
#

CREATE TABLE `psl_blockText` (
  `textID` int(11) unsigned NOT NULL default '0',
  `id` int(11) unsigned NOT NULL default '0',
  `languageID` char(3) NOT NULL default '',
  `title` varchar(255) NOT NULL default '',
  `location` varchar(254) NOT NULL default '',
  `source_url` varchar(254) NOT NULL default '',
  `cache_data` text NOT NULL,
  PRIMARY KEY  (`textID`),
  KEY `id` (`id`),
  KEY `id_2` (`id`,`languageID`)
) TYPE=MyISAM;  

# CUPE added an events module - I'll be integrating this
CREATE TABLE `be_event` (
  `eventID` smallint(5) NOT NULL default '0',
  `draft` smallint(3) NOT NULL default '0',
  `calendar` varchar(255) NOT NULL default '',
  `url` varchar(255) NOT NULL default '',
  `contact` varchar(255) NOT NULL default '',
  `location` varchar(255) NOT NULL default '',
  `email` varchar(255) NOT NULL default '',
  `startDate` int(10) unsigned NOT NULL default '0',
  `endDate` int(10) unsigned NOT NULL default '0',
  `author_id` int(11) unsigned NOT NULL default '0',
  PRIMARY KEY  (`eventID`),
  UNIQUE KEY `eventID` (`eventID`)
) TYPE=MyISAM;
# --------------------------------------------------------


# CUPE added an events module - I'll be integrating this
CREATE TABLE `be_eventText` (
  `eventID` smallint(5) NOT NULL default '0',
  `eventTextID` smallint(5) NOT NULL default '0',
  `language` char(3) NOT NULL default '',
  `name` varchar(255) NOT NULL default '',
  `description` text NOT NULL,
  PRIMARY KEY  (`eventTextID`),
  UNIQUE KEY `eventTextID` (`eventTextID`),
  KEY `eventID` (`eventID`),
  KEY `language` (`language`)
) TYPE=MyISAM;
# --------------------------------------------------------

# July 30 - Adding a separate field for the date issued.
ALTER TABLE `psl_blockText` ADD `date_issued` INT( 10 ) ;

INSERT INTO `psl_blockText` VALUES (1, 1, 'fr', 'Administration', '', 'menu_ary=menuadmin&tpl=navbarBlockh', '<!-- START: navbarBlock.tpl -->\n       &nbsp<a href="/profile.php"><b><font size="-1" face="Verdana,Arial,Helvetica,San-Serif">My Preferences</b></a>\n       &nbsp<a href="/admin/blockAdmin.php"><b><font size="-1" face="Verdana,Arial,Helvetica,San-Serif">Bloc</b></a>\n       &nbsp<a href="/admin/pollAdmin.php"><b><font size="-1" face="Verdana,Arial,Helvetica,San-Serif">Vote</b></a>\n       &nbsp<a href="/admin/authorAdmin.php"><b><font size="-1" face="Verdana,Arial,Helvetica,San-Serif">Auteur</b></a>\n       &nbsp<a href="/admin/infologAdmin.php"><b><font size="-1" face="Verdana,Arial,Helvetica,San-Serif">Carnet de bord</b></a>\n       &nbsp<a href="/admin/groupAdmin.php"><b><font size="-1" face="Verdana,Arial,Helvetica,San-Serif">Group</b></a>\n       &nbsp<a href="/admin/BE_sectionAdmin.php"><b><font size="-1" face="Verdana,Arial,Helvetica,San-Serif">Section fr</b></a>\n       &nbsp<a href="/admin/BE_articleAdmin.php"><b><font size="-1" face="Verdana,Arial,Helvetica,San-Serif">Article fr</b></a>\n       &nbsp<a href="/admin/BE_subsiteAdmin.php"><b><font size="-1" face="Verdana,Arial,Helvetica,San-Serif">BE_SubsiteAdmin</b></a>\n       &nbsp<a href="/admin/BE_linkAdmin.php"><b><font size="-1" face="Verdana,Arial,Helvetica,San-Serif">Link-Admin fr</b></a>\n       &nbsp<a href="/admin/BE_galleryAdmin.php"><b><font size="-1" face="Verdana,Arial,Helvetica,San-Serif">Gallery fr</b></a>\n       &nbsp<a href="/admin/BE_contactAdmin.php"><b><font size="-1" face="Verdana,Arial,Helvetica,San-Serif">BE_Contact</b></a>\n<!-- END: navbarBlock.tpl -->\n', 1059581052);
INSERT INTO `psl_blockText` VALUES (2, 152, 'fr', 'Back-End RSS', 'http://www.back-end.org/', 'http://www.back-end.org/backend.php', '{title_block}    <a class="Link3" href="http://www.be.ca" target="_self"><img src="http://www.be.ca/images/BE/BE_logo-100x34.png" title="Back-End on phpSlash" alt="Back-End on phpSlash" border="0"></a>\n    <i>Back-End builds on phpSlash to provide a simple and flexible CMS.</i><br>\n    <li><a class="Link3" target="_self" href="http://www.be.ca/index.php?article=ASiteMap">New Sitemap</a>\n    <br><i>\n      We\'ve added a site map to help your users navigate your site more easily.\n    </i>\n    <li><a class="Link3" target="_self" href="http://www.be.ca/index.php?article=Sidebar">Creating a Mozilla Sidebar with BE</a>\n    <br><i>\n      \n\n\n    </i>\n    <li><a class="Link3" target="_self" href="http://www.be.ca/index.php?article=Comments">Threaded Comments in Articles</a>\n    <br><i>\n      \n    </i>\n{description_block}', 1059580979);
INSERT INTO `psl_blockText` VALUES (3, 150, 'fr', 'Navigation', '', '', '<!-- START OF TEMPLATED DISPLAY STORY BE_sectionLinks.tpl -->\n\n<table>\n<tr>\n   <td>\n	   <FONT SIZE="-1"><a href="/index.php/Tricks"  class="TopLevelSections">FR Back-End 0.5.x Tricks</a></FONT>\n   </td>\n</tr>\n</table>\n\n<!-- END OF TEMPLATED DISPLAY STORY BE_sectionLinks.tpl -->\n\n\n', 1059581052);
INSERT INTO `psl_blockText` VALUES (4, 2, 'fr', 'New Articles', '', '', '<!-- START OF TEMPLATED DISPLAY STORY be_newArticleLinks.tpl -->\n\n<table>\n<tr>\n   <td>\n	<font size="-1"><A HREF="/index.php/7/javaScriptDD" class="BE_newArticles">FR javaScript Drop Downs</A></font>\n   </td>\n</tr>\n<tr>\n   <td>\n	<font size="-1"><A HREF="/index.php/4/BEupgradeTemplates" class="BE_newArticles">FR Templates (why are there so many of them)</A></font>\n   </td>\n</tr>\n<tr>\n   <td>\n	<font size="-1"><A HREF="/index.php/7/wlp" class="BE_newArticles">FR Mirroring & then replacing static .html structure</A></font>\n   </td>\n</tr>\n</table>\n\n<!-- END OF TEMPLATED DISPLAY STORY be_newArticleLinks.tpl -->\n', 1059581006);
INSERT INTO `psl_blockText` VALUES (5, 10, 'fr', 'Poll', '', '', '<!-- START: pollDisplay.tpl -->\n      <form action="/poll.php">\n      <input type=hidden name=question_id value="4">\n      <input type=hidden name=submit      value="vote">\n      <FONT SIZE=-1 face="arial,helvetica">\n        <b>What Tools would you like to see in the next release?</b><BR>\n        <input type=radio name=answer_id value=0>Versioning <BR>\n        <input type=radio name=answer_id value=1>WYSIWYG Interface <BR>\n        <input type=radio name=answer_id value=2>Page Caching <BR>\n        <input type=radio name=answer_id value=3>Better Document Handling <BR>\n        <input type=radio name=answer_id value=4>Members Areas <BR>\n        <input type=submit value=Vote> \n        [ <a href="/poll.php?submit=viewresults&question_id=4">Results</a> \n        | <a href="/poll.php">Polls</a> ]\n        <br>Comments: <b>0</b> | Votes: <b>0</b>\n      </FONT>\n      </form>\n<!-- END: pollDisplay.tpl -->\n', 1059581052);
INSERT INTO `psl_blockText` VALUES (6, 151, 'fr', 'Random Links', '', '', '<!-- start be_linkSidebar.tpl -->\n<table>\n<tr>\n   <td>\n	   <FONT SIZE="-1"><A HREF="http://www.back-end.org" class="sidebarLink" TARGET="_blank">Back-End</A> </FONT>\n   </td>\n</tr>\n<tr>\n   <td>\n	   <FONT SIZE="-1"><A HREF="http://www.openconcept.ca" class="sidebarLink" TARGET="_blank">OpenConcept</A> </FONT>\n   </td>\n</tr>\n<tr>\n   <td>\n	   <FONT SIZE="-1"><A HREF="http://www.phpslash.org" class="sidebarLink" TARGET="_blank">phpSlash</A> </FONT>\n   </td>\n</tr>\n<tr>\n   <td>\n	   <FONT SIZE="-1"><A HREF="http://www.sf.net" class="sidebarLink" TARGET="_blank">SourceForge</A> </FONT>\n   </td>\n</tr>\n</table>\n<!-- end be_linkSidebar.tpl -->\n', 1059581052);
INSERT INTO `psl_blockText` VALUES (7, 4, 'fr', 'Related Articles', '', '', '', 1059581043);
INSERT INTO `psl_blockText` VALUES (8, 5, 'fr', 'Spotlight Articles', '', '', '<!-- START OF TEMPLATED DISPLAY STORY be_spotlightLinks.tpl -->\n\n<table>\n</table>\n\n<!-- END OF TEMPLATED DISPLAY STORY be_spotlightLinks.tpl -->\n\n\n', 1059581051);
INSERT INTO `psl_blockText` VALUES (9, 1, 'en', 'Administration', '', 'menu_ary=menuadmin&tpl=navbarBlockh', '<!-- START: navbarBlock.tpl -->\n       &nbsp<a href="/profile.php"><b><font size="-1" face="Verdana,Arial,Helvetica,San-Serif">My Preferences</b></a>\n       &nbsp<a href="/admin/blockAdmin.php"><b><font size="-1" face="Verdana,Arial,Helvetica,San-Serif">Block</b></a>\n       &nbsp<a href="/admin/pollAdmin.php"><b><font size="-1" face="Verdana,Arial,Helvetica,San-Serif">Poll</b></a>\n       &nbsp<a href="/admin/authorAdmin.php"><b><font size="-1" face="Verdana,Arial,Helvetica,San-Serif">Author</b></a>\n       &nbsp<a href="/admin/infologAdmin.php"><b><font size="-1" face="Verdana,Arial,Helvetica,San-Serif">Logging</b></a>\n       &nbsp<a href="/admin/groupAdmin.php"><b><font size="-1" face="Verdana,Arial,Helvetica,San-Serif">Group</b></a>\n       &nbsp<a href="/admin/BE_sectionAdmin.php"><b><font size="-1" face="Verdana,Arial,Helvetica,San-Serif">Section</b></a>\n       &nbsp<a href="/admin/BE_articleAdmin.php"><b><font size="-1" face="Verdana,Arial,Helvetica,San-Serif">Article</b></a>\n       &nbsp<a href="/admin/BE_subsiteAdmin.php"><b><font size="-1" face="Verdana,Arial,Helvetica,San-Serif">Subsites</b></a>\n       &nbsp<a href="/admin/BE_linkAdmin.php"><b><font size="-1" face="Verdana,Arial,Helvetica,San-Serif">Links</b></a>\n       &nbsp<a href="/admin/BE_galleryAdmin.php"><b><font size="-1" face="Verdana,Arial,Helvetica,San-Serif">Gallery</b></a>\n       &nbsp<a href="/admin/BE_contactAdmin.php"><b><font size="-1" face="Verdana,Arial,Helvetica,San-Serif">Contacts</b></a>\n<!-- END: navbarBlock.tpl -->\n', 1059581207);
INSERT INTO `psl_blockText` VALUES (10, 152, 'en', 'Back-End RSS', 'http://www.back-end.org/', 'http://www.back-end.org/backend.php', '{title_block}    <a class="Link3" href="http://www.be.ca" target="_self"><img src="http://www.be.ca/images/BE/BE_logo-100x34.png" title="Back-End on phpSlash" alt="Back-End on phpSlash" border="0"></a>\n    <i>Back-End builds on phpSlash to provide a simple and flexible CMS.</i><br>\n    <li><a class="Link3" target="_self" href="http://www.be.ca/index.php?article=ASiteMap">New Sitemap</a>\n    <br><i>\n      We\'ve added a site map to help your users navigate your site more easily.\n    </i>\n    <li><a class="Link3" target="_self" href="http://www.be.ca/index.php?article=Sidebar">Creating a Mozilla Sidebar with BE</a>\n    <br><i>\n      \n\n\n    </i>\n    <li><a class="Link3" target="_self" href="http://www.be.ca/index.php?article=Comments">Threaded Comments in Articles</a>\n    <br><i>\n      \n    </i>\n{description_block}', 1059581127);
INSERT INTO `psl_blockText` VALUES (11, 150, 'en', 'Navigation', '', '', '<!-- START OF TEMPLATED DISPLAY STORY BE_sectionLinks.tpl -->\n\n<table>\n<tr>\n   <td>\n	   <FONT SIZE="-1"><a href="/index.php/Home"  class="TopLevelSections">Home</a></FONT>\n   </td>\n</tr>\n<tr>\n   <td>\n	   <FONT SIZE="-1"><a href="/index.php/Tricks"  class="TopLevelSections">Back-End 0.5.x Tricks</a></FONT>\n   </td>\n</tr>\n<tr>\n   <td>\n	   <FONT SIZE="-1"><a href="/index.php/Features"  class="TopLevelSections">Hilighted Features</a></FONT>\n   </td>\n</tr>\n</table>\n\n<!-- END OF TEMPLATED DISPLAY STORY BE_sectionLinks.tpl -->\n\n\n', 1059581207);
INSERT INTO `psl_blockText` VALUES (12, 2, 'en', 'New Articles', '', '', '<!-- START OF TEMPLATED DISPLAY STORY be_newArticleLinks.tpl -->\n\n<table>\n<tr>\n   <td>\n	<font size="-1"><A HREF="/index.php/7/javaScriptDD" class="BE_newArticles">javaScript Drop Downs</A></font>\n   </td>\n</tr>\n<tr>\n   <td>\n	<font size="-1"><A HREF="/index.php/4/BEupgradeTemplates" class="BE_newArticles">Templates (why are there so many of them)</A></font>\n   </td>\n</tr>\n<tr>\n   <td>\n	<font size="-1"><A HREF="/index.php/7/wlp" class="BE_newArticles">Mirroring & then replacing static .html structure</A></font>\n   </td>\n</tr>\n</table>\n\n<!-- END OF TEMPLATED DISPLAY STORY be_newArticleLinks.tpl -->\n', 1059581164);
INSERT INTO `psl_blockText` VALUES (13, 10, 'en', 'Poll', '', '', '<!-- START: pollDisplay.tpl -->\n      <form action="/poll.php">\n      <input type=hidden name=question_id value="4">\n      <input type=hidden name=submit      value="vote">\n      <FONT SIZE=-1 face="arial,helvetica">\n        <b>What Tools would you like to see in the next release?</b><BR>\n        <input type=radio name=answer_id value=0>Versioning <BR>\n        <input type=radio name=answer_id value=1>WYSIWYG Interface <BR>\n        <input type=radio name=answer_id value=2>Page Caching <BR>\n        <input type=radio name=answer_id value=3>Better Document Handling <BR>\n        <input type=radio name=answer_id value=4>Members Areas <BR>\n        <input type=submit value=Vote> \n        [ <a href="/poll.php?submit=viewresults&question_id=4">Results</a> \n        | <a href="/poll.php">Polls</a> ]\n        <br>Comments: <b>0</b> | Votes: <b>0</b>\n      </FONT>\n      </form>\n<!-- END: pollDisplay.tpl -->\n', 1059581202);
INSERT INTO `psl_blockText` VALUES (14, 151, 'en', 'Random Links', '', '', '<!-- start be_linkSidebar.tpl -->\n<table>\n<tr>\n   <td>\n	   <FONT SIZE="-1"><A HREF="http://www.back-end.org" class="sidebarLink" TARGET="_blank">Back-End</A> </FONT>\n   </td>\n</tr>\n<tr>\n   <td>\n	   <FONT SIZE="-1"><A HREF="http://www.openconcept.ca" class="sidebarLink" TARGET="_blank">OpenConcept</A> </FONT>\n   </td>\n</tr>\n<tr>\n   <td>\n	   <FONT SIZE="-1"><A HREF="http://www.phpslash.org" class="sidebarLink" TARGET="_blank">phpSlash</A> </FONT>\n   </td>\n</tr>\n<tr>\n   <td>\n	   <FONT SIZE="-1"><A HREF="http://www.sf.net" class="sidebarLink" TARGET="_blank">SourceForge</A> </FONT>\n   </td>\n</tr>\n</table>\n<!-- end be_linkSidebar.tpl -->\n', 1059581202);
INSERT INTO `psl_blockText` VALUES (15, 4, 'en', 'Related Articles', '', '', '', 1059581194);
INSERT INTO `psl_blockText` VALUES (16, 5, 'en', 'Spotlight Articles', '', '', '<!-- START OF TEMPLATED DISPLAY STORY be_spotlightLinks.tpl -->\n\n<table>\n</table>\n\n<!-- END OF TEMPLATED DISPLAY STORY be_spotlightLinks.tpl -->\n\n\n', 1059581202);


# August 27 - Adding restriction fields to gallery - mg
ALTER TABLE `be_images` CHANGE `hide` `hide` TINYINT( 2 ) DEFAULT '0' NOT NULL; 
ALTER TABLE `be_images` CHANGE `restrict2members` `restrict2members` INT( 5 ) DEFAULT '0' NOT NULL; 

ALTER TABLE `be_images` CHANGE `dateCreated` `dateCreated` INT( 10 ) UNSIGNED DEFAULT '0' NOT NULL;
ALTER TABLE `be_images` CHANGE `dateModified` `dateModified` INT( 10 ) UNSIGNED DEFAULT '0' NOT NULL;
ALTER TABLE `be_images` CHANGE `dateAvailable` `dateAvailable` INT( 10 ) UNSIGNED DEFAULT '0' NOT NULL;
ALTER TABLE `be_images` CHANGE `dateRemoved` `dateRemoved` INT( 10 ) UNSIGNED DEFAULT '0' NOT NULL;

ALTER TABLE `be_articles` CHANGE `restrict2members` `restrict2members` INT( 5 ) DEFAULT '0' NOT NULL;

ALTER TABLE `be_sections` CHANGE `restrict2members` `restrict2members` INT( 5 ) DEFAULT '0' NOT NULL;
ALTER TABLE `be_sections` CHANGE `hide` `hide` TINYINT( 2 ) DEFAULT '0' NOT NULL;

# If you use the actions uncomment the following
# ALTER TABLE `be_contact` ADD `followup` tinyint(5) DEFAULT '0' NOT NULL ;
# ALTER TABLE `be_action2contact` CHANGE `followup` `followup` TINYINT( 5 ) DEFAULT '0' NOT NULL; 
# ALTER TABLE `pet_petition2contact` CHANGE `followup` `followup` TINYINT( 5 ) DEFAULT '0' NOT NULL; 

# October 3 - adding orderby fields to sections as well as dateForSort to articles & sections - mg
ALTER TABLE `be_articles` ADD `dateForSort` INT( 10 ) UNSIGNED DEFAULT '0' NOT NULL AFTER `dateRemoved` ;
ALTER TABLE `be_sections` ADD `dateForSort` INT( 10 ) UNSIGNED DEFAULT '0' NOT NULL AFTER `dateRemoved` ;
ALTER TABLE `be_sections` ADD `orderby` VARCHAR( 30 ) DEFAULT 'dateCreated' NOT NULL ;
ALTER TABLE `be_sections` ADD `ascdesc` CHAR( 4 ) DEFAULT 'DESC' NOT NULL ;

# Not needed if actions/petitions not used
# ALTER TABLE `be_contact` CHANGE `followup` `followupGlobal` TINYINT( 5 ) DEFAULT '0' NOT NULL;

UPDATE psl_variable SET value="0.7.0" WHERE variable_id='100';


