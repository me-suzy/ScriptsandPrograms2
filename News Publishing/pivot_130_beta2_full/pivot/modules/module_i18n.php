<?php

// ---------------------------------------------------------------------------
//
// PIVOT - LICENSE:
//
// This file is part of Pivot. Pivot and all its parts are licensed under 
// the GPL version 2. see: http://www.pivotlog.net/help/help_about_gpl.php
// for more information.
//
// ---------------------------------------------------------------------------

// don't access directly..
if(!defined('INPIVOT')){ exit('not in pivot'); }

function i18n_is_utf8($string) {
	// From http://w3.org/International/questions/qa-forms-utf-8.html
	return preg_match('%^(?:
		  [\x09\x0A\x0D\x20-\x7E]            # ASCII
		| [\xC2-\xDF][\x80-\xBF]             # non-overlong 2-byte
		|  \xE0[\xA0-\xBF][\x80-\xBF]        # excluding overlongs
		| [\xE1-\xEC\xEE\xEF][\x80-\xBF]{2}  # straight 3-byte
		|  \xED[\x80-\x9F][\x80-\xBF]        # excluding surrogates
		|  \xF0[\x90-\xBF][\x80-\xBF]{2}     # planes 1-3
		| [\xF1-\xF3][\x80-\xBF]{3}          # planes 4-15
		|  \xF4[\x80-\x8F][\x80-\xBF]{2}     # plane 16
		)*$%xs', $string);
}


function i18n_ucfirst($string) {

   $string[0] = strtr($string,
   "abcdefghijklmnopqrstuvwxyz".
   "\x9C\x9A\xE0\xE1\xE2\xE3".
   "\xE4\xE5\xE6\xE7\xE8\xE9".
   "\xEA\xEB\xEC\xED\xEE\xEF".
   "\xF0\xF1\xF2\xF3\xF4\xF5".
   "\xF6\xF8\xF9\xFA\xFB\xFC".
   "\xFD\xFE\xFF",
   "ABCDEFGHIJKLMNOPQRSTUVWXYZ".
   "\x8C\x8A\xC0\xC1\xC2\xC3\xC4".
   "\xC5\xC6\xC7\xC8\xC9\xCA\xCB".
   "\xCC\xCD\xCE\xCF\xD0\xD1\xD2".
   "\xD3\xD4\xD5\xD6\xD8\xD9\xDA".
   "\xDB\xDC\xDD\xDE\x9F");

   return $string;	
	
}



/* Decodes a string to UTF-8 from the internal_encoding */
function i18n_str_to_utf8($string) {
    global $i18n_use;
    
    
    if (!$i18n_use) { 
    	// debug('0: do nothing');
    	// do nothing
    	return $string; 
    	
    } else {

        if (function_exists('mb_detect_encoding')) {
            $encoding = mb_detect_encoding($string);
	    }
	    
        switch (strtolower($encoding)) {


                case 'iso-8859-1':
                        $output = utf8_encode($string);
                        break;
                case 'euc-jp':
                        $output = eucjp_to_utf8($string);
                        break;
				case '':                        
                case 'utf-8':
                        $output = $string;
                        break;
                default:
                        $output = utf8_encode($string);
                        break;
        }

       //  debug("'1: $encoding: $string == $output");
        
    	return $output;
    }
}


/**
 * Enter description here...
 *
 * @param unknown_type $item
 * @param unknown_type $key
 */
function i18n_array_to_utf8(&$item, &$key) {
	if (is_array($item)) {
		array_walk($item, 'i18n_array_to_utf8');
	} else {
		$item = i18n_str_to_utf8($item);
	}
}

/* Decodes a string to the output_encoding from UTF-8 */
function i18n_utf8_to_str($string) {
	global $CurrentEncoding;

	switch (strtolower($CurrentEncoding)) {
		case '':
		case 'iso-8859-1':
			$string = utf8_decode($string);
			break;
		case 'euc-jp':
			$string = utf8_to_eucjp($string);
			break;
		default:
			$string = utf8_decode($string);
			break;
	}

	return $string;
}

/* Decodes a string to UTF-8 from EUC-JP */
function eucjp_to_utf8($string) {
	if (function_exists('mb_convert_encoding')) {
		return mb_convert_encoding($string, "UTF-8");
	}
	return $string;
}

/* Multi-byte safe wordwrap */
function i18n_wordwrap($i, $width, $break, $cut) {
	global $CurrentEncoding;

	switch (strtolower($CurrentEncoding)) {
	case 'utf-8':
	case 'euc-jp':
		return $i; /* to do nothing is better than to break it */
	default:
		/* single-byte space-delimitered language */ 
		return wordwrap($i, $width, $break, $cut);
	}
}

function i18n_safe_string($str, $strict=FALSE) {
   $str = strip_tags($str);
   $str = str_replace("&amp;", "", $str);

   if ($strict) {
     $str=str_replace(" ", "_", $str);
   }
   return $str;
}

function  i18n_entify($i) {
  return $i;
}

function  i18n_unentify($i) {
  return $i;
}

?>