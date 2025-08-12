<?php

/*========================================================*\
||########################################################||
||#                                                      #||
||#     WB News v1.0.0                                   #||
||# ---------------------------------------------------- #||
||#     Copyright (c) 2004-2005                          #||
||#     Created: 23rd August 2005                        #||
||#     Filename: comment.php                            #||
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
else if (!admin_permissions($dbclass, PAGE_COMMENT, (isset($_GET['action']) ? $_GET['action'] : "")))
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
        //############################### LIST COMMENTS ###############################//
        
        $comments = $dbclass->db_fetchall("SELECT *, DATE_FORMAT( FROM_UNIXTIME(`timeposted` + (".toGMT().") + (3600 * ".$newsConfig['timezone']." )) , '". $newsConfig['dateFormat'] ."' ) as `timeposted`, @spam := '' AS spam FROM " . TBL_COMMENTS);
        
        $getNews = $dbclass->db_query("SELECT COUNT(c.id) AS numcomments, n.title, n.id AS newsid
                                       FROM " . TBL_NEWS . " as n, " . TBL_COMMENTS . " as c
                                       WHERE c.newsid = n.id
                                       GROUP BY n.id
                                       ORDER BY n.timeposted DESC
                                       ");
        
        $i = 0;
        $numSize = sizeof($comments);
        $contents['comments'] = '';
        
        if (!defined("INC_FUNC"))
            include $config['installdir']."/includes/function.php";
        
        while ($news = $dbclass->db_fetcharray($getNews))
        {
            $news['comments'] = '';
            for ($j = 0; $j < $numSize; $j++)
                if ($comments[$j]['newsid'] == $news['newsid'])
                {
                    $comments[$j]['message'] = word_wrap(nl2br(preg_replace("/{(.+?)}/is", "{ $1 }", $comments[$j]['message'])), $newsConfig['wordwrap'], LINE_BREAK);
                    if ($comments[$j]['is_spam'] == 1)
                    {
                        // this needs the spam link
                        $comments[$j]['spam'] = $tpl->replace($themeInfo['template']['spam_comment_link'], $comments[$j]);
                        $news['comments'] .= $tpl->replace($tpl->getTemplate('commentlist'), $comments[$j]);
                    }
                    else
                        $news['comments'] .= $tpl->replace($tpl->getTemplate('commentlist'), $comments[$j]);
                }
                
            $news['title'] = preg_replace("/{(.+?)}/is", "{ $1 }", $news['title']);
            $news['alternate-rows'] = (($i % 2) == 0 ? $themeInfo['alternate_color1'] : $themeInfo['alternate_color2']);
            $contents['comments'] .= $tpl->replace($tpl->getTemplate('comments-newslist'), $news);
            $i++;
        }
        
        if ($i == 0)
            $contents['comments'] = $themeInfo['norecords']['comments'];
        
        $contents = array_merge($contents, $GLOBAL, array("MENU_SECTION" => getMenuSections($dbclass, $tpl), "MENU" => getMenu($dbclass, $tpl)));
        $tpl->displayTemplate($tpl->replace($tpl->getTemplate('comment_body'), $contents));
    }
    else
    {
        
        switch ($_GET['action'])
        {
            
        case 'modify':
        //############################### COMMENT MODIFY ##############################//
        
            if ($dbclass->db_checkRows("SELECT id FROM " . TBL_COMMENTS . " WHERE id = '" . (int)$_GET['id'] . "'"))
            {
                
                $dbclass->db_query("UPDATE " . TBL_COMMENTS . " SET
                                    message = '" . addslashes(htmlentities($_POST['comment_msg'])) . "'
                                    WHERE id = '" . (int)$_GET['id'] . "'
                                    ");
                
                if ($dbclass->db_affectedrows() === 1)
                    redirect($tpl, $themeInfo['redirect']['COMMENT_MODIFIED'], PAGE_COMMENT);
                else
                    redirect($tpl, $themeInfo['redirect']['COMMENT_MODIFIED_ERROR'], PAGE_COMMENT);
            }
            else
                redirect($tpl, $themeInfo['redirect']['INVALID_URL'], PAGE_COMMENT);
        
        break;
        case 'ban':
        //############################### BAN IP ADDRESS ##############################//
        
            if ($dbclass->db_checkRows("SELECT id FROM ".TBL_COMMENTS." WHERE id = '" . (int)$_GET['id'] . "'"))
            {
        
                $ipAddr = $dbclass->db_fetchall("SELECT ipaddress 
                                                FROM " . TBL_COMMENTS . "
                                                WHERE id = '" . (int)$_GET['id'] . "'
                                                ");
                                                
                
                $dbclass->db_query("UPDATE " . TBL_NEWSCONFIG . " SET
                                    value = TRIM(CONCAT(value, ' ".$ipAddr[0]['ipaddress']."'))
                                    WHERE var = 'ipban'
                                    AND LOCATE('".$ipAddr[0]['ipaddress']."', value) = 0
                                    ");
                                    
                if ($dbclass->db_affectedrows() === 1)
                    redirect($tpl, $themeInfo['redirect']['COMMENT_BAN'], PAGE_COMMENT);
                else
                    redirect($tpl, $themeInfo['redirect']['COMMENT_BAN_ERROR'], PAGE_COMMENT);
            }
            else
                redirect($tpl, $themeInfo['redirect']['INVALID_URL'], PAGE_COMMENT);
        
        break;
        case 'notspam':
        //############################## COMMENT NOT SPAM #############################//
        
            if ($dbclass->db_checkRows("SELECT id FROM " . TBL_COMMENTS . " WHERE id = '" . (int)$_GET['id'] . "'"))
            {
                
                $dbclass->db_query("UPDATE " . TBL_COMMENTS . " SET
                                    is_spam = -1
                                    WHERE id = '" . (int)$_GET['id'] . "'
                                    ");
                
                if ($dbclass->db_affectedrows() === 1)
                    redirect($tpl, $themeInfo['redirect']['COMMENT_NOTSPAM'], PAGE_COMMENT);
                else
                    redirect($tpl, $themeInfo['redirect']['COMMENT_NOTSPAM_ERROR'], PAGE_COMMENT);
                
            }
            else
                redirect($tpl, $themeInfo['redirect']['INVALID_URL'], PAGE_COMMENT);
        break;
        default:
            redirect($tpl, $themeInfo['redirect']['INVALID_URL'], PAGE_COMMENT);
        break;
            
        }
        
    }
    
}

?>
