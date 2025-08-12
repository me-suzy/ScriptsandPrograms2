<?php

/***************************************************************************

 m_subgroupsmove.php
 --------------------
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


$GLOBALS["form"] = 'subgroups';
$validaccess = VerifyAdminLogin();

if ($GLOBALS["canedit"] == True)
{
   ShuffleSubGroups();
   Header("Location: ".BuildLink('m_subgroups.php')."&page=".$_GET["page"]."&filtergroupname=".$_GET["filtergroupname"]);
}
else
{
   Header("Location: ".BuildLink('adminlogin.php'));
}


function ShuffleSubGroups()
{
   global $_GET;

   // get subgrouporderid for the group that we want to move....
   $strQuery = "SELECT groupname,subgrouporderid FROM ".$GLOBALS["eztbSubgroups"]." WHERE subgroupname='".$_GET["SubGroupName"]."' AND language='".$GLOBALS["gsDefault_language"]."'";
   $fromresult = dbRetrieve($strQuery,true,0,1);
   while ($rs = dbFetch($fromresult))
   {
      $groupname       = $rs["groupname"];
      $subgrouporderid = $rs["subgrouporderid"];

      // get subgrouporderid for the previous or next group in the list
      $newsubgrouporderid = '';
      if ($_GET["direction"] == 'up')
      {
         $strQuery = "SELECT subgroupname,subgrouporderid FROM ".$GLOBALS["eztbSubgroups"]." WHERE subgrouporderid<'".$subgrouporderid."' AND groupname='".$groupname."' AND language='".$GLOBALS["gsDefault_language"]."' ORDER BY subgrouporderid DESC";
      }
      else
      {
         $strQuery = "SELECT subgroupname,subgrouporderid FROM ".$GLOBALS["eztbSubgroups"]." WHERE subgrouporderid>'".$subgrouporderid."' AND groupname='".$groupname."' AND language='".$GLOBALS["gsDefault_language"]."' ORDER BY subgrouporderid";
      }
      $toresult = dbRetrieve($strQuery,true,0,1);
      while ($rs = dbFetch($toresult))
      {
         $newsubgroupname    = $rs["subgroupname"];
         $newsubgrouporderid = $rs["subgrouporderid"];
      }
      dbFreeResult($toresult);

      // Swap the two, as long as there was a next or previous record to swap with
      if ($newsubgrouporderid != '')
      {
         $strQuery = "UPDATE ".$GLOBALS["eztbSubgroups"]." SET subgrouporderid='".$subgrouporderid."' WHERE subgroupname='".$newsubgroupname."'";
         $result = dbExecute($strQuery,true);
         $strQuery = "UPDATE ".$GLOBALS["eztbSubgroups"]." SET subgrouporderid='".$newsubgrouporderid."' WHERE subgroupname='".$_GET["SubGroupName"]."'";
         $result = dbExecute($strQuery,true);
      }
   }
   dbFreeResult($fromresult);
   dbCommit();

} // function ShuffleSubGroups()

?>

