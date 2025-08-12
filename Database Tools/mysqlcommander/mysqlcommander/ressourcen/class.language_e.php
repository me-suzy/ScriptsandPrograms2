<?php 
Class language {

// *** GENERAL ************************************************
var $charset = 'iso-8859-1';
var $day_of_week_short = array('Su', 'Mo', 'Tu', 'We', 'Th', 'Fr', 'Sa');
var $day_of_week = array('Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday');
var $month = array('January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December');
var $dateformat_short = '%dm/%dd/%dY';	// 10/11/2002
var $dateformat_long = '%dM %dd, %dY';		// October 11, 2002

var $byteUnits = array(' B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB');
var $number_thousands_separator = ',';
var $number_decimal_separator = '.';


	function language() {
	}

} // end class language

$lang = new language;
?>