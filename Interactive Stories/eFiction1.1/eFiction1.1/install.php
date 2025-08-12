<?php

// ----------------------------------------------------------------------
// Fanfiction Program
// Copyright (C) 2003 by Rebecca Smallwood.
// http://orodruin.sourceforge.net/
// ----------------------------------------------------------------------
// LICENSE
//
// This program is free software; you can redistribute it and/or
// modify it under the terms of the GNU General Public License (GPL)
// as published by the Free Software Foundation; either version 2
// of the License, or (at your option) any later version.
//
// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// To read the license please visit http://www.gnu.org/copyleft/gpl.html
// ----------------------------------------------------------------------

echo "<html>";
include ("javascript.js");
echo "<body>";

echo "<table width=\"425\" border=\"1\" bordercolor=\"#5B5CAF\" align=\"center\" cellpadding=\"4\" cellspacing=\"4\">";
echo "<tr><td><font style=\"FONT-FAMILY: Verdana,Helvetica; FONT-SIZE: 10px\">";
echo "<center><h4>eFiction 1.1 Install</h4></center>";

if($step1)
{
	echo "IMPORTANT: config.php must be CHMODed to 666 <A HREF=\"javascript:n_window('docs/installhelp.htm#chmod');\">[?]</A><br><br>";

	$decperms = fileperms("config.php");
	$octalperms = sprintf("%o",$decperms);
	$perms=(substr($octalperms,3));

	if($perms != 666)
	{
		echo "The config.php file does not have its permissions set properly. It is currently set to $perms. Please CHMOD config.php to 666, and then refresh the page.";
	}
	else
	{
		echo "The config file is set properly. You may proceed to the next step.";
		echo "<form method=\"POST\" enctype=\"multipart/form-data\" action=\"install.php\">";
		echo "<center><INPUT type=\"submit\" name=\"step2\" value=\"Continue-->\"></center>";
	}
}

else if($step2)
{
	echo "Step 1: Open up the data folder that was included with the script download, and edit the file dbconfig.php so that it contains the correct information for your database name, login, and password. <A HREF=\"javascript:n_window('docs/installhelp.htm#dbinfo');\">[?]</A><br><br>";
	echo "Step 2: For security reasons, it is recommended that you store dbconfig.php outside your web directory, so that it is not accessible by anyone browsing your site. Putting the file one level above your public_html or www folder is best (and sometimes there is already a data folder located there where you can put the dbconfig.php file). Please do not rename the dbconfig.php file.<br><br>";
	echo "Step 3: Please indicate the path to dbconfig.php relative to where the fanfiction files are stored. I.e. if the file is up one level and in a folder called data, you would input: ../data No trailing slash!<br><br>";
	echo "<form method=\"POST\" enctype=\"multipart/form-data\" action=\"install.php\">";
	echo "<table align=\"center\"><tr><td>Path: <INPUT name=\"databasepathnew\"></td></tr></table><br>";
	echo "<center><INPUT type=\"submit\" name=\"step2a\" value=\"Continue-->\"></center>";
}

else if($step2a)
{
	echo "Would you like your installed tables to have a prefix? This is not necessary, but will allow you to run multiple versions of the script off the same database. If not, just leave the field blank and continue on.";
	echo "<form method=\"POST\" enctype=\"multipart/form-data\" action=\"install.php\">";
	echo "<table align=\"center\"><tr><td>Table Prefix: <INPUT name=\"tableprefixnew\"><INPUT name=\"databasepathnew\" type=\"hidden\" value=\"$databasepathnew\"></td></tr></table><br>";
	echo "<center><INPUT type=\"submit\" name=\"step3\" value=\"Continue-->\"></center>";
}

else if($step3)
{

	writeconfig($databasepathnew, $tableprefixnew);

	if (file_exists($databasepathnew."/dbconfig.php"))
	{
    	echo "You have set the path correctly!";
    	echo "<form method=\"POST\" enctype=\"multipart/form-data\" action=\"install.php\">";
		echo "<center><INPUT type=\"hidden\" name=\"tableprefixnew\" value=\"$tableprefixnew\"><INPUT type=\"submit\" name=\"step4\" value=\"Continue-->\"></center>";

	}
	else
	{
    	echo "The path to dbconfig.php is not correct. Please try again:<br><br>";
    	echo "<form method=\"POST\" enctype=\"multipart/form-data\" action=\"install.php\">";
		echo "<table align=\"center\"><tr><td>Path: <INPUT name=\"databasepathnew\"><INPUT type=\"hidden\" name=\"tableprefixnew\" value=\"$tableprefixnew\"></td></tr></table><br>";
		echo "<center><INPUT type=\"submit\" name=\"step3\" value=\"Continue-->\"></center>";
	}

}

else if($step4)
{
	echo "The tables for the fanfiction script now must be installed into your database. If you know how to run mySQL files, you can take the tables.sql file ";
	echo "located in the docs folder and install them manually -- if you choose this, please do so immediately in another window, as the next step requires that ";
	echo "the table be installed. Or you can have the script install the tables for you. <A HREF=\"javascript:n_window('docs/installhelp.htm#tables');\">[?]</A><br><br>";
   	echo "<form method=\"POST\" enctype=\"multipart/form-data\" action=\"install.php\">";
	echo "<center><INPUT type=\"submit\" name=\"step5\" value=\"Script Install Tables\"><INPUT type=\"hidden\" name=\"tableprefixnew\" value=\"$tableprefixnew\"></center><br><br>";
   	echo "<form method=\"POST\" enctype=\"multipart/form-data\" action=\"install.php\">";
	echo "<center><INPUT type=\"submit\" name=\"step6\" value=\"Install Manually Myself\"></center>";
}

else if($step5)
{
	include("config.php");
	include ($databasepath."/dbconfig.php");

	
mysql_query("DROP TABLE IF EXISTS ".$tableprefixnew."fanfiction_authors;") or die(mysql_error());
  mysql_query("CREATE TABLE ".$tableprefixnew."fanfiction_authors (
  uid int(11) NOT NULL auto_increment,
  penname varchar(200) NOT NULL default '',
  realname varchar(200) NOT NULL default '',
  email varchar(200) NOT NULL default '',
  website varchar(200) NOT NULL default '',
  bio text NOT NULL,
  image varchar(200) NOT NULL default '',
  date datetime NOT NULL default '0000-00-00 00:00:00',
  newreviews int(11) NOT NULL default '0',
  admincreated int(11) NOT NULL default '0',
  password varchar(40) NOT NULL default '0',
  validated int(11) NOT NULL default '0',
  userskin varchar(60) NOT NULL default '',
  level tinyint(4) NOT NULL default '0',
  contact tinyint(4) NOT NULL default '0',
  carry tinyint(4) NOT NULL default '0',
  categories varchar(200) NOT NULL default '0',
  PRIMARY KEY  (uid),
  KEY penname (penname),
  KEY validated (validated),
  KEY admincreated (admincreated),
  KEY level (level),
  KEY contact (contact)
) TYPE=MyISAM;") or die(mysql_error());

mysql_query("DROP TABLE IF EXISTS ".$tableprefixnew."fanfiction_categories;") or die(mysql_error());
  	mysql_query("CREATE TABLE ".$tableprefixnew."fanfiction_categories (
  catid int(11) NOT NULL auto_increment,
  parentcatid int(11) NOT NULL default '-1',
  category varchar(60) NOT NULL default '',
  description text NOT NULL,
  image varchar(100) NOT NULL default '',
  locked int(11) NOT NULL default '0',
  leveldown tinyint(4) NOT NULL default '0',
  displayorder int(4) NOT NULL default '0',
  numitems int(11) NOT NULL default '0',
  PRIMARY KEY  (catid),
  KEY parentcatid (parentcatid),
  KEY category (category)
) TYPE=MyISAM;") or die(mysql_error());

mysql_query("DROP TABLE IF EXISTS ".$tableprefixnew."fanfiction_characters;") or die(mysql_error());
  mysql_query("CREATE TABLE ".$tableprefixnew."fanfiction_characters (
  charid int(11) NOT NULL auto_increment,
  catid int(11) NOT NULL default '0',
  charname varchar(60) NOT NULL default '',
  PRIMARY KEY  (charid),
  KEY catid (catid),
  KEY charname (charname)
) TYPE=MyISAM;") or die(mysql_error());

mysql_query("DROP TABLE IF EXISTS ".$tableprefixnew."fanfiction_comments;") or die(mysql_error());
  mysql_query("CREATE TABLE ".$tableprefixnew."fanfiction_comments (
  cid int(11) NOT NULL auto_increment,
  nid int(11) NOT NULL default '0',
  uname varchar(100) NOT NULL default '',
  comment text NOT NULL,
  time datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (cid),
  KEY nid (nid)
) TYPE=MyISAM;") or die(mysql_error());

mysql_query("DROP TABLE IF EXISTS ".$tableprefixnew."fanfiction_favauth;") or die(mysql_error());
mysql_query("CREATE TABLE ".$tableprefixnew."fanfiction_favauth (
  uid int(11) NOT NULL default '0',
  favuid int(11) NOT NULL default '0',
  KEY uid (uid,favuid)
) TYPE=MyISAM;") or die(mysql_error());
 
mysql_query("DROP TABLE IF EXISTS ".$tableprefixnew."fanfiction_favstor;") or die(mysql_error());
mysql_query("CREATE TABLE ".$tableprefixnew."fanfiction_favstor (
  uid int(11) NOT NULL default '0',
  sid int(11) NOT NULL default '0',
  KEY sid (sid),
  KEY uid (uid)
) TYPE=MyISAM;") or die(mysql_error());

mysql_query("DROP TABLE IF EXISTS ".$tableprefixnew."fanfiction_genres;") or die(mysql_error());
  	mysql_query("CREATE TABLE ".$tableprefixnew."fanfiction_genres (
  gid int(11) NOT NULL auto_increment,
  genre varchar(60) NOT NULL default '',
  PRIMARY KEY  (gid),
  KEY genre (genre)
) TYPE=MyISAM;") or die(mysql_error());

mysql_query("DROP TABLE IF EXISTS ".$tableprefixnew."fanfiction_news;") or die(mysql_error());
  	mysql_query("CREATE TABLE ".$tableprefixnew."fanfiction_news (
  nid int(11) NOT NULL auto_increment,
  author varchar(60) NOT NULL default '',
  title varchar(255) NOT NULL default '',
  story text NOT NULL,
  time datetime default NULL,
  PRIMARY KEY  (nid)
) TYPE=MyISAM;") or die(mysql_error());

mysql_query("DROP TABLE IF EXISTS ".$tableprefixnew."fanfiction_ratings;") or die(mysql_error());
  	mysql_query("CREATE TABLE ".$tableprefixnew."fanfiction_ratings (
  rid int(11) NOT NULL auto_increment,
  rating varchar(60) NOT NULL default '',
  ratingwarning int(11) NOT NULL default '0',
  warningtext text NOT NULL,
  PRIMARY KEY  (rid),
  KEY rating (rating)
) TYPE=MyISAM;") or die(mysql_error());

mysql_query("DROP TABLE IF EXISTS ".$tableprefixnew."fanfiction_reviews;") or die(mysql_error());
  	mysql_query("CREATE TABLE ".$tableprefixnew."fanfiction_reviews (
  reviewid int(11) NOT NULL auto_increment,
  sid int(11) NOT NULL default '0',
  psid int(11) NOT NULL default '0',
  reviewer varchar(60) NOT NULL default '0',
  member int(11) NOT NULL default '0',
  review text NOT NULL,
  date datetime NOT NULL default '0000-00-00 00:00:00',
  rating int(11) NOT NULL default '0',
  PRIMARY KEY  (reviewid),
  KEY sid (sid),
  KEY psid (psid),
  KEY rating (rating)
) TYPE=MyISAM;") or die(mysql_error());

mysql_query("DROP TABLE IF EXISTS ".$tableprefixnew."fanfiction_settings;") or die(mysql_error());
  	mysql_query("CREATE TABLE ".$tableprefixnew."fanfiction_settings (
  welcome text NOT NULL,
  thankyou text NOT NULL,
  nothankyou text NOT NULL,
  rules text NOT NULL,
  copyright text NOT NULL,
  help text NOT NULL
) TYPE=MyISAM;") or die(mysql_error());

mysql_query("DROP TABLE IF EXISTS ".$tableprefixnew."fanfiction_stories;") or die(mysql_error());
  	mysql_query("CREATE TABLE ".$tableprefixnew."fanfiction_stories (
  sid int(11) NOT NULL auto_increment,
  psid int(11) NOT NULL default '0',
  title varchar(200) NOT NULL default '',
  chapter varchar(200) NOT NULL default '',
  summary text NOT NULL,
  catid int(11) NOT NULL default '0',
  gid varchar(250) NOT NULL default '0',
  charid varchar(250) NOT NULL default '0',
  wid varchar(250) NOT NULL default '0',
  rid varchar(25) NOT NULL default '0',
  date datetime NOT NULL default '0000-00-00 00:00:00',
  updated datetime NOT NULL default '0000-00-00 00:00:00',
  uid int(11) NOT NULL default '0',
  featured int(11) NOT NULL default '0',
  counter int(11) NOT NULL default '0',
  validated int(11) NOT NULL default '0',
  inorder tinyint(4) NOT NULL default '0',
  storytext text NOT NULL,
  completed tinyint(4) NOT NULL default '0',
  rr tinyint(4) NOT NULL default '0',
  wordcount int(11) NOT NULL default '0',
  numreviews int(4) NOT NULL default '0',
  PRIMARY KEY  (sid),
  KEY psid (psid),
  KEY title (title),
  KEY catid (catid),
  KEY gid (gid),
  KEY charid (charid),
  KEY wid (wid),
  KEY rid (rid),
  KEY uid (uid),
  KEY featured (featured),
  KEY validated (validated),
  KEY completed (completed),
  KEY rr (rr)
) TYPE=MyISAM;") or die(mysql_error());

mysql_query("DROP TABLE IF EXISTS ".$tableprefixnew."fanfiction_warnings;") or die(mysql_error());
  	mysql_query("CREATE TABLE ".$tableprefixnew."fanfiction_warnings (
  wid int(11) NOT NULL auto_increment,
  warning varchar(60) NOT NULL default '',
  PRIMARY KEY  (wid),
  KEY warning (warning)
) TYPE=MyISAM;") or die(mysql_error());

mysql_query("INSERT INTO ".$tableprefixnew."fanfiction_settings VALUES ('This is a sample welcome message. It could say just about anything. It doesn\'t even need to be at the top of the page, as the template allows for it to be placed anywhere on the index page.','This is a default Thank You letter, that you can choose to send people upon accepting their story into the archive.','This is the default No Thank You letter, that you can choose to send people upon not accepting their story into the archive.','We have very basic submission rules: <br><br>\r\n\r\n1. No Mary Sues.<br><br>\r\n\r\n2. No crossovers.<br><br>\r\n\r\n3. Please check your grammar and spelling.<br><br>\r\n\r\nIf you accept the rules, please choose from the categories below.<br><br>','This is your copyright footer. You can put whatever you want here, but it makes sense to say something like \"This site is not affiliated with big scary corporations that could sue my pants off, blah, blah, blah.\" It sure would be nice if you kept a note in here, too, about where you got this script from ;)','<center><h4>Help Page</h4></center>\r\n\r\nYou could use this page for a help or FAQ page, or anything you wanted.')") or die(mysql_error());

echo "The tables have been installed correctly!<br><br>";
echo "<form method=\"POST\" enctype=\"multipart/form-data\" action=\"install.php\">";
echo "<center><INPUT type=\"submit\" name=\"step6\" value=\"Continue-->\"></center>";

}

else if($step6)
{
	echo "The final step is to create the main admin login and password. Please fill out the following fields:<br><br>";
	echo "<form method=\"POST\" enctype=\"multipart/form-data\" action=\"install.php\">
	<table align=\"center\"><tr><td colspan=\"2\">
	<b>New Admin</b>
	</td></tr><tr><td>
	Admin Login: <A HREF=\"javascript:n_window('docs/adminmanual.htm#adminlogin');\">[?]</A>
	</td><td>
	<INPUT name=\"penname\">
	</td></tr><tr><td>
	Password: <A HREF=\"javascript:n_window('docs/adminmanual.htm#adminpassword');\">[?]</A>
	</td><td>
	<INPUT name=\"password\" type=\"password\">
	</td></tr><tr><td>
	E-mail: <A HREF=\"javascript:n_window('docs/adminmanual.htm#adminemail');\">[?]</A>
	</td><td>
	<INPUT name=\"email\">
	</td></tr><tr><td>
	Contact when new fanfics submitted: <A HREF=\"javascript:n_window('docs/adminmanual.htm#admincontact');\">[?]</A>
	</td><td>
	<INPUT type=\"checkbox\" name=\"contact\">
	</td></tr><tr><td colspan=\"2\">
	<INPUT type=\"submit\" value=\"Continue-->\" name=\"step7\">
	</form></td></tr></table>";

}

else if($step7)
{
	include("config.php");
	include ($databasepath."/dbconfig.php");
	$encryptedpassword = md5($password);
	if($contact == "on")
		$contact = "1";
	else
		$contact = "0";
	mysql_query("INSERT INTO ".$tableprefix."fanfiction_authors (penname, password, email, level, contact, userskin, date) VALUES ('$penname', '$encryptedpassword', '$email', '1', '$contact', 'eFiction', now())") or die(mysql_error());
	echo "You are now finished installing the script. You should be able to go to the <a href=\"user.php\">log in page</a> and login with the admin login and password you just created.<br><br>";

	$safe_mode = ini_get("safe_mode");

	if(($safe_mode == "0") || ($safe_mode == ""))
	{
		echo "Safe Mode appears to currently be set to off in your PHP settings. This means that you can allow image uploads, if you so desire.<br><br>";
		echo "Please Note: You must CHMOD the stories folder to 777 if you intend to write story files to the server or allow image uploads.<br><br>";
	}
	if($safe_mode == "1")
	{
		echo "Safe Mode is currently set to on in your PHP settings. This means that you can not allow image uploads with stories. If you want to allow this, you must talk to your webhost, and ask them to set safe_mode to off (which they may not be willing to do).<br><br>";
		echo "Please Note: You must CHMOD the stories folder to 777 if you intend to write story files to the server (you can do this, even if safe_mode is set to on -- you just can't upload images).<br><br>";
	}

	echo "NOTE: the first time you login, you will get a blank screen after hitting submit -- when that happens, just hit the refresh button. This should only occur this one time -- it's a weird session bug that I haven't been able to fix.<br><br>";

	echo "<b>Please delete this file (install.php) from your web directory now! If you do not, you could be leaving a large security hole in your system!</b><br><br><br>";
	echo "<center><b>A big thank you to my beta-testers!</b><br>Theresa Sanchez<br> Khuffie<br> Mona Carol-Kaufman<br> Michele Bumbarger<br> Stephanie Smith<br> eFanfiction<br> Amy Cheng<br> arakune<br> Peganino<br> Ceit<br> brihana25<br> Annabelle Crane</center>";


}

else if($v1pt1step1)
{
		echo "<B>Please be sure to upload the new PHP files included with this release. Because some FTP programs won't overwrite files, it might be best to delete all files ending in ";
		echo ".php (except for your config.php file) and then upload. <font color=\"red\">Please be sure to backup your database before proceeding, just in case.</font> Once you've done so, please move to the below step.</b><br><br>";
		echo "There are some minor table changes that need to be made. Please press the button below to install them, or skip this step if you've already done it:<br><br>";
		echo "<form method=\"POST\" enctype=\"multipart/form-data\" action=\"install.php\">";
		echo "<center><INPUT type=\"submit\" name=\"v1pt1step2\" value=\"Make Table Changes\">";
		echo "<br><br><INPUT type=\"submit\" name=\"v1pt1step3\" value=\"Skip This Step\"></form></center>";
}

else if($v1pt1step2)
{
	include("config.php");
	include ($databasepath."/dbconfig.php");
mysql_query("ALTER TABLE fanfiction_authors ADD carry TINYINT DEFAULT 0 NOT NULL");
mysql_query("ALTER TABLE fanfiction_authors ADD categories TINYINT DEFAULT 0 NOT NULL");
mysql_query("ALTER TABLE fanfiction_categories ADD numitems INT DEFAULT 0 NOT NULL");

mysql_query("DROP TABLE IF EXISTS fanfiction_comments;") or die(mysql_error());
  mysql_query("CREATE TABLE fanfiction_comments (
  cid int(11) NOT NULL auto_increment,
  nid int(11) NOT NULL default '0',
  uname varchar(100) NOT NULL default '',
  comment text NOT NULL,
  time datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (cid),
  KEY nid (nid)
) TYPE=MyISAM;") or die(mysql_error());

mysql_query("DROP TABLE IF EXISTS fanfiction_favauth;") or die(mysql_error());
mysql_query("CREATE TABLE fanfiction_favauth (
  uid int(11) NOT NULL default '0',
  favuid int(11) NOT NULL default '0',
  KEY uid (uid,favuid)
) TYPE=MyISAM;") or die(mysql_error());

mysql_query("DROP TABLE IF EXISTS fanfiction_favstor;") or die(mysql_error());
mysql_query("CREATE TABLE fanfiction_favstor (
  uid int(11) NOT NULL default '0',
  sid int(11) NOT NULL default '0',
  KEY sid (sid),
  KEY uid (uid)
) TYPE=MyISAM;") or die(mysql_error());

	echo "The tables have been modified. The final step requires recounting all stories for the category counts (i.e. so they display the correct number of stories within them). If you do not wish to do this step, then skip it:<br><br>";
	echo "<form method=\"POST\" enctype=\"multipart/form-data\" action=\"install.php\">";
	echo "<center><INPUT type=\"submit\" name=\"v1pt1step3\" value=\"Update Category Story Counts\">";
	echo "<br><br><INPUT type=\"submit\" name=\"v1pt1step4\" value=\"Skip Next Step\"></form></center>";
}

else if($v1pt1step3)
{
	include("config.php");
	include ($databasepath."/dbconfig.php");
	
	mysql_query("UPDATE ".$tableprefix."fanfiction_categories SET numitems = '0'");
	
	$query = mysql_query("SELECT catid FROM ".$tableprefix."fanfiction_stories WHERE sid = psid AND validated = '1'");

	while($result = mysql_fetch_array($query))
	{
		//add one to the parent category2				
		mysql_query("UPDATE ".$tableprefix."fanfiction_categories SET numitems = (numitems + 1) WHERE catid = '$result[catid]'");
		//and then get the parentcategory so we can check it for a parent
		$catquery = mysql_query("SELECT catid, parentcatid FROM ".$tableprefix."fanfiction_categories WHERE catid = '$result[catid]'");
		$thiscat = mysql_fetch_array($catquery);
		
		//while there is a parent category
		while($thiscat[parentcatid] != "-1")
		{
			//add one to the parent category2				
			mysql_query("UPDATE ".$tableprefix."fanfiction_categories SET numitems = (numitems + 1) WHERE catid = '$thiscat[parentcatid]'");
			//and then get the parentcategory so we can check it for a parent
			$catquery2 = mysql_query("SELECT parentcatid, catid FROM ".$tableprefix."fanfiction_categories WHERE catid = '$thiscat[parentcatid]'");
			$thiscat = mysql_fetch_array($catquery2);
		}
	}	
	echo "Category counts have been made. Now all categories should display a total that includes the stories in their category, and their subcategories.";
	echo "If, at any point, the counts seem off because you've been deleting items in PHPmyadmin or some non-standard way, you can always upload this file again, and rerun this step to get correct totals.";
	echo "<form method=\"POST\" enctype=\"multipart/form-data\" action=\"install.php\">";
	echo "<center><INPUT type=\"submit\" name=\"v1pt1step4\" value=\"On to Final Step-->\"></form></center>";
}

else if($v1pt1step4)
{
	include("config.php");
	writeoldconfig();
	echo "The install is now over. There are many new features and settings that you will want to check out in the admin area.<br><br>";
	//echo "One major setting you will need to modify immediately to make your site work correctly is the \"Stories Path\" setting. At this time, please go to your settings and input \"stories\" (no quotes) into that field, if you are storing your stories on the server and not using mySQL to store them.<br><br>";
	echo "Please note: if you want your current install of eFiction to have a table prefix (really only required if you want to run multiple versions of the script off the same database), you must follow the below steps:<br><br>";
	echo "<blockquote>1) Modify your current tables using PHPmyAdmin or a similar program. Rename the tables so that they have the desired prefix (i.e. prefix = \"rivka_\", and you would rename your tables rivka_fanfiction_authors and so on).";
	echo "<br><br>2) Go to the Settings in the admin area on your site, and input the prefix into the appropriate section. Save.</blockquote>";
	echo "Once, you've done that, your current install should now have a prefix, allowing you to run multiple versions of the program (all with different prefixes) on the same database.";
	echo "<br><br><b>Please delete this file immediately, to prevent someone from hacking/removing your database.</b>";
		
}

else
{
	echo "Welcome to the installation file of the eFiction 1.1 program.<BR><BR>";
	echo "Please Note: to run this script, register_globals in your PHP settings must be turned on. <A HREF=\"javascript:n_window('docs/installhelp.htm#php');\">[?]</A><br><br>";


	$register_globals = ini_get("register_globals");

	if($register_globals == 1)
	{
		echo "register_globals appears to be set to on. You may proceed to the next step. Please choose from below:";
		echo "<form method=\"POST\" enctype=\"multipart/form-data\" action=\"install.php\">";
		echo "<center><INPUT type=\"submit\" name=\"step1\" value=\"Brand New Install\">";
		echo "<br><br><INPUT type=\"submit\" name=\"v1pt1step1\" value=\"Upgrade From v.1.0\"></form></center>";
	}
	else
	{
		echo "register_globals does not appear to be turned on. If you have shared webhosting, this ";
		echo "is not something that you can modify yourself. You must contact your webhost, and ask them ";
		echo "\"set register_globals to On in php.ini.\"";
	}
}

echo "</font></td></tr></table></body></html>";

function writeconfig($databasepathnew, $tableprefixnew)
{
		if(!$fp = fopen("config.php",w))
	{
		echo "There was an error writing to the config file. Are you sure it's chmoded to 666?";
		exit;
	}

	$content = "<?php\n"
		."\n"
		."//Config File--you can edit this by hand, but it's preferable to use the admin panel\n"
		."\n"
		."//Sitename\n"
		."\n"
		."\$sitename = \"Enter Sitename\";\n"
		."\n"
		."//Slogan\n"
		."\n"
		."\$slogan = \"This is a great slogan!\";\n"
		."\n"
		."//Site URL\n"
		."\n"
		."\$url = \"http://www.yoursite.com\";\n"
		."\n"
		."//Admin E-mail\n"
		."\n"
		."\$siteemail = \"admin@yoursite.com\";\n"
		."\n"
		."//Database Config Path\n"
		."\n"
		."\$databasepath = \"$databasepathnew\";\n"
		."\n"
		."//Stories Path\n"
		."\n"
		."\$storiespath = \"stories\";\n"
		."\n"
		."//Table Prefix\n"
		."\n"
		."\$tableprefix = \"$tableprefixnew\";\n"
		."\n"
		."//News Comments\n"
		."\n"
		."\$newscomments = \"1\";\n"
		."\n"
		."//Number of Updated Stories\n"
		."\n"
		."\$numupdated = \"8\";\n"
		."\n"
		."//Date Format\n"
		."\n"
		."\$dateformat = \"1\";\n"
		."\n"
		."//News Date Format\n"
		."\n"
		."\$newsdate = \"1\";\n"
		."\n"
		."//Allow Favorites\n"
		."\n"
		."\$favorites = \"1\";\n"
		."\n"
		."//Store stories\n"
		."\n"
		."\$store = \"files\";\n"
		."\n"
		."//Automatically validate stories; yes = 1, no = 0\n"
		."\n"
		."\$autovalidate = \"0\";\n"
		."\n"
		."//Number of categories; if only one, will shorten some processes\n"
		."\n"
		."\$numcats = \"0\";\n"
		."\n"
		."//Allow readers to submit reviews; yes = 1, no = 0\n"
		."\n"
		."\$reviews = \"1\";\n"
		."\n"
		."//Rating system, in addition to reivews; none = 0, stars = 1, like/dislike = 2\n"
		."\n"
		."\$ratings = \"0\";\n"
		."\n"
		."//Allow Round Robins; yes = 1, no = 0\n"
		."\n"
		."\$roundrobins = \"0\";\n"
		."\n"
		."//Turn off submissions completely; yes = 1, no = 0\n"
		."\n"
		."\$submissionsoff = \"0\";\n"
		."\n"
		."//Allow Anonymous reviews; yes = 1, no = 0\n"
		."\n"
		."\$anonreviews = \"0\";\n"
		."\n"
		."//Number of items per page in search results\n"
		."\n"
		."\$itemsperpage = \"15\";\n"
		."\n"
		."//Allow image uploads with stories; yes = 1, no = 0\n"
		."\n"
		."\$imageupload = \"0\";\n"
		."\n"
		."//Max image height\n"
		."\n"
		."\$imageheight = \"200\";\n"
		."\n"
		."//Max image width\n"
		."\n"
		."\$imagewidth = \"200\";\n"
		."\n"
		."//Default Skin\n"
		."\n"
		."\$skin = \"eFiction\";\n"
		."\n"
		.'?>';

	fwrite($fp, $content);
	fclose($fp);
	return;
}

function writeoldconfig()
{
	global $tableprefix, $sitename, $slogan, $url, $store, $autovalidate, $numcats, $reviewsallowed, $ratings, $roundrobins, $submissionsoff, $anonreviews, $itemsperpage, $imageupload, $imagewidth, $imageheight, $skin, $siteemail, $databasepath, $columns, $newscomments, $numupdated, $dateformat, $favorites, $newsdate, $storiespath;
	if(!$fp = fopen("config.php",w))
	{
		echo "There was an error writing to the config file. Are you sure it's chmoded to 666?";
		exit;
	}

	$content = "<?php\n"
		."\n"
		."//Config File--you can edit this by hand, but it's preferable to use the admin panel\n"
		."\n"
		."//Sitename\n"
		."\n"
		."\$sitename = \"$sitename\";\n"
		."\n"
		."//Slogan\n"
		."\n"
		."\$slogan = \"$slogan\";\n"
		."\n"
		."//Site URL\n"
		."\n"
		."\$url = \"$url\";\n"
		."\n"
		."//Admin E-mail\n"
		."\n"
		."\$siteemail = \"$siteemail\";\n"
		."\n"
		."//Database Config Path\n"
		."\n"
		."\$databasepath = \"$databasepath\";\n"
		."\n"
		."//Database Config Path\n"
		."\n"
		."\$storiespath = \"stories\";\n"
		."\n"
		."//Table Prefix\n"
		."\n"
		."\$tableprefix = \"$tableprefix\";\n"
		."\n"
		."//News Comments\n"
		."\n"
		."\$newscomments = \"1\";\n"
		."\n"
		."//Number of Updated Stories\n"
		."\n"
		."\$numupdated = \"$numupdated\";\n"
		."\n"
		."//Date Format\n"
		."\n"
		."\$dateformat = \"1\";\n"
		."\n"
		."//News Date Format\n"
		."\n"
		."\$newsdate = \"1\";\n"
		."\n"
		."//Allow Favorites\n"
		."\n"
		."\$favorites = \"1\";\n"
		."\n"
		."//Store stories\n"
		."\n"
		."\$store = \"$store\";\n"
		."\n"
		."//Automatically validate stories; yes = 1, no = 0\n"
		."\n"
		."\$autovalidate = \"$autovalidate\";\n"
		."\n"
		."//Number of categories; if only one, will shorten some processes\n"
		."\n"
		."\$numcats = \"$numcats\";\n"
		."\n"
		."//Number of columns to display the categories in\n"
		."\n"
		."\$columns = \"$columns\";\n"
		."\n"
		."//Allow readers to submit reviews; yes = 1, no = 0\n"
		."\n"
		."\$reviewsallowed = \"$reviewsallowed\";\n"
		."\n"
		."//Rating system, in addition to reviews; none = 0, stars = 1, like/dislike = 2\n"
		."\n"
		."\$ratings = \"$ratings\";\n"
		."\n"
		."//Allow Round Robins; yes = 1, no = 0\n"
		."\n"
		."\$roundrobins = \"$roundrobins\";\n"
		."\n"
		."//Turn off submissions completely; yes = 1, no = 0\n"
		."\n"
		."\$submissionsoff = \"$submissionsoff\";\n"
		."\n"
		."//Allow Anonymous reviews; yes = 1, no = 0\n"
		."\n"
		."\$anonreviews = \"$anonreviews\";\n"
		."\n"
		."//Number of items per page in search results\n"
		."\n"
		."\$itemsperpage = \"$itemsperpage\";\n"
		."\n"
		."//Allow image uploads with stories; yes = 1, no = 0\n"
		."\n"
		."\$imageupload = \"$imageupload\";\n"
		."\n"
		."//Max image height\n"
		."\n"
		."\$imageheight = \"$imageheight\";\n"
		."\n"
		."//Max image width\n"
		."\n"
		."\$imagewidth = \"$imagewidth\";\n"
		."\n"
		."//Default Skin\n"
		."\n"
		."\$skin = \"$skin\";\n"
		."\n"
		.'?>';

	fwrite($fp, $content);
	fclose($fp);
	return;
}

?>