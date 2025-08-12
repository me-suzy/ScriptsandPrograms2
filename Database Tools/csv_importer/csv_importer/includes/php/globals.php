<?

/*
	includes/php/globals.php

	Here are the global variables which are used all over the place and rather than mess about, I've put them in here to make things easier. d:-)
	
	As with all of the files, don't mess about with stuff as you might stop things working!
*/

$operandsArray = array(0 => array("contains", "contains"),
								1 => array("ends with", "endswith"),
								2 => array("is greater than", "greaterthan"),
								3 => array("is empty.", "empty"),
								4 => array("is equal to", "equalto"),
								5 => array("is less than", "lessthan"),
								6 => array("is not empty.", "notempty"),
								7 => array("is not equal to", "notequalto"),
								8 => array("starts with", "startswith"));
$ruleStages = 7;
$stage = $_POST['stage'];

// About
$authorEmail = "sir_tripod@hotmail.com";
$authorName = "Matthew Lindley";
$scriptDownloadURL = "http://www.asafeplace.co.uk/downloads/csv_importer.zip";
$scriptVersion = "2.0a";

?>