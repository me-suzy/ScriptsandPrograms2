<?php

// door: Bert Deelman
// bert add besigners dot nl
// 18-2-2005 21:02

$GLOBALS["rootdp"] = './';

require_once ($GLOBALS["rootdp"]."include/config.php");
require_once ($GLOBALS["rootdp"]."include/rssfunctions.php");
require_once ($GLOBALS["rootdp"]."include/db.php");

$livesite = dirname($_SERVER["SCRIPT_URI"]);
$domain = $_SERVER["SERVER_NAME"];
$rest = substr($domain, 4); // returns domain - www

// XML starts here
$charsets = explode(',',$GLOBALS["gsCharset"]);
$charset = $charsets[0];
$date = date("r"); 
$SiteDesc = $GLOBALS["gsSitedesc"];
$SiteTitle = $GLOBALS["gsSitetitle"];
$version = $GLOBALS["Version"];
$logo = $GLOBALS["gsHomepageLogo"];
$adminemail = GetAdminEmail();

function GetAdminEmail() {
	$strQuery = "SELECT authoremail FROM ".$GLOBALS["eztbPrefix"].$GLOBALS["eztbAuthors"]." WHERE login='admin'";
	$result = dbRetrieve($strQuery,true,0,0);
	$rs		= dbFetch($result);
	$aemail = $rs["authoremail"];
	return $aemail;
}

header('Content-type: application/xml');

echo "<?xml version=\"1.0\" encoding=\"windows-1252\"?>\n";
echo "<?xml-stylesheet title=\"CSS_formatting\" type=\"text/css\" href=\"./themes/rss.css\"?>\n";
echo "<?xml-stylesheet title=\"XSL_formatting\" type=\"text/xsl\" href=\"./themes/rss2html.xsl\"?>\n";
echo "<!-- Generator = $version -->\n"; 
echo "<rss version=\"2.0\">\n";
echo "<channel>\n";
echo "<copyright>besigners</copyright>\n";
echo "<lastBuildDate>$date</lastBuildDate>\n"; 
echo "<description>$SiteDesc</description>\n";
echo "<title>$SiteTitle</title>\n";
echo "<link>http://$domain</link>\n"; 
echo "<managingEditor>$adminemail</managingEditor>\n"; 
echo "<webMaster>$adminemail</webMaster>\n"; 
echo "<language>en-EN</language>\n"; 
echo "<image>\n"; 
echo "<url>http://$domain$livesite/contentimage/$logo</url>\n"; 
echo "<title>$SiteTitle</title>\n";
echo "<link>http://$domain</link>\n"; 
echo "</image>\n"; 

define('include_new',0);
define('include_updated',1);

if (isset($_POST["ezSID"])) { $_GET["ezSID"] = $_POST["ezSID"]; }
if (isset($_POST["topgroupname"])) { $_GET["topgroupname"] = $_POST["topgroupname"]; }
if (isset($_POST["groupname"])) { $_GET["groupname"] = $_POST["groupname"]; }
if (isset($_POST["subgroupname"])) { $_GET["subgroupname"] = $_POST["subgroupname"]; }
if (isset($_POST["contentname"])) { $_GET["contentname"] = $_POST["contentname"]; }
if (isset($_POST["page"])) { $_GET["page"] = $_POST["page"]; }
if (isset($_POST["link"])) { $_GET["link"] = $_POST["link"]; }
if (isset($_POST["ref"])) { $_GET["ref"] = $_POST["ref"]; }
if (isset($_POST["noframesbrowser"])) { $_GET["noframesbrowser"] = $_POST["noframesbrowser"]; }

$_POST["include_array"] = array(include_new,include_updated);

$aantaldagen = 7; // aantal dagen terug, voor testen

$timenow = time();
$isodate = strftime("%Y-%m-%d",$timenow)." 00:00:00";
$whatsnewisodate = strftime("%Y-%m-%d",DateSub("d",$aantaldagen,$timenow))." 00:00:00";

$newquery = "(publishdate >= '$whatsnewisodate')";
$updatedquery = "(updatedate >= '$whatsnewisodate')";

// Query voor content
$strQueryContent = "SELECT * FROM ".$GLOBALS["eztbPrefix"].$GLOBALS["eztbContents"]." WHERE (".$newquery. " OR ".$updatedquery.") AND (contentactive='1' AND rssvisible='Y')";

//echo $strQueryContent;
if ($GLOBALS["gsLanguage"] == $GLOBALS["gsDefault_language"]) {
	$strQueryContent .= " AND language='".$GLOBALS["gsLanguage"]."' ORDER BY contentname";
	} else {
		$lOrder = '';
		if ($GLOBALS["gsLanguage"] > $GLOBALS["gsDefault_language"]) { 
			$lOrder = ' DESC'; 
		}
		$strQueryContent .= " AND (language='".$GLOBALS["gsLanguage"]."' OR language='".$GLOBALS["gsDefault_language"]."') ORDER BY contentname,language".$lOrder;
}

$result = dbRetrieve($strQueryContent,true,0,0);
$results_found = dbRowsReturned($result);
$results_displayed = 0;
if ($results_found != 0) {
	$nContentName = '';
	while ($rsContent = dbFetch($result)) {
		if ($rsContent["contentname"] != $nContentName) {
			if (ModArticleSecurity($nContentName,$rsContent)) {
				if ((in_array(include_updated, $_POST["include_array"])) && ($rsContent["updatedate"] >= $whatsnewisodate)) { $whatsnewstat = 'updated'; }
				if ((in_array(include_new, $_POST["include_array"])) && ($rsContent["publishdate"] >= $whatsnewisodate)) { $whatsnewstat = 'new'; }
				switch ($whatsnewstat) {
					case 'new'     : $rsContent["title"] = $rsContent["title"];
							 break;
					case 'updated' : $rsContent["title"] = "[update] ".$rsContent["title"];
							 break;
				}

				echo "<item>\n";
				makeTitle ($rsContent["title"]);

				if (trim($rsContent["cteaser"]) != '') {
					makeString ($rsContent["cteaser"]);					
				}
				else { 
					makeString ($rsContent["cbody"]); 
				}
				
				echo "<link>";
				$topgroup = mGetTopGroupName($rsContent["groupname"]);
				$link = "http://".$domain.$livesite."/control.php?&topgroupname=".$topgroup."&groupname=".$rsContent["groupname"]."&subgroupname=".$rsContent["subgroupname"]."&contentname=".$rsContent["contentname"];
				
				echo htmlspecialchars("$link", ENT_QUOTES);
				echo "</link>\r</item>\n";
				$results_displayed++;
			}
			$nContentName = $rsContent["contentname"];
		}
	} // end while
} // if ($results_found != 0)


echo "</channel>\r</rss>\n";	

dbFreeResult($result);

function makeTitle($line) {

	$line = ereg_replace( '(<(/)*b>)|(<(/)*u>)|(<(/)*i>)', '', $line );    // bold,under,italic
	echo "<title>".$line."</title>\n";
}
	
function makeString ($line) {

	$line = preg_replace("'<script[^>]*>.*?</script>'si","",$line);
	$line = preg_replace('/<a\s+.*?href="([^"]+)"[^>]*>([^<]+)<\/a>/is','\2 (\1)', $line);
	$line = preg_replace('/<!--.+?-->/','',$line);
	$line = preg_replace('/{.+?}/','',$line);
	$line = preg_replace('/&nbsp;/',' ',$line);
	$line = preg_replace('/&amp;/',' ',$line);
	$line = preg_replace('/&quot;/',' ',$line);
	$line = strip_tags($line);
	
	echo ("<description>".substr($line,0,200)."...</description>"."\n");
}

?>