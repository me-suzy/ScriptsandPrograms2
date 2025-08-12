<?php 
Class language {

// *** GENERAL ************************************************
var $charset = 'iso-8859-1';
var $day_of_week_short = array('So', 'Mo', 'Di', 'Mi', 'Do', 'Fr', 'Sa');
var $day_of_week = array('Sonntag', 'Montag', 'Dienstag', 'Mittwoch', 'Donnerstag', 'Freitag', 'Samstag');
var $month = array('Januar', 'Februar', 'März', 'April', 'Mai', 'Juni', 'Juli', 'August', 'September', 'Oktober', 'November', 'Dezember');
var $dateformat_short = '%dd.%dm.%dY';	// 11.10.2002
var $dateformat_long = '%dd. %dM %dY';	// 11. Oktober 2002

var $byteUnits = array(' B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB');
var $number_thousands_separator = '.';
var $number_decimal_separator = ',';


	function language() {
	}

} // end class language

$lang = new language;
?>