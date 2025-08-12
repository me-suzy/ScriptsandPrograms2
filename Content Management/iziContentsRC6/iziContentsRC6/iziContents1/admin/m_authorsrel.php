<?php

/***************************************************************************

 m_authorsrel.php
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


$GLOBALS["form"] = 'authors';
$validaccess = VerifyAdminLogin();


if ($GLOBALS["canedit"] == False)
{
   Header("Location: ".BuildLink('adminlogin.php'));
}
else
{
   ReleaseAuthor();
   Header("Location: ".BuildLink('m_authors.php')."&page=".$_GET["page"]."&sort=".$_GET["sort"]."&filtergroupname=".$_GET["filtergroupname"]);
}


function ReleaseAuthor()
{
   global $_GET;

   $strQuery = "UPDATE ".$GLOBALS["eztbAuthors"]." SET disuser = 1-disuser WHERE authorid='".$_GET["UserID"]."'";
   $result = dbExecute($strQuery,true);
   dbCommit();
} // function ReleaseAuthor()


?>
