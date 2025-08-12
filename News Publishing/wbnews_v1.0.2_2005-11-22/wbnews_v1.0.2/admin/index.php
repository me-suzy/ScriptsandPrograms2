<?php

/*========================================================*\
||########################################################||
||#                                                      #||
||#     WB News v1.0.0                                   #||
||# ---------------------------------------------------- #||
||#     Copyright (c) 2004-2005                          #||
||#     Created: 15th August 2005                        #||
||#     Filename: index.php                              #||
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
else
{
    
    $getInfo = $dbclass->db_fetchall("SELECT VERSION() as version");
    $getNews = $dbclass->db_fetchall("SELECT COUNT(" . TBL_NEWS . ".id) as numnews FROM " . TBL_NEWS);
    $getUsers = $dbclass->db_fetchall("SELECT COUNT(" . TBL_USERS . ".userid) as numusers FROM " . TBL_USERS);
    
    $contents = array(
                      "OS_VERSION" => php_uname("s"),
                      "PHP_VERSION" => phpversion(),
                      "MYSQL_VERSION" => $getInfo[0]["version"],
                      "NEWS_ARTICLES" => $getNews[0]["numnews"],
                      "USERS_TOTAL" => $getUsers[0]["numusers"]
                     );

    /*
        Add normal Array $contents + required Arrays such as Theme, User Info
    */
    $contents = array_merge($contents, $GLOBAL, array("MENU_SECTION" => getMenuSections($dbclass, $tpl), "MENU" => getMenu($dbclass, $tpl)));
    
    $tpl->displayTemplate($tpl->replace($tpl->getTemplate("index_body"), $contents));
    
}

?>
