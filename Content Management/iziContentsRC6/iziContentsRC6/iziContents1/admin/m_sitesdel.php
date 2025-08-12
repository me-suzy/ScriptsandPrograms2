<?php

/***************************************************************************

 m_sitesdel.php
 ---------------
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

$GLOBALS["form"] = 'sites';
$validaccess = VerifyAdminLogin();


if ($GLOBALS["candelete"] == False)
{
   Header("Location: ".BuildLink('adminlogin.php'));
}
else
{
   DeleteSite();
   Header("Location: ".BuildLink('m_sites.php')."&page=".$_GET["page"]."&sort=".$_GET["sort"]);
}


function DeleteSite()
{
   global $_GET;

   $sqlString = "DROP TABLE ".$_GET["SiteCode"]."ratings";
   $result = dbExecute($sqlString,true);

   $sqlString = "DROP TABLE ".$_GET["SiteCode"]."contents";
   $result = dbExecute($sqlString,true);

   $sqlString = "DROP TABLE ".$_GET["SiteCode"]."subgroups";
   $result = dbExecute($sqlString,true);

   $sqlString = "DROP TABLE ".$_GET["SiteCode"]."groups";
   $result = dbExecute($sqlString,true);

   $sqlString = "DROP TABLE ".$_GET["SiteCode"]."topgroups";
   $result = dbExecute($sqlString,true);

   $sqlString = "DROP TABLE ".$_GET["SiteCode"]."banners";
   $result = dbExecute($sqlString,true);

   $sqlString = "SELECT * FROM ".$GLOBALS["eztbSpecialcontents"];
   $lresult = dbRetrieve($sqlString,true,0,0);
   while ($rs = dbFetch($lresult))
   {
      $tablename = $_GET["SiteCode"].$rs["scdb"];
      $sqlString = "DROP TABLE ".$tablename;
      $result = dbExecute($sqlString,true);

      $tablename = $_GET["SiteCode"].$rs["scdb"].'categories';
      $sqlString = "DROP TABLE ".$tablename;
      $result = dbExecute($sqlString,true);
   }
   dbFreeResult($lresult);

   $sqlString = "DROP TABLE ".$_GET["SiteCode"]."specialcontents";
   $result = dbExecute($sqlString,true);

   $sqlString = "DROP TABLE ".$_GET["SiteCode"]."themes";
   $result = dbExecute($sqlString,true);

   $sqlString = "DROP TABLE ".$_GET["SiteCode"]."userdata";
   $result = dbExecute($sqlString,true);

   $sqlString = "DROP TABLE ".$_GET["SiteCode"]."authors";
   $result = dbExecute($sqlString,false);

   $sqlString = "DROP TABLE ".$_GET["SiteCode"]."settings";
   $result = dbExecute($sqlString,true);

   $sqlString = "DROP TABLE ".$_GET["SiteCode"]."modules";
   $result = dbExecute($sqlString,true);

   $strQuery = "DELETE FROM ".$GLOBALS["eztbSites"]." WHERE sitecode='".$_GET["SiteCode"]."'";
   $result = dbExecute($strQuery,true);
   dbCommit();
} // function DeleteTag()

?>
