<?php

require_once('testOfFuselogic.php');
$test = &new testOfFuselogic();
$test->run(new HtmlReporter());

?>