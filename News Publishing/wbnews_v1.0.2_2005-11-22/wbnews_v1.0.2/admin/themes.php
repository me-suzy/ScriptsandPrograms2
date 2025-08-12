<?php

/*========================================================*\
||########################################################||
||#                                                      #||
||#     WB News v1.0.0                                   #||
||# ---------------------------------------------------- #||
||#     Copyright (c) 2004-2005                          #||
||#     Created: 6th September 2005                      #||
||#     Filename: themes.php                             #||
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
else if (!admin_permissions($dbclass, PAGE_THEME, (isset($_GET['action']) ? $_GET['action'] : "")))
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
        //################################# THEME LIST ################################//
        
        $getThemes = $dbclass->db_query("SELECT * FROM ".TBL_THEMES);
        if ($dbclass->db_numrows($getThemes))
        {
            $i = 0;
            $contents['list'] = '';
            while ($theme = $dbclass->db_fetcharray($getThemes))
            {
                $theme['alternate-rows'] = (($i % 2) == 0) ? $themeInfo['alternate_color1'] : $themeInfo['alternate_color2'];
                $contents['list'] .= $tpl->replace($tpl->getTemplate('themelist'), $theme);
                $i++;
            }
        }
        else
        {}
        
        /*
            Add normal Array $contents + required Arrays such as Theme, User Info
        */
        $contents = array_merge($contents, $GLOBAL, array("MENU_SECTION" => getMenuSections($dbclass, $tpl), "MENU" => getMenu($dbclass, $tpl)));
        $tpl->displayTemplate($tpl->replace($tpl->getTemplate('themelist_body'), $contents));
    }
    else
    {
        
        switch ($_GET['action'])
        {
            
        case 'add':
        //################################# ADD THEME #################################//
        
            $showForm = true;
            if (isset($_POST['theme_submit']))
            {
                // process form
                if (!defined("LIB_FORMVAL"))
                {
                    include "../includes/lib/formvalidation.php";
                    $formVal = new formVal();
                }
                
                $formVal->checkEmpty($_POST['title'], "Title", 2);
                $formVal->checkEmpty($_POST['themepath'], "Theme Directory", 2);
                
                if ($dbclass->db_checkRows("SELECT title FROM ".TBL_THEMES." WHERE title = '".addslashes($_POST['title'])."'"))
                    $formVal->addError("Theme Title Already Exists");
                    
                if (!is_dir($config['installdir'] . "/templates/". $_POST['themepath']))
                    $formVal->addError("Theme Path Doesnt Exist");
                
                if (sizeof($formVal->errors) != 0)
                    $error = $formVal->displayErrors();
                else
                    $showForm = false;
                    
            }
            
            if ($showForm == true)
            {
                
                $contents = array(
                                  "error" => (isset($error) ? $error : ""),
                                  "action" => "Add",
                                  "formaction" => "add",
                                  "title" => (isset($_POST['title']) ? $_POST['title'] : ""),
                                  "themepath" => (isset($_POST['themepath']) ? $_POST['themepath'] : "")
                                  );
                
                /*
                    Add normal Array $contents + required Arrays such as Theme, User Info
                */
                $contents = array_merge($contents, $GLOBAL, array("MENU_SECTION" => getMenuSections($dbclass, $tpl), "MENU" => getMenu($dbclass, $tpl)));
                $tpl->displayTemplate($tpl->replace($tpl->getTemplate('themeform_body'), $contents));
            }
            else
            {
                
                $dbclass->db_query("INSERT INTO ".TBL_THEMES."
                                    (themeid, title, themepath)
                                    VALUES ('null', '" . addslashes($_POST['title']) . "', '" . addslashes($_POST['themepath']) . "')
                                   ");
                
                if ($dbclass->db_affectedrows() === 1)
                    redirect($tpl, $themeInfo['redirect']['THEME_ADD'] , PAGE_THEME);
                else
                    redirect($tpl, $themeInfo['redirect']['THEME_ADD_ERROR'], PAGE_THEME);
                
            }
        
        break;
        case 'modify':
        //################################ MODIFY THEME ###############################//
        
            if ($dbclass->db_checkRows("SELECT themeid FROM ".TBL_THEMES." WHERE themeid = '".(int)$_GET['themeid']."'"))
            {
                
                $theme = $dbclass->db_fetcharray($dbclass->db_query("SELECT * FROM ".TBL_THEMES." WHERE themeid = '".(int)$_GET['themeid']."'"));
                
                $showForm = true;
                if (isset($_POST['theme_submit']))
                {
                    // process form
                    if (!defined("LIB_FORMVAL"))
                    {
                        include "../includes/lib/formvalidation.php";
                        $formVal = new formVal();
                    }
                    
                    $formVal->checkEmpty($_POST['title'], "Title", 2);
                    $formVal->checkEmpty($_POST['themepath'], "Theme Directory", 2);
                
                    if ($dbclass->db_checkRows("SELECT title FROM ".TBL_THEMES." WHERE title = '".addslashes($_POST['title'])."' AND themeid != '". (int)$_GET['themeid'] ."'"))
                        $formVal->addError("Theme Title Already Exists");
                    
                    if (!is_dir($config['installdir'] . "/templates/". $_POST['themepath']))
                        $formVal->addError("Theme Path Doesnt Exist");
                
                    if (sizeof($formVal->errors) != 0)
                        $error = $formVal->displayErrors();
                    else
                        $showForm = false;
                    
                }
            
                if ($showForm == true)
                {
                    
                    $contents = array(
                                      "error" => (isset($error) ? $error : ""),
                                      "action" => "Modify",
                                      "formaction" => "modify&themeid=" . $theme['themeid'],
                                      "title" => (isset($_POST['title']) ? $_POST['title'] : $theme['title']),
                                      "themepath" => (isset($_POST['themepath']) ? $_POST['themepath'] : $theme['themepath'])
                                      );
                    
                    /*
                        Add normal Array $contents + required Arrays such as Theme, User Info
                    */
                    $contents = array_merge($contents, $GLOBAL, array("MENU_SECTION" => getMenuSections($dbclass, $tpl), "MENU" => getMenu($dbclass, $tpl)));
                    $tpl->displayTemplate($tpl->replace($tpl->getTemplate('themeform_body'), $contents));
                }
                else
                {
                    $dbclass->db_query("UPDATE ".TBL_THEMES." SET
                                        title = '" . addslashes($_POST['title']) . "',
                                        themepath = '" . addslashes($_POST['themepath']) . "'
                                        WHERE themeid = '". (int)$_GET['themeid'] . "'
                                       ");
                
                    if ($dbclass->db_affectedrows() === 1)
                        redirect($tpl, $themeInfo['redirect']['THEME_MODIFIED'] , PAGE_THEME);
                    else
                        redirect($tpl, $themeInfo['redirect']['THEME_MODIFED_ERROR'], PAGE_THEME);
                }
            }
            else
                redirect($tpl, $themeInfo['redirect']['INVALID_URL'], PAGE_THEME);
        
        break;
        case 'delete':
        //################################ DELETE THEME ###############################//
        
            if ($dbclass->db_checkRows("SELECT themeid FROM ".TBL_THEMES." WHERE themeid = '".(int)$_GET['themeid']."'"))
            {
                $themeCount = $dbclass->db_numrows($dbclass->db_query("SELECT themeid FROM ".TBL_THEMES));
                if ($themeCount != 1)
                {
                    
                    $dbclass->db_query("DELETE FROM ".TBL_THEMES." 
                                        WHERE themeid = '".(int)$_GET['themeid']."'
                                        ");
                                        
                    if ($dbclass->db_affectedrows() === 1)
                        redirect($tpl, $themeInfo['redirect']['THEME_DELETED'], PAGE_THEME);
                    else
                        redirect($tpl, $themeInfo['redirect']['THEME_DELETED_ERROR'], PAGE_THEME);
                }
                else
                    redirect($tpl, $themeInfo['redirect']['THEME_DELETED_ERROR'], PAGE_THEME); // cannot delete only 1 theme left
            }
            else
                redirect($tpl, $themeInfo['redirect']['INVALID_URL'], PAGE_THEME);
        
        break;
        default:
            redirect($tpl, $themeInfo['redirect']['INVALID_URL'], PAGE_THEME);
        break;
            
        }
        
    }
    
}

?>
