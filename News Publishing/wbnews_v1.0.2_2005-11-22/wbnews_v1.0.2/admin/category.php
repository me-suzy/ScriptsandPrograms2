<?php

/*========================================================*\
||########################################################||
||#                                                      #||
||#     WB News v1.0.0                                   #||
||# ---------------------------------------------------- #||
||#     Copyright (c) 2004-2005                          #||
||#     Created: 19th August 2005                        #||
||#     Filename: category.php                           #||
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
else if (!admin_permissions($dbclass, PAGE_CAT, (isset($_GET['action']) ? $_GET['action'] : "")))
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
        //############################### CATEGORY LIST ###############################//
        
        $getCategories = $dbclass->db_query("SELECT * FROM ".TBL_CATEGORY);
        
        $contents['catlist'] = '';
        if ($dbclass->db_numrows($getCategories))
        {
            $i = 0;
            while ($category = $dbclass->db_fetcharray($getCategories))
            {
                $category['alternate-rows'] = (($i % 2) == 0) ? $themeInfo['alternate_color1'] : $themeInfo['alternate_color2'];
                
                if (!empty($category['avatar_name']))
                    $category['image'] = $tpl->replace($themeInfo['template']['categorylist_image'], array("name" => $category['name'], "image" => "../avatar/".$category['avatar_name']));
                else if (!empty($category['avatar_url']))
                    $category['image'] = $tpl->replace($themeInfo['template']['categorylist_image'], array("name" => $category['name'], "image" => $category['avatar_url']));
                else
                    $category['image'] = '';
                
                $contents['catlist'] .= $tpl->replace($tpl->getTemplate('category_list'), $category);
                $i++;
            }
        }
        else
            $contents['catlist'] = $themeInfo['norecords']['categories'];
        
        $contents = array_merge($contents, $GLOBAL, array("MENU_SECTION" => getMenuSections($dbclass, $tpl), "MENU" => getMenu($dbclass, $tpl)));
        $tpl->displayTemplate($tpl->replace($tpl->getTemplate('categorylist_body'), $contents));
    }
    else
    {
        
        switch ($_GET['action'])
        {
        
        case "add":
        //############################### CATEGORY ADD ################################//
        
            $showForm = true;
            if (isset($_POST['submit']))
            {
                // process form
                if (!defined("LIB_FORMVAL"))
                {
                    include "../includes/lib/formvalidation.php";
                    $formVal = new formVal();
                }
                
                $formVal->checkEmpty($_POST['name'], "Name", 2);
                ($dbclass->db_checkRows("SELECT name FROM ".TBL_CATEGORY." WHERE name = '".htmlentities($_POST['name'])."'") ? 
                                       $formVal->addError("Category Name already in use") : 
                                       "");
                                                        
                if (sizeof($formVal->errors) != 0)
                    $error = $formVal->displayErrors();
                else
                    $showForm = false;
                
            }
            
            if ($showForm === true)
            {
                
                
                $contents = array(
                                  "action" => ucfirst($_GET['action']),
                                  "actionURL" => $_GET['action'],
                                  "error" => (isset($error) ? $error : ""),
                                  "name" => $tpl->textinput("name", (isset($_POST['name']) ? $_POST['name'] : "")),
                                  "avatar_name" => $tpl->textinput("avatar_name", (isset($_POST['avatar_name']) ? $_POST['avatar_name'] : "")),
                                  "avatar_url" => $tpl->textinput("avatar_url", (isset($_POST['avatar_url']) ? $_POST['avatar_url'] : ""))
                                  );
    
                /*
                    Add normal Array $contents + required Arrays such as Theme, User Info
                */
                $contents = array_merge($contents, $GLOBAL, array("MENU_SECTION" => getMenuSections($dbclass, $tpl), "MENU" => getMenu($dbclass, $tpl)));
                $tpl->displayTemplate($tpl->replace($tpl->getTemplate('categoryform_body'), $contents));
                
            }
            else
            {
                
                $dbclass->db_query("INSERT INTO ".TBL_CATEGORY."
                                   (id, name, avatar_name, avatar_url)
                                   VALUES ('null', '".addslashes(htmlentities($_POST['name']))."', '".addslashes(htmlentities($_POST['avatar_name']))."', '".addslashes(htmlentities($_POST['avatar_url']))."')
                                   ");
                                   
                if ($dbclass->db_affectedrows() === 1)
                    redirect($tpl, $themeInfo['redirect']['CATEGORY_ADDED'], PAGE_CAT);
                else
                    redirect($tpl, $themeInfo['redirect']['CATEGORY_ADDED_ERROR'], PAGE_CAT);
                
            }
        
        break;
        case "modify":
        //############################## CATEGORY MODIFY ##############################//
        
            if (isset($_GET['catid']))
            {
                
                $category = $dbclass->db_fetchall("SELECT * FROM ".TBL_CATEGORY." WHERE id = '".(int)$_GET['catid']."'");
                
                $showForm = true;
                if (isset($_POST['submit']))
                {
                    // process form
                    if (!defined("LIB_FORMVAL"))
                    {
                        include "../includes/lib/formvalidation.php";
                        $formVal = new formVal();
                    }
                
                    $formVal->checkEmpty($_POST['name'], "Name", 2);
                    ($dbclass->db_checkRows("SELECT name FROM ".TBL_CATEGORY." WHERE name = '".htmlentities($_POST['name'])."' 
                                            AND id != '".(int)$_GET['catid']."'") 
                                            ? $formVal->addError("Category Name already in use") : 
                                            "");
                                                        
                    if (sizeof($formVal->errors) != 0)
                        $error = $formVal->displayErrors();
                    else
                        $showForm = false;
                }
            
                if ($showForm === true)
                {
                    $contents = array(
                                    "action" => ucfirst($_GET['action']),
                                    "actionURL" => $_GET['action']."&amp;catid=".$_GET['catid'],
                                    "error" => (isset($error) ? $error : ""),
                                    "name" => $tpl->textinput("name", (isset($_POST['name']) ? $_POST['name'] : $category[0]['name'])),
                                    "avatar_name" => $tpl->textinput("avatar_name", (isset($_POST['avatar_name']) ? $_POST['avatar_name'] : $category[0]['avatar_name'])),
                                    "avatar_url" => $tpl->textinput("avatar_url", (isset($_POST['avatar_url']) ? $_POST['avatar_url'] : $category[0]['avatar_url']))
                                    );
    
                    /*
                        Add normal Array $contents + required Arrays such as Theme, User Info
                    */
                    $contents = array_merge($contents, $GLOBAL, array("MENU_SECTION" => getMenuSections($dbclass, $tpl), "MENU" => getMenu($dbclass, $tpl)));
                    $tpl->displayTemplate($tpl->replace($tpl->getTemplate('categoryform_body'), $contents));
                }
                else
                {
                    
                    $dbclass->db_query("UPDATE ".TBL_CATEGORY." SET
                                        name = '".addslashes(htmlentities($_POST['name']))."',
                                        avatar_name = '".addslashes(htmlentities($_POST['avatar_name']))."',
                                        avatar_url = '".addslashes(htmlentities($_POST['avatar_url']))."'
                                        WHERE id = '".(int)$_GET['catid']."'
                                        ");
                                        
                    if ($dbclass->db_affectedrows() === 1)
                        redirect($tpl, $themeInfo['redirect']['CATEGORY_MODIFIED'], PAGE_CAT);
                    else
                        redirect($tpl, $themeInfo['redirect']['CATEGORY_MODIFIED_ERROR'], PAGE_CAT);
                }
                
            }
            else
                redirect($tpl, $themeInfo['redirect']['INVALID_URL'], PAGE_CAT);
        
        break;
        case "delete":
        //############################## CATEGORY DELETE ##############################//
            
            if (isset($_GET['catid']))
            {
                $dbclass->db_query("DELETE FROM ".TBL_CATEGORY." WHERE id = '".(int)$_GET['catid']."'");
                if ($dbclass->db_affectedrows() === 1)
                    redirect($tpl, $themeInfo['redirect']['CATEGORY_DELETED'], PAGE_CAT);
                else
                    redirect($tpl, $themeInfo['redirect']['CATEGORY_DELETED_ERROR'], PAGE_CAT);
            }
            else
                redirect($tpl, $themeInfo['redirect']['INVALID_URL'], PAGE_CAT);
            
        break;
        default:
            redirect($tpl, $themeInfo['redirect']['INVALID_URL'], PAGE_CAT);
        break;
        }
        
    }
    
}

?>
