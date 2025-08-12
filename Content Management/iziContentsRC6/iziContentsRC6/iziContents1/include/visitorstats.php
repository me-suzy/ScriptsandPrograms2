<?php

/***************************************************************************

 visitorstats.php
 -----------------
 copyright : (C) 2005 - The iziContents Development Team

 iziContents version : 1.0
 fileversion : 1.0.1
 change date : 23 - 04 - 2005
 ***************************************************************************/

/***************************************************************************
 The iziContents Development Team offers no warranties on this script.
 The owner/licensee of the script is solely responsible for any problems
 caused by installation of the script or use of the script.

 All copyright notices regarding iziContents and ezContents must remain intact on the scripts and in the HTML for the scripts.

 For more info on iziContents,
 visit http://www.izicontents.com*/

/***************************************************************************
 *
 *   This program is free software; you can redistribute it and/or modify
 *   it under the terms of the License which can be found within the
 *   zipped package. Under the licence of GPL/GNU.
 *
 ***************************************************************************/


function browser($agent)
{
	// Opera can masquerade as a number of different browsers, so we check for that first
	if (ereg("Opera", $agent)) { $browser = "Opera";
	} elseif ((ereg("Nav", $agent)) || (ereg("Gold", $agent)) || (ereg("X11", $agent)) || (ereg("Mozilla", $agent)) || (ereg("Netscape", $agent)) AND (!ereg("MSIE", $agent))) { $browser = "Netscape";
	} elseif (ereg("MSIE", $agent)) { $browser = "MSIE";
	} elseif (ereg("Lynx", $agent)) { $browser = "Lynx";
	} elseif (ereg("WebTV", $agent)) { $browser = "WebTV";
	} elseif (ereg("Konqueror", $agent)) { $browser = "Konqueror";
	} elseif (ereg("Safari", $agent)) { $browser = "Safari";
	} elseif ((eregi("bot", $agent)) || (ereg("Google", $agent)) || (ereg("Slurp", $agent)) || (ereg("Scooter", $agent)) || (eregi("Spider", $agent)) || (eregi("Infoseek", $agent))) { $browser = "Bot";
	} else { $browser = "Other"; }
	return $browser;
} // function browser()


function os($agent)
{
	if (ereg("Win", $agent)) { $os = "Windows";
	} elseif ((ereg("Mac", $agent)) || (ereg("PPC", $agent))) { $os = "Mac";
	} elseif (ereg("Linux", $agent)) { $os = "Linux";
	} elseif (ereg("FreeBSD", $agent)) { $os = "FreeBSD";
	} elseif (ereg("SunOS", $agent)) { $os = "SunOS";
	} elseif (ereg("IRIX", $agent)) { $os = "IRIX";
	} elseif (ereg("BeOS", $agent)) { $os = "BeOS";
	} elseif (ereg("OS/2", $agent)) { $os = "OS2";
	} elseif (ereg("AIX", $agent)) { $os = "AIX";
	} else { $os = "Other"; }
	return $os;
} // function os()


function country($dialhost)
{
	$domain["com"]			= "US Commercial";
	$domain["net"]			= "Network";
	$domain["org"]			= "US Organisation";
	$domain["edu"]			= "US Education";
	$domain["int"]			= "International (.int)";
	$domain["eu"]			= "European Union (.eu)";
	$domain["arpa"]			= "Old ARPA Network";
	$domain["gov"]			= "US Government";
	$domain["mil"]			= "US Miltary";
	$domain["reverse"]		= "Deprecated";
	$domain["localhost"]	= "Unknown";

	$temp = explode(".", $dialhost);
	$code = $temp[(count($temp)-1)];
	if (isset($domain[$code])) { $code = $domain[$code]; }

	return $code;
} // function country()


function visitor_stats()
{
	global $EZ_SESSION_VARS, $_SERVER;

	$surftool	= $_SERVER["HTTP_USER_AGENT"];
	$address	= $_SERVER["REMOTE_ADDR"];
	$domain		= gethostbyaddr($address);
	$osystem	= os($surftool);
	$surfer		= browser($surftool);
	$origin		= country(strtolower($domain));
	if (isset($_SERVER["HTTP_REFERER"])) {
		$refer	= explode("?", strtolower($_SERVER["HTTP_REFERER"]));
		$refer	= $refer[0];
	} else { $refer	= ''; }
	$visitisodate = dbDateTime(sprintf("%04d-%02d-%02d %02d:%02d:%02d", strftime("%Y"), strftime("%m"), strftime("%d"), strftime("%H"), strftime("%M"), strftime("%S")));

	$strQuery = "INSERT INTO ".$GLOBALS["eztbVisitorstats"]." VALUES('', '".$EZ_SESSION_VARS["Site"]."', '".$visitisodate."', '".$address."', '".$surftool."', '".$osystem."', '".$surfer."', '".$refer."', '".$origin."', 1)";
	$result = dbExecute($strQuery,true);
	dbCommit();
} // function visitor_stats()

?>
