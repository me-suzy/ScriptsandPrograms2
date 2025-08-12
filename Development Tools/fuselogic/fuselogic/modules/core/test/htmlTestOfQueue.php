<?php

require_once('testOfQueue.php');
$test = &new testOfQueue();
$test->run(new HtmlReporter());

?>