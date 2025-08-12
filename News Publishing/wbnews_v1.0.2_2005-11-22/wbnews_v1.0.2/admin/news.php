<?php

/*========================================================*\
||########################################################||
||#                                                      #||
||#     WB News v1.0.0                                   #||
||# ---------------------------------------------------- #||
||#     Copyright (c) 2004-2005                          #||
||#     Created: 16th August 2005                        #||
||#     Filename: news.php                               #||
||#                                                      #||
||########################################################||
/*========================================================*/

/**
	@author Paul Mcilwaine - Webmobo
	@version 1.0
	@package AdminCP
*/

define ('wbnews', true);
include "./global.php";

if (!checkLogged($dbclass) === true)
    redirect($tpl, $themeInfo['redirect']['NOT_LOGGED_IN'], PAGE_LOGIN);
else if (!admin_permissions($dbclass, PAGE_NEWS, (isset($_GET['action']) ? $_GET['action'] : "")))
{
    //############################### NO PERMISSION ###############################//
    
    /*
        Add normal Array $contents + required Arrays such as Theme, User Info
    */
    $contents = array_merge($GLOBAL, array("MENU_SECTION" => getMenuSections($dbclass, $tpl), "MENU" => getMenu($dbclass, $tpl)));
    $tpl->displayTemplate($tpl->replace($tpl->getTemplate('nopermission'), $contents));
    
}
else
{
    
    if (!defined("INC_FUNC"))
        include $config['installdir']. "/includes/function.php";
    
    if (!isset($_GET['action']))
    {
        //############################### NEWS LISTING ###############################//
        $getNews = $dbclass->db_query("SELECT ".TBL_USERS.".username as author, DATE_FORMAT( FROM_UNIXTIME(`timeposted` + (".toGMT().") + (3600 * ".$newsConfig['timezone']." )) , '". $newsConfig['dateFormat'] ."' ) AS dateposted, title, id
                                       FROM ".TBL_NEWS.", ".TBL_USERS."
                                       WHERE ".TBL_NEWS.".userid = ".TBL_USERS.".userid
                                       ORDER BY ".TBL_NEWS.".timeposted DESC
                                       ");
        
        $contents['newslist'] = "";
        if ($dbclass->db_numrows($getNews))
        {
            $i = 0;
            while ($news = $dbclass->db_fetcharray($getNews))
            {
                $news['alternate-rows'] = (($i % 2) == 0) ? $themeInfo['alternate_color1'] : $themeInfo['alternate_color2'];
                $contents['newslist'] .= $tpl->replace($tpl->getTemplate('newslist_list'), $news);
                $i++;
            }
        }
        else
            $contents['newslist'] = $themeInfo['norecords']['news'];
        
        $contents = array_merge($contents, $GLOBAL, array("MENU_SECTION" => getMenuSections($dbclass, $tpl), "MENU" => getMenu($dbclass, $tpl)));
        $tpl->displayTemplate($tpl->replace($tpl->getTemplate('newslist_body'), $contents));
        
    }
    else
    {
        
        switch ($_GET['action'])
        {
            
        case "add":
        //################################# NEWS ADD #################################//
            $showForm = true;
            if (isset($_POST['news_submit']))
            {
                // process form
                if (!defined("LIB_FORMVAL"))
                {
                    include "../includes/lib/formvalidation.php";
                    $formVal = new formVal();
                }
                
                $formVal->checkEmpty($_POST['title'], "Title", 4);
                $formVal->checkEmpty($_POST['message'], "Message", 5);
                
                if (sizeof($formVal->errors) != 0)
                    $error = $formVal->displayErrors();
                else
                    $showForm = false;
                
            }
            
            if ($showForm === true)
            {
                
                $categories = $dbclass->db_fetchall("SELECT id, name FROM " . TBL_CATEGORY, "id", "name");
                
                $contents = array(
                                  "action" => "Add",
                                  "formaction" => PAGE_NEWS . "?action=add",
                                  "title" => (isset($_POST['title']) ? $_POST['title'] : ""),
                                  "category" => $tpl->dropdown('catid', ($categories != false ? $categories : array() ), (isset($_POST['catid']) ? $_POST['catid'] : ""), 1),
                                  "message" => (isset($_POST['message']) ? $_POST['message'] : ""),
                                  "allowcomment" => $tpl->yesno("allowcomments", LINE_BREAK, (isset($_POST['allowcomments']) ? $_POST['allowcomments'] : 1))
                                  );
                
                $contents = array_merge($contents, $GLOBAL, array("MENU_SECTION" => getMenuSections($dbclass, $tpl), "MENU" => getMenu($dbclass, $tpl)));
                $tpl->displayTemplate($tpl->replace($tpl->getTemplate('newsform_body'), $contents));
                
            }
            else
            {
             
                $dbclass->db_query("INSERT INTO " . TBL_NEWS . "
                                    (id, catid, userid, title, news, timeposted, allowcomments)
                                    VALUES ('null', '" . (int)$_POST['catid'] . "', '" . (int)$_SESSION['wbnews-admin_login']['userid'] . "', '" . htmlentities(addslashes($_POST['title'])) . "',
                                            '" . htmlentities(addslashes(auto_parseurl($_POST['message']))) . "', '" . time() . "', '" . (int)$_POST['allowcomments'] . "')
                                   ");
                
                if ($dbclass->db_affectedrows() === 1)
                    redirect($tpl, $themeInfo['redirect']['NEWS_ADDED'], PAGE_NEWS);
                else
                    redirect($tpl, $themeInfo['redirect']['NEWS_ADDED_ERROR'], PAGE_NEWS);
                
            }
        
        break;
        case "modify":
        //################################ NEWS MODIFY ###############################//
            if ($dbclass->db_checkRows("SELECT id FROM " . TBL_NEWS . " WHERE id = '".(int)$_GET['newsid']."'"))
            {
                
                $news = $dbclass->db_fetcharray($dbclass->db_query("SELECT id, catid, title, news as message, allowcomments
                                                                    FROM " . TBL_NEWS . "
                                                                    WHERE id = '" . (int)$_GET['newsid'] . "'
                                                                    "));
                
                $showForm = true;
                if (isset($_POST['news_submit']))
                {
                   // process form
                    if (!defined("LIB_FORMVAL"))
                    {
                        include "../includes/lib/formvalidation.php";
                        $formVal = new formVal();
                    }
                    
                    $formVal->checkEmpty($_POST['title'], "Title", 4);
                    $formVal->checkEmpty($_POST['message'], "Message", 5);
                    
                    if (sizeof($formVal->errors) != 0)
                        $error = $formVal->displayErrors();
                    else
                        $showForm = false;
                    
                }
            
                if ($showForm === true)
                {
                    
                    $categories = $dbclass->db_fetchall("SELECT id, name FROM " . TBL_CATEGORY, "id", "name");
                    
                    $contents = array(
                                      "action" => "Modify",
                                      "formaction" => PAGE_NEWS . "?action=modify&amp;newsid=" . $news['id'],
                                      "title" => (isset($_POST['title']) ? $_POST['title'] : $news['title']),
                                      "category" => $tpl->dropdown('catid', ($categories != false ? $categories : array() ), (isset($_POST['catid']) ? $_POST['catid'] : $news['catid']), 1),
                                      "message" => (isset($_POST['message']) ? $_POST['message'] : $news['message']),
                                      "allowcomment" => $tpl->yesno("allowcomments", LINE_BREAK, (isset($_POST['allowcomments']) ? $_POST['allowcomments'] : $news['allowcomments']))
                                      );
                
                    $contents = array_merge($contents, $GLOBAL, array("MENU_SECTION" => getMenuSections($dbclass, $tpl), "MENU" => getMenu($dbclass, $tpl)));
                    $tpl->displayTemplate($tpl->replace($tpl->getTemplate('newsform_body'), $contents));
                    
                }
                else
                {
                    
                    $dbclass->db_query("UPDATE " . TBL_NEWS . " SET
                                        catid = '" . (int)$_POST['catid'] . "',
                                        title = '" . htmlentities(addslashes($_POST['title'])) . "',
                                        news = '" . htmlentities(addslashes(auto_parseurl($_POST['message']))) . "',
                                        allowcomments = '" . (int)$_POST['allowcomments'] . "'
                                        WHERE id = '" . (int)$_GET['newsid'] . "'
                                        ");
                
                    if ($dbclass->db_affectedrows() === 1)
                        redirect($tpl, $themeInfo['redirect']['NEWS_MODIFIED'], PAGE_NEWS);
                    else
                        redirect($tpl, $themeInfo['redirect']['NEWS_MODIFIED_ERROR'], PAGE_NEWS);
                    
                }
                
            }
            else
                redirect($tpl, $themeInfo['redirect']['INVALID_URL'], PAGE_NEWS);
        
        break;
        case "emoticon":
        //############################## EMOTICON WINDOW #############################//
            $getEmoticons = $dbclass->db_query("SELECT * FROM " . TBL_EMOTICON);
            
            $i = 0;
            $contents['emoticonlist'] = '';
            while ($emoticon = $dbclass->db_fetcharray($getEmoticons))
            {
                if (($i % 3) == 0)
                    $contents['emoticonlist'] .= "\t<tr>\n";
                
                $emoticon['code'] = addslashes($emoticon['code']);
                $contents['emoticonlist'] .= $tpl->replace($tpl->getTemplate('newsemoticon_list'), $emoticon);
                $i++;
            }
            
            $tpl->displayTemplate($tpl->replace($tpl->getTemplate('news_emoticon_window'), $contents));
        
        break;
        case "delete":
        //################################ NEWS DELETE ###############################//
            if (isset($_GET['newsid']))
            {
                $dbclass->db_query("DELETE FROM ".TBL_NEWS." WHERE id = '".(int)$_GET['newsid']."'");
                if ($dbclass->db_affectedrows() === 1)
                    redirect($tpl, $themeInfo['redirect']['NEWS_DELETED'], PAGE_NEWS);
                else
                    redirect($tpl, $themeInfo['redirect']['NEWS_DELETED_ERROR'], PAGE_NEWS);
            }
            else
                redirect($tpl, $themeInfo['redirect']['INVALID_URL'], PAGE_NEWS);
        
        break;
            
        }
        
    }
    
}

?>
