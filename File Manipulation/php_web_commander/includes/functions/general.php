<?php

//
// +----------------------------------------------------------------------+
// | Web Manager                                                          |
// +----------------------------------------------------------------------+
// | Copyright (c) 2004 Protung Dragos (www.protung.ro)                   |
// +----------------------------------------------------------------------+
// +----------------------------------------------------------------------+
// |   filename             : general.php                                 |
// |   copyright            : (C) 2004 Dragos Protung                     |
// |   email                : dragos@protung.ro                           |
// |   lastedit             : 24/08/2004 14:07                            |
// |                                                                      |
// |                                                                      |
// +----------------------------------------------------------------------+
// | Author: Protung Dragos <dragos@protung.ro>                           |
// +----------------------------------------------------------------------+
//



/**
 * @return int
 * @param string $haystack
 * @param string $needle
 * @param int $start
 * @desc Find last occurance of needle in haystack
*/
function str_lpos($haystack, $needle, $start = 0) {

	$postemp= strpos($haystack, $needle, $start);
	if($postemp === false){

		return $start - strlen($needle);
	} else {

		return str_lpos($haystack, $needle, $postemp + strlen($needle));
	}
}



/**
 * @return array
 * @param array
 * @desc Sort arrays by columns
 *       Original coded by Ichier2003
*/
function array_csort() {
   
	$args = func_get_args();
	
	if ( !is_array($args[0]) )
		return false;
	if ( !array_key_exists($args[1], $args[0][0]) ) {
		return $args[0];
	}	
	$marray = array_shift($args);
	$i = 0;
	$msortline = "return(array_multisort(";
	
	foreach ($args as $arg) {
		$i++;
		if (is_string($arg)) {
			foreach ($marray as $row) {

				$sortarr[$i][] = $row[$arg];
			}
		} else {
			$sortarr[$i] = $arg;
		}
		$msortline .= "\$sortarr[".$i."],";
	}
	$msortline .= "\$marray));";

	eval($msortline);
	return $marray;
}


/**
 * @return ARRAY
 * @param int $old_version
 * @desc Get the update details
*/
function check_new_version ($old_version) {

	$data = @file_get_contents("http://protung.ro/phpwc_version.php");
	$data = @explode("_X_", $data);
	if ($old_version < $data[0]) {
	
		return $data;
	} else {
		return false;
	}
}

?>