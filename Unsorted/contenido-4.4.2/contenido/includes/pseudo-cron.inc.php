<?php
/***************************************************************************

pseudo-cron v1.2.1
(c) 2003 Kai Blankenhorn
www.bitfolge.de/en
kaib@bitfolge.de


This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.

****************************************************************************


Usually regular tasks like backup up the site's database are run using cron
jobs. With cron jobs, you can exactly plan when a certain command is to be 
executed. But most homepage owners can't create cron jobs on their web 
server - providers demand some extra money for that.
The only thing that's certain to happen quite regularly on a web page are 
page requests. This is where pseudo-cron comes into play: With every page 
request it checks if any cron jobs should have been run since the previous 
request. If there are, they are run and logged.

Pseudo-cron uses a syntax very much like the Unix cron's one. For an 
overview of the syntax used, see a page of the UNIXGEEKS. The syntax 
pseudo-cron uses is different from the one described on that page in 
the following points:

  -  there is no user column
  -  the executed command has to be an include()able file (which may contain further PHP code) 


All job definitions are made in a text file on the server with a 
user-definable name. A valid command line in this file is, for example:

*	2	1,15	*	*	samplejob.inc.php

This runs samplejob.inc.php at 2am on the 1st and 15th of each month.


Features:
  -  runs any PHP script
  -  periodical or time-controlled script execution
  -  logs all executed jobs
  -  can be run from an IMG tag in an HTML page
  -  follow Unix cron syntax for crontabs


Usage:
  -  Modify the variables in the config section below to match your server.
  -  Write a PHP script that does the job you want to be run regularly. Be
     sure that any paths in it are relative to the script that will run 
     pseudo-cron in the end.
  -  Set up your crontab file with your script
  -  Wait for the next scheduled run :)


Note:
You can log messages to pseudo-cron's log file by calling
     logMessage("log a message");


Changelog:

v1.2.1	02-03-03
	fixed:	 jobs may be run too often under certain conditions
	added:	 global debug switch
	changed: typo in imagecron.php which prevented it from working


v1.2	01-31-03
	added:   more documentation
	changed: log file should now be easier to use
	changed: log file name


v1.1	01-29-03
	changed: renamed pseudo-cron.php to pseudo-cron.inc.php
	fixed:   comments at the end of a line don't work
	fixed:   empty lines in crontab file create nonsense jobs
	changed: log file grows big very quickly
	changed: included config file in main file to avoid directory confusion
	added:   day of week abbreviations may now be used (three letters, english)


v1.0	01-17-03
	inital release

***************************************************************************/


/****************************************/
/*		config section					*/
/****************************************/

// || PLEASE NOTE:
// || all paths used here and in cron scripts 
// || must be absolute or relative to the script which includes pseudo-cron.inc.php!

// the file that contains the job descriptions
// for a description of the format, see http://www.unixgeeks.org/security/newbie/unix/cron-1.html
// and http://www.bitfolge.de/pseudocron
$cronTab = $cfg["path"]["contenido"].$cfg['path']['cronjobs']."crontab.txt";

// the directory where the script can store information on completed jobs and its log file
// include trailing slash
$writeDir = $cfg["path"]["contenido"].$cfg['path']['cronjobs'];

// the directory where the script can store information on completed jobs and its log file
// include trailing slash
$jobDir = $cfg["path"]["contenido"].$cfg['path']['cronjobs'];

// control logging, 1=use log file, 0=don't use log file
$useLog = 1;

// turn on / off debugging output
// DO NOT use this on live servers!
$debug = false;

/****************************************/
/*		don't change anything here		*/
/****************************************/

define("PC_MINUTE",	1);
define("PC_HOUR",	2);
define("PC_DOM",	3);
define("PC_MONTH",	4);
define("PC_DOW",	5);
define("PC_CMD",	7);
define("PC_CRONLINE", 8);

function logMessage($msg) {
	GLOBAL $writeDir, $useLog, $debug;
	if ($useLog==1) {
		$logfile = $writeDir."pseudo-cron.log";
		$file = fopen($logfile,"a");
		if ($msg[strlen($msg)-1]!="\n") {
			$msg.="\r\n";
		}
		if ($debug) echo $msg;
		fputs($file,date("r",time())."  ".$msg);
		fclose($file);
	}
}

function lTrimZeros($number) {
	GLOBAL $debug;
	while ($number[0]=='0') {
		$number = substr($number,1);
	}
	return $number;
}

function parseElement($element, &$targetArray, $numberOfElements) {
	GLOBAL $debug;
	$subelements = explode(",",$element);
	for ($i=0;$i<$numberOfElements;$i++) {
		$targetArray[$i] = $subelements[0]=="*";
	}
	
	for ($i=0;$i<count($subelements);$i++) {
		if (preg_match("~^(\\*|([0-9]{1,2})(-([0-9]{1,2}))?)(/([0-9]{1,2}))?$~",$subelements[$i],$matches)) {
			if ($matches[1]=="*") {
				$matches[2] = 0;		// from
				$matches[4] = $numberOfElements;		//to
			} elseif ($matches[4]=="") {
				$matches[4] = $matches[2];
			}
			if ($matches[5][0]!="/") {
				$matches[6] = 1;		// step
			}
			for ($j=lTrimZeros($matches[2]);$j<=lTrimZeros($matches[4]);$j+=lTrimZeros($matches[6])) {
				$targetArray[$j] = TRUE;
			}
		}
	}
}

function decDate(&$dateArr, $amount, $unit) {
	GLOBAL $debug;
	if ($debug) echo sprintf("Decreasing from %02d.%02d. %02d:%02d by %d %6s ",$dateArr[mday],$dateArr[mon],$dateArr[hours],$dateArr[minutes],$amount,$unit);
	if ($unit=="mday") {
		$dateArr["hours"] = 23;
		$dateArr["minutes"] = 59;
		$dateArr["seconds"] = 59;
		$dateArr["mday"] -= $amount;
		$dateArr["wday"] -= $amount % 7;
		if ($dateArr["wday"]<0) {
			$dateArr["wday"]+=7;
		}
		if ($dateArr["mday"]<1) {
			$dateArr["mon"]--;
			switch ($dateArr["mon"]) {
				case 0:
					$dateArr["mon"] = 12;
					$dateArr["year"]--;
					// fall through
				case 1:
				case 3:
				case 5:
				case 7:
				case 8:
				case 10:
				case 12:
					$dateArr["mday"] = 31;
					break;
				case 4:
				case 6:
				case 9:
				case 11:
					$dateArr["mday"] = 30;
					break;
				case 2:
					$dateArr["mday"] = 28;
					break;
			}
		}
	} elseif ($unit=="hour") {
		if ($dateArr["hours"]==0) {
			decDate($dateArr, 1, "mday");
		} else {
			$dateArr["minutes"] = 59;
			$dateArr["seconds"] = 59;
			$dateArr["hours"]--;
		}
	} elseif ($unit=="minute") {
		if ($dateArr["minutes"]==0) {
			decDate($dateArr, 1, "hour");
		} else {
			$dateArr["seconds"] = 59;
			$dateArr["minutes"]--;
		}
	}
	if ($debug) echo sprintf("to %02d.%02d. %02d:%02d\n",$dateArr[mday],$dateArr[mon],$dateArr[hours],$dateArr[minutes]);
}

function getLastScheduledRunTime($job) {
	GLOBAL $debug;
	$dateArr = getdate();
	$minutesBack = 0;
	while (
		$minutesBack<525600 AND 
		(!$job[PC_MINUTE][$dateArr["minutes"]] OR 
		!$job[PC_HOUR][$dateArr["hours"]] OR 
		(!$job[PC_DOM][$dateArr["mday"]] OR !$job[PC_DOW][$dateArr["wday"]]) OR
		!$job[PC_MONTH][$dateArr["mon"]])
	) {
		if (!$job[PC_DOM][$dateArr["mday"]] OR !$job[PC_DOW][$dateArr["wday"]]) {
			decDate($dateArr,1,"mday");
			$minutesBack+=1440;
			continue;
		}
		if (!$job[PC_HOUR][$dateArr["hours"]]) {
			decDate($dateArr,1,"hour");
			$minutesBack+=60;
			continue;
		}
		if (!$job[PC_MINUTE][$dateArr["minutes"]]) {
			decDate($dateArr,1,"minute");
			$minutesBack++;
			continue;
		}
	}

	if ($debug) print_r($dateArr);
	
	return mktime($dateArr["hours"],$dateArr["minutes"],0,$dateArr["mon"],$dateArr["mday"],$dateArr["year"]);
}

function getJobFileName($jobname) {
	GLOBAL $writeDir;
	GLOBAL $debug;
	$jobfile = $writeDir.urlencode($jobname).".job";
	return $jobfile;
}

function getLastActialRunTime($jobname) {
	GLOBAL $debug;
	$jobfile = getJobFileName($jobname);
	if (file_exists($jobfile)) {
		$file = fopen($jobfile,"r");
		$lastRun = fgets($file,100);
		fclose($file);
		if (is_numeric($lastRun)) {
			return $lastRun;
		}
	}
	return 0;
}

function markLastRun($jobname, $lastRun) {
	GLOBAL $debug;
	$jobfile = getJobFileName($jobname);
	$file = fopen($jobfile,"w");
	fputs($file,$lastRun);
	fclose($file);
}

function runJob($job) {
	GLOBAL $debug;
	GLOBAL $jobDir;
	$extjob = Array();
	$jobfile = getJobFileName($job[PC_CMD]);
	parseElement($job[PC_MINUTE], $extjob[PC_MINUTE], 60);
	parseElement($job[PC_HOUR], $extjob[PC_HOUR], 24);
	parseElement($job[PC_DOM], $extjob[PC_DOM], 31);
	parseElement($job[PC_MONTH], $extjob[PC_MONTH], 12);
	parseElement($job[PC_DOW], $extjob[PC_DOW], 7);
	
	$lastActual = getLastActialRunTime($job[PC_CMD]);
	$lastScheduled = getLastScheduledRunTime($extjob);
	
	if ($lastScheduled>$lastActual) {
		logMessage("Running 	".$job[PC_CRONLINE]);
		logMessage("  Last run:       ".date("r",$lastActual));
		logMessage("  Last scheduled: ".date("r",$lastScheduled));
		markLastRun($job[PC_CMD], $lastScheduled);
		if ($debug) {
			echo getcwd();
			include($jobDir.$job[PC_CMD]);		// display errors only when debugging
		} else {
			@include($jobDir.$job[PC_CMD]);		// any error messages are supressed
		}
		logMessage("Completed	".$job[PC_CRONLINE]);
		return true;
	} else {
		if ($debug) {
			logMessage("Skipping 	".$job[PC_CRONLINE]);
			logMessage("  Last run:       ".date("r",$lastActual));
			logMessage("  Last scheduled: ".date("r",$lastScheduled));
			logMessage("Completed	".$job[PC_CRONLINE]);
		}
		return false;
	}
}

function parseCronFile($cronTabFile) {
	GLOBAL $debug;
	$file = @file($cronTabFile);
	$job = Array();
	$jobs = Array();
	for ($i=0;$i<count($file);$i++) {
		if ($file[$i][0]!='#') {
//			old regex, without dow abbreviations:
//			if (preg_match("~^([-0-9,/*]+)\\s+([-0-9,/*]+)\\s+([-0-9,/*]+)\\s+([-0-9,/*]+)\\s+([-0-7,/*]+|Sun|Mon|Tue|Wen|Thu|Fri|Sat)\\s+([^#]*)(#.*)?$~i",$file[$i],$job)) {
			if (preg_match("~^([-0-9,/*]+)\\s+([-0-9,/*]+)\\s+([-0-9,/*]+)\\s+([-0-9,/*]+)\\s+([-0-7,/*]+|(-|/|Sun|Mon|Tue|Wed|Thu|Fri|Sat)+)\\s+([^#]*)(#.*)?$~i",$file[$i],$job)) {
				$jobNumber = count($jobs);
				$jobs[$jobNumber] = $job;
				if ($jobs[$jobNumber][PC_DOW][0]!='*' AND !is_numeric($jobs[$jobNumber][PC_DOW])) {
					$jobs[$jobNumber][PC_DOW] = str_replace(
						Array("Sun","Mon","Tue","Wed","Thu","Fri","Sat"),
						Array(0,1,2,3,4,5,6),
						$jobs[$jobNumber][PC_DOW]);
				}
				$jobs[$jobNumber][PC_CMD] = trim($job[PC_CMD]);
				$jobs[$jobNumber][PC_CRONLINE] = $file[$i];
			}
		}
	}
	if ($debug) var_dump($jobs);
	return $jobs;
}

if ($debug) echo "<pre>";
$jobs = parseCronFile($cronTab);
for ($i=0;$i<count($jobs);$i++) {
	runJob($jobs[$i]);
}
if ($debug) echo "</pre>";
?>