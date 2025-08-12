<?php

$GLOBALS["rootdp"] = './';

require_once ($GLOBALS["rootdp"]."include/config.php");
require_once ($GLOBALS["rootdp"]."include/db.php");
require_once ($GLOBALS["rootdp"]."include/session.php");

require_once ($GLOBALS["rootdp"]."include/settings.php");
require_once ($GLOBALS["rootdp"]."include/functions.php");

if (isset($_POST["topgroupname"]))	{ $_GET["topgroupname"]	= $_POST["topgroupname"]; }
if (isset($_POST["groupname"]))	        { $_GET["groupname"]	= $_POST["groupname"]; }
if (isset($_POST["subgroupname"]))	{ $_GET["subgroupname"]	= $_POST["subgroupname"]; }
if (isset($_POST["contentname"]))	{ $_GET["contentname"]	= $_POST["contentname"]; }

?>
