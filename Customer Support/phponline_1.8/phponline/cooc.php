<?php
include_once('noca.php');
include_once('rcq.php');

$CCode = $HTTP_COOKIE_VARS['ccode'];

$RName = "";
$REmail = "";
$RUserName = "";

/************************************************************
You may add your own code here to find out information on a 
user depends on CCode value in cookie. Then fill these variables:

$RName
$REmail
$RUserName

************************************************************/

echo "t1=1&name=$RName&email=$REmail&username=$RUserName&t2=1";


?>