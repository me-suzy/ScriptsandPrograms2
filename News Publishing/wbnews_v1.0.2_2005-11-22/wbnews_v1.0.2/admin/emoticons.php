<?php

/*========================================================*\
||########################################################||
||#                                                      #||
||#     WB News v1.0.0                                   #||
||# ---------------------------------------------------- #||
||#     Copyright (c) 2004-2005                          #||
||#     Created: 18th August 2005                        #||
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
else if (!admin_permissions($dbclass, PAGE_EMOTICON, (isset($_GET['action']) ? $_GET['action'] : "")))
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
        
        //############################### EMOTICON LIST ###############################//
        
        $getEmoticons = $dbclass->db_query("SELECT * 
                                            FROM ".TBL_EMOTICON
                                            );
                      
        $contents['emoticon-list'] = '';
        if ($dbclass->db_numrows($getEmoticons))
        {
            
            $i = 0;
            while ($emoticons = $dbclass->db_fetcharray($getEmoticons))
            {
                $emoticons['alternate-rows'] = (($i % 2) == 0) ? $themeInfo['alternate_color1'] : $themeInfo['alternate_color2'];
                $contents['emoticon-list'] .= $tpl->replace($tpl->getTemplate('emoticonlist'), $emoticons);
                $i++;
            }
        }
        else
            $contents['emoticon-list'] = $themeInfo['norecords']['emoticons'];
        
        $contents = array_merge($contents, $GLOBAL, array("MENU_SECTION" => getMenuSections($dbclass, $tpl), "MENU" => getMenu($dbclass, $tpl)));
        $tpl->displayTemplate($tpl->replace($tpl->getTemplate('emoticonlist_body'), $contents));
        
    }
    else
    {
        
        switch ($_GET['action'])
        {
        
        case "add":
        
        //############################### EMOTICON ADD ################################//
        
            $showForm = true;
            if (isset($_POST['emoticon_submit']))
            {
                // process form
                if (!defined("LIB_FORMVAL"))
                {
                    include "../includes/lib/formvalidation.php";
                    $formVal = new formVal();
                }
                
                $formVal->checkEmpty($_POST['name'], "Name", 2);
                $formVal->checkEmpty($_POST['code'], "Code", 1);
                $formVal->checkEmpty($_POST['image_name'], "Image", 2);
                
                if ($dbclass->db_checkRows("SELECT name FROM ".TBL_EMOTICON." WHERE name = '".addslashes($_POST['name'])."'"))
                    $formVal->addError("Name Already Exists");
                    
                if ($dbclass->db_checkRows("SELECT code FROM ".TBL_EMOTICON." WHERE code = '".addslashes($_POST['code'])."'"))
                    $formVal->addError("Code Already Exists");
                
                if (sizeof($formVal->errors) != 0)
                    $error = $formVal->displayErrors();
                else
                    $showForm = false;
            }
            
            if ($showForm === true)
            {
                
                $contents = array(
                                "error" => (isset($error) ? $error : ""),
                                "FORM_NAME" => $tpl->textinput("name", (isset($_POST['name']) ? $_POST['name'] : "") ),
                                "FORM_CODE" => $tpl->textinput("code", (isset($_POST['code']) ? $_POST['code'] : "") ),
                                "FORM_IMAGE" => $tpl->textinput("image_name", (isset($_POST['image_name']) ? $_POST['image_name'] : "") ),
                                "action" => ucfirst($_GET['action'])
                                );
        
                $contents = array_merge($contents, $GLOBAL, array("MENU_SECTION" => getMenuSections($dbclass, $tpl), "MENU" => getMenu($dbclass, $tpl)));
                $tpl->displayTemplate($tpl->replace($tpl->getTemplate('emoticon-form'), $contents));
                
            }
            else
            {
                
                $dbclass->db_query("INSERT INTO ".TBL_EMOTICON."
                                    (id, name, code, image)
                                    VALUES ('null', '" . addslashes($_POST['name']) . "', '" . addslashes($_POST['code']) . "', 
                                    '" .addslashes($_POST['image_name']) . "')
                                    ");
                
                if ($dbclass->db_affectedrows() === 1)
                    redirect($tpl, $themeInfo['redirect']['EMOTICON_ADDED'] , PAGE_EMOTICON);
                else
                    redirect($tpl, $themeInfo['redirect']['EMOTICON_ADDED_ERROR'], PAGE_EMOTICON);
                
            }
        
        break;
        case "modify":
        //############################## EMOTICON MODIFY ##############################//
        
            if (isset($_GET['id']))
            {
                
                $emoticons = $dbclass->db_fetcharray($dbclass->db_query("SELECT * FROM ".TBL_EMOTICON." WHERE id = '".(int)$_GET['id']."'"));
                
                $showForm = true;
                if (isset($_POST['emoticon_submit']))
                {
                    // process form
                    if (!defined("LIB_FORMVAL"))
                    {
                        include "../includes/lib/formvalidation.php";
                        $formVal = new formVal();
                    }
                    
                    $formVal->checkEmpty($_POST['name'], "Name", 2);
                    $formVal->checkEmpty($_POST['code'], "Code", 1);
                    $formVal->checkEmpty($_POST['image_name'], "Image", 2);
                
                    if ($dbclass->db_checkRows("SELECT name FROM ".TBL_EMOTICON." WHERE name = '".addslashes($_POST['name'])."' AND id != '" . (int)$_GET['id'] . "'"))
                        $formVal->addError("Name Already Exists");
                    
                    if ($dbclass->db_checkRows("SELECT code FROM ".TBL_EMOTICON." WHERE code = '".addslashes($_POST['code'])."' AND id != '" . (int)$_GET['id'] . "'"))
                        $formVal->addError("Code Already Exists");
                    
                    if (sizeof($formVal->errors) != 0)
                        $error = $formVal->displayErrors();
                    else
                        $showForm = false;
                }
            
                if ($showForm === true)
                {
                    $contents = array(
                                    "error" => (isset($error) ? $error : ""),
                                    "FORM_NAME" => $tpl->textinput("name", (isset($_POST['name']) ? $_POST['name'] : $emoticons['name']) ),
                                    "FORM_CODE" => $tpl->textinput("code", (isset($_POST['code']) ? $_POST['code'] : $emoticons['code']) ),
                                    "FORM_IMAGE" => $tpl->textinput("image_name", (isset($_POST['image_name']) ? $_POST['image_name'] : $emoticons['image']) ),
                                    "action" => ucfirst($_GET['action'])
                                    );
        
                    $contents = array_merge($contents, $GLOBAL, array("MENU_SECTION" => getMenuSections($dbclass, $tpl), "MENU" => getMenu($dbclass, $tpl)));
                    $tpl->displayTemplate($tpl->replace($tpl->getTemplate('emoticon-form'), $contents));
                }
                else
                {
                    
                    $dbclass->db_query("UPDATE ".TBL_EMOTICON." SET
                                        name = '" . addslashes($_POST['name']) . "',
                                        code = '" . addslashes($_POST['code']) . "',
                                        image = '" .addslashes($_POST['image_name']) . "',
                                        WHERE id = '" . (int)$_GET['id'] . "'
                                        ");
                    
                    if ($dbclass->db_affectedrows() === 1)
                        redirect($tpl, $themeInfo['redirect']['EMOTICON_MODIFIED'], PAGE_EMOTICON);
                    else
                        redirect($tpl, $themeInfo['redirect']['EMOTICON_MODIFIED_ERROR'], PAGE_EMOTICON);
                    
                }
                
            }
            else
                redirect($tpl, $themeInfo['redirect']['INVALID_URL'], PAGE_EMOTICON);
        
        break;
        case "delete":
        //############################## EMOTICON DELETE ##############################//
            
            if (isset($_GET['catid']))
            {
                $dbclass->db_query("DELETE FROM ".TBL_EMOTICON." WHERE id = '".(int)$_GET['id']."'");
                if ($dbclass->db_affectedrows() === 1)
                    redirect($tpl, $themeInfo['redirect']['EMOTICON_DELETED'], PAGE_EMOTICON);
                else
                    redirect($tpl, $themeInfo['redirect']['EMOTICON_DELETED_ERROR'], PAGE_EMOTICON);
            }
            else
                redirect($tpl, $themeInfo['redirect']['INVALID_URL'], PAGE_EMOTICON);
            
        break;
        default:
            redirect($tpl, $themeInfo['redirect']['INVALID_URL'], PAGE_DB);
        break;
        
        }
        
    }
    
}


?>
