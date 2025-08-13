<?php
//global $admin, $thisfile;

$admin=1;
$thisfile="navigate";

include("../includes/config.php");
include("../includes/templ_lib.php");

#load template
$html="";

if($t)
	$html=parse("$t");
else
	$html=parse("navigate");

echo "$html";
if(!$pconnect)
	$conn->Close();
?>