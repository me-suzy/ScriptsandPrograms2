<?php
/*
* Project:      PHP-CSL (PHP Code Snippet Library)
* Version:      0.9
* Date:         2005/02/09 (y/m/d)
* Author:       Stuart Cochrane
* URL:          http://www.php-csl.com
* Sourceforge:  http://www.sourceforge.net/projects/php-csl/
*
* Read: reame.txt, install.txt, license.txt
*
*
*
*
*
*/
include 'config.php';
include 'functions.php';
?>
<html>
<head>
<title>Printer Friendly: <?=$_GET['cat_select'].' / '.$_GET['show']?></title>
<meta name="keywords" content="php-csl, code snippet library">
<meta name="description" content="PHP Code Snippet Library stores all your code for you for easy access, runs on any PHP platform and does not require a database... easy to install and full of features."></head>
<body topmargin="0" leftmargin="0" marginheight="0" marginwidth="0">
<link rel="stylesheet" href="<?=$snippet_theme?>/phpcsl.css" type="text/css">
<?
if(isset($_GET['show']) || !isset($_GET['op'])) {
echo '<h1>'.$_GET['cat_select'].' / '.$_GET['show'].'</h1>';
require 'middle.php';
echo '<br><h1>Description</h1>';
require 'description.php';
}
echo '</body></html>';
?>