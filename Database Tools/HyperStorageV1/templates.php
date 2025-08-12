<?
global $templates;

$templates["users"]="

  `nickname` varchar(45) NOT NULL default '',
  `email` varchar(100) NOT NULL default '',
  `password` varchar(45) NOT NULL default '',
  `firstname` varchar(40) NOT NULL default '',
  `lastname` varchar(50) NOT NULL default '',
  `homephone` varchar(45) NOT NULL default '',
  `workphone` varchar(45) NOT NULL default '',
  `cellphone` varchar(45) NOT NULL default '',
  `address` varchar(85) NOT NULL default '',
  `address2` varchar(55) NOT NULL default '',
  `city` varchar(75) NOT NULL default '',
  `state` varchar(75) NOT NULL default '',
  `zip` varchar(20) NOT NULL default '',
  `country` varchar(85) NOT NULL default '',
  `ban` varchar(3) NOT NULL default '',
  `suspend` varchar(3) NOT NULL default '',
  `verify` varchar(4) NOT NULL default '',
  `verifykey` varchar(15) NOT NULL default '',
  KEY `email` (`email`),
  KEY `nickname` (`nickname`)
  
";

$templates["help"]="

  `articleid` varchar(100) NOT NULL default '',
  `articletype` varchar(100) NOT NULL default '',
  `articletitle` varchar(100) NOT NULL default '',
  `articletext` longtext,
  KEY `articleid` (`articleid`),
  FULLTEXT KEY `articletext` (`articletext`)
  
";
?>