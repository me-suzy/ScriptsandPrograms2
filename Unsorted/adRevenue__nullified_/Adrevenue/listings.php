<?
// Manage Listings

// Are we logged in?
if(!$user[id])
{
	lib_redirect("You must login first!", "ad.php?c=login", 3);
	exit;
}

// Cancel the current command
if($cancel)
	$go = "";


// Delete a keyword from the admap
if($go == "xw" && $id && $ad)
{
	$map = lib_getsql("SELECT views, clicks FROM admap WHERE id='$id' AND client='$user[id]'");
	if($map[0][clicks])
	{
		lib_getsql("UPDATE admap SET status=0 WHERE id='$id' AND client='$user[id]' AND ad='$ad'");
		lib_redirect("Your item was marked for deletion.", "ad.php?c=ads&ad=$ad",2);
	}
	else
	{
		lib_getsql("DELETE FROM admap WHERE id='$id' AND client='$user[id]'");
		lib_redirect("Your item was deleted.", "ad.php?c=ads&ad=$ad", 2);
	}

	return("");
}

// Add a new keyword to the admap
if($go == "aw" && $ad && $word)
{
	// Check for the keyword id	
	$id = lib_getword($word);

	// Do we already have this keyword in the admap?
	if($id)
	{
		$map = lib_getsql("SELECT id FROM admap WHERE keyword='$id' AND ad='$ad' AND client='$user[id]'");
		if($map[0][id])
			lib_redirect("You already added that keyword: <i>$word</i>", "ad.php?c=ads&ad=$ad",3);
	}

	if(!$id)
	{
		lib_redirect("Sorry, we could not add that word.", "ad.php?c=ads&ad=$ad",2);
		exit;
	}
	else
	{
		// Check if the have a CPC for this word
		$word = lib_getsql("SELECT * FROM keywords WHERE id='$id'");
		if($word[0][cpc] > $S[cpc])
			$cpc = $word[0][cpc];
		else
			$cpc = $S[cpc];

		// Save the data
		$i = array();
		$i[client] = $user[id];
		$i[ad] = $ad;
		$i[keyword] = $id;
		$i[bid] = $cpc;
		lib_insert("admap", $i);
	}
	
	lib_redirect("Your item was added successfully.", "ad.php?c=ads&ad=$ad",2);
	exit;
}

// Create a new ad
if($go == "new" || ($go == "edit" && $ad))
{
	if($f[url] && $f[title] && $f[urlshow] && $f[desc1])
	{
		$i = array();
		$i[client] = $user[id];
		$i[date] = time();
		$i[url] = $url;
		$i[urlshow] = $urlshow;
		$i[title] = substr($f[title], 0,25);
		$i[description] = substr($f[desc1], 0,35) . "|" . substr($f[desc2],0,35) . "|" . substr($f[desc3],0,35);
		$i[urlshow] = $f[urlshow];
		$i[url] = substr($f[url],0,1024);

		if($go == "new")
		{
			$i[id] = uniqid("");
			$i[status] = 1;
			$id = lib_insert("ads", $i);
			lib_redirect("Your listing was added successfully.<br>Continue to add keywords.", 
					"ad.php?c=ads&ad=$i[id]", 4);
			exit;
		}
		else
		{
			lib_update("ads", "id", $ad, $i);
			lib_redirect("Your listing was updated successfully.", "ad.php?c=ads&ad=$ad", 4);
			exit;
		}	

	}

	// Is this an edit?
	if(!$f[url] && $ad && $go == "edit")
	{
		$data = lib_getsql("SELECT * FROM ads WHERE id='$ad'");
		$f = $data[0];
		$parts = explode("|", $f[description]);
		$f[desc1] = $parts[0];
		$f[desc2] = $parts[1];
		$f[desc3] = $parts[2];
	}

	// Show the form
	$f[url] = iif(!$f[url], "http://", $f[url]);
	$f[maxcpc] = iif(!$f[maxcpc], $S[cpc], $f[maxcpc]);

	$out = "<table width=100% border=0 cellpadding=0 cellspacing=4>
		<form method=post>
		<font color=red class=contentmedium>$errormsg</font>
		<tr><td colspan=2><font size=+1><b>Create a new Listing</b></font></td></tr>
		<tr><td>&nbsp;</td></tr>

		<tr>
			<td class=content>
				<b>Headline:</b> <font size=1>(max 25 characters)</font><br>
				<input type=text name=f[title] value=\"$f[title]\" size=25 maxlength=25 class=content><br>&nbsp;
			</td>
		</tr>

		<tr>
			<td class=content>
				<b>Description line 1:</b> <font size=1>(max 35 characters)</font><br>
				<input type=text name=f[desc1] value=\"$f[desc1]\" size=35  maxlength=35 class=content><br>&nbsp;

			</td>
		</tr>

		<tr>
			<td class=content>
				<b>Description line 2:</b> <font size=1>(max 35 characters)</font><br>
				<input type=text name=f[desc2] value=\"$f[desc2]\" size=35 maxlength=35 class=content><br>&nbsp;

			</td>
		</tr>

		<tr>
			<td class=content>
				<b>Description line 3:</b> <font size=1>(max 35 characters)</font><br>
				<input type=text name=f[desc3] value=\"$f[desc3]\" size=35 maxlength=35 class=content><br>&nbsp;

			</td>
		</tr>

		<tr>
			<td class=content>
				<b>URL to display:</b> <font size=1>(max 35 characters)</font><br>
				<b>http://</b><input type=text name=f[urlshow] value=\"$f[urlshow]\" size=35 maxlength=35 class=content><br>&nbsp;

			</td>
		</tr>

		<tr>
			<td class=content>
				<b>URL to link to :</b> <font size=1>(max 1024 characters)</font><br>
				<input type=text name=f[url] value=\"$f[url]\" size=35 maxlength=1024 class=content><br>
				<font size=1>
				Your listing will link users to this URL which may<br>be different from the
				above <i>URL to display</i>.<br>This can also be a tracking URL.
				</font>
			</td>
		</tr>

		<tr>
			<td class=content>
				&nbsp;<br>
				<input type=submit value=\"Save Ad\">
			</td>
		</tr>

		<input type=hidden name=c value=ads>
		<input type=hidden name=go value=\"$go\">
		<input type=hidden name=ad value=\"$ad\">

		</form>
		</table>";
        lib_main($out,"Retrieve your login information");
        exit;
}


// Show an ad and its keyword screen
if($ad)
{
	$html = display_ad($ad); 
	if(!$html)
		lib_redirect("Could not find that ad","ad.php",1);

	$ht  = "<font size=+1><b>Edit Your Advertisement</b></font><br>\n";
	$ht .= "<font size=2><a href=ad.php?c=ads>Go back to Ad List</a><p>\n";
	$ht .= "<table width=200 border=0 cellspacing=0 cellpadding=0>\n";
	$ht .= "<tr><td bgcolor=#FFFFFF>$html</td></tr>\n";
	$ht .= "<tr><td bgcolor=#FFFFFF align=center>\n";
	$ht .= "<font size=1><a href=ad.php?c=ads&go=edit&ad=$ad>Edit this ad</a></font>";
	$ht .= "</td></tr>\n";
	$ht .= "</table>\n<p>&nbsp;";

	// Get keywords or categories
	$title = iif($S[adtype] == "page", "Categories", "Keywords");
	$keywords = lib_getsql("SELECT a.id, a.bid, a.clicks, a.views, b.keyword, a.status 
				FROM admap a, keywords b 
				WHERE a.keyword=b.id AND a.ad='$ad' 
				ORDER BY a.views, a.clicks");
	// Show them
	if(count($keywords) > 0)
	{
		$ht .= "<table width=500 border=0 cellspacing=0 cellpadding=0>\n";
		$ht .= "<tr><td bgcolor=#BBBBBB>\n";
		$ht .= "<table width=100% border=0 cellspacing=1 cellpadding=3>\n";
		$ht .= "<tr>\n";
		$ht .= "<td bgcolor=EEEEEE><font size=2><b>$title</b></font></td>\n";
		$ht .= "<td bgcolor=EEEEEE align=right><font size=2><b>Views</b></font></td>\n";
		$ht .= "<td bgcolor=EEEEEE align=right><font size=2><b>Clicks</b></font></td>\n";
		$ht .= "<td bgcolor=EEEEEE align=right><font size=2><b>CTR</b></font></td>\n";
		$ht .= "<td bgcolor=EEEEEE align=right><font size=2><b>CPC</b></font></td>\n";
		$ht .= "<td bgcolor=EEEEEE align=right><font size=2><b>Cost</b></font></td>\n";
		$ht .= "<td bgcolor=EEEEEE><font size=2>&nbsp;</font></td>\n";
		$ht .= "</tr>\n";

		foreach($keywords as $rec)
		{
			if($rec[clicks] > 0 && $rec[views] > 0)
				$ratio = $rec[clicks] * 100 / $rec[views];
			else
				$ratio = 0;

			$ratio = number_format($ratio,2) . " %";
			$rec[bid] = number_format($rec[bid],2);

			$color = "black";
			$menu  = "<a href=ad.php?c=ads&go=xw&id=$rec[id]&ad=$ad>Delete</a>";
			if($rec[status] == 0)
			{
				$color = "red";
				$menu  = "Deleted";
			}

			$rec[amount] = number_format($rec[clicks] * $rec[bid], 2);
			if($rec[clicks])
				$url = "<a href=ad.php?c=report_detail&f[item]=$rec[id] title='View Stats'>$rec[keyword]</a>";
			else
				$url = $rec[keyword];

			$ht .= "<tr>\n";
			$ht .= "<td bgcolor=FFFFFF><font size=1 color=$color>$url</font></td>\n";
			$ht .= "<td bgcolor=FFFFFF align=right><font size=1 color=$color>$rec[views]</font></td>\n";
			$ht .= "<td bgcolor=FFFFFF align=right><font size=1 color=$color>$rec[clicks]</font></td>\n";
			$ht .= "<td bgcolor=FFFFFF align=right><font size=1 color=$color>$ratio</font></td>\n";
			$ht .= "<td bgcolor=FFFFFF align=right><font size=1 color=$color>\$ $rec[bid]</font></td>\n";
			$ht .= "<td bgcolor=FFFFFF align=right><font size=1 color=$color>\$ $rec[amount]</font></td>\n";
			$ht .= "<td bgcolor=FFFFFF align=center><font size=1>$menu</font></td>\n";
			$ht .= "</tr>\n";
		}

		$ht .= "</table>\n</td>\n</tr>\n</table>\n";		
	}

	// Show the form to add another
	if($S[adtype] == "page")
	{
		$wlist = lib_db_htlist("keywords", "keyword", "keyword");
		$kfield = "<select name=word>$wlist</select>";
	}
	else
	{
		$kfield = "<input type=text name=word size=30>\n";
	}

	$ht .= "<hr size=1>\n";
	$ht .= "<font size=2><b>Add $title</b></font><br>\n";
	$ht .= "<table border=0 cellspacing=0 cellpadding=0>\n";
	$ht .= "<tr><td bgcolor=#CCCCCC>\n";
	$ht .= "<table width=100% border=0 cellspacing=1 cellpadding=3>\n";
	$ht .= "<form method=post>\n";
	$ht .= "<tr>\n";
	$ht .= "<td bgcolor=#EEEEEE width=1><font size=2><b>$title</b></font></td>\n";
	$ht .= "<td bgcolor=#EEEEEE><font size=2>&nbsp;</font></td>\n";
	$ht .= "</tr>\n";
	$ht .= "<tr>\n";
	$ht .= "<td bgcolor=#FFFFFF width=1><font size=2><b>$kfield</b></font></td>\n";
	$ht .= "<td bgcolor=#FFFFFF><font size=2><input type=submit value=Add></font></td>\n";
	$ht .= "</tr>\n";
	$ht .= "<input type=hidden name=c value=ads>\n";
	$ht .= "<input type=hidden name=go value=aw>\n";
	$ht .= "<input type=hidden name=ad value=\"$ad\">\n";
	$ht .= "</form>\n";
	$ht .= "</table>\n";
	$ht .= "</td></tr></table>\n";

	lib_main($ht, "Manage Ads");

	exit;
}
 
// Show the list of your ads
$ads = lib_getsql("SELECT * FROM ads WHERE client='$user[id]' ORDER BY title");
if($ads[0][id])
{
	// Grab the balance from the accounts file
	$cust = lib_getsql("SELECT balance FROM clients WHERE id='$user[id]'");
	
	$balance = number_format($cust[0][balance],2);	

	// Show the Account Balance
	$ht .= "<font size=+1><b>Account Information</b></font><br>&nbsp;\n";
	$ht .= "<table width=100% border=0 cellspacing=0 cellpadding=3>\n";
	$ht .= "<tr>\n";
	$ht .= "	<td width=1 valign=bottom><font size=3>Balance:</font></td>\n";
	$ht .= "	<td width=1 valign=bottom><font size=3><b>\$$balance</b></font></td>\n";
	$ht .= "	<td valign=middle><font size=1><a href=ad.php?c=funds>Add Funds</a></font></td>\n";
	$ht .= "</tr>\n";

	$ht .= "</table>\n";	

	$ht .= "<hr size=1\n";

	// Show the list of ads
	$ht .= "<font size=+1><b>Manage Ads</b></font><br>\n";
	$ht .= "<a href=ad.php?c=ads&go=new>Create a new Ad</a>\n";
	$ht .= "<br>&nbsp;\n";
	$ht .= "<table width=100% border=0 cellspacing=0 cellpadding=0>\n";
	$ht .= "<tr><td bgcolor=#CCCCCC>\n";
	$ht .= "<table width=100% border=0 cellspacing=1 cellpadding=3>\n";
	$ht .= "<tr>\n";
	$ht .= "<td bgcolor=#EEEEEE><b><font size=2>Description</font></b></td>\n";
	$ht .= "<td bgcolor=#EEEEEE align=right><b><font size=2>Views</font></b></td>\n";
	$ht .= "<td bgcolor=#EEEEEE align=right><b><font size=2>Clicks</font></b></td>\n";
	$ht .= "<td bgcolor=#EEEEEE align=right><b><font size=2>CTR</font></b></td>\n";
	$ht .= "<td bgcolor=#EEEEEE align=right><b><font size=2>Avg.&nbsp;CPC</font></b></td>\n";
	$ht .= "<td bgcolor=#EEEEEE align=right><b><font size=2>Cost</font></b></td>\n";
	$ht .= "</tr>\n";

	foreach($ads as $rec)
	{
		// Get the number of clicks and views
		$stats = lib_getsql("SELECT sum(clicks) as clicks, sum(views) as views, 
					avg(bid) as cpc, sum(clicks*bid) as cost FROM admap WHERE ad='$rec[id]'");

		$rec[maxcpc] = number_format($rec[maxcpc]);
		$clicks = number_format($stats[0][clicks],2);
		$views  = number_format($stats[0][views],2);
		$ctr    = number_format($stats[0][clicks] * 100 / iif($stats[0][views]==0,1,$stats[0][views]),2);
		$cpc    = number_format($stats[0][cpc],2);
		$rec[title] = "<a href=ad.php?c=ads&ad=$rec[id]>$rec[title]</a>";
		$cost = number_format($stats[0][cost],2);


		$ht .= "<tr>\n";
		$ht .= "<td bgcolor=#FFFFFF><font size=2>$rec[title]</font></td>\n";
		$ht .= "<td bgcolor=#FFFFFF align=right><font size=2>$views</font></td>\n";
		$ht .= "<td bgcolor=#FFFFFF align=right><font size=2>$clicks</font></td>\n";
		$ht .= "<td bgcolor=#FFFFFF align=right><font size=2>$ctr %</font></td>\n";
		$ht .= "<td bgcolor=#FFFFFF align=right><font size=2>\$$cpc</font></td>\n";
		$ht .= "<td bgcolor=#FFFFFF align=right><font size=2>\$$cost</font></td>\n";
		$ht .= "</tr>\n";
	}

	$ht .= "</table>\n";
	$ht .= "</td></tr></table>\n";

	lib_main($ht, "Manage Your Ads");
	exit;
}
else
{
	// Jump directly to creating a new ad
	lib_redirect("You have no ads. Please create a new ad.", "ad.php?c=ads&go=new",5);	
}

?>
