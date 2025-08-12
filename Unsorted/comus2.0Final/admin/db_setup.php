<?
include($DOCUMENT_ROOT . "/includes/config.inc.php");
include($DOCUMENT_ROOT . "/includes/header.php");
if($deflang)
{
	include("../includes/language/lang-".$deflang.".php");
}
else
{
    include("../includes/language/lang-english.php");
}
$username = $dbuser; 
$password = $dbpasswd; 
$db_name = $db; 
?>
<TABLE ALIGN="CENTER" VALIGN="TOP" WIDTH="750" CELLSPACING="0" CELLPADDING="0" BORDER="0">
<TR><TD>&nbsp;</TD></TR>
<TR>
<TD ALIGN="CENTER">

<TABLE ALIGN="CENTER" VALIGN="TOP" WIDTH="100%" CELLPADDING="2" BORDER="1" BORDERCOLOR="#000000" BORDERCOLORLIGHT="#000000" BORDERCOLORDARK="#000000">
<TR><TD ALIGN="CENTER">
<?
mysql_pconnect("$dbhost","$username","$password"); 

mysql_db_query("$db_name","CREATE TABLE tblBlacklist (
  id int(11) NOT NULL auto_increment,
  url char(100) NOT NULL default '',
  email char(100) NOT NULL default '',
  type char(20) NOT NULL default '',
  PRIMARY KEY  (id)
) TYPE=MyISAM");
   echo "BlackList Table Created<br>";
mysql_db_query("$db_name","CREATE TABLE tblTgp (
  id int(11) NOT NULL auto_increment,
  nickname char(30) NOT NULL default '',
  email char(100) NOT NULL default '',
  url char(150) NOT NULL default '',
  category char(100) NOT NULL default '',
  description char(100) NOT NULL default '',
  date char(20) NOT NULL default '',
  newpost char(10) NOT NULL default 'yes',
  accept char(10) NOT NULL default '',
  vote int(3) NOT NULL default '5',
  recip char(5) NOT NULL default '',
  sessionid char(45) NOT NULL default '',
  numpic INT(3) NOT NULL default '0',
  mailme CHAR(5) NOT NULL DEFAULT 'no',
  ppost CHAR(10) NOT NULL DEFAULT 'no',
  PRIMARY KEY  (id)
) TYPE=MyISAM");
   echo "TGP Table Created<br>";
mysql_db_query("$db_name","CREATE TABLE tblPreferred (
  id int(11) NOT NULL auto_increment,
  email char(100) NOT NULL default '',
  nick char(100) NOT NULL default '',
  s_url char(100) NOT NULL default '',
  pass char(5) NOT NULL default '',
  new char(5) NOT NULL default '',
  PRIMARY KEY  (id),
  UNIQUE KEY id (id)
) TYPE=MyISAM");
      echo "Preferred Table Created<br>";
mysql_db_query("$db_name","CREATE TABLE tblCategories (
  id int(11) NOT NULL auto_increment,
  Category char(50) NOT NULL default '',
  PRIMARY KEY  (id)
) TYPE=MyISAM");
$datenow = date("Ymd");
mysql_db_query("$db","CREATE TABLE gtgp_ip (ip char(30) NOT NULL default '') TYPE=MyISAM ");
mysql_db_query("$db","CREATE TABLE gtgp_settings ( ipclear char(15) NOT NULL default '') TYPE=MyISAM");
mysql_query("INSERT into gtgp_settings (ipclear) VALUES ('$datenow')");

   echo "Categories Table Created<br><center><a href=\"admin/index.php\"><b><font size=-1 face=arial>Return to main page</font></b></a></center>";
?>
</TD></TR>
</TABLE>


</TD>
</TR>
</TABLE>
</body>
</html>