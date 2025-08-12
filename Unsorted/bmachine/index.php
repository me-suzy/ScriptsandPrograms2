<?php

/*
********************************************

BN Soft bMachine 2.7

Written by Kailash Nadh
http://bnsoft.net, support@bnsoft.net

bMachine - http://boastology.com

********************************************
*/


//#############################################

include_once "config.php";

if($db == "mysql") {  include_once "inc/mysql/main.mysql.php"; } else { include_once "inc/flat/main.flat.php"; }

// Produce Printer friendly page
if($_GET["print"]) {
prnt(); exit();
}

if($_GET["rate"]) {
	// Check wheter voting is enabled
	if(!$m_vote) {
	header("Location: index.php?id=$_GET[f]"); exit();
	}

	// Flood prevention :)
	if($_COOKIE["bnRte"] == "$_GET[f]") { header("Location: index.php?id=$_GET[f]&bn=78Rtd0D00exz"); exit(); }
	setcookie("bnRte","$_GET[f]");

	$rn=trim($_GET[rate]);
	if($rn != "1" && $rn != "2" && $rn != "3" && $rn != "4" && $rn != "5")  { header("Location: index.php?id=$_GET[f]&bn=78Rtd0D00exz"); exit(); }
	rateit($rn,trim($_GET[f]));
	header("Location: index.php?id=$_GET[f]&bn=78Rtd0D00exz");
}

// Is the request for the FULL article or just the summary
if($_GET[id]) {
$fmt="full";
}

// Show articles posted on a specific date
if($_GET[show]) {
showbyDate($_GET[d],$_GET[m],$_GET[y]);
}

// Show the full article page without going through all the articles
if($fmt == "full") { doDat($_GET[id],""); } else { dfil(); }
ftr("$s_title","");


//#############################################
// Printer Friendly Function

function prnt() {
global $c_url, $c_urls;
$purl="$c_urls/index.php?id=$_GET[pg]";

$ppg=@fread(fopen("$purl", "r"), 1000000);
$titl=explode("[T]",$ppg); $titl=$titl[1];

// Find the article split string
list($na, $dat)=explode("<!-- startprint -->", $ppg);
list($ppg, $na)=explode("<!-- stopprint -->", $dat);

// Strip the image tags out
$ppg=eregi_replace("<img src=[^>]*>", "", $ppg);
$ppg=eregi_replace("<font[^>]*>", "", $ppg);

echo <<<EOF
<HTML><HEAD><TITLE>$titl</TITLE>
<link rel="stylesheet" href="style.css">
</HEAD><BODY>
$ppg
<span class="t_small">Article from : $c_url</span><br>
<span class="t_small">Printed from : $purl</span>
</BODY></HTML>
EOF;
exit();
}

?>