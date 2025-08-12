<?
require ('_.php');
require ('functions.php');


$query0="
CREATE TABLE `Zdir` (
  `page_id` int(6) unsigned NOT NULL auto_increment,
  `rec_crt` varchar(60) NOT NULL default '0000-00-00 00:00:00',
  `rec_edit` datetime NOT NULL default '0000-00-00 00:00:00',
  `auth_id` tinyint(4) NOT NULL default '0',
  `isactive` tinyint(1) NOT NULL default '0',
  `pg_title` tinytext NOT NULL,
  `hits` tinyint(4) NOT NULL default '0',
  `admin_lvl` tinyint(4) NOT NULL default '0',
  `content` text NOT NULL,
  `rec_expire` date NOT NULL default '0000-00-00',
  PRIMARY KEY  (`page_id`)
) TYPE=MyISAM COMMENT='N8cms' AUTO_INCREMENT=100 ;
";

mysql_query($query0) or die(mysql_error());
echo"<a href=index.php>Done!<a>";
?>

