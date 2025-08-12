<?php

/***************************************************************************

 m_subcontentdelete.php
 -----------------
 copyright : (C) 2005 - The iziContents Development Team

 iziContents version : 1.0
 fileversion : 1.0.1
 change date : 15 - 05 - 2005
 author:       sascha@izicontents.com 
 ***************************************************************************/

/***************************************************************************
 The iziContents Development Team offers no warranties on this script.
 The owner/licensee of the script is solely responsible for any problems
 caused by installation of the script or use of the script.

 All copyright notices regarding iziContents and ezContents must remain intact on the scripts and in the HTML for the scripts.

 For more info on iziContents,
 visit http://www.izicontents.com*/

/***************************************************************************
 *
 *   This program is free software; you can redistribute it and/or modify
 *   it under the terms of the License which can be found within the
 *   zipped package. Under the licence of GPL/GNU.
 *
 ***************************************************************************/

include_once ("rootdatapath.php");

$GLOBALS["form"] = 'subcontent';
$GLOBALS["validaccess"] = VerifyAdminLogin();

includeLanguageFiles('admin','subcontent');

// set modulespath
$mod_path = $GLOBALS["rootdp"].$GLOBALS["modules_home"];


force_page_refresh();
if($_GET["scname"] != ""){
$check = checkuninstall($mod_path); // checking uninstallation
specialContDelete($check);
Header("Location: ".BuildLink('m_subcontent.php')."&page=".$_POST["page"]);
}
if($_GET["dirname"] != ""){
deleteFolder($_GET["dirname"]);
Header("Location: ".BuildLink('m_subcontentform.php')."&page=".$_POST["page"]);
}


admhdr();
admintitle(4,"delete module");
if (isset($GLOBALS["strErrors"])) { formError(1); }

if($Status){
echo '<tr bgcolor=#07AC51><td><b><ul><li>Module deleted</ul></b></td></tr></table></body>';
}




function specialContDelete($Status){
global $_SERVER, $_GET;

    if($Status){
        // delete modules from Specialcontentsd
        $strQuery = "DELETE FROM ".$GLOBALS["eztbSpecialcontents"]." WHERE scname ='".$_GET["scname"]."'";
        $result = dbExecute($strQuery,true);
                
        // delete modules from modules
        $strQuery2 = "DELETE FROM ".$GLOBALS["eztbModules"]." WHERE moduledirectory ='".$_GET["scname"]."'";
        $result = dbExecute($strQuery2,true);
               
        // delete Modules from modulesettings
        $strQuery3 = "DELETE FROM ".$GLOBALS["eztbModuleSettings"]." WHERE modulename ='".$_GET["scname"]."'";
        $result = dbExecute($strQuery3,true);
           
        
     }
     else{$GLOBALS["strErrors"][] = "Error on deinstallation";
         
     }
     
        
}

# This function checks the uninstall.sql
function checkuninstall($mod_path){
global $_SERVER, $_GET;
            
            
            $Module = $_GET["scname"];
            $Status = True;
         
        # checking for the mod.inc.php for settings 
        if($Module != ""){
             //$fp = fopen($mod_path.$Module.'/mod.inc.php', "r"); // opening setting file
               
             //if(!$fp){ echo "Unable to open $Module setting-file";
                         
             //}
             
             if($Status){             
             //include($mod_path.$Module."/mod.inc.php"); // include mod.inc.php to get uninstall-filename
             $un_sql = $mod_path.$Module."/uninstall.sql"; // setting path to uninstall.sql
                          
             $fp2 = fopen($un_sql, "r"); // opening uninstall.sql if exists
               if($fp2){
                  $ufile =  fread($fp2, filesize($un_sql));
	          $ufile = str_replace("\r", "", $ufile);
                  $query = explode(";\n",$ufile);
                                
                      for ($i=0; $i < count($query) - 1; $i++) {
                          $sqlQuery = trim($query[$i]);
                          $workquery = explode("\n",$sqlQuery);
                              for ($j=0; $j < count($workquery) - 1; $j++) {
                                   $test_workquery = trim($workquery[$j]);
                                       if (substr($test_workquery,0,1) == '#') { $workquery[$j] = ''; }
                                }
                           $sqlQuery = implode("",$workquery);
                           if (substr($sqlQuery,0,4) == 'DROP') {
                              $sqlQuery = str_replace('DROP TABLE IF EXISTS ','DROP TABLE IF EXISTS '.$GLOBALS["DBPrefix"], $sqlQuery); // adding table_prefix
                           }
                           if ($GLOBALS["Log"] == 'Y') { dbWriteLog($sqlQuery); } // Logging Query
                               $result = dbExecute($sqlQuery,true);
                           if (!$result) {$GLOBALS["strErrors"][] = "Errors in uninstall.sql for module".$Module;
                            $Status = False; }
                       }
                    $fp2 = fclose($fp2);  
                       
                }
                elseif(!$fp2){ 
                    $GLOBALS["strErrors"][] = 'No '.$un_sql;
                        
                 }
          }
          }
        
        return $Status;
}

function deleteFolder($folder){
global $mod_path;


$dirname = $mod_path.$folder;

    // Sanity check
    if (!file_exists($dirname)) {
        return false;
    }
 
    // Simple delete for a file
    if (is_file($dirname)) {
        return unlink($dirname);
    }
 
    // Loop through the folder
    $dir = dir($dirname);
    while (false !== $entry = $dir->read()) {
        // Skip pointers
        if ($entry == '.' || $entry == '..') {
            continue;
        }
 
        // Recurse
        deleteFolder("$dirname/$entry");
    }
 
    // Clean up
    $dir->close();
    return rmdir($dirname);
}
?>