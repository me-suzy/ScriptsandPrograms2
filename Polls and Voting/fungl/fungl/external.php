<?php
require_once 'config.php';
require_once 'classes/poll.php';
require_once 'classes/chart.php';
//Copyright 2005 Fungl.com Do not resells or redistribute.
// 
// see http://fung.com or http://fungl.com/download/ for details
// Oh and Dont resell or redistribute this software.
$poll = new Poll($GLOBALS['db'], $_GET['id']);

if($poll->isError()){
	echo "The poll doesn't exist";
	return;
}

$chart = &ChartFactory::Factory($poll->getChartType(), $poll);

$chart->getImageData(200, 200);
?>