<?
#- Format ads for display

// Show the javascript to display the ads
function display_js($keyword="Home Page", $limit=8, $border="", $bgcolor="")
{
	global $P, $S;
	
	header("Content-type: text/javascript");
	
	$ads = display_ads($keyword, $limit, $border, $bgcolor);
	if(!$ads)
	{
		echo "// Nothing to display";
		exit;
	}
	
	// Break down ads by line 
	// CyKuH [WTN]
	$lines = explode("\n", $ads);
	foreach($lines as $line)
	{
		$line = rtrim($line);
		if($line)
			echo "document.write('$line');\n";
	}
	
	exit;
}


// Display a group of ads up to a max
function display_ads($keyword="", $limit=0, $border="", $bgcolor="")
{
	global $P, $S, $REMOTE_ADDR;

	// Get the keyword id
	$keyid = lib_getword($keyword);
	if(!$keyid)
		return("Error! Could not find or add '$keyword'");

	// Get the top 8 ads for this keyword
	$limit = iif($limit, $limit, $S[adlimit]);
	$ads = lib_getsql("SELECT a.id,a.ad FROM admap a, clients b 
				WHERE a.client=b.id AND a.keyword='$keyid' AND a.status=1 AND b.balance > 0
				ORDER BY bid DESC 
				LIMIT $S[adlimit]");
	if(!$ads[0][ad])
	{
		// Try for run of the site ads
		$ads = lib_getsql("SELECT a.id,a.ad FROM admap a, clients b 
				WHERE a.client=b.id AND a.keyword='0' AND a.status=1 AND b.balance > 0
				ORDER BY bid DESC 
				LIMIT $S[adlimit]");

		if(!$ads[0][ad])
		{
			$ads[0][ad] = "default";
			$ads[0][id] = 1;
		}
	}
	
	// Gather the HTML
	$html = array();
	$update = array();
	foreach($ads as $rec)
	{
		$html[] = display_ad($rec[ad], $border, $bgcolor, $rec[id]);
		$update[] = $rec[id];
	}

	// Build the adlist
	$ht  = "<!-- CyKuH [WTN] -->\n";
	$ht .= "<table width=200 border=0 cellspacing=0 cellpadding=0>\n";
	$ht .= "<tr>\n<td align=center>\n<font size=-1 color=#999999 face=Arial,Helvetica,sans-serif>Sponsored links</td>\n</tr>\n";
	$ht .= "<tr>\n<td>" . implode("<br></td></tr>\n<tr><td>", $html) . "</td>\n</tr>\n";
	$ht .= "<tr><td align=center><a href=$P[url]/ad.php?c=welcome><font size=-1 color=#999999 face=Arial,Helvetica,sans-serif>See your message here...</font></a>\n";
	$ht .= "</td>\n</tr>\n</table>\n";

	// Update the view counts
	if(count($update) > 0)
	{
		$inlist = implode(",", $update);
		lib_getsql("UPDATE admap SET views=views+1 WHERE id IN($inlist)");
	}

	return($ht);
} 


// Return HTML for a single AD
function display_ad($id="", $bordercolor="", $bgcolor="", $map=0)
{
	global $P, $S;

	// Set default colors
	$bordercolor = iif(!$bordercolor, $S[border], $bordercolor);
	$bgcolor = iif(!$bgcolor, $S[bgcolor], $bgcolor);

	// Grab the ad
	$a = lib_getsql("SELECT * FROM ads WHERE id='$id'");
	if(!$a[0][id])
		return("");

	// Format the data
	$lines = explode("|", htmlentities(stripslashes($a[0][description]), ENT_QUOTES));
	$l = "<font color=#333333>" . implode("<br>\n", $lines) . "</font>";
	$url = "$P[url]/ad.php?r=$id&m=$map";
	$showurl = $a[0][urlshow];
	$t = htmlentities(stripslashes($a[0][title]), ENT_QUOTES);

	// Render the html
	$html .= "<!-- CyKuH [WTN] -->\n";
	$html .= "<font face=Arial,Helvetica,sans-serif size=2>\n";
	$html .= "<table border=0 cellpadding=1 cellspacing=0 width=100%>\n";
	$html .= "<tr><td bgcolor=$bordercolor>\n";
	$html .= "<table width=100% border=0 cellspacing=0 cellpadding=5>\n";
	$html .= "<tr><td bgcolor=$bgcolor>\n";
	$html .= "<a href=$url><font size=2 face=Arial,Helvetica,sans-serif>$t</font></a><br>\n";
	$html .= "<font size=1 face=Arial,Helvetica,sans-serif>$l</font><br>\n";
	$html .= "<font size=2 face=Arial,Helvetica,sans-serif color=green>$showurl</font>\n";
	$html .= "</td></tr>\n"; 
	$html .= "</table>\n";
	$html .= "</td></tr>\n";
	$html .= "</table>\n";
	$html .= "</font>\n";

	return($html);
}

?>
