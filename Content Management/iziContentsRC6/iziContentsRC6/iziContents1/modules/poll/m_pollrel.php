<?php

/***************************************************************************

 m_pollrel.php
 --------------
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

$GLOBALS["ModuleName"] = 'poll';
include("moduleref.php");

$GLOBALS["rootdp"] = '../../';
include ($GLOBALS["rootdp"]."include/config.php");
include ($GLOBALS["rootdp"]."include/db.php");
include ($GLOBALS["rootdp"]."include/session.php");
include ($GLOBALS["rootdp"]."include/access.php");
include ($GLOBALS["rootdp"]."include/functions.php");
include ($GLOBALS["rootdp"].$GLOBALS["modules_home"]."modfunctions.php");

$GLOBALS["form"] = 'subcontent';
$validaccess = VerifyAdminLogin();


if ($GLOBALS["canedit"] == False)
{
   Header("Location: ".BuildLink($GLOBALS["rootdp"].'login.php'));
}
else
{
   GetModuleData($GLOBALS["ModuleRef"]);
   ReleaseEntry('pollid',$_GET["PollID"]);
   Header("Location: ".BuildLink('m_'.$GLOBALS["ModuleName"].'.php')."&page=".$_GET["page"]);
}

?>
