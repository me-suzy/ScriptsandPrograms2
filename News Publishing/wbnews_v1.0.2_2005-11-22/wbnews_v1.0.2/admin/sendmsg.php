<?php

/*========================================================*\
||########################################################||
||#                                                      #||
||#     WB News v1.0.0                                   #||
||# ---------------------------------------------------- #||
||#     Copyright (c) 2004-2005                          #||
||#     Created: 9th September 2005                      #||
||#     Filename: sendmsg.php                            #||
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
include $config['installdir']."/templates/".$theme['THEME_DIRECTORY']."/admin/theme_info.php";

if (!checkLogged($dbclass) === true)
    redirect($tpl, $themeInfo['redirect']['NOT_LOGGED_IN'], PAGE_LOGIN);
else
{
    
    $sendMsg = $dbclass->db_fetchall("SELECT *, DATE_FORMAT( FROM_UNIXTIME(`time` + (3600 * ".$newsConfig['timezone']." )) , '". $newsConfig['dateFormat'] ."' ) AS `time` 
                                      FROM " . TBL_SEND
                                      );
    
    $getNews = $dbclass->db_query("SELECT COUNT(s.id) AS nummsg, title, n.id
                                   FROM " . TBL_NEWS . " as n, " . TBL_SEND . " as s
                                   WHERE s.newsid = n.id
                                   GROUP BY n.id
                                   ORDER BY n.timeposted DESC
                                   ");
    
    $i = 0;
    $numSendMsg = sizeof($sendMsg);
    $contents['sendmsg'] = '';
        
    if (!defined("INC_FUNC"))
        include $config['installdir']."/includes/function.php";
        
    while ($news = $dbclass->db_fetcharray($getNews))
    {
        $news['messagelist'] = '';
        for ($j = 0; $j < $numSendMsg; $j++)
        {
            if ($sendMsg[$j]['newsid'] == $news['id'])
            {
                $sendMsg[$j]['message'] = word_wrap(nl2br($sendMsg[$j]['message']), $newsConfig['wordwrap'], LINE_BREAK);
                $news['messagelist'] .= $tpl->replace($tpl->getTemplate('sendmsglist'), $sendMsg[$j]);
            }
        }
        
        $news['alternate-rows'] = (($i % 2) == 0 ? $themeInfo['alternate_color1'] : $themeInfo['alternate_color2']);
        $contents['sendmsg'] .= $tpl->replace($tpl->getTemplate('sendmsg_newslist'), $news);
        $i++;
    }
    
    /*
        Add normal Array $contents + required Arrays such as Theme, User Info
    */
    $contents = array_merge($contents, $GLOBAL, array("MENU_SECTION" => getMenuSections($dbclass, $tpl), "MENU" => getMenu($dbclass, $tpl)));
    $tpl->displayTemplate($tpl->replace($tpl->getTemplate('sendmsg_body'), $contents));
}

?>
