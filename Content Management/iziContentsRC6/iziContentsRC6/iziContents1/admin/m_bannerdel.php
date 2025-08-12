<?php

/***************************************************************************

 m_bannerdel.php
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

include_once ("rootdatapath.php");

$GLOBALS["form"] = 'banners';
$validaccess = VerifyAdminLogin();


if ($GLOBALS["candelete"] == False)
{
   Header("Location: ".BuildLink('adminlogin.php'));
}
else
{
   DeleteBanner();
   Header("Location: ".BuildLink('m_banners.php')."&page=".$_GET["page"]."&sort=".$_GET["sort"]);
}


function DeleteBanner()
{
   global $_GET;

   $strQuery = "DELETE FROM ".$GLOBALS["eztbBanners"]." WHERE bannerid='".$_GET["BannerID"]."'";
   $result = dbExecute($strQuery,true);
   dbCommit();
} // function DeleteBanner()

?>
