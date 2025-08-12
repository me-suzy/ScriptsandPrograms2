<?php
/***************************************
        Google News Grabber v2.1
          by jordie bodlay
      www.revolutiontech.com.au
    jbodlay@revolutiontech.com.au

Feel free to use for non-commercial reasons.
 Use of google content is subject to google 
     copyrights. Use at your own risk.

 I, jordie bodlay, take no responsibility
  for how this script is used by anyone.
 By downloading and using this script the 
downloader/user accepts any responsibility 
  for any copyright infringement or any 
              other misuse.


****************************************/

// required variables
$resultCount	= '7'; //maxes out at around 10
$siteURL		= 'http://news.google.com/?topic=w';
/*

Other URLs:
--------------------
Australian News		- http://news.google.com.au/nwshp?gl=au&topic=n
Canadian News		- http://news.google.com.au/news?ned=ca&topic=n
New Zealand News	- http://news.google.com.au/news?ned=nz&topic=n
U.S. News			- http://news.google.com.au/news?ned=us&topic=n

Business News		- http://news.google.com.au/news?topic=b
Science/Tech News	- http://news.google.com.au/news?topic=t
Entertainment News	- http://news.google.com.au/news?topic=e
Sports News			- http://news.google.com.au/news?topic=s

Own search term		- http://news.google.com.au/news?hl=en&ned=&q=<insert search term here>&btnG=Search+News
*/

/*

News Display
--------------------
$beforeRepeat - this is shown once before the news is looped through
$layoutRepeat - this is the format that the google news items will be shown in, 
				this is looped through for each item
				Use the following for news display:
				
				<google:url>		- this is the web address of the news item
				<google:headline>	- this is the headline of the news item
				<google:source>		- this is the name of the news source
				<google:time>		- this is the time it was posted e.g. 2 mins ago
				<google:description>- this is the short preview/description given by google

*/
$beforeRepeat	= '<table border=0 width=100% cellspacing=0 cellpadding=1>';
$layoutRepeat		= "<tr>	<td><font size=-1 face='times new roman'><b>&#149;</b></td>	<td><font size=-1 face='times new roman'><a href='<google:url>' target='_blank'><google:headline></a> - <google:source> (<google:time>)</td></tr>";
$afterRepeat	= '</table>';




// ------------ NO NEED TO CHANGE BELOW THIS ----------


function openSite($website){

	$readSite = "";
	// trick google into thinking we are just a normal user
	ini_set('user_agent','Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1)'); 

	if($openSite = fopen ($website,"r")){
    while(!feof($openSite)){
		$readSite  .= fread($openSite, 4096);
	}
	fclose($openSite);
	}else{

		echo "Couldn't open $website...";
	}
	return $readSite;

}


$google = openSite($siteURL);

preg_match_all("|<td valign=top><a href=\"(.*)\" id=(.*)><b>(.*)</b></a><br><font size=-1><font color=\#6f6f6f><b>(.*)&nbsp;-</font> <nobr>(.*)</nobr></b></font><br><font size=-1>(.*)</font>|Ui",$google,$result);

/*

Output info:
$result[0] - complete results, just junk, not always useable
$result[1] - Array of news URL's
$result[2] - Array of news id's (sed by google, best to ignore)
$result[3] - Array of news headlines
$result[4] - Array of news sources
$result[5] - Array of posted times
$result[6] - Array of news descriptions

*/

echo $beforeRepeat;

for ($i = 1; $i <= $resultCount; $i++) {	

	$tmpRepeat = str_replace("<google:url>", $result[1][$i], $layoutRepeat);
	$tmpRepeat = str_replace("<google:headline>", $result[3][$i], $tmpRepeat);
	$tmpRepeat = str_replace("<google:source>", $result[4][$i], $tmpRepeat);
	$tmpRepeat = str_replace("<google:time>", $result[5][$i], $tmpRepeat);
	$tmpRepeat = str_replace("<google:description>", $result[6][$i], $tmpRepeat);

	echo $tmpRepeat;

}

echo $afterRepeat;


?>