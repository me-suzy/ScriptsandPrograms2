<?php
/**
 *	(c)2005 http://Lauri.Kasvandik.com
 */

session_start();

require_once 'functions.php';
$timeStart = mikrotime();

require_once 'configuration.php';
require_once 'classes/tpl.class.php';
require_once 'classes/mysql.class.php';
require_once 'classes/Excel/reader.php';
require_once 'classes/quiz.class.php';
require_once 'classes/duration.php';

SQL::connect(SQL_PATH);

#print_r($_COOKIE);
$id = (string)$_GET['id'];

$t = new Quiz($id);
$t->load();

if($t->getError())
{
	$tpl['title'] = 'Err0r :/';
	$tpl['body'] = '<h2>'.$t->getError() . '</h2><a href="index.php">Click here to go to main page</a>.';
}
else
{
	$tpl['title'] = $t->quiz['title'];

	if(!empty($_POST))
	{
		$tpl['title'] .= ' :: Results';
		$tpl['body'] = $t->getResults($_POST);
		$tpl['body'] = str_replace('<!--summary-->', $t->getSummary(), $tpl['body']);
		$t->addSummaryToDb();
#		SQL::getQueries();
#		print_pre($t->quiz['data'][2]);
	} 
	else
	{
		$tpl['body'] = $t->getHTML();
	}
}

// we replace some characters, so html should validate correctly...
$tpl['body'] = nice_chars($tpl['body']);

tpl::out('body.php');

printf("\n\n<!--%s-->", (mikrotime() - $timeStart));
SQL::getQueries();
?>