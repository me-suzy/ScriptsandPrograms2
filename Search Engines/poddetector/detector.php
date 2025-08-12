<?
#-------------------------------------------------#
# podDetector - search bot detector script 		  #
# Copyright (C) 2005 http://phpPod.com			  #
#-------------------------------------------------#

#--Replace you@yourwebsite.com with your own email address--#
$email = "you@yourwebsite.com";

#--Do not edit below--#
$date = date("j F Y");
$time = date("g:i a");

#--See if Google is crawling--#
if(eregi("google",$HTTP_USER_AGENT))
	{
	if ($QUERY_STRING != "")
		{$url = "http://".$SERVER_NAME.$PHP_SELF.'?'.$QUERY_STRING;}
	else
		{$url = "http://".$SERVER_NAME.$PHP_SELF;}
		
	mail("$email", "Googlebot crawled your site http://$SERVER_NAME", "Google crawled $url on $date at $time\n\n---------------------\nPowered by podDetector from http://phpPod.com");
	
	#--DO NOT REMOVE/EDIT THE COPYRIGHT NOTICE BELOW--#
	echo "<center>Welcome Googlebot! We are using <a href=\"http://www.phppod.com/poddetector.php\">podDetector</a> from <a href=\"http://www.phppod.com\">phpPod.com's free php script site!</a></center>";
	#--End of copyright--#
	}

#--See if MSN is crawling--#
if(eregi("msn",$HTTP_USER_AGENT))
	{
	if ($QUERY_STRING != "")
		{$url = "http://".$SERVER_NAME.$PHP_SELF.'?'.$QUERY_STRING;}
	else
		{$url = "http://".$SERVER_NAME.$PHP_SELF;}
		
	mail("$email", "MSN's bot crawled your site http://$SERVER_NAME", "MSN crawled $url on $date at $time\n\n---------------------\nPowered by podDetector from http://phpPod.com");
	
	#--DO NOT REMOVE/EDIT THE COPYRIGHT NOTICE BELOW--#
	echo "<center>Welcome MSN! We are using <a href=\"http://www.phppod.com/poddetector.php\">podDetector</a> from <a href=\"http://www.phppod.com\">phpPod.com's free php script site!</a></center>";
	#--End of copyright--#
	}
	
#--See if Yahoo is crawling--#
if(eregi("yahoo",$HTTP_USER_AGENT))
	{
	if ($QUERY_STRING != "")
		{$url = "http://".$SERVER_NAME.$PHP_SELF.'?'.$QUERY_STRING;}
	else
		{$url = "http://".$SERVER_NAME.$PHP_SELF;}
		
	mail("$email", "Yahoo's bot crawled your site http://$SERVER_NAME", "Yahoo crawled $url on $date at $time\n\n---------------------\nPowered by podDetector from http://phpPod.com");
	
	#--DO NOT REMOVE/EDIT THE COPYRIGHT NOTICE BELOW--#
	echo "<center>Welcome Yahoo! We are using <a href=\"http://www.phppod.com/poddetector.php\">podDetector</a> from <a href=\"http://www.phppod.com\">phpPod.com's free php script site!</a></center>";
	#--End of copyright--#
	}
?>