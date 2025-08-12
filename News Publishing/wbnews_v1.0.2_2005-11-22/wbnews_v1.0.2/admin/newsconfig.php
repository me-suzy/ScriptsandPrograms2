<?php

/*========================================================*\
||########################################################||
||#                                                      #||
||#     WB News v1.0.0                                   #||
||# ---------------------------------------------------- #||
||#     Copyright (c) 2004-2005                          #||
||#     Created: 16th August 2005                        #||
||#     Filename: newsconfig.php                         #||
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
else if (!admin_permissions($dbclass, PAGE_CONFIG, (isset($_GET['action']) ? $_GET['action'] : "")))
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

    if (!isset($_POST['config_submit']))
    {
        // get sections
        $sections = $dbclass->db_fetchall("SELECT *, REPLACE(`section` ,' ','-') AS section_id
                                           FROM ".TBL_ADMINSECTIONS." 
                                           ORDER BY displayorder ASC
                                           ");
                                           
        // get configuration
        $configuration = $dbclass->db_fetchall("SELECT * 
                                                FROM ".TBL_NEWSCONFIG."
                                                ORDER BY displayorder ASC
                                                ");
                                            
        $numSections = sizeof($sections);
        $numConfig = sizeof($configuration);
    
        $contents['configuration'] = '';
        for ($i = 0; $i < $numSections; $i++)
        {
            $sections[$i]['config'] = '';
            $b = 0;
            for ($j = 0; $j < $numConfig; $j++)
            {
                // check if the configuration is with the correct section
                if ($sections[$i]['sectionid'] == $configuration[$j]['sectionid'])
                {
                    $configuration[$j]['alternate-rows'] = (($b % 2) == 0) ? $themeInfo['alternate_color1'] : $themeInfo['alternate_color2'];
                    
                    // form elements
                    $option = explode("_", $configuration[$j]['option']);
                    switch ($option[0])
                    {
                    
                    case 'select':
                    $configuration[$j]['option'] = $tpl->dropdown($configuration[$j]['var'], $option[1](), $configuration[$j]['value']);
                    break;
                    case 'textarea':
                        $configuration[$j]['option'] = $tpl->textarea($configuration[$j]['var'], $configuration[$j]['value']);
                    break;
                    case 'yesno':
                        $configuration[$j]['option'] = $tpl->yesno($configuration[$j]['var'], LINE_BREAK, $configuration[$j]['value']);
                    break;
                    default:
                        $configuration[$j]['option'] = $tpl->textinput($configuration[$j]['var'], $configuration[$j]['value']);
                    break;
                    
                    }
                    
                    $sections[$i]['config'] .= $tpl->replace($tpl->getTemplate('newsconfig_config'), $configuration[$j]);
                    $b++;
                }
            }
            
            $contents['configuration'] .= $tpl->replace($tpl->getTemplate('newsconfig_section'), $sections[$i]);
        }
    
        $contents = array_merge($contents, $GLOBAL, array("MENU_SECTION" => getMenuSections($dbclass, $tpl), "MENU" => getMenu($dbclass, $tpl)));
    
        $tpl->displayTemplate($tpl->replace($tpl->getTemplate("newsconfig_body"), $contents));
        
    }
    else
    {
        unset($_POST['config_submit']);
        
        foreach ($_POST as $var => $value)
            $dbclass->db_query("UPDATE ".TBL_NEWSCONFIG." SET value = '".addslashes(htmlentities($value))."' WHERE var = '".addslashes(htmlentities($var))."'");
            
        redirect($tpl, $themeInfo['redirect']['NEWSCONFIG'], PAGE_CONFIG);
        
    }
    
}

?>
