<?
//phpinfo();

//if (!defined('VALID_PAGE')) {
//	header("Location: ../index.php"); /* Redirect browser */
//	/* Make sure that code below does not get executed when we redirect. */
//	echo "&nbsp;";
//	exit;
//}

include ("../includes/f_includes.inc.php");
//$install_dir_count = substr_count($install_dir, '/');
//$url_to_split=explode("?",$_SERVER["REQUEST_URI"]);
//$url = explode("/",$url_to_split[0]);  // Splits URL into array
//$install_dir_count+=3;
//$_URL[$url[$install_dir_count]]=$url[$install_dir_count+1];
// the directory of the website. default: /
//$absolutepath=$install_dir."/";
// the file that processes all requests. dafault: $absolutepath."index.php/"  
//$absolutepathfull=$absolutepath."index.php/";
//Phplib template system

$t = new Template(".");
$t->set_var("absolutepath", $absolutepath);
$t->set_var("absolutepathfull", $absolutepathfull);
$t->set_var("pagetitle", $sitename);
$t->set_file("block", "t_install.html");
$t->set_var("baseurl", "<base href=\"".$front_end_url."../\">");

$_REQUEST["install_type"]='newinstall';

if ($install_jetbox==true && $_REQUEST["install_type"]=='newinstall') {
	if($_REQUEST["step"] == "step3"){
		//select tables form database for check
		$result  = mysql_query('SHOW TABLE STATUS FROM ' . $database);
		$tablecount = mysql_num_rows($result);
		if($tablecount=='0'){
			$_REQUEST["step"]="step4";
			$_REQUEST["overwrite"]==false;
		}
	}

	if(!isset($_REQUEST["step"])){
		if (strstr( PHP_OS, 'WIN') ) {
			$_REQUEST["step"] = "step1";
		}
	}


	if($_REQUEST["step"] == "step4"){
		$message.="<h3>Final Installation Steps</h3><p>";
		if ($_REQUEST["overwrite"]==true) {
			mysql_query("DROP TABLE IF EXISTS `container`");
			mysql_query("DROP TABLE IF EXISTS `engine`");
			mysql_query("DROP TABLE IF EXISTS `excludes`");
			mysql_query("DROP TABLE IF EXISTS `images`");
			mysql_query("DROP TABLE IF EXISTS `keywords`");
			mysql_query("DROP TABLE IF EXISTS `links_cat`");
			mysql_query("DROP TABLE IF EXISTS `logs`");
			mysql_query("DROP TABLE IF EXISTS `mailspamstop`");
			mysql_query("DROP TABLE IF EXISTS `opencontent`");
			mysql_query("DROP TABLE IF EXISTS `opentempl`");
			mysql_query("DROP TABLE IF EXISTS `opentree`");
			mysql_query("DROP TABLE IF EXISTS `plug_contact`");
			mysql_query("DROP TABLE IF EXISTS `plug_event`");
			mysql_query("DROP TABLE IF EXISTS `plug_links`");
			mysql_query("DROP TABLE IF EXISTS `plug_news`");
			mysql_query("DROP TABLE IF EXISTS `plug_outputs`");
			mysql_query("DROP TABLE IF EXISTS `postlistermain`");
			mysql_query("DROP TABLE IF EXISTS `pot_accesslog`");
			mysql_query("DROP TABLE IF EXISTS `pot_add_data`");
			mysql_query("DROP TABLE IF EXISTS `pot_country`");
			mysql_query("DROP TABLE IF EXISTS `pot_documents`");
			mysql_query("DROP TABLE IF EXISTS `pot_exit_targets`");
			mysql_query("DROP TABLE IF EXISTS `pot_hostnames`");
			mysql_query("DROP TABLE IF EXISTS `pot_iptoc`");
			mysql_query("DROP TABLE IF EXISTS `pot_operating_systems`");
			mysql_query("DROP TABLE IF EXISTS `pot_referers`");
			mysql_query("DROP TABLE IF EXISTS `pot_search_engines`");
			mysql_query("DROP TABLE IF EXISTS `pot_user_agents`");
			mysql_query("DROP TABLE IF EXISTS `pot_visitors`");
			mysql_query("DROP TABLE IF EXISTS `ppuploads`");
			mysql_query("DROP TABLE IF EXISTS `sessions`");
			mysql_query("DROP TABLE IF EXISTS `sites`");
			mysql_query("DROP TABLE IF EXISTS `spider`");
			mysql_query("DROP TABLE IF EXISTS `struct`");
			mysql_query("DROP TABLE IF EXISTS `tempspider`");
			mysql_query("DROP TABLE IF EXISTS `user`");
			mysql_query("DROP TABLE IF EXISTS `userrights`");
			mysql_query("DROP TABLE IF EXISTS `webuser`");
			mysql_query("DROP TABLE IF EXISTS `navigation`");
			
		}




@mysql_query("CREATE TABLE `blog` (
  `b_id` int(10) unsigned NOT NULL auto_increment,
  `name` varchar(50) NOT NULL default '',
  `title` varchar(250) NOT NULL default '',
  `brood` mediumtext NOT NULL,
  `date` timestamp NULL,
  PRIMARY KEY  (`b_id`)
) TYPE=MyISAM AUTO_INCREMENT=3 ");


@mysql_query("CREATE TABLE `blog_comments` (
  `c_id` int(10) unsigned NOT NULL auto_increment,
  `blog_id` int(10) unsigned NOT NULL default '0',
  `name` varchar(170) NOT NULL default '',
  `email` varchar(170) NOT NULL default '',
  `url` varchar(255) NOT NULL default '',
  `comment` text NOT NULL,
  `date` timestamp NOT NULL,
  PRIMARY KEY  (`c_id`)
) TYPE=InnoDB AUTO_INCREMENT=53 ");


@mysql_query("CREATE TABLE `container` (
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
) TYPE=MyISAM PACK_KEYS=1 AUTO_INCREMENT=37 ");


@mysql_query("INSERT INTO `container` VALUES (1, '/useradmin.php', 'Users', 100, 10, 1, 0, 0, '', '', '')");
@mysql_query("INSERT INTO `container` VALUES (2, '/container.php', 'Containers', 1000, 60, 1, 0, 0, '', '', '')");
@mysql_query("INSERT INTO `container` VALUES (34, '/opentemplatecode.php', 'Page template generator', 1000, 71, 1, 0, 0, '', '', 'index, follow')");
@mysql_query("INSERT INTO `container` VALUES (4, '/images.php', 'Images', 10, 20, 1, 1, 0, '', '', '')");
@mysql_query("INSERT INTO `container` VALUES (5, '/plug_outputs.php', 'Downloads', 10, 50, 1, 1, 0, '', '', '')");
@mysql_query("INSERT INTO `container` VALUES (11, '/opentree.php', 'Pages', 10, 10, 1, 1, 1, '', '', '')");
@mysql_query("INSERT INTO `container` VALUES (12, '/opentemplate.php', 'Page templates', 1000, 70, 1, 0, 0, '', '', '')");
@mysql_query("INSERT INTO `container` VALUES (14, '/plug_news.php', 'News', 10, 30, 1, 1, 1, '', '', '')");
@mysql_query("INSERT INTO `container` VALUES (15, '/plug_links.php', 'Links', 10, 40, 1, 1, 1, '', '', '')");
@mysql_query("INSERT INTO `container` VALUES (16, '/plug_contact.php', 'Contacts', 10, 70, 1, 1, 0, '', '', '')");
@mysql_query("INSERT INTO `container` VALUES (36, '/blog_comments.php', 'Blog comments', 10, 23, 1, 1, 1, '', '', 'index, follow')");
@mysql_query("INSERT INTO `container` VALUES (19, '/../postlister/index.php', 'Mailing list', 10, 80, 1, 0, 0, '', '', '')");
@mysql_query("INSERT INTO `container` VALUES (22, '/linkscat.php', 'Links category', 10, 41, 1, 0, 0, '', '', '')");
@mysql_query("INSERT INTO `container` VALUES (23, '/../simple_report/index.php', 'Site statistics', 1000, 20, 1, 0, 0, '', '', '')");
@mysql_query("INSERT INTO `container` VALUES (24, '/opencontent.php', 'Content blocks', 10, 11, 1, 1, 1, '', '', '')");
@mysql_query("INSERT INTO `container` VALUES (26, '/../phpsearch/index.php', 'Search engine', 1000, 30, 1, 0, 0, '', '', '')");
@mysql_query("INSERT INTO `container` VALUES (27, '/wfb.php', 'Files', 10, 90, 1, 0, 0, '', '', '')");
@mysql_query("INSERT INTO `container` VALUES (31, '/nav.php', 'Navigation', 1000, 15, 1, 0, 0, '', '', 'index, follow')");
@mysql_query("INSERT INTO `container` VALUES (33, '/containercode.php', 'Container generator', 1000, 61, 1, 0, 0, '', '', 'index, follow')");
@mysql_query("INSERT INTO `container` VALUES (35, '/plug_blog.php', 'Blog', 10, 22, 1, 1, 1, '', '', 'index, follow')");


@mysql_query("CREATE TABLE `engine` (
  `spider_id` mediumint(9) NOT NULL default '0',
  `key_id` mediumint(9) NOT NULL default '0',
  `weight` smallint(4) NOT NULL default '0',
  KEY `key_id` (`key_id`)
) TYPE=MyISAM");



@mysql_query("CREATE TABLE `excludes` (
  `ex_id` mediumint(11) NOT NULL auto_increment,
  `ex_site_id` mediumint(9) NOT NULL default '0',
  `ex_path` text NOT NULL,
  PRIMARY KEY  (`ex_id`),
  UNIQUE KEY `ex_id` (`ex_id`),
  KEY `ex_site_id` (`ex_site_id`)
) TYPE=MyISAM AUTO_INCREMENT=1 ");


@mysql_query("CREATE TABLE `images` (
  `id` bigint(21) unsigned NOT NULL auto_increment,
  `banner` mediumblob NOT NULL,
  `width` smallint(6) NOT NULL default '0',
  `height` smallint(6) NOT NULL default '0',
  `format` varchar(200) NOT NULL default '',
  `name` varchar(100) NOT NULL default '',
  `description` varchar(255) default NULL,
  `uid` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM AUTO_INCREMENT=28 ");


@mysql_query("CREATE TABLE `keywords` (
  `key_id` mediumint(9) NOT NULL auto_increment,
  `twoletters` char(2) NOT NULL default '',
  `keyword` varchar(64) NOT NULL default '',
  PRIMARY KEY  (`key_id`),
  UNIQUE KEY `keyword` (`keyword`),
  UNIQUE KEY `key_id` (`key_id`),
  KEY `twoletters` (`twoletters`)
) TYPE=MyISAM AUTO_INCREMENT=297 ");


@mysql_query("CREATE TABLE `links_cat` (
  `cat_id` tinyint(3) unsigned NOT NULL auto_increment,
  `cat` varchar(150) NOT NULL default '',
  `pos` tinyint(3) unsigned NOT NULL default '0',
  PRIMARY KEY  (`cat_id`)
) TYPE=MyISAM AUTO_INCREMENT=5 ");

@mysql_query("INSERT INTO `links_cat` VALUES (3, 'Webdesign', 1)");
@mysql_query("INSERT INTO `links_cat` VALUES (4, 'General', 2)");

@mysql_query("CREATE TABLE `logs` (
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
) TYPE=MyISAM AUTO_INCREMENT=1 ");


@mysql_query("CREATE TABLE `mailspamstop` (
  `ip` char(15) NOT NULL default '',
  `time` datetime NOT NULL default '0000-00-00 00:00:00'
) TYPE=MyISAM");


@mysql_query("CREATE TABLE `navigation` (
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
) TYPE=MyISAM AUTO_INCREMENT=32 ");


@mysql_query("INSERT INTO `navigation` VALUES (15, 'main_page.php', '', 0, '', 'Home', 1, 1, 0, 'index, follow', '', '')");
@mysql_query("INSERT INTO `navigation` VALUES (16, 'open_tree.php', '', 0, '', '*opentree*', 2, 1, 0, 'index, follow', '', '')");
@mysql_query("INSERT INTO `navigation` VALUES (17, 'news.php', 'news', 0, '', 'News', 3, 1, 0, 'index, follow', '', '')");
@mysql_query("INSERT INTO `navigation` VALUES (31, 'mailing_list.php', 'mailing_list', 0, '', 'Mailing list', 10, 0, 0, 'index, follow', '', '')");
@mysql_query("INSERT INTO `navigation` VALUES (19, 'outputs.php', 'outputs', 0, '', 'Downloads', 5, 1, 0, 'index, follow', '', '')");
@mysql_query("INSERT INTO `navigation` VALUES (20, 'search.php', 'search', 0, '', 'Search', 6, 0, 1, 'index, follow', '', '')");
@mysql_query("INSERT INTO `navigation` VALUES (21, 'contacts.php', 'contact', 0, '', 'Contact', 7, 0, 1, 'index, follow', '', '')");
@mysql_query("INSERT INTO `navigation` VALUES (22, 'links.php', 'links', 0, '', 'Links', 8, 0, 1, 'index, follow', '', '')");
@mysql_query("INSERT INTO `navigation` VALUES (23, 'sitemap.php', 'sitemap', 0, '', 'Site map', 9, 0, 1, 'index, follow', '', '')");
@mysql_query("INSERT INTO `navigation` VALUES (24, 'supplynews.php', 'supplynews', 0, '', 'Supply news', 10, 0, 1, 'index, follow', '', '')");
@mysql_query("INSERT INTO `navigation` VALUES (25, 'webuser.php', 'webuser', 0, '', 'Sign in or register', 10, 0, 0, 'index, follow', '', '')");
@mysql_query("INSERT INTO `navigation` VALUES (30, 'rss.php', 'rss', 0, '', 'RSS', 10, 0, 0, 'index, follow', '', '')");
@mysql_query("INSERT INTO `navigation` VALUES (29, 'blogs.php', 'blog', 0, '', 'Blog', 10, 1, 0, 'index, follow', '', '')");


@mysql_query("CREATE TABLE `opencontent` (
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
) TYPE=MyISAM AUTO_INCREMENT=24 ");


@mysql_query("INSERT INTO `opencontent` VALUES (16, 0, 2, 0, 'a:1:{s:14:\"opentreefielda\";s:309:\"<H1>Jetbox</H1>\r\n<P>This is the front page. It holds 4 different items: Latest news, latest results/outputs, Upcoming events & this block.</p>\r\n<p>To change the contents of this block, login to Jetbox and select ''Content blocks'' from the main left menu, select ''Frontpage item'' from the list on the right.</p>\";}', 'Frontpage item', '', 0, 0)");
@mysql_query("INSERT INTO `opencontent` VALUES (17, 0, 2, 0, 'a:1:{s:14:\"opentreefielda\";s:278:\"<p>Streamedge designs, develops and implements full solutions for internet and intranet.\r\n<p>We focus on:<br>\r\n» Standardize and create uniform online communication.<br>\r\n» Bring about credible and professional irradiation.<br>\r\n» Contribute to the company process and result.\r\n\";}', 'About Streamedge', '', 0, 0)");
@mysql_query("INSERT INTO `opencontent` VALUES (22, 0, 3, 0, 'a:3:{s:14:\"opentreefielda\";s:18:\"Activity highlight\";s:14:\"opentreefieldb\";s:302:\"<H3>Jetbox one includes a:</H3>\r\n<p>Content management system<br>\r\nFull intagrated mailing list (Postlister)<br>\r\nSite statistics (phpOpenTracker)<br>\r\nUser registration module</p>\r\n<H3>Coming up in next release:</H3>\r\n<p>Search engine (phpDig)<br>\r\nDocument management system (Web file browser) </p>\r\n\";s:14:\"opentreefieldc\";s:0:\"\";}', 'Activities', '', 0, 0)");


@mysql_query("CREATE TABLE `opentempl` (
  `id` smallint(5) unsigned NOT NULL auto_increment,
  `t_name` varchar(255) NOT NULL default '',
  `t_descrip` varchar(255) NOT NULL default '',
  `t_img` varchar(255) NOT NULL default '',
  `t_data` mediumtext NOT NULL,
  `t_file` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM AUTO_INCREMENT=4 ");


@mysql_query("INSERT INTO `opentempl` VALUES (1, 'Leader, content, background', 'Template one', '', 'a:3:{i:0;a:5:{i:0;s:14:\"opentreefielda\";i:1;s:6:\"string\";i:2;s:6:\"Leader\";i:3;s:0:\"\";i:4;s:0:\"\";}i:1;a:5:{i:0;s:14:\"opentreefieldb\";i:1;s:8:\"richblob\";i:2;s:7:\"Content\";i:3;s:0:\"\";i:4;s:0:\"\";}i:2;a:5:{i:0;s:14:\"opentreefieldc\";i:1;s:8:\"richblob\";i:2;s:10:\"Background\";i:3;s:0:\"\";i:4;s:0:\"\";}}', 'opentemplate/a.html')");
@mysql_query("INSERT INTO `opentempl` VALUES (2, 'Plain, content field', 'Content field only.', '', 'a:1:{i:0;a:5:{i:0;s:14:\"opentreefielda\";i:1;s:8:\"richblob\";i:2;s:7:\"Content\";i:3;s:0:\"\";i:4;s:0:\"\";}}', 'opentemplate/plain.html')");
@mysql_query("INSERT INTO `opentempl` VALUES (3, 'header, 2 content', 'header, 2 content', '', 'a:3:{i:0;a:5:{i:0;s:14:\"opentreefielda\";i:1;s:6:\"string\";i:2;s:6:\"Header\";i:3;s:0:\"\";i:4;s:0:\"\";}i:1;a:5:{i:0;s:14:\"opentreefieldb\";i:1;s:8:\"richblob\";i:2;s:10:\"Contents 1\";i:3;s:0:\"\";i:4;s:0:\"\";}i:2;a:5:{i:0;s:14:\"opentreefieldc\";i:1;s:8:\"richblob\";i:2;s:10:\"Contents 2\";i:3;s:0:\"\";i:4;s:0:\"\";}}', 'opentemplate/b.html')");


@mysql_query("CREATE TABLE `opentree` (
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
) TYPE=MyISAM AUTO_INCREMENT=44 ");

@mysql_query("INSERT INTO `opentree` VALUES (8, 0, 2, 0, 'a:1:{s:14:\"opentreefielda\";s:242:\"This is the start of the page tree, this page is visible in the main navigation.<br/> To change to contents of this page: login to the Jetbox and select ''Pages'' from the main left navigation. Select ''highest level'' from the tree on the right.\";}', 'highest level', 'highest level', 0, 0, 1, 1)");
@mysql_query("INSERT INTO `opentree` VALUES (39, 0, 1, 0, 'a:3:{s:14:\"opentreefielda\";s:0:\"\";s:14:\"opentreefieldb\";s:0:\"\";s:14:\"opentreefieldc\";s:0:\"\";}', '', 'jhj', 0, 0, 1, 1)");
@mysql_query("INSERT INTO `opentree` VALUES (40, 0, 1, 0, 'a:3:{s:14:\"opentreefielda\";s:0:\"\";s:14:\"opentreefieldb\";s:4:\"sfdg\";s:14:\"opentreefieldc\";s:0:\"\";}', 'sdf', 'gsdfg', 0, 0, 1, 1)");
@mysql_query("INSERT INTO `opentree` VALUES (41, 0, 2, 56, 'a:1:{s:14:\"opentreefielda\";s:9:\"sfdgsdfgs\";}', 'dsfg', 'sdfgsdf', 0, 0, 1, 1)");
@mysql_query("INSERT INTO `opentree` VALUES (42, 0, 1, 0, 'a:3:{s:14:\"opentreefielda\";s:0:\"\";s:14:\"opentreefieldb\";s:0:\"\";s:14:\"opentreefieldc\";s:0:\"\";}', '', 'okY', 0, 0, 1, 1)");
@mysql_query("INSERT INTO `opentree` VALUES (43, 0, 1, 10, 'a:3:{s:14:\"opentreefielda\";s:4:\"Test\";s:14:\"opentreefieldb\";s:4:\"test\";s:14:\"opentreefieldc\";s:43:\"<p>!!function_date(&quot;d-m-Y&quot;)!!</p>\";}', 'test', 'test', 0, 0, 1, 1)");

@mysql_query("CREATE TABLE `plug_contact` (
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
) TYPE=MyISAM AUTO_INCREMENT=9 ");


@mysql_query("CREATE TABLE `plug_event` (
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
) TYPE=MyISAM AUTO_INCREMENT=13 ");


@mysql_query("CREATE TABLE `plug_links` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `p_id` int(10) unsigned NOT NULL default '0',
  `cat_id` tinyint(3) unsigned NOT NULL default '0',
  `name` varchar(200) NOT NULL default '',
  `url` varchar(200) NOT NULL default '',
  `descrip` text NOT NULL,
  PRIMARY KEY  (`id`)
) TYPE=MyISAM AUTO_INCREMENT=57 ");


@mysql_query("CREATE TABLE `plug_news` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `p_id` int(10) unsigned NOT NULL default '0',
  `title` varchar(250) NOT NULL default '',
  `brood` mediumtext NOT NULL,
  `date` datetime NOT NULL default '0000-00-00 00:00:00',
  `author` varchar(200) NOT NULL default '',
  `intern` tinyint(1) unsigned NOT NULL default '0',
  `extern` tinyint(1) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM AUTO_INCREMENT=36 ");


@mysql_query("CREATE TABLE `plug_outputs` (
  `id` mediumint(8) unsigned NOT NULL auto_increment,
  `p_id` int(10) unsigned NOT NULL default '0',
  `author` varchar(200) NOT NULL default '',
  `title` varchar(200) NOT NULL default '',
  `date` date NOT NULL default '0000-00-00',
  `summary` text NOT NULL,
  `filename` varchar(250) NOT NULL default '',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM AUTO_INCREMENT=43 ");


@mysql_query("CREATE TABLE `postlistermain` (
  `liste` varchar(20) default NULL,
  `tilmeldingsbesked` mediumtext,
  `afmeldingsbesked` mediumtext,
  `standardafsender` varchar(100) default NULL,
  `signatur` mediumtext,
  `afsender` varchar(100) default NULL,
  `emne` varchar(100) default NULL,
  `ebrev` mediumtext,
  `date` datetime NOT NULL default '0000-00-00 00:00:00'
) TYPE=MyISAM");


@mysql_query("INSERT INTO `postlistermain` VALUES ('default', 'You have received this email because you or somebody else\r\nhas subscribed you to the mailing list default at\r\nhttp://.\r\nBefore we can add your email address to our mailing list we need to\r\nmake sure that the email address exists and is working, and that you\r\nactually want to subscribe to our mailing list. Therefore, we ask you\r\nto confirm your subscription by visiting the following URL:\r\n\r\n<[SUBSCRIBE_URL]>\r\n\r\nThank you.', 'You have received this email because you or somebody else\r\nhas unsubscribed you from the mailing list default at\r\nhttp://.\r\nBefore we can remove your email address from our mailing list we need\r\nto make sure that you, the owner of the email address, actually want to\r\nbe removed from the list. Therefore, we ask you to visit the following\r\nURL in order to confirm your unsubscription request:\r\n\r\n<[UNSUBSCRIBE_URL]>\r\n\r\nThank you.', NULL, NULL, NULL, NULL, NULL, '0000-00-00 00:00:00')");


@mysql_query("CREATE TABLE `pot_accesslog` (
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
) TYPE=MyISAM DELAY_KEY_WRITE=1");


@mysql_query("CREATE TABLE `pot_add_data` (
  `accesslog_id` int(11) NOT NULL default '0',
  `data_field` varchar(32) NOT NULL default '',
  `data_value` varchar(255) NOT NULL default '',
  KEY `accesslog_id` (`accesslog_id`)
) TYPE=MyISAM DELAY_KEY_WRITE=1");


@mysql_query("CREATE TABLE `pot_country` (
  `accesslog_id` int(11) NOT NULL default '0',
  `code2` char(2) NOT NULL default ''
) TYPE=MyISAM");


@mysql_query("CREATE TABLE `pot_documents` (
  `data_id` int(11) NOT NULL default '0',
  `string` varchar(255) NOT NULL default '',
  `document_url` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`data_id`)
) TYPE=MyISAM DELAY_KEY_WRITE=1");


@mysql_query("CREATE TABLE `pot_exit_targets` (
  `data_id` int(11) NOT NULL default '0',
  `string` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`data_id`)
) TYPE=MyISAM DELAY_KEY_WRITE=1");


@mysql_query("CREATE TABLE `pot_hostnames` (
  `data_id` int(11) NOT NULL default '0',
  `string` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`data_id`)
) TYPE=MyISAM DELAY_KEY_WRITE=1");


@mysql_query("CREATE TABLE `pot_iptoc` (
  `ipf` double NOT NULL default '0',
  `ipt` double NOT NULL default '0',
  `code2` char(2) NOT NULL default '',
  `code3` char(3) NOT NULL default '',
  `country` varchar(50) default NULL,
  KEY `code2` (`code2`)
) TYPE=MyISAM");


@mysql_query("CREATE TABLE `pot_operating_systems` (
  `data_id` int(11) NOT NULL default '0',
  `string` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`data_id`)
) TYPE=MyISAM DELAY_KEY_WRITE=1");


@mysql_query("CREATE TABLE `pot_referers` (
  `data_id` int(11) NOT NULL default '0',
  `string` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`data_id`)
) TYPE=MyISAM DELAY_KEY_WRITE=1");


@mysql_query("CREATE TABLE `pot_search_engines` (
  `accesslog_id` int(11) NOT NULL default '0',
  `search_engine` varchar(64) NOT NULL default '',
  `keywords` varchar(254) NOT NULL default '',
  PRIMARY KEY  (`accesslog_id`)
) TYPE=MyISAM DELAY_KEY_WRITE=1");


@mysql_query("CREATE TABLE `pot_user_agents` (
  `data_id` int(11) NOT NULL default '0',
  `string` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`data_id`)
) TYPE=MyISAM DELAY_KEY_WRITE=1");


@mysql_query("CREATE TABLE `pot_visitors` (
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
) TYPE=MyISAM DELAY_KEY_WRITE=1");


@mysql_query("CREATE TABLE `ppuploads` (
  `id` bigint(21) unsigned NOT NULL auto_increment,
  `content` mediumblob NOT NULL,
  `filename` varchar(255) NOT NULL default '',
  `width` smallint(6) NOT NULL default '0',
  `height` smallint(6) NOT NULL default '0',
  `mime` varchar(100) NOT NULL default '',
  `name` varchar(100) NOT NULL default '',
  `description` varchar(255) default NULL,
  PRIMARY KEY  (`id`)
) TYPE=MyISAM AUTO_INCREMENT=1 ");


@mysql_query("CREATE TABLE `sessions` (
  `sesskey` varchar(32) NOT NULL default '',
  `expiry` int(11) unsigned NOT NULL default '0',
  `value` text NOT NULL,
  PRIMARY KEY  (`sesskey`)
) TYPE=MyISAM");


@mysql_query("CREATE TABLE `sites` (
  `site_id` mediumint(9) NOT NULL auto_increment,
  `site_url` varchar(127) NOT NULL default '',
  `upddate` timestamp NOT NULL,
  `username` varchar(32) default NULL,
  `password` varchar(32) default NULL,
  `port` smallint(6) default NULL,
  `locked` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`site_id`),
  UNIQUE KEY `site_id` (`site_id`)
) TYPE=MyISAM AUTO_INCREMENT=4 ");


@mysql_query("CREATE TABLE `spider` (
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
) TYPE=MyISAM AUTO_INCREMENT=47 ");


@mysql_query("CREATE TABLE `struct` (
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
) TYPE=MyISAM AUTO_INCREMENT=250 ");


@mysql_query("INSERT INTO `struct` VALUES (232, '', '', 'index,follow', '2005-10-05 17:10:00', '2015-10-03 17:10:00', 'published', '0', '2005-10-05 17:08:27', 11, 40, '', '1', '', 'gsdfg')");
@mysql_query("INSERT INTO `struct` VALUES (233, '', '', 'index,follow', '2005-10-05 17:10:00', '2015-10-03 17:10:00', 'archive', '0', '2005-10-05 17:08:49', 11, 41, '', '1', '', 'sdfgsdf')");
@mysql_query("INSERT INTO `struct` VALUES (245, '', '', 'index,follow', '2005-10-05 23:10:00', '2015-10-03 23:10:00', 'published', '0', '2005-10-05 23:11:01', 11, 42, '', '1', '', 'okY')");
@mysql_query("INSERT INTO `struct` VALUES (249, '', '', 'index,follow', '2005-10-10 16:40:00', '2015-10-08 16:40:00', 'published', '0', '2005-10-10 16:42:34', 11, 43, '', '1', '', 'test')");


@mysql_query("CREATE TABLE `tempspider` (
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
) TYPE=MyISAM AUTO_INCREMENT=347 ");


@mysql_query("CREATE TABLE `user` (
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
) TYPE=MyISAM AUTO_INCREMENT=4 ");


@mysql_query("INSERT INTO `user` VALUES (1, NULL, 'admin', 'admin1', 'admin@localhost', 'administrator', 'Administrator', 1, '2005-10-13 00:08:15', 1)");


@mysql_query("CREATE TABLE `userrights` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `uid` bigint(20) NOT NULL default '0',
  `container_id` smallint(6) NOT NULL default '0',
  `type` enum('administrator','author','editor') NOT NULL default 'author',
  `history` timestamp NOT NULL,
  PRIMARY KEY  (`id`)
) TYPE=MyISAM AUTO_INCREMENT=46 ");


@mysql_query("CREATE TABLE `webuser` (
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
) TYPE=MyISAM AUTO_INCREMENT=2 ");

	$message.="<table width=\"100%\" border=0 cellpadding=8 cellspacing=0 bgcolor=\"f0f0f0\" style=\"border: #dddddd solid; border-width: 1px 1px 1px 1px;\">
		<tbody>
			<tr>
				<td width=\"100%\" style=\"border: #A31C00	 solid; border-width: 0 0 0 0px;padding:10px 10px 10px 10px\">
					<p><b>Status: </b>Tables are created.</p></td>
			</tr>
		</tbody>
		<tbody>
	</table>
	<br><table width=\"100%\" border=0 cellpadding=8 cellspacing=0 bgcolor=\"f0f0f0\" style=\"border: #dddddd solid; border-width: 1px 1px 1px 1px;\">
		<tbody>
			<tr>
				<td width=\"100%\" style=\"border: #A31C00	 solid; border-width: 0 0 0 10px;padding:10px 10px 10px 10px\">
					<b>Security:</b><br>Remove the /instl/ folder<br>Change the permissions to be writeable only the 'webserver user' (644 or -rw-r--r-- 
  within your FTP/ssh Client) on:<br>
  /includes/general_settings.inc.php<br><br></td>
			</tr>
		</tbody>
	</table>
	<br><table width=\"100%\" border=0 cellpadding=8 cellspacing=0 bgcolor=\"f0f0f0\" style=\"border: #dddddd solid; border-width: 1px 1px 1px 1px;\">
		<tbody>
			<tr>
				<td width=\"100%\" style=\"border: #A31C00	 solid; border-width: 0 0 0 0px;padding:10px 10px 10px 10px\">
								Go to  <a href=\"admin/index.php\" target=\"_blank\">/admin/</a> for administration<br>
								Username: admin<br>
								Password: admin1<br>
							  <i>Dont't forget to change this password</i></td>
			</tr>
		</tbody>
		<tbody>
			<tr>
				<td width=\"100%\" style=\"border: #A31C00	 solid; border-width: 0 0 0 0px;padding:10px 10px 10px 10px\">
					<a href=\"./\" target=\"_new\">Or go to the website</a></td>
			</tr>
		</tbody>
	</table>";
		$message.="<br>";

	}
	elseif($_REQUEST["step"] == "step3"){
		$message.="<h3>Current data</h3><p>";
		$message.="<p><b>Notice: </b>The selected database contains tables, please make sure you back-up this data bofore you proceed.";
		$result  = mysql_query('SHOW TABLE STATUS FROM ' . $database);
		$tabel=array(
				"container"=>true,
				"engine"=>true,
				"excludes"=>true,
				"images"=>true,
				"keywords"=>true,
				"links_cat"=>true,
				"logs"=>true,
				"mailspamstop"=>true,
				"opencontent"=>true,
				"opentempl"=>true,
				"opentree"=>true,
				"plug_contact"=>true,
				"plug_event"=>true,
				"plug_links"=>true,
				"plug_news"=>true,
				"plug_outputs"=>true,
				"postlistermain"=>true,
				"pot_accesslog"=>true,
				"pot_add_data"=>true,
				"pot_country"=>true,
				"pot_documents"=>true,
				"pot_exit_targets"=>true,
				"pot_hostnames"=>true,
				"pot_iptoc"=>true,
				"pot_operating_systems"=>true,
				"pot_referers"=>true,
				"pot_search_engines"=>true,
				"pot_user_agents"=>true,
				"pot_visitors"=>true,
				"ppuploads"=>true,
				"sessions"=>true,
				"sites"=>true,
				"spider"=>true,
				"struct"=>true,
				"tempspider"=>true,
				"user"=>true,
				"userrights"=>true,
				"webuser"=>true,
				"wo_programme"=>true
			);
		$message.="<br><br><table width=\"100%\" border=0 cellpadding=8 cellspacing=0 bgcolor=\"fcfcfc\" style=\"border: #dddddd solid; border-width: 1px 1px 1px 1px;\"><tr><td width=\"100%\">";
		$message.="<h4><b>Current tables:</b></h4>";
		$message.="<b>Tables used by Jetbox CMS<br><br></b>";		    
		$counter=0;
		$counter2=0;

		$message.="<table>";
		$message2="<tr><td colspan=\"2\"><br><b>Tables not used by Jetbox CMS</b><br><br></td></tr>";		    
		while($array=mysql_fetch_array($result)){
			if ($tabel[$array[0]]) {
				$counter % 4 == 0 ? $message.="<tr>" : $message.="";
				$message.="<td nowrap><code>".$array[0]."&nbsp;&nbsp;</code></td>";
				$counter++;
				$counter % 4 == 0 ? $message.="</tr>" : $message.="";
			}
			else{
				$counter2 % 4 == 0 ? $message2.="<tr>" : $message2.="";
				$message2.="<td nowrap><code>".$array[0]."&nbsp;&nbsp;</code></td>";
				$counter2++;
				$counter2 % 4 == 0 ? $message2.="</tr>" : $message2.="";
			}
			//$counter % 3 ? $message.="</tr>":

		}
		$counter % 4 <> 0 ? $message.="</tr>" : $message.="";
		$message.=$message2;
		$counter2 % 4 <> 0 ? $message.="</tr>" : $message.="";
		$message.="</table>";
		$message.="</td></tr></table>";
		$message.="<br>Create new tables and insert data.";
		$message.="<br><br><form method='post' action='instl/index.php'>
			<input type='hidden' name='step' value='step4'>
			<input type='hidden' name='install_type' value='newinstall'>
			<input type='radio' name='overwrite' value='1'> Delete existing tables used by Jetbox CMS<br>
			<input type='radio' name='overwrite' value='0' checked> Leave current data untouched. <b>This could result in inproper behaviour of Jetbox CMS</b><br><br>
			<input type='submit' name='Submit' value='Next »'>
			</form>";

	}
	elseif($_REQUEST["step"] == "step2"){
		$message.="<h3>Database check</h3><p>";


		if ($_REQUEST["substep"] == "createdatabase" && $db_error=='' && $db_error2<>'') {
 			$result = mysql_query('CREATE DATABASE '. $database);
			$db_error_create=mysql_error();
			if($db_error_create){
				$message.="<p><img src=\"images/button_error.gif\"><br><br><font color=\"A31C00\">Could not create the new database.</font>";
				$message.=$db_error_create;
				$message.="<br><br><form method='post' action='instl/index.php'>
				<input type='hidden' name='step' value='step2'>
				<input type='hidden' name='substep' value='createdatabase'>
				<input type='hidden' name='install_type' value='newinstall'>
				<input type='submit' name='Submit' value='Refresh'>
				</form>";
			}
			else{
				$message.="<br>Database created successfully.";
				$message.="<br>";
				$message.="<br><br><form method='post' action='instl/index.php'>
				<input type='hidden' name='step' value='step3'>
				<input type='hidden' name='install_type' value='newinstall'>
				<input type='submit' name='Submit' value='Next »'>
				</form>";
			}
		}
		//elseif ($db_config_is_set<>true) {
		//	$message.="<p><img src=\"images/button_error.gif\"><br><br><font color=\"A31C00\">No configuration data for the database connection was set.</font>";
		//	$message.="<br>Specify the correct host in includes/general_settings.inc.php";
		//	$message.="<br>The host should be: <b>".$_SERVER["HTTP_HOST"]."</b>";
		//	$message.="<br><br><form method='post' action='instl/index.php'>
		//		<input type='hidden' name='step' value='step2'>
		//		<input type='hidden' name='install_type' value='newinstall'>
		//		<input type='submit' name='Submit' value='Refresh'>
		//		</form>";
		//}
		elseif($db_error){
			$message.="<p><font color=\"A31C00\"><img src=\"images/button_error.gif\"><br><br>Could not connect to the mysql database server.</font>";
			
			$message.="<br>Please make sure you've specified the correct settings. Under SECTION II in /includes/general_settings.inc.php.";
			$message.="<br><br><b>Database settings:</b>";
			$message.="<br>Username: <b>".($username<>'' ? $username: '<i>Not set</i>')."</b>";
			$message.="<br>Password: <b><i>".($password<>'' ? 'Password set': 'Not set')."</i></b>";
			$message.="<br>Hostname: <b>".($hostname<>'' ? $hostname: '<i>Not set</i>')."</b>";
			$message.="<br>Database: <b>".($database<>'' ? $database: '<i>Not set</i>')."</b>";
			$message.="<br><br><form method='post' action='instl/index.php'>
				<input type='hidden' name='step' value='step2'>
				<input type='hidden' name='install_type' value='newinstall'>
				<input type='submit' name='Submit' value='Refresh'>
				</form>";
		}
		elseif ($db_error2) {
			if ($database<>'') {
				$message.="<p><b>Could not connect to the specified database.</b><br>Database: ". $database;
				$message.="<br><br><form method='post' action='instl/index.php'>
					<input type='hidden' name='step' value='step2'>
					<input type='hidden' name='substep' value='createdatabase'>
					<input type='hidden' name='install_type' value='newinstall'>
					<input type='submit' name='Submit' value='Create database'>
					</form>";
					$message.="<br><font color=\"A31C00\"><img src=\"images/dr_pijl.gif\"><br><br>If this is not what you expected, please specify the correct database.</font>";
			}
			else{
				$message.="<br><font color=\"A31C00\"><img src=\"images/button_error.gif\"><br><br>Database name may not be empty, please specify the correct database.</font>";
			}
			$message.="<br><br><b>Database settings</b>";
			$message.="<br>Username: <b>".($username<>'' ? $username: '<i>Not set</i>')."</b>";
			$message.="<br>Password: <b><i>".($password<>'' ? 'Password set': 'Not set')."</i></b>";
			$message.="<br>Hostname: <b>".($hostname<>'' ? $hostname: '<i>Not set</i>')."</b>";
			$message.="<br>Database: <b>".($database<>'' ? $database: '<i>Not set</i>')."</b>";
			$message.="<br><br><form method='post' action='instl/index.php'>
				<input type='hidden' name='step' value='step2'>
				<input type='hidden' name='install_type' value='newinstall'>
				<input type='submit' name='Submit' value='Refresh'>
				</form>";

		}
		else{
			//check database version
			$primq = "SHOW VARIABLES";
			$primr = @mysql_prefix_query($primq) or die(mysql_error());
			if(@mysql_num_rows($primr)>0){
				while ($primarray = mysql_fetch_array($primr)){
					if($primarray["Variable_name"]=="version"){
						$fullversion=$primarray[1];
						//$message.= substr($primarray[1],0,1);
						//print_r($primarray);
					}
					//echo $primarray[0]."<br />";
				}
			}
			//$message.="<p><b>Host settings configured and tested. [<i>Okay</i>]</b>";
			$message.="<p><b>The database connection is configured and tested.</b>";
			$message.="<br><br>Database connection: Okay";
			$message.="<br><br><b>Database settings</b>";
			$message.="<br>Username: <b>".($username<>'' ? $username: '<i>Not set</i>')."</b>";
			$message.="<br>Password: <b><i>".($password<>'' ? 'Password set': 'Not set')."</i></b>";
			$message.="<br>Hostname: <b>".($hostname<>'' ? $hostname: '<i>Not set</i>')."</b>";
			$message.="<br>Database: <b>".($database<>'' ? $database: '<i>Not set</i>')."</b>";
			//if(substr($fullversion,0,1)>=4){
				if($fullversion<>''){
					$message.="<p><b>Database version: ".$fullversion." (okay)</b>";
				}
				$message.="<br><br><form method='post' action='instl/index.php'>
				<input type='hidden' name='step' value='step3'>
				<input type='hidden' name='install_type' value='newinstall'>
				<input type='submit' name='Submit' value='Next »'>
				</form>";
			//}
			//else{

				//$message.="<p><img src=\"images/button_error.gif\"><br><br>Database version:<font color=\"A31C00\"> ".$fullversion.", at least version 4.0 is required.<br /> Jetbox can't be installed.</font>";
			//}
		}
	}
	elseif($_REQUEST["step"] == "step1"){
		$message.="<h3>General settings</h3><p>";
		$message.="Fill out all the required information in <br>/includes/general_settings.inc.php<br>";
//		$message.="<br>The host should be: <b>".$_SERVER["HTTP_HOST"]."</b> at <code style=\"color: #008\">case \"your.host.com\":</code>";
//		$message.="<br>If appropriate specify this host with and without www.</b><br>";
		$message.="<br><i>Specify the database information:</i>";
		$message.="<br><b>Table prefix: </b>The table prefix setting requires a special module. With the prefix option you can run multiple instances of Jetbox on one database without changing any php code. This module is only available for customers with a professional license. For more information please contact us at <a href=\"mailto:jetbox@streamedge.com?subject=Professional license\">jetbox@streamedge.com</a>";
		//$message.="<br><b>Notice: </b>You don't have to create a database yourself, if the specified database user has the rights to create one.";
		$message.="<br><br><i>Email settings:</i>";
		$message.="<br>Change the email addresses and hosts you may send email <i>to</i> from the website";
		$message.="<br><br><i>Image generation settings:</i>";
		$message.="<br>Specify the correct locations for the jpgraph PHP4 Graph Plotting library.";

		$pos=strpos($install_dir, "/instl");
		if (!$pos===false) {
			$installlr=substr($install_dir, 0, $pos);    
		}

		$message.="<br><br><table width=\"100%\" border=0 cellpadding=8 cellspacing=0 bgcolor=\"fcfcfc\" style=\"border: #dddddd solid; border-width: 1px 1px 1px 1px;\">
		<tbody>
			<tr>
				<td width=\"100%\">
					<b>Installation path detection & settings:</b><br>
					<br>Automatically detected installation path: <b>".$installlr."</b><br><font color=\"cc0000\">The installation path should start with a <b>/</b></font>
					<br>The Url to this Jetbox installation is: <b>http://".$_SERVER["HTTP_HOST"].$installlr."</b><br></td>
			</tr>
			<tr><td><b>If the above settings are incorrect</b><br>Configure the installation path manually under section I.I</td>
			</tr>
		</tbody>
	</table>";

		$message.= "<br><br><form method='post' action='instl/index.php'>
		<input type='hidden' name='step' value='step2'>
		<input type='hidden' name='install_type' value='newinstall'>
		<input type='submit' name='Submit' value='Next »'>
		</form>";
	}
	else{
		$message.="<h3>Permissions</h3><p>";
		$message.="Change the permissions to be writeable by all (767 or -rwxrw-rwx within your FTP Client) on:</b><br><br>
		&nbsp;- Copy & paste this line in your telnet/ssh client:<br>
		<code style=\"color: #008\">chmod 767 includes/general_settings.inc.php search_texts/ webfiles/ webimages/ temp/ files/ files/wfbtrash/</code><br><br>
		<b>General Jetbox CMS files and folders</b><br>
		/includes/general_settings.inc.php<br>
		<br>
		<b>Search engine</b><br>
		/search_texts/<br>
		<br>
		<b>Files and image</b><br>
		/webfiles/<br>
		/webimages/<br>
		/temp/<br>
		<br>
		<b>Document management</b><br>
		/files/<br>
		/files/wfbtrash/<br>
		<br>
		";
		$message.= "<form method='post' action='instl/index.php'>
		<input type='hidden' name='step' value='step1'>
		<input type='hidden' name='install_type' value='newinstall'>
		<input type='submit' name='Submit' value='Next »'>
		</form>";
	}
}
elseif ($install_jetbox==true && $_REQUEST["install_type"]=='upgradeinstall') {
	if ($_REQUEST["step"] == "step2"){
		// Extra database configuration
		$dtable = 'pot_iptoc';
		$dfrom = 'ipf';
		$dto = 'ipt';
		$dco2 = 'code2';
		$dco3 = 'code3';
		$dco = 'country';

		$startrow=0;
		if($_REQUEST[row]){
			$startrow=$_REQUEST[row];
		}
		$endrow=$startrow+2000;
		
		// Fill the table.
		$datarow = 0;
		$handle = fopen ("ip-to-country.csv","r");
		echo "<table><tr><td>Inserting record $startrow to $endrow</td></tr></table>";
		flush();
		while ($data = fgetcsv ($handle, 1000, ",")) {
			if($startrow<=$datarow && $datarow<$endrow){
				$query = "INSERT INTO $dtable(`$dfrom`, `$dto`, `$dco2`, `$dco3`, `$dco`) VALUES('".$data[0]."', '".$data[1]."', '".$data[2]."', '".$data[3]."', '".addslashes($data[4])."')";
				$result = mysql_query($query) or die("Invalid query: " . mysql_error().__LINE__.__FILE__);
				//echo "<table><tr><td>".$datarow."</td></tr></table>";
			}
			elseif($datarow==$endrow){
				break;
			}
			
			$datarow++;
		}


		fclose ($handle);
		$urlto="?step=step2&row=".$endrow;
		if ($datarow==$endrow && $endrow<'41413') {
			?>
			<SCRIPT LANGUAGE="JavaScript">window.location="<?echo $urlto;?>";</script>
			<?
		}
		else{
			if ($result){
				echo "<html><head><title>Success</title></head>
				<body><br><br>";
				if (!empty($table_s)){
					echo "$table_s <br>";
				}
				echo "$datarow rows were added to $dtable. </body></html>";
			}
		}
	}
	elseif ($_REQUEST["step"] == "step1"){
		echo "<html><head><title>Upgrade procedure step one</title></head><body>";
		mysql_query("DROP TABLE IF EXISTS `pot_accesslog`");
		mysql_query("DROP TABLE IF EXISTS `pot_add_data`");
		mysql_query("DROP TABLE IF EXISTS `pot_documents`");
		mysql_query("DROP TABLE IF EXISTS `pot_exit_targets`");
		mysql_query("DROP TABLE IF EXISTS `pot_hostnames`");
		mysql_query("DROP TABLE IF EXISTS `pot_operating_systems`");
		mysql_query("DROP TABLE IF EXISTS `pot_referers`");
		mysql_query("DROP TABLE IF EXISTS `pot_search_engines`");
		mysql_query("DROP TABLE IF EXISTS `pot_user_agents`");
		mysql_query("DROP TABLE IF EXISTS `pot_visitors`");
		mysql_query("DROP TABLE IF EXISTS `pot_country`");
		mysql_query("DROP TABLE IF EXISTS `pot_iptoc`");


		mysql_query("CREATE TABLE `pot_accesslog` (
		`accesslog_id` int(11) NOT NULL default '0',
		`client_id` int(10) unsigned NOT NULL default '0',
		`timestamp` int(10) unsigned NOT NULL default '0',
		`document_id` int(11) NOT NULL default '0',
		`exit_target_id` int(11) NOT NULL default '0',
		`entry_document` enum('0','1') NOT NULL default '0',
		KEY `accesslog_id` (`accesslog_id`),
		KEY `client_time` (`client_id`,`timestamp`),
		KEY `document_id` (`document_id`)
	) TYPE=MyISAM DELAY_KEY_WRITE=1");

		mysql_query("CREATE TABLE `pot_add_data` (
		`accesslog_id` int(11) NOT NULL default '0',
		`data_field` varchar(32) NOT NULL default '',
		`data_value` varchar(255) NOT NULL default '',
		KEY `accesslog_id` (`accesslog_id`)
	) TYPE=MyISAM DELAY_KEY_WRITE=1");

		mysql_query("CREATE TABLE `pot_documents` (
		`data_id` int(11) NOT NULL default '0',
		`string` varchar(255) NOT NULL default '',
		`document_url` varchar(255) NOT NULL default '',
		PRIMARY KEY  (`data_id`)
	) TYPE=MyISAM DELAY_KEY_WRITE=1");

		mysql_query("CREATE TABLE `pot_exit_targets` (
		`data_id` int(11) NOT NULL default '0',
		`string` varchar(255) NOT NULL default '',
		PRIMARY KEY  (`data_id`)
	) TYPE=MyISAM DELAY_KEY_WRITE=1");

		mysql_query("CREATE TABLE `pot_hostnames` (
		`data_id` int(11) NOT NULL default '0',
		`string` varchar(255) NOT NULL default '',
		PRIMARY KEY  (`data_id`)
	) TYPE=MyISAM DELAY_KEY_WRITE=1");

		mysql_query("CREATE TABLE `pot_operating_systems` (
		`data_id` int(11) NOT NULL default '0',
		`string` varchar(255) NOT NULL default '',
		PRIMARY KEY  (`data_id`)
	) TYPE=MyISAM DELAY_KEY_WRITE=1");

		mysql_query("CREATE TABLE `pot_referers` (
		`data_id` int(11) NOT NULL default '0',
		`string` varchar(255) NOT NULL default '',
		PRIMARY KEY  (`data_id`)
	) TYPE=MyISAM DELAY_KEY_WRITE=1");

		mysql_query("CREATE TABLE `pot_search_engines` (
		`accesslog_id` int(11) NOT NULL default '0',
		`search_engine` varchar(64) NOT NULL default '',
		`keywords` varchar(254) NOT NULL default '',
		PRIMARY KEY  (`accesslog_id`)
	) TYPE=MyISAM DELAY_KEY_WRITE=1");

		mysql_query("CREATE TABLE `pot_user_agents` (
		`data_id` int(11) NOT NULL default '0',
		`string` varchar(255) NOT NULL default '',
		PRIMARY KEY  (`data_id`)
	) TYPE=MyISAM DELAY_KEY_WRITE=1");

		mysql_query("CREATE TABLE `pot_visitors` (
		`accesslog_id` int(11) NOT NULL default '0',
		`visitor_id` int(11) NOT NULL default '0',
		`client_id` int(10) unsigned NOT NULL default '0',
		`operating_system_id` int(11) NOT NULL default '0',
		`user_agent_id` int(11) NOT NULL default '0',
		`host_id` int(11) NOT NULL default '0',
		`referer_id` int(11) NOT NULL default '0',
		`timestamp` int(10) unsigned NOT NULL default '0',
		`returning_visitor` enum('0','1') NOT NULL default '0',
		PRIMARY KEY  (`accesslog_id`),
		KEY `client_time` (`client_id`,`timestamp`),
		KEY `os_ua` (`operating_system_id`,`user_agent_id`),
		KEY `host_id` (`host_id`),
		KEY `referer_id` (`referer_id`)
	) TYPE=MyISAM DELAY_KEY_WRITE=1");

		mysql_query("CREATE TABLE `pot_country` (
		`accesslog_id` int(11) NOT NULL default '0',
		`code2` char(2) NOT NULL default ''
	) TYPE=MyISAM");

		mysql_query("CREATE TABLE `pot_iptoc` (
		`ipf` double NOT NULL default '0',
		`ipt` double NOT NULL default '0',
		`code2` char(2) NOT NULL default '',
		`code3` char(3) NOT NULL default '',
		`country` varchar(50) default NULL,
		KEY `ipf` (`ipf`),
		KEY `ipf_2` (`ipf`)
	) TYPE=MyISAM");

		echo "All new tables have been created.<br>";
		echo "<font color='A31C00'>By pressing the step two all required data will be inserted.<br>About 42.000 records will be inserted so sit back and relax.<font>";
		echo "<form method='post' action='instl/index.php'>
		<input type='hidden' name='step' value='step2'>
		<input type='radio' name='install_type' value='upgradeinstall'>
		<input type='submit' name='Submit' value='Step two'>
			</form>";
		echo "</body></html>";
	}
	else{
		$message="I have made a backup of all my phpOpentracker data. That are all the pot_ tables. I'm ready to for the next step.<br>";
		$message.="<font color='A31C00'>By pressing the step one button all current statistical information will be deleted</font>";
		$message.= "<form method='post' action='instl/index.php'>
		<input type='hidden' name='step' value='step1'>
		<input type='radio' name='install_type' value='newinstall'> New installation<br>
		<input type='radio' name='install_type' value='upgradeinstall'> Upgrade existing installation<br><br>
		<input type='submit' name='Submit' value='Next »'>
		</form>";
	}
}
elseif($install_jetbox==true){
	$message.="<h3>Jetbox CMS installation</h3><p>This wizard will guide you thru the installation process.<br>
	<!--<b>Choose the installation type to proceed.</b>--><p>";

	$message.= "<form method='post' action='instl/index.php'>
	<input type='hidden' name='install_type' value='newinstall'>
	<!--
	 <input type='radio' name='install_type' value='newinstall'> New installation<br>
	 <input type='radio' name='install_type' value='upgradeinstall'> Upgrade existing installation<br><br> -->
	<input type='submit' name='Submit' value='Next »'>
	</form>";
}
else{
	$message.="<h3>Jetbox CMS installation</h3><p>Jetbox is already installed please. Check includes/general_settings.inc.php if you want to reinstall Jetbox.<br>";
}

$t->set_var("containera","<div style=\"margin:0px 10px 20px 15px\">".$message."</div>");
//$t->set_var("containera",$message);
//General Header
$t->parse("finaloutput", "block");
$t->p("finaloutput");
?>