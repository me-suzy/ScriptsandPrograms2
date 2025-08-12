<?php
/*
////////////////////////////////////////////////
//             Utopia Software                //
//      http://www.utopiasoftware.net         //
//             Utopia News Pro                //
////////////////////////////////////////////////
*/

/*
	+-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-
	| Install Script
	| Version - 1.1.5
	| Instructions: Upload to Utopia News Pro directory and
	|               run. Immediately remove file from server
	|               after use.
	+-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-
*/

$installer_version = '1.1.5';
require('config.inc.php');
error_reporting(E_ALL & ~E_NOTICE);

// +------------------------------------------------------------------+
// | Step Setup                                                       |
// +------------------------------------------------------------------+
if (isset($_POST['step1']))
{
	$step = 1; // <--License
}
elseif (isset($_POST['step2']))
{
	$step = 2; // <--Configuration Check
}
elseif (isset($_POST['step3']))
{
	$step = 3; // <--Create Tables
}
elseif (isset($_POST['step4']))
{
	$step = 4; // <--Populating Tables
}
elseif (isset($_POST['step5']))
{
	$step = 5; // <--Settings Selection
}
elseif (isset($_POST['step6']))
{
	$step = 6; // <--Settings Insertion
}
elseif (isset($_POST['step7']))
{
	$step = 7; // <--Admin User Selection
}
elseif (isset($_POST['step8']))
{
	$step = 8; // <--Admin User Insertion
}
elseif (isset($_POST['step9']))
{
	$step = 9; // <--Complete
}
elseif (isset($_POST['step10']))
{
	$step = 10; // <--Extra - Database Empty
}
else
{
	$step = 0; // <--Introduction
}
// +------------------------------------------------------------------+
// | Global Installer HTML                                            |
// +------------------------------------------------------------------+
$install_header = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<link rel="stylesheet" href="style.css" />
	<title>Utopia News Pro Installer - Version '.$installer_version.'</title>
<meta name="GENERATOR" content="Utopia News Pro - http://www.utopiasoftware.net/" />
<meta name="ROBOTS" content="noindex, nofollow" />
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
</head>
<body>

<center>
<div align="left" class="hbox">
<div class="lbox"><img src="images/unp_logo.jpg" alt="Utopia News Pro" border="0" /></div>
</div></center>
<br />';

$install_openbox = '<center>
<div align="left" class="box">
<font class="normalfont">
<strong>Utopia News Pro Installer - Step '.$step.' of 9</strong><br /><br />';

$install_closebox = '</font></div></center>';

$install_footer = '
<br />
<center>
<div align="center" class="fbox">
<span class="smallfont">
Utopia News Pro '.$installer_version.' Installer<br />
Copyright &copy;2003 UtopiaSoft, UtopiaSoftware.net
</span>
</div></center>
</body>
</html>';

// +------------------------------------------------------------------+
// | Step 0 - Introduction                                            |
// +------------------------------------------------------------------+
if ($step == 0)
{
	echo ($install_header);
	echo ($install_openbox);
	echo 'Welcome to the Utopia News Pro installer! The installer will first check your system to make sure it is configured correctly to work with UNP.<br /><br />';
	echo 'Please proceed to the first step.<br /><br />';
	echo '<form action="install.php" method="post"><input type="submit" name="step1" value="Continue -&gt;" /></form>';
	echo ($install_closebox);
	echo ($install_footer);
}

// +------------------------------------------------------------------+
// | Step 1 - License                                                 |
// +------------------------------------------------------------------+
if ($step == 1)
{
	$openLicense = fopen('./license.txt', 'r');
	$licenseContents = fread($openLicense, filesize('./license.txt'));
	fclose($openLicense);
	echo ($install_header);
	echo ($install_openbox);
	echo '<div style="width: 600px; margin: 0 auto;">';
	echo 'Please read the following License Agreement and Disclaimer of Warranty.';
	echo '<div class="license">'.$licenseContents.'</div>';
	echo 'Do you accept all the terms of the preceding License Agreement and Disclaimer of Warranty? If you choose No, the installer will cancel. To install Utopia News Pro, you must accept this agreement.';
	echo '<form style="margin-top: 10px;" action="install.php" method="post"><input type="submit" name="step2" value="Yes" />&nbsp;<input type="submit" name="step0" value="No" /></form>';
	echo '</div>';
	echo ($install_closebox);
	echo ($install_footer);
}

// +------------------------------------------------------------------+
// | Step 2 - Check Database Connection                               |
// +------------------------------------------------------------------+
if ($step == 2)
{
	echo ($install_header);
	echo ($install_openbox);
	$config_error = 0;
	echo 'Utopia News Pro is now performing a routine check to ensure that it can connect to the MySQL database.<br /><br />';
	/***************************************************************
	   Start Database Support Attempt
	***************************************************************/
	if (function_exists('mysql_connect'))
	{
		echo '<img src="images/icon_ok.gif" alt="MySQL Supported" /> MySQL supported in PHP<br /><br />';
	}
	else
	{
		echo '<img src="images/icon_bad.gif" alt="MySQL Not Supported" /> MySQL not supported in PHP<br /><br />';
	}
	/***************************************************************
	   Start Database Connection Attempt
	***************************************************************/
	$db_cnx_error = 0;
	if ($config['password'] == '')
	{
		$dbcnx = @mysql_connect($config['hostname'], $config['user']);
		if (!$dbcnx)
		{
			$db_cnx_error++;
			$config_error++;
		}
	}
	else
	{
		$dbcnx = @mysql_connect($config['hostname'], $config['user'], $config['password']);
		if (!$dbcnx)
		{
			$db_cnx_error++;
			$config_error++;
		}
	}
	/***************************************************************
	   Database Success or Error
	***************************************************************/
	if ($db_cnx_error == 0)
	{
		echo '<img src="images/icon_ok.gif" alt="MySQL Connection Success" /> MySQL successfully connected<br /><br />';
		echo "\n";
	}
	else
	{
		echo '<img src="images/icon_bad.gif" alt="MySQL Connection Failure" /> MySQL connection failure<br /><br />';
		echo "\n";
	}
	/***************************************************************
	   Start Database Select Attempt
	***************************************************************/
	$db_select_error = 0;
	$dbselect = @mysql_select_db($config['database']);
	if (!$dbselect)
	{
		$db_select_error++;
		$config_error++;
	}
	/***************************************************************
	   Start Select Success or Error
	***************************************************************/
	if ($db_select_error == 0)
	{
		echo '<img src="images/icon_ok.gif" alt="Database Selection Success" /> Successfully selected the MySQL database<br /><br />';
		echo "\n";
	}
	else
	{
		echo '<img src="images/icon_bad.gif" alt="Database Selection Failure" /> Error selecting the appropriate MySQL database!<br /><br />';
		echo "\n";
	}
	/***************************************************************
	   Start Empty Database Check
	***************************************************************/
	if ($config_error == 0)
	{
		$db_empty_check = mysql_query("SHOW TABLES");
		$db_num_tables = mysql_num_rows($db_empty_check);
		if ($db_num_tables == 0)
		{
			echo '<img src="images/icon_ok.gif" alt="Database Empty" /> Your database is empty.<br /><br />';
		}
		else
		{
			echo '<img src="images/icon_bad.gif" alt="Database Not Emtpy" /> Your database is not empty. You should still be able to continue with the installation as long as your existing tables do not conflict with UNP names (unp_<i>tablename</i>). You <i>may</i> empty your database, but it should not be necessary.<br /><br />';
			$db_empty_option = 1;
		}
	}
	/***************************************************************
	   PHP Version Check
	***************************************************************/
	if (phpversion() < '4.1.0')
	{
		echo '<img src="images/icon_bad.gif" alt="PHP Version Incompatible" /> Your version of PHP is out-of-date and not compatible with UNP. You must have at <i>least</i> PHP 4.1.0 to run UNP. Please upgrade.<br /><br />';
		echo "\n";
		$config_error = $config_error + 10;
	}
	else
	{
		echo '<img src="images/icon_ok.gif" alt="PHP Version Success" /> Your version of PHP is compatible with UNP<br /><br />';
		echo "\n";
	}
	/***************************************************************
	   Success? If so, go onto next step
	***************************************************************/
	if ($config_error == 0)
	{
		if ($db_empty_option == 1)
		{
			echo '<form action="install.php" method="post"><input type="submit" name="step10" value="Empty Database" /></form>';
		}
		echo '<form action="install.php" method="post"><input type="submit" name="step3" value="Continue -&gt;" /></form>';
	}
	elseif ($config_error == 10)
	{
		echo 'You must upgrade you version of PHP before continuing.';
	}
	elseif ($config_error > 10)
	{
		echo 'There were some problems in the initial configuration, as well as a PHP version incompatibility with UNP.';
	}
	else
	{
		echo 'There were some problems in the initial configuration. Please check the config.inc.php file and make certain that the correct values are in place.';
	}
	echo ($install_closebox);
	echo ($install_footer);
}

// +------------------------------------------------------------------+
// | Step 3 - Create Tables                                           |
// +------------------------------------------------------------------+
if ($step == 3)
{
	echo ($install_header);
	echo ($install_openbox);
	echo 'Installer is now creating database tables used by UNP...<br /><br />';
	/***************************************************************
	   Reconnect To Database
	***************************************************************/
	require('functions.inc.php');
	/***************************************************************
	   Start Create Tables
	***************************************************************/
	echo 'Creating table <strong>unp_comments</strong>...<br /><br />';
	$DB->query("CREATE TABLE `unp_comments` (
	`id` smallint(6) unsigned NOT NULL auto_increment,
	`newsid` smallint(5) NOT NULL default '0',
	`name` varchar(50) NOT NULL default '',
	`email` varchar(50) NOT NULL default '',
	`date` int(10) NOT NULL default '0',
	`title` varchar(100) NOT NULL default '',
	`comments` mediumtext NOT NULL,
	`ipaddress` varchar(15) NOT NULL default '',
	`proxy` varchar(15) NOT NULL default '',
	PRIMARY KEY  (`id`),
	KEY `newsid` (`newsid`)
	) TYPE=MyISAM
	");
	
	echo 'Creating table <strong>unp_faq_categories</strong>...<br /><br />';
	$DB->query("CREATE TABLE `unp_faq_categories` (
	`id` smallint(2) unsigned NOT NULL auto_increment,
	`display` smallint(2) unsigned NOT NULL default '0',
	`catname` varchar(50) NOT NULL default '',
	PRIMARY KEY  (`id`)
	) TYPE=MyISAM
	");
	
	echo 'Creating table <strong>unp_faq_questions</strong>...<br /><br />';
	$DB->query("CREATE TABLE `unp_faq_questions` (
	`id` smallint(3) unsigned NOT NULL auto_increment,
	`groupid` smallint(2) unsigned NOT NULL default '0',
	`question` varchar(100) NOT NULL default '',
	`answer` mediumtext NOT NULL,
	PRIMARY KEY  (`id`),
	KEY `groupid` (`groupid`)
	) TYPE=MyISAM
	");
	
	echo 'Creating table <strong>unp_news</strong>...<br /><br />';
	$DB->query("CREATE TABLE `unp_news` (
	`newsid` smallint(5) unsigned NOT NULL auto_increment,
	`date` int(10) unsigned NOT NULL default '0',
	`subject` varchar(100) NOT NULL default '',
	`news` mediumtext NOT NULL,
	`posterid` smallint(5) unsigned NOT NULL default '0',
	`poster` varchar(50) NOT NULL default '',
	`comments` smallint(6) NOT NULL default '0',
	PRIMARY KEY  (`newsid`),
	KEY `poster` (`poster`)
	) TYPE=MyISAM
	");
	
	echo 'Creating table <strong>unp_setting</strong>...<br /><br />';
	$DB->query("CREATE TABLE `unp_setting` (
	`id` smallint(5) unsigned NOT NULL auto_increment,
	`display` smallint(3) unsigned NOT NULL default '0',
	`title` varchar(100) NOT NULL default '',
	`varname` varchar(20) NOT NULL default '',
	`value` mediumtext NOT NULL,
	`description` mediumtext NOT NULL,
	`optioncode` mediumtext NOT NULL,
	PRIMARY KEY  (`id`)
	) TYPE=MyISAM
	");

	echo 'Creating table <strong>unp_style</strong>...<br /><br />';
	$DB->query("CREATE TABLE `unp_style` (
	`id` smallint(3) unsigned NOT NULL auto_increment,
	`title` varchar(50) NOT NULL default '',
	`varname` varchar(20) NOT NULL default '',
	`value` varchar(70) NOT NULL default '',
	PRIMARY KEY  (`id`),
	KEY `title` (`title`)
	) TYPE=MyISAM
	");

	echo 'Creating table <strong>unp_template</strong>...<br /><br />';
	$DB->query("CREATE TABLE `unp_template` (
	`id` smallint(4) unsigned NOT NULL auto_increment,
	`setid` smallint(3) unsigned NOT NULL default '0',
	`templatename` varchar(50) NOT NULL default '',
	`template` mediumtext NOT NULL,
	PRIMARY KEY  (`id`),
	KEY `templatename` (`templatename`)
	) TYPE=MyISAM
	");
	
	echo 'Creating table <strong>unp_user</strong>...<br /><br />';
	$DB->query("CREATE TABLE `unp_user` (
	`userid` smallint(5) unsigned NOT NULL auto_increment,
	`groupid` smallint(5) unsigned NOT NULL default '0',
	`username` varchar(50) NOT NULL default '',
	`password` varchar(32) NOT NULL default '',
	`email` varchar(50) NOT NULL default '',
	PRIMARY KEY  (`userid`),
	KEY `username` (`username`)
	) TYPE=MyISAM
	");
	
	echo 'Successfully created all tables.';
	echo '<form action="install.php" method="post"><input type="submit" name="step4" value="Continue -&gt;" /></form>';
	echo ($install_closebox);
	echo ($install_footer);
}

// +------------------------------------------------------------------+
// | Step 4 - Insert Default Data                                     |
// +------------------------------------------------------------------+
if ($step == 4)
{
	echo ($install_header);
	echo ($install_openbox);
	echo 'Installer is now inserting default data into previously created tables...<br /><br />';
	/***************************************************************
	   Reconnect To Database
	***************************************************************/
	require('functions.inc.php');
	/***************************************************************
	   Start Populate Tables
	***************************************************************/
	echo 'Populating table <strong>unp_faq_categories</strong>...<br /><br />';
	$DB->query("INSERT INTO `unp_faq_categories` (`id`, `display`, `catname`) VALUES
	(1, 1, 'Posting News'),
	(2, 3, 'News Cache'),
	(3, 2, 'Editing News'),
	(4, 4, 'Settings'),
	(5, 5, 'Styles'),
	(6, 6, 'User Management'),
	(7, 10, 'Miscellaneous'),
	(8, 8, 'Comments'),
	(9, 9, 'Templates Editor'),
	(10, 7, 'Profile Management')
	");
	
	echo 'Populating table <strong>unp_faq_questions</strong>...<br /><br />';
	$DB->query("INSERT INTO `unp_faq_questions` (`id`, `groupid`, `question`, `answer`) VALUES
	(1, 1, 'How do I post news?', 'To post news to the database, click either on the \"Post News\" link in the header or on the main News Pro page. Once on the news posting page, fill in both the Subject and News fields with the news subject and content respectively. When done, press Post News, and your news will be submitted. (NOTE: If you make use of the News Cache, you will need to update the cache manually if it is not set to automatically update.)'),
	(2, 1, 'Why do I receive errors when trying to submit news?', 'You are most likely not filling in both the subject and news fields. Both fields are required to submit news.'),
	(3, 1, 'Why do the dates on the posting page and the actual post date differ?', 'The date shown on the news posting page is the time that the page was loaded. In most cases, it will take some time to enter the news, and the date and time upon which the news is actually submitted usually differs from the time the page was loaded. The time of submission is the time recorded.'),
	(4, 2, 'What is the news cache?', 'The news cache saves the display of the dynamic news page into a single text file. This greatly increases the speed of displaying news (especially on high-traffic sites) as it does not have to be extracted from the database and dynamically altered; it can merely be pulled out of a static text file very quickly.'),
	(5, 2, 'How do I use the news cache?', 'To use the news cache, first submit news as you normally would. Then, go to the news cache management page and click either \"Update News Cache\" or \"Create News Cache\". After submitting new news or after editing news, you must click \"Update News Cache\" again unless Auto Cache Update is turned ON in the settings.'),
	(6, 2, 'When would I use the news cache?', 'You may use the cache for many reasons.<blockquote>1. You have a high-traffic site in which you want to take the extra load posed by dynamic news off the server.<br />2. You wish to speed up your news display by converting it into static files.<br />3. You wish to ensure that your news page does not display un-professional looking errors in times when MySQL might be down.</blockquote>'),
	(7, 3, 'How do I edit my news?', 'To edit existing news, click on the \"Edit News\" link in the header or on the main News Pro page. Navigate to the news post which you want to edit and make necessary changes. Then, press Submit to commit the changes to the database.\r\n(NOTE: If you make use of the News Cache, you will need to update the cache manually if it is not set to automatically update.)'),
	(8, 3, 'How do I delete news?', 'To delete existing news, click on the \"Edit News\" link in the header or on the main News Pro page. Navigate to the news post which you want to delete and click the checkbox next to Delete News. Then, press Submit to delete the news from the database.\r\n(NOTE: If you make use of the News Cache, you will need to update the cache manually if it is not set to automatically update.)'),
	(9, 4, 'Why should I turn HTML off?', 'You should turn HTML off because, with it on, it could allow users to entirely alter the news display page in a very bad way. Instead, if you wish to be able to do many HTML formatting techniques, use UNP code.'),
	(10, 4, 'What are UNP codes?', 'UNP codes are replacements for certain basic HTML codes. They are as follows:<br />\r\n<table border=\"1\" align=\"left\" cellpadding=\"1\" cellspacing=\"0\">\r\n	<tr>\r\n		<td width=\"50%\"><center><font class=\"normalfont\">[b]This text is bold.[/b]</font></center></td>\r\n		<td width=\"50%\"><center><font class=\"normalfont\"><strong>This text is bold.</strong></font></center></td>\r\n	</tr>\r\n	<tr>\r\n		<td width=\"50%\"><center><font class=\"normalfont\">[i]This text is italicized.[/i]</font></center></td>\r\n		<td width=\"50%\"><center><font class=\"normalfont\"><em>This text is italicized.</em></font></center></td>\r\n	</tr>\r\n	<tr>\r\n		<td width=\"50%\"><center><font class=\"normalfont\">[u]This text is underlined.[/u]</font></center></td>\r\n		<td width=\"50%\"><center><font class=\"normalfont\"><u>This text is underlined.</u></font></center></td>\r\n	</tr>\r\n	<tr>\r\n		<td width=\"50%\"><center><font class=\"normalfont\">[url]http://www.utopiasoftware.net[/url]</font></center></td>\r\n		<td width=\"50%\"><center><font class=\"normalfont\"><a href=\"http://www.utopiasoftware.net/\" target=\"_blank\"><font class=\"normalfont\">http://www.utopiasoftware.net</font></a></font></center></td>\r\n	</tr>\r\n	<tr>\r\n		<td width=\"50%\"><center><font class=\"normalfont\">[url=http://www.utopiasoftware.net]UtopiaSoftware[/url]</font></center></td>\r\n		<td width=\"50%\"><center><a href=\"http://www.utopiasoftware.net/\" target=\"_blank\"><font class=\"normalfont\">UtopiaSoftware</font></a></center></td>\r\n	</tr>\r\n	<tr>\r\n		<td width=\"50%\"><center><font class=\"normalfont\">[email]support@utopiasoftware.net[/email]</font></center></td>\r\n		<td width=\"50%\"><center><a href=\"mailto:support@utopiasoftware.net\" target=\"_blank\"><font class=\"normalfont\">support@utopiasoftware.net</font></a></center></td>\r\n	</tr>\r\n	<tr>\r\n		<td width=\"50%\"><center><font class=\"normalfont\">[email=support@utopiasoftware.net]Email Us[/url]</font></center></td>\r\n		<td width=\"50%\"><center><a href=\"mailto:support@utopiasoftware.net\" target=\"_blank\"><font class=\"normalfont\">Email Us</font></a></center></td>\r\n	</tr>\r\n	<tr>\r\n		<td width=\"50%\"><center><font class=\"normalfont\">[color=red]This text is red.[/color]</font></center></td>\r\n		<td width=\"50%\"><center><font class=\"normalfont\"><font color=\"#FF0000\">This text is red.</font></font></center></td>\r\n	</tr>\r\n	<tr>\r\n		<td width=\"50%\"><center><font class=\"normalfont\">[size=3]This text is size 3.[/size]</font></center></td>\r\n		<td width=\"50%\"><center><font class=\"normalfont\"><font size=\"3\">This text is size 3.</font></font></center></td>\r\n	</tr>\r\n	<tr>\r\n		<td width=\"50%\"><center><font class=\"normalfont\">[blockquote]This text is blockquoted.[/blockquote]</font></center></td>\r\n		<td width=\"50%\"><font class=\"normalfont\"><blockquote>This text is blockquoted.</blockquote></font></td>\r\n	</tr>\r\n	<tr>\r\n		<td width=\"50%\"><center><font class=\"normalfont\">[img]http://www.utopiasoftware.net/images/icon_ok.gif[/img]</font></center></td>\r\n		<td width=\"50%\"><center><img src=\"images/icon_ok.gif\" alt=\"Image\"></center></td>\r\n	</tr>\r\n		<td width=\"50%\"><center><font class=\"normalfont\">[hr] - Horizontal Ruler:</font></center></td>\r\n		<td width=\"50%\"><center><hr /></center></td>\r\n	</tr>\r\n</table>'),
	(11, 4, 'What are the date/time formats for?', 'This is the format of how you want the date and time to be displayed throughout Utopia News Pro as well as on the news display page. If you are unsure of what to fill in, either leave them at the defaults or check the resources listed for more information.'),
	(12, 5, 'What are the styles colors used for?', 'The styles colors are used on the news display page (news.php and news.txt). Colors entered here are automatically sent to replace the default colors on the news display page.'),
	(13, 5, 'What kind of values am I supposed to enter for a style color?', 'You can enter either hexadecimal values or color names. For example, if you want red, you can enter either \"red\" or \"#FF0000\" (including the #). Either way, the color will be red. Generally it is best to use hexadecimal values when possible.'),
	(14, 6, 'How do I add another user?', 'To add another user to the database, click either on the \"Manage Users\" link in the header or on the main News Pro page. Then, click on the \"Add User\" link. Once on the new user page, fill in all fields and select a user level. When done, press Add User.'),
	(15, 6, 'How do I edit a previously created user?', 'To edit an existing user, click on the \"Manage users\" link in the header or on the main News Pro page. Find the user you want to edit on the main users list, and click on the [Edit] link next to their name. Again, fill in all fields, and press Submit Changes, and the changes will be committed to the database.'),
	(16, 6, 'How do I remove a user?', 'To remove an existing user, click on the \"Manage users\" link in the header or on the main News Pro page. Find the user you want to edit on the main users list, and click on the [Remove] link next to their name. On the next page, you will be prompted with a final chance to cancel the action. Confirm that you want to remove the user by pressing Yes, and the user will be removed. Additionally, you can navigate to the delete users prompt by going to edit a user\'s profile first, and then clicking Delete This User.'),
	(17, 6, 'What is the purpose of the user levels?', 'The user levels allow you to create special users who have more or less abilities than other users. This ensures that you don\'t trust too many people with too many abilities.'),
	(18, 6, 'What are the user levels?', 'The user levels are as follows:<blockquote>\r\n<b>Administrator</b> - Has access to all settings and styles, can post news, can edit <i>all</i> news<br /><b>Enhanced Level</b> - Can not edit settings or styles, can post news, can edit <i>all</i> news<br /><b>Standard Level</b> - Can not edit settings or styles, can post news, can edit only own news</blockquote>'),
	(19, 5, 'Why don\'t the example color boxes update?', 'The example color boxes update upon submission of changes and the reloading of the page, <i>not</i> upon entering a color value.'),
	(20, 5, 'Why are my colors showing up incorrectly?', 'Colors will only show up incorrectly if you have not entered a correct/valid value. Ensure that you have entered the correct hexadecimal value (including the #) or have spelled the color correctly.'),
	(21, 4, 'What are the smilies?', 'The smilies are small emoticons that can be used in news posts. They are as follows:<br />\r\n:) - <img src=\"images/smilies/happy.gif\" /><br />\r\n:( - <img src=\"images/smilies/sad.gif\" /><br />\r\n;) - <img src=\"images/smilies/wink.gif\" /><br />\r\n:D - <img src=\"images/smilies/biggrin.gif\" /><br />\r\n:P - <img src=\"images/smilies/tongue.gif\" /><br />\r\n:angry: - <img src=\"images/smilies/angry.gif\" /><br />\r\n:cool: - <img src=\"images/smilies/cool.gif\" /><br />\r\n:confused: - <img src=\"images/smilies/confused.gif\" /><br />'),
	(22, 4, 'What are headlines?', 'Headlines is a file that generates the latest <i>x</i> news posts\' subjects and dates which can then be included into a page to link to the news posts. For example, check out your <a href=\"headlines.php\" target=\"_blank\">headlines</a> page (only if headlines are enabled).'),
	(23, 7, 'How do I include my news?', 'There are two methods of including news: SSI and PHP includes. If the page in which you want to include your news is a standard HTML page, you will use SSI. If the page is PHP, you can use the more efficient PHP includes. If your page is HTML, rename the page with a *.shtml extension (unless your host allows SSI on *.html or *.htm pages). To include the news, place the following code wherever you want in the page:<br /><br /><tt>&lt;!--#include virtual=\"unp/news.php\"--&gt;</tt> - If you are <b>NOT</b> using the news cache<br /><br />OR<br /><br /><tt>&lt;!--#include virtual=\"unp/news.txt\"--&gt;</tt> - If you <b>ARE</b> using the news cache.<br /><br />If your page is PHP, there is no need to rename any files. Just place the following code wherever you want the news in the page:<br /><br /><tt>include(\'unp/news.php\');</tt> - If you are <b>NOT</b> using the news cache<br /><br />OR<br /><br /><tt>include(\'unp/news.txt\');</tt> - If you <b>ARE</b> using the news cache.<br /><br />You may need to surround the PHP statement with <tt>&lt;?php</tt> and <tt>?&gt;</tt> if PHP is not being parsed in the region you want the news inclusion. Please note that all 4 examples assume that the page the news is to be included in is one directory higher than UNP in directory structure. (ie. news page is <tt>/</tt>, unp is <tt>/unp</tt>). Remember to replace <tt>unp/</tt> with UNP\'s actual directory.'),
	(24, 7, 'How do I include my headlines?', 'There are two methods of including headlines: SSI and PHP includes. If the page in which you want to include your headlines in is a standard HTML page, you will use SSI. If the page is PHP, you can use the more efficient PHP includes. If your page is HTML, rename the page with a *.shtml extension (unless your host allows SSI on *.html or *.htm pages). To include the headlines, place the following code wherever you want in the page:<br /><br /><tt>&lt;!--#include virtual=\"unp/headlines.php\"--&gt;</tt><br /><br />If your page is PHP, there is no need to rename any files. Just place the following code wherever you want the news in the page:<br /><br /><tt>include(\'unp/headlines.php\');</tt><br /><br />You may need to surround the PHP statement with <tt>&lt;?php</tt> and <tt>?&gt;</tt> if PHP is not being parsed in the region you want the headlines inclusion. Please note that all 4 examples assume that the page the headlines are to be included in is one directory higher than UNP in directory structure. (ie. headlines page is <tt>/</tt>, unp is <tt>/unp</tt>). Remember to replace <tt>unp/</tt> with UNP\'s actual directory.'),
	(25, 7, 'How do I change the way the news looks?', 'The newsbit, as well as all other templates, can be altered in the template editor. The template for the way news looks is called <tt>news_newsbit</tt>.'),
	(26, 8, 'What are comments?', 'Comments are anonymously posted statements regarding a piece of news. Site visitors can post comments that other visitors can read.'),
	(27, 8, 'How do I disable comments?', 'If you do not want to use comments, you can disable them in the settings. Disabling comments does <i>not</i> remove them from the database. You can still enable them again and retain the original comments.'),
	(28, 8, 'Can I remove comments en masse?', 'You can remove all of the comments to a single news post on the Edit News page. Click on [Remove All Comments], and it will immediately remove all comments; there is no confirmation.'),
	(29, 8, 'What is allowed in comments?', 'In comments, only smilies are enabled (if smilies are turned on in the settings). UNPCode and HTML cannot and should not be enabled in comments for security reasons.'),
	(30, 8, 'Why can\'t I post comments under my UNP username?', 'You cannot post comments under any registered UNP username <i>unless</i> you are logged in at the time. Login before trying to post under a registered username.'),
	(31, 8, 'What can I do if someone is spamming the comments?', 'If someone is spamming your comments, log in to UNP first, and then view the comments made by this person. The IP address (and proxy, if applicable) of the poster is always logged. With the IP, you can find out the Internet Service Provider (ISP) of the poster and report them for defacing/vandalizing parts of your site.'),
	(32, 9, 'What are templates?', 'Templates are the editable chunks of HTML that are output on certain public pages of UNP such as comments pages and the news output. The template editor allows you to edit these and tailor them to your needs.'),
	(33, 9, 'What are \"*bits\"?', '*Bit templates are templates that are used repeatedly on a specific page. (ie. the newsbit is the template used repeatedly for each instance of a news post on the news display page, the headlinesbit template is the template used repeatedly for each instance of a headline.)'),
	(34, 9, 'How do I use variables in templates?', 'When using a variable in a template (ie. \$var), enclose the variable within curly braces (ie. {\$var}).'),
	(35, 9, 'How do I add my own templates?', 'Converting your UNP installation to a developer build will enable you to add your own custom templates. To convert, locate your global.inc.php file and change<br /><br />define(\"DEV_BUILD\", false);<br /><br />to<br /><br />define(\"DEV_BUILD\", true);'),
	(36, 9, 'How do I use custom templates?', 'Custom templates can be used and accessed from within the code itself. If you are modifying UNP functionality or adding functionality, then you can create custom templates for anything that you\'ll need. To use them, you must first activate the developer features.'),
	(37, 9, 'How do I know what each template is used for?', 'Templates are named and grouped by the page that they appear in. They have names that should fairly accurately describe the purpose of the template. If you cannot figure out what the template is used for just by looking at it, it would be wisest to not alter the template.'),
	(38, 8, 'How do I moderate comments?', 'To moderate comments, you must be logged into the news administration panel. Go to \"Edit News\" and select [View Comments] for the news post from which you want to remove comments. A new link appears in the commentsbit: [Remove Comment]. Use this to remove the comment.'),
	(39, 10, 'What are avatars?', 'Avatars are small images unique to a certain user that are attached to every one of that user\'s news posts (if they are set).'),
	(40, 10, 'How do I set an avatar?', 'To set an avatar, click on your profile link in either the header or on the main News Pro page. Once at your profile, click [Update Avatar]. In the browse box, browse your computer for the avatar that you want. Valid file types are GIF, JPG, and PNG. When you have selected your avatar, click Update Avatar.')
	");
	
	
	echo 'Populating table <strong>unp_setting</strong>...<br /><br />';
	$DB->query("INSERT INTO `unp_setting` (`id`, `display`, `title`, `varname`, `value`, `description`, `optioncode`) VALUES
	(1, 1, 'Site Title', 'sitetitle', 'Utopia News Pro', 'Title of the main site where the news is to be posted.', 'text'),
	(2, 2, 'Site URL', 'siteurl', 'http://www.', 'The URL to the main site where the news will be displayed. (Requires preceding http://)', 'text'),
	(4, 6, 'UNP Code Allowances', 'unpallowance', '1', 'Do you wish for UNP code to be enabled in news posts? If you set this to yes, then special UNP codes can be used in news posts to achieve certain HTML affects.', 'yesno'),
	(5, 8, 'News Limit', 'newslimit', '30', 'The maximum number of news items you wish to display on the news page. Do not set this too high for performance reasons.', 'text'),
	(6, 10, 'Automatically Rebuild Cache', 'autocache', '0', 'Setting this feature to yes automatically rebuilds the news cache after submitting news, editing news, editing styles, and editing settings. Leaving this feature set to no will force you to manually rebuild the cache.', 'yesno'),
	(7, 16, 'Date Format', 'dateformat', 'F j, Y', 'This is the format that will be used to display dates throughout Utopia News Pro.<br /><br />Reference: <a href=\"http://www.php.net/manual/en/function.date.php\" target=\"_blank\">http://www.php.net/manual/en/function.date.php</a><br /><br />Example:<br />US Format: (eg. July 3, 1988) - F j, Y<br />European Format: (eg. 3 July 1988) - j F Y', 'text'),
	(8, 17, 'Time Format', 'timeformat', 'h:i A', 'This is the format that will be used to display times throughout Utopia News Pro.<br /><br />Reference: <a href=\"http://www.php.net/manual/en/function.date.php\" target=\"_blank\">http://www.php.net/manual/en/function.date.php</a><br /><br />Example:<br />AM/PM Format: (eg. 10:31 PM) - h:i A<br />AM/PM Format With Seconds: (eg. 10:31:22) - h:i:s A<br />24-Hour Format: (eg. 22:31) - H:i<br />24-Hour Format With Seconds: (eg. 22:31:22) - H:i:s', 'text'),
	(9, 4, 'URL to Utopia News Pro Install', 'unpurl', 'http://www.', 'The URL to the location where Utopia News Pro is installed. <b>With</b> final slash (/). (Requires preceding http://)', 'text'),
	(10, 5, 'HTML Allowances', 'htmlallowance', '0', 'Do you wish for HTML to be enabled in news posts? We <b>strongly</b> advise against the use of HTML. Instead, to achive many common HTML affects, you can use UNP Code tags.', 'yesno'),
	(11, 7, 'Smilies Allowance', 'smiliesallowance', '1', 'Do you wish for smilies to be enabled in news posts? If you set this to yes, smilie codes will automatically be converted into smilies.', 'yesno'),
	(12, 12, 'Comments', 'commentsallowance', '1', 'Do you wish for comments to be enabled? Enabling this will allow visitors to your site submit publicly viewable comments to news posts.', 'yesno'),
	(13, 9, 'Headlines Limit', 'headlineslimit', '3', 'If you have headlines turned on, this is the maximum number of headlines to display.', 'text'),
	(14, 11, 'Enable Headlines', 'headlinesallowance', '1', 'Do you want to enable headlines? Setting this to No disables the view of headlines.', 'yesno'),
	(15, 15, 'Time Zone', 'timeoffset', '0', 'What is the timezone in which you want news posts to be dated?', 'timezone'),
	(16, 13, 'Avatar Allowances', 'avatarallowance', '1', 'Do you wish for users to be allowed to have avatars (small images displayed on every post a user makes)?', 'yesno'),
	(17, 14, 'Avatar Dimensions', 'avatardimensions', '75', 'If you have avatars turned on, this is the maximum height and width, in pixels, of a user\'s avatar.', 'text'),
	(18, 3, 'UNP Directory', 'unpdir', '/news/', 'The directory in which UNP is installed. <b>Format:</b> /directoryname/', 'text')
	");
	
	echo 'Populating table <strong>unp_style</strong>...<br /><br />';
	$DB->query("INSERT INTO `unp_style` (`id`, `title`, `varname`, `value`) VALUES
	(1, 'Page Background Color', 'bgcolor', '#ffffff'),
	(2, 'Subject Background', 'subjectbg', '#6384B0'),
	(3, 'Poster/Date Background', 'postdatebg', '#6384B0'),
	(4, 'Subject Text Color', 'subjecttext', '#ffffff'),
	(5, 'Poster/Date Text Color', 'postdatetext', '#ffffff'),
	(6, 'News Background Color', 'newsbg', '#ffffff'),
	(7, 'News Text Color', 'newstextcolor', '#000000'),
	(8, 'Border Color', 'bordercolor', '#000000'),
	(9, 'News Link Color', 'linkcolor', '#000000'),
	(10, 'Table Head Link Color', 'tableheadlink', '#ffffff')
	");

	echo 'Populating table <strong>unp_template</strong>...<br /><br />';
	$DB->query("INSERT INTO `unp_template` (`id`, `setid`, `templatename`, `template`) VALUES
	(1, 3, 'news_newsbit', '<!-- News.Bit - NewsID {\$newsid} -->\r\n<a name=\"unpnews{\$newsid}\" />\r\n<table width=\"90%\" align=\"center\"><tr><td>\r\n<table width=\"100%\" cellpadding=\"1\" cellspacing=\"1\" border=\"0\" style=\"background-color: {\$bordercolor}; font-family: verdana, arial, helvetica, sans-serif; font-size: 12px;\">\r\n\r\n<tr style=\"background-color: {\$subjectbg}; color: {\$subjecttext}; font-weight: bold; padding: 4px;\">\r\n	<td width=\"100%\" style=\"padding: 4px;\">{\$subject}</td>\r\n</tr>\r\n\r\n<tr style=\"background-color: {\$newsbg}; color: {\$newstextcolor}; padding: 4px;\">\r\n	<td width=\"100%\" style=\"padding: 4px;\">{\$useravatar}{\$newstext}</td>\r\n</tr>\r\n\r\n<tr style=\"background-color: {\$postdatebg}; color: {\$postdatetext}; font-size: 10px; clear: both;\">\r\n	<td width=\"100%\" style=\"padding: 4px;\"><div style=\"float: left\">{\$commentsinfo}</div><div style=\"font-weight: bold; float: right;\">Posted by <a href=\"{\$unpurl}news.php?action=mail&amp;uname={\$poster}\"><span style=\"color :{\$tableheadlink}\">{\$poster}</span></a> on {\$postdate} at {\$posttime}</div></td>\r\n</tr>\r\n</table>\r\n</td></tr></table>\r\n<!-- News.Bit - NewsID {\$newsid} -->\r\n'),
	(2, 3, 'news_newsbit_commentslink', '<a onClick=\'open(\"{\$unpurl}/comments.php?action=list&amp;newsid={\$newsid}\",\"View\",\"width=550, height=580, top=20,left=20,scrollbars=yes, status=no, toolbar=no, menubar=no\")\' href=\"javascript:void(0)\"><font face=\"verdana, arial, helvetica\" color=\"{\$postdatetext}\" size=\"1\"><strong>Comments:</strong> {\$comments}</font></a>&nbsp;<a onClick=\'open(\"{\$unpurl}/comments.php?action=post&amp;newsid={\$newsid}\",\"Post\",\"width=550, height=580, top=20,left=20,scrollbars=yes, status=no, toolbar=no, menubar=no\")\' href=\"javascript:void(0)\"><font face=\"verdana, arial, helvetica\" color=\"{\$postdatetext}\" size=\"1\">(Add Comment)</font></a>'),
	(3, 2, 'headlines_displaybit', '<a href=\"{\$siteurl}#unpnews{\$newsid}\"><font size=\"1\" color=\"{\$newstextcolor}\" face=\"verdana,arial,helvetica\">{\$subject}</a> - {\$date}</font><br />'),
	(4, 4, 'printable_header', '<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\"\r\n	\"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">\r\n<html xmlns=\"http://www.w3.org/1999/xhtml\">\r\n<head>\r\n<title>{\$sitetitle} - Powered by Utopia News Pro</title>\r\n<meta http-equiv=\"Content-Type\" content=\"text/html; charset=iso-8859-1\" />\r\n<style type=\"text/css\">\r\nbody {\r\n	background-color: #ffffff;\r\n	color: #000000;\r\n	margin: 10px;\r\n	font-family: verdana,arial,helvetica,sans-serif;\r\n	font-size: 12px;\r\n}\r\na:link, a:active, a:visited {\r\n	color: #000000;\r\n}\r\nh1 {\r\n	font-variant: small-caps;\r\n	font-size: 18px;\r\n	font-weight: bold;\r\n	margin: 0;\r\n}\r\n.sf {\r\n	font-size: 10px;\r\n	text-align: center;\r\n}\r\n</style>\r\n</head>\r\n<body>\r\n<h1>{\$sitetitle} News</h1>\r\nPowered by Utopia News Pro<br />\r\n{\$siteurl}\r\n{\$showall_link}\r\n<hr />\r\n<strong>News:</strong>\r\n<br />'),
	(17, 3, 'news_avatarbit', '<img src=\"{\$avatar}\" alt=\"{\$poster}\'s Avatar\" align=\"left\" />'),
	(5, 4, 'printable_footer', '<div class=\"sf\">News generated by <a href=\"http://www.utopiasoftware.net\">Utopia News Pro</a></div>\r\n</body>\r\n</html>'),
	(6, 3, 'news_footer', '<center><a href=\"{\$unpurl}news.php?action=printable\"><font size=\"1\" color=\"{\$newstextcolor}\" face=\"verdana,arial,helvetica\">Show Printable Version</font></a></center><br />\r\n\r\n<center><font size=\"1\" color=\"{\$newstextcolor}\" face=\"verdana,arial,helvetica\">News generated by </font><a href=\"http://www.utopiasoftware.net\"><font face=\"verdana,arial,helvetica\" size=\"1\" color=\"{\$linkcolor}\">Utopia News Pro</font></a></center>'),
	(7, 4, 'printable_newsbit', '<!-- PrintNews.Bit -->\r\n<strong>{\$subject}</strong><br />\r\n<em>Posted by {\$poster} on {\$postdate} at {\$posttime}</em>\r\n<table border=\"0\" width=\"100%\" cellpadding=\"0\" cellspacing=\"0\">\r\n	<tr>\r\n		<td>{\$newstext}</td>\r\n	</tr>\r\n</table>\r\n<hr />\r\n<!-- PrintNews.Bit -->\r\n\r\n'),
	(8, 1, 'comments_submit', '<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">\r\n<html xmlns=\"http://www.w3.org/1999/xhtml\">\r\n<head>\r\n<title>Post Comments - {\$subject}</title>\r\n<meta http-equiv=\"Content-Type\" content=\"text/html; charset=iso-8859-1\" />\r\n<style type=\"text/css\">\r\nbody {\r\n	background-color: {\$bgcolor};\r\n	color: {\$newstextcolor};\r\n	margin: 10px;\r\n	font-family: verdana, arial, helvetica, sans-serif;\r\n	font-size: 12px;\r\n}\r\nform {\r\n	display: inline;\r\n}\r\n.nf {\r\n	color: {\$newstextcolor};\r\n}\r\n.subject {\r\n	color: {\$subjecttext};\r\n}\r\n.postdate {\r\n	color: {\$postdatetext};\r\n}\r\n.sf {\r\n	font-size: 10px;\r\n}\r\n</style>\r\n</head>\r\n<body>\r\n<table width=\"90%\" align=\"center\">\r\n	<tr><td>\r\n	<table border=\"0\" width=\"100%\" style=\"border: {\$bordercolor} 1px solid\" cellpadding=\"5\" cellspacing=\"0\">\r\n	<tr><td bgcolor=\"{\$subjectbg}\" style=\"border-bottom: {\$bordercolor} 1px solid\" colspan=\"2\"><span class=\"subject\"><strong>Submit Comments</strong> - {\$subject}</span></td></tr>\r\n	<tr><td bgcolor=\"{\$newsbg}\" colspan=\"2\" style=\"border-bottom: {\$bordercolor} 1px solid\">\r\n<!-- Submission Form -->\r\n<form action=\"comments.php\" method=\"post\">\r\n<span class=\"nf\">\r\n<strong>Name:</strong><br />\r\n<input type=\"text\" name=\"name\" value=\"{\$username}\" size=\"30\" /><br />\r\n<strong>Password: <span style=\"sf\">(if applicable)</span></strong><br />\r\n<input type=\"password\" name=\"password\" value=\"\" size=\"30\" /><br />\r\n<strong>Email Address:</strong><br />\r\n<input type=\"text\" name=\"email\" value=\"\" size=\"30\" /><br />\r\n<strong>Subject:</strong><br />\r\n<input type=\"text\" name=\"title\" value=\"Re: {\$subject}\" size=\"30\" /><br />\r\n<strong>Comments:</strong><br />\r\n<textarea rows=\"15\" cols=\"55\" name=\"comments\"></textarea><br />\r\n<input type=\"hidden\" value=\"{\$newsid}\" name=\"newsid\" /><input type=\"hidden\" value=\"{\$ipaddress}\" name=\"ipaddress\" /><input type=\"hidden\" value=\"{\$proxy}\" name=\"proxy\" />\r\n<input type=\"submit\" name=\"submitcomment\" value=\"Post Comment\" />\r\n</span>\r\n</form>\r\n<!-- / Submission Form -->\r\n</td></tr>\r\n	<tr><td bgcolor=\"{\$postdatebg}\" align=\"right\" width=\"100%\">\r\n	<table border=\"0\" width=\"100%\" cellpadding=\"0\" cellspacing=\"0\">\r\n	<tr><td align=\"left\"><span class=\"postdate sf\"><strong>Date:</strong> {\$date} at {\$time}</span></td>\r\n	<td align=\"right\"><span class=\"postdate sf\"><strong>IP Address:</strong> {\$ipaddress} <strong>Proxy:</strong> {\$proxy}</span></td>\r\n	</tr>\r\n	</table>\r\n	</td>\r\n	</tr>\r\n</table>\r\n<br />\r\n</td></tr></table>\r\n</body>\r\n</html>'),
	(9, 1, 'comments_list_header', '<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">\r\n<html xmlns=\"http://www.w3.org/1999/xhtml\">\r\n<head>\r\n<title>Post Comments - {\$subject}</title>\r\n<meta http-equiv=\"Content-Type\" content=\"text/html; charset=iso-8859-1\" />\r\n<style type=\"text/css\">\r\nbody {\r\n	background-color: {\$bgcolor};\r\n	color: {\$newstextcolor};\r\n	margin: 10px;\r\n	font-family: verdana, arial, helvetica, sans-serif;\r\n	font-size: 12px;\r\n}\r\na:link, a:active, a:visited {\r\n	color: {\$linkcolor};\r\n	font-size: 12px;\r\n}\r\na:hover {\r\n	color: {\$linkcolor};\r\n	font-size: 12px;\r\n}\r\n.nf {\r\n	color: {\$newstextcolor};\r\n	font-size: 12px;\r\n}\r\n.subject {\r\n	color: {\$subjecttext};\r\n	font-size: 12px;\r\n}\r\n.big {\r\n	font-size: 16px;\r\n}\r\n</style>\r\n</head>\r\n<body>\r\n<table width=\"90%\" align=\"center\">\r\n	<tr><td>\r\n	<table border=\"0\" width=\"100%\" style=\"border: {\$bordercolor} 1px solid\" cellpadding=\"5\" cellspacing=\"0\">\r\n	<tr><td bgcolor=\"{\$subjectbg}\" style=\"border-bottom: {\$bordercolor} 1px solid\" colspan=\"2\"><span class=\"subject\"><strong>Comments</strong> - {\$subject}</span></td></tr>\r\n	<tr><td bgcolor=\"{\$newsbg}\" colspan=\"2\" style=\"border-bottom: {\$bordercolor} 1px solid\"><span class=\"nf\"><strong>News:</strong></span><br />\r\n<table border=\"0\" width=\"100%\" cellpadding=\"0\" cellspacing=\"0\"><tr><td>\r\n<span class=\"nf\">\r\n{\$news}\r\n</span></td></tr></table>\r\n<hr />\r\n<!-- Comments -->\r\n<strong>Comments:</strong><br /><br />'),
	(10, 1, 'comments_list_commentbit', '<!-- Comments.Bit -->\r\n<table border=\"0\" width=\"100%\" cellpadding=\"4\" cellspacing=\"1\" align=\"center\" bgcolor=\"#000000\">\r\n	<tr>\r\n		<td bgcolor=\"{\$subjectbg}\" colspan=\"2\"><span class=\"subject\"><strong>{\$c_title}</strong></span></td>\r\n	</tr>\r\n	<tr>\r\n		<td bgcolor=\"{\$newsbg}\" width=\"30%\">\r\n		<center><span class=\"nf\"><strong><a href=\"mailto:{\$c_email}\">{\$c_name}</a></strong></span></center>\r\n		<span class=\"nf\"><strong>Date:</strong> {\$c_date} at {\$c_time}<br />\r\n		{\$ipaddressinfo}\r\n		{\$removecommentlink}</span>\r\n		</td>\r\n\r\n		<td bgcolor=\"{\$newsbg}\" width=\"70%\" valign=\"top\"><span class=\"nf\">{\$c_text}</span></td>\r\n	</tr>\r\n</table><br />\r\n<!-- Comments.Bit -->\r\n'),
	(13, 1, 'comments_redirect_posted', '<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">\r\n<html xmlns=\"http://www.w3.org/1999/xhtml\">\r\n<head>\r\n<link rel=\"stylesheet\" href=\"style.css\" />\r\n<title>Comments Added</title>\r\n<meta http-equiv=\"Content-Type\" content=\"text/html; charset=iso-8859-1\" />\r\n<meta http-equiv=\"refresh\" content=\"1; url=comments.php?action=list&newsid={\$newsid}\" />\r\n<style type=\"text/css\">\r\nbody {\r\n	background-color: {\$bgcolor};\r\n	color: {\$newstextcolor};\r\n	margin: 10px;\r\n}\r\n</style>\r\n</head>\r\n<body>\r\n<center>\r\n<div align=\"center\" style=\"color: black; background-color: #FFFFFF; border: 1px solid black; padding:5px; width: 750px\">\r\n<span class=\"smallfont\"><strong>Comments Added - Taking You Back To Comments</strong><br />\r\n<a href=\"comments.php?action=list&amp;newsid={\$newsid}\">Click here if you do not wish to wait<br />(Or if your browser doesn\'t forward you)</a></span>\r\n</div></center>\r\n</body>\r\n</html>'),
	(11, 1, 'comments_list_commentbit_removecomment', '<br /><a href=\"comments.php?action=delete&amp;cid={\$c_id}\">[Remove Comment]</a>'),
	(12, 1, 'comments_list_commentbit_ipaddress', '<strong>IP Address:</strong> {\$c_ipaddress}<br /><strong>Proxy:</strong> {\$c_proxy}<br />'),
	(14, 1, 'comments_redirect_deleted', '<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">\r\n<html xmlns=\"http://www.w3.org/1999/xhtml\">\r\n<head>\r\n<link rel=\"stylesheet\" href=\"style.css\" />\r\n<title>Comment(s) Removed</title>\r\n<meta http-equiv=\"Content-Type\" content=\"text/html; charset=iso-8859-1\" />\r\n<meta http-equiv=\"refresh\" content=\"1; url=comments.php?action=list&newsid={\$newsid}\" />\r\n<style type=\"text/css\">\r\nbody {\r\n	background-color: {\$bgcolor};\r\n	color: {\$newstextcolor};\r\n	margin: 10px;\r\n}\r\n</style>\r\n</head>\r\n<body>\r\n<center>\r\n<div align=\"center\" style=\"color: black; background-color: #FFFFFF; border: 1px solid black; padding:5px; width: 750px\">\r\n<span class=\"smallfont\"><strong>Comment(s) Removed - Taking You Back To Comments</strong><br />\r\n		<a href=\"comments.php?action=list&amp;newsid={\$newsid}\">Click here if you do not wish to wait<br />(Or if your browser doesn\'t forward you)</a></span>\r\n</div></center>\r\n</body>\r\n</html>'),
	(15, 1, 'comments_list_footer', '						<!-- / Comments -->\r\n						</td>\r\n					</tr>\r\n					<tr>\r\n						<td bgcolor=\"{\$postdatebg}\" align=\"right\" width=\"100%\">&nbsp;</td>\r\n					</tr>\r\n				</table>\r\n			<br />\r\n		</td></tr></table>\r\n</body>\r\n</html>'),
	(16, 4, 'printable_showall_link', '<br /><a href=\"{\$unpurl}news.php?action=printable&amp;showall\">Show All News</a>'),
	(18, 3, 'news_header', '<!-- news_header -->')
	");
	
	echo 'Populating table <strong>unp_user</strong>...<br /><br />';
	$DB->query("INSERT INTO `unp_user` (`userid`, `groupid`, `username`, `password`, `email`) VALUES
	(1, 1, 'root', 'd41d8cd98f00b204e9800998ecf8427e', 'user@domain.com')
	");
	
	echo 'Successfully populated tables!';
	echo '<form action="install.php" method="post"><input type="submit" name="step5" value="Continue -&gt;" /></form>';
	echo ($install_closebox);
	echo ($install_footer);
}

// +------------------------------------------------------------------+
// | Step 5 - Settings                                                |
// +------------------------------------------------------------------+
if ($step == 5)
{
	echo ($install_header);
	echo ($install_openbox);
	$URI = explode('install', $_SERVER['REQUEST_URI']);
	$currentdir = $URI[0];
	$HTTP_HOST = $_SERVER['HTTP_HOST'];
	/***************************************************************
	   Start Settings Form
	***************************************************************/
	echo 'Installer will now run through the initial configuration of UNP settings. These settings may be changed at any time later.<br />';
	echo '
		<form action="install.php" method="post">
		<table border="0" width="700" cellpadding="0" cellspacing="0">
		<tr>
			<td width="25%"><span class="normalfont"><strong>Main Site Title</strong></span></td>
			<td width="50%"><span class="smallfont">Title of the main site where the news is to be posted.</span></td>
			<td width="25%"><input type="text" size="35" name="sitetitle"></td>
		</tr>
		<tr>
			<td width="100%" colspan="3"><hr /></td>
		</tr>
		<tr>
			<td width="25%"><span class="normalfont"><strong>Main Site URL</strong></span></td>
			<td width="50%"><span class="smallfont">The URL to the main site where the news will be displayed.</span></td>
			<td width="25%"><input type="text" size="35" name="siteurl" value="http://'.$HTTP_HOST.'"></td>
		</tr>
		<tr>
			<td width="100%" colspan="3"><hr /></td>
		</tr>
		<tr>
			<td width="25%"><span class="normalfont"><strong>UNP Install Directory</strong></span></td>
			<td width="50%"><span class="smallfont">The directory in which UNP is installed. <b>Format:</b> /directoryname/</span></td>
			<td width="25%"><input type="text" size="35" name="unpdir" value="'.$currentdir.'"></td>
		</tr>
		<tr>
			<td width="100%" colspan="3"><hr /></td>
		</tr>
		<tr>
			<td width="25%"><span class="normalfont"><strong>UNP Install URL</strong></span></td>
			<td width="50%"><span class="smallfont">The URL to the location where Utopia News Pro is installed. <b>With</b> final slash (/). (Requires preceding http://)</span></td>
			<td width="25%"><input type="text" size="35" name="unpurl" value="http://'.$HTTP_HOST.''.$currentdir.'"></td>
		</tr>
		</table>
		<table border="0" cellpadding="0" cellspacing="0" align="left" valign="top">
		<tr>
			<td width="100%">
			<input type="submit" name="step6" value="Continue -&gt;" /> <input type="reset" value="Reset" />
			</td>
		</tr>
		</table>
		</form>';
	echo ($install_closebox);
	echo ($install_footer);
}

// +------------------------------------------------------------------+
// | Step 6 - Updating Settings                                       |
// +------------------------------------------------------------------+
if ($step == 6)
{
	/***************************************************************
	   Reconnect To Database
	***************************************************************/
	require('functions.inc.php');
	/***************************************************************
	   Settings Grab and Fix
	***************************************************************/
	$sitetitle = addslashes($_POST['sitetitle']);
	$siteurl = addslashes($_POST['siteurl']);
	$unpdir = addslashes($_POST['unpdir']);
	$unpurl = addslashes($_POST['unpurl']);
	/***************************************************************
	   Settings Validation
	***************************************************************/
	if (unp_isempty($sitetitle))
	{
		unp_msgBox('You have entered an invalid site title.');
		exit;
	}
	if (!eregi('^[-_./a-zA-Z0-9!&%#?+,\'=:~]+$', $siteurl))
	{
		unp_msgBox('You have entered an invalid site URL.');
		exit;
	}
	if (!eregi('^[-_./a-zA-Z0-9!&%#?+,\'=:~]+$', $unpurl))
	{
		unp_msgBox('You have entered an invalid UNP URL.');
		exit;
	}
	if (!preg_match('#^/[a-zA-Z0-9./]+/$#', $unpdir))
	{
		unp_msgBox('You have entered an invalid UNP directory');
		exit;
	}
	/***************************************************************
	   Settings Update
	***************************************************************/
	$DB->query("UPDATE `unp_setting` SET value='$sitetitle' WHERE varname='sitetitle'");
	$DB->query("UPDATE `unp_setting` SET value='$siteurl' WHERE varname='siteurl'");
	$DB->query("UPDATE `unp_setting` SET value='$unpurl' WHERE varname='unpurl'");
	$DB->query("UPDATE `unp_setting` SET value='$unpdir' WHERE varname='unpdir'");
	echo ($install_header);
	echo ($install_openbox);
	echo 'Installer is now updating default settings with your new ones.<br /><br />';
	echo 'Updating settings...<br /><br />';	
	echo 'Settings successfully updated.<br /><br />';
	echo '<form action="install.php" method="post"><input type="submit" name="step7" value="Continue -&gt;" /></form>';
	echo ($install_closebox);
	echo ($install_footer);
}

// +------------------------------------------------------------------+
// | Step 7 - Creating Administrator User                             |
// +------------------------------------------------------------------+
if ($step == 7)
{
	echo ($install_header);
	echo ($install_openbox);
	echo 'Installer will now ask you to create the default administrative user.<br /><br />';
	/***************************************************************
	   Show User Creation Form
	***************************************************************/	
	echo '
		<table border="0" width="100%" cellpadding="1" cellspacing="0">
		<form action="install.php" method="post">
		<tr>
			<td width="50%"><font class="normalfont">Username</font></td>
			<td width="50%"><input type="text" value="root" name="username" size="25" /></td>
		</tr>
		<tr>
			<td width="50%"><font class="normalfont">Password</font></td>
			<td width="50%"><input type="password" value="" name="password" size="25" /></td>
		</tr>
		<tr>
			<td width="50%"><font class="normalfont">Verify Password</font></td>
			<td width="50%"><input type="password" value="" name="password2" size="25" /></td>
		</tr>
		<tr>
			<td width="50%"><font class="normalfont">E-Mail Address</font></td>
			<td width="50%"><input type="text" value="" name="email" size="25" /></td>
		</tr>
		<tr>
			<td width="100%" colspan="2"><input type="submit" value="Continue -&gt;" name="step8" /></td>
		</tr>
		</form>
		</table>';
	echo ($install_closebox);
	echo ($install_footer);
}

// +------------------------------------------------------------------+
// | Step 8 - Create User                                             |
// +------------------------------------------------------------------+
if ($step == 8)
{
	/***************************************************************
	   Reconnect To Database
	***************************************************************/
	require('functions.inc.php');
	/***************************************************************
	   User Grab, Fix, and Validation
	***************************************************************/
	$username = $_POST['username'];
	$username = trim($username);
	$username = addslashes($username);
	$password = $_POST['password'];
	$password2 = $_POST['password2'];
	if (unp_isempty($password) || unp_isempty($password2))
	{
		unp_msgBox('You have not entered a password.');
		exit;
	}
	$password = md5($password);
	$password2 = md5($password2);
	$email = trim(addslashes($_POST['email']));
	if (preg_match('/[\'"]+/', $username))
	{
		unp_msgBox('Username must not contain quotation marks.');
		exit;
	}
	if ($password !== $password2)
	{
		unp_msgBox('You have entered two different passwords.');
		exit;
	}
	if (!unp_isvalidemail($email))
	{
		unp_msgBox($gp_invalidemail);
		exit;
	}
	setcookie('unp_user', $username, time()+60*60*24*999999); // Update cookie used to fill in username field automatically
	/***************************************************************
	   Create User
	***************************************************************/
	echo ($install_header);
	echo ($install_openbox);
	echo 'Installer is now creating the administrative user.<br /><br />';	
	echo 'Updating administrative user...<br /><br />';	
	$DB->query("UPDATE unp_user SET username='$username',password='$password',email='$email' WHERE userid='1'");
	echo 'Successfully updated.<br /><br />';
	echo '<form action="install.php" method="post"><input type="submit" name="step9" value="Continue -&gt;" /></form>';	
	echo ($install_closebox);
	echo ($install_footer);
}

// +------------------------------------------------------------------+
// | Step 9 - Completed                                               |
// +------------------------------------------------------------------+
if ($step == 9)
{
	$URI = explode('install', $_SERVER['REQUEST_URI']);
	$currentdir = $URI[0];
	echo ($install_header);
	echo ($install_openbox);
	echo 'Congratulations! You have successfully installed Utopia News Pro. UtopiaSoft would like to take this time to thank you for choosing our software.<br /><br />';
	echo "\n\n";
	echo 'Utopia News Pro comes complete with documentation and a built in FAQ. To view the FAQ, either click on the FAQ link in the header of the administration control panel,
	or click on "Internal FAQ" on the main administration page. The external documentation is located in <tt>/docs/</tt> of the UNP zip file. Please refer to <b>both</b> the FAQ and external documentation before seeking technical support.<br /><br />';
	echo '
	Instructions on news and headlines inclusion can be found in both the external documentation and the FAQ.</font><br /><br />';	
	echo '
	<span class="highlight">NOTICE:</span> <font class="normalfont">Please <b>delete</b> this file once you have finished reading.
	Leaving	this file on the server poses a <b>serious</b> security threat!<br /><br />
	You may now access the admin control panel <a href="index.php">here</a>.';
	echo ($install_closebox);
	echo ($install_footer);
}

// +------------------------------------------------------------------+
// | Database Empty Tool                                              |
// +------------------------------------------------------------------+
if ($step == 10)
{
	isset ($_GET['verify']) ? $verify = $_GET['verify'] : $verify = '';
	echo ($install_header);
	echo '<center><div align="left" style="color: black; background-color: #FFFFFF; border: 1px solid black; padding:10px; width: 730px">
		<font class="normalfont">';
	$verify = $_POST['verify'];
	if ($verify != 1)
	{
		echo '<font class="normalfont"><b>Database Empty Tool</b></font><br /><br />This tool is used to empty a database that already contains data.<br /><br />';
		echo '<font class="normalfont"><b>NOTE:</b> The data removed by this tool is NOT RECOVERABLE and is IRREVERSIBLE! UtopiaSoftware does NOT take any responsibility for any data accidentally deleted.</font><br />';
		echo '<center><form action="install.php" method="post"><input type="hidden" name="verify" value="1" /><input type="submit" name="step10" value="REMOVE ALL DATA FROM THE DATABASE" /></form><form action="install.php" method="post"><input type="submit" name="step2" value="DO NOT REMOVE ANY DATA - RETURN TO STEP 2" /></form></center>';
	}
	else
	{
		require('functions.inc.php');
		echo '<font class="normalfont">You have chosen to empty the database...</font><br /><br />';
		$gettables = $DB->query("SHOW TABLES");
		while ($table = $DB->fetch_array($gettables))
		{
			$DB->query("DROP TABLE IF EXISTS $table[0]");
			echo "<font class=\"smallfont\">Dropped table <b>$table[0]</b></font><br />";
		}
		echo '<font class="normalfont">Successfully emptied database.</font>';
		echo '<form action="install.php" method="post"><input type="submit" name="step2" value="Continue -&gt;" /></form>';
	}
	echo ($install_closebox);
	echo ($install_footer);
}
?>