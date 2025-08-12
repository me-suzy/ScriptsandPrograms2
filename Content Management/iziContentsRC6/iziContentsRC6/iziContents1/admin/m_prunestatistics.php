<?php

/***************************************************************************

 m_prunestatistics.php
 ----------------------
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

$GLOBALS["ScreenWidthMultiplier"] = (float) 3.75;


include_once ("rootdatapath.php");

$GLOBALS["form"] = 'prunestatistics';
$GLOBALS["validaccess"] = VerifyAdminLogin();

frmStats($page);
Header("Location: ".BuildLink('m_viewstatistics.php'));


function frmStats()
{
   //  We don't summarise any of the last 20 records, because those appear in their entirety in the
   //     "Who" display of m_viewstatistics, so this just gets the max statid and deducts 20 from it
   //     giving us the highest value that we'll summarise.
   if ($EZ_SESSION_VARS["Site"] != '') {
      $sqlQuery = "SELECT max(statid) AS maxstatid FROM ".$GLOBALS["eztbVisitorstats"]." WHERE site='".$EZ_SESSION_VARS["Site"]."'";
   } else {
      $sqlQuery = "SELECT max(statid) AS maxstatid FROM ".$GLOBALS["eztbVisitorstats"];
   }
   $mresult = dbRetrieve($sqlQuery,true,0,20);
   if ($rm = dbFetch($mresult)) {
      $maxstatid = $rm["maxstatid"];
   }
   dbFreeResult($mresult);
   $maxstatid = $maxstatid - 20;

   // As long as maxstatid is still a positive value, we summarise the table entries using the SQL
   //    "group by" and summing the count of all visits that match the group by criteria.
   if ($maxstatid > 2)
   {
      if ($EZ_SESSION_VARS["Site"] != '') {
         $sqlQuery = "SELECT site,visitoragent,visitorbrowser,visitoros,country,DATE_FORMAT(visitdate, '%Y-%m-%d %H') AS date,sum(countnumber) AS countnumber FROM ".$GLOBALS["eztbVisitorstats"]." WHERE statid <= '".$maxstatid."' AND site='".$EZ_SESSION_VARS["Site"]."' GROUP BY site,visitoragent,visitorbrowser,visitoros,country,DATE_FORMAT(visitdate, '%Y-%m-%d %H')";
      } else {
         $sqlQuery = "SELECT site,visitoragent,visitorbrowser,visitoros,country,DATE_FORMAT(visitdate, '%Y-%m-%d %H') AS date,sum(countnumber) AS countnumber FROM ".$GLOBALS["eztbVisitorstats"]." WHERE statid <= '".$maxstatid."' GROUP BY site,visitoragent,visitorbrowser,visitoros,country,DATE_FORMAT(visitdate, '%Y-%m-%d %H')";
      }
      $result = dbRetrieve($sqlQuery,true,0,0);
      while($r = dbFetch($result)) {
         //  Insert each summary record in the database
         $strQuery = "INSERT INTO ".$GLOBALS["eztbVisitorstats"]." VALUES('', '".$r["Site"]."', '".$r["date"].":00:00', '', '".$r["visitoragent"]."', '".$r["visitoros"]."', '".$r["visitorbrowser"]."', '', '".$r["country"]."', '".$r["countnumber"]."')";
         $presult = dbExecute($strQuery,true);
      }
      dbFreeResult($result);
   }

   //  Prune all the entries that we've just summarised.
   $strQuery = "DELETE FROM ".$GLOBALS["eztbVisitorstats"]." WHERE statid <= '".$maxstatid."'";
   $result = dbExecute($strQuery,true);

   dbCommit();
} // function frmStats()

?>
