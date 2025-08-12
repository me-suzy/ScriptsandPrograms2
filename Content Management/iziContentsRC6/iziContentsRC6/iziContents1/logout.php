<?php

/***************************************************************************

 logout.php
 -----------
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
include ($GLOBALS["rootdp"]."include/functions.php");


$EZ_SESSION_VARS["LoginCookie"] = '';
$EZ_SESSION_VARS["PasswordCookie"] = '';
$EZ_SESSION_VARS["UserID"] = 0;
$EZ_SESSION_VARS["UserName"] = '';
$EZ_SESSION_VARS["UserGroup"] = '';
db_session_write();


if (!isset($_GET["ref"]) || ($_GET["ref"] == "")) {
	$_GET["ref"] = "login.php";
}

if ($_GET["topgroupname"] != '') {
	Header("Location: ".BuildLink($_GET["ref"])."&topgroupname=".$_GET["topgroupname"]."&groupname=".$_GET["groupname"]."&subgroupname=".$_GET["subgroupname"]."&contentname=".$_GET["contentname"]."&link=".$_GET["link"]);
} else {
	Header("Location: ".BuildLink($_GET["ref"])."&groupname=".$_GET["groupname"]."&subgroupname=".$_GET["subgroupname"]."&contentname=".$_GET["contentname"]."&link=".$_GET["link"]);
}

?>
