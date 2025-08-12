<?php
session_start();
/* CONFIG FILE */

DEFINE ("DB_HOST", '');
DEFINE ("DB_USER", '');
DEFINE ("DB_PWD", '');
DEFINE ("DB_NAME", '');



@mysql_connect(DB_HOST, DB_USER, DB_PWD) or die(mysql_error());
@mysql_select_db(DB_NAME) or die(mysql_error());

$GLOBALS['page_title'] = "My Title Here";

/* DO NOT EDIT BELOW THIS LINE */

$GLOBALS['from'] = str_replace('&', '|', str_replace('&from=' . $_REQUEST['from'], '', $_SERVER['QUERY_STRING']));
$GLOBALS['tofrom'] = str_replace('|', '&', $_REQUEST['from']);


$GLOBALS['query'] = "

DROP TABLE IF EXISTS `words`;
CREATE TABLE `words` (
  `word_id` int(10) unsigned NOT NULL auto_increment,
  `word_title` varchar(255) NOT NULL default '',
  `word_desc` text NOT NULL,
  `word_comments` text NOT NULL,
  `word_mdate` datetime NOT NULL default '0000-00-00 00:00:00',
  `word_cdate` datetime NOT NULL default '0000-00-00 00:00:00',
  `found` tinyint(3) unsigned NOT NULL default '0',
  PRIMARY KEY  (`word_id`),
  KEY `word_title` (`word_title`(5)),
  FULLTEXT KEY `word_desc` (`word_desc`)
) TYPE=MyISAM ;

";

?>
