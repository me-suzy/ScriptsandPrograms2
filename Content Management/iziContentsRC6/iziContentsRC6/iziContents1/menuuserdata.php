<?php

/***************************************************************************

 menuuserdata.php
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

$GLOBALS["rootdp"] = './';
require_once ($GLOBALS["rootdp"]."include/config.php");
require_once ($GLOBALS["rootdp"]."include/db.php");
require_once ($GLOBALS["rootdp"]."include/session.php");

include_once ($GLOBALS["rootdp"]."include/settings.php");
include_once ($GLOBALS["rootdp"]."include/functions.php");
includeLanguageFiles('admin','main');
include ($GLOBALS["rootdp"]."include/userdata.php");


if (isset($_POST["topgroupname"])) { $_GET["topgroupname"] = $_POST["topgroupname"]; }
if (isset($_POST["groupname"])) { $_GET["groupname"] = $_POST["groupname"]; }
if (isset($_POST["subgroupname"])) { $_GET["subgroupname"] = $_POST["subgroupname"]; }


userdatamain('menu');

?>



