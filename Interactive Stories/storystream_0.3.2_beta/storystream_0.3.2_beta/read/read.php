<?php
$GLOBALS['SCRIPT_ROOT'] = '../';
require_once ($GLOBALS['SCRIPT_ROOT'].'include/init.inc.php');
$story = new SSReadPage;
$story->render ();
?>