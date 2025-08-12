<?php

/*========================================================*\
||########################################################||
||#                                                      #||
||#     WB News v1.0.0                                   #||
||# ---------------------------------------------------- #||
||#     Copyright (c) 2004-2005                          #||
||#     Created: 27th September 2005                     #||
||#     Filename: install_functions.php                  #||
||#                                                      #||
||########################################################||
/*========================================================*/

/**
	@author Paul Mcilwaine - Webmobo
	@version 1.0
	@package install
*/

// check if upgrading
function configExistCheck()
{
    if (file_exists("../config.php"))
    {
        /* 
            if installing v1.0.0 should require you to have an empty config.php file 
            anyway so we need to check that there is a previous installation
        */
        
        $fp = fopen("../config.php", "r");
        if (filesize("../config.php") != 0)
        {
            $contents = fread($fp, filesize("../config.php"));
            fclose($fp);
            
            if (strpos($contents, "\$config['version']") !== false)
                header("location: upgrade.php");
        }
        else
            fclose($fp);
    }
    
    return false;
    
}

function createNavSec($tpl, $array, $sessionPos)
{
    $contents = "";
    if ($sessionPos == -1)
    {
        $num = sizeof($array);
        for ($i = 0; $i < $num; $i++)
            $contents .= $tpl->replace($tpl->getTemplate('install_nav'), array("value" => $array[$i]) );
    }
    else
    {
        $num = sizeof($array);
        for ($i = 0; $i < $num; $i++)
            if ($i < $sessionPos)
                $contents .= $tpl->replace($tpl->getTemplate('install_nav_complete'), array("value" => $array[$i]) );
            else
                $contents .= $tpl->replace($tpl->getTemplate('install_nav'), array("value" => $array[$i]) );
    }
    
    return $contents;
}

function db_detailsCheck($dbhost, $dbuser, $dbpass, $dbname)
{
    $con = @mysql_connect($dbhost, $dbuser, $dbpass);
    if (!$con)
        return mysql_error();
        
    $sel = @mysql_select_db($dbname, $con);
    if (!$sel)
        return mysql_error();
        
    return true;
}

function db_installTables($dbhost, $dbuser, $dbpass, $dbname, $tblPrefix)
{
    $con = @mysql_connect($dbhost, $dbuser, $dbpass);
    @mysql_select_db($dbname, $con);

    $query[$tblPrefix . "adminsections"] = "CREATE TABLE `" . $tblPrefix . "adminsections` (
      `sectionid` int(10) unsigned NOT NULL auto_increment,
      `section` varchar(250) NOT NULL default '',
      `displayorder` int(11) NOT NULL default '0',
      PRIMARY KEY  (`sectionid`),
      UNIQUE KEY `section` (`section`)
    );";
    
    $query[$tblPrefix . "category"] = "CREATE TABLE `" . $tblPrefix . "category` (
      `id` int(11) NOT NULL auto_increment,
      `name` varchar(150) NOT NULL default '',
      `avatar_name` varchar(150) NOT NULL default '',
      `avatar_url` varchar(250) NOT NULL default '',
      PRIMARY KEY  (`id`)
    );";
    
    $query[$tblPrefix . "comments"] = "CREATE TABLE `" . $tblPrefix . "comments` (
      `id` int(11) NOT NULL auto_increment,
      `newsid` int(11) NOT NULL default '0',
      `message` text NOT NULL,
      `name` varchar(150) NOT NULL default '',
      `userid` int(11) NOT NULL default '0',
      `email` varchar(200) NOT NULL default '',
      `ipaddress` varchar(15) NOT NULL default '',
      `timeposted` int(11) NOT NULL default '0',
      `is_spam` tinyint(4) NOT NULL default '-1',
      PRIMARY KEY  (`id`),
      KEY `newsid` (`newsid`)
    );";
    
    $query[$tblPrefix . "emoticons"] = "CREATE TABLE `" . $tblPrefix . "emoticons` (
      `id` int(11) NOT NULL auto_increment,
      `name` varchar(150) NOT NULL default '',
      `code` varchar(20) NOT NULL default '',
      `image` varchar(255) NOT NULL default '',
      PRIMARY KEY  (`id`),
      UNIQUE KEY `code` (`code`)
    );";
    
    $query[$tblPrefix . "menu"] = "CREATE TABLE `" . $tblPrefix . "menu` (
      `id` int(11) NOT NULL auto_increment,
      `sectionid` int(11) NOT NULL default '0',
      `name` varchar(200) NOT NULL default '',
      `url` varchar(150) NOT NULL default '',
      `onclick` varchar(255) NOT NULL default '',
      PRIMARY KEY  (`id`),
      KEY `sectionid` (`sectionid`)
    );";
    
    $query[$tblPrefix . "menu_sections"] = "CREATE TABLE `" . $tblPrefix . "menu_sections` (
      `sectionid` int(11) NOT NULL auto_increment,
      `name` varchar(150) NOT NULL default '',
      `name_id` varchar(20) NOT NULL default '',
      PRIMARY KEY  (`sectionid`)
    );";
    
    $query[$tblPrefix . "news"] = "CREATE TABLE `" . $tblPrefix . "news` (
      `id` int(11) NOT NULL auto_increment,
      `catid` int(11) NOT NULL default '0',
      `userid` int(11) NOT NULL default '0',
      `title` varchar(150) NOT NULL default '',
      `news` text NOT NULL,
      `timeposted` int(11) NOT NULL default '0',
      `allowcomments` smallint(6) NOT NULL default '0',
      PRIMARY KEY  (`id`),
      KEY `userid` (`userid`)
    );";
    
    $query[$tblPrefix . "newsconfig"] = "CREATE TABLE `" . $tblPrefix . "newsconfig` (
      `id` int(11) NOT NULL auto_increment,
      `sectionid` int(11) NOT NULL default '0',
      `title` varchar(150) NOT NULL default '',
      `description` varchar(200) NOT NULL default '',
      `value` text NOT NULL,
      `var` varchar(100) NOT NULL default '',
      `option` varchar(150) NOT NULL default '',
      `displayorder` int(11) NOT NULL default '0',
      PRIMARY KEY  (`id`),
      UNIQUE KEY `var` (`var`),
      KEY `title` (`title`,`description`,`var`)
    );";
    
    $query[$tblPrefix . "senddb"] = "CREATE TABLE `" . $tblPrefix . "senddb` (
      `id` int(11) NOT NULL auto_increment,
      `newsid` int(11) NOT NULL default '0',
      `time` int(11) NOT NULL default '0',
      `email_to` varchar(255) NOT NULL default '',
      `email_from` varchar(255) NOT NULL default '',
      `message` text NOT NULL,
      `ipaddress` varchar(15) NOT NULL default '',
      PRIMARY KEY  (`id`)
    );";
    
    $query[$tblPrefix . "themes"] = "CREATE TABLE `" . $tblPrefix . "themes` (
      `themeid` int(11) NOT NULL auto_increment,
      `title` varchar(100) NOT NULL default '',
      `themepath` varchar(200) NOT NULL default '',
      PRIMARY KEY  (`themeid`),
      UNIQUE KEY `title` (`title`,`themepath`)
    );";
    
    $query[$tblPrefix . "usergroups"] = "CREATE TABLE `" . $tblPrefix . "usergroups` (
      `usergroupid` int(11) NOT NULL auto_increment,
      `title` varchar(100) NOT NULL default '',
      `cancontrol` int(11) NOT NULL default '0',
      `canbackup` int(11) NOT NULL default '0',
      `canconfig` int(11) NOT NULL default '0',
      `editcomment` int(11) NOT NULL default '0',
      `checkupdate` int(11) NOT NULL default '0',
      `addnews` int(11) NOT NULL default '0',
      `modifynews` int(11) NOT NULL default '0',
      `deletenews` int(11) NOT NULL default '0',
      `addcategory` int(11) NOT NULL default '0',
      `modifycat` int(11) NOT NULL default '0',
      `deletecat` int(11) NOT NULL default '0',
      `adduser` int(11) NOT NULL default '0',
      `modifyuser` int(11) NOT NULL default '0',
      `deleteuser` int(11) NOT NULL default '0',
      `addtheme` int(11) NOT NULL default '0',
      `modifytheme` int(11) NOT NULL default '0',
      `deletetheme` int(11) NOT NULL default '0',
      `usergroups` int(11) NOT NULL default '0',
      PRIMARY KEY  (`usergroupid`),
      UNIQUE KEY `title` (`title`)
    );";
    
    $query[$tblPrefix . "users"] = "CREATE TABLE `" . $tblPrefix . "users` (
      `userid` int(11) NOT NULL auto_increment,
      `usergroupid` int(11) NOT NULL default '0',
      `username` varchar(100) NOT NULL default '',
      `password` varchar(100) NOT NULL default '',
      `postname` varchar(100) NOT NULL default '',
      `email` varchar(150) NOT NULL default '',
      PRIMARY KEY  (`userid`),
      KEY `usergroupid` (`usergroupid`)
    );";
    
    $return = array();
    foreach ($query as $key => $value)
    {
        if (@mysql_query($value))
            $return[] = array($key, true);
        else
            $return[] = array($key, mysql_error());
    }
    
    return $return;
}

function db_installRecords($dbhost, $dbuser, $dbpass, $dbname, $tblPrefix)
{
    
    $query[] = "INSERT INTO `" . $tblPrefix . "adminsections` VALUES (1, 'News', 1);";
    $query[] = "INSERT INTO `" . $tblPrefix . "adminsections` VALUES (2, 'RSS', 2);";
    $query[] = "INSERT INTO `" . $tblPrefix . "adminsections` VALUES (3, 'Filter', 3);";
    $query[] = "INSERT INTO `" . $tblPrefix . "adminsections` VALUES (4, 'Send to Friend', 4);";
    $query[] = "INSERT INTO `" . $tblPrefix . "adminsections` VALUES (5, 'Users', 5);";
    
    $getcwd = str_replace("\\", "/", getcwd());
	$host = str_replace($_SERVER['DOCUMENT_ROOT'], "http://" . $_SERVER['HTTP_HOST'], str_replace("/install", "", $getcwd));
    
    $query[] = "INSERT INTO `" . $tblPrefix . "emoticons` VALUES (1, 'Smile', ':)', '" . $host . "/emoticons/1.gif');";
    $query[] = "INSERT INTO `" . $tblPrefix . "emoticons` VALUES (2, 'Sad', ':(', '" . $host . "/emoticons/2.gif');";
    $query[] = "INSERT INTO `" . $tblPrefix . "emoticons` VALUES (3, 'Big Smile', ':D', '" . $host . "/emoticons/3.gif');";
    $query[] = "INSERT INTO `" . $tblPrefix . "emoticons` VALUES (4, 'Oh!', ':o', '" . $host . "/emoticons/4.gif');";
    $query[] = "INSERT INTO `" . $tblPrefix . "emoticons` VALUES (5, 'Wink', ';)', '" . $host . "/emoticons/5.gif');";
    $query[] = "INSERT INTO `" . $tblPrefix . "emoticons` VALUES (6, 'Tongue', ':p', '" . $host . "/emoticons/6.gif');";
    $query[] = "INSERT INTO `" . $tblPrefix . "emoticons` VALUES (7, 'Confused', ':confused:', '" . $host . "/emoticons/7.gif');";
    $query[] = "INSERT INTO `" . $tblPrefix . "emoticons` VALUES (8, 'Crying', ':''(', '" . $host . "/emoticons/8.gif');";
    $query[] = "INSERT INTO `" . $tblPrefix . "emoticons` VALUES (9, 'Angry', ':angry:', '" . $host . "/emoticons/9.gif');";
    
    $query[] = "INSERT INTO `" . $tblPrefix . "menu` VALUES (1, 1, 'Home', 'index.php', '');";
    $query[] = "INSERT INTO `" . $tblPrefix . "menu` VALUES (2, 1, 'Check Updates', 'update.php', '');";
    $query[] = "INSERT INTO `" . $tblPrefix . "menu` VALUES (3, 1, 'Logout', 'login.php?action=logout', 'return confirm(''Are you sure you want to logout?'');');";
    $query[] = "INSERT INTO `" . $tblPrefix . "menu` VALUES (4, 2, 'News Configuration', 'newsconfig.php', '');";
    $query[] = "INSERT INTO `" . $tblPrefix . "menu` VALUES (5, 2, 'Database Options', 'database.php', '');";
    $query[] = "INSERT INTO `" . $tblPrefix . "menu` VALUES (6, 3, 'Emoticons', 'emoticons.php', '');";
    $query[] = "INSERT INTO `" . $tblPrefix . "menu` VALUES (7, 3, 'Send to Friend', 'sendmsg.php', '');";
    $query[] = "INSERT INTO `" . $tblPrefix . "menu` VALUES (8, 4, 'Add', 'news.php?action=add', '');";
    $query[] = "INSERT INTO `" . $tblPrefix . "menu` VALUES (9, 4, 'List Articles', 'news.php', '');";
    $query[] = "INSERT INTO `" . $tblPrefix . "menu` VALUES (10, 4, 'Comments', 'comment.php', '');";
    $query[] = "INSERT INTO `" . $tblPrefix . "menu` VALUES (11, 5, 'Add', 'category.php?action=add', '');";
    $query[] = "INSERT INTO `" . $tblPrefix . "menu` VALUES (12, 5, 'List Categories', 'category.php', '');";
    $query[] = "INSERT INTO `" . $tblPrefix . "menu` VALUES (13, 6, 'Add', 'user.php?action=add', '');";
    $query[] = "INSERT INTO `" . $tblPrefix . "menu` VALUES (14, 6, 'Search Users', 'user.php', '');";
    $query[] = "INSERT INTO `" . $tblPrefix . "menu` VALUES (15, 6, 'UserGroups', 'usergroup.php', '');";
    $query[] = "INSERT INTO `" . $tblPrefix . "menu` VALUES (16, 7, 'Add', 'themes.php?action=add', '');";
    $query[] = "INSERT INTO `" . $tblPrefix . "menu` VALUES (17, 7, 'List Themes', 'themes.php', '');";
    
    $query[] = "INSERT INTO `" . $tblPrefix . "menu_sections` VALUES (1, 'AdminCP', 'acp');";
    $query[] = "INSERT INTO `" . $tblPrefix . "menu_sections` VALUES (2, 'Configuration', 'configuration');";
    $query[] = "INSERT INTO `" . $tblPrefix . "menu_sections` VALUES (3, 'Miscellaneous', 'misc');";
    $query[] = "INSERT INTO `" . $tblPrefix . "menu_sections` VALUES (4, 'News', 'news');";
    $query[] = "INSERT INTO `" . $tblPrefix . "menu_sections` VALUES (5, 'Categories', 'categories');";
    $query[] = "INSERT INTO `" . $tblPrefix . "menu_sections` VALUES (6, 'Users', 'users');";
    $query[] = "INSERT INTO `" . $tblPrefix . "menu_sections` VALUES (7, 'Themes', 'themes');";
    
    $query[] = "INSERT INTO `" . $tblPrefix . "newsconfig` VALUES (1, 1, 'News Per Page', 'The amount of news articles per page', '10', 'newslimit', '', 3);";
    $query[] = "INSERT INTO `" . $tblPrefix . "newsconfig` VALUES (2, 1, 'The Date Formation', 'Use MySQLs <a href=\"http://dev.mysql.com/doc/mysql/en/date-and-time-functions.html#id2728257\" target=\"_blank\">DATE_FORMAT</a> Function', '%d-%m-%Y %h:%i%p', 'dateFormat', '', 2);";
    $query[] = "INSERT INTO `" . $tblPrefix . "newsconfig` VALUES (3, 1, 'Max Comment Message', 'Max Comment Message Length', '1000', 'max_comment_comment', '', 4);";
    $query[] = "INSERT INTO `" . $tblPrefix . "newsconfig` VALUES (4, 1, 'Word Wrap', 'If you have long words this is vital to keep site not extending horizontal', '80', 'wordwrap', '', 5);";
    $query[] = "INSERT INTO `" . $tblPrefix . "newsconfig` VALUES (5, 3, 'Bad Words', 'Seperated By Spaces put in Words you would like filtered out', '', 'badwords', 'textarea', 2);";
    $query[] = "INSERT INTO `" . $tblPrefix . "newsconfig` VALUES (6, 3, 'Bad Words Replacement', 'Keep Replacment as small as possible.', '*', 'badwords_replacement', '', 3);";
    $query[] = "INSERT INTO `" . $tblPrefix . "newsconfig` VALUES (7, 3, 'Flood Filter', 'Amount of time in seconds before a user can post another comment', '30', 'floodfilter', '', 1);";
    $query[] = "INSERT INTO `" . $tblPrefix . "newsconfig` VALUES (8, 4, 'Send to Friend', 'Allows users to send a link to a news article.<br /> Please turn this off if your getting reports of spamming.', '', 'sendtofriend', 'yesno', 1);";
    $query[] = "INSERT INTO `" . $tblPrefix . "newsconfig` VALUES (9, 4, 'Message for Send to Friend', 'The Message that is sent, when a user sends a news article to a friend', 'You have been sent an email from {email} who would like you to read the news article located at {url}\r\n\r\nUser Message\r\n------------------------------------------------\r\n{usermsg}\r\n\r\nIf you feel this is spam please reply to {adminemail}', 'sendtomsg', 'textarea', 4);";
    $query[] = "INSERT INTO `" . $tblPrefix . "newsconfig` VALUES (10, 4, 'Admin Email', 'Your Email address', '', 'adminemail', '', 2);";
    $query[] = "INSERT INTO `" . $tblPrefix . "newsconfig` VALUES (11, 4, 'Location of News', 'The Exact location of where the news is located <br />e.g. http://domain.com/news.php', '?newsid={newsid}', 'newsdisplay', '', 3);";
    $query[] = "INSERT INTO `" . $tblPrefix . "newsconfig` VALUES (12, 1, 'Turn News System Off', 'Turns the news system off which stops user being able to view the news.', '', 'systemstatus', 'yesno', 1);";
    $query[] = "INSERT INTO `" . $tblPrefix . "newsconfig` VALUES (13, 1, 'Default Theme', 'Default theme for News and Administration', '1', 'themeid', 'select_getThemes', 8);";
    $query[] = "INSERT INTO `" . $tblPrefix . "newsconfig` VALUES (14, 5, 'New User Email Message', 'The Email a New user will recieve to finish activating there account', 'Hi {username}\r\n\r\nYou have been registered at Your Site Name to be able to post News on the site. To finish activating your account please follow this link {url}.\r\n\r\nRegards\r\nWB News Staff', 'newuser_email', 'textarea', 1);";
    $query[] = "INSERT INTO `" . $tblPrefix . "newsconfig` VALUES (15, 2, 'Web Site Name', 'The Name of your Website, used in RSS Feed', '', 'sitename', '', 2);";
    $query[] = "INSERT INTO `" . $tblPrefix . "newsconfig` VALUES (16, 2, 'Web Address', 'Your website Address, Used in RSS Feed', '', 'siteaddress', '', 3);";
    $query[] = "INSERT INTO `" . $tblPrefix . "newsconfig` VALUES (17, 2, 'RSS Feed On', 'Have the RSS Feed on', '1', 'rss_on', 'yesno', 1);";
    $query[] = "INSERT INTO `" . $tblPrefix . "newsconfig` VALUES (18, 1, 'IP Banning', 'Seperate by a space the IP addresses you wish to ban from using the Comments and Send to Friends', '', 'ipban', 'textarea', 7);";
    $query[] = "INSERT INTO `" . $tblPrefix . "newsconfig` VALUES (19, 1, 'Timezone', 'The Timezone you wish to use throughout your News', '+0', 'timezone', 'select_timezone', 6);";
    $query[] = "INSERT INTO `" . $tblPrefix . "newsconfig` VALUES (20, 5, 'New Password Email', 'When issued a new Password', 'Hi {username}\r\n\r\nYour new Password is:\r\n\r\n{newpass}\r\n\r\nPlease do not lose this password.\r\n\r\nRegards\r\n', 'newpassword_email', 'textarea', 2);";
    $query[] = "INSERT INTO `" . $tblPrefix . "newsconfig` VALUES (21, 5, 'Default UserGroup', 'The Default usergroup when users sign up', '3', 'default_usergroupid', 'select_usergroups', 3);";
    $query[] = "INSERT INTO `" . $tblPrefix . "newsconfig` VALUES (22, 3, 'Spam Filter', 'Add Words which you consider spam leave a space between words', '', 'spamfilter', 'textarea', 4);";
    
    $query[] = "INSERT INTO `" . $tblPrefix . "themes` VALUES (1, 'Default Theme', 'default');";
    
    $query[] = "INSERT INTO `" . $tblPrefix . "usergroups` VALUES (1, 'Super Administrator', 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1);";
    $query[] = "INSERT INTO `" . $tblPrefix . "usergroups` VALUES (2, 'Administrator', 1, 0, 0, 0, 0, 1, 0, 0, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0);";
    $query[] = "INSERT INTO `" . $tblPrefix . "usergroups` VALUES (3, 'User', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);";
    
    $con = @mysql_connect($dbhost, $dbuser, $dbpass);
    @mysql_select_db($dbname, $con);
    
    $num = sizeof($query);
    for ($i = 0; $i < $num; $i++)
        mysql_query($query[$i], $con);
    
}

function configCreator($dbhost, $dbuser, $dbpass, $dbname, $prefix, $install)
{
    
    $contents = "<?php

/*========================================================*\
||########################################################||
||#                                                      #||
||#     WB News v1.0.0                                   #||
||# ---------------------------------------------------- #||
||#     Copyright (c) 2004-2005                          #||
||#     Created: " . date("dS F Y") . str_repeat(" ",  60 - 3 - strlen("||#     Created: " . date("dS F Y"))) . "#||
||#     Filename: config.php                             #||
||#                                                      #||
||########################################################||
/*========================================================*/

/**
	@author Paul Mcilwaine - Webmobo
	@version 1.0
	@package main
*/

if (!defined('wbnews'))
	die (\"Hacking Attempt\");
else
{
	
    \$config['dbhost'] = '".$dbhost."';       //database host
    \$config['dbname'] = '".base64_encode($dbname)."';       //database name
    \$config['dbuser'] = '".base64_encode($dbuser)."';       //database username
    \$config['dbpass'] = '".base64_encode($dbpass)."';       //database password
    \$config['version'] = '1.0.2';			//version
    \$config['installdir'] = '".$install."';
    \$config['salt'] = implode(\"\",array_merge(range('a','z'),range('A','Z'),range(0,9)));
    \$config['prefix'] = '".$prefix."';	    //table prefix
	
}

?>";

    $fp = fopen("../config.php", "w");
    fwrite($fp, $contents);
    fclose($fp);
    
}

?>
