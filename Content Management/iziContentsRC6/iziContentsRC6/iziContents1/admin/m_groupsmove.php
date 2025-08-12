<?php

/***************************************************************************

 m_groupsmove.php
 -----------------
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

$GLOBALS["form"] = 'groups';
$validaccess = VerifyAdminLogin();


if ($GLOBALS["canedit"] == True)
{
   ShuffleGroups();
   Header("Location: ".BuildLink('m_groups.php')."&page=".$_GET["page"]."&sort=".$_GET["sort"]."&filtergroupname=".$_GET["filtergroupname"]);
}
else
{
   Header("Location: ".BuildLink('adminlogin.php'));
}


function ShuffleGroups()
{
   global $_GET;

   // get grouporderid for the group that we want to move....
   $strQuery = "SELECT topgroupname,grouporderid FROM ".$GLOBALS["eztbGroups"]." WHERE groupname='".$_GET["GroupName"]."' AND language='".$GLOBALS["gsDefault_language"]."'";
   $fromresult = dbRetrieve($strQuery,true,0,1);
   while ($rs = dbFetch($fromresult))
   {
      $topgroupname = $rs["topgroupname"];
      $grouporderid = $rs["grouporderid"];

      // get grouporderid for the previous or next group in the list
      $newgrouporderid = '';
      if ($topgroupname != '')
      {
         if ($_GET["direction"] == 'up')
         {
            $strQuery = "SELECT groupname,grouporderid FROM ".$GLOBALS["eztbGroups"]." WHERE grouporderid<'".$grouporderid."' AND topgroupname='".$topgroupname."' AND language='".$GLOBALS["gsDefault_language"]."' ORDER BY grouporderid DESC";
         }
         else
         {
            $strQuery = "SELECT groupname,grouporderid FROM ".$GLOBALS["eztbGroups"]." WHERE grouporderid>'".$grouporderid."' AND topgroupname='".$topgroupname."' AND language='".$GLOBALS["gsDefault_language"]."' ORDER BY grouporderid";
         }
      }
      else
      {
         if ($_GET["direction"] == 'up')
         {
            $strQuery = "SELECT groupname,grouporderid FROM ".$GLOBALS["eztbGroups"]." WHERE grouporderid<'".$grouporderid."' AND language='".$GLOBALS["gsDefault_language"]."' ORDER BY grouporderid DESC";
         }
         else
         {
            $strQuery = "SELECT groupname,grouporderid FROM ".$GLOBALS["eztbGroups"]." WHERE grouporderid>'".$grouporderid."' AND language='".$GLOBALS["gsDefault_language"]."' ORDER BY grouporderid";
         }
      }
      $toresult = dbRetrieve($strQuery,true,0,1);
      while ($rs = dbFetch($toresult))
      {
         $newgroupname    = $rs["groupname"];
         $newgrouporderid = $rs["grouporderid"];
      }
      dbFreeResult($toresult);

      // Swap the two, as long as there was a next or previous record to swap with
      if ($newgrouporderid != '')
      {
         $strQuery = "UPDATE ".$GLOBALS["eztbGroups"]." SET grouporderid='".$grouporderid."' WHERE groupname='".$newgroupname."'";
         $result = dbExecute($strQuery,true);
         $strQuery = "UPDATE ".$GLOBALS["eztbGroups"]." SET grouporderid='".$newgrouporderid."' WHERE groupname='".$_GET["GroupName"]."'";
         $result = dbExecute($strQuery,true);
      }
   }
   dbFreeResult($fromresult);
   dbCommit();

} // function ShuffleGroups()

?>

