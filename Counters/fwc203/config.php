<?php

/******************************************************************************
File Name    : config.php
Description  : 
Author       : mike@mfrank.net (Mike Frank)
Date Created : March 24, 2004
Last Change  : April 14, 2004
Licence      : Freeware (GPL)
******************************************************************************/

$title="FWCounters";                                           // your counter service name
$copy="Copyright &copy; 2004 FrankWorld";                             // copyright details
$contactaddress="mike@mfrank.net";                                 // your email for contact info
$getcountpath="http://localhost/fwcounters/getcount.php";    // getcount.php full path
$digitsdir="http://localhost/fwcounters/digits/";            // full dir to digits
$linkpath="http://localhost/fwcounters";                     // full main dir

// add below if upgrading from v1.9/1.8 to v2.0
$adminpass="hello";						// admin password (not yet used, but is required for max. security)
$maxcounters=100;						// max counters per account (0=unlimited)
$showgraph=1;							// use graph in stats (requires gd library)
$localdigitsdir="digits/";					// dir to digits
$mailsubject="Welcome to $title";				// sign up - subject
$mailfromaddr="mail@mfrank.net";					// sign up - from who
$mailfromname="Micheal Frank\n".$linkpath;			// this shows up on the bottom of the email
$informadmin=0;						// inform the admin on new signups (emails to $contactaddress)
?>