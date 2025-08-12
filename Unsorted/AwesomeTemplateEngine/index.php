<?php
/* index.php */
include('classes/AwesomeTemplateEngine.class.php');

$aT=new AwesomeTemplateEngine('./templates/');

/* Some sample data */
$data['title']=" AwesomeTemplateEngine Demo ";
$data['message']="You're looking at a revolution in templating.";
$data['table'][1]['item']="include()";
$data['table'][1]['url']="http://www.php.net/include";
$data['table'][2]['item']="class{ }";
$data['table'][2]['url']="http://www.php.net/manual/en/language.oop.php#keyword.class";
$data['poweredby']="Powered by AwesomeTemplateEngine";

/* Show the template */
$aT->parseTemplate($data,"example_template.php");
?>