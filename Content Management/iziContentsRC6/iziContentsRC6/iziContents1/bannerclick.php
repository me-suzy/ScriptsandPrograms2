<?php

/***************************************************************************

 bannerclick.php
 ----------------
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

$GLOBALS["rootdp"] = './';
require_once ($GLOBALS["rootdp"]."include/config.php");
require_once ($GLOBALS["rootdp"]."include/db.php");
require_once ($GLOBALS["rootdp"]."include/session.php");


// Retrieve data for the banner that the viewer has clicked on,
//	update the number of clicks, and redirect the viewer to the
//	appropriate web page
if ($_GET["id"] != '') {
	$BannerUrl = GetBannerData($_GET["id"]);
	if ($BannerUrl != '') {
		Header("Location: ".$BannerUrl);
	}
} // if ($_GET["id"] != '')




// Retrieve the data for this banner
function GetBannerData($BannerID)
{
	$strQuery = "SELECT bannerurl,clicks FROM ".$GLOBALS["eztbBanners"]." WHERE bannerid='".$BannerID."'";
	$result = dbRetrieve($strQuery,true,0,0);
	if ($rs = dbFetch($result)) {
		$BannerUrl = trim($rs["bannerurl"]);
		UpdateClicks($BannerID);
	} // if ($rs = dbFetch($result))
	dbFreeResult($result);
	return $BannerUrl;
} // function GetBannerData()


// Update the 'clicks' count
function UpdateClicks($BannerID)
{
	$strQuery = "UPDATE ".$GLOBALS["eztbBanners"]." SET clicks=clicks+1 WHERE bannerid='".$BannerID."'";
	$result = dbExecute($strQuery,true);
	dbCommit();
} // function UpdateClicks()

?>
