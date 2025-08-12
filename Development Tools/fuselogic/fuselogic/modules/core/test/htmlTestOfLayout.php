<?php

require_once('testOfLayout.php');
$test = &new testOfLayout();
$test->run(new HtmlReporter());

?>