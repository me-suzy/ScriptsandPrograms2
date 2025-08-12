<?php
// THIS MUST BE THE FIRST LINE OF THE FILE
$GLOBALS['SCRIPT_ROOT'] = '../';
require_once ($GLOBALS['SCRIPT_ROOT'].'include/init.inc.php');
$announce = new SSGroupPage;
$announce->render ();
?>
