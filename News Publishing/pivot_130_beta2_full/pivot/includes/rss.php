<?php

$old_errrep = error_reporting(0);
//$old_errrep = error_reporting(E_ALL);

// lamer protection
if (strpos($pivot_path,"ttp://")>0) {	die('no');}
$scriptname = basename((isset($_SERVER['PATH_INFO'])) ? $_SERVER['PATH_INFO'] : $_SERVER['PHP_SELF']);
$checkvars = array_merge($_GET , $_POST, $_SERVER, $_COOKIE);
if ( (isset($checkvars['pivot_url'])) || (isset($checkvars['log_url'])) || (isset($checkvars['pivot_path'])) ) {
	die('no');
}
// end lamer protection


// -----------------------
// wrapped by a 'function_exists', since it might already be defined..
if (!function_exists('safe_string')) {
	function safe_string($str, $strict=FALSE) {

		$str = strip_tags($str);

		$str = strtr (
				strtr($str,
					'©®¹¾¼ÀÁÂÃÅÇÈÉÊËÌÍÎÏÑÒÓÔÕØÙÚÛÝàáâãäåçèéêëìíîïñòóôõøùúûýÿ',
					'SZszYAAAAACEEEEIIIINOOOOOUUUYaaaaaaceeeeiiiinooooouuuyy'),
				array(
					'Þ' => 'TH', 
					'þ' => 'th', 
					'Ð' => 'DH', 
					'ð' => 'dh', 
					'ä' => 'ae', 
					'ü' => 'ue', 
					'ö' => 'oe', 
					'Ä' => 'AE', 
					'Ü' => 'UE', 
					'Ö' => 'OE', 
					'ß' => 'ss', 
					'¦' => 'OE', 
					'¶' => 'oe', 
					'Æ' => 'AE', 
					'æ' => 'ae', 
					'µ' => 'mu'
				)
			);

		$str=str_replace("&amp;", "", $str);

		if ($strict) {
			$str=str_replace(" ", "_", $str);
			$str=strtolower(ereg_replace("[^a-zA-Z0-9_]", "", $str));
		} else {
			$str=ereg_replace("[^a-zA-Z0-9 _.,-]", "", $str);
		}
		return $str;
	}
}



// -----------------------
// Main
// -----------------------

$rssformat = stripslashes($rssformat);

define('MAGPIE_DIR', dirname(__FILE__).'/magpierss/');
define('MAGPIE_CACHE_DIR', $pivot_path.'./db/rsscache/');
define('MAGPIE_FETCH_TIME_OUT', 5);	// 5 second timeout
define('MAGPIE_CACHE_AGE', 60*60*8); // 8 hours

if ( (!isset($trimlen)) || ($trimlen==0)) {
	$trimlen = 60;	
}

require_once('magpierss/rss_fetch.inc');

$feed = fetch_rss($rssurl);

$count = 1;

foreach ( $feed->items as $item ) {

		$itemformat = $rssformat;
		foreach ($item as $key => $value) {
			
			if ($key == "link") {
				$value = trim($value);
			} else {
				$value = trimtext(trim($value), $trimlen);
			}
				
			
			if (is_string($value)) {
				$itemformat = str_replace("%$key%", $value, $itemformat);
			}

		}

		echo $itemformat;
		
		if (($count++)>=$rssmax) { break; }
		
}

error_reporting($old_errrep);

?>
