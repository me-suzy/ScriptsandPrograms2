<?php

/*========================================================*\
||########################################################||
||#                                                      #||
||#     WB News v1.0.0                                   #||
||# ---------------------------------------------------- #||
||#     Copyright (c) 2004-2005                          #||
||#     Created: 9th September 2005                      #||
||#     Filename: usergroup.php                          #||
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
else if (!admin_permissions($dbclass, PAGE_UGROUP, (isset($_GET['action']) ? $_GET['action'] : "")))
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
        //############################## LIST USERGROUP ###############################//
        
        $getGroups = $dbclass->db_query("SELECT * FROM " . TBL_UGROUPS);
        
        $i = 0;
        $contents['usergroup'] = '';
        
        while ($group = $dbclass->db_fetcharray($getGroups))
        {
            $group['alternate-rows'] = (($i % 2) == 0) ? $themeInfo['alternate_color1'] : $themeInfo['alternate_color2'];
            
            $group['cancontrol'] = $tpl->yesno('cancontrol', ' ', $group['cancontrol']);
            $group['canbackup'] = $tpl->yesno('canbackup', ' ', $group['canbackup']);
            $group['canconfig'] = $tpl->yesno('canconfig', ' ', $group['canconfig']);
            $group['editcomment'] = $tpl->yesno('editcomment', ' ', $group['editcomment']);
            $group['checkupdate'] = $tpl->yesno('checkupdate', ' ', $group['checkupdate']);
            $group['addnews'] = $tpl->yesno('addnews', ' ', $group['addnews']);
            $group['modifynews'] = $tpl->yesno('modifynews', ' ', $group['modifynews']);
            $group['deletenews'] = $tpl->yesno('deletenews', ' ', $group['deletenews']);
            $group['addcategory'] = $tpl->yesno('addcategory', ' ', $group['addcategory']);
            $group['modifycat'] = $tpl->yesno('modifycat', ' ', $group['modifycat']);
            $group['deletecat'] = $tpl->yesno('deletecat', ' ', $group['deletecat']);
            $group['adduser'] = $tpl->yesno('adduser', ' ', $group['adduser']);
            $group['modifyuser'] = $tpl->yesno('modifyuser', ' ', $group['modifyuser']);
            $group['deleteuser'] = $tpl->yesno('deleteuser', ' ', $group['deleteuser']);
            $group['addtheme'] = $tpl->yesno('addtheme', ' ', $group['addtheme']);
            $group['modifytheme'] = $tpl->yesno('modifytheme', ' ', $group['modifytheme']);
            $group['deletetheme'] = $tpl->yesno('deletetheme', ' ', $group['deletetheme']);
            $group['usergroups'] = $tpl->yesno('usergroups', ' ', $group['usergroups']);
            
            $contents['usergroup'] .= $tpl->replace($tpl->getTemplate('usergrouplist'), $group);
            $i++;
        }
        
        /*
            Add normal Array $contents + required Arrays such as Theme, User Info
        */
        $contents = array_merge($contents, $GLOBAL, array("MENU_SECTION" => getMenuSections($dbclass, $tpl), "MENU" => getMenu($dbclass, $tpl)));
        $tpl->displayTemplate($tpl->replace($tpl->getTemplate('usergroupmain_body'), $contents));
        
    }
    else
    {
        
        switch ($_GET['action'])
        {
            
        case 'add':
        //############################### ADD USERGROUP ###############################//
        
            $showForm = true;
            if (isset($_POST['submit_ugroup']))
            {
                
                // process form
                if (!defined("LIB_FORMVAL"))
                {
                    include "../includes/lib/formvalidation.php";
                    $formVal = new formVal();
                }
                
                $formVal->checkEmpty($_POST['title'], "Title", 3);
                if ($dbclass->db_checkRows("SELECT title FROM ".TBL_UGROUPS." WHERE title = '".addslashes($_POST['title'])."'"))
                    $formVal->addError("Title Already Exists");
                
                 if (sizeof($formVal->errors) != 0)
                    $error = $formVal->displayErrors();
                else
                    $showForm = false;
                    
            }
            
            if ($showForm == true)
            {
                
                $contents['error'] = (isset($error) ? $error : "");
                $contents['title'] = $tpl->textinput('title', (isset($_POST['title']) ?  $_POST['title'] : "") );
                $contents['cancontrol'] = $tpl->yesno('cancontrol', '', (isset($_POST['cancontrol']) ?  $_POST['cancontrol'] : "") );
                $contents['canbackup'] = $tpl->yesno('canbackup', '', (isset($_POST['canbackup']) ?  $_POST['canbackup'] : "") );
                $contents['canconfig'] = $tpl->yesno('canconfig', '', (isset($_POST['canconfig']) ?  $_POST['canconfig'] : "") );
                $contents['editcomment'] = $tpl->yesno('editcomment', '', (isset($_POST['editcomment']) ?  $_POST['editcomment'] : "") );
                $contents['checkupdate'] = $tpl->yesno('checkupdate', '', (isset($_POST['checkupdate']) ?  $_POST['checkupdate'] : "") );
                $contents['addnews'] = $tpl->yesno('addnews', ' ', '', (isset($_POST['addnews']) ?  $_POST['addnews'] : "") );
                $contents['modifynews'] = $tpl->yesno('modifynews', '', (isset($_POST['modifynews']) ?  $_POST['modifynews'] : "") );
                $contents['deletenews'] = $tpl->yesno('deletenews', '', (isset($_POST['deletenews']) ?  $_POST['deletenews'] : "") );
                $contents['addcategory'] = $tpl->yesno('addcategory', '', (isset($_POST['addcategory']) ?  $_POST['addcategory'] : "") );
                $contents['modifycat'] = $tpl->yesno('modifycat', '', (isset($_POST['modifycat']) ?  $_POST['modifycat'] : "") );
                $contents['deletecat'] = $tpl->yesno('deletecat', '', (isset($_POST['deletecat']) ?  $_POST['deletecat'] : "") );
                $contents['adduser'] = $tpl->yesno('adduser', '', (isset($_POST['adduser']) ?  $_POST['adduser'] : "") );
                $contents['modifyuser'] = $tpl->yesno('modifyuser', '', (isset($_POST['modifyuser']) ?  $_POST['modifyuser'] : "") );
                $contents['deleteuser'] = $tpl->yesno('deleteuser', '', (isset($_POST['deleteuser']) ?  $_POST['deleteuser'] : "") );
                $contents['addtheme'] = $tpl->yesno('addtheme', '', (isset($_POST['addtheme']) ?  $_POST['addtheme'] : "") );
                $contents['modifytheme'] = $tpl->yesno('modifytheme', '', (isset($_POST['modifytheme']) ?  $_POST['modifytheme'] : "") );
                $contents['deletetheme'] = $tpl->yesno('deletetheme', '', (isset($_POST['deletetheme']) ?  $_POST['deletetheme'] : "") );
                $contents['usergroups'] = $tpl->yesno('usergroups', '', (isset($_POST['usergroups']) ? $_POST['usergroups'] : "") );
        
                /*
                    Add normal Array $contents + required Arrays such as Theme, User Info
                */
                $contents = array_merge($contents, $GLOBAL, array("MENU_SECTION" => getMenuSections($dbclass, $tpl), "MENU" => getMenu($dbclass, $tpl)));
                $tpl->displayTemplate($tpl->replace($tpl->getTemplate('usergroupform_body'), $contents));
                
            }
            else
            {
                
                unset($_POST['submit_ugroup']);
                $sql = "";
                
                foreach ($_POST as $key => $value)
                    if ($key != "title")
                        $sql .= "'" . ((int)$value >= 1 ? 1 : 0) . "', ";
                    else
                        $sql .= "'" . htmlentities(addslashes($value)) . "', ";
                
                $dbclass->db_query("INSERT INTO " . TBL_UGROUPS . "
                                    VALUES ('null', " . substr($sql, 0, -2) . ")
                                    ");
                                    
                if ($dbclass->db_affectedrows() === 1)
                    redirect($tpl, $themeInfo['redirect']['UGROUP_ADD'], PAGE_UGROUP);
                else
                    redirect($tpl, $themeInfo['redirect']['UGROUP_ADD_ERROR'], PAGE_UGROUP);
                
            }
            
        break;
        case 'modify':
        //################################ MODIFY GROUP ###############################//
        
            if ($dbclass->db_checkRows("SELECT usergroupid FROM " . TBL_UGROUPS . " WHERE usergroupid = '" . (int)$_GET['groupid'] . "' AND usergroupid != '1' AND title != 'Super Administrator'"))
            {
                
                /**
                    @TODO
                        Have a form here just for modification
                */
                
                unset($_POST['submit_ugroup']);
                $sql = "";
                
                foreach ($_POST as $key => $value)
                    $sql .= "`" . $key . "` = '" . ((int)$value >= 1 ? 1 : 0) . "', ";
                
                $dbclass->db_query("UPDATE " . TBL_UGROUPS . " SET
                                    " . substr($sql, 0, -2) . "
                                    WHERE usergroupid = '" . (int)$_GET['groupid'] . "'
                                    ");
                                    
                if ($dbclass->db_affectedrows() === 1)
                    redirect($tpl, $themeInfo['redirect']['UGROUP_MODIFIED'], PAGE_UGROUP);
                else
                    redirect($tpl, $themeInfo['redirect']['UGROUP_MODIFED_ERROR'], PAGE_UGROUP);
                
            }
            else
                redirect($tpl, $themeInfo['redirect']['INVALID_URL'], PAGE_UGROUP);
        
        break;
        case 'delete':
        //############################# USERGROUP DELETE ##############################//
        
            if ($dbclass->db_checkRows("SELECT usergroupid FROM " . TBL_UGROUPS . " WHERE usergroupid = '" . (int)$_GET['usergroupid'] . "' AND usergroupid != '1' AND title != 'Super Administrator'"))
            {
                
                $dbclass->db_query("DELETE FROM " . TBL_UGROUPS . "
                                    WHERE usergroupid = '" . (int)$_GET['usergroupid'] . "'
                                    ");
                                    
                if ($dbclass->db_affectedrows() === 1)
                    redirect($tpl, $themeInfo['redirect']['UGROUP_DELETED'], PAGE_UGROUP);
                else
                    redirect($tpl, $themeInfo['redirect']['UGROUP_DELETED_ERROR'], PAGE_UGROUP);
                
            }
            else
                redirect($tpl, $themeInfo['redirect']['INVALID_URL'], PAGE_UGROUP);
        
        break;
        default:
            redirect($tpl, $themeInfo['redirect']['INVALID_URL'], PAGE_UGROUP);
        break;
            
        }
        
    }
    
}

?>
