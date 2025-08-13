<?php
@setcookie("CookiesOn",1,time()+60,"/","",0); //check for cookies
$thisfile="index";
include("includes/config.php");
include("includes/templ_lib.php");

#load template
$html="<HTML>No Template</HTML>";

if($t)
	$html=parse("$t");
else
	$html=parse("index");

eval("?>$html");
if(!$pconnect)
	$conn->Close();
?>