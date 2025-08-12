<?php
/**
 *	(c)2005 http://Lauri.Kasvandik.com
 */

require_once 'configuration.php';
require_once 'classes/tpl.class.php';
#require_once 'classes/mysql.class.php';
require_once 'classes/Excel/reader.php';
require_once 'classes/quiz.class.php';
#require_once 'classes/duration.php';

$cahcepath = 'cache/';
$xlspath = 'db/';

$excelFiles = glob($xlspath . '*.xls');
#print_r($excelFiles );

foreach($excelFiles as $file)
{
	$fileWithoutExt= preg_replace('/(.+)\..*$/', '\\1', basename($file));
#	echo $basefilename;
	$test = new Quiz($fileWithoutExt);
	$test->load(1);
}
?>