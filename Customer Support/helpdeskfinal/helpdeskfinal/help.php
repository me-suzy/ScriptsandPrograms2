<?php
//
// Project: Help Desk support system
// Description: Help page
//
// Copyright 2005 http://simplehelpdesk.com  Do not resell or redistribute.. This is free copyrighted software.

require_once "includes/auth.php";
require_once "includes/db.php";
require_once "includes/tpl.php";
require_once "includes/funcs.php";
require_once "includes/const.php";

$page_title	= "Help";
$tpl_help	= new tpl("tpl/help.tpl");

echo build_page(content_box($tpl_help->template, $page_title), $page_title);
?>