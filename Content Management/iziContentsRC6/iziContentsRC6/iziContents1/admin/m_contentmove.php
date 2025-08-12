<?php

/***************************************************************************

 m_contentmove.php
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

$GLOBALS["form"] = 'content';
$validaccess = VerifyAdminLogin();


if ($GLOBALS["canedit"] == True)
{
   ShuffleContents();
   Header("Location: ".BuildLink('m_content.php')."&page=".$_GET["page"]."&filtergroupname=".$_GET["filtergroupname"]);
}
else
{
   Header("Location: ".BuildLink('adminlogin.php'));
}


function ShuffleContents()
{
   global $_GET;

   // get orderid for the content item that we want to move....
   $strQuery = "SELECT groupname,subgroupname,orderid FROM ".$GLOBALS["eztbContents"]." WHERE contentname='".$_GET["ContentName"]."' AND language='".$GLOBALS["gsDefault_language"]."'";
   $fromresult = dbRetrieve($strQuery,true,0,1);
   while ($rs = dbFetch($fromresult))
   {
      $groupname    = $rs["groupname"];
      $subgroupname = $rs["subgroupname"];
      $orderid      = $rs["orderid"];

      // get orderid for the previous or next content page in the list
      $neworderid = '';
      if ($_GET["direction"] == 'up')
      {
         $strQuery = "SELECT contentname,orderid FROM ".$GLOBALS["eztbContents"]." WHERE orderid<'".$orderid."' AND groupname='".$groupname."' AND subgroupname='".$subgroupname."' AND language='".$GLOBALS["gsDefault_language"]."' ORDER BY orderid DESC";
      }
      else
      {
         $strQuery = "SELECT contentname,orderid FROM ".$GLOBALS["eztbContents"]." WHERE orderid>'".$orderid."' AND groupname='".$groupname."' AND subgroupname='".$subgroupname."' AND language='".$GLOBALS["gsDefault_language"]."' ORDER BY orderid";
      }
      $toresult = dbRetrieve($strQuery,true,0,1);
      while ($rs = dbFetch($toresult))
      {
         $newcontentname = $rs["contentname"];
         $neworderid     = $rs["orderid"];
      }
      dbFreeResult($toresult);

      // Swap the two, as long as there was a next or previous record to swap with
      if ($neworderid != '')
      {
         $strQuery = "UPDATE ".$GLOBALS["eztbContents"]." SET orderid='".$orderid."' WHERE contentname='".$newcontentname."'";
         $result = dbExecute($strQuery,true);
         $strQuery = "UPDATE ".$GLOBALS["eztbContents"]." SET orderid='".$neworderid."' WHERE contentname='".$_GET["ContentName"]."'";
         $result = dbExecute($strQuery,true);
      }
   }
   dbFreeResult($fromresult);
   dbCommit();

} // function ShuffleContents()

?>

