<?php

/*========================================================*\
||########################################################||
||#                                                      #||
||#     WB News v1.0.0                                   #||
||# ---------------------------------------------------- #||
||#     Copyright (c) 2004-2005                          #||
||#     Created: 19th August 2005                        #||
||#     Filename: database.php                           #||
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
else if (!admin_permissions($dbclass, PAGE_DB, (isset($_GET['action']) ? $_GET['action'] : "")))
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
        
        $tableStatus = $dbclass->db_fetchall("SHOW TABLE STATUS");
        $tableStatusSize = sizeof($tableStatus);
        $tbls = array();
        
        for ($i = 0; $i < $tableStatusSize; $i++)
            if ($tableStatus[$i]['Data_free'] != 0)
                $tbls[] = $tableStatus[$i];
        
        $tblSize = sizeof($tbls);
        $contents['database_list'] = "";
        if ($tblSize != 0)
        {
            for ($i = 0; $i < $tblSize; $i++)
            {
                $tbls[$i]['alternate-rows'] = (($i % 2) == 0) ? $themeInfo['alternate_color1'] : $themeInfo['alternate_color2'];
                $contents['database_list'] .= $tpl->replace($tpl->getTemplate('databaseoption_list'), $tbls[$i]);
            }
        }
        else
            $contents['database_list'] = $themeInfo['norecords']['database'];
        
        $contents = array_merge($contents, $GLOBAL, array("MENU_SECTION" => getMenuSections($dbclass, $tpl), "MENU" => getMenu($dbclass, $tpl)));
        $tpl->displayTemplate($tpl->replace($tpl->getTemplate('databaseoption_body'), $contents));
        
    }
    else
    {
        
        switch ($_GET['action'])
        {
            
        case 'backup':
        
            if (isset($_POST['dboption_submit']))
            {
                
                //get format
                include "./backup.php";
                if ($_POST['format'] === 'sql')
                {
                    include "sqlBackup.php";
                    $format = new sqlBackup($config);
                }
                else
                {
                    include "xmlBackup.php";
                    $format = new xmlBackup($config);
                }
                
                if (isset($_POST['download']) && $_POST['download'] == "yes")
                {
                    // send headers out
                    if ($format->backupFormat() == "xml")
                        header('Content-Type: text/xml');
                    else
                        header('Content-Type: application/octetstream');
                    header("Content-Type: application/force-download");
                    header("Pragma: public");
                    header("Expires: 0");
                    header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
                    header('Content-Disposition: inline; filename="backup-'.date("Y-m-d").'.'.$format->backupFormat().'"');
                    echo $format->toString();
                }
                else
                {
                    
                    $contents = array(
                                      "format" => strtoupper($_POST['format']),
                                      "code" => $format->toString()
                                     );
                    
                    $contents = array_merge($contents, $GLOBAL, array("MENU_SECTION" => getMenuSections($dbclass, $tpl), "MENU" => getMenu($dbclass, $tpl)));
                    $tpl->displayTemplate($tpl->replace($tpl->getTemplate('databasebackup_body'), $contents));
                }
                
            }
            else
                redirect($tpl, $themeInfo['redirect']['INVALID_URL'], PAGE_DB);
        
        break;
        case 'optimize':
        
            if (isset($_GET['table']))
            {
                
                $queryOptimize = $dbclass->db_query("OPTIMIZE TABLE ".$_GET['table']);
                $result = $dbclass->db_fetcharray($queryOptimize);
                
                if ($result['Msg_type'] == "error")
                {
                }
                else
                    redirect($tpl, $themeInfo['redirect']['DATABASE_OPTIMIZE'], PAGE_DB);
                
            }
            else
            {
                
                $tableStatus = $dbclass->db_fetchall("SHOW TABLE STATUS");
                $tableStatusSize = sizeof($tableStatus);
                $tbls = array();
        
                for ($i = 0; $i < $tableStatusSize; $i++)
                    if ($tableStatus[$i]['Data_free'] != 0)
                        $tbls[] = $tableStatus[$i];
                        
                $tblSize = sizeof($tbls);
                
                if ($tblSize != 0)
                {
                    for ($i = 0; $i < $tblSize; $i++)
                        $dbclass->db_query("OPTIMIZE TABLE ".$tbls[$i]['Name']);
                    
                    redirect($tpl, $themeInfo['redirect']['DATABASE_OPTIMIZE'], PAGE_DB);
                }
                else
                    redirect($tpl, $themeInfo['redirect']['INVALID_URL'], PAGE_DB);
                
            }
        
        break;
        default:
            redirect($tpl, $themeInfo['redirect']['INVALID_URL'], PAGE_DB);
        break;
            
        }
        
    }
    
}

?>
