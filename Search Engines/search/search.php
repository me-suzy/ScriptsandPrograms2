<?php
	
	error_reporting(E_ALL & ~E_NOTICE);
	// Free script to search your site
	// Copyright 2004, Digital Point Solutions
	// version 1.2 - August 2, 2004
	//
	// Feel free to format the results however you see fit, the only thing we ask is that you leave a link to us in case others would like to add it to their site.
	// Links will be checked, and removal of the link could result it your website being blocked from using the service.
	// Other than that, have fun!  :)   - Shawn
	
	// Set your parameters here
	$key = "1234567890";			// This is your Google API key, if you don't have one, get one for free at:
									// https://www.google.com/accounts/NewAccount?continue=http://api.google.com/createkey&followup=http://api.google.com/createkey
	
	$site = "www.yoursite.com";		// This is the site you wish to search within
									// If Google has you indexed without "www.", don't specify "www." within your site URL.
									// 'http://' is *not* required in the site variable.
									// Examples:
									// www.cnn.com			search within www.cnn.com
									// www.cnn.com/tech/	search within www.cnn.com/tech/
									//
									// You can also leave it blank to search the entire web

	$spelling = 1;					// Change this to 0 if you do not want to check for spelling suggestions
									// Using this option makes searches twice as long, because it requires 2 queries instead of 1
	
	// Colors
	$color_border = "#000000";
	$color_title = "#B0B0FF";
	$color_odd_results = "#DDDDDD";
	$color_even_results = "#EEEEEE";


	$q = stripslashes ($HTTP_GET_VARS['q']);
	$start = 0 + $HTTP_GET_VARS['start'];

	include ("header.php"); // Edit this file to make it easy to fit into your site's look and feel

	ini_set ("allow_url_fopen", "1");
	if (!ini_get ("allow_url_fopen")) echo '<FONT COLOR=RED><B>Sorry, this PHP configuration does not allow for usage of <A HREF="http://www.php.net/manual/en/ref.filesystem.php#ini.allow-url-fopen" TARGET="_blank">fopen()</A>, which is required for this search engine script.</B></FONT><P>';

	// Let's get the results
	$handle = @fopen ("http://search.digitalpoint.com/?q=" . urlencode ($q) . "&key=" . urlencode ($key) . "&site=" . urlencode ($site) . "&start=" . min (990, $start) . "&spell=" . $spelling, "r");
	while (!feof ($handle) && $handle) {
		$line .= fgets ($handle, 1024);
	}
	fclose($handle);
		
	eval ('$urls = ' . $line . ';');
		
	echo '<TABLE BGCOLOR=' . $color_border . ' CELLSPACING=1 CELLPADDING=0><TR><TD><TABLE BGCOLOR=' . $color_odd_results . ' border=0 CELLSPACING=0 CELLPADDING=6><TR BGCOLOR=' . $color_title . '><TH COLSPAN=3>';
	echo '<TABLE border=0 WIDTH=100%><TR><TH WIDTH=80 ALIGN=CENTER VALIGN=MIDDLE><FONT SIZE=-1><A HREF="http://www.digitalpoint.com/tools/search/">Add Search<BR>To Your Site</A></FONT></TH><TH ALIGN=CENTER>';
	
	echo '&nbsp;&nbsp;';
	
	if ($urls['end'] - $urls['start'] < 9) $urls['results'] = $urls['end'];
	
	if ($urls['results'] > 10) {

		if ($start < 40) {
			$x = 0;
		} else {
			$x = round (max (0, min ($start - 40, $urls['results'] - 96) / 10));
		}
		for ($i = $x; $i < $x + 10; $i++) {
			if ($i != round ($start / 10)) {
				echo '<A HREF="search.php?q=' . urlencode($q) . '&start=' . ($i * 10) . '">' .  ($i + 1) . "</A>&nbsp;&nbsp;";
			} else {
				echo ($i + 1) . "&nbsp;&nbsp;";
			}
			if (max (10, ($i + 1) * 10) >= $urls['results']) break;
		}

		echo '<BR>';
	}
	
	echo '<FONT SIZE=+1>Estimated Total Results:  ' . number_format ($urls['results']) . '</FONT>';
	if ($urls['spelling']) echo '<br><font color="red">Did you mean: <a href="search.php?q=' . urlencode($urls['spelling']) . '">' . $urls['spelling'] . '</a></font>';

	echo '</TH><TH WIDTH=80>&nbsp;</TH></TR></TABLE>';

	$rownum = 1;
	
	$start = $urls['start'];
	if ($urls['error']) {
		echo "<TR COLSPAN=3><TD><BR><FONT SIZE=-1 COLOR=RED>" . $urls['error'] . "</FONT></TD></TR>";
	} else {
	
		foreach ($urls['urls'] as $key => $url) {
			$rownum++;
			echo "<TR";
			if ($rownum % 2 == 1) echo ' BGCOLOR=' . $color_even_results;
			echo "><TH ALIGN=RIGHT WIDTH=35>#" . ($key + $start) . ":&nbsp;</TH><TD><A HREF=\"$url\">" . $urls['titles'][$key] . "</A><BR>" . $urls['snippet'][$key] . "<BR><FONT SIZE=-1>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<B>$url</B></FONT></TD><TD>&nbsp;&nbsp;&nbsp;</TD></TR>";
		}
	}
	echo '</TABLE></TD></TR></TABLE>';

	include ("footer.php");

?>
