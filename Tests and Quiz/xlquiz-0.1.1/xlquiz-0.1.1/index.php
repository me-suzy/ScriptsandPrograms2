<?php
/**
 *	(c)2005 http://Lauri.Kasvandik.com
 */

session_start();

require_once 'configuration.php';
require_once 'classes/tpl.class.php';
require_once 'functions.php';

$tpl['title'] = 'XL-Quiz testpage';

$tpl['body'] = <<<html
<h2>Linux Quizes</h2>
<ul>
<li><a href="taketest.php?id=linux">Linux+</a></li>
</ul>

<p>If You have found any mistakes, have questions or comments then please <a href="contact.php">contact me</a>!</p>

<p class="small">Powered by <a href="http://www.php.net">PHP</a> and
<a href="http://mysql.com">MySQL</a><br />

Valid <a href="http://validator.w3.org/check?uri=referer">XHTML</a> and 
<a href="http://jigsaw.w3.org/css-validator/check/referer">CSS</a><br />

Handwritten with <a href="http://www.editplus.com">Edit+</a><br/>
Tests made with <a href="http://www.openoffice.org/">OpenOffice</a> :P</p>
html;

tpl::out('body.php');

?>