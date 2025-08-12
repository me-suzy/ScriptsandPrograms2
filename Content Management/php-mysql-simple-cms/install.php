<?php
include("config.php"); 
//this script installes the table in your mysql database. You can delete this script if the database is created succesfully. 

database_connect();

$query = "CREATE TABLE config (
			  ID int(11) NOT NULL default 0,
			  titel varchar(55) NOT NULL default '',
			  startpage varchar(55) NOT NULL default '',
			  keywords text NOT NULL,
			  description varchar(55) NOT NULL default '',
			  PRIMARY KEY  (ID)
			) TYPE=MyISAM;";
			
$created = mysql_query($query);
$error = mysql_error();
if ($created) {
   print "The table config <strong>succesfully </strong>created in the database : $database <br>";
} else {
 print "There was a <strong>problem</strong> creating your table:<br>";
 print "<b>$error</b>";
}



$query2 = "INSERT INTO `config` VALUES (1, 'Simple CMS', '1', 'PHP MySQL Simple Content management system - fast - optimized', 'Welcome to my page!');";
			
$created2 = mysql_query($query2);
$error2 = mysql_error();
if ($created2) {
   print "The data is <strong>succesfully</strong> added to config table in : $database <br>";
} else {
 print "There was a <strong>problem</strong> adding data to the config table:<br>";
 print "<b>$error</b>";
}


$query3 =
"CREATE TABLE `content` (
  `id` int(11) NOT NULL auto_increment,
  `title` varchar(255) NOT NULL default '',
  `last_updated` timestamp(14) NOT NULL,
  `posting_time` timestamp(14) NOT NULL,
  `text` text NOT NULL,
  `keywords` text NOT NULL,
  `position` tinyint(4) NOT NULL default '0',
  `status` tinyint(4) default NULL,
  PRIMARY KEY  (`id`)
) TYPE=MyISAM PACK_KEYS=0 AUTO_INCREMENT=12 ;";

			
$created3 = mysql_query($query3);
$error3 = mysql_error();
if ($created2) {
   print "The table content is <strong>succesfully</strong> added in : $database <br>";
} else {
 print "There was a <strong>problem</strong> creating your content table:<br>";
 print mysql_error();
}



$query4 = "INSERT INTO `content` VALUES (1, 'Home', 20040123020414, 20040119003051, '<P>The database is working. You\'re CMS is fully operational and working. </P>\r\n<P>You can login now as admin and edit/add/delete pages.</P>\r\n<P>Good Luck. </P>', 'homepage - CMS - Content management system - PHP - MySQL ', 1, 1);";
			
$created4 = mysql_query($query4);
$error4 = mysql_error();
if ($created4) {
   print "The data is <strong>succesfully</strong> added to the content table in : $database <br><br>";
      print "If everything was added <strong>succesfully</strong>. Please delete this page.";
} else {
 print "There was a <strong>problem</strong> adding data to the content table:<br>";
 print "<b>$error</b>";
}

?>