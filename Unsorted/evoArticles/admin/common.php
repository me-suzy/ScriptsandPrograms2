<?php
// +------------------------------------------------------------------+
// | evoArticles 
// | Copyright (c) 2003-2004 Evo-Dev
// +------------------------------------------------------------------+


error_reporting(E_ALL & ~E_NOTICE);

require ("init.php");
require ("config.php");
require ("config_site.php");
require ("lib/db_mysql.php");

require ("./lib/class.mail.php");
require ("./lib/class.template.php");
require ("./lib/class.functions.php");


$udb = new sqldb;
$udb->scriptname=$site['name'];
$udb->start($db['host'],$db['user'],$db['pass'],$db['name']);

$settings['scriptname'] = "evoArticles";
$version = "1.0.1";
$build = "4102004";

// ---------------
// ************************** start the functions **********************************//
function do_out($a)
{
	admin::do_compress();
	echo ($a);
}

function start()
{
	$starttime = microtime();	
	$starttime = explode(" ",$starttime);
	$starttime = $starttime[1] + $starttime[0];
	return($starttime);		
}
$starttime = start();
	
function endtime()
{
	global $starttime;

	$endtime = microtime();
	$endtime = explode(" ",$endtime);
	$endtime = $endtime[1] + $endtime[0];
	$stime = $endtime - $starttime;
	return $stime;
}

function showtime()
{
	global $evoLANG,$udb,$credit,$site;
	$stime = endtime();
	$time = round($stime,4);

	if ($stats = @exec('uptime'))
	{
		preg_match('/averages?: ([0-9\.]+),[\s]+([0-9\.]+),[\s]+([0-9\.]+)/',$stats,$regs);
		$server_load = $evoLANG[serverload].': '.$regs[1].'';
    }
	else
	{
		$server_load='';
    }
	$time = "<div align=\"center\"> $evoLANG[pagegenerated] <i>".$time."</i>  &amp; ".$udb->query_counter." $evoLANG[queries]. $gzip $server_load <br />$credit</div>";
	return $time;
}

// **************** database ************************//
include("./lib/db_tables.php");
?>