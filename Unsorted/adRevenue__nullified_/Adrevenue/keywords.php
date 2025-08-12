<?
// Manage Keywords
if($user[email] != "admin")
	lib_redirect("Access Denied!", "ad.php",3);

$wtitle = iif($S[adtype] == "page", "Category", "Keyword");

// Delete a keyword
if($go == "x" && $id)
{
	$d = lib_getsql("SELECT * FROM admap WHERE keywordid='$id'");
	if(!$d[id])
	{
		lib_getsql("DELETE FROM keyword WHERE id='$id'");
		lib_redirect("Your Keyword/Category was deleted","ad.php?c=keyword&filter=$filter");
	}
	lib_redirect("Your $wtitle is being used. It cannot be deleted", "ad.php?c=keyword&filter=$filter");
}

// Add / Update keyword
if($go == "update" || $go == "add")
{
	if($f[keyword] && $f[cpc])
	{
		$i = array();
		$i[keyword] = stripslashes($f[keyword]);
		$i[cpc] = $f[cpc];
		if($go == "add")
		{
			$there = lib_getsql("SELECT id FROM keywords WHERE keyword='$f[keyword]'");
			if(!$there[0][id])
			{
				lib_insert("keywords", $i);
				lib_redirect("Your $wtitle was added.", "ad.php?c=keywords&filter=$filter",3);
			}
			lib_redirect("That $wtitle already exists","ad.php?c=keywords&filter=$filter",3);
		}
		else
		{
			lib_update("keywords", "id", $f[id], $i);
			lib_redirect("Your $wtitle was updated","ad.php?c=keywords&filter=$filter",3);
		}
	}

	// Show the form
	$title = "Add $wtitle";
	if($f[id])
	{
		$word = lib_getsql("SELECT * FROM keywords WHERE id='$f[id]'");
		$f = $word[0];
		$title = "Edit $wtitle";
	}

	$out = "<font size=+1><b>$title</b></font><p>";
	$out .= "<table width=100% border=0 cellspacing=0 cellpadding=3>\n";
	$out .= "<form method=get>\n";
	$out .= "<tr>\n";
	$out .= "<td width=1><font size=2 face=Verdana,Arial,Helvetica,sans-serif><b>$wtitle:</b></font></td>\n";
	$out .= "<td><input type=text name=f[keyword] value=\"$f[keyword]\" size=30></td>\n";
	$out .= "</tr>\n"; 
	$out .= "<tr>\n";
	$out .= "<td width=1><font size=2 face=Verdana,Arial,Helvetica,sans-serif><b>CPC:</b></font></td>\n";
	$out .= "<td><input type=text name=f[cpc] value=\"$f[cpc]\" size=10></td>\n";
	$out .= "</tr>\n"; 
	$out .= "<tr>\n";
	$out .= "<td width=1><font size=2>&nbsp;</font></td>\n";
	$out .= "<td><input type=submit value=Save></td>\n";
	$out .= "</tr>\n"; 
	$out .= "<input type=hidden name=c value=keywords>\n";
	$out .= "<input type=hidden name=go value=$go>\n";
	$out .= "<input type=hidden name=f[id] value=\"$f[id]\">\n";
	$out .= "<input type=hidden name=filter value=\"$filter\">\n";
	$out .= "</form>\n";
	$out .= "</table>\n";

	lib_main($out, "Edit $wtitle");
	exit;
}

// List up to 1000 keywords
if($filter)
	$words = lib_getsql("SELECT * FROM keywords WHERE keyword LIKE '%$filter%' ORDER BY keyword LIMIT 500");
else
	$words = lib_getsql("SELECT * FROM keywords ORDER BY keyword LIMIT 500");

$wt = iif($S[adtype] == "page", "Categories", "Keywords");

$out  = "<font size=+1><b>Manage $wt</b></font><p>\n";
$out .= "<table width=100% border=0 cellspacing=0 cellpadding=2>
		<form method=get>
		<tr>
			<td width=1><font size=2 face=Verdana,Arial,Helvetica,sans-serif><b>Find:</b></font></td>
			<td width=1><input type=text name=filter value=\"$filter\" size=20></td>
			<td width=1><input type=submit value=Go!></td>
			<td>&nbsp;&nbsp;<font size=1 face=Verdana,Arial,Helvetica,sans-serif>
				<a href=ad.php?c=keywords&go=add>Add a $wtitle</a></font>
			</td>
		</tr>
		<input type=hidden name=c value=keywords>
		</form>
	 </table>&nbsp;<br>
	 <table width=400 border=0 cellspacing=0 cellpadding=0>
	 <tr><td bgcolor=#CCCCCC>
	 <table width=100% border=0 cellspacing=1 cellpadding=2>
	 <tr>
		<td bgcolor=#EEEEEE><font size=2><b>$wtitle</b></font></td>
		<td bgcolor=#EEEEEE align=right><font size=2><b>CPC</b></font></td>
		<td bgcolor=#EEEEEE align=right><font size=2><b>Clicks</b></font></td>
	 </tr>\n";

if(count($words))
{
	foreach($words as $rec)
	{
		$rec[cpc] = number_format($rec[cpc],2);
		$rec[keyword] = "<a href=ad.php?c=keywords&filter=$filter&f[id]=$rec[id]&go=update title=\"Edit Keywords\">$rec[keyword]</a>";	
		$out .= "
		<tr>
			<td bgcolor=#FFFFFF><font size=1>$rec[keyword]</font></td>
			<td bgcolor=#FFFFFF align=right><font size=1>$rec[cpc]</font></td>
			<td bgcolor=#FFFFFF align=right><font size=1>$rec[clicks]</font></td>
		</tr>\n";
	}
}

$out .= "</table></td></tr></table>\n";
lib_main($out, "Manage $wt");

?>	
