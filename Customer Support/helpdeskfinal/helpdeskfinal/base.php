<?php
//
// Project: Help Desk support system
// Description: Page footer
//
require_once "includes/tpl.php";

$tpl_footer = new tpl("tpl/footer.tpl");

$tpl_footer->compile();
echo $tpl_footer->compiled;
?>