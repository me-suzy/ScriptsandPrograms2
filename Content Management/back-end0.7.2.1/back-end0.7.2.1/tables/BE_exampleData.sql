# $Id: BE_exampleData.sql,v 1.50 2005/06/19 10:39:10 krabu Exp $
#
# Dumping data for table `be_article2section`
#

# Clear previous entries
DELETE FROM be_article2section;

INSERT INTO be_article2section (`articleID`, `sectionID`) VALUES (7, 7);
INSERT INTO be_article2section (`articleID`, `sectionID`) VALUES (4, 4);
INSERT INTO be_article2section (`articleID`, `sectionID`) VALUES (6, 7);

# --------------------------------------------------------


#
# Dumping data for table `be_articleText`
#

# Clear previous entries
DELETE FROM `be_articleText`;

INSERT INTO be_articleText VALUES (4, 4, 'en', 'BEupgradeTemplates', 'Templates (why are there so many of them)', '', 'BE0.4.x used a single template to define a whole page.  BE0.5.x uses three (esentially). slashHead.tpl, BE_body.tpl and slashFoot.tpl.  It&#039;s more complicated than this as phpSlash&#039;s blocks allow you to add left or right had columns all through the web form.  It does make it more complicated to set up, however it becomes a lot more powerful and easy to maintain. The template option in the admin web form allows you to specify a new look for a specific page or article.  If you created a template &#039;newlook&#039; it would look for slashHead-newlook.tpl, BE_body-newlook.tpl and slashFoot-newlook.tpl.  If it didn&#039;t find one of them, it would just use the default (ie. slashHead.tpl, BE_body.tpl or slashFoot.tpl).  If you want to add threading to most web sites you could do this pretty easily by just adding a slashHead-thread1.tpl.\r\n\r\n<p>To upgrade your templates from .4.x the critical piece is to know where to splice them.  You would do this much as if you had a simple header.inc or footer.inc file.  if you had a template which you had called &#039;home.tpl&#039; that you wanted to use on the home page of BE5 you could split it into slashHead-home.tpl.tpl, BE_body-home.tpl.tpl and slashFoot-home.tpl.tpl.  The double &#039;.tpl&#039; would be required unless you change the template name to &#039;home&#039; in your database.\r\n\r\n</p><p>Best place to look for what BE5 is doing with the templates is in the source.  Viewing the source will show you which templates are being called to generate a given page.</p>', '', '', '', 0, '0', '2', '', 0, 1, unix_timestamp(now()));
INSERT INTO be_articleText VALUES (6, 6, 'en', 'wlp', 'Mirroring and then replacing static .html structure', '', 'If you have a static .html site.  Moving to a CMS often has disincentives if you have to change the URLs that your visitors have grown to know.\r\n\r\n<p>With the new Back-End you can avoid this by mirroring the given structure with .phtml files which pass along the critical Article/Section information.  This also allows you to provide multi-lingual content in the same structure as the static site.\r\n\r\n</p><p>Check out <a href="http://www.learningpartnership.org" target="_blank">WLP</a></p>', '', '', '', 0, '0', '3', '', 0, 2, unix_timestamp(now()));
INSERT INTO be_articleText VALUES (7, 7, 'en', 'javaScriptDD', 'javaScript Drop Downs', '', 'If you want to use JavaScript Dropdowns to build better navigation for your site, BE5 allows you to insert dynamic dropdowns with your site&#039;s main sections and sub-sections.\r\n\r\n<p>Check out <a href="http://www.billblaikie.org" target="_blank">billblaikie.org</a></p>', '', '', '', 0, '0', '4', '', 0, 3, unix_timestamp(now()));
INSERT INTO be_articleText VALUES (8, 4, 'fr', 'BEupgradeTemplates', 'FR Templates (why are there so many of them)', '', 'BE0.4.x used a single template to define a whole page.  BE0.5.x uses three (esentially). slashHead.tpl, BE_body.tpl and slashFoot.tpl.  It&#039;s more complicated than this as phpSlash&#039;s blocks allow you to add left or right had columns all through the web form.  It does make it more complicated to set up, however it becomes a lot more powerful and easy to maintain. The template option in the admin web form allows you to specify a new look for a specific page or article.  If you created a template &#039;newlook&#039; it would look for slashHead-newlook.tpl, BE_body-newlook.tpl and slashFoot-newlook.tpl.  If it didn&#039;t find one of them, it would just use the default (ie. slashHead.tpl, BE_body.tpl or slashFoot.tpl).  If you want to add threading to most web sites you could do this pretty easily by just adding a slashHead-thread1.tpl.\r\n\r\n<p>To upgrade your templates from .4.x the critical piece is to know where to splice them.  You would do this much as if you had a simple header.inc or footer.inc file.  if you had a template which you had called &#039;home.tpl&#039; that you wanted to use on the home page of BE5 you could split it into slashHead-home.tpl.tpl, BE_body-home.tpl.tpl and slashFoot-home.tpl.tpl.  The double &#039;.tpl&#039; would be required unless you change the template name to &#039;home&#039; in your database.\r\n\r\n</p><p>Best place to look for what BE5 is doing with the templates is in the source.  Viewing the source will show you which templates are being called to generate a given page.</p>', '', '', '', 0, '0', '2', '', 0, 4, unix_timestamp(now()));
INSERT INTO be_articleText VALUES (9, 6, 'fr', 'wlp', 'FR Mirroring and then replacing static .html structure', '', 'If you have a static .html site.  Moving to a CMS often has disincentives if you have to change the URLs that your visitors have grown to know.\r\n\r\n<p>With the new Back-End you can avoid this by mirroring the given structure with .phtml files which pass along the critical Article/Section information.  This also allows you to provide multi-lingual content in the same structure as the static site.\r\n\r\n</p><p>Check out <a href="http://www.learningpartnership.org" target="_blank">WLP</a></p>', '', '', '', 0, '0', '3', '', 0, 5, unix_timestamp(now()));
INSERT INTO be_articleText VALUES (10, 7, 'fr', 'javaScriptDD', 'FR javaScript Drop Downs', '', 'If you want to use JavaScript Dropdowns to build better navigation for your site, BE5 allows you to insert dynamic dropdowns with your site&#039;s main sections and sub-sections.\r\n\r\n<p>Check out <a href="http://www.billblaikie.org" target="_blank">billblaikie.org</a></p>', '', '', '', 0, '0', '4', '', 0, 6, unix_timestamp(now()));


# increment commentID and ensure it isn't set to 0
# UPDATE `be_articleText` SET `commentIDtext` = '1' WHERE `articleTextID` = '10';
# UPDATE `be_articleText` SET `commentIDtext` = '2' WHERE `articleTextID` = '9';
# UPDATE `be_articleText` SET `commentIDtext` = '3' WHERE `articleTextID` = '8';
# UPDATE `be_articleText` SET `commentIDtext` = '4' WHERE `articleTextID` = '7';
# UPDATE `be_articleText` SET `commentIDtext` = '5' WHERE `articleTextID` = '6';
# UPDATE `be_articleText` SET `commentIDtext` = '6' WHERE `articleTextID` = '4';
# UPDATE `be_articles` SET `subsiteID` = NULL ,
#`commentID` = '7' WHERE `articleID` = '7' AND `URLname` = 'javaScriptDD';
# UPDATE `be_articles` SET `subsiteID` = NULL ,
#`commentID` = '8' WHERE `articleID` = '6' AND `URLname` = 'wlp';
# UPDATE `be_articles` SET `subsiteID` = NULL ,
#`commentID` = '9' WHERE `articleID` = '4' AND `URLname` = 'BEupgradeTemplates';

# --------------------------------------------------------


#
# Dumping data for table `be_articles`
#

# Clear previous entries
DELETE FROM `be_articles`;

INSERT INTO `be_articles` (`articleID`, `URLname`, `author_id`, `dateCreated`, `dateModified`, `dateAvailable`, `dateRemoved`, `dateForSort`, `content_type`, `hide`, `restrict2members`, `spotlight`, `showPrint`, `useIcons`, `hitCounter`, `priority`, `commentID`) VALUES (4, 'BEupgradeTemplates', 1, 1041835651, 1041835651, 1041835651, 1830775651, 0, '', 0, 1, 0, 0, 0, 0, 5, 0);
INSERT INTO `be_articles` (`articleID`, `URLname`, `author_id`, `dateCreated`, `dateModified`, `dateAvailable`, `dateRemoved`, `dateForSort`, `content_type`, `hide`, `restrict2members`, `spotlight`, `showPrint`, `useIcons`, `hitCounter`, `priority`, `commentID`) VALUES (6, 'wlp', 1, 1041835651, 1041835651, 1041835651, 1830775651, 0, '', 0, 1, 0, 0, 0, 0, 6, 0);
INSERT INTO `be_articles` (`articleID`, `URLname`, `author_id`, `dateCreated`, `dateModified`, `dateAvailable`, `dateRemoved`, `dateForSort`, `content_type`, `hide`, `restrict2members`, `spotlight`, `showPrint`, `useIcons`, `hitCounter`, `priority`, `commentID`) VALUES (7, 'javaScriptDD', 1, 1041835651, 1041835651, 1041835651, 1830775651, 0, '', 0, 0, 0, 0, 0, 0, 7, 0);

# --------------------------------------------------------


#
# Dumping data for table `be_link`
#

# Clear previous entries
DELETE FROM `be_link`;

INSERT INTO `be_link` (`linkID`, `url`, `author_id`, `dateCreated`, `dateModified`, `dateAvailable`, `dateRemoved`, `content_type`, `hide`, `restrict2members`, `hitCounter`, `priority`) VALUES (1, 'http://www.back-end.org', 1, 1041835651, 1041835651, 1041835651, 1830775651, '', 0, 0, 0, 0);
INSERT INTO `be_link` (`linkID`, `url`, `author_id`, `dateCreated`, `dateModified`, `dateAvailable`, `dateRemoved`, `content_type`, `hide`, `restrict2members`, `hitCounter`, `priority`) VALUES (2, 'http://www.openconcept.ca', 1, 1041835651, 1041835651, 1041835651, 1830775651, '', 0, 0, 0, 0);
INSERT INTO `be_link` (`linkID`, `url`, `author_id`, `dateCreated`, `dateModified`, `dateAvailable`, `dateRemoved`, `content_type`, `hide`, `restrict2members`, `hitCounter`, `priority`) VALUES (3, 'http://phpslash.sf.net', 1, 1041835651, 1041835651, 1041835651, 1830775651, '', 0, 0, 0, 0);
INSERT INTO `be_link` (`linkID`, `url`, `author_id`, `dateCreated`, `dateModified`, `dateAvailable`, `dateRemoved`, `content_type`, `hide`, `restrict2members`, `hitCounter`, `priority`) VALUES (4, 'http://www.sf.net', 1, 1041835651, 1041835651, 1041835651, 1830775651, '', 0, 0, 0, 0);
INSERT INTO `be_link` (`linkID`, `url`, `author_id`, `dateCreated`, `dateModified`, `dateAvailable`, `dateRemoved`, `content_type`, `hide`, `restrict2members`, `hitCounter`, `priority`) VALUES (6, 'http://www.billblaikie.ca', 1, 1065326400, 1065346096, 1065326400, 0, '', 0, 0, 0, 0);
INSERT INTO `be_link` (`linkID`, `url`, `author_id`, `dateCreated`, `dateModified`, `dateAvailable`, `dateRemoved`, `content_type`, `hide`, `restrict2members`, `hitCounter`, `priority`) VALUES (8, 'http://www.pgs.ca/', 1, 1065326400, 1065346199, 1065326400, 0, '', 0, 0, 0, 0);
INSERT INTO `be_link` (`linkID`, `url`, `author_id`, `dateCreated`, `dateModified`, `dateAvailable`, `dateRemoved`, `content_type`, `hide`, `restrict2members`, `hitCounter`, `priority`) VALUES (9, 'http://www.cupe.ca/', 1, 1065326400, 1065346266, 1065326400, 0, '', 0, 0, 0, 0);
INSERT INTO `be_link` (`linkID`, `url`, `author_id`, `dateCreated`, `dateModified`, `dateAvailable`, `dateRemoved`, `content_type`, `hide`, `restrict2members`, `hitCounter`, `priority`) VALUES (10, 'http://www.fairvotecanada.org/', 1, 1065326400, 1065346372, 1065326400, 0, '', 0, 0, 0, 0);
INSERT INTO `be_link` (`linkID`, `url`, `author_id`, `dateCreated`, `dateModified`, `dateAvailable`, `dateRemoved`, `content_type`, `hide`, `restrict2members`, `hitCounter`, `priority`) VALUES (14, 'http://www.openconcept.ca/be_wiki/', 39, 1069056000, 1069070529, 1069056000, 0, '', 0, 0, 0, 90);
INSERT INTO `be_link` (`linkID`, `url`, `author_id`, `dateCreated`, `dateModified`, `dateAvailable`, `dateRemoved`, `content_type`, `hide`, `restrict2members`, `hitCounter`, `priority`) VALUES (15, 'http://cvs-demo.back-end.org/', 39, 1069056000, 1069071667, 1069056000, 0, '', 0, 0, 0, 0);
INSERT INTO be_link VALUES (16, 'http://www.calgaryblizzard.com/', 1, 1087790400, 1087872005, 1087790400, 0, '', 0, 0, 0, 0);
INSERT INTO be_link VALUES (18, 'http://www.brianmasse.ca/', 1, 1087790400, 1087872183, 1087790400, 0, '', 0, 0, 0, 0);
INSERT INTO be_link VALUES (19, 'http://learningpartnership.org/', 1, 1087790400, 1087872260, 1087790400, 0, '', 0, 0, 0, 0);
INSERT INTO be_link VALUES (20, 'http://www.genderatwork.org/index.html', 1, 1087790400, 1087872312, 1087790400, 0, '', 0, 0, 0, 0);
INSERT INTO be_link VALUES (22, 'http://www.oxnet.org/', 1, 1087790400, 1087872452, 1087790400, 0, '', 0, 0, 0, 0);

# --------------------------------------------------------


#
# Dumping data for table `be_linkText`
#

# Clear previous entries
DELETE FROM `be_linkText`;

INSERT INTO `be_linkText` (`linkTextID`, `linkID`, `languageID`, `title`, `url`, `description`, `description_source`, `title_source`, `originalText`) VALUES (1, 1, 'en', 'Back-End', '', 'This web application', '', '', 0);
INSERT INTO `be_linkText` (`linkTextID`, `linkID`, `languageID`, `title`, `url`, `description`, `description_source`, `title_source`, `originalText`) VALUES (2, 2, 'en', 'OpenConcept', '', 'Primary Developers', '', '', 0);
INSERT INTO `be_linkText` (`linkTextID`, `linkID`, `languageID`, `title`, `url`, `description`, `description_source`, `title_source`, `originalText`) VALUES (3, 3, 'en', 'phpSlash', '', 'A flexible web-logging system', '', '', 0);
INSERT INTO `be_linkText` (`linkTextID`, `linkID`, `languageID`, `title`, `url`, `description`, `description_source`, `title_source`, `originalText`) VALUES (4, 4, 'en', 'SourceForge', '', 'The development area...', '', '', 0);
INSERT INTO `be_linkText` (`linkTextID`, `linkID`, `languageID`, `title`, `url`, `description`, `description_source`, `title_source`, `originalText`) VALUES (5, 1, 'fr', 'Back-End', '', 'This web application', '', '', 0);
INSERT INTO `be_linkText` (`linkTextID`, `linkID`, `languageID`, `title`, `url`, `description`, `description_source`, `title_source`, `originalText`) VALUES (6, 2, 'fr', 'OpenConcept', '', 'Primary Developers', '', '', 0);
INSERT INTO `be_linkText` (`linkTextID`, `linkID`, `languageID`, `title`, `url`, `description`, `description_source`, `title_source`, `originalText`) VALUES (7, 3, 'fr', 'phpSlash', '', 'A flexible web-logging system', '', '', 0);
INSERT INTO `be_linkText` (`linkTextID`, `linkID`, `languageID`, `title`, `url`, `description`, `description_source`, `title_source`, `originalText`) VALUES (8, 4, 'fr', 'SourceForge', '', 'The development area...', '', '', 0);
INSERT INTO `be_linkText` (`linkTextID`, `linkID`, `languageID`, `title`, `url`, `description`, `description_source`, `title_source`, `originalText`) VALUES (9, 6, 'en', 'Bill Blaikie, MP', '', 'Canadian NDP MP', NULL, '', 0);
INSERT INTO `be_linkText` (`linkTextID`, `linkID`, `languageID`, `title`, `url`, `description`, `description_source`, `title_source`, `originalText`) VALUES (11, 8, 'en', 'Physicians for Global Survival', '', ' Physicians for Global Survival (PGS) is a physician-led organization which, out of concern for global health, is committed to the abolition of nuclear weapons, the prevention of war, the promotion of non-violent means of conflict resolution and social justice in a sustainable world.', NULL, '', 0);
INSERT INTO `be_linkText` (`linkTextID`, `linkID`, `languageID`, `title`, `url`, `description`, `description_source`, `title_source`, `originalText`) VALUES (12, 9, 'en', 'CUPE National', '', 'The Canadian Union of Public Employees (CUPE) is Canada\'s largest union. ', NULL, '', 0);
INSERT INTO `be_linkText` (`linkTextID`, `linkID`, `languageID`, `title`, `url`, `description`, `description_source`, `title_source`, `originalText`) VALUES (13, 10, 'en', 'Fair Vote Canada', '', 'Fair Vote Canada (FVC) is a multi-partisan citizens\' campaign for voting system reform. ', NULL, '', 0);
INSERT INTO `be_linkText` (`linkTextID`, `linkID`, `languageID`, `title`, `url`, `description`, `description_source`, `title_source`, `originalText`) VALUES (15, 12, 'en', 'United Church of Canada&#039;s Beads of Hope Campaign', '', 'Petition to address the global HIV/AIDS pandemic.', NULL, '', 0);
INSERT INTO `be_linkText` (`linkTextID`, `linkID`, `languageID`, `title`, `url`, `description`, `description_source`, `title_source`, `originalText`) VALUES (16, 14, 'en', 'Developers Wiki', '', 'A resource for all developers to record their thought, ideas, and keep track of where the project is going', NULL, '', 0);
INSERT INTO `be_linkText` (`linkTextID`, `linkID`, `languageID`, `title`, `url`, `description`, `description_source`, `title_source`, `originalText`) VALUES (17, 15, 'en', 'Demo site', '', 'Demonstration site - rebuilt from the current CVS daily. It&#039;s generally available for you to have a play, log in as &#039;root&#039;, with password &#039;back-end&#039;.', NULL, '', 0);
INSERT INTO be_linkText VALUES (18, 16, 'en', 'Calgary Blizzard', '', '', NULL, '', 0);
INSERT INTO be_linkText VALUES (20, 18, 'en', 'Brian Masse, MP', '', '', NULL, '', 0);
INSERT INTO be_linkText VALUES (21, 19, 'en', 'Women&#039;s Learning Partnership', '', '', NULL, '', 0);
INSERT INTO be_linkText VALUES (22, 20, 'en', 'Gender At Work', '', '', NULL, '', 0);
INSERT INTO be_linkText VALUES (24, 22, 'en', 'Oxfam Canada&#039;s Volunteer Network (Oxnet)', '', '', NULL, '', 0);


# --------------------------------------------------------


#
# Dumping data for table `be_link2section`
#

# Clear previous entries
DELETE FROM `be_link2section`;

INSERT INTO `be_link2section` (`linkID`, `sectionID`) VALUES (1, 1);
INSERT INTO `be_link2section` (`linkID`, `sectionID`) VALUES (2, 1);
INSERT INTO `be_link2section` (`linkID`, `sectionID`) VALUES (3, 1);
INSERT INTO `be_link2section` (`linkID`, `sectionID`) VALUES (4, 1);
INSERT INTO `be_link2section` (`linkID`, `sectionID`) VALUES (6, 12);
INSERT INTO `be_link2section` (`linkID`, `sectionID`) VALUES (7, 12);
INSERT INTO `be_link2section` (`linkID`, `sectionID`) VALUES (8, 12);
INSERT INTO `be_link2section` (`linkID`, `sectionID`) VALUES (9, 12);
INSERT INTO `be_link2section` (`linkID`, `sectionID`) VALUES (10, 12);
INSERT INTO `be_link2section` (`linkID`, `sectionID`) VALUES (11, 12);
INSERT INTO `be_link2section` (`linkID`, `sectionID`) VALUES (12, 12);
INSERT INTO `be_link2section` (`linkID`, `sectionID`) VALUES (13, 12);
INSERT INTO `be_link2section` (`linkID`, `sectionID`) VALUES (14, 11);
INSERT INTO `be_link2section` (`linkID`, `sectionID`) VALUES (15, 11);
INSERT INTO `be_link2section` (`linkID`, `sectionID`)  VALUES (16, 12);
INSERT INTO `be_link2section` (`linkID`, `sectionID`)  VALUES (17, 12);
INSERT INTO `be_link2section` (`linkID`, `sectionID`)  VALUES (18, 12);
INSERT INTO `be_link2section` (`linkID`, `sectionID`)  VALUES (19, 12);
INSERT INTO `be_link2section` (`linkID`, `sectionID`)  VALUES (20, 12);
INSERT INTO `be_link2section` (`linkID`, `sectionID`)  VALUES (21, 12);
INSERT INTO `be_link2section` (`linkID`, `sectionID`)  VALUES (22, 12);

# --------------------------------------------------------


#
# Dumping data for table `be_section2section`
#

# Clear previous entries
DELETE FROM `be_section2section`;

INSERT INTO `be_section2section` (`parentSectionID`, `childSectionID`) VALUES (0, 1);
INSERT INTO `be_section2section` (`parentSectionID`, `childSectionID`) VALUES (1, 2);
INSERT INTO `be_section2section` (`parentSectionID`, `childSectionID`) VALUES (1, 3);
INSERT INTO `be_section2section` (`parentSectionID`, `childSectionID`) VALUES (5, 4);
INSERT INTO `be_section2section` (`parentSectionID`, `childSectionID`) VALUES (11, 5);
INSERT INTO `be_section2section` (`parentSectionID`, `childSectionID`) VALUES (5, 6);
INSERT INTO `be_section2section` (`parentSectionID`, `childSectionID`) VALUES (0, 11);
INSERT INTO `be_section2section` (`parentSectionID`, `childSectionID`) VALUES (0, 12);
INSERT INTO `be_section2section` (`parentSectionID`, `childSectionID`) VALUES (12, 13);
INSERT INTO `be_section2section` (`parentSectionID`, `childSectionID`) VALUES (12, 14);
INSERT INTO `be_section2section` (`parentSectionID`, `childSectionID`) VALUES (12, 15);
INSERT INTO `be_section2section` (`parentSectionID`, `childSectionID`) VALUES (12, 16);
INSERT INTO `be_section2section` (`parentSectionID`, `childSectionID`) VALUES (12, 17);
INSERT INTO `be_section2section` (`parentSectionID`, `childSectionID`) VALUES (12, 18);
INSERT INTO `be_section2section` (`parentSectionID`, `childSectionID`) VALUES (5, 19);
INSERT INTO `be_section2section` (`parentSectionID`, `childSectionID`) VALUES (12, 20);
INSERT INTO `be_section2section` (`parentSectionID`, `childSectionID`) VALUES (15, 21);
INSERT INTO `be_section2section` (`parentSectionID`, `childSectionID`) VALUES (15, 22);
INSERT INTO `be_section2section` (`parentSectionID`, `childSectionID`) VALUES (15, 23);
INSERT INTO `be_section2section` (`parentSectionID`, `childSectionID`) VALUES (11, 24);
INSERT INTO `be_section2section` (`parentSectionID`, `childSectionID`) VALUES (5, 25);
INSERT INTO `be_section2section` (`parentSectionID`, `childSectionID`) VALUES (5, 26);
INSERT INTO `be_section2section` (`parentSectionID`, `childSectionID`) VALUES (5, 27);
INSERT INTO `be_section2section` (`parentSectionID`, `childSectionID`) VALUES (5, 28);
INSERT INTO `be_section2section` (`parentSectionID`, `childSectionID`) VALUES (5, 29);

# --------------------------------------------------------


#
# Dumping data for table `be_sectionText`
#

# Clear previous entries
DELETE FROM `be_sectionText`;


INSERT INTO be_sectionText VALUES (1, 1, 'en', 'Home', 'Home', 'Introduction to Back-End', '\r\n<p><strong>Back-End</strong>  allows even the non-technical to manage  any website easily, through a web browser, on any operating system. Fast, flexible and easy to understand, <strong>Back-End</strong> puts you in charge of your site, saving your organisation time and money in the process.<br /></p><p>We have released <a href="http://sourceforge.net/project/showfiles.php?group_id=6763">Back-End version 0.7.2</a> which is easy to install and packed with new features including the ability to undelete sections and articles.  The latest release is also working to be fully xhtml compliant.  <br /></p>\r\n<p><strong>Back-End</strong> is particularly suitable for advocacy organisations, with a suite of tools that allow you to create and manage polls and petitions, and to interact with your visitors, making it faster and easier for you to respond to issues as they arise, and to organise members activity.</p>\r\n<p>An <strong>Open Source</strong> program, under the <strong>General Public License</strong>, <strong>Back-End</strong> saves you money on license fees while allowing you, or your IT volunteers, the freedom to adapt the program to your unique needs.</p>\r\n<p>To learn more, please explore the site.  For more detailed information, you can post a question in the <a href="http://sourceforge.net/forum/forum.php?forum_id=20982"><strong>help forum</strong></a>, or join the <strong><a href="http://lists.sourceforge.net/lists/listinfo/back-end-support">support mailing list</a></strong>.  If you run into a bug, please add it to the <a href="http://sourceforge.net/tracker/?group_id=6763&amp;atid=106763" style="font-weight: bold;">bug tracker</a>.  If you\'ve got a feature request, post an <a href="http://sourceforge.net/tracker/?group_id=6763&amp;atid=356763">RFE</a>.<br /></p>', '\r\n<p><strong>Back-End</strong>  allows even the non-technical to manage  any website easily, through a web browser, on any operating system. Fast, flexible and easy to understand, <strong>Back-End</strong> puts you in charge of your site, saving your organisation time and money in the process.<br /></p><p>We have released <a href="http://sourceforge.net/project/showfiles.php?group_id=6763">Back-End version 0.7.2</a> which is easy to install and packed with new features including the ability to undelete sections and articles.  The latest release is also working to be fully xhtml compliant.  <br /></p>\r\n<p><strong>Back-End</strong> is particularly suitable for advocacy organisations, with a suite of tools that allow you to create and manage polls and petitions, and to interact with your visitors, making it faster and easier for you to respond to issues as they arise, and to organise members activity.</p>\r\n<p>An <strong>Open Source</strong> program, under the <strong>General Public License</strong>, <strong>Back-End</strong> saves you money on license fees while allowing you, or your IT volunteers, the freedom to adapt the program to your unique needs.</p>\r\n<p>To learn more, please explore the site.  For more detailed information, you can post a question in the <a href="http://sourceforge.net/forum/forum.php?forum_id=20982"><strong>help forum</strong></a>, or join the <strong><a href="http://lists.sourceforge.net/lists/listinfo/back-end-support">support mailing list</a></strong>.  If you run into a bug, please add it to the <a href="http://sourceforge.net/tracker/?group_id=6763&amp;atid=106763" style="font-weight: bold;">bug tracker</a>.  If you\'ve got a feature request, post an <a href="http://sourceforge.net/tracker/?group_id=6763&amp;atid=356763">RFE</a>.<br /></p>', 'Introduction to Back-End', 'Home', 'Home Page', 'Back-End.org A GPL CMS based on PHP/MySQL', NULL, 'home', 1, 0);
INSERT INTO be_sectionText VALUES (19, 15, 'en', 'WhyBE', 'Why Back-End?', 'Is Back-End the right CMS for you?', '<p >No CMS can meet all the needs of all possible users.  Many applications are either too expensive or too poorly adapted for the needs of small businesses, not-for-profit organisations, NGOs or advocacy groups. <strong >Back-End</strong> has most of the features of more expensive CMS software, but is specifically tailored to the needs of these groups.</p><p ><strong >Back-End</strong> offers the usual content creation and management tools, the ability to set different levels of access for different users, the ability to easily run mailing lists, have your own bulletin board, or use real-time chat. It has both simple and advanced Search options.</p><p ><strong >Back-End</strong> allows you to run polls, to have subsites -- related sites that appear to be independent of the main site, to maintain a photo gallery, to organise E-Actions like petitions and fax/letter-writing campaigns, to maintain an Events schedule to which your vistors may add events, to run an on-line store, to track user preferences.</p><p ><strong >Back-End</strong> also provides support for multiple languages in every aspect of the application -- not as an add-on, but built in from the ground up. </p><p >This is just a short list of some of the non-technical features. If you want to see a complete list of all the features of the latest version of <strong >Back-End</strong>, follow the links after this article...</p>', '<p>No CMS can meet all the needs of all possible users.═Many applications are either too expensive or too poorly adapted for the needs of small businesses, not-for-profit organisations, NGOs or advocacy groups. <strong>Back-End</strong> has most of the features of more expensive CMS software, but is specifically tailored to the needs of these groups.</p><p><strong>Back-End</strong> offers the usual content creation and management tools, the ability to set different levels of access for different users, the ability to easily run mailing lists, have your own bulletin board, or use real-time chat. It has both simple and advanced Search options.</p><p><strong>Back-End</strong> allows you to run polls, to have subsites -- related sites that appear to be independent of the main site, to maintain a photo gallery, to organise E-Actions like petitions and fax/letter-writing campaigns, to maintain an Events schedule to which your vistors may add events, to run an on-line store, to track user preferences.</p><p><strong>Back-End</strong> also provides support for multiple languages in every aspect of the application -- not as an add-on, but built in from the ground up. </p><p>This is just a short list of some of the non-technical features. If you want to see a complete list of all the features of the latest version of <strong>Back-End</strong>, follow the links after this article...</p>', 'Is Back-End the right CMS for you?', 'Why Back-End?', '', '', NULL, 'clients', 0, 35);
INSERT INTO be_sectionText VALUES (20, 16, 'en', 'WhatItCost', 'What\'ll it cost me?', 'What costs, what doesn\'t.', '<p ><strong >Back-End</strong> is an Open Source application made available under the General Public License.&nbsp; That means you do not pay a licensing fee to download, use or adapt the software.&nbsp; This can represent a substantial savings for your organisation.</p>\r\n<p ><strong >Back-End</strong> is written in php, an easily used programming language that will run on any platform.&nbsp; To run Back-End,&nbsp;you will need to have a web server with php enabled, and mySQL installed.&nbsp; You can download&nbsp;php from <a href="http://www.php.net/"><strong >php.net</strong></a>, and&nbsp; mySQL from <a href="http://www.mysql.com/"><strong >mySQL.com</strong></a>, both for free.</p>\r\n<p >That said, it will still cost you something to use <strong >Back-End</strong>.&nbsp; How much depends on a number of factors:</p>\r\n<p >1.&nbsp; You will have to have a web site or network.&nbsp; <strong >Back-End</strong> can help you create the site or service you want, but you still have to buy the hardware or contract with an ISP for a home for that site.</p>\r\n<p >2.&nbsp; If you need technical assistance to install, configure or the web software and&nbsp; <strong >Back-End</strong>, you may have to pay someone for those services.&nbsp; If you, or a volunteer, have the ability to take care of it, those costs can be small -- if you hire a large consultancy to help you, they will be substantially higher.</p>\r\n<p >3.&nbsp; If you want to add&nbsp; capabilities to <strong >Back-End</strong> beyond those it already has, you will have to pay a programmer or programmers.&nbsp; This cost will vary depending on the complexity of the programming involved and the type of programmer you hire.&nbsp; Many of <strong >Back-End</strong>\'s core developers are available to do additional programming.&nbsp; You can contact them <strong ><a href="mailto:back-end@openconcept.ca">by email</a>.</strong></p>\r\n<p >4.&nbsp; If you want a high-concept site design, and you are not a designer, you will still have to hire someone to design your site.</p>\r\n<p >The cost of using <strong >Back-End</strong> is not in the software, but in the type of site you want to have, the level of skill you bring to the task, and the people you have helping you.&nbsp; Every penny you spend will further the goals of your organisation instead of those of a multinational corporation.</p>\r\n<p >You will be saving more than money.</p>', '<p><strong>Back-End</strong> is an Open Source application made available under the General Public License.&nbsp; That means you do not pay a licensing fee to&nbsp;download, use or adapt&nbsp;the software.&nbsp; This can represent a substantial savings for your organisation.</p>\r\n<p><strong>Back-End</strong> is written in php, an easily used programming language that will run on any platform.&nbsp; To run Back-End,&nbsp;you will need to have a web server with php enabled, and mySQL installed.&nbsp; You can download&nbsp;php from <A href="http://www.php.net/"><strong>php.net</strong></a>, and&nbsp; mySQL from <A href="http://www.mysql.com/"><strong>MySQL.com</strong></a>, both for free.</p>\r\n<p>That said, it will still cost you something to use <strong>Back-End</strong>.&nbsp; How much depends on a number of factors:</p>\r\n<p>1.&nbsp; You will have to have a web site or network.&nbsp; <strong>Back-End</strong> can help you create the site or service you want, but you still have to buy the hardware or contract with an ISP for a home for that site.</p>\r\n<p>2.&nbsp; If you need technical assistance to install, configure or the web software and&nbsp; <strong>Back-End</strong>, you may have to pay someone for those services.&nbsp; If you, or a volunteer, have the ability to take care of it, those costs can be small -- if you hire a large consultancy to help you, they will be substantially higher.</p>\r\n<p>3.&nbsp; If you want to add&nbsp; capabilities to <strong>Back-End</strong> beyond those it already has, you will have to pay a programmer or programmers.&nbsp; This cost will vary depending on the complexity of the programming involved and the type of programmer you hire.&nbsp; Many of <strong>Back-End</strong>\'s core developers are available to do additional programming.&nbsp; You can contact them <strong><A href="mailto:back-end@openconcept.ca">by email</a>.</strong></p>\r\n<p>4.&nbsp; If you want a high-concept site design, and you are not a designer, you will still have to hire someone to design your site.</p>\r\n<p>The cost of using <strong>Back-End</strong> is not in the software, but in the type of site you want to have, the level of skill you bring to the task, and the people you have helping you.&nbsp; Every penny you spend will further the goals of your organisation instead of those of a multinational corporation.</p>\r\n<p>You will be saving more than money.</p>', 'What costs, what doesn\'t.', 'What\'ll it cost me?', '', '', NULL, 'clients', 0, 37);
INSERT INTO be_sectionText VALUES (21, 17, 'en', 'HowDoIGetIt', 'How do I get it?', 'Links to the latest version of Back-End, and its documentation.', '<p >The latest version of <strong >Back-End</strong> is available from <a href="http://sourceforge.net/projects/back-end/"><strong >SourceForge</strong></a>.&nbsp; To install it, you simply unzip it into your web directory.</p>\r\n<p >In the <strong >Back-End</strong> root directory, there is a file called Install.&nbsp; It contains all the information you or your IT person will need to get <strong >Back-End</strong> up and running. </p>\r\n<p >Pretty easy, isn\'t it?</p>', '<p>The latest version of <strong>Back-End</strong> is available from <A href="http://sourceforge.net/projects/back-end/"><strong>SourceForge</strong></a>.&nbsp; To install it, you simply unzip it into your web directory.</p>\r\n<p>In the <strong>Back-End</strong> root directory, there is a file called Install.&nbsp; It contains all the information you or your IT person will need to get <strong>Back-End</strong> up and running. </p>\r\n<p>Pretty easy, isn\'t it?</p>', 'Links to the latest version of Back-End, and its documentation.', 'How do I get it?', '', '', NULL, 'clients', 0, 39);
INSERT INTO be_sectionText VALUES (22, 18, 'en', 'GettingHelp', 'What if something goes wrong?', '', '<p >What could go wrong?</p>\r\n<p >If for some reason, you are having issues installing&nbsp; <strong >Back-End</strong>, &nbsp;try the following:</p>\r\n<p >First, Re-read the Install file and INDEX.html to be sure that you have correctl unzipped and configured the application.</p>\r\n<p >If that doesn\'t work, navigate to&nbsp;the <strong >Back-End</strong> /public_html/test.php file to see if there are any<br />outstanding issues highlighted on that page.&nbsp; Usually a misconfiguration or file peculiarity will result in an error message appearing at the top of this page, giving informationa bout the problem.</p>\r\n<p >There are several readme files and a docs directory with information about different aspects of the program. Read a few in areas that appear to be related to the issue.</p>\r\n<p >Search the&nbsp;<a href="http://sourceforge.net/forum/forum.php?forum_id=20982"><strong >support forum</strong> </a>and <a href="http://lists.sourceforge.net/lists/listinfo/back-end-support"><strong >mailing list</strong> </a>for others with similar issues -- usually there will be a solution posted, too.</p>\r\n<p >Write to the <a href="http://lists.sourceforge.net/lists/listinfo/back-end-support"><strong >mailing list</strong>&nbsp;</a>with details of your issue.&nbsp; Please&nbsp;include information about operating system, web software and the version of the script you are running.</p>\r\n<p >If you want to purchase a support contract for your back-End installation, please <strong ><a href="mailto:back-end@openconcept.ca">contact us</a></strong>.</p>\r\n<p >It is rare that a problem will not be solved within the first two or three steps of this procedure -- most&nbsp;are issues of misconfiguration.&nbsp; However, when that rare case does occur, there are a lot of qualified people&nbsp;available to give you the help you need.<br /></p>\r\n<p >&nbsp;</p>', '<p>What could go wrong?</p>\r\n<p>If for some reason, you are having issues installing&nbsp; <strong>Back-End</strong>, &nbsp;try the following:</p>\r\n<p>First, Re-read the Install file and INDEX.html to be sure that you have correctl unzipped and configured the application.</p>\r\n<p>If that doesn\'t work, navigate to&nbsp;the <strong>Back-End</strong> /public_html/test.php file to see if there are any<br />outstanding issues highlighted on that page.&nbsp; Usually a misconfiguration or file peculiarity will result in an error message appearing at the top of this page, giving informationa bout the problem.</p>\r\n<p>There are several readme files and a docs directory with information about different aspects of the program. Read a few in areas that appear to be related to the issue.</p>\r\n<p>Search the&nbsp;<A href="http://sourceforge.net/forum/forum.php?forum_id=20982"><strong>support forum</strong> </a>and <A href="http://lists.sourceforge.net/lists/listinfo/back-end-support"><strong>mailing list</strong> </a>for others with similar issues -- usually there will be a solution posted, too.</p>\r\n<p>Write to the <A href="http://lists.sourceforge.net/lists/listinfo/back-end-support"><strong>mailing list</strong>&nbsp;</a>with details of your issue.&nbsp; Please&nbsp;include information about operating system, web software and the version of the script you are running.</p>\r\n<p>If you want to purchase a support contract for your back-End installation, please <strong><A href="mailto:back-end@openconcept.ca">contact us</a></strong>.</p>\r\n<p>It is rare that a problem will not be solved within the first two or three steps of this procedure -- most&nbsp;are issues of misconfiguration.&nbsp; However, when that rare case does occur, there are a lot of qualified people&nbsp;available to give you the help you need.<br /></p>\r\n<p>&nbsp;</p>', '', 'What if something goes wrong?', '', '', NULL, 'clients', 0, 41);
INSERT INTO be_sectionText VALUES (2, 2, 'en', 'Admin', 'Admin', '', 'Just to show admin features.', 'Just to show admin features.', '', 'Admin', '', '', NULL, '', 0, 21);
INSERT INTO be_sectionText VALUES (3, 3, 'en', 'sitemap', 'Site Map', '', 'Site Map Control', 'Site Map Control', '', 'Site Map', '', '', NULL, '', 0, 22);


INSERT INTO `be_sectionText` (`sectionTextID`, `sectionID`, `languageID`, `title`, `blurb`, `content`, `content_source`, `blurb_source`, `title_source`, `meta_keywords`, `meta_description`, `keywordObjects`, `template`, `originalText`, `commentIDtext`) VALUES (35, 2, 'fr', 'Admin', '', 'Just to show admin features.', 'Just to show admin features.', '', 'Admin', '', '', NULL, '', 0, 21);
INSERT INTO `be_sectionText` (`sectionTextID`, `sectionID`, `languageID`, `title`, `blurb`, `content`, `content_source`, `blurb_source`, `title_source`, `meta_keywords`, `meta_description`, `keywordObjects`, `template`, `originalText`, `commentIDtext`) VALUES (36, 3, 'fr', 'Site Map', '', 'Site Map Control', 'Site Map Control', '', 'Site Map', '', '', NULL, '', 0, 22);

INSERT INTO be_sectionText VALUES (5, 5, 'en', 'Features', 'Hilighted Features', 'There are lots of them.', '\r\nThere are a lot of features in the new release of Back-End.  The multilingual articles capacity is a highlight as is the inline editing.', '\r\nThere are a lot of features in the new release of Back-End.  The multilingual articles capacity is a highlight as is the inline editing.', 'There are lots of them.', 'Hilighted Features', '', '0', NULL, 'clients', 0, 0);
INSERT INTO be_sectionText VALUES (4, 4, 'en', 'Templates', 'BE5 Expands the use of phplib templates', '', 'Back-End 0.5.x is using templates more extensively than 0.4.x', '', '', '', '', '0', NULL, '', 0, 0);
INSERT INTO be_sectionText VALUES (6, 6, 'en', 'Blocks', 'phpSlash&#039;s Block Infrastructure', '', 'You can now add, delete, move the blocks on the sidebars for Back-end.  All through the web interface.', '', '', '', '', '0', NULL, '', 0, 0);
INSERT INTO be_sectionText VALUES (8, 2, 'fr', 'Admin', 'FR Hilighted Features', 'There are lots of them.', 'There are a lot of features in the new release of Back-End.  The multilingual articles capacity is a highlight as is the inline editing.', '', '', '', '', '0', NULL, '', 0, 0);
INSERT INTO be_sectionText VALUES (9, 4, 'fr', 'Templates', 'FR BE5 Expands the use of phplib templates', '', 'Back-End 0.5.x is using templates more extensively than 0.4.x', '', '', '', '', '0', NULL, '', 0, 0);
INSERT INTO be_sectionText VALUES (10, 6, 'fr', 'Blocks', 'FR phpSlash&#039;s Block Infrastructure', '', 'You can now add, delete, move the blocks on the sidebars for Back-end.  All through the web interface.', '', '', '', '', '0', NULL, '', 0, 0);
INSERT INTO be_sectionText VALUES (12, 1, 'fr', 'accueil', 'Accueil', '', 'Accueil ', 'Accueil', '', 'Accueil', '', '', NULL, 'home', 0, 21);
INSERT INTO be_sectionText VALUES (18, 14, 'en', 'WhoShould', 'Who should use one?', 'The types of organisations and individuals that need a CMS.', '<p >Almost every organisation with a web site or network&nbsp;needs a CMS.&nbsp; Individuals can also enjoy substantial benefits from a CMS.</p>\r\n<p >How do you know if you need a CMS?</p>\r\n<p ><strong >If </strong>you have a large or content-rich website or intranet, you need a CMS.</p>\r\n<p ><strong >If</strong> you would <strong >like</strong> to have a content-rich website or intranet, but haven\'t got the resources, you need a CMS.</p>\r\n<p ><strong >If </strong>your organisation depends on the timely publication of up-to-date information, you need a CMS.</p>\r\n<p ><strong >If </strong>you need to have more than one individual or section submitting content for your website or intranet, you need a CMS.</p>\r\n<p ><strong >If</strong> you serve a geographically or organisationally diverse client-base, you need a CMS.</p>\r\n<p ><strong >If</strong> you have a limited time for site&nbsp;development and maintenance, you need a CMS.</p>\r\n<p ><strong >If</strong> you have a limited budget, you need a CMS.</p>\r\n<p ><strong ></strong>&nbsp;</p>', '<p>Almost every organisation with a web site or network&nbsp;needs a CMS.&nbsp; Individuals can also enjoy substantial benefits from a CMS.</p>\r\n<p>How do you know if you need a CMS?</p>\r\n<p><strong>If </strong>you have a large or content-rich website or intranet, you need a CMS.</p>\r\n<p><strong>If</strong> you would <strong>like</strong> to have a content-rich website or intranet, but haven\'t got the resources, you need a CMS.</p>\r\n<p><strong>If </strong>your organisation depends on the timely publication of up-to-date information, you need a CMS.</p>\r\n<p><strong>If </strong>you need to have more than one individual or section submitting content for your website or intranet, you need a CMS.</p>\r\n<p><strong>If</strong> you serve a geographically or organisationally diverse client-base, you need a CMS.</p>\r\n<p><strong>If</strong> you have a limited time for site&nbsp;development and maintenance, you need a CMS.</p>\r\n<p><strong>If</strong> you have a limited budget, you need a CMS.</p>\r\n<p><strong></strong>&nbsp;</p>', 'The types of organisations and individuals that need a CMS.', 'Who should use one?', '', '', NULL, 'clients', 0, 33);
INSERT INTO be_sectionText VALUES (15, 11, 'en', 'developers', 'Developers Area', '', '<p >This section contains information and resources that are useful to people implementing and tailoring Back-End.</p><p >See the <a href="http://www.back-end.org/index.php">Client Area</a> if you want more general information on Back-End\'s features.</p>', '<p>This section contains information and resources that are useful to people implementing and tailoring Back-End.</p><p>See the <a href="http://www.back-end.org/index.php">Client Area</a> if you want more general information on Back-End\'s features.</p>', '', 'Developers Area', '', '', NULL, 'developers', 0, 27);
INSERT INTO be_sectionText VALUES (16, 12, 'en', 'clients', 'Clients Area', '', 'Clients Area', 'Clients Area', '', 'Clients Area', '', '', NULL, 'clients', 0, 29);
INSERT INTO be_sectionText VALUES (17, 13, 'en', 'WhatIs', 'What is a Content Management System?', 'An explanation in simple English.', '<p >A content management system (CMS) is an application for managing web site content.&nbsp; It allows&nbsp; you to create, modify, arrange and delete content without knowing HTML, programming or design.&nbsp; There are many CMS applications available, at all levels of cost and complexity, with similar core features: browser-based publishing, format control,&nbsp;and built-in search tools.</p>\r\n<p >Most CMS applications, have templates which define the overall appearance of the site. Templates designate specific portions of a webpage as areas where content can be placed. <b >Back-End</b> is no exception. </p>\r\n<p>A CMS&nbsp; indexes content files in a database and retrieves it for visitors according to priorities you specify -- you can determine where, when and how information will appear on your site.&nbsp; Because the information is already in the database, your visitors can search your site easily.</p>\r\n<p >Commercial CMS systems&nbsp; focus on tailored marketting tools that allow companies to adapt content and advertising using information provided by the user or gathered by the site: if you search Yahoo for "business supplies" the banners will advertise sources of office supplies, not daycare centres.&nbsp; <strong >Back-End</strong>, because of its focus on advocacy groups, supplies a suite of tools useful to them.</p>', '<p><!--StartFragment -->A content management system (CMS) is an application for managing web site content.&nbsp; It allows&nbsp; you to create, modify, arrange and delete content without knowing HTML, programming or design.&nbsp; There are many CMS applications available, at all levels of cost and complexity, with similar core features: browser-based publishing, format control,&nbsp;and built-in search tools.</p>\r\n<p>Most CMS applications, have templates which define the overall appearance of the site. Templates designate specific portions of a webpage as areas where content can be placed. <b>Back-End</b> is no exception. </p>\r\n<p> A CMS&nbsp; indexes content files in a database and retrieves it for visitors according to priorities you specify -- you can determine where, when and how information will appear on your site.&nbsp; Because the information is already in the database, your visitors can search your site easily.</p>\r\n<p>Commercial CMS systems&nbsp; focus on tailored marketting tools that allow companies to adapt content and advertising using information provided by the user or gathered by the site: if you search Yahoo for "business supplies" the banners will advertise sources of office supplies, not daycare centres.&nbsp; <strong>Back-End</strong>, because of its focus on advocacy groups, supplies a suite of tools useful to them.</p>', 'An explanation in simple English.', 'What is a Content Management System?', '', '', NULL, 'clients', 0, 31);
INSERT INTO be_sectionText VALUES (23, 19, 'en', 'SEC3f8b6fa96da88', 'Back-End supports image gallaries.', 'Back-End supports image gallaries. ', 'Back-End supports image gallaries, amongst many other things. \r\n', 'Back-End supports image gallaries, amongst many other things. \r\n', 'Back-End supports image gallaries. ', 'Back-End supports image gallaries.', '', '', NULL, '', 0, 47);
INSERT INTO be_sectionText VALUES (24, 20, 'en', 'BackEnd_Features_List', 'Back-End CMS Features Short List', 'A short features list for Back-End.', '\r\n<span style="font-family: times new roman,times,serif;">Main Features</span><br style="font-family: times new roman,times,serif;" /><ul><li>simple installation wizard</li><li>web publishing system/content management system</li><li>a truly multilingual CMS with the templates and database configuration allowing for content and interation in any number of lanugages.<br /></li><li>main features include sections; articles; links; gallery, etc.</li><li>free software/GPL (no licensing fees)<br /></li><li>multi-user, browser-based management</li><li>inline editing (edit pages as you go)<br /></li><li>human readable urls</li><li>on-the-fly generation of PDFs</li><li>upload tool to allow you to easily post pdf\'s, Word Documents, etc<br /></li><li>friendly content editing through a cross platform WYSISWYG editor, requiring no plug-ins (on supported browsers)</li><li>images can be uploaded and included in article/section text</li><li>full site search built in</li><li>flexible structuring of sections and linked articles</li><li><span style="color: rgb(0, 0, 0);">built in page caching to reduce load times</span><i><br /></i></li><li>web campaign and on-line advocacy tools</li><ul><li>ePetition and eAction (fax/email) toolkit</li><li>events calendar module</li><li>email-to-a-friend and email alerts</li></ul><li>Friendly and supportive development team<br /></li></ul><tt><br /></tt>\r\n', '\r\n<span style="font-family: times new roman,times,serif;">Main Features</span><br style="font-family: times new roman,times,serif;" /><ul style="font-family: times new roman,times,serif;"><li style="color: rgb(0, 0, 0);">simple installation wizard</li><li>web publishing system/content management system</li><li>a truly multilingual CMS with the templates and database configuration allowing for content and interation in any number of lanugages.<br /></li><li>main features include sections; articles; links; gallery, etc.</li><li>free software/GPL (no licensing fees)<br /></li><li>multi-user, browser-based management</li><li>inline editing (edit pages as you go)<br /></li><li>human readable urls</li><li style="color: rgb(0, 0, 0);">on-the-fly generation of PDFs</li><li>upload tool to allow you to easily post pdf\'s, Word Documents, etc<br /></li><li>friendly content editing through a cross platform WYSISWYG editor, requiring no plug-ins (on supported browsers)</li><li>images can be uploaded and included in article/section text</li><li>full site search built in</li><li style="color: rgb(0, 0, 0);">flexible structuring of sections and linked articles</li><li style="color: rgb(0, 0, 0);"><span style="color: rgb(0, 0, 0);">built in page caching to reduce load times</span><i><br /></i></li><li>web campaign and on-line advocacy tools</li><ul><li>ePetition and eAction (fax/email) toolkit</li><li>events calendar module</li><li>email-to-a-friend and email alerts</li></ul><li>Friendly and supportive development team<br /></li></ul><tt><br /></tt>\r\n', 'A short features list for Back-End.', 'Back-End CMS Features Short List', '', '', NULL, '', 0, 53);
INSERT INTO be_sectionText VALUES (25, 20, 'fr', 'BackEnd_Features_List', 'Back-End CMS Features List', 'A short features list for Back-End.', '\r\n<br />\r\n', '\r\n<br />\r\n', 'A short features list for Back-End.', 'Back-End CMS Features List', '', '', NULL, '', 0, 54);
INSERT INTO be_sectionText VALUES (26, 21, 'en', 'FeaturesForUsers', 'As the user sees it', 'In the end, what really matters is the user experience. Back-End provides a rich enviroment for sites of any size.', '<p >In the end, what really matters is the user experience. Back-End provides a rich enviroment for sites of any size.<br /></p><ul><li>Contents are structured using sections and articles</li><li>To ensure that the site is always relevent, articles can be time-limited </li><li>Other features include links, an image gallery and polls</li><li>Human-readable URLs</li><li>On-the-fly generation of PDFs of any page</li><li>Truly multilingual content and interaction throughout</li><li>Full site search built in</li><li>Optional comment module allows users-feedback on articles</li></ul><p >Back-end also includes web campaign and on-line advocacy tools:</p><ul><li>ePetition and eAction (fax/email) toolkit</li><li>events calendar module</li><li>email-to-a-friend and email alerts</li></ul>', '<p>In the end, what really matters is the user experience. Back-End provides a rich enviroment for sites of any size.<br /></p><ul><li>Contents are structured using sections and articles</li><li>To ensure that the site is always relevent, articles can be time-limited </li><li>Other features include links, an image gallery and polls</li><li>Human-readable URLs</li><li>On-the-fly generation of PDFs of any page</li><li>Truly multilingual content and interaction throughout</li><li>Full site search built in</li><li>Optional comment module allows users-feedback on articles</li></ul><p>Back-end also includes web campaign and on-line advocacy tools:</p><ul><li>ePetition and eAction (fax/email) toolkit</li><li>events calendar module</li><li>email-to-a-friend and email alerts</li></ul>', 'In the end, what really matters is the user experience. Back-End provides a rich enviroment for sites of any size.', 'As the user sees it', '', '', NULL, '', 0, 56);
INSERT INTO be_sectionText VALUES (27, 22, 'en', 'FeaturesForEditors', 'Creating and managing content', 'Back-End provides all the tools you\'d expect from a content management system. Flexibility is at the core of our system.', 'Back-End provides all the tools you\'d expect from a content management system. Flexibility is at the core of our system.<br /><br /><ul><li>Online web publishing system/content management system</li><li>Multi-user, browser-based management of all features</li><li>Contents can be structured using sections and articles</li><li>Your site\'s structure can be changed at any time. It\'s just a case of re-linking sections and articles</li><li>Friendly content editing through a cross platform WYSISWYG editor, requiring no plug-ins (on supported browsers). HTML, Wiki and plain text input also supported</li><li>Optional online template editing - edit pages as you go</li><li>Upload tool to allow you to easily post pdf\'s, Word Documents, etc</li><li>Images can be uploaded and included in article and section text</li></ul>', 'Back-End provides all the tools you\'d expect from a content management system. Flexibility is at the core of our system.<br /><br /><ul><li>Online web publishing system/content management system</li><li>Multi-user, browser-based management of all features</li><li>Contents can be structured using sections and articles</li><li>Your site\'s structure can be changed at any time. It\'s just a case of re-linking sections and articles</li><li>Friendly content editing through a cross platform WYSISWYG editor, requiring no plug-ins (on supported browsers). HTML, Wiki and plain text input also supported</li><li>Optional online template editing - edit pages as you go</li><li>Upload tool to allow you to easily post pdf\'s, Word Documents, etc</li><li>Images can be uploaded and included in article and section text</li></ul>', 'Back-End provides all the tools you\'d expect from a content management system. Flexibility is at the core of our system.', 'Creating and managing content', '', '', NULL, '', 0, 58);
INSERT INTO be_sectionText VALUES (28, 23, 'en', 'FeaturesForBusiness', 'Management, Installation and Configuration', 'Find out more why Back-End could be the logical choice for your installation.', 'Back-End provides an excellent choice for your content needs<br /><br /><ul><li>Fine grained permission management</li><li>Option of LDAP/Active Directoy-based authentication</li><li>Back-End is FREE GPL software - no licence fees are payable</li><li>Runs under Linux/Unix and Windows</li><li>Friendly and supportive user community and development team</li><li>Simple installation wizard</li><li>Use of templates makes it easy to tailor Back-End\'s look and feel to your organization\'s needs</li><li>Simple configuration of templates and database configuration allowing for content and interation in any number of languages</li><li>Integrated optional page caching to reduce load times and server-load</li></ul>', 'Back-End provides an excellent choice for your content needs<br /><br /><ul><li>Fine grained permission management</li><li>Option of LDAP/Active Directoy-based authentication</li><li>Back-End is FREE GPL software - no licence fees are payable</li><li>Runs under Linux/Unix and Windows</li><li>Friendly and supportive user community and development team</li><li>Simple installation wizard</li><li>Use of templates makes it easy to tailor Back-End\'s look and feel to your organization\'s needs</li><li>Simple configuration of templates and database configuration allowing for content and interation in any number of languages</li><li>Integrated optional page caching to reduce load times and server-load</li></ul>', 'Find out more why Back-End could be the logical choice for your installation.', 'Management, Installation and Configuration', '', '', NULL, '', 0, 60);
INSERT INTO be_sectionText VALUES (29, 24, 'en', 'developerDocs', 'Developer Docs', 'Some explainations for how BE works for folks who want to develop it more.', '\r\n<ul><li>Browse our developer docs <a href="http://www.back-end.org/Developers_Guide/">online</a></li><li>Download the <a href="http://www.back-end.org/Developers_Guide.tar.gz">tarball</a></li></ul>\r\n', '\r\n<ul><li>Browse our developer docs <a href="http://www.back-end.org/Developers_Guide/">online</a></li><li>Download the <a href="http://www.back-end.org/Developers_Guide.tar.gz">tarball</a></li></ul>\r\n', 'Some explainations for how BE works for folks who want to develop it more.', 'Developer Docs', '', '', NULL, '', 0, 62);
INSERT INTO be_sectionText VALUES (30, 25, 'en', 'SEC3fbb9f6a7142b', 'Extensive multi-lingual support', 'Back-End has multi-lingual support built in, from the ground up. ', '<li> suport for an unlimited number of languages </li>\r\n  <li> every object in the system can have multiple languages associated\r\nwith it </li>\r\n  <li> designed into Back-End classes from the ground up </li>\r\n  <li> designed into the database schema to be flexible and extensible</li>\r\n', '<li> suport for an unlimited number of languages </li>\r\n  <li> every object in the system can have multiple languages associated\r\nwith it </li>\r\n  <li> designed into Back-End classes from the ground up </li>\r\n  <li> designed into the database schema to be flexible and extensible</li>\r\n', 'Back-End has multi-lingual support built in, from the ground up. ', 'Extensive multi-lingual support', '', '', NULL, '', 0, 68);
INSERT INTO be_sectionText VALUES (31, 26, 'en', 'SEC3fbba0106ddd2', 'Sub-sites', '', '<li> way to host multiple sites using a common database, site\r\nhierarchy, and content </li>\r\n  <li> intended for groups that sharing some common content</li>\r\n', '<li> way to host multiple sites using a common database, site\r\nhierarchy, and content </li>\r\n  <li> intended for groups that sharing some common content</li>\r\n', '', 'Sub-sites', '', '', NULL, '', 0, 70);
INSERT INTO be_sectionText VALUES (32, 27, 'en', 'SEC3fbba0f400006', 'Advanced Search', '', '<ul><li> search within a subsite, a single section, a branch of sections,\r\nor categories </li><li> ranking </li><li> optional integrated htdig support </li></ul>\r\n<p > </p>\r\n<h2 ><a name="Section_Hierarchy"></a></h2>\r\n', '<ul><li> search within a subsite, a single section, a branch of sections,\r\nor categories </li><li> ranking </li><li> optional integrated htdig support </li></ul>\r\n<p> </p>\r\n<h2><a name="Section_Hierarchy"></a></h2>\r\n', '', 'Advanced Search', '', '', NULL, '', 0, 72);
INSERT INTO be_sectionText VALUES (33, 28, 'en', 'SEC3fbba1d867f02', 'Section Hierarchy', 'Back-End\'s section hierarchy improves control over the site. ', '<p ><a name="Section_Hierarchy">Back-End provides sections to allow you to control and manage your site. A section has a name and content, and can be assigned a <span style="font-style: italic;">site url</span>. Each section can be the child of another section, and can act as a parent to other sections. Using section-specific controls, you can also control who sees the content of each section. <br /></a></p>\r\n', '<p><a name="Section_Hierarchy">Back-End provides sections to allow you to control and manage your site. A section has a name and content, and can be assigned a <span style="font-style: italic;">site url</span>. Each section can be the child of another section, and can act as a parent to other sections. Using section-specific controls, you can also control who sees the content of each section. <br /></a></p>\r\n', 'Back-End\'s section hierarchy improves control over the site. ', 'Section Hierarchy', '', '', NULL, '', 0, 74);
INSERT INTO be_sectionText VALUES (34, 29, 'en', 'SEC3fbba227f348a', 'Categories', '', ' Allows multiple levels of categorization for each article.\r\n', ' Allows multiple levels of categorization for each article.\r\n', '', 'Categories', '', '', NULL, '', 0, 76);


# --------------------------------------------------------


#
# Dumping data for table `be_sections`
#

# Clear previous entries
DELETE FROM `be_sections`;

INSERT INTO `be_sections` (`sectionID`, `URLname`, `author_id`, `dateCreated`, `dateModified`, `dateAvailable`, `dateRemoved`, `dateForSort`, `content_type`, `hide`, `restrict2members`, `showSections`, `showArticles`, `showLinkSubmit`, `pollID`, `hitCounter`, `priority`, `redirect`, `commentID`, `orderbySections`, `orderbySectionsLogic`, `orderbyArticles`, `orderbyArticlesLogic`, `orderbyLinks`, `orderbyLinksLogic`) VALUES (1, 'Home', 0, 1063782000, 1066402005, 1063782000, 0, 0, 'html', 0, 0, 0, 1, 0, 0, 0, 100, '', 1, 'dateCreated', 'desc', 'dateCreated', 'desc', 'dateCreated', 'desc');
INSERT INTO `be_sections` (`sectionID`, `URLname`, `author_id`, `dateCreated`, `dateModified`, `dateAvailable`, `dateRemoved`, `dateForSort`, `content_type`, `hide`, `restrict2members`, `showSections`, `showArticles`, `showLinkSubmit`, `pollID`, `hitCounter`, `priority`, `redirect`, `commentID`, `orderbySections`, `orderbySectionsLogic`, `orderbyArticles`, `orderbyArticlesLogic`, `orderbyLinks`, `orderbyLinksLogic`) VALUES (2, 'Admin', 1, 1041835651, 1041835651, 1041835651, 1830775651, 0, '', 1, 0, 1, 0, 0, 0, 0, 1, '/login.php', 1, 'dateCreated', 'desc', 'dateCreated', 'desc', 'dateCreated', 'desc');
INSERT INTO `be_sections` (`sectionID`, `URLname`, `author_id`, `dateCreated`, `dateModified`, `dateAvailable`, `dateRemoved`, `dateForSort`, `content_type`, `hide`, `restrict2members`, `showSections`, `showArticles`, `showLinkSubmit`, `pollID`, `hitCounter`, `priority`, `redirect`, `commentID`, `orderbySections`, `orderbySectionsLogic`, `orderbyArticles`, `orderbyArticlesLogic`, `orderbyLinks`, `orderbyLinksLogic`) VALUES (3, 'sitemap', 1, 1041835651, 1041835651, 1041835651, 1830775651, 0, '', 1, 0, 1, 0, 0, 0, 0, 1, '/sitemap.php', 1, 'dateCreated', 'desc', 'dateCreated', 'desc', 'dateCreated', 'desc');
INSERT INTO `be_sections` (`sectionID`, `URLname`, `author_id`, `dateCreated`, `dateModified`, `dateAvailable`, `dateRemoved`, `dateForSort`, `content_type`, `hide`, `restrict2members`, `showSections`, `showArticles`, `showLinkSubmit`, `pollID`, `hitCounter`, `priority`, `redirect`, `commentID`, `orderbySections`, `orderbySectionsLogic`, `orderbyArticles`, `orderbyArticlesLogic`, `orderbyLinks`, `orderbyLinksLogic`) VALUES (5, 'Features', 0, 1041829200, 1065215697, 1041829200, 1830747600, 0, 'html', 0, 0, 1, 1, 0, 0, 0, 12, '', 0, 'title', 'asc', 'title', 'asc', 'title', 'asc');
INSERT INTO `be_sections` (`sectionID`, `URLname`, `author_id`, `dateCreated`, `dateModified`, `dateAvailable`, `dateRemoved`, `dateForSort`, `content_type`, `hide`, `restrict2members`, `showSections`, `showArticles`, `showLinkSubmit`, `pollID`, `hitCounter`, `priority`, `redirect`, `commentID`, `orderbySections`, `orderbySectionsLogic`, `orderbyArticles`, `orderbyArticlesLogic`, `orderbyLinks`, `orderbyLinksLogic`) VALUES (4, 'Templates', 1, 1041835651, 1041835651, 1041835651, 1830775651, 0, '', 0, 1, 1, 0, 0, 0, 0, 13, '', 0, 'dateCreated', 'desc', 'dateCreated', 'desc', 'dateCreated', 'desc');
INSERT INTO `be_sections` (`sectionID`, `URLname`, `author_id`, `dateCreated`, `dateModified`, `dateAvailable`, `dateRemoved`, `dateForSort`, `content_type`, `hide`, `restrict2members`, `showSections`, `showArticles`, `showLinkSubmit`, `pollID`, `hitCounter`, `priority`, `redirect`, `commentID`, `orderbySections`, `orderbySectionsLogic`, `orderbyArticles`, `orderbyArticlesLogic`, `orderbyLinks`, `orderbyLinksLogic`) VALUES (6, 'Blocks', 1, 1041835651, 1041835651, 1041835651, 1830775651, 0, '', 0, 0, 0, 0, 0, 0, 0, 14, '', 0, 'dateCreated', 'desc', 'dateCreated', 'desc', 'dateCreated', 'desc');
INSERT INTO `be_sections` (`sectionID`, `URLname`, `author_id`, `dateCreated`, `dateModified`, `dateAvailable`, `dateRemoved`, `dateForSort`, `content_type`, `hide`, `restrict2members`, `showSections`, `showArticles`, `showLinkSubmit`, `pollID`, `hitCounter`, `priority`, `redirect`, `commentID`, `orderbySections`, `orderbySectionsLogic`, `orderbyArticles`, `orderbyArticlesLogic`, `orderbyLinks`, `orderbyLinksLogic`) VALUES (11, 'developers', 0, 1069056000, 1069070890, 1069056000, 0, 0, 'html', 0, 0, 1, 1, 1, 0, 0, 0, '', 26, 'dateCreated', 'desc', 'dateCreated', 'desc', 'dateCreated', 'desc');
INSERT INTO `be_sections` (`sectionID`, `URLname`, `author_id`, `dateCreated`, `dateModified`, `dateAvailable`, `dateRemoved`, `dateForSort`, `content_type`, `hide`, `restrict2members`, `showSections`, `showArticles`, `showLinkSubmit`, `pollID`, `hitCounter`, `priority`, `redirect`, `commentID`, `orderbySections`, `orderbySectionsLogic`, `orderbyArticles`, `orderbyArticlesLogic`, `orderbyLinks`, `orderbyLinksLogic`) VALUES (12, 'clients', 0, 1063771200, 1063832754, 1063771200, 0, 0, 'html', 0, 0, 1, 1, 1, 0, 0, 0, '', 28, 'dateCreated', 'desc', 'dateCreated', 'desc', 'dateCreated', 'desc');
INSERT INTO `be_sections` (`sectionID`, `URLname`, `author_id`, `dateCreated`, `dateModified`, `dateAvailable`, `dateRemoved`, `dateForSort`, `content_type`, `hide`, `restrict2members`, `showSections`, `showArticles`, `showLinkSubmit`, `pollID`, `hitCounter`, `priority`, `redirect`, `commentID`, `orderbySections`, `orderbySectionsLogic`, `orderbyArticles`, `orderbyArticlesLogic`, `orderbyLinks`, `orderbyLinksLogic`) VALUES (13, 'WhatIs', 0, 1063782000, 1065464307, 1063782000, 0, 0, 'html', 0, 0, 1, 1, 1, 0, 0, 0, '', 30, 'dateCreated', 'desc', 'dateCreated', 'desc', 'dateCreated', 'desc');
INSERT INTO `be_sections` (`sectionID`, `URLname`, `author_id`, `dateCreated`, `dateModified`, `dateAvailable`, `dateRemoved`, `dateForSort`, `content_type`, `hide`, `restrict2members`, `showSections`, `showArticles`, `showLinkSubmit`, `pollID`, `hitCounter`, `priority`, `redirect`, `commentID`, `orderbySections`, `orderbySectionsLogic`, `orderbyArticles`, `orderbyArticlesLogic`, `orderbyLinks`, `orderbyLinksLogic`) VALUES (14, 'WhoShould', 0, 1063857600, 1063931073, 1063857600, 0, 0, 'html', 0, 0, 1, 1, 1, 0, 0, 0, '', 32, 'dateCreated', 'desc', 'dateCreated', 'desc', 'dateCreated', 'desc');
INSERT INTO `be_sections` (`sectionID`, `URLname`, `author_id`, `dateCreated`, `dateModified`, `dateAvailable`, `dateRemoved`, `dateForSort`, `content_type`, `hide`, `restrict2members`, `showSections`, `showArticles`, `showLinkSubmit`, `pollID`, `hitCounter`, `priority`, `redirect`, `commentID`, `orderbySections`, `orderbySectionsLogic`, `orderbyArticles`, `orderbyArticlesLogic`, `orderbyLinks`, `orderbyLinksLogic`) VALUES (15, 'WhyBE', 0, 1063782000, 1069069857, 1063782000, 0, 0, 'html', 0, 0, 1, 1, 1, 0, 0, 0, '', 34, 'dateCreated', 'desc', 'dateCreated', 'desc', 'dateCreated', 'desc');
INSERT INTO `be_sections` (`sectionID`, `URLname`, `author_id`, `dateCreated`, `dateModified`, `dateAvailable`, `dateRemoved`, `dateForSort`, `content_type`, `hide`, `restrict2members`, `showSections`, `showArticles`, `showLinkSubmit`, `pollID`, `hitCounter`, `priority`, `redirect`, `commentID`, `orderbySections`, `orderbySectionsLogic`, `orderbyArticles`, `orderbyArticlesLogic`, `orderbyLinks`, `orderbyLinksLogic`) VALUES (16, 'WhatItCost', 0, 1063782000, 1065464450, 1063782000, 0, 0, 'html', 0, 0, 1, 1, 1, 0, 0, 0, '', 36, 'dateCreated', 'desc', 'dateCreated', 'desc', 'dateCreated', 'desc');
INSERT INTO `be_sections` (`sectionID`, `URLname`, `author_id`, `dateCreated`, `dateModified`, `dateAvailable`, `dateRemoved`, `dateForSort`, `content_type`, `hide`, `restrict2members`, `showSections`, `showArticles`, `showLinkSubmit`, `pollID`, `hitCounter`, `priority`, `redirect`, `commentID`, `orderbySections`, `orderbySectionsLogic`, `orderbyArticles`, `orderbyArticlesLogic`, `orderbyLinks`, `orderbyLinksLogic`) VALUES (17, 'HowDoIGetIt', 0, 1063857600, 1064188355, 1063857600, 0, 0, 'html', 0, 0, 1, 1, 1, 0, 0, 0, '', 38, 'dateCreated', 'desc', 'dateCreated', 'desc', 'dateCreated', 'desc');
INSERT INTO `be_sections` (`sectionID`, `URLname`, `author_id`, `dateCreated`, `dateModified`, `dateAvailable`, `dateRemoved`, `dateForSort`, `content_type`, `hide`, `restrict2members`, `showSections`, `showArticles`, `showLinkSubmit`, `pollID`, `hitCounter`, `priority`, `redirect`, `commentID`, `orderbySections`, `orderbySectionsLogic`, `orderbyArticles`, `orderbyArticlesLogic`, `orderbyLinks`, `orderbyLinksLogic`) VALUES (18, 'GettingHelp', 0, 1063857600, 1064189175, 1063857600, 0, 0, 'html', 0, 0, 1, 1, 1, 0, 0, 0, '', 40, 'dateCreated', 'desc', 'dateCreated', 'desc', 'dateCreated', 'desc');
INSERT INTO `be_sections` (`sectionID`, `URLname`, `author_id`, `dateCreated`, `dateModified`, `dateAvailable`, `dateRemoved`, `dateForSort`, `content_type`, `hide`, `restrict2members`, `showSections`, `showArticles`, `showLinkSubmit`, `pollID`, `hitCounter`, `priority`, `redirect`, `commentID`, `orderbySections`, `orderbySectionsLogic`, `orderbyArticles`, `orderbyArticlesLogic`, `orderbyLinks`, `orderbyLinksLogic`) VALUES (19, 'SEC3f8b6fa96da88', 0, 1066028400, 1066102933, 1066028400, 0, 0, 'html', 0, 0, 1, 1, 1, 0, 0, 0, '', 46, 'dateCreated', 'desc', 'dateCreated', 'desc', 'dateCreated', 'desc');
INSERT INTO `be_sections` (`sectionID`, `URLname`, `author_id`, `dateCreated`, `dateModified`, `dateAvailable`, `dateRemoved`, `dateForSort`, `content_type`, `hide`, `restrict2members`, `showSections`, `showArticles`, `showLinkSubmit`, `pollID`, `hitCounter`, `priority`, `redirect`, `commentID`, `orderbySections`, `orderbySectionsLogic`, `orderbyArticles`, `orderbyArticlesLogic`, `orderbyLinks`, `orderbyLinksLogic`) VALUES (20, 'BackEnd_Features_List', 0, 1067932800, 1068826137, 1067932800, 0, 0, 'html', 0, 0, 1, 1, 1, 0, 0, 0, '', 52, 'dateCreated', 'desc', 'dateCreated', 'desc', 'dateCreated', 'desc');
INSERT INTO `be_sections` (`sectionID`, `URLname`, `author_id`, `dateCreated`, `dateModified`, `dateAvailable`, `dateRemoved`, `dateForSort`, `content_type`, `hide`, `restrict2members`, `showSections`, `showArticles`, `showLinkSubmit`, `pollID`, `hitCounter`, `priority`, `redirect`, `commentID`, `orderbySections`, `orderbySectionsLogic`, `orderbyArticles`, `orderbyArticlesLogic`, `orderbyLinks`, `orderbyLinksLogic`) VALUES (21, 'FeaturesForUsers', 0, 1068969600, 1069069752, 1068969600, 0, 0, 'html', 0, 0, 1, 1, 1, 0, 0, 80, '', 55, 'dateCreated', 'desc', 'dateCreated', 'desc', 'dateCreated', 'desc');
INSERT INTO `be_sections` (`sectionID`, `URLname`, `author_id`, `dateCreated`, `dateModified`, `dateAvailable`, `dateRemoved`, `dateForSort`, `content_type`, `hide`, `restrict2members`, `showSections`, `showArticles`, `showLinkSubmit`, `pollID`, `hitCounter`, `priority`, `redirect`, `commentID`, `orderbySections`, `orderbySectionsLogic`, `orderbyArticles`, `orderbyArticlesLogic`, `orderbyLinks`, `orderbyLinksLogic`) VALUES (22, 'FeaturesForEditors', 0, 1068969600, 1069069780, 1068969600, 0, 0, 'html', 0, 0, 1, 1, 1, 0, 0, 50, '', 57, 'dateCreated', 'desc', 'dateCreated', 'desc', 'dateCreated', 'desc');
INSERT INTO `be_sections` (`sectionID`, `URLname`, `author_id`, `dateCreated`, `dateModified`, `dateAvailable`, `dateRemoved`, `dateForSort`, `content_type`, `hide`, `restrict2members`, `showSections`, `showArticles`, `showLinkSubmit`, `pollID`, `hitCounter`, `priority`, `redirect`, `commentID`, `orderbySections`, `orderbySectionsLogic`, `orderbyArticles`, `orderbyArticlesLogic`, `orderbyLinks`, `orderbyLinksLogic`) VALUES (24, 'developerDocs', 0, 1069142400, 1069173264, 1069142400, 0, 0, 'html', 0, 0, 1, 1, 1, 0, 0, 0, '', 61, 'dateCreated', 'desc', 'dateCreated', 'desc', 'dateCreated', 'desc');
INSERT INTO `be_sections` (`sectionID`, `URLname`, `author_id`, `dateCreated`, `dateModified`, `dateAvailable`, `dateRemoved`, `dateForSort`, `content_type`, `hide`, `restrict2members`, `showSections`, `showArticles`, `showLinkSubmit`, `pollID`, `hitCounter`, `priority`, `redirect`, `commentID`, `orderbySections`, `orderbySectionsLogic`, `orderbyArticles`, `orderbyArticlesLogic`, `orderbyLinks`, `orderbyLinksLogic`) VALUES (23, 'FeaturesForBusiness', 0, 1068969600, 1069069723, 1068969600, 0, 0, 'html', 0, 0, 1, 1, 1, 0, 0, 20, '', 59, 'dateCreated', 'desc', 'dateCreated', 'desc', 'dateCreated', 'desc');
INSERT INTO `be_sections` (`sectionID`, `URLname`, `author_id`, `dateCreated`, `dateModified`, `dateAvailable`, `dateRemoved`, `dateForSort`, `content_type`, `hide`, `restrict2members`, `showSections`, `showArticles`, `showLinkSubmit`, `pollID`, `hitCounter`, `priority`, `redirect`, `commentID`, `orderbySections`, `orderbySectionsLogic`, `orderbyArticles`, `orderbyArticlesLogic`, `orderbyLinks`, `orderbyLinksLogic`) VALUES (25, 'SEC3fbb9f6a7142b', 40, 1069228800, 1069260650, 1069228800, 0, 0, 'html', 0, 0, 1, 1, 1, 0, 0, 0, '', 67, 'dateCreated', 'desc', 'dateCreated', 'desc', 'dateCreated', 'desc');
INSERT INTO `be_sections` (`sectionID`, `URLname`, `author_id`, `dateCreated`, `dateModified`, `dateAvailable`, `dateRemoved`, `dateForSort`, `content_type`, `hide`, `restrict2members`, `showSections`, `showArticles`, `showLinkSubmit`, `pollID`, `hitCounter`, `priority`, `redirect`, `commentID`, `orderbySections`, `orderbySectionsLogic`, `orderbyArticles`, `orderbyArticlesLogic`, `orderbyLinks`, `orderbyLinksLogic`) VALUES (26, 'SEC3fbba0106ddd2', 40, 1069228800, 1069260816, 1069228800, 0, 0, 'html', 0, 0, 1, 1, 1, 0, 0, 0, '', 69, 'dateCreated', 'desc', 'dateCreated', 'desc', 'dateCreated', 'desc');
INSERT INTO `be_sections` (`sectionID`, `URLname`, `author_id`, `dateCreated`, `dateModified`, `dateAvailable`, `dateRemoved`, `dateForSort`, `content_type`, `hide`, `restrict2members`, `showSections`, `showArticles`, `showLinkSubmit`, `pollID`, `hitCounter`, `priority`, `redirect`, `commentID`, `orderbySections`, `orderbySectionsLogic`, `orderbyArticles`, `orderbyArticlesLogic`, `orderbyLinks`, `orderbyLinksLogic`) VALUES (27, 'SEC3fbba0f400006', 40, 1069228800, 1069261043, 1069228800, 0, 0, 'html', 0, 0, 1, 1, 1, 0, 0, 0, '', 71, 'dateCreated', 'desc', 'dateCreated', 'desc', 'dateCreated', 'desc');
INSERT INTO `be_sections` (`sectionID`, `URLname`, `author_id`, `dateCreated`, `dateModified`, `dateAvailable`, `dateRemoved`, `dateForSort`, `content_type`, `hide`, `restrict2members`, `showSections`, `showArticles`, `showLinkSubmit`, `pollID`, `hitCounter`, `priority`, `redirect`, `commentID`, `orderbySections`, `orderbySectionsLogic`, `orderbyArticles`, `orderbyArticlesLogic`, `orderbyLinks`, `orderbyLinksLogic`) VALUES (28, 'SEC3fbba1d867f02', 40, 1069228800, 1069261272, 1069228800, 0, 0, 'html', 0, 0, 1, 1, 1, 0, 0, 0, '', 73, 'dateCreated', 'desc', 'dateCreated', 'desc', 'dateCreated', 'desc');
INSERT INTO `be_sections` (`sectionID`, `URLname`, `author_id`,
`dateCreated`, `dateModified`, `dateAvailable`, `dateRemoved`, `dateForSort`,
`content_type`, `hide`, `restrict2members`, `showSections`, `showArticles`,
`showLinkSubmit`, `pollID`, `hitCounter`, `priority`, `redirect`,
`commentID`, `orderbySections`, `orderbySectionsLogic`, `orderbyArticles`, `orderbyArticlesLogic`, `orderbyLinks`, `orderbyLinksLogic`) VALUES (29, 'SEC3fbba227f348a', 40,
1069228800, 1069261351, 1069228800, 0, 0, 'html', 0, 0, 1, 1, 1, 0, 0, 0, '',
75, 'dateCreated', 'desc', 'dateCreated', 'desc', 'dateCreated', 'desc');


# --------------------------------------------------------


#
# Dumping data for table `psl_block`
#

# Clear previous entries
DELETE FROM `psl_block`;


INSERT INTO `psl_block` VALUES (1, 12, 'Administration', 0, '', 'menu_ary=menuadmin&amp;tpl=navbarBlockh', '', 'a:4:{s:6:"column";s:6:"center";s:5:"width";s:0:"";s:8:"box_type";s:0:"";s:5:"perms";s:4:"user";}', 80, 1109856106);
INSERT INTO `psl_block` VALUES (3, 100, 'Navigation', 10000, '', '', '', 'a:11:{s:6:"column";s:4:"left";s:5:"width";s:3:"160";s:8:"box_type";s:0:"";s:5:"perms";s:0:"";s:5:"count";s:0:"";s:7:"orderby";s:8:"priority";s:7:"ascdesc";s:3:"asc";s:7:"section";s:1:"1";s:12:"showarticles";s:2:"no";s:12:"showSiblings";s:2:"no";s:8:"template";s:0:"";}', 100, 1109855722);
INSERT INTO `psl_block` VALUES (5, 3, 'Back-End newsfeeds', 10000, 'http://www.back-end.org/', 'http://www.back-end.org/backend.php', '', 'a:9:{s:6:"column";s:5:"right";s:5:"width";s:3:"150";s:8:"box_type";s:0:"";s:5:"perms";s:0:"";s:5:"count";s:0:"";s:6:"target";s:5:"_self";s:5:"title";s:0:"";s:3:"tpl";s:0:"";s:6:"errors";s:5:"debug";}', 10, 1109855440);
INSERT INTO `psl_block` VALUES (34, 109, 'Recent Signatures', 0, '', '', '', 'a:4:{s:6:"column";s:5:"right";s:5:"width";s:0:"";s:8:"box_type";s:0:"";s:10:"petitionID";s:1:"1";}', 0, NULL);
INSERT INTO `psl_block` VALUES (10, 5, 'Poll', 10000, '', '', '', 'a:6:{s:6:"column";s:5:"right";s:5:"width";s:4:"100%";s:8:"box_type";s:8:"headless";s:5:"perms";s:0:"";s:11:"question_id";s:0:"";s:11:"language_id";s:0:"";}', 40, 1109855825);
INSERT INTO `psl_block` VALUES (36, 110, 'Events', 0, '', '', '', 'a:3:{s:6:"column";s:4:"left";s:5:"width";s:0:"";s:8:"box_type";s:0:"";}', 0, NULL);
INSERT INTO `psl_block` VALUES (39, 1, 'Template Admin', 0, '', '', '', 'a:4:{s:6:"column";s:5:"right";s:5:"width";s:0:"";s:8:"box_type";s:0:"";s:5:"perms";s:8:"template";}', 0, 1109855914);
INSERT INTO `psl_block` VALUES (41, 113, 'Language', 10000, '', '', '', 'a:5:{s:6:"column";s:4:"left";s:5:"width";s:4:"100%";s:8:"box_type";s:5:"fancy";s:5:"perms";s:0:"";s:13:"template_file";s:0:"";}', 70, 1109856145);
INSERT INTO `psl_block` VALUES (44, 1, 'Validate HTML', 0, '', '', '', 'a:4:{s:6:"column";s:5:"right";s:5:"width";s:4:"100%";s:8:"box_type";s:5:"fancy";s:5:"perms";s:4:"user";}', 0, 1109855992);
INSERT INTO `psl_block` VALUES (50, 102, 'New items', 10000, '', '', '', 'a:8:{s:6:"column";s:4:"left";s:5:"width";s:0:"";s:8:"box_type";s:5:"fancy";s:5:"perms";s:0:"";s:5:"count";s:0:"";s:7:"orderby";s:13:"dateAvailable";s:7:"ascdesc";s:4:"desc";s:18:"showAllNewarticles";s:1:"0";}', 30, 1109855775);
INSERT INTO `psl_block` VALUES (51, 104, 'Related articles', 0, '', '', '', 'a:7:{s:6:"column";s:12:"centerbottom";s:5:"width";s:0:"";s:8:"box_type";s:5:"fancy";s:5:"perms";s:0:"";s:5:"count";s:0:"";s:7:"orderby";s:11:"dateCreated";s:7:"ascdesc";s:4:"desc";}', 20, 1109856161);
INSERT INTO `psl_block` VALUES (54, 103, 'Links at random', 6000, '', '', '', 'a:7:{s:6:"column";s:5:"right";s:5:"width";s:4:"100%";s:8:"box_type";s:5:"fancy";s:5:"perms";s:0:"";s:5:"count";s:0:"";s:7:"orderby";s:5:"title";s:7:"ascdesc";s:3:"asc";}', 10, 1109855606);
INSERT INTO `psl_block` VALUES (57, 1, 'Type preferences', 0, '', '', '', 'a:4:{s:6:"column";s:4:"left";s:5:"width";s:4:"100%";s:8:"box_type";s:5:"fancy";s:5:"perms";s:0:"";}', 12, 1109855944);



# --------------------------------------------------------

#
# Dumping data for table `psl_blockText`
#

# Clear previous entries
DELETE FROM `psl_blockText`;

INSERT INTO `psl_blockText` VALUES (9, 1, 'en', 'Administration', '', 'menu_ary=menuadmin&amp;tpl=navbarBlockh', '<!-- START: navbarBlock.tpl -->\n<div id="navbarblock">\n    <ul>\n      <li><a href="/login.php?logout=yes&redirect=%2Fadmin%2FblockAdmin.php%3F" title="Logout god">Logout god</a></li>      <li><a href="/profile.php" title="My Preferences">My Preferences</a></li>      <li><a href="/admin/blockAdmin.php" title="Block">Block</a></li>      <li><a href="/admin/pollAdmin.php" title="Poll">Poll</a></li>      <li><a href="/admin/authorAdmin.php" title="Users">Users</a></li>      <li><a href="/admin/infologAdmin.php" title="Logging">Logging</a></li>      <li><a href="/admin/groupAdmin.php" title="Group">Group</a></li>      <li><a href="/admin/BE_sectionAdmin.php" title="Section">Section</a></li>      <li><a href="/admin/BE_articleAdmin.php" title="Article">Article</a></li>      <li><a href="/admin/BE_linkAdmin.php" title="Links">Links</a></li>      <li><a href="/admin/BE_uploadAdmin.php" title="Upload">Upload</a></li>      <li><a href="/admin/BE_editTemplateAdmin.php" title="Templates">Templates</a></li>      <li><a href="/admin/BE_contactAdmin.php" title="Contact">Contact</a></li>      <li><a href="/admin/BE_followupAdmin.php" title="Followup">Followup</a></li>      <li><a href="/admin/BE_actionAdmin.php" title="Action">Action</a></li>      <li><a href="/admin/BE_petitionAdmin.php" title="Petitions">Petitions</a></li>      <li><a href="/admin/BE_feedbackAdmin.php" title="View Feedback">View Feedback</a></li>    </ul>\n    <div class="spacer">&nbsp;</div>\n</div>    \n<!-- END: navbarBlock.tpl -->', 1109856106);
INSERT INTO `psl_blockText` VALUES (2, 5, 'fr', 'Back-End newsfeeds', 'http://www.back-end.org/', 'http://www.back-end.org/backend.php', '    <a class="Link3" href="http://www.be.ca" target="_self"><img src="http://www.be.ca/images/BE/BE_logo-100x34.png" title="Back-End on phpSlash" alt="Back-End on phpSlash" /></a>\r\n    <i>Back-End builds on phpSlash to provide a simple and flexible CMS.</i><br />\r\n    <li><a class="Link3" target="_self" href="http://www.be.ca/index.php?article=ASiteMap">New Sitemap</a>\r\n    <br /><i>\r\n      We''ve added a site map to help your users navigate your site more easily.\r\n    </i>\r\n    <li><a class="Link3" target="_self" href="http://www.be.ca/index.php?article=Sidebar">Creating a Mozilla Sidebar with BE</a>\r\n    <br /><i>\r\n      \r\n\r\n\r\n    </i>\r\n    <li><a class="Link3" target="_self" href="http://www.be.ca/index.php?article=Comments">Threaded Comments in Articles</a>\r\n    <br /><i>\r\n      \r\n    </i>\r\n', 1109855440);
INSERT INTO `psl_blockText` VALUES (3, 3, 'fr', 'Navigation', '', '', '<!-- START OF TEMPLATED DISPLAY STORY BE_sectionLinks.tpl -->\r\n\r\n<table>\r\n<tr>\r\n   <td>\r\n    <a href="/index.php/Home"  class="TopLevelSections">FR Home</a>\r\n   </td>\r\n</tr>\r\n</table>\r\n\r\n<!-- END OF TEMPLATED DISPLAY STORY BE_sectionLinks.tpl -->\r\n\r\n\r\n', 1109855722);
INSERT INTO `psl_blockText` VALUES (77, 57, 'fr', 'Type preferences', '', '', '<ul>\r\n<li><a href="#" onclick="setActiveStyleSheet(''default''); return false;" onkeypress=="setActiveStyleSheet(''default''); return false;">default</a></li>\r\n<li><a href="#" onclick="setActiveStyleSheet(''bigger''); return false;" onkeypress=="setActiveStyleSheet(''bigger''); return false;">bigger</a></li>\r\n</ul>\r\n<noscript>Note: Enable Javascript</noscript>', 1109855944);
INSERT INTO `psl_blockText` VALUES (5, 10, 'en', 'Poll', '', '', '<!-- START: BE_pollDisplay.tpl -->\n    <form action="/poll.php">\n   <p> <img src="/images/poll_icon.gif" alt="poll icon" width="35" height="36" style="float: left" />\n        <input type="hidden" name="question_id" value="4" /> \n        <input type="hidden" name="submit" value="vote" /> </p>\n         <p>What Tools would you like to see in the next release?</p>\n   <div class="row">\n         <span class="inputrev"><input type="radio" name="answer_id" value="0" id="be_answerID0" /></span><span class="labelrev"><label for="be_answerID0">Content Approval</label></span><br />\n   </div>\n <div class="row">\n         <span class="inputrev"><input type="radio" name="answer_id" value="1" id="be_answerID1" /></span><span class="labelrev"><label for="be_answerID1">Problem Notification</label></span><br />\n  </div>\n <div class="row">\n         <span class="inputrev"><input type="radio" name="answer_id" value="2" id="be_answerID2" /></span><span class="labelrev"><label for="be_answerID2">Pluggable API</label></span><br />\n   </div>\n <div class="row">\n         <span class="inputrev"><input type="radio" name="answer_id" value="3" id="be_answerID3" /></span><span class="labelrev"><label for="be_answerID3">Content Staging</label></span><br />\n </div>\n <div class="row">\n         <span class="inputrev"><input type="radio" name="answer_id" value="4" id="be_answerID4" /></span><span class="labelrev"><label for="be_answerID4">Trash</label></span><br />\n  </div>\n <div class="row">\n         <span class="inputrev"><input type="radio" name="answer_id" value="5" id="be_answerID5" /></span><span class="labelrev"><label for="be_answerID5">Web-based Translation Management</label></span><br />\n  </div>\n <div class="row">\n         <span class="inputrev"><input type="radio" name="answer_id" value="6" id="be_answerID6" /></span><span class="labelrev"><label for="be_answerID6">Workflow Engine</label></span><br />\n </div>\n <div class="row">\n         <span class="inputrev"><input type="radio" name="answer_id" value="7" id="be_answerID7" /></span><span class="labelrev"><label for="be_answerID7">XHTML Compliant</label></span><br />\n </div>\n         <p><input type="submit" value="Vote" /> <br />[ <a href="/poll.php?submit=viewresults&amp;question_id=4">Results</a> | <a href="/poll.php">Polls</a> ] </p>\n    </form>\n\n<!-- END: BE_pollDisplay.tpl -->\n', 1109855825);
INSERT INTO `psl_blockText` VALUES (10, 5, 'en', 'Back-End newsfeeds', 'http://www.back-end.org/', 'http://www.back-end.org/backend.php', '    <a class="Link3" href="http://www.be.ca" target="_self"><img src="http://www.be.ca/images/BE/BE_logo-100x34.png" title="Back-End on phpSlash" alt="Back-End on phpSlash" /></a>\r\n    <i>Back-End builds on phpSlash to provide a simple and flexible CMS.</i><br />\r\n    <li><a class="Link3" target="_self" href="http://www.be.ca/index.php?article=ASiteMap">New Sitemap</a>\r\n    <br /><i>\r\n      We''ve added a site map to help your users navigate your site more easily.\r\n    </i>\r\n    <li><a class="Link3" target="_self" href="http://www.be.ca/index.php?article=Sidebar">Creating a Mozilla Sidebar with BE</a>\r\n    <br /><i>\r\n      \r\n\r\n\r\n    </i>\r\n    <li><a class="Link3" target="_self" href="http://www.be.ca/index.php?article=Comments">Threaded Comments in Articles</a>\r\n    <br /><i>\r\n      \r\n    </i>\r\n', 1109855440);
INSERT INTO `psl_blockText` VALUES (11, 3, 'en', 'Navigation', '', '', '<!-- START OF TEMPLATED DISPLAY STORY BE_sectionLinks.tpl -->\r\n\r\n<table>\r\n<tr>\r\n   <td>\r\n      <a href="/index.php/Home"  class="TopLevelSections">Home</a>\r\n   </td>\r\n</tr>\r\n<tr>\r\n   <td>\r\n    <a href="/index.php/developers"  class="TopLevelSections">Developers Area</a>\r\n   </td>\r\n</tr>\r\n<tr>\r\n   <td>\r\n     <a href="/index.php/clients"  class="TopLevelSections">Clients Area</a>\r\n   </td>\r\n</tr>\r\n</table>\r\n\r\n<!-- END OF TEMPLATED DISPLAY STORY BE_sectionLinks.tpl -->\r\n\r\n\r\n', 1109855722);
INSERT INTO `psl_blockText` VALUES (73, 50, 'fr', 'New items', '', '', '', 1109855774);
INSERT INTO `psl_blockText` VALUES (74, 10, 'fr', 'Poll', '', '', '', 1109855824);
INSERT INTO `psl_blockText` VALUES (75, 51, 'fr', 'Related articles', '', '', '', 1109855863);
INSERT INTO `psl_blockText` VALUES (76, 39, 'fr', 'Template Admin', '', '', '<script type="text/javascript">\r\n<!--\r\n // Code Contributed by Lasse Nielsen - http://www.infimum.dk\r\n function convert(node) {\r\n   if (!node) {node = document.body;}\r\n\r\n   switch (node.nodeType) {\r\n     case Node.COMMENT_NODE:\r\n       var text = node.nodeValue;\r\n       if (text.substr(0,7)==" START ") {\r\n         var newNode = createNewNode(text.substr(7));\r\n         node.parentNode.replaceChild(newNode,node);\r\n       }\r\n       break\r\n     case Node.ELEMENT_NODE: \r\n       for (var i=0;i<node.childNodes.length;i++) {\r\n         convert(node.childNodes[i]);\r\n       }\r\n       break;\r\n     default:\r\n       break;\r\n  }\r\n}\r\n\r\nfunction createNewNode(text) {\r\n  var a = document.createElement("a");\r\n  a.href =\r\n"{ROOTURL}/admin/BE_editTemplateAdmin.php?submit=edit&file="+escape(text);\r\n  var img = document.createElement("img");\r\n  img.src = "{IMAGEURL}/BE/buttons/templateView.gif";\r\n  a.appendChild(img);\r\n  return a;\r\n}\r\n-->\r\n</script>\r\n\r\n<input type="button" value="Convert!" onclick="convert()" />', 1109855914);
INSERT INTO `psl_blockText` VALUES (24, 34, 'en', 'Recent Signatures', '', '', '<em><li>Robert Tasher from San Ignacio  <li>Jillian Banfield from Petite Riviere  <li>Maria Lucia Cypriano from Guabiruba  <li>Amy Zeder from Keller  <li>Mary Alexander from Schenectady  <li>Alicia Butscher from Decatur  <li>bas van der pol from eindhoven de gexte  <li>Sue Willaims from Belmopan  <li>mags williams from cardiff  <li>Alice Brown from Laurens  </em>', NULL);
INSERT INTO `psl_blockText` VALUES (27, 36, 'en', 'Events', '', '', '<!-- START BE_upcomingEventsBlock.tpl -->\n\n<div class="box1"><h3>Upcoming Events</h3>\n<ul>\n{event_block}<li class="row0"><a href="/events.php?submit=addEvent&amp;calendar=default">Add an event</a></li>\n</ul>\n</div>\n\n<!-- END BE_upcomingEventsBlock.tpl -->', NULL);
INSERT INTO `psl_blockText` VALUES (30, 39, 'en', 'Template Admin', '', '', '<script type="text/javascript">\r\n<!--\r\n // Code Contributed by Lasse Nielsen - http://www.infimum.dk\r\n function convert(node) {\r\n   if (!node) {node = document.body;}\r\n\r\n   switch (node.nodeType) {\r\n     case Node.COMMENT_NODE:\r\n       var text = node.nodeValue;\r\n       if (text.substr(0,7)==" START ") {\r\n         var newNode = createNewNode(text.substr(7));\r\n         node.parentNode.replaceChild(newNode,node);\r\n       }\r\n       break\r\n     case Node.ELEMENT_NODE: \r\n       for (var i=0;i<node.childNodes.length;i++) {\r\n         convert(node.childNodes[i]);\r\n       }\r\n       break;\r\n     default:\r\n       break;\r\n  }\r\n}\r\n\r\nfunction createNewNode(text) {\r\n  var a = document.createElement("a");\r\n  a.href =\r\n"{ROOTURL}/admin/BE_editTemplateAdmin.php?submit=edit&file="+escape(text);\r\n  var img = document.createElement("img");\r\n  img.src = "{IMAGEURL}/BE/buttons/templateView.gif";\r\n  a.appendChild(img);\r\n  return a;\r\n}\r\n-->\r\n</script>\r\n\r\n<input type="button" value="Convert!" onclick="convert()" />', 1109855914);
INSERT INTO `psl_blockText` VALUES (32, 41, 'en', 'Language', '', '', '<!-- START BE_languageSwitchingBlockSelect.tpl -->\r\n\r\n<script type="text/javascript">\r\nfunction submitForm() {\r\n   window.location = document.getElementById(''languageSelect'').value;\r\n   return false;\r\n}\r\n</script>\r\n\r\n<form method="post" action="/index.php" id="languageChoice">\r\n<p><select name="language" id="languageSelect" onchange="javascript:submitForm();">\r\n   <option value="" selected="selected">English</option>\r\n   <option value="?lang=fr" >French</option>\r\n</select>\r\n</p>\r\n</form>\r\n\r\n<!-- END BE_languageSwitchingBlockSelect.tpl -->\r\n', 1109856145);
INSERT INTO `psl_blockText` VALUES (37, 44, 'en', 'Validate HTML', '', '', '<ul>\r\n<li><a href="http://validator.w3.org/check?uri={URL}" target="_blank">W3C HTML Validator</a>\r\n<li><a href="http://jigsaw.w3.org/css-validator/validator?usermedium=all&uri={URL}" target="_blank">W3C CSS Validator</a></li>\r\n<li><a href="http://htmlhelp.com/cgi-bin/validate.cgi?warnings=yes&url={URL}" target="_blank">WDG HTML Validator</a></li>\r\n<li><a href="http://valet.webthing.com/view=Asis/page/validate?parser=Any&resultsMode=traditional&parseMode=web&url={URL}" target="_blank">Webthing HTML Validator</a></li>\r\n<li><a href="http://bobby.watchfire.com/bobby/bobbyServlet?output=Submit&gl=wcag1-aaa&test=&URL={URL}" target="_blank">Bobby</a></li>\r\n<li><a href="http://valet.webthing.com/view=Asis/access/htnorm?suite=WCAG3&xslt=compact&url={URL}" target="_blank">Webthing Accessibility Valet</a></li>\r\n</ul>', 1109855992);
INSERT INTO `psl_blockText` VALUES (52, 51, 'en', 'Related articles', '', '', '', 1109856161);
INSERT INTO `psl_blockText` VALUES (58, 54, 'en', 'Links at random', '', '', '<!-- START BE_linkSidebar.tpl -->\n\n<ul>\n     <li><a href="http://www.back-end.org" class="sidebarLink" >Back-End</a> </li>\n     <li><a href="http://www.billblaikie.ca" class="sidebarLink" >Bill Blaikie, MP</a> </li>\n    <li><a href="http://www.brianmasse.ca/" class="sidebarLink" >Brian Masse, MP</a> </li>\n     <li><a href="http://www.calgaryblizzard.com/" class="sidebarLink" >Calgary Blizzard</a> </li>\n    <li><a href="http://www.cupe.ca/" class="sidebarLink" >CUPE National</a> </li>\n    <li><a href="http://cvs-demo.back-end.org/" class="sidebarLink" >Demo site</a> </li>\n    <li><a href="http://www.openconcept.ca/be_wiki/" class="sidebarLink" >Developers Wiki</a> </li>\n     <li><a href="http://ehcn.openconcept.ca/petition.php?petitionID=1" class="sidebarLink" >Ecumenical Health Care Network</a> </li>\n     <li><a href="http://www.fairvotecanada.org/" class="sidebarLink" >Fair Vote Canada</a> </li>\n     <li><a href="http://www.genderatwork.org/index.html" class="sidebarLink" >Gender At Work</a> </li>\n</ul>\n\n<p><a href="/links.php" class="sidebarLink">more...</a></p>\n\n<!-- END BE_linkSidebar.tpl -->\n', 1109855606);
INSERT INTO `psl_blockText` VALUES (50, 50, 'en', 'New items', '', '', '', 1109855775);
INSERT INTO `psl_blockText` VALUES (71, 41, 'fr', 'Langue', '', '', '', 1109856145);
INSERT INTO `psl_blockText` VALUES (72, 54, 'fr', 'Links at random', '', '', '', 1109855604);
INSERT INTO `psl_blockText` VALUES (78, 44, 'fr', 'Validate HTML', '', '', '<ul>\r\n<li><a href="http://validator.w3.org/check?uri={URL}" target="_blank">W3C HTML Validator</a>\r\n<li><a href="http://jigsaw.w3.org/css-validator/validator?usermedium=all&uri={URL}" target="_blank">W3C CSS Validator</a></li>\r\n<li><a href="http://htmlhelp.com/cgi-bin/validate.cgi?warnings=yes&url={URL}" target="_blank">WDG HTML Validator</a></li>\r\n<li><a href="http://valet.webthing.com/view=Asis/page/validate?parser=Any&resultsMode=traditional&parseMode=web&url={URL}" target="_blank">Webthing HTML Validator</a></li>\r\n<li><a href="http://bobby.watchfire.com/bobby/bobbyServlet?output=Submit&gl=wcag1-aaa&test=&URL={URL}" target="_blank">Bobby</a></li>\r\n<li><a href="http://valet.webthing.com/view=Asis/access/htnorm?suite=WCAG3&xslt=compact&url={URL}" target="_blank">Webthing Accessibility Valet</a></li>\r\n</ul>', 1109855992);
INSERT INTO `psl_blockText` VALUES (64, 57, 'en', 'Type preferences', '', '', '<ul>\r\n<li><a href="#" onclick="setActiveStyleSheet(''default''); return false;" onkeypress="setActiveStyleSheet(''default''); return false;">default</a></li>\r\n<li><a href="#" onclick="setActiveStyleSheet(''bigger''); return false;" onkeypress="setActiveStyleSheet(''bigger''); return false;">bigger</a></li>\r\n</ul>\r\n<noscript>Note: Enable Javascript</noscript>', 1109855944);
INSERT INTO `psl_blockText` VALUES (79, 1, 'fr', 'Administration', '', '', '', 1109856105);




# --------------------------------------------------------



#
# Dumping data for table `psl_block_type`
#

# Clear previous entries
DELETE FROM `psl_block_type`;

INSERT INTO `psl_block_type` (`id`, `name`) VALUES (1, 'html');
INSERT INTO `psl_block_type` (`id`, `name`) VALUES (2, 'url');
INSERT INTO `psl_block_type` (`id`, `name`) VALUES (3, 'rss');
INSERT INTO `psl_block_type` (`id`, `name`) VALUES (5, 'poll');
INSERT INTO `psl_block_type` (`id`, `name`) VALUES (6, 'query');
INSERT INTO `psl_block_type` (`id`, `name`) VALUES (9, 'quote');
INSERT INTO `psl_block_type` (`id`, `name`) VALUES (10, 'skin');
INSERT INTO `psl_block_type` (`id`, `name`) VALUES (11, 'login');
INSERT INTO `psl_block_type` (`id`, `name`) VALUES (12, 'navbar');
INSERT INTO `psl_block_type` (`id`, `name`) VALUES (100, 'BE_sectionList');
INSERT INTO `psl_block_type` (`id`, `name`) VALUES (101, 'BE_spotlightArticles');
INSERT INTO `psl_block_type` (`id`, `name`) VALUES (102, 'BE_newArticles');
INSERT INTO `psl_block_type` (`id`, `name`) VALUES (103, 'BE_randomLinks');
INSERT INTO `psl_block_type` (`id`, `name`) VALUES (104, 'BE_relatedArticles');
INSERT INTO `psl_block_type` (`id`, `name`) VALUES (105, 'BE_relatedCategories');
INSERT INTO `psl_block_type` (`id`, `name`) VALUES (106, 'BE_whatsPopular');
INSERT INTO `psl_block_type` (`id`, `name`) VALUES (107, 'BE_relatedKeywords');
INSERT INTO `psl_block_type` (`id`, `name`) VALUES (108, 'BE_action');
INSERT INTO `psl_block_type` (`id`, `name`) VALUES (111, 'BE_upcomingEvents');
INSERT INTO `psl_block_type` (`id`, `name`) VALUES (112, 'BE_recentPopular');
INSERT INTO `psl_block_type` (`id`, `name`) VALUES (113, 'BE_languageSwitching');
INSERT INTO `psl_block_type` (`id`, `name`) VALUES (114, 'BE_petitions');
INSERT INTO `psl_block_type` (`id`, `name`) VALUES (115, 'BE_petitionSigners');

#INSERT INTO psl_block_type (id, name) VALUES (105, 'BE_relatedCategories');

# --------------------------------------------------------


#
# Dumping data for table `psl_poll_answer`
#

# Clear previous entries
DELETE FROM `psl_poll_answer`;

INSERT INTO psl_poll_answer VALUES (4, '4', 'Trash', 0);
INSERT INTO psl_poll_answer VALUES (4, '1', 'Problem Notification', 0);
INSERT INTO psl_poll_answer VALUES (4, '2', 'Pluggable API', 0);
INSERT INTO psl_poll_answer VALUES (4, '3', 'Content Staging', 0);
INSERT INTO psl_poll_answer VALUES (4, '0', 'Content Approval', 0);
INSERT INTO psl_poll_answer VALUES (4, '5', 'Web-based Translation Management', 0);
INSERT INTO psl_poll_answer VALUES (4, '6', 'Workflow Engine', 0);
INSERT INTO psl_poll_answer VALUES (4, '7', 'XHTML Compliant', 0);
INSERT INTO psl_poll_answer VALUES (77, '0', 'Typo3', 0);
INSERT INTO psl_poll_answer VALUES (77, '1', 'Plone', 0);
INSERT INTO psl_poll_answer VALUES (77, '2', 'PostNuke', 0);
INSERT INTO psl_poll_answer VALUES (77, '3', 'Drupal', 0);
INSERT INTO psl_poll_answer VALUES (77, '4', 'eZ publish', 0);
INSERT INTO psl_poll_answer VALUES (77, '5', 'Geeklog', 0);
INSERT INTO psl_poll_answer VALUES (77, '6', 'Mambo Open Source', 0);
INSERT INTO psl_poll_answer VALUES (77, '7', 'phpWebSite', 0);

# --------------------------------------------------------


#
# Dumping data for table `psl_poll_question`
#

# Clear previous entries
DELETE FROM `psl_poll_question`;

INSERT INTO `psl_poll_question` VALUES (4, 'What Tools would you like to see in the next release?', 0, 1, 1, 'en', '');
INSERT INTO psl_poll_question VALUES (77, 'What other Open Source CMS Applications have you reviewed?', 0, 0, 1087865753, 'en', '');

# --------------------------------------------------------


#
# Dumping data for table `psl_poll_voter`
#

# --------------------------------------------------------

#
# Dumping data for table `db_sequence`
#

# Clear previous entries
DELETE FROM `db_sequence`;

# These are unused by BE and should be removed - mg May2005
# INSERT INTO `db_sequence` (`seq_name`, `nextid`) VALUES ('psl_topic_seq', 36);
# INSERT INTO `db_sequence` (`seq_name`, `nextid`) VALUES ('psl_topic_lut_seq', 70);
# INSERT INTO `db_sequence` (`seq_name`, `nextid`) VALUES ('psl_section_lut_seq', 243);
# INSERT INTO `db_sequence` (`seq_name`, `nextid`) VALUES ('psl_submission_seq', 11);
# INSERT INTO `db_sequence` (`seq_name`, `nextid`) VALUES ('psl_topic_submission_lut_seq', 21);
# INSERT INTO `db_sequence` (`seq_name`, `nextid`) VALUES ('psl_section_submission_lut_seq', 13);
# INSERT INTO `db_sequence` (`seq_name`, `nextid`) VALUES ('psl_section_seq', 9);
# INSERT INTO `db_sequence` (`seq_name`, `nextid`) VALUES ('psl_mailinglist_seq', 2);

INSERT INTO `db_sequence` (`seq_name`, `nextid`) VALUES ('psl_comment_seq', 1);
INSERT INTO `db_sequence` (`seq_name`, `nextid`) VALUES ('psl_comment_dep_seq', 1);
INSERT INTO `db_sequence` (`seq_name`, `nextid`) VALUES ('psl_infolog', 1);
INSERT INTO `db_sequence` (`seq_name`, `nextid`) VALUES ('psl_glossary_seq', 1);
INSERT INTO `db_sequence` (`seq_name`, `nextid`) VALUES ('be_images', 1);
INSERT INTO `db_sequence` (`seq_name`, `nextid`) VALUES ('event_seq', 1);
INSERT INTO `db_sequence` (`seq_name`, `nextid`) VALUES ('eventText_seq', 1);

SELECT @VariableId := max(variable_id) FROM psl_variable;
INSERT INTO `db_sequence` (`seq_name`, `nextid`) VALUES ('psl_variable_seq', (@VariableId+1));
SELECT @BlockId := max(id) FROM psl_block;
INSERT INTO `db_sequence` (`seq_name`, `nextid`) VALUES ('psl_block_seq', (@BlockId+1));
SELECT @BlockTextId := max(textID) FROM psl_blockText;
INSERT INTO `db_sequence` (`seq_name`, `nextid`) VALUES ('psl_blockText_seq', (@BlockTextId+1));
SELECT @BlocktypeId := max(id) FROM psl_block_type;
INSERT INTO `db_sequence` (`seq_name`, `nextid`) VALUES ('psl_blocktype_seq', (@BlocktypeId+1));
SELECT @AuthorId := max(author_id) FROM psl_author;
INSERT INTO `db_sequence` (`seq_name`, `nextid`) VALUES ('psl_author_seq', (@AuthorId+1));
SELECT @SectionBlockLutId := max(lut_id) FROM psl_section_block_lut;
INSERT INTO `db_sequence` (`seq_name`, `nextid`) VALUES ('psl_section_block_lut_seq', (@SectionBlockLutId+1));
SELECT @PermissionId := max(permission_id) FROM psl_permission;
INSERT INTO `db_sequence` (`seq_name`, `nextid`) VALUES ('psl_permission_seq', (@PermissionId+1));
SELECT @GroupId := max(group_id) FROM psl_group;
INSERT INTO `db_sequence` (`seq_name`, `nextid`) VALUES ('psl_group_seq', (@GroupId+1));
SELECT @GroupSectionLutId := max(lut_id) FROM psl_group_section_lut;
INSERT INTO `db_sequence` (`seq_name`, `nextid`) VALUES ('psl_group_section_lut_seq', (@GroupSectionLutId+1));
SELECT @GroupPermissionLutId := max(lut_id) FROM psl_group_permission_lut;
INSERT INTO `db_sequence` (`seq_name`, `nextid`) VALUES ('psl_group_permission_lut_seq', (@GroupPermissionLutId+1));
SELECT @GroupGroupLutId := max(lut_id) FROM psl_group_group_lut;
INSERT INTO `db_sequence` (`seq_name`, `nextid`) VALUES ('psl_group_group_lut_seq', (@GroupGroupLutId+1));
SELECT @AuthorGroupId := max(lut_id) FROM psl_author_group_lut;
INSERT INTO `db_sequence` (`seq_name`, `nextid`) VALUES ('psl_author_group_lut_seq', (@AuthorGroupId+1));
SELECT @SectionId := max(sectionID) FROM be_sections;
INSERT INTO `db_sequence` (`seq_name`, `nextid`) VALUES ('be_sections', (@SectionId+1));
SELECT @ArticleId := max(articleID) FROM be_articles;
INSERT INTO `db_sequence` (`seq_name`, `nextid`) VALUES ('be_articles', (@ArticleId+1));
SELECT @LinkId := max(linkID) FROM be_link;
INSERT INTO `db_sequence` (`seq_name`, `nextid`) VALUES ('be_link', (@LinkId+1));


# --------------------------------------------------------

#
# Dumping data for table `psl_commentcount`
#

# Clear previous entries
DELETE FROM `psl_commentcount`;

INSERT INTO psl_commentcount VALUES (4, 0);
INSERT INTO psl_commentcount VALUES (77, 0);

# --------------------------------------------------------

#
# Dumping data for table `psl_section_block_lut`
#


# Clear previous entries
DELETE FROM `psl_section_block_lut`;

INSERT INTO `psl_section_block_lut` VALUES (579, 151, 11);
INSERT INTO `psl_section_block_lut` VALUES (413, 152, 10);
INSERT INTO `psl_section_block_lut` VALUES (447, 0, 15);
INSERT INTO `psl_section_block_lut` VALUES (446, 0, 14);
INSERT INTO `psl_section_block_lut` VALUES (445, 0, 13);
INSERT INTO `psl_section_block_lut` VALUES (980, 10, 11);
INSERT INTO `psl_section_block_lut` VALUES (402, 0, 9);
INSERT INTO `psl_section_block_lut` VALUES (440, 36, 10);
INSERT INTO `psl_section_block_lut` VALUES (450, 0, 18);
INSERT INTO `psl_section_block_lut` VALUES (449, 0, 17);
INSERT INTO `psl_section_block_lut` VALUES (448, 0, 16);
INSERT INTO `psl_section_block_lut` VALUES (979, 10, 1);
INSERT INTO `psl_section_block_lut` VALUES (963, 3, 20);
INSERT INTO `psl_section_block_lut` VALUES (403, 0, 10);
INSERT INTO `psl_section_block_lut` VALUES (415, 35, 10);
INSERT INTO `psl_section_block_lut` VALUES (441, 36, 11);
INSERT INTO `psl_section_block_lut` VALUES (1072, 1, 20);
INSERT INTO `psl_section_block_lut` VALUES (429, 0, 12);
INSERT INTO `psl_section_block_lut` VALUES (428, 0, 11);
INSERT INTO `psl_section_block_lut` VALUES (418, 34, 10);
INSERT INTO `psl_section_block_lut` VALUES (537, 0, 19);
INSERT INTO `psl_section_block_lut` VALUES (573, 0, 20);
INSERT INTO `psl_section_block_lut` VALUES (574, 0, 21);
INSERT INTO `psl_section_block_lut` VALUES (575, 0, 22);
INSERT INTO `psl_section_block_lut` VALUES (576, 0, 23);
INSERT INTO `psl_section_block_lut` VALUES (580, 0, 24);
INSERT INTO `psl_section_block_lut` VALUES (581, 0, 25);
INSERT INTO `psl_section_block_lut` VALUES (582, 0, 26);
INSERT INTO `psl_section_block_lut` VALUES (583, 0, 27);
INSERT INTO `psl_section_block_lut` VALUES (584, 0, 28);
INSERT INTO `psl_section_block_lut` VALUES (585, 0, 29);
INSERT INTO `psl_section_block_lut` VALUES (1071, 1, 18);
INSERT INTO `psl_section_block_lut` VALUES (1070, 1, 17);
INSERT INTO `psl_section_block_lut` VALUES (1069, 1, 16);
INSERT INTO `psl_section_block_lut` VALUES (1068, 1, 23);
INSERT INTO `psl_section_block_lut` VALUES (1067, 1, 22);
INSERT INTO `psl_section_block_lut` VALUES (1066, 1, 21);
INSERT INTO `psl_section_block_lut` VALUES (1065, 1, 15);
INSERT INTO `psl_section_block_lut` VALUES (1064, 1, 14);
INSERT INTO `psl_section_block_lut` VALUES (1063, 1, 13);
INSERT INTO `psl_section_block_lut` VALUES (1062, 1, 12);
INSERT INTO `psl_section_block_lut` VALUES (1061, 1, 24);
INSERT INTO `psl_section_block_lut` VALUES (1060, 1, 11);
INSERT INTO `psl_section_block_lut` VALUES (1059, 1, 3);
INSERT INTO `psl_section_block_lut` VALUES (1058, 1, 2);
INSERT INTO `psl_section_block_lut` VALUES (1057, 1, 1);
INSERT INTO `psl_section_block_lut` VALUES (1048, 44, 14);
INSERT INTO `psl_section_block_lut` VALUES (1047, 44, 13);
INSERT INTO `psl_section_block_lut` VALUES (1046, 44, 12);
INSERT INTO `psl_section_block_lut` VALUES (1045, 44, 24);
INSERT INTO `psl_section_block_lut` VALUES (1044, 44, 11);
INSERT INTO `psl_section_block_lut` VALUES (1043, 44, 3);
INSERT INTO `psl_section_block_lut` VALUES (1042, 44, 2);
INSERT INTO `psl_section_block_lut` VALUES (1041, 44, 1);
INSERT INTO `psl_section_block_lut` VALUES (1040, 57, 20);
INSERT INTO `psl_section_block_lut` VALUES (1024, 39, 20);
INSERT INTO `psl_section_block_lut` VALUES (1023, 39, 18);
INSERT INTO `psl_section_block_lut` VALUES (1022, 39, 17);
INSERT INTO `psl_section_block_lut` VALUES (1021, 39, 16);
INSERT INTO `psl_section_block_lut` VALUES (1020, 39, 23);
INSERT INTO `psl_section_block_lut` VALUES (1019, 39, 22);
INSERT INTO `psl_section_block_lut` VALUES (1018, 39, 21);
INSERT INTO `psl_section_block_lut` VALUES (1017, 39, 15);
INSERT INTO `psl_section_block_lut` VALUES (1016, 39, 14);
INSERT INTO `psl_section_block_lut` VALUES (1015, 39, 13);
INSERT INTO `psl_section_block_lut` VALUES (1014, 39, 12);
INSERT INTO `psl_section_block_lut` VALUES (1013, 39, 24);
INSERT INTO `psl_section_block_lut` VALUES (1012, 39, 11);
INSERT INTO `psl_section_block_lut` VALUES (1011, 39, 3);
INSERT INTO `psl_section_block_lut` VALUES (1010, 39, 2);
INSERT INTO `psl_section_block_lut` VALUES (1009, 39, 1);
INSERT INTO `psl_section_block_lut` VALUES (962, 3, 18);
INSERT INTO `psl_section_block_lut` VALUES (961, 3, 17);
INSERT INTO `psl_section_block_lut` VALUES (960, 3, 16);
INSERT INTO `psl_section_block_lut` VALUES (959, 3, 23);
INSERT INTO `psl_section_block_lut` VALUES (958, 3, 22);
INSERT INTO `psl_section_block_lut` VALUES (957, 3, 21);
INSERT INTO `psl_section_block_lut` VALUES (956, 3, 15);
INSERT INTO `psl_section_block_lut` VALUES (955, 3, 14);
INSERT INTO `psl_section_block_lut` VALUES (1088, 41, 20);
INSERT INTO `psl_section_block_lut` VALUES (1087, 41, 18);
INSERT INTO `psl_section_block_lut` VALUES (1086, 41, 17);
INSERT INTO `psl_section_block_lut` VALUES (1085, 41, 16);
INSERT INTO `psl_section_block_lut` VALUES (1084, 41, 23);
INSERT INTO `psl_section_block_lut` VALUES (1083, 41, 22);
INSERT INTO `psl_section_block_lut` VALUES (1082, 41, 21);
INSERT INTO `psl_section_block_lut` VALUES (1081, 41, 15);
INSERT INTO `psl_section_block_lut` VALUES (1080, 41, 14);
INSERT INTO `psl_section_block_lut` VALUES (1079, 41, 13);
INSERT INTO `psl_section_block_lut` VALUES (1078, 41, 12);
INSERT INTO `psl_section_block_lut` VALUES (1077, 41, 24);
INSERT INTO `psl_section_block_lut` VALUES (1076, 41, 11);
INSERT INTO `psl_section_block_lut` VALUES (1075, 41, 3);
INSERT INTO `psl_section_block_lut` VALUES (1074, 41, 2);
INSERT INTO `psl_section_block_lut` VALUES (1073, 41, 1);
INSERT INTO `psl_section_block_lut` VALUES (775, 45, 30);
INSERT INTO `psl_section_block_lut` VALUES (774, 45, 23);
INSERT INTO `psl_section_block_lut` VALUES (773, 45, 22);
INSERT INTO `psl_section_block_lut` VALUES (772, 45, 21);
INSERT INTO `psl_section_block_lut` VALUES (771, 45, 15);
INSERT INTO `psl_section_block_lut` VALUES (770, 45, 14);
INSERT INTO `psl_section_block_lut` VALUES (769, 45, 13);
INSERT INTO `psl_section_block_lut` VALUES (768, 45, 12);
INSERT INTO `psl_section_block_lut` VALUES (767, 45, 24);
INSERT INTO `psl_section_block_lut` VALUES (766, 45, 11);
INSERT INTO `psl_section_block_lut` VALUES (765, 45, 3);
INSERT INTO `psl_section_block_lut` VALUES (764, 45, 2);
INSERT INTO `psl_section_block_lut` VALUES (763, 45, 1);
INSERT INTO `psl_section_block_lut` VALUES (738, 46, 15);
INSERT INTO `psl_section_block_lut` VALUES (739, 46, 21);
INSERT INTO `psl_section_block_lut` VALUES (740, 46, 22);
INSERT INTO `psl_section_block_lut` VALUES (741, 46, 23);
INSERT INTO `psl_section_block_lut` VALUES (742, 46, 16);
INSERT INTO `psl_section_block_lut` VALUES (743, 46, 17);
INSERT INTO `psl_section_block_lut` VALUES (744, 46, 18);
INSERT INTO `psl_section_block_lut` VALUES (745, 46, 20);
INSERT INTO `psl_section_block_lut` VALUES (954, 3, 13);
INSERT INTO `psl_section_block_lut` VALUES (953, 3, 12);
INSERT INTO `psl_section_block_lut` VALUES (931, 54, 20);
INSERT INTO `psl_section_block_lut` VALUES (930, 54, 18);
INSERT INTO `psl_section_block_lut` VALUES (929, 54, 17);
INSERT INTO `psl_section_block_lut` VALUES (928, 54, 16);
INSERT INTO `psl_section_block_lut` VALUES (927, 54, 23);
INSERT INTO `psl_section_block_lut` VALUES (926, 54, 22);
INSERT INTO `psl_section_block_lut` VALUES (925, 54, 21);
INSERT INTO `psl_section_block_lut` VALUES (924, 54, 15);
INSERT INTO `psl_section_block_lut` VALUES (923, 54, 14);
INSERT INTO `psl_section_block_lut` VALUES (922, 54, 13);
INSERT INTO `psl_section_block_lut` VALUES (921, 54, 12);
INSERT INTO `psl_section_block_lut` VALUES (920, 54, 24);
INSERT INTO `psl_section_block_lut` VALUES (919, 54, 11);
INSERT INTO `psl_section_block_lut` VALUES (918, 54, 3);
INSERT INTO `psl_section_block_lut` VALUES (917, 54, 1);
INSERT INTO `psl_section_block_lut` VALUES (776, 45, 16);
INSERT INTO `psl_section_block_lut` VALUES (777, 45, 17);
INSERT INTO `psl_section_block_lut` VALUES (778, 45, 18);
INSERT INTO `psl_section_block_lut` VALUES (779, 45, 20);
INSERT INTO `psl_section_block_lut` VALUES (1049, 44, 15);
INSERT INTO `psl_section_block_lut` VALUES (1008, 51, 20);
INSERT INTO `psl_section_block_lut` VALUES (1007, 51, 18);
INSERT INTO `psl_section_block_lut` VALUES (1006, 51, 17);
INSERT INTO `psl_section_block_lut` VALUES (1005, 51, 16);
INSERT INTO `psl_section_block_lut` VALUES (1004, 51, 23);
INSERT INTO `psl_section_block_lut` VALUES (1003, 51, 22);
INSERT INTO `psl_section_block_lut` VALUES (1002, 51, 21);
INSERT INTO `psl_section_block_lut` VALUES (1001, 51, 15);
INSERT INTO `psl_section_block_lut` VALUES (1000, 51, 14);
INSERT INTO `psl_section_block_lut` VALUES (999, 51, 13);
INSERT INTO `psl_section_block_lut` VALUES (998, 51, 12);
INSERT INTO `psl_section_block_lut` VALUES (997, 51, 24);
INSERT INTO `psl_section_block_lut` VALUES (996, 51, 11);
INSERT INTO `psl_section_block_lut` VALUES (995, 51, 3);
INSERT INTO `psl_section_block_lut` VALUES (994, 51, 2);
INSERT INTO `psl_section_block_lut` VALUES (993, 51, 1);
INSERT INTO `psl_section_block_lut` VALUES (982, 10, 12);
INSERT INTO `psl_section_block_lut` VALUES (981, 10, 24);
INSERT INTO `psl_section_block_lut` VALUES (978, 50, 20);
INSERT INTO `psl_section_block_lut` VALUES (977, 50, 18);
INSERT INTO `psl_section_block_lut` VALUES (976, 50, 17);
INSERT INTO `psl_section_block_lut` VALUES (975, 50, 16);
INSERT INTO `psl_section_block_lut` VALUES (974, 50, 23);
INSERT INTO `psl_section_block_lut` VALUES (973, 50, 22);
INSERT INTO `psl_section_block_lut` VALUES (972, 50, 21);
INSERT INTO `psl_section_block_lut` VALUES (971, 50, 15);
INSERT INTO `psl_section_block_lut` VALUES (970, 50, 14);
INSERT INTO `psl_section_block_lut` VALUES (969, 50, 13);
INSERT INTO `psl_section_block_lut` VALUES (968, 50, 12);
INSERT INTO `psl_section_block_lut` VALUES (967, 50, 24);
INSERT INTO `psl_section_block_lut` VALUES (966, 50, 11);
INSERT INTO `psl_section_block_lut` VALUES (965, 50, 3);
INSERT INTO `psl_section_block_lut` VALUES (964, 50, 1);
INSERT INTO `psl_section_block_lut` VALUES (1039, 57, 18);
INSERT INTO `psl_section_block_lut` VALUES (1038, 57, 17);
INSERT INTO `psl_section_block_lut` VALUES (1037, 57, 16);
INSERT INTO `psl_section_block_lut` VALUES (1036, 57, 23);
INSERT INTO `psl_section_block_lut` VALUES (1035, 57, 22);
INSERT INTO `psl_section_block_lut` VALUES (1034, 57, 21);
INSERT INTO `psl_section_block_lut` VALUES (1033, 57, 15);
INSERT INTO `psl_section_block_lut` VALUES (1032, 57, 14);
INSERT INTO `psl_section_block_lut` VALUES (1031, 57, 13);
INSERT INTO `psl_section_block_lut` VALUES (1030, 57, 12);
INSERT INTO `psl_section_block_lut` VALUES (1029, 57, 24);
INSERT INTO `psl_section_block_lut` VALUES (1028, 57, 11);
INSERT INTO `psl_section_block_lut` VALUES (1027, 57, 3);
INSERT INTO `psl_section_block_lut` VALUES (1026, 57, 2);
INSERT INTO `psl_section_block_lut` VALUES (1025, 57, 1);
INSERT INTO `psl_section_block_lut` VALUES (952, 3, 24);
INSERT INTO `psl_section_block_lut` VALUES (951, 3, 11);
INSERT INTO `psl_section_block_lut` VALUES (950, 3, 3);
INSERT INTO `psl_section_block_lut` VALUES (949, 3, 2);
INSERT INTO `psl_section_block_lut` VALUES (948, 3, 1);
INSERT INTO `psl_section_block_lut` VALUES (983, 10, 13);
INSERT INTO `psl_section_block_lut` VALUES (984, 10, 14);
INSERT INTO `psl_section_block_lut` VALUES (985, 10, 15);
INSERT INTO `psl_section_block_lut` VALUES (986, 10, 21);
INSERT INTO `psl_section_block_lut` VALUES (987, 10, 22);
INSERT INTO `psl_section_block_lut` VALUES (988, 10, 23);
INSERT INTO `psl_section_block_lut` VALUES (989, 10, 16);
INSERT INTO `psl_section_block_lut` VALUES (990, 10, 17);
INSERT INTO `psl_section_block_lut` VALUES (991, 10, 18);
INSERT INTO `psl_section_block_lut` VALUES (992, 10, 20);
INSERT INTO `psl_section_block_lut` VALUES (1050, 44, 21);
INSERT INTO `psl_section_block_lut` VALUES (1051, 44, 22);
INSERT INTO `psl_section_block_lut` VALUES (1052, 44, 23);
INSERT INTO `psl_section_block_lut` VALUES (1053, 44, 16);
INSERT INTO `psl_section_block_lut` VALUES (1054, 44, 17);
INSERT INTO `psl_section_block_lut` VALUES (1055, 44, 18);
INSERT INTO `psl_section_block_lut` VALUES (1056, 44, 20);


# --------------------------------------------------------

# Optimize Tables
OPTIMIZE TABLE
`CACHEDATA` ,
`UidNumber` ,
`active_sessions` ,
`active_sessions_split` ,
`auth_user` ,
`auth_user_md5` ,
# `be_action` ,
# `be_action2contact` ,
# `be_action2section` ,
# `be_actionText` ,
# `be_actionType` ,
`be_article2section` ,
`be_articleText` ,
`be_articles` ,
# `be_bib` ,
# `be_bib2category` ,
# `be_bib2country` ,
# `be_bib2keywords` ,
# `be_bib2profile2role` ,
# `be_bib2region` ,
# `be_bibMLA` ,
# `be_bib_category` ,
# `be_bib_country` ,
# `be_bib_language` ,
# `be_bib_region` ,
# `be_bib_types` ,
`be_categories` ,
`be_category2item` ,
# `be_contact` ,
# `be_contactType` ,
# `be_country2region` ,
`be_event` ,
`be_eventText` ,
# `be_followup` ,
# `be_followup2contact` ,
# `be_followup2group` ,
`be_image2section` ,
`be_imageText` ,
`be_images` ,
# `be_keywords` ,
`be_keyword2article` ,
`be_language` ,
`be_link` ,
`be_link2articlesGroup` ,
`be_link2articlesGroupText` ,
`be_link2section` ,
`be_linkText` ,
`be_linkTextValidation` ,
# `be_profession` ,
# `be_profile` ,
# `be_profile2category` ,
# `be_profile2country` ,
# `be_profile2keywords` ,
# `be_profile2nationality` ,
# `be_profile2profession` ,
# `be_profile2region` ,
# `be_profile2spokenLanguages` ,
# `be_profile2upload` ,
# `be_profile_keywords` ,
# `be_profile_photo` ,
# `be_profile_role` ,
# `be_publisher` ,
`be_rsstool` ,
`be_section2section` ,
`be_sectionText` ,
`be_sections` ,
`be_subsite_block_lut` ,
`be_subsite_types` ,
`be_subsites` ,
# `be_target` ,
# `be_targetType` ,
`be_upload` ,
`db_sequence` ,
# `pet_alert` ,
# `pet_country` ,
# `pet_data` ,
# `pet_letters` ,
# `pet_main` ,
# `pet_petition` ,
# `pet_petition2contact` ,
# `pet_petition2section` ,
# `pet_petitionText` ,
`psl_author` ,
`psl_author_group_lut` ,
`psl_block` ,
`psl_blockText` ,
`psl_block_type` ,
`psl_comment` ,
`psl_commentcount` ,
`psl_glossary` ,
`psl_group` ,
`psl_group_group_lut` ,
`psl_group_permission_lut` ,
`psl_group_section_lut` ,
`psl_infolog` ,
`psl_mailinglist` ,
`psl_mailinglist_frequency` ,
`psl_permission` ,
`psl_poll_answer` ,
`psl_poll_question` ,
`psl_poll_voter` ,
`psl_quote` ,
`psl_section` ,
`psl_section_block_lut` ,
`psl_section_lut` ,
`psl_section_submission_lut` ,
`psl_story` ,
`psl_submission` ,
`psl_topic` ,
`psl_topic_lut` ,
`psl_topic_submission_lut` ,
`psl_variable` ;


# CATEGORIZATION OF ARTICLES ETC




# mg - commented this out as it wasn't working in the sql

# NOTE: The category_type values are currently hard-coded in BE_config.php
#       - it is up to you to make sure the values match
# INSERT INTO be_categories (category_type, category_code, languageID, name)
# VALUES
#   ('CATDIVN', 'CA', 'en', 'National'),
#   ('CATDIVN', 'AE', 'en', 'Airline'),
#   ('CATSECT','1','en','Airline'),
#   ('CATISSUE','1','en','Aboriginal'),
