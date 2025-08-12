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


global $block;

if (file_exists("db/blocked_ips.txt.php")) {
	$block=file("db/blocked_ips.txt.php");
	natsort($block);
} else {
	$block[]="0.0.0.0";
}

// loose checking, include ranges like 127.128.129.* or 127.128.129.40-80
function ip_same($num1, $num2) {

	if ($num1==$num2) { return TRUE; }
	if ( ($num1=="*") || ($num2=="*") ) { return TRUE; }
	if ( strpos($num2,"-") > 0 ) {
		list($num2_min, $num2_max) = explode("-", $num2);
		if ( ($num1>=$num2_min) && ($num1<=$num2_max) ) { return TRUE; }
	}

	return FALSE;

}

function ip_check_block($ip) {
	global $block;

	//debug("check ip: $ip");
	$ip_split=explode(".", $ip);
	
	foreach ($block as $test_ip) {

		$test_ip=trim($test_ip);
		
		// simple test if there are no ranges or wildcards..
		if ( (strpos($test_ip, "*") == 0) && (strpos($test_ip, "-") == 0)) {
			if ($test_ip==$ip) {
				return TRUE;
			}
		} else {
			// elaborate test
			$test_ip=explode(".", $test_ip);
		
			if ( (ip_same($ip_split[0], $test_ip[0])) && (ip_same($ip_split[1], $test_ip[1])) 
					&& (ip_same($ip_split[2], $test_ip[2])) && (ip_same($ip_split[3], $test_ip[3])) ) {
				return TRUE;
			}
		}
	}
	
	return FALSE;
	
		
}

// strict checking. no ranges.
function ip_check_block_strict($ip) {
	global $block;

	foreach ($block as $test_ip) {
		if ($ip==trim($test_ip)) {
			return TRUE;
		}
	}
	
	return FALSE;
}



function block_type($ip){
	global $block_cache;
	
	// if checked before, return it from cache..
	if (isset($block_cache[$ip])) {
		return $block_cache[$ip];
	} 
	
	if (!(ip_check_block($ip))) {
		$block_cache[$ip] = "none";
		return "none";
	} else {
		if (ip_check_block_strict($ip)) {
			$block_cache[$ip] = "single";
			return "single";
		} else {
			$block_cache[$ip] = "range";
			return "range";
		}
	}
}


function add_block($ip) {
	global $block, $block_cache;

	$fp=fopen("db/blocked_ips.txt.php", "a");
	fwrite($fp, $ip."\n");
	fclose($fp);
	$block[]=$ip;
	$block_cache[$ip] = 'single';
	
	
}


function rem_block($ip) {
	global $block, $block_cache;

	$fp=fopen("db/blocked_ips.txt.php", "w");
	foreach ($block as $key =>$line) {
		if (trim($line) != $ip) {
			fwrite($fp, trim($line)."\n");
		} else {
			unset($block[$key]);
			unset($block_cache[$ip]);
		}
	}
	fclose($fp);
	

}


function write_blocks($block) {

	$fp=fopen("db/blocked_ips.txt.php", "w");
	
	if ($fp) {
	
		$block = explode("\n", $block);

		foreach ($block as $line) {
			if (strlen($line)>3) {
				fwrite($fp,trim($line)."\n");
			}
		}
		fclose($fp);

		// we can get rid of the old .txt file 
		if (file_exists("db/blocked_ips.txt")) {
			unlink("db/blocked_ips.txt");	
		}
		
	} else {
		
		echo "couldn't write db/blocked_ips.txt.php ";
		
	}
	
}

function write_ignoreddomains($domains) {
	$fp=fopen("db/ignored_domains.txt.php", "w");
	if ($fp) {
		$domains = explode("\n", $domains);
		$blockArray = array();	
		foreach ($domains as $line) {
			if (strlen($line)>2) {
				$output .= strtolower(trim($line))."\n";
			}
		}
		fwrite($fp, $output);
		fclose($fp);

		// we can get rid of the old .txt file 
		if (file_exists("db/ignored_domains.txt")) {
			unlink("db/ignored_domains.txt");	
		}
		
	} else {
		
		echo "couldn't write db/ignored_domains.txt.php ";
		
	}
}



function make_mask($ip) {

	$ip=explode(".",$ip);
	$ip['3']="*";
	$ip=implode(".",$ip);
	
	return $ip;

}

?>
