<?
include('config.php');
mysql_query("
 CREATE TABLE sotw_submits(
id tinyint( 5 ) NOT NULL AUTO_INCREMENT ,
user varchar( 255 ) NOT NULL default '',
sig varchar( 255 ) NOT NULL default '',
website varchar( 255 ) NOT NULL default '',
wid tinyint( 5 ) NOT NULL default '0',
winner enum( 'Y', 'N' ) NOT NULL default 'N',
UNIQUE KEY id( id )
) TYPE = MYISAM
") or die("Error during install: <b>".mysql_error()."</b>");

mysql_query("
CREATE TABLE sotw_week (
  wid tinyint( 5 ) NOT NULL AUTO_INCREMENT ,
  UNIQUE KEY wid (wid)
) TYPE=MyISAM
        
") or die("Error during install: <b>".mysql_error()."</b>");

echo "Sucesfully installed.";
					echo "Week 1 created automatically.<br>";
					mysql_query("INSERT INTO sotw_week VALUES ('null')") or die(mysql_error());
					echo "New week table created.<br>";
					$wid = 1;
					mkdir($sotwPath.$wid, 0777);
					echo "New week folder created.<br.";
					
?>