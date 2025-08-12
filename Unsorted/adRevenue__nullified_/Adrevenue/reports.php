<?


// Show detail for an item
function report_detail()
{
	global $S, $f, $user, $sql;

	$out = "";
	$excel = "";

	$header = report_header("Item Summary", "report_detail");

	if($f[ad])
		$extra  = " AND a.adid='$f[ad]'";
	if($f[item])
		$extra .= " AND a.mapid='$f[item]'"; 

	$data = lib_getsql("SELECT a.*, c.keyword FROM account a, admap b, keywords c 
				WHERE a.mapid=b.id AND b.keyword=c.id AND a.amount < 0 AND a.clientid='$user[id]' 
					AND a.date BETWEEN $f[start] AND $f[end] $extra
				ORDER BY a.date DESC LIMIT 1000");

	$rz = count($data);
	if(!$rz)
		lib_redirect("No data exists for this item.","ad.php?c=ads&ad=f[ad]",3);

	$out .= "$header<br>";
	$out .= "<div align=right>
			<font size=1 face=Verdana,Arial,Helvetica,sans-serif>
			<a href=ad.php?c=report_detail&f[item]=$f[item]&f[ad]=$f[ad]&f[start]=$f[start]&f[end]=$f[end]&f[output]=excel>
			Download Excel Spreadsheet</a>
			</font><br>&nbsp;
		</div>";
	$out .= "<table width=600 border=0 cellspacing=0 cellpadding=0>
		 <tr><td bgcolor=#CCCCCC>	
		 <table width=100% border=0 cellspacing=1 cellpadding=2>
		 <tr>
			<td><font size=1 face=Verdana,Arial,Helvetica,sans-serif><b>DATE</b></font></td>
			<td><font size=1 face=Verdana,Arial,Helvetica,sans-serif><b>ITEM</b></font></td>
			<td align=right><font size=1 face=Verdana,Arial,Helvetica,sans-serif><b>BID</b></font></td>
			<td><font size=1 face=Verdana,Arial,Helvetica,sans-serif><b>IP</b></font></td>
		 </tr>\n";

	$excel .= "DATE\tITEM\tBID\tIP\n";
	foreach($data as $rec)
	{
		$rec[date] = date("m-d-Y h:i a", $rec[date]);
		$rec[bid]  = number_format($rec[amount] * -1, 2);

		$bgcolor = iif($bgcolor == "#FFFFEE", "#FFFFFF","#FFFFEE","#FFFFFF");	

		$out .= "
			<tr>
				<td bgcolor=$bgcolor><font size=1 face=Verdana,Arial,Helvetica,sans-serif>$rec[date]</td>
				<td bgcolor=$bgcolor><font size=1 face=Verdana,Arial,Helvetica,sans-serif>$rec[keyword]</td>
				<td bgcolor=$bgcolor align=right><font size=1 face=Verdana,Arial,Helvetica,sans-serif>$rec[bid]</td>
				<td bgcolor=$bgcolor><font size=1 face=Verdana,Arial,Helvetica,sans-serif>$rec[ip]</td>
			</tr>
			";

		$excel .= "$rec[date]\t$rec[keyword]\t$rec[bid]\t$rec[ip]\n";
	}

	$out .= "</table>\n</td>\n</tr>\n</table>\n";

	if(!$f[output])
		lib_main($out);
	else
	{
		header("Content-type: application/vnd.ms-excel");
		header("Content-Disposition: attachment; filename=details.xls");
		echo $excel;
		exit;
	}

	return("");
}


// Prints out generic header for any report
function report_header($title = "Report", $c)
{
	global $f;

	// Deal with the report dates
	if(!$f[start_day] || !$f[end_day])
	{
		$f[start] = mktime(0,0,0,date("m"),1,date("Y"));
		$f[end] = mktime(23,59,59,date("m"),date("d"),date("Y"));
	}
	else
	{
 		$f[start] = mktime(0,0,0,$f[start_month],$f[start_day], $f[start_year]);
		$f[end] = mktime(23,59,59,$f[end_month],$f[end_day], $f[end_year]);
	}

	$sdate = lib_dateinput($name="start",$f[start]);
	$edate = lib_dateinput($name="end",$f[end]);

	$out .= "<font size=+1><b>$title</b></font><br><p>\n";
	$out .= "<table width=100% border=0 cellspacing=0 cellpadding>\n";
	$out .= "<form method=post>";
	#$out .= "<tr>\n";
	#$out .= "<td width=1><font size=2 face=Verdana,Arial,Helvetica,sans-serif><b>Item:</b></font></td>\n";
	#$out .= "<td>\n";
	#$out .= "<select name=f[item]>\n";
	#$out .= "$items\n";
	#$out .= "</select>\n";
	#$out .= "</td>\n";
	$out .= "</tr>\n";
	$out .= "<tr>\n";
	$out .= "<td width=1><font size=2 face=Verdana,Arial,Helvetica,sans-serif><b>Start:</b></font></td>\n";
	$out .= "<td>$sdate</td>";
	$out .= "</tr>\n";
	$out .= "<tr>\n";
	$out .= "<td width=1><font size=2 face=Verdana,Arial,Helvetica,sans-serif><b>End:</b></font></td>\n";
	$out .= "<td>$edate</td>";
	$out .= "</tr>\n";
	$out .= "<tr>\n";
	$out .= "<td width=1>&nbsp;</td>\n";
	$out .= "<td><input type=submit value=\"Run Report\"></td>\n";
	$out .= "</tr>\n";
	$out .= "<input type=hidden name=c value=\"$c\">";
	$out .= "<input type=hidden name=f[ad] value=\"$f[ad]\">";
	$out .= "<input type=hidden name=f[item] value=\"$f[item]\">";
	$out .= "</form>";
	$out .= "<table>\n";
	$out .= "<hr size=1>\n";

	return($out);
}


?>
