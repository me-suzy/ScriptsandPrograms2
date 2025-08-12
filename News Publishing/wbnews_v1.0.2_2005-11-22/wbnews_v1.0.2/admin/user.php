<?php

/*========================================================*\
||########################################################||
||#                                                      #||
||#     WB News v1.0.0                                   #||
||# ---------------------------------------------------- #||
||#     Copyright (c) 2004-2005                          #||
||#     Created: 24th August 2005                        #||
||#     Filename: user.php                               #||
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
else if (!admin_permissions($dbclass, PAGE_USER, (isset($_GET['action']) ? $_GET['action'] : "")))
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
    if (!isset($_GET['action']))
    {
        //############################ ACCOUNT SEARCH MAIN ############################//
        $contents = array();
        
        /*
            Add normal Array $contents + required Arrays such as Theme, User Info
        */
        $contents = array_merge($contents, $GLOBAL, array("MENU_SECTION" => getMenuSections($dbclass, $tpl), "MENU" => getMenu($dbclass, $tpl)));
        $tpl->displayTemplate($tpl->replace($tpl->getTemplate('usersearch_body'), $contents));
    }
    else
    {
        
        switch ($_GET['action'])
        {
        
        case 'add':
        //############################# ADD USER ACCOUNT ##############################//
        
            $showForm = true;
            if (isset($_POST['user_submit']))
            {
                // process form
                if (!defined("LIB_FORMVAL"))
                {
                    include "../includes/lib/formvalidation.php";
                    $formVal = new formVal();
                }
                
                if ($dbclass->db_checkRows("SELECT username FROM ".TBL_USERS." WHERE username = '".addslashes($_POST['username'])."'"))
                    $formVal->addError("Username Already Exists");
                
                $formVal->validEmail($_POST['email']);
                $formVal->checkEmpty($_POST['user_password'], "Password", 4);
                $formVal->checkEmpty($_POST['username'], "Username", 4);
                $formVal->checkEmpty($_POST['postname'], "Post Name", 4);
                $formVal->match($_POST['user_password'], $_POST['user_confpassword'], "Password", "Confirm Password");
                
                if (sizeof($formVal->errors) != 0)
                    $error = $formVal->displayErrors();
                else
                    $showForm = false;
                
            }
        
            if ($showForm == true)
            {
                
                $contents = array(
                                  "error" => (isset($error) ? $error : ""),
                                  "FORM_USERNAME" => $tpl->textinput("username", (isset($_POST['username']) ? $_POST['username'] : "") ),
                                  "FORM_USERGROUP" => $tpl->dropdown("usergroupid", usergroups(), (isset($_POST['usergroupid']) ? $_POST['usergroupid'] : "")),
                                  "FORM_POSTNAME" => $tpl->textinput("postname", (isset($_POST['postname']) ? $_POST['postname'] : "") ),
                                  "FORM_EMAIL" => $tpl->textinput("email", (isset($_POST['email']) ? $_POST['email'] : "" )),
                                  );
                
                /*
                    Add normal Array $contents + required Arrays such as Theme, User Info
                */
                $contents = array_merge($contents, $GLOBAL, array("MENU_SECTION" => getMenuSections($dbclass, $tpl), "MENU" => getMenu($dbclass, $tpl)));
                $tpl->displayTemplate($tpl->replace($tpl->getTemplate('useradd_body'), $contents));
                
            }
            else
            {
                
                $dbclass->db_query("INSERT INTO ".TBL_USERS."
                                    (userid, usergroupid, username, password, postname, email)
                                    VALUES ('NULL', '" . (int)$_POST['usergroupid'] . "', '".addslashes(htmlentities($_POST['username']))."', '". md5($_POST['user_password'] . $config['salt']). "',
                                            '". addslashes($_POST['postname']) ."', '". addslashes($_POST['email']) ."')
                                   ");
                
                if ($dbclass->db_affectedrows() === 1)
                    redirect($tpl, $themeInfo['redirect']['USER_ADDED'], PAGE_USER);
                else
                    redirect($tpl, $themeInfo['redirect']['USER_ADDED_ERROR'], PAGE_USER);
                
            }
        
        break;
        case 'modify':
        //################################ USER MODIFY ################################//
        
            $userInfo = $dbclass->db_fetcharray($dbclass->db_query("SELECT * FROM ".TBL_USERS." WHERE username = '".addslashes($_POST['search_username'])."'"));
            
            if ($userInfo)
            {
                
                $showForm = true;
                if (isset($_POST['user_submit']))
                {
                    // process form
                    if (!defined("LIB_FORMVAL"))
                    {
                        include "../includes/lib/formvalidation.php";
                        $formVal = new formVal();
                    }
                    
                    $formVal->validEmail($_POST['email']);
                    if (!empty($_POST['password']))
                        $formVal->checkEmpty($_POST['password'], "Password", 4);
                        
                    if (sizeof($formVal->errors) != 0)
                        $error = $formVal->displayErrors();
                    else
                        $showForm = false;
                    
                }
                
                if ($showForm == true)
                {
                    
                    $contents = array(
                                      "USERNAME" => $userInfo['username'],
                                      "FORM_EMAIL" => $tpl->textinput("email", (isset($_POST['email']) ? $_POST['email'] : $userInfo['email']) ),
                                      "FORM_USERGROUP" => $tpl->dropdown("usergroupid", usergroups(), (isset($_POST['usergroupid']) ? $_POST['usergroupid'] : $userInfo['usergroupid'])),
                                      "FORM_POSTNAME" => $tpl->textinput("postname", (isset($_POST['postname']) ? $_POST['postname'] : $userInfo['postname']) ),
                                      "error" => (isset($error) ? $error : ""),
                                      "userid" => $userInfo['userid']
                                      );
                    
                    /*
                        Add normal Array $contents + required Arrays such as Theme, User Info
                    */
                    $contents = array_merge($contents, $GLOBAL, array("MENU_SECTION" => getMenuSections($dbclass, $tpl), "MENU" => getMenu($dbclass, $tpl)));
                    $tpl->displayTemplate($tpl->replace($tpl->getTemplate('usermodify_body'), $contents));
                    
                }
                else
                {
                    // update user account
                    $dbclass->db_query("UPDATE ".TBL_USERS." SET
                                        " . (!empty($_POST['password']) ? "password = '" . md5($_POST['password'] . $config['salt']) . "', " : "") ."
                                        usergroupid = '" . (int)$_POST['usergroupid'] . "',
                                        email = '" . addslashes($_POST['email'])."',
                                        postname = '" . addslashes($_POST['postname'])."'
                                        WHERE username = '" . addslashes($_POST['search_username']) . "'
                                        ");
                                    
                    if ($dbclass->db_affectedrows() === 1)
                        redirect($tpl, $themeInfo['redirect']['USER_MODIFIED'], PAGE_USER);
                    else
                        redirect($tpl, $themeInfo['redirect']['USER_MODIFIED_ERROR'], PAGE_USER);
                }
                
            }
            else
                redirect($tpl, $themeInfo['redirect']['INVALID_URL'], PAGE_USER);
                
        break;
        case 'delete':
        //################################ USER DELETE ################################//
            if ($dbclass->db_checkRows("SELECT userid FROM " . TBL_USERS . " WHERE userid = '" . (int)$_GET['userid'] . "' AND usergroupid != '1'") && ((int)$_GET['userid'] !== (int)$_SESSION['wbnews-admin_login']['userid']))
            {
                
                // user cannot be self
                // user cannot be within the Super Administration Group
                
                $dbclass->db_query("DELETE FROM " . TBL_USERS . "
                                    WHERE userid = '" . (int)$_GET['userid'] . "'
                                    ");
                
                if ($dbclass->db_affectedrows() === 1)
                    redirect($tpl, $themeInfo['redirect']['USER_DELETED'], PAGE_USER);
                else
                    redirect($tpl, $themeInfo['redirect']['USER_DELETED_ERROR'], PAGE_USER);
                
            }
            else
                redirect($tpl, $themeInfo['redirect']['INVALID_URL'], PAGE_USER);
        
        break;
        case 'search':
        //################################ USER SEARCH ################################//
        
            $showForm = true;
            if (isset($_POST['submit']))
            {
                
                // process form
                if (!defined("LIB_FORMVAL"))
                {
                    include "../includes/lib/formvalidation.php";
                    $formVal = new formVal();
                }
                
                // check if username could be found
                if (!empty($_POST['username_like']))
                {
                    if (!$dbclass->db_checkRows("SELECT username FROM ".TBL_USERS." WHERE username LIKE '%".addslashes($_POST['username_like'])."%' AND usergroupid > '" . (int)$_SESSION['wbnews-admin_login']['usergroupid'] ."'"))
                        $formVal->addError("No Usernames Matched Query");
                }
                else
                    $formVal->addError("Username Search Field Empty");
                
                if (sizeof($formVal->errors) != 0)
                    $error = $formVal->displayErrors();
                else
                    $showForm = false;
                    
            }
            
            if ($showForm == true)
            {
                
                $contents = array(
                                  "error" => (isset($error) ? $error : "")
                                  );
                
                /*
                    Add normal Array $contents + required Arrays such as Theme, User Info
                */
                $contents = array_merge($contents, $GLOBAL, array("MENU_SECTION" => getMenuSections($dbclass, $tpl), "MENU" => getMenu($dbclass, $tpl)));
                $tpl->displayTemplate($tpl->replace($tpl->getTemplate('usersearchform_body'), $contents));
            }
            else
            {
                
                $users = $dbclass->db_fetchall("SELECT username 
                                                FROM ".TBL_USERS." 
                                                WHERE username LIKE '%".addslashes($_POST['username_like'])."%'
                                                ");
                                                
                
                $numUsers = sizeof($users);
                $contents['list'] = '';
                
                for ($i = 0; $i < $numUsers; $i++)
                {
                    $users[$i]['alternate-rows'] = (($i % 2) == 0) ? $themeInfo['alternate_color1'] : $themeInfo['alternate_color2'];
                    $contents['list'] .= $tpl->replace($tpl->getTemplate('usersearch_list'), $users[$i]);
                }
                
                /*
                    Add normal Array $contents + required Arrays such as Theme, User Info
                */
                $contents = array_merge($contents, $GLOBAL, array("MENU_SECTION" => getMenuSections($dbclass, $tpl), "MENU" => getMenu($dbclass, $tpl)));
                $tpl->displayTemplate($tpl->replace($tpl->getTemplate('usersearchlist_body'), $contents));
                
            }
        
        break;
        case 'myaccount':
        //############################### CATEGORY LIST ###############################//
        
            $contents = $dbclass->db_fetcharray($dbclass->db_query("SELECT * FROM ".TBL_USERS." WHERE userid = '".(int)$_SESSION['wbnews-admin_login']['userid']."'"));
        
            $showForm = true;
            if (isset($_POST['update_submit']))
            {
                // process form
                if (!defined("LIB_FORMVAL"))
                {
                    include "../includes/lib/formvalidation.php";
                    $formVal = new formVal();
                }
                
                $formVal->validEmail($_POST['email']);
                if (!empty($_POST['password']) || !empty($_POST['conf_password']))
                    $formVal->match($_POST['password'], $_POST['conf_password'], "Password", "Confirm Password");
                
                if (sizeof($formVal->errors) != 0)
                    $error = $formVal->displayErrors();
                else
                    $showForm = false;
            }
            
            if ($showForm == true)
            {
                // display form
                
                $contents = array_merge($contents, $GLOBAL, array("MENU_SECTION" => getMenuSections($dbclass, $tpl), "MENU" => getMenu($dbclass, $tpl)));
                $tpl->displayTemplate($tpl->replace($tpl->getTemplate('useraccount_body'), $contents));
            }
            else
            {
                // update user account
                $dbclass->db_query("UPDATE ".TBL_USERS." SET
                                    " . (!empty($_POST['password']) ? "password = '" . md5($_POST['password'] . $config['salt']) . "', " : "") ."
                                    email = '" . addslashes($_POST['email'])."',
                                    postname = '" . addslashes($_POST['postname'])."'
                                    WHERE userid = '" . (int)$_SESSION['wbnews-admin_login']['userid'] . "'
                                    ");
                                    
                if ($dbclass->db_affectedrows() === 1)
                    redirect($tpl, $themeInfo['redirect']['USER_ACCOUNT'], PAGE_USER . "?action=myaccount");
                else
                    redirect($tpl, $themeInfo['redirect']['USER_ACCOUNT_ERROR'], PAGE_USER . "?action=myaccount");
                
            }
        
        break;
        default:
        //############################### CATEGORY LIST ###############################//
            redirect($tpl, $themeInfo['redirect']['INVALID_URL'], PAGE_USER);
        break;
            
        }
        
    }
    
}

?>
