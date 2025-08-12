<?php
ob_start();

include("configure.php");
include("settings.php");
include("template.php");
include("classes.php");

$GLOBALS['db'] = new db();
$GLOBALS['error'] = array();
$t = new Template();

if(isset($_REQUEST['submit']) or isset($_REQUEST['c'])){
	if($GLOBALS['db']->floodControl()){
	
	$t->set("title","Warning");
		echo $t->fetch("template_header.tpl");
		echo $t->fetch("template_flood.tpl");
		echo $t->fetch("template_footer.tpl");
		exit;
	
	}
	}

if($GLOBALS['db']->checkTeacherLogin()){
	$t->set("url","index.php?a=latest");
	$t->set("message","Please Wait");
	$t->set("text","Welcome!");
	echo $t->fetch("template_header.tpl");
	echo $t->fetch("template_wait.tpl");
	echo $t->fetch("template_footer.tpl");
	}else{
	$t->set("title","Error");
	echo $t->fetch("template_header.tpl");
	echo $t->fetch("template_error.tpl");
	echo $t->fetch("template_footer.tpl");
	}

?>
