<?php
/************************************************************************/
// SAXON: Simple Accessible XHTML Online News system
// Module: logout.php
// Version 4.6
// Developed by Black Widow
// Copyright (c) 2004 by Black Widow
// Support: www.forum.quirm.net
// Commercial Site: www.blackwidows.co.uk
/************************************************************************/
session_start();
header("Cache-control: private"); // IE 6 Fix.
session_destroy();

include 'config.php';

header("Location: ".$uri.$path."login.php");
?>