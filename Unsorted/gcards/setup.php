<?
/*
 * gCards - a web-based eCard application
 * Copyright (C) 2003 Greg Neustaetter
 * 
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or (at
 * your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful, but
 * WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
 */
include_once('inc/adodb/adodb.inc.php');	   # load code common to ADOdb
include_once('config.php');
include_once('inc/UIfunctions.php');

$page = new pagebuilder;
include_once('inc/setLang.php');
$page->showHeader();

if (isset($_POST['installStatus'])) $installStatus = $_POST['installStatus'];

$success = "<td bgcolor=\"#33cc33\"><img src=\"images/siteImages/shim.gif\" border=\"0\" width=\"10\"></td>";
$failure = "<td bgcolor=\"#FF0000\"><img src=\"images/siteImages/shim.gif\" border=\"0\" width=\"10\"></td>";

// Create Database Connection and test to see if DB properties are correct
$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
$conn = &ADONewConnection('mysql');
if (!$conn->Connect($dbhost,$dbuser,$dbpass,$dbdatabase))
{
	?>
		<table cellspacing="5" width="500">
			<tr><? echo $failure;?>
			<td>Could not connect to the selected database: <? echo $dbdatabase?>.  Check the config.php file to be 
			sure you set the correct database server, database name, username, and password.  
			Contact your system administrator if you do not know these values.<br><br>
			You need to fix this before you can continue with the installation process.</td></tr>
		</table>
	<?
	$page->showFooter();
	exit;
}

if(!isset($installStatus))
{
?>
	<table cellspacing="5" width="500" border="0">
		<tr>
			<td colspan="2">
				Welcome to the gCards <? echo $gCardsVersion; ?> setup program.  Please choose
				your setup option below.  Choose 'Install' if this is the first time you have
				installed gCards.  Please choose an upgrade option if you would like to upgrade your current
				gCards installation to version <? echo $gCardsVersion; ?>.
				<br><br>
				For more information please see the gCards user guide.
			</td>
		</tr>
		<tr>
			<td colspan="2">&nbsp;</td>
		</tr>
		<tr>
			<td class="bold" width="150">Choose install type:</td>
			<form action="setup.php" method="post">
			<td align="left" width="350">
				<input type="radio" name="installStatus" value="new" onClick="submit();">Install<br>
				<input type="radio" name="installStatus" value="upgrade103" onClick="submit();">Upgrade from version 1.1 or earlier<br>
				<input type="radio" name="installStatus" value="upgrade12" onClick="submit();">Upgrade from version 1.2 or earlier<br>
				<input type="radio" name="installStatus" value="upgrade131" onClick="submit();">Upgrade from version 1.31 or earlier<br>
			</td>
			</form>
		</tr>	
	</table>
<?
}

if (isset($installStatus))
{
	$release14Changes[] = "ALTER TABLE ".$tablePrefix."sentcards ADD music VARCHAR(60)";
	
	$release14Changes[] = "
	CREATE TABLE ".$tablePrefix."music (
	  mid tinyint(4) NOT NULL auto_increment,
	  mname varchar(40) NOT NULL default '',
	  mpath varchar(60) NOT NULL default '',
	  PRIMARY KEY  (mid)
	) TYPE=MyISAM";
	
	$release13Changes[] = "
	CREATE TABLE ".$tablePrefix."statistics (
	  stat varchar(60) NOT NULL default '',
	  statval int(11) NOT NULL default '0',
	  PRIMARY KEY  (stat)
	) TYPE=MyISAM";
	
	$release13Changes[] = "INSERT INTO ".$tablePrefix."statistics (stat, statval) VALUES ('userhits', 0)";
	$release13Changes[] = "INSERT INTO ".$tablePrefix."statistics (stat, statval) VALUES ('pickuphits', 0)";
	
	$release11Changes[] = "
	ALTER TABLE ".$tablePrefix."cardusers ADD UNIQUE (
	username 
	)";
	$release11Changes[] =	"ALTER TABLE ".$tablePrefix."sentcards CHANGE to_email to_email TEXT NOT NULL";
	$release11Changes[] = 	"
	CREATE TABLE ".$tablePrefix."news (
	  newsid int(11) NOT NULL auto_increment,
	  username varchar(30) NOT NULL default '',
	  subject varchar(200) NOT NULL default '',
	  body text NOT NULL,
	  postdate int(20) default NULL,
	  PRIMARY KEY  (newsid)
	) TYPE=MyISAM";
	
	$setupSQL[] = "
	CREATE TABLE ".$tablePrefix."news (
	  newsid int(11) NOT NULL auto_increment,
	  username varchar(30) NOT NULL default '',
	  subject varchar(200) NOT NULL default '',
	  body text NOT NULL,
	  postdate int(20) default NULL,
	  PRIMARY KEY  (newsid)
	) TYPE=MyISAM";
	
	$setupSQL[]= "
	CREATE TABLE ".$tablePrefix."cardinfo (
	  imageid int(11) NOT NULL auto_increment,
	  cardname varchar(40) NOT NULL default '',
	  catid int(11) default NULL,
	  imagepath varchar(40) NOT NULL default '',
	  thumbpath varchar(40) NOT NULL default '',
	  senttimes int(11) NOT NULL default '0',
	  PRIMARY KEY  (imageid)
	) TYPE=MyISAM";
	
	$setupSQL[]= "
	CREATE TABLE ".$tablePrefix."cardusers (
	  userid int(11) NOT NULL auto_increment,
	  username varchar(40) NOT NULL default '',
	  userpass varchar(40) NOT NULL default '',
	  email varchar(60) default NULL,
	  role varchar(20) default NULL,
	  PRIMARY KEY  (userid),
	  UNIQUE KEY username (username)
	) TYPE=MyISAM";

	$setupSQL[] = "
	CREATE TABLE ".$tablePrefix."categories (
	  catid int(11) NOT NULL auto_increment,
	  category varchar(60) NOT NULL default '',
	  PRIMARY KEY  (catid)
	) TYPE=MyISAM";

	$setupSQL[] = "
	CREATE TABLE ".$tablePrefix."sentcards (
	  cardid int(11) NOT NULL default '0',
	  imageid int(11) NOT NULL default '0',
	  to_name varchar(60) default NULL,
	  to_email text NOT NULL,
	  from_name varchar(60) default NULL,
	  from_email varchar(60) NOT NULL default '',
	  cardtext mediumtext,
	  sendonpickup varchar(10) default NULL,
	  music VARCHAR(60) default NULL,
	  PRIMARY KEY  (cardid)
	) TYPE=MyISAM";
	
	$setupSQL[] = "
	CREATE TABLE ".$tablePrefix."music (
	  mid tinyint(4) NOT NULL auto_increment,
	  mname varchar(40) NOT NULL default '',
	  mpath varchar(60) NOT NULL default '',
	  PRIMARY KEY  (mid)
	) TYPE=MyISAM";

	$setupSQL[] = "
	CREATE TABLE ".$tablePrefix."statistics (
	  stat varchar(60) NOT NULL default '',
	  statval int(11) NOT NULL default '0',
	  PRIMARY KEY  (stat)
	) TYPE=MyISAM";
	
	$setupSQL[] = "INSERT INTO ".$tablePrefix."statistics (stat, statval) VALUES ('userhits', 0)";
	$setupSQL[] = "INSERT INTO ".$tablePrefix."statistics (stat, statval) VALUES ('pickuphits', 0)";
	
	$setupSQL[] = "INSERT INTO ".$tablePrefix."cardusers (username, userpass, email, role) VALUES ('admin',password('admin'),'email','admin')";
	$setupSQL[] = "INSERT INTO ".$tablePrefix."categories (category) VALUES ('test')";
?>
	<table cellspacing="5" width="500">
<?
	switch ($installStatus)
	{
		case 'new':
			SQLExecutor($conn, $setupSQL);
			break;
		case 'upgrade103':
			SQLExecutor($conn, $release11Changes);
		case 'upgrade12':
			SQLExecutor($conn, $release13Changes);
		case 'upgrade131':
			SQLExecutor($conn, $release14Changes);
	}
?>			
	</table> 
	<table cellspacing="5" width="500">
		<tr>
			<td class="bold" colspan="2">Setup Steps</td>
		</tr>
		<tr>
			<td valign="top">1</td>
			<td>CHMOD the 'images' and 'sound' folders to '777' if you have not already done so.</td>
		</tr>
		<tr>
			<td valign="top">2</td>
			<td>Please login to the <a href="login.php" target="_blank">Administration Console</a>.  This will launch in a separate window so you can keep these instructions in the browser.</td>
		</tr>
		<tr>
			<td valign="top">3</td>
			<td>Once logged in, please change the password to the 'admin' account for security purposes.</td>
		</tr>
		<tr>
			<td valign="top">4</td>
			<td>Add categories in the Category Maintenance section.</td>
		</tr>
		<tr>
			<td valign="top">5</td>
			<td>Add new eCards via the Card Maintenance section.</td>
		</tr>
		<tr>
			<td valign="top">6</td>
			<td class="bold">Delete setup.php or CHMOD it to '000' to prevent malicious users from modifying this application</td>
		</tr>
		<tr>
			<td valign="top">7</td>
			<td>Launch <a href="index.php">index.php</a> to see your eCard site!</td>
		</tr>
		<tr>
			<td valign="top">8</td>
			<td>DONE!</td>
		</tr>
	</table>
	
	<br><br>Please login to the <a href="login.php" target="_blank">Administration Console</a> to add new cards and categories.  Be sure to change the admin password for security purposes.
<?
}
$page->showFooter();

function SQLExecutor(&$conn, $sqlArray)
{
	global $success;
	global $failure;
	foreach ($sqlArray as $sql)
	{
		if ($conn->execute($sql)) echo "<tr>$success<td>Successfully exectued SQL: $sql</td></tr>";
		else echo "<tr>$failure<td>Unable to execute SQL: $sql</td></tr>";
	}
}
?>
