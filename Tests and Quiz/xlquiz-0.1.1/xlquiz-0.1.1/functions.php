<?php
/**
 *	(c)2005 http://Lauri.Kasvandik.com
 */

function print_pre($var)
{
	print '<pre>';
	print_r($var);
	print '</pre>';
}

// t as translate
function t($string, $lang = 'et') {
	static $strings;

	if(!isset($strings)) {
		if(defined('LANG')) $lang = LANG;

		$path = 'lang/' . $lang . '.ini';

		if(!is_file($path)) return 'Languagefile "' . $path . '" not found :(';

		$strings = parse_ini_file($path);
	}
	return isset($strings[$string]) ? $strings[$string] : $string;
}


function mikrotime()
{
	return array_sum(explode(' ', microtime()));
}

function nice_chars($str)
{
	$find		= array('',			'',			'',			'',			'',			'');
	$replace = array('&hellip;',	'&lsquo;',	'&rsquo;',	'&mdash;',	'&ldquo;',	'&rdquo;');
	return str_replace($find, $replace, $str);
}

?>