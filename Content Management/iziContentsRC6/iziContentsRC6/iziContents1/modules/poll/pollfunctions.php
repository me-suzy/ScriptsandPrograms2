<?php
/***************************************************************************

 pollfunctions.php
 -------------------
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

function GraphValue($hits,$hitstotal,$colour)
{
   $colourbars = array('blue','pink','yellow','darkgreen','purple','gold','green','brown','orange','aqua','grey','red');

   $colourval = ($colour % count($colourbars));
   $graphvalue = '';
   $percentage = ceil(($hits / $hitstotal) * 100);
   if (($hitstotal > 0) && ($percentage > 0)) {
      $imagelength = $percentage * $GLOBALS["ScreenWidthMultiplier"];
      $graphvalue='<IMG SRC="'.$GLOBALS["rootdp"].$GLOBALS["icon_home"].'graphbar_'.$colourbars[$colourval].'.gif" HEIGHT="10" WIDTH="'.$imagelength.'">';
   }
   return $graphvalue;
} // function GraphValue()


function RegisterVote($poll,$vote)
{
	$sqlString = "INSERT INTO ".$GLOBALS["scTable"]."results (userid,pollid,pollresult) VALUES('".$GLOBALS["PollName"]."', '".$poll."', '".$vote."')";
	$result = dbExecute($sqlString,true);

	$sqlString = "UPDATE ".$GLOBALS["scTable"]."options SET optioncount=optioncount+1 WHERE pollid='".$poll."' AND polloptionid='".$vote."'";
	$result = dbExecute($sqlString,true);
//	dbCommit();
} // function RegisterVote()


function CompleteVote($poll)
{
	$sqlString = "UPDATE ".$GLOBALS["scTable"]." SET pollvotes=pollvotes+1 WHERE pollid='".$poll."'";
	$result = dbExecute($sqlString,true);
//	dbCommit();
} // function CompleteVote()


function GetPollName()
{
   global $_COOKIE, $EZ_SESSION_VARS;

   $PollName = $GLOBALS["ezSID"];
//   $PollName = '';
   if ($_COOKIE["PollName"] != '') {
      $PollName = $_COOKIE["PollName"];
      if (substr($PollName,0,1) != '^') {
         // Somebody is trying to fiddle a cookie
         $PollName = '';
      }
   } elseif ($_COOKIE["UserIdCookie"] != '') {
      $strQuery = "SELECT login from ".$GLOBALS["eztbAuthors"]." WHERE login='".$_COOKIE["UserIdCookie"]."'";
      $result = dbRetrieve($strQuery,true,0,0);
      $rs     = dbFetch($result);
      if ($rs["login"] == $_COOKIE["UserIdCookie"]) { $PollName = $rs["login"]; }
      dbFreeResult($result);
   } elseif (($EZ_SESSION_VARS["UserID"] != '') && ($EZ_SESSION_VARS["UserID"] != 0)) {
      $strQuery = "SELECT authorid,login from ".$GLOBALS["eztbAuthors"]." WHERE authorid='".$EZ_SESSION_VARS["UserID"]."'";
      $result = dbRetrieve($strQuery,true,0,0);
      $rs     = dbFetch($result);
      if ($rs["authorid"] == $EZ_SESSION_VARS["UserID"]) { $PollName = $rs["login"]; }
      dbFreeResult($result);
   }
   if ($PollName == $GLOBALS["ezSID"]) {
      $PollName = substr_replace($PollName,'^',0,1);
      // Timer values for cookies
      // 15 Minutes	= 900;
      // 1 Hour	= 3600;
      // 2 Hours	= 7200;
      // 6 Hours	= 21600;
      // 1 Day	= 86400;
      // 1 Year	= 31622400;
      setcookie ("PollName", $PollName, time()+316224000);
   }
   return $PollName;
} // function GetPollName()


?>
