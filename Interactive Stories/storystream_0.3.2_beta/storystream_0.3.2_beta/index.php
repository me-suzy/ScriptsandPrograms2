<?php
$GLOBALS['SCRIPT_ROOT'] = './';
require_once ('include/init.inc.php');
$story = new SSMainFrontPage ();
$story->render ();
?>
