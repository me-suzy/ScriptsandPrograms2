<?php

/*========================================================*\
||########################################################||
||#                                                      #||
||#     WB News v1.0.0                                   #||
||# ---------------------------------------------------- #||
||#     Copyright (c) 2004-2005                          #||
||#     Created: 16th August 2005                        #||
||#     Filename: update.php                             #||
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
else if (!admin_permissions($dbclass, PAGE_UPDATE, (isset($_GET['action']) ? $_GET['action'] : "")))
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
    // open connection to Update Service
    $fp = fsockopen("www.webmobo.com", 80, $errno, $errstr, 10);
    if (!$fp)
        die("$errstr ($errno)<br />\n");
        
    // send headers out
    $out = "GET /updates/update_service.php HTTP/1.0\r\n";
    $out .= "Host: www.webmobo.com\r\n";
    $out .= "User-Agent: WBNews Update\r\n";
    $out .= "Connection: Close\r\n\r\n";    
    fwrite($fp, $out);

    $string = "";
    while (!feof($fp))
        $string .= fgets($fp, 1024);
    
    $string = trim(substr($string, strpos($string, "<?xml"))); // remove headers
    include "./wbxmlUpdate.php";
    
    // initate Update Service Class
    $updateService = new wbxmlUpdate("application");
    $updateService->parseXML($string); // parse XML
    
    if ($updateService->getVersion() > $config['version'])
    {
        
        $version = array(
                        "version" => $updateService->getVersion(),
                        "features" => implode(LINE_BREAK, $updateService->getFeatures()),
                        "requirements" => implode(LINE_BREAK, $updateService->getRequirements()),
                        "infoaddr" => $updateService->getInfoLink(),
                        "downloadaddr" => $updateService->getDownloadLink()
                        );
                        
        $contents['content'] = $tpl->replace($tpl->getTemplate('update_newversion'), $version);
        
    }
    else
        $contents['content'] = $tpl->getTemplate('update_versionok');
    
    /*
        Add normal Array $contents + required Arrays such as Theme, User Info
    */
    $contents = array_merge($contents, $GLOBAL, array("MENU_SECTION" => getMenuSections($dbclass, $tpl), "MENU" => getMenu($dbclass, $tpl)));
    
    $tpl->displayTemplate($tpl->replace($tpl->getTemplate("update_main"), $contents));
    
}
    
?>
