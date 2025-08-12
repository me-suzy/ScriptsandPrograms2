<?php include('Connections/poll.php'); ?>
<?php
//mysql_select_db($database_poll, $poll);
$query10="DROP TABLE IF EXISTS `flash_access`;";
$result = mysql_query($query10,$poll);
$query11= "CREATE TABLE `flash_access` (
  `User` text NOT NULL,
  `Password` text NOT NULL,
  `address` text NOT NULL
) TYPE=MyISAM;";
$result = mysql_query($query11,$poll);
$query12="INSERT INTO `flash_access` VALUES ('admin', 'password', 'http://www.rantchaos.com/newpoll/');";
$result = mysql_query($query12,$poll);
$query13="DROP TABLE IF EXISTS `flash_poll`;";
$result = mysql_query($query13,$poll);
$query2="
   CREATE TABLE `flash_poll` (
  `ID` int(11) NOT NULL auto_increment,
  `question` text NOT NULL,
  `numopt` int(11) NOT NULL default '0',
  `opt1` text NOT NULL,
  `opt2` text NOT NULL,
  `opt3` text NOT NULL,
  `opt4` text NOT NULL,
  `opt5` text NOT NULL,
  `opt6` text NOT NULL,
  `opt7` text NOT NULL,
  `opt8` text NOT NULL,
  `count1` int(11) NOT NULL default '0',
  `count2` int(11) NOT NULL default '0',
  `count3` int(11) NOT NULL default '0',
  `count4` int(11) NOT NULL default '0',
  `count5` int(11) NOT NULL default '0',
  `count6` int(11) NOT NULL default '0',
  `count7` int(11) NOT NULL default '0',
  `count8` int(11) NOT NULL default '0',
  `status` int(11) NOT NULL default '0',
  `skin` int(11) NOT NULL default '0',
  UNIQUE KEY `ID` (`ID`)
) TYPE=MyISAM AUTO_INCREMENT=21 ;";

$result = mysql_query($query2,$poll) or die ("Error in query: $query. " . mysql_error());

echo "The database structure was succesfully created.The default account is <b>admin</b> and the the default password is <b>password</b>.You can change them later from the control panel";
?>

