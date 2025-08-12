<?php

/******************************************************************************
File Name    : getcount.php
Description  : displays the counter in javascript format depending on the siteid
Author       : mike@mfrank.net (Mike Frank)
Date Created : March 24, 2004
Last Change  : April 7, 2004
Licence      : Freeware (GPL)
******************************************************************************/

include("config.php");
$id=$_GET["id"];
$t=$_GET["t"];
extract($HTTP_GET_VARS);
extract($HTTP_POST_VARS);
if (!$id) { header("location: index.php"); exit; }

$ip = $_SERVER["REMOTE_ADDR"];
$date = date("F d, Y");
$nl = 0;

$counter_file = "countdb/".$id."-c.db";
$log_file = "countdb/".$id."-l.db";
$date_file = "countdb/".$id."-t.db";
$counterfile="countdb/".$id.".db";

if (!file_exists($counterfile)) {
        print "document.write(\"ERROR-The site ID is not valid or no longer exists.\")";
        exit;
}

// open the counter file
$fp = fopen($counterfile,"r");
// read the previous count
$fdata = fgets($fp, filesize($counterfile)+1);
fclose($fp);

$fdata = split("{}", $fdata);
$owner = ereg_replace("owner:", "", $fdata[0]);         // owners name
$email = ereg_replace("email:", "", $fdata[1]);         // owners email
$created = ereg_replace("created:", "", $fdata[2]);     // counter creation date
$url = ereg_replace("url:", "", $fdata[3]);             // web site url
$count = ereg_replace("count:", "", $fdata[4])+1;       // current count
$style = ereg_replace("style:", "", $fdata[5]);         // counter style

$fdata2 = "owner:".$owner."{}email:".$email."{}created:".$created."{}url:".$url."{}count:".$count."{}style:".$style;
$fw = fopen($counterfile,"w");
$pageviews = $count;
$countnew = fputs($fw,$fdata2);
fclose($fw);

// determine if style is installed.
// with version 2.0, you can download digits
// from http://www.mfrank.net/work/fwcounters/
// or add your own.
if ((!file_exists($localdigitsdir.$style.".gif")) && ($style!="text") && ($style!="hidden")) { print "document.write(\"ERROR-Digits not found for ".$style.". Please contact site admin.\")"; exit; }

// if &t=1 then the user wants to see todays hits
if ($t=="1") {
	if (!file_exists($date_file)) { exit; }
	$th = fopen($date_file, "r");
	$pageviews = fgets($th, filesize($date_file)+1);
	fclose($th);
}

// print the counter
switch ($style) {
        case "text": print "document.write(\"$pageviews\")"; continue;
        case "hidden": print "// count: $pageviews"; continue;
        default: $lenght = strlen($pageviews);
                 for ($a = 0; $a <= $lenght-1; $a++) {
                        $number = $pageviews-10*floor($pageviews/10);
                        $pageviews = floor($pageviews/10);
                        $image[$lenght-$a] = $digitsdir.$style."/".$number.".gif";
                 }
                 echo "document.write(\"<a href='$linkpath' target='_blank'>";
                 for ($a = 1; $a <= $lenght; $a++)
                 echo "<img src='$image[$a]' border='0'>";
                 echo "</a>\")";
}

// this allows counters created on v1.8/1.9 to
// continue working without stats
if (!file_exists($counter_file)) { exit; }
$user = file($counter_file);

// open today file and add one
$tf = file($date_file);
for ($i = 0; $i < sizeof($tf); $i++) {
        $last = explode("|", $tf[$i]);
}

$tfw = fopen($date_file, "w");
fwrite ($tfw, $last[0]+1);
fclose ($tfw);
// close today.txt

for ($x=0; $x < sizeof($user); $x++) {
        $temp = explode("!", $user[$x]);
        $opp[$x] = $temp[0]."!".$temp[1]."!".$temp[2];
        $such = strstr($temp[0], $ip);
        if ($such) {
                $list[$nl] = $opp[$x];
                $nl++;
        }

        if($temp[1]!=$date) {
                $lfa = fopen($log_file, "a+");
                $logdata = fwrite($lfa, sizeof($user)."!".$temp[2]."!".$last[0]."!\n");
                fclose($lfa);

                // reset today.txt to 0
                $tf = fopen($date_file, "w");
                $totaldata = fwrite($tf, "1");
                fclose($tf);

                $cf = fopen($counter_file, "w");
                $counterdata = fwrite($cf, $ip."!".$date."!".$date."!\n");
                fclose($cf);
                break;
        }
}

if (!sizeof($list)!="0") {
        $cf = fopen($counter_file, "a+");
        $counterdata = fwrite($cf, $ip."!".$date."!".$date."!\n");
        fclose($cf);
}
?>

