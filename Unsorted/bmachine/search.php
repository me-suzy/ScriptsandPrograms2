<?php

/*
********************************************

BN Soft bMachine 2.7

Written by Kailash Nadh
http://bnsoft.net, support@bnsoft.net

bMachine - http://boastology.com

********************************************
*/

//##################### SEARCH ARTICLES script ##############
include_once "config.php";

if($db == "mysql") {  include_once "inc/mysql/search.mysql.php"; } else { include_once "inc/flat/search.flat.php"; }


// Some simple validation

	if($m_search=="0") { header("Location: index.php"); exit(); }

	if(!$_GET[key]) { $key=$_POST[key]; } else { $key=$_GET[key]; }
	if(!$_GET[item]) { $item=$_POST[item]; } else { $item=$_GET[item]; }

if(!trim($item) || trim($key) == "") {
		hdr("Search","");
		srchFrm($key);
		ftr("",""); exit(); }

//######################################

$ky=$key;
$key=trim($key);

hdr("Search Results: $ky","");
echo <<<EOF
<table border="0" cellpadding="4" cellspacing="0">
<tr><td>
EOF;

// Search for author, title or content

if($item=="title") { $w=0; }
if($item=="author") { $w=1; }
if($item=="content") { $w=2; }

srchFrm($key,$w); // Print the search form

echo <<<EOF
<hr color="gray" size="1">
<span class="content"><b>Search results for "$ky" [$item]</b></span><br><br></td></tr>
<tr><td>
<ul>
EOF;
doSearch("$item",$key); // Perform the search
echo "</ul></tr><td></table>";
ftr("",""); exit();

?>