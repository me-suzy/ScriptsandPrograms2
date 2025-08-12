<?php

/***************************************************************************

 m_contenttoggle.php
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

$GLOBALS["form"] = 'content';
$validaccess = VerifyAdminLogin();


if ($GLOBALS["canedit"] == True)
{
   ToggleContent();
   Header("Location: ".BuildLink('m_content.php')."&page=".$_GET["page"]."&sort=".$_GET["sort"]);
}
else
{
   Header("Location: ".BuildLink('adminlogin.php'));
}


function ToggleContent()
{
   global $_GET;

   $strQuery = "UPDATE ".$GLOBALS["eztbContents"]." SET contentactive=1-contentactive WHERE contentname='".$_GET["ContentName"]."'";
   $result = dbExecute($strQuery,true);
   dbCommit();

} // function ToggleContent()

?>

