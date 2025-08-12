

CREATE TABLE `blog` (
  `b_id` int(10) unsigned NOT NULL auto_increment,
  `name` varchar(50) NOT NULL default '',
  `title` varchar(250) NOT NULL default '',
  `brood` mediumtext NOT NULL,
  `date` timestamp NULL,
  PRIMARY KEY  (`b_id`)
) TYPE=MyISAM AUTO_INCREMENT=3 ;


CREATE TABLE `blog_comments` (
  `c_id` int(10) unsigned NOT NULL auto_increment,
  `blog_id` int(10) unsigned NOT NULL default '0',
  `name` varchar(170) NOT NULL default '',
  `email` varchar(170) NOT NULL default '',
  `url` varchar(255) NOT NULL default '',
  `comment` text NOT NULL,
  `date` timestamp NOT NULL,
  PRIMARY KEY  (`c_id`)
) TYPE=InnoDB AUTO_INCREMENT=53 ;


CREATE TABLE `container` (
  `id` smallint(5) unsigned NOT NULL auto_increment,
  `cfile` varchar(200) NOT NULL default '',
  `cname` varchar(200) NOT NULL default '',
  `level` smallint(6) NOT NULL default '0',
  `corder` tinyint(4) NOT NULL default '0',
  `uid` bigint(21) unsigned NOT NULL default '0',
  `inuseradmin` smallint(1) unsigned NOT NULL default '0',
  `generalview` smallint(1) unsigned NOT NULL default '0',
  `keywords` text NOT NULL,
  `description` text NOT NULL,
  `robot` varchar(30) NOT NULL default '',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM PACK_KEYS=1 AUTO_INCREMENT=37 ;


INSERT INTO `container` VALUES (1, '/useradmin.php', 'Users', 100, 10, 1, 0, 0, '', '', '');
INSERT INTO `container` VALUES (2, '/container.php', 'Containers', 1000, 60, 1, 0, 0, '', '', '');
INSERT INTO `container` VALUES (34, '/opentemplatecode.php', 'Page template generator', 1000, 71, 1, 0, 0, '', '', 'index, follow');
INSERT INTO `container` VALUES (4, '/images.php', 'Images', 10, 20, 1, 1, 0, '', '', '');
INSERT INTO `container` VALUES (5, '/plug_outputs.php', 'Downloads', 10, 50, 1, 1, 0, '', '', '');
INSERT INTO `container` VALUES (11, '/opentree.php', 'Pages', 10, 10, 1, 1, 1, '', '', '');
INSERT INTO `container` VALUES (12, '/opentemplate.php', 'Page templates', 1000, 70, 1, 0, 0, '', '', '');
INSERT INTO `container` VALUES (14, '/plug_news.php', 'News', 10, 30, 1, 1, 1, '', '', '');
INSERT INTO `container` VALUES (15, '/plug_links.php', 'Links', 10, 40, 1, 1, 1, '', '', '');
INSERT INTO `container` VALUES (16, '/plug_contact.php', 'Contacts', 10, 70, 1, 1, 0, '', '', '');
INSERT INTO `container` VALUES (36, '/blog_comments.php', 'Blog comments', 10, 23, 1, 1, 1, '', '', 'index, follow');
INSERT INTO `container` VALUES (19, '/../postlister/index.php', 'Mailing list', 10, 80, 1, 0, 0, '', '', '');
INSERT INTO `container` VALUES (22, '/linkscat.php', 'Links category', 10, 41, 1, 0, 0, '', '', '');
INSERT INTO `container` VALUES (23, '/../simple_report/index.php', 'Site statistics', 1000, 20, 1, 0, 0, '', '', '');
INSERT INTO `container` VALUES (24, '/opencontent.php', 'Content blocks', 10, 11, 1, 1, 1, '', '', '');
INSERT INTO `container` VALUES (26, '/../phpsearch/index.php', 'Search engine', 1000, 30, 1, 0, 0, '', '', '');
INSERT INTO `container` VALUES (27, '/wfb.php', 'Files', 10, 90, 1, 0, 0, '', '', '');
INSERT INTO `container` VALUES (31, '/nav.php', 'Navigation', 1000, 15, 1, 0, 0, '', '', 'index, follow');
INSERT INTO `container` VALUES (33, '/containercode.php', 'Container generator', 1000, 61, 1, 0, 0, '', '', 'index, follow');
INSERT INTO `container` VALUES (35, '/plug_blog.php', 'Blog', 10, 22, 1, 1, 1, '', '', 'index, follow');


CREATE TABLE `engine` (
  `spider_id` mediumint(9) NOT NULL default '0',
  `key_id` mediumint(9) NOT NULL default '0',
  `weight` smallint(4) NOT NULL default '0',
  KEY `key_id` (`key_id`)
) TYPE=MyISAM;



CREATE TABLE `excludes` (
  `ex_id` mediumint(11) NOT NULL auto_increment,
  `ex_site_id` mediumint(9) NOT NULL default '0',
  `ex_path` text NOT NULL,
  PRIMARY KEY  (`ex_id`),
  UNIQUE KEY `ex_id` (`ex_id`),
  KEY `ex_site_id` (`ex_site_id`)
) TYPE=MyISAM AUTO_INCREMENT=1 ;


CREATE TABLE `images` (
  `id` bigint(21) unsigned NOT NULL auto_increment,
  `banner` mediumblob NOT NULL,
  `width` smallint(6) NOT NULL default '0',
  `height` smallint(6) NOT NULL default '0',
  `format` varchar(200) NOT NULL default '',
  `name` varchar(100) NOT NULL default '',
  `description` varchar(255) default NULL,
  `uid` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM AUTO_INCREMENT=28 ;


CREATE TABLE `keywords` (
  `key_id` mediumint(9) NOT NULL auto_increment,
  `twoletters` char(2) NOT NULL default '',
  `keyword` varchar(64) NOT NULL default '',
  PRIMARY KEY  (`key_id`),
  UNIQUE KEY `keyword` (`keyword`),
  UNIQUE KEY `key_id` (`key_id`),
  KEY `twoletters` (`twoletters`)
) TYPE=MyISAM AUTO_INCREMENT=297 ;


CREATE TABLE `links_cat` (
  `cat_id` tinyint(3) unsigned NOT NULL auto_increment,
  `cat` varchar(150) NOT NULL default '',
  `pos` tinyint(3) unsigned NOT NULL default '0',
  PRIMARY KEY  (`cat_id`)
) TYPE=MyISAM AUTO_INCREMENT=5 ;

INSERT INTO `links_cat` VALUES (3, 'Webdesign', 1);
INSERT INTO `links_cat` VALUES (4, 'General', 2);

CREATE TABLE `logs` (
  `l_id` mediumint(9) NOT NULL auto_increment,
  `l_includes` varchar(255) NOT NULL default '',
  `l_excludes` varchar(127) default NULL,
  `l_num` mediumint(9) default NULL,
  `l_mode` char(1) default NULL,
  `l_ts` timestamp NOT NULL,
  `l_time` float NOT NULL default '0',
  PRIMARY KEY  (`l_id`),
  UNIQUE KEY `l_id` (`l_id`),
  KEY `l_includes` (`l_includes`),
  KEY `l_excludes` (`l_excludes`)
) TYPE=MyISAM AUTO_INCREMENT=1 ;


CREATE TABLE `mailspamstop` (
  `ip` char(15) NOT NULL default '',
  `time` datetime NOT NULL default '0000-00-00 00:00:00'
) TYPE=MyISAM;


CREATE TABLE `navigation` (
  `nav_id` smallint(5) unsigned NOT NULL auto_increment,
  `file_name` varchar(255) NOT NULL default '',
  `view_name` varchar(255) NOT NULL default '',
  `item` int(10) unsigned default NULL,
  `option_name` varchar(255) NOT NULL default '',
  `nav_name` varchar(255) NOT NULL default '',
  `top_nav_order` tinyint(1) unsigned NOT NULL default '0',
  `top_nav` tinyint(1) unsigned NOT NULL default '0',
  `bot_nav` tinyint(1) unsigned NOT NULL default '0',
  `robot` varchar(255) NOT NULL default '',
  `search` varchar(255) NOT NULL default '',
  `descrip` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`nav_id`)
) TYPE=MyISAM AUTO_INCREMENT=32 ;


INSERT INTO `navigation` VALUES (15, 'main_page.php', '', 0, '', 'Home', 1, 1, 0, 'index, follow', '', '');
INSERT INTO `navigation` VALUES (16, 'open_tree.php', '', 0, '', '*opentree*', 2, 1, 0, 'index, follow', '', '');
INSERT INTO `navigation` VALUES (17, 'news.php', 'news', 0, '', 'News', 3, 1, 0, 'index, follow', '', '');
INSERT INTO `navigation` VALUES (31, 'mailing_list.php', 'mailing_list', 0, '', 'Mailing list', 10, 0, 0, 'index, follow', '', '');
INSERT INTO `navigation` VALUES (19, 'outputs.php', 'outputs', 0, '', 'Downloads', 5, 1, 0, 'index, follow', '', '');
INSERT INTO `navigation` VALUES (20, 'search.php', 'search', 0, '', 'Search', 6, 0, 1, 'index, follow', '', '');
INSERT INTO `navigation` VALUES (21, 'contacts.php', 'contact', 0, '', 'Contact', 7, 0, 1, 'index, follow', '', '');
INSERT INTO `navigation` VALUES (22, 'links.php', 'links', 0, '', 'Links', 8, 0, 1, 'index, follow', '', '');
INSERT INTO `navigation` VALUES (23, 'sitemap.php', 'sitemap', 0, '', 'Site map', 9, 0, 1, 'index, follow', '', '');
INSERT INTO `navigation` VALUES (24, 'supplynews.php', 'supplynews', 0, '', 'Supply news', 10, 0, 1, 'index, follow', '', '');
INSERT INTO `navigation` VALUES (25, 'webuser.php', 'webuser', 0, '', 'Sign in or register', 10, 0, 0, 'index, follow', '', '');
INSERT INTO `navigation` VALUES (30, 'rss.php', 'rss', 0, '', 'RSS', 10, 0, 0, 'index, follow', '', '');
INSERT INTO `navigation` VALUES (29, 'blogs.php', 'blog', 0, '', 'Blog', 10, 1, 0, 'index, follow', '', '');


CREATE TABLE `opencontent` (
  `id` mediumint(8) unsigned NOT NULL auto_increment,
  `p_id` mediumint(8) unsigned NOT NULL default '0',
  `t_id` smallint(5) unsigned NOT NULL default '0',
  `position` smallint(5) unsigned NOT NULL default '0',
  `content` mediumtext NOT NULL,
  `page_title` varchar(50) NOT NULL default '',
  `nav_title` varchar(50) NOT NULL default '',
  `linkdoc` tinyint(3) unsigned NOT NULL default '0',
  `v_id` mediumint(8) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM AUTO_INCREMENT=24 ;


INSERT INTO `opencontent` VALUES (16, 0, 2, 0, 'a:1:{s:14:"opentreefielda";s:309:"<H1>Jetbox</H1>\r\n<P>This is the front page. It holds 4 different items: Latest news, latest results/outputs, Upcoming events & this block.</p>\r\n<p>To change the contents of this block, login to Jetbox and select ''Content blocks'' from the main left menu, select ''Frontpage item'' from the list on the right.</p>";}', 'Frontpage item', '', 0, 0);
INSERT INTO `opencontent` VALUES (17, 0, 2, 0, 'a:1:{s:14:"opentreefielda";s:278:"<p>Streamedge designs, develops and implements full solutions for internet and intranet.\r\n<p>We focus on:<br>\r\n» Standardize and create uniform online communication.<br>\r\n» Bring about credible and professional irradiation.<br>\r\n» Contribute to the company process and result.\r\n";}', 'About Streamedge', '', 0, 0);
INSERT INTO `opencontent` VALUES (22, 0, 3, 0, 'a:3:{s:14:"opentreefielda";s:18:"Activity highlight";s:14:"opentreefieldb";s:302:"<H3>Jetbox one includes a:</H3>\r\n<p>Content management system<br>\r\nFull intagrated mailing list (Postlister)<br>\r\nSite statistics (phpOpenTracker)<br>\r\nUser registration module</p>\r\n<H3>Coming up in next release:</H3>\r\n<p>Search engine (phpDig)<br>\r\nDocument management system (Web file browser) </p>\r\n";s:14:"opentreefieldc";s:0:"";}', 'Activities', '', 0, 0);


CREATE TABLE `opentempl` (
  `id` smallint(5) unsigned NOT NULL auto_increment,
  `t_name` varchar(255) NOT NULL default '',
  `t_descrip` varchar(255) NOT NULL default '',
  `t_img` varchar(255) NOT NULL default '',
  `t_data` mediumtext NOT NULL,
  `t_file` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM AUTO_INCREMENT=4 ;


INSERT INTO `opentempl` VALUES (1, 'Leader, content, background', 'Template one', '', 'a:3:{i:0;a:5:{i:0;s:14:"opentreefielda";i:1;s:6:"string";i:2;s:6:"Leader";i:3;s:0:"";i:4;s:0:"";}i:1;a:5:{i:0;s:14:"opentreefieldb";i:1;s:8:"richblob";i:2;s:7:"Content";i:3;s:0:"";i:4;s:0:"";}i:2;a:5:{i:0;s:14:"opentreefieldc";i:1;s:8:"richblob";i:2;s:10:"Background";i:3;s:0:"";i:4;s:0:"";}}', 'opentemplate/a.html');
INSERT INTO `opentempl` VALUES (2, 'Plain, content field', 'Content field only.', '', 'a:1:{i:0;a:5:{i:0;s:14:"opentreefielda";i:1;s:8:"richblob";i:2;s:7:"Content";i:3;s:0:"";i:4;s:0:"";}}', 'opentemplate/plain.html');
INSERT INTO `opentempl` VALUES (3, 'header, 2 content', 'header, 2 content', '', 'a:3:{i:0;a:5:{i:0;s:14:"opentreefielda";i:1;s:6:"string";i:2;s:6:"Header";i:3;s:0:"";i:4;s:0:"";}i:1;a:5:{i:0;s:14:"opentreefieldb";i:1;s:8:"richblob";i:2;s:10:"Contents 1";i:3;s:0:"";i:4;s:0:"";}i:2;a:5:{i:0;s:14:"opentreefieldc";i:1;s:8:"richblob";i:2;s:10:"Contents 2";i:3;s:0:"";i:4;s:0:"";}}', 'opentemplate/b.html');


CREATE TABLE `opentree` (
  `id` mediumint(8) unsigned NOT NULL auto_increment,
  `p_id` mediumint(8) unsigned NOT NULL default '0',
  `t_id` smallint(5) unsigned NOT NULL default '0',
  `position` smallint(5) unsigned NOT NULL default '0',
  `content` mediumtext NOT NULL,
  `page_title` varchar(50) NOT NULL default '',
  `nav_title` varchar(50) NOT NULL default '',
  `linkdoc` tinyint(3) unsigned NOT NULL default '0',
  `v_id` mediumint(8) unsigned NOT NULL default '0',
  `top_nav` tinyint(1) unsigned NOT NULL default '1',
  `left_nav` tinyint(3) unsigned NOT NULL default '1',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM AUTO_INCREMENT=44 ;

INSERT INTO `opentree` VALUES (8, 0, 2, 0, 'a:1:{s:14:"opentreefielda";s:242:"This is the start of the page tree, this page is visible in the main navigation.<br/> To change to contents of this page: login to the Jetbox and select ''Pages'' from the main left navigation. Select ''highest level'' from the tree on the right.";}', 'highest level', 'highest level', 0, 0, 1, 1);
INSERT INTO `opentree` VALUES (39, 0, 1, 0, 'a:3:{s:14:"opentreefielda";s:0:"";s:14:"opentreefieldb";s:0:"";s:14:"opentreefieldc";s:0:"";}', '', 'jhj', 0, 0, 1, 1);
INSERT INTO `opentree` VALUES (40, 0, 1, 0, 'a:3:{s:14:"opentreefielda";s:0:"";s:14:"opentreefieldb";s:4:"sfdg";s:14:"opentreefieldc";s:0:"";}', 'sdf', 'gsdfg', 0, 0, 1, 1);
INSERT INTO `opentree` VALUES (41, 0, 2, 56, 'a:1:{s:14:"opentreefielda";s:9:"sfdgsdfgs";}', 'dsfg', 'sdfgsdf', 0, 0, 1, 1);
INSERT INTO `opentree` VALUES (42, 0, 1, 0, 'a:3:{s:14:"opentreefielda";s:0:"";s:14:"opentreefieldb";s:0:"";s:14:"opentreefieldc";s:0:"";}', '', 'okY', 0, 0, 1, 1);
INSERT INTO `opentree` VALUES (43, 0, 1, 10, 'a:3:{s:14:"opentreefielda";s:4:"Test";s:14:"opentreefieldb";s:4:"test";s:14:"opentreefieldc";s:43:"<p>!!function_date(&quot;d-m-Y&quot;)!!</p>";}', 'test', 'test', 0, 0, 1, 1);

CREATE TABLE `plug_contact` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `p_id` int(10) unsigned NOT NULL default '0',
  `function` varchar(255) NOT NULL default '',
  `name` varchar(255) NOT NULL default '',
  `institute` varchar(255) NOT NULL default '',
  `address` blob NOT NULL,
  `country` varchar(150) NOT NULL default '',
  `phone` varchar(60) NOT NULL default '',
  `url` varchar(255) NOT NULL default '',
  `email` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM AUTO_INCREMENT=9 ;


CREATE TABLE `plug_event` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `p_id` int(10) unsigned NOT NULL default '0',
  `name` varchar(100) NOT NULL default '',
  `location` varchar(100) NOT NULL default '',
  `date` datetime NOT NULL default '0000-00-00 00:00:00',
  `subject` text NOT NULL,
  `contact` varchar(100) NOT NULL default '',
  `email` varchar(100) NOT NULL default '',
  `phone` varchar(20) NOT NULL default '',
  `url` varchar(100) NOT NULL default '',
  `signin` tinyint(1) NOT NULL default '1',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM AUTO_INCREMENT=13 ;


CREATE TABLE `plug_links` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `p_id` int(10) unsigned NOT NULL default '0',
  `cat_id` tinyint(3) unsigned NOT NULL default '0',
  `name` varchar(200) NOT NULL default '',
  `url` varchar(200) NOT NULL default '',
  `descrip` text NOT NULL,
  PRIMARY KEY  (`id`)
) TYPE=MyISAM AUTO_INCREMENT=57 ;


CREATE TABLE `plug_news` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `p_id` int(10) unsigned NOT NULL default '0',
  `title` varchar(250) NOT NULL default '',
  `brood` mediumtext NOT NULL,
  `date` datetime NOT NULL default '0000-00-00 00:00:00',
  `author` varchar(200) NOT NULL default '',
  `intern` tinyint(1) unsigned NOT NULL default '0',
  `extern` tinyint(1) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM AUTO_INCREMENT=36 ;


CREATE TABLE `plug_outputs` (
  `id` mediumint(8) unsigned NOT NULL auto_increment,
  `p_id` int(10) unsigned NOT NULL default '0',
  `author` varchar(200) NOT NULL default '',
  `title` varchar(200) NOT NULL default '',
  `date` date NOT NULL default '0000-00-00',
  `summary` text NOT NULL,
  `filename` varchar(250) NOT NULL default '',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM AUTO_INCREMENT=43 ;


CREATE TABLE `postlistermain` (
  `liste` varchar(20) default NULL,
  `tilmeldingsbesked` mediumtext,
  `afmeldingsbesked` mediumtext,
  `standardafsender` varchar(100) default NULL,
  `signatur` mediumtext,
  `afsender` varchar(100) default NULL,
  `emne` varchar(100) default NULL,
  `ebrev` mediumtext,
  `date` datetime NOT NULL default '0000-00-00 00:00:00'
) TYPE=MyISAM;


INSERT INTO `postlistermain` VALUES ('default', 'You have received this email because you or somebody else\r\nhas subscribed you to the mailing list default at\r\nhttp://.\r\nBefore we can add your email address to our mailing list we need to\r\nmake sure that the email address exists and is working, and that you\r\nactually want to subscribe to our mailing list. Therefore, we ask you\r\nto confirm your subscription by visiting the following URL:\r\n\r\n<[SUBSCRIBE_URL]>\r\n\r\nThank you.', 'You have received this email because you or somebody else\r\nhas unsubscribed you from the mailing list default at\r\nhttp://.\r\nBefore we can remove your email address from our mailing list we need\r\nto make sure that you, the owner of the email address, actually want to\r\nbe removed from the list. Therefore, we ask you to visit the following\r\nURL in order to confirm your unsubscription request:\r\n\r\n<[UNSUBSCRIBE_URL]>\r\n\r\nThank you.', NULL, NULL, NULL, NULL, NULL, '0000-00-00 00:00:00');


CREATE TABLE `pot_accesslog` (
  `accesslog_id` int(11) NOT NULL default '0',
  `timestamp` int(10) unsigned NOT NULL default '0',
  `weekday` tinyint(1) unsigned NOT NULL default '0',
  `hour` tinyint(2) unsigned NOT NULL default '0',
  `document_id` int(11) NOT NULL default '0',
  `exit_target_id` int(11) NOT NULL default '0',
  `entry_document` tinyint(1) unsigned NOT NULL default '0',
  KEY `accesslog_id` (`accesslog_id`),
  KEY `timestamp` (`timestamp`),
  KEY `document_id` (`document_id`),
  KEY `exit_target_id` (`exit_target_id`)
) TYPE=MyISAM DELAY_KEY_WRITE=1;


CREATE TABLE `pot_add_data` (
  `accesslog_id` int(11) NOT NULL default '0',
  `data_field` varchar(32) NOT NULL default '',
  `data_value` varchar(255) NOT NULL default '',
  KEY `accesslog_id` (`accesslog_id`)
) TYPE=MyISAM DELAY_KEY_WRITE=1;


CREATE TABLE `pot_country` (
  `accesslog_id` int(11) NOT NULL default '0',
  `code2` char(2) NOT NULL default ''
) TYPE=MyISAM;


CREATE TABLE `pot_documents` (
  `data_id` int(11) NOT NULL default '0',
  `string` varchar(255) NOT NULL default '',
  `document_url` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`data_id`)
) TYPE=MyISAM DELAY_KEY_WRITE=1;


CREATE TABLE `pot_exit_targets` (
  `data_id` int(11) NOT NULL default '0',
  `string` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`data_id`)
) TYPE=MyISAM DELAY_KEY_WRITE=1;


CREATE TABLE `pot_hostnames` (
  `data_id` int(11) NOT NULL default '0',
  `string` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`data_id`)
) TYPE=MyISAM DELAY_KEY_WRITE=1;


CREATE TABLE `pot_iptoc` (
  `ipf` double NOT NULL default '0',
  `ipt` double NOT NULL default '0',
  `code2` char(2) NOT NULL default '',
  `code3` char(3) NOT NULL default '',
  `country` varchar(50) default NULL,
  KEY `code2` (`code2`)
) TYPE=MyISAM;


CREATE TABLE `pot_operating_systems` (
  `data_id` int(11) NOT NULL default '0',
  `string` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`data_id`)
) TYPE=MyISAM DELAY_KEY_WRITE=1;


CREATE TABLE `pot_referers` (
  `data_id` int(11) NOT NULL default '0',
  `string` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`data_id`)
) TYPE=MyISAM DELAY_KEY_WRITE=1;


CREATE TABLE `pot_search_engines` (
  `accesslog_id` int(11) NOT NULL default '0',
  `search_engine` varchar(64) NOT NULL default '',
  `keywords` varchar(254) NOT NULL default '',
  PRIMARY KEY  (`accesslog_id`)
) TYPE=MyISAM DELAY_KEY_WRITE=1;


CREATE TABLE `pot_user_agents` (
  `data_id` int(11) NOT NULL default '0',
  `string` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`data_id`)
) TYPE=MyISAM DELAY_KEY_WRITE=1;


CREATE TABLE `pot_visitors` (
  `accesslog_id` int(11) NOT NULL default '0',
  `visitor_id` int(11) NOT NULL default '0',
  `client_id` int(10) unsigned NOT NULL default '0',
  `operating_system_id` int(11) NOT NULL default '0',
  `user_agent_id` int(11) NOT NULL default '0',
  `host_id` int(11) NOT NULL default '0',
  `referer_id` int(11) NOT NULL default '0',
  `timestamp` int(10) unsigned NOT NULL default '0',
  `weekday` tinyint(1) unsigned NOT NULL default '0',
  `hour` tinyint(2) unsigned NOT NULL default '0',
  `returning_visitor` tinyint(1) unsigned NOT NULL default '0',
  PRIMARY KEY  (`accesslog_id`),
  KEY `client_time` (`client_id`,`timestamp`)
) TYPE=MyISAM DELAY_KEY_WRITE=1;


CREATE TABLE `ppuploads` (
  `id` bigint(21) unsigned NOT NULL auto_increment,
  `content` mediumblob NOT NULL,
  `filename` varchar(255) NOT NULL default '',
  `width` smallint(6) NOT NULL default '0',
  `height` smallint(6) NOT NULL default '0',
  `mime` varchar(100) NOT NULL default '',
  `name` varchar(100) NOT NULL default '',
  `description` varchar(255) default NULL,
  PRIMARY KEY  (`id`)
) TYPE=MyISAM AUTO_INCREMENT=1 ;


CREATE TABLE `sessions` (
  `sesskey` varchar(32) NOT NULL default '',
  `expiry` int(11) unsigned NOT NULL default '0',
  `value` text NOT NULL,
  PRIMARY KEY  (`sesskey`)
) TYPE=MyISAM;


CREATE TABLE `sites` (
  `site_id` mediumint(9) NOT NULL auto_increment,
  `site_url` varchar(127) NOT NULL default '',
  `upddate` timestamp NOT NULL,
  `username` varchar(32) default NULL,
  `password` varchar(32) default NULL,
  `port` smallint(6) default NULL,
  `locked` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`site_id`),
  UNIQUE KEY `site_id` (`site_id`)
) TYPE=MyISAM AUTO_INCREMENT=4 ;


CREATE TABLE `spider` (
  `spider_id` mediumint(9) NOT NULL auto_increment,
  `file` varchar(127) NOT NULL default '',
  `first_words` text NOT NULL,
  `upddate` timestamp NOT NULL,
  `md5` varchar(50) default NULL,
  `site_id` mediumint(9) NOT NULL default '0',
  `path` varchar(127) NOT NULL default '',
  `num_words` int(11) NOT NULL default '1',
  `last_modified` timestamp NOT NULL default '0000-00-00 00:00:00',
  `filesize` int(11) NOT NULL default '0',
  PRIMARY KEY  (`spider_id`),
  UNIQUE KEY `spider_id` (`spider_id`),
  KEY `site_id` (`site_id`)
) TYPE=MyISAM AUTO_INCREMENT=47 ;


CREATE TABLE `struct` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `keywords` text NOT NULL,
  `description` text NOT NULL,
  `robot` varchar(30) NOT NULL default '',
  `ondate` datetime NOT NULL default '0000-00-00 00:00:00',
  `offdate` datetime NOT NULL default '0000-00-00 00:00:00',
  `status` enum('saved','waiting','declined','published','deleted','archive') NOT NULL default 'saved',
  `lock_id` varchar(100) NOT NULL default '0',
  `stamp` timestamp NOT NULL,
  `container_id` tinyint(3) unsigned NOT NULL default '0',
  `content_id` smallint(6) unsigned NOT NULL default '0',
  `template` varchar(100) NOT NULL default '',
  `u_id` varchar(100) NOT NULL default '',
  `comment` text NOT NULL,
  `systemtitle` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM AUTO_INCREMENT=250 ;


INSERT INTO `struct` VALUES (232, '', '', 'index,follow', '2005-10-05 17:10:00', '2015-10-03 17:10:00', 'published', '0', '2005-10-05 17:08:27', 11, 40, '', '1', '', 'gsdfg');
INSERT INTO `struct` VALUES (233, '', '', 'index,follow', '2005-10-05 17:10:00', '2015-10-03 17:10:00', 'archive', '0', '2005-10-05 17:08:49', 11, 41, '', '1', '', 'sdfgsdf');
INSERT INTO `struct` VALUES (245, '', '', 'index,follow', '2005-10-05 23:10:00', '2015-10-03 23:10:00', 'published', '0', '2005-10-05 23:11:01', 11, 42, '', '1', '', 'okY');
INSERT INTO `struct` VALUES (249, '', '', 'index,follow', '2005-10-10 16:40:00', '2015-10-08 16:40:00', 'published', '0', '2005-10-10 16:42:34', 11, 43, '', '1', '', 'test');


CREATE TABLE `tempspider` (
  `file` text NOT NULL,
  `id` mediumint(11) NOT NULL auto_increment,
  `level` tinyint(6) NOT NULL default '0',
  `path` text NOT NULL,
  `site_id` mediumint(9) NOT NULL default '0',
  `indexed` tinyint(1) NOT NULL default '0',
  `upddate` timestamp NOT NULL,
  `error` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `id` (`id`),
  KEY `site_id` (`site_id`)
) TYPE=MyISAM AUTO_INCREMENT=347 ;


CREATE TABLE `user` (
  `uid` bigint(21) unsigned NOT NULL auto_increment,
  `session` varchar(50) default NULL,
  `login` varchar(50) default NULL,
  `user_password` varchar(50) default NULL,
  `email` varchar(50) NOT NULL default '',
  `type` enum('administrator','user') default NULL,
  `display_name` varchar(50) default NULL,
  `visit` smallint(5) unsigned default NULL,
  `history` timestamp NOT NULL,
  `active` tinyint(1) unsigned NOT NULL default '0',
  PRIMARY KEY  (`uid`),
  KEY `email` (`email`)
) TYPE=MyISAM AUTO_INCREMENT=4 ;


INSERT INTO `user` VALUES (1, NULL, 'admin', 'admin1', 'admin@localhost', 'administrator', 'Administrator', 1135, '2005-10-13 00:08:15', 1);


CREATE TABLE `userrights` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `uid` bigint(20) NOT NULL default '0',
  `container_id` smallint(6) NOT NULL default '0',
  `type` enum('administrator','author','editor') NOT NULL default 'author',
  `history` timestamp NOT NULL,
  PRIMARY KEY  (`id`)
) TYPE=MyISAM AUTO_INCREMENT=46 ;


CREATE TABLE `webuser` (
  `uid` bigint(21) unsigned NOT NULL auto_increment,
  `login` varchar(50) default NULL,
  `user_password` varchar(50) default NULL,
  `email` varchar(50) NOT NULL default '',
  `firstname` varchar(80) default NULL,
  `middlename` varchar(20) NOT NULL default '',
  `lastname` varchar(80) NOT NULL default '',
  `companyname` varchar(80) NOT NULL default '',
  `companyfunction` varchar(80) NOT NULL default '',
  `address` varchar(80) NOT NULL default '',
  `address2` varchar(80) NOT NULL default '',
  `address3` varchar(80) NOT NULL default '',
  `city` varchar(50) NOT NULL default '',
  `state` varchar(50) NOT NULL default '',
  `zip` varchar(10) NOT NULL default '',
  `country` varchar(50) NOT NULL default '',
  `workphone` varchar(20) NOT NULL default '',
  `homephone` varchar(20) NOT NULL default '',
  `newsmail` tinyint(1) unsigned NOT NULL default '0',
  `eventmail` tinyint(1) unsigned NOT NULL default '0',
  `internalmail` tinyint(1) unsigned NOT NULL default '0',
  `visit` smallint(5) unsigned default NULL,
  `history` timestamp NOT NULL,
  PRIMARY KEY  (`uid`)
) TYPE=MyISAM AUTO_INCREMENT=2 ;