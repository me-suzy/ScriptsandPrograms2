<?php

//////////////////////////////////////////////////////////////////////////
//
//				Traffic Splitter 1.0 
//			      --------------------	
//			  A script by SmarterScripts.com
//
//  TERMS OF USE:
//  This is a free script, if anyone sold it to you please contact us
//  at the following e-mail address: webmaster@smarterscripts.com
//
//	 (c) copyright 2003 R3N3 Internet Services & SmarterScripts.com
//////////////////////////////////////////////////////////////////////////


//  Define Split Urls:
//  You can define as many or as little (minimum one) urls as you want.
//  To add another url simply add the line:
//  $split_url[] = "http://www.someurl.com";
//  under the last '$split_url[] = "....' value.
//  Removing urls is just as easy, just delete the entire line.

$split_url[] = "http://www.google.com";
$split_url[] = "http://www.dmoz.com";
$split_url[] = "http://www.altavista.com";

//  Default Split Percentage 
//  If the defualt is set to 90 than 90 percent of the time the script
//  will redirect to actual url (which is defined in the url itself)
//  and the other 10 percent of the time it redirect to a random url
//  predefined above. This value can be over-riden by adding a 
//  &p=[some value]  to the end of the split.php url where [some value]
//  is any number between 1 and 99.

$d_p =90;

//
//  DO NOT EDIT BELOW!
//

if (!$HTTP_GET_VARS["u"]) { $url = $_SERVER["QUERY_STRING"]; } else { $url = $HTTP_GET_VARS["u"]; }
$percentage = $HTTP_GET_VARS["p"];
$total_urls = count($split_url) - 1;

mt_srand ((double) microtime() * 1000000);
$rand_url_val = mt_rand(0,$total_urls);
$rand_split_val = mt_rand(1,100);

if(!$percentage or !ereg("^([0-9]{1,2})$", $percentage)) { $percentage = $d_p; }
if(!$url) { $url = $split_url[$rand_url_val]; }
if($percentage >= $rand_split_val) { header("Location: $url"); } else { header("Location: $split_url[$rand_url_val]"); }

?>


