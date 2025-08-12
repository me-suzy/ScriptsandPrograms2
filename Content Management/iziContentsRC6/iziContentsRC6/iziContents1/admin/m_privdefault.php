<?php

/***************************************************************************

 m_privdefault.php
 ------------------
 copyright : (C) 2005 - The iziContents Development Team

 iziContents version : 1.0
 fileversion : 1.0.1
 change date : 23 - 04 - 2005
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

$GLOBALS["form"] = 'privileges';
$validaccess = VerifyAdminLogin();


if ($GLOBALS["canedit"] == False)
{
   Header("Location: ".BuildLink('adminlogin.php'));
} else {
   if (sGetPrivGroup() == "")
   {
      $strQuery = "INSERT INTO ".$GLOBALS["eztbSettings"]." VALUES('privdefault','".$_GET["usergroupname"]."')";
   } else {
      $strQuery = "UPDATE ".$GLOBALS["eztbSettings"]." SET settingvalue='".$_GET["usergroupname"]."' WHERE settingname='privdefault'";
   }

   $result = dbExecute($strQuery,true);
   dbCommit();

   Header("Location: ".BuildLink('m_privileges.php'));
}


function sGetPrivGroup()
{
   $strQuery = "SELECT settingvalue FROM ".$GLOBALS["eztbSettings"]." WHERE settingname='privdefault'";
   $result = dbRetrieve($strQuery,true,0,0);
   $rs     = dbFetch($result);
   $groupname = $rs["settingvalue"];

   dbFreeResult($result);
   return $groupname;
} // function sGetHomepageGroup()

?>

