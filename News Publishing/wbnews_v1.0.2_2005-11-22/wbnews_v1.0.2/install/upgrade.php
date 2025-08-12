<?php

/*========================================================*\
||########################################################||
||#                                                      #||
||#     WB News v1.0.0                                   #||
||# ---------------------------------------------------- #||
||#     Copyright (c) 2004-2005                          #||
||#     Created: 26th September 2005                     #||
||#     Filename: upgrade.php                            #||
||#                                                      #||
||########################################################||
/*========================================================*/

/**
	@author Paul Mcilwaine - Webmobo
	@version 1.0
	@package install
*/

define('wbnews', true);
session_start();

include "./functions.php";
include "./upgrade_functions.php";

include "../config.php";
include "../includes/lib/db_mysql.php";
$dbclass = new DB($config['dbhost'], base64_decode($config['dbname']), base64_decode($config['dbuser']), base64_decode($config['dbpass']));
$dbclass->db_connect();

include "../includes/lib/template.php";
$tpl = new template("./templates", "", "");

$upgradePhases = array("File Check", "Database/Config Alterations", "Table Installation", "Insert Table Records", "Finish");

$supportedVersions = array("0.8.0", "0.9.0", "1.0.0", "1.0.1");
$curVersion = '1.0.2';

if (!isset($_SESSION['upgrade']))
{
    checkConfig($supportedVersions, $curVersion); // it either dies or we can go on
    
    $serial = 'a:212:{s:11:"./users.php";a:1:{s:11:"is_writable";i:0;}s:12:"./search.php";a:1:{s:11:"is_writable";i:0;}s:18:"./sendtofriend.php";a:1:{s:11:"is_writable";i:0;}s:14:"./news.rss.php";a:1:{s:11:"is_writable";i:0;}s:10:"./news.php";a:1:{s:11:"is_writable";i:0;}s:14:"./mainNews.php";a:1:{s:11:"is_writable";i:0;}s:12:"./global.php";a:1:{s:11:"is_writable";i:0;}s:12:"./config.php";a:1:{s:11:"is_writable";i:1;}s:14:"./comments.php";a:1:{s:11:"is_writable";i:0;}s:13:"./archive.php";a:1:{s:11:"is_writable";i:0;}s:12:"./index.html";a:1:{s:11:"is_writable";i:0;}s:21:"./templates/.htaccess";a:1:{s:11:"is_writable";i:0;}s:38:"./templates/default/admin/redirect.tpl";a:1:{s:11:"is_writable";i:0;}s:48:"./templates/default/admin/images/toggle_open.png";a:1:{s:11:"is_writable";i:0;}s:49:"./templates/default/admin/images/toggle_close.png";a:1:{s:11:"is_writable";i:0;}s:43:"./templates/default/admin/images/wbnews.png";a:1:{s:11:"is_writable";i:0;}s:42:"./templates/default/admin/images/help2.png";a:1:{s:11:"is_writable";i:0;}s:46:"./templates/default/admin/images/directory.gif";a:1:{s:11:"is_writable";i:0;}s:50:"./templates/default/admin/images/wysiwyg/color.gif";a:1:{s:11:"is_writable";i:0;}s:53:"./templates/default/admin/images/wysiwyg/fontsize.gif";a:1:{s:11:"is_writable";i:0;}s:48:"./templates/default/admin/images/wysiwyg/php.gif";a:1:{s:11:"is_writable";i:0;}s:54:"./templates/default/admin/images/wysiwyg/underline.gif";a:1:{s:11:"is_writable";i:0;}s:56:"./templates/default/admin/images/wysiwyg/right-align.gif";a:1:{s:11:"is_writable";i:0;}s:56:"./templates/default/admin/images/wysiwyg/number-list.gif";a:1:{s:11:"is_writable";i:0;}s:49:"./templates/default/admin/images/wysiwyg/link.gif";a:1:{s:11:"is_writable";i:0;}s:55:"./templates/default/admin/images/wysiwyg/left-align.gif";a:1:{s:11:"is_writable";i:0;}s:51:"./templates/default/admin/images/wysiwyg/italic.gif";a:1:{s:11:"is_writable";i:0;}s:51:"./templates/default/admin/images/wysiwyg/color2.gif";a:1:{s:11:"is_writable";i:0;}s:56:"./templates/default/admin/images/wysiwyg/font_family.gif";a:1:{s:11:"is_writable";i:0;}s:56:"./templates/default/admin/images/wysiwyg/dotted-list.gif";a:1:{s:11:"is_writable";i:0;}s:53:"./templates/default/admin/images/wysiwyg/emoticon.gif";a:1:{s:11:"is_writable";i:0;}s:49:"./templates/default/admin/images/wysiwyg/bold.gif";a:1:{s:11:"is_writable";i:0;}s:51:"./templates/default/admin/images/wysiwyg/index.html";a:1:{s:11:"is_writable";i:0;}s:57:"./templates/default/admin/images/wysiwyg/center-align.gif";a:1:{s:11:"is_writable";i:0;}s:50:"./templates/default/admin/images/wysiwyg/arrow.gif";a:1:{s:11:"is_writable";i:0;}s:55:"./templates/default/admin/images/wysiwyg/alpha-list.gif";a:1:{s:11:"is_writable";i:0;}s:43:"./templates/default/admin/images/index.html";a:1:{s:11:"is_writable";i:0;}s:35:"./templates/default/admin/login.tpl";a:1:{s:11:"is_writable";i:0;}s:40:"./templates/default/admin/css/layout.css";a:1:{s:11:"is_writable";i:0;}s:48:"./templates/default/admin/css/wysiwyg_editor.css";a:1:{s:11:"is_writable";i:0;}s:40:"./templates/default/admin/css/index.html";a:1:{s:11:"is_writable";i:0;}s:45:"./templates/default/admin/clientscript/nav.js";a:1:{s:11:"is_writable";i:0;}s:48:"./templates/default/admin/clientscript/global.js";a:1:{s:11:"is_writable";i:0;}s:48:"./templates/default/admin/clientscript/editor.js";a:1:{s:11:"is_writable";i:0;}s:49:"./templates/default/admin/clientscript/index.html";a:1:{s:11:"is_writable";i:0;}s:47:"./templates/default/admin/clientscript/basic.js";a:1:{s:11:"is_writable";i:0;}s:42:"./templates/default/admin/notlogged-in.tpl";a:1:{s:11:"is_writable";i:0;}s:40:"./templates/default/admin/index_body.tpl";a:1:{s:11:"is_writable";i:0;}s:35:"./templates/default/admin/help.html";a:1:{s:11:"is_writable";i:0;}s:41:"./templates/default/admin/update_main.tpl";a:1:{s:11:"is_writable";i:0;}s:34:"./templates/default/admin/menu.tpl";a:1:{s:11:"is_writable";i:0;}s:36:"./templates/default/admin/index.html";a:1:{s:11:"is_writable";i:0;}s:44:"./templates/default/admin/menu-container.tpl";a:1:{s:11:"is_writable";i:0;}s:40:"./templates/default/admin/theme_info.php";a:1:{s:11:"is_writable";i:0;}s:43:"./templates/default/admin/menu-sections.tpl";a:1:{s:11:"is_writable";i:0;}s:43:"./templates/default/admin/newslist_body.tpl";a:1:{s:11:"is_writable";i:0;}s:45:"./templates/default/admin/newsconfig_body.tpl";a:1:{s:11:"is_writable";i:0;}s:43:"./templates/default/admin/newslist_list.tpl";a:1:{s:11:"is_writable";i:0;}s:49:"./templates/default/admin/databaseoption_body.tpl";a:1:{s:11:"is_writable";i:0;}s:43:"./templates/default/admin/category_list.tpl";a:1:{s:11:"is_writable";i:0;}s:48:"./templates/default/admin/newsconfig_section.tpl";a:1:{s:11:"is_writable";i:0;}s:47:"./templates/default/admin/newsconfig_config.tpl";a:1:{s:11:"is_writable";i:0;}s:47:"./templates/default/admin/categorylist_body.tpl";a:1:{s:11:"is_writable";i:0;}s:42:"./templates/default/admin/comment_body.tpl";a:1:{s:11:"is_writable";i:0;}s:42:"./templates/default/admin/emoticonlist.tpl";a:1:{s:11:"is_writable";i:0;}s:49:"./templates/default/admin/databaseoption_list.tpl";a:1:{s:11:"is_writable";i:0;}s:47:"./templates/default/admin/categoryform_body.tpl";a:1:{s:11:"is_writable";i:0;}s:47:"./templates/default/admin/comments-newslist.tpl";a:1:{s:11:"is_writable";i:0;}s:37:"./templates/default/admin/dirlist.tpl";a:1:{s:11:"is_writable";i:0;}s:41:"./templates/default/admin/commentlist.tpl";a:1:{s:11:"is_writable";i:0;}s:49:"./templates/default/admin/databasebackup_body.tpl";a:1:{s:11:"is_writable";i:0;}s:46:"./templates/default/admin/useraccount_body.tpl";a:1:{s:11:"is_writable";i:0;}s:43:"./templates/default/admin/newsform_body.tpl";a:1:{s:11:"is_writable";i:0;}s:45:"./templates/default/admin/usersearch_body.tpl";a:1:{s:11:"is_writable";i:0;}s:49:"./templates/default/admin/usersearchform_body.tpl";a:1:{s:11:"is_writable";i:0;}s:49:"./templates/default/admin/usersearchlist_body.tpl";a:1:{s:11:"is_writable";i:0;}s:45:"./templates/default/admin/usersearch_list.tpl";a:1:{s:11:"is_writable";i:0;}s:44:"./templates/default/admin/themelist_body.tpl";a:1:{s:11:"is_writable";i:0;}s:39:"./templates/default/admin/themelist.tpl";a:1:{s:11:"is_writable";i:0;}s:44:"./templates/default/admin/themeform_body.tpl";a:1:{s:11:"is_writable";i:0;}s:39:"./templates/default/admin/directory.tpl";a:1:{s:11:"is_writable";i:0;}s:38:"./templates/default/admin/filelist.tpl";a:1:{s:11:"is_writable";i:0;}s:42:"./templates/default/admin/nopermission.tpl";a:1:{s:11:"is_writable";i:0;}s:46:"./templates/default/admin/sendmsg_newslist.tpl";a:1:{s:11:"is_writable";i:0;}s:42:"./templates/default/admin/sendmsg_body.tpl";a:1:{s:11:"is_writable";i:0;}s:41:"./templates/default/admin/sendmsglist.tpl";a:1:{s:11:"is_writable";i:0;}s:48:"./templates/default/admin/usergroupmain_body.tpl";a:1:{s:11:"is_writable";i:0;}s:43:"./templates/default/admin/emoticon-form.tpl";a:1:{s:11:"is_writable";i:0;}s:50:"./templates/default/admin/news_emoticon_window.tpl";a:1:{s:11:"is_writable";i:0;}s:48:"./templates/default/admin/usergroupform_body.tpl";a:1:{s:11:"is_writable";i:0;}s:43:"./templates/default/admin/usergrouplist.tpl";a:1:{s:11:"is_writable";i:0;}s:45:"./templates/default/admin/usermodify_body.tpl";a:1:{s:11:"is_writable";i:0;}s:47:"./templates/default/admin/newsemoticon_list.tpl";a:1:{s:11:"is_writable";i:0;}s:47:"./templates/default/admin/update_newversion.tpl";a:1:{s:11:"is_writable";i:0;}s:46:"./templates/default/admin/update_versionok.tpl";a:1:{s:11:"is_writable";i:0;}s:42:"./templates/default/admin/useradd_body.tpl";a:1:{s:11:"is_writable";i:0;}s:47:"./templates/default/admin/emoticonlist_body.tpl";a:1:{s:11:"is_writable";i:0;}s:30:"./templates/default/index.html";a:1:{s:11:"is_writable";i:0;}s:34:"./templates/default/theme_info.php";a:1:{s:11:"is_writable";i:0;}s:39:"./templates/default/news_system_off.tpl";a:1:{s:11:"is_writable";i:0;}s:35:"./templates/default/displaynews.tpl";a:1:{s:11:"is_writable";i:0;}s:34:"./templates/default/singlenews.tpl";a:1:{s:11:"is_writable";i:0;}s:30:"./templates/default/nonews.tpl";a:1:{s:11:"is_writable";i:0;}s:45:"./templates/default/display_newsdategroup.tpl";a:1:{s:11:"is_writable";i:0;}s:35:"./templates/default/php_display.tpl";a:1:{s:11:"is_writable";i:0;}s:40:"./templates/default/error_invalidurl.tpl";a:1:{s:11:"is_writable";i:0;}s:43:"./templates/default/displaycomment_view.tpl";a:1:{s:11:"is_writable";i:0;}s:31:"./templates/default/comment.tpl";a:1:{s:11:"is_writable";i:0;}s:36:"./templates/default/commentadded.tpl";a:1:{s:11:"is_writable";i:0;}s:34:"./templates/default/addcomment.tpl";a:1:{s:11:"is_writable";i:0;}s:39:"./templates/default/addcomment_user.tpl";a:1:{s:11:"is_writable";i:0;}s:35:"./templates/default/archivelist.tpl";a:1:{s:11:"is_writable";i:0;}s:35:"./templates/default/sendto_sent.tpl";a:1:{s:11:"is_writable";i:0;}s:36:"./templates/default/sendtofriend.tpl";a:1:{s:11:"is_writable";i:0;}s:35:"./templates/default/search_easy.tpl";a:1:{s:11:"is_writable";i:0;}s:32:"./templates/default/rss/feed.xsl";a:1:{s:11:"is_writable";i:0;}s:34:"./templates/default/rss/index.html";a:1:{s:11:"is_writable";i:0;}s:32:"./templates/default/rss/feed.css";a:1:{s:11:"is_writable";i:0;}s:40:"./templates/default/search_noresults.tpl";a:1:{s:11:"is_writable";i:0;}s:39:"./templates/default/search_advanced.tpl";a:1:{s:11:"is_writable";i:0;}s:38:"./templates/default/search_results.tpl";a:1:{s:11:"is_writable";i:0;}s:43:"./templates/default/search_results_list.tpl";a:1:{s:11:"is_writable";i:0;}s:44:"./templates/default/user_register_logged.tpl";a:1:{s:11:"is_writable";i:0;}s:41:"./templates/default/user_registerform.tpl";a:1:{s:11:"is_writable";i:0;}s:43:"./templates/default/user_register_error.tpl";a:1:{s:11:"is_writable";i:0;}s:39:"./templates/default/user_registered.tpl";a:1:{s:11:"is_writable";i:0;}s:38:"./templates/default/user_loginform.tpl";a:1:{s:11:"is_writable";i:0;}s:38:"./templates/default/user_loggedout.tpl";a:1:{s:11:"is_writable";i:0;}s:37:"./templates/default/user_loggedin.tpl";a:1:{s:11:"is_writable";i:0;}s:22:"./templates/index.html";a:1:{s:11:"is_writable";i:0;}s:31:"./install/upgrade_functions.php";a:1:{s:11:"is_writable";i:0;}s:31:"./install/install_functions.php";a:1:{s:11:"is_writable";i:0;}s:21:"./install/install.php";a:1:{s:11:"is_writable";i:0;}s:21:"./install/upgrade.php";a:1:{s:11:"is_writable";i:0;}s:44:"./install/templates/install_nav_complete.tpl";a:1:{s:11:"is_writable";i:0;}s:36:"./install/templates/install_body.tpl";a:1:{s:11:"is_writable";i:0;}s:35:"./install/templates/install_nav.tpl";a:1:{s:11:"is_writable";i:0;}s:46:"./install/templates/install_tablelist_fail.tpl";a:1:{s:11:"is_writable";i:0;}s:44:"./install/templates/install_filecheck_ok.tpl";a:1:{s:11:"is_writable";i:0;}s:41:"./install/templates/install_dbdetails.tpl";a:1:{s:11:"is_writable";i:0;}s:37:"./install/templates/install_table.tpl";a:1:{s:11:"is_writable";i:0;}s:44:"./install/templates/install_tablelist_ok.tpl";a:1:{s:11:"is_writable";i:0;}s:43:"./install/templates/install_useraccount.tpl";a:1:{s:11:"is_writable";i:0;}s:38:"./install/templates/install_record.tpl";a:1:{s:11:"is_writable";i:0;}s:30:"./install/templates/index.html";a:1:{s:11:"is_writable";i:0;}s:40:"./install/templates/install_complete.tpl";a:1:{s:11:"is_writable";i:0;}s:42:"./install/templates/upgrade_tblinstall.tpl";a:1:{s:11:"is_writable";i:0;}s:36:"./install/templates/upgrade_body.tpl";a:1:{s:11:"is_writable";i:0;}s:35:"./install/templates/upgrade_nav.tpl";a:1:{s:11:"is_writable";i:0;}s:44:"./install/templates/upgrade_nav_complete.tpl";a:1:{s:11:"is_writable";i:0;}s:44:"./install/templates/upgrade_filecheck_ok.tpl";a:1:{s:11:"is_writable";i:0;}s:40:"./install/templates/upgrade_dbconfig.tpl";a:1:{s:11:"is_writable";i:0;}s:45:"./install/templates/upgrade_recordinstall.tpl";a:1:{s:11:"is_writable";i:0;}s:40:"./install/templates/upgrade_complete.tpl";a:1:{s:11:"is_writable";i:0;}s:25:"./install/imgs/wbnews.png";a:1:{s:11:"is_writable";i:0;}s:25:"./install/imgs/index.html";a:1:{s:11:"is_writable";i:0;}s:23:"./install/functions.php";a:1:{s:11:"is_writable";i:0;}s:21:"./includes/bbcode.php";a:1:{s:11:"is_writable";i:0;}s:23:"./includes/function.php";a:1:{s:11:"is_writable";i:0;}s:21:"./includes/common.php";a:1:{s:11:"is_writable";i:0;}s:24:"./includes/constants.php";a:1:{s:11:"is_writable";i:0;}s:21:"./includes/index.html";a:1:{s:11:"is_writable";i:0;}s:29:"./includes/lang/en/index.html";a:1:{s:11:"is_writable";i:0;}s:33:"./includes/lang/en/lang_admin.php";a:1:{s:11:"is_writable";i:0;}s:32:"./includes/lang/en/lang_main.php";a:1:{s:11:"is_writable";i:0;}s:26:"./includes/lang/index.html";a:1:{s:11:"is_writable";i:0;}s:27:"./includes/lib/db_mysql.php";a:1:{s:11:"is_writable";i:0;}s:33:"./includes/lib/formvalidation.php";a:1:{s:11:"is_writable";i:0;}s:25:"./includes/lib/index.html";a:1:{s:11:"is_writable";i:0;}s:27:"./includes/lib/template.php";a:1:{s:11:"is_writable";i:0;}s:30:"./includes/admin-functions.php";a:1:{s:11:"is_writable";i:0;}s:33:"./examples/example_singlenews.php";a:1:{s:11:"is_writable";i:0;}s:29:"./examples/example_search.php";a:1:{s:11:"is_writable";i:0;}s:26:"./examples/example_rss.php";a:1:{s:11:"is_writable";i:0;}s:30:"./examples/example_reguser.php";a:1:{s:11:"is_writable";i:0;}s:27:"./examples/example_main.php";a:1:{s:11:"is_writable";i:0;}s:29:"./examples/example_logout.php";a:1:{s:11:"is_writable";i:0;}s:28:"./examples/example_login.php";a:1:{s:11:"is_writable";i:0;}s:33:"./examples/example_latestnews.php";a:1:{s:11:"is_writable";i:0;}s:32:"./examples/example_groupdate.php";a:1:{s:11:"is_writable";i:0;}s:30:"./examples/example_archive.php";a:1:{s:11:"is_writable";i:0;}s:21:"./examples/index.html";a:1:{s:11:"is_writable";i:0;}s:17:"./emoticons/1.gif";a:1:{s:11:"is_writable";i:0;}s:17:"./emoticons/2.gif";a:1:{s:11:"is_writable";i:0;}s:17:"./emoticons/3.gif";a:1:{s:11:"is_writable";i:0;}s:17:"./emoticons/4.gif";a:1:{s:11:"is_writable";i:0;}s:17:"./emoticons/5.gif";a:1:{s:11:"is_writable";i:0;}s:17:"./emoticons/6.gif";a:1:{s:11:"is_writable";i:0;}s:17:"./emoticons/7.gif";a:1:{s:11:"is_writable";i:0;}s:17:"./emoticons/8.gif";a:1:{s:11:"is_writable";i:0;}s:17:"./emoticons/9.gif";a:1:{s:11:"is_writable";i:0;}s:22:"./emoticons/index.html";a:1:{s:11:"is_writable";i:0;}s:19:"./avatar/index.html";a:1:{s:11:"is_writable";i:0;}s:18:"./admin/global.php";a:1:{s:11:"is_writable";i:0;}s:17:"./admin/login.php";a:1:{s:11:"is_writable";i:0;}s:18:"./admin/update.php";a:1:{s:11:"is_writable";i:0;}s:17:"./admin/index.php";a:1:{s:11:"is_writable";i:0;}s:22:"./admin/newsconfig.php";a:1:{s:11:"is_writable";i:0;}s:18:"./admin/themes.php";a:1:{s:11:"is_writable";i:0;}s:23:"./admin/wbxmlUpdate.php";a:1:{s:11:"is_writable";i:0;}s:20:"./admin/category.php";a:1:{s:11:"is_writable";i:0;}s:20:"./admin/database.php";a:1:{s:11:"is_writable";i:0;}s:19:"./admin/comment.php";a:1:{s:11:"is_writable";i:0;}s:18:"./admin/backup.php";a:1:{s:11:"is_writable";i:0;}s:21:"./admin/emoticons.php";a:1:{s:11:"is_writable";i:0;}s:21:"./admin/xmlBackup.php";a:1:{s:11:"is_writable";i:0;}s:21:"./admin/directory.php";a:1:{s:11:"is_writable";i:0;}s:21:"./admin/sqlBackup.php";a:1:{s:11:"is_writable";i:0;}s:16:"./admin/news.php";a:1:{s:11:"is_writable";i:0;}s:19:"./admin/sendmsg.php";a:1:{s:11:"is_writable";i:0;}s:21:"./admin/usergroup.php";a:1:{s:11:"is_writable";i:0;}s:16:"./admin/user.php";a:1:{s:11:"is_writable";i:0;}}';
    chdir("..");
    $thisSerial = serialize(checkUploadedFiles("./"));
    chdir("install");
    
    $contents['nav'] = createNavSec($tpl, $upgradePhases, -1);
    
    if (($errors = checkAllFiles($serial, $thisSerial)) === true)
    {
        
        $_SESSION['upgrade'] = array("step" => 1);
        $contents['content'] = $tpl->getTemplate('upgrade_filecheck_ok');
    }
    else
    {
        
        // get invalid files
        // get files which arent uploaded
        
        $files = "";
        $numFiles = sizeof($errors);
        for ($i = 0; $i < $numFiles; $i++)
            $files .= $errors[$i] ."<br />\n";
        
        $contents['content'] = $tpl->replace($tpl->getTemplate('upgrade_filecheck_fail'), array("files" => $files));
        
    }
    
    $tpl->displayTemplate($tpl->replace($tpl->getTemplate('upgrade_body'), $contents));
    
}
else
{
    
    switch ($_SESSION['upgrade']['step'])
    {
        
    case 1:
    
        if (updateVersionCheck('1.0.0', 'updateConfigVersion1') || updateVersionCheck('1.0.1', 'updateConfigVersion1'))
        {
            $_SESSION['upgrade']['step'] = 4;
            header("location: upgrade.php?step=4");
        }
        
        // update tables
        updateUGroups($dbclass, $config['prefix']);
        updateNewsConfig($dbclass, $config['prefix']);
        updateNewsTbl($dbclass, $config['prefix']);
        upgrade_menuSystem($dbclass, $config['prefix']);
        updateConfig();
        
        $contents['nav'] = createNavSec($tpl, $upgradePhases, $_SESSION['upgrade']['step']);
        
        $_SESSION['upgrade']['step'] = 2;
        $contents['content'] = $tpl->getTemplate('upgrade_dbconfig');
        $tpl->displayTemplate($tpl->replace($tpl->getTemplate('upgrade_body'), $contents));
    
    break;
    case 2:
    
        addEmoticonsTbl($dbclass, $config['prefix']);
    
        $contents['nav'] = createNavSec($tpl, $upgradePhases, $_SESSION['upgrade']['step']);
        
        $_SESSION['upgrade']['step'] = 3;
        $contents['content'] = $tpl->getTemplate('upgrade_tblinstall');
        $tpl->displayTemplate($tpl->replace($tpl->getTemplate('upgrade_body'), $contents));
    
    break;
    case 3:
    
        addEmoticons($dbclass, $config['prefix']);
    
        $contents['nav'] = createNavSec($tpl, $upgradePhases, $_SESSION['upgrade']['step']);
        
        $_SESSION['upgrade']['step'] = 4;
        $contents['content'] = $tpl->getTemplate('upgrade_recordinstall');
        $tpl->displayTemplate($tpl->replace($tpl->getTemplate('upgrade_body'), $contents));
    
    break;
    case 4:
    
        $contents['nav'] = createNavSec($tpl, $upgradePhases, $_SESSION['upgrade']['step']);
        
        unset($_SESSION['upgrade']);
        unset($_SESSION['install']);
        $contents['content'] = $tpl->getTemplate('upgrade_complete');
        $tpl->displayTemplate($tpl->replace($tpl->getTemplate('upgrade_body'), $contents));
    
    break;
        
    }
    
}

?>
