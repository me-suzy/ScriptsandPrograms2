<?php

/***************************************************************************

 m_topgroupsmove.php
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

$GLOBALS["form"] = 'topgroups';
$validaccess = VerifyAdminLogin();

if ($GLOBALS["canedit"] == True) {
   ShuffleTopGroups();
   Header("Location: ".BuildLink('m_topgroups.php')."&page=".$_GET["page"]."&sort=".$_GET["sort"]);
} else {
   Header("Location: ".BuildLink('adminlogin.php'));
}


function ShuffleTopGroups()
{
   global $_GET;

   // get topgrouporderid for the topgroup that we want to move....
   $strQuery = "SELECT topgrouporderid FROM ".$GLOBALS["eztbTopgroups"]." WHERE topgroupname='".$_GET["TopGroupName"]."' AND language='".$GLOBALS["gsDefault_language"]."'";
   $fromresult = dbRetrieve($strQuery,true,0,1);
   while ($rs = dbFetch($fromresult)) {
      $topgrouporderid = $rs["topgrouporderid"];

      // get topgrouporderid for the next or previous topgroup in the list
      $newtopgrouporderid = '';
      if ($_GET["direction"] == 'up') {
         $strQuery = "SELECT topgrouporderid,topgroupname FROM ".$GLOBALS["eztbTopgroups"]." WHERE topgrouporderid<'".$topgrouporderid."' AND language='".$GLOBALS["gsDefault_language"]."' ORDER BY topgrouporderid DESC";
      } else {
         $strQuery = "SELECT topgrouporderid,topgroupname FROM ".$GLOBALS["eztbTopgroups"]." WHERE topgrouporderid>'".$topgrouporderid."' AND language='".$GLOBALS["gsDefault_language"]."' ORDER BY topgrouporderid";
      }
      $toresult = dbRetrieve($strQuery,true,0,1);
      while ($rs = dbFetch($toresult)) {
         $newtopgroupname    = $rs["topgroupname"];
         $newtopgrouporderid = $rs["topgrouporderid"];
      }
      dbFreeResult($toresult);

      // Swap the two, as long as there was a next or previous record to swap with
      if ($newtopgrouporderid != '') {
         $strQuery = "UPDATE ".$GLOBALS["eztbTopgroups"]." SET topgrouporderid='".$topgrouporderid."' WHERE topgroupname='".$newtopgroupname."'";
         $result = dbExecute($strQuery,true);
         $strQuery = "UPDATE ".$GLOBALS["eztbTopgroups"]." SET topgrouporderid='".$newtopgrouporderid."' WHERE topgroupname='".$_GET["TopGroupName"]."'";
         $result = dbExecute($strQuery,true);
      }
   }
   dbFreeResult($fromresult);
   dbCommit();

} // function ShuffleTopGroups()

?>

