<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>VUBB Installation</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<link href="http://www.vubb.com/forum/templates/core/style.css" rel="stylesheet" type="text/css" title="stylesheet" />
</head>

<body>
  <table width="90%" border="0" cellspacing="0" cellpadding="2" align="center">
	<tr> 
	  <td class="logo_area"><img src="http://www.vubb.com/forum/templates/core/images/logo.jpg" alt="VUBB logo" /></td>
	</tr>
	<tr> 
	  <td class="head_block">
		<div align="left"><a href="http://www.vubb.com/index.php" class="tlinks"><font color="white">Home</font></a> | <a href="http://www.vubb.com/forum/" class="tlinks"><font color="white">Community 
  Forum</font></a> | <a href="http://www.vubb.com/download.php" class="tlinks"><font color="white">Download</font></a> | <a href="http://www.vubb.com/fixit.php" class="tlinks"><font color="white">Fix a Bug/Add Feature</font></a></div>	  </td>
	</tr>
	<tr>
	  <td class="contentbox1">
	  <div align="center">
		  <table width="95%" border="0" cellspacing="0" cellpadding="2">
			<tr> 
			  <td class="head_block"><div align="left"><strong>Installation - Step <?php if(isset($_POST['action'])) { echo "2 "; } else { echo "1 "; } ?>of 2</strong></div></td>
			</tr>
			<tr> 
			  <td><div align="left">
<?php
$continue = 1;
$sql = @mysql_query("SELECT * FROM config");
while($sqlr = @mysql_fetch_array($sql)) {
$continue = 0;
}

if ($continue == 0) {
die("You already have VUBB installed. Please remove this file to access your board.\n</body>\n</html>");
}

if (!isset($_POST['action']))
{
?>
If you have VUBB installed, please remove this file so that you may access the board.<br /><br />
<form name="form1" id="form1" method="post" action="install.php">
<input name="action" type="hidden" id="action" value="install" />
MySQL Username: 
<input name="user" type="text" id="user" />
<br />
MySQL Password:
<input name="pass" type="password" id="pass" />
<br />
MySQL Host:
<input name="host" type="text" id="host" value="localhost" />
<br />
MySQL Database: 
<input name="database" type="text" id="database" />
<br />
<br />
Administrator username: 
<input name="auser" type="text" id="auser" />
<br />
Administrator password:
<input name="apass" type="password" id="apass" />
<br />
Confirm password:
<input name="avpass" type="password" id="avpass" />
<br />
Administrator Email: 
<input name="email" type="text" id="email" />
<br /><br />
Remember to chmod <b>config.php</b> to <b>0777</b> except for Windows.<br />
<input type="submit" name="Submit" value="Install" />
</form>
<?php
}

else if (isset($_POST['action']) && $_POST['action'] == "install")
{
	// check fields
	if (!isset($_POST['user']) || !isset($_POST['pass']) || !isset($_POST['host']) || !isset($_POST['database']))
	{
		echo "Please fill in all fields";
	}
	
	// Check admin passwords match
	else if ($_POST['apass'] != $_POST['avpass'])
	{
		echo "Admin passwords do not match!";
	}
	
	else
	{
		// connect to the database
		mysql_connect($_POST['host'], $_POST['user'], $_POST['pass']) or die(mysql_error());
		mysql_select_db($_POST['database']) or die(mysql_error());
		
		echo "connecting done";
		
		/// Set the needed stuffs
		// config file
		$filename = "config.php";
		
		// content to put in config
		$content = "<?php\n/*\nCopyright 2005 VUBB\n*/\n// database host, username, password, database\ndatabase_connect('" . $_POST['host'] . "', '" . $_POST['user'] . "', '" . $_POST['pass'] . "', '" . $_POST['database'] . "');\n?>";
		
		// Let's make sure the file exists and is writable first.
		if (is_writable($filename)) 
		{
			if (!$handle = fopen($filename, 'w')) 
			{
				echo "Cannot open file, please chmod " . $filename . " to 0777";
				die;
			}
			
			// Write $content to our opened file.
			if (fwrite($handle, $content) === FALSE) 
			{
				echo "Cannot write to file, please chmod  " . $filename . " to 0777";
				die;
			}
			
			// Close the file
			fclose($handle);
		}
		
		else
		{
			echo "Cannot write to file, please chmod  " . $filename . " to 0777";
			die;
		}
		
		// config done
		echo "...Success, config edited...";
		
		// Date format: d=day, n=month, y=year
		$date = date("d/n/y");
		
		// set the path
		$path = getcwd() . "/";
		
		// set the url
		$url = "http://" . $_SERVER['HTTP_HOST']. dirname($_SERVER['PHP_SELF']) . "/";
		
		// Create Tables
		mysql_query("CREATE TABLE `config` (`id` int(1) NOT NULL auto_increment,`name` text NOT NULL,`value` text NOT NULL,PRIMARY KEY  (`id`)) TYPE=MyISAM AUTO_INCREMENT=8") or die(mysql_error());
		mysql_query("CREATE TABLE `forum_replies` (`id` int(11) NOT NULL auto_increment,`starter` varchar(30) NOT NULL default '',`topic_id` int(11) NOT NULL default '0',`starter_id` int(11) NOT NULL default '0',`forumroot` int(11) NOT NULL default '0',`date` varchar(11) NOT NULL default '',`time` varchar(11) NOT NULL default '',UNIQUE KEY `id` (`id`)) TYPE=MyISAM PACK_KEYS=0 AUTO_INCREMENT=1 ") or die(mysql_error());
		mysql_query("CREATE TABLE `forum_reply_text` (`reply_id` int(11) NOT NULL default '0',`topic_id` int(11) NOT NULL default '0',`body` text NOT NULL) TYPE=MyISAM PACK_KEYS=0;") or die(mysql_error());
		mysql_query("CREATE TABLE `forum_topic_text` (`topic_id` int(11) NOT NULL default '0',`body` text NOT NULL) TYPE=MyISAM PACK_KEYS=0;") or die(mysql_error());
		mysql_query("CREATE TABLE `forum_topics` (`id` int(11) NOT NULL auto_increment,`topic` varchar(40) NOT NULL default '',`starter` varchar(20) NOT NULL default '',`starter_id` int(11) NOT NULL default '0',`forumroot` int(11) NOT NULL default '0',`date` varchar(11) NOT NULL default '',`time` varchar(11) NOT NULL default '',`locked` int(1) NOT NULL default '0',`lastdate` varchar(11) NOT NULL default '',`replies` int(11) NOT NULL default '0',`sticky` int(1) NOT NULL default '0',`poll` int(1) NOT NULL default '0',UNIQUE KEY `id` (`id`)) TYPE=MyISAM PACK_KEYS=0 AUTO_INCREMENT=1 ;") or die(mysql_error());
		mysql_query("CREATE TABLE `forums` (`id` int(11) NOT NULL auto_increment,`name` longtext NOT NULL,`description` text NOT NULL,`is_cat` int(1) NOT NULL default '0',`is_link` int(1) NOT NULL default '0',`category` int(11) NOT NULL default '0',`link` text NOT NULL,`topics` int(11) NOT NULL default '0',`replies` int(11) NOT NULL default '0',`order` int(11) NOT NULL default '0',PRIMARY KEY  (`id`)) TYPE=MyISAM PACK_KEYS=0 AUTO_INCREMENT=1 ;") or die(mysql_error());
		mysql_query("CREATE TABLE `groups` (`id` int(11) NOT NULL auto_increment,`name` varchar(30) NOT NULL default '',`permanent` int(1) NOT NULL default '0',PRIMARY KEY  (`id`)) TYPE=MyISAM PACK_KEYS=0 AUTO_INCREMENT=5") or die(mysql_error());
		mysql_query("CREATE TABLE `guests_online` (`id` int(11) NOT NULL default '0',`ip` varchar(15) NOT NULL default '',`time` varchar(15) NOT NULL default '') TYPE=MyISAM") or die(mysql_error());
		mysql_query("CREATE TABLE `members` (`id` int(11) NOT NULL auto_increment,`user` varchar(32) NOT NULL default '',`email` varchar(60) NOT NULL default '',`pass` varchar(32) NOT NULL default '',`group` int(11) NOT NULL default '2',`ip` varchar(50) NOT NULL default '',`lpv` bigint(20) NOT NULL default '0',`online` char(1) NOT NULL default '',`avatar_link` text NOT NULL,`sig` text NOT NULL,`datereg` varchar(15) NOT NULL default '0',`locked` char(1) NOT NULL default 'N',`location` varchar(60) NOT NULL default '',`website` varchar(60) NOT NULL default '',`aim` varchar(30) NOT NULL default '',`msn` varchar(30) NOT NULL default '',`yahoo` varchar(30) NOT NULL default '',`icq` int(30) NOT NULL default '0',UNIQUE KEY `id` (`id`)) TYPE=MyISAM PACK_KEYS=0 AUTO_INCREMENT=1 ;") or die(mysql_error());
		mysql_query("CREATE TABLE `permissions` (`forum` int(11) NOT NULL default '0',`group` int(11) NOT NULL default '0',`cpost` int(1) NOT NULL default '0',`cview` int(1) NOT NULL default '0') TYPE=MyISAM") or die(mysql_error());
		mysql_query("CREATE TABLE `poll_choices` (`choice` varchar(30) NOT NULL default '',`id` int(11) NOT NULL auto_increment,`poll_id` int(11) NOT NULL default '0',`votes` int(11) NOT NULL default '0',PRIMARY KEY  (`id`)) TYPE=MyISAM AUTO_INCREMENT=1 ;") or die(mysql_error());
		mysql_query("CREATE TABLE `poll_voters` (`user_id` int(11) NOT NULL default '0',`poll_id` int(11) NOT NULL default '0') TYPE=MyISAM;") or die(mysql_error());
		mysql_query("CREATE TABLE `polls` (`name` varchar(30) NOT NULL default '',`id` int(11) NOT NULL auto_increment,`topic_id` int(11) NOT NULL default '0',`totalvotes` int(11) NOT NULL default '0',PRIMARY KEY  (`id`)) TYPE=MyISAM AUTO_INCREMENT=1 ;") or die(mysql_error());
		mysql_query("CREATE TABLE `smilies` (`id` int(11) NOT NULL auto_increment,`code` text NOT NULL,`image` text NOT NULL,PRIMARY KEY  (`id`)) TYPE=MyISAM AUTO_INCREMENT=13") or die(mysql_error());
		mysql_query("ALTER TABLE `forum_topics` ADD `views` INT( 11 ) NOT NULL AFTER `replies` ;") or die(mysql_error());
		mysql_query("ALTER TABLE `permissions` ADD `creply` INT( 1 ) NOT NULL AFTER `cpost` ;") or die(mysql_error());

		/// Insert queries
		// Config
		mysql_query("INSERT INTO `config` VALUES (1, 'site_name', 'VUBB')") or die(mysql_error());
		mysql_query("INSERT INTO `config` VALUES (2, 'site_url', '" . $url . "')") or die(mysql_error());
		mysql_query("INSERT INTO `config` VALUES (3, 'site_path', '" . $path . "')") or die(mysql_error());
		mysql_query("INSERT INTO `config` VALUES (4, 'template', 'core')") or die(mysql_error());
		mysql_query("INSERT INTO `config` VALUES (5, 'new_registrations', '1')") or die(mysql_error());
		mysql_query("INSERT INTO `config` VALUES (6, 'language', 'english')") or die(mysql_error());
		mysql_query("INSERT INTO `config` VALUES (7, 'website_link', 'http://vubb.com')") or die(mysql_error());
		mysql_query("INSERT INTO `config` VALUES (8, 'website_name', 'VUBB')") or die(mysql_error());

		// Groups
		mysql_query("INSERT INTO `groups` VALUES (1, 'Guests', 1)") or die(mysql_error());
		mysql_query("INSERT INTO `groups` VALUES (2, 'Members', 1)") or die(mysql_error());
		mysql_query("INSERT INTO `groups` VALUES (3, 'Moderators', 1)") or die(mysql_error());
		mysql_query("INSERT INTO `groups` VALUES (4, 'Administrators', 1)") or die(mysql_error());
		
		// Members
		mysql_query("INSERT INTO `members` VALUES (-1, 'Guest', '', '', 1, '', '', '0', '" . $url . "images/guestav.gif', '', '0', 'N', '', '', '', '', '', 0)") or die(mysql_error());
		mysql_query("INSERT INTO `members` VALUES (1, '" . addslashes($_POST['auser']) . "', '" . addslashes($_POST['email']) . "', '" . md5($_POST['pass']) . "', 4, '', '', '0', '" . $url . "images/noav.gif', '', '" . $date . "', 'N', '', '', '', '', '', 0)") or die(mysql_error());				
		
		// Smilies
		mysql_query("INSERT INTO `smilies` VALUES (1, '[<]', 'images/smilies/back.gif')") or die(mysql_error());
		mysql_query("INSERT INTO `smilies` VALUES (2, ':D', 'images/smilies/bigsmile.gif')") or die(mysql_error());
		mysql_query("INSERT INTO `smilies` VALUES (3, ':(', 'images/smilies/cry.gif')") or die(mysql_error());
		mysql_query("INSERT INTO `smilies` VALUES (4, '[>]', 'images/smilies/forward.gif')") or die(mysql_error());
		mysql_query("INSERT INTO `smilies` VALUES (5, ':(', 'images/smilies/frown.gif')") or die(mysql_error());
		mysql_query("INSERT INTO `smilies` VALUES (6, ':@', 'images/smilies/mad.gif')") or die(mysql_error());
		mysql_query("INSERT INTO `smilies` VALUES (7, '[\"]', 'images/smilies/pause.gif')") or die(mysql_error());
		mysql_query("INSERT INTO `smilies` VALUES (8, '[->]', 'images/smilies/play.gif')") or die(mysql_error());
		mysql_query("INSERT INTO `smilies` VALUES (9, ':)', 'images/smilies/smile.gif')") or die(mysql_error());
		mysql_query("INSERT INTO `smilies` VALUES (10, '[!]', 'images/smilies/stop.gif')") or die(mysql_error());
		mysql_query("INSERT INTO `smilies` VALUES (11, '0.0', 'images/smilies/suprised.gif')") or die(mysql_error());
		mysql_query("INSERT INTO `smilies` VALUES (12, ':P', 'images/smilies/tongue.gif')") or die(mysql_error());
		
		// queries done
		echo "...mysql queries done...";
		
		// tell user board is installed
		echo "VUBB has been installed! <B><FONT COLOR=\"RED\">You must delete this file (install.php) before continuing!</FONT></B>";
	}
}
?>
</div></td>
			</tr>
		  </table>
		</div>
		</td>
	</tr>
	<tr>
	  <td class="head_block"><div align="center">Copyright&copy; 2005 VUBB</div>
</td>
	</tr>
  </table>
</div>
</body>
</html>
