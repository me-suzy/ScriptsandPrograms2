<?php

/*========================================================*\
||########################################################||
||#                                                      #||
||#     WB News v1.0.1                                   #||
||# ---------------------------------------------------- #||
||#     Copyright (c) 2004-2005                          #||
||#     Created: 26th September 2005                     #||
||#     Filename: upgrade_functions.php                  #||
||#                                                      #||
||########################################################||
/*========================================================*/

/**
	@author Paul Mcilwaine - Webmobo
	@version 1.0.1
	@package install
*/

//######################### CONFIG.PHP CHECK ########################//
function checkConfig($supportedVersions, $curVersion)
{
    
    if (file_exists("../config.php"))
    {
        
        $configContents = file("../config.php");
        
        if (preg_match("/\['version'\]='(.+?)';/s", $configContents[19], $version))
        {
            if (!in_array($version[1], $supportedVersions))
                if ($curVersion == $version[1])
                    die ("You cannot upgrade to $curVersion you already have installed it");
                else
                    die ("Version " . $version[1] . " not supported in upgrade");
        }
        else if (preg_match("/\['version'\] = '(.+?)';/s", $configContents[21], $version))
        {
            if (!in_array($version[1], $supportedVersions))
                if ($curVersion == $version[1])
                    die ("You cannot upgrade to $curVersion you already have installed it");
                else
                    die ("Version " . $version[1] . " not supported in upgrade");
        }
        else if (preg_match("/\['version'\] = '(.+?)';/s", $configContents[29], $version))
        {
            if (!in_array($version[1], $supportedVersions))
                if ($curVersion == $version[1])
                    die ("You cannot upgrade to $curVersion you already have installed it");
                else
                    die ("Version " . $version[1] . " not supported in upgrade");
        }
        else
            die ("You may have found a bug, please report this error to Webmobo. <br />Bad Config File, couldnt get version number");
        
    }
    else
        die ("Error: config.php doesnt exist try install.php instead");
        
    return;
        
}

function updateVersionCheck($version, $function)
{
    $configContents = file("../config.php");
    $installVersion = 0;
    
    if (preg_match("/\['version'\]='(.+?)';/s", $configContents[19], $installedVersion))
       $installVersion = $installedVersion[1];
    else if (preg_match("/\['version'\] = '(.+?)';/s", $configContents[21], $installedVersion))
        $installVersion = $installedVersion[1];
    else if (preg_match("/\['version'\] = '(.+?)';/s", $configContents[29], $installedVersion))
        $installVersion = $installedVersion[1];
    else
        return false;
        
    if ($installVersion == $version)
    {
        $function();
        return true;
    }
    else
        return false;
}

//############################# NAV SYS #############################//
function createNavSec($tpl, $array, $sessionPos)
{
    $contents = "";
    if ($sessionPos == -1)
    {
        $num = sizeof($array);
        for ($i = 0; $i < $num; $i++)
            $contents .= $tpl->replace($tpl->getTemplate('upgrade_nav'), array("value" => $array[$i]) );
    }
    else
    {
        $num = sizeof($array);
        for ($i = 0; $i < $num; $i++)
            if ($i < $sessionPos)
                $contents .= $tpl->replace($tpl->getTemplate('upgrade_nav_complete'), array("value" => $array[$i]) );
            else
                $contents .= $tpl->replace($tpl->getTemplate('upgrade_nav'), array("value" => $array[$i]) );
    }
    
    return $contents;
}

//########################### USERGROUPS ############################//
function updateUGroups($db, $prefix)
{
    $db->db_query("ALTER TABLE `" . $prefix . "usergroups` ADD UNIQUE (`title`)");
}

//############################# MENU SYS ############################//
function upgrade_menuSystem($db, $prefix)
{
    
    $getMenuSections = $db->db_query("SELECT sectionid, menuheader as name
                                      FROM `" . $prefix . "menusections`
                                      WHERE sectionid > 6
                                      ");
                                   
    if ($db->db_numrows($getMenuSections))
    {
        // we have additional menus to install
        $sections = array();
        while ($section = $db->db_fetcharray($getMenuSections))
            $sections[] = $section;
            
        $getMenu = $db->db_query("SELECT sectionid, title as name, link as url, onclick
                                  FROM `" . $prefix . "menu`
                                  WHERE sectionid > 6
                                  ");
        
        $menus = array();
        while ($menu = $db->db_fetcharray($getMenu))
            $menus[] = $menu;
        
    }
    
    // drop menusections
    $db->db_query("DROP TABLE `" . $prefix . "menusections`");
    // drop menu
    $db->db_query("DROP TABLE `" . $prefix . "menu`");
    
    // create table & insert new records
    
    $db->db_query("CREATE TABLE `" . $prefix . "menu` (
                   `id` int(11) NOT NULL auto_increment,
                   `sectionid` int(11) NOT NULL default '0',
                   `name` varchar(200) NOT NULL default '',
                   `url` varchar(150) NOT NULL default '',
                   `onclick` varchar(255) NOT NULL default '',
                   PRIMARY KEY  (`id`),
                   KEY `sectionid` (`sectionid`)
                   )");
    
    $db->db_query("INSERT INTO `" . $prefix . "menu` VALUES (1, 1, 'Home', 'index.php', '');");
    $db->db_query("INSERT INTO `" . $prefix . "menu` VALUES (2, 1, 'Check Updates', 'update.php', '');");
    $db->db_query("INSERT INTO `" . $prefix . "menu` VALUES (3, 1, 'Logout', 'login.php?action=logout', 'return confirm(''Are you sure you want to logout?'');');");
    $db->db_query("INSERT INTO `" . $prefix . "menu` VALUES (4, 2, 'News Configuration', 'newsconfig.php', '');");
    $db->db_query("INSERT INTO `" . $prefix . "menu` VALUES (5, 2, 'Database Options', 'database.php', '');");
    $db->db_query("INSERT INTO `" . $prefix . "menu` VALUES (6, 3, 'Emoticons', 'emoticons.php', '');");
    $db->db_query("INSERT INTO `" . $prefix . "menu` VALUES (7, 3, 'Send to Friend', 'sendmsg.php', '');");
    $db->db_query("INSERT INTO `" . $prefix . "menu` VALUES (8, 4, 'Add', 'news.php?action=add', '');");
    $db->db_query("INSERT INTO `" . $prefix . "menu` VALUES (9, 4, 'List Articles', 'news.php', '');");
    $db->db_query("INSERT INTO `" . $prefix . "menu` VALUES (10, 4, 'Comments', 'comment.php', '');");
    $db->db_query("INSERT INTO `" . $prefix . "menu` VALUES (11, 5, 'Add', 'category.php?action=add', '');");
    $db->db_query("INSERT INTO `" . $prefix . "menu` VALUES (12, 5, 'List Categories', 'category.php', '');");
    $db->db_query("INSERT INTO `" . $prefix . "menu` VALUES (13, 6, 'Add', 'user.php?action=add', '');");
    $db->db_query("INSERT INTO `" . $prefix . "menu` VALUES (14, 6, 'Search Users', 'user.php', '');");
    $db->db_query("INSERT INTO `" . $prefix . "menu` VALUES (15, 6, 'UserGroups', 'usergroup.php', '');");
    $db->db_query("INSERT INTO `" . $prefix . "menu` VALUES (16, 7, 'Add', 'themes.php?action=add', '');");
    $db->db_query("INSERT INTO `" . $prefix . "menu` VALUES (17, 7, 'List Themes', 'themes.php', '');");
    
    $db->db_query("CREATE TABLE `" . $prefix . "menu_sections` (
                  `sectionid` int(11) NOT NULL auto_increment,
                  `name` varchar(150) NOT NULL default '',
                  `name_id` varchar(20) NOT NULL default '',
                  PRIMARY KEY  (`sectionid`)
                  )");
    
    $db->db_query("INSERT INTO `" . $prefix . "menu_sections` VALUES (1, 'AdminCP', 'acp');");
    $db->db_query("INSERT INTO `" . $prefix . "menu_sections` VALUES (2, 'Configuration', 'configuration');");
    $db->db_query("INSERT INTO `" . $prefix . "menu_sections` VALUES (3, 'Miscellaneous', 'misc');");
    $db->db_query("INSERT INTO `" . $prefix . "menu_sections` VALUES (4, 'News', 'news');");
    $db->db_query("INSERT INTO `" . $prefix . "menu_sections` VALUES (5, 'Categories', 'categories');");
    $db->db_query("INSERT INTO `" . $prefix . "menu_sections` VALUES (6, 'Users', 'users');");
    $db->db_query("INSERT INTO `" . $prefix . "menu_sections` VALUES (7, 'Themes', 'themes');");
    
    // add new menu_sections
    if (isset($sections))
    {
        $numSections    = sizeof($sections);
        $numMenus       = sizeof($menus);
        for ($i = 0; $i < $numSections; $i++)
        {
            $db->db_query("INSERT INTO `" . $prefix . "menu_sections` 
                         VALUES ('null', '" . $sections[$i]['name'] . "', 
                         '" . str_replace(" ", "_", strtolower($sections[$i]['name']) . "_" . ($i + 1)) . "')");
                         
            $sectionid = $db->db_insertid();
            
            for ($j = 0; $j < $numMenus; $j++)
            {
                if ($sections[$i]['sectionid'] == $menus[$j]['sectionid'])
                {
                    $db->db_query("INSERT INTO `" . $prefix . "menu`
                                  VALUES ('null', '" . $sectionid . "', '" . $menus[$j]['name'] . "', '" . $menus[$j]['url'] . "', 
                                  '" . $menus[$j]['onclick'] . "')
                                  ");
                }
            }
            
        }
    }
}

//########################### NEWS TABLE ############################//
function updateNewsTbl($db, $prefix)
{
    
    // update postinfo first
    $db->db_query("ALTER TABLE `" . $prefix . "news` ADD `userid` INT NOT NULL AFTER `postinfo` ;");
    $db->db_query("ALTER TABLE `" . $prefix . "news` ADD INDEX ( `userid` ) ;");
    
    $query = $db->db_query("SELECT `id`, `postinfo` FROM `" . $prefix . "news`");
    while ($row = $db->db_fetcharray($query))
    {
        $userid = (int)$row['postinfo'];
        $db->db_query("UPDATE `" . $prefix . "news` SET `userid` = '" . $userid . "' WHERE `id` = '" . (int)$row['id'] . "'");
    }
    $db->db_query("ALTER TABLE `" . $prefix . "news` DROP `postinfo`");
    
    // update news bbcode
    $query = $db->db_query("SELECT `id`, `news` FROM `" . $prefix . "news`");
    while ($news = $db->db_fetcharray($query))
    {
        if (newsmsg_containsOldList($news['news']))
        {
            $db->db_query("UPDATE `" . $prefix . "news` SET 
                          `news` = '" . addslashes(newsmsg_updateStr($news['news'])) . "' 
                           WHERE `id` = '" . $news['id'] . "'
                           ");
        }
    }
}

/**
    Determines if the message uses an old version of the LIST BBcode
    @param string string
    @return boolean
*/
function newsmsg_containsOldList($string)
{
    
    $num = 0;
    $yes = false;
    
    if (preg_match("/(\[list=numbered\])\s*(.+?)\s*(\[\/list\])\s*/is", $string)) 
		$num++;
        
    if (preg_match("/(\[list=alpha\])\s*(.+?)\s*(\[\/list\])\s*/is", $string)) 
		$num++;
        
	if (preg_match("/(\[list\])\s*(.+?)\s*(\[\/list\])\s*/is", $string))
        $num++;
        
    if (preg_match("/\s*(\[li\])\s*(.+?)\s*(\[\/li\])\s*/is", $string))
        $yes = true;
        
    if ($num >= 1 && $yes === true)
        return true;
    else
        return false;
    
}

function newsmsg_updateStr($string)
{
    $string = preg_replace("/(\[list\])\s*(.+?)\s*(\[\/list\])(\s*)/is", "[list]\\2\\r\\n[/list]\\4", $string);
    $string = preg_replace("/(\[list=numbered\])\s*(.+?)\s*(\[\/list\])(\s*)/is", "[list=numbered]\\2\\r\\n[/list]\\4", $string);
    $string = preg_replace("/(\[list=alpha\])\s*(.+?)\s*(\[\/list\])(\s*)/is", "[list=numbered]\\2\\r\\n[/list]\\4", $string);
    $string = preg_replace("/\s*(\[li\])\s*(.+?)\s*(\[\/li\])\s*/is", "\r\n[*]\\2", $string);
    $string = str_replace("\\r\\n", "\r\n", $string);
    
    return $string;
}

//########################### NEWSCONFIG ############################//
function updateNewsConfig($db, $prefix)
{
    $db->db_query("UPDATE `" . $prefix . "newsconfig` SET `description` = 'Use MySQLs <a href=\"http://dev.mysql.com/doc/mysql/en/date-and-time-functions.html#id2728257\" target=\"_blank\">DATE_FORMAT</a> Function', `value` = '%d-%m-%Y %h:%i%p' WHERE `id` = '2' LIMIT 1");
}

//########################### CONFIG FILE ###########################//
function updateConfig()
{
    
    $configContents = file("../config.php");

    //get the contents
    preg_match("/='(.+?)'/is", $configContents[15], $dbhost);
    preg_match("/='(.+?)'/is", $configContents[16], $dbname);
    preg_match("/='(.+?)'/is", $configContents[17], $dbuser);
    preg_match("/='(.+?)'/is", $configContents[18], $dbpass);
    preg_match("/='(.+?)'/is", $configContents[19], $version);
    preg_match("/ = '(.+?)'/is", $configContents[20], $install);
    preg_match("/=\'(.+?)\'/is", $configContents[22], $prefix);
    
    //give empty contents if needed
    $dbhost = (isset($dbhost[1]) ? $dbhost[1] : '');
    $dbname = (isset($dbname[1]) ? $dbname[1] : '');
    $dbuser = (isset($dbuser[1]) ? $dbuser[1] : '');
    $dbpass = (isset($dbpass[1]) ? $dbpass[1] : '');
    $version = (isset($version[1]) ? $version[1] : '');
    $install = (isset($install[1]) ? $install[1] : '');
    $prefix = (isset($prefix[1]) ? $prefix[1] : '');
    
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
	
    \$config['dbhost'] = '" . $dbhost . "';       //database host
    \$config['dbname'] = '" . $dbname . "';       //database name
    \$config['dbuser'] = '" . $dbuser . "';       //database username
    \$config['dbpass'] = '" . $dbpass . "';       //database password
    \$config['version'] = '1.0.2';			//version
    \$config['installdir'] = '" . $install . "';
    \$config['salt'] = implode(\"\",array_merge(range('a','z'),range('A','Z'),range(0,9)));
    \$config['prefix'] = '" . $prefix . "';	    //table prefix
	
}

?>";

    $fp = fopen("../config.php", "w");
    fwrite($fp, $contents);
    fclose($fp);
    
}

function updateConfigVersion1()
{
    
    $configContents = file("../config.php");
    //get the contents
    preg_match("/ = '(.+?)'/is", $configContents[25], $dbhost);
    preg_match("/ = '(.+?)'/is", $configContents[26], $dbname);
    preg_match("/ = '(.+?)'/is", $configContents[27], $dbuser);
    preg_match("/ = '(.+?)'/is", $configContents[28], $dbpass);
    preg_match("/ = '(.+?)'/is", $configContents[29], $version);
    preg_match("/ = '(.+?)'/is", $configContents[30], $install);
    preg_match("/ = \'(.+?)\'/is", $configContents[32], $prefix);
    
    //give empty contents if needed
    $dbhost = (isset($dbhost[1]) ? $dbhost[1] : '');
    $dbname = (isset($dbname[1]) ? $dbname[1] : '');
    $dbuser = (isset($dbuser[1]) ? $dbuser[1] : '');
    $dbpass = (isset($dbpass[1]) ? $dbpass[1] : '');
    $version = (isset($version[1]) ? $version[1] : '');
    $install = (isset($install[1]) ? $install[1] : '');
    $prefix = (isset($prefix[1]) ? $prefix[1] : '');
    
    $contents = "<?php

/*========================================================*\
||########################################################||
||#                                                      #||
||#     WB News v1.0.1                                   #||
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
	
    \$config['dbhost'] = '" . $dbhost . "';       //database host
    \$config['dbname'] = '" . $dbname . "';       //database name
    \$config['dbuser'] = '" . $dbuser . "';       //database username
    \$config['dbpass'] = '" . $dbpass . "';       //database password
    \$config['version'] = '1.0.2';			//version
    \$config['installdir'] = '" . $install . "';
    \$config['salt'] = implode(\"\",array_merge(range('a','z'),range('A','Z'),range(0,9)));
    \$config['prefix'] = '" . $prefix . "';	    //table prefix
	
}

?>";

    $fp = fopen("../config.php", "w");
    fwrite($fp, $contents);
    fclose($fp);
}

//############################ EMOTICONS ############################//
function addEmoticonsTbl($db, $prefix)
{
    $db->db_query("CREATE TABLE `" . $prefix . "emoticons` (`id` int(11) NOT NULL auto_increment, `name` varchar(150) NOT NULL default '', `code` varchar(20) NOT NULL default '', `image` varchar(255) NOT NULL default '', PRIMARY KEY  (`id`), UNIQUE KEY `code` (`code`))");
}

function addEmoticons($db, $prefix)
{
    $getcwd = str_replace("\\", "/", getcwd());
	$host = str_replace($_SERVER['DOCUMENT_ROOT'], "http://" . $_SERVER['HTTP_HOST'], str_replace("/install", "", $getcwd));
    
    $db->db_query("INSERT INTO `" . $prefix . "emoticons` VALUES (1, 'Smile', ':)', '" . $host . "/emoticons/1.gif');");
    $db->db_query("INSERT INTO `" . $prefix . "emoticons` VALUES (2, 'Sad', ':(', '" . $host . "/emoticons/2.gif');");
    $db->db_query("INSERT INTO `" . $prefix . "emoticons` VALUES (3, 'Big Smile', ':D', '" . $host . "/emoticons/3.gif');");
    $db->db_query("INSERT INTO `" . $prefix . "emoticons` VALUES (4, 'Oh!', ':o', '" . $host . "/emoticons/4.gif');");
    $db->db_query("INSERT INTO `" . $prefix . "emoticons` VALUES (5, 'Wink', ';)', '" . $host . "/emoticons/5.gif');");
    $db->db_query("INSERT INTO `" . $prefix . "emoticons` VALUES (6, 'Tongue', ':p', '" . $host . "/emoticons/6.gif');");
    $db->db_query("INSERT INTO `" . $prefix . "emoticons` VALUES (7, 'Confused', ':confused:', '" . $host . "/emoticons/7.gif');");
    $db->db_query("INSERT INTO `" . $prefix . "emoticons` VALUES (8, 'Crying', ':''(', '" . $host . "/emoticons/8.gif');");
    $db->db_query("INSERT INTO `" . $prefix . "emoticons` VALUES (9, 'Angry', ':angry:', '" . $host . "/emoticons/9.gif');");
}

?>
