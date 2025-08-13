<?php
error_reporting(7);
@set_magic_quotes_runtime(0);

require ("config.php");
require ("config_site.php");

require ("init.php");
require ("../source/db_mysql.php");
require ("../source/class.functions.php");
require ("../source/class.template.php");
require ("../source/class.mail.php");
require ("../source/class_codeparse.php");
require ("../source/class_book.php");

$udb = new sqldb;
$udb->scriptname=$site['name'];
$udb->start($db['host'],$db['user'],$db['pass'],$db['name']);
/* ----------------------------------------------------------------------------------*/
$settings['prefix'] = 'book_';

$database['sgroup'] = $settings['prefix'].'settings_group';
$database['settings'] = $settings['prefix'].'settings';
$database['entry'] = $settings['prefix'].'entry';
$database['comments'] = $settings['prefix'].'comment';
$database['smilies'] = $settings['prefix'].'smilies';

if (!file_exists(LANG_FOLDER.$settings['deflang'].".php"))
{
	$evoLANG_file = LANG_FOLDER."lang_english.php";
}
else
{
	$evoLANG_file = LANG_FOLDER.$settings['deflang'].".php";
}

require ($evoLANG_file); // get lang file

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
	global $LANG_script,$udb,$credit,$site;
	$stime = endtime();
	$time = round($stime,4);

	if ($stats = @exec('uptime'))
	{
		preg_match('/averages?: ([0-9\.]+),[\s]+([0-9\.]+),[\s]+([0-9\.]+)/',$stats,$regs);
		$server_load='Server Load: '.$regs[1].'';
    }
	else
	{
		$server_load='';
    }

	//$gzip = ($site['gzip'] == "1") ? "GZIP Enabled.":"GZIP Disabled.";

	$time = "<div align='center'> Page generated in <i>".$time."</i> seconds & ".$udb->query_counter." queries. $gzip $server_load </div>";
	return $time;
}

function do_out($a)
{
	//admin::do_compress();
	echo ($a);
}

admin::do_compress();
?>