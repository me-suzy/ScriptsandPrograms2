<?php

/*========================================================*\
||########################################################||
||#                                                      #||
||#     WB News v1.0.0                                   #||
||# ---------------------------------------------------- #||
||#     Copyright (c) 2004-2005                          #||
||#     Created: 1st August 2005                         #||
||#     Filename: global.php                             #||
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

if (checkLogged($dbclass) === true && (!isset($_GET['action'])))
    redirect($tpl, $themeInfo['redirect']['LOGGED_IN'], PAGE_HOME);
else if (checkLogged($dbclass) === true && isset($_GET['action']) && $_GET['action'] === "logout")
{
    logoutAdminUser();
    redirect($tpl, $themeInfo['redirect']['LOGOUT'], PAGE_LOGIN);
}
else
{
    
    $logUserIn = $errormsg = false;
    if (isset($_POST['login_submit']))
    {
        if (loginAdminUser($dbclass, $_POST['admin_uname'], $_POST['admin_pword'], $config['salt']))
            $logUserIn = true;
        else
            $errormsg = "Username / Password Invalid";
    }
    
    if (!$logUserIn)
    {
        
        $contents = array(
                         "error" => (isset($errormsg) ? $errormsg : "")
                         );
                         
        $contents = array_merge($contents, $theme);
        $tpl->displayTemplate($tpl->replace($tpl->getTemplate("login"), $contents));
    }
    else
        redirect($tpl,  $themeInfo['redirect']['LOGGED_IN'], PAGE_HOME); // user logged in now lets redirect them to Administration
    
}

?>
