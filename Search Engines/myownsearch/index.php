<?
include "./config.php";
If ($search){
	include "./mysql.php";
	if ($purgedays>0){
		$purgeseconds = $purgedays * 86400;
		$olddate = strftime("%Y-%m-%d %H:%M:%S", time() - $purgeseconds);
		$sql = "delete from $table where dateentered < '$olddate'";
		$result = mysql_query($sql) or die("Failed: $sql");
	}
	$search = stripslashes($search);
	$urlquery = urlencode($search);
	if (!$strt || $strt < 0) $strt = "0";
	$sql = "select count(*) from $table where title rlike '$search' or url rlike '$search' or descr rlike '$search'";
	$reslt2 = mysql_query($sql);
	$resrw2 = mysql_fetch_row($reslt2);
	$totalresults = $resrw2[0];
	$sql = "select * from $table where title rlike '$search' or url rlike '$search' or descr rlike '$search' order by clicks desc LIMIT $strt, $maxresults";
	$result = mysql_query($sql) or die("Failed: $sql");
	$numrows = mysql_num_rows($result);
	If ($numrows==0 && $showmeta==1){
		Header("Location: web.php?search=$urlquery");
		exit;
	}
	if ($strt> 0) $prevlink = "<a href='".$mainmod."?search=$search&strt=". ($strt - $maxresults) ."'>&lt; Previous Matches</a>";
	if ($numrows==$maxresults) $nextlink = "<a href='".$mainmod."?search=$search&strt=". ($strt + $maxresults) ."'>Next Matches &gt;</a>";
	if ($prevlink && $nextlink){
		$navlinks = $prevlink." | ".$nextlink;
	}
		else
	{
		if ($prevlink) $navlinks = $prevlink;
		if ($nextlink) $navlinks = $nextlink;
	}

	if ($header) include "$header";
	print "<center><font face='$fontface' size='$fontsize'><center><br><br><FORM method=get action='$mainmod'>Search the Web: <input type=text name=search value='$search'><input type=submit value='Search'><br></FORM></center>";
	print "<font face='$fontface' size='$fontsize' color='$textcolor'>Displaying $numrows of $totalresults results matching <b>$search</b></font><br><br>";
	include "./ad.inc";
	print textad($search);
	print "<center><table width='$tablewidth' border='0' cellspacing='0' cellpadding='0'>";
	for($x=0;$x<$numrows;$x++) {
		$result_row = mysql_fetch_row($result);
		$id = $result_row[0];
		$url = $result_row[1];
		$title = $result_row[2];
		$descr = $result_row[3];
		$clicks = $result_row[4];
		$url = "out.php?id=$id";
		if ($newwin=="1") $targetwin = " target='_$id'";
		$extrastuff = "";
		if ($clicks>0) $extrastuff = "<i>($clicks Hits)</i>";
		print "<tr>
    		<td bgcolor='$color1'>&nbsp;&nbsp;<a href='$url'$targetwin><b><font face='$fontface' size='$fontsize'>$title</font></b></a><font face='$fontface' size='$fontsize'> 
      		<font color='#000000'>$extrastuff</font></font></td>
  		</tr>
  		<tr> 
    		<td bgcolor='$color2'>
      		<blockquote>
        	<p><font face='$fontface' size='$fontsize' color='$textcolor'>$descr</font></p>
      		</blockquote>
    		</td>
  		</tr>";
	}
	print "</table></center></table>";
	if ($navlinks) print "<center><font face='$fontface' size='$fontsize' color='$textcolor'><b>$navlinks</b></a></center>";
	if ($showmeta==1) print "<center><font face='$fontface' size='$fontsize' color='$textcolor'><a href='web.php?search=$urlquery'><b>Show Meta Results</b></a></center>";
	print "<center>Powered by <a href='http://nukedweb.memebot.com/' target='_nukedweb'>MyOwnSearch</a></center>";
	if ($footer) include "$footer";
}
else
{
	if ($header) include "$header";
	print "<center><font face='$fontface' size='$fontsize' color='$textcolor'><form name='form1' method='get' action='$mainmod'>Search: <input type='text' name='search' value='$search'><input type=submit value='Go!'><br></form></font></center>";
	include "./content.php";
	print "<center>Powered by <a href='http://nukedweb.memebot.com/' target='_nukedweb'>MyOwnSearch</a></center>";
	if ($footer) include "$footer";
}
?>